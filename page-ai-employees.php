<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - AI数字员工
 * 参考 ai_aether_ai_light_luxe_1：居中 Hero + 12 栏重叠式员工行 + 分页。
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

$cta_text = hireai_field('card_cta_text', $suffix === '_en' ? 'Explore More' : '探索更多');
$fallback = [
    [
        'title' => ['zh' => 'Aria · 灵汐', 'en' => 'Aria'],
        'role'  => ['zh' => '品牌策略师', 'en' => 'Brand Strategist'],
        'intro' => ['zh' => '以数据为镜、以叙事为笔，为品牌构建持续增长的传播链路。', 'en' => 'Data-driven storytelling that builds a durable growth path for modern brands.'],
        'tags'  => ['zh' => ['数据叙事', '品牌诊断', '传播策略'], 'en' => ['DATA STORYTELLING', 'BRAND DIAGNOSIS', 'COMMUNICATION STRATEGY']],
        'link'  => ['zh' => home_url('/ai-employees/'), 'en' => home_url('/ai-employees/')],
    ],
    [
        'title' => ['zh' => 'Atlas · 远航', 'en' => 'Atlas'],
        'role'  => ['zh' => '商业增长官', 'en' => 'Growth Architect'],
        'intro' => ['zh' => '擅长市场洞察与增长实验，把复杂商业目标拆解为可执行的智能行动。', 'en' => 'Maps market insight into executable intelligence and compounding growth experiments.'],
        'tags'  => ['zh' => ['市场洞察', '增长实验', '策略拆解'], 'en' => ['MARKET INSIGHT', 'GROWTH EXPERIMENTS', 'STRATEGY MAPPING']],
        'link'  => ['zh' => home_url('/ai-employees/'), 'en' => home_url('/ai-employees/')],
    ],
    [
        'title' => ['zh' => 'Nexus · 无界', 'en' => 'Nexus'],
        'role'  => ['zh' => '系统架构师', 'en' => 'System Architect'],
        'intro' => ['zh' => '为复杂企业系统设计安全、可扩展、优雅的智能基础设施。', 'en' => 'Designs secure, scalable, and elegant intelligent infrastructure for complex enterprises.'],
        'tags'  => ['zh' => ['系统架构', '数据安全', '弹性扩展'], 'en' => ['SYSTEM ARCHITECTURE', 'DATA SECURITY', 'SCALABLE LOGIC']],
        'link'  => ['zh' => home_url('/ai-employees/'), 'en' => home_url('/ai-employees/')],
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
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', 'AI 数字员工')); ?></span>
	<h1 class="display-lg page-hero__title"><?php echo esc_html(hireai_field('header_title', $suffix === '_en' ? 'The Digital Workforce' : '数字员工名录')); ?></h1>
	<?php if (hireai_field('header_subtitle')) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('header_subtitle')); ?></p>
	<?php endif; ?>
</header>

<section class="section employee-directory" style="padding-top:0;">
	<div class="container">
		<?php if ($query->have_posts()) : ?>
			<?php
			while ($query->have_posts()) {
				$query->the_post();
				get_template_part('template-parts/employee-row', null, ['cta_text' => $cta_text]);
			}
			wp_reset_postdata();
			?>
			<?php hireai_pagination($query->max_num_pages, $paged); ?>
		<?php else : ?>
			<?php foreach ($fallback as $item) : ?>
				<?php
				get_template_part('template-parts/fallback-employee-row', null, [
					'title'    => $localize($item, 'title'),
					'role'     => $localize($item, 'role'),
					'intro'    => $localize($item, 'intro'),
					'tags'     => $localize($item, 'tags'),
					'link'     => $localize($item, 'link'),
					'cta_text' => $cta_text,
				]);
				?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
