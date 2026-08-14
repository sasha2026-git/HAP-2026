<?php if (!defined('ABSPATH')) exit;
/**
 * 洞察归档（category=insights）— 洞察行
 */
get_header();

$suffix = hireai_lang_suffix();
$paged  = max(1, get_query_var('paged'));

$card_cta = $suffix === '_en' ? 'Read More' : '阅读更多';
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
$localize = function ($item, $key) use ($suffix) {
    $value = isset($item[$key]) ? $item[$key] : '';
    if (is_array($value)) {
        return isset($value[$suffix === '_en' ? 'en' : 'zh']) ? $value[$suffix === '_en' ? 'en' : 'zh'] : '';
    }
    return $value;
};
?>
<header class="page-hero page-hero--left" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html($suffix === '_en' ? 'Insights' : '洞察'); ?></span>
	<h1 class="display-lg page-hero__title page-hero__title--display"><?php single_cat_title(); ?></h1>
	<?php if (category_description()) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(strip_tags(category_description())); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="insight-list">
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php
					get_template_part('template-parts/insight-row', null, [
						'cta_text' => $suffix === '_en' ? 'Read More' : '阅读更多',
					]);
					?>
				<?php endwhile; ?>
				<?php hireai_pagination($GLOBALS['wp_query']->max_num_pages, $paged); ?>
			<?php else : ?>
				<?php
				foreach ($fallback_insights as $item) {
					get_template_part('template-parts/fallback-insight-row', null, [
						'title'    => $localize($item, 'title'),
						'excerpt'  => $localize($item, 'excerpt'),
						'link'     => $localize($item, 'link'),
						'date'     => $suffix === '_en' ? 'AUGUST 2026' : '2026 · 08',
						'cat'      => $suffix === '_en' ? 'INSIGHT' : '洞察',
						'cta_text' => $card_cta,
					]);
				}
				?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
