<?php 
 
// Link from TREAM to Sungard Market Map

// upload missing security symbols
function mm_instrument_import() {
  $result = 'read Market Map symbols from upload file<br>';
  $fh = fopen('/data/crm/upload/ISIN_upload.csv','r');
  $file_status = 0;
  while ($line = fgets($fh) and $file_status == 0) {
    $sec_row = explode(",", $line);
    $mm_symbol = $sec_row[0];
    $ISIN = $sec_row[4];
    
    if ($row_nbr == 0) {
      if ($mm_symbol <> 'Symbol' or $ISIN <> 'ISIN') {
	$result .= 'error in file format<br>';
	$file_status = -1;
      }
    } else {
      if ($ISIN <> '') {
	$result .= 'check '.$ISIN.'<br>';
	$sec_id = sql_get("SELECT security_id FROM securities WHERE ISIN = '".$ISIN."';");
	if ($sec_id > 0) {
	  $db_mm_symbol = sql_get("SELECT symbol_market_map FROM securities WHERE security_id = ".$sec_id.";");
	  //$result .= 'compare '.$mm_symbol.' with '.$db_mm_symbol.'<br>';
	  if ($db_mm_symbol <> $mm_symbol) {
	    sql_set("securities", "security_id", $sec_id, "symbol_market_map", $mm_symbol, "text");
	  }
	}
      }  
    }
    
    $row_nbr = $row_nbr + 1;
  }
  fclose($fh);

  $result .= '<br><br>';
  return $result;
}


?> 
