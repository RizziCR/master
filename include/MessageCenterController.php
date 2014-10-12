<?php

/**
 * MessageCenterController
 *
 * @author	gawain
 * @version 0.1
 */

require_once 'include/ETS_View.php';
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Controller/Request/Http.php';
require_once 'Zend/Controller/Response/Http.php';

class MessageCenterController extends Zend_Controller_Action {

    const MODE_INBOX = 1;
    const MODE_OUTBOX = 2;
    const MODE_ARCHIVE = 4;
    const MODE_TRASH = 8;
    const MODE_FOLDER = 16;

    const USERFOLDERSTART = 10;

    const FOLDER_INBOX = 0;
    const FOLDER_OUTBOX = 1;
    const FOLDER_ARCHIVE = 2;
    const FOLDER_TRASH = 3;

    protected $_mode = MessageCenterController::MODE_INBOX;
    protected $_folder = MessageCenterController::FOLDER_INBOX;
    protected $_enable_allymail = false;
    protected $_alliance = null;
    protected $_preview = false;

    /**
     * Constructs a new MessageCenter (done)
     *
     * @param object $_request
     * @param object $_response
     */

    public function __construct(Zend_Controller_Request_Abstract $_request,
                                Zend_Controller_Response_Abstract $_response,
                                array $invokeArgs = array()) {
#    public function __construct($_request = null, $_response = null, $invokeArgs = array()) {
        parent::__construct( $_request, $_response, $invokeArgs );

        $this->view = new ETS_View( );
        $this->view->setController( $this );
        $this->view->set( 'baseurl', $_request->getBaseUrl() );
        $this->view->set( 'etsAddress', $GLOBALS[etsAddress]);

        if( isset( $_SESSION['msgctr_mode'] ) ) {
            $this->_mode   = $_SESSION['msgctr_mode'];
            $this->_folder = $_SESSION['msgctr_folder'];
        }

        $get_alliance_prefs = sql_query(
                "SELECT alliances.tag AS tag,alliances.admin_mails AS admin_mails,usarios.alliance_status AS status " .
                "FROM alliances RIGHT JOIN usarios ON alliances.ID=usarios.alliance WHERE usarios.ID='$_SESSION[sitter]'" );
        $alliance_prefs = sql_fetch_array( $get_alliance_prefs );
        if (! empty( $alliance_prefs[tag] ) && ($alliance_prefs[admin_mails] == "N" || $alliance_prefs[status] != "member")) {
            $this->_enable_allymail = true;
            $this->_alliance = $alliance_prefs[tag];
        }
    }

    public function __destruct() {
        $_SESSION['msgctr_mode'] = $this->_mode;
        $_SESSION['msgctr_folder'] = $this->_folder;
    }

    /**
     * This action is responsable for rendering message listviews (done)
     */
    public function indexAction() {
        switch ( $this->getRequest ()->ord) {
            case 1 :  $order = "sender ASC, time DESC"; break;
            case 2 :  $order = "topic ASC, time DESC"; break;
            default : $order = "time DESC"; break;
        }

        $search = '';
        if(is_array($this->getRequest()->search)) {
            $search = array();
            foreach($this->getRequest()->search as $criteria => $value) {
                $search[] = (addslashes($criteria).' LIKE "%'.addslashes($value).'%"');
            }
            $search = ' AND '.implode(' AND ', $search);
        }

        $all_mesg = array ( );
        ###### TODO ::::::::
        $get_msgs = sql_query( "SELECT id,sender,recipient,time,seen,topic FROM news_igm_umid WHERE owner='$_SESSION[sitter]' && dir='".$this->_folder."' $search ORDER BY $order" );
        while( $msgs = sql_fetch_array( $get_msgs ) ) {
            $msgs[topic] = stripslashes( $msgs[topic] );
            if(!trim($msgs[topic])) {
                $msgs[topic] = $GLOBALS[MESSAGES][MSG_MSGCTR][m044];
            }
            if ($msgs[seen] == "N")
                $msgs[topic] = "<font style='color:#00FF00'>" . $msgs[topic] . "</font>";
            $sUser = new User($msgs[sender]);
            if(strpos($msgs[recipient],',') === false) {
                $fUser = new User($msgs[recipient]);
                $recipientname = $fUser->getScreenName();
                $link = true;
            }
            else {
                $recipientname = '';
                $recipients = explode(',', $msgs[recipient]);
        $_i = 1;
                foreach($recipients as $r) {
                    $rUser = new User($r);
                    $recipientname .= $rUser->getName(). ($_i++ % 3 == 0 ? '<br />' : ' - ');
                }
                $link = false;
            }

            $all_mesg[] = array ('id' => $msgs[id], 'topic' => $msgs[topic], 'sender' => $msgs[sender], 'recipient' => $msgs[recipient],
                        'time' => ETSZeit( $msgs[time] ), 'sendername' => $sUser->getScreenName(), 'recipientname' => $recipientname,
                        'linkrecipient' => $link);
        }

        $this->populateMenu();
        $this->view->set( 'title', $this->getFolderDescription($this->_folder) );
        $this->view->set( 'all_mesg', $all_mesg );
        $this->view->set( 'mode', $this->_mode);
        $this->view->render( 'MessageCenter/index.html' );
    }

    /**
     * All functions that manipulate the state of a message (done)
     */
    public function manageAction() {
        if (count( $_POST[id] )) {
            for($r = 0; $r < count( $_POST[id] ); $r ++)
                if ($_POST[id][$r] != intval( $_POST[id][$r]) )
                    return;
            switch ( $_POST[submit]) {
                case $GLOBALS[MESSAGES][MSG_MSGCTR][m043] : // verschieben
                    $moveto = self::FOLDER_INBOX;
                    if ($_POST[directory])
                        $moveto = intval( $_POST[directory] );
                    sql_query(
                        "UPDATE news_igm_umid SET dir=".$moveto." WHERE owner='$_SESSION[sitter]' && id IN (". implode( ",", $_POST[id] ) .")" );
                break;

                case $GLOBALS[MESSAGES][MSG_MSGCTR][m029] : // löschen
                    if($this->_mode == self::MODE_TRASH) {
                        sql_query(
                            "DELETE FROM news_igm_umid WHERE owner='$_SESSION[sitter]' && id IN (". implode( ",", $_POST[id] ) .")" );
                    }
                    else {
                        sql_query(
                            "UPDATE news_igm_umid SET dir=".self::FOLDER_TRASH." WHERE owner='$_SESSION[sitter]' && id IN (". implode( ",", $_POST[id] ) .")" );
                    }
                break;

                case $GLOBALS[MESSAGES][MSG_MSGCTR][m041] : // gelesen
                    sql_query(
                        "UPDATE news_igm_umid SET seen='Y' WHERE owner='$_SESSION[sitter]' && id IN (". implode( ",", $_POST[id] ) .")" );
                break;

                case $GLOBALS[MESSAGES][MSG_MSGCTR][m042] : // ungelesen
                    sql_query(
                        "UPDATE news_igm_umid SET seen='N' WHERE owner='$_SESSION[sitter]' && id IN (". implode( ",", $_POST[id] ) .")" );
                break;
            }
        }  // count()

        else {
            if ($_POST[submit] == $GLOBALS[MESSAGES][MSG_MSGCTR][m084]) {
                sql_query(
                    "UPDATE news_igm_umid SET dir=".self::FOLDER_TRASH." WHERE owner='$_SESSION[sitter]' && dir=". self::FOLDER_INBOX );
            }
        }

#		$this->_redirect('index');
        header( 'Location: ./msgctr.php?action=index' );
    }

    /**
     * Gets a message from writeAction() and sends it to its recipients (done)
     */
    public function sendAction() {
        if ($this->getRequest()->subaction == $GLOBALS[MESSAGES][MSG_MSGCTR][m066]) // Vorschau
        {
            $this->_preview = true;
            $this->_forward( 'write' );
            return;
        }

        if ($_SESSION[next_msg_send] > time())
            ErrorMessageException( MSG_MSGCTR, e003 ); // Sie können keine weitere Nachricht verschicken, bitte haben Sie einen Moment Geduld

        if (empty($_POST[text]))
            ErrorMessageException( MSG_MSGCTR, e002 ); // Bitte schreiben Sie eine Nachricht

        $get_signature = sql_query( "SELECT signature FROM usarios WHERE ID='$_SESSION[sitter]'" );
        $signature = sql_fetch_array( $get_signature );

        if (trim( $signature[signature] ) != "")
            $sign = "\n\n------\n\n" . trim( $signature[signature] );

        $topic = $_POST[topic];
        if (! trim( $topic ))
            $topic = $GLOBALS[MESSAGES][MSG_MSGCTR][m044];

        if ($_POST[rconf])
            $readconfirm = "Y";
        else
            $readconfirm = "N";

        $recipients = array();

        // alliance members
        $auser = $this->getRequest()->getPost('auser');
        if( is_array($auser) && !empty($auser) )
            $recipients = array_merge($recipients, $auser );

        // single Users from input field
        $puser = $this->getRequest()->getPost('puser');
        if( is_string($puser) && !empty($puser)) {
            $tmp = explode( ",", addslashes( $puser ) );
            for($i = 0; $i < count( $tmp ); $i ++)
                $tmp[$i] = trim( $tmp[$i] );
            $recipients = array_merge($recipients, $tmp );
        }

        // users from addressbook group
        if( is_numeric($this->getRequest()->rec_clist_group) && intval($this->getRequest()->rec_clist_group) > 0) {
            $get_users_in_group = sql_query( "SELECT contact FROM adressbook WHERE gid='" . intval( $this->getRequest()->rec_clist_group ) . "'" );
            while( ($row = sql_fetch_array( $get_users_in_group )) ) {
                $recipients[] = $row[contact];
            }
        }

        // single user from addressbook
        $cuser = $this->getRequest()->getPost('rec_clist_user');
        if( is_string( $cuser) && !empty($cuser) )
            $recipients[] = $cuser;

        $recipients = array_unique( $recipients );

        $real_recipients = array();
        foreach($recipients as $r) {
            $get_user = sql_query( "SELECT ID,user FROM usarios WHERE user='" . addslashes($r) . "'" );
            $row = sql_fetch_array( $get_user );
            sql_free_result($get_user);
            
            //////////////// IGM Sperrfunktion durch den Support
            
            $get_sperr = sql_fetch_array(sql_query("SELECT user FROM sperrliste_igm WHERE user = '$_SESSION[sitter]'"));
            if($get_sperr['user'] != $_SESSION['sitter']) {
	            if(!empty($row[user])) { // richtige Schreibung
	                sql_query(
	                    "INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,confirm,dir) VALUES ('$_SESSION[sitter]','$row[ID]','$row[ID]'," .
	                    time() . ",'" . addslashes( $topic ) . "','" . addslashes( $_POST[text] . $sign ) . "','$readconfirm',".self::FOLDER_INBOX.")" );
	                $real_recipients[] = $row[ID];
	            }
            }else{
            	$real_recipients[] = $row[ID];
            }
        }

        if(!empty($real_recipients)) {
            sql_query(
                "INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,seen,dir) VALUES ('$_SESSION[sitter]','".
                implode(",", $real_recipients)."','$_SESSION[sitter]'," . time() . ",'" . addslashes( $topic ) . "','" . addslashes( $_POST[text] . $sign ) .
                "','YES',".self::FOLDER_OUTBOX.")" );
        }

        $_SESSION[next_msg_send] = time() + 10;

        $this->_forward('index');
    }

    /**
     * Renders the message write formular (check)
     */
    public function writeAction() {
        $msgid = intval($this->getRequest()->id);
        $msg = array();

        $msg[puser] = $this->getRequest()->puser;

        if ($msgid) // Reply and Forward
        {
            $get_msg = sql_query(
                    "SELECT topic,text,sender FROM news_igm_umid WHERE id=" . $msgid . " && owner='$_SESSION[user]'" );

            $msg = sql_fetch_assoc( $get_msg );
            
            $get_user = sql_query( "SELECT ID,user FROM usarios WHERE ID='" . addslashes($msg[sender]) . "'" );
            $sender = sql_fetch_array( $get_user );
            sql_free_result($get_user);

            switch($this->getRequest()->submit) {
                case $GLOBALS[MESSAGES][MSG_MSGCTR][m015]: // Reply with quote
                    if (substr( $msg[topic], 0, 4 ) == "Re: ")
                        $msg[topic] = stripslashes( $msg[topic] );
                    else
                        $msg[topic] = "Re: " . stripslashes( $msg[topic] );
                    $msg[puser] = $sender[user];
                    $msg[text] = '> '.wordwrap($msg[text], 50, "\n> ");
                break;
                case $GLOBALS[MESSAGES][MSG_MSGCTR][m025]: // Reply
                    if (substr( $msg[topic], 0, 4 ) == "Re: ")
                        $msg[topic] = stripslashes( $msg[topic] );
                    else
                        $msg[topic] = "Re: " . stripslashes( $msg[topic] );
                    $msg[puser] = $sender[user];
                    $msg[text] = '';
                break;
                case $GLOBALS[MESSAGES][MSG_MSGCTR][m032]: // Forward
                    if (substr( $msg[topic], 0, 5 ) == "Fwd: ")
                        $msg[topic] = stripslashes( $msg[topic] );
                    else
                        $msg[topic] = "Fwd: " . stripslashes( $msg[topic] );
                    $msg[text] = '> '.wordwrap($msg[text], 50, "\n> ");
                break;
            }
        } //ende Antworten auf

        if( $this->_preview ) {
            $this->view->set('preview_topic', $this->getRequest()->topic);
            $this->view->set('preview_text', BBCode($this->getRequest()->text));

            $msg[topic] = $this->getRequest()->topic;
            $msg[text] = $this->getRequest()->text;
            $this->_preview = false;
        }

        $gids = $groups = $users = array();
        $users[0] = array('name'=>'ohne Gruppe','user'=>array());
        $get_groups = sql_query( "SELECT id,name FROM adressbook_groups WHERE user='$_SESSION[user]'" );
        while( ($row = sql_fetch_array( $get_groups )) ) {
            $users[$row[id]] = array('name'=>$row[name], 'user'=>array());
            $groups[] = array('name'=>$row[name], 'id'=>$row[id], 'selected'=>$this->getRequest()->rec_clist_group == $row[id]);
        }

        $get_users = sql_query( "SELECT contact,gid FROM adressbook WHERE user='$_SESSION[user]' ORDER BY gid,contact" );
        while( ($row = sql_fetch_array( $get_users )) ) {
            $users[$row[gid]][user][] = array('name'=>$row[contact], 'selected'=>$this->getRequest()->rec_clist_user == $row[contact]);
        }

        $this->populateMenu();
        $this->view->set( 'title', $GLOBALS[MESSAGES][MSG_MSGCTR][m000] );
        $this->view->set( 'enable_amail', $this->_enable_allymail );
        $this->view->set( 'members', $this->getAllianceMembers( $this->_alliance ) );
        $this->view->set( 'groups', $groups);
        $this->view->set( 'users', $users);
        $this->view->set( 'topic', $msg[topic] );
        $this->view->set( 'text', $msg[text] );
        $this->view->set( 'puser', $msg[puser] );
        $this->view->set( 'rconf', $this->getRequest()->rconf );
        $this->view->render( 'MessageCenter/write.html' );

    }

    /**
     * All functions that manipulate the user folders (done)
     */
    public function manageFolderAction() {
        if ($this->getRequest()->getPost('submit') == $GLOBALS[MESSAGES][MSG_MSGCTR][m048]) {
            $get_dir_names = sql_query(
                    "SELECT null FROM news_directories WHERE ID='$_SESSION[sitter]' && name='" . addslashes($this->getRequest()->getPost('new_dir')) . "'" );
            if (! sql_num_rows( $get_dir_names ))
                sql_query(
                    "INSERT INTO news_directories (name,user) VALUES ('" . addslashes($this->getRequest()->getPost('new_dir')) . "', '$_SESSION[sitter]')" );
        }

        if ($this->getRequest()->del) {
            sql_query( "UPDATE news_igm_umid SET dir=".self::FOLDER_TRASH." WHERE owner='$_SESSION[sitter]' && dir='" . intval( $this->getRequest()->dir ) . "'" );
            sql_query( "DELETE FROM news_directories WHERE ID='$_SESSION[sitter]' && id='" . intval( $this->getRequest()->dir ) . "'" );
        }

        $folder = array();
        $get_directories = sql_query( "SELECT id,name FROM news_directories WHERE ID='$_SESSION[sitter]'" );
        if (sql_num_rows( $get_directories )) {
            $this->view->set( 'has_folder', true );
            while( $directories = sql_fetch_array( $get_directories ) ) {
                if ($i % 2)
                    $color = "#222222";
                else
                    $color = "#000000";
                $folder[] = array('id'=>$directories[id], 'name'=>$directories[name], 'color'=>$color);
                $i ++;
            }
        }

        $this->populateMenu();
        $this->view->set( 'title', $GLOBALS[MESSAGES][MSG_MSGCTR][m045] );
        $this->view->set( 'folder', $folder );
        $this->view->render( 'MessageCenter/managefolder.html' );
    }

    /**
     * Switch the MessageCenter into user folder mode (done)
     */
    public function folderAction() {
        $this->_folder = intval( $this->getRequest()->folder );

        switch ( $this->_folder) {
            case self::FOLDER_INBOX :   $this->_mode = self::MODE_INBOX; break;
            case self::FOLDER_OUTBOX :  $this->_mode = self::MODE_OUTBOX; break;
            case self::FOLDER_ARCHIVE : $this->_mode = self::MODE_ARCHIVE; break;
            case self::FOLDER_TRASH :   $this->_mode = self::MODE_TRASH; break;
            default :				    {
                $this->_mode = self::MODE_FOLDER;
                $result = sql_query(
                        "SELECT 1 FROM news_directories WHERE ID='$_SESSION[sitter]' && id=" . $this->_folder );
                if (sql_num_rows( $result ) == 0)
                    ErrorMessageException( MSG_MSGCTR, e005 );
            }
            break;
        }

        $this->_forward( 'index' );
    }

    /**
     * Retrieve a certain message from database and show it (done)
     */
    public function showAction() {
        $msgid = intval( $this->getRequest()->id );
        $get_msg = sql_query(
                "SELECT sender,recipient,time,topic,text,confirm,id FROM news_igm_umid WHERE id=" . $msgid . " && owner='$_SESSION[sitter]'" );

        if (! sql_num_rows( $get_msg ))
            ErrorMessageException( MSG_MSGCTR, e005 ); // Diese Nachricht gibt es nicht oder gehört Ihnen nicht

        $msg = sql_fetch_array( $get_msg );

        if (isset($this->getRequest()->rc) && $this->_folder == self::FOLDER_INBOX) {
            sql_query(
                    "INSERT INTO news_igm_umid (sender,recipient,owner,dir,time,topic,text,confirm)
                     VALUES ('$_SESSION[sitter]','$msg[sender]','$msg[sender]'," .self::FOLDER_INBOX. "," . time() . ",".
                         "'" . $GLOBALS[MESSAGES][MSG_MSGCTR][m061] . addslashes( $msg[topic] ) . "',".
                         "'" . $GLOBALS[MESSAGES][MSG_MSGCTR][m062] . addslashes( $msg[topic] ) .
                              $GLOBALS[MESSAGES][MSG_MSGCTR][m063] . ETSZeit_Plain( $msg[time] ) .
                              $GLOBALS[MESSAGES][MSG_MSGCTR][m064] . "','N')"
            );
        }

        $get_prev_id = sql_query(
                "SELECT Max(id) AS last FROM news_igm_umid WHERE owner='$_SESSION[sitter]' && dir=".$this->_folder." && id < " . $msgid );
        list ( $prev_id ) = sql_fetch_row( $get_prev_id ); // Vorherige Nachricht des Nutzers


        $get_next_id = sql_query(
                "SELECT Min(id) AS next FROM news_igm_umid WHERE owner='$_SESSION[sitter]' && dir=".$this->_folder." && id > " . $msgid );
        list ( $next_id ) = sql_fetch_row( $get_next_id ); // Nächste Nachricht des Nutzer

        sql_query( "UPDATE news_igm_umid SET seen='Y',confirm='S' WHERE id=". $msgid ." AND owner='". $_SESSION[sitter]. "'");
        $sUser = new User($msg[sender]);

        $msg[time] = ETSZeit( $msg[time] );
        $msg[text] = BBCode( $msg[text] );
        $msg[sendername] = $sUser->getScreenName();
        $msg[recipients] = array();
        $recipients = explode(',', $msg[recipient]);
        foreach($recipients as $r) {
            $rUser = new User($r);
            $msg[recipients][] = array('name'=>$r, 'screenname'=>$rUser->getScreenName());
        }

        $this->populateMenu();
        if ($msg[confirm] == "Y") {
            $this->view->set( 'addConfirmJS', 1);
        }
        $this->view->set( 'title', $GLOBALS[MESSAGES][MSG_MSGCTR][m006] );
        if(!trim($msg[topic])) {
            $msg[topic] = $GLOBALS[MESSAGES][MSG_MSGCTR][m044];
        }

        $this->view->set( 'msg', $msg );
        $this->view->set( 'nextid', $next_id );
        $this->view->set( 'previd', $prev_id );
        $this->view->set( 'etsAddress', $GLOBALS[etsAddress]);
        $this->view->render( 'MessageCenter/show.html' );
    }

    /**
     * Retrieve a certain message from database and send it to the users private email adress (done)
     */
    public function emailAction() {
        $msgid = intval( $this->getRequest()->id );
        $get_msg = sql_query(
                "SELECT sender,recipient,time,topic,text,confirm,id FROM news_igm_umid WHERE id=" . $msgid . " && owner='$_SESSION[sitter]'" );
        if (sql_num_rows( $get_msg ) == 0)
            ErrorMessageException( MSG_MSGCTR, e005 );

        $msg = sql_fetch_array( $get_msg );
        $msg[time] = ETSZeit( $msg[time] );
        $msg[text] = nl2br(stripslashes( $msg[text] ));

        $get_email_adress = sql_query( "SELECT email FROM userdata WHERE ID='$_SESSION[sitter]'" );
        $email_adress = sql_fetch_array( $get_email_adress );

        require_once 'PHPTAL.php';
        require_once 'include/PHPTAL_EtsTranslator.php';
        $template = new PHPTAL( 'MessageCenter/mail.html' );
        $template->setTranslator( new PHPTAL_EtsTranslator( ) );
        $template->setEncoding( 'ISO-8859-1' );
        $template->set( 'msg', $msg );
        $mailtext = $template->execute();

        smtp_mail( $email_adress[email],
                "ETS-Nachricht: " . stripslashes( $msg[topic] ), $mailtext );

        $this->_forward( 'show' );
    }

    /**
     * Send notice to support that the given message violates the terms of usage (done)
     */
    public function agbAction() {
        $id = intval( $this->getRequest()->id );
        $get_msg = sql_query(
                "SELECT 1 FROM news_igm_umid WHERE id=" . $id . " && owner='$_SESSION[sitter]'" );
        if (sql_num_rows( $get_msg ) == 0)
            ErrorMessageException( MSG_MSGCTR, e005 );

        sql_query("INSERT INTO admin_agb_delict (sender,recipient,time,topic,text)
                   SELECT sender,recipient,time,topic,text FROM news_igm_umid WHERE id='$id' && owner='$_SESSION[sitter]'" );
        $this->_forward( 'show' );
    }

    /**
     * Display and modify the users message signature (done)
     */
    public function preferencesAction() {
        $submit = $this->getRequest()->submit;
        if (! empty( $submit ))
            sql_query(
                    "UPDATE usarios SET signature='" . addslashes(
                            substr( $this->getRequest()->getPost( 'sig' ), 0, 500 ) ) . "' WHERE ID='$_SESSION[sitter]'" );

        $get_signature = sql_query( "SELECT signature FROM usarios WHERE ID='$_SESSION[sitter]'" );
        $signature = sql_fetch_array( $get_signature );

        $this->populateMenu();
        $this->view->set( 'title', $GLOBALS[MESSAGES][MSG_MSGCTR][m074] );
        $this->view->set( 'signature',
                stripslashes( str_replace( "<br />", "\n", $signature[signature] ) ) );
        $this->view->set( 'chars',
                strlen( stripslashes( str_replace( "<br />", "\n", $signature[signature] ) ) ) );
        $this->view->render( 'MessageCenter/preferences.html' );
    }

    /**
     * Display and modify the users message signature (done)
     */
    public function searchAction() {
        $this->populateMenu();
        $this->view->set( 'title', $GLOBALS[MESSAGES][MSG_MSGCTR][m083] );
        $this->view->render( 'MessageCenter/search.html' );
    }

    /**
     * Populate the template engine with the values used by the navigation menu (done)
     */
    protected function populateMenu() {
        $get_msg_in_unseen = sql_query(
                "SELECT Count(*) AS anzahl FROM news_igm_umid WHERE owner='$_SESSION[sitter]' && dir=".self::FOLDER_INBOX." && seen='N'" );
        $msg_in_unseen = sql_fetch_array( $get_msg_in_unseen );
        $this->view->set( 'new_msgs', $msg_in_unseen[anzahl] );

        $get_directories = sql_query(
                "SELECT id,name FROM news_directories WHERE ID='$_SESSION[sitter]' ORDER BY name" );
        $get_directories_msgs_unseen = sql_query(
                "SELECT news_directories.id,Count(news_igm_umid.id) AS anzahl " .
                "FROM news_igm_umid RIGHT JOIN news_directories ON news_igm_umid.dir = news_directories.id && news_igm_umid.seen='N' " .
                "WHERE news_directories.ID='$_SESSION[sitter]' " .
                "GROUP BY news_directories.id ORDER BY news_directories.name" );

        $user_dirs = array ( );
        $user_dirs[] = array('id' => self::FOLDER_INBOX, 'name' => $GLOBALS[MESSAGES][MSG_MSGCTR][m001]);
        $user_dirs[] = array('id' => self::FOLDER_OUTBOX, 'name' => $GLOBALS[MESSAGES][MSG_MSGCTR][m002]);
        $user_dirs[] = array('id' => self::FOLDER_ARCHIVE, 'name' => $GLOBALS[MESSAGES][MSG_MSGCTR][m053]);
        $user_dirs[] = array('id' => self::FOLDER_TRASH, 'name' => $GLOBALS[MESSAGES][MSG_MSGCTR][m003]);
        while( $directories = sql_fetch_array( $get_directories ) ) {
            $directories_msgs_unseen = sql_fetch_array( $get_directories_msgs_unseen );
            $user_dirs[] = array ('id' => $directories[id], 'name' => $directories[name], 'anzahl' => $directories_msgs_unseen[anzahl] );
        }
        $this->view->set( 'user_directories', $user_dirs );

        $free_dirs = $user_dirs;
        unset($free_dirs[1]); // unset Outbox!
        $this->view->set( 'free_user_directories', $free_dirs);
    }

    /**
     * Build a list of alliance members
     *
     * @param string	the alliance tag to search users for
     * @return mixed	the array of memebers
     */
    protected function getAllianceMembers($aTag, $checkRequest = true) {
        $members = array ( );
        $alliance_id = sql_fetch_array(sql_query("SELECT alliance FROM usarios where ID=$_SESSION[user]"));
        $get_alliance_members = sql_query(
                "SELECT user FROM usarios WHERE alliance='$alliance_id[alliance]' && ID!='$_SESSION[user]' ORDER BY user" );
        while( ($alliance_members = sql_fetch_array( $get_alliance_members )) ) {
            $check = false;
            if ($checkRequest)
                $check = is_array($this->getRequest()->auser) && in_array($alliance_members['user'], $this->getRequest()->auser);
            $members[] = array ('name' => $alliance_members[user], 'selected' => $check );
        }
        return $members;
    }

    public function getFolderDescription($folder)
    {
        switch($folder)
        {
            case self::FOLDER_INBOX:
              return $GLOBALS[MESSAGES][MSG_MSGCTR][m001];
            case self::FOLDER_OUTBOX:
              return $GLOBALS[MESSAGES][MSG_MSGCTR][m002];
            case self::FOLDER_ARCHIVE:
              return $GLOBALS[MESSAGES][MSG_MSGCTR][m053];
            case self::FOLDER_TRASH:
              return $GLOBALS[MESSAGES][MSG_MSGCTR][m003];
            default:
              return "Eigenes Verzeichnis";
        }
        // invalid folder chosen
        return "";
    }

    //redirect to standartaction instead of exception
    public function __call($methodName,$params) {
        $this->indexAction();
    }
} // End of Class
