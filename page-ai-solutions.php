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
if ( function_exists( 'have_rows' ) && have_rows( 'solutions_filters', $page_id ) ) {
    $tmp = array();
    while ( have_rows( 'solutions_filters', $page_id ) ) {
        the_row();
        $tmp[] = array(
            'label_zh' => get_sub_field( 'filter_label_zh' ),
            'label_en' => get_sub_field( 'filter_label_en' ),
            'slug'     => get_sub_field( 'filter_slug' ),
        );
    }
    if ( ! empty( $tmp ) ) { $filters = $tmp; }
}

/* ====== 方案卡片（兜底 9 张） ====== */
$cards = array(
    array(
        'kicker_zh' => '公关危机', 'kicker_en' => 'Crisis Counsel',
        'title_zh'  => '公关审惨预警', 'title_en' => 'Crisis Forecast',
        'desc_zh'   => 'AI驱动您的全球网络神经全天候守护品牌声誉，提供24×7全天候的舆情风暴预警机制。',
        'desc_en'   => 'AI-driven global network nerve provides 24/7 brand reputation monitoring and crisis early-warning.',
        'price'     => '¥3000 起', 'image' => 'home/solution-finance.png',
        'is_cta'    => false, 'link' => '/contact/',
    ),
    array(
        'kicker_zh' => '整合营销', 'kicker_en' => 'Marketing',
        'title_zh'  => 'IP联名及营销整合', 'title_en' => 'IP Co-Marketing',
        'desc_zh'   => '利用数字孪生与跨界算法，精准匹配全球顶级IP，打造现象级的跨界营销链路。',
        'desc_en'   => 'Digital twin + cross-boundary algorithms precisely match global premium IP for blockbuster collaborations.',
        'price'     => '¥8000 起', 'image' => 'home/solution-retail.png',
        'is_cta'    => false, 'link' => '/contact/',
    ),
    array(
        'kicker_zh' => '电商视觉', 'kicker_en' => 'E-Commerce',
        'title_zh'  => '电商主图及套图设计', 'title_en' => 'E-Commerce Hero Visuals',
        'desc_zh'   => '生成式AI打造高级感产品的高级视觉矩阵，提升奢品与高定类目的转化率。',
        'desc_en'   => 'Generative AI crafts premium product visual matrices to lift conversions in luxury and bespoke categories.',
        'price'     => '¥1500 起', 'image' => 'defaults/solution-1.jpg',
        'is_cta'    => false, 'link' => '/contact/',
    ),
    array(
        'kicker_zh' => '创意设计', 'kicker_en' => 'Creative',
        'title_zh'  => 'AI艺术图片设计', 'title_en' => 'AI Art Imagery',
        'desc_zh'   => '突破物理边界的视觉艺术创造，专为顶级展会、画册及数字资产量身定制。',
        'desc_en'   => 'Boundary-defying visual art, bespoke for premium exhibitions, lookbooks, and digital assets.',
        'price'     => '¥3000 起', 'image' => 'defaults/solution-2.jpg',
        'is_cta'    => false, 'link' => '/contact/',
    ),
    array(
        'kicker_zh' => '酒店服务', 'kicker_en' => 'Hospitality',
        'title_zh'  => '鸡尾酒单设计师', 'title_en' => 'Cocktail Menu Designer',
        'desc_zh'   => '结合风味算法与视觉美学，为高端酒吧与私人酒会设计独具匠心的酒单体验。',
        'desc_en'   => 'Pairing flavor algorithms with visual artistry, crafting signature menus for premium bars and private events.',
        'price'     => '¥1200 起', 'image' => 'defaults/solution-3.jpg',
        'is_cta'    => false, 'link' => '/contact/',
    ),
    array(
        'kicker_zh' => '数据洞察', 'kicker_en' => 'Data Insight',
        'title_zh'  => '高定客户数据洞察', 'title_en' => 'Bespoke Client Insight',
        'desc_zh'   => '深度挖掘VIP客户偏好，构建多维立体画像，实现千人千面的精准营销与尊享服务。',
        'desc_en'   => 'Mine VIP preferences and build multidimensional personas for one-to-one marketing and concierge service.',
        'price'     => '¥5000 起', 'image' => 'defaults/solution-4.jpg',
        'is_cta'    => false, 'link' => '/contact/',
    ),
    array(
        'kicker_zh' => '金融风控', 'kicker_en' => 'Finance',
        'title_zh'  => '智能投顾助理', 'title_en' => 'Smart Advisor',
        'desc_zh'   => '结合宏观经济指标与市场情绪，提供个性化的资产配置建议与风险预警。',
        'desc_en'   => 'Macro indicators and market sentiment fused—personalized asset allocation with proactive risk alerts.',
        'price'     => '¥12000 起', 'image' => 'defaults/solution-1.jpg',
        'is_cta'    => false, 'link' => '/contact/',
    ),
    array(
        'kicker_zh' => '空间美学', 'kicker_en' => 'Spatial',
        'title_zh'  => '奢华空间漫游生成', 'title_en' => 'Luxury Spatial Walkthrough',
        'desc_zh'   => '分钟级生成超写实的高端商业空间与私宅内部漫游视频，革新设计提案体验。',
        'desc_en'   => 'Photorealistic premium commercial and private residence walkthroughs in minutes—reinventing design proposals.',
        'price'     => '¥8000 起', 'image' => 'defaults/solution-2.jpg',
        'is_cta'    => false, 'link' => '/contact/',
    ),
    array(
        'kicker_zh' => '企业服务', 'kicker_en' => 'Enterprise',
        'title_zh'  => '个性化定制服务', 'title_en' => 'Personalized Bespoke',
        'desc_zh'   => '全方位企业级AI咨询与定制数字员工方案，为高净值企业级客群提供专属服务。',
        'desc_en'   => 'Full-spectrum enterprise AI consulting and bespoke digital employees—signature service for HNW enterprises.',
        'price'     => '', 'image' => 'defaults/solution-3.jpg',
        'is_cta'    => true, 'link' => '/contact/',
    ),
);

/* ====== v3.0.4 hotfix: WC 集成防御 + 保留 fallback ====== */
/* 原来 v3.0.3: wc_get_product($pid) 可能返回 false → 触发 fatal error；
   同时 hireai_field('product_operative') 若字段返回数组会导致卡片 kicker 显示 "Array"。
   现包裹 try/catch + is_object 检查；只要 WC 任意一步出错就跳过整个覆盖、保留 $cards 兜底。 */
$wc_products = [];
if (post_type_exists('product') && function_exists('wc_get_product')) {
    try {
        $paged = max(1, get_query_var('sols_paged') ?: (isset($_GET['sols_page']) ? (int)$_GET['sols_page'] : 1));
        $wc_q = new WP_Query([
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 9,
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'  => false,
        ]);
        if ($wc_q->have_posts()) {
            while ($wc_q->have_posts()) {
                $wc_q->the_post();
                $pid = get_the_ID();
                $wc_obj = wc_get_product($pid);
                $price_html = '';
                $stock      = 'instock';
                if (is_object($wc_obj)) {
                    if (function_exists('wc_format_price_range')) {
                        $price_html = wc_format_price_range($wc_obj);
                    } elseif (method_exists($wc_obj, 'get_price_html')) {
                        $price_html = $wc_obj->get_price_html();
                    }
                    if (method_exists($wc_obj, 'get_stock_status')) {
                        $stock = (string) $wc_obj->get_stock_status();
                    }
                }
                // kicker / retainer 强制转字符串，避免 ACF 返回数组时泄漏 "Array"
                $kicker_raw   = function_exists('hireai_field') ? hireai_field('product_operative', '', $pid) : '';
                $retainer_raw = function_exists('hireai_field') ? hireai_field('product_retainer_label', '', $pid) : '';
                $kicker_str   = is_array($kicker_raw)   ? '' : (string) $kicker_raw;
                $retainer_str = is_array($retainer_raw) ? '' : (string) $retainer_raw;
                $wc_products[] = [
                    'id'       => $pid,
                    'title'    => get_the_title(),
                    'excerpt'  => get_the_excerpt() ?: wp_trim_words(strip_tags(get_the_content()), 24, '…'),
                    'image'    => get_the_post_thumbnail_url($pid, 'medium'),
                    'price'    => $price_html,
                    'stock'    => $stock,
                    'permalink'=> get_permalink($pid),
                    'kicker'   => $kicker_str,
                    'retainer' => $retainer_str,
                ];
            }
            wp_reset_postdata();
            if (!empty($wc_products)) {
                $cards_total = (int) $wc_q->max_num_pages;
                // 把静态 $cards 替换为真实商品；用 is_string 兜底，防止残留数组
                $cards = array_map(function ($p) {
                    $kz = isset($p['kicker']) && !is_array($p['kicker']) ? (string) $p['kicker'] : '';
                    return [
                        'kicker_zh' => $kz, 'kicker_en' => $kz,
                        'title_zh'  => (string) $p['title'], 'title_en'  => (string) $p['title'],
                        'desc_zh'   => (string) $p['excerpt'], 'desc_en' => (string) $p['excerpt'],
                        'price'     => isset($p['price']) ? (string) $p['price'] : '',
                        'image'     => !empty($p['image']) ? $p['image'] : 'defaults/solution-1.jpg',
                        'is_cta'    => false,
                        'link'      => (string) $p['permalink'],
                    ];
                }, $wc_products);
            }
        }
    } catch (Throwable $e) {
        // WC 任意一步抛异常 → 静默回退到静态 $cards 兜底
        $wc_products = [];
    }
}

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
.sols-page-hero__title {
    margin: 0 auto 24px;
    max-width: 920px;
    font-family: var(--font-serif, 'Playfair Display', serif);
    font-weight: 700;
    font-size: clamp(40px, 6vw, 72px);
    line-height: 1.1;
    letter-spacing: -0.01em;
    background: linear-gradient(to right, #775a19, #e9c176, #775a19);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
}
.sols-page-hero__subtitle {
    max-width: 720px;
    margin: 0 auto;
    color: var(--on-surface-variant, #444748);
    font-style: italic;
    font-size: clamp(16px, 1.6vw, 18px);
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
    color: var(--on-surface-variant, #444748);
    font-style: italic;
    font-size: clamp(15px, 1.6vw, 18px);
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

    <!-- ============== 筛选 ============== -->
    <section class="sols-filter" aria-label="<?php echo esc_attr( $is_en ? 'Solution filters' : '方案筛选' ); ?>">
        <div class="sols-filter__tabs" role="tablist">
            <button class="sols-filter__tab is-active" type="button" role="tab" aria-selected="true" data-tab="scene">
                <?php echo esc_html( $tab_scene_text ); ?>
            </button>
            <button class="sols-filter__tab" type="button" role="tab" aria-selected="false" data-tab="employee">
                <?php echo esc_html( $tab_employee_text ); ?>
            </button>
        </div>
        <div class="sols-filter__panel">
            <div class="sols-filter__chips" role="group">
                <?php foreach ( $filters as $idx => $f ) :
                    $label    = $is_en ? $f['label_en'] : $f['label_zh'];
                    $slug     = isset( $f['slug'] ) ? $f['slug'] : '';
                    $is_first = ( 0 === $idx );
                ?>
                    <button class="sols-filter__chip<?php echo $is_first ? ' is-active' : ''; ?>" type="button" data-slug="<?php echo esc_attr( $slug ); ?>">
                        <?php echo esc_html( $label ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============== 方案卡片网格 ============== -->
    <section class="sols-grid-wrap" aria-label="<?php echo esc_attr( $is_en ? 'Solution cards' : '方案列表' ); ?>">
        <div class="sols-grid">
            <?php foreach ( $cards as $i => $c ) :
                $kicker = $is_en ? $c['kicker_en'] : $c['kicker_zh'];
                $title  = $is_en ? $c['title_en']  : $c['title_zh'];
                $desc   = $is_en ? $c['desc_en']   : $c['desc_zh'];
                $img    = isset( $c['image'] ) ? $c['image'] : 'defaults/solution-1.jpg';
                $price  = isset( $c['price'] ) ? $c['price'] : '';
                $link   = isset( $c['link'] )  ? $c['link']  : '/contact/';
                $is_cta = ! empty( $c['is_cta'] );
                $cta_label = $is_cta ? ( $is_en ? 'Contact Us' : '联络助理' ) : $card_cta_text;
                $img_url = function_exists( 'hireai_default_image' ) ? hireai_default_image( $img ) : '';
                if ( ! $img_url ) { $img_url = get_stylesheet_directory_uri() . '/assets/img/' . $img; }
            ?>
                <article class="sols-card<?php echo $is_cta ? ' sols-card--cta-highlight' : ''; ?>">
                    <a class="sols-card__media" href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
                        <img loading="lazy" src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                    </a>
                    <div class="sols-card__body">
                        <span class="sols-card__kicker"><?php echo esc_html( $kicker ); ?></span>
                        <h3 class="sols-card__title"><?php echo esc_html( $title ); ?></h3>
                        <p class="sols-card__desc"><?php echo esc_html( $desc ); ?></p>
                        <div class="sols-card__foot">
                            <?php if ( $price ) : ?>
                                <span class="sols-card__price"><?php echo esc_html( $price ); ?></span>
                            <?php else : ?>
                                <span></span>
                            <?php endif; ?>
                            <a class="sols-card__cta" href="<?php echo esc_url( $link ); ?>">
                                <?php echo esc_html( $cta_label ); ?>
                                <?php echo function_exists( 'hireai_svg' ) ? hireai_svg( 'arrow', 14 ) : ''; ?>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- ============== 分页 ============== -->
        <nav class="sols-pagination" aria-label="<?php echo esc_attr( $is_en ? 'Pagination' : '分页' ); ?>">
            <button class="sols-pagination__btn" type="button" aria-label="<?php echo esc_attr( $is_en ? 'Previous' : '上一页' ); ?>">
                <?php echo function_exists( 'hireai_svg' ) ? hireai_svg( 'chevron-left', 14 ) : '&lsaquo;'; ?>
            </button>
            <button class="sols-pagination__btn is-active" type="button">1</button>
            <button class="sols-pagination__btn" type="button">2</button>
            <button class="sols-pagination__btn" type="button">3</button>
            <span class="sols-pagination__ellipsis">&hellip;</span>
            <button class="sols-pagination__btn" type="button">8</button>
            <button class="sols-pagination__btn" type="button" aria-label="<?php echo esc_attr( $is_en ? 'Next' : '下一页' ); ?>">
                <?php echo function_exists( 'hireai_svg' ) ? hireai_svg( 'chevron-right', 14 ) : '&rsaquo;'; ?>
            </button>
        </nav>
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
/* 邀约礼遇 — 复制推荐码 / 筛选 / 分页交互 */
(function(){
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

    var chips = document.querySelectorAll('.sols-filter__chip');
    chips.forEach(function(chip){
        chip.addEventListener('click', function(){
            chips.forEach(function(c){ c.classList.remove('is-active'); });
            chip.classList.add('is-active');
        });
    });

    var tabs = document.querySelectorAll('.sols-filter__tab');
    tabs.forEach(function(tab){
        tab.addEventListener('click', function(){
            tabs.forEach(function(t){ t.classList.remove('is-active'); t.setAttribute('aria-selected','false'); });
            tab.classList.add('is-active');
            tab.setAttribute('aria-selected','true');
        });
    });

    var pageBtns = document.querySelectorAll('.sols-pagination__btn');
    pageBtns.forEach(function(b){
        b.addEventListener('click', function(){
            if (b.textContent.trim() === '') return;
            pageBtns.forEach(function(x){ x.classList.remove('is-active'); });
            b.classList.add('is-active');
        });
    });
})();
</script>

<?php get_footer(); ?>
