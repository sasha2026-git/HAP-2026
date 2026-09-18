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

/* v3.7.7: 统一从「规范页」读取 —— 站点存在多个同模板页面时，
 *   杜绝"后台在 A 页填字段、前台渲染 B 页 → 编辑与内容毫无关系" */
$lb_page_id = function_exists('hireai_ai_employees_page_id') ? hireai_ai_employees_page_id() : 0;
$lb_pid     = $lb_page_id > 0 ? $lb_page_id : (get_the_ID() ?: false);

/* --------------------------------------------------------------------
 * 1. PAGE FIELDS  (Hero + sections + CTA)
 * -------------------------------------------------------------------- */
$hero_note       = hireai_field('lookbook_hero_note',       $is_en ? '— Curated roles, on call' : '——  在职数字员工 · 精英岗位', $lb_pid);
$hero_kicker     = hireai_field('lookbook_hero_kicker',     $is_en ? 'The Atelier' : '数字工坊', $lb_pid);
$hero_title      = hireai_field('lookbook_hero_title',      $is_en ? 'Elite Digital Solutions' : '精英数字解决方案', $lb_pid);
$hero_subtitle   = hireai_field('lookbook_hero_subtitle',   $is_en ? '"AI-led process, Human-delivered results."' : '"AI 主导流程，人类交付成果。"', $lb_pid);

$cta_heading     = hireai_field('lookbook_cta_heading',     $is_en ? 'Ready to Redefine Humanity?' : '准备好重新定义人性了吗？', $lb_pid);
$cta_sub         = hireai_field('lookbook_cta_sub',         $is_en ? "Join the exclusive echelon of leaders leveraging Aurelian AI's bespoke ecosystem." : '加入运用 Aurelian AI 专属生态的领袖精英之列。', $lb_pid);
$cta_btn         = hireai_field('lookbook_cta_btn',         $is_en ? 'Start The Journey' : '开启旅程', $lb_pid);
$cta_link_lbl    = hireai_field('lookbook_cta_link',        $is_en ? 'Download Brand Book' : '下载品牌手册', $lb_pid);
$cta_url         = hireai_field('lookbook_cta_url',         '/case-insights/', $lb_pid);

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

/* v3.7.6: 中英 repeater 各自读取；当前语言为空时镜像另一语言。
 *   此前 EN 视图在 EN repeater 未填时直接跳到硬编码兜底 5 行 —— 与后台填的中文内容
 *   完全不同，是"页面对不上 ACF"的来源之一。镜像后：只要任一语言填了行，
 *   两种语言都显示同一份自定义内容（英文可在 EN repeater 里逐行覆盖）。 */
if (function_exists('have_rows')) {
    $read_emp_rows = function ($repeater_name) use ($is_en, $lb_pid) {
        $rows = [];
        if (have_rows($repeater_name, $lb_pid)) {
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
                $rows[] = $row;
            }
        }
        return $rows;
    };
    $rows_zh = $read_emp_rows('lookbook_employees');
    $rows_en = $read_emp_rows('lookbook_employees_en');
    $raw_rows = $is_en
        ? ($rows_en !== [] ? $rows_en : $rows_zh)
        : ($rows_zh !== [] ? $rows_zh : $rows_en);
}

/* v3.4.0: fallback 数据兜底（两种语言的 ACF Repeater 都为空时） */
$lb_rows_from_fallback = false;
if (empty($raw_rows) && function_exists('lookbook_fallback_employees')) {
    $raw_rows = lookbook_fallback_employees();
    $lb_rows_from_fallback = true;
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

/* v3.7.6: 恢复的筛选区 + 服务流程区容器 */
.lb-att-filtersec { padding: 0 var(--side, 24px); margin-bottom: clamp(56px, 8vw, 96px); }
.lb-att-devhint { max-width: 1280px; margin: 0 auto clamp(32px, 5vw, 48px); padding: 14px 20px; border: 1px dashed var(--lb-att-gold, #775a19); border-radius: 8px; background: rgba(233,193,118,.08); font-family: var(--font-body, 'Inter'), sans-serif; font-size: 14px; line-height: 1.7; color: var(--lb-att-mid, #444748); }
.lb-att-processsec { max-width: 1280px; margin: 0 auto; padding: clamp(60px, 8vw, 120px) var(--side, 24px) 0; }
.lb-att-process__note { margin: 28px 0 0; text-align: center; font-family: var(--font-body, 'Inter'), sans-serif; font-size: 15px; line-height: 1.6; color: var(--lb-att-mid); }

/* Responsive overrides */
@media (max-width: 960px) {
    .lb-att-process { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 540px) {
    .lb-att-process { grid-template-columns: 1fr; }
    .lb-att-tab { padding: 8px 16px; font-size: 11px; }
}

/* v3.0.8 (Bug B): section gap 160-200px（覆盖 style.css 默认 64-120px）
 * v3.5.7-p6: Hero 下 margin-bottom 砍半（用户反馈线条下还有大空白）
 * v3.5.7-p7: 再砍半（用户截图手机端还有大空白 80-100px） */
.lb-att .lb-hero {
    padding-block: clamp(53px, 7vw, 80px) clamp(13px, 2vw, 24px);
    /* v3.5.7-p7: 80-100px → 40-60px（再砍半） */
    margin-bottom: clamp(40px, 5vw, 60px);
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

    <!-- ─────────── 筛选 tabs（v3.7.6 恢复渲染：lookbook_filter_* 字段此前在后台可填但前台从未输出） ─────────── -->
    <?php
    $lb_cats = [];
    foreach ($raw_rows as $r) {
        $k = trim((string) ($r['kicker'] ?? ''));
        if ($k !== '' && !in_array($k, $lb_cats, true)) { $lb_cats[] = $k; }
    }
    ?>
    <?php if (!empty($lb_cats)) : ?>
    <section class="lb-att-filtersec">
        <div class="lb-att-head">
            <span class="lb-att-head__kicker"><?php echo esc_html(hireai_field('lookbook_filter_kicker', $is_en ? 'BROWSE BY CRAFT' : '分类浏览', $lb_pid)); ?></span>
            <h2 class="lb-att-head__title"><?php echo esc_html(hireai_field('lookbook_filter_title', $is_en ? 'Discover your digital employee by role and craft.' : '按角色与场景，发现属于你的数字员工。', $lb_pid)); ?></h2>
        </div>
        <div class="lb-att-tabs" role="tablist" id="lb-att-tabs">
            <button type="button" class="lb-att-tab is-active" data-cat="all" aria-selected="true"><?php echo esc_html(hireai_field('lookbook_filter_all', $is_en ? 'All' : '全部', $lb_pid)); ?></button>
            <?php foreach ($lb_cats as $lb_cat) : ?>
                <button type="button" class="lb-att-tab" data-cat="<?php echo esc_attr($lb_cat); ?>" aria-selected="false"><?php echo esc_html($lb_cat); ?></button>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <div class="lb-container">
        <?php if ($lb_rows_from_fallback && current_user_can('edit_pages')) : ?>
        <div class="lb-att-devhint">
            <strong>编辑者提示（访客不可见）：</strong>下面 5 张员工卡片是<strong>内置兜底内容</strong>，不来自任何 ACF 字段。要自定义卡片，请在后台「AI数字员工」页面编辑器下方找到 <strong>「AI 数字员工页 · 员工行（Repeater）」</strong>区块 → 添加行（每行一张卡，中文 / English 两个 Tab 独立填写；当前语言未填时前台自动显示另一语言的行）。
        </div>
        <?php endif; ?>
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

    <!-- ─────────── 服务流程（v3.7.6 恢复渲染：lookbook_process_* 共 10 个字段此前在后台可填但前台从未输出） ─────────── -->
    <?php
    $lb_process_d = [
        1 => ['t' => $is_en ? 'Discovery'   : '需求洞察', 'd' => $is_en ? 'Our consultants map your business context and KPIs.' : '我们的顾问与您一起梳理业务场景与核心指标。'],
        2 => ['t' => $is_en ? 'Curation'    : '方案设计', 'd' => $is_en ? 'Pick an archetype from our atelier and weave in your brand DNA.' : '从精品模板库中挑选角色底座，并融入品牌基因。'],
        3 => ['t' => $is_en ? 'Calibration' : '训练调优', 'd' => $is_en ? 'We fine-tune the model on your proprietary corpus to match tone and judgement.' : '以专属语料微调模型，确保语调与判断契合业务。'],
        4 => ['t' => $is_en ? 'Co-pilot'    : '上线陪跑', 'd' => $is_en ? 'After deployment, your dedicated concierge reviews and iterates monthly.' : '交付上线后由专属管家持续陪跑，按月复盘迭代。'],
    ];
    ?>
    <section class="lb-att-processsec">
        <div class="lb-att-head">
            <span class="lb-att-head__kicker"><?php echo esc_html(hireai_field('lookbook_process_kicker', $is_en ? 'OUR PROCESS' : '服务流程', $lb_pid)); ?></span>
            <h2 class="lb-att-head__title"><?php echo esc_html(hireai_field('lookbook_process_title', $is_en ? 'Four steps from discovery to deployment.' : '从了解到上线，四步即可拥有专属数字员工。', $lb_pid)); ?></h2>
        </div>
        <div class="lb-att-process">
            <?php for ($lb_i = 1; $lb_i <= 4; $lb_i++) : ?>
                <div class="lb-att-step">
                    <h3 class="lb-att-step__title"><?php echo esc_html(hireai_field("lookbook_process_step{$lb_i}_title", $lb_process_d[$lb_i]['t'], $lb_pid)); ?></h3>
                    <p class="lb-att-step__desc"><?php echo esc_html(hireai_field("lookbook_process_step{$lb_i}_desc", $lb_process_d[$lb_i]['d'], $lb_pid)); ?></p>
                </div>
            <?php endfor; ?>
        </div>
        <?php $lb_process_note = hireai_field('lookbook_process_note', $is_en ? 'Average delivery in 4–6 weeks, with a dedicated concierge throughout.' : '平均 4–6 周即可交付；全程由资深管家陪跑。', $lb_pid); ?>
        <?php if ($lb_process_note !== '') : ?>
            <p class="lb-att-process__note"><?php echo esc_html($lb_process_note); ?></p>
        <?php endif; ?>
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

    /* v3.7.6: 分类 tabs 客户端过滤（筛选 lb-row，data-category = 行 kicker） */
    var lbTabs = document.querySelectorAll('#lb-att-tabs .lb-att-tab');
    if (lbTabs.length) {
        lbTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var cat = tab.getAttribute('data-cat');
                lbTabs.forEach(function (t) {
                    var on = t === tab;
                    t.classList.toggle('is-active', on);
                    t.setAttribute('aria-selected', on ? 'true' : 'false');
                });
                document.querySelectorAll('#lb-att-rows > .lb-row').forEach(function (row) {
                    var show = cat === 'all' || row.getAttribute('data-category') === cat;
                    row.style.display = show ? '' : 'none';
                });
            });
        });
    }
})();
</script>

<?php get_footer(); ?>
