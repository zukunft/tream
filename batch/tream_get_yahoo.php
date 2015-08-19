<?php


/* 

tream_get_yahoo.php - Yahoo link

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

define ("QUOTES_URL", "http://finance.yahoo.com/d/quotes.csv?");
define ("HIST_URL", "http://ichart.yahoo.com/table.csv?s=");

//http://ichart.yahoo.com/table.csv?s=GOOG&c=2010

function tream_get_quote_yahoo ($symbol, $tags)
{
    $lineCount = 0;
    $stocks = array();

    // load the stock quotes: we are opening it for reading
    // http://finance.yahoo.com/d/quotes.csv?s=  STOCK SYMBOLS  &f=  FORMAT TAGS
    $URL = QUOTES_URL."s=$symbol&f=$tags&e=.csv";
    $fileHandle = fopen ($URL,"r");

    if ($fileHandle) {
	// use the fgetcsv function to store quote values into an array $lineValues
	// one symbol in one line
	do {
	    $stockValues = fgetcsv ($fileHandle, 999999, ",");
	    if ($stockValues) {
		$lineCount++;
		$stocks[$lineCount] = $stockValues;
	    }
	} while ($stockValues);

    fclose ($fileHandle);
    } else {
	// ERROR-Message in the array
	$stocks[0][0] = "ERROR";
	$stocks[0][1] = "No data found.";
    }

    return $stocks;
}

// special case for fast update 
function sql_price_update($sec_id, $timestamp, $open, $high, $low, $close) {
  $get_sql_q = "SELECT close FROM security_daily_prices WHERE security_id = ".$sec_id." AND quote_date = '".$timestamp."';";
  $db_close = sql_get($get_sql_q);
  if (trim($db_close) == '' and $close > 0) {
    $sql_update = "INSERT INTO security_daily_prices (security_id, quote_date, open, high, low, close) VALUES (".$sec_id.", '".$timestamp."', ".$open.", ".$high.", ".$low.", ".$close.");";
    $result = mysql_query($sql_update);
    echo 'day added.<br>';
  } else {
    $sql_update = "UPDATE security_daily_prices SET security_id = ".$sec_id.", quote_date = '".$timestamp."', open = ".$open.", high = ".$high.", low = ".$low.", close = ".$close." WHERE security_id = ".$sec_id." AND quote_date = '".$timestamp."';";
    $result = mysql_query($sql_update);
    echo 'day updated.<br>';
  }

}

function tream_save_yahoo() {
echo "Yahoo price load start<br>";

$link = sql_open();
mysql_select_db('cream');

// get symbols
  $symbols = '';
  $symbol_array  = [];
  $ids = [];
  $sql_get_yahoo_symbol = "SELECT symbol_yahoo, security_id FROM securities WHERE symbol_yahoo <> '' ORDER BY symbol_yahoo;";
  $result_yahoo_symbol = mysql_query($sql_get_yahoo_symbol);
  while ($symbol_row = mysql_fetch_assoc($result_yahoo_symbol)) {
    IF ($symbols <> '') {
      $symbols .= ',';
    }
    $symbols .= $symbol_row['symbol_yahoo'];
    array_push($symbol_array, $symbol_row['symbol_yahoo']);
    array_push($ids, $symbol_row['security_id']);
  }
  //echo $symbols.'<br>';
  
$qarray = tream_get_quote_yahoo($symbols,"b,a,l,d1,o,h,g");

$pos = 0;
foreach ($qarray as $sarray) {
/*  foreach ($sarray as $svalue) {
    echo $svalue.'<br>';
  } */
  $symbol = $sarray[1];
  $bid = floatval($sarray[0]);
  $ask = floatval($sarray[2]);
  $last_with_time = $sarray[4];
  $last_array = explode(" - ", $last_with_time);
  /* $last =  floatval(substr($last_array[1],3,-4)); */
  $last =  floatval(str_replace("<b>","",str_replace("</b>","",$last_array[1]))); 
  $time = strtotime($last_array[0]);
  $date = strtotime($sarray[6]);
  $open = floatval($sarray[8]);
  $high = floatval($sarray[10]);
  $low = floatval($sarray[12]);
  /* fill with last price if high low is missing */
  if ($last > 0) {
    if ($open == 0) {
      $open = $last;
    }
    if ($high == 0) {
      $high = $last;
    }
    if ($low == 0) {
      $low = $last;
    }
  } 
  if ($time == 0 and $last > 0) {
    $db_timestamp = date('Y-m-d H:i:s');
    $db_date = date('Y-m-d');
  } else {  
    $db_timestamp = strftime ('%Y-%m-%d',$date).' '.strftime ('%T',$time);
    $db_date = strftime ('%Y-%m-%d',$date);
  }
  echo $symbol.': bid '.$bid.', ask '.$ask.', last '.$last.', open '.$open.', high '.$high.', low '.$low.' at '.$db_timestamp.' / '.$db_date;
  if ($symbol_array[$pos] == $symbol) {
    $db_symbol = sql_get_value("securities", "security_id", $ids[$pos], "symbol_yahoo");
    if ($db_symbol == $symbol) {
      echo ' symbol OK -> ';
      $db_time = strtotime(sql_get_value("securities", "security_id", $ids[$pos], "update_time"));
      if ($db_time < strtotime($db_timestamp)) {
        echo ' time OK -> ';
	sql_set_no_log("securities", "security_id", $ids[$pos], "bid", $bid, '');
	sql_set_no_log("securities", "security_id", $ids[$pos], "ask", $ask, '');
	sql_set_no_log("securities", "security_id", $ids[$pos], "last_price", $last, '');
	sql_set_no_log("securities", "security_id", $ids[$pos], "update_time", $db_timestamp, '');
	// automatically update daily prices if the security needs a update; do not chech again the timestamp on the daily hist for speed reasons
	sql_price_update($ids[$pos], $db_date, $open, $high, $low, $last);
        //echo ' last -> '.$last;
      } else {
	echo 'no new quote<br>';
      }
    } else {
      echo 'db symbol error: '.$db_symbol.' <> '.$symbol.' symbol changed? <br>';
    }
  } else {
    echo 'internal symbol error: '.$symbol_array[$pos].' <> '.$symbol.'<br>';
  }
  $pos = $pos + 1;
}

  sql_heartbeat(SYSTEM_YAHOO_FEED);

  sql_close($link);

  echo "TREAM Yahoo price load finished<br>";
  
  }
?> 
