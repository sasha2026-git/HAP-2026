<?php
/**
 * Login / registration form for the HireAIPeople account page.
 *
 * This is a presentation override of WooCommerce core. All nonce fields,
 * input names, submit names, and action hooks are intentionally preserved.
 *
 * @package HireAIPeople
 * @version 9.9.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$is_en = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en';
$registration_enabled = 'yes' === get_option('woocommerce_enable_myaccount_registration');

do_action('woocommerce_before_customer_login_form');
?>

<div class="hireai-account hireai-account--auth">
<?php if ($registration_enabled) : ?>
<div class="hireai-account-auth u-columns col2-set" id="customer_login">
    <div class="hireai-account-auth__column u-column1 col-1">
<?php else : ?>
<div class="hireai-account-auth hireai-account-auth--single">
    <div class="hireai-account-auth__column">
<?php endif; ?>

        <h2><?php echo esc_html($is_en ? 'Login' : '登录'); ?></h2>

        <form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

            <?php do_action('woocommerce_login_form_start'); ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="username"><?php echo esc_html($is_en ? 'Username or email address' : '用户名或邮箱'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php echo esc_html($is_en ? 'Required' : '必填'); ?></span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username']) && is_string($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password"><?php echo esc_html($is_en ? 'Password' : '密码'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php echo esc_html($is_en ? 'Required' : '必填'); ?></span></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
            </p>

            <?php do_action('woocommerce_login_form'); ?>

            <p class="form-row hireai-account-auth__actions">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php echo esc_html($is_en ? 'Remember me' : '记住我'); ?></span>
                </label>
                <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                <button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="login" value="<?php echo esc_attr($is_en ? 'Log in' : '登录'); ?>"><?php echo esc_html($is_en ? 'Log in' : '登录'); ?></button>
            </p>
            <p class="woocommerce-LostPassword lost_password">
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php echo esc_html($is_en ? 'Lost your password?' : '忘记密码？'); ?></a>
            </p>

            <?php do_action('woocommerce_login_form_end'); ?>

        </form>

<?php if ($registration_enabled) : ?>
    </div>

    <div class="hireai-account-auth__column u-column2 col-2">

        <h2><?php echo esc_html($is_en ? 'Register' : '注册'); ?></h2>

        <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

            <?php do_action('woocommerce_register_form_start'); ?>

            <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_username"><?php echo esc_html($is_en ? 'Username' : '用户名'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php echo esc_html($is_en ? 'Required' : '必填'); ?></span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
                </p>

            <?php endif; ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_email"><?php echo esc_html($is_en ? 'Email address' : '邮箱地址'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php echo esc_html($is_en ? 'Required' : '必填'); ?></span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
            </p>

            <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_password"><?php echo esc_html($is_en ? 'Password' : '密码'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php echo esc_html($is_en ? 'Required' : '必填'); ?></span></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
                </p>

            <?php else : ?>

                <p class="hireai-account-auth__hint"><?php echo esc_html($is_en ? 'A link to set a new password will be sent to your email address.' : '设置新密码的链接将发送到您的邮箱。'); ?></p>

            <?php endif; ?>

            <?php do_action('woocommerce_register_form'); ?>

            <p class="woocommerce-form-row form-row">
                <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                <button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit" name="register" value="<?php echo esc_attr($is_en ? 'Register' : '注册'); ?>"><?php echo esc_html($is_en ? 'Register' : '注册'); ?></button>
            </p>

            <?php do_action('woocommerce_register_form_end'); ?>

        </form>

    </div>
</div>
<?php else : ?>
    </div>
</div>
<?php endif; ?>
</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
