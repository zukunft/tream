<?php 
 
/* 

tream_db.php - Link to saved files

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

Copyright (c) 2013-2015 zukunft.com AG, Zurich
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

// to do: dont replace the col name; instead add the col at the end 
// 
// check if the col names are unique

// returns one value of a fixed length text line selected by the headline row or an internal database reference id
function file_get_field($field_name, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug) {
  tream_debug ('file_get_field ... ', $debug);
  $result = '';
  
  $field_found = false;

  // loop over the fixed legth fields from the external file
  $field_nbr = 0;
  $start_pos = 0;
  foreach ($field_positions as $field_pos) {
    if ($field_names[$field_nbr] == $field_name and $field_found == false) {
      $result = substr($line, $start_pos, $field_pos);
      $field_found = true;
    }
    $start_pos = $start_pos + $field_pos;
    $field_nbr = $field_nbr + 1;
  }

  // loop over the new added ref fields
  $field_nbr = 0;
  foreach ($ref_fields as $ref_field) {
    if ($ref_field == $field_name and $field_found == false) {
      $result = $ref_values[$field_nbr];
      $field_found = true;
    }
    $field_nbr = $field_nbr + 1;
  }
  
  // replace special fields (maybe move these cases to configurable setup if there is time)
  if ($field_name == 'THIS_SCRIPT_PERSON_ID') {
    $result = THIS_SCRIPT_PERSON_ID;
  }
  if ($field_name == 'TRADE_STATUS_EXECUTED') {
    $result = TRADE_STATUS_EXECUTED;
  }
  if ($field_name == "date_format(BUCH-DAT, 'Y-m-d')") {
    $date_value = file_get_field("BUCH-DAT", $line, $field_positions, $field_names, $ref_fields, $ref_values);
    //echo $date_value.'<br>';
    //echo date_format(date_create_from_format("Ymd", $date_value), 'Y-m-d').'<br>';
    $result = date_format(date_create_from_format("Ymd", $date_value), 'Y-m-d');
  }
  if ($field_name == "date('d.M Y', trade_date)") {
    $result = TRADE_STATUS_EXECUTED;
  }
  if ($field_name == "trade_type") {
    $trade_size = file_get_field("UMSATZ", $line, $field_positions, $field_names, $ref_fields, $ref_values);
    if ($trade_size < 0) {
      $trade_type = TRADE_TYPE_SELL;
    } else {
      $trade_type = TRADE_TYPE_BUY;
    }
    $result = $trade_type;
  }

  tream_debug ('file_get_field ... done: '.$result, $debug);
  return $result;
}

function get_string_between($string, $start, $end){
  $string = " ".$string;
  $ini = strpos($string,$start);
  if ($ini == 0) return "";
  $ini += strlen($start);
  $len = strpos($string,$end,$ini) - $ini;
  return substr($string,$ini,$len);
}


// replace all makers with the real values
function file_set_fields_in_string($in_text, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug) {
  tream_debug ('file_set_fields_in_string ... ', $debug);
  $result = $in_text;
  
  while (get_string_between($result, RECON_MAKER_START, RECON_MAKER_END) <> '') {
    //echo $result.'<br>';
    $var_name = get_string_between($result, RECON_MAKER_START, RECON_MAKER_END);
    //echo $var_name.'<br>';
    $var_value = trim(file_get_field($var_name, $line, $field_positions, $field_names, $ref_fields, $ref_values));
    //echo $var_name.'<br>';
    $result = str_replace(RECON_MAKER_START.$var_name.RECON_MAKER_END, $var_value, $result);
    //echo $result.'<br>';
  }

  tream_debug ('file_set_fields_in_string ... done: '.$result, $debug);
  return $result;
}

// returns the position of one reference field in the reference field list
function ref_field_nbr($field_name, $ref_fields) {
  $result = -1;

  $field_found = false;
  $field_nbr = 0;
  foreach ($ref_fields as $ref_field) {
    if ($ref_field == $field_name and $field_found == false) {
      $result = $field_nbr;
      $field_found = true;
    }
    $field_nbr = $field_nbr + 1;
  }

  return $result;
}

function file_event_close($step_row, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug) {
  $result = '';

  $event_key = $step_row['event_description_unique'];
  $event_key  = file_set_fields_in_string($event_key,  $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $result .= event_close($event_key); 

  tream_debug ('file_event_close ... '.$event_key, $debug);
  return $result;
}

function file_event_add($event_type, $step_row, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug) {
  tream_debug ('file_event_add ... ', $debug);
  $result = '';

  $event_key = $step_row['event_description_unique'];
  $event_text = $step_row['event_description'];
  $text1 = $step_row['solution1_text'];
  $sql1  = $step_row['solution1_sql'];
  $text2 = $step_row['solution2_text'];
  $sql2  = $step_row['solution2_sql'];

  $event_key     = file_set_fields_in_string($event_key,  $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $event_text    = file_set_fields_in_string($event_text, $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $solution1     = file_set_fields_in_string($text1,      $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $solution1_sql = file_set_fields_in_string($sql1,       $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $solution2     = file_set_fields_in_string($text2,      $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $solution2_sql = file_set_fields_in_string($sql2,       $line, $field_positions, $field_names, $ref_fields, $ref_values);
  
  // special fields that should always be set
  $account_id = file_get_field("account_id", $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $portfolio_id = file_get_field("portfolio_id", $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $event_date = file_get_field("trade_date", $line, $field_positions, $field_names, $ref_fields, $ref_values);

  //event_add($event_key, $event_text, $event_type, date_format($event_date, 'Y-m-d'), $solution1, $solution1_sql, $solution2, $solution2_sql, $account_id, $portfolio_id); 
  // temp solution because trade date seems not to return a value
  $result .= event_add($event_key, $event_text, $event_type, date('Y-m-d H:i:s'), $solution1, $solution1_sql, $solution2, $solution2_sql, $account_id, $portfolio_id); 

  tream_debug ('file_event_add ... done: '.$result, $debug);
  return $result;
}

// returns the record key for a row inditified by an unique field
// e.g. find a trade by the bank reference number
function file_get_ref($recon_type, $step_row, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug) {
  tream_debug ('file_get_ref ... ', $debug);
  $result = 0;

  // get the parameter for this step
  $dest_table = $step_row['dest_table'];
  $dest_field = $step_row['dest_field'];
  $dest_id_field = $step_row['dest_id_field'];
  $pk_field = sql_field_name($dest_table, SQL_TABLE_PK_NBR);
  $source_field = $step_row['source_field_name'];
  $value_type = $step_row['recon_value_type_id'];
  $event_key = $step_row['event_description_unique'];
  $event_text = $step_row['event_description'];

  // get the key value to search for
  $source_value = file_get_field($source_field, $line, $field_positions, $field_names, $ref_fields, $ref_values);
  tream_debug ('file_get_ref ... source_value = '.$source_value, $debug);
  
  // set the event text
  $event_key  = file_set_fields_in_string($event_key,  $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $event_text = file_set_fields_in_string($event_text, $line, $field_positions, $field_names, $ref_fields, $ref_values);
  
  tream_debug ('file_get_ref ... event_key = '.$event_key, $debug);
  tream_debug ('file_get_ref ... event_text = '.$event_text, $debug);

  // get the database ref
  if ($recon_type == RECON_STEP_TYPE_ADD) {
    $result = sql_add_row($dest_table, $dest_field, $source_value, $value_type);	        
  }
  if ($recon_type == RECON_STEP_TYPE_REF) {
    tream_debug ('file_get_ref ... sql_get_value '.$source_value, $debug);
    $result = sql_get_value($dest_table, $dest_field, $source_value, $pk_field);	        
    tream_debug ('file_get_ref ... sql_get_value result '.$result, $debug);
    if ($result > 0) {
      tream_debug ('file_get_ref ... close event '.$event_key, $debug);
      event_close($event_key);
      tream_debug ('file_get_ref ... closed '.$event_key, $debug);
    } else {  
      // don't create the event if a later step is used as a failover step for this step
      tream_debug ('file_get_ref ... add event '.$event_key, $debug);
      event_add($event_key, $event_text, '', '', '', '', '', '', '', '', $debug); 
      tream_debug ('file_get_ref ... added '.$event_key, $debug);
    }
  }
  tream_debug ('file_get_ref ... done: '.$result, $debug);
  /* if ($dest_field == 'ISIN') {
    echo 'row id for '.$step_row['dest_field'].' '.$source_value.' ='.$result.'<br>';
  } */
  return $result;
}

// returns the record key for a row inditified by a combination of event_key
// e.g. find a trade by trade date security, size, ...
function file_get_multi_key_ref($step_row, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug) {
  tream_debug ('file_get_multi_key_ref ... ', $debug);
  $result = 0;

  $source_fields = explode(",",$step_row['source_field_name']);
  $dest_table = $step_row['dest_table'];
  $dest_fields = explode(",",$step_row['dest_field']);
  $field_nbr = 0;
  $sql_where = '';
  foreach ($source_fields as $source_field) {
    // get field format and seperate it from the recon_step_type_id
    $used_field = trim($source_field);
    $used_format = '';
    if (strpos($source_field,'(') > 0) {
      //echo $used_field.'<br>';
      $used_field = trim(substr($source_field, 0, strpos($source_field,'(')));
      //echo $used_field.'<br>';
      $used_format = substr($source_field, strpos($source_field,'(')+1);
      //echo $used_format.'<br>';
      $used_format = trim(substr($used_format, 0, strpos($used_format,')')));
      //echo $used_format.'<br>';
    }
  
    //echo 'source_value of '.$used_field.'';
    $id_value = trim(file_get_field(trim($used_field), $line, $field_positions, $field_names, $ref_fields, $ref_values));
    if ($used_format <> '') {
      //echo $id_value.'<br>';
      //echo $used_format.'<br>';
      $date_value = date_create_from_format($used_format, $id_value);
      //$date_value = date_create_from_format('Ymd', '20140611');
      //echo date_format($date_value, 'Y-m-d').'<br>';
      $id_value = date_format($date_value, "Y-m-d");
      //echo $id_value.'<br>';
    }
    //echo ' = '.$id_value.'<br>';
    if ($id_value <> '') {
      if ($sql_where <> '') {
	$sql_where .= ' AND ';
      }
      $sql_where .= $dest_fields[$field_nbr].'='.$id_value;
    }
    $field_nbr = $field_nbr + 1;
  }
  
  $id_field = sql_field_name($dest_table, SQL_TABLE_PK_NBR);
  if (trim($sql_where) <> '') {
    $sql_query = "SELECT ".$id_field." FROM ".$dest_table." WHERE ".$sql_where.";";
    $result = sql_get($sql_query);
  }  

  tream_debug ('file_get_multi_key_ref ... done: '.$result, $debug);
  return $result;
}

// confirms in an array, that a db position exists also in the reconciliation file
function file_confirm_multi_key_ref($db_array, $step_row, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug) {
  tream_debug ('file_confirm_multi_key_ref ... ', $debug);
  $result = $db_array;
  $check_array = explode(",", $step_row['dest_field']);
  
  foreach ($db_array as $db_row) {
    tream_debug ('file_confirm_multi_key_ref ... check row '.$db_row, $debug);
    $found = 1;
    foreach ($check_array as $check_field) {
      $used_field = trim($check_field);
      $file_value = file_get_field($used_field, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug);
      tream_debug ('file_confirm_multi_key_ref ... check field '.$used_field, $debug);
      if ($db_row[$used_field] <> $file_value) {
        tream_debug ('file_confirm_multi_key_ref ... no match because '.$db_row[$used_field].' <> '.$file_value.'', $debug);
	$found = 0;
      }
    }
    if ($found == 1) {
      tream_debug ('file_confirm_multi_key_ref ... match found '.$db_row[$used_field].' <> '.$step_row[$used_field].'', $debug);
      $db_row['confirmed'] = 1;
    }
  }

  tream_debug ('file_confirm_multi_key_ref ... done for fields: '.$step_row['dest_field'], $debug);
  return $result;
}

// converts an external string to the correct sql string
function file_value_convert_to_sql($in_value, $in_format, $sql_format, $debug) {
  tream_debug ('file_value_convert_to_sql ... ', $debug);
  $result = $in_value;
  if ($in_format == RECON_VALUE_TYPE_DATE) {
    $result = substr($in_value,0,4).'-'.substr($in_value,4,2).'-'.substr($in_value,6,2);
    tream_debug ($in_value.' to '.$result, $debug);
  }
  tream_debug ('file_value_convert_to_sql ... done: '.$result, $debug);
  return $result;
}

// writes a value to the database
function file_add_value_to_db($step_row, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug) {
  tream_debug ('file_add_value_to_db ... ', $debug);
  $result = '';

  $dest_table = $step_row['dest_table'];
  $dest_field = $step_row['dest_field'];
  $dest_id_field = $step_row['dest_id_field'];
  $pk_field = sql_field_name($dest_table, SQL_TABLE_PK_NBR);
  $source_field = $step_row['source_field_name'];
  $source_id_field = $step_row['source_id_field'];
  $value_type = $step_row['recon_value_type_id'];

  $source_value = file_get_field($source_field, $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $id_value = file_get_field($source_id_field, $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $result .= sql_add($dest_table, $source_id_field, $id_value, $dest_field, $source_value, $value_type);
  
  $source_value = file_value_convert_to_sql($source_value, $value_type, 'sql_type_date');

  tream_debug ('file_add_value_to_db ... done: '.$result, $debug);
  return $result;
}

// 
function file_get_value_from_db($step_row, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug) {
  tream_debug ('file_get_value_from_db ... ', $debug);
  $result = '';

  $dest_table = $step_row['dest_table'];
  $dest_field = $step_row['dest_field'];
  $dest_id_field = $step_row['dest_id_field'];
  $value_type = $step_row['recon_value_type_id'];

  $id_value = file_get_field($dest_id_field, $line, $field_positions, $field_names, $ref_fields, $ref_values);
  $db_value = sql_get_value($dest_table, $dest_id_field, $id_value, $dest_field);
  $result = $db_value;

  tream_debug ('file_get_value_from_db '.$dest_field.' from table '.$dest_table.' (ID '.$dest_id_field.' '.$id_value.'):  '.$db_value, $debug);

  tream_debug ('file_get_value_from_db ... done: '.$result, $debug);
  return $result;
}

// do the reconciliation for one file
function file_read($file_id, $file_name, $file_positions_all, $file_names_all, $file_max_msg, $debug) {
  tream_debug ('file_read ... ', $debug);
  $result = '';
  // for each file first load all the data into an array
  // each step can modify the array, e.g. the ISIN is replaced by the sec_id
  $result .= 'read file '.$file_name.'<br>';
  
  // reset the file names and positions for each file, because due to different data the sizes can be adjusted for each file
  $file_positions = $file_positions_all;
  $file_names = $file_names_all;
  $file_msg = 0; // counter of messages created due to this input file
  $field_positions = explode(",",$file_positions);
  $field_names = explode(",",$file_names);
  
  $remove_filter_used = '';

  unset($ref_fields);
  unset($ref_values);
  
  tream_debug ('file_read ... load lines ...', $debug);

  // read file into array
  unset($line);
  unset($line_status); // negative numbers indicates an fatal error in this line and the next steps should not be executed any more for this line
  $fh = fopen($file_name,'r');
  while ($file_line = fgets($fh)) {
    $line[] = $file_line;
    //$result .= 'get line '.$file_line.'<br>';
    //$ref_values[] = 0; // add a dummy value to create the array ( maybe this be be removed later)
    $line_status[] = 0;
  }
  fclose($fh);
  
  // display the array for testing
  //print_r($line).'<br>'.'<br>';
  tream_debug ('file_read ... load done.', $debug);
  

  // loop over the reconciliation steps to create the complete ref array
  $sql_query_steps = 'SELECT recon_step_id, recon_step_type_id, dest_table, dest_field, source_field_name, source_id_field, dest_id_field, filter, recon_value_type_id, event_description_unique, event_description, stop_line_on_err, err_step_for_step, solution1_text, solution1_sql, solution2_text, solution2_sql, order_nbr, comment FROM recon_steps WHERE recon_file_id = '.$file_id.' ORDER BY order_nbr;';
  //echo $sql_query_steps;
  $step_result = mysql_query($sql_query_steps);
  while ($step_row = mysql_fetch_assoc($step_result)) {
  
    // $result .= '<br>'.$step_row['comment'].'<br>';

    // get the values that are needed to decide what to do
    $recon_type = $step_row['recon_step_type_id'];
    tream_debug ('file_read ... prepair step type '.$recon_type, $debug);

    // add the field name in the reference array if this step is supposed to add an reference
    if ($recon_type == RECON_STEP_TYPE_ADD 
    OR $recon_type == RECON_STEP_TYPE_REF 
    OR $recon_type == RECON_STEP_TYPE_REF_MULTI_SUGGEST 
    OR $recon_type == RECON_STEP_TYPE_REF_MULTI) {
      $dest_id_field = $step_row['dest_id_field'];
      if ($dest_id_field <> '') {
	$ref_fields[] = $dest_id_field;
        tream_debug ('file_read ... prepair get ref '.$dest_id_field.' ', $debug);
      } else {
	// system event dest id field is empty, but the recon type suggest a recon field
      }
    }

    // add the field name in the reference array if this step is just loading a reference
    if ($recon_type == RECON_STEP_TYPE_GET) {
      $dest_field = trim($step_row['source_field_name']);
      if ($dest_field == '') {
	// if source field is not set use dest field as backup
	$dest_field = $step_row['dest_field'];
	//echo 'dest used ';
      }

      if ($dest_field <> '') {
	$ref_fields[] = $dest_field;
        tream_debug ('file_read ... prepair ref '.$dest_id_field.' added', $debug);
      } else {
	// system event dest id field is empty, but the recon type suggest a recon field
      }
    }
  }  


  // suggest to remove all non confirmed positions
  // only add rows for checked portfolios!!!
  if ($recon_type == RECON_STEP_TYPE_REF_MULTI_REMOVE) {
    // check if the selection is finished 
    if ($remove_filter_used <> $remove_filter_value and $remove_filter_used <> '') {
      tream_debug ('file_read ... check for unconfirmed rows ... for '.$step_row['source_id_field'].' '.$remove_filter_used, $debug);
      foreach ($db_confirm_array as $db_row) {
	tream_debug ('file_read ... check for unconfirmed rows ... check row '.$db_row[0], $debug);
	if ($db_row['confirmed'] == 1) {
	  //tream_debug ('check for unconfirmed rows ... close event '.$db_row[0], $debug);
	  $result .= file_event_close($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	} else {
	  tream_debug ('file_read ... check for unconfirmed rows ... add event for '.$db_row[0], $debug);
	  $event_type = sql_code_link(EVENT_TYPE_TRADE_MISSING);
	  if ($file_msg < $file_max_msg) {
	    $result .= file_event_add($event_type, $step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	    $file_msg = $file_msg + 1;
	  }
	}
      }
    }
  }
  
  // to find non confirmed positions, read get all db rows
  // assumes that the source file is sorted
  if ($recon_type == RECON_STEP_TYPE_REF_MULTI_REMOVE) {
    $remove_filter_field = $step_row['source_id_field'];
    $remove_filter_value = file_get_field($remove_filter_field, $line, $field_positions, $field_names, $ref_fields, $ref_values, $debug);
    tream_debug ('file_read ... check for unconfirmed rows ... filter field '.$remove_filter_field.': '.$remove_filter_value, $debug);

    if ($remove_filter_used <> $remove_filter_value) {
      $sql_db_confirm = 'SELECT '.$step_row['dest_field'].', 0 as confirmed FROM '.$step_row['dest_table'].' WHERE '.$remove_filter_field.' = '.$remove_filter_value.' GROUP BY '.$step_row['dest_field'].';';
      tream_debug ('file_read ... confirm position query is '.$sql_db_confirm.' ', $debug);
      $db_confirm_result = mysql_query($sql_db_confirm);
      $db_confirm_array = [];
      tream_debug ('file_read ... confirm position query ... got result.', $debug);
      while($db_confirm_row = mysql_fetch_assoc($db_confirm_result)) {
	//tream_debug ('confirm position query add '.$db_confirm_row.'.', $debug);
	$db_confirm_array[] = $db_confirm_row;
      }
      $remove_filter_used = $remove_filter_value;
      
      tream_debug ('file_read ... confirm position query done.', $debug);
    } else {
      tream_debug ('file_read ... confirm position query skiped because '.$remove_filter_used.' = '.$remove_filter_value.'.', $debug);
    }
  }

  // loop over the file lines
  $row_nbr = 0;
  $file_status = 0;
  
  for ($i = 0; $i < count($line); $i++) { 

      tream_debug ('file_read ... line '.$i.' ', $debug);
      /*if ($step_row['recon_step_id'] == 8) {
	$result .= 'check line '.$i.' type '.$recon_type.' with status '.$line_status[$i].'<br>';
      } */

    // loop over the reconciliation steps to create the complete ref array
    $sql_query_steps = 'SELECT recon_step_id, recon_step_type_id, dest_table, dest_field, source_field_name, source_id_field, dest_id_field, filter, recon_value_type_id, event_description_unique, event_description, stop_line_on_err, err_step_for_step, solution1_text, solution1_sql, solution2_text, solution2_sql, order_nbr, comment FROM recon_steps WHERE recon_file_id = '.$file_id.' ORDER BY order_nbr;';
    //echo $sql_query_steps;
    $step_result = mysql_query($sql_query_steps);
    while ($step_row = mysql_fetch_assoc($step_result)) {
    
      $dest_id_field = $step_row['dest_id_field'];
      $dest_field = trim($step_row['source_field_name']);

      // $result .= '<br>'.$step_row['comment'].'<br>';

      // get the values that are needed to decide what to do
      $recon_type = $step_row['recon_step_type_id'];
      $recon_type_name = sql_get_value("recon_step_types", "recon_step_type_id", $recon_type, "type_name");
      tream_debug ('file_read ... step '.$step_row['recon_step_id'].' type '.$recon_type_name, $debug);

      if ($line_status[$i] == 0) {
	$use_line = true;
      } else {  
	$recover_status = ($step_row['err_step_for_step'] + 1) * -1;
        tream_debug ('file_read ... check if step '.$step_row['recon_step_id'].' can be excuded line status is '.$line_status[$i].' and this step would recover '.$recover_status, $debug);
	if ($recover_status == $line_status[$i]) {
	  //$result .= 'step '.$step_row['recon_step_id'].' excluded because status is'.$line_status[$i].'<br>';
	  $line_status[$i] = 0;
	  $use_line = true;
	} else {
	  $use_line = false;
	}
      }  
      // filter lines
      if ($step_row['filter'] <> '') {
        if (strstr($step_row['filter'], "=")) {
	  $filter_part = explode("=",$step_row['filter']);
	  if ($filter_part[1] <> file_get_field($filter_part[0], $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i])) {
            tream_debug ('file_read ... line '.$i.' filtered because '.$filter_part[1].' = '.file_get_field($filter_part[0], $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]).' ', $debug);
	    $use_line = false;
	  }
	}
        if (strstr($step_row['filter'], "<>")) {
	  $filter_part = explode("<>",$step_row['filter']);
	  if ($filter_part[1] == $filter_part[0]) {
            tream_debug ('file_read ... line '.$i.' filtered because '.$filter_part[1].' <> '.$filter_part[0].' ', $debug);
	    $use_line = false;
	  }
	}
      }  
      if ($use_line == true) {
        tream_debug ('file_read ... use line '.$i.' ('.$line[$i].')', $debug);
        tream_debug ('file_read ... recon type '.$recon_type.'.', $debug);
	// move the types to fixed values
	if ($recon_type == RECON_STEP_TYPE_REF_MULTI OR $recon_type == RECON_STEP_TYPE_REF_MULTI_SUGGEST) {
	  $row_id = file_get_multi_key_ref($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	  if ($row_id > 0) {
	    $result .= 'get multi key '.$step_row['dest_id_field'].': '.$row_id.' -> ';
	    $ref_values[$i][ref_field_nbr($dest_id_field, $ref_fields)] = $row_id;
	    //$result .= 'multi ref '.$dest_id_field.' '.$row_id.' found in line '.$i.'<br>';
	    $line_status[$i] = 0;
	    if ($recon_type == RECON_STEP_TYPE_REF_MULTI_SUGGEST) {
	      // close any pevious opened event
	      $result .= file_event_close($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	    }
	  } else {
	    if ($recon_type == RECON_STEP_TYPE_REF_MULTI_SUGGEST) {
	      // create an event
	      $event_type = sql_code_link(EVENT_TYPE_TRADE_MISSING);
	      if ($file_msg < $file_max_msg) {
	        $result .= file_event_add($event_type, $step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	        $file_msg = $file_msg + 1;
	      }
	    }
	    // everything below 0 means that this line is crap and should not be used any more
	    // but the line can be reactivated if a later step is the error solving step for the step that failed
	    // + 1 because it should be possible to recover also step 0
	    $line_status[$i] = ($step_row['recon_step_id'] + 1) * -1;
	      
	  }
	}

	// check if a tream position can be confirmed by the reconciliation file
	if ($recon_type == RECON_STEP_TYPE_REF_MULTI_REMOVE) {
	  $db_confirm_array = file_confirm_multi_key_ref($db_confirm_array, $step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	}
	
	// add a new row based on a unique id
	if ($recon_type == RECON_STEP_TYPE_ADD OR $recon_type == RECON_STEP_TYPE_REF) {
	  $row_id =                 file_get_ref($recon_type, $step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	  $source_value = file_get_field($step_row['source_field_name'], $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
          tream_debug ('file_read ... row id for '.$step_row['dest_field'].' '.' = '.$row_id, $debug);

	  $event_stop = $step_row['stop_line_on_err'];

	  if ($recon_type == RECON_STEP_TYPE_REF) {
	    if ($row_id <= 0) {
	      if ($event_stop == 1) {
		// everything below 0 means that this line is crap and should not be used any more
		// but the line can be reactivated if a later step is the error solving step for the step that failed
		// + 1 because it should be possible to recover also step 0
		$tmp_status = ($step_row['recon_step_id'] + 1) * -1;	    
		$line_status[$i] = $tmp_status; 		    
		tream_debug ('file_read ... set status to '.$tmp_status.' for '.$i, $debug);
	      }
	    }
	  }

	  // replace the external id the the db id
	  if ($row_id > 0) {
	    $result .= 'id found for '.$source_value.' in '.$step_row['dest_table'].': '.$row_id.' -> ';
	    $ref_values[$i][ref_field_nbr($dest_id_field, $ref_fields)] = $row_id;
	    tream_debug ('file_read ... set ref field '.$dest_id_field.' (pos '.ref_field_nbr($dest_id_field, $ref_fields).') to '.$row_id.' for '.$i, $debug);
	  } else {
	    $result .= 'NO id found for '.$source_value.' in '.$step_row['dest_table'].'.';
	    //echo 'r'.$row_id;
	    $ref_values[$i][ref_field_nbr($dest_id_field, $ref_fields)] = $row_id;
	    // everything below 0 means that this line is crap and should not be used any more
	    $tmp_status = ($step_row['recon_step_id'] + 1) * -1;	    
	    $line_status[$i] = $tmp_status; 		    
	    tream_debug ('file_read ... do not use line because ref not found in '.$i, $debug);
	  }
	  //$result .= file_get_field($pk_field, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]).'<br>';
	}

	// add a value (the correct dest row must be already defined)
	if ($recon_type == RECON_STEP_TYPE_ADD_VALUE) {
	  //$result .= 'add field '.$step_row['source_field_name'].'<br>';
	  /*if ($step_row['source_field_name'] == 'FREMD-REF-NR') {
	    $result .= 'add '.file_get_field("FREMD-REF-NR", $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]).' for '.file_get_field("trade_id", $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]).'<br>';
	  } */
	  //$result .= 'a1:'.file_get_field("security_id", $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]).'<br>';
	  $result .= file_add_value_to_db($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]).' -> ';
	  //$result .= file_add_value_to_db($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]).' of '.$step_row['dest_table'].' id '.$ref_values[$i][ref_field_nbr($dest_id_field, $ref_fields)].'<br>';
	  //$result .= 'a2:'.file_get_field("security_id", $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]).'<br>';
	}

	// compare a value (the correct dest row must be already defined)
	if ($recon_type == RECON_STEP_TYPE_COMPARE) {
	  $db_field_value = file_get_value_from_db($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	  $file_value = file_get_field($step_row['source_field_name'], $line[$i], $field_positions, $field_names, $ref_fields, $ref_values);
          tream_debug ('file_read ... compare file value '.$file_value.' (field '.$step_row['source_field_name'].') with db '.$db_field_value.' (type '.$step_row['recon_value_type_id'].').', $debug);
          if ($step_row['recon_value_type_id'] == RECON_VALUE_TYPE_INT) {
            $db_field_value = intval($db_field_value);
            $file_value = intval($file_value);
          }
          if ($step_row['recon_value_type_id'] == RECON_VALUE_TYPE_NUM) {
            $db_field_value = floatval($db_field_value);
            $file_value = floatval($file_value);
          }
          tream_debug ('file_read ... compare after convert file value '.$file_value.' with db '.$db_field_value.' (type '.$step_row['recon_value_type_id'].').', $debug);
	  if ($db_field_value == $file_value) {
	    // close any pevious opened event
	    $result .= 'check '.$step_row['source_field_name'].' '.$db_field_value.' -> OK ';
	    $result .= file_event_close($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	  } else {
	    // create an event
	    $event_type = sql_code_link(EVENT_TYPE_TRADE_MISSING);
	    if ($file_msg < $file_max_msg) {
	      $result .= 'check '.$step_row['source_field_name'].' is '.$db_field_value.' in TREAM, but could be '.$file_value.' -> ';
	      $result .= file_event_add($event_type, $step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	      $file_msg = $file_msg + 1;
	    }
	  }
	}
	
	// add a db value to the list 
	if ($recon_type == RECON_STEP_TYPE_GET) {
	  //$result .= 'b1:'.file_get_field("security_id", $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]).'<br>';
	  $dest_field = trim($step_row['source_field_name']);
	  if ($dest_field == '') {
	    // if source field is not set use dest field as backup
	    $dest_field = $step_row['dest_field'];
	  }
	  $ref_field_nbr = ref_field_nbr($dest_field, $ref_fields);
	  //$result .= 'b2:'.$dest_field.' ('.$ref_field_nbr.')<br>';
	  if ($ref_field_nbr >= 0) {
	    $db_field_value = file_get_value_from_db($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	    $ref_values[$i][ref_field_nbr($dest_field, $ref_fields)] = $db_field_value;
	  } else {
	    // create an system error event
	    tream_debug ('field '.$dest_field.' could not be loaded, because the dest field is missing.', $debug);
	  }
	  $result .= 'get '.$dest_field.' '.$db_field_value.' -> ';
	}
      }
      $row_nbr = $row_nbr + 1;
    }  
    $result .= '<br>';
  }
  tream_debug ('file_read ... done: '.$result, $debug);
  return $result;
}

// create a list of files and run the reconciliation for each file
function file_loop($file_id, $debug) {
  tream_debug ('file_loop ... ', $debug);

  $result = '';
  $file_date = time();
  $file_date_pos = 0;
  //$file_id = sql_get_value("recon_files", "file_name", "JB trade file", "recon_file_id");
  $file_name_pattern =  sql_get_value("recon_files", "recon_file_id", $file_id, "file_path");
  $file_type          = sql_get_value("recon_files", "recon_file_id", $file_id, "recon_file_type_id");
  $file_back_days =     sql_get_value("recon_files", "recon_file_id", $file_id, "back_days");
  $file_max_msg =       sql_get_value("recon_files", "recon_file_id", $file_id, "max_messages");
  $file_positions_all = sql_get_value("recon_files", "recon_file_id", $file_id, "fixed_field_positions");
  $file_names_all =     sql_get_value("recon_files", "recon_file_id", $file_id, "fixed_field_names");
  $file_name_pattern =  sql_get_value("recon_files", "recon_file_id", $file_id, "file_path");
  
  // loop over the files
  tream_debug ('file_loop ... start: '.$file_name_pattern, $debug);
  while ($file_date_pos <= $file_back_days) {
    $file_name = str_replace("[[date,Ymd]]", date("Ymd", $file_date), $file_name_pattern);
    
    $result .= 'check file '.$file_name.'<br>';

    $file_matches = glob($file_name);
    if ($file_matches[0] <> '') {
      if ($file_type == FILE_TYPE_FIXED) {
	tream_debug ('found file '.$file_matches[0], $debug);
	$result .= file_read($file_id, $file_matches[0], $file_positions_all, $file_names_all, $file_max_msg, $debug);
      }  
    }  

    $result .= '<br>';

    $file_date = $file_date - (24 * 60 * 60);
    $file_date_pos = $file_date_pos + 1;
  }
  
  $result .= '<br><br>';
  tream_debug ('file_loop ... done: '.$result, $debug);
  return $result;
}

// create a list of files and run the reconciliation for each file
/*function file_loop() {
  $result = '';
  $file_date = time();
  $file_date_pos = 0;
  $file_id = sql_get_value("recon_files", "file_name", "JB trade file", "recon_file_id");
  $file_name_pattern = sql_get_value("recon_files", "file_name", "JB trade file", "file_path");
  $file_back_days = sql_get_value("recon_files", "file_name", "JB trade file", "back_days");
  $file_positions_all = sql_get_value("recon_files", "file_name", "JB trade file", "fixed_field_positions");
  $file_names_all = sql_get_value("recon_files", "file_name", "JB trade file", "fixed_field_names");
  
  // loop over the files
  while ($file_date_pos < $file_back_days) {
    $file_name_pattern = sql_get_value("recon_files", "file_name", "JB trade file", "file_path");
    $file_name = str_replace("[[date,Ymd]]", date("Ymd", $file_date), $file_name_pattern);
    
    $result .= 'check file '.$file_name.'<br>';

    $file_matches = glob($file_name);
    if ($file_matches[0] <> '') {
      $result .= file_read($file_matches[0], $file_positions_all, $file_names_all);
    }  

    $result .= '<br>';

    $file_date = $file_date - (24 * 60 * 60);
    $file_date_pos = $file_date_pos + 1;
  }
  
  $result .= '<br><br>';
  return $result;
} */


?> 
