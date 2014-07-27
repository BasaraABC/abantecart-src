jQuery(window).load(function() {
   
   // Page Preloader
   $('#status').fadeOut();
   $('#preloader').delay(100).fadeOut(function(){
      $('body').delay(100).css({'overflow':'visible'});
   });
   
   ajust_content_height();
});


jQuery(document).ready(function() {

	//Process selected menu on page load
    URL = String(document.location);
    var route = getURLVar(URL, 'rt');
    if (!route) {
        $('#menu_dashboard').addClass('active').addClass('nav-active');
    } else {
        part = route.split('/');
        url = part[0];
        if (part[1]) {
            url += '/' + part[1];
        }
        if (part[2]) {
            url += '/' + part[2];
        }
        var link = $('#menu_box a[href*=\'' + url + '&\']');
        if (link.length == 0) {
            var link = $('#menu_box a[href*=\'' + part[0] + '/' + part[1] + '&\']');
        }
        if (link.length) {
            link.parents('li').addClass('active').addClass('nav-active');
            link.parents('.children').css({display: 'block'});
        }
    }
   
   // Toggle Menu top level
   $('.leftpanel .nav-parent.level0 > a').live('click', function() {  
      var parent = $(this).parent();
      var sub = parent.find('> ul');
      // Dropdown works only when leftpanel is not collapsed
      if(!$('body').hasClass('leftpanel-collapsed')) {
         if(sub.is(':visible')) {
            sub.slideUp(200, function(){
               parent.removeClass('nav-active');
               $('.mainpanel').css({height: ''});
               ajust_content_height();
            });
         } else {
            closeVisibleSubMenu();
            parent.addClass('nav-active');
            sub.slideDown(200, function(){
               ajust_content_height();
            });
         }
      }
      return false;
   });

   // Toggle Menu second level   
   $('.children .nav-parent.level1 > a').live('click', function() {
      var parent = $(this).parent();
      var sub = parent.find('ul.child2');
      // Dropdown works only when leftpanel is not collapsed
      if(!$('body').hasClass('leftpanel-collapsed')) {
         if(sub.is(':visible')) {
            sub.slideUp(200, function(){
               parent.removeClass('nav-active');
               $('.mainpanel').css({height: ''});
               ajust_content_height();
            });
         } else {
            parent.addClass('nav-active');
            sub.slideDown(200, function(){
               ajust_content_height();
            });
         }
      }
      return false;
   });
      
   function closeVisibleSubMenu() {
      $('.leftpanel .nav-parent').each(function() {
         var t = $(this);
         if(t.hasClass('nav-active')) {
            t.find('> ul').slideUp(200, function(){
               t.removeClass('nav-active');
            });
         }
      });
   }
   
   bindEvents();
   
   $('.toggle-chat1').toggles({on: false});
      
   // Sparkline
   $('#sidebar-chart').sparkline([4,3,3,1,4,3,2,2,3,10,9,6], {
	  type: 'bar', 
	  height:'30px',
      barColor: '#428BCA'
   });
   
   $('#sidebar-chart2').sparkline([1,3,4,5,4,10,8,5,7,6,9,3], {
	  type: 'bar', 
	  height:'30px',
      barColor: '#D9534F'
   });
   
   $('#sidebar-chart3').sparkline([5,9,3,8,4,10,8,5,7,6,9,3], {
	  type: 'bar', 
	  height:'30px',
      barColor: '#1CAF9A'
   });
   
   $('#sidebar-chart4').sparkline([4,3,3,1,4,3,2,2,3,10,9,6], {
	  type: 'bar', 
	  height:'30px',
      barColor: '#428BCA'
   });
   
   $('#sidebar-chart5').sparkline([1,3,4,5,4,10,8,5,7,6,9,3], {
	  type: 'bar', 
	  height:'30px',
      barColor: '#F0AD4E'
   });
   
   // Panels Controls
   $('.minimize').click(function(){
      var t = $(this);
      var p = t.closest('.panel');
      if(!$(this).hasClass('maximize')) {
         p.find('.panel-body, .panel-footer').slideUp(200);
         t.addClass('maximize');
         t.html('&plus;');
      } else {
         p.find('.panel-body, .panel-footer').slideDown(200);
         t.removeClass('maximize');
         t.html('&minus;');
      }
      return false;
   });
   
   // Add class on mouse pointer hover for both levels
   $('.nav-bracket > li').hover(function(){
      $(this).addClass('nav-hover');
   }, function(){
      $(this).removeClass('nav-hover');
   });
   $('.nav-bracket .child1 > li').hover(function(){
      $(this).addClass('nav-hover');
   }, function(){
      $(this).removeClass('nav-hover');
   });
   
   $('.menutoggle').click(function(){
		if(jQuery.cookie('leftpanel-collapsed')) {
			$.removeCookie("leftpanel-collapsed");
		} else {
			$.cookie('leftpanel-collapsed', 1);
		}   
		var body = $('body');
		var bodypos = body.css('position');
		if(bodypos != 'relative') {
		   if(!body.hasClass('leftpanel-collapsed')) {
		      body.addClass('leftpanel-collapsed');
		      $('.nav-bracket ul').attr('style','');
		      $(this).addClass('menu-collapsed');
		   } else {
		      body.removeClass('leftpanel-collapsed chat-view');
		      $('.nav-bracket li.active ul').css({display: 'block'});
		      $(this).removeClass('menu-collapsed');
		   }
		} else {       
		   if(body.hasClass('leftpanel-show')) {
		      body.removeClass('leftpanel-show');
		   }
		   else {
		      body.addClass('leftpanel-show');
		   }
		   ajust_content_height();         
		}
   });
   
   // Right Side Panel 
   $('#right_side_view').click(function(){
      var body = $('body');
      var bodypos = body.css('position');
      if(bodypos != 'relative') {
         if(!body.hasClass('chat-view')) {
            body.addClass('leftpanel-collapsed chat-view');
            $('.nav-bracket ul').attr('style','');
            $('#right_side_view').addClass('dropdown-toggle');
         } else {
            body.removeClass('chat-view');
            if(!$('.menutoggle').hasClass('menu-collapsed')) {
               $('body').removeClass('leftpanel-collapsed');
               $('.nav-bracket li.active ul').css({display: 'block'});
               $('#right_side_view').removeClass('dropdown-toggle');
            }
         }
      } else {
         if(!body.hasClass('chat-relative-view')) {
            body.addClass('chat-relative-view');
            body.css({left: ''});
         } else {
            body.removeClass('chat-relative-view');   
         }
      }
   });
   
   reposition_topnav();
   reposition_searchform();
   
   jQuery(window).resize(function(){
      if($('body').css('position') == 'relative') {
         $('body').removeClass('leftpanel-collapsed chat-view');
      } else {
         $('body').removeClass('chat-relative-view');         
         $('body').css({left: '', marginRight: ''});
      }
      reposition_searchform();
      reposition_topnav();
   });
   
   function reposition_searchform() {
      if($('.searchform').css('position') == 'relative') {
         $('.searchform').insertBefore('.leftpanelinner .userlogged');
      } else {
         $('.searchform').insertBefore('.header-right');
      }
   }

   function reposition_topnav() {
      if($('.nav-horizontal').length > 0) {
         if($('.nav-horizontal').css('position') == 'relative') {                         
            if($('.leftpanel .nav-bracket').length == 2) {
               $('.nav-horizontal').insertAfter('.nav-bracket:eq(1)');
            } else {
               // only add to bottom if .nav-horizontal is not yet in the left panel
               if($('.leftpanel .nav-horizontal').length == 0)
                  $('.nav-horizontal').appendTo('.leftpanelinner');
            }
            
            $('.nav-horizontal').css({display: 'block'})
                                  .addClass('nav-pills nav-stacked nav-bracket');
            
            $('.nav-horizontal .children').removeClass('dropdown-menu');
            $('.nav-horizontal > li').each(function() { 
               $(this).removeClass('open');
               $(this).find('a').removeAttr('class');
               $(this).find('a').removeAttr('data-toggle');
            });
            if($('.nav-horizontal li:last-child').has('form')) {
               $('.nav-horizontal li:last-child form').addClass('searchform').appendTo('.topnav');
               $('.nav-horizontal li:last-child').hide();
            }
         
         } else {
            if($('.leftpanel .nav-horizontal').length > 0) {
               
               $('.nav-horizontal').removeClass('nav-pills nav-stacked nav-bracket')
                                        .appendTo('.topnav');
               $('.nav-horizontal .children').addClass('dropdown-menu').removeAttr('style');
               $('.nav-horizontal li:last-child').show();
               $('.searchform').removeClass('searchform').appendTo('.nav-horizontal li:last-child .dropdown-menu');
               $('.nav-horizontal > li > a').each(function() {              
                  $(this).parent().removeClass('nav-active');
                  if($(this).parent().find('.dropdown-menu').length > 0) {
                     $(this).attr('class','dropdown-toggle');
                     $(this).attr('data-toggle','dropdown');  
                  }
               });              
            }
         }
      }
   }
   
   //Set cookies for sticky panels
	$('.sticky_header').click(function(){
		if(jQuery.cookie('sticky-header')) {
			$.removeCookie("sticky-header");
	   		$('body').removeClass('stickyheader');			
	   		$('.sticky_header').removeClass('pressed_pin');
		} else {
	   		$('body').addClass('stickyheader');
	   		$.cookie("sticky-header", 1);	
	   		$('.sticky_header').addClass('pressed_pin');
		}
	});
	
	if(jQuery.cookie('sticky-header')) {
		$('body').addClass('stickyheader');
		$('.sticky_header').addClass('pressed_pin');
	}  
   
	$('.sticky_left').click(function(){
		if(jQuery.cookie('sticky-leftpanel')) {
			$.removeCookie("sticky-leftpanel");
	   		$('.leftpanel').removeClass('sticky-leftpanel');		
	   		$('.sticky_left').removeClass('pressed_pin');
		} else {
	   		$('.leftpanel').addClass('sticky-leftpanel');
	   		$.cookie("sticky-leftpanel", 1);	
	   		$('.sticky_left').addClass('pressed_pin');
		}
	});
	if(jQuery.cookie('sticky-leftpanel')) {
		$('.leftpanel').addClass('sticky-leftpanel');
	}   
   
	if(jQuery.cookie('leftpanel-collapsed')) {
		$('body').addClass('leftpanel-collapsed');
		$('.menutoggle').addClass('menu-collapsed');
	}      
	if($('body').hasClass('leftpanel-collapsed')) {
		$('.nav-bracket .children').css({display: ''});
	}      
	$('.dropdown-menu').find('form').click(function (e) {
      e.stopPropagation();
	});

	//adjust main content height 	
	ajust_content_height();

	//edit mode
    $docW = parseInt($(document).width());
    $('.postit_icon').click(function () {
        pos = $(this).siblings('.postit_notes').offset();
        width = $(this).siblings('.postit_notes').width();
        if (parseInt(pos.left + width) > $docW) {
            $(this).siblings('.postit_notes').css('right', '30px');
        }
    });

	/* Loaders */
	$('.dialog_loader').unbind('click');
	$('.dialog_loader').bind('click', function(e) {
		$('<div></div>')
	    .html('<div class="summary_loading"><div>')
	    .dialog({
	        title: "Processing...",
	        modal: true,
	        width: '200px',
	        resizable: false,
	        buttons: {
	              //  "Cancel" : function() { $(this).dialog("close"); }
	        },
	        close: function(e, ui) {
	        }
	    });
		$(".ui-dialog-titlebar").hide();
		$(".summary_loading").show();
	});

	$('.button_loader').unbind('click');
	$('.button_loader').on('click', function(e) {
		$(this).click(function () { return false; });
		$(this).find("span").hide();
		$(this).append('<span class="ajax_loading">Processing…</span>').show();
	});

});

//-----------------------------------------------
// Add events. Function can be reloaded after AJAX responce
//-----------------------------------------------
var bindEvents  = function(){
	//enable delete confirmations
	wrapConfirmDelete();

	// Tooltip
	$('.tooltips').tooltip({ container: 'body'});
   
	// Popover
	$('.popovers').popover();
   
	// Close Button in Panels
	$('.panel .panel-close').click(function(){
      $(this).closest('.panel').fadeOut(200);
      return false;
	});
   
	//Toggles
	$('.toggle').toggles({on: true});      
}

function ajust_content_height() {
   // Adjust contentpanel height
   var docHeight = jQuery(document).height() - $('#footer').height();
   var extra = $('.headerbar').height() + $('.pageheader').height() + 50;
   var leftHeight = $('.leftpanel').height();
   var rightHeight = $('.contentpanel').height() + extra;
   if(docHeight > rightHeight) {
   		$('.contentpanel').css('min-height',docHeight - extra + 'px');
   }
   if(leftHeight > rightHeight) {
		$('.contentpanel').css('min-height',leftHeight - extra + 'px');
   }
}

//-----------------------------------------
// Confirm Actions (delete, uninstall)
//-----------------------------------------
function getURLVar(URL, urlVarName) {
    var urlHalves = String(URL).toLowerCase().split('?');
    var urlVarValue = '';

    if (urlHalves[1]) {
        var urlVars = urlHalves[1].split('&');

        for (var i = 0; i <= (urlVars.length); i++) {
            if (urlVars[i]) {
                var urlVarPair = urlVars[i].split('=');

                if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
                    urlVarValue = decodeURIComponent(urlVarPair[1]);
                }
            }
        }
    }

    return urlVarValue;
}

//-----------------------------------------
// Funtion to show notification
//-----------------------------------------
function sucess_alert( elm, text, autohide) {
	var html = '<div class="success alert alert-success">'+text+'</div>';
	if(autohide) {
		$(elm).html(html).fadeIn(300).delay(2000).fadeOut(500);
	} else {
		$(elm).html(html).fadeIn(300);
	}
}

function error_alert( elm, text, autohide) {
	var html = '<div class="warning alert alert-error alert-danger">'+text+'</div>';
	if(autohide) {
		$(elm).html(html).fadeIn(300).delay(2000).fadeOut(500);
	} else {
		$(elm).html(html).fadeIn(300);
	}
}

function goTo(url, params) {
    location = url + '&' + params;
}

function addBlock(name) {
    block = $('[name=\'' + name + '\']').first()
        .clone();
    $('[name=\'' + name + '\']').last()
        .closest('.section')
        .after(block);
    $('[name=\'' + name + '\']').last()
        .wrap('<div class="section" />');
    $.aform.styleGridForm($('[name=\'' + name + '\']').last());
    $('[name=\'' + name + '\']').last().aform({ triggerChanged:true, showButtons:false });
}

function checkAll(fldName, checked) {
    $field = $('input[name*=\'' + fldName + '\']');
    if (checked) {
        $field.attr('checked', 'checked').parents('.afield').addClass($.aform.defaults.checkedClass);
    } else {
        $field.removeAttr('checked').parents('.afield').removeClass($.aform.defaults.checkedClass);
    }
}


var $error_dialog = null;
httpError = function (data) {
    if ( data.show_dialog != true )
        return;
    if($error_dialog!=null){ return;}
    $error_dialog = $('<div id="error_dialog"></div>')
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
                if (data.reload_page) {
                	window.location.reload();
                }
            }
        });
}

/*
jQuery(function ($) {
    $('<div/>').ajaxError(function (e, jqXHR, settings, exception) {
        var error_data;
        try{
        var error_data = $.parseJSON(jqXHR.responseText);
        }catch(e){
            error_data = {error: true, error_text: jqXHR.statusText};
        }
        httpError(error_data);
    });
});*/


var numberSeparators = {};
function formatPrice(field) {
    numberSeparators = numberSeparators.length==0 ? {decimal:'.', thousand:','} : numberSeparators;
    var pattern = new RegExp(/[^0-9\-\.]+/g);
    var price = field.value.replace(pattern, '');
    field.value = $().number_format(price, { numberOfDecimals:2,
        decimalSeparator:numberSeparators.decimal,
        thousandSeparator:numberSeparators.thousand});
}
function formatQty(field) {
    numberSeparators = numberSeparators.length==0 ? {decimal:'.', thousand:''} : numberSeparators;
    var pattern = new RegExp(/[^0-9\.]+/g);
    var price = field.value.replace(pattern, '');
    field.value = $().number_format(price, { numberOfDecimals:0,
        decimalSeparator:numberSeparators.decimal,
        thousandSeparator:numberSeparators.thousand});
}



//bind event for submit buttons
var formOnExit = function(){
    $('form[data-confirm-exit="true"]').find('.btn').bind('click', function () {
        var $form = $(this).parents('form');
        //reset elemnts to not changed status
        $form.prop('changed', 'submit');
    });
    // prevent submit of form for "quicksave"
    $("form").bind("keypress", function(e) {
        if (e.keyCode == 13){
            if($(document.activeElement)){
                if($(document.activeElement).parents('.changed').length>0){
                        return false;
                }
            }
        }
    });
}


/*
 task run via ajax
 */


var run_task_url, complete_task_url;
var task_fail = false;
var complete_text = ''; // text about task results

$(".task_run").on('click', function () {
    var modal =
        '<div id="task_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
        '<h4 class="modal-title" id="myModalLabel">&nbsp;</h4>' +
        '</div>' +
        '<div class="modal-body"></div>' +
        '</div></div></div>';
    $("body").first().after(modal);
    var options = {"backdrop": "static", 'show': true};
    $('#task_modal').modal(options);

    run_task_url = $(this).attr('data-run-task-url');
    complete_task_url = $(this).attr('data-complete-task-url');
    var send_data = $(this).parents('form').serialize();
    $.ajax({
        url: run_task_url,
        type: 'POST',
        dataType: 'json',
        data: send_data,
        success: runTaskUI,
        error: function (xhr, ajaxOptions, thrownError) {
            var err = $.parseJSON(xhr.responseText);
            if (err.hasOwnProperty("error_text")) {
                runTaskShowError(err.error_text);
            } else {
                runTaskShowError('Error occurred. See error log for details.');
            }
        }
    });


    return false;
});
/**/
var runTaskUI = function (data) {
    if (data.hasOwnProperty("error") && data.error == true) {
        runTaskShowError('Creation of new task failed! Please check error log for details. \n' + data.error_text);
    } else {
        runTaskStepsUI(data.task_details);
    }
}

var runTaskStepsUI = function (task_details) {
    if (task_details.status != '1') {
        runTaskShowError('Cannot to run steps of task "' + task_details.name + '" because status of task is not "scheduled". Current status - ' + task_details.status);

    } else {
        $('#task_modal .modal-body').html('<div class="progress-info"></div><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="width: 1%;">1%</div></div>');

        var steps_cnt = task_details.steps.length;
        var step_num = 1;
        var def_timeout = $.ajaxSetup()['timemout'];
        var stop_task = false;

        for (var k in task_details.steps) {
            if (stop_task == true) {
                break;
            } // interruption

            var step = task_details.steps[k];

            $('div.progress-info').html('processing step #' + step_num);

            var attempts = 3;// set attempts count for fail ajax call (for repeating request)

            while (attempts > 0) { // run each ajax call few times in case when we have unstable commention etc
                var step_ajax = $.ajax({
                    type: "GET",
                    async: false,
                    url: window.location,
                    data: { rt: step.controller,
                        token: getUrlParameter('token'),
                        s: getUrlParameter('s') },
                    dataType: 'json',
                    success: function (data, textStatus, xhr) {
                        //TODO: add check for php-syntax errors (if php-crashed we got HTTP200 and error handler fired)
                        var prc = Math.round(step_num * 100 / steps_cnt);
                        $('div.progress-bar').css('width', prc + '%').html(prc + '%');
                        complete_text += '<div class="alert-success">Step ' + step_num + ': success</div>';
                        step_num++;
                        if (step_num > steps_cnt) { //after last step start post-trigger of task
                            $('div.progress-bar')
                                .removeClass('active, progress-bar-striped')
                                .css('width', '100%')
                                .html('100%');
                            $('div.progress-info').html('Complete');
                            runTaskComplete(task_details.task_id);
                        }
                        attempts = 0; //stop attempts of this task
                    },
                    error: function (xhr, status, error) {
                        //console.log(xhr, status, error);
                        var error_txt;
                        try { //when server response is json formatted string
                            var err = $.parseJSON(xhr.responseText);
                            if (err.hasOwnProperty("error_text")) {
                                error_txt = err.error_text;
                                attempts = 1; //if we got error from task-controller  - interrupt attemps
                            } else {
                                if(xhr.status==200){
                                    error_txt = '('+xhr.responseText+')';
                                }else{
                                    error_txt = 'HTTP-status:' + xhr.status;
                                }
                                error_txt = 'Connection error occurred. ' + error_txt;
                            }
                        } catch (e) {
                            if(xhr.status==200){
                                error_txt = '('+xhr.responseText+')';
                            }else{
                                error_txt = 'HTTP-status:' + xhr.status;
                            }
                            error_txt = 'Connection error occurred. ' + error_txt;
                        }

                        //so.. if all attempts of this step are failed
                        if (attempts == 1) {
                            complete_text += '<div class="alert-danger">Step ' + step_num + ' - failed. ('+ error_txt +')</div>';
                            //check interruption of task on step failure
                            if (step.hasOwnProperty("settings") && step.settings!=null){
                                if (step.settings.hasOwnProperty("interrupt_on_step_fault")) {
                                    if (step.settings.interrupt_on_step_fault == true) {
                                        stop_task = true;
                                        runTaskComplete(task_details.task_id);
                                    }
                                }
                            }
                            task_fail = true;
                            step_num++;
                            //if last step failed
                            if(step_num>steps_cnt){
                                runTaskComplete(task_details.task_id);
                            }
                        }

                        attempts--;
                    }

                });
            }
        }

    }
}

/* run post-trigger */
var runTaskComplete = function (task_id) {
    if(task_fail){
        complete_text += '<div class="alert-danger">Task Failed</div>';
        // replace progressbar by result message
        $('#task_modal .modal-body').html(complete_text);
        complete_text = '';
    }else{
        $.ajax({
            type: "POST",
            async: false,
            url: complete_task_url,
            data: {task_id: task_id },
            datatype: 'json',
            success: function (data) {
                complete_text += '<div class="alert-success">Task Success</div>';
                // replace progressbar by result message
                $('#task_modal .modal-body').html(complete_text);
                complete_text = '';
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var error_txt = '';
                try { //when server response is json formatted string
                    var err = $.parseJSON(xhr.responseText);
                    if (err.hasOwnProperty("error_text")) {
                        runTaskShowError(err.error_text);
                    } else {
                        if(xhr.status==200){
                            error_txt = '('+xhr.responseText+')';
                        }else{
                            error_txt = 'HTTP-status:' + xhr.status;
                        }
                        error_txt = 'Connection error occurred. ' + error_txt;
                        runTaskShowError(error_txt);
                    }
                } catch (e) {
                    if(xhr.status==200){
                        error_txt = '('+xhr.responseText+')';
                    }else{
                        error_txt = 'HTTP-status:' + xhr.status;
                    }
                    error_txt = 'Connection error occurred. ' + error_txt;
                    runTaskShowError(error_txt);
                }
            }
        });
    }
}


var runTaskShowError = function (error_text) {
    $('#task_modal .modal-body').html('<div class="alert alert-danger" role="alert">' + error_text + '</div>');
}


var getUrlParameter = function (sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i in sURLVariables) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
}

