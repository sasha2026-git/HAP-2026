<?php
if (!defined('ABSPATH')) exit;
/**
 * 商店归档页（奢华风格）— 商店 / 商品分类 / 商品标签
 */
get_header();

$suffix = hireai_lang_suffix();
$paged  = max(1, get_query_var('paged'));
?>
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html($suffix === '_en' ? 'AI Solutions' : 'AI 解决方案'); ?></span>
	<h1 class="headline-lg page-hero__title"><?php woocommerce_page_title(); ?></h1>
	<?php if (is_product_category() || is_product_tag()) : ?>
		<?php $term_desc = term_description(); ?>
		<?php if ($term_desc) : ?>
			<p class="body-lg page-hero__subtitle"><?php echo esc_html(strip_tags($term_desc)); ?></p>
		<?php endif; ?>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<?php if (have_posts()) : ?>

			<div class="woocommerce-sorting" style="display:flex;flex-wrap:wrap;gap:24px;align-items:center;justify-content:space-between;margin-bottom:40px;">
				<?php woocommerce_result_count(); ?>
				<?php woocommerce_catalog_ordering(); ?>
			</div>

			<div class="hireai-product-grid">
				<?php
				while (have_posts()) :
					the_post();
					wc_get_template_part('content', 'product');
				endwhile;
				?>
			</div>

			<?php hireai_pagination($GLOBALS['wp_query']->max_num_pages, $paged); ?>

		<?php else : ?>
			<p style="text-align:center;color:var(--color-text-muted);padding:80px 0;">
				<?php echo esc_html($suffix === '_en' ? 'No products found.' : '暂无产品。'); ?>
			</p>
			<?php
			// 侧栏（默认商店小工具）
			do_action('woocommerce_sidebar');
			?>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
