// Initialize KTMenu
KTMenu.init();

// Add click event listener to delete buttons
document.querySelectorAll('[data-kt-action="delete_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Are you sure you want to remove?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('delete_venue', this.getAttribute('data-kt-venue-id'));
            }
        });
    });
});

// Add click event listener to update buttons
document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.emit('update_venue', this.getAttribute('data-kt-venue-id'));
    });
});

// Listen for 'success' event emitted by Livewire
Livewire.on('success', (message) => {
    // Reload the venues-table datatable
    LaravelDataTables['areas-table'].ajax.reload();
});


Livewire.on('showErrorMessage', function (message) {
        // Handle the error message in your JavaScript code, for example, display it using a modal or toast
        Swal.fire({
            text: 'Venue cannot be deleted because it has associated areas.',
            icon: 'error',
            buttonsStyling: false,
            showCancelButton: false,
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'btn btn-primary',
            }
        });
    });