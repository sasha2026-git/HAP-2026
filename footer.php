<?php if (!defined('ABSPATH')) exit;
?>
</main>

<?php
$suffix    = hireai_lang_suffix();
$logo      = get_theme_mod('site_logo');
if (empty($logo)) {
    $logo = get_stylesheet_directory_uri() . '/assets/img/logo.png';
}
$copyright = hireai_field('footer_copyright', $suffix === '_en' ? '© 2026 Hire AI People. All rights reserved.' : '© 2026 聘AI（Hire AI People）。保留所有权利。', 'option');
$slogan    = hireai_field('footer_slogan', $suffix === '_en' ? 'Hire Intelligence, Artfully Employed.' : '雇佣智慧 · 臻于艺术', 'option');
$desc      = hireai_field('footer_desc', $suffix === '_en' ? 'A platform for AI digital employees and AI solutions—reshaping intelligent hiring with minimalist luxury.' : 'AI 数字员工与 AI 解决方案平台——以极简奢华之姿，重塑企业智能雇佣。', 'option');
?>

<footer class="site-footer">
	<div class="container footer-inner">
		<div class="footer-brand">
			<img class="footer-brand__logo" src="<?php echo esc_url($logo); ?>" alt="Hire AI People">
			<p class="footer-brand__slogan"><?php echo esc_html($slogan); ?></p>
			<p class="footer-brand__desc"><?php echo esc_html($desc); ?></p>
		</div>

		<nav class="footer-nav" aria-label="<?php echo esc_attr($suffix === '_en' ? 'Footer navigation' : '页脚导航'); ?>">
			<?php
			wp_nav_menu([
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'footer-nav__menu',
				'fallback_cb'    => 'hireai_fallback_footer_nav',
				'depth'          => 1,
			]);
			?>
		</nav>

		<p class="footer-copyright"><?php echo esc_html($copyright); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
