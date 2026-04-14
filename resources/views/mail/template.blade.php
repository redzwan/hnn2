<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $renderedSubject }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f4f4f5; color: #18181b; line-height: 1.6; }
        .wrapper { max-width: 620px; margin: 40px auto; padding: 0 16px; }
        .card { background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .header { background: #18181b; padding: 28px 36px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: -0.3px; }
        .body { padding: 36px; }
        .body p { margin-bottom: 16px; font-size: 15px; color: #3f3f46; }
        .body h1, .body h2, .body h3 { margin-bottom: 12px; color: #18181b; }
        .body a { color: #6366f1; }
        .body ul, .body ol { margin: 12px 0 16px 20px; }
        .body li { font-size: 15px; color: #3f3f46; margin-bottom: 4px; }
        .body table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 14px; }
        .body table th { background: #f4f4f5; padding: 8px 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e4e4e7; }
        .body table td { padding: 8px 12px; border-bottom: 1px solid #f4f4f5; }
        .btn { display: inline-block; margin: 8px 0 20px; padding: 12px 28px; background: #18181b; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-size: 15px; font-weight: 600; }
        .footer { padding: 20px 36px; text-align: center; font-size: 12px; color: #a1a1aa; border-top: 1px solid #f4f4f5; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>{{ config('app.name') }}</h1>
            </div>
            <div class="body">
                {!! $renderedBody !!}
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                This email was sent to you as a registered user of our store.
            </div>
        </div>
    </div>
</body>
</html>
