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
$is_en   = $suffix === '_en';
$logo_url = hireai_image('header_logo', get_stylesheet_directory_uri() . '/assets/img/logo.png', 'option');
/* Language switcher label */
$lang_label = $is_en ? '中 / EN' : 'EN / 中';
?>

<!-- Header: sticky-glass left-logo + horizontal nav + right actions -->
<header class="hai-header" id="site-header">
  <div class="hai-header__inner">
    <!-- Left: logo -->
    <a class="hai-header__brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="Hire AI People">
      <img class="hai-header__logo" src="<?php echo esc_url($logo_url); ?>" alt="Hire AI People">
    </a>

    <!-- Center: main nav (desktop only) -->
    <nav class="hai-header__nav" aria-label="<?php echo esc_attr($is_en ? 'Primary navigation' : '主导航'); ?>">
      <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'hai-header__nav-list',
        'fallback_cb'    => 'hireai_fallback_nav',
        'depth'          => 1,
      ]);
      ?>
    </nav>

    <!-- Right: actions -->
    <div class="hai-header__actions">
      <a class="hai-header__account"
         href="<?php echo esc_url(home_url('/my-account/')); ?>">
        <?php echo esc_html($is_en ? 'MY ACCOUNT' : '我的账户'); ?>
      </a>

      <button class="hai-header__lang" type="button" onclick="hireaiSwitchLang((localStorage.getItem('hireai_lang') || 'zh') === 'zh' ? 'en' : 'zh')">
        <?php echo esc_html($lang_label); ?>
      </button>

      <button class="hai-header__menu-toggle" id="nav-toggle" type="button" aria-label="<?php echo esc_attr($is_en ? 'Toggle menu' : '切换菜单'); ?>" aria-expanded="false" aria-controls="mobile-drawer">
        <span class="material-symbols-outlined hai-header__icon" aria-hidden="true">menu</span>
      </button>
    </div>
  </div>
</header>

<!-- Mobile drawer overlay -->
<div class="hai-drawer-overlay" data-drawer-overlay hidden></div>
<!-- Mobile drawer -->
<aside class="mobile-drawer" id="mobile-drawer" data-mobile-drawer aria-hidden="true">
  <div class="mobile-drawer__head">
    <a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="Hire AI People">
      <img class="header-logo-img" src="<?php echo esc_url($logo_url); ?>" alt="Hire AI People">
    </a>
    <button class="mobile-drawer__close" type="button" data-drawer-close aria-label="<?php echo esc_attr($is_en ? 'Close menu' : '关闭菜单'); ?>">
      <span class="material-symbols-outlined hai-header__icon" aria-hidden="true">close</span>
    </button>
  </div>
  <nav class="mobile-drawer__nav" aria-label="<?php echo esc_attr($is_en ? 'Mobile navigation' : '移动端导航'); ?>">
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
</aside>

<main id="content" class="site-main">
