<?php

require("myrunuo.inc.php");

check_get($id, "id");
$id = intval($id);

$link = sql_connect();

// Get guild data
$result = sql_query($link, "SELECT * FROM myrunuo_guilds WHERE guild_id=$id LIMIT 1");
if ($row = mysql_fetch_array($result)) {
  mysql_free_result($result);
  while (list($key, $val) = each($row))
    ${$key} = $val;

  $guild_wars = intval($guild_wars);
  $guild_members = intval($guild_members);
  $guild_master = intval($guild_master);

  $capguild = strtolower(substr($guild_name, 0, 1)).".gif";
  $full_name = $guild_name;
  $guild_name = substr($guild_name, 1);

  if ($guild_website != "") {
    if (strncasecmp($guild_name, "http://", 7))
      $guild_website = "http://".$guild_website;
    $guild_website = "<a href=\"$guild_website\" target=\"_blank\">$guild_website</a>";
  }
  else
    $guild_website = "None";

  if ($guild_charter == "")
    $guild_charter = "No charter has been specified.";
}
else {
  echo "Invalid guild ID.<br>\n";
  mysql_close($link);
  die();
}

$result = sql_query($link, "SELECT char_name FROM myrunuo_characters WHERE char_id=$guild_master LIMIT 1");
if (!(list($master_name) = mysql_fetch_row($result)))
  $master_name = "None";
mysql_free_result($result);

// Guild timestamp
$result = sql_query($link, "SELECT time_datetime FROM myrunuo_timestamps WHERE time_type='Guilds'");
if (!(list($timestamp) = mysql_fetch_row($result)))
  $timestamp = "";
mysql_free_result($result);

echo <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
  <title>$full_name Webpage</title>
  <meta http-equiv="Content-Type" content="text/html; CHARSET=iso-8859-1">
  <script language="JavaScript">
    nav1on = new Image(63, 214);
    nav1on.src = "images/items/av_on.gif";

    nav1off = new Image(63, 214);
    nav1off.src = "images/items/av_off.gif";

    function img_act(imgName) {
      imgOn = eval(imgName + "on.src");
      document [imgName].src = imgOn;
    }

    function img_inact(imgName) {
      imgOff = eval(imgName + "off.src");
      document [imgName].src = imgOff;
    }
  </script>
</head>

<body bgcolor="#ffffff" text="#000000">

<table border="0" cellpadding="0" cellspacing="0">
  <tr valign="middle"> 
    <td colspan="2" align="left">
      <img src="images/caps/$capguild" width="40" height="60" border="0" align="absmiddle">$guild_name
    </td>
  </tr>
  <tr> 
    <td align="left" width="135">
      <strong><font face="Verdana" size="2">Abbreviation:</font></strong>
    </td>
    <td>
      <font face="Verdana" size="-1">$guild_abbreviation</font>
    </td>
  </tr>
  <tr> 
    <td>
      <strong><font face="Verdana" size="2">Guild Type:</font></strong>
    </td>
    <td>
      <font face="Verdana" size="-1">$guild_type</font>
    </td>
  </tr>
  <tr> 
    <td>
      <strong><font face="Verdana" size="2">Website:</font></strong>
    </td>
    <td>
      <font face="Verdana" size="-1">$guild_website</font>
    </td>
  </tr>
  <tr> 
    <td>
      <strong><font face="Verdana" size="2">Master/Mistress:</font></strong></td>
    <td>
      <font face="Verdana" size="-1"><a href="player.php?id=$guild_master">$master_name</a></font>
    </td>
  </tr>
  <tr> 
    <td>
      <strong><font face="Verdana" size="2">Total Members:</font></strong>
    </td>
    <td>
      <font face="Verdana" size="-1">$guild_members</font>
    </td>
  </tr>
  <tr> 
    <td>
      <strong><font face="Verdana" size="2">Total Enemies:</font></strong>
    </td>
    <td>
      <font face="Verdana" size="-1">$guild_wars</font>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <font face="Verdana" size="2"><strong>Charter:</strong></font>
    </td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td>
      <font face="Verdana" size="-1">$guild_charter</font>
    </td>
  </tr>
  <tr>
    <td>
      <br>
      <a OnMouseOver="img_act('nav1')" OnMouseOut="img_inact('nav1')" href="skills.php?id=$id&nc=$guild_members&g=$full_name">
      <img height="63" alt="Skill Averages" src="images/items/av_off.gif" width="214" border="0" name="nav1"></a>
    </td>
  </tr>
</table>
<br>
<table width="640" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td bgcolor="#32605e">
      <font face="Verdana" color="#ffffff" size="-1"><b>&nbsp;&nbsp;Members&nbsp;&nbsp;</b></font>
    </td>
    <td bgcolor="#32605e">
      <font face="Verdana" color="#ffffff" size="-1"><b>&nbsp;&nbsp;Recently at war with&nbsp;&nbsp;</b></font>
    </td>
  </tr>
  <tr>
    <td colspan="2"><font size="1pt">&nbsp;</font></td>
  </tr>
  <tr>
    <td>

EOF;

// Guild Members
$result = sql_query($link, "SELECT char_id,char_name,char_nototitle,char_guildtitle,char_public FROM myrunuo_characters WHERE char_guild=$id");
if (mysql_numrows($result)) {
  while ($row = mysql_fetch_row($result)) {
    $charid = intval($row[0]);
    $charname = $row[1];
    $chartitle = $row[2];
    $charguildtitle = $row[3];
    $charpublic = intval($row[4]);

    if (strcasecmp($charguildtitle, "NULL"))
      $charguildtitle = " [$charguildtitle]";
    else
      $charguildtitle = "";

    $cma = strpos($chartitle, ",");
    $namedisplay = substr($chartitle, 0, $cma);
    $chartitle = substr($chartitle, $cma);

    echo "      <a href=\"player.php?id=$charid\">$namedisplay</a>$chartitle $charguildtitle<br>\n";
  }
}

echo <<<EOF
    </td>
    <td valign="top">

EOF;

// Guild Wars 1
$result = sql_query($link, "SELECT guild_name,guild_2 FROM myrunuo_guilds_wars INNER JOIN myrunuo_guilds ON guild_2=guild_id WHERE guild_1=$id");
$num1 = mysql_numrows($result);
if ($num1) {
  while ($row = mysql_fetch_row($result)) {
    $war_name = $row[0];
    $war_id = intval($row[1]);
    echo "      <font face=\"Verdana\" size=\"-1\"><a href=\"guild.php?id=$war_id\">$war_name</a></font><br>\n";
  }
}

// Guild Wars 2
$result = sql_query($link, "SELECT guild_name,guild_1 FROM myrunuo_guilds_wars INNER JOIN myrunuo_guilds ON guild_1=guild_id WHERE guild_2=$id");
$num2 = mysql_numrows($result);
if ($num2) {
  while ($row = mysql_fetch_row($result)) {
    $war_name = $row[0];
    $war_id = intval($row[1]);
    echo "      <font face=\"Verdana\" size=\"-1\"><a href=\"guild.php?id=$war_id\">$war_name</a></font><br>\n";
  }
}

if (!$num1 && !$num2)
  echo "      <font face=\"Verdana\" size=\"-1\">None</font>\n";

mysql_close($link);

if ($timestamp != "")
  $dt = date("F j, Y, g:i a", strtotime($timestamp));
else
  $dt = date("F j, Y, g:i a");

echo <<<EOF
  </tr>
  <tr>
    <td colspan="2"><font size="1pt">&nbsp;</font></td>
  </tr>
  <tr>
    <td align="left" bgcolor="#32605e" colspan="2">
      <font face="Verdana" color="#ffffff" size="-1">&nbsp;&nbsp;<b>Last Updated:</b> $dt</font>
    </td>
  </tr>
</table>

</body>
</html>

EOF;

?>