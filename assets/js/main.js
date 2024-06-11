(function($) {
    'use strict';

//Toggle Nav for mobile  menu
    $('.menu-toggle').click(function(){
        $('.menu-wrapper').toggleClass('active-nav');
    });



    
	 // Slider Active
$('.slider_list').owlCarousel({
        loop: true,
        autoplay: false,
        autoplayTimeout: 10000,
        dots: false,
        nav: true,
        navText: ["<i class='fas fa-angle-left''></i>", "<i class='fas fa-angle-right''></i>"],
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 1
            },
            992: {
                items: 1
            },
            1000: {
                items: 1
            },
            1920: {
                items: 1
            }
        }
    });	
     // Slider Active
    $('.bg_list').owlCarousel({
        loop: true,
        autoplay: false,
        autoplayTimeout: 10000,
        dots: false,
        nav: true,
        navText: ["<i class='fas fa-angle-left''></i>", "<i class='fas fa-angle-right''></i>"],
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 1
            },
            992: {
                items: 1
            },
            1000: {
                items: 1
            },
            1920: {
                items: 1
            }
        }
    }); 
    /* Portfolio Isotope  */
    $('.image_load').imagesLoaded(function() {

        if ($.fn.isotope) {

            var $portfolio = $('.image_load');

            $portfolio.isotope({

                itemSelector: '.grid-item',

                filter: '*',

                resizesContainer: true,

                layoutMode: 'masonry',

                transitionDuration: '0.8s'

            });
            $('.menu-filtering li').on('click', function() {

                $('.menu-filtering li').removeClass('current_menu_item');

                $(this).addClass('current_menu_item');

                var selector = $(this).attr('data-filter');

                $portfolio.isotope({

                    filter: selector,

                });

            });

        };

    });

     $('.testimonials_list').owlCarousel({
            loop: true,
            autoplay: true,
            autoplayTimeout: 10000,
            dots: false,
            nav: false,
            navText: ["<i class='fa fa-long-arrow-left''></i>", "<i class='fa fa-long-arrow-right''></i>"],
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 2
                },
                1000: {
                    items: 2
                },
                1920: {
                    items: 2
                }
            }
        }); 

          $('.testimonials_list2').owlCarousel({
            loop: true,
            autoplay: false,
            autoplayTimeout: 10000,
            dots: false,
            nav: false,
            navText: ["<i class='fa fa-long-arrow-left''></i>", "<i class='fa fa-long-arrow-right''></i>"],
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 1
                },
                992: {
                    items: 1
                },
                1000: {
                    items: 1
                },
                1920: {
                    items: 1
                }
            }
        });

        $('.brand_list').owlCarousel({
            loop: true,
            autoplay: true,
            autoplayTimeout: 10000,
            dots: false,
            nav: false,
            navText: ["<i class='fas fa-angle-left''></i>", "<i class='fas fa-angle-right''></i>"],
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1000: {
                    items: 5
                },
                1920: {
                    items: 6
                }
            }
        });     

        $('.case-list').owlCarousel({
            loop: true,
            autoplay: true,
            autoplayTimeout: 10000,
            dots: false,
            nav: true,
            navText: ["<i class='fas fa-angle-left''></i>", "<i class='fas fa-angle-right''></i>"],
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1000: {
                    items: 4
                },
                1920: {
                    items: 5
                }
            }
        });

      //Header Search
    if($('.search-box-outer').length) {
        $('.search-box-outer').on('click', function() {
            $('body').addClass('search-active');
        });
        $('.close-search').on('click', function() {
            $('body').removeClass('search-active');
        });
    }

             
    // Venubox
    $('.venobox').venobox({
        numeratio: true,
        infinigall: true
    });
    

	    // counterUp
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });

// sticky
    var wind = $(window);
    var sticky = $('#sticky-header');
    wind.on('scroll', function () {
        var scroll = wind.scrollTop();
        if (scroll < 100) {
            sticky.removeClass('sticky-nav');
        } else {
            sticky.addClass('sticky-nav');
        }
    });

    //Tabs
     $(function(){
         $("#tabs").tabs();
     });

    //Accordion two
    $( function() {
        $("#accordion-two").accordion();
        
    } );

 //Accordion three
    $( function() {
        $("#accordion-three").accordion();
        
    } );

    //Accordion four
    $( function() {
        $("#accordion-four").accordion();
        
    } );
	
     //Accordion five
    $( function() {
        $("#accordion-five").accordion();
        
    } );
// to wow Jquery
new WOW().init();
    
	
	
	
})(jQuery);

 