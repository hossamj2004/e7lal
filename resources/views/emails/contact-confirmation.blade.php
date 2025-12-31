<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم استلام رسالتك - E7lal</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1a5f7a, #159895);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            text-align: center;
            margin: -30px -30px 30px -30px;
        }
        .content {
            margin-bottom: 30px;
        }
        .success-icon {
            text-align: center;
            margin-bottom: 20px;
        }
        .success-icon i {
            font-size: 4rem;
            color: #28a745;
        }
        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
            margin-bottom: 20px;
            text-align: center;
        }
        .contact-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .contact-info h4 {
            color: #1a5f7a;
            margin-bottom: 15px;
        }
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            gap: 10px;
        }
        .contact-item i {
            color: #159895;
            width: 20px;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #1a5f7a, #159895);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>مرحباً {{ $name }}</h2>
            <p>تم استلام رسالتك بنجاح</p>
        </div>

        <div class="success-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <div class="content">
            <div class="message">
                <strong>شكراً لتواصلك معنا!</strong><br>
                تم استلام رسالتك بنجاح وفريقنا سيقوم بالرد عليك في أقرب وقت ممكن.
            </div>

            <div class="contact-info">
                <h4>معلومات الرسالة المرسلة:</h4>
                <div class="contact-item">
                    <i class="bi bi-envelope"></i>
                    <strong>الموضوع:</strong> {{ $subject }}
                </div>
                <div class="contact-item">
                    <i class="bi bi-clock"></i>
                    <strong>تاريخ الإرسال:</strong> {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>

            <p style="text-align: center; margin: 20px 0;">
                إذا كان لديك أي استفسارات إضافية، لا تتردد في التواصل معنا:
            </p>

            <div style="text-align: center;">
                <a href="https://wa.me/201220437090" class="cta-button">
                    <i class="bi bi-whatsapp me-2"></i>تواصل عبر الواتساب
                </a>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <p><strong>أو اتصل بنا مباشرة:</strong></p>
                <p>📞 01220437090</p>
                <p>📧 info@e7lal.com</p>
            </div>
        </div>

        <div class="footer">
            <p>
                مع تحيات فريق E7lal.com<br>
                المتخصص في تبديل السيارات
            </p>
        </div>
    </div>
</body>
</html>