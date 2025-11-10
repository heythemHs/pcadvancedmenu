<?php
/**
 * PrestaShop Advanced Menu Module
 *
 * @author    Your Name
 * @copyright Copyright (c) 2025
 * @license   MIT License
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/classes/PcAdvancedMenuItem.php';

class PcAdvancedMenu extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'pcadvancedmenu';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Your Name';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array(
            'min' => '1.7',
            'max' => _PS_VERSION_
        );
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('PC Advanced Menu');
        $this->description = $this->l('Multilevel hamburger push menu with images, categories, and custom links');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
    }

    /**
     * Install module
     */
    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayNav') &&
            $this->registerHook('displayTop') &&
            $this->installTab();
    }

    /**
     * Uninstall module
     */
    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall() && $this->uninstallTab();
    }

    /**
     * Install admin tab
     */
    protected function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminPcAdvancedMenu';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Advanced Menu';
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('IMPROVE');
        $tab->module = $this->name;
        return $tab->add();
    }

    /**
     * Uninstall admin tab
     */
    protected function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminPcAdvancedMenu');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminPcAdvancedMenu'));
    }

    /**
     * Add CSS and JS to the header
     */
    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/front.css');
        $this->context->controller->addJS($this->_path . 'views/js/front.js');

        // Add Font Awesome if not already loaded
        $this->context->controller->addCSS('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    }

    /**
     * Display menu in navigation
     */
    public function hookDisplayNav($params)
    {
        return $this->displayMenu();
    }

    /**
     * Display menu at top
     */
    public function hookDisplayTop($params)
    {
        return $this->displayMenu();
    }

    /**
     * Generate menu HTML
     */
    protected function displayMenu()
    {
        $items = $this->getMenuItems();

        $this->context->smarty->assign(array(
            'menu_items' => $items,
            'menu_position' => Configuration::get('PC_ADVANCED_MENU_POSITION', 'right'),
        ));

        return $this->display(__FILE__, 'views/templates/hook/pcadvancedmenu.tpl');
    }

    /**
     * Get menu items from database
     */
    protected function getMenuItems()
    {
        $id_lang = (int)$this->context->language->id;
        $id_shop = (int)$this->context->shop->id;

        $sql = new DbQuery();
        $sql->select('m.*, ml.title, ml.link');
        $sql->from('pc_advanced_menu', 'm');
        $sql->leftJoin('pc_advanced_menu_lang', 'ml', 'm.id_menu = ml.id_menu AND ml.id_lang = ' . $id_lang);
        $sql->leftJoin('pc_advanced_menu_shop', 'ms', 'm.id_menu = ms.id_menu AND ms.id_shop = ' . $id_shop);
        $sql->where('m.active = 1');
        $sql->where('(ms.id_shop = ' . $id_shop . ' OR ms.id_shop IS NULL)');
        $sql->orderBy('m.position ASC');

        $results = Db::getInstance()->executeS($sql);

        return $this->buildMenuTree($results);
    }

    /**
     * Build menu tree structure
     */
    protected function buildMenuTree($items, $parent_id = 0)
    {
        $tree = array();

        foreach ($items as $item) {
            if ((int)$item['id_parent'] === $parent_id) {
                $children = $this->buildMenuTree($items, (int)$item['id_menu']);
                if ($children) {
                    $item['children'] = $children;
                }

                // Add image URL if exists
                if (!empty($item['image'])) {
                    $item['image_url'] = $this->context->link->getMediaLink(_PS_IMG_ . 'menu/' . $item['image']);
                }

                // Handle special item types
                if ($item['item_type'] === 'category' && $item['id_category']) {
                    $category = new Category((int)$item['id_category'], (int)$this->context->language->id);
                    $item['link'] = $category->getLink();
                    if (empty($item['title'])) {
                        $item['title'] = $category->name;
                    }
                    // Get category image
                    if (file_exists(_PS_CAT_IMG_DIR_ . $item['id_category'] . '.jpg')) {
                        $item['image_url'] = $this->context->link->getMediaLink(_PS_IMG_ . 'c/' . $item['id_category'] . '.jpg');
                    }
                } elseif ($item['item_type'] === 'cms' && $item['id_cms']) {
                    $cms = new CMS((int)$item['id_cms'], (int)$this->context->language->id);
                    $item['link'] = $this->context->link->getCMSLink($cms);
                    if (empty($item['title'])) {
                        $item['title'] = $cms->meta_title;
                    }
                }

                $tree[] = $item;
            }
        }

        return $tree;
    }
}
