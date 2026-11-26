<?php
/**
 * My Addresses — HireAIPeople presentation override.
 *
 * Address list and formatted output stay with WooCommerce.
 *
 * @package HireAIPeople
 * @version 9.3.0
 */

defined('ABSPATH') || exit;

$is_en = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en';

$customer_id = get_current_user_id();

if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		[
			'billing'  => $is_en ? 'Billing address' : '账单地址',
			'shipping' => $is_en ? 'Shipping address' : '收货地址',
		],
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		[
			'billing' => $is_en ? 'Billing address' : '账单地址',
		],
		$customer_id
	);
}

$oldcol = 1;
$col    = 1;

$formatted_addresses = [];
foreach (array_keys($get_addresses) as $name) {
	$formatted_addresses[$name] = wc_get_account_formatted_address($name);
}
$copyable_from = [
	'billing'  => 'shipping',
	'shipping' => 'billing',
];
?>

<div class="hireai-account__section hireai-account__addresses">
	<p class="hireai-account__intro">
		<?php echo apply_filters('woocommerce_my_account_my_address_description', esc_html($is_en ? 'The following addresses will be used on the checkout page by default.' : '以下地址将默认用于结算页面。')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</p>

	<?php if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) : ?>
		<div class="u-columns woocommerce-Addresses col2-set addresses hireai-account__address-grid">
	<?php endif; ?>

	<?php foreach ($get_addresses as $name => $address_title) : ?>
		<?php
			$address     = isset($formatted_addresses[$name]) ? $formatted_addresses[$name] : '';
			$copy_source = isset($copyable_from[$name]) ? $copyable_from[$name] : '';
			$can_copy    = !$address && $copy_source && !empty($formatted_addresses[$copy_source]);
			$col         = $col * -1;
			$oldcol      = $oldcol * -1;
		?>

		<div class="u-column<?php echo $col < 0 ? 1 : 2; ?> col-<?php echo $oldcol < 0 ? 1 : 2; ?> woocommerce-Address hireai-account__address-card">
			<header class="woocommerce-Address-title title">
				<h2><?php echo esc_html($address_title); ?></h2>
				<a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>" class="edit">
					<?php
						printf(
							/* translators: %s: Address title */
							$address ? esc_html($is_en ? 'Edit %s' : '编辑%s') : esc_html($is_en ? 'Add %s' : '添加%s'),
							esc_html($address_title)
						);
					?>
				</a>
			</header>
			<?php if ($can_copy) : ?>
				<p class="woocommerce-Address-copy">
					<a href="<?php echo esc_url(WC_Form_Handler::get_copy_address_url($name)); ?>" class="copy no-prefetch" rel="nofollow">
						<?php
							printf(
								/* translators: %s: Address title of the address being copied */
								esc_html($is_en ? 'Copy %s' : '复制%s'),
								esc_html($get_addresses[$copy_source])
							);
						?>
					</a>
				</p>
			<?php endif; ?>
			<address>
				<?php
					echo $address ? wp_kses_post($address) : esc_html($is_en ? 'You have not set up this type of address yet.' : '您尚未设置此类地址。');

					/**
					 * Used to output content after core address fields.
					 *
					 * @param string $name Address type.
					 * @since 8.7.0
					 */
					do_action('woocommerce_my_account_after_my_address', $name);
				?>
			</address>
		</div>

	<?php endforeach; ?>

	<?php if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) : ?>
		</div>
	<?php endif; ?>
</div>
