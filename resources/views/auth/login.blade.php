<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - Task Management System</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div>
            <h1>Login</h1>

            @if(session('error'))
                <div role="alert">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                    @error('password')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div>
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember Me</label>
                    </div>

                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <div>
                    <button type="submit">
                        Login
                    </button>
                </div>
            </form>

            @if(Route::has('register'))
                <div>
                    <p>
                        Don't have an account?
                        <a href="{{ route('register') }}">
                            Register here
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </body>
</html>
