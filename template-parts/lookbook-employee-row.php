<?php
/**
 * 数字员工行（文章驱动版） — Lookbook V2
 * 设计稿：奇数左图右文，偶数左文右图，12格grid
 * @param array $args { index: 序号(1起) }
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$index  = isset( $args['index'] ) ? (int) $args['index'] : 1;
$suffix = function_exists( 'hireai_lang_suffix' ) ? hireai_lang_suffix() : '';
$is_zh  = ( '_zh' === $suffix );
$reverse = ( 0 === $index % 2 );

/* 数据 */
$kicker = '';
$cats   = get_the_category();
foreach ( (array) $cats as $c ) { if ( 'ai-employee' === $c->slug ) { $kicker = $c->name; break; } }
$kicker = lookbook_field( 'lookbook_kicker', $kicker ? $kicker : ( $is_zh ? '数字员工' : 'Digital Employee' ) );
$btn    = lookbook_field( 'lookbook_button_text', $is_zh ? '了解详情' : 'Learn More' );
$style  = get_field( 'lookbook_button_style' );
if ( empty( $style ) || 'auto' === $style ) { $style = $reverse ? 'filled' : 'outline'; }
$link   = get_field( 'lookbook_link' );
$url    = '';
if ( is_array( $link ) && ! empty( $link['url'] ) ) { $url = $link['url']; }
elseif ( is_string( $link ) && $link ) { $url = $link; }
if ( empty( $url ) ) { $url = get_permalink(); }
$img  = get_the_post_thumbnail_url( get_the_ID(), 'large' );
if ( ! $img ) { $img = lookbook_img( 'service-' . min( $index, 5 ) . '.png' ); }
$title = get_the_title();
$desc  = get_the_excerpt();
if ( empty( $desc ) ) { $desc = wp_trim_words( strip_tags( get_the_content() ), 34, '…' ); }

$label  = str_pad( (string) $index, 2, '0', STR_PAD_LEFT ) . ' / ' . esc_html( $kicker );
$btn_cls = 'filled' === $style ? 'lb-btn--primary' : 'lb-btn--outline';
?>
<section class="lb-row <?php echo $reverse ? 'lb-row--reverse' : ''; ?>" data-lb-reveal>

	<!-- 图片区 -->
	<div class="lb-row__media">
		<div class="lb-row__border" aria-hidden="true"></div>
		<img class="lb-row__image" src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
	</div>

	<!-- 文字区 -->
	<div class="lb-row__text">
		<span class="lb-row__kicker"><?php echo esc_html( $label ); ?></span>
		<h2 class="lb-row__title"><?php echo esc_html( $title ); ?></h2>
		<p class="lb-row__desc"><?php echo esc_html( $desc ); ?></p>
		<a class="lb-btn <?php echo esc_attr( $btn_cls ); ?>" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $btn ); ?></a>
	</div>

</section>
