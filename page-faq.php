<?php
/**
 * Template Name: 聘AI - FAQ
 *
 * Stitch-matched v3.0.1 — 完全重写（修复 v3.0.0 文件头未识别问题）
 *   1. Hero        — 居中眉题 + 金色渐变大字 + 斜体引言
 *   2. Hero Banner — 全宽圆角大图
 *   3. FAQ body    — 左侧分组导航 + 右侧手风琴（玻璃拟态卡）
 *   4. CTA         — "Ready to Redefine Humanity?" 斜体 banner
 *
 * 数据源：
 *   - Hero / 分组标签 / CTA：ACF group_page_faq（hireai_field 自动按语言取 _zh/_en）
 *   - FAQ 问答：ACF repeater faq_items_zh / faq_items_en（field_faq_row_*）
 *     当 repeater 为空时自动回退到 category=faq 的 Posts 数据（兼容旧内容）。
 *
 * 修复记录（v3.0.0 → v3.0.1）：
 *   v3.0.0 把 ABSPATH 守卫放在 Template Name 注释之前；这在部分 WP 版本下会让
 *   WP_File_Header::get_data 拿不到 Template Name，从而后台看不到模板。
 *   v3.0.1 把 Template Name 注释放回第一个 PHP 块的最前面，ABSPATH 守卫移至第二块。
 *
 * @version 3.0.1
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$suffix  = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '';
$is_en   = ($suffix === '_en');
$page_id = get_the_ID();

/* --------------------------------------------------------------------
 * 1. PAGE FIELDS  (Hero + 分组标签 + CTA)
 * -------------------------------------------------------------------- */
$kicker   = hireai_field('faq_hero_kicker', '', $page_id);
$title    = hireai_field('faq_hero_title', '', $page_id);
$subtitle = hireai_field('faq_hero_subtitle', '', $page_id);
$hero_caption = hireai_field('faq_hero_caption', '', $page_id);
$search_placeholder = hireai_field('faq_search_placeholder', '', $page_id);
$empty_text         = hireai_field('faq_empty_message', '', $page_id);

$groups = [
    'partnership' => [
        'key'   => 'partnership',
        'label' => hireai_field('faq_group_01_label', '', $page_id),
    ],
    'finance' => [
        'key'   => 'finance',
        'label' => hireai_field('faq_group_02_label', '', $page_id),
    ],
    'privacy-security' => [
        'key'   => 'privacy-security',
        'label' => hireai_field('faq_group_03_label', '', $page_id),
    ],
    'other' => [
        'key'   => 'other',
        'label' => hireai_field('faq_group_04_label', '', $page_id),
    ],
];

$cta_kicker     = hireai_field('faq_cta_kicker', '', $page_id);
$cta_title      = hireai_field('faq_cta_title', '', $page_id);
$cta_sub        = hireai_field('faq_cta_subtitle', '', $page_id);
$cta_btn_label  = hireai_field('faq_cta_primary_label', '', $page_id);
$cta_btn_url    = hireai_field('faq_cta_primary_url', '/contact/', $page_id);
$cta_link_label = hireai_field('faq_cta_secondary_label', '', $page_id);
$cta_link_url   = hireai_field('faq_cta_secondary_url', '/cases-insights/', $page_id);

/* --------------------------------------------------------------------
 * 2. HERO IMAGE（ACF image 字段；未上传时回退到内置占位图）
 * -------------------------------------------------------------------- */
$hero_image = hireai_image('faq_hero_image', '', $page_id);
if (!$hero_image) {
    $hero_image = get_stylesheet_directory_uri() . '/assets/img/defaults/hero-home.jpg';
}

/* --------------------------------------------------------------------
 * 3. FAQ 问答 — 优先 ACF Repeater；为空时回退到 category=faq 的 Posts
 * -------------------------------------------------------------------- */
$faq_by_group = [];
$has_repeater = false;

for ($index = 1; $index <= 12; $index++) {
    $number = sprintf('%02d', $index);
    $q_text = trim((string) hireai_field("faq_item_{$number}_question", '', $page_id));
    $a_text = trim((string) hireai_field("faq_item_{$number}_answer", '', $page_id));
    if ($q_text === '' && $a_text === '') continue;
    $gkey = hireai_field("faq_item_{$number}_group", 'other', $page_id);
    if (!isset($faq_by_group[$gkey])) $faq_by_group[$gkey] = [];
    $faq_by_group[$gkey][] = ['q' => $q_text, 'a' => $a_text];
}

/* 默认激活分组：取第一组有数据的 key */
$active_group = '';
foreach (array_keys($groups) as $gk) {
    if (!empty($faq_by_group[$gk])) {
        $active_group = $gk;
        break;
    }
}
if ($active_group === '') {
    $active_group = 'partnership';
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
.hireai-faq-sidebar__btn.is-active {
    color: #775a19;
    border-left-color: #775a19;
    font-weight: 700;
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
        background: rgba(119, 90, 25, 0.06);
        border-color: #775a19;
        border-left-color: #775a19; /* keep visual consistency */
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
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
    border-color: rgba(119, 90, 25, 0.25);
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
    font-size: clamp(18px, 2.2vw, 22px);
    font-weight: 500;
    line-height: 1.35;
    color: #1a1c1c;
    overflow-wrap: anywhere;
}
.hireai-faq-item__toggle:hover .hireai-faq-item__q { color: #775a19; }
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
          <?php foreach ($groups as $gkey => $g) :
              $is_active = ($gkey === $active_group);
              $has_items = !empty($faq_by_group[$gkey]);
          ?>
            <li role="presentation">
              <button type="button"
                      class="hireai-faq-sidebar__btn<?php echo $is_active ? ' is-active' : ''; ?>"
                      role="tab"
                      aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                      aria-controls="hireai-faq-panel-<?php echo esc_attr($gkey); ?>"
                      data-faq-group="<?php echo esc_attr($gkey); ?>"
                      <?php echo $has_items ? '' : 'aria-disabled="true"'; ?>>
                <?php echo esc_html($g['label']); ?>
              </button>
            </li>
          <?php endforeach; ?>
        </ul>
      </aside>

      <!-- Accordion panels -->
      <div class="hireai-faq-panel-wrap">
        <?php foreach ($groups as $gkey => $g) :
            $items = isset($faq_by_group[$gkey]) ? $faq_by_group[$gkey] : [];
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
