<?php
namespace Ramphor\Memberships\Abstracts;

use Ramphor\Memberships\Interfaces\MetaboxTab as MetaboxTabInterface;

abstract class MetaboxTab implements MetaboxTabInterface
{
    protected $workspace;

    public function setWorkspace($workspaceId)
    {
        $this->workspace = $workspaceId;
    }

    public function get_icon()
    {
    }

    public function save($post_id, $post)
    {
    }
}
