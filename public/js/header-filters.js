// Header Filters JavaScript
$(document).ready(function() {
    // Get all filter dropdowns
    const filters = {
        category: $('select[name="category_filter"]'),
        fabric: $('select[name="fabric_filter"]'),
        color: $('select[name="color_filter"]'),
        size: $('select[name="size_filter"]'),
        bottomType: $('select[name="bottom_type_filter"]'),
        priceRange: $('select[name="price_range"]')
    };

    // Add change event listeners to all filters
    Object.values(filters).forEach(filter => {
        if (filter.length > 0) {
            filter.on('change', applyFilters);
        }
    });

    // Function to apply filters using AJAX
    function applyFilters() {
        const filterData = {
            category: filters.category.val() || '',
            fabric: filters.fabric.val() || '',
            color: filters.color.val() || '',
            size: filters.size.val() || '',
            bottomType: filters.bottomType.val() || '',
            priceRange: filters.priceRange.val() || ''
        };

        // Remove empty filters
        Object.keys(filterData).forEach(key => {
            if (!filterData[key]) {
                delete filterData[key];
            }
        });

        // Show loading indicator
        showLoading();

        // Make AJAX request
        $.ajax({
            url: '/',
            method: 'GET',
            data: filterData,
            success: function(response) {
                // Extract the clothes section from the response
                const tempDiv = $('<div>').html(response);
                const newClothesSection = tempDiv.find('.occasion .container').html();
                
                // Update the clothes section
                $('.occasion .container').html(newClothesSection);
                
                // Update URL without page refresh
                updateURL(filterData);
                
                // Hide loading indicator
                hideLoading();
                
                // Show success message if no clothes found
                if (newClothesSection.includes('No clothes available')) {
                    showMessage('No clothes found with the selected filters.', 'info');
                }
            },
            error: function(xhr, status, error) {
                console.error('Filter request failed:', error);
                hideLoading();
                showMessage('Error applying filters. Please try again.', 'error');
            }
        });
    }

    // Function to show loading indicator
    function showLoading() {
        // Create loading overlay if it doesn't exist
        if ($('#filter-loading').length === 0) {
            $('body').append(`
                <div id="filter-loading" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.5);
                    z-index: 9999;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                ">
                    <div style="
                        background: white;
                        padding: 20px;
                        border-radius: 10px;
                        text-align: center;
                    ">
                        <div class="spinner-border text-warning" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Applying filters...</p>
                    </div>
                </div>
            `);
        } else {
            $('#filter-loading').show();
        }
    }

    // Function to hide loading indicator
    function hideLoading() {
        $('#filter-loading').hide();
    }

    // Function to show message
    function showMessage(message, type = 'info') {
        // Remove existing messages
        $('.filter-message').remove();
        
        const alertClass = type === 'error' ? 'alert-danger' : 'alert-info';
        
        $('body').append(`
            <div class="filter-message alert ${alertClass} alert-dismissible fade show" style="
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                max-width: 300px;
            ">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `);
        
        // Auto-hide after 3 seconds
        setTimeout(() => {
            $('.filter-message').fadeOut();
        }, 3000);
    }

    // Function to update URL without page refresh
    function updateURL(filterData) {
        const queryString = $.param(filterData);
        const newURL = window.location.pathname + (queryString ? '?' + queryString : '');
        window.history.pushState(filterData, '', newURL);
    }

    // Function to set filter values from URL parameters
    function setFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        
        Object.keys(filters).forEach(key => {
            const filter = filters[key];
            if (filter.length > 0 && urlParams.has(key)) {
                filter.val(urlParams.get(key));
            }
        });
    }

    // Set filters from URL on page load
    setFiltersFromURL();

    // Handle browser back/forward buttons
    $(window).on('popstate', function(event) {
        if (event.originalEvent.state) {
            // Reset filters to URL state
            Object.keys(filters).forEach(key => {
                const filter = filters[key];
                if (filter.length > 0) {
                    filter.val(event.originalEvent.state[key] || '');
                }
            });
            
            // Reapply filters
            applyFilters();
        }
    });
}); 