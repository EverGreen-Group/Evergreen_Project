const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});




// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})


const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})





if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})



const switchMode = document.getElementById('switch-mode');

// Function to set theme
function setTheme(isDark) {
    if (isDark) {
        document.body.classList.add('dark');
    } else {
        document.body.classList.remove('dark');
    }
    localStorage.setItem('darkMode', isDark);
    switchMode.checked = isDark;
}

// Load saved theme preference
const savedDarkMode = localStorage.getItem('darkMode') === 'true';
setTheme(savedDarkMode);

switchMode.addEventListener('change', function () {
    setTheme(this.checked);
});



// Get the current page from the window location
const currentPage = window.location.pathname.split("/").pop().toLowerCase(); // Get the file name in lowercase

console.log("Current Page: ", currentPage); // Log the current page for debugging

allSideMenu.forEach(item => {
	const href = item.getAttribute('href').toLowerCase(); // Ensure href is lowercase
	console.log("Link Href: ", href); // Log each link href for debugging

	// If the href matches the current page, add the active class
	if(href === currentPage || href.includes(currentPage)) {
		item.parentElement.classList.add('active');
		console.log("Active Link Found: ", href); // Log which link becomes active
	}
});

function enableEditing() {
    const inputs = document.querySelectorAll('.input');
    const isReadOnly = inputs[0].hasAttribute('readonly'); // Check if it's read-only

    if (isReadOnly) {
        //enable editing
        inputs.forEach(input => {
            input.removeAttribute('readonly');
        });
    } else {
        //disable editing
        inputs.forEach(input => {
            input.setAttribute('readonly', true);
        });
    }
}


function submitmessage(event) {
    event.preventDefault(); 
	
    // Get the form data 
    const form = document.querySelector('.complaint-form');
    const formData = new FormData(form);

    // Check if all required fields are filled (basic validation)
    let isFormValid = true;
    formData.forEach((value, key) => {
        if (!value.trim()) {
            isFormValid = false;
        }
    });

    if (isFormValid) {
        alert("Submit Successful");
    } else {
        alert("Unsuccessful: Please fill in all required fields.");
    }
}

function refreshPage() {
    document.querySelector('.complaint-form').reset();
}




/* SUPPLEMENT MANAGER */
/* DASHBOARD */
    const data = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            label: 'Tea Leaves Collected (kg)',
            data: [500, 540, 120, 750, 900, 500, 290, 600, 670, 750, 900, 0], // Match the labels
            backgroundColor: 'rgba(0, 198, 172, 0.712)',
            borderColor: 'rgba(0, 162, 141, 0.712)',
            borderWidth: 1,
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { enabled: true },
            },
            scales: {
                x: { title: { display: true, text: 'Months' } },
                y: { title: { display: true, text: 'Amount (kg)' }, beginAtZero: true },
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('teaLeavesGraph').getContext('2d');
        new Chart(ctx, config); 
    });
    
/*  LEAF SUPPLY */
const leafdata = {
    labels: ['sent', 'pending', 'confirmed', 'reported'],
    datasets: [{
        label: 'Tea Leaves Confirmations',
        data: [70, 48, 12, 1], 
        backgroundColor: ['#007664bc','#ecb500bc','#8d9f2dbc','#ff0800bc'],
        borderColor: 'rgba(0, 0, 0, 0.3)',
        borderWidth: 1,
    }]
};

const leafconfig = {
    type: 'bar',
    data: leafdata,
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { enabled: true },
        },
        scales: {
            x: { title: { display: true, text: 'Confirmation Status' } },
            y: { title: { display: true, text: 'No of Suppliers' }, beginAtZero: true },
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('teaLeavesConfirmationGraph').getContext('2d');
    new Chart(ctx, leafconfig); 
});

const pie_leafconfig = {
    type: 'pie',
    data: leafdata,
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            tooltip: { enabled: true },
        },
        scales: {
            x: { title: { display: true, text: 'Confirmation Status' } },
            y: { title: { display: true, text: 'No of Suppliers' }, beginAtZero: true },
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('teaLeavesConfirmationChart').getContext('2d');
    new Chart(ctx, pie_leafconfig); 
});

document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('fertilizerOrdersChart').getContext('2d');
    var teaLeavesCollectionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], 
            datasets: [{
                label: 'Tea Leaves Collections',
                data: [32, 210, 583, 156, 284, 515, 502, 389, 412, 479, 500, 0], // Example data 
                fill: false,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Tea Leaves Collections (Monthly)'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Amount'
                    },
                    beginAtZero: true
                }
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('fertilizerOrdersChart').getContext('2d');
    new Chart(ctx, pie_leafconfig); 
});
