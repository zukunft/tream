<?php
/* 

tream_messages.php - TREAM Check securities

Checks for example if one security include in a portfolio has a strong move and inform the portfolio manager in this case

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

Copyright (c) 2013-2015 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>

http://tream.biz
*/ 

include_once 'batch/tream_db_adapter.php';
include_once 'batch/tream_db.php';
include_once 'batch/tream_messages.php';

//$debug = TRUE;
$debug = FALSE;


// returns a description of the open trades related to a security (in a portfolio)
// including option positions !?!
function tream_trades_related($security_id, $portfolio_id, $debug) {
  $result = '';

  return $result;
}

// loop over the securities of a portfolio and check if one has done a strong move
function tream_check_portfolio_security_moves($debug) {
  tream_debug ('check_portfolio_security_moves ... ', $debug);
  $result = '';

  $link = sql_open();

  // get securities
  $sql_securities_to_monitor = "SELECT pos.portfolio_id, pos.security_id, pos.pos_value_ref, pos.position, pos.bid, pos.ask, pos.last, pos.ref_decimals FROM v_portfolio_pos pos, portfolios p WHERE pos.pos_value_ref <> 0 AND p.monitoring = 1 AND pos.portfolio_id = p.portfolio_id;";
  $result_securities_to_monitor = mysql_query($sql_securities_to_monitor);
  while ($security_row = mysql_fetch_assoc($result_securities_to_monitor)) {
    $portfolio_id = $security_row['portfolio_id'];
    $security_id  = $security_row['security_id'];
    $portfolio_name = sql_get_value("portfolios", "portfolio_id", $portfolio_id, "portfolio_name");
    $security_name  =sql_get_value("securities", "security_id", $security_id, "name");
    $sec_value  = $security_row['pos_value_ref'];
    $sec_position = $security_row['position'];
    $sec_decimals = $security_row['ref_decimals'];
    $sec_bid  = $security_row['bid'];
    $sec_ask  = $security_row['ask'];
    $sec_last  = $security_row['last'];
    $portfolio_limit = sql_get_value("portfolios", "portfolio_id", $portfolio_id, "monitoring_security_limit");
    $security_limit = sql_get_value("securities", "security_id", $security_id, "monitoring_security_limit");
    
    // in case a new trade has been entered reset the protfolio value (or adjust the protfolio value with the new trade)
    $bo_status = sql_get("SELECT MAX(bo_status) FROM trades WHERE portfolio_id = ".$portfolio_id." AND security_id = ".$security_id.";");
    if ($bo_status > 0) {
      $sql_update = "UPDATE portfolio_security_fixings SET fixed_price = '".$sec_value."' WHERE portfolio_id = '".$portfolio_id."' AND security_id = '".$security_id."';";
      $result .= mysql_query($sql_update);
      $sql_update = "UPDATE trades SET bo_status = 0 WHERE portfolio_id = '".$portfolio_id."' AND security_id = '".$security_id."';";
      $result .= mysql_query($sql_update);
    }
    
    if ($security_limit > 0) {
      $used_limit = $security_limit;
    } else {
      $used_limit = $portfolio_limit;
    }
    echo "Add p ".$portfolio_id." s ".$security_id." with ".$sec_value.". ";
    echo 'check limit of '.$used_limit.', used price '.$sec_bid.'-'.$sec_ask.' '.$sec_last.'), ';
    $last_value = sql_get("SELECT fixed_price FROM portfolio_security_fixings WHERE portfolio_id = ".$portfolio_id." AND security_id = ".$security_id.";");
    if ($used_limit > 0) {
      if ($last_value == '') {
	echo 'insert not log';
	$result = mysql_query("INSERT INTO portfolio_security_fixings (portfolio_id, security_id, fixed_price) VALUES ('".$portfolio_id."', '".$security_id."', '".$sec_value."');");
      } else {
        if ($last_value <> 0) {
	  $change_in_pct = ($sec_value - $last_value) / $last_value * 100; // $last_value is the sec value when the last message has been sent
	} else {  
	  $change_in_pct = 0;
	}
	echo 'check limit of '.$used_limit.', change '.round($change_in_pct,$sec_decimals).'% (used price '.$sec_bid.'-'.$sec_ask.' '.$sec_last.'), ';
	if (abs($change_in_pct) > $used_limit) {
	  echo 'send message';
	  // maybe create a function for the next line in tream_messages.php
	  $portfolio_manager = sql_get("SELECT contact_number FROM v_portfolio_persons WHERE portfolio_id = ".$portfolio_id." AND portfolio_function_code_id = '".ACCOUNT_PERSON_TYPE_PM."';");
	  echo 'to '.$portfolio_manager;
	  $portfolio_manager_deputy = sql_get("SELECT contact_number FROM v_portfolio_persons WHERE portfolio_id = ".$portfolio_id." AND portfolio_function_code_id = '".ACCOUNT_PERSON_TYPE_DPM."';");
	  echo ' and to '.$portfolio_manager_deputy;
	  $mail_subject = "TREAM: ".$security_name." moved ".round($change_in_pct,$sec_decimals)."% (portfolio ".$portfolio_name.")";
	  $mail_msg = "".$security_name." moved ".round($change_in_pct,$sec_decimals)."% and is now trading at ".round($sec_last,$sec_decimals)." (position ".round($sec_position,$sec_decimals)." in portfolio ".$portfolio_name.")";
	  echo msg_email($portfolio_manager, $mail_subject, $mail_msg);
	  echo msg_email($portfolio_manager_deputy, $mail_subject, $mail_msg);
	  echo 'update value';
	  $sql_update = "UPDATE portfolio_security_fixings SET fixed_price = '".$sec_value."' WHERE portfolio_id = '".$portfolio_id."' AND security_id = '".$security_id."';";
	  //echo $sql_update;
	  $result .= mysql_query($sql_update);
	}
      }
    } 
    echo "<br>";
  }

  sql_close($link);
  
  tream_debug ('check_portfolio_security_moves ... done: '.$result, $debug);
  return $result;
}

// send an email if a security has reached an trigger
function msg_sec_triggers($debug) {
  $link = sql_open();

  $result = '';
  tream_debug('msg_sec_triggers ...', $debug);

  // loop over the open triggers and set new triggers to status monitoring
  $sql_result  = sql_result("SELECT * FROM v_security_triggers_open WHERE status_code_id <> '". sql_code_link(SEC_TRIGGER_STATUS_ACTIVE).   "' ".
			                                            " AND status_code_id <> '". sql_code_link(SEC_TRIGGER_STATUS_TRIGGERED)."' ".
			                                            " AND status_code_id <> '". sql_code_link(SEC_TRIGGER_STATUS_CLOSED).   "' ;");
  while ($trigger_row = mysql_fetch_assoc($sql_result)) {
    if ($trigger_row['status_code_id'] == SEC_TRIGGER_STATUS_NEW OR $trigger_row['status_code_id'] == NULL OR $trigger_row['status_code_id'] == '') {
      $result .= sql_set("security_triggers", "security_trigger_id", $trigger_row['security_trigger_id'], "trigger_status_id", sql_code_link(SEC_TRIGGER_STATUS_ACTIVE), "");
    }
  }  

  // loop over the active triggers and check it
  $sql_result  = sql_result("SELECT * FROM v_security_triggers_open WHERE status_code_id = '". sql_code_link(SEC_TRIGGER_STATUS_ACTIVE).   "';");
  while ($trigger_row = mysql_fetch_assoc($sql_result)) {

    // prepare
    $event_id = 'sec trigger '.$trigger_row['security_trigger_id'].'';
    $event_msg = 'Security '.$trigger_row['sec_name'].' ('.$trigger_row['ISIN'].') has reached the '.$trigger_row['type_name'].' level  of '.$trigger_row['trigger_level'];
    $portfolio_manager = sql_get("SELECT contact_number FROM v_portfolio_persons WHERE portfolio_id = ".$trigger_row['portfolio_id']." AND portfolio_function_code_id = '".ACCOUNT_PERSON_TYPE_PM."';");
    $portfolio_manager_deputy = sql_get("SELECT contact_number FROM v_portfolio_persons WHERE portfolio_id = ".$trigger_row['portfolio_id']." AND portfolio_function_code_id = '".ACCOUNT_PERSON_TYPE_DPM."';");
    tream_debug('msg_sec_triggers ... inform portfolio manager '.$portfolio_manager.' and '.$portfolio_manager_deputy, $debug);

    if ($trigger_row['type_code_id'] == SEC_TRIGGER_STATUS_ACTIVE) {
    
      $triggered = FALSE;

      // check take profit 
      if ($trigger_row['type_code_id'] == SEC_TRIGGER_TAKE_PROFIT AND $trigger_row['last_price'] >= $trigger_row['trigger_value']) {
	$triggered = TRUE;
      }

      // check stop loss 
      if ($trigger_row['type_code_id'] == SEC_TRIGGER_STOP_LOSS AND $trigger_row['last_price'] <= $trigger_row['trigger_value']) {
	$triggered = TRUE;
      }

      // send message
      if ($triggered == TRUE) {
	tream_debug('msg_sec_triggers ... triggered '.$trigger_row['sec_name'], $debug);
	$result .= msg_email($portfolio_manager, $event_msg, $event_msg);
	$result .= msg_email($portfolio_manager_deputy, $event_msg, $event_msg);
	$result .= event_add($event_id, $event_msg, EVENT_TYPE_EXPOSURE_LIMIT, date('Y-m-d'), "", "", "", "", 0, $trigger_row['portfolio_id']);
	$result .= sql_set("security_triggers", "security_trigger_id", $trigger_row['security_trigger_id'], "trigger_status_id", sql_code_link(SEC_TRIGGER_STATUS_TRIGGERED), "");
      }
    }
  }  

  sql_close($link);

  return $result;
}


//echo tream_check_portfolio_security_moves($debug);
?> 
