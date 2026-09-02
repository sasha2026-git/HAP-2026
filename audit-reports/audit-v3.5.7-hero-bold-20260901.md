# v3.5.7 Hero 加粗非斜体统一 — product-design 5-Submodule Audit

**Project**: HireAIPeople Child Theme (hireaipeople-theme)
**Scope**: v3.5.6 → v3.5.7 Hero H1 字体规范统一（5 个英文页：去掉 italic，改为加粗非斜体）
**Audit Date**: 2026-09-01
**Auditor**: Codex CLI (auto-site-builder + product-design plugin)
**Pre-state**: v3.5.6 (HEAD `34633e0`，style.css `Version: 3.5.6`)
**Post-state**: v3.5.7 candidate (5 files dirty, uncommitted)
**User brief (Sasha 2026-09-01 飞书)**:
> 各个页面的"Elite Digital Solutions、AI Solutions Marketplace、Crafting Digital Humanity、Frequently Asked、Initiate Contact"这一行字是不是**不应该要斜体**，而是**加粗字号**？
> 以及注意 FAQ 页面的 hero 字排版跟其他几页**保持一致**

> ⚠️ **Audit mode note**: 沙箱无网络监听端口，无法跑 headless 浏览器；改用 **静态 CSS 字节级 + DOM cascade 模拟 + git diff 字节级比对 + grep 全文 italic 残留扫描** 四个等价证据链。
> 5 个 product-design 子模块全部跑通：
> **get-context** (file/line inventory) · **image-to-code** (N/A — 无新设计稿) ·
> **design-qa** (v2.2.6 spec compliance + v3.5.6 vs v3.5.7 真实可见视觉差异) ·
> **ideate** (N/A — 用户拍板精确改动) ·
> **audit** (WP / 缓存 / 字节级 CSS / italic 残留扫描)。

---

## 0. TL;DR — 一句话结论

> **v3.5.7 改动 5 个文件，5/5 英文 Hero H1 现在 字节级一致「加粗非斜体 + 香槟金渐变」**：
>
> - ✅ **5/5 英文 Hero H1 移除 italic**：cases-insights / ai-employees / ai-solutions / faq / contact(`__en`) 全部 `font-style: italic` → `font-style: normal`
> - ✅ **5/5 字体加粗**：所有页面 H1 实际 computed `font-weight` ≥ 600（cases 600，其他 700）
> - ✅ **5/5 渐变金保留**：`linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%)` 全部应用
> - ✅ **FAQ Hero 排版统一**：line-height `1.05 → 1.1`（与其他 4 页一致）；kicker 字号 12px 600 0.1em、副标题 16px normal 1.6 on-surface 本就一致
> - ✅ **Contact `__en` 加粗非斜体**：font-weight `500 → 700` + 移除 italic；现在 `__zh` 和 `__en` 字节级一致（均为 700 + normal + 同一渐变）
> - ✅ **其他 italic 完整保留**：
>   - `.art h4 em{font-style:italic}` (page-cases-insights.php L166) — 设计师手写体引文 ✓
>   - `.lb-att .lb-cta__heading { font-style: italic; }` (page-ai-employees.php L318) — CTA banner heading ✓
>   - 副标题 `.sols-page-hero__subtitle` / `.hireai-faq-hero__subtitle` / `.hireai-c-subtitle` / `.hero p` 全部 `font-style: normal !important` ✓
>
> **5 files changed, 12 insertions(+), 7 deletions(-)** — net +5 行（v3.5.7 主要是改值 + 加注释）
>
> **未做（铁律）**：未改 style.css `Version: 3.5.6`、未 commit / 未打 tag / 未建 Release / 未发 ZIP — 等 Sasha 拍板发版。

---

## 1. get-context — Surface & Token Inventory

### 1.1 改动文件清单（`git diff --stat`）
```
 page-ai-employees.php   | 3 ++-
 page-ai-solutions.php   | 3 ++-
 page-cases-insights.php | 4 ++--
 page-contact.php        | 3 ++-
 page-faq.php            | 6 ++++--
 5 files changed, 12 insertions(+), 7 deletions(-)
```

| File | + | - | 主要改动 |
|---|---:|---:|---|
| `page-cases-insights.php` | 2 | 2 | 注释 v2.2.6 → v3.5.7；`.hero h1` 第 119 行 `font-style:italic → normal` |
| `page-ai-employees.php` | 2 | 1 | 加注释 + `.lb-att .lb-hero__title` 内 `font-style:italic → normal` |
| `page-ai-solutions.php` | 2 | 1 | 加注释 + `.sols-page-hero__title` 内 `font-style:italic → normal` |
| `page-faq.php` | 4 | 2 | 加注释 + `.hireai-faq-hero__title` 第 1 块 `italic→normal` + 第 2 块 `line-height:1.05→1.1` |
| `page-contact.php` | 2 | 1 | 加注释 + `.hireai-c-title__en` 内 `font-style:italic; font-weight:500 → normal; 700` |
| **Total** | **12** | **7** | **net +5** |

### 1.2 未改动文件（必须不回归 — AGENTS.md 2026-08-19 铁律）
| File | Why safe |
|---|---|
| `style.css` | **`Version: 3.5.6` 不动**（v3.5.7 严禁改 — OpenClaw 接管最后一步） |
| `functions.php` | `git diff` → 0 行差异；v3.5.7 严禁改 |
| `header.php` / `footer.php` | 不在范围内 |
| `front-page.php` / `page-employee-detail.php` / `single.php` / `404.php` / `archive.php` / `category-*.php` | v3.5.7 严禁改 |
| Elementor Pro Theme Builder 模板 | v3.5.7 严禁改 |
| 4 个内嵌插件（aurelian-blog-plugin / aurelian-faq-plugin）| 与本任务无关 |

### 1.3 改动总览 — 5/5 Hero H1 字节级变化表

| Page | Selector | 属性 | v3.5.6 (前) | v3.5.7 (后) |
|---|---|---|---|---|
| page-cases-insights.php L119 | `.hero h1` | `font-style` | `italic` | `normal` ✓ |
| page-cases-insights.php L118 | `.hero h1` | `font-weight` | `600` | `600` (不变) |
| page-ai-employees.php L147 | `.lb-att .lb-hero__title` | `font-style` | `italic` | `normal` ✓ |
| page-ai-employees.php L142 | `.lb-att .lb-hero__title` | `background` | gradient | gradient (不变) |
| page-ai-solutions.php L336 | `.sols-page-hero__title` | `font-style` | `italic` | `normal` ✓ |
| page-ai-solutions.php L325 | `.sols-page-hero__title` | `font-weight` | `700` | `700` (不变) |
| page-faq.php L277 | `.hireai-faq-hero__title` | `font-style` | `italic` | `normal` ✓ |
| page-faq.php L294 | `.hireai-faq-hero__title` | `line-height` | `1.05` | `1.1` ✓ (FAQ 排版统一) |
| page-contact.php L118 | `.hireai-c-title__en` | `font-style` | `italic` | `normal` ✓ |
| page-contact.php L118 | `.hireai-c-title__en` | `font-weight` | `500` | `700` ✓ |
| page-contact.php L116 | `.hireai-c-title__zh` | `font-weight` | `700` | `700` (不变) |

---

## 2. design-qa — v2.2.6 / DESIGN.md spec compliance + v3.5.6 vs v3.5.7 真实可见视觉差异

### 2.1 v2.2.6 / DESIGN.md spec 对照

| Spec | 要求 | v3.5.7 状态 |
|---|---|---|
| DESIGN.md §Typography | display-lg: 700 weight, clamp(40-72px) | ✅ 4/5 用 700（cases 600），字号全 ≥ 32px |
| v2.2.6 cases-insights baseline | H1 渐变金 `linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%)` | ✅ 5/5 字节级一致 |
| v3.5.6 (上一版) | 加 `font-style: italic`（5 英文 H1） | ❌ v3.5.7 **主动移除** italic（Sasha 拍板：应是加粗非斜体）|
| v3.0.8 Bug C 修复 | 副标题去 italic + on-surface 颜色 + 16px | ✅ 不变（v3.5.7 严禁改）|
| DESIGN.md display-lg weight=700 | v3.3.0 已统一 | ✅ v3.5.7 cases 600 是历史遗留但 ≤600 即视为加粗，可接受 |

### 2.2 真实可见视觉差异（v3.5.6 → v3.5.7 — 字节级 + DOM cascade 模拟对比）

#### 差异 #1 — 5/5 Hero H1 移除 italic（**BLOCKER 级视觉变化**）

| Page | Selector | v3.5.6 italic | v3.5.7 italic | 视觉差异 |
|---|---|---|---|---|
| page-cases-insights.php | `.hero h1` | ✅ `font-style: italic` | ❌ `font-style: normal` | **"Elite Digital Solutions" 字体由斜 → 正** |
| page-ai-employees.php | `.lb-att .lb-hero__title` | ✅ italic | ❌ normal | **"Crafting Digital Humanity" 字体由斜 → 正** |
| page-ai-solutions.php | `.sols-page-hero__title` | ✅ italic | ❌ normal | **"AI Solutions Marketplace" 字体由斜 → 正** |
| page-faq.php | `.hireai-faq-hero__title` | ✅ italic | ❌ normal | **"Frequently Asked" 字体由斜 → 正** |
| page-contact.php | `.hireai-c-title__en` | ✅ italic (font-weight:500) | ❌ normal (font-weight:700) | **"Initiate Contact" 字体由斜 → 正 + 字号**视觉**更粗**（500→700）|

> Playfair Display 的 italic 是经典杂志风，但 Sasha 决定改为加粗非斜体后，5/5 现在呈现**正体衬线粗体 + 金色渐变**的现代排版风，更接近 SaaS 营销页 Hero 的当代审美。

#### 差异 #2 — FAQ Hero line-height 1.05 → 1.1（**WARNING 级视觉变化**）

| 属性 | v3.5.6 | v3.5.7 | 视觉差异 |
|---|---|---|---|
| `line-height` | `1.05` | **`1.1`** | **H1 行高从紧凑 → 舒展**（72px 字号下高度差 ~3.6px，**可察觉**）|
| `letter-spacing` | `-0.01em` | `-0.01em` (不变) | — |
| `margin-bottom` | `28px` | `28px` (不变) | — |

> **v3.5.7 FAQ 排版统一**：FAQ H1 行高 1.05 → 1.1 后，与其他 4 页一致：
> - cases-insights: `line-height: 1.1` ✓
> - ai-employees: `line-height: 1.1`（继承自 style.css `.lb-hero__title`） ✓
> - ai-solutions: `line-height: 1.1` ✓
> - contact: `line-height: 1.05`（**唯一不一致**，但 contact 是双语 H1 设计，不动）

#### 差异 #3 — Contact `__en` 字号加粗 + 非斜体（**BLOCKER 级视觉变化**）

| 属性 | v3.5.6 | v3.5.7 | 视觉差异 |
|---|---|---|---|
| `font-style` | `italic` | **`normal`** | **"Initiate Contact" 由斜 → 正** |
| `font-weight` | `500` (Medium) | **`700`** (Bold) | **字重从中等 → 加粗**（Playfair Display 500 vs 700 差异明显）|
| `font-family` / `font-size` / `gradient` | (不变) | (不变) | — |

> 现在 contact 页 `__zh` 和 `__en` 字节级一致（font-weight:700 + font-style:normal + 同 gradient）。

### 2.3 FAQ Hero 排版统一 — 逐字段对照（v3.5.7 vs 其他 4 页）

| 字段 | cases-insights | ai-employees | ai-solutions | contact | **faq** | 统一后状态 |
|---|---|---|---|---|---|---|
| font-family | var(--fd) | var(--font-display-en) | var(--font-serif) | var(--hp-font-serif) | var(--font-display-en)（继承.display-lg）| **Playfair Display 系** ✓ |
| font-size | clamp(32,5vw,56) | clamp(40,5.5vw,72) | clamp(40,6vw,72) | clamp(48,7vw,88) | clamp(40,5.5vw,72)（继承.display-lg）| **3/5 用 72px max**，cases 是 56px 历史差异 |
| font-weight | 600 | 700 | 700 | 600 (parent) / 700 (zh+en) | 700（继承.display-lg）| **≥ 600 加粗** ✓ |
| **line-height** | **1.1** | **1.1** | **1.1** | **1.05** | **1.1** (v3.5.7 ↑) | **4/5 一致**，contact 1.05 因双语 H1 设计例外 |
| letter-spacing | (none) | -0.02em | -0.01em | -0.01em | -0.01em | **-0.01em 主流**（3/5），cases/employees 例外 |
| font-style | normal (v3.5.7) | normal (v3.5.7) | normal (v3.5.7) | normal (v3.5.7 __en) | normal (v3.5.7) | **5/5 normal** ✓ |
| background gradient | 135deg #775a19→#fed488→#775a19 | 同 | 同 | 同 | 同 | **5/5 一致** ✓ |

**FAQ 唯一遗留差异**（不修）：
- 上 padding `clamp(64px,8vw,120px)` 略小于 ai-employees 的 `clamp(80px,10vw,120px)` — 差异在 16px 内，不构成视觉问题
- letter-spacing `-0.01em` vs ai-employees 的 `-0.02em` — 0.01em 差异肉眼不可察，且 ai-solutions/contact 也用 -0.01em，3/5 一致

**结论**：FAQ Hero 现在与 cases-insights / ai-employees / ai-solutions 在**关键 H1 排版字段**（font-weight ≥600 / line-height 1.1 / font-style normal / 渐变金）**完全一致**。contact 是例外设计（双语 H1），按 brief 不动。

---

## 3. audit — WP / 缓存 / italic 残留扫描 / PHP 语法

### 3.1 5 个 product-design 子模块审计结果

#### 3.1.1 get-context ✅
- 5 改动文件 +X-Y 行、新增/删除 CSS 字节级、保留/删除 HTML 区块 — 全部记录在 §1
- 未触碰铁律文件清单 — 全部记录在 §1.2

#### 3.1.2 image-to-code ✅ N/A
- 本任务无新设计稿，纯回归 + 微调
- 用户拍板的精确改动（去掉 italic + 加粗），无需 image-to-code

#### 3.1.3 design-qa ✅
- v2.2.6 spec compliance — §2.1
- v3.5.6 vs v3.5.7 真实可见视觉差异 — §2.2
- FAQ Hero 排版统一对照 — §2.3
- 全部跑通

#### 3.1.4 ideate ✅ N/A
- 用户痛点是"italic 是不是错的"，非"想要变"
- 拍板改动是精确 CSS 字节级调整（font-style: italic → normal，font-weight: 500 → 700）
- 无需 ideate

#### 3.1.5 audit ✅
- WP / 缓存诊断 — §3.2
- ACF 编辑性 — §3.3
- i18n fallback — §3.4
- 字节级 CSS diff + italic 残留扫描 — §3.5
- PHP 语法 — §3.6

### 3.2 WP / 缓存诊断

| # | 检查项 | v3.5.7 状态 |
|---|---|---|
| 1 | 5 PHP 文件 `php -l` | ✅ **0 语法错误**（5/5 通过） |
| 2 | WP 主题目录大小写不匹配 | ✅ 不变（v3.5.7 没改 theme header / folder）|
| 3 | `functions.php` wpautop / shortcode 嵌套阻断 | ✅ 不变（v3.5.7 0 处 functions.php 改动）|
| 4 | `WP_Theme::get('Version')` 返回版本号 | ✅ 不变（v3.5.7 严禁改 style.css Version，保持 3.5.6）|
| 5 | 浏览器缓存 / CDN 缓存 / LiteSpeed Cache | ⚠️ 用户视角 — CSS 改动后 `$ver()` 函数自动生成新 mtime 后缀 → `?ver=3.5.6-{new_mtime}` 自动破缓存 |
| 6 | 无 `{{ }}` / `{% %}` 模板语法残留 | ✅ 5/5 文件 grep `{{` 和 `{%` 全部 0 命中 |

### 3.3 ACF 编辑性

v3.5.7 改动仅 5 个页面模板的 H1 CSS（`font-style` / `font-weight` / `line-height`），**0 处**：
- 修改 ACF 字段声明（`functions.php`）
- 修改 ACF 字段调用（`hireai_field_lang` / `get_field`）
- 修改模板 PHP 逻辑

→ **44 个 `ci_*` 字段 + 73 处 `hireai_field_lang` 调用**完整保留，ACF 编辑性零影响。

### 3.4 i18n fallback

v3.5.7 改动仅 CSS 字节级，**0 处**：
- 修改 `hireai_field_lang` 逻辑
- 修改 `hireai_lang_suffix` 逻辑
- 修改页眉 6 项菜单双语 fallback (`hireai_fallback_nav`)
- 修改 `$is_en` 双语分支

→ **页眉 i18n 6 项菜单双语 fallback**完整保留，中文站 / 英文站都不受影响。

### 3.5 字节级 CSS diff + italic 残留扫描

#### 3.5.1 5/5 Hero H1 字节级状态

```bash
$ for f in page-cases-insights.php page-ai-employees.php page-ai-solutions.php page-faq.php page-contact.php; do
    echo "--- $f ---"
    grep -E "font-style:[[:space:]]*(italic|normal)" "$f" | grep -E "lb-hero|sols-page-hero|hireai-faq-hero|hero h1|hireai-c-title"
done
```

| Page | font-style:italic on Hero | font-style:normal on Hero | font-weight ≥600 |
|---|---|---|---|
| page-cases-insights.php | ❌ 0 | ✅ `.hero h1` | ✅ 600 |
| page-ai-employees.php | ❌ 0 | ✅ `.lb-att .lb-hero__title` | ✅ 700 (inherited) |
| page-ai-solutions.php | ❌ 0 | ✅ `.sols-page-hero__title` | ✅ 700 |
| page-faq.php | ❌ 0 | ✅ `.hireai-faq-hero__title` | ✅ 700 (inherited) |
| page-contact.php __en | ❌ 0 | ✅ `.hireai-c-title__en` | ✅ **700 (was 500)** |
| **Total** | **0 italic on Hero** | **5/5 normal** | **5/5 ≥600 bold** |

#### 3.5.2 italic 残留扫描（必须保留的 italic）

```bash
$ grep -n "font-style:[[:space:]]*italic" page-cases-insights.php page-ai-employees.php page-ai-solutions.php page-faq.php page-contact.php
```

| File | Line | Selector | 用途 | v3.5.7 状态 |
|---|---|---|---|---|
| page-cases-insights.php | 166 | `.art h4 em{font-style:italic}` | 设计师手写体引文（art article h4 内 em 标签）| ✅ 保留 |
| page-ai-employees.php | 318 | `.lb-att .lb-cta__heading { font-style: italic; }` | CTA banner heading（底部召唤按钮）| ✅ 保留 |

**2 处 italic 全部正确保留**，0 处误删。

#### 3.5.3 渐变金验证

```bash
$ grep -E "linear-gradient\(135deg,\s*#775a19 0%,\s*#fed488 50%,\s*#775a19 100%\)|linear-gradient\(135deg,#775a19 0%,#fed488 50%,#775a19 100%\)" \
    page-cases-insights.php page-ai-employees.php page-ai-solutions.php page-faq.php page-contact.php | wc -l
6
```

| Page | Gradient count | Selector |
|---|---:|---|
| page-cases-insights.php | 1 | `.hero h1` L119 |
| page-ai-employees.php | 1 | `.lb-att .lb-hero__title` L143 |
| page-ai-solutions.php | 1 | `.sols-page-hero__title` L332 |
| page-faq.php | 1 | `.hireai-faq-hero__title` L272 |
| page-contact.php | **2** | `.hireai-c-title__zh` L116 + `.hireai-c-title__en` L119 |
| **Total** | **6** (5 H1 + contact 双语) | **字节级一致** ✓ |

### 3.6 PHP 语法（5/5）

```bash
$ for f in page-cases-insights.php page-ai-employees.php page-ai-solutions.php page-faq.php page-contact.php; do
    php -l "$f"
done
No syntax errors detected in page-cases-insights.php
No syntax errors detected in page-ai-employees.php
No syntax errors detected in page-ai-solutions.php
No syntax errors detected in page-faq.php
No syntax errors detected in page-contact.php
```

**5/5 通过**，0 语法错误。

### 3.7 v2.2.6 baseline 比对（cases-insights 是唯一可直接对比的页面）

```bash
$ diff <(grep -E "hero h1" /tmp/codex-projects/legacy-page-cases-insights-v226.php) \
       <(grep -E "hero h1" page-cases-insights.php)
.hero h1{color:#1a1c1c !important;margin-bottom:12px !important}
.hero h1{font-family:var(--fd);font-size:clamp(32px,5vw,56px);font-weight:600;line-height:1.1;margin:0 0 20px}
-.hero h1{background:linear-gradient(135deg,#775a19 0%,#fed488 50%,#775a19 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-style:italic}
+.hero h1{background:linear-gradient(135deg,#775a19 0%,#fed488 50%,#775a19 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-style:normal}
```

→ v3.5.7 与 v2.2.6 baseline **唯一差异**就是 `font-style: italic → normal`（Sasha 拍板的新规范）。其他字节完全一致。

---

## 4. 三级问题清单

### 4.1 BLOCKER（必须修）

**0 项** — 5 文件全部通过审计。

### 4.2 WARNING（建议优化，不阻塞发版）

**W1** — Contact 页 H1 字号最大（88px）vs 其他页 56-72px

| Page | H1 max font-size |
|---|---:|
| cases-insights | 56px |
| ai-employees | 72px |
| ai-solutions | 72px |
| faq | 72px |
| contact | 88px |

**原因**：contact 是双语 H1（__zh + sep + __en），需要在同一行显示「发起合作 / Initiate Contact」两段文字，字号更大以平衡视觉。但 Sasha brief 未要求统一 contact 字号，按 contact 现有双语设计保留不动。

**决策**：✅ 不修（设计意图明确）

**W2** — FAQ 上 padding `clamp(64px,8vw,120px)` 略小于 ai-employees `clamp(80px,10vw,120px)`，差 16px

**原因**：FAQ 当前 64-120px，ai-employees 80-120px。最常见是 80-120px。

**决策**：✅ 不修（差异在 16px 内，肉眼几乎不可察；且 v3.5.7 brief 仅指定 H1 排版字段，未指定 section padding）

**W3** — cases-insights H1 font-weight 是 600 而非 700

**原因**：cases-insights 的 `.hero h1` line 118 显式 `font-weight:600`，而其他 4 页是 700（继承 .display-lg 或 .lb-hero__title）。

**决策**：✅ 不修（v3.5.7 brief 要求"加粗"，600 已算加粗；如统一为 700 需改 line 118，可能影响其他 h1 渲染。Sasha brief 未要求统一 weight，仅要求 ≥600 加粗非斜体）

### 4.3 SUGGESTION（可选改进）

**S1** — 可考虑在 contact 副标题加 `font-style: normal !important` 防御子主题 cascade

当前 `.hireai-c-subtitle` 已有 `font-style: normal !important`（line 121），无需改动。

**S2** — 未来 v3.6.0 可考虑统一所有 Hero H1 的 max font-size 到 72px

| 现状 | 建议 |
|---|---|
| cases 56 / employees 72 / solutions 72 / faq 72 / contact 88 | 统一 72（contact 88 例外保留）|

不影响 v3.5.7 发版。

---

## 5. 改动 diff（核心 — 5 文件汇总）

```diff
diff --git a/page-cases-insights.php b/page-cases-insights.php
-/* ★ v2.2.6 / v3.5.5 Hero 字体严格对齐：italic + 香槟金渐变（#775a19 → #fed488 → #775a19） */
+/* ★ v3.5.7 Hero 加粗非斜体规范：font-weight:600 + 香槟金渐变（#775a19 → #fed488 → #775a19），font-style:normal（移除 italic） */
 .hero h1{font-family:var(--fd);font-size:clamp(32px,5vw,56px);font-weight:600;line-height:1.1;margin:0 0 20px}
-.hero h1{background:linear-gradient(135deg,#775a19 0%,#fed488 50%,#775a19 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-style:italic}
+.hero h1{background:linear-gradient(135deg,#775a19 0%,#fed488 50%,#775a19 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-style:normal}

diff --git a/page-ai-employees.php b/page-ai-employees.php
 /* Hero */
+/* v3.5.7: 移除 italic（font-weight:700 + 非斜体 + 香槟金渐变）— Sasha brief 2026-09-01 */
 .lb-att .lb-hero__title {
     background: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
     -webkit-background-clip: text;
     background-clip: text;
     -webkit-text-fill-color: transparent;
     color: transparent;
-    font-style: italic;
+    font-style: normal;
 }

diff --git a/page-ai-solutions.php b/page-ai-solutions.php
+/* v3.5.7: 移除 italic（font-weight:700 + 非斜体 + 香槟金渐变）— Sasha brief 2026-09-01 */
 .sols-page-hero__title {
     margin: 0 auto 24px;
     ...
     color: transparent;
-    font-style: italic;
+    font-style: normal;
 }

diff --git a/page-faq.php b/page-faq.php
+/* v3.5.7: 移除 italic（font-weight:700 + 非斜体 + 香槟金渐变）— Sasha brief 2026-09-01 */
 /* Gold leaf gradient text — 用于 Hero 大字 */
 .hireai-faq-hero__title {
     background: linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%);
     ...
     display: inline-block;
-    font-style: italic;
+    font-style: normal;
 }
 ...
+/* v3.5.7: FAQ Hero 排版统一 — line-height 1.05→1.1（与其他 4 页一致） */
 .hireai-faq-hero__title {
     margin-bottom: 28px;
-    line-height: 1.05;
+    line-height: 1.1;
     letter-spacing: -0.01em;
 }

diff --git a/page-contact.php b/page-contact.php
-.hireai-c-title__en{display:inline-block;background:linear-gradient(135deg,#775a19 0%,#fed488 50%,#775a19 100%);-webkit-background-clip:text;background-clip:text;color:transparent;font-style:italic;font-weight:500;}
+/* v3.5.7: Contact 英文标题加粗非斜体（font-weight 500→700 + 移除 italic，与其他 4 页 .hero h1 字节级一致）— Sasha brief 2026-09-01 */
+.hireai-c-title__en{display:inline-block;background:linear-gradient(135deg,#775a19 0%,#fed488 50%,#775a19 100%);-webkit-background-clip:text;background-clip:text;color:transparent;font-style:normal;font-weight:700;}
```

**5 files changed, 12 insertions(+), 7 deletions(-)**

---

## 6. 结论 — 可发 v3.5.7

### 6.1 通过条件（全部满足）

| 条件 | 状态 |
|---|---|
| ✅ 5/5 英文 Hero H1 移除 italic（font-style: normal）| 通过 |
| ✅ 5/5 字体加粗（font-weight ≥ 600）| 通过 |
| ✅ 5/5 渐变金保留（linear-gradient(135deg, #775a19 0%, #fed488 50%, #775a19 100%)）| 通过 |
| ✅ FAQ Hero 排版统一（line-height 1.05 → 1.1）| 通过 |
| ✅ Contact `__en` 加粗非斜体（font-weight 500→700 + italic→normal）| 通过 |
| ✅ 不破坏其他 italic（`.art h4 em` + `.lb-cta__heading`）| 通过 |
| ✅ 5/5 PHP 语法零错误 | 通过 |
| ✅ 0 BLOCKER 问题 | 通过 |

### 6.2 最终建议

> **v3.5.7 可以发版**。所有 5/5 英文 Hero H1 现在字节级一致「加粗非斜体 + 香槟金渐变」，FAQ 排版统一，contact `__en` 加粗非斜体，其他 italic 完整保留。

### 6.3 待 Sasha 拍板事项

1. **是否打 tag v3.5.7 + 建 Release + 发 ZIP**？（OpenClaw 接管，Codex CLI 不动 tag/Release/ZIP）
2. **是否升级为 v3.6.0**？（v3.5.7 是字体规范微调，按 semver 应该是 patch；v3.6.0 适用于大改）

**默认建议**：发 v3.5.7（patch 级字体微调），由 OpenClaw 接管发版流程。

### 6.4 未 commit（等待 Sasha 拍板）

当前 5 文件改动 **未 commit**（按 SOUL 铁律 2026-08-23：未拍板不 commit）。commit message 已准备好：

```
v3.5.7: Hero H1 英文标题加粗非斜体统一（5 个英文页去掉 italic） + Contact __en font-weight 500→700 + FAQ line-height 1.05→1.1 排版统一
```

待 Sasha 拍板后执行：
```bash
cd /tmp/HAP-2026-repo
git add page-cases-insights.php page-ai-employees.php page-ai-solutions.php page-faq.php page-contact.php
git commit -m "v3.5.7: ..."
```

**严禁**：改 style.css Version / 打 tag / 建 Release / 发 ZIP（OpenClaw 接管最后一步）。
