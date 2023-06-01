// Get the current date
var today = new Date().toISOString().split('T')[0];
// Set the minimum value for check-in date
document.getElementById('check_in').setAttribute('min', today);

// Disable past dates and occupied dates in the check-out date input
document.getElementById('check_in').addEventListener('change', function() {
    var checkInDate = this.value;
    document.getElementById('check_out').setAttribute('min', checkInDate);
});

  