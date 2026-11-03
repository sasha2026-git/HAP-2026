<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: Front Page
 * Hire AI People — design-baseline v3.0.1 (2026-08-22)
 * 六大 Section：Hero → Products → Solutions → Cases → FAQ → CTA
 *
 * v3.0.1 修复 v3.0.0 已知问题：
 *   - 修复 Hero CTA 按钮乱码：用 hireai_field('fp_hero_cta_1_url/title') 双字段读取，
 *     不再用 hireai_link_lang('fp_hero_cta_1', ...)'fp_hero_cta_1'（字段不存在，回落到了 URL 当 title）
 *   - 增加 Intro 区块 "探索更多" 幽灵按钮（design 要求）
 *   - 增加 Products 区块 "探索更多" 下划线链接（design 要求）
 *   - 移除 Material Symbols Outlined CDN 引用（用 inline SVG 替代）
 *   - 全 button 严格 label-md（14px / 600 / 0.1em uppercase）
 *   - 全 kicker 严格 label-sm（12px / 500 / 0.05em uppercase）
 *   - section gap 严格 120px（--gap: clamp(60px,8vw,120px)）
 *   - 金色斜体用 .hireai-fp__gold（纯色 #775a19，白底达标）
 */
get_header();

/* ── 当前语言 ── */
$suffix = hireai_lang_suffix();
$is_en  = ($suffix === '_en');
$lang   = $is_en ? 'en' : 'zh';
$home   = get_stylesheet_directory_uri();
$front_page_id = function_exists('hireai_front_page_data_id') ? hireai_front_page_data_id() : get_queried_object_id();

/* ═══════════════════════════════════════════════════════════════════════
   ACF 数据加载（严格匹配 functions.php 第 774–928 行注册的字段）
   注：fp_*_url / fp_*_title / fp_*_label 都是单独的 text 字段（_zh/_en 双后缀）
   ═══════════════════════════════════════════════════════════════════════ */

/* Hero */
$hero_kicker   = hireai_field_lang('fp_hero_kicker',   $lang, $is_en ? 'AETHER LUXE AI' : '工匠精神与算法', $front_page_id);
$hero_static   = hireai_field_lang('fp_hero_static',   $lang, $is_en ? 'Redefining' : '重新定义', $front_page_id);
$hero_accent_primary   = hireai_field_lang('fp_hero_accent_primary', $lang, $is_en ? 'Digital' : '', $front_page_id);
$hero_accent_secondary = hireai_field_lang('fp_hero_accent_secondary', $lang, $is_en ? 'Productivity' : '', $front_page_id);
if ('' === $hero_accent_primary && '' === $hero_accent_secondary) {
    $hero_accent = hireai_field_lang('fp_hero_accent', $lang, $is_en ? 'Digital Productivity' : '数字生产力', $front_page_id);
    if (preg_match('/^(\p{Han}{2})(\p{Han}+)$/u', $hero_accent, $hero_accent_parts)) {
        $hero_accent_primary   = $hero_accent_parts[1];
        $hero_accent_secondary = $hero_accent_parts[2];
    } else {
        $hero_accent_primary = $hero_accent;
    }
}
$hero_subtitle = hireai_field_lang('fp_hero_subtitle', $lang, $is_en ? 'Fusing cutting-edge technology with a luxurious aesthetic to craft your bespoke digital workforce.' : '融合尖端科技与奢华质感，为您打造专属的数字员工矩阵。', $front_page_id);
$hero_image    = hireai_image('fp_hero_image', $home . '/assets/img/defaults/hero-home.jpg', $front_page_id);
$hero_cta_1_url   = hireai_field('fp_hero_cta_1_url',   home_url('/ai-employees/'), $front_page_id);
$hero_cta_1_title = hireai_field_lang('fp_hero_cta_1_title', $lang, $is_en ? 'Explore Series' : '探索系列', $front_page_id);
$hero_cta_2_url   = hireai_field('fp_hero_cta_2_url',   home_url('/contact/'), $front_page_id);
$hero_cta_2_title = hireai_field_lang('fp_hero_cta_2_title', $lang, $is_en ? 'Custom Consultation' : '定制咨询', $front_page_id);

/* Products section header */
$prod_section_kicker = hireai_field_lang('fp_products_kicker', $lang, $is_en ? 'Limited Neural Series' : '限量神经元系列', $front_page_id);
$prod_section_title  = hireai_field_lang('fp_products_title',  $lang, $is_en ? 'AI Digital Employees' : 'AI 数字员工', $front_page_id);
$prod_section_sub    = hireai_field_lang('fp_products_subtitle', $lang, $is_en ? 'Each digital employee brings a unique soul, refined skills, and unmatched capabilities.' : '每一位数字员工都拥有独特的灵魂、技能与能力，随时加入您的团队。', $front_page_id);
$prod_explore_label  = hireai_field_lang('fp_products_explore_label', $lang, $is_en ? 'Explore More' : '探索更多', $front_page_id);
$prod_explore_url    = hireai_field('fp_products_explore_url', home_url('/ai-employees/'), $front_page_id);

/* v3.11.3: 数字员工区由后台 WooCommerce「精选商品」控制，与解决方案区独立。 */
$products_limit = function_exists('get_field') ? (int) get_field('fp_products_count', $front_page_id) : 0;
$products = hireai_get_featured_employee_products([
    'language' => $lang,
    'per_page' => $products_limit > 0 ? $products_limit : 8,
]);

/* v3.11.4: 精选商品未配置时保持 v3.11.2 首页区块可见，中英文各用一套本地兜底卡。 */
if (empty($products)) {
    $products = [];
    $employee_defaults = [
        1 => [
            'title' => $is_en ? 'Aurelian Prime' : 'Aurelian Prime',
            'desc'  => $is_en ? 'Elite female digital avatar' : '精英女性数字分身',
            'badge' => $is_en ? 'Edition 01/50' : '限量 01/50',
            'image' => 'home/product-prime.png',
        ],
        2 => [
            'title' => $is_en ? 'Aurelian Executive' : 'Aurelian Executive',
            'desc'  => $is_en ? 'Authority & diplomacy protocol' : '权威与外交协议',
            'badge' => $is_en ? 'Executive Series' : 'Executive Series',
            'image' => 'home/product-exec.png',
        ],
        3 => [
            'title' => $is_en ? 'Neural Sales Core' : 'Neural Sales Core',
            'desc'  => $is_en ? 'Enterprise-grade AI optimization' : '企业级AI优化',
            'badge' => $is_en ? 'Neural Series' : 'Neural Series',
            'image' => 'home/product-neural.png',
        ],
    ];

    foreach ($employee_defaults as $index => $default) {
        $products[] = [
            'title' => hireai_field_lang("fp_prod{$index}_title", $lang, $default['title'], $front_page_id),
            'desc'  => hireai_field_lang("fp_prod{$index}_desc", $lang, $default['desc'], $front_page_id),
            'badge' => hireai_field_lang("fp_prod{$index}_badge", $lang, $default['badge'], $front_page_id),
            'img'   => hireai_image("fp_prod{$index}_image", $home . '/assets/img/' . $default['image'], $front_page_id),
            'url'   => function_exists('hireai_resolve_employee_url')
                ? hireai_resolve_employee_url($index - 1, $prod_explore_url)
                : $prod_explore_url,
            'btn'   => hireai_field_lang("fp_prod{$index}_btn", $lang, $is_en ? 'Explore More' : '探索更多', $front_page_id),
        ];
    }

    $products[] = [
        'title' => $is_en ? 'Custom Consultation' : '定制咨询',
        'desc'  => $is_en ? 'Speak with our team to design a bespoke digital employee.' : '与我们的团队对话，定制专属数字员工。',
        'badge' => $is_en ? 'Bespoke Service' : '定制服务',
        'img'   => $home . '/assets/img/defaults/employee-5.jpg',
        'url'   => $hero_cta_2_url,
        'btn'   => $is_en ? 'Book Consultation' : '预约咨询',
    ];
}

$products = array_map(function ($product) use ($is_en) {
    $product['btn'] = $is_en ? 'Explore More' : '探索更多';
    return $product;
}, $products);


/* ═══════════════════════════════════════════════════════════════════════
   v3.6.0 改造 — 5 个 Section 渲染逻辑升级
   策略:
     - 解决方案：仅读取当前语言 sol_featured_on_home=1 商品，实际数量直出
     - 其他模块：沿用既有 ACF / 内容 fallback 链
   安全约束:
     - 不删除 / 不改 hireai_* helper 函数
     - 不改 typography / spacing / radius / container 数值
     - 商品读取统一走 hireai_get_solution_products()
   ═══════════════════════════════════════════════════════════════════════ */

/* ---------- 读 NEW ACF 双语 helper（直接读 _zh / _en 后缀名） ---------- */
$hireai_v360 = function ($base, $lang_code) use ($front_page_id) {
    $sfx = ($lang_code === 'en') ? '_en' : '_zh';
    if (!function_exists('get_field') || !$front_page_id) return '';
    $v = get_field($base . $sfx, $front_page_id);
    if (is_array($v)) return isset($v['url']) ? (string) $v['url'] : '';
    return is_string($v) ? $v : '';
};
$hireai_v360_url = function ($base, $lang_code = null) use ($lang, $front_page_id) {
    if (!function_exists('get_field') || !$front_page_id) return '';
    $lang_code = $lang_code ?: $lang;
    $sfx = ($lang_code === 'en') ? '_en' : '_zh';
    $value = get_field($base . $sfx, $front_page_id);
    if (!is_string($value) || trim($value) === '') {
        $value = get_field($base, $front_page_id);
    }
    return is_string($value) && trim($value) !== '' ? hireai_localized_url(trim($value), $lang_code) : '';
};

$hireai_v360_image_url = function ($base, $lang_code = null) use ($lang, $front_page_id) {
    if (!function_exists('get_field') || !$front_page_id) return '';
    $lang_code = $lang_code ?: $lang;
    $sfx = ($lang_code === 'en') ? '_en' : '_zh';
    $v = get_field($base . $sfx, $front_page_id);
    if (is_null($v) || $v === '' || $v === false) {
        $v = get_field($base, $front_page_id);
    }
    if (is_array($v) && !empty($v['url'])) return (string) $v['url'];
    if (is_string($v)) return $v;
    return '';
};

/* ═══════════════════════════════════════════════════════════════════════
   ② 解决方案区域 — 当前语言 WC 商品（1-4 张，实际数量直出）
   ═══════════════════════════════════════════════════════════════════════ */
$sol_section_kicker = $hireai_v360('fp_solutions_kicker',       $lang) ?: hireai_field_lang('fp_solutions_kicker',       $lang, $is_en ? 'Industry Empowerment' : '行业赋能', $front_page_id);
$sol_section_title  = $hireai_v360('fp_solutions_title',        $lang) ?: hireai_field_lang('fp_solutions_title',        $lang, $is_en ? 'AI Solutions' : 'AI 解决方案', $front_page_id);
$sol_section_sub    = $hireai_v360('fp_solutions_subtitle',     $lang) ?: hireai_field_lang('fp_solutions_subtitle',     $lang, $is_en ? 'Bespoke intelligent solutions across industries.' : '面向多元行业，打造量身定制的智能解决方案。', $front_page_id);
$sol_explore_label  = $hireai_v360('fp_solutions_explore_label',$lang) ?: hireai_field_lang('fp_solutions_explore_label',$lang, $is_en ? 'Explore More' : '探索更多', $front_page_id);
$sol_explore_url    = $hireai_v360_url('fp_solutions_explore_url') ?: hireai_field('fp_solutions_explore_url', home_url('/ai-solutions/'), $front_page_id);

$solution_result = hireai_get_solution_products_result([
    'featured' => true,
    'per_page' => 4,
    'language' => $lang,
]);
$solutions = $solution_result['items'];

/* v3.11.5: 精选方案不足 4 个时，用当前语言最新已发布商品补足；完全没有精选则维持不渲染。 */
if (!empty($solutions) && count($solutions) < 4) {
    $solution_ids = wp_list_pluck($solutions, 'id');
    $latest_solutions = hireai_get_solution_products_result([
        'featured' => false,
        'per_page' => 12,
        'language' => $lang,
    ])['items'];

    foreach ($latest_solutions as $latest_solution) {
        if (count($solutions) >= 4) {
            break;
        }
        if (in_array((int) $latest_solution['id'], array_map('intval', $solution_ids), true)) {
            continue;
        }
        $solutions[] = $latest_solution;
        $solution_ids[] = (int) $latest_solution['id'];
    }
}

/* v3.11.4: 每张方案卡使用本地封面兜底，避免缺少特色图时退化成灰色空块。 */
$solution_fallback_images = [
    $home . '/assets/img/defaults/solution-4.jpg',
    $home . '/assets/img/defaults/solution-2.jpg',
    $home . '/assets/img/defaults/solution-1.jpg',
    $home . '/assets/img/defaults/solution-3.jpg',
];

foreach ($solutions as $solution_index => $solution_product) {
    $solutions[$solution_index]['img'] = !empty($solution_product['image'])
        ? $solution_product['image']
        : $solution_fallback_images[$solution_index % 4];
}
/* ═══════════════════════════════════════════════════════════════════════
   ③ Cases & Insights — 双层 fallback (NEW ACF repeater → 旧 ACF + 默认值)
   ═══════════════════════════════════════════════════════════════════════ */
$cases_kicker        = $hireai_v360('fp_cases_kicker',        $lang) ?: hireai_field_lang('fp_cases_kicker',        $lang, $is_en ? 'Frontier Vision' : '前沿视野', $front_page_id);
$cases_title         = $hireai_v360('fp_cases_title',         $lang) ?: hireai_field_lang('fp_cases_title',         $lang, $is_en ? 'Cases & Insights' : '案例 & 洞察', $front_page_id);
$cases_sub           = $hireai_v360('fp_cases_subtitle',      $lang) ?: hireai_field_lang('fp_cases_subtitle',      $lang, $is_en ? 'See how digital employees transform operations.' : '见证数字员工如何改变企业的运营方式。', $front_page_id);
$cases_explore_label = $hireai_v360('fp_cases_explore_label', $lang) ?: hireai_field_lang('fp_cases_explore_label', $lang, $is_en ? 'Explore More' : '探索更多', $front_page_id);
$cases_explore_url   = $hireai_v360_url('fp_cases_explore_url') ?: hireai_field('fp_cases_explore_url', home_url('/cases-insights/'), $front_page_id);

/* Major case — NEW ACF 优先 */
$major_title_v360 = $hireai_v360('fp_case_major_title', $lang);
$major_label_v360 = $hireai_v360('fp_case_major_label', $lang);
$major_desc_v360  = $hireai_v360('fp_case_major_desc',  $lang);
$major_image_v360 = $hireai_v360_image_url('fp_case_major_image');

$major_case = [
    'label' => $major_label_v360 ?: hireai_field_lang('fp_case_major_label', $lang, $is_en ? 'CASE STUDY' : '案例研究', $front_page_id),
    'title' => $major_title_v360 ?: hireai_field_lang('fp_case_major_title', $lang, $is_en ? 'Aurelian Prime in Private Banking' : 'Aurelian Prime 在私人银行的应用', $front_page_id),
    'desc'  => $major_desc_v360  ?: hireai_field_lang('fp_case_major_desc',  $lang, $is_en ? 'Learn how our top digital employee boosts retention and satisfaction for high-net-worth clients.' : '了解我们的顶级数字员工如何提升高净值客户的留存率与满意度。', $front_page_id),
    'img'   => $major_image_v360 ?: hireai_image('fp_case_major_image', $home . '/assets/img/defaults/case-1.jpg', $front_page_id),
    'url'   => hireai_field('fp_case_major_url', home_url('/cases-insights/'), $front_page_id),
];

/* Minor cases — Layer 1: NEW ACF repeater; Layer 2: 旧 fp_case1/2 + 默认值 */
$side_cases = [];
$_v360_minor = function_exists('get_field') && $front_page_id ? get_field('fp_cases_minor_repeater', $front_page_id) : null;
if (is_array($_v360_minor) && !empty($_v360_minor)) {
    foreach (array_slice($_v360_minor, 0, 6) as $_row) {
        if (!is_array($_row)) continue;
        $_t = $is_en ? ($_row['title_en'] ?? '') : ($_row['title_zh'] ?? '');
        if (!is_string($_t) || $_t === '') continue;
        $_img = '';
        if (!empty($_row['image']) && is_array($_row['image']) && !empty($_row['image']['url'])) {
            $_img = (string) $_row['image']['url'];
        }
        $side_cases[] = [
            'tag'   => $is_en ? ($_row['tag_en'] ?? '') : ($_row['tag_zh'] ?? ''),
            'title' => $_t,
            'desc'  => $is_en ? ($_row['desc_en'] ?? '') : ($_row['desc_zh'] ?? ''),
            'img'   => $_img,
            'url'   => !empty($_row['url']) ? hireai_localized_url((string) $_row['url']) : hireai_localized_url(home_url('/cases-insights/')),
        ];
    }
}
if (count($side_cases) < 2) {
    foreach ([1, 2] as $i) {
        if (count($side_cases) >= 2) break;
        $dflt = [
            1 => ['tag' => '案例研究', 'title' => '电商视觉革命：转化率提升 55%',    'desc' => '重塑线上购物体验，结合虚拟试穿与个性化推荐带来的商业增长。',           'img' => 'case-2'],
            2 => ['tag' => '深度洞察', 'title' => '"未来不再仅仅是代码，更是交响乐。"', 'desc' => '探讨数字人性化的趋势，以及我们在构建有温度的 AI 方面的思考与实践。', 'img' => 'case-3'],
        ];
        $d = $dflt[$i];
        $side_cases[] = [
            'tag'   => hireai_field_lang("fp_case{$i}_tag",   $lang, $d['tag'], $front_page_id),
            'title' => hireai_field_lang("fp_case{$i}_title", $lang, $d['title'], $front_page_id),
            'desc'  => hireai_field_lang("fp_case{$i}_desc",  $lang, $d['desc'], $front_page_id),
            'img'   => hireai_image("fp_case{$i}_image", $home . '/assets/img/defaults/' . $d['img'] . '.jpg', $front_page_id),
            'url'   => hireai_field("fp_case{$i}_url", home_url('/cases-insights/'), $front_page_id),
        ];
    }
}

/* ═══════════════════════════════════════════════════════════════════════
   ④ FAQ — 双层 fallback (NEW ACF repeater → 旧 fp_faq1..3 + 默认值)
   ═══════════════════════════════════════════════════════════════════════ */
$faq_kicker        = $hireai_v360('fp_faq_kicker',        $lang) ?: hireai_field_lang('fp_faq_kicker',        $lang, $is_en ? 'FAQ' : '常见问题', $front_page_id);
$faq_title         = $hireai_v360('fp_faq_title',         $lang) ?: hireai_field_lang('fp_faq_title',         $lang, $is_en ? 'Answers to your questions about digital employees.' : '解答关于数字员工的疑虑，开启智能新纪元。', $front_page_id);
/* v3.7.5: 删除死变量 $faq_sub（fp_faq_subtitle 从未被渲染，FAQ 副标题走 fp_faq_title） */
$faq_explore_label = $hireai_v360('fp_faq_explore_label', $lang) ?: hireai_field_lang('fp_faq_explore_label', $lang, $is_en ? 'Explore More' : '探索更多', $front_page_id);
$faq_explore_url   = $hireai_v360_url('fp_faq_explore_url') ?: hireai_field('fp_faq_explore_url', home_url('/faq/'), $front_page_id);

$faq_items = [];
$_v360_faq = function_exists('get_field') && $front_page_id ? get_field('fp_faq_repeater', $front_page_id) : null;
if (is_array($_v360_faq) && !empty($_v360_faq)) {
    foreach (array_slice($_v360_faq, 0, 10) as $_row) {
        if (!is_array($_row)) continue;
        $_q = $is_en ? ($_row['question_en'] ?? '') : ($_row['question_zh'] ?? '');
        if (!is_string($_q) || trim($_q) === '') continue;
        $faq_items[] = [
            'q' => $_q,
            'a' => $is_en ? ($_row['answer_en'] ?? '') : ($_row['answer_zh'] ?? ''),
        ];
    }
}
if (count($faq_items) < 1) {
    foreach ([1, 2, 3] as $i) {
        if (count($faq_items) >= 3) break;
        $dflt = [
            1 => ['q' => '定制一位数字员工需要多长时间？', 'a' => '这取决于定制的复杂程度。基础模型微调通常需要 2–4 周，而完全定制化（包括独特外观建模、声音克隆和深度行业知识库训练）可能需要 8–12 周。'],
            2 => ['q' => '数字员工的知识库可以实时更新吗？', 'a' => '是的，我们的系统支持通过 API 进行实时知识库更新。您可以随时添加新的产品信息、政策变更或行业动态，确保数字员工始终掌握最新资讯。'],
            3 => ['q' => '如何保障数据隐私与安全？',         'a' => '我们采用企业级加密标准，所有交互数据均在本地或专属私有云中处理。我们严格遵守全球数据保护法规，确保您的商业机密与客户隐私绝对安全。'],
        ];
        $d = $dflt[$i];
        $faq_items[] = [
            'q' => hireai_field_lang("fp_faq{$i}_q", $lang, $d['q'], $front_page_id),
            'a' => hireai_field_lang("fp_faq{$i}_a", $lang, $d['a'], $front_page_id),
        ];
    }
}

/* ═══════════════════════════════════════════════════════════════════════
   ⑤ CTA — NEW ACF 优先 → 旧字段 fallback
   ═══════════════════════════════════════════════════════════════════════ */
$cta_kicker    = $hireai_v360('fp_cta_kicker',        $lang) ?: ($is_en ? 'NEXT STEP' : '开启旅程');
$cta_title     = $hireai_v360('fp_cta_title',         $lang) ?: hireai_field_lang('fp_cta_title',    $lang, $is_en ? 'Begin Your AI Hiring Journey' : '开启您的 AI 雇佣之旅', $front_page_id);
$cta_desc      = $hireai_v360('fp_cta_subtitle',      $lang) ?: hireai_field_lang('fp_cta_desc',     $lang, $is_en ? 'Speak with our team and craft a digital workforce made for you.' : '与我们的团队对话，打造专属您的数字员工阵容。', $front_page_id);
$cta_btn_title = $hireai_v360('fp_cta_button_label',  $lang) ?: hireai_field_lang('fp_cta_btn_title',$lang, $is_en ? 'Contact Us' : '联系我们', $front_page_id);
$cta_btn_url   = $hireai_v360_url('fp_cta_button_url') ?: hireai_field('fp_cta_btn_url', home_url('/contact/'), $front_page_id);
$cta_btn_2_title = hireai_field_lang('fp_cta_secondary_button_label', $lang, $is_en ? 'Book Consultation' : '预约咨询', $front_page_id);
$cta_btn_2_url   = hireai_field('fp_cta_secondary_button_url', home_url('/contact/'), $front_page_id);


/* i18n text */
$t = [
    'expand'   => $is_en ? 'Expand answer'  : '展开答案',
    'collapse' => $is_en ? 'Collapse answer' : '收起答案',
];
?>

<!-- ═══════════════════════════════════════════════════════════════════════
     页面专有样式（hireai-fp-* 命名空间；对齐设计基准）
     ═══════════════════════════════════════════════════════════════════════ -->
<style>
/* ── Reset / Base ──────────────────────────────────────────────────── */
.hireai-fp{box-sizing:border-box;margin:0;padding:0;background:var(--surface,#faf9f9);color:#1a1c1c;font-family:'Inter','Noto Sans SC',-apple-system,BlinkMacSystemFont,sans-serif;line-height:1.6;overflow-x:hidden;}
.hireai-fp *,.hireai-fp *::before,.hireai-fp *::after{box-sizing:border-box;}
.hireai-fp img{display:block;max-width:100%;height:auto;}
.hireai-fp-hero__bg img{height:100%;}
.hireai-fp a:not([class*="hireai-fp__btn"]):not(.hireai-fp-products__explore){text-decoration:none;color:inherit;}
.hireai-fp button{font-family:inherit;cursor:pointer;border:0;background:none;}

/* ── Tokens（严格对齐 DESIGN.md） ─────────────────────────────────── */
.hireai-fp{
    --gold:#775a19; --gold-light:#e9c176; --gold-pale:#ffdea5;
    --gold-line:rgba(119,90,25,.18);
    --ink:#1a1c1c; --ink-muted:#444748;
    --line:rgba(196,199,199,.30);
    --surface:#faf9f9; --surface-low:#f4f3f3; --surface-white:#fff;
    --side:clamp(20px,5vw,80px);
    --max:1440px;
    --f-display:'Playfair Display','Noto Serif SC',Georgia,serif;
    --f-headline:'Playfair Display','Noto Serif SC',Georgia,serif;
    --f-body:'Inter','Noto Sans SC',-apple-system,sans-serif;
    --f-label:'Inter','Noto Sans SC',-apple-system,sans-serif;
    /* Typography tokens（严格对齐 DESIGN.md） */
    --sz-d:clamp(40px,6vw,72px);            /* display-lg  700 1.1 */
    --sz-h1:clamp(32px,4.5vw,48px);         /* headline-lg 600 1.2 */
    --sz-h2:clamp(28px,3.5vw,42px);         /* mid         600 1.2 */
    --sz-h3:clamp(20px,2vw,32px);           /* headline-md 500 1.3 */
    --sz-bl:18px; --sz-bm:16px;             /* body-lg / body-md  400 1.6 */
    --sz-lm:14px; --sz-ls:12px;             /* label-md (600 .1em) / label-sm (500 .05em); v3.3.0 sz-lm stays = label-md spec §2 */
    /* Spacing tokens（严格对齐 DESIGN.md） */
    --gap:clamp(60px,8vw,120px);            /* section-gap */
    --gutter:24px;
    /* Buttons */
    --btn-pad-y:16px; --btn-pad-x:clamp(28px,4vw,48px);
}

/* ── 通用：金质渐变斜体（hero accent 用） ──────────────────────── */
.hireai-fp__gold{
    color:#775a19;
    font-style:italic;font-weight:400;
    display:inline-block;
}

/* ── 通用：kicker 小标签（label-sm 严格对齐 DESIGN.md） ────────── */
.hireai-fp__kicker{
    display:inline-block;font-family:var(--f-label);
    font-size:var(--sz-ls);font-weight:500;line-height:1.2;
    letter-spacing:.05em;text-transform:uppercase;color:var(--gold);
    margin:0 0 16px;
}

/* ── 通用：section padding（120px） ─────────────────────────────── */
.hireai-fp__section{
    padding-block:var(--gap);
    padding-inline:var(--side);
    max-width:var(--max);
    margin-inline:auto;
}

/* ── 通用：按钮 pill（label-md 严格对齐 DESIGN.md） ─────────────── */
/* v3.0.6: min-height 56px (hireaipeople.txt 主按钮); font uses Montserrat (English body spec) */
.hireai-fp__btn{
    display:inline-flex;align-items:center;justify-content:center;
    gap:8px;cursor:pointer;text-align:center;white-space:nowrap;
    font-family:var(--f-label);font-size:var(--sz-lm);font-weight:600;
    line-height:1.2;letter-spacing:.1em;text-transform:uppercase;
    min-height:56px;
    padding:var(--btn-pad-y) var(--btn-pad-x);
    border-radius:9999px;border:1px solid transparent;
    transition:all .3s ease;
}
.hireai-fp__btn.hireai-fp__btn--primary, .hireai-fp__btn--primary.hireai-fp__btn--primary{background:#000;color:#fff!important;border-color:#000;}
.hireai-fp__btn.hireai-fp__btn--primary:hover, .hireai-fp__btn--primary.hireai-fp__btn--primary:hover{box-shadow:0 0 24px rgba(119,90,25,.45);}
.hireai-fp__btn.hireai-fp__btn--outline, .hireai-fp__btn--outline.hireai-fp__btn--outline{background:rgba(255,255,255,.7);color:#775a19!important;border:1px solid rgba(119,90,25,.45);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);}
.hireai-fp__btn.hireai-fp__btn--outline:hover, .hireai-fp__btn--outline.hireai-fp__btn--outline:hover{background:rgba(119,90,25,.12);}
.hireai-fp__btn.hireai-fp__btn--ghost, .hireai-fp__btn--ghost.hireai-fp__btn--ghost{background:transparent;color:#1a1c1c!important;border:1px solid #1a1c1c;}
.hireai-fp__btn.hireai-fp__btn--ghost:hover, .hireai-fp__btn--ghost.hireai-fp__btn--ghost:hover{background:#1a1c1c;color:#fff;}
.hireai-fp__btn.hireai-fp__btn--gold, .hireai-fp__btn--gold.hireai-fp__btn--gold{background:#775a19;color:#fff!important;border:1px solid #e9c176;}
.hireai-fp__btn.hireai-fp__btn--gold:hover, .hireai-fp__btn--gold.hireai-fp__btn--gold:hover{box-shadow:0 0 32px rgba(233,193,118,.45);transform:translateY(-1px);}

/* ═══════════════════════════════════════════════════════════════════════
   1) HERO  ── 上图下文 (图片区块 / 白底文字区 / 金色衬线标题)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-hero{display:flex;flex-direction:column;width:100%;height:auto;overflow:visible;background:var(--surface-white,#fff);}
.hireai-fp-hero__bg{position:relative;left:auto;top:auto;width:100%;height:min(52vw,680px);overflow:hidden;}
.hireai-fp-hero__bg img{display:block;width:100%;height:100%;object-fit:cover;object-position:center bottom;}
.hireai-fp-hero__shade{position:absolute;inset:0;z-index:1;background:linear-gradient(180deg,rgba(23,25,27,.12) 0%,rgba(23,25,27,0) 24%,rgba(23,25,27,0) 78%,rgba(23,25,27,.22) 100%);pointer-events:none;}
.hireai-fp-hero__inner,
html body .hireai-fp .hireai-fp-hero .hireai-fp-hero__inner{position:relative;inset:auto;z-index:2;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;text-align:center;width:min(100%,920px);min-width:min(100%,920px);margin-inline:auto;padding:clamp(44px,6vw,84px) var(--side) clamp(60px,7vw,96px);background:#fff;writing-mode:horizontal-tb;}
.hireai-fp-hero__inner > *,
html body .hireai-fp .hireai-fp-hero .hireai-fp-hero__inner > *{min-width:0;max-width:100%;}
.hireai-fp-hero__kicker{margin-bottom:24px;}
.hireai-fp-hero__title{font-family:var(--f-display);font-size:var(--sz-d);font-weight:700;line-height:1.12;letter-spacing:-.02em;color:var(--gold);margin:0 0 24px;}
.hireai-fp-hero__title,
html body .hireai-fp .hireai-fp-hero .hireai-fp-hero__title{display:grid;width:100%;max-width:none;justify-items:center;}
.hireai-fp-hero__title-line,
html body .hireai-fp .hireai-fp-hero .hireai-fp-hero__title-line{display:block;width:100%;max-width:100%;overflow-wrap:break-word;writing-mode:horizontal-tb;}
.hireai-fp-hero__subtitle{font-family:var(--f-body);font-size:var(--sz-bl);font-weight:400;line-height:1.6;color:rgba(26,28,28,.80);margin:0 auto 36px;width:min(100%,680px);max-width:680px;overflow-wrap:break-word;}
/* v3.3.0: spec §4 Hero 副标题 = body-lg italic (en only); zh keeps upright */
html[lang="en"] .hireai-fp-hero__subtitle{font-style:italic;}
html[lang^="zh"] .hireai-fp-hero__title{letter-spacing:0;}
.hireai-fp-hero__actions{display:flex;flex-wrap:wrap;justify-content:center;gap:16px;}
/* ═══════════════════════════════════════════════════════════════════════
   2) PRODUCTS  ── 设计基准 AI 数字员工 (3-col 卡片 / aspect 3:4)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-products{background:var(--surface-low,#f4f3f3);padding-block:var(--gap);padding-inline:var(--side);color:#1a1c1c;}
.hireai-fp-products__inner{max-width:var(--max);margin:0 auto;}
.hireai-fp-products__head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:clamp(40px,5vw,72px);padding-bottom:48px;border-bottom:1px solid var(--line);}
.hireai-fp-products__head-text{flex:1;min-width:280px;}
.hireai-fp-products__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0 0 12px;}
.hireai-fp-products__title .hireai-fp__gold{font-style:italic;font-weight:400;}
.hireai-fp-products__sub{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:0 0 20px;max-width:560px;}
.hireai-fp-products__explore{display:inline-flex;align-items:center;font-family:var(--f-label);font-size:var(--sz-lm);font-weight:600;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:#000;padding-bottom:4px;border-bottom:1px solid #000;transition:color .3s,border-color .3s;}
.hireai-fp-products__explore:hover{color:var(--gold);border-bottom-color:var(--gold);}
.hireai-fp-products__grid{display:grid;grid-template-columns:1fr;gap:var(--gutter);}
@media(min-width:768px){.hireai-fp-products__grid{grid-template-columns:repeat(2,1fr);}}
@media(min-width:1024px){.hireai-fp-products__grid{grid-template-columns:repeat(3,1fr);}}

.hireai-fp-product{display:flex;flex-direction:column;}
.hireai-fp-product__media{position:relative;aspect-ratio:3/4;overflow:hidden;margin-bottom:24px;background:#eee;border:1px solid var(--line);transition:border-color .5s;}
.hireai-fp-product__media:hover{border-color:var(--gold-light);}
.hireai-fp-product__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-product:hover .hireai-fp-product__img{transform:scale(1.05);}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.18em */
.hireai-fp-product__badge{position:absolute;top:16px;right:16px;background:rgba(255,255,255,.80);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);padding:6px 14px;border-radius:9999px;border:1px solid rgba(0,0,0,.06);font-family:var(--f-label);font-size:11px;font-weight:700;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:#000;white-space:nowrap;}
.hireai-fp-product__name{font-family:var(--f-headline);font-size:var(--sz-h3);font-weight:500;line-height:1.3;color:#000;margin:0 0 8px;}
.hireai-fp-product__desc{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:0 0 20px;}
.hireai-fp-product__btn{width:100%;margin-top:auto;}

/* ═══════════════════════════════════════════════════════════════════════
   3) SOLUTIONS  ── 设计基准 AI 解决方案 (2x2 aspect-video 卡片)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-solutions{background:var(--surface,#faf9f9);padding-block:var(--gap);padding-inline:var(--side);}
.hireai-fp-solutions__inner{max-width:var(--max);margin:0 auto;}
.hireai-fp-solutions__head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:clamp(40px,5vw,64px);}
.hireai-fp-solutions__head-text{flex:1;min-width:280px;max-width:640px;}
.hireai-fp-solutions__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0;}
.hireai-fp-solutions__title .hireai-fp__gold{font-style:italic;font-weight:400;}
.hireai-fp-solutions__sub{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:12px 0 0;max-width:560px;}

.hireai-fp-solutions__grid{display:grid;grid-template-columns:1fr;gap:24px;margin-bottom:48px;}
@media(min-width:768px){.hireai-fp-solutions__grid{grid-template-columns:repeat(2,1fr);gap:clamp(24px,3vw,48px);}}

.hireai-fp-sol{position:relative;display:block;aspect-ratio:16/9;overflow:hidden;border:1px solid var(--line);transition:border-color .5s;}
.hireai-fp-sol:hover{border-color:var(--gold-light);}
.hireai-fp-sol__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-sol:hover .hireai-fp-sol__img{transform:scale(1.05);}
.hireai-fp-sol__placeholder{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:#e8e8e8;color:rgba(119,90,25,.45);}
.hireai-fp-sol__placeholder svg{width:64px;height:64px;}
.hireai-fp-sol__overlay{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:32px;color:#fff;background:linear-gradient(180deg,transparent 0%,rgba(0,0,0,.20) 40%,rgba(0,0,0,.85) 100%);}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.2em */
.hireai-fp-sol__tag{display:inline-block;font-family:var(--f-label);font-size:11px;font-weight:700;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.85);margin-bottom:10px;}
.hireai-fp-sol__title{font-family:var(--f-headline);font-size:clamp(20px,1.8vw,26px);font-weight:500;line-height:1.3;color:#fff;margin:0 0 8px;}
.hireai-fp-sol__desc{font-family:var(--f-body);font-size:14px;line-height:1.6;color:rgba(255,255,255,.85);margin:0;max-width:36ch;}
.hireai-fp-sol__price{display:inline-flex;align-items:center;gap:8px;margin-top:10px;font-family:var(--f-label);font-size:12px;font-weight:600;line-height:1.3;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-light,#e9c176);}
.hireai-fp-sol__price-value del{color:rgba(233,193,118,.62);}
.hireai-fp-sol__price-value ins{text-decoration:none;color:var(--gold-light,#e9c176);}
.hireai-fp-solutions__cta{text-align:center;}

/* ═══════════════════════════════════════════════════════════════════════
   4) CASES  ── 设计基准 案例 & 洞察 (12-col grid, 8+4)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-cases{background:var(--surface-white,#fff);padding-block:var(--gap);padding-inline:var(--side);border-top:1px solid var(--line);}
.hireai-fp-cases__inner{max-width:var(--max);margin:0 auto;}
.hireai-fp-cases__head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:clamp(40px,5vw,64px);padding-bottom:48px;border-bottom:1px solid var(--line);}
.hireai-fp-cases__head-text{flex:1;min-width:280px;max-width:640px;}
.hireai-fp-cases__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0;}
.hireai-fp-cases__title .hireai-fp__gold{font-style:italic;font-weight:400;}
.hireai-fp-cases__sub{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:12px 0 0;}
.hireai-fp-cases__grid{display:grid;grid-template-columns:1fr;gap:clamp(28px,3vw,40px);}
@media(min-width:1024px){.hireai-fp-cases__grid{grid-template-columns:repeat(12,1fr);}}

.hireai-fp-case-major{grid-column:span 1;display:flex;flex-direction:column;cursor:pointer;transition:transform .4s;}
@media(min-width:1024px){.hireai-fp-case-major{grid-column:span 8;}}
.hireai-fp-case-major__media{aspect-ratio:16/9;overflow:hidden;border:1px solid var(--line);margin-bottom:24px;}
.hireai-fp-case-major__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-case-major:hover .hireai-fp-case-major__img{transform:scale(1.05);}
.hireai-fp-case-major__body{padding:0 4px;}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.2em */
.hireai-fp-case-major__tag{display:inline-block;font-family:var(--f-label);font-size:11px;font-weight:700;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:10px;}
.hireai-fp-case-major__title{font-family:var(--f-headline);font-size:clamp(20px,2vw,var(--sz-h3));font-weight:500;line-height:1.3;color:#000;margin:0 0 12px;transition:color .3s;}
.hireai-fp-case-major:hover .hireai-fp-case-major__title{color:var(--gold);}
.hireai-fp-case-major__desc{font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.6;color:var(--ink-muted);margin:0;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}

.hireai-fp-cases__side{grid-column:span 1;display:flex;flex-direction:column;gap:clamp(28px,3vw,40px);}
@media(min-width:1024px){.hireai-fp-cases__side{grid-column:span 4;}}

.hireai-fp-case-side{display:flex;flex-direction:column;cursor:pointer;}
.hireai-fp-case-side__media{aspect-ratio:16/9;overflow:hidden;border:1px solid var(--line);margin-bottom:16px;background:#eee;}
.hireai-fp-case-side__img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease-out;}
.hireai-fp-case-side:hover .hireai-fp-case-side__img{transform:scale(1.05);}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.2em */
.hireai-fp-case-side__tag{display:inline-block;font-family:var(--f-label);font-size:11px;font-weight:700;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;}
.hireai-fp-case-side__title{font-family:var(--f-headline);font-size:clamp(17px,1.6vw,21px);font-weight:500;line-height:1.3;color:#000;margin:0 0 8px;transition:color .3s;}
.hireai-fp-case-side:hover .hireai-fp-case-side__title{color:var(--gold);}
.hireai-fp-case-side__desc{font-family:var(--f-body);font-size:14px;line-height:1.6;color:var(--ink-muted);margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}

.hireai-fp-cases__cta{text-align:center;margin-top:clamp(40px,5vw,72px);}

/* ═══════════════════════════════════════════════════════════════════════
   5) FAQ  ── 设计基准 常见问题 (3 项手风琴)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-faq{background:var(--surface,#faf9f9);padding-block:var(--gap);padding-inline:var(--side);border-top:1px solid var(--line);text-align:center;}
.hireai-fp-faq__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#000;margin:0;}
.hireai-fp-faq__title .hireai-fp__gold{font-style:italic;font-weight:400;}
.hireai-fp-faq__sub{font-family:var(--f-body);font-size:var(--sz-bl);line-height:1.6;color:var(--ink-muted);margin:16px auto 0;max-width:640px;}
.hireai-fp-faq__list{max-width:780px;margin:clamp(40px,5vw,72px) auto 0;text-align:left;}
.hireai-fp-faq-item{border-bottom:1px solid var(--line);}
.hireai-fp-faq-item__header{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:20px 0;cursor:pointer;transition:color .3s;}
.hireai-fp-faq-item__header:hover .hireai-fp-faq-item__q,
.hireai-fp-faq-item.is-open .hireai-fp-faq-item__q{color:var(--gold);}
.hireai-fp-faq-item__q{flex:1;font-family:var(--f-headline);font-size:clamp(18px,1.6vw,22px);font-weight:500;line-height:1.3;color:#000;margin:0;text-align:left;transition:color .3s;}
.hireai-fp-faq-item__icon{flex-shrink:0;width:24px;height:24px;color:var(--ink-muted);transition:transform .3s;}
.hireai-fp-faq-item.is-open .hireai-fp-faq-item__icon{transform:rotate(45deg);}
.hireai-fp-faq-item__body{max-height:0;overflow:hidden;transition:max-height .35s ease-out;}
.hireai-fp-faq-item.is-open .hireai-fp-faq-item__body{max-height:640px;}
.hireai-fp-faq-item__answer{padding-bottom:20px;font-family:var(--f-body);font-size:var(--sz-bm);line-height:1.7;color:rgba(68,71,72,.85);}
.hireai-fp-faq__cta{text-align:center;margin-top:clamp(40px,5vw,72px);}

/* ═══════════════════════════════════════════════════════════════════════
   6) CTA BAND  ── 设计基准 大 CTA (深色 + 金色 radial glow)
   ═══════════════════════════════════════════════════════════════════════ */
.hireai-fp-cta{position:relative;background:#000;color:#fff;padding:clamp(80px,10vw,140px) var(--side);text-align:center;overflow:hidden;}
.hireai-fp-cta__glow{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:clamp(400px,60vw,720px);aspect-ratio:1/1;background:radial-gradient(circle,rgba(119,90,25,.30),transparent 65%);pointer-events:none;}
.hireai-fp-cta__inner{position:relative;max-width:760px;margin:0 auto;}
/* v3.0.6: letter-spacing 0.1em (DESIGN.md label-md letterSpacing) — was 0.3em (way too wide) */
.hireai-fp-cta__kicker{display:inline-block;font-family:var(--f-label);font-size:var(--sz-ls);font-weight:500;line-height:1.2;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-light);margin-bottom:20px;}
.hireai-fp-cta__title{font-family:var(--f-headline);font-size:var(--sz-h1);font-weight:600;line-height:1.2;color:#fff;margin:0 0 20px;}
.hireai-fp-cta__desc{font-family:var(--f-body);font-size:var(--sz-bl);line-height:1.7;color:rgba(255,255,255,.7);margin:0 auto 40px;max-width:640px;}
.hireai-fp-cta__actions{display:inline-flex;flex-wrap:wrap;justify-content:center;gap:16px;}
.hireai-fp-cta__actions .hireai-fp__btn--outline{color:#fff;border-color:rgba(255,255,255,.35);background:rgba(255,255,255,.06);}
.hireai-fp-cta__actions .hireai-fp__btn--outline:hover{background:rgba(255,255,255,.14);}

/* ═══════════════════════════════════════════════════════════════════════
   Responsive fine-tune
   ═══════════════════════════════════════════════════════════════════════ */
@media(max-width:767px){
    .hireai-fp-hero__bg{height:min(62svh,520px);}
    .hireai-fp-hero__bg img{object-position:center 26%;}
    .hireai-fp-hero__inner,
    html body .hireai-fp .hireai-fp-hero .hireai-fp-hero__inner{position:relative;inset:auto;width:100%;min-width:0;padding:52px 20px 68px;}
    .hireai-fp-hero__kicker{margin-bottom:16px;}
    .hireai-fp-hero__title{font-size:clamp(32px,8vw,48px);}
    .hireai-fp-hero__subtitle{max-width:100%;margin-bottom:24px;color:rgba(26,28,28,.80);}
    .hireai-fp-hero__actions{gap:12px;}
    .hireai-fp-hero__actions .hireai-fp__btn{min-height:48px;padding:14px 22px;font-size:13px;}
    .hireai-fp-products__head,.hireai-fp-solutions__head,.hireai-fp-cases__head{flex-direction:column;align-items:flex-start;}
    .hireai-fp-faq__list{padding-inline:0;}
    .hireai-fp-cta__actions{flex-direction:column;align-items:stretch;}
    .hireai-fp-cta__actions .hireai-fp__btn{width:100%;}
}
</style>

<!-- ═══════════════════════════════════════════════════════════════════════
     1) HERO  ── 满屏 / 背景图 / 渐变遮罩 / 双按钮 / 向下滚动指示
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-hero" aria-label="<?php echo esc_attr($is_en ? 'Hero' : '首页主视觉'); ?>">
    <div class="hireai-fp-hero__bg" aria-hidden="true">
        <?php if ($hero_image) : ?>
        <img
            src="<?php echo esc_url($hero_image); ?>"
            alt=""
            width="1920"
            height="1080"
            sizes="100vw"
            loading="eager"
            fetchpriority="high"
            decoding="async">
        <div class="hireai-fp-hero__shade" aria-hidden="true"></div>
        <?php endif; ?>
    </div>
    <div class="hireai-fp-hero__inner">
        <?php if ($hero_kicker) : ?>
        <p class="hireai-fp__kicker hireai-fp-hero__kicker"><?php echo esc_html($hero_kicker); ?></p>
        <?php endif; ?>
        <h1 class="hireai-fp-hero__title">
            <span class="hireai-fp-hero__title-line"><?php echo esc_html($hero_static); ?></span>
            <?php if ('' !== $hero_accent_primary) : ?>
            <span class="hireai-fp-hero__title-line hireai-fp__gold"><?php echo esc_html($hero_accent_primary); ?></span>
            <?php endif; ?>
            <?php if ('' !== $hero_accent_secondary) : ?>
            <span class="hireai-fp-hero__title-line hireai-fp__gold"><?php echo esc_html($hero_accent_secondary); ?></span>
            <?php endif; ?>
        </h1>
        <?php if ($hero_subtitle) : ?>
        <p class="hireai-fp-hero__subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        <?php endif; ?>
        <div class="hireai-fp-hero__actions">
            <a class="hireai-fp__btn hireai-fp__btn--primary" href="<?php echo esc_url($hero_cta_1_url); ?>">
                <?php echo esc_html($hero_cta_1_title); ?>
            </a>
            <a class="hireai-fp__btn hireai-fp__btn--outline" href="<?php echo esc_url($hero_cta_2_url); ?>">
                <?php echo esc_html($hero_cta_2_title); ?>
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     2) PRODUCTS  ── 设计基准 AI 数字员工 (3-col / aspect 3:4 / 徽章 / outline 按钮)
     ═══════════════════════════════════════════════════════════════════════ -->
<?php if ($products) : ?>
<section class="hireai-fp hireai-fp-products" aria-labelledby="hireai-fp-products-title">
    <div class="hireai-fp-products__inner">
        <div class="hireai-fp-products__head">
            <div class="hireai-fp-products__head-text">
                <?php if ($prod_section_kicker) : ?>
                <span class="hireai-fp__kicker"><?php echo esc_html($prod_section_kicker); ?></span>
                <?php endif; ?>
                <h2 class="hireai-fp-products__title" id="hireai-fp-products-title"><?php echo esc_html($prod_section_title); ?></h2>
                <?php if ($prod_section_sub) : ?>
                <p class="hireai-fp-products__sub"><?php echo esc_html($prod_section_sub); ?></p>
                <?php endif; ?>
                <?php if ($prod_explore_label) : ?>
                <a class="hireai-fp-products__explore" href="<?php echo esc_url($prod_explore_url); ?>">
                    <?php echo esc_html($prod_explore_label); ?>
                </a>
                <?php endif; ?>
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
    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════════════════
     3) SOLUTIONS  ── 设计基准 AI 解决方案 (2x2 aspect-video 卡片 + primary CTA)
     ═══════════════════════════════════════════════════════════════════════ -->
<?php if ($solutions) : ?>
<section class="hireai-fp hireai-fp-solutions" aria-labelledby="hireai-fp-solutions-title">
    <div class="hireai-fp-solutions__inner">
        <div class="hireai-fp-solutions__head">
            <div class="hireai-fp-solutions__head-text">
                <?php if ($sol_section_kicker) : ?>
                <span class="hireai-fp__kicker"><?php echo esc_html($sol_section_kicker); ?></span>
                <?php endif; ?>
                <h2 class="hireai-fp-solutions__title" id="hireai-fp-solutions-title"><?php echo esc_html($sol_section_title); ?></h2>
                <?php if ($sol_section_sub) : ?>
                <p class="hireai-fp-solutions__sub"><?php echo esc_html($sol_section_sub); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="hireai-fp-solutions__grid">
            <?php foreach ($solutions as $sol) : if (empty($sol['title'])) continue; ?>
            <a class="hireai-fp-sol" href="<?php echo esc_url($sol['url']); ?>">
                <?php if (!empty($sol['img'])) : ?>
                <img class="hireai-fp-sol__img" src="<?php echo esc_url($sol['img']); ?>" alt="<?php echo esc_attr($sol['title']); ?>" loading="lazy" decoding="async"
                     onerror="this.src='<?php echo esc_url($home . '/assets/img/defaults/solution-1.jpg'); ?>'; this.onerror=function(){ this.style.display='none'; };">
                <?php else : ?>
                <div class="hireai-fp-sol__placeholder" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 5.8a2 2 0 0 0 1.3 1.3L21 12l-5.8 1.9a2 2 0 0 0-1.3 1.3L12 21l-1.9-5.8a2 2 0 0 0-1.3-1.3L3 12l5.8-1.9a2 2 0 0 0 1.3-1.3L12 3z"/></svg>
                </div>
                <?php endif; ?>
                <div class="hireai-fp-sol__overlay">
                    <?php if (!empty($sol['tag'])) : ?>
                    <span class="hireai-fp-sol__tag"><?php echo esc_html($sol['tag']); ?></span>
                    <?php endif; ?>
                    <h3 class="hireai-fp-sol__title"><?php echo esc_html($sol['title']); ?></h3>
                    <p class="hireai-fp-sol__desc"><?php echo esc_html($sol['desc']); ?></p>
                    <?php if (!empty($sol['price_html'])) : ?>
                    <span class="hireai-fp-sol__price">
                        <span><?php echo esc_html($is_en ? 'Starting at' : '起步价'); ?></span>
                        <span class="hireai-fp-sol__price-value"><?php echo wp_kses_post($sol['price_html']); ?></span>
                    </span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="hireai-fp-solutions__cta">
            <a class="hireai-fp__btn hireai-fp__btn--primary" href="<?php echo esc_url($sol_explore_url); ?>">
                <?php echo esc_html($sol_explore_label); ?>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════════════════
     4) CASES  ── 设计基准 案例 & 洞察 (12-col 网格 8+4)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-cases" aria-labelledby="hireai-fp-cases-title">
    <div class="hireai-fp-cases__inner">
        <div class="hireai-fp-cases__head">
            <div class="hireai-fp-cases__head-text">
                <?php if ($cases_kicker) : ?>
                <span class="hireai-fp__kicker"><?php echo esc_html($cases_kicker); ?></span>
                <?php endif; ?>
                <h2 class="hireai-fp-cases__title" id="hireai-fp-cases-title"><?php echo esc_html($cases_title); ?></h2>
                <?php if ($cases_sub) : ?>
                <p class="hireai-fp-cases__sub"><?php echo esc_html($cases_sub); ?></p>
                <?php endif; ?>
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
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════
     5) FAQ  ── 设计基准 常见问题 (3 项手风琴 + ghost CTA)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-faq" id="hireai-fp-faq" aria-labelledby="hireai-fp-faq-title">
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
                 onclick="var n=this.closest('.hireai-fp-faq-item');var o=n.classList.toggle('is-open');this.setAttribute('aria-expanded',o?'true':'false');"
                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}">
                <h3 class="hireai-fp-faq-item__q"><?php echo esc_html($item['q']); ?></h3>
                <svg class="hireai-fp-faq-item__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
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
     6) CTA BAND  ── 设计基准 大 CTA (深色 + 金色 radial glow + 双按钮)
     ═══════════════════════════════════════════════════════════════════════ -->
<section class="hireai-fp hireai-fp-cta" aria-labelledby="hireai-fp-cta-title">
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
            <a class="hireai-fp__btn hireai-fp__btn--outline" href="<?php echo esc_url($cta_btn_2_url); ?>">
                <?php echo esc_html($cta_btn_2_title); ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
