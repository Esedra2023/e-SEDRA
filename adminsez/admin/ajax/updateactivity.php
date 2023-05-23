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


//attenzione ai numeri delle attività
$idprec=0;
if(!$conn = connectDB()) {echo errorConnectDB(); exit();}
$qsql='';
$sqla='UPDATE attivita SET ';
$sqlb='UPDATE attivita SET ';
$sqlc='UPDATE attivita SET ';
$sqls='';
    if(isset($_POST['idAt']))
    {
        $ida=$_POST['idAt'];
        if(isset($_POST['blog']) && ($ida == 204 || $ida == 104)) //votazione con blog
        {
            //attivo o disattivo discussione bisogni o proposte
            $idprec=$ida-1;
            if($_POST['blog'] == 1 )
                $sqlb =$sqlb." active=1,";
            else  $sqlb =$sqlb." active=0,";
        }
        if(isset($_POST['dtStart']))
        {
            $value=$_POST['dtStart'];
            //$value="$value";
            if($value!="")
            {
                $sqla =$sqla." dtStart='$value',";
                if($idprec!=0)
                    $sqlb =$sqlb." dtStart='$value',";
            }
        }
        if(isset($_POST['dtStop']))
        {
            $value=$_POST['dtStop'];
            //$value="$value";
            if($value!="")
            {
                $sqla =$sqla." dtStop='$value',";
                if($ida == 101 || $ida == 201){
                    $idnex=$ida+1;
                    $dnex=calcolaDataAfter($value,1,'D'); //imposto la data di start della revisione
                    $sqlc =$sqlc." dtStart='$dnex',";
                }
                if($idprec!=0)
                    $sqlb =$sqlb." dtStop='$value',";
            }
        }
       if(isset($_POST['grad']) && $_POST['grad']==1 && ($ida==104 || $ida==204))  //graduatoria
        {
            $field='vbis';  //su bisogni
            if($ida==204)
                $field='vpro';  //su proposte
          // $sqls='';
            for($i=1;$i<=10;$i++)
            {
                $ind="val".$i;
                $sta=$_POST[$ind];
                $sqls=$sqls."UPDATE numberOfStars SET $field=$sta WHERE idstar=$i;";
            }
            //$stmt=$conn->query($sqls);
            //if(!$stmt) throw new Exception('Errore query salva stelle bisogni');

            $sqla =$sqla." altridati=1,";
        }
        //else if(isset($_POST['grad']) && $_POST['grad']==1 && $ida==204)  //graduatoria su proposte
        //{
        //    $sqls='';
        //    for($i=1;$i<=10;$i++)
        //    {
        //        $ind="val".$i;
        //        $sta=$_POST[$ind];
        //        $sqls=$sqls."UPDATE numberOfStars SET vpro=$sta WHERE idstar=$i;";
        //    }
        //    $stmt=$conn->query($sqls);
        //    if(!$stmt) throw new Exception('Errore query salva stelle proposte');

        //    $sqla =$sqla." altridati=1,";
        //}
        else if(isset($_POST['grad']) && $_POST['grad']==0 && ($ida==204 || $ida==104)) //votazione semplice
        {
            $sqla =$sqla." altridati=0,";
        }
        if(isset($_POST['altridati']) &&  ($ida==103 || $ida==203 || $ida==300))
        {
            $value=$_POST['altridati'];
            $sqla =$sqla." altridati='$value',";
        }
        if(isset($_POST['active']))
        {
            if($_POST['active']=='false')
                $chk=0;
            else
                $chk=1;
            $sqla =$sqla." active=$chk,";
        }
        if(isset($_POST['anonima']))
        {
            if($_POST['anonima']=='false')
                $chk=0;
            else
                $chk=1;
            $sqla =$sqla." anonima=$chk,";
        }

        if(isset($_POST['stato']))
        {
            $value=$_POST['stato'];
            $sqla =$sqla." stato='$value',";
        }
        if(isset($_POST['revisore']))
        {
            $value=$_POST['revisore'];
            $sqla =$sqla." revisore='$value',";
            if($ida == 101 || $ida == 201){
                $idnex=$ida+1;
                $sqlc =$sqlc." revisore='$value',";
            }
             if($idprec!=0)
                 $sqlb =$sqlb." revisore='$value',";
        }
        if(isset($_POST['giorninoti']))
        {
            $value=$_POST['giorninoti'];
            $sqla =$sqla." giorninoti='$value',";
        }
        if(isset($_POST['ballottaggio']))
        {
            $value=$_POST['ballottaggio'];
            if($value == 1) $controllo=$ida+1; else $controllo=0;
            $sqla =$sqla." ballottaggio='$value',";
        }
        if($sqla!='UPDATE attivita SET ')
        {
            if($sqla[strlen($sqla)-1]==',')
                $sqla = substr($sqla, 0, -1); //elimino la virgola finale
            $sqla=$sqla." WHERE idAt=$ida;";
            $qsql=$qsql.$sqla;
        }
        if($sqlb!='UPDATE attivita SET ')
        {
            if($sqlb[strlen($sqlb)-1]==',')
                $sqlb = substr($sqlb, 0, -1); //elimino la virgola finale
            $sqlb=$sqlb." WHERE idAt=$idprec;";
            $qsql=$qsql.$sqlb;
        }
        if($sqlc!='UPDATE attivita SET ')
        {
            if($sqlc[strlen($sqlc)-1]==',')
                $sqlc = substr($sqlc, 0, -1); //elimino la virgola finale
            $sqlc=$sqlc." WHERE idAt=$idnex;";
            $qsql=$qsql.$sqlc;
            //$stmt=$conn->query($sql);
        }
        if($sqls!='')
            $qsql=$qsql.$sqls;

        if($qsql!='')
        {
            $stmt=$conn->query($qsql);
            if(!$stmt) throw new Exception('Errore query aggiorna attivita');
        }
       if(isset($controllo) && $controllo!=null)
            controlloContemporaneo($controllo,$conn);

        if(isset($_POST['select']))
        {
            $sql="SELECT attivita.*,ruoli.ruolo as rev FROM attivita left join ruoli on ruoli.idRl=attivita.revisore WHERE idAt=$ida;";
        }
        else
            $sql="SELECT attivita.*,ruoli.ruolo as rev FROM attivita left join ruoli on ruoli.idRl=attivita.revisore WHERE idAt=$ida;";

            //$sql="SELECT idAt,nome,active,anonima, scaduta, stato, dtStart,dtStop,giorninoti, revisore,altridati,ruoli.ruolo as rev FROM attivita left join ruoli on ruoli.idRl=attivita.revisore WHERE idAt=$ida;";
        $stmt=$conn->query($sql);
        if(!$stmt) throw new Exception('Errore query seleziona attivita');
        $riga = $stmt->fetch(PDO::FETCH_ASSOC); //result set per una riga di attivita
        if($riga['idAt']==104 || $riga['idAt']==204)
        {
            $idisc=$riga['idAt']-1;
            $sql="SELECT attivita.active FROM attivita WHERE idAt=$idisc;";
            $stmt=$conn->query($sql);
            if(!$stmt) throw new Exception('Errore query seleziona attivita');
            $chkd = $stmt->fetch(PDO::FETCH_ASSOC); //result set per una riga di attivita
            $riga['blog']=$chkd['active'];
        }

        $_SESSION['formacti']=$riga;
        //$sql ="SELECT * FROM attivita WHERE idAt=ida;";
        $conn=null;
        echo json_encode($riga);
    }else echo json_encode(0);
    exit(0);

