# Phase 2 - Starting Implementation Guide

## ✅ Current Status

**Phase 1: 100% Complete & Committed**
- All database tables created
- All models implemented
- Basic AdminController working
- Frontend menu functional
- All documentation complete

**Ready to Build:** Sprint 1 of Phase 2

## 🎯 Sprint 1 Implementation Steps

### Step 1: Enhance renderForm() Method

**File:** `pcadvancedmenu/controllers/admin/AdminPcAdvancedMenuController.php`

**Location:** Replace the current `renderForm()` method (line ~216) with enhanced version

**What to Add:**

#### Basic Fields Section
```php
array(
    'type' => 'text',
    'label' => $this->l('Title'),
    'name' => 'title',
    'lang' => true,
    'required' => true,
),
array(
    'type' => 'text',
    'label' => $this->l('Label'),
    'name' => 'label',
    'lang' => true,
    'desc' => $this->l('Optional label like "New" or "Sale"'),
),
array(
    'type' => 'switch',
    'label' => $this->l('Show Label'),
    'name' => 'active_label',
    'is_bool' => true,
    'values' => array(
        array('id' => 'active_on', 'value' => 1),
        array('id' => 'active_off', 'value' => 0)
    ),
),
```

#### URL Configuration Section
```php
array(
    'type' => 'radio',
    'label' => $this->l('URL Type'),
    'name' => 'url_type',
    'values' => array(
        array('id' => 'url_system', 'value' => 0, 'label' => $this->l('System URL')),
        array('id' => 'url_custom', 'value' => 1, 'label' => $this->l('Custom URL')),
        array('id' => 'url_none', 'value' => 2, 'label' => $this->l('No Link'))
    ),
),
// Add conditional wrappers for system/custom URL sections
```

#### Icon Configuration Section
```php
array(
    'type' => 'radio',
    'label' => $this->l('Icon Type'),
    'name' => 'icon_type',
    'values' => array(
        array('id' => 'icon_image', 'value' => 0, 'label' => $this->l('Upload Image')),
        array('id' => 'icon_class', 'value' => 1, 'label' => $this->l('Icon Class'))
    ),
),
```

#### Color Fields Section
```php
array(
    'type' => 'color',
    'label' => $this->l('Background Color'),
    'name' => 'bg_color',
    'class' => 'color-picker',
),
// Repeat for all 12 color fields
```

### Step 2: Add Helper Methods

**Add these methods to AdminPcAdvancedMenuController:**

```php
/**
 * Get form field values
 */
protected function getFormValues()
{
    $id_menu = (int)Tools::getValue('id_menu');
    $menu_item = new PcAdvancedMenuItem($id_menu);

    $fields = array();

    // Basic fields
    $fields['active'] = $menu_item->active;
    $fields['active_label'] = $menu_item->active_label;
    $fields['position'] = $menu_item->position;

    // URL fields
    $fields['url_type'] = $menu_item->url_type;
    $fields['item_type'] = $menu_item->item_type;

    // Icon fields
    $fields['icon_type'] = $menu_item->icon_type;
    $fields['icon_class'] = $menu_item->icon_class;

    // Color fields
    $fields['bg_color'] = $menu_item->bg_color;
    // ... add all other fields

    return $fields;
}

/**
 * Get categories for dropdown
 */
protected function getCategoryList()
{
    $id_lang = $this->context->language->id;
    $categories = Category::getCategories($id_lang, true, false);

    $list = array(array('id_category' => 0, 'name' => $this->l('-- Select Category --')));

    foreach ($categories as $category) {
        $list[] = array(
            'id_category' => $category['id_category'],
            'name' => $category['name']
        );
    }

    return $list;
}

/**
 * Get CMS pages for dropdown
 */
protected function getCMSList()
{
    $id_lang = $this->context->language->id;
    $cms_pages = CMS::getCMSPages($id_lang);

    $list = array(array('id_cms' => 0, 'meta_title' => $this->l('-- Select CMS Page --')));

    return array_merge($list, $cms_pages);
}

/**
 * Get customer groups
 */
protected function getCustomerGroups()
{
    return Group::getGroups($this->context->language->id);
}
```

### Step 3: Create Admin CSS

**File:** `pcadvancedmenu/views/css/back.css`

```css
/* Admin interface styles */

/* Conditional field wrappers */
.conditional-wrapper {
    display: none;
}

.conditional-wrapper.active {
    display: block;
}

/* Color picker styling */
.color-picker-wrapper {
    position: relative;
}

.sp-replacer {
    padding: 5px;
    border: 1px solid #ccc;
    background: #fff;
    cursor: pointer;
}

/* Field organization */
.form-group.color-field {
    margin-bottom: 15px;
}

.form-group.border-field input {
    max-width: 150px;
}

/* Helper text */
.form-group .help-block {
    color: #999;
    font-size: 12px;
    margin-top: 5px;
}
```

### Step 4: Create Admin JavaScript

**File:** `pcadvancedmenu/views/js/back.js`

```javascript
$(document).ready(function() {

    // URL Type conditional display
    $('#url_type_0, #url_type_1, #url_type_2').change(function() {
        var val = $('input[name=url_type]:checked').val();

        if (val == '0') {
            $('#system-url-wrapper').show();
            $('#custom-url-wrapper').hide();
        } else if (val == '1') {
            $('#system-url-wrapper').hide();
            $('#custom-url-wrapper').show();
        } else {
            $('#system-url-wrapper').hide();
            $('#custom-url-wrapper').hide();
        }
    });

    // Icon Type conditional display
    $('#icon_type_0, #icon_type_1').change(function() {
        var val = $('input[name=icon_type]:checked').val();

        if (val == '0') {
            $('#icon-image-wrapper').show();
            $('#icon-class-wrapper').hide();
        } else {
            $('#icon-image-wrapper').hide();
            $('#icon-class-wrapper').show();
        }
    });

    // Submenu Type conditional display
    $('#submenu_type').change(function() {
        var val = $(this).val();

        if (val > 0) {
            $('#submenu-options-wrapper').show();
        } else {
            $('#submenu-options-wrapper').hide();
        }

        if (val == 2) {
            $('#grid-builder-wrapper').show();
        } else {
            $('#grid-builder-wrapper').hide();
        }
    });

    // Initialize color pickers (if Spectrum.js is loaded)
    if (typeof $.fn.spectrum !== 'undefined') {
        $('.color-picker').spectrum({
            preferredFormat: "hex",
            showInput: true,
            showAlpha: false,
            showPalette: true,
            palette: [
                ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
                ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
                ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
                ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"]
            ]
        });
    }

    // Trigger initial state
    $('input[name=url_type]:checked').trigger('change');
    $('input[name=icon_type]:checked').trigger('change');
    $('#submenu_type').trigger('change');
});
```

### Step 5: Update Main Module to Load Assets

**File:** `pcadvancedmenu/pcadvancedmenu.php`

Add to existing hooks or create new hook:

```php
public function hookDisplayBackOfficeHeader()
{
    if (Tools::getValue('controller') == 'AdminPcAdvancedMenu') {
        $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        $this->context->controller->addJS($this->_path . 'views/js/back.js');

        // Add Spectrum color picker library
        $this->context->controller->addCSS('https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css');
        $this->context->controller->addJS('https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.js');
    }
}
```

And register the hook in install():
```php
$this->registerHook('displayBackOfficeHeader')
```

## 🧪 Testing Sprint 1

After implementing:

1. **Test Form Display**
   - Go to Admin > Design > Advanced Menu
   - Click "Add new menu item"
   - Verify all fields display

2. **Test Conditional Fields**
   - Change URL Type radio buttons
   - Verify correct sections show/hide
   - Change Icon Type
   - Verify image/class sections toggle

3. **Test Color Pickers**
   - Click on color fields
   - Verify Spectrum picker opens
   - Select colors
   - Verify values save

4. **Test Form Submission**
   - Fill in all fields
   - Submit form
   - Verify data saves to database
   - Edit item
   - Verify all fields load correctly

5. **Test Multi-language**
   - Switch language
   - Verify title, label, link fields switch
   - Enter different values per language
   - Verify all languages save

## 📝 Sprint 1 Completion Checklist

- [ ] renderForm() method enhanced with all fields
- [ ] Helper methods added (getCategoryList, getCMSList, etc.)
- [ ] back.css created with styles
- [ ] back.js created with conditional logic
- [ ] Assets registered in module hook
- [ ] Form displays correctly
- [ ] Conditional fields work
- [ ] Color pickers functional
- [ ] Form saves all data
- [ ] Multi-language works
- [ ] No JavaScript errors
- [ ] Ready to commit Sprint 1

## 🎯 After Sprint 1

Once Sprint 1 is complete and tested:

1. **Commit Sprint 1**
   ```bash
   git add -A
   git commit -m "Sprint 1 Complete: Enhanced AdminController with all 50+ fields"
   git push
   ```

2. **Start Sprint 2: Grid Builder**
   - Reference PHASE2_PLAN.md for Sprint 2 details
   - Build submenu_content.tpl template
   - Add drag-and-drop functionality
   - Create column content modal

3. **Continue Through Remaining Sprints**
   - Sprint 3: HTML blocks & links management
   - Sprint 4: Frontend grid rendering
   - Sprint 5: Polish & testing

## 💡 Tips for Implementation

1. **Work Incrementally**
   - Build one section at a time
   - Test frequently
   - Commit working code

2. **Use iqitmegamenu as Reference**
   - Check form structure
   - See how they handle conditional fields
   - Adapt to your needs

3. **Debug Effectively**
   - Check browser console for JS errors
   - Check PrestaShop error logs
   - Use var_dump() for PHP debugging

4. **Keep Documentation Updated**
   - Note any changes from plan
   - Document workarounds
   - Update checklists

## 🚀 You're Ready to Build!

Everything is documented and planned. Sprint 1 should take 3-4 hours to complete.

Good luck! 🎉
