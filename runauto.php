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
//if (session_status() !== PHP_SESSION_ACTIVE) {
//    session_start();
//}
session_start();
if (!defined('ROOT_PATH'))
    define('ROOT_PATH', realpath(dirname(__FILE__)));
require_once(ROOT_PATH . '/include/functions.php');

if (!file_exists(ROOT_PATH . '/data/config.ini.php'))
    exit();
//if (isset($_SESSION['ini']))
//    unset($_SESSION['ini']);
$ini = parse_ini_file(ROOT_PATH . '/data/config.ini.php', false, INI_SCANNER_TYPED);

if (!$ini)
    exit();
//$_SESSION['ini'] = $ini;

//loadIniFile();
//if (!defined('ROOT_PATH'))
//    define('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);
//require_once ROOT_PATH . '/include/functions.php';


if (!$conn = otherconnectDB($ini)) {
    echo errorConnectDB();
    error_log("database not available!", 0);
    exit();
}
$sql = "SELECT idAt, nome, stato, dtStart, notistart, dtStop, notistop, giorninoti FROM attivita";
$stmt = $conn->query($sql);
if (!$stmt)
    throw new Exception('Errore query ricerca attività');
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = null;
notify($res,$ini,$conn);
$conn = null;

//return $res;

function otherconnectDB($ini)
{
    $dsn = $ini['db_drv'] . $ini['db_str'];

    //$options = [//PDO::ATTR_EMULATE_PREPARES=>false,
    //            PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION,
    //            PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC];
    $options = [ //PDO::ATTR_EMULATE_PREPARES=>false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_FOUND_ROWS => true
    ];

    try {
        $conn = new PDO($dsn, $ini['db_user'], $ini['db_psw'], $options);
    } catch (PDOException $e) {
        $conn = false;
    }
    return $conn;
}
function notify($res,$ini,$conn)
{

    foreach ($res as $row){
        notify_act_start($row,$ini,$conn);
        notify_act_stop($row,$ini,$conn);

        //if($row["notistart"])
        //$ggi = calcolaGiorniAllaScadenza($today, $row["dtStart"]);
        //$ggf = calcolaGiorniAllaScadenza($today, $row["dtStop"]);
        ////per ogni ggi e ggf <= giorninoti e diverso da zero (a zero per date non impostate) se non è già stato notificato invia la notifica agli utenti interessati
        ////$bef=calcolaDataAfter($row["dtStart"], $row["giorninoti"], 'D');
        //echo "id: " . $row["idAt"]. " - start: " . $row["dtStart"]." - alert: " . $ggi." ".$ggf."<br>";
    }
}

    function notify_act_start ($row,$ini, $conn)
    {
         $today= date("Y-m-d");
        if($row["dtStart"]!="" && $row["notistart"]!=true)
        {
             $ggi = calcolaGiorniAllaScadenza($today, $row["dtStart"]);
             if($ggi>=0 && $ggi<= $row["giorninoti"])
             {
                 //error_log("id: " . $row["idAt"] . " - start: " . $row["dtStart"] . " - alert: " . $ggi . "<br>", 0);
                 //echo "id: " . $row["idAt"] . " - start: " . $row["dtStart"] . " - alert: " . $ggi . "<br>";
                 //deve partire notifica
                 creaListaUtentiXNotifica($row["idAt"],$ggi, $row["nome"],$ini, $conn);
                 segnaNotificaInviata($row["idAt"],"notistart", $conn);
             }

        //$ggf = calcolaGiorniAllaScadenza($today, $row["dtStop"]);
        //per ogni ggi e ggf <= giorninoti e diverso da zero (a zero per date non impostate) se non è già stato notificato invia la notifica agli utenti interessati
        //$bef=calcolaDataAfter($row["dtStart"], $row["giorninoti"], 'D');

        }
    }

function notify_act_stop($row,$ini, $conn)
{
    $today = date("Y-m-d");
    if ($row["dtStop"] != "" && $row["notistop"] != true) {
        $ggi = calcolaGiorniAllaScadenza($today, $row["dtStop"]);
        if ($ggi >= 0 && $ggi <= $row["giorninoti"]) {
            //error_log("id: " . $row["idAt"] . " - stop: " . $row["dtStop"] . " - alert: " . $ggi . "<br>");
            //echo "id: " . $row["idAt"] . " - stop: " . $row["dtStop"] . " - alert: " . $ggi . "<br>";
            //deve partire notifica
            creaListaUtentiXNotifica($row["idAt"], $ggi, $row["nome"],$ini, $conn);
            segnaNotificaInviata($row["idAt"],"notistop", $conn);
        }

        //$ggf = calcolaGiorniAllaScadenza($today, $row["dtStop"]);
        //per ogni ggi e ggf <= giorninoti e diverso da zero (a zero per date non impostate) se non è già stato notificato invia la notifica agli utenti interessati
        //$bef=calcolaDataAfter($row["dtStart"], $row["giorninoti"], 'D');

    }
}

function segnaNotificaInviata($idAt,$what, $conn)
{
    //if (!$conn = otherconnectDB($ini)) {
    //    echo errorConnectDB();
    //    exit();
    //}
    $sql = "UPDATE attivita SET ".$what."='True' WHERE idAt=" . $idAt;
    $stmt = $conn->query($sql);
    if (!$stmt)
        throw new Exception('Errore query update notifiche inviate');
    //$conn = null;
    $stmt = null;
}

    function creaListaUtentiXNotifica($idAt, $giorni, $nomeAt,$ini, $conn){

    //if (!$conn = otherconnectDB($ini)) {
    //    echo errorConnectDB();
    //    exit();
    //}
    $sql = "SELECT ruolo, sottoruolo FROM attRuoli WHERE activity=".$idAt;
    $stmt = $conn->query($sql);
    if (!$stmt)
        throw new Exception('Errore query automatica');
    $rol = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $users = [];
    foreach ($rol as $rr)
    {
        //echo "ruoli " . $rr["ruolo"] . "  " . $rr["sottoruolo"]."<br>";
        $sqlu = "SELECT idUs, nome, cognome, email FROM ruoliUtenti, utenti WHERE utente=idUs AND ruolo= " .$rr["ruolo"]." AND subruolo =". $rr["sottoruolo"].";";
        $stmtu = $conn->query($sqlu);
        if (!$stmtu)
            throw new Exception('Errore query utenti per mail');
        $user = $stmtu->fetchAll(PDO::FETCH_ASSOC);
        $users=array_merge($user,$users);
    }
     $allusers=array_unique($users, SORT_REGULAR);  //eliminati gli utenti duplicati, invio una sola mail
    foreach ($allusers as $us) {
        //echo "utenti " . $us["email"] . "  " . $us["cognome"] . "<br>";
        $sub = "Notifica Inizio attività";
        $mes = "Ciao " . $us["nome"] . ",\r\n tra " . $giorni . " giorni l'attività : " . $nomeAt . " sarà disponibile, è richiesto anche il tuo contributo. \r\n Mi raccomando ricordati di partecipare!\r\ne-SEDRA team";

        sendNotifyMail($sub,$mes,"barbara.pela@itisvc.it",$ini );//$us["email"]
        //invio email
        //echo $sub . "  " . $mes. "<br>";

    }

    //$conn = null;
    $stmt = null;
    $stmtu = null;

    }

function sendNotifyMail($sub,$mes, $emailUtente,$ini)
{
    //echo "This works: {$arr['key']}";
    //$message ='Ricevi questa mail perchè; qualcuno ha richiesto un Token di accesso'."\r\n".'alla applicazione. Se non sei stato tu, ignora questo messaggio'."\r\n".'Token: '.$x;
    //$headers='From: postmaster@itisvc.it'."\r\n".'Reply-To: postmaster@itisvc.it'."\r\n".'X-Mailer: PHP/' . phpversion();
    //$headers = array(
    //    'From' => 'postmaster@itisvc.it',
    //    'Reply-To' => 'postmaster@itisvc.it',
    //    'X-Mailer' => 'PHP/' . phpversion()
    //);        non funzionava sul server

    ////$msg = "First line of text\nSecond line of text";
//// use wordwrap() if lines are longer than 70 characters
////$msg = wordwrap($msg,70);

    $to = $emailUtente; // il session user non è ancora settato - $_SESSION['user']['email'];
    $subject = $sub;
    //echo "This works: {$arr['key']}";
    $message = $mes; //'Avviso di ' . $not ."\r\n";

    $headers = 'From: ' . $ini['emailNoRep'] . "\r\n" . 'Reply-To: ' . $ini['emailNoRep'] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    return mail($to, $subject, $message, $headers);
}
?>

