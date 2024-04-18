// filter.js

// Function to filter the list based on the selected radio button value
function filterList() {
    // Get the selected radio button value
    const selectedValue = document.querySelector('input[name="filter"]:checked').value;

    // Get all the li elements in the list
    const listItems = document.querySelectorAll('#instanceslist li');

    // Loop through each li element
    listItems.forEach((item) => {
        // Get the value of the data-region attribute
        const region = item.getAttribute('data-type');

        // Check if the region matches the selected value
        if (region === selectedValue || selectedValue === '') {
            // Remove the "d-none" class to show the li element
            item.classList.remove('d-none');
        } else {
            // Add the "d-none" class to hide the li element
            item.classList.add('d-none');
        }
    });
}

function attachClickListener() {
    const patataDiv = document.querySelector('#instanceFilters');
    patataDiv.addEventListener('click', filterList);
}

// Call the function to attach the click event listener
document.addEventListener('DOMContentLoaded', attachClickListener);
