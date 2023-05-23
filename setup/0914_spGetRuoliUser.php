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
// RESTITUISCE ELENCO DI TUTTI I RUOLI ESISTENTI ORDINATI PER PRIMARIO-SECONDARIO E NOME
// CON COLONNA ENB VALORIZZATA PER I RUOLI POSSIBILI E COLONNA CHK VALORIZZATA PER I RUOLI ATTUALI
// DI UN DETERMINATO UTENTE PASSATO COME PARAMETRO

if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
 $sql =<<<SQL
    CREATE PROCEDURE getRuoliUser
        @idUs INT
    AS
    BEGIN
        SET NOCOUNT ON;
        SELECT DISTINCT  ruoli.idRl, ruoli.ruolo, ruoli.primario, T.ENB, ruoliUtenti.ruolo AS CHK
        FROM ruoliUtenti RIGHT JOIN
	        (ruoli LEFT JOIN
			        (SELECT subRuoli.subRuolo AS ENB
			        FROM ruoliUtenti INNER JOIN subRuoli ON ruoliUtenti.ruolo=subRuoli.ruolo
			        WHERE ruoliUtenti.utente=@idUs) AS T
		        ON ruoli.idRl = T.ENB)
	        ON ruoli.idRl=ruoliUtenti.ruolo AND ruoliUtenti.utente=@idUs
        WHERE ruoli.idRl > 1
        ORDER BY ruoli.primario DESC, ruoli.ruolo;
    END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE getRuoliUser(idUs INT)
    BEGIN
        SELECT DISTINCT  ruoli.idRl, ruoli.ruolo, ruoli.primario, T.ENB, ruoliUtenti.ruolo AS CHK
        FROM ruoliUtenti RIGHT JOIN
	        (ruoli LEFT JOIN
			        (SELECT subRuoli.subRuolo AS ENB
			        FROM ruoliUtenti INNER JOIN subRuoli ON ruoliUtenti.ruolo=subRuoli.ruolo
			        WHERE ruoliUtenti.utente=idUs) AS T
		        ON ruoli.idRl = T.ENB)
	        ON ruoli.idRl=ruoliUtenti.ruolo AND ruoliUtenti.utente=idUs
        WHERE ruoli.idRl > 1
        ORDER BY ruoli.primario DESC, ruoli.ruolo;
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

echo "Stored procedure getRuoliUser creata...";
exit();
