<?php
if (!defined('ABSPATH')) exit;
/**
 * AI 数字员工列表页（category=ai-employee，每页 5 个，左右交替 + 分页）
 */
get_header();

$suffix = hireai_lang_suffix();
$paged  = max(1, get_query_var('paged'));

$query = new WP_Query([
    'post_type'      => 'post',
    'category_name'  => 'ai-employee',
    'posts_per_page' => HIREAI_EMPLOYEES_PER_PAGE,
    'paged'          => $paged,
]);
?>
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', 'AI 数字员工')); ?></span>
	<h1 class="headline-lg page-hero__title"><?php echo esc_html(hireai_field('header_title', '数字员工名录')); ?></h1>
	<?php if (hireai_field('header_subtitle')) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('header_subtitle')); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<?php if ($query->have_posts()) : ?>
			<?php
			while ($query->have_posts()) {
				$query->the_post();
				get_template_part('template-parts/employee-row', null, [
					'cta_text' => hireai_field('card_cta_text', $suffix === '_en' ? 'Explore More' : '探索更多'),
				]);
			}
			wp_reset_postdata();
			?>
			<?php hireai_pagination($query->max_num_pages, $paged); ?>
		<?php else : ?>
			<p style="text-align:center;color:var(--color-text-muted);padding:80px 0;">
				<?php echo esc_html($suffix === '_en' ? 'No AI employees published yet.' : '暂无数字员工，敬请期待。'); ?>
			</p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
