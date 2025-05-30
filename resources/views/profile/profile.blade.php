<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile - {{ $user->name }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Lucide Icons CDN -->
  <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>

  <style>
    body {
      background-color: #2F2D2A;
      margin: 0;
      height: 100vh;
      display: flex;
      flex-direction: column;
      font-family: Arial, sans-serif;
    }

    .main-container {
      background-color: #2F2D2A;
      box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }

    .header, .footer {
      background-color: #D2C5A5;
    }

    .info-box {
      background-color: #D2C5A5;
    }

    .control-box {
      background-color: #D2C5A5;
    }

    .delete-box {
      background-color: #E9604E;
    }
  </style>
</head>
<body class="text-white">

  <!-- Header -->
  <header class="header flex-shrink-0 h-20 md:h-24 rounded-b-[2rem] shadow-md flex items-center justify-center px-4">
    <img src="logo.png" alt="Logo" class="h-12 md:h-16">
  </header>

  <!-- Main Section -->
  <main class="main-container flex-grow flex items-center justify-center p-4 md:p-6">
    <div class="flex flex-col md:flex-row max-w-6xl w-full rounded-xl shadow-2xl text-black bg-[#2F2D2A] gap-6 md:gap-10 p-4">

      <!-- Left Profile -->
      <div class="md:w-1/3 w-full flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-400 pb-4 md:pb-0 md:pr-6">
        <img src="{{ $user->avatar_url ?? 'default-avatar.png' }}" alt="Profile Picture" class="rounded-xl w-40 md:w-64 h-auto mb-4">
        <h2 class="text-white text-2xl md:text-3xl font-semibold text-center">{{ $user->name }}</h2>
      </div>

      <!-- Right Info Section -->
      <div class="md:w-2/3 w-full text-white">
        <div class="mb-4">
          <h3 class="text-xl md:text-2xl font-bold mb-2">Account Information</h3>

          <div class="info-box p-4 mb-3 rounded-lg text-black">
            Display Name: {{ $user->name }}
          </div>
          <div class="info-box p-4 mb-3 rounded-lg text-black">
            Email: {{ $user->email }}
          </div>
          <div class="info-box p-4 mb-3 rounded-lg text-black flex flex-col md:flex-row items-start md:items-center gap-2">
            <span class="w-full md:w-auto">Password:</span>
            <div class="flex flex-1 items-center gap-2 w-full">
              <input type="password" id="passwordField" value="••••••••" readonly class="bg-white border border-gray-400 rounded px-3 py-1 text-black w-full">
              <button onclick="togglePassword()" class="bg-gray-800 hover:bg-gray-600 text-white text-sm px-2 py-1 rounded">Show</button>
            </div>
          </div>
        </div>

        <!-- Controls -->
        <div>
          <h3 class="text-xl md:text-2xl font-bold mb-2">Controls</h3>
          <div class="flex flex-col gap-3">
            <a href="{{ route('change-password') }}" class="control-box p-4 rounded-lg text-left text-black w-full">
              Change Password
            </a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="delete-box p-3 rounded-lg text-left text-white w-1/2 text-lg">
                Logout
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- Footer -->
  <footer class="footer flex-shrink-0 h-20 md:h-24 rounded-t-[2rem] shadow-md flex flex-col items-center text-black px-6">
    <div class="flex justify-center items-center gap-6 md:gap-10 mt-4 flex-wrap">
      <a href="{{ route('dashboard') }}" class="hover:bg-[#444]/20 p-3 rounded-full">
        <i data-lucide="home" class="w-6 h-6 text-[#2F2D2A]"></i>
      </a>

      <a href="{{ route('tasks.create') }}" class="bg-white border-4 border-[#2F2D2A] rounded-full p-4 hover:bg-gray-200 transition">
        <i data-lucide="plus" class="w-8 h-8 text-[#2F2D2A]"></i>
      </a>

      <a href="{{ route('tasks.index') }}" class="hover:bg-[#444]/20 p-3 rounded-full">
        <i data-lucide="check-square" class="w-6 h-6 text-[#2F2D2A]"></i>
      </a>
    </div>
  </footer>

  <!-- Script -->
  <script>
    function togglePassword() {
      const field = document.getElementById('passwordField');
      const button = field.nextElementSibling;

      if (field.type === 'password') {
        field.type = 'text';
        button.textContent = 'Hide';
      } else {
        field.type = 'password';
        button.textContent = 'Show';
      }
    }

    lucide.replace();
  </script>
</body>
</html>
