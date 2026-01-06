<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class CustomersExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = User::withCount('orders')
            ->withSum('orders as total_spent', 'total');

        // Apply filters
        if (!empty($this->filters['date_from'])) {
            $query->where('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->where('created_at', '<=', $this->filters['date_to'] . ' 23:59:59');
        }

        if (!empty($this->filters['min_spent'])) {
            $query->having('total_spent', '>=', $this->filters['min_spent']);
        }

        if (!empty($this->filters['max_spent'])) {
            $query->having('total_spent', '<=', $this->filters['max_spent']);
        }

        if (!empty($this->filters['min_orders'])) {
            $query->having('orders_count', '>=', $this->filters['min_orders']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Customer ID',
            'Name',
            'Email',
            'Mobile',
            'Total Orders',
            'Total Spent',
            'Email Verified',
            'Registered Date',
            'Last Updated',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->name,
            $customer->email,
            $customer->mobile ?? 'N/A',
            $customer->orders_count,
            round($customer->total_spent ?? 0, 2),
            $customer->email_verified_at ? 'Yes' : 'No',
            Carbon::parse($customer->created_at)->format('Y-m-d H:i:s'),
            Carbon::parse($customer->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
