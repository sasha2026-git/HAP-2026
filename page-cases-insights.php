<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 案例&洞察
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
    [
        'title'   => ['zh' => '算法驱动的会员分层', 'en' => 'Algorithmic Member Segmentation'],
        'tag'     => ['zh' => '精选案例', 'en' => 'FEATURED CASE'],
        'excerpt' => ['zh' => '以数字员工实时识别高价值客户，让每一次触达更精准、更体面。', 'en' => 'Digital employees identify high-value clients in real time for more precise, more gracious outreach.'],
        'link'    => home_url('/category/cases/'),
    ],
    [
        'title'   => ['zh' => '多时区全球客服中枢', 'en' => 'Cross-Border Customer Service Nexus'],
        'tag'     => ['zh' => '精选案例', 'en' => 'FEATURED CASE'],
        'excerpt' => ['zh' => '统一多语言、多平台客服响应，将等待时间压缩到秒级。', 'en' => 'Unifies multilingual, multi-platform service responses and compresses wait time to seconds.'],
        'link'    => home_url('/category/cases/'),
    ],
    [
        'title'   => ['zh' => '品牌内容生产矩阵', 'en' => 'Brand Content Production Matrix'],
        'tag'     => ['zh' => '精选案例', 'en' => 'FEATURED CASE'],
        'excerpt' => ['zh' => '让数字员工承担高频内容生产，把团队时间还给策略与创意。', 'en' => 'Digital employees own high-volume production so human teams can focus on strategy and creativity.'],
        'link'    => home_url('/category/cases/'),
    ],
];

$cases_total = max((int) $cases_query->found_posts, count($fallback_cases));

$fallback_insights = [
    [
        'title'   => ['zh' => 'AI 雇佣时代的组织设计', 'en' => 'Organizational Design for the AI Hiring Era'],
        'excerpt' => ['zh' => '数字员工不是工具，而是组织能力的新单元。', 'en' => 'Digital employees are not tools; they are a new unit of organizational capability.'],
        'link'    => home_url('/category/insights/'),
    ],
    [
        'title'   => ['zh' => '从模型到灵魂：数字员工的体验层', 'en' => 'From Model to Soul: The Experience Layer'],
        'excerpt' => ['zh' => '奢华 AI 产品的差异，在于可感知的人格与协作体验。', 'en' => 'Luxury AI products are defined by a perceptible personality and collaborative presence.'],
        'link'    => home_url('/category/insights/'),
    ],
    [
        'title'   => ['zh' => '隐形计算的审美', 'en' => 'The Aesthetic of Invisible Computing'],
        'excerpt' => ['zh' => '当界面消失，品牌与智能之间只剩最安静、最直接的协作。', 'en' => 'When the interface disappears, brand and intelligence meet through quiet, direct collaboration.'],
        'link'    => home_url('/category/insights/'),
    ],
];

$get_localized = function ($item, $key) use ($suffix) {
    $value = isset($item[$key]) ? $item[$key] : '';
    if (is_array($value)) {
        $lang_key = ($suffix === '_en') ? 'en' : 'zh';
        return isset($value[$lang_key]) ? $value[$lang_key] : '';
    }
    return $value;
};
?>
<header class="page-hero page-hero--left" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('hero_kicker', $suffix === '_en' ? 'Cases & Insights' : '案例与洞察')); ?></span>
	<h1 class="display-lg page-hero__title page-hero__title--display"><?php echo esc_html(hireai_field('hero_title', $suffix === '_en' ? 'The Ledger of Innovation' : '案例与洞察')); ?></h1>
	<?php if (hireai_field('hero_subtitle')) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('hero_subtitle')); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="section-head__row">
			<?php
			get_template_part('template-parts/section-header', null, [
				'align'    => 'left',
				'kicker'   => hireai_field('cases_kicker', '案例'),
				'title'    => hireai_field('cases_title', '精选案例'),
				'subtitle' => hireai_field('cases_subtitle'),
			]);
			?>
			<span class="section-count"><?php echo esc_html(sprintf('%02d', $cases_total)); ?> <?php echo esc_html($suffix === '_en' ? 'ENTRIES' : '条案例'); ?></span>
		</div>

		<?php if ($cases_query->have_posts()) : ?>
			<div class="grid grid--3 case-card-grid">
				<?php
				while ($cases_query->have_posts()) {
					$cases_query->the_post();
					get_template_part('template-parts/post-card', null, [
						'cta_text' => $card_cta,
						'variant'  => 'case',
					]);
				}
				wp_reset_postdata();
				?>
			</div>
			<?php hireai_pagination($cases_query->max_num_pages, $paged); ?>
		<?php else : ?>
			<div class="grid grid--3 case-card-grid">
				<?php
				foreach ($fallback_cases as $item) {
					get_template_part('template-parts/fallback-post-card', null, [
						'title'    => $get_localized($item, 'title'),
						'tag'      => $get_localized($item, 'tag'),
						'excerpt'  => $get_localized($item, 'excerpt'),
						'link'     => $get_localized($item, 'link'),
						'cta_text' => $card_cta,
						'variant'  => 'case',
					]);
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

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
			<span class="section-count"><?php echo esc_html($suffix === '_en' ? 'LATEST ARTICLES' : '最新文章'); ?></span>
		</div>

		<div class="insight-list">
			<?php if ($insights_query->have_posts()) : ?>
				<?php
				while ($insights_query->have_posts()) {
					$insights_query->the_post();
					get_template_part('template-parts/insight-row', null, ['cta_text' => $card_cta]);
				}
				wp_reset_postdata();
				?>
			<?php else : ?>
				<?php
				foreach ($fallback_insights as $item) {
					get_template_part('template-parts/fallback-insight-row', null, [
						'title'    => $get_localized($item, 'title'),
						'excerpt'  => $get_localized($item, 'excerpt'),
						'link'     => $get_localized($item, 'link'),
						'date'     => $suffix === '_en' ? 'AUGUST 2026' : '2026 · 08',
						'cat'      => $suffix === '_en' ? 'INSIGHT' : '洞察',
						'cta_text' => $card_cta,
					]);
				}
				?>
			<?php endif; ?>
		</div>

		<div class="insight-list__cta">
			<a class="btn btn-ghost" href="<?php echo esc_url($insights_cta['url']); ?>"><?php echo esc_html($insights_cta['title']); ?></a>
		</div>
	</div>
</section>

<?php get_footer(); ?>
