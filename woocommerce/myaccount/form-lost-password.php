<?php
/**
 * Lost password form (theme override of WooCommerce core).
 *
 * Nonce action, input names, hidden fields and hooks mirror core
 * myaccount/form-lost-password.php so WC_Form_Handler keeps working.
 *
 * @package HireAIPeople
 * @version 9.2.0
 */

defined('ABSPATH') || exit;

$is_en = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en';
$account_url = function_exists('hireai_account_page_url') ? hireai_account_page_url() : wc_get_page_permalink('myaccount');

do_action('woocommerce_before_lost_password_form');
?>

<div class="hireai-account hireai-account--auth">
    <div class="hireai-account-auth hireai-account-auth--single">
        <div class="hireai-account-auth__column">

            <h2><?php echo esc_html($is_en ? 'Reset your password' : '找回密码'); ?></h2>

            <form method="post" class="woocommerce-ResetPassword lost_reset_password">

                <p class="hireai-account-auth__hint"><?php echo apply_filters('woocommerce_lost_password_message', esc_html($is_en ? 'Enter your email address and we will send you a link to create a new password.' : '请输入邮箱地址，我们会发送一封包含重置密码链接的邮件。')); ?><?php // @codingStandardsIgnoreLine ?></p>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="user_login"><?php echo esc_html($is_en ? 'Email address' : '邮箱地址'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php echo esc_html($is_en ? 'Required' : '必填'); ?></span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" spellcheck="false" autocapitalize="none" required aria-required="true" />
                </p>

                <?php do_action('woocommerce_lostpassword_form'); ?>

                <p class="woocommerce-form-row form-row">
                    <input type="hidden" name="wc_reset_password" value="true" />
                    <button type="submit" class="woocommerce-Button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" value="<?php echo esc_attr($is_en ? 'Reset password' : '重置密码'); ?>"><?php echo esc_html($is_en ? 'Reset password' : '重置密码'); ?></button>
                </p>

                <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

            </form>

            <p class="hireai-account-auth__switch"><a href="<?php echo esc_url($account_url); ?>"><?php echo esc_html($is_en ? 'Back to log in' : '返回登录'); ?></a></p>

        </div>
    </div>
</div>

<?php
do_action('woocommerce_after_lost_password_form');
