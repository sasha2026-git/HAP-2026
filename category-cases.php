<?php if (!defined('ABSPATH')) exit;
get_header();
$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';
$paged  = max(1, get_query_var('paged'));
?>
<header class="archive-header">
	<div class="container">
		<span class="label archive-header__kicker"><?php echo esc_html($is_en ? 'Cases' : '案例'); ?></span>
		<h1 class="archive-header__title"><?php single_cat_title(); ?></h1>
		<?php the_archive_description('<div class="archive-header__description">', '</div>'); ?>
	</div>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<?php if (have_posts()) : ?>
			<div class="hireai-product-grid">
				<?php while (have_posts()) : the_post(); ?>
					<?php get_template_part('template-parts/post-card', null, ['cta_text' => $is_en ? 'Read Case' : '阅读案例']); ?>
				<?php endwhile; ?>
			</div>
			<?php hireai_pagination($GLOBALS['wp_query']->max_num_pages, $paged); ?>
		<?php else : ?>
			<p class="faq-empty"><?php echo esc_html($is_en ? 'No cases yet.' : '暂无案例。'); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
