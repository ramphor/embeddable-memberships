<?php
namespace Ramphor\Memberships\Interfaces;

interface MetaboxTab
{
    public function get_name();

    public function get_title();

    public function render($post);

    public function save($post_id, $post);
}
