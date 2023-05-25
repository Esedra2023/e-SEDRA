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
//require_once ('../../config.php');
if (!defined('ROOT_PATH'))
    define ('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);
require_once ROOT_PATH .'/include/functions.php';
require_once ROOT_PATH . '/include/wrapperfunctions.php';

    $title =esc($_POST['title']);
    $itable= $_POST['itable'];
    $field = $_POST['field'];
    if($itable=='B')
    {
        $grad=wr_viewDefGradBis($field);
        $testo = "<table><tr><th>Titlo</th><th>  </th><th>Voti</th><th>  </th><th>Votanti</th></tr>";
        foreach($grad as $g)
        {
             $testo = $testo . "<tr><td>{$g['titleBis']}</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{$g['grade']}</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{$g['votanti']}</td></tr>";
        }
        $testo = $testo ."</table>";
    }
    else
    {
    $grad = wr_viewDefGradPro($field,1);    //1 GRADUATORIA TOTALE
    $testo = "<table><tr><th>Titlo</th><th>  </th><th>Voti</th><th>  </th><th>Votanti</th></tr>";
    foreach ($grad as $g) {
        $testo = $testo . "<tr><td>{$g['titlePrp']}</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{$g['grade']}</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{$g['votanti']}</td></tr>";
    }
    $testo = $testo . "</table>";

}
$_POST['text'] = $testo;
$_POST['crud'] = 'C';
    $datfin= new DateTime();
    $datfin = calcolaDataAfter(date_format($datfin, "Y-m-d"),7,'D');
$_POST['dtEnd'] = $datfin;
$_POST['settScad'] = '3';
$_POST['idNw'] = 0;
include(ROOT_PATH . '/ajax/createnews.php');

if ($field != "ingrad") {
    $_SESSION['ini']['gradDefBisogni'] = 2; //graduatoria fissata, news creata e togli i check
    updIniFile('Temp', 'gradDefBisogni', 2);
}
if ($field == "ingrad") {
    $_SESSION['ini']['BallottaggioBis'] = 2;
    updIniFile('Temp', 'BallottaggioBis', 2);

}