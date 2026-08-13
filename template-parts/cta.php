<?php
if (!defined('ABSPATH')) exit;
/**
 * CTA 横幅（金色链接带）
 * 用法：get_template_part('template-parts/cta', null, [
 *   'kicker' => '', 'title' => '', 'subtitle' => '',
 *   'link' => ['url' => '#', 'title' => '联系我们'], 'link2' => null,
 * ]);
 */
$kicker   = isset($args['kicker']) ? $args['kicker'] : '';
$title    = isset($args['title']) ? $args['title'] : '';
$subtitle = isset($args['subtitle']) ? $args['subtitle'] : '';
$link     = isset($args['link']) ? $args['link'] : ['url' => '#', 'title' => ''];
$link2    = isset($args['link2']) ? $args['link2'] : null;
?>
<section class="cta-band">
	<div class="container">
		<div class="cta-band__inner" data-reveal>
			<?php if ($kicker !== '') : ?>
				<span class="label cta-band__kicker"><?php echo esc_html($kicker); ?></span>
			<?php endif; ?>
			<?php if ($title !== '') : ?>
				<h2 class="cta-band__title"><?php echo esc_html($title); ?></h2>
			<?php endif; ?>
			<?php if ($subtitle !== '') : ?>
				<p class="cta-band__subtitle"><?php echo esc_html($subtitle); ?></p>
			<?php endif; ?>
			<div class="cta-band__actions">
				<a class="btn btn-primary btn-lg" href="<?php echo esc_url($link['url']); ?>"<?php echo !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '" rel="noopener"' : ''; ?>>
					<?php echo esc_html($link['title']); ?>
				</a>
				<?php if ($link2 && !empty($link2['url'])) : ?>
					<a class="btn btn-secondary btn-lg" href="<?php echo esc_url($link2['url']); ?>"<?php echo !empty($link2['target']) ? ' target="' . esc_attr($link2['target']) . '" rel="noopener"' : ''; ?>>
						<?php echo esc_html($link2['title']); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
