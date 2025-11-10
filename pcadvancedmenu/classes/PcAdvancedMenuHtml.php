<?php
/**
 * HTML Block Class for Menu Content
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class PcAdvancedMenuHtml extends ObjectModel
{
    public $id_html;
    public $active;
    public $position;
    public $date_add;
    public $date_upd;

    // Multi-lang fields
    public $title;
    public $content;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'pc_advanced_menu_html',
        'primary' => 'id_html',
        'multilang' => true,
        'multishop' => true,
        'fields' => array(
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
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
            'content' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'required' => true
            ),
        ),
    );

    /**
     * Constructor
     */
    public function __construct($id_html = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('pc_advanced_menu_html', array('type' => 'shop'));
        parent::__construct($id_html, $id_lang, $id_shop);
    }

    /**
     * Get all HTML blocks
     */
    public static function getAllBlocks($id_lang, $id_shop, $active = true)
    {
        $sql = new DbQuery();
        $sql->select('h.*, hl.title, hl.content');
        $sql->from('pc_advanced_menu_html', 'h');
        $sql->leftJoin('pc_advanced_menu_html_lang', 'hl', 'h.id_html = hl.id_html AND hl.id_lang = ' . (int)$id_lang);
        $sql->leftJoin('pc_advanced_menu_html_shop', 'hs', 'h.id_html = hs.id_html AND hs.id_shop = ' . (int)$id_shop);
        if ($active) {
            $sql->where('h.active = 1');
        }
        $sql->orderBy('h.position ASC');

        return Db::getInstance()->executeS($sql);
    }

    /**
     * Get HTML blocks for select dropdown
     */
    public static function getBlocksForSelect($id_lang)
    {
        $blocks = self::getAllBlocks($id_lang, Context::getContext()->shop->id, false);
        $options = array();

        foreach ($blocks as $block) {
            $options[] = array(
                'id_html' => $block['id_html'],
                'title' => $block['title']
            );
        }

        return $options;
    }
}
