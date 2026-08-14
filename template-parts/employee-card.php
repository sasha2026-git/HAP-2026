<?php if (!defined('ABSPATH')) exit;
/**
 * 首页数字员工卡（参考 aether_ai_light_luxe_1）
 * 用法：get_template_part('template-parts/employee-card', null, ['cta_text' => '']);
 */
$cta_text = isset($args['cta_text']) ? $args['cta_text'] : '';
$role     = site_field('employee_role' . hireai_lang_suffix(), '', get_the_ID());
$cats     = get_the_category();
$cat_name = !empty($cats) ? $cats[0]->name : '';
$role_label = $role !== '' ? $role : $cat_name;
?>
<article class="employee-card" data-reveal>
	<a class="employee-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if (has_post_thumbnail()) : ?>
			<?php the_post_thumbnail('hireai-card'); ?>
		<?php else : ?>
			<span class="media-placeholder">HireAI People</span>
		<?php endif; ?>
	</a>
	<div class="employee-card__body">
		<?php if ($role_label !== '') : ?>
			<span class="label employee-card__role"><?php echo esc_html($role_label); ?></span>
		<?php endif; ?>
		<h3 class="employee-card__name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ($cta_text !== '') : ?>
			<span class="employee-card__cta"><?php echo esc_html($cta_text); ?></span>
		<?php endif; ?>
	</div>
</article>
