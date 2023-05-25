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
require_once ROOT_PATH . '/include/postfunctions.php';
require_once ROOT_PATH . '/include/wrapperfunctions.php';

if(!isset($_POST['page'])) forbidden();

if(isset($_SESSION['moment']))
    unset($_SESSION['moment']);
defineMoment();		//ri-definisco qui in caso di modifiche da parte dell'admin

$posts=[];

$topics = getAllTopics();
$conta=0;
$nat=count($_SESSION['moment']);
if($nat!=0)
{
    if (array_key_exists(101,$_SESSION['moment']))  //segnalazione bisogni
    {
        $Abisogni=$_SESSION['moment']['101'];
        $Abisogni['bisAct']=true;
        $Abisogni['IamAuthor']=compareRuoli($Abisogni['author'],$_SESSION['user']['roles']);
		$Abisogni['IamRev']=IamRevisor($Abisogni['revisore']);

        //if($Abisogni['IamRev'])
        //    $posts = wr_getAllPosts("revisor","bisogni");
        //else
        if($Abisogni['IamAuthor'])         // se non sono mai stato autore non dovrei comunque vedere nulla
			$posts = wr_getAllPosts("personal","bisogni");

        //$anonim=$Abisogni['anonima'];

		include(ROOT_PATH . '/pages/bisognisegnala.php');
        $conta++;
    }

    if(array_key_exists(102,$_SESSION['moment']))   //revisione bisogni
    {
        $Rbisogni=$_SESSION['moment']['102'];
        $Rbisogni['revBis']=true;
        //la revisione non ha autore
        $Rbisogni['IamRev']=IamRevisor($Rbisogni['revisore']);
        $Rbisogni['IamAuthor']=false;

        if($Rbisogni['IamRev'])
        {
            $posts = wr_getAllPosts("revisor","bisogni");
            include(ROOT_PATH . '/pages/bisognirevisione.php');
        }
        else
        {
            $posts = wr_getAllPosts("personal","bisogni");
            $titlePage='Fase attiva dal '.date_format(date_create($Rbisogni['dtStart']),'d/m/Y').
                ' al '.date_format(date_create($Rbisogni['dtStop']),'d/m/Y').' - mancano '.
                $Rbisogni['ggscad'].' giorni alla chiusura.<br/>Il tuo ruolo non consente la partecipazione in questa fase';
            $h2 = $Rbisogni['nome'];
            include(ROOT_PATH . '/pages/bisognidefault.php');
        }
        $conta++;
    }

    if (array_key_exists(103,$_SESSION['moment']) && array_key_exists(104,$_SESSION['moment'])) //votazione bisogni con discussione
    {
        //prevale la votazione prendo tutto dall'attività di votazione
        $VDbisogni=$_SESSION['moment']['104'];
        $VDbisogni['votAct']=true;
        $VDbisogni['blogAct']=true;
        $field="pubblicato";
        if($VDbisogni['ballottaggio'])
            $field="ingrad";
        // $Dbisogni=$_SESSION['moment']['103'];

        //dovrebbe avere date coincidenti e stessi ruoli
        // anche se non coincidono prendo quelli della votazione
        $VDbisogni['IamAuthor']=compareRuoli($VDbisogni['author'],$_SESSION['user']['roles']);
        $VDbisogni['IamRev']=IamRevisor($VDbisogni['revisore']);

        if($VDbisogni['IamAuthor'])
        {
            //la funzione seleziona solo i voti nel periodo attuale di votazione
            $posts = getAllPublishBisWithGrade(true,$field);     //true bisogni anonimi per discussione
            likeForMeB($posts,$_SESSION['user']['idUs']);
        }
        // se sono anche revisore li cambio con quelli nominativi
        if($VDbisogni['IamRev'])
        {
            $posts = getAllPublishBisWithGrade(false,$field);     //revisore vede anche i nominativi e le sue valutazioni se è anche autore-->
            likeForMeB($posts,0);
        }

        //$anonimV=$VDbisogni['anonima'];       //votazione può essere anonima, ma discussione sempre nominativa
        //$anonimD=$Dbisogni['anonima'];
        $_SESSION['VDbisogni']=$VDbisogni;
        include(ROOT_PATH . '/pages/bisognidiscutivota.php');
        $conta++;
    }
   else if (array_key_exists(104,$_SESSION['moment'])) //solo votazione bisogni
    {
        $VDbisogni=$_SESSION['moment']['104'];
        $VDbisogni['votAct']=true;
        $VDbisogni['blogAct']=false;
        $field="pubblicato";
        if($VDbisogni['ballottaggio'])
            $field="ingrad";
        // $Dbisogni=$_SESSION['moment']['103'];

        //dovrebbe avere date coincidenti e stessi ruoli
        // anche se non coincidono prendo quelli della votazione
        $VDbisogni['IamAuthor']=compareRuoli($VDbisogni['author'],$_SESSION['user']['roles']);
        $VDbisogni['IamRev']=IamRevisor($VDbisogni['revisore']);

        //$IamRevD=IamRevisor($Dbisogni['revisore']);
        //if($IamRevV != $IamRevD)
        // $IamRev=$IamRevV;

        if($VDbisogni['IamAuthor'])
        {
            $posts = getAllPublishBisWithGrade(true,$field);     //true bisogni anonimi per discussione
            likeForMeB($posts,$_SESSION['user']['idUs']);
        }
        // se sono anche revisore li cambio con quelli nominativi
        if($VDbisogni['IamRev'])
        {
            $posts = getAllPublishBisWithGrade(false,$field);     //revisore vede anche i nominativi e le sue valutazioni se è anche autore-->
            likeForMeB($posts,0);
        }
        //$anonimV=$VDbisogni['anonima'];       //votazione può essere anonima, ma discussione sempre nominativa
        //$anonimD=$Dbisogni['anonima'];
        $_SESSION['VDbisogni']=$VDbisogni;
        include(ROOT_PATH . '/pages/bisognidiscutivota.php');
        $conta++;
   }
    //    else  if (array_key_exists(103,$_SESSION['moment'])) //solo discussione non può esistere
   //{
   //    //votazione non c'è prendo tutto dall'attività discussione
   //    $VDbisogni=$_SESSION['moment']['103'];
   //    $VDbisogni['votAct']=false;
   //    $VDbisogni['blogAct']=true;

   //    // $Dbisogni=$_SESSION['moment']['103'];

   //    //dovrebbe avere date coincidenti e stessi ruoli
   //    // anche se non coincidono prendo quelli della votazione
   //    $VDbisogni['IamAuthor']=compareRuoli($VDbisogni['author'],$_SESSION['user']['roles']);
   //    $VDbisogni['IamRev']=IamRevisor($VDbisogni['revisore']);

   //    //$IamRevD=IamRevisor($Dbisogni['revisore']);
   //    //if($IamRevV != $IamRevD)
   //    // $IamRev=$IamRevV;

   //    $posts = wr_getAllPosts('revisor','bisogni');   //ATTTENZIONE CONTIENE ANCHE QUELLI CANCELLATI
   //    if($VDbisogni['IamAuthor'])   {}   //nascondo i nomi
   //        //$posts = wr_getAllPosts('revisor','bisogni');     //true bisogni anonimi per discussione

   //    // se sono anche revisore li cambio con quelli nominativi
   //    //if($VDbisogni['IamRev'])
   //    //{
   //    //    $posts = getAllPublishBisWithGrade(false);     //revisore vede anche i nominativi e le sue valutazioni se è anche autore-->
   //    //}
   //    foreach($posts as &$po)
   //    {
   //        $lk=getLike($po['idBs']);
   //        if($lk)
   //            $po['nlike']=$lk['nlike'];
   //    }
   //    //$anonimV=$VDbisogni['anonima'];       //votazione può essere anonima, ma discussione sempre nominativa
   //    //$anonimD=$Dbisogni['anonima'];
   //    $_SESSION['VDbisogni']=$VDbisogni;
   //    include(ROOT_PATH . '/pages/bisognidiscutivota.php');
   //    $conta++;
   //}
    if(array_key_exists(105,$_SESSION['moment']))   //pubblicazione bisogni
    {
        $Pbisogni=$_SESSION['moment']['105'];
        $Pbisogni['pubBis']=true;
        //la pubblicazione non ha autore
        $Pbisogni['IamRev']=IamRevisor($Pbisogni['revisore']);
        $Pbisogni['IamAuthor']=false;
        //$someonepersonalpost=false;
        //if(!isset($_SESSION['ini']['gradDefBisogni']))      /* &&  !isset($_SESSION['ini']['ballottaggio']))*/
        //{
        $ballot=getLastPollType(104);
        if($ballot!=null)
        {
            if($ballot['ballottaggio'] == 0)        //prima votazione
            {
                $field = "pubblicato";
                $_POST['field'] = $field;
                if($Pbisogni['IamRev'])
                {

                    if(!isset($_SESSION['ini']['gradDefBisogni']) || $_SESSION['ini']['gradDefBisogni']==0)
                    {
                        $posts = wr_getBisResultPolling("revisor",1, $field);   //salvo la graduatoria con ballot a zero e imposto il $_SESSION['ini']['gradDefBisogni']
                    }
                    if($_SESSION['ini']['gradDefBisogni'] == 1) //vedo grad 1 con check
                    {
                        $_POST['check'] = 1;  $_POST['news'] = 1;
                    }
                    else if ($_SESSION['ini']['gradDefBisogni'] == 2)
                    {
                        $_POST['check']=0;
                        $_POST['news'] = 0;
                    }
                    $posts=wr_viewDefGradBis($field); //recupero graduatoria da tabella apposita
                    $esclusib=getBisogniEsclusi("revisor", $field);
                    include(ROOT_PATH . '/pages/bisognipubblicaGrad.php');
                }
                else
                {
                    if(isset($_SESSION['ini']['gradDefBisogni']) && $_SESSION['ini']['gradDefBisogni']!=0)
                    {
                        //vedo grad1 senza check
                        $_POST['check']=0;
                        $_POST['news'] = 0;
                        $posts=wr_viewDefGradBis($field); //recupero graduatoria da tabella apposita
                        $esclusib=getBisogniEsclusi("personal", $field);
                        include(ROOT_PATH . '/pages/bisognipubblicaGrad.php');
                    }
                    else{//utente generico con graduatoria non ancora consolidata
                        $posts = wr_getBisResultPolling("personal",0, $field);
                        $titlePage='Fase attiva dal '.date_format(date_create($Pbisogni['dtStart']),'d/m/Y').' al '.date_format(date_create($Pbisogni['dtStop']),'d/m/Y').' - mancano '.$Pbisogni['ggscad'].' giorni alla chiusura.<br/>';
                        $titlePage=$titlePage. 'Il tuo ruolo non consente la partecipazione in questa fase';
                        $h2=$Pbisogni['nome'];
                        include(ROOT_PATH . '/pages/bisognidefault.php');
                        //include(ROOT_PATH . '/pages/bisognipubblica.php');
                    }
                }
            }
            else if($ballot['ballottaggio'] == 1)
            {
                $field = "ingrad";
                $_POST['field'] = $field;

                if($Pbisogni['IamRev'])
                {
                    if(!isset($_SESSION['ini']['BallottaggioBis']) || $_SESSION['ini']['BallottaggioBis'] == 0)
                    {
                        $posts = wr_getBisResultPolling("revisor",1, $field);   //salvo la graduatoria con ballot a 1
                    }
                    if($_SESSION['ini']['BallottaggioBis'] == 1)
                    { //vedo grad 2 senza check con bottone
                        $_POST['check']=0;  //no check
                        $_POST['news'] = 1; //si news
                    }
                    else if ($_SESSION['ini']['BallottaggioBis'] == 2)
                    {
                        $_POST['check'] = 0;
                        $_POST['news'] = 0; //no news
                    }

                    $posts=wr_viewDefGradBis($field); //recupero graduatoria da tabella apposita
                    $esclusib=getBisogniEsclusi("revisor", $field);
                    include(ROOT_PATH . '/pages/bisognipubblicaGrad.php');
                }
                else
                {
                    if(isset($_SESSION['ini']['BallottaggioBis']) && $_SESSION['ini']['BallottaggioBis'] == 1)
                    {
                        //vedo grad2 senza check
                        $_POST['check']=0;  $_POST['news'] = 0;
                        $posts=wr_viewDefGradBis($field); //recupero graduatoria da tabella apposita
                        $esclusib=getBisogniEsclusi("personal", $field);
                    }
                    else{
                        $_POST['field'] = "pubblicato";
                        //vedo grad1 senza check
                        $_POST['check']=0;
                        $_POST['news'] = 0;
                        $posts=wr_viewDefGradBis("pubblicato"); //recupero graduatoria da tabella apposita
                        $esclusib=getBisogniEsclusi("personal","pubblicato");
                    }
                    include(ROOT_PATH . '/pages/bisognipubblicaGrad.php');
                }
            }
        } //altrimenti attività in corso o qualche anomalia

        //$anonim=$Abisogni['anonima'];

        //$topics = getAllTopics();

        $conta++;
    }
}
if($conta==0)
{
    //pagina di default dei bisogni personali
    $posts = wr_getAllPosts("personal","bisogni");
    $titlePage='Nessuna fase attiva riguardante i bisogni, oppure il tuo ruolo non consente la partecipazione alle fasi attive';
    $h2='I miei Bisogni';
    $conambito=false;
    include(ROOT_PATH . '/pages/bisognidefault.php');
}

?>

