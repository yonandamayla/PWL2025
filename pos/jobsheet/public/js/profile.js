$(function() {
    // Handle profile picture preview
    $('#profile_picture').on('change', function() {
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
            $(this).next('.custom-file-label').text(file.name);
        }
    });
    
    // Auto activate tab from URL hash
    let hash = window.location.hash;
    if (hash) {
        $('.profile-menu a[href="' + hash + '"]').tab('show');
    }
    
    // Update URL hash on tab change
    $('.profile-menu a').on('click', function (e) {
        history.pushState(null, null, $(this).attr('href'));
    });
    
    // Show immediate success message when password is changed
    $('#passwordChangeForm').on('submit', function(e) {
        // Validate form
        if (this.checkValidity()) {
            // Show success message immediately
            if ($('#password').val() === $('#password_confirmation').val() && 
                $('#current_password').val() !== '' &&
                $('#password').val() !== '') {
                setTimeout(function() {
                    $('#passwordSuccessMsg').removeClass('d-none').hide().fadeIn();
                }, 500);
            }
        }
    });
});