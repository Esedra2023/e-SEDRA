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
    CREATE PROCEDURE revBisogni
        @vid INT,
        @topicid INT,
        @more VARCHAR(30),
        @title VARCHAR(60),
        @body VARCHAR(1024),
        @image VARCHAR(50),
        @publish TINYINT,
        @revisor INT,
        @note VARCHAR(60),
        @crud CHAR(1)
    AS
        BEGIN
                SET NOCOUNT ON;
                IF @crud = 'D' BEGIN
                    UPDATE bisogni SET deleted=1, revisore=@revisor, pubblicato=0 WHERE idBs=@vid;
                END
                ELSE IF @crud = 'R' BEGIN
                    UPDATE bisogni SET ambito=@topicid, moreambito=@more, titleBis=@title, textBis=@body, imgBis=@image, dtRev=getdate(), pubblicato=@publish, rev=@note, revisore=@revisor
		            WHERE idBs=@vid;
                END
        END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE revBisogni(vid INT, topicid INT, more VARCHAR(30), title VARCHAR(60),image VARCHAR(50),body VARCHAR(1024), publish TINYINT,revisor INT, note VARCHAR(60),crud CHAR(1))
    BEGIN
        IF crud = 'D' THEN
            UPDATE bisogni SET deleted=1, revisore=revisor, pubblicato=0 WHERE idBs=vid;
        ELSE IF crud = 'R' THEN
            UPDATE bisogni SET ambito=topicid, moreambito=more, titleBis=title, textBis=body, imgBis=image, dtRev=DATE(CURRENT_TIMESTAMP), pubblicato=publish, rev=note, revisore=revisor
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

echo "Stored procedure revBisogni creata ...";
exit();
