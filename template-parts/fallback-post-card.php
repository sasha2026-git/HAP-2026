<?php if (!defined('ABSPATH')) exit;
/**
 * 无内容时的默认文章卡（案例/洞察）
 * 用法：get_template_part('template-parts/fallback-post-card', null, [...]);
 */
$title   = isset($args['title']) ? $args['title'] : '';
$excerpt = isset($args['excerpt']) ? $args['excerpt'] : '';
$tag     = isset($args['tag']) ? $args['tag'] : '';
$link    = isset($args['link']) ? $args['link'] : home_url('/');
$date    = isset($args['date']) ? $args['date'] : '';
$cta     = isset($args['cta_text']) ? $args['cta_text'] : '阅读更多';
$variant = isset($args['variant']) ? $args['variant'] : '';
?>
<article class="card post-card<?php echo $variant ? ' post-card--' . esc_attr($variant) : ''; ?>" data-reveal>
	<a class="card__media" href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true">
		<span class="media-placeholder">HireAI People</span>
	</a>
	<div class="card__body">
		<div>
			<?php if ($tag !== '') : ?>
				<div class="card__meta"><?php echo esc_html($tag); ?><?php echo $date !== '' ? ' · ' . esc_html($date) : ''; ?></div>
			<?php elseif ($date !== '') : ?>
				<div class="card__meta"><?php echo esc_html($date); ?></div>
			<?php endif; ?>
			<h3 class="card__title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
			<p class="card__excerpt"><?php echo esc_html($excerpt); ?></p>
		</div>
		<a class="btn btn-ghost" href="<?php echo esc_url($link); ?>"><?php echo esc_html($cta); ?></a>
	</div>
</article>
