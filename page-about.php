<?php
/**
 * Template Name: 聘AI - 关于我们
 *
 * v3.9.0 — About Us
 *   1. Page slogan hero
 *   2. Human / AI co-creation story
 *   3. Three-phase methodology
 *   4. Contact CTA
 *
 * @package HireAI
 * @version 3.9.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$suffix  = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '';
$is_en   = ($suffix === '_en');
$page_id = get_the_ID();

$hero_kicker = $is_en ? 'THE ATELIER' : '智慧工坊';
$hero_title = get_the_title($page_id);
$hero_subtitle = hireai_field('about_hero_accent', $is_en ? 'Where High Technology Meets Haute Couture.' : '当代科技，邂逅高级定制。', $page_id);

$story_number = hireai_field('about_story_number', '01', $page_id);
$story_kicker = hireai_field('about_story_kicker', $is_en ? 'FORGING FORWARD WITH AMBITION · ABOUT US' : '以雄心持续向前 · 关于我们', $page_id);
$story_image = hireai_image('about_story_image', get_stylesheet_directory_uri() . '/assets/img/defaults/employee-1.jpg', $page_id);
$story_image_alt = hireai_field('about_story_image_alt', $is_en ? 'The HireAIPeople human and AI co-creation team' : '聘AI人机共创团队', $page_id);
$story_image_kicker = hireai_field('about_story_image_kicker', $is_en ? 'The Sanctuary Atelier' : '智慧共创工坊', $page_id);
$story_image_title = hireai_field('about_story_image_title', $is_en ? 'AI Speed. Human Warmth.' : 'AI 速度，人类温度。', $page_id);
$story_image_pill = hireai_field('about_story_image_pill', $is_en ? 'Symbiosis Model 4.0' : '共生模型 4.0', $page_id);
$story_card_kicker = hireai_field('about_story_card_kicker', $is_en ? 'HARMONIC PARADIGM' : '和谐范式', $page_id);
$story_card_quote = hireai_field('about_story_card_quote', $is_en
    ? 'Technology’s ultimate purpose is never to replace humanity, but to amplify human ambition.'
    : '技术的终极目的不是替代人类，而是放大人的雄心与想象力。', $page_id);
$story_title = hireai_field('about_story_title', $is_en ? 'Forging Forward with Greater Ambition' : '以更大的雄心向前', $page_id);
$story_paragraph_1 = hireai_field('about_story_paragraph_1', $is_en
    ? 'We are builders driven by rigorous AI research and persistent passion. We believe frontline judgment, market discernment, and human imagination chart the direction that foundation models accelerate.'
    : '我们是由持续热情与严谨 AI 研究驱动的建设者。我们相信，前沿判断、市场洞察与人的想象力决定方向，基础模型则负责加速实现。', $page_id);
$story_paragraph_2 = hireai_field('about_story_paragraph_2', $is_en
    ? 'Your tactile understanding of the business, customer feedback, and tomorrow’s vision are not details—they are the sovereign compass of every engagement.'
    : '你对业务的触感、来自一线的反馈，以及对明天的想象，从来不是细节，而是每次合作的最高指南。', $page_id);
$story_pull_quote = hireai_field('about_story_pull_quote', $is_en
    ? '“Your ambition and judgment are paramount.”'
    : '「你的雄心与判断，始终最重要。」', $page_id);
$story_paragraph_3 = hireai_field('about_story_paragraph_3', $is_en
    ? 'Our engineering scaffold stays unyielding while our team contributes judgment, empathy, and artisanal refinement. Every deployment should inspire hope, exploration, and conviction in your own power.'
    : '我们提供稳定可靠的工程与技术底座，团队则贡献判断、同理心与精细打磨。每次交付，都应带来希望、探索的喜悦，以及对自身力量的确信。', $page_id);

$method_number = hireai_field('about_method_number', '02', $page_id);
$method_kicker = hireai_field('about_method_kicker', $is_en ? 'NEURAL ARCHITECTURE' : '智能架构', $page_id);
$method_title = hireai_field('about_method_title', $is_en ? 'A Tripartite Protocol for Sovereign Intelligence' : '面向自主智能的三段式协议', $page_id);
$method_intro = hireai_field('about_method_intro', $is_en
    ? 'A disciplined continuum that translates corporate wisdom into perpetual, high-precision performance.'
    : '以纪律严明的连续流程，将企业智慧转化为持续、高精度的经营表现。', $page_id);
$method_image = hireai_image('about_method_image', get_stylesheet_directory_uri() . '/assets/img/defaults/solution-1.jpg', $page_id);
$method_image_alt = hireai_field('about_method_image_alt', $is_en ? 'HireAIPeople three-phase AI workflow architecture' : '聘AI三阶段智能工作流架构', $page_id);
$method_items = function_exists('hireai_about_process_items') ? hireai_about_process_items($page_id, $is_en ? 'en' : 'zh') : [];

$cta_kicker = hireai_field('about_cta_kicker', $is_en ? 'CO-CREATE THE FUTURE' : '共创未来', $page_id);
$cta_title = hireai_field('about_cta_title', $is_en ? 'Ready to explore with greater ambition?' : '准备好更有雄心地探索吗？', $page_id);
$cta_subtitle = hireai_field('about_cta_subtitle', $is_en
    ? 'Tell us where your business must go next. We will design, refine, and operate the AI workforce beside you.'
    : '告诉我们业务的下一站。我们会与你共同设计、打磨并运营 AI 数字员工。', $page_id);
$cta_primary_label = hireai_field('about_cta_primary_label', $is_en ? 'Initiate Contact' : '发起联络', $page_id);
$cta_primary_url = hireai_field('about_cta_primary_url', '/contact/', $page_id);
$cta_secondary_label = hireai_field('about_cta_secondary_label', $is_en ? 'Examine Case Studies' : '查看案例&洞察', $page_id);
$cta_secondary_url = hireai_field('about_cta_secondary_url', '/cases-insights/', $page_id);
$cta_assurance_1 = hireai_field('about_cta_assurance_1', $is_en ? 'Strict NDA Protected' : '严格 NDA 保护', $page_id);
$cta_assurance_2 = hireai_field('about_cta_assurance_2', $is_en ? 'Sovereign Architecture' : '自主可控架构', $page_id);

?>
<style id="hireai-about-page-css">
.hireai-about-page{background:var(--hai-surface-low,#f9f9f9);color:var(--hai-on-surface,#1a1a1a);}
.hireai-about-hero{padding-block:clamp(64px,8vw,120px) clamp(40px,5vw,72px);text-align:center;background:var(--hai-surface-lowest,#fff);}
.hireai-about-hero .page-hero__kicker{display:inline-block;margin-bottom:24px;color:#775a19;}
.hireai-about-hero .page-hero__title{display:inline-block;margin-bottom:28px;line-height:1.1;letter-spacing:-.01em;background:linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent;font-style:normal;}
.hireai-about-hero .page-hero__subtitle{max-width:640px;margin-inline:auto;font-family:var(--font-body-en,'Inter'),sans-serif;font-size:16px;font-style:normal;color:var(--hai-on-surface,#1a1c1c);line-height:1.6;}
.hireai-about-section{padding:120px 0;background:var(--hai-surface-low,#f9f9f9);}
.hireai-about-section--white{background:var(--hai-surface-lowest,#fff);}
.hireai-about-container{max-width:1440px;margin:0 auto;padding:0 24px;}
.hireai-about-tag{display:flex;align-items:center;gap:12px;margin-bottom:40px;color:var(--hai-on-surface-variant,#4d4635);font-family:var(--font-label,Inter),sans-serif;font-size:12px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;}
.hireai-about-tag__number{color:var(--hai-secondary,#775a19);font-size:14px;}
.hireai-about-tag__line{width:32px;height:1px;background:var(--hai-platinum-grey,#b8b8b8);}
.hireai-about-tag--center{justify-content:center;}
.hireai-about-story{display:grid;grid-template-columns:7fr 5fr;gap:80px;align-items:center;}
.hireai-about-story__media{position:relative;}
.hireai-about-story__frame{position:relative;overflow:hidden;border-radius:4px;border:1px solid rgba(196,199,199,.22);box-shadow:0 30px 80px rgba(26,28,28,.10);}
.hireai-about-story__frame img{display:block;width:100%;height:560px;object-fit:cover;transform:scale(1.01);transition:transform .6s ease;}
.hireai-about-story__media:hover .hireai-about-story__frame img{transform:scale(1.04);}
.hireai-about-story__overlay{position:absolute;inset:auto 0 0;padding:32px;background:linear-gradient(to top,rgba(26,26,26,.72),transparent);color:#fff;}
.hireai-about-story__overlay-kicker{margin:0 0 8px;color:var(--hai-secondary-fixed-dim,#e9c176);font-family:var(--font-label,Inter),sans-serif;font-size:12px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;}
.hireai-about-story__overlay-title{margin:0;font-family:var(--font-display-en,var(--font-serif));font-size:30px;font-weight:500;line-height:1.25;}
.hireai-about-story__pill{position:absolute;top:24px;right:24px;display:inline-flex;align-items:center;gap:8px;padding:9px 15px;border:1px solid rgba(255,255,255,.32);border-radius:999px;background:rgba(255,255,255,.18);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);color:#fff;font-family:var(--font-label,Inter),sans-serif;font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;}
.hireai-about-story__card{position:absolute;right:-24px;bottom:56px;max-width:330px;padding:26px;border:1px solid rgba(196,199,199,.30);border-radius:4px;background:rgba(255,255,255,.95);box-shadow:0 24px 60px rgba(26,28,28,.12);}
.hireai-about-story__card-kicker{display:flex;align-items:center;gap:8px;margin-bottom:12px;color:var(--hai-secondary,#775a19);font-family:var(--font-label,Inter),sans-serif;font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;}
.hireai-about-story__card-quote{margin:0;font-family:var(--font-display-en,var(--font-serif));font-size:19px;font-style:italic;line-height:1.45;color:var(--hai-on-surface,#1a1a1a);}
.hireai-about-story__title{margin:0 0 26px;font-family:var(--font-display-en,var(--font-serif));font-size:clamp(30px,3.3vw,48px);font-weight:600;line-height:1.18;letter-spacing:-.01em;}
.hireai-about-story__body{display:flex;flex-direction:column;gap:20px;font-family:var(--font-body-en,var(--font-body));font-size:16px;line-height:1.75;color:var(--hai-on-surface-variant,#4d4635);}
.hireai-about-story__pull{margin:4px 0;padding:22px 26px;border-left:2px solid var(--hai-secondary,#775a19);border-radius:0 4px 4px 0;background:rgba(255,255,255,.82);box-shadow:0 12px 34px rgba(26,28,28,.05);font-family:var(--font-display-en,var(--font-serif));font-size:21px;font-style:italic;line-height:1.45;color:var(--hai-on-surface,#1a1a1a);}
.hireai-about-method{max-width:1040px;margin:0 auto;text-align:center;}
.hireai-about-method__title{max-width:820px;margin:0 auto 18px;font-family:var(--font-display-en,var(--font-serif));font-size:clamp(30px,3.5vw,48px);font-weight:600;line-height:1.18;}
.hireai-about-method__intro{max-width:720px;margin:0 auto 56px;font-family:var(--font-body-en,var(--font-body));font-size:18px;line-height:1.65;color:var(--hai-on-surface-variant,#4d4635);}
.hireai-about-method__image{margin:0 0 32px;padding:18px;border:1px solid rgba(196,199,199,.28);border-radius:4px;background:rgba(255,255,255,.78);box-shadow:0 20px 60px rgba(26,28,28,.06);}
.hireai-about-method__image img{display:block;width:100%;max-height:420px;object-fit:cover;border-radius:4px;}
.hireai-about-process{display:grid;grid-template-columns:repeat(3,1fr);gap:32px;list-style:none;margin:0;padding:0;text-align:left;}
.hireai-about-process__item{display:flex;flex-direction:column;min-height:100%;padding:32px;border:1px solid rgba(196,199,199,.32);border-radius:4px;background:rgba(255,255,255,.82);box-shadow:0 10px 36px rgba(26,28,28,.04);transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease;}
.hireai-about-process__item:hover{transform:translateY(-4px);border-color:rgba(119,90,25,.42);box-shadow:0 18px 46px rgba(26,28,28,.08);}
.hireai-about-process__head{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;color:var(--hai-secondary,#775a19);}
.hireai-about-process__number{font-family:var(--font-display-en,var(--font-serif));font-size:32px;font-weight:500;line-height:1;}
.hireai-about-process__phase{display:block;margin-bottom:10px;font-family:var(--font-label,Inter),sans-serif;font-size:12px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;color:var(--hai-on-surface-variant,#4d4635);}
.hireai-about-process__title{margin:0 0 14px;font-family:var(--font-display-en,var(--font-serif));font-size:25px;font-weight:500;line-height:1.3;color:var(--hai-on-surface,#1a1a1a);}
.hireai-about-process__text{margin:0;font-family:var(--font-body-en,var(--font-body));font-size:16px;line-height:1.7;color:var(--hai-on-surface-variant,#4d4635);}
.hireai-about-process__meta{display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:30px;color:var(--hai-on-surface-variant,#4d4635);font-family:var(--font-label,Inter),sans-serif;font-size:13px;letter-spacing:.08em;text-transform:uppercase;}
.hireai-about-cta{padding:120px 0;background:var(--hai-surface-low,#f9f9f9);}
.hireai-about-cta__card{position:relative;overflow:hidden;max-width:960px;margin:0 auto;padding:88px 56px;text-align:center;border:1px solid rgba(119,90,25,.20);border-radius:4px;background:rgba(255,255,255,.80);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);box-shadow:0 32px 90px rgba(26,28,28,.07);}
.hireai-about-cta__card::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 50% 100%,rgba(233,193,118,.20),transparent 62%);pointer-events:none;}
.hireai-about-cta__card>*{position:relative;}
.hireai-about-cta__kicker{margin:0 0 20px;color:var(--hai-secondary,#775a19);font-family:var(--font-label,Inter),sans-serif;font-size:12px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;}
.hireai-about-cta__title{margin:0 auto 20px;max-width:720px;font-family:var(--font-display-en,var(--font-serif));font-size:clamp(32px,4.5vw,64px);font-weight:600;line-height:1.1;}
.hireai-about-cta__subtitle{max-width:640px;margin:0 auto 38px;font-family:var(--font-body-en,var(--font-body));font-size:18px;line-height:1.65;color:var(--hai-on-surface-variant,#4d4635);}
.hireai-about-cta__actions{display:flex;justify-content:center;flex-wrap:wrap;gap:16px;}
.hireai-about-cta__button{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:52px;padding:14px 28px;border-radius:999px;font-family:var(--font-label,Inter),sans-serif;font-size:13px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;transition:transform .2s ease,box-shadow .2s ease;}
.hireai-about-cta__button--primary{color:#fff;background:linear-gradient(135deg,#775a19,#e9c176);box-shadow:0 14px 34px rgba(119,90,25,.22);}
.hireai-about-cta__button--secondary{color:var(--hai-on-surface,#1a1a1a);border:1px solid rgba(119,90,25,.30);background:#fff;}
.hireai-about-cta__button:hover,.hireai-about-cta__button:focus-visible{transform:translateY(-2px);box-shadow:0 18px 42px rgba(119,90,25,.26);}
.hireai-about-cta__assurance{display:flex;justify-content:center;flex-wrap:wrap;gap:28px;margin-top:52px;color:var(--hai-on-surface-variant,#4d4635);font-family:var(--font-label,Inter),sans-serif;font-size:13px;letter-spacing:.08em;text-transform:uppercase;}
.hireai-about-cta__assurance span{display:inline-flex;align-items:center;gap:8px;}
@media (max-width:1279px){.hireai-about-story{grid-template-columns:1fr;gap:64px;}.hireai-about-story__media{margin-right:24px;}}
@media (max-width:1023px){.hireai-about-process{grid-template-columns:1fr;}.hireai-about-story__frame img{height:430px;}}
@media (max-width:767px){.hireai-about-hero,.hireai-about-section,.hireai-about-cta{padding-top:72px;padding-bottom:72px;}.hireai-about-container{padding:0 24px;}.hireai-about-story{gap:40px;}.hireai-about-story__media{margin-right:0;}.hireai-about-story__frame img{height:330px;}.hireai-about-story__card{position:relative;right:auto;bottom:auto;margin:-36px 16px 0;}.hireai-about-story__overlay{padding:22px;}.hireai-about-story__overlay-title{font-size:24px;}.hireai-about-story__title{font-size:30px;}.hireai-about-story__pull{font-size:19px;}.hireai-about-method__image{padding:10px;}.hireai-about-process__item{padding:24px;}.hireai-about-cta__card{padding:64px 24px;}.hireai-about-cta__actions .hireai-about-cta__button{width:100%;}}
</style>

<main class="hireai-about-page">
    <header class="page-hero page-hero--center hireai-about-hero" aria-labelledby="hireai-about-title">
        <div class="container">
            <span class="label-caps page-hero__kicker"><?php echo esc_html($hero_kicker); ?></span>
            <h1 id="hireai-about-title" class="display-lg page-hero__title"><?php echo esc_html($hero_title); ?></h1>
            <p class="body-lg page-hero__subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        </div>
    </header>

    <section class="hireai-about-section" aria-labelledby="hireai-about-story-title">
        <div class="hireai-about-container">
            <p class="hireai-about-tag"><span class="hireai-about-tag__number"><?php echo esc_html($story_number); ?></span><span class="hireai-about-tag__line" aria-hidden="true"></span><?php echo esc_html($story_kicker); ?></p>
            <div class="hireai-about-story">
                <div class="hireai-about-story__media">
                    <figure class="hireai-about-story__frame">
                        <img src="<?php echo esc_url($story_image); ?>" alt="<?php echo esc_attr($story_image_alt); ?>" loading="lazy" decoding="async">
                        <?php if ($story_image_pill !== '') : ?>
                            <span class="hireai-about-story__pill"><?php echo hireai_svg('sparkle', 15, 'hireai-about-icon'); ?><?php echo esc_html($story_image_pill); ?></span>
                        <?php endif; ?>
                        <figcaption class="hireai-about-story__overlay">
                            <p class="hireai-about-story__overlay-kicker"><?php echo esc_html($story_image_kicker); ?></p>
                            <p class="hireai-about-story__overlay-title"><?php echo esc_html($story_image_title); ?></p>
                        </figcaption>
                    </figure>
                    <aside class="hireai-about-story__card">
                        <p class="hireai-about-story__card-kicker"><?php echo hireai_svg('shield', 16, 'hireai-about-icon'); ?><?php echo esc_html($story_card_kicker); ?></p>
                        <blockquote class="hireai-about-story__card-quote"><?php echo esc_html($story_card_quote); ?></blockquote>
                    </aside>
                </div>
                <div>
                    <h2 id="hireai-about-story-title" class="hireai-about-story__title"><?php echo esc_html($story_title); ?></h2>
                    <div class="hireai-about-story__body">
                        <p><?php echo esc_html($story_paragraph_1); ?></p>
                        <p><?php echo esc_html($story_paragraph_2); ?></p>
                        <blockquote class="hireai-about-story__pull"><?php echo esc_html($story_pull_quote); ?></blockquote>
                        <p><?php echo esc_html($story_paragraph_3); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hireai-about-section hireai-about-section--white" aria-labelledby="hireai-about-method-title">
        <div class="hireai-about-container">
            <div class="hireai-about-method">
                <p class="hireai-about-tag hireai-about-tag--center"><span class="hireai-about-tag__number"><?php echo esc_html($method_number); ?></span><span class="hireai-about-tag__line" aria-hidden="true"></span><?php echo esc_html($method_kicker); ?></p>
                <h2 id="hireai-about-method-title" class="hireai-about-method__title"><?php echo esc_html($method_title); ?></h2>
                <p class="hireai-about-method__intro"><?php echo esc_html($method_intro); ?></p>
                <?php if ($method_image !== '') : ?>
                    <figure class="hireai-about-method__image">
                        <img src="<?php echo esc_url($method_image); ?>" alt="<?php echo esc_attr($method_image_alt); ?>" loading="lazy" decoding="async">
                    </figure>
                <?php endif; ?>
                <ol class="hireai-about-process">
                    <?php foreach ($method_items as $index => $item) : ?>
                        <li class="hireai-about-process__item">
                            <div class="hireai-about-process__head">
                                <span class="hireai-about-process__number"><?php echo esc_html($item['number']); ?></span>
                                <?php echo hireai_svg($index === 0 ? 'arrow-forward' : ($index === 1 ? 'sparkle' : 'shield'), 24, 'hireai-about-icon'); ?>
                            </div>
                            <span class="hireai-about-process__phase"><?php echo esc_html($item['phase']); ?></span>
                            <h3 class="hireai-about-process__title"><?php echo esc_html($item['title']); ?></h3>
                            <p class="hireai-about-process__text"><?php echo esc_html($item['description']); ?></p>
                            <div class="hireai-about-process__meta">
                                <span><?php echo esc_html($item['meta']); ?></span>
                                <?php echo hireai_svg('arrow-forward', 16, 'hireai-about-icon'); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </section>

    <section class="hireai-about-cta" aria-labelledby="hireai-about-cta-title">
        <div class="hireai-about-container">
            <div class="hireai-about-cta__card">
                <p class="hireai-about-cta__kicker"><?php echo esc_html($cta_kicker); ?></p>
                <h2 id="hireai-about-cta-title" class="hireai-about-cta__title"><?php echo esc_html($cta_title); ?></h2>
                <p class="hireai-about-cta__subtitle"><?php echo esc_html($cta_subtitle); ?></p>
                <div class="hireai-about-cta__actions">
                    <a class="hireai-about-cta__button hireai-about-cta__button--primary" href="<?php echo esc_url($cta_primary_url); ?>"><?php echo esc_html($cta_primary_label); ?><?php echo hireai_svg('east', 16, 'hireai-about-icon'); ?></a>
                    <a class="hireai-about-cta__button hireai-about-cta__button--secondary" href="<?php echo esc_url($cta_secondary_url); ?>"><?php echo esc_html($cta_secondary_label); ?><?php echo hireai_svg('arrow-forward', 16, 'hireai-about-icon'); ?></a>
                </div>
                <p class="hireai-about-cta__assurance">
                    <span><?php echo hireai_svg('shield', 16, 'hireai-about-icon'); ?><?php echo esc_html($cta_assurance_1); ?></span>
                    <span><?php echo hireai_svg('shield-star', 16, 'hireai-about-icon'); ?><?php echo esc_html($cta_assurance_2); ?></span>
                </p>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
