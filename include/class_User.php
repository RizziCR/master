<?php

class User {
    private $_name = '';
    private $_affix = '';

    function __construct($id) {
        $res = sql_query('SELECT user, name_affix FROM userdata WHERE ID="'.addslashes($id).'"');
        list($this->_name, $this->_affix) = sql_fetch_row($res);
        sql_free_result($res);

        if(empty($this->_name))
            $this->_name = $id;
    }

    function getName() {
        return $this->_name;
    }

    function getAffix() {
        return $this->_affix;
    }

    function getScreenName() {
        return (($this->_affix) ? ($this->_name.' '.htmlspecialchars($this->_affix)) : $this->_name);
    }
}
?>
