/* --------------------------------------------------------------------------
 * Foundstrap     : ZenGarden - Gardening and Landscaping HTML Template
 *
 * file           : config.js
 * Version        : 1.0
 * Author         : Foundstrap - team
 * Author URI     : http://foundstrap.com
 *
 * Foundstrap Studio. Copyright 2016. All Rights Reserved.
 * -------------------------------------------------------------------------- */

 /* --------------------------------------------------------------------------
    [table of contents]
  - Javascript plugin configuration
    1. Menu Configuration
    2. Jquery Sticky Menu
    4. Chart Configuration
    5. jQuery Progress Bar

  - jQuery Handle Initialization
    1. Grid Equal
    2. Count Number
    3. Flex Slider
    4. Flex Slider Height Configuration
    5. Image Parent
    6. Carousel
    7. Scroll Up
    8. Menu Navigation
    9. Search Panel
    10. jQuery Animate
    11. Megamenu Image Background
    12. Gap
    13. Accordion config
    14. Isotope
  * -------------------------------------------------------------------------- */

(function($) {
    "use strict";
    $('[data-toggle="popover"]').popover();
    /* --------------------------------------------------------------------------
     * Menu Configuration
     * -------------------------------------------------------------------------- */
    if ($.fn.smartmenus) {
        $.fn.foundstrapmenu = function (options) {
            return this.each(function() {
                var self = $(this);

                self.smartmenus({
                    mainMenuSubOffsetX  : 0,
                    mainMenuSubOffsetY  : 0,
                    subMenusSubOffsetX  : 0,
                    subMenusSubOffsetY  : -43,
                    subMenusMinWidth    : "12.5em",
                    subIndicatorsText   : '',
                }).find('li.active').children('a').addClass('active');

                self.parent().closest('.menu-container').before('<div class="menu-trigger"><span></span></div>');

                // responsive menu
                $(".menu-trigger").on('click', function(e){
                    e.preventDefault();
                    if($(this).hasClass("active")) {
                        // when menu close
                        $(this).removeClass("active");
                        $('.menu-container').slideUp("slow");
                    } else {
                        // when menu open
                        $(this).addClass("active");
                        $('.menu-container').slideDown("slow");
                    }
                });

                // jQuery when resize window
                $(window).on('resize', function() {
                    if( ($(window).width() > 768) && (!$(".menu-trigger").hasClass("active"))) {
                        self.attr('style','');
                    }
                });
            });
        };
    }
})(jQuery), + (function($) {
    "use strict";
    /* --------------------------------------------------------------------------
     * Jquery Sticky Menu
     * Waypoints Sticky Element Shortcut - 4.0.1
     * Copyright © 2011-2016 Caleb Troughton
     * Licensed under the MIT license.
     * https://github.com/imakewebthings/waypoints/blob/master/licenses.txt
     * -------------------------------------------------------------------------- */

    var Waypoint = window.Waypoint;

    /* http://imakewebthings.com/waypoints/shortcuts/sticky-elements */
    function Sticky(options) {
        this.options = $.extend({}, Waypoint.defaults, Sticky.defaults, options);
        this.element = this.options.element;
        this.$element = $(this.element);
        this.createWrapper();
        this.createWaypoint();
    }

    /* Private */
    Sticky.prototype.createWaypoint = function() {
        var originalHandler = this.options.handler;

        this.waypoint = new Waypoint($.extend({}, this.options, {
            element: this.wrapper,
            handler: $.proxy(function(direction) {
                var shouldBeStuck = this.options.direction.indexOf(direction) > -1;
                var wrapperHeight = shouldBeStuck ? this.$element.outerHeight(true) : '';

                this.$wrapper.height(wrapperHeight);
                this.$element.toggleClass(this.options.stuckClass, shouldBeStuck);

                if (originalHandler) {
                    originalHandler.call(this, direction);
                }
            }, this)
        }));
    };

    /* Private */
    Sticky.prototype.createWrapper = function() {
        if (this.options.wrapper) {
            this.$element.wrap(this.options.wrapper);
        }
        this.$wrapper = this.$element.parent();
        this.wrapper = this.$wrapper[0];
    };

    /* Public */
    Sticky.prototype.destroy = function() {
        if (this.$element.parent()[0] === this.wrapper) {
            this.waypoint.destroy();
            this.$element.removeClass(this.options.stuckClass);
            if (this.options.wrapper) {
                this.$element.unwrap();
            }
        }
    };

    Sticky.defaults = {
        wrapper: '<div class="sticky-wrapper">',
        stuckClass: 'header-on-sticky animate__animated animate__fadeInDown',
        direction: 'down'
    };

    Waypoint.Sticky = Sticky;
})(jQuery) + (function($) {
    "use strict";
    /* --------------------------------------------------------------------------
     * Chart Configuration
     * -------------------------------------------------------------------------- */

    // jQuery easypiechart constructor
    if ($.fn.easyPieChart) {
        var defaultset = {
            barColor    : '#95a5a6',
            trackColor  : '#f1f1f1',
            scaleColor  : false,
            lineCap     : 'round',
            scaleLength : 0,
            lineWidth   : 16,
            size        : 220,
            responsive  : true,
            animation   : 5000,
            font        : 16,
            bgColor     : false
        };

        $.fn.circleBar = function (options) {

            return this.each(function() {

                var circleBar = $.extend({}, defaultset, options);
                var setSize, widthElement;
                var defaultChartSize = 100;

                if (circleBar.responsive) { setSize = 100; }
                else { setSize = circleBar.size; }

                if(setSize !== defaultChartSize) {
                    widthElement = setSize;
                } else {
                    widthElement = $(this).parent().width();
                }

                $(this).css({
                    'height'       : widthElement,
                    'width'        : widthElement,
                    'position'     : 'relative',
                    'display'      : 'block',
                    'margin'       : '0 auto 20px',
                    'text-align'   : 'center',
                });

                $(this).append("<div class='percent'></div>");

                $(this).find(".percent").css({
                    'position'      : 'absolute',
                    'top'           : 0,
                    'left'          : 0,
                    'line-height'   : widthElement + 'px',
                    'width'         : widthElement + 'px',
                    'text-align'    : 'center',
                    'font-size'     : circleBar.font + 'px',
                    'font-weight'   : 300
                });

                if (circleBar.bgColor) {
                    widthElement = widthElement - circleBar.lineWidth;
                    $(this).css({'padding': circleBar.lineWidth / 2 });
                }

                $(this).easyPieChart({
                    barColor    : circleBar.barColor,
                    trackColor  : circleBar.trackColor,
                    scaleColor  : circleBar.scaleColor,
                    lineCap     : circleBar.lineCap,
                    lineWidth   : circleBar.lineWidth,
                    size        : widthElement,
                    animation   : circleBar.animation,
                    scaleLength : circleBar.scaleLength,

                    onStep: function(from, to, percent) {
                        $(this.el).find('.percent').text(Math.round(percent)+'%');
                    }
                });
            });
        };
    }
    else {
        console.log('easyPieChart jQuery plugin not found');
    }
})(jQuery), + (function($) {
    "use strict";
    /* --------------------------------------------------------------------------
     * jQuery Progress Bar
     * -------------------------------------------------------------------------- */

    $.fn.progressBar = function () {
        return this.each(function() {
            var _thisBar = $(this);
            var percentage = $(this).attr('data-percent');

            var progressBarWidth = ( percentage * _thisBar.width() ) / 100;

            _thisBar.find('.progress-content').append("<div class='progress-meter'></div>").animate({
                width : progressBarWidth,
                number : percentage
            }, {
                duration: 2000,
                step: function(number) {
                    _thisBar.find('.progress-meter').text(Math.round(number) + '%');
                }
            });
        });
    };
})(jQuery) + (function($) {
    "use strict";
    /* --------------------------------------------------------------------------
     * jQuery Handle Initialization
     * -------------------------------------------------------------------------- */

    var themeApp = {

        /* ----------- Count Number ----------- */
        theme_counterNumber: function() {
            if ($(".count-me").length) {
                if (!$(window).width() <= 767) {
                    var waypoint = new Waypoint({
                        element: document.getElementsByClassName('count-me'),
                        handler: function() {
                            $('.count-me').each(function() {
                                $(this).data('countToOptions', {
                                    formatter: function (value, options) {
                                        return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
                                    }
                                });

                                $('.number').each(count);

                                function count(options) {
                                    var $this = $(this);
                                    options = $.extend({}, options || {}, $this.data('countToOptions') || {});
                                    $this.countTo(options);
                                }
                            });

                            this.destroy();
                        },
                        offset: '90%'
                    });
                }
            }
        },

        /* ----------- Flex Slider ----------- */
        theme_flexSlider: function() {
            var flexSlideshow = $(".slideshow"),
                flexFullScreen = flexSlideshow.data('flex-fullscreen'),
                headerHeight = 0;

            flexSlideshow.each(function() {
                if(flexFullScreen) {
                    var parentHeader = $('body').find("#header");

                    if( parentHeader.hasClass('header-version2') || parentHeader.hasClass('header-version3') ) {
                        setHeaderSlider($(this), parentHeader.height());
                    } else {
                        setHeaderSlider($(this), headerHeight);
                    }
                }

                $(this).find('.slides > li').each(function(){
                    var getImage = $(this).find("img"),
                        imageSrc = getImage.attr("src"),
                        imageX = getImage.data('position-x'),
                        imageY = getImage.data('position-y');

                    (imageX) ? imageX : imageX = 'center';
                    (imageY) ? imageY : imageY = 'center';

                    $(this).css({
                        'background' : 'url("'+ imageSrc +'") '+ imageX +' '+ imageY +' no-repeat no-repeat',
                        'background-size' : 'cover'
                    });

                    getImage.hide();
                });

                function setHeaderSlider(e, headerHeight) {
                    e.css('height', $(window).height() - headerHeight);

                    $(window).resize(function() {
                        $(".slideshow").css('height', $(window).height() - headerHeight);
                    });
                }
            });

            flexSlideshow.flexslider({
                animation: "fade",
                controlNav: false,
                directionNav: true,
                slideshowSpeed: 7000,
                pauseOnHover: "true",
                prevText: "",
                nextText: "",
                start: function(slider) {
                    flexStartAnimate(slider);
                },
                end: function(slider) {
                    flexResetAnimate(slider);
                },
                before: function(slider) {
                    flexResetAnimate(slider);
                    themeApp.theme_flexSliderHeight();
                },
                after: function(slider){
                    flexStartAnimate(slider);

                    // bug after click nav
                    if (!slider.playing) {
                        slider.play();
                    }
                }
            });

            function flexResetAnimate(slider) {
                slider.find(".flex-animate").each(function() {
                    var animateElement = $(this),
                    animateItem  = animateElement.attr('data-animate'),
                    animateDelay = animateElement.attr('data-animate-delay');
                    animateElement.removeClass(animateItem);
                    animateElement.removeClass("animate__animated");
                    animateElement.css("opacity","0");
                });
            }

            function flexStartAnimate(slider) {
                slider.find('.flex-active-slide').find(".flex-animate").each(function(e,i) {
                    var animateElement = $(this),
                    animateItem  = animateElement.attr('data-animate'),
                    animateDelay = animateElement.attr('data-animate-delay');

                    if( animateDelay > 0 ) {
                        var delayTime = (animateDelay / 1000) + 's';

                        animateElement.css({
                            'visibility'              : 'visible',
                            '-webkit-animation-delay' : delayTime,
                            '-moz-animation-delay'    : delayTime,
                            '-o-animation-delay'      : delayTime,
                            'animation-delay'         : delayTime
                        });
                    }

                    animateElement.css('opacity', '1');
                    animateElement.addClass('animate__animated ' + animateItem);
                });
            }
        },

        /* ----------- Flex Slider Height Configuration ----------- */
        theme_flexSliderHeight: function() {
            var sliderHeight = null;

            $('.slideshow').each(function() {
                $(this).find('.slides > li').each(function() {
                    sliderHeight = $(this).height();
                    if (sliderHeight < sliderHeight) {
                        sliderHeight = sliderHeight;
                    }
                });
            });

            $(".flex-content-wrapper").css("height", sliderHeight);
        },

        /* ----------- Image Parent ----------- */
        theme_imgParent: function() {
            $('.set-image-bg').each(function() {
                var parentClass = $(this).data('parent'),
                parentDiv = $(this).closest('.'+ parentClass),
                imageSrc = $(this).attr('src');

                parentDiv.css({
                    "background" : 'url("'+ imageSrc +'") center center no-repeat no-repeat',
                    "background-size" : "cover"
                });

                $(this).hide();
            });
        },

        /* ----------- Carousel ----------- */
        theme_carousel: function() {
            $('.project-carousel').addClass('owl-carousel').owlCarousel({
                loop:true,
                dots:false,
                autoplay: true,
                autoplayTimeout: 2500,
                items: 1
            });

            $('.testimonial-carousel').addClass('owl-carousel').owlCarousel({
                loop:true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 2750,
                items: 1
            });

            $('.post-type-carousel').addClass('owl-carousel').owlCarousel({
                loop:true,
                dots:false,
                nav: true,
                navText:['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>'],
                autoplay: true,
                autoplayTimeout: 3000,
                animateIn: 'fadeIn',
                animateOut: 'fadeOut',
                items: 1
            });

            $('.client-carousel').addClass('owl-carousel').owlCarousel({
                loop:true,
                dots:false,
                autoplay: true,
                autoplayTimeout: 2500,
                responsive: {
                    0    : { items: 1 },
                    568  : { items: 2 },
                    768  : { items: 3 },
                    992  : { items: 4 },
                    1200 : { items: 5 }
                }
            });

            $('.service-item-carousel').addClass('owl-carousel').owlCarousel({
                loop:true,
                dots:false,
                autoplay: true,
                autoplayTimeout: 2500,
                responsive: {
                    0    : { items: 1 },
                    768  : { items: 2 },
                    992  : { items: 3 },
                    1200 : { items: 4 }
                }
            });

            $('.service-carousel').flexslider({
                animation: "slide",
                controlNav: "thumbnails"
            });
        },

        /* ----------- Scroll Up ----------- */
        theme_scrollUp: function() {
            $.scrollUp({
                scrollText: '<i class="fa fa-chevron-up"></i>',
                scrollSpeed: 500,
                easingType: 'swing'
            });
        },

        /* ----------- Menu Navigation ----------- */
        theme_menu_nav: function() {
            $("#menu").foundstrapmenu();

            if( $(window).width() >= 1170 ) {
                var headerSticky = $('.header-main');

                var sticky = new Waypoint.Sticky({
                    element: headerSticky[0]
                });
            }
        },

        /* ----------- Search Panel ----------- */
        theme_search_panel: function() {
            $(".search-btn").on('click', function(e) {
                e.preventDefault();
                if ($(".search-panel").is(":visible")){
                    // Hide - slide up.
                    $(this).removeClass("active");
                    $(".search-panel").fadeOut("slow");
                } else {
                    // Show - slide down.
                    $(this).addClass("active");
                    $(".search-panel").fadeIn("slow");
                }
            });

            $(document).mouseup(function (e) {
                var navContainer = $('.search-panel'),
                    triggerNav = $(".search-btn");

                if (!navContainer.is(e.target) && navContainer.has(e.target).length === 0 && !triggerNav.is(e.target) && triggerNav.has(e.target).length === 0) {
                    navContainer.fadeOut("slow");
                    triggerNav.removeClass('active');
                }
            });
        },

        /* ----------- Me Account Panel ----------- */
        theme_meaccount_panel: function() {
            $(".meaccount-btn").on('click', function(e) {
                e.preventDefault();
                if ($(".meaccount-panel").is(":visible")){
                    // Hide - slide up.
                    $(this).removeClass("active");
                    $(".meaccount-panel").fadeOut("slow");
                } else {
                    // Show - slide down.
                    $(this).addClass("active");
                    $(".meaccount-panel").fadeIn("slow");
                }
            });

            $(document).mouseup(function (e) {
                var navContainer = $('.meaccount-panel'),
                    triggerNav = $(".meaccount-btn");

                if (!navContainer.is(e.target) && navContainer.has(e.target).length === 0 && !triggerNav.is(e.target) && triggerNav.has(e.target).length === 0) {
                    navContainer.fadeOut("slow");
                    triggerNav.removeClass('active');
                }
            });
        },

        /* ----------- jQuery Animate ----------- */
        theme_animate: function() {
            if ($(window).width() <= 767) {
                $('.me-animate').css('opacity', '1');
            } else {
                foundstrapAnimate( $('.me-animate') );
            }

            function foundstrapAnimate( items, trigger ) {
                items.each( function() {
                    var animateElement = $(this),
                    animateItem  = animateElement.attr('data-animate'),
                    animateDelay = animateElement.attr('data-animate-delay');

                    if( animateDelay > 0 ) {
                        var delayTime = (animateDelay / 1000) + 's';

                        animateElement.css({
                            'visibility'              : 'visible',
                            '-webkit-animation-delay' : delayTime,
                            '-moz-animation-delay'    : delayTime,
                            '-o-animation-delay'      : delayTime,
                            'animation-delay'         : delayTime
                        });
                    }

                    var animateTrigger = ( trigger ) ? trigger : animateElement;

                    var waypoints = animateTrigger.waypoint({
                        handler: function() {
                            animateElement.css('opacity', '1');
                            animateElement.addClass('animate__animated ' + animateItem);

                            this.destroy();
                        },
                        offset: '80%'
                    });
                });
            }
        },

        /* ----------- Megamenu Image Background ----------- */
        theme_megamenu_img: function() {
            $(".mega-menu-container").each(function() {
                var getImg = $(this).find(".mega-menu-img");

                if(getImg.length > 0) {
                    var imgSrc = getImg.attr("src"),
                        imgPosition = getImg.data("position");

                    getImg.hide();
                    $(this).css({
                        'background' : 'url('+ imgSrc +') no-repeat no-repeat ' + imgPosition
                    });
                }
            });
        },

        /* ----------- Gap ----------- */
        theme_gap: function() {
            $('.gap').each(function () {
                var self = $(this),
                dataGap = self.data();

                $.each(dataGap, function(direction, val) {
                    if (direction == "gapTop")    { self.css("margin-top", val+'px'); }
                    if (direction == "gapBottom") { self.css("margin-bottom", val+'px'); }
                    if (direction == "gapLeft")   { self.css("margin-left", val+'px'); }
                    if (direction == "gapRight")  { self.css("margin-right", val+'px'); }
                });
            });
        },

        /* ----------- Isotope ----------- */
        theme_isotope: function() {
            var l = $(".gallery-container");
            l.isotope({
                filter: "*",
                animationOptions: {
                    duration: 750,
                    easing: "linear",
                    queue: false
                }
            });

            $(".gallery-filter a").on('click', function () {
                var n = $(this).attr("data-filter");
                l.isotope({
                    filter: n,
                    animationOptions: {
                        duration: 750,
                        easing: "linear",
                        queue: false
                    }
                });
                return false;
            });

            var k = $(".gallery-filter"),
            m = k.find("a");
            m.on('click', function () {
                var o = $(this);
                if (o.hasClass("active")) {
                    return false;
                }

                var n = o.parents(".gallery-filter");
                n.find(".active").removeClass("active");
                o.addClass("active");
            });
        },

        // theme init
        theme_init_ready: function() {
            themeApp.theme_animate();
            themeApp.theme_menu_nav();
            themeApp.theme_gap();
            themeApp.theme_flexSlider();
            themeApp.theme_flexSliderHeight();
            themeApp.theme_imgParent();
            themeApp.theme_carousel();
            themeApp.theme_scrollUp();
            themeApp.theme_counterNumber();
            themeApp.theme_megamenu_img();
            themeApp.theme_search_panel();
            themeApp.theme_meaccount_panel();
            themeApp.theme_isotope();
        },

        theme_init_resize:function() {
            themeApp.theme_flexSliderHeight();
        }
    };

    // ready function
    jQuery(document).ready(function($) {
        themeApp.theme_init_ready();

        if ( $(window).width() <= 767 ) {
            $('.section-parallax').css('background-size','cover');
        }

        var widthScreen = $(window).width(),
            mobileScreen = (widthScreen < 480);

        if(mobileScreen) {
            $('.post-item').each(function() {
                $(this).find('.date-meta').insertAfter($(this).find('.post-title'));
            });
        }
    });

    // resize function
    jQuery(window).on('resize', function($) {
        themeApp.theme_init_resize();
    });
})(jQuery);
