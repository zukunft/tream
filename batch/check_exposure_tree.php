<html>
 <head>
  <title>TREAM exposure tree check</title>
 </head>
 <body>
   TREAM - 
 <?php 
 
/* 

check_exposure_tree.php - check the exposure tree consistency and create warning messages if needed

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
include_once './check_portfolio.php';

  echo 'an open source portfolio system for external asset manager<br>';

function tree_exposures($exposure_id, $description, $security_type_id, $level, $display) {

  $pos = $level;
  while ($pos > 0) {
    tream_display ('---', $display);
    $pos = $pos -1;
  }

  if (trim($description) <> '') {
    tream_display ('-> '.$description.'', $display);
  }    

  // display linked sec type
  $sec_type_name = sql_get_value('security_types', 'security_type_id', $security_type_id, 'description');
  if (trim($sec_type_name) <> '') {

    tream_display ('  "'.$sec_type_name.'"', $display);
  }    

  if (trim($description) <> '') {
    tream_display ('<br>', $display);
  }    

  // check if this exposure is splitted up into sub items
  $sql_sub_exposure = 'SELECT exposure_item_id, description, currency_id, security_type_id FROM exposure_items WHERE is_part_of = '.$exposure_id.';';
  $sql_sub_result = mysql_query($sql_sub_exposure);
  //tream_display ('check if '.$description.' has subitems->', $display);
  while ($sub_row = mysql_fetch_assoc($sql_sub_result)) {
    //tream_display ('found '.$sub_row['description'].', here it comes<br>', $display);
    tree_exposures($sub_row['exposure_item_id'], $sub_row['description'], $sub_row['security_type_id'], $level + 1, $display);
  }
  
  $result = "";

  return $result;
}


// check the asset allocation of one portfolio
// if display is set the results are shown for mainly for debugging; otherwise only messages are created
function tream_check_exposure_type($type_id, $display) {
  // main batch job 
  tream_display (' - exposure tree for type "'.sql_get_portfolio_name($type_id).'" at '.date('Y-m-d H:i:s').'<br><br>', $display);

  // show the type selector
  tream_display ('<form action="">', $display);
  tream_display ('<select name="id">', $display);
  $sql_query_types = 'SELECT type_name, exposure_type_id FROM exposure_types WHERE exposure_type_id > 0 ORDER BY type_name;';
  $type_result = mysql_query($sql_query_types);
  while ($type_row = mysql_fetch_assoc($type_result)) {
    if ($type_id == $type_row['exposure_type_id']) {
      tream_display ('<option selected value="'.$type_row[1].'">'.$type_row['type_name'].'</option>', $display);
    } else {
      tream_display ('<option value="'.$type_row['exposure_type_id'].'">'.$type_row['type_name'].'</option>', $display);
    }  
  }
  tream_display ('<input type="submit" value="Display">', $display);
  tream_display ('</select>', $display);
  tream_display ('</form>', $display);
  tream_display ('<br>', $display);

  // loop over the items of the type and fill up the usage
  $sql_item = 'SELECT exposure_item_id, description, currency_id, security_type_id FROM exposure_items WHERE exposure_type_id = '.$type_id.' AND (is_part_of IS NULL OR is_part_of = 0) ORDER BY order_nbr;';
  $sql_item_result = mysql_query($sql_item);
  while ($item_row = mysql_fetch_assoc($sql_item_result)) {
    if (trim($item_row['description']) <> '') {
      tree_exposures($item_row['exposure_item_id'], $item_row['description'], $item_row['security_type_id'], 0, $display);
    }  
  }  
 
}  



  // init
  $link = sql_open(FALSE);

  if (isset($_GET['id'])) {
    $type_id = $_GET['id'];
  } else {
    $type_id = 3;
  }

  tream_check_exposure_type($type_id, TRUE);
  
  echo '<br>';
  echo '<br>';
  echo 'list wrong linked sec types<br>';
  echo '<br>';
  
  // loop over the items of the type and fill up the usage
  $sql_item = 'SELECT s.description, s.security_type_id, count(e.exposure_type_id) as linked, s.code_id FROM security_types s LEFT JOIN exposure_items e ON s.security_type_id = e.security_type_id GROUP BY s.description;';
  $sql_item_result = mysql_query($sql_item);
  while ($item_row = mysql_fetch_assoc($sql_item_result)) {
    if ($item_row['linked'] <> '1' and $item_row['code_id'] <> 'ref') {
      if ($item_row['linked'] <> '0') {
	echo 'linked more than once: '.$item_row['description'].'<BR>';
      } else {
	echo 'not linked: '.$item_row['description'].'<BR>';
      }  
    }  
  }  

  echo '<BR>tree check finished<br>';


  sql_close($link);

 ?> 
 </body>
</html> 
