$(function($) {
    $("#left_menu").click(function(){
        // $(".uU16SS").addClass("left_menu_show");
        //$(".uU16SS").animate({left: '0px'}, "slow");
        $(".uU16SS").css({
            "transform": "translate3d(0px, 0px, 0px)",
            "transition-duration": "500ms"
        });
        $("._3p1vfs").css({
            "opacity": "1"
        });
    });


    var navOffset=$("._354-78").offset().top;
    $(window).scroll(function(){
        var scrollPos=$(window).scrollTop();
        if(scrollPos >=navOffset){
            $("._354-78").css({
                "transform": "translate3d(0px, 0px, 0px)",
                "transition-duration": "500ms"
            });
        }else{
            $("._354-78").css({
                "transform": "translate3d(0px, -112px, 0px)",
                "transition-duration": "500ms"
            });
        }
    });
});
