<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-unpaid
                            {--dry-run : Run without actually cancelling orders}
                            {--limit= : Maximum number of orders to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel unpaid orders that have exceeded the payment deadline (7 days)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $limit = $this->option('limit');

        $this->info('Starting unpaid order cancellation process...');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No orders will be cancelled');
        }

        // Query expired unpaid orders
        $query = Order::expiredUnpaid()
            ->with(['items.product', 'user', 'shippingAddress']);

        if ($limit) {
            $query->limit((int) $limit);
        }

        $expiredOrders = $query->get();
        $totalOrders = $expiredOrders->count();

        if ($totalOrders === 0) {
            $this->info('No expired unpaid orders found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$totalOrders} expired unpaid order(s) to cancel.");

        $successCount = 0;
        $failureCount = 0;
        $skippedCount = 0;

        // Progress bar for better UX
        $progressBar = $this->output->createProgressBar($totalOrders);
        $progressBar->start();

        foreach ($expiredOrders as $order) {
            try {
                // Validate order state before cancellation
                if ($order->status === 'shipped') {
                    $this->newLine();
                    $this->warn("Order {$order->order_number} is shipped - skipping cancellation");
                    $skippedCount++;
                    $progressBar->advance();
                    continue;
                }

                if ($order->status === 'processing') {
                    $this->newLine();
                    $this->warn("Order {$order->order_number} is being processed - skipping cancellation");
                    $skippedCount++;
                    $progressBar->advance();
                    continue;
                }

                if (!$isDryRun) {
                    $reason = "Order automatically cancelled due to non-payment within 7 days";

                    if ($order->cancelOrder($reason)) {
                        $successCount++;
                    } else {
                        $failureCount++;
                    }
                } else {
                    // Dry run - just count
                    $successCount++;
                    $this->newLine();
                    $this->line("Would cancel: {$order->order_number} (Created: {$order->created_at->format('Y-m-d H:i')})");
                }

            } catch (\Exception $e) {
                $failureCount++;
                $this->newLine();
                $this->error("Failed to cancel order {$order->order_number}: {$e->getMessage()}");

                Log::error('Order cancellation failed', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('=== Cancellation Summary ===');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Found', $totalOrders],
                ['Successfully Cancelled', $successCount],
                ['Failed', $failureCount],
                ['Skipped', $skippedCount],
            ]
        );

        // Log summary
        Log::info('Unpaid order cancellation completed', [
            'total' => $totalOrders,
            'success' => $successCount,
            'failed' => $failureCount,
            'skipped' => $skippedCount,
            'dry_run' => $isDryRun
        ]);

        return $failureCount > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
