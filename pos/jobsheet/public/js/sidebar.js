document.addEventListener('DOMContentLoaded', function() {
    // Toggle submenu on click
    const dropdownLinks = document.querySelectorAll('.has-dropdown');
    
    dropdownLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('.nav-item').classList.toggle('active');
        });
    });
    
    // Auto-expand active parent menu
    const activeSubmenuItem = document.querySelector('.submenu-item.active');
    if (activeSubmenuItem) {
        activeSubmenuItem.closest('.nav-item').classList.add('active');
    }
});