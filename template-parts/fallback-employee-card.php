<?php if (!defined('ABSPATH')) exit;
$title = isset($args['title']) ? $args['title'] : '';
$role  = isset($args['role']) ? $args['role'] : '';
$image = isset($args['image']) ? $args['image'] : '';
$link  = isset($args['link']) ? $args['link'] : home_url('/');
?>
<article class="employee-card" data-reveal>
	<a class="employee-card__media" href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true"<?php if ($image !== '') : ?> style="background-image:url('<?php echo esc_url($image); ?>')"<?php endif; ?>></a>
	<div class="employee-card__body">
		<?php if ($role !== '') : ?><span class="label employee-card__role"><?php echo esc_html($role); ?></span><?php endif; ?>
		<h3 class="employee-card__name"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
		<div class="employee-card__line" aria-hidden="true"></div>
	</div>
</article>
