<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админ панель - @yield('title')</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/admin_assets/plugins/fontawesome-free/css/all.min.css">
    <!-- jQuery -->
    <script src="/js/jquery.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    @livewireScripts
    <!-- For datetime -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin_assets/dist/css/adminlte.min.css">
    <!-- Custom css -->
    <link rel="stylesheet" href="/admin_assets/admin.css">

    <!-- SummerNote Text Editor -->
    <link rel="stylesheet" href="/plugins/summernote/summernote.min.css">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/admin_assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="/plugins/filepond/filepond.css">

    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- include libraries(jQuery, bootstrap) -->
    {{--    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">--}}

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <style>
        .note-editing-area {
            background: white;
            min-width: 300px;
        }
    </style>

    @vite(['resources/sass/admin.scss', 'resources/js/app.js'])

    <script>
        // Функция для установки куки
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        }

        // Функция для получения значения куки
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        // Функция для включения/выключения темной темы
        function toggleDarkMode() {
            var body = document.body;
            var labelDark = document.getElementById("labelDark");
            var chat_wrap = $(".chat .container")
            var chat_text_area = $(".chat .container textarea")
            var templates_wrap = $(".templates_wrap")
            if (labelDark.classList.contains("active")) {
                body.classList.add("dark-mode");
                setCookie("darkMode", "enabled", 365);
                if(chat_wrap) {
                    chat_wrap.css('background', '#454D55')
                    chat_text_area.css('background', '#454D55')
                    chat_text_area.css('color', 'white')
                    templates_wrap.css('background', '#454D55')
                    templates_wrap.css('color', 'white')

                }
            } else {
                body.classList.remove("dark-mode");
                setCookie("darkMode", "disabled", 365);
                if(chat_wrap) {
                    chat_wrap.css('background', 'white')
                    chat_text_area.css('background', 'white')
                    chat_text_area.css('color', 'black')
                    templates_wrap.css('background', 'white')
                    templates_wrap.css('color', 'black')
                }
            }
        }

        // Инициализация состояния темной темы при загрузке страницы
        setTimeout(() => {
            var labelDark = document.getElementById("labelDark");
            var darkModeCookie = getCookie("darkMode");
            var option_b1 = document.getElementById("option_b1");
            var option_b2 = document.getElementById("option_b2");

            if (darkModeCookie === "enabled") {
                labelDark.click();
                toggleDarkMode();
                option_b1.removeAttribute("checked");
                option_b2.setAttribute("checked", "checked");


            }

            document.body.removeAttribute("hidden");

            // Привязка обработчика события для переключения темной темы
            document.getElementById("labelDark").addEventListener("click", toggleDarkMode);
            document.getElementById("labelLight").addEventListener("click", toggleDarkMode);
        }, 3)



    </script>

</head>

<body hidden class="hold-transition sidebar-mini layout-fixed">

<div style="display: none;" class="admin_preloader_block_wrap">
    <x-preloader mode="portal"/>
</div>

{{--<x-preloader mode="portal"/>--}}


<div class="wrapper">

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
            <img src="/admin_assets/dist/img/AdminLTELogo.png" alt="/adminLTE Logo"
                 class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <h3>Первая Книга</h3>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="{{ route('homeAdmin') }}" class="nav-link">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>
                                Наши сборники
                                @if ($new_participants > 0)
                                    <span class="right badge badge-danger">{{$new_participants}}</span>
                                @endif
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('own_books_index')}}" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Собственные книги
                                @if ($own_books_alert > 0)
                                    <span class="right badge badge-danger">{{$own_books_alert}}</span>
                                @endif
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('chats_admin') }}" class="nav-link">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Чаты админ
                                @if ($new_chats > 0)
                                    <span class="right badge badge-danger">{{$new_chats}}</span>
                                @endif
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-share-alt"></i>
                            <p>
                                Соц. сеть
                                <i style="top: .4rem;" class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul style="padding-left: 20px;" class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('chats_users') }}" class="nav-link">
                                    <i class="nav-icon fas fa-comments"></i>
                                    <p>
                                        Чаты польз.
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin_social_comments') }}" class="nav-link">
                                    <i class="nav-icon fas fa-comment"></i>

                                    <p>
                                        Комментарии
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin_social_likes') }}" class="nav-link">
                                    <i class="nav-icon fas fa-heart"></i>
                                    <p>
                                        Лайки
                                    </p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('admin_social_subs') }}" class="nav-link">
                                    <i class="nav-icon fas fa-star"></i>
                                    <p>
                                        Подписки
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin_social_donates') }}" class="nav-link">
                                    <i class="nav-icon nav-icon fas fa-donate"></i>
                                    <p>
                                        Донаты
                                    </p>
                                </a>
                            </li>


                        </ul>
                    </li>


                    <li class="nav-item">
                        <a href="{{ route('user.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Пользователи
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-piggy-bank"></i>
                            <p>
                                Финансы
                                <i style="top: .4rem;" class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul style="padding-left: 20px;" class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('promocodes_page') }}" class="nav-link">
                                    <i class="nav-icon fas fa-percent "></i>
                                    <p>
                                        Промокоды
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('transactions_from_admin') }}" class="nav-link">
                                    <i class="nav-icon fas fa-ruble-sign "></i>
                                    <p>
                                        Все транзакции
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://yookassa.ru/my/analytics/payments" target="_blank" class="nav-link">
                                    <i class="nav-icon fab fa-yandex-international"></i>
                                    <p>YooKassa</p>
                                </a>
                            </li>


                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin_stat') }}" class="nav-link">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>
                                Статистика
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/admin" class="new_admin nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Новая админка
                            </p>
                        </a>
                    </li>


                    <style>
                        .btn-group.btn-group-toggle {
                            width: fit-content;
                            padding-left: 16px;
                        }
                        .bg-olive.btn.active, .bg-olive.btn:active, .bg-olive.btn:not(:disabled):not(.disabled).active, .bg-olive.btn:not(:disabled):not(.disabled):active {
                            background-color: #7992a8 !important;
                            border-color: #454D55;
                            color: #fff;
                            padding: 0 10px;
                            width: fit-content;
                        }
                        .bg-olive.btn:not(.active) {
                            background-color: #454D55 !important;
                            border-color: #454D55;
                            color: #fff;
                            padding: 0 10px;
                            width: fit-content;
                        }
                    </style>
                    <div class="btn-group d-block btn-group-toggle" data-toggle="buttons">
                        <label id="labelDark" class="btn bg-olive active">
                            <input type="radio" name="options" id="option_b1" autocomplete="off" checked="">
                            <i style="font-size: 14px; color: yellow"  class="fas fa-regular fa-sun"></i>
                        </label>
                        <label id="labelLight" class="btn bg-olive">
                            <input type="radio" name="options" id="option_b2" autocomplete="off">
                            <i style="font-size: 14px;" class="fas fa-regular fa-moon"></i>
                        </label>
                    </div>


                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div style="padding-left: 20px;padding-right: 20px;" class="fixed content-wrapper">
        <div id="menu-trig" class="d-none pt-3">
            <a class="justify-content-center btn btn-outline-info">Меню</a>
        </div>
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script src="/js/js.js"></script>

<!-- Bootstrap 4 -->
<script src="/admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="/admin_assets/plugins/select2/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="/admin_assets/plugins/moment/moment.min.js"></script>
<!-- date-picker -->
<script src="/admin_assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/admin_assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- BS-Stepper -->
<script src="/admin_assets/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- AdminLTE App -->
<script src="/admin_assets/dist/js/adminlte.min.js"></script>

<script src="/plugins/filepond/filepond.js"></script>
<!-- include FilePond jQuery adapter -->
<script src="/plugins/filepond/filepond.jquery.js"></script>
<!-- include FilePond file-validate-size adapter -->
<script src="/plugins/filepond/filepond-plugin-file-validate-size.min.js"></script>
<!-- include FilePond file-validate-type adapter -->
<script src="/plugins/filepond/filepond-plugin-file-validate-type.min.js"></script>

<script src="//unpkg.com/alpinejs"></script>

<!-- Page specific script -->
<script>
    $('.datepicker').each(function () {
        $(this).datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd',
            onSelect: function (dateText) {
                console.log("Selected date: " + dateText + "; input's current value: " + this.value);
            }
        });
    });
</script>

<script>
    $('#menu-trig').on('click', function () {
        $('.main-sidebar').toggleClass('menu__active');
    });

    // $(document).mouseup(function(e)
    // {
    //     var container = $(".main-sidebar");
    //
    //     // if the target of the click isn't the container nor a descendant of the container
    //     if (!container.is(e.target) && container.has(e.target).length === 0)
    //     {
    //         $('.main-sidebar').css('margin-left', '-250px');
    //     }
    // });
</script>

{{-------- МЕНЯТЬ СТАТУС КНОПКА ----------}}
<script>
    $('.change_status_button').on('click', function () {

        $('#' + $(this).attr('data-form') + '_form_wrap').toggle();
        $('#' + $(this).attr('data-form') + '_text').toggle();

        if ($('#' + $(this).attr('data-form') + '_form_wrap').is(":visible")) {
            $(this).html('<i class="mr-1 fa fa-times"></i> Отменить');
        } else {
            $(this).html('<i style="font-size: 20px;" class="fa fa-edit"></i>');
        }
    })
</script>

<script>
    $('.change_status').on('click', function (e) {
        e.preventDefault();
        var form = $(this).parents('form');
        var status_from = $(this).attr('data-status-from');
        var status_to = form.find('select option:selected').text();

        Swal.fire({
            title: "Мы что-то меняем:",
            html: "<b>Старое значение: </b>" + status_from + "<br><b>Новое значение: </b>" + status_to,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `Все верно`,
            denyButtonText: `Отменить`,
        }).then((result) => {
            if (result.isConfirmed) {

                $('.preloader_wrap').removeClass('preloaded_loaded');
                $('.preloader_wrap').css('opacity', '0');
                $('.preloader_wrap').css('background', '#fdfeffcc');
                $('.preloader_wrap span').show(100);
                window.setTimeout(function () {
                    $('.preloader_wrap').css('opacity', '1');
                    $('.admin_preloader_block_wrap').show();
                    $('.admin_preloader_block_wrap').show();
                }, 10);
                form.submit();
            }
        })
    });

    $('.all_participants_email').on('click', function (e) {
        e.preventDefault();
        var form = $(this).parents('form');

        Swal.fire({
            title: "Отправляем всем участникам!",
            text: "Текст правильно написали?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `Поехали!`,
            denyButtonText: `Отменить`,
        }).then((result) => {
            if (result.isConfirmed) {

                $('.preloader_wrap').removeClass('preloaded_loaded');
                $('.preloader_wrap').css('opacity', '0');
                $('.preloader_wrap').css('background', '#fdfeffcc');
                $('.preloader_wrap span').html("Посылаем Email каждому...");
                $('.preloader_wrap span').show(100);
                window.setTimeout(function () {
                    $('.preloader_wrap').css('opacity', '1');
                    $('.admin_preloader_block_wrap').show();

                }, 10);
                form.submit();
            }
        })
    });

    $('.save_collection').on('click', function (e) {
        e.preventDefault();
        var form = $(this).parents('form');

        Swal.fire({
            title: "Все правильно заполнили?",
            text: "Сохраняем сборник?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `Все верно`,
            denyButtonText: `Отменить`,
        }).then((result) => {
            if (result.isConfirmed) {

                $('.preloader_wrap').removeClass('preloaded_loaded');
                $('.preloader_wrap').css('opacity', '0');
                $('.preloader_wrap').css('background', '#fdfeffcc');
                $('.preloader_wrap span').html("Обновляем сборник...");
                $('.preloader_wrap span').show(100);
                window.setTimeout(function () {
                    $('.preloader_wrap').css('opacity', '1');
                    $('.admin_preloader_block_wrap').show();
                }, 10);
                form.submit();
            }
        })
    });

    $('.create_chat').on('click', function (e) {
        $('.preloader_wrap').removeClass('preloaded_loaded');
        $('.preloader_wrap').css('opacity', '0');
        $('.preloader_wrap').css('background', '#fdfeffcc');
        $('.preloader_wrap span').html("Создаем чат...");
        $('.preloader_wrap span').show(100);
        window.setTimeout(function () {
            $('.preloader_wrap').css('opacity', '1');
            $('.admin_preloader_block_wrap').show();
        }, 10);
    });
</script>
{{-------- // МЕНЯТЬ СТАТУС КНОПКА ----------}}

<script>
    window.addEventListener('loader', event => {
        var button = $('#' + event.detail.id);
        button.attr("disabled", true);
        button.toggleClass('button--loading')
    })
</script>

<script>
    window.addEventListener('swal:modal', event => {
        Swal.fire({
            title: event.detail.title,
            icon: event.detail.type,
            html: event.detail.text,
            showConfirmButton: false,
        })
        if (event.detail.type === 'success') {
            $('#go-to-part-page').attr('href', event.detail.link);
            $('#go-to-part-page').trigger('click');
            $('#back').trigger('click');
        }

    })

    window.addEventListener('swal:confirm', event => {
        console.log(event.detail.onconfirm)
        Swal.fire({
            title: event.detail.title,
            // icon: 'warning',
            html: event.detail.html,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `Все верно`,
            denyButtonText: `Отменить`,
        }).then((result) => {
            if (result.isConfirmed) {
                if (event.detail.id) {
                    window.livewire.emit(event.detail.onconfirm, event.detail.id)
                } else {
                    window.livewire.emit(event.detail.onconfirm)
                }

            }
        })
    })

    window.addEventListener('swal:min', event => {
        Swal.fire({
            position: 'top-end',
            title: event.detail.title,
            icon: event.detail.type,
            html: event.detail.text,
            showConfirmButton: false,
            timer: 3000
        })
    })

    file_input = $('.custom-file-input')
    for (var i = 0; i < file_input.length; i++) {
        file_input[i].addEventListener('change', function () {

            var fileName = document.getElementById($(this).attr('name')).files[0].name;
            $("#label_" + $(this).attr('name')).html(fileName);

        }, false);
    }

    // Javascript to enable link to tab
    var hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
    if (hash) {
        $('.nav-item a[href="#' + hash + '"]').tab('show');
    }


    // Change hash for page-reload
    $('.nav-item a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    })

    {{-------- Авто функции скрытия - показ блоков ---------}}
    $('.show-hide').on('change', function () {

        $('.' + $(this).attr('name')).each(function () {
            $(this).hide();
        })
        $('#block_' + $(this).attr('id')).show();
    })

    $('.up-down').on('change', function () {
        if ($(this).val() === 'show' || $(this).prop('checked') & $(this).prop('type') === 'checkbox') {
            $('.' + $(this).attr('name')).slideDown()
        } else {
            $('.' + $(this).attr('name')).slideUp()
        }
    })
    // ------// Авто функции скрытия - показ блоков---------------------
</script>
<script src="/admin_assets/admin.js"></script>
<script>
    // Просто убираем прелоадер для админки
    $('.preloader_wrap').addClass('preloaded_loaded');
    $('.preloader_wrap').removeClass('preloaded_hiding');
</script>


<script src="/js/sweetalert2.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

<script src="/plugins/summernote/summernote.min.js"></script>

<script src="/plugins/autolinker/autolinker.min.js"></script>

<script>
    @if (session('success'))
    Swal.fire({
        title: '{{session('alert_title')}}',
        icon: '{{session('alert_type')}}',
        html: '<p>{{session('alert_text')}}</p>',
        showConfirmButton: false,
    })
    @endif
</script>

<script>
    document.addEventListener('scroll_chats', function () {
        scroll_chats()
    });
</script>


@yield('page-js')
@stack('page-js')

</body>
</html>
