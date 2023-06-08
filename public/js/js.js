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
    var link = $(this).prop('href');

    if (location2 == link) {
        $(this).addClass('active-menu-link');
    }

    if (window.location.pathname == '/register') {
        $('#a_modal_login').addClass('active-menu-link');
    }

    // if (window.location.pathname.startsWith('/myaccount'))
    // {$('.account a').addClass('active-menu-link');}
    //
    // if (window.location.pathname.startsWith('/collections'))
    // { $('a[href$="our_collections"]').addClass('active-menu-link');}
    //
    // if(window.location.pathname === '/')
    // {
    //     $('#home').addClass('active-menu-link')
    //     $('#home_mobile').addClass('active-menu-link')
    // }
    // if (location2.startsWith(link)) {
    //
    //         $('#home').removeClass('active-menu-link')
    //         $('#home_mobile').removeClass('active-menu-link')
    //         $(this).addClass('active-menu-link');}
});


$(".menu__item").each(function () {
    var location2 = window.location.href;
    var link = $(this).attr('href');

    if (link === '/myaccount/collections') {
        link = '/myaccount'
    }

    if (window.location.pathname === '/') {
        $('#home').addClass('active-menu-link')
        $('#home_mobile').addClass('active-menu-link')
    }

    if (location2.startsWith(link)) {

        $('#home').removeClass('active-menu-link')
        $('#home_mobile').removeClass('active-menu-link')
        $(this).addClass('active-menu-link');
    }

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
        setTimeout(function () {
            modal_on = 1
        }, 1000)
    }, false);

    $(document).on("click", function (event) {
        if (!$(event.target).closest(".modal-content").length) {
            if (modal_on == 1) {
                $('.modal').fadeOut(200);
                if ($('#video_hero_iframe')) {
                    $("#video_hero_iframe").attr('src', 'https://www.youtube.com/embed/q9YOJS_6FMg');
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
window.setTimeout(function () { // хардкорно выключаем долгий прелоадер
    $('.book-preloader-wrap').addClass('preloaded_loaded');
    console.log('started!')
}, 4000);
// ------  //// PRELOADER  ------ //


// ------  SMOOTH SCROLLING  ------ //
// Select all links with hashes
$('a[href*="#"]')
    // Remove links that don't actually link to anything
    .not('[href="#"]')
    .not('[href="#0"]')
    .click(function (event) {
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
                }, 1000, function () {
                    // Callback after animation
                    // Must change focus!
                    var $target = $(target);
                    $target.focus();
                    if ($target.is(":focus")) { // Checking if the target was focused
                        return false;
                    } else {
                        $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                        $target.focus(); // Set focus again
                    }
                    ;
                });
            }
        }
    });
// ------ // SMOOTH SCROLLING  ------ //


// Auto resize textarea
function auto_grow(element, start_height) {
    // $(element).closest('.input-block').css('height', start_height + "px");
    $(element).closest('.input-block').css('height', 100 + "px");
    final_height = element.scrollHeight;
    $(element).closest('.input-block').css('height', final_height + 2 + "px");
};


$('.show_preloader_on_click').click(function () {
    $(this).css('width', $(this).innerWidth())
    $(this).css('height', $(this).innerHeight())
    $(this).css('background', 'none');
    $(this).css('color', 'white');
    $(this).css('disabled', true);
    $(this).css('cursor', 'wait');
    $(this).html('<span class="button--loading"></span>')
})


function show_modal_function() {


    $('.show_modal').click(function (e) {
        e.preventDefault();
        modal_object_id = $(this).attr('data-for-modal');
        modal_object = $('#' + modal_object_id);
        $('.cus-modal-wrap').append(modal_object);

        $('#' + modal_object_id).show();
        $('.cus-modal').fadeIn();
    })


    $('.cus-modal').on('click', function (event) {
        if ($(event.target).has('.cus-modal-container').length === 1) {
            $('.cus-modal-container').hide();
            $('.cus-modal').fadeOut();
        }
    });
}

show_modal_function();


function like_icon_animation_function() {

    $('.like_icon').click(function () {
        if ($('#user_id_logged_in').attr('data-user_id') > 0) {
            const icon = $(this)
            icon.addClass('fa-beat');

            setTimeout(function () {
                icon.removeClass('fa-beat');
            }, 100);
        }
    })
}

like_icon_animation_function()


$('.cus-dropdown').click(function (e) {
    e.preventDefault();
    e.stopPropagation();
    $('.cus-dropdown').not(this).each(function(){
        $(this).removeClass('expanded');
        $(this).removeClass('overflow-auto');
    });


    clicked_el = $(this);
    clicked_el.toggleClass('expanded');

    if(clicked_el.hasClass('expanded')) {
        wait = 200
    } else {
        wait = 0
    }
    setTimeout(function() {
        clicked_el.toggleClass('overflow-auto')
    }, wait)


});


$('.cus-dropdown label').click(function (e) {
    old_val = $(this).siblings('input[type=radio]:checked').val();
    new_val = $('#' + $(e.target).attr('for')).val();

    if (old_val != new_val) {

        $('#' + $(e.target).attr('for')).prop('checked', true).trigger('change');
    }
})

$('.cus-dropdown label').click(function (e) {
    $('.cus-dropdown').scrollTop(0);
    // alert(clicked_text);
});


// Покзываем и скрываем прелоадер на кнопке отправки сообщений
$('.send_mes_button').on('click', function () {
    $(this).children('.button--loading').show();
    $(this).children('.tooltip').css('opacity', 0);
    setTimeout(function () {
       $('.send-wrap button').prop("disabled", true);
    }, 100)

})

// Покзываем и скрываем прелоадер на кнопке "загрузить еще"
function make_load_more_preloader() {
    $('#load_more').click(function () {
        $(this).css('height', "30px")
        $(this).text("")
        $(this).addClass('button--loading');
        $(this).css('background', "none")
        $('.user_work_text_wrap').attr('wire:ignore', '');
        // Livewire.emit('load_more');
    })
}
make_load_more_preloader()





$(document).click(function (e) {
    el = $('.cus-dropdown')
    el.scrollTop(0);
    el.removeClass('expanded');
    el.removeClass('overflow-auto');
    el.css('overflow', 'hidden');

});















