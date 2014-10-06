<?php include($tpl_common_dir . 'action_confirm.tpl'); ?>

<div class="row">
	<div class="col-sm-12 col-lg-12">
		<ul class="content-nav">
			<li>
				<a class="actionitem" title="<?php echo $button_insert; ?>" href="<?php echo $insert; ?>"><i
							class="fa fa-plus-circle fa-lg"></i></a>
			</li>

			<?php if (!empty ($form_language_switch)) { ?>
				<li>
					<?php echo $form_language_switch; ?>
				</li>
			<?php } ?>
			<?php if (!empty ($help_url)) { ?>
				<li>
					<div class="help_element">
						<a href="<?php echo $help_url; ?>" target="new">
							<i class="fa fa-question-circle fa-lg"></i>
						</a></div>
				</li>
			<?php } ?>
		</ul>
	</div>
</div>




<div class="row">
	<div class="col-sm-12 col-lg-12">
		<div class="panel panel-default">
			<div class="panel-body">
		<?php echo $listing_grid; ?>
			</div>
		</div>
	</div>
</div>

