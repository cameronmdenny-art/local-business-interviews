/**
 * Local Business Interviews - Frontend JavaScript
 * Handles searchable dropdowns and interactive elements
 */

(function($) {
    'use strict';

    /**
     * Initialize searchable selects
     */
    function initSearchableSelects() {
        $('.lbi-searchable-select').each(function() {
            var $select = $(this);
            var selectId = $select.attr('id') || 'select-' + Math.random().toString(36).substr(2, 9);
            $select.attr('id', selectId);

            // Create wrapper
            var $wrapper = $('<div class="lbi-select-wrapper"></div>');
            $select.wrap($wrapper);

            // Create search input
            var label = $select.prev('label').text() || 'Search';
            var $searchInput = $(
                '<input type="text" class="lbi-select-search-input" ' +
                'placeholder="Search ' + label.toLowerCase() + '..." ' +
                'aria-label="Search ' + label.toLowerCase() + '">'
            );

            $select.before($searchInput);

            // Handle search input
            $searchInput.on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                var $options = $select.find('option');

                $options.each(function() {
                    var $option = $(this);
                    var optionText = $option.text().toLowerCase();

                    if (searchTerm === '' || optionText.includes(searchTerm)) {
                        $option.show();
                    } else {
                        $option.hide();
                    }
                });

                // Show first non-hidden option
                var $visibleOptions = $options.filter(function() {
                    return $(this).css('display') !== 'none';
                });

                if ($visibleOptions.length > 0) {
                    $select.prop('size', Math.min($visibleOptions.length + 1, 10));
                }
            });

            // Handle select change
            $select.on('change', function() {
                var selectedText = $select.find('option:selected').text();
                $searchInput.val('').trigger('keyup');
                // Reset size
                $(this).prop('size', 1);
            });

            // Handle focus
            $select.on('focus', function() {
                $(this).addClass('focused');
            });

            $select.on('blur', function() {
                $(this).removeClass('focused');
            });
        });
    }

    /**
     * Filter directory on category change
     */
    function initDirectoryFilters() {
        $('.lbi-directory-filters').on('change', 'select', function() {
            // Optional: Auto-submit form on selection
            // Uncomment to enable auto-filter on select change
            // $(this).closest('form').submit();
        });

        // Add loading state
        $('.lbi-directory-filters').on('submit', function() {
            var $button = $(this).find('button[type="submit"]');
            $button.prop('disabled', true).append('<span class="lbi-loading"> Loading...</span>');
        });
    }

    /**
     * Add focus styles to form elements
     */
    function initFormStyles() {
        $(document).on('focus', '.lbi-searchable-select, .lbi-select-search-input', function() {
            $(this).closest('.lbi-filter-field').addClass('focused');
        });

        $(document).on('blur', '.lbi-searchable-select, .lbi-select-search-input', function() {
            $(this).closest('.lbi-filter-field').removeClass('focused');
        });
    }

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initSearchableSelects();
        initDirectoryFilters();
        initFormStyles();
    });

    // Reinitialize if content is dynamically loaded
    $(document).on('updated_checkout', function() {
        initSearchableSelects();
    });

})(jQuery);

/**
 * Keyboard navigation for select dropdowns
 */
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('.lbi-searchable-select');

    selects.forEach(function(select) {
        select.addEventListener('keydown', function(e) {
            // Allow standard select keyboard navigation
            if (e.key === 'ArrowUp' || e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
                // Let browser handle these
                return;
            }

            // Type to search
            if (e.key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) {
                var searchInput = this.previousElementSibling;
                if (searchInput && searchInput.classList.contains('lbi-select-search-input')) {
                    searchInput.value += e.key;
                    searchInput.dispatchEvent(new Event('keyup'));
                    e.preventDefault();
                }
            }
        });
    });
});
