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
$suffix = hireai_lang_suffix();
$logo_url = get_stylesheet_directory_uri() . '/assets/img/logo.png';
$contact_page  = get_page_by_path('contact');
$contact_url   = $contact_page instanceof WP_Post ? get_permalink($contact_page) : home_url('/contact/');
$consult_label = $suffix === '_en' ? 'Consultation' : '预约咨询';
?>

<header class="site-header" id="site-header">
	<div class="container header-inner">
		<a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="Hire AI People">
			<img src="<?php echo esc_url($logo_url); ?>" alt="Hire AI People" style="height:46px !important;width:auto !important;display:block;">
		</a>

		<nav class="desktop-nav" aria-label="<?php echo esc_attr($suffix === '_en' ? 'Primary navigation' : '主导航'); ?>">
			<?php
			wp_nav_menu([
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'desktop-nav__menu',
				'fallback_cb'    => 'hireai_fallback_nav',
				'depth'          => 1,
			]);
			?>
		</nav>

		<div class="header-actions">
			<a class="btn-consult" href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html($consult_label); ?></a>

			<div class="header-lang">
				<button class="lang-btn" id="hireai-lang-zh" onclick="hireaiSwitchLang('zh')">CN</button>
				<button class="lang-btn" id="hireai-lang-en" onclick="hireaiSwitchLang('en')">EN</button>
			</div>

			<button class="nav-toggle" id="nav-toggle" type="button" aria-label="<?php echo esc_attr($suffix === '_en' ? 'Toggle menu' : '切换菜单'); ?>" aria-expanded="false" aria-controls="mobile-drawer">
				<?php echo hireai_svg('menu', 24, 'hireai-icon nav-toggle__open'); ?>
				<?php echo hireai_svg('close', 24, 'hireai-icon nav-toggle__close'); ?>
			</button>
		</div>
	</div>
</header>

<div class="drawer-overlay" data-drawer-overlay hidden></div>
<aside class="mobile-drawer" id="mobile-drawer" data-mobile-drawer aria-hidden="true">
	<div class="mobile-drawer__head">
		<a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="Hire AI People">
			<img src="<?php echo esc_url($logo_url); ?>" alt="Hire AI People" style="height:46px !important;width:auto !important;display:block;">
		</a>
		<button class="mobile-drawer__close" type="button" data-drawer-close aria-label="<?php echo esc_attr($suffix === '_en' ? 'Close menu' : '关闭菜单'); ?>">
			<?php echo hireai_svg('close', 24); ?>
		</button>
	</div>
	<nav class="mobile-drawer__nav" aria-label="<?php echo esc_attr($suffix === '_en' ? 'Mobile navigation' : '移动端导航'); ?>">
		<?php
		wp_nav_menu([
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'mobile-drawer__menu',
			'fallback_cb'    => 'hireai_fallback_nav',
			'depth'          => 1,
		]);
		?>
	</nav>
	<a class="btn-consult mobile-drawer__cta" href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html($consult_label); ?></a>
</aside>

<main id="content" class="site-main">
