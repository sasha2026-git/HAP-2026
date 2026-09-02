<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 案例与洞察 (杂志版)
 * Description: 杂志排版风格的案例展示与洞察页面（v3.5.5：v2.2.6 视觉 + ACF 集成）
 *
 * - 视觉排版严格对齐 v2.2.6（227 行精简硬编码版）
 * - 数据源：ACF（group_page_cases_insights 字段），缺失时回退到 v2.2.6 硬编码默认
 * - 双语：每个字符串都通过 hireai_field_lang 后台可编辑；.zh / .en 两个 span 始终输出，CSS/JS 切换可见性
 */

get_header();

$ci_page_id = get_the_ID();

/* ── 语言检测 ─────────────────────────────────────────── */
$ci_lang = function_exists('hireai_lang_suffix') ? (hireai_lang_suffix() === '_en' ? 'en' : 'zh') : 'zh';

/**
 * 强制读取指定语言的 ACF 字段值（不受当前 $ci_lang 影响）
 * 用法：$ci_field_lang('ci_hero_kicker', '智慧工坊', 'THE ATELIER OF INTELLIGENCE', 'zh')
 */
$ci_field_lang_force = function ($name, $zh_default, $en_default, $lang) use ($ci_page_id) {
    $def = ($lang === 'en') ? $en_default : $zh_default;
    if (function_exists('hireai_field_lang')) {
        return hireai_field_lang($name, $lang, $def, $ci_page_id);
    }
    return $def;
};

/**
 * 读取 ACF 双语字段（按当前 $ci_lang 自动选 zh/en）
 * 用法：$ci_field('ci_hero_kicker', '智慧工坊', 'THE ATELIER OF INTELLIGENCE')
 */
$ci_field = function ($name, $zh_default, $en_default) use ($ci_lang, $ci_field_lang_force) {
    return $ci_field_lang_force($name, $zh_default, $en_default, $ci_lang);
};

/**
 * 渲染 .zh / .en 双语 span 块（保留 v2.2.6 的 CSS 切换模式）
 * - 默认隐藏 .en（与 v2.2.6 一致）；JS applyClientLang 会根据 cookie 切换
 * - 自动从 ACF 取 zh 和 en 两侧的值
 */
$ci_bi = function ($name, $zh_default, $en_default) use ($ci_field_lang_force) {
    $zh = (string) $ci_field_lang_force($name, $zh_default, $en_default, 'zh');
    $en = (string) $ci_field_lang_force($name, $zh_default, $en_default, 'en');
    if ($zh === '' && $en === '') {
        return '';
    }
    return '<span class="zh">' . esc_html($zh) . '</span>'
         . '<span class="en" style="display:none">' . esc_html($en) . '</span>';
};

/**
 * 渲染 .zh / .en 双语 span 块（直接传值，不再读 ACF）
 */
$ci_bi_raw = function ($zh, $en) {
    $zh = (string) $zh;
    $en = (string) $en;
    if ($zh === '' && $en === '') {
        return '';
    }
    return '<span class="zh">' . esc_html($zh) . '</span>'
         . '<span class="en" style="display:none">' . esc_html($en) . '</span>';
};

/**
 * 取一个 ACF 图片字段（zh/en），缺省回退到内置默认 URL
 */
$ci_img = function ($name, $default) use ($ci_lang, $ci_page_id) {
    if (function_exists('hireai_image_lang')) {
        $u = hireai_image_lang($name, $ci_lang, $default, $ci_page_id);
        return $u !== '' ? $u : $default;
    }
    return $default;
};

/* ── 默认值（对齐 v2.2.6 视觉 / 排版） ────────────────────────── */
$DEF_IMG_C1 = 'https://lh3.googleusercontent.com/aida-public/AB6AXuBKTez5bKYX8hkcGYIQTY7AQolcclgmGZVyeYdO7wtjzKGi1KGDp3W2_rnyulzx-fXVnwh8gbqlgLhL7rQYDO1Hy15ExxYsRLQtHY7utzChF5Rbbt_kkIEIxWTZRt6UaN0wAUNxg3cMa-JBP4U6F5ayebnM_4V0l7fTBRWgVMtAtDjYDzOfIoeVjRBeqtwa1JkujelvLr8_CVdXDxk8Fk5JPJgXAgj5o8PW25SxvcGZNL4zqNxKLunY37DbMfsijcrosruEz0jP5B8';
$DEF_IMG_C2 = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAbzyCKncNddZWI4AL9W1PzTrf87dAdc15bSa6Fd3YrSSGFEuBfJ3GhCFLTHnITaPoy4NCIEIDZOeUKsHJd6e2c7FDaew9HCkxeuTv03yNA4X7y-qTkiFY4MOvSk2zrCu5GQ_p65NrRMz_GNOBLtPNKFzrS-Ckc5gm5l8yNmgXbThtaUMNmdR8RX5fPFCm2HTkfsWAPWB_exmyy2S83jKAisRxFIzhTpkKULDmR2dsgcRNJKdBeEcyuvWMQJMYMmhDdxAF54pTW_lY';
$DEF_IMG_C3 = 'https://lh3.googleusercontent.com/aida-public/AB6AXuD4H19rUAh5_bCz2v1ci1tseGa-Di5jpnBrAnHe-YgawYATxXGmS91wVn2gBXbFf_0wxBUsuUY2OiauRcb-ihfSKdfpgDOKtdR6DPZPZ5hw5AN05sbGZDMOlJEQ7CyYm2vkYhqAeH64sLBuIhllT8g3Xj4Qtxu37Ey2Ec9ghcAQALQT7gvWNeq6ZYILIiQ17Q68TUDhnEJH1gswkrK2eVMzgllPbeCSTXoDVClzV4puDtJEM4bQXxHHSDexA5tek-i9d0zjTNG9gCI';
$DEF_IMG_C4 = 'https://lh3.googleusercontent.com/aida-public/AB6AXuA07OHePXio7nWOjPsAAu22p1aEQmProB1QS2hd7-Dql9qnXIqTKGADFpMMJtQ7qjpsHvNaVVo1dP2zxURta_vFC6fKely12_ZmG1HXrJqIAf2mbwUgFewx_rSk1qT5UVJHJxUcXL-PEUsEAOkAezFV3CHIZCY4WD19WyO0KAcbMDq6Xa5pLw1HP0yU4oC697R8GifXGbWNOELwTRpCdraofjCGcJNz0GeZHhTKRb7gscadQ6qmTOUHImlIz4vTnY55Hec6Z9fGPyQ';
?>

<style>

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--gold:#775a19;--gold-l:#e9c176;--txt:#1a1c1c;--txt-v:#444748;--out-v:#c4c7c7;--bg:#faf9f9;--bg-s:#f4f3f3;--dark:#1b1c19;--fd:'Playfair Display',serif;--fb:'Inter',sans-serif}
body{font-family:var(--fb);background:var(--bg);color:var(--txt);-webkit-font-smoothing:antialiased}


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
/* ★ v3.5.7 Hero 加粗非斜体规范：font-weight:600 + 香槟金渐变（#775a19 → #fed488 → #775a19），font-style:normal（移除 italic） */
.hero h1{font-family:var(--fd);font-size:clamp(32px,5vw,56px);font-weight:600;line-height:1.1;margin:0 0 20px}
.hero h1{background:linear-gradient(135deg,#775a19 0%,#fed488 50%,#775a19 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-style:normal}
.hero p{font-size:clamp(14px,1.2vw,16px) !important;line-height:1.6 !important;color:var(--txt-v) !important;margin:0 auto !important}

.cases{max-width:1200px;margin:0 auto;padding:0 24px 40px}
.sec-hdr{margin-bottom:40px}
/* ★ v3.5.5 修复：.sec-hdr h2 与 .insights-hdr h2 完全一致（字号/字重/letter-spacing/颜色/上下 padding） */
.sec-hdr h2,.insights-hdr h2{font-family:var(--fd);font-size:32px;font-weight:600;letter-spacing:0;margin:0 0 12px;line-height:1.2}
.sec-hdr__line{height:4px;width:48px;background:var(--gold);margin-top:10px}

.cases-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:20px;align-items:start}
.case{position:relative;overflow:hidden;border-radius:12px}
.case__media{position:relative;overflow:hidden;border-radius:12px}
.case__img{width:90%;display:block;object-fit:cover;transition:transform .7s;margin:0 auto}
.case:hover .case__img{transform:scale(1.05)}
.case__badge{position:absolute;padding:6px 16px;background:rgba(249,248,243,.7);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border:1px solid rgba(119,90,25,.2);border-radius:9999px;font-size:12px;font-weight:600;color:var(--gold);letter-spacing:.05em;white-space:nowrap;z-index:2}
.badge-tr{top:20px;right:20px}
.badge-bl{bottom:20px;left:20px}
.badge-tl{top:20px;left:20px}
.badge-br{bottom:20px;right:20px}
.case__body{padding:20px 0 0}
.case__body h3{font-family:var(--fd);font-size:20px;margin:0 0 6px}
.case__body p{font-size:14px;line-height:1.5;color:var(--txt-v)}

/* ★ Grid 列跨度（v2.2.6 原版） */
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
.insights-hdr p{font-size:12px;font-weight:600;letter-spacing:.3em;text-transform:uppercase;color:var(--txt-v)}
.art-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px;max-width:1200px;margin:0 auto}
.art{cursor:pointer}
.art__iw{aspect-ratio:4/5;border-radius:8px;overflow:hidden;margin-bottom:16px}
.art__ph{width:100%;height:100%;background:linear-gradient(135deg,var(--bg),#e8e5df);display:flex;align-items:center;justify-content:center;font-size:48px;color:#747878;transition:transform .7s}
.art:hover .art__ph{transform:scale(1.05)}
.art__cat{font-size:11px;font-weight:500;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;display:block}
.art h4{font-family:var(--fd);font-size:18px;line-height:1.3;margin:0 0 8px}
.art h4 em{font-style:italic}
.art:hover h4{color:var(--gold)}
.art p{font-size:13px;line-height:1.5;color:var(--txt-v);margin:0 0 12px}
.art__rt{font-size:11px;font-weight:500;letter-spacing:.1em;text-transform:uppercase;color:var(--txt-v)}

.consult{background:var(--dark);position:relative;overflow:hidden;padding:80px 24px;text-align:center}
.consult__glow{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:600px;height:600px;background:radial-gradient(circle,rgba(119,90,25,.2),transparent 70%);pointer-events:none}
.consult h2{font-family:var(--fd);font-size:clamp(28px,3.5vw,48px);color:#fff;margin:0 0 16px;position:relative}
.consult p{font-size:16px;line-height:1.6;color:rgba(255,255,255,.7);margin:0 0 32px;position:relative}
.consult__btn{display:inline-block;padding:16px 48px;background:linear-gradient(135deg,var(--gold),var(--gold-l));color:#fff;border:none;border-radius:9999px;font-family:var(--fb);font-size:13px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;transition:all .3s;position:relative}
.consult__btn:hover{transform:translateY(-2px);box-shadow:0 4px 20px rgba(119,90,25,.4)}

footer{border-top:1px solid var(--out-v);padding:40px 24px;text-align:center;max-width:1200px;margin:0 auto}
footer .links{display:flex;justify-content:center;gap:28px;margin-bottom:20px;flex-wrap:wrap}
footer .links a{font-size:12px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--txt-v);text-decoration:none}
footer .links a:hover{color:var(--gold)}
footer .copy{font-size:13px;color:var(--txt-v)}

/* ★ 移动端：单列 */
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
  <?php
    /* Hero kicker：ACF 驱动 zh/en 两个 span */
    echo '<span class="kicker">';
    echo $ci_bi('ci_hero_kicker', '智慧工坊', 'THE ATELIER OF INTELLIGENCE');
    echo '</span>';

    /* Hero h1：em 部分单独 ACF 字段（避免 wp_kses_post 风险），两侧独立读取 */
    $h1_pre_zh = (string) $ci_field_lang_force('ci_hero_h1_pre_zh', '打造数字 ', 'Crafting Digital ', 'zh');
    $h1_em_zh  = (string) $ci_field_lang_force('ci_hero_h1_em_zh',  '人文',                  'Humanity',           'zh');
    $h1_pre_en = (string) $ci_field_lang_force('ci_hero_h1_pre_zh', '打造数字 ', 'Crafting Digital ', 'en');
    $h1_em_en  = (string) $ci_field_lang_force('ci_hero_h1_em_zh',  '人文',                  'Humanity',           'en');
  ?>
  <h1>
    <span class="zh"><?php echo esc_html($h1_pre_zh); ?><em><?php echo esc_html($h1_em_zh); ?></em></span>
    <span class="en" style="display:none"><?php echo esc_html($h1_pre_en); ?><em><?php echo esc_html($h1_em_en); ?></em></span>
  </h1>
  <p>
    <?php echo $ci_bi('ci_hero_p_zh', '技术精度与传承美学的交汇之处。', 'Where technical precision meets heritage aesthetic.'); ?>
  </p>
<div style="width:1px;height:40px;background:linear-gradient(180deg,#775a19,transparent);margin:20px auto 0"></div>
</section>

<section class="cases">
  <div class="sec-hdr">
    <h2>
      <?php echo $ci_bi('ci_sec_h2_zh', '卓越案例', 'Collaborative Excellence'); ?>
    </h2>
    <div class="sec-hdr__line"></div>
  </div>
  <div class="cases-grid">
    <!-- 案例1: 大图 span 8 -->
    <div class="case case-1">
      <div class="case__media">
        <img class="case__img" src="<?php echo esc_url($ci_img('ci_case1_image', $DEF_IMG_C1)); ?>" alt="">
        <div class="case__badge badge-tr"><?php echo $ci_bi('ci_case1_badge', '+42% 留存', '+42% Retention'); ?></div>
      </div>
      <div class="case__body">
        <?php echo '<h3>' . $ci_bi('ci_case1_title_zh', '数字礼宾：高定精品馆', 'Aurelian Prime for Private Banking') . '</h3>'; ?>
        <?php echo '<p>' . $ci_bi('ci_case1_desc_zh', '为高净值客户打造超写实数字人，引领其在元宇宙私密展厅中探索收藏系列。', 'Reimagining wealth management through a hyper-realistic digital concierge.') . '</p>'; ?>
      </div>
    </div>
    <!-- 案例2: 小图 span 4 + 下移 -->
    <div class="case case-2">
      <div class="case__media">
        <img class="case__img" src="<?php echo esc_url($ci_img('ci_case2_image', $DEF_IMG_C2)); ?>" alt="">
        <div class="case__badge badge-bl"><?php echo $ci_bi('ci_case2_badge', 'AI 艺术整合', 'AI Art Integration'); ?></div>
      </div>
      <div class="case__body">
        <?php echo '<h3>' . $ci_bi('ci_case2_title_zh', 'Lumina NFT 系列', 'Lumina NFT Series') . '</h3>'; ?>
        <?php echo '<p>' . $ci_bi('ci_case2_desc_zh', '独家 IP 合作，将生成算法与传统工艺融合。', 'Exclusive IP collaboration merging generative algorithms with heritage craft.') . '</p>'; ?>
      </div>
    </div>
    <!-- 案例3: 方形 span 6 -->
    <div class="case case-3">
      <div class="case__media">
        <img class="case__img" src="<?php echo esc_url($ci_img('ci_case3_image', $DEF_IMG_C3)); ?>" alt="">
        <div class="case__badge badge-tl"><?php echo $ci_bi('ci_case3_badge', '3.4 倍转化', '3.4x Conversion'); ?></div>
      </div>
      <div class="case__body">
        <?php echo '<h3>' . $ci_bi('ci_case3_title_zh', '电商进化论', 'E-commerce Evolution') . '</h3>'; ?>
        <?php echo '<p>' . $ci_bi('ci_case3_desc_zh', '将浏览转化为沉浸式策展体验。', 'Luxury retail performance scaling through personalized digital twin advisors.') . '</p>'; ?>
      </div>
    </div>
    <!-- 案例4: 大图 span 6 + 下移 -->
    <div class="case case-4">
      <div class="case__media">
        <img class="case__img" src="<?php echo esc_url($ci_img('ci_case4_image', $DEF_IMG_C4)); ?>" alt="">
        <div class="case__badge badge-br"><?php echo $ci_bi('ci_case4_badge', 'IP 保护 100%', 'IP Protection 100%'); ?></div>
      </div>
      <div class="case__body">
        <?php echo '<h3>' . $ci_bi('ci_case4_title_zh', '数字 IP 金库', 'The Digital IP Vault') . '</h3>'; ?>
        <?php echo '<p>' . $ci_bi('ci_case4_desc_zh', 'AI 集成奢侈房产的全球 PR 审计与声誉管理。', 'Global PR audit and reputation management for AI-integrated luxury estates.') . '</p>'; ?>
      </div>
    </div>
  </div>
  <div class="pagi"><button class="pagi__dot on"></button><button class="pagi__dot"></button></div>
</section>

<section class="insights">
  <div class="insights-hdr">
    <h2>
      <?php echo $ci_bi('ci_insights_h2_zh', '前沿洞察', 'The Intelligence Journal'); ?>
    </h2>
    <p>
      <?php echo $ci_bi('ci_insights_subtitle_zh', '行业洞察与思想领导力', 'INDUSTRY INSIGHTS & THOUGHT LEADERSHIP'); ?>
    </p>
  </div>
  <div class="art-grid">
    <article class="art">
      <div class="art__iw"><div class="art__ph">✦</div></div>
      <span class="art__cat"><?php echo esc_html($ci_field('ci_art1_cat', 'Aesthetics', 'Aesthetics')); ?></span>
      <h4>
        <?php echo '<span class="zh">' . esc_html($ci_field_lang_force('ci_art1_title_pre_zh', '机器中的幽灵：', 'The Ghost in the Machine: ', 'zh')) . '<em>' . esc_html($ci_field_lang_force('ci_art1_title_em_zh', '定义', 'Defining', 'zh')) . '</em>' . esc_html($ci_field_lang_force('ci_art1_title_post_zh', ' AI 之美', ' AI Beauty', 'zh')) . '</span>'; ?>
        <span class="en" style="display:none"><?php echo esc_html($ci_field_lang_force('ci_art1_title_pre_zh', '机器中的幽灵：', 'The Ghost in the Machine: ', 'en')); ?><em><?php echo esc_html($ci_field_lang_force('ci_art1_title_em_zh', '定义', 'Defining', 'en')); ?></em><?php echo esc_html($ci_field_lang_force('ci_art1_title_post_zh', ' AI 之美', ' AI Beauty', 'en')); ?></span>
      </h4>
      <p>
        <?php echo $ci_bi('ci_art1_desc_zh', '为何传统品牌正走向超风格化的数字表达。', 'Moving beyond uncanny valley into hyper-stylized digital.'); ?>
      </p>
      <span class="art__rt"><?php echo esc_html($ci_field('ci_art1_rt', '8 分钟阅读', '8 MIN READ')); ?></span>
    </article>
    <article class="art">
      <div class="art__iw"><div class="art__ph">◈</div></div>
      <span class="art__cat"><?php echo esc_html($ci_field('ci_art2_cat', 'Technology', 'Technology')); ?></span>
      <h4>
        <?php echo '<span class="zh">' . esc_html($ci_field_lang_force('ci_art2_title_pre_zh', '神经网络与丝绸：', 'Neural Networks & Silk: ', 'zh')) . '<em>' . esc_html($ci_field_lang_force('ci_art2_title_em_zh', '未来', 'Future', 'zh')) . '</em>' . esc_html($ci_field_lang_force('ci_art2_title_post_zh', ' 服务的织物', ' Service', 'zh')) . '</span>'; ?>
        <span class="en" style="display:none"><?php echo esc_html($ci_field_lang_force('ci_art2_title_pre_zh', '神经网络与丝绸：', 'Neural Networks & Silk: ', 'en')); ?><em><?php echo esc_html($ci_field_lang_force('ci_art2_title_em_zh', '未来', 'Future', 'en')); ?></em><?php echo esc_html($ci_field_lang_force('ci_art2_title_post_zh', ' 服务的织物', ' Service', 'en')); ?></span>
      </h4>
      <p>
        <?php echo $ci_bi('ci_art2_desc_zh', '在不失去专属触感的前提下扩展个性化关怀。', 'Scaling personalized attention without losing human touch.'); ?>
      </p>
      <span class="art__rt"><?php echo esc_html($ci_field('ci_art2_rt', '12 分钟阅读', '12 MIN READ')); ?></span>
    </article>
    <article class="art">
      <div class="art__iw"><div class="art__ph">❖</div></div>
      <span class="art__cat"><?php echo esc_html($ci_field('ci_art3_cat', 'Strategy', 'Strategy')); ?></span>
      <h4>
        <?php echo '<span class="zh">' . esc_html($ci_field_lang_force('ci_art3_title_pre_zh', '新白手套：', 'The New White Glove: ', 'zh')) . '<em>' . esc_html($ci_field_lang_force('ci_art3_title_em_zh', 'AI', 'AI', 'zh')) . '</em>' . esc_html($ci_field_lang_force('ci_art3_title_post_zh', ' 作为终极礼宾', ' as Ultimate Concierge', 'zh')) . '</span>'; ?>
        <span class="en" style="display:none"><?php echo esc_html($ci_field_lang_force('ci_art3_title_pre_zh', '新白手套：', 'The New White Glove: ', 'en')); ?><em><?php echo esc_html($ci_field_lang_force('ci_art3_title_em_zh', 'AI', 'AI', 'en')); ?></em><?php echo esc_html($ci_field_lang_force('ci_art3_title_post_zh', ' 作为终极礼宾', ' as Ultimate Concierge', 'en')); ?></span>
      </h4>
      <p>
        <?php echo $ci_bi('ci_art3_desc_zh', '审视自动化高端体验时代中忠诚度的演变。', 'Loyalty evolution in automated high-end experiences.'); ?>
      </p>
      <span class="art__rt"><?php echo esc_html($ci_field('ci_art3_rt', '6 分钟阅读', '6 MIN READ')); ?></span>
    </article>
  </div>
  <div class="pagi"><button class="pagi__dot on"></button></div>
</section>

<section class="consult">
  <div class="consult__glow"></div>
  <h2>
    <?php echo $ci_bi('ci_consult_h2_zh', '准备好定义您的传承了吗？', 'Ready to define your legacy?'); ?>
  </h2>
  <p>
    <?php echo $ci_bi('ci_consult_p_zh', '加入全球领先的品牌 AI 数字员工计划。迈出第一步。', "Join the world's leading brands in the new era of digital human excellence."); ?>
  </p>
  <button class="consult__btn">
    <?php echo $ci_bi('ci_consult_btn_zh', '立即咨询', 'Initiate Consultation'); ?>
  </button>
</section>

<?php get_footer(); ?>
