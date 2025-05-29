<?php
$profilePicture = 'jarodpfp.png';
$name = 'Jarod Rebalde';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password</title>
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
      box-shadow: 10px 10px 25px rgba(0,0,0,0.6), -10px 10px 25px rgba(0,0,0,0.6), 0 10px 25px rgba(0,0,0,0.6);
    }

    .header, .footer {
      background-color: #D2C5A5;
    }

    .info-box {
      background-color: #D2C5A5;
    }

    .control-box, .input-box {
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
  <header class="header flex-shrink-0 h-20 md:h-24 rounded-b-[2rem] shadow-md flex items-center justify-center px-4">
    <img src="logo.png" alt="Logo" class="h-12 md:h-16">
  </header>

  <main class="main-container flex-grow flex items-center justify-center p-4 md:p-6">
    <div class="flex flex-col md:flex-row max-w-6xl w-full rounded-xl shadow-2xl text-black bg-[#2F2D2A] gap-6 md:gap-10 p-4">

      <div class="md:w-1/3 w-full flex flex-col items-center justify-center pb-4 md:pb-0">
        <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="rounded-xl w-56 md:w-80 h-auto mb-4">
        <h2 class="text-white text-4xl md:text-5xl font-semibold text-center"><?php echo $name; ?></h2>
      </div>

      <div class="md:w-2/3 w-full text-white">
        <div class="mb-4">
          <h3 class="text-3xl md:text-4xl font-bold mb-4">Change Password</h3>
          <div class="input-box p-4 mb-3 rounded-lg">
            <label for="currentPassword" class="block text-sm font-semibold mb-1">Enter Current Password</label>
            <input type="password" id="currentPassword" class="w-full px-3 py-2 rounded border border-gray-300">
          </div>
          <div class="input-box p-4 mb-3 rounded-lg">
            <label for="newPassword" class="block text-sm font-semibold mb-1">Enter New Password</label>
            <input type="password" id="newPassword" class="w-full px-3 py-2 rounded border border-gray-300">
          </div>
          <div class="input-box p-4 mb-3 rounded-lg">
            <label for="confirmPassword" class="block text-sm font-semibold mb-1">Re-type New Password</label>
            <input type="password" id="confirmPassword" class="w-full px-3 py-2 rounded border border-gray-300">
          </div>
          <div class="text-right">
            <button onclick="validatePasswords()" class="confirm-button px-4 py-2 rounded text-sm">Confirm</button>
          </div>
        </div>
      </div>

    </div>
  </main>

  <footer class="footer flex-shrink-0 h-20 md:h-24 rounded-t-[2rem] shadow-md flex flex-col items-center text-black px-6">
    <div class="flex justify-center items-center gap-6 md:gap-10 mt-4 flex-wrap">
      <button class="hover:bg-[#444]/20 p-3 rounded-full">
        <i data-lucide="home" class="w-6 h-6 text-[#2F2D2A]"></i>
      </button>
      <button class="bg-white border-4 border-[#2F2D2A] rounded-full p-4 hover:bg-gray-200 transition">
        <i data-lucide="plus" class="w-8 h-8 text-[#2F2D2A]"></i>
      </button>
      <button class="hover:bg-[#444]/20 p-3 rounded-full">
        <i data-lucide="check-square" class="w-6 h-6 text-[#2F2D2A]"></i>
      </button>
    </div>
  </footer>

  <script>
    function validatePasswords() {
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;

      if (newPassword !== confirmPassword) {
        alert('New password and confirmation do not match.');
        return;
      }

      if (newPassword.length < 8 || !/[A-Z]/.test(newPassword) || !/[a-z]/.test(newPassword) || !/[0-9]/.test(newPassword)) {
        alert('Password must be at least 8 characters long and contain uppercase, lowercase, and a number.');
        return;
      }

      alert('Password change submitted!');
      // Add form submission logic here
    }
    lucide.replace();
  </script>
</body>
</html>
