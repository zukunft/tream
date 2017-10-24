<?php 
 
/* 

tream_allocation_optimizer.php - calculates an optimal asset allocation

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

include_once './tream_db_adapter.php';
include_once './tream_db.php';
include_once './tream_messages.php';
include_once './tream_portfolio_check.php';

define("THIS_SCRIPT_VERBOSE_LEVEL", 2); // 0 = show only actions, 1 = show warnings also, 2 = show also infos



// main batch job to check all
function tream_allocation_optimize($display) {
  tream_display ('Suggest an optimal asset allocation as of '.date('Y-m-d H:i:s').'<br><br>', $display);
  // reset to suggestions
  mysql_query('UPDATE `exposure_targets` SET optimized = NULL WHERE optimized IS NOT NULL;');
  // loop over the mandates
  $sql_query_mandates = 'SELECT m.description, c.symbol, t.account_mandat_id, c.currency_id FROM exposure_targets t, account_mandates m, currencies c WHERE t.account_mandat_id = m.account_mandat_id AND t.currency_id = c.currency_id GROUP BY t.account_mandat_id;';
  $mandate_result = mysql_query($sql_query_mandates);
  // loop over the fx
  while ($mandate_row = mysql_fetch_assoc($mandate_result)) {
    tream_display ('Calc for mandate '.$mandate_row['description'].' '.$mandate_row['symbol'].'<br>', $display);
    //tream_check_portfolio($portfolio_row['portfolio_id'], FALSE);
  } 
  tream_display ('Suggest an optimal asset allocation done at '.date('Y-m-d H:i:s').'<br><br>', $display);
}  


$link = sql_open(FALSE);
tream_allocation_optimize(TRUE);
sql_close($link);


?> 
