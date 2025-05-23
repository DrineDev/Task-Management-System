<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password - Task Management System</title>
    </head>
    <body>
        <div>
            <h1>Forgot Password</h1>

            @if(session('status'))
                <div role="alert">
                    <span>{{ session('status') }}</span>
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

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div>
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit">
                        Send Password Reset Link
                    </button>
                </div>
            </form>

            <div>
                <a href="{{ route('login') }}">
                    Back to Login
                </a>
            </div>
        </div>
    </body>
</html> 