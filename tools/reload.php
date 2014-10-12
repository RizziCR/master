<?php
include("session.php");
?> 
<html>
<head>
<title>Testseite</title>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="refresh" content="2; URL=http://team.escape-to-space.de/tools/frameset.php"> 
</head>
<body>
<table align="center" width="50%" border="0">
<tr>
<td align="center"><span style="font-size: 0.9em";>Du wirst automatisch zur &Uuml;bersicht weitergeleitet<br><? echo $_SESSION[supporter];?></span><br><br></td>
</tr>
</body>
</html>