
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
if (isset($_POST['topic'])) {
    $topic_id = $_POST['topic'];
    if(isset($_POST['field']))
        $similposts = getPublishedPostsByTopic($topic_id,$_POST['field']);
    else
        $similposts = getPublishedPostsByTopic($topic_id,"pubblicato");
    foreach($similposts as $key => $sp)
    {

        if($sp['idBs']==$_POST['idB'])
        {
            $savek=$key;
            break;
        }
    }
    unset($similposts[$savek]);

    if (count($similposts)!=0)
        $fambito='Altri in: '.$_POST['topicName'];
    else
        $fambito="";
}
?>

<!--<section class="" id="filteredposts">--><!--container-->
    <h5 class="mt-3">
        <?php echo $fambito ?>
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
                                <!--<a class="linkstylebutton" id="cb<?php echo $spost['idBs'];?>" data-idbis="<?php echo $spost['idBs'];?>" href=''>
    <?php /*if($spost['imgBis']) echo "<img class='card-img-top' src=\"/images/bisogni/{$spost['imgBis']}\"/ alt='..'>";
          else echo "<img class='card-img-top' src=\"/images/bisogni/0.webp\" / alt='...'>"; */?>-->
    <!--<img src="..." class="card-img-top" alt="..." />-->
<!--</a>-->
                               
                                    <div class="card-body bisassociati">

                                        <h6 class="card-title">
                                            <?php echo $spost['titleBis'] ?>
                                        </h6>
                                        <p class="card-text">
                                            <a href="" class="linkstylebutton text-decoration-none stretched-link" data-idbis="<?php echo $spost['idBs'];?>">
                                                <?php if (strlen($spost['textBis']) > 80)
                                                        echo substr($spost['textBis'], 0, 80) . ' ...';
                                                    else
                                                        echo $spost['textBis'];?>
                                            </a>
                                        </p>
                                    </div>
                            
                                <!--<div class="card-footer">
                                    <small class="text-muted">
                                        <?php 
                                        //echo date("d-m-Y H:i", strtotime($spost["dtIns"])); ?>
                                    </small>
                                </div>-->
                            </div>
                       
                    </div>                   
                
            <?php endforeach; 
            }?>
            </div>
