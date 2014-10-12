<?php
  $_this = intval($_GET[g]);
  if($_this<4 || $_this>18) $_this = 4;
  $next = ($_this+1)>18 ? 4 : ($_this+1) ;
  $prev = ($_this-1)<4 ? 18 : ($_this-1);
  
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Escape to Space: Charts</title>
    <link type="text/css" rel="StyleSheet" href="http://www.escape-to-space.de/css/main.css" />
</head>
<body style="text-align:center">
<img src="http://www.escape-to-space.de/stats/graph1.php?g=<?php echo $_this; ?>" />
<div style="text-align:center">
<table style="display:inline" align="center" width="200">
<tr>
  <td><a href="graphbig.php?g=<?=$prev;?>">&lt;&lt; Vorheriges</a></td>
  <td align="right"><a href="graphbig.php?g=<?=$next;?>">N&auml;chstes &gt;&gt;</a></td>
</tr>
</table>
</div>
</body>
</html>
