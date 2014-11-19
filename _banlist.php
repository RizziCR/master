<?php
	$banlist = array(	"62.67.235.169", // www.felixbruns.de
						"212.112.227.114"); // www.unmatrix.org & www.etstec.de

	if (in_array($_SERVER["REMOTE_ADDR"],$banlist))
			die();
?>