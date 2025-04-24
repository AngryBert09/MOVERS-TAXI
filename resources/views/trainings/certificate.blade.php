<!DOCTYPE html>
<html>

<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/titlelogo.png') }}">


    <title>Certificate of Achievement</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            padding: 40px;
            background-color: #f9f9f9;
        }

        .certificate {
            border: 15px double #2c3e50;
            padding: 60px 40px;
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* .certificate:before {
            content: "";
            position: absolute;
            top: 20px;
            bottom: 20px;
            left: 20px;
            right: 20px;
            border: 1px solid #2c3e50;
            pointer-events: none;
        } */

        .cert-title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .cert-name {
            font-size: 32px;
            margin: 30px 0;
            color: #2980b9;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cert-body {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .cert-footer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            width: 40%;
            border-top: 1px solid #333;
            padding-top: 10px;
            margin-top: 60px;
        }

        .seal {
            position: absolute;
            top: 20px;
            right: 20px;
            opacity: 0.8;
            width: 80px;
        }

        .cert-message {
            font-style: italic;
            margin: 20px 0;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <img src="{{ asset('assets/img/moverslogo.png') }}" class="seal" alt="MOVERS">

        <div class="cert-title">Certificate of Achievement</div>

        <div class="cert-body">
            This is to certify that
            <div class="cert-name">{{ $employee['first_name'] }} {{ $employee['last_name'] }}</div>
            has successfully completed and demonstrated proficiency in
            <strong>{{ $achievement->type }}</strong><br>
            conducted by <strong>{{ $achievement->training_provider }}</strong>.
        </div>

        <div class="cert-message">
            This achievement recognizes the dedication, effort, and commitment to professional development
            demonstrated throughout the training program.
        </div>

        <div class="cert-body">
            Date of Completion:
            <strong>{{ \Carbon\Carbon::parse($achievement->training_date)->format('F j, Y') }}</strong>
        </div>

        <div class="cert-footer">
            <div class="signature">
                <strong>Training Director</strong><br>
                {{ $achievement->training_provider }}
            </div>
            <div class="signature">
                <strong>Authorized Signature</strong><br>

            </div>
        </div>
    </div>
</body>

</html>
