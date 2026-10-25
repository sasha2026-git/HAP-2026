<?php
/**
 * Template Name: 聘AI - FAQ
 *
 * Stitch-matched v3.8.0 — 自由 FAQ 数据层
 *   1. Hero        — 居中眉题 + 金色渐变大字 + 斜体引言
 *   2. Hero Banner — 全宽圆角大图
 *   3. FAQ body    — 左侧分组导航 + 右侧手风琴（玻璃拟态卡）
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
 * @version 3.8.0
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
    $is_en ? 'THE ATELIER' : 'THE ATELIER', $page_id);
$title    = hireai_field('header_title',
    $is_en ? 'Frequently Asked' : '常见问题', $page_id);
$subtitle = hireai_field('header_subtitle',
    $is_en
        ? 'Discover detailed insights into our partnership models, financial structures, and security protocols.'
        : '深入了解我们的合作模式、财务结构与安全协议。',
    $page_id);
$hero_caption = hireai_field('header_hero_caption',
    $is_en ? 'Our Atelier' : '我们的数字工坊', $page_id);

$search_placeholder = hireai_field('search_placeholder',
    $is_en ? 'Search questions…' : '输入关键词检索…', $page_id);
$empty_text         = hireai_field('empty_text',
    $is_en ? 'No matching questions found. Try a different keyword.' : '未找到匹配的问题，请尝试其他关键词。',
    $page_id);

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

$active_group = 0;
foreach ($faq_categories as $category_index => $category) {
    if (!empty($category['items'])) {
        $active_group = $category_index;
        break;
    }
}

/* --------------------------------------------------------------------
 * 4. RENDER
 * -------------------------------------------------------------------- */
?>
<!-- ════════════════════════════════════════════════════════════════
     Page-specific styles (gold-gradient hero title + sidebar + glassy accordion)
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
.hireai-faq-banner {
    margin-block: clamp(24px, 4vw, 56px);
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

/* FAQ body grid: sidebar + accordion */
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
    background-image: linear-gradient(135deg, #e9c176 0%, #775a19 100%);
    background-clip: padding-box;
    border-left-color: rgba(119, 90, 25, 0.65);
    font-weight: 700;
}
.hireai-faq-sidebar__btn:focus-visible {
    outline: 2px solid #775a19;
    outline-offset: 2px;
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

/* Accordion panel — glassy card */
.hireai-faq-panel {
    display: flex;
    flex-direction: column;
    gap: clamp(16px, 2vw, 24px);
}

/* Each FAQ item — glassmorphism */
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
.hireai-faq-item:active,
.hireai-faq-item.is-open,
.hireai-faq-item:focus-within {
    background-image: linear-gradient(135deg, rgba(233, 193, 118, 0.2), rgba(119, 90, 25, 0.08));
    border-color: rgba(119, 90, 25, 0.5);
    box-shadow: 0 8px 40px rgba(119, 90, 25, 0.12);
}
.hireai-faq-item__toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    width: 100%;
    background: none;
    border: 0;
    padding: 0;
    text-align: left;
    cursor: pointer;
    color: inherit;
    font: inherit;
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
.hireai-faq-item__toggle:hover .hireai-faq-item__q { color: #775a19; }
.hireai-faq-item__toggle:active .hireai-faq-item__q,
.hireai-faq-item.is-open .hireai-faq-item__q,
.hireai-faq-item__toggle:focus-visible .hireai-faq-item__q {
    background-image: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    -webkit-text-fill-color: transparent;
}
.hireai-faq-item__toggle:focus-visible {
    outline: 2px solid #775a19;
    outline-offset: 4px;
}
.hireai-faq-item__icon {
    flex: 0 0 28px;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #775a19;
    transition: transform 0.3s ease;
}
.hireai-faq-item.is-open .hireai-faq-item__icon { transform: rotate(180deg); }

.hireai-faq-item__a {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: max-height 0.35s ease-out, opacity 0.3s ease-out, margin-top 0.3s ease-out, padding-top 0.3s ease-out;
    margin-top: 0;
    padding-top: 0;
    border-top: 0 solid rgba(196, 199, 199, 0);
    color: var(--on-surface-variant, #444748);
    font-size: 16px;
    line-height: 1.7;
}
.hireai-faq-item.is-open .hireai-faq-item__a {
    max-height: 720px;
    opacity: 1;
    margin-top: clamp(16px, 2vw, 24px);
    padding-top: clamp(16px, 2vw, 24px);
    border-top: 1px solid rgba(196, 199, 199, 0.4);
}

.hireai-faq-empty {
    text-align: center;
    padding: 32px 16px;
    color: var(--on-surface-variant, #444748);
}
</style>

<main id="content" class="hireai-faq-page">

  <!-- ═══════════ 1. HERO ═══════════ -->
  <section class="hireai-faq-hero" aria-labelledby="hireai-faq-hero-title">
    <div class="container">
      <span class="label-caps hireai-faq-hero__kicker"><?php echo esc_html($kicker); ?></span>
      <h1 id="hireai-faq-hero-title"
          class="display-lg hireai-faq-hero__title">
        <?php echo esc_html($title); ?>
      </h1>
      <p class="body-lg hireai-faq-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
    </div>
  </section>

  <!-- ═══════════ 2. HERO BANNER IMAGE ═══════════ -->
  <section class="container hireai-faq-banner" aria-hidden="true">
    <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_caption); ?>" loading="lazy">
  </section>

  <!-- ═══════════ 3. FAQ BODY (Sidebar + Accordion) ═══════════ -->
  <section class="hireai-faq-body" aria-labelledby="hireai-faq-body-title">
    <h2 id="hireai-faq-body-title" class="screen-reader-text">
      <?php echo esc_html($is_en ? 'Browse frequently asked questions' : '浏览常见问题'); ?>
    </h2>
    <div class="container hireai-faq-grid">

      <!-- Sidebar: group navigation -->
      <aside class="hireai-faq-sidebar" aria-label="<?php echo esc_attr($is_en ? 'FAQ categories' : 'FAQ 分类导航'); ?>">
        <ul class="hireai-faq-sidebar__list" role="tablist">
      <?php foreach ($faq_categories as $gkey => $category) :
              $is_active = ($gkey === $active_group);
              $has_items = !empty($category['items']);
          ?>
            <li role="presentation">
              <button type="button"
                      class="hireai-faq-sidebar__btn<?php echo $is_active ? ' is-active' : ''; ?>"
                      role="tab"
                      aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                      aria-controls="hireai-faq-panel-<?php echo esc_attr($gkey); ?>"
                      data-faq-group="<?php echo esc_attr($gkey); ?>"
                      <?php echo $has_items ? '' : 'aria-disabled="true"'; ?>>
                <?php echo esc_html($category['label']); ?>
              </button>
            </li>
          <?php endforeach; ?>
        </ul>
      </aside>

      <!-- Accordion panels -->
      <div class="hireai-faq-panel-wrap">
        <?php foreach ($faq_categories as $gkey => $category) :
            $items = $category['items'];
            $is_active = ($gkey === $active_group);
        ?>
          <div class="hireai-faq-panel<?php echo $is_active ? '' : ' is-hidden'; ?>"
               id="hireai-faq-panel-<?php echo esc_attr($gkey); ?>"
               role="tabpanel"
               data-faq-group="<?php echo esc_attr($gkey); ?>"
               <?php echo $is_active ? '' : 'hidden'; ?>>
            <?php if (empty($items)) : ?>
              <p class="hireai-faq-empty"><?php echo esc_html($empty_text); ?></p>
            <?php else : ?>
              <?php foreach ($items as $idx => $item) :
                  $qid = 'faq-' . $gkey . '-' . $idx;
              ?>
                <div class="hireai-faq-item" data-faq-item>
                  <button type="button"
                          class="hireai-faq-item__toggle"
                          aria-expanded="false"
                          aria-controls="<?php echo esc_attr($qid); ?>">
                    <h3 class="hireai-faq-item__q"><?php echo esc_html($item['q']); ?></h3>
                    <span class="hireai-faq-item__icon" aria-hidden="true">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                           stroke="currentColor" stroke-width="1.6"
                           stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9l6 6 6-6"/>
                      </svg>
                    </span>
                  </button>
                  <div class="hireai-faq-item__a" id="<?php echo esc_attr($qid); ?>" role="region">
                    <div class="hireai-faq-item__a-inner">
                      <?php echo nl2br(esc_html($item['a'])); ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
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
        <a class="lb-btn lb-btn--primary" href="<?php echo esc_url(home_url($cta_btn_url)); ?>">
          <?php echo esc_html($cta_btn_label); ?>
        </a>
        <a class="lb-btn lb-btn--ghost" href="<?php echo esc_url(home_url($cta_link_url)); ?>">
          <?php echo esc_html($cta_link_label); ?>
        </a>
      </div>
    </div>
  </section>

</main>

<!-- ═══════════ JS: Sidebar tab switch + Accordion expand ═══════════ -->
<script>
(function () {
    'use strict';

    /* --- Sidebar tab switching --- */
    var sidebarBtns = document.querySelectorAll('.hireai-faq-sidebar__btn');
    var panels      = document.querySelectorAll('.hireai-faq-panel');

    sidebarBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var gkey = btn.getAttribute('data-faq-group');
            if (!gkey) return;
            if (btn.getAttribute('aria-disabled') === 'true') return;

            sidebarBtns.forEach(function (b) {
                b.classList.remove('is-active');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('is-active');
            btn.setAttribute('aria-selected', 'true');

            panels.forEach(function (p) {
                if (p.getAttribute('data-faq-group') === gkey) {
                    p.classList.remove('is-hidden');
                    p.removeAttribute('hidden');
                } else {
                    p.classList.add('is-hidden');
                    p.setAttribute('hidden', '');
                }
            });
        });
    });

    /* --- Accordion expand / collapse --- */
    document.querySelectorAll('.hireai-faq-item__toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var item = toggle.closest('.hireai-faq-item');
            if (!item) return;
            var open = item.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    });
})();
</script>

<?php
get_footer();
