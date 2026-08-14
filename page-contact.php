<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 聘AI - 联系
 * 参考 aether_ai_light_luxe_4：大标题 + 表单卡 + 直连/微信/HQ 信息卡。
 */
get_header();

$suffix = hireai_lang_suffix();
$email  = hireai_field('contact_email', 'concierge@hireaipeople.com');
$wechat = hireai_field('contact_wechat', 'hireai-official');
$qr_img = hireai_image('wechat_qr');
$map_url  = hireai_field('contact_map_url', 'https://uri.amap.com/search?keyword=Shanghai%2C%20China');
$map_label = hireai_field('contact_map_label', $suffix === '_en' ? 'View Map' : '查看地图');

$sent = isset($_GET['sent']) ? sanitize_key($_GET['sent']) : '';
$success_msg = hireai_field('form_success', $suffix === '_en' ? "Your inquiry has been sent. We'll be in touch shortly." : '您的咨询已发送，我们将尽快与您联系。');
$invalid_msg = hireai_field('form_invalid', $suffix === '_en' ? 'Please provide a valid name, email, and message.' : '请填写正确的姓名、邮箱与需求描述。');
$error_msg   = hireai_field('form_error', $suffix === '_en' ? 'Something went wrong. Please retry or email us directly.' : '发送失败，请稍后重试或直接邮件联系我们。');
?>
<header class="page-hero page-hero--left" data-reveal>
	<span class="label page-hero__kicker"><?php echo esc_html(hireai_field('header_kicker', '联系')); ?></span>
	<h1 class="display-lg page-hero__title"><?php echo esc_html(hireai_field('header_title', $suffix === '_en' ? 'Initiate Contact.' : '发起联络。')); ?></h1>
	<?php if (hireai_field('header_subtitle')) : ?>
		<p class="body-lg page-hero__subtitle"><?php echo esc_html(hireai_field('header_subtitle')); ?></p>
	<?php endif; ?>
</header>

<section class="section" style="padding-top:0;">
	<div class="container">
		<div class="contact-grid contact-grid--luxe">
			<div class="contact-form contact-form--luxe" data-reveal>
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

					<div class="field-row field-row--2">
						<div class="field">
							<input type="text" id="contact-name" name="name" placeholder=" " required>
							<label for="contact-name"><?php echo esc_html(hireai_field('form_name_label', '姓名')); ?></label>
						</div>
						<div class="field">
							<input type="text" id="contact-company" name="company" placeholder=" " autocomplete="organization">
							<label for="contact-company"><?php echo esc_html(hireai_field('form_company_label', $suffix === '_en' ? 'Company Entity' : '公司/机构')); ?></label>
						</div>
					</div>
					<div class="field">
						<input type="email" id="contact-email" name="email" placeholder=" " required>
						<label for="contact-email"><?php echo esc_html(hireai_field('form_email_label', $suffix === '_en' ? 'Secure Email' : '邮箱')); ?></label>
					</div>
					<div class="field">
						<textarea id="contact-message" name="message" placeholder=" " rows="4" required></textarea>
						<label for="contact-message"><?php echo esc_html(hireai_field('form_message_label', $suffix === '_en' ? 'Inquiry Details' : '需求描述')); ?></label>
					</div>

					<div class="contact-form__actions">
						<button type="submit" class="btn btn-primary btn-lg">
							<?php echo esc_html(hireai_field('form_submit_label', $suffix === '_en' ? 'Transmit Message' : '提交咨询')); ?>
							<span aria-hidden="true">→</span>
						</button>
					</div>
				</form>
			</div>

			<div class="contact-info contact-info--cards" data-reveal>
				<article class="contact-card">
					<span class="label contact-card__label"><?php echo esc_html($suffix === '_en' ? 'Direct Channel' : '直连通道'); ?></span>
					<h3 class="contact-card__value"><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></h3>
				</article>

				<article class="contact-card contact-card--qr">
					<span class="label contact-card__label"><?php echo esc_html($suffix === '_en' ? 'WeChat Connect' : '微信连接'); ?></span>
					<?php if ($qr_img) : ?>
						<img class="contact-qr__img" src="<?php echo esc_url($qr_img); ?>" alt="WeChat QR Code">
					<?php else : ?>
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
					<?php endif; ?>
					<p class="contact-card__hint"><?php echo esc_html($suffix === '_en' ? 'Scan for instant bespoke support.' : '扫码获取专属支持。'); ?></p>
				</article>

				<article class="contact-card contact-card--hq">
					<span class="label contact-card__label"><?php echo esc_html($suffix === '_en' ? 'Global Headquarters' : '全球总部'); ?></span>
					<p class="contact-card__address">
						<?php echo nl2br(esc_html(hireai_field('contact_address', $suffix === '_en' ? 'Shanghai, China' : '中国 · 上海'))); ?>
					</p>
					<?php if ($map_url) : ?>
						<a class="contact-card__map" href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener"><?php echo esc_html($map_label); ?></a>
					<?php endif; ?>
				</article>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
