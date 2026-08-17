<?php if (!defined('ABSPATH')) exit;
/**
 * 无内容时的默认洞察行
 */
$title   = isset($args['title']) ? $args['title'] : '';
$excerpt = isset($args['excerpt']) ? $args['excerpt'] : '';
$date    = isset($args['date']) ? $args['date'] : '';
$link    = isset($args['link']) ? $args['link'] : home_url('/');
$cat     = isset($args['cat']) ? $args['cat'] : 'INSIGHT';
$cta     = isset($args['cta_text']) ? $args['cta_text'] : '阅读更多';
?>
<article class="insight-row" data-reveal>
	<div class="insight-row__meta">
		<span class="insight-row__date"><?php echo esc_html($date); ?></span>
		<span class="insight-row__cat"><?php echo esc_html($cat); ?></span>
	</div>
	<div class="insight-row__body">
		<h3 class="insight-row__title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
		<p class="insight-row__excerpt"><?php echo esc_html($excerpt); ?></p>
		<a class="btn btn-ghost insight-row__cta" href="<?php echo esc_url($link); ?>"><?php echo esc_html($cta); ?></a>
	</div>
</article>
