<?php
if (!defined('ABSPATH')) exit;
/**
 * 通用页面（隐私政策 / 服务条款 / 退换货政策 / 法律声明等）
 */
get_header();

$suffix = hireai_lang_suffix();
?>
<?php while (have_posts()) : the_post(); ?>
	<header class="page-hero" data-reveal>
		<span class="label page-hero__kicker"><?php echo esc_html($suffix === '_en' ? 'Page' : '页面'); ?></span>
		<h1 class="headline-lg page-hero__title"><?php the_title(); ?></h1>
	</header>

	<section class="section" style="padding-top:0;">
		<div class="container" data-reveal>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
<?php endwhile; ?>

<?php get_footer(); ?>
