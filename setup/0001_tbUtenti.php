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

if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
    $autoinc = 'IDENTITY(1,1)';
    $dtNow = 'DEFAULT getdate()';
    $dtInsert = 'getdate()';
}
else if($_SESSION['ini']['dbms'] == 'My SQL'){
    $autoinc = 'AUTO_INCREMENT';
    $dtNow = 'DEFAULT 0';
    $dtInsert= 'now()';     //DATE(CURRENT_TIMESTAMP);
}
$pswAdm = password_hash($_POST['PSWAD'], PASSWORD_DEFAULT);

$sql =<<<SQL
CREATE TABLE utenti (
    idUs    INT $autoinc NOT NULL PRIMARY KEY,
    nome    VARCHAR(30)  NOT NULL,
    cognome VARCHAR(30)  NOT NULL,
    email   VARCHAR(30)  NOT NULL UNIQUE,
    psw     VARCHAR(255) NOT NULL,
    dtPsw   DATE $dtNow  NOT NULL,
    cell    VARCHAR(20),
    cod     VARCHAR(20),
    menuAct TINYINT DEFAULT 0
);
INSERT INTO utenti (nome, cognome, email, psw, dtPsw) VALUES
    (' ', 'Admin', '{$_POST['MAILAD']}', '$pswAdm', $dtInsert);
SQL;


if($_POST['resume'] === '1') {  //SQL SERVER
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $resume = 'if exists(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_NAME = \'utenti\') DROP TABLE utenti;';
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $resume = 'DROP TABLE IF EXISTS utenti;';
    }
    $sql = $resume . $sql;
}

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{$conn->query( $sql );
} catch(PDOException $e) {echo error($e); exit();}
$conn = NULL;

echo "Table utenti creata...";
exit();
