<?php
/**
 * SQL Uninstallation Script
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

$sql = array();

// Drop tables
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_links_shop`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_links_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_links`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_html_shop`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_html_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_html`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_shop`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu`';

// Execute all SQL queries
foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

// Delete configuration
Configuration::deleteByName('PC_ADVANCED_MENU_POSITION');
Configuration::deleteByName('PC_ADVANCED_MENU_ENABLED');

return true;
