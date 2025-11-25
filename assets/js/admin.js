document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('form[onsubmit*="confirm"]');

    deleteButtons.forEach(form => {
        form.addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to delete this item?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    const fileInputs = document.querySelectorAll('input[type="file"]');

    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                const maxSize = 5 * 1024 * 1024;

                if (!allowedTypes.includes(file.type)) {
                    alert('Please upload only JPG, PNG, or GIF images.');
                    e.target.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    alert('File size must be less than 5MB.');
                    e.target.value = '';
                    return;
                }
            }
        });
    });

    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
