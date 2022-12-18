let soc_slider = document.querySelector('.other_works_block');
let inner_soc_slider = document.querySelector('.other_works_block_in');

let pressed = false;
let startx;
let x;
let cur_left = 0;
let first_slide = 0;
let title_to_change_first = $('.main_work_info p').text();
let author_to_change_first = $('.main_work_info a').text();
let img_to_change_first = $('.main_work img').attr('src');
let src_to_change_first = $('.main_work .read_main_hovered a').attr('data-id');
let like_to_change_first = $('.main_work .other_work_icon_block div:nth-child(1) span').text();
let comment_to_change_first = $('.main_work .other_work_icon_block div:nth-child(2) span').text();
let outer = soc_slider.getBoundingClientRect();
let inner = inner_soc_slider.getBoundingClientRect();
let cnt_works = $('.other_work').length;
let flag_active_right = true;
let flag_active_left = false;
let outer_line = $('.line-out');
let line_out_width = parseInt(outer_line.innerWidth());
let line_in_width = parseInt($('.line-in').outerWidth(true));
let slide_width = parseInt($('.other_work').outerWidth(true)); // Получаем ширину каждого слайда

// $('.main_work').css('width', 'inherit');
// $('.main_work').css('height', 'inherit');

// soc_slider.addEventListener('mousedown', (e) => {
//     pressed = true;
//     startx = e.offsetX - inner_soc_slider.offsetLeft;
//     soc_slider.style.cursor = 'grabbing'
// });
//
// soc_slider.addEventListener('mouseenter', () => {
//     soc_slider.style.cursor = 'grab'
// });
//
// soc_slider.addEventListener('mouseup', () => {
//     soc_slider.style.cursor = 'grab'
// });
//
// window.addEventListener('mouseup', () => {
//     pressed = false;
// });
//
// soc_slider.addEventListener('mousemove', (e) => {
//     let slide_width = parseInt($('.other_work').outerWidth(true));
//
//     if (!pressed) return;
//     e.preventDefault();
//
//     x = e.offsetX;
//     cur_left = x - startx;
//
//     inner_soc_slider.style.left = `${cur_left}px`;
//
//     checkboundary();
// });
//
function checkboundary() {
    console.log(inner_soc_slider.style.left);
    console.log(inner.right);
    console.log(outer.right);


    if (parseInt(inner_soc_slider.style.left) > 0) {
        inner_soc_slider.style.left = '0px'
    } else if (inner.right < outer.right) {
        inner_soc_slider.style.left = `-${inner.width - outer.width}px`
    }
}


function move_work(dir) {

    // console.log('first_slide (start): ' + first_slide);
    // console.log('cur_left (start): ' + cur_left);


    // Закрываем кнопки на время действия скрипта
    $('.change_ex').css('pointer-events', 'none');


    // MOVE SLIDER
    if ((dir === 'right' && flag_active_right) || (dir === 'left' && flag_active_left)) { // если кнопка активна:


        // Создаем величину move_to для движения слайдов
        if (dir === 'right') {
            console.log(slide_width);
            console.log(cur_left);
            move_to = -(slide_width - cur_left);
        } else if (dir === 'left') {
            move_to = cur_left + slide_width;
        };

        // Делаем движение слайдов на величину move_to
        inner_soc_slider.style.transition = '.3s all ease-in-out'
        inner_soc_slider.style.left = `${move_to}px`;
        setTimeout(function () {
            inner_soc_slider.style.transition = 'none'
        }, 300);

        if (parseInt(inner_soc_slider.style.left) >= 0) {
            $('#soc_ex_prev').addClass('change_ex_buttons__inactive');
        }

        // Меняем основное изображение
        if (parseInt(inner_soc_slider.style.left) === 0) {
            title_to_change = title_to_change_first;
            author_to_change = author_to_change_first;
            img_to_change = img_to_change_first;
            src_to_change = src_to_change_first;
            like_to_change = like_to_change_first;
            comment_to_change = comment_to_change_first;

        } else {
            title_to_change = $(`#other_work_${first_slide} .other_work_info p`).text();
            author_to_change = $(`#other_work_${first_slide} .other_work_info a`).text();
            img_to_change = $(`#other_work_${first_slide} img`).attr('src');
            src_to_change = $(`#other_work_${first_slide}`).attr('data-id');
            like_to_change = like_to_change_first;
            like_to_change = $(`#other_work_${first_slide} .other_work_icon_block div:nth-child(1) span` ).text();
            comment_to_change = $(`#other_work_${first_slide} .other_work_icon_block div:nth-child(2) span` ).text();
        }

        setTimeout(function () {
            $('.main_work_info p').hide().text(title_to_change).fadeIn('slow');
            $('.main_work_info a').hide().text(author_to_change).fadeIn('slow');
            $('.main_work .read_main_hovered a').attr('href', '/work/' + src_to_change);
            $('.main_work .other_work_icon_block div:nth-child(1) span').text(like_to_change);
            $('.main_work .other_work_icon_block div:nth-child(2) span').text(comment_to_change);
            $('.main_work img').fadeOut(100, function () {
                $(this).attr('src', img_to_change).bind('onreadystatechange load', function () {
                    if (this.complete) $(this).fadeIn(100);
                });
            });
        }, 300);


        // Понимаем какое настоящее состояние left
        cur_left = parseInt(inner_soc_slider.style.left);
        first_slide = Math.abs(cur_left / slide_width);

        share_of_move = (first_slide / (cnt_works - 3)) * 100

        // Движение ползунка
        if (first_slide === 0) {
            move_to_line = 'max( calc(' + share_of_move + '% - ' + line_out_width + 'px) , 0px )' ;
        } else {
            move_to_line = 'max( calc(' + share_of_move + '% - ' + line_out_width + 'px) , 0px )' ; // если ненулевой слайд вычитаем ширину ползунка
        }
        outer_line.css('left', move_to_line);


        // Создаем состояние кнопок (right)
        if ((cnt_works - (parseInt(first_slide) + 1)) < 3) { // Если на экране 3 последних слайда
            flag_active_right = false;
            $('#soc_ex_next').addClass('change_ex_buttons__inactive');
        } else {
            flag_active_right = true;
            $('#soc_ex_next').removeClass('change_ex_buttons__inactive');
        }

        // Создаем состояние кнопок (left)
        if (cur_left === 0) { // если слева ничего нет
            flag_active_left = false;
            $('#soc_ex_prev').addClass('change_ex_buttons__inactive');
        } else {
            flag_active_left = true;
            $('#soc_ex_prev').removeClass('change_ex_buttons__inactive');
        }


        // console.log('first_slide (finish): ' + first_slide);
        // console.log('cur_left (finish): ' + cur_left);



    }

    // Открываем кнопки
    setTimeout(function () {
        $('.change_ex').css('pointer-events', 'all');
    }, 300);


}

