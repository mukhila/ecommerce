<?php

namespace App\Exports;

use Modules\Product\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Product::with('category');

        // Apply filters
        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (!empty($this->filters['stock_status'])) {
            switch ($this->filters['stock_status']) {
                case 'in_stock':
                    $query->where('stock', '>', 10);
                    break;
                case 'low_stock':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'out_of_stock':
                    $query->where('stock', '=', 0);
                    break;
            }
        }

        if (isset($this->filters['is_active'])) {
            $query->where('is_active', $this->filters['is_active']);
        }

        if (isset($this->filters['is_featured'])) {
            $query->where('is_featured', $this->filters['is_featured']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Product ID',
            'Name',
            'SKU',
            'Slug',
            'Category',
            'Price',
            'Discounted Price',
            'GST Rate (%)',
            'Stock',
            'Is Active',
            'Is Featured',
            'Created At',
            'Updated At',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->sku,
            $product->slug,
            $product->category->name ?? 'N/A',
            $product->price,
            $product->discounted_price ?? 'N/A',
            $product->gst_rate,
            $product->stock,
            $product->is_active ? 'Yes' : 'No',
            $product->is_featured ? 'Yes' : 'No',
            Carbon::parse($product->created_at)->format('Y-m-d H:i:s'),
            Carbon::parse($product->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
