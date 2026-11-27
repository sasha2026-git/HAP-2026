<?php
/**
 * Reset password form (theme override of WooCommerce core).
 *
 * WC_Shortcode_My_Account::lost_password() only loads this template after
 * validating the reset key, so $args always carries key/login. Defaults are
 * kept to avoid notices if another integration includes it directly.
 *
 * @package HireAIPeople
 * @version 9.2.0
 */

defined('ABSPATH') || exit;

$is_en = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en';
$reset_key   = isset($args['key']) ? (string) $args['key'] : '';
$reset_login = isset($args['login']) ? (string) $args['login'] : '';

do_action('woocommerce_before_reset_password_form');
?>

<div class="hireai-account hireai-account--auth">
    <div class="hireai-account-auth hireai-account-auth--single">
        <div class="hireai-account-auth__column">

            <h2><?php echo esc_html($is_en ? 'Set a new password' : '设置新密码'); ?></h2>

            <form method="post" class="woocommerce-ResetPassword lost_reset_password">

                <p class="hireai-account-auth__hint"><?php echo apply_filters('woocommerce_reset_password_message', esc_html($is_en ? 'Choose a new password for your account.' : '请为您的账户设置一个新密码。')); ?><?php // @codingStandardsIgnoreLine ?></p>

                <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                    <label for="password_1"><?php echo esc_html($is_en ? 'New password' : '新密码'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php echo esc_html($is_en ? 'Required' : '必填'); ?></span></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" minlength="8" enterkeyhint="next" required aria-required="true" aria-describedby="password_1_hint" />
                    <span class="hireai-account-auth__field-hint" id="password_1_hint"><?php echo esc_html($is_en ? 'At least 8 characters.' : '至少 8 位。'); ?></span>
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                    <label for="password_2"><?php echo esc_html($is_en ? 'Confirm new password' : '确认新密码'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php echo esc_html($is_en ? 'Required' : '必填'); ?></span></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" minlength="8" enterkeyhint="done" required aria-required="true" />
                </p>

                <input type="hidden" name="reset_key" value="<?php echo esc_attr($reset_key); ?>" />
                <input type="hidden" name="reset_login" value="<?php echo esc_attr($reset_login); ?>" />

                <div class="clear"></div>

                <?php do_action('woocommerce_resetpassword_form'); ?>

                <p class="woocommerce-form-row form-row">
                    <input type="hidden" name="wc_reset_password" value="true" />
                    <button type="submit" class="woocommerce-Button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" value="<?php echo esc_attr($is_en ? 'Save password' : '保存新密码'); ?>"><?php echo esc_html($is_en ? 'Save password' : '保存新密码'); ?></button>
                </p>

                <?php wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce'); ?>

            </form>

        </div>
    </div>
</div>

<?php
do_action('woocommerce_after_reset_password_form');
