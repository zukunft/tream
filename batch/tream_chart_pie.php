<?php   

/*  

tream_chart_pie.php - create a pie chart with TREAM settings

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

/* pChart library inclusions */
 include_once("../pChart/class/pData.class.php");
 include_once("../pChart/class/pDraw.class.php");
 include_once("../pChart/class/pPie.class.php");
 include_once("../pChart/class/pImage.class.php");
 
 function tream_chart_pie ($title, $labels, $values) {
 
 $hsize = 500;
 $vsize = 330;
 $lsize = 20;
 $mid_pos = $hsize / 2;
 $vmid_pos = ($vsize+$lsize) / 2;
 $lable_pos = $vsize-30;
 $pie_radius= $vsize * 0.5;
 

 /* Create and populate the pData object */
 $MyData = new pData();   
 $MyData->addPoints($values,"ScoreA");  
 $MyData->setSerieDescription("ScoreA","Application A");

 /* Define the absissa serie */
 $MyData->addPoints($labels,"Labels");
 $MyData->setAbscissa("Labels");

 /* Create the pChart object */
 $myPicture = new pImage($hsize,$vsize,$MyData,TRUE);

 /* Draw a solid background */
 $Settings = array("R"=>165, "G"=>191, "B"=>224, "Dash"=>1, "DashR"=>121, "DashG"=>159, "DashB"=>207);
 $myPicture->drawFilledRectangle(0,0,$hsize,$vsize,$Settings);

 /* Draw a gradient overlay */
 $Settings = array("StartR"=>103, "StartG"=>166, "StartB"=>245, "EndR"=>11, "EndG"=>41, "EndB"=>77, "Alpha"=>50);
 $myPicture->drawGradientArea(0,0,$hsize,$vsize,DIRECTION_VERTICAL,$Settings);
 $myPicture->drawGradientArea(0,0,$hsize,$lsize,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,$hsize-1,$vsize-1,array("R"=>0,"G"=>0,"B"=>0));

 /* Write the picture title */ 
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/Silkscreen.ttf","FontSize"=>8));
 $myPicture->drawText(10,13,$title,array("R"=>255,"G"=>255,"B"=>255));

 /* Set the default font properties */ 
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));

 /* Create the pPie object */ 
 $PieChart = new pPie($myPicture,$MyData);
 //$PieChart->setFixedScale(0,100);   

 /* Define the slice color */
 $PieChart->setSliceColor(0,array("R"=>143,"G"=>197,"B"=>0));
 $PieChart->setSliceColor(1,array("R"=>97,"G"=>77,"B"=>63));
 $PieChart->setSliceColor(2,array("R"=>97,"G"=>113,"B"=>63));

 /* Enable shadow computing */ 
 $myPicture->setShadow(TRUE,array("X"=>3,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 /* Draw a splitted pie chart */ 
 $PieChart->draw3DPie($mid_pos,$vmid_pos,array("Radius"=>$pie_radius,"WriteValues"=>TRUE,"DrawLabels"=>TRUE,"DataGapAngle"=>6,"DataGapRadius"=>6,"Border"=>TRUE));

 /* Write the legend */
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/pf_arma_five.ttf","FontSize"=>10));
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));
 $myPicture->drawText($mid_pos,$lable_pos,$title,array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));

 /* Write the legend box */ 
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/Silkscreen.ttf","FontSize"=>10,"R"=>255,"G"=>255,"B"=>255));
 //$PieChart->drawPieLegend(500,8,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 //$myPicture->autoOutput("pictures/example.draw3DPie.png");
  return $myPicture; 
 }

/*$link = sql_open();


mysql_select_db('cream'); */

 $label_text = $_GET['labels'];
 $value_text = $_GET['values'];
 $title = $_GET['title'];
  
 $labels = explode(",", $label_text);
 $values = explode(",", $value_text);

 //$labels = array("CHF","EUR","USD");
 //$values = array(50,30,20);
 $myPicture = tream_chart_pie ($title, $labels, $values);
 header("Content-Type: image/png");
 $myPicture->Render(null);

/* sql_close($link); */


?>
