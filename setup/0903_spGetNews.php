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
    CREATE PROCEDURE getNews
    @idUs INT,
    @iNw INT
    AS
    BEGIN
        SET NOCOUNT ON;
        IF  @idUs = 0   BEGIN
            SELECT news.idNw, utenti.nome, utenti.cognome, news.title,
                   news.text, news.dtNews, news.topublish, news.dtEnd, CONVERT(VARCHAR(17), news.dtNews,113) AS sdtNews
            FROM utenti INNER JOIN news ON utenti.idUs = news.utente
            ORDER BY news.dtNews DESC;
        END
        ELSE IF @iNw = 0 BEGIN
            SELECT news.idNw, utenti.nome, utenti.cognome, news.title,
                   news.text, news.dtNews, news.topublish, news.dtEnd, news.dtEnd, CONVERT(VARCHAR(17), news.dtNews,113) AS sdtNews
            FROM utenti INNER JOIN news ON utenti.idUs = news.utente
            WHERE news.utente=@idUs
            ORDER BY news.dtNews DESC;
        END
        ELSE BEGIN
            SELECT news.idNw, utenti.nome, utenti.cognome, news.title,
                   news.text, news.dtNews, news.topublish, news.dtEnd, news.dtEnd, CONVERT(VARCHAR(17), news.dtNews,113) AS sdtNews
            FROM utenti INNER JOIN news ON utenti.idUs = news.utente
            WHERE news.idNw=@iNw;
        END
    END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE getNews(iUs INT, iNw INT)
    BEGIN
        IF iUs = 0 THEN
            SELECT news.idNw, utenti.nome, utenti.cognome, news.title,
                   news.text, news.dtNews, news.dtEnd, news.topublish, DATE_FORMAT( news.dtNews,'%d-%m-%Y %T') AS sdtNews
            FROM utenti INNER JOIN news ON utenti.idUs = news.utente
            ORDER BY news.dtNews DESC;
        ELSE IF iNw = 0 THEN
            SELECT news.idNw, utenti.nome, utenti.cognome, news.title,
                   news.text, news.dtNews, news.topublish, news.dtEnd, DATE_FORMAT( news.dtNews,'%d-%m-%Y %T') AS sdtNews
            FROM utenti INNER JOIN news ON utenti.idUs = news.utente
            WHERE news.utente=iUs
            ORDER BY news.dtNews DESC;
        ELSE
            SELECT news.idNw, utenti.nome, utenti.cognome, news.title,
                   news.text, news.dtNews, news.topublish, news.dtEnd, DATE_FORMAT( news.dtNews,'%d-%m-%Y %T') AS sdtNews
            FROM utenti INNER JOIN news ON utenti.idUs = news.utente
            WHERE news.idNw=iNw;
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

echo "Stored procedure getNews creata...";
exit();
