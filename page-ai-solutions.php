<?php
/**
 * Template Name: 聘AI - AI 方案商城（Marketplace v3）
 * 说明：方案商城模板。Hero + 行业筛选 chips + 9 张方案卡片 + 分页 + 邀约礼遇 Invite & Earn 区块。
 *       字段均从 ACF（group_page_ai_solutions / group_solutions_filters / group_solutions_invite_steps）读取。
 * 版本：3.0.0
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

/* ====== 邀约礼遇 Invite & Earn ====== */
$invite_kicker        = hireai_field( 'invite_kicker', $is_en ? 'INVITE & EARN' : '邀约礼遇', $page_id );
$invite_title         = hireai_field( 'invite_title', $is_en ? 'Invite & Earn' : '邀约礼遇 / Invite & Earn', $page_id );
$invite_subtitle      = hireai_field( 'invite_subtitle', $is_en ? 'Share your exclusive invite code with peers to enjoy the Aurelian AI experience together.' : '分享您的专属邀请码，与友共赏 AI 数字人卓越体验。', $page_id );
$invite_code          = hireai_field( 'invite_code', $is_en ? 'hireaipeople.com/invite/VIP001' : 'hireaipeople.com/invite/VIP001', $page_id );
$invite_copy_text     = hireai_field( 'invite_copy_text', $is_en ? 'Copy Link' : '复制链接', $page_id );
$invite_reward_amount = hireai_field( 'invite_reward_amount', $is_en ? '¥500' : '￥500', $page_id );
$invite_reward_label  = hireai_field( 'invite_reward_label', $is_en ? 'Both you and your invitee earn a ¥500 bespoke credit toward your next AI service upgrade.' : '双方均可获得 ￥500 定制额度奖励，用于您的下一次 AI 服务升级。', $page_id );
$invite_steps_label   = hireai_field( 'invite_steps_label', $is_en ? 'How It Works' : '如何运作', $page_id );

/* ====== 筛选 tab 标签 ====== */
$tab_scene_text       = hireai_field( 'filter_tab_scene_label', $is_en ? 'By Scenario' : '按场景分类', $page_id );
$tab_employee_text    = hireai_field( 'filter_tab_employee_label', $is_en ? 'By Digital Employee' : '按数字员工分类', $page_id );

/* ====== 收尾 CTA ====== */
$final_kicker         = hireai_field( 'final_cta_kicker', $is_en ? 'NEXT STEP' : 'NEXT STEP', $page_id );
$final_title          = hireai_field( 'final_cta_title', $is_en ? 'Ready to Elevate Your Brand?' : '准备好为您的品牌升级了吗？', $page_id );
$final_subtitle       = hireai_field( 'final_cta_subtitle', $is_en ? 'Tell us your ambitions and we will design a bespoke AI plan around them.' : '告诉我们您的雄心，我们将围绕它设计一套专属 AI 解决方案。', $page_id );
$final_cta_primary    = hireai_field( 'final_cta_primary_text', $is_en ? 'Start the Conversation' : '开启对话', $page_id );
$final_cta_secondary  = hireai_field( 'final_cta_secondary_text', $is_en ? 'Browse Cases' : '浏览案例', $page_id );

/* ====== 推荐步骤（repeater，兜底 3 步） ====== */
$invite_steps_fallback = array(
    array( 'step_no' => '01', 'step_zh' => '分享您的专属推荐链接。', 'step_en' => 'Share your exclusive referral link.' ),
    array( 'step_no' => '02', 'step_zh' => '好友加入并完成首笔订阅。', 'step_en' => 'Your friend signs up and completes their first subscription.' ),
    array( 'step_no' => '03', 'step_zh' => '双方均可获得 ' . ( $is_en ? '¥500' : '￥500' ) . ' 定制额度奖励。', 'step_en' => 'Both you and your invitee earn a ¥500 bespoke credit toward your next AI service upgrade.' ),
);
$invite_steps = $invite_steps_fallback;
if ( function_exists( 'have_rows' ) && have_rows( 'solutions_invite_steps', $page_id ) ) {
    $tmp = array();
    while ( have_rows( 'solutions_invite_steps', $page_id ) ) {
        the_row();
        $tmp[] = array(
            'step_no' => get_sub_field( 'step_no' ),
            'step_zh' => get_sub_field( 'step_zh' ),
            'step_en' => get_sub_field( 'step_en' ),
        );
    }
    if ( ! empty( $tmp ) ) { $invite_steps = $tmp; }
}

/* ====== 筛选 chips（repeater，兜底 7 个场景） ====== */
$filters_fallback = array(
    array( 'label_zh' => '全国统一者', 'label_en' => 'Apex Strategist', 'slug' => 'apex' ),
    array( 'label_zh' => '总裁高效', 'label_en' => 'Executive', 'slug' => 'executive' ),
    array( 'label_zh' => '营销推广', 'label_en' => 'Marketing', 'slug' => 'marketing' ),
    array( 'label_zh' => '电子商务', 'label_en' => 'E-Commerce', 'slug' => 'ecommerce' ),
    array( 'label_zh' => '创意设计', 'label_en' => 'Design', 'slug' => 'design' ),
    array( 'label_zh' => '高效服务', 'label_en' => 'Hospitality', 'slug' => 'hospitality' ),
    array( 'label_zh' => '企业管理', 'label_en' => 'Enterprise', 'slug' => 'enterprise' ),
);
$filters = $filters_fallback;
/* v3.5.7-p18 Task B: 7 场景 tab fallback = hireai_list_solution_categories()
 *   - repeater 仍可用,ACF 编辑器可继续添加额外场景(向后兼容)
 *   - 默认回退到内置 8 项(全部 + 7 个场景)
 */
$filters = array();
if ( function_exists( 'have_rows' ) && have_rows( 'solutions_filters', $page_id ) ) {
    while ( have_rows( 'solutions_filters', $page_id ) ) {
        the_row();
        $filters[] = array(
            'label_zh' => get_sub_field( 'filter_label_zh' ),
            'label_en' => get_sub_field( 'filter_label_en' ),
            'slug'     => get_sub_field( 'filter_slug' ),
        );
    }
}
if ( empty( $filters ) && function_exists( 'hireai_list_solution_categories' ) ) {
    foreach ( hireai_list_solution_categories() as $slug => $cat ) {
        $filters[] = array(
            'label_zh' => $cat['name_zh'],
            'label_en' => $cat['name_en'],
            'slug'     => $slug,
        );
    }
}

/* v3.5.7-p18 Task B: 静态兜底 $cards 已废弃 —— 改为 WC 商品驱动
 *   - 之前有 9 张硬编码 fallback（含一张 CTA「联络助理」）
 *   - 现在所有卡片 = WC 商品 hireai_get_ai_solutions_products(1, 9)
 *   - 没装 WC 或无商品时 = 空数组,UI 显示 empty_text
 */
$cards = array();

/* ====== v3.0.4 hotfix: WC 集成防御 + 保留 fallback ====== */
/* v3.0.8 (Bug E): 增强 product_cat 探测 + fallback 到「全商品」
 *   之前 v3.0.7 完全不限定 product_cat → 6 个商品应该都拉到，但截图显示部分不可见
 *   根因猜测：catalog_visibility 或 stock_status 过滤过严
 *   修复：
 *     1. hireai_find_product_category_id() 探测 product_cat（限 category 优先）
 *     2. fallback：探测失败时不传 tax_query product_cat，让所有 publish product 都返回
 *     3. admin notice：拉到 0 个时提示
 *
 * 原来 v3.0.3: wc_get_product($pid) 可能返回 false → 触发 fatal error；
   同时 hireai_field('product_operative') 若字段返回数组会导致卡片 kicker 显示 "Array"。
   现包裹 try/catch + is_object 检查；只要 WC 任意一步出错就跳过整个覆盖、保留 $cards 兜底。
 */
/* v3.5.7-p21: 单 panel 一次 query(取代 v3.5.7-p18 的 8 场景分别 query)
 *   - 之前 8 个 panel 各自 query product_cat -> Echo 没写 product_cat -> 全空
 *   - 现在只 query 一次(不带 category/persona 过滤),拿全部 publish 商品
 *   - chip 切换由 client-side JS 处理(data-personas 属性)
 *
 * 备援:hireai_get_ai_solutions_products 不存在 -> 静默空数组
 */
$cards_all = [];
$personas_for_filter = function_exists('hireai_list_solution_personas') ? hireai_list_solution_personas() : [];

if (post_type_exists('product') && function_exists('wc_get_product')) {
    try {
        /* 单次 query:全部 publish 商品,最多 12 张(Echo 数据约 6 张,留扩展) */
        $ids_all = function_exists('hireai_get_ai_solutions_products')
            ? hireai_get_ai_solutions_products(1, 12, '', '')
            : [];
        $cards_all = hireai_build_solution_cards_from_ids($ids_all);

        if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
            error_log('[hireai v3.5.7-p21] single panel cards: ' . count($cards_all));
        }
        if (empty($cards_all) && current_user_can('manage_options')) {
            add_action('admin_notices', function () {
                echo '<div class="notice notice-warning"><p>聘AI: AI 解决方案商城没有显示任何 WC 商品。可能原因:1) 商品 catalog_visibility=hidden; 2) WC 未启用。请到 WC -> 产品 检查。</p></div>';
            });
        }
    } catch (Throwable $e) {
        $cards_all = [];
    }
}

/* 默认 $cards(向后兼容 hero / pagination 旧代码引用) */
$cards = $cards_all;
/* v3.5.7-p21: 保留 $cards_by_tab key='all' 让旧 panel 渲染回退路径不报错(虽然不再使用) */
$cards_by_tab = ['all' => $cards_all];
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

/* 筛选区 */
.sols-filter {
    padding: 0 20px clamp(32px, 5vw, 56px);
    max-width: 1440px;
    margin: 0 auto;
}
.sols-filter__tabs {
    display: flex;
    gap: 0;
    margin-bottom: 0;
}
.sols-filter__tab {
    padding: 14px 32px;
    background: var(--surface-container-low, #f4f3f3);
    border: 1px solid var(--outline-variant, #e2e2e2);
    border-bottom: none;
    border-radius: 8px 8px 0 0;
    color: var(--on-surface-variant, #747878);
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-size: 16px;
    cursor: pointer;
    margin-right: 4px;
    transition: all .3s ease;
}
.sols-filter__tab.is-active {
    background: var(--surface, #faf9f9);
    color: var(--primary, #1a1c1c);
    border-color: rgba(119, 90, 25, .4);
    font-weight: 600;
    position: relative;
}
.sols-filter__tab.is-active::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #775a19, #e9c176);
    border-radius: 8px 8px 0 0;
}
.sols-filter__panel {
    background: var(--surface, #faf9f9);
    border: 1px solid rgba(119, 90, 25, .35);
    border-radius: 0 8px 8px 8px;
    padding: 28px clamp(20px, 3vw, 40px);
    margin-top: -1px;
}
.sols-filter__chips {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
/* v3.0.6: font uses Montserrat (English body spec) */
.sols-filter__chip {
    padding: 8px 22px;
    border: 1px solid var(--outline-variant, #e2e2e2);
    border-radius: 999px;
    color: var(--on-surface-variant, #444748);
    background: transparent;
    font-size: 14px;
    cursor: pointer;
    transition: all .3s ease;
    font-family: var(--font-label, 'Montserrat', 'Inter', sans-serif), sans-serif;
}
.sols-filter__chip:hover,
.sols-filter__chip.is-active {
    border-color: var(--gold-leaf, #775a19);
    color: var(--gold-leaf, #775a19);
    background: rgba(119, 90, 25, .05);
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
    .sols-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1080px) {
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
    aspect-ratio: 4/3;
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
    padding: 16px 20px clamp(80px, 8vw, 120px);
}
.sols-pagination__btn {
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
}
.sols-pagination__btn:hover,
.sols-pagination__btn.is-active {
    border-color: var(--gold-leaf, #775a19);
    color: var(--gold-leaf, #775a19);
    background: rgba(119, 90, 25, .05);
}
.sols-pagination__ellipsis {
    color: var(--on-surface-variant, #444748);
    padding: 0 6px;
}

/* ============== 邀约礼遇 Invite & Earn ============== */
.sols-invite {
    background: var(--surface-container-low, #f4f3f3);
    border-top: 1px solid rgba(196, 199, 199, .3);
    padding: clamp(60px, 8vw, 100px) 20px;
}
.sols-invite__inner {
    max-width: 1080px;
    margin: 0 auto;
}
.sols-invite__head {
    text-align: center;
    margin-bottom: 48px;
}
.sols-invite__kicker {
    display: inline-block;
    margin-bottom: 14px;
    color: var(--gold-leaf, #775a19);
    font-family: var(--font-label, 'Inter', sans-serif);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
}
.sols-invite__title {
    margin: 0 0 14px;
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-size: clamp(32px, 4vw, 48px);
    font-weight: 600;
    background: linear-gradient(to right, #775a19, #e9c176, #775a19);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
}
.sols-invite__subtitle {
    /* v3.0.8 (Bug C): 移除 italic + 改 on-surface 颜色 + 16px */
    font-family: var(--font-body-en, 'Inter'), sans-serif;
    color: var(--on-surface, #1a1c1c);
    font-style: normal !important;
    font-size: 16px;
    line-height: 1.6;
    max-width: 640px;
    margin: 0 auto;
}
.sols-invite__card {
    background: rgba(249, 248, 243, .7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(119, 90, 25, .3);
    border-radius: 16px;
    padding: clamp(28px, 4vw, 48px);
    box-shadow: 0 18px 60px -20px rgba(119, 90, 25, .12);
}
.sols-invite__row {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 36px;
}
@media (min-width: 720px) {
    .sols-invite__row { flex-direction: row; }
}
.sols-invite__code-wrap {
    flex: 1 1 auto;
    position: relative;
    display: flex;
    align-items: center;
    background: var(--surface-container-lowest, #fff);
    border: 1px solid rgba(119, 90, 25, .35);
    border-radius: 10px;
    padding: 16px 22px;
    font-family: var(--font-mono, 'Inter', monospace);
    font-size: 15px;
    color: var(--on-surface, #1a1c1c);
    overflow: hidden;
}
.sols-invite__code-wrap code {
    font-family: inherit;
    font-size: inherit;
    color: inherit;
    background: transparent;
    word-break: break-all;
}
.sols-invite__copy {
    flex: 0 0 auto;
    display: inline-flex; align-items: center; justify-content: center; gap: 10px;
    padding: 16px 32px;
    border: 1px solid rgba(119, 90, 25, .4);
    border-radius: 10px;
    background: transparent;
    color: var(--primary, #1a1c1c);
    font-family: var(--font-label, 'Inter', sans-serif);
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    cursor: pointer;
    transition: all .3s ease;
}
.sols-invite__copy:hover {
    border-color: var(--gold-leaf, #775a19);
    box-shadow: inset 0 0 12px rgba(119, 90, 25, .12), 0 0 18px rgba(119, 90, 25, .18);
    color: var(--gold-leaf, #775a19);
}
.sols-invite__copy svg { width: 16px; height: 16px; }
.sols-invite__steps {
    max-width: 720px;
    margin: 0 auto;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 22px;
}
.sols-invite__step {
    display: flex;
    gap: 22px;
    align-items: flex-start;
}
.sols-invite__step-no {
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-size: 32px;
    line-height: 1;
    color: var(--gold-leaf, #775a19);
    opacity: .55;
    flex: 0 0 auto;
    min-width: 56px;
}
.sols-invite__step-text {
    margin: 0;
    padding-top: 6px;
    color: var(--on-surface-variant, #444748);
    font-size: 15px;
    line-height: 1.7;
}
.sols-invite__reward {
    color: var(--gold-leaf, #775a19);
    font-weight: 700;
    font-style: normal;
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
    <header class="sols-page-hero">
        <span class="sols-page-hero__kicker"><?php echo esc_html( $hero_kicker ); ?></span>
        <h1 class="sols-page-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
        <p class="sols-page-hero__subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
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

    <!-- ============== v3.5.7-p21: 6 个数字人 chip(取代 8 场景 tab) ============== -->
    <section class="sols-tabs-wrap" aria-label="<?php echo esc_attr( $is_en ? 'Filter by digital employee' : '按数字人筛选' ); ?>">
        <div class="sols-tabs" role="tablist" data-sols-tabs>
            <?php
            $first_chip = true;
            foreach ( $filters as $f ) :
                $chip_label = $is_en ? $f['label_en'] : $f['label_zh'];
                $chip_slug  = isset( $f['slug'] ) ? (string) $f['slug'] : '';
                $is_active  = $first_chip;
                $first_chip = false;
            ?>
                <button type="button" role="tab" class="sols-tab<?php echo $is_active ? ' is-active' : ''; ?>"
                        aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                        data-tab="<?php echo esc_attr( $chip_slug ); ?>">
                    <?php echo esc_html( $chip_label ); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ============== v3.5.7-p21: 单 panel 一次渲染 12 张卡(JS chip 切换 hide/show) ============== -->
    <section class="sols-grid-wrap" aria-label="<?php echo esc_attr( $is_en ? 'Solution cards' : '方案列表' ); ?>" data-sols-panels>
        <?php
        $empty_text_local = $empty_text;
        $card_template    = function ($c) use ($is_en, $card_cta_text) {
            $kicker = $is_en ? $c['kicker_en'] : $c['kicker_zh'];
            $title  = $is_en ? $c['title_en']  : $c['title_zh'];
            $desc   = $is_en ? $c['desc_en']   : $c['desc_zh'];
            $img    = isset($c['image']) ? $c['image'] : 'defaults/solution-1.jpg';
            $price  = isset($c['price']) ? $c['price'] : '';
            $link   = isset($c['link'])  ? $c['link']  : '/contact/';
            $personas_attr = '';
            if (!empty($c['personas']) && is_array($c['personas'])) {
                $personas_attr = ' data-personas="' . esc_attr(implode(' ', $c['personas'])) . '"';
            }
            $img_url = function_exists('hireai_default_image') ? hireai_default_image($img) : '';
            if (!$img_url) { $img_url = get_stylesheet_directory_uri() . '/assets/img/' . $img; }
            ?>
            <article class="sols-card"<?php echo $personas_attr; ?>>
                <a class="sols-card__media" href="<?php echo esc_url($link); ?>" aria-label="<?php echo esc_attr($title); ?>">
                    <img loading="lazy" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>">
                </a>
                <div class="sols-card__body">
                    <span class="sols-card__kicker"><?php echo esc_html($kicker); ?></span>
                    <h3 class="sols-card__title"><?php echo esc_html($title); ?></h3>
                    <p class="sols-card__desc"><?php echo esc_html($desc); ?></p>
                    <div class="sols-card__foot">
                        <?php if ($price) : ?>
                            <span class="sols-card__price"><?php echo esc_html($price); ?></span>
                        <?php else : ?>
                            <span></span>
                        <?php endif; ?>
                        <a class="sols-card__cta" href="<?php echo esc_url($link); ?>">
                            <?php echo esc_html($card_cta_text); ?>
                            <?php echo function_exists('hireai_svg') ? hireai_svg('arrow', 14) : ''; ?>
                        </a>
                    </div>
                </div>
            </article>
            <?php
        };
        ?>
            <div class="sols-tab-panel is-active" data-panel="all" role="tabpanel">
                <div class="sols-grid">
                    <?php if ( ! empty( $cards_all ) ) : ?>
                        <?php foreach ( $cards_all as $c ) :
                            $tmpl = hireai_solution_card_template_fields( $c );
                            $card_template( $tmpl );
                        endforeach; ?>
                    <?php else : ?>
                        <div class="sols-empty">
                            <p><?php echo esc_html( $is_en ? 'No solutions in this category yet.' : '该数字人暂无作品' ); ?></p>
                            <p class="sols-empty__hint"><?php echo esc_html( $is_en ? 'Contact us for bespoke solutions.' : '可联系助理定制方案' ); ?> <a href="/contact/">→ /contact/</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
    </section>

</main>

<!-- ============== 邀约礼遇 Invite & Earn ============== -->
<section class="sols-invite" id="invite-earn" aria-label="<?php echo esc_attr( $invite_kicker ); ?>">
    <div class="sols-invite__inner">
        <header class="sols-invite__head">
            <span class="sols-invite__kicker"><?php echo esc_html( $invite_kicker ); ?></span>
            <h2 class="sols-invite__title"><?php echo esc_html( $invite_title ); ?></h2>
            <p class="sols-invite__subtitle"><?php echo esc_html( $invite_subtitle ); ?></p>
        </header>

        <div class="sols-invite__card">
            <div class="sols-invite__row">
                <div class="sols-invite__code-wrap">
                    <code id="sols-invite-code"><?php echo esc_html( $invite_code ); ?></code>
                </div>
                <button class="sols-invite__copy" type="button" data-copy-target="sols-invite-code" data-copied="<?php echo esc_attr( $is_en ? 'Copied' : '已复制' ); ?>">
                    <?php echo function_exists( 'hireai_svg' ) ? hireai_svg( 'copy', 14 ) : ''; ?>
                    <span><?php echo esc_html( $invite_copy_text ); ?></span>
                </button>
            </div>

            <ol class="sols-invite__steps" aria-label="<?php echo esc_attr( $invite_steps_label ); ?>">
                <?php foreach ( $invite_steps as $step ) :
                    $step_no   = isset( $step['step_no'] ) ? $step['step_no'] : '';
                    $step_text = $is_en ? ( isset( $step['step_en'] ) ? $step['step_en'] : '' ) : ( isset( $step['step_zh'] ) ? $step['step_zh'] : '' );
                    $highlight = (string) $invite_reward_amount;
                    $rendered  = str_replace(
                        $highlight,
                        '<span class="sols-invite__reward">' . esc_html( $highlight ) . '</span>',
                        esc_html( $step_text )
                    );
                ?>
                    <li class="sols-invite__step">
                        <span class="sols-invite__step-no"><?php echo esc_html( $step_no ); ?></span>
                        <p class="sols-invite__step-text"><?php echo $rendered; /* already escaped; reward span is trusted */ ?></p>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</section>

<!-- ============== 大 CTA 收尾 ============== -->
<section class="sols-cta" aria-label="<?php echo esc_attr( $final_kicker ); ?>">
    <div class="sols-cta__inner">
        <span class="sols-invite__kicker" style="margin-bottom:14px;"><?php echo esc_html( $final_kicker ); ?></span>
        <h2 class="sols-cta__title"><?php echo esc_html( $final_title ); ?></h2>
        <p class="sols-cta__sub"><?php echo esc_html( $final_subtitle ); ?></p>
        <div class="sols-cta__actions">
            <a class="btn btn-solid" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
                <?php echo esc_html( $final_cta_primary ); ?>
                <?php echo function_exists( 'hireai_svg' ) ? hireai_svg( 'arrow', 14 ) : ''; ?>
            </a>
            <a class="btn btn-outline" href="<?php echo esc_url( home_url( '/category/cases/' ) ); ?>">
                <?php echo esc_html( $final_cta_secondary ); ?>
            </a>
        </div>
    </div>
</section>

<script>
/* v3.5.7-p18 Task B: 邀约礼遇 — 复制推荐码 + 8 tab 切换 + 数字人筛选 */
(function(){
    /* === 1. 复制推荐码 === */
    var btn = document.querySelector('.sols-invite__copy');
    if (btn) {
        btn.addEventListener('click', function(){
            var targetId = btn.getAttribute('data-copy-target');
            var codeEl = document.getElementById(targetId);
            if (!codeEl) return;
            var text = codeEl.textContent || '';
            var done = function(){
                var labelEl = btn.querySelector('span');
                if (labelEl) {
                    var orig = labelEl.textContent;
                    labelEl.textContent = btn.getAttribute('data-copied') || 'Copied';
                    setTimeout(function(){ labelEl.textContent = orig; }, 1800);
                }
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(done).catch(function(){
                    var ta = document.createElement('textarea');
                    ta.value = text; document.body.appendChild(ta);
                    ta.select(); document.execCommand('copy'); ta.remove();
                    done();
                });
            } else {
                var ta = document.createElement('textarea');
                ta.value = text; document.body.appendChild(ta);
                ta.select(); document.execCommand('copy'); ta.remove();
                done();
            }
        });
    }

    /* === 2. v3.5.7-p21: 6 个数字人 chip 切换(单 panel,client-side 过滤) === */
    var chips   = document.querySelectorAll('[data-sols-tabs] .sols-tab');
    var panel   = document.querySelector('[data-sols-panels] .sols-tab-panel.is-active');
    var cards   = panel ? panel.querySelectorAll('.sols-card') : [];
    var activePersona = '';
    function applyChipFilter() {
        cards.forEach(function(card){
            if (!activePersona) { card.style.display = ''; return; }
            var dataP = card.getAttribute('data-personas') || '';
            var list  = dataP.split(/\s+/).filter(Boolean);
            card.style.display = list.indexOf(activePersona) >= 0 ? '' : 'none';
        });
    }
    function switchChip(slug) {
        chips.forEach(function(c){
            var isActive = c.getAttribute('data-tab') === slug;
            c.classList.toggle('is-active', isActive);
            c.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
        activePersona = (!slug || slug === 'all') ? '' : slug;
        applyChipFilter();
        /* 更新 URL hash 以便分享 */
        if (history && history.replaceState) {
            try {
                history.replaceState(null, '', activePersona ? '#persona=' + activePersona : window.location.pathname + window.location.search);
            } catch(e) {}
        }
    }
    chips.forEach(function(chip){
        chip.addEventListener('click', function(){
            switchChip(chip.getAttribute('data-tab'));
        });
    });

    /* === 3. 初始:从 URL hash 恢复 chip 状态 === */
    var hashMatch = (window.location.hash || '').match(/persona=([a-z0-9_-]+)/);
    if (hashMatch && hashMatch[1]) {
        var matched = document.querySelector('[data-sols-tabs] .sols-tab[data-tab="' + hashMatch[1] + '"]');
        if (matched) switchChip(hashMatch[1]);
    }
})();
</script>

<?php get_footer(); ?>
