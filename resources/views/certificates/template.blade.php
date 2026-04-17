<!DOCTYPE html>
<html>
<head>
    <title>Intern Certificate</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f9ff;
            color: #00204F;
        }
        
        .certificate-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 32, 79, 0.1);
            position: relative;
            overflow: hidden;
            border: 15px solid #f5f9ff;
        }
        
        .watermark {
            position: absolute;
            opacity: 0.05;
            font-size: 180px;
            font-weight: bold;
            color: #0077cc;
            transform: rotate(-30deg);
            top: 30%;
            left: 10%;
            z-index: 1;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }
        
        .logo {
            height: 80px;
            margin-bottom: 20px;
        }
        
        h1 {
            font-size: 36px;
            margin: 0;
            color: #00204F;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .subtitle {
            font-size: 18px;
            color: #0077cc;
            margin-top: 10px;
            font-weight: 300;
        }
        
        .divider {
            height: 3px;
            background: linear-gradient(90deg, transparent, #0077cc, transparent);
            margin: 30px 0;
            border: none;
        }
        
        .certificate-body {
            text-align: center;
            position: relative;
            z-index: 2;
            padding: 0 50px;
        }
        
        .certificate-text {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .recipient-name {
            font-size: 32px;
            font-weight: 700;
            color: #0077cc;
            margin: 30px 0;
            padding: 15px 0;
            border-top: 2px dashed #0077cc;
            border-bottom: 2px dashed #0077cc;
        }
        
        .details {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        
        .signature {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            width: 200px;
            height: 1px;
            background: #00204F;
            margin: 40px auto 10px;
        }
        
        .signature-name {
            font-weight: 600;
            margin-top: 5px;
        }
        
        .signature-title {
            font-size: 14px;
            color: #666;
        }
        
        .footer {
            margin-top: 50px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        
        .decoration {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(0, 119, 204, 0.1);
            z-index: 1;
        }
        
        .decoration-1 {
            top: 50px;
            left: -30px;
            width: 150px;
            height: 150px;
        }
        
        .decoration-2 {
            bottom: 80px;
            right: -40px;
            width: 200px;
            height: 200px;
        }
        
        .date {
            margin-top: 30px;
            font-size: 16px;
            color: #666;
        }
        
        .badge {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background: #0077cc;
            color: white;
            border-radius: 50px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Decorative elements -->
        <div class="watermark">CERTIFICATE</div>
        <div class="decoration decoration-1"></div>
        <div class="decoration decoration-2"></div>
        
        <!-- Header with logo -->
        <div class="header">
         
            <h1>Certificate of Completion</h1>
            <div class="subtitle">Internship Program</div>
        </div>
        
        <hr class="divider">
        
        <!-- Certificate body -->
        <div class="certificate-body">
            <p class="certificate-text">This is to certify that</p>
            
            <div class="recipient-name">{{ $intern->certificate_name }}</div>
            
            <p class="certificate-text">has successfully completed the internship program at</p>
            <p class="certificate-text"><strong>Sri Lanka Telecom - Digital Platforms Division</strong></p>
            
            <p class="certificate-text">from {{ \Carbon\Carbon::parse($intern->training_start_date)->format('F d, Y') }}
               to {{ \Carbon\Carbon::parse($intern->training_end_date)->format('F d, Y') }}.</p>
            
            <div class="badge">
                <i class="fas fa-award"></i> Successful Completion
            </div>
            
            <div class="date">
                Generated on {{ \Carbon\Carbon::parse($intern->certificate_generated_at)->format('F d, Y') }}
            </div>
        </div>
        
        <!-- Signatures section -->
        <div class="details">
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">[Supervisor's Name]</div>
                <div class="signature-title">Internship Supervisor</div>
            </div>
            
            
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Certificate ID: {{ $intern->reg_no }}-{{ \Carbon\Carbon::parse($intern->certificate_generated_at)->format('Ymd') }}</p>
            <p>© 2025 Sri Lanka Telecom IT - Digital Platforms. All rights reserved.</p>
        </div>
    </div>
</body>
</html>