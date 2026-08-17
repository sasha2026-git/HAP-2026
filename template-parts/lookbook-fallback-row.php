<?php
/**
 * 数字员工行（兜底版） — Lookbook V2
 * @param array $args { index: 序号(1起), item: {kicker,title,desc,button,image,url} }
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$index = isset( $args['index'] ) ? (int) $args['index'] : 1;
$item  = isset( $args['item'] ) ? $args['item'] : array();
$style = ( 0 === $index % 2 ) ? 'outline' : 'primary';
$img   = ! empty( $item['image'] ) ? lookbook_img( $item['image'] ) : lookbook_img( 'service-' . min( $index, 5 ) . '.png' );
$url   = ! empty( $item['url'] ) ? $item['url'] : '#';
$num   = str_pad( (string) $index, 2, '0', STR_PAD_LEFT );
?>
<article class="lb-row <?php echo ( 0 === $index % 2 ) ? 'lb-row--reverse' : ''; ?>" data-lb-reveal>
	<div class="lb-row__media">
		<div class="lb-row__border" aria-hidden="true"></div>
		<img class="lb-row__image" src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" />
	</div>
	<div class="lb-row__text">
		<p class="lb-row__kicker"><span><?php echo esc_html( $num ); ?></span><span aria-hidden="true"> / </span><span><?php echo esc_html( $item['kicker'] ); ?></span></p>
		<h2 class="lb-row__title"><?php echo esc_html( $item['title'] ); ?></h2>
		<p class="lb-row__desc"><?php echo esc_html( $item['desc'] ); ?></p>
		<a class="lb-btn <?php echo esc_attr( $style === 'outline' ? 'lb-btn--outline' : 'lb-btn--primary' ); ?>" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $item['button'] ); ?></a>
	</div>
</article>
