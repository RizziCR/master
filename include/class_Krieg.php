<?php

require_once 'MessageCenterController.php';

class Krieg {

    const NO_LEAVE = 1;
    const NO_VACATION = 2;
    const NO_APPLICATION = 3;
    const NO_FLY = 4;

    const TYPE_OPEN = 1;
    const TYPE_WON = 2;
    const TYPE_LOST = 3;
    const TYPE_NEGO = 4;
    const TYPE_DENIED = 5;
    const TYPE_CANCELLED = 6;
    const TYPE_EMPTY = 7;
    const TYPE_ALL = 8;

    const STATE_OPEN = 1;
    const STATE_CLOSED = 2;

    // the alliance starting the war
    private $_thisAllianceTag = NULL;

    // the war as array
    private $_war = NULL;

    // validation errors
    private $_validation_errors = array();

    // state of this war
    private $_state = NULL;

    // message array
    private $_msg = array(
        'deny' => array('Kriegsangebot abgelehnt', 'Die Allianz %s hat das aktuelle Angebot zu Kriegsverhandlungen abgelehnt und damit die Verhandlung abgebrochen.'),
        'cancel' => array('Kriegsangebot zurückgezogen', 'Die Allianz %s hat ihr Kriegsangebot zurückgezogen und damit die Verhandlungen zu einem Krieg abgebrochen.'),
        'surrender' => array('Kriegsende durch Kapitulation', 'Die Allianz %s hat euren Krieg durch Kapitulation beendet.'),
        'application' => array('Kriegsende durch Regelverstoß', 'Die Allianz %s hat gegen die Kriegsvereinbarung verstossen. Damit ist der Krieg beendet.'),
        'vacation' => array('Kriegsende durch Regelverstoß', 'Die Allianz %s hat gegen die Kriegsvereinbarung verstossen. Damit ist der Krieg beendet.'),
        'leave' => array('Kriegsende durch Regelverstoß', 'Die Allianz %s hat gegen die Kriegsvereinbarung verstossen. Damit ist der Krieg beendet.'),
        'ceasefire' => array('Kriegsende durch Regelverstoß', 'Die Allianz %s hat gegen die Kriegsvereinbarung verstossen. Damit ist der Krieg beendet.'),
        'deletion' => array('Kriegsende durch Allianzlöschung', 'Die Allianz %s hat gegen die Kriegsvereinbarung verstossen. Damit ist der Krieg beendet.'),
        'rename' => array('Kriegsende durch Allianzumbenennung', 'Die Allianz %s hat gegen die Kriegsvereinbarung verstossen. Damit ist der Krieg beendet.'),
        'modify' => array('Änderung der Kriegsverhandlung', 'Die Allianz %s hat den ausgehandelten Kriegsvertrag geändert. Deine Allianz muß die Änderungen noch akzeptieren.'),
        'begin' => array('Kriegsbeginn', 'Der ausgehandelte Krieg hat begonnen!'),
        'add_ally' => array('Einladung zum Krieg', 'Die Allianz %s lädt deine Allianz zu einem Krieg auf ihrer Seite ein.'),
        'add_opp' => array('Aufforderung zum Krieg', 'Die Allianz(en) %s fordert deine Allianz zu einem Krieg heraus.'),
        'accept' => array('Kriegsaufforderung akzeptiert', 'Die Allianz %s hat die aktuellen Kriegsbedingungen akzeptiert.'),
        'finish' => array('Der Krieg ist beendet', 'Der aktuelle Krieg ist beendet.'),
        'finish_colo' => array('Der Krieg ist beendet', 'Der aktuelle Krieg ist beendet. Es wurden mehr Kolonien verloren, als die Kriegsbdingungen zulassen.'),
        'finish_memb' => array('Der Krieg ist beendet', 'Der aktuelle Krieg ist beendet. Es wurden mehr Mitglieder verloren, als die Kriegsbdingungen zulassen.'),
    	'remis' => array('Unentschieden', 'Die Kriegspartein haben sich auf einen Remis verständigt.')
    );

    /**
     * Constructs a new instance of Krieg.
     *
     * @param string an alliance tag
     */
    public function __construct($thisAllianceTag) {
        $this->_thisAllianceTag = $thisAllianceTag;
    }

    /**
     * Start a new war using the given configuration.
     *
     * @param array the war configuration
     */
    public function start(array $config) {
        if($this->inWar()) return false;

        $callback = array($this, '_alliance_filter');
        $config[war][opponents] = !empty($config[war][opponents]) ? array_map($callback, explode(',', $config[war][opponents])) : array();
        $config[war][allies]    = !empty($config[war][allies])    ? array_map($callback, explode(',', $config[war][allies]))    : array();
        $config[war][opponents] = array_diff($config[war][opponents], array($this->_thisAllianceTag));
        $config[war][allies]    = array_diff($config[war][allies], array($this->_thisAllianceTag));

        if($this->_checkConfig($config)) {
            $config = $this->_modifyConfig($config);
            if(sql_query("INSERT INTO wars (id,config,config_version) VALUES (null, '".serialize($config)."',1)")) {
                $war_id = sql_insert_id();

                $this->_addParty($war_id, $this->_thisAllianceTag, 'A', 'Y');
                if(is_array($config[war][allies]))
                    foreach($config[war][allies] as $ally) {
                        $this->_addParty($war_id, $ally, 'A', 'N');
                        $this->_sendAllyAdminMail($ally, $this->_msg['add_ally'][0], sprintf($this->_msg['add_ally'][1], $this->_thisAllianceTag) );
                    }
                foreach($config[war][opponents] as $opponent) {
                    $this->_addParty($war_id, $opponent, 'B', 'N');
                    $this->_sendAllyAdminMail($opponent, $this->_msg['add_opp'][0], sprintf($this->_msg['add_opp'][1],
                        implode(', ',array_merge(array($this->_thisAllianceTag),$config[war][allies])))
                    );
                }
                sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$war_id."', causer='".$this->_thisAllianceTag."', occasion='declare'");
                return true;
            }
        }
        return false;
    }

    /**
     * Modify a war offer according to the new configuration.
     *
     * @param array the war configuration
     */
    public function modify(array $config) {
        if($this->inWar()) return false;

        $callback = array($this, '_alliance_filter');
        $config[war][opponents] = !empty($config[war][opponents]) ? array_map($callback, explode(',', $config[war][opponents])) : array();
        $config[war][allies]    = !empty($config[war][allies])    ? array_map($callback, explode(',', $config[war][allies]))    : array();
        $config[war][opponents] = array_diff($config[war][opponents], array($this->_thisAllianceTag));
        $config[war][allies]    = array_diff($config[war][allies], array($this->_thisAllianceTag));

        if($this->_checkConfig($config)) {
            $config = $this->_modifyConfig($config);

            $removed = array_diff($this->getAllies(),$config[war][allies]);
            if(!empty($removed)) {
                $this->_validation_errors[] = 'Es ist nicht möglich, Kriegspartner auszuladen';
                return false;
            }
            $removed = array_diff($this->getOpponents(),$config[war][opponents]);
            if(!empty($removed)) {
                $this->_validation_errors[] = 'Es ist nicht möglich, Kriegsgegner auszuladen';
                return false;
            }

            sql_query("UPDATE war_party SET accepted='N' WHERE war_id='".$this->_war[id]."'");
            if(sql_query("UPDATE wars SET config='".serialize($config)."', config_version=config_version+1 WHERE id=".$this->_war[id])) {
                sql_query("UPDATE war_party SET accepted='Y', accepted_version=(SELECT config_version FROM wars WHERE id='".$this->_war[id]."') ".
                          "WHERE war_id='".$this->_war[id]."' AND tag='".$this->_thisAllianceTag."'");
                if(is_array($config[war][allies]))
                    foreach($config[war][allies] as $ally) {
                        $this->_sendAllyAdminMail($ally, $this->_msg['modify'][0], sprintf($this->_msg['modify'][1], $this->_thisAllianceTag) );
                    }
                foreach($config[war][opponents] as $opponent) {
                    $this->_sendAllyAdminMail($opponent, $this->_msg['modify'][0], sprintf($this->_msg['modify'][1], $this->_thisAllianceTag) );
                }

                $added = array_diff($config[war][allies],$this->getAllies());
                foreach($added as $a) {
                    $this->_addParty($this->_war[id], $a, $this->_war[side], 'N');
                    $this->_sendAllyAdminMail($a, $this->_msg['add_ally'][0], sprintf($this->_msg['add_ally'][1], $this->_thisAllianceTag) );
                }
                $added = array_diff($config[war][opponents],$this->getOpponents());
                foreach($added as $a) {
                    $this->_addParty($this->_war[id], $a, ($this->_war[side]=='A' ? 'B' : 'A'), 'N');
                    $this->_sendAllyAdminMail($a, $this->_msg['add_opp'][0], sprintf($this->_msg['add_opp'][1], $this->_thisAllianceTag) );
                }

                return true;
            }
        }
    }

    /**
     * Accept the loaded war according to the previously negotiated configuration.
     */
    public function accept() {
        if($this->inWar()) return false;

        sql_query("UPDATE war_party SET accepted='Y' WHERE war_id='".$this->_war[id]."' AND tag='".$this->_thisAllianceTag."'");
        sql_query("UPDATE war_party SET accepted_version=(SELECT config_version FROM wars WHERE id='".$this->_war[id]."') ".
            "WHERE war_id='".$this->_war[id]."' AND tag='".$this->_thisAllianceTag."'");
        if(is_array($tags = $this->_getParties())) {
            $tags = array_diff($tags, array($this->_thisAllianceTag) );
            foreach($tags as $tag) {
                $this->_sendAllyAdminMail($tag, $this->_msg['accept'][0], sprintf($this->_msg['accept'][1], $this->_thisAllianceTag) );
            }
        }
        $count = sql_query("SELECT 1 FROM war_party WHERE accepted='N' AND war_id='".$this->_war[id]."'");
        if(sql_num_rows($count) == 0) {
            $begin = ($this->_war[config][war][begin][mode] == 'date' ? $this->_getDate($this->_war[config][war][begin][date]) : time() );
            $end = ($this->_war[config][war][end][opt_time] == 1 ?
                ($this->_war[config][war][end][timemode] == 'date' ?
                    $this->_getDate($this->_war[config][war][end][date]) :
                    $begin + intval($this->_war[config][war][end][days])*24*3600
                ) :
                '2147483647' );
            sql_query("UPDATE wars SET approved='Y', open='N', begin='".$begin."', end='".$end."' WHERE id='".$this->_war[id]."'");
            sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='accept'");
            // cancel other negotiations
            $other_negs = $this->getWars(self::TYPE_NEGO);
            $tmp_war = new Krieg($this->_thisAllianceTag);
            foreach($other_negs as $on) {
                $tmp_war->load($on[id]);
                $tmp_war->deny();
            }
            unset($tmp_war);

            // save start states if necessary
            if($this->_war[config][war][begin][mode] == 'accept') {
                $this->beginWar();
                sql_query("INSERT INTO chronicle SET time='".time()."'+1, war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='start'");
            }
        }
        return true;
    }

    /**
     * Deny the loaded war.
     */
    public function deny() {
        if($this->_state == self::STATE_OPEN) {
            sql_query("UPDATE wars SET denied='Y' WHERE id='".$this->_war[id]."'");
            sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='decline'");
            $this->endWar($this->_thisAllianceTag, '', 'deny', 'N');
        }
        return true;
    }

    /**
     * Cancel the loaded war.
     */
    public function cancel() {
        if($this->_state == self::STATE_OPEN) {
            sql_query("UPDATE wars SET cancelled='Y' WHERE id='".$this->_war[id]."'");
            sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='withdraw'");
            $this->endWar($this->_thisAllianceTag, '', 'cancel', 'N');
        }
        return true;
    }

    /**
     * Finish the loaded active war and count it as lost for the party of current alliance.
     */
    public function surrender() {
        if(!$this->_war[finishable]) {
            $this->_validation_errors[] = 'Der Krieg kann noch nicht beendet werden.';
            return false;
        }
        if($this->_state == self::STATE_OPEN) {
            // Override winner because points do not count on surrender
            $winner = ( $this->_war[side] == 'A' ? 'B' : 'A' );
            sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='end', victory='surrender'");
            $this->endWar($this->_thisAllianceTag, '', 'surrender', $winner);
        }
        return true;
    }

    /**
     * Ends the loaded war, sends a message to all party-admins/-founders and saves the state
     * of all alliances to the database.
     *
     * @param string	alliance who caused the ending
     * @param string	$culprit (not used)
     * @param string	cause of war ending (see Krieg::_msg)
     * @param string	winner 'A' xor 'B'
     */
    public function endWar($alliance, $culprit, $cause, $winner) {
        if($this->_state == self::STATE_CLOSED) return;
        if(is_null($winner)) {
            $statsC = $this->_diffColonies();
	    // B conquered more colonies
            if($statsC['B'] > $statsC['A']) {
                $winner = 'B';
            }
	    // A conquered more colonies
            else if($statsC['B'] < $statsC['A']) {
                $winner = 'A';
            }
            else { // no colony difference
                $statsM = $this->_diffMembers();
		// B lost more members
                if($statsM['B'] > $statsM['A']) {
                    $winner = 'A';
                }
		// A lost more members
                else if($statsM['B'] < $statsM['A']) {
                    $winner = 'B';
                }
                else {
                    $winner = 'B';
                }
            }
        }
        sql_query("UPDATE wars SET winner='".$winner."' WHERE id='".$this->_war[id]."'");
        if(is_array($tags = $this->_getParties())) {
            foreach($tags as $tag) {
                $end_state = addslashes(serialize($this->_saveState($tag)));
                sql_query("UPDATE war_party SET end_state='".$end_state."' WHERE war_id='".$this->_war[id]."' AND tag='".$tag."'");
                $this->_sendAllyAdminMail($tag, $this->_msg[$cause][0], sprintf($this->_msg[$cause][1], $alliance) );
            }
        }
        if($winner != 'N') { // only give fame if there is a winner
            // five 5% of private fame to every member of the winner alliances
            sql_query("UPDATE usarios, war_party, wars ".
        		      "SET usarios.fame_own=ceil(usarios.fame_own+wars.fame_".$winner."*0.05) ".
        		      "WHERE usarios.alliance=war_party.tag AND war_party.war_id=wars.id AND war_id=".$this->_war[id]." AND side='".$winner."'");
            // give private fame to all winner alliances
            sql_query("UPDATE alliances, war_party, wars ".
              "SET alliances.fame_own=alliances.fame_own+wars.fame_".$winner." ".
              "WHERE alliances.tag=war_party.tag AND war_party.war_id=wars.id AND war_id=".$this->_war[id]." AND side='".$winner."'");
            // recompute public fame for alliances and members
            foreach (($winner == 'A' ? $this->getAttackers() : $this->getDefenders()) as $winningAlliance)
            {
              // do not do these computations before all members and alliances have been dealt their own share, as has been done before the loop
              recompute_alliance_fame($winningAlliance);
              recompute_user_fame_for_alliance($winningAlliance);
            }
        }
    }

    /**
     * Starts the loaded war, sends a message to all party-admins/-founders and saves the state
     * of all alliances to database.
     *
     */
    public function beginWar() {
        list($fameA, $fameB) = $this->_calcFame();
        if(is_array($tags = $this->_getParties())) {
            foreach($tags as $tag) {
                $begin_state = addslashes(serialize($this->_saveState($tag)));
                sql_query("UPDATE war_party SET begin_state='".$begin_state."' WHERE war_id='".$this->_war[id]."' AND tag='".$tag."'");
                $this->_sendAllyAdminMail($tag, $this->_msg['begin'][0], sprintf($this->_msg['begin'][1], null) );
            }
        }
        sql_query("UPDATE wars SET open='Y', fame_A=".$fameA.", fame_B=".$fameB." WHERE id='".$this->_war[id]."'");
    }

    /**
     * Handle the accepted application of $username. This can either be done by doing
     * nothing or finishing the war counting as lost.
     * If no war has been loaded with load() before calling this function it will load
     * the currently open war. If the alliance is not in a war, false is returned.
     *
     * @param string the user who has been accepted
     * @return unknown
     */
    
    public function remis() {
        if($this->_war[remis]) {
            $this->_validation_errors[] = 'Remis wurde schon angeboten.';
            return false;
        }
    	$_wars = sql_query("SELECT DISTINCT id, config FROM wars WHERE id='".$this->_war[id]."'");
        while($_war = sql_fetch_assoc($_wars)) {
        	$select = sql_query("SELECT tag FROM war_party INNER JOIN usarios ON war_party.tag=usarios.alliance WHERE war_id='".$this->_war[id]."' AND NOT user='$_SESSION[user]';");
        	$select = sql_fetch_array($select);
        	$config = unserialize($_war[config]);
        	$config[war][remis] = "1";
        	$config[war][remis2] = $select[tag];
    	$_config = serialize($config);
        sql_query("UPDATE wars SET config='".addslashes($_config)."' WHERE id=".$_war[id]);
    	}
    	return true;
    }
    
    public function remis_accept() {
    # TODO: Nur die eigenen erkämpften RuPu ausschütten
    	
        sql_query("UPDATE wars SET winner='remis' WHERE id='".$this->_war[id]."'");
        if(is_array($tags = $this->_getParties())) {
            foreach($tags as $tag) {
                $end_state = addslashes(serialize($this->_saveState($tag)));
                sql_query("UPDATE war_party SET end_state='".$end_state."' WHERE war_id='".$this->_war[id]."' AND tag='".$tag."'");
                $this->_sendAllyAdminMail($tag, $this->_msg['remis'][0], $this->_msg['remis'][1] );
            }
        }
        return true;
    }
    
    
    
    
    
    
    public function handleApplication($username) {
        if($this->checkWarOptions(self::NO_APPLICATION)) {
            // Override winner because points do not count on breach of the rules
            $winner = ( $this->_war[side] == 'A' ? 'B' : 'A' );
            sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='end', victory='join'");
            $this->endWar($this->_thisAllianceTag, $username, 'application', $winner);
            return true;
        }
        return false;
    }

    /**
     * Handle the change of the user $username into vacation. This can either be done by doing
     * nothing or finishing the war counting as lost.
     * If no war has been loaded with load() before calling this function it will load
     * the currently open war. If the alliance is not in a war, false is returned.
     *
     * @param string the user who changed into vacation
     */
    public function handleVacation($username) {
        if($this->checkWarOptions(self::NO_VACATION)) {
            // Override winner because points do not count on breach of the rules
            $winner = ( $this->_war[side] == 'A' ? 'B' : 'A' );
            sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='end', victory='vacation'");
            $this->endWar($this->_thisAllianceTag, $username, 'vacation', $winner);
            return true;
        }
        else {
            return $this->_handleMemberLost();
        }
    }

    /**
     * Handle the leaving of $username. This can either be done by doing
     * nothing or finishing the war counting as lost.
     * If no war has been loaded with load() before calling this function it will load
     * the currently open war. If the alliance is not in a war, false is returned.
     *
     * @param unknown_type $username
     */
    public function handleLeaving($username) {
        if($this->checkWarOptions(self::NO_LEAVE)) {
            // Override winner because points do not count on breach of the rules
            $winner = ( $this->_war[side] == 'A' ? 'B' : 'A' );
            sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='end', victory='leave'");
            $this->endWar($this->_thisAllianceTag, $username, 'leave', $winner);
            return true;
        }
        else {
            return $this->_handleMemberLost();
        }
    }

    /**
     * Handle the leaving of $username. This can either be done by doing
     * nothing or finishing the war counting as lost.
     * If the alliance is not in a war, false is returned.
     */
    public function _handleMemberLost() {
      //XXX what about user self erasing? should count as loss, too
        if(!$this->inWar()) return false;
        if(!is_array($this->_war)) {
            $this->load();
        }
        if($this->_war[config][war][end][members] > 0) {
            $stats = $this->_diffMembers();
            if($stats[$this->_war[side]] >= $this->_war[config][war][end][members]) {
                $winner = ( $this->_war[side] == 'A' ? 'B' : 'A' );
                sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='end', victory='loss'");
                $this->endWar($this->_thisAllianceTag, '', 'finish_memb', $winner);
                return true;
            }
        }
        return false;
    }

    /**
     * Handle the deletion of an alliance that is in this war. This can either be done by doing
     * nothing or finishing the war counting as lost.
     * If no war has been loaded with load() before calling this function it will load
     * the currently open war. If the alliance is not in a war, false is returned.
     */
    public function handleAllianceDeletion() {
        if(!$this->inWar()) return false;
        if(!is_array($this->_war)) {
            $this->load();
        }
        // Override winner because points do not count on breach of the rules
        $winner = ( $this->_war[side] == 'A' ? 'B' : 'A' );
        sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='end', victory='disband'");
        $this->endWar($this->_thisAllianceTag, '', 'deletion', $winner);
        return true;
    }

    /**
     * Handle the rename of an alliance that is in this war.
     * If no war has been loaded with load() before calling this function it will load
     * the currently open war. If the alliance is not in a war, false is returned.
     */
    public function handleAllianceRename($new_tag) {
        sql_query("UPDATE war_party SET tag='".addslashes($new_tag)."' WHERE tag='".$this->_thisAllianceTag."'");
        $_wars = sql_query("SELECT DISTINCT wars.id, wars.config FROM wars, war_party WHERE wars.id=war_party.war_id AND ".
                           "war_party.tag='".addslashes($new_tag)."'");
        while($_war = sql_fetch_assoc($_wars)) {
            $_config = unserialize($_war[config]);
            foreach($_config[war][opponents] as $_key => $_o) {
                if($_o == $this->_thisAllianceTag)
                    $_config[war][opponents][$_key] = $new_tag;
            }
            foreach($_config[war][allies] as $_key => $_a) {
                if($_a == $this->_thisAllianceTag)
                    $_config[war][allies][$_key] = $new_tag;
            }
            $_config = serialize($_config);
            sql_query("UPDATE wars SET config='".addslashes($_config)."' WHERE id=".$_war[id]);
        }
    }

    /**
     * Handle the case that the lost of colonies is an end criteria. This can either be done
     * by doing nothing or finishing the war counting as lost.
     * If the alliance is not in a war, false is returned.
     */
    public function handleLostColonies() {
        if(!$this->inWar()) return false;
        if(!is_array($this->_war)) {
            $this->load();
        }
        if($this->_war[config][war][end][kolos] > 0) {
            $stats = $this->_diffColonies();
            if( abs( $stats['A'] ) >= $this->_war[config][war][end][kolos] ) {
                $winner = ( $stats['A'] < $stats['B'] ? 'B' : 'A' );
                sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='end', victory='colonies'");
                $this->endWar($this->_thisAllianceTag, '', 'finish_colo', $winner);
                return true;
            }
        }
        return false;
    }

    /**
     * Handle the violation of ceasefire. This can either be done by doing nothing or finishing
     * the war counting as lost.
     * If the alliance is not in a war, false is returned.
     */
    public function handleCeasefire($target, $time) {
        if($this->checkWarOptions(self::NO_FLY)) {
            if(!$this->isOpponent($target))
                return false;
            switch($this->_war[config][war][options][nofly][mode]) {
                case 'daily':
		    $time = localtime( $time, true );
		    $time = $time[tm_sec] + $time[tm_min] * 60 + $time[tm_hour] * 3600;
#                    $time = $time % (24 * 3600); // get hours and minutes from timestamp
                    $from  = $this->_getTime($this->_war[config][war][options][nofly][daily_from]);
                    $until = $this->_getTime($this->_war[config][war][options][nofly][daily_until]);
                    if($from > $until) { // 22:00 - 9:00
                        list($from, $until) = array($until, $from);
            		if($time <= $from || $time >= $until) { // 9:00 - 22:00
                    	    $winner = ( $this->_war[side] == 'A' ? 'B' : 'A' );
                	}
                	else return false;
		    }
		    else { // 9:00 - 22:00
            		if($from <= $time && $time <= $until) {
                    	    $winner = ( $this->_war[side] == 'A' ? 'B' : 'A' );
                	}
                	else return false;
		    }
                    break;
                case 'period':
                    if(
                        $this->_getDate($this->_war[config][war][options][nofly][date_from]) < $time &&
                        $this->_getDate($this->_war[config][war][options][nofly][date_until]) > $time
                    )
                        $winner = ( $this->_war[side] == 'A' ? 'B' : 'A' );
                    else return false;
                    break;
                default:
                    return false;
            }
            sql_query("INSERT INTO chronicle SET time='".time()."', war_id='".$this->_war[id]."', causer='".$this->_thisAllianceTag."', occasion='end', victory='breach'");
            $this->endWar($this->_thisAllianceTag, '', 'ceasefire', $winner);
            return true;
        }
        return false;
    }

    /**
     * Check if the given option is set in the currently loaded war.
     * For possible options see class constants beginning with NO_*.
     * If no war has been loaded with load() before calling this function it will load
     * the currently open war. If the alliance is not in a war, false is returned.
     *
     * @param integer the option to check
     * @return boolean
     */
    public function checkWarOptions($option) {
        if(!$this->inWar()) return false;
        if(!is_array($this->_war)) {
            $this->load();
        }
        switch ($option) {
            case self::NO_APPLICATION:
                $return = $this->_war[config][war][options][no_new] == 1; break;
            case self::NO_FLY:
                $return = $this->_war[config][war][options][no_fly] == 1; break;
            case self::NO_LEAVE:
                $return = $this->_war[config][war][options][no_leave] == 1; break;
            case self::NO_VACATION:
                $return = $this->_war[config][war][options][no_vacation] == 1; break;
            default:
                $return = false;
        }
        return $return;
    }

    /**
     * Check if the alliance of this war instance is currently in an active war.
     *
     * @return boolean
     */
    public function inWar() {
        return count($this->getWars(self::TYPE_OPEN)) > 0;
    }

    /**
     * Check if the alliance of this war instance is currently in a war negotiation.
     *
     * @return boolean
     */
    public function inNegotiation() {
        return count($this->getWars(self::TYPE_NEGO)) > 0;
    }

    /**
     * Returns an array with all opponents in the loaded war.
     *
     * @return array
     */
    public function getOpponents() {
        $other_side = $this->_war[side] == 'A' ? 'B' : 'A';
        if(!is_array($this->_war[$other_side])) return array();
        return array_keys($this->_war[$other_side]);
    }

    /**
     * Check if the given alliance is in an active war against the alliance of this war instance.
     *
     * @param string alliance tag to check
     * @return boolean
     */
    public function isOpponent($tag) {
        return in_array($tag, $this->getOpponents());
    }

    /**
     * Returns an array with all allies in the loaded war.
     *
     * @return array
     */
    public function getAllies() {
        $side = $this->_war[side] == 'B' ? 'B' : 'A';
        if(!is_array($this->_war[$side])) return array();
        return array_diff(array_keys($this->_war[$side]),array($this->_thisAllianceTag));
    }

    /**
     * Returns an array with all allies in the loaded war, this one included.
     *
     * @return array
     */
    public function getAllAllies() {
        $side = $this->_war[side] == 'B' ? 'B' : 'A';
        if(!is_array($this->_war[$side])) return array();
        return array_keys($this->_war[$side]);
    }

    public function getAttackers() {
        if(!is_array($this->_war['A'])) return array();
        return array_keys($this->_war['A']);
    }

    public function getDefenders() {
        if(!is_array($this->_war['B'])) return array();
        return array_keys($this->_war['B']);
    }

    /**
     * Check if the given alliance is an ally in an active war with the alliance of this war instance.
     *
     * @param string alliance tag to check
     * @return boolean
     */
    public function isAlly($tag) {
        return in_array($tag, $this->getAllies());
    }

    /**
     * Returns a list of all wars filtered by the given type. For possible types see class constants
     * beginning with TYPE_*.
     *
     * @param integer war type
     * @return array
     */
    public function getWars($type = self::TYPE_ALL, $with_config = false) {
        $where = '';
        switch($type) {
            case self::TYPE_OPEN: $where = ' AND approved = "Y" AND winner IS NULL'; break;
            case self::TYPE_WON: $where = ' AND approved = "Y" AND winner = side'; break;
            case self::TYPE_LOST: $where = ' AND approved = "Y" AND winner != side'; break;
            case self::TYPE_NEGO: $where = ' AND denied="N" AND cancelled="N" AND approved = "N" AND winner IS NULL'; break;
            case self::TYPE_DENIED: $where = ' AND denied="Y"'; break;
            case self::TYPE_CANCELLED: $where = ' AND cancelled="Y"'; break;
            case self::TYPE_EMPTY:
                return array(array('id'=>'0',
                    'challenger' => array($this->_thisAllianceTag), 'opponent'=> array(),
                    'challenger_string' => $this->_thisAllianceTag, 'opponent_string'=> '',
                    'config' => $with_config ? $this->_generateEmptyConfig() : NULL
                ));
            break;
            default: /* TYPE_ALL */
                 $where = '';
            break;
        }
        $wars = array();
        $get_wars = sql_query("SELECT wars.*, war_party.side,war_party.accepted FROM wars, war_party ".
            "WHERE wars.id = war_party.war_id ".
            "AND war_party.tag = '".addslashes($this->_thisAllianceTag)."'".$where);
        while( $row = sql_fetch_assoc( $get_wars ) ) {
            $tmp = array('A'=> array(),'B'=> array());
            $parties = sql_query( 'SELECT tag,side FROM war_party WHERE war_id='.$row[id] );
            while($p = sql_fetch_assoc($parties))
                $tmp[$p['side']][] = $p['tag'];

            $wars[] = array('id'=>$row[id],
                'challenger'        => $tmp['A'],
                'challenger_string' => implode(', ', $tmp['A']),
                'opponent'          => $tmp['B'],
                'opponent_string'   => implode(', ', $tmp['B']),
                'config'            => $with_config ? unserialize($row[config]) : NULL,
                'side'              => $row[side],
                'accepted'          => $row['accepted'],
                'finishable'		=> $row['begin'] + 3*24*3600 < time(),
            	'remis'				=> $row['remis'],
            	'remis2'			=> $row['remis2']
            );
        }
        return $wars;
    }

    /**
     * Load the war with the given id into this Krieg instance.
     * If no id is given, the currently open war gets loaded.
     *
     * @param integer $warId
     * @return boolean
     */
    public function load($warId = NULL) {
        if(is_null($warId)) {
            list( $open_war ) = $this->getWars(self::TYPE_OPEN);
            if(isset($open_war))
                $this->load($open_war[id]);
            return true;
        }

        if(!is_numeric($warId)) return false;

        $war = array( 'A'=> array(), 'B'=> array() );

        $get_war = sql_query("SELECT wars.*, war_party.side, war_party.accepted FROM wars, war_party".
            " WHERE wars.id = war_party.war_id AND wars.id=".intval($warId).
            " AND war_party.tag='".addslashes($this->_thisAllianceTag)."'");
        $row = sql_fetch_assoc( $get_war );
        sql_free_result($get_war);
		# TODO: $war[config][remis] und [remis2] nur setzen wenn Gegnerische Allianz (Allianz=Allianz)
        if($row[id] != 0) {
            $parties = sql_query("SELECT * FROM war_party WHERE war_id=".$row[id]);
            while($p = sql_fetch_assoc($parties))
                $war[$p['side']][$p[tag]] = $p;
            sql_free_result($parties);

            $war[config] = unserialize($row[config]);
            $war[id] = $row[id];
            $war[side] = $row[side];
            $war[accepted] = $row[accepted];
            $war[finishable] = ( $row[start] + 3*24*3600 < time() );
            
        	$select = sql_query("SELECT tag FROM war_party INNER JOIN usarios ON war_party.tag=usarios.alliance WHERE war_id='".$this->_war[id]."' AND NOT user='$_SESSION[user]';");
	        $select = sql_fetch_array($select);
	        if($select[tag] == $war[config][remis2])
	        	$war[config][war][remis2] = $select[tag];
        	else
        		$war[config][war][remis] = 2;
	        
	        
            $this->_war = $war;
            $this->_state = ( !empty($row[winner]) ? self::STATE_CLOSED : self::STATE_OPEN );
            return true;
        }
        else return false;
    }

    /**
     * Return an array with all errors that occured during processing.
     *
     * @return mixed
     */
    public function getErrors() {
        if(!empty($this->_validation_errors))
            return $this->_validation_errors;
        else
            return false;
    }


    /*
     * section for protected functions.
     *
     */

    /**
     * Add a new party to the war specified by war_id.
     *
     * @param integer	the ID of the war to add to
     * @param string	alliance tag to add
     * @param string	side to add the new party to: 'A' xor 'B'
     * @param string	has the new party accepted the conditions: 'Y' xor 'N'
     */
    protected function _addParty($war_id, $tag, $side, $accepted) {
        sql_query('INSERT INTO war_party (id,tag,begin_state,end_state,war_id,side,accepted,accepted_version) '.
                  'VALUES (null,"'.$tag.'","","",'.$war_id.',"'.$side.'","'.$accepted.'",
                    (SELECT config_version FROM wars WHERE id='.$war_id.'))'
                 );
    }

    /**
     * Returns an array with all participients in the loaded war.
     *
     * @return array
     */
    protected function _getParties() {
        $a = is_array($this->_war['A']) ? array_keys($this->_war['A']) : array();
        $b = is_array($this->_war['B']) ? array_keys($this->_war['B']) : array();
        return array_merge($a, $b);
    }

    /**
     * Save the state of given alliance into an array.
     *
     * @param string alliance tag
     * @return array
     */
    protected function _saveState($tag) {
        $state = array();
        $users = sql_query('SELECT * FROM usarios,userdata WHERE usarios.user=userdata.user AND alliance="'.addslashes($tag).'"');

        while( $row = sql_fetch_assoc($users) ) {

            $cities = array();
            $get_cities = sql_query("SELECT city FROM city WHERE user='".$row[user]."'");
            while($city = sql_fetch_assoc($get_cities)) $cities[] = $city[city];
            sql_free_result($get_cities);

            $state[$row[user]] = array(
                'stats' => array('vacation' => $row[holiday] ),
                'cities' => $cities
            );
        }
        return $state;
    }

    function _isNatural($n) {
        return ($n == ceil($n));
    }

    /**
     * Validate the given war configuration against some simple rules.
     *
     * @param array configuration
     * @return boolean
     */
    protected function _checkConfig($config) {
        if(!is_array($config)) { $this->_validation_errors[] = 'Internal error I1.'; return false; }
        if(!is_array($config[war])) { $this->_validation_errors[] = 'Internal error I2.'; return false; }
        if(!is_array($config[war][begin])) { $this->_validation_errors[] = 'Internal error I3.'; return false; }
        if(!is_array($config[war][end])) { $this->_validation_errors[] = 'Internal error I4.'; return false; }
        if(!is_array($config[war][options])) { $this->_validation_errors[] = 'Internal error I5.'; return false; }
        if(!is_array($config[war][options][nofly])) { $this->_validation_errors[] = 'Internal error I6.'; return false; }
        if(!in_array($config[war][begin][mode], array('date','accept'))) { $this->_validation_errors[] = 'Auswahl Beginn-Modus falsch.'; return false; }
        if($config[war][begin][mode] == 'date') {
            if(!$this->_isDate($config[war][begin][date])) { $this->_validation_errors[] = 'Anfangsdatum ist kein Datum.'; return false; }
            if($this->_getDate($config[war][begin][date]) <= time()) { $this->_validation_errors[] = 'Anfangsdatum in Vergangenheit.'; return false; }
        }
        if($config[war][end][opt_time] == true) {
            if(!in_array($config[war][end][timemode], array('date','days'))) { $this->_validation_errors[] = 'Auswahl Ende-Bedingung falsch.'; return false; }
            if($config[war][end][timemode] == 'date') {
                if(!$this->_isDate($config[war][end][date])) { $this->_validation_errors[] = 'Enddatum ist kein Datum.'; return false; }
                if($config[war][begin][mode] == 'date') {
                    if($this->_getDate($config[war][end][date]) < $this->_getDate($config[war][begin][date]) + 3*24*3600) {
                        $this->_validation_errors[] = 'Ungültiges Endedatum oder Zeitspanne zu kurz (mind. 3 Tage).'; return false;
                    }
                    if($this->_getDate($config[war][end][date]) > $this->_getDate($config[war][begin][date]) + 30*24*3600) {
                        $this->_validation_errors[] = 'Ungültiges Endedatum oder Zeitspanne zu lang (max. 30 Tage).'; return false;
                    }
                }
                else {
                    if($this->_getDate($config[war][end][date]) < time() + 3*24*3600) {
                        $this->_validation_errors[] = 'Ungültiges Endedatum oder Zeitspanne zu kurz (mind. 3 Tage).'; return false;
                    }
                    if($this->_getDate($config[war][end][date]) > time() + 30*24*3600) {
                        $this->_validation_errors[] = 'Ungültiges Endedatum oder Zeitspanne zu lang (max. 30 Tage).'; return false;
                    }
                }
            }
            if($config[war][end][timemode] == 'days') {
                 if(!is_numeric($config[war][end][days])) { $this->_validation_errors[] = 'Zeitspanne ist keine Zahl.'; return false; }
                 if(!$this->_isNatural($config[war][end][days])) { $this->_validation_errors[] = 'Zeitspanne ist keine ganze Zahl.'; return false; }
                 if(intval($config[war][end][days]) < 3) { $this->_validation_errors[] = 'Zeitspanne zu kurz (mindestens 3 Tage).'; return false; }
                 if(intval($config[war][end][days]) > 30) { $this->_validation_errors[] = 'Zeitspanne zu lang (maximal 30 Tage).'; return false; }
            }
        }
        if($config[war][options][no_fly] == true) {
            if(!in_array($config[war][options][nofly][mode], array('daily','period'))) { $this->_validation_errors[] = 'Modus des Flugverbots nicht gewählt.'; return false; }
            if($config[war][options][nofly][mode] == 'period') {
                if(!$this->_isDate($config[war][options][nofly][date_from])) { $this->_validation_errors[] = 'Flugverbot-Beginn ist kein Datum.'; return false; }
                if(!$this->_isDate($config[war][options][nofly][date_until])) { $this->_validation_errors[] = 'Flugverbot-Ende ist kein Datum.'; return false; }
                if($this->_getDate($config[war][options][nofly][date_from]) >= $this->_getDate($config[war][options][nofly][date_until]) ) {
                    $this->_validation_errors[] = 'Flugverbotszeitraum falsch.'; return false;
                }
// TODO: Check Beginn Flug > Beginn && Ende Flug < Ende
            }
            if($config[war][options][nofly][mode] == 'daily') {
                if(!$this->_isTime($config[war][options][nofly][daily_from])) { $this->_validation_errors[] = 'Flugverbot-Beginn ist keine Zeit.'; return false; }
                if(!$this->_isTime($config[war][options][nofly][daily_until])) { $this->_validation_errors[] = 'Flugverbot-Ende ist keine Zeit.'; return false; }
            }
        }
        if($config[war][end][opt_kololost] == true) {
            if(!is_numeric($config[war][end][kolos])) { $this->_validation_errors[] = 'Kolonien ist keine Zahl.'; return false; }
            if(!$this->_isNatural($config[war][end][kolos])) { $this->_validation_errors[] = 'Kolonien ist keine ganze Zahl.'; return false; }
	}
        if($config[war][end][opt_memberlost] == true) {
            if(!is_numeric($config[war][end][members])) { $this->_validation_errors[] = 'Mitglieder ist keine Zahl.'; return false; }
            if(!$this->_isNatural($config[war][end][members])) { $this->_validation_errors[] = 'Mitglieder ist keine ganze Zahl.'; return false; }
	}
//        if($config[war][end][opt_time] != true && $config[war][end][opt_kololost] != true && $config[war][end][opt_memberlost] != true) {
//            $this->_validation_errors[] = 'Keine Endebedingung gewählt.'; return false;
//        }
        if(! is_array($config[war][opponents]) || empty($config[war][opponents])) {
            $this->_validation_errors[] = 'Keine Gegner gew&auml;hlt.'; return false;
        }
        if(is_array($config[war][allies]) && (boolean) array_intersect($config[war][opponents], $config[war][allies])) {
            $this->_validation_errors[] = 'Partner und Gegner identisch.'; return false;
        }
        $tags = array_merge($config[war][opponents], is_array($config[war][allies]) ? $config[war][allies] : array() );
        $check_ally = sql_query('SELECT 1 FROM alliances WHERE tag IN ("'.implode('","', $tags ).'")');
        if(sql_num_rows($check_ally) != count($tags)) {
            $this->_validation_errors[] = 'Partner- oder Gegnerallianz nicht gefunden.'; return false;
        }
        $myside = $otherside = 0;
        if(is_array($config[war][allies])) {
            $tmp = array_merge($config[war][allies], array($this->_thisAllianceTag));
            $colonies_a = $this->_countColonies( $tmp );
            $users_a    = $this->_countMembers( $tmp );
            $myside  = array_reduce($config[war][allies], create_function('$v,$w', '$k = new Krieg($w); return ($v += $k->inWar());'));
        }
        else {
            $colonies_a = $this->_countColonies(array($this->_thisAllianceTag));
            $users_a    = $this->_countMembers( array($this->_thisAllianceTag));
        }
        $colonies_o = $this->_countColonies($config[war][opponents]);
        $users_o    = $this->_countMembers( $config[war][opponents]);
        $otherside  = array_reduce($config[war][opponents], create_function('$v,$w', '$k = new Krieg($w); return ($v += $k->inWar());'));
        if(intval($config[war][end][kolos]) > min(array($colonies_a, $colonies_o))) {
            $this->_validation_errors[] = 'Kolonienanzahl grösser als die einer Seite.'; return false;
        }
        if(intval($config[war][end][members]) > min(array($users_a, $users_o))) {
            $this->_validation_errors[] = 'Mitgliederanzahl grösser als die einer Seite.'; return false;
        }
        if($myside > 0) {
            $this->_validation_errors[] = 'Eine oder mehrere Partner-Allianzen befinden sich bereits im Krieg.'; return false;
        }
        if($otherside > 0) {
            $this->_validation_errors[] = 'Eine oder mehrere Gegner-Allianzen befinden sich bereits im Krieg.'; return false;
        }
        return true;
    }

    /**
     * Modify the given config array and set the implicit options.
     *
     * @param array the config to modify
     * @return array
     */
    protected function _modifyConfig($config) {
        if($config[war][end][opt_memberlost]) { //  Aufnahmesperre Ja, Urlaubsperre Nein, Austrittsperre Nein
            $config[war][options][no_new] = 1;
            $config[war][options][no_vacation] = 0;
            $config[war][options][no_leave] = 0;
        }
        return $config;
    }

    /**
     * Create an empty configuration with some default values.
     *
     * @return array
     */
    protected function _generateEmptyConfig() {
        return array('war' => array(
            'begin' => array(
                'mode' => '',
                'date' => '',
            ),
            'end' => array(
                'opt_time' => false,
                'date' => '',
                'days' => '',
                'timemode' => '',
                'opt_kololost' => false,
                'kolos' => '',
                'opt_memberlost' => false,
                'members' => '',
            ),
            'options' => array(
                'no_fly' => false,
                'nofly' => array(
                    'mode' => '',
                    'date_from' => '',
                    'date_until' => '',
                    'daily_from' => '',
                    'daily_until' => '',
                ),
                'no_new' => false,
                'no_vacation' => false,
                'no_leave' => false,
            )
        ));
    }

    /**
     * Check if the given string is a valid date in either DD.MM.YY or DD.MM.YYYY format.
     *
     * @param string	the date string to check
     * @return boolean
     */
    protected function _isDate($str) {
        return preg_match('/^\d{1,2}\.\d{1,2}\.\d{2}(\d{2}){0,1}$/', $str) && 
               ( is_array(strptime($str, '%d.%m.%y')) || is_array(strptime($str, '%d.%m.%Y')) );
    }

    /**
     * Check if the given string is a valid time HH:MM format.
     *
     * @param string	the time string to check
     * @return boolean
     */
    protected function _isTime($str) {
        return is_array(strptime($str, '%H:%M'));
    }

    /**
     * Convert the given date string from DD.MM.YY or DD.MM.YYYY format into unix timestamp.
     *
     * @param  string	the date string to convert
     * @return integer
     */
    protected function _getDate($str) {
        $ftime = strptime($str, '%d.%m.%Y');
        if(!is_array($ftime)) $ftime = strptime($str, '%d.%m.%y');
        if(is_array($ftime))
            return mktime(
                0,
                0,
                0,
                $ftime['tm_mon'] + 1,
                $ftime['tm_mday'],
                $ftime['tm_year'] + 1900
            );
        return false;
    }

    /**
     * Convert the given time string from HH:MM format to seconds since midnight.
     *
     * @param  string	the time string to convert
     * @return integer
     */
    protected function _getTime($str) {
        $ftime = strptime($str, '%H:%M');
        if(is_array($ftime)) {
            return $ftime['tm_hour']*3600 + $ftime['tm_min']*60 + $ftime['tm_sec'];
        }
        return false;
    }

    /**
     * Returns the number of colonies owned by the alliances given in the parameter array.
     *
     * @param  array	an array of alliance tags
     * @return integer
     */
    protected function _countColonies($tags) {
        $res = sql_query('SELECT COUNT(user) FROM city WHERE alliance IN ("'.implode('","',$tags).'") AND home="NO"');
        list( $count ) = sql_fetch_row($res);
        return $count;
    }

    /**
     * Returns the number of users united in the alliances given in the parameter array.
     *
     * @param  array	an array of alliance tags
     * @return integer
     */
    protected function _countMembers($tags) {
        $res = sql_query('SELECT COUNT(user) FROM usarios WHERE alliance IN ("'.implode('","',$tags).'")');
        list( $count ) = sql_fetch_row($res);
        return $count;
    }

    /**
     * Sends a IGM to the founder and all admins of the given alliance.
     *
     * @param string	alliance tag
     * @param string	subject of igm
     * @param string	message body of igm
     */
    protected function _sendAllyAdminMail($tag, $subject, $text) {
        $admins_res = sql_query('SELECT user FROM usarios WHERE alliance="'.$tag.'" AND alliance_status IN ("admin", "founder")');
        while($admin = sql_fetch_assoc($admins_res)) {
            sql_query(
                "INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,confirm,dir) VALUES (".
                "'Kriegverwaltung','$admin[user]','$admin[user]'," .
                time() . ",'" . addslashes( $subject ) . "','" . addslashes( $text ) . "','N',".MessageCenterController::FOLDER_INBOX.")" );
        }
    }

    /*
     * Input-Format of $state
     *          $state[user] = array(
     *              'stats' => array( 'vacation' => 1 xor 0 ),
     *              'cities' => [city,city,city,city,city,city]
     *          );
     *
     */
    private function _flattenStateCities($state) {
        $cities = array();
        foreach(array_keys($state) as $u)
            $cities = array_merge($cities, $state[$u][cities]);
        return $cities;
    }

    private function _flattenStateUsers($state) {
        $users = array();
        foreach(array_keys($state) as $u)
            if($state[$u]['stats']['vacation'] == false)
                $users[] = $u;
        return $users;
    }

    /**
     * Do the accounting of the war and return the winner side. This is not true! This function 
     * rather returns an array with the keys A and B (war parties) and as its values the number of 
     * starting colonies the party conquered from the enemy minus the number it lost of its own.
     *
     * @return string	winner: 'A' xor 'B'
     */
    protected function _diffColonies() {
        $stats = array('A' => 0, 'B' => 0);
        $seite_beginn = $seite_jetzt = array('A'=>array(), 'B'=>array());

        if(is_array($tags = $this->_getParties())) {
            foreach($tags as $tag) {
                $result = sql_query("SELECT begin_state, side FROM war_party WHERE war_id='".$this->_war[id]."' AND tag='".$tag."'");
                $party = sql_fetch_assoc($result);
                sql_free_result($result);

                $begin_state = unserialize($party['begin_state']);
                $current_state = $this->_saveState($tag);

                $seite_beginn[$party['side']] = array_merge($seite_beginn[$party['side']], $this->_flattenStateCities($begin_state));
                $seite_jetzt[$party['side']] = array_merge($seite_jetzt[$party['side']], $this->_flattenStateCities($current_state));
            }
            $stats['A'] = count(array_intersect( $seite_jetzt['A'], $seite_beginn['B'])) -
                          count(array_intersect( $seite_beginn['A'], $seite_jetzt['B']));
            $stats['B'] = -$stats['A'];
        }
        return $stats;
    }

    /**
     * Do the accounting of the war and return the winner side. This is not true! This function 
     * rather returns an array with the keys A and B (the war parties) and as its values the number 
     * of members they lost since the beginning of the war.
     *
     * @return string	winner: 'A' xor 'B'
     */
    protected function _diffMembers() {
        $stats = array('A' => 0, 'B' => 0);
        $seite_beginn = $seite_jetzt = array('A'=>array(), 'B'=>array());

        if(is_array($tags = $this->_getParties())) {
            foreach($tags as $tag) {
                $result = sql_query("SELECT begin_state, side FROM war_party WHERE war_id='".$this->_war[id]."' AND tag='".$tag."'");
                $party = sql_fetch_assoc($result);
                sql_free_result($result);

                $begin_state = unserialize($party['begin_state']);
                $current_state = $this->_saveState($tag);

                $seite_beginn[$party['side']] = array_merge($seite_beginn[$party['side']], $this->_flattenStateUsers($begin_state));
                $seite_jetzt[$party['side']] = array_merge($seite_jetzt[$party['side']], $this->_flattenStateUsers($current_state));
            }
            $stats['A'] = count(array_diff( $seite_beginn['A'], $seite_jetzt['A']));
            $stats['B'] = count(array_diff( $seite_beginn['B'], $seite_jetzt['B']));
        }
        return $stats;
    }

    protected function _alliance_filter($element) {
        $element = trim($element);
        $tmp = sql_fetch_assoc(sql_query('SELECT tag FROM alliances WHERE tag="'.addslashes($element).'"'));
        if(!empty($tmp[tag]))
            $element = $tmp[tag];
        return $element;
    }

    protected function _calcFame() {
        $APowerr = sql_query('SELECT SUM(power) FROM alliances, war_party WHERE alliances.tag=war_party.tag AND war_id='.$this->_war[id].' AND side="A" GROUP BY side');
        $BPowerr = sql_query('SELECT SUM(power) FROM alliances, war_party WHERE alliances.tag=war_party.tag AND war_id='.$this->_war[id].' AND side="B" GROUP BY side');
        list($APower) = sql_fetch_row($APowerr);
        list($BPower) = sql_fetch_row($BPowerr);
        sql_free_result($APowerr);
        sql_free_result($BPowerr);
        if ($APower == 0 || $BPower == 0)
          return array(0,0);

        $Ar = sql_query('SELECT COUNT(*),SUM(fame_own) FROM usarios, war_party WHERE usarios.alliance=war_party.tag AND war_id='.$this->_war[id].' AND side="A" GROUP BY side');
        $Br = sql_query('SELECT COUNT(*),SUM(fame_own) FROM usarios, war_party WHERE usarios.alliance=war_party.tag AND war_id='.$this->_war[id].' AND side="B" GROUP BY side');
        list($Amember, $Afame) = sql_fetch_row($Ar);
        list($Bmember, $Bfame) = sql_fetch_row($Br);
        sql_free_result($Ar);
        sql_free_result($Br);

        // Case 1: fame points in case side A wins
        $a = ($Bfame+50)/($Afame+50);
        if     (0<=$a   && $a<=4.32)   $rFakA = 1.5*sin(0.1*$a*pi())+1;
        else if(4.32<$a && $a<=9.32)   $rFakA = 0.107*($a-4.32)+2.466;
        else                           $rFakA = 3;
        // fame = powerfactor * memberfactor * famefactor * base value
        $fameA = ($BPower/$APower) * max(1, pow(1.05, 0.1*($Bmember-$Amember))) * $rFakA * 200;

        // Case 2: fame points in case side B wins
        $a = ($Afame+50)/($Bfame+50);
        if     (0<=$a   && $a<=4.32)   $rFakB = 1.5*sin(0.1*$a*pi())+1;
        else if(4.32<$a && $a<=9.32)   $rFakB = 0.107*($a-4.32)+2.466;
        else                           $rFakB = 3;
        // fame = powerfactor * memberfactor * famefactor * base value
        $fameB = ($APower/$BPower) * max(1, pow(1.05, 0.1*($Amember-$Bmember))) * $rFakB * 200;
        
        return array($fameA, $fameB);
    }
}
?>
