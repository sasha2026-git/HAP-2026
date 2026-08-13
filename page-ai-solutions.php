<?php
if (!defined('ABSPATH')) exit;
/**
 * AI 解决方案页（WooCommerce 产品网格，按场景筛选，每页 9 个 + 分页）
 */
get_header();

$suffix = hireai_lang_suffix();
$paged  = max(1, get_query_var('paged'));

// 场景筛选配置：ACF repeater（可编辑），空则回退到四大场景
$filters = [];
if (function_exists('get_field')) {
    $filters_raw = get_field('solutions_filters');
    if (is_array($filters_raw)) {
        foreach ($filters_raw as $row) {
            if (!is_array($row)) {
                continue;
            }
            $label = ($suffix === '_en') ? (isset($row['filter_label_en']) ? $row['filter_label_en'] : '') : (isset($row['filter_label_zh']) ? $row['filter_label_zh'] : '');
            $slug  = isset($row['filter_slug']) ? $row['filter_slug'] : '';
            if ($slug !== '' && $label !== '') {
                $filters[] = ['label' => $label, 'slug' => $slug];
            }
        }
    }
}
if (empty($filters)) {
    $filters = ($suffix === '_en')
        ? [
            ['label' => 'Marketing', 'slug' => 'marketing'],
            ['label' => 'E-commerce', 'slug' => 'ecommerce'],
            ['label' => 'Design', 'slug' => 'design'],
            ['label' => 'PR', 'slug' => 'pr'],
        ]
        : [
            ['label' => '营销', 'slug' => 'marketing'],
            ['label' => '电商', 'slug' => 'ecommerce'],
            ['label' => '设计', 'slug' => 'design'],
            ['label' => '公关', 'slug' => 'pr'],
        ];
}
?>
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', 'AI 解决方案')); ?></span>
	<h1 class="headline-lg page-hero__title"><?php echo esc_html(hireai_field('header_title', '臻选智能方案')); ?></h1>
	<?php if (hireai_field('header_subtitle')) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('header_subtitle')); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<?php if (!class_exists('WooCommerce')) : ?>
			<p style="text-align:center;color:var(--color-text-muted);padding:80px 0;">
				<?php echo esc_html($suffix === '_en' ? 'Please activate the WooCommerce plugin to browse solutions.' : '请启用 WooCommerce 插件以浏览解决方案。'); ?>
			</p>
		<?php else : ?>
			<div class="solution-filters" role="group" aria-label="<?php echo esc_attr($suffix === '_en' ? 'Filter solutions' : '筛选解决方案'); ?>">
				<button class="chip is-active" type="button" data-filter="">
					<?php echo esc_html($suffix === '_en' ? 'All' : '全部'); ?>
				</button>
				<?php foreach ($filters as $f) : ?>
					<button class="chip" type="button" data-filter="<?php echo esc_attr($f['slug']); ?>"><?php echo esc_html($f['label']); ?></button>
				<?php endforeach; ?>
			</div>

			<?php
			$query = new WP_Query([
				'post_type'      => 'product',
				'posts_per_page' => HIREAI_SOLUTIONS_PER_PAGE,
				'paged'          => $paged,
			]);

			if ($query->have_posts()) :
				?>
				<div class="hireai-product-grid" id="solution-grid">
					<?php
					while ($query->have_posts()) {
						$query->the_post();
						wc_get_template_part('content', 'product', ['cta_text' => hireai_field('card_cta_text', $suffix === '_en' ? 'Explore More' : '探索更多')]);
					}
					wp_reset_postdata();
					?>
				</div>

				<p class="solution-empty" data-solution-empty>
					<?php echo esc_html(hireai_field('empty_text', $suffix === '_en' ? 'No solutions in this category yet.' : '该分类下暂无解决方案')); ?>
				</p>

				<?php hireai_pagination($query->max_num_pages, $paged); ?>
			<?php else : ?>
				<p style="text-align:center;color:var(--color-text-muted);padding:80px 0;">
					<?php echo esc_html($suffix === '_en' ? 'No solutions published yet.' : '暂无解决方案，敬请期待。'); ?>
				</p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
