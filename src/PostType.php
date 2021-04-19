<?php
namespace Ramphor\Memberships;

class PostType
{
    protected $memebership;

    public function __construct($memebershipInstance)
    {
        if (!is_a($memebershipInstance, Memberships::class)) {
            return;
        }
        $this->membership = $memebershipInstance;

        add_action('init', array($this, 'registerPostType'));
    }

    public function registerPostType()
    {
        $id = $this->membership->getId();
        $parentMenu = $this->membership->getParentMenu();
        $labels = array(
            'name' => __('Memberships', 'ramphor_memberships'),
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_in_menu' => is_null($parentMenu),
        );

        register_post_type("{$id}_membership", apply_filters(
            "ramphor_memberships_{$id}_post_type_args",
            $args
        ));
    }
}
