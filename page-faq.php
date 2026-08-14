<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 常见问题
 * 参考 aether_ai_light_luxe_3：居中检索 + 分类 + 手风琴；空库时默认问答可见。
 */
get_header();

$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';

$groups = [
    'partnership' => ['label' => hireai_field('faq_group_1_label', $is_en ? 'Partnership' : '合作方式')],
    'finance' => ['label' => hireai_field('faq_group_2_label', $is_en ? 'Finance' : '财务')],
    'privacy-security' => ['label' => hireai_field('faq_group_3_label', $is_en ? 'Privacy & Security' : '隐私和安全')],
    'other' => ['label' => hireai_field('faq_group_4_label', $is_en ? 'Other' : '其他')],
];

$fallback = [
    'partnership' => [
        ['title' => ['zh' => '数字员工如何与我的团队协作？', 'en' => 'How do digital employees work with my team?'], 'answer' => ['zh' => '他们以专属工作台、内容交付与数据报表的方式参与项目，并可由您随时调整任务边界。', 'en' => 'They join through dedicated workspaces, content delivery, and reporting, with boundaries you can adjust at any time.']],
        ['title' => ['zh' => '上线周期需要多久？', 'en' => 'What is the onboarding timeline?'], 'answer' => ['zh' => '标准周期为 4 至 8 周，具体取决于数据结构与定制深度。', 'en' => 'The standard integration period ranges from 4 to 8 weeks, depending on complexity and customization.']],
    ],
    'finance' => [
        ['title' => ['zh' => '如何收费？', 'en' => 'How is pricing structured?'], 'answer' => ['zh' => '按方案与使用周期定制，首页展示的价格为入门档；我们会根据团队规模与场景给出明确报价。', 'en' => 'Pricing is tailored by scope and engagement. Homepage prices are entry-level; we provide a clear quote based on team size and use case.']],
    ],
    'privacy-security' => [
        ['title' => ['zh' => '数据与隐私如何保障？', 'en' => 'How is data privacy protected?'], 'answer' => ['zh' => '客户数据仅在合同约定的范围内用于交付，不用于训练其他客户模型。', 'en' => 'Client data is used only for the agreed engagement and is never used to train other clients’ models.']],
    ],
    'other' => [
        ['title' => ['zh' => '我可以先试用一个数字员工吗？', 'en' => 'Can I try a digital employee first?'], 'answer' => ['zh' => '可以。我们会为合适场景提供限时试点，明确交付物与评估标准。', 'en' => 'Yes. For suitable scenarios we offer a time-boxed pilot with clear deliverables and evaluation criteria.']],
    ],
];

$localize = function ($item, $key) use ($is_en) {
    $value = isset($item[$key]) ? $item[$key] : '';
    if (is_array($value)) {
        return isset($value[$is_en ? 'en' : 'zh']) ? $value[$is_en ? 'en' : 'zh'] : '';
    }
    return $value;
};

$faq_query = new WP_Query(['post_type' => 'post', 'posts_per_page' => 20, 'category_name' => 'faq', 'no_found_rows' => true]);
$faq_posts = [];
if ($faq_query->have_posts()) {
    while ($faq_query->have_posts()) {
        $faq_query->the_post();
        $group = function_exists('get_field') ? get_field('faq_group') : '';
        $faq_posts[$group][] = ['q' => get_the_title(), 'a' => wp_strip_all_tags(get_the_content()), 'link' => get_permalink()];
    }
    wp_reset_postdata();
}
$has_posts = !empty($faq_posts);
?>
<header class="page-hero page-hero--center">
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', $is_en ? 'FAQ' : '常见问题')); ?></span>
	<h1 class="display-lg page-hero__title"><?php echo esc_html(hireai_field('header_title', $is_en ? 'Clarity Amidst Complexity' : '清晰以对')); ?></h1>
	<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('header_subtitle', $is_en ? 'Find answers to common questions regarding our AI employee ecosystem.' : '在复杂中寻求清晰——关于我们 AI 数字员工生态的常见问题解答。')); ?></p>
	<div class="faq-search-wrap">
		<div class="faq-search">
			<?php echo hireai_svg('search', 18); ?>
			<input id="faq-search-input" type="search" placeholder="<?php echo esc_attr(hireai_field('search_placeholder', $is_en ? 'Search questions…' : '输入关键词检索…')); ?>">
		</div>
	</div>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="faq-categories" role="group" aria-label="<?php echo esc_attr($is_en ? 'FAQ categories' : 'FAQ 分类'); ?>">
			<?php $first = true; foreach ($groups as $slug => $group) : ?>
				<button class="faq-category<?php echo $first ? ' is-active' : ''; ?>" type="button" data-faq-category="<?php echo esc_attr($slug); ?>" aria-selected="<?php echo $first ? 'true' : 'false'; ?>"><?php echo esc_html($group['label']); ?></button>
				<?php $first = false; endforeach; ?>
		</div>

		<div class="faq-panel">
			<?php foreach ($groups as $slug => $group) : ?>
				<div data-faq-group data-faq-category-group="<?php echo esc_attr($slug); ?>"<?php echo $slug !== 'partnership' ? ' hidden' : ''; ?>>
					<?php
					$items = !empty($faq_posts[$slug]) ? $faq_posts[$slug] : $fallback[$slug];
					foreach ($items as $item) :
						if (isset($item['q'], $item['a'])) {
							$question = $item['q'];
							$answer = $item['a'];
						} else {
							$question = $localize($item, 'title');
							$answer = $localize($item, 'answer');
						}
						?>
						<article class="faq-item">
							<button class="faq-item__toggle" type="button" aria-expanded="false">
								<span class="faq-item__q"><span class="faq-item__q-text"><?php echo esc_html($question); ?></span></span>
								<span class="faq-item__icon" aria-hidden="true"><?php echo hireai_svg('plus', 22); ?></span>
							</button>
							<div class="faq-item__a">
								<div class="faq-item__a-inner"><p><span class="faq-item__a-text"><?php echo esc_html($answer); ?></span></p></div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
			<p class="faq-empty" data-faq-empty hidden><?php echo esc_html(hireai_field('empty_text', $is_en ? 'No matching questions found. Try a different keyword.' : '未找到匹配的问题，请尝试其他关键词。')); ?></p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
