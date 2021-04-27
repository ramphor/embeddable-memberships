<?php
namespace Ramphor\Memberships\Admin\Metaboxes\Tabs;

use Ramphor\Memberships\Abstracts\MetaboxTab;

class RestrictContent extends MetaboxTab
{
    const TAB_NAME = 'restrict_content';

    protected $postTypes = array(
        'post',
        'page'
    );
    protected $taxonomies = array(
        'category',
        'post_tag'
    );

    public function get_name()
    {
        return static::TAB_NAME;
    }

    public function get_title()
    {
        return __('Restrict Content', 'ramphor_memberships');
    }

    public function get_icon()
    {
        return '<span class="dashicons dashicons-format-aside"></span>';
    }

    public function render($post)
    {
    }
}
