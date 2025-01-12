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
}
else if($_SESSION['ini']['dbms'] == 'My SQL'){
    $autoinc = 'AUTO_INCREMENT';
    $dtNow = 'DEFAULT 0';
}

$sql =<<<SQL
    CREATE TABLE bisogni (
    idBs INT $autoinc NOT NULL PRIMARY KEY,
    ambito INT,
    moreambito VARCHAR(30),
    dtIns   DATETIME $dtNow  NOT NULL,
    utente INT NOT NULL,
	titleBis VARCHAR(60) NOT NULL,
    textBis VARCHAR(1024),
    imgBis VARCHAR(50),
	deleted INT NOT NULL DEFAULT 0,
    ingrad INT NOT NULL DEFAULT 0,
    pubblicato TINYINT NOT NULL,
	aggiornato DATETIME,
    dtRev DATETIME,
    rev VARCHAR(60),
    revisore INT,
    FOREIGN KEY (utente) REFERENCES utenti(idUs) ON DELETE CASCADE,
    FOREIGN KEY (ambito) REFERENCES ambiti(idAm) ON DELETE SET NULL,
    FOREIGN KEY (revisore) REFERENCES utenti(idUs) ON DELETE NO ACTION
);
SQL;
if($_POST['resume'] === '1') {  //SQL SERVER
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $resume = 'if exists(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_NAME = \'bisogni\') DROP TABLE bisogni;';
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $resume = 'DROP TABLE IF EXISTS bisogni;';
    }
    $sql = $resume . $sql;
}

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{$conn->query( $sql );
} catch(PDOException $e) {echo error($e); exit();}
$conn = NULL;

echo "Table bisogni creata...";
exit();
