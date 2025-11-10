# PC Advanced Menu - Upgrade Notes

## Phase 1: Advanced Core Implementation

This phase implements a sophisticated menu system inspired by iqitmegamenu with extensive customization options.

### New Features Added

#### 1. Advanced Menu Item Model (PcAdvancedMenuItem)

**URL Configuration:**
- `url_type`: System URL (0), Custom URL (1), or No Link (2)
- Automatic category and CMS page linking
- Custom URL support with multi-language

**Icon System:**
- `icon_type`: Image icon (0) or CSS class icon (1)
- Support for Font Awesome or custom icon classes
- Image upload for custom icons
- Legend icon for tooltips

**Menu Types:**
- Horizontal menu (0) - main navigation
- Vertical menu (1) - sidebar navigation
- Mobile menu (2) - responsive mobile version

**Label System:**
- Custom labels (e.g., "New", "Sale", "Hot")
- Label colors (background and text)
- Option to enable/disable labels

**Submenu Configuration:**
- Type: None (0), Simple dropdown (1), Grid layout (2)
- Configurable width (pixels)
- Grid content builder with drag-and-drop (JSON structure)
- Background image with repeat and positioning options

**Visual Customization:**
- Main item colors: background, text, hover states
- Submenu colors: background, links, hover, titles
- Border customization: top, right, bottom, left, inner
- Full color picker support

**Advanced Options:**
- Float positioning
- Open in new window
- Customer group access control
- Multi-shop associations

#### 2. HTML Blocks System (PcAdvancedMenuHtml)

Create reusable HTML content blocks for menu columns:
- WYSIWYG editor support
- Multi-language content
- Active/inactive status
- Position ordering
- Multi-shop support

Use cases:
- Promotional banners in menu
- Rich formatted content
- Embedded videos/images
- Custom HTML/CSS

#### 3. Links Management (PcAdvancedMenuLinks)

Manage collections of links for menu columns:
- Quick link lists
- Multi-language URLs and titles
- New window option
- Position ordering
- Active/inactive status

Use cases:
- Footer-style link lists in mega menu
- Quick access links
- Social media links
- Customer service links

### Database Schema

#### Main Table: `pc_advanced_menu`
**50+ fields including:**
- Basic: id, parent, position, active, type
- URL config: url_type, id_category, id_cms
- Icons: icon_type, icon_class, icon, legend_icon
- Submenu: submenu_type, submenu_width, submenu_content, submenu_image
- Colors: 12+ color fields for complete customization
- Borders: 5 border configuration fields
- Access: group_access, new_window, float
- Dates: date_add, date_upd

#### Supporting Tables:
- `pc_advanced_menu_lang` - Multi-language (title, link, label)
- `pc_advanced_menu_shop` - Multi-shop associations
- `pc_advanced_menu_html` + lang/shop - HTML blocks
- `pc_advanced_menu_links` + lang/shop - Link collections

### Content Types for Grid Columns

The submenu grid system will support:
1. **Empty** - Blank column
2. **HTML Content** - Custom HTML blocks
3. **Categories Tree** - Category navigation
4. **Various Links** - Link collections
5. **Products** - Product listings
6. **Manufacturers** - Brand logos
7. **Banner Image** - Image with link
8. **Suppliers** - Supplier logos

### Configuration Levels

**Tab/Menu Item Level:**
- Full visual customization
- URL and icon configuration
- Submenu type selection
- Group access control

**Submenu Grid Level:**
- Row and column layout (12-column grid)
- Drag and drop positioning
- Per-column content type
- Column width configuration

**Column Content Level:**
- Content type selection
- Type-specific options
- Multi-language support
- Title and styling options

### Next Steps (Phase 2)

1. **Advanced AdminController**
   - Tabbed interface for menu types
   - Drag-and-drop menu item ordering
   - Grid builder with visual editor
   - Color pickers for all color fields
   - Image upload manager
   - Real-time preview

2. **Admin Templates**
   - Form with conditional field display
   - Grid builder interface
   - Column content modals
   - Category tree selector
   - Product selector
   - Link manager interface

3. **Admin JavaScript**
   - Grid drag-and-drop
   - Column width resizing
   - Content type switching
   - AJAX save/load
   - Real-time JSON building

4. **Frontend Templates**
   - Grid-based submenu rendering
   - Content type renderers
   - Responsive layouts
   - Touch-friendly interactions

5. **Advanced CSS**
   - Dynamic color injection
   - Grid system styles
   - Content type styling
   - Animation improvements

### Benefits of This Architecture

1. **Extreme Flexibility**: Support any menu structure imaginable
2. **Professional**: Matches commercial mega menu modules
3. **User-Friendly**: Visual builders, not code editing
4. **Performance**: Efficient database structure
5. **Extensible**: Easy to add new content types
6. **Maintainable**: Clean OOP architecture
7. **Compatible**: PrestaShop 1.7+ standards

### Migration Path

For existing simple menus:
- Keep using `url_type=1` (custom) with basic fields
- Gradually adopt advanced features
- No breaking changes to existing data
- All new fields have sensible defaults

### Technical Notes

**JSON Structure for submenu_content:**
```json
{
  "rows": [
    {
      "columns": [
        {
          "width": 3,
          "contentType": 2,
          "title": {"1": "Categories", "2": "Catégories"},
          "content": {"ids": [3, 4, 5], "treep": 1}
        },
        {
          "width": 6,
          "contentType": 6,
          "content": {"image": "banner.jpg", "link": "http://..."}
        },
        {
          "width": 3,
          "contentType": 4,
          "content": {"ids": [1, 2, 3], "display": "list"}
        }
      ]
    }
  ]
}
```

**Color Format:**
- RGB: `rgb(255, 0, 0)`
- Hex: `#ff0000`
- Transparent: `transparent`

**Group Access Format:**
- Serialized PHP array of group IDs with boolean values
- Example: `serialize([1 => true, 2 => false, 3 => true])`

---

## Installation & Testing

1. Install module in test environment
2. Verify all 9 tables created
3. Check admin menu appears under "Design > Advanced Menu"
4. Test creating basic menu items
5. Test multi-language fields
6. Test multi-shop associations

## Compatibility

- PrestaShop 1.7.0+
- PHP 7.1+
- MySQL 5.6+
- All modern browsers

This architecture provides a solid foundation for a world-class mega menu system.
