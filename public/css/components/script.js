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




