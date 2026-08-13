<?php
if (!defined('ABSPATH')) exit;
/**
 * 文章卡（案例 / 洞察 / 博客归档）
 * 用法：get_template_part('template-parts/post-card', null, ['cta_text' => '阅读更多', 'show_meta' => true]);
 */
$cta_text  = isset($args['cta_text']) ? $args['cta_text'] : '阅读更多';
$show_meta = isset($args['show_meta']) ? (bool) $args['show_meta'] : true;

$cats     = get_the_category();
$cat_name = !empty($cats) ? $cats[0]->name : '';
?>
<article class="card post-card" data-reveal>
	<a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if (has_post_thumbnail()) : ?>
			<?php the_post_thumbnail('hireai-card'); ?>
		<?php else : ?>
			<span class="media-placeholder">HireAI People</span>
		<?php endif; ?>
	</a>
	<div class="card__body">
		<?php if ($show_meta) : ?>
			<div class="card__meta">
				<?php
				echo esc_html($cat_name);
				if ($cat_name !== '') {
					echo ' · ';
				}
				echo esc_html(get_the_date('Y.m.d'));
				?>
			</div>
		<?php endif; ?>
		<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
		<a class="btn btn-ghost" href="<?php the_permalink(); ?>"><?php echo esc_html($cta_text); ?></a>
	</div>
</article>
