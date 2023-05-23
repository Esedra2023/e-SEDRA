
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
function refreshSinglePost(idb, dfpage,act,n=0) {
    var data = new FormData;
    data.append("page", dfpage);
   // alert("id " + elem.dataset.idbis);
    if (act == 104) {
        data.append("idBis", idb);
        call_ajax_viewPage('pages/bisognisinglepost.php', data);
    }
    else {
        data.append("idPro", idb);
       /* data.append("n", n);*/
        call_ajax_viewPage('pages/propostesinglepost.php', data);
    }
}


function chkVal(currentAct) {
    var data = new FormData;
    data.append("Act", currentAct);
    fetch('ajax/chkvalut.php', {
        method: 'POST',
        body: data
    })
        .then(
            function (response) {
                if (response.status !== 200) {
                    console.log('Looks like there was a problem. Status Code: ' +
                        response.status);
                    return;
                }
                // Examine the text in the response
                response.text().then(function (risp) {
                    //trasformo ilJSON in oggetto JS
                   /* alert(risp);*/
                    chkVoti = JSON.parse(risp);
                    //alert(chkVoti);
                 });
            })
        .catch(function (err) {
            //console.log('Fetch Error :-S', err);
        });
}

function valGraduatoriaLU(elem,page,act) {
    /* alert(elem.value);*/
    //------------ MODIFICHE LUISA -------------
    /*  alert(chkVoti.length);*/
   /* alert(chkVoti[elem.value][0] + " " + chkVoti[elem.value][1])*/
    if (chkVoti.length === 0 || chkVoti[elem.value][0] > chkVoti[elem.value][1]) {
        //------------- FINE MODIFICHE LUISA ----------
        var data = new FormData;
        if (act == 104) {
            data.append("idBis", elem.previousElementSibling.value);    //valore del campo nascosto con id bisogno
            data.append("val", elem.value);
            call_ajax_single_promise('ajax/updvalbis.php', data);
        }
        else {
            data.append("idPro", elem.previousElementSibling.value);    //valore del campo nascosto con id bisogno
            data.append("val", elem.value);
            call_ajax_single_promise('ajax/updvalpro.php', data);
        }
        chkVal(act); // per non fare il reload della pagina 
        if (page == 1) {
            //if (chkVoti[elem.value][0] != 1024)   //graduatoria in pagina principale con template
            //{
                var old = elem.getAttribute("old-value");
                if (!old)
                    refreshstelle(elem.value, (chkVoti[elem.value][1]) + 1, 0, 0);
                else
                    refreshstelle(elem.value, (chkVoti[elem.value][1]) + 1, old, (chkVoti[old][1]) - 1);
            //}
            //else {      //votazione semlice in pagina principale
            //    refreshsingola(elem.value, chkVoti[elem.value][1] + 1);
            //}
        }
            
        //location.reload();
        /*  call_ajax_updateBisogni(idb, elem.value);*/
        /*                $.post('/ajax/updvalbis.php', { idBis: $(this).attr('id'), val: $target.val() };*/
        //------------ MODIFICHE LUISA -------------
    }
    else {
        if (page == 1) {
            showMessagge("Voto non assegnabile vedi tabella", "my-callout-danger", "infoMessagge");
        } else { showMessagge("Voto non assegnabile vedi tabella pagina precedente", "my-callout-danger", "infoMessagge"); }

       /* alert("Voto non possibile - puoi assegnare al massino " + chkVoti[elem.value][0] + " volte " + elem.value + " stelle");*/
        //alert(elem.getAttribute("value") + ' ' + elem.getAttribute("old-value"));
        //alert($target.attr("value") + ' ' + $target.attr("old-value"));
        //alert("vecchio "+elem.getAttribute("old-value"));

        elem.setAttribute("value", elem.getAttribute("old-value"));
        //alert("nuovo "+elem.getAttribute("value"));
        //location.reload();

        /*  $target.attr("value", $target.attr("old-value"));*/
    }
            //------------- FINE MODIFICHE LUISA ----------
}