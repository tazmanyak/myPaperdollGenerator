<?php

require("myrunuo.inc.php");

function skillimage($skillid, $skill)
{
  if ($skill / 10 >= 100)
    $temp = "g";
  else
    $temp = "";
  $skillimage = "images/skills/{$skillid}{$temp}.gif";

  return $skillimage;
}

function skillname($skillid, $skill)
{
  global $skillnames;

  if ($skill / 10 >= 100)
    $temp = "Grandmaster:<br>";
  else
    $temp = "";
  $skillname = $temp . $skillnames[$skillid];

  return $skillname;
}

check_get($id, "id");
$id = intval($id);

echo <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
  <title>View Player</title>
  <meta http-equiv="Content-Type" content="text/html; CHARSET=iso-8859-1">
</head>

<body bgcolor="#ffffff" text="#000000" marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

EOF;

$link = sql_connect();

if ($id) {
  $result = sql_query($link, "SELECT char_name,char_nototitle FROM myrunuo_characters WHERE char_id=$id");
  if (!(list($charname,$chartitle) = mysql_fetch_row($result))) {
    echo "Invalid character ID!\n";
    die();
  }
  mysql_free_result($result);

  echo <<<EOF
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3" align="center" valign="top">
      <img src="paperdoll.php?id=$id" width="262" height="323">
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <b>$chartitle</b>
    </td>
  </tr>
  <tr>

EOF;

  $result = sql_query($link, "SELECT skill_id,skill_value FROM myrunuo_characters_skills WHERE char_id=$id ORDER BY skill_value DESC LIMIT 3");
  $num = 0;
  while (list($skillid,$skill) = mysql_fetch_row($result)) {
    $skillid = intval($skillid);
    $skill = intval($skill);
    $name = skillname($skillid, $skill);

    // Fix for swapped skill IDs
    if ($skillid == 47)
      $skillid = 48;
    else if ($skillid == 48)
      $skillid = 47;

    $image = skillimage($skillid, $skill);

    echo <<<EOF
    <td align="center" valign="top">
      <a href="http://guide.uo.com/skill_{$skillid}.html"><img src="$image" border="0" width="125" height="80" alt="$name"></a><br>
      <font face="Arial" size="2">$name</font>
    </td>

EOF;
    $num++;
  }
  mysql_free_result($result);

  while ($num < 3) {
    echo "    <td>&nbsp;</td>\n";
    $num++;
  }

  echo "  </tr>\n";

 $result = sql_query($link, "SELECT myrunuo_guilds.guild_id,myrunuo_guilds.guild_name FROM myrunuo_characters INNER JOIN myrunuo_guilds ON myrunuo_characters.char_guild=myrunuo_guilds.guild_id WHERE myrunuo_characters.char_id=$id");
  if (list($gid,$guild) = mysql_fetch_row($result)) {
    $gid = intval($gid);
    echo <<<EOF
  <tr>
    <td align="center" colspan="3">
      <br><font face="Verdana, Arial" color="#000000" size="2"><b>Guild:</b> &nbsp; &nbsp;<a href="guild.php?id=$gid" style="color: Black">$guild</a></font>
    </td>
  </tr>

EOF;
  }
  mysql_free_result($result);
}

mysql_close($link);

echo <<<EOF
</table>

</body>
</html>

EOF;

?>