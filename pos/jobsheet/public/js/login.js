document.addEventListener('DOMContentLoaded', function() {
    // Add floating label functionality
    document.querySelectorAll('.form-floating input').forEach(input => {
        if (input.value) {
            input.parentNode.classList.add('focused');
        }
        
        input.addEventListener('focus', () => {
            input.parentNode.classList.add('focused');
        });
        
        input.addEventListener('blur', () => {
            if (!input.value) {
                input.parentNode.classList.remove('focused');
            }
        });
    });

    // Password visibility toggle
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordField = document.getElementById('password');
    
    if (passwordToggle && passwordField) {
        passwordToggle.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            // Toggle icon
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // Demo account toggle button
    const demoToggle = document.getElementById('demoToggle');
    const demoAccounts = document.getElementById('demoAccounts');
    
    if (demoToggle && demoAccounts) {
        demoToggle.addEventListener('click', function() {
            demoAccounts.classList.toggle('show');
        });
    }

    // Auto-fill on demo account click
    document.querySelectorAll('.demo-account').forEach(account => {
        account.addEventListener('click', function() {
            const email = this.querySelector('strong').innerText;
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password123';
            
            // Trigger focus/blur to update floating labels
            document.getElementById('email').focus();
            document.getElementById('email').blur();
            document.getElementById('password').focus();
            document.getElementById('password').blur();
        });
    });

    // Form submission loading state
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    if (loginForm && loginBtn) {
        loginForm.addEventListener('submit', function() {
            loginBtn.classList.add('btn-loading');
        });
    }
});