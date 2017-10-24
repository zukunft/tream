<?php   
/*  

tream_chart_exposure_type.php - show the risk reward chart

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


//include("/data/crm/tream_charts.php");
/* pChart library inclusions */
/*include_once './tream_db_adapter.php';
include_once './tream_db.php';
include_once './tream_messages.php'; */
include_once '../pChart/class/pData.class.php';
include_once '../pChart/class/pDraw.class.php';
include_once '../pChart/class/pScatter.class.php';
include_once '../pChart/class/pImage.class.php';
 
//include '/data/crm/batch/tream_db_adapter_small.php';

// the technical login into the database

define("SQL_HOST", "localhost");
define("SQL_USER", "p4a");
define("SQL_PASSWORD", "xxx");
define("SQL_DATABASE", "tream"); 

//include '/data/crm/batch/tream_db.php';

function sql_open() {
  $link = mysql_connect(SQL_HOST, SQL_USER, SQL_PASSWORD);

  if (!$link) {
      die('Could not connect: ' . mysql_error());
  }

  if (!mysql_select_db(SQL_DATABASE, $link)) {
      echo 'Could not select database';
      exit;
  }
  
  return $link;
}

function sql_close($link) {
  mysql_close($link);
}

// get the first value of an sql query
function sql_get($query) {
  $result = '';
  $sql_result = mysql_query($query) or die('Query failed: ' . mysql_error() . ', when executing the query ' . $query . '.');
  $sql_array = mysql_fetch_array($sql_result, MYSQL_NUM);
  if (is_null($sql_array[0])) {
    $result = '';
  } else {  
    $result = $sql_array[0];
  }
  return $result;
}
 
function sql_get_value($table, $id_field, $id_value, $value_field) {
  $result = sql_get("SELECT ".$value_field." FROM ".$table." WHERE ".$id_field." = '".$id_value."';");
  return $result;
}


 
 function tream_chart_xy ($title, $xaxis, $yaxis, $labels, $xvalues, $yvalues) {
 
 $hsize = 600;
 $vsize = 500;
 $lsize = 20;
 $chartarea_left_spacer = 50;
 $chartarea_right_spacer = 50;
 $chartarea_top_spacer = 60;
 $chartarea_bottom_spacer = 50;
 $legend_bottom_spacer = 20;
 $legend_right_spacer = 190;

 /*
 
 $mid_pos = $hsize / 2;
 $vmid_pos = ($vsize+$lsize) / 2;
 $lable_pos = $vsize-30;
 $pie_radius= $vsize * 0.5;
 
/* Create the pData object */
$myData = new pData();  

/* Create the X axis and the binded series */
foreach ($xvalues as $xpoint) { $myData->addPoints($xpoint,"return"); }
foreach ($yvalues as $ypoint) { $myData->addPoints($ypoint,"risk"); }
$myData->setAxisName(0,$xaxis);
$myData->setAxisXY(0,AXIS_X);
$myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

/* Create the Y axis and the binded series */
$myData->setSerieOnAxis("risk",1);
$myData->setAxisName(1,$yaxis);
$myData->setAxisXY(1,AXIS_Y);
$myData->setAxisPosition(1,AXIS_POSITION_LEFT);

/* Create the 1st scatter chart binding */
$myData->setScatterSerie("return","risk",0);
$myData->setScatterSerieDescription(0,"based on 10 years history");
$myData->setScatterSerieColor(0,array("R"=>0,"G"=>0,"B"=>0));

/* Create the 2nd scatter chart binding 
$myData->setScatterSerie("risk","Probe 3",1);
$myData->setScatterSerieDescription(1,"Last Year"); */

/* Create the pChart object */
$myPicture = new pImage($hsize,$vsize,$myData);

/* Draw the background */
$Settings = array("R"=>165, "G"=>191, "B"=>224, "Dash"=>1, "DashR"=>121, "DashG"=>159, "DashB"=>207);
$myPicture->drawFilledRectangle(0,0,$hsize,$vsize,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>103, "StartG"=>166, "StartB"=>245, "EndR"=>11, "EndG"=>41, "EndB"=>77, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,$hsize,$vsize,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,$hsize,$lsize,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/Silkscreen.ttf","FontSize"=>8));
$myPicture->drawText($lsize/2,13,$title,array("R"=>255,"G"=>255,"B"=>255));

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,$hsize-1,$vsize-1,array("R"=>0,"G"=>0,"B"=>0));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/pf_arma_five.ttf","FontSize"=>8));

/* Set the graph area */
$myPicture->setGraphArea($chartarea_left_spacer,$chartarea_top_spacer,$hsize-$chartarea_right_spacer,$vsize-$chartarea_bottom_spacer);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture,$myData);

$myScatter->drawScatterScale();

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

$myScatter->drawScatterPlotChart();

// Set labels  
 //$myScatter->setLabel($myData->GetData(),$myData->GetDataDescription(),"return","1","Daily incomes",221,230,174);  

 $myScatter->drawScatterLegend($hsize-$legend_right_spacer,$vsize-$legend_bottom_spacer,array("Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER));

$myPicture->setFontProperties("../pChart/fonts/tahoma.ttf",4);  
 $LabelSettings = array("OverrideTitle"=>"TEST","ForceLabels"=>$labels,"Decimals"=>2,"TitleMode"=>LABEL_TITLE_BACKGROUND,"TitleR"=>255,"TitleG"=>255,"TitleB"=>255);
 $pos = 0;
foreach ($labels as $label) { 
 $myData->setScatterSerieDescription(0,$label);
 $myScatter->writeScatterLabel(0,$pos,$LabelSettings);
 $pos =  $pos + 1;

 }

 
 
$myScatter->drawScatterBestFit();

$myPicture->autoOutput("../pChart/example/pictures/example.drawScatterBestFit.png");

  return $myPicture; 
 }


  $debug_in = $_GET['debug'];

  if ($debug_in == 1) {
    $debug = TRUE;
  } else {  
    $debug = FALSE;
  }
 if ($debug) {echo "chart building started<br>"; }

 
// get the parameters
$type_id = $_GET['type_id'];
$ref_fx_id = $_GET['ref_fx'];
//echo "type".$type_id."<br>";
 
$title = "Assets";
$xaxis = "return";
$yaxis = "risk";

 if ($debug) {echo "connecting to database using ".SQL_USER."<br>"; }

// load the values
$link = sql_open($debug);

 if ($debug) {echo "connected to database using ".SQL_USER."<br>"; }

//echo "open<br>";
$exposure_ids = [];
$chart_labels = '';
$chart_values = '';
//$ref_fx_id = sql_get_value("currencies", "symbol", $ref_fx, "currency_id");
if ($ref_fx_id <= 0) { $ref_fx_id = 1;
}
$sql_item = 'SELECT exposure_item_id, description FROM exposure_items WHERE exposure_type_id = '.$type_id.' AND (is_part_of IS NULL OR is_part_of = 0) ORDER BY order_nbr;';
$sql_item_result = mysql_query($sql_item);
while ($item_row = mysql_fetch_assoc($sql_item_result)) {
  $this_label = trim($item_row['description']);
  if ($this_label <> '') {
    //echo 'list item for '.$item_row['description'].'->';
    //echo 'add lable '.$position_label.'<br>';
    if ($chart_labels <> '') {
      $chart_labels .= ','.$this_label;
    } else {  
      $chart_labels .= $this_label;
    }
    array_push($exposure_ids, $item_row['exposure_item_id']);
  }
}  
 
// read hist return
$xvalue_text = '';
$yvalue_text = '';
foreach ($exposure_ids AS $exposure_id) {
  //echo $exposure_id.'i<br>';
  $xvalue = sql_get("SELECT hist_volatility FROM exposure_item_values WHERE exposure_item_id = '".$exposure_id."' AND ref_currency_id = ".$ref_fx_id.";");
  $yvalue = sql_get("SELECT hist_return FROM exposure_item_values WHERE exposure_item_id = '".$exposure_id."' AND ref_currency_id = ".$ref_fx_id.";");
  //$xvalue = sql_get_value("exposure_items", "exposure_item_id", $exposure_id, "hist_volatility");
  //$yvalue = sql_get_value("exposure_items", "exposure_item_id", $exposure_id, "hist_return");
  if ($xvalue_text <> '') { $xvalue_text .= ','; }
  if ($yvalue_text <> '') { $yvalue_text .= ','; }
  $xvalue_text .= round($xvalue);
  $yvalue_text .= round($yvalue);
}

 sql_close($link);
 if ($debug) {echo "disconnected from database<br>"; }

 //$label_text = "CHF,EUR,USD";
 //$xvalue_text = "50,30,20";
 //$yvalue_text = "2,3,5";

 $labels = explode(",", $chart_labels);
 $xvalues = explode(",", $xvalue_text);
 $yvalues = explode(",", $yvalue_text);

 if ($debug) { 
   echo "draw chart<br>";
 } else {
 $myPicture = tream_chart_xy ($title, $xaxis, $yaxis, $labels, $xvalues, $yvalues);
 header("Content-Type: image/png");
 $myPicture->Render(null); 
 }
?>
