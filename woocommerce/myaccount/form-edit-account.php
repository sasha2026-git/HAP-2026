<?php
/**
 * Edit account form — HireAIPeople presentation override.
 *
 * Every input name, nonce, hidden action and submit name is unchanged. Labels
 * and helper copy are the only theme-owned pieces.
 *
 * @package HireAIPeople
 * @version 11.0.0
 */

defined('ABSPATH') || exit;

$is_en = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en';
$t = static function ($en, $zh) use ($is_en) {
	return $is_en ? $en : $zh;
};

/**
 * Hook - woocommerce_before_edit_account_form.
 *
 * @since 2.6.0
 */
do_action('woocommerce_before_edit_account_form');
?>

<div class="hireai-account__section hireai-account__form-wrap">
	<form class="woocommerce-EditAccountForm edit-account hireai-account__form" action="" method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?> >

		<?php do_action('woocommerce_edit_account_form_start'); ?>

		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
			<label for="account_first_name"><?php echo esc_html($t('First name', '名')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr($user->first_name); ?>" aria-required="true" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
			<label for="account_last_name"><?php echo esc_html($t('Last name', '姓')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr($user->last_name); ?>" aria-required="true" />
		</p>
		<div class="clear"></div>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="account_display_name"><?php echo esc_html($t('Display name', '显示名称')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" aria-describedby="account_display_name_description" value="<?php echo esc_attr($user->display_name); ?>" aria-required="true" />
			<span id="account_display_name_description"><em><?php echo wc_reviews_enabled() ? esc_html($t('This will be how your name will be displayed in the account section and in reviews', '此名称将显示在账户区域及商品评价中')) : esc_html($t('This will be how your name will be displayed in the account section', '此名称将显示在账户区域')); ?></em></span>
		</p>
		<div class="clear"></div>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="account_email"><?php echo esc_html($t('Email address', '邮箱地址')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
			<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr($user->user_email); ?>" aria-required="true" />
		</p>

		<?php
		/**
		 * Hook where additional fields should be rendered.
		 *
		 * @since 8.7.0
		 */
		do_action('woocommerce_edit_account_form_fields');
		?>

		<fieldset>
			<legend><?php echo esc_html($t('Password change', '修改密码')); ?></legend>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_current"><?php echo esc_html($t('Current password (leave blank to leave unchanged)', '当前密码（留空则不修改）')); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="current-password" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_1"><?php echo esc_html($t('New password (leave blank to leave unchanged)', '新密码（留空则不修改）')); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="new-password" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_2"><?php echo esc_html($t('Confirm new password', '确认新密码')); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="new-password" />
			</p>
		</fieldset>
		<div class="clear"></div>

		<?php
		/**
		 * My Account edit account form.
		 *
		 * @since 2.6.0
		 */
		do_action('woocommerce_edit_account_form');
		?>

		<p class="hireai-account__actions">
			<?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
			<button type="submit" class="woocommerce-Button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="save_account_details" value="<?php echo esc_attr($t('Save changes', '保存修改')); ?>"><?php echo esc_html($t('Save changes', '保存修改')); ?></button>
			<input type="hidden" name="action" value="save_account_details" />
		</p>

		<?php do_action('woocommerce_edit_account_form_end'); ?>
	</form>
</div>

<?php do_action('woocommerce_after_edit_account_form'); ?>
