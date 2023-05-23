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

    <div class="accordion mb-3" id="accordionGrad">
        <div class="accordion-item">
            <h2 class="accordion-header" id="accG">
                <button class="accordion-button" id="bottoneAccGrad" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGrad" aria-expanded="true" aria-controls="collapseGrad" >
                    <span class="bi bi-list-task">&nbsp;Graduatoria Ultima Votazione Bisogni</span>
                </button>
            </h2>
            <div id="collapseGrad" class="accordion-collapse collapse" aria-labelledby="" data-bs-parent="#accordionGrad">
                <div class="accordion-body">
                    <table class="table table-responsive align-middle" id="Gradtable">
                        <!--<caption>Graduatoria Ultima Votazione Bisogni</caption>-->
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Ambito</th>
                                <th>Titolo</th>                               
                                <th>Voti</th>
                                <th>
                                    <i class="bi bi-heart-fill"></i>
                                </th>
                                <th>Votanti</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                foreach ($grad as $key => $pd) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $key + 1; ?>
                                </td>
                                <td>
                                    <?php echo $pd['ambito']; ?>
                                </td>
                                <td>
                                    <?php echo $pd['titleBis']; ?>
                                </td>
                                <td>
                                    <?php echo $pd['grade'];?>
                                </td>
                                <td>
                                    <?php echo $pd['nlike'];?>
                                </td>
                                <td>
                                    <?php echo $pd['votanti']; ?>
                                </td>
                            </tr>
                            <?php
                               }//end for
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>