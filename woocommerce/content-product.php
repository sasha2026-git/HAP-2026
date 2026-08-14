<?php if (!defined('ABSPATH')) exit;
/**
 * 商品卡片（奢华风格）— 参考 ai_aether_ai_light_luxe_2
 * 可由 wc_get_template_part 传入 $cta_text 覆盖按钮文字
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
$short_desc  = $product->get_short_description();
$cats_attr   = (!is_wp_error($cat_slugs)) ? implode(' ', $cat_slugs) : '';
$operative   = hireai_field('product_operative', '', $product_id);
$retainer    = hireai_field('product_retainer_label', $suffix === '_en' ? 'Starting Retainer' : '起步档', $product_id);
if ($operative === '') {
    $operative = $suffix === '_en' ? 'OPERATIVE: HIREAI' : '执行智能体：聘AI';
}
?>
<article class="product-card" data-cats="<?php echo esc_attr($cats_attr); ?>">
	<a class="product-card__media" href="<?php echo esc_url(get_permalink()); ?>" tabindex="-1" aria-hidden="true">
		<?php if (has_post_thumbnail()) : ?>
			<?php the_post_thumbnail('hireai-card'); ?>
		<?php else : ?>
			<span class="media-placeholder">HireAI People</span>
		<?php endif; ?>
		<?php if ($product->is_on_sale()) : ?>
			<span class="product-card__badge product-card__badge--sale"><?php echo esc_html($suffix === '_en' ? 'Sale' : '促销'); ?></span>
		<?php endif; ?>
		<?php if ($cat_label !== '') : ?>
			<span class="product-card__badge product-card__badge--category"><?php echo esc_html($cat_label); ?></span>
		<?php endif; ?>
	</a>
	<div class="product-card__body">
		<h3 class="product-card__title">
			<a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>
		</h3>
		<div class="product-card__operative"><?php echo esc_html($operative); ?></div>
		<?php if ($short_desc !== '') : ?>
			<p class="product-card__excerpt"><?php echo esc_html(wp_strip_all_tags($short_desc)); ?></p>
		<?php endif; ?>
		<div class="product-card__footer">
			<div class="product-card__retainer">
				<span><?php echo esc_html($retainer); ?></span>
				<strong class="product-card__price"><?php echo wp_kses_post($price_html); ?></strong>
			</div>
			<a class="product-card__arrow" href="<?php echo esc_url(get_permalink()); ?>" aria-label="<?php echo esc_attr($cta_text); ?>">
				<span aria-hidden="true">→</span>
			</a>
		</div>
	</div>
</article>
