<?php if (!defined('ABSPATH')) exit;
$title   = isset($args['title']) ? $args['title'] : '';
$excerpt = isset($args['excerpt']) ? $args['excerpt'] : '';
$tag     = isset($args['tag']) ? $args['tag'] : '';
$link    = isset($args['link']) ? $args['link'] : home_url('/');
$date    = isset($args['date']) ? $args['date'] : '';
$cta     = isset($args['cta_text']) ? $args['cta_text'] : '阅读更多';
$image   = isset($args['image']) ? $args['image'] : '';
?>
<article class="post-card" data-reveal>
	<a class="post-card__media" href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true"<?php if ($image !== '') : ?> style="background-image:url('<?php echo esc_url($image); ?>')"<?php endif; ?>></a>
	<div class="post-card__body">
		<div>
			<?php if ($tag !== '') : ?><div class="post-card__meta"><?php echo esc_html($tag); ?><?php echo $date !== '' ? ' · ' . esc_html($date) : ''; ?></div><?php endif; ?>
			<h3 class="post-card__title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
			<p class="post-card__excerpt"><?php echo esc_html($excerpt); ?></p>
		</div>
		<a class="btn btn-ghost post-card__cta" href="<?php echo esc_url($link); ?>"><?php echo esc_html($cta); ?> <?php echo hireai_svg('arrow', 14); ?></a>
	</div>
</article>
