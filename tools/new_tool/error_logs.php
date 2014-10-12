<?php

session_start();

// Include des DB Zugriffs (config.php) und des HTML Begins (head, start body -> html_head.php);
include("config.php");
include("html_head.php");


$load_error = mysql_query("SELECT * FROM query_error_log ORDER BY `date` DESC");


echo "<table border=1>
			<tr>
				<td>
					ID
				</td><td width=10%>
					Datum
				</td><td>
					Ort
				</td><td>
					Error
				</td><td>
					Query
				</td>
			</tr>";

while($use_error = mysql_fetch_array($load_error)) {
	
	echo "<tr>
				<td>
					$use_error[id]
				</td><td>
					". date("H:i:s d.m.Y", $use_error['date']) . "
				</td><td>
					$use_error[ort]
				</td><td>
					$use_error[error]
				</td><td>
					$use_error[query]
				</td>
		 </tr>";
	
}

echo "</table>";



include("html_end.php");

?>