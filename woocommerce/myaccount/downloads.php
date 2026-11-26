<?php
/**
 * Downloads — HireAIPeople presentation override.
 *
 * Printable-download rendering and hooks stay with WooCommerce.
 *
 * @package HireAIPeople
 * @version 7.8.0
 */

if (!defined('ABSPATH')) {
	exit;
}

$is_en = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en';

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action('woocommerce_before_account_downloads', $has_downloads);

if ($has_downloads) : ?>

	<div class="hireai-account__section hireai-account__downloads">
		<?php do_action('woocommerce_before_available_downloads'); ?>

		<?php do_action('woocommerce_available_downloads', $downloads); ?>

		<?php do_action('woocommerce_after_available_downloads'); ?>
	</div>

<?php else : ?>

	<?php
	$wp_button_class = wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : '';
	$empty_text      = $is_en ? 'No downloads available yet.' : '暂无可下载内容。';
	$browse_text     = $is_en ? 'Browse products' : '去逛逛';
	wc_print_notice(
		esc_html($empty_text) . ' <a class="button wc-forward' . esc_attr($wp_button_class) . '" href="' . esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))) . '">' . esc_html($browse_text) . '</a>',
		'notice'
	);
	?>

<?php endif; ?>

<?php do_action('woocommerce_after_account_downloads', $has_downloads); ?>
