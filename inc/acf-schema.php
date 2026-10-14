<?php
if (!defined('ABSPATH')) exit;

if (!function_exists('hireai_acf_schema')) {
    function hireai_acf_schema() {
        $text = function ($name, $label, $zh = '', $en = '') {
            return ['name' => $name, 'label' => $label, 'type' => 'text', 'zh' => $zh, 'en' => $en];
        };
        $area = function ($name, $label, $zh = '', $en = '') {
            return ['name' => $name, 'label' => $label, 'type' => 'textarea', 'rows' => 3, 'zh' => $zh, 'en' => $en];
        };
        $url = function ($name, $label, $value = '') {
            return ['name' => $name, 'label' => $label, 'type' => 'url', 'default_value' => $value];
        };
        $image = function ($name, $label) {
            return ['name' => $name, 'label' => $label, 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'];
        };
        $switch = function ($name, $label, $value = 0) {
            return ['name' => $name, 'label' => $label, 'type' => 'true_false', 'default_value' => (int) $value, 'ui' => 1];
        };

        $bilingual = function ($fields, $prefix, $count, $map) use ($text, $area, $url, $image, $switch) {
            for ($index = 1; $index <= $count; $index++) {
                $number = sprintf('%02d', $index);
                foreach ($map as $key => $definition) {
                    $fields[] = $definition($prefix . $number . '_' . $key, $prefix . $number . ' ' . $key);
                }
            }
            return $fields;
        };

        $groups = [];

        $home_fields = [
            $text('home_hero_kicker', 'Hero · Kicker', '工匠精神与算法', 'AETHER LUXE AI'),
            $text('home_hero_static', 'Hero · 前段标题', '重新定义', 'Redefining'),
            $text('home_hero_accent_primary', 'Hero · 金色词一', '数字', 'Digital'),
            $text('home_hero_accent_secondary', 'Hero · 金色词二', '生产力', 'Productivity'),
            $text('home_hero_accent', 'Hero · 备用金色词', '数字生产力', 'Digital Productivity'),
            $text('home_hero_subtitle', 'Hero · 副标题', '融合尖端科技与奢华质感，为您打造专属的数字员工矩阵。', 'Fusing cutting-edge technology with a luxurious aesthetic to craft your bespoke digital workforce.'),
            $image('home_hero_image', 'Hero · 图片'),
            $url('home_hero_cta_1_url', 'Hero · CTA 1 链接', '/ai-employees/'),
            $text('home_hero_cta_1_label', 'Hero · CTA 1 文本', '探索系列', 'Explore Series'),
            $url('home_hero_cta_2_url', 'Hero · CTA 2 链接', '/contact/'),
            $text('home_hero_cta_2_label', 'Hero · CTA 2 文本', '定制咨询', 'Custom Consultation'),
            $text('home_intro_kicker', 'Intro · Kicker', '工匠精神与算法', 'Craftsmanship Meets Algorithm'),
            $text('home_intro_title', 'Intro · 标题', '塑造超越物理边界的存在。', 'Shaping existence beyond physical boundaries.'),
            $area('home_intro_description', 'Intro · 描述', '我们结合传统奢华的严谨工艺与神经网络的无限可能。', 'We combine the rigor of traditional luxury with the infinite potential of neural networks.'),
            $text('home_intro_cta_label', 'Intro · 按钮文本', '探索更多', 'Explore More'),
            $url('home_intro_cta_url', 'Intro · 按钮链接', '/ai-employees/'),
            $text('home_products_heading_kicker', '员工列表 · Kicker', '限量神经元系列', 'Limited Neural Series'),
            $text('home_products_heading_title', '员工列表 · 标题', 'AI 数字员工', 'AI Digital Employees'),
            $text('home_products_heading_subtitle', '员工列表 · 副标题', '每一位数字员工都拥有独特的灵魂、技能与能力。', 'Each digital employee brings a unique soul, refined skills, and unmatched capabilities.'),
            $text('home_products_cta_label', '员工列表 · 链接文本', '探索更多', 'Explore More'),
            $url('home_products_cta_url', '员工列表 · 链接', '/ai-employees/'),
            $text('home_solutions_heading_kicker', '方案列表 · Kicker', '行业赋能', 'Industry Empowerment'),
            $text('home_solutions_heading_title', '方案列表 · 标题', 'AI 解决方案', 'AI Solutions'),
            $text('home_solutions_heading_subtitle', '方案列表 · 副标题', '面向多元行业，打造量身定制的智能解决方案。', 'Bespoke intelligent solutions across industries.'),
            $text('home_solutions_cta_label', '方案列表 · 链接文本', '探索更多', 'Explore More'),
            $url('home_solutions_cta_url', '方案列表 · 链接', '/ai-solutions/'),
            $text('home_cases_heading_kicker', '案例列表 · Kicker', '真实案例', 'Real Results'),
            $text('home_cases_heading_title', '案例列表 · 标题', '案例与洞察', 'Cases & Insights'),
            $text('home_cases_heading_subtitle', '案例列表 · 副标题', '展示数字员工带来的商业成果。', 'Business outcomes delivered by digital employees.'),
            $text('home_cases_cta_label', '案例列表 · 链接文本', '探索更多', 'Explore More'),
            $url('home_cases_cta_url', '案例列表 · 链接', '/cases-insights/'),
            $text('home_case_major_label', '主案例 · 标签', '精选', 'Featured'),
            $text('home_case_major_title', '主案例 · 标题', '高净值客户留存率提升', 'Higher retention for high-net-worth clients'),
            $area('home_case_major_description', '主案例 · 描述', '了解数字员工如何提升客户留存率与满意度。', 'See how a digital employee improves retention and satisfaction.'),
            $image('home_case_major_image', '主案例 · 图片'),
            $url('home_case_major_url', '主案例 · 链接', '/cases-insights/'),
            $text('home_faq_heading_kicker', 'FAQ · Kicker', '常见问题', 'FAQ'),
            $text('home_faq_heading_title', 'FAQ · 标题', '解答关于数字员工的疑虑。', 'Answers to your questions about digital employees.'),
            $text('home_faq_cta_label', 'FAQ · 链接文本', '探索更多', 'Explore More'),
            $url('home_faq_cta_url', 'FAQ · 链接', '/faq/'),
            $text('home_cta_kicker', 'CTA · Kicker', '开启旅程', 'NEXT STEP'),
            $text('home_cta_title', 'CTA · 标题', '开启您的 AI 雇佣之旅', 'Begin Your AI Hiring Journey'),
            $text('home_cta_description', 'CTA · 描述', '与我们的团队对话，打造专属您的数字员工阵容。', 'Speak with our team and craft a digital workforce made for you.'),
            $text('home_cta_primary_label', 'CTA · 主按钮', '联系我们', 'Contact Us'),
            $url('home_cta_primary_url', 'CTA · 主链接', '/contact/'),
            $text('home_cta_secondary_label', 'CTA · 次按钮', '预约咨询', 'Book Consultation'),
            $url('home_cta_secondary_url', 'CTA · 次链接', '/contact/'),
        ];
        for ($index = 1; $index <= 3; $index++) {
            $number = sprintf('%02d', $index);
            $home_fields[] = $text("home_product_{$number}_title", "员工卡 {$number} · 标题");
            $home_fields[] = $text("home_product_{$number}_description", "员工卡 {$number} · 描述");
            $home_fields[] = $text("home_product_{$number}_badge", "员工卡 {$number} · 徽标");
            $home_fields[] = $image("home_product_{$number}_image", "员工卡 {$number} · 图片");
            $home_fields[] = $url("home_product_{$number}_url", "员工卡 {$number} · 链接");
            $home_fields[] = $text("home_product_{$number}_cta_label", "员工卡 {$number} · 按钮文本", '探索更多', 'Explore More');
        }
        for ($index = 1; $index <= 4; $index++) {
            $number = sprintf('%02d', $index);
            $home_fields[] = $text("home_solution_{$number}_title", "方案卡 {$number} · 标题");
            $home_fields[] = $text("home_solution_{$number}_tag", "方案卡 {$number} · 标签");
            $home_fields[] = $area("home_solution_{$number}_description", "方案卡 {$number} · 描述");
            $home_fields[] = $image("home_solution_{$number}_image", "方案卡 {$number} · 图片");
            $home_fields[] = $url("home_solution_{$number}_url", "方案卡 {$number} · 链接");
            $home_fields[] = $switch("home_solution_{$number}_enabled", "方案卡 {$number} · 显示", $index <= 3);
        }
        for ($index = 1; $index <= 2; $index++) {
            $number = sprintf('%02d', $index);
            $home_fields[] = $text("home_case_minor_{$number}_tag", "次要案例 {$number} · 标签");
            $home_fields[] = $text("home_case_minor_{$number}_title", "次要案例 {$number} · 标题");
            $home_fields[] = $area("home_case_minor_{$number}_description", "次要案例 {$number} · 描述");
            $home_fields[] = $image("home_case_minor_{$number}_image", "次要案例 {$number} · 图片");
            $home_fields[] = $url("home_case_minor_{$number}_url", "次要案例 {$number} · 链接");
        }
        for ($index = 1; $index <= 10; $index++) {
            $number = sprintf('%02d', $index);
            $home_fields[] = $text("home_faq_{$number}_question", "FAQ {$number} · 问题");
            $home_fields[] = $area("home_faq_{$number}_answer", "FAQ {$number} · 回答");
        }
        $groups[] = hireai_make_bilingual_group('group_home_v38', '首页 · v3.8', $home_fields, [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]]);

        $employee_fields = [
            $text('employees_hero_note', 'Hero · 说明', '在职数字员工 · 精英岗位', 'Curated roles, on call'),
            $text('employees_hero_kicker', 'Hero · Kicker', '数字工坊', 'The Atelier'),
            $text('employees_hero_title', 'Hero · 标题', '精英数字解决方案', 'Elite Digital Solutions'),
            $text('employees_hero_subtitle', 'Hero · 副标题', 'AI 主导流程，人类交付成果。', 'AI-led process, Human-delivered results.'),
            $image('employees_hero_image', 'Hero · 图片'),
            $text('employees_filter_kicker', '筛选 · Kicker', '筛选', 'Filter'),
            $text('employees_filter_title', '筛选 · 标题', '按角色浏览', 'Browse by role'),
            $text('employees_filter_all_label', '筛选 · 全部', '全部', 'All'),
            $text('employees_process_kicker', '流程 · Kicker', '合作流程', 'Process'),
            $text('employees_process_title', '流程 · 标题', '四步交付', 'Four steps'),
            $text('employees_process_note', '流程 · 说明', '透明、可控、可复盘。', 'Transparent, controlled, reviewable.'),
            $text('employees_cta_heading', 'CTA · 标题', '准备好重新定义人性了吗？', 'Ready to Redefine Humanity?'),
            $text('employees_cta_subtitle', 'CTA · 副标题', '加入运用专属生态的领袖精英之列。', 'Join the exclusive echelon of leaders.'),
            $text('employees_cta_primary_label', 'CTA · 主按钮', '开启旅程', 'Start The Journey'),
            $url('employees_cta_primary_url', 'CTA · 主链接', '/contact/'),
            $text('employees_cta_secondary_label', 'CTA · 次按钮', '下载品牌手册', 'Download Brand Book'),
            $url('employees_cta_secondary_url', 'CTA · 次链接', '/cases-insights/'),
        ];
        for ($index = 1; $index <= 12; $index++) {
            $number = sprintf('%02d', $index);
            $employee_fields[] = $text("employees_row_{$number}_kicker", "员工行 {$number} · Kicker");
            $employee_fields[] = $text("employees_row_{$number}_title", "员工行 {$number} · 标题");
            $employee_fields[] = $area("employees_row_{$number}_description", "员工行 {$number} · 描述");
            $employee_fields[] = $image("employees_row_{$number}_image", "员工行 {$number} · 图片");
            $employee_fields[] = $url("employees_row_{$number}_url", "员工行 {$number} · 链接");
            $employee_fields[] = $text("employees_row_{$number}_cta_label", "员工行 {$number} · 按钮", '了解详情', 'Learn More');
        }
        for ($index = 1; $index <= 4; $index++) {
            $number = sprintf('%02d', $index);
            $employee_fields[] = $text("employees_process_{$number}_title", "流程 {$number} · 标题");
            $employee_fields[] = $area("employees_process_{$number}_description", "流程 {$number} · 描述");
        }
        $groups[] = hireai_make_bilingual_group('group_employees_v38', 'AI数字员工页 · v3.8', $employee_fields, [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-ai-employees.php']]]);

        $solutions_fields = [
            $text('solutions_hero_kicker', 'Hero · Kicker', 'BESPOKE SOLUTIONS', 'BESPOKE SOLUTIONS'),
            $text('solutions_hero_title', 'Hero · 标题', 'AI方案商城', 'AI Solutions Marketplace'),
            $text('solutions_hero_subtitle', 'Hero · 副标题', '雇佣顶尖数字智脑，赋能企业未来。', 'Hire elite digital minds to empower your business.'),
            $image('solutions_hero_image', 'Hero · 图片'),
            $text('solutions_hero_primary_label', 'Hero · 主按钮', '定制方案', 'Custom Plan'),
            $url('solutions_hero_primary_url', 'Hero · 主链接', '/contact/'),
            $text('solutions_hero_secondary_label', 'Hero · 次按钮', '查看案例', 'View Cases'),
            $url('solutions_hero_secondary_url', 'Hero · 次链接', '/category/cases/'),
            $text('solutions_card_cta_label', '方案卡 · 按钮', '探索更多', 'Explore More'),
            $text('solutions_empty_message', '空状态', '该分类下暂无解决方案。', 'No solutions in this category yet.'),
            $text('solutions_invite_kicker', '邀请 · Kicker', '邀约礼遇', 'INVITE & EARN'),
            $text('solutions_invite_title', '邀请 · 标题', '邀约礼遇 / Invite & Earn', 'Invite & Earn'),
            $text('solutions_invite_subtitle', '邀请 · 副标题', '分享专属邀请码，共享 AI 服务。', 'Share your exclusive invite code.'),
            $text('solutions_invite_code', '邀请 · 链接/代码', 'hireaipeople.com/invite/VIP001', 'hireaipeople.com/invite/VIP001'),
            $text('solutions_invite_copy_label', '邀请 · 复制按钮', '复制链接', 'Copy Link'),
            $text('solutions_invite_reward_amount', '邀请 · 奖励金额', '￥500', '¥500'),
            $text('solutions_invite_reward_description', '邀请 · 奖励说明', '双方均可获得 ￥500 定制额度。', 'Both accounts receive a ¥500 credit.'),
            $text('solutions_invite_steps_heading', '邀请 · 步骤标题', '如何运作', 'How It Works'),
            $text('product_operative', '方案商品 · 执行说明'),
            $text('solutions_cta_kicker', 'CTA · Kicker', 'NEXT STEP', 'NEXT STEP'),
            $text('solutions_cta_title', 'CTA · 标题', '准备好为您的品牌升级了吗？', 'Ready to Elevate Your Brand?'),
            $text('solutions_cta_subtitle', 'CTA · 副标题', '与顾问一起制定方案。', 'Create your plan with a consultant.'),
            $text('solutions_cta_primary_label', 'CTA · 主按钮', '预约咨询', 'Book a Consultation'),
            $url('solutions_cta_primary_url', 'CTA · 主链接', '/contact/'),
            $text('solutions_cta_secondary_label', 'CTA · 次按钮', '查看案例', 'View Cases'),
            $url('solutions_cta_secondary_url', 'CTA · 次链接', '/category/cases/'),
        ];
        for ($index = 1; $index <= 6; $index++) {
            $number = sprintf('%02d', $index);
            $solutions_fields[] = $text("solutions_filter_{$number}_label", "筛选 {$number} · 名称");
            $solutions_fields[] = $text("solutions_filter_{$number}_slug", "筛选 {$number} · Slug");
            $solutions_fields[] = $switch("solutions_filter_{$number}_enabled", "筛选 {$number} · 显示", $index <= 4);
        }
        for ($index = 1; $index <= 3; $index++) {
            $number = sprintf('%02d', $index);
            $solutions_fields[] = $text("solutions_invite_step_{$number}_title", "步骤 {$number} · 标题");
            $solutions_fields[] = $area("solutions_invite_step_{$number}_description", "步骤 {$number} · 描述");
        }
        $groups[] = hireai_make_bilingual_group('group_solutions_v38', 'AI解决方案页 · v3.8', $solutions_fields, [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-ai-solutions.php']]]);

        $cases_fields = [
            $text('cases_hero_kicker', 'Hero · Kicker', '智慧档案', 'INTELLIGENCE ARCHIVE'),
            $text('cases_hero_title_prefix', 'Hero · H1 前段', '打造数字 ', 'Crafting Digital '),
            $text('cases_hero_title_emphasis', 'Hero · H1 强调', '人文', 'Humanity'),
            $text('cases_hero_description', 'Hero · 描述', '技术精度与传承美学的交汇之处。', 'Where technical precision meets heritage aesthetic.'),
            $text('cases_section_heading', '案例区 · 标题', '卓越案例', 'Collaborative Excellence'),
            $text('cases_insights_title', '洞察 · 标题', '最新思考', 'Latest thinking'),
            $text('cases_insights_description', '洞察 · 描述', 'AI 组织、交付与治理实践。', 'AI operations, delivery, and governance.'),
            $text('cases_cta_title', 'CTA · 标题', '准备好重塑人性了吗？', 'Ready to Redefine Humanity?'),
            $text('cases_cta_description', 'CTA · 描述', '与专属顾问讨论方案。', 'Discuss your roadmap with a consultant.'),
            $text('cases_cta_primary_label', 'CTA · 按钮', '预约咨询', 'Book a Consultation'),
            $url('cases_cta_primary_url', 'CTA · 链接', '/contact/'),
        ];
        for ($index = 1; $index <= 4; $index++) {
            $number = sprintf('%02d', $index);
            $cases_fields[] = $text("cases_card_{$number}_badge", "案例卡 {$number} · 徽标");
            $cases_fields[] = $text("cases_card_{$number}_title", "案例卡 {$number} · 标题");
            $cases_fields[] = $area("cases_card_{$number}_description", "案例卡 {$number} · 描述");
            $cases_fields[] = $image("cases_card_{$number}_image", "案例卡 {$number} · 图片");
            $cases_fields[] = $url("cases_card_{$number}_url", "案例卡 {$number} · 链接", '/category/cases/');
        }
        for ($index = 1; $index <= 3; $index++) {
            $number = sprintf('%02d', $index);
            $cases_fields[] = $text("cases_insight_{$number}_category", "洞察卡 {$number} · 分类");
            $cases_fields[] = $text("cases_insight_{$number}_title_prefix", "洞察卡 {$number} · 标题前段");
            $cases_fields[] = $text("cases_insight_{$number}_title_emphasis", "洞察卡 {$number} · 标题强调");
            $cases_fields[] = $text("cases_insight_{$number}_title_suffix", "洞察卡 {$number} · 标题后段");
            $cases_fields[] = $area("cases_insight_{$number}_description", "洞察卡 {$number} · 描述");
            $cases_fields[] = $text("cases_insight_{$number}_read_time", "洞察卡 {$number} · 阅读时长");
            $cases_fields[] = $image("cases_insight_{$number}_image", "洞察卡 {$number} · 图片");
            $cases_fields[] = $url("cases_insight_{$number}_url", "洞察卡 {$number} · 链接", '/category/insights/');
        }
        $groups[] = hireai_make_bilingual_group('group_cases_insights_v38', '案例&洞察页 · v3.8', $cases_fields, [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-cases-insights.php']]]);

        $card_fields = [
            $text('case_card_badge', '案例卡 · 徽标'),
            $image('case_card_cover_image', '案例卡 · 封面'),
            $text('insight_card_category', '洞察卡 · 分类'),
            $text('insight_card_read_time', '洞察卡 · 阅读时长'),
            $image('insight_card_cover_image', '洞察卡 · 封面'),
            $text('single_faq_group', 'FAQ · 分组'),
        ];
        $groups[] = hireai_make_bilingual_group('group_content_card_v38', '文章卡片 · v3.8', $card_fields, [
            [['param' => 'post_taxonomy', 'operator' => '==', 'value' => 'category:cases']],
            [['param' => 'post_taxonomy', 'operator' => '==', 'value' => 'category:insights']],
        ]);

        $faq_fields = [
            $text('faq_hero_kicker', 'Hero · Kicker', 'THE ATELIER', 'THE ATELIER'),
            $text('faq_hero_title', 'Hero · 标题', '常见问题', 'Frequently Asked'),
            $text('faq_hero_subtitle', 'Hero · 副标题', '深入了解合作模式、财务结构与安全协议。', 'Partnership, financial, and security details.'),
            $image('faq_hero_image', 'Hero · 图片'),
            $text('faq_hero_caption', 'Hero · 说明', '我们的数字工坊', 'Our Atelier'),
            $text('faq_search_placeholder', '搜索 · 占位符', '输入关键词检索…', 'Search questions…'),
            $text('faq_empty_message', '搜索 · 空状态', '未找到匹配的问题。', 'No matching questions found.'),
            $text('faq_group_01_label', '分组 1', '合作方式', 'Partnership'),
            $text('faq_group_02_label', '分组 2', '财务', 'Finance'),
            $text('faq_group_03_label', '分组 3', '隐私和安全', 'Privacy & Security'),
            $text('faq_group_04_label', '分组 4', '其他', 'Other'),
            $text('faq_cta_kicker', 'CTA · Kicker', '仍有疑问？', 'STILL CURIOUS?'),
            $text('faq_cta_title', 'CTA · 标题', '准备好重新定义人性了吗？', 'Ready to Redefine Humanity?'),
            $text('faq_cta_subtitle', 'CTA · 副标题', '加入专属生态的领袖精英之列。', 'Join the exclusive echelon of leaders.'),
            $text('faq_cta_primary_label', 'CTA · 主按钮', '开启旅程', 'Start The Journey'),
            $url('faq_cta_primary_url', 'CTA · 主链接', '/contact/'),
            $text('faq_cta_secondary_label', 'CTA · 次按钮', '下载品牌手册', 'Download Brand Book'),
            $url('faq_cta_secondary_url', 'CTA · 次链接', '/cases-insights/'),
        ];
        for ($index = 1; $index <= 12; $index++) {
            $number = sprintf('%02d', $index);
            $faq_fields[] = [
                'name' => "faq_item_{$number}_group", 'label' => "FAQ {$number} · 分组", 'type' => 'select',
                'choices' => ['partnership' => '合作方式', 'finance' => '财务', 'privacy-security' => '隐私和安全', 'other' => '其他'],
                'default_value' => 'other', 'return_format' => 'value',
            ];
            $faq_fields[] = $text("faq_item_{$number}_question", "FAQ {$number} · 问题");
            $faq_fields[] = $area("faq_item_{$number}_answer", "FAQ {$number} · 回答");
        }
        $groups[] = hireai_make_bilingual_group('group_faq_v38', '常见问题页 · v3.8', $faq_fields, [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-faq.php']]]);

        $contact_fields = [
            $text('contact_hero_kicker', 'Hero · Kicker', '联系', 'CONTACT'),
            $text('contact_hero_title', 'Hero · 标题', '联络', 'Contact'),
            $text('contact_hero_subtitle', 'Hero · 副标题', '与我们的管家联系，定制 AI 数字员工。', 'Connect with our concierges to curate your AI workforce.'),
            $image('contact_hero_image', 'Hero · 图片'),
            $text('contact_email', '邮箱', 'concierge@hireaipeople.com', 'concierge@hireaipeople.com'),
            $text('contact_wechat', '微信', 'hireai-official', 'hireai-official'),
            $text('contact_address', '地址', '中国 · 上海', 'Shanghai, China'),
            $text('contact_map_label', '地图 · 文本', '查看地图', 'View Map'),
            $url('contact_map_url', '地图 · 链接', 'https://uri.amap.com/search?keyword=Shanghai%2C%20China'),
            $image('contact_wechat_qr', '微信二维码'),
            $text('contact_form_heading', '表单 · 标题', '咨询表单', 'Inquiry Form'),
            $text('contact_form_name_label', '表单 · 姓名标签', '姓名', 'Name'),
            $text('contact_form_email_label', '表单 · 邮箱标签', '邮箱', 'Email'),
            $text('contact_form_phone_label', '表单 · 电话标签', '电话', 'Phone'),
            $text('contact_form_wechat_label', '表单 · 微信标签', '微信', 'WeChat'),
            $text('contact_form_message_label', '表单 · 内容标签', '需求描述', 'Inquiry Details'),
            $text('contact_form_submit_label', '表单 · 提交', '提交咨询', 'Send Inquiry'),
            $text('contact_form_success_message', '表单 · 成功', '您的咨询已发送。', 'Your inquiry has been sent.'),
            $text('contact_form_invalid_message', '表单 · 无效', '请填写正确的姓名、邮箱与需求描述。', 'Please provide a valid name, email, and message.'),
            $text('contact_form_error_message', '表单 · 错误', '发送失败，请稍后重试。', 'Something went wrong. Please retry.'),
            $text('contact_concierge_heading', '管家 · 标题', '专属管家', 'Direct Concierge'),
            $text('contact_concierge_description', '管家 · 描述', '如有即时需求或定制咨询，请直接联系。', 'For immediate assistance or bespoke inquiries.'),
            $url('contact_social_xhs_url', '小红书链接'),
            $url('contact_social_instagram_url', 'Instagram 链接'),
            $url('contact_social_facebook_url', 'Facebook 链接'),
            $text('contact_cta_title', 'CTA · 标题', '准备好重新定义服务了吗？', 'Ready to Redefine Humanity?'),
            $text('contact_cta_description', 'CTA · 描述', '与专属数字员工一同迈入未来。', 'Step into the future of luxury service.'),
            $text('contact_cta_button_label', 'CTA · 按钮', '开始咨询', 'Begin Consultation'),
            $url('contact_cta_button_url', 'CTA · 链接', '/contact/'),
        ];
        $groups[] = hireai_make_bilingual_group('group_contact_v38', '联系页 · v3.8', $contact_fields, [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-contact.php']]]);

        $detail_fields = [
            $text('employee_detail_name', '姓名', '全国统一者', 'Apex Strategist'),
            $text('employee_detail_role', '角色', '数字员工', 'Digital Employee'),
            $text('employee_detail_kicker', 'Kicker', '战略精英', 'Strategic Elite'),
            $area('employee_detail_soul', '灵魂', '', ''),
            $area('employee_detail_skill', '技能', '', ''),
            $area('employee_detail_capabilities', '核心能力', '', ''),
            $image('employee_detail_image', '形象图'),
            $text('employee_detail_price', '起步档', '', ''),
            $text('employee_detail_retainer', '合作条款', '', ''),
            $text('employee_detail_cases_label', '案例 · 标签', '查看相关案例', 'View Related Cases'),
            $text('employee_detail_cases_kicker', '案例 · Kicker', '精选案例', 'SELECTED CASES'),
            $text('employee_detail_cases_heading', '案例 · 标题', '这位员工的擅长领域', 'Where this employee excels'),
            $url('employee_detail_cases_url', '案例 · 链接', '/category/cases/'),
            $text('employee_detail_cases_empty_message', '案例 · 空状态', '案例发布后展示于此。', 'Cases will be showcased here once published.'),
            $text('employee_detail_engagement_heading', '合作 · 标题', '起步档与合作条款', 'Retainer & Terms'),
            $text('employee_detail_cta_heading', 'CTA · 标题', '准备好部署了吗？', 'Ready to deploy?'),
            $text('employee_detail_cta_subtitle', 'CTA · 副标题', '加入正在使用专属数字员工的领袖行列。', 'Join leaders using bespoke digital employees.'),
            $text('employee_detail_cta_button_label', 'CTA · 按钮', '开启旅程', 'Start The Journey'),
            $url('employee_detail_cta_button_url', 'CTA · 链接', '/contact/'),
        ];
        for ($index = 1; $index <= 4; $index++) {
            $number = sprintf('%02d', $index);
            $detail_fields[] = $text("employee_detail_term_{$number}", "合作条款 {$number}");
        }
        $groups[] = hireai_make_bilingual_group('group_employee_detail_v38', '数字员工详情 · v3.8', $detail_fields, [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-employee-detail.php']],
            [['param' => 'post_category', 'operator' => '==', 'value' => 'category:ai-employee']],
        ]);

        $single_fields = [
            $text('employee_single_role', '角色', '数字员工', 'Digital Employee'),
            $text('employee_single_kicker', 'Kicker', '战略精英', 'Strategic Elite'),
            $area('employee_single_soul', '灵魂', '', ''),
            $area('employee_single_skill', '技能', '', ''),
            $area('employee_single_capabilities', '核心能力', '', ''),
            $image('employee_single_image', '形象图'),
            $text('employee_single_button_label', '按钮 · 文本', '咨询', 'Inquire'),
            ['name' => 'employee_single_button_style', 'label' => '按钮 · 样式', 'type' => 'select', 'choices' => ['auto' => '自动', 'solid' => 'Solid', 'ghost' => 'Ghost'], 'default_value' => 'auto', 'return_format' => 'value'],
            $text('employee_single_cta_label', '次按钮 · 文本'),
            $url('employee_single_cta_url', '次按钮 · 链接', '/contact/'),
            $text('single_background_heading', '背景资料 · 标题', '背景资料', 'Background'),
        ];
        $groups[] = hireai_make_bilingual_group('group_single_v38', '文章/员工卡 · v3.8', $single_fields, [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'post']],
            [['param' => 'post_category', 'operator' => '==', 'value' => 'category:ai-employee']],
        ]);

        return $groups;
    }
}
