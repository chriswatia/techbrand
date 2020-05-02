(function( $ ) {
	"use strict";
	    // Sets cookies.
	var createCookie = function(name, value, days){

		// If we have a days value, set it in the expiry of the cookie.
		if ( days ) {
			var date = new Date();
			date.setTime(date.getTime() + (days*24*60*60*1000));
			var expires = '; expires=' + date.toGMTString();
		} else {
			var expires = '';
		}

		// Write the cookie.
		document.cookie = name + '=' + value + expires + '; path=/';
	}

	//	Email validation
	function isValidEmailAddress(emailAddress) {
	    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	    return pattern.test(emailAddress);
	};

	// Retrieves cookies.
	var getCookie = function(name){
		var nameEQ = name + '=';
		var ca = document.cookie.split(';');
		for ( var i = 0; i < ca.length; i++ ) {
			var c = ca[i];
			while ( c.charAt(0) == ' ' ) {
				c = c.substring(1, c.length);
			}
			if ( c.indexOf(nameEQ) == 0 ) {
				return c.substring(nameEQ.length, c.length);
			}
		}

		return null;
	}

    function validate_it( current_ele, value ) {
        if( !value.trim() ) {
            return true;
        } else if( current_ele.hasClass('cp-email') ) {
            if( !isValidEmailAddress( value ) ) {
                return true;
            }
            else {
                return false;
            }
        } else if( current_ele.hasClass('cp-textfeild') ) {
            if( /^[a-zA-Z0-9- ]*$/.test( value ) == false ) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

	function ib_process_cp_form(t) {

		var form 						= jQuery(t),
			data 						= form.serialize(),
			info_container  			= jQuery(t).parents(".global_info_bar_container").find('.cp-msg-on-submit'),
			form_container  			= jQuery(t).parents(".global_info_bar_container").find('.cp-form-container'),
			spinner  					= jQuery(t).parents(".global_info_bar_container").find('.cp-form-processing'),
			info_bar 					= jQuery(t).parents(".global_info_bar_container"),
			cp_form_processing_wrap 	= jQuery(t).parents(".global_info_bar_container").find('.cp-form-processing-wrap'),
			cp_animate_container    	= jQuery(t).parents(".global_info_bar_container"),
			cp_tooltip    				= info_bar.find(".cp-tooltip-icon").data('classes'),
			close_div 					= jQuery(t).parents(".global_info_bar_container").find(".ib-close");


		var cookieTime 					= info_bar.data('conversion-cookie-time');
		var redirectdata 				= jQuery(t).parents(".global_info_bar_container").data("redirect-lead-data"),
			redirect_to 				= jQuery(t).parents(".global_info_bar_container").data("redirect-to"),
		 	//download_url 				= jQuery(t).parents(".global_info_bar_container").data("download-url");
		 	form_action_on_submit 		= jQuery(t).parents(".global_info_bar_container").data("form-action"),
		 	form_action_dealy			= jQuery(t).parents(".global_info_bar_container").data("form-action-time"),
		 	form_action_dealy 			= parseInt(form_action_dealy * 1000);

		var parent_id = info_bar.data('parent-style');

        if( typeof parent_id !== 'undefined' ) {
            var cookieName = parent_id;
        } else {
            var cookieName = info_bar.data('info_bar-id');
        }


		// Check for required fields are not empty
		// And create query strings to send to redirect URL after form submission
        var query_string = '';
        var submit_status = true;
        var redirect_with = '';
        var cf_response = '';

        form.find('.cp-input').each( function(index) {
            var $this = jQuery(this);

            if( ! $this.hasClass('cp-submit-button')) { // Check condition for Submit Button
                var    input_name = $this.attr('name'),
                    input_value = $this.val();

                var res = input_name.replace(/param/gi, function myFunction(x){return ''; });
                res = res.replace('[','');
                res = res.replace(']','');

                query_string += ( index != 0 ) ? "&" : '';
                query_string += res+"="+input_value ;

                var input_required = $this.attr('required') ? true : false;

                if( input_required ) {
                    if( validate_it( $this, input_value ) ) {
                        submit_status = false;
                        $this.addClass('cp-input-error');
                    } else {
                        $this.removeClass('cp-input-error');
                    }
                }
            }
        });

		//	All form fields Validation
        var fail = 0;
        var fail_log = '';
        form.find( 'select, textarea, input' ).each(function(i, el ){
        	
            if( jQuery( el ).prop( 'required' )){

                var type = jQuery( el ).attr("type");
				if ( type == 'checkbox' && $(this).prop("checked") == false ) {
                    fail++;
                    setTimeout(function(){
			            jQuery( el ).addClass('cp-error');
			        },100);
                    name = jQuery( el ).attr( 'name' );
                    fail_log += name + " is required \n";
				}else if ( ! jQuery( el ).val() ) {
                    fail++;
                    setTimeout(function(){
			            jQuery( el ).addClass('cp-error');
			        },100);
                    name = jQuery( el ).attr( 'name' );
                    fail_log += name + " is required \n";
                } else {
                	//	Client side email Validation
                	//	If not empty value, Then validate email
                	if( jQuery( el ).hasClass('cp-email') ) {
		    			var email = jQuery( el ).val();

		    			if( isValidEmailAddress( email ) ) {
			    			jQuery( el ).removeClass('cp-error');
			    			//fail = false;
			    		} else {
			    			setTimeout(function(){
					            jQuery( el ).addClass('cp-error');
					        },100);
			    			fail++;
			    			var name = jQuery( el ).attr( 'name' ) || '';
			    			console.log( name + " is required \n" );
			    		}
		    		} else {
                		jQuery( el ).removeClass('cp-error');
		    		}
                }
            }
        });

        //submit if fail count never got greater than 0
        if ( fail > 0 ) {
            console.log( fail_log );
        } else {

			cp_form_processing_wrap.show();

			info_container.fadeOut(120, function() {
			    jQuery(this).show().css({visibility: "hidden"});
			    close_div.show().css({visibility: "hidden"});
			});

			spinner.hide().css({visibility: "visible"}).fadeIn(100);

			jQuery.ajax({
				url: smile_ajax.url,
				data: data,
				type: 'POST',
				dataType: 'HTML',
				success: function(result){

					if(cookieTime) {
						createCookie(cookieName,true,cookieTime);
					}

					var obj = jQuery.parseJSON( result );
					var cls = '';
					var msg_string = '';
					if( typeof obj.status != 'undefined' && obj.status != null ) {
						cls = obj.status;
					}

					if( typeof obj.cf_response != 'undefined' && obj.cf_response != null ) {
						cf_response = obj.cf_response;
						jQuery(document).trigger("cp_cf_response_done",[this,info_bar,cf_response]);
					}

					//	is valid - Email MX Record
					if( obj.email_status ) {
						form.find('.cp-email').removeClass('cp-error');
					} else {
						setTimeout(function(){
							form.find('.cp-email').addClass('cp-error');
						},100);
						form.find('.cp-email').focus();
					}

					var detailed_msg = (typeof obj.detailed_msg !== 'undefined' && obj.detailed_msg !== null )  ? obj.detailed_msg : '';

					if( detailed_msg !== '' && detailed_msg !== null ) {
						detailed_msg =  "<h5>Here is More Information:</h5><div class='cp-detailed-message'>"+detailed_msg+"</div>";
						detailed_msg += "<div class='cp-admin-error-notice'>Read How to Fix This, click <a target='_blank' rel='noopener' href='https://www.convertplug.com/plus/docs/something-went-wrong/'>here</a></div>";
						detailed_msg += "<div class='cp-go-back'>Go Back</div>";
						msg_string   += '<div class="cp-only-admin-msg">[Only you can see this message]</div>';
					}

					// remove backslashes from success message
					obj.message = obj.message.replace(/\\/g, '');

					// The Detailed message when the Google recaptcha Inavlid secret Key.
					if(obj.detailed_msg == 'Invalid Secret Key for Google Recaptcha'){
						setTimeout(function(){
							form.find('.g-recaptcha').addClass('cp-error');
						},100);
						form.find('.g-recaptcha').focus();
					}

					//	show message error/success
					if(typeof obj.message != 'undefined' && obj.message != null) {
						info_container.hide().css({visibility: "visible"}).fadeIn(120);
						close_div.hide().css({visibility:  "visible"}).fadeIn(120);
						msg_string += '<div class="cp-m-'+cls+'"><div class="cp-error-msg">'+obj.message+'</div>'+detailed_msg+'</div>';
						info_container.html( msg_string );
						cp_animate_container.addClass('cp-form-submit-'+cls);
					}


					if(typeof obj.action !== 'undefined' && obj.action != null){

						spinner.fadeOut(100, function() {
						    jQuery(this).show().css({visibility: "hidden"});
						});

						info_container.hide().css({visibility: "visible"}).fadeIn(120);
						close_div.hide().css({visibility:  "visible"}).fadeIn(120);

						if( cls === 'success' ) {

							//hide tooltip
							jQuery('head').append('<style class="cp-tooltip-css">.tip.'+cp_tooltip+'{display:none }</style>');

							// 	Redirect if status is [success]
							if( obj.action === 'redirect' ) {
								cp_form_processing_wrap.hide();
								info_bar.hide();
								var url =obj.url;
								var urlstring ='';
								if (url.indexOf("?") > -1) {
								    urlstring = '&';
								} else {
									urlstring = '?';
								}

								var redirect_url = url + urlstring + decodeURI(query_string);

								if( redirectdata == 1 ){
									redirect_url = redirect_url ;
								} else {
									redirect_url = obj.url ;
								}

								if(redirect_to !=='download'){
									redirect_with = redirect_to;
									var win_open = window.open( redirect_url,'_'+redirect_with );
									if(win_open == ''){
										document.location.href = redirect_url;
									}
								}else{

									if( redirect_url !== '' ){
										var redirect_file = redirect_url.split(',');
										jQuery.each( redirect_file, function(index,url){
											redirect_url = url;
											cp_ifb_download_file( redirect_url );
										});
									}
									//cp_ifb_download_file(redirect_url);
								}

							} else {
								cp_form_processing_wrap.show();
								
								if(form_action_on_submit == 'disappear'){
									info_bar.removeClass('cp-hide-inline-style');
									info_bar.removeClass('cp-close-ifb');

									setTimeout(function(){
										if( info_bar.hasClass('cp-info-bar-inline') ){
											info_bar.addClass('cp-hide-inline-style');
										}
										if( info_bar.hasClass('cp-ifb-with-toggle') ){
											info_bar.addClass('cp-close-ifb');
										}

										jQuery(document).trigger('cp_close_info_bar',[info_bar]);
									},form_action_dealy);
								}else if(form_action_on_submit == 'reappear'){
									setTimeout(function(){										
										info_container.empty();
										cp_form_processing_wrap.css({'display': 'none'});
										info_container.removeAttr('style');
										spinner.removeAttr('style');
										form.trigger("reset");


									},form_action_dealy);
								}

								// if button contains anchor tag then redirect to that url
								if( ( jQuery(t).find('a').length > 0 ) ) {
									var redirect_src = jQuery(t).find('a').attr('href');
									var redirect_target = jQuery(t).find('a').attr('target');
									if(redirect_target == '' || typeof redirect_target == 'undefined'){
										redirect_target ='_self';
									}
									if( redirect_src != '' || redirect_src != '#' ) {
										window.open( redirect_src,redirect_target );
									}
								}

								if( !(info_bar.hasClass('cp-do-not-close-inline'))){									
									setTimeout(function(){
										info_bar.addClass('cp-hide-inline-style');
							           jQuery(document).trigger('cp_close_info_bar',[info_bar]);
							        },3000);
								}

							}
						}
					}
				},
				error: function(data){
					//	Show form & Hide processing spinner
					cp_form_processing_wrap.hide();
					spinner.fadeOut(100, function() {
						jQuery(this).show().css({visibility: "hidden"});
					});
		        }
			});
		}
	}

	jQuery(document).ready(function(){

		jQuery('.cp-info-bar-container').find('.smile-optin-form').each(function(index, el) {

			// enter key press
			jQuery(el).find("input").keypress(function(event) {
			    if (event.which == 13) {
			        event.preventDefault();
			        ib_process_cp_form( el );
			    }
			});

		    // submit add subscriber request
		    jQuery(el).find('.btn-subscribe').click(function(e){
				e.preventDefault;
				jQuery( el ).find('.cp-input').removeClass('cp-error');
				if( !jQuery(this).hasClass('cp-disabled') ){
					ib_process_cp_form( el );
					//	Complete the Conversion.
					jQuery(document).trigger("ib_conversion_done",[this]);

					//	Redirect after conversion
					var redirect_link 			= jQuery(this).attr('data-redirect-link') || '';
					var redirect_link_target	= jQuery(this).attr('data-redirect-link-target') || '_blank';
					if( redirect_link != 'undefined' && redirect_link != '' ) {						
						if (navigator.userAgent.toLowerCase().match(/(ipad|iphone)/)) {
						   document.location = redirect_link; 
						}else{
							window.open( redirect_link , redirect_link_target );
						}
					}
				}
				e.preventDefault();
			});
		});

		// Close error message on click of message
		jQuery(document).on("click", ".cp-form-submit-error", function(e){

			var cp_form_processing_wrap = jQuery(this).find(".cp-form-processing-wrap") ,
				cp_tooltip              =  jQuery(this).find(".cp-tooltip-icon").data('classes'),
				cp_msg_on_submit        = jQuery(this).find(".cp-msg-on-submit"),
				cp_form_processing      = jQuery(this).find(".cp-form-processing");

			cp_form_processing_wrap.hide();
			jQuery(this).removeClass('cp-form-submit-error');
			cp_msg_on_submit.html('');
			cp_msg_on_submit.removeAttr("style");

			//show tooltip
			jQuery('head').append('<style class="cp-tooltip-css">.tip.'+cp_tooltip+'{display:block }</style>');

		});

	});

function cp_ifb_download_file(fileURL) {
   var link = jQuery("<a>");
    var index 	 = fileURL.lastIndexOf("/") + 1;
    var fileName = fileURL.substr(index);
    link.attr( "href", fileURL );
    link.attr( "download", fileName );
    link.text( "cpro_anchor_link" );
    link.addClass( "cplus_dummy_anchor" );
    link.attr( "target", "_blank" );
	jQuery('body').append(link);
	jQuery(".cplus_dummy_anchor")[0].click();
	
	setTimeout(function() {
	 jQuery(".cplus_dummy_anchor").remove();												                	
	}, 500 );
}

})( jQuery );
