<?php
$guest_data = $this->session->data['guest'];
?>

<div id="pay_error_container">
    <?php if ($info) { ?>
		<div class="info alert alert-info"><i class="fa fa fa-check fa-fw"></i> <?php echo $info; ?></div>
    <?php } ?>
    <?php if ($error) { ?>
		<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation fa-fw"></i> <?php echo $error; ?>
		</div>
    <?php } ?>
</div>

<fieldset>
	<form id="<?php echo $pay_form['form_open']->name; ?>"
		  action="<?php echo $pay_form['form_open']->action; ?>"
		  class="validate-creditcard"
		  method="post">
        <?php
        if (count($all_addresses) == 1) {
            $readonly = ' readonly ';
        }

        //do we show payment details yet? Show only if shipping selected
        if ($show_payment == false) {
            ?>
			<div class="row">
				<div class="form-group col-xxs-12 text-center">
					<label class="h5 text-uppercase"><?php echo $fast_checkout_text_select_delivery; ?></label>
				</div>
			</div>
            <?php
        }
        ?>

        <?php if ($this->cart->hasShipping()) { ?>
		<div class="row">
			<div class="form-group <?php if ($show_payment) {
                echo "col-xxs-12 col-xs-6";
            } else {
                echo "col-xxs-12";
            } ?>">
				<b><?php echo $fast_checkout_text_shipping_address; ?>:</b>
				<div class="left-inner-addon">
					<i class="fa fa-home" id="delivery_icon"></i>
                    <?php if ($guest_data['shipping']) {
                        $address = $this->customer->getFormattedAddress($guest_data['shipping'],
                            $guest_data['shipping']['address_format']);
                        ?>
						<a href="<?php echo $edit_address_url; ?>&type=shipping" class="address_edit"><i
									class="fa fa-edit"></i></a>
						<div class="well">
							<b><?php echo $fast_checkout_text_shipping_address; ?>:</b> <br/>
                            <?php echo $address; ?>
						</div>
                    <?php } else { ?>
						<select data-placeholder="" class="form-control input-lg" id="shipping_address_id"
								name="shipping_address_id" <?php echo $readonly; ?>>
							<option disabled><?php echo $fast_checkout_text_shipping_address; ?>:</option>
							<option disabled></option>
                            <?php
                            if (count($all_addresses)) {
                                foreach ($all_addresses as $addr) {
                                    $current = '';
                                    if ($addr['address_id'] == $csession['shipping_address_id']) {
                                        $current = ' selected ';
                                    }
                                    $address = $this->customer->getFormattedAddress($addr, $addr['address_format']);
                                    $lines = explode("<br />", $address);
                                    echo '<option value="'.$addr['address_id'].'" '.$current.'>'.$lines[0].', '
                                        .$lines[1].'...</option>';
                                    for ($i = 0; $i <= count($lines); $i++) {
                                        echo '<option disabled>&nbsp;&nbsp;&nbsp;'.$lines[$i].'</option>';
                                    }
                                }
                            }
                            ?>
						</select>
						<div class="select_arrow"><i class="fa fa-angle-double-down"></i></div>
                    <?php } ?>
				</div>
                <?php
                if (count($all_addresses)) { ?>
					<div class="shipping_address_details"></div>
                    <?php
                } ?>
			</div>

            <?php
            $readonly = '';
            if (count($csession['shipping_methods']) == 1) {
                $readonly = ' readonly ';
            }
            ?>

            <?php } //eof if product has shipping ?>

            <?php
            if ($show_payment == true){
            if ($need_payment_address) { ?>
			<div class="form-group col-xxs-12 col-xs-6">
				<b><?php echo $fast_checkout_text_payment_address; ?>:</b>
				<div class="left-inner-addon">
					<i class="fa fa-bank"></i>
                    <?php if ($guest_data) {
                        $address = $this->customer->getFormattedAddress($guest_data, $guest_data['address_format']);
                        ?>
						<a href="<?php echo $edit_address_url; ?>&type=payment" class="address_edit"><i
									class="fa fa-edit"></i></a>
						<div class="well">
							<b><?php echo $fast_checkout_text_payment_address; ?>:</b> <br/>
                            <?php echo $address; ?>
						</div>
                    <?php } else { ?>
						<select data-placeholder="" class="form-control input-lg" id="payment_address_id"
								name="payment_address_id" <?php echo $readonly; ?>>
							<option disabled><?php echo $fast_checkout_text_payment_address; ?>:</option>
							<option disabled></option>
                            <?php
                            if (count($all_addresses)) {
                                foreach ($all_addresses as $addr) {
                                    $current = '';
                                    if ($addr['address_id'] == $csession['payment_address_id']) {
                                        $current = ' selected ';
                                    }
                                    $address = $this->customer->getFormattedAddress($addr, $addr['address_format']);
                                    $lines = explode("<br />", $address);
                                    echo '<option value="'.$addr['address_id'].'" '.$current.'>'.$lines[0].', '
                                        .$lines[1].'...</option>';
                                    for ($i = 0; $i <= count($lines); $i++) {
                                        echo '<option disabled>&nbsp;&nbsp;&nbsp;'.$lines[$i].'</option>';
                                    }
                                }
                            }
                            ?>
						</select>
						<div class="select_arrow"><i class="fa fa-angle-double-down"></i></div>
                    <?php } ?>
				</div>
                <?php
                if (count($all_addresses)) { ?>
					<div class="payment_address_details"></div>
                    <?php
                } ?>
				<input name="cc_owner" type="hidden" value="<?php echo $customer_name; ?>">
			</div>
		</div>
    <?php } else { ?>
		<div class="row">
			<div class="form-group col-xxs-12">
				<div class="left-inner-addon">
					<i class="fa fa-user"></i>
					<input class="form-control input-lg" placeholder="Your Name" name="cc_owner" type="text"
						   value="<?php echo $customer_name; ?>">
				</div>
			</div>
		</div>
    <?php }
    } ?>


        <?php
        if ($this->cart->hasShipping()) {
            $readonly = '';
            if (count($csession['shipping_methods']) == 1) {
                $readonly = ' readonly ';
            }
            ?>
			<div class="row">
				<div class="form-group col-xxs-12">
					<div class="left-inner-addon">
						<i class="fa fa-truck"></i>
						<select data-placeholder="" class="form-control input-lg" id="shipping_method"
								name="shipping_method" <?php echo $readonly; ?>>
                            <?php
                            if (count($csession['shipping_methods'])) {
                                if (!$csession['shipping_method']) {
                                    //no shipping yet selected
                                    echo '<option value="" selected>- '.$fast_checkout_text_select_shipping_method
                                        .' -</option>';
                                }
                                foreach ($csession['shipping_methods'] as $shp_key => $shmd) {
                                    if ($shmd['error']) {
                                        $text = $shmd['title'].': '.$shmd['error'];
                                        echo '<option disabled title="'.$text.'">'.$text.'</option>';
                                    } elseif (!$shmd['error'] && is_array($shmd['quote'])) {
                                        foreach ($shmd['quote'] as $q_key => $quote) {
                                            $current = '';
                                            if ($quote['id'] == $csession['shipping_method']['id']) {
                                                $current = ' selected ';
                                            }

                                            echo '<option value="'.$shp_key.'.'.$q_key.'" '.$current.'>'.
                                                $quote['title'].': '.$quote['text']
                                                .'</option>';
                                        }
                                    }
                                }
                            }
                            ?>
						</select>
						<div class="select_arrow"><i class="fa fa-angle-double-down"></i></div>
					</div>
				</div>
			</div>
        <?php } //eof if product has shipping ?>

        <?php
        //if not all required fields are selected, do not show payment fields
        if ($show_payment == true){
         ?>

		<div class="row">
			<div class="form-group col-xxs-12">
				<div class="left-inner-addon">
					<i class="fa fa-envelope"></i>
					<input class="form-control input-lg"
						   placeholder="Your Email"
						   id="cc_email"
						   name="cc_email"
						   type="text"
						   value="<?php echo $customer_email; ?>" <?php if ($loggedin) {
                        echo 'readonly';
                    } ?>>
				</div>
			</div>
		</div>

        <?php if ($require_telephone) { ?>
			<div class="row">
				<div class="form-group col-xxs-12">
					<div class="left-inner-addon">
						<i class="fa fa-phone"></i>
						<input id="cc_telephone"
							   class="form-control input-lg"
							   placeholder="<?php echo $fast_checkout_text_telephone_placeholder; ?>"
							   name="cc_telephone"
							   type="text"
							   value="<?php echo $customer_telephone; ?>">
					</div>
				</div>
			</div>
        <?php } ?>

        <?php if ($enabled_coupon) { ?>
			<div class="row">
				<div class="form-group col-xxs-12">
					<div class="left-inner-addon">
						<i class="fa fa-ticket"></i>
						<div class="input-group">
							<input id="coupon_code"
								   class="form-control input-lg"
								   placeholder="<?php echo $fast_checkout_text_coupon_code; ?>"
								   name="coupon_code"
								   type="text"
								   value="<?php echo $csession['coupon']; ?>"
                                <?php if ($csession['coupon']) {
                                    echo "disabled";
                                } ?>
							>
							<span class="input-group-btn">
						<?php if ($csession['coupon']) { ?>
							<button class="btn btn-default btn-lg btn-remove-coupon" type="button">
							<i class="fa fa-trash fa-fw"></i> <span
										class="hidden-xxs"><?php echo $fast_checkout_text_remove; ?></span>
						  </button>
                        <?php } else { ?>
							<button class="btn btn-default btn-lg btn-coupon" type="button">
							<i class="fa fa-check fa-fw"></i> <span
										class="hidden-xxs"><?php echo $fast_checkout_text_apply; ?></span>
						  </button>
                        <?php } ?>
						</span>
						</div>
					</div>
				</div>
			</div>
        <?php } ?>
		<input type="hidden" name="account_credit" value="0">
	</form>
    <?php if ($loggedin && $balance_enough === true) { ?>
		<ul class="nav nav-tabs payment_tabs" role="tablist">
			<li class="paywith">
                <?php echo $fast_checkout_text_pay_with; ?>
			</li>
            <?php if ($payment_available === true) { ?>
				<li class="active">
					<a href="#credit_card" role="tab" data-toggle="tab">
						<i class="fa fa-credit-card fa-fw"></i>
						<span class="hidden-xxs"><?php echo $fast_checkout_text_credit_card; ?></span>
					</a>
				</li>
            <?php } ?>
            <?php if ($payment_available !== true){ ?>
			<li class="active">
                <?php } else { ?>
			<li>
                <?php } ?>
				<a href="#account_credit" role="tab" data-toggle="tab">
					<i class="fa fa-money fa-fw"></i>
					<span class="hidden-xxs"><?php echo sprintf($fast_checkout_text_account_credit,
                            $balance_value) ?></span>
					<span class="visible-xxs"><?php echo $balance_value; ?></span>
				</a>
			</li>
		</ul>
		<div class="tab-content">
            <?php if ($payment_available === true) { ?>
				<div class="tab-pane fade in active" id="credit_card"></div>
				<div class="tab-pane fade text-center" id="account_credit"></div>
            <?php } else { ?>
				<div class="tab-pane fade in active text-center" id="account_credit"></div>
            <?php } ?>
		</div>
    <?php } else {
        if ($payment_available === true) {
            include($this->templateResource('/template/responses/checkout/payment_select.tpl'));
        }
    } ?>
    <?php } ?>
</fieldset>


<?php if ($loggedin && $balance_enough === true) { ?>
    <?php if ($payment_available === true) { ?>
		<div id="hidden_credit_card" style="display: none;">
			<h5 class="text-center"><?php echo $fast_checkout_text_select_payment; ?>:</h5>
            <?php include($this->templateResource('/template/responses/checkout/payment_select.tpl')) ?>
		</div>
    <?php } ?>
	<div id="hidden_account_credit" style="display: none;">
		<h5 class="text-center"><?php echo $fast_checkout_text_your_balance; ?>:</h5>
		<h2><?php echo $balance_value; ?> <i class="fa fa-money fa-fw"></i></h2>
		<div class="row">
			<div class="form-group col-xxs-12">
				<button class="credit-pay-btn btn btn-primary btn-lg btn-block"
						data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing ...">
                    <?php echo sprintf($fast_checkout_text_pay_from_balance, $total_string); ?>
				</button>
			</div>
		</div>
	</div>
<?php } ?>

<script type="text/javascript">
	getUrlParams = function(key, value) {
		let searchParams = new URLSearchParams(window.location.search)
		//Remove old value
		if (searchParams.has('cart_key')) {
			searchParams.delete('cart_key')
		}
		if (searchParams.has('rt')) {
			searchParams.delete('rt')
		}
		if (searchParams.has('coupon_code')) {
			searchParams.delete('coupon_code')
		}
		if (searchParams.has('remove_coupon')) {
			searchParams.delete('remove_coupon')
		}

		//Set New Value
		if (searchParams.has(key)) {
			searchParams.set(key, value)
		} else {
			searchParams.append(key, value)
		}
		return searchParams.toString()
	}

	jQuery(document).ready(function () {

		$("#payment_address_id").change(function () {
			let url = '<?php echo $main_url ?>&'+getUrlParams('payment_address_id', $(this).val());
			pageRequest(url);
		});

		$("#shipping_address_id").change(function () {
			let url = '<?php echo $main_url ?>&'+getUrlParams('shipping_address_id', $(this).val());
			pageRequest(url);
		});

		$("#shipping_method").change(function () {
			let url = '<?php echo $main_url ?>&'+getUrlParams('shipping_method', $(this).val());
			pageRequest(url);
		});

		$(".pay-form").on("click", ".btn-coupon", function () {
			var $input = $(this).closest('.input-group').find('input');
			var coupon = $input.val().replace(/\s+/g, '');
			if (!coupon) {
				$.aCCValidator.show_error($(this), '.form-group');
				return false;
			}
			let url = '<?php echo $main_url ?>&'+getUrlParams('coupon_code', coupon);
			pageRequest(url);
		});

		$(".pay-form").on("click", ".btn-remove-coupon", function () {
			let url = '<?php echo $main_url ?>&'+getUrlParams('remove_coupon', true);
			pageRequest(url);
		});

		$(".pay-form").on("click", ".credit-pay-btn", function () {
			var form = $('#PayFrm');
			form.find('input[name="account_credit"]').val(1);

			$('form').unbind("submit"),
				$.ajax({
					url: form.attr('action'),
					type: 'POST',
					dataType: 'html',
					data: form.serialize(),
					success: function (data) {
						$('#fast_checkout_cart').hide().html(data).fadeIn(1000)
					}
				});
				//form.submit();
		});

		$(".pay-form").on("click", ".payment-option", function () {
			if ($(this).hasClass('selected')) {
				return;
			}
			var payment_id = $(this).data('payment-id');
			let url = '<?php echo $main_url ?>&'+getUrlParams('payment_method', payment_id);
			//pageRequest(url);
			var form = $('#PayFrm');
			$('#payment_details').remove();
			$('form').unbind("submit"),
				form.attr('action', url);
			$.ajax({
				url: url,
				type: 'GET',
				dataType: 'html',
				success: function (data) {
					$('#fast_checkout_cart').hide().html(data).fadeIn(1000)
				}
			});
			//form.submit();
		});

		//load first tab
        <?php if ($payment_available === true){ ?>
		$('#credit_card').html($('#hidden_credit_card').html());
        <?php } else { ?>
		$('#account_credit').html($('#hidden_account_credit').html());
        <?php } ?>
		$("a[href='#credit_card']").on('shown.bs.tab', function (e) {
			$('#account_credit').html('');
			$('#credit_card').html($('#hidden_credit_card').html());
		});

		$("a[href='#account_credit']").on('shown.bs.tab', function (e) {
			$('#credit_card').html('');
			$('#account_credit').html($('#hidden_account_credit').html());
		});

		$('form.validate-creditcard #cc_telephone').bind({
			change: function () {
				//check as email is entered
				if (validatePhone($(this).val())) {
					$.aCCValidator.show_success($(this), '.form-group');
				} else {
					$.aCCValidator.show_error($(this), '.form-group');
				}
			},
			blur: function () {
				//check full number as lost focus
				if (validatePhone($(this).val())) {
					$.aCCValidator.show_success($(this), '.form-group');
				} else {
					$.aCCValidator.show_error($(this), '.form-group');
				}
			}
		});

		$('form.validate-creditcard #cc_email').bind({
			change: function () {
				//check as email is entered
				if (validateEmail($(this).val())) {
					$.aCCValidator.show_success($(this), '.form-group');
				} else {
					$.aCCValidator.show_error($(this), '.form-group');
				}
			},
			blur: function () {
				//check full number as lost focus
				if (validateEmail($(this).val())) {
					$.aCCValidator.show_success($(this), '.form-group');
				} else {
					$.aCCValidator.show_error($(this), '.form-group');
				}
			}
		});

		$('form.validate-creditcard #cc_number').bind({
			change: function () {
				//check as number is entered
				$.aCCValidator.precheckCCNumber($(this));
			},
			blur: function () {
				//check full number as lost focus
				$.aCCValidator.checkCCNumber($(this));
			}
		});

		$('form.validate-creditcard #cc_owner').bind({
			change: function () {
				$.aCCValidator.checkCCName($(this), 'reset');
			},
			blur: function () {
				$.aCCValidator.checkCCName($(this));
			}
		});

		$('form.validate-creditcard #cc_expire_date_month').bind({
			change: function () {
				$.aCCValidator.checkExp($(this), 'reset');
			},
			blur: function () {
				$.aCCValidator.checkExp($(this));
			}
		});

		$('form.validate-creditcard #cc_expire_date_year').bind({
			change: function () {
				$.aCCValidator.checkExp($(this), 'reset');
			},
			blur: function () {
				$.aCCValidator.checkExp($(this));
			}
		});

		$('form.validate-creditcard #cc_cvv2').bind({
			change: function () {
				$.aCCValidator.checkCVV($(this), 'reset');
			},
			blur: function () {
				$.aCCValidator.checkCVV($(this));
			}
		});

		getAddressHtml = function(address) {
			let html = ''
			if (typeof address != "undefined") {
				if (address.firstname || address.lasttname) {
					html += address.firstname + ' ' + address.lastname + ' <br/>'
				}
				if (address.company) {
					html += address.company + ' <br/>'
				}
				if (address.address_2) {
					html += address.address_2 + ' <br/>'
				}
				if (address.address_1) {
					html += address.address_1 + ' <br/>'
				}
				if (address.city || address.postcode) {
					html += address.city + ' ' + address.postcode + ' <br/>'
				}
				if (address.zone) {
					html += address.zone + ' <br/>'
				}
				if (address.country) {
					html += address.country
				}
                <?php if ($address_edit_base_url) { ?>
				html += '<div class="address_edit_link"><a href="<?php echo $address_edit_base_url; ?>'+address.address_id+'"><i class="fa fa-edit"></i></a></div>'
                <?php } ?>
			}
			return html
		}

		updateShippingAddressDisplay = function () {
			let addresses = JSON.parse(atob('<?php echo base64_encode(json_encode($all_addresses)); ?>'))
			let shipping_address_id = $("#shipping_address_id").val()
			let address = addresses.find((el) => el.address_id == shipping_address_id)

			if (typeof address != "undefined") {
				$('.shipping_address_details').hide().html(getAddressHtml(address)).fadeIn(1000);
			}
		}

		updatePaymentAddressDisplay = function () {
			let addresses = JSON.parse(atob('<?php echo base64_encode(json_encode($all_addresses)); ?>'))
			let payment_address_id = $("#payment_address_id").val()
			let address = addresses.find((el) => el.address_id == payment_address_id)

			if (typeof address != "undefined") {
				$('.payment_address_details').hide().html(getAddressHtml(address)).fadeIn(1000);
			}
		}

		updateShippingAddressDisplay()
		updatePaymentAddressDisplay()


		$('#PayFrm').on('submit', function (event) {
			alert('Handle!')
			return false;

		})


	});

</script>
