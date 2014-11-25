/** webLog:
 *  Use a manual method for console loggings.
 *
 *  In production, comment out contents of the method to make sure no logs appear
 *  This method will determine if text is a string, or an object, and proceed as needed.
 *
 *  Usage: webLog.log(variable)
 *
 *  **/
var webLog = {
    log: function (text) {
        /** This method will determine if text is a string, or an object, and proceed as needed. **/
        if (typeof text !== 'undefined') {
            if (typeof value === 'object') {
                console.log(JSON.stringify(text));
            } else {
                console.log(text);
            }
        }
    }
}

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
	offsetHeader: 0,
	getWindowWidth  : function () {
		return window.outerWidth;
	},
	pageScroll: function(_tag){
		var totalScroll = $(_tag).offset().top-functions.offsetHeader;
		setTimeout(function(){
			$('html,body').scrollTo(totalScroll, 500 );
		}, 100)
	},
	emailReplace: function(){
		/** Use JavaScript to replace <a> with a mail link, to reduce potential spam**/
		var varPre = "mailto:",
			varMid = '',
			varEnd = '',
			varText = '';
		if ($(".js-replacer-text").length > 0) {
			$(".js-replacer-text").each(function () {
				var modifyText = $(this).data('modify');
				varEnd = $(this).data('domain');
				varMid = $(this).data('extra');
				varText = $(this).data('text');
				$(this).attr('href', varPre + varMid + '@' + varEnd);
				if(typeof modifyText == 'boolean' && modifyText != true){


				}else{
					if(typeof varText !== 'undefined'){
						$(this).text(varText);
					}else{
						$(this).text(varMid + '@' + varEnd);
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

	}

};

(function ( $ ) {
	$.this = 'undefined';
	$(window).resize(function() {
		functions.windowResize('video', functions.videoResize);
		//functions.windowResize('headerScroll', functions.headerScroll);
	});

	$(document).ready(function () {
		functions.videoResize();
		functions.emailReplace();
		//functions.headerScroll();

	});


	$(document).on(handleClick, '.js-scroll-find', function (e) {
		e.preventDefault();
		//functions.scrollFind($(this), e);
	});

	/** Page Logic **/
	var successStories = $(".js-success-stories");
	successStories.owlCarousel({
		items : 1,
		singleItem : true,
		navigation: false,
		pagination: false,
		//navigationText : ["Previous", "Next"],
		responsiveRefreshRate : 100,
		mouseDrag: false,
		afterAction: function(){
			setTimeout(functions.videoResize(), 300);
		}

	});

	$(".navigation .right").click(function(e){
		e.preventDefault();
		successStories.trigger('owl.next');
	});
	$(".navigation .left").click(function(e){
		e.preventDefault();
		successStories.trigger('owl.prev');
	});
}( jQuery ));