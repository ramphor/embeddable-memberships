<?php
namespace Ramphor\Memberships\Interfaces;

interface MetaboxTab
{
    public function get_name();

    public function get_title();

    public function render();

    public function save($post_id, $post);
}
