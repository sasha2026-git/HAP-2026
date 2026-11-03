# Design QA — WooCommerce Single Product v3.11.6

## Evidence

- Source visual truth: `/tmp/d1.jpg` (Sasha's before-state screenshot).
- Implementation evidence: `/tmp/live-product-patched.png` (live WordPress/WooCommerce page rendered with the patched child stylesheet injected over the old deployed stylesheet).
- Combined comparison: `/tmp/single-product-design-qa-comparison.png`.
- Viewport: `1439 x 980` browser viewport; full-page capture `1439 x 1500`.
- State: logged-out Chinese single product at `1439 x 660`; WooCommerce, LiteSpeed, Hello Elementor, and Polylang assets loaded as in production.

## Full-View Comparison

The before evidence shows only the oversized brand header and no product content. Chromium measured the old product-page logo at `2007 x 555`, pushing gallery and summary below the `660 px` fold while both reveal regions remained `opacity: 0`. The patched page measured the logo at `203 x 56`; gallery and summary became visible at `y=161`, with the Chinese title and price in the first viewport.

## Focused Comparison

- Typography/hierarchy: category chip, display title, price, feature headings, add-to-cart, and contact actions form a readable descending hierarchy in the live render.
- Spacing/layout rhythm: the desktop split keeps gallery left and summary right; the corrected header restores normal content position without overflow.
- Color/tokens: contact and add-to-cart affordances retain the gold `--gold-leaf` direction; text colors resolve to the existing charcoal/muted-surface tokens.
- Copy/content: live Chinese product copy renders correctly; the static contract render verified hook order and English fallback selection with `_en`.

## Findings

- No actionable P0/P1/P2 visual differences were found in the static contract render.

## Comparison History

1. First patch review found a potential duplicate H1 because the custom template title and `woocommerce_single_product_summary` default title callback would both render. Removed the default title callback while retaining the custom gold H1.
2. Chromium inspection established the P0 CSS override: WooCommerce's `.woocommerce img` rule out-ranked the child theme logo rule and enlarged the brand mark to intrinsic size. Added higher-specificity `body.woocommerce` logo rules.
3. Price layout was adjusted so the custom retainer label and WooCommerce price remain one row. Contact actions were moved before meta to preserve the previous summary order.
4. Post-fix live Chrome render showed no actionable layout, typography, color, or hierarchy mismatch.

## Residual Test Gap

The live screenshot injects the patched stylesheet while the deployed PHP remains v3.11.5; the patched PHP template is therefore covered by the static hook contract render, not this live capture. An unstaged English product translation still needs backend/staging verification.

Final result: passed
