<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Profile - {{ is_array($profile) ? $profile['name'] : ($profile->name ?? $user->email) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      background-color: #2F2D2A;
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      font-family: Arial, sans-serif;
      position: relative;
      padding-bottom: 8rem; /* Add padding for footer */
    }

    .main-container {
      background-color: #2F2D2A;
      box-shadow: 15px 15px 30px rgba(0,0,0,0.8), -15px 15px 30px rgba(0,0,0,0.6), 0 20px 30px rgba(0,0,0,0.7);
      flex: 1;
      width: 100%;
      overflow-y: auto;
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

    footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 50;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #2F2D2A;
      padding: 2rem;
      border-radius: 1rem;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border: 2px solid #D2C5A5;
    }

    .modal-header {
      color: #D2C5A5;
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 1rem;
      text-align: center;
    }

    .modal-body {
      color: #D2C5A5;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .modal-footer {
      display: flex;
      justify-content: center;
      gap: 1rem;
    }

    .modal-button {
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .modal-button.cancel {
      background-color: #D2C5A5;
      color: #2F2D2A;
    }

    .modal-button.delete {
      background-color: #dc2626;
      color: white;
    }

    .modal-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

  <!-- Main Content -->
  <main class="main-container flex items-center justify-center p-4 md:p-6">
    <div class="flex flex-col md:flex-row max-w-6xl w-full rounded-xl text-black bg-[#2F2D2A] gap-6 md:gap-10 p-4">

      <!-- Profile Picture + Name -->
      <div class="md:w-1/3 w-full flex flex-col items-center justify-center pb-4 md:pb-0">
        <img src="{{ is_array($profile) ? ($profile['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5') : ($profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5') }}" alt="Profile Picture" class="rounded-xl w-60 md:w-80 h-auto mb-5">
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

          <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="input-box p-4 mb-3 rounded-lg">
              <label for="name" class="block text-sm font-semibold mb-1">Display Name (Optional)</label>
              <input type="text" id="name" name="name" value="{{ is_array($profile) ? ($profile['name'] ?? '') : ($profile->name ?? '') }}" class="w-full px-3 py-1.5 rounded border border-gray-300">
            </div>

            <div class="input-box p-4 mb-3 rounded-lg">
              <label for="avatar" class="block text-sm font-semibold mb-1">Profile Picture (Optional)</label>
              <input type="file" id="avatar" name="avatar" accept="image/*" class="w-full px-3 py-1.5 rounded border border-gray-300">
              <div class="mt-2">
                <p class="text-sm text-gray-600">Current profile picture:</p>
                <img src="{{ is_array($profile) ? ($profile['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5') : ($profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5') }}" alt="Current profile picture" class="mt-1 h-20 w-20 object-cover rounded">
              </div>
            </div>

            <div class="info-box p-4 mb-3 rounded-lg text-black">
              Email: {{ $user->email }}
            </div>

            <div class="flex justify-end">
              <button type="submit" class="confirm-button px-4 py-2 rounded text-sm">Update Profile</button>
            </div>
          </form>

          <!-- Controls -->
          <div class="mt-8">
            <h3 class="text-3xl md:text-4xl font-bold mb-6 text-white">Account Settings</h3>
            <div class="flex flex-col gap-3">
              <a href="{{ route('change-email.show') }}" class="control-box p-4 rounded-lg text-left text-black w-full">
                Change Email
              </a>
              <a href="{{ route('change-password.show') }}" class="control-box p-4 rounded-lg text-left text-black w-full">
                Change Password
              </a>
              <form action="{{ route('profile.delete') }}" method="POST" class="w-1/3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="openDeleteModal()" class="bg-red-600 hover:bg-red-700 p-4 rounded-lg text-left text-white w-full">
                  Delete Account
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="fixed bottom-0 left-0 right-0 w-full h-32 bg-[#D2C5A5] rounded-tl-[72px] rounded-tr-[72px] inset-x-0 flex items-center justify-around md:justify-center md:space-x-16 px-8 py-4 shadow-lg">
    <button class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
        <i class="fas fa-user text-[40px] mb-1"></i>
    </button>
    <button class="w-28 h-28 bg-[#ECE3D2] rounded-lg flex items-center justify-center text-[#1B1A19] text-[40px] shadow-xl hover:bg-[#928c80] transition-colors -mt-8 md:-mt-12">
        <i class="fas fa-plus"></i>
    </button>
    <a href="{{ route('dashboard') }}" class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
        <i class="fas fa-clipboard-list text-[40px] mb-1"></i>
    </a>
  </footer>

  <!-- Delete Account Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
        Delete Account
      </div>
      <div class="modal-body">
        Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.
      </div>
      <div class="modal-footer">
        <button onclick="closeDeleteModal()" class="modal-button cancel">
          Cancel
        </button>
        <form action="{{ route('profile.delete') }}" method="POST" class="inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="modal-button delete">
            Delete Account
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    function openDeleteModal() {
      document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
      document.getElementById('deleteModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('deleteModal');
      if (event.target === modal) {
        closeDeleteModal();
      }
    }
  </script>
</body>
</html>
