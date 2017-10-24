<html>
 <head>
  <title>TREAM check run</title>
 </head>
 <body>
 <?php 
 
/*  

check.php - Check script that fixes automatically some issues or creates a message for the user

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

// to do: check that security exposure is not greater 100%

define("THIS_SCRIPT_VERBOSE_LEVEL", 2); // 0 = show only actions, 1 = show warnings also, 2 = show also infos

include './tream_db_adapter.php';
include './tream_db.php';
include './tream_mm_link.php';
include './tream_jb_link.php';
include './tream_cs_link.php';

$const_birthday_limit = 10; 
 
$status = 0;

// checks all data in the database if market conventions are done and corrects the data if needed
// e.g. an ISIN cannot have spaces
// maybe this should be moved to the masks
function check_market_conventions() {
  $result  = 'check market conventions';
  mysql_query("UPDATE securities SET ISIN = trim(ISIN) WHERE ISIN <> trim(ISIN);");
  // merge dublicate ISINs - the field ISIN is not unique because sometimes it is missing, 
  // but if the same ISIN is twice in the system in should suggest an merger
  $result .= '<br><br>';
  return $result;
}

// closed all non updated events
function event_close_old() {
  $result  = 'close all non confirmed events';
  $result .= mysql_query("UPDATE `events` SET event_status_id = ".EVENT_STATUS_CLOSED.", closed = NOW() WHERE updated < DATE_SUB(NOW(),INTERVAL 2 DAY) AND closed IS NULL;");
  // merge dublicate ISINs - the field ISIN is not unique because sometimes it is missing, 
  // but if the same ISIN is twice in the system in should suggest an merger
  $result .= '<br><br>';
  return $result;
}



// main batch job 
echo 'Open Source EAM tool - start of main daily check job at '.date('Y-m-d H:i:s').'<br><br>';

$yr = file_get_contents("https://tream.biz/batch/tream_get_security_from_yahoo.php");

echo $yr.'.';

/*$link = sql_open();


echo sql_table_check();

echo check_market_conventions();

echo event_close_old();

echo event_check_birthdays($link);

echo mm_instrument_import();

echo jb_trade_import();

echo jb_position_import();

echo cs_position_import();

// add check of all trades where the currency is not set

sql_close($link);
*/
if ($status == 0) {
  echo '    done';
} else {
  echo '    errors';
}
 
 ?> 
 </body>
</html> 
