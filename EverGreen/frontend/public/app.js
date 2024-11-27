document.addEventListener('DOMContentLoaded', () => {
    // Fetch and display worker statistics
    fetchWorkerStats();

    // Fetch and display dashboard widgets
    fetchDashboardWidgets();

    // Fetch and display announcements
    fetchAnnouncements();

    // Add event listeners
    document.querySelector('#logout').addEventListener('click', logout);
    document.querySelector('#collapse').addEventListener('click', toggleSidebar);
});

function fetchWorkerStats() {
    // Simulating API call
    const stats = {
        normalWorkers: { total: 60, present: 50 },
        drivers: { total: 15, active: 4 },
        operators: { total: 15, activeMachines: 4 },
        driverAssistants: { total: 15, active: 4 }
    };

    const statsContainer = document.querySelector('.worker-stats');
    for (const [key, value] of Object.entries(stats)) {
        const div = document.createElement('div');
        div.innerHTML = `
            <h3>${key}</h3>
            <p>Total: ${value.total}</p>
            <p>${Object.keys(value)[1]}: ${Object.values(value)[1]}</p>
        `;
        statsContainer.appendChild(div);
    }
}

function fetchDashboardWidgets() {
    // Simulating API call
    const widgets = {
        totalEmployees: { count: 105, men: 60, women: 45, growth: '+2%' },
        talentRequest: { count: 16, men: 6, women: 10, growth: '+5%' },
        upcomingTasks: [
            { name: 'Tea leaves withering', time: '11:30 AM', priority: 'high' },
            { name: 'Tea Leaves Rolling', time: '10:30 AM', priority: 'medium' },
            { name: 'Tea Leaves Oxidation', time: '09:15 AM', priority: 'low' },
            { name: 'Tea Leaves Firing', time: '11:30 AM', priority: 'high' }
        ]
    };

    const widgetsContainer = document.querySelector('.dashboard-widgets');
    for (const [key, value] of Object.entries(widgets)) {
        const div = document.createElement('div');
        div.innerHTML = `<h3>${key}</h3>`;
        if (key !== 'upcomingTasks') {
            div.innerHTML += `
                <p>Total: ${value.count}</p>
                <p>Men: ${value.men}, Women: ${value.women}</p>
                <p>Growth: ${value.growth}</p>
            `;
        } else {
            const ul = document.createElement('ul');
            value.forEach(task => {
                const li = document.createElement('li');
                li.textContent = `${task.name} - ${task.time} (${task.priority})`;
                ul.appendChild(li);
            });
            div.appendChild(ul);
        }
        widgetsContainer.appendChild(div);
    }
}

function fetchAnnouncements() {
    // Simulating API call
    const announcements = [
        { message: 'Leaf Collecting needed assigned driver #4545', time: '5 Minutes ago' },
        { message: 'Machine Working #7878', time: 'Yesterday, 12:30 PM' },
        { message: 'Worker drying and sorting #3434', time: 'Yesterday, 09:15 AM' }
    ];

    const announcementsContainer = document.querySelector('.announcements');
    announcements.forEach(announcement => {
        const div = document.createElement('div');
        div.innerHTML = `
            <p>${announcement.message}</p>
            <small>${announcement.time}</small>
        `;
        announcementsContainer.appendChild(div);
    });
}

function logout() {
    // Implement logout logic
    console.log('Logging out...');
}

function toggleSidebar() {
    // Implement sidebar collapse/expand logic
    console.log('Toggling sidebar...');
}