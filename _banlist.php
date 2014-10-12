<?php
	$banlist = array(	"62.67.235.169", // www.felixbruns.de
						"212.112.227.114"); // www.unmatrix.org & www.etstec.de

	for ($i=0;$i<count($banlist);$i++)
		if ($REMOTE_ADDR == $banlist[$i])
			die();
?>