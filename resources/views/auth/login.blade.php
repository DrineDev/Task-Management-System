{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<<<<<<< Updated upstream
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - Task Management System</title>
        @vite(['resources/css/login.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#1E1E1E] text-[#F4EBD9] min-h-screen relative overflow-hidden">

    <!-- TOP BAR -->
    <div class="w-full px-6 py-4 bg-[#D2C5A5] rounded-bl-[48px] rounded-br-[48px] flex flex-col justify-end items-center sm:h-32 h-24">
    <div class="w-full max-w-sm flex flex-col sm:flex-row justify-between items-center gap-2">
        <a href="/register" class="text-zinc-800 text-sm sm:text-base font-bold font-['Istok_Web']">Sign Up</a>
        <a href="/login" class="text-zinc-800 text-sm sm:text-base font-bold font-['Istok_Web'] underline">Log In</a>
    </div>
    </div>


    <!-- WELCOME BACK -->
        <h1 class="text-center text-2xl sm:text-3x1 font-bold text-stone-200 font-['Istok_Web'] mt-16 mb-1.5">Welcome Back!</h1>

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
                    <input class="bg-stone-200 rounded-xl px-4 py-2 w-full text-sm sm:text-based" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-left pb-3.5 pt-5">Password</label>
                    <input  class="bg-stone-200 rounded-xl px-4 py-2 w-full text-sm sm:text-based" id="password" type="password" name="password" required>
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

     <!-- BOTTOM BAR -->
    <footer class="w-full h-24 sm:h-32 bg-[#D2C5A5] rounded-tl-[48px] rounded-tr-[48px] fixed bottom-0 left-0 right-0 z-10"></footer>

=======
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login · Task Management</title>

  {{-- Tailwind + Google Fonts --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Istok+Web:wght@400;700&family=Inter:wght@400;700&display=swap');
    .font-istok { font-family: 'Istok Web', sans-serif; }
    .font-inter { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="h-screen overflow-hidden bg-[#1E1E1E] text-[#F4EBD9] font-istok">

  <!-- fixed header -->
  <header class="fixed inset-x-0 top-0 h-20 bg-[#D2C5A5] rounded-bl-[60px] rounded-br-[60px]
                 flex items-end justify-center px-4 z-20">
    <nav class="w-36 flex justify-between pb-3 text-sm">
      <a href="{{ route('register') }}" class="text-zinc-800 font-bold">Sign Up</a>
      <a href="{{ route('login') }}"    class="text-zinc-800 font-bold underline">Log In</a>
    </nav>
  </header>

  <!-- static middle -->
  <main class="absolute inset-x-0 top-20 bottom-20 overflow-auto px-4">
    <div class="h-full flex items-center justify-center">
      <div class="bg-[#26231F] p-6 sm:p-8 rounded-xl shadow-lg w-full max-w-sm space-y-5">

        <!-- Title -->
        <h1 class="text-center text-2xl font-bold text-stone-200">Welcome Back!</h1>

        <!-- Session Errors -->
        @if(session('error'))
          <p class="text-red-400 text-sm"> {{ session('error') }} </p>
        @endif
        @if($errors->any())
          <ul class="text-red-400 text-sm list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        @endif

        <!-- form (space-y-5 → space-y-4) -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
          @csrf

          <!-- Email -->
          <div class="space-y-1">
            <label for="email" class="block text-white text-sm">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
              class="bg-stone-200 text-stone-900 text-sm font-inter px-3 py-2 w-full rounded-lg
                     focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition" autofocus>
            @error('email')
              <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div class="space-y-1">
            <label for="password" class="block text-white text-sm">Password</label>
            <input id="password" name="password" type="password" required
              class="bg-stone-200 text-stone-900/80 text-sm font-inter px-3 py-2 w-full rounded-lg
                     focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition">
            @error('password')
              <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
          </div>

          <!-- Remember Me (animated toggle) -->
          <div class="flex items-center gap-3">
            <label class="inline-flex items-center cursor-pointer">
              <input type="checkbox" id="remember" name="remember" class="sr-only peer" />
              <div class="w-10 h-5 bg-gray-300 rounded-full relative transition-colors duration-200
                          peer-focus:ring-2 peer-focus:ring-[#C7B89B]
                          peer-checked:bg-[#C7B89B]
                          after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                          after:bg-white after:border after:border-gray-300 after:rounded-full
                          after:h-4 after:w-4 after:transition-all
                          peer-checked:after:translate-x-[1.25rem]">
              </div>
              <span class="ml-2 text-white text-sm font-inter">Remember Me</span>
            </label>
          </div>

          <!-- Submit -->
          <button type="submit"
            class="w-full h-12 bg-[#C7B89B] text-sm font-inter font-bold rounded-lg text-zinc-800
                   hover:bg-[#B5A688] transition-colors">
            Login
          </button>
        </form>

        <!-- Forgot Password (mt-2, mb-4) -->
        @if(Route::has('password.request'))
          <p class="mt-2 mb-4 text-left text-xs text-white">
            <a href="{{ route('password.request') }}" class="text-[#A49373] hover:underline">
              Forgotten your password?
            </a>
          </p>
        @endif

        <!-- Divider (my-4) -->
        <div class="flex items-center gap-2 text-xs text-white my-4">
          <div class="flex-grow border-t border-white"></div>
          <span class="font-istok">or sign in with</span>
          <div class="flex-grow border-t border-white"></div>
        </div>

        <!-- Social (gap-3, mb-6) -->
        <div class="flex justify-center items-center gap-3 mb-6">
          <a href="{{ route('auth.provider','google') }}"
             class="flex-1 h-8 flex items-center justify-center gap-2 bg-white rounded-full shadow
                    hover:opacity-90 transition-colors px-4">
            <img src="https://www.google.com/favicon.ico" alt="Google" class="w-4 h-4">
            <span class="text-xs font-istok font-bold text-zinc-800">Google</span>
          </a>
          <a href="{{ route('auth.provider','facebook') }}"
             class="flex-1 h-8 flex items-center justify-center gap-2 bg-white rounded-full shadow
                    hover:opacity-90 transition-colors px-4">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                 alt="Facebook" class="w-4 h-4">
            <span class="text-xs font-istok font-bold text-zinc-800">Facebook</span>
          </a>
        </div>

      </div>
    </div>
  </main>

  {{-- FIXED FOOTER --}}
  <footer class="fixed inset-x-0 bottom-0 h-20 bg-[#D2C5A5]
                 rounded-tl-[60px] rounded-tr-[60px]
                 flex items-center justify-center text-zinc-800 text-xs font-istok z-20">
  </footer>
>>>>>>> Stashed changes

    </body>
</html>
