<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ e($subject) }}</title>
</head>
<body style="font-family: system-ui, -apple-system, 'Segoe UI', sans-serif; line-height: 1.5; color: #222;">
    <h1 style="font-size: 18px; margin: 0 0 16px;">{{ e($subject) }}</h1>
    <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; border-color: #ddd; max-width: 640px;">
        @foreach ($fields as $row)
            <tr>
                <th align="left" style="background: #f8f9fa; white-space: nowrap;">{{ e($row['label']) }}</th>
                <td style="word-break: break-word;">{!! nl2br(e($row['value'])) !!}</td>
            </tr>
        @endforeach
    </table>
    <p style="color: #666; font-size: 12px; margin-top: 20px;">
        Отправлено с сайта {{ e(config('app.name')) }} ({{ e(config('app.url')) }})
    </p>
</body>
</html>
