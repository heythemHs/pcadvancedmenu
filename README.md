# PrestaShop Advanced Multilevel Menu Module

A comprehensive PrestaShop module that provides a beautiful multilevel hamburger push menu with extensive customization options.

![PrestaShop Version](https://img.shields.io/badge/PrestaShop-1.7+-blue.svg)
![PHP Version](https://img.shields.io/badge/PHP-7.1+-purple.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

## Overview

This module creates a sleek, mobile-friendly hamburger menu that slides in from either side of the screen. Perfect for creating organized navigation with multiple levels, categories, images, and custom links.

## Key Features

- **Multilevel Navigation**: Unlimited nested menu levels
- **Multiple Content Types**:
  - Custom links with icons
  - PrestaShop product categories
  - CMS pages
  - Organizational tabs
- **Visual Enhancements**:
  - Font Awesome icon integration
  - Custom image support for each menu item
  - Smooth CSS animations
- **Full Localization**: Multi-language and multi-store support
- **Easy Configuration**: User-friendly admin interface with drag & drop
- **Responsive Design**: Works perfectly on all devices
- **Customizable Position**: Menu slides from left or right

## Quick Start

1. Copy the `pcadvancedmenu` folder to your PrestaShop `modules/` directory
2. Install the module from **Modules > Module Manager**
3. Configure from **Design > Advanced Menu**
4. Add your menu items and enjoy!

## Use Cases

### E-commerce Store
Create a "Products" tab with all your categories and subcategories, complete with category images.

### Content-Rich Site
Add an "Inspiration" tab with CMS pages showcasing your blog posts, guides, and galleries with custom images.

### Multi-section Navigation
Organize your menu into logical sections (Shop, About Us, Help Center, etc.) with beautiful icons.

## Documentation

Full documentation is available in the [module's README](./pcadvancedmenu/README.md).

## Screenshots

The menu includes:
- Sliding animation from left or right
- Multi-level navigation with "Go Back" buttons
- Icon and image support
- Mobile-optimized hamburger trigger
- Semi-transparent overlay when menu is open

## Technical Details

- **Compatibility**: PrestaShop 1.7+
- **PHP**: 7.1 or higher
- **Database**: MySQL 5.6+
- **Dependencies**: Font Awesome (auto-loaded)

## Module Structure

```
pcadvancedmenu/
├── Main module files (PHP, config)
├── Admin controller for configuration
├── Database schema and models
├── Frontend templates (Smarty)
├── CSS and JavaScript assets
└── Multi-language support
```

## Contributing

Contributions are welcome! Feel free to:
- Report bugs
- Suggest new features
- Submit pull requests
- Improve documentation

## License

MIT License - See LICENSE file for details

## Credits

- Inspired by modern mobile menu designs
- Based on CodePen multilevel hamburger menu concept
- Uses Font Awesome for icons

## Author

Created for PrestaShop community

## Support

For questions, issues, or feature requests, please open an issue on GitHub.

---

**Note**: Don't forget to add a `logo.png` file (64x64 pixels) to the module directory before installation!