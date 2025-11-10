<?php
/**
 * SQL Installation Script
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

$sql = array();

// Main menu table with all advanced fields
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu` (
    `id_menu` int(11) NOT NULL AUTO_INCREMENT,
    `id_parent` int(11) DEFAULT 0,
    `id_category` int(11) DEFAULT NULL,
    `id_cms` int(11) DEFAULT NULL,
    `item_type` varchar(50) NOT NULL DEFAULT "custom",
    `menu_type` int(11) NOT NULL DEFAULT 0,
    `url_type` int(11) NOT NULL DEFAULT 0,
    `icon_type` int(11) NOT NULL DEFAULT 1,
    `icon_class` varchar(100) DEFAULT NULL,
    `icon` varchar(255) DEFAULT NULL,
    `image` varchar(255) DEFAULT NULL,
    `legend_icon` varchar(255) DEFAULT NULL,
    `position` int(11) NOT NULL DEFAULT 0,
    `active` tinyint(1) NOT NULL DEFAULT 1,
    `active_label` tinyint(1) NOT NULL DEFAULT 0,
    `new_window` tinyint(1) NOT NULL DEFAULT 0,
    `float` tinyint(1) NOT NULL DEFAULT 0,

    `submenu_type` int(11) NOT NULL DEFAULT 0,
    `submenu_width` int(11) NOT NULL DEFAULT 600,
    `submenu_content` longtext DEFAULT NULL,
    `submenu_image` varchar(255) DEFAULT NULL,
    `submenu_repeat` int(11) DEFAULT 0,
    `submenu_bg_position` int(11) DEFAULT 0,

    `bg_color` varchar(50) DEFAULT NULL,
    `txt_color` varchar(50) DEFAULT NULL,
    `h_bg_color` varchar(50) DEFAULT NULL,
    `h_txt_color` varchar(50) DEFAULT NULL,
    `labelbg_color` varchar(50) DEFAULT NULL,
    `labeltxt_color` varchar(50) DEFAULT NULL,
    `submenu_bg_color` varchar(50) DEFAULT NULL,
    `submenu_link_color` varchar(50) DEFAULT NULL,
    `submenu_hover_color` varchar(50) DEFAULT NULL,
    `submenu_title_color` varchar(50) DEFAULT NULL,
    `submenu_title_colorh` varchar(50) DEFAULT NULL,
    `submenu_titleb_color` varchar(50) DEFAULT NULL,

    `submenu_border_t` varchar(50) DEFAULT NULL,
    `submenu_border_r` varchar(50) DEFAULT NULL,
    `submenu_border_b` varchar(50) DEFAULT NULL,
    `submenu_border_l` varchar(50) DEFAULT NULL,
    `submenu_border_i` varchar(50) DEFAULT NULL,

    `group_access` text DEFAULT NULL,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_menu`),
    KEY `id_parent` (`id_parent`),
    KEY `menu_type` (`menu_type`),
    KEY `active` (`active`),
    KEY `position` (`position`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// Multi-lang table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_lang` (
    `id_menu` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `title` varchar(255) DEFAULT NULL,
    `link` varchar(255) DEFAULT NULL,
    `label` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id_menu`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// Multi-shop table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_shop` (
    `id_menu` int(11) NOT NULL,
    `id_shop` int(11) NOT NULL,
    PRIMARY KEY (`id_menu`, `id_shop`),
    KEY `id_shop` (`id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// HTML blocks table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_html` (
    `id_html` int(11) NOT NULL AUTO_INCREMENT,
    `active` tinyint(1) NOT NULL DEFAULT 1,
    `position` int(11) NOT NULL DEFAULT 0,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_html`),
    KEY `active` (`active`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// HTML blocks lang table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_html_lang` (
    `id_html` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` longtext NOT NULL,
    PRIMARY KEY (`id_html`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// HTML blocks shop table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_html_shop` (
    `id_html` int(11) NOT NULL,
    `id_shop` int(11) NOT NULL,
    PRIMARY KEY (`id_html`, `id_shop`),
    KEY `id_shop` (`id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// Links table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_links` (
    `id_link` int(11) NOT NULL AUTO_INCREMENT,
    `active` tinyint(1) NOT NULL DEFAULT 1,
    `position` int(11) NOT NULL DEFAULT 0,
    `new_window` tinyint(1) NOT NULL DEFAULT 0,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_link`),
    KEY `active` (`active`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// Links lang table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_links_lang` (
    `id_link` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `url` varchar(255) NOT NULL,
    PRIMARY KEY (`id_link`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// Links shop table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_links_shop` (
    `id_link` int(11) NOT NULL,
    `id_shop` int(11) NOT NULL,
    PRIMARY KEY (`id_link`, `id_shop`),
    KEY `id_shop` (`id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// Execute all SQL queries
foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

// Insert default configuration
Configuration::updateValue('PC_ADVANCED_MENU_POSITION', 'right');
Configuration::updateValue('PC_ADVANCED_MENU_ENABLED', 1);

// Create images directory
if (!file_exists(_PS_IMG_DIR_ . 'menu/')) {
    mkdir(_PS_IMG_DIR_ . 'menu/', 0755, true);
}

return true;
