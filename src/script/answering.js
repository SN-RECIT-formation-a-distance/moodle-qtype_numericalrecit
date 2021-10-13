document.onreadystatechange = function () {
    var state = document.readyState;
    if (state == 'complete') {
        document.addEventListener('click',function(e){
            if(e.target && e.target.id== 'button_takephoto'){
                e.preventDefault();
                $('.atto_recittakepicture_button_takephoto').trigger('click');
             }
         });

        let el = document.querySelector(".numericalrecit .editor_atto_content");
        let inputs = document.querySelectorAll('.numericalrecitpart.disabled input');
        if (el && inputs[0]){ 
            el.addEventListener("input", function() {
                for (let i = 0; i < inputs.length; i++){
                    inputs[i].value = '0';
                    inputs[i].setAttribute('value', '0');
                }
            }, false);
        }

         inputs = document.querySelectorAll('.numericalrecitpart input');
         for (let i = 0; i < inputs.length; i++){
             inputs[i].onchange = function(){
                 if (this.value.includes(',')){
                     this.value = this.value.replace(',', '.');
                 }
             }
         }
    }
};
