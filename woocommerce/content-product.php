<?php
if (!defined('ABSPATH')) exit;
/**
 * 商品卡片（奢华风格）— 可由 wc_get_template_part 传入 $cta_text 覆盖按钮文字
 */
global $product;

if (!$product || !$product->is_visible()) {
    return;
}

$suffix = hireai_lang_suffix();
$cta_text = (isset($cta_text) && $cta_text !== '') ? $cta_text : ($suffix === '_en' ? 'Explore More' : '探索更多');

$product_id  = $product->get_id();
$cat_slugs   = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'slugs']);
$cat_names   = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'names']);
$cat_label   = (!is_wp_error($cat_names) && !empty($cat_names)) ? $cat_names[0] : '';
$price_html  = $product->get_price_html();
$cats_attr   = (!is_wp_error($cat_slugs)) ? implode(' ', $cat_slugs) : '';
?>
<article class="product-card" data-cats="<?php echo esc_attr($cats_attr); ?>">
	<a class="product-card__media" href="<?php echo esc_url(get_permalink()); ?>" tabindex="-1" aria-hidden="true">
		<?php if (has_post_thumbnail()) : ?>
			<?php the_post_thumbnail('hireai-card'); ?>
		<?php else : ?>
			<span class="media-placeholder">HireAI People</span>
		<?php endif; ?>
		<?php if ($product->is_on_sale()) : ?>
			<span class="product-card__badge"><?php echo esc_html($suffix === '_en' ? 'Sale' : '促销'); ?></span>
		<?php endif; ?>
	</a>
	<div class="product-card__body">
		<?php if ($cat_label !== '') : ?>
			<span class="label product-card__cat"><?php echo esc_html($cat_label); ?></span>
		<?php endif; ?>
		<h3 class="product-card__title">
			<a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>
		</h3>
		<?php if ($price_html !== '') : ?>
			<div class="product-card__price"><?php echo wp_kses_post($price_html); ?></div>
		<?php endif; ?>
		<a class="btn btn-ghost" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html($cta_text); ?></a>
	</div>
</article>
