#! /usr/bin/php5
<?php

require_once("constants.php");

if (time() > PAUSE_BEGIN && time() < PAUSE_END)
    exit;

require_once("bench.php");

$bench = new Bench;
$bench->Start();

@set_time_limit(590);

require_once("database.php");
require_once("functions.php");
require_once 'include/class_Krieg.php';

$unix_timestamp = time();

// Kriege beenden 2
try {
    sql_begin_transaction();
    $war_res = sql_query("SELECT id FROM wars WHERE approved='Y' AND open='Y' AND winner IS NULL");
    while($k = sql_fetch_assoc($war_res)) {
        list( $_alliance ) = sql_fetch_row( sql_query('SELECT tag FROM war_party WHERE war_id='.$k[id].' AND side="A" LIMIT 1') );
        $krieg = new Krieg($_alliance);
        $krieg->load($k[id]);
        $krieg->handleLostColonies();
    }
} catch(Exception $e) {
    sql_roll_back();
}
sql_commit();

$bench->NewMarke("Kriege beenden 2");

$bench->NewMarke("Ende");
//$bench->ShowResults();
?>
