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
    CREATE PROCEDURE setProposte
        @pid INT,
        @utente INT,
        @propo VARCHAR(40),
        @mail VARCHAR(30),
        @tel VARCHAR(20),
        @title VARCHAR(60),
		@body VARCHAR(2048),
        @pdfnome VARCHAR(40),
        @pdfall VARCHAR(20),
        @bis VARCHAR(30),
        @publish TINYINT,
        @crud char(1)

    AS
        BEGIN
                SET NOCOUNT ON;
                IF @crud = 'C' BEGIN
                    INSERT INTO proposte (utente, proponente, email, tel,titlePrp, textPrp, pdfalleg, pdforigname, rifbisgenerico, pubblicato, dtIns, aggiornato)
                    VALUES(@utente, @propo, @mail, @tel, @title,@body, @pdfall, @pdfnome,@bis, @publish, getdate(), getdate());
                    SELECT @@IDENTITY; -- recupero l'ID
                END
                ELSE IF @crud = 'U' BEGIN
                    UPDATE proposte SET titlePrp=@title, email=@mail, tel=@tel, rifbisgenerico=@bis, textPrp=@body, aggiornato=getdate()
		            WHERE idPr=@pid;
                END

        END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE setProposte(pid INT, ute INT, propo VARCHAR(40), mail VARCHAR(30), cel VARCHAR(20), title VARCHAR(60),body VARCHAR(2048), pdfall VARCHAR(20), pdfnome VARCHAR(40), bis VARCHAR(30), publish TINYINT, crud CHAR(1))
    BEGIN
        DECLARE _lastid INT;
        IF crud = 'U' THEN
            UPDATE proposte SET titlePrp=title, email=mail, tel=cel, rifbisgenerico=bis, textPrp=body, aggiornato=DATE(CURRENT_TIMESTAMP)
            WHERE idPr=@pid;
         ELSE IF crud = 'C' THEN
            INSERT INTO proposte (utente, proponente, email, tel,titlePrp, textPrp, pdfalleg, pdforigname,rifbisgenerico, pubblicato, dtIns, aggiornato)
                    VALUES(ute, propo, mail, cel, title,body, pdfall, pdfnome, bis, publish, DATE(CURRENT_TIMESTAMP), DATE(CURRENT_TIMESTAMP));
           SELECT LAST_INSERT_ID() INTO _lastid;
        END IF;
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

echo "Stored procedure setProposte creata...";
exit();
