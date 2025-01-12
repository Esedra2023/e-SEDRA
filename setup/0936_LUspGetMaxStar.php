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
    CREATE PROCEDURE getMaxStar
        @tipo INT
    AS
    BEGIN
        IF @tipo = 104
        BEGIN
            SELECT numberOfStars.idStar, numberOfStars.vbis AS maxStar
            FROM numberOfStars;
        END
        ELSE BEGIN
            SELECT numberOfStars.idStar, numberOfStars.vpro AS maxstar
            FROM numberOfStars;
        END
    END
SQL;
      } else if($_SESSION['ini']['dbms'] == 'My SQL'){
          $sql =<<<SQL
    CREATE PROCEDURE getMaxStar(tipo INT)
    BEGIN
        IF tipo = 104 THEN
            SELECT numberOfStars.idStar, numberOfStars.vbis AS maxStar
            FROM numberOfStars;
        ELSE
            SELECT numberOfStars.idStar, numberOfStars.vpro AS maxStar
            FROM numberOfStars;
        END IF;
	END
SQL;
      }

      if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
      try{
          $conn->query( $sql );
      } catch(PDOException $e) {echo error($e); exit();}
      $conn = NULL;

      echo "Stored procedure GetMaxStar creata...";
      exit();