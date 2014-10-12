<?php

function sql_query($sql) {
    $result = mysql_query($sql);
    if (mysql_error()) {
//    	throw new Exception();
        $errmsg = mysql_error();
        $page = $_SERVER["REQUEST_URI"];
        //      mysql_query("ROLLBACK");
        $time = time();
        mysql_query("INSERT INTO query_error_log (query,ort,error,date) VALUES ('". addslashes($sql) ."','". addslashes($page) ."','". addslashes($errmsg) ."', '$time')");
    }
    return $result;
}

function sql_select_db($dbName, $db) {
    return mysql_select_db($dbName,$db);
}

function sql_connect($dbServer, $dbLogin, $dbPwd) {
    return mysql_connect($dbServer, $dbLogin, $dbPwd);
}

function sql_fetch_row($result) {
    return mysql_fetch_row($result);
}

function sql_fetch_array($result) {
    return mysql_fetch_array($result);
}

function sql_fetch_assoc($result) {
    return mysql_fetch_assoc($result);
}

function sql_num_rows($result) {
    return mysql_num_rows($result);
}

function sql_free_result($result) {
    return mysql_free_result($result);
}

function sql_insert_id() {
    return mysql_insert_id();
}

function sql_affected_rows() {
    return mysql_affected_rows();
}

// later: PDO->beginTransaction()
function sql_begin_transaction() {
//    sql_query('SET AUTOCOMMIT=0;');
//    return sql_query('START TRANSACTION');
}

// later: PDO->commit()
function sql_commit() {
//    return sql_query('COMMIT');
}

// later: PDO->rollBack()
function sql_roll_back() {
//    return sql_query('ROLLBACK');
}
?>