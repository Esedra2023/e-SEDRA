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
    CREATE PROCEDURE updValPro
        @idPro INT,
        @idUs INT,
		@val INT
    AS
    BEGIN
        SET NOCOUNT ON;
        DECLARE @id INT;

		SELECT @id=valPrp.idVp FROM valPrp WHERE valPrp.proposta=@idPro AND valPrp.utente=@idUs AND valPrp.dtIns >= (SELECT dtStart FROM attivita WHERE idAt=204) AND valPrp.dtIns <= (SELECT dtStop FROM attivita WHERE idAt=204);
		IF @id > 0 BEGIN
			 UPDATE valPrp SET valPrp.grade=@val, valPrp.dtIns=getdate() WHERE valPrp.idVp=@id;
		END
		ELSE BEGIN
			INSERT INTO valPrp(proposta, utente, grade) VALUES(@idPro, @idUs, @val);
		END
    END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
CREATE PROCEDURE updValPro(
    IN idPro INT,
    IN idUs INT,
    IN val INT
)
BEGIN
    DECLARE id INT DEFAULT 0;

    SELECT valPrp.idVb INTO id FROM valPrp
    WHERE valPrp.proposta=idPro AND valPrp.utente=idUs
    AND valPrp.dtIns >= (SELECT dtStart FROM attivita WHERE idAt = 104)
    AND valPrp.dtIns <= (SELECT dtStop FROM attivita WHERE idAt = 104)
    LIMIT 1;

    IF id > 0 THEN
        UPDATE valPrp SET valPrp.grade = val, valPrp.dtIns = NOW() WHERE valPrp.idVp = id;
    ELSE
        INSERT INTO valPrp(proposta, utente, grade, dtIns) VALUES(idPro, idUs, val, NOW());
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

echo "Stored procedure updValPro creata...";
exit();