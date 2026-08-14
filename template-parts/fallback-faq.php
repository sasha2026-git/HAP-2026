<?php if (!defined('ABSPATH')) exit;
$question = isset($args['question']) ? $args['question'] : '';
$answer   = isset($args['answer']) ? $args['answer'] : '';
?>
<article class="faq-item">
	<button class="faq-item__toggle" type="button" aria-expanded="false">
		<span class="faq-item__q"><span class="faq-item__q-text"><?php echo esc_html($question); ?></span></span>
		<span class="faq-item__icon" aria-hidden="true"><?php echo hireai_svg('plus', 22); ?></span>
	</button>
	<div class="faq-item__a">
		<div class="faq-item__a-inner"><p><span class="faq-item__a-text"><?php echo esc_html($answer); ?></span></p></div>
	</div>
</article>
