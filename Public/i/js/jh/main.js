$(document).ready(function () {

    $('#dropdown').click(function () {
        if ($(".dropdown-menu").is(":hidden")) {
            $('.dropdown-menu').css('display', 'block');
        } else {
            $('.dropdown-menu').css('display', 'none');
        }
    });

    $('#dropdown').blur(function () {
        if (!$(".dropdown-menu").is(":hover") && !$(".dropdown-menu").is(":click")) {
            $('.dropdown-menu').css('display', 'none');
        }
    });

});


function jump(gc, type) {
    window.location.href = "/" + gc + "/" + type;
}

function jump1(type) {
    window.location.href = "/" + type;
}