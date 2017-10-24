<html>
 <head>
  <title>TREAM - load security data from Yahoo</title>
 </head>
 <body>
 <?php 
 

/* 

tream_get_yahoo_sec.php - Yahoo link (get security data)

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
include_once './tream_get_yahoo.php';
include_once './tream_messages.php';
  // check if for debugging all result details should be shown
  $debug = 0;
  if (isset($_GET['debug'])) {
    $debug = $_GET['debug'];
  }  
  // init
  $link = sql_open(FALSE);

  tream_debug('TREAM - loading of security data from Yahoo.<br>', $debug);

  if (isset($_GET['id'])) {
    $sec_id = $_GET['id'];
    // loop over the items of the type and fill up the usage
    $sql = 'SELECT * FROM securities s WHERE s.security_id = '.$sec_id.';';
    $sql_result = mysql_query($sql);
    while ($sec_row = mysql_fetch_assoc($sql_result)) {
      $symbol = $sec_row['symbol_yahoo'];
      if ($symbol == '') {

        $ISIN = $sec_row['ISIN'];
        if ($ISIN == '') {
          tream_debug('<BR>No yahoo symbol or ISIN found<br>', $debug);
        } else {
          //tream_debug('<BR>yahoo symbol '.$name.' '.$symbol.' (ISIN '.$ISIN.', id '.$sec_id.') found<br>', $debug);
        }  
      } else {
        // check security name
        $name = $sec_row['name'];
        if ($name  == '') {
          tream_debug('load security name from yahoo using symbol '.$symbol.' (id '.$sec_id.'); ', $debug);
          tream_get_sec_yahoo ($symbol, $sec_id, $debug);
        }
        //tream_debug('<BR>yahoo symbol '.$name.' '.$symbol.' (ISIN '.$ISIN.', id '.$sec_id.') found<br>', $debug);
      }  
    }  

    tream_debug('<BR>loading of security data from Yahoo finished<br>', $debug);
  } else {
    echo 'No security selected; ';
  }
    
   tream_debug('<BR>Yahoo price update ... start.<br>', $debug);
   tream_save_yahoo($debug);
   tream_debug('<BR>Yahoo price update ... finished.<br>', $debug);


  sql_close($link); 
  echo 'Updated from Yahoo '.date("Y-m-d H:i:s");
 ?> 
 </body>
</html> 
