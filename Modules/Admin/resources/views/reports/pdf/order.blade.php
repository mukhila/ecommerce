<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background-color: #f8f9fa; padding: 8px; text-align: left; border: 1px solid #ddd; }
        table td { padding: 8px; border: 1px solid #ddd; }
        .stat-box { display: inline-block; width: 23%; padding: 10px; margin: 5px; text-align: center; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p><strong>Period:</strong> {{ $period }}</p>
    </div>

    <div class="stat-box">
        <h3>{{ $stats['total_orders'] }}</h3>
        <p>Total Orders</p>
    </div>
    <div class="stat-box">
        <h3>{{ $stats['delivered_orders'] }}</h3>
        <p>Delivered</p>
    </div>
    <div class="stat-box">
        <h3>{{ $stats['cancelled_orders'] }}</h3>
        <p>Cancelled</p>
    </div>
    <div class="stat-box">
        <h3>{{ $stats['fulfillment_rate'] }}%</h3>
        <p>Fulfillment Rate</p>
    </div>

    <h3>Order Status Distribution</h3>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th style="text-align: center;">Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statusDistribution['labels'] as $index => $label)
            <tr>
                <td>{{ $label }}</td>
                <td style="text-align: center;">{{ $statusDistribution['values'][$index] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
