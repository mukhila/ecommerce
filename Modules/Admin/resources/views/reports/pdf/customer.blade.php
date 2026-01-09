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
        <h3>{{ $stats['total_customers'] }}</h3>
        <p>Total Customers</p>
    </div>
    <div class="stat-box">
        <h3>{{ $stats['new_customers'] }}</h3>
        <p>New Customers</p>
    </div>
    <div class="stat-box">
        <h3>{{ $stats['retention_rate'] }}%</h3>
        <p>Retention Rate</p>
    </div>

    <h3>Top Customers by Lifetime Value</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Total Orders</th>
                <th style="text-align: right;">Lifetime Value (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topCustomers as $customer)
            <tr>
                <td>{{ $customer['name'] }}</td>
                <td>{{ $customer['email'] }}</td>
                <td>{{ $customer['total_orders'] }}</td>
                <td style="text-align: right;">₹{{ number_format($customer['lifetime_value'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
