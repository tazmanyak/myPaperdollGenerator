<?php

require("myrunuo.inc.php");

check_get($id, "id");
$id = intval($id);

check_get($nc, "nc");
$nc = intval($nc);

check_get($guild, "g");
$guild = htmlspecialchars($guild);

$link = sql_connect();

// Skills timestamp
$result = sql_query($link, "SELECT time_datetime FROM myrunuo_timestamps WHERE time_type='Skills'");
if (!(list($timestamp) = mysql_fetch_row($result)))
  $timestamp = "";
mysql_free_result($result);

echo <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
  <title>$guild Webpage</title>
  <meta http-equiv="Content-Type" content="text/html; CHARSET=iso-8859-1">
</head>

<body bgcolor="#ffffff" text="#000000">

<table cellspacing="0" cellpadding="0" width="480" border="0">
  <tbody>
    <tr> 
      <td bgcolor="#32605e" colspan="2">
        <font face="Verdana" size="-1" color="#ffffff"><b>Overall Skill Averages for $guild</b></font>
      </td>
    </tr>
    <tr>
      <td colspan="2"><font size="1pt">&nbsp;</font></td>
    </tr>
    <tr>
      <td valign="top">
        <table cellspacing="0" cellpadding="0" border="0">
          <tbody>

EOF;

$result = sql_query($link, "SELECT skill_id,SUM(skill_value) AS totalskill_value
                    FROM myrunuo_characters LEFT JOIN myrunuo_characters_skills ON myrunuo_characters.char_id=myrunuo_characters_skills.char_id
                    WHERE char_guild=$id GROUP BY skill_id"); // AND char_public=1

$sid = -1;
for ($l = 0; $l < 2; $l++) {
  for ($i = 0 + ($l * 26); $i <= 25 + ($l * 26); $i++) {
    // Fix for swapped skill numbers
    if ($i == 47)
      $s = 48;
    else if ($i == 48)
      $s = 47;
    else
      $s = $i;

    echo <<<EOF
            <tr> 
              <td>
                <font face="Verdana" size="-1"><a href="http://guide.uo.com/skill_$s.html">$skillnames[$i]</a></font>
              </td>
              <td align="right">
                <font face="Verdana" size="-1">&nbsp;&nbsp;

EOF;

    if ($sid < $i) {
      if ($row = mysql_fetch_row($result)) {
        $sid = intval($row[0]);
        $val = sprintf("%0.1f", $row[1] / $nc / 10);
      }
      else
        $sid = 99;
    }
    if ($i == $sid)
      echo "$val";
    else
      echo "0";

    echo <<<EOF
                </font>
              </td>
            </tr>

EOF;
  }

  if (!$l) {
    echo <<<EOF
          </tbody>
        </table>
      </td>
      <td valign="top">
        <table cellspacing="0" cellpadding="0" border="0">
          <tbody>

EOF;
  }
}

mysql_free_result($result);
mysql_close($link);

if ($timestamp != "")
  $dt = date("F j, Y, g:i a", strtotime($timestamp));
else
  $dt = date("F j, Y, g:i a");

echo <<<EOF
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2"><font size="1pt">&nbsp;</font></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#32605e" colspan="2">
        <font face="Verdana" color="#ffffff" size="-1">&nbsp;&nbsp;<b>Last Updated:</b> $dt</font>
      </td>
    </tr>
  </tbody>
</table>

</body>
</html>

EOF;

?>