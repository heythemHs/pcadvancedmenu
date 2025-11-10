<?php
/**
 * Admin Controller for PC Advanced Menu
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'pcadvancedmenu/classes/PcAdvancedMenuItem.php';

class AdminPcAdvancedMenuController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'pc_advanced_menu';
        $this->className = 'PcAdvancedMenuItem';
        $this->lang = true;
        $this->multishop_context = Shop::CONTEXT_ALL;
        $this->identifier = 'id_menu';
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?')
            )
        );

        $this->fields_list = array(
            'id_menu' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'filter_key' => 'ml!title'
            ),
            'item_type' => array(
                'title' => $this->l('Type'),
                'type' => 'select',
                'list' => array(
                    'custom' => $this->l('Custom Link'),
                    'category' => $this->l('Category'),
                    'cms' => $this->l('CMS Page'),
                    'tab' => $this->l('Tab'),
                ),
                'filter_key' => 'a!item_type',
                'callback' => 'getItemTypeLabel'
            ),
            'id_parent' => array(
                'title' => $this->l('Parent ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'position' => 'position',
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'active' => 'status',
                'type' => 'bool',
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'ajax' => true,
                'orderby' => false
            )
        );

        parent::__construct();
    }

    /**
     * Initialize page header toolbar
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_menu'] = array(
                'href' => self::$currentIndex . '&addpc_advanced_menu&token=' . $this->token,
                'desc' => $this->l('Add new menu item', null, null, false),
                'icon' => 'process-icon-new'
            );
            $this->page_header_toolbar_btn['settings'] = array(
                'href' => self::$currentIndex . '&configure&token=' . $this->token,
                'desc' => $this->l('Settings', null, null, false),
                'icon' => 'process-icon-cogs'
            );
        }

        parent::initPageHeaderToolbar();
    }

    /**
     * Render list
     */
    public function renderList()
    {
        $this->addRowActionSkipList('edit', array());

        $lists = parent::renderList();

        if (Tools::getValue('configure')) {
            return $this->renderGeneralSettings() . $lists;
        }

        return $lists;
    }

    /**
     * Render general settings form
     */
    public function renderGeneralSettings()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Menu'),
                        'name' => 'PC_ADVANCED_MENU_ENABLED',
                        'is_bool' => true,
                        'desc' => $this->l('Enable or disable the advanced menu'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Menu Position'),
                        'name' => 'PC_ADVANCED_MENU_POSITION',
                        'desc' => $this->l('Choose whether the menu slides from left or right'),
                        'values' => array(
                            array(
                                'id' => 'position_left',
                                'value' => 'left',
                                'label' => $this->l('Left')
                            ),
                            array(
                                'id' => 'position_right',
                                'value' => 'right',
                                'label' => $this->l('Right')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save Settings'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this->module;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitGeneralSettings';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminPcAdvancedMenu', false) . '&configure';
        $helper->token = Tools::getAdminTokenLite('AdminPcAdvancedMenu');
        $helper->tpl_vars = array(
            'fields_value' => $this->getGeneralSettingsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * Get general settings values
     */
    public function getGeneralSettingsValues()
    {
        return array(
            'PC_ADVANCED_MENU_ENABLED' => Configuration::get('PC_ADVANCED_MENU_ENABLED'),
            'PC_ADVANCED_MENU_POSITION' => Configuration::get('PC_ADVANCED_MENU_POSITION'),
        );
    }

    /**
     * Process general settings save
     */
    public function processSubmitGeneralSettings()
    {
        Configuration::updateValue('PC_ADVANCED_MENU_ENABLED', Tools::getValue('PC_ADVANCED_MENU_ENABLED'));
        Configuration::updateValue('PC_ADVANCED_MENU_POSITION', Tools::getValue('PC_ADVANCED_MENU_POSITION'));

        $this->confirmations[] = $this->l('Settings updated successfully');
    }

    /**
     * Render form
     */
    public function renderForm()
    {
        // Get parent items for dropdown
        $parent_items = array(array('id' => 0, 'name' => $this->l('Root (No Parent)')));
        $items = PcAdvancedMenuItem::getMenuItems($this->context->language->id, $this->context->shop->id, false);
        foreach ($items as $item) {
            if (!isset($_GET['id_menu']) || (int)$item['id_menu'] !== (int)$_GET['id_menu']) {
                $parent_items[] = array(
                    'id' => $item['id_menu'],
                    'name' => str_repeat('-- ', (int)$this->getItemLevel($items, $item['id_menu'])) . $item['title']
                );
            }
        }

        // Get categories
        $categories = Category::getCategories($this->context->language->id, true, false);
        $category_list = array(array('id_category' => 0, 'name' => $this->l('-- Select Category --')));
        foreach ($categories as $category) {
            $category_list[] = array(
                'id_category' => $category['id_category'],
                'name' => $category['name']
            );
        }

        // Get CMS pages
        $cms_pages = CMS::getCMSPages($this->context->language->id);
        $cms_list = array(array('id_cms' => 0, 'title' => $this->l('-- Select CMS Page --')));
        foreach ($cms_pages as $cms) {
            $cms_list[] = array(
                'id_cms' => $cms['id_cms'],
                'title' => $cms['meta_title']
            );
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Menu Item'),
                'icon' => 'icon-list'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'lang' => true,
                    'required' => false,
                    'desc' => $this->l('Leave empty to use category/CMS name automatically'),
                    'col' => 6
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Item Type'),
                    'name' => 'item_type',
                    'required' => true,
                    'options' => array(
                        'query' => array(
                            array('id' => 'custom', 'name' => $this->l('Custom Link')),
                            array('id' => 'category', 'name' => $this->l('Category')),
                            array('id' => 'cms', 'name' => $this->l('CMS Page')),
                            array('id' => 'tab', 'name' => $this->l('Tab (Parent Item)')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Select the type of menu item')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Custom Link'),
                    'name' => 'link',
                    'lang' => true,
                    'desc' => $this->l('Used for custom link type only'),
                    'col' => 6
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Category'),
                    'name' => 'id_category',
                    'options' => array(
                        'query' => $category_list,
                        'id' => 'id_category',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Select category (for category type only)')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('CMS Page'),
                    'name' => 'id_cms',
                    'options' => array(
                        'query' => $cms_list,
                        'id' => 'id_cms',
                        'name' => 'title'
                    ),
                    'desc' => $this->l('Select CMS page (for CMS type only)')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Parent Item'),
                    'name' => 'id_parent',
                    'options' => array(
                        'query' => $parent_items,
                        'id' => 'id',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Select parent item for multi-level menu')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Icon Class'),
                    'name' => 'icon',
                    'desc' => $this->l('Font Awesome icon class (e.g., "fa fa-home")'),
                    'col' => 4
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image'),
                    'name' => 'image',
                    'desc' => $this->l('Upload an image for this menu item'),
                    'thumb' => $this->getImageUrl()
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Position'),
                    'name' => 'position',
                    'class' => 'fixed-width-xs',
                    'desc' => $this->l('Menu item position')
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        return parent::renderForm();
    }

    /**
     * Get item level for tree display
     */
    protected function getItemLevel($items, $id_menu, $level = 0)
    {
        foreach ($items as $item) {
            if ((int)$item['id_menu'] === (int)$id_menu) {
                if ((int)$item['id_parent'] === 0) {
                    return $level;
                }
                return $this->getItemLevel($items, $item['id_parent'], $level + 1);
            }
        }
        return $level;
    }

    /**
     * Get image URL
     */
    protected function getImageUrl()
    {
        if (Tools::getValue('id_menu')) {
            $menu_item = new PcAdvancedMenuItem((int)Tools::getValue('id_menu'));
            if ($menu_item->image) {
                return _PS_IMG_ . 'menu/' . $menu_item->image;
            }
        }
        return null;
    }

    /**
     * Process save
     */
    public function processAdd()
    {
        $this->processImageUpload();
        return parent::processAdd();
    }

    /**
     * Process update
     */
    public function processUpdate()
    {
        $this->processImageUpload();
        return parent::processUpdate();
    }

    /**
     * Process image upload
     */
    protected function processImageUpload()
    {
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $image_name = uniqid() . '_' . $_FILES['image']['name'];
            $upload_dir = _PS_IMG_DIR_ . 'menu/';

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                $_POST['image'] = $image_name;
            }
        }
    }

    /**
     * Get item type label
     */
    public function getItemTypeLabel($value)
    {
        $types = array(
            'custom' => $this->l('Custom Link'),
            'category' => $this->l('Category'),
            'cms' => $this->l('CMS Page'),
            'tab' => $this->l('Tab'),
        );
        return isset($types[$value]) ? $types[$value] : $value;
    }

    /**
     * Process status toggle
     */
    public function processStatus()
    {
        if (Validate::isLoadedObject($object = $this->loadObject())) {
            if ($object->toggleStatus()) {
                $this->redirect_after = self::$currentIndex . '&conf=5&token=' . $this->token;
            } else {
                $this->errors[] = Tools::displayError('An error occurred while updating the status.');
            }
        } else {
            $this->errors[] = Tools::displayError('An error occurred while updating the status for an object.') .
                ' <b>' . $this->table . '</b> ' . Tools::displayError('(cannot load object)');
        }

        return $object;
    }

    /**
     * Assign positions
     */
    public function ajaxProcessUpdatePositions()
    {
        $way = (int)Tools::getValue('way');
        $id_menu = (int)Tools::getValue('id');
        $positions = Tools::getValue($this->table);

        if (is_array($positions)) {
            foreach ($positions as $position => $value) {
                $pos = explode('_', $value);

                if (isset($pos[2]) && (int)$pos[2] === $id_menu) {
                    if ($menu_item = new PcAdvancedMenuItem((int)$pos[2])) {
                        if (isset($position) && $menu_item->updatePosition($way, $position)) {
                            echo 'ok position ' . (int)$position . ' for menu item ' . (int)$pos[2] . '\r\n';
                        } else {
                            echo '{"hasError" : true, "errors" : "Can not update menu item ' . (int)$id_menu . ' to position ' . (int)$position . ' "}';
                        }
                    } else {
                        echo '{"hasError" : true, "errors" : "This menu item (' . (int)$id_menu . ') can t be loaded"}';
                    }

                    break;
                }
            }
        }
    }
}
