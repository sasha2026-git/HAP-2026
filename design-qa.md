# Design QA Report — HireAI Homepage v2.0.0 Stitch 1:1 还原

## Source Visual Truth
- `/tmp/stitch_design_0819/screen.png`
- `/tmp/stitch_design_0819/DESIGN.md`
- `/tmp/stitch_design_0819/code.html`

## Implementation
- `/tmp/codex-projects/hireaipeople-theme/front-page.php`

## Viewport
- Desktop: 1440px
- Mobile: 375px (max-width: 767px)

---

## Findings & Fixes

### Spacing / Layout Rhythm

| # | 问题 | 修复 | 对应 DESIGN.md |
|---|------|------|----------------|
| 1 | `--section-pad` 为 `300px 80px`，与设计稿 section-gap 120px 严重不符 | 改为 `120px 80px`（桌面）/ `120px 20px`（移动） | Layout & Spacing: section-gap 120px |
| 2 | `--side-pad` 为 `140px`，超出 margin-desktop 80px | 改为 `80px`（桌面）/ `20px`（移动） | Layout & Spacing: margin-desktop 80px, margin-mobile 20px |
| 3 | 相邻 `.hireai-fp__section` 之间有 `margin-top: 160px`，叠加 padding 后造成 280px 超大留白 | 移除 `margin-top` 规则，仅保留 padding | Layout & Spacing: 8px baseline, section-gap 120px |
| 4 | Hero `padding: 45vh` 导致标题过于偏下，设计稿标题位于视口偏上 1/3 | 改为 `padding: 32vh var(--side-pad) 80px` | Layout & Spacing: 视觉重心 |
| 5 | Hero `max-width: 44.8rem` 过窄，设计稿标题更舒展 | 改为 `max-width: 56rem` | Layout & Spacing: container 占比 |
| 6 | Products / Solutions / Cases grid gap 为 `20px`，设计稿为 `24px`/`32px` | Products `gap: 24px`, Solutions `gap: 32px`, Cases `gap: 32px` | Layout & Spacing: gutter 24px |
| 7 | FAQ list `margin-top: 160px` 过大，设计稿紧接标题下方 | 改为 `margin-top: 64px` | Layout & Spacing: section-gap 120px |

### Typography

| # | 问题 | 修复 | 对应 DESIGN.md |
|---|------|------|----------------|
| 8 | Hero title `clamp(27px, 4vw, 45px)` 远小于 design display-lg 72px | 改为 `clamp(32px, 5vw, 72px)` | Typography: display-lg 72px / 1.1 / -0.02em |
| 9 | Hero subtitle `clamp(10px, 1.1vw, 13px)` 过小，且为全大写 | 改为固定 `18px`，保留 uppercase | Typography: body-lg 18px / 1.6 |
| 10 | Section label `font-size: 11px` / `letter-spacing: 0.3em`，设计稿为 12px / 0.3em | 改为 `12px` / `0.3em` | Typography: label-md 12px / 0.1em（设计稿 kicker 用 0.3em） |
| 11 | Section title `clamp(28px, 3.5vw, 44px)` 小于 headline-lg 48px | 改为 `clamp(32px, 3.5vw, 48px)` | Typography: headline-lg 48px / 1.2 / 600 |
| 12 | Intro desc `clamp(14px, 1.2vw, 18px)` 过小 | 改为固定 `18px` | Typography: body-lg 18px / 1.6 |
| 13 | Product card desc `clamp(13px, 1.1vw, 15px)` 过小 | 改为固定 `16px` | Typography: body-md 16px / 1.6 |
| 14 | Solution card desc `clamp(13px, 1.1vw, 15px)` 过小 | 改为固定 `16px` | Typography: body-md 16px / 1.6 |
| 15 | Major case title `clamp(22px, 2.4vw, 32px)` 偏小 | 改为固定 `24px` | Typography: headline-md 32px（major case 取中间值） |
| 16 | Major case desc `clamp(13px, 1.1vw, 15px)` 过小 | 改为固定 `16px` | Typography: body-md 16px / 1.6 |
| 17 | FAQ question `clamp(18px, 2.2vw, 28px)` 偏大且不固定 | 改为固定 `20px`（桌面）/ `18px`（移动） | Typography: headline-md 32px（FAQ 取 20px 适中） |
| 18 | FAQ answer `clamp(13px, 1.1vw, 16px)` 偏小 | 改为固定 `16px` | Typography: body-md 16px / 1.6 |

### Colors & Tokens

| # | 问题 | 修复 | 对应 DESIGN.md |
|---|------|------|----------------|
| 19 | Burnished gold gradient 为 `45deg`，设计稿和 code.html 均为 `135deg` | 改为 `linear-gradient(135deg, #e9c176 0%, #775a19 100%)` | Colors: 45deg 渐变模拟金属反光（设计稿实际用 135deg） |
| 20 | Cases section 背景为 `var(--surface)` #faf9f9，设计稿为纯白卡片区 | 改为 `background: #ffffff` | Colors: surface-container-lowest #ffffff |
| 21 | Products section 背景为 `#fff`，但容器未正确包裹 | 已确认 `#fff`，并添加 `.hireai-fp-products > .hireai-fp__section` 正确约束 | Colors: surface-container-lowest #ffffff |

### Components / Buttons

| # | 问题 | 修复 | 对应 DESIGN.md |
|---|------|------|----------------|
| 22 | Product card CTA 使用 `.hireai-fp__btn--outline`（白色背景+blur），设计稿为透明底+金色边框，hover 变金色填充 | 新增 `.hireai-fp__btn--outline-fill`：透明底 / 金色边框 / hover 金色填充+白色字 | Components: Buttons — 次按钮 1px 金色渐变描边无填充，hover 金色 glow |
| 23 | FAQ CTA 使用 `.hireai-fp__btn--primary`（黑底），设计稿为描边按钮 | 新增 `.hireai-fp__btn--outline-dark`：透明底 / 黑色边框 / hover 黑底白字 | Components: Buttons — 次按钮描边样式 |
| 24 | 产品区和案例区缺少左右箭头导航按钮 | 新增 `.hireai-fp__arrows` + `.hireai-fp__arrow-btn` 组件（48px 圆形边框按钮，桌面显示，移动隐藏） | Components: Navigation / 交互控件 |
| 25 | Hero scroll indicator 为 CSS 绘制的 45deg 箭头，设计稿为 Material Symbols `expand_more` | 替换为 `<span class="material-symbols-outlined">expand_more</span>` | Components: Iconography |

### Elevation & Depth

| # | 问题 | 修复 | 对应 DESIGN.md |
|---|------|------|----------------|
| 26 | Product / Solution 卡片 hover 仅有边框变色，缺少 ambient glow | 新增 `box-shadow: 0 0 40px rgba(233, 193, 118, 0.04)` | Elevation: Ambient Glows — blur 40px, opacity 4%, 金色 tint |
| 27 | 主按钮 hover 仅有 `box-shadow: 0 0 20px rgba(119,90,25,0.4)` | 叠加 ambient glow `0 0 40px rgba(233, 193, 118, 0.08)` | Elevation: Ambient Glows |

### Glassmorphism (AI 卡片)

| # | 问题 | 修复 | 对应 DESIGN.md |
|---|------|------|----------------|
| 28 | 当前产品卡片无 glassmorphism（纯白底），设计稿要求半透明 Ivory + blur(20px) + 1px 金色边框 | 当前版本使用 `#fff` 背景 + 浅边框，后续迭代可升级为 `backdrop-filter: blur(20px)` + `rgba(249,248,243,0.7)` + 金色边框。本次因 ACF 字段兼容性保留现有结构，已增加金色边框 hover glow | Elevation: AI Feature Cards |

### Responsive

| # | 问题 | 修复 | 对应 DESIGN.md |
|---|------|------|----------------|
| 29 | 移动端 section padding 为 `150px 20px` / `--side-pad: 30px` | 统一为 `120px 20px` / `20px` | Layout & Spacing: margin-mobile 20px |
| 30 | 移动端 hero title `clamp(22px, 4vw, 29px)` 偏小 | 改为 `clamp(28px, 6vw, 32px)` | Typography: headline-lg-mobile 32px |

---

## 自检结果

- [x] `php8.3 -l front-page.php` — 通过，无语法错误
- [x] `grep -c "{{" front-page.php` = 0
- [x] `grep -c "{%" front-page.php` = 0
- [x] `wp_add_inline_style` handle = `hireaipeople-style`，与 `wp_enqueue_style('hireaipeople-style', ...)` 一致
- [x] 未引入 cdn.tailwindcss.com / unpkg.com / lh3.googleusercontent
- [x] 保留 `Material+Symbols+Outlined` 字体链接
- [x] 保留 `$is_en` 双语逻辑 + ACF 字段
- [x] 保留短码 `[aurelian_home]`（未改动 functions.php）
- [x] 未执行 commit / push / tag / release

---

## final result

**blocked** — 因沙箱环境无法启动浏览器截图做 side-by-side 像素级比对。上述 30 项修复均基于 DESIGN.md / code.html / screen.png 的代码级逐项核对。建议在可渲染环境中进行最终视觉验证后再执行发布流程。

---

## 0819 页眉 + 页脚 1:1 还原（header.php / footer.php / style.css / functions.php）

### 对照设计稿逐项 → 已还原

| 设计稿 | 还原 | 状态 |
|--------|------|------|
| TopNavBar sticky-glass，surface 90% + blur | `.hai-header` `position:sticky` / `rgba(250,249,249,0.90)` / `backdrop-filter:blur(20px)` | ✅ |
| 三栏，内容 max 1440px 居中，左右 80px/20px | `.hai-header__inner` `max-width:1440px` + `padding:0 80px / 0 20px` | ✅ |
| 左栏主 nav，label-md 14px / Inter 600 / uppercase / 0.1em，current=primary+下划线 | `.hai-header__nav-list li a` | ✅ |
| 左栏 desktop only，移动端隐藏 | `.hai-header__nav` `display:none` + `@media(min-width:768px)` `flex` | ✅ |
| 中栏 Logo h-8 md:h-10 object-contain，ACF `header_logo` | `.hai-header__logo` 32px/40px | ✅ |
| 右栏：我的账户 label-sm 12px desktop only | `.hai-header__account` | ✅ |
| 语言 pill bordered，hover 反色 primary | `.hai-header__lang`，`hireaiSwitchLang` 保留 | ✅ |
| search 图标接首页搜索 | `.hai-header__search` + 隐藏 form `/?s=` | ✅ |
| menu 图标移动端 hamburger，联动 mobile-drawer | `#nav-toggle` + `main.js` drawer 逻辑保留 | ✅ |
| Footer 内容居中列，Logo h-12 md:h-16 | `.hai-footer__inner`（py 120px 对齐 section-gap）+ `.hai-footer__logo` 48px/64px | ✅ |
| 4 政策链接 body-md 16px Inter 400，on-surface-variant，hover 金色，flex wrap center | `.hai-footer__nav-list li a`；WP `footer` 菜单后备 + 4 个设计稿链接 | ✅ |
| 社交 3 图标 public/diamond/token，gap 32px，border-y 整行居中，24px，primary/60 hover primary | `.hai-footer__social` + Material Symbols | ✅ |
| 版权 body-md 16px，on-surface-variant，居中 | `.hai-footer__copyright`；保留 `footer_copyright` ACF + 2026 双语默认 | ✅ |
| functions.php 全局 inline override 与新结构冲突 | 已删除旧 `wp_add_inline_style` override，保留 handle `hireaipeople-style` | ✅ |

### 自检结果

- [x] `php8.3 -l header.php / footer.php / functions.php` — 均 No syntax errors
- [x] 四目标文件 `{{` / `{%` 残留 = 0
- [x] 四目标文件无 cdn.tailwindcss.com / unpkg.com / lh3.googleusercontent
- [x] 无 `wp_add_inline_style` 残留，handle `hireaipeople-style` 与 `wp_enqueue_style` 一致
- [x] `.site-header` / `.desktop-nav` / `.site-footer` 旧规则已替换为 `.hai-header` / `.hai-footer` 体系
- [x] header.php / footer.php 最终 class 名与 style.css 新 CSS 对齐
- [x] 保留双语 `hireai_lang_suffix()` / `hireaiSwitchLang`、ACF 字段、Woocommerce my-account、wp_nav_menu('primary') / ('footer')、mobile-drawer
- [x] 未执行 commit / push / tag / release

### final result（0819 页眉页脚）

**blocked** — 沙箱内无法启动浏览器渲染 WordPress 主题做像素级 side-by-side 比对；以上均基于 DESIGN.md / code.html 的代码级逐项核对完成，建议在可渲染 WP 环境做最终视觉确认后再发布。
