<?php
namespace Ramphor\Memberships;

use Ramphor\Memberships\Admin\PostTypesHeader;
use Ramphor\Memberships\Admin\ProfileFields;

class Memberships
{
    protected static $instances = array();

    protected $id;
    protected $parentMenu;
    protected $profileFieldsEnabled = false;
    protected $options = array();

    private function __construct($id)
    {
        $this->id = $id;
    }

    public static function createInstance($id, $parentMenu = null, $options = array())
    {
        if (!isset(static::$instances[$id])) {
            static::$instances[$id] = new static($id);
        }
        if (!is_null($parentMenu)) {
            static::$instances[$id]->setParentMenu($parentMenu);
        }
        static::$instances[$id]->parseOptions($options);

        return static::$instances[$id];
    }

    protected function parseOptions($options)
    {
        $this->options = wp_parse_args($options, array(
            'page_title' => __('Memberships', 'ramphor_memberships'),
            'menu_title' => __('Memberships', 'ramphor_memberships'),
        ));
    }

    public function run()
    {
        new PostType($this);
        new PostTypesHeader($this);

        add_action(
            'admin_menu',
            array($this, 'registerAdminMenu')
        );

        if ($this->profileFieldsEnabled) {
            new ProfileFields($this);
        }
    }

    public function registerAdminMenu()
    {
        if (empty($this->parentMenu)) {
            return;
        }
        add_submenu_page(
            $this->parentMenu,
            $this->options['page_title'],
            $this->options['menu_title'],
            'manage_options',
            sprintf('/edit.php?post_type=%s_membership', $this->id)
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function setParentMenu($parentMenu)
    {
        $this->parentMenu = $parentMenu;
    }

    public function getParentMenu()
    {
        return $this->parentMenu;
    }

    public function enableProfileFields()
    {
        $this->profileFieldsEnabled = true;
    }
}
