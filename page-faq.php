<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 常见问题
 * 常见问题页：分类标签 + 关键词实时检索 + 手风琴
 */
get_header();

$suffix = hireai_lang_suffix();

$faq_query = new WP_Query([
    'post_type'      => 'post',
    'category_name'  => 'faq',
    'posts_per_page' => -1,
    'no_found_rows'  => true,
]);

$groups = [];
while ($faq_query->have_posts()) {
    $faq_query->the_post();
    $g = site_field('faq_group', 'other', get_the_ID());
    if (!in_array($g, ['partnership', 'finance', 'privacy-security', 'other'], true)) {
        $g = 'other';
    }
    if (!isset($groups[$g])) {
        $groups[$g] = [];
    }
    $groups[$g][] = get_post();
}
wp_reset_postdata();

$group_labels = [
    'partnership'     => hireai_field('faq_group_1_label', $suffix === '_en' ? 'Partnership' : '合作方式'),
    'finance'         => hireai_field('faq_group_2_label', $suffix === '_en' ? 'Finance' : '财务'),
    'privacy-security' => hireai_field('faq_group_3_label', $suffix === '_en' ? 'Privacy & Security' : '隐私和安全'),
    'other'           => hireai_field('faq_group_4_label', $suffix === '_en' ? 'Other' : '其他'),
];

$fallback_faqs = [
    'partnership' => [
        [
            'question' => ['zh' => '如何与聘AI建立合作？', 'en' => 'How do we initiate a partnership with HireAI People?'],
            'answer'   => ['zh' => '合作从一次专属咨询开始。我们的策略师会评估组织需求，并为您的数字员工生态设计定制部署蓝图。', 'en' => 'Partnership begins with an exclusive consultation. Our strategists assess your needs and design a bespoke deployment blueprint.'],
        ],
        [
            'question' => ['zh' => '数字员工的交付周期是多久？', 'en' => 'What is the onboarding timeline for an AI employee?'],
            'answer'   => ['zh' => '标准集成周期通常为 4 至 8 周，具体取决于内部数据结构和定制训练参数。', 'en' => 'Standard integration generally takes 4 to 8 weeks, depending on data structure and customization.'],
        ],
    ],
    'finance' => [
        [
            'question' => ['zh' => '如何收费？', 'en' => 'How is pricing structured?'],
            'answer'   => ['zh' => '按方案与使用周期定制，展示价格为入门档；我们会根据团队规模与场景给出明确报价。', 'en' => 'Pricing is tailored by scope and engagement. Listed prices are entry-level; we provide a clear quote based on team size and use case.'],
        ],
        [
            'question' => ['zh' => '是否支持对公付款与合同？', 'en' => 'Do you support corporate billing and contracts?'],
            'answer'   => ['zh' => '支持。合作确认后我们会提供正式合同与对公结算流程。', 'en' => 'Yes. Once an engagement is confirmed, we provide a formal contract and corporate billing process.'],
        ],
    ],
    'privacy-security' => [
        [
            'question' => ['zh' => '如何保障数据与隐私安全？', 'en' => 'How is data privacy protected?'],
            'answer'   => ['zh' => '客户数据仅在合同范围内用于交付，不用于训练其他客户模型。', 'en' => 'Client data is used only for the agreed engagement and is never used to train other clients’ models.'],
        ],
    ],
    'other' => [
        [
            'question' => ['zh' => '数字员工如何与我的团队协作？', 'en' => 'How do digital employees work with my team?'],
            'answer'   => ['zh' => '他们以专属工作台、内容交付与数据报表的方式参与项目，并可由您随时调整任务边界。', 'en' => 'They join through dedicated workspaces, content delivery, and reporting, with boundaries you can adjust at any time.'],
        ],
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
<header class="page-hero page-hero--center" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', '常见问题')); ?></span>
	<h1 class="display-lg page-hero__title page-hero__title--display"><?php echo esc_html(hireai_field('header_title', '清晰以对')); ?></h1>
	<?php if (hireai_field('header_subtitle')) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('header_subtitle')); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="faq-search" data-reveal>
			<input type="search" id="faq-search-input" class="faq-search__input" autocomplete="off"
				placeholder="<?php echo esc_attr(hireai_field('search_placeholder', '输入关键词检索…')); ?>"
				aria-label="<?php echo esc_attr($suffix === '_en' ? 'Search questions' : '检索问题'); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
				<circle cx="11" cy="11" r="7"></circle>
				<line x1="21" y1="21" x2="16.2" y2="16.2"></line>
			</svg>
		</div>

		<div class="faq-categories" role="tablist" aria-label="<?php echo esc_attr($suffix === '_en' ? 'FAQ categories' : '问题分类'); ?>" data-reveal>
			<button class="faq-category is-active" type="button" role="tab" data-faq-category=""><?php echo esc_html($suffix === '_en' ? 'All' : '全部'); ?></button>
			<?php foreach ($group_labels as $group_key => $group_label) : ?>
				<button class="faq-category" type="button" role="tab" data-faq-category="<?php echo esc_attr($group_key); ?>"><?php echo esc_html($group_label); ?></button>
			<?php endforeach; ?>
		</div>

		<div class="faq-panel faq-panel--card" data-reveal>
			<?php if (empty($groups)) : ?>
				<?php foreach ($group_labels as $group_key => $group_label) : ?>
					<?php if (empty($fallback_faqs[$group_key])) {
						continue;
					} ?>
					<div class="faq-group" data-faq-group="fallback-<?php echo esc_attr($group_key); ?>" data-faq-category-group="<?php echo esc_attr($group_key); ?>">
						<h2 class="faq-group__title"><?php echo esc_html($group_label); ?></h2>
						<div class="faq-list">
							<?php foreach ($fallback_faqs[$group_key] as $item) : ?>
								<?php
								get_template_part('template-parts/fallback-faq', null, [
									'question' => $get_localized($item, 'question'),
									'answer'   => $get_localized($item, 'answer'),
								]);
								?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<?php foreach ($group_labels as $group_key => $group_label) : ?>
					<?php if (empty($groups[$group_key])) {
						continue;
					} ?>
					<div class="faq-group" data-faq-group="<?php echo esc_attr($group_key); ?>" data-faq-category-group="<?php echo esc_attr($group_key); ?>">
						<h2 class="faq-group__title"><?php echo esc_html($group_label); ?></h2>
						<div class="faq-list">
							<?php foreach ($groups[$group_key] as $faq_post) :
								setup_postdata($faq_post);
								?>
								<article class="faq-item">
									<button class="faq-item__toggle" type="button" aria-expanded="false">
										<span class="faq-item__q"><span class="faq-item__q-text"><?php the_title(); ?></span></span>
										<span class="faq-item__icon" aria-hidden="true"></span>
									</button>
									<div class="faq-item__a">
										<div class="faq-item__a-inner">
											<p><span class="faq-item__a-text"><?php echo esc_html(wp_strip_all_tags(get_the_content())); ?></span></p>
										</div>
									</div>
								</article>
							<?php endforeach;
								wp_reset_postdata(); ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<p class="faq-empty" data-faq-empty>
				<?php echo esc_html(hireai_field('empty_text', $suffix === '_en' ? 'No matching questions found. Try a different keyword.' : '未找到匹配的问题，请尝试其他关键词。')); ?>
			</p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
