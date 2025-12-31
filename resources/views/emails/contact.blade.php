<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة جديدة من موقع E7lal</title>
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
        .field {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #159895;
        }
        .field-label {
            font-weight: bold;
            color: #1a5f7a;
            display: block;
            margin-bottom: 5px;
        }
        .field-value {
            color: #333;
        }
        .message-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #159895;
            white-space: pre-wrap;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ffeaa7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>رسالة جديدة من موقع E7lal.com</h2>
            <p>تم استلام رسالة جديدة من نموذج التواصل</p>
        </div>

        <div class="content">
            <div class="warning">
                <strong>تنبيه:</strong> هذه الرسالة تم إرسالها من نموذج التواصل على الموقع. يرجى الرد عليها من خلال البريد الإلكتروني للمرسل.
            </div>

            <div class="field">
                <span class="field-label">الاسم:</span>
                <span class="field-value">{{ $name }}</span>
            </div>

            <div class="field">
                <span class="field-label">رقم الهاتف:</span>
                <span class="field-value">{{ $phone }}</span>
            </div>

            <div class="field">
                <span class="field-label">البريد الإلكتروني:</span>
                <span class="field-value">{{ $email }}</span>
            </div>

            <div class="field">
                <span class="field-label">الموضوع:</span>
                <span class="field-value">{{ $subject }}</span>
            </div>

            <div class="field">
                <span class="field-label">الرسالة:</span>
                <div class="message-content">{{ $message }}</div>
            </div>

            <div class="field">
                <span class="field-label">معلومات إضافية:</span>
                <span class="field-value">
                    IP: {{ $ip }}<br>
                    المتصفح: {{ $user_agent }}
                </span>
            </div>
        </div>

        <div class="footer">
            <p>
                هذه الرسالة تم إرسالها تلقائياً من موقع E7lal.com<br>
                يرجى عدم الرد على هذا البريد الإلكتروني مباشرة
            </p>
        </div>
    </div>
</body>
</html>