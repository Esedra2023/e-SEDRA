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
    CREATE PROCEDURE setDataUser
        @vid INT,
        @nome VARCHAR(30),
		@cogn VARCHAR(30),
        @email VARCHAR(30),
        @psw VARCHAR(255),
        @cell VARCHAR(20),
        @cod VARCHAR(20)
    AS
        BEGIN
                SET NOCOUNT ON;
                IF @vid = 0 BEGIN
                    INSERT INTO utenti (nome, cognome, email, psw, cell, cod)
		            VALUES(@nome, @cogn, @email, @psw, @cell, @cod);
                END
                ELSE BEGIN
                    if @vid = 1 BEGIN
                        UPDATE utenti SET cell=@cell, cod=@cod
		                WHERE idUs=@vid;
                    END
                    ELSE BEGIN
                        UPDATE utenti SET nome=@nome, cognome=@cogn, cell=@cell, cod=@cod
		                WHERE idUs=@vid;
                    END
                END
        END
SQL;
} else if($_SESSION['ini']['dbms'] == 'My SQL'){
$sql =<<<SQL
    CREATE PROCEDURE setDataUser(vid INT, nomen VARCHAR(30), cognn VARCHAR(30), emailn VARCHAR(30), pswn VARCHAR(255), celln VARCHAR(20), codn VARCHAR(20))
    BEGIN
         IF vid = 0 THEN
           INSERT INTO utenti(nome, cognome, email, psw, cell, cod, dtPsw)
		        VALUES(nomen, cognn, emailn, pswn, celln, codn, DATE(CURRENT_TIMESTAMP));
        ELSE
            IF vid = 1 THEN
                UPDATE utenti SET cell=celln, cod=codn
		                WHERE idUs=vid;
            ELSE
                UPDATE utenti SET nome=nomen, cognome=cognn, cell=celln, cod=codn
		                WHERE idUs=vid;
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

echo "Stored procedure setDataUser creata...";
exit();
