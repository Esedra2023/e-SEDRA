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


function controlloContemporaneo($cont,$conn)
{
        $contv=$cont-1;
        $sql="SELECT idAt FROM attivita WHERE idAt=$contv AND dtStart  >= (SELECT dtStart from attivita where idAt=$cont) AND dtStart <= (SELECT dtStop from attivita where idAt=$cont);";
        $stmt=$conn->query($sql);
        if(!$stmt) throw new Exception('Errore query controllo contemporaneo');
        $at = $stmt->fetch(PDO::FETCH_ASSOC); //result set per attività sovrapposta
        if($at!="")
        {
            $sql="UPDATE attivita SET dtStart=null, dtStop=null, stato=2 WHERE idAt=$cont;";
            $stmt=$conn->query($sql);
            if(!$stmt) throw new Exception('Errore aggiornamento attività contemporanea');
        }
}





//function myfunctiontest()
//{
//    echo 'function caricata';
//}
function loadIniFile()
{
    if(!file_exists(ROOT_PATH.'/data/config.ini.php')) return false;
    if(isset($_SESSION['ini'])) unset($_SESSION['ini']);
    //$ini=parse_ini_file(ROOT_PATH. '/data/config.ini.php');
    $ini=parse_ini_file(ROOT_PATH. '/data/config.ini.php',false,INI_SCANNER_TYPED);

    if(!$ini) return false;
    $_SESSION['ini'] = $ini;
    return true;
}

/**MODIFICA O AGGIUNGE UNA CHIAVE E RELATIVO VALORE IN UNA SEZIONE DI UN FILE *.INI  (anche non esistente)
 * @param string     $section   Sezione del file *.ini. Se non esiste la crea.
 * @param string     $item      Chiave da aggiungere nella sezione del file *.ini
 * @param string     $value     Valore per la chiave aggiunta
 * @param bool       $file      se true aggiunge se non esiste sezione o item (default: true)
 * @param string     $file      File *.ini (default: '/../data/config.ini.php')
 */
function updIniFile($section, $item, $value, $add=true, $file=null){
    if(is_null($file)) $file = ROOT_PATH. '/data/config.ini.php';

    if(!($ini = file($file))) return 'Impossibile aprire il file *.ini';
    $searchItem=$item.'='.$value.PHP_EOL;
    $found=false;
    if(($pos = array_search('['.$section.']'.PHP_EOL, $ini)) === false) {
        if($add){ $ini[] = PHP_EOL.'['.$section.']'.PHP_EOL; $ini[] = $searchItem; }
        else return 'Sezione non trovata nel file *.ini';
    }
    else{
        while($pos < count($ini) )
        {
            if($ini[$pos][0] != '[')
            {
                if(explode('=', $ini[$pos])[0] == $item){
                    $ini[$pos]=$searchItem;
                    $found = true;
                    break;
                }
            }
            $pos++;
        }
        if($add && !$found){
            //$newItem=$item.'='.$value;      //per aggiungere in fondo senza newline
            array_splice($ini, $pos-1, 0, $searchItem);}
        else if(!$found) return 'Chiave non trovata nel file *.ini';
    }
    if(!(file_put_contents($file, implode($ini)))) return 'Impossibile aggiornare il file *.ini';
    return true;
}

//CAMBIA IL VALORE DI UNA CHIAVE SOLO SE GIA' ESISTENTE IN UN FILE *.INI
// true_or_string_error setIniItem(chiave, valore_da_impostare [,file])
function setIniItemValue($item, $value, $file=null){
    if(is_null($file)) $file = ROOT_PATH. '/data/config.ini.php';
    $foundItem = false;
    if(!($ini = file($file))) return 'Impossibile aprire il file *.ini';
    foreach($ini as $key=>$val){
        if(explode('=', $val)[0] == $item){
            $ini[$key]=$item.'='.$value.PHP_EOL;
            $foundItem = true;
            break;
        }
    }
    if(!$foundItem) return 'Chiave non trovata nel file *.ini';
    if(!(file_put_contents($file, implode($ini)))) return 'Impossibile aggiornare il file *.ini';
    return true;
}

function esc(String $value){
	// bring the global db connect object into function
	// remove empty space sorrounding string
	$val = trim($value);
    //$val = mysqli_real_escape_string($conn, $value);
	return $val;
}

function dateString($data, $time)
{
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
        if(!$time)
            $st="CONVERT(VARCHAR,$data,105)";
        else
            $st="CONVERT(VARCHAR,$data,121)";
    }
    else{
        if(!$time)
            $st="DATE_FORMAT($data,'%d-%m-%Y')";
        else
            $st="DATE_FORMAT($data,'%d-%m-%Y %T')";
    }
    return $st;
}

function getRoleName($idR)
{
    for($i=0;$i<count($_SESSION['allRoles']);$i++)
    {
        if($_SESSION['allRoles'][$i]['idRl'] == $idR)
            return $_SESSION['allRoles'][$i]['ruolo'];
    }
    if($idR==1)
        return "Amministratore";
    return "";
}
function getAllTopics() {
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $sql = "CALL getAmbiti()";
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
	$stmt = $conn->query($sql);
	if(!$stmt) throw new Exception('Errore query getAmbiti');
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC); //result set per tutti i ruoli
    $conn=null;
    $stmt=null;
	return $topics;
}

function calcolaDataAfter($datapwd,$m,$tipo){
    $inter='P'.$m.$tipo;
    $dateIni = date_create($datapwd);
    $dateStop = date_add($dateIni, new DateInterval($inter));
    return(date_format($dateStop, "Y-m-d"));
}


function calcolaGiorniAllaScadenza($dtSta,$dtSto)
{
    $din=new DateTime($dtSta);
    $dfin=new DateTime($dtSto);

    $gg=($din)->diff($dfin)->days;
    if($din < $dfin)
        return $gg;
    else return $gg*(-1);
}

function defineMoment()
{
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $sql = "CALL getMoment";        //tutte le attività con date che comprendono la data odierna
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
        $sql = '{'.$sql.'}';
    }
        $stmt = $conn->prepare($sql);
        //$stmt->bindValue(':how',$_POST['user_id']);
        if(! $stmt->execute()) throw new Exception('Errore query getMoment');
        $mom= $stmt->fetchAll(PDO::FETCH_ASSOC);

        $n=count($mom);
        //if(isset($_SESSION['moment'])){ unset($_SESSION['moment']); $_SESSION['moment']=[];}
        //if($n==0)
        //{
        //    $sql = "CALL getRecent";    //ultima attività terminata
        //    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
        //        $sql = '{'.$sql.'}';
        //    }
        //    $stmt = $conn->prepare($sql);
        //    if(! $stmt->execute()) throw new Exception('Errore query getMoment');
        //    $mom= $stmt->fetchAll(PDO::FETCH_ASSOC);       //dovevo fare fetch per prendere solo il primo ma non funziona
        //    $n=count($mom);
        //    if(isset($_SESSION['moment'])){
        //        unset($_SESSION['moment']); $_SESSION['moment']=[];
        //    }
        //}
        if($n==0)
            $_SESSION['moment']['0'] = ['nome' => "Non ci sono attivit&agrave; attive in questo momento."];
         $conn=null;
         $stmt=null;
         foreach($mom as &$mt)
         {
           unset($mt['note']);
           if($mt['active'])
           {
               $gg=calcolaGiorniAllaScadenza(date("Y-m-d"),$mt['dtStop']);
               $mt['ggscad']=$gg+1;
               //sarà impossibile che succeda!!!
               if($gg<0) $mt['stato']=2;    //TERMINATA
               $key=$mt['idAt'];
               unset($mt['idAt']);
               $author=getRuoliAutorizzati($key);
               $mt['author']=$author;
               $_SESSION['moment'][$key] = $mt;
           }
        }
}
function getLastPollType($idAt){
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT ballottaggio FROM attivita WHERE idAt=$idAt AND stato=2;";
    $stmt = $conn->query($sql);
    if(! $stmt) throw new Exception('Errore query getAuthorRoles');
    $bal = $stmt->fetch(PDO::FETCH_ASSOC);

    $conn=null;
    $stmt=null;
    if($bal)
        return $bal;
    else return null;
}

function IamRevisor($ruol)
{
    if(array_key_exists(1, $_SESSION['user']['roles']))      //admin fa sempre parte dei revisori
        return true;
    if(array_key_exists($ruol, $_SESSION['user']['roles']))
        return true;
    foreach($_SESSION['user']['roles'] as $r)
    {
         if(array_key_exists($ruol, $r))
             return true;
    }
    return false;
}

function compareRuoli($sezau,$ruser){ //il primo è un vettore di interi da prendere a coppie, il secondo ha chiavi e sottochiavi
    $k=array_keys($ruser);
    for($i=0;$i<count($sezau);$i+=2)
    {
        for($j=0;$j<count($k);$j++)
        {
            if($k[$j]==$sezau[$i])
            {
                $sub=$sezau[$i+1];
                if($sub==0)
                    return true;    //sono autorizzati ruoli primari
                else
                {
                    //sono autorizzati sottoruoli
                    if(array_key_exists($sub,$ruser[$k[$j]]))    // tutte le sottochiavi del ruolo k[j]
                        return true;
                }
            }
        }
    }
    return false;
}
function getRuoliAutorizzati($idAt){
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
	$sql = "SELECT ruolo, sottoruolo FROM attRuoli WHERE activity=$idAt;";
    $stmt = $conn->query($sql);
    if(! $stmt) throw new Exception('Errore query getAuthorRoles');
    $auth = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
    $rauth=[];
    for($i=0;$i<count($auth);$i++)
    {
        $rauth[]=$auth[$i]['ruolo'];
        if($auth[$i]['sottoruolo']!=null)
            $rauth[]=$auth[$i]['sottoruolo'];
        else
            $rauth[]=0;
    }
    return $rauth;
}


function mostraNews(){

    $iam=$_SESSION['user']['idUs'];
    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
    $sql = "SELECT * FROM news WHERE utente=$iam;";
    $stmt = $conn->query($sql);
    if(! $stmt) throw new Exception('Errore query getRevisor');
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn=null;
    $stmt=null;
    return $res;
}


//function getRevisor($idAt){
//    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
//    $sql = "SELECT revisore FROM attivita WHERE idAt=$idAt;";
//    $stmt = $conn->query($sql);
//    if(! $stmt) throw new Exception('Errore query getRevisor');
//    $res = $stmt->fetch(PDO::FETCH_ASSOC);
//    $conn=null;
//    $stmt=null;
//    $_SESSION['ini']['revisore']=$res['revisore'];
//}

//function getPeriod($idAt,$from, $to){
//    if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
//    $sql = "SELECT dtStart, dtStop FROM attivita WHERE idAt=$idAt;";
//    $stmt = $conn->query($sql);
//    if(! $stmt) throw new Exception('Errore query getPeriod');
//    $period = $stmt->fetch(PDO::FETCH_ASSOC);
//    $conn=null;
//    $stmt=null;
//    $_SESSION['ini'][$from]=$period['dtStart'];
//    $_SESSION['ini'][$to]=$period['dtStop'];
//}



function connectDB()
{
    $dsn = $_SESSION['ini']['db_drv'] . $_SESSION['ini']['db_str'];

    //$options = [//PDO::ATTR_EMULATE_PREPARES=>false,
    //            PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION,
    //            PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC];
    $options = [//PDO::ATTR_EMULATE_PREPARES=>false,
                    PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_FOUND_ROWS => true];

    try {
        $conn = new PDO( $dsn, $_SESSION['ini']['db_user'], $_SESSION['ini']['db_psw'], $options);
    }catch( PDOException $e ) { $conn = false;}
    return $conn;
}

function genToken($length=8)
{
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012345678';
    $len = strlen($str); $token = ''; srand();
    for ($i = 0; $i < $length; $i++) $token .= $str[rand(0,$len - 1)];
    return $token;
}

function sendTokenMail($token,$emailUtente)
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

    $to  = $emailUtente; // il session user non è ancora settato - $_SESSION['user']['email'];
    $subject = 'Richiesta Token';
    //echo "This works: {$arr['key']}";
    $message ='Ricevi questa mail perchè qualcuno ha richiesto un Token di accesso'."\r\n".'alla applicazione. Se non sei stato tu, ignora questo messaggio.'."\r\n\r\n".'Token: '.$token;

    if($_SESSION['ini']['scTkn']) $message=$message."\r\n".'La sua validità è di '.$_SESSION['ini']['scTkn'].' ore.';
    $headers='From: '.$_SESSION['ini']['emailNoRep']. "\r\n".'Reply-To: '.$_SESSION['ini']['emailNoRep']."\r\n".'X-Mailer: PHP/'. phpversion();
    return mail($to, $subject, $message, $headers);
}


function error($msg = NULL):string {
    return '->'.$msg;
}

function errorConnectDB($msg = ' Connessione al database non riuscita'):string {
    return error($msg);
}

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . error($msg) . '")</script>';
}

function redirect($url, $clean = true)
{
    if($clean) ob_clean();
    else ob_flush();
    header('Location: '.$url);
    exit();
}

function forbidden()
{
    header("HTTP/1.0 403 Forbidden");exit();
}

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

//BARBARA 16/09/2022        NON USATA!!!!
//function parse_csv_file($full_path) {
//    //$full_path = ROOT_PATH . $path;
//    $contents = [];

//    if(!file_exists($full_path) || !is_readable($full_path)) {
//        return $contents;
//    }

//    if(mime_content_type($full_path) !== 'text/csv') {
//        return $contents;
//    }

//    $file = fopen($full_path, 'r');
//    $righe=file($full_path);
//    print_r($righe);
//    foreach ( $righe as $riga ) {
//    // Separo le colonne
//        $colonne = explode( ';', $riga );

//    }
//    print_r($colonne);
//    //$headers = fgetcsv($file);
//    //print_r($headers);

//    //while (($data = fgetcsv($file)) !== false) {
//    //    if (count($data) == 1 && is_null($data[0])) {
//    //        continue;
//    //    }
//    //    $contents[] = array_combine($headers, $data);
//    //}
//    fclose($file);
//    return $colonne;
//}

//set_exception_handler(function($e) {
//    error_log($e->getMessage());
//    exit('Qualcosa non ha funzionato!! '. $e->getMessage() ); //something a user can understand
//});


//BARBARA 19/09/2022
//function set_User($vid,$nome,$cgnm,$email,$cell,$cod,$psw=null)
//{
//    $up=true;
//    //$sql = "CALL getDupMail(:how)";
//    //if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
//    //    $sql = '{'.$sql.'}';
//    //}
//    if(!$conn = connectDB()) {
//        //return 'no db';
//        echo errorConnectDB();
//        exit();
//    }
//    //try{
//    //    $stmt = $conn->prepare($sql);
//    //    $stmt->bindValue(':how', $email);
//    //    $stmt->execute();
//    //    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    //   } catch(PDOException $e) { echo error($e); exit();}
//    //   ////$count = $stmt->rowCount();

//    //if($res == null)
//    //{
//        //CREarE PaSSWORD DI DEFAULT PIU' ROBUSTA
//        if($psw==null)
//        {   //ATTENZIONE CREARE UNA PASSWORD UNIVOCA
//            $pswTemp = strToLower($nome[0] . $cgnm[0]);
//            $psw = password_hash($pswTemp, PASSWORD_DEFAULT);
//            $up=false;
//        }
//        $sql = "CALL setDataUser(:vid, :n, :c, :em, :ps, :cel, :cod)";
//        if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
//            $sql = '{'.$sql.'}';
//        }
//        try{
//            $stmt = $conn->prepare($sql);
//            //vid=0 per insert into, =id per update
//            $arrVal = array(':vid'=>$vid, ':n'=>$nome, ':c'=>$cgnm, ':em'=>$email, ':ps'=>$psw, ':cel'=>$cell, ':cod'=>$cod);
//            $stmt->execute($arrVal);
//            //$stmt->bindValue(':vid', $vid);
//            //$stmt->bindValue(':n', $nome);
//            //$stmt->bindValue(':c', $cgnm);
//            //$stmt->bindValue(':em', $email);
//            //$stmt->bindValue(':ps', $psw);
//            //$stmt->bindValue(':cel', $cell);
//            //$stmt->bindValue(':cod', $cod);

//            //$stmt->execute();
//        } catch(PDOException $e) {echo error("errore inserimento/aggiornamento utente: ".$e); exit();}
//            //GESTIRE L'ERRORE MAIL DUPLICATA
//             /*PDOException: SQLSTATE[23000]: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]
//              * Violation of UNIQUE KEY constraint 'UQ__utenti__AB6E616413B05423'. Cannot insert duplicate key in object 'dbo.utenti'.
//              * The duplicate key value is ..*/
//             $ultimo=$conn->lastInsertId();
//             $conn=null;
//             $stmt=null;
//             if($up)
//                 return $vid;
//             else
//                return $ultimo;
//         //}
//        //else
//        // {
//        ////echo'mail duplicata';
//        //return 0;
//        //}
//}

//BARBARA 19/09/2022
//function set_ruolo_utente($utente,$ruolo,$subr)
//{
//    $sql = "CALL setRoleUser(:how,:role, :sub)";
//    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) {
//        $sql = '{'.$sql.'}';
//    }
//    if(!$conn = connectDB()) {
//        echo errorConnectDB(); exit();
//    }
//    try{
//        $stmt = $conn->prepare($sql);
//        $stmt->bindValue(':how', $utente);
//        $stmt->bindValue(':role', $ruolo);
//        $stmt->bindValue(':sub', $subr);
//        $stmt->execute();
//    } catch(PDOException $e) {echo error('Errore inserimento ruolo utente '.$e); exit();}
//}

//function ConfirmMessage($msg, $redirect)
//{
//            echo '<script type="text/javascript">
//            confirm("' . $msg . '")
//            window.location.href = "'.$redirect.'"
//            </script>';
//}

//function EchoMessage($msg, $redirect)
//{
//            echo '<script type="text/javascript">
//            alert("' . $msg . '");
//            window.location.href = "'.$redirect.'"
//            </script>';
//}

?>