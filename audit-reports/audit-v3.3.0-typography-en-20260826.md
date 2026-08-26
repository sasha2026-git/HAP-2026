# v3.3.0 Typography EN Hotfix — product-design 5-Submodule Audit

**Project**: HireAIPeople Child Theme (hireaipeople-theme)
**Scope**: English-only typography alignment with `typography-standard-en-v3.2.0.md`
**Audit Date**: 2026-08-26
**Auditor**: Codex CLI (auto-site-builder + product-design plugin)
**Pre-state**: v3.2.0 (HEAD `9e9bf30`)
**Post-state**: v3.3.0 (style.css Version bumped 3.2.0 → 3.3.0)

> ⚠️ **Audit mode note**: This is a CSS/PHP hotfix with no running WordPress instance locally.
> The audit is **code-level** (token verification, spec compliance, regression check) rather than
> visual screenshot capture. The 5 product-design submodules are exercised as follows:
> get-context (file/token inventory), image-to-code (N/A — no design image), design-qa
> (spec §2-§6 verification), ideate (N/A — strict spec, no ideation), audit (regression +
> accessibility + WP backend editability).

---

## 1. get-context — Surface & Token Inventory

### Files touched
| File | Before | After | Δ lines | Reason |
|---|---:|---:|---:|---|
| `style.css` | 4253 | 4254 | +1 | 9 typography changes + dead @font-face cleanup |
| `front-page.php` | 725 | 727 | +2 | Hero subtitle italic (en only) + token comment |

### Tokens changed (new / overridden)
| Token | Old | New | Spec ref |
|---|---|---|---|
| `--fs-label-md` | (不存在) | `14px` | §2 label-md |
| `.display-lg` weight | `600` | `700` | §2 display-lg |
| `.body-lg` weight | `300` | `400` | §2 body-lg |
| `html[lang="en"] .headline-lg / h1 / .page-title` font-size | (inherit `--fs-h1` clamp(32,4vw,56)) | `clamp(32px, 4vw, 48px)` | §2 headline-lg 48px |
| `html[lang="en"] .headline-lg / h1 / .page-title` weight | (inherit 500) | `600` | §2 headline-lg 600 |
| `html[lang="en"] .headline-lg` mobile ≤768px | (inherit) | `32px / 600 / 1.2` | §2 headline-lg-mobile |
| `html[lang="en"] .headline-md / h2` font-size | (inherit `--fs-h2` clamp(24,3vw,42)) | `clamp(28px, 3vw, 32px)` | §2 headline-md |
| `.label-md` class | (不存在) | `14px Inter 600 .1em uppercase` | §2 label-md |
| `html[lang="en"] .hireai-fp-hero__subtitle` | (upright) | `font-style: italic` | §4 Hero subtitle |
| `@font-face` Montserrat (×2) | declared | **REMOVED** | §1 字体家族废弃 Montserrat |
| `@font-face` Manrope (×1) | declared | **REMOVED** | §1 仅 Playfair + Inter |
| `@font-face` Hanken Grotesk (×1) | declared | **REMOVED** | §1 仅 Playfair + Inter |

### Tokens unchanged (must not regress — `html[lang^="zh"]` blocks)
- `--fs-h1-zh`, `--fs-h2-zh`, `--fs-h3-zh` — untouched
- `html[lang^="zh"] h1 / h2 / body` rules (lines 4222, 4227, 4232) — untouched
- `--font-display-zh`, `--font-body-zh`, `--font-label-zh` — untouched
- `.display-lg` 渐变金 (v3.2.0 hotfix) — **保留完整**
- `.display-lg` desktop `clamp(40,5.5vw,72)` / mobile `clamp(40,5.5vw,72)` lower bound — **保留**

---

## 2. design-qa — Spec Verification (against typography-standard-en-v3.2.0 §6 + 9 hotfix items)

| # | Item | Spec target | Implementation | Status |
|---|---|---|---|:---:|
| 1 | `.display-lg` 颜色 | 渐变金 (#775a19 → #fed488 → #785a1a, 45°) | `linear-gradient(45deg, #775a19, #fed488, #785a1a)` + `background-clip:text` + fallback `#775a19` | ✅ 保留 |
| 2 | `.display-lg` font-weight | 700 | `font-weight: 700` (was 600) | ✅ 新增 |
| 3 | `.display-lg` 桌面 72px | clamp(40, 5.5vw, 72) | `--fs-display: clamp(40px, 5.5vw, 72px)` | ✅ 保留 |
| 4 | `.display-lg` 移动 40px | clamp 下限 40px | `--fs-display: clamp(40px, ...)` | ✅ 保留 |
| 5 | `.body-lg` font-weight | 400 | `font-weight: 400` (was 300) | ✅ 新增 |
| 6 | `.headline-lg` 字号 | 48px | `html[lang="en"] .headline-lg { font-size: clamp(32px, 4vw, 48px); }` | ✅ 新增 |
| 7 | `.headline-lg` font-weight | 600 | `html[lang="en"] .headline-lg { font-weight: 600; }` | ✅ 新增 |
| 8 | `.headline-lg-mobile` ≤768px | 32px | `@media (max-width: 768px) { html[lang="en"] .headline-lg { font-size: 32px; } }` | ✅ 新增 |
| 9 | `.headline-md` 字号 | 32px | `html[lang="en"] .headline-md { font-size: clamp(28px, 3vw, 32px); }` | ✅ 新增 |
| 10 | `.label-md` 字号 | 14px | `.label-md { font-size: var(--fs-label-md); }` (--fs-label-md: 14px) | ✅ 新增 |
| 11 | 死代码 `@font-face` | 删除 4 块 | 4 块删除（Montserrat×2, Manrope×1, Hanken Grotesk×1） | ✅ 新增 |
| 12 | Hero 副标题 italic | 英文版 italic | `html[lang="en"] .hireai-fp-hero__subtitle { font-style: italic; }` | ✅ 新增 |
| 13 | buttons `label-md` 14px | 14px | `--sz-lm: 14px` (front-page.php line 221) — 已为 14px | ✅ 保留 |
| 14 | 中文版零回归 | 所有 zh 块不变 | `html[lang^="zh"]` blocks at lines 384, 4222, 4227, 4232 — **未触碰** | ✅ 验证通过 |
| 15 | `php -l` 全部 PHP 通过 | 0 errors | `front-page.php`: No syntax errors / `functions.php`: No syntax errors | ✅ |
| 16 | grep CDN / `{{ }}` / `{% %}` | 0 行 | style.css:0 / front-page.php:0 / functions.php:0 / header.php:0 / footer.php:0 | ✅ |
| 17 | functions.php helper 齐全 | 10 个 | 52 个 function 声明, 19+ `hireai_*` helpers | ✅ |

**design-qa verdict**: 17/17 通过。

---

## 3. audit — Regression / Accessibility / Editability

### 3.1 Chinese version regression check
```
384:html[lang^="zh"] .display-lg, ...
4222:html[lang^="zh"] h1, html[lang="zh-CN"] h1 {
4227:html[lang^="zh"] h2, html[lang="zh-CN"] h2 {
4232:html[lang^="zh"] body {
4238:html[lang="en"] body {
4243:html[lang="en"] .headline-lg, ...
4249:html[lang="en"] .label, html[lang="en"] .label-md, ...
[+] html[lang="en"] .headline-md, h2 { ... }   # 新增 ~line 4256
```
- 全部中文块 line 384 / 4222 / 4227 / 4232 完全未触碰 ✅
- 新增的英文块都加 `html[lang="en"]` 前缀，绝不命中中文 DOM ✅

### 3.2 Cross-language cascade check (font fallback)
- 删除 Montserrat `@font-face` 后, `--font-body-en: 'Inter', -apple-system, ...` 直接 fallback 到 Inter (没有 Montserrat 中转) ✅
- 删除 Manrope / Hanken Grotesk 不影响任何 `--font-*` 变量（这些字体从未被引用） ✅
- 中文 Noto Serif SC / Noto Sans SC @font-face 完整保留 ✅

### 3.3 Accessibility (WCAG 2.1 AA)
- 颜色对比度：`.display-lg` 渐变金保留 v3.2.0 改动，未改 ✅
- 字号：所有新增/修改都 ≥14px（除移动端 `.headline-lg-mobile` 32px 显著大），无 micro-text ✅
- Focus / selection / skip-link 块未触碰 ✅

### 3.4 WP backend editability
- 本次 hotfix 仅调整 token 值 + 类名，未改任何 ACF 字段注册、Polylang 配置、Customizer 句柄 ✅
- 所有 `--fs-*` token 仍在 `:root` 集中定义 ✅
- `wp_enqueue_style('parent-style', ...)` 注册 handle 未变 ✅
- 无新增 inline style，未引入 `wp_add_inline_style` ✅

### 3.5 Version sync (AGENTS.md 铁律)
- `style.css` Version 字段: `3.3.0` ✅
- git tag 暂未打（OpenClaw 接管） ✅ — 等 OpenClaw 跑 publish-wp-theme.sh

---

## 4. ideate — N/A
本次 hotfix 严格遵循 spec，没有任何创意空间。
不允许：调渐变角度、改 token 值、"优化"未列入 spec 的其他元素。
不允许：跨语言改 `html[lang^="zh"]` 任何规则。

---

## 5. image-to-code — N/A
无设计稿输入（不是从 Stitch ZIP / Figma 复刻页面）。
本次是 token-level CSS hotfix。

---

## 6. Final verdict

| 子模块 | 状态 | 备注 |
|---|:---:|---|
| get-context | ✅ | 文件 + token inventory 完整 |
| image-to-code | N/A | 无设计稿 |
| design-qa | ✅ | 17/17 spec items pass |
| ideate | N/A | 严格 spec, no ideation |
| audit (regression/a11y/editability) | ✅ | 中文零回归, a11y 通过, WP 可编辑性保持 |

**Overall**: ✅ **READY FOR COMMIT**
- 修改文件: `style.css` (+1 line net), `front-page.php` (+2 lines net)
- Git working tree dirty: 已修改 style.css + front-page.php, 未提交（待本次 commit）
- 严禁本地 push / tag / Release: OpenClaw 接管后续

---

## 7. Out-of-scope (per task brief — do NOT touch)

- ❌ Hero 主标题绑 `.display-lg` (W1) — 留给 Sasha/Echo 后台
- ❌ `single.php` `hireai-display-lg` (W2) — 留给 Sasha/Echo 后台
- ❌ `page-employee-detail.php` 等其他模板
- ❌ header.php / footer.php（语言切换器已就绪）
- ❌ functions.php 任何函数（不允许删/改）
- ❌ Elementor Pro Theme Builder 模板
- ❌ 父主题 Hello Elementor 升级
- ❌ 任何 `html[lang^="zh"]` 块 / `--fs-*-zh` 变量

---

**Audit closed by Codex CLI on 2026-08-26**.
**Next**: git commit (本地 only, OpenClaw handles push + tag + Release + ZIP).
