(function($) {
"use strict";


/*--------------------------------------------------------------
CUSTOM PRE DEFINE FUNCTION
------------------------------------------------------------*/
/* is_exist() */
jQuery.fn.is_exist = function(){
  return this.length;
}

$(function(){

/*--------------------------------------------------------------
LGTICO HERO SLIDER JS
--------------------------------------------------------------*/
// var lgtico_hero_slider = $('.lgtico-service-slider');
//   if (lgtico_hero_slider.is_exist()) {
//       lgtico_hero_slider.owlCarousel({
//       loop:true,
//       margin:0,
//       nav:true,
//       dots:false,
//       items:3,
//       animateOut: 'fadeOut',
//       animateIn: 'fadeIn',
//       autoplay:true,
//       autoplayTimeout:7000,
//       autoplayHoverPause:true,
//       navText: ["<i class=\"ti-angle-left\"></i>",
//         "<i class=\"ti-angle-right\"></i>"],
//   });

// }


$(document).on('change','select',function(){
  console.log("PROBANDO");
});


});/*End document ready*/


$(window).on("load" ,function(){


}); // End window LODE



$(window).on("resize", function(){

}); // end window resize


})(jQuery);






