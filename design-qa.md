# Design QA — Front Page ACF Update

final result: passed

## Evidence

- Source visual truth: `qa-screens/front-page-acf-source.jpg`
- Implementation screenshot: `qa-screens/front-page-acf-live-zh.png`
- Side-by-side comparison: `qa-screens/front-page-acf-compare.png`
- Viewport: 1435×436
- State: logged-out Chinese homepage, default Products section copy, desktop
- Full-view evidence: OCR and positional comparison of the eyebrow, title, subtitle, and Explore link all match the intended copy. Eyebrow/title/subtitle horizontal starts align at approximately x=72; the remaining text-position delta is local font rasterization, not layout drift.
- Focused-region evidence: not needed because the supplied 436px-tall target contains only the section header, not card imagery or interaction states.

## Required Surfaces

- Fonts and typography: 12px uppercase kicker, 48px headline, 16px body copy, and uppercase Explore link retain the source hierarchy.
- Spacing and layout: section background, 80px desktop side padding, text stack, and bottom divider remain aligned with the supplied target.
- Colors and tokens: light surface (`#f4f3f3`), black text, muted body text, and gold hover token remain unchanged by the ACF data-source fix.
- Image quality: no image assets are present in the supplied header crop; no visual change was made.
- Copy and content: `限量神经元系列`, `AI 数字员工`, `每一位数字员工都拥有独特的灵魂、技能与能力，随时加入您的团队。`, and `探索更多` match exactly.

## Findings

No P0/P1/P2 findings. The code change locks ACF reads to the current-language front page and adds missing fields; it intentionally does not alter markup, typography, spacing, color, or default copy.

## Comparison History

- Pass 1: compared the supplied header against the live Chinese homepage at the same viewport. No P0/P1/P2 differences; no fix iteration required.

## Verification Boundary

The screenshot is the live v3.10.6 default state. The v3.10.7 code changes were not deployed during QA, so a saved ACF override cannot yet be visually verified on production. Because the patch changes only field-source resolution and ACF registration, this default-state comparison validates no visual regression.
