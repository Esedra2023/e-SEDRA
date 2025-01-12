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
//$fieldb="pubblicato";
//if(isset($_SESSION['ini']['BallottaggioBis']) && $_SESSION['ini']['BallottaggioBis']==1)
//    $fieldb="ingrad";

//$bisogni = getSummaryBis($fieldb);
$conta=0;
$nat=count($_SESSION['moment']);
if($nat!=0)
{
    if (array_key_exists(201,$_SESSION['moment']))  //invio proposte
    {
        $Aproposte=$_SESSION['moment']['201'];
        $Aproposte['proAct']=true;
        $Aproposte['IamAuthor']=compareRuoli($Aproposte['author'],$_SESSION['user']['roles']);
		$Aproposte['IamRev']=IamRevisor($Aproposte['revisore']);

        if($Aproposte['IamAuthor'])         // se non sono mai stato autore non dovrei comunque vedere nulla
			$posts = wr_getAllPosts("personal","proposte");

        //$anonim=$Aproposte['anonima'];

		include(ROOT_PATH . '/pages/propostesegnala.php');
        $conta++;
    }

    if(array_key_exists(202,$_SESSION['moment']))   //revisione proposte
    {
        $Rproposte=$_SESSION['moment']['202'];
        $Rproposte['revPro']=true;
        //la revisione non ha autore
        $Rproposte['IamRev']=IamRevisor($Rproposte['revisore']);
        $Rproposte['IamAuthor']=false;

        if($Rproposte['IamRev'])
        {
            $posts = wr_getAllPosts("revisor","proposte");
            include(ROOT_PATH . '/pages/proposterevisione.php');
        }
        else
        {
            $posts = wr_getAllPosts("personal","proposte");
            $titlePage='Fase attiva dal '.date_format(date_create($Rproposte['dtStart']),'d/m/Y').
                ' al '.date_format(date_create($Rproposte['dtStop']),'d/m/Y').' - mancano '.
                $Rproposte['ggscad'].'alla chiusura.<br/>Il tuo ruolo non consente la partecipazione in questa fase';
            $h2 = $Rproposte['nome'];
            include(ROOT_PATH . '/pages/propostedefault.php');
        }
        $conta++;
    }

    if (array_key_exists(203,$_SESSION['moment']) && array_key_exists(204,$_SESSION['moment'])) //votazione bisogni con discussione
    {
        //prevale la votazione prendo tutto dall'attività di votazione
        $VDproposte=$_SESSION['moment']['204'];
        $VDproposte['votAct']=true;
        $VDproposte['blogAct']=true;
        $field="pubblicato";
        if($VDproposte['ballottaggio'])
            $field="ingrad";



        //dovrebbe avere date coincidenti e stessi ruoli
        // anche se non coincidono prendo quelli della votazione
        $VDproposte['IamAuthor']=compareRuoli($VDproposte['author'],$_SESSION['user']['roles']);
        $VDproposte['IamRev']=IamRevisor($VDproposte['revisore']);


        if($VDproposte['IamAuthor'])
        {
            $posts = getAllPublishProWithGrade(true,$field);     //true bisogni anonimi per discussione
            likeForMeP($posts,$_SESSION['user']['idUs']);
        }
        // se sono anche revisore li cambio con quelli nominativi
        if($VDproposte['IamRev'])
        {
            $posts = getAllPublishProWithGrade(false,$field);     //revisore vede anche i nominativi e le sue valutazioni se è anche autore-->
            likeForMeP($posts,0);
        }

        //$anonimV=$VDbisogni['anonima'];       //votazione può essere anonima, ma discussione sempre nominativa
        //$anonimD=$Dbisogni['anonima'];
        $_SESSION['VDproposte']=$VDproposte;
        include(ROOT_PATH . '/pages/propostediscutivota.php');
        $conta++;
    }
    else if (array_key_exists(204,$_SESSION['moment'])) //solo votazione proposte
    {
        //prevale la votazione prendo tutto dall'attività di votazione
        $VDproposte=$_SESSION['moment']['204'];
        $VDproposte['votAct']=true;
        $VDproposte['blogAct']=false;

        $field="pubblicato";
        if($VDproposte['ballottaggio'])
            $field="ingrad";

        // $Dbisogni=$_SESSION['moment']['103'];

        //dovrebbe avere date coincidenti e stessi ruoli
        // anche se non coincidono prendo quelli della votazione
        $VDproposte['IamAuthor']=compareRuoli($VDproposte['author'],$_SESSION['user']['roles']);
        $VDproposte['IamRev']=IamRevisor($VDproposte['revisore']);

        //$IamRevD=IamRevisor($Dbisogni['revisore']);
        //if($IamRevV != $IamRevD)
        // $IamRev=$IamRevV;

        if($VDproposte['IamAuthor']){
            $posts = getAllPublishProWithGrade(true,$field);     //true bisogni anonimi per discussione
            likeForMeP($posts,$_SESSION['user']['idUs']);
        }
        // se sono anche revisore li cambio con quelli nominativi
        if($VDproposte['IamRev'])
        {
            $posts = getAllPublishProWithGrade(false,$field);     //revisore vede anche i nominativi e le sue valutazioni se è anche autore-->
            likeForMeP($posts,0);
        }

        //$anonimV=$VDbisogni['anonima'];       //votazione può essere anonima, ma discussione sempre nominativa
        //$anonimD=$Dbisogni['anonima'];
        $_SESSION['VDproposte']=$VDproposte;
        include(ROOT_PATH . '/pages/propostediscutivota.php');
        $conta++;
    }

    if(array_key_exists(205,$_SESSION['moment']) && !array_key_exists(204,$_SESSION['moment']) )   //pubblicazione proposte
    {
        $Pproposte=$_SESSION['moment']['205'];
        $Pproposte['pubPro']=true;
        //la pubblicazione non ha autore
        $Pproposte['IamRev']=IamRevisor($Pproposte['revisore']);
        $Pproposte['IamAuthor']=false;
        $ballot=getLastPollType(204);
        if($ballot!=null)
        {
            if($ballot['ballottaggio'] == 0)
            {
                $field = "pubblicato";
                $_POST['field'] = $field;
                if($Pproposte['IamRev'])
                {
                    if(!isset($_SESSION['ini']['gradDefProposte']) || $_SESSION['ini']['gradDefProposte'] == 0)
                    {
                        $posts = wr_getProResultPolling("revisor",1, $field);   //salvo la graduatoria con ballot a zero e imposto il $_SESSION['ini']['gradDefBisogni']
                    }
                    if ($_SESSION['ini']['gradDefProposte'] == 1) //vedo grad 1 con check
                    {
                        $_POST['check'] = 1;
                        $_POST['news'] = 1;
                    } else if ($_SESSION['ini']['gradDefProposte'] == 2) {
                        $_POST['check'] = 0;
                        $_POST['news'] = 0;
                    }

                    $posts=wr_viewDefGradPro($field,1); //recupero graduatoria da tabella apposita
                    $esclusib=getProposteEscluse("revisor", $field);
                    include(ROOT_PATH . '/pages/propostepubblicaGrad.php');
                }
                else
                {
                    if(isset($_SESSION['ini']['gradDefProposte']) && $_SESSION['ini']['gradDefProposte'] != 0)
                    {
                        //vedo grad1 senza check
                        $_POST['check'] = 0;
                        $_POST['news'] = 0;
                        $posts=wr_viewDefGradPro($field,1); //recupero graduatoria da tabella apposita
                        $esclusib=getProposteEscluse("personal", $field);
                        include(ROOT_PATH . '/pages/propostepubblicaGrad.php');
                    }
                    else{//utente generico con graduatoria non ancora consolidata
                        $posts = wr_getProResultPolling("personal",0, $field);
                        $titlePage='Fase attiva dal '.date_format(date_create($Pproposte['dtStart']),'d/m/Y').' al '.date_format(date_create($Pproposte['dtStop']),'d/m/Y').' - mancano '.$Pproposte['ggscad'].'alla chiusura.<br/>';
                        $titlePage=$titlePage. 'Il tuo ruolo non consente la partecipazione in questa fase';
                        $h2=$Pproposte['nome'];
                        include(ROOT_PATH . '/pages/propostedefault.php');
                    }
                }
            }
            else if($ballot['ballottaggio'] == 1)
            {
                $field = "ingrad";
                $_POST['field'] = $field;
                if($Pproposte['IamRev'])
                {
                    if(!isset($_SESSION['ini']['BallottaggioPro']) || $_SESSION['ini']['BallottaggioPro'] == 0)
                    {
                        $posts = wr_getProResultPolling("revisor",1, $field);   //salvo la graduatoria con ballot a 1
                    }
                    if ($_SESSION['ini']['BallottaggioPro'] == 1) { //vedo grad 2 senza check con bottone
                        $_POST['check'] = 0; //no check
                        $_POST['news'] = 1; //si news
                    } else if ($_SESSION['ini']['BallottaggioPro'] == 2) {
                        $_POST['check'] = 0;
                        $_POST['news'] = 0; //no news
                    }
                    //vedo grad 2 senza check e bottone
                    $posts=wr_viewDefGradPro($field,1); //recupero graduatoria da tabella apposita
                    $esclusib=getProposteEscluse("revisor", $field);
                    include(ROOT_PATH . '/pages/propostepubblicaGrad.php');
                }
                else
                {
                    if(isset($_SESSION['ini']['BallottaggioPro']) && $_SESSION['ini']['BallottaggioPro'] == 1)
                    {
                        //vedo grad2 senza check
                        $_POST['check'] = 0;
                        $_POST['news'] = 0;
                        $posts=wr_viewDefGradPro($field,1); //recupero graduatoria da tabella apposita
                        $esclusib=getProposteEscluse("personal", $field);
                    }
                    else{
      
                        $_POST['field'] = "pubblicato";
                        //vedo grad1 senza check
                        $_POST['check'] = 0;
                        $_POST['news'] = 0;
                        $posts=wr_viewDefGradPro("pubblicato",1); //recupero graduatoria da tabella apposita
                        $esclusib=getProposteEscluse("personal","pubblicato");
                    }
                    include(ROOT_PATH . '/pages/propostepubblicaGrad.php');
                }
            }
        } //altrimenti attività in corso o qualche anomalia

        $conta++;
    } else{
        //ballottaggio partito prima che finisse la fase di pubblicazione
        //ignoro perchè ho già graduatoria poi stoppo la pubblicazione
    }
}
if($conta==0)
{
    //pagina di default delle proposte personali
    $posts = wr_getAllPosts("personal","proposte");
    $titlePage='Nessuna fase attiva riguardante le proposte, oppure il tuo ruolo non consente la partecipazione alle fasi attive';
    $h2='Le mie proposte';
    include(ROOT_PATH . '/pages/propostedefault.php');
}
?>

