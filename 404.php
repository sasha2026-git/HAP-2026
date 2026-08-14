<?php if (!defined('ABSPATH')) exit;
/**
 * 404 页面
 */
get_header();

$suffix = hireai_lang_suffix();
?>
<section class="error-404">
	<div class="container" data-reveal>
		<div class="error-404__code">404</div>
		<h1 class="error-404__title"><?php echo esc_html($suffix === '_en' ? 'Page Not Found' : '页面未找到'); ?></h1>
		<p class="error-404__desc">
			<?php echo esc_html($suffix === '_en' ? 'The page you are looking for has moved or never existed.' : '您访问的页面可能已被移动或不存在。'); ?>
		</p>
		<div class="cta-band__actions" style="justify-content:center;">
			<a class="btn btn-primary btn-lg" href="<?php echo esc_url(home_url('/')); ?>">
				<?php echo esc_html($suffix === '_en' ? 'Back to Home' : '返回首页'); ?>
			</a>
		</div>
	</div>
</section>

<?php get_footer(); ?>
