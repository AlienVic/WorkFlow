<?php
 /*
     Example10 : A 3D exploded pie graph
 */

 // Standard inclusions
 require_once('base.php');
 include("pChart/pData.class.php");
 include("pChart/pChart.class.php");
 require_once('GraphBusiness.php');


$fontpath = BaseUrl."/pchart/Fonts/";//字体路径设置

  $graphdat = new GraphBusiness();
 // Dataset definition
 $DataSet = new pData;
 $DataSet->AddPoint($graphdat->GetGraphData(),"Serie1");
 $DataSet->AddPoint(array("January","February","March","April","May"),"Serie2");
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $Test = new pChart(420,250);
 $Test->drawFilledRoundedRectangle(7,7,413,243,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,415,245,5,230,230,230);

 //设置各个序列的颜色
$RGB = array();
$RGB[] = array("R"=>255,"G"=>0,"B"=>0);
$RGB[] = array("R"=>1,"G"=>2,"B"=>3);
$RGB[] = array("R"=>1,"G"=>2,"B"=>3);
$RGB[] = array("R"=>1,"G"=>2,"B"=>3);
$RGB[] = array("R"=>1,"G"=>2,"B"=>3);
 $Test->createColorGradientPalette_SpeColor($RGB,5);
 //--------------------------------------------------------




 //$Test->createColorGradientPalette(5,204,56,203,110,41,5);

 // Draw the pie chart
 $Test->setFontProperties($fontpath."tahoma.ttf",8);
 $Test->AntialiasQuality = 0;
 $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),180,130,110,PIE_PERCENTAGE_LABEL,FALSE,50,20,5);
 $Test->drawPieLegend(330,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

 // Write the title
 $Test->setFontProperties($fontpath."MankSans.ttf",10);
 $Test->drawTitle(10,20,"Sales per month",100,100,100);

 $Test->Render("example10.png");
?>