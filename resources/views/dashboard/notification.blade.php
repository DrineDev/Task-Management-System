<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Notification Popup</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadein {
      from { opacity: 0; transform: translateY(-20px);}
      to { opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div id="notification" class="hidden fixed top-6 right-4 sm:right-6 bg-[#2F2D2A] text-[#D2C5A5] px-6 py-5 rounded-lg shadow-md z-[1000] font-sans text-sm 
    w-[95vw] sm:w-[480px] max-h-[320px] overflow-y-auto animate-fadein space-y-4">
    
    <button onclick="hideNotification()" class="absolute top-1 right-2 text-[#D2C5A5] text-lg leading-none hover:scale-110 transition-transform">&times;</button>
    <div class="font-bold text-base mb-2">Notifications</div>

    <div class="bg-[#D2C5A5] text-[#2F2D2A] p-4 rounded-md flex items-start gap-4">
      <div class="text-3xl leading-none">🔔</div>
      <div class="flex-1 text-sm leading-snug">"Provide monthly report" is due soon! Mark it done when finished.</div>
    </div>

    <div class="bg-[#D2C5A5] text-[#2F2D2A] p-4 rounded-md flex items-start gap-4">
      <div class="text-3xl leading-none">✅</div>
      <div class="flex-1 text-sm leading-snug">"Book Charity Venue" is overdue! Update your dashboard.</div>
    </div>

    <div class="bg-[#D2C5A5] text-[#2F2D2A] p-4 rounded-md flex items-start gap-4">
      <div class="text-3xl leading-none">📅</div>
      <div class="flex-1 text-sm leading-snug">Meeting at 2 PM today. Don’t forget!</div>
    </div>

    <div class="bg-[#D2C5A5] text-[#2F2D2A] p-4 rounded-md flex items-start gap-4">
      <div class="text-3xl leading-none">📦</div>
      <div class="flex-1 text-sm leading-snug">"Deliver team suuplies" is due soon! Mark it done when finished.</div>
    </div>

  </div>

  <script>
    function showNotification() {
      const notif = document.getElementById('notification');
      notif.classList.remove('hidden');
      notif.classList.add('block');
      setTimeout(hideNotification, 10000);
    }

    function hideNotification() {
      const notif = document.getElementById('notification');
      notif.classList.remove('block');
      notif.classList.add('hidden');
    }
  </script>

</body>
</html>
