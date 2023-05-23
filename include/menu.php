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

<link href="css/menu.css" rel="stylesheet" />
<header id="header" class="fixed-top  bg-primary">
    <div class="container d-flex align-items-center">

        <a class="logo me-auto">
            <img id="appText" src="images/esedratext1.png" title="logoEsedra" />
            <!--<a href="index.html">e-SEDRA</a>-->
        </a>
        <a class="logo me-auto">
            <img src="images/logo.png" alt="" class="img-fluid" />
        </a>

        <nav id="navbar" class="navbar order-last order-lg-0">
            <!--<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>-->
            <!--<div class="mainMenu" id="navbarMenu">-->
            <ul>
                <?php if(isset($_SESSION['user'])) {
                          $mact=$_SESSION['user']['menuAct'];
                          // myfunctiontest();?>
                <li>
                    <a class="<?php if($mact == 0) echo 'active'; ?> text-decoration-none" href="pages/home.php" id="0">Home</a>
                </li>
                <li>
                    <a class="<?php if($mact == 1) echo 'active'; ?> text-decoration-none" href="pages/bisognibase.php" id="1">Bisogni</a>
                </li>
                <li>
                    <a class="<?php if($mact == 2) echo 'active'; ?> text-decoration-none" href="pages/propostebase.php" id="2">Proposte</a>
                </li>
                <?php
                if(array_key_exists(1, $_SESSION['user']['roles'])) { //utente è amministratore
                ?>
                <li class="dropdown">
                    <a href="javascript:void(0);" class="no_link text-decoration-none">
                        <span>Configurazione</span><i class="bi bi-chevron-down"></i>
                    </a>
                    <ul>
                        <li>
                            <a class="<?php if($mact == 3) echo 'active'; ?> text-decoration-none" href="adminsez/admin/admconfgen.php" id="3">Generale</a>
                        </li>
                        <li>
                            <a class="<?php if($mact == 4) echo 'active'; ?> text-decoration-none" href="adminsez/admin/admconfact.php" id="4">Attivit&agrave;</a>
                        </li>
                        <li>
                            <a class="<?php if($mact == 5) echo 'active'; ?> text-decoration-none" href="adminsez/admin/topics.php" id="5">Ambiti</a>
                        </li>
                        <li>
                            <a class="<?php if($mact == 6) echo 'active'; ?> text-decoration-none" href="adminsez/admin/users.php" id="6">Utenti</a>
                        </li>
                        <li>
                            <a class="<?php if($mact == 7) echo 'active'; ?> text-decoration-none" href="adminsez/admin/admlogs.php" id="7">LogFile</a>
                        </li>
                    </ul>
                </li>
                <?php }
                      }?>

                <?php if(isset($_SESSION['user'])) {?>

                <li class="dropdown">
                    <a href="javascript:void(0);" class="no_link text-decoration-none">
                        <i class="bi bi-person-fill"></i>
                        <span>
                            &nbsp;<?php echo $_SESSION['user']['nome'].' '.$_SESSION['user']['cognome'];?>
                        </span>
                    </a>
                    <ul>
                        <!--<li>
                            <a class="text-decoration-none" href="pages/revisore.php" id="8">Revisione</a>
                        </li>-->
                        <li>
                            <a class="<?php if($mact == 8) echo 'active'; ?> text-decoration-none" href="pages/profile.php" id="8">Profilo</a>
                        </li>
                        <li>
                            <a class="text-decoration-none" href="include/logout.php" id="m_logout">Esci</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle" id="mobileMenu"></i>
            <?php } ?>
        </nav><!--.navbar-->
        <?php if(!isset($_SESSION['user'])) {?>  
            <a href="" id="logButton" class="text-decoration-none"><span class="bi bi-box-arrow-in-right"></span>&nbsp;Accedi</a>
        <?php }?>
  
    </div>
</header><!--End Header-->
<script>

        /**
   * Easy selector helper function
   */
const myselect = (el, all = false) => {
    el = el.trim()
    if (all) {
        return [...document.querySelectorAll(el)]
    } else {
        return document.querySelector(el)
    }
}

/**
 * Easy event listener function
 */
const myon = (type, el, listener, all = false) => {
    let selectEl = myselect(el, all)
    if (selectEl) {
        if (all) {
            selectEl.forEach(e => e.addEventListener(type, listener))
        } else {
            selectEl.addEventListener(type, listener)
        }
    }
}
        /**
   * Mobile nav toggle
   */
    myon('click', '.mobile-nav-toggle', function (e) {
   /*     alert('clic su mobile');*/
    myselect('#navbar').classList.toggle('navbar-mobile')
    this.classList.toggle('bi-list')
    this.classList.toggle('bi-x')
  })

  /**
   * Mobile nav dropdowns activate
   */
  myon('click', '.navbar .dropdown > a', function(e) {
    if (myselect('#navbar').classList.contains('navbar-mobile')) {
      e.preventDefault()
      this.nextElementSibling.classList.toggle('dropdown-active')
    }
  }, true)


    $(document).ready(function () {
// -----  LOGIN/LOGOUT -----
    $('#logButton').click(function (event) {
        /*alert("clic su logButton");*/
        event.preventDefault();
        $.get('include/login.php', function (response) {
            $('header').append(response).find('#login-page').fadeIn(300);
        });
    });

    // -----  MENU PAGINE UTENTE ----

    $('#navbar a').click(function (event) {
       /* alert($(this));*/
        //if ($(this).prop('id') === "logButton") {
        //    /*alert("login passa qui " + $(this).prop('id'));*/
        //    event.preventDefault();
        //}
        //if ($(this).prop('id') === "m_logout")
        //{
        //    /*alert("logout passa qui " + $(this).prop('id'));*/
        //    /*event.preventDefault();*/
        //}
        //else
        if (!$(this).hasClass("no_link")) {     /*BARBARA aggiunto per menu dropdown*/
            /* alert("altri menu " + $(this).prop('id'));*/
            if ($(this).prop('id') != "logButton" && $(this).prop('id') != "m_logout") {
                /*waitIcon('#contentPage');*/ //js function per icona loader
                // carica <section id="#contentPage" con pagina php
                //alert($(this).prop('href'));
                $('#contentPage').load($(this).prop('href'), { page: 99 });
                //gestisce visualizzazione scelta menu
                $(".active").removeClass("active");
                $(this).addClass("active");
               /* underline($(this));*/
                //aggiorna nel DB la pagina utente corrente
                $.post("ajax/updmenu.php", { menu: $(this).prop('id') }); //$(this).index -mettere #id al menu
                if ($('#navbar').hasClass("navbar-mobile")) {
                    el = document.getElementById("mobileMenu");
                    simulateClick(el);
                }
                event.preventDefault();
            }
        }
    }); //$('.mainMenu a').click
    }); //fine $(document).ready

</script>