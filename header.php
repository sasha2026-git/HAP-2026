<?php if (!defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content"><?php echo esc_html(hireai_lang_suffix() === '_en' ? 'Skip to content' : '跳到主要内容'); ?></a>

<?php
$suffix  = hireai_lang_suffix();
$logo    = get_theme_mod('site_logo');
$logo_h  = get_theme_mod('site_logo_height', 44);
if (empty($logo)) {
    $logo = get_stylesheet_directory_uri() . '/images/logo.png';
}
$logo_h = absint($logo_h) ? absint($logo_h) : 44;
?>
<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="Hire AI People">
			<img src="<?php echo esc_url($logo); ?>" alt="Hire AI People" style="height:<?php echo esc_attr($logo_h); ?>px">
		</a>

		<nav class="hireai-nav" id="primary-nav" aria-label="<?php echo esc_attr(hireai_lang_suffix() === '_en' ? 'Primary navigation' : '主导航'); ?>">
			<?php
			wp_nav_menu([
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'hireai-nav__menu',
				'fallback_cb'    => 'hireai_fallback_nav',
				'depth'          => 1,
			]);
			?>
		</nav>

		<div class="site-header__actions">
			<?php
			$hireai_contact_page = get_page_by_path('contact');
			$hireai_contact_url = $hireai_contact_page instanceof WP_Post ? get_permalink($hireai_contact_page) : home_url('/contact/');
			?>
			<a class="btn btn-secondary btn-header" href="<?php echo esc_url($hireai_contact_url); ?>"><?php echo esc_html($suffix === '_en' ? 'Consultation' : '预约咨询'); ?></a>
			<?php if (function_exists('pll_the_languages')) : ?>
				<div class="lang-switch">
					<?php pll_the_languages(['display_names_as' => 'slug', 'hide_current' => false, 'dropdown' => 0, 'hide_if_no_translation' => 0]); ?>
				</div>
			<?php endif; ?>

			<button class="nav-toggle" id="nav-toggle" aria-label="<?php echo esc_attr(hireai_lang_suffix() === '_en' ? 'Toggle menu' : '切换菜单'); ?>" aria-expanded="false" aria-controls="primary-nav">
				<span></span>
				<span></span>
				<span></span>
			</button>
		</div>
	</div>
</header>

<main id="content" class="site-main">
