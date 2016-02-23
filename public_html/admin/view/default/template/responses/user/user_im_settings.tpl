<div class="modal-header">
	<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
	<h4 class="modal-title"><?php echo $text_title ?></h4>
</div>

<div class="tab-content">
	<?php echo $form['form_open']; ?>
	<div class="panel-body panel-body-nopadding">
		<?php
		foreach ($form['fields'] as $name => $field) {?>
		<div class="form-group d" >
			<label class="control-label col-sm-4 col-xs-12" for="<?php echo $field->element_id; ?>"><?php echo ${'entry_im_'.$name}; ?></label>
			<div class="input-group afield col-sm-5 col-xs-12">
				<?php
				if($name=='email'){ ?>
				<div class="input-group-btn">
					<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    Dropdown trigger
					    <span class="caret"></span>
					  </button>
					  <ul class="emails dropdown-menu" aria-labelledby="dLabel">
						  <?php
                        foreach($admin_emails as $e){
                            echo '<li>'.$e.'</li>';
                        }
                        ?>
					  </ul>
				</div>
				<?php }
				echo $field; ?>
			</div>
		</div>
	<?php } ?>
	</div>

	<div class="panel-footer">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3 center">

				<button class="btn btn-primary">
					<i class="fa fa-save"></i> <?php echo $form['submit']->text; ?>
				</button>
				&nbsp;
				<a class="btn btn-default" data-dismiss="modal" href="<?php echo $cancel; ?>">
					<i class="fa fa-refresh"></i> <?php echo $form['cancel']->text; ?>
				</a>

			</div>
		</div>
	</div>

	</form>
</div>

<script type="text/javascript">
$('#imsetFrm').submit(function () {
	save_changes();
	return false;
});

//save an close mode
$('.on_save_close').on('click', function(){
	var $btn = $(this);
	save_changes();
	$btn.closest('.modal').modal('hide');
	return false;
});

function save_changes(){
	$.ajax({
		url: '<?php echo $form['form_open']->action; ?>',
	    type: 'POST',
	    data: $('#imsetFrm').serializeArray(),
	    dataType: 'json',
	    success: function (data) {
			<?php if(!$language_definition_id){?>
			if ($('#im_settings_modal')) {
			    $('#im_settings_modal').modal('hide');
			}
			if ($('#lang_definition_grid')) {
			    $('#lang_definition_grid').trigger("reloadGrid");
			    success_alert(data.result_text);
			}
			<?php }else{ ?>
				success_alert(data.result_text, false, "#im_settings_modal");
			<?php } ?>
	    }
	});
}

	$('.emails.dropdown-menu li').on('click', function(){
		$('#imsetFrm_settingsemail').val($(this).html()).change();
	});

</script>

