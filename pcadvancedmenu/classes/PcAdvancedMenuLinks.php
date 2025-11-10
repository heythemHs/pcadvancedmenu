<?php
/**
 * Links Class for Menu Content
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class PcAdvancedMenuLinks extends ObjectModel
{
    public $id_link;
    public $active;
    public $position;
    public $new_window;
    public $date_add;
    public $date_upd;

    // Multi-lang fields
    public $title;
    public $url;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'pc_advanced_menu_links',
        'primary' => 'id_link',
        'multilang' => true,
        'multishop' => true,
        'fields' => array(
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'new_window' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

            // Multi-lang fields
            'title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'required' => true,
                'size' => 255
            ),
            'url' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isUrl',
                'required' => true,
                'size' => 255
            ),
        ),
    );

    /**
     * Constructor
     */
    public function __construct($id_link = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('pc_advanced_menu_links', array('type' => 'shop'));
        parent::__construct($id_link, $id_lang, $id_shop);
    }

    /**
     * Get all links
     */
    public static function getAllLinks($id_lang, $id_shop, $active = true)
    {
        $sql = new DbQuery();
        $sql->select('l.*, ll.title, ll.url');
        $sql->from('pc_advanced_menu_links', 'l');
        $sql->leftJoin('pc_advanced_menu_links_lang', 'll', 'l.id_link = ll.id_link AND ll.id_lang = ' . (int)$id_lang);
        $sql->leftJoin('pc_advanced_menu_links_shop', 'ls', 'l.id_link = ls.id_link AND ls.id_shop = ' . (int)$id_shop);
        if ($active) {
            $sql->where('l.active = 1');
        }
        $sql->orderBy('l.position ASC');

        return Db::getInstance()->executeS($sql);
    }

    /**
     * Get links for select dropdown
     */
    public static function getLinksForSelect($id_lang)
    {
        $links = self::getAllLinks($id_lang, Context::getContext()->shop->id, false);
        $options = array();

        foreach ($links as $link) {
            $options[] = array(
                'id_link' => $link['id_link'],
                'title' => $link['title']
            );
        }

        return $options;
    }
}
