<?php if ( $type == 'password' && $has_value == 'Y' ) : ?>
	<span id="<?php echo $id ?>_confirm_default" class="confirm_default">***********</span>
<?php endif; ?>	
    <input type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="form-control atext <?php echo $style; ?>" value="<?php echo $value ?>" ovalue="<?php echo $value ?>" <?php echo $attr; ?> placeholder="<?php echo $placeholder ?>" />
    
<?php if ( $required == 'Y' || !empty ($help_url) ) { ?>
	<span class="input-group-addon">
	<?php if ( $required == 'Y') { ?> 
		<span class="required">*</span>
	<?php } ?>	

	<?php if ( !empty ($help_url) ) { ?>
	<span class="help_element"><a href="<?php echo $help_url; ?>" target="new"><i class="fa fa-question-circle fa-lg"></i></a></span>
	<?php } ?>	
	</span>
<?php } ?>