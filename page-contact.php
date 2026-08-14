<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 联系我们
 * 参考 aether_ai_light_luxe_4：浮动标签表单 + 邮箱 / 微信 / 总部地址。
 */
get_header();

$suffix = hireai_lang_suffix();
$is_en  = $suffix === '_en';
$page_id = get_the_ID();
$sent = isset($_GET['sent']) ? sanitize_key(wp_unslash($_GET['sent'])) : '';
$name_label   = hireai_field('form_name_label', $is_en ? 'Name' : '姓名', $page_id);
$company_label = hireai_field('form_company_label', $is_en ? 'Company Entity' : '公司/机构', $page_id);
$email_label  = hireai_field('form_email_label', $is_en ? 'Secure Email' : '邮箱', $page_id);
$message_label = hireai_field('form_message_label', $is_en ? 'Inquiry Details' : '需求描述', $page_id);
$submit_label = hireai_field('form_submit_label', $is_en ? 'Send Inquiry' : '提交咨询', $page_id);
$email = hireai_field('contact_email', 'concierge@hireaipeople.com', $page_id);
$wechat = hireai_field('contact_wechat', 'hireai-official', $page_id);
$qr = hireai_image('wechat_qr', '', $page_id);
$address = hireai_field('contact_address', $is_en ? 'Shanghai, China' : '中国 · 上海', $page_id);
$map_label = hireai_field('contact_map_label', $is_en ? 'View Map' : '查看地图', $page_id);
$map_url = hireai_field('contact_map_url', $is_en ? 'https://uri.amap.com/search?keyword=Shanghai%2C%20China' : 'https://uri.amap.com/search?keyword=Shanghai%2C%20China', $page_id);
$form_action = esc_url(admin_url('admin-post.php'));
?>
<header class="page-hero">
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', $is_en ? 'CONTACT' : '联系', $page_id)); ?></span>
	<h1 class="display-lg page-hero__title"><?php echo esc_html(hireai_field('header_title', $is_en ? 'Initiate Contact' : '发起联络', $page_id)); ?></h1>
	<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('header_subtitle', $is_en ? "Tell us your needs and we'll respond within one business day." : '告诉我们您的需求，我们将在一个工作日内回复。', $page_id)); ?></p>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="contact-grid">
			<div class="contact-form-card">
				<form class="contact-form" action="<?php echo $form_action; ?>" method="post">
					<input type="hidden" name="action" value="hireai_contact">
					<?php wp_nonce_field('hireai_contact', 'hireai_nonce'); ?>
					<div class="form-grid">
						<div class="form-field">
							<input id="contact-name" name="name" type="text" placeholder="<?php echo esc_attr($name_label); ?>" required>
							<label for="contact-name"><?php echo esc_html($name_label); ?></label>
						</div>
						<div class="form-field">
							<input id="contact-company" name="company" type="text" placeholder="<?php echo esc_attr($company_label); ?>">
							<label for="contact-company"><?php echo esc_html($company_label); ?></label>
						</div>
					</div>
					<div class="form-field">
						<input id="contact-email" name="email" type="email" placeholder="<?php echo esc_attr($email_label); ?>" required>
						<label for="contact-email"><?php echo esc_html($email_label); ?></label>
					</div>
					<div class="form-field">
						<textarea id="contact-message" name="message" rows="4" placeholder="<?php echo esc_attr($message_label); ?>" required></textarea>
						<label for="contact-message"><?php echo esc_html($message_label); ?></label>
					</div>
					<div class="form-field" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
						<label for="website">Website</label>
						<input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
					</div>
					<?php if ($sent === 'success') : ?>
						<div class="form-message"><?php echo esc_html(hireai_field('form_success', $is_en ? "Your inquiry has been sent. We'll be in touch shortly." : '您的咨询已发送，我们将尽快与您联系。', $page_id)); ?></div>
					<?php elseif ($sent === 'invalid') : ?>
						<div class="form-message form-message--error"><?php echo esc_html(hireai_field('form_invalid', $is_en ? 'Please provide a valid name, email, and message.' : '请填写正确的姓名、邮箱与需求描述。', $page_id)); ?></div>
					<?php elseif ($sent === 'error') : ?>
						<div class="form-message form-message--error"><?php echo esc_html(hireai_field('form_error', $is_en ? 'Something went wrong. Please retry or email us directly.' : '发送失败，请稍后重试或直接邮件联系我们。', $page_id)); ?></div>
					<?php endif; ?>
					<div class="form-actions">
						<button class="btn btn-solid" type="submit"><?php echo esc_html($submit_label); ?> <?php echo hireai_svg('arrow', 14); ?></button>
					</div>
				</form>
			</div>

			<div class="contact-side">
				<a class="contact-card" href="mailto:<?php echo esc_attr($email); ?>">
					<span class="contact-card__icon"><?php echo hireai_svg('mail', 26); ?></span>
					<h3><?php echo esc_html($is_en ? 'Direct Channel' : '直接邮箱'); ?></h3>
					<span class="contact-card__value"><?php echo esc_html($email); ?></span>
				</a>

				<div class="contact-card contact-qr">
					<h3><?php echo esc_html($is_en ? 'WeChat Connect' : '微信联系'); ?></h3>
					<div class="contact-qr__image">
						<?php if ($qr) : ?>
							<img src="<?php echo esc_url($qr); ?>" alt="WeChat QR">
						<?php else : ?>
							<span class="media-placeholder"><?php echo esc_html($is_en ? 'WeChat' : '微信'); ?></span>
						<?php endif; ?>
					</div>
					<p><?php echo esc_html($wechat); ?></p>
				</div>

				<div class="contact-address">
					<h3><?php echo esc_html($is_en ? 'Global Headquarters' : '总部地址'); ?></h3>
					<p><?php echo nl2br(esc_html($address)); ?></p>
					<?php if ($map_url) : ?>
						<a class="btn btn-ghost" href="<?php echo esc_url($map_url); ?>"><?php echo esc_html($map_label); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
