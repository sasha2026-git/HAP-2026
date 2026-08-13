<?php
if (!defined('ABSPATH')) exit;
/**
 * 案例 & 洞察页：6 个案例（可分页）+ 3 个洞察（固定最新）
 */
get_header();

$suffix = hireai_lang_suffix();
$paged  = max(1, get_query_var('paged'));

$cases_query = new WP_Query([
    'post_type'      => 'post',
    'category_name'  => 'cases',
    'posts_per_page' => HIREAI_CASES_PER_PAGE,
    'paged'          => $paged,
]);

$insights_query = new WP_Query([
    'post_type'      => 'post',
    'category_name'  => 'insights',
    'posts_per_page' => HIREAI_INSIGHTS_PER_PAGE,
    'no_found_rows'  => true,
]);

$cases_cta = hireai_link('cases_cta', '/category/cases/', $suffix === '_en' ? 'All Cases' : '查看全部案例');
$insights_cta = hireai_link('insights_cta', '/category/insights/', $suffix === '_en' ? 'More Insights' : '更多洞察');
$card_cta = hireai_field('card_cta_text', $suffix === '_en' ? 'Read More' : '阅读更多');
?>
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('hero_kicker', $suffix === '_en' ? 'Cases & Insights' : '案例与洞察')); ?></span>
	<h1 class="headline-lg page-hero__title"><?php echo esc_html(hireai_field('hero_title', $suffix === '_en' ? 'Cases & Insights' : '案例与洞察')); ?></h1>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<?php if ($cases_query->have_posts()) : ?>
			<div class="section-head__row">
				<?php
				get_template_part('template-parts/section-header', null, [
					'align'    => 'left',
					'kicker'   => hireai_field('cases_kicker', '案例'),
					'title'    => hireai_field('cases_title', '精选案例'),
					'subtitle' => hireai_field('cases_subtitle'),
				]);
				?>
				<a class="btn btn-ghost" href="<?php echo esc_url($cases_cta['url']); ?>"><?php echo esc_html($cases_cta['title']); ?></a>
			</div>
			<div class="grid grid--3">
				<?php
				while ($cases_query->have_posts()) {
					$cases_query->the_post();
					get_template_part('template-parts/post-card', null, ['cta_text' => $card_cta]);
				}
				wp_reset_postdata();
				?>
			</div>
			<?php hireai_pagination($cases_query->max_num_pages, $paged); ?>
		<?php else : ?>
			<p style="text-align:center;color:var(--color-text-muted);padding:48px 0;">
				<?php echo esc_html($suffix === '_en' ? 'No cases published yet.' : '暂无案例，敬请期待。'); ?>
			</p>
		<?php endif; ?>
	</div>
</section>

<?php if ($insights_query->have_posts()) : ?>
	<section class="section" style="padding-top:0;">
		<div class="container">
			<div class="section-head__row">
				<?php
				get_template_part('template-parts/section-header', null, [
					'align'    => 'left',
					'kicker'   => hireai_field('insights_kicker', '洞察'),
					'title'    => hireai_field('insights_title', '前沿洞察'),
					'subtitle' => hireai_field('insights_subtitle'),
				]);
				?>
				<a class="btn btn-ghost" href="<?php echo esc_url($insights_cta['url']); ?>"><?php echo esc_html($insights_cta['title']); ?></a>
			</div>
			<div class="grid grid--3">
				<?php
				while ($insights_query->have_posts()) {
					$insights_query->the_post();
					get_template_part('template-parts/post-card', null, ['cta_text' => $card_cta]);
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
