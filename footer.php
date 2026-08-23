<?php if (!defined('ABSPATH')) exit;
?>
</main>

<?php
$suffix    = hireai_lang_suffix();
$logo_url  = hireai_image('footer_logo', get_stylesheet_directory_uri() . '/assets/img/logo.png', 'option');
$copyright = hireai_field('footer_copyright', $suffix === '_en' ? '© 2026 Hire AI People. All rights reserved.' : '© 2026 聘AI（Hire AI People）。保留所有权利。', 'option');
?>

<footer class="hai-footer" id="site-footer">
  <div class="hai-footer__inner">
    <!-- Centered logo -->
    <div class="hai-footer__logo-wrap">
      <img class="hai-footer__logo" src="<?php echo esc_url($logo_url); ?>" alt="Hire AI People">
    </div>

    <!-- Policy links: WP menu fallback to 4 hardcoded links -->
    <nav class="hai-footer__nav" aria-label="<?php echo esc_attr($suffix === '_en' ? 'Footer navigation' : '页脚导航'); ?>">
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
          $fallback_links = [
              'privacy-policy' => ($suffix === '_en') ? 'Privacy Policy' : '隐私政策',
              'terms'          => ($suffix === '_en') ? 'Terms of Service' : '服务条款',
              'refund-policy'  => ($suffix === '_en') ? 'Refund Policy' : '退换政策',
              'legal'          => ($suffix === '_en') ? 'Legal Notice' : '法律声明',
          ];
          foreach ($fallback_links as $slug => $label) {
              $page = get_page_by_path($slug);
              $url = $page instanceof WP_Post ? get_permalink($page) : '#';
              echo '<li><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
          }
          echo '</ul>';
      }
      ?>
    </nav>

    <!-- Social icons row with top/bottom borders -->
    <div class="hai-footer__social">
      <a class="hai-footer__social-link" href="#" aria-label="Public"><?php echo hireai_svg("public", 18, "hai-footer__social-icon"); ?></a>
      <a class="hai-footer__social-link" href="#" aria-label="Diamond"><?php echo hireai_svg("diamond", 18, "hai-footer__social-icon"); ?></a>
      <a class="hai-footer__social-link" href="#" aria-label="Token"><?php echo hireai_svg("token", 18, "hai-footer__social-icon"); ?></a>
    </div>

    <!-- Copyright -->
    <p class="hai-footer__copyright"><?php echo esc_html($copyright); ?></p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
