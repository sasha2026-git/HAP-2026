<?php if (!defined('ABSPATH')) exit;
$title  = isset($args['title']) ? $args['title'] : '';
$role   = isset($args['role']) ? $args['role'] : '';
$intro  = isset($args['intro']) ? $args['intro'] : '';
$tags   = isset($args['tags']) && is_array($args['tags']) ? $args['tags'] : [];
$link   = isset($args['link']) ? $args['link'] : home_url('/');
$cta    = isset($args['cta_text']) ? $args['cta_text'] : '探索更多';
$image  = isset($args['image']) ? $args['image'] : '';
$reverse = isset($args['reverse']) ? (bool) $args['reverse'] : false;
?>
<article class="employee-row<?php echo $reverse ? ' employee-row--reverse' : ''; ?>" data-reveal>
	<a class="employee-row__media" href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true"<?php if ($image !== '') : ?> style="background-image:url('<?php echo esc_url($image); ?>')"<?php endif; ?>></a>
	<div class="employee-row__content">
		<div class="employee-row__role"><?php echo esc_html($role); ?></div>
		<h2 class="employee-row__name"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h2>
		<p class="employee-row__intro"><?php echo esc_html($intro); ?></p>
		<?php if ($tags) : ?>
			<div class="employee-tags">
				<?php foreach ($tags as $tag) : ?><span class="employee-tag"><?php echo esc_html($tag); ?></span><?php endforeach; ?>
			</div>
		<?php endif; ?>
		<a class="btn btn-ghost" href="<?php echo esc_url($link); ?>"><?php echo esc_html($cta); ?> <?php echo hireai_svg('arrow', 14); ?></a>
	</div>
</article>
