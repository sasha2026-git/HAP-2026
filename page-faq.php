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
 * 3. FAQ 问答 — 优先 ACF Repeater；为空时回退到 category=faq 的 Posts
 * -------------------------------------------------------------------- */
$faq_by_group = [];
$has_repeater = false;

if (function_exists('have_rows') && function_exists('get_field')) {
    $repeater_name = $is_en ? 'faq_items_en' : 'faq_items_zh';
    if (have_rows($repeater_name)) {
        $has_repeater = true;
        while (have_rows($repeater_name)) {
            the_row();
            $gkey = (string) get_sub_field('faq_row_group');
            if ($gkey === '') {
                $gkey = 'other';
            }
            if (!isset($faq_by_group[$gkey])) {
                $faq_by_group[$gkey] = [];
            }
            $q_text = trim((string) get_sub_field('faq_row_question'));
            $a_text = trim((string) get_sub_field('faq_row_answer'));
            // v3.0.4 hotfix: 跳过空问答（之前会插入一条空白项，导致侧栏显示但展开无文字）
            if ($q_text === '' && $a_text === '') {
                continue;
            }
            $faq_by_group[$gkey][] = [
                'q' => $q_text,
                'a' => $a_text,
            ];
        }
    }
}

// Posts fallback
if (!$has_repeater) {
    $faq_query = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 60,
        'category_name'  => 'faq',
        'no_found_rows'  => true,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
    ]);
    if ($faq_query->have_posts()) {
        while ($faq_query->have_posts()) {
            $faq_query->the_post();
            $gkey = function_exists('get_field') ? (string) get_field('faq_group') : '';
            if ($gkey === '') {
                $gkey = 'other';
            }
            if (!isset($faq_by_group[$gkey])) {
                $faq_by_group[$gkey] = [];
            }
            $q_text = trim((string) get_the_title());
            $a_text = trim((string) wp_strip_all_tags(get_the_content()));
            // v3.0.4 hotfix: 跳过空问答（防止渲染空白面板）
            if ($q_text === '' && $a_text === '') {
                continue;
            }
            $faq_by_group[$gkey][] = [
                'q' => $q_text,
                'a' => $a_text,
            ];
        }
        wp_reset_postdata();
    }
}

// 兜底：完全无内容时，4 个分组各显示 2 条预设问答（共 8 条 ≥ 任务要求的 5 条）
// v3.0.4 hotfix: 即使 ACF repeater + Posts 双双空，也保证 FAQ 文字内容可见
if (empty($faq_by_group)) {
    $fallback = [
        'partnership' => [
            [
                'q' => $is_en ? 'How long does it take to deploy a digital employee?' : '上线一个数字员工需要多长时间？',
                'a' => $is_en
                    ? 'Standard rollout takes 4–8 weeks depending on data readiness and customization depth.'
                    : '标准上线周期为 4 至 8 周，具体取决于数据结构与定制深度。',
            ],
            [
                'q' => $is_en ? 'Do you provide ongoing support after launch?' : '上线之后是否提供持续支持？',
                'a' => $is_en
                    ? 'Yes. Every engagement includes a dedicated concierge and quarterly optimization reviews.'
                    : '是的。每一段合作都会配备专属管家，并提供季度性的优化复盘。',
            ],
            [
                /* v3.0.5: 每组补到 3 条（共 12 条 ≥ brief 要求的 8 条） */
                'q' => $is_en ? 'Can the digital employee integrate with our existing tools?' : '数字员工能与我们现有的工具集成吗？',
                'a' => $is_en
                    ? 'Yes. We support standard APIs and connectors for CRM, ERP, and ticketing systems; bespoke integration is also available.'
                    : '可以。我们支持 CRM、ERP、工单等标准 API 与连接器，也支持定制化集成。',
            ],
        ],
        'finance' => [
            [
                'q' => $is_en ? 'How is your pricing structured?' : '如何收费？',
                'a' => $is_en
                    ? 'Pricing is tailored by scope and engagement; we provide a clear quote based on team size and use case.'
                    : '按方案与使用周期定制。我们会根据团队规模与场景给出明确报价。',
            ],
            [
                'q' => $is_en ? 'Do you offer a free pilot?' : '是否提供免费试点？',
                'a' => $is_en
                    ? 'For suitable scenarios we offer a time-boxed pilot with clear deliverables and evaluation criteria.'
                    : '针对合适的场景，我们会提供限时试点，明确交付物与评估标准。',
            ],
            [
                'q' => $is_en ? 'Are there long-term contracts?' : '需要长期合同吗？',
                'a' => $is_en
                    ? 'No. We offer monthly subscriptions with no long-term lock-in; enterprise plans can be customized to your roadmap.'
                    : '不需要。我们提供按月订阅，不强制长期锁定；企业方案可按您的路线图定制。',
            ],
        ],
        'privacy-security' => [
            [
                'q' => $is_en ? 'How is data privacy protected?' : '数据与隐私如何保障？',
                'a' => $is_en
                    ? 'Client data is only used for the agreed engagement and is never used to train other clients’ models.'
                    : '客户数据仅在合同约定的范围内用于交付，不用于训练其他客户模型。',
            ],
            [
                'q' => $is_en ? 'Where is the data stored?' : '数据存储在哪里？',
                'a' => $is_en
                    ? 'All data is stored in regional private clouds; you may opt for on-premise deployment for sensitive workloads.'
                    : '所有数据存储在区域私有云；针对敏感业务，您也可选择本地化部署。',
            ],
            [
                'q' => $is_en ? 'Who owns the model trained on our data?' : '用我们数据训练的模型归谁所有？',
                'a' => $is_en
                    ? 'You do. The bespoke model and its derivative assets belong to your organization under our standard engagement terms.'
                    : '归您所有。按标准合作条款，专属模型及其衍生资产归贵公司所有。',
            ],
        ],
        'other' => [
            [
                'q' => $is_en ? 'Can I try a digital employee first?' : '我可以先试用一个数字员工吗？',
                'a' => $is_en
                    ? 'Yes. For suitable scenarios we offer a time-boxed pilot with clear deliverables and evaluation criteria.'
                    : '可以。我们会为合适场景提供限时试点，明确交付物与评估标准。',
            ],
            [
                'q' => $is_en ? 'Which languages do digital employees speak?' : '数字员工支持哪些语言？',
                'a' => $is_en
                    ? 'Out of the box: Mandarin, Cantonese, and English. Additional languages can be enabled on request.'
                    : '开箱即用支持普通话、粤语与英语；其他语言可按需启用。',
            ],
            [
                'q' => $is_en ? 'How do we get started?' : '如何开始合作？',
                'a' => $is_en
                    ? 'Book a 30-minute consultation. We assess your goals, scope a pilot, and propose a tailored engagement plan within a week.'
                    : '预约 30 分钟咨询。我们会评估您的目标，框定试点方案，并在 1 周内提供专属合作计划。',
            ],
        ],
    ];
    foreach ($fallback as $gkey => $items) {
        if (!isset($faq_by_group[$gkey])) {
            $faq_by_group[$gkey] = $items;
        }
    }
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
/* Gold leaf gradient text — 用于 Hero 大字 */
.hireai-faq-hero__title {
    background: linear-gradient(135deg, #e9c176 0%, #775a19 55%, #e9c176 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    display: inline-block;
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
.hireai-faq-hero__title {
    margin-bottom: 28px;
    line-height: 1.05;
    letter-spacing: -0.01em;
}
.hireai-faq-hero__subtitle {
    max-width: 640px;
    margin-inline: auto;
    color: var(--on-surface-variant, #444748);
    font-style: italic;
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
