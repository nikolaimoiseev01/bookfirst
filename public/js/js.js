function trigger_all_js() {
//region -- Выбранный пункт в хедере


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
//endregion

//region -- Инициализация модалок
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
//endregion

//region -- Основной SWAL
    window.addEventListener('swal:modal', event => {
        Swal.fire({
            title: event.detail.title,
            icon: event.detail.type,
            html: "<p>" + event.detail.text + "</p>",
            showConfirmButton: false,
        })
    })
//endregion

//region -- Прелоадер
    window.onload = function () {
        $('.preloader_wrap').addClass('preloaded_hiding');
        window.setTimeout(function () {
            $('.preloader_wrap').addClass('preloaded_loaded');
            $('.preloader_wrap').removeClass('preloaded_hiding');
        }, 500);
    }
    window.setTimeout(function () { // хардкорно выключаем долгий прелоадер
        $('.preloader_wrap').addClass('preloaded_loaded');
        $('.preloader_wrap').removeClass('preloaded_hiding');
    }, 4000);
//endregion

//region -- Плавная прокрутка
// Select all links with hashes
    $('a[href*="#"]')
        // Remove links that don't actually link to anything
        .not('[href="#"]')
        .not('[href="#0"]')
        .not(".nav a")
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
//endregion

//region -- Прелоадер при клике

    function make_button_preloader() {
        $('.show_preloader_on_click').on('click', function () {
            if (!$(this).hasClass('disabled')) {
                $(this).css('width', $(this).outerWidth())
                $(this).css('height', $(this).outerHeight())
                $(this).html('<span class="button--loading"></span>')
                $(this).addClass('loading_process')
            }
        })
    }

    make_button_preloader()
//endregion

//region -- Подстановка имени файла в инпут
    function update_file_input() {
        file_input = $('.custom-file-input')
        for (var i = 0; i < file_input.length; i++) {
            file_input[i].addEventListener('change', function () {
                cut_dots = '';
                var fileName = document.getElementById($(this).attr('name')).files[0].name;
                if (fileName.length > 30) {
                    cut_dots = '...'
                }
                $("#label_" + $(this).attr('name')).show();
                $("#label_" + $(this).attr('name') + ' p').html(fileName.substring(0, 30) + cut_dots);

            }, false);
        }
    }

    update_file_input()
//endregion

//region -- "Еще нет на амазоне" кнопка
    $(".no_amazon").click(function (event) {
        event.preventDefault();
        Swal.fire({
            html: '<p  >На данный момент идет процесс добавления данного сборника на сайт Amazon.com. Ссылка станет активной в ближайшее время.</p>' +
                '<p style="margin-top: 10px; margin-bottom: 20px;">\n Мы предлагаем настроить оповещение Google для ISBN, чтобы получать уведомления о появлении книги в интернет-магазинах.</p>' +
                '<a target="_blank" href="https://support.google.com/websearch/answer/4815696?visit_id=637674760899190323-326960395&hl=ru&rd=3" class="button">Инструкция</a>',
            icon: 'info',
            showConfirmButton: false,
        })
    });
//endregion

//region -- Фиксирование UTM метки
    var utm_source_cookie;
    var utm_medium_cookie;

    function getCook(cookiename) {
        // Get name followed by anything except a semicolon
        var cookiestring = RegExp(cookiename + "=[^;]+").exec(document.cookie);
        // Return everything after the equal sign, or an empty string if the cookie name not found
        return decodeURIComponent(!!cookiestring ? cookiestring.toString().replace(/^[^=]+./, "") : "");
    }

    function getParameters() {
        let urlString = window.location.toString();
        let paramString = urlString.split('?')[1];
        let queryString = new URLSearchParams(paramString);
        for (let pair of queryString.entries()) {
            if (pair[0] == 'utm_source') {
                utm_source = pair[1]
                document.cookie = "utm_source=" + utm_source;
            } else if (pair[0] == 'utm_medium') {
                utm_medium = pair[1]
                document.cookie = "utm_medium=" + utm_medium;
            }
        }
    }


    utm_source_cookie = getCook('utm_source');
    utm_medium_cookie = getCook('utm_medium');

    if (utm_source_cookie === '') {
        getParameters();
        utm_source_cookie = getCook('utm_source');
        utm_medium_cookie = getCook('utm_medium');
    }
//endregion

//region -- Новогодние снежинки
// {{--<script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>--}}
// {{--<script>--}}
//     {{--    new Snowflakes({--}}
//     {{--        color: '#5ECDEF', // Default: "#5ECDEF"--}}
//     {{--        container: document.body, // Default: document.body--}}
//     {{--        count: 20, // 100 snowflakes. Default: 50--}}
//     {{--        minOpacity: 0.4, // From 0 to 1. Default: 0.6--}}
//     {{--        maxOpacity: 0.8, // From 0 to 1. Default: 1--}}
//     {{--        minSize: 10, // Default: 10--}}
//     {{--        maxSize: 20, // Default: 25--}}
//     {{--        rotation: true, // Default: true--}}
//     {{--        speed: 1, // The property affects the speed of falling. Default: 1--}}
//     {{--        wind: true, // Without wind. Default: true--}}
//     {{--        zIndex: 9997 // Default: 9999--}}
//     {{--    });--}}
// {{--</script>--}}
// {{-------------------------------------------------------------------------}}
//endregion


}

//region -- Кастомный селект
$('.cus-dropdown').click(function (e) {
    e.preventDefault();
    e.stopPropagation();
    console.log("test");
    $('.cus-dropdown').not(this).each(function () {
        $(this).removeClass('expanded');
        $(this).removeClass('overflow-auto');
    });

    clicked_el = $(this);
    clicked_el.toggleClass('expanded');

    if (clicked_el.hasClass('expanded')) {
        wait = 200
    } else {
        wait = 0
    }
    setTimeout(function () {
        clicked_el.toggleClass('overflow-auto')
    }, wait)
});

$('.cus-dropdown label').click(function (e) {
    $('.cus-dropdown').scrollTop(0);
    old_val = $(this).siblings('input[type=radio]:checked').val();
    new_val = $('#' + $(e.target).attr('for')).val();

    if (old_val != new_val) {
        $('#' + $(this).attr('for')).prop('checked', true).trigger('change');
    }
})

$(document).click(function (e) {
    el = $('.cus-dropdown')
    el.scrollTop(0);
    el.removeClass('expanded');
    el.removeClass('overflow-auto');
    el.css('overflow', 'hidden');

});

//endregion

//region -- Анимация кнопки лайк
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
//endregion

//region -- Выдвигающийся блок юзера в хедере

user_header = $('.user_header_block_wrap')
if (user_header.length > 0) {
    var scrollTop = $(window).scrollTop(),
        elementOffset = $('.user_header_block_wrap').offset().top,
        nav_height = (elementOffset - scrollTop),
        user_header_block_height = $('.user_header_block_wrap').outerHeight(),
        show_user_header_height = user_header_block_height + nav_height;

    function check_user_header_on_scroll() {
        var y = $(this).scrollTop();
        scrollTop = $(window).scrollTop();

        if (y > show_user_header_height || scrollTop > show_user_header_height) {
            $('.user_header_scrolled_wrap ').slideDown(300);
        } else {
            $('.user_header_scrolled_wrap ').slideUp(300);
        }
    }

    $(document).ready(function () {
        check_user_header_on_scroll();
        width = $(window).width()
        if (width <= 1000) {
            $('.send_donate p').text('Донат')
        } else {
            $('.send_donate p').text('Отправить донат')
        }
    });


    $(document).scroll(function () {
        check_user_header_on_scroll();
    });


    $(window).resize(function (e) {
        width = $(window).width()
        if (width <= 1000) {
            $('.send_donate p').text('Донат')
        } else {
            $('.send_donate p').text('Отправить донат')
        }

    });
}
//endregion


$(document).ready(function () {
    trigger_all_js()
});

document.addEventListener('livewire:update', function () {
    trigger_all_js()

});

document.addEventListener('trigger_all_js', function () {
    trigger_all_js()

});









