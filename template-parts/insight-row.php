<?php if (!defined('ABSPATH')) exit;
/**
 * 洞察列表行（参考 aether_ai_light_luxe_2）
 * 用法：get_template_part('template-parts/insight-row', null, ['cta_text' => '阅读更多']);
 */
$cta_text = isset($args['cta_text']) ? $args['cta_text'] : '阅读更多';
$cats     = get_the_category();
$cat_name = !empty($cats) ? $cats[0]->name : '';
?>
<article class="insight-row" data-reveal>
	<div class="insight-row__meta">
		<span class="insight-row__date"><?php echo esc_html(strtoupper(get_the_date('F j, Y'))); ?></span>
		<?php if ($cat_name !== '') : ?>
			<span class="insight-row__cat"><?php echo esc_html($cat_name); ?></span>
		<?php endif; ?>
	</div>
	<div class="insight-row__body">
		<h3 class="insight-row__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="insight-row__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
		<a class="btn btn-ghost insight-row__cta" href="<?php the_permalink(); ?>"><?php echo esc_html($cta_text); ?></a>
	</div>
</article>
