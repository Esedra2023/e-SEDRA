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

?>
    <table class="table align-middle" id="Protable">
        <thead>
            <tr>
                <th>N</th>
                <th>Titolo</th>
                <th>Proposta</th>
                <th>Voti</th>
                <th>
                    <i class="bi bi-heart-fill"></i>
                </th>
                <th>Data Revisione</th>
                <th>Note Revisore</th>
                <th>Stato</th>
            </tr>
        </thead>

        <tbody>
            <?php $tot=0;
                    if (!empty($posts)){
                        $tot=count($posts);
                        foreach ($posts as $key => $post) {
            ?>
            <tr>
                <td>
                    <?php echo $key + 1; ?>
                </td>
                <td>
                    <button type="button" class="linkstylebutton btn btn-outline-primary text-start" data-idpro="<?php echo  $post['idPr']; ?>" value="<?php echo  $post['idPr'];?>">
                        <?php echo $post['titlePrp']; ?>
                    </button>
                </td>
                <td>
                    <a href="uploadpdf\<?php echo $post['pdfalleg']; ?>" target="_blank">
                        <?php echo $post['pdforigname']; ?>
                    </a>
                </td>

                <td>
                    <?php if(isset($post['grade']) && $post['grade']!=null) echo $post['grade']; else echo '---' ?>
                </td>
                <td>
                    <?php if(isset($post['nlike'])) echo $post['nlike']; else echo'-'; ;?>
                </td>
                <td>
                    <?php if($post['dtRev']!=null) echo date("d-m-Y", strtotime($post['dtRev']));else echo ''?>
                </td>
                <td>
                    <?php echo $post['rev']; ?>
                </td>
                <td>
                    <?php if($post['pubblicato']) echo "<i class='bi bi-check-square'></i>"; else if($post['deleted']==1) echo "<i class='bi bi-trash3'></i>"; else echo "<i class='bi bi-x-circle-fill'></i>"; ?>
                </td>

            </tr>
            <?php
                        }//end for
            }//if
            if($tot==0) {
                echo '<tr><td colspan="8"class="alert alert-primary col-md-12 mt-3 text-center">Nessuna proposta da visualizzare</td></tr>';
            }
            ?>
        </tbody>
    </table>