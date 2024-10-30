(function($) {
    "use strict";
    $(function() {
        function mimo_masonry_newswall() {
            var mimo_masonry_container = $('.mimo-masonry-masonry-widget');
            mimo_masonry_container.imagesLoaded(function() {
                mimo_masonry_container.isotope({
                    itemSelector: 'article.mimo-masonry-masonry-item'
                }).isotope('layout').css({
                    'visibility': 'visible'
                });
            });
        }
        // Button Infinite Scroll
        function mimo_masonry_infinitewall() {
            var mimo_masonry_container = $('.mimo-masonry-infinite-widget');
            mimo_masonry_container.infinitescroll({
                loading: {
            finished: function() {
                $('nav.mimo-masonry-nav-links').show();
                $('.mimo-masonry-loading').remove();
            },
            finishedMsg: '',
            msg: $('<div class="mimo-masonry-loading"><div></div></div>'),
            msgText: '',
            img:'',
            selector: '.mimo-masonry-page-nav',
            speed: 'fast',
            start: undefined
        },
                navSelector: 'nav.mimo-masonry-nav-links',
                nextSelector: 'nav.mimo-masonry-nav-links   a',
                itemSelector: 'article.mimo-masonry-masonry-item', // selector for all items you'll retrieve
                debug: false,
                errorCallback: function(){
                    $('nav.mimo-masonry-nav-links').remove();
                $('.mimo-masonry-loading').remove();
                $('.mimo-masonry-page-nav').addClass('mimo-masonry-endofpage').delay(2000).fadeOut();
                }
            }, function(newElements, navSelector) {
                var $newElems = $(newElements);
                $newElems.hide();
               
                
                $newElems.imagesLoaded(function() {
                    if ($('.mimo-masonry-masonry-widget').length > 0) {
                        mimo_masonry_container.isotope('appended', $newElems);
                    }
                    
                    
                    $newElems.show();
                });
            });
            if ($('.mimo-masonry-infinite-button').length > 0) {
                $(window).unbind('.infscr');
            }
            if ($('.mimo-masonry-infinite-button').length > 0) {
                $('nav.mimo-masonry-nav-links a:last').click(function() {
                    
                    var mimo_masonry_container = $('.mimo-masonry-infinite-widget');
                    mimo_masonry_container.infinitescroll('retrieve');
                    if ($('.mimo-masonry-masonry-widget').length > 0) {
                        mimo_masonry_container.isotope('layout');
                    }
                    
                    $(window).unbind('.infscr');
                    return false;
                });
            }
        }
        //Filter masonry layout
        function mimo_masonry_filterelems() {
            $('.mimo-masonry-filter').on('click', 'a', function() {
                $('.mimo-masonry-filter a').removeClass("selected");
                $(this).addClass('selected');
                var filterValue = $(this).attr('data-filter');
                var mimo_masonry_container = $('.mimo-masonry-masonry-widget');
                mimo_masonry_container.isotope({
                    filter: filterValue
                });
            });
        }

        


        $(window).load(function() {
            mimo_masonry_newswall();
            mimo_masonry_infinitewall();
            mimo_masonry_filterelems();
        });
        $(window).ajaxComplete(function() {
            mimo_masonry_newswall();
        });
        $('body').resize(function() {
            var mimo_masonry_container = $('.mimo-masonry-infinitewidget');
            if ($('.mimo-masonry-masonrywidget').length > 0) {
                mimo_masonry_container.isotope('layout');
            }
        });
    });
}(jQuery));