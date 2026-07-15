=== AURELIAN Blog ===
Contributors: aurelianai
Plugin URI: https://aurelian.ai
Tags: blog, case studies, acf, luxury, shortcode, glassmorphism
Requires at least: 6.0
Tested up to: 6.7
Stable tag: 1.0.0
Requires PHP: 8.0
Requires Plugins: advanced-custom-fields
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

案例&观点 (Blog & Case Studies) — 使用短码 [aurelian_blog] 插入页面。v2 设计系统：Aurelian Digital Excellence。

== Description ==

AURELIAN Blog 是一个 WordPress 短码插件，创建 "案例&观点" 页面，包含：

* **Hero** — "The Atelier of Intelligence" 金色标签 + "Crafting Digital Humanity" (Humanity 为斜体) + 描述段落
* **Collaborative Excellence (案例展示)** — 3行×2列玻璃卡片网格，含行业标签、烫金指标、标题、描述、编辑线 + "Read Case Study" 链接
* **The Intelligence Journal (观点期刊)** — 6篇文章，3列×2行网格，aspect-[4/5] 图片带 hover 放大效果
* **Newsletter 订阅** — 玻璃拟态卡片："Subscribe to the Atelier"，含 email 输入框 + "Join the Atelier" 按钮 + 隐私声明
* **Footer 页脚** — Logo、5个导航链接、分隔线、版权声明、3个社交媒体图标 (public/chat/mail)
* **自定义光标** — 12px 金色圆点，mix-blend-mode: difference，悬停交互元素时放大4倍
* **滚动动画** — Intersection Observer 驱动的内容淡入效果
* **分页导航** — 案例区（圆点分页）+ 期刊区（页码01/02/03）
* **玻璃拟态卡片** — 严格遵循 DESIGN.md v2 规格：rgba(249,248,243,0.7) 背景 + blur(20px) + 1px 金边
* **烫金渐变文字** — 线性渐变 (#775a19 → #e9c176 → #775a19)

所有内容均可通过 ACF (Advanced Custom Fields) 在页面编辑界面中可视化编辑。

== Installation ==

1. 确保已安装并激活 Advanced Custom Fields (ACF) 免费版插件。
2. 上传 `aurelian-blog-plugin.zip` 到 WordPress 后台 → 插件 → 安装插件 → 上传插件。
3. 激活插件。
4. 创建或编辑一个 WordPress 页面，在内容区域插入短码：`[aurelian_blog]`
5. 保存页面后，ACF 字段组会自动出现在页面编辑界面中。
6. 直接在 ACF 字段中编辑内容，或保持默认值。

== Usage ==

Shortcode: `[aurelian_blog]`

将该短码放置在任意 WordPress 页面或文章的内容编辑器中即可渲染整个 Blog 页面。

== ACF Fields Structure ==

所有字段按 Tab 分组：

1. **🏛️ Hero** — Hero Badge、Hero Title Line 1、Hero Title Italic Word、Hero Subtitle
2. **📋 Case Studies** — Section Title、"View Directory" 链接、Repeater（图片、行业标签、指标、标题、描述、链接）
3. **📝 Intelligence Journal** — Section Title、Subtitle、Repeater（图片、分类、标题、是否斜体、描述、阅读时间、链接）
4. **✉️ Newsletter** — 标题、描述、输入框占位符、按钮文字、隐私声明、表单提交 URL
5. **🦶 Footer** — 品牌名、Logo 图片、版权文字、导航链接 Repeater

== Default Content ==

插件内置了完整的默认英文内容：
* 6个案例卡片（Luxury Retail / Private Banking / Automotive / Hospitality / Horology / Real Estate）
* 6篇期刊文章（含斜体标题：The Ghost in the Machine / The New White Glove）
* 5个 Footer 链接（Brand Story / Sustainability / Privacy / Terms of Service / Contact）

即使不保存任何 ACF 字段，页面也能立即呈现精美效果。

== Design System (v2) ==

本插件完全遵循 "Aurelian Digital Excellence" v2 设计系统：

* **配色**: 象牙白底 (#faf9f9) + 深炭色文字 (#1a1c1c) + 香槟金点缀 (#775a19) + 烫金渐变 (#775a19→#e9c176→#775a19)
* **字体**: Playfair Display (标题/展示) + Inter (正文/标签)
* **风格**: 极简建筑美学 + 玻璃拟态 (Glassmorphism) + 金属质感金
* **圆角**: DEFAULT=0.25rem / lg=0.5rem / xl=0.75rem / full=9999px
* **间距**: section-gap=120px / gutter=24px / margin-desktop=80px / margin-mobile=20px
* **字号**: display-lg=72px / headline-lg=48px / headline-md=32px / body-lg=18px / label-md=14px
* **玻璃卡片**: rgba(249,248,243,0.7) 背景 + blur(20px) + 1px rgba(119,90,25,0.1) 边框
* **按钮**: 胶囊型 (rounded-full) + 深炭色背景 + 金色悬停发光
* **输入框**: 底部边框仅 1px + 聚焦时金色过渡
* **自定义光标**: 12px 金色圆点 + mix-blend-mode: difference

== Accessibility ==

* 跳转链接 (Skip Link)
* ARIA 标签 (aria-label) + 装饰性元素 aria-hidden="true"
* 语义化 HTML5 标签（article, nav, footer, section）
* prefers-reduced-motion 媒体查询（禁用所有动画）
* 屏幕阅读器隐藏标签 (.sr-only 模式)
* 键盘可访问的交互元素

== Changelog ==

= 1.0.0 =
* Initial release (v2 design system)
* Hero with italic word support
* Case Studies 3×2 glass-card grid with burnished gold metrics
* Intelligence Journal 3-col grid with aspect-[4/5] images
* Newsletter glassmorphism card
* Footer with public/chat/mail Material Symbols
* Custom cursor (12px gold dot, mix-blend-mode: difference)
* Intersection Observer scroll animations
* Pagination: dots (case studies) + page numbers (journal)
* ACF tab-based field groups with repeater support
* Default content fallbacks with null coalescing
* Tailwind CSS CDN + Playfair Display + Inter + Material Symbols
* Border radius: 0.25rem DEFAULT per DESIGN.md v2
* Accessibility: skip link, ARIA labels, aria-hidden, reduced motion
* Astra theme CSS reset
