# QA Report — About Us v3.10.5

## Scope

- Corrected the section 01 image layers in the bilingual About Us template.
- Restored the floating quote-card ACF fields and removed the pill-badge ACF field.
- Bumped the child theme version from 3.10.4 to 3.10.5.
- No commit, tag, Release, ZIP, or publish operation was performed.

## Changes

- Kept the section 01 bottom caption layer removed (`AI Speed. Human Warmth.` / `AI 速度，人类温度。`).
- Removed the top-right pill badge (`Symbiosis Model 4.0` / `共生模型 4.0`) from markup, CSS, and ACF registration.
- Restored the bottom-right white quote card with `HARMONIC PARADIGM` / `我们的价值观` and its bilingual value-quote copy, including its mobile stacked placement.
- Preserved the unrelated v3.10.4 heading, layout, team section, and hero spacing changes.

## Validation

- `php -l`: 165 PHP files checked, 0 failures.
- Runtime-code template residue: 0 PHP/CSS/JS/HTML files containing `{{ }}` or `{% %}` patterns.
- Runtime-code blocked-CDN scan: 0 references to `cdn.tailwindcss.com` or `unpkg.com`.
- `git diff --check`: clean.
- Target-string audit: pill badge and bottom caption references in `page-about.php` / `functions.php` = 0; quote-card fields in both files = present.
- Static bilingual rendering with mocked WordPress/ACF helpers:
  - Desktop 1440px Chinese and English: `.hireai-about-story__pill` count 0; `.hireai-about-story__overlay` count 0; `.hireai-about-story__card` count 1.
  - Desktop card copy: `我们的价值观` + `技术的终极目的不是替代人类，而是放大人的雄心与想象力。` in Chinese; `HARMONIC PARADIGM` + the matching English quote in English.
  - Mobile 390px Chinese: the same three node counts pass and the card moves below the photograph instead of overflowing the viewport.
  - Desktop card geometry: Chinese card `x=429.33, y=807.78, width=384, height=148.09`; English card `x=429.33, y=766.23, width=384, height=175.64` within the section 01 image area.
- Product-design screenshots generated and checked through DOM assertions, geometry, OCR, and element captures: `/tmp/hireai-design-audit-v3105/`.

## Limits

- This is a local template/code verification, not a logged-in WordPress/Polylang/ACF backend pass.
- Existing saved `about_story_image_pill` postmeta may remain in the database; with no template or ACF field registration, it is not rendered or editable through this field group.
