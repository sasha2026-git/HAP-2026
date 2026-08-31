<?php if (!defined('ABSPATH')) exit;
?>
</main>

<?php
$suffix    = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '';
$is_en     = ($suffix === '_en');
$logo_url  = function_exists('hireai_image')
    ? hireai_image('footer_logo', get_stylesheet_directory_uri() . '/assets/img/logo.png', 'option')
    : get_stylesheet_directory_uri() . '/assets/img/logo.png';
$copyright = function_exists('hireai_field')
    ? hireai_field('footer_copyright',
        $is_en ? '© 2026 Hire AI People. All rights reserved.' : '© 2026 聘AI（Hire AI People）。保留所有权利。',
        'option')
    : ($is_en ? '© 2026 Hire AI People. All rights reserved.' : '© 2026 聘AI（Hire AI People）。保留所有权利。');

/* 页脚导航 aria */
$footer_nav_aria = function_exists('hireai_field_lang')
    ? hireai_field_lang('footer_nav_aria', $is_en ? 'en' : 'zh',
        $is_en ? 'Footer navigation' : '页脚导航', 'option')
    : ($is_en ? 'Footer navigation' : '页脚导航');

/* 社交图标 aria 标签（中英文双 fallback） */
$social_public_aria = function_exists('hireai_field_lang')
    ? hireai_field_lang('footer_social_public_aria', $is_en ? 'en' : 'zh',
        $is_en ? 'Public' : '公开', 'option')
    : ($is_en ? 'Public' : '公开');
$social_diamond_aria = function_exists('hireai_field_lang')
    ? hireai_field_lang('footer_social_diamond_aria', $is_en ? 'en' : 'zh',
        $is_en ? 'Diamond' : '钻石', 'option')
    : ($is_en ? 'Diamond' : '钻石');
$social_token_aria = function_exists('hireai_field_lang')
    ? hireai_field_lang('footer_social_token_aria', $is_en ? 'en' : 'zh',
        $is_en ? 'Token' : '代币', 'option')
    : ($is_en ? 'Token' : '代币');

/* 4 个法务链接的 label + url（hireai_field_lang 中英文 fallback） */
$fallback_links = [
    'privacy-policy' => [
        'label' => function_exists('hireai_field_lang')
            ? hireai_field_lang('footer_privacy_label', $is_en ? 'en' : 'zh',
                $is_en ? 'Privacy Policy' : '隐私政策', 'option')
            : ($is_en ? 'Privacy Policy' : '隐私政策'),
        'url'   => function_exists('hireai_field_lang')
            ? hireai_field_lang('footer_privacy_url', $is_en ? 'en' : 'zh',
                $is_en ? '/privacy-policy/' : '/privacy-policy/', 'option')
            : '/privacy-policy/',
    ],
    'terms' => [
        'label' => function_exists('hireai_field_lang')
            ? hireai_field_lang('footer_terms_label', $is_en ? 'en' : 'zh',
                $is_en ? 'Terms of Service' : '服务条款', 'option')
            : ($is_en ? 'Terms of Service' : '服务条款'),
        'url'   => function_exists('hireai_field_lang')
            ? hireai_field_lang('footer_terms_url', $is_en ? 'en' : 'zh',
                $is_en ? '/terms/' : '/terms/', 'option')
            : '/terms/',
    ],
    'refund-policy' => [
        'label' => function_exists('hireai_field_lang')
            ? hireai_field_lang('footer_refund_label', $is_en ? 'en' : 'zh',
                $is_en ? 'Refund Policy' : '退换政策', 'option')
            : ($is_en ? 'Refund Policy' : '退换政策'),
        'url'   => function_exists('hireai_field_lang')
            ? hireai_field_lang('footer_refund_url', $is_en ? 'en' : 'zh',
                $is_en ? '/refund-policy/' : '/refund-policy/', 'option')
            : '/refund-policy/',
    ],
    'legal' => [
        'label' => function_exists('hireai_field_lang')
            ? hireai_field_lang('footer_legal_label', $is_en ? 'en' : 'zh',
                $is_en ? 'Legal Notice' : '法律声明', 'option')
            : ($is_en ? 'Legal Notice' : '法律声明'),
        'url'   => function_exists('hireai_field_lang')
            ? hireai_field_lang('footer_legal_url', $is_en ? 'en' : 'zh',
                $is_en ? '/legal/' : '/legal/', 'option')
            : '/legal/',
    ],
];

/* 用 hireai_link_lang 解析（如果有自定义链接则优先 ACF） */
function hireai_footer_resolve_link($slug, $fallback_url) {
    $field = 'footer_link_' . str_replace('-', '_', $slug);
    if (function_exists('hireai_link_lang')) {
        $is_en = (function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en');
        $r = hireai_link_lang($field, $is_en ? 'en' : 'zh', $fallback_url, '', 'option');
        if (!empty($r['url']) && $r['url'] !== '#') return $r['url'];
        if (!empty($r['url'])) return $r['url'];
    }
    $page = get_page_by_path($slug);
    return $page instanceof WP_Post ? get_permalink($page) : $fallback_url;
}
?>

<footer class="hai-footer" id="site-footer">
  <div class="hai-footer__inner">
    <!-- Centered logo -->
    <div class="hai-footer__logo-wrap">
      <img class="hai-footer__logo" src="<?php echo esc_url($logo_url); ?>" alt="Hire AI People">
    </div>

    <!-- Policy links: WP menu fallback to 4 hardcoded links (多语言字段驱动) -->
    <nav class="hai-footer__nav" aria-label="<?php echo esc_attr($footer_nav_aria); ?>">
      <?php
      $has_footer_menu = has_nav_menu('footer');
      if ($has_footer_menu) {
          wp_nav_menu([
              'theme_location' => 'footer',
              'container'      => false,
              'menu_class'     => 'hai-footer__nav-list',
              'fallback_cb'    => 'hireai_fallback_footer_nav',
              'depth'          => 1,
          ]);
      } else {
          echo '<ul class="hai-footer__nav-list">';
          foreach ($fallback_links as $slug => $item) {
              $url = hireai_footer_resolve_link($slug, $item['url']);
              echo '<li><a href="' . esc_url($url) . '">' . esc_html($item['label']) . '</a></li>';
          }
          echo '</ul>';
      }
      ?>
    </nav>

    <!-- Social icons row with top/bottom borders (aria-labels 多语言驱动) -->
    <div class="hai-footer__social">
      <a class="hai-footer__social-link" href="#" aria-label="<?php echo esc_attr($social_public_aria); ?>"><?php echo hireai_svg("public", 18, "hai-footer__social-icon"); ?></a>
      <a class="hai-footer__social-link" href="#" aria-label="<?php echo esc_attr($social_diamond_aria); ?>"><?php echo hireai_svg("diamond", 18, "hai-footer__social-icon"); ?></a>
      <a class="hai-footer__social-link" href="#" aria-label="<?php echo esc_attr($social_token_aria); ?>"><?php echo hireai_svg("token", 18, "hai-footer__social-icon"); ?></a>
    </div>

    <!-- Copyright -->
    <p class="hai-footer__copyright"><?php echo esc_html($copyright); ?></p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
