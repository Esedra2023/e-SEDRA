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
//    $sql = "INSERT INTO ruoli(ruolo, primario) VALUES({$_POST['val']}, 1);";

if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
 $sql =<<<SQL
    CREATE PROCEDURE updValBis
        @idBis INT,
        @idUs INT,
		@val INT
    AS
    BEGIN
        SET NOCOUNT ON;
        DECLARE @id INT;

		SELECT @id=valBis.idVb FROM valBis WHERE valBis.bisogno=@idBis AND valBis.utente=@idUs AND valBis.dtIns >= (SELECT dtStart FROM attivita WHERE idAt=104) AND valBis.dtIns <= (SELECT dtStop FROM attivita WHERE idAt=104);
		IF @id > 0 BEGIN
			 UPDATE valBis SET valBis.grade=@val, valBis.dtIns=getdate() WHERE valBis.idVb=@id;
		END
		ELSE BEGIN
			INSERT INTO valBis(bisogno, utente, grade) VALUES(@idBis, @idUs, @val);
		END
    END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE updValBis(idBis INT, idUs INT, val INT)
    BEGIN
		SELECT @id=valBis.idVb FROM valBis WHERE valBis.bisogno=idBis AND valBis.utente=idUs AND valBis.dtIns >= (SELECT dtStart FROM attivita WHERE idAt=104) AND valBis.dtIns <= (SELECT dtStop FROM attivita WHERE idAt=104);
		IF @id > 0 THEN
			 UPDATE valBis SET valBis.grade=val, valBis.dtIns=DATE(CURRENT_TIMESTAMP) WHERE valBis.idVb=@id;
		ELSE
			INSERT INTO valBis(bisogno, utente, grade, dtIns) VALUES(idBis, idUs, val, DATE(CURRENT_TIMESTAMP));
		END IF;
    END
SQL;
}

/*	tolto non serve più	calcolare il totale qui.
 * SELECT vGradeBis.cntBis, vGradeBis.avgBis, vGradeBis.sumBis FROM vGradeBis
WHERE vGradeBis.bisogno = @idBis;


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

echo "Stored procedure updValBis creata...";
exit();