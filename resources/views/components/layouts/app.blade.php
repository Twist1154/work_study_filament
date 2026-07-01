<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Workstudy Program' }}</title>

    <!-- Loads your Tailwind CSS and JavaScript assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">

<!-- Renders the Livewire component contents here -->
{{ $slot }}

</body>
</html>
