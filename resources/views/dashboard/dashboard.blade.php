<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
        <title>dashboard</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>

    </head>
    <body>
     <!-- TOP BAR -->
    <div class="w-full h-32 px-8 py-4 bg-[#D2C5A5] rounded-bl-[72px] rounded-br-[72px] flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-20 h-20 rounded-full overflow-hidden">
                <img src="https://scontent.fceb5-1.fna.fbcdn.net/v/t1.15752-9/494691149_1210378300185800_1251328112097693854_n.png?_nc_cat=105&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeFXKITHiyuCpIfzfDxcS4uRa_MhWbjLagJr8yFZuMtqAhip9fubz25PYlenTJBdUsvf4VUDaVSickdElvZeuh5p&_nc_ohc=ns0brLQtLRgQ7kNvwEP32KB&_nc_oc=Adnj59VG1ltNRLdgb2PQU5pZf2VP4k8XBTXvuwNsl2rVkwaEwtP4LRMwR1NFoe8KhIQ&_nc_zt=23&_nc_ht=scontent.fceb5-1.fna&oh=03_Q7cD2QFbj5GEZqig7KO5FHZirrcHz1-q3-_XaMrcHdtR5_hMdA&oe=685F4105" alt="User Avatar" class="w-full h-full object-cover">
            </div>
            <div class="ml-3">
                <h2 class="text-[40px] font-semibold text-[#2F2D2A]">Jarod Rebalde</h2>
                <p class="text-[16px] text-[#2F2D2A]">Welcome Back</p>
            </div>
        </div>
        <button class="w-20 h-20 bg-[#2F2D2A] text-[#D2C5A5] rounded-full p-2 hover:text-[#C7B89B] focus:outline-none focus:ring-2 focus:ring-gray-400 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
        </button>
    </div>

    <!-- ----------------------------- Dashboard Main --------------------------- -->
<! --------------------------------- SEARCH --------------------------------- -->
    <!-- <div class="grid grid-cols-2 grid-rows-1 gap-1">
   <div class="m-6 mx-11 w-fit p-2 flex items-center justify-start text-stone-900/50 text-xl font-bold font-['Inter']">
    <input type="search" class="w-[470px] h-11 bg-stone-200 rounded-xl" placeholder="Search...">
<button class="ml-3 flex items-center bg-[#C7B89B] p-2 rounded-md hover:bg-[#7c6e5b] transition-colors ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#3D3D3D" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
        </svg>
</button>
    </div>
    </div> -->

    <main class="grid grid-cols-1 md:grid-cols-3 gap-6 m-9">
<!-- ------------------------------- SEARCH -------------------------------- -->
            <div class="md:col-span-2 space-y-6">
                <div class="flex items-center">
                    <input type="text" placeholder="Search..." class="flex-grow bg-stone-200 rounded-xl outline-none text-stone-900/50 text-xl font-bold font-['Inter']">
                    <button class="ml-3 p-2 bg-[#C7B89B] rounded-md hover:bg-[#7c6e5b] transition-colors ease-in-out">
                        <i class="fas fa-sliders-h text-[#3D3D3]"></i>
                    </button>

                </div>
<!-- ------------------------------- STATUS -------------------------------- -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-[#c7bb9b] rounded-xl p-5 text-center shadow-lg">
                        <i class="fa-regular fa-clock text-[#2F2D2A] text-3xl mb-2"></i>
                        <h2 class="text-4xl font-bold text-[#2F2D2A]">5</h2>
                        <p class="text-[#1B1A19]">Ongoing</p>
                    </div>
                    <div class="bg-[#c7bb9b] rounded-xl p-5 text-center shadow-lg">
                        <i class="fa-regular fa-circle-check text-[#2F2D2A] text-3xl mb-2"></i>
                        <h2 class="text-4xl font-bold text-[#2F2D2A]">4</h2>
                        <p class="text-[#1B1A19]">Completed</p>
                    </div>
                    <div class="bg-[#c7bb9b] rounded-xl p-5 text-center shadow-lg">
                        <i class="fa-regular fa-calendar-xmark text-[#2F2D2A] text-3xl mb-2"></i>
                        <h2 class="text-4xl font-bold text-[#2F2D2A]">2</h2>
                        <p class="text-[#1B1A19]">Overdue</p>
                    </div>
                </div>
<!-- ------------------------------ CALENDAR ------------------------------- -->
             <div class="bg-[#2f2d2a] rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <button id="prevBtn" class="text-[#ece3d2] hover:text-gray-300 transition-colors"><i class="fas fa-chevron-left"></i></button>
                        <h3 id="monthYear" class="text-[26px] font-semibold"></h3>
                        <button id="nextBtn" class="text-[#ece3d2] hover:text-gray-300 transition-colors"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div class="grid grid-cols-7 text-center text-[#ece3d2] font-medium mb-3">
                        <span>Sun</span>
                        <span>Mon</span>
                        <span>Tue</span>
                        <span>Wed</span>
                        <span>Thu</span>
                        <span>Fri</span>
                        <span>Sat</span>
                    </div>
                    <div id="calendarDays" class="grid grid-cols-7 grid-rows-6 gap-2">
                        </div>
                </div>

<!-- ------------------------------ PROJECTS ------------------------------- -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-[#2f2d2a] rounded-xl p-6 shadow-lg">
                        <h3 class="text-xl font-semibold mb-4">Projects</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="font-semibold">Project name</p>
                                    <p class="text-gray-400 text-sm">No task</p>
                                </div>
                                <i class="fas fa-ellipsis-v text-gray-400 cursor-pointer"></i>
                            </div>
                            <div class="rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="font-semibold">Project name</p>
                                    <p class="text-gray-400 text-sm">No task</p>
                                </div>
                                <i class="fas fa-ellipsis-v text-gray-400 cursor-pointer"></i>
                            </div>
                            <div class="rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="font-semibold">Project name</p>
                                    <p class="text-gray-400 text-sm">No task</p>
                                </div>
                                <i class="fas fa-ellipsis-v text-gray-400 cursor-pointer"></i>
                            </div>
                            <div class="rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="font-semibold">Project name</p>
                                    <p class="text-gray-400 text-sm">No task</p>
                                </div>
                                <i class="fas fa-ellipsis-v text-gray-400 cursor-pointer"></i>
                            </div>
                        </div>
                    </div>
<!-- ------------------------------ ALL TASKS ------------------------------ -->
                    <div class="bg-[#2f2d2a] rounded-xl p-6 shadow-lg">
                        <h3 class="text-[#C7B89B] text-xl font-semibold mb-4">All Task</h3>
                        <div class="space-y-4">
                            <div class="bg-[#C7B89B] rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="text-[#1B1A19] font-semibold">Clean Up Old Code</p>
                                    <p class="text-[#2F2D2A] text-sm">Tue, 20 May 2025</p>
                                </div>
                                <i class="fas fa-ellipsis-v text-[#2F2D2A] cursor-pointer"></i>
                            </div>
                            <div class="bg-[#C7B89B] rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="text-[#1B1A19] font-semibold">Clean Up Old Code</p>
                                    <p class="text-[#2F2D2A] text-sm">Tue, 20 May 2025</p>
                                </div>
                                <i class="fas fa-ellipsis-v text-[#2F2D2A] cursor-pointer"></i>
                            </div>
                            <div class="bg-[#C7B89B] rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="text-[#1B1A19] font-semibold">Clean Up Old Code</p>
                                    <p class="text-[#2F2D2A] text-sm">Tue, 20 May 2025</p>
                                </div>
                                <i class="fas fa-ellipsis-v text-[#2F2D2A] cursor-pointer"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!-- --------------------------- Priority Tasks ---------------------------- -->
            <div class="md:col-span-1 space-y-6">
                <div class="bg-[#C7B89B] rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <span class="bg-[#2F2D2A] text-[#D2C5A5] text-xs font-semibold px-2 py-1 rounded-full">High</span>
                        <i class="fas fa-ellipsis-v text-[#2F2D2A] cursor-pointer"></i>
                    </div>
                    <h3 class="text-[#1B1A19] text-lg font-semibold mb-2">Implement User Authentication Module</h3>
                    <p class="text-[#2F2D2A] text-sm mb-4">Develop a secure user login and registration system</p>
                    <div class="text-[#2F2D2A] flex items-center justify-between text-sm">
                        <p>Progress</p>
                        <p>50 %</p>
                    </div>
                    <div class="w-full bg-[#D9D9D9] rounded-full h-2.5 mt-2">
                        <div class="bg-[#3D3D3D] h-2.5 rounded-full" style="width: 50%"></div>
                    </div>
                    <p class="text-[#2F2D2A] text-xs mt-4">Mon, 10 May 2025</p>
                </div>

                <div class="bg-[#C7B89B] rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <span class="bg-[#2F2D2A] text-[#D2C5A5] text-xs font-semibold px-2 py-1 rounded-full">High</span>
                        <i class="fas fa-ellipsis-v text-[#2F2D2A] cursor-pointer"></i>
                    </div>
                    <h3 class="text-[#1B1A19] text-lg font-semibold mb-2">Implement User Authentication Module</h3>
                    <p class="text-[#2F2D2A] text-sm mb-4">Develop a secure user login and registration system</p>
                    <div class="text-[#2F2D2A] flex items-center justify-between text-sm">
                        <p>Progress</p>
                        <p>50 %</p>
                    </div>
                    <div class="w-full bg-[#D9D9D9] rounded-full h-2.5 mt-2">
                        <div class="bg-[#3D3D3D] h-2.5 rounded-full" style="width: 50%"></div>
                    </div>
                    <p class="text-[#2F2D2A] text-xs mt-4">Mon, 10 May 2025</p>
                </div>

                <div class="bg-[#C7B89B] rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <span class="bg-[#2F2D2A] text-[#D2C5A5] text-xs font-semibold px-2 py-1 rounded-full">High</span>
                        <i class="fas fa-ellipsis-v text-[#2F2D2A] cursor-pointer"></i>
                    </div>
                    <h3 class="text-[#1B1A19] text-lg font-semibold mb-2">Implement User Authentication Module</h3>
                    <p class="text-[#2F2D2A] text-sm mb-4">Develop a secure user login and registration system</p>
                    <div class="text-[#2F2D2A] flex items-center justify-between text-sm">
                        <p>Progress</p>
                        <p>50 %</p>
                    </div>
                    <div class="w-full bg-[#D9D9D9] rounded-full h-2.5 mt-2">
                        <div class="bg-[#3D3D3D] h-2.5 rounded-full" style="width: 50%"></div>
                    </div>
                    <p class="text-[#2F2D2A] text-xs mt-4">Mon, 10 May 2025</p>
                </div>
            </div>
        </main>


  <footer class="fixed bottom-0 left-0 right-0 w-full h-32 bg-[#D2C5A5] rounded-tl-[72px] rounded-tr-[72px] inset-x-0 flex items-center justify-around md:justify-center md:space-x-16 px-8 py-4 shadow-lg">
            <button class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
                <i class="fas fa-user text-[40px] mb-1"></i>
            </button>
            <button class="w-28 h-28 bg-[#ECE3D2] rounded-lg flex items-center justify-center text-[#1B1A19] text-[40px] shadow-xl hover:bg-[#928c80] transition-colors -mt-8 md:-mt-12">
                <i class="fas fa-plus"></i>
            </button>
            <button class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
                <i class="fas fa-clipboard-list text-[40px] mb-1"></i>
            </button>
        </footer>
    </div>


<!-- ------------------------------- SCRIPT -------------------------------- -->
     <script>
        const monthYear = document.getElementById("monthYear");
        const calendarDays = document.getElementById("calendarDays");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");

        let currentDate = new Date();
        let tasks = {}; // Stores tasks keyed by 'YYYY-MM-DD'

        function formatDateKey(date) {
            return date.toISOString().split('T')[0];
        }

        function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDay = firstDay.getDay();
    const totalDays = lastDay.getDate();

<!-- ----------------------------- Test Tasks ------------------------------ -->
    let tasks = {};
    tasks['2025-05-20'] = ['Clean Up Old Code'];
    tasks['2025-05-22'] = ['Meeting with Client', 'Prepare Presentation'];
    tasks['2025-05-28'] = ['Submit Report'];

<!-- ----------------------------- Test tasks ------------------------------ -->

    monthYear.textContent = `${date.toLocaleString("default", { month: "long" })} ${year}`;
    calendarDays.innerHTML = "";

    const totalCells = 42; // 6 rows * 7 days
    let dayCounter = 1;

    for (let i = 0; i < totalCells; i++) {
        const cell = document.createElement("div");

        if (i < startDay || dayCounter > totalDays) {
            cell.classList.add("text-gray-600");
            cell.textContent = ""; // Empty cell
        } else {
            const dateObj = new Date(year, month, dayCounter);
            const dateKey = formatDateKey(dateObj);
            const isToday =
                dayCounter === new Date().getDate() &&
                month === new Date().getMonth() &&
                year === new Date().getFullYear();
            const hasTask = tasks[dateKey] && tasks[dateKey].length > 0;

             cell.className = `py-7 rounded-lg cursor-pointer relative text-center text-gray-100 ${
                        isToday ? 'bg-[#1B1A1B] text-white font-bold' : 'hover:bg-gray-700'
                    }`;

            cell.innerHTML = `${dayCounter}`;

            if (hasTask) {
                const indicator = document.createElement('div');
                indicator.className = 'absolute bottom-1 left-1 right-1 h-1 bg-[#D2C5A5] rounded'; // Small green line
                cell.appendChild(indicator);
            }

            dayCounter++;
        }

        calendarDays.appendChild(cell);




    }
}

        prevBtn.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        });

        nextBtn.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        });



        // Initial render of the calendar
        renderCalendar(currentDate);

        lucide.replace();
    </script>
</body>

</html>


