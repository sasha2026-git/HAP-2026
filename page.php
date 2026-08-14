<?php if (!defined('ABSPATH')) exit;
/**
 * 通用页面：页头 + 正文。
 */
get_header();
$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';
?>
<div class="container page-content">
	<header class="page-hero">
		<h1 class="display-lg page-hero__title"><?php the_title(); ?></h1>
	</header>
	<div class="section" style="padding-top:0;">
		<?php
		while (have_posts()) {
			the_post();
			the_content();
		}
		?>
	</div>
</div>
<?php get_footer(); ?>
