# PC Advanced Menu - PrestaShop Module

A powerful multilevel hamburger push menu module for PrestaShop with support for images, categories, CMS pages, and custom links.

## Features

- **Multilevel Menu Structure**: Create unlimited levels of nested menu items
- **Multiple Item Types**:
  - Custom Links with icons and images
  - Product Categories (auto-linked with category images)
  - CMS Pages (auto-linked)
  - Tab Parents (organize menu into sections)
- **Rich Customization**:
  - Font Awesome icons support
  - Custom images for menu items
  - Configurable menu position (left or right)
  - Drag & drop positioning
- **Multi-language Support**: All menu items support multiple languages
- **Multi-store Compatible**: Configure different menus for different shops
- **Responsive Design**: Mobile-friendly hamburger menu
- **Smooth Animations**: CSS-powered slide animations

## Installation

### Method 1: Manual Installation

1. Download or clone this repository
2. Copy the `pcadvancedmenu` folder to your PrestaShop `modules/` directory
3. Go to **Modules > Module Manager** in your PrestaShop back office
4. Search for "PC Advanced Menu"
5. Click **Install**
6. Configure the module

### Method 2: Upload via Back Office

1. Zip the `pcadvancedmenu` folder
2. Go to **Modules > Module Manager** in your PrestaShop back office
3. Click **Upload a module**
4. Select the zip file and upload
5. Click **Install**

## Configuration

### General Settings

1. Go to **Design > Advanced Menu** (or click **Configure** in the module)
2. Click on **Settings** button
3. Configure:
   - **Enable Menu**: Turn the menu on/off
   - **Menu Position**: Choose left or right side

### Adding Menu Items

1. Go to **Design > Advanced Menu**
2. Click **Add new menu item**
3. Fill in the form:

#### Fields Description

- **Title**: The text displayed in the menu (multi-language)
  - Leave empty for categories/CMS to use their default names
- **Item Type**: Choose the type of menu item
  - **Custom Link**: A custom URL with text
  - **Category**: Links to a product category
  - **CMS Page**: Links to a CMS page
  - **Tab**: A parent item that contains sub-items
- **Custom Link**: The URL (only for custom link type)
- **Category**: Select a category (only for category type)
- **CMS Page**: Select a CMS page (only for CMS type)
- **Parent Item**: Select a parent to create sub-levels
- **Icon Class**: Font Awesome icon class (e.g., `fa fa-home`)
- **Image**: Upload a custom image for the menu item
- **Position**: Order of the item in the menu
- **Active**: Enable/disable the menu item

### Creating a Multilevel Menu

Example structure for a Products tab with categories:

1. Create a Tab item:
   - Title: "Products"
   - Type: Tab
   - Icon: `fa fa-shopping-cart`
   - Parent: Root

2. Create category items under the tab:
   - Title: (leave empty to use category name)
   - Type: Category
   - Category: Select "Electronics"
   - Parent: Products tab
   - Position: 1

3. Create sub-categories:
   - Type: Category
   - Category: Select "Smartphones"
   - Parent: Electronics category
   - Position: 1

### Creating an Inspiration Tab with Images

1. Create a Tab item:
   - Title: "Inspiration"
   - Type: Tab
   - Icon: `fa fa-lightbulb-o`

2. Add CMS pages with images:
   - Title: "Summer Collection"
   - Type: CMS Page
   - CMS Page: Select your CMS page
   - Parent: Inspiration tab
   - Image: Upload an inspirational image

## Usage

### Displaying the Menu

The menu automatically appears on your site when enabled. The hamburger icon (☰) will be displayed in the top corner (left or right based on configuration).

### Hooks

The module uses these hooks:
- `displayNav`: Main navigation hook
- `displayTop`: Top of page
- `header`: Loads CSS and JavaScript

### Customization

#### Changing Colors

Edit `/views/css/front.css` and modify the CSS variables:

```css
:root {
  --menu-white: #fff;
  --menu-navy: #2e2f35;
  --menu-navy-dark: #1e1e24;
  --menu-navy-light: #494a50;
  --menu-grey: #a5a5a4;
}
```

#### Changing Animation Speed

In `/views/css/front.css`, modify the transition property:

```css
.pushNav {
  transition: ease-in-out 0.5s; /* Change 0.5s to your preferred speed */
}
```

## Database Tables

The module creates these tables:

- `ps_pc_advanced_menu`: Main menu items
- `ps_pc_advanced_menu_lang`: Multi-language translations
- `ps_pc_advanced_menu_shop`: Multi-store associations
- `ps_pc_advanced_menu_tab`: Tab configurations
- `ps_pc_advanced_menu_tab_lang`: Tab translations

## File Structure

```
pcadvancedmenu/
├── pcadvancedmenu.php              # Main module file
├── config.xml                       # Module configuration
├── logo.png                         # Module icon (64x64)
├── classes/
│   └── PcAdvancedMenuItem.php      # Menu item model
├── controllers/
│   └── admin/
│       └── AdminPcAdvancedMenuController.php  # Admin interface
├── sql/
│   ├── install.php                 # Installation SQL
│   └── uninstall.php               # Uninstallation SQL
├── views/
│   ├── css/
│   │   └── front.css               # Frontend styles
│   ├── js/
│   │   └── front.js                # Frontend JavaScript
│   └── templates/
│       └── hook/
│           ├── pcadvancedmenu.tpl  # Main menu template
│           └── _menu_item.tpl      # Menu item partial
└── translations/                    # Translation files
```

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Requirements

- PrestaShop 1.7.x or higher
- PHP 7.1 or higher
- MySQL 5.6 or higher
- Font Awesome (auto-loaded by module)

## Troubleshooting

### Menu Not Showing

1. Check if module is enabled in configuration
2. Verify the menu has active items
3. Clear PrestaShop cache (Advanced Parameters > Performance)
4. Check browser console for JavaScript errors

### Images Not Displaying

1. Verify the `img/menu/` directory exists and is writable (chmod 755)
2. Check that images were uploaded successfully
3. Clear browser cache

### Styling Issues

1. Check if there are CSS conflicts with your theme
2. Try adding `!important` to CSS rules if needed
3. Ensure Font Awesome is loading

## Credits

- Menu design inspired by [CodePen](https://codepen.io/) multilevel hamburger menu
- Font Awesome for icons

## License

MIT License - Feel free to use and modify

## Support

For issues, questions, or contributions, please contact the module author.

## Changelog

### Version 1.0.0
- Initial release
- Multilevel menu support
- Multi-language and multi-store compatible
- Support for categories, CMS pages, and custom links
- Image support for menu items
- Font Awesome icons integration
- Responsive design
- Left/right positioning option
