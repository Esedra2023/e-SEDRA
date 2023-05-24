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

<!DOCTYPE html>

<?php session_start();
      if (!defined('ROOT_PATH'))
          define ('ROOT_PATH', realpath(dirname(__FILE__)));
      require_once (ROOT_PATH.'/include/functions.php');
      if(!loadIniFile() || !$_SESSION['ini']['install']) redirect('setup.php');

      $_POST['page']=0; //dummy per creare condizione apertura pagine
?>
<html lang="it-it" class="h-100">
<head>
    <title>e-SEDRA</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
 <!--favicon-->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png" />
    <link rel="manifest" href="site.webmanifest" />
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#5bbad5" />
    <link rel="shortcut icon" href="favicon.ico" />
    <meta name="msapplication-TileColor" content="#da532c" />
    <meta name="msapplication-config" content="browserconfig.xml" />
    <meta name="theme-color" content="#ffffff" />

<!--favicon end-->

    <script
        src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
        integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg"
        crossorigin="anonymous" />
    <!-- Google Fonts per banner nella home-->
    <!--<link href="https://fonts.googleapis.com/css?family=Averia+Serif+Libre|Noto+Serif|Tangerine" rel="stylesheet" />-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />

    <link href="css/custom.css" rel="stylesheet" />
    <link href="css/common.css" rel="stylesheet" />
    <link href="css/home.css" rel="stylesheet" />

    <script src="js/functions.js"></script>
    <script src="js/fetch-data-loader.min.js"></script>
    <!--<script src="js/index.js"></script>-->
    <script src="js/webcmp.js"></script>

</head>
<body class="d-flex flex-column h-100">

    <?php require_once ROOT_PATH.'/include/menu.php';?>
    <!--<div class="wrapper ">--><!--flex-shrink-0-->

    <div id="contentPage" class="flex-shrink-0">
        <!-- PAGINE -------------- -->

        <?php
        //echo(ROOT_PATH);

    //if($nouser)
    //{
    //    include ROOT_PATH  .'/pages/home.php';
    //}
    //else
    //{
    if(!isset($_SESSION['user']) || $_SESSION['user']['menuAct'] == 0) include ROOT_PATH .'/pages/home.php';
    else if($_SESSION['user']['menuAct'] == 1) include ROOT_PATH .'/pages/bisognibase.php';
    else if($_SESSION['user']['menuAct'] == 2) include ROOT_PATH .'/pages/propostebase.php';
    else if($_SESSION['user']['menuAct'] == 3) include ROOT_PATH .'/adminsez/admin/admconfgen.php';
    else if($_SESSION['user']['menuAct'] == 4) include ROOT_PATH .'/adminsez/admin/admconfact.php';
    else if($_SESSION['user']['menuAct'] == 5) include ROOT_PATH .'/adminsez/admin/topics.php';
    else if($_SESSION['user']['menuAct'] == 6) include ROOT_PATH .'/adminsez/admin/users.php';
    else if($_SESSION['user']['menuAct'] == 7) include ROOT_PATH .'/adminsez/admin/admlogs.php';
    else if($_SESSION['user']['menuAct'] == 8) include ROOT_PATH .'/pages/profile.php';

    //}
        ?>
    </div><!-- FINE PAGINE -------------- -->
    <!--</div>-->

    <?php
    require_once ROOT_PATH. '/include/footer.php';

    ?>

    <!--<script type="module" src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>-->
    <!--<script type="module" src="/js/bootstrap.bundle.min.js"></script>-->
    <script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>

