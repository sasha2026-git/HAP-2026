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
?>

<footer class="site-footer">
	<div class="container footer-inner">
		<div class="footer-brand">
			<img class="footer-brand__logo" src="<?php echo esc_url($logo); ?>" alt="Hire AI People" style="height:40px;width:auto;">
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
