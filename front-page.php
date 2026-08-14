<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 首页
 * 参考 aether_ai_light_luxe_1：全屏 Hero + 数字员工 / 解决方案 / 案例&洞察 / FAQ / CTA。
 */
get_header();

$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';
$localize = function ($item, $key) use ($is_en) {
    $value = isset($item[$key]) ? $item[$key] : '';
    if (is_array($value)) {
        return isset($value[$is_en ? 'en' : 'zh']) ? $value[$is_en ? 'en' : 'zh'] : '';
    }
    return $value;
};

$hero_image   = hireai_image('hero_image', hireai_default_image('hero-home.jpg'));
$hero_cta_1   = hireai_link('hero_cta_1', '/ai-employees/', $is_en ? 'Explore AI Employees' : '探索数字员工');
$hero_cta_2   = hireai_link('hero_cta_2', '/ai-solutions/', $is_en ? 'View Solutions' : '了解解决方案');
$employees_cta = hireai_link('employees_cta', '/ai-employees/', $is_en ? 'Explore More' : '探索更多');
$solutions_cta = hireai_link('solutions_cta', '/ai-solutions/', $is_en ? 'Explore More' : '探索更多');
$cases_cta     = hireai_link('cases_cta', '/cases-insights/', $is_en ? 'View All' : '查看全部');
$faq_cta       = hireai_link('faq_cta', '/faq/', $is_en ? 'View FAQ' : '查看 FAQ');
$cta_button    = hireai_link('cta_button', '/contact/', $is_en ? 'Contact Us' : '联系我们');

$fallback_employees = [
    ['title' => ['zh' => 'Nexus-01', 'en' => 'Nexus-01'], 'role' => ['zh' => '数据分析师', 'en' => 'Data Analyst'], 'image' => 'employee-1.jpg', 'link' => '/ai-employees/'],
    ['title' => ['zh' => 'Aura', 'en' => 'Aura'], 'role' => ['zh' => '创意总监', 'en' => 'Creative Dir.'], 'image' => 'employee-2.jpg', 'link' => '/ai-employees/'],
    ['title' => ['zh' => 'Logos', 'en' => 'Logos'], 'role' => ['zh' => '策略师', 'en' => 'Strategist'], 'image' => 'employee-3.jpg', 'link' => '/ai-employees/'],
    ['title' => ['zh' => 'Seren', 'en' => 'Seren'], 'role' => ['zh' => '客户关系', 'en' => 'Client Rel.'], 'image' => 'employee-4.jpg', 'link' => '/ai-employees/'],
    ['title' => ['zh' => 'Vanguard', 'en' => 'Vanguard'], 'role' => ['zh' => '架构师', 'en' => 'Architect'], 'image' => 'employee-5.jpg', 'link' => '/ai-employees/'],
];

$fallback_solutions = [
    ['title' => ['zh' => '全域营销智囊', 'en' => 'Omnichannel Marketing Intelligence'], 'icon' => 'arrow', 'image' => 'solution-1.jpg', 'text' => ['zh' => '覆盖内容、投放与数据复盘的全链路营销智能体。', 'en' => 'A full-funnel marketing agent for content, media, and performance review.'], 'link' => '/ai-solutions/'],
    ['title' => ['zh' => '电商转化引擎', 'en' => 'Commerce Conversion Engine'], 'icon' => 'arrow', 'image' => 'solution-2.jpg', 'text' => ['zh' => '从选品、定价到客服，让增长从洞察到成交顺畅闭环。', 'en' => 'Connects selection, pricing, and service into a seamless growth loop.'], 'link' => '/ai-solutions/'],
    ['title' => ['zh' => '奢品内容工坊', 'en' => 'Luxury Content Atelier'], 'icon' => 'arrow', 'image' => 'solution-3.jpg', 'text' => ['zh' => '为高净值品牌打造有艺术质感、有销售力的内容体系。', 'en' => 'Crafts artful, conversion-ready content systems for high-net-worth brands.'], 'link' => '/ai-solutions/'],
];

$fallback_cases = [
    ['title' => ['zh' => '奢侈品牌中国区内容焕新', 'en' => 'Luxury Brand China Content Refresh'], 'kicker' => ['zh' => '精选案例 · 奢侈零售', 'en' => 'Case Study · Luxury Retail'], 'text' => ['zh' => '以数字员工重建内容矩阵，让发布效率与品牌质感同步提升。', 'en' => 'Digital employees rebuild the content matrix while preserving brand polish.'], 'link' => '/cases-insights/'],
    ['title' => ['zh' => 'AI 雇佣时代的组织设计', 'en' => 'Organizational Design for the AI Hiring Era'], 'kicker' => ['zh' => '前沿洞察 · 科技', 'en' => 'Insight · Technology'], 'text' => ['zh' => '数字员工不是工具，而是组织能力的新单元。', 'en' => 'Digital employees are not tools; they are a new unit of organizational capability.'], 'link' => '/cases-insights/'],
];

$fallback_faq = [
    ['title' => ['zh' => '数字员工如何与我的团队协作？', 'en' => 'How do digital employees work with my team?'], 'answer' => ['zh' => '他们以专属工作台、内容交付与数据报表的方式参与项目，并可由您随时调整任务边界。', 'en' => 'They join through dedicated workspaces, content delivery, and reporting, with boundaries you can adjust at any time.']],
    ['title' => ['zh' => '如何收费？', 'en' => 'How is pricing structured?'], 'answer' => ['zh' => '按方案与使用周期定制，首页展示的价格为入门档；我们会根据团队规模与场景给出明确报价。', 'en' => 'Pricing is tailored by scope and engagement. Homepage prices are entry-level; we provide a clear quote based on team size and use case.']],
    ['title' => ['zh' => '数据与隐私如何保障？', 'en' => 'How is data privacy protected?'], 'answer' => ['zh' => '客户数据仅在合同约定的范围内用于交付，不用于训练其他客户模型。', 'en' => 'Client data is used only for the agreed engagement and is never used to train other clients’ models.']],
    ['title' => ['zh' => '上线周期需要多久？', 'en' => 'What is the onboarding timeline?'], 'answer' => ['zh' => '标准周期为 4 至 8 周，具体取决于数据结构与定制深度。', 'en' => 'The standard integration period ranges from 4 to 8 weeks, depending on complexity and customization.']],
];

$employees_query = new WP_Query(['post_type' => 'post', 'posts_per_page' => 3, 'category_name' => 'ai-employee', 'no_found_rows' => true]);
$solutions_query = class_exists('WooCommerce') ? new WP_Query(['post_type' => 'product', 'posts_per_page' => 3, 'no_found_rows' => true]) : false;
$cases_query = new WP_Query(['post_type' => 'post', 'posts_per_page' => 2, 'category_name' => 'cases', 'no_found_rows' => true]);
$faq_query = new WP_Query(['post_type' => 'post', 'posts_per_page' => 4, 'category_name' => 'faq', 'no_found_rows' => true]);
?>
<section class="hero">
	<?php if ($hero_image) : ?>
		<div class="hero__media" style="background-image:url('<?php echo esc_url($hero_image); ?>')"></div>
	<?php else : ?>
		<div class="hero__media"></div>
	<?php endif; ?>
	<div class="container hero__content">
		<span class="label hero__kicker"><?php echo esc_html(hireai_field('hero_kicker', 'HIRE AI PEOPLE')); ?></span>
		<h1 class="display-lg"><?php echo esc_html(hireai_field('hero_title', $is_en ? "Hire Intelligence,\nArtfully Employed." : "智慧雇佣，\n臻于艺术。")); ?></h1>
		<p class="body-lg"><?php echo esc_html(hireai_field('hero_subtitle', $is_en ? 'HireAI People employs bespoke AI digital employees and solutions—crafted with artisan precision to quietly drive your growth.' : '聘AI 为企业雇聘专属 AI 数字员工与解决方案——以工匠精神雕琢算法，以静谧之力驱动增长。')); ?></p>
		<div class="hero__actions">
			<a class="btn btn-outline" href="<?php echo esc_url($hero_cta_1['url']); ?>"<?php echo !empty($hero_cta_1['target']) ? ' target="' . esc_attr($hero_cta_1['target']) . '" rel="noopener"' : ''; ?>><?php echo esc_html($hero_cta_1['title']); ?></a>
			<a class="btn btn-ghost" href="<?php echo esc_url($hero_cta_2['url']); ?>"<?php echo !empty($hero_cta_2['target']) ? ' target="' . esc_attr($hero_cta_2['target']) . '" rel="noopener"' : ''; ?>><?php echo esc_html($hero_cta_2['title']); ?></a>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="module-head">
			<div class="module-head__copy">
				<span class="label module-head__kicker"><?php echo esc_html(hireai_field('employees_kicker', $is_en ? 'AI EMPLOYEES' : 'AI 数字员工')); ?></span>
				<h2><?php echo esc_html(hireai_field('employees_title', $is_en ? 'Digital Artisans' : '数字工匠')); ?></h2>
				<p><?php echo esc_html(hireai_field('employees_subtitle', $is_en ? 'Each digital employee brings a unique soul, refined skills, and unmatched capabilities—ready to join your team.' : '每一位数字员工都拥有独特的灵魂、技能与能力，随时加入您的团队。')); ?></p>
			</div>
			<a class="text-link" href="<?php echo esc_url($employees_cta['url']); ?>"><?php echo esc_html($employees_cta['title']); ?> <?php echo hireai_svg('arrow', 14); ?></a>
		</div>

		<div class="employee-grid">
			<?php if ($employees_query->have_posts()) : ?>
				<?php while ($employees_query->have_posts()) : $employees_query->the_post(); ?>
					<?php get_template_part('template-parts/employee-card'); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach (array_slice($fallback_employees, 0, 3) as $item) : ?>
					<?php get_template_part('template-parts/fallback-employee-card', null, [
						'title' => $localize($item, 'title'),
						'role'  => $localize($item, 'role'),
						'image' => hireai_default_image($localize($item, 'image')),
						'link'  => home_url($localize($item, 'link')),
					]); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="section section--surface">
	<div class="container">
		<div class="module-head">
			<div class="module-head__copy">
				<span class="label module-head__kicker"><?php echo esc_html(hireai_field('solutions_kicker', $is_en ? 'AI SOLUTIONS' : 'AI 解决方案')); ?></span>
				<h2><?php echo esc_html(hireai_field('solutions_title', $is_en ? 'Curated Solutions' : '臻选解决方案')); ?></h2>
				<p><?php echo esc_html(hireai_field('solutions_subtitle', $is_en ? 'Bespoke intelligent services tailored for marketing, e-commerce, design, and PR.' : '面向营销、电商、设计、公关四大场景的量身定制智能服务。')); ?></p>
			</div>
			<a class="text-link" href="<?php echo esc_url($solutions_cta['url']); ?>"><?php echo esc_html($solutions_cta['title']); ?> <?php echo hireai_svg('arrow', 14); ?></a>
		</div>

		<div class="solution-grid">
			<?php if ($solutions_query && $solutions_query->have_posts()) : ?>
				<?php $solution_i = 0; ?>
				<?php while ($solutions_query->have_posts()) : $solutions_query->the_post(); ?>
					<?php get_template_part('template-parts/solution-card', null, ['index' => $solution_i, 'cta_text' => $is_en ? 'Explore More' : '探索更多']); ?>
					<?php $solution_i++; ?>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach ($fallback_solutions as $item) : ?>
					<?php get_template_part('template-parts/solution-card', null, [
						'fallback' => true,
						'title'    => $localize($item, 'title'),
						'text'     => $localize($item, 'text'),
						'link'     => home_url($localize($item, 'link')),
						'image'    => hireai_default_image($localize($item, 'image')),
						'icon'     => $localize($item, 'icon'),
						'cta_text' => $is_en ? 'Explore More' : '探索更多',
					]); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="module-head">
			<div class="module-head__copy">
				<span class="label module-head__kicker"><?php echo esc_html(hireai_field('cases_kicker', $is_en ? 'CASES & INSIGHTS' : '案例与洞察')); ?></span>
				<h2><?php echo esc_html(hireai_field('cases_title', $is_en ? 'Cases & Insights' : '案例与思考')); ?></h2>
				<p><?php echo esc_html(hireai_field('cases_subtitle', $is_en ? 'See how digital employees transform operations and explore the deeper currents of AI.' : '见证数字员工如何改变企业的运营方式，洞察 AI 行业的深层趋势。')); ?></p>
			</div>
			<a class="text-link" href="<?php echo esc_url($cases_cta['url']); ?>"><?php echo esc_html($cases_cta['title']); ?> <?php echo hireai_svg('arrow', 14); ?></a>
		</div>

		<div class="case-grid">
			<?php if ($cases_query->have_posts()) : ?>
				<?php while ($cases_query->have_posts()) : $cases_query->the_post(); ?>
					<article class="case-card">
						<a class="case-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"<?php if (has_post_thumbnail()) : ?> style="background-image:url('<?php echo esc_url(get_the_post_thumbnail_url(null, 'hireai-wide')); ?>')"<?php endif; ?>></a>
						<span class="label case-card__kicker"><?php $cats = get_the_category(); echo esc_html(!empty($cats) ? $cats[0]->name : ($is_en ? 'Case Study' : '精选案例')); ?></span>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24)); ?></p>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach ($fallback_cases as $item) : $case_image = hireai_default_image($localize($item, 'image')); ?>
					<article class="case-card">
						<a class="case-card__media" href="<?php echo esc_url(home_url($localize($item, 'link'))); ?>" tabindex="-1" aria-hidden="true"<?php if ($case_image) : ?> style="background-image:url('<?php echo esc_url($case_image); ?>')"<?php endif; ?>></a>
						<span class="label case-card__kicker"><?php echo esc_html($localize($item, 'kicker')); ?></span>
						<h3><a href="<?php echo esc_url(home_url($localize($item, 'link'))); ?>"><?php echo esc_html($localize($item, 'title')); ?></a></h3>
						<p><?php echo esc_html($localize($item, 'text')); ?></p>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="section section--surface">
	<div class="container">
		<div class="module-head">
			<div class="module-head__copy">
				<span class="label module-head__kicker"><?php echo esc_html(hireai_field('faq_kicker', $is_en ? 'FAQ' : '常见问题')); ?></span>
				<h2><?php echo esc_html(hireai_field('faq_title', $is_en ? 'Answers, Immediately' : '疑问，即刻解答')); ?></h2>
				<p><?php echo esc_html(hireai_field('faq_subtitle', $is_en ? 'Common questions about partnerships, finance, privacy, and security.' : '关于合作方式、财务、隐私与安全的常见问题。')); ?></p>
			</div>
			<a class="text-link" href="<?php echo esc_url($faq_cta['url']); ?>"><?php echo esc_html($faq_cta['title']); ?> <?php echo hireai_svg('arrow', 14); ?></a>
		</div>

		<div class="faq-panel">
			<?php if ($faq_query->have_posts()) : ?>
				<?php while ($faq_query->have_posts()) : $faq_query->the_post(); ?>
					<article class="faq-item">
						<button class="faq-item__toggle" type="button" aria-expanded="false">
							<span class="faq-item__q"><span class="faq-item__q-text"><?php the_title(); ?></span></span>
							<span class="faq-item__icon" aria-hidden="true"><?php echo hireai_svg('plus', 22); ?></span>
						</button>
						<div class="faq-item__a">
							<div class="faq-item__a-inner"><p><span class="faq-item__a-text"><?php echo esc_html(wp_strip_all_tags(get_the_content())); ?></span></p></div>
						</div>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach ($fallback_faq as $item) : ?>
					<?php get_template_part('template-parts/fallback-faq', null, [
						'question' => $localize($item, 'title'),
						'answer'   => $localize($item, 'answer'),
					]); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="cta-section">
	<div class="container">
		<div class="cta-section__inner">
			<h2><?php echo esc_html(hireai_field('cta_title', $is_en ? 'Begin Your AI Hiring Journey' : '开启您的 AI 雇佣之旅')); ?></h2>
			<p><?php echo esc_html(hireai_field('cta_subtitle', $is_en ? 'Speak with our team and craft a digital workforce made for you.' : '与我们的团队对话，打造专属您的数字员工阵容。')); ?></p>
			<a class="btn btn-solid" href="<?php echo esc_url($cta_button['url']); ?>"><?php echo esc_html($cta_button['title']); ?> <?php echo hireai_svg('arrow', 14); ?></a>
		</div>
	</div>
</section>

<?php get_footer(); ?>
