
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
//------------ MODIFICHE LUISA -------------
    var chkVoti = []; //array globale per controllo votazione limitata, così strutturato ("nrostelle" => [limite massimo stelle, tot stelle già assegnate])
    // Es: chkVoti = array( '1' => [3,2], ....)
    var $svVoto;


ready(function () {

    //Quando pagina pronta chiamo funzione per conteggio voti assegnati che riempie array chkVoti
    chkVal(204);
    //console.log('chkVoti ' + chkVoti);
    //--------- FINE MODIFICHE LUISA --------------

    var defaultpage = 1;

    var disctable = document.querySelector('#Disctable');
    //console.log(disctable);
    disctable.addEventListener("click", (e) => {
        //if (e.target.nodeName != 'BUTTON' && e.target.nodeName != 'SPAN') { /*console.log('- '+e.target);*/  return; }
        //let edt = e.target.closest('.spedit');
        //console.log(edt.dataset.idtpc);
        let elem = e.target;
        let span = null;
        if (elem.classList.contains("linkstylebutton")) {          
            //console.log('+ ' + elem.nodeName + ' ' + idBisogno + 'aut ' + auth + ' rev ' + rev);
            //var n = Number(e.target.previousElementSibling.value) +1;
            //alert(n);
            refreshSinglePost(elem.dataset.idpro, defaultpage,204,0) 
        }
        if (elem.classList.contains("rating")) {   
            valGraduatoriaLU(elem,defaultpage,204); 
        }

    });
    
}); //end ready
