<?php
/**
 * Template Name: 聘AI - AI 方案商城（Marketplace v3）
 * 说明：方案商城模板。Hero + 全局商品标签筛选 + 每页 9 张方案卡片 + 顶部/底部分页。
 *       页面文案来自 group_page_ai_solutions，标签配置来自“站点设置 → 解决方案标签”。
 * 版本：3.11.0
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$suffix = function_exists( 'hireai_lang_suffix' ) ? hireai_lang_suffix() : '';
$is_en  = ( '_en' === $suffix );
$page_id = get_the_ID();

/* ====== 文案（页面 ACF 可编辑，缺省 = 设计稿文案） ====== */
$hero_kicker       = hireai_field( 'header_kicker', $is_en ? 'BESPOKE SOLUTIONS' : 'BESPOKE SOLUTIONS', $page_id );
$hero_title        = hireai_field( 'header_title', $is_en ? 'AI Solutions Marketplace' : 'AI方案商城', $page_id );
$hero_subtitle     = hireai_field( 'header_subtitle', $is_en ? 'Hire elite digital minds to empower your business.' : '雇佣顶尖数字智脑，赋能企业未来。', $page_id );
$hero_cta_primary  = hireai_field( 'hero_cta_primary_text', $is_en ? 'Custom Plan' : '定制方案', $page_id );
$hero_cta_primary_link  = hireai_field( 'hero_cta_primary_link', $is_en ? '/contact/' : '/contact/', $page_id );
$hero_cta_secondary    = hireai_field( 'hero_cta_secondary_text', $is_en ? 'View Cases' : '查看案例', $page_id );
$hero_cta_secondary_link = hireai_field( 'hero_cta_secondary_link', $is_en ? '/category/cases/' : '/category/cases/', $page_id );

$card_cta_text     = hireai_field( 'card_cta_text', $is_en ? 'Explore More' : '探索更多', $page_id );
$empty_text        = hireai_field( 'empty_text', $is_en ? 'No solutions in this category yet.' : '该分类下暂无解决方案', $page_id );

/* ====== 收尾 CTA ====== */
$final_kicker         = hireai_field( 'final_cta_kicker', $is_en ? 'NEXT STEP' : 'NEXT STEP', $page_id );
$final_title          = hireai_field( 'final_cta_title', $is_en ? 'Ready to Elevate Your Brand?' : '准备好为您的品牌升级了吗？', $page_id );
$final_subtitle       = hireai_field( 'final_cta_subtitle', $is_en ? 'Tell us your ambitions and we will design a bespoke AI plan around them.' : '告诉我们您的雄心，我们将围绕它设计一套专属 AI 解决方案。', $page_id );
$final_cta_primary    = hireai_field( 'final_cta_primary_text', $is_en ? 'Start the Conversation' : '开启对话', $page_id );
$final_cta_secondary  = hireai_field( 'final_cta_secondary_text', $is_en ? 'Browse Cases' : '浏览案例', $page_id );

$filter_state = function_exists('hireai_get_solution_filter_state')
    ? hireai_get_solution_filter_state($page_id)
    : ['filters' => [], 'missing_tags' => []];
$filters      = $filter_state['filters'];
$filter_slugs = array_column($filters, 'slug');
$requested_tag = get_query_var('solution_tag', '');
if ($requested_tag === '' && isset($_GET['solution_tag']) && is_string($_GET['solution_tag'])) {
    $requested_tag = wp_unslash($_GET['solution_tag']);
}
$active_tag   = sanitize_key(is_string($requested_tag) ? $requested_tag : '');
$requested_page = get_query_var('solution_page', 1);
if (($requested_page === 1 || $requested_page === '') && isset($_GET['solution_page']) && is_string($_GET['solution_page'])) {
    $requested_page = wp_unslash($_GET['solution_page']);
}
$current_page = max(1, (int) (is_scalar($requested_page) ? $requested_page : 1));
if ($active_tag === '' || !in_array($active_tag, $filter_slugs, true)) {
    $active_tag = '';
}

$solutions_result = function_exists('hireai_get_solution_products_result')
    ? hireai_get_solution_products_result([
        'paged' => $current_page,
        'per_page' => 9,
        'tag_slug' => $active_tag,
    ])
    : ['items' => [], 'max_num_pages' => 1];
$cards           = $solutions_result['items'];
$total_pages     = max(1, (int) ($solutions_result['max_num_pages'] ?? 1));
$pagination_links = $total_pages > 1 && function_exists('paginate_links')
    ? paginate_links([
        'base' => add_query_arg('solution_page', '%#%'),
        'format' => '',
        'current' => max(1, (int) ($solutions_result['paged'] ?? $current_page)),
        'total' => $total_pages,
        'prev_text' => '‹',
        'next_text' => '›',
    ])
    : '';
?>
<style>
/* ============== 页面专有样式（仅本模板生效） ============== */
.sols-page-hero {
    padding-top: clamp(120px, 14vh, 180px);
    padding-bottom: clamp(40px, 6vw, 80px);
    text-align: center;
    position: relative;
}
.sols-page-hero::after {
    content: '';
    display: block;
    width: 1px;
    height: 56px;
    margin: 32px auto 0;
    background: linear-gradient(to bottom, transparent, var(--gold-leaf, #d4af37), transparent);
}
.sols-page-hero__kicker {
    display: inline-block;
    margin-bottom: 18px;
    color: var(--gold-leaf, #d4af37);
    font-family: var(--font-label, 'Inter', sans-serif);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}
/* v3.5.7: 移除 italic（font-weight:700 + 非斜体 + 香槟金渐变）— Sasha brief 2026-09-01 */
.sols-page-hero__title {
    margin: 0 auto 24px;
    max-width: 920px;
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-weight: 700;
    font-size: clamp(40px, 6vw, 72px);
    line-height: 1.1;
    letter-spacing: -0.01em;
    background: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    font-style: normal;
}
.sols-page-hero__subtitle {
    max-width: 720px;
    margin: 0 auto;
    /* v3.0.8 (Bug C): 移除 italic + 用 on-surface 颜色 + 16px（DESIGN.md body-md） */
    font-family: var(--font-body-en, 'Inter'), sans-serif;
    font-size: 16px;
    font-style: normal !important;
    color: var(--on-surface, #1a1c1c);
    line-height: 1.6;
}
.sols-page-hero__actions {
    margin-top: 40px;
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    justify-content: center;
}

/* 卡片网格 */
.sols-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 28px;
    max-width: 1440px;
    margin: 0 auto;
    padding: 0 20px clamp(48px, 6vw, 80px);
}
@media (min-width: 720px) {
    .sols-grid { grid-template-columns: repeat(3, 1fr); }
}
/* v3.0.6: border-radius 4px (hireaipeople.txt 卡片圆角 0或4px) — was 12px */
.sols-card {
    background: rgba(249, 248, 243, .7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(119, 90, 25, .2);
    border-radius: 4px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 40px -10px rgba(119, 90, 25, .04);
    transition: all .4s ease;
}
.sols-card:hover {
    border-color: rgba(119, 90, 25, .5);
    box-shadow: 0 18px 50px -10px rgba(119, 90, 25, .12);
    transform: translateY(-2px);
}
.sols-card__media {
    position: relative;
    aspect-ratio: 16/9;
    overflow: hidden;
    background: var(--surface-container, #eeeeee);
    display: block;
}
.sols-card__media img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .8s ease;
    display: block;
}
.sols-card:hover .sols-card__media img {
    transform: scale(1.06);
}
.sols-card__media::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.5), transparent);
    pointer-events: none;
}
.sols-card__body {
    padding: 28px;
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing); font uses Montserrat */
.sols-card__kicker {
    color: var(--gold-leaf, #775a19);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 10px;
    font-family: var(--font-label, 'Montserrat', 'Inter', sans-serif), sans-serif;
}
.sols-card__title {
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-size: clamp(22px, 2vw, 28px);
    font-weight: 500;
    margin: 0 0 14px;
    color: var(--primary, #1a1c1c);
}
.sols-card__desc {
    color: var(--on-surface-variant, #444748);
    line-height: 1.6;
    font-size: 15px;
    flex: 1 1 auto;
    margin: 0 0 24px;
}
.sols-card__foot {
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px;
    padding-top: 18px;
    border-top: 1px solid rgba(196, 199, 199, .4);
}
.sols-card__price {
    color: var(--gold-leaf, #b58a2b);
    font-weight: 600;
    font-size: 14px;
    letter-spacing: .08em;
}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing); font uses Montserrat */
.sols-card__cta {
    display: inline-flex; align-items: center; gap: 8px;
    background: transparent;
    border: none;
    padding: 0;
    color: var(--primary, #1a1c1c);
    font-family: var(--font-label, 'Montserrat', 'Inter', sans-serif), sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    cursor: pointer;
    transition: gap .3s ease, color .3s ease;
    text-decoration: none;
}
.sols-card:hover .sols-card__cta {
    color: var(--gold-leaf, #775a19);
    gap: 12px;
}
.sols-card__cta svg { width: 14px; height: 14px; }
.sols-card--cta-highlight {
    border-color: var(--gold-leaf, #775a19);
    box-shadow: 0 10px 40px -10px rgba(119, 90, 25, .18);
}

/* 分页 */
.sols-pagination {
    display: flex; align-items: center; justify-content: center;
    gap: 10px;
    padding: 16px 20px 40px;
}
.sols-pagination .page-numbers {
    width: 40px; height: 40px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid var(--outline-variant, #e2e2e2);
    border-radius: 50%;
    color: var(--on-surface-variant, #444748);
    background: transparent;
    cursor: pointer;
    transition: all .3s ease;
    font-family: var(--font-label, 'Inter', sans-serif);
    font-size: 13px;
    text-decoration: none;
}
.sols-pagination .page-numbers:hover,
.sols-pagination .page-numbers.current {
    border-color: var(--gold-leaf, #775a19);
    color: var(--gold-leaf, #775a19);
    background: rgba(119, 90, 25, .05);
}
.sols-pagination .page-numbers.dots {
    border: none;
    background: transparent;
    width: auto;
    color: var(--on-surface-variant, #444748);
    padding: 0 6px;
}

/* ============== 大 CTA 收尾 ============== */
.sols-cta {
    padding: clamp(80px, 10vw, 140px) 20px;
    text-align: center;
    background: var(--surface, #faf9f9);
    border-top: 1px solid rgba(196, 199, 199, .3);
}
.sols-cta__inner {
    max-width: 720px;
    margin: 0 auto;
}
.sols-cta__kicker {
    display: inline-block;
    margin-bottom: 14px;
    color: var(--gold-leaf, #775a19);
    font-family: var(--font-label, 'Inter', sans-serif);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
}
.sols-cta__title {
    margin: 0 0 18px;
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 600;
    background: linear-gradient(to right, #775a19, #e9c176, #775a19);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
}
.sols-cta__sub {
    margin: 0 0 32px;
    color: var(--on-surface-variant, #444748);
    font-size: clamp(15px, 1.5vw, 17px);
    line-height: 1.7;
}
.sols-cta__actions {
    display: flex; flex-wrap: wrap; gap: 16px; justify-content: center;
}
</style>

<main class="sols-main">

    <!-- ============== Hero ============== -->
    <header class="hireai-page-hero">
        <span class="kicker"><?php echo esc_html( $hero_kicker ); ?></span>
        <h1><?php echo esc_html( $hero_title ); ?></h1>
        <p><?php echo esc_html( $hero_subtitle ); ?></p>
        <div style="width:1px;height:40px;background:linear-gradient(180deg,#775a19,transparent);margin:20px auto 0"></div>
        <div class="sols-page-hero__actions">
            <a class="btn btn-solid" href="<?php echo esc_url( $hero_cta_primary_link ); ?>">
                <?php echo esc_html( $hero_cta_primary ); ?>
                <?php echo function_exists( 'hireai_svg' ) ? hireai_svg( 'arrow', 14 ) : ''; ?>
            </a>
            <a class="btn btn-outline" href="<?php echo esc_url( $hero_cta_secondary_link ); ?>">
                <?php echo esc_html( $hero_cta_secondary ); ?>
            </a>
        </div>
    </header>

    <!-- ============== 全局 ACF 商品标签筛选 ============== -->
    <section class="sols-tabs-wrap" aria-label="<?php echo esc_attr( $is_en ? 'Filter by product tag' : '按商品标签筛选' ); ?>">
        <nav class="sols-tabs" aria-label="<?php echo esc_attr( $is_en ? 'Product tag filters' : '商品标签筛选' ); ?>" data-sols-tabs>
            <?php foreach ( $filters as $filter ) :
                $chip_slug = (string) $filter['slug'];
                $chip_label = $is_en ? $filter['label_en'] : $filter['label_zh'];
                $chip_href = $chip_slug === 'all'
                    ? remove_query_arg(['solution_tag', 'solution_page'])
                    : add_query_arg(['solution_tag' => $chip_slug, 'solution_page' => false]);
                $chip_active = $chip_slug === ($active_tag === '' ? 'all' : $active_tag);
            ?>
                <a class="sols-tab<?php echo $chip_active ? ' is-active' : ''; ?>"
                   aria-current="<?php echo $chip_active ? 'true' : 'false'; ?>"
                   href="<?php echo esc_url( $chip_href ); ?>">
                    <?php echo esc_html( $chip_label ); ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </section>

    <!-- ============== 商品列表：每页 9 个，顶部/底部翻页 ============== -->
    <section class="sols-grid-wrap" aria-label="<?php echo esc_attr( $is_en ? 'Solution cards' : '方案列表' ); ?>" data-sols-panels>
        <?php if ( $pagination_links ) : ?>
            <nav class="sols-pagination sols-pagination--top" aria-label="<?php echo esc_attr( $is_en ? 'Solution pagination' : '方案分页' ); ?>">
                <?php echo wp_kses_post( $pagination_links ); ?>
            </nav>
        <?php endif; ?>

        <?php if ( ! empty( $cards ) ) : ?>
        <div class="sols-grid">
            <?php foreach ( $cards as $card ) :
                $image = ! empty( $card['image'] ) ? $card['image'] : get_stylesheet_directory_uri() . '/assets/img/defaults/solution-1.jpg';
            ?>
            <article class="sols-card">
                <a class="sols-card__media" href="<?php echo esc_url( $card['permalink'] ); ?>" aria-label="<?php echo esc_attr( $card['title'] ); ?>">
                    <img loading="lazy" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $card['title'] ); ?>"
                         onerror="if (!this.dataset.imageFallback) { this.dataset.imageFallback='1'; this.src='<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/defaults/solution-1.jpg' ); ?>'; } else { this.style.display='none'; }">
                </a>
                <div class="sols-card__body">
                    <?php if ( $card['kicker'] !== '' ) : ?>
                        <span class="sols-card__kicker"><?php echo esc_html( $card['kicker'] ); ?></span>
                    <?php endif; ?>
                    <h3 class="sols-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
                    <p class="sols-card__desc"><?php echo esc_html( $card['excerpt'] ); ?></p>
                    <div class="sols-card__foot">
                        <?php if ( $card['price_html'] !== '' ) : ?>
                            <span class="sols-card__price"><?php echo wp_kses_post( $card['price_html'] ); ?></span>
                        <?php else : ?>
                            <span></span>
                        <?php endif; ?>
                        <a class="sols-card__cta" href="<?php echo esc_url( $card['permalink'] ); ?>">
                            <?php echo esc_html( $card_cta_text ); ?>
                            <?php echo function_exists( 'hireai_svg' ) ? hireai_svg( 'arrow', 14 ) : ''; ?>
                        </a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
            <div class="sols-empty">
                <p><?php echo esc_html( $empty_text ); ?></p>
                <p class="sols-empty__hint">
                    <?php echo esc_html( $is_en ? 'Contact us for bespoke solutions.' : '可联系助理定制方案' ); ?>
                    <a href="<?php echo esc_url( hireai_localized_url( '/contact/' ) ); ?>">→ /contact/</a>
                </p>
            </div>
        <?php endif; ?>

        <?php if ( $pagination_links ) : ?>
            <nav class="sols-pagination sols-pagination--bottom" aria-label="<?php echo esc_attr( $is_en ? 'Solution pagination' : '方案分页' ); ?>">
                <?php echo wp_kses_post( $pagination_links ); ?>
            </nav>
        <?php endif; ?>
    </section>

</main>

<!-- ============== 大 CTA 收尾 ============== -->
<section class="sols-cta" aria-label="<?php echo esc_attr( $final_kicker ); ?>">
    <div class="sols-cta__inner">
        <span class="sols-cta__kicker"><?php echo esc_html( $final_kicker ); ?></span>
        <h2 class="sols-cta__title"><?php echo esc_html( $final_title ); ?></h2>
        <p class="sols-cta__sub"><?php echo esc_html( $final_subtitle ); ?></p>
        <div class="sols-cta__actions">
            <a class="btn btn-solid" href="<?php echo esc_url( hireai_localized_url( home_url( '/contact/' ) ) ); ?>">
                <?php echo esc_html( $final_cta_primary ); ?>
                <?php echo function_exists( 'hireai_svg' ) ? hireai_svg( 'arrow', 14 ) : ''; ?>
            </a>
            <a class="btn btn-outline" href="<?php echo esc_url( hireai_localized_url( home_url( '/category/cases/' ) ) ); ?>">
                <?php echo esc_html( $final_cta_secondary ); ?>
            </a>
        </div>
    </div>
</section>


<?php get_footer(); ?>
