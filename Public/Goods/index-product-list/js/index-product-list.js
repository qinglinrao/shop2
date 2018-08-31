$(function($) {
    $(".left_menu").click(function(){
        // $(".uU16SS").addClass("left_menu_show");
        //$(".uU16SS").animate({left: '0px'}, "slow");
        $(".uU16SS").css({
            "transform": "translate3d(0px, 0px, 0px)",
            "transition-duration": "500ms",
            "z-index": "999"
        });
        $("._3p1vfs").css({
            "opacity": "1",
            "z-index": "1",
            "pointer-events": "auto"
        });
        $("body").css({
           "overflow-y": "hidden"
        });
        $("._1Mg3DU").css({
            "pointer-events": "auto"
        });


    });

    $("._3p1vfs").click(function () {
        $(".uU16SS").css({
            "transform": "translate3d(-102%, 0px, 0px)",
            "transition-duration": "500ms",
            "z-index": "1"
        });
        $("._3p1vfs").css({
            "opacity": "0",
            "z-index": "0",
            "pointer-events": ""
        });
        $("body").css({
            "overflow-y": "visible"
        });
        $("._1Mg3DU").css({
            "pointer-events": ""
        });

    });
    function left_menu_hide(){
        alert('click222');
        $(".uU16SS").css({
            "transform": "translate3d(-102%, 0px, 0px)",
            "transition-duration": "500ms",
            "z-index": "1"
        });
        $("._3p1vfs").css({
            "opacity": "0",
            "z-index": "0"
        });
    };




    var navOffset=$("._354-78").height();
    $(window).scroll(function(){
        var scrollPos=$(window).scrollTop();
        if(scrollPos >=navOffset){
            $("._354-78").css({
                "transform": "translate3d(0px, 0px, 0px)",
                "transition-duration": "500ms",
                "z-index": "999"
            });
        }else{
            $("._354-78").css({
                "transform": "translate3d(0px, -112px, 0px)",
                "transition-duration": "500ms"
            });
        }
    });

    //轮播图
    var swiper = new Swiper('.swiper-container', {
        //autoplay: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.next',
            prevEl: '.prev',
        },
        pagination: {
            el: '.swiper-pagination',
        }
    });
});
