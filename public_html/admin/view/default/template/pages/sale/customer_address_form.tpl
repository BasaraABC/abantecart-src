<?php if (!empty($error['warning'])) { ?>
<div class="warning alert alert-error alert-danger"><?php echo $error['warning']; ?></div>
<?php }else if (count(array_keys($error))) {
		foreach ($error as $key => $error_text) { ?>
			<div class="warning alert alert-error alert-danger"><?php echo is_array($error_text) ? implode('<br>',$error_text) : $error_text; ?></div>
<?php 	}
	}
if ($success) { ?>
<div class="success alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<div class="tab-content">
	<div class="panel-heading">
			<div class="pull-right">
			    <div class="btn-group mr10 toolbar">
                    <?php if (!empty ($help_url)) : ?>
                    <a class="btn btn-white tooltips" href="<?php echo $help_url; ?>" target="new" data-toggle="tooltip" title="" data-original-title="Help">
                    <i class="fa fa-question-circle fa-lg"></i>
                    </a>
                    <?php endif; ?>
			    </div>
                <?php echo $form_language_switch; ?>
			</div>

	</div>

	<?php echo $form['form_open']; ?>
	<div class="panel-body panel-body-nopadding">
		<label class="h4 heading" id="<?php echo $section;?>"><?php echo ${'tab_' . $section}; ?></label>
			<?php foreach ($form['fields'] as $name => $field) { ?>
			<?php
				//Logic to cululate fileds width
				$widthcasses = "col-sm-7";
				if ( is_int(stripos($field->style, 'large-field')) ) {
					$widthcasses = "col-sm-7";
				} else if ( is_int(stripos($field->style, 'medium-field')) || is_int(stripos($field->style, 'date')) ) {
					$widthcasses = "col-sm-5";
				} else if ( is_int(stripos($field->style, 'small-field')) || is_int(stripos($field->style, 'btn_switch')) ) {
					$widthcasses = "col-sm-3";
				} else if ( is_int(stripos($field->style, 'tiny-field')) ) {
					$widthcasses = "col-sm-2";
				}
				$widthcasses .= " col-xs-12";
			?>
		<div class="form-group <? if (!empty($error[$name])) { echo "has-error"; } ?>">
			<label class="control-label col-sm-3 col-xs-12" for="<?php echo $field->element_id; ?>"><?php echo ${'entry_' . $name}; ?></label>
			<div class="input-group afield <?php echo $widthcasses; ?> <?php echo ($name == 'description' ? 'ml_ckeditor' : '')?>">
				<?php echo $field; ?>
			</div>
		    <?php if (!empty($error[$name])) { ?>
		    <span class="help-block field_err"><?php echo $error[$name]; ?></span>
		    <?php } ?>
		</div>
			<?php }  ?><!-- <div class="fieldset"> -->
	</div>

	<div class="panel-footer">
		<div class="row">
		   <div class=" center col-sm-6 col-sm-offset-3">
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














































<div class="contentBox">
	<div class="cbox_tl">
		<div class="cbox_tr">
			<div class="cbox_tc">
				<div class="heading icon_title_customer"><?php echo $heading_title; ?></div>
				<div class="heading-tabs">
					<?php if (!empty($tabs)) { ?>
						<?php foreach ($tabs as $tab) { ?>
							<a href="<?php echo $tab['href']; ?>"
							   class="<?php echo $tab['class']; ?>"><span><?php echo $tab['text']; ?></span></a>
						<?php } ?>
					<?php } ?>
				</div>
				<?php echo $this->getHookVar('extension_tabs'); ?>

				<div class="toolbar">
					<?php if (!empty ($help_url)) : ?>
						<div class="help_element"><a href="<?php echo $help_url; ?>" target="new"><img
										src="<?php echo $template_dir; ?>image/icons/help.png"/></a></div>
					<?php endif; ?>
					<?php echo $form_language_switch; ?>
					<div class="heading"><?php echo $balance; ?></div>
					<div class="buttons">
						<?php echo $button_orders_count; ?>
						<?php echo $button_actas; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="cbox_cl">
		<div class="cbox_cr">
			<div class="cbox_cc">

				<?php echo $form['form_open']; ?>
				<div class="fieldset">
					<div class="heading"><?php echo $form_title; ?></div>
					<div class="top_left">
						<div class="top_right">
							<div class="top_mid"></div>
						</div>
					</div>
					<div class="cont_left">
						<div class="cont_right">
							<div class="cont_mid">

								<div style="display: inline-block; width: 100%;">
									<div id="vtabs" class="vtabs"><a tab="#tab_general"><?php echo $tab_general; ?></a>
										<?php $address_row = 1; ?>
										<?php foreach ($addresses as $address) { ?>
											<a id="address_<?php echo $address_row; ?>"
											   tab="#tab_address_<?php echo $address_row; ?>"
											   onclick="wrapAddress(<?php echo $address_row; ?>)"><?php echo $tab_address . ' ' . $address_row; ?>
												<span onclick="rmAddress('<?php echo $address_row; ?>')" class="remove">&nbsp;</span></a>
											<?php $address_row++; ?>
										<?php } ?>
										<span id="address_add" onclick="addAddress();" class="add"
											  style="float: right; margin-right: 14px; font-size: 13px; font-weight: bold;">Add&nbsp;<?php echo $tab_address ?></span>
									</div>
									<div id="form">
										<div id="tab_general" class="vtabs_page">
											<table class="form">
												<?php foreach ($form['fields'] as $name => $field) { ?>
													<tr>
														<td><?php echo ${'entry_' . $name}; ?></td>
														<td id="payment_<?php echo $name; ?>">
															<?php echo $field; ?>
															<?php if (!empty($error[$name])) { ?>
																<div class="field_err"><?php echo $error[$name]; ?></div>
															<?php } ?>
														</td>
													</tr>
												<?php }  ?>
											</table>
										</div>
										<?php $address_row = 1; ?>
										<?php foreach ($addresses as $key => $address) { ?>
											<div id="tab_address_<?php echo $address_row; ?>" class="vtabs_page">
												<table class="form">
													<tr>
														<td><?php echo $entry_firstname; ?></td>
														<td><input class="no-save" type="text"
																   name="addresses[<?php echo $address_row; ?>][firstname]"
																   value="<?php echo $address['firstname']; ?>"/><span
																	class="required">*</span>
															<?php if ($error[$key]['firstname']) { ?>
																<span class="error"><?php echo $error[$key]['firstname']; ?></span>
															<?php } ?></td>
													</tr>
													<tr>
														<td><?php echo $entry_lastname; ?></td>
														<td><input class="no-save" type="text"
																   name="addresses[<?php echo $address_row; ?>][lastname]"
																   value="<?php echo $address['lastname']; ?>"/><span
																	class="required">*</span>
															<?php if ($error[$key]['lastname']) { ?>
																<span class="error"><?php echo $error[$key]['lastname']; ?></span>
															<?php } ?></td>
													</tr>
													<tr>
														<td><?php echo $entry_company; ?></td>
														<td><input class="no-save" type="text"
																   name="addresses[<?php echo $address_row; ?>][company]"
																   value="<?php echo $address['company']; ?>"/></td>
													</tr>
													<tr>
														<td><?php echo $entry_address_1; ?></td>
														<td><input class="no-save" type="text"
																   name="addresses[<?php echo $address_row; ?>][address_1]"
																   value="<?php echo $address['address_1']; ?>"/><span
																	class="required">*</span>
															<?php if ($error[$key]['address_1']) { ?>
																<span class="error"><?php echo $error[$key]['address_1']; ?></span>
															<?php } ?></td>
													</tr>
													<tr>
														<td><?php echo $entry_address_2; ?></td>
														<td><input class="no-save" type="text"
																   name="addresses[<?php echo $address_row; ?>][address_2]"
																   value="<?php echo $address['address_2']; ?>"/></td>
													</tr>
													<tr>
														<td><?php echo $entry_city; ?></td>
														<td><input class="no-save" type="text"
																   name="addresses[<?php echo $address_row; ?>][city]"
																   value="<?php echo $address['city']; ?>"/><span
																	class="required">*</span>
															<?php if ($error[$key]['city']) { ?>
																<span class="error"><?php echo $error[$key]['city']; ?></span>
															<?php } ?></td>
													</tr>
													<tr>
														<td><?php echo $entry_postcode; ?></td>
														<td><input class="no-save" type="text"
																   name="addresses[<?php echo $address_row; ?>][postcode]"
																   value="<?php echo $address['postcode']; ?>"/></td>
													</tr>
													<tr>
														<td><?php echo $entry_country; ?></td>
														<td><select class="no-save"
																	name="addresses[<?php echo $address_row; ?>][country_id]"
																	onchange="getZones('zone_select_<?php echo $address_row; ?>', this.value, <?php echo isset($address['zone_id']) ? $address['zone_id'] : 0; ?>);">
																<option value="FALSE"><?php echo $text_select; ?></option>
																<?php foreach ($countries as $country) { ?>
																	<?php if ($country['country_id'] == $address['country_id']) { ?>
																		<option value="<?php echo $country['country_id']; ?>"
																				selected="selected"><?php echo $country['name']; ?></option>
																	<?php } else { ?>
																		<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
																	<?php } ?>
																<?php } ?>
															</select>
															<?php if ($error[$key]['country_id']) { ?>
																<span class="error"><?php echo $error[$key]['country_id']; ?></span>
															<?php } ?></td>
													</tr>
													<tr>
														<td><?php echo $entry_zone; ?></td>
														<td><select class="no-save"
																	name="addresses[<?php echo $address_row; ?>][zone_id]"
																	id="zone_select_<?php echo $address_row; ?>">
															</select>
															<?php if ($error[$key]['zone_id']) { ?>
																<span class="error"><?php echo $error[$key]['zone_id']; ?></span>
															<?php } ?></td>
													</tr>
												</table>
												<script type="text/javascript"><!--
													getZones('zone_select_<?php echo $address_row; ?>', <?php echo $address['country_id']; ?>, <?php echo $address['zone_id']; ?>);
													//--></script>
											</div>
											<?php $address_row++; ?>
										<?php } ?>
									</div>
								</div>

							</div>
						</div>
					</div>
					<div class="bottom_left">
						<div class="bottom_right">
							<div class="bottom_mid"></div>
						</div>
					</div>
				</div>
				<div class="buttons align_center">
					<button type="submit" class="btn_standard"><?php echo $form['submit']; ?></button>
					<a class="btn_standard" href="<?php echo $cancel; ?>"><?php echo $form['cancel']; ?></a>
				</div>
				</form>

			</div>
		</div>
	</div>
	<div class="cbox_bl">
		<div class="cbox_br">
			<div class="cbox_bc"></div>
		</div>
	</div>
</div>


<script type="text/javascript"><!--

	function getZones(id, country_id, zone_id) {
		if (!zone_id) {
			zone_id = 0;
		}

		$.ajax(
				{
					url: '<?php echo $common_zone; ?>&country_id=' + country_id + '&zone_id=' + zone_id,
					type: 'GET',
					dataType: 'json',
					success: function (data) {
						result = data;
						showZones(id, data);
					}
				});
	}

	function showZones(id, data) {
		var options = '';

		$.each(data['options'], function (i, opt) {
			options += '<option value="' + i + '"';
			if (opt.selected) {
				options += 'selected="selected"';
			}
			options += '>' + opt.value + '</option>'
		});

		var selectObj = $('#' + id);

		selectObj.html(options);
		var selected_name = $('#' + id + ' :selected').text();

		selectObj.parent().find('span').text(selected_name);

	}
//--></script>

<script type="text/javascript"><!--
	var address_row = <?php echo $address_row; ?>;
	var wrapped = [];


	function addAddress() {
		html = '<div id="tab_address_' + address_row + '" class="vtabs_page">';
		html += '<table class="form">';
		html += '<tr>';
		html += '<td><?php echo $entry_firstname; ?></td>';
		html += '<td><input type="text" name="addresses[' + address_row + '][firstname]" value="" /><span class="required">*</span></td>';
		html += '</tr>';
		html += '<tr>';
		html += '<td><?php echo $entry_lastname; ?></td>';
		html += '<td><input type="text" name="addresses[' + address_row + '][lastname]" value="" /><span class="required">*</span></td>';
		html += '</tr>';
		html += '<tr>';
		html += '<td><?php echo $entry_company; ?></td>';
		html += '<td><input type="text" name="addresses[' + address_row + '][company]" value="" /></td>';
		html += '</tr>';
		html += '<tr>';
		html += '<td><?php echo $entry_address_1; ?></td>';
		html += '<td><input type="text" name="addresses[' + address_row + '][address_1]" value="" /><span class="required">*</span></td>';
		html += '</tr>';
		html += '<tr>';
		html += '<td><?php echo $entry_address_2; ?></td>';
		html += '<td><input type="text" name="addresses[' + address_row + '][address_2]" value="" /></td>';
		html += '</tr>';
		html += '<tr>';
		html += '<td><?php echo $entry_city; ?></td>';
		html += '<td><input type="text" name="addresses[' + address_row + '][city]" value="" /><span class="required">*</span></td>';
		html += '</tr>';
		html += '<tr>';
		html += '<td><?php echo $entry_postcode; ?></td>';
		html += '<td><input type="text" name="addresses[' + address_row + '][postcode]" value="" /></td>';
		html += '</tr>';
		html += '<td><?php echo $entry_country; ?></td>';
		html += '<td>';
		html += '<select name="addresses[' + address_row + '][country_id]" onchange="getZones(\'zone_select_' + address_row + '\', this.value);" >';
		html += '<option value="0"><?php echo $text_select; ?></option>';
		<?php foreach ($countries as $country) { ?>
		html += '<option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
		<?php } ?>
		html += '</select>';
		html += '</td>';
		html += '</tr>';
		html += '<tr>';
		html += '<td><?php echo $entry_zone; ?></td>';
		html += '<td>';
		html += '<select name="addresses[' + address_row + '][zone_id]" id="zone_select_' + address_row + '"><option value="0"><?php echo $text_none; ?></option></select>';
		html += '</td>';
		html += '</tr>';
		html += '</table>';
		html += '</div>';

		$('#form').append(html);
		$("input, textarea, select, .scrollbox", '#tab_address_' + address_row).each(function () {
			$.aform.styleGridForm(this);
		});

		$("input, textarea, select, .scrollbox", '#tab_address_' + address_row).aform({
			triggerChanged: false
		});

		$('#address_add').before('<a id="address_' + address_row + '" tab="#tab_address_' + address_row + '"><?php echo $tab_address; ?> ' + address_row + '<span onclick="$(\'#vtabs a:first\').trigger(\'click\'); $(\'#address_' + address_row + '\').remove(); $(\'#tab_address_' + address_row + '\').remove();" class="remove">&nbsp;</span></a>');
		$.tabs('.vtabs a', address_row);
		$('#address_' + address_row).trigger('click');

		address_row++;
	}

	function rmAddress(address_row) {
		$('#vtabs a:first').trigger('click');
		$('#address_' + address_row).remove();
		$('#tab_address_' + address_row).remove();
	}
	function wrapAddress(address_row) {
		if (!wrapped[address_row]) {
			$('input, textarea, select, .scrollbox', '#tab_address_' + address_row).each(function () {
				$.aform.styleGridForm(this);
			});

			$("input, textarea, select, .scrollbox", '#tab_address_' + address_row).aform({
				triggerChanged: false
			});
			$('.aform').show();

			wrapped[address_row] = true;
		}
	}

	jQuery(function () {
		$.tabs('.vtabs a');
		$('.aform').show();
		$('.aselect, select').width('200px');
	});

	//--></script>