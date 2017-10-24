<?php 
 
// Link from Credit Suisse EAM file export to TREAM

define("CS_FILE_PATH", "/data/crm/upload/"); // server file path where the upload files from Bär are saved
define("CS_TRADE_CHECK_DAYS", 40); // check trade files of the last 10 days from Bank Bär

define("CS_FILE_HEADER_LINE", 5); // check trade files of the last 10 days from Bank Bär

define("THIS_SCRIPT_PERSON_ID", 24);


// check of cs positions
function cs_position_import() {
  $result = 'read position file from CS and report diffenrences<br>';
  
  // get latest pos file  
  $latest_pos_file = '';
  $file_date = time();
  $file_date_pos = 0;
  while ($file_date_pos < CS_TRADE_CHECK_DAYS) {
    $file_name = CS_FILE_PATH.'ControllerSpreadSheetRealtimePositionsSC_TZ_'.date("Ymd", $file_date).'*.csv';
    $file_matches = glob($file_name);
    if ($file_matches[0] <> '' and $latest_pos_file == '') {
      $latest_pos_file = $file_matches[0];
    }
    $file_date = $file_date - (24 * 60 * 60);
    $file_date_pos = $file_date_pos + 1;
  }

  // loop over position files
  $fh = fopen($latest_pos_file,'r');
  $line_nbr = 0;
  while ($line = fgets($fh)) {
    $line_nbr = $line_nbr + 1;
    if ($line_nbr == CS_FILE_HEADER_LINE) {
      $header_array = explode(',',$line);
    }

    if ($line_nbr > CS_FILE_HEADER_LINE) {
      $line_array = csv_explode(',',$line,'"');

      $valor = $line_array[10];
      $sec_name = $line_array[11];
      $bank_portfolio_id = $line_array[0];
      $sec_position = floatval(str_replace(',','.',str_replace('.','',$line_array[3])));
      $curr = $line_array[16];
      $curr_id = sql_get_curr_id($curr);
      $in_price = floatval(str_replace(',','.',str_replace('.','',$line_array[20])));
      $pos_date = date_create_from_format('Ymd', '20140520');
      $in_date = date_create_from_format('Ymd', '20060202');
      
      if ($valor <> '') {
      
	$result .= 'valor '.$valor;
	$result .= ', name '.$sec_name;
	$result .= ', portfolio '.$bank_portfolio_id;
	$result .= ', pos '.$sec_position;
	$result .= ', price '.$in_price;
	$result .= ', sec curr '.$curr.' (id '.$curr_id.')';
	$result .= ' -> ';
      
	$result .= db_check_security('', $valor, $sec_name);
	$sec_id = sql_get_security_id_from_valor($valor);
	if ($sec_id > 0) {
	  $result .= ', sec found ';
	  $ISIN = sql_get_security_ISIN($sec_id);
	  $portfolio_id = sql_find_portfolio_by_bank_id($bank_portfolio_id);
	  if ($portfolio_id > 0) {
	    $result .= ', found ';
	    $portfolio_name = sql_get_portfolio_name($portfolio_id);
	    $account_id = sql_get_portfolio_account($portfolio_id);
	    
	    $db_sec_pos = floatval(sql_get("SELECT position FROM v_portfolio_pos WHERE portfolio_id = '".$portfolio_id."' AND security_id = ".$sec_id.";"));
	    $event_key = 'Sec pos of '.$sec_id.' in '.$portfolio_id;
	    $event_text = 'Position of '.$sec_name.' (ISIN '.$ISIN.') in '.$portfolio_name.' is '.$db_sec_pos.', but the bank position is '.$sec_position.' as of '.date_format($pos_date, 'Y-m-d').'.';
	    if ($db_sec_pos == $sec_position) {
	      $result .= 'position OK, ';
	      event_close($event_key);
	    } else {  
	      $result .= 'tp position is '.$db_sec_pos.', but Bank position is '.$sec_position.' as of '.date_format($pos_date, 'Y-m-d').'.';
	      if ($db_sec_pos == 0) {
		$solution1     = "Add a new trade of ".$sec_position." ".$sec_name." at ".$in_price.".";
		$solution1_sql = sql_new_trade($account_id, $portfolio_id, $in_date, $sec_id, $curr_id, $in_price, $sec_position, TRADE_TYPE_DELIVERY);
	      }
	      if ($db_sec_pos <> 0) {
		$solution2     = 'adjust the trade position.';
		$solution2_sql = 'UPDATE trades () VALUES ;';
	      } else {  
		$solution2     = '';
		$solution2_sql = '';
	      }
	      event_add($event_key, $event_text, $event_type, date_format($pos_date, 'Y-m-d'), $solution1, $solution1_sql, $solution2, $solution2_sql, $account_id, $portfolio_id);
	    }
	  }
	}
      }
      $result .= '<br>';
    }        
  }
  fclose($fh);

  $result .= '<br><br>';
  return $result;
}


?> 
