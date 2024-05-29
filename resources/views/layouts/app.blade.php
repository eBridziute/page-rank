<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @vite(['resources/sass/app.scss'])

        <title>@yield('title')</title>
    </head>
    <body>
        <main>
            <div class="container my-5">
                @yield('content')
            </div>
        </main>
    </body>
</html>
