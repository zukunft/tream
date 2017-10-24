<?php 
 
/* 

tream_files_xml.php - Link to saved XML files

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

// simple parser for normal xml file

define("THIS_SCRIPT_PERSON_ID", 24);


// add cope_id to file type

// add table file type
// fix values fixed and xml



// load file
// read an xlm file and splits it up into an array based on the "record" tag
function xml_load($file_name, $record_tag) {
  tream_debug ('file_read ... ', $debug);
  $result = '';
  
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

  tream_debug ('file_read ... done: '.$result, $debug);
  return $result;
}


// jump to next record

// get field
function xml_get_field($xml_text, $field_name, $debug) {
  tream_debug ('xml_get_field ... ', $debug);
  $result = '';

  $field_found = false;

  tream_debug ('xml_get_field ... done: '.$result, $debug);
  return $result;
}



// do the reconciliation for one xml file
function xml_read($file_id, $file_name, $debug) {
  tream_debug ('xml_read ... ', $debug);
  $result = '';
  $xmlstr = '';
  
  $result .= 'read file '.$file_name.' for positions<br>';

  // read file to string
  $fh = fopen($file_name,'r');
  while ($file_line = fgets($fh)) {
    $xmlstr .= $file_line;
  }
  fclose($fh);
  
  $records = new SimpleXMLElement($xmlstr);
  
  foreach ($records->movie->characters->character as $character) {
   echo $character->name, ' played by ', $character->actor, PHP_EOL;
  }


  // loop over records
  // get security by ISIN
  // compare position
  // suggest to create diff trade
  // marco to confirm diff trades
  
  
  unset($ref_fields);
  unset($ref_values);
  
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
  tream_debug ('file_read ... loaded.', $debug);
  

  // loop over the reconciliation steps
  $sql_query_steps = 'SELECT recon_step_id, recon_step_type_id, dest_table, dest_field, source_field_name, source_id_field, dest_id_field, filter, recon_value_type_id, event_description_unique, event_description, stop_line_on_err, err_step_for_step, solution1_text, solution1_sql, solution2_text, solution2_sql, order_nbr FROM recon_steps WHERE recon_file_id = '.$file_id.' ORDER BY order_nbr;';
  //echo $sql_query_steps;
  $step_result = mysql_query($sql_query_steps);
  while ($step_row = mysql_fetch_assoc($step_result)) {
  
    // get the values that are needed to deccide what to do
    $recon_type = $step_row['recon_step_type_id'];
    tream_debug ('file_read ... step '.$recon_type, $debug);

    // add the field name in the reference array if this step is supposed to add an reference
    if ($recon_type == RECON_STEP_TYPE_ADD 
      OR $recon_type == RECON_STEP_TYPE_REF 
      OR $recon_type == RECON_STEP_TYPE_REF_MULTI_SUGGEST 
      OR $recon_type == RECON_STEP_TYPE_REF_MULTI) {
      $dest_id_field = $step_row['dest_id_field'];
      if ($dest_id_field <> '') {
	$ref_fields[] = $dest_id_field;
        tream_debug ('file_read ... get ref '.$dest_id_field.' ', $debug);
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
        tream_debug ('file_read ... ref '.$dest_id_field.' added', $debug);
      } else {
	// system event dest id field is empty, but the recon type suggest a recon field
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

      if ($line_status[$i] == 0) {
	$use_line = true;
      } else {  
	$recover_status = ($step_row['err_step_for_step'] + 1) * -1;
        tream_debug ('file_read ... check if step '.$step_row['recon_step_id'].' can be excuded line status is '.$line_status[$i].' and this step would recover '.$recover_status, $debug);
	if ($recover_status == $line_status[$i]) {
	  //$result .= 'step '.$step_row['recon_step_id'].' excuded because status is'.$line_status[$i].'<br>';
	  $line_status[$i] = 0;
	  $use_line = true;
	} else {
	  $use_line = false;
	}
      }  
      // filter lines
      if ($step_row['filter'] <> '') {
	$filter_part = explode("=",$step_row['filter']);
	if ($filter_part[1] <> file_get_field($filter_part[0], $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i])) {
	  $use_line = false;
	}
      }  
      if ($use_line == true) {
        tream_debug ('file_read ... use line '.$i.' ('.$line[$i].')', $debug);
	//$result .= $recon_type.'<br>';
	// move the types to fixed values
	if ($recon_type == RECON_STEP_TYPE_REF_MULTI OR $recon_type == RECON_STEP_TYPE_REF_MULTI_SUGGEST) {
	  $row_id = file_get_multi_key_ref($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]);
	  if ($row_id > 0) {
	    $ref_values[$i][ref_field_nbr($dest_id_field, $ref_fields)] = $row_id;
	    //$result .= 'multi ref '.$dest_id_field.' '.$row_id.' found in line '.$i.'<br>';
	    $line_status[$i] = 0;
	    if ($recon_type == RECON_STEP_TYPE_REF_MULTI_SUGGEST) {
	      // close any pevious opened event
	      $result .= file_event_close($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]);
	    }
	  } else {
	    if ($recon_type == RECON_STEP_TYPE_REF_MULTI_SUGGEST) {
	      // create an event
	      $event_type = sql_code_link(EVENT_TYPE_TRADE_MISSING);
	      $result .= file_event_add($event_type, $step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]);
	    }
	    // everything below 0 means that this line is crap and should not be used any more
	    // but the line can be reactivated if a later step is the error solving step for the step that failed
	    // + 1 because it should be possible to recover also step 0
	    $line_status[$i] = ($step_row['recon_step_id'] + 1) * -1;
	      
	  }
	}
	// add a new row based on a unique id
	if ($recon_type == RECON_STEP_TYPE_ADD OR $recon_type == RECON_STEP_TYPE_REF) {
	  $row_id = file_get_ref($recon_type, $step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i], $debug);
	  $result .= 'found '.$step_row['dest_table'].' id '.$row_id.' -> ';
          tream_debug ('file_read ... row id for '.$step_row['dest_field'].' '.' ='.$row_id, $debug);

	  $event_stop = $step_row['stop_line_on_err'];

	  if ($recon_type == RECON_STEP_TYPE_REF) {
	    if ($row_id <= 0) {
	      if ($event_stop == 1) {
		// everything below 0 means that this line is crap and should not be used any more
		// but the line can be reactivated if a later step is the error solving step for the step that failed
		// + 1 because it should be possible to recover also step 0
		$tmp_status = ($step_row['recon_step_id'] + 1) * -1;	    
		$line_status[$i] = $tmp_status; 		    
		tream_debug ('set status to '.$tmp_status.' for '.$i, $debug);
	      }
	    }
	  }

	  //echo 'i'.$i;
	  // replace the external id the the db id
	  if ($row_id > 0) {
	    //echo 'r'.$row_id;
	    $ref_values[$i][ref_field_nbr($dest_id_field, $ref_fields)] = $row_id;
	  } else {
	    //echo 'r'.$row_id;
	    $ref_values[$i][ref_field_nbr($dest_id_field, $ref_fields)] = $row_id;
	    //echo $row_id.'=';
	    //echo $ref_values[$i][0].'<br>';
	  }
	  //$result .= file_get_field($pk_field, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]).'<br>';
	}

	// add a value (the correct dest ro must be already defined)
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
	    $db_field_value = file_get_value_from_db($step_row, $line[$i], $field_positions, $field_names, $ref_fields, $ref_values[$i]);
	    $ref_values[$i][ref_field_nbr($dest_field, $ref_fields)] = $db_field_value;
	  } else {
	    // create an system error event
	    echo 'field '.$dest_field.' could not be loaded, because the dest field is missing.<br>';
	  }
	  $result .= 'load field '.$dest_field.' with value '.$db_field_value.' -> ';
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
  $file_back_days =     sql_get_value("recon_files", "recon_file_id", $file_id, "back_days");
  $file_positions_all = sql_get_value("recon_files", "recon_file_id", $file_id, "fixed_field_positions");
  $file_names_all =     sql_get_value("recon_files", "recon_file_id", $file_id, "fixed_field_names");
  $file_name_pattern =  sql_get_value("recon_files", "recon_file_id", $file_id, "file_path");
  
  // loop over the files
  tream_debug ('file_loop ... start: '.$file_name_pattern, $debug);
  while ($file_date_pos < $file_back_days) {
    $file_name = str_replace("[[date,Ymd]]", date("Ymd", $file_date), $file_name_pattern);
    
    $result .= 'check file '.$file_name.'<br>';

    $file_matches = glob($file_name);
    if ($file_matches[0] <> '') {
      $result .= file_read($file_id, $file_matches[0], $file_positions_all, $file_names_all, $debug);
    }  

    $result .= '<br>';

    $file_date = $file_date - (24 * 60 * 60);
    $file_date_pos = $file_date_pos + 1;
  }
  
  $result .= '<br><br>';
  tream_debug ('file_loop ... done: '.$result, $debug);
  return $result;
}


?> 
