<?php
/**
 * My Account Dashboard — HireAIPeople presentation override.
 *
 * All WooCommerce dashboard hooks are preserved verbatim. Only copy is localized.
 *
 * @package HireAIPeople
 * @version 4.4.0
 */

defined('ABSPATH') || exit;

$is_en = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en';

$allowed_html = [
	'a' => [
		'href' => [],
	],
];

$orders_url  = esc_url(wc_get_endpoint_url('orders'));
$address_url = esc_url(wc_get_endpoint_url('edit-address'));
$account_url = esc_url(wc_get_endpoint_url('edit-account'));
$logout_url  = esc_url(wc_logout_url());

if ($is_en) :
	?>
	<p class="hireai-account__greeting">
		<?php
		printf(
			wp_kses(__('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce'), $allowed_html),
			'<strong>' . esc_html($current_user->display_name) . '</strong>',
			$logout_url
		);
		?>
	</p>

	<p class="hireai-account__intro">
		<?php
		printf(
			wp_kses(__('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce'), $allowed_html),
			$orders_url,
			$address_url,
			$account_url
		);
		?>
	</p>
	<?php
else :
	?>
	<p class="hireai-account__greeting">
		您好，<strong><?php echo esc_html($current_user->display_name); ?></strong>
		（不是 <?php echo esc_html($current_user->display_name); ?>？<a href="<?php echo $logout_url; ?>">退出登录</a>）
	</p>

	<p class="hireai-account__intro">
		在账户面板中，您可以查看<a href="<?php echo $orders_url; ?>">最近订单</a>、管理<a href="<?php echo $address_url; ?>">收货与账单地址</a>，以及<a href="<?php echo $account_url; ?>">修改密码和账户详情</a>。
	</p>
	<?php
endif;

/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_dashboard');

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');
