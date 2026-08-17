<?php if (!defined('ABSPATH')) exit;
/**
 * 通用归档：页头 + 三列文章网格 + 分页。
 */
get_header();
$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';
$paged  = max(1, get_query_var('paged'));
?>
<header class="archive-header">
	<div class="container">
		<span class="label archive-header__kicker"><?php echo esc_html($is_en ? 'Archive' : '归档'); ?></span>
		<h1 class="archive-header__title"><?php the_archive_title(); ?></h1>
		<?php the_archive_description('<div class="archive-header__description">', '</div>'); ?>
	</div>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<?php if (have_posts()) : ?>
			<div class="post-grid">
				<?php while (have_posts()) : the_post(); ?>
					<?php get_template_part('template-parts/post-card'); ?>
				<?php endwhile; ?>
			</div>
			<?php hireai_pagination($GLOBALS['wp_query']->max_num_pages, $paged); ?>
		<?php else : ?>
			<p class="faq-empty"><?php echo esc_html($is_en ? 'No posts found.' : '暂无内容。'); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
