<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile - {{ is_array($profile) ? $profile['name'] : ($profile->name ?? $user->email) }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
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
      box-shadow: 15px 15px 30px rgba(0,0,0,0.8), -15px 15px 30px rgba(0,0,0,0.6), 0 20px 30px rgba(0,0,0,0.7);
    }

    .header, .footer {
      background-color: #D2C5A5;
    }

    .info-box, .control-box, .input-box {
      background-color: #D2C5A5;
    }

    .input-box input {
      background-color: #E0D6BB;
      color: #5C4033;
    }

    .input-box label {
      color: #5C4033;
    }

    .confirm-button {
      background-color: #A47551;
      color: white;
    }
  </style>
</head>
<body class="text-white">
  <!-- Header -->
  <header class="header flex-shrink-0 h-20 md:h-24 rounded-b-[2rem] shadow-md flex items-center justify-center px-4">
    <img src="logo.png" alt="Logo" class="h-12 md:h-16">
  </header>

  <!-- Main Content -->
  <main class="main-container flex-grow flex items-center justify-center p-4 md:p-6">
    <div class="flex flex-col md:flex-row max-w-6xl w-full rounded-xl text-black bg-[#2F2D2A] gap-6 md:gap-10 p-4">

      <!-- Profile Picture + Name -->
      <div class="md:w-1/3 w-full flex flex-col items-center justify-center pb-4 md:pb-0">
        <img src="{{ is_array($profile) ? ($profile['avatar_url'] ?? 'default-avatar.png') : ($profile->avatar_url ?? 'default-avatar.png') }}" alt="Profile Picture" class="rounded-xl w-60 md:w-80 h-auto mb-5">
        <h2 class="text-white text-4xl md:text-5xl font-semibold text-center">{{ is_array($profile) ? $profile['name'] : ($profile->name ?? $user->email) }}</h2>
      </div>

      <!-- Input Fields -->
      <div class="md:w-2/3 w-full text-white">
        <div class="mb-4">
          <h3 class="text-3xl md:text-4xl font-bold mb-6">Profile Information</h3>

          @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
              <span class="block sm:inline">{{ session('success') }}</span>
            </div>
          @endif

          @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
              <span class="block sm:inline">{{ session('error') }}</span>
            </div>
          @endif

          @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
              <ul>
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="input-box p-4 mb-3 rounded-lg">
              <label for="name" class="block text-sm font-semibold mb-1">Display Name (Optional)</label>
              <input type="text" id="name" name="name" value="{{ is_array($profile) ? ($profile['name'] ?? '') : ($profile->name ?? '') }}" class="w-full px-3 py-1.5 rounded border border-gray-300">
            </div>

            <div class="input-box p-4 mb-3 rounded-lg">
              <label for="avatar_url" class="block text-sm font-semibold mb-1">Avatar URL (Optional)</label>
              <input type="text" id="avatar_url" name="avatar_url" value="{{ is_array($profile) ? ($profile['avatar_url'] ?? '') : ($profile->avatar_url ?? '') }}" class="w-full px-3 py-1.5 rounded border border-gray-300">
            </div>

            <div class="info-box p-4 mb-3 rounded-lg text-black">
              Email: {{ $user->email }}
            </div>

            <div class="text-right">
              <button type="submit" class="confirm-button px-4 py-2 rounded text-sm">Update Profile</button>
            </div>
          </form>

          <!-- Controls -->
          <div class="mt-8">
            <h3 class="text-3xl md:text-4xl font-bold mb-6">Account Settings</h3>
            <div class="flex flex-col gap-3">
              <a href="{{ route('change-email.show') }}" class="control-box p-4 rounded-lg text-left text-black w-full">
                Change Email
              </a>
              <a href="{{ route('change-password.show') }}" class="control-box p-4 rounded-lg text-left text-black w-full">
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

  <script>
    lucide.replace();
  </script>
</body>
</html>

