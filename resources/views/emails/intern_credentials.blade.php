<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SLT Internship Portal Access</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #1e3a8a;
            padding: 25px 0;
            text-align: center;
        }
        .logo {
            height: 60px;
        }
        .content {
            padding: 30px;
        }
        h1 {
            color: #1e3a8a;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .credentials-box {
            background-color: #f0f4ff;
            border-left: 4px solid #1e3a8a;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .credentials-box p {
            margin: 8px 0;
        }
      .button {
            display: inline-block;
            background-color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: bold;
            margin: 15px 0;
            border: 2px solid blue;
            color: blue;
        }


        .warning-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 25px 0;
            border-radius: 0 4px 4px 0;
        }

        .footer {
            background-color: #1e3a8a;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #93c5fd;
            text-decoration: none;
        }
        .signature {
            margin-top: 25px;
        }
        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="https://i.postimg.cc/2874YvsJ/logo.png" alt="SLT Logo" class="logo">
        </div>

        <div class="content">
            <h1>Welcome to SLT Internship Portal</h1>

            <p>Dear Intern,</p>

            <p>We are pleased to welcome you to the SLT Internship Program. Your account has been successfully created in our internship management system, providing you access to program resources, schedules, and mentorship tools.</p>

            <div class="credentials-box">
                <p><strong>Registration Number:</strong> {{ $regNo }}</p>
                <p><strong>Temporary Password:</strong> {{ $initialPassword }}</p>
            </div>

            <p>For security purposes, please reset your password immediately after your first login:</p>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ url('password/reset/' . $token) . '?email=' . urlencode($email) }}" class="button">
                    Reset Your Password
                </a>
            </div>

            <div class="warning-box">
                <p><strong>Security Notice:</strong> This password reset link will expire in 24 hours. If not used within this timeframe, you'll need to request a new link from the login page.</p>
            </div>


            <div class="divider"></div>

            <p>If you experience any technical difficulties accessing your account, please contact our support team:</p>
            <p><strong>Email:</strong> internshipsupport@slt.lk<br>
            <strong>Phone:</strong> +94 11 2 345 678 (9:00 AM - 5:00 PM, Monday-Friday)</p>

            <div class="signature">
                <p>Best regards,</p>
                <p><strong>SLT Internship Program Office</strong><br>
                Human Resources Development Division<br>
                Sri Lanka Telecom PLC</p>
            </div>
        </div>

        <div class="footer">
            <p>© 2023 Sri Lanka Telecom. All Rights Reserved.</p>
            <p>No. 28, Lotus Road, Colombo 01, Sri Lanka</p>
            <p><a href="https://www.slt.lk">www.slt.lk</a> | <a href="https://www.slt.lk/contact">Contact Us</a></p>
            <p style="color: #bfdbfe; margin-top: 15px;">This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
