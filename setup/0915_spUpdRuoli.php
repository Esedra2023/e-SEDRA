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
    CREATE PROCEDURE updRuoli
        @idRl INT,
        @idRs INT,
		@Ruolo VARCHAR(30)
    AS
    BEGIN
        SET NOCOUNT ON;
        DECLARE @ID INT;
        IF @idRl = 1 BEGIN
            INSERT INTO ruoli(ruolo, primario) VALUES(@Ruolo, 1);
        END
        ELSE IF @idRs = 1 BEGIN
            INSERT INTO ruoli(ruolo, primario) VALUES(@Ruolo, 0);
			SET @ID = @@IDENTITY;
            INSERT INTO subRuoli(ruolo, subRuolo) VALUES(@idRl, @ID);
        END
        ELSE IF @idRl > 1 AND @idRs > 1 BEGIN
            INSERT INTO subRuoli(ruolo, subRuolo) VALUES(@idRl, @idRs);
        END
		ELSE IF @Ruolo = '' BEGIN
            DELETE FROM subRuoli WHERE subRuoli.ruolo = @idRl OR subRuoli.subRuolo = @idRl;
            DELETE FROM ruoli WHERE ruoli.idRl = @idRl OR
                   ruoli.idRl NOT IN (SELECT subRuoli.subRuolo FROM subRuoli)
                   AND ruoli.primario = 0;
        END
    END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE updRuoli(idRl INT, idRs INT, Ruolo VARCHAR(30))
    BEGIN
        IF idRl = 1 THEN
            INSERT INTO ruoli(ruolo, primario) VALUES(Ruolo, 1);
        ELSEIF idRs = 1 THEN
            INSERT INTO ruoli(ruolo, primario) VALUES(Ruolo, 0);
			SET @ID:= LAST_INSERT_ID();
            INSERT INTO subRuoli(ruolo, subRuolo) VALUES(idRl, @ID);
        ELSEIF idRl > 1 AND idRs > 1 THEN
            INSERT INTO subRuoli(ruolo, subRuolo) VALUES(idRl, idRs);
		ELSEIF Ruolo = '' THEN
            DELETE FROM subRuoli WHERE subRuoli.ruolo = idRl OR subRuoli.subRuolo = idRl;
            DELETE FROM ruoli WHERE ruoli.idRl = idRl OR
                   ruoli.idRl NOT IN (SELECT subRuoli.subRuolo FROM subRuoli)
                   AND ruoli.primario = 0;
        END IF;
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

echo "Stored procedure updRuoli creata...";
exit();
