<?php 
 
/* 

tream_portfolio_check.php - Calculate the portfolio exposures and create warning messages if needed

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

include_once './tream_db_adapter.php';
include_once './tream_db.php';

define("THIS_SCRIPT_VERBOSE_LEVEL", 2); // 0 = show only actions, 1 = show warnings also, 2 = show also infos

// used for debugging to be able to switch on an off the output
function tream_display($msg, $display) {
  if ($display == TRUE) {
    echo $msg;
  }
}

// get portfolio allocation
function portfolio_value($portfolio_id, $sec_id, $field_name, $display) {
  $result = '';
  $sql_portfolio_query = "SELECT security_id, security_name, ISIN, pos_value_ref, pos_value, position, decimals, ref_decimals, sec_curr, buy_price, pnl, pnl_ref FROM v_portfolio_allocation WHERE portfolio_id = '".$portfolio_id."';";
  $sql_portfolio_result = mysql_query($sql_portfolio_query);

  while ($sec_row = mysql_fetch_assoc($sql_portfolio_result)) {
    if ($sec_row['security_id'] == $sec_id) {
      $result = $sec_row[$field_name];
    }
  }
  return $result;
}

// for debugging show also the single trades
function sec_trades($portfolio_id, $sec_id, $percent, $ref_curr, $display) {
    $sec_name = portfolio_value($portfolio_id, $sec_id, "security_name", $display);
    tream_display ('Display trades for '.$sec_name.'<br>', $display);
}

// show the exposure for one security
function sec_row($portfolio_id, $sec_id, $percent, $ref_curr, $display) {
  $result = '';
  if ($sec_id > 0 and $portfolio_id > 0 and $percent <> 0) {
    $sec_pos = portfolio_value($portfolio_id, $sec_id, "position", $display);
    $sec_name = portfolio_value($portfolio_id, $sec_id, "security_name", $display);
    $sec_isin = portfolio_value($portfolio_id, $sec_id, "ISIN", $display);
    $sec_price = portfolio_value($portfolio_id, $sec_id, "buy_price", $display);
    $sec_price_ref = portfolio_value($portfolio_id, $sec_id, "buy_price", $display);
    $sec_value = portfolio_value($portfolio_id, $sec_id, "pos_value", $display);
    $sec_value_ref = portfolio_value($portfolio_id, $sec_id, "pos_value_ref", $display);
    $sec_pnl_ref = portfolio_value($portfolio_id, $sec_id, "pnl_ref", $display);
    $sec_pnl = portfolio_value($portfolio_id, $sec_id, "pnl", $display);
    $sec_decimals = portfolio_value($portfolio_id, $sec_id, "decimals", $display);
    $sec_curr = portfolio_value($portfolio_id, $sec_id, "sec_curr", $display);

    if ($sec_value <> 0 and $sec_pos <> 0) {
      if ($display == TRUE) {
        sec_trades($portfolio_id, $sec_id, $percent, $ref_curr, $display);
      }
      tream_display (round($sec_value_ref * $percent,$sec_decimals).' '.$ref_curr, $display);
      if ($percent == 1) { tream_display (' for '.round($sec_pos).' '.$sec_name.' (ISIN '.$sec_isin.')', $display); }
      if ($percent <> 1) { tream_display (' ('.round($percent*100,0).'% of '.$ref_curr.' '.round($sec_value_ref, $sec_decimals).' for '.round($sec_pos).' '.$sec_name.' (ISIN '.$sec_isin.')', $display); }
      if ($percent <> 1 and $sec_curr <> $ref_curr) { tream_display (', in '.$sec_curr.' '.round($sec_value_ref * $percent,$sec_decimals), $display); }
      if ($percent <> 1) { tream_display (')', $display); }
/*
      tream_display (', bought at '.round($sec_price,$sec_decimals);
      if ($sec_curr <> $ref_curr) { tream_display (', in ref '.round($sec_price_ref,$sec_decimals);}
      tream_display (', pnl '.round($sec_pnl,$sec_decimals);
      if ($sec_curr <> $ref_curr) { tream_display (', with fx impact '.round($sec_pnl-$sec_pnl_ref,$sec_decimals); }
      tream_display (' at '.$sec_row['name'].' id '.$sec_id.' (curr based xxx)', $display);
      */
      tream_display ('<br>', $display);
    } else {
      if ($sec_pos <> 0) {
        tream_display ('Price is missing for '.$sec_name.'<br>', $display);
      }  
    //  echo $sec_row['name'].'<br>' ;
    } 
  } else {
    tream_display ('Missing sec '.$sec_id.' with '.$percent.'<br>', $display);
  }  
  return $result;
}

// display the exposure for one exposure item
// display any sub items first
// returns the aggregated exposure for double check
function exposures($portfolio_id, $exposure_id, $description, $currency_id, $security_type_id, $position_usage, $type_name, $total_aum, $level, $ref_curr, $result_type, $display) {
  // add the item sum entry for the next level
  $position_usage[$level -1] = 0;
  $item_sum = 0;
  //tream_display ('reset sum for '.$description.'->', $display);
  $has_sub = 0;

  // check if this exposure is splitted up into sub items
  $sql_sub_exposure = 'SELECT exposure_item_id, description, currency_id, security_type_id FROM exposure_items WHERE is_part_of = '.$exposure_id.';';
  $sql_sub_result = mysql_query($sql_sub_exposure);
  //tream_display ('check if '.$description.' has subitems->', $display);
  while ($sub_row = mysql_fetch_assoc($sql_sub_result)) {
    //tream_display ('found '.$sub_row['description'].', here it comes<br>', $display);
    $position_usage = exposures($portfolio_id, $sub_row['exposure_item_id'], $sub_row['description'], $sub_row['currency_id'], $sub_row['security_type_id'], $position_usage, $type_name, $total_aum, $level -1, $ref_curr, $result_type, $display);
    $has_sub = 1;
    //tream_display ('display sub '.$sub_row['description'], $display);
  }
  //if ($has_sub == 0) {
  //  tream_display ('finished subitems<br>', $display);
  //}  

  if (trim($description) <> '') {

    // get the security exposure in pct for this exposure item
    $sql_sec = 'SELECT s.security_id, s.security_type_id, e.exposure_in_pct FROM security_exposures e, securities s WHERE e.security_id = s.security_id AND e.exposure_item_id = '.$exposure_id.';';
    //echo $sql_sec;
    if ($type_name == 'Currencies') {
      // list the single positions for the currency splitting
      $sql_sec = 'SELECT security_id,  security_type_id, 100 AS exposure_in_pct FROM securities WHERE currency_id = '.$currency_id.';';
    }
    if ($type_name == 'Asset Class') {
      // list the single positions for the security splitting
      $sql_sec = 'SELECT security_id,  security_type_id, 100 AS exposure_in_pct FROM securities WHERE security_type_id = '.$security_type_id.';';
      //tream_display ('get sec with '.$sql_sec.'->', $display);
    }
    $sql_sec_result = mysql_query($sql_sec);
    while ($sec_row = mysql_fetch_assoc($sql_sec_result)) {
      $sec_id = $sec_row['security_id'];
      if ($result_type == '') {
        //tream_display ('found sec '.$sec_id.'->', $display);
      }
      $percent = $sec_row['exposure_in_pct'] / 100;
      if ($sec_id > 0) {
	if ($result_type == '') {
	  sec_row($portfolio_id, $sec_id, $percent, $ref_curr, $display);
	}
	$sec_value_ref = portfolio_value($portfolio_id, $sec_id, "pos_value_ref") * $percent ;

	if ($sec_value_ref <> 0) {
	  // add the ref value to all levels
	  for ($level_pos = 0; $level_pos >= $level-1; $level_pos--) {
	    $position_usage[$level_pos]       = $position_usage[$level_pos]       + $sec_value_ref;
	    //tream_display ('add '.$sec_value_ref.' to sum '.$position_usage[$level_pos].' for '.$description.''.$level_pos.'->', $display);
	  }
	  $item_sum                = $item_sum                + $sec_value_ref;
	  $position_usage[$sec_id] = $position_usage[$sec_id] + $percent;
	  //tream_display ('add '.$sec_value_ref.' to sum '.$item_sum.' for '.$description.'->', $display);
	} 
      }  
    }
    
    if ($position_usage[$level-1] <> 0) {
      if ($result_type == '') {
	$usage_in_pct = $position_usage[$level-1]/$total_aum*100;
	tream_display ('<h3>'.$description.' '.round($usage_in_pct,2).' pct ('.$ref_curr.' '.round($position_usage[$level-1]).')</h3><br>', $display);
	// get exposure limits
	//tream_display (check_exposure($exposure_id, $portfolio_id, $usage_in_pct, $display)."<br><br>", $display);
	tream_display (check_exposure($exposure_id, $portfolio_id, $usage_in_pct)."<br><br>", $display);
      }
    }
  }  
      
  if ($result_type == '') {
    $result = $position_usage;
  }
  if ($result_type == 'label') {
    $result = $description;
  }
  return $result;
}

// display the exposure for one exposure type
// display any sub items first
// returns the aggregated exposure for double check
function exposure_by_type($portfolio_id, $exposure_type_id, $type_name, $total_aum, $ref_curr, $display) {
  $type_sum = 0;
  //tream_display ('reset lables<br>', $display);
  $exposure_ids = [];
  $chart_labels = '';
  $chart_values = '';
  
  // create an array with all position
  // if the sum for each position is not 100 (percent) the rest is moved to others
  $position_usage = [];
  
  // add the item sum entry for level zero; e.g. the level zero if the type level
  $position_usage[0] = 0;

  // fill the array up with all security positions of this portfolio
  $sql_portfolio_query = "SELECT security_id, 0 AS percent FROM v_portfolio_allocation WHERE portfolio_id = '".$portfolio_id."';";
  $sql_portfolio_result = mysql_query($sql_portfolio_query);
  while ($sec_row = mysql_fetch_assoc($sql_portfolio_result)) {
    $sec_id = $sec_row['security_id'];
    if ($sec_id > 0) {
      $position_usage[$sec_id] = 0;
    }
  }
      
  // loop over the exposure items of the type and fill up the usage
  $sql_item = 'SELECT exposure_item_id, description, currency_id, security_type_id FROM exposure_items WHERE exposure_type_id = '.$exposure_type_id.' AND (is_part_of IS NULL OR is_part_of = 0) ORDER BY order_nbr;';
  $sql_item_result = mysql_query($sql_item);
  while ($item_row = mysql_fetch_assoc($sql_item_result)) {
    if (trim($item_row['description']) <> '') {
      // tream_display ('list item for '.$item_row['description'].'->', $display);
      $position_usage = exposures($portfolio_id, $item_row['exposure_item_id'], $item_row['description'], $item_row['currency_id'], $item_row['security_type_id'], $position_usage, $type_name, $total_aum, 0, $ref_curr, '', $display);
      $position_label = exposures($portfolio_id, $item_row['exposure_item_id'], $item_row['description'], $item_row['currency_id'], $item_row['security_type_id'], $position_usage, $type_name, $total_aum, 0, $ref_curr, 'label', $display);
      //tream_display ('check lable '.$position_label.' has '.$position_usage[0].'<br>', $display);
      if ($position_usage[-1] > 0) {
	//tream_display ('add lable '.$position_label.'<br>', $display);
	if ($chart_labels <> '') {
	  $chart_labels .= ','.$position_label;
	  $chart_values .= ','.$position_usage[-1];
	} else {  
	  $chart_labels .= $position_label;
	  $chart_values .= $position_usage[-1];
	}
	array_push($exposure_ids, $item_row['exposure_item_id']);
      }
    } else {  
      tream_display ('description missing for '.$item_row['exposure_item_id'], $display);
    }  
  }  

  // show not assigned positions
  foreach ($position_usage AS $sec_id => $usage) {
    $percent = 1 - $usage; 
    if ($usage <> 1) {
      sec_row($portfolio_id, $sec_id, $percent, $display);
      if ($sec_id > 0) {
	$sec_value_ref = portfolio_value($portfolio_id, $sec_id, "pos_value_ref") * $percent ;

	if ($sec_value_ref <> 0) {
	  $type_sum          = $type_sum          + $sec_value_ref;
	  $position_usage[0] = $position_usage[0] + $sec_value_ref;
	}
      }  
    }

  }

  
  if ($type_sum <> 0) {
    tream_display ('<h3>Other '.round($type_sum/$total_aum*100,2).' % ('.round($type_sum).')</h3><br>', $display);
    if ($chart_labels <> '') { $chart_labels .= ','; }
    if ($chart_values <> '') { $chart_values .= ','; }
    $chart_labels .= 'Other';
    $chart_values .= round($type_sum);
    array_push($exposure_ids, 0);

  }
  //echo $chart_labels.'<br>';
  //echo $chart_values.'<br>';
  tream_display ('  <img src="/crm/batch/tream_chart_pie.php?labels='.$chart_labels.'&values='.$chart_values.'&title='.$type_name.'">', $display);
  tream_display ('  <br><br>', $display);
  // read hist return
  $xvalues = '';
  $yvalues = '';
  foreach ($exposure_ids AS $exposure_id) {
    //echo $exposure_id.'i<br>';
    $ref_fx_id = sql_get_value("currencies", "symbol", $ref_curr, "currency_id");
    $xvalue = sql_get("SELECT hist_volatility FROM exposure_item_values WHERE exposure_item_id = '".$exposure_id."' AND ref_currency_id = ".$ref_fx_id.";");
    $yvalue = sql_get("SELECT hist_return FROM exposure_item_values WHERE exposure_item_id = '".$exposure_id."' AND ref_currency_id = ".$ref_fx_id.";");
    //$xvalue = sql_get_value("exposure_item_values", "exposure_item_id", $exposure_id, "hist_volatility");
    //$yvalue = sql_get_value("exposure_item_values", "exposure_item_id", $exposure_id, "hist_return");
    if ($xvalues <> '') { $xvalues .= ','; }
    if ($yvalues <> '') { $yvalues .= ','; }
    $xvalues .= round($xvalue);
    $yvalues .= round($yvalue);
  }
  //echo $xvalues.'y'.$xvalues.'<br>';
  tream_display ('  <img src="/crm/batch/tream_chart_xy.php?&title='.$type_name.'&xaxis=risk&yaxis=return&labels='.$chart_labels.'&xvalues='.$xvalues.'&yvalues='.$yvalues.'">', $display);
      
  return $position_usage[0];
}

// check all parameters needed for one portfolio
// and create messages for the user for the missing parameters
function tream_check_portfolio_setup($portfolio_id, $display) {
  $result = '';

  $sql_check_setup = 'SELECT currency_1, currency_2 FROM v_trade_premium_ref_get_fx_missing WHERE portfolio_id = '.$portfolio_id.' GROUP BY currency_1, currency_2;';
  $sql_check_result = mysql_query($sql_check_setup);
  while ($check_row = mysql_fetch_assoc($sql_check_result)) {
    $check_msg = 'Portfolio check result: Currency pair '.$check_row['currency_1'].' to '.$check_row['currency_2'].' or factor is missing.';
    tream_display ('<h3>'.$check_msg.'</h3>', $display);
    $result .= event_add("curr pair missing ".$check_row['currency_1'].'/'.$check_row['currency_2'], check_msg, EVENT_TYPE_USER_DAILY, date('Y-m-d'), "", "", "", "", 0, $portfolio_id);
    tream_display ('<br>', $display);
  }
  
  return $result;
}

// display the portfolio selector
function tream_check_portfolio_selector($portfolio_id, $display) {
  tream_display ('<form action="">', $display);
  tream_display ('<select name="id">', $display);
  $sql_query_portfolios = 'SELECT portfolio_name, portfolio_id 
                             FROM portfolios 
                            WHERE portfolio_id > 0 
                         ORDER BY portfolio_number;';
  $portfolio_result = mysql_query($sql_query_portfolios);
  while ($portfolio_row = mysql_fetch_assoc($portfolio_result)) {
    if ($portfolio_id == $portfolio_row['portfolio_id']) {
      tream_display ('<option selected value="'.$portfolio_row[1].'">'.$portfolio_row['portfolio_name'].'</option>', $display);
    } else {
      tream_display ('<option value="'.$portfolio_row['portfolio_id'].'">'.$portfolio_row['portfolio_name'].'</option>', $display);
    }  
  }
  tream_display ('<input type="submit" value="Display">', $display);
  tream_display ('</select>', $display);
  tream_display ('</form>', $display);
  tream_display ('<br>', $display);
}


// check the asset allocation of one portfolio
// if display is set the results are shown for mainly for debugging; otherwise only messages are created
function tream_check_portfolio($portfolio_id, $display) {
  // main batch job 
  tream_display ('Portfolio overview for portfolio "'.sql_get_portfolio_name($portfolio_id).'" at '.date('Y-m-d H:i:s').'<br><br>', $display);
  
  // check parameters consistency
  tream_check_portfolio_setup($portfolio_id, $display);

  // show the portfolio selector
  tream_check_portfolio_selector($portfolio_id, $display);

  // get portfolio sums
  $portfolio_aum = sql_get("SELECT aum FROM v_portfolio_pnl WHERE portfolio_id = '".$portfolio_id."';");
  $ref_curr = sql_get("SELECT c.symbol FROM portfolios p, currencies c WHERE p.portfolio_id = '".$portfolio_id."' AND p.currency_id = c.currency_id;");

  // loop over the type
  $sql_type = 'SELECT type_name, exposure_type_id 
                 FROM exposure_types;';
  $sql_type_result = mysql_query($sql_type);
  while ($type_row = mysql_fetch_assoc($sql_type_result)) {
    tream_display ('<h1>'.$type_row['type_name'].'</h1>', $display);
    $type_sum = 0;
    if (trim($type_row['type_name']) <> 'not set') {
      $type_sum = $type_sum + exposure_by_type($portfolio_id, $type_row['exposure_type_id'], trim($type_row['type_name']), $portfolio_aum, $ref_curr, $display);
      
      tream_display ('<h2>'.$type_row['type_name'].' '.round((($type_sum / $portfolio_aum) * 100)).'% ('.round($type_sum).'/'.round($portfolio_aum).')</h2><br>', $display);
    }

    tream_display ('<br><br>', $display);
  }

}  

// main batch job to check all portfolios
function tream_check_all_portfolios($display) {
  
  tream_display ('check all portfolios at '.date('Y-m-d H:i:s').'<br><br>', $display);

  // loop over the portfolios that should be checked
  $sql_query_portfolios = 'SELECT portfolio_name, portfolio_id 
                             FROM portfolios 
                            WHERE portfolio_id > 0 
                              AND monitoring = 1 
                         ORDER BY portfolio_name;';

  $portfolio_result = mysql_query($sql_query_portfolios);
  while ($portfolio_row = mysql_fetch_assoc($portfolio_result)) {
    tream_display ('check portfolio '.$portfolio_row['portfolio_name'].'<br>', $display);
    tream_check_portfolio($portfolio_row['portfolio_id'], FALSE);
  }
  tream_display ('<br>check all portfolios done at '.date('Y-m-d H:i:s').'<br><br>', $display);
}  

?> 
