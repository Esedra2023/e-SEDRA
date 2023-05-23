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
require_once ROOT_PATH.'/include/functions.php';
if(!isset($_POST['err'])) forbidden();

// ---------------- modifica file INI logerrors.txt.php
date_default_timezone_set('Europe/Amsterdam');
$dt = date('d/m/Y H:i');
$fileContent = <<<ERR

<=========================================>
[$dt]
setupCount = {$_POST['num']}
file = {$_POST['file']}
error = "{$_POST['err']}"
ERR;
$f = fopen(ROOT_PATH  . '//data//logerrors.txt.php', "a");
if($f && fwrite($f, $fileContent)) fclose($f);
else {echo error('Impossibile registrare il log dell\'errore'); exit();}

echo "Log errore creato";
exit();
