document.querySelectorAll('.ex_name').forEach((el) => {
    el.innerHTML = el.textContent.replace(/\S+\s|\S+$/g, "<span class='word'>$&</span>");
});
document.querySelectorAll('.ex_desc').forEach((el) => {
    el.innerHTML = el.textContent.replace(/\S+\s|\S+$/g, "<span class='word'>$&</span>");
});


document.querySelectorAll('.word').forEach((el) => {
    el.innerHTML = el.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
});


$(".change_ex").click(function () {

    // Cover animate
    var active_from = parseInt($('.active').attr('id')),
        active_to = 1,
        len = $('.cover').length;

    if ($(this).attr('id') == 'prev') {
        if (active_from == 0) {
            active_to = Math.abs(1 - len)
        } else {
            active_to = active_from - 1
        }
    } else {
        active_to = (active_from + 1) % len
    }

    $('.change_ex').css('pointer-events', 'none');
    $('#' + active_to).addClass("active");
    $('#' + active_from).removeClass("active");

    $('.book').addClass("animate")




    // Text animate
    $('#ex_' + active_from).removeClass("active_text")
    $('#ex_' + active_to).addClass("active_text")
    $('.line-out').css('left', active_to * 90 + 'px');


    anime.timeline({loop: false})
        .add({
            targets: '#ex_' + active_to + ' .ex_name .letter',
            scale: [0, 1],
            duration: 1000,
            elasticity: 600,
            delay: (el, i) => 60 * (i + 1)
        })

    anime.timeline({loop: false})
        .add({
            targets: '#ex_' + active_to + ' .ex_desc .letter',
            scale: [0, 1],
            duration: 300,
            elasticity: 600,
            delay: (el, i) => 18 * (i + 1)
        })

    $('#ex_' + active_to).animate(
        {position: 'absolute'},
        2000, function () {
            $('.book').removeClass("animate")
            $('.change_ex').css('pointer-events', '');
        });

});
