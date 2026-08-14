<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 首页
 * 首页 — Hero + 五个模块（数字员工 / 解决方案 / 案例&洞察 / FAQ / 联系 CTA）
 */
get_header();

$suffix = hireai_lang_suffix();
$hero_img = hireai_image('hero_image');
$hero_cta_1 = hireai_link('hero_cta_1', '/ai-employees/', $suffix === '_en' ? 'Explore AI Employees' : '探索数字员工');
$hero_cta_2 = hireai_link('hero_cta_2', '/ai-solutions/', $suffix === '_en' ? 'View Solutions' : '了解解决方案');

$employees_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 2,
    'category_name'  => 'ai-employee',
    'no_found_rows'  => true,
]);

$has_woo = class_exists('WooCommerce');
$solutions_query = false;
if ($has_woo) {
    $solutions_query = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => 3,
        'no_found_rows'  => true,
    ]);
}

$cases_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'category_name'  => 'cases',
    'no_found_rows'  => true,
]);

$insights_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 2,
    'category_name'  => 'insights',
    'no_found_rows'  => true,
]);

$faq_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'category_name'  => 'faq',
    'no_found_rows'  => true,
]);

$emp_cta  = hireai_link('employees_cta', '/ai-employees/', $suffix === '_en' ? 'Explore More' : '探索更多');
$sol_cta  = hireai_link('solutions_cta', '/ai-solutions/', $suffix === '_en' ? 'Explore More' : '探索更多');
$case_cta = hireai_link('cases_cta', '/cases-insights/', $suffix === '_en' ? 'View All' : '查看全部');
$faq_cta  = hireai_link('faq_cta', '/faq/', $suffix === '_en' ? 'View FAQ' : '查看 FAQ');
$cta_btn  = hireai_link('cta_button', '/contact/', $suffix === '_en' ? 'Contact Us' : '联系我们');

$fallback_items = [
    'employees' => [
        [
            'title'    => ['zh' => 'Aria · 灵汐', 'en' => 'Aria'],
            'subtitle' => ['zh' => '品牌策略师', 'en' => 'Brand Strategist'],
            'tag'      => ['zh' => '数字员工', 'en' => 'AI EMPLOYEE'],
            'link'     => home_url('/ai-employees/'),
        ],
        [
            'title'    => ['zh' => 'Atlas · 远航', 'en' => 'Atlas'],
            'subtitle' => ['zh' => '商业增长官', 'en' => 'Growth Architect'],
            'tag'      => ['zh' => '数字员工', 'en' => 'AI EMPLOYEE'],
            'link'     => home_url('/ai-employees/'),
        ],
    ],
    'solutions' => [
        [
            'title'    => ['zh' => '全域营销智囊', 'en' => 'Omnichannel Marketing Intelligence'],
            'tag'      => ['zh' => '营销', 'en' => 'MARKETING'],
            'excerpt'  => ['zh' => '覆盖内容、投放与数据复盘的全链路营销智能体。', 'en' => 'A full-funnel marketing agent for content, media, and performance review.'],
            'price'    => ['zh' => '¥4,800 / 月起', 'en' => 'From ¥4,800/mo'],
            'retainer' => ['zh' => '起步档', 'en' => 'Starting Retainer'],
            'link'     => home_url('/ai-solutions/'),
        ],
        [
            'title'    => ['zh' => '电商转化引擎', 'en' => 'Commerce Conversion Engine'],
            'tag'      => ['zh' => '电商', 'en' => 'E-COMMERCE'],
            'excerpt'  => ['zh' => '从选品、定价到客服，让增长从洞察到成交顺畅闭环。', 'en' => 'Connects selection, pricing, and service into a seamless growth loop.'],
            'price'    => ['zh' => '¥6,800 / 月起', 'en' => 'From ¥6,800/mo'],
            'retainer' => ['zh' => '项目基准', 'en' => 'Project Base'],
            'link'     => home_url('/ai-solutions/'),
        ],
        [
            'title'    => ['zh' => '奢品内容工坊', 'en' => 'Luxury Content Atelier'],
            'tag'      => ['zh' => '设计', 'en' => 'DESIGN'],
            'excerpt'  => ['zh' => '为高净值品牌打造有艺术质感、有销售力的内容体系。', 'en' => 'Crafts artful, conversion-ready content systems for high-net-worth brands.'],
            'price'    => ['zh' => '¥8,800 / 月起', 'en' => 'From ¥8,800/mo'],
            'retainer' => ['zh' => '按概念', 'en' => 'Per Concept'],
            'link'     => home_url('/ai-solutions/'),
        ],
    ],
    'cases' => [
        [
            'title'    => ['zh' => '奢侈品牌中国区内容焕新', 'en' => 'Luxury Brand China Content Refresh'],
            'tag'      => ['zh' => '精选案例', 'en' => 'FEATURED CASE'],
            'excerpt'  => ['zh' => '以数字员工重建内容矩阵，让发布效率与品牌质感同步提升。', 'en' => 'Digital employees rebuild the content matrix while preserving brand polish.'],
            'link'     => home_url('/cases-insights/'),
        ],
        [
            'title'    => ['zh' => '跨境电商 24×7 客服', 'en' => 'Cross-Border Commerce 24×7 Service'],
            'tag'      => ['zh' => '精选案例', 'en' => 'FEATURED CASE'],
            'excerpt'  => ['zh' => '数字员工覆盖多时区客服，把等待变成即时响应。', 'en' => 'Digital employees cover every timezone and turn waiting into immediate response.'],
            'link'     => home_url('/cases-insights/'),
        ],
        [
            'title'    => ['zh' => '高净值品牌私域增长', 'en' => 'Private Growth for a High-Net-Worth Brand'],
            'tag'      => ['zh' => '精选案例', 'en' => 'FEATURED CASE'],
            'excerpt'  => ['zh' => '将私域内容与销售线索联动，形成可复用的增长闭环。', 'en' => 'Links private-domain content with sales signals into a reusable growth loop.'],
            'link'     => home_url('/cases-insights/'),
        ],
    ],
    'insights' => [
        [
            'title'    => ['zh' => 'AI 雇佣时代的组织设计', 'en' => 'Organizational Design for the AI Hiring Era'],
            'tag'      => ['zh' => '前沿洞察', 'en' => 'FRONTIER INSIGHT'],
            'excerpt'  => ['zh' => '数字员工不是工具，而是组织能力的新单元。', 'en' => 'Digital employees are not tools; they are a new unit of organizational capability.'],
            'link'     => home_url('/cases-insights/'),
        ],
        [
            'title'    => ['zh' => '从模型到灵魂：数字员工的体验层', 'en' => 'From Model to Soul: The Experience Layer'],
            'tag'      => ['zh' => '前沿洞察', 'en' => 'FRONTIER INSIGHT'],
            'excerpt'  => ['zh' => '奢华 AI 产品的差异，在于可感知的人格与协作体验。', 'en' => 'Luxury AI products are defined by a perceptible personality and collaborative presence.'],
            'link'     => home_url('/cases-insights/'),
        ],
    ],
    'faq' => [
        [
            'title'   => ['zh' => '数字员工如何与我的团队协作？', 'en' => 'How do digital employees work with my team?'],
            'excerpt' => ['zh' => '他们以专属工作台、内容交付与数据报表的方式参与项目，并可由您随时调整任务边界。', 'en' => 'They join through dedicated workspaces, content delivery, and reporting, with boundaries you can adjust at any time.'],
        ],
        [
            'title'   => ['zh' => '如何收费？', 'en' => 'How is pricing structured?'],
            'excerpt' => ['zh' => '按方案与使用周期定制，首页展示的价格为入门档；我们会根据团队规模与场景给出明确报价。', 'en' => 'Pricing is tailored by scope and engagement. Homepage prices are entry-level; we provide a clear quote based on team size and use case.'],
        ],
        [
            'title'   => ['zh' => '数据与隐私如何保障？', 'en' => 'How is data privacy protected?'],
            'excerpt' => ['zh' => '客户数据仅在合同约定的范围内用于交付，不用于训练其他客户模型。', 'en' => 'Client data is used only for the agreed engagement and is never used to train other clients’ models.'],
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

$sol_card_cta = hireai_field('card_cta_text', $suffix === '_en' ? 'Explore More' : '探索更多');
$read_card_cta = hireai_field('card_cta_text', $suffix === '_en' ? 'Read More' : '阅读更多');

$render_fallback_employee_card = function ($item) use ($get_localized) {
    $title = $get_localized($item, 'title');
    $role  = $get_localized($item, 'subtitle');
    $link  = $get_localized($item, 'link');
    if ($link === '') {
        $link = home_url('/');
    }
    ?>
    <article class="employee-card" data-reveal>
        <a class="employee-card__media" href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true">
            <span class="media-placeholder">HireAI People</span>
        </a>
        <div class="employee-card__body">
            <?php if ($role !== '') : ?>
                <span class="label employee-card__role"><?php echo esc_html($role); ?></span>
            <?php endif; ?>
            <h3 class="employee-card__name"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
        </div>
    </article>
    <?php
};

$render_fallback_solution_card = function ($item) use ($get_localized) {
    $title   = $get_localized($item, 'title');
    $tag     = $get_localized($item, 'tag');
    $excerpt = $get_localized($item, 'excerpt');
    $price   = $get_localized($item, 'price');
    $retainer = $get_localized($item, 'retainer');
    $link    = $get_localized($item, 'link');
    if ($link === '') {
        $link = home_url('/');
    }
    ?>
    <article class="solution-card" data-reveal>
        <?php if ($tag !== '') : ?>
            <span class="label solution-card__cat"><?php echo esc_html($tag); ?></span>
        <?php endif; ?>
        <h3 class="solution-card__title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
        <p class="solution-card__excerpt"><?php echo esc_html($excerpt); ?></p>
        <?php if ($retainer !== '' || $price !== '') : ?>
            <div class="solution-card__footer">
                <?php if ($retainer !== '') : ?>
                    <span class="solution-card__retainer"><?php echo esc_html($retainer); ?></span>
                <?php endif; ?>
                <?php if ($price !== '') : ?>
                    <strong class="solution-card__price"><?php echo esc_html($price); ?></strong>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </article>
    <?php
};

$render_fallback_post_card = function ($item, $cta_text) use ($get_localized) {
    $title   = $get_localized($item, 'title');
    $excerpt = $get_localized($item, 'excerpt');
    $link    = $get_localized($item, 'link');
    $tag     = $get_localized($item, 'tag');
    if ($link === '') {
        $link = home_url('/');
    }
    ?>
    <article class="card post-card post-card--case" data-reveal>
        <a class="card__media" href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true">
            <span class="media-placeholder">HireAI People</span>
        </a>
        <div class="card__body">
            <?php if ($tag !== '') : ?>
                <div class="card__meta"><?php echo esc_html($tag); ?></div>
            <?php endif; ?>
            <h3 class="card__title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
            <p class="card__excerpt"><?php echo esc_html($excerpt); ?></p>
            <a class="btn btn-ghost" href="<?php echo esc_url($link); ?>"><?php echo esc_html($cta_text); ?></a>
        </div>
    </article>
    <?php
};

$render_fallback_faq = function ($item) use ($get_localized) {
    $question = $get_localized($item, 'title');
    $answer   = $get_localized($item, 'excerpt');
    ?>
    <div class="faq-item">
        <button class="faq-item__toggle" type="button" aria-expanded="false">
            <span class="faq-item__q"><span class="faq-item__q-text"><?php echo esc_html($question); ?></span></span>
            <span class="faq-item__icon" aria-hidden="true"></span>
        </button>
        <div class="faq-item__a">
            <div class="faq-item__a-inner">
                <p><span class="faq-item__a-text"><?php echo esc_html($answer); ?></span></p>
            </div>
        </div>
    </div>
    <?php
};
?>

<!-- ============ Hero ============ -->
<section class="hero hero--cinema">
	<div class="hero__backdrop" aria-hidden="true">
		<?php if ($hero_img) : ?>
			<img src="<?php echo esc_url($hero_img); ?>" alt="">
		<?php else : ?>
			<span class="media-placeholder hero__placeholder">HireAI People</span>
		<?php endif; ?>
	</div>
	<div class="container hero__content" data-reveal>
		<?php if (hireai_field('hero_kicker')) : ?>
			<span class="label hero__kicker"><?php echo esc_html(hireai_field('hero_kicker')); ?></span>
		<?php endif; ?>
		<h1 class="display-lg hero__title"><?php echo esc_html(hireai_field('hero_title', "智慧雇佣，\n臻于艺术。")); ?></h1>
		<?php if (hireai_field('hero_subtitle')) : ?>
			<p class="body-lg hero__subtitle"><?php echo esc_html(hireai_field('hero_subtitle')); ?></p>
		<?php endif; ?>
		<div class="hero__actions">
			<a class="btn btn-primary btn-lg" href="<?php echo esc_url($hero_cta_1['url']); ?>"<?php echo !empty($hero_cta_1['target']) ? ' target="' . esc_attr($hero_cta_1['target']) . '" rel="noopener"' : ''; ?>><?php echo esc_html($hero_cta_1['title']); ?></a>
			<a class="btn btn-secondary btn-lg" href="<?php echo esc_url($hero_cta_2['url']); ?>"<?php echo !empty($hero_cta_2['target']) ? ' target="' . esc_attr($hero_cta_2['target']) . '" rel="noopener"' : ''; ?>><?php echo esc_html($hero_cta_2['title']); ?></a>
		</div>
	</div>
</section>

<!-- ============ 数字员工精选 ============ -->
<section class="home-module">
	<div class="container">
		<div class="section-head__row">
			<?php
			get_template_part('template-parts/section-header', null, [
				'align'    => 'left',
				'kicker'   => hireai_field('employees_kicker', $suffix === '_en' ? 'AI EMPLOYEES' : 'AI 数字员工'),
				'title'    => hireai_field('employees_title', $suffix === '_en' ? 'Digital Artisans' : '数字工匠'),
				'subtitle' => hireai_field('employees_subtitle'),
			]);
			?>
			<a class="btn btn-ghost" href="<?php echo esc_url($emp_cta['url']); ?>"><?php echo esc_html($emp_cta['title']); ?></a>
		</div>
		<?php if ($employees_query->have_posts()) : ?>
			<div class="employee-grid">
				<?php
				while ($employees_query->have_posts()) {
					$employees_query->the_post();
					get_template_part('template-parts/employee-card', null, [
						'cta_text' => $emp_cta['title'],
					]);
				}
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<div class="employee-grid">
				<?php
				foreach (array_slice($fallback_items['employees'], 0, 2) as $item) {
					$render_fallback_employee_card($item);
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- ============ 解决方案精选 ============ -->
<section class="home-module home-module--white">
	<div class="container">
		<div class="section-head__row">
			<?php
			get_template_part('template-parts/section-header', null, [
				'align'    => 'left',
				'kicker'   => hireai_field('solutions_kicker', $suffix === '_en' ? 'AI SOLUTIONS' : 'AI 解决方案'),
				'title'    => hireai_field('solutions_title', $suffix === '_en' ? 'Curated Solutions' : '臻选解决方案'),
				'subtitle' => hireai_field('solutions_subtitle'),
			]);
			?>
			<a class="btn btn-ghost" href="<?php echo esc_url($sol_cta['url']); ?>"><?php echo esc_html($sol_cta['title']); ?></a>
		</div>
		<?php if ($has_woo && $solutions_query->have_posts()) : ?>
			<div class="grid grid--3 solution-card-grid">
				<?php
				while ($solutions_query->have_posts()) {
					$solutions_query->the_post();
					get_template_part('template-parts/solution-card', null, ['cta_text' => $sol_card_cta]);
				}
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<div class="grid grid--3 solution-card-grid">
				<?php
				foreach ($fallback_items['solutions'] as $item) {
					$render_fallback_solution_card($item);
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- ============ 案例 & 洞察精选 ============ -->
<section class="home-module">
	<div class="container">
		<div class="section-head__row">
			<?php
			get_template_part('template-parts/section-header', null, [
				'align'    => 'left',
				'kicker'   => hireai_field('cases_kicker', $suffix === '_en' ? 'CASES & INSIGHTS' : '案例与洞察'),
				'title'    => hireai_field('cases_title', $suffix === '_en' ? 'Cases & Insights' : '案例与思考'),
				'subtitle' => hireai_field('cases_subtitle'),
			]);
			?>
			<a class="btn btn-ghost" href="<?php echo esc_url($case_cta['url']); ?>"><?php echo esc_html($case_cta['title']); ?></a>
		</div>
			<div class="grid grid--3 case-card-grid">
				<?php if ($cases_query->have_posts()) : ?>
					<?php
					while ($cases_query->have_posts()) {
						$cases_query->the_post();
						get_template_part('template-parts/post-card', null, [
							'cta_text' => $read_card_cta,
							'variant'  => 'case',
						]);
					}
					wp_reset_postdata();
					?>
				<?php else : ?>
					<?php
					foreach ($fallback_items['cases'] as $item) {
						$render_fallback_post_card($item, $read_card_cta);
					}
					?>
				<?php endif; ?>
			</div>

			<div class="home-insights insight-list">
				<?php if ($insights_query->have_posts()) : ?>
					<?php
					while ($insights_query->have_posts()) {
						$insights_query->the_post();
						get_template_part('template-parts/insight-row', null, ['cta_text' => $read_card_cta]);
					}
					wp_reset_postdata();
					?>
				<?php else : ?>
					<?php
					foreach ($fallback_items['insights'] as $item) {
						get_template_part('template-parts/fallback-insight-row', null, [
							'title'    => $get_localized($item, 'title'),
							'excerpt'  => $get_localized($item, 'excerpt'),
							'link'     => $get_localized($item, 'link'),
							'date'     => $suffix === '_en' ? 'AUGUST 2026' : '2026 · 08',
							'cat'      => $suffix === '_en' ? 'INSIGHT' : '洞察',
							'cta_text' => $read_card_cta,
						]);
					}
					?>
				<?php endif; ?>
			</div>
		</div>
</section>

<!-- ============ FAQ 精选 ============ -->
<section class="home-module home-module--white">
	<div class="container">
		<div class="section-head__row">
			<?php
			get_template_part('template-parts/section-header', null, [
				'align'    => 'left',
				'kicker'   => hireai_field('faq_kicker', $suffix === '_en' ? 'FAQ' : '常见问题'),
				'title'    => hireai_field('faq_title', $suffix === '_en' ? 'Answers, Immediately' : '疑问，即刻解答'),
				'subtitle' => hireai_field('faq_subtitle'),
			]);
			?>
			<a class="btn btn-ghost" href="<?php echo esc_url($faq_cta['url']); ?>"><?php echo esc_html($faq_cta['title']); ?></a>
		</div>
		<?php if ($faq_query->have_posts()) : ?>
			<div class="faq-list faq-list--center faq-panel">
				<?php
				while ($faq_query->have_posts()) {
					$faq_query->the_post();
					?>
					<div class="faq-item">
						<button class="faq-item__toggle" type="button" aria-expanded="false">
							<span class="faq-item__q"><span class="faq-item__q-text"><?php the_title(); ?></span></span>
							<span class="faq-item__icon" aria-hidden="true"></span>
						</button>
						<div class="faq-item__a">
							<div class="faq-item__a-inner">
								<p><?php echo esc_html(wp_strip_all_tags(get_the_content())); ?></p>
							</div>
						</div>
					</div>
					<?php
				}
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<div class="faq-list faq-list--center faq-panel">
				<?php
				foreach ($fallback_items['faq'] as $item) {
					$render_fallback_faq($item);
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- ============ 联系 CTA ============ -->
<?php
get_template_part('template-parts/cta', null, [
	'kicker'   => $suffix === '_en' ? 'CONTACT' : '联系',
	'title'    => hireai_field('cta_title', $suffix === '_en' ? 'Begin Your AI Hiring Journey' : '开启您的 AI 雇佣之旅'),
	'subtitle' => hireai_field('cta_subtitle'),
	'link'     => $cta_btn,
]);
?>

<?php get_footer(); ?>
