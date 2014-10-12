<?php
include_once("../admsession.php");
include_once("../htmlheader.php");
	echo 	"<h2>Voting-XML -> Datei l&ouml;schen</h2>";
if($_POST[apply] !== "Ja"){
	echo "Votingdatei wirklich l&ouml;schen ?
	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
	<input type=\"submit\" name=\"apply\" value=\"Ja\">
	</form>";
} ELSE {
/*
 * removes the vote xml from file system
 *
 * - this script delets the page/vote.xml
 * - vote xml is changed by system,
 *   if you changed the this file in repository,
 *   then its better to delete file before changes
 *   take place and prevent conflicts on automatic
 *   svn update
 */

// before you can delete (unlink) a file, you must first
// be sure that it is not open in your program. Use the
// fclose function to close down an open file.

	$myFile = "../../page/vote.xml";
	$fh = fopen($myFile, 'w') or die("can't open file");
	fclose($fh);

// Now to delete testFile.txt we simply run a PHP script
// that is located in the same directory. Unlink just needs
// to know the name of the file to start working its destructive magic.
// If unlink was succssful returns true.
	if (unlink($myFile)){
		echo "Datei ".$myFile . "  wurde erfolgreich gel&ouml;scht";
		// Eintrag in Supporter-Log
		require_once("database.php");
		sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
					VALUES ('$_SESSION[supporter]', 'System', '<b>Vote-XML gel&ouml;scht</b>', '".time()."')");
		// Ende Eintrag
	}
	else
	{
    echo "can't delete " . $myFile;
	}
}
?>
</body>
</html>