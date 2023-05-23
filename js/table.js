
/** This file is part of e-Sedra.
 *
 *   e-Sedra is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   e-Sedra is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *   along with e-Sedra.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright Copyright 2023 e-Sedra. All Rights Reserved.
 *
 */

function creaContenutoCella(ele, riga) {
    var st = "";
    if (ele.type == "button") { // creo button
        st = "<button type='" + ele.type+"'";
        if (ele.hasOwnProperty("class")) {
            st += setClass(ele.class);
        }      
        if (ele.hasOwnProperty("exdata")) {
            st += getComplexData(ele.exdata, riga);
            
        }
        if (ele.hasOwnProperty("value")) {
            st += ' value=' + riga[ele.value];
        }       
        if (ele.hasOwnProperty('other')) {
            st += ele.other;
        }
        if (ele.hasOwnProperty('disable')) {
            if (ele.disable.hasOwnProperty('field'))
            {
                if (riga[ele.disable.field] == 1)
                    st += " disabled";
            } 
        }
        st += '>';
        if (ele.hasOwnProperty('text')) {
            st += setText(ele.text, riga);
        }
        if (ele.hasOwnProperty('textobj')) {
            var ob = ele.textobj;
            if (ob.hasOwnProperty('span')) { 
                st += "<span class = 'bi ";
                if (ob.span.hasOwnProperty('field')) {
                    var ico;
                    //console.log("-- " + ob.span.field+" "+riga[ob.span.field] + " " + ob.span.value);
                    if (riga[ob.span.field] == ob.span.value) {                       
                        ico = ob.span.icon;
                    }
                        else ico = ob.span.deficon;                 
                    //console.log(ico);
                    st += ico;// 'bi-trash3'
                }
                st += "'></span >";
            }
        }           
        st += '</button >';
        //if (ele.text[0] == 0)
        //    st += ele.text[1] + '</button >';
        //else if (ele.text[0] == 1)
        //    st += riga[ele.text[1]] + '</button >';
    }
    if (ele.type == "text") { //creo testo smeplice
        st += setText(ele.text, riga);
        //if (ele.text[0] == 0)
        //    st += ele.text[1];
        //else if (ele.text[0] == 1)
        //    st += riga[ele.text[1]];
    }
    if (ele.type == "link") { //creo link
        st += "<a href= uploadpdf/";
        st += setText(ele.link, riga) + " target='_blank'>";
        //if (ele.link[0] == 0)
        //    st += ele.link[1] + " target='_blank'>";
        //else if (ele.link[0] == 1)
        //    st += riga[ele.link[1]] + " target='_blank'>";
        st += setText(ele.title, riga) + "</a>";
        //st += ele.title + "</a>";
    }
    return st;
}

function setClass(elem) {
    let a = " class= '";
    for (let x = 0; x < elem.length; x++)
        a += " " + elem[x];
    a += "'";
    return a;
}
function getComplexData(elem, riga) {
    var att = Object.keys(elem);
    var st = "";
    for (let k = 0; k < att.length; k++) {
        //console.log(elem[att[k]]);
        st += ' data-' + att[k] + "=" +setText(elem[att[k]], riga);
    //    + riga[ele.exdata[k + 1]];
    }
    return st;
}

function setText(elem, riga) {
    if (elem[0] == 0)
        return elem[1];
    else if (elem[0] == 1)
        return riga[elem[1]];
}

function refreshTable(tableid, righe, job) {
    var tab = document.getElementById(tableid);
    var oldtb = tab.getElementsByTagName("tbody");
    
    var tbn = document.createElement("tbody");
    let i;
    for (i = 0; i < righe.length; i++) {
        tr = document.createElement("tr");
        td = document.createElement("td");
        td.innerHTML = (i + 1);
        tr.appendChild(td);
        for (let j = 0; j < job.ncol; j++) {
            td = document.createElement("td");
            td.innerHTML = creaContenutoCella(job.c[j], righe[i]);
            tr.appendChild(td);
        }
        tbn.appendChild(tr);
    }
    for (let i = 0; i < oldtb.length; i++) {
        if (oldtb[i] != null)
            oldtb[i].remove();
    }
    tab.appendChild(tbn);
}

async function call_ajax_dati_table(table, viewtab, obj) {
    var data = new FormData();
    role = document.getElementById("whatcont").value;
    //console.log(role);
    data.append('table', table);
    data.append('role', role);
    let promo1 = await fetch('ajax/getallposts.php', {
        method: 'POST',
        body: data
    }).then(successResponse => {
        if (successResponse.status != 200) {
            return null;
        } else {
            return successResponse.json();
        }
    },
        failResponse => {
            //console.log("promessa fallita in select dati per " + table);
            return null;
        });
    //console.log('aspetto che la promessa risolva');
    let result = await promo1;
    //console.log('OK.. ' + result);
    if (result) {
        //alert("dati tabella pronti");
        refreshTable(viewtab, result, obj);

    }//finestra per messaggio errore è quella un po' più in basso
    else showMessagge(result['errors'], "my-callout-danger", "infoMessagge2");
}
