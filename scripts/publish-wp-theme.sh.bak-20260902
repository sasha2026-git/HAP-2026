#!/usr/bin/env bash
# ============================================================================
# 方案 B v3.0.0 发布脚本 — 由 Sasha 在有网络的机器上执行
# ============================================================================
# 用法：
#   1. 在本机 git pull (或下载 ZIP 解压)
#   2. cd /path/to/HAP-2026-v222
#   3. bash _briefs/PUBLISH-V300.sh
# ============================================================================
set -euo pipefail

echo "====== 方案 B v3.0.0 发布 ======"

# 1. 验证工作目录与分支
echo "[1/6] 验证 git 状态..."
git rev-parse --show-toplevel >/dev/null
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
if [ "$CURRENT_BRANCH" != "main" ]; then
  echo "错误：当前分支 $CURRENT_BRANCH，必须在 main 上"
  exit 1
fi
HEAD_COMMIT=$(git rev-parse HEAD)
HEAD_MSG=$(git log -1 --pretty=%s)
echo "  分支: $CURRENT_BRANCH"
echo "  HEAD: $HEAD_COMMIT"
echo "  信息: $HEAD_MSG"

# 2. 验证 style.css Version
echo ""
echo "[2/6] 验证 style.css Version..."
STYLE_VERSION=$(grep '^Version:' style.css | awk '{print $2}')
if [ "$STYLE_VERSION" != "3.0.0" ]; then
  echo "错误：style.css Version 是 $STYLE_VERSION，期望 3.0.0"
  exit 1
fi
echo "  Version: $STYLE_VERSION ✓"

# 3. 验证本地 tag
echo ""
echo "[3/6] 验证 tag v3.0.0..."
if ! git rev-parse v3.0.0 >/dev/null 2>&1; then
  echo "错误：tag v3.0.0 不存在"
  exit 1
fi
echo "  tag v3.0.0 已存在 ✓"

# 4. 推 main 分支
echo ""
echo "[4/6] git push origin main..."
git push origin main

# 5. 推 tag
echo ""
echo "[5/6] git push origin v3.0.0..."
git push origin v3.0.0

# 6. 打包 ZIP + 上传 Release
echo ""
echo "[6/6] 打包 + 创建 GitHub Release..."
THEME_DIR=$(basename "$(git rev-parse --show-toplevel)")
TMP_ZIP="/tmp/${THEME_DIR}-v3.0.0.zip"

cd "$(dirname "$(git rev-parse --show-toplevel)")"
zip -r "$TMP_ZIP" "$THEME_DIR" \
  -x "${THEME_DIR}/.git*" \
  -x "${THEME_DIR}/_briefs/*-audit.md" \
  -x "${THEME_DIR}/_briefs/PLAN-B-V300-FULL.md" \
  -x "${THEME_DIR}/_briefs/PUBLISH-V300.sh" \
  -x "${THEME_DIR}/_brief-v226-audit.md" \
  -x "${THEME_DIR}/audit-report-v226.md"
echo "  ZIP 已生成: $TMP_ZIP ($(du -h "$TMP_ZIP" | awk '{print $1}'))"

gh release create v3.0.0 "$TMP_ZIP" \
  --title "v3.0.0 - 全站重做：基于 Stitch 设计稿从零重建" \
  --notes "## 重大变更

- 全站从零重做，基于 Stitch 设计稿（2026-08-22）
- 6 个核心页面 + single.php 全部接入 ACF 字段组
- 修复 v2.2.6 全部 BLOCKER
- 修复 12 个 WARNING + 8 个 SUGGESTION
- 删除所有死字段
- 风格：白金奢华 + Glassmorphism

## 升级说明

- ⚠️ 新字段组与 v2.2.6 不兼容，需要在 WP 后台重新填内容
- v2.2.6 备份在 archive/v2.2.x 分支 + tag v2.2.6-archived-20260822

## 自检结果

- ✅ PHP php -l：15 个文件全部通过
- ✅ 被墙 CDN 命中 = 0
- ✅ 模板字符串残留 = 0
- ✅ 硬编码密钥 = 0
- ✅ ACF 调用：每页 ≥ 8 处（总计 159 处）

## hireai_field 调用明细

- front-page.php: 56
- page-ai-solutions.php: 24
- page-ai-employees.php: 18
- page-cases-insights.php: 15
- page-faq.php: 18
- page-contact.php: 20
- single.php: 8
- 总计: 159 处"

echo ""
echo "====== ✅ 方案 B v3.0.0 已发布 ======"
echo "Release URL: https://github.com/sasha2026-git/HAP-2026/releases/tag/v3.0.0"
