<div class="input-group afield col-sm-5">

	<?php
	echo $this->html->buildElement( array(
					'type' => 'phone',
					'name' => 'to',
					'placeholder' => 'place you phone number here'
				));

	 ?>
	<div class="input-group-btn">
			<?php
			echo $this->html->buildElement( array(
					'type' => 'button',
					'name' => 'test_connection',
					'title' => $text_test,
					'text' => $text_test,
					'style' => 'btn btn-info'
				));
				 ?>
		</div>
</div>



<script type="text/javascript">
	<!--

	$('#test_connection').click(function() {
		if($('#editSettings_default_textmarketer_status').attr('data-orgvalue')!='1'){
			error_alert(<?php js_echo($this->language->get('error_turn_extension_on')); ?>);
			return false;
		}
		if($('#to').val().length==0){
			error_alert(<?php js_echo($this->language->get('error_empty_test_phone_number')); ?>);
			return false;
		}

		$.ajax({
			url: '<?php echo $this->html->getSecureUrl('r/extension/default_textmarketer/test'); ?>',
			type: 'GET',
			dataType: 'json',
			data: {to: $('#to').val()},
			beforeSend: function() {
				$('#test_connection').button('loading');
			},
			success: function( response ) {
				$('#test_connection').button('reset');
				if ( response.hasOwnProperty('error') && response.error!=false  ) {
					error_alert( response['message'] );
					return false;
				}
				info_alert( response['message'] );
			},
			complete: function(){
				$('#test_connection').button('reset');
			}
		});
		return false;
	});


-->
</script>
