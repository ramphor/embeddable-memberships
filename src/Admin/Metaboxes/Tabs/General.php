<?php
namespace Ramphor\Memberships\Admin\Metaboxes\Tabs;

use Ramphor\Memberships\Abstracts\MetaboxTab;

class General extends MetaboxTab
{
    const TAB_NAME = 'general';

    public function get_name()
    {
        return static::TAB_NAME;
    }

    public function get_title()
    {
        return __('General', 'ramphor_memberships');
    }

    public function render()
    {
    }
}
