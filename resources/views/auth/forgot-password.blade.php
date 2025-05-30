<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password - Task Management System</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#1E1E1E] text-[#F4EBD9] min-h-screen relative overflow-hidden">

        <!-- TOP BAR -->
        <div class="w-full h-32 px-8 py-4 bg-[#D2C5A5] rounded-bl-[72px] rounded-br-[72px] flex items-end">
            <a href="{{ route('login') }}" class="flex items-center gap-2 text-zinc-800 text-base font-bold font-['Istok_Web']">
                <!-- Arrow -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>


       <!-- FORGOT PASSWORD WRAPPER -->
        <div class="flex justify-center items-center flex-grow px-4">
         <div class="bg-[#26231F] p-10 rounded-xl shadow-lg w-full max-w-md mt-20 mb-20">

          <!-- FORGOT PASSWORD INSTRUCTIONS -->
        <h1 class="text-left text-3xl font-bold text-[#ECE3D2] font-['Istok_Web']">Forgot Password?</h1>
        <p class="text-left text-base font-normal text-[#FFFFFF] font-['Istok_Web'] mb-12">
            Enter the email associated with your account
            and we’ll send a password reset link to your email to reset your password.</p>

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
                    <label for="email" class="block text-left pb-5">Email</label>
                    <input class="bg-stone-200 rounded-xl px-4 py-2 w-full" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-10">
                    <button class= "w-full h-14 p-2.5 bg-[#C7B89B] rounded-xl inline-flex justify-center items-center gap-2.5 text-stone-900/80 text-base font-bold" type="submit">
                        Send Password Reset Link
                    </button>
                </div>
            </form>

            <!-- <div>
                <a href="{{ route('login') }}">
                    Back to Login
                </a>
            </div> -->

            </div>
         </div>

             <!--BOTTOM BAR-->
            <div class="w-full h-32 bg-[#D2C5A5] rounded-tl-[72px] rounded-tr-[72px] absolute inset-x-0 bottom-0"></div>

    </body>
</html>
