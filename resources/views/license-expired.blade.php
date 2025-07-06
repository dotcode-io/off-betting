<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Expired - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            max-width: 500px;
            margin: 20px;
        }
        .icon {
            font-size: 64px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .message {
            color: #7f8c8d;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .license-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #e74c3c;
        }
        .license-info h3 {
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .info-label {
            font-weight: 600;
            color: #34495e;
        }
        .info-value {
            color: #7f8c8d;
        }
        .status-invalid {
            color: #e74c3c;
            font-weight: 600;
        }
        .instructions {
            background: #e8f4fd;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid #3498db;
        }
        .instructions h4 {
            color: #2980b9;
            margin-top: 0;
        }
        .command {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 10px 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔒</div>
        <h1>Application License Expired</h1>
        <p class="message">
            The application license has expired and access is currently restricted.
            Please renew your license to continue using the application.
        </p>

        <div class="license-info">
            <h3>License Status</h3>
            <div class="info-row">
                <span class="info-label">Current Month:</span>
                <span class="info-value">{{ $licenseStatus['current_month'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">License Valid:</span>
                <span class="info-value status-invalid">
                    {{ $licenseStatus['is_valid'] ? 'Yes' : 'No' }}
                </span>
            </div>
            @if($licenseStatus['license_key'])
            <div class="info-row">
                <span class="info-label">License Key:</span>
                <span class="info-value">{{ $licenseStatus['license_key'] }}</span>
            </div>
            @endif
            @if($licenseStatus['expires_at'])
            <div class="info-row">
                <span class="info-label">Expires At:</span>
                <span class="info-value">{{ $licenseStatus['expires_at'] }}</span>
            </div>
            @endif
        </div>

        <div class="instructions">
            <h4>How to Renew License</h4>
            <p><small>Contact your system administrator if you need assistance.</small></p>
        </div>
    </div>
</body>
</html>
