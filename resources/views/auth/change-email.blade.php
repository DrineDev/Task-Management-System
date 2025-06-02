<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Email</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    .error-message {
      color: #E9604E;
      font-size: 0.875rem;
      margin-top: 0.25rem;
    }
  </style>
</head>
<body class="text-white">
  <!-- Header -->
  <div class="w-full h-auto md:h-32 px-4 md:px-8 py-4 bg-[#D2C5A5] rounded-bl-[36px] md:rounded-bl-[72px] rounded-br-[36px] md:rounded-br-[72px] flex items-center justify-end">
    <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="w-16 h-16 md:w-20 md:h-20 bg-[#2F2D2A] text-[#D2C5A5] rounded-full p-2 hover:text-[#C7B89B] focus:outline-none focus:ring-2 focus:ring-gray-400 flex items-center justify-center">
            <i class="fas fa-sign-out-alt text-[40px]"></i>
        </button>
    </form>
  </div>

  <main class="main-container flex-grow flex items-center justify-center p-4 md:p-6">
    <div class="flex flex-col md:flex-row max-w-6xl w-full rounded-xl text-black bg-[#2F2D2A] gap-6 md:gap-10 p-4">

      <div class="md:w-1/3 w-full flex flex-col items-center justify-center pb-4 md:pb-0">
        <img src="{{ is_array($profile) ? ($profile['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5') : ($profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5') }}" alt="Profile Picture" class="rounded-xl w-60 md:w-80 h-auto mb-5">
        <h2 class="text-white text-4xl md:text-5xl font-semibold text-center">{{ is_array($profile) ? $profile['name'] : ($profile->name ?? $user->email) }}</h2>
      </div>

      <div class="md:w-2/3 w-full text-white">
        <div class="mb-4">
          <h3 class="text-3xl md:text-4xl font-bold mb-6">Change Email</h3>

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

          <form action="{{ route('change-email.update') }}" method="POST">
            @csrf
            @method('PUT')
          <div class="input-box p-4 mb-3 rounded-lg">
              <label for="current_password" class="block text-sm font-semibold mb-1">Current Password</label>
              <input type="password" id="current_password" name="current_password" class="w-full px-3 py-1.5 rounded border border-gray-300" required>
          </div>

          <div class="input-box p-4 mb-3 rounded-lg">
              <label for="email" class="block text-sm font-semibold mb-1">New Email</label>
              <input type="email" id="email" name="email" class="w-full px-3 py-1.5 rounded border border-gray-300" required>
          </div>

          <div class="text-right">
              <button type="submit" class="confirm-button px-4 py-2 rounded text-sm">Update Email</button>
            </div>
          </form>

          <div class="mt-8">
            <a href="{{ route('profile.show') }}" class="control-box p-4 rounded-lg text-left text-black w-full inline-block">
              Back to Profile
            </a>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- Footer -->
  <footer class="w-full h-28 bg-[#D2C5A5] rounded-tl-[72px] rounded-tr-[72px] inset-x-0 flex items-center justify-around md:justify-center md:space-x-16 px-8 py-4 shadow-lg">
    <a href="{{ route('profile.show') }}" class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
        <i class="fas fa-user text-[40px] mb-1"></i>
    </a>
    <button id="addbutton" class="w-28 h-28 md:w-24 md:h-24 bg-[#ECE3D2] rounded-lg flex items-center justify-center text-[#1B1A19] text-[40px] md:text-[20px] shadow-xl hover:bg-[#928c80] transition-colors -mt-8 md:-mt-12">
        <i class="fas fa-plus"></i>
      </button>
    <a href="{{ route('dashboard') }}" class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
        <i class="fas fa-clipboard-list text-[40px] mb-1"></i>
    </a>
  </footer>
</body>
</html>
