// ------  ACTIVE MENU ELEMENT  ------ //
$(".nav-item").each(function () {
    var location2 = window.location.protocol + '//' + window.location.host + window.location.pathname;
    var link = this.href;

    // alert("link: " + link + " AND current: " + location2)
    if (location2.startsWith(link)) {
        $(this).addClass('active-nav-item');
    }
});

var location2 = window.location.pathname;
if (location2.startsWith('/email/verify')) {
    $(".nav-item").first().addClass('active-nav-item');
}
$(".menu-link").each(function () {
    var location2 = window.location.href;
    var link = $(this).attr('href');
    if (window.location.pathname.startsWith('/myaccount'))
    {$('.account a').addClass('active-menu-link');}

    if (window.location.pathname.startsWith('/collections'))
    { $('a[href$="our_collections"]').addClass('active-menu-link');}

    if(window.location.pathname === '/')
    {
        $('#home').addClass('active-menu-link')
        $('#home_mobile').addClass('active-menu-link')
    }
    if (location2.startsWith(link)) {

            $('#home').removeClass('active-menu-link')
            $('#home_mobile').removeClass('active-menu-link')
            $(this).addClass('active-menu-link');}
});


$(".menu__item").each(function () {
    var location2 = window.location.href;
    var link = $(this).attr('href');

    if (link === '/myaccount/collections')
    {link = '/myaccount'}

    if(window.location.pathname === '/')
    {
        $('#home').addClass('active-menu-link')
        $('#home_mobile').addClass('active-menu-link')
    }

    if (location2.startsWith(link)) {

            $('#home').removeClass('active-menu-link')
            $('#home_mobile').removeClass('active-menu-link')
            $(this).addClass('active-menu-link');}

});
// ------  // ACTIVE MENU ELEMENT  ------ //


// // ------  FAST NAVIGATION  ------ //
// $(function () {
//     var load = function (url) {
//         $.get(url).done(function (data) {
//             $(".account-content").html(data);
//             window.livewire.start();
//             window.livewire.rescan();
//
//         });
//     };
//
//     $(document).on('click', 'a.nav-item', function (e) {
//         e.preventDefault();
//         if ($(this).attr('class') == 'nav-item') {
//             // Change background of menu element
//             $('.active-nav-item').removeClass('active-nav-item');
//             $(this).addClass('active-nav-item');
//         }
//         var $this = $(this),
//             url = $this.attr("href"),
//             title = $this.text();
//
//         history.pushState({
//             url: url,
//             title: title
//         }, title, url);
//
//        document.title = title;
//
//         load(url);
//         // window.livewire.restart();
//
//     });
//
//     $(window).on('popstate', function (e) {
//
//         var state = e.originalEvent.state;
//         if (state !== null) {
//             document.title = state.title;
//             load(state.url);
//         } else {
//             $(".account-content").empty();
//         }
//         $(".nav-item").each(function () {
//             var location2 = window.location.protocol + '//' + window.location.host + window.location.pathname;
//             var link = this.href;
//             $(this).removeClass('active-nav-item');
//             if (location2.startsWith(link)) {
//                 $(this).addClass('active-nav-item');
//             }
//         });
//
//     });
//
//
// });
// // ------  / FAST NAVIGATION  ------ //


// ------  MODALS  ------ //

modals = $('.modal-from')
var modal_on = 0;

for (var i = 0; i < modals.length; i++) {
    modals[i].addEventListener('click', function () {
        modal = $(this).attr('data-modal');
        // alert(modal);
        $('#' + modal).fadeToggle(200);
        // $('#' + modal + " .modal-content").fadeToggle(500);
        setTimeout(function(){modal_on = 1}, 1000)
    }, false);

    $(document).on("click", function (event) {
        if (!$(event.target).closest(".modal-content").length) {
            if (modal_on == 1) {
                $('.modal').fadeOut(200);
                if ($('#video_hero_iframe')) {
                    $("#video_hero_iframe").attr('src','https://www.youtube.com/embed/q9YOJS_6FMg');
                }
                modal_on = 0;
            }
        }
    });
}
// ------  / MODALS  ------ //


// ------  PRELOADER  ------ //
window.onload = function () {
    $('.book-preloader-wrap').addClass('preloaded_hiding');
    window.setTimeout(function () {
        $('.book-preloader-wrap').addClass('preloaded_loaded');
        $('.book-preloader-wrap').removeClass('preloaded_hiding');
    }, 500);
}
// ------  //// PRELOADER  ------ //


// ------  SMOOTH SCROLLING  ------ //
// Select all links with hashes
$('a[href*="#"]')
    // Remove links that don't actually link to anything
    .not('[href="#"]')
    .not('[href="#0"]')
    .click(function(event) {
        // On-page links
        if (
            location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
            &&
            location.hostname == this.hostname
        ) {
            // Figure out element to scroll to
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            // Does a scroll target exist?
            if (target.length) {
                // Only prevent default if animation is actually gonna happen
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 1000, function() {
                    // Callback after animation
                    // Must change focus!
                    var $target = $(target);
                    $target.focus();
                    if ($target.is(":focus")) { // Checking if the target was focused
                        return false;
                    } else {
                        $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
                        $target.focus(); // Set focus again
                    };
                });
            }
        }
    });
// ------ // SMOOTH SCROLLING  ------ //










