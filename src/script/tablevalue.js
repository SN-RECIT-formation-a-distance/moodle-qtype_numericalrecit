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
require(['jquery'], function($) {
    document.onreadystatechange = function () {
        var state = document.readyState;
        if (state == 'complete') {
            initOnCompleteLoad();
        }
    };

    function initOnCompleteLoad(){
        $('#id_valuetable').click(function(){
            if (document.getElementById('popup_valuetable')) $("#popup_valuetable").remove();
    
            var formula = getAnswerFormula();
            if (formula.length == 0 || formula[0] == ''){
                alert(M.util.get_string('answermissing', 'qtype_numericalrecit'));
                return;
            }
            var th = '';
            for (let formul of formula){
                th += '<th type="formula">'+formul+'</th>';
            }
    
            $(document.body).append('<div id="popup_valuetable" class="modal" tabindex="-1" role="dialog">\
            <div class="modal-dialog" role="document">\
              <div class="modal-content w-auto">\
                <div class="modal-header">\
                  <h5 class="modal-title">'+M.util.get_string('valuetable', 'qtype_numericalrecit')+'</h5>\
                  <button type="button" class="close" data-dismiss="modal" id="closevaluetable" aria-label="Close">\
                    <span aria-hidden="true">&times;</span>\
                  </button>\
                </div>\
                <div class="modal-body">\
                <table class="table w-auto"><thead><tr><th>a</th>'+th+'</tr></thead>\
                <tbody>\
                </tbody></table>\
                <button type="button" class="btn btn-primary" id="addcolvaluetable"><i class="fa fa-plus"></i> '+M.util.get_string('addcolumn', 'qtype_numericalrecit')+'</button>\
                <button type="button" class="btn btn-primary" id="addlinevaluetable"><i class="fa fa-plus"></i> '+M.util.get_string('addline', 'qtype_numericalrecit')+'</button>\
                </div>\
                <div class="modal-footer">\
                  <button type="button" class="btn btn-success" id="savevaluetable"><i class="fa fa-save"></i> '+M.util.get_string('save', 'qtype_numericalrecit')+ '</button>\
                </div>\
              </div>\
            </div>\
          </div>');
    
          buildValueTable();
          $("#popup_valuetable").modal('show');
        });
    
        $("body").on("change", "#popup_valuetable input", function(){
            var row = $(this).closest('tr');
            var formulas = getAnswerFormula();
            for (var formula of formulas){
                getAnswerForRow(row, formula);
            }
        })
    
        $("body").on("click", "#closevaluetable", function(){
            $("#popup_valuetable").modal('hide');
            $("#popup_valuetable").remove();
        })
    
        $("body").on("click", "#addcolvaluetable", function(){
            addColumnToValueTable();
        })
    
        $("body").on("click", "#addlinevaluetable", function(){
            addRowToValueTable();
        })
    
        $("body").on("click", "#savevaluetable", function(){
            var vars = '';
            var random = '';
            var values = getTableValues();
            if (!values){
                alert(M.util.get_string('fillallfields', 'qtype_numericalrecit'));
                return;
            }
            for (var k in values){
                vars += k + ' = [' + values[k].join(',') + '][k];';
                rand = [];
                for (var i = 0; i < values[k].length; i++){
                    rand.push(i);
                }
                random = 'k = {' + rand.join(',') + '};';
            }
            $("textarea[name=varsrandom]").val(random);
            $("textarea[name=varsglobal]").val(vars);
            $("#popup_valuetable").modal('hide');
            $("#popup_valuetable").remove();
        });
     
        //Add show example button
        $('.collapsible-actions').append(` ${M.util.get_string('docs', 'qtype_numericalrecit')}`);
        $('.collapsible-actions').append(' ­ <a href="#" class="btn btn-warning" id="showexamplertr"><i class="fa fa-lightbulb"></i> '+M.util.get_string('showexample', 'qtype_numericalrecit')+'</a>');
        hideAutoGradingFields(true);
        //Check if auto grading is ticked
        setTimeout(function(){
            if ($('#id_automark').is(':checked')){
                hideAutoGradingFields(false);
            }
        }, 1000);
    
        $("body").on("click", "#showexamplertr", function(){
            $("textarea[name=varsrandom]").val("k = {0,1};");
            $("textarea[name=varsglobal]").val("a = [1,3][k];b = [3,6][k];");
            $("input[name=\"answer[0]\"]").val("a + b");
            $("#id_questiontext").val(M.util.get_string('calculate', 'qtype_numericalrecit')+" {a} + {b}");
            $("#id_questiontexteditable p").html(M.util.get_string('calculate', 'qtype_numericalrecit')+" {a} + {b}");
            $("#id_stepfeedback").val(M.util.get_string('descriptionhere', 'qtype_numericalrecit'));
            $("#id_stepfeedbackeditable p").html(M.util.get_string('descriptionhere', 'qtype_numericalrecit'));
            $("input[name=name]").val("numericalrecit");
    
            $('.collapsible').removeClass('collapsed');
        });
    
        
        $("body").on("change", "#id_automark", function(){
            if (this.checked){
                hideAutoGradingFields(false);
            }else{
                hideAutoGradingFields(true);
            }
        });
    };
    
    function hideAutoGradingFields(hide){
        if (hide){
            $('#fitem_id_answernumbering').hide();
            $('div[id^="fitem_id_answertype_"]').hide();
            $('div[id^="fitem_id_correctness_"]').hide();
            $('#id_multitriesheader').hide();
            $('#id_combinedfeedbackhdr').hide();
            $('#id_subqoptions').hide();
            $('div[id^="fitem_id_unitpenalty_"]').hide();
            $('#fitem_id_stepmark').show();
            $('#id_stepmark').prop('disabled', false);
            setTimeout(function(){ $('.moreless-actions').hide(); }, 1000);
        }else{
            //$('#fitem_id_answernumbering').show();
            $('div[id^="fitem_id_answertype_"]').show();
            $('div[id^="fitem_id_correctness_"]').show();
            $('#id_multitriesheader').show();
            $('#id_combinedfeedbackhdr').show();
            $('#id_subqoptions').show();
            $('.moreless-actions').show();
            $('div[id^="fitem_id_unitpenalty_"]').show();
            $('#id_stepmark').attr('value', '0');
            $('#id_stepmark').val('0');
            $('#fitem_id_stepmark').hide();
        }
    
    }
    
    function buildValueTable(){
        var vars = $("textarea[name=varsglobal]").val();
        if (vars === ''){
            addRowToValueTable();
            return;
        }
        vars = vars.split(';');
        rowsnum = 0;
        values = {};
        for (var i = 0; i < vars.length; i++){
            vars[i] = vars[i].replace('\n', '');
            var letter = vars[i][0];
            if (!letter || !letter.match(/[a-z]/i)) continue;
            if (!doesColumnExist(letter)) addColumnToValueTable(letter);
            var sp = vars[i].split('[')[1];
            if (sp){
                sp = sp.replace(']', '');
                values[letter] = sp.split(',');
                rowsnum = values[letter].length;
            }
        }
    
        var rows = [];
        var formulas = getAnswerFormula();
    
        for (var i = 0; i < rowsnum; i++){
            var row = addRowToValueTable();
            rows.push(row);
        }
        for (var letter in values){
            var col = doesColumnExist(letter);
            for (var i = 0; i < values[letter].length; i++){
                $($(rows[i]).find('td').eq($(col).index())).find('input').val(values[letter][i]);
            }
        }
    
        for (var row of rows) {
            for (var formula of formulas){
                getAnswerForRow(row, formula);
            }
        }
    }
    
    function getRowValues(row){
        var t = {};
        $(row).find("td").each(function() {
            var th = getTHFromTD(this)
            var k = th.text();
            if (th.attr('type') !== 'formula'){
                t[k] = $(this).find("input").val();
            }
        });
        return t;
    }
    
    function getTableValues(){
        var t = {};
        var error = false;
        $('#popup_valuetable').find("tr").each(function() {
            $(this).find("td").each(function() {
                var th = getTHFromTD(this)
                var k = th.text();
                if (th.attr('type') !== 'formula' && $(this).find("input")[0]){
                    if (!t[k]) t[k] = [];
                    var val = $(this).find("input").val();
                    if (!parseFloat(val)){
                        error = true;
                    }
                    t[k].push(val);
                }
            });
        });
        if (error){
            return;
        }
        return t;
    }
    
    function getTHFromTD(td){
        var $th = $(td).closest('table').find('th').eq($(td).index());
        return $th;
    }
    
    function getAnswerColumn(row, formula){
        var col = null;
        $(row).find("td").each(function() {
            var th = getTHFromTD(this)
            var k = th.text();
            if (th.attr('type') == 'formula' && th.text() == formula){
                col = this;
            }
        });
        return col;
    }
    
    function doesColumnExist(name){
        var exist = false;
        $('#popup_valuetable').find("th").each(function() {
            if ($(this).text() == name){
                exist = this;
            }
        });
        return exist;
    }
    
    function addColumnToValueTable(letter){
        if (!letter) letter = getNextLetter();
        if (!letter) return;
        var alen = getAnswerFormula().length;
        $('#popup_valuetable table').find('tr').each(function(){
            $(this).find('th').eq(-alen).before('<th>'+letter+'</th>');
            $(this).find('td').eq(-alen).before('<td><input type="number" value=""></td>'); 
        });
    }
    
    function addRowToValueTable(){
        var td = '';
        var alen = getAnswerFormula().length;
        var len = $('#popup_valuetable').find("th").length - alen;
        for (var i = 0; i < len; i++){
            td += '<td><input type="number" value=""></td>';
        }
        for (let i = 0; i < alen; i++){
            td += '<td>'+M.util.get_string('notavailable', 'qtype_numericalrecit')+'</td>';
        }
    
        $('#popup_valuetable table').append('<tr>'+td+'</tr>');
        return $('#popup_valuetable table tr:last');
    }
    
    function getNextLetter(){
        var alen = getAnswerFormula().length;
        var letters = ['a','b','c','d','e','f','g','h'];
        var len = $('#popup_valuetable').find("th").length;
        return letters[len-alen];
    }
    
    
    function getAnswerFormula(){
        var answers = [];
        $("input[name^=\"answer[\"]").each(function(_, a){
            a = a.value;
            if (a !== '')
                answers.push(a);
        });
        return answers
    }
    
    function getAnswerForRow(row, formula){
        var varsglobal = '';
        var values = getRowValues(row);
        for (var val in values){
            if (!values[val]) return;
            varsglobal += val + ' = ' + values[val] + ';';
        }
    
        var data = [];
        data['varsrandom'] = '';
        data['varsglobal'] = varsglobal;
        data['varslocals[0]'] = '';
        data['answers[0]'] = formula;
        
        data['start'] = 0;
        data['N'] = 1;
        data['random'] = 0;
    
        var p = [];
        for (var key in data) {
            p[p.length] = encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
        }
        params = p.join('&').replace(/ /g,'+');
    
        var url = M.cfg.wwwroot + '/question/type/numericalrecit/instantiate.php';
    
        var http_request = new XMLHttpRequest();
        http_request.open("POST", url, true);
    
        http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
        http_request.onreadystatechange = function () {
            if (http_request.readyState == 4 && http_request.status == 200) {
                try {
                    var vars = JSON.parse( http_request.responseText );
                    if (vars['errors'][0] && vars['errors'][0] !== ''){
                        alert(vars['errors'][0]);
                        return;
                    }
                    var td = getAnswerColumn(row, data['answers[0]']);
                    var num = Math.round((parseFloat(vars['lists'][0]['answer0'][0]) + Number.EPSILON) * 1000) / 1000
                    $(td).text(num);
                }catch(e){
                    console.log(e);
                    alert(M.util.get_string('error_algebraic_var', 'qtype_numericalrecit'))
                }
            }
        };
        http_request.send(params);   
    }
});