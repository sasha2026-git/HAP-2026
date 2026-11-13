# AGENTS.md — 聘AI / HireAIPeople 官网（HAP-2026）

> 本文件是接手本项目的唯一可靠记忆。Codex CLI 启动时自动读取。
> 每次迭代结束必须更新「变更记录」和「已知坑」。
>
> **修订说明（Echo 修改 2026-11-13）**：本文件已由 Echo 对照真实仓库（`/tmp/codex-projects/hireaipeople-theme`）与线上站点（hireaipeople.com）逐条核验。文中带「（Echo 修改 2026-11-13…）」标注的条目为本次更正；核验一致的部分未作改动。

---

## 1. 项目概况

| 项 | 内容 |
| -------- | -------- |
| 项目 | 聘AI（HireAIPeople）官网 = AURELIAN AI 品牌站 |
| 域名 | https://hireaipeople.com |
| 仓库 | `git@github.com:sasha2026-git/HAP-2026.git` |
| 工作分支 | **main**（WordPress 跟踪 main，不是 dev） |
| 本地工作副本 | `/tmp/codex-projects/hireaipeople-theme`（**仓库根 = 主题根**） |
| 当前版本 | v3.11.15（以 `style.css` 的 `Version:` 为唯一来源） |
| 线上实际生效版本 | **v3.11.14**（2026-11-13 01:54 由 Sasha 升级完成；Echo 实测首页 `style.css?ver=3.11.14`，与仓库一致 ✅）。**每次发版后请回填此字段**（Echo 修改 2026-11-13） |
| 部署环境 | 阿里云香港 ECS + 宝塔面板（IP / SSH / 面板凭据**只找 Sasha**，禁止写进仓库） |
| 负责人 | Sasha（决策/发布）；Echo（内容/运营）；Codex CLI（代码） |

⚠️ **安全红线：服务器 IP、SSH、面板密码、API Token 一律不写进本文件、不写进任何提交。**

---

## 2. 技术栈

| 层 | 选型 |
| -------- | -------- |
| CMS | WordPress + 自定义**子主题** `HireAIPeople Child`（`Template: hello-elementor`） |
| 主题性质 | ⚠️ **子主题**，父主题 = hello-elementor。不要当独立主题改，不要动父主题 |
| 可视化编辑 | ACF（字段组 + Options Page）+ 短码；全站图文必须后台可改 |
| 多语言 | Polylang（已启用，`/{lang}/` 前缀；中英双物理页架构，见 §7） |
| 电商 | WooCommerce（`woocommerce/` 下有模板覆盖） |
| 更新分发 | Plugin Update Checker（`lib/Puc`）→ GitHub Release 自动检测 |
| 设计系统 | Playfair Display（英）/ Noto Serif SC（中）+ Inter + Noto Sans SC（本地 woff2）；**浅底金棕 VI**：底色 #F9F9F9 / 主文字 #1A1C1C / 品牌金 #775A19 / 亮金 #E9C176 / 浅灰面板 #F4F3F3 / 细线 #E8E5DF（Echo 修改 2026-11-13：原稿写「象牙白 #F5F0E8 / 炭黑 #1A1A2E / 香槟金 #C9A96E」属黑金旧版，与主题 `:root` 及线上首页实测不符，已按现行 VI 更正） |

---

## 3. 目录结构（真实）

```text
HAP-2026/                      # ← 仓库根 == 主题根（没有 hireaipeople-theme/ 子目录）
├── style.css                  # 主题头 + 全站样式；Version 字段是版本唯一来源
├── functions.php              # 核心：ACF 注册、helper、Polylang 迁移、缓存 hook（108 个函数）
├── front-page.php             # 首页（不是短码）
├── header.php / footer.php
├── page-about.php             # 关于我们
├── page-ai-employees.php      # AI 数字员工列表
├── page-employee-detail.php   # 数字员工详情
├── page-ai-solutions.php      # AI 解决方案
├── page-cases-insights.php    # 案例&观点
├── page-faq.php               # FAQ
├── page-contact.php           # 联系
├── category-cases.php         # 分类：案例
├── category-insights.php      # 分类：洞察
├── category.php / archive.php / single.php / page.php / 404.php
├── template-parts/            # 卡片/行/CTA/FAQ 片段（含 fallback-* 无数据兜底）
├── lib/                       # Puc（更新器）、plugin-update-checker、faq-data.php、load-v5p7.php
├── assets/                    # fonts(本地woff2) / css / js / img / content
├── woocommerce/               # WooCommerce 模板覆盖
├── aurelian-faq-plugin/       # FAQ 插件源码（v3.0.1）
├── aurelian-blog-plugin/      # 案例&观点插件源码（v2.1.1）
├── scripts/publish-wp-theme.sh# ★ 唯一发布脚本 v2.0（9 步门禁）
├── audit-reports/             # 历史审计证据（只增不改）
├── qa-report-*.md             # 各版本 QA 报告（根目录，只增不改）
├── design-qa.md               # 设计验收记录
└── PUBLISH-V300.sh            # ⚠️ 老脚本，已废弃，勿用
```

**禁止**：改 `assets/` 编译产物；覆盖 `audit-reports/`、`qa-report-*.md`（历史证据）。

---

## 4. 短码（真实存在的只有这些）

| 短码 | 来源 | 用途 |
| -------- | -------- | -------- |
| `[aurelian_faq]` | aurelian-faq-plugin v3.0.1 | FAQ 手风琴 |
| `[aurelian_blog]` | aurelian-blog-plugin v2.1.1 | 案例&观点列表 |

⚠️ **没有 `[aurelian_home]`**。首页走 `front-page.php` 模板，不要去找短码。
⚠️ **没有 `[aurelian_blog_header]`**（Echo 修改 2026-11-13：全仓 `add_shortcode` 只有 `aurelian_blog` 与 `aurelian_faq` 两个，原稿多列了此项；列表页头部由 `page-cases-insights.php` 直接输出）。

---

## 5. 字段层：10 个必需 helper（⛔ 禁止删除）

`functions.php` 中以下函数被 header/footer/各页面/模板片段广泛调用，**删除或重命名任意一个 = 整站白屏（v3.0.8 事故）**：

```
hireai_lang_suffix      hireai_field        hireai_field_lang
hireai_image            hireai_image_lang   hireai_link
hireai_link_lang        site_field          site_link
site_image_url
```

改 `functions.php` 后必做：
```bash
git diff functions.php | grep '^-function '     # 列出所有被删函数
git grep -n "被删函数名("                        # 确认无调用才能删
```
**觉得重复就用 alias / wrapper，绝不删。**

---

## 6. 设计系统铁律（视觉差异最显眼，后台一刷新就看到）

- 字体：Playfair Display（英标题）+ **Noto Serif SC（中文标题）** + Inter（英正文）+ **Noto Sans SC（中文正文）**，**必须 `assets/fonts/` 本地 woff2 + `font-display: swap`**
- 已本地化字体文件：`PlayfairDisplay-Variable` / `InterVariable(+Italic)` / `NotoSansSC-Regular|Bold` / `NotoSerifSC-Regular|Bold|Variable` / `HankenGrotesk-Variable` / `Manrope-Variable`（Codex 补 2026-11-13：原 §6 只写 Playfair+Inter，与 §2 及实际 `assets/fonts/` 不一致）
- ⛔ 禁用 `fonts.googleapis.com` / `fonts.gstatic.com`（被墙）
- ⛔ 禁用 `cdn.tailwindcss.com`（被墙）
- ⛔ 禁用 Material Symbols 字形引用 → **一律内联 SVG**（CDN 失败会变豆腐块）
- 留白：`.hireai-fp__section` 之间固定 **120px**，不用 `clamp()` 模糊值
- 自检（必须全部返回 0 行）：
```bash
grep -rE "material-symbols-outlined|<span class=\"material" *.php template-parts/*.php
grep -rE "fonts.googleapis|fonts.gstatic|cdn.tailwindcss" *.php functions.php
```

---

## 7. Polylang 多语言铁律（v3.5.7-p25 血坑）

- Polylang 会**自动创建翻译分类**：给中文文章挂 `cases`(cat 51) → 自动建 `cases-en`(cat 136) → 文章被移走
- ⛔ **WP_Query 禁止写死 `category_name => 'xxx'`**
- ✅ 一律用 `tax_query` + 多 slug IN（slug 比 cat ID 稳定）：
```php
'tax_query' => [[
    'taxonomy' => 'category',
    'field'    => 'slug',
    'terms'    => ['cases', 'cases-en', '案例', '案例&洞察'],
    'operator' => 'IN',
]]
```
- `save_post` 清缓存白名单用 **cat ID 数组**，不用 slug 数组
- ⛔ 不要靠"禁用 Polylang sync"解决（影响面太大）
- 双物理页架构见 `functions.php` 的 `hireai_polylang_*` 系列（`hireai_polylang_pages_migrate()` / `_rollback()`）

---

## 8. 发布流程（每次发版照抄，一步不能漏）

### 8.1 标准命令
```bash
cd /tmp/codex-projects/hireaipeople-theme
# 1) 先同步 style.css Version 字段 = 目标版本
# 2) 跑脚本（自动 9 步门禁）
bash scripts/publish-wp-theme.sh
```

### 8.2 分支铁律
`commit → push main → 打 tag → push tag → Release → ZIP`
（WP 只认 main；提交到别的分支 = 后台永远看不到更新）

### 8.3 三重验证（发布后立刻跑，禁止凭印象宣布成功）
```bash
git ls-remote origin "refs/tags/vX.Y.Z^{}"        # tag 必须指向含代码的 commit
gh release view vX.Y.Z --json isDraft,isLatest    # 必须 isDraft=false, isLatest=true
grep '^Version' style.css                          # 必须 == tag 去掉 v
unzip -l HAP-2026-vX.Y.Z.zip | grep -E "front-page|functions|page-faq"
```

### 8.4 血泪清单
- `gh release create` 默认可能 draft → 必须显式 `--draft=false`
- 旧版 Release 必须 `--latest=false`，否则用户拉到旧版
- ZIP 必须含真实代码（不是 1 行 .gitignore）
- 发布后**只回 Release 链接**，Sasha 没说要 ZIP 就不要发 ZIP

---

## 9. 缓存 / PHP-FPM（"更新了但页面没变化" 90% 是这里）

- 真实环境：**PHP 8.4** + **服务名 `php-fpm-84.service`**（Sasha 2026-11-13 实测确认 ✅）。⚠️ 原稿写的 `php-fpm-80.service` 是旧 PHP 8.0 时代的服务名，**已作废，不要再照抄**（Echo 修改 2026-11-13）。
- 标准命令（Sasha 2026-11-13 确认服务名为 `php-fpm-84.service`）：
```bash
sudo systemctl reload php-fpm-84.service
```
- 改完代码 / 主题升级后页面无变化 → 让 Sasha 执行上面的 reload
- 原因：opcache + PHP-FPM 多子进程下 `opcache_reset()` / `opcache_invalidate()` **只影响当前子进程**，不可靠（`functions.php:4696` 有完整注释）
- ⛔ 不要先猜"LiteSpeed 缓存""缓存插件"——先 reload

---

## 10. 已知坑（踩过的，别再踩）

| 现象 | 根因 | 解法 |
| -------- | -------- | -------- |
| XML-RPC 报 500「标题摘要为空」 | 用了 `title`/`description` | 用 `post_title`/`post_content`/`post_excerpt` |
| REST 建 `product`/`menu-items` 报 403 | `rest_cannot_create` | 改用 page + parent；菜单走 `wp_navigation` |
| product 打 category 成功但 count=0 | `category` 默认只注册给 post | `register_taxonomy_for_object_type()` |
| `_elementor_data` 写入后 REST 看不到 | protected meta | 查 DB 确认；改父级要删重建一次性写入 |
| 整站 0 字节响应 / OS 卡死 | MySQL OOM（4G 内存无 swap） | buffer 256M、max_connections 30、加 swap、关 snapd |
| 主题升级后前台无变化 | opcache / PHP-FPM 子进程 | `sudo systemctl reload php-fpm-84.service`（PHP 8.4，Sasha 2026-11-13 确认） |
| 文章挂了分类但不显示 | Polylang 自动建翻译 cat | `tax_query` 多 slug IN（见 §7） |
| 升级后白屏 Fatal error | `functions.php` 函数被删 | 见 §5，10 个 helper 禁删 |
| 图标显示成方块 | Material Symbols CDN 被墙 | 改内联 SVG（见 §6） |
| Echo 看不到 plugins.php | 角色权限受限 | 插件/设置类操作交给 Codex 或 Sasha |

---

## 11. 改码流程（硬性）

1. **建站 / 改站 / 改代码一律由 Codex CLI + `auto-site-builder` + product-design 审计完成**
2. 职责按 agent 拆开（Codex 补 2026-11-13：Echo 上一版把两者混写，容易误读）：
   - **Codex agent**：派发 Codex CLI 任务、监控、**接管发布动作**（`git push` / `gh release create` / ZIP 上传，因沙箱无网络）、写文档
   - **Echo / 其他 OpenClaw agent**：派发任务、监控、内容验收、写文档。⛔ **不做 `git push`、不打 tag、不建 Release**（2026-11-11 铁律）
   - **所有人**：⛔ 不手写 `.php` / `.css` 代码（见第 3 条）
3. ⛔ **禁止因为 Codex 慢就手写代码代替**（v3.0.2 事故）
4. Codex 卡住 → 至少等 30 分钟 → 反馈 Sasha 决定，不自行 kill 后手改
5. 每次改完必须过 product-design 审计再交付

---

## 12. Git 安全自检（每次 push 前）

```bash
git ls-files | grep -iE "wp-config|secret|\.env|api[_-]?key|credential|settings"   # 必须为空
```
`.gitignore` 已含：`wp-config.php` `*.sql` `*.pem` `*.key` `.env*` `settings.yml` `*.orig` `/specs-review/` `/V305-FIX-SUMMARY.md` `/WORKSPACE-NAV.md` `*.bak-*`
Codex 调试产生的临时文件（`*-SUMMARY.md`、`specs-review/`、`*.bak`）**绝不入库**；dirty 自动 commit 用 `git add -u`，不用 `git add -A`。

---

## 13. 验收标准

- [ ] 全站图文 WP 后台可视化编辑（ACF / Customizer）
- [ ] 移动端 + 桌面端响应式正常，无横向溢出
- [ ] 对照 `design-qa.md` 通过
- [ ] 字体本地化 + 无被墙 CDN + 无 Material Symbols 字形
- [ ] section 留白 120px 达标
- [ ] 多语言切换中英双向正常，分类文章都能显示
- [ ] 性能：资源压缩、WebP、缓存
- [ ] SEO：title/description/canonical/sitemap/schema
- [ ] `php -l` 全部通过；10 个 helper 函数存在
- [ ] 交付：main 已同步 + tag + Release（非 draft / Latest）+ ZIP 含真实代码 + 变更说明

---

## 14. 品牌与内容约定

- 数字员工 5 人（**Sasha 2026-11-13 确认名单无误**；内容在 WP 后台 ACF 字段维护，代码里不硬编码）：灵韵 Lyra / 韦特 Victor / 泰拉 Tara / 安然 Ann / 程杰 Jay；旧名字全部过期
- 出图：浅香槟金 + 灰银，禁暖黄；人物克制简洁
- 出图色值（Echo 修改 2026-11-13 补充）：亮金 `#E9C176` / 品牌金 `#775A19` / 底色 `#F9F9F9` / 主文字 `#1A1C1C`；金为缎面哑光，禁镜面反射与高饱和橙金
- 对外文案：温和理性，不贬低同行与用户；发布前过「中文去 AI 味」

---

## 15. 变更记录

| 日期 | 变更 | 执行者 |
| -------- | -------- | -------- |
| 2026-11-13 | Tech 创建初版草稿（未入库） | Tech |
| 2026-11-13 | Codex 核对真实仓库后改正：目录结构、子主题属性、短码、发布脚本、移除服务器 IP、补齐 Polylang/PHP-FPM/helper/安全/发布三重验证等铁律 | Codex |
| 2026-11-13 | **Echo 交叉核对（真实仓库 + 线上站点）后修正 6 处**：①§2 设计系统色值由黑金旧版改为现行浅底金棕（#F9F9F9/#1A1C1C/#775A19/#E9C176）②§4 删除不存在的 `[aurelian_blog_header]` ③§2 字体栈补 Noto Sans SC / Noto Serif SC ④§11 删除 Echo 的 git push / gh release 权限（与 2026-11-11 铁律冲突）。另新增：§1「线上实际生效版本」字段、§14 出图色值。⑤§9 + §10 按 Sasha 确认更正：PHP 版本为 **8.4**，原 `php-fpm-80.service` 服务名作废 ⑥§1 线上版本已由 Sasha 升级至 v3.11.14，Echo 实测确认一致 | Echo |
| 2026-11-13 | **Codex 二次复核 Echo 版**：逐条实测验证 Echo 的 6 处修正（色值 `#f9f9f9/#1a1c1c/#775a19/#e9c176/#f4f3f3/#e8e5df` 全部命中 style.css、旧黑金 3 值 0 命中；`add_shortcode` 全仓只有 `aurelian_blog`/`aurelian_faq`，确认 `[aurelian_blog_header]` 不存在；Noto Sans/Serif SC woff2 已本地化；线上 `style.css?ver=3.11.14` 实测一致）→ **Echo 6 处全部正确**。另补 3 处：§6 字体清单补 Noto 并列出实际 woff2 文件、§11 拆分 Codex/Echo 职责避免误读、§9 标注 php-fpm 服务名为待验证项 | Codex |
| 2026-11-13 | Sasha 确认 php-fpm 服务名 = **`php-fpm-84.service`**（PHP 8.4），§9 / §10 已按实测值定稿（取消「待验证」标注） | Sasha + Codex |
| 2026-11-13 | **v3.11.15**：「关于我们」页三步流程默认文案（中英）对齐 AI 数字员工页，仅更新 `hireai_about_process_defaults()` 与 ACF `about_process_1..3` 默认值 | Codex |
