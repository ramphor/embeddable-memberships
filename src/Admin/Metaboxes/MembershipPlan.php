<?php
namespace Ramphor\Memberships\Admin\Metaboxes;

use Ramphor\Memberships\Memberships;

class MembershipPlan
{
    protected $memebershipId;

    public function __construct($memebership)
    {
        if (!is_a($memebership, Memberships::class)) {
            return;
        }
        $this->membershipId = $memebership->getId();

        add_action('add_meta_boxes', array($this, 'register_meta_boxes'));
    }

    public function register_meta_boxes()
    {
        add_meta_box(
            "{$this->memebershipId}_plan_data",
            __('Membership Plan Data', 'ramphor_memberships'),
            array($this, 'render'),
            "{$this->membershipId}_membership_plan",
            'advanced',
            'core'
        );
    }

    public function render()
    {
        ?>
        <?php
    }
}
