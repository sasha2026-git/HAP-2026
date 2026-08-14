<?php if (!defined('ABSPATH')) exit;
/**
 * 商店归档页（奢华风格）— 商店 / 商品分类 / 商品标签
 */
get_header();

$suffix = hireai_lang_suffix();
$paged  = max(1, get_query_var('paged'));

$cta_text = $suffix === '_en' ? 'Explore More' : '探索更多';
$fallback_products = [
    ['title' => ['zh' => '全域营销智囊', 'en' => 'Omnichannel Marketing Intelligence'], 'tag' => ['zh' => '营销', 'en' => 'MARKETING'], 'operative' => ['zh' => '执行智能体：ARIA-01', 'en' => 'OPERATIVE: ARIA-01'], 'excerpt' => ['zh' => '覆盖内容、投放与数据复盘的全链路营销智能体。', 'en' => 'A full-funnel marketing agent for content, media, and performance review.'], 'price' => ['zh' => '¥4,800 / 月起', 'en' => 'From ¥4,800/mo'], 'retainer' => ['zh' => '起步档', 'en' => 'Starting Retainer'], 'link' => ['zh' => home_url('/ai-solutions/'), 'en' => home_url('/ai-solutions/')]],
    ['title' => ['zh' => '电商转化引擎', 'en' => 'Commerce Conversion Engine'], 'tag' => ['zh' => '电商', 'en' => 'E-COMMERCE'], 'operative' => ['zh' => '执行智能体：QUANTUM-C', 'en' => 'OPERATIVE: QUANTUM-C'], 'excerpt' => ['zh' => '从选品、定价到客服，让增长从洞察到成交顺畅闭环。', 'en' => 'Connects selection, pricing, and service into a seamless growth loop.'], 'price' => ['zh' => '¥6,800 / 月起', 'en' => 'From ¥6,800/mo'], 'retainer' => ['zh' => '项目基准', 'en' => 'Project Base'], 'link' => ['zh' => home_url('/ai-solutions/'), 'en' => home_url('/ai-solutions/')]],
    ['title' => ['zh' => '奢品内容工坊', 'en' => 'Luxury Content Atelier'], 'tag' => ['zh' => '设计', 'en' => 'DESIGN'], 'operative' => ['zh' => '执行智能体：AURA-7', 'en' => 'OPERATIVE: AURA-7'], 'excerpt' => ['zh' => '为高净值品牌打造有艺术质感、有销售力的内容体系。', 'en' => 'Crafts artful, conversion-ready content systems for high-net-worth brands.'], 'price' => ['zh' => '¥8,800 / 月起', 'en' => 'From ¥8,800/mo'], 'retainer' => ['zh' => '按概念', 'en' => 'Per Concept'], 'link' => ['zh' => home_url('/ai-solutions/'), 'en' => home_url('/ai-solutions/')]],
    ['title' => ['zh' => '危机公关文案', 'en' => 'Crisis PR Copywriting'], 'tag' => ['zh' => '公关', 'en' => 'PUBLIC RELATIONS'], 'operative' => ['zh' => '执行智能体：ELARA-9', 'en' => 'OPERATIVE: ELARA-9'], 'excerpt' => ['zh' => '以毫秒级校准话术处理突发舆情，保护品牌叙事与市场信任。', 'en' => 'Immediate, highly calibrated messaging protocols designed to mitigate brand exposure and steer public narrative.'], 'price' => ['zh' => '¥15,000 / 月起', 'en' => 'From ¥15,000/mo'], 'retainer' => ['zh' => '年度授权', 'en' => 'Annual License'], 'link' => ['zh' => home_url('/ai-solutions/'), 'en' => home_url('/ai-solutions/')]],
];
$localize = function ($item, $key) use ($suffix) {
    $value = isset($item[$key]) ? $item[$key] : '';
    if (is_array($value)) {
        return isset($value[$suffix === '_en' ? 'en' : 'zh']) ? $value[$suffix === '_en' ? 'en' : 'zh'] : '';
    }
    return $value;
};
?>
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html($suffix === '_en' ? 'AI Solutions' : 'AI 解决方案'); ?></span>
	<h1 class="headline-lg page-hero__title"><?php woocommerce_page_title(); ?></h1>
	<?php if (is_product_category() || is_product_tag()) : ?>
		<?php $term_desc = term_description(); ?>
		<?php if ($term_desc) : ?>
			<p class="body-lg page-hero__subtitle"><?php echo esc_html(strip_tags($term_desc)); ?></p>
		<?php endif; ?>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<?php if (have_posts()) : ?>

			<div class="woocommerce-sorting" style="display:flex;flex-wrap:wrap;gap:24px;align-items:center;justify-content:space-between;margin-bottom:40px;">
				<?php woocommerce_result_count(); ?>
				<?php woocommerce_catalog_ordering(); ?>
			</div>

			<div class="hireai-product-grid">
				<?php
				while (have_posts()) :
					the_post();
					wc_get_template_part('content', 'product');
				endwhile;
				?>
			</div>

			<?php hireai_pagination($GLOBALS['wp_query']->max_num_pages, $paged); ?>

		<?php else : ?>
			<div class="hireai-product-grid">
				<?php foreach ($fallback_products as $index => $item) : ?>
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
						'image'     => hireai_default_image('solution-' . ($index + 1) . '.jpg'),
					]);
					?>
				<?php endforeach; ?>
			</div>
			<?php
			// 侧栏（默认商店小工具）
			do_action('woocommerce_sidebar');
			?>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
