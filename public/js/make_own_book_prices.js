var
    rows = 0,
    pages = 0,
    pages_from_doc = 0,

    inside_status = 9,
    cover_status = 1,

    cover_color = 0,
    cover_type = "",
    cover_comment = null,
    cover_files_to_php = '',
    pre_cover_files_to_php = '',
    pre_cover_files,

    print_needed = 0,
    tirag_coef = 0,
    pages_coef = 0,
    inside_color = 0,
    cover_color_coef = 0,
    cover_style_coef = 0,
    color_pages = 0,

    promo_var_num = null,

    text_check_price = 0,
    text_design_price = 0,
    cover_price = 1500,
    print_price = 0,
    layout_work_price = 500,
    promo_price = 0,
    total_price = 0;

function make_own_book_prices() {

    // Меняем цифру кол-ва экземпляров
    $("#prints-num").keyup(function get_print_val() {
        print_needed = $(this).val();
        calculation();
    })
    // ------------------------------------

    // Меняем цифру кол-ва цветных страниц
    $("#color_pages").keyup(function () {
        color_pages = $(this).val();
        calculation();
    })

    if ($("#inside_color_yes").prop("checked") === false) {
        color_pages = 0
    }
    else {
        color_pages = $('#color_pages').val()
    }
    // ------------------------------------





    if ($("#textcheck_needed").prop("checked") === true) {
        text_check_price = pages * 30;
    } else {
        text_check_price = 0;
    }

    if ($("#textdesign_needed").prop("checked") === true) {
        text_design_price = pages * 13;
    } else {
        text_design_price = 0;
    }

//Обрабатываем значение печати
    if (print_needed < 10) {
        tirag_coef = 1
    } else if (print_needed < 50) {
        tirag_coef = 0.95
    } else {
        tirag_coef = 0.9
    }

    if (pages <= 100) {
        pages_coef = 1.8
    } else {
        pages_coef = 1
    }


    if ($("#cover_color_yes").prop("checked") === true) {
        cover_color_coef = 1;
        cover_color = 1;
    } else {
        cover_color_coef = 0.7;
        cover_color = 0;
    }

    if ($("#cover_style_hard").prop("checked") === true) {
        cover_style_coef = 2.1
        cover_type = "hard"
    } else {
        cover_style_coef = 1
        cover_type = "soft"
    }


    if ($("#promo-needed").prop("checked") === true) {
        if (typeof $('input[name=promo_input]:checked').val() == 'undefined') {
            promo_price = 0
        } else {
            promo_price = $('input[name=promo_input]:checked').val()
            if (promo_price === '2000') {
                promo_var_num = 2
            } else {
                promo_var_num = 1;
            }

        }
        ;

    } else {
        promo_price = 0;
        promo_var_num = null;
    }


    if ($("#inside_color_yes").prop("checked") === false) {
        color_pages = 0
    }

    // Ставим комментарий обложки и ее статус
    if ($('input[name=cover_status]:checked').val() === 'cover_status_yes') {
        cover_comment = null;
        cover_status = 9;
        $('#cover-price-total').slideUp();
    } else {
        cover_status = 1;
        cover_comment = $('#cover_comment').val();
        $('#cover-price-total').slideDown();
    }
    // -------------------------------------------------


    print_price = (pages - color_pages + (color_pages * 3)) * 0.7 * tirag_coef * cover_color_coef * cover_style_coef * pages_coef * print_needed * 2.2;

    total_price = parseInt(text_design_price) + parseInt(text_check_price) + 300 + 500 + parseInt(cover_price) + parseInt(Math.round(print_price)) + parseInt(promo_price);

//--------------------------------

    console.log('--------------------------------');
    console.log('rows: ' + rows + '; pages: ' + pages);
    console.log('color_pages: ' + color_pages);
    console.log('tirag_coef: ' + tirag_coef);
    console.log('cover_color_coef: ' + cover_color_coef);
    console.log('cover_style_coef: ' + cover_style_coef);
    console.log('pages_coef: ' + pages_coef);
    console.log('print_needed: ' + print_needed);

    console.log('text_design_price: ' + text_design_price);
    console.log('text_check_price: ' + text_check_price);
    console.log('print_price: ' + Math.round(print_price));
    console.log('promo_price: ' + promo_price);
    console.log('cover_price: ' + cover_price);

    console.log('PRINT: ' + pages + ' - ' + color_pages + ' + (' + color_pages + ' * ' + 3 + ') * ' + 0.7 + ' * ' + tirag_coef + ' * '
        + cover_color_coef + ' * ' + cover_style_coef + ' * ' + pages_coef + ' * ' + print_needed + ' * ' + 2.2 + ' = ' + print_price);
    console.log('TOTAL: ' + text_design_price + ' + ' + text_check_price + ' + ' + cover_price + ' + ' + 300 + ' + ' + 500 + ' + ' + Math.round(print_price) + ' = ' + total_price);
    console.log('--------------------------------');
}
