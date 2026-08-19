<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - AI Solutions Collection
 *
 * Aurelian luxury system: gold #775a19 / #e9c176, Playfair Display + Inter.
 * Dior / Bvlgari审美 — 极简留白、大衬线标题、金色点缀、无红色。
 * WooCommerce 产品自动拉取 + 分类筛选 + 分页。
 * 无商品时展示双语 fallback 方案。
 */
get_header();

$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';
$paged  = max(1, get_query_var('paged'));
$cta_text = hireai_field('card_cta_text', $is_en ? 'Discover' : '探索');

/* ── 筛选器 ── */
$filters = [];
if (function_exists('get_field')) {
    $filters_raw = get_field('solutions_filters');
    if (is_array($filters_raw)) {
        foreach ($filters_raw as $row) {
            if (!is_array($row)) continue;
            $label = $is_en ? (isset($row['filter_label_en']) ? $row['filter_label_en'] : '') : (isset($row['filter_label_zh']) ? $row['filter_label_zh'] : '');
            $slug  = isset($row['filter_slug']) ? $row['filter_slug'] : '';
            if ($slug !== '' && $label !== '') $filters[] = ['label' => $label, 'slug' => $slug];
        }
    }
}
if (empty($filters)) {
    $filters = $is_en
        ? [['label'=>'Marketing','slug'=>'marketing'],['label'=>'E-commerce','slug'=>'ecommerce'],['label'=>'Design','slug'=>'design'],['label'=>'Public Relations','slug'=>'pr']]
        : [['label'=>'营销','slug'=>'marketing'],['label'=>'电商','slug'=>'ecommerce'],['label'=>'设计','slug'=>'design'],['label'=>'公关','slug'=>'pr']];
}

/* ── Fallback 方案数据 ── */
$fallback = [
    ['title'=>['zh'=>'全域营销智囊','en'=>'Omnichannel Marketing Intelligence'],'tag'=>['zh'=>'营销','en'=>'MARKETING'],'operative'=>['zh'=>'执行智能体：ARIA-01','en'=>'OPERATIVE: ARIA-01'],'excerpt'=>['zh'=>'覆盖内容、投放与数据复盘的全链路营销智能体。','en'=>'A full-funnel marketing agent for content, media, and performance review.'],'price'=>['zh'=>'¥4,800 / 月起','en'=>'From ¥4,800/mo'],'retainer'=>['zh'=>'起步档','en'=>'Starting'],'cats'=>'marketing','image'=>'solution-1.jpg'],
    ['title'=>['zh'=>'电商转化引擎','en'=>'Commerce Conversion Engine'],'tag'=>['zh'=>'电商','en'=>'E-COMMERCE'],'operative'=>['zh'=>'执行智能体：QUANTUM-C','en'=>'OPERATIVE: QUANTUM-C'],'excerpt'=>['zh'=>'从选品、定价到客服，让增长从洞察到成交顺畅闭环。','en'=>'Connects selection, pricing, and service into a seamless growth loop.'],'price'=>['zh'=>'¥6,800 / 月起','en'=>'From ¥6,800/mo'],'retainer'=>['zh'=>'项目基准','en'=>'Base'],'cats'=>'ecommerce','image'=>'solution-2.jpg'],
    ['title'=>['zh'=>'奢品内容工坊','en'=>'Luxury Content Atelier'],'tag'=>['zh'=>'设计','en'=>'DESIGN'],'operative'=>['zh'=>'执行智能体：AURA-7','en'=>'OPERATIVE: AURA-7'],'excerpt'=>['zh'=>'为高净值品牌打造有艺术质感、有销售力的内容体系。','en'=>'Crafts artful, conversion-ready content systems for high-net-worth brands.'],'price'=>['zh'=>'¥8,800 / 月起','en'=>'From ¥8,800/mo'],'retainer'=>['zh'=>'按概念','en'=>'Per Concept'],'cats'=>'design','image'=>'solution-3.jpg'],
    ['title'=>['zh'=>'危机公关文案','en'=>'Crisis PR Copywriting'],'tag'=>['zh'=>'公关','en'=>'PR'],'operative'=>['zh'=>'执行智能体：ELARA-9','en'=>'OPERATIVE: ELARA-9'],'excerpt'=>['zh'=>'以毫秒级校准话术处理突发舆情，保护品牌叙事与市场信任。','en'=>'Calibrated messaging protocols to mitigate brand exposure and steer public narrative.'],'price'=>['zh'=>'¥15,000 / 月起','en'=>'From ¥15,000/mo'],'retainer'=>['zh'=>'年度授权','en'=>'Annual'],'cats'=>'pr','image'=>'solution-4.jpg'],
];

$localize = function ($item, $key) use ($is_en) {
    $v = isset($item[$key]) ? $item[$key] : '';
    return is_array($v) ? ($v[$is_en ? 'en' : 'zh'] ?? '') : $v;
};

/* ── WooCommerce 查询 ── */
$has_woo  = class_exists('WooCommerce');
$per_page = defined('HIREAI_SOLUTIONS_PER_PAGE') ? HIREAI_SOLUTIONS_PER_PAGE : 12;
$query    = $has_woo ? new WP_Query([
    'post_type'      => 'product',
    'posts_per_page' => $per_page,
    'paged'          => $paged,
]) : false;

$total_products = $has_woo ? wp_count_posts('product')->publish : 0;
?>

<!-- ════════════════════════════════════════════════════════
     LUXURY AI SOLUTIONS COLLECTION — Aurelian Gold System
     ════════════════════════════════════════════════════════ -->
<style>
/* ── Aurelian Palette (matches front-page.php) ── */
:root {
    --sol-black:     #1a1a1a;
    --sol-dark:      #2c2c2c;
    --sol-mid:       #6b6b6b;
    --sol-mid-soft:  #9a9a9a;
    --sol-light:     #f5f3ef;
    --sol-cream:     #faf9f7;
    --sol-white:     #ffffff;
    --sol-gold:      #775a19;
    --sol-gold-l:    #e9c176;
    --sol-gold-d:    #5a4310;
    --sol-border:    #e8e5df;
    --sol-border-l:  #f0ede8;
    --sol-shadow:    rgba(119,90,25,0.08);
    --sol-shadow-h:  rgba(119,90,25,0.18);
    --sol-font-display: 'Playfair Display', 'Georgia', serif;
    --sol-font-body:    'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* ── Reset scoped ── */
.sol-solutions *, .sol-solutions *::before, .sol-solutions *::after { box-sizing: border-box; margin: 0; padding: 0; }
.sol-solutions {
    font-family: var(--sol-font-body);
    color: var(--sol-black);
    background: var(--sol-cream);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ── Hero ── */
.sol-hero {
    position: relative;
    text-align: center;
    padding: 112px 24px 72px;
    background: var(--sol-white);
    border-bottom: 1px solid var(--sol-border);
    overflow: hidden;
}
/* Subtle radial texture for futuristic depth */
.sol-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 60% 50% at 50% 0%, rgba(119,90,25,0.04) 0%, transparent 70%),
        radial-gradient(circle at 80% 80%, rgba(233,193,118,0.03) 0%, transparent 40%);
    pointer-events: none;
}
.sol-hero__divider {
    width: 56px;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--sol-gold), transparent);
    margin: 0 auto 28px;
}
.sol-hero__kicker {
    display: inline-block;
    font-family: var(--sol-font-body);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.4em;
    text-transform: uppercase;
    color: var(--sol-gold);
    margin-bottom: 20px;
    position: relative;
}
.sol-hero__title {
    font-family: var(--sol-font-display);
    font-size: clamp(34px, 5vw, 60px);
    font-weight: 400;
    line-height: 1.1;
    color: var(--sol-black);
    margin: 0 0 18px;
    letter-spacing: -0.01em;
    position: relative;
}
.sol-hero__sub {
    font-size: 16px;
    line-height: 1.75;
    color: var(--sol-mid);
    max-width: 520px;
    margin: 0 auto;
    position: relative;
}
.sol-hero__count {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 28px;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--sol-mid-soft);
    position: relative;
}
.sol-hero__count strong {
    font-family: var(--sol-font-display);
    font-size: 18px;
    color: var(--sol-gold);
    font-weight: 500;
}

/* ── Filter Bar ── */
.sol-filters {
    display: flex;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    padding: 40px 24px 0;
    max-width: 900px;
    margin: 0 auto;
}
.sol-filter-btn {
    font-family: var(--sol-font-body);
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    padding: 10px 28px;
    border: 1px solid var(--sol-border);
    border-radius: 0;
    background: transparent;
    color: var(--sol-mid);
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.23,1,0.32,1);
    position: relative;
    overflow: hidden;
}
.sol-filter-btn::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 1px;
    background: var(--sol-gold);
    transition: all 0.35s cubic-bezier(0.23,1,0.32,1);
    transform: translateX(-50%);
}
.sol-filter-btn:hover {
    border-color: var(--sol-gold-l);
    color: var(--sol-gold);
}
.sol-filter-btn:hover::after {
    width: 60%;
}
.sol-filter-btn.is-active {
    background: var(--sol-black);
    border-color: var(--sol-black);
    color: var(--sol-white);
}
.sol-filter-btn.is-active::after {
    display: none;
}

/* ── Product Grid ── */
.sol-grid-wrap {
    max-width: 1280px;
    margin: 0 auto;
    padding: 48px 32px 96px;
}
.sol-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 28px;
}

/* ── Product Card ── */
.sol-card {
    background: var(--sol-white);
    border: 1px solid var(--sol-border-l);
    overflow: hidden;
    transition:
        transform 0.5s cubic-bezier(0.23,1,0.32,1),
        box-shadow 0.5s cubic-bezier(0.23,1,0.32,1),
        border-color 0.4s ease;
    position: relative;
    display: flex;
    flex-direction: column;
}
.sol-card:hover {
    transform: translateY(-8px);
    box-shadow:
        0 24px 64px -12px rgba(0,0,0,0.06),
        0 0 0 1px var(--sol-gold-l);
    border-color: var(--sol-gold-l);
}

.sol-card__media {
    position: relative;
    display: block;
    aspect-ratio: 4/5;
    overflow: hidden;
    background: var(--sol-light);
}
.sol-card__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s cubic-bezier(0.23,1,0.32,1);
}
.sol-card:hover .sol-card__media img {
    transform: scale(1.06);
}
/* Subtle gradient overlay on image */
.sol-card__media::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 40%;
    background: linear-gradient(to top, rgba(0,0,0,0.08), transparent);
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.4s;
}
.sol-card:hover .sol-card__media::after {
    opacity: 1;
}

.sol-card__badge {
    position: absolute;
    top: 16px;
    left: 16px;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    padding: 6px 16px;
    background: var(--sol-white);
    color: var(--sol-black);
    z-index: 1;
    transition: all 0.3s ease;
}
.sol-card:hover .sol-card__badge {
    background: var(--sol-gold);
    color: var(--sol-white);
}
.sol-card__badge--sale {
    background: var(--sol-gold);
    color: var(--sol-white);
}

.sol-card__body {
    padding: 24px 24px 28px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.sol-card__operative {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: var(--sol-gold);
    margin-bottom: 12px;
}
.sol-card__title {
    font-family: var(--sol-font-display);
    font-size: 21px;
    font-weight: 400;
    line-height: 1.3;
    margin: 0 0 10px;
}
.sol-card__title a {
    color: var(--sol-black);
    text-decoration: none;
    transition: color 0.3s ease;
}
.sol-card__title a:hover {
    color: var(--sol-gold);
}
.sol-card__excerpt {
    font-size: 13.5px;
    line-height: 1.7;
    color: var(--sol-mid);
    margin: 0 0 20px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.sol-card__footer {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding-top: 18px;
    border-top: 1px solid var(--sol-border-l);
}
.sol-card__retainer-label {
    font-size: 10px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--sol-mid-soft);
    display: block;
    margin-bottom: 4px;
}
.sol-card__price {
    font-family: var(--sol-font-display);
    font-size: 18px;
    color: var(--sol-black);
    font-weight: 500;
}

.sol-card__arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border: 1px solid var(--sol-border);
    color: var(--sol-black);
    text-decoration: none;
    transition: all 0.35s cubic-bezier(0.23,1,0.32,1);
    flex-shrink: 0;
}
.sol-card__arrow:hover {
    background: var(--sol-gold);
    border-color: var(--sol-gold);
    color: var(--sol-white);
}
.sol-card__arrow svg {
    transition: transform 0.3s ease;
}
.sol-card__arrow:hover svg {
    transform: translateX(3px);
}

/* ── Empty State ── */
.sol-empty {
    text-align: center;
    padding: 80px 24px;
    color: var(--sol-mid);
    font-size: 15px;
    display: none;
    grid-column: 1 / -1;
}
.sol-empty.is-visible { display: block; }

/* ── Pagination ── */
.sol-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin-top: 56px;
}
.sol-pagination a,
.sol-pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    height: 44px;
    padding: 0 16px;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.04em;
    text-decoration: none;
    border: 1px solid var(--sol-border);
    color: var(--sol-mid);
    background: var(--sol-white);
    transition: all 0.3s ease;
}
.sol-pagination a:hover {
    border-color: var(--sol-gold);
    color: var(--sol-gold);
    box-shadow: 0 2px 12px var(--sol-shadow);
}
.sol-pagination .current {
    background: var(--sol-gold);
    border-color: var(--sol-gold);
    color: var(--sol-white);
    box-shadow: 0 4px 16px rgba(119,90,25,0.2);
}
.sol-pagination .dots {
    border: none;
    background: transparent;
    color: var(--sol-mid-soft);
    min-width: auto;
    padding: 0 6px;
}

/* ── Fallback CTA Link ── */
.sol-cta-wrap {
    text-align: center;
    margin-top: 56px;
}
.sol-cta {
    display: inline-block;
    font-family: var(--sol-font-body);
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 16px 48px;
    border: 1px solid var(--sol-gold);
    color: var(--sol-gold);
    text-decoration: none;
    background: transparent;
    transition: all 0.35s cubic-bezier(0.23,1,0.32,1);
}
.sol-cta:hover {
    background: var(--sol-gold);
    color: var(--sol-white);
    box-shadow: 0 8px 32px rgba(119,90,25,0.2);
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .sol-hero { padding: 72px 20px 48px; }
    .sol-hero__title { font-size: 28px; }
    .sol-hero__sub { font-size: 15px; }
    .sol-grid { grid-template-columns: 1fr; gap: 20px; }
    .sol-grid-wrap { padding: 32px 16px 64px; }
    .sol-filters { padding: 28px 16px 0; gap: 4px; }
    .sol-filter-btn { padding: 8px 18px; font-size: 10px; }
    .sol-pagination { gap: 4px; }
    .sol-pagination a,
    .sol-pagination span { min-width: 38px; height: 38px; padding: 0 12px; font-size: 12px; }
}
@media (min-width: 769px) and (max-width: 1024px) {
    .sol-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<!-- ════════════════════════════════════════════════════════
     HTML
     ════════════════════════════════════════════════════════ -->
<div class="sol-solutions">

    <!-- Hero -->
    <section class="sol-hero">
        <div class="sol-hero__divider"></div>
        <span class="sol-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', $is_en ? 'AI SOLUTIONS' : 'AI 解决方案')); ?></span>
        <h1 class="sol-hero__title"><?php echo esc_html(hireai_field('header_title', $is_en ? 'Curated AI Solutions.' : '臻选智能方案。')); ?></h1>
        <p class="sol-hero__sub"><?php echo esc_html(hireai_field('header_subtitle', $is_en ? 'Each solution is a specialized AI operative — engineered for precision, designed for elegance.' : '每一个方案，都是一位专精的 AI 智能体——为精准而生，以优雅呈现。')); ?></p>
        <?php if ($total_products > 0) : ?>
        <div class="sol-hero__count">
            <span><?php echo esc_html($is_en ? 'Collection' : '共计'); ?></span>
            <strong><?php echo esc_html($total_products); ?></strong>
            <span><?php echo esc_html($is_en ? 'solutions' : '个方案'); ?></span>
        </div>
        <?php endif; ?>
    </section>

    <!-- Filters -->
    <nav class="sol-filters" role="group" aria-label="<?php echo esc_attr($is_en ? 'Filter solutions' : '筛选解决方案'); ?>">
        <button class="sol-filter-btn is-active" type="button" data-filter=""><?php echo esc_html($is_en ? 'All' : '全部'); ?></button>
        <?php foreach ($filters as $f) : ?>
            <button class="sol-filter-btn" type="button" data-filter="<?php echo esc_attr($f['slug']); ?>"><?php echo esc_html($f['label']); ?></button>
        <?php endforeach; ?>
    </nav>

    <!-- Grid -->
    <div class="sol-grid-wrap">
        <?php if ($query && $query->have_posts()) : ?>
            <div class="sol-grid" id="sol-solution-grid">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php
                    global $product;
                    if (!$product || !$product->is_visible()) continue;
                    $pid       = $product->get_id();
                    $cat_slugs = wp_get_post_terms($pid, 'product_cat', ['fields' => 'slugs']);
                    $cat_names = wp_get_post_terms($pid, 'product_cat', ['fields' => 'names']);
                    $cat_label = (!is_wp_error($cat_names) && !empty($cat_names)) ? $cat_names[0] : '';
                    $cats_attr = (!is_wp_error($cat_slugs)) ? implode(' ', $cat_slugs) : '';
                    $operative = hireai_field('product_operative', $is_en ? 'OPERATIVE: HIREAI' : '执行智能体：聘AI', $pid);
                    $retainer  = hireai_field('product_retainer_label', $is_en ? 'Starting' : '起步档', $pid);
                    $img_url   = has_post_thumbnail() ? get_the_post_thumbnail_url($pid, 'large') : '';
                    ?>
                    <article class="sol-card" data-cats="<?php echo esc_attr($cats_attr); ?>">
                        <a class="sol-card__media" href="<?php echo esc_url(get_permalink()); ?>" tabindex="-1" aria-hidden="true">
                            <?php if ($img_url) : ?>
                                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                            <?php endif; ?>
                            <?php if ($product->is_on_sale()) : ?>
                                <span class="sol-card__badge sol-card__badge--sale"><?php echo esc_html($is_en ? 'Offer' : '特惠'); ?></span>
                            <?php elseif ($cat_label !== '') : ?>
                                <span class="sol-card__badge"><?php echo esc_html($cat_label); ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="sol-card__body">
                            <div class="sol-card__operative"><?php echo esc_html($operative); ?></div>
                            <h3 class="sol-card__title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h3>
                            <?php
                            $short_desc = $product->get_short_description();
                            if ($short_desc !== '') : ?>
                                <p class="sol-card__excerpt"><?php echo esc_html(wp_strip_all_tags($short_desc)); ?></p>
                            <?php endif; ?>
                            <div class="sol-card__footer">
                                <div>
                                    <span class="sol-card__retainer-label"><?php echo esc_html($retainer); ?></span>
                                    <strong class="sol-card__price"><?php echo wp_kses_post($product->get_price_html()); ?></strong>
                                </div>
                                <a class="sol-card__arrow" href="<?php echo esc_url(get_permalink()); ?>" aria-label="<?php echo esc_attr($cta_text); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <p class="sol-empty" data-sol-empty><?php echo esc_html($is_en ? 'No solutions match this filter.' : '该分类下暂无方案。'); ?></p>

            <?php if ($query->max_num_pages > 1) : ?>
                <nav class="sol-pagination" aria-label="<?php echo esc_attr($is_en ? 'Solutions pagination' : '方案分页'); ?>">
                    <?php
                    echo paginate_links([
                        'total'     => $query->max_num_pages,
                        'current'   => $paged,
                        'prev_text' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>',
                        'next_text' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>',
                        'type'      => 'list',
                        'before_page_number' => '<span>',
                        'after_page_number'  => '</span>',
                    ]);
                    ?>
                </nav>
            <?php endif; ?>

        <?php else : ?>
            <!-- Fallback: no WooCommerce or no products -->
            <div class="sol-grid" id="sol-solution-grid">
                <?php foreach ($fallback as $item) : ?>
                    <article class="sol-card" data-cats="<?php echo esc_attr($localize($item, 'cats')); ?>">
                        <a class="sol-card__media" href="<?php echo esc_url(home_url('/ai-solutions/')); ?>" tabindex="-1" aria-hidden="true">
                            <?php
                            $img = hireai_default_image($localize($item, 'image'));
                            if ($img) : ?>
                                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($localize($item, 'title')); ?>" loading="lazy">
                            <?php endif; ?>
                            <span class="sol-card__badge"><?php echo esc_html($localize($item, 'tag')); ?></span>
                        </a>
                        <div class="sol-card__body">
                            <div class="sol-card__operative"><?php echo esc_html($localize($item, 'operative')); ?></div>
                            <h3 class="sol-card__title"><a href="<?php echo esc_url(home_url('/ai-solutions/')); ?>"><?php echo esc_html($localize($item, 'title')); ?></a></h3>
                            <p class="sol-card__excerpt"><?php echo esc_html($localize($item, 'excerpt')); ?></p>
                            <div class="sol-card__footer">
                                <div>
                                    <span class="sol-card__retainer-label"><?php echo esc_html($localize($item, 'retainer')); ?></span>
                                    <strong class="sol-card__price"><?php echo esc_html($localize($item, 'price')); ?></strong>
                                </div>
                                <a class="sol-card__arrow" href="<?php echo esc_url(home_url('/ai-solutions/')); ?>" aria-label="<?php echo esc_attr($cta_text); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div><!-- .sol-solutions -->

<!-- ════════════════════════════════════════════════════════
     JS: Filter + Empty State
     ════════════════════════════════════════════════════════ -->
<script>
(function(){
    var btns  = document.querySelectorAll('.sol-filter-btn');
    var cards = document.querySelectorAll('#sol-solution-grid .sol-card');
    var empty = document.querySelector('[data-sol-empty]');

    function filterCards(slug) {
        var visible = 0;
        cards.forEach(function(c) {
            var match = slug === '' || (c.getAttribute('data-cats') || '').indexOf(slug) !== -1;
            c.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        if (empty) empty.classList.toggle('is-visible', visible === 0);
    }

    btns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            btns.forEach(function(b){ b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            filterCards(btn.getAttribute('data-filter'));
        });
    });
})();
</script>

<?php get_footer(); ?>
