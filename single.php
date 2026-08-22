<?php
/**
 * Single post template — 聘AI v3.0.0
 *
 * - 文章分类为 `ai-employee` 时：渲染数字员工档案页（含 ACF 字段 + CTA）
 * - 文章分类为 `faq` 时：渲染 FAQ 文章页（带 kicker + 内容）
 * - 其他文章：渲染通用博客文章页
 *
 * 所有可见文本/链接均通过 hireai_field() / hireai_image() / hireai_link() 读取，
 * ACF 未配置时回退到默认值，保证站点安装即用。
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$suffix = hireai_lang_suffix();
$is_en  = ($suffix === '_en');

while (have_posts()) :
    the_post();
    $post_id    = get_the_ID();
    $categories = wp_get_post_terms($post_id, 'category', ['fields' => 'slugs']);
    $cats       = is_wp_error($categories) ? [] : (array) $categories;
    $is_employee = in_array('ai-employee', $cats, true);
    $is_faq      = in_array('faq', $cats, true);

    if ($is_employee) :
        $role      = hireai_field('employee_role', $is_en ? 'Digital Employee' : '数字员工', $post_id);
        $kicker    = hireai_field('employee_kicker', $is_en ? 'Strategic Elite' : '战略精英', $post_id);
        $soul      = hireai_field('employee_soul', '', $post_id);
        $skill     = hireai_field('employee_skill', '', $post_id);
        $caps_raw  = hireai_field('employee_capabilities', '', $post_id);
        $btn_text  = hireai_field('employee_button_text', $is_en ? 'Inquire' : '咨询', $post_id);
        $btn_style = hireai_field('employee_button_style', 'auto', $post_id);
        $cases_link = hireai_link('employee_cases_link', '/category/cases/', $is_en ? 'View Related Cases' : '查看相关案例', $post_id);

        $image = hireai_image('employee_image', hireai_default_image('employee-1.jpg'), $post_id);
        if (!$image) {
            $image = has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'hireai-wide') : hireai_default_image('employee-1.jpg');
        }

        $cap_lines = [];
        if (!empty($caps_raw)) {
            $cap_lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $caps_raw))));
        }

        $btn_class = 'hireai-btn hireai-btn--outline';
        if ($btn_style === 'filled') {
            $btn_class = 'hireai-btn hireai-btn--filled';
        }
        ?>
        <article class="hireai-single-employee container">
            <div class="hireai-single-employee__grid">
                <div class="hireai-single-employee__media">
                    <?php if ($image) : ?>
                        <img class="hireai-single-employee__img" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                    <?php endif; ?>
                    <?php if ($kicker !== '') : ?>
                        <span class="hireai-single-employee__kicker"><?php echo esc_html($kicker); ?></span>
                    <?php endif; ?>
                </div>

                <div class="hireai-single-employee__summary">
                    <span class="hireai-chip hireai-single-employee__role"><?php echo esc_html($role); ?></span>
                    <h1 class="hireai-display-lg hireai-single-employee__title"><?php the_title(); ?></h1>

                    <?php if ($skill !== '') : ?>
                        <p class="hireai-body-lg hireai-single-employee__skill"><?php echo nl2br(esc_html($skill)); ?></p>
                    <?php elseif (has_excerpt()) : ?>
                        <p class="hireai-body-lg hireai-single-employee__skill"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>

                    <?php if ($soul !== '') : ?>
                        <div class="hireai-single-employee__divider" aria-hidden="true"></div>
                        <p class="hireai-body-md hireai-single-employee__soul"><?php echo nl2br(esc_html($soul)); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($cap_lines)) : ?>
                        <div class="hireai-single-employee__capabilities">
                            <?php foreach ($cap_lines as $line) : ?>
                                <span class="hireai-tag"><?php echo esc_html($line); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="hireai-single-employee__actions">
                        <a class="<?php echo esc_attr($btn_class); ?>" href="<?php echo esc_url($cases_link['url']); ?>">
                            <?php echo esc_html($btn_text); ?>
                            <?php echo hireai_svg('arrow', 14); ?>
                        </a>
                        <a class="hireai-link hireai-link--ghost" href="<?php echo esc_url(home_url('/contact/')); ?>">
                            <?php echo esc_html($is_en ? 'Request Custom Build' : '请求定制'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <?php if (get_the_content() !== '') : ?>
                <section class="hireai-single-employee__content">
                    <h2 class="hireai-headline-md"><?php echo esc_html($is_en ? 'Background' : '背景资料'); ?></h2>
                    <div class="hireai-body-md hireai-prose"><?php the_content(); ?></div>
                </section>
            <?php endif; ?>
        </article>

        <style>
        .hireai-single-employee { padding: clamp(40px, 6vw, 80px) clamp(20px, 4vw, 80px); max-width: 1280px; margin: 0 auto; }
        .hireai-single-employee__grid { display: grid; grid-template-columns: 1fr; gap: 32px; }
        @media (min-width: 900px) { .hireai-single-employee__grid { grid-template-columns: 5fr 7fr; gap: 64px; align-items: start; } }
        .hireai-single-employee__media { position: relative; aspect-ratio: 3/4; overflow: hidden; border-radius: 12px; background: var(--surface-container-low, #f4f3f3); border: 1px solid var(--gold-light, #e9c176); }
        .hireai-single-employee__img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s ease; }
        .hireai-single-employee__media:hover .hireai-single-employee__img { transform: scale(1.04); }
        .hireai-single-employee__kicker { position: absolute; top: 16px; left: 16px; background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 6px 14px; border-radius: 999px; font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--on-surface, #1a1c1c); }
        .hireai-single-employee__summary { display: flex; flex-direction: column; gap: 16px; }
        .hireai-chip { display: inline-block; padding: 4px 12px; border: 1px solid var(--gold-primary, #775a19); border-radius: 999px; font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--gold-primary, #775a19); width: fit-content; }
        .hireai-single-employee__title { margin: 8px 0 0 0; }
        .hireai-single-employee__skill { color: var(--on-surface-variant, #444748); }
        .hireai-single-employee__divider { width: 48px; height: 1px; background: linear-gradient(to right, var(--gold-primary, #775a19), transparent); margin: 8px 0; }
        .hireai-single-employee__soul { color: var(--on-surface-variant, #444748); font-style: italic; }
        .hireai-single-employee__capabilities { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .hireai-tag { display: inline-block; padding: 6px 14px; background: rgba(255,255,255,0.6); backdrop-filter: blur(10px); border: 1px solid var(--outline-variant, #c4c7c7); border-radius: 999px; font-family: 'Inter', sans-serif; font-size: 12px; color: var(--on-surface, #1a1c1c); }
        .hireai-single-employee__actions { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; margin-top: 24px; }
        .hireai-btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; border-radius: 999px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; text-decoration: none; transition: all 0.3s ease; cursor: pointer; }
        .hireai-btn--filled { background: var(--primary, #000); color: var(--on-primary, #fff); border: 1px solid var(--primary, #000); }
        .hireai-btn--filled:hover { background: #1a1a1a; box-shadow: 0 0 20px rgba(233, 193, 118, 0.4); }
        .hireai-btn--outline { background: transparent; color: var(--gold-primary, #775a19); border: 1px solid var(--gold-primary, #775a19); }
        .hireai-btn--outline:hover { background: var(--gold-primary, #775a19); color: #fff; }
        .hireai-link--ghost { font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--gold-primary, #775a19); text-decoration: none; }
        .hireai-link--ghost:hover { color: var(--on-surface, #1a1c1c); }
        .hireai-single-employee__content { margin-top: 64px; padding-top: 32px; border-top: 1px solid var(--outline-variant, #c4c7c7); }
        .hireai-prose { line-height: 1.7; color: var(--on-surface, #1a1c1c); }
        .hireai-prose p { margin: 0 0 16px 0; }
        </style>

    <?php elseif ($is_faq) :
        $faq_group = function_exists('get_field') ? get_field('faq_group', $post_id) : '';
        $group_labels = [
            'partnership'      => $is_en ? 'Partnership' : '合作方式',
            'finance'          => $is_en ? 'Finance' : '财务',
            'privacy-security' => $is_en ? 'Privacy & Security' : '隐私和安全',
            'other'            => $is_en ? 'Other' : '其他',
        ];
        $group_label = isset($group_labels[$faq_group]) ? $group_labels[$faq_group] : $group_labels['other'];
        ?>
        <article class="hireai-single-faq container">
            <header class="hireai-single-faq__header">
                <span class="hireai-chip"><?php echo esc_html($group_label); ?></span>
                <h1 class="hireai-display-lg"><?php the_title(); ?></h1>
            </header>
            <div class="hireai-single-faq__content hireai-body-lg hireai-prose">
                <?php if (has_excerpt()) : ?>
                    <p class="hireai-single-faq__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
                <?php the_content(); ?>
            </div>
            <footer class="hireai-single-faq__footer">
                <a class="hireai-link--ghost" href="<?php echo esc_url(home_url('/faq/')); ?>">
                    <?php echo esc_html($is_en ? 'Back to All FAQs' : '返回所有常见问题'); ?>
                </a>
            </footer>
        </article>

        <style>
        .hireai-single-faq { padding: clamp(40px, 6vw, 80px) clamp(20px, 4vw, 80px); max-width: 880px; margin: 0 auto; }
        .hireai-single-faq__header { display: flex; flex-direction: column; gap: 16px; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--outline-variant, #c4c7c7); }
        .hireai-single-faq__excerpt { font-size: 18px; color: var(--on-surface-variant, #444748); font-style: italic; }
        .hireai-single-faq__footer { margin-top: 48px; padding-top: 24px; border-top: 1px solid var(--outline-variant, #c4c7c7); }
        </style>

    <?php else : ?>
        <article class="hireai-single-post container">
            <header class="hireai-single-post__header">
                <span class="hireai-chip"><?php echo esc_html($is_en ? 'Journal' : '文章'); ?></span>
                <h1 class="hireai-display-lg"><?php the_title(); ?></h1>
                <div class="hireai-single-post__meta">
                    <time><?php echo esc_html(get_the_date()); ?></time>
                    <?php
                    $cat_objs = wp_get_post_terms($post_id, 'category', ['fields' => 'all']);
                    if (!empty($cat_objs) && !is_wp_error($cat_objs)) {
                        echo ' · ';
                        $names = array_map(function($t) { return $t->name; }, $cat_objs);
                        echo esc_html(implode(', ', $names));
                    }
                    ?>
                </div>
            </header>
            <?php if (has_post_thumbnail()) : ?>
                <div class="hireai-single-post__hero">
                    <?php the_post_thumbnail('hireai-wide', ['class' => 'hireai-single-post__hero-img', 'loading' => 'lazy']); ?>
                </div>
            <?php endif; ?>
            <div class="hireai-single-post__content hireai-body-lg hireai-prose">
                <?php the_content(); ?>
            </div>
            <footer class="hireai-single-post__footer">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                if ($prev_post || $next_post) : ?>
                    <nav class="hireai-single-post__nav" aria-label="<?php echo esc_attr($is_en ? 'Post navigation' : '文章导航'); ?>">
                        <?php if ($prev_post) : ?>
                            <a class="hireai-link--ghost" href="<?php echo esc_url(get_permalink($prev_post)); ?>">
                                &larr; <?php echo esc_html($prev_post->post_title); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($next_post) : ?>
                            <a class="hireai-link--ghost" href="<?php echo esc_url(get_permalink($next_post)); ?>">
                                <?php echo esc_html($next_post->post_title); ?> &rarr;
                            </a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            </footer>
        </article>

        <style>
        .hireai-single-post { padding: clamp(40px, 6vw, 80px) clamp(20px, 4vw, 80px); max-width: 880px; margin: 0 auto; }
        .hireai-single-post__header { margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--outline-variant, #c4c7c7); }
        .hireai-single-post__meta { margin-top: 12px; font-family: 'Inter', sans-serif; font-size: 13px; color: var(--on-surface-variant, #444748); letter-spacing: 0.05em; }
        .hireai-single-post__hero { margin-bottom: 32px; border-radius: 12px; overflow: hidden; }
        .hireai-single-post__hero-img { width: 100%; height: auto; display: block; }
        .hireai-single-post__content { line-height: 1.8; }
        .hireai-single-post__footer { margin-top: 48px; padding-top: 24px; border-top: 1px solid var(--outline-variant, #c4c7c7); }
        .hireai-single-post__nav { display: flex; justify-content: space-between; gap: 24px; flex-wrap: wrap; }
        </style>

    <?php endif; ?>
<?php endwhile; ?>

<?php get_footer();
