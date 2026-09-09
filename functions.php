<?php if (!defined('ABSPATH')) exit;

/* -------------------------------------------------------------------------
 * 0.0 GitHub 自动更新（plugin-update-checker）
 *     push main + tag + Release上传ZIP后，WP后台外观→主题自动提示更新
 *     style.css Version: X.Y.Z = 版本号，Release tag: vX.Y.Z，ZIP: HAP-2026-vX.Y.Z.zip
 *
 * v2.2.6 改:对齐 Allscented 的 PUC 默认配置——不调 setBranch/setReleaseAsset,
 *         让 PUC 读 GitHub Releases API(必须确保 release isDraft=false)
 *         style.css 增加 Theme URI 字段,PUC 默认就能找到 repo
 * ---------------------------------------------------------------------- */
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

if (!defined('HIREAI_SKIP_UPDATE_CHECKER')) {
    require_once __DIR__ . '/lib/plugin-update-checker.php';
    $hireai_update_checker = PucFactory::buildUpdateChecker(
        'https://github.com/sasha2026-git/HAP-2026/',
        get_stylesheet_directory() . '/style.css',
        'hireaipeople'
    );
    // 不调 setBranch/setReleaseAsset,让 PUC 用默认 stable-tag 模式从 GitHub Releases API 检测
}

/**
 * 聘AI (Hire AI People) Child — Hello Elementor 子主题
 * functions.php
 *
 * 职责：资源加载 / 导航 / WooCommerce 支持 / ACF 字段注册（双语方案 B）/
 *       辅助函数回退 / 联系表单处理 / 分页
 */

// v3.5.7-p18: HIREAI_VERSION 强化 —— 每次调用实时读 style.css（不用常量缓存）
//   - wp_get_theme()->get('Version') 有静态缓存 + get_file_data() 在某些环境下也会被 OPcache 拦截
//   - 旧方案 define('HIREAI_VERSION', ...) 一旦声明,WP 全局常量后续不可改
//   - 新方案 hireai_get_version() 每次 wp_loaded 自动检测版本变化,触发强制清缓存
if (!function_exists('hireai_get_version')) {
    function hireai_get_version() {
        $theme_data = get_file_data(get_stylesheet_directory() . '/style.css', ['Version' => 'Version']);
        return !empty($theme_data['Version']) ? $theme_data['Version'] : '1.0.0';
    }
}
if (!defined('HIREAI_VERSION')) {
    define('HIREAI_VERSION', hireai_get_version());
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
        'employee'       => ['title' => '数字员工目录', 'template' => 'page-employee-detail.php'],
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
 * 0.1 数字员工详情页重写规则：/employee/<slug>/ -> 单页 + 模板 page-employee-detail.php
 *      兼容 Aurelian Prime 风格，每个数字员工是独立 WP 子页面，slug = employee-<name>
 * ---------------------------------------------------------------------- */
add_action('init', function () {
    add_rewrite_rule(
        '^employee/([^/]+)/?$',
        'index.php?pagename=employee&hireai_emp_slug=$matches[1]',
        'top'
    );
}, 20);

add_filter('query_vars', function ($vars) {
    $vars[] = 'hireai_emp_slug';
    return $vars;
});

add_action('pre_get_posts', function ($q) {
    if (!is_admin() && $q->is_main_query() && $q->get('pagename') === 'employee' && ($slug = $q->get('hireai_emp_slug'))) {
        // 让页头/页脚用 page-employee-detail 模板（父页面）
        $q->set('pagename', 'employee');
        // 不修改主查询：靠模板层面 get_page_by_path() 取具体员工
    }
});

/* -------------------------------------------------------------------------
 * 0.2 自动把每个 employee-* 页面也映射到 /employee/<slug>/
 *      在页面保存时清 rewrite cache，使新规则立即生效
 * ---------------------------------------------------------------------- */
add_action('save_post_page', function ($post_id, $post) {
    if (wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
        return;
    }
    if ($post->post_status !== 'publish') {
        return;
    }
    // 仅当 slug 以 employee- 开头才重建 rewrite 缓存
    if (strpos($post->post_name, 'employee-') === 0) {
        flush_rewrite_rules(false);
    }
}, 20, 2);

/* v3.0.8 (Bug A): 页面保存时清 transient + ACF cache
 *   - 防止 Sasha 编辑 ACF 图片后前台读旧值（LiteSpeed/对象缓存/ACF JSON sync）
 *   - 不修改 OBJECT CACHE 本身（依赖 LiteSpeed 自动失效）
 */
add_action('save_post_page', function ($post_id, $post) {
    if (wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
        return;
    }
    // 清 ACF field values 缓存（WP Object Cache）
    if (function_exists('wp_cache_delete_group')) {
        wp_cache_delete_group('acf_field_values');
    }
    // 清 LiteSpeed Cache（如果安装）
    if (class_exists('LiteSpeed_Cache_API')) {
        do_action('litespeed_purge_post', $post_id);
    }
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[hireai v3.0.8] page_save cache_clear: post_id=' . $post_id);
    }
}, 30, 2);

/* v3.0.8 (Bug A): ACF save_post 清对应页面的对象缓存（更精细触发）
 *   - ACF update_field 时立刻清 wp_cache（避免 ACF 内部缓存保留旧值）
 */
add_action('acf/save_post', function ($post_id) {
    if (!function_exists('wp_cache_delete_group')) return;
    // ACF 在 meta cache 中以 _acf_meta_<post_id> 为 key 缓存所有字段值
    wp_cache_delete($post_id, 'post_meta');
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[hireai v3.0.8] acf_save cache_clear: post_id=' . $post_id);
    }
}, 20);


/* -------------------------------------------------------------------------
 * 0. 辅助函数（带默认值回退，ACF 未装时优雅降级）
 * ---------------------------------------------------------------------- */

/**
 * 当前语言后缀（双语方案 B）：无 Polylang 时默认 zh
 */
function hireai_lang_suffix() {
    /* v3.0.5 hotfix: 优先读 hireai_lang cookie（JS hireaiSwitchLang 切换后写 cookie + 刷新页面），
     * 只有 cookie 没设置时才回退到 Polylang。这样点 EN 后刷新，服务端正确返回 _en。 */
    $cookie_lang = isset($_COOKIE['hireai_lang']) ? trim((string) $_COOKIE['hireai_lang']) : '';
    if ($cookie_lang === 'en') {
        return '_en';
    }
    if ($cookie_lang === 'zh') {
        return '_zh';
    }
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
        // v3.0.4 hotfix: 防御 ACF 把 text 字段错配成 link/image/repeater 时返回数组
        // ——返回数组会导致 esc_html() 输出 "Array" 或触发 PHP Warning。
        if (is_array($v)) {
            // 若是 ACF link 风格数组（['title'=>...,'url'=>...,'target'=>...]) 且 title 非空，提取 title
            if (isset($v['title']) && is_string($v['title']) && $v['title'] !== '') {
                return $v['title'];
            }
            // 否则一律回退到默认值（避免 "Array" 泄漏到前端）
            return $default;
        }
        if ($v !== null && $v !== '' && $v !== false) {
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
 *
 * v3.0.8 (Bug A): 多 suffix 回退链 — `fp_prod1_image_zh` 找不到时自动尝试
 *   1. 同 suffix（如 `_zh`）
 *   2. 无 suffix（用户手动创建字段时常见）
 *   3. 反向 suffix（`_en` ↔ `_zh`）
 *   4. 默认资产
 */
function site_image_url($name, $default = '', $post_id = false) {
    if (function_exists('get_field')) {
        $v = $post_id ? get_field($name, $post_id) : get_field($name);
        if (site_image_url_is_valid($v)) {
            return site_image_url_resolve($v, $default);
        }
        // v3.0.8: suffix fallback chain — 尝试无 suffix + 反向 suffix
        if ($post_id === false && preg_match('/_([a-z]{2,3})$/', $name, $m)) {
            $cur_suffix = $m[1];
            $base = substr($name, 0, -strlen($cur_suffix));
            // 顺序：无 suffix → 反向 suffix
            $alt_suffixes = ['', ($cur_suffix === 'zh' ? '_en' : '_zh')];
            foreach ($alt_suffixes as $alt) {
                $alt_name = $base . $alt;
                if ($alt_name === $name) continue;
                $v2 = get_field($alt_name);
                if (site_image_url_is_valid($v2)) {
                    return site_image_url_resolve($v2, $default);
                }
            }
        }
    }
    return $default;
}

/**
 * v3.0.8 (Bug A) helper — 判断 ACF image 字段值是否"有内容"
 */
function site_image_url_is_valid($v) {
    if (is_array($v)) {
        return !empty($v['url']) || !empty($v['ID']) || !empty($v['id']);
    }
    if (is_string($v)) return $v !== '';
    if (is_numeric($v)) return intval($v) > 0;
    return $v !== null && $v !== false && $v !== '';
}

/**
 * v3.0.8 (Bug A) helper — 把 ACF image 字段值统一解析成 URL 字符串
 */
function site_image_url_resolve($v, $default = '') {
    if (is_array($v)) {
        if (!empty($v['url'])) return $v['url'];
        if (!empty($v['ID'])) {
            $url = wp_get_attachment_image_url($v['ID'], 'full');
            if ($url) return $url;
        }
        if (!empty($v['id'])) {
            $url = wp_get_attachment_image_url($v['id'], 'full');
            if ($url) return $url;
        }
    }
    if (is_string($v) && $v !== '') {
        // v3.0.8: 容错非标准 URL（相对路径 / 不带协议的本地路径）
        if (filter_var($v, FILTER_VALIDATE_URL)) return $v;
        // 相对路径或本地路径 → 当成 URL 返回（避免被 default 吞掉）
        if (strpos($v, '/') === 0 || strpos($v, './') === 0) return $v;
        return $v;
    }
    if (is_numeric($v) && intval($v) > 0) {
        $url = wp_get_attachment_image_url(intval($v), 'full');
        return $url ?: $default;
    }
    return $default;
}



/**
 * v3.0.8 hotfix — 兼容层：v3.0.8 commit 不小心删了 hireai_image()，导致 Fatal error。
 * 加回原函数（与 v3.0.7 完全一致），保持所有调用点工作。
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
        'public' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/>',
        'diamond'=> '<path d="M6 3h12l3 6-9 12L3 9z"/><path d="M6 3l3 6M18 3l-3 6M3 9h18"/>',
        'token'  => '<circle cx="12" cy="12" r="9"/><path d="M9 9l6 6M15 9l-6 6"/>',
        'sparkle'=> '<path d="M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5z"/><path d="M19 14l.7 2.3L22 17l-2.3.7L19 20l-.7-2.3L16 17l2.3-.7z"/>',
        'arrow-forward' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'content-copy' => '<rect x="8" y="8" width="12" height="12" rx="1"/><path d="M16 8V5a1 1 0 00-1-1H5a1 1 0 00-1 1v10a1 1 0 001 1h3"/>',
        'shield-star' => '<path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M12 8l1.2 2.4L15.5 11l-2.3.6L12 14l-1.2-2.4L8.5 11l2.3-.6z"/>',
        'star'   => '<path d="M12 2l2.5 6.5L21 9l-5 4.5L17.5 21 12 17.5 6.5 21 8 13.5 3 9l6.5-.5z"/>',
        'gem'    => '<path d="M6 3h12l3 6-9 12L3 9z"/>',
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
 * 0.5. v3.0.7 数据集成修复：智能 slug 探测（WP_DEBUG + 中文 slug 友好）
 * ---------------------------------------------------------------------- */

/**
 * 智能探测 category ID — 多 slug + 中文名 fallback
 * 用于：cases / insights / ai-employee 这类可能只有中文 slug 的分类
 *
 * @param array $candidates 候选 slug 或 category 名（如 ['cases', 'case', '案例']）
 * @return int category term_id；找不到返回 0
 */
function hireai_find_category_id($candidates) {
    $candidates = is_array($candidates) ? $candidates : [$candidates];
    foreach ($candidates as $key) {
        $key = trim((string) $key);
        if ($key === '') continue;
        // 先按 slug 找
        $term = get_category_by_slug($key);
        if ($term && !is_wp_error($term)) {
            return (int) $term->term_id;
        }
        // slug 找不到 → 按中文名 / 显示名找（get_cat_ID 支持 name）
        $cid = get_cat_ID($key);
        if ($cid) return (int) $cid;
    }
    return 0;
}

/**
 * v3.0.8 (Bug D) — 列出全部 category（slug + name + count），仅 WP_DEBUG + admin 时用
 *
 * @return array 形如 [['slug'=>'cases','name'=>'案例','count'=>12], ...]
 */
function hireai_list_all_categories() {
    if (!function_exists('get_categories')) return [];
    $cats = get_categories(['hide_empty' => false, 'taxonomy' => 'category']);
    $out = [];
    foreach ($cats as $c) {
        $out[] = [
            'slug'  => $c->slug,
            'name'  => $c->name,
            'count' => (int) $c->count,
        ];
    }
    return $out;
}

/**
 * v3.0.8 (Bug D) — 探测不到 cases_cat_id 时 fallback 到「除 insights 外的所有 category」
 *
 * @param int $exclude_cat_id 排除的 category（如 insights_cat_id）
 * @return int category term_id；找不到返回 0
 */
function hireai_fallback_post_category_id($exclude_cat_id = 0) {
    if (!function_exists('get_categories')) return 0;
    $cats = get_categories([
        'hide_empty' => true,
        'taxonomy'   => 'category',
        'exclude'    => $exclude_cat_id ? [(int) $exclude_cat_id] : [],
    ]);
    foreach ($cats as $c) {
        if ((int) $c->term_id !== (int) $exclude_cat_id && (int) $c->count > 0) {
            return (int) $c->term_id;
        }
    }
    return 0;
}

/**
 * v3.5.7-p16: 扩展 product_cat 中文候选词 — 覆盖 page-ai-solutions.php 默认 $cards 的 kicker_zh 全部 9 个
 *   - 公关危机 / 整合营销 / 电商视觉 / 创意设计 / 酒店服务 / 数据洞察 / 金融风控 / 空间美学 / 企业服务
 *   - 同时补 slug / 拼音 / 通用 slug 的英文候选,提高探测命中率
 *
 * @return array<string> 候选 slug/name 列表(供 hireai_find_product_category_id 探测)
 */
function hireai_product_category_candidates() {
    return [
        // 通用 slug/name
        'solution', 'solutions', 'product', 'products', 'ai-solution', 'ai-solutions',
        'shop', 'store', '商城',
        '解决方案', '商品', 'AI解决方案', '产品', '服务', '解决方案商城',
        // kicker_zh 全部 9 个(中文 → slug → 拼音/英文)
        '公关危机', '危机公关', 'crisis-counsel', 'crisis', 'pr-crisis',
        '整合营销', 'integrated-marketing', 'marketing',
        '电商视觉', 'e-commerce', 'ecommerce', 'retail',
        '创意设计', 'creative-design', 'creative', 'design',
        '酒店服务', 'hospitality', 'hotel',
        '数据洞察', 'data-insight', 'data', 'insight',
        '金融风控', 'finance', 'risk-control', 'fintech',
        '空间美学', 'spatial', 'aesthetics', 'space',
        '企业服务', 'enterprise', 'enterprise-service',
        // 兜底关键词
        '公关', '营销', '电商', '设计', '酒店', '数据', '金融', '空间', '企业',
        '创意', '服务', '零售', '医疗', '娱乐', '教育',
    ];
}

/**
 * v3.0.8 (Bug E) — 智能探测 WC product_cat term_id
 *
 * v3.5.7-p16: 接受 "default" 关键字时,自动加载 hireai_product_category_candidates() 全集
 *            (覆盖 page-ai-solutions.php 默认 $cards kicker_zh 全部 9 个)
 *
 * @param array|string $candidates 候选 slug / term name,或 'default'
 * @return int product_cat term_id;找不到返回 0
 */
function hireai_find_product_category_id($candidates) {
    if (!taxonomy_exists('product_cat')) return 0;
    if ($candidates === 'default' || (is_array($candidates) && count($candidates) === 1 && $candidates[0] === 'default')) {
        $candidates = hireai_product_category_candidates();
    }
    $candidates = is_array($candidates) ? $candidates : [$candidates];
    foreach ($candidates as $key) {
        $key = trim((string) $key);
        if ($key === '') continue;
        // 1. 按 slug 找
        $term = get_term_by('slug', $key, 'product_cat');
        if ($term && !is_wp_error($term)) return (int) $term->term_id;
        // 2. 按 name 找
        $term = get_term_by('name', $key, 'product_cat');
        if ($term && !is_wp_error($term)) return (int) $term->term_id;
    }
    return 0;
}

/**
 * v3.5.7-p16: tax_query 去重 — 同一 taxonomy 多次 push 时只保留最后一次
 *   - 修 v3.5.7-p15 bug: page-ai-solutions.php 的 product_cat 在某些路径下被 push 两次
 *   - 返回干净的 tax_query 数组(已按 taxonomy 去重 + 自动补 relation='AND')
 *
 * @param array $tax_query tax_query 项列表(每项: ['taxonomy','field','terms','operator'])
 * @return array 清洗后的 tax_query
 */
function hireai_dedupe_tax_query($tax_query) {
    if (!is_array($tax_query) || empty($tax_query)) return [];
    $by_tax    = [];
    $non_assoc = [];
    foreach ($tax_query as $key => $item) {
        if (!is_array($item)) {
            $non_assoc[$key] = $item;
            continue;
        }
        $tax = isset($item['taxonomy']) ? (string) $item['taxonomy'] : '';
        if ($tax === '') continue;
        $by_tax[$tax] = $item;
    }
    $out = array_values($by_tax);
    if (count($out) > 1 && !isset($non_assoc['relation'])) {
        $out['relation'] = 'AND';
    } elseif (isset($non_assoc['relation'])) {
        $out['relation'] = $non_assoc['relation'];
    }
    return $out;
}

/**
 * v3.0.8 (Bug E) — 列出所有 product_cat（slug + name + count），仅 WP_DEBUG + admin 时用
 *
 * @return array
 */
function hireai_list_all_product_categories() {
    if (!taxonomy_exists('product_cat')) return [];
    $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
    if (is_wp_error($terms)) return [];
    $out = [];
    foreach ($terms as $t) {
        $out[] = [
            'slug'  => $t->slug,
            'name'  => $t->name,
            'count' => (int) $t->count,
        ];
    }
    return $out;
}

/**
 * v3.5.7-p18 Task B: 7 个场景分类清单(用于 AI 解决方案商城 tab)
 *   - 全部 + 7 个场景,共 8 个选项
 *   - name_zh / name_en 双语 fallback(ACF 未填值时回退到此处)
 *   - 调用 page-ai-solutions.php 渲染 tab UI
 *
 * @return array<string,array{slug:string,name_zh:string,name_en:string,is_scene:bool}>
 */
function hireai_list_solution_categories() {
    return [
        ''              => ['slug' => '',          'name_zh' => '全部',           'name_en' => 'All',           'is_scene' => false],
        'brand-ip'      => ['slug' => 'brand-ip', 'name_zh' => '品牌&IP',        'name_en' => 'Brand & IP',    'is_scene' => true],
        'marketing'     => ['slug' => 'marketing','name_zh' => '市场营销',       'name_en' => 'Marketing',     'is_scene' => true],
        'visual-design' => ['slug' => 'visual-design','name_zh' => '视觉设计',   'name_en' => 'Visual Design', 'is_scene' => true],
        'ecommerce'     => ['slug' => 'ecommerce','name_zh' => '电商&网站',      'name_en' => 'E-commerce & Website', 'is_scene' => true],
        'crisis'        => ['slug' => 'crisis',   'name_zh' => '公关危机',       'name_en' => 'Crisis',        'is_scene' => true],
        'copywriting'   => ['slug' => 'copywriting','name_zh' => '文案报告',     'name_en' => 'Copywriting',   'is_scene' => true],
        'custom'        => ['slug' => 'custom',   'name_zh' => '定制方案',       'name_en' => 'Custom',        'is_scene' => true],
    ];
}

/**
 * v3.5.7-p18 Task B: 把 product ID 列表转换为 cards 数组(page-ai-solutions.php 渲染用)
 *   - 提取 title / excerpt / image / price / stock / kicker_zh-en / retainer_zh-en / persona slugs
 *   - 自动跳过不可访问 / 非 publish 的商品
 *   - 若全部失败返回空数组(由 UI 显示 empty_text)
 *
 * @param array $ids product post IDs
 * @return array<int,array> card 数据
 */
function hireai_build_solution_cards_from_ids($ids) {
    if (!is_array($ids) || empty($ids)) return [];
    if (!function_exists('wc_get_product')) return [];
    $cards = [];
    foreach ($ids as $pid) {
        $pid = (int) $pid;
        $wc_post_obj = get_post($pid);
        if (!$wc_post_obj || $wc_post_obj->post_status !== 'publish') continue;
        $wc_prev_post = $GLOBALS['post'] ?? null;
        $GLOBALS['post'] = $wc_post_obj;
        setup_postdata($wc_post_obj);
        $wc_obj  = wc_get_product($pid);
        $price_html = '';
        $stock      = 'instock';
        if (is_object($wc_obj)) {
            if (function_exists('wc_format_price_range')) {
                $price_html = wc_format_price_range($wc_obj);
            } elseif (method_exists($wc_obj, 'get_price_html')) {
                $price_html = $wc_obj->get_price_html();
            }
            if (method_exists($wc_obj, 'get_stock_status')) {
                $ss = $wc_obj->get_stock_status();
                $stock = ($ss === false || $ss === '' || $ss === null) ? 'instock' : (string) $ss;
            }
        }
        /* 双语 ACF 字段(kicker=product_operative, retainer=product_retainer_label) */
        $kz_raw   = function_exists('hireai_field_lang') ? hireai_field_lang('product_operative',       'zh', '', $pid) : (function_exists('hireai_field') ? hireai_field('product_operative',       '', $pid) : '');
        $ken_raw  = function_exists('hireai_field_lang') ? hireai_field_lang('product_operative',       'en', '', $pid) : '';
        $rtz_raw  = function_exists('hireai_field_lang') ? hireai_field_lang('product_retainer_label', 'zh', '', $pid) : (function_exists('hireai_field') ? hireai_field('product_retainer_label', '', $pid) : '');
        $rten_raw = function_exists('hireai_field_lang') ? hireai_field_lang('product_retainer_label', 'en', '', $pid) : '';
        $kicker_zh   = is_array($kz_raw)   ? '' : (string) $kz_raw;
        $kicker_en   = is_array($ken_raw)  ? '' : (string) $ken_raw;
        $retainer_zh = is_array($rtz_raw)  ? '' : (string) $rtz_raw;
        $retainer_en = is_array($rten_raw) ? '' : (string) $rten_raw;
        /* 提取数字人标签(用于 client-side persona 过滤)
         * v3.5.7-p21: 优先 product_tag(Echo 数据完整,5 个数字人 slug),product_cat fallback
         *   - 短 slug(victoria/adrian/...)和完整 slug(victoria-brand-pr/...)都接受
         *   - 每张卡片打 data-personas="short1 short2 ..."(短名),JS chip 切换按短名匹配
         */
        $persona_slugs = [];
        $dh_map        = function_exists('hireai_digital_humans') ? hireai_digital_humans() : [];
        $dh_short_set  = array_keys($dh_map);
        $dh_full_to_short = [];
        foreach ($dh_map as $short => $info) {
            if (!empty($info['full'])) { $dh_full_to_short[$info['full']] = $short; }
        }
        /* 1) product_tag 优先(Echo 数据) */
        $tag_terms = function_exists('wp_get_post_terms') ? wp_get_post_terms($pid, 'product_tag', ['fields' => 'slugs']) : [];
        if (!is_wp_error($tag_terms) && !empty($tag_terms)) {
            foreach ($tag_terms as $slug) {
                if ($slug === '' || $slug === null) continue;
                if (in_array($slug, $dh_short_set, true)) {
                    if (!in_array($slug, $persona_slugs, true)) { $persona_slugs[] = $slug; }
                } elseif (isset($dh_full_to_short[$slug])) {
                    $short = $dh_full_to_short[$slug];
                    if (!in_array($short, $persona_slugs, true)) { $persona_slugs[] = $short; }
                }
            }
        }
        /* 2) product_cat fallback(旧数据,Echo 9/8 之前) */
        if (empty($persona_slugs) && function_exists('hireai_list_solution_categories')) {
            $scene_slugs = array_map(function ($c) { return $c['slug']; }, array_filter(hireai_list_solution_categories(), function ($c) { return !empty($c['is_scene']); }));
        } else {
            $scene_slugs = ['brand-ip','marketing','visual-design','ecommerce','crisis','copywriting','custom'];
        }
        $cat_terms = function_exists('wp_get_post_terms') ? wp_get_post_terms($pid, 'product_cat', ['fields' => 'slugs']) : [];
        if (!is_wp_error($cat_terms) && !empty($cat_terms)) {
            foreach ($cat_terms as $slug) {
                if ($slug === '' || $slug === null) continue;
                if (in_array($slug, $scene_slugs, true)) continue;
                if (in_array($slug, $dh_short_set, true)) {
                    if (!in_array($slug, $persona_slugs, true)) { $persona_slugs[] = $slug; }
                } elseif (isset($dh_full_to_short[$slug])) {
                    $short = $dh_full_to_short[$slug];
                    if (!in_array($short, $persona_slugs, true)) { $persona_slugs[] = $short; }
                } elseif (!in_array($slug, $persona_slugs, true)) {
                    $persona_slugs[] = $slug;
                }
            }
        }
        $cards[] = [
            'id'          => $pid,
            'title'       => get_the_title(),
            'excerpt'     => get_the_excerpt() ?: wp_trim_words(strip_tags(get_the_content()), 24, '…'),
            'image'       => get_the_post_thumbnail_url($pid, 'medium'),
            'price'       => $price_html,
            'stock'       => $stock,
            'permalink'   => get_permalink($pid),
            'kicker_zh'   => $kicker_zh,
            'kicker_en'   => $kicker_en,
            'retainer_zh' => $retainer_zh,
            'retainer_en' => $retainer_en,
            'personas'    => $persona_slugs,
        ];
        wp_reset_postdata();
        if ($wc_prev_post) { $GLOBALS['post'] = $wc_prev_post; setup_postdata($wc_prev_post); }
    }
    wp_reset_postdata();
    return $cards;
}

/**
 * v3.5.7-p18 Task B: 把 card 数据映射到前端模板用的字段
 *   - is_cta 强制 false(已废弃 CTA 卡)
 *   - 标题/描述 双语(zh 优先,缺失时 fallback en)
 *
 * @param array $p card 数据(从 hireai_build_solution_cards_from_ids 返回)
 * @return array
 */
function hireai_solution_card_template_fields($p) {
    $kz_zh = isset($p['kicker_zh'])   && !is_array($p['kicker_zh'])   ? (string) $p['kicker_zh']   : '';
    $kz_en = isset($p['kicker_en'])   && !is_array($p['kicker_en'])   ? (string) $p['kicker_en']   : $kz_zh;
    $rt_zh = isset($p['retainer_zh']) && !is_array($p['retainer_zh']) ? (string) $p['retainer_zh'] : '';
    $rt_en = isset($p['retainer_en']) && !is_array($p['retainer_en']) ? (string) $p['retainer_en'] : $rt_zh;
    return [
        'kicker_zh'   => $kz_zh,
        'kicker_en'   => $kz_en,
        'retainer_zh' => $rt_zh,
        'retainer_en' => $rt_en,
        'title_zh'    => (string) $p['title'],
        'title_en'    => (string) $p['title'],
        'desc_zh'     => (string) $p['excerpt'],
        'desc_en'     => (string) $p['excerpt'],
        'price'       => isset($p['price']) ? (string) $p['price'] : '',
        'image'       => !empty($p['image']) ? $p['image'] : 'defaults/solution-1.jpg',
        'is_cta'      => false,
        'link'        => (string) $p['permalink'],
        'personas'    => isset($p['personas']) ? (array) $p['personas'] : [],
    ];
}

/**
 * v3.5.7-p18 Task B: 数字人分类清单(独立维度,与场景 AND 关系)
 *   - 自动从 WC product_cat term 抽出(排除 7 个场景分类)
 *   - hide_empty=true:只列有商品的数字人分类
 *   - 调用 page-ai-solutions.php 渲染 persona chip UI
 *
 * @return array<string,array{slug:string,name:string,count:int}>
 */
function hireai_list_solution_personas() {
    if (!taxonomy_exists('product_cat')) return [];
    /* 场景 slug 用于排除 */
    $scene_slugs = array_map(
        function ($c) { return $c['slug']; },
        array_filter(
            hireai_list_solution_categories(),
            function ($c) { return !empty($c['is_scene']); }
        )
    );
    $terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'exclude'    => [],  /* 不能直接 exclude slug 列表,只能循环过滤 */
    ]);
    if (is_wp_error($terms) || empty($terms)) return [];
    $personas = [];
    foreach ($terms as $t) {
        if (in_array($t->slug, $scene_slugs, true)) continue;
        $personas[$t->slug] = [
            'slug'  => $t->slug,
            'name'  => $t->name,
            'count' => (int) $t->count,
        ];
    }
    return $personas;
}

/**
 * 智能探测"数字员工"Post — 多 slug + 中文 category fallback
 * 返回 WP_Post[] 数组（按 menu_order 排序）
 *
 * @param int $limit 拉取数量
 * @return array WP_Post 列表（可能为空数组）
 */
function hireai_resolve_employees($limit = 6) {
    // 候选：英文 slug + 中文 category 名（覆盖 v3.0.6 仅 ai-employee 失败场景）
    $cat_id = hireai_find_category_id([
        'ai-employee', 'ai-employees', 'digital-employees',
        'employee', 'employees', 'AI数字员工', '数字员工',
    ]);
    $query_args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => max(1, (int) $limit),
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ];
    if ($cat_id > 0) {
        $query_args['cat'] = $cat_id;
    } else {
        // 兜底：拿最近 post（避免返回空）
        $query_args['category_name'] = 'ai-employee';
    }
    $posts = get_posts($query_args);

    if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
        error_log('[hireai v3.0.7] emp_search: cat_id=' . $cat_id . ', limit=' . $limit . ', found=' . count($posts));
    }
    return is_array($posts) ? $posts : [];
}

/**
 * 单个数字员工的强 URL — 用 get_permalink 让 WP 自动处理中文 slug 编码
 *
 * @param int $index 第 N 个员工（0-based）
 * @param string $fallback_url 找不到时的兜底 URL（默认 /ai-employees/ 列表页）
 * @return string permalink 或 fallback
 */
function hireai_resolve_employee_url($index = 0, $fallback_url = '') {
    $employees = hireai_resolve_employees(max(6, (int) $index + 1));
    if (isset($employees[$index]) && $employees[$index] instanceof WP_Post) {
        $url = get_permalink($employees[$index]->ID);
        if ($url) return $url;
    }
    if ($fallback_url === '') {
        $fallback_url = home_url('/ai-employees/');
    }
    return $fallback_url;
}

/* -------------------------------------------------------------------------
 * 2.7. v3.5.7-p16 — 数据层 hooks(数据源抽象)
 *    让 page-ai-solutions.php / page-cases-insights.php 不再直接写 WP_Query
 *    而是调用这两个 hook,确保:
 *      - product_cat 探测+去重(修 v3.5.7-p15 product_cat 重复 push bug)
 *      - 缓存 key 命名一致(save_post / save_post_product 清缓存路径固定)
 *      - 双语 fallback 逻辑统一(zh 失败自动试英文 slug)
 * ---------------------------------------------------------------------- */

/**
 * v3.5.7-p18: 获取 AI 解决方案页面用的 WC 商品 ID 列表(已应用 tax_query + 去重)
 *   - 调用 page-ai-solutions.php 直接消费返回的 IDs
 *   - 缓存 key: hireai_solutions_cache_v2_p{N}_cat{SLUG}_per{PERSONA_SLUG}(与 save_post_product hook 一致)
 *   - product_visibility NOT IN(排除 hidden/search 屏蔽)
 *   - 7 场景 product_cat:brand-ip / marketing / visual-design / ecommerce / crisis / copywriting / custom
 *   - 数字人 product_cat:由 hireai_list_solution_personas() 动态从 DB 抽出(独立维度,与场景 AND 关系)
 *   - 探测失败时 fallback:不过滤 product_cat,让所有 publish product 都返回
 *
 * @param int    $paged         当前页码
 * @param int    $per_page      每页商品数
 * @param string $category_slug 场景分类 slug(留空=全部)
 * @param string $persona_slug  数字人分类 slug(留空=全部)
 * @return array<int> WC product post IDs
 */
function hireai_get_ai_solutions_products($paged = 1, $per_page = 9, $category_slug = '', $persona_slug = '') {
    if (!post_type_exists('product') || !function_exists('wc_get_product')) return [];
    $paged         = max(1, (int) $paged);
    $per_page      = max(1, (int) $per_page);
    $category_slug = is_string($category_slug) ? sanitize_key($category_slug) : '';
    $persona_slug  = is_string($persona_slug)  ? sanitize_key($persona_slug)  : '';

    /* v3.5.7-p18: 缓存 key 升级到 v2 + 双维度 slug 后缀(分类 + 数字人) */
    $cache_key = 'hireai_solutions_cache_v2_p' . $paged . '_cat' . $category_slug . '_per' . $persona_slug;

    $cached = get_transient($cache_key);
    if (is_array($cached)) {
        return array_map('intval', $cached);
    }

    // 1. 基础 tax_query:排除 catalog-visibility=hidden / search-excluded
    $tax_query = [[
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => ['exclude-from-catalog', 'exclude-from-search'],
        'operator' => 'NOT IN',
    ]];

    // 2. 场景分类筛选(可选)
    if (!empty($category_slug)) {
        $term = get_term_by('slug', $category_slug, 'product_cat');
        if ($term && !is_wp_error($term)) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => [(int) $term->term_id],
            ];
        }
    }

    // 3. 数字人筛选(独立维度,与场景 AND 关系)
    /* v3.5.7-p21: persona_slug 改走 product_tag(Echo 数据) + 短→长 slug 映射
     *   - 之前查 product_cat(Echo 没写) -> 数字人 chip 永远拉不到商品
     *   - 接受短名(victoria/adrian/iris/kai/evan)或完整 slug(victoria-brand-pr/...)
     *   - 优先查 product_tag;若 term 不存在,fallback 到 product_cat(兼容历史数据)
     */
    if (!empty($persona_slug)) {
        $effective_slug = function_exists('hireai_digital_human_full_slug')
            ? hireai_digital_human_full_slug($persona_slug)
            : $persona_slug;
        $term     = null;
        $tax_used = '';
        if (taxonomy_exists('product_tag')) {
            $term = get_term_by('slug', $effective_slug, 'product_tag');
            if ($term && !is_wp_error($term)) {
                $tax_used = 'product_tag';
            }
        }
        if ((!$term || is_wp_error($term)) && taxonomy_exists('product_cat')) {
            $fallback = get_term_by('slug', $effective_slug, 'product_cat');
            if ($fallback && !is_wp_error($fallback)) {
                $term     = $fallback;
                $tax_used = 'product_cat';
            }
        }
        if ($term && !is_wp_error($term) && $tax_used !== '') {
            $tax_query[] = [
                'taxonomy' => $tax_used,
                'field'    => 'term_id',
                'terms'    => [(int) $term->term_id],
            ];
        }
    }

    // 4. 两套筛选同时存在 → relation=AND
    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
    }

    // 5. 去重 + 兼容旧 hook 调用
    $tax_query = function_exists('hireai_dedupe_tax_query')
        ? hireai_dedupe_tax_query($tax_query)
        : $tax_query;

    // 6. 拉 IDs
    $q = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
        'fields'         => 'ids',
        'tax_query'      => $tax_query,
    ]);
    $ids = is_array($q->posts) ? array_map('intval', $q->posts) : [];
    wp_reset_postdata();

    set_transient($cache_key, $ids, 5 * MINUTE_IN_SECONDS);
    return $ids;
}

/**
 * v3.5.7-p16: 获取 Cases / Insights 文章 ID 列表
 *   - 调用 page-cases-insights.php 消费
 *   - $type: 'cases' (拉 4 篇) | 'insights' (拉 3 篇) | 其他按需
 *   - 双语 fallback: 'cases' 失败 → '案例';'insights' 失败 → '洞察'
 *   - 缓存 key: hireai_cases_cache_v1 / hireai_insights_cache_v1(与 save_post hook 一致)
 *
 * @param string $type 'cases' | 'insights' (其他值原样作为 category slug)
 * @param int    $limit 拉取数量
 * @return array<int> post IDs
 */
function hireai_get_cases_insights_posts($type = 'cases', $limit = 4) {
    $type  = trim((string) $type);
    $limit = max(1, (int) $limit);

    $cache_key_map = [
        'cases'    => 'hireai_cases_cache_v1',
        'insights' => 'hireai_insights_cache_v1',
    ];
    $cache_key = isset($cache_key_map[$type])
        ? $cache_key_map[$type]
        : 'hireai_' . sanitize_key($type) . '_cache_v1';

    $cached = get_transient($cache_key);
    if (is_array($cached)) {
        return array_map('intval', array_slice($cached, 0, $limit));
    }

    $cn_fallback_map = [
        'cases'    => '案例',
        'insights' => '洞察',
    ];

    // 第 1 次:英文 slug
    $q = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'category_name'  => $type,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
        'fields'         => 'ids',
    ]);
    $ids = is_array($q->posts) ? $q->posts : [];
    wp_reset_postdata();

    // 第 2 次:中文 slug fallback
    if (empty($ids) && isset($cn_fallback_map[$type])) {
        $q2 = new WP_Query([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'category_name'  => $cn_fallback_map[$type],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'  => true,
            'fields'         => 'ids',
        ]);
        $ids = is_array($q2->posts) ? $q2->posts : [];
        wp_reset_postdata();
    }

    $ids = array_map('intval', $ids);
    set_transient($cache_key, $ids, 5 * MINUTE_IN_SECONDS);
    return array_slice($ids, 0, $limit);
}

/* -------------------------------------------------------------------------
 * 0.6. v3.0.8 诊断 — 仅 WP_DEBUG + admin 角色下生效
 *     控制台输出 ACF fp_prodN_image / category / product_cat / cookie / post slug 状态
 * ---------------------------------------------------------------------- */
add_action('wp_footer', function () {
    if (!defined('WP_DEBUG') || !WP_DEBUG) return;
    if (!current_user_can('manage_options')) return;
    ?>
    <script id="hireai-v308-debug">
    console.group('[HIREAI v3.0.8 诊断]');
    try {
        console.log('All categories:', <?php
            $cats = hireai_list_all_categories();
            echo json_encode(array_map(fn($c) => $c['slug'] . '(' . $c['name'] . '):' . $c['count'], $cats));
        ?>);
        console.log('WC product_type:', <?php echo json_encode(post_type_exists('product') ? 'EXISTS' : 'NOT FOUND'); ?>);
        console.log('All product_cat:', <?php
            $cats = hireai_list_all_product_categories();
            echo json_encode(array_map(fn($c) => $c['slug'] . '(' . $c['name'] . '):' . $c['count'], $cats));
        ?>);
        console.log('hireai_lang cookie:', <?php echo json_encode($_COOKIE['hireai_lang'] ?? 'none'); ?>);
        console.log('Posts (CN slug 比例):', <?php
            $all = get_posts(['post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 50, 'fields' => 'ids']);
            $cn = 0;
            foreach ($all as $pid) {
                $p = get_post($pid);
                if ($p && !preg_match('/^[a-z0-9-]+$/', $p->post_name)) $cn++;
            }
            echo json_encode($cn . '/' . count($all));
        ?>);
        // v3.0.8 (Bug A): ACF fp_prodN_image_zh 实际值（确认字段名 + return_format）
        console.log('fp_prod1_image_zh:', <?php
            $v = function_exists('get_field') ? get_field('fp_prod1_image_zh') : '';
            echo json_encode(is_array($v) ? ('array:' . json_encode($v)) : (string) $v);
        ?>);
        console.log('fp_prod1_image (no suffix):', <?php
            $v = function_exists('get_field') ? get_field('fp_prod1_image') : '';
            echo json_encode(is_array($v) ? ('array:' . json_encode($v)) : (string) $v);
        ?>);
        console.log('fp_prod2_image_zh:', <?php
            $v = function_exists('get_field') ? get_field('fp_prod2_image_zh') : '';
            echo json_encode(is_array($v) ? ('array:' . json_encode($v)) : (string) $v);
        ?>);
        console.log('fp_prod3_image_zh:', <?php
            $v = function_exists('get_field') ? get_field('fp_prod3_image_zh') : '';
            echo json_encode(is_array($v) ? ('array:' . json_encode($v)) : (string) $v);
        ?>);
        // v3.0.8: cases / ai-employee category 探测结果（增强 candidate 列表）
        console.log('cases_cat_id:', <?php echo json_encode(hireai_find_category_id(['cases','case','casestudy','case-studies','case-showcase','case-collection','work','works','project','projects','portfolio','案例','案例研究','案例展示','案例集','我们的案例','项目案例'])); ?>);
        console.log('insights_cat_id:', <?php echo json_encode(hireai_find_category_id(['insights','insight','industry-insights','blog','news','article','articles','洞察','观点','行业洞察','我们的洞察'])); ?>);
        console.log('ai_employee_cat_id:', <?php echo json_encode(hireai_find_category_id(['ai-employee','ai-employees','digital-employees','employee','employees','AI数字员工','数字员工'])); ?>);
        console.log('product_cat_id:', <?php echo json_encode(hireai_find_product_category_id(['solution','solutions','product','products','ai-solution','ai-solutions','shop','store','解决方案','商品','AI解决方案','产品','服务','解决方案商城','公关','电商','零售','金融','医疗','娱乐'])); ?>);
    } catch (e) {
        console.error('diag error:', e);
    }
    console.groupEnd();
    </script>
    <?php
});


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
    // v3.5.7-p17 Bug 1 修复：父主题 CSS URL 加 filemtime 防缓存
    //   - 即使 WP 后台缓存 / CDN / OPcache 拦截,HIREAI_VERSION + mtime 也会强制 bust
    $parent_mtime = file_exists(get_template_directory() . '/style.css') ? filemtime(get_template_directory() . '/style.css') : HIREAI_VERSION;
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css', [], HIREAI_VERSION . '-' . $parent_mtime);

    // 子主题样式（版本号随文件时间戳变化，缓存自动失效）
    wp_enqueue_style(
        'hireaipeople-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['parent-style'],
        $ver('/style.css')
    );

    // 移除 Material Symbols CDN（v3.0.2）：所有图标改用 inline SVG（hireai_svg()），避免外网依赖 + 隐私追踪风险。

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
    $is_en       = ($lang_suffix === '_en');
    /* ★ v3.5.5 全 6 项菜单双语 fallback（参考 footer 模式 + hireai_field_lang 后台可编辑） */
    $items       = [
        ['slug' => '',             'zh' => '首页',         'en' => 'Home'],
        ['slug' => 'ai-employees', 'zh' => 'AI 数字员工',  'en' => 'AI Employees'],
        ['slug' => 'ai-solutions', 'zh' => 'AI 解决方案商城',  'en' => 'AI Solutions'],
        ['slug' => 'cases-insights','zh' => '案例与洞察',   'en' => 'Cases & Insights'],
        ['slug' => 'faq',          'zh' => '常见问题',     'en' => 'FAQ'],
        ['slug' => 'contact',      'zh' => '联系我们',     'en' => 'Contact'],
    ];
    $current_url = trailingslashit(esc_url(home_url(add_query_arg([], $GLOBALS['wp']->request))));
    echo '<ul class="hai-header__nav-list">';
    foreach ($items as $item) {
        /* 后台可编辑：ACF nav_item_{slug}_label 双语字段（option），无则 fallback 内置 zh/en */
        $acf_field = 'nav_item_' . ($item['slug'] === '' ? 'home' : $item['slug']) . '_label';
        $fallback_label = $is_en ? $item['en'] : $item['zh'];
        $acf_label = function_exists('hireai_field_lang')
            ? hireai_field_lang($acf_field, $is_en ? 'en' : 'zh', $fallback_label, 'option')
            : $fallback_label;

        if ($item['slug'] === '') {
            $url   = home_url('/');
            $label = $acf_label;
            $is_current = (home_url('/') === $current_url) || (is_front_page());
        } else {
            $page = get_page_by_path($item['slug']);
            if (!$page) {
                continue;
            }
            $url   = get_permalink($page);
            /* 优先 ACF 标签；空时回退页面标题（兼容 Polylang 多语翻译） */
            $label = $acf_label !== '' ? $acf_label : get_the_title($page);
            $is_current = (trailingslashit($url) === $current_url);
        }
        $class = $is_current ? 'menu-item current-menu-item' : 'menu-item';
        echo '<li class="' . esc_attr($class) . '"><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}



/**
 * v3.5.7：主导航已由 WordPress 菜单初始化时，覆盖其菜单标题。
 *
 * 未创建主菜单时，hireai_fallback_nav() 仍负责六项双语回退；已创建
 * 主菜单时，WordPress 会走 Walker 输出，fallback_cb 不会执行。通过
 * nav_menu_item_title 过滤可在保留后台菜单管理能力的同时，继续使用
 * site options 中六个 nav_item_*_label 双语 ACF 字段。
 */
function hireai_bilingual_nav_title($title, $item, $args = null, $depth = 0) {
    $theme_location = is_object($args) && isset($args->theme_location)
        ? (string) $args->theme_location
        : '';

    if ($theme_location !== 'primary' || !is_object($item)) {
        return $title;
    }

    $object_id = isset($item->object_id) ? (int) $item->object_id : 0;
    $post      = function_exists('get_post') ? get_post($object_id) : null;
    if (!$post || !isset($post->post_type) || $post->post_type !== 'page' || empty($post->post_name)) {
        return $title;
    }

    $field = 'nav_item_' . $post->post_name . '_label';
    $lang  = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en' ? 'en' : 'zh';

    return function_exists('hireai_field_lang')
        ? hireai_field_lang($field, $lang, (string) $title, 'option')
        : (string) $title;
}

add_filter('nav_menu_item_title', 'hireai_bilingual_nav_title', 10, 4);

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
        ['name' => 'fp_hero_cta_1_url', 'label' => '主按钮 · 链接', 'type' => 'text',
         'zh' => '/ai-employees/', 'en' => '/ai-employees/',
        ],
        ['name' => 'fp_hero_cta_1_title', 'label' => '主按钮 · 文字', 'type' => 'text',
         'zh' => '探索系列', 'en' => 'EXPLORE SERIES',
        ],
        ['name' => 'fp_hero_cta_2_url', 'label' => '次按钮 · 链接', 'type' => 'text',
         'zh' => '/contact/', 'en' => '/contact/',
        ],
        ['name' => 'fp_hero_cta_2_title', 'label' => '次按钮 · 文字', 'type' => 'text',
         'zh' => '定制咨询', 'en' => 'CONSULTATION',
        ],
        [
            'name' => 'fp_hero_image', 'label' => 'Hero 背景图片', 'type' => 'image',
            'zh' => '', 'en' => '', 'extra' => ['return_format' => 'array', 'preview_size' => 'medium'],
        ],
    ], [
        // 同 AllScented：精准匹配博客首页 / 静态首页（ACF 免费版标准 location 格式）
        [['param' => 'page_template', 'operator' => '==', 'value' => 'front-page.php']],
        [['param' => 'page', 'operator' => '==', 'value' => 'home']],
        [['param' => 'page', 'operator' => '==', 'value' => 'front-page']],
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
        ['name' => 'fp_products_explore_url', 'label' => '数字员工 · 按钮链接', 'type' => 'text', 'zh' => '/ai-employees/', 'en' => '/ai-employees/'],

        ['name' => 'fp_prod1_title', 'label' => '数字员工 1 · 标题', 'type' => 'text', 'zh' => 'Aurelian Prime', 'en' => 'Aurelian Prime'],
        ['name' => 'fp_prod1_desc', 'label' => '数字员工 1 · 描述', 'type' => 'text', 'zh' => '精英女性数字分身', 'en' => 'Elite female digital avatar'],
        ['name' => 'fp_prod1_badge', 'label' => '数字员工 1 · 徽标', 'type' => 'text', 'zh' => '限量 01/50', 'en' => 'Edition 01/50'],
        ['name' => 'fp_prod1_image', 'label' => '数字员工 1 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],
        ['name' => 'fp_prod1_url', 'label' => '数字员工 1 · 链接', 'type' => 'text',
         'zh' => '/ai-employees/', 'en' => '/ai-employees/',
        ],
        ['name' => 'fp_prod1_btn', 'label' => '数字员工 1 · 按钮文字', 'type' => 'text',
         'zh' => '探索更多', 'en' => 'Explore More',
        ],

        ['name' => 'fp_prod2_title', 'label' => '数字员工 2 · 标题', 'type' => 'text', 'zh' => 'Aurelian Executive', 'en' => 'Aurelian Executive'],
        ['name' => 'fp_prod2_desc', 'label' => '数字员工 2 · 描述', 'type' => 'text', 'zh' => '权威与外交协议', 'en' => 'Authority & diplomacy protocol'],
        ['name' => 'fp_prod2_badge', 'label' => '数字员工 2 · 徽标', 'type' => 'text', 'zh' => 'Executive Series', 'en' => 'Executive Series'],
        ['name' => 'fp_prod2_image', 'label' => '数字员工 2 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],
        ['name' => 'fp_prod2_url', 'label' => '数字员工 2 · 链接', 'type' => 'text',
         'zh' => '/ai-employees/', 'en' => '/ai-employees/',
        ],
        ['name' => 'fp_prod2_btn', 'label' => '数字员工 2 · 按钮文字', 'type' => 'text',
         'zh' => '探索更多', 'en' => 'Explore More',
        ],

        ['name' => 'fp_prod3_title', 'label' => '数字员工 3 · 标题', 'type' => 'text', 'zh' => 'Neural Sales Core', 'en' => 'Neural Sales Core'],
        ['name' => 'fp_prod3_desc', 'label' => '数字员工 3 · 描述', 'type' => 'text', 'zh' => '企业级AI优化', 'en' => 'Enterprise-grade AI optimization'],
        ['name' => 'fp_prod3_badge', 'label' => '数字员工 3 · 徽标', 'type' => 'text', 'zh' => 'Neural Series', 'en' => 'Neural Series'],
        ['name' => 'fp_prod3_image', 'label' => '数字员工 3 · 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],
        ['name' => 'fp_prod3_url', 'label' => '数字员工 3 · 链接', 'type' => 'text',
         'zh' => '/ai-employees/', 'en' => '/ai-employees/',
        ],
        ['name' => 'fp_prod3_btn', 'label' => '数字员工 3 · 按钮文字', 'type' => 'text',
         'zh' => '探索更多', 'en' => 'Explore More',
        ],

        ['name' => 'fp_solutions_kicker', 'label' => '解决方案 · 眉题', 'type' => 'text', 'zh' => '行业赋能', 'en' => 'Industry Empowerment'],
        ['name' => 'fp_solutions_title', 'label' => '解决方案 · 标题', 'type' => 'textarea', 'zh' => 'AI 解决方案', 'en' => 'AI Solutions', 'extra' => ['rows' => 1]],
        ['name' => 'fp_solutions_subtitle', 'label' => '解决方案 · 副标题', 'type' => 'textarea', 'zh' => '面向多个行业的量身定制智能方案。', 'en' => 'Bespoke intelligent solutions across industries.', 'extra' => ['rows' => 2]],
        ['name' => 'fp_solutions_explore_label', 'label' => '解决方案 · 按钮文字', 'type' => 'text', 'zh' => '探索更多', 'en' => 'Explore More'],
        ['name' => 'fp_solutions_explore_url', 'label' => '解决方案 · 按钮链接', 'type' => 'text', 'zh' => '/ai-solutions/', 'en' => '/ai-solutions/'],

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
        ['name' => 'fp_cases_explore_url', 'label' => '案例 · 按钮链接', 'type' => 'text', 'zh' => '/cases-insights/', 'en' => '/cases-insights/'],

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
        ['name' => 'fp_faq_explore_url', 'label' => 'FAQ · 按钮链接', 'type' => 'text', 'zh' => '/faq/', 'en' => '/faq/'],
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
        [['param' => 'page', 'operator' => '==', 'value' => 'home']],
        [['param' => 'page', 'operator' => '==', 'value' => 'front-page']],
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
        ['name' => 'lookbook_hero_note', 'label' => 'Hero 注脚', 'type' => 'text', 'zh' => '——  在职数字员工 · 精英岗位', 'en' => '— Curated roles, on call'],
        ['name' => 'lookbook_process_note', 'label' => '服务流程 · 注脚', 'type' => 'textarea', 'zh' => '平均 4–6 周即可交付；全程由资深管家陪跑。', 'en' => 'Average delivery in 4–6 weeks, with a dedicated concierge throughout.', 'extra' => ['rows' => 2]],

        /* ── 场景筛选 section ── */
        ['name' => 'lookbook_filter_kicker', 'label' => '筛选区 · 眉题', 'type' => 'text', 'zh' => '分类浏览', 'en' => 'BROWSE BY CRAFT'],
        ['name' => 'lookbook_filter_title', 'label' => '筛选区 · 标题', 'type' => 'textarea', 'zh' => '按角色与场景，发现属于你的数字员工。', 'en' => 'Discover your digital employee by role and craft.', 'extra' => ['rows' => 2]],
        ['name' => 'lookbook_filter_all', 'label' => '筛选区 · 「全部」标签', 'type' => 'text', 'zh' => '全部', 'en' => 'All'],

        /* ── 服务流程 section ── */
        ['name' => 'lookbook_process_kicker', 'label' => '服务流程 · 眉题', 'type' => 'text', 'zh' => '服务流程', 'en' => 'OUR PROCESS'],
        ['name' => 'lookbook_process_title', 'label' => '服务流程 · 标题', 'type' => 'textarea', 'zh' => '从了解到上线，四步即可拥有专属数字员工。', 'en' => 'Four steps from discovery to deployment.', 'extra' => ['rows' => 2]],
        ['name' => 'lookbook_process_step1_title', 'label' => '流程 1 · 标题', 'type' => 'text', 'zh' => '需求洞察', 'en' => 'Discovery'],
        ['name' => 'lookbook_process_step1_desc', 'label' => '流程 1 · 描述', 'type' => 'textarea', 'zh' => '我们的顾问与您一起梳理业务场景与核心指标。', 'en' => 'Our consultants map your business context and KPIs.', 'extra' => ['rows' => 2]],
        ['name' => 'lookbook_process_step2_title', 'label' => '流程 2 · 标题', 'type' => 'text', 'zh' => '方案设计', 'en' => 'Curation'],
        ['name' => 'lookbook_process_step2_desc', 'label' => '流程 2 · 描述', 'type' => 'textarea', 'zh' => '从精品模板库中挑选角色底座，并融入品牌基因。', 'en' => 'Pick an archetype from our atelier and weave in your brand DNA.', 'extra' => ['rows' => 2]],
        ['name' => 'lookbook_process_step3_title', 'label' => '流程 3 · 标题', 'type' => 'text', 'zh' => '训练调优', 'en' => 'Calibration'],
        ['name' => 'lookbook_process_step3_desc', 'label' => '流程 3 · 描述', 'type' => 'textarea', 'zh' => '以专属语料微调模型，确保语调与判断契合业务。', 'en' => 'We fine-tune the model on your proprietary corpus to match tone and judgement.', 'extra' => ['rows' => 2]],
        ['name' => 'lookbook_process_step4_title', 'label' => '流程 4 · 标题', 'type' => 'text', 'zh' => '上线陪跑', 'en' => 'Co-pilot'],
        ['name' => 'lookbook_process_step4_desc', 'label' => '流程 4 · 描述', 'type' => 'textarea', 'zh' => '交付上线后由专属管家持续陪跑，按月复盘迭代。', 'en' => 'After deployment, your dedicated concierge reviews and iterates monthly.', 'extra' => ['rows' => 2]],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'page-ai-employees.php']],
    ]));

    /* ---- 3b. 数字员工 — Repeater（每行可编辑） ---- */
    acf_add_local_field_group([
        'key'    => 'group_page_ai_employees_rows',
        'title'  => 'AI 数字员工页 · 员工行（Repeater）',
        'fields' => [
            [
                'key' => 'field_lookbook_employees_tab_zh',
                'label' => '中文内容（每行）',
                'type' => 'tab',
            ],
            [
                'key'   => 'field_lookbook_employees',
                'label' => '员工行（最多 12 行）',
                'name'  => 'lookbook_employees',
                'type'  => 'repeater',
                'instructions' => '每行对应一个数字员工展示卡（与 fallback 数据互补，缺少时自动用兜底 5 行）。',
                'layout' => 'row',
                'max'    => 12,
                'button_label' => '添加一行',
                'sub_fields' => [
                    ['key' => 'field_emp_row_kicker_zh',    'label' => '眉题（中文）',  'name' => 'emp_row_kicker',    'type' => 'text'],
                    ['key' => 'field_emp_row_title_zh',     'label' => '标题（中文）',  'name' => 'emp_row_title',     'type' => 'text'],
                    ['key' => 'field_emp_row_desc_zh',      'label' => '描述（中文）',  'name' => 'emp_row_desc',      'type' => 'textarea', 'rows' => 3],
                    ['key' => 'field_emp_row_button_zh',    'label' => '按钮（中文）',  'name' => 'emp_row_button',    'type' => 'text'],
                    ['key' => 'field_emp_row_image',        'label' => '展示图',         'name' => 'emp_row_image',     'type' => 'image', 'return_format' => 'url'],
                    ['key' => 'field_emp_row_url',          'label' => '链接地址',       'name' => 'emp_row_url',       'type' => 'text'],
                ],
            ],
            [
                'key' => 'field_lookbook_employees_tab_en',
                'label' => 'English Content (per row)',
                'type' => 'tab',
            ],
            [
                'key'   => 'field_lookbook_employees_en',
                'label' => 'Employee Rows (Repeater, EN)',
                'name'  => 'lookbook_employees_en',
                'type'  => 'repeater',
                'instructions' => 'Mirror of the Chinese repeater; rows should align 1:1 with the 中文 repeater above.',
                'layout' => 'row',
                'max'    => 12,
                'button_label' => 'Add Row',
                'sub_fields' => [
                    ['key' => 'field_emp_row_kicker_en',    'label' => 'Kicker (EN)',    'name' => 'emp_row_kicker',    'type' => 'text'],
                    ['key' => 'field_emp_row_title_en',     'label' => 'Title (EN)',     'name' => 'emp_row_title',     'type' => 'text'],
                    ['key' => 'field_emp_row_desc_en',      'label' => 'Description (EN)','name' => 'emp_row_desc',      'type' => 'textarea', 'rows' => 3],
                    ['key' => 'field_emp_row_button_en',    'label' => 'Button (EN)',    'name' => 'emp_row_button',    'type' => 'text'],
                    ['key' => 'field_emp_row_url_en',       'label' => 'Link URL',       'name' => 'emp_row_url',       'type' => 'text'],
                ],
            ],
        ],
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-ai-employees.php']],
        ],
    ]);

    /* ---- 4. AI 解决方案页 ---- */
    acf_add_local_field_group($hireai_make_group('group_page_ai_solutions', 'AI 解决方案页', [
        ['name' => 'header_kicker', 'label' => '页眉眉题', 'type' => 'text', 'zh' => 'BESPOKE SOLUTIONS', 'en' => 'BESPOKE SOLUTIONS'],
        ['name' => 'header_title', 'label' => '页眉标题', 'type' => 'textarea', 'zh' => 'AI方案商城', 'en' => 'AI Solutions Marketplace', 'extra' => ['rows' => 1]],
        ['name' => 'header_subtitle', 'label' => '页眉副标题', 'type' => 'textarea', 'zh' => '雇佣顶尖数字智脑，赋能企业未来，探索专为高净值品牌与前瞻企业打造的专属AI解决方案。', 'en' => 'Hire elite digital minds to empower your business. Discover bespoke AI solutions tailored for premium brands and forward-looking enterprises.', 'extra' => ['rows' => 2]],
        ['name' => 'hero_cta_primary_text', 'label' => 'Hero · 主 CTA 文字', 'type' => 'text', 'zh' => '定制方案', 'en' => 'Custom Plan'],
        ['name' => 'hero_cta_primary_link', 'label' => 'Hero · 主 CTA 链接', 'type' => 'text', 'zh' => '/contact/', 'en' => '/contact/'],
        ['name' => 'hero_cta_secondary_text', 'label' => 'Hero · 次 CTA 文字', 'type' => 'text', 'zh' => '查看案例', 'en' => 'View Cases'],
        ['name' => 'hero_cta_secondary_link', 'label' => 'Hero · 次 CTA 链接', 'type' => 'text', 'zh' => '/category/cases/', 'en' => '/category/cases/'],
        ['name' => 'card_cta_text', 'label' => '卡片按钮文字', 'type' => 'text', 'zh' => '探索更多', 'en' => 'Explore More'],
        ['name' => 'empty_text', 'label' => '筛选空状态文案', 'type' => 'text', 'zh' => '该分类下暂无解决方案', 'en' => 'No solutions in this category yet.'],
        /* —— 邀约礼遇区块字段 —— */
        ['name' => 'invite_kicker', 'label' => '邀约礼遇 · 眉题', 'type' => 'text', 'zh' => '邀约礼遇', 'en' => 'INVITE & EARN'],
        ['name' => 'invite_title', 'label' => '邀约礼遇 · 标题', 'type' => 'textarea', 'zh' => '邀约礼遇 / Invite & Earn', 'en' => 'Invite & Earn', 'extra' => ['rows' => 1]],
        ['name' => 'invite_subtitle', 'label' => '邀约礼遇 · 副标题', 'type' => 'textarea', 'zh' => '分享您的专属邀请码，与友共赏 AI 数字人卓越体验。', 'en' => 'Share your exclusive invite code with peers to enjoy the Aurelian AI experience together.', 'extra' => ['rows' => 2]],
        ['name' => 'invite_code', 'label' => '邀约礼遇 · 推荐码', 'type' => 'text', 'zh' => 'hireaipeople.com/invite/VIP001', 'en' => 'hireaipeople.com/invite/VIP001'],
        ['name' => 'invite_copy_text', 'label' => '邀约礼遇 · 复制按钮文字', 'type' => 'text', 'zh' => '复制链接', 'en' => 'Copy Link'],
        ['name' => 'invite_reward_amount', 'label' => '邀约礼遇 · 奖励金额', 'type' => 'text', 'zh' => '￥500', 'en' => '¥500'],
        ['name' => 'invite_reward_label', 'label' => '邀约礼遇 · 奖励描述', 'type' => 'textarea', 'zh' => '双方均可获得 ￥500 定制额度奖励，用于您的下一次 AI 服务升级。', 'en' => 'Both you and your invitee earn a ¥500 bespoke credit toward your next AI service upgrade.', 'extra' => ['rows' => 2]],
        ['name' => 'invite_steps_label', 'label' => '邀约礼遇 · 步骤区块标签', 'type' => 'text', 'zh' => '如何运作', 'en' => 'How It Works'],
        /* —— 筛选 / 大 CTA 收尾 —— */
        ['name' => 'filter_tab_scene_label', 'label' => '筛选 · 按场景标签', 'type' => 'text', 'zh' => '按场景分类', 'en' => 'By Scenario'],
        ['name' => 'filter_tab_employee_label', 'label' => '筛选 · 按数字员工标签', 'type' => 'text', 'zh' => '按数字员工分类', 'en' => 'By Digital Employee'],
        ['name' => 'final_cta_kicker', 'label' => '收尾 CTA · 眉题', 'type' => 'text', 'zh' => 'NEXT STEP', 'en' => 'NEXT STEP'],
        ['name' => 'final_cta_title', 'label' => '收尾 CTA · 标题', 'type' => 'textarea', 'zh' => '准备好为您的品牌升级了吗？', 'en' => 'Ready to Elevate Your Brand?', 'extra' => ['rows' => 1]],
        ['name' => 'final_cta_subtitle', 'label' => '收尾 CTA · 副标题', 'type' => 'textarea', 'zh' => '告诉我们您的雄心，我们将围绕它设计一套专属 AI 解决方案。', 'en' => 'Tell us your ambitions and we will design a bespoke AI plan around them.', 'extra' => ['rows' => 2]],
        ['name' => 'final_cta_primary_text', 'label' => '收尾 CTA · 主按钮文字', 'type' => 'text', 'zh' => '开启对话', 'en' => 'Start the Conversation'],
        ['name' => 'final_cta_secondary_text', 'label' => '收尾 CTA · 次按钮文字', 'type' => 'text', 'zh' => '浏览案例', 'en' => 'Browse Cases'],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'page-ai-solutions.php']],
    ]));

    /* 邀约礼遇 — 推荐步骤 repeater（中文/英文各一行） */
    acf_add_local_field_group([
        'key'    => 'group_solutions_invite_steps',
        'title'  => 'AI 解决方案 — 邀约礼遇 · 推荐步骤',
        'fields' => [
            [
                'key' => 'field_solutions_invite_steps',
                'label' => '推荐步骤',
                'name'  => 'solutions_invite_steps',
                'type'  => 'repeater',
                'instructions' => '每行一步：步骤序号（01/02/03...）+ 中英文描述。',
                'layout' => 'row',
                'button_label' => '添加步骤',
                'sub_fields' => [
                    ['key' => 'field_invite_step_no',   'label' => '步骤编号', 'name' => 'step_no',  'type' => 'text'],
                    ['key' => 'field_invite_step_zh',   'label' => '描述（中文）', 'name' => 'step_zh', 'type' => 'text'],
                    ['key' => 'field_invite_step_en',   'label' => 'Description (EN)', 'name' => 'step_en', 'type' => 'text'],
                ],
            ],
        ],
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-ai-solutions.php']],
        ],
    ]);

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
        ['name' => 'cases_cta_url', 'label' => '案例 · 链接', 'type' => 'text',
         'zh' => '/category/cases/', 'en' => '/category/cases/',
        ],
        ['name' => 'cases_cta_title', 'label' => '案例 · 按钮文字', 'type' => 'text',
         'zh' => '查看全部案例', 'en' => 'All Cases',
        ],

        ['name' => 'insights_kicker', 'label' => '洞察 · 眉题', 'type' => 'text', 'zh' => '洞察', 'en' => 'INSIGHTS'],
        ['name' => 'insights_title', 'label' => '洞察 · 标题', 'type' => 'textarea', 'zh' => '前沿洞察', 'en' => 'Frontier Insights', 'extra' => ['rows' => 1]],
        ['name' => 'insights_subtitle', 'label' => '洞察 · 副标题', 'type' => 'textarea', 'zh' => '关于 AI 行业与数字员工的深度思考。', 'en' => 'Deep thinking on AI and the digital workforce.', 'extra' => ['rows' => 2]],
        ['name' => 'insights_cta_url', 'label' => '洞察 · 链接', 'type' => 'text',
         'zh' => '/category/insights/', 'en' => '/category/insights/',
        ],
        ['name' => 'insights_cta_title', 'label' => '洞察 · 按钮文字', 'type' => 'text',
         'zh' => '更多洞察', 'en' => 'More Insights',
        ],

/* ★ v3.5.5 新增：CI archive 全字段 ACF 化（对齐 v2.2.6 硬编码默认值） */
        ['name' => 'ci_hero_kicker', 'label' => 'CI · Hero 眉题', 'type' => 'text', 'zh' => '智慧工坊', 'en' => 'THE ATELIER OF INTELLIGENCE'],
        ['name' => 'ci_hero_h1_pre_zh', 'label' => 'CI · Hero h1 前缀（em 之前）', 'type' => 'text', 'zh' => '打造数字 ', 'en' => 'Crafting Digital '],
        ['name' => 'ci_hero_h1_em_zh', 'label' => 'CI · Hero h1 em（斜体强调）', 'type' => 'text', 'zh' => '人文', 'en' => 'Humanity'],
        ['name' => 'ci_hero_p_zh', 'label' => 'CI · Hero 副文', 'type' => 'textarea', 'zh' => '技术精度与传承美学的交汇之处。', 'en' => 'Where technical precision meets heritage aesthetic.', 'extra' => ['rows' => 2]],

        ['name' => 'ci_sec_h2_zh', 'label' => 'CI · 案例区 h2', 'type' => 'text', 'zh' => '卓越案例', 'en' => 'Collaborative Excellence'],

        ['name' => 'ci_case1_badge', 'label' => 'CI · 案例 1 徽章', 'type' => 'text', 'zh' => '+42% 留存', 'en' => '+42% Retention'],
        ['name' => 'ci_case1_title_zh', 'label' => 'CI · 案例 1 标题', 'type' => 'text', 'zh' => '数字礼宾：高定精品馆', 'en' => 'Aurelian Prime for Private Banking'],
        ['name' => 'ci_case1_desc_zh', 'label' => 'CI · 案例 1 描述', 'type' => 'textarea', 'zh' => '为高净值客户打造超写实数字人，引领其在元宇宙私密展厅中探索收藏系列。', 'en' => 'Reimagining wealth management through a hyper-realistic digital concierge.', 'extra' => ['rows' => 2]],
        ['name' => 'ci_case1_image', 'label' => 'CI · 案例 1 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url', 'preview_size' => 'medium']],

        ['name' => 'ci_case2_badge', 'label' => 'CI · 案例 2 徽章', 'type' => 'text', 'zh' => 'AI 艺术整合', 'en' => 'AI Art Integration'],
        ['name' => 'ci_case2_title_zh', 'label' => 'CI · 案例 2 标题', 'type' => 'text', 'zh' => 'Lumina NFT 系列', 'en' => 'Lumina NFT Series'],
        ['name' => 'ci_case2_desc_zh', 'label' => 'CI · 案例 2 描述', 'type' => 'textarea', 'zh' => '独家 IP 合作，将生成算法与传统工艺融合。', 'en' => 'Exclusive IP collaboration merging generative algorithms with heritage craft.', 'extra' => ['rows' => 2]],
        ['name' => 'ci_case2_image', 'label' => 'CI · 案例 2 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url', 'preview_size' => 'medium']],

        ['name' => 'ci_case3_badge', 'label' => 'CI · 案例 3 徽章', 'type' => 'text', 'zh' => '3.4 倍转化', 'en' => '3.4x Conversion'],
        ['name' => 'ci_case3_title_zh', 'label' => 'CI · 案例 3 标题', 'type' => 'text', 'zh' => '电商进化论', 'en' => 'E-commerce Evolution'],
        ['name' => 'ci_case3_desc_zh', 'label' => 'CI · 案例 3 描述', 'type' => 'textarea', 'zh' => '将浏览转化为沉浸式策展体验。', 'en' => 'Luxury retail performance scaling through personalized digital twin advisors.', 'extra' => ['rows' => 2]],
        ['name' => 'ci_case3_image', 'label' => 'CI · 案例 3 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url', 'preview_size' => 'medium']],

        ['name' => 'ci_case4_badge', 'label' => 'CI · 案例 4 徽章', 'type' => 'text', 'zh' => 'IP 保护 100%', 'en' => 'IP Protection 100%'],
        ['name' => 'ci_case4_title_zh', 'label' => 'CI · 案例 4 标题', 'type' => 'text', 'zh' => '数字 IP 金库', 'en' => 'The Digital IP Vault'],
        ['name' => 'ci_case4_desc_zh', 'label' => 'CI · 案例 4 描述', 'type' => 'textarea', 'zh' => 'AI 集成奢侈房产的全球 PR 审计与声誉管理。', 'en' => 'Global PR audit and reputation management for AI-integrated luxury estates.', 'extra' => ['rows' => 2]],
        ['name' => 'ci_case4_image', 'label' => 'CI · 案例 4 图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url', 'preview_size' => 'medium']],

        ['name' => 'ci_insights_h2_zh', 'label' => 'CI · 洞察区 h2', 'type' => 'text', 'zh' => '前沿洞察', 'en' => 'The Intelligence Journal'],
        ['name' => 'ci_insights_subtitle_zh', 'label' => 'CI · 洞察区副标题', 'type' => 'text', 'zh' => '行业洞察与思想领导力', 'en' => 'INDUSTRY INSIGHTS & THOUGHT LEADERSHIP'],

        ['name' => 'ci_art1_cat', 'label' => 'CI · 文章 1 分类', 'type' => 'text', 'zh' => 'Aesthetics', 'en' => 'Aesthetics'],
        ['name' => 'ci_art1_title_pre_zh', 'label' => 'CI · 文章 1 标题前缀', 'type' => 'text', 'zh' => '机器中的幽灵：', 'en' => 'The Ghost in the Machine: '],
        ['name' => 'ci_art1_title_em_zh', 'label' => 'CI · 文章 1 标题 em', 'type' => 'text', 'zh' => '定义', 'en' => 'Defining'],
        ['name' => 'ci_art1_title_post_zh', 'label' => 'CI · 文章 1 标题后缀', 'type' => 'text', 'zh' => ' AI 之美', 'en' => ' AI Beauty'],
        ['name' => 'ci_art1_desc_zh', 'label' => 'CI · 文章 1 描述', 'type' => 'textarea', 'zh' => '为何传统品牌正走向超风格化的数字表达。', 'en' => 'Moving beyond uncanny valley into hyper-stylized digital.', 'extra' => ['rows' => 2]],
        ['name' => 'ci_art1_rt', 'label' => 'CI · 文章 1 阅读时长', 'type' => 'text', 'zh' => '8 分钟阅读', 'en' => '8 MIN READ'],

        ['name' => 'ci_art2_cat', 'label' => 'CI · 文章 2 分类', 'type' => 'text', 'zh' => 'Technology', 'en' => 'Technology'],
        ['name' => 'ci_art2_title_pre_zh', 'label' => 'CI · 文章 2 标题前缀', 'type' => 'text', 'zh' => '神经网络与丝绸：', 'en' => 'Neural Networks & Silk: '],
        ['name' => 'ci_art2_title_em_zh', 'label' => 'CI · 文章 2 标题 em', 'type' => 'text', 'zh' => '未来', 'en' => 'Future'],
        ['name' => 'ci_art2_title_post_zh', 'label' => 'CI · 文章 2 标题后缀', 'type' => 'text', 'zh' => ' 服务的织物', 'en' => ' Service'],
        ['name' => 'ci_art2_desc_zh', 'label' => 'CI · 文章 2 描述', 'type' => 'textarea', 'zh' => '在不失去专属触感的前提下扩展个性化关怀。', 'en' => 'Scaling personalized attention without losing human touch.', 'extra' => ['rows' => 2]],
        ['name' => 'ci_art2_rt', 'label' => 'CI · 文章 2 阅读时长', 'type' => 'text', 'zh' => '12 分钟阅读', 'en' => '12 MIN READ'],

        ['name' => 'ci_art3_cat', 'label' => 'CI · 文章 3 分类', 'type' => 'text', 'zh' => 'Strategy', 'en' => 'Strategy'],
        ['name' => 'ci_art3_title_pre_zh', 'label' => 'CI · 文章 3 标题前缀', 'type' => 'text', 'zh' => '新白手套：', 'en' => 'The New White Glove: '],
        ['name' => 'ci_art3_title_em_zh', 'label' => 'CI · 文章 3 标题 em', 'type' => 'text', 'zh' => 'AI', 'en' => 'AI'],
        ['name' => 'ci_art3_title_post_zh', 'label' => 'CI · 文章 3 标题后缀', 'type' => 'text', 'zh' => ' 作为终极礼宾', 'en' => ' as Ultimate Concierge'],
        ['name' => 'ci_art3_desc_zh', 'label' => 'CI · 文章 3 描述', 'type' => 'textarea', 'zh' => '审视自动化高端体验时代中忠诚度的演变。', 'en' => 'Loyalty evolution in automated high-end experiences.', 'extra' => ['rows' => 2]],
        ['name' => 'ci_art3_rt', 'label' => 'CI · 文章 3 阅读时长', 'type' => 'text', 'zh' => '6 分钟阅读', 'en' => '6 MIN READ'],

        ['name' => 'ci_consult_h2_zh', 'label' => 'CI · 咨询区 h2', 'type' => 'text', 'zh' => '准备好定义您的传承了吗？', 'en' => 'Ready to define your legacy?'],
        ['name' => 'ci_consult_p_zh', 'label' => 'CI · 咨询区副文', 'type' => 'textarea', 'zh' => '加入全球领先的品牌 AI 数字员工计划。迈出第一步。', 'en' => 'Join the world\'s leading brands in the new era of digital human excellence.', 'extra' => ['rows' => 2]],
        ['name' => 'ci_consult_btn_zh', 'label' => 'CI · 咨询按钮', 'type' => 'text', 'zh' => '立即咨询', 'en' => 'Initiate Consultation'],

                ['name' => 'card_cta_text', 'label' => '卡片按钮文字', 'type' => 'text', 'zh' => '阅读更多', 'en' => 'Read More'],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'page-cases-insights.php']],
    ]));

    /* ---- 6. FAQ 页 ---- */
    acf_add_local_field_group($hireai_make_group('group_page_faq', '常见问题页', [
        ['name' => 'header_kicker', 'label' => '页眉眉题', 'type' => 'text', 'zh' => 'THE ATELIER', 'en' => 'THE ATELIER'],
        ['name' => 'header_title', 'label' => '页眉标题（金色渐变大字）', 'type' => 'textarea', 'zh' => '常见问题', 'en' => 'Frequently Asked', 'extra' => ['rows' => 1]],
        ['name' => 'header_subtitle', 'label' => '页眉副标题（斜体）', 'type' => 'textarea', 'zh' => '深入了解我们的合作模式、财务结构与安全协议。', 'en' => 'Discover detailed insights into our partnership models, financial structures, and security protocols.', 'extra' => ['rows' => 2]],
        ['name' => 'header_hero_image', 'label' => 'Hero 横幅图片', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url', 'preview_size' => 'medium']],
        ['name' => 'header_hero_caption', 'label' => 'Hero 图替代文字', 'type' => 'text', 'zh' => '我们的数字工坊', 'en' => 'Our Atelier'],
        ['name' => 'search_placeholder', 'label' => '检索框占位符', 'type' => 'text', 'zh' => '输入关键词检索…', 'en' => 'Search questions…'],
        ['name' => 'empty_text', 'label' => '无结果文案', 'type' => 'textarea', 'zh' => '未找到匹配的问题，请尝试其他关键词。', 'en' => 'No matching questions found. Try a different keyword.', 'extra' => ['rows' => 2]],
        ['name' => 'faq_group_1_label', 'label' => '分组1 · 合作方式', 'type' => 'text', 'zh' => '合作方式', 'en' => 'Partnership'],
        ['name' => 'faq_group_2_label', 'label' => '分组2 · 财务', 'type' => 'text', 'zh' => '财务', 'en' => 'Finance'],
        ['name' => 'faq_group_3_label', 'label' => '分组3 · 隐私和安全', 'type' => 'text', 'zh' => '隐私和安全', 'en' => 'Privacy & Security'],
        ['name' => 'faq_group_4_label', 'label' => '分组4 · 其他', 'type' => 'text', 'zh' => '其他', 'en' => 'Other'],
        ['name' => 'cta_kicker', 'label' => 'CTA · 眉题（小标签）', 'type' => 'text', 'zh' => '仍有疑问？', 'en' => 'STILL CURIOUS?'],
        ['name' => 'cta_title', 'label' => 'CTA · 标题（斜体大字）', 'type' => 'textarea', 'zh' => '准备好重新定义人性了吗？', 'en' => 'Ready to Redefine Humanity?', 'extra' => ['rows' => 1]],
        ['name' => 'cta_sub', 'label' => 'CTA · 副标题', 'type' => 'textarea', 'zh' => '加入运用 Aurelian AI 专属生态的领袖精英之列。', 'en' => "Join the exclusive echelon of leaders leveraging Aurelian AI's bespoke ecosystem.", 'extra' => ['rows' => 2]],
        ['name' => 'cta_btn_label', 'label' => 'CTA · 主按钮文字', 'type' => 'text', 'zh' => '开启旅程', 'en' => 'Start The Journey'],
        ['name' => 'cta_btn_url', 'label' => 'CTA · 主按钮链接', 'type' => 'text', 'zh' => '/contact/', 'en' => '/contact/'],
        ['name' => 'cta_link_label', 'label' => 'CTA · 副按钮文字', 'type' => 'text', 'zh' => '下载品牌手册', 'en' => 'Download Brand Book'],
        ['name' => 'cta_link_url', 'label' => 'CTA · 副按钮链接', 'type' => 'text', 'zh' => '/case-insights/', 'en' => '/case-insights/'],
    ], [
        [['param' => 'page_template', 'operator' => '==', 'value' => 'page-faq.php']],
    ]));

    /* ---- 6b. 常见问题页 · 问答 Repeater（页面级，zh + en 两套平行） ---- */
    acf_add_local_field_group([
        'key'    => 'group_page_faq_items',
        'title'  => '常见问题页 · 问答列表（Repeater）',
        'fields' => [
            [
                'key'   => 'field_faq_items_zh',
                'label' => '中文问答（每行：分组 + 问题 + 答案）',
                'name'  => 'faq_items_zh',
                'type'  => 'repeater',
                'instructions' => '在后台增删中文 FAQ 条目；分组 key 必须与 4 个 faq_group_*_label 对应（partnership / finance / privacy-security / other）。',
                'layout'       => 'row',
                'max'          => 60,
                'button_label' => '➕ 添加一条问答（中文）',
                'sub_fields'   => [
                    [
                        'key'      => 'field_faq_row_group_zh',
                        'label'    => '所属分组',
                        'name'     => 'faq_row_group',
                        'type'     => 'select',
                        'choices'  => [
                            'partnership'      => '合作方式',
                            'finance'          => '财务',
                            'privacy-security' => '隐私和安全',
                            'other'            => '其他',
                        ],
                        'default_value' => 'partnership',
                    ],
                    [
                        'key'   => 'field_faq_row_q_zh',
                        'label' => '问题',
                        'name'  => 'faq_row_question',
                        'type'  => 'text',
                    ],
                    [
                        'key'   => 'field_faq_row_a_zh',
                        'label' => '答案',
                        'name'  => 'faq_row_answer',
                        'type'  => 'textarea',
                        'rows'  => 4,
                        'new_lines' => 'br',
                    ],
                ],
            ],
            [
                'key'   => 'field_faq_items_en',
                'label' => 'English Q&A (rows: group + question + answer)',
                'name'  => 'faq_items_en',
                'type'  => 'repeater',
                'instructions' => 'Mirror of the Chinese repeater; rows should align 1:1 with 中文 above (or be a stand-alone set when ZH repeater is empty).',
                'layout'       => 'row',
                'max'          => 60,
                'button_label' => '➕ Add Q&A (EN)',
                'sub_fields'   => [
                    [
                        'key'      => 'field_faq_row_group_en',
                        'label'    => 'Group',
                        'name'     => 'faq_row_group',
                        'type'     => 'select',
                        'choices'  => [
                            'partnership'      => 'Partnership',
                            'finance'          => 'Finance',
                            'privacy-security' => 'Privacy & Security',
                            'other'            => 'Other',
                        ],
                        'default_value' => 'partnership',
                    ],
                    [
                        'key'   => 'field_faq_row_q_en',
                        'label' => 'Question',
                        'name'  => 'faq_row_question',
                        'type'  => 'text',
                    ],
                    [
                        'key'   => 'field_faq_row_a_en',
                        'label' => 'Answer',
                        'name'  => 'faq_row_answer',
                        'type'  => 'textarea',
                        'rows'  => 4,
                        'new_lines' => 'br',
                    ],
                ],
            ],
        ],
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-faq.php']],
        ],
        'menu_order' => 5,
    ]);

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
        ['name' => 'employee_cases_link', 'label' => '案例展示链接', 'type' => 'text',
         'zh' => '/category/cases/', 'en' => '/category/cases/',
        ],
        ['name' => 'employee_cases_link_title', 'label' => '案例展示链接文字', 'type' => 'text',
         'zh' => '查看相关案例', 'en' => 'View Related Cases',
        ],
        ['name' => 'employee_kicker', 'label' => '栏目标签（kicker）', 'type' => 'text', 'zh' => '战略精英', 'en' => 'Strategic Elite'],
        ['name' => 'employee_button_text', 'label' => '按钮文字', 'type' => 'text', 'zh' => '探索', 'en' => 'Inquire'],
        ['name' => 'employee_button_style', 'label' => '按钮样式', 'type' => 'select', 'zh' => 'auto', 'en' => 'auto', 'extra' => ['choices' => ['auto' => '自动（交替）', 'filled' => '深色实底', 'outline' => '金色描边'], 'default_value' => 'auto']],
        ['name' => 'employee_link', 'label' => '按钮/图片链接', 'type' => 'text',
         'zh' => '', 'en' => '',
        ],
        ['name' => 'employee_link_title', 'label' => '按钮/图片链接文字', 'type' => 'text',
         'zh' => '', 'en' => '',
        ],
        ['name' => 'employee_price', 'label' => '起步价', 'type' => 'text', 'zh' => '￥60,000 /月起', 'en' => '¥ 60,000 / mo'],
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


    /* ---- 9.4 案例文章 ACF：category=cases 文章的卡片覆盖字段 ---- */
    acf_add_local_field_group($hireai_make_group('group_case_meta', '案例 — 卡片', [
        ['name' => 'case_kicker', 'label' => '案例卡片 · kicker（覆盖）', 'type' => 'text', 'zh' => '', 'en' => ''],
        ['name' => 'case_badge',  'label' => '案例卡片 · badge（覆盖）', 'type' => 'text', 'zh' => '', 'en' => ''],
        ['name' => 'case_subtitle', 'label' => '案例卡片 · 副标题（覆盖 excerpt）', 'type' => 'textarea', 'zh' => '', 'en' => '', 'extra' => ['rows' => 2]],
    ], [
        [['param' => 'post_taxonomy', 'operator' => '==', 'value' => 'category:cases']],
    ]));

    /* ---- 9.45 洞察文章 ACF：category=insights 文章的卡片覆盖字段 ---- */
    acf_add_local_field_group($hireai_make_group('group_insight_meta', '洞察 — 卡片', [
        ['name' => 'insight_cat',      'label' => '洞察 · 分类标签（覆盖）', 'type' => 'text', 'zh' => '', 'en' => ''],
        ['name' => 'insight_read_time', 'label' => '洞察 · 阅读时长（覆盖）', 'type' => 'text', 'zh' => '', 'en' => ''],
    ], [
        [['param' => 'post_taxonomy', 'operator' => '==', 'value' => 'category:insights']],
    ]));

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
        ['name' => 'header_brand_label', 'label' => '页眉 · 品牌 aria-label（Logo 链接）', 'type' => 'text', 'zh' => '聘AI（Hire AI People）', 'en' => 'Hire AI People'],
        ['name' => 'header_logo_alt', 'label' => '页眉 · Logo alt 文本', 'type' => 'text', 'zh' => '聘AI（Hire AI People）', 'en' => 'Hire AI People'],

        ['name' => 'footer_logo', 'label' => '页脚 Logo', 'type' => 'image', 'zh' => '', 'en' => '', 'extra' => ['return_format' => 'url']],
        ['name' => 'header_cta_label', 'label' => '页眉CTA按钮文字', 'type' => 'text', 'zh' => '预约咨询', 'en' => 'Consultation'],
        ['name' => 'header_cta_url', 'label' => '页眉CTA按钮链接', 'type' => 'text', 'zh' => '/contact/', 'en' => '/contact/'],
        ['name' => 'header_consult_label', 'label' => '页眉咨询按钮文字', 'type' => 'text', 'zh' => '预约咨询', 'en' => 'Consultation'],
        /* ★ v3.5.5 新增：6 个导航项双语 ACF 标签（hireai_fallback_nav 后台可编辑） */
        ['name' => 'nav_item_home_label', 'label' => '导航 · 首页', 'type' => 'text', 'zh' => '首页', 'en' => 'Home'],
        ['name' => 'nav_item_ai-employees_label', 'label' => '导航 · AI 数字员工', 'type' => 'text', 'zh' => 'AI 数字员工', 'en' => 'AI Employees'],
        ['name' => 'nav_item_ai-solutions_label', 'label' => '导航 · AI 解决方案商城', 'type' => 'text', 'zh' => 'AI 解决方案商城', 'en' => 'AI Solutions'],
        ['name' => 'nav_item_cases-insights_label', 'label' => '导航 · 案例与洞察', 'type' => 'text', 'zh' => '案例与洞察', 'en' => 'Cases & Insights'],
        ['name' => 'nav_item_faq_label', 'label' => '导航 · 常见问题', 'type' => 'text', 'zh' => '常见问题', 'en' => 'FAQ'],
        ['name' => 'nav_item_contact_label', 'label' => '导航 · 联系我们', 'type' => 'text', 'zh' => '联系我们', 'en' => 'Contact'],
        ['name' => 'footer_copyright', 'label' => '版权信息', 'type' => 'text', 'zh' => '© 2026 聘AI（Hire AI People）。保留所有权利。', 'en' => '© 2026 Hire AI People. All rights reserved.'],
        ['name' => 'footer_slogan', 'label' => '品牌 Slogan', 'type' => 'text', 'zh' => '雇佣智慧 · 臻于艺术', 'en' => 'Hire Intelligence, Artfully Employed.'],
        ['name' => 'footer_desc', 'label' => '页脚介绍', 'type' => 'textarea', 'zh' => 'AI 数字员工与 AI 解决方案平台——以极简奢华之姿，重塑企业智能雇佣。', 'en' => 'A platform for AI digital employees and AI solutions—reshaping intelligent hiring with minimalist luxury.', 'extra' => ['rows' => 3]],
        ['name' => 'header_account_label', 'label' => '页眉 · 我的账户 按钮文字', 'type' => 'text', 'zh' => '我的账户', 'en' => 'MY ACCOUNT'],
        ['name' => 'header_lang_label', 'label' => '页眉 · 语言切换按钮（当前中文时显示）', 'type' => 'text', 'zh' => 'EN / 中', 'en' => '中 / EN'],
        ['name' => 'header_skip_link', 'label' => '页眉 · 跳到主要内容', 'type' => 'text', 'zh' => '跳到主要内容', 'en' => 'Skip to content'],
        ['name' => 'header_nav_aria_primary', 'label' => '页眉 · 主导航 aria', 'type' => 'text', 'zh' => '主导航', 'en' => 'Primary navigation'],
        ['name' => 'header_nav_aria_mobile', 'label' => '页眉 · 移动端导航 aria', 'type' => 'text', 'zh' => '移动端导航', 'en' => 'Mobile navigation'],
        ['name' => 'header_menu_toggle_aria', 'label' => '页眉 · 汉堡菜单 aria', 'type' => 'text', 'zh' => '切换菜单', 'en' => 'Toggle menu'],
        ['name' => 'header_menu_close_aria', 'label' => '页眉 · 关闭菜单 aria', 'type' => 'text', 'zh' => '关闭菜单', 'en' => 'Close menu'],
        ['name' => 'footer_nav_aria', 'label' => '页脚 · 页脚导航 aria', 'type' => 'text', 'zh' => '页脚导航', 'en' => 'Footer navigation'],
        ['name' => 'footer_privacy_label', 'label' => '页脚 · 隐私政策 文字', 'type' => 'text', 'zh' => '隐私政策', 'en' => 'Privacy Policy'],
        ['name' => 'footer_privacy_url', 'label' => '页脚 · 隐私政策 链接', 'type' => 'text', 'zh' => '/privacy-policy/', 'en' => '/privacy-policy/'],
        ['name' => 'footer_terms_label', 'label' => '页脚 · 服务条款 文字', 'type' => 'text', 'zh' => '服务条款', 'en' => 'Terms of Service'],
        ['name' => 'footer_terms_url', 'label' => '页脚 · 服务条款 链接', 'type' => 'text', 'zh' => '/terms/', 'en' => '/terms/'],
        ['name' => 'footer_refund_label', 'label' => '页脚 · 退换政策 文字', 'type' => 'text', 'zh' => '退换政策', 'en' => 'Refund Policy'],
        ['name' => 'footer_refund_url', 'label' => '页脚 · 退换政策 链接', 'type' => 'text', 'zh' => '/refund-policy/', 'en' => '/refund-policy/'],
        ['name' => 'footer_legal_label', 'label' => '页脚 · 法律声明 文字', 'type' => 'text', 'zh' => '法律声明', 'en' => 'Legal Notice'],
        ['name' => 'footer_legal_url', 'label' => '页脚 · 法律声明 链接', 'type' => 'text', 'zh' => '/legal/', 'en' => '/legal/'],
        ['name' => 'footer_social_public_aria', 'label' => '页脚 · 社交图标 1 aria', 'type' => 'text', 'zh' => '公开', 'en' => 'Public'],
        ['name' => 'footer_social_diamond_aria', 'label' => '页脚 · 社交图标 2 aria', 'type' => 'text', 'zh' => '钻石', 'en' => 'Diamond'],
        ['name' => 'footer_social_token_aria', 'label' => '页脚 · 社交图标 3 aria', 'type' => 'text', 'zh' => '代币', 'en' => 'Token'],
    ], [
        [['param' => 'options_page', 'operator' => '==', 'value' => 'hireai-settings']],
    ]));
});

// ============================================================
// 自动清除 LiteSpeed Cache（主题更新后前端同步生效）
// ============================================================
if (class_exists('\LiteSpeed\Purge')) {
    // 主题版本变更时自动清除全站缓存
    add_action('after_switch_theme', function () {
        \LiteSpeed\Purge::purge_all('Theme switched');
    });
    add_action('upgrader_process_complete', function ($upgrader, $options) {
        if (isset($options['type']) && $options['type'] === 'theme') {
            \LiteSpeed\Purge::purge_all('Theme updated via WP updater');
        }
    }, 10, 2);
}

// ============================================================
// 主题更新后:清 transient + 刷 ACF 字段缓存(确保前后端/ACF 同步)
// v2.2.6 改:对齐 Allscented 的"WP 后台更新=前端更新=ACF 更新=缓存更新"
// ============================================================
add_action('upgrader_process_complete', function ($upgrader, $options) {
    if (!isset($options['type']) || $options['type'] !== 'theme') {
        return;
    }
    // 1. 清 WP theme update transient(强制重新检测,避免显示旧版本)
    delete_site_transient('update_themes');
    // 2. 清 ACF 字段缓存(确保 PHP acf_add_local_field_group 的最新定义生效)
    if (function_exists('acf_get_store')) {
        $store = acf_get_store('fields');
        if ($store) {
            $store->reset();
        }
        $groups_store = acf_get_store('field-groups');
        if ($groups_store) {
            $groups_store->reset();
        }
    }
    // 3. 清 OPcache(防止 PHP 文件更新但缓存还在跑旧字节码)
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
}, 11, 2);

/* -------------------------------------------------------------------------
 * 11. v3.0.3 — 商品 / 文章 保存时清缓存,确保前后端同步
 *      - WC 商品价格/库存/图片 -> 同步
 *      - WP 文章 (cases/insights/employee) -> 同步
 * ---------------------------------------------------------------------- */
/* v3.5.7-p17 Bug 3 修复：抽出清缓存函数 (save_post_product + transition_post_status 共用)
 *   - 清所有 hireai_solutions_* transient (page / global / pagination v1..v9)
 *   - 清 WP update_themes transient (强制 WP 检测到主题更新)
 *   - 缓存 key 与 hireai_get_ai_solutions_products() 一致
 */
function hireai_flush_solutions_cache() {
    delete_site_transient('update_themes');
    delete_transient('hireai_solutions_products');
    delete_transient('hireai_solutions_cache_v1');
    delete_transient('hireai_solutions_cache_v2');
    for ($wc_p = 1; $wc_p <= 9; $wc_p++) {
        delete_transient('hireai_solutions_cache_v1_p' . (int) $wc_p);
        /* v3.5.7-p18: 清 v2 cache(分页 × 8 个场景分类 × N 个数字人) */
        if (function_exists('hireai_list_solution_categories')) {
            $scenes = array_keys(hireai_list_solution_categories());
            foreach ($scenes as $cat) {
                delete_transient('hireai_solutions_cache_v2_p' . (int) $wc_p . '_cat' . $cat);
                delete_transient('hireai_solutions_cache_v2_p' . (int) $wc_p . '_cat' . $cat . '_per');
            }
        }
        delete_transient('hireai_solutions_cache_v2_p' . (int) $wc_p . '_cat_per');
    }
    /* v3.5.7-p18: 数字人分类缓存（p1-p9 × 所有数字人 slug） */
    if (function_exists('hireai_list_solution_personas')) {
        $personas = array_keys(hireai_list_solution_personas());
        for ($wc_p = 1; $wc_p <= 9; $wc_p++) {
            foreach ($personas as $per) {
                delete_transient('hireai_solutions_cache_v2_p' . (int) $wc_p . '_cat_per' . $per);
                delete_transient('hireai_solutions_cache_v2_p' . (int) $wc_p . '_cat_per' . $per . '_per');
            }
        }
    }
}

/* v3.5.7-p17 Bug 3 修复：保留原 save_post_product hook (WC 价格/库存变化但 status 不变时仍需清缓存)
 *   - 仍然只在 publish 状态时清缓存 (与 v3.5.7-p16 行为一致)
 *   - 抽出 hireai_flush_solutions_cache() 复用
 */
add_action('save_post_product', function ($post_id, $post) {
    if (wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) return;
    if ($post->post_status !== 'publish') return;
    // 1. transient (使用 v3.5.7-p17 抽出的统一清缓存函数)
    hireai_flush_solutions_cache();
    // 2. ACF 字段缓存
    if (function_exists('acf_get_store')) {
        $s = acf_get_store('fields');         if ($s) { $s->reset(); }
        $g = acf_get_store('field-groups');   if ($g) { $g->reset(); }
    }
    // 3. 全文缓存对象（WP Rocket / W3 Total Cache / LiteSpeed 通用钩子）
    if (function_exists('wp_cache_clear_cache')) { wp_cache_clear_cache(); }
    if (class_exists('\LiteSpeed\Purge')) { \LiteSpeed\Purge::purge_all('hireai product saved'); }
    // 4. OPcache
    if (function_exists('opcache_reset')) { opcache_reset(); }
}, 20, 2);

/* v3.5.7-p17 Bug 3 修复：新增 transition_post_status 监听 (覆盖 auto-draft → publish 路径)
 *   - 当商品从非 publish 变 publish 时清缓存 (例如 WC 后台快速发布按钮)
 *   - 解决 save_post 在 auto-draft -> publish 路径不触发的问题
 *   - 与 save_post_product 共用 hireai_flush_solutions_cache()
 */
add_action('transition_post_status', function ($new, $old, $post) {
    if (!$post || $post->post_type !== 'product') return;
    if ($new === 'publish' && $old !== 'publish') {
        hireai_flush_solutions_cache();
        if (function_exists('acf_get_store')) {
            $s = acf_get_store('fields');         if ($s) { $s->reset(); }
            $g = acf_get_store('field-groups');   if ($g) { $g->reset(); }
        }
        if (function_exists('wp_cache_clear_cache')) { wp_cache_clear_cache(); }
        if (class_exists('\LiteSpeed\Purge')) { \LiteSpeed\Purge::purge_all('hireai product published'); }
        if (function_exists('opcache_reset')) { opcache_reset(); }
    }
}, 20, 3);

add_action('save_post', function ($post_id, $post) {
    if (wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) return;
    if ($post->post_status !== 'publish') return;
    if ($post->post_type !== 'post') return;
    // 仅清当文章位于 case/insight/ai-employee 时
    $cats = wp_get_post_terms($post_id, 'category', ['fields' => 'slugs']);
    if (array_intersect((array)$cats, ['cases', 'insights', 'ai-employee', 'case', 'insight'])) {
        delete_site_transient('update_themes');
        delete_transient('hireai_cases_insights_posts');
        /* v3.5.7-p15: 拆分为 cases/insights 独立 cache,确保 WP 后台发布后即时同步 */
        delete_transient('hireai_cases_cache_v1');
        delete_transient('hireai_insights_cache_v1');
        if (function_exists('acf_get_store')) {
            $s = acf_get_store('fields');         if ($s) { $s->reset(); }
            $g = acf_get_store('field-groups');   if ($g) { $g->reset(); }
        }
        if (function_exists('wp_cache_clear_cache')) { wp_cache_clear_cache(); }
        if (class_exists('\LiteSpeed\Purge')) { \LiteSpeed\Purge::purge_all('hireai post saved'); }
        if (function_exists('opcache_reset')) { opcache_reset(); }
    }
    /* v3.5.7-p18: 文章保存时强制下次 wp_loaded 重新检测版本（避免后台编辑时不刷新缓存） */
    delete_option('hireai_last_seen_version');
}, 20, 2);

/* -------------------------------------------------------------------------
 * v3.5.7-p18 Task A: page header 缓存 hotfix —— wp_loaded + after_switch_theme
 *   - 每次 wp_loaded 检测 version 变化 → 自动清 WP object cache + LiteSpeed + OPcache
 *   - after_switch_theme 强制清（用户切主题或被强制启用子主题时）
 *   - 配合 hireai_get_version() 函数,使 HIREAI_VERSION 真正实时同步
 * ---------------------------------------------------------------------- */
add_action('wp_loaded', function () {
    $current_version = hireai_get_version();
    $stored_version  = get_option('hireai_last_seen_version', '');
    if ($current_version === $stored_version) return;

    update_option('hireai_last_seen_version', $current_version, false);

    // 1. 清 WP object cache (themes group)
    if (function_exists('wp_cache_flush_group')) {
        wp_cache_flush_group('themes');
    }
    // 2. 清 LiteSpeed 全页缓存
    if (class_exists('\LiteSpeed\Purge')) {
        \LiteSpeed\Purge::purge_all('hireai version changed to ' . $current_version);
    }
    // 3. 清 OPcache
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    // 4. 清 ACF 字段缓存（版本变更可能伴随 ACF 字段组调整）
    if (function_exists('acf_get_store')) {
        $s = acf_get_store('fields');         if ($s) { $s->reset(); }
        $g = acf_get_store('field-groups');   if ($g) { $g->reset(); }
    }
});

add_action('after_switch_theme', function () {
    /* v3.5.7-p18: 主题切换时强制清 OPcache + LiteSpeed（覆盖旧 hook 只清 LiteSpeed 的情况） */
    if (class_exists('\LiteSpeed\Purge')) {
        \LiteSpeed\Purge::purge_all('hireai theme switched p18');
    }
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    /* 强制下次 wp_loaded 重新检测版本 */
    delete_option('hireai_last_seen_version');
});

/* -------------------------------------------------------------------------
 * v3.5.7-p18 Task D: 7 个 product_cat term 自动创建（如不存在）
 *   - 仅在 WP 后台 init 钩子上跑一次(用 transient 标记避免重复查询)
 *   - 7 场景:brand-ip / marketing / visual-design / ecommerce / crisis / copywriting / custom
 *   - 已存在则跳过(wp_insert_term 不会覆盖)
 * ---------------------------------------------------------------------- */
add_action('init', function () {
    if (!taxonomy_exists('product_cat')) return;
    if (get_transient('hireai_p18_product_cat_seeded')) return;
    $scenes = [
        'brand-ip'      => '品牌&IP',
        'marketing'     => '市场营销',
        'visual-design' => '视觉设计',
        'ecommerce'     => '电商&网站',
        'crisis'        => '公关危机',
        'copywriting'   => '文案报告',
        'custom'        => '定制方案',
    ];
    foreach ($scenes as $slug => $name) {
        if (!term_exists($slug, 'product_cat')) {
            wp_insert_term($name, 'product_cat', ['slug' => $slug]);
        }
    }
    set_transient('hireai_p18_product_cat_seeded', 1, DAY_IN_SECONDS);
}, 20);


/* -------------------------------------------------------------------------
 * v3.5.7-p21: 注册 category taxonomy 到 product CPT + 6 个数字人 chip 配置
 *   - WP 5.5+ 默认断开 category 与 product 关联,Echo 9/8 写入的 80-92 post category 静默丢失
 *   - 加这行(priority 5,在 p18 seed priority 20 之前)让防御生效,不影响现有 page-is_singular('product') 代码
 *   - 数字人 chip 改为 6 个(All + 5 数字人),slug 走 product_tag,short→full 映射兼容历史数据
 * ---------------------------------------------------------------------- */
add_action('init', function () {
    register_taxonomy_for_object_type('category', 'product');
}, 5);

/**
 * v3.5.7-p21: 6 个数字人 chip 配置(AI 解决方案商城顶栏筛选)
 *   - 取代 v3.5.7-p18 的 8 个场景 tab(product_cat 字段空 → 8 个 panel 全空)
 *   - slug 走 product_tag(Echo 数据最完整的就是 product_tag 5 个数字人)
 *   - short slug(victoria/adrian/iris/kai/evan) = chip 显示用
 *   - full slug(victoria-brand-pr/...) = 历史 product_tag 数据,Echo 也可能用 short
 *   - ACF repeater 'solutions_filters' 仍可覆盖(向后兼容)
 *
 * @return array<string,array{slug:string,full:string,label_zh:string,label_en:string}>
 */
function hireai_digital_humans() {
    return [
        'victoria' => ['slug' => 'victoria', 'full' => 'victoria-brand-pr', 'label_zh' => 'Victoria · 公关',   'label_en' => 'Victoria · PR'],
        'adrian'   => ['slug' => 'adrian',   'full' => 'adrian-strategy',  'label_zh' => 'Adrian · 品牌IP',  'label_en' => 'Adrian · Brand-IP'],
        'iris'     => ['slug' => 'iris',     'full' => 'iris-visual',      'label_zh' => 'Iris · 视觉',      'label_en' => 'Iris · Visual'],
        'kai'      => ['slug' => 'kai',      'full' => 'kai-ecommerce',    'label_zh' => 'Kai · 电商',       'label_en' => 'Kai · E-Commerce'],
        'evan'     => ['slug' => 'evan',     'full' => 'evan-copywriting', 'label_zh' => 'Evan · 文案',      'label_en' => 'Evan · Copywriting'],
    ];
}

/**
 * v3.5.7-p21: slug 映射(short → full),供 hireai_get_ai_solutions_products() 用
 *   - 接受 'victoria' → 返回 'victoria-brand-pr'(或反之)
 *   - 如果传入的 slug 不在 map,原样返回(兼容未来新增数字人)
 *
 * @param string $slug chip 短名或完整 slug
 * @return string 完整 product_tag slug
 */
function hireai_digital_human_full_slug($slug) {
    if (!is_string($slug) || $slug === '') return '';
    static $short_to_full = null;
    if ($short_to_full === null) {
        $map = function_exists('hireai_digital_humans') ? hireai_digital_humans() : [];
        $short_to_full = [];
        foreach ($map as $short => $info) {
            $short_to_full[$short] = $info['full'];
            $short_to_full[$info['full']] = $info['full']; // 幂等
        }
    }
    return isset($short_to_full[$slug]) ? $short_to_full[$slug] : $slug;
}
