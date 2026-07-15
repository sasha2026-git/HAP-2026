<?php
/**
 * Plugin Name: Aurelian FAQ v3
 * Plugin URI: https://github.com/sasha2026-git/HireAIPeople
 * GitHub Plugin URI: sasha2026-git/HireAIPeople
 * Description: 常见问题页面 (FAQ) — 短码 [aurelian_faq]，支持 ACF 可视化编辑
 * Version: 3.0.0
 * Author: Codex
 * Requires Plugins: advanced-custom-fields
 */

if (!defined('ABSPATH')) exit;

// GitHub Updater (Plugin Update Checker)
require_once __DIR__ . '/lib/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$afaq_update_checker = PucFactory::buildUpdateChecker(
    'https://github.com/sasha2026-git/HireAIPeople',
    __FILE__,
    'aurelian-faq-plugin'
);
$afaq_update_checker->setDirectoryName('aurelian-faq-plugin');

add_action('admin_notices', 'afaq_check_acf');
function afaq_check_acf() {
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="notice notice-warning"><p>🔥 <strong>Aurelian FAQ v3</strong> 需要安装并启用 <strong>Advanced Custom Fields (ACF)</strong> 插件。</p></div>';
    }
}

// =============================================
// ACF 字段注册
// =============================================
add_action('acf/init', 'afaq_register_acf_fields');
function afaq_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group(array(
        'key'    => 'group_aurelian_faq_v3',
        'title'  => '❓ Aurelian FAQ — 页面内容编辑',
        'fields' => array(

            // ===== 英雄区 =====
            array('key'=>'field_afaq_tab_hero','label'=>'🎯 顶部大图区（Hero）','type'=>'tab'),
            array('key'=>'field_afaq_hero_tagline','label'=>'顶部小标签','name'=>'faq_hero_tagline','type'=>'text','default_value'=>'常见问题'),
            array('key'=>'field_afaq_hero_title','label'=>'主标题','name'=>'faq_hero_title','type'=>'text','default_value'=>'您想知道的答案就在这里'),
            array('key'=>'field_afaq_hero_desc','label'=>'描述','name'=>'faq_hero_desc','type'=>'textarea','default_value'=>'关于AURELIAN AI数字人的一切疑问，我们为您一一解答。','new_lines'=>'br'),

            // ===== FAQ 分类 & 问题 =====
            array('key'=>'field_afaq_tab_faq','label'=>'📋 FAQ 分类与问答','type'=>'tab'),
            array(
                'key'          => 'field_afaq_categories',
                'label'        => 'FAQ 分类',
                'name'         => 'faq_categories',
                'type'         => 'repeater',
                'layout'       => 'block',
                'button_label' => '➕ 添加分类',
                'collapsed'    => 'field_afaq_cat_title',
                'sub_fields' => array(
                    array('key'=>'field_afaq_cat_title','label'=>'分类名称','name'=>'faq_cat_title','type'=>'text','default_value'=>'新分类'),
                    array(
                        'key'          => 'field_afaq_cat_items',
                        'label'        => '问答列表',
                        'name'         => 'faq_cat_items',
                        'type'         => 'repeater',
                        'layout'       => 'block',
                        'button_label' => '➕ 添加问答',
                        'collapsed'    => 'field_afaq_q_question',
                        'sub_fields' => array(
                            array('key'=>'field_afaq_q_question','label'=>'问题','name'=>'faq_q_question','type'=>'text','default_value'=>'新问题'),
                            array('key'=>'field_afaq_q_answer','label'=>'答案','name'=>'faq_q_answer','type'=>'wysiwyg','default_value'=>'','media_upload'=>0,'tabs'=>'text','toolbar'=>'basic'),
                        ),
                    ),
                ),
            ),

            // ===== AI解决方案展示 =====
            array('key'=>'field_afaq_tab_solutions','label'=>'💎 AI解决方案展示','type'=>'tab'),
            array('key'=>'field_afaq_sol_title','label'=>'区域标题','name'=>'faq_sol_title','type'=>'text','default_value'=>'探索更多AI解决方案'),
            array('key'=>'field_afaq_sol_desc','label'=>'区域描述','name'=>'faq_sol_desc','type'=>'textarea','default_value'=>'点击了解AURELIAN AI如何助力您的业务','new_lines'=>'br'),
            array(
                'key'          => 'field_afaq_solutions',
                'label'        => '解决方案卡片',
                'name'         => 'faq_solutions',
                'type'         => 'repeater',
                'layout'       => 'block',
                'button_label' => '➕ 添加方案',
                'collapsed'    => 'field_afaq_sol_card_title',
                'sub_fields' => array(
                    array('key'=>'field_afaq_sol_card_icon','label'=>'图标Emoji/文字','name'=>'faq_sol_card_icon','type'=>'text','default_value'=>'🤖'),
                    array('key'=>'field_afaq_sol_card_title','label'=>'标题','name'=>'faq_sol_card_title','type'=>'text','default_value'=>'AI数字人'),
                    array('key'=>'field_afaq_sol_card_desc','label'=>'描述','name'=>'faq_sol_card_desc','type'=>'textarea','default_value'=>'','new_lines'=>'br'),
                    array('key'=>'field_afaq_sol_card_url','label'=>'链接','name'=>'faq_sol_card_url','type'=>'url','default_value'=>'#'),
                ),
            ),

            // ===== 定价方案 =====
            array('key'=>'field_afaq_tab_pricing','label'=>'💰 定价方案','type'=>'tab'),
            array('key'=>'field_afaq_price_title','label'=>'定价区域标题','name'=>'faq_price_title','type'=>'text','default_value'=>'透明定价，灵活选择'),
            array('key'=>'field_afaq_price_desc','label'=>'定价区域描述','name'=>'faq_price_desc','type'=>'textarea','default_value'=>'选择最适合您需求的方案','new_lines'=>'br'),
            array(
                'key'          => 'field_afaq_pricing_tiers',
                'label'        => '定价方案',
                'name'         => 'faq_pricing_tiers',
                'type'         => 'repeater',
                'layout'       => 'block',
                'button_label' => '➕ 添加方案',
                'collapsed'    => 'field_afaq_tier_title',
                'sub_fields' => array(
                    array('key'=>'field_afaq_tier_badge','label'=>'徽章文字（可选）','name'=>'faq_tier_badge','type'=>'text','default_value'=>'','placeholder'=>'如：推荐 / 最受欢迎'),
                    array('key'=>'field_afaq_tier_title','label'=>'方案名称','name'=>'faq_tier_title','type'=>'text','default_value'=>'基础版'),
                    array('key'=>'field_afaq_tier_price','label'=>'价格','name'=>'faq_tier_price','type'=>'text','default_value'=>'¥9,999'),
                    array('key'=>'field_afaq_tier_period','label'=>'周期','name'=>'faq_tier_period','type'=>'text','default_value'=>'/月'),
                    array('key'=>'field_afaq_tier_desc','label'=>'描述','name'=>'faq_tier_desc','type'=>'textarea','default_value'=>'适合初创企业和个人使用','new_lines'=>'br'),
                    array('key'=>'field_afaq_tier_btn','label'=>'按钮文字','name'=>'faq_tier_btn','type'=>'text','default_value'=>'立即开始'),
                    array('key'=>'field_afaq_tier_url','label'=>'按钮链接','name'=>'faq_tier_url','type'=>'url','default_value'=>'#'),
                    array(
                        'key'          => 'field_afaq_tier_features',
                        'label'        => '功能列表',
                        'name'         => 'faq_tier_features',
                        'type'         => 'repeater',
                        'layout'       => 'table',
                        'button_label' => '➕ 添加功能',
                        'collapsed'    => '',
                        'sub_fields' => array(
                            array('key'=>'field_afaq_tier_feat','label'=>'功能项','name'=>'faq_tier_feat','type'=>'text','default_value'=>'新功能'),
                        ),
                    ),
                ),
            ),

            // ===== 联系区域 =====
            array('key'=>'field_afaq_tab_contact','label'=>'📞 联系区域','type'=>'tab'),
            array('key'=>'field_afaq_cont_title','label'=>'标题','name'=>'faq_cont_title','type'=>'text','default_value'=>'仍有疑问？'),
            array('key'=>'field_afaq_cont_desc','label'=>'描述','name'=>'faq_cont_desc','type'=>'textarea','default_value'=>'我们的团队随时为您解答','new_lines'=>'br'),
            array('key'=>'field_afaq_cont_phone','label'=>'电话','name'=>'faq_cont_phone','type'=>'text','default_value'=>'+86 400-888-9999'),
            array('key'=>'field_afaq_cont_email','label'=>'邮箱','name'=>'faq_cont_email','type'=>'email','default_value'=>'contact@aurelian-ai.com'),
            array('key'=>'field_afaq_cont_qr1','label'=>'二维码1（微信）','name'=>'faq_cont_qr1','type'=>'image','return_format'=>'url'),
            array('key'=>'field_afaq_cont_qr1_label','label'=>'二维码1 标签','name'=>'faq_cont_qr1_label','type'=>'text','default_value'=>'微信客服'),
            array('key'=>'field_afaq_cont_qr2','label'=>'二维码2（公众号）','name'=>'faq_cont_qr2','type'=>'image','return_format'=>'url'),
            array('key'=>'field_afaq_cont_qr2_label','label'=>'二维码2 标签','name'=>'faq_cont_qr2_label','type'=>'text','default_value'=>'关注公众号'),

            // ===== Footer =====
            array('key'=>'field_afaq_tab_footer','label'=>'📌 Footer','type'=>'tab'),
            array('key'=>'field_afaq_footer_logo','label'=>'Logo 图片','name'=>'faq_footer_logo','type'=>'image','return_format'=>'url'),
            array('key'=>'field_afaq_footer_brand','label'=>'品牌名（无 Logo 时显示）','name'=>'faq_footer_brand','type'=>'text','default_value'=>'AURELIAN AI'),
            array('key'=>'field_afaq_footer_copyright','label'=>'版权文字','name'=>'faq_footer_copyright','type'=>'text','default_value'=>'© 2026 AURELIAN AI. ALL RIGHTS RESERVED.'),
            array(
                'key'          => 'field_afaq_footer_menu',
                'label'        => '底部菜单',
                'name'         => 'faq_footer_menu',
                'type'         => 'repeater',
                'layout'       => 'table',
                'button_label' => '➕ 添加链接',
                'sub_fields' => array(
                    array('key'=>'field_afaq_fm_label','label'=>'文字','name'=>'faq_fm_label','type'=>'text','default_value'=>'新链接'),
                    array('key'=>'field_afaq_fm_url','label'=>'URL','name'=>'faq_fm_url','type'=>'url','default_value'=>'#'),
                ),
            ),
        ),
        'location' => array(
            array(array('param'=>'post_type','operator'=>'==','value'=>'page')),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'field',
        'hide_on_screen' => '',
    ));
}

// =============================================
// 短码渲染
// =============================================
add_shortcode('aurelian_faq', 'afaq_render');
function afaq_render() {
    ob_start();

    // ===== 页脚默认 =====
    $default_footer_menu = array(
        array('label' => 'Brand Story',     'url' => '#'),
        array('label' => 'Privacy',         'url' => '#'),
        array('label' => 'Terms of Service','url' => '#'),
        array('label' => 'Contact',         'url' => '#'),
    );

    // ===== 默认 FAQ 数据（初始展示） =====
    $default_categories = array(
        array(
            'faq_cat_title' => '关于AI数字人',
            'faq_cat_items' => array(
                array('faq_q_question' => '什么是AURELIAN AI数字人？', 'faq_q_answer' => 'AURELIAN AI数字人是基于先进人工智能技术打造的虚拟数字形象，具备自然语言交互、情感识别、多模态表达等能力，可广泛应用于客户服务、品牌代言、教育培训等场景。'),
                array('faq_q_question' => 'AURELIAN AI数字人与普通AI有何不同？', 'faq_q_answer' => 'AURELIAN AI数字人采用专有的多模态大模型，结合了语音合成、面部表情生成、肢体动作协调等前沿技术。相比传统AI，它拥有更自然的情感表达、更流畅的对话体验，以及可定制的外形与性格特征。'),
                array('faq_q_question' => 'AI数字人是否需要硬件设备支持？', 'faq_q_answer' => '不需要特殊硬件。AURELIAN AI数字人基于云端运行，通过网页、移动应用或大屏即可展示。您可以将其嵌入官网、小程序、APP或线下互动屏幕。'),
            ),
        ),
        array(
            'faq_cat_title' => '产品与服务',
            'faq_cat_items' => array(
                array('faq_q_question' => '有哪些类型的AI数字人方案？', 'faq_q_answer' => '我们提供三大类方案：1）<strong>标准型数字人</strong>——快速部署，适合客服、导购等场景；2）<strong>定制型数字人</strong>——根据品牌需求定制外形、声音与性格；3）<strong>旗舰型数字人</strong>——全栈定制，包含专属大模型训练与多场景部署。'),
                array('faq_q_question' => '部署周期需要多久？', 'faq_q_answer' => '标准方案可在3-5个工作日内完成部署。定制方案根据复杂度，通常需要2-4周。旗舰方案需根据具体需求评估，一般在4-8周内完成。'),
                array('faq_q_question' => '是否支持多语言？', 'faq_q_answer' => '是的，AURELIAN AI数字人支持中文、英文、日语、韩语、法语、德语等12种语言，并可在对话中自动识别切换。'),
            ),
        ),
        array(
            'faq_cat_title' => '技术与安全',
            'faq_cat_items' => array(
                array('faq_q_question' => '数据安全性如何保障？', 'faq_q_answer' => '我们严格遵循国际数据安全标准（ISO 27001），所有数据传输采用256位加密，对话记录可选择本地部署存储。客户数据不会用于模型训练，确保隐私安全。'),
                array('faq_q_question' => '是否支持私有化部署？', 'faq_q_answer' => '是的，我们提供完整的私有化部署方案，支持部署在客户指定的服务器或云环境（阿里云、华为云、AWS、Azure等），满足金融、医疗等对数据安全有严格要求的行业需求。'),
                array('faq_q_question' => 'AI数字人的知识库如何更新？', 'faq_q_answer' => '您可以通过后台管理系统实时更新数字人的知识库，支持文档导入（PDF/Word/Excel）、手动录入、API对接三种方式。更新后可以即时生效，无需重新部署。'),
            ),
        ),
        array(
            'faq_cat_title' => '定价与支持',
            'faq_cat_items' => array(
                array('faq_q_question' => '是否有免费试用？', 'faq_q_answer' => '是的，我们提供7天免费试用，您可以在官方网站申请体验。试用期间将获得完整的标准版功能体验与专属技术支持。'),
                array('faq_q_question' => '售后服务包括哪些？', 'faq_q_answer' => '所有付费方案均提供7×24小时技术支持。定制及以上方案配备专属项目经理，提供定期运维报告、性能优化建议与年度升级服务。'),
                array('faq_q_question' => '是否可以根据需求调整方案？', 'faq_q_answer' => '当然可以，我们的方案支持灵活调整。您可以根据业务需求随时升级或降级方案，按比例退还或补足差价。详情请咨询客户成功团队。'),
            ),
        ),
    );

    $default_solutions = array(
        array('faq_sol_card_icon'=>'🤖','faq_sol_card_title'=>'AI数字人','faq_sol_card_desc'=>'定制化智能数字形象，7×24小时为客户提供自然流畅的交互体验','faq_sol_card_url'=>'#'),
        array('faq_sol_card_icon'=>'💬','faq_sol_card_title'=>'智能客服','faq_sol_card_desc'=>'基于大模型的智能对话系统，精准理解客户意图，提升服务效率','faq_sol_card_url'=>'#'),
        array('faq_sol_card_icon'=>'🎨','faq_sol_card_title'=>'内容创作','faq_sol_card_desc'=>'AI驱动的文案、图像、视频内容生成，加速品牌内容生产流程','faq_sol_card_url'=>'#'),
        array('faq_sol_card_icon'=>'📊','faq_sol_card_title'=>'数据分析','faq_sol_card_desc'=>'智能数据分析与洞察平台，帮助您从数据中发现商业机会','faq_sol_card_url'=>'#'),
        array('faq_sol_card_icon'=>'🔐','faq_sol_card_title'=>'私有化部署','faq_sol_card_desc'=>'企业级私有化解决方案，确保数据安全与合规','faq_sol_card_url'=>'#'),
        array('faq_sol_card_icon'=>'🌐','faq_sol_card_title'=>'全球化方案','faq_sol_card_desc'=>'支持多语言、多文化的全球化部署，助力品牌出海','faq_sol_card_url'=>'#'),
    );

    $default_pricing = array(
        array(
            'faq_tier_badge'=>'','faq_tier_title'=>'基础版','faq_tier_price'=>'¥9,999','faq_tier_period'=>'/月',
            'faq_tier_desc'=>'适合初创企业和个人使用','faq_tier_btn'=>'立即开始','faq_tier_url'=>'#',
            'faq_tier_features' => array(
                array('faq_tier_feat'=>'1个标准数字人形象'),
                array('faq_tier_feat'=>'基础对话能力'),
                array('faq_tier_feat'=>'1个部署渠道'),
                array('faq_tier_feat'=>'5,000次/月交互'),
                array('faq_tier_feat'=>'邮件技术支持'),
            ),
        ),
        array(
            'faq_tier_badge'=>'最受欢迎','faq_tier_title'=>'专业版','faq_tier_price'=>'¥29,999','faq_tier_period'=>'/月',
            'faq_tier_desc'=>'适合快速成长的中型企业','faq_tier_btn'=>'立即开始','faq_tier_url'=>'#',
            'faq_tier_features' => array(
                array('faq_tier_feat'=>'3个定制数字人形象'),
                array('faq_tier_feat'=>'高级对话 + 情感识别'),
                array('faq_tier_feat'=>'3个部署渠道'),
                array('faq_tier_feat'=>'50,000次/月交互'),
                array('faq_tier_feat'=>'专属客户成功经理'),
                array('faq_tier_feat'=>'数据分析报表'),
            ),
        ),
        array(
            'faq_tier_badge'=>'','faq_tier_title'=>'企业版','faq_tier_price'=>'定制报价','faq_tier_period'=>'',
            'faq_tier_desc'=>'适合大型企业和定制需求','faq_tier_btn'=>'联系我们','faq_tier_url'=>'#',
            'faq_tier_features' => array(
                array('faq_tier_feat'=>'不限数字人形象'),
                array('faq_tier_feat'=>'全栈定制+私有化部署'),
                array('faq_tier_feat'=>'不限部署渠道'),
                array('faq_tier_feat'=>'无限交互次数'),
                array('faq_tier_feat'=>'专属项目团队'),
                array('faq_tier_feat'=>'定制模型训练'),
            ),
        ),
    );

    // ===== 获取 ACF 数据 =====
    global $post_id;
    $post_id = get_the_ID();
    if (!$post_id && isset($_GET["post"])) $post_id = intval($_GET["post"]);
    if (!$post_id) $post_id = get_queried_object_id();

    function afaq_f($name, $default = "", $pid = null) {
        global $post_id;
        $pid = $pid ?: $post_id;
        $val = get_field($name, $pid);
        return (!empty($val) || $val === "0") ? $val : $default;
    }

    // ===== 获取数据（有ACF值则用，无则用默认） =====
    $hero_tagline = afaq_f('faq_hero_tagline', '常见问题');
    $hero_title   = afaq_f('faq_hero_title', '您想知道的答案就在这里');
    $hero_desc    = afaq_f('faq_hero_desc', '关于AURELIAN AI数字人的一切疑问，我们为您一一解答。');

    $categories_raw = get_field('faq_categories', $post_id);
    $categories = array();
    if (!empty($categories_raw) && is_array($categories_raw)) {
        foreach ($categories_raw as $cat) {
            $items = array();
            if (!empty($cat['faq_cat_items']) && is_array($cat['faq_cat_items'])) {
                foreach ($cat['faq_cat_items'] as $item) {
                    $items[] = array(
                        'question' => $item['faq_q_question'] ?? '',
                        'answer'   => $item['faq_q_answer'] ?? '',
                    );
                }
            }
            $categories[] = array(
                'title' => $cat['faq_cat_title'] ?? '',
                'items' => $items,
            );
        }
    } else {
        $categories = $default_categories;
    }

    $sol_title = afaq_f('faq_sol_title', '探索更多AI解决方案');
    $sol_desc  = afaq_f('faq_sol_desc', '点击了解AURELIAN AI如何助力您的业务');

    $solutions_raw = get_field('faq_solutions', $post_id);
    $solutions = array();
    if (!empty($solutions_raw) && is_array($solutions_raw)) {
        foreach ($solutions_raw as $s) {
            $solutions[] = array(
                'icon'  => $s['faq_sol_card_icon'] ?? '🤖',
                'title' => $s['faq_sol_card_title'] ?? '',
                'desc'  => $s['faq_sol_card_desc'] ?? '',
                'url'   => $s['faq_sol_card_url'] ?? '#',
            );
        }
    } else {
        $solutions = $default_solutions;
    }

    $price_title = afaq_f('faq_price_title', '透明定价，灵活选择');
    $price_desc  = afaq_f('faq_price_desc', '选择最适合您需求的方案');

    $pricing_raw = get_field('faq_pricing_tiers', $post_id);
    $pricing_tiers = array();
    if (!empty($pricing_raw) && is_array($pricing_raw)) {
        foreach ($pricing_raw as $t) {
            $features = array();
            if (!empty($t['faq_tier_features']) && is_array($t['faq_tier_features'])) {
                foreach ($t['faq_tier_features'] as $f) {
                    $features[] = $f['faq_tier_feat'] ?? '';
                }
            }
            $pricing_tiers[] = array(
                'badge'    => $t['faq_tier_badge'] ?? '',
                'title'    => $t['faq_tier_title'] ?? '',
                'price'    => $t['faq_tier_price'] ?? '',
                'period'   => $t['faq_tier_period'] ?? '',
                'desc'     => $t['faq_tier_desc'] ?? '',
                'btn'      => $t['faq_tier_btn'] ?? '',
                'url'      => $t['faq_tier_url'] ?? '#',
                'features' => $features,
            );
        }
    } else {
        $pricing_tiers = $default_pricing;
    }

    $cont_title   = afaq_f('faq_cont_title', '仍有疑问？');
    $cont_desc    = afaq_f('faq_cont_desc', '我们的团队随时为您解答');
    $cont_phone   = afaq_f('faq_cont_phone', '+86 400-888-9999');
    $cont_email   = afaq_f('faq_cont_email', 'contact@aurelian-ai.com');
    $cont_qr1     = afaq_f('faq_cont_qr1', '');
    $cont_qr1_lbl = afaq_f('faq_cont_qr1_label', '微信客服');
    $cont_qr2     = afaq_f('faq_cont_qr2', '');
    $cont_qr2_lbl = afaq_f('faq_cont_qr2_label', '关注公众号');

    $footer_logo      = afaq_f('faq_footer_logo', '');
    $footer_brand     = afaq_f('faq_footer_brand', 'AURELIAN AI');
    $footer_copyright = afaq_f('faq_footer_copyright', '© 2026 AURELIAN AI. ALL RIGHTS RESERVED.');
    $footer_menu_raw  = get_field('faq_footer_menu', $post_id);
    $footer_menu = array();
    if (!empty($footer_menu_raw) && is_array($footer_menu_raw)) {
        foreach ($footer_menu_raw as $item) {
            $footer_menu[] = array(
                'label' => $item['faq_fm_label'] ?? '',
                'url'   => $item['faq_fm_url'] ?? '#',
            );
        }
    } else {
        $footer_menu = $default_footer_menu;
    }
    ?>
    <div id="afaq-root">

    <!-- ===== Skip Navigation (WCAG) ===== -->
    <a href="#afaq-content-start" class="afaq-skip-link">跳转到主要内容</a>

    <!-- Tailwind CDN (轻量) -->
    <script>
    (function(){
        if (document.getElementById('afaq-tw')) return;
        var s = document.createElement('script');
        s.id = 'afaq-tw';
        s.src = 'https://cdn.tailwindcss.com?plugins=forms,container-queries';
        document.head.appendChild(s);
    })();
    </script>
    <script>
    tailwind.config = {
      corePlugins: { preflight: false },
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#000000",
            "secondary": "#775a19",
            "surface": "#faf9f9",
            "background": "#faf9f9",
            "on-surface-variant": "#444748",
            "on-surface": "#1a1c1c",
            "on-secondary-container": "#785a1a",
            "surface-container-low": "#f4f3f3",
            "secondary-fixed-dim": "#e9c176",
            "outline-variant": "#c4c7c7"
          },
          fontFamily: {
            'display': ["'Playfair Display'", 'serif'],
            'body': ['Inter', 'sans-serif'],
          }
        }
      }
    };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
      #afaq-root {
        font-family: Inter, sans-serif;
        background: #faf9f9;
        color: #1a1c1c;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        --gold: #775a19;
        --gold-light: #e9c176;
        --bg-ivory: #faf9f9;
        --bg-warm: #f4f3f3;
        --text-dark: #1a1c1c;
        --text-muted: #444748;
        /* Design System Border Radius */
        --radius-sm: 4px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 24px;
      }

      /* ===== SKIP NAV ===== */
      .afaq-skip-link {
        position: absolute;
        left: -9999px;
        top: 0;
        z-index: 9999;
        padding: 12px 24px;
        background: var(--gold);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border-radius: var(--radius-sm);
      }
      .afaq-skip-link:focus {
        left: 16px;
        top: 16px;
      }

      /* ===== HERO ===== */
      .afaq-hero {
        position: relative;
        padding: 120px 24px 80px;
        text-align: center;
        background: linear-gradient(180deg, #f4f3f3 0%, #faf9f9 100%);
        overflow: hidden;
      }
      .afaq-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 40%, rgba(119,90,25,0.04) 0%, transparent 60%),
                    radial-gradient(circle at 70% 60%, rgba(233,193,118,0.06) 0%, transparent 50%);
        pointer-events: none;
      }
      .afaq-hero-tag {
        font-size: 13px;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 20px;
        font-weight: 600;
        position: relative;
      }
      .afaq-hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(36px, 5vw, 56px);
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: var(--text-dark);
        margin-bottom: 20px;
        position: relative;
      }
      .afaq-hero-desc {
        font-size: 16px;
        line-height: 1.7;
        color: var(--text-muted);
        max-width: 600px;
        margin: 0 auto;
        position: relative;
      }

      /* ===== LAYOUT: Nav + Content ===== */
      .afaq-layout {
        max-width: 1280px;
        margin: 0 auto;
        padding: 60px 24px 0;
        display: flex;
        gap: 48px;
        align-items: flex-start;
      }
      .afaq-nav {
        flex: 0 0 220px;
        position: sticky;
        top: 32px;
        max-height: calc(100vh - 64px);
        overflow-y: auto;
        scrollbar-width: thin;
      }
      .afaq-nav-item {
        display: block;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-muted);
        text-decoration: none;
        border-radius: var(--radius-md);
        transition: all 0.25s;
        margin-bottom: 4px;
        cursor: pointer;
      }
      .afaq-nav-item:hover,
      .afaq-nav-item.active {
        background: rgba(119,90,25,0.08);
        color: var(--gold);
      }
      .afaq-nav-item.active {
        font-weight: 600;
        background: rgba(119,90,25,0.1);
      }
      .afaq-content {
        flex: 1;
        min-width: 0;
      }
      #afaq-content-start {
        scroll-margin-top: 32px;
      }

      /* ===== FAQ ACCORDION ===== */
      .afaq-category {
        margin-bottom: 48px;
        scroll-margin-top: 32px;
      }
      .afaq-category-title {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(119,90,25,0.15);
      }
      .afaq-item {
        border-bottom: 1px solid rgba(196,199,199,0.4);
      }
      .afaq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 0;
        cursor: pointer;
        font-size: 15px;
        font-weight: 500;
        color: var(--text-dark);
        transition: color 0.25s;
        gap: 16px;
        user-select: none;
      }
      .afaq-question:hover { color: var(--gold); }
      .afaq-question.open { color: var(--gold); font-weight: 600; }
      .afaq-question .afaq-icon {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1.5px solid currentColor;
        font-size: 14px;
        transition: transform 0.35s;
        color: var(--gold);
      }
      .afaq-question.open .afaq-icon { transform: rotate(45deg); }
      .afaq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.45s cubic-bezier(0.16,1,0.3,1), padding 0.3s;
        padding: 0;
        font-size: 14px;
        line-height: 1.7;
        color: var(--text-muted);
      }
      .afaq-answer.open {
        max-height: 2000px;
        padding: 0 0 24px;
      }
      .afaq-answer p { margin: 0 0 12px; }
      .afaq-answer p:last-child { margin-bottom: 0; }

      /* ===== SOLUTIONS SECTION ===== */
      .afaq-section {
        max-width: 1280px;
        margin: 0 auto;
        padding: 80px 24px;
      }
      .afaq-section-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(28px, 3.5vw, 36px);
        font-weight: 600;
        line-height: 1.2;
        text-align: center;
        margin-bottom: 12px;
        color: var(--text-dark);
      }
      .afaq-section-desc {
        font-size: 15px;
        line-height: 1.6;
        color: var(--text-muted);
        text-align: center;
        max-width: 560px;
        margin: 0 auto 48px;
      }
      .afaq-sol-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
      }
      .afaq-sol-card {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(119,90,25,0.1);
        border-radius: var(--radius-lg);
        padding: 32px 24px;
        text-align: center;
        transition: all 0.35s;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
      }
      .afaq-sol-card:hover {
        transform: translateY(-4px);
        border-color: rgba(119,90,25,0.25);
        box-shadow: 0 12px 40px rgba(119,90,25,0.08);
      }
      .afaq-sol-icon {
        font-size: 36px;
        margin-bottom: 16px;
        display: block;
      }
      .afaq-sol-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 12px;
        color: var(--text-dark);
      }
      .afaq-sol-card-desc {
        font-size: 13px;
        line-height: 1.6;
        color: var(--text-muted);
      }

      /* ===== PRICING ===== */
      .afaq-section.bg-warm { background: var(--bg-warm); }
      .afaq-price-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 32px;
        max-width: 1080px;
        margin: 0 auto;
      }
      .afaq-price-card {
        background: #fff;
        border-radius: var(--radius-xl);
        padding: 40px 32px;
        border: 1px solid rgba(196,199,199,0.4);
        position: relative;
        transition: all 0.35s;
        display: flex;
        flex-direction: column;
      }
      .afaq-price-card:hover {
        box-shadow: 0 16px 48px rgba(119,90,25,0.1);
        border-color: rgba(119,90,25,0.2);
        transform: translateY(-4px);
      }
      .afaq-price-card.featured {
        border-color: var(--gold);
        box-shadow: 0 8px 32px rgba(119,90,25,0.12);
      }
      .afaq-price-badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--gold);
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 4px 16px;
        border-radius: 9999px;
      }
      .afaq-price-name {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-dark);
      }
      .afaq-price-amount {
        font-family: 'Playfair Display', serif;
        font-size: 42px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
        line-height: 1.1;
      }
      .afaq-price-period {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 12px;
      }
      .afaq-price-desc {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 24px;
        line-height: 1.5;
      }
      .afaq-price-features {
        list-style: none;
        padding: 0;
        margin: 0 0 32px;
        flex-grow: 1;
      }
      .afaq-price-features li {
        padding: 8px 0;
        font-size: 14px;
        color: var(--text-muted);
        border-bottom: 1px solid rgba(196,199,199,0.25);
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .afaq-price-features li::before {
        content: '✓';
        color: var(--gold);
        font-weight: 700;
        font-size: 14px;
      }
      .afaq-price-btn {
        display: inline-block;
        width: 100%;
        padding: 14px 32px;
        border-radius: 9999px;
        background: var(--text-dark);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
      }
      .afaq-price-btn:hover {
        box-shadow: 0 0 24px rgba(119,90,25,0.25);
        background: var(--gold);
      }
      .afaq-price-card.featured .afaq-price-btn {
        background: var(--gold);
      }
      .afaq-price-card.featured .afaq-price-btn:hover {
        background: var(--text-dark);
        box-shadow: 0 0 24px rgba(119,90,25,0.25);
      }

      /* ===== CONTACT ===== */
      .afaq-contact {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 48px;
        max-width: 1080px;
        margin: 0 auto;
        align-items: center;
      }
      .afaq-contact-info {
        text-align: left;
      }
      .afaq-contact-info h3 {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 16px;
        color: var(--text-dark);
      }
      .afaq-contact-info p {
        font-size: 14px;
        line-height: 1.7;
        color: var(--text-muted);
        margin-bottom: 24px;
      }
      .afaq-contact-detail {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        font-size: 14px;
        color: var(--text-dark);
      }
      .afaq-contact-detail a {
        color: var(--gold);
        text-decoration: none;
      }
      .afaq-contact-detail a:hover { text-decoration: underline; }
      .afaq-contact-qrs {
        display: flex;
        gap: 32px;
        margin-top: 24px;
      }
      .afaq-contact-qr-item {
        text-align: center;
      }
      .afaq-contact-qr-item img {
        width: 120px;
        height: 120px;
        border-radius: var(--radius-md);
        border: 1px solid rgba(196,199,199,0.4);
        margin-bottom: 8px;
        object-fit: cover;
        background: #fff;
      }
      .afaq-contact-qr-item span {
        font-size: 12px;
        color: var(--text-muted);
      }
      .afaq-contact-form {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 32px;
        border: 1px solid rgba(196,199,199,0.3);
      }
      .afaq-contact-form h4 {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
      }
      .afaq-form-group {
        margin-bottom: 16px;
      }
      .afaq-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 6px;
      }
      .afaq-form-group input,
      .afaq-form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid rgba(196,199,199,0.5);
        border-radius: var(--radius-md);
        font-size: 14px;
        font-family: Inter, sans-serif;
        background: var(--bg-ivory);
        transition: border-color 0.25s;
        box-sizing: border-box;
      }
      .afaq-form-group input:focus,
      .afaq-form-group textarea:focus {
        outline: none;
        border-color: var(--gold);
      }
      .afaq-form-group textarea { resize: vertical; min-height: 80px; }
      .afaq-form-submit {
        padding: 12px 32px;
        border-radius: 9999px;
        background: var(--text-dark);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
      }
      .afaq-form-submit:hover {
        background: var(--gold);
        box-shadow: 0 0 20px rgba(119,90,25,0.2);
      }

      /* ===== FOOTER ===== */
      .afaq-footer {
        background: #fff;
        border-top: 1px solid rgba(196,199,199,0.4);
        padding: 80px 24px;
        text-align: center;
      }
      .afaq-footer-inner {
        max-width: 1280px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 40px;
      }
      .afaq-footer-brand {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 500;
        color: var(--text-dark);
      }
      .afaq-footer-brand img { height: 40px; width: auto; }
      .afaq-footer-nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 24px 48px;
      }
      .afaq-footer-nav a {
        font-size: 14px;
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.25s;
      }
      .afaq-footer-nav a:hover { color: var(--gold); }
      .afaq-footer-copy {
        font-size: 11px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: rgba(68,71,72,0.5);
      }

      /* ===== REVEAL ===== */
      .afaq-reveal {
        opacity: 0;
        transform: translateY(24px);
        transition: all 0.7s cubic-bezier(0.16,1,0.3,1);
      }
      .afaq-reveal.active {
        opacity: 1;
        transform: translateY(0);
      }

      /* ===== RESPONSIVE ===== */
      @media (max-width: 900px) {
        .afaq-layout { flex-direction: column; padding-top: 40px; }
        .afaq-nav {
          flex: none;
          width: 100%;
          position: static;
          max-height: none;
          display: flex;
          flex-wrap: wrap;
          gap: 4px;
          margin-bottom: 24px;
        }
        .afaq-nav-item { font-size: 13px; padding: 8px 12px; }
        .afaq-contact { grid-template-columns: 1fr; }
        .afaq-price-grid { grid-template-columns: 1fr; max-width: 400px; }
        .afaq-sol-grid { grid-template-columns: 1fr 1fr; }
      }
      @media (max-width: 600px) {
        .afaq-hero { padding: 80px 20px 60px; }
        .afaq-sol-grid { grid-template-columns: 1fr; }
        .afaq-contact-qrs { justify-content: center; }
      }
    </style>

    <!-- ===== HERO ===== -->
    <section class="afaq-hero" aria-label="<?php echo esc_attr($hero_tagline); ?>">
      <div class="afaq-hero-tag"><?php echo esc_html($hero_tagline); ?></div>
      <h1 class="afaq-hero-title"><?php echo esc_html($hero_title); ?></h1>
      <p class="afaq-hero-desc"><?php echo wp_kses_post($hero_desc); ?></p>
    </section>

    <!-- ===== LAYOUT: Nav + Content ===== -->
    <div class="afaq-layout">
      <!-- Sticky Nav -->
      <nav class="afaq-nav" aria-label="FAQ 分类导航">
        <?php foreach ($categories as $i => $cat): ?>
        <a class="afaq-nav-item" href="#afaq-cat-<?php echo $i; ?>"><?php echo esc_html($cat['title'] ?? ''); ?></a>
        <?php endforeach; ?>
        <?php if (!empty($solutions)): ?><a class="afaq-nav-item" href="#afaq-sol">解决方案</a><?php endif; ?>
        <?php if (!empty($pricing_tiers)): ?><a class="afaq-nav-item" href="#afaq-price">定价</a><?php endif; ?>
        <a class="afaq-nav-item" href="#afaq-contact">联系我们</a>
      </nav>

      <!-- Content -->
      <div class="afaq-content" id="afaq-content-start">
        <?php foreach ($categories as $i => $cat): ?>
        <div class="afaq-category afaq-reveal" id="afaq-cat-<?php echo $i; ?>">
          <h2 class="afaq-category-title"><?php echo esc_html($cat['title'] ?? ''); ?></h2>
          <?php foreach (($cat['items'] ?? []) as $j => $item): ?>
          <div class="afaq-item" data-cat="<?php echo $i; ?>" data-idx="<?php echo $j; ?>">
            <div class="afaq-question" role="button" tabindex="0" aria-expanded="false">
              <span><?php echo esc_html($item['question'] ?? ''); ?></span>
              <span class="afaq-icon" aria-hidden="true">+</span>
            </div>
            <div class="afaq-answer"><?php echo wp_kses_post(wpautop($item['answer'] ?? '')); ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ===== SOLUTIONS ===== -->
    <?php if (!empty($solutions)): ?>
    <section class="afaq-section" id="afaq-sol" aria-label="<?php echo esc_attr($sol_title); ?>">
      <h2 class="afaq-section-title afaq-reveal"><?php echo esc_html($sol_title); ?></h2>
      <p class="afaq-section-desc afaq-reveal"><?php echo esc_html($sol_desc); ?></p>
      <div class="afaq-sol-grid">
        <?php foreach ($solutions as $s): ?>
        <a class="afaq-sol-card afaq-reveal" href="<?php echo esc_url($s['url'] ?? ''); ?>">
          <span class="afaq-sol-icon" aria-hidden="true"><?php echo esc_html($s['icon'] ?? ''); ?></span>
          <div class="afaq-sol-card-title"><?php echo esc_html($s['title'] ?? ''); ?></div>
          <div class="afaq-sol-card-desc"><?php echo esc_html($s['desc'] ?? ''); ?></div>
        </a>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

    <!-- ===== PRICING ===== -->
    <?php if (!empty($pricing_tiers)): ?>
    <section class="afaq-section bg-warm" id="afaq-price" aria-label="<?php echo esc_attr($price_title); ?>">
      <h2 class="afaq-section-title afaq-reveal"><?php echo esc_html($price_title); ?></h2>
      <p class="afaq-section-desc afaq-reveal"><?php echo esc_html($price_desc); ?></p>
      <div class="afaq-price-grid">
        <?php foreach ($pricing_tiers as $t): ?>
        <div class="afaq-price-card afaq-reveal<?php echo !empty($t['badge']) ? ' featured' : ''; ?>">
          <?php if (!empty($t['badge'])): ?>
          <div class="afaq-price-badge"><?php echo esc_html($t['badge'] ?? ''); ?></div>
          <?php endif; ?>
          <div class="afaq-price-name"><?php echo esc_html($t['title'] ?? ''); ?></div>
          <div class="afaq-price-amount"><?php echo esc_html($t['price'] ?? ''); ?></div>
          <?php if (!empty($t['period'])): ?>
          <div class="afaq-price-period"><?php echo esc_html($t['period'] ?? ''); ?></div>
          <?php endif; ?>
          <div class="afaq-price-desc"><?php echo esc_html($t['desc'] ?? ''); ?></div>
          <ul class="afaq-price-features">
            <?php foreach (($t['features'] ?? []) as $f): ?>
            <li><?php echo esc_html($f); ?></li>
            <?php endforeach; ?>
          </ul>
          <a class="afaq-price-btn" href="<?php echo esc_url($t['url'] ?? ''); ?>"><?php echo esc_html($t['btn'] ?? ''); ?></a>
        </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

    <!-- ===== CONTACT ===== -->
    <section class="afaq-section" id="afaq-contact" aria-label="联系我们">
      <div class="afaq-contact">
        <div class="afaq-contact-info afaq-reveal">
          <h3><?php echo esc_html($cont_title); ?></h3>
          <p><?php echo esc_html($cont_desc); ?></p>
          <div class="afaq-contact-detail"><span aria-hidden="true">📞</span> <span><?php echo esc_html($cont_phone); ?></span></div>
          <div class="afaq-contact-detail"><span aria-hidden="true">✉️</span> <a href="mailto:<?php echo esc_attr($cont_email); ?>"><?php echo esc_html($cont_email); ?></a></div>
          <?php if ($cont_qr1 || $cont_qr2): ?>
          <div class="afaq-contact-qrs">
            <?php if ($cont_qr1): ?>
            <div class="afaq-contact-qr-item">
              <img src="<?php echo esc_url($cont_qr1); ?>" alt="<?php echo esc_attr($cont_qr1_lbl); ?>">
              <span><?php echo esc_html($cont_qr1_lbl); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($cont_qr2): ?>
            <div class="afaq-contact-qr-item">
              <img src="<?php echo esc_url($cont_qr2); ?>" alt="<?php echo esc_attr($cont_qr2_lbl); ?>">
              <span><?php echo esc_html($cont_qr2_lbl); ?></span>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
        <div class="afaq-contact-form afaq-reveal">
          <h4>给我们留言</h4>
          <form action="#" method="post" onsubmit="alert('感谢您的留言！我们会尽快回复。');return false;">
            <div class="afaq-form-group">
              <label for="afaq-name">姓名</label>
              <input type="text" id="afaq-name" name="name" required placeholder="请输入您的姓名">
            </div>
            <div class="afaq-form-group">
              <label for="afaq-email">邮箱</label>
              <input type="email" id="afaq-email" name="email" required placeholder="请输入您的邮箱">
            </div>
            <div class="afaq-form-group">
              <label for="afaq-msg">留言</label>
              <textarea id="afaq-msg" name="message" required placeholder="请输入您的问题或需求..."></textarea>
            </div>
            <button type="submit" class="afaq-form-submit">发送留言</button>
          </form>
        </div>
      </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="afaq-footer">
      <div class="afaq-footer-inner">
        <div class="afaq-footer-brand">
          <?php if ($footer_logo): ?>
          <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php echo esc_attr($footer_brand); ?> logo">
          <?php else: ?>
          <?php echo esc_html($footer_brand); ?>
          <?php endif; ?>
        </div>
        <nav class="afaq-footer-nav" aria-label="Footer Navigation">
          <?php foreach ($footer_menu as $item): ?>
          <a href="<?php echo esc_url($item['url'] ?? ''); ?>"><?php echo esc_html($item['label'] ?? ''); ?></a>
          <?php endforeach; ?>
        </nav>
        <div class="afaq-footer-copy"><?php echo esc_html($footer_copyright); ?></div>
      </div>
    </footer>

    <!-- ===== SCRIPTS ===== -->
    <script>
    (function(){
      var root = document.getElementById('afaq-root');
      if (!root) return;

      // FAQ Accordion
      root.querySelectorAll('.afaq-question').forEach(function(q){
        q.addEventListener('click', function(){
          var answer = this.nextElementSibling;
          var isOpen = this.classList.toggle('open');
          answer.classList.toggle('open');
          this.setAttribute('aria-expanded', isOpen);
        });
        // Keyboard support
        q.addEventListener('keydown', function(e){
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.click();
          }
        });
      });

      // Sticky Nav Active State
      var navItems = root.querySelectorAll('.afaq-nav-item');
      var sections = [];
      navItems.forEach(function(item){
        var href = item.getAttribute('href');
        if (href && href.startsWith('#')) {
          var target = document.getElementById(href.substring(1));
          if (target) sections.push({ el: target, nav: item });
        }
      });

      function updateNav() {
        var scrollY = window.scrollY + 120;
        var activeFound = false;
        for (var i = sections.length - 1; i >= 0; i--) {
          var sec = sections[i];
          if (sec.el.offsetTop <= scrollY) {
            navItems.forEach(function(n){ n.classList.remove('active'); });
            sec.nav.classList.add('active');
            activeFound = true;
            break;
          }
        }
        if (!activeFound && navItems.length > 0) {
          navItems.forEach(function(n){ n.classList.remove('active'); });
          navItems[0].classList.add('active');
        }
      }

      window.addEventListener('scroll', updateNav, { passive: true });
      setTimeout(updateNav, 300);

      // Smooth scroll for nav items
      navItems.forEach(function(item){
        item.addEventListener('click', function(e){
          var href = this.getAttribute('href');
          if (href && href.startsWith('#')) {
            e.preventDefault();
            var target = document.getElementById(href.substring(1));
            if (target) {
              var offset = 100;
              var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
              window.scrollTo({ top: top, behavior: 'smooth' });
            }
          }
        });
      });

      // Scroll Reveal
      var revealObs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
          if (e.isIntersecting) {
            e.target.classList.add('active');
            revealObs.unobserve(e.target);
          }
        });
      }, { threshold: 0.08, rootMargin: '0px 0px -60px 0px' });

      root.querySelectorAll('.afaq-reveal').forEach(function(el){
        revealObs.observe(el);
      });
    })();
    </script>

    </div>
    <?php
    return ob_get_clean();
}
