<?php
/**
 * Plugin Name: AURELIAN Blog
 * Plugin URI: https://github.com/sasha2026-git/HireAIPeople
 * GitHub Plugin URI: sasha2026-git/HireAIPeople
 * Description: 案例&观点 (Blog & Case Studies) — 3×2 Case Study grid + Intelligence Journal + Newsletter + Footer. Design system v2: Aurelian Digital Excellence.
 * Version:     2.1.0
 * Author:      Aurelian Digital Excellence
 * Requires Plugins: advanced-custom-fields
 * Text Domain: aurelian-blog
 *
 * Shortcode: [aurelian_blog]
 * Function prefix: ahai_
 * ACF group key: group_aurelian_blog
 */

if (!defined('ABSPATH')) exit;

// GitHub Updater (Plugin Update Checker)
require_once __DIR__ . '/lib/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$ablog_update_checker = PucFactory::buildUpdateChecker(
    'https://github.com/sasha2026-git/HireAIPeople',
    __FILE__,
    'aurelian-blog-plugin'
);
$ablog_update_checker->setDirectoryName('aurelian-blog-plugin');

   0. ACF Dependency Check
   ============================================================ */
add_action('admin_notices', 'ahai_blog_check_acf');
function ahai_blog_check_acf() {
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="notice notice-warning"><p><strong>AURELIAN Blog</strong>: '
            . esc_html__('This plugin requires Advanced Custom Fields (ACF) to be installed and activated.', 'aurelian-blog')
            . '</p></div>';
    }
}

/* ============================================================
   1. ACF Local Field Groups — group_aurelian_blog
   ============================================================ */
add_action('acf/include_fields', 'ahai_blog_register_acf_fields');
function ahai_blog_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'      => 'group_aurelian_blog',
        'title'    => '📰 案例&观点 (Blog) — Page Content Editor',
        'fields'   => [

            // ===========================
            // TAB: Hero
            // ===========================
            [
                'key'   => 'field_ahai_blog_tab_hero',
                'label' => '🏛️ Hero',
                'type'  => 'tab',
                'placement' => 'top',
            ],
            [
                'key'           => 'field_ahai_blog_hero_badge',
                'label'         => 'Hero Badge',
                'name'          => 'ahai_blog_hero_badge',
                'type'          => 'text',
                'default_value' => 'The Atelier of Intelligence',
                'placeholder'   => 'e.g. The Atelier of Intelligence',
                'wrapper'       => ['width' => 50],
            ],
            [
                'key'           => 'field_ahai_blog_hero_title_line1',
                'label'         => 'Hero Title — Line 1 (before line break)',
                'name'          => 'ahai_blog_hero_title_line1',
                'type'          => 'text',
                'default_value' => 'Crafting Digital',
                'placeholder'   => 'e.g. Crafting Digital',
                'wrapper'       => ['width' => 50],
            ],
            [
                'key'           => 'field_ahai_blog_hero_title_italic',
                'label'         => 'Hero Title — Italic Word (line 2, italic)',
                'name'          => 'ahai_blog_hero_title_italic',
                'type'          => 'text',
                'default_value' => 'Humanity',
                'placeholder'   => 'e.g. Humanity',
                'wrapper'       => ['width' => 50],
            ],
            [
                'key'           => 'field_ahai_blog_hero_subtitle',
                'label'         => 'Hero Subtitle / Description',
                'name'          => 'ahai_blog_hero_subtitle',
                'type'          => 'textarea',
                'default_value' => 'Where technical precision meets heritage aesthetic. Discover our latest case studies and industry journals.',
                'rows'          => 3,
                'new_lines'     => 'br',
            ],

            // ===========================
            // NEW: Hero Background & Typography
            // ===========================
            [
                'key'           => 'field_ahai_blog_hero_bg',
                'label'         => 'Hero Background Image',
                'name'          => 'ahai_blog_hero_bg',
                'type'          => 'image',
                'return_format' => 'url',
                'preview_size'  => 'large',
                'wrapper'       => ['width' => 50],
                'instructions'  => 'Recommended: 1920x1080, dark/neutral tone for readability.',
            ],
            [
                'key'           => 'field_ahai_blog_hero_title_size',
                'label'         => 'Hero Title Font Size (px)',
                'name'          => 'ahai_blog_hero_title_size',
                'type'          => 'number',
                'default_value' => 56,
                'min'           => 24,
                'max'           => 120,
                'step'          => 2,
                'wrapper'       => ['width' => 33],
                'instructions'  => 'Default: 56px. For larger screens only.',
            ],
            [
                'key'           => 'field_ahai_blog_section_title_size',
                'label'         => 'Section Title Font Size (px)',
                'name'          => 'ahai_blog_section_title_size',
                'type'          => 'number',
                'default_value' => 40,
                'min'           => 18,
                'max'           => 96,
                'step'          => 2,
                'wrapper'       => ['width' => 33],
                'instructions'  => 'Default: 40px. For Case Studies & Intelligence Journal section titles.',
            ],

            // ===========================
            // TAB: Case Studies
            // ===========================
            [
                'key'   => 'field_ahai_blog_tab_cases',
                'label' => '📋 Case Studies',
                'type'  => 'tab',
                'placement' => 'top',
            ],
            [
                'key'           => 'field_ahai_blog_cases_section_title',
                'label'         => 'Section Title',
                'name'          => 'ahai_blog_cases_section_title',
                'type'          => 'text',
                'default_value' => 'Collaborative Excellence',
                'wrapper'       => ['width' => 50],
            ],
            [
                'key'           => 'field_ahai_blog_cases_dir_label',
                'label'         => '"View Directory" Link Text',
                'name'          => 'ahai_blog_cases_dir_label',
                'type'          => 'text',
                'default_value' => 'View Directory',
                'wrapper'       => ['width' => 25],
            ],
            [
                'key'           => 'field_ahai_blog_cases_dir_url',
                'label'         => '"View Directory" Link URL',
                'name'          => 'ahai_blog_cases_dir_url',
                'type'          => 'url',
                'default_value' => '#',
                'wrapper'       => ['width' => 25],
            ],
            [
                'key'          => 'field_ahai_blog_cases_repeater',
                'label'        => 'Case Study Cards (6 items = 3×2 grid)',
                'name'         => 'ahai_blog_case_studies',
                'type'         => 'repeater',
                'layout'       => 'block',
                'button_label' => '➕ Add Case Study',
                'collapsed'    => 'field_ahai_blog_cs_title',
                'min'          => 0,
                'max'          => 12,
                'sub_fields'   => [
                    [
                        'key'           => 'field_ahai_blog_cs_image',
                        'label'         => 'Cover Image (aspect-ratio: 3/2)',
                        'name'          => 'ahai_blog_cs_image',
                        'type'          => 'image',
                        'return_format' => 'url',
                        'preview_size'  => 'large',
                        'wrapper'       => ['width' => 50],
                    ],
                    [
                        'key'           => 'field_ahai_blog_cs_industry',
                        'label'         => 'Industry Tag',
                        'name'          => 'ahai_blog_cs_industry',
                        'type'          => 'text',
                        'default_value' => 'Luxury Retail',
                        'placeholder'   => 'e.g. Luxury Retail / Private Banking / Automotive',
                        'wrapper'       => ['width' => 25],
                    ],
                    [
                        'key'           => 'field_ahai_blog_cs_metric',
                        'label'         => 'Key Metric (Gold Text)',
                        'name'          => 'ahai_blog_cs_metric',
                        'type'          => 'text',
                        'default_value' => '+42% Conversion',
                        'placeholder'   => 'e.g. +42% Conversion / 24/7 Elite Access',
                        'wrapper'       => ['width' => 25],
                    ],
                    [
                        'key'           => 'field_ahai_blog_cs_title',
                        'label'         => 'Case Title',
                        'name'          => 'ahai_blog_cs_title',
                        'type'          => 'text',
                        'default_value' => 'The Digital Concierge: Maison de Couture',
                    ],
                    [
                        'key'           => 'field_ahai_blog_cs_desc',
                        'label'         => 'Description (2-3 lines)',
                        'name'          => 'ahai_blog_cs_desc',
                        'type'          => 'textarea',
                        'rows'          => 3,
                        'default_value' => 'Implementing a hyper-realistic digital human to guide high-net-worth individuals through private showroom collections in the metaverse.',
                    ],
                    [
                        'key'           => 'field_ahai_blog_cs_link',
                        'label'         => '"Read Case Study" Link',
                        'name'          => 'ahai_blog_cs_link',
                        'type'          => 'url',
                        'default_value' => '#',
                    ],
                ],
            ],

            // ===========================
            // TAB: Intelligence Journal
            // ===========================
            [
                'key'   => 'field_ahai_blog_tab_journal',
                'label' => '📝 Intelligence Journal',
                'type'  => 'tab',
                'placement' => 'top',
            ],
            [
                'key'           => 'field_ahai_blog_ja_section_title',
                'label'         => 'Section Title',
                'name'          => 'ahai_blog_ja_section_title',
                'type'          => 'text',
                'default_value' => 'The Intelligence Journal',
                'wrapper'       => ['width' => 50],
            ],
            [
                'key'           => 'field_ahai_blog_ja_section_subtitle',
                'label'         => 'Section Subtitle',
                'name'          => 'ahai_blog_ja_section_subtitle',
                'type'          => 'text',
                'default_value' => 'Industry Insights & Thought Leadership',
                'wrapper'       => ['width' => 50],
            ],
            [
                'key'          => 'field_ahai_blog_ja_repeater',
                'label'        => 'Journal Articles (6 items, 3-col × 2 rows)',
                'name'         => 'ahai_blog_journal_articles',
                'type'         => 'repeater',
                'layout'       => 'block',
                'button_label' => '➕ Add Article',
                'collapsed'    => 'field_ahai_blog_ja_title',
                'min'          => 0,
                'max'          => 20,
                'sub_fields'   => [
                    [
                        'key'           => 'field_ahai_blog_ja_image',
                        'label'         => 'Article Image (aspect-ratio: 4/5)',
                        'name'          => 'ahai_blog_ja_image',
                        'type'          => 'image',
                        'return_format' => 'url',
                        'preview_size'  => 'large',
                        'wrapper'       => ['width' => 50],
                    ],
                    [
                        'key'           => 'field_ahai_blog_ja_category',
                        'label'         => 'Category Tag',
                        'name'          => 'ahai_blog_ja_category',
                        'type'          => 'text',
                        'default_value' => 'Aesthetics',
                        'placeholder'   => 'e.g. Aesthetics / Technology / Strategy',
                        'wrapper'       => ['width' => 50],
                    ],
                    [
                        'key'           => 'field_ahai_blog_ja_title',
                        'label'         => 'Article Title',
                        'name'          => 'ahai_blog_ja_title',
                        'type'          => 'text',
                        'default_value' => 'The Ghost in the Machine: Defining AI Beauty',
                    ],
                    [
                        'key'           => 'field_ahai_blog_ja_is_italic',
                        'label'         => 'Title in Italic?',
                        'name'          => 'ahai_blog_ja_is_italic',
                        'type'          => 'true_false',
                        'ui'            => 1,
                        'default_value' => 0,
                        'wrapper'       => ['width' => 25],
                    ],
                    [
                        'key'           => 'field_ahai_blog_ja_desc',
                        'label'         => 'Description',
                        'name'          => 'ahai_blog_ja_desc',
                        'type'          => 'textarea',
                        'rows'          => 3,
                        'default_value' => 'Why heritage brands are moving beyond uncanny valley into hyper-stylized digital representations.',
                    ],
                    [
                        'key'           => 'field_ahai_blog_ja_readtime',
                        'label'         => 'Read Time',
                        'name'          => 'ahai_blog_ja_readtime',
                        'type'          => 'text',
                        'default_value' => '08 MIN READ',
                        'wrapper'       => ['width' => 50],
                    ],
                    [
                        'key'           => 'field_ahai_blog_ja_link',
                        'label'         => 'Article Link',
                        'name'          => 'ahai_blog_ja_link',
                        'type'          => 'url',
                        'default_value' => '#',
                        'wrapper'       => ['width' => 50],
                    ],
                ],
            ],

            // ===========================
            // TAB: Newsletter
            // ===========================
            [
                'key'   => 'field_ahai_blog_tab_newsletter',
                'label' => '✉️ Newsletter',
                'type'  => 'tab',
                'placement' => 'top',
            ],
            [
                'key'           => 'field_ahai_blog_nl_title',
                'label'         => 'Title',
                'name'          => 'ahai_blog_nl_title',
                'type'          => 'text',
                'default_value' => 'Subscribe to the Atelier',
                'wrapper'       => ['width' => 50],
            ],
            [
                'key'           => 'field_ahai_blog_nl_desc',
                'label'         => 'Description',
                'name'          => 'ahai_blog_nl_desc',
                'type'          => 'textarea',
                'rows'          => 2,
                'default_value' => 'Receive exclusive insights on the intersection of heritage luxury and artificial intelligence. Monthly curated journals.',
                'wrapper'       => ['width' => 50],
            ],
            [
                'key'           => 'field_ahai_blog_nl_placeholder',
                'label'         => 'Input Placeholder',
                'name'          => 'ahai_blog_nl_placeholder',
                'type'          => 'text',
                'default_value' => 'EMAIL ADDRESS',
                'wrapper'       => ['width' => 33],
            ],
            [
                'key'           => 'field_ahai_blog_nl_btn',
                'label'         => 'Button Text',
                'name'          => 'ahai_blog_nl_btn',
                'type'          => 'text',
                'default_value' => 'Join the Atelier',
                'wrapper'       => ['width' => 33],
            ],
            [
                'key'           => 'field_ahai_blog_nl_privacy',
                'label'         => 'Privacy Text',
                'name'          => 'ahai_blog_nl_privacy',
                'type'          => 'text',
                'default_value' => 'RESPECTING YOUR PRIVACY SINCE 2024.',
                'wrapper'       => ['width' => 34],
            ],
            [
                'key'           => 'field_ahai_blog_nl_form_action',
                'label'         => 'Form Action URL (optional — leave empty for no submission)',
                'name'          => 'ahai_blog_nl_form_action',
                'type'          => 'url',
                'default_value' => '',
                'wrapper'       => ['width' => 50],
            ],

            // ===========================
            // TAB: Footer
            // ===========================
            [
                'key'   => 'field_ahai_blog_tab_footer',
                'label' => '🦶 Footer',
                'type'  => 'tab',
                'placement' => 'top',
            ],
            [
                'key'           => 'field_ahai_blog_ft_brand_name',
                'label'         => 'Brand Name (fallback if no logo)',
                'name'          => 'ahai_blog_ft_brand_name',
                'type'          => 'text',
                'default_value' => 'AURELIAN',
                'wrapper'       => ['width' => 34],
            ],
            [
                'key'           => 'field_ahai_blog_ft_logo',
                'label'         => 'Footer Logo Image (optional)',
                'name'          => 'ahai_blog_ft_logo',
                'type'          => 'image',
                'return_format' => 'url',
                'preview_size'  => 'large',
                'wrapper'       => ['width' => 33],
            ],
            [
                'key'           => 'field_ahai_blog_ft_copyright',
                'label'         => 'Copyright Text',
                'name'          => 'ahai_blog_ft_copyright',
                'type'          => 'text',
                'default_value' => '© 2024 HIREAIPEOPLE. THE FRONTIER OF DIGITAL HUMANITY.',
                'wrapper'       => ['width' => 33],
            ],
            [
                'key'          => 'field_ahai_blog_ft_links',
                'label'        => 'Footer Nav Links (5 items)',
                'name'         => 'ahai_blog_ft_links',
                'type'         => 'repeater',
                'layout'       => 'table',
                'button_label' => '➕ Add Link',
                'min'          => 0,
                'max'          => 10,
                'sub_fields'   => [
                    [
                        'key'           => 'field_ahai_blog_ft_link_label',
                        'label'         => 'Link Text',
                        'name'          => 'ahai_blog_ft_link_label',
                        'type'          => 'text',
                        'default_value' => 'Brand Story',
                        'wrapper'       => ['width' => 50],
                    ],
                    [
                        'key'           => 'field_ahai_blog_ft_link_url',
                        'label'         => 'Link URL',
                        'name'          => 'ahai_blog_ft_link_url',
                        'type'          => 'url',
                        'default_value' => '#',
                        'wrapper'       => ['width' => 50],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'page',
                ],
            ],
        ],
        'position'        => 'normal',
        'style'           => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'  => [],
    ]);
}

/* ============================================================
   2. Default Content — beautiful out-of-the-box
   ============================================================ */

function ahai_blog_default_case_studies() {
    return [
        [
            'ahai_blog_cs_image'    => '',
            'ahai_blog_cs_industry' => 'Luxury Retail',
            'ahai_blog_cs_metric'   => '+42% Conversion',
            'ahai_blog_cs_title'    => 'The Digital Concierge: Maison de Couture',
            'ahai_blog_cs_desc'     => 'Implementing a hyper-realistic digital human to guide high-net-worth individuals through private showroom collections in the metaverse.',
            'ahai_blog_cs_link'     => '#',
        ],
        [
            'ahai_blog_cs_image'    => '',
            'ahai_blog_cs_industry' => 'Private Banking',
            'ahai_blog_cs_metric'   => '24/7 Elite Access',
            'ahai_blog_cs_title'    => 'Wealth Management 2.0: The AI Advisor',
            'ahai_blog_cs_desc'     => 'Seamlessly blending human financial expertise with the tireless processing power of a dedicated AI wealth ambassador.',
            'ahai_blog_cs_link'     => '#',
        ],
        [
            'ahai_blog_cs_image'    => '',
            'ahai_blog_cs_industry' => 'Automotive',
            'ahai_blog_cs_metric'   => 'Next-Gen Interface',
            'ahai_blog_cs_title'    => 'The Virtual Co-Pilot: Grand Tourer Experience',
            'ahai_blog_cs_desc'     => 'Reimagining the luxury driving experience through an emotive, AI-driven digital companion that anticipates passenger needs.',
            'ahai_blog_cs_link'     => '#',
        ],
        [
            'ahai_blog_cs_image'    => '',
            'ahai_blog_cs_industry' => 'Hospitality',
            'ahai_blog_cs_metric'   => 'Ultra-Personalized',
            'ahai_blog_cs_title'    => 'Digital Grandeur: The Sovereign Hotel AI',
            'ahai_blog_cs_desc'     => 'Elevating high-end hospitality with a multilingual digital butler capable of managing every detail of a guest\'s stay with absolute discretion.',
            'ahai_blog_cs_link'     => '#',
        ],

    ];
}

function ahai_blog_default_journal_articles() {
    return [
        [
            'ahai_blog_ja_image'     => '',
            'ahai_blog_ja_category'  => 'Aesthetics',
            'ahai_blog_ja_title'     => 'The Ghost in the Machine: Defining AI Beauty',
            'ahai_blog_ja_is_italic' => 1,
            'ahai_blog_ja_desc'      => 'Why heritage brands are moving beyond uncanny valley into hyper-stylized digital representations.',
            'ahai_blog_ja_readtime'  => '08 MIN READ',
            'ahai_blog_ja_link'      => '#',
        ],
        [
            'ahai_blog_ja_image'     => '',
            'ahai_blog_ja_category'  => 'Technology',
            'ahai_blog_ja_title'     => 'Neural Networks & Silk: The Fabric of Future Service',
            'ahai_blog_ja_is_italic' => 0,
            'ahai_blog_ja_desc'      => 'Scaling personalized attention to millions without losing the \'human\' touch of exclusivity.',
            'ahai_blog_ja_readtime'  => '12 MIN READ',
            'ahai_blog_ja_link'      => '#',
        ],
        [
            'ahai_blog_ja_image'     => '',
            'ahai_blog_ja_category'  => 'Strategy',
            'ahai_blog_ja_title'     => 'The New White Glove: AI as the Ultimate Concierge',
            'ahai_blog_ja_is_italic' => 1,
            'ahai_blog_ja_desc'      => 'Examining the evolution of loyalty in an era of automated high-end experiences.',
            'ahai_blog_ja_readtime'  => '06 MIN READ',
            'ahai_blog_ja_link'      => '#',
        ],

    ];
}

function ahai_blog_default_footer_links() {
    return [
        ['ahai_blog_ft_link_label' => 'Brand Story',      'ahai_blog_ft_link_url' => '#'],
        ['ahai_blog_ft_link_label' => 'Sustainability',    'ahai_blog_ft_link_url' => '#'],
        ['ahai_blog_ft_link_label' => 'Privacy',           'ahai_blog_ft_link_url' => '#'],
        ['ahai_blog_ft_link_label' => 'Terms of Service',  'ahai_blog_ft_link_url' => '#'],
        ['ahai_blog_ft_link_label' => 'Contact',           'ahai_blog_ft_link_url' => '#'],
    ];
}

/* ============================================================
   3. Shared Assets — Tailwind CDN + Fonts + Base CSS
   ============================================================ */
add_action('wp_footer', 'ahai_blog_shared_assets');
function ahai_blog_shared_assets() {
    static $done = false;
    if ($done) return;
    $done = true;
    ?>
<script>
(function(){
  if(!document.getElementById('ahai-tw')){
    var s=document.createElement('script');s.id='ahai-tw';
    s.src='https://cdn.tailwindcss.com?plugins=forms,container-queries';
    document.head.appendChild(s);
    s.onload=function(){
      tailwind.config={
        darkMode:"class",
        corePlugins:{preflight:false},
        theme:{extend:{
          colors:{
            "surface":"#faf9f9","on-tertiary":"#ffffff","secondary":"#775a19",
            "on-primary-fixed-variant":"#474746","tertiary-fixed":"#e3e3de",
            "on-tertiary-fixed-variant":"#464744","outline-variant":"#c4c7c7",
            "error":"#ba1a1a","primary-fixed":"#e5e2e1","on-primary-container":"#858383",
            "tertiary-fixed-dim":"#c7c7c2","surface-variant":"#e2e2e2",
            "on-primary-fixed":"#1c1b1b","secondary-fixed":"#ffdea5",
            "on-surface":"#1a1c1c","on-tertiary-fixed":"#1b1c19",
            "on-error":"#ffffff","background":"#faf9f9","on-surface-variant":"#444748",
            "on-secondary":"#ffffff","on-primary":"#ffffff","on-secondary-fixed":"#261900",
            "on-secondary-container":"#785a1a","on-error-container":"#93000a",
            "primary":"#000000","primary-fixed-dim":"#c8c6c5",
            "secondary-fixed-dim":"#e9c176","surface-container":"#eeeeee",
            "tertiary-container":"#1b1c19","inverse-surface":"#2f3131",
            "inverse-primary":"#c8c6c5","surface-tint":"#5f5e5e","outline":"#747878",
            "surface-container-lowest":"#ffffff","surface-container-low":"#f4f3f3",
            "surface-container-highest":"#e2e2e2","tertiary":"#000000",
            "on-tertiary-container":"#848480","surface-bright":"#faf9f9",
            "primary-container":"#1c1b1b","error-container":"#ffdad6",
            "surface-dim":"#dadada","surface-container-high":"#e8e8e8",
            "secondary-container":"#fed488","on-secondary-fixed-variant":"#5d4201",
            "inverse-on-surface":"#f1f1f0","on-background":"#1a1c1c"
          },
          fontFamily:{
            "display-lg":["Playfair Display","serif"],
            "headline-lg":["Playfair Display","serif"],
            "headline-lg-mobile":["Playfair Display","serif"],
            "headline-md":["Playfair Display","serif"],
            "body-lg":["Inter","sans-serif"],
            "body-md":["Inter","sans-serif"],
            "label-md":["Inter","sans-serif"],
            "label-sm":["Inter","sans-serif"]
          },
          fontSize:{
            "display-lg":["72px",{lineHeight:"1.1",letterSpacing:"-0.02em",fontWeight:"700"}],
            "headline-lg":["48px",{lineHeight:"1.2",fontWeight:"600"}],
            "headline-lg-mobile":["32px",{lineHeight:"1.2",fontWeight:"600"}],
            "headline-md":["32px",{lineHeight:"1.3",fontWeight:"500"}],
            "body-lg":["18px",{lineHeight:"1.6",fontWeight:"400"}],
            "body-md":["16px",{lineHeight:"1.6",fontWeight:"400"}],
            "label-md":["14px",{lineHeight:"1.2",letterSpacing:"0.1em",fontWeight:"600"}],
            "label-sm":["12px",{lineHeight:"1.2",letterSpacing:"0.05em",fontWeight:"500"}]
          },
          spacing:{
            "margin-mobile":"20px","margin-tablet":"40px","gutter":"24px",
            "container-max":"1440px","base":"8px","section-gap":"120px","margin-desktop":"80px"
          },
          borderRadius:{
            "DEFAULT":"0.25rem","lg":"0.5rem","xl":"0.75rem","full":"9999px"
          }
        }}
      };
    };
  }
  if(!document.getElementById('ahai-gf')){
    var l=document.createElement('link');l.id='ahai-gf';
    l.rel='stylesheet';
    l.href='https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@400;500;600;700&display=swap';
    document.head.appendChild(l);
  }
  if(!document.getElementById('ahai-ms')){
    var l2=document.createElement('link');l2.id='ahai-ms';
    l2.rel='stylesheet';
    l2.href='https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap';
    document.head.appendChild(l2);
  }
})();
</script>
<style id="ahai-blog-base-css">
/* ── Astra Theme Reset ── */
#aurelian-blog a,
#aurelian-blog a:hover,
#aurelian-blog a:focus,
#aurelian-blog a:active,
#aurelian-blog a:visited {
  color: inherit !important;
  text-decoration: none !important;
}
#aurelian-blog a.bg-primary { background-color: #000000 !important; color: #ffffff !important; }
#aurelian-blog a.bg-secondary { background-color: #775a19 !important; color: #ffffff !important; }
#aurelian-blog a.border-secondary { border-color: rgba(119,90,25,0.4) !important; }
#aurelian-blog a.border-secondary:hover { background-color: rgba(119,90,25,0.05) !important; }

/* ── Material Symbols Base ── */
#aurelian-blog .material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
  vertical-align: middle;
}

/* ── Glass Card (v2 DESIGN.md spec) ── */
#aurelian-blog .glass-card {
  background: rgba(249, 248, 243, 0.7);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(119, 90, 25, 0.1);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
#aurelian-blog .glass-card:hover {
  box-shadow: 0 0 40px rgba(119, 90, 25, 0.15);
  transform: translateY(-4px);
  border-color: #775a19;
}

/* ── Burnished Gold Gradient Text ── */
#aurelian-blog .burnished-gold-text {
  background: linear-gradient(to right, #775a19, #e9c176, #775a19);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* ── Editorial Line ── */
#aurelian-blog .editorial-line {
  height: 1px;
  background: #c4c7c7;
  transform-origin: left;
  transition: transform 0.6s ease, background 0.6s ease;
}
#aurelian-blog .ahai-case-card:hover .editorial-line {
  transform: scaleX(1.1);
  background: #775a19;
}

/* ── Pagination Dots ── */
#aurelian-blog .pagination-dot {
  width: 6px; height: 6px;
  background: #c4c7c7;
  border-radius: 50%;
  transition: all 0.3s ease;
  display: inline-block;
}
#aurelian-blog .pagination-dot.active {
  width: 24px;
  border-radius: 4px;
  background: #775a19;
}

/* ── Custom Cursor (v2 spec) ── */
#ahai-custom-cursor {
  width: 12px; height: 12px;
  background: #775a19;
  border-radius: 50%;
  position: fixed;
  pointer-events: none;
  z-index: 9999;
  mix-blend-mode: difference;
  transition: transform 0.15s ease, opacity 0.15s ease;
}

/* ── Case Study Card Image Hover ── */
#aurelian-blog .ahai-cs-img {
  transition: transform 1.5s cubic-bezier(0.4, 0, 0.2, 1);
}
#aurelian-blog .ahai-case-card:hover .ahai-cs-img {
  transform: scale(1.05);
}

/* ── Journal Article Image Hover ── */
#aurelian-blog .ahai-ja-img-wrap {
  transition: transform 0.7s ease;
}
#aurelian-blog .ahai-ja-group:hover .ahai-ja-img-wrap {
  transform: scale(1.1);
}
#aurelian-blog .ahai-ja-overlay {
  transition: opacity 0.3s ease;
}
#aurelian-blog .ahai-ja-group:hover .ahai-ja-overlay {
  opacity: 1;
}

/* ── Skip Link ── */
#aurelian-blog .skip-link {
  position: absolute;
  top: -100px; left: 0;
  background: #000; color: #fff;
  padding: 8px 16px; z-index: 9999;
  border-radius: 0 0 4px 0;
  font-size: 14px; font-weight: 600;
  transition: top 0.2s;
}
#aurelian-blog .skip-link:focus {
  top: 0;
}

/* ── Mobile Responsive ── */
@media (max-width: 768px) {
  #aurelian-blog .ahai-desktop-pad {
    padding-left: 20px !important;
    padding-right: 20px !important;
  }
  #aurelian-blog .ahai-cs-grid {
    grid-template-columns: 1fr !important;
  }
  #aurelian-blog .ahai-ja-grid {
    grid-template-columns: 1fr !important;
  }
  #aurelian-blog .ahai-hero-title {
    font-size: 42px !important;
  }
  #aurelian-blog .ahai-section-title {
    font-size: 32px !important;
  }
  #aurelian-blog .ahai-nl-form {
    flex-direction: column !important;
  }
  #aurelian-blog .ahai-footer-row {
    flex-direction: column !important;
    gap: 24px !important;
    text-align: center !important;
  }
}

/* ── Reduced Motion ── */
@media (prefers-reduced-motion: reduce) {
  #aurelian-blog .glass-card,
  #aurelian-blog .ahai-cs-img,
  #aurelian-blog .ahai-ja-img-wrap {
    transition: none !important;
  }
  #aurelian-blog .reveal {
    opacity: 1 !important;
    transform: none !important;
  }
}
</style>
    <?php
}

/* ============================================================
   4. Shortcode: [aurelian_blog]
   ============================================================ */
add_shortcode('aurelian_blog', 'ahai_blog_render');

function ahai_blog_render() {
    // ─── Fetch ACF fields with null coalescing fallbacks ───
    $hero_badge       = get_field('ahai_blog_hero_badge')        ?? 'The Atelier of Intelligence';
    $hero_title_line1 = get_field('ahai_blog_hero_title_line1')  ?? 'Crafting Digital';
    $hero_title_italic= get_field('ahai_blog_hero_title_italic') ?? 'Humanity';
    $hero_subtitle    = get_field('ahai_blog_hero_subtitle')     ?? 'Where technical precision meets heritage aesthetic. Discover our latest case studies and industry journals.';
$hero_bg            = get_field('ahai_blog_hero_bg')        ?? '';
$hero_title_size     = get_field('ahai_blog_hero_title_size')     ? (int) get_field('ahai_blog_hero_title_size') : 56;
$section_title_size  = get_field('ahai_blog_section_title_size')  ? (int) get_field('ahai_blog_section_title_size') : 40;



    $cs_section_title = get_field('ahai_blog_cases_section_title') ?? 'Collaborative Excellence';
    $cs_dir_label     = get_field('ahai_blog_cases_dir_label')     ?? 'View Directory';
    $cs_dir_url       = get_field('ahai_blog_cases_dir_url')       ?? '#';
    $case_studies     = get_field('ahai_blog_case_studies');
    if (!$case_studies || !is_array($case_studies) || count($case_studies) === 0) {
        $case_studies = ahai_blog_default_case_studies();
    }
    $case_studies = array_slice($case_studies, 0, 4);

    $ja_section_title    = get_field('ahai_blog_ja_section_title')    ?? 'The Intelligence Journal';
    $ja_section_subtitle = get_field('ahai_blog_ja_section_subtitle') ?? 'Industry Insights & Thought Leadership';
    $journal_articles    = get_field('ahai_blog_journal_articles');
    if (!$journal_articles || !is_array($journal_articles) || count($journal_articles) === 0) {
        $journal_articles = ahai_blog_default_journal_articles();
    }
    $journal_articles = array_slice($journal_articles, 0, 3);

    $nl_title       = get_field('ahai_blog_nl_title')       ?? 'Subscribe to the Atelier';
    $nl_desc        = get_field('ahai_blog_nl_desc')        ?? 'Receive exclusive insights on the intersection of heritage luxury and artificial intelligence. Monthly curated journals.';
    $nl_placeholder = get_field('ahai_blog_nl_placeholder') ?? 'EMAIL ADDRESS';
    $nl_btn         = get_field('ahai_blog_nl_btn')         ?? 'Join the Atelier';
    $nl_privacy     = get_field('ahai_blog_nl_privacy')     ?? 'RESPECTING YOUR PRIVACY SINCE 2024.';
    $nl_form_action = get_field('ahai_blog_nl_form_action') ?? '';

    $ft_brand_name = get_field('ahai_blog_ft_brand_name') ?? 'AURELIAN';
    $ft_logo       = get_field('ahai_blog_ft_logo')       ?? '';
    $ft_copyright  = get_field('ahai_blog_ft_copyright')  ?? '© 2024 HIREAIPEOPLE. THE FRONTIER OF DIGITAL HUMANITY.';
    $ft_links      = get_field('ahai_blog_ft_links');
    if (!$ft_links || !is_array($ft_links) || count($ft_links) === 0) {
        $ft_links = ahai_blog_default_footer_links();
    }

    ob_start();
    ?>
<div id="aurelian-blog" class="ahai-wrapper" style="font-family:Inter,system-ui,sans-serif;color:#1a1c1c;background:#faf9f9;overflow-x:hidden;max-width:100%;">

    <!-- Custom Cursor -->
    <div id="ahai-custom-cursor" aria-hidden="true"></div>

    <!-- Skip Link (Accessibility) -->
    <a class="skip-link" href="#ahai-main-content" aria-label="<?php echo esc_attr__('Skip to main content', 'aurelian-blog'); ?>">
        <?php esc_html_e('Skip to Content', 'aurelian-blog'); ?>
    </a>

    <!-- ====== HERO SECTION ====== -->
    <section
        class="ahai-desktop-pad ahai-reveal"
        style="padding:80px 80px 0;max-width:1440px;margin:0 auto 80px;text-align:center;position:relative;overflow:hidden;"
        aria-labelledby="ahai-hero-heading"
    >
        <?php if ($hero_bg): ?>
        <div style="position:absolute;inset:0;z-index:0;pointer-events:none;" aria-hidden="true">
            <img src="<?php echo esc_url($hero_bg); ?>" alt=""
                 style="width:100%;height:100%;object-fit:cover;object-position:center;opacity:0.15;">
        </div>
        <?php endif; ?>
        <div style="position:relative;z-index:1;">
        <span style="font-family:Inter;font-size:14px;font-weight:600;letter-spacing:0.3em;text-transform:uppercase;color:#775a19;display:block;margin-bottom:24px;">
            <?php echo esc_html($hero_badge); ?>
        </span>

        <h1 id="ahai-hero-heading" class="ahai-hero-title"
            style="font-family:'Playfair Display',serif;font-size:<?php echo esc_attr($hero_title_size); ?>px;font-weight:700;line-height:1.1;letter-spacing:-0.02em;margin:0 0 32px;color:#1a1c1c;">
            <?php echo esc_html($hero_title_line1); ?><br>
            <span style="font-style:italic;"><?php echo esc_html($hero_title_italic); ?></span>
        </h1>

        <p style="font-family:Inter;font-size:18px;font-weight:400;line-height:1.6;color:#444748;max-width:640px;margin:0 auto;">
            <?php echo esc_html($hero_subtitle); ?>
        </p>
        </div><!-- /z-index wrapper -->
    </section>

    <!-- ====== COLLABORATIVE EXCELLENCE (Case Studies 3×2) ====== -->
    <section
        class="ahai-desktop-pad ahai-reveal"
        style="padding:0 80px;max-width:1440px;margin:0 auto 120px;"
        aria-labelledby="ahai-cs-heading"
    >
        <!-- Header Row -->
        <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:64px;flex-wrap:wrap;gap:16px;">
            <div>
                <h2 id="ahai-cs-heading" class="ahai-section-title"
                    style="font-family:'Playfair Display',serif;font-size:<?php echo esc_attr($section_title_size); ?>px;font-weight:600;margin:0 0 16px;line-height:1.2;color:#1a1c1c;">
                    <?php echo esc_html($cs_section_title); ?>
                </h2>
                <div style="height:4px;width:96px;background:#775a19;" aria-hidden="true"></div>
            </div>
            <a href="<?php echo esc_url($cs_dir_url); ?>"
               style="font-family:Inter;font-size:14px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#444748;border-bottom:1px solid #c4c7c7;padding-bottom:8px;transition:color 0.3s ease;"
               onmouseover="this.style.color='#775a19';this.style.borderColor='#775a19';"
               onmouseout="this.style.color='#444748';this.style.borderColor='#c4c7c7';"
            ><?php echo esc_html($cs_dir_label); ?></a>
        </div>

        <!-- 3×2 Grid -->
        <div class="ahai-cs-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <?php foreach ($case_studies as $cs):
                $cs_image    = $cs['ahai_blog_cs_image']    ?? '';
                $cs_industry = $cs['ahai_blog_cs_industry'] ?? 'Luxury Retail';
                $cs_metric   = $cs['ahai_blog_cs_metric']   ?? '';
                $cs_title    = $cs['ahai_blog_cs_title']    ?? 'Untitled Case';
                $cs_desc     = $cs['ahai_blog_cs_desc']     ?? '';
                $cs_link     = $cs['ahai_blog_cs_link']     ?? '#';
            ?>
            <article class="ahai-case-card glass-card" style="border-radius:0.75rem;overflow:hidden;"
                     aria-labelledby="cs-title-<?php echo esc_attr(sanitize_title($cs_title)); ?>">
                <a href="<?php echo esc_url($cs_link); ?>" style="display:block;color:inherit;text-decoration:none;"
                   aria-label="<?php echo esc_attr(sprintf(__('Read case study: %s', 'aurelian-blog'), $cs_title)); ?>">
                    <!-- Image aspect-[1.5] -->
                    <div style="aspect-ratio:1.5;overflow:hidden;">
                        <?php if ($cs_image): ?>
                            <img class="ahai-cs-img" src="<?php echo esc_url($cs_image); ?>" alt="" aria-hidden="true"
                                 style="width:100%;height:100%;object-fit:cover;display:block;">
                        <?php else: ?>
                            <div style="width:100%;height:100%;background:#e2e2e2;display:flex;align-items:center;justify-content:center;color:#858383;font-family:Inter;font-size:12px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;"
                                 aria-hidden="true"><?php esc_html_e('Case Image', 'aurelian-blog'); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Card Content -->
                    <div style="padding:48px;">
                        <!-- Industry Tag + Metric -->
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
                            <span style="font-family:Inter;font-size:12px;font-weight:500;letter-spacing:0.05em;text-transform:uppercase;color:#775a19;border:1px solid #775a19;padding:4px 16px;border-radius:9999px;">
                                <?php echo esc_html($cs_industry); ?>
                            </span>
                            <?php if ($cs_metric): ?>
                            <span class="burnished-gold-text" style="font-family:'Playfair Display',serif;font-size:32px;font-weight:500;">
                                <?php echo esc_html($cs_metric); ?>
                            </span>
                            <?php endif; ?>
                        </div>

                        <!-- Title -->
                        <h3 id="cs-title-<?php echo esc_attr(sanitize_title($cs_title)); ?>"
                            style="font-family:'Playfair Display',serif;font-size:32px;font-weight:500;margin:0 0 16px;line-height:1.3;color:#1a1c1c;">
                            <?php echo esc_html($cs_title); ?>
                        </h3>

                        <!-- Description -->
                        <p style="font-family:Inter;font-size:16px;font-weight:400;line-height:1.6;color:#444748;margin:0 0 32px;">
                            <?php echo esc_html($cs_desc); ?>
                        </p>

                        <!-- Editorial Line + Read More -->
                        <div class="editorial-line" style="width:100%;" aria-hidden="true"></div>
                        <div style="margin-top:24px;display:flex;align-items:center;gap:8px;font-family:Inter;font-size:14px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#1a1c1c;">
                            <?php esc_html_e('Read Case Study', 'aurelian-blog'); ?>
                            <span class="material-symbols-outlined" style="font-size:16px;" aria-hidden="true">arrow_forward</span>
                        </div>
                    </div>
                </a>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination for Case Studies -->
        <div style="margin-top:80px;display:flex;justify-content:center;align-items:center;gap:32px;" aria-label="<?php echo esc_attr__('Case studies pagination', 'aurelian-blog'); ?>">
            <button style="width:48px;height:48px;border-radius:9999px;border:1px solid #c4c7c7;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s;color:#444748;"
                    onmouseover="this.style.backgroundColor='#000';this.style.color='#fff';this.style.borderColor='#000';"
                    onmouseout="this.style.backgroundColor='transparent';this.style.color='#444748';this.style.borderColor='#c4c7c7';"
                    aria-label="<?php echo esc_attr__('Previous case studies', 'aurelian-blog'); ?>">
                <span class="material-symbols-outlined" aria-hidden="true">chevron_left</span>
            </button>
            <div style="display:flex;align-items:center;gap:12px;" aria-hidden="true">
                <span class="pagination-dot active"></span>
                <span class="pagination-dot"></span>
                <span class="pagination-dot"></span>
            </div>
            <button style="width:48px;height:48px;border-radius:9999px;border:1px solid #c4c7c7;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s;color:#444748;"
                    onmouseover="this.style.backgroundColor='#000';this.style.color='#fff';this.style.borderColor='#000';"
                    onmouseout="this.style.backgroundColor='transparent';this.style.color='#444748';this.style.borderColor='#c4c7c7';"
                    aria-label="<?php echo esc_attr__('Next case studies', 'aurelian-blog'); ?>">
                <span class="material-symbols-outlined" aria-hidden="true">chevron_right</span>
            </button>
        </div>
    </section>

    <!-- ====== THE INTELLIGENCE JOURNAL ====== -->
    <section
        class="ahai-reveal"
        style="background:#f4f3f3;padding:120px 0;"
        aria-labelledby="ahai-ja-heading"
    >
        <div class="ahai-desktop-pad" style="padding:0 80px;max-width:1440px;margin:0 auto;">
            <!-- Section Header -->
            <div style="text-align:center;margin-bottom:80px;">
                <h2 id="ahai-ja-heading" class="ahai-section-title"
                    style="font-family:'Playfair Display',serif;font-size:<?php echo esc_attr($section_title_size); ?>px;font-weight:600;margin:0 0 16px;line-height:1.2;color:#1a1c1c;">
                    <?php echo esc_html($ja_section_title); ?>
                </h2>
                <p style="font-family:Inter;font-size:14px;font-weight:600;letter-spacing:0.4em;text-transform:uppercase;color:#444748;margin:0;">
                    <?php echo esc_html($ja_section_subtitle); ?>
                </p>
            </div>

            <!-- 3-Column Journal Grid: gap-x-12 (48px) gap-y-20 (80px) -->
            <div class="ahai-ja-grid" style="display:grid;grid-template-columns:repeat(3,1fr);column-gap:48px;row-gap:80px;">
                <?php foreach ($journal_articles as $ja):
                    $ja_image     = $ja['ahai_blog_ja_image']     ?? '';
                    $ja_category  = $ja['ahai_blog_ja_category']  ?? 'Aesthetics';
                    $ja_title     = $ja['ahai_blog_ja_title']     ?? 'Untitled Article';
                    $ja_is_italic = $ja['ahai_blog_ja_is_italic'] ?? false;
                    $ja_desc      = $ja['ahai_blog_ja_desc']      ?? '';
                    $ja_readtime  = $ja['ahai_blog_ja_readtime']  ?? '08 MIN READ';
                    $ja_link      = $ja['ahai_blog_ja_link']      ?? '#';
                ?>
                <article class="ahai-ja-group"
                         aria-labelledby="ja-title-<?php echo esc_attr(sanitize_title($ja_title)); ?>">
                    <a href="<?php echo esc_url($ja_link); ?>" style="display:block;color:inherit;text-decoration:none;"
                       aria-label="<?php echo esc_attr(sprintf(__('Read article: %s', 'aurelian-blog'), $ja_title)); ?>">
                        <!-- Image aspect-[4/5] with hover overlay -->
                        <div style="position:relative;margin-bottom:32px;overflow:hidden;">
                            <div style="aspect-ratio:0.8;overflow:hidden;">
                                <?php if ($ja_image): ?>
                                    <img class="ahai-ja-img-wrap" src="<?php echo esc_url($ja_image); ?>" alt="" aria-hidden="true"
                                         style="width:100%;height:100%;object-fit:cover;display:block;">
                                <?php else: ?>
                                    <div class="ahai-ja-img-wrap" style="width:100%;height:100%;background:#e2e2e2;display:flex;align-items:center;justify-content:center;color:#858383;font-family:Inter;font-size:12px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;"
                                         aria-hidden="true"><?php esc_html_e('Journal Image', 'aurelian-blog'); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="ahai-ja-overlay" style="position:absolute;inset:0;background:rgba(26,28,28,0.05);opacity:0;pointer-events:none;" aria-hidden="true"></div>
                        </div>

                        <!-- Category Tag -->
                        <span style="font-family:Inter;font-size:12px;font-weight:500;letter-spacing:0.05em;text-transform:uppercase;color:#775a19;display:block;margin-bottom:16px;">
                            <?php echo esc_html($ja_category); ?>
                        </span>

                        <!-- Title (with optional italic) -->
                        <h4 id="ja-title-<?php echo esc_attr(sanitize_title($ja_title)); ?>"
                            style="font-family:'Playfair Display',serif;font-size:32px;font-weight:500;margin:0 0 16px;line-height:1.3;color:#1a1c1c;<?php echo $ja_is_italic ? 'font-style:italic;' : ''; ?>">
                            <?php echo esc_html($ja_title); ?>
                        </h4>

                        <!-- Description -->
                        <p style="font-family:Inter;font-size:16px;font-weight:400;line-height:1.6;color:#444748;margin:0 0 24px;">
                            <?php echo esc_html($ja_desc); ?>
                        </p>

                        <!-- Read Time Line -->
                        <div style="display:flex;align-items:center;gap:16px;">
                            <div style="width:32px;height:1px;background:#747878;" aria-hidden="true"></div>
                            <span style="font-family:Inter;font-size:12px;font-weight:500;letter-spacing:0.05em;text-transform:uppercase;color:#444748;">
                                <?php echo esc_html($ja_readtime); ?>
                            </span>
                        </div>
                    </a>
                </article>
                <?php endforeach; ?>
            </div>

            <!-- Journal Pagination: 01 / 02 / 03 -->
            <div style="margin-top:80px;display:flex;justify-content:center;align-items:center;gap:32px;" aria-label="<?php echo esc_attr__('Journal pagination', 'aurelian-blog'); ?>">
                <button style="width:48px;height:48px;border-radius:9999px;border:1px solid #c4c7c7;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s;color:#444748;"
                        onmouseover="this.style.backgroundColor='#000';this.style.color='#fff';this.style.borderColor='#000';"
                        onmouseout="this.style.backgroundColor='transparent';this.style.color='#444748';this.style.borderColor='#c4c7c7';"
                        aria-label="<?php echo esc_attr__('Previous articles', 'aurelian-blog'); ?>">
                    <span class="material-symbols-outlined" aria-hidden="true">west</span>
                </button>
                <div style="display:flex;gap:24px;font-family:Inter;font-size:14px;font-weight:600;letter-spacing:0.1em;">
                    <span style="color:#775a19;border-bottom:2px solid #775a19;padding-bottom:4px;">01</span>
                    <span style="color:rgba(68,71,72,0.4);cursor:pointer;" onmouseover="this.style.color='#775a19';" onmouseout="this.style.color='rgba(68,71,72,0.4)';">02</span>
                    <span style="color:rgba(68,71,72,0.4);cursor:pointer;" onmouseover="this.style.color='#775a19';" onmouseout="this.style.color='rgba(68,71,72,0.4)';">03</span>
                </div>
                <button style="width:48px;height:48px;border-radius:9999px;border:1px solid #c4c7c7;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s;color:#444748;"
                        onmouseover="this.style.backgroundColor='#000';this.style.color='#fff';this.style.borderColor='#000';"
                        onmouseout="this.style.backgroundColor='transparent';this.style.color='#444748';this.style.borderColor='#c4c7c7';"
                        aria-label="<?php echo esc_attr__('Next articles', 'aurelian-blog'); ?>">
                    <span class="material-symbols-outlined" aria-hidden="true">east</span>
                </button>
            </div>
        </div>
    </section>

    <!-- ====== NEWSLETTER SECTION ====== -->
    <section
        class="ahai-desktop-pad ahai-reveal"
        style="padding:120px 80px;"
        aria-labelledby="ahai-nl-heading"
    >
        <div class="glass-card" style="max-width:896px;margin:0 auto;padding:64px;border-radius:1rem;text-align:center;position:relative;overflow:hidden;">
            <!-- Ambient Gold Glow blob (top-right) -->
            <div style="position:absolute;top:0;right:0;width:128px;height:128px;background:rgba(119,90,25,0.1);border-radius:9999px;filter:blur(48px);transform:translate(32px,-32px);" aria-hidden="true"></div>

            <h3 id="ahai-nl-heading"
                style="font-family:'Playfair Display',serif;font-size:48px;font-weight:600;margin:0 0 16px;color:#1a1c1c;position:relative;z-index:1;">
                <?php echo esc_html($nl_title); ?>
            </h3>
            <p style="font-family:Inter;font-size:18px;font-weight:400;line-height:1.6;color:#444748;margin:0 0 48px;position:relative;z-index:1;">
                <?php echo esc_html($nl_desc); ?>
            </p>

            <form class="ahai-nl-form"
                  action="<?php echo $nl_form_action ? esc_url($nl_form_action) : ''; ?>"
                  method="post"
                  style="display:flex;flex-direction:row;gap:16px;max-width:560px;margin:0 auto;position:relative;z-index:1;"
                  aria-label="<?php echo esc_attr__('Newsletter subscription form', 'aurelian-blog'); ?>"
            >
                <div style="flex:1;">
                    <label for="ahai-nl-email" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">
                        <?php esc_html_e('Email Address', 'aurelian-blog'); ?>
                    </label>
                    <input
                        id="ahai-nl-email"
                        type="email"
                        placeholder="<?php echo esc_attr($nl_placeholder); ?>"
                        required
                        style="width:100%;background:transparent;border:none;border-bottom:1px solid #c4c7c7;padding:16px 0;font-family:Inter;font-size:14px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;outline:none;color:#1a1c1c;transition:border-color 0.3s;"
                    >
                </div>
                <button
                    type="submit"
                    style="background:#1a1c1c;color:#fff;padding:16px 48px;border-radius:9999px;border:none;font-family:Inter;font-size:14px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;cursor:pointer;box-shadow:0 4px 24px rgba(0,0,0,0.15);transition:all 0.3s;white-space:nowrap;"
                    onmouseover="this.style.backgroundColor='#775a19';this.style.boxShadow='0 0 25px rgba(119,90,25,0.3)';"
                    onmouseout="this.style.backgroundColor='#1a1c1c';this.style.boxShadow='0 4px 24px rgba(0,0,0,0.15)';"
                ><?php echo esc_html($nl_btn); ?></button>
            </form>

            <p style="margin-top:32px;font-family:Inter;font-size:12px;font-weight:500;letter-spacing:0.05em;color:#747878;position:relative;z-index:1;">
                <?php echo esc_html($nl_privacy); ?>
            </p>
        </div>
    </section>

    <!-- ====== FOOTER ====== -->
    <footer
        id="ahai-main-content"
        class="ahai-reveal"
        style="background:#ffffff;border-top:1px solid rgba(196,199,199,0.3);padding:120px 80px;"
        role="contentinfo"
    >
        <div style="max-width:1440px;margin:0 auto;display:flex;flex-direction:column;align-items:center;gap:48px;">
            <!-- Logo + Nav Links -->
            <div style="display:flex;flex-direction:column;align-items:center;">
                <?php if ($ft_logo): ?>
                    <img src="<?php echo esc_url($ft_logo); ?>"
                         alt="<?php echo esc_attr($ft_brand_name); ?>"
                         style="height:48px;margin-bottom:32px;">
                <?php else: ?>
                    <div style="margin-bottom:32px;font-family:'Playfair Display',serif;font-size:24px;font-weight:700;color:#1a1c1c;">
                        <?php echo esc_html($ft_brand_name); ?>
                    </div>
                <?php endif; ?>

                <nav style="display:flex;flex-wrap:wrap;justify-content:center;gap:32px 64px;"
                     aria-label="<?php echo esc_attr__('Footer navigation', 'aurelian-blog'); ?>">
                    <?php foreach ($ft_links as $fl):
                        $fl_label = $fl['ahai_blog_ft_link_label'] ?? __('Link', 'aurelian-blog');
                        $fl_url   = $fl['ahai_blog_ft_link_url']   ?? '#';
                    ?>
                    <a href="<?php echo esc_url($fl_url); ?>"
                       style="font-family:Inter;font-size:14px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#444748;transition:color 0.3s ease;"
                       onmouseover="this.style.color='#775a19';"
                       onmouseout="this.style.color='#444748';"
                    ><?php echo esc_html($fl_label); ?></a>
                    <?php endforeach; ?>
                </nav>
            </div>

            <!-- Divider -->
            <div style="width:100%;height:1px;background:rgba(196,199,199,0.3);" aria-hidden="true"></div>

            <!-- Bottom Row: Copyright + Social Icons -->
            <div class="ahai-footer-row" style="display:flex;flex-direction:row;justify-content:space-between;align-items:center;width:100%;">
                <p style="margin:0;font-family:Inter;font-size:16px;font-weight:400;line-height:1.6;color:#858383;text-transform:uppercase;letter-spacing:0.05em;">
                    <?php echo esc_html($ft_copyright); ?>
                </p>

                <!-- Social Icons: public / chat / mail -->
                <div style="display:flex;gap:24px;"
                     aria-label="<?php echo esc_attr__('Social media links', 'aurelian-blog'); ?>">
                    <a href="#" aria-label="<?php echo esc_attr__('Public page', 'aurelian-blog'); ?>"
                       style="color:#444748;font-size:20px;transition:color 0.3s;"
                       onmouseover="this.style.color='#775a19';" onmouseout="this.style.color='#444748';">
                        <span class="material-symbols-outlined" aria-hidden="true">public</span>
                    </a>
                    <a href="#" aria-label="<?php echo esc_attr__('Chat with us', 'aurelian-blog'); ?>"
                       style="color:#444748;font-size:20px;transition:color 0.3s;"
                       onmouseover="this.style.color='#775a19';" onmouseout="this.style.color='#444748';">
                        <span class="material-symbols-outlined" aria-hidden="true">chat</span>
                    </a>
                    <a href="#" aria-label="<?php echo esc_attr__('Email us', 'aurelian-blog'); ?>"
                       style="color:#444748;font-size:20px;transition:color 0.3s;"
                       onmouseover="this.style.color='#775a19';" onmouseout="this.style.color='#444748';">
                        <span class="material-symbols-outlined" aria-hidden="true">mail</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</div><!-- /#aurelian-blog -->

<!-- ====== JavaScript: Custom Cursor + Intersection Observer + Interactions ====== -->
<script>
(function() {
    var wrapper = document.getElementById('aurelian-blog');
    if (!wrapper) return;

    // ── Custom Cursor (v2 spec: 12px gold circle, mix-blend-mode: difference) ──
    var cursor = document.getElementById('ahai-custom-cursor');
    if (cursor && window.matchMedia('(pointer: fine)').matches) {
        document.addEventListener('mousemove', function(e) {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        });

        // Scale up on interactive elements
        wrapper.querySelectorAll('a, button, input[type="email"]').forEach(function(el) {
            el.addEventListener('mouseenter', function() {
                cursor.style.transform = 'scale(4)';
                cursor.style.opacity = '0.3';
            });
            el.addEventListener('mouseleave', function() {
                cursor.style.transform = 'scale(1)';
                cursor.style.opacity = '1';
            });
        });

        // Hide custom cursor when mouse leaves window
        document.addEventListener('mouseleave', function() {
            cursor.style.opacity = '0';
        });
        document.addEventListener('mouseenter', function() {
            cursor.style.opacity = '1';
        });
    } else if (cursor) {
        // Hide cursor on touch devices
        cursor.style.display = 'none';
    }

    // ── Intersection Observer for scroll-based fade-in (v2 spec) ──
    var reveals = wrapper.querySelectorAll('.ahai-reveal');
    if (reveals.length && 'IntersectionObserver' in window) {
        // Initialize: all sections start invisible
        reveals.forEach(function(el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(10px)';
            el.style.transition = 'all 1s cubic-bezier(0.4, 0, 0.2, 1)';
        });

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        reveals.forEach(function(el) {
            observer.observe(el);
        });

        // Make hero visible immediately
        var hero = wrapper.querySelector('section.ahai-reveal');
        if (hero) {
            hero.style.opacity = '1';
            hero.style.transform = 'translateY(0)';
        }
    } else {
        // Fallback: all visible
        reveals.forEach(function(el) {
            el.style.opacity = '1';
            el.style.transform = 'none';
        });
    }

    // ── Newsletter input focus/blur ──
    var nlInput = wrapper.querySelector('#ahai-nl-email');
    if (nlInput) {
        nlInput.addEventListener('focus', function() {
            this.style.borderColor = '#775a19';
        });
        nlInput.addEventListener('blur', function() {
            this.style.borderColor = '#c4c7c7';
        });
    }

    // ── Smooth scroll for anchor links ──
    wrapper.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var href = this.getAttribute('href');
            if (href === '#' || href === '#ahai-main-content') return;
            e.preventDefault();
            var target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
})();
</script>
    <?php
    return ob_get_clean();
}