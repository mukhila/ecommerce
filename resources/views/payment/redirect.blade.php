<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to Payment Gateway...</title>
</head>
<body onload="document.forms['paymentForm'].submit()">
    <div style="text-align: center; margin-top: 20%;">
        <h3>Redirecting to Payment Gateway...</h3>
        <p>Please do not refresh this page.</p>
        <form name="paymentForm" action="{{ $url }}" method="POST">
            @foreach($params as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
    </div>
</body>
</html>
