slider = $('.last_works_block .right_wrap .works_wrap')

slides = $('.last_works_block .right_wrap .works_wrap .work_card_wrap')

// Запоминаем первый слайд
first_slide = $('.last_works_block .left_wrap .work_card_wrap')
first_img_src = $(first_slide).find('img').attr('src')
first_title = $(first_slide).find('p').text()
first_author = $(first_slide).find('.link').text()
first_link_src = $(first_slide).find('.image_wrap a').attr('href')

// Понимаем ползунок
total_width = $('.line_wrap').outerWidth();
pointer = $('.line_wrap .pointer')



slider.on('afterChange', function (event, slick, currentSlide, nextSlide) {

    if (currentSlide === 0) {
        img_src = first_img_src
        title = first_title
        author = first_author
        link_src = first_link_src

    } else {
        img_src = $(slides[currentSlide - 1]).find('img').attr('src')
        title = $(slides[currentSlide - 1]).find('p').text()
        author = $(slides[currentSlide - 1]).find('.link').text()
        link_src = $(slides[currentSlide - 1]).find('.image_wrap a').attr('href')
    }

    $('.last_works_block .left_wrap img').fadeOut(100, function () {
        $(this).attr('src', img_src).bind('onreadystatechange load', function () {
            if (this.complete) $(this).fadeIn(100);
        });
    });

    $(".last_works_block .left_wrap p").hide().text(title).fadeIn('slow');
    $(".last_works_block .left_wrap .link").hide().text(author).fadeIn('slow');
    $(".last_works_block .left_wrap .image_wrap a").attr('href', link_src);

    share_of_move = (currentSlide / (slides.length - 3)) * 100
    pointer.css('left', share_of_move)

});

