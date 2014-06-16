<?php if (!empty($error['warning'])) { ?>
<div class="warning alert alert-error alert-danger"><?php echo $error['warning']; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<?php echo $summary_form; ?>

<?php echo $product_tabs ?>
<div class="tab-content">

	<div class="panel-heading">
	
			<div class="pull-right">

                <div class="btn-group mr10 toolbar">
                    <?php echo $form_language_switch; ?>
                </div>

			    <div class="btn-group mr10 toolbar">
                    <a class="btn btn-white tooltips" href="<?php echo $clone_url; ?>" data-toggle="tooltip" title="<?php echo $text_clone; ?>" data-original-title="<?php echo $text_clone; ?>">
                    <i class="fa fa-tags"></i>
                    </a>
                    <?php if (!empty ($help_url)) : ?>
                    <a class="btn btn-white tooltips help_element" href="<?php echo $help_url; ?>" target="new" data-toggle="tooltip" title="" data-original-title="Help">
                    <i class="fa fa-question"></i>
                    </a>
                    <?php endif; ?>
			    </div>	
			</div>
		
	</div>

	<?php echo $form['form_open']; ?>
	<div class="panel-body panel-body-nopadding">
		
		<?php foreach ($form['fields'] as $section => $fields) { ?>
		<label class="h4 heading"><?php echo ${'tab_' . $section}; ?></label>         
			<?php foreach ($fields as $name => $field) { ?>
		<div class="form-group">
			<label class="control-label col-sm-3" for="<?php echo $field->element_id; ?>"><?php echo ${'entry_' . $name}; ?></label>
			<div class="input-group afield col-sm-7 <?php echo ($name == 'description' ? 'ml_ckeditor' : '')?>">
				<?php echo $field; ?>
		        <?php if (is_array($error[$name]) && !empty($error[$name][$language_id])) { ?>
		        <div class="field_err"><?php echo $error[$name][$language_id]; ?></div>
		        <?php } else if (!empty($error[$name])) { ?>
		        <div class="field_err"><?php echo $error[$name]; ?></div>
		        <?php } ?>
			</div>
		</div>
			<?php }  ?><!-- <div class="fieldset"> -->
		<?php }  ?>
 		
	</div>
	
	<div class="panel-footer">
		<div class="row">
		   <div class="col-sm-6 col-sm-offset-3">
		     <button class="btn btn-primary">
		     <i class="fa fa-save"></i> <?php echo $form['submit']->text; ?>
		     </button>&nbsp;
		     <a class="btn btn-default" href="<?php echo $cancel; ?>">
		     <i class="fa fa-refresh"></i> <?php echo $form['cancel']->text; ?>
		     </a>
		   </div>
		</div>
	</div>
	</form>
	
</div><!-- <div class="tab-content"> -->


<script type="text/javascript" src="<?php echo $template_dir; ?>javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $template_dir; ?>javascript/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript"><!--

$(document).ready(function () {
    var array = ['#productFrm_price',
        '#productFrm_cost',
        '#productFrm_shipping_price',
        '#productFrm_length',
        '#productFrm_width',
        '#productFrm_height',
        '#productFrm_weight'];

});

$('#productFrm_generate_seo_keyword').click(function(){
	var seo_name = $('#productFrm_product_description\\[name\\]').val().replace('%','');
	$.get('<?php echo $generate_seo_url;?>&seo_name='+seo_name, function(data){
		$('#productFrm_keyword').val(data).change();
	});
});

if (document.getElementById('productFrm_product_description[description]'))
    $('#productFrm_product_description\\[description\\]').parents('.afield').removeClass('mask2');
CKEDITOR.replace('productFrm_product_description[description]',
    {
        filebrowserBrowseUrl:false,
        filebrowserImageBrowseUrl:'<?php echo $rl; ?>',
        filebrowserWindowWidth:'920',
        filebrowserWindowHeight:'520',
        language:'<?php echo $language_code; ?>'
    }
);
//--></script>