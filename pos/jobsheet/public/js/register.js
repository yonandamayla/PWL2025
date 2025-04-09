document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle for password confirmation
    const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
    const confirmPasswordField = document.getElementById('password_confirmation');
    
    if (confirmPasswordToggle && confirmPasswordField) {
        confirmPasswordToggle.addEventListener('click', function() {
            const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.setAttribute('type', type);
            
            // Toggle icon
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // Form submission loading state
    const registerForm = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerBtn');
    
    if (registerForm && registerBtn) {
        registerForm.addEventListener('submit', function() {
            registerBtn.classList.add('btn-loading');
        });
    }

    // Password match validation
    const passwordField = document.getElementById('password');
    
    if (passwordField && confirmPasswordField) {
        function validatePasswordMatch() {
            if (confirmPasswordField.value !== passwordField.value) {
                confirmPasswordField.setCustomValidity('Kata sandi tidak cocok.');
            } else {
                confirmPasswordField.setCustomValidity('');
            }
        }

        passwordField.addEventListener('change', validatePasswordMatch);
        confirmPasswordField.addEventListener('keyup', validatePasswordMatch);
    }
});