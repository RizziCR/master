<?php

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once ('include/class_Party.php');
  require_once("do_loop.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('city_general.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Umfrage');
  
  if($_POST['answer'] && !$_SESSION[sitt_login]) {
  	$select = sql_query("SELECT votes FROM usarios WHERE ID='$_SESSION[user]'");
  	$select = sql_fetch_array($select);
  	if(!$_SESSION['sitt_login']) {
	  	if($select['votes'] == 1) {
		  	$pfuschOutput .=  "Deine Stimme wurde gezählt. Vielen Dank.";
		  	if($_POST['answer'] == 1) 
		  		$update = sql_query("UPDATE voting SET `answer1_count` = `answer1_count`+1 WHERE tag = 'ETS'");
		  	if($_POST['answer'] == 2) 
		  		$update = sql_query("UPDATE voting SET `answer2_count` = `answer2_count`+1 WHERE tag = 'ETS'");
		  	if($_POST['answer'] == 3) 
		  		$update = sql_query("UPDATE voting SET `answer3_count` = `answer3_count`+1 WHERE tag = 'ETS'");
		  	if($_POST['answer'] == 4) 
		  		$update = sql_query("UPDATE voting SET `answer4_count` = `answer4_count`+1 WHERE tag = 'ETS'");
		  	if($_POST['answer'] == 5) 
		  		$update = sql_query("UPDATE voting SET `answer5_count` = `answer5_count`+1 WHERE tag = 'ETS'");
		  	if($_POST['answer'] == 6) 
		  		$update = sql_query("UPDATE voting SET `answer6_count` = `answer6_count`+1 WHERE tag = 'ETS'");
		  	if($_POST['answer'] == 7) 
		  		$update = sql_query("UPDATE voting SET `answer7_count` = `answer7_count`+1 WHERE tag = 'ETS'");
		  	if($_POST['answer'] == 8) 
		  		$update = sql_query("UPDATE voting SET `answer8_count` = `answer8_count`+1 WHERE tag = 'ETS'");
		  	if($_POST['answer'] == 9) 
		  		$update = sql_query("UPDATE voting SET `answer9_count` = `answer9_count`+1 WHERE tag = 'ETS'");
		  	if($_POST['answer'] == 10)
		  		$update = sql_query("UPDATE voting SET `answer10_count` = `answer10_count`+1 WHERE tag = 'ETS'");
		  		
		  	$update = sql_query("UPDATE usarios SET `votes` = '0' WHERE ID='$_SESSION[user]'");
	  	}else{
	  		$pfuschOutput .=  "Du hattest schon abgestimmt.";
	  	}
  	}else{
  		$pfuschOutput .= "Du kannst nur für dich abstimmen, nicht für deinen zu Sittenden.";
  	}
  }else{
  	  $pfuschOutput .= "<center>Ingame-Umfrage</center><br><br>";
	  $select = sql_query("SELECT * FROM voting WHERE tag='ETS'");
	  $select = sql_fetch_array($select);
	  if($select['tag'] == 'ETS') {
		  $pfuschOutput .= "$select[question]<br><br>
		  <form action='votes.php' method='post'>
		  <table border=0>";
		  
		  if($select[answer1] != "") {
		  	$pfuschOutput .=  "<tr>
		  			<td>
		  				$select[answer1]
		  			</td><td>
		  				<input type='radio' name='answer' value='1'>
		  			</td>
		  		</tr>";
		  }
		  if($select[answer2] != "") {
		  	$pfuschOutput .=  "<tr>
		  			<td>
		  				$select[answer2]
		  			</td><td>
		  				<input type='radio' name='answer' value='2'>
		  			</td>
		  		</tr>";
		  }
		  if($select[answer3] != "") {
		  	$pfuschOutput .=  "<tr>
		  			<td>
		  				$select[answer3]
		  			</td><td>
		  				<input type='radio' name='answer' value='3'>
		  			</td>
		  		</tr>";
		  }
		  if($select[answer4] != "") {
		  	$pfuschOutput .=  "<tr>
		  			<td>
		  				$select[answer4]
		  			</td><td>
		  				<input type='radio' name='answer' value='4'>
		  			</td>
		  		</tr>";
		  }
		  if($select[answer5] != "") {
		  	$pfuschOutput .=  "<tr>
		  			<td>
		  				$select[answer5]
		  			</td><td>
		  				<input type='radio' name='answer' value='5'>
		  			</td>
		  		</tr>";
		  }
		  if($select[answer6] != "") {
		  	$pfuschOutput .=  "<tr>
		  			<td>
		  				$select[answer6]
		  			</td><td>
		  				<input type='radio' name='answer' value='6'>
		  			</td>
		  		</tr>";
		  }
		  if($select[answer7] != "") {
		  	$pfuschOutput .=  "<tr>
		  			<td>
		  				$select[answer7]
		  			</td><td>
		  				<input type='radio' name='answer' value='7'>
		  			</td>
		  		</tr>";
		  }
		  if($select[answer8] != "") {
		  	$pfuschOutput .=  "<tr>
		  			<td>
		  				$select[answer8]
		  			</td><td>
		  				<input type='radio' name='answer' value='8'>
		  			</td>
		  		</tr>";
		  }
		  if($select[answer9] != "") {
		  	$pfuschOutput .=  "<tr>
		  			<td>
		  				$select[answer9]
		  			</td><td>
		  				<input type='radio' name='answer' value='9'>
		  			</td>
		  		</tr>";
		  }
		  if($select[answer10] != "") {
		  	$pfuschOutput .=  "<tr>
		  			<td>
		  				$select[answer10]
		  			</td><td>
		  				<input type='radio' name='answer' value='10'>
		  			</td>
		  		</tr>";
		  }
		  $pfuschOutput .=  "<tr>
		  			<td colspan=2>
		  				<input type='submit' value='Stimme abgeben'>
		  			</td>
		  		</tr>		
		  </table>		
		  </form>
		  <br><br><br>
		  Achtung: Jeder hat nur eine Stimme. Der jeweils erst angeklickte Button zählt.";
	  }			
  }
  
  
  
  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);
  
  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  // create html page
  try {
    echo  $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
  



?>