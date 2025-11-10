<?php
/**
 * Menu Item Class
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class PcAdvancedMenuItem extends ObjectModel
{
    public $id_menu;
    public $id_parent;
    public $id_category;
    public $id_cms;
    public $item_type;
    public $menu_type; // horizontal, vertical, mobile
    public $url_type; // 0=system, 1=custom, 2=no link
    public $icon_type; // 0=image, 1=class
    public $icon_class;
    public $icon;
    public $image;
    public $legend_icon;
    public $position;
    public $active;
    public $active_label;
    public $new_window;
    public $float;

    // Submenu configuration
    public $submenu_type; // 0=none, 1=simple, 2=grid
    public $submenu_width;
    public $submenu_content; // JSON structure for grid content
    public $submenu_image;
    public $submenu_repeat;
    public $submenu_bg_position;

    // Color customization
    public $bg_color;
    public $txt_color;
    public $h_bg_color; // hover
    public $h_txt_color; // hover
    public $labelbg_color;
    public $labeltxt_color;
    public $submenu_bg_color;
    public $submenu_link_color;
    public $submenu_hover_color;
    public $submenu_title_color;
    public $submenu_title_colorh;
    public $submenu_titleb_color;

    // Border customization
    public $submenu_border_t;
    public $submenu_border_r;
    public $submenu_border_b;
    public $submenu_border_l;
    public $submenu_border_i;

    // Access control
    public $group_access;

    public $date_add;
    public $date_upd;

    // Multi-lang fields
    public $title;
    public $link;
    public $label;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'pc_advanced_menu',
        'primary' => 'id_menu',
        'multilang' => true,
        'multishop' => true,
        'fields' => array(
            'id_parent' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_category' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_cms' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'item_type' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 50),
            'menu_type' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'url_type' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'icon_type' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'icon_class' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 100),
            'icon' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
            'image' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
            'legend_icon' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'active_label' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'new_window' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'float' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),

            // Submenu configuration
            'submenu_type' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'submenu_width' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'submenu_content' => array('type' => self::TYPE_HTML),
            'submenu_image' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
            'submenu_repeat' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'submenu_bg_position' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),

            // Color customization
            'bg_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'txt_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'h_bg_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'h_txt_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'labelbg_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'labeltxt_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'submenu_bg_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'submenu_link_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'submenu_hover_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'submenu_title_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'submenu_title_colorh' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'submenu_titleb_color' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),

            // Border customization
            'submenu_border_t' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'submenu_border_r' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'submenu_border_b' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'submenu_border_l' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),
            'submenu_border_i' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 50),

            // Access control
            'group_access' => array('type' => self::TYPE_STRING),

            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

            // Multi-lang fields
            'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => false, 'size' => 255),
            'link' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => false, 'size' => 255),
            'label' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => false, 'size' => 255),
        ),
    );

    /**
     * Constructor
     */
    public function __construct($id_menu = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('pc_advanced_menu', array('type' => 'shop'));
        parent::__construct($id_menu, $id_lang, $id_shop);
    }

    /**
     * Get all menu items
     */
    public static function getMenuItems($id_lang, $id_shop, $active = true, $menu_type = 0)
    {
        $sql = new DbQuery();
        $sql->select('m.*, ml.title, ml.link, ml.label');
        $sql->from('pc_advanced_menu', 'm');
        $sql->leftJoin('pc_advanced_menu_lang', 'ml', 'm.id_menu = ml.id_menu AND ml.id_lang = ' . (int)$id_lang);
        $sql->leftJoin('pc_advanced_menu_shop', 'ms', 'm.id_menu = ms.id_menu AND ms.id_shop = ' . (int)$id_shop);
        if ($active) {
            $sql->where('m.active = 1');
        }
        $sql->where('m.menu_type = ' . (int)$menu_type);
        $sql->where('(ms.id_shop = ' . (int)$id_shop . ' OR ms.id_shop IS NULL)');
        $sql->orderBy('m.position ASC');

        return Db::getInstance()->executeS($sql);
    }

    /**
     * Get next position for menu type
     */
    public static function getNextPosition($menu_type = 0)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;

        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
            SELECT MAX(m.position) AS next_position
            FROM `' . _DB_PREFIX_ . 'pc_advanced_menu` m
            INNER JOIN `' . _DB_PREFIX_ . 'pc_advanced_menu_shop` ms ON m.id_menu = ms.id_menu
            WHERE m.menu_type = ' . (int)$menu_type . ' AND ms.id_shop = ' . (int)$id_shop
        );

        return (++$row['next_position']);
    }

    /**
     * Reorder positions after delete
     */
    public function reOrderPositions($menu_type)
    {
        $id_menu = $this->id_menu;
        $context = Context::getContext();
        $id_shop = $context->shop->id;

        $rows = Db::getInstance()->executeS('
            SELECT m.position, m.id_menu
            FROM `' . _DB_PREFIX_ . 'pc_advanced_menu` m
            INNER JOIN `' . _DB_PREFIX_ . 'pc_advanced_menu_shop` ms ON m.id_menu = ms.id_menu
            WHERE ms.id_shop = ' . (int)$id_shop . '
            AND m.menu_type = ' . (int)$menu_type . '
            AND m.position > ' . (int)$this->position
        );

        foreach ($rows as $row) {
            $current_item = new PcAdvancedMenuItem($row['id_menu']);
            --$current_item->position;
            $current_item->update();
            unset($current_item);
        }

        return true;
    }

    /**
     * Delete menu item and reorder
     */
    public function delete()
    {
        $res = true;
        $item = new PcAdvancedMenuItem((int)$this->id_menu);
        $res &= $this->reOrderPositions($item->menu_type);
        $res &= Db::getInstance()->delete('pc_advanced_menu_shop', 'id_menu = ' . (int)$this->id_menu);
        $res &= parent::delete();
        return $res;
    }

    /**
     * Get max position
     */
    public static function getMaxPosition()
    {
        $sql = 'SELECT MAX(position) FROM `' . _DB_PREFIX_ . 'pc_advanced_menu`';
        $result = Db::getInstance()->getValue($sql);
        return (int)$result;
    }

    /**
     * Update positions
     */
    public static function updatePositions($items)
    {
        foreach ($items as $position => $id_menu) {
            Db::getInstance()->update(
                'pc_advanced_menu',
                array('position' => (int)$position),
                'id_menu = ' . (int)$id_menu
            );
        }
        return true;
    }
}
