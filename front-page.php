<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: HireAI Homepage
 * Stitch-matched v2.0.0 — inline CSS, local assets, no Tailwind dependency.
 */
get_header();

$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';
$home   = get_stylesheet_directory_uri();

/* ── Helper: 取当前语言字段/链接 ── */
$b = function ($name, $zh_default = '', $en_default = '') use ($is_en) {
    return hireai_field_lang($name, $is_en ? 'en' : 'zh', $is_en ? $en_default : $zh_default);
};
$bl = function ($name, $zh_url, $en_url, $zh_title, $en_title) use ($is_en) {
    return hireai_link_lang($name, $is_en ? 'en' : 'zh', $is_en ? $en_url : $zh_url, $is_en ? $en_title : $zh_title);
};
$fb = function ($item, $key) use ($is_en) {
    if (!isset($item[$key])) return '';
    $v = $item[$key];
    if (is_array($v)) {
        return isset($v[$is_en ? 'en' : 'zh']) ? $v[$is_en ? 'en' : 'zh'] : '';
    }
    return $v;
};

/* ═══════════════════════════════════════════════════════════════════════════
   ACF Data (双语：zh/en 双字段，前端 .zh/.en 双块切换)
   ═══════════════════════════════════════════════════════════════════════════ */

/* Hero */
$hero_image    = hireai_image('fp_hero_image', $home . '/assets/img/home/hero.png');
$hero_kicker   = $b('fp_hero_kicker', '工匠精神与算法', 'Prestige Digital Labor');
$hero_static   = $b('fp_hero_static', '重新定义', 'Redefine');
$hero_accent   = $b('fp_hero_accent', '数字劳动力', 'Digital Labor');
$hero_subtitle = $b('fp_hero_subtitle',
    '融合尖端科技与奢华质感，为您打造专属数字员工。',
    'Fusing cutting-edge technology with a luxurious aesthetic to craft your exclusive digital employees.');
$hero_cta_1_url   = $b('fp_hero_cta_1_url', '/ai-employees/', '/ai-employees/');
$hero_cta_1_title = $b('fp_hero_cta_1_title', '探索系列', 'EXPLORE SERIES');
$hero_cta_2_url   = $b('fp_hero_cta_2_url', '/contact/', '/contact/');
$hero_cta_2_title = $b('fp_hero_cta_2_title', '定制咨询', 'CONSULTATION');

/* Intro */
$intro_kicker = $b('fp_intro_kicker', '工匠精神与算法', 'Craftsmanship Meets Algorithm');
$intro_title  = $b('fp_intro_title', '塑造超越物理边界的存在。', 'Shaping existence beyond physical boundaries.');
$intro_desc   = $b('fp_intro_desc',
    '我们结合传统奢华的严谨工艺与神经网络的无限可能。每一位数字员工都是独一无二的杰作，专为优雅、智慧与共鸣而设计。',
    'We combine the rigor of traditional luxury with the infinite potential of neural networks. Every digital employee is a one-of-a-kind masterpiece, designed for elegance, intelligence, and resonance.');
$intro_cta_url   = hireai_field_lang('fp_intro_cta_url', 'zh', '/ai-employees/') ?: hireai_field_lang('fp_intro_cta_url', 'en', '/ai-employees/');
$intro_cta_title = $b('fp_intro_cta_title', '探索更多', 'Explore More');

/* Products */
$products_section_kicker = $b('fp_products_kicker', '限量神经元系列', 'Limited Neural Series');
$products_section_title  = $b('fp_products_title', 'AI 数字员工', 'AI Digital Employees');
$products_section_sub    = $b('fp_products_subtitle',
    '每一位数字员工都拥有独特的灵魂、技能与能力，随时加入您的团队。',
    'Each digital employee brings a unique soul, refined skills, and unmatched capabilities.');
$products_explore_label  = $b('fp_products_explore_label', '探索更多', 'Explore More');
$products_explore_url    = home_url('/ai-employees/');

$products = [
    [
        'title' => $b('fp_prod1_title', 'Aurelian Prime', 'Aurelian Prime'),
        'desc'  => $b('fp_prod1_desc', '精英女性数字分身', 'Elite female digital avatar'),
        'badge' => $b('fp_prod1_badge', '限量 01/50', 'Edition 01/50'),
        'img'   => hireai_image('fp_prod1_image', $home . '/assets/img/home/product-prime.png'),
        'url'   => hireai_field('fp_prod1_url', home_url('/ai-employees/')),
        'btn'   => $b('fp_prod1_btn', '探索更多', 'Explore More'),
    ],
    [
        'title' => $b('fp_prod2_title', 'Aurelian Executive', 'Aurelian Executive'),
        'desc'  => $b('fp_prod2_desc', '权威与外交协议', 'Authority & diplomacy protocol'),
        'badge' => $b('fp_prod2_badge', 'Executive Series', 'Executive Series'),
        'img'   => hireai_image('fp_prod2_image', $home . '/assets/img/home/product-exec.png'),
        'url'   => hireai_field('fp_prod2_url', home_url('/ai-employees/')),
        'btn'   => $b('fp_prod2_btn', '探索更多', 'Explore More'),
    ],
    [
        'title' => $b('fp_prod3_title', 'Neural Sales Core', 'Neural Sales Core'),
        'desc'  => $b('fp_prod3_desc', '企业级AI优化', 'Enterprise-grade AI optimization'),
        'badge' => $b('fp_prod3_badge', 'Neural Series', 'Neural Series'),
        'img'   => hireai_image('fp_prod3_image', $home . '/assets/img/home/product-neural.png'),
        'url'   => hireai_field('fp_prod3_url', home_url('/ai-employees/')),
        'btn'   => $b('fp_prod3_btn', '探索更多', 'Explore More'),
    ],
];

/* Solutions */
$solutions_kicker = $b('fp_solutions_kicker', '行业赋能', 'Industry Empowerment');
$solutions_title  = $b('fp_solutions_title', 'AI 解决方案', 'AI Solutions');
$solutions_sub    = $b('fp_solutions_subtitle', '面向多个行业的量身定制智能方案。', 'Bespoke intelligent solutions across industries.');
$solutions_explore_label = $b('fp_solutions_explore_label', '探索更多', 'Explore More');
$solutions_explore_url   = home_url('/ai-solutions/');

$solutions = [
    [
        'title' => $b('fp_sol1_title', '金融与财富管理', 'Finance & Wealth Management'),
        'desc'  => $b('fp_sol1_desc', '智能顾问与客户关系维护的数字化重塑。', 'Digital reshaping of intelligent advisors and client relationship management.'),
        'img'   => hireai_image('fp_sol1_image', $home . '/assets/img/home/solution-finance.png'),
    ],
    [
        'title' => $b('fp_sol2_title', '高端零售与电商', 'Premium Retail & E-commerce'),
        'desc'  => $b('fp_sol2_desc', '24/7全天候奢华购物体验升级。', '24/7 all-day luxury shopping experience upgrade.'),
        'img'   => hireai_image('fp_sol2_image', $home . '/assets/img/home/solution-retail.png'),
    ],
    [
        'title' => $b('fp_sol3_title', '医疗健康与陪伴', 'Healthcare & Companionship'),
        'desc'  => $b('fp_sol3_desc', '充满同理心的智能关怀与健康咨询。', 'Empathetic intelligent care and health consultation.'),
        'img'   => hireai_image('fp_sol3_image', ''),
        'icon'  => 'health_and_safety',
    ],
    [
        'title' => $b('fp_sol4_title', '泛娱乐与虚拟偶像', 'Entertainment & Virtual Idols'),
        'desc'  => $b('fp_sol4_desc', '打造永不塌房的超级IP与互动体验。', 'Build the ultimate never-fail super IP and interactive experience.'),
        'img'   => hireai_image('fp_sol4_image', ''),
        'icon'  => 'auto_awesome',
    ],
];

/* Cases & Insights */
$cases_kicker = $b('fp_cases_kicker', '前沿视野', 'Frontier Vision');
$cases_title  = $b('fp_cases_title', '案例 & 洞察', 'Cases & Insights');
$cases_sub    = $b('fp_cases_subtitle', '见证数字员工如何改变企业的运营方式。', 'See how digital employees transform operations.');
$cases_explore_label = $b('fp_cases_explore_label', '探索更多', 'Explore More');
$cases_explore_url   = home_url('/cases-insights/');

$major_case = [
    'label' => $b('fp_case_major_label', '案例研究', 'CASE STUDY'),
    'title' => $b('fp_case_major_title', 'Aurelian Prime 在私人银行的应用', 'Aurelian Prime in Private Banking'),
    'desc'  => $b('fp_case_major_desc', '了解我们的顶级数字员工如何提升高净值客户的留存率与满意度。', 'Learn how our top digital employee boosts retention and satisfaction for high-net-worth clients.'),
    'img'   => hireai_image('fp_case_major_image', $home . '/assets/img/home/solution-finance.png'),
];

$side_cases = [
    [
        'tag'   => $b('fp_case1_tag', '案例研究', 'CASE STUDY'),
        'title' => $b('fp_case1_title', '电商视觉革命：转化率提升55%', 'E-commerce Visual Revolution: +55% Conversion'),
        'desc'  => $b('fp_case1_desc', '重塑线上购物体验，结合虚拟试穿与个性化推荐带来的商业增长。', 'Reshaping online shopping with virtual try-on and personalized recommendations.'),
        'img'   => hireai_image('fp_case1_image', $home . '/assets/img/home/product-prime.png'),
    ],
    [
        'tag'   => $b('fp_case2_tag', '深度洞察', 'DEEP INSIGHT'),
        'title' => $b('fp_case2_title', '"未来不再仅仅是代码，更是交响乐。"', '"The future is no longer just code, but a symphony."'),
        'desc'  => $b('fp_case2_desc', '探讨数字人性化的趋势，以及我们在构建有温度的AI方面的思考与实践。', 'Exploring the trend of humanized digital beings and our approach to building warm AI.'),
        'img'   => hireai_image('fp_case2_image', $home . '/assets/img/home/solution-retail.png'),
    ],
];

/* FAQ */
$faq_kicker = $b('fp_faq_kicker', '常见问题', 'FAQ');
$faq_title  = $b('fp_faq_title', '解答关于数字员工的疑虑，开启智能新纪元。', 'Answers to your questions about digital employees.');
$faq_explore_label = $b('fp_faq_explore_label', '探索更多', 'Explore More');
$faq_explore_url   = home_url('/faq/');

$fallback_faq = [
    [
         'question' => $b('fp_faq1_q', '定制一位数字员工需要多长时间？', 'How long does it take to customize a digital employee?'),
         'answer' => $b('fp_faq1_a', '这取决于定制的复杂程度。基础模型微调通常需要2-4周，而完全定制化可能需要8-12周。', 'This depends on the complexity of the customization. Basic model fine-tuning typically takes 2-4 weeks, while full customization may require 8-12 weeks.'),
    ],
    [
         'question' => $b('fp_faq2_q', '数字员工的知识库可以实时更新吗？', "Can a digital employee's knowledge base be updated in real-time?"),
         'answer' => $b('fp_faq2_a', '是的，我们的系统支持通过API进行实时知识库更新。', 'Yes, our system supports real-time knowledge base updates via API.'),
    ],
    [
         'question' => $b('fp_faq3_q', '如何保障数据隐私与安全？', 'How do you ensure data privacy and security?'),
         'answer' => $b('fp_faq3_a', '我们采用企业级加密标准，所有交互数据均在本地或专属私有云中处理。', 'We employ enterprise-grade encryption standards. All interaction data is processed in local or dedicated private clouds.'),
    ],
];

/* CTA band */
$cta_title = $b('fp_cta_title', '开启您的 AI 雇佣之旅', 'Begin Your AI Hiring Journey');
$cta_desc  = $b('fp_cta_desc', '与我们的团队对话，打造专属您的数字员工阵容。', 'Speak with our team and craft a digital workforce made for you.');
$cta_btn_title = $b('fp_cta_btn_title', '联系我们', 'Contact Us');
$cta_btn_url   = hireai_field_lang('fp_cta_btn_url', 'zh', '/contact/') ?: hireai_field_lang('fp_cta_btn_url', 'en', '/contact/');

?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&amp;display=swap">
<style>
/* ═══════════════════════════════════════════════════════════════════════════
   HireAI Homepage v1.9.0 — Stitch-matched inline styles
   ═══════════════════════════════════════════════════════════════════════════ */

/* ── Scoped tokens ── */
.hireai-fp {
    --gold:       #775a19;
    --gold-light: #e9c176;
    --gold-pale:  #ffdea5;
    --surface:    #faf9f9;
    --black:      #000;
    --text-secondary: #444748;
    --border-light:   rgba(196,199,199,0.3);
    --border-gold:    rgba(119,90,25,0.3);
    --section-pad: 120px 80px;
    --side-pad:    80px;
    --max-w: 1440px;
}

.hireai-fp, .hireai-fp * { box-sizing: border-box; margin: 0; padding: 0; }
.hireai-fp {
    overflow-x: hidden;
    background: var(--surface);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #1a1c1c;
    line-height: 1.6;
}
.hireai-fp img { display: block; max-width: 100%; }
.hireai-fp a  { text-decoration: none; color: inherit; }

@media (max-width: 767px) {
    .hireai-fp { --section-pad: 80px 20px; --side-pad: 20px; }
}

/* ── Shared: burnished gold text ── */
.hireai-fp__burnished {
    background: linear-gradient(135deg, #e9c176 0%, #775a19 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* ── Shared: section utility ── */
.hireai-fp__section {
    padding: var(--section-pad);
    max-width: var(--max-w);
    margin: 0 auto;
}

/* ── Shared: pill button ── */
.hireai-fp__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', sans-serif;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 16px 48px;
    border-radius: 9999px;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    white-space: nowrap;
}
.hireai-fp__btn--primary {
    background: #000;
    color: #fff;
}
.hireai-fp__btn--primary:hover {
    box-shadow: 0 0 20px rgba(119,90,25,0.4);
}
.hireai-fp__btn--outline {
    background: rgba(255,255,255,0.5);
    backdrop-filter: blur(8px);
    color: var(--gold);
    border: 1px solid var(--border-gold);
}
.hireai-fp__btn--outline:hover {
    background: rgba(119,90,25,0.08);
}

/* ── Shared: section header ── */
.hireai-fp__section-label {
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    line-height: 1.2;
    letter-spacing: 0.3em;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gold);
}
.hireai-fp__section-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(28px, 3.5vw, 44px);
    line-height: 1.2;
    font-weight: 600;
    color: #000;
    margin-top: 16px;
}
@media (max-width: 767px) {
    .hireai-fp__section-title { font-size: clamp(24px, 4.5vw, 32px); }
}


/* ── Section spacing ── */
.hireai-fp__section + .hireai-fp__section { margin-top: 160px; }
@media (max-width: 767px) {
    .hireai-fp__section + .hireai-fp__section { margin-top: 80px; }
}

/* ══════════════════════════════════════════════════════════════════════════
   HERO
   ══════════════════════════════════════════════════════════════════════════ */
.hireai-fp-hero {
    position: relative;
    min-height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: var(--surface);
}
.hireai-fp-hero__bg {
    position: absolute;
    inset: 0;
    z-index: 0;
}
.hireai-fp-hero__bg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hireai-fp-hero__gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, var(--surface) 0%, transparent 50%, transparent 100%);
    z-index: 1;
}
.hireai-fp-hero__content {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 62vh var(--side-pad) 80px;
    max-width: 44.8rem;
    width: 100%;
}
.hireai-fp-hero__title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(24px, 3.4vw, 38px);
    line-height: 1.1;
    letter-spacing: -0.02em;
    font-weight: 700;
    color: var(--black);
    margin-bottom: 24px;
}
@media (max-width: 767px) {
    .hireai-fp-hero__title { font-size: clamp(20px, 3.6vw, 27px); }
    .hireai-fp-hero__content { padding-top: 62vh; }
}
.hireai-fp-hero__title em {
    font-style: italic;
    font-weight: normal;
}
.hireai-fp-hero__subtitle {
    font-family: 'Inter', sans-serif;
    font-size: clamp(10px, 1.1vw, 13px);
    line-height: 1.6;
    color: rgba(0,0,0,0.65);
    letter-spacing: 0.05em;
    text-transform: uppercase;
    font-weight: 300;
    margin-bottom: 38px;
    max-width: 576px;
    margin-left: auto;
    margin-right: auto;
}
.hireai-fp-hero__actions {
    display: flex;
    gap: 24px;
    justify-content: center;
    flex-wrap: wrap;
}
.hireai-fp-hero__scroll {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
}
.hireai-fp-hero__scroll span {
    display: block;
    width: 24px;
    height: 24px;
    border-right: 2px solid rgba(0,0,0,0.25);
    border-bottom: 2px solid rgba(0,0,0,0.25);
    transform: rotate(45deg);
    animation: hireai-fp-bounce 2s infinite;
}
@keyframes hireai-fp-bounce {
    0%, 100% { transform: rotate(45deg) translateY(0); }
    50%      { transform: rotate(45deg) translateY(8px); }
}

/* ══════════════════════════════════════════════════════════════════════════
   INTRO
   ══════════════════════════════════════════════════════════════════════════ */
.hireai-fp-intro {
    background: var(--surface);
    text-align: center;
    max-width: var(--max-w);
    margin: 0 auto;
}
.hireai-fp-intro__title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(28px, 3.5vw, 48px);
    line-height: 1.2;
    font-weight: 600;
    color: var(--black);
    margin-bottom: 32px;
}
@media (max-width: 767px) {
    .hireai-fp-intro__title { font-size: clamp(24px, 4vw, 32px); }
}
.hireai-fp-intro__desc {
    font-family: 'Inter', sans-serif;
    font-size: clamp(14px, 1.2vw, 18px);
    line-height: 1.6;
    color: var(--text-secondary);
    max-width: 720px;
    margin: 0 auto;
}

/* ══════════════════════════════════════════════════════════════════════════
   PRODUCTS
   ══════════════════════════════════════════════════════════════════════════ */
.hireai-fp-products {
    background: #fff;
    max-width: 1200px;
    margin: 0 auto;
    padding-inline: 80px;
}
@media (max-width: 767px) { .hireai-fp-products { padding-inline: 24px; } }
.hireai-fp-products__header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 80px;
    padding-bottom: 48px;
    border-bottom: 1px solid var(--border-light);
}
@media (max-width: 767px) {
    .hireai-fp-products__header { flex-direction: column; align-items: flex-start; gap: 20px; }
}
.hireai-fp-products__grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 20px;
}
@media (min-width: 768px) {
    .hireai-fp-products__grid { grid-template-columns: repeat(3, 1fr); }
}
.hireai-fp-product-card {
    display: flex;
    flex-direction: column;
    transition: transform 0.6s ease-out;
    background: rgba(249, 248, 243, 0.7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(119, 90, 25, 0.15);
    border-radius: 0.5rem;
    padding: 20px;
}
.hireai-fp-product-card__img-wrap {
    position: relative;
    aspect-ratio: 3/4;
    overflow: hidden;
    margin-bottom: 24px;
    border: 1px solid rgba(196,199,199,0.2);
    background: #eee;
    transition: border-color 0.5s;
    border-radius: 0.5rem;
}
.hireai-fp-product-card:hover .hireai-fp-product-card__img-wrap {
    border-color: var(--gold-light);
}
.hireai-fp-product-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s ease-out;
}
.hireai-fp-product-card:hover .hireai-fp-product-card__img {
    transform: scale(1.05);
}
.hireai-fp-product-card__badge {
    position: absolute;
    top: 16px;
    right: 16px;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 4px 16px;
    border-radius: 9999px;
    border: 1px solid rgba(0,0,0,0.06);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #000;
}
.hireai-fp-product-card__info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.hireai-fp-product-card__name {
    font-family: 'Playfair Display', serif;
    font-size: clamp(22px, 2.4vw, 32px);
    line-height: 1.3;
    font-weight: 500;
    color: var(--black);
    margin-bottom: 8px;
}
.hireai-fp-product-card__desc {
    font-family: 'Inter', sans-serif;
    font-size: clamp(13px, 1.1vw, 15px);
    line-height: 1.6;
    color: var(--text-secondary);
    margin-bottom: 16px;
}
.hireai-fp-product-card .hireai-fp__btn {
    width: 100%;
    margin-top: auto;
    padding: 16px 0;
    font-size: 12px;
    border-radius: 9999px;
}

/* ══════════════════════════════════════════════════════════════════════════
   SOLUTIONS
   ══════════════════════════════════════════════════════════════════════════ */
.hireai-fp-solutions {
    background: var(--surface);
    overflow: hidden;
    max-width: 1200px;
    margin: 0 auto;
    padding-inline: 80px;
}
@media (max-width: 767px) { .hireai-fp-solutions { padding-inline: 24px; } }
.hireai-fp-solutions__header {
    max-width: 640px;
    margin-bottom: 64px;
}
.hireai-fp-solutions__grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 20px;
}
@media (min-width: 768px) {
    .hireai-fp-solutions__grid { grid-template-columns: repeat(2, 1fr); }
}
.hireai-fp-sol-card {
    position: relative;
    aspect-ratio: 16/9;
    overflow: hidden;
    border: 1px solid rgba(196,199,199,0.2);
    transition: border-color 0.3s;
    border-radius: 0.5rem;
}
.hireai-fp-sol-card:hover {
    border-color: var(--gold);
}
.hireai-fp-sol-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease-out;
}
.hireai-fp-sol-card:hover .hireai-fp-sol-card__img {
    transform: scale(1.05);
}
.hireai-fp-sol-card__icon-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e2e2e2;
}
.hireai-fp-sol-card__icon {
    width: 72px;
    height: 72px;
    color: var(--gold);
    opacity: 0.55;
}
.hireai-fp-sol-card__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.2) 50%, transparent);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 32px;
    color: #fff;
}
.hireai-fp-sol-card__title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(18px, 1.8vw, 24px);
    line-height: 1.3;
    font-weight: 500;
    margin-bottom: 8px;
}
.hireai-fp-sol-card__desc {
    font-family: 'Inter', sans-serif;
    font-size: clamp(13px, 1.1vw, 15px);
    line-height: 1.6;
    opacity: 0.8;
}
.hireai-fp-solutions__cta {
    text-align: center;
    margin-top: 32px;
}

/* ══════════════════════════════════════════════════════════════════════════
   CASES
   ══════════════════════════════════════════════════════════════════════════ */
.hireai-fp-cases {
    background: var(--surface);
    padding: var(--section-pad);
    max-width: 1200px;
    margin: 160px auto 0;
    border-top: 1px solid var(--border-light);
    padding-inline: 80px;
}
@media (max-width: 767px) {
    .hireai-fp-cases { margin-top: 80px; padding-inline: 24px; }
}
.hireai-fp-cases__header {
    display: flex;
    flex-direction: column;
    gap: 32px;
    margin-bottom: 64px;
    padding-bottom: 48px;
    border-bottom: 1px solid var(--border-light);
}
@media (min-width: 768px) {
    .hireai-fp-cases__header {
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-end;
    }
}
@media (min-width: 768px) {
    .hireai-fp-cases__header {
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-end;
    }
}
.hireai-fp-cases__grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 32px;
}
@media (min-width: 768px) {
    .hireai-fp-cases__grid {
        grid-template-columns: repeat(12, 1fr);
    }
}
/* Major case */
.hireai-fp-case-major {
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(196,199,199,0.2);
    border-radius: 0.5rem;
}
@media (min-width: 768px) {
    .hireai-fp-case-major { grid-column: span 8; }
}
.hireai-fp-case-major__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s ease-out;
}
.hireai-fp-case-major:hover .hireai-fp-case-major__img {
    transform: scale(1.05);
}
.hireai-fp-case-major__label {
    display: block;
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: var(--gold);
    margin-bottom: 8px;
}
.hireai-fp-case-major__title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(22px, 2.4vw, 32px);
    line-height: 1.3;
    font-weight: 500;
    color: var(--black);
    margin-bottom: 16px;
    transition: color 0.3s;
}
.hireai-fp-case-major:hover .hireai-fp-case-major__title {
    color: var(--gold);
}
.hireai-fp-case-major__desc {
    font-family: 'Inter', sans-serif;
    font-size: clamp(13px, 1.1vw, 15px);
    line-height: 1.6;
    color: var(--text-secondary);
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
/* Side cases */
.hireai-fp-cases__side {
    display: flex;
    flex-direction: column;
    gap: 32px;
}
@media (min-width: 768px) {
    .hireai-fp-cases__side { grid-column: span 4; }
}
.hireai-fp-case-side-card {
    display: flex;
    flex-direction: column;
}
.hireai-fp-case-side-card__img-wrap {
    aspect-ratio: 16/9;
    overflow: hidden;
    border: 1px solid rgba(196,199,199,0.2);
    margin-bottom: 16px;
    border-radius: 0.5rem;
}
.hireai-fp-case-side-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s ease-out;
}
.hireai-fp-case-side-card:hover .hireai-fp-case-side-card__img {
    transform: scale(1.05);
}
.hireai-fp-case-side-card__tag {
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: var(--gold);
    margin-bottom: 8px;
}
.hireai-fp-case-side-card__title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(17px, 1.5vw, 20px);
    line-height: 1.3;
    font-weight: 500;
    color: var(--black);
    margin-bottom: 8px;
    transition: color 0.3s;
}
.hireai-fp-case-side-card:hover .hireai-fp-case-side-card__title {
    color: var(--gold);
}
.hireai-fp-case-side-card__desc {
    font-family: 'Inter', sans-serif;
    font-size: clamp(13px, 1.1vw, 14px);
    line-height: 1.6;
    color: var(--text-secondary);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.hireai-fp-cases__cta {
    text-align: center;
    margin-top: 48px;
}

/* ══════════════════════════════════════════════════════════════════════════
   FAQ
   ══════════════════════════════════════════════════════════════════════════ */
.hireai-fp-faq {
    background: var(--surface);
    border-top: 1px solid var(--border-light);
    text-align: center;
    margin-top: 160px;
}
@media (max-width: 767px) {
    .hireai-fp-faq { margin-top: 80px; }
}
.hireai-fp-faq__subtitle {
    font-family: 'Inter', sans-serif;
    font-size: clamp(14px, 1.2vw, 18px);
    line-height: 1.6;
    color: var(--text-secondary);
    margin-top: 16px;
}
.hireai-fp-faq__list {
    max-width: 44.8rem;
    margin: 160px auto 0;
    text-align: left;
}
.hireai-fp-faq-item {
    border-bottom: 1px solid var(--border-light);
    padding-bottom: 0;
}
.hireai-fp-faq-item__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 16px 0;
    cursor: pointer;
}
.hireai-fp-faq-item__header:hover .hireai-fp-faq-item__question {
    color: var(--gold);
}
.hireai-fp-faq-item__question {
    font-family: 'Playfair Display', serif;
    font-size: clamp(18px, 2.2vw, 28px);
    line-height: 1.3;
    font-weight: 500;
    color: var(--black);
    flex: 1;
    transition: color 0.3s;
}
@media (max-width: 767px) {
    .hireai-fp-faq-item__question { font-size: clamp(17px, 3.5vw, 22px); }
}
.hireai-fp-faq-item__icon {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    color: var(--text-secondary);
    transition: transform 0.3s;
}
.hireai-fp-faq-item.is-active .hireai-fp-faq-item__icon {
    transform: rotate(180deg);
}
.hireai-fp-faq-item__body {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease-out;
}
.hireai-fp-faq-item.is-active .hireai-fp-faq-item__body {
    max-height: 500px;
}
.hireai-fp-faq-item__answer {
    padding-bottom: 16px;
    font-family: 'Inter', sans-serif;
    font-size: clamp(13px, 1.1vw, 16px);
    line-height: 1.6;
    color: rgba(68,71,72,0.8);
}
.hireai-fp-faq__cta {
    text-align: center;
    margin-top: 48px;
}

/* ══ CTA band ══ */
.hireai-fp-cta {
    background: var(--black);
    margin: 160px auto 0;
    padding: clamp(80px, 10vw, 160px) var(--side-pad);
    max-width: 1200px;
    text-align: center;
    color: #fff;
}
@media (max-width: 767px) {
    .hireai-fp-cta { margin-top: 80px; }
}
.hireai-fp-cta__inner {
    max-width: 640px;
    margin: 0 auto;
}
.hireai-fp-cta__title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(30px, 4vw, 52px);
    line-height: 1.2;
    font-weight: 600;
    margin-bottom: 24px;
}
.hireai-fp-cta__desc {
    font-family: 'Inter', sans-serif;
    font-size: clamp(15px, 1.2vw, 18px);
    line-height: 1.6;
    color: rgba(255,255,255,0.7);
    margin-bottom: 40px;
}
.hireai-fp-cta__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', sans-serif;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 16px 48px;
    border-radius: 9999px;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    background: linear-gradient(135deg, #e9c176 0%, #775a19 100%);
    color: #fff;
    transition: all 0.3s;
    border: none;
}
.hireai-fp-cta__btn:hover {
    box-shadow: 0 0 30px rgba(233,193,118,0.35);
}
</style>

<!-- ══════════════════════════════════════════════════════════════════════════
     HERO
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-hero">
    <div class="hireai-fp-hero__bg">
        <?php if ($hero_image) : ?>
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($is_en ? 'AI Digital Employees' : 'AI数字员工'); ?>">
        <?php endif; ?>
    </div>
    <div class="hireai-fp-hero__gradient"></div>
    <div class="hireai-fp-hero__content">
        <?php if ($hero_kicker) : ?>
        <p class="hireai-fp__section-label" style="margin-bottom:24px;display:block;"><?php echo esc_html($hero_kicker); ?></p>
        <?php endif; ?>
        <h1 class="hireai-fp-hero__title">
            <?php echo esc_html($hero_static); ?>
            <em class="hireai-fp__burnished"><?php echo esc_html($hero_accent); ?></em>
        </h1>
        <?php if ($hero_subtitle) : ?>
        <p class="hireai-fp-hero__subtitle"><?php echo wp_kses_post($hero_subtitle); ?></p>
        <?php endif; ?>
        <div class="hireai-fp-hero__actions">
            <a class="hireai-fp__btn hireai-fp__btn--primary"
               href="<?php echo esc_url($hero_cta_1_url); ?>">
                <?php echo esc_html($hero_cta_1_title); ?>
            </a>
            <a class="hireai-fp__btn hireai-fp__btn--outline"
               href="<?php echo esc_url($hero_cta_2_url); ?>">
                <?php echo esc_html($hero_cta_2_title); ?>
            </a>
        </div>
    </div>
    <div class="hireai-fp-hero__scroll"><span></span></div>
</section>

<!-- ══════════════════════════════════════════════════════════════════════════
     INTRODUCTION
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp__section">
    <div class="hireai-fp-intro">
        <?php if ($intro_kicker) : ?>
        <p class="hireai-fp__section-label" style="margin-bottom:16px;display:block;"><?php echo esc_html($intro_kicker); ?></p>
        <?php endif; ?>
        <h2 class="hireai-fp-intro__title"><?php echo esc_html($intro_title); ?></h2>
        <?php if ($intro_desc) : ?>
        <p class="hireai-fp-intro__desc"><?php echo wp_kses_post($intro_desc); ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════════════════
     PRODUCTS (AI 数字员工)
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-products hireai-fp__section">
    <div class="hireai-fp-products__header">
        <div>
            <h2 class="hireai-fp__section-title hireai-fp__burnished"><?php echo esc_html($products_section_title); ?></h2>
            <?php if ($products_section_kicker) : ?>
            <p class="hireai-fp__section-label" style="text-transform:uppercase;margin-top:8px;display:block;"><?php echo esc_html($products_section_kicker); ?></p>
            <?php endif; ?>
            <a href="<?php echo esc_url($products_explore_url); ?>"
               style="display:inline-block;margin-top:16px;font-family:'Inter',sans-serif;font-size:14px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:#000;border-bottom:1px solid rgba(0,0,0,0.2);padding-bottom:4px;transition:all 0.3s;">
                <?php echo esc_html($products_explore_label); ?>
            </a>
        </div>
    </div>
    <div class="hireai-fp-products__grid">
        <?php foreach ($products as $prod) : ?>
        <div class="hireai-fp-product-card">
            <div class="hireai-fp-product-card__img-wrap">
                <img class="hireai-fp-product-card__img"
                     src="<?php echo esc_url($prod['img']); ?>"
                     alt="<?php echo esc_attr($prod['title']); ?>">
                <?php if (!empty($prod['badge'])) : ?>
                <span class="hireai-fp-product-card__badge"><?php echo esc_html($prod['badge']); ?></span>
                <?php endif; ?>
            </div>
            <div class="hireai-fp-product-card__info">
                <div>
                    <h3 class="hireai-fp-product-card__name"><?php echo esc_html($prod['title']); ?></h3>
                    <p class="hireai-fp-product-card__desc"><?php echo esc_html($prod['desc']); ?></p>
                </div>
            </div>
            <a class="hireai-fp__btn hireai-fp__btn--outline"
               href="<?php echo esc_url($prod['url']); ?>">
                <?php echo esc_html($prod['btn']); ?>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════════════════
     AI SOLUTIONS
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-solutions hireai-fp__section">
    <div class="hireai-fp-solutions__header">
        <p class="hireai-fp__section-label" style="margin-bottom:16px;display:block;"><?php echo esc_html($solutions_kicker); ?></p>
        <h2 class="hireai-fp__section-title hireai-fp__burnished"><?php echo esc_html($solutions_title); ?></h2>
    </div>
    <div class="hireai-fp-solutions__grid">
        <?php foreach ($solutions as $sol) : ?>
        <div class="hireai-fp-sol-card">
            <?php if (!empty($sol['img'])) : ?>
            <img class="hireai-fp-sol-card__img"
                 src="<?php echo esc_url($sol['img']); ?>"
                 alt="<?php echo esc_attr($fb($sol, 'title')); ?>">
            <?php elseif (!empty($sol['icon'])) : ?>
            <div class="hireai-fp-sol-card__icon-placeholder">
                <svg class="hireai-fp-sol-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <?php if ($sol['icon'] === 'health_and_safety') : ?>
                    <path d="M12 3 5 6v5c0 4.2 2.9 7.7 7 9 4.1-1.3 7-4.8 7-9V6l-7-3z"/>
                    <path d="m9 12 2 2 4-4"/>
                    <?php else : ?>
                    <path d="M12 3l2.3 5.2L20 9l-4.2 3.7L17.3 19 12 15.8 6.7 19l1.5-6.3L4 9l5.7-.8z"/>
                    <?php endif; ?>
                </svg>
            </div>
            <?php endif; ?>
            <div class="hireai-fp-sol-card__overlay">
                <h3 class="hireai-fp-sol-card__title"><?php echo esc_html($fb($sol, 'title')); ?></h3>
                <p class="hireai-fp-sol-card__desc"><?php echo esc_html($fb($sol, 'desc')); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="hireai-fp-solutions__cta">
        <a class="hireai-fp__btn hireai-fp__btn--primary"
           href="<?php echo esc_url($solutions_explore_url); ?>">
            <?php echo esc_html($solutions_explore_label); ?>
        </a>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════════════════
     CASES & INSIGHTS
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-cases">
    <div class="hireai-fp-cases__header">
        <div>
            <p class="hireai-fp__section-label" style="margin-bottom:16px;display:block;"><?php echo esc_html($cases_kicker); ?></p>
            <h2 class="hireai-fp__section-title hireai-fp__burnished"><?php echo esc_html($cases_title); ?></h2>
        </div>
    </div>
    <div class="hireai-fp-cases__grid">
        <!-- Major Case -->
        <div class="hireai-fp-case-major">
            <img class="hireai-fp-case-major__img"
                 src="<?php echo esc_url($major_case['img']); ?>"
                 alt="<?php echo esc_attr($major_case['title']); ?>">
            <div style="padding:32px;">
                <span class="hireai-fp-case-major__label"><?php echo esc_html($is_en ? 'CASE STUDY' : '案例研究'); ?></span>
                <h3 class="hireai-fp-case-major__title"><?php echo esc_html($major_case['title']); ?></h3>
                <p class="hireai-fp-case-major__desc"><?php echo esc_html($major_case['desc']); ?></p>
            </div>
        </div>
        <!-- Side Cases -->
        <div class="hireai-fp-cases__side">
            <?php foreach ($side_cases as $sc) : ?>
            <div class="hireai-fp-case-side-card">
                <div class="hireai-fp-case-side-card__img-wrap">
                    <?php if (!empty($sc['img'])) : ?>
                    <img class="hireai-fp-case-side-card__img"
                         src="<?php echo esc_url($sc['img']); ?>"
                         alt="<?php echo esc_attr($sc['title']); ?>">
                    <?php endif; ?>
                </div>
                <span class="hireai-fp-case-side-card__tag"><?php echo esc_html($sc['tag']); ?></span>
                <h4 class="hireai-fp-case-side-card__title"><?php echo esc_html($sc['title']); ?></h4>
                <p class="hireai-fp-case-side-card__desc"><?php echo esc_html($sc['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="hireai-fp-cases__cta">
        <a class="hireai-fp__btn hireai-fp__btn--primary"
           href="<?php echo esc_url($cases_explore_url); ?>">
            <?php echo esc_html($cases_explore_label); ?>
        </a>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════════════════
     FAQ
     ══════════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp-faq hireai-fp__section" id="faq">
    <h2 class="hireai-fp__section-title hireai-fp__burnished"><?php echo esc_html($faq_kicker); ?></h2>
    <?php if ($faq_title) : ?>
    <p class="hireai-fp-faq__subtitle"><?php echo wp_kses_post($faq_title); ?></p>
    <?php endif; ?>
    <div class="hireai-fp-faq__list">
        <?php foreach ($fallback_faq as $item) : ?>
        <div class="hireai-fp-faq-item">
            <div class="hireai-fp-faq-item__header" onclick="this.closest('.hireai-fp-faq-item').classList.toggle('is-active')">
                <h4 class="hireai-fp-faq-item__question"><?php echo esc_html($item['question']); ?></h4>
                <svg class="hireai-fp-faq-item__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </div>
            <div class="hireai-fp-faq-item__body">
                <div class="hireai-fp-faq-item__answer">
                    <p><?php echo wp_kses_post($item['answer']); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="hireai-fp-faq__cta">
        <a class="hireai-fp__btn hireai-fp__btn--primary"
           href="<?php echo esc_url($faq_explore_url); ?>">
            <?php echo esc_html($faq_explore_label); ?>
        </a>
    </div>
</section>


<?php get_footer(); ?>
