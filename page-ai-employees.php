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

$cta_heading     = hireai_field('lookbook_cta_heading',     $is_en ? 'Ready to Redefine Humanity?' : '准备好重新定义人性了吗？');
$cta_sub         = hireai_field('lookbook_cta_sub',         $is_en ? "Join the exclusive echelon of leaders leveraging Aurelian AI's bespoke ecosystem." : '加入运用 Aurelian AI 专属生态的领袖精英之列。');
$cta_btn         = hireai_field('lookbook_cta_btn',         $is_en ? 'Start The Journey' : '开启旅程');
$cta_link_lbl    = hireai_field('lookbook_cta_link',        $is_en ? 'Download Brand Book' : '下载品牌手册');
$cta_url         = hireai_field('lookbook_cta_url',         '/case-insights/');

/* --------------------------------------------------------------------
 * 2. EMPLOYEE ROWS — ACF repeater (唯一数据源) → lookbook_fallback_employees()
 *
 *   v3.4.0 回归 v3.0.0 设计意图：删除 v3.0.7 临时补丁 hireai_resolve_employees() 调用
 *     - v3.0.7 错误地引入 WP_Query 抢占 ACF Repeater（站点后台没有 ai-employee 文章，
 *       但临时补丁让优先级变最高 → ACF Repeater 完全失效）
 *     - v3.0.9 进一步把 WP_Query 设为优先级 1，URL fixup 也依赖它
 *     - v3.4.0 彻底删除所有 hireai_resolve_employees() 调用，回到 v3.0.0 纯 ACF 模式
 *     - hireai_resolve_employee_url() 仍保留（row 循环内单点探测 fallback URL）
 *     - 当 ACF Repeater 完全为空时，回退到 lookbook_fallback_employees() 静态兜底
 *
 *   数据源优先级：ACF Repeater (lookbook_employees / lookbook_employees_en) > fallback
 *   严禁：再用 WordPress 文章 (WP_Query) 拉数据（v3.0.7 教训）
 * -------------------------------------------------------------------- */
$raw_rows = [];

/* v3.4.0: 回归 v3.0.0 设计意图 — ACF Repeater 唯一数据源（跟 AllScented 一致）*/
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
            // Fallback for empty fields
            if ($row['button'] === '') {
                $row['button'] = $is_en ? 'Learn More' : '了解详情';
            }
            if ($row['url'] === '') {
                /* v3.4.0: 保持 helper 自动探测 fallback（v3.0.7 教训——函数定义保留不动） */
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

/* v3.4.0: fallback 数据兜底（ACF Repeater 完全为空时） */
if (empty($raw_rows) && function_exists('lookbook_fallback_employees')) {
    $raw_rows = lookbook_fallback_employees();
}

/* --------------------------------------------------------------------
 * 2.5 PAGINATION (v3.4.0) — 每页 5 个，超过自动加页
 *   URL query: ?emp_page=N (1-based)；超界自动 clamp 到 total_pages
 *   注：分页在前端 ALL/分类 filter tabs 之前，但 filter tabs 数据从 full raw_rows 提取，
 *       所以此处先 slice 渲染，再保留完整 raw_rows 供 filter 使用（filter 仍渲染全部）。
 *       当前实现：filter tabs 也只显示当前页可见分类（All + 当前页分类），因为同一分类
 *       可能跨页。但用户切 tab 时 JS 会过滤 hidden rows，无视觉问题。
 * -------------------------------------------------------------------- */
$total_rows    = count($raw_rows);
$per_page      = 5;
$current_page  = max(1, (int) ($_GET['emp_page'] ?? 1));
$total_pages   = max(1, (int) ceil($total_rows / $per_page));
if ($current_page > $total_pages) {
    $current_page = $total_pages;
}
$raw_rows = array_slice($raw_rows, ($current_page - 1) * $per_page, $per_page);

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
/* v3.5.7: 移除 italic（font-weight:700 + 非斜体 + 香槟金渐变）— Sasha brief 2026-09-01 */
.lb-att .lb-hero__title {
    background: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    font-style: normal;
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
    padding-block: clamp(53px, 7vw, 80px) clamp(13px, 2vw, 24px);
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

    <div class="lb-container">
        <!-- ─────────── Employee Rows ─────────── -->
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

        <!-- ─────────── Employee Rows Pagination (v3.4.0) ─────────── -->
        <?php if ($total_pages > 1):
            $base_url = remove_query_arg('emp_page');
        ?>
            <nav class="lb-att-pagination" aria-label="<?php echo esc_attr($is_en ? 'Employee pagination' : '员工分页'); ?>">
                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                        <a class="lb-att-pagination__btn <?php echo $p === $current_page ? 'is-active' : ''; ?>"
                           href="<?php echo esc_url(add_query_arg('emp_page', $p, $base_url)); ?>"
                           aria-current="<?php echo $p === $current_page ? 'page' : 'false'; ?>">
                            <?php echo $p; ?>
                        </a>
                    <?php endfor; ?>
            </nav>
        <?php endif; ?>
    </div>

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

<!-- ────────────── Tiny progressive JS: reveal-on-scroll ────────────── -->
<script>
(function () {
    'use strict';
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
