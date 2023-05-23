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
if(!isset($_POST['resume'])) forbidden();

if(!loadIniFile()){echo error('Impossibile caricare il file di configurazione'); exit();};
if($_SESSION['ini']['db_exist']) exit();

// CONNESSIONE AL DBMS ($conn) PER CREAZIONE DB
try{
    $conn = new PDO( $_SESSION['ini']['db_drv'], $_SESSION['ini']['db_user'], $_SESSION['ini']['db_psw'], [PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION]);
} catch(PDOException $e) {echo error($e); exit();}

//test esistenza DB
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
    $sql = "SELECT name FROM sys.databases where name='".$_SESSION['ini']['db_name']."';";
}
else if($_SESSION['ini']['dbms'] == 'My SQL'){
    $sql = "SELECT SCHEMA_NAME FROM information_schema.schemata WHERE SCHEMA_NAME='{$_SESSION['ini']['db_name']}';";
}
$res=$conn->query( $sql );
if($res->fetch()) {echo error('Un database con nome "' . $_SESSION['ini']['db_name']. '" e\' gia\' esistente!'); exit();}

//COSTRUZIONE SQL CREAZIONE DATABASE
if($_SESSION['ini']['dbms'] == 'SQL Server Express LocalDB')
{
    $path = __DIR__. '\\' . $_SESSION['ini']['db_name'] . '.mdf';
    $sql="CREATE DATABASE {$_SESSION['ini']['db_name']} ON (NAME = '{$_SESSION['ini']['db_name']}', FILENAME = '$path');";
    //$sql =<<<SQL
    //CREATE DATABASE {$_SESSION['ini']['db_name']}
    //ON (NAME = '{$_SESSION['ini']['db_name']}', FILENAME = '$path');
    //SQL;
}
else if($_SESSION['ini']['dbms'] == "SQL Server" || $_SESSION['ini']['dbms'] == "My SQL")
{
    //$sql =<<<SQL
    //CREATE DATABASE {$_SESSION['ini']['db_name']};
    //SQL;
    $sql="CREATE DATABASE {$_SESSION['ini']['db_name']};";
}
//else if($_SESSION['ini']['dbms'] == 'My SQL')
//{
//    $sql =<<<SQL
//    CREATE DATABASE {$_SESSION['ini']['db_name']};
//    SQL;
//}
/*----> INUTILE: se errori il DB non è stato creato
if($_POST['resume'] === '1') {  //SQL SERVER
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        // SOLO PER LIVELLO COMPATIBILITA' <= 120
        $resume = "if exists(SELECT name FROM sys.databases where name='{$_SESSION['ini']['db_name']}') DROP DATABASE {$_SESSION['ini']['db_name']};";
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $resume = "DROP DATABASE IF EXISTS {$_SESSION['ini']['dbms']};";
    }
    $sql = $resume . $sql;
}
*/

try{ $conn->query( $sql );
} catch(PDOException $e) {echo error($e); exit();}
$conn = NULL;

echo "Base dati creata...";
exit();

