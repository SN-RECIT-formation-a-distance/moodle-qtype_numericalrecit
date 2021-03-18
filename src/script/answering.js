document.onreadystatechange = function () {
    var state = document.readyState;
    if (state == 'complete') {
        
        
        $("body").on("click", "#button_takephoto", function(e){
            e.preventDefault();
            $('.atto_recittakepicture_button_takephoto').trigger('click');
        })
    }
};
