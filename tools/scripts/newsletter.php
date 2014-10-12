#! /usr/bin/php5
<?php
include_once("../admsession.php");
  @set_time_limit(100000);

  include_once("database.php");
  include_once("functions.php");

  if(php_sapi_name() != 'cli')
    die('Das Script muss ueber CLI ausgefuehrt werden.');

  if($_SERVER[argc] != 2) {
    die('Usage: newsletter.php "Subject for Mail" < file_for_msg_text'.chr(10));
  }

  while(!feof(STDIN))
    $mailtext .= trim(fgets(STDIN));

  $betreff = $_SERVER[argv][1];

  $users = sql_query("SELECT user, email, name FROM userdata");
  while ($mails = sql_fetch_array($users))
  {
    $tmptext = $mailtext;
    $tmptext = str_replace('###NAME###', $mails[name], $tmptext);
    $tmptext = str_replace('###EMAIL###', $mails[email], $tmptext);
    $tmptext = str_replace('###NICK###', $mails[user], $tmptext);
    $tmptext = str_replace('###etsAddress###', $etsAddress, $tmptext);
    $tmptext = str_replace('###etsName###', $etsName, $tmptext);
print_r($tmptext);
break;
#    smtp_mail($mails[email], $betreff, $tmptext);
  }

  echo "\nFertig\n\n";

?>
