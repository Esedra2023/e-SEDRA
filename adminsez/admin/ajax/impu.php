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
require_once(ROOT_PATH  . '/adminsez/admin/includes/utilfunctions.php');
//header('Content-Type: application/json');
    //console_log($_FILES);

//if (isset($_POST['importUserList']))
//{
    $output = [];
    $errors = [];

     if (!isset($_POST["Irprim"])) {
         $output['Ruolo']= 'Ruolo non selezionato!';
     }
     else
     {
         $ruolo = $_POST['Irprim'];
         if (isset($_FILES['fileup']) && is_uploaded_file($_FILES['fileup']['tmp_name']))
            {
                $upload_file_name = $_FILES['fileup']['name'];
                if($upload_file_name!="" && strlen ($upload_file_name)<=100)
                {
                    //replace any non-alpha-numeric cracters in th file name
            //$upload_file_name = preg_replace("/[^A-Za-z0-9 \.\-_]/", '', $upload_file_name);
                    //Save the file
                    //$dest=__DIR__.'/upload/'.$upload_file_name;
                    //if (!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $dest))

                    // recupero alcune informazioni sul file inviato
               $userfile_tmp = $_FILES['fileup']['tmp_name'];
                    //$userfile_vero = $_FILES['fileup']['name'];
                    //$tipo_file = $_FILES['fileup']['type'];

                    $righe=read_csv($userfile_tmp,';',$ruolo,$errors);    //mettere $dest al posto di userfile...
                    if($righe != 0)
                    {
                        $output['success']="Importati ".$righe." utenti.";
                    }
                    //else
                    //{
                    //    array_push($errors,'Mail duplicate!');
                    //}
                }
                else
                {
                    array_push($errors,'Nome file troppo lungo');
                }
            }
            else
            {
                array_push($errors,'Nessun file selezionato');
            }
     }
    if(count($errors) > 0) {
        $output['errors'] = $errors;
    }

    echo json_encode($output);
    //exit(0);



