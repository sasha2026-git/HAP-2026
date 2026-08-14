<?php if (!defined('ABSPATH')) exit;
/**
 * 通用归档（作者 / 日期 / 标签）
 */
get_header();

$suffix = hireai_lang_suffix();
$paged  = max(1, get_query_var('paged'));
?>
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html($suffix === '_en' ? 'Archive' : '归档'); ?></span>
	<h1 class="headline-lg page-hero__title"><?php the_archive_title(); ?></h1>
	<?php the_archive_description('<p class="body-lg page-hero__subtitle">', '</p>'); ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<?php if (have_posts()) : ?>
			<div class="grid grid--3">
				<?php while (have_posts()) : the_post(); ?>
					<?php
					get_template_part('template-parts/post-card', null, [
						'cta_text' => $suffix === '_en' ? 'Read More' : '阅读更多',
					]);
					?>
				<?php endwhile; ?>
			</div>
			<?php hireai_pagination($GLOBALS['wp_query']->max_num_pages, $paged); ?>
		<?php else : ?>
			<p style="text-align:center;color:var(--color-text-muted);padding:80px 0;">
				<?php echo esc_html($suffix === '_en' ? 'Nothing found here yet.' : '这里暂时没有内容。'); ?>
			</p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
