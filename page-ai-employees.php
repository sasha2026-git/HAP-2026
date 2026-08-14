<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - AI数字员工
 * 参考 ai_aether_ai_light_luxe_1：居中 Hero + 交替式数字员工档案 + 分页。
 */
get_header();

$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';
$paged  = max(1, get_query_var('paged'));
$cta_text = hireai_field('card_cta_text', $is_en ? 'Explore More' : '探索更多');

$query = new WP_Query([
    'post_type'      => 'post',
    'category_name'  => 'ai-employee',
    'posts_per_page' => HIREAI_EMPLOYEES_PER_PAGE,
    'paged'          => $paged,
]);

$fallback = [
    ['title' => ['zh' => 'Nexus-01', 'en' => 'Nexus-01'], 'role' => ['zh' => '数据分析师', 'en' => 'Data Analyst'], 'intro' => ['zh' => '以数据为镜，把复杂业务问题转化为可执行的智能分析。', 'en' => 'Turns complex business questions into actionable data intelligence.'], 'tags' => ['zh' => ['数据分析', '市场策略', '内容创作'], 'en' => ['DATA ANALYSIS', 'MARKET STRATEGY', 'CONTENT CREATION']], 'image' => 'employee-1.jpg'],
    ['title' => ['zh' => 'Aura', 'en' => 'Aura'], 'role' => ['zh' => '创意总监', 'en' => 'Creative Dir.'], 'intro' => ['zh' => '以算法与美学为双翼，把抽象概念转化为可落地的视觉体系。', 'en' => 'The nexus of algorithm and aesthetic, translating abstract concepts into visual architecture.'], 'tags' => ['zh' => ['创意方向', '视觉体系', '生成式设计'], 'en' => ['CREATIVE DIRECTION', 'VISUAL SYSTEM', 'GENERATIVE DESIGN']], 'image' => 'employee-2.jpg'],
    ['title' => ['zh' => 'Logos', 'en' => 'Logos'], 'role' => ['zh' => '策略师', 'en' => 'Strategist'], 'intro' => ['zh' => '擅长市场洞察与增长实验，把复杂商业目标拆解为可执行的智能行动。', 'en' => 'Maps market insight into executable intelligence and compounding growth experiments.'], 'tags' => ['zh' => ['品牌策略', '增长实验', '策略拆解'], 'en' => ['BRAND STRATEGY', 'GROWTH EXPERIMENTS', 'STRATEGY MAPPING']], 'image' => 'employee-3.jpg'],
    ['title' => ['zh' => 'Seren', 'en' => 'Seren'], 'role' => ['zh' => '客户关系', 'en' => 'Client Rel.'], 'intro' => ['zh' => '以沉稳与亲和陪伴客户，让每一次协作都保持清晰、顺畅与体面。', 'en' => 'Keeps every collaboration clear, smooth, and refined through calm, attentive client care.'], 'tags' => ['zh' => ['客户关系', '专属服务', '24×7 待命'], 'en' => ['CLIENT RELATIONS', 'CONCIERGE SERVICE', '24×7 AVAILABILITY']], 'image' => 'employee-4.jpg'],
    ['title' => ['zh' => 'Vanguard', 'en' => 'Vanguard'], 'role' => ['zh' => '架构师', 'en' => 'Architect'], 'intro' => ['zh' => '为复杂企业系统设计安全、可扩展、优雅的智能基础设施。', 'en' => 'Designs secure, scalable, and elegant intelligent infrastructure for complex enterprises.'], 'tags' => ['zh' => ['系统架构', '数据安全', '弹性扩展'], 'en' => ['SYSTEM ARCHITECTURE', 'DATA SECURITY', 'SCALABLE LOGIC']], 'image' => 'employee-5.jpg'],
];

$localize = function ($item, $key) use ($is_en) {
    $value = isset($item[$key]) ? $item[$key] : '';
    if (is_array($value)) {
        return isset($value[$is_en ? 'en' : 'zh']) ? $value[$is_en ? 'en' : 'zh'] : '';
    }
    return $value;
};
?>
<header class="page-hero page-hero--center">
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', $is_en ? 'AI EMPLOYEES' : 'AI 数字员工')); ?></span>
	<h1 class="display-lg page-hero__title"><?php echo esc_html(hireai_field('header_title', $is_en ? 'The Digital Workforce' : '数字员工名录')); ?></h1>
	<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('header_subtitle', $is_en ? 'Each is a deeply-trained bespoke agent with a distinct soul and capability set.' : '每一位都是经过深度训练的专属智能体，拥有独特的灵魂与能力。')); ?></p>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="employee-directory">
			<?php if ($query->have_posts()) : ?>
				<?php $index = 0; while ($query->have_posts()) : $query->the_post(); ?>
					<?php get_template_part('template-parts/employee-row', null, [
						'cta_text' => $cta_text,
						'reverse'  => ($index % 2 === 1),
					]); ?>
					<?php $index++; ?>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach ($fallback as $index => $item) : ?>
					<?php get_template_part('template-parts/fallback-employee-row', null, [
						'title'    => $localize($item, 'title'),
						'role'     => $localize($item, 'role'),
						'intro'    => $localize($item, 'intro'),
						'tags'     => $localize($item, 'tags'),
						'image'    => hireai_default_image($localize($item, 'image')),
						'link'     => home_url('/ai-employees/'),
						'cta_text' => $cta_text,
						'reverse'  => ($index % 2 === 1),
					]); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php hireai_pagination($query->max_num_pages, $paged); ?>
	</div>
</section>

<?php get_footer(); ?>
