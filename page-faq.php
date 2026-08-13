<?php
if (!defined('ABSPATH')) exit;
/**
 * 常见问题页：按 ACF 字段分组 + 关键词实时检索（纯 JS 前端过滤）
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
?>
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', '常见问题')); ?></span>
	<h1 class="headline-lg page-hero__title"><?php echo esc_html(hireai_field('header_title', '清晰以对')); ?></h1>
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

		<?php if (empty($groups)) : ?>
			<p style="text-align:center;color:var(--color-text-muted);padding:48px 0;">
				<?php echo esc_html($suffix === '_en' ? 'No FAQs published yet.' : '暂无常见问题，敬请期待。'); ?>
			</p>
		<?php else : ?>
			<?php foreach ($group_labels as $group_key => $group_label) : ?>
				<?php if (empty($groups[$group_key])) {
					continue;
				} ?>
				<div class="faq-group" data-faq-group="<?php echo esc_attr($group_key); ?>">
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

			<p class="faq-empty" data-faq-empty>
				<?php echo esc_html(hireai_field('empty_text', $suffix === '_en' ? 'No matching questions found. Try a different keyword.' : '未找到匹配的问题，请尝试其他关键词。')); ?>
			</p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
