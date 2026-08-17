<?php
/**
 * 数字员工行（兜底版） — Lookbook V2
 * @param array $args { index: 序号(1起), item: {kicker,title,desc,button,image,url} }
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$index   = isset( $args['index'] ) ? (int) $args['index'] : 1;
$item    = isset( $args['item'] ) ? $args['item'] : array();
$reverse = ( 0 === $index % 2 );
$style   = $reverse ? 'filled' : 'outline';
$img     = ! empty( $item['image'] ) ? lookbook_img( $item['image'] ) : lookbook_img( 'service-' . min( $index, 5 ) . '.png' );
$url     = ! empty( $item['url'] ) ? $item['url'] : '#';
$label   = str_pad( (string) $index, 2, '0', STR_PAD_LEFT ) . ' / ' . esc_html( $item['kicker'] );
$btn_cls = 'filled' === $style ? 'lb-btn--primary' : 'lb-btn--outline';
?>
<section class="lb-row <?php echo $reverse ? 'lb-row--reverse' : ''; ?>" data-lb-reveal>

	<!-- 图片区 -->
	<div class="lb-row__media">
		<div class="lb-row__border" aria-hidden="true"></div>
		<img class="lb-row__image" src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" />
	</div>

	<!-- 文字区 -->
	<div class="lb-row__text">
		<span class="lb-row__kicker"><?php echo esc_html( $label ); ?></span>
		<h2 class="lb-row__title"><?php echo esc_html( $item['title'] ); ?></h2>
		<p class="lb-row__desc"><?php echo esc_html( $item['desc'] ); ?></p>
		<a class="lb-btn <?php echo esc_attr( $btn_cls ); ?>" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $item['button'] ); ?></a>
	</div>

</section>
