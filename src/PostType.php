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


        add_action('init', array($this, 'registerPostTypes'));
    }

    public function registerPostTypes()
    {
        $id = $this->membership->getId();
        $parentMenu = $this->membership->getParentMenu();

        $labels = array(
            'name'                  => _x('Members', 'Post type general name', 'ramphor_memberships'),
            'singular_name'         => _x('Member', 'Post type singular name', 'ramphor_memberships'),
            'menu_name'             => _x('Memberships', 'Admin Menu text', 'ramphor_memberships'),
            'name_admin_bar'        => _x('Membership', 'Add New on Toolbar', 'ramphor_memberships'),
            'add_new'               => __('Add Member', 'ramphor_memberships'),
            'add_new_item'          => __('Add New Member', 'ramphor_memberships'),
            'new_item'              => __('New membership', 'ramphor_memberships'),
            'edit_item'             => __('Edit membership', 'ramphor_memberships'),
            'view_item'             => __('View membership', 'ramphor_memberships'),
            'all_items'             => __('All memberships', 'ramphor_memberships'),
            'search_items'          => __('Search memberships', 'ramphor_memberships'),
            'parent_item_colon'     => __('Parent memberships:', 'ramphor_memberships'),
            'not_found'             => __('No memberships found.', 'ramphor_memberships'),
            'not_found_in_trash'    => __('No memberships found in Trash.', 'ramphor_memberships'),
            'featured_image'        => _x('Membership Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'ramphor_memberships'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'ramphor_memberships'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'ramphor_memberships'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'ramphor_memberships'),
            'archives'              => _x('Membership archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'ramphor_memberships'),
            'insert_into_item'      => _x('Insert into membership', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'ramphor_memberships'),
            'uploaded_to_this_item' => _x('Uploaded to this membership', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'ramphor_memberships'),
            'filter_items_list'     => _x('Filter memberships list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'ramphor_memberships'),
            'items_list_navigation' => _x('Memberships list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'ramphor_memberships'),
            'items_list'            => _x('Memberships list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'ramphor_memberships'),
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_in_menu' => is_null($parentMenu),
        );
        register_post_type("{$id}_user_membership", apply_filters(
            "ramphor_memberships_{$id}_post_type_args",
            $args
        ));


        $labels = array(
            'name'                  => _x('Membership Plans', 'Post type general name', 'ramphor_memberships'),
            'singular_name'         => _x('Membership Plan', 'Post type singular name', 'ramphor_memberships'),
            'menu_name'             => _x('Membership Plans', 'Admin Menu text', 'ramphor_memberships'),
            'name_admin_bar'        => _x('Membership Plan', 'Add New on Toolbar', 'ramphor_memberships'),
            'add_new'               => __('Add New', 'ramphor_memberships'),
            'add_new_item'          => __('Add New Plan', 'ramphor_memberships'),
            'new_item'              => __('New Membership Plan', 'ramphor_memberships'),
            'edit_item'             => __('Edit Membership Plan', 'ramphor_memberships'),
            'view_item'             => __('View Membership Plan', 'ramphor_memberships'),
            'all_items'             => __('All Membership Plans', 'ramphor_memberships'),
            'search_items'          => __('Search membership plans', 'ramphor_memberships'),
            'parent_item_colon'     => __('Parent membership plans:', 'ramphor_memberships'),
            'not_found'             => __('No membership plans found.', 'ramphor_memberships'),
            'not_found_in_trash'    => __('No membership plans found in Trash.', 'ramphor_memberships'),
            'featured_image'        => _x('Membership Plan Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'ramphor_memberships'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'ramphor_memberships'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'ramphor_memberships'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'ramphor_memberships'),
            'archives'              => _x('Membership Plan archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'ramphor_memberships'),
            'insert_into_item'      => _x('Insert into membership plan', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'ramphor_memberships'),
            'uploaded_to_this_item' => _x('Uploaded to this membership plan', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'ramphor_memberships'),
            'filter_items_list'     => _x('Filter membership plans list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'ramphor_memberships'),
            'items_list_navigation' => _x('Membership Plans list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'ramphor_memberships'),
            'items_list'            => _x('Membership Plans list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'ramphor_memberships'),
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_in_menu' => false,
            'supports' => array('title')
        );
        register_post_type("{$id}_membership_plan", apply_filters(
            "ramphor_memberships_{$id}_plan_post_type_args",
            $args
        ));
    }
}
