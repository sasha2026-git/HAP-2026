<?php if (!defined('ABSPATH')) exit;
get_header();
$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';
?>
<div class="container not-found">
	<div class="not-found__inner">
		<span class="label page-hero__kicker"><?php echo esc_html($is_en ? '404' : '404'); ?></span>
		<h1 class="display-lg"><?php echo esc_html($is_en ? 'Page Not Found' : '页面未找到'); ?></h1>
		<p class="body-lg"><?php echo esc_html($is_en ? 'The page you requested does not exist or has moved.' : '您访问的页面不存在或已迁移。'); ?></p>
		<a class="btn btn-ghost" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($is_en ? 'Back to Home' : '返回首页'); ?> <?php echo hireai_svg('arrow', 14); ?></a>
	</div>
</div>
<?php get_footer(); ?>
