
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

    $datu=$_POST;
    $mod=0;
    if(isset($datu['cell'])) {$datu['cell']=trim($datu['cell']); $mod=1; $_SESSION['user']['cell']=$datu['cell'];}
    if(isset($datu['cod'])) {$datu['cod']=trim($datu['cod']); $mod=1;$_SESSION['user']['cod']=$datu['cod'];}

        //AGGIORNAMENTO DATI UTENTE
   if($mod==1)
   {
         $id= set_User($datu['idUs'],$datu,null);
         echo json_encode($id);
   }
   else echo json_encode($mod);

   exit(0);

