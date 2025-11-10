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
    public $icon;
    public $image;
    public $position;
    public $active;
    public $date_add;
    public $date_upd;

    // Multi-lang fields
    public $title;
    public $link;

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
            'item_type' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size' => 50
            ),
            'icon' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 100),
            'image' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

            // Multi-lang fields
            'title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'required' => false,
                'size' => 255
            ),
            'link' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isUrl',
                'required' => false,
                'size' => 255
            ),
        ),
    );

    /**
     * Get all menu items
     */
    public static function getMenuItems($id_lang, $id_shop, $active = true)
    {
        $sql = new DbQuery();
        $sql->select('m.*, ml.title, ml.link');
        $sql->from('pc_advanced_menu', 'm');
        $sql->leftJoin('pc_advanced_menu_lang', 'ml', 'm.id_menu = ml.id_menu AND ml.id_lang = ' . (int)$id_lang);
        $sql->leftJoin('pc_advanced_menu_shop', 'ms', 'm.id_menu = ms.id_menu AND ms.id_shop = ' . (int)$id_shop);
        if ($active) {
            $sql->where('m.active = 1');
        }
        $sql->where('(ms.id_shop = ' . (int)$id_shop . ' OR ms.id_shop IS NULL)');
        $sql->orderBy('m.position ASC');

        return Db::getInstance()->executeS($sql);
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
