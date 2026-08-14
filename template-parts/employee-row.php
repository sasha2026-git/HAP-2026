<?php if (!defined('ABSPATH')) exit;
$cta_text = isset($args['cta_text']) ? $args['cta_text'] : '探索更多';
$reverse  = isset($args['reverse']) ? (bool) $args['reverse'] : false;
$suffix   = hireai_lang_suffix();
$alternate = !empty($args['alternate']);
$role     = site_field('employee_role' . $suffix, '', get_the_ID());
$caps_raw = site_field('employee_capabilities' . $suffix, '', get_the_ID());
$tags = [];
if ($caps_raw !== '') {
    foreach (array_slice(array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $caps_raw)))), 0, 3) as $line) {
        if ($line !== '') $tags[] = $line;
    }
}
$image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'hireai-wide') : hireai_default_image('employee-1.jpg');
$cats = get_the_category();
$cat_name = !empty($cats) ? $cats[0]->name : '';
$role_label = $role !== '' ? $role : $cat_name;
?>
<article class="employee-row<?php echo $reverse ? ' employee-row--reverse' : ''; ?>" data-reveal>
	<a class="employee-row__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"<?php if ($image) : ?> style="background-image:url('<?php echo esc_url($image); ?>')"<?php endif; ?>></a>
	<div class="employee-row__content">
		<div class="employee-row__role"><?php echo esc_html($role_label); ?></div>
		<h2 class="employee-row__name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="employee-row__intro"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 34)); ?></p>
		<?php if ($tags) : ?>
			<div class="employee-tags">
				<?php foreach ($tags as $tag) : ?><span class="employee-tag"><?php echo esc_html($tag); ?></span><?php endforeach; ?>
			</div>
		<?php endif; ?>
		<a class="btn btn-ghost" href="<?php the_permalink(); ?>"><?php echo esc_html($cta_text); ?> <?php echo hireai_svg('arrow', 14); ?></a>
	</div>
</article>
