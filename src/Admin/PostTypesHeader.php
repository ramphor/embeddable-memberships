<?php
namespace Ramphor\Memberships\Admin;

use Ramphor\Memberships\Memberships;

class PostTypesHeader
{
    protected $membershipId;
    protected $postTypes;

    public function __construct($membershipInstance)
    {
        if (!is_a($membershipInstance, Memberships::class)) {
            return;
        }
        $this->membershipId = $membershipInstance->getId();
        $this->postTypes = array_map(function ($type) {
            return "{$this->membershipId}_{$type}";
        }, array(
            'membership',
            'membership_plan',
        ));

        add_action('current_screen', array($this, 'init'));
    }

    protected function createHeaderMenuItemClasses($needed, $currentScreen, $type = 'post_type')
    {
        switch ($type) {
            case 'post_type':
                return isset($currentScreen->post_type) && $currentScreen->post_type === $needed ? ' nav-tab-active' : '';
        }
    }

    protected function createHeaderMenuItems()
    {
        global $wp_post_types;
        $menuItems = array();
        $currentScreen = get_current_screen();
        foreach ($this->postTypes as $postType) {
            $postTypeObject = get_post_type_object($postType);

            $menuItems[] = array(
                'label' => $postTypeObject->labels->name,
                'url' => sprintf('%s?post_type=%s', admin_url('edit.php'), $postType),
                'html_classes' => $this->createHeaderMenuItemClasses($postType, $currentScreen, 'post_type'),
            );
        }

        return apply_filters("ramphor_memberships_{$this->membershipId}_toolbar_items", $menuItems);
    }

    public function init()
    {
        $currentScreen = get_current_screen();
        if (isset($currentScreen->post_type) && in_array($currentScreen->post_type, $this->postTypes)) {
            if ($currentScreen->base === 'edit') {
                add_action(
                    'all_admin_notices',
                    array($this, 'renderMembershipMenuToolBar')
                );
            } elseif ($currentScreen->action === 'add') {
                add_filter('admin_body_class', function ($classes) {
                    return $classes .= ' ramphor_memberships';
                });
            }
        }
    }

    public function renderMembershipMenuToolBar()
    {
        $menuItems = $this->createHeaderMenuItems();

        ?>
        <div class="wrap ramphor-memberships">
            <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
                <?php foreach ($menuItems as $menuItem) : ?>
                <a href="<?php echo $menuItem['url'] ?>" class="nav-tab<?php echo $menuItem['html_classes'] ?>">
                    <?php echo $menuItem['label']; ?>
                </a>
                <?php endforeach; ?>
            </h2>
        </div>
        <?php
    }
}
