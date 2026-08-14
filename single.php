<?php if (!defined('ABSPATH')) exit;
/**
 * 文章详情：数字员工档案（ACF）或通用文章。
 */
get_header();

$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';

while (have_posts()) :
	the_post();
	$cats = wp_get_post_terms(get_the_ID(), 'category', ['fields' => 'slugs']);
	$cats = is_wp_error($cats) ? [] : (array) $cats;
	$is_employee = in_array('ai-employee', $cats, true) || site_field('employee_role' . $suffix, '', get_the_ID()) !== '';
	$role = site_field('employee_role' . $suffix, '', get_the_ID());
	$soul = site_field('employee_soul' . $suffix, '', get_the_ID());
	$skill = site_field('employee_skill' . $suffix, '', get_the_ID());
	$caps = site_field('employee_capabilities' . $suffix, '', get_the_ID());
	$cases_link = hireai_link('employee_cases_link', '/category/cases/', $is_en ? 'View Related Cases' : '查看相关案例', get_the_ID());
	$image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'hireai-wide') : hireai_default_image('employee-1.jpg');
	$cap_lines = $caps !== '' ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $caps)))) : [];
	if ($is_employee) :
		?>
		<div class="container">
			<section class="product-single">
				<div class="product-single__gallery">
					<a class="employee-row__media" href="<?php the_permalink(); ?>" style="display:block;<?php if ($image) : ?> background-image:url('<?php echo esc_url($image); ?>');<?php endif; ?>"></a>
				</div>
				<div class="product-single__summary">
					<span class="chip product-single__cat"><?php echo esc_html($role !== '' ? $role : ($is_en ? 'Digital Employee' : '数字员工')); ?></span>
					<h1 class="display-lg product-single__title"><?php the_title(); ?></h1>
					<p class="body-lg"><?php echo esc_html($skill !== '' ? $skill : get_the_excerpt()); ?></p>
					<?php if ($soul !== '') : ?>
						<div class="product-single__divider" aria-hidden="true"></div>
						<p class="body-md"><?php echo esc_html($soul); ?></p>
					<?php endif; ?>
					<?php if ($cap_lines) : ?>
						<div class="employee-tags" style="margin-top:24px;">
							<?php foreach ($cap_lines as $line) : ?>
								<span class="employee-tag"><?php echo esc_html($line); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<div class="product-single__actions">
						<a class="btn btn-ghost" href="<?php echo esc_url($cases_link['url']); ?>"><?php echo esc_html($cases_link['title']); ?> <?php echo hireai_svg('arrow', 14); ?></a>
					</div>
				</div>
			</section>
		</div>
	<?php else : ?>
		<div class="container single-layout">
			<header class="single-layout__header">
				<span class="label page-hero__kicker"><?php echo esc_html($is_en ? 'Journal' : '文章'); ?></span>
				<h1 class="display-lg"><?php the_title(); ?></h1>
				<div class="single-layout__meta"><?php echo esc_html(get_the_date()); ?></div>
			</header>
			<div class="single-layout__content"><?php the_content(); ?></div>
		</div>
	<?php endif; ?>
<?php endwhile; ?>

<?php get_footer(); ?>
