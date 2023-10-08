$(document).ready(function() {
    $('#uploadImage').change(function(e){
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.showImage').attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files['0']);
    });

    //Collapse function
    $(document).on('click','.collapse-header',function (){
        $header = $(this);
        $content = $header.parent().siblings();
        $content.slideToggle(300);
    })


    //Sidebar hmbrgr
    $(".reign-toggler.large").click(function() {
        $(".sidebar-container").toggleClass('closed');
        $(".sidebar-container").toggleClass('opened');
        $(this).toggleClass('opened');
        $(".rm-expand").toggleClass('rm-expand-out');
    });
    $(".reign-toggler.small").click(function() {
        $("#sidebar-inst-mobile").show();
        $(".rm-expand").toggleClass('rm-expand-out');
    });
    $('.reign-toggler.opened.mobile').click(function() {
        $("#sidebar-inst-mobile").hide();
    });

    $(".collapse-header").click(function(){
        $(this).children('ul').slideToggle();
        $(this).find('.icon-no-rotate').toggleClass('icon-rotate');
    });

    //Spider motion class
    setTimeout(function(){
        $(".spider-wrapper").addClass("o-position");
    },1000)

    // Mobile fixed scroll
    $(window).scroll(function() {
        var windscroll = $(window).scrollTop();

        if (windscroll >= 100) {
            $('section').each(function(i) {
        // The number at the end of the next line is how pany pixels you from the top you want it to activate.
                if ($(this).position().top <= windscroll - 0) {
                    $('.classwork-nav li.uk-active').removeClass('uk-active');
                    $('.classwork-nav li').eq(i).addClass('uk-active');
                }
            });

        } else {
            $('.classwork-nav li.uk-active').removeClass('uk-active');
            $('.classwork-nav  li:first').addClass('uk-active');
        }
    }).scroll();
    $("#toggle-hmbrgr").click(function(){
        if($("#sidebar-inst").hasClass("opened")) {
            $(".side-bar-link").removeAttr("uk-tooltip");
        }else {
            $(".side-bar-link.dash").attr("uk-tooltip","title:Dashboard; pos: right");
            $(".side-bar-link.class").attr("uk-tooltip","title:Classrooms; pos: right");
            $(".side-bar-link.room").attr("uk-tooltip","title:Rooms; pos: right");
            $(".side-bar-link.lessons").attr("uk-tooltip","title:Lessons; pos: right");
            $(".side-bar-link.code").attr("uk-tooltip","title:Code Generator; pos: right");
            $(".side-bar-link.ass").attr("uk-tooltip","title:My Assistants; pos: right");
        }
    });

});
$(document).on('click', ".togglePassword", function (e) {
    e.preventDefault();
    var type = $(this).parent().find(".password").attr("type");

    if(type == "password"){
        $(this).parent().find(".password").attr("type","text");
        $(this).empty();
        $(this).append(`<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="none" stroke="#000" d="m7.56,7.56c.62-.62,1.49-1.01,2.44-1.01,1.91,0,3.45,1.54,3.45,3.45,0,.95-.39,1.82-1.01,2.44"></path><path fill="none" stroke="#000" d="m19.5,10c-2.4,3.66-5.26,7-9.5,7h0,0,0c-4.24,0-7.1-3.34-9.49-7C2.89,6.34,5.75,3,9.99,3h0,0,0c4.25,0,7.11,3.34,9.5,7Z"></path><line fill="none" stroke="#000" x1="2.5" y1="2.5" x2="17.5" y2="17.5"></line></svg>`);

    }else if(type == "text"){
        $(this).parent().find(".password").attr("type","password");
        $(this).empty();
        $(this).append(`<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><circle fill="none" stroke="#000" cx="10" cy="10" r="3.45"></circle><path fill="none" stroke="#000" d="m19.5,10c-2.4,3.66-5.26,7-9.5,7h0,0,0c-4.24,0-7.1-3.34-9.49-7C2.89,6.34,5.75,3,9.99,3h0,0,0c4.25,0,7.11,3.34,9.5,7Z"></path></svg>`);
    }
});
