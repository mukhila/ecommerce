<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .stat-box h3 {
            margin: 0;
            font-size: 20px;
            color: #556ee6;
        }
        .stat-box p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #f8f9fa;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p><strong>Period:</strong> {{ $period }}</p>
        <p><strong>Generated:</strong> {{ $generated_at }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <h3>₹{{ number_format($stats['total_revenue'], 2) }}</h3>
            <p>Total Revenue</p>
        </div>
        <div class="stat-box">
            <h3>{{ number_format($stats['order_count']) }}</h3>
            <p>Total Orders</p>
        </div>
        <div class="stat-box">
            <h3>₹{{ number_format($stats['average_order_value'], 2) }}</h3>
            <p>Avg Order Value</p>
        </div>
        <div class="stat-box">
            <h3>{{ $stats['growth_percentage'] }}%</h3>
            <p>Growth Rate</p>
        </div>
    </div>

    <div class="section-title">GST Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Tax Rate</th>
                <th style="text-align: right;">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gstBreakdown['labels'] as $index => $label)
            <tr>
                <td>{{ $label }}</td>
                <td style="text-align: right;">₹{{ number_format($gstBreakdown['values'][$index], 2) }}</td>
            </tr>
            @endforeach
            <tr style="font-weight: bold; background-color: #f8f9fa;">
                <td>Total GST</td>
                <td style="text-align: right;">₹{{ number_format(array_sum($gstBreakdown['values']), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Payment Method Distribution</div>
    <table>
        <thead>
            <tr>
                <th>Payment Method</th>
                <th style="text-align: center;">Orders</th>
                <th style="text-align: right;">Revenue (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentDistribution['labels'] as $index => $label)
            <tr>
                <td>{{ $label }}</td>
                <td style="text-align: center;">{{ $paymentDistribution['orders'][$index] }}</td>
                <td style="text-align: right;">₹{{ number_format($paymentDistribution['revenue'][$index], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer-generated report. Generated using Admin Dashboard.</p>
        <p>© {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</body>
</html>
