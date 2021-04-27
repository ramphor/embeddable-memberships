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
                <input id="post_name" type="text" class="short" name="post_name" value="<?php echo $post->post_name; ?>" />
            </p>
        </div>
        <?php
    }

    protected function grantPlanAccess()
    {
        ?>
        <div class="options_group">
            <p class="form-field plan-access-method-field">
                <label for="_access_method">Grant access upon</label>
                <span class="plan-access-method-selectors">
                    <label class="label-radio">
                        <input
                            type="radio"
                            name="_access_method"
                            class="js-access-method-selector js-access-method-type"
                            value="manual-only"
                            checked="checked"
                        >
                        manual assignment only
                    </label>
                    <label class="label-radio">
                        <input
                            type="radio"
                            name="_access_method"
                            class="js-access-method-selector js-access-method-type"
                            value="signup"
                        >
                        user account registration
                    </label>
                </span>
            </p>
        </div>
        <?php
    }

    protected function membershipExpireDate()
    {
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
}
