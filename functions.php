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
    $hireai_update_checker->setBranch('main');

    // GitHub Token 认证 — 在 wp-config.php 中定义 HIREAI_GITHUB_TOKEN 常量即可
    if ( defined('HIREAI_GITHUB_TOKEN') ) {
        $hireai_update_checker->setAuthentication( HIREAI_GITHUB_TOKEN );
    }
}

/**
 * 聘AI (Hire AI People) Child — Hello Elementor 子主题
 * functions.php
 *
 * 职责：资源加载 / 导航 / WooCommerce 支持 / ACF 字段注册（双语方案 B）/
 *       辅助函数回退 / 联系表单处理 / 分页
 */

// 版本号自动从 style.css Header 读取，每次更新 style.css 的 Version 字段即可
if (!defined('HIREAI_VERSION')) {
    define('HIREAI_VERSION', wp_get_theme()->get('Version'));
}

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
 * lookbook_field — hireai_field 的别名（v1.1.0 兼容）
 * page-ai-employees.php 使用此函数名
 */
function lookbook_field($name, $default = '', $post_id = false) {
    return hireai_field($name, $default, $post_id);
}

/**
 * lookbook_img — hireai_default_image 的别名
 */
function lookbook_img($name = '') {
    return hireai_default_image($name);
}

/**
 * lookbook_fallback_employees — 返回兜底数字员工数据
 */
function lookbook_fallback_employees() {
    $suffix = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '';
    $is_zh  = ('_zh' === $suffix);
    return [
        [
            'kicker' => $is_zh ? '战略精英' : 'Strategic Elite',
            'title'  => $is_zh ? '公共关系审计' : 'Public Relations Audit',
            'desc'   => $is_zh ? '基于专有神经网络，对您的线上与线下形象做取证级分析，精准测绘全球市场的认知、情绪与影响力缺口。' : 'A forensic analysis of your digital and physical presence. Leveraging proprietary neural networks to map perception, sentiment, and influence gaps in global markets.',
            'button' => $is_zh ? '咨询' : 'Inquire',
            'image'  => 'lookbook/service-1.png',
            'url'    => home_url('/contact/'),
        ],
        [
            'kicker' => $is_zh ? '知识产权资产' : 'Intellectual Asset',
            'title'  => $is_zh ? 'IP 联名合作' : 'IP Collaboration',
            'desc'   => $is_zh ? '让经典品牌与数字生命力交融，以传奇 IP 与生成式架构共创元宇宙内外的新收入曲线。' : 'Bridging heritage brands with digital longevity. We facilitate the synthesis of legendary IP and generative architecture to create new revenue streams.',
            'button' => $is_zh ? '查看作品' : 'Explore Portfolio',
            'image'  => 'lookbook/service-2.png',
            'url'    => home_url('/contact/'),
        ],
        [
            'kicker' => $is_zh ? '视觉商业' : 'Visual Commerce',
            'title'  => $is_zh ? '电商视觉场景' : 'E-commerce Sets',
            'desc'   => $is_zh ? '超越实体影棚。我们用 AI 驱动照片级真实感，打造沉浸式高转化视觉场景。' : 'Beyond the physical studio. We architect immersive, high-conversion visual environments using AI-driven photorealism.',
            'button' => $is_zh ? '进入展厅' : 'View Showroom',
            'image'  => 'lookbook/service-3.png',
            'url'    => home_url('/contact/'),
        ],
        [
            'kicker' => $is_zh ? '数字精品艺术' : 'Digital Fine Art',
            'title'  => $is_zh ? 'AI 艺术图像设计' : 'AI Art Image Design',
            'desc'   => $is_zh ? '策展崇高。艺术家以先进生成模型为笔，创作超越物理边界的定制图像。' : 'Curating the sublime. Our artists utilize advanced generative models as their brushes to create bespoke imagery.',
            'button' => $is_zh ? '委托创作' : 'Commission',
            'image'  => 'lookbook/service-4.png',
            'url'    => home_url('/contact/'),
        ],
        [
            'kicker' => $is_zh ? '感官生活方式' : 'Sensory Lifestyle',
            'title'  => $is_zh ? '酒单设计师' : 'Cocktail Menu Designer',
            'desc'   => $is_zh ? '调酒学与分子 AI 的交汇。我们用神经网络分析风味谱系，设计招牌饮品身份。' : 'The intersection of mixology and molecular AI. We design signature drink identities by analyzing flavor profiles through neural networks.',
            'button' => $is_zh ? '预约咨询' : 'Book Consultation',
            'image'  => 'lookbook/service-5.png',
            'url'    => home_url('/contact/'),
        ],
    ];
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
 * 获取指定语言的 ACF 字段（双语方案 B：_zh/_en 双字段独立编辑）
 * @param string $name     字段基础名（不含语言后缀）
 * @param string $lang     'zh' 或 'en'
 * @param mixed  $default  默认值
 * @param mixed  $post_id  可选 post_id / 'option'
 */
function hireai_field_lang($name, $lang, $default = '', $post_id = false) {
    return site_field($name . (($lang === 'en') ? '_en' : '_zh'), $default, $post_id);
}

/**
 * 获取指定语言的 ACF 图片 URL
 */
function hireai_image_lang($name, $lang, $default = '', $post_id = false) {
    return site_image_url($name . (($lang === 'en') ? '_en' : '_zh'), $default, $post_id);
}

/**
 * 获取指定语言的 ACF 链接
 */
function hireai_link_lang($name, $lang, $default_url = '#', $default_title = '', $post_id = false) {
    return site_link($name . (($lang === 'en') ? '_en' : '_zh'), $default_url, $default_title, $post_id);
}

/**
 * 渲染一个双语文本块（.zh/.en，CSS/JS 负责切换显示）
 */
function hireai_bilingual($zh, $en, $tag = 'span') {
    $open = '<' . $tag . ' class="zh">';
    $mid  = '</' . $tag . '><' . $tag . ' class="en" style="display:none">';
    $close= '</' . $tag . '>';
    return $open . esc_html((string) $zh) . $mid . esc_html((string) $en) . $close;
}


/**
 * 本地 SVG 图标（避免任何外部字体/图标 CDN 依赖）
 */
/**
 * 本地默认图 URL：存在时返回 assets/img/defaults/ 下的素材，不存在返回空串。
 */
function hireai_default_image($name = '') {
    if ($name === '') {
        return '';
    }
    $base = get_stylesheet_directory() . '/assets/img/';
    $uri  = get_stylesheet_directory_uri() . '/assets/img/';
    // 含斜杠时视为 assets/img/ 下的相对路径，直接查找（不做 sanitize 以保留目录分隔符）
    if (strpos($name, '/') !== false) {
        $path = $base . $name;
        if (file_exists($path)) {
            return $uri . $name;
        }
    }
    // 裸文件名：sanitize 后依次查 lookbook/、defaults/ 子目录
    $name = sanitize_file_name($name);
    if ($name === '') {
        return '';
    }
    $dirs = ['lookbook', 'defaults'];
    foreach ($dirs as $dir) {
        $path = $base . $dir . '/' . $name;
        if (file_exists($path)) {
            return $uri . $dir . '/' . $name;
        }
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

// 每次文件改动自动生成新版本号 → 强制浏览器/CDN/全页缓存刷新（无需手动清缓存）
$ver = function ($file) {
    $path = get_stylesheet_directory() . $file;
    $mtime = file_exists($path) ? filemtime($path) : HIREAI_VERSION;
    return HIREAI_VERSION . '-' . $mtime;
};

add_action('wp_enqueue_scripts', function () use ($ver) {
    // 父主题样式（只加载一次）
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css', [], HIREAI_VERSION);

    // 子主题样式（版本号随文件时间戳变化，缓存自动失效）
    wp_enqueue_style(
        'hireaipeople-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['parent-style'],
        $ver('/style.css')
    );

    // Material Symbols（页眉/页脚 search、menu、social 图标），与首页/博客插件同一来源。
    wp_enqueue_style(
        'hireaipeople-material-symbols',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap',
        [],
        null
    );

    // 首页专用样式
    if (is_front_page()) {
        wp_enqueue_style(
            'hireaipeople-front-page',
            get_stylesheet_directory_uri() . '/assets/css/front-page.css',
            ['hireaipeople-style'],
            $ver('/assets/css/front-page.css')
        );
    }

    // 字体已本地化：@font-face 在 style.css 顶部加载 assets/fonts/*.woff2，不再请求 Google Fonts。
    // 前端脚本
    wp_enqueue_script(
        'hireaipeople-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [],
        $ver('/assets/js/main.js'),
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
    echo '<ul class="hai-header__nav-list">';
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

    $wp_customize->add_setting('site_logo_height', ['default' => '46', 'sanitize_callback' => 'absint']);
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

    /* ---- 1. 首页 Hero（字段名与 front-page.php 读取的 fp_* 一一对应） ---- */
    acf_add_local_field_group($hireai_make_group('group_front_hero', '首页 — Hero 区域', [
        [
            'name' => 'fp_hero_kicker', 'label' => '眉题（kicker）', 'type' => 'text',
            'zh' => '工匠精神与算法', 'en' => 'Prestige Digital Labor',
        ],
        [
            'name' => 'fp_hero_static', 'label' => '主标题（常亮大字）', 'type' => 'text',
            'zh' => '重新定义', 'en' => 'Redefine',
        ],
        [
            'name' => 'fp_hero_accent', 'label' => '主标题（金色斜体）', 'type' => 'text',
            'zh' => '数字劳动力', 'en' => 'Digital Labor',
        ],
        [
            'name' => 'fp_hero_subtitle', 'label' => '副标题', 'type' => 'textarea',
            'zh' => '融合尖端科技与奢华质感，为您打造专属数字员工。',
            'en' => 'Fusing cutting-edge technology with a luxurious aesthetic to craft your exclusive digital employees.',
            'extra' => ['rows' => 3],
        ],
        [
            'name' => 'fp_hero_cta_1', 'label' => '主按钮', 'type' => 'link',
            'zh' => ['url' => '/ai-employees/', 'title' => '探索系列'],
            'en' => ['url' => '/ai-employees/', 'title' => 'EXPLORE SERIES'],
        ],
        [
            'name' => 'fp_hero_cta_2', 'label' => '次按钮', 'type' => 'link',
            'zh' => ['url' => '/contact/', 'title' => '定制咨询'],
            'en' => ['url' => '/contact/', 'title' => 'CONSULTATION'],
        ],
        [
            'name' => 'fp_hero_image', 'label' => 'Hero 背景图片', 'type' => 'image',
            'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url'],
        ],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'front-page.php']],
        [['param' => 'front_page', 'operator' == '==', 'value' => '1']],
    ]));

    /* ---- 2. 首页各模块（字段名与 front-page.php 读取的 fp_* 一一对应） ---- */
    acf_add_local_field_group($hireai_make_group('group_front_modules', '首页 — 各模块', [
        ['name' => 'fp_intro_kicker', 'label' => '引言 · 眉题', 'type' => 'text', 'zh' => '工匠精神与算法', 'en' => 'Craftsmanship Meets Algorithm'],
        ['name' => 'fp_intro_title', 'label' => '引言 · 标题', 'type' => 'textarea', 'zh' => '塑造超越物理边界的存在。', 'en' => 'Shaping existence beyond physical boundaries.', 'extra' => ['rows' => 2]],
        ['name' => 'fp_intro_desc', 'label' => '引言 · 描述', 'type' => 'textarea', 'zh' => '我们结合传统奢华的严谨工艺与神经网络的无限可能。每一位数字员工都是独一无二的杰作，专为优雅、智慧与共鸣而设计。', 'en' => 'We combine the rigor of traditional luxury with the infinite potential of neural networks. Every digital employee is a one-of-a-kind masterpiece, designed for elegance, intelligence, and resonance.', 'extra' => ['rows' => 4]],
        ['name' => 'fp_intro_cta_title', 'label' => '引言 · 链接文字', 'type' => 'text', 'zh' => '探索更多', 'en' => 'Explore More'],
        ['name' => 'fp_intro_cta_url', 'label' => '引言 · 链接地址', 'type' => 'text', 'zh' => '/ai-employees/', 'en' => '/ai-employees/'],

        ['name' => 'fp_products_kicker', 'label' => '数字员工 · 眉题', 'type' => 'text', 'zh' => '限量神经元系列', 'en' => 'Limited Neural Series'],
        ['name' => 'fp_products_title', 'label' => '数字员工 · 标题', 'type' => 'textarea', 'zh' => 'AI 数字员工', 'en' => 'AI Digital Employees', 'extra' => ['rows' => 1]],
        ['name' => 'fp_products_subtitle', 'label' => '数字员工 · 副标题', 'type' => 'textarea', 'zh' => '每一位数字员工都拥有独特的灵魂、技能与能力，随时加入您的团队。', 'en' => 'Each digital employee brings a unique soul, refined skills, and unmatched capabilities.', 'extra' => ['rows' => 2]],
        ['name' => 'fp_products_explore_label', 'label' => '数字员工 · 按钮文字', 'type' => 'text', 'zh' => '探索更多', 'en' => 'Explore More'],

        ['name' => 'fp_prod1_title', 'label' => '数字员工 1 · 标题', 'type' => 'text', 'zh' => 'Aurelian Prime', 'en' => 'Aurelian Prime'],
        ['name' => 'fp_prod1_desc', 'label' => '数字员工 1 · 描述', 'type' => 'text', 'zh' => '精英女性数字分身', 'en' => 'Elite female digital avatar'],
        ['name' => 'fp_prod1_badge', 'label' => '数字员工 1 · 徽标', 'type' => 'text', 'zh' => '限量 01/50', 'en' => 'Edition 01/50'],
        ['name' => 'fp_prod1_image', 'label' => '数字员工 1 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],
        ['name' => 'fp_prod1_url', 'label' => '数字员工 1 · 链接', 'type' => 'link', 'zh' => ['url' => '/ai-employees/', 'title' => ''], 'en' => ['url' => '/ai-employees/', 'title' => '']],

        ['name' => 'fp_prod2_title', 'label' => '数字员工 2 · 标题', 'type' => 'text', 'zh' => 'Aurelian Executive', 'en' => 'Aurelian Executive'],
        ['name' => 'fp_prod2_desc', 'label' => '数字员工 2 · 描述', 'type' => 'text', 'zh' => '权威与外交协议', 'en' => 'Authority & diplomacy protocol'],
        ['name' => 'fp_prod2_badge', 'label' => '数字员工 2 · 徽标', 'type' => 'text', 'zh' => 'Executive Series', 'en' => 'Executive Series'],
        ['name' => 'fp_prod2_image', 'label' => '数字员工 2 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],
        ['name' => 'fp_prod2_url', 'label' => '数字员工 2 · 链接', 'type' => 'link', 'zh' => ['url' => '/ai-employees/', 'title' => ''], 'en' => ['url' => '/ai-employees/', 'title' => '']],

        ['name' => 'fp_prod3_title', 'label' => '数字员工 3 · 标题', 'type' => 'text', 'zh' => 'Neural Sales Core', 'en' => 'Neural Sales Core'],
        ['name' => 'fp_prod3_desc', 'label' => '数字员工 3 · 描述', 'type' => 'text', 'zh' => '企业级AI优化', 'en' => 'Enterprise-grade AI optimization'],
        ['name' => 'fp_prod3_badge', 'label' => '数字员工 3 · 徽标', 'type' => 'text', 'zh' => 'Neural Series', 'en' => 'Neural Series'],
        ['name' => 'fp_prod3_image', 'label' => '数字员工 3 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],
        ['name' => 'fp_prod3_url', 'label' => '数字员工 3 · 链接', 'type' => 'link', 'zh' => ['url' => '/ai-employees/', 'title' => ''], 'en' => ['url' => '/ai-employees/', 'title' => '']],

        ['name' => 'fp_solutions_kicker', 'label' => '解决方案 · 眉题', 'type' => 'text', 'zh' => '行业赋能', 'en' => 'Industry Empowerment'],
        ['name' => 'fp_solutions_title', 'label' => '解决方案 · 标题', 'type' => 'textarea', 'zh' => 'AI 解决方案', 'en' => 'AI Solutions', 'extra' => ['rows' => 1]],
        ['name' => 'fp_solutions_subtitle', 'label' => '解决方案 · 副标题', 'type' => 'textarea', 'zh' => '面向多个行业的量身定制智能方案。', 'en' => 'Bespoke intelligent solutions across industries.', 'extra' => ['rows' => 2]],
        ['name' => 'fp_solutions_explore_label', 'label' => '解决方案 · 按钮文字', 'type' => 'text', 'zh' => '探索更多', 'en' => 'Explore More'],

        ['name' => 'fp_sol1_title', 'label' => '方案 1 · 标题', 'type' => 'text', 'zh' => '金融与财富管理', 'en' => 'Finance & Wealth Management'],
        ['name' => 'fp_sol1_desc', 'label' => '方案 1 · 描述', 'type' => 'textarea', 'zh' => '智能顾问与客户关系维护的数字化重塑。', 'en' => 'Digital reshaping of intelligent advisors and client relationship management.', 'extra' => ['rows' => 3]],
        ['name' => 'fp_sol1_tag', 'label' => '方案 1 · 标签', 'type' => 'text', 'zh' => '金融', 'en' => 'Finance'],
        ['name' => 'fp_sol1_image', 'label' => '方案 1 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],

        ['name' => 'fp_sol2_title', 'label' => '方案 2 · 标题', 'type' => 'text', 'zh' => '高端零售与电商', 'en' => 'Premium Retail & E-commerce'],
        ['name' => 'fp_sol2_desc', 'label' => '方案 2 · 描述', 'type' => 'textarea', 'zh' => '24/7全天候奢华购物体验升级。', 'en' => '24/7 all-day luxury shopping experience upgrade.', 'extra' => ['rows' => 3]],
        ['name' => 'fp_sol2_tag', 'label' => '方案 2 · 标签', 'type' => 'text', 'zh' => '零售', 'en' => 'Retail'],
        ['name' => 'fp_sol2_image', 'label' => '方案 2 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],

        ['name' => 'fp_sol3_title', 'label' => '方案 3 · 标题', 'type' => 'text', 'zh' => '医疗健康与陪伴', 'en' => 'Healthcare & Companionship'],
        ['name' => 'fp_sol3_desc', 'label' => '方案 3 · 描述', 'type' => 'textarea', 'zh' => '充满同理心的智能关怀与健康咨询。', 'en' => 'Empathetic intelligent care and health consultation.', 'extra' => ['rows' => 3]],
        ['name' => 'fp_sol3_tag', 'label' => '方案 3 · 标签', 'type' => 'text', 'zh' => '健康', 'en' => 'Health'],
        ['name' => 'fp_sol3_image', 'label' => '方案 3 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],

        ['name' => 'fp_sol4_title', 'label' => '方案 4 · 标题', 'type' => 'text', 'zh' => '泛娱乐与虚拟偶像', 'en' => 'Entertainment & Virtual Idols'],
        ['name' => 'fp_sol4_desc', 'label' => '方案 4 · 描述', 'type' => 'textarea', 'zh' => '打造永不塌房的超级IP与互动体验。', 'en' => 'Build the ultimate never-fail super IP and interactive experience.', 'extra' => ['rows' => 3]],
        ['name' => 'fp_sol4_tag', 'label' => '方案 4 · 标签', 'type' => 'text', 'zh' => '娱乐', 'en' => 'Entertainment'],
        ['name' => 'fp_sol4_image', 'label' => '方案 4 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],

        ['name' => 'fp_cases_kicker', 'label' => '案例 · 眉题', 'type' => 'text', 'zh' => '前沿视野', 'en' => 'Frontier Vision'],
        ['name' => 'fp_cases_title', 'label' => '案例 · 标题', 'type' => 'textarea', 'zh' => '案例 & 洞察', 'en' => 'Cases & Insights', 'extra' => ['rows' => 1]],
        ['name' => 'fp_cases_subtitle', 'label' => '案例 · 副标题', 'type' => 'textarea', 'zh' => '见证数字员工如何改变企业的运营方式。', 'en' => 'See how digital employees transform operations.', 'extra' => ['rows' => 2]],
        ['name' => 'fp_cases_explore_label', 'label' => '案例 · 按钮文字', 'type' => 'text', 'zh' => '探索更多', 'en' => 'Explore More'],

        ['name' => 'fp_case_major_label', 'label' => '主案例 · 标签', 'type' => 'text', 'zh' => '案例研究', 'en' => 'CASE STUDY'],
        ['name' => 'fp_case_major_title', 'label' => '主案例 · 标题', 'type' => 'textarea', 'zh' => 'Aurelian Prime 在私人银行的应用', 'en' => 'Aurelian Prime in Private Banking', 'extra' => ['rows' => 2]],
        ['name' => 'fp_case_major_desc', 'label' => '主案例 · 描述', 'type' => 'textarea', 'zh' => '了解我们的顶级数字员工如何提升高净值客户的留存率与满意度。', 'en' => 'Learn how our top digital employee boosts retention and satisfaction for high-net-worth clients.', 'extra' => ['rows' => 3]],
        ['name' => 'fp_case_major_image', 'label' => '主案例 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],

        ['name' => 'fp_case1_tag', 'label' => '案例 1 · 标签', 'type' => 'text', 'zh' => '案例研究', 'en' => 'CASE STUDY'],
        ['name' => 'fp_case1_title', 'label' => '案例 1 · 标题', 'type' => 'textarea', 'zh' => '电商视觉革命：转化率提升55%', 'en' => 'E-commerce Visual Revolution: +55% Conversion', 'extra' => ['rows' => 2]],
        ['name' => 'fp_case1_desc', 'label' => '案例 1 · 描述', 'type' => 'textarea', 'zh' => '重塑线上购物体验，结合虚拟试穿与个性化推荐带来的商业增长。', 'en' => 'Reshaping online shopping with virtual try-on and personalized recommendations.', 'extra' => ['rows' => 3]],
        ['name' => 'fp_case1_image', 'label' => '案例 1 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],

        ['name' => 'fp_case2_tag', 'label' => '案例 2 · 标签', 'type' => 'text', 'zh' => '深度洞察', 'en' => 'DEEP INSIGHT'],
        ['name' => 'fp_case2_title', 'label' => '案例 2 · 标题', 'type' => 'textarea', 'zh' => '"未来不再仅仅是代码，更是交响乐。"', 'en' => '"The future is no longer just code, but a symphony."', 'extra' => ['rows' => 2]],
        ['name' => 'fp_case2_desc', 'label' => '案例 2 · 描述', 'type' => 'textarea', 'zh' => '探讨数字人性化的趋势，以及我们在构建有温度的AI方面的思考与实践。', 'en' => 'Exploring the trend of humanized digital beings and our approach to building warm AI.', 'extra' => ['rows' => 3]],
        ['name' => 'fp_case2_image', 'label' => '案例 2 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],

        ['name' => 'fp_faq_kicker', 'label' => 'FAQ · 眉题', 'type' => 'text', 'zh' => '常见问题', 'en' => 'FAQ'],
        ['name' => 'fp_faq_title', 'label' => 'FAQ · 标题', 'type' => 'textarea', 'zh' => '解答关于数字员工的疑虑，开启智能新纪元。', 'en' => 'Answers to your questions about digital employees.', 'extra' => ['rows' => 2]],
        ['name' => 'fp_faq_explore_label', 'label' => 'FAQ · 按钮文字', 'type' => 'text', 'zh' => '探索更多', 'en' => 'Explore More'],
        ['name' => 'fp_faq1_q', 'label' => 'FAQ 1 · 问题', 'type' => 'text', 'zh' => '定制一位数字员工需要多长时间？', 'en' => 'How long does it take to customize a digital employee?'],
        ['name' => 'fp_faq1_a', 'label' => 'FAQ 1 · 回答', 'type' => 'textarea', 'zh' => '这取决于定制的复杂程度。基础模型微调通常需要2-4周，而完全定制化可能需要8-12周。', 'en' => 'This depends on the complexity of the customization. Basic model fine-tuning typically takes 2-4 weeks, while full customization may require 8-12 weeks.', 'extra' => ['rows' => 4]],
        ['name' => 'fp_faq2_q', 'label' => 'FAQ 2 · 问题', 'type' => 'text', 'zh' => '数字员工的知识库可以实时更新吗？', 'en' => "Can a digital employee's knowledge base be updated in real-time?"],
        ['name' => 'fp_faq2_a', 'label' => 'FAQ 2 · 回答', 'type' => 'textarea', 'zh' => '是的，我们的系统支持通过API进行实时知识库更新。', 'en' => 'Yes, our system supports real-time knowledge base updates via API.', 'extra' => ['rows' => 4]],
        ['name' => 'fp_faq3_q', 'label' => 'FAQ 3 · 问题', 'type' => 'text', 'zh' => '如何保障数据隐私与安全？', 'en' => 'How do you ensure data privacy and security?'],
        ['name' => 'fp_faq3_a', 'label' => 'FAQ 3 · 回答', 'type' => 'textarea', 'zh' => '我们采用企业级加密标准，所有交互数据均在本地或专属私有云中处理。', 'en' => 'We employ enterprise-grade encryption standards. All interaction data is processed in local or dedicated private clouds.', 'extra' => ['rows' => 4]],

        ['name' => 'fp_cta_title', 'label' => 'CTA · 标题', 'type' => 'textarea', 'zh' => '开启您的 AI 雇佣之旅', 'en' => 'Begin Your AI Hiring Journey', 'extra' => ['rows' => 1]],
        ['name' => 'fp_cta_desc', 'label' => 'CTA · 描述', 'type' => 'textarea', 'zh' => '与我们的团队对话，打造专属您的数字员工阵容。', 'en' => 'Speak with our team and craft a digital workforce made for you.', 'extra' => ['rows' => 2]],
        ['name' => 'fp_cta_btn_title', 'label' => 'CTA · 按钮文字', 'type' => 'text', 'zh' => '联系我们', 'en' => 'Contact Us'],
        ['name' => 'fp_cta_btn_url', 'label' => 'CTA · 按钮地址', 'type' => 'text', 'zh' => '/contact/', 'en' => '/contact/'],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'front-page.php']],
        [['param' => 'front_page', 'operator' => '==', 'value' => '1']],
    ]));

    /* ---- 3. AI 数字员工列表页 ---- */
    acf_add_local_field_group($hireai_make_group('group_page_ai_employees', 'AI 数字员工页', [
        ['name' => 'lookbook_hero_kicker', 'label' => '页眉眉题', 'type' => 'text', 'zh' => '数字工坊', 'en' => 'The Atelier'],
        ['name' => 'lookbook_hero_title', 'label' => '页眉标题', 'type' => 'textarea', 'zh' => '精英数字解决方案', 'en' => 'Elite Digital Solutions', 'extra' => ['rows' => 1]],
        ['name' => 'lookbook_hero_subtitle', 'label' => '页眉副标题', 'type' => 'textarea', 'zh' => '"AI 主导流程，人类交付成果。"', 'en' => '"AI-led process, Human-delivered results."', 'extra' => ['rows' => 2]],
        ['name' => 'lookbook_cta_heading', 'label' => 'CTA 标题', 'type' => 'text', 'zh' => '准备好重新定义人性了吗？', 'en' => 'Ready to Redefine Humanity?'],
        ['name' => 'lookbook_cta_sub', 'label' => 'CTA 副标题', 'type' => 'textarea', 'zh' => '加入运用 Aurelian AI 专属生态的领袖精英之列。', 'en' => "Join the exclusive echelon of leaders leveraging Aurelian AI's bespoke ecosystem.", 'extra' => ['rows' => 2]],
        ['name' => 'lookbook_cta_btn', 'label' => 'CTA 主按钮', 'type' => 'text', 'zh' => '开启旅程', 'en' => 'Start The Journey'],
        ['name' => 'lookbook_cta_link', 'label' => 'CTA 文字链接', 'type' => 'text', 'zh' => '下载品牌手册', 'en' => 'Download Brand Book'],
        ['name' => 'lookbook_cta_url', 'label' => 'CTA 链接地址', 'type' => 'text', 'zh' => '/case-insights/', 'en' => '/case-insights/'],
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
        ['name' => 'employee_kicker', 'label' => '栏目标签（kicker）', 'type' => 'text', 'zh' => '战略精英', 'en' => 'Strategic Elite'],
        ['name' => 'employee_button_text', 'label' => '按钮文字', 'type' => 'text', 'zh' => '探索', 'en' => 'Inquire'],
        ['name' => 'employee_button_style', 'label' => '按钮样式', 'type' => 'select', 'zh' => 'auto', 'en' => 'auto', 'extra' => ['choices' => ['auto' => '自动（交替）', 'filled' => '深色实底', 'outline' => '金色描边'], 'default_value' => 'auto']],
        ['name' => 'employee_link', 'label' => '按钮/图片链接', 'type' => 'link', 'zh' => ['url' => '', 'title' => ''], 'en' => ['url' => '', 'title' => '']],
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
        ['name' => 'header_logo', 'label' => '页眉 Logo', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],
        ['name' => 'footer_logo', 'label' => '页脚 Logo', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],
        ['name' => 'header_consult_label', 'label' => '页眉咨询按钮文字', 'type' => 'text', 'zh' => '预约咨询', 'en' => 'Consultation'],
        ['name' => 'footer_copyright', 'label' => '版权信息', 'type' => 'text', 'zh' => '© 2026 聘AI（Hire AI People）。保留所有权利。', 'en' => '© 2026 Hire AI People. All rights reserved.'],
        ['name' => 'footer_slogan', 'label' => '品牌 Slogan', 'type' => 'text', 'zh' => '雇佣智慧 · 臻于艺术', 'en' => 'Hire Intelligence, Artfully Employed.'],
        ['name' => 'footer_desc', 'label' => '页脚介绍', 'type' => 'textarea', 'zh' => 'AI 数字员工与 AI 解决方案平台——以极简奢华之姿，重塑企业智能雇佣。', 'en' => 'A platform for AI digital employees and AI solutions—reshaping intelligent hiring with minimalist luxury.', 'extra' => ['rows' => 3]],
    ], [
        [['param' => 'options_page', 'operator' => '==', 'value' => 'hireai-settings']],
    ]));
});
