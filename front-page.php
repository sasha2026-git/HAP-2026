<?php
if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 首页
 * 首页 — Hero + 五个模块（数字员工 / 解决方案 / 案例&洞察 / FAQ / 联系 CTA）
 */
get_header();

$suffix = hireai_lang_suffix();
$hero_img = hireai_image('hero_image');
$hero_cta_1 = hireai_link('hero_cta_1', '/ai-employees/', $suffix === '_en' ? 'Explore AI Employees' : '探索数字员工');
$hero_cta_2 = hireai_link('hero_cta_2', '/ai-solutions/', $suffix === '_en' ? 'View Solutions' : '了解解决方案');

// 各模块数据
$employees_args = [
    'post_type'      => 'post',
    'posts_per_page' => 2,
    'category_name'  => 'ai-employee',
    'no_found_rows'  => true,
];
$employees_query = new WP_Query($employees_args);

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

// 模块文案与链接
$emp_cta  = hireai_link('employees_cta', '/ai-employees/', $suffix === '_en' ? 'Explore More' : '探索更多');
$sol_cta  = hireai_link('solutions_cta', '/ai-solutions/', $suffix === '_en' ? 'Explore More' : '探索更多');
$case_cta = hireai_link('cases_cta', '/cases-insights/', $suffix === '_en' ? 'View All' : '查看全部');
$faq_cta  = hireai_link('faq_cta', '/faq/', $suffix === '_en' ? 'View FAQ' : '查看 FAQ');
$cta_btn  = hireai_link('cta_button', '/contact/', $suffix === '_en' ? 'Contact Us' : '联系我们');
?>

<!-- ============ Hero ============ -->
<section class="hero">
	<div class="container hero__inner">
		<div class="hero__content" data-reveal>
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
		<div class="hero__media" data-reveal>
			<?php if ($hero_img) : ?>
				<img src="<?php echo esc_url($hero_img); ?>" alt="<?php echo esc_attr(hireai_field('hero_title', 'Hire AI People')); ?>">
			<?php else : ?>
				<span class="media-placeholder" style="min-height:460px;">Hire AI People</span>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- ============ 数字员工精选 ============ -->
<?php if ($employees_query->have_posts()) : ?>
	<section class="home-module">
		<div class="container">
			<?php
			get_template_part('template-parts/section-header', null, [
				'kicker'   => hireai_field('employees_kicker', 'AI 数字员工'),
				'title'    => hireai_field('employees_title', '数字工匠'),
				'subtitle' => hireai_field('employees_subtitle'),
			]);
			?>
			<div class="grid grid--2">
				<?php
				while ($employees_query->have_posts()) {
					$employees_query->the_post();
					get_template_part('template-parts/post-card', null, [
						'cta_text'  => hireai_field('card_cta_text', $suffix === '_en' ? 'Explore More' : '探索更多'),
						'show_meta' => false,
					]);
				}
				wp_reset_postdata();
				?>
			</div>
			<div class="section-head__row" style="justify-content:center;margin-top:48px;margin-bottom:0;">
				<a class="btn btn-secondary" href="<?php echo esc_url($emp_cta['url']); ?>"><?php echo esc_html($emp_cta['title']); ?></a>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- ============ 解决方案精选 ============ -->
<?php if ($solutions_query && $solutions_query->have_posts()) : ?>
	<section class="home-module">
		<div class="container">
			<?php
			get_template_part('template-parts/section-header', null, [
				'kicker'   => hireai_field('solutions_kicker', 'AI 解决方案'),
				'title'    => hireai_field('solutions_title', '臻选解决方案'),
				'subtitle' => hireai_field('solutions_subtitle'),
			]);
			?>
			<div class="hireai-product-grid">
				<?php
				while ($solutions_query->have_posts()) {
					$solutions_query->the_post();
					wc_get_template_part('content', 'product', ['cta_text' => $suffix === '_en' ? 'Explore More' : '探索更多']);
				}
				wp_reset_postdata();
				?>
			</div>
			<div class="section-head__row" style="justify-content:center;margin-top:48px;margin-bottom:0;">
				<a class="btn btn-secondary" href="<?php echo esc_url($sol_cta['url']); ?>"><?php echo esc_html($sol_cta['title']); ?></a>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- ============ 案例 & 洞察精选 ============ -->
<?php if ($cases_query->have_posts() || $insights_query->have_posts()) : ?>
	<section class="home-module">
		<div class="container">
			<?php
			get_template_part('template-parts/section-header', null, [
				'kicker'   => hireai_field('cases_kicker', '案例与洞察'),
				'title'    => hireai_field('cases_title', '案例与思考'),
				'subtitle' => hireai_field('cases_subtitle'),
			]);
			?>
			<?php if ($cases_query->have_posts()) : ?>
				<div class="grid grid--3">
					<?php
					while ($cases_query->have_posts()) {
						$cases_query->the_post();
						get_template_part('template-parts/post-card', null, [
							'cta_text' => hireai_field('card_cta_text', $suffix === '_en' ? 'Read More' : '阅读更多'),
						]);
					}
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>
			<?php if ($insights_query->have_posts()) : ?>
				<div class="grid grid--2" style="margin-top:40px;">
					<?php
					while ($insights_query->have_posts()) {
						$insights_query->the_post();
						get_template_part('template-parts/post-card', null, [
							'cta_text' => hireai_field('card_cta_text', $suffix === '_en' ? 'Read More' : '阅读更多'),
						]);
					}
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>
			<div class="section-head__row" style="justify-content:center;margin-top:48px;margin-bottom:0;">
				<a class="btn btn-secondary" href="<?php echo esc_url($case_cta['url']); ?>"><?php echo esc_html($case_cta['title']); ?></a>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- ============ FAQ 精选 ============ -->
<?php if ($faq_query->have_posts()) : ?>
	<section class="home-module">
		<div class="container">
			<?php
			get_template_part('template-parts/section-header', null, [
				'kicker'   => hireai_field('faq_kicker', '常见问题'),
				'title'    => hireai_field('faq_title', '疑问，即刻解答'),
				'subtitle' => hireai_field('faq_subtitle'),
			]);
			?>
			<div class="faq-list faq-list--center">
				<?php
				while ($faq_query->have_posts()) {
					$faq_query->the_post();
					?>
					<div class="faq-item">
						<button class="faq-item__toggle" aria-expanded="false">
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
			<div class="section-head__row" style="justify-content:center;margin-top:48px;margin-bottom:0;">
				<a class="btn btn-secondary" href="<?php echo esc_url($faq_cta['url']); ?>"><?php echo esc_html($faq_cta['title']); ?></a>
			</div>
		</div>
	</section>
<?php endif; ?>

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
