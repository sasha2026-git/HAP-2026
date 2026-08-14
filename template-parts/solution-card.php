<?php if (!defined('ABSPATH')) exit;
/**
 * 首页 AI 解决方案卡（无图白卡，参考 aether_ai_light_luxe_1）
 * 用法：get_template_part('template-parts/solution-card', null, ['cta_text' => '']);
 */
$cta_text = isset($args['cta_text']) ? $args['cta_text'] : '';
$icon     = isset($args['icon']) ? $args['icon'] : 'arrow';
global $product;
if (!$product) {
	$product = wc_get_product(get_the_ID());
}
$cat_names = $product ? wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']) : [];
$cat_label = (!is_wp_error($cat_names) && !empty($cat_names)) ? $cat_names[0] : '';
$excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content()), 24);
?>
<article class="solution-card" data-reveal>
	<span class="solution-card__icon" aria-hidden="true"><?php echo hireai_svg($icon, 24); ?></span>
	<?php if ($cat_label !== '') : ?>
		<span class="label solution-card__cat"><?php echo esc_html($cat_label); ?></span>
	<?php endif; ?>
	<h3 class="solution-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<p class="solution-card__excerpt"><?php echo esc_html($excerpt); ?></p>
	<?php if ($cta_text !== '') : ?>
		<a class="btn btn-ghost solution-card__cta" href="<?php the_permalink(); ?>"><?php echo esc_html($cta_text); ?></a>
	<?php endif; ?>
</article>
