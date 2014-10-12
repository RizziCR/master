<?php
  $use_lib = 19; // MSG_ADRESSBOOK

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");


  echo "  <html>
      <head>
      <META HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=iso-8859-1\">
      <META HTTP-EQUIV=\"expires\" CONTENT=\"0\">
      <META HTTP-EQUIV=\"Cache-Control\" CONTENT=\"no-cache\">
      <META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">

      <title>{$MESSAGES[MSG_ADRESSBOOK][m000]}</title>

      <link rel=stylesheet type=text/css href=\"css/css.css\">

      <script language=javascript>
      function set_checkboxes(check)
      {
          var boxes = document.formular.elements['id[]'];
          var set_boxes  = boxes.length;

        if (set_boxes)
          for (i=0;i<set_boxes;i++)
            boxes[i].checked = check;
        else
          boxes.checked = check;

          return true;
      }
      function open_url(w, url) {
          w.location.href=url;
	  w.focus();
	  return false;
      }
      </script>

      </head>
      <body bgcolor=\"#000000\">
      <h1>{$MESSAGES[MSG_ADRESSBOOK][m000]}</h1>";

  if ($_SESSION[sitt_login])
    ErrorMessage(MSG_GENERAL,e000);  // Die Funktion ist für Sitter gesperrt

  if (ErrorMessage(0))
  {
    echo ErrorMessage();
    echo "                </td>
            </tr>
        </table>
    </body>
</html>";
    die();
  }

  switch ($_POST[submit])
  {
    case $MESSAGES[MSG_ADRESSBOOK][m005] : // User hinzufügen
      $get_existence = sql_query("SELECT ID FROM usarios WHERE user='". htmlspecialchars($_POST[new_contact],ENT_QUOTES) ."'");
      if (!sql_num_rows($get_existence))
        ErrorMessage(MSG_ADRESSBOOK,e000);  // Diesen User gibt es nicht
      $existence = sql_fetch_array($get_existence);

      if ($_POST[group] != "nogrp")
      {
        $get_group = sql_query("SELECT null FROM adressbook_groups WHERE user='$_SESSION[sitter]' && id='". htmlspecialchars($_POST[group],ENT_QUOTES) ."'");
        if (!sql_num_rows($get_group))
          ErrorMessage(MSG_ADRESSBOOK,e003);  // Diese Gruppe existiert nicht
      }
      else
        $_POST[group] = 0;

      $get_inlist = sql_query("SELECT null FROM adressbook WHERE user='$_SESSION[sitter]' && gid='". htmlspecialchars($_POST[group],ENT_QUOTES) ."' && contact='$existence[ID]'");
      if (sql_num_rows($get_inlist))
        ErrorMessage(MSG_ADRESSBOOK,e001);  // Es ist bereits ein Eintrag in dieser Gruppe vorhanden

      if (ErrorMessage(0))
      {
        echo ErrorMessage();

        echo "<br><a href=\"".$_SERVER['PHP_SELF']."\">{$MESSAGES[MSG_ADRESSBOOK][m006]}</a>";

    echo "                </td>
            </tr>
        </table>
    </body>
</html>";

        die();
      }

      sql_query("INSERT INTO adressbook (user,contact,gid) VALUES ('$_SESSION[sitter]','$existence[ID]','". htmlspecialchars($_POST[group],ENT_QUOTES) ."')");

      break;


    case $MESSAGES[MSG_ADRESSBOOK][m009] : // User löschen
      sql_query("DELETE FROM adressbook WHERE user='$_SESSION[sitter]' && id='". htmlspecialchars($_POST[id],ENT_QUOTES) ."'");

      break;
    case $MESSAGES[MSG_ADRESSBOOK][m004] : // Gruppe hinzufügen
      $get_inlist = sql_query("SELECT null FROM adressbook_groups WHERE user='$_SESSION[sitter]' && name='". htmlspecialchars($_POST[new_group],ENT_QUOTES) ."'");
      if (sql_num_rows($get_inlist))
        ErrorMessage(MSG_ADRESSBOOK,e002);  // Diese Gruppe existiert bereits

      if (ErrorMessage(0))
      {
        echo ErrorMessage();

        echo "<br><a href=\"".$_SERVER['PHP_SELF']."\">{$MESSAGES[MSG_ADRESSBOOK][m006]}</a>";

    echo "                </td>
            </tr>
        </table>
    </body>
</html>";

        die();
      }

      sql_query("INSERT INTO adressbook_groups (user,name) VALUES ('$_SESSION[sitter]','". htmlspecialchars($_POST[new_group],ENT_QUOTES) ."')");

      break;

    case $MESSAGES[MSG_ADRESSBOOK][m008] : // Gruppe löschen
      sql_query("DELETE FROM adressbook_groups WHERE user='$_SESSION[sitter]' && id='". htmlspecialchars($_POST[id],ENT_QUOTES) ."'");
      sql_query("UPDATE adressbook SET gid='0' WHERE user='$_SESSION[sitter]' && gid='". htmlspecialchars($_POST[id],ENT_QUOTES) ."'");
      $get_doubled_user = sql_query("SELECT contact FROM adressbook WHERE user='$_SESSION[sitter]' && gid=0 GROUP BY contact HAVING Count(*) > 1");
      $doubled_user = sql_fetch_array($get_doubled_user);
      sql_query("DELETE FROM adressbook WHERE user='$_SESSION[sitter]' && contact='$doubled_user[contact]' LIMIT 1");

      break;
  }

  if ($del)
    sql_query("DELETE FROM clist WHERE user='$_SESSION[sitter]' && contact='$ctc'");


  echo "  <table width=243 border=0 cellpadding=2 cellspacing=0>
      <tr valign=top>
        <td align=center class=table_head>
          {$MESSAGES[MSG_ADRESSBOOK][m007]}
        </td>
      </tr>
      <tr>
        <td>
          <table width=239 border=0 cellpadding=0 cellspacing=0>";

  $get_groups = sql_query("SELECT id,name FROM adressbook_groups WHERE user='$_SESSION[user]' ORDER BY name");
  if (sql_num_rows($get_groups))
  {
    while ($groups = sql_fetch_array($get_groups))
    {
      $g_select .= "<option value=$groups[id]>$groups[name]</option>\n";
      $g_list[0] = $MESSAGES[MSG_ADRESSBOOK][m010];
      $g_list[$groups[id]] = $groups[name];

      if ($i%2)
        $color = "#222222";
      else
        $color = "#000000";

      echo "  <tr bgcolor=\"$color\">
            <td>
              $groups[name]
            </td>
            <form action=\"".$_SERVER['PHP_SELF']."\" method=POST>
            <td align=right>
              <input type=hidden name=id value=$groups[id]>
              <input type=submit name=submit value=\"{$MESSAGES[MSG_ADRESSBOOK][m008]}\" class=button>
            </td>
            </form>
          </tr>";
    }
  }
  else
    echo $MESSAGES[MSG_ADRESSBOOK][m002];

  echo "      </table>
        </td>
      </tr>
      <form action=\"".$_SERVER['PHP_SELF']."\" method=POST>
      <tr>
        <td align=center>
          <input type=text name=new_group class=button>
          <input type=submit name=submit value=\"{$MESSAGES[MSG_ADRESSBOOK][m004]}\" class=button>
        </td>
      </tr>
      </form>
      <tr>
        <td>
          <br><br>
        </td>
      </tr>
      <tr valign=top>
        <td align=center class=table_head>
          {$MESSAGES[MSG_ADRESSBOOK][m001]}
        </td>
      </tr>
      <tr>
        <td>
          <table width=239 border=0 cellpadding=0 cellspacing=0>";

  $get_list = sql_query("SELECT adressbook.id, userdata.user AS contact, adressbook.gid FROM adressbook INNER JOIN userdata ON userdata.ID=adressbook.contact WHERE adressbook.user='$_SESSION[sitter]' ORDER BY adressbook.gid,userdata.user");
  if (sql_num_rows($get_list))
  {
    while ($list = sql_fetch_array($get_list))
    {
      if ($show_group != $list[gid])
        echo "  <tr><td><br></td></tr>
            <tr>
              <td>
                <b>{$g_list[$list[gid]]}</b>
              </td>
            </tr>";
      $show_group = $list[gid];

      if ($i%2)
        $color = "#222222";
      else
        $color = "#000000";

      echo "  <tr bgcolor=\"$color\">
            <td>
              <a onclick=\"open_url(window.opener.opener,'./information.php?type=u&name=$list[contact]');\">$list[contact]</a>
            </td>
            <form action=\"".$_SERVER['PHP_SELF']."\" method=POST>
            <td align=right>
              <input type=hidden name=id value=$list[id]>
              <input type=submit name=submit value=\"{$MESSAGES[MSG_ADRESSBOOK][m009]}\" class=button>
            </td>
            </form>
          </tr>";
    }
  }
  else
    echo $MESSAGES[MSG_ADRESSBOOK][m002];

  echo "      </table>
        </td>
      </tr>
      <tr>
        <td>
          <br><br>
        </td>
      </tr>
      <tr valign=top>
        <td align=center class=table_head>
          {$MESSAGES[MSG_ADRESSBOOK][m003]}
        </td>
      </tr>
      <form action=\"".$_SERVER['PHP_SELF']."\" method=POST>
      <tr>
        <td align=center>
          <input type=text name=new_contact value=\"$_GET[nc]\" class=button><br>
          <select name=group class=button>
            <option value=nogrp>keine Gruppe</option>
            $g_select
          </select><br>
          <input type=submit name=submit value=\"{$MESSAGES[MSG_ADRESSBOOK][m005]}\" class=button>
        </td>
      </tr>
      </form>
      </table>";


  echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=GET>
          <tr>
            <td align=center>
              <br>
              <input type=hidden name=action value=contacts>
            </td>
          </tr>
          </form>
          </table>
        </td>
      </tr>
      </table>";
?>
