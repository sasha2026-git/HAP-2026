<?php
/**
 * My Account page — HireAIPeople presentation override.
 *
 * Structure only. WooCommerce keeps ownership of both hook groups:
 *  - woocommerce_account_navigation -> myaccount/navigation.php
 *  - woocommerce_account_content    -> dashboard / orders / downloads / addresses / account
 *
 * @package HireAIPeople
 * @version 3.5.0
 */

defined('ABSPATH') || exit;
?>
<div class="hireai-account">
	<?php do_action('woocommerce_account_navigation'); ?>

	<div class="woocommerce-MyAccount-content hireai-account__content">
		<?php
			/**
			 * My Account content.
			 *
			 * @since 2.6.0
			 */
			do_action('woocommerce_account_content');
		?>
	</div>
</div>
