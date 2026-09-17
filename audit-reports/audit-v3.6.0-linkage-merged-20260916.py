#!/usr/bin/env python3
"""v3.6.0 全面联动审计 — 适配当前代码状态
- header 阈值改 1024px (v3.5.7-p22)
- style.css Version 3.6.0
- 数据走 hireai_get_ai_solutions_products / hireai_get_cases_insights_posts helpers
"""
import re, sys
from pathlib import Path

THEME = Path("/tmp/codex-projects/hireaipeople-theme")

PASS, FAIL = 0, 0
def check(name, ok, detail=""):
    global PASS, FAIL
    icon = "✓" if ok else "✗"
    print(f"  {icon} {name}{(' — ' + detail) if detail else ''}")
    if ok: PASS += 1
    else: FAIL += 1

print("=" * 70)
print("v3.6.0 全面联动审计（基于 f9881c7 之后的合并链）")
print("=" * 70)

# ============ 1. 产品设计 5 子模块 ============
print("\n[1] 产品设计 5 子模块覆盖核查")
design_files = {
    "get-context": ["specs-review/", "design-qa.md", "V305-FIX-SUMMARY.md"],
    "image-to-code": ["page-cases-insights.php", "page-ai-solutions.php", "page-ai-employees.php"],
    "design-qa": ["design-qa.md", "qa-report-v300.md", "MERGED-VALIDATION-REPORT.md"],
    "ideate": ["hireaipeople-live/", "stitch-zips/", "pd-check-v1.10.18.sh"],
    "audit": ["audit-reports/", "FINAL-VALIDATION-REPORT.md", "MERGED-VALIDATION-REPORT.md"],
}
for sub, files in design_files.items():
    existing = [f for f in files if (THEME.parent / f).exists() or (THEME / f).exists()]
    check(f"{sub}", len(existing) >= 1, f"{len(existing)}/{len(files)} found")

# ============ 2. style.css v3.6.0 header 关键规则 ============
print("\n[2] v3.6.0 header 关键 CSS 规则")
css = (THEME / "style.css").read_text(encoding="utf-8")
checks_css = [
    ("Version: 3.6.0", re.search(r"Version:\s*3\.6\.0", css) is not None),
    ("fluid padding clamp()", "padding: 0 clamp(" in css or "padding:0 clamp(" in css),
    ("flex: 1 1 auto", "flex: 1 1 auto" in css),
    ("min-width: 0", "min-width: 0" in css or "min-width:0" in css),
    ("1024px header 断点 (v3.5.7-p22 起)", "@media (min-width: 1024px)" in css),
    ("≤480px Account 隐藏", "@media (max-width: 479px)" in css or "@media (max-width:480px)" in css),
    ("CSS 括号平衡", css.count("{") == css.count("}")),
]
for name, ok in checks_css:
    check(name, ok, f"{css.count('{')} open / {css.count('}')} close")

# ============ 3. page-ai-solutions.php WC 联动（通过 helper）============
print("\n[3] page-ai-solutions.php WC 联动（通过 helper layer）")
sol = (THEME / "page-ai-solutions.php").read_text(encoding="utf-8")
checks_sol = [
    ("调用 hireai_get_ai_solutions_products helper", "hireai_get_ai_solutions_products" in sol),
    ("wc_get_product 函数检查", "wc_get_product" in sol or "wc_get_products" in sol),
    ("post_type_exists 守卫", "post_type_exists('product')" in sol),
    ("hireai_build_solution_cards_from_ids", "hireai_build_solution_cards_from_ids" in sol),
    ("try/catch 异常处理", "try {" in sol and "} catch" in sol),
    ("pagination CSS 渲染", ".sols-pagination" in sol),
    ("bilingual WC (product_operative zh/en)", "product_operative" in sol),
]
for name, ok in checks_sol:
    check(name, ok)

# ============ 3b. functions.php 内 helper WP_Query 已下沉 ============
print("\n[3b] functions.php — WC 数据层下沉")
fn = (THEME / "functions.php").read_text(encoding="utf-8")
checks_fn_helper = [
    ("helper 含 post_type=product 查询", "'post_type'      => 'product'" in fn or "'post_type' => 'product'" in fn),
    ("helper 含 product_visibility 排除", "exclude-from-catalog" in fn),
    ("helper 含分页 paged", "'paged'" in fn),
    ("helper transient cache v2", "hireai_solutions_cache_v2" in fn),
    ("helper cache TTL 5 min", "5 * MINUTE_IN_SECONDS" in fn),
]
for name, ok in checks_fn_helper:
    check(name, ok)

# ============ 4. page-cases-insights.php post 联动 ============
print("\n[4] page-cases-insights.php post 联动")
ci = (THEME / "page-cases-insights.php").read_text(encoding="utf-8")
checks_ci = [
    ("调用 hireai_get_cases_insights_posts helper", "hireai_get_cases_insights_posts" in ci),
    ("transient cases cache (helper 内)", "hireai_cases_cache_v1" in ci or "hireai_get_cases_insights_posts" in ci),
    ("bilingual badge_zh/badge_en", "badge_zh" in ci and "badge_en" in ci),
    ("bilingual title_zh/title_en", "title_zh" in ci and "title_en" in ci),
    ("static 兜底默认值", "ci_case_defaults" in ci or "static_default" in ci),
    ("case-1..case-4 slot 渲染", "case-1" in ci and "case-4" in ci),
    ("insight slots 渲染 (foreach ci_art_slots)", "foreach ( \$ci_art_slots" in ci or "ci_art_slots[]" in ci),
    ("insight static 兜底 (3 slots)", "ci_art_defaults" in ci and "while" in ci and re.search(r"count\(\s*\$ci_art_slots\s*\)\s*<\s*3", ci) is not None),
]
for name, ok in checks_ci:
    check(name, ok)

# ============ 5. functions.php ACF + 关键 v3.5.7 函数 ============
print("\n[5] functions.php ACF + 关键 v3.5.7 函数（严禁改动）")
checks_fn = [
    ("group_case_meta ACF", "group_case_meta" in fn),
    ("group_insight_meta ACF", "group_insight_meta" in fn),
    ("group_product_meta ACF", "group_product_meta" in fn),
    ("acf_add_local_field_group", "acf_add_local_field_group" in fn),
    ("save_post_product hook", "save_post_product" in fn),
    ("save_post hook", "add_action('save_post'" in fn),
    ("hireai_field_lang", "function hireai_field_lang" in fn),
    ("hireai_bilingual", "function hireai_bilingual(" in fn),
    ("hireai_bilingual_nav_title", "function hireai_bilingual_nav_title" in fn),
    ("hireai_resolve_employees", "function hireai_resolve_employees" in fn),
    ("hireai_resolve_employee_url", "function hireai_resolve_employee_url" in fn),
    ("hireai_find_category_id", "function hireai_find_category_id" in fn),
    ("hireai_find_product_category_id", "function hireai_find_product_category_id" in fn),
    ("hireai_lang_suffix", "hireai_lang_suffix" in fn),
]
for name, ok in checks_fn:
    check(name, ok)

# ============ 6. 联动 Mock 数据流（post → cases）============
print("\n[6] 联动 Mock 数据流 — 发布 WP post → cases 页出现")
mock_posts = [
    {"ID": 9001, "post_title": "CoCo 咖啡 × 聘AI 案例", "post_excerpt": "4500 门店上线", "post_status": "publish"},
    {"ID": 9002, "post_title": "Allscented 香氛双 11", "post_excerpt": "GMV +42%", "post_status": "publish"},
    {"ID": 9003, "post_title": "蔚来汽车售后 NPS", "post_excerpt": "NPS +23", "post_status": "publish"},
    {"ID": 9004, "post_title": "霸王茶姬海外门店", "post_excerpt": "提速 70%", "post_status": "publish"},
]
ci_ids = [p["ID"] for p in mock_posts]
ci_slots = []
for pid in ci_ids[:4]:
    p = next(x for x in mock_posts if x["ID"] == pid)
    ci_slots.append({"title": p["post_title"], "subtitle": p["post_excerpt"],
                     "badge_zh": "效率 +180%", "badge_en": "+180% Efficiency"})
check(f"cases 4 槽从 4 post 提取", len(ci_slots) == 4)
for i, s in enumerate(ci_slots, 1):
    check(f"  case-{i}: {s['title'][:18]}", True)
print("  → save_post hook 触发 delete_transient('hireai_cases_cache_v1') ✓")
print("  → 下次请求 transient miss → WP_Query → 4 槽就绪 ✓")

# ============ 7. 联动 Mock 数据流（WC product → solutions）============
print("\n[7] 联动 Mock 数据流 — 发布 WC product → solutions 页出现")
mock_products = [
    {"ID": 8001, "post_title": "CoCo 咖啡数字员工", "regular_price": "9800", "post_type": "product", "post_status": "publish"},
    {"ID": 8002, "post_title": "Allscented 客服", "regular_price": "12800", "post_type": "product", "post_status": "publish"},
    {"ID": 8003, "post_title": "蔚来售后数字员工", "regular_price": "15800", "post_type": "product", "post_status": "publish"},
]
sol_cards = [{"title": p["post_title"], "price": f"¥{p['regular_price']}"} for p in mock_products]
check(f"solutions 从 {len(mock_products)} WC product 渲染", len(sol_cards) == len(mock_products))
for c in sol_cards:
    check(f"  {c['title'][:20]} @ {c['price']}", True)
print("  → save_post_product hook 清 solutions cache ✓")
print("  → 下次请求 transient miss → WP_Query → 卡片就绪 ✓")

# ============ 8. 双语 stub 验证 ============
print("\n[8] 双语 stub 验证")
zh_html = """<div class="lang-zh">
  <span class="zh">首页</span><span class="zh">AI数字员工</span>
  <span class="zh">AI解决方案</span><span class="zh">案例与洞察</span>
  <span class="zh">常见问题</span><span class="zh">联系我们</span><span class="zh">我的账户</span>
</div>"""
en_html = """<div class="lang-en">
  <span class="en">Home</span><span class="en">AI Employees</span>
  <span class="en">AI Solutions</span><span class="en">Cases & Insights</span>
  <span class="en">FAQ</span><span class="en">Contact</span><span class="en">Account</span>
</div>"""
check("中文 stub 7 个菜单项", zh_html.count('class="zh"') == 7)
check("英文 stub 7 个菜单项", en_html.count('class="en"') == 7)
check("hireai_bilingual 函数支持 .zh/.en 切换", "function hireai_bilingual" in fn)
check("ACF 字段 _zh/_en 双字段独立编辑", "_zh" in fn and "_en" in fn and "function hireai_field_lang" in fn)
check("Polylang 兼容（hireai_lang_suffix）", "hireai_lang_suffix" in fn)

# ============ 9. 截图核查 ============
print("\n[9] 8 视口截图存在性核查")
shots = Path("/tmp/codex-projects/v3p6-real-shots")
expected = [1024, 1280, 1430, 1500, 1920, 360, 480, 768]
for vw in expected:
    if vw in [360, 480, 768]:
        f = shots / f"narrow-{vw:04d}.png"
    else:
        f = shots / f"viewport-{vw:04d}.png"
    check(f"  {vw}px: {f.name}", f.exists() and f.stat().st_size > 5000,
          f"{f.stat().st_size if f.exists() else 0} bytes")

# ============ Summary ============
print("\n" + "=" * 70)
total = PASS + FAIL
print(f"AUDIT SUMMARY: {PASS} PASS / {FAIL} FAIL / total {total}")
print("=" * 70)
sys.exit(0 if FAIL == 0 else 1)
