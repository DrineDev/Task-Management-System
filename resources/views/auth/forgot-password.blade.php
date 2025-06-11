{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<<<<<<< Updated upstream
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password - Task Management System</title>
        @vite(['resources/css/login.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#1E1E1E] text-[#F4EBD9] min-h-screen relative overflow-hidden">
=======
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Forgot Password · Task Management</title>
>>>>>>> Stashed changes

  <!-- Tailwind + Google Fonts -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Istok+Web:wght@400;700&family=Inter:wght@400;700&display=swap');
    .font-istok { font-family: 'Istok Web', sans-serif; }
    .font-inter { font-family: 'Inter', sans-serif; }
  </style>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-screen overflow-hidden bg-[#1E1E1E] text-[#F4EBD9] font-istok">

  <!-- FIXED HEADER -->
<header class="fixed inset-x-0 top-0 h-20 bg-[#D2C5A5]
               rounded-bl-[60px] rounded-br-[60px]
               flex items-center justify-start px-6 z-20">
  <a href="{{ route('login') }}"
     class="inline-flex items-center gap-2 text-zinc-800 text-base font-bold
            hover:text-zinc-900 transition-colors">
    <!-- Left arrow -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 19l-7-7 7-7" />
    </svg>
    <span>Back</span>
  </a>
</header>

  <!-- STATIC MIDDLE -->
  <main class="absolute inset-x-0 top-20 bottom-20 overflow-auto px-4">
    <div class="h-full flex items-center justify-center">
      <div class="bg-[#26231F] p-6 sm:p-8 rounded-xl shadow-lg w-full max-w-sm space-y-6">

        <!-- Heading -->
        <h1 class="text-left text-2xl font-bold text-[#ECE3D2] font-istok">Forgot Password?</h1>
        <p class="text-left text-sm font-inter text-[#FFFFFF] leading-relaxed">
          Enter the email associated with your account and we’ll send you
          a link to reset your password.
        </p>

        <!-- Status / Errors -->
        @if(session('status'))
          <p class="text-green-400 text-sm">{{ session('status') }}</p>
        @endif
        @if($errors->any())
          <ul class="text-red-400 text-sm list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
          @csrf

          <div class="space-y-1">
            <label for="email" class="block text-white text-sm font-normal">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
              class="bg-stone-200 text-stone-900 text-sm font-inter px-3 py-2 w-full rounded-lg
                     focus:outline-none focus:ring-2 focus:ring-[#C7B89B] transition" autofocus>
            @error('email')
              <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
          </div>

          <button type="submit"
            class="w-full h-12 bg-[#C7B89B] text-sm font-inter font-bold rounded-lg text-zinc-800
                   hover:bg-[#B5A688] transition-colors">
            Send Reset Link
          </button>
        </form>

      </div>
    </div>
  </main>

  <!-- FIXED FOOTER -->
  <footer class="fixed inset-x-0 bottom-0 h-16 bg-[#D2C5A5]
                 rounded-tl-[60px] rounded-tr-[60px]
                 flex items-center justify-center text-zinc-800 text-xs font-istok z-20">
  </footer>

<<<<<<< Updated upstream
            </div>
         </div>

             <!-- BOTTOM BAR -->
    <div class="w-full h-24 sm:h-32 bg-[#D2C5A5] rounded-tl-[48px] rounded-tr-[48px] fixed bottom-0 left-0 right-0 z-10"></div>

    </body>
=======
</body>
>>>>>>> Stashed changes
</html>
