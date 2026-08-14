<?php if (!defined('ABSPATH')) exit;
/**
 * 分类归档：category=ai-employee → 数字员工交替行；其他 → 文章列表
 */
get_header();

$suffix   = hireai_lang_suffix();
$category = get_queried_object();
$slug     = is_object($category) ? $category->slug : '';
$paged    = max(1, get_query_var('paged'));

$is_employee = ($slug === 'ai-employee');
$per_page = $is_employee ? HIREAI_EMPLOYEES_PER_PAGE : 9;
?>
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html($suffix === '_en' ? 'Category' : '分类'); ?></span>
	<h1 class="headline-lg page-hero__title"><?php single_cat_title(); ?></h1>
	<?php if (category_description()) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(strip_tags(category_description())); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<?php if (have_posts()) : ?>
			<?php if ($is_employee) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php
					get_template_part('template-parts/employee-row', null, [
						'cta_text' => $suffix === '_en' ? 'Explore More' : '探索更多',
					]);
					?>
				<?php endwhile; ?>
			<?php else : ?>
				<div class="grid grid--3">
					<?php while (have_posts()) : the_post(); ?>
						<?php
						get_template_part('template-parts/post-card', null, [
							'cta_text' => $suffix === '_en' ? 'Read More' : '阅读更多',
						]);
						?>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>

			<?php hireai_pagination($GLOBALS['wp_query']->max_num_pages, $paged); ?>
		<?php else : ?>
			<p style="text-align:center;color:var(--color-text-muted);padding:80px 0;">
				<?php echo esc_html($suffix === '_en' ? 'No posts in this category yet.' : '该分类下暂无内容。'); ?>
			</p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
