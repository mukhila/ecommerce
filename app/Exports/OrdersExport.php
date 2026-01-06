<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Order::with(['user', 'shippingAddress']);

        // Apply filters
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['payment_status'])) {
            $query->where('payment_status', $this->filters['payment_status']);
        }

        if (!empty($this->filters['payment_method'])) {
            $query->where('payment_method', $this->filters['payment_method']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->where('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->where('created_at', '<=', $this->filters['date_to'] . ' 23:59:59');
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Customer Email',
            'Customer Mobile',
            'Order Total',
            'Subtotal',
            'GST Amount',
            'Shipping Address',
            'Status',
            'Payment Status',
            'Payment Method',
            'Razorpay Order ID',
            'Tracking Number',
            'Order Date',
            'Last Updated',
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->user->name ?? 'Guest',
            $order->user->email ?? 'N/A',
            $order->user->mobile ?? 'N/A',
            $order->total,
            $order->subtotal,
            $order->gst_amount,
            $this->formatAddress($order->shippingAddress),
            ucfirst($order->status),
            ucfirst($order->payment_status),
            ucfirst($order->payment_method),
            $order->razorpay_order_id ?? 'N/A',
            $order->tracking_number ?? 'N/A',
            Carbon::parse($order->created_at)->format('Y-m-d H:i:s'),
            Carbon::parse($order->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    protected function formatAddress($address): string
    {
        if (!$address) {
            return 'N/A';
        }

        return implode(', ', array_filter([
            $address->address,
            $address->city,
            $address->state,
            $address->pincode,
        ]));
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
