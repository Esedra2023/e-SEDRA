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
if (session_status() !== PHP_SESSION_ACTIVE) {
          session_start();
      }
if (!defined('ROOT_PATH'))
    define ('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);
require_once ROOT_PATH .'/include/functions.php';
//require_once ROOT_PATH .'/adminsez/admin/includes/adminfunctions.php';


revProposta();

function revProposta()
{
    $output=[];
    $errors=[];
    $idbis=[];

    $title = esc($_POST['proTitle']);
	$body = esc($_POST['proBody']);
    //$prop = esc($_POST['perconto']);

	// validate form - già campi richiesti in JS
    if (empty($title)) {
        array_push($errors, "Titolo del bisogno mancante");
    }
    if(empty($body)) {
        array_push($errors, " Corpo del bisogno vuoto");
    }
    //if(empty($prop)) {
    //    array_push($errors, " manca Proponente");
    //}
    if(!isset($_POST['bis']))
        $idbis=null;
    else{
        $idbis=explode(",",$_POST['bis']);
    }
    //if(!isset($_POST['propmail']))
    //    $mail="";
    //else  $mail = esc($_POST['propmail']);

    //if(!isset($_POST['propcell']))
    //    $cell=null;
    //else $cell=esc($_POST['propcell']);

    if(!isset($_POST['altreinfo']))
        $bisg=null;
    else $bisg=esc($_POST['altreinfo']);

    if(isset($_POST['NdR']))
        $Ndr=esc($_POST['NdR']);
    else $Ndr="";

    // registro la proposta if there are no errors in the form


    if (count($errors) == 0) {
        if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

        if(isset($_POST['publish']))
            $publish=1;
        else
            $publish=0;

        $crud=$_POST['crud'];
        if($crud=='D')      //sempre cancellazione logica per i revisori
        {
            //uso note e revisor, metto il campo deleted a 1 che indica CANCELLAZIONE LOGICA e pubblicato a zero
            $title=$body=null;
        }
        $sql = "CALL revProposte(:vid, :title, :body, :bis, :publish, :revisor, :note, :crud)";
        if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
        $stmt = $conn->prepare($sql);
        $arrVal = array(':vid'=>$_POST['hidden_post_id'], ':bis'=>$bisg,
            ':title'=>$title, ':body'=>$body, ':publish'=>$publish,
                            ':revisor'=>$_SESSION['user']['idUs'], ':note' => $Ndr,':crud'=>$crud);

        if(! $stmt->execute($arrVal)) throw new Exception('Errore query revisiona proposta');

        if($idbis!=null && $idbis!="")
        {
            $hi=$_POST['hidden_post_id'];
            //confrontare i due elenchi di bisogni, ma attenzione ai tipi diversi
            //$sql="SELECT bisogno FROM propBis WHERE proposta=$hi;";
            //$stmt = $conn->prepare($sql);
            //if(! $stmt->execute()) throw new Exception('Errore query recupera bisogni per proposta');
            //$bissa = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //if(identicalValues($_POST['bis'],$bissa))
            //{
                $sql="DELETE FROM propBis WHERE proposta=$hi;";
                foreach($idbis as $b)
                {
                    $sql=$sql."INSERT INTO propBis VALUES ($hi,$b);";
                }
                $stmt = $conn->prepare($sql);
                if(! $stmt->execute()) throw new Exception('Errore query save bisogni per proposta');
                // $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //}
        }
        $conn=null;
        $stmt=null;

        if($crud=='D')  $output['success']="Cancellazione logica proposta avvenuta con successo";
        else  $output['success']="Revisione proposta terminata";
    }
    if(count($errors) > 0) {
        $output['errors'] = $errors;
    }

    echo json_encode($output);

}

//function identicalValues( $arrayA , $arrayB ) {
//    sort( $arrayA );
//    sort( $arrayB );
//    return $arrayA == $arrayB;
//}

