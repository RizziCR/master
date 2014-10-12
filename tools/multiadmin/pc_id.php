<?php 
include_once("../session.php");
include_once("../htmlheader.php");
require_once("database.php");
	

	echo "V 0.53";
	echo "<table>";
    $get_pc_ids_of_user = sql_query("SELECT pc_id, user FROM multi_sessions GROUP BY pc_id");
    while ($pc_ids_of_user = sql_fetch_array($get_pc_ids_of_user))
    {
    $x=0;
    $output = "";
    $output2 = "";
   	#http://escape-to-space.de/tools/multiadmin/index.php?ip=true&get=pc&pc_id=271&suspect_user=lilasa
   	$output = "<tr valign=top>
				<td>
					<a href=\"index.php?ip=true&get=pc&pc_id=$pc_ids_of_user[pc_id]&suspect_user=$pc_ids_of_user[user]\">$pc_ids_of_user[pc_id]</a>
				</td>
				<td>";

        $get_user_of_pc_ids = sql_query("SELECT user,Count(*) AS anzahl FROM multi_sessions WHERE pc_id='$pc_ids_of_user[pc_id]' GROUP BY user");
        while ($user_of_pc_ids = sql_fetch_array($get_user_of_pc_ids))
        {
        $output2 .= "<table border=0 cellpadding=1 cellspacing=0>
        			  <tr valign=top>
						<td width=200>
							<a href=\"index.php?suspect_user=$user_of_pc_ids[user]\">$user_of_pc_ids[user]</a> (<a href=\"index.php?ip=true&get=user&suspect_user=$user_of_pc_ids[user]\">$user_of_pc_ids[anzahl]</a>)
						</td>
						<td width=600>";

            $get_multi_formular = sql_query("SELECT doppel_ip_user,reason FROM multi_angemeldete_doppel_ip WHERE user='$user_of_pc_ids[user]'");
            while ($multi_formular = sql_fetch_array($get_multi_formular))
            {
                $output2 .= "$multi_formular[doppel_ip_user] => $multi_formular[reason]<br>";
            }

            $output2 .= "</td>
					</tr>
				</table>";
            $x++;
        }

		if($x > 1) {
			echo "$output $output2";
			echo "</td>
					</tr>";
			$x = 0;
		}
    }
    echo "</table>";



?>