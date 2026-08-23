<?php
/**
 * Template Name: 数字员工详情页 (Aurelian Prime 风格)
 * Description: 单个数字员工的详情页 — Hero / 能力 / 案例 / 定价 / CTA 五大板块。
 *              URL 重写：/employee/<slug>/ -> page=employee&hireai_emp_slug=<slug>
 *              数据源：posts where post_name = <slug> AND category=ai-employee
 * Version: 3.0.3
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$suffix = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '';
$is_en  = ($suffix === '_en');

/* ----- 1. 取数据：通过 query var hireai_emp_slug 拉具体员工 ----- */
$emp_slug = get_query_var('hireai_emp_slug');
$emp_post = null;

if ($emp_slug) {
    $candidates = get_posts([
        'name'        => $emp_slug,
        'post_type'   => 'post',
        'post_status' => 'publish',
        'numberposts' => 1,
        'tax_query'   => [[
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => 'ai-employee',
        ]],
    ]);
    if (!empty($candidates)) {
        $emp_post = $candidates[0];
    }
}

/* ----- 1.1 兜底数据：当 slug 为空或找不到时显示 ----- */
$fallback = [
    'name'        => $is_en ? 'Apex Strategist' : '全国统一者',
    'role'        => $is_en ? 'Strategic Elite' : '战略精英',
    'soul'        => $is_en ? 'Cultivated with a precise psychological profile—quiet, sharp, and shaped by logic.' : '以逻辑为骨、以静谧为魂，被悉心培育出独一无二的心智。',
    'skill'       => $is_en ? 'Masters data analysis, market strategy, and multilingual content creation—ready to collaborate seamlessly with your team.' : '精通数据分析、市场策略与多语言内容创作，可与您的团队无缝协作。',
    'capabilities'=> $is_en ? "Deep market research\nReal-time data analysis\nMultilingual content creation\n24×7 availability" : "深度市场调研\n实时数据分析\n多语言内容创作\n24×7 待命服务",
    'image'       => '',
    'price'       => $is_en ? '¥ 60,000 / mo' : '￥60,000 /月起',
    'retainer'    => $is_en ? 'Starting retainer' : '起步档',
    'cta_label'   => $is_en ? 'Inquire Now' : '立即咨询',
    'cta_url'     => '/contact/',
    'cases_link'  => '/category/cases/',
    'cases_label' => $is_en ? 'View Related Cases' : '查看相关案例',
];

if ($emp_post instanceof WP_Post) {
    $post_id = $emp_post->ID;
    $emp = [
        'name'        => get_the_title($post_id),
        'role'        => hireai_field('employee_role', $fallback['role'], $post_id),
        'soul'        => hireai_field('employee_soul', $fallback['soul'], $post_id),
        'skill'       => hireai_field('employee_skill', $fallback['skill'], $post_id),
        'capabilities'=> hireai_field('employee_capabilities', $fallback['capabilities'], $post_id),
        'image'       => get_the_post_thumbnail_url($post_id, 'full'),
        'price'       => hireai_field('employee_price', $fallback['price'], $post_id),
        'retainer'    => hireai_field('product_retainer_label', $fallback['retainer'], $post_id),
        'cta_label'   => hireai_field('employee_button_text', $fallback['cta_label'], $post_id),
        'cta_url'     => hireai_field('employee_link', $fallback['cta_url'], $post_id),
        'cases_link'  => hireai_field('employee_cases_link', $fallback['cases_link'], $post_id),
        'cases_label' => hireai_field('employee_cases_link_title', $fallback['cases_label'], $post_id),
    ];
    $capabilities = array_filter(array_map('trim', preg_split('/\r?\n/', (string)$emp['capabilities'])));
} else {
    // 父页面或找不到具体员工：展示目录链接
    $emp = $fallback;
    $capabilities = array_filter(array_map('trim', preg_split('/\r?\n/', $fallback['capabilities'])));
}

/* ----- 2. 取站点级 CTA 文案 ----- */
$site_kicker = hireai_field('lookbook_cta_heading', $is_en ? 'Ready to deploy?' : '准备好部署了吗？', 'option');
$site_sub    = hireai_field('lookbook_cta_sub', $is_en ? 'Join the echelon of leaders using our bespoke digital employees.' : '加入正在使用我们专属数字员工的领袖行列。', 'option');
$site_btn    = hireai_field('lookbook_cta_btn', $is_en ? 'Start The Journey' : '开启旅程', 'option');
$site_url    = hireai_field('lookbook_cta_url', '/contact/', 'option');

/* 兜底 logo/字体 */
$logo_url = get_theme_mod('header_logo', get_stylesheet_directory_uri() . '/assets/img/header-logo.svg');

/* ----- 3. 渲染 ----- */
?>
<article class="emp-prime" data-emp-prime-reveal>

  <!-- ============ 1. HERO ============ -->
  <section class="emp-prime__hero" aria-labelledby="emp-hero-name">
    <div class="emp-prime__hero-inner">
      <div class="emp-prime__hero-text">
        <p class="emp-prime__kicker"><?php echo esc_html($emp['role']); ?></p>
        <h1 id="emp-hero-name" class="emp-prime__name"><?php echo esc_html($emp['name']); ?></h1>
        <p class="emp-prime__soul"><?php echo esc_html($emp['soul']); ?></p>
        <div class="emp-prime__hero-actions">
          <a class="emp-prime__btn emp-prime__btn--primary" href="<?php echo esc_url($emp['cta_url']); ?>"><?php echo esc_html($emp['cta_label']); ?></a>
          <a class="emp-prime__btn emp-prime__btn--ghost" href="<?php echo esc_url($emp['cases_link']); ?>"><?php echo esc_html($emp['cases_label']); ?></a>
        </div>
      </div>
      <div class="emp-prime__hero-media">
        <?php if (!empty($emp['image'])): ?>
          <img class="emp-prime__hero-image" src="<?php echo esc_url($emp['image']); ?>" alt="<?php echo esc_attr($emp['name']); ?>" loading="eager" />
        <?php else: ?>
          <div class="emp-prime__hero-placeholder" aria-hidden="true"></div>
        <?php endif; ?>
        <span class="emp-prime__seal">AURELIAN · PRIME</span>
      </div>
    </div>
  </section>

  <!-- ============ 2. CAPABILITY (能力) ============ -->
  <section class="emp-prime__cap" aria-labelledby="emp-cap-head">
    <div class="emp-prime__cap-inner">
      <header class="emp-prime__section-head">
        <p class="emp-prime__kicker"><?php echo esc_html($is_en ? 'CAPABILITIES' : '核心能力'); ?></p>
        <h2 id="emp-cap-head" class="emp-prime__section-title"><?php echo esc_html($is_en ? 'Skill, Soul, and System' : '技能、灵魂与系统'); ?></h2>
      </header>
      <div class="emp-prime__cap-grid">
        <div class="emp-prime__cap-card">
          <h3 class="emp-prime__cap-title"><?php echo esc_html($is_en ? 'SKILL' : '技能'); ?></h3>
          <p><?php echo esc_html($emp['skill']); ?></p>
        </div>
        <div class="emp-prime__cap-card">
          <h3 class="emp-prime__cap-title"><?php echo esc_html($is_en ? 'SOUL' : '灵魂'); ?></h3>
          <p><?php echo esc_html($emp['soul']); ?></p>
        </div>
      </div>
      <ul class="emp-prime__cap-list">
        <?php foreach ($capabilities as $idx => $line): ?>
          <li class="emp-prime__cap-item" style="--emp-i:<?php echo (int)$idx; ?>">
            <span class="emp-prime__cap-num"><?php echo str_pad((string)((int)$idx + 1), 2, '0', STR_PAD_LEFT); ?></span>
            <span class="emp-prime__cap-line"><?php echo esc_html($line); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- ============ 3. CASES (案例) ============ -->
  <section class="emp-prime__cases" aria-labelledby="emp-cases-head">
    <div class="emp-prime__cases-inner">
      <header class="emp-prime__section-head">
        <p class="emp-prime__kicker"><?php echo esc_html($is_en ? 'SELECTED CASES' : '精选案例'); ?></p>
        <h2 id="emp-cases-head" class="emp-prime__section-title"><?php echo esc_html($is_en ? 'Where this employee excels' : '这位员工的擅长领域'); ?></h2>
      </header>
      <?php
      // 取分类 case 的文章，最多 3 篇
      $cases = get_posts([
          'post_type'      => 'post',
          'post_status'    => 'publish',
          'numberposts'    => 3,
          'tax_query'      => [[
              'taxonomy' => 'category',
              'field'    => 'slug',
              'terms'    => 'cases',
          ]],
          'orderby' => 'date',
          'order'   => 'DESC',
      ]);
      if (empty($cases)) {
          // fallback - 取 3 个 ai-employee 类的文章作为案例展示
          $cases = get_posts([
              'post_type'      => 'post',
              'post_status'    => 'publish',
              'numberposts'    => 3,
              'tax_query'      => [[
                  'taxonomy' => 'category',
                  'field'    => 'slug',
                  'terms'    => 'ai-employee',
              ]],
              'exclude'        => $emp_post ? [$emp_post->ID] : [],
              'orderby' => 'date',
              'order'   => 'DESC',
          ]);
      }
      ?>
      <?php if (!empty($cases)): ?>
        <ul class="emp-prime__case-grid">
          <?php foreach ($cases as $ci => $case): ?>
            <li class="emp-prime__case" style="--emp-i:<?php echo (int)$ci; ?>">
              <?php if (has_post_thumbnail($case->ID)): ?>
                <a class="emp-prime__case-media" href="<?php echo esc_url(get_permalink($case->ID)); ?>" aria-label="<?php echo esc_attr(get_the_title($case->ID)); ?>">
                  <?php echo get_the_post_thumbnail($case->ID, 'medium', ['class' => 'emp-prime__case-image', 'loading' => 'lazy']); ?>
                </a>
              <?php endif; ?>
              <h3 class="emp-prime__case-title">
                <a href="<?php echo esc_url(get_permalink($case->ID)); ?>"><?php echo esc_html(get_the_title($case->ID)); ?></a>
              </h3>
              <p class="emp-prime__case-excerpt"><?php echo esc_html(wp_trim_words(strip_tags($case->post_excerpt ?: $case->post_content), 22, '…')); ?></p>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="emp-prime__cases-empty"><?php echo esc_html($is_en ? 'Cases will be showcased here once published.' : '案例发布后将展示于此。'); ?></p>
      <?php endif; ?>
    </div>
  </section>

  <!-- ============ 4. PRICING (定价) ============ -->
  <section class="emp-prime__price" aria-labelledby="emp-price-head">
    <div class="emp-prime__price-inner">
      <header class="emp-prime__section-head">
        <p class="emp-prime__kicker"><?php echo esc_html($is_en ? 'ENGAGEMENT' : '合作模式'); ?></p>
        <h2 id="emp-price-head" class="emp-prime__section-title"><?php echo esc_html($is_en ? 'Retainer & Terms' : '起步档与合作条款'); ?></h2>
      </header>
      <div class="emp-prime__price-card">
        <p class="emp-prime__price-retainer"><?php echo esc_html($emp['retainer']); ?></p>
        <p class="emp-prime__price-amount"><?php echo esc_html($emp['price']); ?></p>
        <ul class="emp-prime__price-list">
          <li><?php echo esc_html($is_en ? 'Dedicated onboarding concierge' : '专属上线陪跑'); ?></li>
          <li><?php echo esc_html($is_en ? 'Fine-tuning on proprietary corpus' : '专属语料微调'); ?></li>
          <li><?php echo esc_html($is_en ? 'Monthly iteration review' : '每月复盘迭代'); ?></li>
          <li><?php echo esc_html($is_en ? '24×7 priority support' : '24×7 优先级支持'); ?></li>
        </ul>
        <a class="emp-prime__btn emp-prime__btn--primary" href="<?php echo esc_url($emp['cta_url']); ?>"><?php echo esc_html($emp['cta_label']); ?></a>
      </div>
    </div>
  </section>

  <!-- ============ 5. CTA ============ -->
  <section class="emp-prime__cta" aria-labelledby="emp-cta-head">
    <div class="emp-prime__cta-inner">
      <h2 id="emp-cta-head" class="emp-prime__cta-title"><?php echo esc_html($site_kicker); ?></h2>
      <p class="emp-prime__cta-sub"><?php echo esc_html($site_sub); ?></p>
      <a class="emp-prime__btn emp-prime__btn--ghost" href="<?php echo esc_url($site_url); ?>"><?php echo esc_html($site_btn); ?> <?php echo hireai_svg('arrow-forward', 14); ?></a>
    </div>
  </section>

</article>

<style>
/* Aurelian Prime digital-employee detail (3.0.3) */
.emp-prime { color: var(--hai-text, #1a1410); font-family: var(--hai-font-body, 'Inter', sans-serif); }
.emp-prime__hero, .emp-prime__cap, .emp-prime__cases, .emp-prime__price, .emp-prime__cta { padding: 80px 24px; }
.emp-prime__hero-inner, .emp-prime__cap-inner, .emp-prime__cases-inner, .emp-prime__price-inner, .emp-prime__cta-inner { max-width: 1180px; margin: 0 auto; }
.emp-prime__kicker { font-family: var(--hai-font-display, 'Playfair Display', serif); font-style: italic; color: #775a19; letter-spacing: 0.12em; font-size: 13px; margin: 0 0 12px; text-transform: uppercase; }
.emp-prime__section-head { margin-bottom: 40px; }
.emp-prime__section-title { font-family: var(--hai-font-display, serif); font-size: clamp(28px, 4vw, 44px); font-weight: 600; margin: 0; line-height: 1.2; color: #1a1410; }

.emp-prime__hero { background: linear-gradient(135deg, #fbf7ef 0%, #f3ead9 100%); }
.emp-prime__hero-inner { display: grid; grid-template-columns: 1.05fr 1fr; gap: 60px; align-items: center; }
@media (max-width: 880px) { .emp-prime__hero-inner { grid-template-columns: 1fr; gap: 32px; } }
.emp-prime__name { font-family: var(--hai-font-display, serif); font-size: clamp(40px, 7vw, 84px); font-weight: 700; margin: 0 0 18px; line-height: 1.05; color: #1a1410; }
.emp-prime__soul { font-size: 18px; line-height: 1.65; color: rgba(26,20,16,0.82); max-width: 540px; margin: 0 0 32px; }
.emp-prime__hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.emp-prime__btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; font-size: 13px; letter-spacing: 0.1em; text-transform: uppercase; text-decoration: none; border-radius: 999px; font-family: inherit; font-weight: 500; transition: all .25s ease; border: 1px solid transparent; cursor: pointer; }
.emp-prime__btn--primary { background: #1a1410; color: #fff; border-color: #1a1410; }
.emp-prime__btn--primary:hover { background: #775a19; border-color: #775a19; }
.emp-prime__btn--ghost { background: transparent; color: #1a1410; border-color: rgba(26,20,16,0.25); }
.emp-prime__btn--ghost:hover { border-color: #775a19; color: #775a19; }
.emp-prime__hero-media { position: relative; }
.emp-prime__hero-image { width: 100%; height: auto; aspect-ratio: 4/5; object-fit: cover; border-radius: 8px; box-shadow: 0 30px 80px rgba(26,20,16,0.18); }
.emp-prime__hero-placeholder { aspect-ratio: 4/5; background: rgba(26,20,16,0.08); border: 1px dashed rgba(26,20,16,0.18); border-radius: 8px; }
.emp-prime__seal { position: absolute; right: 18px; bottom: 18px; background: rgba(255,255,255,0.95); padding: 6px 12px; font-size: 11px; letter-spacing: 0.2em; color: #775a19; border: 1px solid rgba(119,90,25,0.35); border-radius: 999px; }

.emp-prime__cap { background: #fff; }
.emp-prime__cap-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 40px; }
@media (max-width: 720px) { .emp-prime__cap-grid { grid-template-columns: 1fr; } }
.emp-prime__cap-card { background: #fbf7ef; padding: 28px; border-radius: 12px; border: 1px solid rgba(119,90,25,0.16); }
.emp-prime__cap-title { font-family: var(--hai-font-display, serif); font-size: 24px; margin: 0 0 12px; color: #775a19; letter-spacing: 0.1em; }
.emp-prime__cap-card p { margin: 0; line-height: 1.7; color: rgba(26,20,16,0.8); }
.emp-prime__cap-list { list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
@media (max-width: 720px) { .emp-prime__cap-list { grid-template-columns: 1fr; } }
.emp-prime__cap-item { display: flex; align-items: baseline; gap: 14px; padding: 16px 20px; background: rgba(119,90,25,0.05); border-left: 2px solid #775a19; border-radius: 4px; transition: transform .3s, background .3s; }
.emp-prime__cap-item:hover { background: rgba(119,90,25,0.10); transform: translateX(4px); }
.emp-prime__cap-num { font-family: var(--hai-font-display, serif); font-style: italic; color: #775a19; font-size: 16px; min-width: 32px; }
.emp-prime__cap-line { font-size: 15px; color: #1a1410; }

.emp-prime__cases { background: #fbf7ef; }
.emp-prime__case-grid { list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
@media (max-width: 880px) { .emp-prime__case-grid { grid-template-columns: 1fr; } }
.emp-prime__case { background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 8px 32px rgba(26,20,16,0.06); transition: transform .35s, box-shadow .35s; }
.emp-prime__case:hover { transform: translateY(-4px); box-shadow: 0 16px 48px rgba(26,20,16,0.12); }
.emp-prime__case-media { display: block; aspect-ratio: 16/10; overflow: hidden; }
.emp-prime__case-image { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
.emp-prime__case:hover .emp-prime__case-image { transform: scale(1.05); }
.emp-prime__case-title { font-family: var(--hai-font-display, serif); font-size: 22px; margin: 18px 22px 8px; line-height: 1.3; }
.emp-prime__case-title a { color: #1a1410; text-decoration: none; }
.emp-prime__case-title a:hover { color: #775a19; }
.emp-prime__case-excerpt { margin: 0 22px 22px; font-size: 14px; line-height: 1.6; color: rgba(26,20,16,0.7); }
.emp-prime__cases-empty { text-align: center; color: rgba(26,20,16,0.6); font-style: italic; padding: 40px 0; }

.emp-prime__price { background: #1a1410; color: #fbf7ef; }
.emp-prime__price .emp-prime__section-title { color: #fbf7ef; }
.emp-prime__price .emp-prime__kicker { color: #e9c176; }
.emp-prime__price-card { max-width: 560px; margin: 0 auto; padding: 48px 40px; background: rgba(255,255,255,0.04); border: 1px solid rgba(233,193,118,0.25); border-radius: 12px; text-align: center; }
.emp-prime__price-retainer { font-family: var(--hai-font-display, serif); font-style: italic; color: #e9c176; letter-spacing: 0.18em; font-size: 12px; text-transform: uppercase; margin: 0 0 12px; }
.emp-prime__price-amount { font-family: var(--hai-font-display, serif); font-size: 48px; font-weight: 600; margin: 0 0 32px; color: #fbf7ef; }
.emp-prime__price-list { list-style: none; padding: 0; margin: 0 0 32px; text-align: left; }
.emp-prime__price-list li { padding: 12px 0; border-top: 1px solid rgba(233,193,118,0.18); font-size: 15px; color: rgba(251,247,239,0.85); }
.emp-prime__price-list li:first-child { border-top: 0; }

.emp-prime__cta { background: linear-gradient(135deg, #775a19 0%, #1a1410 100%); color: #fff; text-align: center; }
.emp-prime__cta-title { font-family: var(--hai-font-display, serif); font-size: clamp(32px, 5vw, 56px); margin: 0 0 18px; line-height: 1.15; }
.emp-prime__cta-sub { margin: 0 0 36px; max-width: 600px; margin-left: auto; margin-right: auto; opacity: 0.85; font-size: 17px; }
.emp-prime__cta .emp-prime__btn--ghost { border-color: rgba(255,255,255,0.4); color: #fff; }
.emp-prime__cta .emp-prime__btn--ghost:hover { background: rgba(255,255,255,0.1); border-color: #fff; color: #fff; }
</style>

<?php
get_footer();
