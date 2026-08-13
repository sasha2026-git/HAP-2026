<?php
if (!defined('ABSPATH')) exit;
/**
 * 文章详情：category=ai-employee → 数字员工详情（soul/skill/capabilities）
 *             category=cases / insights → 案例·洞察文章
 *             其他 → 通用文章
 */
get_header();

$suffix = hireai_lang_suffix();
$is_employee = in_category('ai-employee');
?>
<?php while (have_posts()) : the_post(); ?>

	<?php if ($is_employee) : ?>

		<?php
		$pid           = get_the_ID();
		$role          = site_field('employee_role', $suffix === '_en' ? 'Digital Employee' : '数字员工', $pid);
		$soul          = site_field('employee_soul', '', $pid);
		$skill         = site_field('employee_skill', '', $pid);
		$capabilities  = site_field('employee_capabilities', '', $pid);
		$cases_link    = site_link('employee_cases_link', '/category/cases/', $suffix === '_en' ? 'View Related Cases' : '查看相关案例', $pid);
		$back_text     = $suffix === '_en' ? 'All Employees' : '全部数字员工';
		?>
		<article <?php post_class(); ?>>
			<div class="employee-hero">
				<div class="employee-hero__media" data-reveal>
					<?php if (has_post_thumbnail()) : ?>
						<?php the_post_thumbnail('hireai-wide'); ?>
					<?php else : ?>
						<span class="media-placeholder" style="min-height:480px;">HireAI People</span>
					<?php endif; ?>
				</div>
				<div class="employee-hero__content" data-reveal>
					<span class="label employee-hero__kicker"><?php echo esc_html($suffix === '_en' ? 'AI Employee' : 'AI 数字员工'); ?></span>
					<h1 class="employee-hero__name"><?php the_title(); ?></h1>
					<?php if ($role !== '') : ?>
						<div class="employee-hero__role"><?php echo esc_html($role); ?></div>
					<?php endif; ?>
					<p class="employee-hero__intro"><?php echo esc_html(get_the_excerpt()); ?></p>
				</div>
			</div>

			<div class="container">
				<div class="employee-detail">
					<?php if ($soul !== '') : ?>
						<section class="employee-block">
							<span class="label employee-block__kicker"><?php echo esc_html($suffix === '_en' ? 'The Soul' : '灵魂'); ?></span>
							<h2 class="employee-block__title"><?php echo esc_html($suffix === '_en' ? 'Soul' : '灵魂'); ?></h2>
							<div class="employee-block__text"><?php echo esc_html($soul); ?></div>
						</section>
					<?php endif; ?>

					<?php if ($skill !== '') : ?>
						<section class="employee-block">
							<span class="label employee-block__kicker"><?php echo esc_html($suffix === '_en' ? 'The Skill' : '技能'); ?></span>
							<h2 class="employee-block__title"><?php echo esc_html($suffix === '_en' ? 'Skill' : '技能'); ?></h2>
							<div class="employee-block__text"><?php echo esc_html($skill); ?></div>
						</section>
					<?php endif; ?>

					<?php if ($capabilities !== '') : ?>
						<section class="employee-block">
							<span class="label employee-block__kicker"><?php echo esc_html($suffix === '_en' ? 'Capabilities' : '能力'); ?></span>
							<h2 class="employee-block__title"><?php echo esc_html($suffix === '_en' ? 'Capabilities' : '能力'); ?></h2>
							<ul class="employee-block__list">
								<?php
								$lines = preg_split('/\r\n|\r|\n/', $capabilities);
								foreach ($lines as $line) {
									$line = trim($line);
									if ($line !== '') {
										echo '<li>' . esc_html($line) . '</li>';
									}
								}
								?>
							</ul>
						</section>
					<?php endif; ?>

					<?php if (!empty($cases_link['url'])) : ?>
						<section class="employee-block">
							<a class="btn btn-secondary" href="<?php echo esc_url($cases_link['url']); ?>"<?php echo !empty($cases_link['target']) ? ' target="' . esc_attr($cases_link['target']) . '" rel="noopener"' : ''; ?>>
								<?php echo esc_html($cases_link['title']); ?>
							</a>
						</section>
					<?php endif; ?>
				</div>

				<?php
				$back_emp_cat = get_category_by_slug('ai-employee');
				$back_emp_url = $back_emp_cat ? get_category_link($back_emp_cat) : home_url('/');
				?>
				<nav class="hireai-pagination" style="margin-top:24px;">
					<a class="btn btn-ghost" href="<?php echo esc_url($back_emp_url); ?>">← <?php echo esc_html($back_text); ?></a>
				</nav>
			</div>
		</article>

	<?php else : ?>

		<?php
		$cats = get_the_category();
		$cat_name = !empty($cats) ? $cats[0]->name : '';
		$back_text = $suffix === '_en' ? 'Back to all posts' : '返回全部文章';
		$back_url = get_post_type_archive_link('post');
		if (!$back_url) {
			$back_url = home_url('/');
		}
		?>
		<article <?php post_class(); ?>>
			<header class="page-hero" data-reveal>
				<?php if ($cat_name !== '') : ?>
					<span class="label page-hero__kicker"><?php echo esc_html($cat_name); ?></span>
				<?php endif; ?>
				<h1 class="headline-lg page-hero__title"><?php the_title(); ?></h1>
				<p class="body-md page-hero__subtitle" style="margin-top:16px;">
					<?php echo esc_html(get_the_date('Y.m.d')); ?>
				</p>
			</header>

			<?php if (has_post_thumbnail()) : ?>
				<div class="container" data-reveal>
					<figure style="margin:0 auto 64px;max-width:1100px;">
						<?php the_post_thumbnail('hireai-wide', ['style' => 'border-radius:var(--radius-card);width:100%;']); ?>
					</figure>
				</div>
			<?php endif; ?>

			<div class="container" data-reveal>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<nav class="hireai-pagination" style="margin-top:24px;">
					<a class="btn btn-ghost" href="<?php echo esc_url($back_url); ?>">← <?php echo esc_html($back_text); ?></a>
				</nav>
			</div>
		</article>

	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
