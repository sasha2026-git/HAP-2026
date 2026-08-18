<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: HireAI Homepage
 * Stitch-matched design with inline CSS. No Tailwind dependency.
 */
get_header();

$suffix   = hireai_lang_suffix();
$is_en    = $suffix === '_en';
$fallback = function ($item, $key) use ($is_en) {
    $value = isset($item[$key]) ? $item[$key] : '';
    if (is_array($value)) {
        return isset($value[$is_en ? 'en' : 'zh']) ? $value[$is_en ? 'en' : 'zh'] : '';
    }
    return $value;
};

/* ── ACF data ── */
$hero_image    = hireai_image('fp_hero_image', get_template_directory_uri() . '/assets/img/defaults/hero-home.jpg');
$hero_kicker   = hireai_field('fp_hero_kicker', 'Prestige Digital Labor');
$hero_title    = hireai_field('fp_hero_title', 'HireAIPeople');
$hero_subtitle = hireai_field('fp_hero_subtitle', 'Neural Craftsmanship &amp; Digital Excellence. Leading the future of AI service with artistic soul. 将艺术灵魂注入神经算法，打造具备卓越品味的数字生产力。');
$hero_cta_1    = hireai_link('fp_hero_cta_1', home_url('/contact/'), $is_en ? 'Consult Now' : '立即咨询');
$hero_cta_2    = hireai_link('fp_hero_cta_2', home_url('/cases-insights/'), $is_en ? 'View Cases' : '查看案例');

$emp_kicker = hireai_field('fp_emp_kicker', 'The Digital Atelier');
$emp_title  = hireai_field('fp_emp_title', $is_en ? 'AI Digital Workers' : 'AI数字员工矩阵');
$employees  = [
    [
        'kicker' => hireai_field('fp_emp1_kicker', 'Public Relations'),
        'title'  => hireai_field('fp_emp1_title', $is_en ? 'PR Audit' : '公关审核'),
        'desc'   => hireai_field('fp_emp1_desc', $is_en ? 'AI-driven media insight and copy compliance specialist, ensuring the brand maintains a dignified and tasteful voice in complex social media environments.' : '由AI驱动的舆情洞察与文案合规专家，确保品牌在复杂的社交媒体环境中保持尊贵与体面的发声。'),
        'image'  => hireai_image('fp_emp1_image', get_stylesheet_directory_uri() . '/assets/img/employee-1.jpg'),
    ],
    [
        'kicker' => hireai_field('fp_emp2_kicker', 'Branding Strategy'),
        'title'  => hireai_field('fp_emp2_title', $is_en ? 'IP Co-branding' : 'IP联名营销'),
        'desc'   => hireai_field('fp_emp2_desc', $is_en ? 'Orchestrating cross-dimensional creative collaborations, using AI to generate infinite visual languages that connect virtual and real top-tier commercial value.' : '策划跨次元的创意联名，利用AI生成无限可能的视觉语言，连接虚拟与现实的顶级商业价值。'),
        'image'  => hireai_image('fp_emp2_image', get_stylesheet_directory_uri() . '/assets/img/employee-2.jpg'),
    ],
    [
        'kicker' => hireai_field('fp_emp3_kicker', 'E-commerce Aesthetics'),
        'title'  => hireai_field('fp_emp3_title', $is_en ? 'E-commerce Design' : '电商视觉设计'),
        'desc'   => hireai_field('fp_emp3_desc', $is_en ? 'Premium visuals tailored for luxury e-commerce, generating Dior-quality product compositions in seconds, reshaping the sensory experience of online shopping.' : '为奢品电商定制的高级视觉，秒级生成具备Dior质感的产品构图，重塑线上购物的感官体验。'),
        'image'  => hireai_image('fp_emp3_image', get_stylesheet_directory_uri() . '/assets/img/employee-3.jpg'),
    ],
];


$cases_kicker = hireai_field('fp_cases_kicker', 'The Masterpieces');
$cases_title  = hireai_field('fp_cases_title', $is_en ? 'Featured Cases' : '卓越案例展示');
$cases_link   = hireai_link('fp_cases_link', home_url('/cases-insights/'), $is_en ? 'Explore Full Gallery' : 'Explore Full Gallery');

$major_case = [
    'kicker' => hireai_field('fp_case_major_kicker', $is_en ? 'Luxury Real Estate' : '顶级豪宅'),
    'title'  => hireai_field('fp_case_major_title', $is_en ? 'Luxury Villa AI Virtual Curation' : '顶级豪宅AI虚拟策展项目'),
    'desc'   => hireai_field('fp_case_major_desc', $is_en ? 'Using AI digital twin technology and visual rendering to create immersive virtual house-touring experiences for global high-net-worth individuals.' : '利用AI数字孪生技术与视觉渲染，为全球高净值人群打造沉浸式的虚拟看房体验。'),
    'image'  => hireai_image('fp_case_major_image', get_stylesheet_directory_uri() . '/assets/img/case-major.jpg'),
];

$side_cases = [
    [
        'kicker' => hireai_field('fp_case_side1_kicker', $is_en ? 'Haute Couture' : '高级定制'),
        'title'  => hireai_field('fp_case_side1_title', $is_en ? 'AI Couture Fabric Patterns' : '高级定制AI面料纹样'),
        'desc'   => hireai_field('fp_case_side1_desc', $is_en ? 'Through GAN generative adversarial networks, providing inspiration for Paris Fashion Week, replicating the intricate textures of hand-embroidery.' : '通过GAN生成对抗网络，为巴黎时装周提供灵感触点，复刻手工刺绣的精密纹理。'),
        'image'  => hireai_image('fp_case_side1_image', get_stylesheet_directory_uri() . '/assets/img/case-side1.jpg'),
    ],
    [
        'kicker' => hireai_field('fp_case_side2_kicker', $is_en ? 'Bvlgari Style Marketing' : 'Bvlgari风格营销'),
        'title'  => hireai_field('fp_case_side2_title', $is_en ? 'Brand Social Media Visual Refresh' : '品牌社媒视觉重塑'),
        'desc'   => hireai_field('fp_case_side2_desc', $is_en ? 'Fusing traditional luxury aesthetics with avant-garde digital beauty to create visually compelling symbols with greater social penetration.' : '将传统奢侈品调性与前卫数字美学融合，打造更具社交穿透力的视觉符号。'),
    ],
];

$faq_kicker = hireai_field('fp_faq_kicker', 'The Intelligence Library');
$faq_title  = hireai_field('fp_faq_title', $is_en ? 'FAQ' : '常见问题解答');

$fallback_faq = [
    [
        'question' => $is_en ? 'How are AI digital employees different from freelancers or outsourcing?' : 'AI数字员工与传统兼职/外包有什么区别？',
        'answer'   => $is_en ? 'AI digital employees provide 24/7 uninterrupted service with consistent brand visual identity. Compared to traditional outsourcing, delivery efficiency improves by over 80%, and they continuously evolve with your brand data.' : 'AI数字员工提供24/7的不间断服务，且具备品牌专属的视觉风格一致性。相比于传统外包，其交付效率提升80%以上，且能够随着您的品牌数据不断进化。',
    ],
    [
        'question' => $is_en ? 'How long does it take to customize a dedicated AI digital human?' : '定制一个专属AI数字人需要多久？',
        'answer'   => $is_en ? 'Basic character models are typically delivered within 7-14 business days. For deep logic customization or specific skill training, the cycle is approximately 3-5 weeks.' : '基础角色模型通常在7-14个工作日内交付。如果是深度的逻辑定制或特定技能训练，周期约为3-5周。',
    ],
    [
        'question' => $is_en ? 'What industries are your AI digital employees suitable for?' : '你们的AI数字员工适合哪些行业？',
        'answer'   => $is_en ? 'Our AI digital employees cover marketing, e-commerce, customer service, HR, training, and more. We provide customized solutions based on your specific business scenario and brand requirements.' : '我们的AI数字员工覆盖营销、电商、客服、人力资源、培训等多个领域。我们会根据您的具体业务场景和品牌需求提供定制化解决方案。',
    ],
];


/* AI Solutions (fallback data) */
$solutions = [
    [
        'tag'   => ['zh' => '营销', 'en' => 'MARKETING'],
        'title' => ['zh' => '全域营销智囊', 'en' => 'Omnichannel Marketing'],
        'desc'  => ['zh' => '覆盖内容、投放与数据复盘的全链路营销智能体。', 'en' => 'A full-funnel marketing agent for content, media, and performance review.'],
        'image' => get_stylesheet_directory_uri() . '/' . 'assets/img/defaults/solution-1.jpg',
    ],
    [
        'tag'   => ['zh' => '电商', 'en' => 'E-COMMERCE'],
        'title' => ['zh' => '电商转化引擎', 'en' => 'Commerce Conversion'],
        'desc'  => ['zh' => '从选品、定价到客服，让增长从洞察到成交顺畅闭环。', 'en' => 'Connects selection, pricing, and service into a seamless growth loop.'],
        'image' => get_stylesheet_directory_uri() . '/' . 'assets/img/defaults/solution-2.jpg',
    ],
    [
        'tag'   => ['zh' => '设计', 'en' => 'DESIGN'],
        'title' => ['zh' => '奢品内容工坊', 'en' => 'Luxury Content Atelier'],
        'desc'  => ['zh' => '为高净值品牌打造有艺术质感、有销售力的内容体系。', 'en' => 'Crafts artful, conversion-ready content systems for high-net-worth brands.'],
        'image' => get_stylesheet_directory_uri() . '/' . 'assets/img/defaults/solution-3.jpg',
    ],
    [
        'tag'   => ['zh' => '公关', 'en' => 'PUBLIC RELATIONS'],
        'title' => ['zh' => '危机公关文案', 'en' => 'Crisis PR Copywriting'],
        'desc'  => ['zh' => '以毫秒级校准话术处理突发舆情，保护品牌叙事与市场信任。', 'en' => 'Immediate, highly calibrated messaging to mitigate brand exposure.'],
        'image' => get_stylesheet_directory_uri() . '/' . 'assets/img/defaults/solution-4.jpg',
    ],
];
?>

<style>
/* ── HireAI Homepage — Inline styles (stitch-matched) ── */

/* Fonts */

.hireai-fp, .hireai-fp * { box-sizing: border-box; margin: 0; padding: 0; }
.hireai-fp { overflow-x: hidden; background: #faf9f9; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; color: #1a1c1c; line-height: 1.6; }

.hireai-fp-hero {
    position: relative; min-height: 100vh; display: flex; align-items: center; overflow: hidden;
}
.hireai-fp-hero__bg {
    position: absolute; inset: 0; z-index: 0;
}
.hireai-fp-hero__bg-img {
    width: 100%; height: 100%; background-size: cover; background-position: center;
    transition: transform 20s cubic-bezier(0.4,0,0.2,1); transform: scale(1.05);
}
.hireai-fp-hero:hover .hireai-fp-hero__bg-img { transform: scale(1); }
.hireai-fp-hero__bg-overlay {
    position: absolute; inset: 0; background: rgba(255,255,255,0.05);
}
.hireai-fp-hero__bg-gradient {
    position: absolute; inset: 0;
    background: linear-gradient(to bottom, rgba(250,249,249,0) 40%, rgba(250,249,249,0.9) 80%, rgba(250,249,249,1) 100%);
}
.hireai-fp-hero__content {
    position: relative; z-index: 10; width: 100%; padding: 40vh 80px 20vh;
    max-width: 1440px; margin: 0 auto;
}
@media (max-width: 768px) {
    .hireai-fp-hero__content { padding: 40vh 20px 20vh; }
}
.hireai-fp-hero__inner { max-width: 48rem; }
.hireai-fp-hero__kicker {
    font-family: 'Inter', sans-serif; font-size: 14px; line-height: 1.2; letter-spacing: 0.4em;
    font-weight: 600; text-transform: uppercase; color: #775a19; display: inline-block; margin-bottom: 24px;
}
.hireai-fp-hero__title {
    font-family: 'Playfair Display', serif; font-size: 58px; line-height: 1.05;
    letter-spacing: -0.02em; font-weight: 700; color: #000; margin-bottom: 32px;
}
@media (max-width: 768px) {
    .hireai-fp-hero__title { font-size: 29px; }
}
.hireai-fp-hero__title em { font-style: italic; font-weight: normal; }
.hireai-fp-hero__subtitle {
    font-family: 'Inter', sans-serif; font-size: 18px; line-height: 1.6;
    color: rgba(68,71,72,0.8); max-width: 576px; margin-bottom: 48px;
}
.hireai-fp-hero__actions { display: flex; gap: 24px; flex-wrap: wrap; }
.hireai-fp-btn {
    display: inline-block; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600;
    letter-spacing: 0.1em; text-transform: uppercase; padding: 16px 48px; border-radius: 9999px;
    text-decoration: none; text-align: center; cursor: pointer; transition: all 0.3s; border: none;
}
.hireai-fp-btn--primary { background: #000; color: #fff; }
.hireai-fp-btn--primary:hover { box-shadow: 0 0 20px rgba(119,90,25,0.4); }
.hireai-fp-btn--outline {
    background: transparent; color: #775a19;
    border: 1px solid rgba(119,90,25,0.4);
}
.hireai-fp-btn--outline:hover { background: rgba(119,90,25,0.05); }

/* ── Section Utility ── */
.hireai-fp-section {
    padding: 120px 80px; max-width: 1440px; margin: 0 auto;
}
@media (max-width: 768px) {
    .hireai-fp-section { padding: 60px 20px; }
}
.hireai-fp-section-header { text-align: center; margin-bottom: 96px; }
.hireai-fp-section-header__kicker {
    font-family: 'Inter', sans-serif; font-size: 14px; line-height: 1.2; letter-spacing: 0.3em;
    font-weight: 600; text-transform: uppercase; color: #775a19;
}
.hireai-fp-section-header__title {
    font-family: 'Playfair Display', serif; font-size: 48px; line-height: 1.2;
    font-weight: 600; color: #000; margin-top: 16px;
}
@media (max-width: 768px) {
    .hireai-fp-section-header__title { font-size: 28px; }
}
.hireai-fp-section-header__divider {
    width: 96px; height: 1px; margin: 40px auto 0;
    background: linear-gradient(90deg, transparent, rgba(119,90,25,0.4), transparent);
}
.hireai-fp-section-header__divider--sm {
    width: 64px;
}

/* ── Employee Grid ── */
.hireai-fp-grid-3 {
    display: grid; grid-template-columns: repeat(1, 1fr); gap: 40px;
}
@media (min-width: 768px) {
    .hireai-fp-grid-3 { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .hireai-fp-grid-3 { grid-template-columns: repeat(3, 1fr); }
}

/* ── Glass Card ── */
.hireai-fp-glass-card {
    background: rgba(250,249,249,0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(119,90,25,0.1); border-radius: 0.75rem; padding: 20px;
    transition: all 0.5s cubic-bezier(0.4,0,0.2,1);
}
.hireai-fp-glass-card:hover {
    border-color: rgba(119,90,25,0.4);
    box-shadow: 0 30px 60px -15px rgba(0,0,0,0.1);
    transform: translateY(-4px);
}
.hireai-fp-glass-card__img-wrap {
    aspect-ratio: 4/5; overflow: hidden; position: relative; margin-bottom: 32px; border-radius: 0.5rem;
}
.hireai-fp-glass-card__img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 1s cubic-bezier(0.4,0,0.2,1);
}
.hireai-fp-glass-card:hover .hireai-fp-glass-card__img { transform: scale(1.1); }
.hireai-fp-glass-card__img-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.2), transparent);
    opacity: 0; transition: opacity 0.3s;
}
.hireai-fp-glass-card:hover .hireai-fp-glass-card__img-overlay { opacity: 1; }
.hireai-fp-glass-card__body { padding: 8px; }
.hireai-fp-glass-card__kicker {
    font-family: 'Inter', sans-serif; font-size: 12px; line-height: 1.2; letter-spacing: 0.15em;
    font-weight: 500; text-transform: uppercase; color: #775a19;
}
.hireai-fp-glass-card__title {
    font-family: 'Playfair Display', serif; font-size: 28px; line-height: 1.3; font-weight: 500;
    color: #000; margin: 8px 0 16px;
}
.hireai-fp-glass-card__desc {
    color: #444748; font-family: 'Inter', sans-serif; font-size: 16px; line-height: 1.6;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}

/* ── AI Solutions Grid ── */
.hireai-fp-solutions {
    background: #faf9f9; padding: 120px 80px; max-width: 1440px; margin: 0 auto;
}
@media (max-width: 768px) {
    .hireai-fp-solutions { padding: 60px 20px; }
}
.hireai-fp-solutions__grid {
    display: grid; grid-template-columns: repeat(1, 1fr); gap: 24px;
}
@media (min-width: 768px) {
    .hireai-fp-solutions__grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .hireai-fp-solutions__grid { grid-template-columns: repeat(2, 1fr); }
}
.hireai-fp-sol-card {
    background: #fff; border: 1px solid rgba(196,199,199,0.3); transition: border-color 0.3s;
}
.hireai-fp-sol-card:hover { border-color: #775a19; }
.hireai-fp-sol-card__img-wrap {
    aspect-ratio: 16/9; overflow: hidden;
}
.hireai-fp-sol-card__img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4,0,0.2,1);
}
.hireai-fp-sol-card:hover .hireai-fp-sol-card__img { transform: scale(1.05); }
.hireai-fp-sol-card__body { padding: 24px; }
.hireai-fp-sol-card__tag {
    font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 500; text-transform: uppercase;
    letter-spacing: 0.15em; color: #775a19;
}
.hireai-fp-sol-card__title {
    font-family: 'Playfair Display', serif; font-size: 24px; line-height: 1.3; font-weight: 500;
    color: #000; margin-top: 8px;
}
.hireai-fp-sol-card__desc {
    font-family: 'Inter', sans-serif; font-size: 16px; line-height: 1.6; color: #444748;
    margin-top: 12px;
}
.hireai-fp-solutions__cta {
    text-align: center; margin-top: 64px;
}

/* ── Featured Cases ── */
.hireai-fp-cases {
    background: #f4f3f3; padding: 120px 0;
}
.hireai-fp-cases__inner {
    max-width: 1440px; margin: 0 auto; padding: 0 80px;
}
@media (max-width: 768px) {
    .hireai-fp-cases__inner { padding: 0 20px; }
}
.hireai-fp-cases__header {
    display: flex; flex-direction: column; gap: 32px; margin-bottom: 80px;
}
@media (min-width: 768px) {
    .hireai-fp-cases__header { flex-direction: row; justify-content: space-between; align-items: flex-end; }
}
.hireai-fp-cases__header-text { max-width: 32rem; text-align: left; }
.hireai-fp-cases__header-kicker {
    font-family: 'Inter', sans-serif; font-size: 14px; line-height: 1.2; letter-spacing: 0.3em;
    font-weight: 600; text-transform: uppercase; color: #775a19;
}
.hireai-fp-cases__header-title {
    font-family: 'Playfair Display', serif; font-size: 48px; line-height: 1.2;
    font-weight: 600; color: #000; margin-top: 16px;
}
@media (max-width: 768px) {
    .hireai-fp-cases__header-title { font-size: 28px; }
}
.hireai-fp-cases__header-link {
    font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; letter-spacing: 0.1em;
    text-transform: uppercase; color: #000; text-decoration: none;
    border-bottom: 1px solid rgba(26,28,28,0.2); padding-bottom: 4px;
    display: inline-flex; align-items: center; gap: 12px; transition: all 0.3s;
}
.hireai-fp-cases__header-link:hover { border-color: #000; }
.hireai-fp-cases__header-link svg { transition: transform 0.3s; }
.hireai-fp-cases__header-link:hover svg { transform: translateX(3px); }

.hireai-fp-cases__grid {
    display: grid; grid-template-columns: 1fr; gap: 24px;
}
@media (min-width: 768px) {
    .hireai-fp-cases__grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .hireai-fp-cases__grid { grid-template-columns: repeat(12, 1fr); }
}

/* Major case */
.hireai-fp-case-major {
    position: relative; overflow: hidden; background: #000; min-height: 500px; height: 650px;
}
@media (min-width: 1024px) {
    .hireai-fp-case-major { grid-column: span 8; }
}
.hireai-fp-case-major__bg {
    position: absolute; inset: 0; background-size: cover; background-position: center; opacity: 0.7;
    transition: transform 3s cubic-bezier(0.4,0,0.2,1);
}
.hireai-fp-case-major:hover .hireai-fp-case-major__bg { transform: scale(1.05); }
.hireai-fp-case-major__overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, #000 0%, rgba(0,0,0,0.2) 40%, transparent 100%);
}
.hireai-fp-case-major__content {
    position: absolute; bottom: 0; left: 0; padding: 56px; color: #fff;
}
@media (max-width: 768px) {
    .hireai-fp-case-major__content { padding: 24px; }
}
.hireai-fp-case-major__kicker {
    font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 500; text-transform: uppercase;
    letter-spacing: 0.3em; color: #775a19; margin-bottom: 24px; display: block;
}
.hireai-fp-case-major__title {
    font-family: 'Playfair Display', serif; font-size: 48px; line-height: 1.2;
    font-weight: 600; margin: 0 0 24px;
}
@media (max-width: 768px) {
    .hireai-fp-case-major__title { font-size: 28px; }
}
.hireai-fp-case-major__desc {
    font-family: 'Inter', sans-serif; font-size: 18px; line-height: 1.6;
    color: rgba(255,255,255,0.6); max-width: 36rem;
}

/* Side cases */
.hireai-fp-case-side {
    display: flex; flex-direction: column; gap: 24px;
}
@media (min-width: 1024px) {
    .hireai-fp-case-side { grid-column: span 4; }
}
.hireai-fp-case-card {
    background: #fff; padding: 40px; border: 1px solid rgba(196,199,199,0.3);
    flex: 1; transition: border-color 0.3s;
}
.hireai-fp-case-card:hover { border-color: #775a19; }
.hireai-fp-case-card__kicker {
    font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 500; text-transform: uppercase;
    letter-spacing: 0.15em; color: #775a19; margin-bottom: 24px; display: block;
}
.hireai-fp-case-card__title {
    font-family: 'Playfair Display', serif; font-size: 32px; line-height: 1.3;
    font-weight: 500; margin: 0 0 24px;
}
@media (max-width: 768px) {
    .hireai-fp-case-card__title { font-size: 24px; }
}
.hireai-fp-case-card__desc {
    color: #444748; font-family: 'Inter', sans-serif; font-size: 16px;
    line-height: 1.6; margin-bottom: 32px;
}
.hireai-fp-case-card__img {
    width: 100%; height: 8rem; object-fit: cover;
    filter: grayscale(1); transition: filter 0.5s;
}
.hireai-fp-case-card:hover .hireai-fp-case-card__img { filter: grayscale(0); }

/* ── FAQ ── */
.hireai-fp-faq-list {
    max-width: 56rem; margin: 0 auto; display: flex; flex-direction: column; gap: 32px;
}
.hireai-fp-faq-item {
    border-bottom: 1px solid rgba(196,199,199,0.3); padding-bottom: 32px; cursor: pointer;
}
.hireai-fp-faq-item__header {
    display: flex; justify-content: space-between; align-items: center; gap: 16px;
}
.hireai-fp-faq-item__question {
    font-family: 'Playfair Display', serif; font-size: 22px; line-height: 1.3;
    font-weight: 500; margin: 0; transition: color 0.3s; flex: 1;
}
.hireai-fp-faq-item:hover .hireai-fp-faq-item__question { color: #775a19; }
.hireai-fp-faq-item__icon {
    font-size: 24px; color: #444748; transition: transform 0.3s; flex-shrink: 0;
}
.hireai-fp-faq-item.is-active .hireai-fp-faq-item__icon { transform: rotate(180deg); }
.hireai-fp-faq-item__answer {
    max-height: 0; overflow: hidden; transition: max-height 0.5s cubic-bezier(0.4,0,0.2,1);
}
.hireai-fp-faq-item.is-active .hireai-fp-faq-item__answer { max-height: 500px; }
.hireai-fp-faq-item__answer-inner {
    padding-top: 24px; font-family: 'Inter', sans-serif; font-size: 16px;
    line-height: 1.6; color: rgba(68,71,72,0.8);
}
.hireai-fp-faq__cta {
    text-align: center; margin-top: 64px;
}

</style>

<main class="hireai-fp">

<!-- ═══ Hero ═══ -->
<section class="hireai-fp-hero">
    <div class="hireai-fp-hero__bg">
        <?php if ($hero_image) : ?>
        <div class="hireai-fp-hero__bg-img" style="background-image: url('<?php echo esc_url($hero_image); ?>')"></div>
        <?php else : ?>
        <div class="hireai-fp-hero__bg-img"></div>
        <?php endif; ?>
        <div class="hireai-fp-hero__bg-overlay"></div>
        <div class="hireai-fp-hero__bg-gradient"></div>
    </div>
    <div class="hireai-fp-hero__content">
        <div class="hireai-fp-hero__inner">
            <?php if ($hero_kicker) : ?>
            <span class="hireai-fp-hero__kicker"><?php echo esc_html($hero_kicker); ?></span>
            <?php endif; ?>
            <h1 class="hireai-fp-hero__title">
                <?php echo esc_html($hero_title); ?><br>
                <em><?php echo esc_html($is_en ? '聘用AI数字员工' : '聘用AI数字员工'); ?></em>
            </h1>
            <p class="hireai-fp-hero__subtitle"><?php echo wp_kses_post($hero_subtitle); ?></p>
            <div class="hireai-fp-hero__actions">
                <a class="hireai-fp-btn hireai-fp-btn--primary" href="<?php echo esc_url($hero_cta_1['url']); ?>"<?php echo !empty($hero_cta_1['target']) ? ' target="' . esc_attr($hero_cta_1['target']) . '" rel="noopener"' : ''; ?>><?php echo esc_html($hero_cta_1['title']); ?></a>
                <a class="hireai-fp-btn hireai-fp-btn--outline" href="<?php echo esc_url($hero_cta_2['url']); ?>"<?php echo !empty($hero_cta_2['target']) ? ' target="' . esc_attr($hero_cta_2['target']) . '" rel="noopener"' : ''; ?>><?php echo esc_html($hero_cta_2['title']); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ═══ AI Employee Matrix ═══ -->
<section class="hireai-fp-section">
    <div class="hireai-fp-section-header">
        <span class="hireai-fp-section-header__kicker"><?php echo esc_html($emp_kicker); ?></span>
        <h2 class="hireai-fp-section-header__title"><?php echo esc_html($emp_title); ?></h2>
        <div class="hireai-fp-section-header__divider"></div>
    </div>
    <div class="hireai-fp-grid-3">
        <?php foreach ($employees as $emp) : ?>
        <div class="hireai-fp-glass-card">
            <div class="hireai-fp-glass-card__img-wrap">
                <img class="hireai-fp-glass-card__img" src="<?php echo esc_url($emp['image']); ?>" alt="<?php echo esc_attr($emp['title']); ?>">
                <div class="hireai-fp-glass-card__img-overlay"></div>
            </div>
            <div class="hireai-fp-glass-card__body">
                <span class="hireai-fp-glass-card__kicker"><?php echo esc_html($emp['kicker']); ?></span>
                <h3 class="hireai-fp-glass-card__title"><?php echo esc_html($emp['title']); ?></h3>
                <p class="hireai-fp-glass-card__desc"><?php echo esc_html($emp['desc']); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div style="text-align: center; margin-top: 48px;">
        <a class="hireai-fp-btn hireai-fp-btn--outline" href="<?php echo esc_url(home_url('/ai-employees/')); ?>"><?php echo esc_html($is_en ? 'Explore More' : '探索更多'); ?></a>
    </div>
</section>

<!-- ═══ AI Solutions ═══ -->
<section class="hireai-fp-solutions">
    <div class="hireai-fp-section-header">
        <span class="hireai-fp-section-header__kicker"><?php echo esc_html($is_en ? 'AI SOLUTIONS' : 'AI SOLUTIONS'); ?></span>
        <h2 class="hireai-fp-section-header__title"><?php echo esc_html($is_en ? 'AI Solutions' : 'AI解决方案'); ?></h2>
        <div class="hireai-fp-section-header__divider"></div>
    </div>
    <div class="hireai-fp-solutions__grid">
        <?php foreach ($solutions as $sol) : ?>
        <div class="hireai-fp-sol-card">
            <div class="hireai-fp-sol-card__img-wrap">
                <img class="hireai-fp-sol-card__img" src="<?php echo esc_url($sol['image']); ?>" alt="<?php echo esc_attr($fallback($sol, 'title')); ?>">
            </div>
            <div class="hireai-fp-sol-card__body">
                <span class="hireai-fp-sol-card__tag"><?php echo esc_html($fallback($sol, 'tag')); ?></span>
                <h3 class="hireai-fp-sol-card__title"><?php echo esc_html($fallback($sol, 'title')); ?></h3>
                <p class="hireai-fp-sol-card__desc"><?php echo esc_html($fallback($sol, 'desc')); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="hireai-fp-solutions__cta">
        <a class="hireai-fp-btn hireai-fp-btn--outline" href="<?php echo esc_url(home_url('/ai-solutions/')); ?>"><?php echo esc_html($is_en ? 'Explore More' : '探索更多'); ?></a>
    </div>
</section>

<!-- ═══ Featured Cases ═══ -->
<section class="hireai-fp-cases">
    <div class="hireai-fp-cases__inner">
        <div class="hireai-fp-cases__header">
            <div class="hireai-fp-cases__header-text">
                <span class="hireai-fp-cases__header-kicker"><?php echo esc_html($cases_kicker); ?></span>
                <h2 class="hireai-fp-cases__header-title"><?php echo esc_html($cases_title); ?></h2>
            </div>
            <a class="hireai-fp-cases__header-link" href="<?php echo esc_url($cases_link['url']); ?>"<?php echo !empty($cases_link['target']) ? ' target="' . esc_attr($cases_link['target']) . '" rel="noopener"' : ''; ?>>
                <?php echo esc_html($cases_link['title']); ?>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
        <div class="hireai-fp-cases__grid">
            <!-- Major Case -->
            <div class="hireai-fp-case-major">
                <div class="hireai-fp-case-major__bg" style="background-image: url('<?php echo esc_url($major_case['image']); ?>')"></div>
                <div class="hireai-fp-case-major__overlay"></div>
                <div class="hireai-fp-case-major__content">
                    <span class="hireai-fp-case-major__kicker"><?php echo esc_html($major_case['kicker']); ?></span>
                    <h3 class="hireai-fp-case-major__title"><?php echo esc_html($major_case['title']); ?></h3>
                    <p class="hireai-fp-case-major__desc"><?php echo esc_html($major_case['desc']); ?></p>
                </div>
            </div>
            <!-- Side Cases -->
            <div class="hireai-fp-case-side">
                <?php foreach ($side_cases as $sc) : ?>
                <div class="hireai-fp-case-card">
                    <span class="hireai-fp-case-card__kicker"><?php echo esc_html($sc['kicker']); ?></span>
                    <h4 class="hireai-fp-case-card__title"><?php echo esc_html($sc['title']); ?></h4>
                    <p class="hireai-fp-case-card__desc"><?php echo esc_html($sc['desc']); ?></p>
                    <?php if (!empty($sc['image'])) : ?>
                    <img class="hireai-fp-case-card__img" src="<?php echo esc_url($sc['image']); ?>" alt="<?php echo esc_attr($sc['title']); ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ═══ FAQ ═══ -->
<section class="hireai-fp-section" id="faq">
    <div class="hireai-fp-section-header">
        <span class="hireai-fp-section-header__kicker"><?php echo esc_html($faq_kicker); ?></span>
        <h2 class="hireai-fp-section-header__title"><?php echo esc_html($faq_title); ?></h2>
        <div class="hireai-fp-section-header__divider hireai-fp-section-header__divider--sm"></div>
    </div>
    <div class="hireai-fp-faq-list">
        <?php foreach ($fallback_faq as $item) : ?>
        <div class="hireai-fp-faq-item">
            <div class="hireai-fp-faq-item__header" onclick="this.closest('.hireai-fp-faq-item').classList.toggle('is-active')">
                <h4 class="hireai-fp-faq-item__question"><?php echo esc_html($item['question']); ?></h4>
                <span class="hireai-fp-faq-item__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </span>
            </div>
            <div class="hireai-fp-faq-item__answer">
                <div class="hireai-fp-faq-item__answer-inner">
                    <p><?php echo esc_html($item['answer']); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="hireai-fp-faq__cta">
        <a class="hireai-fp-btn hireai-fp-btn--outline" href="<?php echo esc_url(home_url('/faq/')); ?>"><?php echo esc_html($is_en ? 'Explore More' : '探索更多'); ?></a>
    </div>
</section>

</main>


<?php get_footer(); ?>
