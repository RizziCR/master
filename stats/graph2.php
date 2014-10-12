<?php

require_once('graph_common.php');

define("TTF_FONTFILE","DejaVuSans.ttf");

require_once ("jpgraph/jpgraph.php");
require_once ("jpgraph/jpgraph_utils.inc.php");
require_once ("jpgraph/jpgraph_line.php");

//------------------------------------------------------------------
// Create some random data for the plot. We use the current time for the
// first X-position
//------------------------------------------------------------------
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

// Left and right margin for each graph
$lm=55; $rm=105;

//----------------------
// Setup the line graph
//----------------------
$max = max(max($datay), max($datay2));

$graph = new Graph(600,200);
$graph->SetScale('intlin');
$graph->SetMargin($lm,$rm,30,30);
$graph->SetFrame(false);
$graph->title->Set('Lagerbestand und Kostenfaktoren des '.$names[$i]);

$graph->xaxis->SetTickPositions($tickPositions,$minTickPositions);
$graph->xaxis->SetLabelFormatString('d.M',true);
$graph->xgrid->Show();

$graph->SetYScale(0,'lin', 0, $max*1.2);
$graph->SetYScale(1,'lin', 0, $max*1.2);

$p1 = new LinePlot($datay3,$datax);
$p1->SetColor('navy');
$graph->Add($p1);
$graph->yaxis->SetColor('navy');
$graph->yaxis->title->Set('Lagerbestand');
$graph->yaxis->title->SetMargin(5); // Some extra margin to clear labels
$graph->yaxis->title->SetColor('navy');

$p2 = new LinePlot($datay2,$datax);
$p2->SetColor('teal');
$graph->AddY(0,$p2);
$graph->ynaxis[0]->SetColor('teal');
$graph->ynaxis[0]->title->Set('Kauffaktor (%)');
$graph->ynaxis[0]->title->SetMargin(2); // Some extra margin to clear labels
$graph->ynaxis[0]->title->SetColor('teal');

$p3 = new LinePlot($datay,$datax);
$p3->SetColor('red');
$graph->AddY(1,$p3);
$graph->ynaxis[1]->SetColor('red');
$graph->ynaxis[1]->title->Set('Verkaufsfaktor (%)');
$graph->ynaxis[1]->title->SetMargin(2); // Some extra margin to clear labels
$graph->ynaxis[1]->title->SetColor('red');


$graph->Stroke();

?>
