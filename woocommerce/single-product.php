<?php
if (!defined('ABSPATH')) exit;
/**
 * 单产品页（奢华风格）— 支持价格变体 product variations
 */
get_header();

$suffix = hireai_lang_suffix();

while (have_posts()) :
	the_post();

	global $product;
	if (!$product) {
		$product = wc_get_product(get_the_ID());
	}

	$cat_list = $product ? wc_get_product_category_list($product->get_id(), ' · ') : '';
	?>
	<div class="container">
		<div class="product-single">
			<div class="product-single__gallery" data-reveal>
				<?php woocommerce_show_product_images(); ?>
			</div>

			<div class="product-single__summary" data-reveal>
				<?php if ($cat_list) : ?>
					<span class="label product-single__cat"><?php echo wp_kses_post($cat_list); ?></span>
				<?php endif; ?>

				<h1 class="product-single__title"><?php the_title(); ?></h1>

				<?php woocommerce_template_single_price(); ?>

				<div class="product-single__excerpt">
					<?php the_excerpt(); ?>
				</div>

				<?php woocommerce_template_single_add_to_cart(); ?>

				<?php woocommerce_template_single_meta(); ?>
			</div>
		</div>
	</div>

	<div class="container">
		<?php
		woocommerce_related_products([
			'posts_per_page' => 3,
			'columns'        => 3,
			'orderby'        => 'rand',
		]);
		?>
	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
