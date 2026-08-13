<?php
if (!defined('ABSPATH')) exit;
/**
 * 案例归档（category=cases）— blog 形式
 */
get_header();

$suffix = hireai_lang_suffix();
$paged  = max(1, get_query_var('paged'));
?>
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html($suffix === '_en' ? 'Cases' : '案例'); ?></span>
	<h1 class="headline-lg page-hero__title"><?php single_cat_title(); ?></h1>
	<?php if (category_description()) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(strip_tags(category_description())); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="archive-list">
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php
					$cats = get_the_category();
					$cat_name = !empty($cats) ? $cats[0]->name : '';
					?>
					<article class="card post-card post-card--list" data-reveal>
						<a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('hireai-card'); ?>
							<?php else : ?>
								<span class="media-placeholder">HireAI People</span>
							<?php endif; ?>
						</a>
						<div class="card__body">
							<div class="card__meta">
								<?php echo esc_html($cat_name); ?> · <?php echo esc_html(get_the_date('Y.m.d')); ?>
							</div>
							<h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
							<a class="btn btn-ghost" href="<?php the_permalink(); ?>"><?php echo esc_html($suffix === '_en' ? 'Read More' : '阅读更多'); ?></a>
						</div>
					</article>
				<?php endwhile; ?>

				<?php hireai_pagination($GLOBALS['wp_query']->max_num_pages, $paged); ?>
			<?php else : ?>
				<p style="text-align:center;color:var(--color-text-muted);padding:80px 0;">
					<?php echo esc_html($suffix === '_en' ? 'No cases published yet.' : '暂无案例，敬请期待。'); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
