<?php
/**
 * Template Name: 聘AI - FAQ
 *
 * Stitch-matched v3.8.1 — 自由 FAQ 数据层
 *   1. Hero        — 居中眉题 + 金色渐变大字 + 斜体引言
 *   2. Hero Banner — 全宽圆角大图
 *   3. FAQ body    — 左侧分类过滤 + 右侧问答平铺（每页 10 条）
 *   4. CTA         — "Ready to Redefine Humanity?" 斜体 banner
 *
 * 数据源：
 *   - Hero / 分组标签 / CTA：ACF group_page_faq（hireai_field 自动按语言取 _zh/_en）
 *   - FAQ 分类/问答：ACF nested repeater faq_data_categories_zh / faq_data_categories_en；
 *     为空时按当前语言回退旧 repeater、category=faq 文章和内置默认内容。
 *
 * 修复记录（v3.0.0 → v3.0.1）：
 *   v3.0.0 把 ABSPATH 守卫放在 Template Name 注释之前；这在部分 WP 版本下会让
 *   WP_File_Header::get_data 拿不到 Template Name，从而后台看不到模板。
 *   v3.0.1 把 Template Name 注释放回第一个 PHP 块的最前面，ABSPATH 守卫移至第二块。
 *
 *   v3.11.17：≤767px 分类筛选改为下拉（桌面横排按钮不变）。
 *   v3.11.18：修复下拉触发器被 Hello Elementor reset.css 的 #c36 玫红 hover/focus 覆盖。
 *
 * @version 3.11.18
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$suffix  = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '';
$is_en   = ($suffix === '_en');
$page_id = get_the_ID();

/* --------------------------------------------------------------------
 * 1. PAGE FIELDS  (Hero + 分组标签 + CTA)
 * -------------------------------------------------------------------- */
$kicker   = hireai_field('header_kicker',
    $is_en ? 'Comprehensive Partnership Guide' : '全方位合作指南', $page_id);
$title    = hireai_field('header_title',
    $is_en ? 'Clarity First, Instant Answers' : '化繁为简，即刻解惑', $page_id);
$subtitle = hireai_field('header_subtitle',
    $is_en
        ? 'From security compliance to delivery standards, get every detail covered.'
        : '从安全合规到交付标准，了解您关心的每一个细节',
    $page_id);
$hero_caption = hireai_field('header_hero_caption',
    $is_en ? 'Our Atelier' : '我们的数字工坊', $page_id);

$search_placeholder = hireai_field('search_placeholder',
    $is_en ? 'Search questions…' : '输入关键词检索…', $page_id);
$empty_text         = hireai_field('empty_text',
    $is_en ? 'No matching questions found. Try a different keyword.' : '未找到匹配的问题，请尝试其他关键词。',
    $page_id);

/* FAQ 列表 / 翻页 UI 文案（v3.11.16：原先硬编码，现全部走 ACF 双语字段） */
$all_label             = hireai_field('faq_all_label', $is_en ? 'All FAQs' : '全部常见问题', $page_id);
$prev_label            = hireai_field('faq_prev_label', $is_en ? 'Previous' : '上一页', $page_id);
$next_label            = hireai_field('faq_next_label', $is_en ? 'Next' : '下一页', $page_id);
$status_template       = hireai_field('faq_status_template', $is_en ? 'Showing %1$d–%2$d of %3$d questions' : '正在显示第 %1$d–%2$d 条，共 %3$d 条', $page_id);
$body_heading          = hireai_field('faq_body_heading', $is_en ? 'Browse frequently asked questions' : '浏览常见问题', $page_id);
$sidebar_aria_label    = hireai_field('faq_sidebar_aria_label', $is_en ? 'FAQ categories' : 'FAQ 分类导航', $page_id);
$pagination_aria_label = hireai_field('faq_pagination_aria_label', $is_en ? 'FAQ pagination' : 'FAQ 翻页', $page_id);

$groups = [
    'partnership' => [
        'key'   => 'partnership',
        'label' => hireai_field('faq_group_1_label', $is_en ? 'Partnership' : '合作方式', $page_id),
    ],
    'finance' => [
        'key'   => 'finance',
        'label' => hireai_field('faq_group_2_label', $is_en ? 'Finance' : '财务', $page_id),
    ],
    'privacy-security' => [
        'key'   => 'privacy-security',
        'label' => hireai_field('faq_group_3_label', $is_en ? 'Privacy & Security' : '隐私和安全', $page_id),
    ],
    'other' => [
        'key'   => 'other',
        'label' => hireai_field('faq_group_4_label', $is_en ? 'Other' : '其他', $page_id),
    ],
];

$cta_kicker     = hireai_field('cta_kicker',     $is_en ? 'STILL CURIOUS?' : '仍有疑问？', $page_id);
$cta_title      = hireai_field('cta_title',      $is_en ? 'Ready to Redefine Humanity?' : '准备好重新定义人性了吗？', $page_id);
$cta_sub        = hireai_field('cta_sub',        $is_en ? "Join the exclusive echelon of leaders leveraging Aurelian AI's bespoke ecosystem." : '加入运用 Aurelian AI 专属生态的领袖精英之列。', $page_id);
$cta_btn_label  = hireai_field('cta_btn_label',  $is_en ? 'Start The Journey' : '开启旅程', $page_id);
$cta_btn_url    = hireai_field('cta_btn_url',    '/contact/', $page_id);
$cta_link_label = hireai_field('cta_link_label', $is_en ? 'Download Brand Book' : '下载品牌手册', $page_id);
$cta_link_url   = hireai_field('cta_link_url',   '/case-insights/', $page_id);

/* --------------------------------------------------------------------
 * 2. HERO IMAGE（ACF image 字段；未上传时回退到内置占位图）
 * -------------------------------------------------------------------- */
$hero_image = hireai_image('header_hero_image', '', $page_id);
if (!$hero_image) {
    $hero_image = get_stylesheet_directory_uri() . '/assets/img/defaults/hero-home.jpg';
}

/* --------------------------------------------------------------------
 * 3. FAQ DATA — free-form nested repeater first; legacy sources retained
 * -------------------------------------------------------------------- */
$faq_categories       = [];
$has_faq_data_source  = false;
$faq_repeater_name    = $is_en ? 'faq_data_categories_en' : 'faq_data_categories_zh';
$faq_data_value       = function_exists('get_field') ? get_field($faq_repeater_name, $page_id) : null;

if (is_array($faq_data_value) && !empty($faq_data_value)) {
    $has_faq_data_source = true;
    foreach ($faq_data_value as $category_row) {
        $category_label = trim((string) ($category_row['category_label'] ?? ''));
        $category_items = [];

        foreach ((array) ($category_row['faq_entries'] ?? []) as $entry) {
            $question = trim((string) ($entry['question'] ?? ''));
            $answer   = trim((string) ($entry['answer'] ?? ''));
            if ($question === '' && $answer === '') {
                continue;
            }
            $category_items[] = ['q' => $question, 'a' => $answer];
        }

        $faq_categories[] = ['label' => $category_label, 'items' => $category_items];
    }
}

if (!$has_faq_data_source && function_exists('have_rows') && function_exists('get_field')) {
    $legacy_repeater_name = $is_en ? 'faq_items_en' : 'faq_items_zh';
    if (have_rows($legacy_repeater_name, $page_id)) {
        $has_faq_data_source = true;
        $legacy_by_group = [];

        while (have_rows($legacy_repeater_name, $page_id)) {
            the_row();
            $legacy_by_group[(string) get_sub_field('faq_row_group')][] = [
                'q' => trim((string) get_sub_field('faq_row_question')),
                'a' => trim((string) get_sub_field('faq_row_answer')),
            ];
        }

        foreach ($groups as $legacy_key => $legacy_group) {
            $items = array_values(array_filter($legacy_by_group[$legacy_key] ?? [], static function ($item) {
                return $item['q'] !== '' || $item['a'] !== '';
            }));
            $faq_categories[] = ['label' => $legacy_group['label'], 'items' => $items];
        }
    }
}

if (!$has_faq_data_source) {
    $faq_query = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 60,
        'category_name'  => 'faq',
        'no_found_rows'  => true,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
    ]);

    if ($faq_query->have_posts()) {
        $has_faq_data_source = true;
        $legacy_by_group = [];

        while ($faq_query->have_posts()) {
            $faq_query->the_post();
            $legacy_key = function_exists('get_field') ? (string) get_field('faq_group') : '';
            $legacy_by_group[$legacy_key === '' ? 'other' : $legacy_key][] = [
                'q' => trim((string) get_the_title()),
                'a' => trim((string) wp_strip_all_tags(get_the_content())),
            ];
        }
        wp_reset_postdata();

        foreach ($groups as $legacy_key => $legacy_group) {
            $faq_categories[] = [
                'label' => $legacy_group['label'],
                'items' => $legacy_by_group[$legacy_key] ?? [],
            ];
        }
    }
}

/* Bundled Sasha content is the default when no editable FAQ source exists. */
if (!$has_faq_data_source) {
    foreach (hireai_faq_default_categories($is_en ? 'en' : 'zh') as $default_category) {
        $faq_categories[] = [
            'label' => $default_category['category_label'],
            'items' => array_map(static function ($entry) {
                return ['q' => $entry['question'], 'a' => $entry['answer']];
            }, $default_category['faq_entries']),
        ];
    }
}

$faq_category_keys = [];
foreach ($faq_categories as $category_index => $category) {
    $faq_category_keys[$category_index] = 'faq-category-' . ($category_index + 1);
}

/* --------------------------------------------------------------------
 * 4. RENDER
 * -------------------------------------------------------------------- */
?>
<!-- ════════════════════════════════════════════════════════════════
     Page-specific styles (gold-gradient hero title + sidebar + flat answers)
     ════════════════════════════════════════════════════════════════ -->
<style id="hireai-faq-page-css">
/* v3.5.7: 移除 italic（font-weight:700 + 非斜体 + 香槟金渐变）— Sasha brief 2026-09-01 */
/* Gold leaf gradient text — 用于 Hero 大字 */
.hireai-faq-hero__title {
    background: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    display: inline-block;
    font-style: normal;
}

/* Hero layout spacing */
.hireai-faq-hero {
    padding-block: clamp(64px, 8vw, 120px) clamp(40px, 5vw, 72px);
    text-align: center;
}
.hireai-faq-hero__kicker {
    display: inline-block;
    margin-bottom: 24px;
    color: #775a19;
}
/* v3.5.7: FAQ Hero 排版统一 — line-height 1.05→1.1（与其他 4 页一致：cases/employees/solutions/contact） */
.hireai-faq-hero__title {
    margin-bottom: 28px;
    line-height: 1.1;
    letter-spacing: -0.01em;
}
.hireai-faq-hero__subtitle {
    max-width: 640px;
    margin-inline: auto;
    /* v3.0.8 (Bug C): 移除 italic + 用 on-surface 颜色 + 16px */
    font-family: var(--font-body-en, 'Inter'), sans-serif;
    font-size: 16px;
    font-style: normal !important;
    color: var(--on-surface, #1a1c1c);
    line-height: 1.6;
}

/* Hero banner image */
/* v3.11.16: 宽度与左右留白交给外层容器，口径对齐 page-about.php 的 .hireai-about-container
   （max-width 1440 + padding-inline 24px）。不再依赖共享 .container——它在 ≥768px 被统一
   改成 padding-inline:0，正是本页 hero 图贴边的原因。 */
.hireai-faq-banner {
    margin-block: clamp(24px, 4vw, 56px);
}
.hireai-faq-banner__inner {
    width: 100%;
    max-width: 1440px;
    margin-inline: auto;
    padding-inline: 24px;
}
/* 同一页面内的正文 grid 也走 .container；给 FAQ 页补回 24px 边距，保持与 banner 对齐 */
.hireai-faq-page .container {
    padding-inline: 24px;
}
/* v3.0.6: border-radius 4px (hireaipeople.txt 图片圆角 0或4px) — was clamp(16px,1.5vw,24px) */
.hireai-faq-banner img {
    display: block;
    width: 100%;
    height: clamp(280px, 42vw, 520px);
    object-fit: cover;
    border-radius: 4px;
    box-shadow: 0 24px 60px rgba(26, 28, 28, 0.08);
}

/* FAQ body grid: sidebar + paginated answer list */
.hireai-faq-body {
    padding-block: clamp(40px, 6vw, 88px);
}
.hireai-faq-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: clamp(32px, 5vw, 56px);
}
@media (min-width: 900px) {
    .hireai-faq-grid {
        grid-template-columns: 220px 1fr;
        gap: clamp(48px, 5vw, 80px);
    }
}

/* Sidebar — sticky vertical category nav */
.hireai-faq-sidebar {
    position: relative;
    border-left: 1px solid rgba(196, 199, 199, 0.4);
    padding-left: 24px;
}
@media (min-width: 900px) {
    .hireai-faq-sidebar {
        position: sticky;
        top: 120px;
        align-self: start;
    }
}
.hireai-faq-sidebar__list {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin: 0;
    padding: 0;
    list-style: none;
}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.18em; font uses Montserrat */
.hireai-faq-sidebar__btn {
    display: block;
    width: 100%;
    text-align: left;
    padding: 6px 0;
    background: none;
    border: 0;
    border-left: 2px solid transparent;
    margin-left: -25px;
    padding-left: 24px;
    font-family: var(--font-label, 'Montserrat', 'Inter', sans-serif), sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--on-surface-variant, #444748);
    cursor: pointer;
    transition: color 0.25s ease, border-color 0.25s ease;
}
.hireai-faq-sidebar__btn:hover { color: #1a1c1c; }
.hireai-faq-sidebar__btn:hover,
.hireai-faq-sidebar__btn:active,
.hireai-faq-sidebar__btn:focus-visible,
.hireai-faq-sidebar__btn.is-active {
    color: #ffffff;
    background-color: #775a19;
    background-image: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
    background-clip: padding-box;
    border-left-color: rgba(119, 90, 25, 0.65);
    font-weight: 700;
}
html body .hireai-faq-page .hireai-faq-sidebar__btn:focus,
html body .hireai-faq-page .hireai-faq-sidebar__btn:focus-visible,
html body .hireai-faq-page .hireai-faq-sidebar__btn:active {
    outline: 2px solid #775a19 !important;
    outline-offset: 2px !important;
    box-shadow: 0 0 0 3px rgba(233, 193, 118, 0.35) !important;
    color: #ffffff !important;
    background-color: #775a19 !important;
    background-image: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%) !important;
    -webkit-text-fill-color: #ffffff !important;
}
html body .hireai-faq-page .hireai-faq-sidebar__btn:hover,
html body .hireai-faq-page .hireai-faq-sidebar__btn.is-active {
    color: #ffffff !important;
    background-color: #775a19 !important;
    background-image: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%) !important;
    -webkit-text-fill-color: #ffffff !important;
}

/* Sidebar fallback on mobile: horizontal scroll */
@media (max-width: 899px) {
    .hireai-faq-sidebar {
        border-left: 0;
        padding-left: 0;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .hireai-faq-sidebar__list {
        flex-direction: row;
        flex-wrap: nowrap;
        gap: 12px;
        border-bottom: 1px solid rgba(196, 199, 199, 0.4);
        padding-bottom: 12px;
    }
    .hireai-faq-sidebar__btn {
        margin-left: 0;
        padding: 8px 14px;
        border: 1px solid rgba(196, 199, 199, 0.5);
        border-radius: 999px;
        white-space: nowrap;
    }
    .hireai-faq-sidebar__btn.is-active {
        background-image: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
        border-color: rgba(119, 90, 25, 0.65);
        border-left-color: rgba(119, 90, 25, 0.65);
    }
}

/* v3.11.17: mobile category filter — custom dropdown (≤767px only).
   v3.11.18: Hello Elementor 的父主题 reset.css 用
   `[type="button"]:focus, [type="button"]:hover, button:focus, button:hover
   { background-color:#c36; color:#fff }` (#c36 = #CC3366 玫红) 把 tap/hover 态的
   触发器染成玫红。该选择器权重 (0,2,0) 高于原来的 .hireai-faq-filter__trigger
   (0,1,0)，且 reset.css 在子主题样式之后加载。这里统一用 `.hireai-faq-page`
   作用域把相关状态提到 (0,3,0)，不使用 !important。
   Desktop pills stay untouched. The panel overlays the FAQ list and never
   creates horizontal overflow, even with long EN labels. */
.hireai-faq-filter {
    display: none;
    position: relative;
    width: 100%;
    max-width: 100%;
}

/* Trigger = PC 选中态：金色渐变 + 白字（渐变与 .hireai-faq-sidebar__btn.is-active 完全一致） */
.hireai-faq-page .hireai-faq-filter__trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    width: 100%;
    max-width: 100%;
    min-height: 48px;
    padding: 10px 16px;
    border: 1px solid rgba(119, 90, 25, 0.65);
    border-radius: 4px;
    background-color: #775a19;
    background-image: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
    color: #ffffff;
    -webkit-text-fill-color: #ffffff;
    font-family: var(--font-label, 'Montserrat', 'Inter', sans-serif), sans-serif;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.06em;
    line-height: 1.3;
    text-align: left;
    text-transform: uppercase;
    cursor: pointer;
    transition: box-shadow 0.2s ease, border-color 0.2s ease;
}
/* 覆盖 reset.css 的玫红 hover/focus/active（reset 为 (0,2,0)，这里 (0,3,0)） */
.hireai-faq-page .hireai-faq-filter__trigger:hover,
.hireai-faq-page .hireai-faq-filter__trigger:focus,
.hireai-faq-page .hireai-faq-filter__trigger:active,
.hireai-faq-page .hireai-faq-filter__trigger[aria-expanded="true"] {
    border-color: rgba(119, 90, 25, 0.65);
    background-color: #775a19;
    background-image: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
    color: #ffffff;
    -webkit-text-fill-color: #ffffff;
    outline: 0;
    box-shadow: 0 6px 18px rgba(119, 90, 25, 0.22);
    text-decoration: none;
}
.hireai-faq-page .hireai-faq-filter__trigger:focus-visible {
    outline: 2px solid #775a19;
    outline-offset: 2px;
    box-shadow: 0 0 0 3px rgba(233, 193, 118, 0.35);
}
.hireai-faq-filter__value {
    flex: 1 1 auto;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.hireai-faq-filter__icon {
    flex: 0 0 auto;
    width: 16px;
    height: 16px;
    color: currentColor;
    transition: transform 0.2s ease;
}
.hireai-faq-filter__trigger[aria-expanded="true"] .hireai-faq-filter__icon {
    transform: rotate(180deg);
}

/* Panel: white surface + thin gold border */
.hireai-faq-page .hireai-faq-filter__menu {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    z-index: 60;
    max-height: min(60vh, 360px);
    margin: 0;
    padding: 6px;
    overflow-x: hidden;
    overflow-y: auto;
    border: 1px solid rgba(119, 90, 25, 0.35);
    border-radius: 4px;
    background-color: #ffffff;
    background-image: none;
    box-shadow: 0 16px 40px rgba(26, 28, 28, 0.14);
    box-sizing: border-box;
}
.hireai-faq-filter__menu[hidden] { display: none; }

.hireai-faq-page .hireai-faq-filter__option {
    display: flex;
    align-items: center;
    width: 100%;
    min-height: 48px;
    padding: 12px 14px;
    border: 0;
    border-radius: 3px;
    background-color: transparent;
    background-image: none;
    color: #1a1c1c;
    -webkit-text-fill-color: #1a1c1c;
    font-family: var(--font-label, 'Montserrat', 'Inter', sans-serif), sans-serif;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 0.04em;
    line-height: 1.4;
    text-align: left;
    text-transform: uppercase;
    white-space: normal;
    overflow-wrap: anywhere;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease;
}
/* 覆盖 reset.css 的玫红 hover/focus/active（reset 为 (0,2,0)，这里 (0,3,0)） */
.hireai-faq-page .hireai-faq-filter__option:hover,
.hireai-faq-page .hireai-faq-filter__option:focus,
.hireai-faq-page .hireai-faq-filter__option:focus-visible,
.hireai-faq-page .hireai-faq-filter__option:active {
    background-color: rgba(233, 193, 118, 0.18);
    background-image: none;
    color: #1a1c1c;
    -webkit-text-fill-color: #1a1c1c;
    outline: 2px solid rgba(119, 90, 25, 0.55);
    outline-offset: -2px;
    box-shadow: none;
}
/* Selected option = 浅金底 + 深金字 + 左侧金色竖条（对齐 PC 选中态语言） */
.hireai-faq-page .hireai-faq-filter__option.is-active,
.hireai-faq-page .hireai-faq-filter__option[aria-selected="true"] {
    background-color: rgba(233, 193, 118, 0.22);
    background-image: none;
    box-shadow: inset 3px 0 0 #775a19;
    color: #775a19;
    -webkit-text-fill-color: #775a19;
    font-weight: 700;
}
.hireai-faq-page .hireai-faq-filter__option:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}
@media (max-width: 767px) {
    .hireai-faq-filter { display: block; }
    .hireai-faq-sidebar {
        overflow: visible;
        border-left: 0;
        padding-left: 0;
    }
    .hireai-faq-sidebar__list { display: none; }
}

/* Answer list — glassy card */
.hireai-faq-panel {
    display: flex;
    flex-direction: column;
    gap: clamp(16px, 2vw, 24px);
}

/* Each FAQ item — answers render flat by default */
/* v3.0.6: border-radius 4px (hireaipeople.txt 卡片圆角 0或4px) — was 16px */
.hireai-faq-item {
    background: rgba(255, 255, 255, 0.78);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(196, 199, 199, 0.35);
    border-radius: 4px;
    padding: clamp(20px, 3vw, 32px);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.04);
    transition: box-shadow 0.3s ease, border-color 0.3s ease;
}
.hireai-faq-item:hover {
    background-image: linear-gradient(135deg, rgba(233, 193, 118, 0.18), rgba(119, 90, 25, 0.08));
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
    border-color: rgba(119, 90, 25, 0.45);
}
.hireai-faq-item__q {
    flex: 1;
    min-width: 0;
    margin: 0;
    font-family: var(--font-serif, 'Playfair Display', Georgia, serif);
    font-size: var(--fs-h4, 18px);
    font-weight: 500;
    line-height: 1.35;
    color: #1a1c1c;
    overflow-wrap: anywhere;
}
.hireai-faq-item:hover .hireai-faq-item__q,
.hireai-faq-item:focus-within .hireai-faq-item__q {
    background-image: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    -webkit-text-fill-color: transparent;
}

.hireai-faq-item__a {
    margin-top: clamp(14px, 2vw, 20px);
    padding-top: clamp(14px, 2vw, 20px);
    border-top: 1px solid rgba(196, 199, 199, 0.4);
    color: var(--on-surface-variant, #444748);
    font-size: 16px;
    line-height: 1.7;
}

.hireai-faq-status {
    margin: 0 0 18px;
    color: var(--on-surface-variant, #444748);
    font-family: var(--font-body-en, 'Inter'), sans-serif;
    font-size: 13px;
    letter-spacing: 0.04em;
}

.hireai-faq-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: clamp(28px, 4vw, 40px);
}
.hireai-faq-pagination__button,
.hireai-faq-pagination__page {
    min-width: 42px;
    min-height: 42px;
    padding: 9px 14px;
    border: 1px solid rgba(196, 199, 199, 0.55);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.82);
    color: var(--on-surface, #1a1c1c);
    font-family: var(--font-label, 'Montserrat', 'Inter', sans-serif), sans-serif;
    font-size: 13px;
    font-weight: 650;
    letter-spacing: 0.04em;
    cursor: pointer;
    transition: color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}
.hireai-faq-pagination__button:hover,
.hireai-faq-pagination__button:active,
.hireai-faq-pagination__button:focus,
.hireai-faq-pagination__button:focus-visible,
.hireai-faq-pagination__page:hover,
.hireai-faq-pagination__page:active,
.hireai-faq-pagination__page:focus,
.hireai-faq-pagination__page:focus-visible,
.hireai-faq-pagination__page[aria-current="page"] {
    border-color: rgba(119, 90, 25, 0.65);
    outline: 2px solid #775a19 !important;
    outline-offset: 2px !important;
    box-shadow: 0 0 0 3px rgba(233, 193, 118, 0.3) !important;
    color: #ffffff !important;
    background-color: #775a19 !important;
    background-image: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%) !important;
    -webkit-text-fill-color: #ffffff !important;
}
.hireai-faq-pagination__button:disabled,
.hireai-faq-pagination__page:disabled {
    opacity: 0.45;
    cursor: not-allowed;
    color: var(--on-surface-variant, #444748) !important;
    background-color: rgba(255, 255, 255, 0.82) !important;
    background-image: none !important;
    box-shadow: none !important;
    outline: 0 !important;
}

.hireai-faq-empty {
    text-align: center;
    padding: 32px 16px;
    color: var(--on-surface-variant, #444748);
}
</style>

<main id="content" class="hireai-faq-page">

  <!-- ═══════════ 1. HERO ═══════════ -->
  <section class="hireai-page-hero" aria-labelledby="hireai-faq-hero-title">
    <div>
      <span class="kicker"><?php echo esc_html($kicker); ?></span>
      <h1 id="hireai-faq-hero-title">
        <?php echo esc_html($title); ?>
      </h1>
      <p><?php echo esc_html($subtitle); ?></p>
    </div>
    <div style="width:1px;height:40px;background:linear-gradient(180deg,#775a19,transparent);margin:20px auto 0"></div>
  </section>

  <!-- ═══════════ 2. HERO BANNER IMAGE ═══════════ -->
  <section class="hireai-faq-banner" aria-hidden="true">
    <div class="hireai-faq-banner__inner">
      <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_caption); ?>" loading="lazy">
    </div>
  </section>

  <!-- ═══════════ 3. FAQ BODY (Category filter + flat list + pagination) ═══════════ -->
  <section class="hireai-faq-body" aria-labelledby="hireai-faq-body-title">
    <h2 id="hireai-faq-body-title" class="screen-reader-text">
      <?php echo esc_html($body_heading); ?>
    </h2>
    <div class="container hireai-faq-grid">

      <!-- Sidebar: category filter -->
      <aside class="hireai-faq-sidebar" aria-label="<?php echo esc_attr($sidebar_aria_label); ?>">
        <div class="hireai-faq-filter" data-faq-filter>
          <button type="button"
                  class="hireai-faq-filter__trigger"
                  id="hireai-faq-filter-trigger"
                  aria-haspopup="listbox"
                  aria-expanded="false"
                  aria-controls="hireai-faq-filter-menu"
                  aria-labelledby="hireai-faq-filter-label hireai-faq-filter-value"
                  data-faq-filter-trigger>
            <span class="screen-reader-text" id="hireai-faq-filter-label"><?php echo esc_html($sidebar_aria_label); ?></span>
            <span class="hireai-faq-filter__value" id="hireai-faq-filter-value" data-faq-filter-value><?php echo esc_html($all_label); ?></span>
            <svg class="hireai-faq-filter__icon" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
              <path d="M5 7.5l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <div class="hireai-faq-filter__menu"
               id="hireai-faq-filter-menu"
               role="listbox"
               aria-label="<?php echo esc_attr($sidebar_aria_label); ?>"
               data-faq-filter-menu
               hidden>
            <button type="button"
                    class="hireai-faq-filter__option is-active"
                    role="option"
                    aria-selected="true"
                    data-faq-category="all"
                    data-faq-filter-option>
              <?php echo esc_html($all_label); ?>
            </button>
        <?php foreach ($faq_categories as $gkey => $category) :
                $category_key = $faq_category_keys[$gkey];
                $has_items = !empty($category['items']);
        ?>
            <button type="button"
                    class="hireai-faq-filter__option"
                    role="option"
                    aria-selected="false"
                    data-faq-category="<?php echo esc_attr($category_key); ?>"
                    data-faq-filter-option
                    <?php echo $has_items ? '' : 'disabled aria-disabled="true"'; ?>>
              <?php echo esc_html($category['label']); ?>
            </button>
        <?php endforeach; ?>
          </div>
        </div>
        <ul class="hireai-faq-sidebar__list">
          <li>
            <button type="button"
                    class="hireai-faq-sidebar__btn is-active"
                    aria-pressed="true"
                    data-faq-category="all">
              <?php echo esc_html($all_label); ?>
            </button>
          </li>
      <?php foreach ($faq_categories as $gkey => $category) :
              $category_key = $faq_category_keys[$gkey];
              $has_items = !empty($category['items']);
          ?>
            <li>
              <button type="button"
                      class="hireai-faq-sidebar__btn"
                      aria-pressed="false"
                      data-faq-category="<?php echo esc_attr($category_key); ?>"
                      <?php echo $has_items ? '' : 'disabled'; ?>>
                <?php echo esc_html($category['label']); ?>
              </button>
            </li>
          <?php endforeach; ?>
        </ul>
      </aside>

      <!-- Flat Q&A list: data-faq-category on both sides of the filter -->
      <div class="hireai-faq-panel-wrap">
        <div class="hireai-faq-panel" id="hireai-faq-results">
          <p class="hireai-faq-status" data-faq-status aria-live="polite" hidden></p>
        <?php $item_number = 0; ?>
        <?php foreach ($faq_categories as $gkey => $category) :
            $category_key = $faq_category_keys[$gkey];
            foreach ($category['items'] as $item) :
                $item_number++;
                $qid = 'faq-' . $category_key . '-' . $item_number;
        ?>
          <article class="hireai-faq-item" data-faq-item data-faq-category="<?php echo esc_attr($category_key); ?>">
            <h3 class="hireai-faq-item__q"><?php echo esc_html($item['q']); ?></h3>
            <div class="hireai-faq-item__a" id="<?php echo esc_attr($qid); ?>">
              <div class="hireai-faq-item__a-inner">
                <?php echo nl2br(esc_html($item['a'])); ?>
              </div>
            </div>
          </article>
        <?php endforeach; endforeach; ?>
        <?php if ($item_number === 0) : ?>
          <p class="hireai-faq-empty"><?php echo esc_html($empty_text); ?></p>
        <?php endif; ?>
        </div>
        <nav class="hireai-faq-pagination"
             aria-label="<?php echo esc_attr($pagination_aria_label); ?>"
             data-faq-pagination
             hidden>
          <button type="button" class="hireai-faq-pagination__button" data-faq-page-action="prev">
            <?php echo esc_html($prev_label); ?>
          </button>
          <div class="hireai-faq-pagination__pages" data-faq-pages
               style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:center;"></div>
          <button type="button" class="hireai-faq-pagination__button" data-faq-page-action="next">
            <?php echo esc_html($next_label); ?>
          </button>
        </nav>
      </div>

    </div>
  </section>

  <!-- ═══════════ 4. CTA BANNER ═══════════ -->
  <section class="lb-cta" aria-labelledby="hireai-faq-cta-title">
    <div class="lb-cta__inner">
      <?php if ($cta_kicker) : ?>
        <span class="label-caps hireai-faq-cta__kicker" style="display:block;color:#775a19;margin-bottom:18px;">
          <?php echo esc_html($cta_kicker); ?>
        </span>
      <?php endif; ?>
      <h2 id="hireai-faq-cta-title" class="lb-cta__heading"><?php echo esc_html($cta_title); ?></h2>
      <p class="lb-cta__sub"><?php echo esc_html($cta_sub); ?></p>
      <div class="lb-cta__actions">
        <a class="lb-btn lb-btn--primary" href="<?php echo esc_url(hireai_localized_url($cta_btn_url)); ?>">
          <?php echo esc_html($cta_btn_label); ?>
        </a>
        <a class="lb-btn lb-btn--ghost" href="<?php echo esc_url(hireai_localized_url($cta_link_url)); ?>">
          <?php echo esc_html($cta_link_label); ?>
        </a>
      </div>
    </div>
  </section>

</main>

<!-- ═══════════ JS: category filter + 10-per-page pagination ═══════════ -->
<script>
(function () {
    'use strict';

    var perPage = 10;
    var categoryButtons = Array.prototype.slice.call(document.querySelectorAll('.hireai-faq-sidebar__btn[data-faq-category]'));
    var filter = document.querySelector('[data-faq-filter]');
    var filterTrigger = document.querySelector('[data-faq-filter-trigger]');
    var filterMenu = document.querySelector('[data-faq-filter-menu]');
    var filterValue = document.querySelector('[data-faq-filter-value]');
    var filterOptions = Array.prototype.slice.call(document.querySelectorAll('[data-faq-filter-option]'));
    var items = Array.prototype.slice.call(document.querySelectorAll('[data-faq-item]'));
    var pagination = document.querySelector('[data-faq-pagination]');
    var pageContainer = document.querySelector('[data-faq-pages]');
    var status = document.querySelector('[data-faq-status]');
    var prevButton = document.querySelector('[data-faq-page-action="prev"]');
    var nextButton = document.querySelector('[data-faq-page-action="next"]');
    var activeCategory = 'all';
    var activePage = 0;
    var coreReady = !!(categoryButtons.length && items.length && pagination && pageContainer && status && prevButton && nextButton);

    function closeFilter(returnFocus) {
        if (!filterTrigger || !filterMenu) {
            return;
        }
        filterMenu.setAttribute('hidden', '');
        filterTrigger.setAttribute('aria-expanded', 'false');
        if (returnFocus) {
            filterTrigger.focus();
        }
    }

    function openFilter() {
        if (!filterTrigger || !filterMenu) {
            return;
        }
        filterMenu.removeAttribute('hidden');
        filterTrigger.setAttribute('aria-expanded', 'true');
        var activeOption = filterOptions.filter(function (option) {
            return option.getAttribute('data-faq-category') === activeCategory;
        })[0];
        if (activeOption) {
            activeOption.focus();
        }
    }

    function syncSelection() {
        categoryButtons.forEach(function (button) {
            var isActive = button.getAttribute('data-faq-category') === activeCategory;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
        filterOptions.forEach(function (option) {
            var isActive = option.getAttribute('data-faq-category') === activeCategory;
            option.classList.toggle('is-active', isActive);
            option.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
        var selectedOption = filterOptions.filter(function (option) {
            return option.getAttribute('data-faq-category') === activeCategory;
        })[0];
        if (selectedOption && filterValue) {
            filterValue.textContent = selectedOption.textContent.replace(/\s+/g, ' ').trim();
        }
    }

    function selectCategory(category, focusTrigger) {
        activeCategory = category || 'all';
        activePage = 0;
        syncSelection();
        closeFilter(!!focusTrigger);
        if (coreReady) {
            render();
        }
    }

    function filteredItems() {
        return items.filter(function (item) {
            return activeCategory === 'all' || item.getAttribute('data-faq-category') === activeCategory;
        });
    }

    function makePageButton(pageNumber) {
        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'hireai-faq-pagination__page';
        button.textContent = String(pageNumber + 1);
        button.setAttribute('data-faq-page', String(pageNumber));
        return button;
    }

    function render() {
        var matching = filteredItems();
        var pageCount = Math.max(1, Math.ceil(matching.length / perPage));
        activePage = Math.min(activePage, pageCount - 1);
        var first = activePage * perPage;
        var last = Math.min(first + perPage, matching.length);

        items.forEach(function (item) {
            var index = matching.indexOf(item);
            if (index >= first && index < last) {
                item.removeAttribute('hidden');
            } else {
                item.setAttribute('hidden', '');
            }
        });

        if (matching.length > 0) {
            status.textContent = <?php echo wp_json_encode($status_template); ?>;
            status.textContent = status.textContent
                .replace('%1$d', String(first + 1))
                .replace('%2$d', String(last))
                .replace('%3$d', String(matching.length));
        } else {
            status.textContent = <?php echo wp_json_encode($empty_text); ?>;
        }
        status.removeAttribute('hidden');

        pageContainer.innerHTML = '';
        for (var pageNumber = 0; pageNumber < pageCount; pageNumber++) {
            var pageButton = makePageButton(pageNumber);
            if (pageNumber === activePage) {
                pageButton.setAttribute('aria-current', 'page');
            }
            pageButton.addEventListener('click', function () {
                activePage = Number(this.getAttribute('data-faq-page'));
                render();
            });
            pageContainer.appendChild(pageButton);
        }

        if (matching.length > perPage) {
            pagination.removeAttribute('hidden');
        } else {
            pagination.setAttribute('hidden', '');
        }
        prevButton.disabled = activePage === 0;
        nextButton.disabled = activePage === pageCount - 1;
    }

    categoryButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            selectCategory(button.getAttribute('data-faq-category') || 'all', false);
        });
    });

    if (filterTrigger && filterMenu && filter) {
        filterTrigger.addEventListener('click', function () {
            if (filterTrigger.getAttribute('aria-expanded') === 'true') {
                closeFilter(false);
            } else {
                openFilter();
            }
        });

        filterTrigger.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                openFilter();
            }
        });

        filterOptions.forEach(function (option) {
            option.addEventListener('click', function () {
                if (option.disabled) {
                    return;
                }
                selectCategory(option.getAttribute('data-faq-category') || 'all', true);
            });
        });

        filterMenu.addEventListener('keydown', function (event) {
            var enabledOptions = filterOptions.filter(function (option) {
                return !option.disabled;
            });
            var currentIndex = enabledOptions.indexOf(document.activeElement);

            if (event.key === 'Escape') {
                event.preventDefault();
                closeFilter(true);
                return;
            }
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                var direction = event.key === 'ArrowDown' ? 1 : -1;
                var nextIndex = currentIndex < 0 ? 0 : (currentIndex + direction + enabledOptions.length) % enabledOptions.length;
                enabledOptions[nextIndex].focus();
                return;
            }
            if (event.key === 'Home' || event.key === 'End') {
                event.preventDefault();
                enabledOptions[event.key === 'Home' ? 0 : enabledOptions.length - 1].focus();
            }
        });

        document.addEventListener('click', function (event) {
            if (filterTrigger.getAttribute('aria-expanded') === 'true' && !filter.contains(event.target)) {
                closeFilter(false);
            }
        });

        filter.addEventListener('focusout', function () {
            window.setTimeout(function () {
                if (filterTrigger.getAttribute('aria-expanded') === 'true' && !filter.contains(document.activeElement)) {
                    closeFilter(false);
                }
            }, 0);
        });
    }

    syncSelection();

    if (!coreReady) {
        return;
    }

    prevButton.addEventListener('click', function () {
        activePage = Math.max(0, activePage - 1);
        render();
    });
    nextButton.addEventListener('click', function () {
        activePage += 1;
        render();
    });

    render();
})();
</script>

<?php
get_footer();
