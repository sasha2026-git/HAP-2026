<?php if (!defined('ABSPATH')) exit;
$args = isset($args) ? $args : [];
$lg_only = !empty($args['lg_only']);
$suffix = hireai_lang_suffix();
$role = site_field('employee_role' . $suffix, '', get_the_ID());
$cats = get_the_category();
$cat_name = !empty($cats) ? $cats[0]->name : '';
$role_label = $role !== '' ? $role : $cat_name;
$image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'hireai-card') : hireai_default_image('employee-1.jpg');
?>
<article class="employee-card<?php echo $lg_only ? ' employee-card--lg-only' : ''; ?>" data-reveal>
	<a class="employee-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"<?php if ($image) : ?> style="background-image:url('<?php echo esc_url($image); ?>')"<?php endif; ?>></a>
	<div class="employee-card__body">
		<?php if ($role_label !== '') : ?><span class="label employee-card__role"><?php echo esc_html($role_label); ?></span><?php endif; ?>
		<h3 class="employee-card__name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<div class="employee-card__line" aria-hidden="true"></div>
	</div>
</article>
