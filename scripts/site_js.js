var isMobile = {
    Android   : function () {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function () {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS       : function () {
        return navigator.userAgent.match(/iPhone|iPod|iPad/i);
    },
    Opera     : function () {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows   : function () {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any       : function () {
        return (
            isMobile.Android() ||
                isMobile.BlackBerry() ||
                isMobile.iOS() ||
                isMobile.Opera() ||
                isMobile.Windows() );
    }
};
var popupCenter = function(url, title, w, h) {
	// Fixes dual-screen position                         Most browsers      Firefox
	var dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : screen.left;
	var dualScreenTop = window.screenTop !== undefined ? window.screenTop : screen.top;

	var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
	var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

	var left = ((width / 2) - (w / 2)) + dualScreenLeft;
	var top = ((height / 3) - (h / 3)) + dualScreenTop;

	var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

	// Puts focus on the newWindow
	if (window.focus) {
		newWindow.focus();
	}
};

/** For mobile friendliness, use touchstart for mobile devices, and click events for desktops**/
var handleClick = (isMobile.any() !== null) ? "touchstart" : "click";
//var functions = '';
$ = jQuery;
var functions = {
	debug: true,
	log: function(){
		var _args = Array.prototype.slice.call(arguments);
		if(this.debug){
			if (typeof console !== "undefined" || typeof console.log !== "undefined") {
				console.log(_args);
			}
		}
	},
	offsetHeader  : function(){ return $('.wrapper.main-header').outerHeight()},
	getWindowWidth  : function() {
		return (window.outerWidth == 0 ? window.innerWidth : window.outerWidth);
	},
	getWindowHeight  : function() {
		return (document.documentElement.clientHeight == 0 ? $(window).height() : document.documentElement.clientHeight);
	},
	device_type    : function () {
		var _args = Array.prototype.slice.call(arguments),
			_match = (typeof _args[0] === 'undefined' ?  false : _args[0]),
			_return = '';

		if (this.getWindowWidth() <= 568) {
			_return = 'phone';
		} else if (this.getWindowWidth() <= 850) {
			_return = 'tablet';
		} else {
			_return = 'desktop';
		}
		return (_match ? (_match == _return) : _return);
	},
	vertical_center          : function () {
		var _center = $(".js-vertical-center");
		_center.each(function () {
			var _pH = $(this).parent().height(),
				_c = $(this).outerHeight(),
				_dif = ((_pH - _c) / 2);
			if($(this).hasClass('js-no-vertical-mobile') && functions.device_type('mobile')){
				$(this).css('margin-top', '');
			}else{
				$(this).stop(false, false).animate({
						'margin-top': _dif + "px"
					}, 200, false
				);
			}

		});
	},
	menu_toggle: function(){
		if(typeof $.sidr == 'function'){
			$.sidr('close', 'mobile-nav');
		}
	},
	pushUpdate    : function (_launch) {
		var _footer = $('.wrapper.main-footer'),
			_bottomPush = $('.bottom-push'),
			_site = $('body > .site'),
			_mainContent = $('.wrapper.main-content'),
			_mainHeader  = $('.wrapper.main-header').outerHeight(),
			_offset = _footer.outerHeight();
		if (_offset !== null) {
			_bottomPush.css('height', _offset);
			_site.attr('style', 'margin-bottom:-' + _offset + 'px');
			if(typeof _mainContent !== 'undefined' && typeof _launch !== 'undefined'){
				_mainContent.attr('style', 'min-height:100px');
			}
		}
	},
	pageScroll    : function (_tag) {
		if(typeof $(_tag) !== 'undefined'){
			var _totalScroll = $(_tag).offset().top;
			$('html,body').animate({
				scrollTop: _totalScroll
			}, 1000);
		}
	},
	anchorScroll: function(_this, _location, _e){
		if (_location.pathname.replace(/^\//,'') == _this.pathname.replace(/^\//,'') && _location.hostname == _this.hostname) {
			var _target = $(_this.hash);
			_target = _target.length ? _target : $('[name=' + _this.hash.slice(1) +']');
			if (_target.length) {
				_e.preventDefault();
				functions.pageScroll(_target.selector);
			}
		}
	},
	emailReplace: function(){
		/** Use JavaScript to replace <a> with a mail link, to reduce potential spam**/
		var _varPre = "mailto:",
			_selector = ".js-replacer-text";

		if ($(_selector).length > 0) {
			$(_selector).each(function(){
				var _varUpdate = $(this).data('update'),
					_varEnd = $(this).data('domain'),
					_varMid = $(this).data('extra'),
					_varText = $(this).data('text');
				$(this).attr('href', _varPre + _varMid + '@' + _varEnd);
				if(typeof _varUpdate == 'boolean' && _varUpdate != true){


				}else{
					if(typeof _varText !== 'undefined'){
						$(this).html(_varText);
					}else{
						$(this).text(_varMid + '@' + _varEnd);
					}
				}
			});
		}
	},
	windowResize: function(name, callback, vars){
		clearTimeout($.data(this, name));
		$.data(this, name, setTimeout(function() {
			if(typeof vars === 'string' || typeof vars == 'object'){
				callback(vars);
			}else{
				callback();
			}
		}, 100));
	},
	matchHeight :function(_tag){
		var _arrHeight = [],
			_elHeight = 0;
 
		$(_tag).each(function () {
			_arrHeight.push($(this).outerHeight());
		});
		_elHeight = Math.max.apply(Math,_arrHeight);

		if(functions.getWindowWidth() > 767){
			$(_tag).each(function () {
				$(this).stop(false,false).animate({
						'min-height': _elHeight+"px"
					}, 200, false
				);
			});
		}else{
			$(_tag).each(function () {
				$(this).stop(false,false).animate({
						'min-height': '0px'
					}, 200, false
				);
			});
		}
	},
	videoResize: function(){
		if ($(".js-video-resize:visible").length > 0) {
			$(".js-video-resize:visible").each(function () {

				var _this = $(this);
				var iFrameID = _this.find('iframe').attr('id');
				var $video = '';
				var ratioHeight = 0;
				var ratioWidth = 0;
				var vidWidth = 0;
				var vidHeight = 0;

				$video = $("#"+iFrameID);
				vidWidth = _this.outerWidth();
				ratioWidth = $video.attr('data-vidWidth');
				ratioHeight = $video.attr('data-vidHeight');
				vidHeight = ((vidWidth*ratioHeight) / ratioWidth);
				$video.css('display', 'block');
				$video.animate({height: vidHeight}, 100);
				//webLog.log({vid: _this, vidID: iFrameID, vW: vidWidth, vH: vidHeight, rW: ratioWidth, rH: ratioHeight});
			});
		}
	},
	generic_resize           : function () {
		functions.videoResize();
		functions.pushUpdate();
		functions.vertical_center();
		functions.menu_toggle();
	}

};
var menuButtonName = {};
(function ( $ ) {
	$.this = 'undefined';
	$(window).resize(function() {
		functions.windowResize('generic', functions.generic_resize);
		//functions.windowResize('headerScroll', functions.headerScroll);
	});

	$(document).ready(function () {
		functions.videoResize();
		functions.emailReplace();
		functions.pushUpdate();
		functions.vertical_center();
		//functions.headerScroll();

		menuButtonName = $('#btn-mobile-nav');
		menuButtonName.sidr({
			name: 'mobile-nav',
			source: '#mobile-nav',
			renaming : false,
			displace: true,
			onOpen: function(){
				menuButtonName.toggleClass('inactive');
				menuButtonName.toggleClass('active');
			},
			onClose: function(){
				menuButtonName.toggleClass('active');
				menuButtonName.toggleClass('inactive');
			}
		});

	});

	$(document).on(handleClick, 'a[href*=#]:not([href=#])', function (e) {
		functions.anchorScroll(this, location, e);
	});

}( jQuery ));