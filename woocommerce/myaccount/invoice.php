<?php
/**
 * Invoice requests endpoint — HireAIPeople presentation override.
 *
 * Rendered by hireai_invoice_render_endpoint() through
 * woocommerce_account_invoice_endpoint. The endpoint value is either empty
 * (form + history) or an invoice ID the current user is allowed to read
 * (detail view).
 *
 * @package HireAIPeople
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

$is_en = function_exists('hireai_lang_suffix') && hireai_lang_suffix() === '_en';
$t = static function ($en, $zh) use ($is_en) {
	return $is_en ? $en : $zh;
};

if (!is_user_logged_in()) {
	return;
}

$current_user = wp_get_current_user();

if (function_exists('wc_print_notices')) {
	wc_print_notices();
}

if (!empty($invoice) && $invoice instanceof WP_Post) :
	$invoice_id = (int) $invoice->ID;
	$status     = (string) get_post_meta($invoice_id, 'invoice_status', true);
	$type       = (string) get_post_meta($invoice_id, 'invoice_type', true);
	$order_id   = absint(get_post_meta($invoice_id, 'invoice_order_id', true));
	$admin_note = (string) get_post_meta($invoice_id, 'invoice_admin_note', true);
	$submitted  = $invoice->post_date_gmt && '0000-00-00 00:00:00' !== $invoice->post_date_gmt
		? get_date_from_gmt($invoice->post_date_gmt, 'Y-m-d H:i')
		: get_the_date('Y-m-d H:i', $invoice);

	$detail_rows = [
		$t('Reference', '申请编号')       => '#' . $invoice_id,
		$t('Submitted', '提交时间')       => $submitted,
		$t('Invoice type', '发票类型')    => hireai_invoice_type_label($type, $is_en),
		$t('Company', '公司名称')         => (string) get_post_meta($invoice_id, 'invoice_company', true),
		$t('Tax ID', '税号')              => (string) get_post_meta($invoice_id, 'invoice_tax_id', true),
		$t('Registered address', '注册地址') => (string) get_post_meta($invoice_id, 'invoice_address', true),
		$t('Registered phone', '注册电话')   => (string) get_post_meta($invoice_id, 'invoice_phone', true),
		$t('Bank name', '开户银行')       => (string) get_post_meta($invoice_id, 'invoice_bank', true),
		$t('Bank account', '银行账号')    => (string) get_post_meta($invoice_id, 'invoice_account', true),
		$t('Receiving email', '收票邮箱') => (string) get_post_meta($invoice_id, 'invoice_email', true),
		$t('Related order', '关联订单')   => $order_id ? '#' . $order_id : '',
		$t('Your note', '用户备注')       => (string) get_post_meta($invoice_id, 'invoice_note', true),
	];
	?>

	<div class="hireai-invoice hireai-invoice--detail">
		<p class="hireai-invoice__back">
			<a href="<?php echo esc_url(hireai_invoice_endpoint_url()); ?>">&larr; <?php echo esc_html($t('Back to invoice requests', '返回发票申请')); ?></a>
		</p>

		<h2><?php echo esc_html($t('Invoice request', '发票申请')); ?> #<?php echo esc_html((string) $invoice_id); ?></h2>

		<p class="hireai-invoice__status-line">
			<span class="hireai-invoice__status hireai-invoice__status--<?php echo esc_attr($status !== '' ? $status : 'pending'); ?>">
				<?php echo esc_html(hireai_invoice_status_label($status !== '' ? $status : 'pending', $is_en)); ?>
			</span>
		</p>

		<table class="hireai-invoice__detail">
			<tbody>
				<?php foreach ($detail_rows as $label => $value) : ?>
					<?php if ($value === '') : ?>
						<?php continue; ?>
					<?php endif; ?>
					<tr>
						<th scope="row"><?php echo esc_html($label); ?></th>
						<td><?php echo nl2br(esc_html($value)); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php if ($admin_note !== '') : ?>
			<div class="hireai-invoice__admin-note">
				<h3><?php echo esc_html($t('Note from our team', '处理备注')); ?></h3>
				<p><?php echo nl2br(esc_html($admin_note)); ?></p>
			</div>
		<?php endif; ?>
	</div>

<?php else : ?>
	<?php
	$draft = (isset($draft) && is_array($draft)) ? $draft : [];
	$value = static function ($key, $default = '') use ($draft) {
		return isset($draft[$key]) ? (string) $draft[$key] : $default;
	};

	$selected_type = $value('invoice_type', 'vat_special');
	if (!in_array($selected_type, ['vat_special', 'vat_normal'], true)) {
		$selected_type = 'vat_special';
	}
	$is_special = 'vat_special' === $selected_type;

	$default_email = $value('invoice_email', $current_user->user_email);
	$orders        = hireai_invoice_customer_orders(get_current_user_id());
	$selected_order = absint($value('order_id', '0'));

	$page      = isset($_GET['inv_page']) ? max(1, absint($_GET['inv_page'])) : 1;
	$history   = hireai_invoice_get_user_invoices(get_current_user_id(), $page, 10);
	$base_url  = hireai_invoice_endpoint_url();
	?>

	<div class="hireai-invoice">
		<h2><?php echo esc_html($t('Request an invoice', '申请发票')); ?></h2>
		<p class="hireai-invoice__intro">
			<?php echo esc_html($t(
				'Choose an invoice type, fill in the billing details and submit. We will review the request and email you when its status changes.',
				'请选择发票类型并填写开票信息后提交。我们会在核实后处理，状态变更时会邮件通知您。'
			)); ?>
		</p>

		<div class="hireai-invoice__form-wrap">
			<form class="hireai-invoice__form" action="" method="post">
				<input type="hidden" name="hireai_invoice_action" value="submit" />
				<?php wp_nonce_field('hireai_invoice_submit', 'hireai_invoice_nonce'); ?>

				<p class="woocommerce-form-row form-row form-row-first">
					<label for="hireai-invoice-type"><?php echo esc_html($t('Invoice type', '发票类型')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
					<select id="hireai-invoice-type" name="invoice_type" required aria-required="true">
						<option value="vat_special" <?php selected($selected_type, 'vat_special'); ?>><?php echo esc_html($t('VAT special invoice (electronic)', '电子专票')); ?></option>
						<option value="vat_normal" <?php selected($selected_type, 'vat_normal'); ?>><?php echo esc_html($t('VAT normal invoice (electronic)', '电子普票')); ?></option>
					</select>
				</p>

				<p class="woocommerce-form-row form-row form-row-last">
					<label for="hireai-invoice-company"><?php echo esc_html($t('Company name', '公司名称')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" id="hireai-invoice-company" name="company_name" maxlength="120" value="<?php echo esc_attr($value('company_name')); ?>" required aria-required="true" />
				</p>
				<div class="clear"></div>

				<p class="woocommerce-form-row form-row form-row-wide">
					<label for="hireai-invoice-tax-id"><?php echo esc_html($t('Tax ID / Unified social credit code', '统一社会信用代码 / 纳税人识别号')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" id="hireai-invoice-tax-id" name="tax_id" maxlength="20" pattern="[0-9A-Za-z]{15,20}" value="<?php echo esc_attr($value('tax_id')); ?>" required aria-required="true" aria-describedby="hireai-invoice-tax-id-hint" />
					<span id="hireai-invoice-tax-id-hint" class="hireai-invoice__hint"><?php echo esc_html($t('15–20 letters or digits.', '15–20 位字母或数字。')); ?></span>
				</p>

				<fieldset class="hireai-invoice__special" id="hireai-invoice-special"<?php echo $is_special ? '' : ' hidden'; ?>>
					<legend><?php echo esc_html($t('Special VAT invoice details', '电子专票专用信息')); ?></legend>
					<p class="hireai-invoice__hint"><?php echo esc_html($t('These details are required for a special VAT invoice.', '开具电子专票时以下信息为必填。')); ?></p>

					<p class="woocommerce-form-row form-row form-row-wide">
						<label for="hireai-invoice-address"><?php echo esc_html($t('Registered address', '注册地址')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" id="hireai-invoice-address" name="company_address" value="<?php echo esc_attr($value('company_address')); ?>"<?php echo $is_special ? ' required aria-required="true"' : ''; ?> />
					</p>

					<p class="woocommerce-form-row form-row form-row-first">
						<label for="hireai-invoice-phone"><?php echo esc_html($t('Registered phone', '注册电话')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="tel" class="woocommerce-Input woocommerce-Input--tel input-text" id="hireai-invoice-phone" name="company_phone" value="<?php echo esc_attr($value('company_phone')); ?>"<?php echo $is_special ? ' required aria-required="true"' : ''; ?> />
					</p>

					<p class="woocommerce-form-row form-row form-row-last">
						<label for="hireai-invoice-bank"><?php echo esc_html($t('Bank name', '开户银行')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" id="hireai-invoice-bank" name="bank_name" value="<?php echo esc_attr($value('bank_name')); ?>"<?php echo $is_special ? ' required aria-required="true"' : ''; ?> />
					</p>
					<div class="clear"></div>

					<p class="woocommerce-form-row form-row form-row-wide">
						<label for="hireai-invoice-account"><?php echo esc_html($t('Bank account', '银行账号')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" id="hireai-invoice-account" name="bank_account" value="<?php echo esc_attr($value('bank_account')); ?>"<?php echo $is_special ? ' required aria-required="true"' : ''; ?> />
					</p>
				</fieldset>

				<p class="woocommerce-form-row form-row form-row-first">
					<label for="hireai-invoice-email"><?php echo esc_html($t('Receiving email', '收票邮箱')); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" id="hireai-invoice-email" name="invoice_email" value="<?php echo esc_attr($default_email); ?>" required aria-required="true" />
				</p>

				<p class="woocommerce-form-row form-row form-row-last">
					<label for="hireai-invoice-order"><?php echo esc_html($t('Related order (optional)', '关联订单（选填）')); ?></label>
					<select id="hireai-invoice-order" name="order_id">
						<option value="0"><?php echo esc_html($t('— Not related to an order —', '— 不关联订单 —')); ?></option>
						<?php foreach ($orders as $order) : ?>
							<?php
							$order_id = (int) $order->get_id();
							$label    = hireai_invoice_order_label($order);
							?>
							<option value="<?php echo esc_attr((string) $order_id); ?>" <?php selected($selected_order, $order_id); ?>><?php echo esc_html($label); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
				<div class="clear"></div>

				<p class="woocommerce-form-row form-row form-row-wide">
					<label for="hireai-invoice-note"><?php echo esc_html($t('Note (optional)', '备注（选填）')); ?></label>
					<textarea id="hireai-invoice-note" name="note" rows="4" maxlength="500"><?php echo esc_textarea($value('note')); ?></textarea>
				</p>

				<p class="hireai-invoice__actions">
					<button type="submit" class="button woocommerce-Button"><?php echo esc_html($t('Submit request', '提交申请')); ?></button>
				</p>
			</form>
		</div>

		<script>
		(function () {
			var type = document.getElementById('hireai-invoice-type');
			var box = document.getElementById('hireai-invoice-special');
			if (!type || !box) {
				return;
			}
			var fields = box.querySelectorAll('input');
			function sync() {
				var special = type.value === 'vat_special';
				box.hidden = !special;
				for (var i = 0; i < fields.length; i++) {
					fields[i].required = special;
					if (special) {
						fields[i].setAttribute('aria-required', 'true');
					} else {
						fields[i].removeAttribute('aria-required');
					}
				}
			}
			type.addEventListener('change', sync);
			sync();
		}());
		</script>

		<h2 class="hireai-invoice__history-title"><?php echo esc_html($t('Your invoice requests', '我的发票申请')); ?></h2>

		<?php if (empty($history['posts'])) : ?>
			<p class="hireai-invoice__empty"><?php echo esc_html($t('You have not submitted any invoice request yet.', '您还没有提交过发票申请。')); ?></p>
		<?php else : ?>
			<table class="shop_table shop_table_responsive hireai-invoice__table">
				<thead>
					<tr>
						<th scope="col"><?php echo esc_html($t('Submitted', '提交时间')); ?></th>
						<th scope="col"><?php echo esc_html($t('Invoice type', '发票类型')); ?></th>
						<th scope="col"><?php echo esc_html($t('Company', '公司名称')); ?></th>
						<th scope="col"><?php echo esc_html($t('Related order', '关联订单')); ?></th>
						<th scope="col"><?php echo esc_html($t('Status', '状态')); ?></th>
						<th scope="col"><?php echo esc_html($t('Action', '操作')); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($history['posts'] as $row) : ?>
						<?php
						$row_id       = (int) $row->ID;
						$row_status   = (string) get_post_meta($row_id, 'invoice_status', true);
						$row_status   = $row_status !== '' ? $row_status : 'pending';
						$row_type     = (string) get_post_meta($row_id, 'invoice_type', true);
						$row_company  = (string) get_post_meta($row_id, 'invoice_company', true);
						$row_order    = absint(get_post_meta($row_id, 'invoice_order_id', true));
						$row_submitted = $row->post_date_gmt && '0000-00-00 00:00:00' !== $row->post_date_gmt
							? get_date_from_gmt($row->post_date_gmt, 'Y-m-d')
							: get_the_date('Y-m-d', $row);
						?>
						<tr>
							<td data-title="<?php echo esc_attr($t('Submitted', '提交时间')); ?>"><?php echo esc_html($row_submitted); ?></td>
							<td data-title="<?php echo esc_attr($t('Invoice type', '发票类型')); ?>"><?php echo esc_html(hireai_invoice_type_label($row_type, $is_en)); ?></td>
							<td data-title="<?php echo esc_attr($t('Company', '公司名称')); ?>"><?php echo esc_html($row_company); ?></td>
							<td data-title="<?php echo esc_attr($t('Related order', '关联订单')); ?>"><?php echo $row_order ? esc_html('#' . $row_order) : '&mdash;'; ?></td>
							<td data-title="<?php echo esc_attr($t('Status', '状态')); ?>">
								<span class="hireai-invoice__status hireai-invoice__status--<?php echo esc_attr($row_status); ?>"><?php echo esc_html(hireai_invoice_status_label($row_status, $is_en)); ?></span>
							</td>
							<td data-title="<?php echo esc_attr($t('Action', '操作')); ?>" class="hireai-invoice__cell-action">
								<a class="button hireai-invoice__view" href="<?php echo esc_url(hireai_invoice_endpoint_url($row_id)); ?>"><?php echo esc_html($t('View', '查看详情')); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<?php if ($history['pages'] > 1) : ?>
				<nav class="hireai-invoice__pagination" aria-label="<?php echo esc_attr($t('Invoice request pages', '发票申请分页')); ?>">
					<?php if ($page > 1) : ?>
						<a class="button" href="<?php echo esc_url(add_query_arg('inv_page', $page - 1, $base_url)); ?>"><?php echo esc_html($t('Previous', '上一页')); ?></a>
					<?php endif; ?>
					<?php if ($page < $history['pages']) : ?>
						<a class="button" href="<?php echo esc_url(add_query_arg('inv_page', $page + 1, $base_url)); ?>"><?php echo esc_html($t('Next', '下一页')); ?></a>
					<?php endif; ?>
				</nav>
			<?php endif; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>
