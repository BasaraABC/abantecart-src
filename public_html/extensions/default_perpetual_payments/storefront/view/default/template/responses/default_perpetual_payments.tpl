<h4 class="heading4"><?php echo $text_credit_card; ?>:</h4>

<form id="perpetual" class="form-horizontal">

	<div class="form-group ">
		<label class="col-sm-5 control-label"><?php echo $entry_cc_type; ?></label>

		<div class="input-group">
			<?php echo $cc_type; ?>
		</div>
		<span class="help-block"></span>
	</div>
	<div class="form-group ">
		<label class="col-sm-5 control-label"><?php echo $entry_cc_number; ?></label>

		<div class="input-group">
			<?php echo $cc_number; ?>
		</div>
		<span class="help-block"></span>
	</div>

	<div class="form-group ">
		<label class="col-sm-5 control-label"><?php echo $entry_cc_start_date; ?></label>

		<div class="col-sm-7 input-group form-inline">
			<?php echo $cc_start_date_month; ?>&nbsp;<?php echo $cc_start_date_year; ?>
		</div>
		<span class="help-block"></span>
	</div>

	<div class="form-group ">
		<label class="col-sm-5 control-label"><?php echo $entry_cc_expire_date; ?></label>

		<div class="col-sm-7 input-group form-inline"><?php echo $cc_expire_date_month; ?>
			&nbsp;<?php echo $cc_expire_date_year; ?></div>
		<span class="help-block"></span>
	</div>
	<div class="form-group ">
		<label class="col-sm-5 control-label"><?php echo $entry_cc_cvv2; ?></label>

		<div class="input-group">
			<?php echo $cc_cvv2; ?>
		</div>
		<span class="help-block"></span>
	</div>

	<div class="form-group ">
		<label class="col-sm-5 control-label"><?php echo $entry_cc_issue; ?></label>

		<div class="col-sm-7 input-group">
			<?php echo $cc_issue . '&nbsp;' . $text_issue; ?>
		</div>
		<span class="help-block"></span>
	</div>


	<div class="form-group action-buttons center">
    	<div class="input-group">
			<a id="<?php echo $back->name ?>" href="<?php echo $back->href; ?>" class="btn btn-default mr10" title="<?php echo $back->text ?>">
				<i class="fa fa-arrow-left"></i>
				<?php echo $back->text ?>
			</a>
			<button id="<?php echo $submit->name ?>" class="btn btn-orange pull-right" title="<?php echo $submit->text ?>" type="submit">
    		    <i class="fa fa-check"></i>
    		    <?php echo $submit->text; ?>
    		</button>
	    </div>
	</div>


</form>

<script type="text/javascript"><!--


	$(document).ready(function () {
		$('#cc_expire_date_year, #cc_start_date_year ').width('50');
		$('#cc_expire_date_month, #cc_start_date_month').width('85');
	});
	$('#pp_button').click(function () {
		confirmSubmit();
		return false;
	});

	function confirmSubmit() {
		$.ajax({
			type: 'POST',
			url: 'index.php?rt=extension/default_perpetual_payments/send',
			data: $('#perpetual :input'),
			dataType: 'json',
			beforeSend: function () {
				$('#perpetual_button').parent().hide();
				$('.action-buttons').before('<div class="wait alert alert-info"><img src="<?php echo $template_dir; ?>image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			success: function (data) {
				if (data.error) {
					alert(data.error);
					$('.wait').remove();
					$('#perpetual_button').parent().show();
				}
				if (data.success) {
					location = data.success;
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert(textStatus + ' ' + errorThrown);
				$('.wait').remove();
				$('#perpetual_button').parent().show();
			}
		});
	}
	//--></script>
