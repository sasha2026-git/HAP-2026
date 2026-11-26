<?php
/**
 * Edit address form — HireAIPeople presentation override.
 *
 * Address fields are still rendered by woocommerce_form_field(), so every field
 * name, required flag and validation rule remains WooCommerce-owned. Nonce, the
 * hidden action and the submit name are unchanged.
 *
 * @package HireAIPeople
 * @version 9.3.0
 */

defined('ABSPATH') || exit;

$is_en = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en';

if ('billing' === $load_address) {
	$page_title = $is_en ? 'Billing address' : '账单地址';
} else {
	$page_title = $is_en ? 'Shipping address' : '收货地址';
}

do_action('woocommerce_before_edit_account_address_form');

if (!$load_address) : ?>

	<?php wc_get_template('myaccount/my-address.php'); ?>

<?php else : ?>

	<div class="hireai-account__section hireai-account__form-wrap">
		<form method="post" novalidate class="hireai-account__form">

			<h2><?php echo apply_filters('woocommerce_my_account_edit_address_title', $page_title, $load_address); ?></h2><?php // @codingStandardsIgnoreLine ?>

			<div class="woocommerce-address-fields">
				<?php do_action("woocommerce_before_edit_address_form_{$load_address}"); ?>

				<div class="woocommerce-address-fields__field-wrapper">
					<?php
					foreach ($address as $key => $field) {
						woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $field['value']));
					}
					?>
				</div>

				<?php do_action("woocommerce_after_edit_address_form_{$load_address}"); ?>

				<p class="hireai-account__actions">
					<button type="submit" class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="save_address" value="<?php echo esc_attr($is_en ? 'Save address' : '保存地址'); ?>"><?php echo esc_html($is_en ? 'Save address' : '保存地址'); ?></button>
					<?php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce'); ?>
					<input type="hidden" name="action" value="edit_address" />
				</p>
			</div>

		</form>
	</div>

<?php endif; ?>

<?php do_action('woocommerce_after_edit_account_address_form'); ?>
