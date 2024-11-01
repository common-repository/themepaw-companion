(function ($) {

    var TpawSliderHandler = function ($scope, $) {

        var slider_elem = $scope.find('.lgtico-single-slider');

        if( slider_elem.length === 0 )
            return false;

        var settings = slider_elem.data('settings');
        
        slider_elem.owlCarousel({
            loop: settings['loop'],
            margin: settings['margin'],
            nav: $.parseJSON(settings['nav']),
            dots: settings['dots'],
            items: settings['slider_breaks'],
            animateOut: settings['animateOut'],
            animateIn: settings['animateIn'],
            autoplay: settings['autoplay'],
            autoplayTimeout: 7000,
            mouseDrag: settings['mouseDrag'],
            touchDrag: settings['touchDrag'],
            pullDrag: settings['pullDrag'],
            navContainerClass: 'owl-nav flat-nav',
            stagePadding: settings['stagePadding'],
            autoplayHoverPause: true,
            navText: ["<i class=\"ti-angle-left\"></i>", "<i class=\"ti-angle-right\"></i>"],
            responsive:{
                0:{
                    items: settings['slider_breaks_mobile'],
                },
                768:{
                    items: settings['slider_breaks_tablet'],
                },
                1025:{
                    items: settings['slider_breaks'],
                }
            }
        });

    };


    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction('frontend/element_ready/tpaw_infobox.default', TpawSliderHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/tpaw_testimonial_slider.default', TpawSliderHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/tpaw_blog_posts.default', TpawSliderHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/tpaw_postslist.default', TpawSliderHandler);

    });

})(jQuery);