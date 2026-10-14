<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: Front Page
 * Hire AI People — Stitch-matched v3.0.1 (2026-08-22)
 *
 * 重写自 /tmp/stitch-check/{code.html,screen.png,DESIGN.md}
 * 七大 Section：Hero → Intro → Products → Solutions → Cases → FAQ → CTA
 *
 * v3.0.1 修复 v3.0.0 已知问题：
 *   - 修复 Hero CTA 按钮乱码：用 hireai_field('fp_hero_cta_1_url/title') 双字段读取，
 *     不再用 hireai_link_lang('fp_hero_cta_1', ...)'fp_hero_cta_1'（字段不存在，回落到了 URL 当 title）
 *   - 增加 Intro 区块 "探索更多" 幽灵按钮（design 要求）
 *   - 增加 Products 区块 "探索更多" 下划线链接（design 要求）
 *   - 移除 Material Symbols Outlined CDN 引用（用 inline SVG 替代）
 *   - 全 button 严格 label-md（14px / 600 / 0.1em uppercase）
 *   - 全 kicker 严格 label-sm（12px / 500 / 0.05em uppercase）
 *   - section gap 严格 120px（--gap: clamp(60px,8vw,120px)）
 *   - 金色斜体用 .hireai-fp__gold（135deg #e9c176 → #775a19）
 */
get_header();

/* ── 当前语言 ── */
$suffix = hireai_lang_suffix();
$is_en  = ($suffix === '_en');
$lang   = $is_en ? 'en' : 'zh';
$home   = get_stylesheet_directory_uri();

/* ═══════════════════════════════════════════════════════════════════════
   ACF 数据加载（严格匹配 functions.php 第 774–928 行注册的字段）
   注：fp_*_url / fp_*_title / fp_*_label 都是单独的 text 字段（_zh/_en 双后缀）
   ═══════════════════════════════════════════════════════════════════════ */

/* Hero */
$hero_kicker   = hireai_field_lang('fp_hero_kicker',   $lang, $is_en ? 'AETHER LUXE AI' : '工匠精神与算法');
$hero_static   = hireai_field_lang('fp_hero_static',   $lang, $is_en ? 'Redefining' : '重新定义');
$hero_accent_primary   = hireai_field_lang('fp_hero_accent_primary', $lang, $is_en ? 'Digital' : '');
$hero_accent_secondary = hireai_field_lang('fp_hero_accent_secondary', $lang, $is_en ? 'Productivity' : '');
if ('' === $hero_accent_primary && '' === $hero_accent_secondary) {
    $hero_accent = hireai_field_lang('fp_hero_accent', $lang, $is_en ? 'Digital Productivity' : '数字生产力');
    if (preg_match('/^(\p{Han}{2})(\p{Han}+)$/u', $hero_accent, $hero_accent_parts)) {
        $hero_accent_primary   = $hero_accent_parts[1];
        $hero_accent_secondary = $hero_accent_parts[2];
    } else {
        $hero_accent_primary = $hero_accent;
    }
}
$hero_subtitle = hireai_field_lang('fp_hero_subtitle', $lang, $is_en ? 'Fusing cutting-edge technology with a luxurious aesthetic to craft your bespoke digital workforce.' : '融合尖端科技与奢华质感，为您打造专属的数字员工矩阵。');
$hero_image    = hireai_image('fp_hero_image', $home . '/assets/img/defaults/hero-home.jpg');
$hero_cta_1_url   = hireai_field('fp_hero_cta_1_url',   home_url('/ai-employees/'));
$hero_cta_1_title = hireai_field_lang('fp_hero_cta_1_title', $lang, $is_en ? 'Explore Series' : '探索系列');
$hero_cta_2_url   = hireai_field('fp_hero_cta_2_url',   home_url('/contact/'));
$hero_cta_2_title = hireai_field_lang('fp_hero_cta_2_title', $lang, $is_en ? 'Custom Consultation' : '定制咨询');

/* Intro */
$intro_kicker    = hireai_field_lang('fp_intro_kicker', $lang, $is_en ? 'Craftsmanship Meets Algorithm' : '工匠精神与算法');
$intro_title     = hireai_field_lang('fp_intro_title',  $lang, $is_en ? 'Shaping existence beyond physical boundaries.' : '塑造超越物理边界的存在。');
$intro_desc      = hireai_field_lang('fp_intro_desc',   $lang, $is_en ? 'We combine the rigor of traditional luxury with the infinite potential of neural networks. Every digital employee is a one-of-a-kind masterpiece, designed for elegance, intelligence, and resonance.' : '我们结合传统奢华的严谨工艺与神经网络的无限可能。每一位数字员工都是独一无二的杰作，专为优雅、智慧与共鸣而设计。');
$intro_cta_label = hireai_field_lang('fp_intro_cta_title', $lang, $is_en ? 'Explore More' : '探索更多');
$intro_cta_url   = hireai_field('fp_intro_cta_url', home_url('/ai-employees/'));

/* Products section header */
$prod_section_kicker = hireai_field_lang('fp_products_kicker', $lang, $is_en ? 'Limited Neural Series' : '限量神经元系列');
$prod_section_title  = hireai_field_lang('fp_products_title',  $lang, $is_en ? 'AI Digital Employees' : 'AI 数字员工');
$prod_section_sub    = hireai_field_lang('fp_products_subtitle', $lang, $is_en ? 'Each digital employee brings a unique soul, refined skills, and unmatched capabilities.' : '每一位数字员工都拥有独特的灵魂、技能与能力，随时加入您的团队。');
$prod_explore_label  = hireai_field_lang('fp_products_explore_label', $lang, $is_en ? 'Explore More' : '探索更多');
$prod_explore_url    = hireai_field('fp_products_explore_url', home_url('/ai-employees/'));

/* v3.0.7: 智能探测 (多 slug + 中文 category) — 避免 v3.0.6 仅 'ai-employee' 失败导致跳 /contact/ */
$ai_employee_posts = function_exists('hireai_resolve_employees') ? hireai_resolve_employees(3) : [];
$ai_employee_slugs = [];
foreach ($ai_employee_posts as $emp_post) {
    if ($emp_post instanceof WP_Post) {
        $ai_employee_slugs[] = $emp_post->post_name;
    }
}

$products = [];
foreach ([1, 2, 3] as $index) {
    $number = sprintf('%02d', $index);
    $products[] = [
        'title' => hireai_field("home_product_{$number}_title", '', null, $index),
        'desc'  => hireai_field("home_product_{$number}_description", ''),
        'badge' => hireai_field("home_product_{$number}_badge", ''),
        'img'   => hireai_image("home_product_{$number}_image", ''),
        'url'   => hireai_field("home_product_{$number}_url", home_url('/ai-employees/')),
        'btn'   => hireai_field("home_product_{$number}_cta_label", ''),
    ];
}

/* ═══════════════════════════════════════════════════════════════════════
   v3.6.0 改造 — 5 个 Section 渲染逻辑升级
   策略（C+ACF 双 fallback + 保留旧链）:
     - 优先：v3.6.0 NEW ACF (group_frontpage_v360) 显式双语字段 / repeater
     - 次优：v3.6.0 NEW WC 商品（sol_featured_on_home=true）的双语 cover
     - 第三：v3.5.7-p27 既有链 WC 商品 → ACF fp_* 字段 → 默认值
   安全约束:
     - 不删除 / 不改 hireai_* helper 函数
     - 不改 typography / spacing / radius / container 数值
     - 保留 hireai_get_ai_solutions_products 函数定义(虽然此处改用 inline WP_Query)
   ═══════════════════════════════════════════════════════════════════════ */

/* ---------- 读 NEW ACF 双语 helper（直接读 _zh / _en 后缀名） ---------- */
$hireai_v360 = function ($base, $lang_code) {
    $sfx = ($lang_code === 'en') ? '_en' : '_zh';
    if (!function_exists('get_field')) return '';
    $v = get_field($base . $sfx);
    if (is_array($v)) return isset($v['url']) ? (string) $v['url'] : '';
    return is_string($v) ? $v : '';
};
$hireai_v360_url = function ($base) {
    if (!function_exists('get_field')) return '';
    $v = get_field($base);
    return is_string($v) ? trim($v) : '';
};
$hireai_v360_image_url = function ($base) {
    if (!function_exists('get_field')) return '';
    $v = get_field($base);
    if (is_array($v) && !empty($v['url'])) return (string) $v['url'];
    if (is_string($v)) return $v;
    return '';
};

/* ═══════════════════════════════════════════════════════════════════════
   ① Hero 区域 — v3.7.5 修复
   字段组已合并为唯一一套 group_front_page_v375（functions.php）。
   HTML 实际渲染的是第 34-42 行读取的 $hero_kicker / $hero_static /
   $hero_accent / $hero_subtitle / $hero_cta_1_* / $hero_cta_2_*；
   v3.6.0 曾在这里把 $hireai_v360('fp_hero_title'/'fp_hero_cta_label'/
   'fp_hero_cta_url') 读进从未被渲染的死变量，导致后台改 v3.6.0 的
   Hero 字段前台毫无变化 —— 这些死读取已删除。
   ═══════════════════════════════════════════════════════════════════════ */
$hero_accent      = hireai_field_lang('fp_hero_accent', $lang, $is_en ? 'Digital Labor' : '数字劳动力');

/* ═══════════════════════════════════════════════════════════════════════
   ② 解决方案区域 — 三层 fallback
   Layer 1: NEW ACF repeater (fp_sol_static_repeater)  ← D
   Layer 2: NEW WC featured (sol_featured_on_home=1)   ← C
   Layer 3: p27 旧链 WC → ACF fp_sol1..4 → defaults
   ═══════════════════════════════════════════════════════════════════════ */
$sol_section_kicker = $hireai_v360('fp_solutions_kicker',       $lang) ?: hireai_field_lang('fp_solutions_kicker',       $lang, $is_en ? 'Industry Empowerment' : '行业赋能');
$sol_section_title  = $hireai_v360('fp_solutions_title',        $lang) ?: hireai_field_lang('fp_solutions_title',        $lang, $is_en ? 'AI Solutions' : 'AI 解决方案');
$sol_section_sub    = $hireai_v360('fp_solutions_subtitle',     $lang) ?: hireai_field_lang('fp_solutions_subtitle',     $lang, $is_en ? 'Bespoke intelligent solutions across industries.' : '面向多元行业，打造量身定制的智能解决方案。');
$sol_explore_label  = $hireai_v360('fp_solutions_explore_label',$lang) ?: hireai_field_lang('fp_solutions_explore_label',$lang, $is_en ? 'Explore More' : '探索更多');
$sol_explore_url    = $hireai_v360_url('fp_solutions_explore_url') ?: hireai_field('fp_solutions_explore_url', home_url('/ai-solutions/'));

$solutions = [];
foreach ([1, 2, 3, 4] as $index) {
    $number = sprintf('%02d', $index);
    if (!hireai_field("home_solution_{$number}_enabled", false)) continue;
    $solutions[] = [
        'title' => hireai_field("home_solution_{$number}_title", ''),
        'desc'  => hireai_field("home_solution_{$number}_description", ''),
        'tag'   => hireai_field("home_solution_{$number}_tag", ''),
        'img'   => hireai_image("home_solution_{$number}_image", ''),
        'url'   => hireai_field("home_solution_{$number}_url", home_url('/ai-solutions/')),
    ];
}

$cases_major = [
    'tag'   => hireai_field('home_case_major_label', ''),
    'title' => hireai_field('home_case_major_title', ''),
    'desc'  => hireai_field('home_case_major_description', ''),
    'img'   => hireai_image('home_case_major_image', ''),
    'url'   => hireai_field('home_case_major_url', home_url('/cases-insights/')),
];
$side_cases = [];
foreach ([1, 2] as $index) {
    $number = sprintf('%02d', $index);
    $side_cases[] = [
        'tag'   => hireai_field("home_case_minor_{$number}_tag", ''),
        'title' => hireai_field("home_case_minor_{$number}_title", ''),
        'desc'  => hireai_field("home_case_minor_{$number}_description", ''),
        'img'   => hireai_image("home_case_minor_{$number}_image", ''),
        'url'   => hireai_field("home_case_minor_{$number}_url", home_url('/cases-insights/')),
    ];
}

/* ═══════════════════════════════════════════════════════════════════════
   ④ FAQ — 双层 fallback (NEW ACF repeater → 旧 fp_faq1..3 + 默认值)
   ═══════════════════════════════════════════════════════════════════════ */
$faq_kicker        = $hireai_v360('fp_faq_kicker',        $lang) ?: hireai_field_lang('fp_faq_kicker',        $lang, $is_en ? 'FAQ' : '常见问题');
$faq_title         = $hireai_v360('fp_faq_title',         $lang) ?: hireai_field_lang('fp_faq_title',         $lang, $is_en ? 'Answers to your questions about digital employees.' : '解答关于数字员工的疑虑，开启智能新纪元。');
/* v3.7.5: 删除死变量 $faq_sub（fp_faq_subtitle 从未被渲染，FAQ 副标题走 fp_faq_title） */
$faq_explore_label = $hireai_v360('fp_faq_explore_label', $lang) ?: hireai_field_lang('fp_faq_explore_label', $lang, $is_en ? 'Explore More' : '探索更多');
$faq_explore_url   = $hireai_v360_url('fp_faq_explore_url') ?: hireai_field('fp_faq_explore_url', home_url('/faq/'));

$faq_items = [];
foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10] as $index) {
    $number = sprintf('%02d', $index);
    $question = trim((string) hireai_field("home_faq_{$number}_question", ''));
    $answer   = trim((string) hireai_field("home_faq_{$number}_answer", ''));
    if ($question === '' && $answer === '') continue;
    $faq_items[] = ['q' => $question, 'a' => $answer];
}

/* ═══════════════════════════════════════════════════════════════════════
   ⑤ CTA — NEW ACF 优先 → 旧字段 fallback
   ═══════════════════════════════════════════════════════════════════════ */
$cta_kicker        = hireai_field('home_cta_kicker', '');
$cta_title         = hireai_field('home_cta_title', '');
$cta_desc          = hireai_field('home_cta_description', '');
$cta_btn_title     = hireai_field('home_cta_primary_label', '');
$cta_btn_url       = hireai_field('home_cta_primary_url', home_url('/contact/'));
$cta_btn_2_title   = hireai_field('home_cta_secondary_label', '');
$cta_btn_2_url     = hireai_field('home_cta_secondary_url', home_url('/contact/'));


/* i18n text */
$t = [
    'scroll'   => $is_en ? 'Scroll'         : '向下滚动',
    'prev'     => $is_en ? 'Previous'       : '上一个',
    'next'     => $is_en ? 'Next'           : '下一个',
    'expand'   => $is_en ? 'Expand answer'  : '展开答案',
    'collapse' => $is_en ? 'Collapse answer' : '收起答案',
];
?>

<!-- ═══════════════════════════════════════════════════════════════════════
     页面专有样式（hireai-fp-* 命名空间；严格对齐 DESIGN.md + Stitch 截图）
     ═══════════════════════════════════════════════════════════════════════ -->
<style>
/* ── Reset / Base ──────────────────────────────────────────────────── */
.hireai-fp{box-sizing:border-box;margin:0;padding:0;background:var(--surface,#faf9f9);color:#1a1c1c;font-family:'Inter','Noto Sans SC',-apple-system,BlinkMacSystemFont,sans-serif;line-height:1.6;overflow-x:hidden;}
.hireai-fp *,.hireai-fp *::before,.hireai-fp *::after{box-sizing:border-box;}
.hireai-fp img{display:block;max-width:100%;height:auto;}
.hireai-fp-hero__bg img{height:100%;}
.hireai-fp a:not([class*="hireai-fp__btn"]):not(.hireai-fp-products__explore){text-decoration:none;color:inherit;}
.hireai-fp button{font-family:inherit;cursor:pointer;border:0;background:none;}

/* ── Tokens（严格对齐 DESIGN.md） ─────────────────────────────────── */
.hireai-fp{
    --gold:#775a19; --gold-light:#e9c176; --gold-pale:#ffdea5;
    --gold-line:rgba(119,90,25,.18);
    --ink:#1a1c1c; --ink-muted:#444748;
    --line:rgba(196,199,199,.30);
    --surface:#faf9f9; --surface-low:#f4f3f3; --surface-white:#fff;
    --side:clamp(20px,5vw,80px);
    --max:1440px;
    --f-display:'Playfair Display','Noto Serif SC',Georgia,serif;
    --f-headline:'Playfair Display','Noto Serif SC',Georgia,serif;
    --f-body:'Inter','Noto Sans SC',-apple-system,sans-serif;
    --f-label:'Inter','Noto Sans SC',-apple-system,sans-serif;
    /* Typography tokens（严格对齐 DESIGN.md） */
    --sz-d:clamp(40px,6vw,72px);            /* display-lg  700 1.1 */
    --sz-h1:clamp(32px,4.5vw,48px);         /* headline-lg 600 1.2 */
    --sz-h2:clamp(28px,3.5vw,42px);         /* mid         600 1.2 */
    --sz-h3:clamp(20px,2vw,32px);           /* headline-md 500 1.3 */
    --sz-bl:18px; --sz-bm:16px;             /* body-lg / body-md  400 1.6 */
    --sz-lm:14px; --sz-ls:12px;             /* label-md (600 .1em) / label-sm (500 .05em); v3.3.0 sz-lm stays = label-md spec §2 */
    /* Spacing tokens（严格对齐 DESIGN.md） */
    --gap:clamp(60px,8vw,120px);            /* section-gap */
    --gutter:24px;
    /* Buttons */
    --btn-pad-y:16px; --btn-pad-x:clamp(28px,4vw,48px);
}

/* ── 通用：金质渐变斜体（hero accent 用） ──────────────────────── */
.hireai-fp__gold{
    background:linear-gradient(135deg,#e9c176 0%,#775a19 100%);
    -webkit-background-clip:text;background-clip:text;
    -webkit-text-fill-color:transparent;color:transparent;
    font-style:italic;font-weight:400;
    display:inline-block;
}

/* ── 通用：kicker 小标签（label-sm 严格对齐 DESIGN.md） ────────── */
.hireai-fp__kicker{
    display:inline-block;font-family:var(--f-label);
    font-size:var(--sz-ls);font-weight:500;line-height:1.2;
    letter-spacing:.05em;text-transform:uppercase;color:var(--gold);
    margin:0 0 16px;
}

/* ── 通用：section padding（120px） ─────────────────────────────── */
.hireai-fp__section{
    padding-block:var(--gap);
    padding-inline:var(--side);
    max-width:var(--max);
    margin-inline:auto;
}

/* ── 通用：按钮 pill（label-md 严格对齐 DESIGN.md） ─────────────── */
/* v3.0.6: min-height 56px (hireaipeople.txt 主按钮); font uses Montserrat (English body spec) */
.hireai-fp__btn{
    display:inline-flex;align-items:center;justify-content:center;
    gap:8px;cursor:pointer;text-align:center;white-space:nowrap;
    font-family:var(--f-label);font-size:var(--sz-lm);font-weight:600;
    line-height:1.2;letter-spacing:.1em;text-transform:uppercase;
    min-height:56px;
    padding:var(--btn-pad-y) var(--btn-pad-x);
    border-radius:9999px;border:1px solid transparent;
    transition:all .3s ease;
}
.hireai-fp__btn.hireai-fp__btn--primary, .hireai-fp__btn--primary.hireai-fp__btn--primary{background:#000;color:#fff!important;border-color:#000;}
.hireai-fp__btn.hireai-fp__btn--primary:hover, .hireai-fp__btn--primary.hireai-fp__btn--primary:hover{box-shadow:0 0 24px rgba(119,90,25,.45);}
.hireai-fp__btn.hireai-fp__btn--outline, .hireai-fp__btn--outline.hireai-fp__btn--outline{background:rgba(255,255,255,.7);color:#775a19!important;border:1px solid rgba(119,90,25,.45);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);}
.hireai-fp__btn.hireai-fp__btn--outline:hover, .hireai-fp__btn--outline.hireai-fp__btn--outline:hover{background:rgba(119,90,25,.12);}
.hireai-fp__btn.hireai-fp__btn--ghost, .hireai-fp__btn--ghost.hireai-fp__btn--ghost{background:transparent;color:#1a1c1c!important;border:1px solid #1a1c1c;}
.hireai-fp__btn.hireai-fp__btn--ghost:hover, .hireai-fp__btn--ghost.hireai-fp__btn--ghost:hover{background:#1a1c1c;color:#fff;}
.hireai-fp__btn.hireai-fp__btn--gold, .hireai-fp__btn--gold.hireai-fp__btn--gold{background:linear-gradient(135deg,#e9c176 0%,#775a19 100%);color:#fff!important;border-color:transparent;}
.hireai-fp__btn.hireai-fp__btn--gold:hover, .hireai-fp__btn--gold.hireai-fp__btn--gold:hover{box-shadow:0 0 32px rgba(233,193,118,.45);transform:translateY(-1px);}

/* ═══════════════════════════════════════════════════════════════════════
   1) HERO  ── Stitch code.html Hero (满屏 / 背景图 / 双按钮 / 滚动指示)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-hero{position:relative;width:100%;height:min(56.25vw,860px);display:block;overflow:hidden;background:var(--surface,#faf9f9);}
.hireai-fp-hero__bg{position:absolute;left:0;top:0;width:100%;height:100%;overflow:hidden;}
.hireai-fp-hero__bg img{display:block;width:100%;height:100%;object-fit:cover;object-position:center bottom;}
.hireai-fp-hero__shade{position:absolute;left:0;top:0;right:0;bottom:0;z-index:1;background:linear-gradient(180deg,rgba(249,249,249,.55) 0%,rgba(249,249,249,.20) 45%,var(--surface,#faf9f9) 100%);}
.hireai-fp-hero__inner,
html body .hireai-fp .hireai-fp-hero .hireai-fp-hero__inner{position:absolute;inset:auto 0 0 0;z-index:2;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;text-align:center;width:min(100%,920px);min-width:min(100%,920px);margin-inline:auto;padding:0 var(--side) 80px;writing-mode:horizontal-tb;}
.hireai-fp-hero__inner > *,
html body .hireai-fp .hireai-fp-hero .hireai-fp-hero__inner > *{min-width:0;max-width:100%;}
.hireai-fp-hero__kicker{margin-bottom:24px;}
.hireai-fp-hero__title{font-family:var(--f-display);font-size:var(--sz-d);font-weight:700;line-height:1.1;letter-spacing:-.02em;color:#000;margin:0 0 24px;}
.hireai-fp-hero__title,
html body .hireai-fp .hireai-fp-hero .hireai-fp-hero__title{display:grid;width:100%;max-width:none;justify-items:center;}
.hireai-fp-hero__title-line,
html body .hireai-fp .hireai-fp-hero .hireai-fp-hero__title-line{display:block;width:100%;max-width:100%;overflow-wrap:break-word;writing-mode:horizontal-tb;}
.hireai-fp-hero__subtitle{font-family:var(--f-body);font-size:var(--sz-bl);font-weight:400;line-height:1.6;color:rgba(26,28,28,.80);margin:0 auto 36px;width:min(100%,680px);max-width:680px;overflow-wrap:break-word;}
/* v3.3.0: spec §4 Hero 副标题 = body-lg italic (en only); zh keeps upright */
html[lang="en"] .hireai-fp-hero__subtitle{font-style:italic;}
.hireai-fp-hero__actions{display:flex;flex-wrap:wrap;justify-content:center;gap:16px;}
.hireai-fp-hero__scroll{position:absolute;left:50%;bottom:32px;transform:translateX(-50%);z-index:3;color:rgba(0,0,0,.45);display:inline-flex;animation:hireai-fp-bounce 2.2s ease-in-out infinite;}
.hireai-fp-hero__scroll svg{width:36px;height:36px;}
@keyframes hireai-fp-bounce{0%,100%{transform:translate(-50%,0);}50%{transform:translate(-50%,8px);}}

/* ═══════════════════════════════════════════════════════════════════════
   2) INTRO  ── 居中：kicker + 大标题 + 描述 + ghost CTA
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-intro{background:var(--surface,#faf9f9);padding-block:var(--gap);padding-inline:var(--side);text-align:center;}
.hireai-fp-intro__inner{max-width:760px;margin:0 auto;}
.hireai-fp-intro__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0 0 24px;}
.hireai-fp-intro__desc{font-family:var(--f-body);font-size:var(--sz-bl);line-height:1.7;color:var(--ink-muted);margin:0 auto 36px;max-width:680px;}
.hireai-fp-intro__cta{display:inline-flex;}

/* ═══════════════════════════════════════════════════════════════════════
   3) PRODUCTS  ── Stitch AI 数字员工 (3-col 卡片 / aspect 3:4)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-products{background:var(--surface-low,#f4f3f3);padding-block:var(--gap);padding-inline:var(--side);color:#1a1c1c;}
.hireai-fp-products__inner{max-width:var(--max);margin:0 auto;}
.hireai-fp-products__head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:clamp(40px,5vw,72px);padding-bottom:48px;border-bottom:1px solid var(--line);}
.hireai-fp-products__head-text{flex:1;min-width:280px;}
.hireai-fp-products__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0 0 12px;}
.hireai-fp-products__title .hireai-fp__gold{font-style:italic;font-weight:400;}
.hireai-fp-products__sub{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:0 0 20px;max-width:560px;}
.hireai-fp-products__explore{display:inline-flex;align-items:center;font-family:var(--f-label);font-size:var(--sz-lm);font-weight:600;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:#000;padding-bottom:4px;border-bottom:1px solid #000;transition:color .3s,border-color .3s;}
.hireai-fp-products__explore:hover{color:var(--gold);border-bottom-color:var(--gold);}
.hireai-fp-products__nav{display:flex;gap:12px;}
.hireai-fp-products__nav button{width:48px;height:48px;border-radius:50%;border:1px solid #c4c7c7;background:transparent;color:#1a1c1c;display:inline-flex;align-items:center;justify-content:center;transition:all .25s;}
.hireai-fp-products__nav button:hover{background:rgba(0,0,0,.06);border-color:#000;}
.hireai-fp-products__nav svg{width:20px;height:20px;}

.hireai-fp-products__grid{display:grid;grid-template-columns:1fr;gap:var(--gutter);}
@media(min-width:768px){.hireai-fp-products__grid{grid-template-columns:repeat(3,1fr);}}

.hireai-fp-product{display:flex;flex-direction:column;}
.hireai-fp-product__media{position:relative;aspect-ratio:3/4;overflow:hidden;margin-bottom:24px;background:#eee;border:1px solid var(--line);transition:border-color .5s;}
.hireai-fp-product__media:hover{border-color:var(--gold-light);}
.hireai-fp-product__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-product:hover .hireai-fp-product__img{transform:scale(1.05);}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.18em */
.hireai-fp-product__badge{position:absolute;top:16px;right:16px;background:rgba(255,255,255,.80);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);padding:6px 14px;border-radius:9999px;border:1px solid rgba(0,0,0,.06);font-family:var(--f-label);font-size:11px;font-weight:700;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:#000;white-space:nowrap;}
.hireai-fp-product__name{font-family:var(--f-headline);font-size:var(--sz-h3);font-weight:500;line-height:1.3;color:#000;margin:0 0 8px;}
.hireai-fp-product__desc{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:0 0 20px;}
.hireai-fp-product__btn{width:100%;margin-top:auto;}

/* ═══════════════════════════════════════════════════════════════════════
   4) SOLUTIONS  ── Stitch AI 解决方案 (2x2 aspect-video 卡片)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-solutions{background:var(--surface,#faf9f9);padding-block:var(--gap);padding-inline:var(--side);}
.hireai-fp-solutions__inner{max-width:var(--max);margin:0 auto;}
.hireai-fp-solutions__head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:clamp(40px,5vw,64px);}
.hireai-fp-solutions__head-text{flex:1;min-width:280px;max-width:640px;}
.hireai-fp-solutions__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0;}
.hireai-fp-solutions__title .hireai-fp__gold{font-style:italic;font-weight:400;}
.hireai-fp-solutions__sub{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:12px 0 0;max-width:560px;}

.hireai-fp-solutions__grid{display:grid;grid-template-columns:1fr;gap:24px;margin-bottom:48px;}
@media(min-width:768px){.hireai-fp-solutions__grid{grid-template-columns:repeat(2,1fr);gap:clamp(24px,3vw,48px);}}

.hireai-fp-sol{position:relative;display:block;aspect-ratio:16/9;overflow:hidden;border:1px solid var(--line);transition:border-color .5s;}
.hireai-fp-sol:hover{border-color:var(--gold-light);}
.hireai-fp-sol__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-sol:hover .hireai-fp-sol__img{transform:scale(1.05);}
.hireai-fp-sol__placeholder{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:#e8e8e8;color:rgba(119,90,25,.45);}
.hireai-fp-sol__placeholder svg{width:64px;height:64px;}
.hireai-fp-sol__overlay{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:32px;color:#fff;background:linear-gradient(180deg,transparent 0%,rgba(0,0,0,.20) 40%,rgba(0,0,0,.85) 100%);}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.2em */
.hireai-fp-sol__tag{display:inline-block;font-family:var(--f-label);font-size:11px;font-weight:700;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.85);margin-bottom:10px;}
.hireai-fp-sol__title{font-family:var(--f-headline);font-size:clamp(20px,1.8vw,26px);font-weight:500;line-height:1.3;color:#fff;margin:0 0 8px;}
.hireai-fp-sol__desc{font-family:var(--f-body);font-size:14px;line-height:1.6;color:rgba(255,255,255,.85);margin:0;max-width:36ch;}
.hireai-fp-solutions__cta{text-align:center;}

/* ═══════════════════════════════════════════════════════════════════════
   5) CASES  ── Stitch 案例 & 洞察 (12-col grid, 8+4)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-cases{background:var(--surface-white,#fff);padding-block:var(--gap);padding-inline:var(--side);border-top:1px solid var(--line);}
.hireai-fp-cases__inner{max-width:var(--max);margin:0 auto;}
.hireai-fp-cases__head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:clamp(40px,5vw,64px);padding-bottom:48px;border-bottom:1px solid var(--line);}
.hireai-fp-cases__head-text{flex:1;min-width:280px;max-width:640px;}
.hireai-fp-cases__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0;}
.hireai-fp-cases__title .hireai-fp__gold{font-style:italic;font-weight:400;}
.hireai-fp-cases__sub{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:12px 0 0;}
.hireai-fp-cases__nav{display:flex;gap:12px;}
.hireai-fp-cases__nav button{width:48px;height:48px;border-radius:50%;border:1px solid #c4c7c7;background:transparent;color:#1a1c1c;display:inline-flex;align-items:center;justify-content:center;transition:all .25s;}
.hireai-fp-cases__nav button:hover{background:rgba(0,0,0,.06);border-color:#000;}
.hireai-fp-cases__nav svg{width:20px;height:20px;}

.hireai-fp-cases__grid{display:grid;grid-template-columns:1fr;gap:clamp(28px,3vw,40px);}
@media(min-width:1024px){.hireai-fp-cases__grid{grid-template-columns:repeat(12,1fr);}}

.hireai-fp-case-major{grid-column:span 1;display:flex;flex-direction:column;cursor:pointer;transition:transform .4s;}
@media(min-width:1024px){.hireai-fp-case-major{grid-column:span 8;}}
.hireai-fp-case-major__media{aspect-ratio:16/9;overflow:hidden;border:1px solid var(--line);margin-bottom:24px;}
.hireai-fp-case-major__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-case-major:hover .hireai-fp-case-major__img{transform:scale(1.05);}
.hireai-fp-case-major__body{padding:0 4px;}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.2em */
.hireai-fp-case-major__tag{display:inline-block;font-family:var(--f-label);font-size:11px;font-weight:700;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:10px;}
.hireai-fp-case-major__title{font-family:var(--f-headline);font-size:clamp(20px,2vw,var(--sz-h3));font-weight:500;line-height:1.3;color:#000;margin:0 0 12px;transition:color .3s;}
.hireai-fp-case-major:hover .hireai-fp-case-major__title{color:var(--gold);}
.hireai-fp-case-major__desc{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:0;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}

.hireai-fp-cases__side{grid-column:span 1;display:flex;flex-direction:column;gap:clamp(28px,3vw,40px);}
@media(min-width:1024px){.hireai-fp-cases__side{grid-column:span 4;}}

.hireai-fp-case-side{display:flex;flex-direction:column;cursor:pointer;}
.hireai-fp-case-side__media{aspect-ratio:16/9;overflow:hidden;border:1px solid var(--line);margin-bottom:16px;background:#eee;}
.hireai-fp-case-side__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-case-side:hover .hireai-fp-case-side__img{transform:scale(1.05);}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.2em */
.hireai-fp-case-side__tag{display:inline-block;font-family:var(--f-label);font-size:11px;font-weight:700;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;}
.hireai-fp-case-side__title{font-family:var(--f-headline);font-size:clamp(17px,1.6vw,21px);font-weight:500;line-height:1.3;color:#000;margin:0 0 8px;transition:color .3s;}
.hireai-fp-case-side:hover .hireai-fp-case-side__title{color:var(--gold);}
.hireai-fp-case-side__desc{font-family:var(--f-body);font-size:14px;line-height:1.6;color:var(--ink-muted);margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}

.hireai-fp-cases__cta{text-align:center;margin-top:clamp(40px,5vw,72px);}

/* ═══════════════════════════════════════════════════════════════════════
   6) FAQ  ── Stitch 常见问题 (3 项手风琴)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-faq{background:var(--surface,#faf9f9);padding-block:var(--gap);padding-inline:var(--side);border-top:1px solid var(--line);text-align:center;}
.hireai-fp-faq__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0;}
.hireai-fp-faq__title .hireai-fp__gold{font-style:italic;font-weight:400;}
.hireai-fp-faq__sub{font-family:var(--f-body);font-size:var(--sz-bl);line-height:1.6;color:var(--ink-muted);margin:16px auto 0;max-width:640px;}
.hireai-fp-faq__list{max-width:780px;margin:clamp(40px,5vw,72px) auto 0;text-align:left;}
.hireai-fp-faq-item{border-bottom:1px solid var(--line);}
.hireai-fp-faq-item__header{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:20px 0;cursor:pointer;transition:color .3s;}
.hireai-fp-faq-item__header:hover .hireai-fp-faq-item__q,
.hireai-fp-faq-item.is-open .hireai-fp-faq-item__q{color:var(--gold);}
.hireai-fp-faq-item__q{flex:1;font-family:var(--f-headline);font-size:clamp(18px,1.6vw,22px);font-weight:500;line-height:1.3;color:#000;margin:0;text-align:left;transition:color .3s;}
.hireai-fp-faq-item__icon{flex-shrink:0;width:24px;height:24px;color:var(--ink-muted);transition:transform .3s;}
.hireai-fp-faq-item.is-open .hireai-fp-faq-item__icon{transform:rotate(45deg);}
.hireai-fp-faq-item__body{max-height:0;overflow:hidden;transition:max-height .35s ease-out;}
.hireai-fp-faq-item.is-open .hireai-fp-faq-item__body{max-height:640px;}
.hireai-fp-faq-item__answer{padding-bottom:20px;font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.7;color:rgba(68,71,72,.85);}
.hireai-fp-faq__cta{text-align:center;margin-top:clamp(40px,5vw,72px);}

/* ═══════════════════════════════════════════════════════════════════════
   7) CTA BAND  ── Stitch 大 CTA (深色 + 金色 radial glow)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-cta{position:relative;background:#000;color:#fff;padding:clamp(80px,10vw,140px) var(--side);text-align:center;overflow:hidden;}
.hireai-fp-cta__glow{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:clamp(400px,60vw,720px);aspect-ratio:1/1;background:radial-gradient(circle,rgba(119,90,25,.30),transparent 65%);pointer-events:none;}
.hireai-fp-cta__inner{position:relative;max-width:760px;margin:0 auto;}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.3em (way too wide) */
.hireai-fp-cta__kicker{display:inline-block;font-family:var(--f-label);font-size:var(--sz-ls);font-weight:500;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-light);margin-bottom:20px;}
.hireai-fp-cta__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#fff;margin:0 0 20px;}
.hireai-fp-cta__desc{font-family:var(--f-body);font-size:var(--sz-bl);line-height:1.7;color:rgba(255,255,255,.7);margin:0 auto 40px;max-width:640px;}
.hireai-fp-cta__actions{display:inline-flex;flex-wrap:wrap;justify-content:center;gap:16px;}
.hireai-fp-cta__actions .hireai-fp__btn--outline{color:#fff;border-color:rgba(255,255,255,.35);background:rgba(255,255,255,.06);}
.hireai-fp-cta__actions .hireai-fp__btn--outline:hover{background:rgba(255,255,255,.14);}

/* ═══════════════════════════════════════════════════════════════════════
   Responsive fine-tune
   ═══════════════════════════════════════════════════════════════════════ */
@media(max-width:767px){
    .hireai-fp-hero{height:auto;min-height:calc(100vh - 64px);min-height:calc(100svh - 64px);display:flex;align-items:flex-end;}
    .hireai-fp-hero__bg{position:absolute;inset:0;width:100%;height:100%;}
    .hireai-fp-hero__bg img{width:100%;height:100%;object-fit:cover;object-position:center 28%;}
    .hireai-fp-hero__shade{background:linear-gradient(180deg,rgba(23,25,27,0) 0%,rgba(23,25,27,.10) 52%,rgba(23,25,27,.62) 78%,rgba(23,25,27,.88) 100%);}
    .hireai-fp-hero__inner,
    html body .hireai-fp .hireai-fp-hero .hireai-fp-hero__inner{position:relative;inset:auto;width:100%;min-width:0;padding:76px 20px 76px;}
    .hireai-fp-hero__kicker{margin-bottom:16px;}
    .hireai-fp-hero__title{font-size:clamp(32px,8vw,48px);}
    .hireai-fp-hero__title .hireai-fp__gold{background:linear-gradient(135deg,#f4dcac 0%,#e9c176 42%,#a9792b 100%);-webkit-background-clip:text;background-clip:text;}
    .hireai-fp-hero__subtitle{max-width:100%;margin-bottom:24px;color:rgba(255,255,255,.88);text-shadow:0 2px 12px rgba(26,28,28,.34);}
    .hireai-fp-hero__title{color:#fff;text-shadow:0 2px 16px rgba(26,28,28,.30);}
    .hireai-fp-hero__kicker{color:#e9c176;text-shadow:0 1px 8px rgba(26,28,28,.36);}
    .hireai-fp-hero__actions{gap:12px;}
    .hireai-fp-hero__actions .hireai-fp__btn{min-height:48px;padding:14px 22px;font-size:13px;}
    .hireai-fp-hero__actions .hireai-fp__btn--outline{background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.44);color:#fff!important;}
    .hireai-fp-hero__scroll{bottom:18px;color:#fff;}
    .hireai-fp-products__head,.hireai-fp-solutions__head,.hireai-fp-cases__head{flex-direction:column;align-items:flex-start;}
    .hireai-fp-products__nav,.hireai-fp-cases__nav{display:none;}
    .hireai-fp-faq__list{padding-inline:0;}
    .hireai-fp-cta__actions{flex-direction:column;align-items:stretch;}
    .hireai-fp-cta__actions .hireai-fp__btn{width:100%;}
}
</style>

<!-- ═══════════════════════════════════════════════════════════════════════
     1) HERO  ── 满屏 / 背景图 / 渐变遮罩 / 双按钮 / 向下滚动指示
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-hero" aria-label="<?php echo esc_attr($is_en ? 'Hero' : '首页主视觉'); ?>">
    <div class="hireai-fp-hero__bg" aria-hidden="true">
        <?php if ($hero_image) : ?>
        <img
            src="<?php echo esc_url($hero_image); ?>"
            alt=""
            width="1920"
            height="1080"
            sizes="100vw"
            loading="eager"
            fetchpriority="high"
            decoding="async">
        <?php endif; ?>
    </div>
    <div class="hireai-fp-hero__shade" aria-hidden="true"></div>
    <div class="hireai-fp-hero__inner">
        <?php if ($hero_kicker) : ?>
        <p class="hireai-fp__kicker hireai-fp-hero__kicker"><?php echo esc_html($hero_kicker); ?></p>
        <?php endif; ?>
        <h1 class="hireai-fp-hero__title">
            <span class="hireai-fp-hero__title-line"><?php echo esc_html($hero_static); ?></span>
            <?php if ('' !== $hero_accent_primary) : ?>
            <span class="hireai-fp-hero__title-line hireai-fp__gold"><?php echo esc_html($hero_accent_primary); ?></span>
            <?php endif; ?>
            <?php if ('' !== $hero_accent_secondary) : ?>
            <span class="hireai-fp-hero__title-line hireai-fp__gold"><?php echo esc_html($hero_accent_secondary); ?></span>
            <?php endif; ?>
        </h1>
        <?php if ($hero_subtitle) : ?>
        <p class="hireai-fp-hero__subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        <?php endif; ?>
        <div class="hireai-fp-hero__actions">
            <a class="hireai-fp__btn hireai-fp__btn--primary" href="<?php echo esc_url($hero_cta_1_url); ?>">
                <?php echo esc_html($hero_cta_1_title); ?>
            </a>
            <a class="hireai-fp__btn hireai-fp__btn--outline" href="<?php echo esc_url($hero_cta_2_url); ?>">
                <?php echo esc_html($hero_cta_2_title); ?>
            </a>
        </div>
    </div>
    <a class="hireai-fp-hero__scroll" href="#hireai-fp-intro" aria-label="<?php echo esc_attr($t['scroll']); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
    </a>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     2) INTRO  ── 居中：kicker + 大标题 + 描述 + ghost CTA
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-intro" id="hireai-fp-intro" aria-labelledby="hireai-fp-intro-title">
    <div class="hireai-fp-intro__inner">
        <?php if ($intro_kicker) : ?>
        <span class="hireai-fp__kicker"><?php echo esc_html($intro_kicker); ?></span>
        <?php endif; ?>
        <h2 class="hireai-fp-intro__title" id="hireai-fp-intro-title"><?php echo esc_html($intro_title); ?></h2>
        <?php if ($intro_desc) : ?>
        <p class="hireai-fp-intro__desc"><?php echo esc_html($intro_desc); ?></p>
        <?php endif; ?>
        <?php if ($intro_cta_label) : ?>
        <div class="hireai-fp-intro__cta">
            <a class="hireai-fp__btn hireai-fp__btn--ghost" href="<?php echo esc_url($intro_cta_url); ?>">
                <?php echo esc_html($intro_cta_label); ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     3) PRODUCTS  ── Stitch AI 数字员工 (3-col / aspect 3:4 / 徽章 / outline 按钮)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-products" aria-labelledby="hireai-fp-products-title">
    <div class="hireai-fp-products__inner">
        <div class="hireai-fp-products__head">
            <div class="hireai-fp-products__head-text">
                <?php if ($prod_section_kicker) : ?>
                <span class="hireai-fp__kicker"><?php echo esc_html($prod_section_kicker); ?></span>
                <?php endif; ?>
                <h2 class="hireai-fp-products__title" id="hireai-fp-products-title"><?php echo esc_html($prod_section_title); ?></h2>
                <?php if ($prod_section_sub) : ?>
                <p class="hireai-fp-products__sub"><?php echo esc_html($prod_section_sub); ?></p>
                <?php endif; ?>
                <?php if ($prod_explore_label) : ?>
                <a class="hireai-fp-products__explore" href="<?php echo esc_url($prod_explore_url); ?>">
                    <?php echo esc_html($prod_explore_label); ?>
                </a>
                <?php endif; ?>
            </div>
            <div class="hireai-fp-products__nav" aria-hidden="true">
                <button type="button" aria-label="<?php echo esc_attr($t['prev']); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button type="button" aria-label="<?php echo esc_attr($t['next']); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
        <div class="hireai-fp-products__grid">
            <?php foreach ($products as $p) : if (empty($p['title'])) continue; ?>
            <article class="hireai-fp-product">
                <div class="hireai-fp-product__media">
                    <?php if (!empty($p['img'])) : ?>
                    <img class="hireai-fp-product__img" src="<?php echo esc_url($p['img']); ?>" alt="<?php echo esc_attr($p['title']); ?>" loading="lazy" decoding="async">
                    <?php endif; ?>
                    <?php if (!empty($p['badge'])) : ?>
                    <span class="hireai-fp-product__badge"><?php echo esc_html($p['badge']); ?></span>
                    <?php endif; ?>
                </div>
                <h3 class="hireai-fp-product__name"><?php echo esc_html($p['title']); ?></h3>
                <p class="hireai-fp-product__desc"><?php echo esc_html($p['desc']); ?></p>
                <a class="hireai-fp__btn hireai-fp__btn--outline hireai-fp-product__btn" href="<?php echo esc_url($p['url']); ?>">
                    <?php echo esc_html($p['btn']); ?>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     4) SOLUTIONS  ── Stitch AI 解决方案 (2x2 aspect-video 卡片 + primary CTA)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-solutions" aria-labelledby="hireai-fp-solutions-title">
    <div class="hireai-fp-solutions__inner">
        <div class="hireai-fp-solutions__head">
            <div class="hireai-fp-solutions__head-text">
                <?php if ($sol_section_kicker) : ?>
                <span class="hireai-fp__kicker"><?php echo esc_html($sol_section_kicker); ?></span>
                <?php endif; ?>
                <h2 class="hireai-fp-solutions__title" id="hireai-fp-solutions-title"><?php echo esc_html($sol_section_title); ?></h2>
                <?php if ($sol_section_sub) : ?>
                <p class="hireai-fp-solutions__sub"><?php echo esc_html($sol_section_sub); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="hireai-fp-solutions__grid">
            <?php foreach ($solutions as $sol) : if (empty($sol['title'])) continue; ?>
            <a class="hireai-fp-sol" href="<?php echo esc_url($sol['url']); ?>">
                <?php if (!empty($sol['img'])) : ?>
                <img class="hireai-fp-sol__img" src="<?php echo esc_url($sol['img']); ?>" alt="<?php echo esc_attr($sol['title']); ?>" loading="lazy" decoding="async">
                <?php else : ?>
                <div class="hireai-fp-sol__placeholder" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 5.8a2 2 0 0 0 1.3 1.3L21 12l-5.8 1.9a2 2 0 0 0-1.3 1.3L12 21l-1.9-5.8a2 2 0 0 0-1.3-1.3L3 12l5.8-1.9a2 2 0 0 0 1.3-1.3L12 3z"/></svg>
                </div>
                <?php endif; ?>
                <div class="hireai-fp-sol__overlay">
                    <span class="hireai-fp-sol__tag"><?php echo esc_html($sol['tag']); ?></span>
                    <h3 class="hireai-fp-sol__title"><?php echo esc_html($sol['title']); ?></h3>
                    <p class="hireai-fp-sol__desc"><?php echo esc_html($sol['desc']); ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="hireai-fp-solutions__cta">
            <a class="hireai-fp__btn hireai-fp__btn--primary" href="<?php echo esc_url($sol_explore_url); ?>">
                <?php echo esc_html($sol_explore_label); ?>
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     5) CASES  ── Stitch 案例 & 洞察 (12-col 网格 8+4)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-cases" aria-labelledby="hireai-fp-cases-title">
    <div class="hireai-fp-cases__inner">
        <div class="hireai-fp-cases__head">
            <div class="hireai-fp-cases__head-text">
                <?php $cases_kicker = hireai_field('home_cases_heading_kicker', ''); if ($cases_kicker) : ?>
                <span class="hireai-fp__kicker"><?php echo esc_html($cases_kicker); ?></span>
                <?php endif; ?>
                <?php $cases_title = hireai_field('home_cases_heading_title', ''); ?>
                <h2 class="hireai-fp-cases__title" id="hireai-fp-cases-title"><?php echo esc_html($cases_title); ?></h2>
                <?php $cases_sub = hireai_field('home_cases_heading_subtitle', ''); if ($cases_sub) : ?>
                <p class="hireai-fp-cases__sub"><?php echo esc_html($cases_sub); ?></p>
                <?php endif; ?>
            </div>
            <div class="hireai-fp-cases__nav" aria-hidden="true">
                <button type="button" aria-label="<?php echo esc_attr($t['prev']); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button type="button" aria-label="<?php echo esc_attr($t['next']); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
        <div class="hireai-fp-cases__grid">
            <a class="hireai-fp-case-major" href="<?php echo esc_url($cases_major['url']); ?>">
                <div class="hireai-fp-case-major__media">
                    <?php if (!empty($cases_major['img'])) : ?>
                    <img class="hireai-fp-case-major__img" src="<?php echo esc_url($cases_major['img']); ?>" alt="<?php echo esc_attr($cases_major['title']); ?>" loading="lazy" decoding="async">
                    <?php endif; ?>
                </div>
                <div class="hireai-fp-case-major__body">
                    <span class="hireai-fp-case-major__tag"><?php echo esc_html($cases_major['label']); ?></span>
                    <h3 class="hireai-fp-case-major__title"><?php echo esc_html($cases_major['title']); ?></h3>
                    <p class="hireai-fp-case-major__desc"><?php echo esc_html($cases_major['desc']); ?></p>
                </div>
            </a>
            <div class="hireai-fp-cases__side">
                <?php foreach ($side_cases as $sc) : if (empty($sc['title'])) continue; ?>
                <a class="hireai-fp-case-side" href="<?php echo esc_url($sc['url']); ?>">
                    <div class="hireai-fp-case-side__media">
                        <?php if (!empty($sc['img'])) : ?>
                        <img class="hireai-fp-case-side__img" src="<?php echo esc_url($sc['img']); ?>" alt="<?php echo esc_attr($sc['title']); ?>" loading="lazy" decoding="async">
                        <?php endif; ?>
                    </div>
                    <span class="hireai-fp-case-side__tag"><?php echo esc_html($sc['tag']); ?></span>
                    <h4 class="hireai-fp-case-side__title"><?php echo esc_html($sc['title']); ?></h4>
                    <p class="hireai-fp-case-side__desc"><?php echo esc_html($sc['desc']); ?></p>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="hireai-fp-cases__cta">
            <a class="hireai-fp__btn hireai-fp__btn--primary" href="<?php echo esc_url(hireai_field('home_cases_cta_url', home_url('/cases-insights/'))); ?>">
                <?php echo esc_html(hireai_field('home_cases_cta_label', '')); ?>
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     6) FAQ  ── Stitch 常见问题 (3 项手风琴 + ghost CTA)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-faq" id="hireai-fp-faq" aria-labelledby="hireai-fp-faq-title">
    <h2 class="hireai-fp-faq__title" id="hireai-fp-faq-title"><?php echo esc_html($faq_kicker); ?></h2>
    <?php if ($faq_title) : ?>
    <p class="hireai-fp-faq__sub"><?php echo esc_html($faq_title); ?></p>
    <?php endif; ?>
    <div class="hireai-fp-faq__list">
        <?php foreach ($faq_items as $item) : if (empty($item['q'])) continue; ?>
        <div class="hireai-fp-faq-item">
            <div class="hireai-fp-faq-item__header"
                 role="button" tabindex="0"
                 aria-expanded="false"
                 onclick="var n=this.closest('.hireai-fp-faq-item');var o=n.classList.toggle('is-open');this.setAttribute('aria-expanded',o?'true':'false');"
                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}">
                <h3 class="hireai-fp-faq-item__q"><?php echo esc_html($item['q']); ?></h3>
                <svg class="hireai-fp-faq-item__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </div>
            <div class="hireai-fp-faq-item__body" role="region">
                <div class="hireai-fp-faq-item__answer">
                    <p><?php echo esc_html($item['a']); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="hireai-fp-faq__cta">
        <a class="hireai-fp__btn hireai-fp__btn--ghost" href="<?php echo esc_url($faq_explore_url); ?>">
            <?php echo esc_html($faq_explore_label); ?>
        </a>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     7) CTA BAND  ── Stitch 大 CTA (深色 + 金色 radial glow + 双按钮)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-cta" aria-labelledby="hireai-fp-cta-title">
    <div class="hireai-fp-cta__glow" aria-hidden="true"></div>
    <div class="hireai-fp-cta__inner">
        <?php if ($cta_kicker) : ?>
        <span class="hireai-fp-cta__kicker"><?php echo esc_html($cta_kicker); ?></span>
        <?php endif; ?>
        <h2 class="hireai-fp-cta__title" id="hireai-fp-cta-title"><?php echo esc_html($cta_title); ?></h2>
        <?php if ($cta_desc) : ?>
        <p class="hireai-fp-cta__desc"><?php echo esc_html($cta_desc); ?></p>
        <?php endif; ?>
        <div class="hireai-fp-cta__actions">
            <a class="hireai-fp__btn hireai-fp__btn--gold" href="<?php echo esc_url($cta_btn_url); ?>">
                <?php echo esc_html($cta_btn_title); ?>
            </a>
            <?php if ($cta_btn_2_title) : ?>
            <a class="hireai-fp__btn hireai-fp__btn--outline" href="<?php echo esc_url($cta_btn_2_url); ?>">
                <?php echo esc_html($cta_btn_2_title); ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
