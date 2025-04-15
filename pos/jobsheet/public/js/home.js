$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Handle Update button click (can be expanded later)
    $('.btn-info').on('click', function() {
        // Currently just redirects to items page via href
        // You can add more functionality here if needed
    });

    // Refresh dashboard data every 5 minutes
    function refreshDashboardData() {
        // This would be implemented with AJAX if real-time data is needed
        console.log('Dashboard data refresh capability ready');
    }
    
    // Set up refresh timer (commented out for now)
    // setInterval(refreshDashboardData, 300000); // 5 minutes
});