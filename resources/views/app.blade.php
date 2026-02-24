<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>@yield('title', 'MICRON')</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <div id="app">
        {{-- Сюда будет монтироваться Vue — все компоненты отрисуются внутри #app --}}
    </div>

    @vite(['resources/js/app.js'])
</body>
</html>