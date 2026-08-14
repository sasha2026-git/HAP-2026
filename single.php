<?php if (!defined('ABSPATH')) exit;
/**
 * 文章详情：category=ai-employee → 数字员工详情（参考 ai_aether_ai_light_luxe_3）
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
		$role          = hireai_field('employee_role', $suffix === '_en' ? 'Digital Employee' : '数字员工', $pid);
		$soul          = hireai_field('employee_soul', $suffix === '_en' ? 'Cultivated with a specific psychological profile: quiet, precise, and shaped by logic.' : '以逻辑为骨、以静谧为魂，被悉心培育出独一无二的心智与气质。', $pid);
		$skill         = hireai_field('employee_skill', $suffix === '_en' ? 'Masters data analysis, market strategy, and content creation: ready to collaborate seamlessly with your team.' : '精通数据分析、市场策略与内容创作，可与您的团队无缝协作。', $pid);
		$capabilities  = hireai_field('employee_capabilities', $suffix === '_en' ? "Deep market research\nReal-time data analysis\nMultilingual content creation\n24×7 availability" : "深度市场调研\n实时数据分析\n多语言内容创作\n24×7 待命服务", $pid);
		$cases_link    = hireai_link('employee_cases_link', '/category/cases/', $suffix === '_en' ? 'View Related Cases' : '查看相关案例', $pid);
		$back_text     = $suffix === '_en' ? 'All Employees' : '全部数字员工';
		$contact_page = get_page_by_path('contact');
		$contact_url = $contact_page instanceof WP_Post ? get_permalink($contact_page) : home_url('/contact/');
		$cap_lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $capabilities))));
		$trait_1_title = $suffix === '_en' ? 'Precision' : '精准';
		$trait_2_title = $suffix === '_en' ? 'Composure' : '沉静';
		$trait_1_text  = $suffix === '_en' ? 'Micro-calibrated to deliver answers with exactly the context required.' : '以最恰当的信息粒度交付结论，不增加噪音。';
		$trait_2_text  = $suffix === '_en' ? 'Unaffected by cognitive bias or emotional variance during critical data flux.' : '在关键数据波动中不受认知偏差或情绪波动影响。';
		if (isset($cap_lines[0])) {
			$trait_1_title = $cap_lines[0];
		}
		if (isset($cap_lines[1])) {
			$trait_2_title = $cap_lines[1];
		}
		if (isset($cap_lines[2])) {
			$trait_1_text = $cap_lines[2];
		}
		if (isset($cap_lines[3])) {
			$trait_2_text = $cap_lines[3];
		}
		?>
		<article <?php post_class(); ?>>
			<section class="employee-profile-hero">
				<div class="employee-profile-hero__content" data-reveal>
					<span class="employee-profile-chip"><?php echo esc_html($role); ?></span>
					<h1 class="display-lg employee-profile-hero__name"><?php the_title(); ?></h1>
					<p class="body-lg employee-profile-hero__intro"><?php echo esc_html(get_the_excerpt()); ?></p>
					<div class="employee-profile-actions">
						<a class="btn btn-primary btn-lg" href="<?php echo esc_url($contact_url); ?>">
							<?php echo esc_html($suffix === '_en' ? 'Integrate Now' : '立即整合'); ?>
						</a>
						<a class="btn btn-ghost" href="#employee-detail"><?php echo esc_html($suffix === '_en' ? 'View Architecture' : '查看架构'); ?></a>
					</div>
				</div>
				<div class="employee-profile-hero__media" data-reveal>
					<?php if (has_post_thumbnail()) : ?>
						<?php the_post_thumbnail('hireai-wide'); ?>
					<?php else : ?>
						<span class="media-placeholder" style="min-height:600px;">HireAI People</span>
					<?php endif; ?>
				</div>
			</section>

			<?php if ($soul !== '') : ?>
				<section class="employee-soul">
					<div class="container employee-soul__grid">
						<div class="employee-soul__lead">
							<div class="employee-soul__label-row">
								<span class="employee-soul__line" aria-hidden="true"></span>
								<span class="label"><?php echo esc_html($suffix === '_en' ? 'The Soul' : '灵魂'); ?></span>
							</div>
							<h2 class="employee-soul__title"><?php echo esc_html($suffix === '_en' ? 'A temperament carved from logic.' : '以逻辑为骨、以静谧为魂。'); ?></h2>
						</div>
						<div class="employee-soul__body">
							<p class="body-lg employee-soul__text"><?php echo esc_html($soul); ?></p>
							<div class="employee-trait-grid">
								<div class="employee-trait">
									<h3 class="employee-trait__title"><?php echo esc_html($trait_1_title); ?></h3>
									<p class="employee-trait__text"><?php echo esc_html($trait_1_text); ?></p>
								</div>
								<div class="employee-trait">
									<h3 class="employee-trait__title"><?php echo esc_html($trait_2_title); ?></h3>
									<p class="employee-trait__text"><?php echo esc_html($trait_2_text); ?></p>
								</div>
							</div>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<div id="employee-detail" class="container">
				<div class="employee-detail">
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
								foreach ($cap_lines as $line) {
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
			<header class="page-hero page-hero--left" data-reveal>
				<?php if ($cat_name !== '') : ?>
					<span class="label page-hero__kicker"><?php echo esc_html($cat_name); ?></span>
				<?php endif; ?>
				<h1 class="display-lg page-hero__title"><?php the_title(); ?></h1>
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
