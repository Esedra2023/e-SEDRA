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
      require_once ROOT_PATH.'/include/functions.php';if(!isset($_POST['resume'])) forbidden();

if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
 $sql =<<<SQL
    CREATE PROCEDURE reqToken
        @email VARCHAR(30),
		@token VARCHAR(10),
		@exp INT
    AS
    BEGIN
        SET NOCOUNT ON;
		DECLARE @idUs INT;
		DECLARE @num INT;
		SELECT @num=COUNT(*), @idUs=utenti.idUs FROM utenti
		WHERE utenti.email=@email GROUP BY utenti.idUs;
        DELETE FROM token WHERE token.dtExp < GETDATE() OR token.utente = @idUs;
        IF @num > 0 BEGIN
			IF @exp = 0 BEGIN SET @exp = NULL; END
			INSERT INTO token(utente, token, dtExp)
			VALUES (@idUs, @token, DATEADD(hour, @exp, GETDATE()));
		END
		SELECT @num;
    END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE reqToken(email VARCHAR(30), token VARCHAR(10), exp INT)
    BEGIN
		SELECT @num:=COUNT(*), @idUs:=utenti.idUs FROM utenti
		WHERE utenti.email=email;
        DELETE FROM token WHERE token.dtExp < NOW() OR token.utente=@idUs;
        IF @num > 0 THEN
            IF exp = 0 THEN SET exp = NULL; END IF;
            INSERT INTO token(token.utente, token.token, token.dtExp)
            VALUES (@idUs, token, DATE_ADD(NOW(), INTERVAL exp HOUR));
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

echo "Stored procedure reqToken creata...";
exit();
