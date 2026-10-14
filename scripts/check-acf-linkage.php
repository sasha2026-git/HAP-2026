<?php
if (PHP_SAPI !== 'cli') exit("CLI only\n");
if (!defined('ABSPATH')) define('ABSPATH', __DIR__ . '/');

$theme_root = dirname(__DIR__);
require $theme_root . '/inc/acf-schema.php';

if (!function_exists('hireai_make_bilingual_group')) {
    function hireai_make_bilingual_group($key, $title, $fields, $location) {
        $shared = ['image', 'url', 'true_false', 'select'];
        $out = [['key' => $key . '_tab_zh', 'label' => '中文内容', 'type' => 'tab']];
        foreach ($fields as $field) {
            $is_shared = in_array($field['type'], $shared, true);
            $out[] = [
                'key' => $key . '_' . $field['name'] . ($is_shared ? '' : '_zh'),
                'name' => $field['name'] . ($is_shared ? '' : '_zh'),
                'type' => $field['type'],
            ];
            foreach ($field as $field_key => $field_value) {
                if (!in_array($field_key, ['name', 'type'], true)) $out[array_key_last($out)][$field_key] = $field_value;
            }
        }
        $out[] = ['key' => $key . '_tab_en', 'label' => 'English Content', 'type' => 'tab'];
        foreach ($fields as $field) {
            if ($is_shared) continue;
            $out[] = [
                'key' => $key . '_' . $field['name'] . '_en',
                'name' => $field['name'] . '_en',
                'type' => $field['type'],
            ];
            foreach ($field as $field_key => $field_value) {
                if (!in_array($field_key, ['name', 'type'], true)) $out[array_key_last($out)][$field_key] = $field_value;
            }
        }
        return ['key' => $key, 'title' => $title, 'fields' => $out, 'location' => $location];
    }
}

$errors = [];
$group_keys = [];
$field_keys = [];
$field_names = [];
$registered = [];
$allowed = ['text', 'textarea', 'image', 'url', 'true_false', 'select', 'tab'];

foreach (hireai_acf_schema() as $group) {
    if (empty($group['key']) || empty($group['fields']) || empty($group['location'])) {
        $errors[] = 'invalid group shape';
        continue;
    }
    if (isset($group_keys[$group['key']])) $errors[] = 'duplicate group key ' . $group['key'];
    $group_keys[$group['key']] = true;
    foreach ($group['fields'] as $field) {
        if (empty($field['key']) || empty($field['type']) || !in_array($field['type'], $allowed, true) || (empty($field['name']) && $field['type'] !== 'tab')) {
            $errors[] = 'invalid field ' . ($field['key'] ?? 'unknown');
            continue;
        }
        if ($field['type'] === 'tab') continue;
        if (isset($field_keys[$field['key']])) $errors[] = 'duplicate field key ' . $field['key'];
        if (isset($field_names[$field['name']])) $errors[] = 'duplicate field name ' . $field['name'];
        $field_keys[$field['key']] = true;
        $field_names[$field['name']] = true;
        if ($field['type'] !== 'tab') $registered[] = $field['name'];
    }
}

$templates = ['front-page.php', 'page-ai-employees.php', 'page-ai-solutions.php', 'page-cases-insights.php', 'page-faq.php', 'page-contact.php', 'page-employee-detail.php', 'single.php'];
$read_names = [];
foreach ($templates as $template) {
    $source = file_get_contents($theme_root . '/' . $template);
    if (preg_match_all('/(?:hireai_field|hireai_field_lang|hireai_image|hireai_link|site_field|site_image_url|ci_bi|ci_field|ci_field_lang_force|get_field)\(\s*[\x27"]([^\x27"}\s]+)[\x27"]/', $source, $matches)) {
        foreach ($matches[1] as $name) $read_names[] = $name;
    }
    if (preg_match_all('/(?:hireai_field|hireai_field_lang|hireai_image)\(\s*[\\x27"]([^\\x27"]+)\s*\.\s*[\\x27"]/', $source, $dynamic)) {
        foreach ($dynamic[1] as $prefix) {
            if (preg_match('/_(\d{2})$/', $prefix, $number)) {
                $max = (int) $number[1];
                for ($index = 1; $index <= $max; $index++) $read_names[] = $prefix . sprintf('%02d', $index);
            }
        }
    }
}

$functions_source = file_get_contents($theme_root . '/functions.php');
preg_match('/function hireai_legacy_field_aliases\(.*?return \$aliases;\s*\}/s', $functions_source, $alias_function);
$alias_map = [];
if (!empty($alias_function[0]) && preg_match_all('/[\x27"]([^\x27"]+)[\x27"]\s*=>\s*[\x27"]([^\x27"]+)[\x27"]/', $alias_function[0], $alias_matches)) {
    foreach ($alias_matches[1] as $index => $old) $alias_map[$old] = $alias_matches[2][$index];
}

$expand_alias = function ($name) use ($alias_map) {
    $name = preg_replace('/_(zh|en)$/i', '', $name);
    if (isset($alias_map[$name])) return $alias_map[$name];
    if (preg_match('/^lookbook_employee_([1-9]|1[0-2])_(kicker|title|desc|button|image|url)$/', $name, $m)) {
        return 'employees_row_' . sprintf('%02d', $m[1]) . '_' . ['kicker' => 'kicker', 'title' => 'title', 'desc' => 'description', 'button' => 'cta_label', 'image' => 'image', 'url' => 'url'][$m[2]];
    }
    if (preg_match('/^fp_prod([1-3])_(.+)$/', $name, $m)) return 'home_product_' . sprintf('%02d', $m[1]) . '_' . $m[2];
    if (preg_match('/^fp_sol([1-4])_(.+)$/', $name, $m)) return 'home_solution_' . sprintf('%02d', $m[1]) . '_' . $m[2];
    if (preg_match('/^fp_case([1-2])_(.+)$/', $name, $m)) return 'home_case_minor_' . sprintf('%02d', $m[1]) . '_' . $m[2];
    if (preg_match('/^fp_faq([1-9]|10)_(q|a)$/', $name, $m)) return 'home_faq_' . sprintf('%02d', $m[1]) . '_' . ($m[2] === 'q' ? 'question' : 'answer');
    if (preg_match('/^ci_case([1-4])_(badge|title|desc|image)$/', $name, $m)) return 'cases_card_' . sprintf('%02d', $m[1]) . '_' . ['badge' => 'badge', 'title' => 'title', 'desc' => 'description', 'image' => 'image'][$m[2]];
    if (preg_match('/^ci_art([1-3])_(cat|title_pre|title_em|title_post|desc|rt|image)$/', $name, $m)) return 'cases_insight_' . sprintf('%02d', $m[1]) . '_' . ['cat' => 'category', 'title_pre' => 'title_prefix', 'title_em' => 'title_emphasis', 'title_post' => 'title_suffix', 'desc' => 'description', 'rt' => 'read_time', 'image' => 'image'][$m[2]];
    $extras = [
        'ci_hero_kicker' => 'cases_hero_kicker', 'ci_hero_title' => 'cases_hero_title', 'ci_hero_subtitle' => 'cases_hero_subtitle', 'ci_hero_image' => 'cases_hero_image', 'ci_hero_caption' => 'cases_hero_caption',
        'ci_hero_h1_pre' => 'cases_hero_title_prefix', 'ci_hero_h1_em' => 'cases_hero_title_emphasis', 'ci_hero_p' => 'cases_hero_description', 'ci_sec_h2' => 'cases_section_heading',
        'ci_tab_cases' => 'cases_tab_cases_label', 'ci_tab_insights' => 'cases_tab_insights_label', 'ci_cases_empty' => 'cases_empty_message', 'ci_cases_more' => 'cases_more_label', 'ci_cases_more_url' => 'cases_more_url',
        'ci_insights_kicker' => 'cases_insights_kicker', 'ci_insights_h2' => 'cases_insights_title', 'ci_insights_subtitle' => 'cases_insights_description', 'ci_insights_more' => 'cases_insights_more_label', 'ci_insights_more_url' => 'cases_insights_more_url', 'ci_insights_empty' => 'cases_insights_empty_message',
        'case_kicker' => 'case_card_kicker', 'case_badge' => 'case_card_badge', 'case_subtitle' => 'case_card_subtitle', 'case_cover_image' => 'case_card_cover_image',
        'insight_cat' => 'insight_card_category', 'insight_read_time' => 'insight_card_read_time', 'insight_cover_image' => 'insight_card_cover_image',
        'ci_consult_kicker' => 'cases_cta_kicker', 'ci_consult_h2' => 'cases_cta_title', 'ci_consult_p' => 'cases_cta_description', 'ci_consult_btn' => 'cases_cta_primary_label', 'ci_consult_url' => 'cases_cta_primary_url',
    ];
    if (isset($extras[$name])) return $extras[$name];
    return $name;
};

$sources = [];
foreach ($templates as $template) $sources[$template] = file_get_contents($theme_root . '/' . $template);
$sources['functions.php'] = $functions_source;
$registered_base = array_values(array_unique(array_map(function ($name) {
    return preg_replace('/_(zh|en)$/i', '', $name);
}, $registered)));
$expanded_reads = [];
foreach ($read_names as $raw) {
    if (strpos($raw, '$') !== false || strpos($raw, '/') !== false || preg_match('/_$/', $raw)) continue;
    $expanded_reads[] = $expand_alias($raw);
}
foreach ($sources as $source) {
foreach ($registered_base as $logical_name) {
        if (preg_match('/^(.+?)(\d{2})$/', $logical_name, $m)) {
            foreach (['number', 'index', 'ci_number', 'ci_idx', 'process_number', 'term_number'] as $variable) {
                if (strpos($source, $m[1] . '{$' . $variable . '}') !== false) {
                    for ($index = 1; $index <= (int) $m[2]; $index++) $expanded_reads[] = $m[1] . sprintf('%02d', $index);
                }
            }
        }
        if (!preg_match('/^(.+?)(\d{2})_(.+)$/', $logical_name, $m)) continue;
        $prefix = $m[1];
        $suffix = preg_replace('/_(zh|en)$/i', '', $m[3]);
        $variable_found = false;
        foreach (['number', 'index', 'ci_number', 'ci_idx', 'process_number', 'term_number'] as $variable) {
            if (strpos($source, $prefix . '{$' . $variable . '}_' . $suffix) !== false) $variable_found = true;
            if (strpos($source, $prefix . "' . $" . $variable) !== false) $variable_found = true;
            if (strpos($source, $prefix . "' . $" . $variable . " . '_" . $suffix) !== false) $variable_found = true;
        }
        if (!$variable_found) continue;
        for ($index = 1; $index <= (int) $m[2]; $index++) {
            $expanded_reads[] = $prefix . sprintf('%02d', $index) . '_' . $suffix;
        }
    }
}

$read_base = array_values(array_unique($expanded_reads));
$missing = array_fill_keys(array_diff($read_base, $registered_base), true);
$unused = array_diff($registered_base, $read_base);

if ($errors) {
    fwrite(STDERR, "Schema errors:\n- " . implode("\n- ", array_unique($errors)) . "\n");
    exit(1);
}
if ($missing || $unused) {
    fwrite(STDERR, "ACF linkage mismatch\n");
    if ($missing) fwrite(STDERR, 'Read but unregistered: ' . implode(', ', array_keys($missing)) . "\n");
    if ($unused) fwrite(STDERR, 'Registered but unread: ' . implode(', ', $unused) . "\n");
    exit(1);
}
echo "ACF linkage OK: " . count($registered) . " fields across " . count($group_keys) . " groups\n";
