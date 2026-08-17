<?php
/**
 * Template Name: 聘AI - AI数字员工（Lookbook V2）
 * 说明：数字员工 = 分类 ai-employee 的文章（posts）驱动；满 5 个自动分页；
 *       无文章时展示设计稿默认 5 位数字员工（兜底）。
 * 版本：1.1.1
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$per_page = defined( 'HIREAI_EMPLOYEES_PER_PAGE' ) ? (int) HIREAI_EMPLOYEES_PER_PAGE : 5;
$paged    = max( 1, (int) get_query_var( 'paged' ) );

$lb_query = new WP_Query( array(
	'post_type'      => 'post',
	'category_name'  => 'ai-employee',
	'posts_per_page' => $per_page,
	'paged'          => $paged,
) );

$suffix = function_exists( 'hireai_lang_suffix' ) ? hireai_lang_suffix() : '';
$is_zh  = ( '_zh' === $suffix );

/* Hero / CTA 文案（ACF 可编辑，缺省=设计稿文案） */
$hero_kicker  = lookbook_field( 'lookbook_hero_kicker', $is_zh ? '数字工坊' : 'The Atelier' );
$hero_title   = lookbook_field( 'lookbook_hero_title', $is_zh ? '精英数字解决方案' : 'Elite Digital Solutions' );
$hero_sub     = lookbook_field( 'lookbook_hero_subtitle', $is_zh ? 'AI 主导流程，人类交付成果。' : '"AI-led process, Human-delivered results."' );
$cta_heading  = lookbook_field( 'lookbook_cta_heading', $is_zh ? '准备好重新定义人性了吗？' : 'Ready to Redefine Humanity?' );
$cta_sub      = lookbook_field( 'lookbook_cta_sub', $is_zh ? '加入运用 Aurelian AI 专属生态的领袖精英之列，开启您的专属篇章。' : "Join the exclusive echelon of leaders leveraging Aurelian AI's bespoke ecosystem." );
$cta_btn      = lookbook_field( 'lookbook_cta_btn', $is_zh ? '开启旅程' : 'Start The Journey' );
$cta_link_txt = lookbook_field( 'lookbook_cta_link', $is_zh ? '下载品牌手册' : 'Download Brand Book' );
$cta_url      = lookbook_field( 'lookbook_cta_url', '' );
if ( empty( $cta_url ) ) { $cta_url = home_url( '/' ); }
?>

<main class="lb-main">

	<!-- ================= Hero ================= -->
	<section class="lb-hero">
		<h2 class="lb-hero__kicker" data-lb-reveal><?php echo esc_html( $hero_kicker ); ?></h2>
		<h1 class="lb-hero__title" data-lb-reveal><?php echo esc_html( $hero_title ); ?></h1>
		<p class="lb-hero__subtitle" data-lb-reveal><?php echo esc_html( $hero_sub ); ?></p>
		<div class="lb-hero__divider" data-lb-reveal aria-hidden="true"></div>
	</section>

	<!-- ================= 数字员工列表 ================= -->
	<div class="lb-container">
		<?php if ( $lb_query->have_posts() ) : $idx = 0; while ( $lb_query->have_posts() ) : $lb_query->the_post(); $idx++; ?>
			<?php get_template_part( 'template-parts/lookbook-employee-row', null, array( 'index' => $idx ) ); ?>
		<?php endwhile; wp_reset_postdata(); else : ?>
			<?php $fallback = function_exists( 'lookbook_fallback_employees' ) ? lookbook_fallback_employees() : array(); foreach ( $fallback as $i => $item ) : ?>
				<?php get_template_part( 'template-parts/lookbook-fallback-row', null, array( 'index' => $i + 1, 'item' => $item ) ); ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<?php if ( $lb_query->max_num_pages > 1 ) : ?>
		<nav class="lb-pagination" aria-label="<?php echo esc_attr( $is_zh ? '分页' : 'Pagination' ); ?>">
			<?php echo paginate_links( array(
				'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
				'format'    => '?paged=%#%',
				'current'   => $paged,
				'total'     => $lb_query->max_num_pages,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'type'      => 'plain',
			) ); ?>
		</nav>
	<?php endif; ?>

	<!-- ================= CTA ================= -->
	<section class="lb-cta">
		<div class="lb-cta__inner">
			<h2 class="lb-cta__heading" data-lb-reveal><?php echo esc_html( $cta_heading ); ?></h2>
			<p class="lb-cta__sub" data-lb-reveal><?php echo esc_html( $cta_sub ); ?></p>
			<div class="lb-cta__actions" data-lb-reveal>
				<a class="lb-btn lb-btn--primary" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_btn ); ?></a>
				<a class="lb-btn lb-btn--ghost" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_link_txt ); ?></a>
			</div>
		</div>
	</section>

</main>

<script>
(function () {
	var items = document.querySelectorAll('[data-lb-reveal]');
	if (!items.length) return;
	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		items.forEach(function (el) { el.classList.add('is-visible'); }); return;
	}
	if (!('IntersectionObserver' in window)) { items.forEach(function (el) { el.classList.add('is-visible'); }); return; }
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) { entry.target.classList.add('is-visible'); io.unobserve(entry.target); }
		});
	}, { threshold: 0.1 });
	items.forEach(function (el) { io.observe(el); });
})();
</script>

<?php get_footer(); ?>
