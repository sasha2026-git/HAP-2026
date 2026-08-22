<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: Front Page
 * Stitch-matched v4.0.0 — 全栈重写 (Hero → Intro → Products → Solutions → Cases → FAQ → CTA)
 *
 * v4.0.0 (2026-08-22) — 重建自 /tmp/stitch-check/{code.html,screen.png,DESIGN.md}
 *   - 完全遵循 Stitch 设计稿结构（七大 section、12-col cases 网格、玻璃拟态 CTA）
 *   - 双语：.zh/.en 双后缀字段由 hireai_field_lang() 直接读取
 *   - 内联 CSS（页面顶部 <style>）+ 局部 hireai-fp-* 命名空间
 *   - 不使用 Tailwind / Material Symbols CDN，使用内联 SVG 图标
 */
get_header();

/* ── 当前语言 ── */
$suffix = hireai_lang_suffix();
$is_en  = ($suffix === '_en');
$lang   = $is_en ? 'en' : 'zh';
$home   = get_stylesheet_directory_uri();

/* ── Helper: 单字段双语 fallback ── */
$b = function ($name, $zh_default = '', $en_default = '') use ($lang) {
    return hireai_field_lang($name, $lang, $lang === 'en' ? $en_default : $zh_default);
};

/* ═══════════════════════════════════════════════════════════════════════
   ACF 数据加载（严格匹配 functions.php 第 774–928 行注册的字段）
   ═══════════════════════════════════════════════════════════════════════ */

/* Hero */
$hero_kicker   = hireai_field_lang('fp_hero_kicker', $lang, $is_en ? 'AETHER LUXE AI' : 'AETHER LUXE AI');
$hero_static   = hireai_field_lang('fp_hero_static', $lang, $is_en ? 'Redefining' : '重新定义');
$hero_accent   = hireai_field_lang('fp_hero_accent', $lang, $is_en ? 'Digital Labor' : '数字劳动力');
$hero_subtitle = hireai_field_lang('fp_hero_subtitle', $lang, $is_en ? 'Fusing cutting-edge technology with a luxurious aesthetic to craft your bespoke digital workforce.' : '融合尖端科技与奢华质感，为您打造专属的数字员工矩阵。');
$hero_image    = hireai_image('fp_hero_image', $home . '/assets/img/home/hero.png');
$hero_cta_1    = hireai_link_lang('fp_hero_cta_1', 'zh', '/ai-employees/', '/ai-employees/', '探索系列', 'EXPLORE SERIES');
$hero_cta_2    = hireai_link_lang('fp_hero_cta_2', 'zh', '/contact/',     '/contact/',     '联系我们',   'CONTACT US');

/* Intro */
$intro_kicker     = hireai_field_lang('fp_intro_kicker', $lang, $is_en ? 'Craftsmanship Meets Algorithm' : '工匠精神与算法');
$intro_title      = hireai_field_lang('fp_intro_title', $lang, $is_en ? 'Shaping existence beyond physical boundaries.' : '塑造超越物理边界的存在。');
$intro_desc       = hireai_field_lang('fp_intro_desc', $lang, $is_en ? 'We combine the rigor of traditional luxury with the infinite potential of neural networks. Every digital employee is a one-of-a-kind masterpiece, designed for elegance, intelligence, and resonance.' : '我们结合传统奢华的严谨工艺与神经网络的无限可能。每一位数字员工都是独一无二的杰作，专为优雅、智慧与共鸣而设计。');
$intro_cta_label  = hireai_field_lang('fp_intro_cta_title', $lang, $is_en ? 'Explore More' : '探索更多');
$intro_cta_url    = hireai_field_lang('fp_intro_cta_url', $lang, '/ai-employees/');

/* Products section */
$prod_section_kicker = hireai_field_lang('fp_products_kicker', $lang, $is_en ? 'Limited Neural Series' : '限量神经元系列');
$prod_section_title  = hireai_field_lang('fp_products_title', $lang, $is_en ? 'AI Digital Employees' : 'AI 数字员工');
$prod_section_sub    = hireai_field_lang('fp_products_subtitle', $lang, $is_en ? 'Each digital employee brings a unique soul, refined skills, and unmatched capabilities.' : '每一位数字员工都拥有独特的灵魂、技能与能力，随时加入您的团队。');
$prod_explore_label  = hireai_field_lang('fp_products_explore_label', $lang, $is_en ? 'Explore More' : '探索更多');
$prod_explore_url    = hireai_field_lang('fp_products_explore_url', $lang, '/ai-employees/');

$products = [];
foreach ([1, 2, 3] as $i) {
    $products[] = [
        'title' => hireai_field_lang("fp_prod{$i}_title", $lang, $is_en ? '' : ''),
        'desc'  => hireai_field_lang("fp_prod{$i}_desc", $lang, $is_en ? '' : ''),
        'badge' => hireai_field_lang("fp_prod{$i}_badge", $lang, $is_en ? '' : ''),
        'img'   => hireai_image("fp_prod{$i}_image",
                    $home . '/assets/img/home/' . ($i === 1 ? 'product-prime' : ($i === 2 ? 'product-exec' : 'product-neural')) . '.png'),
        'url'   => hireai_field("fp_prod{$i}_url", home_url('/ai-employees/')),
        'btn'   => hireai_field_lang("fp_prod{$i}_btn", $lang, $is_en ? 'Explore More' : '探索更多'),
    ];
}

/* Solutions section */
$sol_section_kicker = hireai_field_lang('fp_solutions_kicker', $lang, $is_en ? 'Industry Empowerment' : '行业赋能');
$sol_section_title  = hireai_field_lang('fp_solutions_title', $lang, $is_en ? 'AI Solutions' : 'AI 解决方案');
$sol_section_sub    = hireai_field_lang('fp_solutions_subtitle', $lang, $is_en ? 'Bespoke intelligent solutions across industries.' : '面向多元行业，打造量身定制的智能解决方案。');
$sol_explore_label  = hireai_field_lang('fp_solutions_explore_label', $lang, $is_en ? 'Explore More' : '探索更多');
$sol_explore_url    = hireai_field_lang('fp_solutions_explore_url', $lang, '/ai-solutions/');

$solutions = [];
foreach ([1, 2, 3, 4] as $i) {
    $defaults = [
        1 => ['title' => '金融与财富管理',   'desc' => '智能顾问与客户关系维护的数字化重塑。', 'tag' => '金融',   'icon' => 'finance',       'img' => 'solution-finance'],
        2 => ['title' => '高端零售与电商',   'desc' => '24/7 全天候奢华购物体验升级。',         'tag' => '零售',   'icon' => 'retail',        'img' => 'solution-retail'],
        3 => ['title' => '医疗健康与陪伴',   'desc' => '充满同理心的智能关怀与健康咨询。',       'tag' => '健康',   'icon' => 'health',        'img' => ''],
        4 => ['title' => '泛娱乐与虚拟偶像', 'desc' => '打造永不塌房的超级 IP 与互动体验。',     'tag' => '娱乐',   'icon' => 'entertainment', 'img' => ''],
    ];
    $d = $defaults[$i];
    $solutions[] = [
        'title' => hireai_field_lang("fp_sol{$i}_title", $lang, $is_en ? $d['title'] : $d['title']),
        'desc'  => hireai_field_lang("fp_sol{$i}_desc", $lang, $is_en ? $d['desc'] : $d['desc']),
        'tag'   => hireai_field_lang("fp_sol{$i}_tag", $lang, $is_en ? $d['tag'] : $d['tag']),
        'img'   => hireai_image("fp_sol{$i}_image",
                    $d['img'] ? $home . '/assets/img/home/' . $d['img'] . '.png' : ''),
        'icon'  => $d['icon'],
        'url'   => hireai_field("fp_sol{$i}_url", home_url('/ai-solutions/')),
    ];
}

/* Cases section */
$cases_kicker        = hireai_field_lang('fp_cases_kicker', $lang, $is_en ? 'Frontier Vision' : '前沿视野');
$cases_title         = hireai_field_lang('fp_cases_title', $lang, $is_en ? 'Cases & Insights' : '案例 & 洞察');
$cases_sub           = hireai_field_lang('fp_cases_subtitle', $lang, $is_en ? 'See how digital employees transform operations.' : '见证数字员工如何改变企业的运营方式。');
$cases_explore_label = hireai_field_lang('fp_cases_explore_label', $lang, $is_en ? 'Explore More' : '探索更多');
$cases_explore_url   = hireai_field_lang('fp_cases_explore_url', $lang, '/cases-insights/');

$major_case = [
    'label' => hireai_field_lang('fp_case_major_label', $lang, $is_en ? 'CASE STUDY' : '案例研究'),
    'title' => hireai_field_lang('fp_case_major_title', $lang, $is_en ? 'Aurelian Prime in Private Banking' : 'Aurelian Prime 在私人银行的应用'),
    'desc'  => hireai_field_lang('fp_case_major_desc', $lang, $is_en ? 'Learn how our top digital employee boosts retention and satisfaction for high-net-worth clients.' : '了解我们的顶级数字员工如何提升高净值客户的留存率与满意度。'),
    'img'   => hireai_image('fp_case_major_image', $home . '/assets/img/defaults/case-1.jpg'),
    'url'   => hireai_field('fp_case_major_url', home_url('/cases-insights/')),
];

$side_cases = [];
foreach ([1, 2] as $i) {
    $defaults = [
        1 => ['tag' => '案例研究', 'title' => '电商视觉革命：转化率提升 55%', 'desc' => '重塑线上购物体验，结合虚拟试穿与个性化推荐带来的商业增长。', 'img' => 'case-2'],
        2 => ['tag' => '深度洞察', 'title' => '"未来不再仅仅是代码，更是交响乐。"', 'desc' => '探讨数字人性化的趋势，以及我们在构建有温度的 AI 方面的思考与实践。', 'img' => 'case-3'],
    ];
    $d = $defaults[$i];
    $side_cases[] = [
        'tag'   => hireai_field_lang("fp_case{$i}_tag", $lang, $is_en ? $d['tag'] : $d['tag']),
        'title' => hireai_field_lang("fp_case{$i}_title", $lang, $is_en ? $d['title'] : $d['title']),
        'desc'  => hireai_field_lang("fp_case{$i}_desc", $lang, $is_en ? $d['desc'] : $d['desc']),
        'img'   => hireai_image("fp_case{$i}_image", $home . '/assets/img/defaults/' . $d['img'] . '.jpg'),
        'url'   => hireai_field("fp_case{$i}_url", home_url('/cases-insights/')),
    ];
}

/* FAQ section */
$faq_kicker = hireai_field_lang('fp_faq_kicker', $lang, $is_en ? 'FAQ' : '常见问题');
$faq_title  = hireai_field_lang('fp_faq_title', $lang, $is_en ? 'Answers to your questions about digital employees.' : '解答关于数字员工的疑虑，开启智能新纪元。');
$faq_explore_label = hireai_field_lang('fp_faq_explore_label', $lang, $is_en ? 'Explore More' : '探索更多');
$faq_explore_url   = hireai_field_lang('fp_faq_explore_url', $lang, '/faq/');

$faq_items = [];
foreach ([1, 2, 3] as $i) {
    $defaults = [
        1 => ['q' => '定制一位数字员工需要多长时间？', 'a' => '这取决于定制的复杂程度。基础模型微调通常需要 2–4 周，而完全定制化（包括独特外观建模、声音克隆和深度行业知识库训练）可能需要 8–12 周。'],
        2 => ['q' => '数字员工的知识库可以实时更新吗？', 'a' => '是的，我们的系统支持通过 API 进行实时知识库更新。您可以随时添加新的产品信息、政策变更或行业动态，确保数字员工始终掌握最新资讯。'],
        3 => ['q' => '如何保障数据隐私与安全？',         'a' => '我们采用企业级加密标准，所有交互数据均在本地或专属私有云中处理。我们严格遵守全球数据保护法规，确保您的商业机密与客户隐私绝对安全。'],
    ];
    $d = $defaults[$i];
    $faq_items[] = [
        'q' => hireai_field_lang("fp_faq{$i}_q", $lang, $is_en ? $d['q'] : $d['q']),
        'a' => hireai_field_lang("fp_faq{$i}_a", $lang, $is_en ? $d['a'] : $d['a']),
    ];
}

/* CTA band */
$cta_kicker   = hireai_field_lang('fp_cta_kicker', $lang, $is_en ? 'Begin the Journey' : '开启旅程');
$cta_title    = hireai_field_lang('fp_cta_title', $lang, $is_en ? 'Begin Your AI Hiring Journey' : '开启您的 AI 雇佣之旅');
$cta_desc     = hireai_field_lang('fp_cta_desc', $lang, $is_en ? 'Speak with our team and craft a digital workforce made for you.' : '与我们的团队对话，打造专属您的数字员工阵容。');
$cta_btn_title = hireai_field_lang('fp_cta_btn_title', $lang, $is_en ? 'Contact Us' : '联系我们');
$cta_btn_url   = hireai_field_lang('fp_cta_btn_url', $lang, '/contact/');
$cta_btn_2_title = hireai_field_lang('fp_cta_btn_2_title', $lang, $is_en ? 'Book Consultation' : '预约咨询');
$cta_btn_2_url   = hireai_field_lang('fp_cta_btn_2_url', $lang, '/contact/');

/* i18n text */
$t = [
    'scroll'   => $is_en ? 'Scroll'    : '向下滚动',
    'prev'     => $is_en ? 'Previous'  : '上一个',
    'next'     => $is_en ? 'Next'      : '下一个',
];
?>

<!-- ═══════════════════════════════════════════════════════════════════════
     页面专有样式（hireai-fp-* 命名空间）
     ═══════════════════════════════════════════════════════════════════════ -->
<style>
.hireai-fp{box-sizing:border-box;margin:0;padding:0;background:var(--surface,#faf9f9);color:#1a1c1c;font-family:'Inter',-apple-system,BlinkMacSystemFont,sans-serif;line-height:1.6;overflow-x:hidden;}
.hireai-fp *{box-sizing:border-box;}
.hireai-fp img{display:block;max-width:100%;height:auto;}
.hireai-fp a{text-decoration:none;color:inherit;}
.hireai-fp button{font-family:inherit;cursor:pointer;}

/* ── Tokens（对齐 DESIGN.md） ── */
.hireai-fp{
    --gold:#775a19; --gold-light:#e9c176; --gold-pale:#ffdea5;
    --ink:#1a1c1c; --ink-muted:#444748; --line:rgba(196,199,199,.3);
    --line-gold:rgba(119,90,25,.18); --side:clamp(20px,5vw,80px);
    --max:1440px;
    --f-display:'Playfair Display','Noto Serif SC',serif;
    --f-headline:'Playfair Display','Noto Serif SC',serif;
    --f-body:'Inter','Noto Sans SC',sans-serif;
    --f-label:'Inter','Noto Sans SC',sans-serif;
    --sz-d:clamp(40px,6vw,72px);
    --sz-h1:clamp(32px,4.5vw,48px);
    --sz-h2:clamp(28px,3.5vw,42px);
    --sz-h3:clamp(20px,2vw,32px);
    --sz-bl:18px; --sz-bm:16px; --sz-lm:14px; --sz-ls:12px;
    --gap:clamp(60px,8vw,120px);
    --btn-pad-y:16px; --btn-pad-x:clamp(28px,4vw,48px);
}

/* ── 通用：金质渐变文字 ── */
.hireai-fp__gold{
    background:linear-gradient(135deg,#e9c176 0%,#775a19 100%);
    -webkit-background-clip:text;background-clip:text;
    -webkit-text-fill-color:transparent;color:transparent;
    font-style:italic;font-weight:400;
}

/* ── 通用：kicker 小标签 ── */
.hireai-fp__kicker{
    display:inline-block;font-family:var(--f-label);
    font-size:var(--sz-ls);font-weight:500;letter-spacing:.3em;
    text-transform:uppercase;color:var(--gold);
    margin-bottom:16px;
}

/* ── 通用：section padding ── */
.hireai-fp__section{
    padding-block:var(--gap);
    padding-inline:var(--side);
    max-width:var(--max);
    margin-inline:auto;
}

/* ── 通用：按钮（pill） ── */
.hireai-fp__btn{
    display:inline-flex;align-items:center;justify-content:center;
    font-family:var(--f-label);font-size:var(--sz-lm);font-weight:600;
    line-height:1.2;letter-spacing:.1em;text-transform:uppercase;
    padding:var(--btn-pad-y) var(--btn-pad-x);border-radius:9999px;
    border:1px solid transparent;cursor:pointer;transition:all .3s;
    text-align:center;white-space:nowrap;
}
.hireai-fp__btn--primary{background:#000;color:#fff;border-color:#000;}
.hireai-fp__btn--primary:hover{box-shadow:0 0 24px rgba(119,90,25,.45);}
.hireai-fp__btn--outline{background:rgba(255,255,255,.55);color:var(--gold);border:1px solid var(--line-gold);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);}
.hireai-fp__btn--outline:hover{background:rgba(119,90,25,.08);}
.hireai-fp__btn--ghost{background:transparent;color:#1a1c1c;border-color:#1a1c1c;}
.hireai-fp__btn--ghost:hover{background:#1a1c1c;color:#fff;}
.hireai-fp__btn--gold{background:linear-gradient(135deg,#e9c176 0%,#775a19 100%);color:#fff;border-color:transparent;}
.hireai-fp__btn--gold:hover{box-shadow:0 0 32px rgba(233,193,118,.45);transform:translateY(-1px);}

/* ═══════════════════════════════════════════════════════════════════════
   1) HERO
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-hero{position:relative;width:100%;min-height:100vh;display:flex;align-items:center;justify-content:center;overflow:hidden;background:var(--surface,#faf9f9);}
.hireai-fp-hero__bg{position:absolute;inset:0;z-index:0;}
.hireai-fp-hero__bg img{width:100%;height:100%;object-fit:cover;}
.hireai-fp-hero__shade{position:absolute;inset:0;z-index:1;background:linear-gradient(180deg,rgba(249,249,249,.65) 0%,rgba(249,249,249,.25) 45%,var(--surface,#faf9f9) 100%);}
.hireai-fp-hero__inner{position:relative;z-index:2;text-align:center;width:min(100%,920px);padding:14vh var(--side) 80px;}
.hireai-fp-hero__kicker{margin-bottom:24px;}
.hireai-fp-hero__title{font-family:var(--f-display);font-size:var(--sz-d);font-weight:700;line-height:1.1;letter-spacing:-.02em;color:#000;margin:0 0 24px;}
.hireai-fp-hero__title-line{display:block;}
.hireai-fp-hero__subtitle{font-family:var(--f-body);font-size:var(--sz-bl);font-weight:400;line-height:1.6;color:rgba(0,0,0,.65);max-width:640px;margin:0 auto 38px;}
.hireai-fp-hero__actions{display:flex;flex-wrap:wrap;justify-content:center;gap:16px;}
.hireai-fp-hero__scroll{position:absolute;left:50%;bottom:36px;transform:translateX(-50%);z-index:2;display:flex;flex-direction:column;align-items:center;gap:8px;color:rgba(0,0,0,.45);font-family:var(--f-label);font-size:10px;letter-spacing:.3em;text-transform:uppercase;}
.hireai-fp-hero__scroll span{display:block;width:18px;height:18px;border-right:2px solid rgba(0,0,0,.35);border-bottom:2px solid rgba(0,0,0,.35);transform:rotate(45deg);animation:hireai-fp-bounce 2.2s infinite ease-in-out;}
@keyframes hireai-fp-bounce{0%,100%{transform:rotate(45deg) translateY(0);}50%{transform:rotate(45deg) translateY(8px);}}

/* ═══════════════════════════════════════════════════════════════════════
   2) INTRO
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-intro{background:var(--surface,#faf9f9);text-align:center;padding-block:var(--gap);padding-inline:var(--side);max-width:1080px;margin:0 auto;}
.hireai-fp-intro__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0 0 24px;}
.hireai-fp-intro__desc{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.7;color:var(--ink-muted);max-width:760px;margin:0 auto 40px;}
.hireai-fp-intro__cta a{display:inline-flex;align-items:center;gap:10px;}

/* ═══════════════════════════════════════════════════════════════════════
   3) PRODUCTS（AI 数字员工）
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-products{background:#fff;padding-inline:var(--side);padding-block:var(--gap);max-width:var(--max);margin-inline:auto;}
.hireai-fp-products__head{display:flex;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:clamp(40px,5vw,72px);padding-bottom:24px;border-bottom:1px solid var(--line);}
.hireai-fp-products__head-text{max-width:680px;}
.hireai-fp-products__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0;}
.hireai-fp-products__sub{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:12px 0 0;}
.hireai-fp-products__nav{display:flex;gap:12px;}
.hireai-fp-products__nav button{width:48px;height:48px;border-radius:50%;border:1px solid var(--outline-variant,#c4c7c7);background:transparent;color:#1a1c1c;display:flex;align-items:center;justify-content:center;transition:all .25s;}
.hireai-fp-products__nav button:hover{background:rgba(0,0,0,.06);border-color:#000;}

.hireai-fp-products__grid{display:grid;grid-template-columns:1fr;gap:24px;}
@media(min-width:640px){.hireai-fp-products__grid{grid-template-columns:repeat(2,1fr);}}
@media(min-width:1024px){.hireai-fp-products__grid{grid-template-columns:repeat(3,1fr);}}

.hireai-fp-product{display:flex;flex-direction:column;background:rgba(249,248,243,.72);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid var(--line-gold);border-radius:.5rem;padding:20px;transition:transform .5s;}
.hireai-fp-product:hover{transform:translateY(-4px);}
.hireai-fp-product__media{position:relative;aspect-ratio:3/4;overflow:hidden;margin-bottom:24px;border:1px solid var(--line);background:#eee;border-radius:.5rem;}
.hireai-fp-product__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-product:hover .hireai-fp-product__img{transform:scale(1.05);}
.hireai-fp-product__badge{position:absolute;top:14px;right:14px;background:rgba(255,255,255,.85);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);padding:5px 14px;border-radius:9999px;border:1px solid rgba(0,0,0,.06);font-family:var(--f-label);font-size:10px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:#000;}
.hireai-fp-product__name{font-family:var(--f-headline);font-size:clamp(22px,2vw,var(--sz-h3));font-weight:500;line-height:1.3;color:#000;margin:0 0 8px;}
.hireai-fp-product__desc{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:0 0 20px;}
.hireai-fp-product__btn{width:100%;margin-top:auto;padding:14px 0;}

/* ═══════════════════════════════════════════════════════════════════════
   4) SOLUTIONS（AI 解决方案）
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-solutions{background:var(--surface,#faf9f9);padding-inline:var(--side);padding-block:var(--gap);max-width:var(--max);margin-inline:auto;overflow:hidden;}
.hireai-fp-solutions__head{display:flex;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:clamp(40px,5vw,72px);}
.hireai-fp-solutions__head-text{max-width:680px;}
.hireai-fp-solutions__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0;}

.hireai-fp-solutions__grid{display:grid;grid-template-columns:1fr;gap:24px;}
@media(min-width:768px){.hireai-fp-solutions__grid{grid-template-columns:repeat(2,1fr);gap:32px;}}

.hireai-fp-sol{position:relative;aspect-ratio:16/9;overflow:hidden;border:1px solid var(--line);border-radius:.5rem;transition:border-color .3s;background:#e2e2e2;}
.hireai-fp-sol:hover{border-color:var(--gold);}
.hireai-fp-sol__media,.hireai-fp-sol__img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .7s ease-out;}
.hireai-fp-sol:hover .hireai-fp-sol__img{transform:scale(1.05);}
.hireai-fp-sol__placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#e8e8e8;}
.hireai-fp-sol__placeholder svg{width:64px;height:64px;color:var(--gold);opacity:.55;}
.hireai-fp-sol__overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0) 0%,rgba(0,0,0,.25) 50%,rgba(0,0,0,.85) 100%);display:flex;flex-direction:column;justify-content:flex-end;padding:clamp(20px,2.5vw,32px);color:#fff;}
.hireai-fp-sol__tag{font-family:var(--f-label);font-size:10px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--gold-light);margin:0 0 10px;}
.hireai-fp-sol__title{font-family:var(--f-headline);font-size:clamp(20px,2vw,28px);font-weight:500;line-height:1.3;color:#fff;margin:0 0 8px;}
.hireai-fp-sol__desc{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:rgba(255,255,255,.85);margin:0;max-width:480px;}

.hireai-fp-solutions__cta{text-align:center;margin-top:clamp(40px,5vw,72px);}

/* ═══════════════════════════════════════════════════════════════════════
   5) CASES & INSIGHTS
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-cases{background:#fff;padding-inline:var(--side);padding-block:var(--gap);max-width:var(--max);margin-inline:auto;border-top:1px solid var(--line);}
.hireai-fp-cases__head{display:flex;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:clamp(40px,5vw,72px);padding-bottom:24px;border-bottom:1px solid var(--line);}
.hireai-fp-cases__head-text{max-width:680px;}
.hireai-fp-cases__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0;}
.hireai-fp-cases__nav{display:flex;gap:12px;}
.hireai-fp-cases__nav button{width:48px;height:48px;border-radius:50%;border:1px solid var(--outline-variant,#c4c7c7);background:transparent;color:#1a1c1c;display:flex;align-items:center;justify-content:center;transition:all .25s;}
.hireai-fp-cases__nav button:hover{background:rgba(0,0,0,.06);border-color:#000;}

.hireai-fp-cases__grid{display:grid;grid-template-columns:1fr;gap:clamp(32px,4vw,48px);}
@media(min-width:1024px){.hireai-fp-cases__grid{grid-template-columns:repeat(12,1fr);gap:clamp(32px,4vw,48px);}}

.hireai-fp-case-major{grid-column:span 12;display:flex;flex-direction:column;cursor:pointer;transition:transform .4s;}
@media(min-width:1024px){.hireai-fp-case-major{grid-column:span 8;}}
.hireai-fp-case-major__media{aspect-ratio:16/9;overflow:hidden;border:1px solid var(--line);border-radius:.5rem;margin-bottom:24px;}
.hireai-fp-case-major__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-case-major:hover .hireai-fp-case-major__img{transform:scale(1.05);}
.hireai-fp-case-major__body{padding:0 4px;}
.hireai-fp-case-major__tag{display:inline-block;font-family:var(--f-label);font-size:11px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:10px;}
.hireai-fp-case-major__title{font-family:var(--f-headline);font-size:clamp(22px,2.4vw,var(--sz-h3));font-weight:500;line-height:1.3;color:#000;margin:0 0 12px;transition:color .3s;}
.hireai-fp-case-major:hover .hireai-fp-case-major__title{color:var(--gold);}
.hireai-fp-case-major__desc{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:0;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}

.hireai-fp-cases__side{grid-column:span 12;display:flex;flex-direction:column;gap:clamp(28px,3vw,40px);}
@media(min-width:1024px){.hireai-fp-cases__side{grid-column:span 4;}}

.hireai-fp-case-side{display:flex;flex-direction:column;cursor:pointer;}
.hireai-fp-case-side__media{aspect-ratio:16/9;overflow:hidden;border:1px solid var(--line);border-radius:.5rem;margin-bottom:16px;background:#eee;}
.hireai-fp-case-side__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-case-side:hover .hireai-fp-case-side__img{transform:scale(1.05);}
.hireai-fp-case-side__tag{display:inline-block;font-family:var(--f-label);font-size:11px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;}
.hireai-fp-case-side__title{font-family:var(--f-headline);font-size:clamp(18px,1.8vw,22px);font-weight:500;line-height:1.3;color:#000;margin:0 0 8px;transition:color .3s;}
.hireai-fp-case-side:hover .hireai-fp-case-side__title{color:var(--gold);}
.hireai-fp-case-side__desc{font-family:var(--f-body);font-size:14px;line-height:1.6;color:var(--ink-muted);margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}

.hireai-fp-cases__cta{text-align:center;margin-top:clamp(40px,5vw,72px);}

/* ═══════════════════════════════════════════════════════════════════════
   6) FAQ（常见问题）
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-faq{background:var(--surface,#faf9f9);padding-inline:var(--side);padding-block:var(--gap);text-align:center;border-top:1px solid var(--line);}
.hireai-fp-faq__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0;}
.hireai-fp-faq__sub{font-family:var(--f-body);font-size:var(--sz-bl);line-height:1.6;color:var(--ink-muted);margin:16px auto 0;max-width:640px;}
.hireai-fp-faq__list{max-width:780px;margin:clamp(40px,5vw,72px) auto 0;text-align:left;}
.hireai-fp-faq-item{border-bottom:1px solid var(--line);padding-bottom:0;}
.hireai-fp-faq-item__header{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:20px 0;cursor:pointer;}
.hireai-fp-faq-item__header:hover .hireai-fp-faq-item__q{color:var(--gold);}
.hireai-fp-faq-item__q{flex:1;font-family:var(--f-headline);font-size:clamp(18px,1.8vw,24px);font-weight:500;line-height:1.3;color:#000;margin:0;transition:color .3s;text-align:left;}
.hireai-fp-faq-item__icon{flex-shrink:0;width:24px;height:24px;color:var(--ink-muted);transition:transform .3s;}
.hireai-fp-faq-item.is-open .hireai-fp-faq-item__icon{transform:rotate(180deg);}
.hireai-fp-faq-item__body{max-height:0;overflow:hidden;transition:max-height .35s ease-out;}
.hireai-fp-faq-item.is-open .hireai-fp-faq-item__body{max-height:640px;}
.hireai-fp-faq-item__answer{padding-bottom:20px;font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.7;color:rgba(68,71,72,.85);}
.hireai-fp-faq__cta{text-align:center;margin-top:clamp(40px,5vw,72px);}

/* ═══════════════════════════════════════════════════════════════════════
   7) CTA BAND（开启您的 AI 雇佣之旅）
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-cta{position:relative;background:#000;color:#fff;padding:clamp(80px,10vw,140px) var(--side);text-align:center;overflow:hidden;}
.hireai-fp-cta__glow{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:clamp(400px,60vw,720px);aspect-ratio:1/1;background:radial-gradient(circle,rgba(119,90,25,.25),transparent 65%);pointer-events:none;}
.hireai-fp-cta__inner{position:relative;max-width:760px;margin:0 auto;}
.hireai-fp-cta__kicker{display:inline-block;font-family:var(--f-label);font-size:var(--sz-ls);font-weight:500;letter-spacing:.3em;text-transform:uppercase;color:var(--gold-light);margin-bottom:20px;}
.hireai-fp-cta__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#fff;margin:0 0 20px;}
.hireai-fp-cta__desc{font-family:var(--f-body);font-size:var(--sz-bl);line-height:1.7;color:rgba(255,255,255,.7);margin:0 auto 40px;max-width:640px;}
.hireai-fp-cta__actions{display:inline-flex;flex-wrap:wrap;justify-content:center;gap:16px;}

/* ═══════════════════════════════════════════════════════════════════════
   Responsive fine-tune
   ═══════════════════════════════════════════════════════════════════════ */
@media(max-width:767px){
    .hireai-fp-hero__inner{padding:18vh var(--side) 80px;}
    .hireai-fp-hero__title{font-size:clamp(32px,8vw,48px);}
    .hireai-fp-products__head,.hireai-fp-solutions__head,.hireai-fp-cases__head{flex-direction:column;align-items:flex-start;}
    .hireai-fp-products__nav,.hireai-fp-cases__nav{display:none;}
    .hireai-fp-faq__list{padding-inline:0;}
}
</style>

<!-- ═══════════════════════════════════════════════════════════════════════
     1) HERO  ── Stitch code.html Hero Section
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-hero" aria-label="<?php echo esc_attr($is_en ? 'Hero' : '首页主视觉'); ?>">
    <div class="hireai-fp-hero__bg">
        <?php if ($hero_image) : ?>
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($is_en ? 'AI Digital Employees' : 'AI 数字员工'); ?>" loading="eager" decoding="async">
        <?php endif; ?>
    </div>
    <div class="hireai-fp-hero__shade" aria-hidden="true"></div>
    <div class="hireai-fp-hero__inner">
        <?php if ($hero_kicker) : ?>
        <p class="hireai-fp__kicker hireai-fp-hero__kicker"><?php echo esc_html($hero_kicker); ?></p>
        <?php endif; ?>
        <h1 class="hireai-fp-hero__title">
            <span class="hireai-fp-hero__title-line"><?php echo esc_html($hero_static); ?></span>
            <span class="hireai-fp-hero__title-line hireai-fp__gold"><?php echo esc_html($hero_accent); ?></span>
        </h1>
        <?php if ($hero_subtitle) : ?>
        <p class="hireai-fp-hero__subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        <?php endif; ?>
        <div class="hireai-fp-hero__actions">
            <a class="hireai-fp__btn hireai-fp__btn--primary"
               href="<?php echo esc_url($hero_cta_1['url']); ?>"
               <?php if (!empty($hero_cta_1['target'])) : ?>target="<?php echo esc_attr($hero_cta_1['target']); ?>" rel="noopener"<?php endif; ?>>
                <?php echo esc_html($hero_cta_1['title']); ?>
            </a>
            <a class="hireai-fp__btn hireai-fp__btn--outline"
               href="<?php echo esc_url($hero_cta_2['url']); ?>"
               <?php if (!empty($hero_cta_2['target'])) : ?>target="<?php echo esc_attr($hero_cta_2['target']); ?>" rel="noopener"<?php endif; ?>>
                <?php echo esc_html($hero_cta_2['title']); ?>
            </a>
        </div>
    </div>
    <a class="hireai-fp-hero__scroll" href="#hireai-fp-intro" aria-label="<?php echo esc_attr($t['scroll']); ?>">
        <span aria-hidden="true"></span>
        <?php echo esc_html($t['scroll']); ?>
    </a>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     2) INTRO  ── Stitch 引言 section
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-intro" id="hireai-fp-intro">
    <?php if ($intro_kicker) : ?>
    <span class="hireai-fp__kicker"><?php echo esc_html($intro_kicker); ?></span>
    <?php endif; ?>
    <h2 class="hireai-fp-intro__title"><?php echo esc_html($intro_title); ?></h2>
    <?php if ($intro_desc) : ?>
    <p class="hireai-fp-intro__desc"><?php echo esc_html($intro_desc); ?></p>
    <?php endif; ?>
    <?php if ($intro_cta_label && $intro_cta_url) : ?>
    <div class="hireai-fp-intro__cta">
        <a class="hireai-fp__btn hireai-fp__btn--ghost" href="<?php echo esc_url($intro_cta_url); ?>">
            <?php echo esc_html($intro_cta_label); ?>
        </a>
    </div>
    <?php endif; ?>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     3) AI 数字员工  ── Stitch AI 数字员工 (3-col product grid)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-products" aria-labelledby="hireai-fp-products-title">
    <div class="hireai-fp-products__head">
        <div class="hireai-fp-products__head-text">
            <?php if ($prod_section_kicker) : ?>
            <span class="hireai-fp__kicker"><?php echo esc_html($prod_section_kicker); ?></span>
            <?php endif; ?>
            <h2 class="hireai-fp-products__title" id="hireai-fp-products-title"><?php echo esc_html($prod_section_title); ?></h2>
            <?php if ($prod_section_sub) : ?>
            <p class="hireai-fp-products__sub"><?php echo esc_html($prod_section_sub); ?></p>
            <?php endif; ?>
        </div>
        <div class="hireai-fp-products__nav" aria-hidden="true">
            <button type="button" aria-label="<?php echo esc_attr($t['prev']); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button type="button" aria-label="<?php echo esc_attr($t['next']); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
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
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     4) AI 解决方案  ── Stitch AI 解决方案 (2-col solutions grid)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-solutions" aria-labelledby="hireai-fp-solutions-title">
    <div class="hireai-fp-solutions__head">
        <div class="hireai-fp-solutions__head-text">
            <?php if ($sol_section_kicker) : ?>
            <span class="hireai-fp__kicker"><?php echo esc_html($sol_section_kicker); ?></span>
            <?php endif; ?>
            <h2 class="hireai-fp-solutions__title" id="hireai-fp-solutions-title"><?php echo esc_html($sol_section_title); ?></h2>
            <?php if ($sol_section_sub) : ?>
            <p class="hireai-fp-products__sub" style="margin-top:12px;"><?php echo esc_html($sol_section_sub); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="hireai-fp-solutions__grid">
        <?php foreach ($solutions as $sol) : if (empty($sol['title'])) continue; ?>
        <a class="hireai-fp-sol" href="<?php echo esc_url($sol['url']); ?>">
            <div class="hireai-fp-sol__media">
                <?php if (!empty($sol['img'])) : ?>
                <img class="hireai-fp-sol__img" src="<?php echo esc_url($sol['img']); ?>" alt="<?php echo esc_attr($sol['title']); ?>" loading="lazy" decoding="async">
                <?php else : ?>
                <div class="hireai-fp-sol__placeholder">
                    <?php if ($sol['icon'] === 'health') : ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    <?php else : ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3L12 3z"/></svg>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
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
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     5) 案例 & 洞察  ── Stitch 案例&洞察 (12-col grid: 8 major + 4 side)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-cases" aria-labelledby="hireai-fp-cases-title">
    <div class="hireai-fp-cases__head">
        <div class="hireai-fp-cases__head-text">
            <?php if ($cases_kicker) : ?>
            <span class="hireai-fp__kicker"><?php echo esc_html($cases_kicker); ?></span>
            <?php endif; ?>
            <h2 class="hireai-fp-cases__title" id="hireai-fp-cases-title"><?php echo esc_html($cases_title); ?></h2>
            <?php if ($cases_sub) : ?>
            <p class="hireai-fp-products__sub" style="margin-top:12px;"><?php echo esc_html($cases_sub); ?></p>
            <?php endif; ?>
        </div>
        <div class="hireai-fp-cases__nav" aria-hidden="true">
            <button type="button" aria-label="<?php echo esc_attr($t['prev']); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button type="button" aria-label="<?php echo esc_attr($t['next']); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>
    </div>
    <div class="hireai-fp-cases__grid">
        <a class="hireai-fp-case-major" href="<?php echo esc_url($major_case['url']); ?>">
            <div class="hireai-fp-case-major__media">
                <?php if (!empty($major_case['img'])) : ?>
                <img class="hireai-fp-case-major__img" src="<?php echo esc_url($major_case['img']); ?>" alt="<?php echo esc_attr($major_case['title']); ?>" loading="lazy" decoding="async">
                <?php endif; ?>
            </div>
            <div class="hireai-fp-case-major__body">
                <span class="hireai-fp-case-major__tag"><?php echo esc_html($major_case['label']); ?></span>
                <h3 class="hireai-fp-case-major__title"><?php echo esc_html($major_case['title']); ?></h3>
                <p class="hireai-fp-case-major__desc"><?php echo esc_html($major_case['desc']); ?></p>
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
        <a class="hireai-fp__btn hireai-fp__btn--primary" href="<?php echo esc_url($cases_explore_url); ?>">
            <?php echo esc_html($cases_explore_label); ?>
        </a>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     6) 常见问题  ── Stitch FAQ accordion
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-faq" id="hireai-fp-faq" aria-labelledby="hireai-fp-faq-title">
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
                 onclick="this.closest('.hireai-fp-faq-item').classList.toggle('is-open'); this.setAttribute('aria-expanded', this.closest('.hireai-fp-faq-item').classList.contains('is-open'));"
                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}">
                <h3 class="hireai-fp-faq-item__q"><?php echo esc_html($item['q']); ?></h3>
                <svg class="hireai-fp-faq-item__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="6 9 12 15 18 9"></polyline>
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
     7) CTA BAND  ── Stitch 大 CTA (kicker + title + desc + 2 buttons)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-cta" aria-labelledby="hireai-fp-cta-title">
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
            <a class="hireai-fp__btn hireai-fp__btn--outline" href="<?php echo esc_url($cta_btn_2_url); ?>" style="color:#fff;border-color:rgba(255,255,255,.35);background:rgba(255,255,255,.06);">
                <?php echo esc_html($cta_btn_2_title); ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
