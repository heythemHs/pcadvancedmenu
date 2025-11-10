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
     * Render form with tabs
     */
    public function renderForm()
    {
        // Get data for dropdowns
        $parent_items = $this->getParentMenuItems();
        $category_list = $this->getCategoryList();
        $cms_list = $this->getCMSList();
        $customer_groups = $this->getCustomerGroups();

        // Tab 1: Basic Information
        $this->fields_form = array(
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Basic Information'),
                        'icon' => 'icon-info'
                    ),
                    'input' => array(
                        array(
                            'type' => 'text',
                            'label' => $this->l('Title'),
                            'name' => 'title',
                            'lang' => true,
                            'required' => true,
                            'desc' => $this->l('Menu item title displayed to users'),
                            'col' => 6
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Label'),
                            'name' => 'label',
                            'lang' => true,
                            'desc' => $this->l('Optional label like "New" or "Sale"'),
                            'col' => 4
                        ),
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Show Label'),
                            'name' => 'active_label',
                            'is_bool' => true,
                            'values' => array(
                                array('id' => 'active_label_on', 'value' => 1, 'label' => $this->l('Yes')),
                                array('id' => 'active_label_off', 'value' => 0, 'label' => $this->l('No'))
                            ),
                        ),
                        array(
                            'type' => 'radio',
                            'label' => $this->l('Menu Type'),
                            'name' => 'menu_type',
                            'values' => array(
                                array('id' => 'menu_type_0', 'value' => 0, 'label' => $this->l('Horizontal')),
                                array('id' => 'menu_type_1', 'value' => 1, 'label' => $this->l('Vertical')),
                                array('id' => 'menu_type_2', 'value' => 2, 'label' => $this->l('Mobile'))
                            ),
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Position'),
                            'name' => 'position',
                            'class' => 'fixed-width-xs',
                            'desc' => $this->l('Menu item display order')
                        ),
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Active'),
                            'name' => 'active',
                            'is_bool' => true,
                            'values' => array(
                                array('id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')),
                                array('id' => 'active_off', 'value' => 0, 'label' => $this->l('No'))
                            ),
                        ),
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Float'),
                            'name' => 'float',
                            'is_bool' => true,
                            'desc' => $this->l('Float menu item to right side'),
                            'values' => array(
                                array('id' => 'float_on', 'value' => 1, 'label' => $this->l('Yes')),
                                array('id' => 'float_off', 'value' => 0, 'label' => $this->l('No'))
                            ),
                        ),
                        array(
                            'type' => 'switch',
                            'label' => $this->l('Open in New Window'),
                            'name' => 'new_window',
                            'is_bool' => true,
                            'values' => array(
                                array('id' => 'new_window_on', 'value' => 1, 'label' => $this->l('Yes')),
                                array('id' => 'new_window_off', 'value' => 0, 'label' => $this->l('No'))
                            ),
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    )
                ),
            ),
            // Tab 2: URL Configuration
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('URL Configuration'),
                        'icon' => 'icon-link'
                    ),
                    'input' => array(
                        array(
                            'type' => 'radio',
                            'label' => $this->l('URL Type'),
                            'name' => 'url_type',
                            'values' => array(
                                array('id' => 'url_type_0', 'value' => 0, 'label' => $this->l('System URL (Category/CMS)')),
                                array('id' => 'url_type_1', 'value' => 1, 'label' => $this->l('Custom URL')),
                                array('id' => 'url_type_2', 'value' => 2, 'label' => $this->l('No Link (Parent Only)'))
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'system_url_wrapper_start',
                            'html_content' => '<div id="system-url-wrapper" class="conditional-wrapper">'
                        ),
                        array(
                            'type' => 'select',
                            'label' => $this->l('System Item Type'),
                            'name' => 'item_type',
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
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'system_url_wrapper_end',
                            'html_content' => '</div>'
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'custom_url_wrapper_start',
                            'html_content' => '<div id="custom-url-wrapper" class="conditional-wrapper">'
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Custom Link'),
                            'name' => 'link',
                            'lang' => true,
                            'desc' => $this->l('Enter custom URL (e.g., https://example.com)'),
                            'col' => 6
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'custom_url_wrapper_end',
                            'html_content' => '</div>'
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
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    )
                ),
            ),
            // Tab 3: Icon & Image
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Icon & Image'),
                        'icon' => 'icon-picture-o'
                    ),
                    'input' => array(
                        array(
                            'type' => 'radio',
                            'label' => $this->l('Icon Type'),
                            'name' => 'icon_type',
                            'values' => array(
                                array('id' => 'icon_type_0', 'value' => 0, 'label' => $this->l('Upload Icon Image')),
                                array('id' => 'icon_type_1', 'value' => 1, 'label' => $this->l('Icon Class (Font Awesome)'))
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'icon_class_wrapper_start',
                            'html_content' => '<div id="icon-class-wrapper" class="conditional-wrapper">'
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Icon Class'),
                            'name' => 'icon_class',
                            'desc' => $this->l('Font Awesome icon class (e.g., "fa fa-home")'),
                            'col' => 4
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'icon_class_wrapper_end',
                            'html_content' => '</div>'
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'icon_image_wrapper_start',
                            'html_content' => '<div id="icon-image-wrapper" class="conditional-wrapper">'
                        ),
                        array(
                            'type' => 'file',
                            'label' => $this->l('Icon Image'),
                            'name' => 'icon',
                            'desc' => $this->l('Upload an icon image (small size recommended)'),
                            'thumb' => $this->getIconImageUrl()
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'icon_image_wrapper_end',
                            'html_content' => '</div>'
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Legend Icon'),
                            'name' => 'legend_icon',
                            'desc' => $this->l('Optional legend icon text'),
                            'col' => 4
                        ),
                        array(
                            'type' => 'file',
                            'label' => $this->l('Menu Item Image'),
                            'name' => 'image',
                            'desc' => $this->l('Upload a main image for this menu item'),
                            'thumb' => $this->getImageUrl()
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    )
                ),
            ),
            // Tab 4: Submenu Configuration
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Submenu Configuration'),
                        'icon' => 'icon-sitemap'
                    ),
                    'input' => array(
                        array(
                            'type' => 'radio',
                            'label' => $this->l('Submenu Type'),
                            'name' => 'submenu_type',
                            'values' => array(
                                array('id' => 'submenu_type_0', 'value' => 0, 'label' => $this->l('No Submenu')),
                                array('id' => 'submenu_type_1', 'value' => 1, 'label' => $this->l('Simple Submenu')),
                                array('id' => 'submenu_type_2', 'value' => 2, 'label' => $this->l('Grid Submenu (Mega Menu)'))
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'submenu_options_wrapper_start',
                            'html_content' => '<div id="submenu-options-wrapper" class="conditional-wrapper">'
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Submenu Width'),
                            'name' => 'submenu_width',
                            'desc' => $this->l('Submenu width in pixels (e.g., 800)'),
                            'class' => 'fixed-width-sm',
                            'suffix' => 'px'
                        ),
                        array(
                            'type' => 'file',
                            'label' => $this->l('Submenu Background Image'),
                            'name' => 'submenu_image',
                            'desc' => $this->l('Background image for submenu'),
                            'thumb' => $this->getSubmenuImageUrl()
                        ),
                        array(
                            'type' => 'select',
                            'label' => $this->l('Background Repeat'),
                            'name' => 'submenu_repeat',
                            'options' => array(
                                'query' => array(
                                    array('id' => 'no-repeat', 'name' => $this->l('No Repeat')),
                                    array('id' => 'repeat', 'name' => $this->l('Repeat')),
                                    array('id' => 'repeat-x', 'name' => $this->l('Repeat Horizontally')),
                                    array('id' => 'repeat-y', 'name' => $this->l('Repeat Vertically')),
                                ),
                                'id' => 'id',
                                'name' => 'name'
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'label' => $this->l('Background Position'),
                            'name' => 'submenu_bg_position',
                            'options' => array(
                                'query' => array(
                                    array('id' => 'left top', 'name' => $this->l('Left Top')),
                                    array('id' => 'center top', 'name' => $this->l('Center Top')),
                                    array('id' => 'right top', 'name' => $this->l('Right Top')),
                                    array('id' => 'left center', 'name' => $this->l('Left Center')),
                                    array('id' => 'center center', 'name' => $this->l('Center Center')),
                                    array('id' => 'right center', 'name' => $this->l('Right Center')),
                                    array('id' => 'left bottom', 'name' => $this->l('Left Bottom')),
                                    array('id' => 'center bottom', 'name' => $this->l('Center Bottom')),
                                    array('id' => 'right bottom', 'name' => $this->l('Right Bottom')),
                                ),
                                'id' => 'id',
                                'name' => 'name'
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'submenu_options_wrapper_end',
                            'html_content' => '</div>'
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'grid_builder_wrapper_start',
                            'html_content' => '<div id="grid-builder-wrapper" class="conditional-wrapper">'
                        ),
                        array(
                            'type' => 'textarea',
                            'label' => $this->l('Submenu Grid Content (JSON)'),
                            'name' => 'submenu_content',
                            'desc' => $this->l('Grid content in JSON format. Use Grid Builder (Phase 2 Sprint 2) for visual editing.'),
                            'rows' => 10,
                            'cols' => 70
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'grid_builder_wrapper_end',
                            'html_content' => '</div>'
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    )
                ),
            ),
            // Tab 5: Colors
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Color Customization'),
                        'icon' => 'icon-tint'
                    ),
                    'input' => array(
                        array(
                            'type' => 'color',
                            'label' => $this->l('Background Color'),
                            'name' => 'bg_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Menu item background color')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Text Color'),
                            'name' => 'txt_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Menu item text color')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Hover Background Color'),
                            'name' => 'h_bg_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Background color on hover')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Hover Text Color'),
                            'name' => 'h_txt_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Text color on hover')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Label Background Color'),
                            'name' => 'labelbg_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Label badge background color')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Label Text Color'),
                            'name' => 'labeltxt_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Label badge text color')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Submenu Background Color'),
                            'name' => 'submenu_bg_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Submenu background color')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Submenu Link Color'),
                            'name' => 'submenu_link_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Submenu link text color')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Submenu Hover Color'),
                            'name' => 'submenu_hover_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Submenu link hover color')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Submenu Title Color'),
                            'name' => 'submenu_title_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Submenu column title color')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Submenu Title Hover Color'),
                            'name' => 'submenu_title_colorh',
                            'class' => 'color-picker',
                            'desc' => $this->l('Submenu title hover color')
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Submenu Title Bottom Color'),
                            'name' => 'submenu_titleb_color',
                            'class' => 'color-picker',
                            'desc' => $this->l('Submenu title bottom border color')
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    )
                ),
            ),
            // Tab 6: Borders
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Border Configuration'),
                        'icon' => 'icon-square-o'
                    ),
                    'input' => array(
                        array(
                            'type' => 'text',
                            'label' => $this->l('Top Border'),
                            'name' => 'submenu_border_t',
                            'desc' => $this->l('Top border CSS (e.g., "1px solid #ccc")'),
                            'col' => 6
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Right Border'),
                            'name' => 'submenu_border_r',
                            'desc' => $this->l('Right border CSS'),
                            'col' => 6
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Bottom Border'),
                            'name' => 'submenu_border_b',
                            'desc' => $this->l('Bottom border CSS'),
                            'col' => 6
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Left Border'),
                            'name' => 'submenu_border_l',
                            'desc' => $this->l('Left border CSS'),
                            'col' => 6
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Inner Border'),
                            'name' => 'submenu_border_i',
                            'desc' => $this->l('Inner column border CSS'),
                            'col' => 6
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    )
                ),
            ),
            // Tab 7: Access Control
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Access Control'),
                        'icon' => 'icon-users'
                    ),
                    'input' => array(
                        array(
                            'type' => 'group',
                            'label' => $this->l('Customer Group Access'),
                            'name' => 'groupBox',
                            'values' => $customer_groups,
                            'desc' => $this->l('Select which customer groups can see this menu item'),
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    )
                ),
            ),
        );

        return parent::renderForm();
    }

    /**
     * Get parent menu items for dropdown
     */
    protected function getParentMenuItems()
    {
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

        return $parent_items;
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

        $list = array(array('id_cms' => 0, 'title' => $this->l('-- Select CMS Page --')));

        if (is_array($cms_pages)) {
            foreach ($cms_pages as $cms) {
                $list[] = array(
                    'id_cms' => $cms['id_cms'],
                    'title' => $cms['meta_title']
                );
            }
        }

        return $list;
    }

    /**
     * Get customer groups
     */
    protected function getCustomerGroups()
    {
        return Group::getGroups($this->context->language->id);
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
     * Get main menu item image URL
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
     * Get icon image URL
     */
    protected function getIconImageUrl()
    {
        if (Tools::getValue('id_menu')) {
            $menu_item = new PcAdvancedMenuItem((int)Tools::getValue('id_menu'));
            if ($menu_item->icon && $menu_item->icon_type == 0) {
                return _PS_IMG_ . 'menu/icons/' . $menu_item->icon;
            }
        }
        return null;
    }

    /**
     * Get submenu background image URL
     */
    protected function getSubmenuImageUrl()
    {
        if (Tools::getValue('id_menu')) {
            $menu_item = new PcAdvancedMenuItem((int)Tools::getValue('id_menu'));
            if ($menu_item->submenu_image) {
                return _PS_IMG_ . 'menu/submenu/' . $menu_item->submenu_image;
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
     * Process image uploads (handles icon, image, submenu_image)
     */
    protected function processImageUpload()
    {
        // Process main menu item image
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

        // Process icon image
        if (isset($_FILES['icon']) && $_FILES['icon']['size'] > 0) {
            $icon_name = uniqid() . '_' . $_FILES['icon']['name'];
            $upload_dir = _PS_IMG_DIR_ . 'menu/icons/';

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (move_uploaded_file($_FILES['icon']['tmp_name'], $upload_dir . $icon_name)) {
                $_POST['icon'] = $icon_name;
            }
        }

        // Process submenu background image
        if (isset($_FILES['submenu_image']) && $_FILES['submenu_image']['size'] > 0) {
            $submenu_image_name = uniqid() . '_' . $_FILES['submenu_image']['name'];
            $upload_dir = _PS_IMG_DIR_ . 'menu/submenu/';

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (move_uploaded_file($_FILES['submenu_image']['tmp_name'], $upload_dir . $submenu_image_name)) {
                $_POST['submenu_image'] = $submenu_image_name;
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
