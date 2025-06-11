{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
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
  @vite(['resources/css/login.css', 'resources/js/app.js'])
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
          <a href="{{ route('auth.provider', 'google') }}"
             class="flex-1 h-10 flex items-center justify-center gap-2 bg-white rounded-lg shadow
                    hover:opacity-90 transition-colors px-4">
            <img src="https://www.google.com/favicon.ico" alt="Google" class="w-5 h-5">
            <span class="text-sm font-istok font-bold text-zinc-800">Google</span>
          </a>
          <a href="{{ route('auth.provider', 'facebook') }}"
             class="flex-1 h-10 flex items-center justify-center gap-2 bg-white rounded-lg shadow
                    hover:opacity-90 transition-colors px-4">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                 alt="Facebook" class="w-5 h-5">
            <span class="text-sm font-istok font-bold text-zinc-800">Facebook</span>
          </a>
        </div>

      </div>
    </div>
  </main>

 <!-- fixed footer -->
  <footer class="fixed inset-x-0 bottom-0 h-16 bg-[#D2C5A5]
                 rounded-tl-[60px] rounded-tr-[60px]
                 flex items-center justify-center text-zinc-800 text-xs font-istok z-20">
  </footer>

</body>
</html>
