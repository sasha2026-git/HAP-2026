<?php
/**
 * Template Name: 聘AI - 案例 & 洞察（Atelier v3）
 *
 * Aurelian luxury system: gold #775a19 / #e9c176, Playfair Display + Inter.
 *
 * Structure (from Stitch design 案例洞察/code.html):
 *   1. Hero (centered, gold-leaf accent, italic tagline)
 *   2. 案例 studies grid (12-col stagger: wide / tall-tall / wide)
 *   3. 洞察 / Insights 3-column cards (4:3 image, date, title, read-more)
 *   4. Bottom CTA banner ("Ready to Redefine Humanity?")
 *
 * Data sources:
 *   - Hero / sections / cards: ACF group_page_cases_insights
 *     (hero_kicker, hero_title, hero_subtitle,
 *      cases_kicker, cases_title, cases_subtitle, cases_cta_url, cases_cta_title,
 *      insights_kicker, insights_title, insights_subtitle, insights_cta_url, insights_cta_title,
 *      card_cta_text)
 *   - Case items & insight items: hard-coded fallback data (no ACF repeater
 *     registered in functions.php), so the design copy is preserved verbatim.
 *
 * @version 3.0.0
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$suffix  = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '';
$is_en   = ($suffix === '_en');
$page_id = get_the_ID();

/* --------------------------------------------------------------------
 * 1. ACF text fields (single, language-aware via hireai_field)
 * -------------------------------------------------------------------- */
$hero_kicker       = hireai_field('hero_kicker',
    $is_en ? 'CASES & INSIGHTS' : '案例与洞察', $page_id);

$hero_title        = hireai_field('hero_title',
    $is_en ? 'Cases & Insights' : '案例与洞察', $page_id);

$hero_subtitle     = hireai_field('hero_subtitle',
    $is_en
        ? '"AI-led process, Human-delivered results."'
        : '「AI 主导流程，匠心交付成果。」', $page_id);

$cases_kicker      = hireai_field('cases_kicker',
    $is_en ? 'CASES' : '案例', $page_id);

$cases_title       = hireai_field('cases_title',
    $is_en ? 'Selected Cases' : '精选案例', $page_id);

$cases_subtitle    = hireai_field('cases_subtitle',
    $is_en
        ? 'How real clients grow with digital employees.'
        : '真实客户如何借助数字员工实现增长。', $page_id);

$cases_cta_url     = hireai_field('cases_cta_url',
    $is_en ? '/category/cases/' : '/category/cases/', $page_id);

$cases_cta_title   = hireai_field('cases_cta_title',
    $is_en ? 'All Cases' : '查看全部案例', $page_id);

$insights_kicker   = hireai_field('insights_kicker',
    $is_en ? 'INSIGHTS' : '洞察', $page_id);

$insights_title    = hireai_field('insights_title',
    $is_en ? 'Frontier Insights' : '前沿洞察', $page_id);

$insights_subtitle = hireai_field('insights_subtitle',
    $is_en
        ? 'Deep thinking on AI and the digital workforce.'
        : '关于 AI 行业与数字员工的深度思考。', $page_id);

$insights_cta_url  = hireai_field('insights_cta_url',
    $is_en ? '/category/insights/' : '/category/insights/', $page_id);

$insights_cta_title = hireai_field('insights_cta_title',
    $is_en ? 'More Insights' : '更多洞察', $page_id);

$card_cta_text     = hireai_field('card_cta_text',
    $is_en ? 'Read More' : '阅读全文', $page_id);

/* --------------------------------------------------------------------
 * 2. Case-study cards (hard-coded fallback, mirrors Stitch design)
 *    Fields available per card: kicker, title, desc, image
 * -------------------------------------------------------------------- */
$case_defaults = [
    'defaults/case-1.jpg',
    'defaults/case-2.jpg',
    'defaults/case-3.jpg',
    'defaults/case-4.jpg',
    'defaults/case-5.jpg',
    'defaults/case-6.jpg',
];

$cases = [
    [
        'span'    => 12,
        'aspect'  => '21 / 9',
        'kicker_zh' => 'Brand Protection',
        'kicker_en' => 'Brand Protection',
        'title_zh'  => '公关审计与品牌重塑',
        'title_en'  => 'Crisis Counsel & Brand Reinvention',
        'desc_zh'   => '专业AI驱动的公关洞察与品牌保护，通过实时监测与情感分析，精准守护品牌声誉并在全球市场中重新定义叙事。',
        'desc_en'   => 'AI-driven reputation and crisis counsel: real-time monitoring and sentiment analysis protecting your brand narrative across global markets.',
        'image'  => $case_defaults[0],
    ],
    [
        'span'    => 6,
        'aspect'  => '4 / 5',
        'kicker_zh' => 'Strategic Alliances',
        'kicker_en' => 'Strategic Alliances',
        'title_zh'  => '跨界超级IP协作',
        'title_en'  => 'Bespoke IP Collaborations',
        'desc_zh'   => '跨界赋能，连接全球顶尖艺术IP，打造具有收藏价值的数字孪生艺术品与品牌资产。',
        'desc_en'   => 'Connecting heritage and digital — co-authoring collectable digital twins and brand assets with the world\u2019s most coveted IPs.',
        'image'  => $case_defaults[1],
    ],
    [
        'span'    => 6,
        'aspect'  => '4 / 5',
        'kicker_zh' => 'Digital Retail',
        'kicker_en' => 'Digital Retail',
        'title_zh'  => '奢品电商视觉体系',
        'title_en'  => 'Luxury E-Commerce Visuals',
        'desc_zh'   => '全场景AI电商视觉解决方案，全方位提升转化率与品牌格调。',
        'desc_en'   => 'End-to-end AI visual systems for luxury commerce — elevating both conversion and brand gravitas.',
        'image'  => $case_defaults[2],
    ],
    [
        'span'    => 12,
        'aspect'  => '21 / 9',
        'kicker_zh' => 'Visual Masterpieces',
        'kicker_en' => 'Visual Masterpieces',
        'title_zh'  => 'AI 艺术先锋影像',
        'title_en'  => 'AI Fine-Art Imageworks',
        'desc_zh'   => '重新定义视觉美学，开启数字感官盛宴，引领高端艺术审美新趋势。',
        'desc_en'   => 'Re-defining the visual canon — opening digital sensorial feasts that lead luxury aesthetic trends.',
        'image'  => $case_defaults[3],
    ],
];

/* --------------------------------------------------------------------
 * 3. Insight cards (hard-coded fallback, mirrors Stitch design)
 * -------------------------------------------------------------------- */
$insights = [
    [
        'date_zh' => '2024年10月15日',
        'date_en' => 'October 15, 2024',
        'title_zh' => '生成式AI如何重塑高端美妆行业的数字化未来',
        'title_en' => 'How generative AI rewires the digital future of luxury beauty',
        'image'  => $case_defaults[4],
    ],
    [
        'date_zh' => '2024年9月28日',
        'date_en' => 'September 28, 2024',
        'title_zh' => '解析数字人代言：奢华品牌的新世代公关策略',
        'title_en' => 'Decoding digital-human endorsement: the next-gen PR play for luxury houses',
        'image'  => $case_defaults[5],
    ],
    [
        'date_zh' => '2024年9月10日',
        'date_en' => 'September 10, 2024',
        'title_zh' => '超越物理极限：用AI构建旗舰级沉浸式电商空间',
        'title_en' => 'Beyond physical limits: AI-built flagship immersive commerce spaces',
        'image'  => $case_defaults[0],
    ],
];

/* --------------------------------------------------------------------
 * 4. Final CTA copy (no ACF group registered; preserve design copy)
 * -------------------------------------------------------------------- */
$final_heading = $is_en
    ? 'Ready to Redefine Humanity?'
    : '准备好重新定义人性了吗？';
$final_sub     = $is_en
    ? "Join the exclusive echelon of leaders leveraging Aurelian AI\u2019s bespoke ecosystem."
    : '加入运用 Aurelian AI 专属生态的领袖精英之列，开启属于您的篇章。';
$final_primary = $is_en ? 'Start The Journey' : '开启旅程';
$final_ghost   = $is_en ? 'Download Brand Book' : '下载品牌手册';
$final_url     = $is_en ? '/contact/' : '/contact/';
$final_ghost_url = $is_en ? '/contact/' : '/contact/';

/* ====== v3.0.3 — Cases & Insights <-> 文章系统打通 ====== */
$wp_cases = get_posts([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'numberposts'    => 6,
    'tax_query'      => [[
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => ['cases', 'case'],
        'operator' => 'IN',
    ]],
    'orderby' => 'date',
    'order'   => 'DESC',
]);
if (!empty($wp_cases)) {
    $cases = [];
    $chunks = array_chunk($wp_cases, 4);
    $i = 0;
    foreach ($chunks as $ci => $chunk) {
        $is_wide = (0 === $ci % 2);
        foreach ($chunk as $idx => $case) {
            $cats = get_the_category($case->ID);
            $cat_name = !empty($cats) ? $cats[0]->name : '';
            $cases[] = [
                'span'      => ($is_wide && 0 === $idx) ? 12 : 6,
                'aspect'    => ($is_wide && 0 === $idx) ? '21 / 9' : '4 / 5',
                'kicker_zh' => $cat_name,
                'kicker_en' => $cat_name,
                'title_zh'  => get_the_title($case->ID),
                'title_en'  => get_the_title($case->ID),
                'desc_zh'   => wp_strip_all_tags($case->post_excerpt ?: wp_trim_words(strip_tags($case->post_content), 30, '…')),
                'desc_en'   => wp_strip_all_tags($case->post_excerpt ?: wp_trim_words(strip_tags($case->post_content), 30, '…')),
                'image'     => get_the_post_thumbnail_url($case->ID, 'large') ?: ($case_defaults[$i % count($case_defaults)] ?? $case_defaults[0]),
                'link'      => get_permalink($case->ID),
            ];
            $i++;
        }
    }
}

$wp_insights = get_posts([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'numberposts'    => 3,
    'tax_query'      => [[
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => ['insights', 'insight'],
        'operator' => 'IN',
    ]],
    'orderby' => 'date',
    'order'   => 'DESC',
]);
if (!empty($wp_insights)) {
    $insights = [];
    foreach ($wp_insights as $idx => $ins) {
        $ts = strtotime($ins->post_date);
        $insights[] = [
            'date_zh' => date('Y年n月j日', $ts),
            'date_en' => date('F j, Y', $ts),
            'title_zh'=> get_the_title($ins->ID),
            'title_en'=> get_the_title($ins->ID),
            'image'   => get_the_post_thumbnail_url($ins->ID, 'medium') ?: ($case_defaults[$idx % count($case_defaults)] ?? $case_defaults[0]),
            'link'    => get_permalink($ins->ID),
        ];
    }
}

?>
<style>
/* =====================================================================
   案例 & 洞察页 — 页面专有样式（仅本模板生效）
   ===================================================================== */

/* ---------- Hero：金色渐变标题 + 装饰竖线 ---------- */
.ci-hero__title {
    background: linear-gradient(to right, #775a19, #e9c176, #775a19);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    margin-bottom: 24px;
}
.ci-hero__divider {
    width: 1px;
    height: 96px;
    margin: 40px auto 0;
    background: linear-gradient(to bottom, var(--lb-secondary, #775a19), transparent);
}

/* ---------- 通用 section 头 ---------- */
.ci-section {
    display: flex;
    flex-direction: column;
    min-width: 0;
    gap: clamp(28px, 4vw, 48px);
}
.ci-section__head {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
}
.ci-section__kicker {
    font-family: var(--font-label, 'Inter', sans-serif);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: var(--gold-leaf, #775a19);
}
.ci-section__title {
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-size: clamp(28px, 4vw, 40px);
    font-weight: 600;
    line-height: 1.2;
    color: var(--primary, #1a1c1c);
    margin: 0;
}
.ci-section__sub {
    margin: 0;
    max-width: 640px;
    font-family: var(--font-body, 'Inter', sans-serif);
    font-size: clamp(15px, 1.5vw, 17px);
    color: var(--on-surface-variant, #444748);
    line-height: 1.6;
}
.ci-section__rule {
    width: 1px;
    height: 56px;
    background: linear-gradient(to bottom, var(--gold-leaf, #775a19), transparent);
    margin: 8px auto 0;
}

/* ---------- 案例 12 列网格 ---------- */
.ci-cases-grid {
    display: grid;
    grid-template-columns: repeat(12, minmax(0, 1fr));
    gap: clamp(24px, 3vw, 40px);
}
.ci-case {
    grid-column: span 12;
    display: flex;
    flex-direction: column;
    gap: 24px;
    min-width: 0;
}
@media (min-width: 769px) {
    .ci-case[data-span="6"]  { grid-column: span 6;  }
    .ci-case[data-span="12"] { grid-column: span 12; }
}
.ci-case__media {
    position: relative;
    overflow: hidden;
    border-radius: var(--radius-lg, 0.75rem);
    border: 1px solid rgba(196, 199, 199, 0.35);
    background: var(--surface-container, #eeeeee);
    cursor: pointer;
}
.ci-case__media::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(26, 26, 26, 0.10);
    transition: background 0.7s ease;
    z-index: 2;
    pointer-events: none;
}
.ci-case:hover .ci-case__media::after { background: transparent; }
.ci-case__media img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scale(1);
    transition: transform 1s cubic-bezier(0.16, 1, 0.3, 1);
}
.ci-case:hover .ci-case__media img { transform: scale(1.05); }
.ci-case[data-aspect="21 / 9"] .ci-case__media { aspect-ratio: 21 / 9; }
.ci-case[data-aspect="4 / 5"]  .ci-case__media { aspect-ratio: 4 / 5;  }

.ci-case__body {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 0 4px;
}
.ci-case__kicker {
    font-family: var(--font-label, 'Inter', sans-serif);
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold-leaf, #775a19);
}
.ci-case__title {
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-size: clamp(22px, 2.2vw, 28px);
    font-weight: 500;
    line-height: 1.25;
    color: var(--primary, #1a1c1c);
    margin: 0;
    transition: color 0.3s ease;
}
.ci-case:hover .ci-case__title { color: var(--gold-leaf, #775a19); }
.ci-case__desc {
    margin: 0;
    font-family: var(--font-body, 'Inter', sans-serif);
    font-size: 15px;
    line-height: 1.6;
    color: var(--on-surface-variant, #444748);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ---------- 案例分页 / 关闭 CTA ---------- */
.ci-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 18px;
    padding: 16px 0 0;
}
.ci-pagination__btn {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--outline-variant, #e2e2e2);
    border-radius: 50%;
    color: var(--on-surface-variant, #444748);
    background: transparent;
    cursor: pointer;
    transition: all 0.3s ease;
}
.ci-pagination__btn:hover {
    border-color: var(--gold-leaf, #775a19);
    color: var(--gold-leaf, #775a19);
    box-shadow: 0 0 18px rgba(119, 90, 25, 0.18);
}
.ci-pagination__btn svg { width: 14px; height: 14px; }
.ci-pagination__count {
    font-family: var(--font-label, 'Inter', sans-serif);
    font-size: 13px;
    font-weight: 600;
    color: var(--primary, #1a1c1c);
    letter-spacing: 0.12em;
}

/* ---------- 洞察 3 列卡片 ---------- */
.ci-insights-grid {
    display: grid;
    grid-template-columns: repeat(1, minmax(0, 1fr));
    gap: clamp(28px, 4vw, 48px);
}
@media (min-width: 720px) {
    .ci-insights-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (min-width: 1080px) {
    .ci-insights-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
.ci-insight {
    display: flex;
    flex-direction: column;
    gap: 20px;
    min-width: 0;
}
.ci-insight__media {
    position: relative;
    overflow: hidden;
    border-radius: var(--radius-lg, 0.75rem);
    aspect-ratio: 4 / 3;
    background: var(--surface-container, #eeeeee);
    border: 1px solid rgba(196, 199, 199, 0.35);
    cursor: pointer;
}
.ci-insight__media::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(26, 26, 26, 0.08);
    transition: background 0.7s ease;
    z-index: 2;
    pointer-events: none;
}
.ci-insight:hover .ci-insight__media::after { background: transparent; }
.ci-insight__media img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scale(1);
    transition: transform 1s cubic-bezier(0.16, 1, 0.3, 1);
}
.ci-insight:hover .ci-insight__media img { transform: scale(1.05); }

.ci-insight__body {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 0 4px;
}
.ci-insight__date {
    font-family: var(--font-label, 'Inter', sans-serif);
    font-size: 11px;
    font-weight: 600;
    color: var(--on-surface-variant, #444748);
    letter-spacing: 0.12em;
    text-transform: uppercase;
}
.ci-insight__title {
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-size: clamp(18px, 1.6vw, 22px);
    font-weight: 500;
    line-height: 1.3;
    color: var(--primary, #1a1c1c);
    margin: 0;
    transition: color 0.3s ease;
}
.ci-insight:hover .ci-insight__title { color: var(--gold-leaf, #775a19); }

.ci-insight__cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    font-family: var(--font-label, 'Inter', sans-serif);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--primary, #1a1c1c);
    text-decoration: none;
    border-bottom: 1px solid rgba(26, 26, 26, 0.2);
    padding-bottom: 4px;
    transition: all 0.3s ease;
    width: fit-content;
}
.ci-insight__cta:hover {
    color: var(--gold-leaf, #775a19);
    border-bottom-color: var(--gold-leaf, #775a19);
}
.ci-insight__cta svg { width: 12px; height: 12px; }

/* ---------- Section 收尾 CTA（"查看全部案例" / "更多洞察"） ---------- */
.ci-section__cta {
    display: flex;
    justify-content: center;
    padding-top: 16px;
}

/* ---------- 滚动揭示动画 ---------- */
.ci-reveal {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}
.ci-reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}
</style>

<div class="lb-main">
<div class="lb-container">

    <!-- ═══════════════════════════════════════════════════════════
         1. Hero
         ═══════════════════════════════════════════════════════════ -->
    <section class="lb-hero ci-hero" aria-labelledby="ci-hero-title-heading">
        <span class="lb-hero__kicker"><?php echo esc_html($hero_kicker); ?></span>
        <h1 class="lb-hero__title ci-hero__title" id="ci-hero-title-heading">
            <?php echo esc_html($hero_title); ?>
        </h1>
        <p class="lb-hero__subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        <div class="ci-hero__divider" aria-hidden="true"></div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════
         2. 案例 studies (12-col stagger grid)
         ═══════════════════════════════════════════════════════════ -->
    <section class="ci-section ci-cases" aria-labelledby="ci-cases-title-heading">
        <header class="ci-section__head">
            <h2 class="ci-section__title" id="ci-cases-title-heading">
                <?php echo esc_html($cases_title); ?>
            </h2>
            <?php if ($cases_subtitle !== '') : ?>
                <p class="ci-section__sub"><?php echo esc_html($cases_subtitle); ?></p>
            <?php endif; ?>
            <div class="ci-section__rule" aria-hidden="true"></div>
        </header>

        <div class="ci-cases-grid">
            <?php foreach ($cases as $case) :
                $img_url   = hireai_default_image($case['image']);
                $title     = $is_en ? $case['title_en'] : $case['title_zh'];
                $kicker    = $is_en ? $case['kicker_en'] : $case['kicker_zh'];
                $desc      = $is_en ? $case['desc_en']   : $case['desc_zh'];
                $span      = (int) $case['span'];
                $aspect    = $case['aspect'];
                $case_link = isset($case['link']) ? $case['link'] : '#';
                ?>
                <a class="ci-case ci-reveal"
                   href="<?php echo esc_url($case_link); ?>"
                   data-span="<?php echo esc_attr($span); ?>"
                   data-aspect="<?php echo esc_attr($aspect); ?>"
                   aria-label="<?php echo esc_attr($title); ?>">
                    <div class="ci-case__media">
                        <img src="<?php echo esc_url($img_url); ?>"
                             alt="<?php echo esc_attr($title); ?>"
                             loading="lazy" decoding="async">
                    </div>
                    <div class="ci-case__body">
                        <span class="ci-case__kicker"><?php echo esc_html($kicker); ?></span>
                        <h3 class="ci-case__title"><?php echo esc_html($title); ?></h3>
                        <p class="ci-case__desc"><?php echo esc_html($desc); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="ci-pagination" role="group" aria-label="<?php echo esc_attr($is_en ? 'Case pagination' : '案例分页'); ?>">
            <button type="button" class="ci-pagination__btn" aria-label="<?php echo esc_attr($is_en ? 'Previous' : '上一页'); ?>" disabled>
                <?php echo hireai_svg('west', 14, 'ci-pagination__icon'); ?>
            </button>
            <span class="ci-pagination__count">01 / 03</span>
            <button type="button" class="ci-pagination__btn" aria-label="<?php echo esc_attr($is_en ? 'Next' : '下一页'); ?>" disabled>
                <?php echo hireai_svg('east', 14, 'ci-pagination__icon'); ?>
            </button>
        </div>

        <div class="ci-section__cta">
            <a class="lb-btn lb-btn--outline"
               href="<?php echo esc_url($cases_cta_url); ?>">
                <?php echo esc_html($cases_cta_title); ?>
                <?php echo hireai_svg('east', 14, 'lb-btn__icon'); ?>
            </a>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════
         3. 洞察 / Insights (3-column)
         ═══════════════════════════════════════════════════════════ -->
    <section class="ci-section ci-insights" aria-labelledby="ci-insights-title-heading">
        <header class="ci-section__head">
            <span class="ci-section__kicker"><?php echo esc_html($insights_kicker); ?></span>
            <h2 class="ci-section__title" id="ci-insights-title-heading">
                <?php echo esc_html($insights_title); ?>
            </h2>
            <?php if ($insights_subtitle !== '') : ?>
                <p class="ci-section__sub"><?php echo esc_html($insights_subtitle); ?></p>
            <?php endif; ?>
            <div class="ci-section__rule" aria-hidden="true"></div>
        </header>

        <div class="ci-insights-grid">
            <?php foreach ($insights as $insight) :
                $img_url  = hireai_default_image($insight['image']);
                $title    = $is_en ? $insight['title_en'] : $insight['title_zh'];
                $date     = $is_en ? $insight['date_en']  : $insight['date_zh'];
                $delay_ms = isset($insight['_delay']) ? (int) $insight['_delay'] : 0;
                $style_attr = $delay_ms > 0 ? ' style="transition-delay:' . esc_attr($delay_ms) . 'ms;"' : '';
                ?>
                <article class="ci-insight ci-reveal"<?php echo $style_attr; ?>
                         aria-label="<?php echo esc_attr($title); ?>">
                    <div class="ci-insight__media">
                        <img src="<?php echo esc_url($img_url); ?>"
                             alt="<?php echo esc_attr($title); ?>"
                             loading="lazy" decoding="async">
                    </div>
                    <div class="ci-insight__body">
                        <span class="ci-insight__date"><?php echo esc_html($date); ?></span>
                        <h3 class="ci-insight__title"><?php echo esc_html($title); ?></h3>
                        <a class="ci-insight__cta" href="<?php echo esc_url(isset($insight['link']) ? $insight['link'] : $insights_cta_url); ?>">
                            <span><?php echo esc_html($card_cta_text); ?></span>
                            <?php echo hireai_svg('east', 12, 'ci-insight__cta-icon'); ?>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="ci-pagination" role="group" aria-label="<?php echo esc_attr($is_en ? 'Insight pagination' : '洞察分页'); ?>">
            <button type="button" class="ci-pagination__btn" aria-label="<?php echo esc_attr($is_en ? 'Previous' : '上一页'); ?>" disabled>
                <?php echo hireai_svg('west', 14, 'ci-pagination__icon'); ?>
            </button>
            <button type="button" class="ci-pagination__btn" aria-label="<?php echo esc_attr($is_en ? 'Next' : '下一页'); ?>" disabled>
                <?php echo hireai_svg('east', 14, 'ci-pagination__icon'); ?>
            </button>
        </div>

        <div class="ci-section__cta">
            <a class="lb-btn lb-btn--outline"
               href="<?php echo esc_url($insights_cta_url); ?>">
                <?php echo esc_html($insights_cta_title); ?>
                <?php echo hireai_svg('east', 14, 'lb-btn__icon'); ?>
            </a>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════
         4. Bottom CTA banner
         ═══════════════════════════════════════════════════════════ -->
    <section class="lb-cta" aria-labelledby="ci-cta-heading">
        <div class="lb-cta__inner">
            <h2 class="lb-cta__heading" id="ci-cta-heading">
                <?php echo esc_html($final_heading); ?>
            </h2>
            <p class="lb-cta__sub"><?php echo esc_html($final_sub); ?></p>
            <div class="lb-cta__actions">
                <a class="lb-btn lb-btn--primary" href="<?php echo esc_url($final_url); ?>">
                    <?php echo esc_html($final_primary); ?>
                </a>
                <a class="lb-btn lb-btn--ghost" href="<?php echo esc_url($final_ghost_url); ?>">
                    <?php echo esc_html($final_ghost); ?>
                </a>
            </div>
        </div>
    </section>

</div><!-- /.lb-container -->
</div><!-- /.lb-main -->

<script>
(function () {
    'use strict';

    /* Reveal-on-scroll for .ci-reveal */
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        document.querySelectorAll('.ci-reveal').forEach(function (el) { io.observe(el); });
    } else {
        document.querySelectorAll('.ci-reveal').forEach(function (el) { el.classList.add('is-visible'); });
    }
})();
</script>

<?php get_footer(); ?>
