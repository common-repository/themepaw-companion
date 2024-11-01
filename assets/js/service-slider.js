var ServiceSliderInit = function($scope, $) {

    console.log( $scope );
    var slider = $scope.find(".lgtico-service-slider");

    if( slider.legnth ) {
        slider.owlCarousel({
            loop:true,
            margin:0,
            nav:true,
            dots:false,
            items:3,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            autoplay:true,
            autoplayTimeout:7000,
            autoplayHoverPause:true,
            navText: ["<i class=\"ti-angle-left\"></i>",
              "<i class=\"ti-angle-right\"></i>"],
        });
    }
};

jQuery(window).on("elementor/frontend/init", function() {
    console.log("yeah man");
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/elmtrip-infobox.default",
        ServiceSliderInit
    );
});


elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
	console.log($scope);
} );