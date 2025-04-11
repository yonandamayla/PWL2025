document.addEventListener('DOMContentLoaded', function() {
    // CSRF token setup for AJAX requests
    $.ajaxSetup({ 
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
        } 
    });
    
    // Initialize any common components or behaviors
    initializeComponents();
});

/**
 * Initialize shared UI components and behaviors
 */
function initializeComponents() {
    // Initialize tooltips
    if (typeof $.fn.tooltip !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    // Initialize popovers
    if (typeof $.fn.popover !== 'undefined') {
        $('[data-toggle="popover"]').popover();
    }
    
    // Add fade-out effect to alerts after 5 seconds
    setTimeout(function() {
        $('.alert:not(.alert-important)').fadeOut(500);
    }, 5000);
}

/**
 * Format number as currency
 * @param {number} number - Number to format
 * @param {string} currency - Currency symbol (default: 'Rp')
 * @returns {string} Formatted currency string
 */
function formatCurrency(number, currency = 'Rp') {
    return currency + ' ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/**
 * Format date to Indonesian format
 * @param {string} dateString - Date string to format
 * @returns {string} Formatted date string
 */
function formatDate(dateString) {
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const date = new Date(dateString);
    return date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
}