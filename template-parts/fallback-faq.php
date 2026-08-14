<?php if (!defined('ABSPATH')) exit;
/**
 * 无内容时的默认 FAQ 条目
 * 用法：get_template_part('template-parts/fallback-faq', null, ['question' => '', 'answer' => '']);
 */
$question = isset($args['question']) ? $args['question'] : '';
$answer   = isset($args['answer']) ? $args['answer'] : '';
?>
<article class="faq-item">
	<button class="faq-item__toggle" type="button" aria-expanded="false">
		<span class="faq-item__q"><span class="faq-item__q-text"><?php echo esc_html($question); ?></span></span>
		<span class="faq-item__icon" aria-hidden="true"></span>
	</button>
	<div class="faq-item__a">
		<div class="faq-item__a-inner">
			<p><span class="faq-item__a-text"><?php echo esc_html($answer); ?></span></p>
		</div>
	</div>
</article>
