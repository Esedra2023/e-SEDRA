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

<script>
    //alert("script trovato");

    //document.addEventListener('DOMContentLoaded', function () {
    //const LOG = document.getElementById('LOGLOG');
    //alert(LOG);
    //LOG.addEventListener('click', function () {
    //    alert("cliccato su login");
    //    // Funzione per aggiungere event listener a più elementi
    //});

    function addKeyUpListener(ids, callback) {
        ids.forEach(id => {
            //console.log(id);
            const element = document.getElementById(id);
            if (element) element.addEventListener('keyup', callback);
        });
    }

    // Aggiunta degli event listener
    addKeyUpListener(['newPswR', 'rNewPswR', 'newPswE', 'rNewPswE'], checkPasswords);

    // Funzione per controllare le password
    function checkPasswords(event) {
        const newPswR = document.getElementById('newPswR');
        const rNewPswR = document.getElementById('rNewPswR');
        const newPswE = document.getElementById('newPswE');
        const rNewPswE = document.getElementById('rNewPswE');

        const target = event.target; // Elemento che ha scatenato l'evento
        let other; // L'altro campo da confrontare

        if (target.id === 'newPswR' || target.id === 'rNewPswR') {
            other = target.id === 'newPswR' ? rNewPswR : newPswR;
        } else if (target.id === 'newPswE' || target.id === 'rNewPswE') {
            other = target.id === 'newPswE' ? rNewPswE : newPswE;
        }

        if (target.value === other.value) {
            target.style.backgroundColor = "white";
            other.style.backgroundColor = "white";
        } else {
            target.style.backgroundColor = "#ffe6ff";
            other.style.backgroundColor = "#ffe6ff";
        }
    }

    function validate(form) {
        let result = true;
        //console.log("validate");
        if (form.id === 'register-form' && document.getElementById('newPswR').value !== document.getElementById('rNewPswR').value) {
            alert("I campi Password e Ripeti password devono contenere lo stesso valore");
            return false;
        } else if (form.id === 'expPsw-form' && document.getElementById('newPswE').value !== document.getElementById('rNewPswE').value) {
            alert("I campi Password e Ripeti password devono contenere lo stesso valore");
            return false;
        }

        const inputs = form.querySelectorAll('.login-input');
        inputs.forEach(input => {
            if (!input.value.trim()) {
                alert("Attenzione: compilare tutti i campi!");
                result = false;
                return false; // In un forEach, questo agisce come un 'continue' nel ciclo normale
            }
        });

        return result;
    }

    //function toggleForm2(form) {
    //    //const form = document.getElementById(idForm);
    //    const currentOpacity = window.getComputedStyle(form).opacity;

    //    if (currentOpacity > 0) {
    //        // Inizia l'animazione di fade out
    //        form.style.opacity = '0';
    //        // Attendi che l'opacità scenda a 0 prima di animare l'altezza
    //        setTimeout(() => {
    //            form.style.height = '0';
    //        }, 500); // Corrisponde alla durata dell'animazione dell'opacità
    //    } else {
    //        // Inizia l'animazione di fade in
    //        form.style.opacity = '1';
    //        form.style.height = 'auto'; // o imposta un'altezza specifica
    //    }
    //}

    //function toggleForm1(form) {
    //    // Se l'opacità è 1, nascondi il form
    //    if (window.getComputedStyle(form).opacity === '1') {
    //        form.style.opacity = '0';
    //        setTimeout(() => form.style.display = 'none', 500); // Assumendo una transizione di 0.5s
    //    } else { // Altrimenti, mostra il form
    //        form.style.display = '';
    //        setTimeout(() => form.style.opacity = '1', 10); // Ritardo per garantire che display sia applicato
    //    }
    //}
    document.querySelectorAll('input').forEach(input => {
        //console.log(input);
        input.addEventListener('keypress', function (e) {
            if (e.key == " ") {
                alert("Lo spazio non è un carattere valido!");
                e.preventDefault();
            }
        });
    });


    // Trova tutti i form e aggiungi loro un event listener per la sottomissione
    document.querySelectorAll('form').forEach(form => {
        //console.log(form);
        form.addEventListener('submit', function (event) {
            //console.log(event+" submit");
            event.preventDefault();
            //alert("prima di validate");
            // Esegui la validazione del form qui. Assicurati che la funzione validate() ora accetti un elemento form HTML
            if (!validate(form)) return false;

            document.querySelectorAll('.login-button').forEach(button => {
                button.setAttribute('disabled', true);
            });

            let spinnerDiv = document.getElementById("spinner-div");
            if (spinnerDiv) spinnerDiv.classList.remove('d-none');

            let formId = form.getAttribute('id');
            let url = '';

            switch (formId) {
                case 'login-form':
                    url = 'ajax/chklogin.php';
                    break;
                case 'register-form':
                    url = 'ajax/chktoken.php';
                    break;
                case 'token-form':
                    url = 'ajax/chkreqtoken.php';
                    break;
                case 'expPsw-form':
                    url = 'ajax/chkchgexppsw.php';
                    break;
            }
            // Prepara i dati da inviare
            let formData = new FormData(form);
            //console.log(url);
            // Effettua la richiesta POST
            fetch(url, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(result => {
                    // Qui gestisci la risposta come nel tuo codice jQuery
                    // Per esempio:
                    //alert("Result " + result);
                    if (result.substring(0, 2) === '->') {
                        alert('Errore 000-9: ' + result);
                        logerror('-9', url, result); // Assicurati di convertire anche logerror in vanilla JS se necessario
                    }
                    // Continua con le altre condizioni...
                    else {
                        //alert("form ID " + formId);
                        switch (formId) {
                            case 'login-form':
                                switch (result) {
                                    case 'K':
                                        fetch('ajax/chkloglogin.php', {
                                            method: 'POST', // Metodo di invio dati al server
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded', // Specifica il tipo di contenuto
                                            },
                                            body: 'X=X' // Dati inviati al server
                                        }).then(response => {
                                            if (response.ok) {
                                                //alert("Reindirizzo l'utente");
                                                // Se la richiesta è andata a buon fine, reindirizza l'utente
                                                window.location.href = 'index.php';
                                            } else {
                                                // Gestisci eventuali errori di risposta
                                                console.error('Richiesta fallita', response);
                                            }
                                        }).catch(error => {
                                            // Gestisci eventuali errori di rete
                                            console.error('Errore di rete', error);
                                        });

                                        //$.post('ajax/chkloglogin.php', { X: 'X' });
                                        //$(window.location).attr('href', 'index.php');
                                        break;
                                    case 'X':
                                        alert("Username e/o Password Errati!");
                                        break;
                                    default:
                                        //console.log("sono nel default");
                                        fadeInAndGrowHeight(document.getElementById('expPsw-form'));
                                        fadeOutAndShrinkHeight(document.getElementById('login-form'));
                                        
                                        /*$('#login-form').animate({ height: "toggle", opacity: "toggle" }, "slow");*/
                                        //$('#expPsw-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                                        if (result < 0) document.getElementById('warning').style.display = 'none'; //$('#warning').hide();
                                        else {
                                            document.getElementById('ggScadPsw').innerHTML = result;
                                            document.getElementById('expired').style.display = 'none';
                                            //$('#ggScadPsw').html(result);
                                            // $('#expired').hide();
                                        }
                                }
                                break;
                            case 'register-form':
                                if (result == 0) alert("Errore: Token non valido o scaduto!");
                                else {
                                    alert("Password reimpostata correttamente!");
                                    fadeOutAndShrinkHeight(document.getElementById('register-form'));
                                    fadeInAndGrowHeight(document.getElementById('login-form'));
                                    //$('#register-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                                    //$('#login-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                                }
                                break;
                            case 'token-form':
                                let emailInputValue = document.querySelector('#token-form input[name="EMAIL"]').value;

                                switch (result) {
                                    case '-1': alert('Email non registrata nell\'applicazione!');
                                        break;
                                    case '-2': alert('Errore: invio Email non riuscito!');
                                        break;
                                    case '-3': alert('Attenzione: funzionalit\u00E0 non attiva! Contattare l\'amministratore dell\'applicazione.');
                                        break;
                                    case '0':
                                        alert('Un token \u00E8 stato inviato all\'indirizzo email: ' + emailInputValue);
                                        break;
                                    default: //dovrebbe essere >0
                                        alert('Un token con validit\u00E0 ' + result + ' ore \u00E8 stato inviato all\'indirizzo email: ' + emailInputValue);
                                }
                                fadeOutAndShrinkHeight(document.getElementById('token-form'));
                                fadeInAndGrowHeight(document.getElementById('register-form'));
                                //$('#token-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                                //$('#register-form').animate({ height: "toggle", opacity: "toggle" }, "slow");

                                break;
                            case 'expPsw-form':
                                if (result == 0) alert("Errore: Email o password non riconosciute!");
                                else {
                                    alert("Password reimpostata correttamente!");
                                    fadeOutAndShrinkHeight(document.getElementById('expPsw-form'));
                                    fadeInAndGrowHeight(document.getElementById('login-form'));
                                    //$('#expPsw-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                                    //$('#login-form').animate({ height: "toggle", opacity: "toggle" }, "slow");
                                }
                                break;
                        }
                    }
                })
                .catch(error => {
                    //console.error('Errore nella richiesta:', error);
                    alert(`Errore 000-10: ${error}`);
                    logerror('-10', url, `${error}`); // Converti anche questa se necessario
                })
                .finally(() => {
                    if (spinnerDiv) spinnerDiv.classList.add('d-none');
                    document.querySelectorAll('.login-button').forEach(button => {
                        button.removeAttribute('disabled');
                    });
                });
        });
    });

    // -------------------  FUNZIONALITA' DEL MODAL DIALOG

    document.querySelectorAll('a.login-link').forEach(link => {
        link.addEventListener('click', function () {
            let form = this.closest('form');
            //console.log(form.id +" OUT Login IN")
        fadeOutAndShrinkHeight(form);
            fadeInAndGrowHeight(document.getElementById('login-form'));
        
        });
    });

    document.querySelectorAll('a.token-link').forEach(link => {
        link.addEventListener('click', function () {
            let form = this.closest('form');
        //console.log(form.id + " OUT token IN")
        fadeOutAndShrinkHeight(form);
            fadeInAndGrowHeight(document.getElementById('token-form'));
        
        });
    });

    document.querySelectorAll('a.register-link').forEach(link => {
        link.addEventListener('click', function () {
            let form = this.closest('form');
            //console.log(form.id + " OUT register IN")
            fadeOutAndShrinkHeight(form);
            fadeInAndGrowHeight(document.getElementById('register-form'));
        });
    });

    // Assicurati di aggiungere CSS per le transizioni di opacità.

    document.addEventListener('keyup', function (e) {
        if (e.key == 'Escape') { // keyCode per Esc
            let loginPage = document.getElementById("login-page");
            if (loginPage) loginPage.remove();
        }
    });

    document.querySelectorAll('#modal-close-button a').forEach(button => {
        button.addEventListener('click', function () {
            let loginPage = document.getElementById("login-page");
            if (loginPage) loginPage.remove();
        });
    });

    document.querySelectorAll('a.close-link').forEach(link => {
        link.addEventListener('click', function () {
            window.location.reload();
        });
    });

   

  // Funzione per ottenere l'altezza, modificata per restituire l'altezza memorizzata se disponibile
    function getVisibleHeight(element) {
        if (element.dataset.originalHeight) {
            return parseInt(element.dataset.originalHeight, 10);
        }

        // Memorizza lo stile corrente per ripristinarlo in seguito
        const originalStyle = {
            visibility: element.style.visibility,
            position: element.style.position,
            height: element.style.height,
            display: element.style.display
        };

        // Applica stili temporanei per il calcolo
        element.style.visibility = 'hidden';
        element.style.position = 'absolute';
        element.style.height = '';
        element.style.display = 'block';

        // Calcola l'altezza
        const height = element.offsetHeight;

        // Memorizza l'altezza per usi futuri
        element.dataset.originalHeight = height;

        // Ripristina gli stili originali
        Object.assign(element.style, originalStyle);

        return height;
    }

        function fadeInAndGrowHeight(element) {
        // Assicurati che l'elemento sia "calcolabile"
        const height = getVisibleHeight(element);
        //console.log("IN " + element.id + " " + height);
        // Imposta l'elemento per l'animazione
        element.style.overflow = 'hidden';
        element.style.height = '0px';
        element.style.display = 'block';

        const animation = element.animate([
            { height: '0px', opacity: 0 },
            { height: `${height}px`, opacity: 1 }
        ], {
            duration: 750,
            fill: 'forwards'
        });

        animation.onfinish = () => {
            element.style.height = ''; // Rimuovi l'altezza fissa per permettere un flusso di layout naturale
            element.style.opacity = '';
            element.style.overflow = '';
        };
    }

    function fadeOutAndShrinkHeight(element) {
        // Calcola l'altezza attuale per l'animazione e memorizzala
        const height = element.offsetHeight;
        element.dataset.originalHeight = height; // Memorizza l'altezza prima di nascondere

        const animation = element.animate([
            { height: `${height}px`, opacity: 1 },
            { height: '0px', opacity: 0 }
        ], {
            duration: 500,
            fill: 'forwards'
        });

        animation.onfinish = () => {
            // Nascondi completamente l'elemento alla fine dell'animazione
            element.style.display = 'none';
            // Pulisci le proprietà per evitare conflitti con il CSS dell'elemento
            element.style.height = '';
            element.style.opacity = '';
        };
    }

  //  });//quando il DOM è creato
</script>


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
            display: none;
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
            #login-modal {
                max-width: 50vw;
                margin: 4% auto 0;
            }
        }

        @media screen and (max-width: 600px) {
            #login-modal {
                max-width: 70vmin;
                margin: 20% auto 0;
            }
        }

        @media screen and (max-width: 400px) {
            #login-modal {
                max-width: 85vmin;
                margin: 20% auto 0;
            }
        }

        @media screen and (max-width: 300px) {
            #login-modal {
                max-width: 98vmin;
                margin: 4% auto 0;
            }
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

        #login-form {
            display: block;
            /*transition: opacity 0.5s, height 0.5s ease;*/
            /*overflow: hidden;*/ /*Previene lo sfarfallio durante la transizione di altezza*/
        }

        #register-form, #token-form, #expPsw-form {
            display: none;
           /* opacity: 1;  visibile*/
        }

    </style>

    <!--<div id="login-form" style="opacity: 1; transition: opacity 0.5s, height 0.5s; overflow: hidden;">-->
    <!-- Contenuti del form di login -->
    <!--</div>-->

    <div id="login-modal">
        <div id="modal-close-button">
            <a href="#"></a>
        </div>
        <div id="spinner-div" class="pt-2 pb-3 d-flex align-items-center justify-content-center d-none" style="z-index:500;position:fixed;width:30vw;top:50%;left:50%;transform:translate(-50%,-50%);">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
        <form id="login-form" >
            <p class="message">
                Hai richiesto un Token? <a class="register-link" href="#">Usalo!</a>
            </p>
            <input class="login-input" type="email" name="EMAIL" placeholder="email" />
            <input class="login-input" type="password" name="PSW" placeholder="password" />
            <input class="login-button" type="submit" value="LOGIN" name="login" />
            <p class="message">
                Password dimenticata? <a class="token-link" href="#">Richiedi un Token</a>
            </p>
        </form>



        <form id="register-form">
            <p class="message">
                Non hai un token? <a class="token-link" href="#">Richiedi Token</a>
            </p>
            <input class="login-input" type="text" name="TOKEN" placeholder="Token" />
            <input id="newPswR" class="login-input" type="password" name="NEWPSW" placeholder="nuova password" />
            <input id="rNewPswR" class="login-input" type="password" name="rnewpsw" placeholder="ripeti nuova password" />
            <input class="login-button" type="submit" value="REGISTRA PASSWORD" name="regpsw" />
            <p class="message">
                Hai una password? <a class="login-link" href="#">Effettua il login</a>
            </p>
        </form>

        <form id="token-form">
            <p class="message">
                Hai richiesto un Token? <a class="register-link" href="#">Usalo!</a>
            </p>
            <input class="login-input" type="email" name="EMAIL" placeholder="email" />
            <input class="login-button" type="submit" value="RICHIEDI TOKEN" name="reqtoken" />
            <p class="message">
                Hai una password? <a class="login-link" href="#">Effettua il login</a>
            </p>
        </form>


        <form id="expPsw-form">
            <p class="message" id="warning">
                Mancano <span id="ggScadPsw"></span>
                giorni alla scadenza della password.<br />
                Cambiarla ora? <a class="close-link" href="#">No, non ora!</a>
            </p>
            <p class="message" id="expired" style="color:blue;">Attenzione: la password &#232; scaduta! Per continuare &#200; necessario impostare una nuova password! </p>
            <input class="login-input" type="email" name="EMAIL" placeholder="email" />
            <input class="login-input" type="password" name="OLDPSW" placeholder="vecchia password" />
            <input id="newPswE" class="login-input" type="password" name="NEWPSW" placeholder="nuova password" />
            <input id="rNewPswE" class="login-input" type="password" name="rnewpsw" placeholder="ripeti nuova password" />
            <input class="login-button" type="submit" value="CAMBIA PASSWORD" name="updPsw" />
        </form>

    </div>

</div>




