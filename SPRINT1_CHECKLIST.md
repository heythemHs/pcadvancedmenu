# Sprint 1: Enhanced AdminController - Implementation Checklist

## Objective
Transform the basic AdminController into a sophisticated configuration interface with all 50+ fields organized logically with tabs and conditional display.

## Fields to Add to renderForm()

### Tab 1: Basic Information
- ✅ title (multi-lang) - text
- ✅ label (multi-lang) - text
- ✅ active_label - switch
- ✅ menu_type - radio (horizontal/vertical/mobile)
- ✅ position - text
- ✅ active - switch
- ✅ float - switch
- ✅ new_window - switch

### Tab 2: URL Configuration
- ✅ url_type - radio (system/custom/no-link)
- ✅ Custom URL section (shown if url_type=1):
  - link (multi-lang) - text
- ✅ System URL section (shown if url_type=0):
  - item_type - select (custom/category/cms/tab)
  - id_category - select
  - id_cms - select
- ✅ id_parent - select (for multilevel)

### Tab 3: Icon & Image
- ✅ icon_type - radio (image/class)
- ✅ Icon Class section (shown if icon_type=1):
  - icon_class - text with icon picker
- ✅ Icon Image section (shown if icon_type=0):
  - icon - file upload
- ✅ legend_icon - text
- ✅ image - file upload

### Tab 4: Submenu Configuration
- ✅ submenu_type - radio (none/simple/grid)
- ✅ Submenu options (shown if submenu_type>0):
  - submenu_width - text
  - submenu_image - file upload
  - submenu_repeat - select
  - submenu_bg_position - select
- ✅ Grid Builder (shown if submenu_type=2):
  - submenu_content - textarea with grid builder button
  - "Open Grid Builder" button (launches modal)

### Tab 5: Colors
All color fields with color pickers:
- ✅ Main Item Colors:
  - bg_color
  - txt_color
  - h_bg_color (hover)
  - h_txt_color (hover)
- ✅ Label Colors:
  - labelbg_color
  - labeltxt_color
- ✅ Submenu Colors:
  - submenu_bg_color
  - submenu_link_color
  - submenu_hover_color
  - submenu_title_color
  - submenu_title_colorh
  - submenu_titleb_color

### Tab 6: Borders
- ✅ submenu_border_t - text
- ✅ submenu_border_r - text
- ✅ submenu_border_b - text
- ✅ submenu_border_l - text
- ✅ submenu_border_i - text

### Tab 7: Access Control
- ✅ group_access - checkboxes for each customer group

## Helper Methods to Add

### getFormValues()
Return all field values including:
- Defaults for new items
- Loaded values for existing items
- Multi-lang field handling
- Group access unserialization

### getCategoryList()
Build category dropdown with indentation

### getCMSList()
Build CMS pages dropdown

### getParentMenuItems()
Build parent items dropdown with tree structure

### getCustomerGroups()
Get all customer groups for checkboxes

### processImageUpload() - ENHANCE
Handle multiple image uploads:
- icon (if icon_type=0)
- image (menu item image)
- submenu_image (submenu background)

### processSubmenuContent()
Validate and save JSON grid content

### processGroupAccess()
Serialize group access array

## JavaScript Functionality Needed

### Conditional Field Display
```javascript
// Show/hide based on url_type
$('#url_type').change(function() {
  if ($(this).val() == '0') {
    $('#system-url-wrapper').show();
    $('#custom-url-wrapper').hide();
  } else if ($(this).val() == '1') {
    $('#system-url-wrapper').hide();
    $('#custom-url-wrapper').show();
  } else {
    $('#system-url-wrapper').hide();
    $('#custom-url-wrapper').hide();
  }
});

// Show/hide based on icon_type
$('#icon_type').change(function() {
  if ($(this).val() == '0') {
    $('#icon-image-wrapper').show();
    $('#icon-class-wrapper').hide();
  } else {
    $('#icon-image-wrapper').hide();
    $('#icon-class-wrapper').show();
  }
});

// Show/hide based on submenu_type
$('#submenu_type').change(function() {
  if ($(this).val() > 0) {
    $('#submenu-options').show();
  } else {
    $('#submenu-options').hide();
  }

  if ($(this).val() == 2) {
    $('#grid-builder-wrapper').show();
  } else {
    $('#grid-builder-wrapper').hide();
  }
});
```

### Color Pickers
Initialize color pickers on all color fields:
```javascript
$('.color-picker').spectrum({
  preferredFormat: "hex",
  showInput: true,
  showAlpha: false,
  showPalette: true
});
```

### Tab Navigation
Bootstrap tabs for organizing fields into sections

## CSS Needed

### Tab Styles
```css
.nav-tabs {
  margin-bottom: 20px;
}
```

### Conditional Wrappers
```css
.conditional-wrapper {
  display: none;
}
.conditional-wrapper.active {
  display: block;
}
```

### Color Picker Integration
```css
.sp-replacer {
  border: 1px solid #ccc;
  background: #fff;
  padding: 5px;
}
```

## Files to Create/Modify

### Phase 1 (This Sprint)
1. ✅ controllers/admin/AdminPcAdvancedMenuController.php
   - Add complete renderForm()
   - Add helper methods
   - Add AJAX handlers

2. ⏳ views/css/back.css (basic version)
   - Tab styles
   - Conditional field styles
   - Color picker integration

3. ⏳ views/js/back.js (basic version)
   - Conditional field logic
   - Color picker initialization
   - Form validation

## Testing Checklist

After implementation:
- [ ] All fields display correctly
- [ ] Tabs switch properly
- [ ] Conditional fields show/hide
- [ ] Color pickers work
- [ ] File uploads function
- [ ] Form saves all fields
- [ ] Multi-lang switches work
- [ ] Validation works
- [ ] No JavaScript errors

## Estimated Completion
Sprint 1 should take 3-4 hours to complete all of the above.

Current Status: Building renderForm() method now...
