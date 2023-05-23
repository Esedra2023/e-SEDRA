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
{
    define ('ROOT_PATH',$_POST['SubDROOT']);
    $_SESSION['ini']['ROOT_PATH']=ROOT_PATH;
}
require_once (ROOT_PATH.'/include/functions.php');
if(!isset($_POST['resume'])) forbidden();
// ---------------- creazione file INI
$dbms = $_POST['DBMS'];
$db_name = $_POST['DB'];
// COSTRUZIONE DSN e PARAMETRI CONNESSIONE DRIVER PDO
if($dbms == 'SQL Server Express LocalDB')
{
    $db_drv ="sqlsrv:Server=(LocalDB)\MSSQLLocalDB;";
    $db_str = "AttachDbFilename=" . __DIR__ . "\\" . $_POST['DB'] . '.mdf';
    $_POST['USN']= NULL;
    $_POST['PSW']= NULL;
}
else if($dbms == 'SQL Server')
{
    $db_drv ='sqlsrv:Server='. $_POST['HOST'] .';';
    $db_str = 'Database='. $_POST['DB'];
}
else if($dbms == 'My SQL')
{
    $db_drv = 'mysql:host=' . $_POST['HOST'] . ';';
    $db_str = 'dbname='. $_POST['DB'];
}

// CREAZIONE FILE INI (data\config.ini.php)
$fileContent = <<<INI

[root]
;cartella di installazione
ROOT_PATH="{$_POST['SubDROOT']}"
;configurazione

posta="{$_POST['MAIL']}"
social="{$_POST['SOCIAL']}"
web="{$_POST['WEB']}"

[connection]
;parametri di connessione al DB
dbms="$dbms"
db_drv="$db_drv"
db_str="$db_str"
db_name="$db_name"
db_exist={$_POST['DBEX']}
db_user="{$_POST['USN']}"
db_psw="{$_POST['PSW']}"


INI;

$f = fopen(ROOT_PATH.'/data/config.ini.php', "w");
if($f && fwrite($f, $fileContent)) fclose($f);
else {echo json_encode(error('Impossibile creare il file di configurazione')); exit();}

$f = fopen(ROOT_PATH.'/data/logerrors.txt.php', "w");
if($f && fwrite($f, ";<?php die(); ?>")) fclose($f);
else {echo json_encode(error('Impossibile creare il file di log')); exit();}

// ---------------- Restituisce vettore con elenco file php creazione DB
$files = glob(ROOT_PATH.'/setup/0*.php');
foreach($files as $key => $file) $files[$key] = 'setup/'.basename($file);
echo json_encode($files);
///////////////////////////////////////////////////
exit();
