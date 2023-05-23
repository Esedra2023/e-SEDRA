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


function wr_getCommentCanceled(){
    $_POST['notajax']=1;
    include(ROOT_PATH . '/ajax/getcommenticancellati.php');
    return $comcanc;
}

function wr_viewDefGradBis($field){
    $_POST['notajax']=1;
    $_POST['field']=$field;
    include(ROOT_PATH . '/ajax/getgradbis.php');
    return $gradb;
}

function wr_viewDefGradPro($field,$ruolo){
    $_POST['notajax']=1;
    $_POST['field']=$field;
    $_POST['ruolo']=$ruolo;
    include(ROOT_PATH . '/ajax/getgradpro.php');
    return $gradb;
}

function wr_getBisResultPolling($role,$saveg,$field)
{
    $_POST['notajax']=1;
    //$_POST['table']=$table;
    $_POST['role']=$role;
    $_POST['savegrad']=$saveg;
    $_POST['field']=$field;
    include(ROOT_PATH . '/ajax/getbispollresults.php');
    return $posts;
}

function wr_getProResultPolling($role,$saveg,$field)
{
    $_POST['notajax']=1;
    //$_POST['table']=$table;
    $_POST['role']=$role;
    $_POST['savegrad']=$saveg;
    $_POST['field']=$field;
    include(ROOT_PATH . '/ajax/getpropollresults.php');
    return $posts;
}


function wr_getAllPosts( $role,$table)
{
    $_POST['notajax']=1;
    $_POST['table']=$table;
    $_POST['role']=$role;

    include(ROOT_PATH . '/ajax/getallposts.php');
    return $posts;
}
function wr_getCina($act)
{
    $_POST['notajax']=1;
    if($act == 103)
        include(ROOT_PATH . '/ajax/getcina.php');
    else include(ROOT_PATH . '/ajax/getcinaP.php');
    return $CIna;
}

function wr_getNews($idu,$idn,$edit)
{
    $_POST['notajax']=1;
    $_POST['idUs']=$idu;
    $_POST['idNw']=$idn;
    $_POST['edit']=$edit;
    include(ROOT_PATH . '/ajax/getnews.php');
    return $news;
}

function wr_chkvalut($act,$altridati)
{
    $_POST['notajax']=1;
    $_POST['Act']=$act;
    include(ROOT_PATH . '/ajax/chkvalut.php');
   //if($altridati==0)
   //    return chkVoti; //votazione semplice vettore vuoto
   //else
       return $chkVal;  //graduatoria vettore con numero max stelle anche per semplice
}

?>