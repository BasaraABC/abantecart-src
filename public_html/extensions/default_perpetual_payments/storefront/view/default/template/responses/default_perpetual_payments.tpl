<b style="margin-bottom: 3px; display: block;"><?php echo $text_credit_card; ?></b>
<div id="perpetual" class="checkout_details">
  <table width="100%">
    <tr>
      <td><?php echo $entry_cc_number; ?></td>
      <td><input type="text" name="cc_number" value="" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_start_date; ?></td>
      <td><select class="input-small" name="cc_start_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select class="input-small" name="cc_start_date_year">
          <?php foreach ($year_valid as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
        <?php echo $text_start_date; ?></td>
    </tr>    
    <tr>
      <td><?php echo $entry_cc_expire_date; ?></td>
      <td><select class="input-small" name="cc_expire_date_month">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select class="input-small" name="cc_expire_date_year">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_cvv2; ?></td>
      <td><input class="input-mini" type="text" name="cc_cvv2" value="" size="3" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_issue; ?></td>
      <td><input class="input-mini" type="text" name="cc_issue" value="" size="1" />
        <?php echo $text_issue; ?></td>
    </tr>
  </table>
</div>

<div class="form-group action-buttons">
   <div class="col-md-12">
   	<button class="btn btn-orange pull-right" title="<?php echo $button_confirm; ?>" onclick="confirmSubmit();" type="submit">
   	    <i class="fa fa-check"></i>
   	    <?php echo $button_confirm; ?>
   	</button>
   	<a  href="<?php echo str_replace('&', '&amp;', $back); ?>" class="btn btn-default mr10" title="<?php echo $button_back; ?>">
   	    <i class="fa fa-arrow-left"></i>
   	    <?php echo $button_back; ?>
   	</a>
    </div>
</div>

<script type="text/javascript"><!--
function confirmSubmit() {
	$.ajax({
		type: 'POST',
		url: 'index.php?rt=extension/default_perpetual_payments/send',
		data: $('#perpetual :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('#perpetual_button').parent().hide();	
			$('.action-buttons').before('<div class="wait alert alert-info"><img src="<?php echo $template_dir; ?>image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		success: function(data) {
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
