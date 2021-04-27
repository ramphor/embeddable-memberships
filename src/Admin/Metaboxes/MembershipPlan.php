<?php
namespace Ramphor\Memberships\Admin\Metaboxes;

use Ramphor\Memberships\Memberships;
use Ramphor\Memberships\Abstracts\MetaboxTab;
use Ramphor\Memberships\Admin\Metaboxes\Tabs\General;

class MembershipPlan
{
    protected $memebershipId;
    protected $tabInstances = array();

    public function __construct($memebership)
    {
        if (!is_a($memebership, Memberships::class)) {
            return;
        }
        $this->membershipId = $memebership->getId();

        add_action('init', array($this, 'createMetaboxTabInstances'), 30);
        add_action('add_meta_boxes', array($this, 'register_meta_boxes'));
        add_action('save_post', array($this, 'saveMembershipMetaDatas'), 10, 2);
    }

    public function createMetaboxTabInstances()
    {
        $tabs = apply_filters("{$this->memebershipId}_metabox_tabs", array(
            General::TAB_NAME => General::class,
        ));

        foreach ($tabs as $tab) {
            if (!class_exists($tab)) {
                error_log(sprintf(
                    __('The tab "%s" class is not found', 'ramphor_memberships'),
                    $tab
                ));
                continue;
            }
            $tabInstance = new $tab();
            if (!is_a($tabInstance, MetaboxTab::class)) {
                error_log(sprintf(
                    __('The tab "%s" is skipped', 'ramphor_memberships'),
                    $tab
                ));
                continue;
            }
            array_push($this->tabInstances, $tabInstance);
        }
    }

    public function register_meta_boxes($post_type)
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

    public function render($post)
    {
        ?>
            <div class="panel-wrap data">
                <ul class="membership_plan_data_tabs membership-tabs">
                <?php foreach ($this->tabInstances as $tabInstance) :
                    $tab_icon = $tabInstance->get_icon();
                    $tab_name = esc_attr($tabInstance->get_name());
                    ?>
                    <li class="<?php echo $tab_name; ?>_options <?php echo $tab_name; ?>_tab">
                        <a href="#membership-plan-data-<?php echo $tab_name; ?>">
                        <?php if ($tab_icon) : ?>
                            <span class="tab-icon"><?php echo $tab_icon; ?></span>
                        <?php endif; ?>
                            <span><?php echo $tabInstance->get_title(); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
                </ul>

                <div class="ramphor-memberships-panels">
                <?php foreach ($this->tabInstances as $tabInstance) :
                    $tab_name = esc_attr($tabInstance->get_name());
                    ?>
                    <div id="membership-plan-data-<?php echo $tab_name; ?>" class="panel ramphor-memberships-panel">
                        <?php $tabInstance->render($post); ?>
                    </div><!-- //#membership-plan-data-<?php echo $tab_name; ?> -->
                <?php endforeach; ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php
    }

    public function saveMembershipMetaDatas($post_id, $post)
    {
        if ($post->post_type !== "{$this->membershipId}_membership_plan") {
            return;
        }
        foreach ($this->tabInstances as $tabInstance) {
            $tabInstance->save($post_id, $post);
        }
    }
}
