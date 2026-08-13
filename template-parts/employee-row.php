<?php
if (!defined('ABSPATH')) exit;
/**
 * 数字员工交替行（列表页）
 * 用法：get_template_part('template-parts/employee-row', null, ['cta_text' => '探索更多']);
 */
$cta_text = isset($args['cta_text']) ? $args['cta_text'] : '探索更多';
$role     = site_field('employee_role' . hireai_lang_suffix(), '', get_the_ID());
$cats     = get_the_category();
$cat_name = !empty($cats) ? $cats[0]->name : '';
?>
<article class="employee-row" data-reveal>
	<div class="employee-row__media">
		<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php if (has_post_thumbnail()) : ?>
				<?php the_post_thumbnail('hireai-wide'); ?>
			<?php else : ?>
				<span class="media-placeholder">HireAI People</span>
			<?php endif; ?>
		</a>
	</div>
	<div class="employee-row__content">
		<?php if ($cat_name !== '') : ?>
			<span class="label employee-row__kicker"><?php echo esc_html($cat_name); ?></span>
		<?php endif; ?>
		<h2 class="employee-row__name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php if ($role !== '') : ?>
			<div class="employee-row__role"><?php echo esc_html($role); ?></div>
		<?php endif; ?>
		<p class="employee-row__intro"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 30)); ?></p>
		<a class="btn btn-ghost" href="<?php the_permalink(); ?>"><?php echo esc_html($cta_text); ?></a>
	</div>
</article>
