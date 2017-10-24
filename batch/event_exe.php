<html>
 <head>
  <title>TREAM event execute</title>
 </head>
 <body>
 <?php 
 
/* 

event_exe.php      - called from the p4a user interface to execute one single event

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

define("THIS_SCRIPT_VERBOSE_LEVEL", 2); // 0 = show only actions, 1 = show warnings also, 2 = show also infos

include './tream_db_adapter.php';
include './tream_db.php';
include './tream_mm_link.php';
include './tream_jb_link.php';

$status = 0;

// main batch job 
echo 'Open Source EAM tool - execute events run '.date('Y-m-d H:i:s').'<br><br>';

$link = sql_open();

// check all birthdays in the next days (limit is set as parameter)
function event_execute($link) {
  $result = 'execute events<br><br>';
  $sql    = 'SELECT event_id, solution1_sql, solution2_sql, solution_selected FROM events WHERE solution_selected > 0 AND event_status_id < '.sql_code_link(EVENT_STATUS_DONE).';';
  $sql_result = mysql_query($sql, $link);

  if (!$sql_result) {
      $result .= "DB Error, could not query the database\n";
      $result .= 'MySQL Error: ' . mysql_error();
      exit;
  }

  while ($row = mysql_fetch_assoc($sql_result)) {
    $event_id = $row['event_id'];
    if ($row['solution_selected'] == 1) {
      $sql_to_execute = $row['solution1_sql'];
    }
    if ($row['solution_selected'] == 2) {
      $sql_to_execute = $row['solution2_sql'];
    }
      $result .= 'Event '.$row['event_id'].' is executed: do >'.$sql_to_execute.'<' ;
      $result .= '->status '.sql_set_event_status($event_id, sql_code_link(EVENT_STATUS_DONE));
      mysql_query($sql_to_execute);
      // add mising log insert
      $result .= '->closed at '.sql_set_event_closed($event_id).'<br>';
      $result .= '<br>';
  }

  $result .= '<br><br>';
  return $result;
}

echo event_execute($link);

sql_close($link);

if ($status == 0) {
  echo '    done';
} else {
  echo '    errors';
}
 
 ?> 
 </body>
</html> 
