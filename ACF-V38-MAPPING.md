# ACF v3.8 页面 → 区块 → 字段映射

## 规则

- 命名：`页面_区块_字段`；列表字段固定编号 `01`-`12`。
- 语言：每个文本字段生成 `字段_zh` / `字段_en`；URL、image、开关、select 为双语共用。
- 读取顺序：当前语言 → 无后缀字段 → 镜像另一语言。所有默认值只存字段注册表，模板不写兜底文案。
- 兼容：旧名只作读取别名，不注册到后台；canonical 字段为空时自动读旧 meta，已填内容不清空、不覆盖。
- 类型：仅 `text`、`textarea`、`image`、`url`、`true_false`、`select`，以及 ACF Free 可用的分组标签 `tab`；所有列表用编号普通字段，不用 Repeater。

## 字段组

### `group_home_v38` — front-page.php

| 区块 | canonical 字段 | 旧名别名 |
|---|---|---|
| Hero | `home_hero_kicker` | `fp_hero_kicker` |
| Hero | `home_hero_static` / `home_hero_accent_primary` / `home_hero_accent_secondary` / `home_hero_accent` | `fp_hero_*` |
| Hero | `home_hero_subtitle` / `home_hero_image` | `fp_hero_subtitle` / `fp_hero_image` |
| Hero CTA | `home_hero_cta_1_label` / `home_hero_cta_1_url` | `fp_hero_cta_1_title` / `fp_hero_cta_1_url` |
| Hero CTA | `home_hero_cta_2_label` / `home_hero_cta_2_url` | `fp_hero_cta_2_title` / `fp_hero_cta_2_url` |
| Intro | `home_intro_kicker` / `home_intro_title` / `home_intro_description` / `home_intro_cta_label` / `home_intro_cta_url` | `fp_intro_kicker` / `fp_intro_title` / `fp_intro_desc` / `fp_intro_cta_title` / `fp_intro_cta_url` |
| 员工卡 | `home_products_heading_kicker` / `home_products_heading_title` / `home_products_heading_subtitle` / `home_products_cta_label` / `home_products_cta_url` | `fp_products_*` |
| 员工卡 01-03 | `home_product_01_title` / `description` / `badge` / `image` / `url` / `cta_label` | `fp_prod1_*` |
| 员工卡 02-03 | 同 `home_product_02_*` / `home_product_03_*` | `fp_prod2_*` / `fp_prod3_*` |
| 方案 | `home_solutions_heading_*`、`home_solutions_cta_*` | `fp_solutions_*` |
| 方案 01-04 | `home_solution_01_title` / `tag` / `description` / `image` / `url` / `enabled` | `fp_sol1_*`；`enabled` 兼容旧 `fp_sol_static_repeater` |
| 案例 | `home_cases_heading_*`、`home_cases_cta_*`、`home_case_major_label` / `title` / `description` / `image` / `url` | `fp_cases_*` / `fp_case_major_*` |
| 次要案例 01-02 | `home_case_minor_01_tag` / `title` / `description` / `image` / `url`；02 同构 | `fp_case1_*` / `fp_case2_*` |
| FAQ | `home_faq_heading_kicker` / `title` / `cta_label` / `cta_url` | `fp_faq_kicker` / `fp_faq_title` / `fp_faq_explore_*` |
| FAQ 01-10 | `home_faq_01_question` / `answer`（10 组同构） | `fp_faq1_q` / `fp_faq1_a` |
| CTA | `home_cta_kicker` / `title` / `description` / `primary_label` / `primary_url` / `secondary_label` / `secondary_url` | `fp_cta_*` |

### `group_employees_v38` — page-ai-employees.php

| 区块 | canonical 字段 | 旧名别名 |
|---|---|---|
| Hero | `employees_hero_note` / `kicker` / `title` / `subtitle` / `image` | `lookbook_hero_*` |
| 筛选 | `employees_filter_kicker` / `title` / `all_label` | `lookbook_filter_*` |
| 员工行 01-12 | `employees_row_01_kicker` / `title` / `description` / `image` / `url` / `cta_label` | Free 行字段 `lookbook_employee_1_*`，Pro Repeater `lookbook_employees` / `lookbook_employees_en` |
| 流程 | `employees_process_kicker` / `title` / `note`；`employees_process_01_title` / `description` 至 04 | `lookbook_process_*` |
| CTA | `employees_cta_heading` / `subtitle` / `primary_label` / `primary_url` / `secondary_label` / `secondary_url` | `lookbook_cta_*` |

### `group_solutions_v38` — page-ai-solutions.php

| 区块 | canonical 字段 | 旧名别名 |
|---|---|---|
| Hero | `solutions_hero_kicker` / `title` / `subtitle` / `image` | `header_*`、旧页面 Hero |
| Hero CTA | `solutions_hero_primary_label` / `_url` / `secondary_label` / `_url` | `hero_cta_*` |
| 列表 | `solutions_card_cta_label` / `solutions_empty_message` | `card_cta_text` / `empty_text` |
| 筛选 01-06 | `solutions_filter_01_label` / `slug` / `enabled`；06 同构 | `solutions_filters` Repeater |
| 邀请 | `solutions_invite_kicker` / `title` / `subtitle` / `code` / `copy_label` / `reward_amount` / `reward_description` / `steps_heading` | `invite_*` |
| 步骤 01-03 | `solutions_invite_step_01_title` / `description`；03 同构 | `solutions_invite_steps` Repeater |
| CTA | `solutions_cta_kicker` / `title` / `subtitle` / `primary_label` / `primary_url` / `secondary_label` / `secondary_url` | `final_cta_*` |

### `group_cases_insights_v38` — page-cases-insights.php

| 区块 | canonical 字段 | 旧名别名 |
|---|---|---|
| Hero | `cases_hero_kicker` / `title` / `subtitle` / `image` / `caption` | `ci_hero_*` / `header_*` |
| Tabs | `cases_tab_cases_label` / `cases_tab_insights_label` | `ci_tab_*` |
| 列表 | `cases_empty_message` / `cases_more_label` / `cases_more_url` | `ci_*` |
| 洞察 | `cases_insights_kicker` / `title` / `description` / `more_label` / `more_url` / `empty_message` | `ci_insights_*` |
| CTA | `cases_cta_kicker` / `title` / `description` / `primary_label` / `primary_url` | `ci_consult_*` |

### `group_faq_v38` — page-faq.php

| 区块 | canonical 字段 | 旧名别名 |
|---|---|---|
| Hero | `faq_hero_kicker` / `title` / `subtitle` / `image` / `caption` | `header_*` / `faq_hero_caption` |
| 搜索 | `faq_search_placeholder` / `faq_empty_message` | `search_placeholder` / `empty_text` |
| 分组 | `faq_group_01_label` 至 `faq_group_04_label` | `faq_group_1_label` 至 4 |
| 问答 01-12 | `faq_item_01_group`（select） / `question` / `answer`；12 组同构 | `faq_items_zh` / `faq_items_en` Repeater |
| CTA | `faq_cta_kicker` / `title` / `subtitle` / `primary_label` / `primary_url` / `secondary_label` / `secondary_url` | `cta_*` |

### `group_contact_v38` — page-contact.php

| 区块 | canonical 字段 | 旧名别名 |
|---|---|---|
| Hero | `contact_hero_kicker` / `title` / `subtitle` / `image` | `header_*` / `contact_hero_image` |
| 联系方式 | `contact_email` / `contact_wechat` / `contact_address` / `contact_map_label` / `contact_map_url` / `contact_wechat_qr` | 原同名 / `wechat_qr` |
| 表单 | `contact_form_heading` / `name_label` / `email_label` / `phone_label` / `wechat_label` / `message_label` / `submit_label` / `success_message` / `invalid_message` / `error_message` | `form_*` |
| 管家 | `contact_concierge_heading` / `description` / `social_xhs_url` / `social_instagram_url` / `social_facebook_url` | `concierge_*` / `social_*` |
| CTA | `contact_cta_title` / `description` / `button_label` / `button_url` | `cta_*` |

### `group_employee_detail_v38` — page-employee-detail.php

| 区块 | canonical 字段 | 旧名别名 |
|---|---|---|
| 详情 | `employee_detail_role` / `kicker` / `soul` / `skill` / `capabilities` / `image` / `price` / `retainer` | `employee_*` / `product_retainer_label` |
| 相关案例 | `employee_detail_cases_label` / `cases_url` / `cases_empty_message` | `employee_cases_link*` |
| CTA | `employee_detail_cta_heading` / `subtitle` / `button_label` / `button_url` | `lookbook_cta_*`（规范员工页来源） |

### `group_single_v38` — single.php

| 区块 | canonical 字段 | 旧名别名 |
|---|---|---|
| 员工卡片 | `employee_single_role` / `kicker` / `soul` / `skill` / `capabilities` / `image` / `button_label` / `button_style` | `employee_*` |
| 通用文章 | `single_background_heading` / `single_faq_heading` | 渲染中的双语硬编码标题 |
| FAQ | `single_faq_enabled` / `single_faq_question` / `single_faq_answer` | `faq_group`（旧自由文本） |

### 文章/商品共享组

| 目的 | canonical 字段 |
|---|---|
| 案例卡 | `case_card_kicker` / `case_card_title` / `case_card_description` / `case_card_image` / `case_card_url` / `case_card_enabled` |
| 洞察卡 | `insight_card_kicker` / `title` / `description` / `image` / `url` / `enabled` |
| 方案商品 | `solution_product_title` / `description` / `tag` / `image` / `featured_enabled` / `featured_order` / `retainer_label` / `operative_note` |

## 注册与校验

- 唯一入口：`hireai_register_field_group()`；唯一执行点：`acf/init` 内 `hireai_register_schema_groups()`。
- 注册前校验 group key、field key、field name、location；字段 name 全站唯一。
- 校验失败写入 `hireai_acf_schema_errors` option 并在后台提示，不静默吞掉字段。

## 双向完整性

- `scripts/check-acf-linkage.php` 静态提取模板读取名，展开动态编号与 legacy 别名后和注册表差集比对。
- 退出码非 0 表示存在模板读取未注册或注册字段未被模板读取。

## 数据兼容

- v3.8 不执行 destructive migration，不删除旧 post meta。
- 文本读取链：新 canonical 当前语言 → 旧别名当前语言 → 新 canonical 无后缀 → 镜像语言。
- 图片/链接读取链：新 canonical 当前语言 → 旧别名当前语言 → 无后缀 → 镜像语言。
- 员工行额外兼容：先读 `employees_row_01_*` 至 `employees_row_12_*`；若整行为空，再读旧 Pro Repeater 导出的 `lookbook_employee_1_*` 至 `lookbook_employee_12_*`。
- FAQ 页额外兼容：先读 `faq_item_01_*` 至 `faq_item_12_*`；文章型 FAQ 的旧 `faq_group` 值映射到 `single_faq_group`。
- 后台会显示 v3.8 字段组；旧页面组已由 `hireai_register_field_group()` 隔离，不再注册，避免同页竞争。

## Sasha 后台验收清单

1. 打开「首页」编辑页，确认只有一个「首页 · v3.8」字段组；中英文 Tab 内逐项改 Hero、Intro、员工卡 01-03、方案卡 01-04、案例卡、FAQ 01-10、CTA。
2. 打开「AI数字员工」规范页，确认员工行 01-12 是普通字段；只填中文后切 EN，应镜像中文；填写 EN 对应行后，EN 显示 EN 覆盖值。
3. 打开「AI解决方案」规范页，改筛选 01-06 的启用开关、Slug、中英名称；确认 12 个商品卡仍由 WooCommerce 商品内容驱动。
4. 打开「案例&洞察」规范页，改 Hero H1 三段、案例/洞察 4+3 组固定卡和底部 CTA；清空卡片字段时前台不出现硬编码卡。
5. 打开「常见问题」规范页，改 4 个分组标签和 12 条问答；把问答分组 select 切到合作/财务/隐私/其他，确认前台分组同步。
6. 打开「联系」规范页，改邮箱、微信、二维码、表单标签和三条提交提示；提交一次测试表单验证 success/error 路径。
7. 打开 AI 员工详情页和任一 AI 员工文章，改角色、灵魂、技能、能力、价格、4 条合作条款、案例空状态和 CTA。
8. 运行 `php scripts/check-acf-linkage.php`，必须输出 `ACF linkage OK`。
9. 后台保存后清 LiteSpeed 全站缓存；中英文前台各刷新一次，确认修改即时对应。
