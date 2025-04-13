<!DOCTYPE html>
<html>

<head>
    <title>Two-Factor Authentication Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .email-header h1 {
            color: #333;
            font-size: 24px;
        }

        .email-body p {
            color: #555;
            line-height: 1.6;
        }

        .email-body strong {
            color: #000;
        }

        .email-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🔒 Two-Factor Authentication Code</h1>
        </div>
        <div class="email-body">
            <p>👋 Hello,</p>
            <p>Your 2FA verification code is: <strong>{{ $code }}</strong> 🔑</p>
            <p>⏳ This code will expire in 3 minutes.</p>
            <p>If you did not request this, please ignore this email. 🚫</p>
        </div>
        <div class="email-footer">
            <p>🙏 Thank you for using our service.</p>
            <p>💼 Movers HR</p>
        </div>
    </div>
    </div>
</body>

</html>
