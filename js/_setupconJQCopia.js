
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

///////////////// variabili globali per chiamate ajax 
//var urlROOT;
var count = 0;
var arrayFiles = [];
var totFiles;
var resume = 0;
//var rootPATH;
/////////////////
/// togliere blank
//$outputString = preg_replace('/\s+/', '', $originalString);

//per passare il ROOT_PATH in JS
//function get_rootpath() {
//   /* rootPATH = window.location.pathname.split(`/`)[1];*/
//    rootPATHjs = document.getElementById('myRoot').value;
//    alert('js ' + rootPATHjs);
//}




$(document).ready(function () {

    /*get_rootpath();*/
   /* alert(rootPATH);*/
    $("#dbms").change(function () {
        if ($("#dbms").val() == "SQL Server Express LocalDB") {
            $('#host').val("").prop("disabled", true).prev().prop("hidden", true);
            $('#usn').val("").prop("disabled", true).prev().prop("hidden", true);
            $('#psw').val("").prop("disabled", true).prev().prop("hidden", true);
        } else {
            $('#host').prop("disabled", false).prev().prop("hidden", false);
            $('#usn').prop("disabled", false).prev().prop("hidden", false);
            $('#psw').prop("disabled", false).prev().prop("hidden", false);
        }
        /*alert(rootPATH);*/
    }); //fine $("#dbms").change

    //$("input").blur(function (event) {
    //    var pattern = new RegExp(/^[a-zA-Z0-9_]+$/);
    //    alert(pattern.test($(this)));
    //    return false;
    //}); //fine $("input").blur

$("input").keypress(function (event) {
    if (event.which == 32) {
        alert("Lo spazio non \u00E8 un carattere valido!");
        return false;
    }
}); //fine $("input").keypress

$("#pswAdm, #rpswAdm").keyup(function () {
    if ($("#pswAdm").val() == $("#rpswAdm").val()) {
        $("#pswAdm").css("background-color", "rgba(var(--bs-success-rgb),0.35)");
        $("#rpswAdm").css("background-color", "rgba(var(--bs-success-rgb),0.35)");
    }
    else {
        $("#pswAdm").css("background-color", "rgba(var(--bs-danger-rgb),0.35)");
        $("#rpswAdm").css("background-color", "rgba(var(--bs-danger-rgb),0.35)");
    }
}); //fine $("#pswAdm, #rpswAdm").keyup

$('#login').click(function () {
    //$(window.location).attr('href', 'login.php');
    $.post("ajax/chklogin.php", { EMAIL: $("#mailAdm").val(), PSW: $("#pswAdm").val() }
    ).done(function (result) {
        if (result ==='K') $(window.location).attr('href', 'index.php'); //window.location.reload();
        else if (result.substr(0, 2) === '->') { //se errore in login.php
            alert('Errore 000-9: ' + result);
            logerror('-9', 'chklogin.php', result);
        }
        else $("#msg").text("Username o Password Errati!");
    }).fail(function (xhr, ajaxOptions, thrownError) { //errore ajax
        let str = "Errore 000-10: " + xhr.status + " " + thrownError;
        alert(str);
        logerror('-10', 'chklogin.php', str);
    })
});

    //$('#domRoot').click(function () {
    //    if ($('#domRoot').is(':checked')) {
    //        $('#cartRoot').val("").prop("disabled", true).prev().prop("hidden", true);
    //    }
    //    else { 
    //        $('#cartRoot').val("").prop("disabled", false).prev().prop("hidden", false);
    //    }
    //});

$("form").submit(function (event) {
    event.preventDefault();

    $error = false;
    $("select, input").each(function (index) {
        $(this).val($.trim($(this).val()));
        if (!$(this).val() && !$(this).prop("disabled")) {
            alert("Inserire tutti i dati richiesti");
            $error = true; return false;
        }
    }); // fine each
    if (!$error && $("#pswAdm").val() !== $("#rpswAdm").val()) {
        alert("I campi Password e Ripeti password dell'Account Amministratore devono contenere lo stesso valore");
        $error = true; return false;
    }

    if ($error) return false;

    //SE la validazione form è OK
    $('#installa').attr('disabled', true);
    $(".progress").css("visibility", "visible");
    $("#msgProgress").css("visibility", "visible");

    if ($('#installa').attr('value') == "Installa" || !count) start();
    else if (resume == 1) progress();
    else if (resume == -2) finish();
    else alert("Errore non identificato!\n");

    //// crea file config.ini.php e logerrors.txt.php sul server
    //// restituisce vettore (json) con i file "0 *.php" per setup
    function start() {
        dati = 'DBMS=' + $('#dbms').val() + '&HOST=' + $('#host').val() + '&DB=' + $('#db').val() + '&DBEX=' + $('#dbEx').is(':checked') + '&USN=' + $('#usn').val() + '&PSW=' + $('#psw').val() + '&resume=' + resume + '&SubDROOT=' + $('#myRoot').val() +
            '&MAIL=' + $('#emailLink').val() + '&SOCIAL=' + $('#socialLink').val() + '&WEB=' + $('#webLink').val();
        $.ajax({
            method: "POST",
            url: "setup/start.php",
            data: dati,
            dataType: "json"
        }).done(function (result) {
            let str = JSON.stringify(result);
            if (str.substring(0, 2) === '->') { //se errore in start.php
                alert('Errore 000-1: ' + str);
                $('#installa').attr('value', "Riprova");
                $('#installa').attr('disabled', false);
                resume = -1;
                logerror('-1', 'start.php', str);
            }
            else {
                resume = 0; //Eventuale errore nel file php è stato corretto
                arrayFiles = [...result];       //operatore ... spread copia il vettore result in arrayFiles
                totFiles = arrayFiles.length;
                $("#msgProgress").text("Inizializzazione completata...");
                progress();
            }
        }).fail(function (xhr, ajaxOptions, thrownError) { //errore ajax
            let str = "Errore 000-2: " + xhr.status + " " + thrownError;
            alert(str);
            $('#installa').attr('value', "Riprova");
            $('#installa').attr('disabled', false);
            resume = -1;
            logerror('-2', 'start.php', str);
        })
    }  //fine start()

    //// esegue codice php dei file restituiti da start()
    function progress() {
        if (count == 1) dati = 'MAILAD=' + $('#mailAdm').val() + '&PSWAD=' + $('#pswAdm').val() + '&resume=' + resume;
        else dati = 'resume=' + resume;
        //console.log(arrayFiles[count]);
        $.ajax({
            method: "POST",
            url: arrayFiles[count],
            data: dati
        }).done(function (result) {
            if (result.substr(0, 2) === '->') {
                let str = 'Errore ' + arrayFiles[count].substr(7, 4) + ": " + result;
                alert(str);
                $('#installa').attr("disabled", false);
                $('#installa').attr("value", "Riprova");
                resume = 1;
                logerror(count, arrayFiles[count], str);
            }
            else {
                let percent = Math.round(((++count) / totFiles * 100.0)) + "%";
                $("#myBar").css("width", percent).html(percent);
                $("#msgProgress").text(result);
                if (count == 1) $('#dbEx').attr('disabled', true); //dopo creazione prima tabella DB non si può più cambiare
                resume = 0; //Eventuale errore nel file php è stato corretto
                if (count < totFiles) progress();
                else finish();
            }
        }).fail(function (xhr, ajaxOptions, thrownError) {
            let str = 'Errore ' + arrayFiles[count].substr(7, 4) + ': ' + xhr.status + ' ' + thrownError;
            alert(str);
            $('#installa').attr("disabled", false);
            $('#installa').attr("value", "Riprova");
            resume = 1;
            logerror(count, arrayFiles[count], str);
        })
    } //fine progress()

    //// scrive setup = 1 (installazione completata) nel file config.ini.php
    function finish() {
        $.ajax({
            method: "POST",
            url: "setup/finish.php",
            data: "resume=" + resume
        }).done(function (result) {
            if (result.substr(0, 2) === '->') {
                let str = 'Errore 000-3: ' + result;
               alert(str);
                $('#installa').attr("disabled", false);
                $('#installa').attr("value", "Riprova");
                resume = -2;
                logerror('', 'finish.php', str);
            }
            else {
                $("#login").css("display", "block");
                $("#installa").css("display", "none");
                $("#msgProgress").text("Installazione completata.");
            }
        }).fail(function (xhr, ajaxOptions, thrownError) {
            let str = 'Errore 000-4: ' + xhr.status + ' ' + thrownError;
            alert(str);
            $('#installa').attr("disabled", false);
            $('#installa').attr("value", "Riprova");
            resume = -2;
            logerror('', 'finish.php', str);
        })
    } //fine finish()

}); // fine $("form").submit

}); //fine $(document).ready


