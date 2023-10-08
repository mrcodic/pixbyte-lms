(function (window, undefined) {
    'use strict';
    /*
    NOTE:
    ------
    PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
    WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */



    $(document).on('click', ".togglePassword", function (e) {
        e.preventDefault();
        var type = $(this).parent().parent().find(".password").attr("type");
        console.log(type);

        if(type == "password"){
            $(this).parent().parent().find(".password").attr("type","text");
        }else if(type == "text"){
            $(this).parent().parent().find(".password").attr("type","password");
        }
    });




})(window);
