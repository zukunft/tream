<?php 
 
/* 

tream_files_test.php - Link to test files

TO DO: 
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

define("THIS_SCRIPT_PERSON_ID", 24);

define("RECON_STEP_TYPE_ADD", 1);               // move this const to two functions: get row id and create row if needed
define("RECON_STEP_TYPE_ADD_VALUE", 2);         // move this const to two functions: get row id and create row if needed
define("RECON_STEP_TYPE_REF", 3);               // move this const to two functions: get row id and create row if needed
define("RECON_STEP_TYPE_REF_MULTI", 4);         // move this const to two functions: get row id and create row if needed
define("RECON_STEP_TYPE_REF_MULTI_SUGGEST", 6); // move this const to two functions: get row id and create row if needed
define("RECON_STEP_TYPE_REF_MULTI_REMOVE", 8);  // move this const to two functions: get row id and create row if needed
define("RECON_STEP_TYPE_GET", 5);               // move this const to two functions: get row id and create row if needed
define("RECON_STEP_TYPE_COMPARE", 7);           // move this const to two functions: get row id and create row if needed

define("RECON_VALUE_TYPE_STRING", 1);   // move this const to two functions: get row id and create row if needed
define("RECON_VALUE_TYPE_INT", 2);      // move this const to two functions: get row id and create row if needed
define("RECON_VALUE_TYPE_REF", 3);      // move this const to two functions: get row id and create row if needed
define("RECON_VALUE_TYPE_DATE", 4);     // move this const to two functions: get row id and create row if needed
define("RECON_VALUE_TYPE_NUM", 5);      // move this const to two functions: get row id and create row if needed

define("RECON_MAKER_EXTERNAL_ID", "%external_id%");      //
define("RECON_MAKER_START", "%$");      //
define("RECON_MAKER_END", "$%");      //


// do the reconciliation for one file
function file_read($file_name, $debug) {
  echo 'file_read ... <br>';
  $fh = fopen($file_name,'r');
  $file_line = fgets($fh,1521);
  echo 'file_read ... load line ...'.$file_line.'<br>';
  while ($file_line = fgets($fh,1521)) {
    $line[] = $file_line;
    echo 'file_read ... load line ...'.$file_line.'<br>';
    $result .= 'get line '.$file_line.'<br>';
    //$ref_values[] = 0; // add a dummy value to create the array ( maybe this be be removed later)
    $line_status[] = 0;
  }
  fclose($fh);
 
  echo 'file_read ... done: '.$result.'<br>';
  return $result;
}
echo 'test_start<br>';
file_read('/home/data/tream-data/upload/03110729.TRINKLER.POS.201511200258.txt', 1);
echo 'test_end<br>';

?> 
