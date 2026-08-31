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

<?php
$suffix      = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '';
$is_en       = ($suffix === '_en');
$logo_url    = function_exists('hireai_image')
    ? hireai_image('header_logo', get_stylesheet_directory_uri() . '/assets/img/logo.png', 'option')
    : get_stylesheet_directory_uri() . '/assets/img/logo.png';

/* Language switcher label: hireai_field 双语 fallback */
$lang_label = function_exists('hireai_field')
    ? hireai_field('header_lang_label', $is_en ? '中 / EN' : 'EN / 中', 'option')
    : ($is_en ? '中 / EN' : 'EN / 中');

/* 品牌 / Logo aria-label 与 alt：hireai_field_lang 中英文 fallback */
$brand_label = function_exists('hireai_field_lang')
    ? hireai_field_lang('header_brand_label', $is_en ? 'en' : 'zh',
        $is_en ? 'Hire AI People' : '聘AI（Hire AI People）', 'option')
    : ($is_en ? 'Hire AI People' : '聘AI（Hire AI People）');

$logo_alt = function_exists('hireai_field_lang')
    ? hireai_field_lang('header_logo_alt', $is_en ? 'en' : 'zh',
        $is_en ? 'Hire AI People' : '聘AI（Hire AI People）', 'option')
    : ($is_en ? 'Hire AI People' : '聘AI（Hire AI People）');

/* 跳到主要内容：hireai_field_lang 中英文 fallback */
$skip_link_text = function_exists('hireai_field_lang')
    ? hireai_field_lang('header_skip_link', $is_en ? 'en' : 'zh',
        $is_en ? 'Skip to content' : '跳到主要内容', 'option')
    : ($is_en ? 'Skip to content' : '跳到主要内容');

/* 主导航 aria */
$nav_aria_primary = function_exists('hireai_field_lang')
    ? hireai_field_lang('header_nav_aria_primary', $is_en ? 'en' : 'zh',
        $is_en ? 'Primary navigation' : '主导航', 'option')
    : ($is_en ? 'Primary navigation' : '主导航');

/* 我的账户按钮：hireai_field_lang 中英文 fallback */
$account_label = function_exists('hireai_field_lang')
    ? hireai_field_lang('header_account_label', $is_en ? 'en' : 'zh',
        $is_en ? 'MY ACCOUNT' : '我的账户', 'option')
    : ($is_en ? 'MY ACCOUNT' : '我的账户');

/* 汉堡菜单按钮 aria */
$menu_toggle_aria = function_exists('hireai_field_lang')
    ? hireai_field_lang('header_menu_toggle_aria', $is_en ? 'en' : 'zh',
        $is_en ? 'Toggle menu' : '切换菜单', 'option')
    : ($is_en ? 'Toggle menu' : '切换菜单');

/* 关闭菜单按钮 aria */
$menu_close_aria = function_exists('hireai_field_lang')
    ? hireai_field_lang('header_menu_close_aria', $is_en ? 'en' : 'zh',
        $is_en ? 'Close menu' : '关闭菜单', 'option')
    : ($is_en ? 'Close menu' : '关闭菜单');

/* 移动端导航 aria */
$nav_aria_mobile = function_exists('hireai_field_lang')
    ? hireai_field_lang('header_nav_aria_mobile', $is_en ? 'en' : 'zh',
        $is_en ? 'Mobile navigation' : '移动端导航', 'option')
    : ($is_en ? 'Mobile navigation' : '移动端导航');
?>

<a class="skip-link screen-reader-text" href="#content"><?php echo esc_html($skip_link_text); ?></a>

<!-- Header: sticky-glass left-logo + horizontal nav + right actions -->
<header class="hai-header" id="site-header">
  <div class="hai-header__inner">
    <!-- Left: logo -->
    <a class="hai-header__brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php echo esc_attr($brand_label); ?>">
      <img class="hai-header__logo" src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>">
    </a>

    <!-- Center: main nav (desktop only) -->
    <nav class="hai-header__nav" aria-label="<?php echo esc_attr($nav_aria_primary); ?>">
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
        <?php echo esc_html($account_label); ?>
      </a>

      <button class="hai-header__lang" type="button" onclick="hireaiSwitchLang((localStorage.getItem('hireai_lang') || 'zh') === 'zh' ? 'en' : 'zh')">
        <?php echo esc_html($lang_label); ?>
      </button>

      <button class="hai-header__menu-toggle" id="nav-toggle" type="button" aria-label="<?php echo esc_attr($menu_toggle_aria); ?>" aria-expanded="false" aria-controls="mobile-drawer">
        <?php echo hireai_svg("menu", 24, "hai-header__icon"); ?>
      </button>
    </div>
  </div>
</header>

<!-- Mobile drawer overlay -->
<div class="hai-drawer-overlay" data-drawer-overlay hidden></div>
<!-- Mobile drawer -->
<aside class="mobile-drawer" id="mobile-drawer" data-mobile-drawer aria-hidden="true">
  <div class="mobile-drawer__head">
    <a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php echo esc_attr($brand_label); ?>">
      <img class="header-logo-img" src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>">
    </a>
    <button class="mobile-drawer__close" type="button" data-drawer-close aria-label="<?php echo esc_attr($menu_close_aria); ?>">
      <?php echo hireai_svg("close", 24, "hai-header__icon"); ?>
    </button>
  </div>
  <nav class="mobile-drawer__nav" aria-label="<?php echo esc_attr($nav_aria_mobile); ?>">
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
