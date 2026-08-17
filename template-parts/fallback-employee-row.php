<?php if (!defined('ABSPATH')) exit;
$index        = isset($args['index']) ? absint($args['index']) : 1;
$kicker       = isset($args['kicker']) ? $args['kicker'] : '';
$title        = isset($args['title']) ? $args['title'] : '';
$text         = isset($args['text']) ? $args['text'] : '';
$button_text  = isset($args['button_text']) ? $args['button_text'] : '';
$button_style = isset($args['button_style']) ? $args['button_style'] : 'filled';
$link         = isset($args['link']) ? $args['link'] : home_url('/');
$image        = isset($args['image']) ? $args['image'] : '';
$reverse      = !empty($args['reverse']);
$style_class  = $button_style === 'outline' ? 'lookbook-row__button--outline' : 'lookbook-row__button';
?>
<article class="lookbook-row<?php echo $reverse ? ' lookbook-row--reverse' : ''; ?>" data-reveal>
	<div class="lookbook-row__media">
		<span class="lookbook-row__frame" aria-hidden="true"></span>
		<a class="lookbook-row__image" href="<?php echo esc_url($link); ?>">
			<img class="lookbook-row__photo" src="<?php echo esc_url($image); ?>" alt="" loading="lazy">
		</a>
	</div>
	<div class="lookbook-row__content">
		<p class="lookbook-row__eyebrow"><span class="lookbook-row__num"><?php echo esc_html(str_pad($index, 2, '0', STR_PAD_LEFT)); ?></span><span class="lookbook-row__separator" aria-hidden="true">/</span><?php echo esc_html($kicker); ?></p>
		<h2 class="lookbook-row__title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h2>
		<p class="lookbook-row__description"><?php echo esc_html($text); ?></p>
		<a class="lookbook-row__button <?php echo esc_attr($style_class); ?>" href="<?php echo esc_url($link); ?>"><?php echo esc_html($button_text); ?> <?php echo hireai_svg('east', 16); ?></a>
	</div>
</article>
