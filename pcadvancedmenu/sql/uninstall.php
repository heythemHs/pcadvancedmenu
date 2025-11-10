<?php
/**
 * SQL Uninstallation Script
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

$sql = array();

// Drop tables
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_tab_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pc_advanced_menu_tab`';
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
