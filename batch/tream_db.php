<?php 
 
/* 

tream_db.php - Link to the TREAM database

all table specific definitions should be here execpt the general audit functions like sql_log 
all functions return html code

TO DO: 

move sender email to a configuration table

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

// Do to: filter single high quote in sql text before updating the db

define("EVENT_STATUS_CREATED", 1); // move this const to two functions: get row id and create row if needed
define("EVENT_STATUS_DONE", 3);    // move this const to two functions: get row id and create row if needed
define("EVENT_STATUS_CLOSED", 4); 

define("FILE_TYPE_FIXED", 1);
define("FILE_TYPE_XML", 2);

define("TRADE_TYPE_BUY", 1);
define("TRADE_TYPE_SELL", 2);
define("TRADE_TYPE_DELIVERY", 16);
define("TRADE_STATUS_EXECUTED", 5); // move this const to two functions: get row id and create row if needed

define("SQL_TABLE_PK_NBR", 0); // at the moment the first field in a table must always be the primary key


// ----------------
// search functions
// ----------------
function sql_get_security_id_from_ISIN($ISIN) {
  return sql_get_value('securities', 'ISIN', $ISIN, 'security_id');
}

function sql_get_security_id_from_CH_valor($valor) {
  $sec_isin = 'CH'.sprintf('%09d', intval($valor)).'%';
  return sql_get("SELECT security_id FROM securities WHERE ISIN LIKE '".$sec_isin."';");
}

function sql_get_security_id_from_valor($valor) {
  return sql_get_value('securities', 'valor', $valor, 'security_id');
}

function sql_get_security_ISIN($sec_id) {
  return sql_get_value('securities', 'security_id', $sec_id, 'ISIN');
}

function sql_get_event_description($event_id) {
  return sql_get_value('events', 'event_id', $event_id, 'description');
}

function sql_get_event_status($event_id) {
  return sql_get_value('events', 'event_id', $event_id, 'event_status_id');
}

function sql_get_portfolio_name($portfolio_id) {
  return sql_get_value('portfolios', 'portfolio_id', $portfolio_id, 'portfolio_name');
}

function sql_get_portfolio_account($portfolio_id) {
  return sql_get_value('portfolios', 'portfolio_id', $portfolio_id, 'account_id');
}

function sql_get_exposure_type_name($type_id) {
  return sql_get_value('exposure_types', 'exposure_type_id', $type_id, 'type_name');
}

function sql_get_curr_id($curr_symbol) {
  return sql_get_value('currencies', 'symbol', $curr_symbol, 'currency_id');
}

function sql_get_sec_exp_source_id($code_id) {
  $source_id = sql_get_value('security_exposure_stati', 'code_id', $code_id, 'security_exposure_status_id');
  return $source_id;
}

function sql_get_user_id($code_id) {
  $user_id = sql_get_value('log_users', 'code_id', $code_id, 'user_id');
  return $user_id;
}

// get the trade id based on the bank trade id
function sql_trade_find_bank($bank_ref_id, $portfolio_id) {
  // by selecting the portfolio it is not a problem if two banks use the same trade id
  // the security id is not included to avoid problems if the security has been changed
  $trade_id = sql_get("SELECT trade_id FROM trades WHERE bank_ref_id = '".$bank_ref_id."' AND portfolio_id = ".$portfolio_id.";");
  return $trade_id;
}

// get the trade id based on the trade parameters
function sql_trade_find_trade_by_paramaters($portfolio_id, $sec_id, $trade_date, $trade_size, $trade_price) {
  $creation_time_limit = $trade_date + (24 * 60 * 60); // because the trade can be added during the complete day
  // by selection the portfolio  
  $sql_query  = "SELECT trade_id ";
  $sql_query .= "  FROM trades ";
  $sql_query .= " WHERE portfolio_id = ".$portfolio_id." ";
  $sql_query .= "   AND security_id = ".$sec_id." ";
  $sql_query .= "   AND (trade_date = '".date("Y-m-d", $trade_date)."' ";
  $sql_query .= "        OR (trade_date IS NULL ";
  $sql_query .= "            AND creation_time <= '".date("Y-m-d", $creation_time_limit)."' ";
  $sql_query .= "            AND valid_until >=   '".date("Y-m-d", $trade_date)."')) ";
  $sql_query .= "   AND size = ".$trade_size." ";
  $sql_query .= "   AND (price = ".$trade_price." OR price IS NULL);";
  //echo $sql_query.'<br>';
  $trade_id = sql_get($sql_query);
  return $trade_id;
}
          


function sql_find_portfolio_by_bank_id($bank_portfolio_id) {
  $portfolio_id = sql_get("SELECT portfolio_id FROM portfolios WHERE bank_portfolio_id = '".$bank_portfolio_id."';");
  return $portfolio_id;
}

function sql_find_event($event_key) {
  $event_id = sql_get("SELECT event_id FROM events WHERE description_unique = '".$event_key."';");
  return $event_id;
}

function sql_find_curr($curr_symbol) {
  $curr_id = sql_get("SELECT currency_id FROM currencies WHERE symbol = '".$curr_symbol."';");
  return $curr_id;
}

function sql_find_exposure_item($item_name) {
  $item_id = sql_get("SELECT exposure_item_id FROM exposure_items WHERE description = '".$item_name."';");
  return $item_id;
}

// -------------------------------------------
// set functions - to add or overwrite a value
// -------------------------------------------

// set values - the following function are to set some values in the database
function sql_set_trade_date($trade_id, $trade_date) {
  return sql_set("trades", "trade_id", $trade_id, "trade_date", $trade_date, "date");
}

function sql_set_trade_bank_id($trade_id, $bank_id) {
  return sql_set("trades", "trade_id", $trade_id, "bank_ref_id", $bank_id, "text");
}

function sql_set_trade_status($trade_id, $status_id) {
  return sql_set("trades", "trade_id", $trade_id, "trade_status_id", $status_id, "int");
}

function sql_set_event_update($event_id) {
  return sql_set_no_log("events", "event_id", $event_id, "updated", date('Y-m-d H:i:s'), "datetime");
}

function sql_set_event_closed($event_id) {
  return sql_set("events", "event_id", $event_id, "closed", date('Y-m-d H:i:s'), "datetime");
}

function sql_set_event_open($event_id) {
  return sql_set("events", "event_id", $event_id, "closed", NULL, "datetime");
}

function sql_set_event_status($event_id, $status_id) {
  return sql_set("events", "event_id", $event_id, "event_status_id", $status_id, "int");
}

function sql_set_event_type($event_id, $type_id) {
  return sql_set("events", "event_id", $event_id, "event_type_id", $type_id, "int");
}

function sql_set_event_description($event_id, $description) {
  return sql_set("events", "event_id", $event_id, "description", $description, "text");
}

function sql_set_event_account($event_id, $account_id) {
  return sql_set("events", "event_id", $event_id, "account_id", $account_id, "int");
}

function sql_set_event_portfolio($event_id, $portfolio_id) {
  return sql_set("events", "event_id", $event_id, "portfolio_id", $portfolio_id, "int");
}

function sql_set_event_date($event_id, $field_value) {
  return sql_set("events", "event_id", $event_id, "event_date", $field_value, "date");
}

function sql_set_event_solution1($event_id, $field_value) {
  return sql_set("events", "event_id", $event_id, "solution1_description", $field_value, "text");
}

function sql_set_event_solution1_sql($event_id, $field_value) {
  return sql_set("events", "event_id", $event_id, "solution1_sql", $field_value, "text");
}

function sql_set_event_solution2($event_id, $field_value) {
  return sql_set("events", "event_id", $event_id, "solution2_description", $field_value, "text");
}

function sql_set_event_solution2_sql($event_id, $field_value) {
  return sql_set("events", "event_id", $event_id, "solution2_sql", $field_value, "text");
}


// -----------------------------------
// add functions - only to add a value
// -----------------------------------

// add value - sql_add functions will only write the value if the database value ahs been empty
// add value general - one add function for each table
function sql_add_trade_field($trade_id, $value_field, $new_value, $value_type) {
  return sql_add("trades", "trade_id", $trade_id, $value_field, $new_value, $value_type);
}

function sql_add_security_field($sec_id, $value_field, $new_value, $value_type) {
  return sql_add("securities", "security_id", $sec_id, $value_field, $new_value, $value_type);
}

function sql_add_exposure_item_field($item_id, $value_field, $new_value, $value_type) {
  return sql_add("exposure_items", "exposure_item_id", $sec_id, $value_field, $new_value, $value_type);
}

function sql_add_event_field($event_id, $value_field, $new_value, $value_type) {
  return sql_add("events", "event_id", $event_id, $value_field, $new_value, $value_type);
}

// add value fields - one add function for each table field
// add value fields trades
function sql_add_trade_premium($trade_id, $premium) {
  return sql_add_trade_field($trade_id, "premium", $premium, "double");
}

function sql_add_trade_fees_ext($trade_id, $fees_ext) {
  return sql_add_trade_field($trade_id, "fees_extern", $fees_ext, "double");
}

function sql_add_trade_fees_bank($trade_id, $fees_bank) {
  return sql_add_trade_field($trade_id, "fees_bank", $fees_bank, "double");
}

function sql_add_trade_date($trade_id, $trade_date) {
  return sql_add_trade_field($trade_id, "trade_date", $trade_date, "date");
}

function sql_add_trade_price($trade_id, $trade_price) {
  return sql_add_trade_field($trade_id, "price", $trade_price, "double");
}

// add value fields securities
function sql_add_security_name($sec_id, $name) {
  return sql_add_security_field($sec_id, "name", $name, "text");
}

function sql_add_security_valor($sec_id, $valor) {
  return sql_add_security_field($sec_id, "valor", $valor, "int");
}

// using not the curr id, but the curr symbol
function sql_add_security_curr($sec_id, $curr) {
  //echo $sec_id;
  if (trim($curr) <> '') {
    //echo $curr;
    $curr_id = sql_find_curr($curr);
    //echo $curr_id;
    if ($curr_id <= 0) {
      sql_add_curr("symbol", $curr, "text");
    }
    $curr_id = sql_find_curr($curr);
    if ($curr_id > 0) {
      return sql_add_security_field($sec_id, "currency_id", $curr_id, "int");
    } else {
      echo 'cannot insert curr '.$curr.'.';
    }  
  }
}

// add value fields events
function sql_add_event_create($event_id) {
  return sql_add_event_field($event_id, "created", date('Y-m-d H:i:s'), "datetime");
}

function sql_add_event_closed($event_id) {
  return sql_add_event_field($event_id, "closed", date('Y-m-d H:i:s'), "datetime");
}

function sql_add_event_description($event_id, $description) {
  return sql_add_event_field($event_id, "description", $description, "text");
}

function sql_add_event_date($event_id, $field_value) {
  return sql_add_event_field($event_id, "event_date", $field_value, "date");
}

function sql_add_event_solution1($event_id, $field_value) {
  return sql_add_event_field($event_id, "solution1_description", $field_value, "text");
}

function sql_add_event_solution1_sql($event_id, $field_value) {
  return sql_add_event_field($event_id, "solution1_sql", $field_value, "text");
}

function sql_add_event_solution2($event_id, $field_value) {
  return sql_add_event_field($event_id, "solution2_description", $field_value, "text");
}

function sql_add_event_solution2_sql($event_id, $field_value) {
  return sql_add_event_field($event_id, "solution2_sql", $field_value, "text");
}

// --------------------------------------
// new functions - to insert a new record
// --------------------------------------

// to insert a new records
function sql_add_security($value_field, $new_value, $value_type) {
  $sec_id = sql_insert("securities", "security_id", $value_field, $new_value, $value_type);
  return $sec_id;
}

function sql_add_event($value_field, $new_value, $value_type) {
  $event_id = sql_insert("events", "event_id", $value_field, $new_value, $value_type);
  //echo $event_id;
  return $event_id;
}

function sql_add_curr($value_field, $new_value, $value_type) {
  $curr_id = sql_insert("currencies", "currency_id", $value_field, $new_value, $value_type);
  return $curr_id;
}

function sql_add_exposure_item($value_field, $new_value, $value_type) {
  $item_id = sql_insert("exposure_items", "exposure_item_id", $value_field, $new_value, $value_type);
  return $item_id;
}

// --------------------------------------
// more general function
// --------------------------------------

// add a new row
function sql_add_row($table, $unique_field, $unique_value, $value_type, $debug) {
  $row_id = 0;
  if (trim($unique_value) <> '') {
    $pk_field = sql_field_name($table, SQL_TABLE_PK_NBR);
    $row_id = sql_get_value($table, $unique_field, $unique_value, $pk_field);
    if ($row_id <= 0) {
      echo 'insert into '.$table.' '.$unique_field.' '.$unique_value.'<br>';
      $row_id = sql_insert($table, $pk_field, $unique_field, $unique_value, $value_type);
      if ($row_id <= 0) {
	echo 'Cannot insert '.$unique_field.' '.$unique_value.' into '.$table.' ('.$pk_field.' is not set).<br>';
	//tream_debug ('Cannot insert '.$unique_field.' '.$unique_value.' into '.$table.'.', $debug);
      }
    }
  }
  return $row_id;
}

// ---------------------------------------------------------------
// sql new functions - returns the sql code to insert a new record
// ---------------------------------------------------------------

// to insert a new records

function sql_new_trade($account_id, $portfolio_id, $in_date, $sec_id, $curr_id, $in_price, $sec_position, $trade_type) {
  $sql_code = "INSERT INTO trades ";
  $sql_code .= "(account_id, portfolio_id, trade_date, tp_person_id, security_id, currency_id, trade_type_id, trade_status_id, price, size, rational, settlement_date) ";
  $sql_code .= "VALUES ";
  $sql_code .= "(".$account_id.", ".$portfolio_id.", \'".date_format($in_date, 'Y-m-d')."\', ".THIS_SCRIPT_PERSON_ID.", ".$sec_id.", ".$curr_id.", ".$trade_type.", ".TRADE_STATUS_EXECUTED.", ".$in_price.", ".$sec_position.", \'System created trade based on Bank feed\', \'".date_format($in_date, 'Y-m-d')."\');";
  return $sql_code;
}

// ---------------
// check functions
// ---------------

// checks the default setup of an table -> if the row 0 has the name " not set"
function sql_table_ok($table, $id_field, $name_field) {
  $result = '';
  $db_id = sql_get_value($table, $name_field, ' not set', $id_field);
  //$db_id = sql_get("SELECT ".$id_field." FROM ".$table." WHERE ".$name_field." = ' not set';");
  if ($db_id <> 0 OR $db_id == '') {
    $status = 0;
    $result .= "Table ".$table." ";
    event_add("table check ".$table , "Table check of ".$table." shows that the NULL record is missing." ); 
    $result .= "<br>";
  } else {
    $result .= "Table ".$table." OK.<br>";
  }
  return $result;
}

// checks if all tream table have the correct setup
// check that all needed tables have the entry "not set" with if 0
function sql_table_check() {
  $result  = 'check the table setup<br>';
  $result .= sql_table_ok("account_mandates", "account_mandat_id", "description");
  $result .= sql_table_ok("account_types", "account_type_id", "description");
  $result .= sql_table_ok("action_stati", "action_status_id ", "status_text");
  $result .= sql_table_ok("action_types", "action_type_id", "type_name");
  $result .= sql_table_ok("addresses", "address_id", "description");
  $result .= sql_table_ok("address_link_types", "address_link_type_id", "type_name");
  $result .= sql_table_ok("banks", "bank_id", "bank_name");
  $result .= sql_table_ok("contact_types", "contact_type_id", "type_name");
  $result .= sql_table_ok("contract_types", "contract_type_id", "type_name");
  $result .= sql_table_ok("countries", "country_id", "name");
  $result .= sql_table_ok("currencies", "currency_id", "symbol");
  $result .= sql_table_ok("currency_pairs", "currency_pair_id", "description");
  $result .= sql_table_ok("document_categories", "document_category_id", "category_name");
  $result .= sql_table_ok("document_types", "document_type_id", "type_name");
  $result .= sql_table_ok("event_stati", "event_status_id ", "status_text");
  $result .= sql_table_ok("event_types", "event_type_id", "type_name");
  $result .= sql_table_ok("parameter_types", "parameter_type_id", "description");
  $result .= sql_table_ok("persons", "person_id", "lastname");
  $result .= sql_table_ok("persons", "person_id", "display_name");
  $result .= sql_table_ok("person_types", "person_type_id", "description");
  $result .= sql_table_ok("securities", "security_id", "name");
  $result .= sql_table_ok("security_price_feed_types", "feed_type_id", "type_name");
  $result .= sql_table_ok("security_trigger_stati", "trigger_status_id ", "status_text");
  $result .= sql_table_ok("security_trigger_types", "trigger_type_id ", "type_name");
// tables without "not set" record
//  $result .= sql_table_ok("security_types", "security_type_id ", "description");
  $result .= sql_table_ok("trade_stati", "trade_status_id ", "status_text");
  $result .= sql_table_ok("trade_types", "trade_type_id ", "description");
  $result .= sql_table_ok("value_stati", "value_status_id ", "status_text");
  $result .= sql_table_ok("value_types", "value_type_id ", "description");
  $result .= '<br><br>';
  return $result;
}


// ---------------
// event functions
// ---------------

// inform the defined persons that a new event has been created
// at the moment just send a email, maybe a later other channels
function event_inform($event_key, $event_text, $event_type, $event_date, $solution1, $solution1_sql, $solution2, $solution2_sql, $account_id, $portfolio_id) {
  $result = '';
  //$result .= 'check messages to be sent<br>';
  $sql    = 'SELECT contact_number, account_name FROM v_portfolio_persons WHERE portfolio_id = '.$portfolio_id.';';
  $sql_result = mysql_query($sql);

  if (!$sql_result) {
      $result .= "DB Error, could not query the database\n";
      $result .= 'MySQL Error: ' . mysql_error();
      exit;
  }

  while ($row = mysql_fetch_assoc($sql_result)) {
      $mail_to = $row['contact_number'];
      $mail_subject = 'New TREAM event: '.$event_key;
      $mail_message = $event_text;
      $mail_header = 'From:     tream@zukunft.com' . "\r\n" .
		      'Reply-To: tream@zukunft.com' . "\r\n" .
		      'X-Mailer: PHP/' . phpversion();
      $result .= 'send mail '.$mail_subject.' to '.$mail_to;
      $result .= mail($mail_to, $mail_subject, $mail_message, $mail_header).'<br>';
  }    
  return $result;
}

function event_add($event_key, $event_text, $event_type, $event_date, $solution1, $solution1_sql, $solution2, $solution2_sql, $account_id, $portfolio_id, $debug) {
  tream_debug ('event_add ... ', $debug);
  $result = '';
  $debug_msg = '';
  $event_id = sql_find_event($event_key);
  if ($event_id <= 0 AND trim($event_key) <> '') {
    tream_debug ('event_add ... key '.$event_key.'', $debug);
    $result .= 'event '.$event_key.' created ';
    $debug_msg .= sql_add_event("description_unique", $event_key, "text");
    // $result .= sql_add_event("description_unique", $event_key, "text");
    // create push messages if needed
    $debug_msg .= event_inform($event_key, $event_text, $event_type, $event_date, $solution1, $solution1_sql, $solution2, $solution2_sql, $account_id, $portfolio_id) ;
    tream_debug ('event_add ... '.$debug_msg, $debug);
  }
  $event_id = sql_find_event($event_key);
  if ($event_id > 0) {
    $status = intval(sql_get_event_status($event_id));
    if ($status > 0 AND $status <> 1) {
      $result .= 'event '.$event_key.' with status '.$status.' ('.$event_id.') updated';
    }
    $debug_msg .= sql_add_event_create       ($event_id).'<br>';
    $debug_msg .= sql_set_event_update       ($event_id).'<br>';
    $debug_msg .= sql_set_event_description  ($event_id, $event_text).'<br>';
    $debug_msg .= sql_set_event_account      ($event_id, $account_id).'<br>';
    $debug_msg .= sql_set_event_portfolio    ($event_id, $portfolio_id).'<br>';
    $debug_msg .= sql_set_event_date         ($event_id, $event_date).'<br>';
    $debug_msg .= sql_set_event_solution1    ($event_id, $solution1).'<br>';
    $debug_msg .= sql_set_event_solution1_sql($event_id, $solution1_sql).'<br>';
    $debug_msg .= sql_set_event_solution2    ($event_id, $solution2).'<br>';
    $debug_msg .= sql_set_event_solution2_sql($event_id, $solution2_sql).'<br>';
    // reopen the event, if it has been closed
    $debug_msg .= sql_set_event_open         ($event_id);
    $debug_msg .= sql_set_event_status       ($event_id, 1);
    $debug_msg .= sql_set_event_type         ($event_id, sql_code_link($event_type));
    tream_debug ('event_add ... update '.$event_text.': '.$debug_msg.'<br>', $debug);
  } else {
    $result .= 'Error: cannot create event';
  } 
  tream_debug ('event_add ... done', $debug);
  return $result;
}
 
// closes an event if an open event exists
function event_close($event_key, $debug) {
  $result = '';
  $debug_msg = '';
  $status = 0;

  tream_debug ('event_close '.$event_key.'', $debug);
  $event_id = sql_find_event($event_key);
  if ($event_id > 0) {
    $status = intval(sql_get_event_status($event_id));
    if ($status > 0 AND $status <> 4) {
      $result .= 'event '.$event_key.' with status '.$status.' ('.$event_id.') closed';
    }
    $debug_msg .= sql_set_event_closed($event_id);
    $debug_msg .= sql_set_event_status($event_id, 4);
    tream_debug ('event_close: '.$debug_msg.'', $debug);
  } else {
    tream_debug ('event '.$event_key.' not found and not closed.', $debug);
  }
  return $result;
}
 
// saves the expore for on portfolio in the database for faster calculation
function exposure_save($portfolio_id, $target_id, $usage, $value_ref_ccy) {
  if ($target_id > 0 AND $portfolio_id > 0) {
    $target_value_id = sql_get("SELECT exposure_target_value_id FROM exposure_target_values WHERE exposure_target_id = ".$target_id." AND portfolio_id = ".$portfolio_id.";");
    if ($target_value_id > 0) {
      sql_set_no_log("exposure_target_values", "exposure_target_value_id", $target_value_id, "calc_value", $usage, "");
      sql_set_no_log("exposure_target_values", "exposure_target_value_id", $target_value_id, "calc_value_in_ref_ccy", $value_ref_ccy, "");
    } else {
      $sql ="INSERT INTO exposure_target_values (exposure_target_id, portfolio_id, calc_value, calc_value_in_ref_ccy) VALUES ('".$target_id."', '".$portfolio_id."', '".$usage."', '".$value_ref_ccy."');";
      echo 'insert '.$sql.' .';
      $result = mysql_query("INSERT INTO exposure_target_values (exposure_target_id, portfolio_id, calc_value, calc_value_in_ref_ccy) VALUES ('".$target_id."', '".$portfolio_id."', '".$usage."', '".$value_ref_ccy."');");
    }
  } else {
    echo 'target '.$target_id.' is missing';
  }
}

 
// check get the exposure limit and create a event if outside the limit
// the portfolio is used to check exceptions
// and save the value in the database for faster reuse
function check_exposure($exposure_id, $portfolio_id, $usage_in_pct, $sum_in_ref_ccy, $debug) {
  $result = '';
  $exposure_name = sql_get_value("exposure_items", "exposure_item_id", $exposure_id, "description");
  $ref_currency_id = sql_get_value("portfolios", "portfolio_id", $portfolio_id, "currency_id");
  $portfolio_name = sql_get_value("portfolios", "portfolio_id", $portfolio_id, "portfolio_name");
  $account_id = sql_get_value("portfolios", "portfolio_id", $portfolio_id, "account_id");
  $mandate_id = sql_get_value("accounts", "account_id", $account_id, "account_mandat_id");
  $target_id  = sql_get("SELECT exposure_target_id FROM exposure_targets WHERE exposure_item_id = ".$exposure_id." AND currency_id = ".$ref_currency_id." AND account_mandat_id = ".$mandate_id.";");
  $target     = sql_get("SELECT target             FROM exposure_targets WHERE exposure_item_id = ".$exposure_id." AND currency_id = ".$ref_currency_id." AND account_mandat_id = ".$mandate_id.";");
  $limit_up   = sql_get("SELECT limit_up           FROM exposure_targets WHERE exposure_item_id = ".$exposure_id." AND currency_id = ".$ref_currency_id." AND account_mandat_id = ".$mandate_id.";");
  $limit_down = sql_get("SELECT limit_down         FROM exposure_targets WHERE exposure_item_id = ".$exposure_id." AND currency_id = ".$ref_currency_id." AND account_mandat_id = ".$mandate_id.";");
  $result = ' check limits if '.$exposure_name.' '.round($usage_in_pct).'% is between '.$limit_up.' and '.$limit_down.', should be '.$target.' ';
  if ($target_id > 0 AND $portfolio_id > 0) {
    exposure_save($portfolio_id, $target_id, $usage_in_pct, $sum_in_ref_ccy);
  } else {
    echo 'target for '.$exposure_name.' is missing';
  }
  if ($limit_up > 0) {
    if ($limit_up <= $usage_in_pct) {
      $event_key = $exposure_name." above limit ".$limit_up; // include the limit up in the id to create a new event if the limit is changed
      $event_name = "In portfolio ".trim($portfolio_name)." the ".$exposure_name." is at ".round($usage_in_pct,2)."pct, which is above limit of ".$limit_up.", target id ".$target.""; 
      $solution1     = "";
      $solution1_sql = "";
      $solution2     = "";
      $solution2_sql = "";
      $result .= '-> event ';

      $result .= event_add($event_key, $event_name, EVENT_TYPE_EXPOSURE_LIMIT, date('Y-m-d'), $solution1, $solution1_sql, $solution2, $solution2_sql, $account_id, $portfolio_id, $debug);
      $result .= '-> event checked ';
    }
  }
  if ($limit_down > 0) {
    if ($limit_down >= $usage_in_pct) {
      $event_key = $exposure_name." below limit ".$limit_down; // include the limit up in the id to create a new event if the limit is changed
      $event_name = "In portfolio ".trim($portfolio_name)." the ".$exposure_name." is at ".round($usage_in_pct,2)."pct, which is below limit of ".$limit_down.", target id ".$target.""; 
      $solution1     = "";
      $solution1_sql = "";
      $solution2     = "";
      $solution2_sql = "";
      $result .= '-> event ';

      $result .= event_add($event_key, $event_name, EVENT_TYPE_EXPOSURE_LIMIT, date('Y-m-d'), $solution1, $solution1_sql, $solution2, $solution2_sql, $account_id, $portfolio_id, $debug);
      $result .= '-> event checked ';
    }
  }
  return $result;
}
 
// check all birthdays in the next days (limit is set as parameter)
function event_check_birthdays($link) {
  $result = 'create birthday events<br><br>';
  $sql    = 'SELECT person_id, firstname, lastname, date_of_birth FROM persons WHERE date_of_birth IS NOT NULL;';
  $sql_result = mysql_query($sql, $link);

  if (!$sql_result) {
      $result .= "DB Error, could not query the database\n";
      $result .= 'MySQL Error: ' . mysql_error();
      exit;
  }

  while ($row = mysql_fetch_assoc($sql_result)) {
      $result .= $row['lastname'];
      $birthday = strtotime($row['date_of_birth']);
      $day_offset = 0;
      $check_day = time();
      while ($day_offset <= $const_birthday_limit) {
	$check_day = $check_day + (24 * 60 * 60);
	// date_add($check_day, date_interval_create_from_date_string('1 day'));
	if (date('m-d', $birthday) == date('m-d', $check_day)) {
	  $event_key   = 'birthday '.$row['person_id'].' '.date('j M Y', $check_day);
	  $event_name = 'Birthday '.$row['firstname'].' '.$row['lastname'].' '.date('j M Y', $check_day);
	  event_add($event_key, $event_name, $event_type, date_format($birthday, 'Y-m-d'), $solution1, $solution1_sql, $solution2, $solution2_sql, $account_id, $portfolio_id);
	}
	$day_offset = $day_offset + 1;
      }
      $result .= ' checked, ';
  }

  $result .= '<br><br>';
  return $result;
}

// ------------------
// security functions
// ------------------

// adds security and adds parameters if needed 
function db_check_security($ISIN, $valor, $name, $curr) {
  $ISIN = trim($ISIN);
  $valor = trim($valor);
  $name = trim($name);
  $curr = trim($curr);
 
  $sec_id = 0;
  if ($ISIN <> '' and $ISIN <> '0') {
    $result .= 'check '.$ISIN.'->';
    $sec_id = sql_get_security_id_from_ISIN($ISIN);
    if ($sec_id <= 0) {
      $result .= 'add '.$name.' (ISIN '.$ISIN.', valor '.$valor.'), ';
      $sec_id = sql_add_security("ISIN", $ISIN, "text");
    }
  }  
  if ($valor <> '' and $valor <> '0') {
    $result .= 'check '.$valor.'->';
    $sec_id = sql_get_security_id_from_valor($valor);
    if ($sec_id <= 0) {
      $sec_id = sql_get_security_id_from_CH_valor($valor);
      if ($sec_id <= 0) {
	$result .= 'add '.$name.' (valor '.$valor.'), ';
	$sec_id = sql_add_security("valor", $valor, "text");
      }
      $sec_id = sql_get_security_id_from_valor($valor);
    }
  }  
    
  if ($sec_id > 0) {
    // check parameters and add it if possible
    $result .= sql_add_security_name($sec_id, $name);
    $result .= sql_add_security_valor($sec_id, $valor);
    if (trim($curr) <> '') {
      $result .= 'check '.$curr.'->';
      $result .= sql_add_security_curr($sec_id, $curr);
    }
  }  
  return $result;
}


// ---------------
// trade functions
// ---------------

// checks if a trade exists and checks all parameters
function trade_check($bank_name, $bank_portfolio_id, $ISIN, $valor, $sec_name, $trade_date, $trade_size, $trade_price, $trade_curr, $bank_ref_id, $storno_code) {
  $continue = true;
  $result = '';
  
  // check the security	  
  $result .= db_check_security($ISIN, $valor, $sec_name);
  $sec_id = sql_get_security_id_from_ISIN($ISIN);
  if ($sec_id <= 0) {
    $continue = false;
  }
  
  // check the portfolio
  if ($continue) {
    $event_key = $bank_name." portfolio " . $bank_portfolio_id . " missing";
    $event_text = "The ".$bank_name." portfolio ".$bank_portfolio_id." is missing. Please add the id in field bank portfolio id.";

    $portfolio_id = sql_find_portfolio_by_bank_id($bank_portfolio_id);
    if ($portfolio_id > 0) {
      $result .= 'portfolio '.$bank_portfolio_id.' found->';
      $portfolio_name = sql_get_portfolio_name($portfolio_id);
      $account_id = sql_get_portfolio_account($portfolio_id);
      event_close($event_key);
    } else {
      event_add($event_key, $event_text); 
      $continue = false;
    }
  }

  // format the trade event descriptions
  $curr_id = sql_get_curr_id($trade_curr);
  if ($trade_size < 0) {
    $trade_type = TRADE_TYPE_SELL;
  } else {
    $trade_type = TRADE_TYPE_BUY;
  }
  $event_key = "Trade missing ".$bank_ref_id. " portfolio ".$portfolio_id;
  $event_text = "Trade of ".$trade_size." ".$Sec_name_short." (ISIN ".$ISIN.") @ ".$trade_price." missing for portfolio ".$portfolio_name." on trade date ".date('d.M Y', $trade_date).".";
    
   if ($storno_code <> '0' AND $storno_code <> ' ' AND $storno_code <> '') {
     $result .= 'Strono of '.$bank_ref_id.' with code '.$storno_code.'<br>';
     event_close($event_key);
     $continue = false;
   }
  
  // check trade
  if ($continue) {
    $result .= 'search for trade date: '.date("Y-m-d", $trade_date).', size: '.$trade_size.', price: '.$trade_price.', bank id: '.$bank_ref_id.'->';
    
	    
    // check if trade id exists
    $trade_id = sql_trade_find_bank($bank_ref_id, $portfolio_id);
    if ($trade_id > 0) {      
      $result .= 'found, check parameters for trade id: '.$trade_id.'->';
      event_close($event_key);
    } else {
      // try to find an matching trade that has been entered manually
      //$result .= "SELECT trade_id FROM trades WHERE portfolio_id = ".$portfolio_id." AND security_id = ".$sec_id." AND trade_date = '".date("Y-m-d", $trade_date)."' AND size = ".$trade_size." AND price = ".$trade_price.";";
      $trade_id = sql_trade_find_trade_by_paramaters($portfolio_id, $sec_id, $trade_date, $trade_size, $trade_price);     
      if ($trade_id > 0) {      
	$result .= 'found, check parameters for trade id: '.$trade_id.' and add the bank id.<br>';
	$result .= sql_set_trade_bank_id($trade_id, $bank_ref_id);
	// close event messages if they exist
	event_close($event_key);
      } else {
	// check if there is a partly execution, if yes offer two suggestion: 1. split the trade and 2. insert a new trade
	$result .= 'suggest to insert a new trade and add the bank id';
        $solution1     = "Add a new trade of ".$trade_size." ".$sec_name." at ".$trade_price.".";
	$solution1_sql = sql_new_trade($account_id, $portfolio_id, $trade_date, $sec_id, $curr_id, $trade_price, $trade_size, $trade_type);
        $solution2     = "";
	$solution2_sql = "";
	event_add($event_key, $event_text, $event_type, date_format($trade_date, 'Y-m-d'), $solution1, $solution1_sql, $solution2, $solution2_sql, $account_id, $portfolio_id);
      }
    }

    // recheck and add missing parameters
    $trade_id = sql_trade_find_bank($bank_ref_id, $portfolio_id); 
    if ($trade_id > 0) {      
      //$db_trade_date = strtotime(sql_get("SELECT trade_date FROM trades WHERE trade_id = ".$trade_id.";")); 
      //if ($db_trade_date <> $trade_date) {
      $result .= sql_add_trade_date($trade_id, $trade_date);
      $result .= sql_add_trade_price($trade_id, $trade_price);
      $result .= sql_set_trade_status($trade_id, TRADE_STATUS_EXECUTED);
      //}
    }
  }
  return $result;
}

?> 
