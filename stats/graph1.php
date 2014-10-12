<?php

require_once('graph_common.php');

setlocale(LC_ALL, 'de_DE');

define("TTF_FONTFILE","DejaVuSans.ttf");

require_once ("jpgraph/jpgraph.php");
require_once ("jpgraph/jpgraph_utils.inc.php");
require_once ("jpgraph/jpgraph_line.php");
require_once ("jpgraph/jpgraph_stock.php");
require_once ("jpgraph/jpgraph_mgraph.php");

//------------------------------------------------------------------
// Create some random data for the plot. We use the current time for the
// first X-position
//------------------------------------------------------------------
#/root/rra/plane_$i.rrd
$xmltext = '';
$i = intval($_GET['g']);
if($i<4 || $i>18) $i=4;
$f = $i - 3;
$rrd = popen("rrdtool xport --start now-1week --end now \
DEF:sell=/root/rra/plane_$f.rrd:factor_sell:AVERAGE \
DEF:buy=/root/rra/plane_$f.rrd:factor_buy:AVERAGE \
DEF:stock=/root/rra/plane_$f.rrd:stock:AVERAGE \
XPORT:sell \
XPORT:buy \
XPORT:stock",'r');
while($line = fread($rrd,4096)) {
    $xmltext .= $line;
}
pclose($rrd);

$datay = array();
$datay2 = array();
$datay3 = array();
$datax = array();

$n = 0;
$xml = new SimpleXMLElement($xmltext);
foreach($xml->data->row as $row) {
    $datax[] = intval($row->t);
    $datay[] = floatval($row->v[0]);
    $datay2[] = floatval($row->v[1]);
    $datay3[] = floatval($row->v[2]);
    ++$n;
}
array_pop($datax);
array_pop($datay);
array_pop($datay2);
array_pop($datay3);

// Now get labels at the start of each month
$d_utils = new DateScaleUtils();
list($tickPositions,$minTickPositions) =
    $d_utils->getTicks($datax,DSUTILS_DAY1);

// Now create the real graph
// Combine a line and a bar graph

// We add some grace to the end of the X-axis scale so that the first and last
// data point isn't exactly at the very end or beginning of the scale
$grace = 4000;
$xmin = $datax[0]-$grace;
$xmax = $datax[$n-2]+$grace;

// Left and right margin for each graph
$lm=55; $rm=55;

//----------------------
// Setup the line graph
//----------------------
$max = max(max($datay), max($datay2));
$graph = new Graph(600,200);
$graph->SetScale('linlin',0,$max*1.2,$xmin,$xmax);
$graph->SetY2Scale('lin',0,$max*1.2,$xmin,$xmax);
$graph->SetMargin($lm,$rm,10,30);
$graph->SetFrame(false);
#$graph->SetBox(true);
$graph->title->Set('Kostenfaktoren des '.$names[$i]);
$graph->xaxis->SetTickPositions($tickPositions,$minTickPositions);
$graph->xaxis->SetLabelFormatString('d.M',true);
$graph->xgrid->Show();

$graph->yaxis->title->Set('Verkaufsfaktor (%)');
$graph->yaxis->title->SetMargin(5); // Some extra margin to clear labels
$graph->yaxis->title->SetColor('red');
$graph->yaxis->SetColor('red');

$graph->y2axis->title->Set('Kauffaktor (%)');
$graph->y2axis->title->SetMargin(5); // Some extra margin to clear labels
$graph->y2axis->title->SetColor('teal');
$graph->y2axis->SetColor('teal');

$p1 = new LinePlot($datay,$datax);
$p1->setColor('red');
$graph->Add($p1);

$p2 = new LinePlot($datay2,$datax);
$p2->SetColor('teal');
$graph->AddY2($p2);

//----------------------
// Setup the bar graph
//----------------------
$graph2 = new Graph(600,200);
$graph2->SetScale('linlin',0,max($datay3)*1.2,$xmin,$xmax);
$graph2->SetMargin($lm,$rm,10,30);
$graph2->SetFrame(false);
#$graph2->SetBox(true);
$graph2->title->Set('Lagerbestand des '.$names[$i]);
$graph2->xaxis->SetTickPositions($tickPositions,$minTickPositions);
$graph2->xaxis->SetLabelFormatString('d.M',true);
$graph2->xgrid->Show();
$graph2->yaxis->SetColor('navy');
#$graph2->xaxis->SetTickSide(SIDE_DOWN);
$b1 = new LinePlot($datay3,$datax);
$b1->SetColor('navy');
$graph2->Add($b1);

//-----------------------
// Create a multigraph
//----------------------
$mgraph = new MGraph();
$mgraph->SetImgFormat('png',90);
$mgraph->SetMargin(2,2,2,2);
$mgraph->SetFrame(true,'darkgray',1);
#$mgraph->SetBackgroundImage('tiger1.jpg');
$mgraph->AddMix($graph2,0,0,85);
$mgraph->AddMix($graph,0,210,85);
$mgraph->Stroke();

?>
