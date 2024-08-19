<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8" />
    <title>{{ __('auth.login') }} - {{ config('other.title') }}</title>
    @section('meta')
        <meta name="description"
            content="{{ __('auth.login-now-on') }} {{ config('other.title') }} . {{ __('auth.not-a-member') }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta property="og:title" content="{{ __('auth.login') }}" />
        <meta property="og:site_name" content="{{ config('other.title') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="{{ url('/img/og.png') }}" />
        <meta property="og:description" content="{{ config('unit3d.powered-by') }}" />
        <meta property="og:url" content="{{ url('/') }}" />
        <meta property="og:locale" content="{{ config('app.locale') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @show
        <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon" />
        <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon" />
        @vite('resources/sass/pages/_auth.scss')
</head>
<body><!-- Se agrega CSS para centrar nombre de ususario y contraseña en caja de texto -->
    <style>
        #username {
            text-align: center;
        }
    </style>

    <style>
        #password {
            text-align: center;
        }
    </style>
    
    <!-- Do NOT Change! For Jackett Support -->
    <div class="Jackett" style="display: none">{{ config('unit3d.powered-by') }}</div>
    <!-- Do NOT Change! For Jackett Support -->
    <main>
        <section class="auth-form">
            <form class="auth-form__form" method="POST" action="{{ route('login') }}">
                @csrf
                <a class="auth-form__branding" href="{{ route('home.index') }}">
                    <i class="fal fa-tv-retro"></i>
                    <img class="auth-form__site-logo-lateam" src="{{ url('/img/logo.png') }}" alt="LaTeam" />
                </a>
                @if (Session::has('warning') || Session::has('success') || Session::has('info'))
                    <ul class="auth-form__important-infos">
                        @if (Session::has('warning'))
                            <li class="auth-form__important-info">
                                Warning: {{ Session::get('warning') }}
                            </li>
                        @endif

                        @if (Session::has('info'))
                            <li class="auth-form__important-info">
                                Info: {{ Session::get('info') }}
                            </li>
                        @endif

                        @if (Session::has('success'))
                            <li class="auth-form__important-info">
                                Success: {{ Session::get('success') }}
                            </li>
                        @endif
                    </ul>
                @endif

                <p class="auth-form__text-input-group">
                    <label class="auth-form__label" for="username">
                        {{-- {{ __('auth.username') }} --}}
                    </label>
                    <input id="username" class="auth-form__text-input" autocomplete="username" autofocus
                        name="username" required type="text" placeholder="Username" value="{{ old('username') }}" />
                </p>
                <p class="auth-form__text-input-group">
                    <label class="auth-form__label" for="password">
                        {{-- {{ __('auth.password') }} --}}
                    </label>
                    <input id="password" class="auth-form__text-input" autocomplete="current-password" name="password"
                        required type="password" placeholder="Password" />
                </p>
                <p class="auth-form__checkbox-input-group">
                    <input id="remember" class="auth-form__checkbox-input" name="remember"
                        {{ old('remember') ? 'checked' : '' }} type="checkbox" />
                    <label class="auth-form__label" for="remember">
                        {{ __('auth.remember-me') }}
                    </label>
                </p>
                @if (config('captcha.enabled'))
                    @hiddencaptcha
                @endif
                <div class="auth-form__button-container">
                    <button class="auth-form__primary-button">LOGIN</button>

                    @if (Session::has('errors'))
                        <ul class="auth-form__errors">
                            @foreach ($errors->all() as $error)
                                <li class="auth-form__error">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    </div>
            </form>
            <footer class="auth-form__footer">
                @if (!config('other.invite-only'))
                    <a class="auth-form__footer-item" href="{{ route('register') }}">
                        {{ __('auth.signup') }}
                    </a>
                @elseif (config('other.application_signups'))
                    <a class="auth-form__footer-item" href="{{ route('application.create') }}">
                        {{ __('auth.apply') }}
                    </a>
                    @endif
                <a class="auth-form__footer-item" href="{{ route('password.request') }}">
                    {{ __('auth.lost-password') }}
                </a>
                <div class="discord-div">
                    <a class="discord-widget" href="https://discord.gg/RUKj5JfEST" title="Join us on Discord">
                        <img src="https://discordapp.com/api/guilds/838217297478680596/embed.png?style=banner3">
                    </a>
                </div>
            </footer>
        </section>
    </main>
</body>
</html>
