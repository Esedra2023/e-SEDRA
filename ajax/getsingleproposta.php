
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

$sql = "CALL getSingleProposta(:idPr,:pub)";
if(stripos($_SESSION['ini']['dbms'], 'SQL Server') === 0) $sql = '{'.$sql.'}';
if(!$conn = connectDB()) {echo errorConnectDB(); exit();}

   $stmt = $conn->prepare($sql);
   $stmt->bindValue(':idPr', $_POST['idPr']);
   $stmt->bindValue(':pub', $_POST['pub']);
  if(! $stmt->execute()) throw new Exception('Errore query get proposta singola');

$resProposta = $stmt->fetch(PDO::FETCH_ASSOC);
$sql="SELECT bisogno FROM propBis WHERE proposta={$_POST['idPr']}";
$stmt = $conn->prepare($sql);
if(! $stmt->execute()) throw new Exception('Errore query getBisogni associati');
$bis = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = NULL;
$conn = NULL;
$res['rec']=$resProposta;
$res['bis']=$bis;
$isEditingProposta=true;
echo(json_encode($res));


