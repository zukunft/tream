<?php 
  
/* 

tream_db_adapter.php - Database adapter for the TREAM project

TO DO: 

replace ' with /' before writing a text to the sql db


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

 
// the technical login into the database
define("SQL_HOST", "localhost");
define("SQL_USER", "p4a");
define("SQL_PASSWORD", "xxx");
define("SQL_DATABASE", "tream");

/*

Link between database records and code

the install script creates some predefined records
some of these records are linked to the code over the field code_id
the user can change the name of the records
and the id of the records may differ due to program updates at a different time


The fields of code linked tables are:

1. tablename_id: the unique id that is a foreign key inside the database
2. name_field: the name of the record. This field is preset by the install script, but the user can change it
3. code_id: unique to link the code with the record. Cannot be changed by the user

In the function sql_code_link the table names are fixed to shorten the code
changes here must also be done in install.php and in the function sql_code_link in this module
                                  ===========                     =============

*/

define("EVENT_STATUS_CREATED", "event_status_create"); 
define("EVENT_STATUS_WORKING", "event_status_working"); 
define("EVENT_STATUS_DONE",    "event_status_done"); 
define("EVENT_STATUS_CLOSED",  "event_status_closed"); 

define("EVENT_TYPE_TRADE_MISSING",  "event_type_trade_missing");
define("EVENT_TYPE_SQL_ERROR_ID",   "event_type_sql_error");
define("EVENT_TYPE_SYSTEM_EVENT",   "event_type_system_event");
define("EVENT_TYPE_USER_DAILY",     "event_type_daily_user");
define("EVENT_TYPE_EXPOSURE_LIMIT", "event_type_exposure_limit");

define("USER_SYSTEM",       "user_system");
define("USER_CHECK_SCRIPT", "user_check_script");

define("SYSTEM_MM_FEED",       "markertmap_xls_feed");
define("SYSTEM_YAHOO_FEED",    "yahoo_feed");

define("ACCOUNT_PERSON_TYPE_PM",      "pm");
define("ACCOUNT_PERSON_TYPE_DPM",     "deputy_pm");
define("ACCOUNT_PERSON_TYPE_BANK_RM", "bank_rm");

define("MSG_TRADE_CONF_CLIENT", "trade_conf_client");
define("MSG_TRADE_CONF_BANK",   "trade_conf_bank");

define("SEC_TRIGGER_TAKE_PROFIT", "take_profit");
define("SEC_TRIGGER_STOP_LOSS",   "stop_loss");

define("SEC_TRIGGER_STATUS_NEW",   "new");
define("SEC_TRIGGER_STATUS_ACTIVE",   "active");
define("SEC_TRIGGER_STATUS_TRIGGERED",   "done");
define("SEC_TRIGGER_STATUS_CLOSED",   "closed");


// IDs to be replaced
define("EVENT_TYPE_SQL_ERROR", 4);

//define("THIS_SCRIPT_USER_ID", 6);
//define("EVENT_STATUS_DONE", 3);

// General SQL functions (maybe replace by ZEND functions)

function sql_open($debug) {
  tream_debug ('sql open ... ', $debug);
  $link = mysql_connect(SQL_HOST, SQL_USER, SQL_PASSWORD);

  if (!$link) {
      die('Could not connect: ' . mysql_error());
  }

  if (!mysql_select_db(SQL_DATABASE, $link)) {
      echo 'Could not select database';
      exit;
  }

  tream_debug ('sql open ... done', $debug);  
  return $link;
}

function sql_close($link) {
  mysql_close($link);
}

function sql_heartbeat($system_id) {
  sql_set_no_log("systems", "code_id", $system_id, "last_heartbeat", date("Y-m-d H:i:s"), "");
}

// get the first value of an sql query
function sql_result($query) {
  $sql_result = mysql_query($query) or die('Query failed: ' . mysql_error() . ', when executing the query ' . $query . '.');
/*
  if (!$sql_result) {
      $result .= "DB Error, could not query the database\n";
      $result .= 'MySQL Error: ' . mysql_error();
      exit;
  }
*/  

  return $sql_result;
}
 
// get the first value of an sql query
function sql_get($query) {
  $result = '';
  $sql_result = sql_result($query);
  $sql_array = mysql_fetch_array($sql_result, MYSQL_NUM);
  if (is_null($sql_array[0])) {
    $result = '';
  } else {  
    $result = $sql_array[0];
  }
  return $result;
}
 
function sql_get_value($table, $id_field, $id_value, $value_field) {
  $result = sql_get("SELECT ".$value_field." FROM ".$table." WHERE ".$id_field." = '".$id_value."';");
  return $result;
}
 
// set a value in an sql table and report the changes
function sql_log($table, $id_field, $id_value, $value_field, $old_value, $new_value) {
  if ($old_value <> $new_value) {
    mysql_query("INSERT INTO log_data (table_name, row_id, field_name, old_value, new_value, user_id) VALUES ('".$table."', '".$id_value."', '".$value_field."', '".$old_value."', '".$new_value."', ".THIS_SCRIPT_USER_ID.");");
  }
  return $new_value;
}

// set a value in an sql table and report the changes
function sql_set($table, $id_field, $id_value, $value_field, $new_value, $value_type) {
  // get the existing value
  $db_value = sql_get_value($table, $id_field, $id_value, $value_field); 
  if ($value_type == 'date') {
    if ($new_value == '') {
      $new_value = $db_value;
    } else {
      $db_value = date("Y-m-d", strtotime($db_value)); 
      $new_value = date("Y-m-d", strtotime($new_value)); 
    }
  }
  if ($value_type == 'text') {
    $db_value = trim($db_value); 
    $new_value = trim($new_value); 
  }
  if ($db_value <> $new_value) {
    mysql_query("UPDATE `".$table."` SET `".$value_field."` = '".$new_value."' WHERE `".$id_field."` = '".$id_value."';");
    sql_log($table, $id_field, $id_value, $value_field, $db_value, $new_value);
  }
  return $new_value;
}

// set a value in an sql table and without saving the changes (only used to update the last checked time of events)
function sql_set_no_log($table, $id_field, $id_value, $value_field, $new_value, $value_type) {
  // get the existing value
  $db_value = sql_get_value($table, $id_field, $id_value, $value_field); 
  if ($value_type == 'date') {
    $db_value = strtotime($db_value); 
    $new_value = date("Y-m-d", $new_value); 
  }
  if ($db_value <> $new_value) {
    $sql_query = "UPDATE `".$table."` SET `".$value_field."` = '".$new_value."' WHERE `".$id_field."` = '".$id_value."';";
    //echo $sql_query;
    mysql_query($sql_query);
  }
  return $new_value;
}

// only set the value if the field is empty
function sql_add($table, $id_field, $id_value, $value_field, $new_value, $value_type) {
  $result = '';

  $add_value = 0;
  $db_value = sql_get_value($table, $id_field, $id_value, $value_field);
  if (trim($db_value) == '' AND $new_value <> '') {
    $add_value = 1;
  }
  if ($value_type == 'double') {
    $db_value = strval($db_value); 
    if ($db_value == 0  AND $new_value <> 0) {
      $add_value = 1;
    }
  }
  if ($value_type == 'int' OR $value_type == RECON_VALUE_TYPE_INT) {
    $db_value = strval($db_value); 
    $new_value = strval($new_value); 
    if ($db_value == 0  AND $new_value <> 0) {
      $add_value = 1;
    }
  }
  if ($value_type == RECON_VALUE_TYPE_REF) {
    $db_value = strval($db_value); 
    $new_value = strval($new_value); 
    if ($db_value <= 0 AND $new_value > 0) {
      $add_value = 1;
    }
  }
  if ($value_type == 'date') {
    $new_value = date("Y-m-d", $new_value); 
  }
  if ($add_value) {
    sql_set($table, $id_field, $id_value, $value_field, $new_value, "double");
    $result .= $value_field.' of '.$new_value.' added ';
  } else {  
    $result .= $value_field.' '.$new_value.' OK ';
  }
  return $result;
}

// insert 
function sql_insert($table, $id_field, $value_field, $new_value, $value_type) {
  $id_value = NULL;
  // don't insert empty lines
  if (trim($new_value) <> '') {
    // get the existing value
    $result = mysql_query("INSERT INTO ".$table." (".$value_field.") VALUES ('".$new_value."');");
    if (!$result) {
      if ($table <> 'events') {
	echo event_add("Insert ".$table." ".$value_field." ".$new_value." failt", "Cannot insert into ".$table." the ".$value_field." ".$new_value." because: ".mysql_error().".", EVENT_TYPE_SQL_ERROR, date('Y-m-d H:i:s'), "Please contact your system administrator.", "", "", "", "", "");
      } else {  
        echo "Error ".mysql_error()." when creating an event." ;
      }
    } else {  
      $id_value = sql_get("SELECT ".$value_field." FROM ".$table." WHERE ".$value_field." = '".$new_value."';");
      //echo "SELECT ".$value_field." FROM ".$table." WHERE ".$value_field." = '".$new_value."';<br>";
      //echo $id_value;
      sql_log($table, $id_field, $id_value, $value_field, "", $new_value);      
    }
  }
  return $id_value;
}

// insert 
function sql_field_name($table, $field_nbr) {
  $res = mysql_query("SELECT * FROM ".$table." LIMIT 0,1;");
  return mysql_field_name($res, $field_nbr);
}

// get the field type of a database field
function sql_field_type($table, $field_name) {
  $res = mysql_query('SELECT fieldtype('.$field_name.') FROM '.$table.' LIMIT 0,1;');
  return $res;
}

// returns the pk / row_id for a given code_id
// if the code_id does not exist the missing record is created
// the code_id is always saved in the 20 char long field code_id
function sql_code_link($code_id, $name_field, $description, $debug) {
  // set the table name and the id field
  $table_name = '';
  if ($code_id == EVENT_STATUS_CREATED
   OR $code_id == EVENT_STATUS_WORKING 
   OR $code_id == EVENT_STATUS_DONE 
   OR $code_id == EVENT_STATUS_CLOSED) {
    $table_name = "event_stati";
    $id_field   = "event_status_id";
  }
  if ($code_id == EVENT_TYPE_TRADE_MISSING
   OR $code_id == EVENT_TYPE_SQL_ERROR_ID 
   OR $code_id == EVENT_TYPE_SYSTEM_EVENT 
   OR $code_id == EVENT_TYPE_USER_DAILY 
   OR $code_id == EVENT_TYPE_EXPOSURE_LIMIT) {
    $table_name = "event_types";
    $id_field   = "event_type_id";
  }
  if ($code_id == USER_SYSTEM
   OR $code_id == USER_CHECK_SCRIPT) {
    $table_name = "log_users";
    $id_field   = "user_id";
    tream_debug ('users<br>', $debug);
  }
  if ($code_id == SYSTEM_MM_FEED
   OR $code_id == SYSTEM_YAHOO_FEED) {
    $table_name = "systems";
    $id_field   = "system_id";
    tream_debug ('systems<br>', $debug);
  }
  if ($code_id == ACCOUNT_PERSON_TYPE_PM
   OR $code_id == ACCOUNT_PERSON_TYPE_DPM 
   OR $code_id == ACCOUNT_PERSON_TYPE_BANK_RM) {
    $table_name = "account_person_types";
    $id_field   = "account_person_type_id";
    tream_debug ('account_person_types<br>', $debug);
  }
  if ($code_id == MSG_TRADE_CONF_CLIENT
   OR $code_id == MSG_TRADE_CONF_BANK) {
    $table_name = "message_types";
    $id_field   = "message_type_id";
    tream_debug ('message_types<br>', $debug);
  }

  if ($code_id == SEC_TRIGGER_TAKE_PROFIT
   OR $code_id == SEC_TRIGGER_STOP_LOSS) {
    $table_name = "security_trigger_types";
    $id_field   = "security_trigger_type_id";
    tream_debug ('security_trigger_types<br>', $debug);
  }

  if ($code_id == SEC_TRIGGER_STATUS_NEW
   OR $code_id == SEC_TRIGGER_STATUS_ACTIVE 
   OR $code_id == SEC_TRIGGER_STATUS_TRIGGERED 
   OR $code_id == SEC_TRIGGER_STATUS_CLOSED) {
    $table_name = "security_trigger_stati";
    $id_field   = "trigger_status_id";
    tream_debug ('security_trigger_stati<br>', $debug);
  }

  if ($table_name == '') {
    tream_debug('table name for code_id '.$code_id.' ('.$name_field.') not found <br>', $debug);
  } else {
    // get the row_id
    $row_id = sql_get_value($table_name, "code_id", $code_id, $id_field);

    // insert the missing row if needed
    if ($row_id <= 0) {
      $row_id = sql_insert($table_name, $id_field, "code_id", $code_id, '');
      tream_debug ('inserted '.$code_id.' as '.$row_id.'<br>', $debug);
      // looks like otherwise the install script needs to be executed twice ????
      $row_id = sql_get_value($table_name, "code_id", $code_id, $id_field); 
    } else {
      tream_debug ('found '.$code_id.' as '.$row_id.'<br>', $debug);
    }  

    // set the name as default
    if ($row_id > 0 AND $name_field <> '' AND $description <> '') {
      $row_name = sql_get_value($table_name, $id_field, $row_id, $name_field);
      if ($row_name == '') {
	tream_debug ('add '.$description.'<br>', $debug);
	sql_set_no_log($table_name, $id_field, $row_id, $name_field, $description, '');
	//sql_add       ($table_name, $id_field, $row_id, $name_field, $description, '');
      }
    }
  }
  return $row_id;
}


?> 
