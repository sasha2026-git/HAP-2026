<?php if (!defined('ABSPATH')) exit;
$cta_text = isset($args['cta_text']) ? $args['cta_text'] : '阅读更多';
$show_meta = isset($args['show_meta']) ? (bool) $args['show_meta'] : true;
$image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'hireai-card') : hireai_default_image('case-1.jpg');
$cats = get_the_category();
$cat_name = !empty($cats) ? $cats[0]->name : '';
?>
<article class="post-card" data-reveal>
	<a class="post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"<?php if ($image) : ?> style="background-image:url('<?php echo esc_url($image); ?>')"<?php endif; ?>></a>
	<div class="post-card__body">
		<div>
			<?php if ($show_meta) : ?>
				<div class="post-card__meta">
					<?php echo esc_html($cat_name); if ($cat_name !== '') { echo ' · '; } echo esc_html(get_the_date('Y.m.d')); ?>
				</div>
			<?php endif; ?>
			<h3 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<p class="post-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
		</div>
		<a class="btn btn-ghost post-card__cta" href="<?php the_permalink(); ?>"><?php echo esc_html($cta_text); ?> <?php echo hireai_svg('arrow', 14); ?></a>
	</div>
</article>
