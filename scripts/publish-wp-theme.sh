#!/usr/bin/env bash
# ============================================================================
# 通用 WP 主题发布脚本 v2.0（2026-09-02 升级）
# ============================================================================
# 用途：发布任何版本（正式版或 patch 版），自动门禁 tag == Version
#
# 用法：
#   cd /path/to/HAP-2026
#   bash scripts/publish-wp-theme.sh
#
# 可选环境变量：
#   VERSION=v3.5.7-p4 bash scripts/publish-wp-theme.sh  # 指定版本（默认从 style.css 读）
#   SKIP_PUSH=true bash scripts/publish-wp-theme.sh      # 只本地不推送（dry-run）
# ============================================================================
set -euo pipefail

# ===== 颜色输出 =====
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

err() { echo -e "${RED}❌ $1${NC}" >&2; exit 1; }
ok()  { echo -e "${GREEN}✅ $1${NC}"; }
warn() { echo -e "${YELLOW}⚠️  $1${NC}"; }

echo "====== 🚀 WP 主题发布脚本 v2.0 ======"

# ===== Step 0: 读取 VERSION =====
echo ""
echo "[0/9] 读取目标版本..."
if [ -n "${VERSION:-}" ]; then
    TARGET_VERSION="$VERSION"
    echo "  从环境变量: $TARGET_VERSION"
else
    TARGET_VERSION=$(grep '^Version:' style.css | awk '{print $2}')
    if [ -z "$TARGET_VERSION" ]; then
        err "style.css 没有 Version 字段"
    fi
    echo "  从 style.css: $TARGET_VERSION"
fi

# v 前缀只用在 TAG_NAME，TARGET_VERSION 保持纯版本号
TAG_NAME="v${TARGET_VERSION}"
# 规范化 TARGET_VERSION（去掉前导 v，避免比较时不一致）
TARGET_VERSION="${TARGET_VERSION#v}"

# ===== Step 1: 验证分支 =====
echo ""
echo "[1/9] 验证分支..."
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
HEAD_COMMIT=$(git rev-parse HEAD)
HEAD_MSG=$(git log -1 --pretty=%s)
echo "  分支: $CURRENT_BRANCH"
echo "  HEAD: $HEAD_COMMIT"
echo "  信息: $HEAD_MSG"

# ===== Step 2: 工作目录干净检查 =====
echo ""
echo "[2/9] 检查工作目录..."
if ! git diff --quiet HEAD 2>/dev/null; then
    warn "有未提交改动"
    git status --short
    read -p "  继续？(y/N) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        err "用户中断"
    fi
fi
ok "工作目录干净或已确认"

# ===== Step 3: ⛔ 铁律门禁：tag == style.css Version =====
echo ""
echo "[3/9] ⛔ 铁律门禁：Version 字段验证..."
STYLE_VERSION=$(grep '^Version:' style.css | awk '{print $2}')
STYLE_VERSION="${STYLE_VERSION#v}"  # 去掉可能的 v 前缀
TARGET_VERSION_NOV="${TARGET_VERSION#v}"  # 双保险
if [ "$STYLE_VERSION" != "$TARGET_VERSION_NOV" ]; then
    err "style.css Version ($STYLE_VERSION) 与目标版本 ($TARGET_VERSION) 不一致

请先修改 style.css：
  sed -i 's/^Version:.*$/Version: $TARGET_VERSION/' style.css

铁律：WP 通过 style.css: Version 字段判断更新，tag 名称不会被识别。
历史教训：v3.5.7-p3 / v3.5.7-p4 曾因未 bump version 导致 WP 检测不到更新。"
fi
ok "Version: $STYLE_VERSION"

# ===== Step 4: 验证本地无同名 tag（或已删除） =====
echo ""
echo "[4/9] 验证 tag 状态..."
if git rev-parse "$TAG_NAME" >/dev/null 2>&1; then
    EXISTING_TAG_COMMIT=$(git rev-parse "${TAG_NAME}^{}")
    if [ "$EXISTING_TAG_COMMIT" != "$HEAD_COMMIT" ]; then
        warn "tag $TAG_NAME 已存在但指向旧 commit ($EXISTING_TAG_COMMIT)"
        warn "当前 HEAD: $HEAD_COMMIT"
        echo "  选 y = 删旧 tag 重打（指向新 HEAD）"
        echo "  选 N = 退出（你可能搞错了）"
        read -p "  继续？(y/N) " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            err "用户中断"
        fi
        git tag -d "$TAG_NAME"
        ok "已删旧 tag"
    else
        ok "tag $TAG_NAME 指向正确（当前 HEAD）"
    fi
fi

# ===== Step 5: PHP 语法检查 =====
echo ""
echo "[5/9] PHP 语法检查..."
PHP_ERRORS=0
for f in $(find . -name "*.php" -not -path "./.git/*"); do
    if ! php -l "$f" >/dev/null 2>&1; then
        warn "PHP 语法错误: $f"
        php -l "$f"
        PHP_ERRORS=$((PHP_ERRORS + 1))
    fi
done
if [ "$PHP_ERRORS" -gt 0 ]; then
    err "$PHP_ERRORS 个 PHP 文件语法错误"
fi
ok "PHP 语法 0 错误"

# ===== Step 6: 关键函数完整性（v3.0.8 教训） =====
echo ""
echo "[6/9] 关键函数完整性..."
REQUIRED_FNS=("hireai_lang_suffix" "hireai_field" "hireai_field_lang" "hireai_image" "hireai_image_lang" "hireai_link" "hireai_link_lang" "site_field" "site_link" "site_image_url" "hireai_resolve_employees")
MISSING_FNS=()
for fn in "${REQUIRED_FNS[@]}"; do
    if ! grep -q "^function $fn" functions.php 2>/dev/null; then
        MISSING_FNS+=("$fn")
    fi
done
if [ ${#MISSING_FNS[@]} -gt 0 ]; then
    err "functions.php 缺少关键函数: ${MISSING_FNS[*]}

铁律：任何 functions.php 重构不得删除被调用的函数（v3.0.8 教训）
"
fi
ok "11 个关键函数完整"

# ===== Step 7: 推送分支 =====
echo ""
echo "[7/9] 推送分支..."
if [ "${SKIP_PUSH:-}" = "true" ]; then
    warn "SKIP_PUSH=true，跳过推送"
else
    git push origin "$CURRENT_BRANCH"
    ok "分支 $CURRENT_BRANCH 已推送"
fi

# ===== Step 8: 打 tag + 推送 =====
echo ""
echo "[8/9] 打 tag + 推送..."
if [ "${SKIP_PUSH:-}" = "true" ]; then
    git tag -a "$TAG_NAME" -m "$TAG_NAME: $HEAD_MSG"
    warn "SKIP_PUSH=true，tag 仅本地"
else
    git tag -a "$TAG_NAME" -m "$TAG_NAME: $HEAD_MSG"
    git push origin "$TAG_NAME"
    ok "tag $TAG_NAME 已推送"

    # 强制同步：tag 必须指向 main HEAD
    REMOTE_PEEL=$(git ls-remote origin "refs/tags/${TAG_NAME}^{}" | awk '{print $1}')
    if [ "$REMOTE_PEEL" != "$HEAD_COMMIT" ]; then
        warn "tag 远程 peeled ($REMOTE_PEEL) != HEAD ($HEAD_COMMIT)，强制修正"
        git push origin ":refs/tags/$TAG_NAME"
        git push origin "$TAG_NAME"
        ok "tag 已重新指向当前 HEAD"
    fi
fi

# ===== Step 9: 打包 ZIP + 上传 Release =====
echo ""
echo "[9/9] 打包 ZIP + 创建/更新 Release..."
THEME_DIR=$(basename "$(git rev-parse --show-toplevel)")
TMP_ZIP="/tmp/${THEME_DIR}-${TAG_NAME}.zip"

cd "$(dirname "$(git rev-parse --show-toplevel)")"
zip -qr "$TMP_ZIP" "$THEME_DIR" \
    -x "${THEME_DIR}/.git*" \
    -x "${THEME_DIR}/_publish-*.sh" \
    -x "${THEME_DIR}/scripts/*" \
    -x "${THEME_DIR}/qa-report.md" \
    -x "${THEME_DIR}/audit-reports/audit-v3.5.5-no-change-20260901*" \
    -x "${THEME_DIR}/design-qa.md" \
    -x "${THEME_DIR}/*.bak*" \
    -x "${THEME_DIR}/audit-reports/fix-*.patch"
echo "  ZIP: $TMP_ZIP ($(du -h "$TMP_ZIP" | awk '{print $1}'))"

# 验证 ZIP 内的 Version 字段
ZIP_VERSION=$(unzip -p "$TMP_ZIP" style.css 2>/dev/null | grep '^Version' | awk '{print $2}')
ZIP_VERSION="${ZIP_VERSION#v}"
if [ "$ZIP_VERSION" != "$TARGET_VERSION_NOV" ]; then
    err "ZIP 内的 Version ($ZIP_VERSION) != 目标版本 ($TARGET_VERSION)
打包时可能漏了 style.css 更新，请检查。"
fi
ok "ZIP Version: $ZIP_VERSION"

if [ "${SKIP_PUSH:-}" = "true" ]; then
    warn "SKIP_PUSH=true，跳过 Release 上传"
else
    # 检查 Release 是否存在
    if gh release view "$TAG_NAME" --repo sasha2026-git/HAP-2026 >/dev/null 2>&1; then
        ok "Release $TAG_NAME 已存在，上传/覆盖 ZIP..."
        gh release upload "$TAG_NAME" "$TMP_ZIP" --repo sasha2026-git/HAP-2026 --clobber
    else
        ok "创建 Release $TAG_NAME..."
        gh release create "$TAG_NAME" "$TMP_ZIP" \
            --repo sasha2026-git/HAP-2026 \
            --title "$TAG_NAME - $HEAD_MSG" \
            --notes "## 改动

$HEAD_MSG

## 验证

- ✅ style.css Version = $TARGET_VERSION
- ✅ tag 指向 main HEAD
- ✅ 11 个关键函数完整
- ✅ PHP 语法 0 错误

## 安装

1. WP 后台 → 外观 → 主题 → 添加新主题 → 上传 ZIP
2. 替换当前主题
3. LiteSpeed Cache → Purge All
4. 浏览器 Ctrl+F5" \
            --draft=false --latest=true

        # 把旧版 Latest 标 false
        # 注意：仅当 target 是 patch 时处理（保留前一正式版 Latest 状态较复杂，跳过）
    fi
fi

echo ""
echo "====== ✅ $TAG_NAME 已发布 ======"
echo "Release: https://github.com/sasha2026-git/HAP-2026/releases/tag/$TAG_NAME"
echo "ZIP:     $TMP_ZIP"
