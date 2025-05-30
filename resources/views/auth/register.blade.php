<!DOCTYPE html>
<html>
    <head>
       <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register - Task Management System</title>
        @vite(['resources/css/login.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#1E1E1E] text-[#F4EBD9] min-h-screen relative overflow-hidden">

    <!-- TOP BAR -->
<div class="w-full px-6 py-4 bg-[#D2C5A5] rounded-bl-[48px] rounded-br-[48px] flex flex-col justify-end items-center sm:h-32 h-24">
  <div class="w-full max-w-sm flex flex-col sm:flex-row justify-between items-center gap-2">
    <a href="/register" class="text-zinc-800 text-sm sm:text-base font-bold font-['Istok_Web'] underline">Sign Up</a>
    <a href="/login" class="text-zinc-800 text-sm sm:text-base font-bold font-['Istok_Web']">Log In</a>
  </div>
</div>

    
       <!-- CREATE AN ACCOUNT -->
        <h1 class="text-center text-2xl sm:text-3x1 font-bold text-stone-200 font-['Istok_Web'] mt-8 mb-1.5">Create An Account</h1>

        <!-- SIGN UP FORM WRAPPER -->
     <div class="flex justify-center px-4 pt-8 pb-40 sm:pb-52 overflow-y-auto">
         <div class="bg-[#26231F] p-6 sm:p-10 rounded-xl shadow-lg w-full max-w-md">

             @if(session('success'))
                <div>{{ session('success') }}</div>
            @endif

        @if(session('error'))
            <div>{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- <div>
                <label for="name" class="block text-left pb-3.5">Username</label>
                <input class="bg-stone-200 rounded-xl px-4 py-2 w-full" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                @error('username')
                    <div>{{ $message }}</div>
                @enderror
            </div> -->

            <div>
                <label for="email" class="block text-left pb-3.5">Email</label>
                <input class="bg-stone-200 rounded-xl px-4 py-2 w-full text-sm sm:text-based" id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div>{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-left pb-3.5">Password</label>
                <input class="pb-12 text-sm sm:text-based" id="password" type="password" name="password" required>
                @error('password')
                    <div>{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-left pb-3.5">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <div class="pt-5">
                <button class="w-full h-14 p-2.5 bg-[#C7B89B] rounded-xl inline-flex justify-center items-center gap-2.5 text-stone-900/80 text-base font-bold" type="submit">
                    Sign Up
                </button>
            </div>
        </form>

        <!-- @if(Route::has('login'))
            <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
        @endif
         </div> -->

          <div class="flex flex-col sm:flex-row items-center gap-3 pt-5">
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

        <!-- BOTTOM BAR -->
    <footer class="fixed bottom-0 left-0 right-0 bg-[#D2C5A5] z-10 rounded-tl-[48px] rounded-tr-[48px] h-20 sm:h-28 pb-[env(safe-area-inset-bottom)]"></footer>


    </body>
</html>
