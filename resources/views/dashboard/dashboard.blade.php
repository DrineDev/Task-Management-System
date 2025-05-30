<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
        <title>dashboard</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    </head>
    <body>
<!-- ------------------------------- TOP BAR ------------------------------- -->

<div class="w-full h-auto md:h-32 px-4 md:px-8 py-4 bg-[#D2C5A5] rounded-bl-[36px] md:rounded-bl-[72px] rounded-br-[36px] md:rounded-br-[72px] flex items-center justify-between">
    <div class="flex items-center">
        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden">
            <img src="https://scontent.fceb5-1.fna.fbcdn.net/v/t1.15752-9/494691149_1210378300185800_1251328112097693854_n.png?_nc_cat=105&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeFXKITHiyuCpIfzfDxcS4uRa_MhWbjLagJr8yFZuMtqAhip9fubz25PYlenTJBdUsvf4VUDaVSickdElvZeuh5p&_nc_ohc=ns0brLQtLRgQ7kNvwEP32KB&_nc_oc=Adnj59VG1ltNRLdgb2PQU5pZf2VP4k8XBTXvuwNsl2rVkwaEwtP4LRMwR1NFoe8KhIQ&_nc_zt=23&_nc_ht=scontent.fceb5-1.fna&oh=03_Q7cD2QFbj5GEZqig7KO5FHZirrcHz1-q3-_XaMrcHdtR5_hMdA&oe=685F4105" alt="User Avatar" class="w-full h-full object-cover">
        </div>
        <div class="ml-3">
            <h2 class="text-[24px] md:text-[40px] font-semibold text-[#2F2D2A]">Jarod Rebalde</h2>
            <p class="text-[12px] md:text-[16px] text-[#2F2D2A]">Welcome Back</p>
        </div>
    </div>
    <button class="w-16 h-16 md:w-20 md:h-20 bg-[#2F2D2A] text-[#D2C5A5] rounded-full p-2 hover:text-[#C7B89B] focus:outline-none focus:ring-2 focus:ring-gray-400 flex items-center justify-center">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 md:size-10">
    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
    </svg>
    </button>
</div>

<!-- ----------------------------------------------------------------------- -->
<!--                             DASHBOARD MAIN                              -->
<!-- ----------------------------------------------------------------------- -->

    <main class="grid grid-cols-1 md:grid-cols-3 gap-6 m-9 pb-32 md:pb-32">
<!-- ------------------------------- SEARCH -------------------------------- -->
            <div class="md:col-span-2 space-y-6 md:space-y-5">
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
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-[#C7B89B]">Projects</h3>
                            <button id="openAddProjectModalBtn" class="bg-[#C7B89B] text-[#2F2D2A] rounded-md px-3 py-2 font-bold hover:bg-[#B8A98B] focus:outline-none focus:shadow-outline">
                                Add Project
                            </button>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="font-semibold">Project name</p>
                                    <p class="text-gray-400 text-sm">No task</p>
                                </div>
                                 <div class="relative">
                                    <i class="fas fa-ellipsis-v text-[#D2C5A5] cursor-pointer task-settings-icon"></i>
                                    <div class="absolute right-0 w-44 z-40 bg-[#3D3D3D] rounded-md shadow-lg hidden task-options">
                                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" onclick="EditProject()">Edit</button>
                                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" onclick="deleteProject()">Delete</button>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="font-semibold">Project name</p>
                                    <p class="text-gray-400 text-sm">No task</p>
                                </div>
                                 <div class="relative">
                                    <i class="fas fa-ellipsis-v text-[#D2C5A5] cursor-pointer task-settings-icon"></i>
                                    <div class="absolute right-0 w-44 z-40 bg-[#3D3D3D] rounded-md shadow-lg hidden task-options">
                                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" onclick="EditProject()">Edit</button>
                                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" onclick="deleteProject()">Delete</button>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="font-semibold">Project name</p>
                                    <p class="text-gray-400 text-sm">No task</p>
                                </div>
                                 <div class="relative">
                                    <i class="fas fa-ellipsis-v text-[#D2C5A5] cursor-pointer task-settings-icon"></i>
                                    <div class="absolute right-0 w-44 z-40 bg-[#3D3D3D] rounded-md shadow-lg hidden task-options">
                                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" onclick="EditProject()">Edit</button>
                                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" onclick="deleteProject()">Delete</button>
                                    </div>
                                </div>
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
                                 <div class="relative">
                                    <i class="fas fa-ellipsis-v text-[#2F2D2A] cursor-pointer task-settings-icon"></i>
                                    <div class="absolute right-0 w-44 z-40 bg-[#3D3D3D] rounded-md shadow-lg hidden task-options">
                                        <button id="openEditTaskModalBtn" class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" onclick="editTask()">Edit</button>
                                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" onclick="deleteTask()">Delete</button>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-[#C7B89B] rounded-lg p-4 flex items-center justify-between shadow-md">
                                <div>
                                    <p class="text-[#1B1A19] font-semibold">Clean Up Old Code</p>
                                    <p class="text-[#2F2D2A] text-sm">Tue, 20 May 2025</p>
                                </div>
                                <div class="relative">
                                    <i class="fas fa-ellipsis-v text-[#2F2D2A] cursor-pointer task-settings-icon"></i>
                                    <div class="absolute right-0 w-44 z-40 bg-[#3D3D3D] rounded-md shadow-lg hidden task-options">
                                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" onclick="editTask()">Edit</button>
                                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" onclick="deleteTask()">Delete</button>
                                    </div>
                                </div>
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

<!-- ------------------------------- NAVBAR -------------------------------- -->

  <footer class="fixed bottom-0 left-0 right-0 w-full h-32 z-30 bg-[#D2C5A5] rounded-tl-[72px] rounded-tr-[72px] inset-x-0 flex items-center justify-around md:justify-center md:space-x-16 px-8 py-4 shadow-lg">
            <button class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
                <i class="fas fa-user text-[40px] mb-1"></i>
            </button>
            <button id="addbutton" class="w-28 h-28 md:w-24 md:h-24 bg-[#ECE3D2] rounded-lg flex items-center justify-center text-[#1B1A19] text-[40px] md:text-[20px] shadow-xl hover:bg-[#928c80] transition-colors -mt-8 md:-mt-12">
                <i class="fas fa-plus"></i>
            </button>
            <button class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
                <i class="fas fa-clipboard-list text-[40px] mb-1"></i>
            </button>
        </footer>

<!-- ----------------------------------------------------------------------- -->
<!--                                 MODALS                                  -->
<!-- ----------------------------------------------------------------------- -->


<!-- ------------------------------- ADD TASK MODAL ------------------------------- -->
<div id="addTaskModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50 hidden">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#2F2D2A] rounded-3xl p-8 w-full max-w-md">
        <div class="flex items-center justify-between mb-6">
            <button id="closeAddTaskModal" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                <i class="fas fa-arrow-left text-xl"></i> Back
            </button>
            <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Create New Task</h2>
            <div></div> </div>

        <div class="space-y-4">
            <div>
                <label for="taskTitle" class="block text-[#C7B89B] text-sm font-bold mb-2">Title</label>
                <input type="text" id="taskTitle" placeholder="Write Here"
                       class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
            </div>
            <div>
                <label for="taskDescription" class="block text-[#C7B89B] text-sm font-bold mb-2">Description</label>
                <textarea id="taskDescription" placeholder="Write Here"
                          class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="startDate" class="block text-[#C7B89B] text-sm font-bold mb-2">Start Date</label>
                    <div class="relative">
                        <input type="date" id="startDate"
                               class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
                    </div>
                </div>
                <div>
                    <label for="endDate" class="block text-[#C7B89B] text-sm font-bold mb-2">End Date</label>
                    <div class="relative">
                        <input type="date" id="endDate"
                               class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-[#C7B89B] text-sm font-bold mb-2">Category</label>
                <div class="flex items-center space-x-2">
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold">+</button>
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold">Work</button>
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold">Personal</button>
                    </div>
            </div>
            <div>
                <label class="block text-[#C7B89B] text-sm font-bold mb-2">Priority</label>
                <div class="flex items-center space-x-2">
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center"><i class="fas fa-minus mr-1"></i>
                    Low</button>
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center"><i class="fas fa-equals mr-1"></i> Normal</button>
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center"><i class="fas fa-exclamation mr-1"></i> High</button>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <button class="w-full bg-[#D2C5A5] text-[#2F2D2A] rounded-xl py-3 font-bold hover:bg-[#C7B89B] focus:outline-none focus:shadow-outline">
                Create Task
            </button>
        </div>
    </div>
</div>


<!-- ------------------------------- ADD PROJECT MODAL ------------------------------- -->

<div id="addProjectModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50 hidden">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#3D3D3D] rounded-3xl p-8 w-full max-w-md">
        <div class="flex items-center justify-between mb-6">
            <button id="closeAddProjectModal" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                <i class="fas fa-arrow-left text-xl"></i> Back
            </button>
            <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Create New Project</h2>
            <div></div>
        </div>
        <div class="space-y-4">
            <div>
                <label for="projectTitle" class="block text-[#C7B89B] text-sm font-bold mb-2">Project Title</label>
                <input type="text" id="projectTitle" placeholder="Enter project title"
                       class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
            </div>
            <div>
                <label for="projectDescription" class="block text-[#C7B89B] text-sm font-bold mb-2">Project Description</label>
                <textarea id="projectDescription" placeholder="Enter project description"
                          class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]"></textarea>
            </div>
            </div>
        <div class="mt-8">
            <button class="w-full bg-[#D2C5A5] text-[#2F2D2A] rounded-xl py-3 font-bold hover:bg-[#C7B89B] focus:outline-none focus:shadow-outline">
                Create Project
            </button>
        </div>
    </div>
</div>

<!-- --------------------------- EDIT TASK MODAL --------------------------- -->
<div id="editTaskModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50 hidden">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#2F2D2A] rounded-3xl p-8 w-full max-w-md">
        <div class="flex items-center justify-between mb-6">
            <button id="closeEditTaskModal" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                <i class="fas fa-arrow-left text-xl"></i> Back
            </button>
            <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Edit Task</h2>
            <div></div>
        </div>
        <div class="space-y-4">
            <div>
                <label for="editTaskTitle" class="block text-[#C7B89B] text-sm font-bold mb-2">Change Title</label>
                <input type="text" id="editTaskTitle" placeholder="Write Here"
                       class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
            </div>
            <div>
                <label for="editTaskDescription" class="block text-[#C7B89B] text-sm font-bold mb-2">Change Description</label>
                <textarea id="editTaskDescription" placeholder="Write Here"
                          class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="editStartDate" class="block text-[#C7B89B] text-sm font-bold mb-2">Start Date</label>
                    <div class="relative">
                        <input type="date" id="editStartDate"
                               class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
                    </div>
                </div>
                <div>
                    <label for="editEndDate" class="block text-[#C7B89B] text-sm font-bold mb-2">End Date</label>
                    <div class="relative">
                        <input type="date" id="editEndDate"
                               class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-[#C7B89B] text-sm font-bold mb-2">Change Category</label>
                <div class="flex items-center space-x-2">
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold">+</button>
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold">Work</button>
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold">Personal</button>
                </div>
            </div>
            <div>
                <label class="block text-[#C7B89B] text-sm font-bold mb-2">Priority</label>
                <div class="flex items-center space-x-2">
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center"><i class="fas fa-minus mr-1"></i> Low</button>
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center"><i class="fas fa-equals mr-1"></i> Normal</button>
                    <button class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center"><i class="fas fa-exclamation mr-1"></i> High</button>
                </div>
            </div>
        </div>
        <div class="mt-8">
            <button class="w-full bg-[#D2C5A5] text-[#2F2D2A] rounded-xl py-3 font-bold hover:bg-[#C7B89B] focus:outline-none focus:shadow-outline">
                Save Changes
            </button>
        </div>
    </div>
</div>

<!-- --------------------------- EDIT PROJECT MODAL --------------------------- -->
<div id="editProjectModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50 hidden">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#3D3D3D] rounded-3xl p-8 w-full max-w-md">
        <div class="flex items-center justify-between mb-6">
            <button id="closeEditProjectModal" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                <i class="fas fa-arrow-left text-xl"></i> Back
            </button>
            <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Edit Project</h2>
            <div></div>
        </div>
        <div class="space-y-4">
            <div>
                <label for="editProjectTitle" class="block text-[#C7B89B] text-sm font-bold mb-2">Project Title</label>
                <input type="text" id="editProjectTitle" placeholder="Edit project title"
                       class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
            </div>
            <div>
                <label for="editProjectDescription" class="block text-[#C7B89B] text-sm font-bold mb-2">Project Description</label>
                <textarea id="editProjectDescription" placeholder="Edit project description"
                          class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]"></textarea>
            </div>
        </div>
        <div class="mt-8">
            <button class="w-full bg-[#D2C5A5] text-[#2F2D2A] rounded-xl py-3 font-bold hover:bg-[#C7B89B] focus:outline-none focus:shadow-outline">
                Save Changes
            </button>
        </div>
    </div>
</div>

<!-- ----------------------------------------------------------------------- -->
<!--                                 SCRIPT -------------------------------- -->
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


<!-- ------------------------- SCRIPTS FOR MODALS -------------------------- -->


        const addbutton = document.getElementById('addbutton');
    const addTaskModal = document.getElementById('addTaskModal');
    const closeAddTaskModal = document.getElementById('closeAddTaskModal');
    const addProjectModal = document.getElementById('addProjectModal');
    const closeAddProjectModal = document.getElementById('closeAddProjectModal');
    const openAddProjectModalBtn = document.getElementById('openAddProjectModalBtn');
    const closeEditTaskModal = document.getElementById('closeEditTaskModal');
    const editTaskModal = document.getElementById('editTaskModal');
    const openEditTaskModalBtn = document.getElementById('openEditTaskModalBtn');
    const closeEditProjectModal = document.getElementById('closeEditProjectModal');
    const editProjectModal = document.getElementById('editProjectModal');

    function openModal(modal) {
        modal.classList.remove('hidden');
    }

    function closeModal(modal) {
        modal.classList.add('hidden');
    }

    addbutton.addEventListener('click', (event) => {
        event.stopPropagation(); // Prevent immediate close
        openModal(addTaskModal);
    });

    closeAddTaskModal.addEventListener('click', () => closeModal(addTaskModal));
    closeAddProjectModal.addEventListener('click', () => closeModal(addProjectModal));
    closeEditTaskModal.addEventListener('click', () => closeModal(editTaskModal));
    closeEditProjectModal.addEventListener('click', () => closeModal(editProjectModal));


    if (openAddProjectModalBtn) {
        openAddProjectModalBtn.addEventListener('click', () => {
            openModal(addProjectModal);
        });
    }



    const taskSettingsIcons = document.querySelectorAll('.task-settings-icon');

    taskSettingsIcons.forEach(icon => {
        icon.addEventListener('click', (event) => {
            event.stopPropagation();
            const taskOptions = icon.nextElementSibling;
            taskOptions.classList.toggle('hidden');
        });
    });

    window.addEventListener('click', (event) => {
        document.querySelectorAll('.task-options').forEach(options => {
            options.classList.add('hidden');
        });
    });


    function editTask() {
        openModal(editTaskModal);
    }

    function deleteTask() {
        alert("Delete task functionality will be implemented here.");
        //  Replace this with your actual delete task logic
    }

    function EditProject() {
        openModal(editProjectModal);
    }

    function deleteProject() {
        alert("Delete task functionality will be implemented here.");
        //  Replace this with your actual delete task logic
    }

    if (closeEditProjectModal) {
        closeEditProjectModal.addEventListener('click', closeEditProjectModalFunc);
    }
    </script>
</body>

</html>


