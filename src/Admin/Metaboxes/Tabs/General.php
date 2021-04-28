<?php
namespace Ramphor\Memberships\Admin\Metaboxes\Tabs;

use Ramphor\Memberships\Abstracts\MetaboxTab;

class General extends MetaboxTab
{
    const TAB_NAME = 'general';

    protected $post;

    public function get_name()
    {
        return static::TAB_NAME;
    }

    public function get_title()
    {
        return __('General', 'ramphor_memberships');
    }

    public function get_icon()
    {
        return '<span class="dashicons dashicons-admin-tools"></span>';
    }

    protected function renderSlugEdit()
    {
        $post = $this->post;
        ?>
        <div class="options_group">
            <p class="form-field post_name_field ">
                <label for="post_name"><?php echo __('Slug'); ?></label>
                <input
                    id="post_name"
                    type="text"
                    class="short"
                    name="post_name"
                    value="<?php echo $post->post_name; ?>"
                />
            </p>
        </div>
        <?php
    }

    protected function grantPlanAccess()
    {
        $access_methods = apply_filters("{$this->workspace}_general_access_methods", array(
            'manual-only' => __('manual assignment only', 'ramphor_memberships'),
            'signup' => __('user account registration', 'ramphor_memberships')
        ));
        $current_method = get_post_meta($this->post->ID, '_access_method', true);
        if (!$current_method) {
            $current_method = array_key_first($access_methods);
        }

        ?>
        <div class="options_group">
            <p class="form-field plan-access-method-field">
                <label for="_access_method">Grant access upon</label>
                <span class="plan-access-method-selectors">
                    <?php foreach ($access_methods as $access_method => $label) : ?>
                    <label class="label-radio">
                        <input
                            type="radio"
                            name="_access_method"
                            value="<?php echo esc_attr($access_method); ?>"
                            <?php checked($access_method, $current_method); ?>
                        >
                        <?php echo esc_html($label); ?>
                    </label>
                    <?php endforeach; ?>
                </span>
            </p>
        </div>
        <?php
    }

    public function membershipExpireDate()
    {
        $expirations = array(
            'unlimited' => __('unlimited', 'ramphor_memberships'),
            'specific' => __('specific length', 'ramphor_memberships'),
            'fixed_dates' => __('fixed dates', 'ramphor_memberships'),
        );
        ?>
        <p>
            <label for="">Membership Expiration</label>
            <span>
            <?php foreach($expirations as $expiration => $label): ?>
                <label>
                    <input type="radio">
                    unlimited
                </label>
            <?php endforeach; ?>
            </span>
        </p>
        <?php
    }

    public function renderAfterGeneralTabContent()
    {
        do_action(
            "{$this->workspace}_membership_plan_after_general_tab_content",
            $this->post
        );
    }

    public function render($post)
    {
        $this->post = $post;

        $this->renderSlugEdit();
        $this->grantPlanAccess();
        $this->membershipExpireDate();
        $this->renderAfterGeneralTabContent();
    }

    public function save($post_id, $post)
    {
        if (isset($_POST['_access_method'])) {
            update_post_meta($post_id, '_access_method', $_POST['_access_method']);
        }
    }
}
