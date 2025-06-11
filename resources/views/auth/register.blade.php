<!DOCTYPE html>
<<<<<<< Updated upstream
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
=======
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register · Task Management</title>

  {{-- Tailwind CDN + Google Fonts --}}
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
      <a href="{{ route('register') }}" class="text-zinc-800 font-bold underline">Sign Up</a>
      <a href="{{ route('login') }}"    class="text-zinc-800 font-bold">Log In</a>
    </nav>
  </header>

  {{-- STATIC MIDDLE --}}
  <main class="absolute inset-x-0 top-20 bottom-20 overflow-auto px-4">
    <div class="h-full flex items-center justify-center">
      <div class="bg-[#26231F] p-4 sm:p-6 rounded-xl shadow-lg w-full max-w-sm space-y-4">
>>>>>>> Stashed changes

      <!-- Title -->
        <h1 class="text-center text-2xl font-bold text-stone-200">Create an Account</h1>

        {{-- Session Feedback (backend hooks left intact) --}}
        @if(session('success'))
          <div class="text-green-400 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="text-red-400 text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4" id="register-form" novalidate>
          @csrf

<<<<<<< Updated upstream
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
=======
          <!-- Username -->
          <div>
            <label for="username" class="block text-white text-sm mb-1">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" required
              class="bg-stone-200 text-stone-900 text-sm font-inter px-3 py-2 w-full rounded-lg
                     focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition" />
            @error('username')
              <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-white text-sm mb-1">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
              class="bg-stone-200 text-stone-900 text-sm font-inter px-3 py-2 w-full rounded-lg
                     focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition" />
            @error('email')
              <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-white text-sm mb-1">Password</label>
            <input id="password" name="password" type="password" required
              class="bg-stone-200 text-stone-900/80 text-sm font-inter px-3 py-2 w-full rounded-lg
                     focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition" />
            @error('password')
              <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Confirm Password -->
          <div>
            <label for="password_confirmation" class="block text-white text-sm mb-1">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
              class="bg-stone-200 text-stone-900/80 text-sm font-inter px-3 py-2 w-full rounded-lg
                     focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition" />
          </div>
>>>>>>> Stashed changes

          <!-- Submit Button -->
          <button type="submit"
            class="w-full h-12 bg-[#C7B89B] text-sm font-inter font-bold rounded-lg hover:bg-[#B5A688] transition-colors text-zinc-800
                   flex items-center justify-center gap-2">
            Sign Up
          </button>
        </form>

<<<<<<< Updated upstream
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

=======
        {{-- Divider & Social--}}
        <div class="flex items-center gap-2 text-xs text-white pt-4">
          <div class="flex-grow border-t"></div>
          <span class="font-istok">or sign up with</span>
          <div class="flex-grow border-t"></div>
>>>>>>> Stashed changes
        </div>
        <div class="flex justify-center items-center gap-3 pt-2">
          <a href="{{ route('auth.provider','google') }}"
             class="flex-1 h-8 flex items-center justify-center gap-2 bg-white rounded-full shadow hover:opacity-90 transition-colors px-4">
            <img src="https://www.google.com/favicon.ico" class="w-4 h-4" alt="Google"/>
            <span class="text-xs font-istok font-bold text-zinc-800">Google</span>
          </a>
          <a href="{{ route('auth.provider','facebook') }}"
             class="flex-1 h-8 flex items-center justify-center gap-2 bg-white rounded-full shadow hover:opacity-90 transition-colors px-4">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                 class="w-4 h-4" alt="Facebook"/>
            <span class="text-xs font-istok font-bold text-zinc-800">Facebook</span>
          </a>
        </div>

<<<<<<< Updated upstream
        <!-- BOTTOM BAR -->
    <footer class="fixed bottom-0 left-0 right-0 bg-[#D2C5A5] z-10 rounded-tl-[48px] rounded-tr-[48px] h-20 sm:h-28 pb-[env(safe-area-inset-bottom)]"></footer>

=======
      </div>
    </div>
  </main>
>>>>>>> Stashed changes

  {{-- FIXED FOOTER --}}
  <footer class="fixed inset-x-0 bottom-0 h-20 bg-[#D2C5A5]
                 rounded-tl-[60px] rounded-tr-[60px]
                 flex items-center justify-center text-zinc-800 text-xs font-istok z-20">
  </footer>

</body>
</html>
