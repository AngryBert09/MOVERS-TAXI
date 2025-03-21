<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Your Inquiry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #007BFF;
        }

        .email-body {
            font-size: 16px;
            line-height: 1.6;
        }

        .reply-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            text-align: center;
        }

        .reply-section a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .reply-section a:hover {
            background-color: #0056b3;
        }

        .email-footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            Reply to Your Inquiry
        </div>
        <div class="email-body">
            <p>Dear Sir/Madam,</p>
            <p>Thank you for reaching out to us. Here is our reply to your inquiry:</p>
            <blockquote
                style="background-color: #f9f9f9; border-left: 5px solid #007BFF; padding: 10px; margin: 10px 0;">
                {{ $replyMessage }}
            </blockquote>
            <p>If you have any further questions, feel free to contact us.</p>

            <!-- Reply Button -->
            <div class="reply-section">
                <p><strong>Need further assistance?</strong></p>
                <a href="mailto:support@yourcompany.com?subject=Follow-up on Inquiry">Reply to this email</a>
            </div>

            <p>Best regards,</p>
            <p><strong>MOVERS HR TEAM</strong></p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Movers HR. All rights reserved.
        </div>
    </div>
</body>

</html>
