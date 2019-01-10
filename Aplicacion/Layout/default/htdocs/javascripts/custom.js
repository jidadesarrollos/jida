$(document).ready(function(){

/* 
=============================================== 
Owl Carousel Bow Javascript
=============================================== 
*/
	"use strict";
	var owl      = $("#owl-demo");
	var owl2     = $("#owl-demo-2");
	var owl3     = $("#owl-demo-3");
	var owl4     = $("#owl-demo-4");
	var owl5     = $("#owl-demo-5");
 
	owl.owlCarousel({
		 
			itemsCustom : [
				[0, 1],
				[450, 1],
				[600, 1],
				[700, 1],
				[1000, 1]
			],
			navigation : false,
			pagination : false,
			autoPlay : 5000,
			// transitionStyle : "goDown"
			// transitionStyle : "fade"
			// transitionStyle : "backSlide"
			transitionStyle : "fadeUp"
 
	});

	owl2.owlCarousel({
		 
			itemsCustom : [
				[0, 1],
				[450, 1],
				[600, 1],
				[700, 1],
				[1000, 1]
			],
			navigation : false,
			pagination : true,
			autoPlay : 3000,
			// transitionStyle : "goDown"
			transitionStyle : "fade"
			// transitionStyle : "backSlide"
			// transitionStyle : "fadeUp"
 
	});

	owl3.owlCarousel({
		 
			itemsCustom : [
				[0, 1],
				[450, 1],
				[600, 1],
				[700, 1],
				[1000, 1]
			],
			navigation : true,
			pagination : false,
			autoPlay : false,
			// transitionStyle : "goDown"
			// transitionStyle : "fade"
			transitionStyle : "backSlide"
			// transitionStyle : "fadeUp"
 
	});

	owl4.owlCarousel({
		 
			itemsCustom : [
				[0, 1],
				[450, 1],
				[600, 1],
				[700, 1],
				[1000, 1]
			],
			navigation : true,
			pagination : false,
			autoPlay : 3000,
			transitionStyle : "goDown"
			// transitionStyle : "fade"
			// transitionStyle : "backSlide"
			// transitionStyle : "fadeUp"
 
	});

	owl5.owlCarousel({
		 
			itemsCustom : [
				[0, 1],
				[450, 1],
				[600, 1],
				[700, 1],
				[1000, 1]
			],
			navigation : true,
			pagination : false,
			autoPlay : 3000,
			// transitionStyle : "goDown"
			// transitionStyle : "fade"
			transitionStyle : "backSlide"
			// transitionStyle : "fadeUp"
 
	});

	$(window).scroll(function(){ 
	var scroll = $(window).scrollTop();
		if( scroll > 20 ){    
			$(".navbar").addClass("navbar-lool-act");       
		} else {
			$(".navbar").removeClass("navbar-lool-act");  
		}
	});

/* 
=============================================== 
Infinite Scroll
=============================================== 
*/

	// $('.scroll').jscroll();
	$('.scroll').jscroll({
		autoTrigger: false
	});

});