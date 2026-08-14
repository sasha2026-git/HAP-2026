<?php if (!defined('ABSPATH')) exit;

/* -------------------------------------------------------------------------
 * 0.0 GitHub 自动更新（plugin-update-checker）
 *     发布新版本到 GitHub Release 后，WP 后台 外观→主题 会提示一键更新
 * ---------------------------------------------------------------------- */
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

if (!defined('HIREAI_SKIP_UPDATE_CHECKER')) {
    require_once __DIR__ . '/lib/plugin-update-checker.php';
    $hireai_update_checker = PucFactory::buildUpdateChecker(
        'https://github.com/sasha2026-git/HAP-2026/',
        get_stylesheet_directory() . '/style.css',
        'hireaipeople'
    );
    $hireai_update_checker->setBranch('site-hireai');
}

/**
 * 聘AI (Hire AI People) Child — Hello Elementor 子主题
 * functions.php
 *
 * 职责：资源加载 / 导航 / WooCommerce 支持 / ACF 字段注册（双语方案 B）/
 *       辅助函数回退 / 联系表单处理 / 分页
 */

define('HIREAI_VERSION', '1.0.9');

/* 每页数量（可通过常量覆盖） */
define('HIREAI_EMPLOYEES_PER_PAGE', 5);
define('HIREAI_SOLUTIONS_PER_PAGE', 9);
define('HIREAI_CASES_PER_PAGE', 6);
define('HIREAI_INSIGHTS_PER_PAGE', 3);

/* -------------------------------------------------------------------------
 * 0.0 默认页面 / 菜单初始化（后台模板下拉与 ACF 编辑区可见性）
 * ---------------------------------------------------------------------- */
function hireai_ensure_default_pages() {
    $main_pages = [
        'home'           => ['title' => '首页', 'template' => 'front-page.php'],
        'ai-employees'   => ['title' => 'AI数字员工', 'template' => 'page-ai-employees.php'],
        'ai-solutions'   => ['title' => 'AI解决方案', 'template' => 'page-ai-solutions.php'],
        'cases-insights' => ['title' => '案例&洞察', 'template' => 'page-cases-insights.php'],
        'faq'            => ['title' => '常见问题', 'template' => 'page-faq.php'],
        'contact'        => ['title' => '联系', 'template' => 'page-contact.php'],
    ];

    $footer_pages = [
        'privacy-policy' => '隐私政策',
        'terms'          => '服务条款',
        'refund-policy'  => '退换货政策',
        'legal'          => '法律声明',
    ];

    $main_page_ids = [];

    foreach ($main_pages as $slug => $page) {
        $existing = get_page_by_path($slug, OBJECT, 'page');
        $page_id  = $existing instanceof WP_Post ? (int) $existing->ID : 0;

        if (!$page_id) {
            $inserted = wp_insert_post([
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_title'   => $page['title'],
                'post_name'    => $slug,
                'post_content' => '',
            ], true);

            if (!$inserted || is_wp_error($inserted)) {
                continue;
            }

            $page_id = (int) $inserted;
        }

        update_post_meta($page_id, '_wp_page_template', $page['template']);
        $main_page_ids[$slug] = $page_id;
    }

    foreach ($footer_pages as $slug => $title) {
        $existing = get_page_by_path($slug, OBJECT, 'page');
        if ($existing instanceof WP_Post) {
            continue;
        }

        $inserted = wp_insert_post([
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_content' => '',
        ], true);

        if (!$inserted || is_wp_error($inserted)) {
            continue;
        }
    }

    if (!empty($main_page_ids['home'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', (int) $main_page_ids['home']);
    }

    $menu = wp_get_nav_menu_object('主导航');
    if (!$menu) {
        $menu = get_term_by('name', '主导航', 'nav_menu');
    }
    if ($menu instanceof WP_Term) {
        $menu_id = (int) $menu->term_id;
    } else {
        $menu_id = wp_create_nav_menu('主导航');
    }

    if (is_wp_error($menu_id)) {
        return;
    }

    $existing_items      = wp_get_nav_menu_items($menu_id);
    $existing_page_ids   = [];
    if (is_array($existing_items)) {
        foreach ($existing_items as $item) {
            if (!($item instanceof WP_Post)) {
                continue;
            }
            $object_id = (int) get_post_meta($item->ID, '_menu_item_object_id', true);
            $item_type = get_post_meta($item->ID, '_menu_item_type', true);
            if ($object_id > 0 && $item_type === 'post_type') {
                $existing_page_ids[$object_id] = true;
            }
        }
    }

    $nav_page_slugs = ['home', 'ai-employees', 'ai-solutions', 'cases-insights', 'faq', 'contact'];
    foreach ($nav_page_slugs as $slug) {
        if (empty($main_page_ids[$slug])) {
            continue;
        }

        $page_id = (int) $main_page_ids[$slug];
        if (isset($existing_page_ids[$page_id])) {
            continue;
        }

        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'     => $main_pages[$slug]['title'],
            'menu-item-object'    => 'page',
            'menu-item-object-id' => $page_id,
            'menu-item-type'      => 'post_type',
            'menu-item-status'    => 'publish',
        ]);
    }

    $locations = get_theme_mod('nav_menu_locations', []);
    if (!is_array($locations)) {
        $locations = [];
    }
    $locations['primary'] = (int) $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
}

function hireai_ensure_default_pages_once() {
    if (get_option('hireai_pages_auto_created')) {
        return;
    }

    hireai_ensure_default_pages();
    update_option('hireai_pages_auto_created', 1);
}

add_action('after_switch_theme', 'hireai_ensure_default_pages');
add_action('admin_init', 'hireai_ensure_default_pages_once');

/* -------------------------------------------------------------------------
 * 0. 辅助函数（带默认值回退，ACF 未装时优雅降级）
 * ---------------------------------------------------------------------- */

/**
 * 当前语言后缀（双语方案 B）：无 Polylang 时默认 zh
 */
function hireai_lang_suffix() {
    if (function_exists('pll_current_language') && pll_current_language() === 'en') {
        return '_en';
    }
    return '_zh';
}

/**
 * 取 ACF 字段（自动追加语言后缀），带默认值回退
 *
 * @param string $name     字段基础名（不含语言后缀）
 * @param mixed  $default  默认值（无 ACF 或字段为空时返回）
 * @param mixed  $post_id  可选 post_id / 'option'
 * @return mixed
 */
function site_field($name, $default = '', $post_id = false) {
    if (function_exists('get_field')) {
        $v = $post_id ? get_field($name, $post_id) : get_field($name);
        if ($v !== null && $v !== '' && $v !== false && !(is_array($v) && empty($v))) {
            return $v;
        }
    }
    return $default;
}

/**
 * 取语言化字段：site_field( $name . hireai_lang_suffix(), ... )
 */
function hireai_field($name, $default = '', $post_id = false) {
    return site_field($name . hireai_lang_suffix(), $default, $post_id);
}

/**
 * 取图片 URL，兼容 ACF image 三种 return_format（数组/ID/URL）
 */
function site_image_url($name, $default = '', $post_id = false) {
    if (function_exists('get_field')) {
        $v = $post_id ? get_field($name, $post_id) : get_field($name);
        if (is_array($v) && !empty($v['url'])) {
            return $v['url'];
        }
        if (is_numeric($v) && intval($v) > 0) {
            $url = wp_get_attachment_image_url(intval($v), 'full');
            return $url ? $url : $default;
        }
        if (is_string($v) && $v !== '') {
            return $v;
        }
    }
    return $default;
}

/**
 * 取语言化图片 URL
 */
function hireai_image($name, $default = '', $post_id = false) {
    return site_image_url($name . hireai_lang_suffix(), $default, $post_id);
}

/**
 * 取 ACF link 字段，统一返回 ['url','title','target']
 */
function site_link($name, $default_url = '#', $default_title = '', $post_id = false) {
    if (function_exists('get_field')) {
        $v = $post_id ? get_field($name, $post_id) : get_field($name);
        if (is_array($v) && !empty($v['url'])) {
            return [
                'url'    => $v['url'],
                'title'  => ($v['title'] !== '' && $v['title'] !== null) ? $v['title'] : $default_title,
                'target' => isset($v['target']) ? $v['target'] : '',
            ];
        }
        if (is_string($v) && $v !== '') {
            return ['url' => $v, 'title' => $default_title, 'target' => ''];
        }
    }
    return ['url' => $default_url, 'title' => $default_title, 'target' => ''];
}

/**
 * 取语言化链接
 */
function hireai_link($name, $default_url = '#', $default_title = '', $post_id = false) {
    return site_link($name . hireai_lang_suffix(), $default_url, $default_title, $post_id);
}

/**
 * 本地 SVG 图标（避免任何外部字体/图标 CDN 依赖）
 */
/**
 * 本地默认图 URL：存在时返回 assets/img/defaults/ 下的素材，不存在返回空串。
 */
function hireai_default_image($name = '') {
    $name = sanitize_file_name($name);
    if ($name === '') {
        return '';
    }
    $path = get_stylesheet_directory() . '/assets/img/defaults/' . $name;
    if (file_exists($path)) {
        return get_stylesheet_directory_uri() . '/assets/img/defaults/' . $name;
    }
    return '';
}

function hireai_svg($name = 'arrow', $size = 16, $class = 'hireai-icon') {
    $icons = [
        'menu'   => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'close'  => '<path d="M6 6l12 12M18 6L6 18"/>',
        'arrow'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'east'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'west'   => '<path d="M19 12H5M11 6l-6 6 6 6"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>',
        'plus'   => '<path d="M12 5v14M5 12h14"/>',
        'mail'   => '<rect x="3" y="5" width="18" height="14" rx="1"/><path d="M3 7l9 6 9-6"/>',
        'chevron-left'  => '<path d="M15 6l-6 6 6 6"/>',
        'chevron-right' => '<path d="M9 6l6 6-6 6"/>',
        'shield' => '<path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/>',
        'bolt'   => '<path d="M13 2L4 14h6l-1 8 9-12h-6l1-8z"/>',
        'image'  => '<rect x="3" y="4" width="18" height="16" rx="1"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="M21 15l-5-5-9 9"/>',
    ];

    $paths = isset($icons[$name]) ? $icons[$name] : $icons['arrow'];
    return '<svg class="' . esc_attr($class) . '" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths . '</svg>';
}

/**
 * 联系页模板对应页面 ID（表单处理器读取页面字段用）
 */
function hireai_contact_page_id() {
    static $id = null;
    if ($id === null) {
        $pages = get_posts([
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'meta_key'       => '_wp_page_template',
            'meta_value'     => 'page-contact.php',
            'fields'         => 'ids',
        ]);
        $id = !empty($pages) ? intval($pages[0]) : 0;
    }
    return $id;
}

/* -------------------------------------------------------------------------
 * 1. 资源加载：父主题 + 子主题样式（自托管字体）+ 脚本
 * ---------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {
    // 父主题样式（只加载一次）
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // 子主题样式
    wp_enqueue_style(
        'hireaipeople-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['parent-style'],
        HIREAI_VERSION
    );

    // 字体已本地化：@font-face 在 style.css 顶部加载 assets/fonts/*.woff2，不再请求 Google Fonts。
    // 前端脚本
    wp_enqueue_script(
        'hireaipeople-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [],
        HIREAI_VERSION,
        true
    );

    // 把语言后缀传给 JS（FAQ 空文案等按语言取用）
    wp_localize_script('hireaipeople-main', 'HireAI', [
        'lang' => hireai_lang_suffix(),
    ]);
});

/* -------------------------------------------------------------------------
 * 2. 主题支持 + 导航菜单
 * ---------------------------------------------------------------------- */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('automatic-feed-links');

    // WooCommerce 支持
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('wc-product-gallery-lightbox');

    // 卡片裁剪尺寸（4:3）
    add_image_size('hireai-card', 900, 675, true);
    add_image_size('hireai-wide', 1440, 640, true);

    // 导航菜单
    register_nav_menus([
        'primary' => '主导航',
        'footer'  => '页脚导航',
    ]);
});

/* -------------------------------------------------------------------------
 * 3. 分页函数（paginate_links 奢华风格）
 *    说明：FAQ 以「文章」形式发布（category=faq），分组用 ACF 字段 faq_group。
 * ---------------------------------------------------------------------- */
function hireai_pagination($total = 0, $current = 0) {
    if ($total <= 1) {
        return;
    }
    if (!$current) {
        $current = max(1, get_query_var('paged'));
    }
    $suffix = hireai_lang_suffix();
    $prev   = ($suffix === '_en') ? 'Prev' : '上一页';
    $next   = ($suffix === '_en') ? 'Next' : '下一页';
    $big    = 999999999;

    $links = paginate_links([
        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'    => '?paged=%#%',
        'current'   => $current,
        'total'     => $total,
        'prev_text' => '← ' . $prev,
        'next_text' => $next . ' →',
        'type'      => 'list',
    ]);

    if ($links) {
        echo '<nav class="hireai-pagination" role="navigation" aria-label="pagination">' . wp_kses_post($links) . '</nav>';
    }
}

/* -------------------------------------------------------------------------
 * 5. 导航回退（未设置菜单时按固定顺序输出六个页面）
 * ---------------------------------------------------------------------- */
function hireai_fallback_nav() {
    $lang_suffix = hireai_lang_suffix();
    $items       = [
        ['slug' => '', 'label' => ($lang_suffix === '_en') ? 'Home' : '首页'],
        ['slug' => 'ai-employees', 'label' => ''],
        ['slug' => 'ai-solutions', 'label' => ''],
        ['slug' => 'cases-insights', 'label' => ''],
        ['slug' => 'faq', 'label' => ''],
        ['slug' => 'contact', 'label' => ''],
    ];
    $current_url = trailingslashit(esc_url(home_url(add_query_arg([], $GLOBALS['wp']->request))));
    echo '<ul class="hireai-nav__menu">';
    foreach ($items as $item) {
        if ($item['slug'] === '') {
            $url   = home_url('/');
            $label = $item['label'];
            $is_current = (home_url('/') === $current_url) || (is_front_page());
        } else {
            $page = get_page_by_path($item['slug']);
            if (!$page) {
                continue;
            }
            $url   = get_permalink($page);
            $label = $item['label'] !== '' ? $item['label'] : get_the_title($page);
            $is_current = (trailingslashit($url) === $current_url);
        }
        $class = $is_current ? 'menu-item current-menu-item' : 'menu-item';
        echo '<li class="' . esc_attr($class) . '"><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}

function hireai_fallback_footer_nav() {
    $slugs = ['privacy-policy', 'terms', 'refund-policy', 'legal'];
    echo '<ul class="footer-menu">';
    foreach ($slugs as $slug) {
        $page = get_page_by_path($slug);
        if (!$page) {
            continue;
        }
        echo '<li><a href="' . esc_url(get_permalink($page)) . '">' . esc_html(get_the_title($page)) . '</a></li>';
    }
    echo '</ul>';
}

/* -------------------------------------------------------------------------
 * 6. Customizer：Logo + 页脚文字（get_theme_mod 兜底）
 * ---------------------------------------------------------------------- */
add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_setting('site_logo', ['default' => '', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'site_logo', [
        'label'   => 'Logo 图片（默认 images/logo.png）',
        'section' => 'title_tagline',
    ]));

    $wp_customize->add_setting('site_logo_height', ['default' => '44', 'sanitize_callback' => 'absint']);
    $wp_customize->add_control('site_logo_height', [
        'label'   => 'Logo 高度（px）',
        'section' => 'title_tagline',
        'type'    => 'number',
    ]);

    $wp_customize->add_setting('footer_copyright_zh', ['default' => '', 'sanitize_callback' => 'wp_kses_post']);
    $wp_customize->add_control('footer_copyright_zh', [
        'label'   => '页脚版权（中文）',
        'section' => 'title_tagline',
    ]);

    $wp_customize->add_setting('footer_copyright_en', ['default' => '', 'sanitize_callback' => 'wp_kses_post']);
    $wp_customize->add_control('footer_copyright_en', [
        'label'   => '页脚版权（EN）',
        'section' => 'title_tagline',
    ]);
});

/* -------------------------------------------------------------------------
 * 7. 联系表单处理（admin-post.php，nonce 验证 + wp_mail）
 * ---------------------------------------------------------------------- */
add_action('admin_post_hireai_contact', 'hireai_handle_contact');
add_action('admin_post_nopriv_hireai_contact', 'hireai_handle_contact');

function hireai_handle_contact() {
    $lang_suffix = hireai_lang_suffix();
    $page_id     = hireai_contact_page_id();
    $redirect    = wp_get_referer() ? wp_get_referer() : home_url('/');

    // nonce 验证
    if (!isset($_POST['hireai_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hireai_nonce'])), 'hireai_contact')) {
        wp_safe_redirect(add_query_arg('sent', 'error', $redirect));
        exit;
    }

    // honeypot 蜜罐：正常用户不会填写
    if (!empty($_POST['website'])) {
        wp_safe_redirect(add_query_arg('sent', 'success', $redirect));
        exit;
    }

    $name    = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $company = isset($_POST['company']) ? sanitize_text_field(wp_unslash($_POST['company'])) : '';
    $email   = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';

    if ($name === '' || !is_email($email) || $message === '') {
        wp_safe_redirect(add_query_arg('sent', 'invalid', $redirect));
        exit;
    }

    $to = site_field('contact_email' . $lang_suffix, get_option('admin_email'), $page_id);
    if (!is_email($to)) {
        $to = get_option('admin_email');
    }

    $subject = sprintf('【聘AI】新的咨询：%s', $name);
    $body    = "姓名 / Name: {$name}\n";
    if ($company !== '') {
        $body .= "公司 / Company: {$company}\n";
    }
    $body   .= "邮箱 / Email: {$email}\n";
    $body   .= "------------------------------------\n";
    $body   .= wp_strip_all_tags($message) . "\n";
    $headers = ['Reply-To: ' . $email];

    $sent = wp_mail($to, $subject, $body, $headers);
    wp_safe_redirect(add_query_arg('sent', $sent ? 'success' : 'error', $redirect));
    exit;
}

/* -------------------------------------------------------------------------
 * 8. 摘要长度
 * ---------------------------------------------------------------------- */
add_filter('excerpt_length', function () {
    return 32;
});

add_filter('excerpt_more', function () {
    return '…';
});

/* -------------------------------------------------------------------------
 * 9. ACF 字段注册（acf/init + function_exists 双重保护）
 *    双语方案 B：每个内容块 xxx_zh + xxx_en，两组 Tab。
 * ---------------------------------------------------------------------- */
add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    /* ---- 辅助：生成 zh/en 双 Tab 字段组 ---- */
    $hireai_make_group = function ($key, $title, $fields, $location) {
        $out = [];
        $out[] = ['key' => $key . '_tab_zh', 'label' => '中文内容', 'type' => 'tab'];

        foreach ($fields as $f) {
            $fld = [
                'key'           => $key . '_' . str_replace(' ', '_', $f['name']) . '_zh',
                'label'         => isset($f['label_zh']) ? $f['label_zh'] : $f['label'],
                'name'          => $f['name'] . '_zh',
                'type'          => $f['type'],
                'default_value' => isset($f['zh']) ? $f['zh'] : '',
            ];
            if (!empty($f['extra'])) {
                $fld = array_merge($fld, $f['extra']);
            }
            $out[] = $fld;
        }

        $out[] = ['key' => $key . '_tab_en', 'label' => 'English Content', 'type' => 'tab'];

        foreach ($fields as $f) {
            $fld = [
                'key'           => $key . '_' . str_replace(' ', '_', $f['name']) . '_en',
                'label'         => isset($f['label_en']) ? $f['label_en'] : $f['label'],
                'name'          => $f['name'] . '_en',
                'type'          => $f['type'],
                'default_value' => isset($f['en']) ? $f['en'] : '',
            ];
            if (!empty($f['extra'])) {
                $fld = array_merge($fld, $f['extra']);
            }
            $out[] = $fld;
        }

        return [
            'key'      => $key,
            'title'    => $title,
            'fields'   => $out,
            'location' => $location,
        ];
    };

    /* ---- 1. 首页 Hero ---- */
    acf_add_local_field_group($hireai_make_group('group_front_hero', '首页 — Hero 区域', [
        [
            'name' => 'hero_kicker', 'label' => '眉题（kicker）', 'type' => 'text',
            'zh' => 'HIRE AI PEOPLE', 'en' => 'HIRE AI PEOPLE',
        ],
        [
            'name' => 'hero_title', 'label' => '大标题', 'type' => 'textarea',
            'zh' => "智慧雇佣，\n臻于艺术。", 'en' => "Hire Intelligence,\nArtfully Employed.",
            'extra' => ['rows' => 2],
        ],
        [
            'name' => 'hero_subtitle', 'label' => '副标题', 'type' => 'textarea',
            'zh' => '聘AI 为企业雇聘专属 AI 数字员工与解决方案——以工匠精神雕琢算法，以静谧之力驱动增长。',
            'en' => 'HireAI People employs bespoke AI digital employees and solutions—crafted with artisan precision to quietly drive your growth.',
            'extra' => ['rows' => 3],
        ],
        [
            'name' => 'hero_cta_1', 'label' => '主按钮', 'type' => 'link',
            'zh' => ['url' => '/ai-employees/', 'title' => '探索数字员工'],
            'en' => ['url' => '/ai-employees/', 'title' => 'Explore AI Employees'],
        ],
        [
            'name' => 'hero_cta_2', 'label' => '次按钮', 'type' => 'link',
            'zh' => ['url' => '/ai-solutions/', 'title' => '了解解决方案'],
            'en' => ['url' => '/ai-solutions/', 'title' => 'View Solutions'],
        ],
        [
            'name' => 'hero_image', 'label' => '主视觉图', 'type' => 'image',
            'zh' => '', 'en' => '', 'extra' => ['return_format' => 'array'],
        ],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'front-page.php']],
        [['param' => 'front_page', 'operator' => '==', 'value' => '1']],
    ]));

    /* ---- 2. 首页五个模块 ---- */
    acf_add_local_field_group($hireai_make_group('group_front_modules', '首页 — 精选模块', [
        ['name' => 'employees_kicker', 'label' => '数字员工 · 眉题', 'type' => 'text', 'zh' => 'AI 数字员工', 'en' => 'AI EMPLOYEES'],
        ['name' => 'employees_title', 'label' => '数字员工 · 标题', 'type' => 'textarea', 'zh' => '数字工匠', 'en' => 'Digital Artisans', 'extra' => ['rows' => 1]],
        ['name' => 'employees_subtitle', 'label' => '数字员工 · 副标题', 'type' => 'textarea', 'zh' => '每一位数字员工都拥有独特的灵魂、技能与能力，随时加入您的团队。', 'en' => 'Each digital employee brings a unique soul, refined skills, and unmatched capabilities—ready to join your team.', 'extra' => ['rows' => 2]],
        ['name' => 'employees_cta', 'label' => '数字员工 · 按钮', 'type' => 'link', 'zh' => ['url' => '/ai-employees/', 'title' => '探索更多'], 'en' => ['url' => '/ai-employees/', 'title' => 'Explore More']],

        ['name' => 'solutions_kicker', 'label' => '解决方案 · 眉题', 'type' => 'text', 'zh' => 'AI 解决方案', 'en' => 'AI SOLUTIONS'],
        ['name' => 'solutions_title', 'label' => '解决方案 · 标题', 'type' => 'textarea', 'zh' => '臻选解决方案', 'en' => 'Curated Solutions', 'extra' => ['rows' => 1]],
        ['name' => 'solutions_subtitle', 'label' => '解决方案 · 副标题', 'type' => 'textarea', 'zh' => '面向营销、电商、设计、公关四大场景的量身定制智能服务。', 'en' => 'Bespoke intelligent services tailored for marketing, e-commerce, design, and PR.', 'extra' => ['rows' => 2]],
        ['name' => 'solutions_cta', 'label' => '解决方案 · 按钮', 'type' => 'link', 'zh' => ['url' => '/ai-solutions/', 'title' => '探索更多'], 'en' => ['url' => '/ai-solutions/', 'title' => 'Explore More']],

        ['name' => 'cases_kicker', 'label' => '案例 · 眉题', 'type' => 'text', 'zh' => '案例与洞察', 'en' => 'CASES & INSIGHTS'],
        ['name' => 'cases_title', 'label' => '案例 · 标题', 'type' => 'textarea', 'zh' => '案例与思考', 'en' => 'Cases & Insights', 'extra' => ['rows' => 1]],
        ['name' => 'cases_subtitle', 'label' => '案例 · 副标题', 'type' => 'textarea', 'zh' => '见证数字员工如何改变企业的运营方式，洞察 AI 行业的深层趋势。', 'en' => 'See how digital employees transform operations and explore the deeper currents of AI.', 'extra' => ['rows' => 2]],
        ['name' => 'cases_cta', 'label' => '案例 · 按钮', 'type' => 'link', 'zh' => ['url' => '/cases-insights/', 'title' => '查看全部'], 'en' => ['url' => '/cases-insights/', 'title' => 'View All']],

        ['name' => 'faq_kicker', 'label' => 'FAQ · 眉题', 'type' => 'text', 'zh' => '常见问题', 'en' => 'FAQ'],
        ['name' => 'faq_title', 'label' => 'FAQ · 标题', 'type' => 'textarea', 'zh' => '疑问，即刻解答', 'en' => 'Answers, Immediately', 'extra' => ['rows' => 1]],
        ['name' => 'faq_subtitle', 'label' => 'FAQ · 副标题', 'type' => 'textarea', 'zh' => '关于合作方式、财务、隐私与安全的常见问题。', 'en' => 'Common questions about partnerships, finance, privacy, and security.', 'extra' => ['rows' => 2]],
        ['name' => 'faq_cta', 'label' => 'FAQ · 按钮', 'type' => 'link', 'zh' => ['url' => '/faq/', 'title' => '查看 FAQ'], 'en' => ['url' => '/faq/', 'title' => 'View FAQ']],

        ['name' => 'cta_title', 'label' => 'CTA · 标题', 'type' => 'textarea', 'zh' => '开启您的 AI 雇佣之旅', 'en' => 'Begin Your AI Hiring Journey', 'extra' => ['rows' => 1]],
        ['name' => 'cta_subtitle', 'label' => 'CTA · 副标题', 'type' => 'textarea', 'zh' => '与我们的团队对话，打造专属您的数字员工阵容。', 'en' => 'Speak with our team and craft a digital workforce made for you.', 'extra' => ['rows' => 2]],
        ['name' => 'cta_button', 'label' => 'CTA · 按钮', 'type' => 'link', 'zh' => ['url' => '/contact/', 'title' => '联系我们'], 'en' => ['url' => '/contact/', 'title' => 'Contact Us']],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'front-page.php']],
        [['param' => 'front_page', 'operator' => '==', 'value' => '1']],
    ]));

    /* ---- 3. AI 数字员工列表页 ---- */
    acf_add_local_field_group($hireai_make_group('group_page_ai_employees', 'AI 数字员工页', [
        ['name' => 'header_kicker', 'label' => '页眉眉题', 'type' => 'text', 'zh' => 'AI 数字员工', 'en' => 'AI EMPLOYEES'],
        ['name' => 'header_title', 'label' => '页眉标题', 'type' => 'textarea', 'zh' => '数字员工名录', 'en' => 'The Digital Roster', 'extra' => ['rows' => 1]],
        ['name' => 'header_subtitle', 'label' => '页眉副标题', 'type' => 'textarea', 'zh' => '每一位都是经过深度训练的专属智能体，拥有独特的灵魂与能力。', 'en' => 'Each is a deeply-trained bespoke agent with a distinct soul and capability set.', 'extra' => ['rows' => 2]],
        ['name' => 'card_cta_text', 'label' => '卡片按钮文字', 'type' => 'text', 'zh' => '探索更多', 'en' => 'Explore More'],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'page-ai-employees.php']],
    ]));

    /* ---- 4. AI 解决方案页 ---- */
    acf_add_local_field_group($hireai_make_group('group_page_ai_solutions', 'AI 解决方案页', [
        ['name' => 'header_kicker', 'label' => '页眉眉题', 'type' => 'text', 'zh' => 'AI 解决方案', 'en' => 'AI SOLUTIONS'],
        ['name' => 'header_title', 'label' => '页眉标题', 'type' => 'textarea', 'zh' => '臻选智能方案', 'en' => 'Curated Intelligence', 'extra' => ['rows' => 1]],
        ['name' => 'header_subtitle', 'label' => '页眉副标题', 'type' => 'textarea', 'zh' => '按场景筛选——营销、电商、设计、公关，总有一款适合您的业务。', 'en' => 'Filter by scenario—marketing, e-commerce, design, PR—there is a solution for every business.', 'extra' => ['rows' => 2]],
        ['name' => 'card_cta_text', 'label' => '卡片按钮文字', 'type' => 'text', 'zh' => '探索更多', 'en' => 'Explore More'],
        ['name' => 'empty_text', 'label' => '筛选空状态文案', 'type' => 'text', 'zh' => '该分类下暂无解决方案', 'en' => 'No solutions in this category yet.'],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'page-ai-solutions.php']],
    ]));

    /* 筛选配置（单一 repeater，行内含中英文标签 + 场景 slug） */
    acf_add_local_field_group([
        'key'    => 'group_solutions_filters',
        'title'  => 'AI 解决方案 — 筛选配置',
        'fields' => [
            [
                'key'       => 'field_solutions_filters',
                'label'     => '场景筛选',
                'name'      => 'solutions_filters',
                'type'      => 'repeater',
                'instructions' => '每行一个筛选场景：中英文标签 + 对应的商品分类 slug（如 marketing / ecommerce / design / pr）。',
                'layout'    => 'table',
                'button_label' => '添加场景',
                'sub_fields' => [
                    [
                        'key'   => 'field_filter_label_zh',
                        'label' => '标签（中文）',
                        'name'  => 'filter_label_zh',
                        'type'  => 'text',
                    ],
                    [
                        'key'   => 'field_filter_label_en',
                        'label' => 'Label (EN)',
                        'name'  => 'filter_label_en',
                        'type'  => 'text',
                    ],
                    [
                        'key'   => 'field_filter_slug',
                        'label' => '商品分类 slug',
                        'name'  => 'filter_slug',
                        'type'  => 'text',
                    ],
                ],
            ],
        ],
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-ai-solutions.php']],
        ],
    ]);

    /* ---- 5. 案例 & 洞察页 ---- */
    acf_add_local_field_group($hireai_make_group('group_page_cases_insights', '案例 & 洞察页', [
        ['name' => 'hero_kicker', 'label' => '页眉眉题', 'type' => 'text', 'zh' => '案例与洞察', 'en' => 'CASES & INSIGHTS'],
        ['name' => 'hero_title', 'label' => '页眉标题', 'type' => 'textarea', 'zh' => '案例与洞察', 'en' => 'Cases & Insights', 'extra' => ['rows' => 1]],
        ['name' => 'hero_subtitle', 'label' => '页眉副标题', 'type' => 'textarea', 'zh' => '见证数字员工如何改变企业的运营方式，洞察 AI 行业的深层趋势。', 'en' => 'See how digital employees transform operations and explore the deeper currents of AI.', 'extra' => ['rows' => 2]],

        ['name' => 'cases_kicker', 'label' => '案例 · 眉题', 'type' => 'text', 'zh' => '案例', 'en' => 'CASES'],
        ['name' => 'cases_title', 'label' => '案例 · 标题', 'type' => 'textarea', 'zh' => '精选案例', 'en' => 'Selected Cases', 'extra' => ['rows' => 1]],
        ['name' => 'cases_subtitle', 'label' => '案例 · 副标题', 'type' => 'textarea', 'zh' => '真实客户如何借助数字员工实现增长。', 'en' => 'How real clients grow with digital employees.', 'extra' => ['rows' => 2]],
        ['name' => 'cases_cta', 'label' => '案例 · 按钮', 'type' => 'link', 'zh' => ['url' => '/category/cases/', 'title' => '查看全部案例'], 'en' => ['url' => '/category/cases/', 'title' => 'All Cases']],

        ['name' => 'insights_kicker', 'label' => '洞察 · 眉题', 'type' => 'text', 'zh' => '洞察', 'en' => 'INSIGHTS'],
        ['name' => 'insights_title', 'label' => '洞察 · 标题', 'type' => 'textarea', 'zh' => '前沿洞察', 'en' => 'Frontier Insights', 'extra' => ['rows' => 1]],
        ['name' => 'insights_subtitle', 'label' => '洞察 · 副标题', 'type' => 'textarea', 'zh' => '关于 AI 行业与数字员工的深度思考。', 'en' => 'Deep thinking on AI and the digital workforce.', 'extra' => ['rows' => 2]],
        ['name' => 'insights_cta', 'label' => '洞察 · 按钮', 'type' => 'link', 'zh' => ['url' => '/category/insights/', 'title' => '更多洞察'], 'en' => ['url' => '/category/insights/', 'title' => 'More Insights']],

        ['name' => 'card_cta_text', 'label' => '卡片按钮文字', 'type' => 'text', 'zh' => '阅读更多', 'en' => 'Read More'],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'page-cases-insights.php']],
    ]));

    /* ---- 6. FAQ 页 ---- */
    acf_add_local_field_group($hireai_make_group('group_page_faq', '常见问题页', [
        ['name' => 'header_kicker', 'label' => '页眉眉题', 'type' => 'text', 'zh' => '常见问题', 'en' => 'FAQ'],
        ['name' => 'header_title', 'label' => '页眉标题', 'type' => 'textarea', 'zh' => '清晰以对', 'en' => 'Clarity Amidst Complexity', 'extra' => ['rows' => 1]],
        ['name' => 'header_subtitle', 'label' => '页眉副标题', 'type' => 'textarea', 'zh' => '在复杂中寻求清晰——关于我们 AI 数字员工生态的常见问题解答。', 'en' => 'Find answers to common questions regarding our AI employee ecosystem.', 'extra' => ['rows' => 2]],
        ['name' => 'search_placeholder', 'label' => '检索框占位符', 'type' => 'text', 'zh' => '输入关键词检索…', 'en' => 'Search questions…'],
        ['name' => 'empty_text', 'label' => '无结果文案', 'type' => 'textarea', 'zh' => '未找到匹配的问题，请尝试其他关键词。', 'en' => 'No matching questions found. Try a different keyword.', 'extra' => ['rows' => 2]],
        ['name' => 'faq_group_1_label', 'label' => '分组1 · 合作方式', 'type' => 'text', 'zh' => '合作方式', 'en' => 'Partnership'],
        ['name' => 'faq_group_2_label', 'label' => '分组2 · 财务', 'type' => 'text', 'zh' => '财务', 'en' => 'Finance'],
        ['name' => 'faq_group_3_label', 'label' => '分组3 · 隐私和安全', 'type' => 'text', 'zh' => '隐私和安全', 'en' => 'Privacy & Security'],
        ['name' => 'faq_group_4_label', 'label' => '分组4 · 其他', 'type' => 'text', 'zh' => '其他', 'en' => 'Other'],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'page-faq.php']],
    ]));

    /* ---- 7. 联系页 ---- */
    acf_add_local_field_group($hireai_make_group('group_page_contact', '联系页', [
        ['name' => 'header_kicker', 'label' => '页眉眉题', 'type' => 'text', 'zh' => '联系', 'en' => 'CONTACT'],
        ['name' => 'header_title', 'label' => '页眉标题', 'type' => 'textarea', 'zh' => '发起联络', 'en' => 'Initiate Contact', 'extra' => ['rows' => 1]],
        ['name' => 'header_subtitle', 'label' => '页眉副标题', 'type' => 'textarea', 'zh' => '告诉我们您的需求，我们将在一个工作日内回复。', 'en' => "Tell us your needs and we'll respond within one business day.", 'extra' => ['rows' => 2]],
        ['name' => 'contact_email', 'label' => '联系邮箱', 'type' => 'text', 'zh' => 'concierge@hireaipeople.com', 'en' => 'concierge@hireaipeople.com'],
        ['name' => 'contact_wechat', 'label' => '微信号', 'type' => 'text', 'zh' => 'hireai-official', 'en' => 'hireai-official'],
        ['name' => 'wechat_qr', 'label' => '微信二维码', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'array']],
        ['name' => 'contact_address', 'label' => '总部地址', 'type' => 'textarea', 'zh' => '中国 · 上海', 'en' => 'Shanghai, China', 'extra' => ['rows' => 2]],
        ['name' => 'contact_map_label', 'label' => '地图按钮文字', 'type' => 'text', 'zh' => '查看地图', 'en' => 'View Map'],
        ['name' => 'contact_map_url', 'label' => '地图链接', 'type' => 'text', 'zh' => 'https://uri.amap.com/search?keyword=Shanghai%2C%20China', 'en' => 'https://uri.amap.com/search?keyword=Shanghai%2C%20China'],
        ['name' => 'form_name_label', 'label' => '表单 · 姓名', 'type' => 'text', 'zh' => '姓名', 'en' => 'Name'],
        ['name' => 'form_company_label', 'label' => '表单 · 公司/机构', 'type' => 'text', 'zh' => '公司/机构', 'en' => 'Company Entity'],
        ['name' => 'form_email_label', 'label' => '表单 · 邮箱', 'type' => 'text', 'zh' => '邮箱', 'en' => 'Secure Email'],
        ['name' => 'form_message_label', 'label' => '表单 · 需求描述', 'type' => 'text', 'zh' => '需求描述', 'en' => 'Inquiry Details'],
        ['name' => 'form_submit_label', 'label' => '表单 · 提交按钮', 'type' => 'text', 'zh' => '提交咨询', 'en' => 'Send Inquiry'],
        ['name' => 'form_success', 'label' => '表单 · 成功提示', 'type' => 'textarea', 'zh' => '您的咨询已发送，我们将尽快与您联系。', 'en' => "Your inquiry has been sent. We'll be in touch shortly.", 'extra' => ['rows' => 2]],
        ['name' => 'form_invalid', 'label' => '表单 · 校验失败提示', 'type' => 'textarea', 'zh' => '请填写正确的姓名、邮箱与需求描述。', 'en' => 'Please provide a valid name, email, and message.', 'extra' => ['rows' => 2]],
        ['name' => 'form_error', 'label' => '表单 · 发送失败提示', 'type' => 'textarea', 'zh' => '发送失败，请稍后重试或直接邮件联系我们。', 'en' => 'Something went wrong. Please retry or email us directly.', 'extra' => ['rows' => 2]],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'page-contact.php']],
    ]));

    /* ---- 8. 数字员工文章（category=ai-employee）---- */
    acf_add_local_field_group($hireai_make_group('group_employee_meta', '数字员工 — 详情', [
        ['name' => 'employee_role', 'label' => '职位', 'type' => 'text', 'zh' => '数字员工', 'en' => 'Digital Employee'],
        ['name' => 'employee_soul', 'label' => '灵魂（soul）', 'type' => 'textarea', 'zh' => '以逻辑为骨、以静谧为魂，被悉心培育出独一无二的心智与气质。', 'en' => 'Cultivated with a specific psychological profile—quiet, precise, and shaped by logic.', 'extra' => ['rows' => 4]],
        ['name' => 'employee_skill', 'label' => '技能（skill）', 'type' => 'textarea', 'zh' => '精通数据分析、市场策略与内容创作，可与您的团队无缝协作。', 'en' => 'Masters data analysis, market strategy, and content creation—ready to collaborate seamlessly with your team.', 'extra' => ['rows' => 4]],
        ['name' => 'employee_capabilities', 'label' => '能力（capabilities，每行一项）', 'type' => 'textarea', 'zh' => "深度市场调研\n实时数据分析\n多语言内容创作\n24×7 待命服务", 'en' => "Deep market research\nReal-time data analysis\nMultilingual content creation\n24×7 availability", 'extra' => ['rows' => 6]],
        ['name' => 'employee_cases_link', 'label' => '案例展示链接', 'type' => 'link', 'zh' => ['url' => '/category/cases/', 'title' => '查看相关案例'], 'en' => ['url' => '/category/cases/', 'title' => 'View Related Cases']],
    ], [
        [['param' => 'post_taxonomy', 'operator' => '==', 'value' => 'category:ai-employee']],
    ]));

    /* ---- 9. FAQ 文章（category=faq）分组字段 ---- */
    acf_add_local_field_group([
        'key'    => 'group_faq_post',
        'title'  => 'FAQ 分组',
        'fields' => [
            [
                'key'           => 'field_faq_group',
                'label'         => 'FAQ 分组',
                'name'          => 'faq_group',
                'type'          => 'select',
                'choices'       => [
                    'partnership'     => '合作方式',
                    'finance'         => '财务',
                    'privacy-security' => '隐私和安全',
                    'other'           => '其他',
                ],
                'default_value' => 'other',
            ],
        ],
        'location' => [
            [['param' => 'post_taxonomy', 'operator' => '==', 'value' => 'category:faq']],
        ],
    ]);

    /* ---- 9.5 商品 ACF：解决方案卡片 / 单产品页卖点 ---- */
    acf_add_local_field_group($hireai_make_group('group_product_meta', 'AI 解决方案 — 卡片与详情', [
        ['name' => 'product_operative', 'label' => '执行智能体', 'type' => 'text', 'zh' => '执行智能体：聘AI', 'en' => 'OPERATIVE: HIREAI'],
        ['name' => 'product_retainer_label', 'label' => '收费档位标签', 'type' => 'text', 'zh' => '起步档', 'en' => 'Starting Retainer'],
        ['name' => 'product_feature_1_title', 'label' => '卖点 1 · 标题', 'type' => 'text', 'zh' => '降低品牌曝光风险', 'en' => 'Mitigating Brand Exposure'],
        ['name' => 'product_feature_1_text', 'label' => '卖点 1 · 描述', 'type' => 'textarea', 'zh' => '即时形成保护品牌资产的高校准响应。', 'en' => 'Instantly formulate responses that protect brand equity.', 'extra' => ['rows' => 2]],
        ['name' => 'product_feature_2_title', 'label' => '卖点 2 · 标题', 'type' => 'text', 'zh' => '自动化神经工作流', 'en' => 'Automating Neural Workflows'],
        ['name' => 'product_feature_2_text', 'label' => '卖点 2 · 描述', 'type' => 'textarea', 'zh' => '实时情绪分析并触发自动草拟协议。', 'en' => 'Real-time sentiment analysis triggering automated draft protocols.', 'extra' => ['rows' => 2]],
    ], [
        [['param' => 'post_type', 'operator' => '==', 'value' => 'product']],
    ]));

    /* ---- 10. 站点选项（页脚，ACF Pro Options Page）---- */
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => '站点设置',
            'menu_title' => '站点设置',
            'menu_slug'  => 'hireai-settings',
            'capability' => 'manage_options',
            'icon_url'   => 'dashicons-admin-generic',
        ]);
    }

    acf_add_local_field_group($hireai_make_group('group_site_options', '站点设置 — 页脚', [
        ['name' => 'footer_copyright', 'label' => '版权信息', 'type' => 'text', 'zh' => '© 2026 聘AI（Hire AI People）。保留所有权利。', 'en' => '© 2026 Hire AI People. All rights reserved.'],
        ['name' => 'footer_slogan', 'label' => '品牌 Slogan', 'type' => 'text', 'zh' => '雇佣智慧 · 臻于艺术', 'en' => 'Hire Intelligence, Artfully Employed.'],
        ['name' => 'footer_desc', 'label' => '页脚介绍', 'type' => 'textarea', 'zh' => 'AI 数字员工与 AI 解决方案平台——以极简奢华之姿，重塑企业智能雇佣。', 'en' => 'A platform for AI digital employees and AI solutions—reshaping intelligent hiring with minimalist luxury.', 'extra' => ['rows' => 3]],
    ], [
        [['param' => 'options_page', 'operator' => '==', 'value' => 'hireai-settings']],
    ]));
});
