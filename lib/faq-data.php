<?php
/**
 * FAQ page data layer.
 *
 * Categories and questions are stored as a nested repeater so both levels can
 * be edited freely in WordPress. The bundled zh/en sources remain only as
 * fallback/default sources and never override content saved in the database.
 *
 * @version 3.8.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('hireai_faq_default_categories')) {
    /**
     * Parse the bundled FAQ source into category/question arrays.
     *
     * @param string $lang Language code.
     * @return array[]
     */
    function hireai_faq_default_categories($lang = 'zh') {
        static $defaults = [];
        $lang = 'en' === $lang ? 'en' : 'zh';

        if (isset($defaults[$lang])) {
            return $defaults[$lang];
        }

        $file = get_theme_file_path("assets/content/faq-content-{$lang}.txt");
        $raw  = file_exists($file) ? (string) file_get_contents($file) : '';
        $parsed = [];

        if ('' === trim($raw)) {
            return $defaults[$lang] = [];
        }

        $raw = str_replace(["\r\n", "\r"], "\n", $raw);

        foreach (explode("\n", $raw) as $line) {
            $line = trim($line);

            if ('' === $line) {
                continue;
            }

            if (preg_match('/^([0-9]+)\.\s*(.*)$/', $line, $matches)) {
                $parsed[] = [
                    'category_label' => $matches[2],
                    'faq_entries'    => [],
                ];
                continue;
            }

            if (empty($parsed)) {
                continue;
            }

            if (1 === preg_match('/^Q[0-9]+\./', $line)) {
                $question_start = (int) strpos($line, '.') + 1;
                $parsed[count($parsed) - 1]['faq_entries'][] = [
                    'question' => ltrim(substr($line, $question_start)),
                    'answer'   => '',
                ];
                continue;
            }

            $last_index = count($parsed) - 1;
            $entries    = &$parsed[$last_index]['faq_entries'];

            if (!empty($entries)) {
                $entries[count($entries) - 1]['answer'] = trim($entries[count($entries) - 1]['answer'] . ' ' . $line);
            }

            unset($entries);
        }

        $defaults[$lang] = array_values(array_filter($parsed, static function ($category) {
            return '' !== $category['category_label'] && !empty($category['faq_entries']);
        }));

        return $defaults[$lang];
    }
}

add_action('acf/init', 'hireai_register_faq_data_fields', 20);

if (!function_exists('hireai_register_faq_data_fields')) {
    /**
     * Register the free-form FAQ category/question field group.
     */
    function hireai_register_faq_data_fields() {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        $zh_defaults = hireai_faq_default_categories('zh');
        $en_defaults = hireai_faq_default_categories('en');

        acf_add_local_field_group([
            'key'    => 'group_page_faq_data_v2',
            'title'  => '常见问题 · 自由分类与问答（v3.8）',
            'fields' => [
                [
                    'key'          => 'field_faq_data_categories_zh',
                    'label'        => '中文 FAQ 分类',
                    'name'         => 'faq_data_categories_zh',
                    'type'         => 'repeater',
                    'instructions' => '每行一个左侧分类；在分类内自由增删右侧问答。前台按此顺序渲染。',
                    'layout'       => 'block',
                    'button_label' => '➕ 添加分类',
                    'default_value'=> $zh_defaults,
                    'sub_fields'   => [
                        [
                            'key'   => 'field_faq_category_label_zh',
                            'label' => '分类名称',
                            'name'  => 'category_label',
                            'type'  => 'text',
                        ],
                        [
                            'key'           => 'field_faq_data_entries_zh',
                            'label'         => '问答列表',
                            'name'          => 'faq_entries',
                            'type'          => 'repeater',
                            'layout'        => 'block',
                            'button_label'  => '➕ 添加问答',
                            'sub_fields'    => [
                                [
                                    'key'   => 'field_faq_data_question_zh',
                                    'label' => '问题',
                                    'name'  => 'question',
                                    'type'  => 'text',
                                ],
                                [
                                    'key'       => 'field_faq_data_answer_zh',
                                    'label'     => '答案',
                                    'name'      => 'answer',
                                    'type'      => 'textarea',
                                    'rows'      => 4,
                                    'new_lines' => 'br',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'key'          => 'field_faq_data_categories_en',
                    'label'        => 'English FAQ categories',
                    'name'         => 'faq_data_categories_en',
                    'type'         => 'repeater',
                    'instructions' => 'Add any number of categories; add any number of Q&A rows inside each category. Leave empty to use the legacy FAQ fields.',
                    'layout'       => 'block',
                    'button_label' => '➕ Add category',
                    'default_value'=> $en_defaults,
                    'sub_fields'   => [
                        [
                            'key'   => 'field_faq_category_label_en',
                            'label' => 'Category label',
                            'name'  => 'category_label',
                            'type'  => 'text',
                        ],
                        [
                            'key'          => 'field_faq_data_entries_en',
                            'label'        => 'Questions & answers',
                            'name'         => 'faq_entries',
                            'type'         => 'repeater',
                            'layout'       => 'block',
                            'button_label' => '➕ Add Q&A',
                            'sub_fields'   => [
                                [
                                    'key'   => 'field_faq_data_question_en',
                                    'label' => 'Question',
                                    'name'  => 'question',
                                    'type'  => 'text',
                                ],
                                [
                                    'key'       => 'field_faq_data_answer_en',
                                    'label'     => 'Answer',
                                    'name'      => 'answer',
                                    'type'      => 'textarea',
                                    'rows'      => 4,
                                    'new_lines' => 'br',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'location' => [
                [['param' => 'page_template', 'operator' => '==', 'value' => 'page-faq.php']],
            ],
            'menu_order' => 6,
            'active'     => true,
        ]);
    }
}
