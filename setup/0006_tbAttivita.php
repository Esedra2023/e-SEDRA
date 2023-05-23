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
    $autoinc = 'IDENTITY(1,1)';
    $bit = '';
}
else if($_SESSION['ini']['dbms'] == 'My SQL'){
    $autoinc = 'AUTO_INCREMENT';
    $bit = '(1)';
}

$sql =<<<SQL
CREATE TABLE attivita (
    idAt  INT NOT NULL PRIMARY KEY,
    nome VARCHAR (35) NOT NULL,
    active BIT$bit DEFAULT 1,
    anonima BIT$bit DEFAULT 0,
    ballottaggio BIT$bit DEFAULT 0,
    stato INT DEFAULT 0,
    dipendeda INT,
    giorninoti INT DEFAULT 3,
    dtStart DATE,
    notistart BIT$bit DEFAULT 0,
    dtStop DATE,
    notistop BIT$bit DEFAULT 0,
    revisore INT,
    altridati INT,
    note VARCHAR(60),
    FOREIGN KEY (dipendeda) REFERENCES attivita(idAt) ON DELETE NO ACTION,
    FOREIGN KEY (revisore) REFERENCES ruoli(idRl) ON DELETE NO ACTION
);

INSERT INTO attivita (idAt, nome, active, anonima, dipendeda, note) VALUES
    (101,'Segnalazione Bisogni',1,1,NULL,''),(102,'Revisione Bisogni',1,0,101,''),(103,'Discussione Bisogni',0,0,102,'Elimina post bisogni dopo settimane'),
    (104,'Votazione Bisogni',1,0,102,'0 Singola 1 Graduatoria'),(105,'Pubblicazione Bisogni',1,0,104,'sulla home page'),
    (201,'Inserimento Proposte',1,1,105,''),(202,'Revisione Proposte',1,0,201,''),(203,'Discussione Proposte',0,0,202,'Elimina post proposte dopo settimane'),
    (204,'Votazione Proposte',1,0,202,'0 Singola 1 Graduatoria'),(205,'Pubblicazione Proposte',1,0,204,'sulla home page'),
    (300,'Pubblicazione News',1,1,NULL,'Elimina news dopo settimane');
SQL;

if($_POST['resume'] === '1') {  //SQL SERVER
    if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0){
        $resume = 'if exists(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_NAME = \'attivita\') DROP TABLE attivita;';
    }
    else if($_SESSION['ini']['dbms'] == 'My SQL'){
        $resume = 'DROP TABLE IF EXISTS attivita;';
    }
    $sql = $resume . $sql;
}

if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
try{$conn->query( $sql );
} catch(PDOException $e) {echo error($e); exit();}
$conn = NULL;

echo "Table attivita creata...";
exit();
