<?php if (!defined('ABSPATH')) exit;
/**
 * 数字员工交替行（列表页）— 参考 ai_aether_ai_light_luxe_1
 * 用法：get_template_part('template-parts/employee-row', null, ['cta_text' => '探索更多']);
 */
$cta_text = isset($args['cta_text']) ? $args['cta_text'] : '探索更多';
$suffix   = hireai_lang_suffix();
$role     = site_field('employee_role' . $suffix, '', get_the_ID());
$caps_raw = site_field('employee_capabilities' . $suffix, '', get_the_ID());
$cats     = get_the_category();
$cat_name = !empty($cats) ? $cats[0]->name : '';
$tags     = [];
if ($caps_raw !== '') {
    $lines = preg_split('/\r\n|\r|\n/', $caps_raw);
    foreach (array_slice(array_values(array_filter(array_map('trim', $lines))), 0, 3) as $line) {
        if ($line !== '') {
            $tags[] = $line;
        }
    }
}
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
		<?php if ($role !== '') : ?>
			<div class="employee-row__role"><?php echo esc_html($role); ?></div>
		<?php elseif ($cat_name !== '') : ?>
			<div class="employee-row__role"><?php echo esc_html($cat_name); ?></div>
		<?php endif; ?>
		<h2 class="employee-row__name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="employee-row__intro"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 34)); ?></p>
		<?php if ($tags) : ?>
			<div class="employee-tags">
				<?php foreach ($tags as $tag) : ?>
					<span class="employee-tag"><?php echo esc_html($tag); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<a class="btn btn-ghost" href="<?php the_permalink(); ?>"><?php echo esc_html($cta_text); ?></a>
	</div>
</article>
