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

Copyright (c) 2013-2017 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>

http://tream.biz
*/ 

include_once './tream_db_adapter.php';
include_once './tream_db.php';
include_once './tream_messages.php';

define ("QUOTES_URL", "http://finance.yahoo.com/d/quotes.csv?");
define ("HIST_URL", "http://ichart.yahoo.com/table.csv?s=");

define ("MAX_SYMBOLS_AT_ONCE", 20);

// 23.03.2015/tz: ignore symbols with space

//http://ichart.yahoo.com/table.csv?s=GOOG&c=2010

//https://de.finance.yahoo.com/lookup/stocks?s=ch0012221716&t=S&m=ALL
//https://www.wikidata.org/wiki/Q52825
//https://www.wikidata.org/wiki/Special:EntityData/Q52825.json
//https://query.wikidata.org/#PREFIX entity%3A <http%3A%2F%2Fwww.wikidata.org%2Fentity%2F>%0APREFIX rdf%3A <http%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23>%0A%0ASELECT %3FpropUrl %3FpropLabel %3FvalUrl %3FvalLabel WHERE {%0A {%0A entity%3AQ52825 %3FpropUrl %3FvalUrl.%0A %3Fproperty %3Fref %3FpropUrl.%0A %3Fproperty rdf%3Atype wikibase%3AProperty.%0A %3Fproperty rdfs%3Alabel %3FpropLabel.%0A }%0A %3FvalUrl rdfs%3Alabel %3FvalLabel.%0A FILTER((LANG(%3FvalLabel)) %3D "en")%0A FILTER((LANG(%3FpropLabel)) %3D "en")%0A}%0A
//https://tools.wmflabs.org/isin/?language=de&isin=CH0012221716
//https://www.six-swiss-exchange.com/shares/security_info_de.html?id=CH0012221716

// reads the static data from yahoo
function tream_get_sec_yahoo ($symbol, $sec_id, $debug)
{
    $lineCount = 0;

    // load the stock quotes: we are opening it for reading
    // http://finance.yahoo.com/d/quotes.csv?s=  STOCK SYMBOLS  &f=  FORMAT TAGS
    $URL = QUOTES_URL."s=$symbol&f=n&e=.csv";

    tream_debug("URL: ".$URL, $debug);

    $fileHandle = fopen ($URL,"r");

    if ($fileHandle) {
	// use the fgetcsv function to store quote values into an array $lineValues
	// one symbol in one line
	do {
	    $stockValues = fgetcsv ($fileHandle, 999999, ",");
            tream_debug('Security name '.$stockValues[0].' found for '.$symbol.'; ', $debug);
	    sql_sec_name_update($sec_id, $stockValues[0], $debug);
	} while ($stockValues);

    fclose ($fileHandle);
    } else {
	// ERROR-Message in the array
	tream_debug('No security name found for '.$symbol.'; ', $debug);
    }

    return $stocks;
}

function tream_get_quote_yahoo ($symbol, $tags, $debug)
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

    tream_debug("URL: ".$URL, $debug);


    return $stocks;
}

// special case for fast update 
function sql_sec_name_update($sec_id, $sec_name, $debug) {
  if ($sec_id > 0 and $sec_name <> '') {
    $get_sql_q = "SELECT name FROM securities WHERE security_id = ".$sec_id.";";
    $db_name = sql_get($get_sql_q);
    if (trim($db_name) == '') {
        $sql_update = "UPDATE securities SET name = '".$sec_name."' WHERE security_id = ".$sec_id.";";
        $result = mysql_query($sql_update);
        tream_debug('Security name '.$sec_name.' added to '.$sec_id.' using '.$sql_update.'; ', $debug);
    } else {
        tream_debug('Security name not overwritten; ', $debug);
    }
    tream_debug('No security for data adding given; ', $debug);
  }

}
// special case for fast update 
function sql_price_update($sec_id, $timestamp, $open, $high, $low, $close, $debug) {
  $get_sql_q = "SELECT close FROM security_daily_prices WHERE security_id = ".$sec_id." AND quote_date = '".$timestamp."';";
  $db_close = sql_get($get_sql_q);
  if (trim($db_close) == '' and $close > 0) {
    $sql_update = "INSERT INTO security_daily_prices (security_id, quote_date, open, high, low, close) VALUES (".$sec_id.", '".$timestamp."', ".$open.", ".$high.", ".$low.", ".$close.");";
    $result = mysql_query($sql_update);
    tream_debug('Day added; ', $debug);
  } else {
    $sql_update = "UPDATE security_daily_prices SET security_id = ".$sec_id.", quote_date = '".$timestamp."', open = ".$open.", high = ".$high.", low = ".$low.", close = ".$close." WHERE security_id = ".$sec_id." AND quote_date = '".$timestamp."';";
    $result = mysql_query($sql_update);
    tream_debug('Day updated; ', $debug);
  }

}

function tream_save_yahoo_part($symbols, $symbol_array, $ids, $debug) {
  $qarray = tream_get_quote_yahoo($symbols,"b,a,l,d1,o,h,g", $debug);

  $pos = 0;
  foreach ($qarray as $sarray) {
  /*  foreach ($sarray as $svalue) {
      echo $svalue.'<br>';
    } */
    $symbol = $sarray[1];
    $symbol = $symbol_array[$pos];
    // tream_debug('yahoo returned symbol '.$symbol, $debug);
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
    tream_debug($symbol.': bid '.$bid.', ask '.$ask.', last '.$last.', open '.$open.', high '.$high.', low '.$low.' at '.$db_timestamp.' / '.$db_date, $debug);
    //if ($symbol_array[$pos] == $symbol) {
    if ($symbol_array[$pos] !== 'N/A') {
      $db_symbol = sql_get_value("securities", "security_id", $ids[$pos], "symbol_yahoo");
      if ($db_symbol == $symbol) {
	tream_debug(' symbol OK -> ', $debug);
	$db_time = strtotime(sql_get_value("securities", "security_id", $ids[$pos], "update_time"));
	if ($db_time < strtotime($db_timestamp)) {
	  tream_debug(' time OK -> ', $debug);
	  sql_set_no_log("securities", "security_id", $ids[$pos], "bid", $bid, '');
	  sql_set_no_log("securities", "security_id", $ids[$pos], "ask", $ask, '');
	  sql_set_no_log("securities", "security_id", $ids[$pos], "last_price", $last, '');
	  sql_set_no_log("securities", "security_id", $ids[$pos], "update_time", $db_timestamp, '');
	  // automatically update daily prices if the security needs a update; do not chech again the timestamp on the daily hist for speed reasons
	  sql_price_update($ids[$pos], $db_date, $open, $high, $low, $last, $debug);
	  //echo ' last -> '.$last;
	} else {
	  tream_debug('No new quote; ', $debug);
	}
      } else {
	echo 'db symbol error: '.$db_symbol.' <> '.$symbol.' symbol changed? ';
      }
    } else {
      echo 'internal symbol error: '.$symbol_array[$pos].' <> '.$symbol.' ';
    }
    $pos = $pos + 1;
  }
}

// splitted into packets to load at least some in case of a wrong symbol
function tream_save_yahoo($debug) {
tream_debug("Yahoo price load start<br>", $debug);

$link = sql_open();
mysql_select_db('tream');

  // get symbols
  $symbol_pos = 0;
  $symbols = '';
  $symbol_array  = [];
  $ids = [];
  $sql_get_yahoo_symbol = "SELECT symbol_yahoo, security_id FROM securities WHERE symbol_yahoo <> '' ORDER BY symbol_yahoo;";
  $result_yahoo_symbol = mysql_query($sql_get_yahoo_symbol);
  while ($symbol_row = mysql_fetch_assoc($result_yahoo_symbol)) {
    tream_debug("load: ".$symbol_row['symbol_yahoo'], $debug);
    IF (strpos($symbol_row['symbol_yahoo'], ' ') == FALSE) {
      IF ($symbols <> '') {
        $symbols .= ',';
      }
      $symbols .= $symbol_row['symbol_yahoo'];
      array_push($symbol_array, $symbol_row['symbol_yahoo']);
      array_push($ids, $symbol_row['security_id']);
      // load a part
      if ($symbol_pos > MAX_SYMBOLS_AT_ONCE) {
        tream_save_yahoo_part($symbols, $symbol_array, $ids, $debug);
	$symbol_pos = 0;
	$symbols = '';
	$symbol_array  = [];
	$ids = [];
      } else {
        $symbol_pos = $symbol_pos + 1;
      }
    } else {
      tream_debug("wrong symbol: ".$symbol_row['symbol_yahoo']." ignored.", $debug);
    }
  }

  if ($symbol_pos > 0) {
    tream_save_yahoo_part($symbols, $symbol_array, $ids, $debug);
    $symbol_pos = 0;
    $symbols = '';
    $symbol_array  = [];
    $ids = [];
  }
  
  //echo $symbols.'<br>';
  

  sql_heartbeat(SYSTEM_YAHOO_FEED);

  sql_close($link);

  tream_debug("Yahoo price load end<br>", $debug);
  
  }
?> 
