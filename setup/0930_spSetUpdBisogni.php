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
    CREATE PROCEDURE setBisogni
        @vid INT,
        @topicid INT,
        @more VARCHAR(30),
        @title VARCHAR(60),
		@image VARCHAR(50),
        @body VARCHAR(1024),
        @publish TINYINT,
        @utente INT,
        @crud char(1)
    AS
        BEGIN
                SET NOCOUNT ON;
                IF @crud = 'C' BEGIN
                    INSERT INTO bisogni (utente, ambito, moreambito, titleBis,  imgBis, textBis, pubblicato, dtIns, aggiornato)
                    VALUES(@utente, @topicid, @more, @title,@image, @body, @publish, getdate(), getdate());
                END
                ELSE IF @crud = 'U' BEGIN
                    UPDATE bisogni SET titleBis=@title, ambito=@topicid, moreambito=@more, imgBis=@image, textBis=@body, aggiornato=getdate()
		            WHERE idBs=@vid;
                END
        END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE setBisogni(vid INT, topicid INT, more VARCHAR(30), title VARCHAR(60),image VARCHAR(50),body VARCHAR(1024),publish TINYINT,utente INT, crud CHAR(1))
    BEGIN
         IF crud = 'C' THEN
            INSERT INTO bisogni (utente, ambito, moreambito, titleBis,  imgBis, textBis, pubblicato, dtIns, aggiornato)
                    VALUES(utente, topicid, more, title,image, body, publish, now(), now());
        ELSE IF crud = 'U' THEN
            UPDATE bisogni SET titleBis=title, ambito= topicid, moreambito=more, imgBis=image, textBis=body, aggiornato=now()
            WHERE idBs=vid;
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

echo "Stored procedure setBisogni creata...";
exit();
