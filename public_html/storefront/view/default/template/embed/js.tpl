<?php
/*

 embed code for test

 text about your store<br />
 --------------------------------------------<br />
 <script src="http://your_domain.com/public_html/index.php?rt=r/embed/js" defer type="text/javascript"></script>
 <div class="abantecart-widget-container" data-url="http://your_domain.com/public_html/index.php"
		data-css-url="http://your_domain.com/public_html/storefront/view/default/stylesheet/embed.css" >
	 <div class="abantecart_product" data-product-id="90">
		 <div class="abantecart_image">&nbsp;</div>
		 <div class="abantecart_name">&nbsp;</div>
		 <div class="abantecart_options">&nbsp;</div>
		 <div class="abantecart_price">&nbsp;</div>
		 <div class="abantecart_qty">&nbsp;</div>
		 <div class="abantecart_addtocart">&nbsp;</div>
	 </div>
 </div>
  */

?>

//set global sign of allowed 3dparty cookies as true by default. This value might be overridden by testcookie js
var abc_cookie_allowed = true; 
var abc_token_name = '<?php echo EMBED_TOKEN_NAME; ?>';
var abc_token_value = '';

(function() {
	// Localize jQuery
	var jQuery;

	/******** Load jQuery if not yet loaded *********/
	if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.11.0') {
		script_loader("http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js");
		// Poll for jQuery to come into existence
		var checkReady = function(callback) {
		    if (window.jQuery !== undefined) {
		   		callback(jQuery);
		    }
		    else {
		        window.setTimeout(function() { checkReady(callback); }, 10);
		    }
		};	
		checkReady(function($){
			jQuery = window.jQuery.noConflict(true);	
	    	main();
		});	
	} else {
	    // The jQuery version on the window is the one we want to use
	    jQuery = window.jQuery;
	    main();
	}
	
	/******** Called after jQuery has loaded ******/
	function scriptLoadHandler() {
	    // Restore $ and window.jQuery to their previous values and store the
	    // new jQuery in our local jQuery variable
	    jQuery = window.jQuery.noConflict(true);
	    main(); 
	}

	/*****************************************/

	var abc_get_cookie = function() {
		var name = 'abantecart_token';
		var matches = document.cookie.match(new RegExp(
	        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
	  return matches ? decodeURIComponent(matches[1]) : undefined;
	}

	abc_token_value = abc_get_cookie();

	if(abc_token_value != undefined && abc_token_value.length > 0){
		abc_cookie_allowed = false;
	}

	/********* AbanteCart url wrapper  ***********/
	var abc_process_url = function (url){
		if(abc_cookie_allowed==false){
			url += '&'+abc_token_name+'='+abc_token_value;
		}
		//TODO: need to add currency and language code
		return url;
	}

	var abc_process_request = function(url){
		if(url.length < 1){
			console.log('Abantecart embedded code: empty url requested!');
			return null; 
		}
		url = abc_process_url(url);
		script_loader(url);
	}

	/******** function to append css-file with styles for embedded block from AbanteCart host ********/
	var abc_append_css = function(url){
		if(url.length<1){
		    console.log('AbanteCart embedded code: empty url for css requested!');
		    return null;
		}
		css_loader(url);
	}

	/******** Main function ********/
	function main() { 
		//set new custom jQuery in global space for included scripts (custom bootstrap)
		window.jQuery_abc = jQuery;
		/******** Load custom modal *********/
		css_loader("<?php echo $base.$this->templateResource('/stylesheet/bootstrap.embed.css'); ?>");
		script_loader("<?php echo $base.$this->templateResource('/javascript/bootstrap.embed.js'); ?>");

		// Load bootstrap custom modal (single instance)
		var modal = '<div id="abc_embed_modal" class="abcmodal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">'+
					'<div class="abcmodal-dialog abcmodal-lg">'+
						'<div class="abcmodal-content">' +
							'<div class="abcmodal-header"><?php if($icon) { ?><img src="resources/<?php echo $icon; ?>""/>&nbsp;<?php } ?>&nbsp;<?php echo $store_name; ?>' +
								'<button aria-hidden="true" data-dismiss="abcmodal" class="abcmodal_close" type="button">&times;</button>' +
				'<h4 class="abcmodal-title"></h4>' +
							'</div>' +
				'<div class="abcmodal-body"><iframe id="amp_product_frame" width="100%" height="650px" frameBorder="0"></iframe>' +
				'<div id="iframe_loading" class="center_div"><i class="fa fa-spinner fa-spin fa-2x"></i></div>' +
							'</div>' +
						'</div>' +
					'</div>' +
			'</div>';
		modal += '<div class="abantecart-widget-cart"></div>';

	    jQuery(document).ready(function($) {

	        if( !$('#abc_embed_modal').length ) {
				$('body').append(modal);
			<?php
			// do cookie-test if session id not retrieved from http-request
			if($test_cookie){?>
				abc_token_name = '<?php echo EMBED_TOKEN_NAME; ?>';
				abc_token_value = abc_get_cookie();
				var testcookieurl  = '<?php echo $abc_embed_test_cookie_url; ?>';
				if(abc_token_value!=undefined && abc_token_value!=''){
					testcookieurl +='&<?php echo EMBED_TOKEN_NAME; ?>='+abc_token_value;
				}
				abc_process_request(testcookieurl);
			<?php } ?>
			}

			// Poll for abc_process_wrapper to come into existence
			var processReady = function(callback) {
			    if (abc_process_wrapper !== undefined) {
			   		callback();
			    }
			    else {
			        window.setTimeout(function() { processReady(callback); }, 10);
			    }
			};	
			processReady(function($){
				//fill data into embedded blocks
				abc_process_wrapper();
			});	

			$('#abc_embed_modal').on('shown.bs.abcmodal', function (e) {
			    var d = new Date();
				//get href of modal caller
				var frame_url = abc_process_url($(e.relatedTarget).attr('data-href')+ '&time_stamp='+d.getTime());

			    $('#abc_embed_modal iframe').attr("src", frame_url);
			    $('#iframe_loading').show();
			    $('#abc_embed_modal').abcmodal('show');
				$('#iframe_loading').hide();
			});

	    });


		var abc_process_wrapper = function(){
			//using local jQuery
			$ = jQuery;
			$('.abantecart-widget-container').each(function(){
				var c = $(this);
				//widget url - base url of widget data (for case when 2 widgets from different domains on the same page)
				var w_url = c.attr('data-url');
				if(c.attr('data-css-url')){
					//load remote css for this embed block
					abc_append_css(c.attr('data-css-url'));
				}
				abc_process_container(c, w_url);
				abc_populate_cart(w_url);

				$('.abantecart-widget-container').on("click", ".abantecart_addtocart", function(){
					abc_process_request($(".abantecart_addtocart button").attr('data-href'));
					abc_populate_cart(w_url);
					return false;
				});

			});
		}

		var abc_process_container = function (obj, w_url){
			//using local jQuery
			$ = jQuery;
			var child = $(obj).children().first();
			if(child.attr('data-product-id').length>0){
				abc_populate_product_item(child, w_url);
			}
			//	elseif(child.attr('data-category-id').length>0){} //for future
		}

		var abc_populate_product_item = function(child, w_url){
			//using local jQuery
			$ = jQuery;
			var product_id = child.attr('data-product-id');
			var d = new Date();
			// to know where we must to apply result
			var target_id = 'abc_'+d.getTime(); 
			child.attr('id',target_id);
			var url = w_url+'&rt=r/embed/js/product&product_id=' + product_id + '&target=' + target_id;
			abc_process_request(url);
		}

		var abc_populate_cart = function( w_url){
			//using local jQuery
			$ = jQuery;
			var url = w_url+'&rt=r/embed/js/cart';
			abc_process_request(url);
		}

	}
	
	/******** Script loader function ********/
	function script_loader( url ) { 
		var script_tag = document.createElement('script');
		script_tag.setAttribute("type","text/javascript");
		script_tag.setAttribute("src",url);
		// Try to find the head, otherwise default to the documentElement
		(document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
		return script_tag;
	}

	/******** CSS loader function ********/
	function css_loader( url ) { 
		var css_tag = document.createElement('link');
		css_tag.setAttribute("type","text/javascript");
		css_tag.setAttribute("rel",'stylesheet');
		css_tag.setAttribute("type",'text/css');
		css_tag.setAttribute("media","all");
		css_tag.setAttribute("href",url);
		// Try to find the head, otherwise default to the documentElement
		(document.getElementsByTagName("head")[0] || document.documentElement).appendChild(css_tag);
	}
	
})();