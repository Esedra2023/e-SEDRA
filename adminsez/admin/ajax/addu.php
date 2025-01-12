
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
    //require_once('../../config.php');
    if (!defined('ROOT_PATH'))
        define ('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);
    require_once ROOT_PATH .'/include/functions.php';
    require_once(ROOT_PATH . '/adminsez/admin/includes/utilfunctions.php');
    //header('Content-Type: application/json');

    //if (isset($_POST['create_user']) || isset($_POST['update_user'])) {
    $datu=$_POST;
    foreach($datu as &$dt)
    {
        $dt=trim($dt);
    }
    if(!isset($datu['nome']) && !isset($datu['cognome']) && $datu['user_id']==1)
    {
        $datu['nome']="";
        $datu['cognome']="Admin";
    }

    //$x = trim($_POST['nome']);
    //$datau =["nome" => '{$x}'];
    //$x = trim($_POST['cognome']);
    //        $cgnm = trim($_POST['cognome']);
    //        $email = trim($_POST['email']);

    //        $cell = trim($_POST['cell']);
            //$cod = trim($_POST['cod']);
            //$ruoli=$_POST['ruoli'];
            $output = [];
            $errors = [];
            $succ=[];
            if(isset($datu["email"]))
            $email=$datu["email"];
            //Controlli sui campi obbligatori
            // verifico che tutti i campi siano stati compilati e con dati validi
            //if(empty($nome)) { array_push($errors,['param' => 'nome', 'msg' => 'Campo obbligatorio']);}
            if (!preg_match('/^[A-Za-z \'-]+$/i',$datu["nome"]) && $_POST['user_id']!=1) //solo l'admin può avere il nome vuoto
            {
                        array_push($errors,['param' => 'nome', 'msg' => 'Formato non valido per il nome']);
            }
            //if(empty($cgnm)) {
            //     array_push($errors,['param' => 'cognome', 'msg' => 'Campo obbligatorio']);
            //}
            //else
            if (!preg_match('/^[A-Za-z \'-]+$/i',$datu["cognome"]) && $_POST['user_id']!=1) {
                array_push($errors,['param' => 'cognome', 'msg' => 'Formato non valido per il cognome']);
            }
            if(empty($email) && $_POST['user_id']==0) {
                 array_push($errors,['param' => 'email', 'msg' => 'Campo obbligatorio per i nuovi utenti']);
            }
            else
            {
            //FILTER_FLAG_EMAIL_UNICODE
                if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                     array_push($errors,['param' => 'email', 'msg' => 'Formato non valido per e-email']);
                }
            }
            if($datu["cell"]!=null && !preg_match('/3\d{2}[\. ]??\d{6,7}$/',$datu["cell"]))
                array_push($errors,['param' => 'cell', 'msg' => 'Formato non valido per il numero di telefono']);

           //if(!empty($cell)) {
           //     //if(!preg_match('/^3\d{9}$/',$cell)) {
           //      //array_push($errors,['param' => 'cell', 'msg' => 'Formato non valido per il numero di cellulare']);
           //  //}
           // }
            //$ruoli = isset($_POST['ruoli']) ? $_POST['ruoli'] : array();
            //if (!count($ruoli)) array_push($errors, ['param' => 'tuttiRuoli', 'msg' => 'Devi selezionare almeno un ruolo primario!']);
            //$rprim = isset($_POST['rprim']) ? $_POST['rprim'] : array();
            //if (!count($rprim))  array_push($errors, ['param' => 'tuttiRuoli', 'msg' => 'Devi selezionare almeno un ruolo primario!']);
            if(count($errors) > 0) {
                $output['errors'] = $errors;
            }
            else {
                if($datu['user_id']!=0)
                {
                    //AGGIORNAMENTO DATI UTENTE
                    $id= set_User($datu['user_id'],$datu,null);

                    //eliminare tutti i ruoli e ricreare quelli nuovi altrimenti
                    //bisogna fare confronto nuovi con vecchi e aggiornare
                    //solo ruolo 1 dell'admin non viene cancellato
                    delete_ruoli_utente($id);
                    $ruoli=json_decode($datu['ruoli']);

                    for($i=0;$i<count($ruoli);$i+=2)
                        set_ruolo_utente($id,$ruoli[$i], $ruoli[$i+1]);
                    //aggiornare la SESSION perchè ho modificato dati admin
                    if($datu['user_id']==1)
                    {
                        if(isset($datu['cell']))
                            $_SESSION['user']['cell']=$datu['cell'];
                        if(isset($datu['cod']))
                            $_SESSION['user']['cod']=$datu['cod'];
                        if(count($ruoli)!=0)
                        {
                            unset($_SESSION['user']['roles']);
                            $_SESSION['user']['roles']['1']['nome']= getRoleName(1);
                            for($i=0;$i<count($ruoli);$i+=2)
                            {
                                $_SESSION['user']['roles'][$ruoli[$i]]['nome']= getRoleName($ruoli[$i]);
                                if($ruoli[$i+1]!=0)
                                {
                                    $_SESSION['user']['roles'][$ruoli[$i]][$ruoli[$i+1]]=getRoleName($ruoli[$i+1]);
                                }
                            }
                        }
                    }
                    array_push($succ,"Aggiornato utente ID= ".$id);
                    //$_SESSION['message'] = "Aggiornato utente ID=".$id ;
                    unset($_SESSION['user_id']);
                    unset($_SESSION['editU']);
                    unset($_SESSION['isEditingUser']);
                    //unset($_POST['update_user']);
                }
                else{
                    //CREAZIONE NUOVO UTENTE
                //PROVARE A FARE TRANSAZIONE
                    $id= set_User(0,$datu,null);
                    $ruoli=json_decode($datu['ruoli']);
                    //$ruoli=$_POST['ruoli'];
                    for($i=0;$i<count($ruoli);$i+=2)
                        set_ruolo_utente($id,$ruoli[$i], $ruoli[$i+1]);
                    array_push($succ,"Creato utente ID= ".$id);
                }
                array_push($succ,$id);
                array_push($succ,$datu["cognome"][0]);
                $output['success'] = $succ;
                //unset($_POST['create_user']);
            }
    echo json_encode($output);

    //header('location: ../users.php');
    //exit(0);
    //}
