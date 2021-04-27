<?php
namespace Ramphor\Memberships;

use Ramphor\Memberships\Admin\PostTypesHeader;
use Ramphor\Memberships\Admin\ProfileFields;
use Ramphor\Memberships\Admin\Metaboxes\MembershipPlan as MembershipPlanMetabox;

class Memberships
{
    const LIB_VERSION = '1.0.0.12';

    protected static $instances = array();
    protected static $calledScripts = false;

    protected $id;
    protected $parentMenu;
    protected $profileFieldsEnabled = false;
    protected $options = array();


    private function __construct($id)
    {
        $this->id = $id;
        if (!defined('RAMPHOR_MEMBERSHIPS_ROOT_DIR')) {
            define('RAMPHOR_MEMBERSHIPS_ROOT_DIR', dirname(__DIR__));
        }

        if (is_admin() && !static::$calledScripts) {
            add_action('admin_enqueue_scripts', array($this, 'registerScripts'));
            static::$calledScripts = true;
        }
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
        new MembershipPlanMetabox($this);

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

    public function asset_url($path)
    {
        $abspath = ABSPATH;
        $rootdir = RAMPHOR_MEMBERSHIPS_ROOT_DIR;
        if (PHP_OS === 'WINNT') {
            $abspath = str_replace('\\', '/', $abspath);
            $rootdir = str_replace('\\', '/', RAMPHOR_MEMBERSHIPS_ROOT_DIR);
        }
        $root_url = str_replace($abspath, site_url('/'), $rootdir);

        return sprintf('%s/assets/%s', $root_url, $path);
    }

    public function registerScripts()
    {
        wp_register_style(
            'ramphor-memberships',
            $this->asset_url('admin/memberships.css'),
            array(),
            static::LIB_VERSION
        );

        wp_enqueue_style('ramphor-memberships');
    }
}
