<html>
 <head>
  <title>TREAM portfolio overview</title>
 </head>
 <body>
 <?php 
 
/* 

portfolio_overview.php - display all asset allocation for one portfolio and create a limit message if needed


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
include_once './tream_portfolio_check.php';

  echo 'TREAM - create Target Result in External Asset Managment<br>';

  // init
  $link = sql_open(FALSE);

  if (isset($_GET['id'])) {
    $portfolio_id = $_GET['id'];
  } else {
    $portfolio_id = 1;
  }

  $debug_in = $_GET['debug'];

  if ($debug_in == 1) {
    $debug = TRUE;
  } else {  
    $debug = FALSE;
  }

  tream_check_portfolio($portfolio_id, TRUE);
  
  echo 'portfolio overview finished<br>';


  sql_close($link);

 ?> 
 </body>
</html> 
