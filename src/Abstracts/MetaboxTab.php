<?php
namespace Ramphor\Memberships\Abstracts;

use Ramphor\Memberships\Interfaces\MetaboxTab as MetaboxTabInterface;

abstract class MetaboxTab implements MetaboxTabInterface
{
    public function get_icon()
    {
    }

    public function save($post_id, $post)
    {
    }
}
