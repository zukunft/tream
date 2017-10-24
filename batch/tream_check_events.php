<?php

/* 

tream_check_events.php - TREAM Check events

Checks if new events must be created or others can be closed

TO DO: 

...

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

include './tream_db_adapter.php';
include './tream_db.php';

function tream_check_event_corporate_action ($portfolio_id) {
  $result = '';
  //$event_type = sql_code_link(EVENT_TYPE_USER_DAILY);
  $result = event_add("event ca ".$portfolio_id, "Please check the coorporate action for portfolio ".$portfolio_id, EVENT_TYPE_USER_DAILY, date('Y-m-d'), "", "", "", "", 0, $portfolio_id);

  return $result;
}

function tream_check_event_cash ($portfolio_id) {
  $result = '';
  $result .= event_add("event cash ".$portfolio_id, "Please check for negative cash in portfolio ".$portfolio_id, EVENT_TYPE_USER_DAILY, date('Y-m-d'), "", "", "", "", 0, $portfolio_id);

  return $result;
}


function tream_check_events() {
  echo "Check events<br>";

  $link = sql_open();
  mysql_select_db('cream');

  // get portfolios
  $sql_portfolios_to_monitor = "SELECT portfolio_id FROM portfolios WHERE monitoring = 1;";
  $result_portfolios_to_monitor = mysql_query($sql_portfolios_to_monitor);
  while ($portfolio_row = mysql_fetch_assoc($result_portfolios_to_monitor)) {
    $portfolio_id = $portfolio_row['portfolio_id'];
    echo tream_check_event_corporate_action($portfolio_id)."<br>";
    echo tream_check_event_cash($portfolio_id)."<br>";
    echo "Check event for portfolio ".$portfolio_id." done.<br>";
  }

  sql_close($link);

  echo "Check events done.<br>";
  
}
?> 
