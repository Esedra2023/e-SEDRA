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
    $bit = '';
}
else if($_SESSION['ini']['dbms'] == 'My SQL'){
    $autoinc = 'AUTO_INCREMENT';
    $bit = '(1)';
}

$sql =<<<SQL
    CREATE TABLE subRuoli (
    idSbRl INT $autoinc NOT NULL PRIMARY KEY,
    ruolo  INT NOT NULL,
    subRuolo INT NOT NULL,
    FOREIGN KEY (ruolo) REFERENCES ruoli(IdRl) ON DELETE CASCADE,
    FOREIGN KEY (subRuolo) REFERENCES ruoli(IdRl) ON DELETE NO ACTION
);
INSERT INTO subRuoli (ruolo, subruolo) VALUES
(2,7),(2,8),(2,9),(2,10),(3,8),(3,10),(4,8),(4,10),(5,7),(5,8),(5,10),(3,12),(2,11),(3,11),(4,11),(5,11);
SQL;
if($_POST['resume'] === '1') {  //SQL SERVER
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $resume = 'if exists(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_NAME = \'subRuoli\') DROP TABLE subRuoli;';
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $resume = 'DROP TABLE IF EXISTS subRuoli;';
    }
    $sql = $resume . $sql;
}

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{$conn->query( $sql );
} catch(PDOException $e) {echo error($e); exit();}
$conn = NULL;

echo "Table subRuoli creata...";
exit();
