<?php   
/* 

tream_recon_file.php - TREAM reconciliation for the given file type
  
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

Copyright (c) 2013-2015 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>

http://tream.biz
*/ 

include_once './tream_db_adapter.php';
include_once './tream_db.php';
include_once './tream_files.php';
include_once './tream_messages.php';

$file_id = $_GET['file_id'];
$debug_in = $_GET['debug'];

if ($debug_in == 1) {
  $debug = TRUE;
} else {  
  $debug = FALSE;
}

// main batch job 
echo 'TREAM - start of reconciliation for files of type '.$file_id.' at '.date('Y-m-d H:i:s').'<br><br>';

$link = sql_open($debug);

if ($file_id > 0) {
  echo file_loop($file_id, $debug);
}

// add check of all trades where the currency is not set

sql_close($link);

if ($status == 0) {
  echo '    done';
} else {
  echo '    errors';
}
 
?>
