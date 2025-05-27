<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - Task Management System</title>
        @vite(['resources/css/login.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#1E1E1E] text-[#F4EBD9] min-h-screen relative overflow-hidden">

    <!-- TOP BAR -->
        <div class="w-full h-32 px-8 py-4 bg-[#D2C5A5] rounded-bl-[72px] rounded-br-[72px] inline-flex flex-col justify-end items-center gap-2.5">
            <div class="w-44 inline-flex justify-between items-center">
                <a href="{{ route ('register') }}" class="justify-start text-zinc-800 text-base font-bold font-['Istok_Web']">Sign Up</a>
                <a href="/login" class="justify-start text-zinc-800 text-base font-bold font-['Istok_Web'] underline">Log In</a>
            </div>
        </div>

    <!-- WELCOME BACK -->
        <h1 class="text-center text-3xl font-bold text-stone-200 font-['Istok_Web'] mt-16 mb-1.5">Welcome Back!</h1>

    <!-- LOGIN FORM WRAPPER -->
     <div class="flex justify-center">
         <div class="bg-[#26231F] p-10 rounded-xl shadow-lg w-full max-w-md">

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
                    <label for="email" class="block text-left pb-3.5">Email</label>
                    <input class="bg-stone-200 rounded-xl px-4 py-2 w-full" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-left pb-3.5 pt-5">Password</label>
                    <input  class="bg-stone-200 rounded-xl px-4 py-2 w-full" id="password" type="password" name="password" required>
                    @error('password')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="block text-left pt-5 pb-5">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember Me</label>
                    </div>


                <div>
                    <button class="w-full h-14 p-2.5 bg-[#C7B89B] rounded-xl inline-flex justify-center items-center gap-2.5 text-stone-900/80 text-base font-bold"type="submit">
                        Login
                    </button>
                </div>
            </form>

              @if(Route::has('password.request'))
                <div class="text-sm mt-4 text-left">
                    <span class="text-white">Forgotten your password?</span>
                    <a href="{{ route('password.request') }}" class="text-[#A49373] hover:underline">
                            Reset password.
                        </a>
                </div>
                    @endif

                <div class="flex items-center gap-3 pt-5">
                    <div class="flex-grow border-t border-white"></div>
                    <div class="text-white text-xs font-normal font-['Istok_Web']">or sign in with</div>
                    <div class="flex-grow border-t border-white"></div>
                </div>

              <div class="flex justify-center items-center gap-4 mt-6">
                <!-- Google Button -->
                <div class="relative w-40 h-9">
                    <button class="w-full h-full flex items-center justify-center gap-3 px-4 py-2 bg-white rounded-full shadow-md">
                        <img src="https://www.google.com/favicon.ico" alt="Google" class="w-4 h-4">
                        <span class="text-xs font-bold text-zinc-800 font-['Istok_Web']">Google</span>
                    </button>
                </div>

                <!-- Facebook Button -->
                <div class="relative w-40 h-9">
                    <button class="w-full h-full flex items-center justify-center gap-3 px-4 py-2 bg-white rounded-full shadow-md">
                      <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook" class="w-4 h-4">
                        <span class="text-xs font-bold text-zinc-800 font-['Istok_Web']">Facebook</span>
                    </button>
                </div>
            </div>


            </div>
        </div>


            <!-- @if(Route::has('register'))
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
     </div> -->

     <!--BOTTOM BAR-->
    <div class="w-full h-32 bg-[#D2C5A5] rounded-tl-[72px] rounded-tr-[72px] absolute inset-x-0 bottom-0"></div>

    </body>
</html>
