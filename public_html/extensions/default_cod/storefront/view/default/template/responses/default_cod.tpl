<div class="form-group action-buttons">
    <div class="col-md-12">
    	<button id="checkout_btn" onclick="confirmSubmit();" class="btn btn-orange pull-right" title="<?php echo $button_confirm->text ?>">
    	    <i class="fa fa-check"></i>
    	    <?php echo $button_confirm->text; ?>
    	</button>
    	<a id="<?php echo $button_back->name ?>" href="<?php echo $back; ?>" class="btn btn-default wmr10" title="<?php echo $button_back->text ?>">
    	    <i class="fa fa-arrow-left"></i>
    	    <?php echo $button_back->text ?>
    	</a>
    </div>
</div>
<script type="text/javascript"><!--
function confirmSubmit() {
	$('body').css('cursor','wait');
	$.ajax({
		type: 'GET',
		url: 'index.php?rt=extension/default_cod/confirm',
		beforeSend: function() {
			$('#checkout_btn').parent().hide();
			$('.action-buttons').before('<div class="wait alert alert-info"><img src="<?php echo $template_dir; ?>image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},		
		success: function() {
			location = '<?php echo $continue; ?>';
		},
		error: function (jqXHR, textStatus, errorThrown) {
			alert(textStatus + ' ' + errorThrown);
			$('.wait').remove();	
			$('#checkout_btn').parent().show();	
		}				
	});
}
//--></script>
