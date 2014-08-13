<?php 
	$active_object = '';
	$active_library = '';
	if( $action == 'list_object' ) { 
		$active_object = 'class="active"';
	} else {
		$active_library = 'class="active"';
	}
?>	
<div id="rl_container">
	<ul class="nav nav-tabs nav-justified nav-profile">
<?php if(has_value($object_id)) { ?>	
	<li id="object" data-id="<?php echo $resource_id; ?>" data-type="<?php echo $type; ?>" <?php echo $active_object; ?>><a href="#"><strong><?php echo $object_title; ?></strong></a></li>
<?php } ?>	
	<li id="library" data-id="<?php echo $resource_id; ?>" data-type="<?php echo $type; ?>" <?php echo $active_library; ?>><a href="#"><span><?php echo $heading_title; ?></span></a></li>
	</ul>

<div class="tab-content rl-content">

	<ul class="reslibrary-options">
		<li>
			<form id="<?php echo $search_form->name; ?>" name="<?php echo $search_form->name; ?>" action="<?php echo $current_url; ?>" class="form-inline" role="form">
			<div class="form-group">
				<div class="input-group input-group-sm"> 
				<?php echo $rl_types; ?>
				</div>
			</div>         	
			<div class="form-group">
				<div class="input-group input-group-sm">  
				<?php echo $search_field_input; ?>     
				</div>  	    
         	</div>    
			<div class="form-group">
				<button class="btn btn-xs btn-primary btn_search" type="submit"><?php echo $button_go; ?></button>
			</div>         	
        	</form>
        </li>	
        <li>
          <div class="ckbox ckbox-default">
            <input type="checkbox" value="1" id="rl_selectall">
            <label for="rl_selectall">Select All</label>
          </div>
        </li>
        <li>
          <a id="add_resource" data-type="<?php echo $type; ?>" class="btn btn-xs btn-default add_resource tooltips" data-original-title="<?php echo $button_add; ?>"><i class="fa fa-plus"></i></a>
        </li>
<?php if(has_value($active_object)) { ?>        
        <li>
          <a class="itemopt disabled rl_save_multiple" onclick="false;" href=""><i class="fa fa-save"></i></a>
        </li>
<?php } ?>        
        <li>
          <a class="itemopt disabled rl_link_multiple" onclick="false;" href=""><i class="fa fa-link"></i></a>
        </li>
        <li>
          <a class="itemopt disabled rl_delete_multiple" onclick="false;" href="" data-confirmation="delete"><i class="fa fa-trash-o"></i></a>
        </li>
        <?php if( $form_language_switch ) { ?>
        <li>
			    <?php echo $form_language_switch; ?>
        </li>    
        <?php } ?>
        <?php if (!empty ($help_url)) { ?>
        <li>
			<a class="btn btn-white btn-xs tooltips" href="<?php echo $help_url; ?>" target="new" title="" data-original-title="Help">
			<i class="fa fa-question-circle"></i>
			</a>
        </li>    
        <?php } ?>
	</ul>

	<div class="row">
        <div class="col-sm-12">
			<div class="row reslibrary">
			<?php
				//list RL items
				foreach ($rls as $rl) {
					  /*
					  [resource_id] => 
					  [name] => 
					  [title] => 
					  [description] => 
					  [resource_path] => 
					  [resource_code] => 
					  [mapped] => 1
					  [sort_order] => 0
					  [thumbnail_url] => 
					  [url] => 
					  [relative_url] =>
					  [mapped_to_current] =>
					  */					  
			?>  
			  <div class="col-xs-6 col-sm-2 col-md-2 document">
			    <div class="thmb <?php if( $rl['mapped_to_current'] ) { echo "mapped"; } ?>">
			      <div class="ckbox ckbox-default" style="display: none;">
			        <input type="checkbox" value="" id="check_<?php echo $rl['resource_id']; ?>">
			        <label for="check<?php echo $rl['resource_id']; ?>"></label>
			        <?php if( has_value($active_object) ) { 
			        	if (!$rl['sort_order']) {
			        		$rl['sort_order'] = '';
			        	}
			        ?>
			        <div class="rl_sort_order center ml10 mt10" title="sort order">
			        	<input type="text" class="form-control input-sm" placeholder="sort order" size="5"
			        	name="sort_order['<?php echo $rl['resource_id']; ?>']" 
			        	value="<?php echo $rl['sort_order']; ?>" />
			        </div>
			        <?php } ?>			        
			      </div>
			      <div class="btn-group rl-group" style="display: none;">
			          <button data-toggle="dropdown" class="btn btn-default dropdown-toggle rl-toggle" type="button">
			            <span class="caret"></span>
			          </button>
			          <ul role="menu" class="dropdown-menu rl-menu" data-rl-id="<?php echo $rl['resource_id']; ?>">
			            <li><a class="rl_edit" href="#" onclick="false;"><i class="fa fa-pencil"></i> Edit</a></li>
			            <li><a class="rl_link" href="#" onclick="false;"><i class="fa fa-link"></i> Link</a></li>
			            <li><a class="rl_download" href="#" onclick="false;"><i class="fa fa-download"></i> Download</a></li>
			            <li><a class="rl_delete" href="#" onclick="false;" data-confirmation="delete"><i class="fa fa-trash-o"></i> Delete</a></li>
			          </ul>
			      </div>
			      <?php if($rl['resource_code']) { ?>
			      <div class="thmb-prev thmb-icon">
			        <i class="fa fa-code fa-3"></i>
			      </div>
			      <?php } else { ?>
			      <div class="thmb-prev">
			      	<a class="resource_edit tooltips" data-type="<?php echo $type; ?>" data-id="<?php echo $rl['resource_id']; ?>" data-original-title="<?php echo $rl['name']; ?>" href="#">
			        <img alt="" class="img-responsive" src="<?php echo $rl['thumbnail_url']; ?>">
			        </a>
			      </div>
			      <?php } ?>
			      <h5 class="rl-title"><a class="resource_edit tooltips" data-type="<?php echo $type; ?>" data-id="<?php echo $rl['resource_id']; ?>" data-original-title="<?php echo $rl['name']; ?>" href="#"><?php echo $rl['name']; ?></a></h5>
			      <?php if ($rl['created']) { ?>
			      <small class="text-muted">Added: <?php echo $rl['created']; ?></small>
			      <?php } ?>
			    </div>
			  </div>
			 <?php 
			   }
			 ?>
			              
			</div>       
        </div><!-- col-sm-12 -->

	<?php if( $pagination_bootstrap ) { ?>
		<div class="col-sm-12 rl_pagination">
			<div class="row">
			<div class="col-sm-1 form-inline">
			    <div class="form-group">
			    	<div class="input-group input-group-sm dropup tooltips" data-original-title="<?php echo $text_sort_order; ?>"> 
			          <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
			            <i class="fa fa-sort"></i> 
			          </button>
			          <ul role="menu" class="dropdown-menu">
			            <li><a href="<?php echo $no_sort_url; ?>&sort=created&order=DESC"><i class="fa fa-sort-amount-desc"></i> <?php $text_sorting_date_desc; ?></a></li>
			            <li><a href="<?php echo $no_sort_url; ?>&sort=created&order=ASC"><i class="fa fa-sort-amount-asc"></i> <?php $text_sorting_date_asc; ?></a></li>
			            <li><a href="<?php echo $no_sort_url; ?>&sort=name&order=ASC"><i class="fa fa-sort-alpha-asc"></i> <?php $text_sorting_name_asc; ?></a></li>
			            <li><a href="<?php echo $no_sort_url; ?>&sort=name&order=DESC"><i class="fa fa-sort-alpha-desc"></i> <?php $text_sorting_name_desc; ?></a></li>
			            <li><a href="<?php echo $no_sort_url; ?>&sort=sort_order&order=ASC"><i class="fa fa-sort-numeric-asc"></i> <?php $text_sorting_asc; ?></a></li>
			            <li><a href="<?php echo $no_sort_url; ?>&sort=sort_order&order=DESC"><i class="fa fa-sort-numeric-desc"></i> <?php $text_sorting_desc; ?></a></li>
			          </ul>
			    	</div>
			    </div>
		    </div>
		    <div class="col-sm-11 center form-inline pull-right">
		    	<?php echo $pagination_bootstrap; ?>
		    </div>
		    </div>
		</div>
		<?php }?>
						

      </div>
      
	</div>
			
</div><!-- <div class="tab-content"> -->

</div>


<?php if ( 1 == 0 ){ ?>
    <div id="column_left">

        
        <span id="add_resource_msg"></span>
        <a id="done_resource" class="btn_standard"><?php echo $button_done; ?></a>
    </div>
    <div id="column_right_wrapper">
        <ul class="tabs">
            <li>
            	<a class="selected" href="#column_right" id="object"><?php echo $object_title; ?></a>
            </li>
            <li style="float: right; margin-right:15px;">
            	<a href="#column_right" id="library"><?php echo $heading_title; ?></a>
            </li>
        </ul>
        <a href="#" id="button_save_order" class="btn_standard"><?php echo $button_save_order; ?></a>

        <div id="column_right"></div>
    </div>
    <?php if ($mode == ''){ ?>
    <div id="multiactions">
        <?php echo $text_with_selected ?>
        <?php echo $batch_actions ?>&nbsp;<a style="vertical-align: middle; margin-top: -1px;" id="perform_action"
                          class="btn_standard"><?php echo $button_go_actions ?></a>
    </div>
    <?php } ?>
    <div id="pagination"></div>
</div>

<div id="edit_frm" style="display:none;">
    <?php echo $edit_form_open;?>
        <div class="resource_image"></div>
        <table class="files resource-details" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="2" class="sub_title"><?php echo $text_edit_resource ?></td>
            </tr>
            <tr>
                <td></td>
                <td class="message"></td>
            </tr>
            <tr>
                <td><?php echo $text_language; ?></td>
                <td><?php echo $language; ?></td>
            </tr>
            <tr>
                <td><?php echo $text_resource_code; ?></td>
                <td><?php echo $field_resource_code;?></td>
            </tr>
            <tr>
                <td><?php echo $text_name; ?></td>
                <td><?php echo $field_name; ?></td>
            </tr>
            <tr>
                <td><?php echo $text_title; ?></td>
                <td><?php echo $field_title; ?></td>
            </tr>
            <tr>
                <td><?php echo $text_description; ?></td>
                <td><?php echo $field_description; ?></td>
            </tr>
            <tr>
                <td>
                </td>
                <td class="save">
                    <button style="float: right;" type="submit"><img
                        src="<?php echo $template_dir?>image/icons/icon_grid_save.png" alt="<?php echo $button_save; ?>"
                        border="0"/><?php echo $button_save; ?></button>
                </td>
            </tr>
            <tr class="border">
                <td><?php echo $text_mapped_to; ?></td>
                <td class="mapped"></td>
            </tr>
            <tr id="do_map">
                <td><?php echo $text_map; ?></td>
                <td>
                    <?php if ($mode != 'url') { ?>
                    <a class="btn_action resource_unmap" id="map_this"><span class="icon_s_save">&nbsp;<span
                        class="btn_text"><?php echo $button_select_resource; ?></span></span></a>
                    <?php } else { ?>
                    <a class="btn_action resource_unmaps use" rel="0"><span class="icon_s_save">&nbsp;<span
                        class="btn_text"><?php echo $button_select_resource; ?></span></span></a>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="confirm_dialog" title="<?php echo $confirm_title ?>">
    <?php echo $text_confirm ?>
</div>

<div id="resource_details">
    <div class="resource_image"></div>
    <table class="files resource-details" width="510" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="2" align="right"><a class="close">X</a></td>
        </tr>
        <tr>
            <td colspan="2" class="sub_title"><?php echo $text_resource_details; ?></td>
        </tr>
        <tr>
            <td width="130"><?php echo $text_name; ?>
	            <input id="resource_id" type="hidden" name="resource_id" value="<?php echo $resource_id; ?>" />
            </td>
            <td width="380" class="name"></td>
        </tr>
        <tr>
            <td><?php echo $text_description; ?></td>
            <td class="description"></td>
        </tr>
        <tr>
            <td><?php echo $text_mapped_to; ?></td>
            <td class="mapped"></td>
        </tr>
        <tr id="do_map_info">
            <td><?php echo $text_map; ?></td>
            <td>
                <?php if ($mode != 'url') { ?>
                <a class="btn_action resource_unmap" id="map_this_info"><span class="icon_s_save">&nbsp;<span
                    class="btn_text"><?php echo $button_select_resource; ?></span></span></a>
                <?php } else { ?>
                <a class="btn_action resource_unmaps use" id="map_this_info" rel="1"><span
                    class="icon_s_save">&nbsp;<span
                    class="btn_text"><?php echo $button_select_resource; ?></span></span></a>
                <?php } ?>
            </td>
        </tr>
    </table>
</div>


<script type="text/javascript">
function wordwrap(str, int_width, str_break, cut) {
    var i, j, s, r = str.split("\n");
    if (int_width > 0) for (i in r) {
        for (s = r[i], r[i] = ""; s.length > int_width;
             j = cut ? int_width : (j = s.substr(0, int_width).match(/\S*$/)).input.length - j[0].length || int_width,
                 r[i] += s.substr(0, j) + ((s = s.substr(j)).length ? str_break : "")
            );
        r[i] += s;
    }
    return r.join("\n");
}

function cut_str(str, length) {
    if (str.length < length)
        return str;
    return str.substr(0, length) + '...';
}

function querySt(hu, ji) {
    gy = hu.split("&");
    for (i = 0; i < gy.length; i++) {
        ft = gy[i].split("=");
        if (ft[0] == ji) {
            return ft[1];
        }
    }
}

var rl_mode = '<?php echo $mode; ?>';
jQuery(function ($) {

    var type = '<?php echo $type; ?>',
        mode = '<?php echo $mode; ?>',
        types = [],
        object_title = '<?php echo addslashes($object_title); ?>',
        show_object_resource = true,
        page = 1,
        loadedItems,
        selectedItem;

<?php foreach ($types as $t) {
    echo 'types["' . $t['type_name'] . '"] = {
        id: "' . $t['type_id'] . '",
        name: "' . $t['type_name'] . '",
        dir: "' . $t['default_directory'] . '"
    };
    ';
} ?>

    var object_name = '<?php echo $object_name; ?>';
    var object_id = '<?php echo $object_id; ?>';
    var urls = {
        upload:'<?php echo $rl_add; ?>',
        resources:'<?php echo $rl_resources; ?>',
        del:'<?php echo $rl_delete; ?>',
        get_resource:'<?php echo $rl_get_resource; ?>',
        get_preview:'<?php echo $rl_get_preview; ?>',
        update_resource:'<?php echo $rl_update_resource; ?>',
        update_sort_order:'<?php echo $rl_update_sort_order; ?>',
        map:'<?php echo $rl_map; ?>',
        unmap:'<?php echo $rl_unmap; ?>',
        resource:'<?php echo HTTP_DIR_RESOURCE; ?>'
    }
    var errors = {
        error_no_type:'<?php echo $error_no_type; ?>',
        error_required_data:'<?php echo $error_required_data; ?>'
    };
    var text = {
        edit:'<?php echo $button_edit; ?>',
        del:'<?php echo $button_delete; ?>',
        button_select_resource:'<?php echo $button_select_resource; ?>',
        map:'<?php echo $text_map; ?>',
        unmap:'<?php echo $text_unmap; ?>',
        text_success:'<?php echo $text_success; ?>',
        text_no_resources:'<?php echo $text_no_resources; ?>',
        text_none:'<?php echo $text_none; ?>',
        text_cant_delete_title:'<?php echo $text_cant_delete_title; ?>',
        text_cant_delete:'<?php echo $text_cant_delete; ?>',
        button_select:'<?php echo $button_select; ?>',
        view_title:'<?php echo $view_title; ?>',
        text_preview:'<?php echo $text_preview; ?>'
    };

    $("#column_right_wrapper .tabs a").click(function () {
        $("#column_right_wrapper .tabs a").removeClass('selected');
        $(this).addClass('selected');
        if ($(this).attr('id') == 'library') {
            show_object_resource = false;
        } else {
            show_object_resource = true;
        }
        page = 1;
        loadResources();
        return false;
    });

 /*
    $("#confirm_dialog").dialog({
        draggable:false,
        resizable:false,
        autoOpen:false,
        modal:true
    });
*/
    $('#column_right').ajaxError(function (e, jqXHR, settings, exception) {
        hideLoading();
        $(this).html('<div class="error" align="center"><b>' + exception + '</b></div>');
    });

    $('#pagination a').on('click', function () {
		try{
			page = querySt($(this).attr('href'), 'page');
		}catch(e){
			page = $(this).html();
		}

        loadResources();
        return false;
    });

    function showLoading(type) {
        if (type == 'small') {
            $("#column_right_wrapper").prepend('<div class="rl_loading"></div>');
        } else
            $("#column_right").html('').addClass('loading_row');
    }

    function hideLoading(type) {
        if (type == 'small') {
            $("#column_right_wrapper .rl_loading").remove();
        } else
            $("#column_right").removeClass('loading_row');
    }

    $('table.resource-details select[name="language_id"]').on('change', function () {
        var language_id = $(this).val();
        $(this).prev().html($(this).find("option:selected").text());

        var form = $(this).closest('form');
	    $(form).find('.message').html('').removeClass('error').removeClass('success');
	    var resource_id = form.find('input[name="resource_id"]').val();
	    if(resource_id){
		    $.ajax({
			    url: '<?php echo $rl_get_info;?>',
			    type: 'GET',
			    data: {'language_id': language_id, 'resource_id': resource_id},
			    dataType: 'json',
			    success: function(json) {
				    if ( json.error ) {
					    form.find(".message").html( json.error ).addClass('error');
					    return;
				    }

				    form.find('input[name="name"]').val(json.name);
				    form.find('input[name="title"]').val(json.title);
				    form.find('textarea[name="description"]').val(json.description);
			    }
		    });
	    }
    });
    var loadedItems;

    function loadEditForm(json) {

        hideLoading();
        loadedItems = { 0:json };

        var form = $('#edit_frm form').clone();
        $(form).attr('action', urls.update_resource + '&resource_id=' + json.resource_id);
        $('select[name="language_id"]', form).val($('#language_id').val());


        form.find('input[name="resource_id"]').val(json.resource_id);
        form.find('input[name="name"]').val(json.name);
        form.find('input[name="title"]').val(json.title);
        form.find('textarea[name="description"]').val(json.description);

        if (json.resource_code) {
            $('textarea[name="resource_code"]', form).val(json.resource_code);
            $('textarea[name="resource_code"]', form).parents('tr').show();
        } else {
            $('textarea[name="resource_code"]', form).parents('tr').hide();
        }

        var src = '<img src="' + json.thumbnail_url + '" title="' + json.name + '" />';
        if (type == 'image' && json.resource_code) {
            src = json.thumbnail_url;
        }
        $('div.resource_image', form).html(src + '<a target="_preview" href="' + urls.get_preview + '&resource_id=' + json.resource_id + '&language_id=' + json.language_id + '">' + text.text_preview + '</a>');
        $('div.resource_image', form).append('<br><br><span class="resource_path">'+json.relative_url+'</span>');

        if (!json.resource_objects) {
            $('td.mapped', form).html(text.text_none);
        } else {
            var already_mapped = false;
            var html = '';
            $.each(json.resource_objects, function (obj_name, items) {
                if (items.length) {
                    html += '<b>' + obj_name + '</b><br/>';
                    $.each(items, function (index, item) {
                        html += '<a target="_resource_object" href="' + item['url'] + '">' + item['name'] + '</a><br/>';
                        already_mapped = (obj_name == object_name && item['object_id'] == object_id) ? true : already_mapped;
                    });
                    html += '<br/>';
                }
            });
            html = html == '' ? text.text_none : html;
            $('td.mapped', form).html(html);
        }

        $('#column_right').html(form);

        if (mode != 'url') {
            if (already_mapped) {
                $('#do_map').hide();
            } else {
                $('#do_map').show();
                $('#map_this').attr('value', json.resource_id);
                $('#map_this').click(function () {
                    this.checked = false;
                    $.ajax({
                        url:urls.map,
                        type:'POST',
                        data:{'resources[]':$(this).attr('value')},
                        dataType:'json',
                        success:function (json) {
                            parent.$('#dialog').dialog('close');
                            parent.$('#dialog').remove();
                        }
                    });
                });
            }
        }
        $('#multiactions').hide();
        $('#pagination').hide();

    }


    function loadResources() {
        showLoading();
        $('#multiactions').show();
        $('#pagination').show();

        $('#resource_details a.close').click();

        var keyword = $('#rlsearchform input[name="search"]').val();
        if (keyword == '<?php echo $text_search; ?>') {
            keyword = '';
        }

        var resource_data = {
            type:type,
            language_id:$('#language_id').val(),
            keyword:keyword,
            page:page
        }
        //show resource for given object and id
        if (show_object_resource) {
            resource_data.object_name = object_name;
            resource_data.object_id = object_id;
        }
/*
        $.ajax({
            url:urls.resources,
            type:'GET',
            data:resource_data,
            dataType:'json',
            success:function (json) {
                hideLoading();
                $('#column_right').html('');
                var html = '';
                loadedItems = json.items;

                $('#pagination').html(json.pagination);
                $('#pagination div.links').length ? $('#pagination').show() : $('#pagination').hide();

                if (!json.items.length) {
                    //html = '<div class="no_resource">' + text.text_no_resources + '<div><a class="btn_standard add_resource"><?php echo $button_add_resource; ?></a></div></div>';
                    //$('#column_right').html(html);
                    return;
                }

                $(json.items).each(function (index, item) {
                    var src = '<img src="' + item['thumbnail_url'] + '" title="' + item['name'] + '" />';
                    if (type == 'image' && item['resource_code']) {
                        src = item['thumbnail_url'];
                    }
                    html += '<div id="resource' + item['resource_id'] + '" class="resource' + (item['object_name'] == object_name && item['object_id'] == object_id ? ' select' : '') + '" >\
                    <a class="select" href="' + urls.get_resource + '&resource_id=' + item['resource_id'] + '&language_id=' + item['language_id'] + '">' + src + '\</a>\
                    ' + ( show_object_resource && mode != 'url' ? '<div class="sort_order" title="sort order"><input type="text" name="sort_order[' + item['resource_id'] + ']" value="' + (item['sort_order'] ? item['sort_order'] : '') + '" rel="' + index + '"  /></div>' : '') + '\
                    ' + ( mode != 'url' ? '<div class="checkbox"><input type="checkbox" name="resources[]" value="' + item['resource_id'] + '" rel="' + index + '"  /></div>' : '') + '\
                    <br />\
                    <b class="name">' + cut_str(wordwrap(item['name'], 14, '<br/>', true), 32) + '</b>\
                    <div class="buttons">\
                    <a class="edit" rel="' + item['resource_id'] + '" href="' + urls.get_resource + '&resource_id=' + item['resource_id'] + '"><img src="<?php echo $template_dir?>image/icons/icon_s_edit.png" alt="' + text.edit + '" border="0" /></a>\
                    ' + ( item['mapped'] > 0 ? '' : '<a class="delete" rel="resource' + item['resource_id'] + '" href="' + urls.del + '&resource_id=' + item['resource_id'] + '"><img src="<?php echo $template_dir?>image/icons/icon_s_delete.png" alt="' + text.del + '" border="0" /></a>') + '\
                    ' + ( mode == 'url' ? '<a class="use" rel="' + index + '" >' + text.button_select_resource + '</a>' : '' ) + '\
                    </div>\
                    </div>';
                });
                html += '<div class="clr_both" style="height:30px"></div>';
                $('#column_right').html(html);


            }
        });
*/

    }

    $('#rlsearchform').submit(function () {
        page = 1;
        loadResources();
    });
    $('#rlsearchform a.btn_search').click(function () {
        page = 1;
        loadResources();
        return false;
    });
    $('#rlsearchform input').keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
            page = 1;
            loadResources();
        }
    });
    $('#language_id').change(function () {
        page = 1;
        $('#language_id').prev().html($(this).find("option:selected").text());
        loadResources();
        return false;
    });

    $('#column_right input[name^=sort_order]').on('keyup', function () {
        $('#button_save_order').show();
    });

    $('#button_save_order').click(function () {
        $.ajax({
            url:urls.update_sort_order + '&object_name=' + object_name + '&object_id=' + object_id,
            type:'POST',
            data:$('input[name^=sort_order]').serializeArray(),
            dataType:'json',
            success:function (json) {
                loadResources();
            }
        });
        $(this).hide();
        return false;
    });

	//Resource selected, choose action to assign resource to object
    $('#column_right a.use, #map_this_info').on('click', function () {

        var item = loadedItems[$(this).attr('rel')];

        if (window.opener) {
            placeInCKE(item);
            window.self.close();
            return;
        }

        parent.selectResource = item;

        if (item['resource_code']) {
            parent.$('#' + parent.selectField).html(item['resource_code']);
            //new! add resource id to parrent for better mapping of single items
            //see example in SF menu
			var rl_id_field = parent.$('input[name="' + parent.selectField + '_rl_id' + '"]');
            if (rl_id_field.length) {
            	rl_id_field.val(item['resource_id']);
            } 
           
            parent.$('input[name="' + parent.selectField + '"]').val(item['resource_code']);
        } else {
            parent.loadSingle(type, parent.wrapper_id, item['resource_id'], parent.selectField);
            //change hidden element and mark ad changed 
            parent.$('input[name="' + parent.selectField + '"]').val(types[type].dir + item['resource_path']).addClass('afield changed');
            parent.$('form').prop('changed', 'true');
        }

        parent.$('#dialog').dialog('close');
        parent.$('#dialog').remove();
    });

    $('#column_right a.delete').on('click', function () {

        var that = this;

        $("#confirm_dialog").dialog('option', 'buttons', {
            "<?php echo $button_delete ?>":function () {
                $.ajax({
                    url:$(that).attr('href'),
                    type:'POST',
                    data:{ type:type },
                    dataType:'json',
                    success:function (json) {
                        if (json) {
                            $('#' + $(that).attr('rel')).remove();
                        }
                    }
                });
                $(this).dialog("close");
                $('#resource_details a.close').click();
            },
            "<?php echo $button_cancel ?>":function () {
                $(this).dialog("close");
            }
        });
        $("#confirm_dialog").dialog('open');

        return false;
    });


    $('#column_right a.edit').on('click', function () {
        showLoading();
        $.ajax({
            url:$(this).attr('href'),
            type:'GET',
            data:{resource_objects:1, object_name:object_name, type:type},
            dataType:'json',
            success:loadEditForm
        });

        $('#resource_details a.close').click();
        return false;
    });
	$('#multiactions select#actions').aform({triggerChanged: false});
    $('#perform_action').click(function () {
        var url = '';
        switch ($('#multiactions select#actions').val()) {
            case 'map':
                $.ajax({
                    url:urls.map,
                    type:'POST',
                    data:$('input[name^=resources]').serializeArray(),
                    dataType:'json',
                    success:function (json) {
                        $('#object').click();
	                    $('#multiactions select#actions').val('').change();
                    }
                });
                break;
            case 'unmap':
                $.ajax({
                    url:urls.unmap,
                    type:'POST',
                    data:$('input[name^=resources]').serializeArray(),
                    dataType:'json',
                    success:function (json) {
                        loadResources();
	                    $('#multiactions select#actions').val('').change();
                    }
                });
                break;
            case 'delete':
                var cant_delete = false;
                $('input[name^=resources]:checked').each(function (index, item) {
                    if (!$('#resource' + $(item).val() + ' a.delete').length)
                        cant_delete = true;
                });

                if (cant_delete) {
                    $('<div title="' + text.text_cant_delete_title + '">' + text.text_cant_delete + '</div>').dialog(
                        {
                            buttons:{
                                "<?php echo $button_close ?>":function () {
                                    $(this).dialog("close");
                                }
                            }
                        }
                    );
                } else {
                    $("#confirm_dialog").dialog('option', 'buttons', {
                        "<?php echo $button_delete ?>":function () {
                            $.ajax({
                                url:urls.del,
                                type:'POST',
                                data:$('input[name^=resources]').serializeArray(),
                                dataType:'json',
                                success:function (json) {
                                    loadResources();
                                }
                            });
                            $(this).dialog("close");
                        },
                        "<?php echo $button_cancel ?>":function () {
                            $(this).dialog("close");
                        }
                    });
                    $("#confirm_dialog").dialog('open');
                }
	            $('#multiactions select#actions').val('').change();
                break;
            default:
                return;
        }

    });

    $('td.save button').on('click', function () {
        var form = $(this).closest('form');
        form.find(".message").html('').removeClass('error').removeClass('success');
		var language_id = form.find('select').val();
        var error_required_data = false;
        var required_lang_id = null;
        var code = form.find('textarea[name="resource_code"]:visible');
        if (code.length && !$(code).val()) {
            error_required_data = true;
        }
	    if(!form.find('input[name="name"]').val() ) {
                error_required_data = true;
        }

        if (error_required_data) {
            form.find(".message").html(errors.error_required_data + ' - ' + form.find('option:selected').text()).addClass('error');
            return false;
        }


        showLoading('small');
        $.ajax({
            url:form.attr('action'),
            type:'POST',
            data:form.serializeArray(),
            dataType:'json',
            success:function (json) {
                $(form).find('.message').addClass('success').html(text.text_success);
                hideLoading('small');
            }
        });

        return false;
    });

    $('#types a').click(function () {
        type = $(this).attr('href');
        $('#column_left a').removeClass('selected');
        $(this).addClass('selected');

        //clear error msg
        $('#add_resource_msg').html('');

        //load resources
        loadResources();

        return false;
    });

    $('a.add_resource').on('click', function () {
        if (typeof type == 'undefined') {
            $('#add_resource_msg').html(errors.error_no_type);
            return false;
        }

        $('#multiactions').hide();
        $('#pagination').hide();
        $('#resource_details a.close').click();

        var url = urls.upload + '&type=' + type;
        if (show_object_resource) {
            url += '&object_name=' + object_name + '&object_id=' + object_id;
        }
        $('#column_right').html('<iframe src="' + url + '" style="padding:0; margin: 10px auto; display: block; width: 648px; height: 370px;" frameborder="no" scrolling="auto"></iframe>');
        $('#column_right iframe').load(function (e) {
            try {
                var error_data = $.parseJSON($(this).contents().find('body').html());
            } catch (e) {
                var error_data = null;
            }
            if (error_data && error_data.error_code) {
                parent.$('#dialog').dialog('close');
                parent.httpError(error_data);
            }
        });
    });

    $('#done_resource').click(function () {
    <?php if ($mode == 'url'){ ?>
        var item = null;

        if ($('#resource_details').is(':visible')) {
            item = selectedItem;
        } else if ($('input[name^=resources]:checked').length) {
            item = loadedItems[$('input[name^=resources]:checked').first().attr('rel')];
        }

        if (item) {
            if (window.opener) {
                placeInCKE(item);
                window.self.close();
                return;
            }
            parent.selectResource = item;
            if (item['resource_code']) {
                parent.$('#' + parent.selectField).html(item['resource_code']);
                parent.$('input[name="' + parent.selectField + '"]').val(item['resource_code']);
            } else {
                parent.$('#' + parent.selectField).html('<img src="' + item['thumbnail_url'] + '" title="' + item['name'] + '" />');
                parent.$('input[name="' + parent.selectField + '"]').val(types[type].dir + item['resource_path']);
            }
        }

        <?php } ?>
        parent.$('#dialog').dialog('close');
        parent.$('#dialog').remove();

    });

    function placeInCKE(item) {
        if (window.opener.CKEDITOR) {
            var dialog = window.opener.CKEDITOR.dialog.getCurrent();
            //Note: Return full image path to allow in editor size control
            dialog.getContentElement('info', 'txtUrl').setValue(item.url);
        }
    }

    $('#column_right a.select').on('click', function () {
        showLoading('small');
        $.ajax({
            url:$(this).attr('href'),
            type:'GET',
            data:{resource_objects:1, object_name:object_name},
            dataType:'json',
            success:function (json) {
                hideLoading('small');
                selectedItem = json;
                var src = '<img src="' + json.thumbnail_url + '" title="' + json.name + '" />';
                if (type == 'image' && json.resource_code) {
                    src = json.thumbnail_url;
                }
                $('#resource_details div.resource_image').html(src + '<a target="_preview" href="' + urls.get_preview + '&resource_id=' + json.resource_id + '&language_id=' + json.language_id + '">' + text.text_preview + '</a>');
        		$('#resource_details div.resource_image').append('<br><br><span class="resource_path">'+json.relative_url+'</span>');
                
                $('#resource_details td.name').html(json.name);
                $('#resource_details td.description').html(json.description);

                $('#resource_details').show();

                if (!json.resource_objects) {
                    $('#resource_details td.mapped').html(text.text_none);
                } else {
                    var html = '';
                    var already_mapped = false;
                    $.each(json.resource_objects, function (obj_name, items) {
                        if (items.length) {
                            html += '<b>' + obj_name + '</b><br/>';
                            $.each(items, function (index, item) {
                                html += '<a target="_resource_object" href="' + item['url'] + '">' + item['name'] + '</a><br/>';
                                already_mapped = (obj_name == object_name && item['object_id'] == object_id) ? true : already_mapped;
                            });
                            html += '<br/>';
                        }
                    });
                    $('#resource_details td.mapped').html(html);
                    loadedItems[0] = json;

                    if (mode != 'url') {
                        if (already_mapped) {
                            $('#do_map_info').hide();
                        } else {
                            $('#do_map_info').show();
                            $('#map_this_info').attr('value', json.resource_id);

                            $('#map_this_info').click(function () {
                                this.checked = false;
                                $.ajax({
                                    url:urls.map,
                                    type:'POST',
                                    data:{'resources[]':$(this).attr('value')},
                                    dataType:'json',
                                    success:function (json) {
                                        parent.$('#dialog').dialog('close');
                                        parent.$('#dialog').remove();
                                    }
                                });
                            });
                        }
                    }
                }
            }
        });

        return false;
    });

    $('#resource_details a.close').on('click', function () {
        $('#resource_details').hide();
        return false;
    });

<?php if ($add){ ?>
    $('#add_resource').click();
    <?php }elseif ($update){ ?>
    showLoading();
    $.ajax({
        url:urls.get_resource,
        type:'GET',
        data:{resource_objects:1, object_name:object_name, object_id:object_id, resource_id:'<?php echo $resource_id ?>', type:type},
        dataType:'json',
        success:loadEditForm
    });
    <?php }else{ ?>
    loadResources();
    <?php } ?>
});

$("#language_id").ready(function () {
    $("#language_id").parents('.afield').width($('.search_box').width());
});
$("#language_id").change(function () {
    $("#language_id").parents('.afield').width($('.search_box').width());
});

var $error_dialog = null;
httpError = function (data) {
    if ($error_dialog)
        return;

    $error_dialog = $('<div></div>')
        .html(data.error_text)
        .dialog({
            title:data.error_title,
            modal:true,
            resizable:false,
            buttons:{
                "Close":function () {
                    $(this).dialog("close");
                }
            },
            close:function (e, ui) {
                switch (data.error_code) {
                    //app error
                    case 400 :
                        break;
                    //error login
                    case 401 :
                        parent.window.location.reload();
                        break;
                    //error permission
                    case 402 :
                        break;
                    //error not found
                    case 404 :
                        break;
                }
            }
        });
}

jQuery(function ($) {
    $('<div/>').ajaxError(function (e, jqXHR, settings, exception) {
        var error_data = $.parseJSON(jqXHR.responseText);
        httpError(error_data);
    });
});

</script>
</body>


<?php } // if ( 1 == 0 ){ ?>