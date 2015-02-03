/*
 *  SliderNav - A Simple Content Slider with a Navigation Bar
 *  Copyright 2010 Monjurul Dolon, http://mdolon.com/
 *  Released under the MIT, BSD, and GPL Licenses.
 *  More information: http://devgrow.com/slidernav
 */
jQuery.fn.sliderNav = function(options) {
	var defaults = { items: ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"], debug: false, height: null, arrows: true};
	var opts = jQuery.extend(defaults, options); var o = jQuery.meta ? jQuery.extend({}, opts, $$.data()) : opts; var slider = jQuery(this); jQuery(slider).addClass('list_slider');
	jQuery('.slider-content li:first', slider).addClass('selected');
	jQuery(slider).append('<div class="slider-nav"><ul></ul></div>');
	for(var i in o.items) jQuery('.slider-nav ul', slider).append("<li><a alt='#"+o.items[i]+"'>"+o.items[i]+"</a></li>");
	var height = jQuery('.slider-nav', slider).height();
	if(o.height) height = o.height;
	jQuery('.slider-content, .slider-nav', slider).css('height',height);
	if(o.debug) jQuery(slider).append('<div id="debug">Scroll Offset: <span>0</span></div>');
	jQuery('.slider-nav a', slider).mouseover(function(event){
		var target = jQuery(this).attr('alt');
		var cOffset = jQuery('.slider-content', slider).offset().top;
		var tOffset = jQuery('.slider-content '+target, slider).offset().top;
		var height = jQuery('.slider-nav', slider).height(); if(o.height) height = o.height;
		var pScroll = (tOffset - cOffset) - height/8;
		jQuery('.slider-content li', slider).removeClass('selected');
		jQuery(target).addClass('selected');
		jQuery('.slider-content', slider).stop().animate({scrollTop: '+=' + pScroll + 'px'});
		if(o.debug) jQuery('#debug span', slider).html(tOffset);
	});
	if(o.arrows){
		jQuery('.slider-nav',slider).css('top','20px');
		jQuery(slider).prepend('<div class="slide-up end"><span class="arrow up"></span></div>');
		jQuery(slider).append('<div class="slide-down"><span class="arrow down"></span></div>');
		jQuery('.slide-down',slider).click(function(){
			jQuery('.slider-content',slider).animate({scrollTop : "+="+height+"px"}, 500);
		});
		jQuery('.slide-up',slider).click(function(){
			jQuery('.slider-content',slider).animate({scrollTop : "-="+height+"px"}, 500);
		});
	}
};