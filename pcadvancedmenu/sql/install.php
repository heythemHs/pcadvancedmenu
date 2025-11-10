<?php
/**
 * SQL Installation Script
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

$sql = array();

// Main menu table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu` (
    `id_menu` int(11) NOT NULL AUTO_INCREMENT,
    `id_parent` int(11) DEFAULT 0,
    `id_category` int(11) DEFAULT NULL,
    `id_cms` int(11) DEFAULT NULL,
    `item_type` varchar(50) NOT NULL DEFAULT "custom",
    `icon` varchar(100) DEFAULT NULL,
    `image` varchar(255) DEFAULT NULL,
    `position` int(11) NOT NULL DEFAULT 0,
    `active` tinyint(1) NOT NULL DEFAULT 1,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_menu`),
    KEY `id_parent` (`id_parent`),
    KEY `active` (`active`),
    KEY `position` (`position`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// Multi-lang table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_lang` (
    `id_menu` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `title` varchar(255) DEFAULT NULL,
    `link` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id_menu`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// Multi-shop table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_shop` (
    `id_menu` int(11) NOT NULL,
    `id_shop` int(11) NOT NULL,
    PRIMARY KEY (`id_menu`, `id_shop`),
    KEY `id_shop` (`id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// Tab configuration table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_tab` (
    `id_tab` int(11) NOT NULL AUTO_INCREMENT,
    `tab_key` varchar(50) NOT NULL,
    `position` int(11) NOT NULL DEFAULT 0,
    `active` tinyint(1) NOT NULL DEFAULT 1,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_tab`),
    UNIQUE KEY `tab_key` (`tab_key`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

// Tab multi-lang table
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_tab_lang` (
    `id_tab` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id_tab`, `id_lang`)
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
