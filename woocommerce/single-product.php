<?php if (!defined('ABSPATH')) exit;
/**
 * 单产品页（奢华风格）— 参考 ai_aether_ai_light_luxe_4
 * 保留 WooCommerce 定价变体与购物车表单。
 */
get_header();

$suffix = hireai_lang_suffix();
$contact_page = get_page_by_path('contact');
$contact_url = hireai_localized_url($contact_page instanceof WP_Post ? get_permalink($contact_page) : home_url('/contact/'));

// 主题 header 已打开 <main>；替换 WooCommerce 默认 wrapper，保留 hook 供兼容层注入。
$open_hireai_wc_scope = function () {
	echo '<div class="hireai-wc-scope">';
};
$close_hireai_wc_scope = function () {
	echo '</div>';
};
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', $open_hireai_wc_scope, 10);
add_action('woocommerce_after_main_content', $close_hireai_wc_scope, 10);
do_action('woocommerce_before_main_content');

while (have_posts()) :
	the_post();

	global $product;
	if (!$product instanceof WC_Product && function_exists('wc_setup_product_data')) {
		wc_setup_product_data(get_the_ID());
		global $product;
	}

	if (!$product instanceof WC_Product && function_exists('wc_get_product')) {
		$product = wc_get_product(get_the_ID());
	}

	if (!$product instanceof WC_Product) {
		continue;
	}

	$cat_list = $product ? wc_get_product_category_list($product->get_id(), ' · ') : '';
	$feature_1_title = hireai_field('product_feature_1_title', $suffix === '_en' ? 'Mitigating Brand Exposure' : '降低品牌曝光风险', get_the_ID());
	$feature_1_text  = hireai_field('product_feature_1_text', $suffix === '_en' ? 'Instantly formulate responses that protect brand equity.' : '即时形成保护品牌资产的高校准响应。', get_the_ID());
	$feature_2_title = hireai_field('product_feature_2_title', $suffix === '_en' ? 'Automating Neural Workflows' : '自动化神经工作流', get_the_ID());
	$feature_2_text  = hireai_field('product_feature_2_text', $suffix === '_en' ? 'Real-time sentiment analysis triggering automated draft protocols.' : '实时情绪分析并触发自动草拟协议。', get_the_ID());
	$retainer_label  = hireai_field('product_retainer_label', $suffix === '_en' ? 'Starting Retainer' : '起步档', get_the_ID());

	do_action('woocommerce_before_single_product');
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

				<div class="product-single__divider" aria-hidden="true"></div>

				<?php
				$render_retainer_with_price = function () use ($retainer_label) {
					echo '<div class="product-single__price-line"><span class="product-single__retainer">' . esc_html($retainer_label) . '</span>';
					woocommerce_template_single_price();
					echo '</div>';
				};
				$render_product_features = function () use ($feature_1_title, $feature_1_text, $feature_2_title, $feature_2_text) {
					?>
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
					<?php
				};
				$render_product_actions = function () use ($suffix, $contact_url) {
					?>
					<div class="product-single__actions">
						<a class="btn btn-primary btn-lg" href="<?php echo esc_url($contact_url); ?>">
							<?php echo esc_html($suffix === '_en' ? 'Inquire for Bespoke Deployment' : '咨询专属部署'); ?>
						</a>
						<a class="btn btn-secondary btn-lg" href="<?php echo esc_url($contact_url); ?>">
							<?php echo esc_html($suffix === '_en' ? 'Download Brief' : '下载方案简介'); ?>
						</a>
					</div>
					<?php
				};

				// WooCommerce 默认按 10/30/40/50/60 输出标题、价格、摘要、购买表单和元数据。
				// 这里保留模板 H1，并替换 30，保持“起步档 + 价格”的金色摘要排版。
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 10);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 30);
				add_action('woocommerce_single_product_summary', $render_retainer_with_price, 30);
				add_action('woocommerce_single_product_summary', $render_product_features, 45);
				add_action('woocommerce_single_product_summary', $render_product_actions, 55);
				do_action('woocommerce_single_product_summary');
				remove_action('woocommerce_single_product_summary', $render_retainer_with_price, 30);
				remove_action('woocommerce_single_product_summary', $render_product_features, 45);
				remove_action('woocommerce_single_product_summary', $render_product_actions, 55);
				?>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="product-single__description hireai-prose">
			<?php the_content(); ?>
		</div>

		<?php
		$render_related_products = function () {
			woocommerce_related_products([
				'posts_per_page' => 3,
				'columns'        => 3,
				'orderby'        => 'rand',
			]);
		};

		// 默认 hook 会重复输出 tabs/upsell；这里只保留当前商品的相关方案。
		remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
		remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
		remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		add_action('woocommerce_after_single_product_summary', $render_related_products, 20);
		do_action('woocommerce_after_single_product_summary');
		remove_action('woocommerce_after_single_product_summary', $render_related_products, 20);
		?>
	</div>

	<?php do_action('woocommerce_after_single_product'); ?>

<?php endwhile; ?>

<?php
do_action('woocommerce_after_main_content');
remove_action('woocommerce_before_main_content', $open_hireai_wc_scope, 10);
remove_action('woocommerce_after_main_content', $close_hireai_wc_scope, 10);
get_footer();
?>
