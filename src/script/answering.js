// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package    qtype_numericalrecit
 * @copyright  2019 RECIT
 * @copyright  Based on work by 2010 Hon Wai, Lau <lau65536@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
document.addEventListener('DOMContentLoaded', function(){ 
    let btn = document.querySelector('[data-take-picture-btn-id]');
    
    if(btn){
        btn.addEventListener('click',function(e){
            e.preventDefault();
            
            let editorBtn = document.querySelector(btn.getAttribute('data-take-picture-btn-id'));
            if(editorBtn){
                editorBtn.click();
            }
        });
    }

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
}, false);
