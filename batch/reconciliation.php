<html>
 <head>
  <title>TREAM reconciliation</title>
 </head>
 <body>
 <?php 
 
/* 

reconciliation.php - Reconciliation script for the TREAM project


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
 
define("THIS_SCRIPT_VERBOSE_LEVEL", 2); // 0 = show only actions, 1 = show warnings also, 2 = show also infos

include_once './php_general_library.php';
include_once './tream_db_adapter.php';
include_once './tream_db.php';
include_once './tream_mm_link.php';
include_once './tream_jb_link.php';
include_once './tream_cs_link.php';
include_once './tream_files.php';

$status = 0;

// main batch job 
echo 'Open Source EAM tool - start of reconciliation job at '.date('Y-m-d H:i:s').'<br><br>';

$link = sql_open();

echo file_loop();

// add check of all trades where the currency is not set

sql_close($link);

if ($status == 0) {
  echo '    done';
} else {
  echo '    errors';
}
 
 ?> 
 </body>
</html> 
