$(document).ready(function () {
    var mySwiper = new Swiper('.swiper-box', {
        // Optional parameters
        direction: 'vertical',
        loop: false,
        spaceBetween: 30,
        slidesPerView: 5,
        slidesPerGroup: 4,

        // Navigation arrows
        navigation: {
            nextEl: '.button-next',
            prevEl: '.button-prev'
        }
    });

    var mySwiperOne = new Swiper('.swiper-box-one', {
        // Optional parameters
        direction: 'vertical',
        loop: false,
        spaceBetween: 30,

        // Navigation arrows
        navigation: {
            nextEl: '.button-next',
            prevEl: '.button-prev'
        }
    });
});
