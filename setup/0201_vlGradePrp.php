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
    $round = 'CAST(AVG(valPrp.grade) AS NUMERIC(12,2))';
}
else if($_SESSION['ini']['dbms'] == 'My SQL'){
   $round = 'ROUND(AVG(valPrp.grade), 2)';
}

$sql =<<<SQL
    CREATE VIEW vGradePrp
    AS
    SELECT valPrp.proposta, COUNT(valPrp.proposta) AS cntPrp,
           $round  AS avgPrp, SUM(valPrp.grade) AS sumPrp
    FROM valPrp
    GROUP BY valPrp.proposta;
SQL;

if($_POST['resume'] === '1') {  //SQL SERVER
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $resume = 'if exists(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_NAME = \'vGradePrp\') DROP VIEW vGradePrp;';
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $resume = 'DROP VIEW IF EXISTS vGradePrp;';
    }
    $sql = $resume . $sql;
}

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{$conn->query( $sql );
} catch(PDOException $e) {echo error($e); exit();}
$conn = NULL;

echo "Vista vGradePrp creata...";
exit();
