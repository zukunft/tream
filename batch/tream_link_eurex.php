<html>
 <head>
  <title>Vola import</title>
 </head>
 <body>
 <?php 
 
/* 

tream_link_eurex.php - import vola base values from the internet

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

define("THIS_SCRIPT_VERBOSE_LEVEL", 2); // 0 = show only actions, 1 = show warnings also, 2 = show also infos


echo 'Start';

$url = 'http://www.eurexchange.com/exchange-en/products/equ/opt/31302/';

//exec("wget ".$url);
//echo $url;
//$body = http_parse_message(http_get($url))->body; 
$body = file_get_contents($url); 
//print_r($info);
echo $body;


//phpinfo();
echo 'End';


 
 ?> 
 </body>
</html> 
