document.onreadystatechange = function () {
    var state = document.readyState;
    if (state == 'complete') {
        document.addEventListener('click',function(e){
            if(e.target && e.target.id== 'button_takephoto'){
                e.preventDefault();
                $('.atto_recittakepicture_button_takephoto').trigger('click');
             }
         });
    }
};
