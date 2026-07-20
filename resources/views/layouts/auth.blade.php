<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: (function() {
        const stored = localStorage.getItem('darkMode');
        return stored !== null ?
            stored === 'true' :
            window.matchMedia('(prefers-color-scheme: dark)').matches;
    })(),
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        document.documentElement.classList.toggle('dark', this.darkMode);
    }
}" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }} · Knitting</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    <script>
        function applyDarkMode() {
            const stored = localStorage.getItem('darkMode');
            let darkMode;
            if (stored === null) {
                darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            } else {
                darkMode = stored === 'true';
            }
            document.documentElement.classList.toggle('dark', darkMode);
        }
        applyDarkMode();
    </script>
</head>

<body class="layout-auth flex items-center justify-center p-4">
    {{ $slot }}

    @livewireScripts

    <script>
        document.addEventListener('livewire:navigated', applyDarkMode);
    </script>
</body>

</html>
