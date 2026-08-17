<?php if (!defined('ABSPATH')) exit;
/**
 * 单产品页（奢华风格）— 参考 ai_aether_ai_light_luxe_4
 * 保留 WooCommerce 定价变体与购物车表单。
 */
get_header();

$suffix = hireai_lang_suffix();
$contact_page = get_page_by_path('contact');
$contact_url = $contact_page instanceof WP_Post ? get_permalink($contact_page) : home_url('/contact/');

while (have_posts()) :
	the_post();

	global $product;
	if (!$product) {
		$product = wc_get_product(get_the_ID());
	}

	$cat_list = $product ? wc_get_product_category_list($product->get_id(), ' · ') : '';
	$feature_1_title = hireai_field('product_feature_1_title', $suffix === '_en' ? 'Mitigating Brand Exposure' : '降低品牌曝光风险', get_the_ID());
	$feature_1_text  = hireai_field('product_feature_1_text', $suffix === '_en' ? 'Instantly formulate responses that protect brand equity.' : '即时形成保护品牌资产的高校准响应。', get_the_ID());
	$feature_2_title = hireai_field('product_feature_2_title', $suffix === '_en' ? 'Automating Neural Workflows' : '自动化神经工作流', get_the_ID());
	$feature_2_text  = hireai_field('product_feature_2_text', $suffix === '_en' ? 'Real-time sentiment analysis triggering automated draft protocols.' : '实时情绪分析并触发自动草拟协议。', get_the_ID());
	$retainer_label  = hireai_field('product_retainer_label', $suffix === '_en' ? 'Starting Retainer' : '起步档', get_the_ID());
	?>
	<div class="container">
		<div class="product-single">
			<div class="product-single__gallery" data-reveal>
				<?php woocommerce_show_product_images(); ?>
			</div>

			<div class="product-single__summary" data-reveal>
				<?php if ($cat_list) : ?>
					<span class="chip product-single__cat"><?php echo wp_kses_post($cat_list); ?></span>
				<?php endif; ?>

				<h1 class="display-lg product-single__title"><?php the_title(); ?></h1>

				<div class="product-single__price-line">
					<span class="product-single__retainer"><?php echo esc_html($retainer_label); ?></span>
					<?php woocommerce_template_single_price(); ?>
				</div>

				<div class="product-single__divider" aria-hidden="true"></div>

				<div class="product-single__excerpt">
					<?php the_excerpt(); ?>
				</div>

				<div class="product-single__features">
					<div class="product-feature">
						<span class="product-feature__icon" aria-hidden="true"></span>
						<div>
							<h4 class="product-feature__title"><?php echo esc_html($feature_1_title); ?></h4>
							<p class="product-feature__text"><?php echo esc_html($feature_1_text); ?></p>
						</div>
					</div>
					<div class="product-feature">
						<span class="product-feature__icon" aria-hidden="true"></span>
						<div>
							<h4 class="product-feature__title"><?php echo esc_html($feature_2_title); ?></h4>
							<p class="product-feature__text"><?php echo esc_html($feature_2_text); ?></p>
						</div>
					</div>
				</div>

				<?php woocommerce_template_single_add_to_cart(); ?>

				<div class="product-single__actions">
					<a class="btn btn-primary btn-lg" href="<?php echo esc_url($contact_url); ?>">
						<?php echo esc_html($suffix === '_en' ? 'Inquire for Bespoke Deployment' : '咨询专属部署'); ?>
					</a>
					<a class="btn btn-secondary btn-lg" href="<?php echo esc_url($contact_url); ?>">
						<?php echo esc_html($suffix === '_en' ? 'Download Brief' : '下载方案简介'); ?>
					</a>
				</div>

				<?php woocommerce_template_single_meta(); ?>
			</div>
		</div>
	</div>

	<div class="container">
		<?php
		woocommerce_related_products([
			'posts_per_page' => 3,
			'columns'        => 3,
			'orderby'        => 'rand',
		]);
		?>
	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
