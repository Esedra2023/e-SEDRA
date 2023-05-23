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


$news=wr_getNews(0,0,0);
for($i=0;$i<count($news);$i++)
{
    $dtE = $news[$i]['dtEnd'];
    $dtC = strtotime("now");
    $dtE = strtotime($dtE);
    if($dtC > $dtE) $news[$i]['scad']=1;
    else $news[$i]['scad']=0;
}
?>
<div class="content">
    <h2 class="content-head is-center">News</h2>
   
    <section id="news" class="row mb-5">
        <div class="col-lg-6">
            <div class="accordion" id="NewsAccHP">
                <?php
            $nb=0;
            foreach($news as $row){

                  //$dtN = $row['dtNews'];
                  //$dtN = strtotime($dtN);
                  //$dtN = date("d/m/Y H:i", $dtN);		//formatta la data
                 if($row['scad']!=1){
                     $nb++;
                ?>
                <div class="accordion-item">

                    <h2 class="accordion-header">

                        <button class="accordion-button <?php if($nb!=1) echo 'collapsed'?>" type="button" data-bs-toggle="collapse" data-bs-target="#n<?php echo $row['idNw']?>" aria-expanded="true" aria-controls="n<?php echo $row['idNw']?>">
                            <span class="text-muted">
                                <?php	echo $row['sdtNews'];?>
                            </span>
                            <h5>
                                        &nbsp;&nbsp;&nbsp;<?php	echo $row['title'];		?>
                            </h5>
                        </button>
                    </h2>
                    <div id="n<?php echo $row['idNw']?>" class="accordion-collapse collapse <?php if($nb==1) echo 'show'?>" data-bs-parent="#NewsAccHP">
                        <div class="accordion-body">
                            <!--<strong>
                                <?php	echo $row['title'];		?>
                            </strong>--><?php	echo $row['text'];?>
                        </div>
                    </div>
                </div>
                <?php }
            } ?>
            </div>
        </div>

        <!--<div class="container">-->
            <!--Mostra News Scadute is-center-->

 
 <div class="col-lg-6">
   
     <div class="form-check form-switch mt-3 mb-3">
        <h5>
         <input class="form-check-input" type="checkbox" id="newscadute" /><!--role="switch"-->         
          <label class="form-check-label" for="newscadute">News Scadute</label>
        </h5>
         <!--<span class="slider round"></span>--><!---->
     </div>
     <hr />
        <div class="accordion" id="NewsScadAccHP">
            <?php foreach($news as $row){

                      //$dtN = $row['dtNews'];
                      //$dtN = strtotime($dtN);
                      //$dtN = date("d/m/Y H:i", $dtN);		//formatta la data
                      if($row['scad'] == 1){
            ?>
            <div class="accordion-item">

                <h2 class="accordion-header">

                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#n<?php echo $row['idNw']?>" aria-expanded="true" aria-controls="n<?php echo $row['idNw']?>">
                        <span class="text-muted">
                            <?php	echo $row['sdtNews'];?>
                        </span>
                        <h6>
                             &nbsp;&nbsp;&nbsp;<?php	echo $row['title'];		?>
                        </h6>
                    </button>
                </h2>
                <div id="n<?php echo $row['idNw']?>" class="accordion-collapse collapse" data-bs-parent="#NewsScadAccHP">
                    <div class="accordion-body">
                        <strong><?php	echo $row['title'];		?></strong><br/><?php	echo $row['text'];?>
                    </div>
                </div>
            </div>
            <?php }
                  } ?>
        </div>
     </div>

        <!--</div>-->

    </section>
</div>

<script src="js/news.js"></script>
<!--<script src="js/expandtext.js"></script>-->

