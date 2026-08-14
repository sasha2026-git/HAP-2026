<?php if (!defined('ABSPATH')) exit;
$title     = isset($args['title']) ? $args['title'] : '';
$excerpt   = isset($args['excerpt']) ? $args['excerpt'] : '';
$tag       = isset($args['tag']) ? $args['tag'] : '';
$link      = isset($args['link']) ? $args['link'] : home_url('/');
$price     = isset($args['price']) ? $args['price'] : '';
$retainer  = isset($args['retainer']) ? $args['retainer'] : '';
$operative = isset($args['operative']) ? $args['operative'] : '';
$cta       = isset($args['cta_text']) ? $args['cta_text'] : '探索更多';
$cats      = isset($args['cats']) ? $args['cats'] : '';
$image     = isset($args['image']) ? $args['image'] : '';
?>
<article class="product-card" data-cats="<?php echo esc_attr($cats); ?>" data-reveal>
	<a class="product-card__media" href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true"<?php if ($image !== '') : ?> style="background-image:url('<?php echo esc_url($image); ?>')"<?php endif; ?>>
		<?php if ($tag !== '') : ?><span class="product-card__badge product-card__badge--category"><?php echo esc_html($tag); ?></span><?php endif; ?>
	</a>
	<div class="product-card__body">
		<h3 class="product-card__title"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
		<?php if ($operative !== '') : ?><div class="product-card__operative"><?php echo esc_html($operative); ?></div><?php endif; ?>
		<p class="product-card__excerpt"><?php echo esc_html($excerpt); ?></p>
		<div class="product-card__footer">
			<div class="product-card__retainer">
				<span><?php echo esc_html($retainer); ?></span>
				<strong class="product-card__price"><?php echo esc_html($price); ?></strong>
			</div>
			<a class="product-card__arrow" href="<?php echo esc_url($link); ?>" aria-label="<?php echo esc_attr($cta); ?>"><?php echo hireai_svg('arrow', 18); ?></a>
		</div>
	</div>
</article>
