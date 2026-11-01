# QA 报告 — 聘AI v3.9.0 关于我们

## 基本信息

| 项目 | 值 |
|------|-----|
| 版本 | 3.9.0 |
| 多语言 | 中英双语（zh / en） |
| 检查日期 | 2026-10-26 |
| 检查人 | Codex CLI · auto-site-builder + Product Design audit |
| 发布状态 | 未提交 / 未打 tag / 未发布，等待 Sasha 视觉确认 |

## 检查结果

| 维度 | 状态 | 证据 |
|------|------|------|
| 视觉统一 | ✅ | 复用现有白金变量、Playfair/Inter/Noto 本地字体、4 个桌面区块均为 120px 上下留白 |
| 代码正确 | ✅ | 主题 PHP `php -l` 36/36 通过；`git diff --check` 无问题 |
| ACF 可编辑 | ✅ | 34 个页面字段 + 4 个中英 repeater 全部注册；模板字段无缺失注册 |
| 双语独立 | ✅ | zh/en 字段平行；英文 About Us 自动关联 Polylang 翻译 |
| 导航 | ✅ | 桌面 / 移动端 / footer 菜单均按 7 项顺序过滤；中英文文案符合需求 |
| 响应式 | ✅ | 1440 / 834 / 375 `scrollWidth` 均等于 viewport，无页面级横向溢出 |
| 资源合规 | ✅ | 无 Google Fonts / Material Symbols / Tailwind / unpkg / cdnjs / lh3 Google CDN；图片与图标本地或内联 SVG |
| 无障碍基础 | ✅ | 单 H1、图片 alt 可编辑、CTA 最小高度 52px、导航链接文本可读 |

## Product Design 审计

### 优点

1. **主题一致性优先**：未复制 Stitch 的 Tailwind/Material 体系；转译为聘AI现有白金、玻璃卡片、金色渐变标题与 4px 圆角语言。
2. **信息结构清晰**：品牌理念 → 人机共创 → 三段方法论 → CTA，保留设计稿叙事但文案换成聘AI品牌语境。
3. **导航稳定**：新增 About Us 后 7 项在 1024-1279px 仍不挤压账户/语言按钮；375px 抽屉可打开且同为 7 项。
4. **后台编辑覆盖完整**：Hero 数据、故事段落/引言、两张图片、方法论卡片、CTA、按钮链接与保障文案均可编辑。

### 已修复

- [P1] 存量案例页 4 个 `lh3.googleusercontent.com` 默认图片会依赖被墙 CDN；已替换为主题本地 `assets/img/defaults/case-*.jpg`。

### 证据限制

- 本环境不能直接进行人工目视读图；截图验收使用 Playwright DOM/computed style、资源加载状态、OCR 与图像统计交叉验证。最终视觉以 Sasha 打开下方截图确认为准。
- Stitch 参考稿为 478×1600 单视口导出，且需求明确主题规范优先；因此未做强制像素 diff，改为主题 token / 字体 / 间距 / 结构一致性检查。
- Polylang 与 ACF Multilingual 的实际后台语言 tab 需在 WP staging 复验；代码已按同一字段名 + `_zh/_en` 平行值实现。

## 渲染截图

| 语言 | 1440 | 834 | 375 | 菜单打开 |
|------|------|-----|-----|----------|
| 中文 | `/tmp/about-render/screenshots/about-zh-1440.png` | `/tmp/about-render/screenshots/about-zh-834.png` | `/tmp/about-render/screenshots/about-zh-375.png` | `/tmp/about-render/screenshots/about-zh-375-menu-open.png` |
| English | `/tmp/about-render/screenshots/about-en-1440.png` | `/tmp/about-render/screenshots/about-en-834.png` | `/tmp/about-render/screenshots/about-en-375.png` | `/tmp/about-render/screenshots/about-en-375-menu-open.png` |

## 最终结论

**通过 ✓（等待 Sasha 视觉确认后发布）**
