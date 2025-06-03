document.addEventListener('DOMContentLoaded', function() {
    let currentDate = new Date();
    const monthYearElement = document.getElementById('monthYear');
    const calendarDaysElement = document.getElementById('calendarDays');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    function updateCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        
        // Update month and year display
        monthYearElement.textContent = date.toLocaleString('default', { month: 'long', year: 'numeric' });
        
        // Clear existing calendar days
        calendarDaysElement.innerHTML = '';
        
        // Get first day of month and total days
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const firstDayOfWeek = firstDay.getDay();
        const totalDays = lastDay.getDate();
        
        // Add empty cells for days before first of month
        for (let i = 0; i < firstDayOfWeek; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'h-8';
            calendarDaysElement.appendChild(emptyDay);
        }
        
        // Add days of the month
        for (let day = 1; day <= totalDays; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day text-center py-1 rounded hover:bg-[#3d3b38] cursor-pointer text-[#ece3d2]';
            dayElement.textContent = day;
            
            // Set the date attribute
            const currentDateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            dayElement.setAttribute('data-date', currentDateStr);
            
            // Check for tasks on this day
            const tasksForDay = tasksData.filter(task => {
                const taskDate = new Date(task.deadline).toISOString().split('T')[0];
                return taskDate === currentDateStr;
            });
            
            if (tasksForDay.length > 0) {
                const hasHighPriority = tasksForDay.some(task => task.priority === 3);
                const hasMediumPriority = tasksForDay.some(task => task.priority === 2);
                
                if (hasHighPriority) {
                    dayElement.style.textDecoration = 'underline';
                    dayElement.style.textDecorationColor = '#ef4444';
                } else if (hasMediumPriority) {
                    dayElement.style.textDecoration = 'underline';
                    dayElement.style.textDecorationColor = '#eab308';
                } else {
                    dayElement.style.textDecoration = 'underline';
                    dayElement.style.textDecorationColor = '#ece3d2';
                }
            }
            
            calendarDaysElement.appendChild(dayElement);
        }
    }

    // Get tasks data
    const tasksData = JSON.parse(document.getElementById('tasks-data').textContent);
    
    // Initialize calendar
    updateCalendar(currentDate);
    
    // Add event listeners for navigation
    prevBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateCalendar(currentDate);
    });
    
    nextBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateCalendar(currentDate);
    });
}); 