
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

//session_start();
//if (!defined('ROOT_PATH'))
//          define ('ROOT_PATH', $_SESSION['ini']['ROOT_PATH']);
      require_once (ROOT_PATH . '/include/postfunctions.php'); ?>
<?php
// Get posts under a particular topic
if (isset($_POST['idPr'])) {
    //$topic_id = $_POST['topic'];
    if(isset($_POST['field']))
        $similposts = getSimilarProposte($_POST['idPr'],$_POST['field']);
    else
        $similposts = getSimilarProposte($_POST['idPr'],"pubblicato");
    //foreach($similposts as $key => $sp)
    //{

    //    if($sp['idBs']==$_POST['idB'])
    //    {
    //        $savek=$key;
    //        break;
    //    }
    //}
    //unset($similposts[$savek]);

    //if (count($similposts)!=0)
    //    //$fambito=$_POST['topicName']." ...";
    //else
    //    //$fambito="";
}
?>

<!--<section class="" id="filteredposts">--><!--container-->
    <h5 class="mt-3">
        <?php //echo $fambito ?>
    </h5>
    <hr />
    <!--<div class="container">-->
        <!-- content -->
        <!--<div class="content">-->        
            <div class="row card_container mb-3"><!--row-cols-1 row-cols-md-3 g-4--> 
           
                <?php  if(isset($similposts)) {
                           foreach ($similposts as $spost): ?>              
                    <div class="col-6 mb-2">
                        
                            <div class="card w-100 h-100 ">                               
                                    <div class="card-body bisassociati">

                                        <h6 class="card-title">
                                            <?php echo $spost['titlePrp'] ?>
                                        </h6>
                                        <p class="card-text">
                                            <a href="" class="linkstylebutton text-decoration-none stretched-link" data-idpro="<?php echo $spost['idPr'];?>">
                                                <?php echo substr($spost['textPrp'],0,80).' ...';?>
                                            </a>
                                        </p>
                                    </div>
                            
                                <div class="card-footer">
                                    <small class="text-muted">
                                        <?php echo date("j F Y ", strtotime($spost["dtIns"])); ?>
                                    </small>
                                </div>
                            </div>
                       
                    </div>                   
                
            <?php endforeach; 
            }?>
            </div>
