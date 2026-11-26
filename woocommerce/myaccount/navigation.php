<?php
/**
 * My Account navigation — HireAIPeople presentation override.
 *
 * Endpoint discovery, ordering, classes and current-page state all stay with
 * WooCommerce. Only labels are mapped bilingually so the two physical pages read
 * naturally in EN / ZH.
 *
 * @package HireAIPeople
 * @version 9.3.0
 */

if (!defined('ABSPATH')) {
	exit;
}

$is_en = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en';

$hireai_account_labels = [
	'dashboard'       => $is_en ? 'Dashboard' : '仪表盘',
	'orders'          => $is_en ? 'Orders' : '订单',
	'downloads'       => $is_en ? 'Downloads' : '下载',
	'edit-address'    => $is_en ? 'Addresses' : '地址',
	'payment-methods' => $is_en ? 'Payment methods' : '支付方式',
	'edit-account'    => $is_en ? 'Account details' : '账户详情',
	'customer-logout' => $is_en ? 'Log out' : '退出',
];

do_action('woocommerce_before_account_navigation');
?>

<nav class="woocommerce-MyAccount-navigation hireai-account__nav" aria-label="<?php echo esc_attr($is_en ? 'Account pages' : '账户页面'); ?>">
	<ul>
		<?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
			<li class="<?php echo esc_attr(wc_get_account_menu_item_classes($endpoint)); ?>">
				<a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" <?php echo wc_is_current_account_menu_item($endpoint) ? 'aria-current="page"' : ''; ?>>
					<?php echo esc_html(isset($hireai_account_labels[$endpoint]) ? $hireai_account_labels[$endpoint] : $label); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action('woocommerce_after_account_navigation'); ?>
