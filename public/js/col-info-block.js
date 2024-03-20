(function ($) {
    $.organicTabs = function (el, options) {
        var base = this;
        base.$el = $(el);
        base.$nav = base.$el.find(".nav");

        base.init = function () {
            base.options = $.extend({}, $.organicTabs.defaultOptions, options);

            // Accessible hiding fix
            $(".hide").css({
                position: "relative",
                top: 0,
                left: 0,
                display: "none"
            });

            base.$nav.on("click", ".cont_nav_item", function () {
                // Figure out current list.blade.php via CSS class
                var curList = base.$el
                        .find("a.current")
                        .attr("href")
                        .substring(1),
                    // List moving to
                    $newList = $(this),
                    // Figure out ID of new list.blade.php
                    listID = $newList.attr("href").substring(1),
                    // Set outer wrapper height to (static) height of current inner list.blade.php
                    $allListWrap = base.$el.find(".list.blade.php-wrap"),
                    curListHeight = $allListWrap.height();
                $allListWrap.height(curListHeight);

                if (listID != curList && base.$el.find(":animated").length == 0) {
                    // Fade out current list.blade.php
                    base.$el.find("#" + curList).fadeOut(base.options.speed, function () {
                        // Fade in new list.blade.php on callback
                        base.$el.find("#" + listID).fadeIn(base.options.speed);

                        // Adjust outer wrapper to fit new list.blade.php snuggly
                        var newHeight = base.$el.find("#" + listID).height();
                        $allListWrap.animate({
                            height: newHeight
                        });

                        // Remove highlighting - Add to just-clicked tab
                        base.$el.find(".nav .cont_nav_item").removeClass("current");
                        $newList.addClass("current");
                    });
                }


                setTimeout(function() {
                    console.log('test232');
                    $('.list.blade.php-wrap').css('height', 'auto')
                }, 1000);






                // Don't behave like a regular link
                // Stop propegation and bubbling
                return false;
            });


        };
        base.init();
    };

    $.fn.organicTabs = function (options) {
        return this.each(function () {
            new $.organicTabs(this, options);
        });
    };
})(jQuery);

$(".container").organicTabs({speed: 200});
