<?php if (!defined('ABSPATH')) exit;
/**
 * 首页 AI 解决方案卡 — 带头图（与数字员工卡同构的"头像卡片"）
 *
 * WC 路径：get_template_part('template-parts/solution-card', null, ['index' => N, 'cta_text' => '...'])
 * 兜底路径：['fallback' => true, 'title'=>..., 'text'=>..., 'link'=>..., 'image'=>..., 'icon'=>..., 'cta_text'=>...]
 */
$args = isset($args) ? $args : [];
$is_fallback = !empty($args['fallback']);
$index       = isset($args['index']) ? (int) $args['index'] : 0;

if ($is_fallback) {
	$title     = isset($args['title']) ? $args['title'] : '';
	$text      = isset($args['text']) ? $args['text'] : '';
	$link      = isset($args['link']) ? $args['link'] : '#';
	$image     = isset($args['image']) ? $args['image'] : '';
	$icon      = isset($args['icon']) ? $args['icon'] : 'arrow';
	$cat_label = '';
	$cta_text  = isset($args['cta_text']) ? $args['cta_text'] : '';
} else {
	global $product;
	if (!$product || $product->get_id() !== get_the_ID()) {
		$product = wc_get_product(get_the_ID());
	}
	$cat_names = $product ? wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']) : [];
	$cat_label = (!is_wp_error($cat_names) && !empty($cat_names)) ? $cat_names[0] : '';
	$title     = get_the_title();
	$link      = get_permalink();
	$text      = has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content()), 24);
	$icon      = isset($args['icon']) ? $args['icon'] : 'arrow';
	$cta_text  = isset($args['cta_text']) ? $args['cta_text'] : '';
	$featured  = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'hireai-wide') : '';
	$image     = $featured ? $featured : hireai_default_image('solution-' . ($index + 1) . '.jpg');
}

if ($cta_text === '') {
	$cta_text = 'Explore More';
}
?>
<article class="solution-card" data-reveal>
	<?php if ($image) : ?>
		<a class="solution-card__media" href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true" style="background-image:url('<?php echo esc_url($image); ?>')"></a>
	<?php endif; ?>
	<div class="solution-card__body">
		<span class="solution-card__icon" aria-hidden="true"><?php echo hireai_svg($icon, 20); ?></span>
		<?php if ($cat_label !== '') : ?>
			<span class="label solution-card__cat"><?php echo esc_html($cat_label); ?></span>
		<?php endif; ?>
		<h3 class="solution-card__title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
		<p class="solution-card__excerpt"><?php echo esc_html($text); ?></p>
		<a class="text-link solution-card__cta" href="<?php echo esc_url($link); ?>"><?php echo esc_html($cta_text); ?> <?php echo hireai_svg('arrow', 14); ?></a>
	</div>
</article>
