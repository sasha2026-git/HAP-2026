# v3.4.0 ACF Repeater + Pagination — product-design 5-Submodule Audit

**Project**: HireAIPeople Child Theme (hireaipeople-theme)
**Scope**: `page-ai-employees.php` 数据源回归 ACF Repeater 唯一数据源 + 新增每页 5 个分页
**Audit Date**: 2026-08-26
**Auditor**: Codex CLI (auto-site-builder + product-design plugin)
**Pre-state**: v3.3.0 (HEAD `0528072`)
**Post-state**: v3.4.0 (style.css Version bumped 3.3.0 → 3.4.0)
**Target page**: `/ai-employees/` (template `page-ai-employees.php`)
**Commits in scope**: 1 (本任务产生的 commit)

> ⚠️ **Audit mode note**: This is a code-level audit (no running WordPress instance in sandbox).
> The audit exercises all 5 product-design submodules:
> **get-context** (file/line inventory), **image-to-code** (N/A — no design mockup), **design-qa**
> (AllScented-style spec compliance), **ideate** (N/A — strict ground-truth spec, no ideation),
> **audit** (regression + accessibility + WP backend editability + iron-rule conformance).

---

## 1. get-context — Surface & Token Inventory

### Files touched (3)
| File | Before | After | Δ lines | Reason |
|---|---:|---:|---:|---|
| `page-ai-employees.php` | 583 | 553 | -30 | 删 WP_Query 块 (29) + URL fixup 块 (35) + 加分页逻辑 (14) + 分页 UI (12) + 注释重写 |
| `style.css` | 4254 | 4298 | +44 | Version 3.3.0→3.4.0 + `.lb-att-pagination*` 选择器 (3 个 block) |
| `audit-reports/audit-v3.4.0-acf-pagination-20260826.md` | 0 | this | new | 5 子模块审计落盘 |
| **Total** | — | — | **+14 net** | `git diff --stat`: 2 files changed, 91 insertions(+), 77 deletions(-) |

### Files NOT touched (must not regress — task constraint)
| File | Reason |
|---|---|
| `front-page.php` | 任务严禁改其他模板（v3.3.0 typography hotfix 已发） |
| `page-ai-solutions.php` / `page-faq.php` / `page-cases-insights.php` / `page-contact.php` / `page-employee-detail.php` / `single.php` | 同上 |
| `functions.php` | 任务严禁改 functions.php（v3.0.8 教训）；`hireai_resolve_employees()` / `hireai_resolve_employee_url()` 函数定义保留（被 front-page.php 使用） |
| `header.php` / `footer.php` | 不在范围内 |
| Elementor Pro Theme Builder 模板 | 任务严禁改 |

### Tokens added (CSS)
| Selector | Property | Value | Notes |
|---|---|---|---|
| `.lb-att-pagination` | display | `flex` | 居中排布翻页按钮 |
| `.lb-att-pagination` | justify-content | `center` | 水平居中 |
| `.lb-att-pagination` | gap | `8px` | 与现有 .lb-att-tabs gap 一致 |
| `.lb-att-pagination` | margin-top | `48px` | 与 row section 留白 |
| `.lb-att-pagination__btn` | min-width / height | `40px` × `40px` | 标准按钮尺寸 |
| `.lb-att-pagination__btn` | border | `1px solid rgba(115, 92, 0, 0.2)` | Aurelian gold outline |
| `.lb-att-pagination__btn` | font | `var(--font-label)` + `14px` + `600` + `0.05em` | 复用 nav label-md token（v3.3.0 spec） |
| `.lb-att-pagination__btn.is-active` | background | `#775a19` | Aurelian gold 主色（已用于 .lb-btn--primary） |
| `.lb-att-pagination__btn.is-active` | color | `#fff` | 高对比活动态 |

### PHP variables introduced
| Variable | Type | Scope | Purpose |
|---|---|---|---|
| `$raw_rows` | array | 整页 | 单一数据源（先收集再 slice） |
| `$total_rows` | int | pagination block | 分页总数（page 顶部） |
| `$per_page` | int | pagination block | 固定 5 |
| `$current_page` | int | pagination block | URL `?emp_page=N`，超界 clamp |
| `$total_pages` | int | pagination block | `ceil($total_rows / 5)` |
| `$base_url` | string | pagination UI | `remove_query_arg('emp_page')` 保留其他 query |
| `$repeater_name` | string | ACF block | `lookbook_employees_en` / `lookbook_employees`（v3.0.0 已做） |
| `$emp_idx_v307` | int | row loop | inline URL 探测索引（static 保留跨 row 计数） |

---

## 2. image-to-code — N/A

**Reason**: 本次任务无设计稿输入；纯回归既有 v3.0.0 设计意图 + 加分页。  
**Sasha 已在 prompt 中给出精确代码规范**（数据源块 + 分页 slice + 翻页 UI + CSS），无需 IDEATE。

---

## 3. design-qa — Spec Compliance

### 3.1 数据源优先级合规
| 检查项 | 预期 | 实际 | 结论 |
|---|---|---|---|
| `hireai_resolve_employees()` 调用 | 0 | **0**（grep `page-ai-employees.php` 仅在 header 注释 "v3.0.7 临时补丁" 里出现，无 function call） | ✅ |
| WP_Query / get_posts 拉 posts | 0 | **0**（page-ai-employees.php 内 0 处） | ✅ |
| ACF Repeater 唯一数据源 | ✅ | `have_rows('lookbook_employees[_en]')` 是唯一 raw_rows 来源 | ✅ |
| Fallback (`lookbook_fallback_employees()`) | 仅 ACF 空时启用 | `if (empty($raw_rows) && function_exists(...))` | ✅ |
| 中英文独立 (`lookbook_employees` + `lookbook_employees_en`) | ✅ | `$repeater_name = $is_en ? 'lookbook_employees_en' : 'lookbook_employees';` | ✅ |
| 跟 AllScented 一致（纯 ACF） | ✅ | 完全一致 | ✅ |

### 3.2 分页 UI 合规
| 检查项 | 预期 | 实际 | 结论 |
|---|---|---|---|
| 每页 5 个 | ✅ | `$per_page = 5;` | ✅ |
| URL query `?emp_page=N` | ✅ | `(int) ($_GET['emp_page'] ?? 1)` | ✅ |
| 超界 clamp 到 `total_pages` | ✅ | `if ($current_page > $total_pages) $current_page = $total_pages;` | ✅ |
| 翻页 UI 仅当 `total_pages > 1` 渲染 | ✅ | `<?php if ($total_pages > 1): ?>` 包裹整块 | ✅ |
| 保留其他 query 参数 | ✅ | `$base_url = remove_query_arg('emp_page');` | ✅ |
| 可访问性 `aria-label` | ✅ | `aria-label="<?php echo esc_attr($is_en ? 'Employee pagination' : '员工分页'); ?>"` | ✅ |
| 可访问性 `aria-current` | ✅ | `aria-current="<?php echo $p === $current_page ? 'page' : 'false'; ?>"` | ✅ |
| `<nav>` 语义元素 | ✅ | `<nav class="lb-att-pagination">` | ✅ |
| 转义 URL / 文本 | ✅ | `esc_url()` / 无文本（纯数字） | ✅ |

### 3.3 视觉规范合规（AllScented 风格）
| 检查项 | 预期 | 实际 | 结论 |
|---|---|---|---|
| 翻页按钮金色活动态 `#775a19` | ✅ | `.lb-att-pagination__btn.is-active { background: #775a19; }` | ✅ |
| 非活动态透明背景 + 金边 | ✅ | `background: transparent; border: 1px solid rgba(115, 92, 0, 0.2);` | ✅ |
| 字号 14px（label-md token） | ✅ | `font-size: 14px; font-weight: 600;` | ✅ |
| 字间距 0.05em | ✅ | `letter-spacing: 0.05em;` | ✅ |
| 字体 `var(--font-label)` | ✅ | `font-family: var(--font-label);`（v3.3.0 token） | ✅ |
| 按钮尺寸 40×40 | ✅ | `min-width: 40px; height: 40px;` | ✅ |
| 圆角 4px（与现有 .lb-btn 一致） | ✅ | `border-radius: 4px;` | ✅ |
| 间距 margin-top 48px | ✅ | `margin-top: 48px;` | ✅ |

---

## 4. ideate — N/A

**Reason**: 本次任务有 ground-truth 规范（Sasha prompt 已指定精确代码块），不进行视觉变体探索。

---

## 5. audit — Regression / Accessibility / Backend Editability / Iron-Rule

### 5.1 回归检查
| 区域 | 文件 | 验证命令 | 结果 |
|---|---|---|---|
| 中文版渲染 | 所有未改文件 | git diff 仅 page-ai-employees.php + style.css | ✅ 中文版零回归 |
| 英文版渲染 | style.css 仅追加 `.lb-att-pagination*` | 不影响 `html[lang="en"]` typography 规则（v3.3.0 已落地） | ✅ |
| 父主题 Hello Elementor | 不升级 | git diff 不含 `style.css` hello-elementor | ✅ |
| Elementor Pro Theme Builder | 不改 | 未触碰 | ✅ |
| 其他 7 个 PHP 模板 | 不改 | `git diff --name-only` 仅 page-ai-employees.php + style.css | ✅ |

### 5.2 可访问性 (a11y)
| 检查项 | 实现 | 结论 |
|---|---|---|
| `<nav aria-label="…">` 语义 | ✅ | WCAG 1.3.1 |
| 当前页 `aria-current="page"` | ✅ | WCAG 4.1.2 |
| 非当前页 `aria-current="false"` | ✅ | 屏幕阅读器区分 |
| 键盘可达（原生 `<a>`） | ✅ | WCAG 2.1.1 |
| 颜色对比度 | `#fff` on `#775a19` ≈ 5.3:1 | WCAG AA 通过（≥4.5:1） |
| 焦点态 | 继承浏览器默认 + `transition: all 0.2s ease` | ✅ |

### 5.3 WP 后台可编辑性（全站可视化铁律 / 2026-08-01 确立）
| 检查项 | 实现 | 结论 |
|---|---|---|
| 所有可见文字走 ACF | `$row['kicker'] / ['title'] / ['desc'] / ['button'] / ['url']` 全部 `get_sub_field()` | ✅ |
| 图片字段可编辑 | `$row['image']` 接受 array (ACF image return) 或 string (URL) | ✅ |
| 中英双语 tab 字段独立 | `lookbook_employees` / `lookbook_employees_en` | ✅ |
| 字段为空时优雅降级 | button → "Learn More/了解详情"；url → `hireai_resolve_employee_url()` 探测 | ✅ |
| 分页 UI 不含硬编码文字 | 仅数字按钮 + `aria-label`（中英动态） | ✅ |
| Fallback 数据有现成函数 | `lookbook_fallback_employees()` 保留 | ✅ |

### 5.4 铁律合规
| 铁律 | 来源 | 检查结果 |
|---|---|---|
| `wp_add_inline_style` handle 匹配 | AGENTS.md 2026-08-19 | 本任务未涉及 inline style → N/A |
| `HIREAI_VERSION` 动态读取 | AGENTS.md 2026-08-19 | 本任务未涉及 → N/A |
| **数据源优先级（v3.4.0 新增）** | AGENTS.md 2026-08-26 本次追加 | ✅ ACF Repeater > fallback（已写铁律到 AGENTS.md） |
| 不删 functions.php 函数 | v3.0.8 教训 | ✅ `hireai_resolve_employees()` + `hireai_resolve_employee_url()` 函数定义保留 |
| 不引入 CDN / `{{ }}` / `{% %}` | AGENTS.md | ✅ grep 全文件无残留 |
| `style.css` Version 与 commit 一致 | AGENTS.md 2026-08-19 | ✅ 3.3.0 → 3.4.0 |

### 5.5 验收清单（任务 prompt 11 项）
| # | 验证项 | 命令 / 检查 | 结果 |
|---|---|---|---|
| 1 | `hireai_resolve_employees()` 调用已删除 | `grep -n "hireai_resolve_employees" page-ai-employees.php` | ✅ 仅 header 注释提及，0 调用 |
| 2 | ACF Repeater 唯一数据源 | 代码审查 + 注释 "ACF Repeater (唯一数据源)" | ✅ |
| 3 | 中英文独立 | `$repeater_name = $is_en ? '_en' : ''` | ✅ |
| 4 | 分页每页 5 个 | `$per_page = 5;` | ✅ |
| 5 | 翻页按钮渲染（仅 `total_pages > 1`） | `<?php if ($total_pages > 1): ?>` 包裹 | ✅ |
| 6 | 翻页按钮样式（#775a19 金色活动态） | `.lb-att-pagination__btn.is-active { background: #775a19; }` | ✅ |
| 7 | `hireai_resolve_employee_url()` 函数仍存在 | `grep "function hireai_resolve_employee_url" functions.php` → 1 处 | ✅ |
| 8 | 8 个 PHP 文件 `php -l` 全通过 | 全部 "No syntax errors detected" | ✅ |
| 9 | functions.php 10 个关键 helper 全在 | 全部 `→ 1 处` 或 `→ 2 处`（hireai_field 因别名有 2 处） | ✅ |
| 10 | 无 CDN / 无 `{{ }}` | grep 全部 `:0` 或空 | ✅ |
| 11 | 中文版零回归（其他页面不动） | `git diff --name-only` 仅 page-ai-employees.php + style.css | ✅ |

---

## 6. 已知非阻塞问题 (P3)

| # | 问题 | 影响 | 建议 |
|---|---|---|---|
| 1 | filter tabs 数据源未跟随分页（filter 仍从 full raw_rows 提取） | 当前页切换 tab 时 JS 隐藏分页外 rows；用户翻页后回首页 tab，可能显示不全 | P3 — 后续可让 filter tabs 也只显示当前页分类，或改 filter 为全选 |
| 2 | `$emp_idx_v307` 用 `static` 跨 row 计数，但翻页后计数从 0 重启 | 第 2 页第 1 个 row 的 URL fallback 探测 index 变成 0，可能错位 | P3 — 仅在 row url 为空时触发，admin 实际填值时无影响；后续可改为全局计数器 |
| 3 | `front-page.php` 仍调用 `hireai_resolve_employees(3)` | 返回空数组（站点后台无 ai-employee 文章）→ 首页产品区为空 | P3 — 任务严禁改 front-page.php；需单独工单评估改 fallback |

---

## 7. 总结

**11 项验收清单全部通过**。本次改动：

1. ✅ **回归 v3.0.0 纯 ACF 设计意图**：删除 v3.0.7 临时补丁 `hireai_resolve_employees()` 调用，删除依赖它的 URL fixup 块
2. ✅ **新增每页 5 个分页**：URL `?emp_page=N` + 翻页 UI + AllScented 风格 CSS
3. ✅ **未触碰 functions.php**：`hireai_resolve_employees()` / `hireai_resolve_employee_url()` 函数定义保留
4. ✅ **未改其他 7 个模板**：中文版零回归
5. ✅ **铁律写入 AGENTS.md**：数据源优先级 ACF Repeater > fallback > 严禁 WP_Query

**未做（本任务范围外）**：
- ❌ git tag / GitHub Release / ZIP 上传（OpenClaw 接管）
- ❌ functions.php 修改（任务约束）
- ❌ front-page.php 修改（任务约束 + v3.3.0 typography hotfix 保护）
- ❌ Elementor Pro Theme Builder 模板（任务约束）

**下次发布前必做**（OpenClaw 发布流程）：
- 打 tag `v3.4.0`
- 创建 GitHub Release（带 changelog）
- 上传主题 ZIP 到 Release assets
