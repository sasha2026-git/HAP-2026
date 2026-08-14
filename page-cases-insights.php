<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 案例&洞察
 * 参考 aether_ai_light_luxe_2：案例三列卡 + 洞察列表 + 博客分页；空库时默认内容可见。
 */
get_header();

$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';
$paged  = max(1, get_query_var('paged'));
$cta_text = hireai_field('card_cta_text', $is_en ? 'Read More' : '阅读更多');

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

$fallback_cases = [
    ['title' => ['zh' => '奢侈品牌中国区内容焕新', 'en' => 'Luxury Brand China Content Refresh'], 'tag' => ['zh' => '精选案例 · 奢侈零售', 'en' => 'Case Study · Luxury Retail'], 'excerpt' => ['zh' => '以数字员工重建内容矩阵，让发布效率与品牌质感同步提升。', 'en' => 'Digital employees rebuild the content matrix while preserving brand polish.'], 'image' => 'case-1.jpg'],
    ['title' => ['zh' => '跨境电商 24×7 客服', 'en' => 'Cross-Border Commerce 24×7 Service'], 'tag' => ['zh' => '精选案例 · 电商', 'en' => 'Case Study · E-commerce'], 'excerpt' => ['zh' => '数字员工覆盖多时区客服，把等待变成即时响应。', 'en' => 'Digital employees cover every timezone and turn waiting into immediate response.'], 'image' => 'case-2.jpg'],
    ['title' => ['zh' => '高净值品牌私域增长', 'en' => 'Private Growth for a High-Net-Worth Brand'], 'tag' => ['zh' => '精选案例 · 私域', 'en' => 'Case Study · CRM'], 'excerpt' => ['zh' => '将私域内容与销售线索联动，形成可复用的增长闭环。', 'en' => 'Links private-domain content with sales signals into a reusable growth loop.'], 'image' => 'case-3.jpg'],
    ['title' => ['zh' => '智能风控与品牌安全', 'en' => 'Intelligent Risk and Brand Safety'], 'tag' => ['zh' => '精选案例 · 安全', 'en' => 'Case Study · Security'], 'excerpt' => ['zh' => '用实时模型识别风险信号，在影响扩散前完成响应。', 'en' => 'Real-time models identify risk signals and respond before impact spreads.'], 'image' => 'case-4.jpg'],
    ['title' => ['zh' => '数字员工零售导购', 'en' => 'Digital Employee Retail Styling'], 'tag' => ['zh' => '精选案例 · 零售', 'en' => 'Case Study · Retail'], 'excerpt' => ['zh' => '以个性化推荐重构高端零售的顾客旅程。', 'en' => 'Personalized recommendations reshape the high-end retail customer journey.'], 'image' => 'case-5.jpg'],
    ['title' => ['zh' => '合同智能审阅', 'en' => 'Automated Contract Analysis'], 'tag' => ['zh' => '精选案例 · 法务', 'en' => 'Case Study · Legal'], 'excerpt' => ['zh' => '让法务团队从重复文本审阅中抽身，专注策略判断。', 'en' => 'Legal teams step away from repetitive review and focus on strategy.'], 'image' => 'case-6.jpg'],
];

$fallback_insights = [
    ['title' => ['zh' => 'AI 雇佣时代的组织设计', 'en' => 'Organizational Design for the AI Hiring Era'], 'excerpt' => ['zh' => '数字员工不是工具，而是组织能力的新单元。', 'en' => 'Digital employees are not tools; they are a new unit of organizational capability.'], 'date' => $is_en ? 'August 14, 2026' : '2026.08.14'],
    ['title' => ['zh' => '从模型到灵魂：数字员工的体验层', 'en' => 'From Model to Soul: The Experience Layer'], 'excerpt' => ['zh' => '奢华 AI 产品的差异，在于可感知的人格与协作体验。', 'en' => 'Luxury AI products are defined by a perceptible personality and collaborative presence.'], 'date' => $is_en ? 'August 10, 2026' : '2026.08.10'],
    ['title' => ['zh' => 'AI 服务的透明化与信任', 'en' => 'Architecting Trust in Autonomous Systems'], 'excerpt' => ['zh' => '当算法承担关键决策，透明度就是组织信任的基础设施。', 'en' => 'When algorithms carry mission-critical decisions, transparency becomes the infrastructure of trust.'], 'date' => $is_en ? 'August 02, 2026' : '2026.08.02'],
];

$localize = function ($item, $key) use ($is_en) {
    $value = isset($item[$key]) ? $item[$key] : '';
    if (is_array($value)) {
        return isset($value[$is_en ? 'en' : 'zh']) ? $value[$is_en ? 'en' : 'zh'] : '';
    }
    return $value;
};
?>
<header class="page-hero">
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('hero_kicker', $is_en ? 'CASES & INSIGHTS' : '案例与洞察')); ?></span>
	<h1 class="display-lg page-hero__title"><?php echo esc_html(hireai_field('hero_title', $is_en ? 'Cases & Insights' : '案例与洞察')); ?></h1>
	<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('hero_subtitle', $is_en ? 'A curated gallery where intelligence meets human ingenuity.' : '见证数字员工如何改变企业的运营方式，洞察 AI 行业的深层趋势。')); ?></p>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="section-head section-head--split">
			<h2><?php echo esc_html(hireai_field('cases_title', $is_en ? 'Selected Cases' : '精选案例')); ?></h2>
			<span class="section-head__meta"><?php echo esc_html($is_en ? '06 ENTRIES' : '06 组案例'); ?></span>
		</div>

		<?php if ($cases_query->have_posts()) : ?>
			<div class="hireai-product-grid">
				<?php while ($cases_query->have_posts()) : $cases_query->the_post(); ?>
					<?php get_template_part('template-parts/post-card', null, ['cta_text' => $cta_text]); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<?php hireai_pagination($cases_query->max_num_pages, $paged); ?>
		<?php else : ?>
			<div class="hireai-product-grid">
				<?php foreach ($fallback_cases as $item) : ?>
					<?php get_template_part('template-parts/fallback-post-card', null, [
						'title'   => $localize($item, 'title'),
						'tag'     => $localize($item, 'tag'),
						'excerpt' => $localize($item, 'excerpt'),
						'link'    => home_url('/cases-insights/'),
						'cta_text' => $cta_text,
						'image'   => hireai_default_image($localize($item, 'image')),
					]); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="section section--surface">
	<div class="container">
		<div class="section-head section-head--split">
			<h2><?php echo esc_html(hireai_field('insights_title', $is_en ? 'Frontier Insights' : '前沿洞察')); ?></h2>
			<span class="section-head__meta"><?php echo esc_html($is_en ? 'LATEST ARTICLES' : '最新文章'); ?></span>
		</div>

		<div class="insight-list">
			<?php if ($insights_query->have_posts()) : ?>
				<?php while ($insights_query->have_posts()) : $insights_query->the_post(); ?>
					<?php get_template_part('template-parts/insight-row', null, ['cta_text' => $cta_text]); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach ($fallback_insights as $item) : ?>
					<?php get_template_part('template-parts/fallback-insight-row', null, [
						'title'   => $localize($item, 'title'),
						'excerpt' => $localize($item, 'excerpt'),
						'date'    => $localize($item, 'date'),
						'link'    => home_url('/cases-insights/'),
						'cat'     => $is_en ? 'INSIGHT' : '洞察',
						'cta_text' => $cta_text,
					]); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
