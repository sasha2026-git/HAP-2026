<?php
/**
 * 数字员工行（兜底版） — Lookbook V2
 * @param array $args { index: 序号(1起), item: {kicker,title,desc,button,image,url} }
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$index = isset( $args['index'] ) ? (int) $args['index'] : 1;
$item  = isset( $args['item'] ) ? $args['item'] : array();
$style = ( 0 === $index % 2 ) ? 'filled' : 'outline';
$img   = ! empty( $item['image'] ) ? lookbook_img( $item['image'] ) : lookbook_img( 'service-' . min( $index, 5 ) . '.png' );
$url   = ! empty( $item['url'] ) ? $item['url'] : '#';
?>
<article class="lookbook-row <?php echo ( 0 === $index % 2 ) ? 'lookbook-row--reverse' : ''; ?>" data-lb-reveal>
	<div class="lookbook-row__media">
		<div class="lookbook-row__frame" aria-hidden="true"></div>
		<div class="lookbook-row__image-wrap">
			<img class="lookbook-row__image" src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" />
		</div>
	</div>
	<div class="lookbook-row__copy">
		<p class="lookbook-row__label"><span class="lookbook-row__num"><?php echo esc_html( str_pad( (string) $index, 2, '0', STR_PAD_LEFT ) ); ?></span><span class="lookbook-row__slash" aria-hidden="true"> / </span><span class="lookbook-row__kicker"><?php echo esc_html( $item['kicker'] ); ?></span></p>
		<h2 class="lookbook-row__title"><?php echo esc_html( $item['title'] ); ?></h2>
		<p class="lookbook-row__desc"><?php echo esc_html( $item['desc'] ); ?></p>
		<a class="lookbook-btn lookbook-btn--<?php echo esc_attr( $style ); ?>" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $item['button'] ); ?></a>
	</div>
</article>
