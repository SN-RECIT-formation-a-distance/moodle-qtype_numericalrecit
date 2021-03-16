function initOnCompleteLoad(){
    $('#id_valuetable').click(function(){
        if (document.getElementById('popup_valuetable')) return;

        var formula = getAnswerFormula();
        if (formula == ''){
            alert(M.util.get_string('answermissing', 'qtype_numericalrecit'));
            return;
        }

        $(document.body).append('<div id="popup_valuetable" class="modal show fade in" tabindex="-1" role="dialog">\
        <div class="modal-dialog" role="document">\
          <div class="modal-content">\
            <div class="modal-header">\
              <h5 class="modal-title">Value Table</h5>\
              <button type="button" class="close" data-dismiss="modal" id="closevaluetable" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
              </button>\
            </div>\
            <div class="modal-body">\
            <table class="table"><thead><tr><th>a</th><th>b</th><th type="formula">'+formula+'</th></tr></thead>\
            <tbody>\
            </tbody></table>\
            <button type="button" class="btn btn-primary" id="addcolvaluetable">'+M.util.get_string('addcolumn', 'qtype_numericalrecit')+'</button>\
            <button type="button" class="btn btn-primary" id="addlinevaluetable">'+M.util.get_string('addline', 'qtype_numericalrecit')+'</button>\
            </div>\
            <div class="modal-footer">\
              <button type="button" class="btn btn-primary" id="savevaluetable">Save changes</button>\
            </div>\
          </div>\
        </div>\
      </div>');

      buildValueTable();
    });

    $("body").on("change", "#popup_valuetable input", function(){
        var row = $(this).closest('tr');
        getAnswerForRow(row);
    })

    $("body").on("click", "#closevaluetable", function(){
        $("#popup_valuetable").remove();
    })

    $("body").on("click", "#addcolvaluetable", function(){
        //addColumnToValueTable();
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
        $("#popup_valuetable").remove();
    });



};

document.onreadystatechange = function () {
    var state = document.readyState;
    if (state == 'complete') {
        initOnCompleteLoad();
    }
};

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
        sp = sp.replace(']', '');
        values[letter] = sp.split(',');
        rowsnum = values[letter].length;
    }

    var rows = [];

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
        getAnswerForRow(row);
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

function getAnswerColumn(row){
    var col = null;
    $(row).find("td").each(function() {
        var th = getTHFromTD(this)
        var k = th.text();
        if (th.attr('type') == 'formula'){
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
    $('#popup_valuetable table').find('tr').each(function(){
        $(this).find('th').eq(-1).before('<th>'+letter+'</th>');
        $(this).find('td').eq(-1).before('<td><input type="number" value=""></td>'); 
    });
}

function addRowToValueTable(){
    var td = '';
    var len = $('#popup_valuetable').find("th").length - 1;
    for (var i = 0; i < len; i++){
        td += '<td><input type="number" value=""></td>';
    }
    td += '<td>'+M.util.get_string('notavailable', 'qtype_numericalrecit')+'</td>';

    $('#popup_valuetable table').append('<tr>'+td+'</tr>');
    return $('#popup_valuetable table tr:last');
}

function getNextLetter(){
    var letters = ['a','b','c','d','e','f','g','h'];
    var len = $('#popup_valuetable').find("th").length;
    return letters[len-1];
}


function getAnswerFormula(){
    return $("input[name=\"answer[0]\"]").val();
}

function getAnswerForRow(row){
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
    data['answers[0]'] = getAnswerFormula();
    
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
                var td = getAnswerColumn(row);
                $(td).text(vars['lists'][0]['answer0'][0]);
            }catch(e){
                alert(M.util.get_string('error_algebraic_var', 'qtype_numericalrecit'))
            }
        }
    };
    http_request.send(params);   
}