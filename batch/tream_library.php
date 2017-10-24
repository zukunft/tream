<?php 

/*  

tream_lib.php - functions that are used several times in TREAM 

Setup scripts
-------------
install.php - Install wizard for TREAM and it also contains an overview about the script names


This file is part of TREAM - Portfolio Management Software.

TREAM is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of
the License, or (at your option) any later version.

TREAM is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with TREAM. If not, see <http://www.gnu.org/licenses/gpl.html>.

To contact the authors write to: 
Timon Zielonka <timon@zukunft.com>

Copyright (c) 2013-2017 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>

http://tream.biz
*/ 

include_once './tream_db_adapter.php';
include_once './tream_db.php';

// General TREAM functions that should be used by all batch scripts

// start a tream batch job - open the database connection and check if permitted
function batch_start(){
} 

// TREAM security functions

// check if the user really has called the batch script from the GUI to avoid batch job starts without user identification
function batch_permitted($user_id, $secure_id){
} 


// function that should be moved to the php standard

function csv_explode($delim=',', $str, $enclose='"', $preserve=false){
  $resArr = array();
  $n = 0;
  $expEncArr = explode($enclose, $str);
  foreach($expEncArr as $EncItem){
    if($n++%2){
      array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:''));
    }else{
      $expDelArr = explode($delim, $EncItem);
      array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
      $resArr = array_merge($resArr, $expDelArr);
    }
  }
  return $resArr;
} 

?> 
