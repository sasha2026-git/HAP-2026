<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 案例&洞察
 * The Archive of Excellence — Cases & Insights v5.0
 * Reference: hireai-cases-archive-reference.html (authoritative design)
 * ACF: caseRepeater (cases), pure-JS filter + pagination, CN/EN toggle.
 */
get_header();

$lang        = function_exists('pll_current_language') ? pll_current_language() : 'zh';
$site_suffix = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '_zh';
$is_en       = ($site_suffix === '_en');

$contact_url = home_url('/contact/');
if (function_exists('get_page_by_path')) {
    $contact_page = get_page_by_path('contact');
    if ($contact_page instanceof WP_Post) {
        $contact_url = get_permalink($contact_page);
    }
}

$default_img = function ($i) {
    return get_stylesheet_directory_uri() . '/assets/img/defaults/case-' . $i . '.jpg';
};

$bi = function ($value) {
    if (is_array($value)) {
        return [
            'zh' => isset($value['zh']) ? (string) $value['zh'] : '',
            'en' => isset($value['en']) ? (string) $value['en'] : '',
        ];
    }
    $v = (string) $value;
    return ['zh' => $v, 'en' => $v];
};

$case_bi = function ($c, $key, $fallback = '') use ($bi) {
    if (is_array($c)) {
        if (array_key_exists($key . '_zh', $c) || array_key_exists($key . '_en', $c)) {
            return [
                'zh' => isset($c[$key . '_zh']) ? (string) $c[$key . '_zh'] : '',
                'en' => isset($c[$key . '_en']) ? (string) $c[$key . '_en'] : '',
            ];
        }
        if (array_key_exists($key, $c)) {
            return $bi($c[$key]);
        }
    }
    return $bi($fallback);
};

$img_url = function ($c) {
    if (!is_array($c)) {
        return is_string($c) ? $c : '';
    }
    if (!empty($c['image'])) {
        $img = $c['image'];
        if (is_array($img) && !empty($img['url'])) {
            return $img['url'];
        }
        if (is_numeric($img)) {
            $u = wp_get_attachment_image_url((int) $img, 'full');
            return $u ? $u : '';
        }
        return (string) $img;
    }
    if (!empty($c['url'])) {
        return (string) $c['url'];
    }
    return '';
};

$link_url = function ($c) {
    if (is_array($c) && !empty($c['link'])) {
        $l = $c['link'];
        if (is_array($l) && !empty($l['url'])) {
            return (string) $l['url'];
        }
        if (is_string($l) && $l !== '') {
            return $l;
        }
    }
    return '#';
};

/* ── Fallback cases (matched to the Archive design copy) ── */
$fallback_cases = [
    [
        'image'    => $default_img(1),
        'tag'      => ['zh' => '私人银行', 'en' => 'Private Banking'],
        'metric'   => ['zh' => '+42% 留存', 'en' => '+42% Retention'],
        'category' => ['zh' => '公关审计', 'en' => 'PR Audit'],
        'title'    => ['zh' => '奥瑞利安：私人银行数字礼宾', 'en' => 'Aurelian Prime for Private Banking'],
        'desc'     => ['zh' => '通过为独立个体量身打造的超写实数字礼宾，重塑财富管理体验。', 'en' => 'Reimagining the wealth management experience through a hyper-realistic digital concierge designed for the sovereign individual.'],
        'link'     => '#',
    ],
    [
        'image'    => $default_img(2),
        'tag'      => ['zh' => 'AI 艺术', 'en' => 'AI Art'],
        'metric'   => ['zh' => 'AI 艺术整合', 'en' => 'AI Art Integration'],
        'category' => ['zh' => 'AI 艺术', 'en' => 'AI Art'],
        'title'    => ['zh' => 'Lumina NFT 系列', 'en' => 'Lumina NFT Series'],
        'desc'     => ['zh' => '融合生成算法与传统工艺的独家 IP 合作。', 'en' => 'An exclusive IP collaboration merging generative algorithms with heritage craftsmanship.'],
        'link'     => '#',
    ],
    [
        'image'    => $default_img(3),
        'tag'      => ['zh' => '电商', 'en' => 'E-commerce'],
        'metric'   => ['zh' => '3.4 倍转化', 'en' => '3.4x Conversion'],
        'category' => ['zh' => '电商', 'en' => 'E-commerce'],
        'title'    => ['zh' => '电商进化', 'en' => 'E-commerce Evolution'],
        'desc'     => ['zh' => '通过个性化数字孪生顾问，实现奢侈零售业绩规模化增长。', 'en' => 'Luxury retail performance scaling through personalized digital twin advisors.'],
        'link'     => '#',
    ],
    [
        'image'    => $default_img(4),
        'tag'      => ['zh' => 'IP 资产库', 'en' => 'IP Vault'],
        'metric'   => ['zh' => 'IP 保护 100%', 'en' => 'IP Protection 100%'],
        'category' => ['zh' => 'IP 合作', 'en' => 'IP Collaboration'],
        'title'    => ['zh' => '数字 IP 资产库', 'en' => 'The Digital IP Vault'],
        'desc'     => ['zh' => '面向 AI 整合型奢侈资产，提供全球公关审计与声誉管理。', 'en' => 'Global PR audit and reputation management for AI-integrated luxury estates.'],
        'link'     => '#',
    ],
];

/* ── ACF repeater → normalized JSON ── */
$cases = site_field('caseRepeater', []);
if (!is_array($cases)) {
    $cases = [];
}
$cases = count($cases) > 0 ? array_values($cases) : $fallback_cases;

$normalized = [];
foreach ($cases as $c) {
    $normalized[] = [
        'image'    => $img_url($c),
        'link'     => $link_url($c),
        'tag'      => $case_bi($c, 'tag', ['zh' => '', 'en' => '']),
        'metric'   => $case_bi($c, 'metric', ['zh' => '', 'en' => '']),
        'category' => $case_bi($c, 'category', ['zh' => '', 'en' => '']),
        'title'    => $case_bi($c, 'title', ['zh' => '', 'en' => '']),
        'desc'     => $case_bi($c, 'desc', ['zh' => '', 'en' => '']),
    ];
}
$cases_json = wp_json_encode($normalized, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

<style>
.ci-archive, .ci-archive *, .ci-archive *::before, .ci-archive *::after { box-sizing: border-box; }

.ci-archive {
  --surface: #faf9f9;
  --surface-container-low: #f4f3f3;
  --surface-container: #eeeeee;
  --on-surface: #1a1c1c;
  --on-surface-variant: #444748;
  --on-primary: #ffffff;
  --on-secondary: #ffffff;
  --on-secondary-container: #785a1a;
  --on-tertiary-container: #848480;
  --gold-primary: #775a19;
  --gold-light: #e9c176;
  --gold-soft: #fed488;
  --secondary-fixed: #ffdea5;
  --secondary-container: #fed488;
  --tertiary: #000000;
  --primary: #000000;
  --outline: #747878;
  --outline-variant: #c4c7c7;
  --bg-alpha: rgba(250, 249, 249, 0.70);
  --bg-alpha-solid: rgba(250, 249, 249, 0.92);
  --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  --font-serif: 'Playfair Display', Georgia, serif;
  --spring: cubic-bezier(0.4, 0, 0.2, 1);
  margin: 0;
  overflow-x: hidden;
  background: var(--surface);
  color: var(--on-surface);
  font-family: var(--font-body);
  line-height: 1.6;
  -webkit-font-smoothing: antialiased;
}

/* ── Language toggle visibility ── */
.ci-archive .lang-zh,
.ci-archive .lang-en { display: none; }
.ci-archive .ci-lang-inline .lang-zh,
.ci-archive .ci-lang-inline .lang-en { display: none; }
.ci-archive .ci-lang-block .lang-zh,
.ci-archive .ci-lang-block .lang-en { display: none; }
.ci-archive .ci-lang-flex .lang-zh,
.ci-archive .ci-lang-flex .lang-en { display: none; }
.ci-archive[data-lang="zh"] .ci-lang-inline .lang-zh { display: inline; }
.ci-archive[data-lang="en"] .ci-lang-inline .lang-en { display: inline; }
.ci-archive[data-lang="zh"] .ci-lang-block .lang-zh { display: block; }
.ci-archive[data-lang="en"] .ci-lang-block .lang-en { display: block; }
.ci-archive[data-lang="zh"] .ci-lang-flex .lang-zh { display: inline-flex; }
.ci-archive[data-lang="en"] .ci-lang-flex .lang-en { display: inline-flex; }

/* ── Fixed top nav ── */
.ci-nav {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 60;
  background: var(--bg-alpha);
  -webkit-backdrop-filter: blur(20px);
  backdrop-filter: blur(20px);
  border-bottom: 0.5px solid var(--outline-variant);
  box-shadow: 0 1px 24px rgba(0, 0, 0, 0.04);
  transition: background 0.4s var(--spring), box-shadow 0.4s var(--spring), height 0.4s var(--spring);
}
.ci-nav.is-scrolled {
  background: var(--bg-alpha-solid);
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
}
.ci-nav__inner {
  max-width: 1440px;
  margin: 0 auto;
  padding: 0 20px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  transition: height 0.4s var(--spring);
}
.ci-nav.is-scrolled .ci-nav__inner { height: 64px; }
@media (min-width: 768px) {
  .ci-nav__inner { padding: 0 80px; }
}
.ci-nav__brand {
  font-family: var(--font-serif);
  font-size: 18px;
  font-weight: 600;
  letter-spacing: 0.02em;
  line-height: 1.15;
  color: var(--on-surface);
  text-decoration: none;
  white-space: nowrap;
}
.ci-nav__links {
  display: none;
  align-items: center;
  gap: 24px;
}
@media (min-width: 768px) { .ci-nav__links { display: flex; } }
.ci-nav__link {
  font-size: 14px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--on-surface-variant);
  text-decoration: none;
  transition: opacity 0.3s;
}
.ci-nav__link:hover { opacity: 0.72; }
.ci-nav__right { display: flex; align-items: center; gap: 18px; }
.ci-nav__icons { display: none; align-items: center; gap: 16px; }
@media (min-width: 768px) { .ci-nav__icons { display: flex; } }
.ci-nav-icon {
  color: var(--on-surface-variant);
  font-size: 24px;
  line-height: 1;
  cursor: pointer;
  user-select: none;
  transition: opacity 0.3s;
}
.ci-nav-icon:hover { opacity: 0.72; }
.ci-nav__lang { display: flex; gap: 4px; }
.ci-lang-btn {
  font-family: var(--font-body);
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  padding: 6px 12px;
  border: 1px solid var(--outline-variant);
  border-radius: 9999px;
  background: transparent;
  color: var(--on-surface-variant);
  cursor: pointer;
  transition: all 0.3s var(--spring);
  line-height: 1.2;
}
.ci-lang-btn.is-active {
  background: var(--primary);
  color: var(--on-primary);
  border-color: var(--primary);
}
.ci-btn-consult {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px 24px;
  border-radius: 9999px;
  background: var(--primary);
  color: var(--on-primary);
  font-family: var(--font-body);
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  text-decoration: none;
  white-space: nowrap;
  transition: box-shadow 0.3s var(--spring), background 0.3s, color 0.3s;
}
.ci-btn-consult:hover { box-shadow: 0 0 15px rgba(119, 90, 25, 0.35); }

/* ── Hero ── */
.ci-hero {
  position: relative;
  padding: 150px 20px 120px;
  text-align: center;
}
@media (min-width: 768px) { .ci-hero { padding: 150px 80px 120px; } }
.ci-hero__kicker {
  display: block;
  margin-bottom: 24px;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.3em;
  text-transform: uppercase;
  color: var(--gold-primary);
}
.ci-hero__title {
  font-family: var(--font-serif);
  font-size: 42px;
  line-height: 1.1;
  letter-spacing: -0.02em;
  font-weight: 700;
  color: var(--on-surface);
  margin: 0 auto 32px;
  max-width: 54rem;
}
@media (min-width: 768px) { .ci-hero__title { font-size: 72px; max-width: 56rem; } }
.ci-hero__title .burnished {
  font-style: italic;
  background: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  color: transparent;
}
.ci-hero__rule {
  width: 1px;
  height: 96px;
  margin: 48px auto 0;
  background: linear-gradient(to bottom, var(--gold-primary), transparent);
}

/* ── Category filter ── */
.ci-filter {
  max-width: 1440px;
  margin: 0 auto 64px;
  padding: 0 20px;
}
@media (min-width: 768px) { .ci-filter { padding: 0 80px; } }
.ci-filter__row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 16px 32px;
  border-top: 1px solid var(--outline-variant);
  border-bottom: 1px solid var(--outline-variant);
  padding: 32px 0;
}
.ci-filter__btn {
  font-family: var(--font-body);
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--on-surface-variant);
  background: none;
  border: 0;
  border-bottom: 2px solid transparent;
  padding: 6px 2px 8px;
  cursor: pointer;
  transition: color 0.3s, border-color 0.3s;
}
.ci-filter__btn:hover { color: var(--primary); }
.ci-filter__btn.is-active { color: var(--primary); border-bottom-color: var(--primary); }

/* ── Editorial grid ── */
.ci-main {
  max-width: 1440px;
  margin: 0 auto;
  padding: 0 20px 120px;
}
@media (min-width: 768px) { .ci-main { padding: 0 80px 120px; } }
.ci-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 28px;
  align-items: start;
}
@media (min-width: 768px) {
  .ci-grid {
    grid-template-columns: repeat(12, 1fr);
    gap: 24px 64px;
  }
}
.ci-case { display: block; color: inherit; text-decoration: none; }
.ci-case.pos-1 { grid-column: span 8; }
.ci-case.pos-2 { grid-column: span 4; margin-top: 128px; }
.ci-case.pos-3 { grid-column: span 6; }
.ci-case.pos-4 { grid-column: span 6; margin-top: 96px; }
@media (max-width: 768px) {
  .ci-grid {
    grid-template-columns: 1fr !important;
    gap: 24px;
  }
  .ci-case.pos-1, .ci-case.pos-2, .ci-case.pos-3, .ci-case.pos-4 {
    grid-column: 1 / -1 !important;
    margin-top: 0 !important;
  }
  .ci-case__media {
    aspect-ratio: 4 / 3 !important;
  }
  .ci-case.pos-1 .ci-case__media { aspect-ratio: 16 / 9 !important; }
  .ci-case.pos-2 .ci-case__media { aspect-ratio: 3 / 4 !important; }
  .ci-case.pos-3 .ci-case__media { aspect-ratio: 1 / 1 !important; }
  .ci-case.pos-4 .ci-case__media { aspect-ratio: 4 / 5 !important; }
  .ci-case__title {
    font-size: 22px !important;
    line-height: 1.3;
    margin-bottom: 8px;
  }
  .ci-case.pos-2 .ci-case__title { font-size: 20px !important; }
  .ci-case__desc {
    font-size: 14px !important;
    line-height: 1.65;
  }
  .ci-case.pos-2 .ci-case__desc { font-size: 14px !important; }
  .ci-case__body { padding: 0 0 8px; }
  .ci-case__badge {
    font-size: 11px;
    padding: 6px 14px;
  }
  .ci-hero__title { font-size: 28px !important; }
  .ci-hero { padding: 120px 20px 80px; }
  .ci-cta__btn {
    display: flex !important;
    width: 100% !important;
    padding: 14px 24px !important;
    box-sizing: border-box;
  }
  .ci-cta__title { font-size: 24px !important; margin-bottom: 20px; }
  .ci-cta__desc { font-size: 14px !important; margin-bottom: 32px; }
  .ci-cta { padding: 80px 20px !important; }
  .ci-footer { padding: 60px 20px !important; }
  .ci-footer__links { gap: 12px 24px; }
  .ci-nav__inner { padding: 0 16px; }
  .ci-filter { padding: 0 16px; margin-bottom: 32px; }
  .ci-filter__row { gap: 12px 20px; padding: 20px 0; }
  .ci-main { padding: 0 16px 80px; }
}
@media (min-width: 769px) and (max-width: 1024px) {
  .ci-case.pos-1, .ci-case.pos-2, .ci-case.pos-3, .ci-case.pos-4 {
    grid-column: span 6;
    margin-top: 0;
  }
}
.ci-case__media {
  position: relative;
  overflow: hidden;
  margin-bottom: 32px;
  border: 0.5px solid var(--outline-variant);
  box-shadow: 0 40px 40px -20px rgba(119, 90, 25, 0.06);
  background: var(--surface-container-low);
}
.ci-case.pos-1 .ci-case__media { aspect-ratio: 16 / 9; }
.ci-case.pos-2 .ci-case__media { aspect-ratio: 3 / 4; }
.ci-case.pos-3 .ci-case__media { aspect-ratio: 1 / 1; }
.ci-case.pos-4 .ci-case__media { aspect-ratio: 4 / 5; }
.ci-case__img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.7s var(--spring);
}
.ci-case:hover .ci-case__img { transform: scale(1.05); }
.ci-case__badge {
  position: absolute;
  z-index: 2;
  display: inline-flex;
  align-items: center;
  padding: 8px 20px;
  border-radius: 9999px;
  background: rgba(249, 248, 243, 0.7);
  -webkit-backdrop-filter: blur(20px);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(119, 90, 25, 0.2);
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.05em;
  color: var(--gold-primary);
  white-space: nowrap;
}
.ci-case.pos-1 .ci-case__badge { top: 24px; right: 24px; }
.ci-case.pos-2 .ci-case__badge { bottom: 24px; left: 24px; }
.ci-case.pos-3 .ci-case__badge { top: 24px; left: 24px; }
.ci-case.pos-4 .ci-case__badge { bottom: 24px; right: 24px; }
.ci-case__body { padding: 0 4px 8px; }
.ci-case__tag {
  display: block;
  font-size: 12px;
  font-weight: 500;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  color: var(--gold-primary);
  margin-bottom: 8px;
}
.ci-case__title {
  font-family: var(--font-serif);
  font-size: clamp(24px, 2.2vw, 32px);
  line-height: 1.3;
  font-weight: 500;
  color: var(--on-surface);
  margin-bottom: 12px;
  transition: color 0.3s;
}
.ci-case:hover .ci-case__title { color: var(--gold-primary); }
.ci-case.pos-2 .ci-case__title { font-size: clamp(20px, 1.8vw, 28px); }
.ci-case__desc {
  font-size: clamp(14px, 1.05vw, 18px);
  line-height: 1.6;
  color: var(--on-surface-variant);
  max-width: 42rem;
}
.ci-case.pos-2 .ci-case__desc { font-size: 16px; }
.ci-empty {
  grid-column: 1 / -1;
  text-align: center;
  padding: 60px 20px;
  color: var(--on-surface-variant);
  font-size: 14px;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

/* ── Pagination ── */
.ci-pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 24px;
  margin-top: 48px;
}
.ci-pagination__btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-family: var(--font-body);
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: 10px 28px;
  border: 1px solid var(--outline-variant);
  border-radius: 9999px;
  background: transparent;
  color: var(--on-surface);
  cursor: pointer;
  transition: all 0.3s var(--spring);
}
.ci-pagination__btn:hover {
  background: var(--gold-primary);
  color: #fff;
  border-color: var(--gold-primary);
}
.ci-pagination__btn:disabled { opacity: 0.35; cursor: default; pointer-events: none; }
.ci-pagination__dots { display: flex; gap: 8px; align-items: center; }
.ci-pagination__dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--outline-variant);
  cursor: pointer;
  transition: all 0.3s var(--spring);
}
.ci-pagination__dot.is-active {
  background: var(--gold-primary);
  width: 24px;
  border-radius: 4px;
}

/* ── CTA ── */
.ci-cta {
  position: relative;
  background: var(--tertiary);
  color: var(--on-tertiary);
  overflow: hidden;
  padding: 120px 20px;
  text-align: center;
}
@media (min-width: 768px) { .ci-cta { padding: 120px 80px; } }
.ci-cta::before {
  content: '';
  position: absolute;
  inset: 0;
  opacity: 0.2;
  background: radial-gradient(circle at 50% 50%, #775a19 0%, transparent 70%);
  pointer-events: none;
}
.ci-cta__inner { position: relative; z-index: 1; max-width: 1000px; margin: 0 auto; }
.ci-cta__title {
  font-family: var(--font-serif);
  font-size: 32px;
  line-height: 1.2;
  font-weight: 600;
  margin: 0 auto 32px;
  max-width: 800px;
}
@media (min-width: 768px) { .ci-cta__title { font-size: 48px; } }
.ci-cta__desc {
  font-size: clamp(15px, 1.2vw, 18px);
  line-height: 1.6;
  color: var(--on-tertiary-container);
  max-width: 600px;
  margin: 0 auto 48px;
}
.ci-cta__btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 16px 48px;
  border-radius: 9999px;
  border: 0;
  cursor: pointer;
  background: linear-gradient(135deg, #775a19 0%, #fed488 55%, #e9c176 100%);
  color: #261900;
  font-family: var(--font-body);
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  text-decoration: none;
  transition: transform 0.3s var(--spring), box-shadow 0.3s var(--spring), background 0.3s;
}
.ci-cta__btn:hover {
  background: #ffffff;
  color: var(--on-surface);
  box-shadow: 0 12px 40px rgba(119, 90, 25, 0.35);
  transform: translateY(-2px);
}

/* ── Footer ── */
.ci-footer {
  background: var(--surface);
  border-top: 1px solid var(--outline-variant);
  padding: 120px 20px;
  text-align: center;
}
@media (min-width: 768px) { .ci-footer { padding: 120px 80px; } }
.ci-footer__brand {
  display: inline-block;
  font-family: var(--font-serif);
  font-size: 20px;
  font-weight: 600;
  color: var(--on-surface);
  margin-bottom: 48px;
}
.ci-footer__links {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 16px 32px;
  margin-bottom: 48px;
}
.ci-footer__link {
  font-size: 12px;
  font-weight: 500;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--on-surface-variant);
  text-decoration: none;
  transition: color 0.3s;
}
.ci-footer__link:hover { color: var(--gold-primary); }
.ci-footer__copy {
  font-size: 12px;
  font-weight: 500;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--on-tertiary-container);
}
</style>

<div class="ci-archive" id="ciArchive" data-lang="<?php echo esc_attr($lang); ?>">

<!-- Fixed Top Navbar (in-page minimal) -->
<nav class="ci-nav" aria-label="<?php echo esc_attr($is_en ? 'Primary' : '主导航'); ?>">
    <div class="ci-nav__inner">
        <a class="ci-nav__brand" href="<?php echo esc_url(home_url('/')); ?>">The Archive of Excellence</a>
        <div class="ci-nav__links">
            <a class="ci-nav__link ci-lang-inline" href="#archiveMain"><span class="lang-zh">数字人文</span><span class="lang-en">Digital Humanity</span></a>
        </div>
        <div class="ci-nav__right">
            <div class="ci-nav__lang" role="group" aria-label="<?php echo esc_attr($is_en ? 'Language' : '语言'); ?>">
                <button type="button" class="ci-lang-btn <?php echo $lang === 'zh' ? 'is-active' : ''; ?>" data-lang="zh">中文</button>
                <button type="button" class="ci-lang-btn <?php echo $lang === 'en' ? 'is-active' : ''; ?>" data-lang="en">EN</button>
            </div>
            <div class="ci-nav__icons">
                <svg class="ci-nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <svg class="ci-nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
            </div>
            <a class="ci-btn-consult ci-lang-inline" href="<?php echo esc_url($contact_url); ?>"><span class="lang-zh">预约咨询</span><span class="lang-en">Consultation</span></a>
        </div>
    </div>
</nav>

<!-- Hero -->
<header class="ci-hero">
    <span class="ci-hero__kicker ci-lang-inline"><span class="lang-zh">卓越档案</span><span class="lang-en">The Archive of Excellence</span></span>
    <h1 class="ci-hero__title">
        <span class="ci-lang-inline"><span class="lang-zh">定义数字人文的</span><span class="lang-en">Defining the Future of</span></span><br>
        <span class="burnished ci-lang-inline"><span class="lang-zh">未来</span><span class="lang-en">Digital Humanity</span></span>
    </h1>
    <div class="ci-hero__rule" aria-hidden="true"></div>
</header>

<!-- Category Filter -->
<section class="ci-filter" aria-label="<?php echo esc_attr($is_en ? 'Case collections' : '案例分类'); ?>">
    <div class="ci-filter__row">
        <button type="button" class="ci-filter__btn is-active ci-lang-inline" data-cat="" data-cat-zh=""><span class="lang-zh">全部案例</span><span class="lang-en">All Collections</span></button>
        <button type="button" class="ci-filter__btn ci-lang-inline" data-cat="PR Audit" data-cat-zh="公关审计"><span class="lang-zh">公关审计</span><span class="lang-en">PR Audit</span></button>
        <button type="button" class="ci-filter__btn ci-lang-inline" data-cat="IP Collaboration" data-cat-zh="IP 合作"><span class="lang-zh">IP 合作</span><span class="lang-en">IP Collaboration</span></button>
        <button type="button" class="ci-filter__btn ci-lang-inline" data-cat="E-commerce" data-cat-zh="电商"><span class="lang-zh">电商</span><span class="lang-en">E-commerce</span></button>
        <button type="button" class="ci-filter__btn ci-lang-inline" data-cat="AI Art" data-cat-zh="AI 艺术"><span class="lang-zh">AI 艺术</span><span class="lang-en">AI Art</span></button>
    </div>
</section>

<!-- Editorial Grid (The Archive) -->
<main class="ci-main" id="archiveMain">
    <div class="ci-grid" id="ciGrid"></div>
    <div class="ci-pagination" id="ciPagination">
        <button class="ci-pagination__btn ci-pagination__prev ci-lang-inline" type="button" disabled>
            <span aria-hidden="true">&lt;</span><span class="lang-zh">上一页</span><span class="lang-en">Prev</span>
        </button>
        <div class="ci-pagination__dots" aria-label="<?php echo esc_attr($is_en ? 'Pages' : '分页'); ?>"></div>
        <button class="ci-pagination__btn ci-pagination__next ci-lang-inline" type="button">
            <span class="lang-zh">下一页</span><span class="lang-en">Next</span><span aria-hidden="true">&gt;</span>
        </button>
    </div>
</main>

<!-- Consultation CTA -->
<section class="ci-cta" id="contact">
    <div class="ci-cta__inner">
        <h2 class="ci-cta__title ci-lang-block"><span class="lang-zh">准备好定义你的传承了吗？</span><span class="lang-en">Ready to define your legacy?</span></h2>
        <p class="ci-cta__desc ci-lang-block"><span class="lang-zh">与全球领先品牌和家族办公室一起，进入数字人文卓越的新时代。</span><span class="lang-en">Join the world's leading brands and family offices in the new era of digital human excellence.</span></p>
        <a class="ci-cta__btn ci-lang-inline" href="<?php echo esc_url($contact_url); ?>"><span class="lang-zh">发起咨询</span><span class="lang-en">Initiate Consultation</span></a>
    </div>
</section>

<!-- Footer (in-page minimal) -->
<footer class="ci-footer">
    <div class="ci-footer__brand">The Archive of Excellence</div>
    <div class="ci-footer__links">
        <a class="ci-footer__link ci-lang-inline" href="#"><span class="lang-zh">隐私政策</span><span class="lang-en">Privacy Policy</span></a>
        <a class="ci-footer__link ci-lang-inline" href="#"><span class="lang-zh">服务条款</span><span class="lang-en">Terms of Service</span></a>
        <a class="ci-footer__link ci-lang-inline" href="#"><span class="lang-zh">媒体</span><span class="lang-en">Press</span></a>
        <a class="ci-footer__link ci-lang-inline" href="#"><span class="lang-zh">联系</span><span class="lang-en">Contact</span></a>
    </div>
    <p class="ci-footer__copy ci-lang-inline"><span class="lang-zh">© 2024 HireAIPeople。数字人文卓越新时代。</span><span class="lang-en">© 2024 HireAIPeople. The New Era of Digital Human Excellence.</span></p>
</footer>

<script type="application/json" id="archiveCases"><?php echo $cases_json; ?></script>
</div>

<script>
(function () {
    var page = document.getElementById('ciArchive');
    if (!page) return;

    var source = document.getElementById('archiveCases');
    var allCases = [];
    try { allCases = source ? JSON.parse(source.textContent || '[]') : []; } catch (e) { allCases = []; }

    var grid = document.getElementById('ciGrid');
    var pagination = document.getElementById('ciPagination');
    var filterBtns = Array.prototype.slice.call(page.querySelectorAll('.ci-filter__btn'));
    var langBtns = Array.prototype.slice.call(page.querySelectorAll('.ci-lang-btn'));
    var prevBtn = pagination.querySelector('.ci-pagination__prev');
    var nextBtn = pagination.querySelector('.ci-pagination__next');
    var dotsEl = pagination.querySelector('.ci-pagination__dots');

    var PER_PAGE = 4;
    var activeCat = '';
    var activePage = 0;
    var currentLang = page.getAttribute('data-lang') || 'en';

    function catOf(c) {
        return {
            zh: (c.category && c.category.zh) || '',
            en: (c.category && c.category.en) || ''
        };
    }
    function matches(c) {
        if (!activeCat) return true;
        var cat = catOf(c);
        return cat.en === activeCat || cat.zh === activeCat ||
            cat.zh === (filterBtnCatZh(activeCat) || activeCat) ||
            cat.en === (filterBtnCatZh(activeCat) || activeCat);
    }
    function filterBtnCatZh(en) {
        var b = filterBtns.filter(function (x) { return x.getAttribute('data-cat') === en; })[0];
        return b ? b.getAttribute('data-cat-zh') || '' : '';
    }
    function filtered() {
        return allCases.filter(matches);
    }

    function biSpan(bi, cls) {
        var wrap = document.createElement('span');
        wrap.className = cls;
        var zh = document.createElement('span');
        zh.className = 'lang-zh';
        zh.textContent = (bi && bi.zh) || '';
        var en = document.createElement('span');
        en.className = 'lang-en';
        en.textContent = (bi && bi.en) || '';
        wrap.appendChild(zh);
        wrap.appendChild(en);
        return wrap;
    }

    function buildCard(c, pos) {
        var card = document.createElement('a');
        card.className = 'ci-case pos-' + pos;
        card.href = c.link || '#';

        var media = document.createElement('div');
        media.className = 'ci-case__media';

        var gradients = [
            'linear-gradient(145deg, #1a1207 0%, #3d2b0f 40%, #5a3d1a 70%, #1a1207 100%)',
            'linear-gradient(145deg, #0d1117 0%, #1b2838 40%, #2d4a6f 70%, #0d1117 100%)',
            'linear-gradient(145deg, #1a0a1e 0%, #3d1a45 40%, #5a2d6e 70%, #1a0a1e 100%)',
            'linear-gradient(145deg, #0a1a1a 0%, #1a3d3d 40%, #2d5a5a 70%, #0a1a1a 100%)',
        ];
        media.className += ' ci-case__media--grad-' + pos;
        media.style.background = gradients[(pos - 1) % gradients.length];

        if (c.metric && (c.metric.zh || c.metric.en)) {
            var badge = document.createElement('span');
            badge.className = 'ci-case__badge ci-lang-flex';
            badge.appendChild(biSpan(c.metric, ''));
            media.appendChild(badge);
        }

        var body = document.createElement('div');
        body.className = 'ci-case__body';

        if (c.tag && (c.tag.zh || c.tag.en)) {
            var tag = document.createElement('div');
            tag.className = 'ci-case__tag ci-lang-block';
            tag.appendChild(biSpan(c.tag, ''));
            body.appendChild(tag);
        }

        var title = document.createElement('h3');
        title.className = 'ci-case__title ci-lang-block';
        title.appendChild(biSpan(c.title || {}, ''));
        body.appendChild(title);

        if (c.desc && (c.desc.zh || c.desc.en)) {
            var desc = document.createElement('p');
            desc.className = 'ci-case__desc ci-lang-block';
            desc.appendChild(biSpan(c.desc, ''));
            body.appendChild(desc);
        }

        card.appendChild(media);
        card.appendChild(body);
        return card;
    }

    function writeHash() {
        var h = '#cases=' + activePage;
        try { history.replaceState(null, '', h); } catch (e) { window.location.hash = h; }
    }
    function readHash() {
        var m = window.location.hash.match(/cases=(\d+)/);
        return m ? parseInt(m[1], 10) : 0;
    }

    function renderPagination(totalPages) {
        dotsEl.innerHTML = '';
        for (var i = 0; i < totalPages; i++) {
            (function (idx) {
                var d = document.createElement('span');
                d.className = 'ci-pagination__dot' + (idx === activePage ? ' is-active' : '');
                d.setAttribute('data-page', idx);
                d.addEventListener('click', function () {
                    activePage = idx;
                    writeHash();
                    render();
                });
                dotsEl.appendChild(d);
            }(i));
        }
        prevBtn.disabled = activePage === 0;
        nextBtn.disabled = activePage >= totalPages - 1;
    }

    function render() {
        var list = filtered();
        var totalPages = Math.max(1, Math.ceil(list.length / PER_PAGE));
        if (activePage >= totalPages) activePage = totalPages - 1;
        if (activePage < 0) activePage = 0;

        grid.innerHTML = '';
        if (list.length === 0) {
            var empty = document.createElement('p');
            empty.className = 'ci-empty ci-lang-inline';
            empty.appendChild(biSpan({ zh: '暂无案例', en: 'No cases found' }, ''));
            grid.appendChild(empty);
        } else {
            var items = list.slice(activePage * PER_PAGE, activePage * PER_PAGE + PER_PAGE);
            items.forEach(function (c, i) {
                grid.appendChild(buildCard(c, i + 1));
            });
        }
        renderPagination(totalPages);
    }

    prevBtn.addEventListener('click', function () {
        if (activePage > 0) { activePage--; writeHash(); render(); }
    });
    nextBtn.addEventListener('click', function () {
        var list = filtered();
        var totalPages = Math.max(1, Math.ceil(list.length / PER_PAGE));
        if (activePage < totalPages - 1) { activePage++; writeHash(); render(); }
    });

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            activeCat = btn.getAttribute('data-cat') || '';
            activePage = 0;
            filterBtns.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
            writeHash();
            render();
        });
    });

    langBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            currentLang = btn.getAttribute('data-lang');
            page.setAttribute('data-lang', currentLang);
            langBtns.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
            render();
        });
    });

    var nav = page.querySelector('.ci-nav');
    window.addEventListener('scroll', function () {
        if (nav) nav.classList.toggle('is-scrolled', window.scrollY > 50);
    }, { passive: true });

    activePage = readHash();
    render();
})();
</script>

<?php get_footer(); ?>
