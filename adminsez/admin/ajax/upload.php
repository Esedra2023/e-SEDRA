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
if(!isset($_POST['page'])) forbidden();
require_once ROOT_PATH .'/include/functions.php';
require_once(ROOT_PATH . 'adminsez/admin/includes/utilfunctions.php');

if (isset($_POST['hidden_field']))
{
    $output = [];
    $errors = [];
    $total_line = '';

    if (isset($_FILES['fileToUpload']) && is_uploaded_file($_FILES['fileToUpload']['tmp_name']))
    {
        $upload_file_name = $_FILES['fileToUpload']['name'];
        //console_log($upload_file_name);
        //First, Validate the file name
        if(!empty($_FILES['fileToUpload']['name']))
        {
            //$errors[] = ['param' => 'fileToUpload', 'msg' => 'File non selezionato'];
            //console_log($errors);
            //exit();


            //Too long file name?
            if(strlen ($upload_file_name)<=100)
            {
                //$errors[] = ['param' => 'fileToUpload', 'msg' => 'Nome file troppo lungo'];
                //console_log($errors);
                //exit();

                //replace any non-alpha-numeric cracters in th file name

                $upload_file_name = preg_replace("/[^A-Za-z0-9 \.\-_]/", '', $upload_file_name);

                //verifico l'estensione del file
                $ext_ok = array('csv', 'txt');
                $temp = explode('.', $upload_file_name);
                $ext = end($temp);
                if (in_array($ext, $ext_ok)) {
                    //$errors[] = ['param' => 'fileToUpload', 'msg' => 'Estensione del file non ammessa'];
                    //console_log($errors);
                    //echo error('Il file ha un estensione non ammessa!');
                    //exit();

                // ----ESEMPIO  limito la dimensione massima a 4MB  ---verificare se necessario
                    if ($_FILES['fileToUpload']['size'] <= 4194304)
                    {
                        //$errors[] = ['param' => 'fileToUpload', 'msg' => 'File troppo grande'];
                        //console_log($errors);
                        //exit();


                        //Save the file
                        //ATTENZIONE MODIFICATA IN DOCUMENT ROOT
                       // $dest=ROOT_PATH .'/uploads/'.$upload_file_name;

                        $dest=$_SERVER['DOCUMENT_ROOT']."\\uploads\\".$upload_file_name;
                        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $dest))
                        {
                        //    echo 'File Has Been Uploaded !';
                        //}


                        //verifico di non sovrascrivere un file esistente -----PROBABILMENTE NON NECESSARIO
                        //$target_file = '/var/www/myupload/' . $_FILES['userfile']['name'];
                        //if (file_exists($target_file)) {
                        //    echo 'Il file esiste già';
                        //    exit();
                        //}



                        // recupero alcune informazioni sul file inviato
                        $userfile_tmp = $_FILES['fileToUpload']['tmp_name'];
                        $userfile_vero = $_FILES['fileToUpload']['name'];
                        $tipo_file = $_FILES['fileToUpload']['type'];
                        $_SESSION['csv_file_name'] = $dest; //$userfile_tmp;     //mettere $dest al posto di userfile...
                        $file_content = file($dest, FILE_SKIP_EMPTY_LINES);
                        $total_line = count($file_content);


                        //console_log($dest);
                        //echo $userfile_tmp . '  ' . $userfile_vero . '  ' . $tipo_file.'   ';
                        // leggo il contenuto del file
                        // $dati_file = file_get_contents($userfile_tmp);       // Funziona rimane da spacchettare ... avrà limite di dimensione!!!!

                        //Save the file
                        //if (isset($_POST["Irprim"])) {
                        //    $ruolo = $_POST['Irprim'];
                        //    //console_log($ruolo);

                        //    $mes=read_csv($userfile_tmp,';',$ruolo,$errors);    //mettere $dest al posto di userfile...
                        //    if($mes == 0)
                        //    {
                        //        $output['success']="Caricamento avvenuto con successo";
                        //    }
                        //}
                        //else
                        //    $errors[] = ['param' => 'RuoliPrim', 'msg' => 'Selezionare un ruolo primario'];
                        }
                        else
                        {
                            $errors[] = ['param' => 'fileToUpload', 'msg' => 'File non caricato'];
                            //console_log($errors);
                            //exit();
                        }
                    }
                    else
                    {
                        $errors[] = ['param' => 'fileToUpload', 'msg' => 'File troppo grande'];
                        //console_log($errors);
                        //exit();
                    }
                }else
                {
                    $errors[] = ['param' => 'fileToUpload', 'msg' => 'Estensione del file non ammessa'];
                }
            }
            else
            {
                $errors[] = ['param' => 'fileToUpload', 'msg' => 'Nome file troppo lungo'];
            }
        }
        else
        {
            $errors[] = ['param' => 'fileToUpload', 'msg' => 'File non selezionato'];
        }
    }
    else $errors[] = ['param' => 'fileToUpload', 'msg' => 'File inesistente'];
        //console_log($errors);
    if(count($errors) > 0) {
        $output = array(
			'error'		=>	$errors
		);
    }else
	{
		$output = array(
			'success'		=>	true,
			'total_line'	=>	($total_line - 1)
		);
	}

    echo json_encode($output);
    //header('location: ../users.php');
    //exit(0);

}
    //BARBARA 19/09/2022
?>





