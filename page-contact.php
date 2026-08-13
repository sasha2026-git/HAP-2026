<?php
if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 联系
 * 联系页：邮箱 / 微信 / 在线表单（admin-post.php + nonce + wp_mail）
 */
get_header();

$suffix = hireai_lang_suffix();
$email  = hireai_field('contact_email', 'concierge@hireaipeople.com');
$wechat = hireai_field('contact_wechat', 'hireai-official');
$qr_img = hireai_image('wechat_qr');

$sent = isset($_GET['sent']) ? sanitize_key($_GET['sent']) : '';
$success_msg = hireai_field('form_success', $suffix === '_en' ? "Your inquiry has been sent. We'll be in touch shortly." : '您的咨询已发送，我们将尽快与您联系。');
$invalid_msg = hireai_field('form_invalid', $suffix === '_en' ? 'Please provide a valid name, email, and message.' : '请填写正确的姓名、邮箱与需求描述。');
$error_msg   = hireai_field('form_error', $suffix === '_en' ? 'Something went wrong. Please retry or email us directly.' : '发送失败，请稍后重试或直接邮件联系我们。');
?>
<header class="page-hero" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', '联系')); ?></span>
	<h1 class="headline-lg page-hero__title"><?php echo esc_html(hireai_field('header_title', '发起联络')); ?></h1>
	<?php if (hireai_field('header_subtitle')) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('header_subtitle')); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="contact-grid">
			<!-- 联系信息 -->
			<div class="contact-info" data-reveal>
				<div class="contact-info__item">
					<span class="label contact-info__label"><?php echo esc_html($suffix === '_en' ? 'Email' : '邮箱'); ?></span>
					<div class="contact-info__value">
						<a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
					</div>
				</div>
				<div class="contact-info__item">
					<span class="label contact-info__label"><?php echo esc_html($suffix === '_en' ? 'WeChat' : '微信'); ?></span>
					<div class="contact-info__value"><?php echo esc_html($wechat); ?></div>
				</div>
				<?php if ($qr_img) : ?>
					<div class="contact-qr">
						<img class="contact-qr__img" src="<?php echo esc_url($qr_img); ?>" alt="WeChat QR Code">
					</div>
				<?php else : ?>
					<div class="contact-qr">
						<span class="contact-qr__fallback">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
								<rect x="3" y="3" width="7" height="7" rx="1"></rect>
								<rect x="14" y="3" width="7" height="7" rx="1"></rect>
								<rect x="3" y="14" width="7" height="7" rx="1"></rect>
								<line x1="14" y1="14" x2="21" y2="14"></line>
								<line x1="14" y1="17" x2="21" y2="17"></line>
							</svg>
							<?php echo esc_html($wechat); ?>
						</span>
					</div>
				<?php endif; ?>
			</div>

			<!-- 在线表单 -->
			<div class="contact-form" data-reveal>
				<?php if ($sent === 'success') : ?>
					<p class="form-message form-message--success"><?php echo esc_html($success_msg); ?></p>
				<?php elseif ($sent === 'invalid') : ?>
					<p class="form-message form-message--error"><?php echo esc_html($invalid_msg); ?></p>
				<?php elseif ($sent === 'error') : ?>
					<p class="form-message form-message--error"><?php echo esc_html($error_msg); ?></p>
				<?php endif; ?>

				<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="contact-form">
					<input type="hidden" name="action" value="hireai_contact">
					<?php wp_nonce_field('hireai_contact', 'hireai_nonce'); ?>

					<div class="hp-field" aria-hidden="true">
						<label for="website">Website (leave empty)</label>
						<input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
					</div>

					<div class="field">
						<label for="contact-name"><?php echo esc_html(hireai_field('form_name_label', '姓名')); ?></label>
						<input type="text" id="contact-name" name="name" required>
					</div>
					<div class="field">
						<label for="contact-email"><?php echo esc_html(hireai_field('form_email_label', '邮箱')); ?></label>
						<input type="email" id="contact-email" name="email" required>
					</div>
					<div class="field">
						<label for="contact-message"><?php echo esc_html(hireai_field('form_message_label', '需求描述')); ?></label>
						<textarea id="contact-message" name="message" required></textarea>
					</div>

					<button type="submit" class="btn btn-primary btn-lg">
						<?php echo esc_html(hireai_field('form_submit_label', '提交咨询')); ?>
					</button>
				</form>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
