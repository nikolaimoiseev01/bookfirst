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
    <link rel="stylesheet" href="/admin/plugins/fontawesome-free/css/all.min.css">
    <!-- jQuery -->
    <script src="/js/jquery.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    @livewireScripts
    <!-- For datetime -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin/dist/css/adminlte.min.css">
    <!-- Custom css -->
    <link rel="stylesheet" href="/admin/admin.css">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="/plugins/filepond/filepond.css">

    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

</head>
<body class="hold-transition sidebar-mini layout-fixed">

<!-- preloader -->
<div class="book-preloader-wrap">
    <span style="display: none">Меняем статус...</span>
    <div class="book-preloader">
        <div class="inner">
            <div class="left"></div>
            <div class="middle"></div>
            <div class="right"></div>
        </div>
        <ul>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</div>
<!-- preloader -->


<div class="wrapper">

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
            <img src="/admin/dist/img/AdminLTELogo.png" alt="/adminLTE Logo" class="brand-image img-circle elevation-3"
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
                        <a href="{{ route('chats') }}" class="nav-link">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Чаты
                                @if ($new_chats > 0)
                                    <span class="right badge badge-danger">{{$new_chats}}</span>
                                @endif
                            </p>
                        </a>
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
<script src="/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="/admin/plugins/select2/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="/admin/plugins/moment/moment.min.js"></script>
<!-- date-picker -->
<script src="/admin/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- BS-Stepper -->
<script src="/admin/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- AdminLTE App -->
<script src="/admin/dist/js/adminlte.min.js"></script>

<script src="/plugins/filepond/filepond.js"></script>
<!-- include FilePond jQuery adapter -->
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
<!-- include FilePond file-validate-size adapter -->
<script src="/plugins/filepond/filepond-plugin-file-validate-size.min.js"></script>
<!-- include FilePond file-validate-type adapter -->
<script src="/plugins/filepond/filepond-plugin-file-validate-type.min.js"></script>

<!-- Page specific script -->
<script>
    $('.datepicker').each(function () {
        $(this).datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd',
            onSelect: function(dateText) {
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

                $('.book-preloader-wrap').removeClass('preloaded_loaded');
                $('.book-preloader-wrap').css('opacity', '0');
                $('.book-preloader-wrap').css('background', '#fdfeffcc');
                $('.book-preloader-wrap span').show(100);
                window.setTimeout(function () {
                    $('.book-preloader-wrap').css('opacity', '1');
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

                $('.book-preloader-wrap').removeClass('preloaded_loaded');
                $('.book-preloader-wrap').css('opacity', '0');
                $('.book-preloader-wrap').css('background', '#fdfeffcc');
                $('.book-preloader-wrap span').html("Посылаем Email каждому...");
                $('.book-preloader-wrap span').show(100);
                window.setTimeout(function () {
                    $('.book-preloader-wrap').css('opacity', '1');
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

                $('.book-preloader-wrap').removeClass('preloaded_loaded');
                $('.book-preloader-wrap').css('opacity', '0');
                $('.book-preloader-wrap').css('background', '#fdfeffcc');
                $('.book-preloader-wrap span').html("Обновляем сборник...");
                $('.book-preloader-wrap span').show(100);
                window.setTimeout(function () {
                    $('.book-preloader-wrap').css('opacity', '1');
                }, 10);
                form.submit();
            }
        })
    });

    $('.create_chat').on('click', function (e) {
                $('.book-preloader-wrap').removeClass('preloaded_loaded');
                $('.book-preloader-wrap').css('opacity', '0');
                $('.book-preloader-wrap').css('background', '#fdfeffcc');
                $('.book-preloader-wrap span').html("Создаем чат...");
                $('.book-preloader-wrap span').show(100);
                window.setTimeout(function () {
                    $('.book-preloader-wrap').css('opacity', '1');
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
        Swal.fire({
            title: event.detail.title,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `Все верно`,
            denyButtonText: `Отменить`,
        }).then((result) => {
            if (result.isConfirmed) {
                window.livewire.emit('delete', event.detail.id)
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
<script src="/admin/admin.js"></script>
<script src="/js/sweetalert2.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
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
@yield('page-js')

</body>
</html>
