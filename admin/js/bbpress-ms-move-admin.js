var bbpcNonce = '';
(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
var bbpc_run = false;

	$( window ).load(function() {
		jQuery('#bbpress_copy').click(function() {

			bbpcNonce = jQuery('#bbpc-nonce').val();
			if(bbpc_run){alert('Already run, please refresh the page.'); return;}

			var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
			var siteTo = jQuery('select[name=bbpress_copy_to]').val();

			if(siteFrom ==siteTo){
				alert('cant copy to the same site');
				return;
			}

			jQuery('.bbpc-checkbox').attr("disabled", true);

			bbpc_run = true;

			var first_function = jQuery('#bbpc-functions').find( "li" ).first().data("function");
			window[first_function]('copy');
			bbpc_enableBeforeUnload();

		});

		jQuery('#bbpress_delete').click(function() {
			if(confirm("Are you sure?"))
			{
				if(bbpc_run){alert('Already run, please refresh the page.'); return;}
				jQuery('.bbpc-checkbox').attr("disabled", true);
				bbpc_run = true;

				var first_function = jQuery('#bbpc-functions').find( "li" ).first().data("function");
				window[first_function]('delete');
				bbpc_enableBeforeUnload();
			}
			else
			{
				return;
			}
		});

	});



})( jQuery );



function bbpc_run_next($last,$action){
	var next = jQuery("ul#bbpc-functions").find("[data-function='" + $last + "']").next();
	console.log('###'+next.data("function"));
	if(next.data("function")){
		window[next.data("function")]($action);
	}else{
		alert('Done!');
		bbpc_disableBeforeUnload();
	}
}


// bbPress Copy functions

function bbpc_settings($action){
	
	var run = jQuery('#bbpc_settings_set').is(":checked");
	if(!run){
		return bbpc_run_next('bbpc_settings',$action);
	}

	var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
	var siteTo = jQuery('select[name=bbpress_copy_to]').val();


	var data = {
		'security': bbpcNonce,
		'action': 'bbpress_copy_ajax_handler',
		'bbpc_action': $action+'_settings',
		'bbpc_from': siteFrom,
		'bbpc_to': siteTo
	};

	jQuery.post(ajaxurl, data, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.error){alert(obj.error);}
		else{
			bbpc_progress('#bbpc_settings',100,100);
			jQuery('#bbpc_settings').val(100);
			return bbpc_run_next('bbpc_settings',$action);
		}
	});
}

function bbpc_forum_structure($action,$offset,$total){

	var run = jQuery('#bbpc_forum_structure_set').is(":checked");
	if(!run){
		return bbpc_run_next('bbpc_forum_structure',$action);
	}

	var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
	var siteTo = jQuery('select[name=bbpress_copy_to]').val();

	if(!$offset){$offset = 0;}

	var data = {
		'security': bbpcNonce,
		'action': 'bbpress_copy_ajax_handler',
		'bbpc_action': $action+'_forum_structure',
		'bbpc_from': siteFrom,
		'bbpc_to': siteTo,
		'bbpc_offset': $offset,
		'bbpc_total': $total
	};

	jQuery.post(ajaxurl, data, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.error){alert(obj.error);}
		else{

			bbpc_progress('#bbpc_forum_structure',obj.offset,obj.total);
			console.log(obj);
			if(obj.offset < obj.total){
				bbpc_forum_structure($action,obj.offset,obj.total);
			}else{
				return bbpc_run_next('bbpc_forum_structure',$action);
			}

		}
	});
}


function bbpc_forum_topics($action,$offset,$total){

	var run = jQuery('#bbpc_forum_topics_set').is(":checked");
	if(!run){
		return bbpc_run_next('bbpc_forum_topics',$action);
	}

	var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
	var siteTo = jQuery('select[name=bbpress_copy_to]').val();

	if(!$offset){$offset = 0;}

	var data = {
		'security': bbpcNonce,
		'action': 'bbpress_copy_ajax_handler',
		'bbpc_action': $action+'_forum_topics',
		'bbpc_from': siteFrom,
		'bbpc_to': siteTo,
		'bbpc_offset': $offset,
		'bbpc_total': $total
	};

	jQuery.post(ajaxurl, data, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.error){alert(obj.error);}
		else{

			bbpc_progress('#bbpc_forum_topics',obj.offset,obj.total);

			console.log(obj);
			if(obj.offset < obj.total){
				bbpc_forum_topics($action,obj.offset,obj.total);
			}else{
				return bbpc_run_next('bbpc_forum_topics',$action);
			}

		}
	});
}

function bbpc_forum_replies($action,$offset,$total){

	var run = jQuery('#bbpc_forum_replies_set').is(":checked");
	if(!run){
		return bbpc_run_next('bbpc_forum_replies',$action);
	}

	var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
	var siteTo = jQuery('select[name=bbpress_copy_to]').val();

	if(!$offset){$offset = 0;}

	var data = {
		'security': bbpcNonce,
		'action': 'bbpress_copy_ajax_handler',
		'bbpc_action': $action+'_forum_replies',
		'bbpc_from': siteFrom,
		'bbpc_to': siteTo,
		'bbpc_offset': $offset,
		'bbpc_total': $total
	};

	jQuery.post(ajaxurl, data, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.error){alert(obj.error);}
		else{

			bbpc_progress('#bbpc_forum_replies',obj.offset,obj.total);

			console.log(obj);
			if(obj.offset < obj.total){
				bbpc_forum_replies($action,obj.offset,obj.total);
			}else{
				return bbpc_run_next('bbpc_forum_replies',$action);
			}

		}
	});
}

function bbpc_forum_terms($action,$offset,$total){

	var run = jQuery('#bbpc_forum_terms_set').is(":checked");
	if(!run){
		return bbpc_run_next('bbpc_forum_terms',$action);
	}

	var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
	var siteTo = jQuery('select[name=bbpress_copy_to]').val();

	if(!$offset){$offset = 0;}

	var data = {
		'security': bbpcNonce,
		'action': 'bbpress_copy_ajax_handler',
		'bbpc_action': $action+'_forum_terms',
		'bbpc_from': siteFrom,
		'bbpc_to': siteTo,
		'bbpc_offset': $offset,
		'bbpc_total': $total
	};

	jQuery.post(ajaxurl, data, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.error){alert(obj.error);}
		else{

			bbpc_progress('#bbpc_forum_terms',obj.offset,obj.total);
			console.log(obj);
			if(obj.offset < obj.total){
				bbpc_forum_terms($action,obj.offset,obj.total);
			}else{
				return bbpc_run_next('bbpc_forum_terms',$action);
			}

		}
	});
}

function bbpc_forum_term_relationships($action,$offset,$total){

	var run = jQuery('#bbpc_forum_term_relationships_set').is(":checked");
	if(!run){
		return bbpc_run_next('bbpc_forum_term_relationships',$action);
	}

	var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
	var siteTo = jQuery('select[name=bbpress_copy_to]').val();

	if(!$offset){$offset = 0;}

	var data = {
		'security': bbpcNonce,
		'action': 'bbpress_copy_ajax_handler',
		'bbpc_action': $action+'_forum_term_relationships',
		'bbpc_from': siteFrom,
		'bbpc_to': siteTo,
		'bbpc_offset': $offset,
		'bbpc_total': $total
	};

	jQuery.post(ajaxurl, data, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.error){alert(obj.error);}
		else{

			bbpc_progress('#bbpc_forum_term_relationships',obj.offset,obj.total);
			console.log(obj);
			if(obj.offset < obj.total){
				bbpc_forum_term_relationships($action,obj.offset,obj.total);
			}else{
				return bbpc_run_next('bbpc_forum_term_relationships',$action);
			}

		}
	});
}

function bbpc_forum_user_subscriptions($action,$offset,$total){

	var run = jQuery('#bbpc_forum_user_subscriptions_set').is(":checked");
	if(!run){
		return bbpc_run_next('bbpc_forum_user_subscriptions',$action);
	}

	var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
	var siteTo = jQuery('select[name=bbpress_copy_to]').val();

	if(!$offset){$offset = 0;}

	var data = {
		'security': bbpcNonce,
		'action': 'bbpress_copy_ajax_handler',
		'bbpc_action': $action+'_forum_user_subscriptions',
		'bbpc_from': siteFrom,
		'bbpc_to': siteTo,
		'bbpc_offset': $offset,
		'bbpc_total': $total
	};

	jQuery.post(ajaxurl, data, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.error){alert(obj.error);}
		else{

			bbpc_progress('#bbpc_forum_user_subscriptions',obj.offset,obj.total);

			console.log(obj);
			if(obj.offset < obj.total){
				bbpc_forum_user_subscriptions($action,obj.offset,obj.total);
			}else{
				return bbpc_run_next('bbpc_forum_user_subscriptions',$action);
			}

		}
	});
}

function bbpc_forum_user_capabilities($action,$offset,$total){

	var run = jQuery('#bbpc_forum_user_capabilities_set').is(":checked");
	if(!run){
		return bbpc_run_next('bbpc_forum_user_capabilities',$action);
	}

	var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
	var siteTo = jQuery('select[name=bbpress_copy_to]').val();

	if(!$offset){$offset = 0;}

	var data = {
		'security': bbpcNonce,
		'action': 'bbpress_copy_ajax_handler',
		'bbpc_action': $action+'_forum_user_capabilities',
		'bbpc_from': siteFrom,
		'bbpc_to': siteTo,
		'bbpc_offset': $offset,
		'bbpc_total': $total
	};

	jQuery.post(ajaxurl, data, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.error){alert(obj.error);}
		else{

			bbpc_progress('#bbpc_forum_user_capabilities',obj.offset,obj.total);

			console.log(obj);
			if(obj.offset < obj.total){
				bbpc_forum_user_capabilities($action,obj.offset,obj.total);
			}else{
				return bbpc_run_next('bbpc_forum_user_capabilities',$action);
			}

		}
	});
}

function bbpc_forum_user_levels($action,$offset,$total){

	var run = jQuery('#bbpc_forum_user_levels_set').is(":checked");
	if(!run){
		return bbpc_run_next('bbpc_forum_user_levels',$action);
	}

	var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
	var siteTo = jQuery('select[name=bbpress_copy_to]').val();

	if(!$offset){$offset = 0;}

	var data = {
		'security': bbpcNonce,
		'action': 'bbpress_copy_ajax_handler',
		'bbpc_action': $action+'_forum_user_levels',
		'bbpc_from': siteFrom,
		'bbpc_to': siteTo,
		'bbpc_offset': $offset,
		'bbpc_total': $total
	};

	jQuery.post(ajaxurl, data, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.error){alert(obj.error);}
		else{

			bbpc_progress('#bbpc_forum_user_levels',obj.offset,obj.total);

			console.log(obj);
			if(obj.offset < obj.total){
				bbpc_forum_user_levels($action,obj.offset,obj.total);
			}else{
				return bbpc_run_next('bbpc_forum_user_levels',$action);
			}

		}
	});
}

function bbpc_forum_attachments($action,$offset,$total){

	var run = jQuery('#bbpc_forum_attachments_set').is(":checked");
	if(!run){
		return bbpc_run_next('bbpc_forum_attachments',$action);
	}

	var siteFrom = jQuery('select[name=bbpress_copy_from]').val();
	var siteTo = jQuery('select[name=bbpress_copy_to]').val();

	if(!$offset){$offset = 0;}

	var data = {
		'security': bbpcNonce,
		'action': 'bbpress_copy_ajax_handler',
		'bbpc_action': $action+'_forum_attachments',
		'bbpc_from': siteFrom,
		'bbpc_to': siteTo,
		'bbpc_offset': $offset,
		'bbpc_total': $total
	};

	jQuery.post(ajaxurl, data, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.error){alert(obj.error);}
		else{

			bbpc_progress('#bbpc_forum_attachments',obj.offset,obj.total);
			console.log(obj);
			if(obj.offset < obj.total){
				bbpc_forum_attachments($action,obj.offset,obj.total);
			}else{
				return bbpc_run_next('bbpc_forum_attachments',$action);
			}

		}
	});
}

function bbpc_progress(id,done,total){
	jQuery(id).attr('max',total);
	jQuery(id).val(done);
	if(done < total){
		jQuery(id+'_progress').html(done+' / '+total);
	}else{
		jQuery(id+'_progress').html('Done!');
	}
}



function bbpc_enableBeforeUnload() {
	var bbpc_confirmOnPageExit = function (e)
	{
		// If we haven't been passed the event get the window.event
		e = e || window.event;

		var message = 'You will have to start again if you navigate away now?';

		// For IE6-8 and Firefox prior to version 4
		if (e)
		{
			e.returnValue = message;
		}

		// For Chrome, Safari, IE8+ and Opera 12+
		return message;
	};

	window.onbeforeunload = bbpc_confirmOnPageExit;

}
function bbpc_disableBeforeUnload() {
	window.onbeforeunload = null;
}