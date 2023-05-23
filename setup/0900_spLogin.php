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
//NB PRECEDENTE: WHERE ruoli.primario=1 AND utenti.email=email
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
 $sql =<<<SQL
    CREATE PROCEDURE Login
        @email VARCHAR(30),
		@scPsw INT
    AS
    BEGIN
        SET NOCOUNT ON;
        DECLARE @dtStart DATETIME;
		SET @dtStart = GETDATE();

		IF @scPsw > 0
		BEGIN
			SELECT utenti.idUs, @dtStart AS dtStart, DATEDIFF(day, @dtStart, DATEADD(month, @scPsw, utenti.dtPsw)) AS ggScPsw,
			utenti.email, utenti.psw
			FROM utenti WHERE utenti.email=@email;
        END
		ELSE
		BEGIN
			SELECT utenti.idUs, @dtStart AS dtStart,
			utenti.email, utenti.psw
			FROM utenti WHERE utenti.email=@email;
		END

		IF @@Rowcount > 0 BEGIN
			select t.email, t.idUs, t.nome, t.cognome, t.cell, t.cod, t.menuAct, t.psw, t.dtPsw, t.ruolo, t.ruoloNU, ruoli.ruolo as subruolo, t.subruoloNU
            FROM (SELECT utenti.idUs, utenti.nome, utenti.cognome, utenti.email, utenti.cell, utenti.cod, utenti.menuAct, utenti.psw, utenti.dtPsw, ruoli.ruolo, ruoliutenti.ruolo as ruoloNU, ruoliutenti.subruolo as subruoloNU
            FROM UTENTI left JOIN (
                RUOLIUTENTI inner join ruoli on ruoliutenti.ruolo=ruoli.idRl )  ON utenti.idUs=ruoliutenti.utente) as t left join  ruoli on t.subruoloNU=ruoli.idRl
            WHERE t.email = @email
            ORDER BY t.ruolo ASC, subruolo ASC;
		END
    END
SQL;
}
else if($_SESSION['ini']['dbms'] == 'My SQL'){ //NB PRECEDENTE: WHERE ruoli.primario=1 AND utenti.email=email
$sql =<<<SQL
    CREATE PROCEDURE Login(email VARCHAR(30), scPsw INT)
    BEGIN
        SET @dtStart = NOW();

        IF scPsw > 0 THEN
			SELECT @num:=COUNT(*) AS X, utenti.idUs, @dtStart AS dtStart,
			DATEDIFF(DATE_ADD(utenti.dtPsw, INTERVAL scPsw MONTH), @dtStart) AS ggScPsw,
			utenti.email, utenti.psw
			FROM utenti WHERE utenti.email=email;
		ELSE
 			SELECT @num:=COUNT(*) AS X, utenti.idUs, @dtStart AS dtStart,
			utenti.email, utenti.psw
			FROM utenti WHERE utenti.email=email;
        END IF;

		IF @num > 0 THEN
	        CREATE OR REPLACE view t as SELECT utenti.idUs, utenti.nome, utenti.cognome, utenti.email, utenti.cell, utenti.cod, utenti.menuAct, utenti.psw, utenti.dtPsw, ruoli.ruolo, ruoliUtenti.ruolo as ruoloNU, ruoliUtenti.subruolo as subruoloNU
        FROM utenti left JOIN ( ruoliUtenti INNER JOIN ruoli on ruoliUtenti.ruolo=ruoli.idRl )  ON utenti.idUs=ruoliUtenti.utente;
        SELECT t.email, t.idUs, t.nome, t.cognome, t.cell, t.cod, t.menuAct, t.psw, t.dtPsw, t.ruolo, t.ruoloNU, ruoli.ruolo as subruolo, t.subruoloNU
            FROM (t left JOIN  ruoli on t.subruoloNU=ruoli.idRl)
            WHERE t.email=email
            ORDER BY t.ruolo ASC, subruolo ASC;
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

echo "Stored procedure Login creata...";
exit();
