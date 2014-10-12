<?php
require_once("functions.php");
require_once("database.php");

switch($_REQUEST[action])
{
    case "add_cat" :
        if ($_REQUEST[cat])
            sql_query("INSERT INTO admin_faq_cat (name) VALUES ('".addslashes($_REQUEST[cat])."')");
        break;

    case "del_cat" :
        if ($_REQUEST[cid])
        {
            sql_query("DELETE FROM admin_faq WHERE cat='".intval($_REQUEST[cid])."'");
            sql_query("DELETE FROM admin_faq_cat WHERE id='".intval($_REQUEST[cid])."'");
        }
        break;

    case "change_cat" :
        if ($_REQUEST[id] && $_POST[name])
            sql_query("UPDATE admin_faq_cat SET name='".addslashes($_POST[name])."' WHERE id='".intval($_REQUEST[id])."'");


    case "add_question" :
        if ($_REQUEST[cid] && $_POST[question] && $_POST[answer])
            sql_query("INSERT INTO admin_faq (cat,question,answer,title,description,keywords) ".
                "VALUES ('".intval($_REQUEST[cid])."','".addslashes($_POST[question])."','".addslashes($_POST[answer])."',".
                "'".addslashes($_POST[title])."','".addslashes($_POST[description])."','".addslashes($_POST[keywords])."')");

    case "change_question" :
        if ($_REQUEST[id] && $_POST[question] && $_POST[answer])
            sql_query("UPDATE admin_faq SET question='".addslashes($_POST[question])."',answer='". addslashes($_POST[answer]) .
                "',title='". addslashes($_POST[title]) ."',description='". addslashes($_POST[description]) ."',keywords='". addslashes($_POST[keywords]) .
                "' WHERE id='".intval($_REQUEST[id])."'");

    case "del_question" :
        if ($_REQUEST[id] && !$_POST[question] && !$_POST[answer] && !$_POST[name])
            sql_query("DELETE FROM admin_faq WHERE id='".intval($_REQUEST[id])."'");

    case "choose_cat" :
    case "ch_cat" :
    case "ch_question" :
        if ($_REQUEST[cid])
            $get_questions = sql_query("SELECT * FROM admin_faq WHERE cat='".intval($_REQUEST[cid])."' ORDER BY question");
        break;
}
?>
<html>
<head>
<title>FAQ-Admin</title>
<style type="text/css">
<!--
body,table,tr,td {
    font-family: Verdana;
    font-size: 10px;
    color: #000000;
}

a {
    font-family: Verdana;
    font-size: 10px;
    color: #0000FF;
    text-decoration: underline;
}

input {
    font-family: Verdana;
    font-size: 10px;
    color: #000000;
    border: 1px solid #000000;
    background-color: #FFFFFF;
}

textarea {
    font-family: Verdana;
    font-size: 10px;
    color: #000000;
    background-color: #FFFFFF;
    width: 500px;
    height: 300px;
    overflow: auto;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="<?php echo $etsAddress ?>/javascript/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
<!--
tinyMCE.init({
        theme : "advanced",
        mode : "textareas",
        plugins : "inlinepopups",
//        theme_advanced_buttons1 : "bold,italic,underline,undo,redo,link,unlink,image,forecolor,formatselect,removeformat,cleanup,code",
//        theme_advanced_buttons2 : "",
//        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "bottom",
        theme_advanced_toolbar_align : "center",
//        theme_advanced_blockformats : "p,blockquote",
//        content_css : "css/bbcode.css",
        entity_encoding : "raw",
        add_unload_trigger : false,
        remove_linebreaks : false,
        inline_styles : false,
        convert_fonts_to_spans : false
});
// -->
</script>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>Kategorie</td>
        <td>Aktion</td>
    </tr>
    <?
    $get_cats = sql_query("SELECT * FROM admin_faq_cat ORDER BY name");
    while ($cats = sql_fetch_array($get_cats)) {
        if ($cats[id] == $_REQUEST[sel_cid]) {
            echo '<form action="'.$_SERVER['PHP_SELF'] .'" method="post">
            <tr>
                <td><input type="text" name="name" value="'.$cats[name].'" size="70" maxlength="50"></td>
                <td><input type="submit" value="Speichern">
                    <input type="hidden" name="action" value="change_cat">
                    <input type="hidden" name="id" value="'.$cats[id].'">
                    <input type="hidden" name="cid" value="'.$cats[id].'"></td>
            </tr>
            </form>';
        }
        else {
            echo '<tr>
                <td><a href="'.$_SERVER['PHP_SELF'] .'?action=choose_cat&cid='.$cats[id].'">'.$cats[name].'</a></td>
                <td><a href="'.$_SERVER['PHP_SELF'] .'?action=ch_cat&cid='.$cats[id].'&sel_cid='.$cats[id].'">ändern</a>
                    <a href="'.$_SERVER['PHP_SELF'] .'?action=del_cat&cid='.$cats[id].'">löschen</a></td>
            </tr>';
        }
    }

    echo '	<form action="'.$_SERVER['PHP_SELF'] .'" method="post">
            <tr>
                <td><input type="text" name="cat" size="70" maxlength="50"></td>
                <td><input type="submit" value="Hinzufügen">
                    <input type="hidden" name="action" value="add_cat"></td>
            </tr>
            </form>
            </table><br><br><br>';

    if ($_REQUEST[cid])
    {
        echo '	<table border="0" cellpadding="0" cellspacing="0">';

        $get_questions = sql_query("SELECT * FROM admin_faq WHERE cat='".intval($_REQUEST[cid])."' ORDER BY question");
        while ($questions = sql_fetch_array($get_questions)) {
            if ($questions[id] == $_REQUEST[sel_id]) {
                echo '<form action="'.$_SERVER['PHP_SELF'] .'" method="post">
                    <tr>
                        <td>Frage: <input type="text" size="70" name="question" value="'.$questions[question].'" /><br />
                            Meta-Titel: <input type="text" size="70" name="title" value="'.$questions[title].'" /><br />
                            Meta-Desc.: <input type="text" size="70" name="description" value="'.$questions[description].'" /><br />
                            Meta-Keyw.: <input type="text" size="70" name="keywords" value="'.$questions[keywords].'" /><br />
                        </td>
                        <td><input type="submit" value="Speichern">
                            <input type="hidden" name="action" value="change_question">
                            <input type="hidden" name="cid" value="'.$_REQUEST[cid].'">
                            <input type="hidden" name="id" value="'.$questions[id].'"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><textarea id="answer1" name="answer">'. stripslashes(str_replace('<br>',"\n",$questions[answer])) .'</textarea></td>
                    </tr>
                    </form>';
            }
            else {
                echo '<tr>
                        <td><b>'.$questions[question].'</b></td>
                        <td><a href="'.$_SERVER['PHP_SELF'] .'?action=ch_question&cid='.$_REQUEST[cid].'&sel_id='.$questions[id].'">ändern</a>
                            <a href="'.$_SERVER['PHP_SELF'] .'?action=del_question&cid='.$_REQUEST[cid].'&id='.$questions[id].'">löschen</a></td>
                    </tr>
                    <tr>
                        <td colspan="2">'. htmlspecialchars($questions[answer]) .'<br><br></td>
                    </tr>
                    </form>';
            }
        }

        if (!$_REQUEST[sel_id]) {
            echo '<form action="'.$_SERVER['PHP_SELF'] .'" method="post">
                <tr>
                    <td colspan="2"><br></td>
                <tr>
                    <td>Frage: <input type="text" size="70" name="question" /><br />
                        Meta-Titel: <input type="text" size="70" name="title" /><br />
                        Meta-Desc.: <input type="text" size="70" name="description" /><br />
                        Meta-Keyw.: <input type="text" size="70" name="keywords" /><br />
                    </td>
                    <td><input type="submit" value="Hinzufügen">
                        <input type="hidden" name="action" value="add_question">
                        <input type="hidden" name="cid" value="'.$_REQUEST[cid].'"></td>
                </tr>
                <tr>
                    <td colspan="2"><textarea id="answer2" name="answer"></textarea></td>
                </tr>
                </form>';
        }
    }

    ?>
</table>
</body>
</html>
