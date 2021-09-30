document.onreadystatechange = function () {
    var state = document.readyState;
    if (state == 'complete') {
        document.addEventListener('click',function(e){
            if(e.target && e.target.id== 'button_takephoto'){
                e.preventDefault();
                $('.atto_recittakepicture_button_takephoto').trigger('click');
             }
         });

        let inputs = document.querySelectorAll('.numericalrecitpart.disabled input');
        for (let i = 0; i < inputs.length; i++){
            inputs[i].value = '0';
            inputs[i].setAttribute('value', '0');
        }
    }
};
