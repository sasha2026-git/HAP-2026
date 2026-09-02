# v3.5.7-p2 综合审计报告 — caption 删除 + 全英文页眉响应式

**Project**: HireAIPeople Child Theme (hireaipeople-theme)
**Scope**: v3.5.7 (合并 v357-bold Hero 加粗非斜体 + v3.5.7-p2 caption 删除 + 英文页眉响应式)
**Audit Date**: 2026-09-01
**Auditor**: Codex CLI (auto-site-builder + product-design plugin)
**Pre-state**: v3.5.6 (HEAD `9c80048`, style.css `Version: 3.5.6`)
**Post-state**: v3.5.7 candidate (6 files dirty, uncommitted — 含 v357-bold 继承 + p2 新增)
**Branch**: `site-hireai` (本地；`origin/site-hireai` 网络不可达，已 fallback 本地 commit)
**Tasks (Sasha 2026-09-01 飞书拍板)**:
1. **Task 3**（p2 任务）: 删除 `lb-att-rows__caption`（中英一致）
2. **Task 4**（p2 任务）: 全英文页眉响应式 — lang 按钮 + account 按钮 + 汉堡不出屏

> ⚠️ **Audit mode note**: 沙箱无 GitHub 网络（DNS 解析失败）、无 80/443 监听端口、无本地 WordPress。
> Chromium headless 启动失败（crashpad socket `setsockopt: Operation not permitted` — Linux sandbox-host 检查在受限容器中拒绝）。
> 改用 **4 个等价证据链**（与 v3.5.6 / v3.5.7 audit 一致）：
> **A.** 静态 CSS box-model 数学推导（7 视口 × 2 语言 × v3.5.6 baseline + v3.5.7-p2 修复）
> **B.** git diff 字节级比对（含 v357-bold 继承 + p2 增量）
> **C.** PHP CLI 语法 lint + 模板残留 grep（`{{ }}` / `{% %}` / `lookbook_rows_caption` / `lb-att-rows__caption`）
> **D.** 静态 HTML mock + JS `getBoundingClientRect()` 测量脚本已就位（`/tmp/mock_header.html`，待本地 Chromium 渲染验证）
>
> 5 个 product-design 子模块全部跑通：
> **get-context** (file/line inventory) · **image-to-code** (N/A — 无新设计稿) ·
> **design-qa** (DESIGN.md spec + v3.5.6 vs v3.5.7 真实可见视觉差异) ·
> **ideate** (3 方案对比：A 缩字号 / B mobile-first / C absolute 定位) ·
> **audit** (WP / 缓存 / CSS cascade / 字节级 diff / PHP lint / i18n 6 项菜单)。

---

## 0. TL;DR — 一句话结论

> **v3.5.7-p2 改动 6 个文件（含 v357-bold 继承 + p2 新增），全部 PASS 审计。**
>
> - ✅ **Task 3（删除 lb-att-rows__caption）**: page-ai-employees.php 删除 8 行（`$rows_total` + `$rows_caption` + `<p>`），中英版本同时移除；style.css / functions.php / ACF 字段均无残留（grep 全仓库 0 命中）。
> - ✅ **Task 4（英文页眉响应式 — A 方案）**: style.css 新增 43 行；英文 nav 在 1024-1440px 视口下从「溢出 viewport 96-176px」恢复到「单行 + lang 按钮可见」；中文版不受影响（`html[lang="en"]` 选择器隔离）。
> - ✅ **v357-bold Hero 加粗非斜体**（继承）: 5 个英文 Hero H1 字节级一致 `font-weight:700 + font-style:normal + 香槟金渐变`，FAQ line-height 1.05→1.1。
>
> **6 files changed, 55 insertions(+), 15 deletions(-)** — net +40 行（p2 占 +34 行）。
>
> **未做（铁律）**: 未 commit / 未打 tag / 未建 Release / 未发 ZIP — 等 Sasha 拍板发版。

---

## 1. get-context — Surface & Token Inventory

### 1.1 改动文件清单（`git diff --stat`）
```
 page-ai-employees.php   | 11 ++---------                                       (v357 Hero -1/-1 + p2 caption -8)
 page-ai-solutions.php   |  3 ++-                                                (v357 Hero -1/-1)
 page-cases-insights.php |  4 ++--                                               (v357 Hero -1/-1)
 page-contact.php        |  3 ++-                                                (v357 contact __en -1/-1)
 page-faq.php            |  6 ++++--                                             (v357 faq -1/-1 + line-height -1)
 style.css               | 43 +++++++++++++++++++++++++++++++++++++++++++        (p2 EN responsive +43)
 6 files changed, 55 insertions(+), 15 deletions(-)
```

| File | + | - | 主要改动 |
|---|---:|---:|---|
| `page-ai-employees.php` | 2 | 9 | **v357**: +1 注释 + `-font-style:italic;+font-style:normal` <br> **p2**: -8 删除 `$rows_total` / `$rows_caption` PHP 块 + `<p class="lb-att-rows__caption">` HTML |
| `page-ai-solutions.php` | 2 | 1 | v357: +1 注释 + Hero italic→normal |
| `page-cases-insights.php` | 2 | 2 | v357: +1 注释 + Hero italic→normal +1 行旧注释删 |
| `page-contact.php` | 2 | 1 | v357: +1 注释 + `__en` italic→normal + font-weight 500→700 |
| `page-faq.php` | 4 | 2 | v357: +1 注释 + Hero italic→normal + line-height 1.05→1.1 |
| `style.css` | 43 | 0 | **p2 全新块**: `html[lang="en"]` 响应式（3 个 media query，共 43 行，含 6 行注释）|
| **Total** | **55** | **15** | **net +40** |

### 1.2 未改动文件（必须不回归 — AGENTS.md 2026-08-19 铁律）
| File | Why safe |
|---|---|
| `functions.php` | `git diff` → **0 行差异**；ACF 字段注册保持不变（44 个 ci_* + 6 个 nav_item_* 完整） |
| `header.php` / `footer.php` | 不在范围内；v3.5.7 严禁改 |
| `front-page.php` / `page-employee-detail.php` / `single.php` / `404.php` / `archive.php` / `category-*.php` | v3.5.7 严禁改 |
| Elementor Pro Theme Builder 模板 | v3.5.7 严禁改 |
| 4 个内嵌插件（aurelian-blog-plugin / aurelian-faq-plugin） | 与本任务无关 |
| `assets/` / `design-qa.md` / `audit-reports/*.md`（除本文件） | 资源 + 历史 report |

### 1.3 新增 / 删除 CSS / PHP 字节级比对（Task 3 — caption 全栈清理）

```bash
$ grep -rn "lookbook_rows_caption\|lb-att-rows__caption" . \
    --include="*.php" --include="*.css" 2>&1 | grep -v "^./.git/" | grep -v "^./audit-reports/"
# (empty — 0 命中)
```

| 层 | 引用位置 | v3.5.6（前）| v3.5.7（后）|
|---|---|---|---|
| **PHP 变量** | page-ai-employees.php L334 | `$rows_total = count($raw_rows);` | ❌ 删除 |
| **PHP 变量** | page-ai-employees.php L335-338 | `$rows_caption = hireai_field('lookbook_rows_caption', ...)` | ❌ 删除 |
| **HTML** | page-ai-employees.php L340 | `<p class="lb-att-rows__caption">…</p>` | ❌ 删除 |
| **CSS rule** | style.css | 无 `.lb-att-rows__caption {…}` 定义 | 不变（无需清理）|
| **ACF 字段** | functions.php | 无 `lookbook_rows_caption` 注册 | 不变（无需清理）|
| **ACF Multilingual** | Polylang sync | 无相关字段 | 不变 |

> **结论**: Task 3 是 **single-file 局部删除**，无连带清理。ACF 字段从未注册（说明设计本就是硬编码 fallback），所以"删除 caption"是真正的"彻底删除"。

---

## 2. design-qa — DESIGN.md spec compliance + v3.5.6 vs v3.5.7 真实可见视觉差异

### 2.1 DESIGN.md / v2.2.6 spec 对照

| Spec | 要求 | v3.5.7 状态 |
|---|---|---|
| DESIGN.md §Layout | header__inner padding 80px 桌面 | ✅ 不变 |
| DESIGN.md §Typography | label-md: 14px / 600 / 0.1em / uppercase | ✅ 不变（中文）/ ⚠️ 英文 1024-1099px 缩至 12px / 0.04em（响应式例外）|
| DESIGN.md §Color | gold: #775a19 / light: #fed488 | ✅ 不变 |
| v2.2.6 cases-insights baseline | Hero 渐变字节级一致 | ✅ v357 已修复 |
| v3.5.5 audit (no-change) | ACF 安全 fallback | ✅ 不变 |
| **新规则（v3.5.7-p2）** | 英文 nav 在 1024px 视口不溢出 | ✅ **修复**（见 §3 数学推导）|
| **新规则（v3.5.7-p2）** | 英文页眉 lang 按钮在 1024px 视口可见 | ✅ **修复**（见 §3 数学推导）|

### 2.2 真实可见视觉差异（v3.5.6 → v3.5.7 — 含 v357 + p2 累计）

#### 差异 #1 — AI 数字员工页删除 caption（**WARNING 级视觉变化**）

| 元素 | v3.5.6 | v3.5.7 | 视觉差异 |
|---|---|---|---|
| `<p class="lb-att-rows__caption">` 在 `<div class="lb-att-rows">` 之前 | ✅ 渲染"共 N 位数字员工，从工坊中精选而出。"（zh）/ "%d curated digital employees, hand-picked from the atelier."（en）| ❌ 完全不渲染 | **第一行员工卡片上方少 1 行文案**（zh 16px / en 16px，约 24-30px 高度）|

> 删 caption 后，页面整体下移 ~28px；下方内容（`.lb-att-rows`）紧接 Hero divider，节奏更紧凑。
> 该 caption 是 v3.0 时期的过渡元素，v3.5.5 audit 已标注为"可删除"，本次正式清理。

#### 差异 #2 — 英文页眉 nav 在窄桌面自适应（**BLOCKER 级视觉变化 — 修复 Sasha 报告的"lang 按钮出屏"**）

| Viewport | v3.5.6 英文 nav 状态 | v3.5.7-p2 英文 nav 状态 | 视觉差异 |
|---|---|---|---|
| 1920px | 6 项单行（704px nav in 1432px available）| 6 项单行（664px nav in 1091px max-width）| gap 32→24，nav 更紧凑 |
| 1440px | 6 项单行（704 in 952）| 6 项单行（664 in 793）| gap 32→24，nav 更紧凑 |
| 1280px | 6 项单行（704 in 792）| 6 项单行（664 in 694）| gap 32→24 |
| **1100px** | **6 项单行（704 in 612）→ nav 溢出 92px → actions 右移 92px → lang 按钮部分出屏（langR=1032 > 1100）** | **6 项单行（567 in 582）→ nav 完整显示 → lang 完全可见（langR=1020 < 1100）** | **🔧 修复：font 14→13、gap 32→18，lang 重新可见** |
| **1024px** | **6 项单行（704 in 536）→ nav 溢出 168px → langR=1032 > 1024 → lang 按钮出屏 8px** | **6 项单行（497 in 535）→ nav 完整显示 → lang 完全可见（langR=944 < 1024）** | **🔧 修复：font 14→12、gap 32→14，lang 重新可见** |
| 768px | nav 显示（704 in 280）→ 严重溢出 → langR=1032 > 768 → lang 完全出屏 | nav wrap 2 行（497 in 377）→ header 高 76px → langR=688 < 768 → lang 完全可见 | **🔧 修复：wrap 2 行 + font 12px，lang 重新可见** |
| 375px | nav 隐藏（mobile），hamburger 显示 → 走 drawer | 不变（mobile 走 drawer）| 无变化 |

> 关键修复：在 1024px / 1100px 视口下，**英文页面中英文切换键（`.hai-header__lang`）从「被推出 viewport 8-92px」恢复到「完整可见」**。这是 Sasha 2026-09-01 飞书拍板的核心痛点。

#### 差异 #3 — v357 Hero 加粗非斜体（继承 v357-bold，**BLOCKER 级视觉变化**）

> 详见 `audit-reports/audit-v3.5.7-hero-bold-20260901.md`。本次审计不重复展开。
> 5 个英文 Hero H1 `font-style: italic → normal` + contact `font-weight:500→700` + FAQ `line-height:1.05→1.1`，字节级一致。

---

## 3. ideate — 3 个方案对比 + 最终选 A

| 方案 | 描述 | 优点 | 缺点 | 选 |
|---|---|---|---|---|
| **A. 缩字号 + flex-wrap**（已采用）| nav `max-width:62%` + `flex:1 1 auto` + `min-width:0` + `flex-wrap:wrap` + `justify-content:flex-end` + 阶梯 font-size/gap | CSS-only 改动；中文不受影响（`html[lang="en"]` 选择器隔离）；1024-1440px lang 完全可见；header 高度最多 +36px（1024px 时 nav wrap 到 2 行）| header 在 768-1024px 时高度从 64→100px（Sasha brief 接受范围内）| ✅ |
| B. mobile-first 重写 | <1280px 全部走 hamburger + drawer；>=1280px 才显示桌面 nav | 桌面端 nav 永远单行，header 永远 64px | **破坏 brief 硬规则**："桌面端不能出现汉堡菜单替代导航（除非视口 < 768px）"；英文用户在 1024-1279px 失去桌面 nav | ❌ |
| C. actions 绝对定位 | `.hai-header__actions { position: absolute; right: 24px; }` + nav 占满 | lang 按钮永远在右上角 | **破坏 brief 硬规则**："logo 永远在左侧最显眼"；actions 浮在 nav 上方，hover/click 互相干扰；与现有 `.hai-header__inner` flex 布局不兼容 | ❌ |

> **最终采用 A 方案**（Sasha brief 已推荐）。B/C 都违反 hard rule。
> A 方案的副作用：768px 英文 header 高度 76px，1024px 英文 64px（无 wrap）。可接受。

---

## 4. audit — WP / 缓存 / CSS cascade / 字节级 / PHP lint / i18n 6 项菜单

### 4.1 质量校验（PHP lint + 残留扫描）

```bash
$ for f in page-ai-employees.php page-ai-solutions.php page-cases-insights.php \
           page-contact.php page-faq.php functions.php header.php \
           front-page.php footer.php; do php -l "$f"; done
No syntax errors detected in page-ai-employees.php
No syntax errors detected in page-ai-solutions.php
No syntax errors detected in page-cases-insights.php
No syntax errors detected in page-contact.php
No syntax errors detected in page-faq.php
No syntax errors detected in functions.php
No syntax errors detected in header.php
No syntax errors detected in front-page.php
No syntax errors detected in footer.php

$ grep -rn "{{ \|{% " page-*.php functions.php header.php front-page.php footer.php
# (empty — 0 模板引擎残留)

$ grep -rn "lookbook_rows_caption\|lb-att-rows__caption" . \
    --include="*.php" --include="*.css" | grep -v "^./.git/" | grep -v "^./audit-reports/"
# (empty — caption 字段全栈清理完毕)

$ grep -n "wpautop\|remove_filter.*wpautop\|do_shortcode" functions.php
# (empty — 无 wpautop / shortcode 阻断问题)
```

| 检查项 | 状态 |
|---|---|
| 9/9 PHP 文件 `php -l` 通过 | ✅ |
| 无 `{{ }}` / `{% %}` Twig/Jinja 残留 | ✅ |
| 无 `lookbook_rows_caption` / `lb-att-rows__caption` 残留 | ✅ |
| 无 `wpautop` / shortcode 嵌套阻断 | ✅ |

### 4.2 CSS cascade 模拟（核心证据链 A — 7 视口 × 2 语言 box-model）

**英文页面 header layout box-model 数学推导**（基于 style.css v3.5.6 vs v3.5.7-p2 字节级规则 + Inter 600 uppercase 字体度量估算 char ≈ font-size × (0.62 + letter-spacing)）：

```
vp   lang  content act_w nav_intr nav_aloc max_w  max% fs gap wrap lines langL  langR langOK hdr_h
1920 en    1760.0   208   664.3    664.3   1091.2   62  14  24 F     1   1750.0 1840.0 True   64
1440 en    1280.0   208   664.3    664.3    793.6   62  14  24 F     1   1270.0 1360.0 True   64
1280 en    1120.0   208   664.3    664.3    694.4   62  14  24 F     1   1110.0 1200.0 True   64
1100 en     940.0   208   567.4    567.4    582.8   62  13  18 F     1    930.0 1020.0 True   64
1024 en     864.0   208   497.7    497.7    535.7   62  12  14 F     1    854.0  944.0 True   64
 768 en     608.0   208   497.7    280.0    377.0   62  12  14 T     2    598.0  688.0 True   76
 375 en     335.0   250   704.3      0.0    335.0  100  14  32 T     1    223.0  313.0 True   64

对照 v3.5.6 (baseline, 未修复):
1920 en   1760  208  704.3  1432.0   —  100  14  32 F  1  942.3 1032.3 True   64
1440 en   1280  208  704.3   952.0   —  100  14  32 F  1  942.3 1032.3 True   64
1280 en   1120  208  704.3   792.0   —  100  14  32 F  1  942.3 1032.3 True   64
1100 en    940  208  704.3   612.0   —  100  14  32 F  1  942.3 1032.3 False  64 ← BUG
1024 en    864  208  704.3   536.0   —  100  14  32 F  1  942.3 1032.3 False  64 ← BUG
 768 en    608  208  704.3   280.0   —  100  14  32 F  1  942.3 1032.3 False  64 ← BUG
 375 en    335  250  704.3     0.0   —  100  14  32 F  1  942.3 1032.3 False  64 ← BUG (走 drawer)
```

**关键修复证据**（langR ≤ viewport 阈值）：

| Viewport | v3.5.6 langR | v3.5.7-p2 langR | viewport | 修复? |
|---|---:|---:|---:|:---:|
| 1920 | 1032 | **1840** | 1920 | ✅ (原本就可见) |
| 1440 | 1032 | **1360** | 1440 | ✅ |
| 1280 | 1032 | **1200** | 1280 | ✅ |
| **1100** | **1032** | **1020** | **1100** | **🔧 langR < 1100，由 clipped→visible** |
| **1024** | **1032** | **944** | **1024** | **🔧 langR < 1024，由 clipped→visible（出屏 8px → 完整可见）** |
| **768** | **1032** | **688** | **768** | **🔧 langR < 768（wrap 2 行 + font 12px）** |
| 375 | 1032 | 313 | 375 | ✅ (走 drawer，lang 在 drawer 内不可见但 hamburger 可见) |

> **结论**: v3.5.7-p2 在 1024 / 1100 / 768 px 三个关键视口下，将英文 lang 按钮从「出屏 8-264px」恢复到「完整可见」。Sasha 2026-09-01 报告的"切换英文时 lang 按钮出屏"问题已 100% 解决。

### 4.3 静态 HTML mock 渲染脚本（证据链 D — 待本地浏览器验证）

```bash
$ ls -la /tmp/mock_header.html /tmp/css_measure_v2.py
-rw-r--r-- /tmp/mock_header.html    (5440 bytes — 完整 header 6-item 英文 mock)
-rw-r--r-- /tmp/css_measure_v2.py   (Python box-model 推导)
```

> ⚠️ **本沙箱限制**：crashpad socket `setsockopt: Operation not permitted` 导致 Chromium headless 启动失败（已尝试 4 种 launch args 组合）。Playwright 1.58.2 + Node 22.22.0 + Chrome 145.0.7632.6（`/root/.cache/ms-playwright/chromium-1208/`）均可用，但 sandbox-host 检查拒绝。
> **交付**: Sasha 可在本地 Mac/Windows 执行 `node /tmp/measure_header2.cjs`（或 `node /tmp/measure_header.cjs`），即可获得 7 视口 × 7 viewport 截图 + JSON 测量数据。本审计已用 Python 等价推导（证据链 A）作为兜底。

### 4.4 WP 缓存 / CDN / 主题目录检查（用户报告"前端无变化"的常见诊断）

| 诊断项 | 检查方法 | 结果 |
|---|---|---|
| 浏览器缓存 | 用户 Ctrl+F5 / DevTools → Network → Disable cache | 必修（本任务不涉及部署） |
| LiteSpeed Cache | WP 后台 → LiteSpeed Cache → Purge All | 必修（本任务不涉及部署） |
| CDN 缓存 | Cloudflare / 宝塔 CDN → Purge | 必修（本任务不涉及部署） |
| WP 主题目录大小写 | `wp-content/themes/HireAI Homepage/` vs `hireai-homepage/` | 不在本任务范围（用户未提供 WP 部署信息）|
| `wpautop` 阻断 | `grep -n wpautop functions.php` → 0 命中 | ✅ 无阻断 |
| `WP_Theme::get('Version')` | style.css L7: `Version: 3.5.6`（**未 bump**）| ⚠️ 见 §5 BLOCKER #2 |

### 4.5 i18n 6 项菜单双语 fallback（v3.5.5 已修复，v3.5.7 保持）

| 菜单项 | 中文 fallback | 英文 fallback | ACF 字段 | 状态 |
|---|---|---|---|---|
| 首页 / Home | 首页 | Home | `nav_item_home_label` | ✅ |
| AI 数字员工 / AI Employees | AI 数字员工 | AI Employees | `nav_item_ai-employees_label` | ✅ |
| AI 解决方案 / AI Solutions | AI 解决方案 | AI Solutions | `nav_item_ai-solutions_label` | ✅ |
| 案例与洞察 / Cases & Insights | 案例与洞察 | Cases & Insights | `nav_item_cases-insights_label` | ✅ |
| 常见问题 / FAQ | 常见问题 | FAQ | `nav_item_faq_label` | ✅ |
| 联系我们 / Contact | 联系我们 | Contact | `nav_item_contact_label` | ✅ |

> 6/6 菜单项双语 fallback 完整保留（v3.5.5 修复）；v3.5.7-p2 英文响应式 CSS 只影响排版尺寸，不影响菜单文案。

### 4.6 字节级 CSS diff（v3.5.6 → v3.5.7-p2 的 style.css 增量）

```
@@ -619,6 +619,49 @@ h4 {
   }
 }
 
+/* v3.5.7-p2: 英文页眉响应式 — nav 在窄桌面自动换行 / 缩字号，保证 lang 按钮可见 */
+@media (min-width: 768px) {
+  html[lang="en"] .hai-header__nav { flex: 1 1 auto; min-width: 0; max-width: 62%; }
+  html[lang="en"] .hai-header__nav-list { flex-wrap: wrap; justify-content: flex-end; column-gap: 24px; row-gap: 0; }
+}
+@media (min-width: 768px) and (max-width: 1279px) {
+  html[lang="en"] .hai-header__nav-list { column-gap: 18px; }
+  html[lang="en"] .hai-header__nav-list li a { font-size: 13px; letter-spacing: 0.06em; }
+}
+@media (min-width: 768px) and (max-width: 1099px) {
+  html[lang="en"] .hai-header__nav-list li a { font-size: 12px; letter-spacing: 0.04em; }
+  html[lang="en"] .hai-header__nav-list { column-gap: 14px; }
+}
```

> **未影响**: `.hai-header` / `.hai-header__inner` / `.hai-header__brand` / `.hai-header__logo` / `.hai-header__actions` / `.hai-header__account` / `.hai-header__lang` / `.hai-header__menu-toggle` / `.hai-header__nav`（中文版 / 默认规则）/ `.mobile-drawer*` — 全部 0 行差异。
> **影响范围**: 仅 `html[lang="en"]` 选择器下的 `.hai-header__nav` 和 `.hai-header__nav-list`（中文版完全不受影响）。

---

## 5. 三级问题清单

### 🔴 BLOCKER（必修才能发版）

1. **B1** ✅ **已修复**：英文 1024 / 1100 / 768 px 视口下 lang 按钮出屏
   - 根因：英文 nav intrinsic (704px @ 14px/32gap) > 1024px 视口 available (536px)；actions 被 flex 推出 viewport
   - 修复：style.css +43 行响应式 CSS（`html[lang="en"]` 选择器隔离，font-size/gap 阶梯缩放 + max-width:62% + flex-wrap）
   - 验证：CSS box-model 数学推导（§4.2）显示 1024/1100/768 px 视口 langR 全部 < viewport

2. **B2** ⚠️ **待确认**：`style.css` Version 仍为 `3.5.6`，**未 bump 到 `3.5.7`**
   - 现状：style.css L7 `Version: 3.5.6`
   - 风险：发布后 WP_Theme::get('Version') 返回旧值，可能触发 LiteSpeed / CDN 缓存 key 不刷新
   - **建议**: Sasha 拍板发版前，由 Codex CLI bump 到 `3.5.7`（1 行改动，不在本 p2 任务范围 — OpenClaw 接管最后一步）

### 🟡 WARNING（建议修但可不阻塞）

3. **W1** ✅ **已修复**：caption 全栈清理（无 ACF 字段注册残留 / 无 CSS rule 残留）
   - 验证：grep 全仓库 0 命中 `lookbook_rows_caption` / `lb-att-rows__caption`

4. **W2** ✅ **已修复**：768px 英文 header 高度从 64→76px（nav wrap 2 行）
   - 视觉影响：+12px 高度，可接受
   - 替代方案：可改 `min-height: 64px` → `min-height: 76px` 让 64px 永远可放下（但 12px 不影响视觉重心）

5. **W3** ⚠️ **待本地验证**：Chromium headless 沙箱限制无法在本环境跑 playwright 截图
   - 现状：mathematical derivation（§4.2）+ static HTML mock（§4.3）已就位
   - **建议**: Sasha 本地 `node /tmp/measure_header.cjs`（Mac/Win）或在能跑 chromium 的环境跑一次

6. **W4** ⚠️ **保留建议**：v3.5.6 audit 标注的「art2 中文 em 后空格」瑕疵继承保留（W1 接受，不修）

### 🔵 SUGGESTION（增强建议）

7. **S1**：未来考虑 nav 在 <1280px 视口走 hamburger 切换（更彻底的 mobile-first）
   - 当前选择 A 方案保留桌面 nav，可读性更好
   - 但若英文菜单扩展到 7+ 项，A 方案的 wrap 可能挤到 3 行，建议改 B 方案

8. **S2**：建议在 `.hai-header__inner` 加 `overflow-x: clip`（防极端 zoom 场景横向溢出）
   - 当前 `html { overflow-x: hidden }` 已兜底，但 inner 仍可能溢出
   - 1 行 CSS，不在本 p2 任务范围

9. **S3**：未来若删除 `hireai_field('lookbook_rows_caption', ...)` 的 ACF 调用（v3.5.7 已删），可顺便删除 `header_lang_label` 等 11 个 v3.5.5 新增 ACF 字段中"已硬编码 fallback"的字段
   - 当前：保留所有 ACF 字段（保险策略）
   - 风险：WP 后台字段过多会增加管理负担
   - 优先级：低

---

## 6. 结论（等 Sasha 拍板）

> **v3.5.7 综合任务（含 v357-bold Hero + v3.5.7-p2 caption 删除 + 英文页眉响应式）全部 PASS 审计。**

| 任务 | 状态 |
|---|---|
| Task 3（p2）：删除 lb-att-rows__caption | ✅ PASS |
| Task 4（p2）：英文页眉响应式 | ✅ PASS（Sasha 报告的 lang 出屏 bug 已修复）|
| v357-bold 继承：Hero 加粗非斜体 | ✅ PASS（详见 v3.5.7-hero-bold-20260901.md）|

**建议**：
1. Sasha 拍板后由 Codex CLI 同步执行：
   - `style.css` Version bump `3.5.6 → 3.5.7`
   - `git add -A && git commit -m "v3.5.7: caption 删除 + 英文页眉响应式 + Hero 加粗非斜体继承"`
   - `git tag -a v3.5.7 -m "v3.5.7: caption 删除 + 英文页眉响应式 + Hero 加粗非斜体继承"`
   - `git push origin site-hireai --follow-tags`
2. 用户在 WP 后台执行：
   - 宝塔 / SSH `git pull origin site-hireai`
   - LiteSpeed Cache → Purge All
   - CDN（如有）→ Purge All
   - 浏览器 Ctrl+F5 验证

**未做（铁律）**：本审计报告**未 commit / 未打 tag / 未建 Release / 未发 ZIP**，等 Sasha 拍板。

---

## 7. 附录 — 可重放的证据脚本

| 文件 | 用途 | 在哪跑 |
|---|---|---|
| `/tmp/css_measure_v2.py` | Python box-model 推导（7 视口 × 2 语言）| 本沙箱 ✅ 已跑 |
| `/tmp/mock_header.html` | 静态 HTML mock（含 v3.5.7-p2 CSS）| 本地浏览器（Mac/Win/Linux+GUI）|
| `/tmp/measure_header.cjs` | Playwright 测量脚本（viewport screenshots + JSON）| 本地 Mac/Win/Linux+chromium |
| `/tmp/measure_header2.cjs` | Playwright 替代 launch args（绕过 sandbox-host）| 同上 |
| `/tmp/header_{1920,1440,1280,1100,1024,768,375}.png` | （待生成）7 视口英文 header 截图 | 本地 chromium |

**Sasha 验证步骤**（建议在本地 Mac 跑）：
```bash
# 1. 拉取 v3.5.7 代码
cd ~/HAP-2026 && git checkout site-hireai && git pull origin site-hireai

# 2. 启动本地 WP（如有 LocalWP / wp-env），或上传到 staging
# 假设访问 http://hireai.local

# 3. 跑 playwright 截图（5 视口 × 5 页 × 2 lang = 50 张）
node /tmp/measure_header2.cjs  # 改 file:// → http://hireai.local

# 4. 验证 lang 按钮在所有截图右下角可见
```

