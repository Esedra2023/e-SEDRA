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

define ('ROOT_PATH', __DIR__);
$lenroot=strlen($_SERVER['DOCUMENT_ROOT']);
$lendir=strlen(__DIR__);
if($lenroot==$lendir && $_SERVER['DOCUMENT_ROOT']==__DIR__)
{
    //localhost su Windows
    $dir='/';
}
else if($lendir>$lenroot)
{
    //sottocartella della document root
    $dir=substr(__DIR__,$lenroot-1);
}
else if(substr($_SERVER['DOCUMENT_ROOT'],$lendir)==__DIR__)
{
    // root
    $dir=substr($_SERVER['DOCUMENT_ROOT'],$lenroot-1);
}

require_once ROOT_PATH.'/include/functions.php';
if(loadIniFile() && $_SESSION['ini']['install']) redirect('index.php');
?>
<!DOCTYPE html>
<html lang="it-it">
<head>
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
   <script
          src="https://code.jquery.com/jquery-3.6.1.min.js"
          integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
          crossorigin="anonymous">
   </script>
    <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="css/custom.css" />

 <link rel="stylesheet" href="css/setup.css" />

 
 <script src="js/functions.js"></script>   
    
    <title>Installazione</title>
</head>
<body class="d-flex flex-column h-100">
    <div class="myheader d-flex align-items-center mb-3">
        <div class="flex-shrink-0">
            <img id="appLogo" src="images/logo.png" title="logo esedra compatto" />
           
        </div>
        <div class="flex-grow-1 ms-3 row align-items-center">
        <img id="appText" src="images/esedratext2.png" title="scritta esedra" />
            </div>
        <div class="flex-grow-1 ms-3 row align-items-end">
       <h2 class="col-md-3">Configurazione</h2> 
        </div>
    </div>

<div class="flex-shrink-0">   
 
 <div id="main" class="container">  
      
    <form name="form row g-3 gap-3">
        
        <fieldset class="row gx-5 gy-3 align-items-end" form="form">

            <legend class="col-md-2">Database</legend>
            <div class="col-md-5">
                <label  class="form-label" for="dbms"><span>* </span>DBMS: </label>
                <select class="form-select" id="dbms" name='DBMS'>
                    <option disabled selected hidden >Seleziona il DBMS </option>      <!--style="color:gray;"-->
                    <option value="My SQL">My SQL</option>
                    <option value="SQL Server">SQL Server</option>
                    <option value="SQL Server Express LocalDB">SQL Server Express LocalDB</option>
                </select>
            </div>
            
            <div class="col-md-5">
                <label class="form-label" for="host"><span>* </span>Server database: </label>
                <input  class="form-control" id="host" name="HOST" type="text" placeholder="servername | IP">
            </div>
             <div class="msg col-md-2">
             </div>
             <div class="col-md-5">
                <label  class="form-label" for="db"><span>* </span>Nome del database: </label>
                <input  class="form-control"id="db" name="DB" type="text">
            </div>
            
           
             <div class="col-md-5 align-items-end">
               
                <input  type="checkbox" class="form-check-input" id="dbEx" name="DBEX">
                 <label class="form-label" for="dbEx">Database gi&agrave; esistente</label>
            </div>
             <div class="msg col-md-2">
                    * campi obbligatori
                </div>
             <div class="col-md-5">
                <label class="form-label"  for="usn"><span>* </span>Username: </label>
                <input  class="form-control"id="usn" name="USN" type="text">
                  </div>
             <div class="col-md-5">
                <label  class="form-label" for="psw"><span>* </span>Password: </label>
                <input class="form-control" id="psw" name="PSW" type="password">
             </div>
            
        </fieldset >
       <div class="mb-2"><hr /></div>
        <fieldset class="row g-5 align-items-end" form="form">
            <legend class="col-md-2">Account admin</legend>
             <div class="col-md-4">
                <label class="form-label" for="mailAdm"><span>* </span>E-mail: </label>
                <input class="form-control" id="mailAdm" name="MAILAD" type="email" required>
             </div>
             <div class="col-md-3">
                <label class="form-label" for="pswAdm"><span>* </span>Password: </label>
                <input class="form-control" id="pswAdm" name="PSWAD" type="password" required>
             </div>
             <div class="col-md-3">
                <label class="form-label" for="rpswAdm"><span>* </span>Ripeti password: </label>
                <input class="form-control" id="rpswAdm" type="password" required>
             </div>
        </fieldset>
         <div class="mb-2"><hr /></div>
        <fieldset class="row g-5 align-items-end" form="form">
            <legend   class="col-md-2">Personalizzazione</legend>
             <div class="col-md-4">
                   <div title="E-mail" data-bs-toggle="tooltip" data-bs-placement="top"  class="input-group">
                <div class="input-group-text"><span  class="fa fa-envelope"/></div>
                <input  class="form-control" id="emailLink" name="emailLink" type="text" placeholder="Indirizzo e-Mail">
                </div>
             </div>
             <div class="col-md-3">
                <div title="Social" data-bs-toggle="tooltip" data-bs-placement="top"  class="input-group">
                <div  class="input-group-text"><span class="fa fa-users"></span></div>
                <input  class="form-control" id="socialLink" name="socialLink" type="text" placeholder="Link Social Network">
                </div>
             </div>
             <div class="col-md-3">
                 <div title="Sito Web" data-bs-toggle="tooltip" data-bs-placement="top"  class="input-group">
               <div class="input-group-text"><span class="fa fa-globe"></span></div>
                     <input  class="form-control" id="webLink" name="webLink" type="text" placeholder="Indirizzo Sito Web"/>
                 </div>
             </div>
        </fieldset>
        <div class="mb-2"><hr /></div>
        <fieldset class="row g-5 align-items-end">
            <legend class="col-md-2">Percorso</legend>
            <div class="col-md-4">             
                <input  type="checkbox" class="form-check-input" id="domRoot" name="domRoot" disabled <?php if(strlen($dir)==1 && $dir[0]=='/') echo 'checked';?>>
                 <label class="form-label" for="domRoot">Dominio/sottodominio</label>
            </div>
             <div class="col-md-6">
               <label class="form-label" for="cartRoot">Cartella:</label>
                <input class="form-control" id="cartRoot" name="cartRoot" type="text" value="<?php echo $dir;?>" disabled/> 
                <input type="hidden" id="myRoot" name="myRoot" value="<?php echo ROOT_PATH;?>" disabled />
             </div>
        </fieldset>
        <!-- PROGRESS BAR -->
        <div class="progress  mt-3">
             <div id="myBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Avanzamento creazione tabelle db"  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
         </div>  
        <div id="msgProgress">Inizializzazione...</div>
       
        <!-- FINE PROGRESS BAR -->
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
        <input class="btn btn-primary" id="installa" name="install" type="submit" value="Installa"/>
        <input class="btn btn-primary" id="login" type="button" value="Avvia Applicazione"/>
    </div>
    </form>
     </div>
    
</div>  <!--chiusura wrapper-->
    <?php include ROOT_PATH. '/include/footer.php'; ?>
     
    <script src="js/setup.js"></script> 

    <script src="js/bootstrap.bundle.min.js"></script>

    </body>
</html>