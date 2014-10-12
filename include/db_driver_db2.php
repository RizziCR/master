<?php

$last_stmt = null;

function sql_query($sql) {
    global $db, $last_stmt;

    if($stmt = db2_prepare($db, $sql)) {
        if(db2_execute($stmt)) {
            $last_stmt = $stmt;
            return $stmt;
        }
        else {
            $errmsg = db2_stmt_errormsg($stmt);
            $page = $_SERVER["REQUEST_URI"];
            db2_rollback($db);
            db2_exec($db, "INSERT INTO query_error_log (query,ort,error) VALUES ('". addslashes($sql) ."','". addslashes($page) ."','". addslashes($errmsg) ."')");
        }
    }
    return false;
}

function sql_select_db($dbName, $db) {
    // Note: no implementation
}

function sql_connect($database, $dbLogin, $dbPwd) {
    return db2_connect($database, $dbLogin, $dbPwd);
}

function sql_fetch_row($result) {
    return db2_fetch_row($result);
}

function sql_fetch_array($result) {
    // The db2_fetch_array() function returns an array, indexed by column position, representing a row in a result set.
    // The MySQL equivalent of this function, mysql_fetch_array(), returns an array that is numerically indexed by the
    // column position as well as by column name.
    return db2_fetch_array($result);
}

function sql_fetch_assoc($result) {
    return db2_fetch_assoc($result);
}

function sql_num_rows($result) {
    global $last_query;
    return db2_result(sql_query("SELECT COUNT(*) FROM ($last_query) AS C"), 0);
}

function sql_free_result($result) {
    return db2_free_result($result);
}

function sql_insert_id() {
    global $db;
    if($stmt = db2_prepare($db, 'SELECT IDENTITY_VAL_LOCAL() AS VAL FROM SYSIBM.SYSDUMMY1')) {
        if(db2_execute($stmt)) {
            if( $result = db2_fetch_assoc($stmt)) {
                return $result['VAL'];
            }
        }
    }
    return NULL;
}

function sql_affected_rows() {
    global $last_stmt;
    return db2_num_rows($last_stmt);
}
?>