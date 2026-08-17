<?php if (!defined('ABSPATH')) exit;
/**
 * 区块标题（section header）
 * 用法：get_template_part('template-parts/section-header', null, ['kicker' => '', 'title' => '', 'subtitle' => '', 'align' => 'center']);
 */
$kicker   = isset($args['kicker']) ? $args['kicker'] : '';
$title    = isset($args['title']) ? $args['title'] : '';
$subtitle = isset($args['subtitle']) ? $args['subtitle'] : '';
$align    = isset($args['align']) ? $args['align'] : 'center';
?>
<header class="section-head section-head--<?php echo esc_attr($align); ?>" data-reveal>
	<?php if ($kicker !== '') : ?>
		<span class="label section-head__kicker"><?php echo esc_html($kicker); ?></span>
	<?php endif; ?>
	<?php if ($title !== '') : ?>
		<h2 class="headline-lg section-head__title"><?php echo esc_html($title); ?></h2>
	<?php endif; ?>
	<?php if ($subtitle !== '') : ?>
		<p class="body-md section-head__subtitle"><?php echo esc_html($subtitle); ?></p>
	<?php endif; ?>
</header>
