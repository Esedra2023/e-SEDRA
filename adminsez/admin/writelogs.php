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
//require_once ROOT_PATH.'/include/functions.php';
//require_once (ROOT_PATH . '/adminsez/admin/includes/utilfunctions.php');
$_POST['notajax']=1;
require(ROOT_PATH . '/adminsez/admin/ajax/getlogdata.php');

$now=time();
//$path= $_SERVER['DOCUMENT_ROOT']."\\logs\\";
$filename="log".$now.".csv";
$fl = fopen($filename,"w");
//$export_data = unserialize($result);
if($fl!=null)
{
    $sep=';';   //separatore di elenco per i file csv è il ;
    $header=['Data Login','Data Logout','Cognome','Nome','Email','Ruoli'];
    fputcsv($fl,$header,$sep);
    foreach($result as $lg)
   {
       //$st=preparaRiga($lg);
        fputcsv($fl,$lg,$sep);
       // fwrite($fl,$st );  //"json_encode($logs)"
        //fwrite($fl,PHP_EOL );
   }
   fclose($fl);
}
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$filename);
header("Content-Type: application/csv; ");

readfile($filename);

// deleting file
unlink($filename);

//echo json_encode($filename);
exit();

//function preparaRiga($lg)
//{
//    $st=$lg['cognome']." ".$lg['nome'].",".$lg['ruolo'].",".$lg['email'].",".$lg['dtS'].",".$lg['dtE'].PHP_EOL;
//    return $st;
//}
