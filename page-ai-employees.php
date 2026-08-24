<?php
/**
 * Template Name: 聘AI - AI 数字员工（Lookbook / Atelier）
 *
 * Aurelian luxury system: gold #775a19 / #e9c176, Playfair Display + Inter.
 *
 * Structure:
 *   1. Hero (centered, gold-leaf accent)
 *   2. Filter tabs (category-based)
 *   3. Employee lookbook rows (alternating image/text, glass effect)
 *   4. Service process section (4-step timeline)
 *   5. CTA banner
 *
 * Data sources:
 *   - Hero / CTA: ACF group_page_ai_employees (single fields)
 *   - Filter labels: ACF group_page_ai_employees (lookbook_filter_*)
 *   - Process section: ACF group_page_ai_employees (lookbook_process_*)
 *   - Employee rows: ACF repeater lookbook_employees / lookbook_employees_en,
 *     falls back to lookbook_fallback_employees() when empty.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$suffix = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '';
$is_en  = ($suffix === '_en');

/* --------------------------------------------------------------------
 * 1. PAGE FIELDS  (Hero + sections + CTA)
 * -------------------------------------------------------------------- */
$hero_note       = hireai_field('lookbook_hero_note',       $is_en ? '— Curated roles, on call' : '——  在职数字员工 · 精英岗位');
$hero_kicker     = hireai_field('lookbook_hero_kicker',     $is_en ? 'The Atelier' : '数字工坊');
$hero_title      = hireai_field('lookbook_hero_title',      $is_en ? 'Elite Digital Solutions' : '精英数字解决方案');
$hero_subtitle   = hireai_field('lookbook_hero_subtitle',   $is_en ? '"AI-led process, Human-delivered results."' : '"AI 主导流程，人类交付成果。"');

$filter_kicker   = hireai_field('lookbook_filter_kicker',   $is_en ? 'BROWSE BY CRAFT' : '分类浏览');
$filter_title    = hireai_field('lookbook_filter_title',    $is_en ? 'Discover your digital employee by role and craft.' : '按角色与场景，发现属于你的数字员工。');
$filter_all_lbl  = hireai_field('lookbook_filter_all',      $is_en ? 'All' : '全部');

$process_kicker  = hireai_field('lookbook_process_kicker',  $is_en ? 'OUR PROCESS' : '服务流程');
$process_title   = hireai_field('lookbook_process_title',   $is_en ? 'Four steps from discovery to deployment.' : '从了解到上线，四步即可拥有专属数字员工。');

$process_steps   = [];
$proc_titles_zh  = ['需求洞察', '方案设计', '训练调优', '上线陪跑'];
$proc_titles_en  = ['Discovery', 'Curation', 'Calibration', 'Co-pilot'];
$proc_descs_zh   = [
    '我们的顾问与您一起梳理业务场景与核心指标。',
    '从精品模板库中挑选角色底座，并融入品牌基因。',
    '以专属语料微调模型，确保语调与判断契合业务。',
    '交付上线后由专属管家持续陪跑，按月复盘迭代。',
];
$proc_descs_en   = [
    'Our consultants map your business context and KPIs.',
    'Pick an archetype from our atelier and weave in your brand DNA.',
    'We fine-tune the model on your proprietary corpus to match tone and judgement.',
    'After deployment, your dedicated concierge reviews and iterates monthly.',
];
for ($i = 1; $i <= 4; $i++) {
    $process_steps[] = [
        'title' => hireai_field('lookbook_process_step' . $i . '_title',
                          $is_en ? $proc_titles_en[$i - 1] : $proc_titles_zh[$i - 1]),
        'desc'  => hireai_field('lookbook_process_step' . $i . '_desc',
                          $is_en ? $proc_descs_en[$i - 1] : $proc_descs_zh[$i - 1]),
    ];
}

$process_note    = hireai_field('lookbook_process_note',    $is_en ? 'Average delivery in 4–6 weeks, with a dedicated concierge throughout.' : '平均 4–6 周即可交付；全程由资深管家陪跑。');
$cta_heading     = hireai_field('lookbook_cta_heading',     $is_en ? 'Ready to Redefine Humanity?' : '准备好重新定义人性了吗？');
$cta_sub         = hireai_field('lookbook_cta_sub',         $is_en ? "Join the exclusive echelon of leaders leveraging Aurelian AI's bespoke ecosystem." : '加入运用 Aurelian AI 专属生态的领袖精英之列。');
$cta_btn         = hireai_field('lookbook_cta_btn',         $is_en ? 'Start The Journey' : '开启旅程');
$cta_link_lbl    = hireai_field('lookbook_cta_link',        $is_en ? 'Download Brand Book' : '下载品牌手册');
$cta_url         = hireai_field('lookbook_cta_url',         '/case-insights/');

/* --------------------------------------------------------------------
 * 2. EMPLOYEE ROWS — WP_Query → ACF repeater → lookbook_fallback_employees()
 *
 *   v3.0.9 (Block 2): 在 ACF repeater 之前先尝试 WP_Query
 *     - v3.0.7 之前流程：ACF repeater → fallback (lookbook_fallback_employees 静态)
 *     - v3.0.9 新流程：WP_Query (拉 ai-employee category) → ACF repeater → fallback
 *     - 当 WP_Query 拉到 posts 时，ACF repeater 完全跳过 (避免 N+1 DB hit)
 *     - 当 WP_Query 空时，回退到 v3.0.7 行为
 *     - 当 ACF repeater 也空时，回退到 lookbook_fallback_employees() 静态兜底
 * -------------------------------------------------------------------- */
$raw_rows = [];

/* v3.0.9 (Block 2): WP_Query 优先拉 ai-employee / ai-employees category posts */
if (function_exists('hireai_resolve_employees')) {
    $ai_emp_q = hireai_resolve_employees(9);
    if (!empty($ai_emp_q)) {
        $emp_idx_v309 = 0;
        foreach ($ai_emp_q as $emp_post) {
            if (!($emp_post instanceof WP_Post)) continue;
            /* category 名作为 kicker (如 "AI 数字员工")，用于 filter tabs */
            $emp_cats = get_the_category($emp_post->ID);
            $emp_cat_name = !empty($emp_cats) ? $emp_cats[0]->name : '';
            $emp_perm = get_permalink($emp_post->ID);
            $emp_thumb = get_the_post_thumbnail_url($emp_post->ID, 'large');
            $emp_excerpt = wp_strip_all_tags($emp_post->post_excerpt ?: wp_trim_words(strip_tags($emp_post->post_content), 30, '…'));
            $raw_rows[] = [
                'kicker' => $emp_cat_name !== '' ? $emp_cat_name : ($is_en ? 'AI Employee' : '数字员工'),
                'title'  => get_the_title($emp_post->ID),
                'desc'   => $emp_excerpt,
                'button' => $is_en ? 'Learn More' : '了解详情',
                'url'    => $emp_perm ?: home_url('/ai-employees/'),
                'image'  => $emp_thumb ?: ('lookbook/service-' . (($emp_idx_v309 % 5) + 1) . '.png'),
            ];
            $emp_idx_v309++;
        }
        if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
            error_log('[hireai v3.0.9] employee WP_Query: found=' . count($raw_rows));
        }
    }
}

/* v3.0.9 (Block 2): WP_Query 已拉到 posts 时，ACF repeater 完全跳过 */
if (!empty($raw_rows)) {
    // skip ACF repeater entirely
} else {
if (function_exists('have_rows')) {
    $repeater_name = $is_en ? 'lookbook_employees_en' : 'lookbook_employees';
    if (have_rows($repeater_name)) {
        while (have_rows($repeater_name)) {
            the_row();
            $row = [
                'kicker' => trim((string) get_sub_field('emp_row_kicker')),
                'title'  => trim((string) get_sub_field('emp_row_title')),
                'desc'   => trim((string) get_sub_field('emp_row_desc')),
                'button' => trim((string) get_sub_field('emp_row_button')),
                'url'    => trim((string) get_sub_field('emp_row_url')),
                'image'  => '',
            ];
            $img = get_sub_field('emp_row_image');
            if (is_array($img) && !empty($img['url'])) {
                $row['image'] = $img['url'];
            } elseif (is_string($img) && $img !== '') {
                $row['image'] = $img;
            }
            // Fall back to locale-aware label when admin leaves a field blank
            if ($row['button'] === '') {
                $row['button'] = $is_en ? 'Learn More' : '了解详情';
            }
            if ($row['url'] === '') {
                /* v3.0.7: helper 自动探测 — 找不到 post 时落回 /ai-employees/ 列表页（不是 /contact/，因为太重） */
                static $emp_idx_v307 = 0;
                $row['url'] = function_exists('hireai_resolve_employee_url')
                    ? hireai_resolve_employee_url($emp_idx_v307, home_url('/ai-employees/'))
                    : home_url('/ai-employees/');
                $emp_idx_v307++;
            }
            $raw_rows[] = $row;
        }
    }
}
/* v3.0.5: 把 fallback 行也注入 slug — 这样 admin 没填 ACF 时仍可跳详情页 */
}  /* v3.0.9 (Block 2): 关闭 `} else {` 分支 */
if (empty($raw_rows) && function_exists('lookbook_fallback_employees')) {
    $raw_rows = lookbook_fallback_employees();
}

/* v3.0.7: 智能探测 — get_permalink() 让 WP 处理中文 slug 编码；fallback /ai-employees/ 而非 /contact/
 *   v3.0.9 (Block 2): 当 $raw_rows 来自 WP_Query 时，URL 已是 permalink，
 *   跳过整个 helper 探测循环（节省一次 get_posts DB 调用）。 */
$rows_need_url_fixup = true;
if (!empty($raw_rows)) {
    /* 启发式：第一行 url 是 permalink（来自 WP_Query）时，rows 全部来自 WP_Query */
    $first_url = isset($raw_rows[0]['url']) ? (string) $raw_rows[0]['url'] : '';
    $rows_need_url_fixup = (
        $first_url === ''
        || $first_url === home_url('/contact/')
        || $first_url === home_url('/ai-employees/')
        || $first_url === '/contact/'
        || $first_url === '/ai-employees/'
    );
}
$ai_emp_lookup = $rows_need_url_fixup && function_exists('hireai_resolve_employees')
    ? hireai_resolve_employees(50)
    : [];
$emp_index = 0;
if ($rows_need_url_fixup) {
    foreach ($raw_rows as &$row_ref) {
        if (!isset($row_ref['url']) || $row_ref['url'] === '' || $row_ref['url'] === home_url('/contact/') || $row_ref['url'] === '/contact/') {
            if (isset($ai_emp_lookup[$emp_index]) && $ai_emp_lookup[$emp_index] instanceof WP_Post) {
                $perm = get_permalink($ai_emp_lookup[$emp_index]->ID);
                if ($perm) {
                    $row_ref['url'] = $perm;
                }
            }
        }
        $emp_index++;
    }
    unset($row_ref);
}

/* --------------------------------------------------------------------
 * 3. FILTER TABS — derive unique categories from the rows
 * -------------------------------------------------------------------- */
$filters = [];
foreach ($raw_rows as $r) {
    $cat = trim((string) ($r['kicker'] ?? ''));
    if ($cat !== '' && !in_array($cat, $filters, true)) {
        $filters[] = $cat;
    }
}
?>
<!-- ════════════════════════════════════════════════════════
     AI 数字员工 — Aurelian Luxury Lookbook
     ════════════════════════════════════════════════════════ -->
<style>
/* ── Page-scoped: section header + filter tabs + process timeline ── */
.lb-att {
    --lb-att-cream: #faf9f9;
    --lb-att-ink:   #1a1c1c;
    --lb-att-mid:   #444748;
    --lb-att-gold:  #775a19;
    --lb-att-goldl: #e9c176;
    --lb-att-line:  rgba(196, 199, 199, 0.5);
    /* v3.0.8 (Bug B): section gap 160-200px（hireaipeople.txt 规范）
     *   之前 page-ai-employees.php 的 .lb-hero/.lb-container/.lb-cta padding-block
     *   仅 64-120px → section 之间没间隙 */
    --gap: clamp(160px, 18vw, 200px);
    background: var(--lb-att-cream);
}

/* Hero */
.lb-att .lb-hero__title {
    background: linear-gradient(120deg, var(--lb-att-gold) 0%, var(--lb-att-goldl) 50%, var(--lb-att-gold) 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
}

/* Section divider */
.lb-att__rule {
    display: block;
    width: 56px;
    height: 1px;
    margin: 32px auto;
    background: linear-gradient(90deg, transparent, var(--lb-att-gold), transparent);
}

/* Section module head — divider-style */
.lb-att-head {
    text-align: center;
    margin: 0 auto clamp(28px, 4vw, 48px);
    max-width: 720px;
}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.3em (way too wide); font uses Montserrat */
.lb-att-head__kicker {
    display: block;
    font-family: var(--font-label, 'Montserrat', 'Inter'), sans-serif;
    font-size: var(--fs-label, 12px);
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--lb-att-gold);
    margin-bottom: 16px;
}
.lb-att-head__title {
    font-family: var(--font-serif, 'Playfair Display'), Georgia, serif;
    font-size: clamp(28px, 4vw, 40px);
    font-weight: 600;
    line-height: 1.2;
    color: var(--lb-att-ink);
    margin: 0;
}

/* Filter tabs */
.lb-att-tabs {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
    margin: 0 auto clamp(40px, 6vw, 64px);
    max-width: 980px;
}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.14em; font uses Montserrat */
.lb-att-tab {
    font-family: var(--font-label, 'Montserrat', 'Inter'), sans-serif;
    font-size: var(--fs-label, 12px);
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 10px 22px;
    border: 1px solid var(--lb-att-line);
    border-radius: 999px;
    background: rgba(255,255,255,0.6);
    color: var(--lb-att-mid);
    cursor: pointer;
    transition: all 0.3s var(--lb-ease, cubic-bezier(0.22,1,0.36,1));
    text-decoration: none;
}
.lb-att-tab:hover {
    color: var(--lb-att-ink);
    border-color: var(--lb-att-gold);
}
.lb-att-tab.is-active {
    background: var(--lb-att-ink);
    border-color: var(--lb-att-ink);
    color: #fff;
}

/* Rows wrapper (just to scope the JS filter) */
.lb-att-rows { display: flex; flex-direction: column; gap: var(--gap, clamp(160px, 18vw, 200px)); }
.lb-att-rows > .lb-row { transition: opacity 0.4s ease; }
.lb-att-rows.is-filtering > .lb-row.is-hidden {
    opacity: 0;
    transform: scale(0.98);
    pointer-events: none;
    max-height: 0;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border: 0;
}
@media (prefers-reduced-motion: reduce) {
    .lb-att-rows > .lb-row { transition: none; }
}

/* Process timeline (4 steps) */
.lb-att-process {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 24px;
    counter-reset: lb-step;
}
/* v3.0.6: border-radius 4px (hireaipeople.txt 卡片圆角 0或4px) — was 12px */
.lb-att-step {
    position: relative;
    padding: 56px 28px 32px;
    border: 1px solid var(--lb-att-line);
    border-radius: 4px;
    background: rgba(255,255,255,0.7);
    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
    text-align: center;
}
.lb-att-step::before {
    counter-increment: lb-step;
    content: counter(lb-step, decimal-leading-zero);
    position: absolute;
    top: -18px;
    left: 50%;
    transform: translateX(-50%);
    min-width: 56px;
    padding: 6px 14px;
    background: var(--lb-att-cream);
    border: 1px solid var(--lb-att-gold);
    border-radius: 999px;
    color: var(--lb-att-gold);
    font-family: var(--font-serif, 'Playfair Display'), Georgia, serif;
    font-size: 14px;
    font-weight: 600;
    /* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.18em */
    letter-spacing: 0.1em;
}
.lb-att-step__title {
    font-family: var(--font-serif, 'Playfair Display'), Georgia, serif;
    font-size: 22px;
    font-weight: 600;
    line-height: 1.2;
    margin: 0 0 12px;
    color: var(--lb-att-ink);
}
.lb-att-step__desc {
    font-family: var(--font-body, 'Inter'), sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: var(--lb-att-mid);
    margin: 0;
}

/* Responsive overrides */
@media (max-width: 960px) {
    .lb-att-process { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 540px) {
    .lb-att-process { grid-template-columns: 1fr; }
    .lb-att-tab { padding: 8px 16px; font-size: 11px; }
}

/* v3.0.8 (Bug B): section gap 160-200px（覆盖 style.css 默认 64-120px） */
.lb-att .lb-hero {
    padding-block: clamp(80px, 10vw, 120px) clamp(40px, 5vw, 72px);
    margin-bottom: var(--gap, clamp(160px, 18vw, 200px));
}
.lb-att .lb-container {
    padding-bottom: var(--gap, clamp(160px, 18vw, 200px));
    gap: var(--gap, clamp(160px, 18vw, 200px));
}
.lb-att .lb-cta {
    padding-block: var(--gap, clamp(160px, 18vw, 200px)) clamp(80px, 10vw, 120px);
    background: transparent;
    border-top: 1px solid var(--lb-att-line);
    border-bottom: 1px solid var(--lb-att-line);
}

/* CTA tweaks: keep on cream background, not surface-low
   v3.0.8: .lb-cta__heading 保留 italic（Stitch 设计稿允许 display 用 italic） */
.lb-att .lb-cta__heading { font-style: italic; }
</style>

<div class="site-main lb-main lb-att" id="content">

    <!-- ─────────── Hero ─────────── -->
    <section class="lb-hero">
        <span class="lb-hero__kicker"><?php echo esc_html($hero_kicker); ?></span>
        <h1 class="lb-hero__title"><?php echo esc_html($hero_title); ?></h1>
        <p class="lb-hero__subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        <div class="lb-hero__divider" aria-hidden="true"></div>
    </section>

    <!-- ─────────── Filter Tabs ─────────── -->
    <div class="lb-container">
        <header class="lb-att-head">
            <span class="lb-att-head__kicker"><?php echo esc_html($filter_kicker); ?></span>
            <h2 class="lb-att-head__title"><?php echo esc_html($filter_title); ?></h2>
            <span class="lb-att__rule" aria-hidden="true"></span>
        </header>

        <?php if (!empty($filters)) : ?>
            <nav class="lb-att-tabs" role="tablist" aria-label="<?php echo esc_attr($is_en ? 'Filter by craft' : '按类别筛选'); ?>">
                <button type="button"
                        class="lb-att-tab is-active"
                        data-filter="*"
                        role="tab"
                        aria-selected="true">
                    <?php echo esc_html($filter_all_lbl); ?>
                </button>
                <?php foreach ($filters as $cat) : ?>
                    <button type="button"
                            class="lb-att-tab"
                            data-filter="<?php echo esc_attr($cat); ?>"
                            role="tab"
                            aria-selected="false">
                        <?php echo esc_html($cat); ?>
                    </button>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <!-- ─────────── Employee Rows ─────────── -->
        <?php
        $rows_total = count($raw_rows);
        $rows_caption = hireai_field('lookbook_rows_caption',
            $is_en
                ? sprintf('%1$d curated digital employees, hand-picked from the atelier.', $rows_total)
                : sprintf('共 %1$d 位数字员工，从工坊中精选而出。', $rows_total));
        ?>
        <p class="lb-att-rows__caption"><?php echo esc_html($rows_caption); ?></p>
        <div class="lb-att-rows" id="lb-att-rows">
            <?php
            $index = 0;
            foreach ($raw_rows as $row) :
                $index++;
                $img_url = !empty($row['image'])
                    ? (strpos($row['image'], 'http') === 0 ? $row['image'] : get_stylesheet_directory_uri() . '/assets/img/' . ltrim($row['image'], '/'))
                    : get_stylesheet_directory_uri() . '/assets/img/lookbook/service-' . min($index, 5) . '.png';
                $kicker = trim((string) ($row['kicker'] ?? ''));
                $cat    = $kicker !== '' ? $kicker : 'all';
                $style  = ($index % 2 === 0) ? 'outline' : 'primary';
                $num    = str_pad((string) $index, 2, '0', STR_PAD_LEFT);
            ?>
                <article class="lb-row <?php echo $index % 2 === 0 ? 'lb-row--reverse' : ''; ?>"
                         data-lb-reveal
                         data-category="<?php echo esc_attr($cat); ?>">
                    <div class="lb-row__media">
                        <div class="lb-row__border" aria-hidden="true"></div>
                        <img class="lb-row__image"
                             src="<?php echo esc_url($img_url); ?>"
                             alt="<?php echo esc_attr($row['title'] ?? ''); ?>"
                             loading="lazy"
                             decoding="async" />
                    </div>
                    <div class="lb-row__text">
                        <p class="lb-row__kicker">
                            <span><?php echo esc_html($num); ?></span>
                            <span aria-hidden="true"> / </span>
                            <span><?php echo esc_html($kicker); ?></span>
                        </p>
                        <h2 class="lb-row__title"><?php echo esc_html($row['title'] ?? ''); ?></h2>
                        <p class="lb-row__desc"><?php echo esc_html($row['desc'] ?? ''); ?></p>
                        <a class="lb-btn <?php echo $style === 'outline' ? 'lb-btn--outline' : 'lb-btn--primary'; ?>"
                           href="<?php echo esc_url($row['url'] ?? home_url('/contact/')); ?>">
                            <?php echo esc_html($row['button'] ?? ''); ?>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ─────────── Service Process ─────────── -->
    <section class="lb-container" aria-labelledby="lb-att-process-title">
        <header class="lb-att-head">
            <span class="lb-att-head__kicker"><?php echo esc_html($process_kicker); ?></span>
            <h2 class="lb-att-head__title" id="lb-att-process-title"><?php echo esc_html($process_title); ?></h2>
            <span class="lb-att__rule" aria-hidden="true"></span>
        </header>

        <ol class="lb-att-process" role="list">
            <?php foreach ($process_steps as $step) : ?>
                <li class="lb-att-step">
                    <h3 class="lb-att-step__title"><?php echo esc_html($step['title']); ?></h3>
                    <p class="lb-att-step__desc"><?php echo esc_html($step['desc']); ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
    </section>

    <!-- ─────────── CTA ─────────── -->
    <section class="lb-cta">
        <div class="lb-cta__inner">
            <h2 class="lb-cta__heading"><?php echo esc_html($cta_heading); ?></h2>
            <p class="lb-cta__sub"><?php echo esc_html($cta_sub); ?></p>
            <div class="lb-cta__actions">
                <a class="lb-btn lb-btn--primary" href="<?php echo esc_url(home_url('/contact/')); ?>">
                    <?php echo esc_html($cta_btn); ?>
                </a>
                <a class="lb-btn lb-btn--ghost" href="<?php echo esc_url($cta_url); ?>">
                    <?php echo esc_html($cta_link_lbl); ?>
                </a>
            </div>
        </div>
    </section>
</div><!-- /.lb-main -->

<!-- ─────────── Tiny progressive JS: filter tabs + reveal ─────────── -->
<script>
(function () {
    'use strict';
    var rows = document.querySelectorAll('.lb-att-rows .lb-row');
    var tabs = document.querySelectorAll('.lb-att-tabs .lb-att-tab');

    if (tabs.length) {
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) {
                    t.classList.remove('is-active');
                    t.setAttribute('aria-selected', 'false');
                });
                tab.classList.add('is-active');
                tab.setAttribute('aria-selected', 'true');

                var f = tab.getAttribute('data-filter');
                var wrap = document.getElementById('lb-att-rows');
                if (!wrap) return;
                wrap.classList.add('is-filtering');
                rows.forEach(function (r) {
                    var cat = r.getAttribute('data-category') || '';
                    var match = (f === '*') || (f === cat);
                    if (match) {
                        r.classList.remove('is-hidden');
                    } else {
                        r.classList.add('is-hidden');
                    }
                });
                setTimeout(function () { wrap.classList.remove('is-filtering'); }, 420);
            });
        });
    }

    /* Reveal-on-scroll (lightweight) */
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('is-visible');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        document.querySelectorAll('[data-lb-reveal]').forEach(function (el) { io.observe(el); });
    } else {
        document.querySelectorAll('[data-lb-reveal]').forEach(function (el) { el.classList.add('is-visible'); });
    }
})();
</script>

<?php get_footer(); ?>
