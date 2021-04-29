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
        $post_id = $this->post->ID;
        $expirations = array(
            'unlimited' => __('unlimited', 'ramphor_memberships'),
            'specific' => __('specific length', 'ramphor_memberships'),
            'fixed_dates' => __('fixed dates', 'ramphor_memberships'),
        );
        $current_expiration = get_post_meta($post_id, '_membership_expiration', true);
        if (!$current_expiration) {
            $current_expiration = array_key_first($expirations);
        }

        $membership_expiration_length = get_post_meta($post_id, '_membership_expiration_length', true);
        $expiration_length_types = array(
            'day' => __('Days', 'ramphor_memberships'),
            'month' => __('Months', 'ramphor_memberships'),
            'year' => __('Years', 'ramphor_memberships')
        );
        $current_expiration_length_type = get_post_meta(
            $post_id,
            '_expiration_length_type',
            true
        );

        $membership_fixed_start_date = get_post_meta($post_id, '_membership_fixed_start_date', true);
        if (!$membership_fixed_start_date) {
            $membership_fixed_start_date = date('Y/m/d');
        }
        $membership_fixed_end_date = get_post_meta($post_id, '_membership_fixed_end_date', true);
        if (!$membership_fixed_end_date) {
            $membership_fixed_end_date = date('Y/m/d', strtotime("+1 day"));
        }
        ?>
        <div>
            <label for=""><?php echo esc_html(__('Membership Expiration', 'ramphor_memberships')); ?></label>
            <span>
            <?php foreach ($expirations as $expiration => $label) : ?>
                <label>
                    <input
                        id="membership-expiration-<?php echo esc_attr($expiration); ?>"
                        type="radio"
                        name="membership_expiration"
                        value="<?php echo esc_attr($expiration); ?>"
                        <?php checked($expiration, $current_expiration); ?>
                    />
                    <?php echo $label; ?>
                </label>
            <?php endforeach; ?>
            </span>

            <div class="expiration-option specific-length" data-require="membership_expiration" data-require-value="specific">
                <div class="length-inputs">
                    <input
                        type="text"
                        name="membership_expiration_length"
                        value="<?php echo $membership_expiration_length ?>"
                    />
                    <select name="membership_expiration_length_type">
                        <?php foreach ($expiration_length_types as $expiration_length_type => $label) : ?>
                        <option value="<?php echo $expiration_length_type; ?>"<?php selected($expiration_length_type, $current_expiration_length_type); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="expiration-option fixed-dates" data-require="membership_expiration" data-require-value="fixed_dates">
                <div class="start-date">
                    <label for=""><?php echo esc_html(__('Start date', 'ramphor_memberships')); ?></label>
                    <input
                        class="v-datepicker"
                        type="text"
                        name="membership_fixed_start_date"
                        value="<?php echo esc_attr($membership_fixed_start_date); ?>"
                    />
                    <span class="date-format">
                        <code>YYYY-MM-DD</code>
                    </span>
                </div>

                <input
                    type="hidden"
                />

                <div class="end-date">
                    <label for=""><?php echo esc_html(__('End date', 'ramphor_memberships')); ?></label>
                    <input
                        class="v-datepicker"
                        type="text"
                        name="membership_fixed_end_date"
                        value="<?php echo esc_attr($membership_fixed_end_date); ?>"
                    />
                    <span class="date-format">
                        <code>YYYY-MM-DD</code>
                    </span>
                </div>
            </div>
        </div>
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

        if (isset($_POST['membership_expiration'])) {
            update_post_meta($post_id, '_membership_expiration', $_POST['membership_expiration']);
        }

        if (isset($_POST['membership_expiration_length'])) {
            update_post_meta($post_id, '_membership_expiration_length', $_POST['membership_expiration_length']);
        }
        if (isset($_POST['membership_expiration_length_type'])) {
            update_post_meta($post_id, '_expiration_length_type', $_POST['membership_expiration_length_type']);
        }
        if (isset($_POST['membership_fixed_start_date'])) {
            update_post_meta($post_id, '_membership_fixed_start_date', $_POST['membership_fixed_start_date']);
        }
        if (isset($_POST['membership_fixed_end_date'])) {
            update_post_meta($post_id, '_membership_fixed_end_date', $_POST['membership_fixed_end_date']);
        }
    }
}
