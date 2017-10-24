<?php 
 
/* 

tream_link_jb.php - Link from TREAM to Bank Julius Bär EAM files

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

define("JB_FILE_PATH", "/data/crm/upload/"); // server file path where the upload files from Bär are saved
define("JB_TRADE_CHECK_DAYS", 20); // check trade files of the last 10 days from Bank Bär

define("THIS_SCRIPT_PERSON_ID", 24);

// check of jb transactions
function jb_trade_import() {
  $result = 'read trade file from JB and report diffenrences<br><br>';

  $file_date = time();
  $file_date_pos = 0;
  while ($file_date_pos < JB_TRADE_CHECK_DAYS) {
    $file_name = JB_FILE_PATH.'03110729.TRINKLER.BEW.'.date("Ymd", $file_date).'*.TXT';
    $file_matches = glob($file_name);
    if ($file_matches[0] <> '') {
      $result .= 'read file '.$file_matches[0].' for trades<br>';

      $fh = fopen($file_matches[0],'r');

      // in the first loop just check the depot movements; in a second run check the account movements
      $row_nbr = 0;
      $file_status = 0;
      while ($line = fgets($fh) and $file_status == 0) {

	// just depot movements
	$trade_type = substr($line, 0, 4);
	
	if ($trade_type == "DBEW") {
	
	  // split the position line
	  $valor = substr($line, 40, 9);
	  $ISIN = substr($line, 70, 12);
	  $Sec_name_short = substr($line, 50, 19);
	  $sec_curr = substr($line, 666, 3);  
	  $bank_portfolio_id = substr($line, 10, 8);
	  $trade_date = strtotime(substr($line, 206, 8));  
	  //$trade_date = date_create_from_format('Ymd', substr($line, 206, 8));
	  $bank_ref_id = substr($line, 461, 15);  
	  $trade_price = floatval(substr($line, 164, 15));  
	  $trade_size = floatval(substr($line, 139, 18));  
	  $trade_curr = substr($line, 202, 3);  
	  $storno_code = substr($line, 162, 1);  
	  
	  $result .= db_check_security($ISIN, $valor, $Sec_name_short, $sec_curr);
	  //echo 'debug sec curr '.$sec_curr.'<br>';
	  
	  $result .= trade_check("Julius Baer", $bank_portfolio_id, $ISIN, $valor, $Sec_name_short, $trade_date, $trade_size, $trade_price, $trade_curr, $bank_ref_id, $storno_code);
	  
	  $result .= '<br>';
	  
	}
	
	$row_nbr = $row_nbr + 1;
      }

      fclose($fh);
      

      $result .= '<br>';

      // second reading of the file to add the cash payments to the trades
      $result .= 'reread file '.$file_matches[0].' for cash flows<br>';

      $fh = fopen($file_matches[0],'r');
      while ($line = fgets($fh) and $file_status == 0) {
	// just account movements
	$trade_type = substr($line, 0, 4);
	
	if ($trade_type == "KBEW") {
	  // split the position line
	  $trade_amount = substr($line, 139, 20);  
	  $trade_fee_ext = substr($line, 398, 20);  
	  $trade_fee_bank = substr($line, 419, 20);  
	  $trade_fx_rate = substr($line, 91, 19);  
	  $trade_premium_prf_curr = substr($line, 670, 20);  
	  $trade_curr = substr($line, 202, 3);  
	  $trade_prf_curr = substr($line, 691, 3);  
	  $trade_ins_curr = substr($line, 666, 3);  
	  $bank_portfolio_id = substr($line, 10, 9);
	  $bank_ref_id = substr($line, 461, 15);  
	  $storno_code = substr($line, 162, 1);  

	  $result .= ' rate '. $trade_fx_rate.' '.$trade_premium_prf_curr;
	  
	  
	  // check the portfolio
	  $result .= ' search for '. $bank_portfolio_id;
	  $portfolio_id = sql_find_portfolio_by_bank_id($bank_portfolio_id);
	  $portfolio_id = sql_find_portfolio_by_bank_id($bank_portfolio_id);
	  if ($portfolio_id > 0) {
	    $result .= ' portfolio '. $portfolio_id.' found ';
	    $portfolio_name = sql_get("SELECT portfolio_name FROM portfolios WHERE portfolio_id = '".$portfolio_id."';");
	    $result .= ' portfolio '. $portfolio_name.' found ';

	    $trade_id = sql_trade_find_bank($bank_ref_id, $portfolio_id);
	    if ($trade_id > 0) {      
	      $result .= 'trade '. $trade_id.' found ';
	      $result .= 'check cash flows for trade id: '.$trade_id.'->';
	      sql_add_trade_premium  ($trade_id, $trade_amount);
	      sql_add_trade_fees_ext ($trade_id, $trade_fee_ext);   
	      sql_add_trade_fees_bank($trade_id, $trade_fee_bank);
	      $result .= '<br>';
	    }
	  }
	  
	}
	$row_nbr = $row_nbr + 1;
      }

      $result .= '<br><br>';

    }

    fclose($fh);

    $file_date = $file_date - (24 * 60 * 60);
    $file_date_pos = $file_date_pos + 1;
  }


  $result .= '<br><br>';
  return $result;
}

// check of jb positions
function jb_position_import() {
  $result = 'read position file from JB and report diffenrences<br>';
  
  // get latest pos file  
  $latest_pos_file = '';
  $file_date = time();
  $file_date_pos = 0;
  while ($file_date_pos < JB_TRADE_CHECK_DAYS) {
    $file_name = JB_FILE_PATH.'03110729.TRINKLER.POS.'.date("Ymd", $file_date).'*.TXT';
    $file_matches = glob($file_name);
    if ($file_matches[0] <> '' and $latest_pos_file == '') {
      $latest_pos_file = $file_matches[0];
    }
    $file_date = $file_date - (24 * 60 * 60);
    $file_date_pos = $file_date_pos + 1;
  }

  // loop over position files
  $fh = fopen($latest_pos_file,'r');
  while ($line = fgets($fh)) {
    $valor = substr($line, 98, 9);
    $ISIN = substr($line, 108, 12);
    $sec_curr = trim(substr($line, 145, 5));
    //echo $sec_curr.'.';
    $Sec_name_short = trim(substr($line, 121, 19));
    $pos_date = date_create_from_format('Ymd', substr($line, 26, 8));
    $in_date = date_create_from_format('Ymd', substr($line, 574, 8));
    $in_price = trim(substr($line, 555, 18));
    
    $result .= db_check_security($ISIN, $valor, $Sec_name_short, $sec_curr);
    $sec_id = sql_get_security_id_from_ISIN($ISIN);
    if ($sec_id > 0) {
      $sec_position = floatval(substr($line, 535, 20));
      $bank_portfolio_id = substr($line, 7, 8);
      $portfolio_id = sql_find_portfolio_by_bank_id($bank_portfolio_id);
      if ($portfolio_id > 0) {
	$portfolio_name = sql_get_portfolio_name($portfolio_id);
	$account_id = sql_get_portfolio_account($portfolio_id);
	$sec_curr_id = sql_get_curr_id($sec_curr);
	$db_sec_pos = floatval(sql_get("SELECT position FROM v_portfolio_pos WHERE portfolio_id = '".$portfolio_id."' AND security_id = ".$sec_id.";"));
        $event_key = 'Sec pos of '.$sec_id.' in '.$portfolio_id;
        $event_text = 'Position of '.$Sec_name_short.' (ISIN '.$ISIN.') in '.$portfolio_name.' is '.$db_sec_pos.', but the bank position is '.$sec_position.' as of '.date_format($pos_date, 'Y-m-d').'.';
	if ($db_sec_pos == $sec_position) {
          // $result .= 'position of '.$sec_position.' matches.';
          $result .= 'position OK, ';
	  event_close($event_key);
        } else {  
          $result .= 'tp position is '.$db_sec_pos.', but Bank position is '.$sec_position.' as of '.date_format($pos_date, 'Y-m-d').'.';
          if ($db_sec_pos == 0) {
            $solution1     = "Add a new trade of ".$sec_position." ".$Sec_name_short." at ".$in_price.".";
            $solution1_sql = sql_new_trade($account_id, $portfolio_id, $in_date, $sec_id, $sec_curr_id, $in_price, $sec_position, TRADE_TYPE_DELIVERY);
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
        $result .= '<br>';
      }
    }        
  }
  fclose($fh);

  $result .= '<br><br>';
  return $result;
}



?> 
