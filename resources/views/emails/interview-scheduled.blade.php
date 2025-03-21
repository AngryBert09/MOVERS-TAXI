<!DOCTYPE html>
<html>

<head>
    <title>Interview Invitation</title>
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

        .details {
            background-color: #f4f4f7;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e1e1e1;
        }

        .details p {
            margin: 8px 0;
            font-size: 16px;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            background-color: #4a90e2;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
        }

        .btn:hover {
            background-color: #357abd;
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
            <h1>You're Invited to an Interview 🎯</h1>
        </div>

        <!-- Content Section -->
        <div class="content">
            <p>Dear <strong>{{ $applicantName }}</strong>,</p>
            <p>We are excited to invite you to an interview for the position you applied for at <strong>MOVERS</strong>.
                We were impressed with your application and look forward to discussing your potential contribution to
                our team.</p>

            <!-- Interview Details -->
            <div class="details">
                <p><strong>📅 Date:</strong> {{ $interviewDate }}</p>
                <p><strong>⏰ Time:</strong> {{ $interviewTime }}</p>
                <p><strong>📍 Location:</strong>
                    BLOCK 23 LOT 20 BAUTISTA ST. MALIGAYA PARK CAMARIN CALOOCAN CITY
                </p>
            </div>

            {{-- <p>Please confirm your availability by clicking the button below. If the proposed time is inconvenient, feel
                free to request a reschedule.</p>

            <!-- Confirmation Button -->
            <div class="btn-container">
                <a href="#" class="btn">Confirm Interview</a>
            </div> --}}
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>If you have any questions or need assistance, please do not hesitate to reach out.</p>
            <p>Best regards,</p>
            <p><strong>MOVERS Hiring Team</strong></p>
            <p>&copy; {{ date('Y') }} MOVERS. All rights reserved.</p>
        </div>
    </div>

</body>

</html>
