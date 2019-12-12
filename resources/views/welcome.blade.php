<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Travho
                </div>

                <div class="links">
                    <a href="https://app.swaggerhub.com/apis-docs/GarciaAlejandro/Travelers/1.0.0#/">Documentación</a>
                    <a href="https://docs.google.com/document/d/1Wp2In0jmpjy7xdZLoG7dgG4OWusw_6lMQfIC0BX3G_M/edit?usp=sharing">Manual de instalación</a>
                    <a href="https://docs.google.com/presentation/d/1x1jKikmfa5_cC1pXXKXzp4ndRq-Tjy0biDd_HEiDVx0/edit?usp=sharing">Presentación</a>
                    <a href="https://github.com/Eliezfer/Travho">Repositorio</a>
                    <a href="https://drive.google.com/file/d/1779bWg0p5Fz1k32d5vKmvwjmCtwBdihe/view?usp=sharing">Diagrama de Recursos</a>
                </div>
            </div>
        </div>
    </body>
</html>
