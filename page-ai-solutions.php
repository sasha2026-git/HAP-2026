<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - AI解决方案
 * 参考 ai_aether_ai_light_luxe_2：筛选条 + 3 列方案卡 + 分页；无内容时默认卡可见。
 */
get_header();

$suffix = hireai_lang_suffix();
$paged  = max(1, get_query_var('paged'));
$cta_text = hireai_field('card_cta_text', $suffix === '_en' ? 'Explore More' : '探索更多');

// 场景筛选配置：ACF repeater（可编辑），空则回退到四大场景
$filters = [];
if (function_exists('get_field')) {
    $filters_raw = get_field('solutions_filters');
    if (is_array($filters_raw)) {
        foreach ($filters_raw as $row) {
            if (!is_array($row)) {
                continue;
            }
            $label = ($suffix === '_en') ? (isset($row['filter_label_en']) ? $row['filter_label_en'] : '') : (isset($row['filter_label_zh']) ? $row['filter_label_zh'] : '');
            $slug  = isset($row['filter_slug']) ? $row['filter_slug'] : '';
            if ($slug !== '' && $label !== '') {
                $filters[] = ['label' => $label, 'slug' => $slug];
            }
        }
    }
}
if (empty($filters)) {
    $filters = ($suffix === '_en')
        ? [
            ['label' => 'Marketing', 'slug' => 'marketing'],
            ['label' => 'E-commerce', 'slug' => 'ecommerce'],
            ['label' => 'Design', 'slug' => 'design'],
            ['label' => 'Public Relations', 'slug' => 'pr'],
        ]
        : [
            ['label' => '营销', 'slug' => 'marketing'],
            ['label' => '电商', 'slug' => 'ecommerce'],
            ['label' => '设计', 'slug' => 'design'],
            ['label' => '公关', 'slug' => 'pr'],
        ];
}

$fallback = [
    ['title' => ['zh' => '全域营销智囊', 'en' => 'Omnichannel Marketing Intelligence'], 'tag' => ['zh' => '营销', 'en' => 'MARKETING'], 'operative' => ['zh' => '执行智能体：ARIA-01', 'en' => 'OPERATIVE: ARIA-01'], 'excerpt' => ['zh' => '覆盖内容、投放与数据复盘的全链路营销智能体。', 'en' => 'A full-funnel marketing agent for content, media, and performance review.'], 'price' => ['zh' => '¥4,800 / 月起', 'en' => 'From ¥4,800/mo'], 'retainer' => ['zh' => '起步档', 'en' => 'Starting Retainer'], 'link' => ['zh' => home_url('/ai-solutions/'), 'en' => home_url('/ai-solutions/')], 'cats' => ['zh' => 'marketing', 'en' => 'marketing']],
    ['title' => ['zh' => '电商转化引擎', 'en' => 'Commerce Conversion Engine'], 'tag' => ['zh' => '电商', 'en' => 'E-COMMERCE'], 'operative' => ['zh' => '执行智能体：QUANTUM-C', 'en' => 'OPERATIVE: QUANTUM-C'], 'excerpt' => ['zh' => '从选品、定价到客服，让增长从洞察到成交顺畅闭环。', 'en' => 'Connects selection, pricing, and service into a seamless growth loop.'], 'price' => ['zh' => '¥6,800 / 月起', 'en' => 'From ¥6,800/mo'], 'retainer' => ['zh' => '项目基准', 'en' => 'Project Base'], 'link' => ['zh' => home_url('/ai-solutions/'), 'en' => home_url('/ai-solutions/')], 'cats' => ['zh' => 'ecommerce', 'en' => 'ecommerce']],
    ['title' => ['zh' => '奢品内容工坊', 'en' => 'Luxury Content Atelier'], 'tag' => ['zh' => '设计', 'en' => 'DESIGN'], 'operative' => ['zh' => '执行智能体：AURA-7', 'en' => 'OPERATIVE: AURA-7'], 'excerpt' => ['zh' => '为高净值品牌打造有艺术质感、有销售力的内容体系。', 'en' => 'Crafts artful, conversion-ready content systems for high-net-worth brands.'], 'price' => ['zh' => '¥8,800 / 月起', 'en' => 'From ¥8,800/mo'], 'retainer' => ['zh' => '按概念', 'en' => 'Per Concept'], 'link' => ['zh' => home_url('/ai-solutions/'), 'en' => home_url('/ai-solutions/')], 'cats' => ['zh' => 'design', 'en' => 'design']],
    ['title' => ['zh' => '危机公关文案', 'en' => 'Crisis PR Copywriting'], 'tag' => ['zh' => '公关', 'en' => 'PUBLIC RELATIONS'], 'operative' => ['zh' => '执行智能体：ELARA-9', 'en' => 'OPERATIVE: ELARA-9'], 'excerpt' => ['zh' => '以毫秒级校准话术处理突发舆情，保护品牌叙事与市场信任。', 'en' => 'Immediate, highly calibrated messaging protocols designed to mitigate brand exposure and steer public narrative.'], 'price' => ['zh' => '¥15,000 / 月起', 'en' => 'From ¥15,000/mo'], 'retainer' => ['zh' => '年度授权', 'en' => 'Annual License'], 'link' => ['zh' => home_url('/ai-solutions/'), 'en' => home_url('/ai-solutions/')], 'cats' => ['zh' => 'pr', 'en' => 'pr']],
];
$localize = function ($item, $key) use ($suffix) {
    $value = isset($item[$key]) ? $item[$key] : '';
    if (is_array($value)) {
        return isset($value[$suffix === '_en' ? 'en' : 'zh']) ? $value[$suffix === '_en' ? 'en' : 'zh'] : '';
    }
    return $value;
};
$has_woo = class_exists('WooCommerce');
$query = $has_woo ? new WP_Query([
    'post_type'      => 'product',
    'posts_per_page' => HIREAI_SOLUTIONS_PER_PAGE,
    'paged'          => $paged,
]) : false;
?>
<header class="page-hero page-hero--left" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', 'AI 解决方案')); ?></span>
	<h1 class="display-lg page-hero__title"><?php echo esc_html(hireai_field('header_title', $suffix === '_en' ? 'Curated AI Solutions.' : '臻选智能方案。')); ?></h1>
	<?php if (hireai_field('header_subtitle')) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('header_subtitle')); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="solution-filter-bar" role="group" aria-label="<?php echo esc_attr($suffix === '_en' ? 'Filter solutions' : '筛选解决方案'); ?>">
			<button class="solution-filter is-active" type="button" data-filter="">
				<?php echo esc_html($suffix === '_en' ? 'All Solutions' : '全部方案'); ?>
			</button>
			<?php foreach ($filters as $f) : ?>
				<button class="solution-filter" type="button" data-filter="<?php echo esc_attr($f['slug']); ?>"><?php echo esc_html($f['label']); ?></button>
			<?php endforeach; ?>
		</div>

		<?php if ($query && $query->have_posts()) : ?>
			<div class="hireai-product-grid" id="solution-grid">
				<?php
				while ($query->have_posts()) {
					$query->the_post();
					wc_get_template_part('content', 'product', ['cta_text' => $cta_text]);
				}
				wp_reset_postdata();
				?>
			</div>
			<p class="solution-empty" data-solution-empty>
				<?php echo esc_html(hireai_field('empty_text', $suffix === '_en' ? 'No solutions in this category yet.' : '该分类下暂无解决方案')); ?>
			</p>
			<?php hireai_pagination($query->max_num_pages, $paged); ?>
		<?php else : ?>
			<div class="hireai-product-grid" id="solution-grid">
				<?php foreach ($fallback as $item) : ?>
					<?php
					get_template_part('template-parts/fallback-product-card', null, [
						'title'     => $localize($item, 'title'),
						'tag'       => $localize($item, 'tag'),
						'operative' => $localize($item, 'operative'),
						'excerpt'   => $localize($item, 'excerpt'),
						'price'     => $localize($item, 'price'),
						'retainer'  => $localize($item, 'retainer'),
						'link'      => $localize($item, 'link'),
						'cta_text'  => $cta_text,
						'cats'      => $localize($item, 'cats'),
					]);
					?>
				<?php endforeach; ?>
			</div>
			<p class="solution-empty" data-solution-empty>
				<?php echo esc_html(hireai_field('empty_text', $suffix === '_en' ? 'No solutions in this category yet.' : '该分类下暂无解决方案')); ?>
			</p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
