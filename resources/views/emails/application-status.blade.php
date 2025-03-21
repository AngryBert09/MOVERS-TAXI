<!DOCTYPE html>
<html>

<head>
    <title>Application Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .header h1 {
            color: #4a90e2;
            margin: 0;
            font-size: 26px;
        }

        .content {
            padding: 20px 0;
            color: #333333;
            line-height: 1.7;
        }

        .status-box {
            background-color: #f4f4f7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        .status-box p {
            font-size: 18px;
            margin: 0;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 14px;
            color: #888888;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>
                @if ($status === 'Hired')
                    🎉 Congratulations, {{ $applicantName }}!
                @else
                    ⚡ Application Update
                @endif
            </h1>
        </div>

        <!-- Content Section -->
        <div class="content">
            <p>Dear <strong>{{ $applicantName }}</strong>,</p>

            @if ($status === 'Hired')
                <p>We are thrilled to inform you that you have been <strong>hired</strong>! We are excited to welcome
                    you to the team and will be in touch soon with the next steps.</p>
            @elseif ($status === 'Rejected')
                <p>We appreciate the time and effort you put into the application process. Unfortunately, after careful
                    consideration, we have decided to proceed with another candidate. We encourage you to apply again
                    for future opportunities.</p>
            @else
                <p>We wanted to update you regarding your application status. If you have any questions, please feel
                    free to reach out.</p>
            @endif

            <!-- Status Box -->
            <div class="status-box">
                <p><strong>Status:</strong> {{ $status }}</p>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Thank you for your interest in joining <strong>MOVERS</strong>.</p>
            <p>Best regards,</p>
            <p><strong>MOVERS Hiring Team</strong></p>
            <p>&copy; {{ date('Y') }} MOVERS. All rights reserved.</p>
        </div>
    </div>

</body>

</html>
