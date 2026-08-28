<?php
/**
 * Template Name: 聘AI - 案例 & 洞察（杂志版 v2.2.6 排版）
 *
 * Aurelian luxury system: gold #775a19 / #e9c176, Playfair Display + Inter.
 *
 * Structure (杂志版 v2.2.6 排版):
 *   1. Hero (centered, gold-leaf accent, italic tagline)
 *   2. 案例 studies grid (12-col stagger: 8 / 4 / 6 / 6)
 *   3. 洞察 / Insights 3-column cards (4:5 image, category, title, read-more)
 *   4. Bottom dark CTA banner ("Ready to define your legacy?")
 *
 * Data sources:
 *   - Hero / sections / cards: ACF group_page_cases_insights
 *   - Case items (4 张): get_posts(['cat' => cases_id], 4) 动态拉取
 *   - Insight items (3 张): get_posts(['cat' => insights_id], 3) 动态拉取
 *
 * @version 3.5.0 杂志版回退
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
 *    中英两套 fallback 与 v2.2.6 静态文案语义一致
 * -------------------------------------------------------------------- */
$hero_kicker       = hireai_field('hero_kicker',
    $is_en ? 'THE ATELIER OF INTELLIGENCE' : '智慧工坊', $page_id);

$hero_title        = hireai_field('hero_title',
    $is_en ? 'Crafting Digital <em>Humanity</em>' : '打造数字 <em>人文</em>', $page_id);

$hero_subtitle     = hireai_field('hero_subtitle',
    $is_en
        ? 'Where technical precision meets heritage aesthetic.'
        : '技术精度与传承美学的交汇之处。', $page_id);

$cases_kicker      = hireai_field('cases_kicker',
    $is_en ? 'CASES' : '案例', $page_id);

$cases_title       = hireai_field('cases_title',
    $is_en ? 'Collaborative Excellence' : '卓越案例', $page_id);

$cases_subtitle    = hireai_field('cases_subtitle',
    $is_en
        ? 'Where technical precision meets heritage aesthetic.'
        : '技术精度与传承美学的交汇之处。', $page_id);

$cases_cta_url     = hireai_field('cases_cta_url',
    $is_en ? '/category/cases/' : '/category/cases/', $page_id);

$cases_cta_title   = hireai_field('cases_cta_title',
    $is_en ? 'All Cases' : '查看全部案例', $page_id);

$insights_kicker   = hireai_field('insights_kicker',
    $is_en ? 'INDUSTRY INSIGHTS & THOUGHT LEADERSHIP' : '行业洞察与思想领导力', $page_id);

$insights_title    = hireai_field('insights_title',
    $is_en ? 'The Intelligence Journal' : '前沿洞察', $page_id);

$insights_subtitle = hireai_field('insights_subtitle',
    $is_en
        ? 'INDUSTRY INSIGHTS & THOUGHT LEADERSHIP'
        : '行业洞察与思想领导力', $page_id);

$insights_cta_url  = hireai_field('insights_cta_url',
    $is_en ? '/category/insights/' : '/category/insights/', $page_id);

$insights_cta_title = hireai_field('insights_cta_title',
    $is_en ? 'More Insights' : '更多洞察', $page_id);

$card_cta_text     = hireai_field('card_cta_text',
    $is_en ? 'Read More' : '阅读全文', $page_id);

/* --------------------------------------------------------------------
 * 2. 案例 cards (4 张) — 动态 get_posts(cases) 拉取最新 4 篇
 *    HTML 排版完全照搬 v2.2.6 杂志版（case-1/2/3/4 + 12-col grid）
 * -------------------------------------------------------------------- */

/* v3.0.8 (Bug D) + v3.0.9: cases category 探测 fallback */
$cases_cat_id = function_exists('hireai_find_category_id')
    ? hireai_find_category_id([
        'cases', 'case', 'casestudy', 'case-studies', 'case-showcase', 'case-collection',
        'work', 'works', 'project', 'projects', 'portfolio',
        '案例', '案例研究', '案例展示', '案例集', '我们的案例', '项目案例',
    ])
    : 0;
$cases_q = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'numberposts'    => 4,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
];
if ($cases_cat_id) {
    $cases_q['cat'] = $cases_cat_id;
} else {
    $cases_q['tax_query'] = [['taxonomy' => 'category', 'field' => 'slug', 'terms' => ['cases', 'case'], 'operator' => 'IN']];
}
$wp_cases = get_posts($cases_q);

/* v2.2.6 杂志版: 4 张 case 的 badge 标签 (装饰性，纯视觉) */
$case_badges = [
    $is_en ? '+42% Retention'       : '+42% 留存提升',
    $is_en ? 'AI Art Integration'   : 'AI 艺术融合',
    $is_en ? '3.4x Conversion'      : '3.4x 转化率',
    $is_en ? 'IP Protection 100%'   : 'IP 保护 100%',
];
$case_badge_pos = ['badge-tr', 'badge-bl', 'badge-tl', 'badge-br'];
$case_default_imgs = [
    'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=1200&h=675&fit=crop',
    'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=800&h=1067&fit=crop',
    'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=1000&h=1000&fit=crop',
    'https://images.unsplash.com/photo-1556761175-4b46a572b786?w=1000&h=1250&fit=crop',
];

$cases_render = [];
if (!empty($wp_cases)) {
    foreach ($wp_cases as $idx => $case) {
        if ($idx >= 4) break;
        $cats = get_the_category($case->ID);
        $cat_name = !empty($cats) ? $cats[0]->name : '';
        $cases_render[] = [
            'kicker'  => $cat_name,
            'title'   => get_the_title($case->ID),
            'desc'    => wp_strip_all_tags($case->post_excerpt ?: wp_trim_words(strip_tags($case->post_content), 30, '…')),
            'image'   => get_the_post_thumbnail_url($case->ID, 'large') ?: $case_default_imgs[$idx],
            'link'    => get_permalink($case->ID),
        ];
    }
} else {
    /* Fallback: 静态 4 张 (语义与 v2.2.6 一致) */
    $cases_render = [
        [
            'kicker' => $is_en ? 'Bespoke' : '高定',
            'title'  => $is_en ? 'Aurelian Prime for Private Banking' : '数字礼宾：高定精品馆',
            'desc'   => $is_en
                ? 'Reimagining wealth management through a hyper-realistic digital concierge.'
                : '为高净值客户打造超写实数字人，引领其在元宇宙私密展厅中探索收藏系列。',
            'image'  => $case_default_imgs[0],
            'link'   => $cases_cta_url,
        ],
        [
            'kicker' => 'IP',
            'title'  => $is_en ? 'Lumina NFT Series' : 'Lumina NFT 系列',
            'desc'   => $is_en
                ? 'Exclusive IP collaboration merging generative algorithms with heritage craft.'
                : '独家 IP 合作，将生成算法与传统工艺融合。',
            'image'  => $case_default_imgs[1],
            'link'   => $cases_cta_url,
        ],
        [
            'kicker' => $is_en ? 'Retail' : '零售',
            'title'  => $is_en ? 'E-commerce Evolution' : '电商进化论',
            'desc'   => $is_en
                ? 'Luxury retail performance scaling through personalized digital twin advisors.'
                : '将浏览转化为沉浸式策展体验。',
            'image'  => $case_default_imgs[2],
            'link'   => $cases_cta_url,
        ],
        [
            'kicker' => $is_en ? 'Brand' : '品牌',
            'title'  => $is_en ? 'The Digital IP Vault' : '数字 IP 金库',
            'desc'   => $is_en
                ? 'Global PR audit and reputation management for AI-integrated luxury estates.'
                : 'AI 集成奢侈房产的全球 PR 审计与声誉管理。',
            'image'  => $case_default_imgs[3],
            'link'   => $cases_cta_url,
        ],
    ];
}

/* --------------------------------------------------------------------
 * 3. 洞察 cards (3 张) — 动态 get_posts(insights) 拉取最新 3 篇
 * -------------------------------------------------------------------- */
$insights_cat_id = function_exists('hireai_find_category_id')
    ? hireai_find_category_id([
        'insights', 'insight', 'industry-insights', 'blog', 'news', 'article', 'articles',
        '洞察', '观点', '行业洞察', '我们的洞察',
    ])
    : 0;
$insights_q = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'numberposts'    => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
];
if ($insights_cat_id) {
    $insights_q['cat'] = $insights_cat_id;
} else {
    $insights_q['tax_query'] = [['taxonomy' => 'category', 'field' => 'slug', 'terms' => ['insights', 'insight'], 'operator' => 'IN']];
}
$wp_insights = get_posts($insights_q);

/* v2.2.6 杂志版: 3 张 insight 的装饰字符 + 估算阅读时间 */
$insight_glyphs = ['✦', '◈', '❖'];
$insight_fallback_titles = [
    [
        'cat'   => 'Aesthetics',
        'title' => $is_en ? 'The Ghost in the Machine: <em>Defining</em> AI Beauty' : '机器中的幽灵：<em>定义</em> AI 之美',
        'desc'  => $is_en ? 'Moving beyond uncanny valley into hyper-stylized digital.' : '为何传统品牌正走向超风格化的数字表达。',
        'time'  => '8 MIN READ',
    ],
    [
        'cat'   => 'Technology',
        'title' => $is_en ? 'Neural Networks & Silk: Future Service' : '神经网络与丝绸：<em>未来</em>服务的织物',
        'desc'  => $is_en ? 'Scaling personalized attention without losing human touch.' : '在不失去专属触感的前提下扩展个性化关怀。',
        'time'  => '12 MIN READ',
    ],
    [
        'cat'   => 'Strategy',
        'title' => $is_en ? 'The New White Glove: AI as Ultimate Concierge' : '新白手套：<em>AI</em> 作为终极礼宾',
        'desc'  => $is_en ? 'Loyalty evolution in automated high-end experiences.' : '审视自动化高端体验时代中忠诚度的演变。',
        'time'  => '6 MIN READ',
    ],
];

$insights_render = [];
if (!empty($wp_insights)) {
    foreach ($wp_insights as $idx => $ins) {
        if ($idx >= 3) break;
        $cats = get_the_category($ins->ID);
        $cat_name = !empty($cats) ? $cats[0]->name : ($insight_fallback_titles[$idx]['cat'] ?? 'Insight');
        $insights_render[] = [
            'glyph'  => $insight_glyphs[$idx] ?? '✦',
            'cat'    => $cat_name,
            'title'  => get_the_title($ins->ID),
            'desc'   => wp_strip_all_tags($ins->post_excerpt ?: wp_trim_words(strip_tags($ins->post_content), 24, '…')),
            'image'  => '',
            'time'   => $insight_fallback_titles[$idx]['time'] ?? '8 MIN READ',
            'link'   => get_permalink($ins->ID),
        ];
    }
} else {
    foreach ($insight_fallback_titles as $idx => $fb) {
        $insights_render[] = [
            'glyph'  => $insight_glyphs[$idx],
            'cat'    => $fb['cat'],
            'title'  => $fb['title'],
            'desc'   => $fb['desc'],
            'image'  => '',
            'time'   => $fb['time'],
            'link'   => $insights_cta_url,
        ];
    }
}

/* --------------------------------------------------------------------
 * 4. 最终 CTA 文案 (v2.2.6 静态: 暗色 + 金色按钮)
 * -------------------------------------------------------------------- */
$consult_title = $is_en
    ? 'Ready to define your legacy?'
    : '准备好定义您的传承了吗？';
$consult_sub   = $is_en
    ? "Join the world's leading brands in the new era of digital human excellence."
    : '加入全球领先的品牌 AI 数字员工计划。迈出第一步。';
$consult_cta   = $is_en ? 'Initiate Consultation' : '立即咨询';

?>
<style>
/* =====================================================================
   案例 & 洞察 — 杂志版 (v2.2.6 排版)
   字体：英文 Playfair Display (标题) + Inter (正文)
        中文通过浏览器 fallback chain 自动落到 Noto Serif SC / Noto Sans SC
   ===================================================================== */

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--gold:#775a19;--gold-l:#e9c176;--txt:#1a1c1c;--txt-v:#444748;--out-v:#c4c7c7;--bg:#faf9f9;--bg-s:#f4f3f3;--dark:#1b1c19}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--txt);-webkit-font-smoothing:antialiased}

.hero{
  display:block !important;
  min-height:auto !important;
  padding:24px 24px 20px !important;
  max-width:1200px !important;
  margin:0 auto !important;
  background:#faf9f9 !important;
  border-radius:0 !important;
  text-align:center !important;
  align-items:unset !important;
  position:relative !important;
}
.hero h1{color:#1a1c1c !important;margin-bottom:12px !important}
.hero p{color:#444748}
.hero span.kicker{
  font-family:'Inter',sans-serif;
  font-size:11px !important;
  font-weight:600 !important;
  letter-spacing:.3em !important;
  text-transform:uppercase !important;
  color:#775a19 !important;
  display:block !important;
  margin-bottom:12px !important;
  text-align:center !important;
  margin-left:auto !important;
  margin-right:auto !important;
}
.hero h1{
  font-family:'Playfair Display',serif;
  font-size:clamp(32px,5vw,56px);
  font-weight:600;
  line-height:1.1;
  margin:0 0 20px;
  background:linear-gradient(135deg,#775a19 0%,#fed488 50%,#775a19 100%);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  background-clip:text;
  font-style:italic;
}
.hero p{
  font-family:'Inter',sans-serif;
  font-size:clamp(14px,1.2vw,16px) !important;
  line-height:1.6 !important;
  color:var(--txt-v) !important;
  margin:0 auto !important;
}

.cases{max-width:1200px;margin:0 auto;padding:0 24px 40px}
.sec-hdr{margin-bottom:40px}
.sec-hdr h2{font-family:'Playfair Display',serif;font-size:32px;font-weight:600}
.sec-hdr__line{height:4px;width:48px;background:var(--gold);margin-top:10px}

.cases-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:20px;align-items:start}
.case{position:relative;overflow:hidden;border-radius:12px}
.case__media{position:relative;overflow:hidden;border-radius:12px}
.case__img{width:90%;display:block;object-fit:cover;transition:transform .7s;margin:0 auto}
.case:hover .case__img{transform:scale(1.05)}
.case__badge{
  position:absolute;
  padding:6px 16px;
  background:rgba(249,248,243,.7);
  backdrop-filter:blur(18px);
  -webkit-backdrop-filter:blur(18px);
  border:1px solid rgba(119,90,25,.2);
  border-radius:9999px;
  font-family:'Inter',sans-serif;
  font-size:12px;
  font-weight:600;
  color:var(--gold);
  letter-spacing:.05em;
  white-space:nowrap;
  z-index:2;
}
.badge-tr{top:20px;right:20px}
.badge-bl{bottom:20px;left:20px}
.badge-tl{top:20px;left:20px}
.badge-br{bottom:20px;right:20px}
.case__body{padding:20px 0 0}
.case__body h3{font-family:'Playfair Display',serif;font-size:20px;margin:0 0 6px}
.case__body p{font-family:'Inter',sans-serif;font-size:14px;line-height:1.5;color:var(--txt-v)}

/* 杂志版 12 列错位 grid */
.case-1{grid-column: span 8}
.case-1 .case__img{aspect-ratio:16/9}
.case-2{grid-column: span 4;margin-top:128px}
.case-2 .case__img{aspect-ratio:3/4}
.case-3{grid-column: span 6}
.case-3 .case__img{aspect-ratio:1/1}
.case-4{grid-column: span 6;margin-top:96px}
.case-4 .case__img{aspect-ratio:4/5}

.pagi{display:flex;justify-content:center;gap:8px;padding:24px 0}
.pagi__dot{width:8px;height:8px;border-radius:50%;border:1px solid var(--out-v);background:transparent;cursor:pointer;padding:0;transition:all .3s}
.pagi__dot.on{background:var(--gold);border-color:var(--gold)}

.insights{background:var(--bg-s);padding:60px 24px}
.insights-hdr{text-align:center;margin-bottom:48px}
.insights-hdr h2{font-family:'Playfair Display',serif;font-size:32px;margin-bottom:6px}
.insights-hdr p{font-family:'Inter',sans-serif;font-size:12px;font-weight:600;letter-spacing:.3em;text-transform:uppercase;color:var(--txt-v)}
.art-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px;max-width:1200px;margin:0 auto}
.art{cursor:pointer}
.art__iw{aspect-ratio:4/5;border-radius:8px;overflow:hidden;margin-bottom:16px}
.art__ph{
  width:100%;height:100%;
  background:linear-gradient(135deg,var(--bg),#e8e5df);
  display:flex;align-items:center;justify-content:center;
  font-family:'Playfair Display',serif;
  font-size:48px;color:#747878;transition:transform .7s;
}
.art:hover .art__ph{transform:scale(1.05)}
.art__cat{font-family:'Inter',sans-serif;font-size:11px;font-weight:500;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;display:block}
.art h4{font-family:'Playfair Display',serif;font-size:18px;line-height:1.3;margin:0 0 8px}
.art h4 em{font-style:italic}
.art:hover h4{color:var(--gold)}
.art p{font-family:'Inter',sans-serif;font-size:13px;line-height:1.5;color:var(--txt-v);margin:0 0 12px}
.art__rt{font-family:'Inter',sans-serif;font-size:11px;font-weight:500;letter-spacing:.1em;text-transform:uppercase;color:var(--txt-v)}

.consult{background:var(--dark);position:relative;overflow:hidden;padding:80px 24px;text-align:center}
.consult__glow{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:600px;height:600px;background:radial-gradient(circle,rgba(119,90,25,.2),transparent 70%);pointer-events:none}
.consult h2{font-family:'Playfair Display',serif;font-size:clamp(28px,3.5vw,48px);color:#fff;margin:0 0 16px;position:relative}
.consult p{font-family:'Inter',sans-serif;font-size:16px;line-height:1.6;color:rgba(255,255,255,.7);margin:0 0 32px;position:relative}
.consult__btn{
  display:inline-block;
  padding:16px 48px;
  background:linear-gradient(135deg,var(--gold),var(--gold-l));
  color:#fff;border:none;border-radius:9999px;
  font-family:'Inter',sans-serif;
  font-size:13px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;
  cursor:pointer;transition:all .3s;position:relative;
  text-decoration:none;
}
.consult__btn:hover{transform:translateY(-2px);box-shadow:0 4px 20px rgba(119,90,25,.4)}

/* 移动端：单列 */
@media(max-width:768px){
  .cases-grid{grid-template-columns:1fr}
  .case-1,.case-2,.case-3,.case-4{grid-column:span 1;margin-top:0}
  .case-1 .case__img{aspect-ratio:16/9}
  .case-2 .case__img{aspect-ratio:3/4}
  .case-3 .case__img{aspect-ratio:1/1}
  .case-4 .case__img{aspect-ratio:4/5}
  .art-grid{grid-template-columns:1fr}
  .consult__btn{width:100%;text-align:center}
}
</style>




<section class="hero">
  <span class="kicker"><?php echo esc_html($hero_kicker); ?></span>
  <h1><?php echo wp_kses_post($hero_title); ?></h1>
  <p><?php echo esc_html($hero_subtitle); ?></p>
  <div style="width:1px;height:40px;background:linear-gradient(180deg,#775a19,transparent);margin:20px auto 0"></div>
</section>

<section class="cases">
  <div class="sec-hdr">
    <h2><?php echo esc_html($cases_title); ?></h2>
    <div class="sec-hdr__line"></div>
  </div>
  <div class="cases-grid">
    <?php foreach ($cases_render as $idx => $c) :
        $pos  = $case_badge_pos[$idx] ?? 'badge-tr';
        $span_arr = [0 => '', 1 => 'margin-top:128px;', 2 => '', 3 => 'margin-top:96px;'];
        $span_style = $span_arr[$idx] ?? '';
        ?>
        <div class="case case-<?php echo ($idx + 1); ?>" style="<?php echo $span_style; ?>">
          <div class="case__media">
            <img class="case__img" src="<?php echo esc_url($c['image']); ?>" alt="<?php echo esc_attr($c['title']); ?>">
            <div class="case__badge <?php echo esc_attr($pos); ?>"><?php echo esc_html($case_badges[$idx] ?? ''); ?></div>
          </div>
          <div class="case__body">
            <h3><?php echo esc_html($c['title']); ?></h3>
            <p><?php echo esc_html($c['desc']); ?></p>
          </div>
        </div>
    <?php endforeach; ?>
  </div>
  <div class="pagi"><button class="pagi__dot on"></button><button class="pagi__dot"></button></div>
  <div style="text-align:center;padding:16px 0 0">
    <a class="consult__btn" style="background:transparent;border:1px solid var(--gold);color:var(--gold)" href="<?php echo esc_url($cases_cta_url); ?>">
      <?php echo esc_html($cases_cta_title); ?>
    </a>
  </div>
</section>

<section class="insights">
  <div class="insights-hdr">
    <h2><?php echo esc_html($insights_title); ?></h2>
    <p><?php echo esc_html($insights_subtitle); ?></p>
  </div>
  <div class="art-grid">
    <?php foreach ($insights_render as $idx => $a) : ?>
      <a class="art" href="<?php echo esc_url($a['link']); ?>" aria-label="<?php echo esc_attr(wp_strip_all_tags($a['title'])); ?>" style="text-decoration:none;color:inherit;display:block">
        <div class="art__iw"><div class="art__ph"><?php echo esc_html($a['glyph']); ?></div></div>
        <span class="art__cat"><?php echo esc_html($a['cat']); ?></span>
        <h4><?php echo wp_kses_post($a['title']); ?></h4>
        <p><?php echo esc_html($a['desc']); ?></p>
        <span class="art__rt"><?php echo esc_html($a['time']); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
  <div class="pagi"><button class="pagi__dot on"></button></div>
  <div style="text-align:center;padding:24px 0 0">
    <a class="consult__btn" style="background:transparent;border:1px solid var(--gold);color:var(--gold)" href="<?php echo esc_url($insights_cta_url); ?>">
      <?php echo esc_html($insights_cta_title); ?>
    </a>
  </div>
</section>

<section class="consult">
  <div class="consult__glow"></div>
  <h2><?php echo esc_html($consult_title); ?></h2>
  <p><?php echo esc_html($consult_sub); ?></p>
  <a class="consult__btn" href="<?php echo esc_url($is_en ? '/contact/' : '/contact/'); ?>"><?php echo esc_html($consult_cta); ?></a>
</section>

<?php get_footer(); ?>
