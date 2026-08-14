<?php if (!defined('ABSPATH')) exit;
/**
 * 无内容时的默认数字员工行
 * 用法：get_template_part('template-parts/fallback-employee-row', null, [...]);
 */
$title  = isset($args['title']) ? $args['title'] : '';
$role   = isset($args['role']) ? $args['role'] : '';
$intro  = isset($args['intro']) ? $args['intro'] : '';
$tags   = isset($args['tags']) && is_array($args['tags']) ? $args['tags'] : [];
$link   = isset($args['link']) ? $args['link'] : home_url('/');
$cta    = isset($args['cta_text']) ? $args['cta_text'] : '探索更多';
?>
<article class="employee-row" data-reveal>
	<div class="employee-row__media">
		<a href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true">
			<span class="media-placeholder">HireAI People</span>
		</a>
	</div>
	<div class="employee-row__content">
		<span class="label employee-row__kicker">AI EMPLOYEE</span>
		<h2 class="employee-row__name"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h2>
		<?php if ($role !== '') : ?>
			<div class="employee-row__role"><?php echo esc_html($role); ?></div>
		<?php endif; ?>
		<p class="employee-row__intro"><?php echo esc_html($intro); ?></p>
		<?php if ($tags) : ?>
			<div class="employee-tags">
				<?php foreach ($tags as $tag) : ?>
					<span class="employee-tag"><?php echo esc_html($tag); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<a class="btn btn-ghost" href="<?php echo esc_url($link); ?>"><?php echo esc_html($cta); ?></a>
	</div>
</article>
