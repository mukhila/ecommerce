<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background-color: #f8f9fa; padding: 8px; text-align: left; border: 1px solid #ddd; font-weight: bold; }
        table td { padding: 8px; border: 1px solid #ddd; }
        .section-title { font-size: 16px; font-weight: bold; margin: 20px 0 10px 0; padding-bottom: 5px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p><strong>Period:</strong> {{ $period }}</p>
        <p><strong>Generated:</strong> {{ $generated_at }}</p>
    </div>

    <div class="section-title">Top Selling Products</div>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Product Name</th>
                <th style="text-align: center;">Units Sold</th>
                <th style="text-align: right;">Revenue (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts['products'] as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product['name'] }}</td>
                <td style="text-align: center;">{{ $product['total_sold'] }}</td>
                <td style="text-align: right;">₹{{ number_format($product['total_revenue'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Inventory Alerts</div>
    @if(count($lowStock) > 0)
    <p><strong>Low Stock Items:</strong> {{ count($lowStock) }}</p>
    @endif
    @if(count($outOfStock) > 0)
    <p><strong>Out of Stock Items:</strong> {{ count($outOfStock) }}</p>
    @endif
</body>
</html>
