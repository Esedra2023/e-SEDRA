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
    CREATE PROCEDURE insNews
        @crud CHAR,
        @id INT,
		@title VARCHAR(40),
		@text VARCHAR(1024),
        @dtEnd DATETIME,
        @dtExp INT,
        @topublish TINYINT
    AS
    BEGIN
        SET NOCOUNT ON;
        IF @dtExp > 0 BEGIN
            DELETE FROM news
            WHERE DATEADD(week, @dtExp, news.dtEnd) < GETDATE();
        END
        IF @crud = 'C' BEGIN
            INSERT INTO news(utente, title, text, dtEnd,topublish)
            VALUES (@id, @title, @text, @dtEnd,@topublish);
        END
        ELSE IF @crud = 'U' BEGIN
            UPDATE news SET title=@title, text=@text, dtEnd=@dtEnd WHERE idNw=@id;
        END
    END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE insNews(crud CHAR, id INT, title VARCHAR(40), text VARCHAR(1024), dtEnd DATE, dtExp INT, top TINYINT)
    BEGIN
        IF dtExp > 0 THEN
            DELETE FROM news
            WHERE DATE_ADD(news.dtEnd, INTERVAL dtExp WEEK) < NOW();
        END IF;
        IF crud = 'C' THEN
            INSERT INTO news(utente, title, text, dtNews, dtEnd, topublish)
            VALUES (id, title, text, NOW(), dtEnd, top);
        ELSE IF crud = 'U' THEN
            UPDATE news SET news.title=title, news.text=text, news.dtEnd=dtEnd  WHERE idNw=id;
        END IF;
    END IF;
    END;
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

echo "Stored procedure insNews creata...";
exit();
