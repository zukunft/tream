<?php   
 /* CAT:Pie charts */
/*
include '../batch/tream_db_adapter.php';
include '../batch/tream_db.php';
include '../batch/tream_mm_link.php';
include '../batch/tream_jb_link.php';
*/
/* pChart library inclusions */
 include("../pChart/class/pData.class.php");
 include("../pChart/class/pDraw.class.php");
 include("../pChart/class/pScatter.class.php");
 include("../pChart/class/pImage.class.php");
 
 function tream_chart_xy ($title, $xaxis, $yaxis, $labels, $xvalues, $yvalues) {
 
 $hsize = 600;
 $vsize = 500;
 $lsize = 20;
 $chartarea_left_spacer = 50;
 $chartarea_right_spacer = 50;
 $chartarea_top_spacer = 60;
 $chartarea_bottom_spacer = 40;
 $legend_bottom_spacer = 20;
 $legend_right_spacer = 120;

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
$myData->setScatterSerieDescription(0,"This year");
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
$myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText($lsize/2,13,$title,array("R"=>255,"G"=>255,"B"=>255));

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,$hsize-1,$vsize-1,array("R"=>0,"G"=>0,"B"=>0));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Set the graph area */
$myPicture->setGraphArea($chartarea_left_spacer,$chartarea_top_spacer,$hsize-$chartarea_right_spacer,$vsize-$chartarea_bottom_spacer);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture,$myData);

$myScatter->drawScatterScale();

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

$myScatter->drawScatterPlotChart();

$myScatter->drawScatterLegend($hsize-$legend_right_spacer,$vsize-$legend_bottom_spacer,array("Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER));

$myScatter->drawScatterBestFit();

$myPicture->autoOutput("pictures/example.drawScatterBestFit.png");

  return $myPicture; 
 }


 $title = $_GET['title'];
 $xaxis = $_GET['xaxis'];
 $yaxis = $_GET['yaxis'];
 $label_text = $_GET['labels'];
 $xvalue_text = $_GET['xvalues'];
 $yvalue_text = $_GET['yvalues'];
  
 $label_text = "CHF,EUR,USD";
 $xvalue_text = "50,30,20";
 $yvalue_text = "2,3,5";

 $labels = explode(",", $label_text);
 $xvalues = explode(",", $xvalue_text);
 $yvalues = explode(",", $yvalue_text);

 //$labels = array("CHF","EUR","USD");
 //$values = array(50,30,20);
 //$axis_x_name = 'return';
 //$axis_y_name = 'risk';
 $myPicture = tream_chart_xy ($title, $xaxis, $yaxis, $labels, $xvalues, $yvalues);
 header("Content-Type: image/png");
 $myPicture->Render(null);


?>
