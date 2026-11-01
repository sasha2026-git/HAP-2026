# QA Report — About Us v3.10.4

## Scope

- Updated the bilingual About Us template and local ACF field registration.
- Bumped the child theme version from 3.10.3 to 3.10.4.
- No commit, tag, Release, ZIP, or publish operation was performed.

## Changes

- Kept section 01 as a desktop image-left / copy-right grid and removed the two image text overlays: the bottom overlay (`AI Speed. Human Warmth.` / `AI 速度，人类温度。`) and the floating quote card (`Harmonic Paradigm` / `和谐范式`).
- Matched section 01, 02, and new section 03 heading typography to the Cases & Insights section-title token: `32px`, weight `600`, line height `1.2`.
- Left the section 02 three-step methodology layout and bottom CTA structure unchanged.
- Added bilingual ACF-backed section 03 fields for number, title, intro, image, and image alt text. Defaults use `employee-2.jpg` until an editor selects language-specific media.
- Reduced the About Us hero's post-bar bottom spacing from `60px` to `40px`, exactly one third.

## Screenshot Judgment

Only one of the three referenced screenshots was mounted in this session (`a6490d46-9a3a-4beb-a7ff-0fabd81c18ea.jpg`), and the chat tool reported image pixels as unavailable. I therefore inspected it with dimensions, color-boundary analysis, and Chinese/English OCR. It contains the hero and the `01 — 关于聘AI (HIREAIPEOPLE)` marker, but not visible boxes around the two removed overlays.

The two removed boxes were identified from the current template and existing full-page QA captures: the bottom image overlay and the floating white quote card are the only two boxed text layers on the section 01 photograph. The small top-right pill badge (`Symbiosis Model 4.0`) was retained because it is a badge rather than one of the two boxed caption layers.

## Validation

- `php -l`: 165 PHP files checked, 0 failures.
- Template residue: 0 files containing `{{ }}` or `{% %}` patterns.
- Blocked CDN scan: 0 references to `cdn.tailwindcss.com` or `unpkg.com`.
- `git diff --check`: clean.
- Static bilingual rendering with mocked WordPress/ACF helpers:
  - Desktop 1440px: section 01 uses a two-column grid; section 01/02/03 headings compute to `32px`; removed overlay/card node count is 0; hero bottom padding computes to `40px`; CTA retains two actions.
  - Mobile 390px: section 01 collapses to one column; headings remain `32px`; removed node count remains 0; CTA retains two actions.
  - Chinese and English renders both contain the new section 03 title and unchanged CTA actions.
- Product-design screenshots generated and inspected by DOM/OCR/pixel statistics: `/tmp/hireai-design-audit-v3104/`.

## Limits

- This is a local static template render, not a logged-in WordPress/Polylang/ACF backend test. ACF editor visibility and language-specific saved media still need a staging backend pass before release.
- The page does not introduce category queries, so the multilingual category mechanism is unchanged.
