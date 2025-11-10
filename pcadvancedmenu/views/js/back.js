/**
 * PC Advanced Menu - Admin Back Office JavaScript
 * Handles conditional field display and color pickers
 */

$(document).ready(function() {

    /**
     * URL Type conditional display
     * Show/hide system URL or custom URL fields based on selection
     */
    $('input[name="url_type"]').on('change', function() {
        var val = $('input[name="url_type"]:checked').val();

        if (val == '0') {
            // System URL selected
            $('#system-url-wrapper').addClass('active').show();
            $('#custom-url-wrapper').removeClass('active').hide();
        } else if (val == '1') {
            // Custom URL selected
            $('#system-url-wrapper').removeClass('active').hide();
            $('#custom-url-wrapper').addClass('active').show();
        } else {
            // No link selected
            $('#system-url-wrapper').removeClass('active').hide();
            $('#custom-url-wrapper').removeClass('active').hide();
        }
    });

    /**
     * Icon Type conditional display
     * Show/hide icon image upload or icon class field based on selection
     */
    $('input[name="icon_type"]').on('change', function() {
        var val = $('input[name="icon_type"]:checked').val();

        if (val == '0') {
            // Icon image selected
            $('#icon-image-wrapper').addClass('active').show();
            $('#icon-class-wrapper').removeClass('active').hide();
        } else {
            // Icon class selected
            $('#icon-image-wrapper').removeClass('active').hide();
            $('#icon-class-wrapper').addClass('active').show();
        }
    });

    /**
     * Submenu Type conditional display
     * Show/hide submenu options and grid builder based on selection
     */
    $('input[name="submenu_type"]').on('change', function() {
        var val = $('input[name="submenu_type"]:checked').val();

        if (val > 0) {
            // Simple or Grid submenu selected
            $('#submenu-options-wrapper').addClass('active').show();
        } else {
            // No submenu selected
            $('#submenu-options-wrapper').removeClass('active').hide();
        }

        if (val == 2) {
            // Grid submenu selected - show grid builder
            $('#grid-builder-wrapper').addClass('active').show();
        } else {
            // Hide grid builder
            $('#grid-builder-wrapper').removeClass('active').hide();
        }
    });

    /**
     * Initialize color pickers using Spectrum.js
     * Only if Spectrum library is loaded
     */
    if (typeof $.fn.spectrum !== 'undefined') {
        $('.color-picker').spectrum({
            preferredFormat: "hex",
            showInput: true,
            showAlpha: false,
            showPalette: true,
            showInitial: true,
            allowEmpty: true,
            clickoutFiresChange: true,
            palette: [
                // Grayscale
                ["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"],
                // Primary colors
                ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"],
                // Light shades
                ["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc"],
                // Medium shades
                ["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd"],
                // Dark shades
                ["#cc0000", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"],
                // Very dark shades
                ["#990000", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"]
            ]
        });
    } else {
        // Fallback to HTML5 color input if Spectrum is not available
        console.log('Spectrum.js not loaded, using HTML5 color inputs');
        $('.color-picker').attr('type', 'color');
    }

    /**
     * Trigger initial state on page load
     * This ensures correct fields are visible when editing existing items
     */
    if ($('input[name="url_type"]:checked').length > 0) {
        $('input[name="url_type"]:checked').trigger('change');
    } else {
        // Default to system URL if nothing selected
        $('input[name="url_type"][value="0"]').prop('checked', true).trigger('change');
    }

    if ($('input[name="icon_type"]:checked').length > 0) {
        $('input[name="icon_type"]:checked').trigger('change');
    } else {
        // Default to icon class if nothing selected
        $('input[name="icon_type"][value="1"]').prop('checked', true).trigger('change');
    }

    if ($('input[name="submenu_type"]:checked').length > 0) {
        $('input[name="submenu_type"]:checked').trigger('change');
    } else {
        // Default to no submenu if nothing selected
        $('input[name="submenu_type"][value="0"]').prop('checked', true).trigger('change');
    }

    /**
     * Form validation before submit
     * Ensure required fields are filled based on selections
     */
    $('form[name="form"]').on('submit', function(e) {
        var urlType = $('input[name="url_type"]:checked').val();
        var hasError = false;
        var errorMessage = '';

        // Validate based on URL type
        if (urlType == '0') {
            // System URL - check if category or CMS is selected for appropriate types
            var itemType = $('select[name="item_type"]').val();
            if (itemType == 'category' && $('select[name="id_category"]').val() == '0') {
                hasError = true;
                errorMessage = 'Please select a category for system URL type.';
            } else if (itemType == 'cms' && $('select[name="id_cms"]').val() == '0') {
                hasError = true;
                errorMessage = 'Please select a CMS page for system URL type.';
            }
        } else if (urlType == '1') {
            // Custom URL - check if link is provided
            var customLink = $('input[name="link"]').val();
            if (!customLink || customLink.trim() === '') {
                hasError = true;
                errorMessage = 'Please enter a custom URL.';
            }
        }

        if (hasError) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }

        return true;
    });

    /**
     * JSON validator for submenu_content field
     * Validate JSON syntax before form submission
     */
    $('textarea[name="submenu_content"]').on('blur', function() {
        var jsonContent = $(this).val();

        if (jsonContent && jsonContent.trim() !== '') {
            try {
                JSON.parse(jsonContent);
                $(this).removeClass('has-error').addClass('has-success');
                $(this).closest('.form-group').find('.help-block')
                    .removeClass('text-danger').addClass('text-success')
                    .text('Valid JSON format ✓');
            } catch (e) {
                $(this).removeClass('has-success').addClass('has-error');
                $(this).closest('.form-group').find('.help-block')
                    .removeClass('text-success').addClass('text-danger')
                    .text('Invalid JSON format: ' + e.message);
            }
        }
    });

    /**
     * Item type change handler
     * Show relevant fields based on item type selection
     */
    $('select[name="item_type"]').on('change', function() {
        var itemType = $(this).val();

        // Show/hide category selector
        if (itemType === 'category') {
            $('select[name="id_category"]').closest('.form-group').show();
        } else {
            $('select[name="id_category"]').closest('.form-group').hide();
        }

        // Show/hide CMS selector
        if (itemType === 'cms') {
            $('select[name="id_cms"]').closest('.form-group').show();
        } else {
            $('select[name="id_cms"]').closest('.form-group').hide();
        }
    });

    // Trigger initial item type state
    $('select[name="item_type"]').trigger('change');

    /**
     * Menu type visual feedback
     * Add visual indication of selected menu type
     */
    $('input[name="menu_type"]').on('change', function() {
        var val = $(this).val();
        var descriptions = {
            '0': 'Horizontal menu layout - traditional top navigation',
            '1': 'Vertical menu layout - sidebar navigation',
            '2': 'Mobile menu layout - optimized for mobile devices'
        };

        if (descriptions[val]) {
            if (!$('#menu-type-description').length) {
                $(this).closest('.form-group').append(
                    '<div id="menu-type-description" class="help-block"></div>'
                );
            }
            $('#menu-type-description').text(descriptions[val]);
        }
    });

    /**
     * Auto-save indicator (placeholder for future AJAX save functionality)
     */
    var formChanged = false;
    $('form[name="form"] input, form[name="form"] select, form[name="form"] textarea').on('change', function() {
        formChanged = true;
    });

    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    $('form[name="form"]').on('submit', function() {
        formChanged = false;
    });

    /**
     * Help tooltips initialization
     * Add tooltips to description fields
     */
    if (typeof $.fn.tooltip !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }

    console.log('PC Advanced Menu admin interface initialized successfully');
});
