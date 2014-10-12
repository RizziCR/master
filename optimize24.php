#! /usr/bin/php5
<?php

require_once("constants.php");

if (time() > PAUSE_BEGIN && time() < PAUSE_END)
    exit;

  require_once("database.php");

  require_once 'include/MessageCenterController.php';

try {

    sql_query('SET AUTOCOMMIT=0;');
    sql_query('START TRANSACTION');

  $get_user = sql_query("SELECT owner FROM news_igm_umid WHERE dir= ".MessageCenterController::FOLDER_TRASH." GROUP BY owner HAVING Count(*)>100");
  while ($user = sql_fetch_array($get_user))
  {
    $get_time = sql_query("SELECT time FROM news_igm_umid WHERE dir=".MessageCenterController::FOLDER_TRASH." AND owner='$user[owner]' ORDER BY time DESC LIMIT 100,1");
    $time = sql_fetch_array($get_time);
    sql_query("DELETE FROM news_igm_umid WHERE dir=".MessageCenterController::FOLDER_TRASH." AND owner='$user[owner]' AND time<=$time[time]");
   }

  $get_user = sql_query("SELECT owner FROM news_igm_umid WHERE dir=".MessageCenterController::FOLDER_INBOX." GROUP BY owner HAVING Count(*)>100");
  while ($user = sql_fetch_array($get_user))
  {
    $get_time = sql_query("SELECT time FROM news_igm_umid WHERE dir=".MessageCenterController::FOLDER_INBOX." AND owner='$user[owner]' ORDER BY time DESC LIMIT 100,1");
    $time = sql_fetch_array($get_time);
    sql_query("UPDATE news_igm_umid SET dir=".MessageCenterController::FOLDER_TRASH." WHERE owner='$user[owner]' AND dir=".MessageCenterController::FOLDER_INBOX." AND seen='Y' AND time<=$time[time]");
    sql_query("UPDATE news_igm_umid SET dir=".MessageCenterController::FOLDER_TRASH." WHERE owner='$user[owner]' AND dir=".MessageCenterController::FOLDER_INBOX." AND seen='N' AND time<=$time[time] AND time<".time()."-14*24*3600");
  }

  $get_user = sql_query("SELECT owner FROM news_igm_umid WHERE dir=".MessageCenterController::FOLDER_OUTBOX." GROUP BY owner HAVING Count(*)>100");
  while ($user = sql_fetch_array($get_user))
  {
    $get_time = sql_query("SELECT time FROM news_igm_umid WHERE dir=".MessageCenterController::FOLDER_OUTBOX." AND owner='$user[owner]' ORDER BY time DESC LIMIT 100,1");
    $time = sql_fetch_array($get_time);
    sql_query("UPDATE news_igm_umid SET dir=".MessageCenterController::FOLDER_TRASH." WHERE owner='$user[owner]' AND dir=".MessageCenterController::FOLDER_OUTBOX." AND time<=$time[time]");
  }

  // cancel attacks which take longer than a week and archive the action to allow punishment
  // of the attacker
  sql_query("insert long_term_flights select * from actions where (f_arrival - UNIX_TIMESTAMP() > 7*24*60*60) && (f_action='attack')");
  sql_query("delete from actions where (f_arrival - UNIX_TIMESTAMP() > 7*24*60*60) && (f_action='attack')");
} catch(Exception $e) {
    sql_query('ROLLBACK');
}

sql_query('COMMIT');

  $tablesResult = sql_query("SHOW TABLES FROM $dbName", $db);
  while ($row = sql_fetch_row($tablesResult)) {
    sql_query("ANALYZE TABLE ".$row[0]);
    sql_query("OPTIMIZE TABLE ".$row[0]);
  }

?>
