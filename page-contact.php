<?php
/**
 * Template Name: 联系我们 · Contact
 *
 * Aurelian luxury system — Contact page
 *
 * Structure (per Stitch design):
 *   1. Hero (centered kicker + bilingual title + italic intro)
 *   2. Decorative banner image
 *   3. Inquiry form + Direct Concierge (7 / 5 grid)
 *   4. CTA: "Ready to Redefine Humanity?"
 *
 * Data sources:
 *   - ACF group_page_contact (registered in functions.php)
 *   - hireai_handle_contact() form processor (admin-post.php?action=hireai_contact)
 *
 * Style:
 *   - Uses existing hp-* utility classes (hp-form-input, hp-form-textarea, hp-form-label,
 *     hp-btn, hp-btn--primary) + a small <style> block for layout-specific rules.
 *   - No Tailwind, no CDN, no template-string residue.
 *
 * @package HireAI
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

/* --------------------------------------------------------------------
 * 1. LANGUAGE
 * -------------------------------------------------------------------- */
$suffix = function_exists('hireai_lang_suffix') ? hireai_lang_suffix() : '';
$is_en  = ($suffix === '_en');

/* --------------------------------------------------------------------
 * 2. HERO FIELDS  (ACF group_page_contact)
 * -------------------------------------------------------------------- */
$hero_kicker   = hireai_field('header_kicker',  'THE ATELIER');
// Bilingual title — admin controls each half independently.
$title_zh = function_exists('hireai_field_lang')
    ? hireai_field_lang('header_title', 'zh', '联络')
    : hireai_field('header_title_zh', '联络');
$title_en = function_exists('hireai_field_lang')
    ? hireai_field_lang('header_title', 'en', 'Contact')
    : hireai_field('header_title_en', 'Contact');

$hero_subtitle = hireai_field(
    'header_subtitle',
    $is_en
        ? 'Connect with our concierges to curate your bespoke AI workforce.'
        : '与我们的管家联系，定制属于您的 AI 数字员工。'
);

// Decorative hero image — falls back to hero-home.jpg if ACF has no upload.
$hero_image = function_exists('hireai_default_image')
    ? hireai_default_image('hero-home.jpg')
    : get_stylesheet_directory_uri() . '/assets/img/defaults/hero-home.jpg';

/* --------------------------------------------------------------------
 * 3. DIRECT CONCIERGE FIELDS
 * -------------------------------------------------------------------- */
$contact_email  = hireai_field('contact_email', 'concierge@hireaipeople.com');
$contact_wechat = hireai_field('contact_wechat', 'hireai-official');
$contact_addr   = hireai_field('contact_address', $is_en ? 'Shanghai, China' : '中国 · 上海');
$map_label      = hireai_field('contact_map_label', $is_en ? 'View Map' : '查看地图');
$map_url        = hireai_field('contact_map_url', 'https://uri.amap.com/search?keyword=Shanghai%2C%20China');

// WeChat QR (admin upload) — fallback empty box if not set.
$qr_image = hireai_image('wechat_qr', '');

/* --------------------------------------------------------------------
 * 4. FORM LABELS & MESSAGES
 * -------------------------------------------------------------------- */
$label_name    = hireai_field('form_name_label',    $is_en ? 'Name' : '姓名');
$label_email   = hireai_field('form_email_label',   $is_en ? 'Email' : '邮箱');
$label_msg     = hireai_field('form_message_label', $is_en ? 'Inquiry Details' : '需求描述');
$label_submit  = hireai_field('form_submit_label',  $is_en ? 'Send Inquiry' : '提交咨询');

$msg_success = hireai_field(
    'form_success',
    $is_en
        ? "Your inquiry has been sent. We'll be in touch shortly."
        : '您的咨询已发送，我们将尽快与您联系。'
);
$msg_invalid = hireai_field(
    'form_invalid',
    $is_en
        ? 'Please provide a valid name, email, and message.'
        : '请填写正确的姓名、邮箱与需求描述。'
);
$msg_error = hireai_field(
    'form_error',
    $is_en
        ? 'Something went wrong. Please retry or email us directly.'
        : '发送失败，请稍后重试或直接邮件联系我们。'
);

/* --------------------------------------------------------------------
 * 5. FORM SUBMISSION FEEDBACK (sent=success|invalid|error)
 * -------------------------------------------------------------------- */
$sent_status = isset($_GET['sent']) ? sanitize_text_field(wp_unslash($_GET['sent'])) : '';
$form_action = esc_url(admin_url('admin-post.php'));

/* --------------------------------------------------------------------
 * 6. PAGE-SPECIFIC STYLES
 *    Uses --hp-* variables from style.css. No CDN, no Tailwind.
 * -------------------------------------------------------------------- */
?>
<style>
/* ===== Hero ===== */
.hireai-c-hero{padding:clamp(72px,9vw,128px) clamp(20px,5vw,80px) clamp(40px,5vw,72px);text-align:center;background:var(--hp-surface,#faf9f9);}
.hireai-c-kicker{display:block;font-family:var(--hp-font-body);font-size:var(--fs-label);font-weight:700;letter-spacing:.4em;text-transform:uppercase;color:var(--hp-on-surface-var);margin:0 0 28px;}
.hireai-c-title{margin:0;font-family:var(--hp-font-serif);font-weight:600;font-size:clamp(48px,7vw,88px);line-height:1.05;letter-spacing:-.01em;}
.hireai-c-title__zh{display:inline-block;background:linear-gradient(135deg,#b8862e 0%,#e9c176 45%,#b8862e 100%);-webkit-background-clip:text;background-clip:text;color:transparent;font-weight:700;}
.hireai-c-title__sep{display:inline-block;margin:0 .35em;color:var(--hp-secondary);opacity:.55;font-weight:300;}
.hireai-c-title__en{display:inline-block;background:linear-gradient(135deg,#b8862e 0%,#e9c176 45%,#b8862e 100%);-webkit-background-clip:text;background-clip:text;color:transparent;font-style:italic;font-weight:500;}
.hireai-c-subtitle{margin:28px auto 0;max-width:680px;font-family:var(--font-body-en,'Inter'),sans-serif;font-size:16px;font-style:normal !important;line-height:1.65;color:var(--on-surface,#1a1c1c);}

/* ===== Decorative banner ===== */
.hireai-c-banner{padding:0 clamp(20px,5vw,80px);background:var(--hp-surface,#faf9f9);}
.hireai-c-banner__inner{position:relative;width:100%;max-width:1200px;margin:0 auto;border-radius:var(--hp-radius-md,.75rem);overflow:hidden;border:1px solid rgba(119,90,25,.12);box-shadow:0 30px 80px rgba(0,0,0,.07);}
.hireai-c-banner__inner img{display:block;width:100%;height:auto;object-fit:cover;max-height:480px;}

/* ===== Form section ===== */
.hireai-c-section{padding:clamp(72px,8vw,120px) clamp(20px,5vw,80px);background:var(--hp-surface,#faf9f9);}
.hireai-c-grid{display:grid;grid-template-columns:1fr;gap:clamp(40px,5vw,72px);max-width:1280px;margin:0 auto;}
@media(min-width:1024px){.hireai-c-grid{grid-template-columns:7fr 5fr;align-items:start;}}

/* ----- Form card ----- */
.hireai-c-form-card{background:#fff;border:1px solid rgba(119,90,25,.1);border-radius:var(--hp-radius-md,.75rem);padding:clamp(32px,4vw,56px);box-shadow:0 25px 60px rgba(0,0,0,.05);}
.hireai-c-form-card h2{margin:0 0 36px;font-family:var(--hp-font-serif);font-weight:500;font-size:clamp(24px,2vw,32px);color:var(--hp-on-surface,#1a1c1c);}

/* Honeypot — hidden from real users */
.hireai-c-honeypot{position:absolute !important;left:-10000px;width:1px;height:1px;overflow:hidden;}

.hireai-c-form{display:flex;flex-direction:column;gap:36px;}
.hireai-c-form__row{display:grid;grid-template-columns:1fr;gap:36px;}
@media(min-width:640px){.hireai-c-form__row{grid-template-columns:1fr 1fr;}}

/* Inline alert */
.hireai-c-alert{margin:0 0 28px;padding:14px 20px;border-radius:8px;font-family:var(--hp-font-body);font-size:14px;line-height:1.55;}
.hireai-c-alert--ok{background:rgba(119,90,25,.07);border:1px solid rgba(119,90,25,.25);color:var(--hp-secondary,#775a19);}
.hireai-c-alert--err{background:rgba(186,26,26,.06);border:1px solid rgba(186,26,26,.2);color:#8a1010;}

/* Submit row alignment */
.hireai-c-form__actions{display:flex;justify-content:flex-end;margin-top:8px;padding-top:32px;border-top:1px solid rgba(196,199,199,.5);}

/* ----- Direct Concierge ----- */
.hireai-c-concierge{padding:clamp(24px,3vw,40px) 0;}
.hireai-c-concierge h3{margin:0 0 16px;font-family:var(--hp-font-serif);font-weight:500;font-size:clamp(20px,1.8vw,24px);color:var(--hp-on-surface,#1a1c1c);}
.hireai-c-concierge__desc{margin:0 0 24px;font-family:var(--hp-font-body);font-size:15px;line-height:1.6;color:var(--hp-on-surface-var);}

/* Email link */
.hireai-c-email{display:inline-flex;align-items:center;gap:8px;margin:0 0 36px;padding-bottom:8px;border-bottom:1px solid rgba(119,90,25,.35);font-family:var(--hp-font-body);font-size:15px;color:var(--hp-secondary,#775a19);text-decoration:none;transition:border-color .25s;}
.hireai-c-email:hover{border-bottom-color:var(--hp-secondary);}

/* QR row */
.hireai-c-qr-row{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin:0 0 40px;}
.hireai-c-qr-item{display:flex;flex-direction:column;align-items:center;text-align:center;gap:12px;}
.hireai-c-qr-box{width:128px;height:128px;background:var(--hp-surface-low,#f4f3f3);border:1px solid rgba(196,199,199,.4);border-radius:var(--hp-radius-md,.75rem);display:flex;align-items:center;justify-content:center;padding:8px;overflow:hidden;}
.hireai-c-qr-box img{width:100%;height:100%;object-fit:contain;mix-blend-mode:multiply;opacity:.85;}
.hireai-c-qr-placeholder{font-family:var(--hp-font-body);font-size:11px;color:var(--hp-on-surface-var);opacity:.55;letter-spacing:.1em;}
.hireai-c-qr-label{font-family:var(--hp-font-body);font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--hp-on-surface-var);}

/* Social */
.hireai-c-connect{padding-top:32px;border-top:1px solid rgba(196,199,199,.45);}
.hireai-c-connect h4{margin:0 0 18px;font-family:var(--hp-font-body);font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--hp-on-surface-var);}
.hireai-c-social{display:flex;gap:14px;}
.hireai-c-social a{width:44px;height:44px;border-radius:50%;border:1px solid rgba(119,90,25,.3);display:flex;align-items:center;justify-content:center;font-family:var(--hp-font-body);font-size:12px;font-weight:600;color:var(--hp-secondary,#775a19);text-decoration:none;transition:all .3s;}
.hireai-c-social a:hover{background:var(--hp-secondary,#775a19);color:var(--hp-on-primary,#fff);box-shadow:0 0 18px rgba(119,90,25,.4);}

/* Address */
.hireai-c-addr{margin-top:36px;padding-top:32px;border-top:1px solid rgba(196,199,199,.45);}
.hireai-c-addr h4{margin:0 0 12px;font-family:var(--hp-font-body);font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--hp-on-surface-var);}
.hireai-c-addr p{margin:0 0 14px;font-family:var(--hp-font-body);font-size:14px;line-height:1.6;color:var(--hp-on-surface,#1a1c1c);white-space:pre-line;}
.hireai-c-addr a{font-family:var(--hp-font-body);font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--hp-secondary,#775a19);text-decoration:none;border-bottom:1px solid rgba(119,90,25,.3);padding-bottom:4px;transition:border-color .25s;}
.hireai-c-addr a:hover{border-bottom-color:var(--hp-secondary);}

/* ===== CTA ===== */
.hireai-c-cta{padding:0 clamp(20px,5vw,80px) clamp(72px,9vw,120px);background:var(--hp-surface,#faf9f9);}
.hireai-c-cta__card{position:relative;overflow:hidden;text-align:center;padding:clamp(56px,8vw,96px) clamp(24px,5vw,64px);border-radius:var(--hp-radius-lg,1rem);background:rgba(250,249,249,.72);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(119,90,25,.18);box-shadow:0 30px 80px rgba(0,0,0,.06);}
.hireai-c-cta__card::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at center,rgba(233,193,118,.16) 0%,transparent 60%);pointer-events:none;}
.hireai-c-cta__card > *{position:relative;z-index:1;}
.hireai-c-cta__title{margin:0 0 20px;font-family:var(--hp-font-serif);font-weight:700;font-size:clamp(36px,5vw,72px);line-height:1.1;color:var(--hp-on-surface,#1a1c1c);}
.hireai-c-cta__desc{margin:0 auto 36px;max-width:560px;font-family:var(--hp-font-body);font-size:clamp(15px,1.3vw,18px);line-height:1.6;color:var(--hp-on-surface-var);}
</style>

<main class="hireai-contact">

  <!-- ============================================================
       1. HERO
       ============================================================ -->
  <section class="hireai-c-hero" aria-labelledby="hireai-c-title">
    <p class="hireai-c-kicker"><?php echo esc_html($hero_kicker); ?></p>
    <h1 id="hireai-c-title" class="hireai-c-title">
      <span class="hireai-c-title__zh"><?php echo esc_html($title_zh); ?></span><span class="hireai-c-title__sep" aria-hidden="true"> / </span><span class="hireai-c-title__en"><?php echo esc_html($title_en); ?></span>
    </h1>
    <p class="hireai-c-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
  </section>

  <!-- ============================================================
       2. DECORATIVE BANNER
       ============================================================ -->
  <?php if ($hero_image !== '') : ?>
  <section class="hireai-c-banner" aria-hidden="true">
    <div class="hireai-c-banner__inner">
      <img src="<?php echo esc_url($hero_image); ?>" alt="" loading="lazy" decoding="async">
    </div>
  </section>
  <?php endif; ?>

  <!-- ============================================================
       3. INQUIRY FORM + DIRECT CONCIERGE  (7 / 5 grid)
       ============================================================ -->
  <section class="hireai-c-section" aria-label="<?php echo esc_attr($is_en ? 'Contact form' : '联系表单'); ?>">
    <div class="hireai-c-grid">

      <!-- LEFT: Inquiry Form -->
      <div class="hireai-c-form-card">
        <h2><?php echo esc_html($is_en ? 'Inquiry Form' : '咨询表单'); ?></h2>

        <?php if ($sent_status === 'success') : ?>
          <div class="hireai-c-alert hireai-c-alert--ok" role="status">
            <?php echo esc_html($msg_success); ?>
          </div>
        <?php elseif ($sent_status === 'invalid') : ?>
          <div class="hireai-c-alert hireai-c-alert--err" role="alert">
            <?php echo esc_html($msg_invalid); ?>
          </div>
        <?php elseif ($sent_status === 'error') : ?>
          <div class="hireai-c-alert hireai-c-alert--err" role="alert">
            <?php echo esc_html($msg_error); ?>
          </div>
        <?php endif; ?>

        <form class="hireai-c-form" action="<?php echo $form_action; ?>" method="post">
          <input type="hidden" name="action" value="hireai_contact">
          <?php wp_nonce_field('hireai_contact', 'hireai_nonce'); ?>

          <!-- Honeypot: real users never fill this. -->
          <input type="text"
                 name="website"
                 value=""
                 class="hireai-c-honeypot"
                 tabindex="-1"
                 autocomplete="off"
                 aria-hidden="true">

          <div class="hireai-c-form__row">
            <div class="hp-form-group">
              <label class="hp-form-label" for="hireai-c-name">
                <?php echo esc_html($label_name); ?> <span>(姓名)</span>
              </label>
              <input id="hireai-c-name"
                     class="hp-form-input"
                     type="text"
                     name="name"
                     placeholder="<?php echo esc_attr($is_en ? 'Your full name' : '您的姓名'); ?>"
                     required
                     autocomplete="name">
            </div>
            <div class="hp-form-group">
              <label class="hp-form-label" for="hireai-c-email">
                <?php echo esc_html($label_email); ?> <span>(邮箱)</span>
              </label>
              <input id="hireai-c-email"
                     class="hp-form-input"
                     type="email"
                     name="email"
                     placeholder="<?php echo esc_attr('your@email.com'); ?>"
                     required
                     autocomplete="email">
            </div>
          </div>

          <div class="hireai-c-form__row">
            <div class="hp-form-group">
              <label class="hp-form-label" for="hireai-c-phone">PHONE (电话)</label>
              <input id="hireai-c-phone"
                     class="hp-form-input"
                     type="tel"
                     name="phone"
                     placeholder="+1..."
                     autocomplete="tel">
            </div>
            <div class="hp-form-group">
              <label class="hp-form-label" for="hireai-c-wechat">WECHAT (微信)</label>
              <input id="hireai-c-wechat"
                     class="hp-form-input"
                     type="text"
                     name="wechat"
                     placeholder="WeChat ID"
                     autocomplete="off">
            </div>
          </div>

          <div class="hp-form-group">
            <label class="hp-form-label" for="hireai-c-msg">
              <?php echo esc_html($label_msg); ?> <span>(需求描述)</span>
            </label>
            <textarea id="hireai-c-msg"
                      class="hp-form-textarea"
                      name="message"
                      rows="4"
                      placeholder="<?php echo esc_attr($is_en ? 'Tell us about your digital human needs…' : '告诉我们您的数字员工需求…'); ?>"
                      required></textarea>
          </div>

          <div class="hireai-c-form__actions">
            <button class="hp-btn hp-btn--primary" type="submit">
              <?php echo esc_html($label_submit); ?> / 提交
            </button>
          </div>
        </form>
      </div>

      <!-- RIGHT: Direct Concierge -->
      <aside class="hireai-c-concierge" aria-label="<?php echo esc_attr($is_en ? 'Direct concierge' : '专属管家'); ?>">
        <h3>Direct Concierge</h3>
        <p class="hireai-c-concierge__desc">
          <?php echo esc_html($is_en
              ? 'For immediate assistance or bespoke inquiries.'
              : '如有即时需求或定制咨询，请直接联系专属管家。'); ?>
        </p>

        <a class="hireai-c-email" href="mailto:<?php echo esc_attr($contact_email); ?>">
          <span aria-hidden="true">✉</span>
          <?php echo esc_html($contact_email); ?>
        </a>

        <div class="hireai-c-qr-row">
          <div class="hireai-c-qr-item">
            <div class="hireai-c-qr-box">
              <?php if ($qr_image !== '') : ?>
                <img src="<?php echo esc_url($qr_image); ?>"
                     alt="<?php echo esc_attr($is_en ? 'WeChat QR code' : '微信二维码'); ?>"
                     loading="lazy"
                     decoding="async">
              <?php else : ?>
                <span class="hireai-c-qr-placeholder">[QR]</span>
              <?php endif; ?>
            </div>
            <span class="hireai-c-qr-label">WECHAT (微信)</span>
          </div>

          <div class="hireai-c-qr-item">
            <div class="hireai-c-qr-box">
              <span class="hireai-c-qr-placeholder">[QR]</span>
            </div>
            <span class="hireai-c-qr-label">WHATSAPP</span>
          </div>
        </div>

        <div class="hireai-c-connect">
          <h4>Connect</h4>
          <div class="hireai-c-social">
            <a href="#" title="Xiaohongshu" aria-label="Xiaohongshu">小红书</a>
            <a href="#" title="Instagram" aria-label="Instagram">Ins</a>
            <a href="#" title="Facebook" aria-label="Facebook">FB</a>
          </div>
        </div>

        <?php if ($contact_addr !== '') : ?>
        <div class="hireai-c-addr">
          <h4><?php echo esc_html($is_en ? 'Headquarters' : '总部'); ?></h4>
          <p><?php echo nl2br(esc_html($contact_addr)); ?></p>
          <?php if ($map_url !== '') : ?>
            <a href="<?php echo esc_url($map_url); ?>"
               target="_blank" rel="noopener noreferrer">
              <?php echo esc_html($map_label); ?> →
            </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </aside>

    </div>
  </section>

  <!-- ============================================================
       4. CTA  -  Ready to Redefine Humanity?
       ============================================================ -->
  <section class="hireai-c-cta" aria-labelledby="hireai-c-cta-title">
    <div class="hireai-c-cta__card">
      <h2 id="hireai-c-cta-title" class="hireai-c-cta__title">
        <?php echo esc_html($is_en
            ? 'Ready to Redefine Humanity?'
            : '准备好重新定义服务了吗？'); ?>
      </h2>
      <p class="hireai-c-cta__desc">
        <?php echo esc_html($is_en
            ? 'Step into the future of luxury service with our bespoke digital workforce.'
            : '与我们的专属数字员工一同，迈入奢华服务的未来。'); ?>
      </p>
      <a class="hp-btn hp-btn--primary" href="#hireai-c-title">
        <?php echo esc_html($is_en ? 'Begin Consultation' : '开始咨询'); ?>
      </a>
    </div>
  </section>

</main>

<?php
get_footer();
