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
require_once ROOT_PATH . '/vendor/table2pdf.php';

    $title =esc($_POST['title']);
    $itable= $_POST['itable'];
    $field = $_POST['field'];
    $field = trim($field);
    if($itable=='B')
    {
        $grad=wr_viewDefGradBis($field);
        $testo = "<table><tr><th>Titolo</th><th>  </th><th>Voti</th></tr>";
        foreach($grad as $g)
        {
             $testo = $testo . "<tr><td>{$g['titleBis']}</td><td>&nbsp;</td><td>{$g['grade']}</td></tr>";
        }
        $testo = $testo ."</table>";
    }
    else
    {
    $grad = wr_viewDefGradPro($field,1);    //1 GRADUATORIA TOTALE
    $testo = "<table><tr><th>Titolo</th><th>  </th><th>Voti</th></tr>";
    foreach ($grad as $g) {
        $testo = $testo . "<tr><td>{$g['titlePrp']}</td><td>&nbsp;</td><td>{$g['grade']}</td></tr>";
    }
    $testo = $testo . "</table>";

}
$_POST['text'] = $testo;
$_POST['crud'] = 'C';
    $datfin= new DateTime();
    $datfin = calcolaDataAfter(date_format($datfin, "Y-m-d H:i"),10,'D');
$_POST['dtEnd'] = $datfin;
$_POST['settScad'] = '';
$_POST['idNw'] = 0;
$_POST['topublish'] = 1;
include(ROOT_PATH . '/ajax/createnews.php');

if ($field != "ingrad") {
    creaGradPDF($itable, $grad,false);
    $_SESSION['ini']['gradDefBisogni'] = 2; //graduatoria fissata, news creata e togli i check
    updIniFile('Temp', 'gradDefBisogni', 2);

}
if ($field == "ingrad") {
    creaGradPDF($itable, $grad, true);
    $_SESSION['ini']['BallottaggioBis'] = 2;
    updIniFile('Temp', 'BallottaggioBis', 2);




}


// Colored table
function FancyTable($pdf,$header, $data)
{
    // Colors, line width and bold font
    $pdf->SetFillColor(136, 22, 0);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(231, 208, 204);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('', 'I',9);
    // Header
    $w = array(10,25,60, 60, 10,10,10);
    for ($i = 0; $i < count($header); $i++)
        $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
    $pdf->Ln();
    // Color and font restoration
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('');
    // Data
    //$fill = false;
    $pos = 1;
    $pdf->SetWidths($w);
    foreach ($data as $row) {
        $am= mb_convert_encoding($row['ambito'], 'windows-1252', 'UTF-8');
        $tit= mb_convert_encoding($row['titleBis'], 'windows-1252', 'UTF-8');
        $text=mb_convert_encoding($row['textBis'], 'windows-1252', 'UTF-8');
        $pdf->Row(array($pos,$am ,$tit ,$text , $row['grade'],$row['nlike'], $row['votanti']));
        //$pdf->Cell($w[0], 6, number_format($pos), 'LR', 0, 'R', $fill);
        //$pdf->Cell($w[1], 6, $row['ambito'], 'LR', 0, 'L', $fill);
        //$pdf->Cell($w[2], 6, $row['titleBis'], 'LR',0, 'L', $fill);
        //$pdf->Cell($w[3], 6, $row['textBis'], 'LR',0, 'L', $fill);
        //$pdf->Cell($w[4], 6, number_format($row['grade']), 'LR', 0, 'R', $fill);
        //$pdf->Cell($w[5], 6, number_format($row['nlike']), 'LR', 0, 'R', $fill);
        //$pdf->Cell($w[6], 6, number_format($row['votanti']), 'LR', 0, 'R', $fill);
        //$pdf->Ln();
        $pos++;
        //$fill = !$fill;
    }
    // Closing line
    //$pdf->Cell(array_sum($w), 0, '', 'T');
}

function creaGradPDF($table, $grad,$bal)
{
    $pdf = new PDF_MC_Table();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    if ($table == 'B')
    {
        $titolo = "Graduatoria Bisogni";
        $nomefile="gradbis";
    }
    else
    {
        $titolo = "Graduatoria Proposte";
        $nomefile="gradpro";
    }
    if ($bal)
    {
        $titolo = $titolo . " dopo Seconda Votazione";
        $nomefile=$nomefile.'2';
    }
    $nomefile=ROOT_PATH ."/".$nomefile.".pdf";
    $cen = $pdf->GetStringWidth($titolo) +4;
    $pdf->SetX((210 - $cen) / 2);
    $pdf->SetTextColor(136, 22, 0);
    $pdf->Cell($cen, 10, $titolo,0,1,'C');
    //$pdf->Ln(12);
    $header = array('Pos.','Ambito','Titolo', 'Descrizione', 'Punti', 'Like','Votanti');
    FancyTable($pdf, $header, $grad);
    $pdf->Output('F', $nomefile);
}