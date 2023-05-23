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
 $sql =<<<SQL
    CREATE PROCEDURE getLogData
    AS
        BEGIN
                SET NOCOUNT ON;
               select logs.idLg,  FORMAT(logs.dtStart, 'dd/MM/yyyy hh:mm') AS dtS, FORMAT(logs.dtEnd, 'dd/MM/yyyy hh:mm') AS dtE,
                ut.cognome, ut.nome, ut.email, ut.ruolo, ut.subruolo
                FROM logs, (select t.email, t.idUs, t.nome, t.cognome, t.ruolo, ruoli.ruolo as subruolo
                from (SELECT utenti.idUs, utenti.nome, utenti.cognome, utenti.email, ruoli.ruolo, ruoliUtenti.subruolo as subruolo
                FROM utenti left JOIN (
                ruoliUtenti inner join ruoli on ruoliUtenti.ruolo=ruoli.idRl )  ON utenti.idUs=ruoliUtenti.utente) as t left join  ruoli on t.subruolo=ruoli.idRl
                ) as ut
                WHERE logs.utente=ut.idUs;
        END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE getLogData()
    BEGIN
       select logs.idLg,  DATE_FORMAT(logs.dtStart, '%d/%m/%Y %T') AS dtS, DATE_FORMAT(logs.dtEnd, '%d/%m/%Y %T') AS dtE,
        ut.cognome, ut.nome, ut.email, ut.ruolo, ut.subruolo
        FROM logs, (select t.email, t.idUs, t.nome, t.cognome, t.ruolo, ruoli.ruolo as subruolo
        from (SELECT utenti.idUs, utenti.nome, utenti.cognome, utenti.email, ruoli.ruolo, ruoliUtenti.subruolo as subruolo
        FROM utenti left JOIN (
            ruoliUtenti inner join ruoli on ruoliUtenti.ruolo=ruoli.idRl )  ON utenti.idUs=ruoliUtenti.utente) as t left join  ruoli on t.subruolo=ruoli.idRl) as ut
        WHERE logs.utente=ut.idUs;
    END
SQL;
}

/*
if($_POST['resume'] === '1') {
    $resume='DROP PROCEDURE IF EXISTS spLogin;';
    $sql = $resume . $sql;
}
//echo error($sql); exit();
*/
if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{$conn->query( $sql );
} catch(PDOException $e) {echo error($e); exit();}
$conn = NULL;

echo "Stored procedure getLogData creata ...";
exit();
