<?php
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
?>
<div id="login-page">
<style>
    #login-page {
        position: fixed;
        background-color: rgb(43, 14, 5, 0.50);
        z-index: 100;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display:none;
    }

    #login-modal {
        margin: 10% auto 0;
        border-radius: .5em;
        position: relative;
        z-index: 101;
        background: linear-gradient(to left, var(--bs-primary), var(--bs-danger));
        max-width: 60vmin;
        padding: 3.5%;
        text-align: center;
        box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.3), 0 5px 5px 0 rgba(0, 0, 0, 0.2);
    }
    @media screen and (max-width: 900px) {
        #login-modal {max-width:50vw;margin:4% auto 0;}
    }
    @media screen and (max-width: 600px) {
        #login-modal {max-width: 70vmin;margin:20% auto 0;}
    }
    @media screen and (max-width: 400px) {
        #login-modal {max-width: 85vmin;margin:20% auto 0;}
    }
    @media screen and (max-width: 300px) {
        #login-modal {max-width: 98vmin;margin:4% auto 0;}
    }

    #login-modal .login-input {
        font-family: sans-serif;
        width: 100%;
        margin: 0 0 1.3em;
        padding: .6em;
        font-size: .87em;
    }
    #login-modal .login-button {
        font-family: sans-serif;
        text-transform: uppercase;
        outline: 0;
        background: var(--bs-warning);
        width: 100%;
        border: 0;
        padding: .7em;
        color: #FFFFFF;
        font-size: .87em;
        transition: all 0.3s ease;
    }
    #login-modal .login-button:hover {
        background: var(--bs-primary);
    }
    #login-modal .login-button:active {
        background: var(--bs-warning);
    }
    #login-modal .message {
        margin: 1.2em 0;
        font-size: .8em;
        color: var(--bs-white);
    }
    #login-modal .message a {
        color: var(--bs-warning);
        text-decoration: none;
    }

    #modal-close-button a {
        position: absolute;
        right: 1em;
        top: -1.8em;
    }
    #modal-close-button a::before {
        content: '';
        position: absolute;
        width: .22em;
        height: 1.5em;
        background-color: #e2f4f8;
        transform: rotate(45deg);
    }
    #modal-close-button a::after {
        content: '';
        position: absolute;
        width: .22em;
        height: 1.5em;
        background-color: #e2f4f8;
        transform: rotate(-45deg);
    }
    
    #register-form, #token-form, #expPsw-form {
        display: none;
    }
</style>

<div id="login-modal">
    <div id="modal-close-button"><a href="#"></a></div>
     <div id="spinner-div" class="pt-2 pb-3 d-flex align-items-center justify-content-center d-none" style="z-index:500;position:fixed;width:30vw;top:50%;left:50%;transform:translate(-50%,-50%);">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
    <form id="login-form">
      <p class="message">Hai richiesto un Token? <a class="register-link"href="#">Usalo!</a></p>
      <input class="login-input" type="email" name="EMAIL" placeholder="email"/>
      <input class="login-input" type="password" name="PSW" placeholder="password"/>
      <input class ="login-button" type="submit" value="LOGIN" name="login"/>
      <p class="message">Password dimenticata? <a class="token-link" href="#">Richiedi un Token</a></p>
    </form>
    <form id="register-form">
      <p class="message">Non hai un token? <a class="token-link" href="#">Richiedi Token</a></p>
      <input class="login-input" type="text" name="TOKEN" placeholder="Token"/>
      <input id="newPswR" class="login-input" type="password" name="NEWPSW" placeholder="nuova password"/>
      <input id="rNewPswR"class="login-input" type="password" name="rnewpsw" placeholder="ripeti nuova password"/>
      <input class ="login-button" type="submit" value="REGISTRA PASSWORD" name="regpsw"/>
      <p class="message">Hai una password? <a class="login-link" href="#">Effettua il login</a></p>
    </form>
    <form id="token-form">
      <p class="message">Hai richiesto un Token? <a class="register-link" href="#">Usalo!</a></p>
      <input class="login-input" type="email" name="EMAIL" placeholder="email"/>
      <input class ="login-button" type="submit" value="RICHIEDI TOKEN" name="reqtoken"/>
     <p class="message">Hai una password? <a class="login-link" href="#">Effettua il login</a></p>
    </form>
    <form id="expPsw-form">
      <p class="message" id="warning">Mancano <span id="ggScadPsw"></span> giorni alla scadenza della password.<br />Cambiarla ora? <a class="close-link" href="#">No, non ora!</a></p>
      <p class="message" id="expired" style="color:blue;">Attenzione: la password &#232; scaduta! Per continuare &#200; necessario impostare una nuova password! </p>
      <input class="login-input" type="email" name="EMAIL" placeholder="email"/>
      <input class="login-input" type="password" name="OLDPSW" placeholder="vecchia password"/>
      <input id="newPswE" class="login-input" type="password" name="NEWPSW" placeholder="nuova password"/>
      <input id="rNewPswE" class="login-input" type="password" name="rnewpsw" placeholder="ripeti nuova password"/>
      <input class ="login-button" type="submit" value="CAMBIA PASSWORD" name="updPsw"/>
    </form>
  </div>
<script>
    $("#newPswR, #rNewPswR").keyup(function () {
        if ($("#newPswR").val() == $("#rNewPswR").val()) {
            $("#newPswR").css("background-color", "white");
            $("#rNewPswR").css("background-color", "white");
        }
        else {
            $("#newPswR").css("background-color", '#ffe6ff');
            $("#rNewPswR").css("background-color", '#ffe6ff');
        }
    }); //fine $("#newPsw, #rNewPsw").keyup
    $("#newPswE, #rNewPswE").keyup(function () {
        if ($("#newPswE").val() == $("#rNewPswE").val()) {
            $("#newPswE").css("background-color", "white");
            $("#rNewPswE").css("background-color", "white");
        }
        else {
            $("#newPswE").css("background-color", '#ffe6ff');
            $("#rNewPswE").css("background-color", '#ffe6ff');
        }
    }); //fine $("#newPsw, #rNewPsw").keyup

    function validate($form) {
        $result = true;
        if ($form.prop('id') == 'register-form' && $("#newPswR").val() !== $("#rNewPswR").val()) {
            alert("I campi Password e Ripeti password devono contenere lo stesso valore");
            return false;
        }
        else if ($form.prop('id') == 'expPsw-form' && $("#newPswE").val() !== $("#rNewPswE").val()) {
            alert("I campi Password e Ripeti password devono contenere lo stesso valore");
            return false;
        }

        $form.children('.login-input').each(function (index) {
            //$(this).val( preg_replace('/\s+/', '', $(this).val()) ); //toglie blank
            if (!$(this).val()) {
                alert("Attenzione: compilare tutti i campi!");
                $result = false; return false;
            }
        }); // fine each
        return $result;
    } //fine function validate()
    //usare filter_var($field, SANITIZATION TYPE);  ???
    $("input").keypress(function (e) {
        if (e.which == 32) {
            alert("Lo spazio non \u00E8 un carattere valido!");
            return false;
        }
    }); //fine $("input").keypress

    /*
    $("input").loseFocus(function () {
        if ($new = $(this).val().replace(/\s/g, '')) {
            $(this).val($new);
            alert("Gli spazi non sono caratteri validi e pertanto sono stati rimossi!");
            return false;
        }
    }); //fine $("input").loseFocus
    */

    $("form").submit(function (event) {
        if (!validate($(this))) return false; // VALIDAZIONE FORM
        $('.login-button').attr('disabled', true);

        let wic = document.getElementById("spinner-div");
             wic.classList.remove('d-none');

      /*  waitIcon('#login-modal');*/ //js function per icona loader
        event.preventDefault();
   //////////////////////////////////////////
        let $idForm = $(this).prop('id');
        if ($idForm == 'login-form') var url = 'ajax/chklogin.php';
        else if ($idForm == 'register-form') var url = 'ajax/chktoken.php';
        else if ($idForm == 'token-form') var url = 'ajax/chkreqtoken.php';
        else if ($idForm == 'expPsw-form') var url = 'ajax/chkchgexppsw.php';

        //ATTENZIONE CHIUSA SESSION PRIMA DI CHIAMATA AJAX
    /*    session_write_close();*/

        $.post(url, $(this).serialize()).done(function (result) {
            if (result.substr(0, 2) === '->') { //errore sql
                alert('Errore 000-9: ' + result);
                logerror('-9', url, result);
            }
            else if ($idForm == 'login-form') {
                if (result === 'K') {
                    $.post('ajax/chkloglogin.php', { X: 'X' });

                    $(window.location).attr('href', 'index.php');
                    //window.location.href="\index.php";
                }
                else if (result === 'X') alert("Username e/o Password Errati!");
                else {  //if (Number.isInteger(result)) {
                    $('#login-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                    $('#expPsw-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                    if (result < 0) $('#warning').hide();
                    else {
                        $('#ggScadPsw').html(result);
                        $('#expired').hide();
                    }
                }
            }
            else if ($idForm == 'register-form') {
                if (result == 0) alert("Errore: Token non valido o scaduto!");
                else {
                    alert("Password reimpostata correttamente!");
                    $('#register-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                    $('#login-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                }
            }
            else if ($idForm == 'token-form') {
                if (result == -1) alert('Email non registrata nell\'applicazione!');
                else if (result == -2) alert('Errore: invio Email non riuscito!');
                else if (result == -3) alert('Attenzione: funzionalit\u00E0 non attiva! Contattare l\'amministratore dell\'applicazione.');
                else {
                    if (result > 0) alert('Un token con validit\u00E0 ' + result + ' ore \u00E8 stato inviato all\'indirizzo email: ' + $('#token-form input[name="EMAIL"]').val());
                    else alert('Un token \u00E8 stato inviato all\'indirizzo email: ' + $('#token-form input[name="EMAIL"]').val());
                    $('#token-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                    $('#register-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                }
            }
            else if ($idForm == 'expPsw-form') {
                if (result == 0) alert("Errore: Email o password non riconosciute!");
                else {
                    alert("Password reimpostata correttamente!");
                    $('#expPsw-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                    $('#login-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                }
            }
            else alert('Errore imprevisto in: ' + url);
        }).fail(function (xhr, ajaxOptions, thrownError) { //errore ajax
            let str = "Errore 000-10: " + xhr.status + " " + thrownError;
            alert(str);
            logerror('-10', url, str);
        }).always(function () { 

                let wic = document.getElementById("spinner-div");
                wic.classList.add('d-none');
          /*  removeWaitIcon();*/
            $('.login-button').attr('disabled', false);
        });

    }); // fine $("#login-modal form").submit


    // -------------------  FUNZIONALITA' DEL MODAL DIALOG
    $('a.login-link').click(function() {
        $(this).closest('form').animate({height: "toggle", opacity: "toggle"}, "slow");
        $('#login-form').animate({height: "toggle", opacity: "toggle"}, "slow");    
    });
    $('a.token-link').click(function() {
        $(this).closest('form').animate({height: "toggle", opacity: "toggle"}, "slow");
        $('#token-form').animate({height: "toggle", opacity: "toggle"}, "slow");
    });
    $('a.register-link').click(function(){
        $(this).closest('form').animate({height: "toggle", opacity: "toggle"}, "slow");
        $('#register-form').animate({height: "toggle", opacity: "toggle"}, "slow");
    });
    $(document).keyup(function(e){
        if (e.which == '27') {
            $("div").remove("#login-page");
	    }
    });
    $('#modal-close-button a').click(function(){
        $("div").remove("#login-page");
    });
    $('a.close-link').click(function () {
        window.location.reload();
    });

</script>
</div>
