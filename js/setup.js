
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


ready(function () {
    const dbms = document.getElementById('dbms');
    const inst = document.getElementById('installa')
    dbms.addEventListener('change', function () {
        let host = document.getElementById('host');
        let usn = document.getElementById('usn');
        let psw = document.getElementById('psw');
        let dbex = document.getElementById('dbEx');
        if (dbms.value == "SQL Server Express LocalDB") {
            host.value = "";
            host.setAttribute('disabled', true);
            usn.value = "";
            usn.setAttribute('disabled', true);
            psw.value = "";
            psw.setAttribute('disabled', true);
            dbex.setAttribute('disabled', true);
        }
        else {
            host.removeAttribute('disabled');
            usn.removeAttribute('disabled');
            psw.removeAttribute('disabled');
            dbex.removeAttribute('disabled');
        }
    });
    

    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('keypress', function (e) {
            if (e.key == " ") {
                alert("Lo spazio non \u00E8 un carattere valido!");
                e.preventDefault();
            }
        });
    });

    function addKeyUpListener(ids, callback) {
        ids.forEach(id => {
            //console.log(id);
            const element = document.getElementById(id);
            if (element) element.addEventListener('keyup', callback);
        });
    }

    // Aggiunta degli event listener
    addKeyUpListener(['pswAdm', 'rpswAdm'], checkPassword);

    // Funzione per controllare se le password sono uguali
    function checkPassword(event) {
        const newPswR = document.getElementById('pswAdm');
        const rNewPswR = document.getElementById('rpswAdm');
        
        const target = event.target; // Elemento che ha scatenato l'evento
        let other; // L'altro campo da confrontare
        console.log(target.id);
        if (target.id === 'pswAdm') {
            other = rNewPswR;
        }
        else other = newPswR;
        console.log(target.value +'  '+other.value);
        if (target.value === other.value) {
            target.style.backgroundColor = "rgba(var(--bs-success-rgb),0.35";
            other.style.backgroundColor = "rgba(var(--bs-success-rgb),0.35";
        } else {
            target.style.backgroundColor = "rgba(var(--bs-danger-rgb),0.35)";
            other.style.backgroundColor = "rgba(var(--bs-danger-rgb),0.35)";
        }
    }

    document.getElementById('login').addEventListener('click', function () {
        const email = document.getElementById("mailAdm").value;
        const password = document.getElementById("pswAdm").value;

        fetch("ajax/chklogin.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `EMAIL=${encodeURIComponent(email)}&PSW=${encodeURIComponent(password)}`
        })
            .then(response => response.text())
            .then(result => {
                if (result === 'K') {
                    window.location.href = 'index.php';
                } else if (result.substring(0, 2) === '->') {
                    alert('Errore 000-9: ' + result);
                    logerror('-9', 'chklogin.php', result);
                } else {
                    document.getElementById("msg").textContent = "Username o Password Errati!";
                }
            })
            .catch(error => {
                let str = "Errore 000-10: " + error.status + " " + error;
                alert(str);
                logerror('-10', 'chklogin.php', str);
            });
    });

document.querySelectorAll('form').forEach(form => {    
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            errore = false;
            //alert("prima di validate");
            // Esegui la validazione del form qui. Assicurati che la funzione validate() ora accetti un elemento form HTML
            document.querySelectorAll('select, input').forEach(tb => {
                tb.value = tb.value.trim();
                if (!tb.value && !tb.disabled) {
                    if(!errore)
                        alert("Inserire tutti i dati richiesti");
                    errore = true; return false;
                }
            });
            if (!errore && document.getElementById('pswAdm').value !== document.getElementById('rpswAdm').value) {
                alert("I campi Password e Ripeti password dell'Account Amministratore devono contenere lo stesso valore");
                errore = true; return false;
            }
            if (errore) return false;

            //SE la validazione form è OK
           
            if(inst)
                inst.setAttribute('disabled', true);
            document.getElementsByClassName('progress')[0].style.visibility = 'visible';
            document.getElementById('msgProgress').style.visibility = 'visible';
            if (inst.value == 'Installa' || !count) start();
            else if (resume == 1) progress();
            else if (resume == -2) finish();
            else alert("Errore non identificato!\n");
        });

    async function start() {
        dati = new FormData(document.getElementById('formsetup'));
        //aggiumgo i campi non abilitati
        dati.append('SubDROOT', document.getElementById('myRoot').value);
        dati.append('resume', resume);
        let dbe = document.getElementById('dbEx');
        dati.append('DBEX', dbe.checked);
        //dati = 'DBMS=' + dbv + '&HOST=' + hostv + '&DB=' + dbv + '&DBEX=' + $('#dbEx').is(':checked') + '&USN=' + usnv + '&PSW=' + pswv + '&resume=' + resume + '&SubDROOT=' + myrv +
        //    '&MAIL=' + $('#emailLink').val() + '&SOCIAL=' + $('#socialLink').val() + '&WEB=' + $('#webLink').val();
        let promo1 = await fetch('setup/start.php', {
            method: 'POST',
            body: dati
        }).then(successResponse => {
            if (successResponse.status != 200) {
                return null;
            } else {
                return successResponse.json();
            }              
        },
        failResponse => {
            inst.value = 'Riprova';
            inst.removeAttribute('disabled');
            resume = -1;
            logerror('-2', 'start.php', str);
            return null;
            });

        //console.log('aspetto che la promessa risolva');
        let result = await promo1;
        if (result != null) { 
        //console.log('promessa risolta ');
        let str = JSON.stringify(result);
        //console.log(str);
        if (str.substring(0, 2) === '->') { //se errore in start.php
            alert('Errore 000-1: ' + str);
            inst.value = 'Riprova';
            inst.removeAttribute('disabled');
            resume = -1;
            logerror('-1', 'start.php', str);
        }
        else {
            resume = 0; //Eventuale errore nel file php è stato corretto
            arrayFiles = [...result];       //operatore ... spread copia il vettore result in arrayFiles
            totFiles = arrayFiles.length;
            document.getElementById('msgProgress').value="Inizializzazione completata...";
            progress();
            }
        }
    }  //fine start()


    function progress() {
        let dati;
        if (count == 1) {
            dati = 'MAILAD=' + document.getElementById('mailAdm').value + '&PSWAD=' + document.getElementById('pswAdm').value + '&resume=' + resume;
        } else {
            dati = 'resume=' + resume;
        }

        fetch(arrayFiles[count], {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: dati
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(result => {
                if (result.substring(0, 2) === '->') {
                    let str = 'Errore ' + arrayFiles[count].substring(7, 11) + ": " + result;
                    alert(str);
                    document.getElementById('installa').removeAttribute('disabled');
                    document.getElementById('installa').value = "Riprova";
                    resume = 1;
                    logerror(count, arrayFiles[count], str);
                } else {
                    let percent = Math.round(((++count) / totFiles * 100.0)) + "%";
                    document.getElementById('myBar').style.width = percent;
                    document.getElementById('myBar').innerHTML = percent;
                    document.getElementById('msgProgress').textContent = result;
                    if (count == 1) document.getElementById('dbEx').setAttribute('disabled', true); // dopo creazione prima tabella DB non si può più cambiare
                    resume = 0; // Eventuale errore nel file php è stato corretto
                    if (count < totFiles) progress();
                    else finish();
                }
            })
            .catch(error => {
                let str = 'Errore ' + arrayFiles[count].substring(7, 11) + ': ' + error.message;
                alert(str);
                document.getElementById('installa').removeAttribute('disabled');
                document.getElementById('installa').value = "Riprova";
                resume = 1;
                logerror(count, arrayFiles[count], str);
            });
    } //fine progress()

    //// scrive setup = 1 (installazione completata) nel file config.ini.php
    function finish() {
        fetch("setup/finish.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: "resume=" + resume
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(result => {
                if (result.substring(0, 2) === '->') {
                    let str = 'Errore 000-3: ' + result;
                    alert(str);
                    document.getElementById('installa').removeAttribute('disabled');
                    document.getElementById('installa').value = "Riprova";
                    resume = -2;
                    logerror('', 'finish.php', str);
                } else {
                    document.getElementById('login').style.display = "block";
                    document.getElementById('installa').style.display = "none";
                    document.getElementById('msgProgress').textContent = "Installazione completata.";
                }
            })
            .catch(error => {
                let str = 'Errore 000-4: ' + error.message;
                alert(str);
                document.getElementById('installa').removeAttribute('disabled');
                document.getElementById('installa').value = "Riprova";
                resume = -2;
                logerror('', 'finish.php', str);
            });
    } //fine finish()
}); // fine $("form").submit
}); //fine $(document).ready
