{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register · Task Management</title>

  {{-- Tailwind + Google Fonts --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Istok+Web:wght@400;700&family=Inter:wght@400;700&display=swap');
    .font-istok { font-family: 'Istok Web', sans-serif; }
    .font-inter { font-family: 'Inter', sans-serif; }
  </style>

  {{-- Vite assets --}}
  @vite(['resources/css/login.css','resources/js/app.js'])
</head>
<body class="h-screen overflow-hidden bg-[#1E1E1E] text-[#F4EBD9] font-istok">

  <!-- fixed header -->
  <header class="fixed inset-x-0 top-0 h-20 bg-[#D2C5A5] rounded-bl-[60px] rounded-br-[60px]
                 flex items-end justify-center px-4 z-20">
    <nav class="w-36 flex justify-between pb-3 text-sm">
      <a href="{{ route('register') }}" class="text-zinc-800 font-bold underline">Sign Up</a>
      <a href="{{ route('login') }}"    class="text-zinc-800 font-bold ">Log In</a>
    </nav>
  </header>

  <!-- main: card centered between header & footer -->
  <main class="absolute inset-x-0 top-16 bottom-16 flex items-center justify-center px-4">
    <div class="bg-[#26231F] p-4 sm:p-6 rounded-xl shadow-lg w-full max-w-sm space-y-4">

      <!-- Title -->
      <h1 class="text-center text-2xl font-bold text-stone-200">Create an Account</h1>

      <!-- Flash Messages -->
      @if(session('success'))
        <p class="text-green-400 text-sm">{{ session('success') }}</p>
      @endif
      @if(session('error'))
        <p class="text-red-400 text-sm">{{ session('error') }}</p>
      @endif

      <!-- Form -->
      <form method="POST" action="{{ route('register') }}" class="space-y-3">
        @csrf

        <div class="space-y-1">
          <label for="name" class="block text-white text-sm">Name</label>
          <input id="name" name="name" type="text" value="{{ old('name') }}" required
            class="bg-stone-200 text-stone-900 text-sm font-inter px-3 py-2 w-full rounded-lg
                   focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition" />
          @error('name')
            <p class="text-red-400 text-xs">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-1">
          <label for="email" class="block text-white text-sm">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" required
            class="bg-stone-200 text-stone-900 text-sm font-inter px-3 py-2 w-full rounded-lg
                   focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition" />
          @error('email')
            <p class="text-red-400 text-xs">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-1">
          <label for="password" class="block text-white text-sm">Password</label>
          <input id="password" name="password" type="password" required
            class="bg-stone-200 text-stone-900/80 text-sm font-inter px-3 py-2 w-full rounded-lg
                   focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition" />
          @error('password')
            <p class="text-red-400 text-xs">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-1">
          <label for="password_confirmation" class="block text-white text-sm">Confirm Password</label>
          <input id="password_confirmation" name="password_confirmation" type="password" required
            class="bg-stone-200 text-stone-900/80 text-sm font-inter px-3 py-2 w-full rounded-lg
                   focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition" />
        </div>

        <button type="submit"
          class="w-full h-12 bg-[#C7B89B] text-sm font-inter font-bold rounded-lg
                 text-zinc-800 hover:bg-[#B5A688] transition-colors">
          Sign Up
        </button>
      </form>

      <!-- Divider & Social -->
      <div class="flex items-center gap-2 text-xs text-white pt-4">
        <div class="flex-grow border-t border-white"></div>
        <span class="font-istok">or sign up with</span>
        <div class="flex-grow border-t border-white"></div>
      </div>
      <div class="flex justify-center items-center gap-3">
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
  </main>

  <!-- fixed footer -->
  <footer class="fixed inset-x-0 bottom-0 h-16 bg-[#D2C5A5]
                 rounded-tl-[60px] rounded-tr-[60px]
                 flex items-center justify-center text-zinc-800 text-xs font-istok z-20">
  </footer>

</body>
</html>
