(function($) {
    "use strict";
    /**
     * JavaScript class for working with third party services.@since 3.0.1
     */
     var cp_form          = '',
     $this            = '',
     cta_delay        = '',
     returns          = '',
     class_id         = '',
     hasClass         = '',
     modal            = '',
     display          = '',
     referrers        = '',
     url              = '',
     doc_ref          = '',
     dr_arr           = '',
     ucount           = '',
     dr_domain        = '',
     _domain          = '',
     timestring       = '',
     y                = new Date(),
     gtime            = y.toGMTString(),
     ltime            = y.toLocaleString(),
     date             = new Date(),
     tzoffset         = '',
     utc              = '',
     new_date         = '',
     scheduled        = '',
     start            = '',
     end              = '',
     expires          = '',
     nameEQ           = '',
     dev_mode         = '',
     exit             = '',
     opt              = '',
     delay            = '',
     load_on_refresh  = '',
     scrollTill       = '',
     scheduled        = '',
     nounce           = '',
     parent_id        = '',
     cookieName       = '',
     temp_cookie      = '',
     cookie           = '',
     tmp_cookie       = '',
     refresh_cookie   = '',
     referrer         = '',
     ref_check        = '',
     doc_ref          = '',
     referred         = true,
     cp_scroll        = true,
     first_time_user  = false,
     is_open          = true,
     isAutoPlay       = '',
     data             = '',
     $modal_container = '',
     getPriorityModal = '',
     inactive_time    = '',
     scrollTilllength = '',
     scrollValue      = '',
     afterpost        = false,
     Youtube_on_tab   = false,
     scroll_class     = '',
     iframes          = '',
     styleArray       = Array(),
     custom_class_arr = Array(),
     ib_id               = '',
     style               = '',
     info_bar            = '',
     scrollPercent       = '',
     toggle_visible      = '',
     disabled_upto       = '',
     numLoads            = '',
     count_load          = '',
     page_down           = '',
     infobar_container   = '',
     ib_height           = '',
     anim                = '',
     ifb_scroll_to_x     = '',
     scrolled            = '',
     ab_height           = '',
     custom_class        = '',
     module_type         = '',
     module              = '',
     cp_height           = '',
     slidein             = '',
     slidein_container   = '',
     delay_set           = '',
     url_arr             = '',
     ajax_run            = true,
     custom_selector     = '',
     floating_status     = 0 ,
     close_img           = '',
     custom_style        = '',
     window_style        = '',
     woo_events          = false,
     add_flag            = false;


     var ConvertPlus = {
        /**
         * Initializes the all class variables.
         * @return void
         * @since 4.0.1
         */
         init: function( e, element, event ) {

            var body               = $('body');
                $this              = element,
                class_id           = element.data("class-id"),
                module_type        = element.data("module-type");
                dev_mode           = element.data("dev-mode"),
                exit               = element.data("exit-intent"),
                opt                = element.data('option'),
                modal              = $('.'+class_id),
                delay_set          = element.data("onload-delay"),
                delay              = delay_set * 1000,  // convert delay time from seconds to miliseconds
                load_on_refresh    = element.data('load-on-refresh'),
                scrollTill         = element.data("onscroll-value"),
                display            = false,
                nounce             = element.find(".cp-impress-nonce").val(),
                referrer           = element.data('referrer-domain'),
                ref_check          = element.data('referrer-check'),
                doc_ref            = document.referrer.toLowerCase(),
                isAutoPlay         = modal.find('.cp-youtube-frame').attr('data-autoplay') || '0';
                $modal_container   = $( "." + class_id ).parents(".cp-modal-popup-container"),
                getPriorityModal   = ConvertPlus._getPrioritized(),
                inactive_time      = element.data('inactive-time'),
                scrollTilllength   = jQuery(".cp-load-after-post").length,
                scrollValue        = element.data("after-content-value"),
                scroll_class       = element.data("scroll-class"),
                afterpost          = element.hasClass('cp-after-post'),
                custom_class       = element.data('custom-class'),
                custom_selector    = element.data('custom-selector'),
                ib_id              = element.attr("id"),
                iframes            = modal.find('iframe'),
                disabled_upto      = modal.data('load-on-count'),
                close_img          = modal.find('.cp-close-img').data('close-scr');

                if( isAutoPlay !=='' ){
                    isAutoPlay = modal.find('.cp-youtube-continer').attr('data-autoplay') || '0';
                }
                if( module_type == 'info-bar' ){
                    cookieName        = element.data('info_bar-id'),
                    parent_id         = element.data('parent-style'),
                    style             = element.data('info_bar-style'),
                    info_bar          = element,
                    ib_id             = element.attr("id"),
                    toggle_visible    = element.data('toggle-visible'),
                    display           = true,
                    module            = info_bar,
                    infobar_container = element,
                    afterpost         = element.hasClass('ib-after-post'),
                    disabled_upto     = element.data('load-on-count');
                    ConvertPlus._infoBarPos( info_bar ); //set inofbar position
                    scheduled          = ConvertPlus._isScheduled( info_bar );
                    close_img          = info_bar.find('.cp-close-img').data('close-scr');
                    setTimeout(function() {
                     ib_height = infobar_container.outerHeight();
                 }, 100 );

                }else if( module_type == 'modal'){
                    parent_id          = modal.data('parent-style'),
                    cookieName         = element.data('modal-id'),
                    style              = element.data('modal-style'),
                    scheduled          = ConvertPlus._isScheduled( modal ),
                    module             = modal;
                    custom_style       = modal.find('.cp-modal-body').data('custom-style'),
                    window_style       = modal.find('.cp-modal-content').data('window-style');
                }else if( module_type == 'slide_in'){
                    slidein             = $('.'+class_id),
                    cookieName          = slidein.data('slidein-id'),
                    toggle_visible      = element.data('toggle-visible'),
                    style               = slidein.data('slidein-style'),
                    afterpost           = element.hasClass('si-after-post'),
                    slidein_container   = element.closest('.cp-slidein-popup-container'),
                    module              = $('.'+class_id),
                    scheduled           = ConvertPlus._isScheduled( slidein ),
                    parent_id           = slidein.data('parent-style'),
                    custom_style        = slidein.find('.cp-slidein-body').data('custom-style'),
                    close_img           = slidein.find('.cp-close-img').data('close-scr');
                }
                if( module_type == 'modal' && module.hasClass('cp-window-size')){ modal.windowSize(); }

                if( typeof parent_id !== 'undefined' ) {  cookieName = parent_id; }

                temp_cookie         = "temp_"+cookieName;
                ConvertPlus._removeCookie(temp_cookie);

                switch( event ) {

                    case 'load':
                    if( delay_set !== '' ){
                        this._CploadEvent();
                    }
                    this._CpCustomClass();
                    this._CpLoadImages();

                    this._CpIframe();
                    if( module_type == 'slide_in'){
                        this._close_button_tootip();
                    }
                    break;

                    case 'scroll':
                    this._CpscrollEvent( e );
                    break;

                    case 'mouseleave':
                    this._CpmouseleaveEvent( e );
                    break;

                    case 'closepopup':
                    this._CpclosepopupEvent( e );
                    break;

                    case 'idle':
                    this._CpidleEvent();
                    break;
                }
            },
        /**
         * Check modals visibility on first load
         * @return nothing
         */
         _hide_on_page_load: function(md){
            var display = false;
            if( load_on_refresh == "disabled" ){
                var disabled_upto   = md.data('load-on-count'),
                disabled_upto      = disabled_upto - 1 ,
                numLoads           = parseInt(ConvertPlus._getPageCookie(cookieName+'pageLoads'), 10);

                if (isNaN(numLoads) || numLoads <= 0) {
                    ConvertPlus._setPageCookie(cookieName+'pageLoads', 1);
                }else {
                   ConvertPlus._setPageCookie(cookieName+'pageLoads', numLoads + 1);
               }

               var count_load = ConvertPlus._getPageCookie(cookieName+'pageLoads') ;
               if( count_load > disabled_upto ){
                display = true;
            }
        } else {
            ConvertPlus._removeCookie(cookieName+'pageLoads');
        }
        return display;
    },
        /**
         * Clsoe popup Event
         * @return nothing
         */
         _CpclosepopupEvent: function( event ){
            var type = module_type;
            if( type == 'modal' && typeof modal !=='undefined'){
                var template      = $modal_container.data('template'),
                cookieTime    = modal.data('closed-cookie-time'),
                cp_animate    = modal.find('.cp-animate-container'),
                entry_anim    = modal.data('overlay-animation'),
                exit_anim     = cp_animate.data('exit-animation'),
                animatedwidth = cp_animate.data('disable-animationwidth'),
                vw            = jQuery(window).width(),
                parent_id     = modal.data('parent-style');

                if( typeof parent_id !== 'undefined' ) {
                    var cookieName = parent_id;
                } else {
                    var cookieName = modal.data('modal-id');
                }
                ConvertPlus._createCookie(temp_cookie,true,1);
                var cookie      = ConvertPlus._getCookie(cookieName);
                ConvertPlus._cpExecuteVideoAPI(modal,'pause');
                if(typeof event !== 'undefined' ){
                    event.preventDefault();
                }
                if(!cookie){
                    if(cookieTime){
                        ConvertPlus._createCookie(cookieName,true,cookieTime);
                        ConvertPlus._cpExecuteVideoAPI(modal,'pause');
                    }
                }

                if( exit_anim == "cp-overlay-none" || ( typeof animatedwidth !== 'undefined' && vw <= animatedwidth ) ){
                    modal.removeClass("cp-open");
                    if( modal.hasClass('cp-hide-inline-style') ){
                        exit_anim = "cp-overlay-none";
                    }

                    exit_anim = "cp-overlay-none";
                    if( jQuery(".cp-open").length < 1 ){
                        jQuery("html").removeAttr('style');
                    }
                }

                //if( !template ){
                    cp_animate.removeClass( entry_anim );
                    if( vw >= animatedwidth || typeof animatedwidth == 'undefined' ){
                        cp_animate.addClass( exit_anim );
                    }
                   if( exit_anim !== "cp-overlay-none" ){
                        setTimeout( function(){
                            ConvertPlus._cpExecuteVideoAPI(modal,'pause');
                            if( jQuery(".cp-open").length < 1 ){
                                jQuery("html").removeAttr('style');
                            }
                            setTimeout( function(){
                                cp_animate.removeClass(exit_anim);
                            },500);

                            modal.removeClass("cp-open");
                            jQuery(".cp-overlay").removeClass("cp-open");
                        }, 1000 );
                    }

                //}

            }else if( type =='info-bar' ){
                var entry_anim        = info_bar.data('entry-animation'),
                exit_anim         = info_bar.data('exit-animation'),
                cookieTime        = info_bar.data('closed-cookie-time'),
                cookieName        = info_bar.data('info_bar-id'),
                animate_push_page = info_bar.data('animate-push-page'),
                page_push_down    = info_bar.data('push-down') || null,
                parent_id         = info_bar.data('parent-style');

                jQuery("html").removeClass("cp-ib-open");

                if( typeof parent_id !== 'undefined' ) {
                    cookieName = parent_id;
                } else {
                    cookieName = info_bar.data('info_bar-id');
                }
                temp_cookie     = "temp_"+cookieName;

                //  If not has 'cp-ifb-with-toggle' class for smooth toggle
                if( !info_bar.hasClass('cp-ifb-with-toggle') ){
                    info_bar.removeClass(entry_anim);
                    info_bar.addClass(exit_anim);
                }

                if( info_bar.hasClass("cp-pos-top")){

                    if( page_push_down ) {
                        var cp_top_offset_container = jQuery("#cp-top-offset-container").val(),
                        offset_def_settings = jQuery("#cp-top-offset-container").data('offset_def_settings');
                        if( typeof offset_def_settings !== 'undefined' ){
                           var mTop                = offset_def_settings.margin_top,
                            top                    = offset_def_settings.top;                        

                            setTimeout(function() {
                                if(info_bar.hasClass('cp-ifb-hide')){
                                    mTop                = 0,
                                    top                 = 0;
                                }                           
                                if( animate_push_page == 1 ) {

                                    if( cp_top_offset_container == '' ) {
                                        jQuery('body').animate({
                                            'marginTop' : mTop,
                                            'top'       : top
                                        });
                                    }
                                    else {
                                        jQuery(cp_top_offset_container).animate({
                                            'margin-top' : mTop,
                                            'top'        : top
                                        });
                                    }
                                } else {
                                    if( cp_top_offset_container == '' ) {
                                        jQuery('body').css({
                                            'margin-top' : mTop,
                                            'top'        : top
                                        });
                                    }
                                    else {
                                        jQuery(cp_top_offset_container).css({
                                            'margin-top' : mTop,
                                            'top'        : top
                                        });
                                    }
                                }
                                
                            }, 2000);

                        }

                    }    

                    if( jQuery(".ib-display").length == 1 ) {
                        var admin_bar_height                = jQuery('#wpadminbar').outerHeight(),
                        cp_push_down_support_container  = jQuery("#cp-push-down-support").val();

                        if( jQuery('#wpadminbar').length ) {
                            if( animate_push_page == 1 ) {
                                jQuery(cp_push_down_support_container).animate({ 'top': admin_bar_height }, 1000 );
                            } else {
                                jQuery(cp_push_down_support_container).css( 'top',  admin_bar_height );
                            }
                        } else {
                            if( animate_push_page == 1 ) {
                                jQuery(cp_push_down_support_container).animate({ 'top': '0px' }, 1000 );
                            } else{
                                jQuery(cp_push_down_support_container).css( 'top', '0px' );
                            }
                        }
                    }
                }

                ConvertPlus._createCookie(temp_cookie,true,1);
                if(cookieTime) {
                    ConvertPlus._createCookie(cookieName,true,cookieTime);
                }

                if( info_bar.hasClass('cp-hide-inline-style') || info_bar.hasClass('cp-close-ifb') ){
                    exit_anim = "cp-overlay-none";
                }

                if( info_bar.hasClass('cp-close-ifb') ){
                    setTimeout( function(){
                        info_bar.hide();
                        info_bar.removeClass("ib-display");
                            //  If not has 'cp-ifb-with-toggle' class for smooth toggle
                            info_bar.removeClass(exit_anim);
                            info_bar.addClass(entry_anim);

                            jQuery("html").css("overflow-x","auto");
                        }, 3000);
                }

                if( exit_anim !== "cp-overlay-none" ){
                    setTimeout( function(){

                        if(!info_bar.hasClass('cp-ifb-with-toggle') ){
                            info_bar.hide();
                            info_bar.removeClass("ib-display");
                            //  If not has 'cp-ifb-with-toggle' class for smooth toggle
                            info_bar.removeClass(exit_anim);
                            info_bar.addClass(entry_anim);
                        }
                        jQuery("html").css("overflow-x","auto");
                    }, 3000);
                } else {
                    setTimeout( function(){
                        if(!info_bar.hasClass('cp-ifb-with-toggle')){
                            info_bar.hide();
                            info_bar.removeClass("ib-display");
                            //  If not has 'cp-ifb-with-toggle' class for smooth toggle
                            exit_anim = "cp-overlay-none";
                            info_bar.removeClass(exit_anim);
                            info_bar.addClass(entry_anim);
                        }
                        jQuery("html").css("overflow-x","auto");
                    }, 100);
                }
            }else if( type =='slide_in' ){
                var container   = slidein.parents(".cp-slidein-popup-container"),
                template    = container.data('template'),
                cookieTime  = slidein.data('closed-cookie-time'),
                cp_animate  = slidein.find('.cp-animate-container'),
                entry_anim  = slidein.data('overlay-animation'),
                exit_anim   = cp_animate.data('exit-animation'),
                parent_id   = slidein.data('parent-style');

                jQuery("html").removeClass("cp-si-open");

                if( typeof parent_id !== 'undefined' ) {
                    cookieName = parent_id;
                } else {
                    cookieName = slidein.data('slidein-id');
                }

                temp_cookie = "temp_"+cookieName;
                ConvertPlus._createCookie(temp_cookie,true,1);
                cookie      = ConvertPlus._getCookie(cookieName);
                if(typeof event !== 'undefined' ){
                    event.preventDefault();
                }
                if(!cookie){
                    if(cookieTime){
                        ConvertPlus._createCookie(cookieName,true,cookieTime);
                    }
                }

                if( slidein.hasClass('cp-hide-inline-style') || slidein.hasClass('cp-close-slidein') ){
                    exit_anim = "cp-overlay-none";
                }

                if( slidein.hasClass('cp-close-slidein') || slidein.hasClass('cp-close-after-x')  ){
                    slidein.removeClass("si-open");
                }

                var animatedwidth = cp_animate.data('disable-animationwidth');
                var vw = jQuery(window).width();
                if( exit_anim == "cp-overlay-none" || ( typeof animatedwidth !== 'undefined' && vw <= animatedwidth ) ){
                    if(slidein.hasClass('cp-slide-without-toggle')){
                        slidein.removeClass("si-open");
                    }
                    exit_anim = "cp-overlay-none";
                    if( jQuery(".cp-slidein-global.si-open").length < 1 ){
                        jQuery("html").removeAttr('style');
                    }
                }

                if( !template ){
                    cp_animate.removeClass( entry_anim );
                    var animatedwidth = cp_animate.data('disable-animationwidth'),
                    vw = jQuery(window).width();

                    if( vw >= animatedwidth || typeof animatedwidth == 'undefined' ){
                        cp_animate.addClass( exit_anim );
                    }

                    if( exit_anim !== "cp-overlay-none" ){

                        setTimeout( function(){

                            if(slidein.hasClass('cp-slide-without-toggle')){
                                slidein.removeClass("si-open");
                            }

                            if( jQuery(".cp-slidein-global.si-open").length < 1 ){
                                jQuery("html").removeAttr('style');
                            }
                            setTimeout( function(){
                               if( !slidein.hasClass('do_not_close')){
                                cp_animate.removeClass(exit_anim);
                            }
                        });
                        }, 1000 );
                    }
                }
            }
            jQuery("html").removeClass("cp-mp-open");
            jQuery('html').removeClass('cp-oveflow-hidden');
            jQuery('html').removeClass('customize-support');            
            jQuery('html').removeClass('cp-exceed-viewport');
            jQuery('html').removeClass('cp-exceed-vieport cp-window-viewport');
            jQuery("html").removeClass('cp-custom-viewport');
            jQuery('html').removeClass('cp-overflow-hidden');
        },
        /**
         * Get Custom Class for modules
         * @return nothing
         */
         _CpCustomClass: function(){
            if( typeof custom_class !== "undefined" && custom_class !== "" ){
                custom_class = custom_class.split(" ");
                jQuery.each( custom_class, function(index,classname){
                    if( typeof classname !== 'undefined' && classname !== '' ){
                        custom_class_arr.push(classname);
                    }
                });
            }            
        },      
        _CpLoadImages: function(){
            var md      = module,
            type        = module_type,
            image       = close_img,
            c_style     = custom_style,
            w_style     = window_style; 
           
            if( 'modal'== type ){                
                if( 'undefined' != typeof c_style ){
                    md.find('.cp-modal-body').attr( 'style', c_style );
                    md.find('.cp-modal-body').removeAttr('data-custom-style');
                }

                if( 'undefined' != typeof w_style ){
                    md.find('.cp-modal-content').attr( 'style', w_style );
                    md.find('.cp-modal-content').removeAttr('data-window-style');
                }
            }else if( 'slide_in'== type ){
                if( 'undefined' != typeof c_style ){
                    md.find('.cp-slidein-body').attr( 'style', c_style );
                    md.find('.cp-slidein-body').removeAttr('data-custom-style');
                }
            }

            //md.find('.cp-image').
        }, 
        /**
         * Check video popup 
         * @return nothing
         */
         _CpIframe: function(){
            jQuery.each(iframes, function( index, iframe ){
                var src = iframe.src,
                youtube = src.search('youtube.com'),
                vimeo = src.search('vimeo.com'),
                src = src.replace("&autoplay=1","");
                src = src.replace("&mute=1","");
                if( youtube !== -1 ){
                    var yt_src = ( src.indexOf("?") === -1 ) ? src+'?enablejsapi=1' : src+'&enablejsapi=1';
                    iframe.src = yt_src;
                    iframe.id = 'yt-'+class_id;
                }
                if( vimeo !== -1 ){
                    var vm_src = ( src.indexOf("?") === -1 ) ? src+'?api=1' : src+'&api=1';
                    iframe.src = iframe.src+'?api=1';
                    iframe.id = 'vim-'+class_id;
                }
            });
        },
        /**
         * load popup
         * @return nothing
         */
         _CploadEvent: function(){
            var md      = module,
            type    = module_type,
            id      = style,
            display = false,
            invoke  = false;

            if( load_on_refresh == "disabled" ){
                var first_time_user = ConvertPlus._hide_on_page_load(md);

            }else{
                var first_time_user = true;
            }
            if( typeof md!=='undefined' && ConvertPlus._canCpShow() && first_time_user && delay ){

               setTimeout(function() {
                display = ( ConvertPlus._isOtherPopupOpen(type) );

                if( type == 'slide_in'){
                    invoke = ConvertPlus._check_slide_open(md);
                    if(invoke)   {
                        display = true;
                    }
                }

                if(display){
                    ConvertPlus._displayPopup( md,type,id );
                }
            },parseInt( delay ));
           }
           if( dev_mode == "enabled" ){
            ConvertPlus._removeCookie(cookieName);
        }
    },
        /**
         * Exit intent trigger
         * @return nothing
         */
         _CpmouseleaveEvent: function(e){
            var md      = module,
            type    = module_type,
            id      = style,
            invoke  = false,
            display = false;

            if( exit == 'enabled' && typeof md !=='undefined' && ConvertPlus._canCpShow() ) {
                if( e.clientY <= 0 ){
                    display = ( ConvertPlus._isOtherPopupOpen(type) );

                    if( type == 'slide_in'){
                        invoke = ConvertPlus._check_slide_open(md);
                        if(invoke)   {
                            display = true;
                        }
                    }
                    if(display ) {
                       ConvertPlus._displayPopup(md,type,id);
                   }
               }
           }
           if( dev_mode == "enabled" ){
            ConvertPlus._removeCookie(cookieName);
        }
    },
        /**
         * Function to check multiple slidein ar open or not
         * @return nothing
         */
         _check_slide_open:function(md){
            var display = false;
            if( md.find(".cp-slide-in-float-on").length!== 0 && jQuery(".si-open").find(".cp-slide-in-float-on").length <= 1){
                floating_status = 1;
            }
            if( jQuery(".si-open").length <= floating_status ){
                display = true;
            } else {
                display = false;
            }
            return display;
        },
        /**
         * On scroll event
         * @return nothing
         */
         _CpscrollEvent: function(e){
            var scrolled             = jQuery(window).scrollTop(),
            scrollPercent        = 100 * jQuery(window).scrollTop() / (jQuery(document).height() - jQuery(window).height()),
            md                   = module,
            display              = false,
            invoke               = false,
            load_on_scroll       = 'disable',
            scrollTill_post      = '',
            is_on_screen         = '',
            load_on_scroll_class = '',
            id                   = style,
            type                 = module_type;    

            if( scrollTill ){
                load_on_scroll = 'enable';
                scrolled =  scrollPercent.toFixed(0);
            }

            if( typeof scroll_class !== 'undefined' ){
                load_on_scroll_class = 'enable';
            }

            if( ConvertPlus._canCpShow()  ) {
                display = ( ConvertPlus._isOtherPopupOpen(type) );
                if( scrolled >= scrollTill && load_on_scroll == 'enable' ){
                    invoke = true;
                }else if( afterpost ) {

                    if( scrollTilllength > 0 ){
                        scrollTill_post  = jQuery(".cp-load-after-post").offset().top - 30;
                        scrollTill_post  = scrollTill_post - ( ( jQuery(window).height() * scrollValue ) / 100 );

                        if( scrolled >= scrollTill_post ) {
                            invoke = true;
                        }
                    }else{
                        invoke = false;
                    }
                }else if( load_on_scroll_class =='enable' ){
                    scroll_class = scroll_class.split(" ");
                    $.each(scroll_class, function(index,classname){
                        var position    = jQuery( classname ).position();
                        if( typeof position !== 'undefined' && position !== ' ' ) {
                            is_on_screen = ConvertPlus._cp_modal_isOnScreen( jQuery( classname ) );
                            if(display && is_on_screen){
                               ConvertPlus._displayPopup(md,type,id);
                           }
                       }
                   });
                    invoke = false;
                }

                if(display && invoke){
                   ConvertPlus._displayPopup(md,type,id);
               }
           }

           if( dev_mode == "enabled" ){
               ConvertPlus._removeCookie(cookieName);
           }
       },
        /**
         * Idle event for modules.
         * @return nothing
         */
         _CpidleEvent: function(){
            var md      = module,
            type    = module_type,
            id      = style,
            display = false;
            if( ConvertPlus._canCpShow()) {
                display = ( ConvertPlus._isOtherPopupOpen(type) );
                if(display && typeof inactive_time !== "undefined"){
                    ConvertPlus._displayPopup(md,type,id);
                }
            }
        },
        /**
         * Display popup.
         * @return nothing
         */
         _displayPopup: function(md,type,id){
            var styleArray = Array();
            var autoplay = isAutoPlay;

            var is_ipression_counted = ConvertPlus._getCookie('cp-impression-added-for'+id);
            if( type == 'modal'){

                $(window).trigger('modalOpen',[md]);
                $(document).trigger('resize');
                var frame = md.find('.cp-youtube-frame');
                var frame_length = frame.length;
                var lazy_video = false;
                if( frame_length >= 1){
                     isAutoPlay = md.find('.cp-youtube-frame').attr('data-autoplay') || '0';
                }else{
                    lazy_video = true;
                    isAutoPlay = md.find('.cp-youtube-continer').attr('data-autoplay') || '0';
                }

                if( isAutoPlay == '1' ) {    
                    if(lazy_video){
                        md.find('.cp-youtube-continer').trigger('click', [isAutoPlay]);
                    } else{                          
                        ConvertPlus._cpExecuteVideoAPI(md,'play');
                    }
                }

                md.addClass('cp-open cp-visited-popup');
                if( !is_ipression_counted && !md.hasClass( 'cp_impression_counted' ) && !md.hasClass( 'cp-disabled-impression' ) ){
                    if( styleArray.indexOf(id) == -1){
                        styleArray.push( id );
                    }
                    md.addClass( 'cp_impression_counted' );
                    ConvertPlus._createCookie('cp-impression-added-for'+id,true,1);
                    if(styleArray.length !== 0 ) {
                        ConvertPlus.update_impressions(styleArray);
                    }
                }
                //  Show YouTube CTA form
                ConvertPlus._youtube_show_cta(md);

            }else if(type == 'info-bar' ){

                if( !is_ipression_counted && !md.hasClass('cp_impression_counted') && !md.hasClass( 'cp-disabled-impression' ) )
                {
                    if( styleArray.indexOf(id) == -1){
                        styleArray.push( id );
                    }
                    if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {
                        ConvertPlus.update_impressions(styleArray);

                        ConvertPlus._createCookie('cp-impression-added-for'+id,true,1);

                        jQuery("[data-info_bar-style="+style+"]").each(function(e) {
                            jQuery(this).addClass('cp_impression_counted');
                        });
                    }
                }
                if( md.hasClass("cp-pos-top")){
                    if( jQuery("body").hasClass("admin-bar") ){
                        ab_height = jQuery("#wpadminbar").outerHeight();
                        md.css("top", ab_height+"px");
                    }
                } else {
                    cp_height  = md.find(".cp-info-bar-body").outerHeight();
                    md.css("min-height",cp_height+"px");
                }

                md.addClass('ib-display');
                jQuery(document).trigger('resize');
                jQuery(document).trigger('infobarOpen',[md]);
                setTimeout( function(){
                    anim = md.find(".cp-submit").data("animation");
                    md.find(".cp-submit").addClass(anim);
                }, 2000 );

            }else if( type =='slide_in' ){

                ConvertPlus._adjustToggleButton(slidein_container);
                jQuery(window).trigger('slideinOpen',[md]);

                //jQuery(document).trigger('resize');
                md.addClass('si-open');

                if( !is_ipression_counted && !md.hasClass('cp_impression_counted') && !md.hasClass( 'cp-disabled-impression' ) ) {
                    if( styleArray.indexOf(id) == -1){
                        styleArray.push( id );
                    }
                    if( styleArray.length !== 0 && typeof toggle_visible == 'undefined' ) {

                        ConvertPlus.update_impressions(styleArray);

                        ConvertPlus._createCookie('cp-impression-added-for'+id,true,1);

                        jQuery("[data-slidein-style="+style+"]").each(function(e) {
                            jQuery(this).addClass('cp_impression_counted');
                        });
                    }
                }
            }

        },
        /**
         * Update impression for modules.
         * @return nothing
         */
         update_impressions: function(styles){  
            var opt   = opt;
            if( ajax_run == true ){
                nounce = jQuery(".cp-impress-nonce").val();
                data = {action:'smile_update_impressions',impression:true,styles:styles,option:'smile_modal_styles',security:nounce};
                jQuery.ajax({
                    url:smile_ajax.url,
                    data: data,
                    type: "POST",
                    dataType:"HTML",
                    security:jQuery(".cp-impress-nonce").val(),
                    beforeSend: function(result){
                        ajax_run = false;
                    }
                });
            }else{               
                setTimeout(function() {
                    nounce = jQuery(".cp-impress-nonce").val();
                    data = {action:'smile_update_impressions',impression:true,styles:styles,option:'smile_modal_styles',security:nounce};
                    jQuery.ajax({
                        url:smile_ajax.url,
                        data: data,
                        type: "POST",
                        dataType:"HTML",
                        security:jQuery(".cp-impress-nonce").val(),
                        beforeSend: function(result){// do your stuff
                            ajax_run = false;
                        }
                    });
                },  2000);               
            }
        },
        /**
         * Check if another popup is ope or not
         * @return nothing
         */
         _isOtherPopupOpen: function( type ) {
            var condition = '';
            if( type == 'modal'){
                condition = ( ( $(".cp-open").length <= 0 ) && !modal.hasClass("cp-visited-popup") );
            }else if(type == 'info-bar'){
                condition = (jQuery(".ib-display").length <= 0);
            }else if(type == 'slide_in'){
                var float_count = jQuery(".si-open").find(".cp-slide-in-float-on").length,
                open_count  = 1;
                if( float_count == 0){
                    var open_count = 0;
                }
                condition = (jQuery(".si-open").length <= open_count && jQuery(".si-open").find(".cp-slide-in-float-on").length <= 1 && !slidein.hasClass('cp_impression_counted'));
            }
            return ( condition )  ;
        },

        /**
         * Check visibility of module
         * @return {[type]} [description]
         */
         _canCpShow: function(){

            cookie     = ConvertPlus._getCookie(cookieName);
            tmp_cookie = ConvertPlus._getCookie(temp_cookie);

            if( dev_mode == "enabled") {
                if( tmp_cookie ) {
                    cookie = true;
                } else {
                    cookie = ConvertPlus._getCookie(cookieName);
                }
            } else {
                cookie = ConvertPlus._getCookie(cookieName);
            }

            if( module_type == 'slide_in' ){
                if( dev_mode == "enabled") {
                    ConvertPlus._removeCookie(cookieName+'-conversion');
                }else{
                    if( cookie && slidein.hasClass('cp-always-minimize-widget')){
                        slidein.addClass('cp-minimize-widget');
                        cookie = false;
                    }
                    var conversion_cookies  = ConvertPlus._getCookie(cookieName+'-conversion');
                    if(conversion_cookies && slidein.hasClass('cp-always-minimize-widget')){
                      cookie = true;
                  }
              }
          }

          if( cookie == null ){
            cookie = false;
        }

        if( typeof referrer !== "undefined" && referrer !== "" ){
         referred = ConvertPlus._isReferrer( referrer, doc_ref, ref_check );
     }

     is_open = ConvertPlus._isOtherPopupOpen(module_type);
     return ( !cookie && scheduled && referred && is_open);
 },
        /**
         * Check fullscreen popup
         * @return nothing
         */
         _isWindowSize: function( type ) {
            return ( type.hasClass('cp-window-size') );
        },
         /**
         * Remove/get/create cookies
         * @param string name of the coockies.
         * @return Boolean
         */
         _removeCookie: function( name ){
            ConvertPlus._createCookie(name, '', -1);
        },
        _createCookie: function(name, value, days){
            var expires = '';
            // If we have a days value, set it in the expiry of the cookie.
            if ( days ) {
                var date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                var expires = '; expires=' + date.toGMTString();
            }
            // Write the cookie.
            document.cookie = name + '=' + value + expires + '; path=/';
        },
        _getCookie: function(name){
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
        },
        _setPageCookie:function(cookieName, cookieValue, nDays){
            var today  = new Date(),
            expire = new Date();
            if (nDays === null || nDays === 0) {
                nDays = 1;
            }
            expire.setTime(today.getTime() + 3600000 * 24 * nDays);

            document.cookie = cookieName + "=" + escape(cookieValue) + ";expires=" + expire.toGMTString();

        },
        _getPageCookie: function(cookieName){
            var theCookie = " " + document.cookie,
            ind = theCookie.indexOf(" " + cookieName + "=");
            if (ind === -1) { ind = theCookie.indexOf(";" + cookieName + "="); }
            if (ind === -1 || cookieName === "") { return ""; }
            var ind1 = theCookie.indexOf(";", ind + 1);
            if (ind1 === -1) { ind1 = theCookie.length; }
            return unescape(theCookie.substring(ind + cookieName.length + 2, ind1));
        },
        /**
         * Scheduled or not?
         * @return {Boolean} [description]
         */
         _isScheduled: function( modal ){

            y          = new Date(),
            timestring = modal.data('timezonename'),
            tzoffset   = modal.data('tz-offset'),
            gtime      = y.toGMTString(),
            ltime      = y.toLocaleString(),
            date       = new Date(),
            utc        = date.getTime() + ( date.getTimezoneOffset() * 60000 ),// turn date to utc
            new_date   = new Date( utc + ( 3600000 * tzoffset ) ),// set new Date object
            scheduled  = modal.data('scheduled');

            if( typeof scheduled !== "undefined" && scheduled == true ) {

                var start = modal.data('start'),
                end   = modal.data('end'),
                start = Date.parse(start),
                end   = Date.parse(end);

                if( timestring == 'system' ) {
                    ltime = Date.parse(date);
                } else {
                    ltime = Date.parse(new_date);
                }

                if( ltime >= start && ltime <= end ) {
                    return true;
                } else {
                    return false;
                }

            } else {
                return true;
            }
        },
        /**
         * Referere detection
         * @param  {[type]}  referrer  [description]
         * @param  {[type]}  doc_ref   [description]
         * @param  {[type]}  ref_check [description]
         * @return {Boolean}           [description]
         */
         _isReferrer: function( referrer, doc_ref, ref_check ){
            var display   = false;

            if( typeof doc_ref !== 'undefined' ){
                var doc_refs   = ConvertPlus._stripTrailingSlash( doc_ref.replace(/.*?:\/\//g, "") ),
                referrers      = referrer.split( ",");                        

                jQuery.each( referrers, function(i, url ){

                    //if( typeof doc_refs !== 'undefined' ){

                        var url   = ConvertPlus._stripTrailingSlash( url ),
                        doc_ref   = doc_refs.replace("www.",""),
                        dr_arr    = doc_ref.split("."),
                        ucount    = url.match(/./igm).length,
                        url_arr   = '',
                        _domain   = '',
                        dr_domain = dr_arr[0];
                        url = ConvertPlus._stripTrailingSlash( url.replace(/.*?:\/\//g, "") );
                        url = url.replace("www.","");
                        url_arr = url.split("*");

                        if(doc_ref.indexOf("reddit.com") !== -1 ){
                            doc_ref = 'reddit.com';
                        }else if(doc_ref.indexOf("t.co") !== -1 ){
                            doc_ref = 'twitter.com';
                        }

                        if( doc_ref.indexOf("plus.google.co") !== -1 ){
                            doc_ref = 'plus.google.com';
                        } else if( doc_ref.indexOf("google.co") !== -1 ) {
                            doc_ref = 'google.com';
                        }

                        _domain = url_arr[0];
                        _domain = ConvertPlus._stripTrailingSlash( _domain );

                        if( ref_check =="display" ) {
                            if( url.indexOf('*') !== -1 ) {
                                if( _domain == doc_ref ){
                                    display = true;
                                    return false;
                                } else if( doc_ref.indexOf( _domain ) !== -1 ){
                                    display = true;
                                    return false;
                                } else {
                                    display = false;
                                    return false;
                                }
                            } else if( url == doc_ref ){
                                display = true;
                                return false;
                            } else if( doc_ref.indexOf( _domain ) !== -1 ){
                                display = true;
                                return false;
                            } else {
                                display = false;
                            }
                        } else if( ref_check == "hide" ) {
                            if( url.indexOf('*') !== -1 ) {
                                if( _domain == doc_ref ){
                                    display = false;
                                    return false;
                                } else if( doc_ref.indexOf( _domain ) !== -1 ){
                                    display = false;
                                    return false;
                                } else {
                                    display = true;
                                    return false;
                                }
                            } else if( url == doc_ref ){
                                display = false;
                                return false;
                            } else if( doc_ref.indexOf( _domain ) !== -1 ){
                                display = false;
                                return false;
                            } else {
                                display = true;
                            }
                        }
                    //}//
                });

            }
            return display;
        },
        /**
         * [_stripTrailingSlash description]
         * @param  {[type]} url [description]
         * @return {[type]}     [description]
         */
         _stripTrailingSlash: function( url ){
            if( url.substr(-1) === '/') {
                return url.substr(0, url.length - 1);
            }
            return url;
        },
        /**
         * [_getPrioritized modal]
         * @return {[type]} [description]
         */
         _getPrioritized: function(){
            var modal = 'none';
            jQuery(".cp-global-load").each(function(t,v) {
                var class_id = jQuery(this).data("class-id"),
                hasClass = jQuery(this).hasClass("priority_modal");

                if( hasClass ){
                    modal = jQuery('.'+class_id);
                    return modal;
                }
            });
            return modal;
        },

        /**
         * Youtube API execution
         * @param  {[type]} obj    [description]
         * @param  {[type]} status [description]
         * @return {[type]}        [description]
         */
         _cpExecuteVideoAPI: function( obj, status ){
            
            var iframes = obj.find('iframe');
            jQuery.each(iframes, function( index, frame ){
                var  src = frame.src;
                if( isAutoPlay == '1' ){
                    src = frame.getAttribute('data_y_src');                   
                    if( src == '' || src == null ){   
                        src = frame.src;
                    }
                } 
                
                // Youtube API
                var youtube = src.search('youtube.com');

                if( Youtube_on_tab == true ){
                    status = 'pause';
                }

                if( youtube >= 1 ){
                    var youtube_frame = frame.contentWindow;
                    if( status == 'play' ){
                        youtube_frame.postMessage('{"event":"command","func":"playVideo","args":""}','*');
                        if(iframes.hasClass('cp-youtube-frame')){
                            iframes.removeAttr('data_y_src');
                            iframes.attr("allow","autoplay");
                            iframes.attr("src", src.replace("autoplay=0", "autoplay=1"));
                        }
                    } else {   
                       if( isAutoPlay == '1' ){
                            iframes.attr("data_y_src",src );  
                            iframes.removeAttr('src');
                        }  
                        iframes.removeAttr("allow");   
                        iframes.attr("data_y_src", src.replace("autoplay=0", "autoplay=0"));
                        iframes.removeAttr('src');
                        youtube_frame.postMessage('{"event":"command","func":"pauseVideo","args":""}','*');
                        youtube_frame.postMessage('{"event":"command","func":"stopVideo","args":""}','*');
                    }
                }
                // Vimeo API
                var vimeo = src.search('vimeo.com');
                if( vimeo >= 1 ){
                    var vimeo_frame = frame.contentWindow;
                    if( status == 'play' ){
                        vimeo_frame.postMessage('{"method":"play"}','*');
                    } else {
                        vimeo_frame.postMessage('{"method":"pause"}','*');
                    }
                }
            });
        },
        /**
         * [_youtube_show_cta description]
         * @param  {[type]} modal [description]
         * @return {[type]}       [description]
         */
         _youtube_show_cta: function( modal ){
            var cp_form = modal.find('.cp-form-container');
            if( modal.find('.cp-modal-body').hasClass('cp-youtube') && !cp_form.hasClass('cp-youtube-cta-none') ) {
                var cta_delay   = cp_form.attr('data-cta-delay') || '';

                if( typeof cta_delay != '' && cta_delay != null ) {

                    cta_delay = parseInt(cta_delay * 1000);
                    cp_form.slideUp('500');
                    setTimeout(function() {

                        //  show CTA after complete delay time
                        cp_form.slideDown('500');


                    }, cta_delay );
                }
            }
        },
        /**
         * [_check_responsive_font_sizes description]
         * @return {[type]} [description]
         */
         _check_responsive_font_sizes: function(){
            //  Apply font sizes
            jQuery(".cp_responsive[data-font-size-init]").each(function(index, el) {

                var p = jQuery(el),
                data = jQuery( this ).html();

                if ( data.toLowerCase().indexOf("cp_font") >= 0 && data.match("^<span") && data.match("</span>$") ) {
                    p.addClass('cp-no-responsive');
                } else {
                    p.removeClass('cp-no-responsive');
                }
            });
        },
        /**
         * Name:_count_inline_impressions Count inline impression for modules.
         * @return {[type]} [description]
         */
         _count_inline_impressions : function( modal ){

            var type = modal.data("module-type"),
            main_class = '';

            if( type == 'modal'){
                main_class  = '.cp-modal-inline-end' ;
            }else if( type == 'info-bar'){
                main_class = '.cp-info_bar-inline-end' ;
            }else if( type == 'slide_in'){
                main_class = '.cp-slide_in-inline-end';
            }

            jQuery(main_class).each(function(e) {
                var elem                 = jQuery(this),
                is_visible           = ConvertPlus._isScrolledIntoStyleView(elem),
                style_id             = elem.data('style'),
                is_ipression_counted = ConvertPlus._getCookie('cp-impression-added-for'+style_id);

                if( type == 'modal'){
                   var condition   = ( !jQuery(".cp-overlay[data-modal-style="+style_id+"]").hasClass('cp_impression_counted') && !jQuery(".cp-overlay[data-modal-style="+style_id+"]").hasClass('cp-disabled-impression') );
                   check_class = ".cp-overlay[data-modal-style="+style_id+"]";
               }else if( type == 'info-bar'){
                  var  condition = ( !jQuery("[data-info_bar-style="+style_id+"]").hasClass('cp_impression_counted') && !jQuery("[data-info_bar-style="+style_id+"]").hasClass( 'cp-disabled-impression' ) ),
                  check_class = "[data-info_bar-style="+style_id+"]";
              }else if( type == 'slide_in'){
                var  condition = ( !jQuery("[data-slidein-style="+style_id+"]").hasClass('cp_impression_counted') && !jQuery("[data-slidein-style="+style_id+"]").hasClass( 'cp-disabled-impression' ) ),
                check_class = "[data-slidein-style="+style_id+"]";
            }

            if( is_visible & !is_ipression_counted ) {
                var styleArray = Array();
                if( condition ) {
                    styleArray.push(style_id);
                    ConvertPlus.update_impressions(styleArray);
                    ConvertPlus._createCookie('cp-impression-added-for'+style_id,true,1);
                }
                jQuery(check_class).each(function() {
                    elem.addClass('cp_impression_counted');
                });
            }
        });
        },

        /**
         * _close_button_tootip style for Close tooltip.
         * @return {[type]} [description]
         */
         _close_button_tootip: function(){

            if(  module_type == 'modal' && module_type!=='undefined' ){
                jQuery(".cp-overlay").each(function(t) {
                    var $this                = jQuery(this),
                    classname            = $this.find(".cp-tooltip-icon").data('classes'),
                    closeid              = $this.find(".cp-tooltip-icon").data('closeid'),
                    tcolor               = $this.find(".cp-tooltip-icon").data("color"),
                    tbgcolor             = $this.find(".cp-tooltip-icon").data("bgcolor"),
                    fontfamily           = $this.find(".cp-tooltip-icon").data("font-family"),
                    modalht              = $this.find(".cp-modal-content").height(),
                    vw                   = jQuery(window).width(),
                    id                   = $this.data("modal-id"),
                    new_tooltip_position = '' ;

                    if( $this.find(".cp-overlay-close").hasClass('cp-adjacent-left') ){
                        new_tooltip_position ='right';
                    }else if( $this.find(".cp-overlay-close").hasClass('cp-adjacent-right')){
                     new_tooltip_position ='left';
                 }

                 $this.find(".cp-tooltip-icon").removeAttr('data-position');
                 $this.find(".cp-tooltip-icon").attr("data-position" , new_tooltip_position );

                 var position = new_tooltip_position,
                 offsetval = '20';

                 jQuery("body").addClass('customize-support');

                 if( typeof classname !=='undefined'){
                    jQuery("."+classname).remove();
                }

                jQuery('head').append('<style class="cp-tooltip-css '+classname+'">.customize-support .tip.'+classname+'{color: '+tcolor+';background-color:'+tbgcolor+';border-color:'+tbgcolor+';font-family:'+fontfamily+'; }</style>');

                if( position == 'left' ){
                    jQuery('head').append('<style class="cp-tooltip-css '+classname+'">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before {border-left-color: '+tbgcolor+' ;border-top-color:transparant}</style>');
                }else if( position == 'right' ) {
                    jQuery('head').append('<style class="cp-tooltip-css '+classname+'">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before{border-right-color: '+tbgcolor+';border-left-color:transparent }</style>');
                }else {
                    jQuery('head').append('<style class="cp-tooltip-css '+classname+'">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before{border-top-color: '+tbgcolor+';border-left-color:transparent }</style>');
                }
            });
            }else if(module_type == 'slide_in' && module_type!=='undefined' ){
                var  classname = module.find(".has-tip").data('classes'),
                closeid   = module.find(".has-tip").data('closeid'),
                tcolor    = module.find(".has-tip").data("color"),
                tbgcolor  = module.find(".has-tip").data("bgcolor"),
                slideinht = module.find(".cp-slidein-content").height(),
                vw        = jQuery(window).width(),
                position  = module.find(".has-tip").data("position"),
                offsetval = 20;

                jQuery("body").addClass('customize-support');

                jQuery('head').append('<style class="cp-tooltip-css">.customize-support .tip.'+classname+'{color: '+tcolor+';background-color:'+tbgcolor+';font-size:13px;border-color:'+tbgcolor+' }</style>');
                if( position == 'left' ){
                    jQuery('head').append('<style class="cp-tooltip-css">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before {border-left-color: '+tbgcolor+' ;border-top-color:transparent}</style>');
                } else if( position == 'right' ){
                    jQuery('head').append('<style class="cp-tooltip-css">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before{border-right-color: '+tbgcolor+';border-left-color:transparent }</style>');
                }else {
                    jQuery('head').append('<style class="cp-tooltip-css">.customize-support .tip.'+classname+'[class*="arrow"]:before , .'+classname+'[class*="arrow"]:before{border-top-color: '+tbgcolor+';border-left-color:transparent }</style>');
                }
            }

        },
        /**
         * check if element is visible in view port
         * @param  {[type]}  elem [description]
         * @return {Boolean}      [description]
         */
         _isScrolledIntoStyleView: function(elem){
            var $elem = elem,
            $window = $(window),
            docViewTop = $window.scrollTop(),
            docViewBottom = docViewTop + $window.height(),
            elemTop = $elem.offset().top,
            elemBottom = elemTop + $elem.height();

            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        },
        /**
         * check if element is visible in screen
         * @param  {[type]}  elem [description]
         * @return {Boolean}      [description]
         */
         _cp_modal_isOnScreen: function( obj ){
            var win = $(window);
            var viewport = {
                top : win.scrollTop(),
                left : win.scrollLeft()
            };
            viewport.right = viewport.left + win.width();
            viewport.bottom = viewport.top + win.height();

            var bounds = obj.offset();
            bounds.right = bounds.left + obj.outerWidth();
            bounds.bottom = bounds.top + obj.outerHeight();
            return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
        },
         /**
         * check info bar position.
         * @param  {[type]}  elem [description]
         * @return {Boolean}      [description]
         */
         _infoBarPos:function( cp_info_bar ){
            if( cp_info_bar.hasClass("cp-pos-top") ) {
                cp_info_bar.css('top','0');
            } else {

                if( cp_info_bar.hasClass("ib-fixed") ){
                    cp_info_bar.css('top','auto');
                } else {
                    var toggle = cp_info_bar.data("toggle"),
                    body_ht = jQuery("body").parent("html").height(),
                    toggle_ht = cp_info_bar.find('.cp-ifb-toggle-btn').outerHeight(),
                    cp_height  = cp_info_bar.find(".cp-info-bar-body").outerHeight();

                    if( toggle == 1 ) {
                     body_ht = body_ht - cp_height + toggle_ht;
                 }
                 if( !cp_info_bar.hasClass('cp-info-bar-inline') ) {
                    cp_info_bar.css('top',body_ht+'px');
                }
                cp_info_bar.css("min-height",cp_height+"px");
            }
        }
        if( jQuery("body").hasClass("admin-bar") ){
            if( cp_info_bar.hasClass("cp-pos-top")){
                var ab_height = jQuery("#wpadminbar").outerHeight();
                if( !cp_info_bar.hasClass('cp-info-bar-inline') ) {
                    cp_info_bar.css("top", ab_height+"px");
                }
            }
        }
    },
        /**
         * Style for fullscreen popup
         * @return nothing
         */
         _windowSize:function(){
           var cp_content_container   = this.find(".cp-content-container"),
           cp_info_bar            = this.find(".cp-info-bar"),
           cp_info_bar_content    = this.find(".cp-info-bar-content"),
           cp_info_bar_body       = this.find(".cp-info-bar-body");
           cp_info_bar.removeAttr('style');
           cp_info_bar_content.removeAttr('style');
           cp_content_container.removeAttr('style');
           cp_info_bar_body.removeAttr('style');
           var ww = jQuery(window).width() + 30;
           var wh = jQuery(window).height();
           jQuery(this).find("iframe").css("width",ww);

           cp_content_container.css({'max-width':ww+'px','width':'100%','height':wh+'px','padding':'0','margin':'0 auto'});
           cp_info_bar_content.css({'max-width':ww+'px','width':'100%'});
           cp_info_bar.css({'max-width':ww+'px','width':'100%','left':'0','right':'0'});
           cp_info_bar_body.css({'max-width':ww+'px','width':'100%','height':wh+'px'});
       },
        /**
         * Set infobar height
         * @param  {[type]} t [description]
         * @return nothing
         */
         _cp_set_ifb_ht: function(t){
            var h   = parseInt(jQuery( t ).outerHeight()),
            vw  = jQuery(window).outerWidth(),
            ua = window.navigator.userAgent,
            msie = 0;
            if( typeof ua !== 'undefined' ){
                msie = ua.indexOf("MSIE ");
            }
            //  is IE browser?
            if ( msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./) ) {
                if( vw > 768 ) {
                    jQuery( t ).find('.cp-info-bar-body').css({ 'height': h+'px' });
                } else {
                    jQuery( t ).find('.cp-info-bar-body').css({ 'height': 'auto' });
                }
            }
        },
        /**
         * Color for inline list tag from modules.
         * @param  {[type]} t [description]
         * @return nothing
         */
         _cp_ifb_color_for_list_tag: function(t){
            var moadal_style    = jQuery(t).data('class');
            jQuery(t).find("li").each(function() {
                if(jQuery(this).parents(".cp_social_networks").length == 0){
                    var $this = jQuery(this),
                    parent_li   = $this.parents("div").attr('class').split(' ')[0],
                    cnt         = $this.index()+1,
                    font_size   = $this.find(".cp_font").css("font-size"),
                    color       = $this.find("span").css("color"),
                    list_type   = $this.parent(),
                    list_type   = list_type[0].nodeName.toLowerCase(),
                    style_type  = '',
                    style_css   = '';

                //apply style type to list
                if( list_type == 'ul' ){
                    style_type = $this.closest('ul').css('list-style-type');
                    if( style_type == 'none' ){
                        $this.closest('ul').css( 'list-style-type', 'disc' );
                    }
                } else {
                    style_type = $this.closest('ol').css('list-style-type');
                    if( style_type == 'none' ){
                        $this.closest('ol').css( 'list-style-type', 'decimal' );
                    }
                }
                //apply color to list
                jQuery(this).find("span").each(function(){
                    var spancolor = jQuery(this).css("color");
                    if(spancolor.length > 0){
                        color = spancolor;
                    }
                });

                var font_style ='';
                jQuery(".cp-li-color-css-"+cnt).remove();
                jQuery(".cp-li-font-css-"+cnt).remove();
                if(font_size){
                   font_style = 'font-size:'+font_size;
                   jQuery('head').append('<style class="cp-li-font-css'+cnt+'">.'+moadal_style+' .'+parent_li+' li:nth-child('+cnt+'){ '+font_style+'}</style>');
               }
               if(color){
                  jQuery('head').append('<style class="cp-li-color-css'+cnt+'">.'+moadal_style+' .'+parent_li+' li:nth-child('+cnt+'){ color: '+color+';}</style>');
              }
          }

      });
        },
        /**
         * Push page down for info bar
         * @param  {[type]} md [description]
         * @return Noting
         */
         _apply_push_page_down:function( md,resize){
            setTimeout(function() {
                var has_toggle_btn  = md.data('toggle'),
                toggle_visible = md.data('toggle-visible') || null,
                toggle = false;
                ConvertPlus._push_page_down( md, toggle, toggle_visible );
            }, 300);
        },
        _push_page_down:function( info_bar, toggle, toggle_visible ){          
            var page_down = info_bar.data('push-down') || null,
            animate_push_page = info_bar.data('animate-push-page'),
            cp_top_offset_container = jQuery("#cp-top-offset-container").val();

            if( page_down && !toggle_visible ) {
                if( info_bar.hasClass("cp-pos-top") ){
                    var cp_push_page_css = '';
                    var push_margin = ConvertPlus._cal_top_margin_push_down(info_bar,animate_push_page, toggle);
                    var apply_css = isNaN(parseFloat(push_margin)); 
                    if( !apply_css ){                    
                        if( animate_push_page == 1 )  {
                            if( cp_top_offset_container == '' ) {                          
                                jQuery("body").removeClass("cp_push_no_scroll").addClass("cp_push_scroll_animate");
                                cp_push_page_css = "body.cp_push_scroll_animate{margin-top:"+push_margin+"px!important}";
                            } else {
                                cp_push_page_css = cp_top_offset_container+"{margin-top:"+push_margin+"px}";
                            }
                        }else {
                            if( cp_top_offset_container == '' ) {
                             jQuery("body").removeClass("cp_push_scroll_animate").addClass("cp_push_no_scroll");
                             cp_push_page_css = "body.cp_push_no_scroll{margin-top:"+push_margin+"px!important}";
                         } else {
                             cp_push_page_css = cp_top_offset_container+"{margin-top:"+push_margin+"px}";
                         }
                     }
                     $('.cp-push-page-css').remove();
                     $('head').append('<style class="cp-push-page-css">'+cp_push_page_css+'</style>');
                 }
             }
         }
     },
     _cal_top_margin_push_down: function( info_bar,animate_push_page, toggle ){
            var cp_push_down_support_container = jQuery("#cp-push-down-support").val(); // Retrieve class / ID which user enter in &author setting
            var cp_top_offset_container = jQuery("#cp-top-offset-container").val();
            var wpadminbar = jQuery("#wpadminbar").outerHeight(); // Calculate WP admin Bar Height
            var ib_height = info_bar.outerHeight(); // Calculate Info Bar Height

            if( cp_top_offset_container == '' && rs_flag <= 1 ) {               
                var site_offset = jQuery('body').offset().top;
                var offset_def_settings = {
                    margin_top: jQuery('body').css('margin-top'),
                    top:  jQuery('body').css('top'),
                };
            } else {               
                if( jQuery(cp_top_offset_container).length > 0 ) {
                    var site_offset = jQuery(cp_top_offset_container).offset().top;
                    var offset_def_settings = {
                        margin_top: jQuery(cp_top_offset_container).css('margin-top'),
                        top:  jQuery(cp_top_offset_container).css('top'),
                    };
                }
            }

            if( typeof offset_def_settings !== 'undefined') {
                var seetings_string = JSON.stringify(offset_def_settings);
                jQuery("#cp-top-offset-container").attr("data-offset_def_settings", seetings_string  );
            }

            if( typeof site_offset == 'undefined' ){
                site_offset = 0;
            }

            if( typeof wpadminbar == 'undefined' ){
                wpadminbar = 0;
            }

            var push_down_top = (ib_height + site_offset) - wpadminbar,
                push_down_top_support = ib_height + site_offset,
                cp_push_down_support_ht = jQuery("#cp-push-down-support").outerHeight(), // Calculate height of user entered fixed class / ID
                cp_push_down_support_htop = push_down_top_support - 0;

                if( toggle ) {
                    cp_push_down_support_htop = wpadminbar + ib_height;
                    push_down_top = ib_height;
                }
                if( animate_push_page == 1 ) {
                    jQuery("#cp-push-down-support").stop().animate({ 'top': cp_push_down_support_htop + 'px' }, 1200 );
                } else {
                    jQuery("#cp-push-down-support").css( 'top', cp_push_down_support_htop + 'px' );
                }
                return push_down_top;
            },
        /**
         * Check toggele functionality
         * @return nothing.
         */
         _cp_ifb_toggle:function(){
            jQuery(".cp-info-bar").each(function(index, el) {

                var info_bar = jQuery( el );
                info_bar.find( ".cp-ifb-toggle-btn" ).click(function() {

                    var cp_ifb_toggle_btn   = jQuery(this),
                    cp_info_bar         = cp_ifb_toggle_btn.closest('.cp-info-bar'),
                    btn_animation       = 'smile-slideInDown',
                    exit_animation      = cp_info_bar.data("exit-animation"),
                    entry_animation     = cp_info_bar.data("entry-animation"),
                    cp_info_bar_body    = cp_info_bar.find(".cp-info-bar-body"),
                    toggle_visibility   = cp_info_bar.data('toggle-visible'),
                    is_imp_added       = cp_info_bar.data('impression-added'),
                    style_id           = cp_info_bar.data('info_bar-id');

                    if( toggle_visibility == true ) {
                        if( typeof is_imp_added == 'undefined' && !cp_info_bar.hasClass( 'cp-disabled-impression' ) ) {
                            var styleArray = [style_id];
                            ConvertPlus.update_impressions( styleArray );
                            cp_info_bar.data('impression-added','true');
                        }
                    }

                    var toggle = false,
                    toggle_visible = null;

                    ConvertPlus._push_page_down( cp_info_bar, toggle, toggle_visible );

                    cp_info_bar.removeClass( entry_animation );
                    cp_info_bar.removeClass( exit_animation );

                    if( cp_info_bar.hasClass('cp-pos-bottom') ) {
                        btn_animation = 'smile-slideInUp';
                    }

                    var  cp_info_bar_class    = cp_info_bar.attr('class');

                    cp_ifb_toggle_btn.removeClass('cp-ifb-show smile-animated '+ btn_animation +'');
                    cp_info_bar.attr('class',cp_info_bar_class);
                    cp_info_bar.attr('class', cp_info_bar_class + ' smile-animated ' + entry_animation);
                    cp_info_bar.removeClass('cp-ifb-hide');

                    cp_ifb_toggle_btn.addClass('cp-ifb-hide');
                    cp_info_bar_body.addClass('cp-flex');
                    cp_info_bar.find( ".ib-close" ).css({
                        'visibility': 'visible'
                    });

                    var toggle = true;
                    ConvertPlus._push_page_down( info_bar, toggle );

                });

            //click of close button
            info_bar.find( ".ib-close" ).click(function() {

                var cp_info_bar         =   jQuery(this).parents(".cp-info-bar"),
                cp_ifb_toggle_btn   =   cp_info_bar.find(".cp-ifb-toggle-btn"),
                cp_info_bar_body    =   cp_info_bar.find(".cp-info-bar-body"),
                btn_animation       =   'smile-slideInDown',
                exit_animation      =   cp_info_bar.data("exit-animation"),
                entry_animation     =   cp_info_bar.data("entry-animation"),
                data_toggle         =   cp_info_bar.data("toggle"),
                form                =   cp_info_bar.find('.form-main').attr('class');

                if(data_toggle == 1){

                        //  Toggle button animation class
                        if(cp_info_bar.hasClass('cp-pos-bottom')){
                           btn_animation = 'smile-slideInUp';
                       }

                       cp_info_bar.removeClass(entry_animation);
                       var  cp_info_bar_class   = cp_info_bar.attr('class');
                       cp_info_bar.attr('class', cp_info_bar_class + ' ' + exit_animation);

                       setTimeout(function() {
                            //  Toggle button animation
                            cp_ifb_toggle_btn.removeClass('cp-ifb-hide');
                            cp_ifb_toggle_btn.addClass('cp-ifb-show smile-animated '+btn_animation +'');
                            cp_info_bar.removeClass('smile-animated');
                            cp_info_bar.removeClass(exit_animation);
                            cp_info_bar.addClass('cp-ifb-hide');
                            cp_info_bar_body.removeClass('cp-flex');
                            cp_info_bar.find( ".ib-close" ).css({
                                'visibility': 'hidden'
                            });
                            if(typeof form !== 'undefined'){
                                cp_info_bar.find('.smile-optin-form')[0].reset();
                                cp_info_bar.find(".cp-form-processing-wrap").css('display', 'none');
                                cp_info_bar.find(".cp-form-processing").removeAttr('style');
                                cp_info_bar.find(".cp-msg-on-submit").removeAttr('style');
                                cp_info_bar.find(".cp-m-success").remove();
                                cp_info_bar.find(".cp-m-error").remove();
                            }
                        }, 1500 );
                   }
               });

        });
},
        /**
         * set toggle button position.
         * @param  {[type]} container [description]
         * @return {[type]}           [description]
         */
         _adjustToggleButton:function(container){
            if( container.find('.cp-slidein-toggle').length > 0 ) {
                var slide_in_head = container.find('.cp-slidein-head').outerHeight();
                container.find('.cp-animate-container').css( { "height":slide_in_head + 'px', "opacity":"0" } );
            }
        },
    };

    /* Load after x sec Event */
    $( window ).on( 'load', function() {

        $(".cp-global-load").each(function(event) {
            var inactive_time    = jQuery(this).data('inactive-time');
            if( typeof inactive_time !== "undefined" ) {
                inactive_time = inactive_time * 1000;
                jQuery( document ).idleTimer( {
                    timeout: inactive_time,
                    idle: false
                });
            }
           // var load_time    = jQuery(this).data("onload-delay");
            //if( load_time !== 'undefined' && load_time !== '' ){
                ConvertPlus.init( event, $( this ), 'load' );
            //}

            if(typeof window.orientation !== 'undefined'){
                Youtube_on_tab = true;
            }
        });

        // z-index fixes for manual display
        $('.cp-modal-global').each(function(){
            var style_id = $(this).data("modal-style");
            if( typeof style_id!=='undefined' && style_id !== '' ){
                var container = jQuery(".cp-modal-popup-container."+style_id);
                if( !container.hasClass('cp-inline-modal-container')){
                    container.appendTo(document.body);
                    $(this).appendTo(document.body);
                }
            }
        });

        jQuery("html").addClass('cp-overflow-hidden');

        var custom_uniqueNames = [];
        jQuery.each(custom_class_arr, function(i, el){
            if($.inArray(el, custom_uniqueNames) === -1) custom_uniqueNames.push(el);
        });
        
        //click event for open module on custom class
        jQuery.each(custom_uniqueNames, function(index,value){

            if( '' != value && 'undefined' != value && null != value  ) {
                var check_val = "."+value,
                    is_custom = false;

                if( value.indexOf('#') != -1 || value.indexOf('.') != -1 ){                   
                    var str     = value;                  
                    str         = str.replace(/^(?:\[[^\]]*\]|\([^()]*\))\s*|\s*(?:\[[^\]]*\]|\([^()]*\))/g, "");
                    check_val   = str;
                    is_custom   = true;
                }
                
                jQuery("body").on( "click", check_val, function(event){
                    if( is_custom ){                       
                        var element        = jQuery(".cp-global-load[data-custom-selector='"+custom_selector+"']");
                    }else{
                        var element        = jQuery(".cp-global-load"+check_val);
                    }     

                    var type           = element.data('module-type'),
                    is_inner_class = false;
                    if( !jQuery(this).hasClass("global_info_bar_container") ){
                        //event.preventDefault();
                    }else{
                        is_inner_class = true;
                    }
                    
                    if( type == 'modal'){

                        var modal_id = element.data("modal-style");

                        if( !jQuery('.cp-modal-popup-container.'+modal_id).find('.cp-animate-container').hasClass('cp-form-submit-success') ) {
                            event.preventDefault();
                            var class_id    = element.data("class-id"),
                            modal       = $('.'+class_id);

                            if( modal.hasClass('cp-window-size') ){
                                modal.windowSize();
                            }

                            if( $(".global_modal_container.cp-open").length <= 0 ){

                                ConvertPlus._displayPopup(modal,type,modal_id);
                                var cp_tooltip  =  modal.find(".cp-tooltip-icon").data('classes');
                                $('head').append('<style class="cp-tooltip-close-css">.tip.'+cp_tooltip+'{ display:block; }</style>');
                            }

                            //LAzy load video.
                            var frame = modal.find('.cp-youtube-continer');
                            var frame_length = frame.length;
                            var lazy_video = false;
                            if( frame_length >= 1){
                                lazy_video = true;
                                var autoplay = modal.find('.cp-youtube-continer').data('autoplay');
                                modal.find('.cp-youtube-continer').trigger('click', [autoplay]);
                            }else{
                                var src = modal.find('.cp-youtube-frame').attr('data_y_src');
                                modal.find('.cp-youtube-frame').attr('src', src);
                                modal.find('.cp-youtube-frame').removeAttr('data_y_src');
                            }

                            if(styleArray.length !== 0 ) {
                                if( !$(this).hasClass('cp-disabled') && !modal.hasClass( 'cp-disabled-impression' ) ){
                                    ConvertPlus.update_impressions(styleArray);
                                    $(document).trigger("cp_custom_class_clicked",[this]);
                                }
                            }
                        }

                    }else if( type == 'info-bar' && !is_inner_class ){
                        if( !jQuery(this).hasClass("global_info_bar_container") ){
                            event.preventDefault();
                        }
                        var target      = element.first(),                       
                        id =       target.data('info_bar-id');
                        if( !target.hasClass('cp-form-submit-success') ) {
                            var class_id   = target.data("custom-class");
                            if( ConvertPlus._isOtherPopupOpen( type )){
                                target.css('display','block');
                                ConvertPlus._displayPopup(target,type,id);
                            }
                        }

                    }else if( type == 'slide_in'){
                        if( !jQuery(this).hasClass("slidein-overlay") ){
                        event.preventDefault();
                        var type        = element.data('module-type'),
                            target      = element,
                            class_id    = element.data("class-id"),
                            slidein     = $('.'+class_id),
                            style       = slidein.data('slidein-style'),
                            condition   = ( jQuery(".si-open").length <= 1 && jQuery(".si-open").find(".cp-slide-in-float-on").length <= 1 );

                            if( condition ){
                                slidein.find('.cp-animate-container').removeClass('cp-hide-slide');
                                ConvertPlus._displayPopup(slidein,type,style);
                            }
                        }
                    }

                });
            }
        });        
    });


/* check if event is already fired */
function cp_is_triggered( elem ){
    var module_type   = elem.data("module-type"),
    exit_intent   = elem.data("exit-intent"),
    condition     = true;

    if( module_type == 'modal' ){
        var class_id           = elem.data("class-id"),
        modal              = $('.'+class_id),
        condition = (modal.hasClass('cp-open') || modal.hasClass('cp-visited-popup'));
    }else if( module_type == 'slide_in'){
        var class_id           = elem.data("class-id"),
        slide_in              = $('.'+class_id),
        condition = slide_in.hasClass('si-open');
    }else if( module_type == 'info-bar'){
        condition = elem.hasClass('ib-display');
    }
    return condition;
}

  
/* Exit Intent Event */ 
$( document ).on( 'mouseleave', function( event ) {

    $(".cp-global-load").each( function(t) {
        var element = $( this ),
        exit_intent   = element.data("exit-intent"),
        add_to_cart   = element.data("add-to-cart"),
        item_present  = ConvertPlus._getCookie( 'woocommerce_items_in_cart' );

     if( exit_intent == 'enabled' && add_to_cart == '1' ){
            var result = cp_is_triggered( element );
            if( result == false  && ( add_flag || item_present == 1 ) ){
                    ConvertPlus.init( event, element, 'mouseleave' );
                }
            }
        else if( exit_intent == 'enabled' ){
            var result = cp_is_triggered( element );
                if( result == false ){
                    ConvertPlus.init( event, element, 'mouseleave' );
                }
            }
    });
});

/* Idle Event */
jQuery(document).on( "idle.idleTimer", function(event, elem, obj){
    $(".cp-global-load").each( function(t) {
        ConvertPlus.init( event, $( this ), 'idle' );
    });
});

/*Google Recaptcha */
jQuery(window).on('load', function (e) {
       if (jQuery('.g-recaptcha-response')[0]) {
       jQuery('.cp-onload ').addClass('cp-recaptcha cp-recaptcha-index-1 cp-recaptcha-index-2 cp-recaptcha-index-3 cp-recaptcha-index-4 cp-recaptcha-index-5 cp-recaptcha-index-6 cp-recaptcha-index-7 ');
       jQuery('.g-recaptcha-response').addClass('cp-recaptcha-required');
       jQuery('.cp-recaptcha-required').prop('required',true);
       jQuery('.g-recaptcha-response').parent().addClass('cp-g-recaptcha-response');
        }
       var element = jQuery('.cp-module'),
        module_type   = element.data("module-type");
        if ( module_type == 'info-bar'){
            if ( jQuery('.g-recaptcha').parents('.cp-info-bar-container').length == 1 ) { 
                jQuery('.cp-info-bar-body .cp-submit').addClass('cp-recaptcha-css');
                jQuery('.ib-form-container .cp-form-container .cp-form-layout-3 .cp-submit .cp-recaptcha-css ').css('display','inline','!important');
                jQuery('.ib-form-container .cp-form-container .cp-form-layout-3 .cp-submit-wrap').css('padding-bottom' , '40px');
                
            }
        }
});

jQuery(document).ready(function(){

    ConvertPlus._check_responsive_font_sizes();
    jQuery('.blinking-cursor').remove();

    $(".cp-global-load").each( function(t) {
     ConvertPlus._count_inline_impressions( $( this ) );
    });

    /*infobar functions*/
    ConvertPlus._cp_ifb_toggle();
});

jQuery(window).on('load', function (e) {
   /*load after content add extra spces for some theme*/
   clearTimeout($.data(this, 'cp_check_empty_span'));
   $.data(this, 'cp_check_empty_span', setTimeout(function() {
    var load_after_post = jQuery.trim(jQuery('.cp-load-after-post').parent().text());
    if( typeof load_after_post !== 'undefined' ){
        var post_lenght = jQuery.trim( load_after_post ).length;
        if( post_lenght == '0'){
            var check_xtheme_preview = jQuery(window.parent.document).find(".cs-preview-frame-container").length;
            if( check_xtheme_preview !== 1 ){
                jQuery('.cp-load-after-post').parent().addClass("cp-empty-content");
            }
        }
    }    

    var deviceAgent = navigator.userAgent.toLowerCase();
    var agentID = deviceAgent.match(/(iphone|ipod|ipad)/);
    if (agentID) {     
        jQuery('html').addClass('cp-iphone-browser');
    }

   // load images after page load.
    [].forEach.call(jQuery('.cp-module').find('img[data-src]'), function(img) {
      img.setAttribute('src', img.getAttribute('data-src'));
      img.onload = function() {
        img.removeAttribute('data-src');

      };
    });
    
}, 1000));


});


var rs_flag = 0;
var resizeTimer;

jQuery(window).on('resize', function(e) {
    
    clearTimeout(resizeTimer);
    
    resizeTimer = setTimeout(function() {
        
        ConvertPlus._close_button_tootip();

        jQuery(".cp-info-bar.ib-display").each(function() {
            var md = jQuery(this);
            rs_flag++;        
            ConvertPlus._apply_push_page_down(md,'resize');
        });

        jQuery(".cp-info-bar").each(function(t) {
            ConvertPlus._infoBarPos(jQuery( this ) );
        });

    }, 1000);    

});

jQuery(window).on("modalOpen", function(e,data) {
    ConvertPlus._close_button_tootip();
});

jQuery(document).on("cp_conversion_done", function(e, $this, style_id){
    if( !jQuery( $this ).parents(".cp-form-container").find(".cp-email").length > 0 ){
        var is_only_conversion = jQuery( $this ).parents(".cp-form-container").find('[name="only_conversion"]').length;
        if ( is_only_conversion > 0 && jQuery( $this ).parents(".cp-modal-popup-container").hasClass('cp-'+style_id) ) {
           jQuery($this).addClass('cp-disabled');
       }
   }
});

// Custom class impression count
jQuery(document).on("cp_custom_class_clicked", function(e, $this){
   jQuery($this).addClass('cp-disabled');
});

// Close modal on click of close button
jQuery(document).on("click", ".cp-form-submit-error", function(e){
    var $this                   = jQuery(this),
    cp_form_processing_wrap = $this.find(".cp-form-processing-wrap") ,
    cp_tooltip              = $this.find(".cp-tooltip-icon").data('classes'),
    cp_msg_on_submit        = $this.find(".cp-msg-on-submit");

    cp_form_processing_wrap.hide();
    $this.removeClass('cp-form-submit-error');
    cp_msg_on_submit.html('');
    cp_msg_on_submit.removeAttr("style");
    jQuery('head').append('<style class="cp-tooltip-css">.tip.'+cp_tooltip+'{display:block }</style>');

});

jQuery(".cp-overlay").on( "idle.idleTimer", function(event, elem, obj){        
    var modal = jQuery(".cp-overlay");
    jQuery(document).trigger('closeModal',[modal]);
    var cp_tooltip  =  modal.find(".cp-tooltip-icon").data('classes');
    setTimeout(function(){
        jQuery('head').append('<style id="cp-tooltip-close-css">.tip.'+cp_tooltip+'{ display:none; }</style>');
    },1000);
});

jQuery(document).on( "idle.idleTimer", function(event, elem, obj){ 
    if( jQuery(".ib-display").hasClass('cp-close-after-x')){
        var info_bar = jQuery(".ib-display");
        jQuery(document).trigger('cp_close_info_bar',[info_bar]);
    }

    if( jQuery(".slidein-overlay").hasClass('cp-close-after-x')){
        var slidein = jQuery(".slidein-overlay");            
        jQuery(document).trigger('closeSlideIn',[slidein]);
    }
});

//close modal on cp-close class
jQuery(document).on("click", ".cp-close", function(e){
    if( !jQuery(this).parents(".cp-overlay").hasClass('do_not_close') ){
        var modal       =  jQuery(this).parents(".cp-overlay");
        jQuery(document).trigger('closeModal',[modal]);
    }
});

//close modal on cp-inner-close class
jQuery(document).on("click", ".cp-inner-close", function(e){
    var modal       =  jQuery(this).parents(".cp-overlay");
    jQuery(document).trigger('closeModal',[modal]);
});

// Close modal on click of close button
jQuery(document).on("closeModal", function(event,modal){
    var id          =  modal.data("class"),
    overlay     =  $( '.cp-global-load[data-class-id=' + id + ']' );
    ConvertPlus.init( event, overlay, 'closepopup' );
});

jQuery(document).on("cp_close_info_bar", function( event, info_bar ) {
    var id          =  info_bar.data("class"),
    overlay     =  $( '.cp-ib-onload[data-class-id=' + id + ']' );
    ConvertPlus.init( event, info_bar, 'closepopup' );
});

//set cookies for optin widget style
jQuery("body").on("click", ".cp-slidein-head .cp-widget-open", function(e){
    var slidein     = jQuery(this).parents(".slidein-overlay"),
    cookieTime  = slidein.data('closed-cookie-time'),
    cookieName  = slidein.data('slidein-id'),
    cp_animate  = slidein.find('.cp-animate-container'),
    entry_anim  = slidein.data('overlay-animation'),
    exit_anim   = cp_animate.data('exit-animation'),
    conversion  = slidein.data('conversion-cookie-time'),
    temp_cookie = "temp_"+cookieName;

    ConvertPlus._createCookie(temp_cookie,true,1);

    var cookie      = ConvertPlus._getCookie(cookieName);

    if(!cookie){
        if(cookieTime){
            slidein.addClass("cp-always-minimize-widget");
            ConvertPlus._createCookie(cookieName,true,cookieTime);
        }
    }

});

// Close Slide In on click of close button
jQuery(document).on("closeSlideIn", function(event,slidein){
    var container   =  slidein.parents(".cp-slidein-popup-container"),
    id      =  slidein.data("class"),
    overlay =  $( '.si-onload[data-class-id=' + id + ']' );
    ConvertPlus.init( event, overlay, 'closepopup' );
});

//set tab index for input
jQuery(".smile-optin-form").each(function() {
    var option = $(this).parents('.cp-module').data('module-name');
    $(this).find('input[name="cp_module_type"]').val(option);
    var last_input = jQuery(this).find( "input.cp-input" ).last();
    if( last_input.hasClass("cp-input")){
        last_input.addClass("cp-last-field");
    }
});

jQuery("input.cp-input").keydown(function(e) {
    var keyCode = (window.event) ? e.which : e.keyCode;
    if (keyCode == 9 && jQuery(this).hasClass("cp-last-field")){
        e.preventDefault();
        var form = jQuery(this).parents(".smile-optin-form");
        form.find(".cp-submit").attr("tabindex",-1).focus();
    }
});


$( document ).scroll( function(event) {    

    //scroll event trigger
    clearTimeout($.data(this, 'CP_scrollEvent'));
    $.data(this, 'CP_scrollEvent', setTimeout(function() {
        $(".cp-global-load").each( function(t) {
            var element = $( this ),
            scroll_chk   = element.data('onscroll-value'),
            scroll_class = element.data('scroll-class'),
            scrollValue  = element.data("after-content-value");
            var after_post = ( element.hasClass("cp-after-post") || element.hasClass("ib-after-post") ||   element.hasClass("si-after-post") );

            if( (typeof scroll_class !== 'undefined' && scroll_class !== '') || (scroll_chk !=='') || after_post ){
                var result = cp_is_triggered( element );
                if( result == false ){
                    ConvertPlus.init( event, element, 'scroll' );
                }
            }

            ConvertPlus._count_inline_impressions( $( this ) );

        });
    },200));    

    //Add compatibility support for avada theme push page down    
    clearTimeout($.data(this, 'CP_scrollTimer'));
    $.data(this, 'CP_scrollTimer', setTimeout(function() {
        $(".cp-ib-onload.cp-pos-top").each( function(t) {  
            var element = $( this ), 
            ht = element.outerHeight(), 
            page_push_down    = element.data('push-down') || null;
            var data_toggle = element.data("toggle-visible");                
            
            if( page_push_down && element.hasClass("ib-display") && element.hasClass("ib-fixed") ){
                var is_avada_header = jQuery(".fusion-header-wrapper").find(".fusion-sticky-menu-");
                var is_avada_sticky_menu = '';
                if( typeof is_avada_header !== 'undefined' ){
                    is_avada_sticky_menu = is_avada_header.length;
                }

                if( is_avada_sticky_menu > 0 ){
                    var fusion_class = '.fusion-header';
                    if( jQuery("body").hasClass("fusion-header-layout-v4") || jQuery("body").hasClass("fusion-header-layout-v5") ){
                        fusion_class = '.fusion-secondary-main-menu';
                    }
                    var admin_bar_height  = jQuery('#wpadminbar').outerHeight();
                    var total_ht = ht + admin_bar_height;  
                    jQuery(fusion_class).addClass("cp-fusion-header");
                    jQuery(".cp_fusion_css").remove();
                    
                    if( element.find(".cp-ifb-toggle-btn").hasClass("cp-ifb-show") ){
                        var fixed_css = ".cp-fusion-header{top:"+admin_bar_height+"px !important}";
                        $('head').append("<style class='cp_fusion_css' type='text/css'>"+fixed_css+"</style>");
                    }else{
                        var fixed_css = ".cp-fusion-header{top:"+total_ht+"px !important}";
                        $('head').append("<style class='cp_fusion_css' type='text/css'>"+fixed_css+"</style>");
                    }                      
                    
                    jQuery(fusion_class).addClass("cp-scroll-start");        
                }
            }else{

             var fusion_class = '.fusion-header';
             if( jQuery("body").hasClass("fusion-header-layout-v4") || jQuery("body").hasClass("fusion-header-layout-v5") ){
                fusion_class = '.fusion-secondary-main-menu';
            }
            jQuery(fusion_class).addClass("cp-fusion-header");
            var admin_bar_height  = jQuery('#wpadminbar').outerHeight(); 
            var fixed_css = ".cp-fusion-header{top:"+admin_bar_height+"px !important}";
            $('head').append("<style class='cp_fusion_css' type='text/css'>"+fixed_css+"</style>");

        }
    });

    }, 100));

});

//Add compatibility support for avada theme push page down
jQuery(document).on("infobarOpen", function(e,data) {  
    var element = data, 
    ht      = element.outerHeight(),  
    page_push_down    = element.data('push-down') || null;

    var is_avada_sticky_menu = jQuery(".fusion-header-wrapper").find(".fusion-sticky-menu-").length;
    if( is_avada_sticky_menu && page_push_down ){
        var fusion_class = '.fusion-header';
        if( jQuery("body").hasClass("fusion-header-layout-v4") || jQuery("body").hasClass("fusion-header-layout-v5") ){
            fusion_class = '.fusion-secondary-main-menu';
        }

        var admin_bar_height  = jQuery('#wpadminbar').outerHeight(),
        total_ht = ht + admin_bar_height,
        old_top = jQuery(fusion_class).css("top");

        jQuery(fusion_class).attr( "data-old-top", old_top );
        var data_toggle = element.data("toggle-visible");                
        if( !data_toggle  && element.hasClass("ib-fixed") ){ 
            jQuery(".cp_fusion_css").remove();
            jQuery(fusion_class).addClass("cp-fusion-header");
            var fixed_css = ".cp-fusion-header{top:"+total_ht+"px !important}";
            $('head').append("<style class='cp_fusion_css' type='text/css'>"+fixed_css+"</style>");

            jQuery(fusion_class).addClass("cp-scroll-start");   
        }               
    }

});

//Add compatibility support for avada theme push page down
jQuery(document).on("cp_close_info_bar", function( event, info_bar ) {
    var element = info_bar, 
    ht      = element.outerHeight(),      
    page_push_down    = element.data('push-down') || null;

    if( jQuery("body").hasClass("fusion-header-layout-v4") || jQuery("body").hasClass("fusion-header-layout-v5") ){
        var fusion_class = '.fusion-secondary-main-menu';
    }else{
        var fusion_class = '.fusion-header';
    }

    if( page_push_down ){
        var old_top = jQuery(fusion_class).attr("data-old-top");  
        jQuery(".cp_fusion_css").remove();  
    }

    info_bar.addClass("cp-stop-scroll");
    jQuery(fusion_class).removeClass("cp-scroll-start");    
    $('.cp-push-page-css').remove();
});

//close gravity form & Custom analytics for Contact form.
jQuery(document).bind('gform_confirmation_loaded', function(event, form_id){ 
    var form     = jQuery('#gf_'+form_id),        
        style_id   = form.parents(".cp-module").data("style-id"),
        style_name = form.parents(".cp-module").data("module-name"),
        is_closed  = form.parents(".cp-module").data("close-gravity");    
    jQuery(document).trigger('cp_custom_analytics',[style_id]);
    
    if( is_closed == '1'){
        jQuery(document).trigger('cp_custom_close_module',[form,style_name]);
    }
});

//Custom analytics for Contact form.
document.addEventListener( 'wpcf7submit', function( event ) {
    var status = event.detail.status;
    var form_id = event.detail.id;

    if( status == 'mail_sent'){
        var form = jQuery('#'+form_id );
        var style_id    = form.parents(".cp-module").data("style-id"),
            style_name  = form.parents(".cp-module").data("module-name"),
            is_closed  = form.parents(".cp-module").data("close-gravity");    

        jQuery(document).trigger('cp_custom_analytics',[style_id]);
        if( is_closed == '1'){
            jQuery(document).trigger('cp_custom_close_module',[form,style_name]);
        }
    }       
}, false );

//custom analytics for ninja form.    
jQuery( document ).on( 'nfFormSubmitResponse', function( event, response ) {
    var error       = response.response.errors,
        form_id     = 'nf-form-'+response.id+'-cont',
        form        = jQuery('#'+form_id),
        style_id    = form.parents(".cp-module").data("style-id"),
        style_name  = form.parents(".cp-module").data("module-name"),
        is_closed  = form.parents(".cp-module").data("close-gravity");    

        jQuery(document).trigger('cp_custom_analytics',[style_id]);
        if( is_closed == '1'){
            jQuery(document).trigger('cp_custom_close_module',[form,style_name]);
        }
});

//close module after custom conversion
jQuery(window).on("cp_custom_close_module", function( e, form, style_name ) {
    if( style_name == 'modal' ){
        var modal       =  form.parents(".cp-open");
        jQuery(document).trigger('closeModal',[modal]);
    }else if( style_name == 'slidein' ){
        var slidein       =  form.parents(".slidein-overlay");
        jQuery(document).trigger('closeSlideIn',[slidein]);
    }else if( style_name == 'infobar' ){
        var info_bar = form.parents(".cp-info-bar");
        jQuery(document).trigger("cp_close_info_bar",[info_bar]);
    }    
});

//Custom conversion
jQuery(window).on("cp_custom_analytics", function(e,style_id) {
    setTimeout(function() {
        var nounce = jQuery(".cp-impress-nonce").val(),
        data = {action:'smile_update_custom_conversions',conversion:true,style_id:style_id,option:'smile_modal_styles',security:nounce};
        jQuery.ajax({
            url:smile_ajax.url,
            data: data,
            type: "POST",
            dataType:"HTML",
            security:jQuery(".cp-impress-nonce").val(),
            beforeSend: function(result){// do your stuff
                ajax_run = false;
            }
        });
    },  2000); 
});

jQuery( '.cp-youtube-continer' ).on( 'click', function( e, auto ) {
    e.preventDefault();
    var iframe      = jQuery( "<iframe/>" );
    var src   = jQuery( this ).data( 'custom-url' );
    var style   = jQuery( this ).data( 'custom-css' );
    var classname   = jQuery( this ).data( 'class' );
    var autoplay = jQuery( this ).data( 'autoplay' );
    var wt   = jQuery( this ).data( 'width' );
    var ht   = jQuery( this ).data( 'height' );
    src = src.replace("autoplay=0", "autoplay=1"); 

    if( auto == null || auto == '1' || auto == 'undefined'){   
        src = src.replace("autoplay=0", "autoplay=1");
    }else{        
        src = src.replace("autoplay=1", "autoplay=0");
    }

    iframe.attr( 'class', classname );
    iframe.attr( 'frameborder', '0' );
    iframe.attr( 'allowfullscreen', '1' );
    iframe.attr( 'style', style );
    iframe.attr( 'allow', 'autoplay;encrypted-media;' );
    iframe.attr( 'src', src );
    if( wt !== '' || ht !== '' ){
        iframe.attr( 'width', wt );
        iframe.attr( 'height', ht );
    }
    jQuery( this ).html( iframe );
   
} );



})(jQuery);


