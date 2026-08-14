<?php if (!defined('ABSPATH')) exit;
/**
 * 案例归档（category=cases）— 案例卡网格
 */
get_header();

$suffix = hireai_lang_suffix();
$paged  = max(1, get_query_var('paged'));

$card_cta = $suffix === '_en' ? 'Read Case' : '阅读案例';
$fallback_cases = [
    [
        'title'   => ['zh' => '奢侈品牌中国区内容焕新', 'en' => 'Luxury Brand China Content Refresh'],
        'tag'     => ['zh' => '精选案例', 'en' => 'FEATURED CASE'],
        'excerpt' => ['zh' => '以数字员工重建内容矩阵，让发布效率与品牌质感同步提升。', 'en' => 'Digital employees rebuild the content matrix while preserving brand polish.'],
        'link'    => home_url('/category/cases/'),
    ],
    [
        'title'   => ['zh' => '跨境电商 24×7 客服', 'en' => 'Cross-Border Commerce 24×7 Service'],
        'tag'     => ['zh' => '精选案例', 'en' => 'FEATURED CASE'],
        'excerpt' => ['zh' => '数字员工覆盖多时区客服，把等待变成即时响应。', 'en' => 'Digital employees cover every timezone and turn waiting into immediate response.'],
        'link'    => home_url('/category/cases/'),
    ],
    [
        'title'   => ['zh' => '高净值品牌私域增长', 'en' => 'Private Growth for a High-Net-Worth Brand'],
        'tag'     => ['zh' => '精选案例', 'en' => 'FEATURED CASE'],
        'excerpt' => ['zh' => '将私域内容与销售线索联动，形成可复用的增长闭环。', 'en' => 'Links private-domain content with sales signals into a reusable growth loop.'],
        'link'    => home_url('/category/cases/'),
    ],
];
$localize = function ($item, $key) use ($suffix) {
    $value = isset($item[$key]) ? $item[$key] : '';
    if (is_array($value)) {
        return isset($value[$suffix === '_en' ? 'en' : 'zh']) ? $value[$suffix === '_en' ? 'en' : 'zh'] : '';
    }
    return $value;
};
?>
<header class="page-hero page-hero--left" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html($suffix === '_en' ? 'Cases' : '案例'); ?></span>
	<h1 class="display-lg page-hero__title page-hero__title--display"><?php single_cat_title(); ?></h1>
	<?php if (category_description()) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(strip_tags(category_description())); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<?php if (have_posts()) : ?>
			<div class="grid grid--3 case-card-grid">
				<?php while (have_posts()) : the_post(); ?>
					<?php
					get_template_part('template-parts/post-card', null, [
						'cta_text' => $suffix === '_en' ? 'Read Case' : '阅读案例',
						'variant'  => 'case',
					]);
					?>
				<?php endwhile; ?>
			</div>
			<?php hireai_pagination($GLOBALS['wp_query']->max_num_pages, $paged); ?>
		<?php else : ?>
			<div class="grid grid--3 case-card-grid">
				<?php
				foreach ($fallback_cases as $item) {
					get_template_part('template-parts/fallback-post-card', null, [
						'title'    => $localize($item, 'title'),
						'tag'      => $localize($item, 'tag'),
						'excerpt'  => $localize($item, 'excerpt'),
						'link'     => $localize($item, 'link'),
						'cta_text' => $card_cta,
						'variant'  => 'case',
					]);
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
