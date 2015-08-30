<?php

require("myrunuo.inc.php");

// Guild page / war page to display
check_get($gp, "gp");
$gp = intval($gp);
check_get($wp, "wp");
$wp = intval($wp);

check_get($sortby, "sortby");
if ($sortby == "" || $sortby == "guild_name")
  $sort1 = "myrunuo_guilds.guild_name";
else
  $sort1 = $sortby." DESC";

check_get($sortby1, "sortby1");
if ($sortby1 == "" || $sortby1 == "guild_name")
  $sort2 = "myrunuo_guilds.guild_name";
else
  $sort2 = $sortby1." DESC";

$link = sql_connect();

// Total guilds count
$result = sql_query($link, "SELECT COUNT(*) FROM myrunuo_guilds");
list($totalguilds) = mysql_fetch_row($result);
$totalguilds = intval($totalguilds);
mysql_free_result($result);

// Total guilds at war
$result = sql_query($link, "SELECT DISTINCT count(*) FROM myrunuo_guilds_wars GROUP BY guild_1");
list($totalwar) = mysql_fetch_row($result);
$totalwar = intval($totalwar);
mysql_free_result($result);

// Chaos guilds total count
$result = sql_query($link, "SELECT COUNT(*) FROM myrunuo_guilds WHERE guild_type='Chaos'");
list($chaosguilds) = mysql_fetch_row($result);
$chaosguilds = intval($chaosguilds);
mysql_free_result($result);

// Order guilds total count
$result = sql_query($link, "SELECT COUNT(*) FROM myrunuo_guilds WHERE guild_type='Order'");
list($orderguilds) = mysql_fetch_row($result);
$orderguilds = intval($orderguilds);
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
  <title>Shard Guilds</title>
  <meta http-equiv="Content-Type" content="text/html; CHARSET=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<font face="Verdana, Comic Sans MS" size="4"><strong>SHARD GUILDS</strong></font>
<br>
<table cellspacing="0" cellpadding="0" width="580" border="0">
  <tbody>
    <tr>
      <td align="left" valign="top">
        <font face="Verdana" size="-1">
        <img height="60" src="images/caps/t.gif" width="40" align="left" border="0"> his page shows the overall top guilds in mutually declared wars and Membership.<br><br><br><br><br>
        </font>
      </td>
    </tr>
    <tr>
      <td align="left" bgcolor="#32605e">
        <font face="Verdana" color="#ffffff" size="-1">
        &nbsp;&nbsp;<b>Total Guilds:</b> $totalguilds<b>&nbsp;&nbsp;Total Chaos Guilds:</b> $chaosguilds &nbsp;&nbsp;<b>Total Order Guilds:</b> $orderguilds
        </font>
      </td>
    </tr>
    <tr> 
      <td>
        <table cellpadding="2" width="580" border="0">
          <tbody>
            <tr> 
              <td colspan="3">

EOF;

if ($gp - $guilds_perpage >= 0) {
  $num = $gp - $guilds_perpage;
  echo "        <a href=\"guilds.php?gp=$num&wp=$wp&sortby=$sortby&sortby1=$sortby1\"><img src=\"images/items/back.jpg\" border=\"0\"></a>\n";
}
else
  echo "        &nbsp; &nbsp;";

$page = intval($gp / $guilds_perpage) + 1;
$pages = ceil($totalguilds / $guilds_perpage);
if ($pages > 1)
  echo " <font size=\"-1\" face=\"Verdana\">Page [$page/$pages]</font> ";

if ($gp + $guilds_perpage < $totalguilds) {
  $num = $gp + $guilds_perpage;
  echo "        <a href=\"guilds.php?gp=$num&wp=$wp&sortby=$sortby&sortby1=$sortby1\"><img src=\"images/items/next.jpg\" border=\"0\"></a>\n";
}

echo <<<EOF
              </td>
            </tr>
            <tr> 
              <td align="left" colspan="3">
                <img height="25" src="images/items/vetmem.gif" width="243" border="0">
              </td>
            </tr>
            <tr> 
              <td width="10">&nbsp;</td>
              <td>
                <font face="Verdana" size="2">
                <a href="guilds.php?sortby=guild_name&sortby1=$sortby1" style="color: black"><strong>Guild Name</strong></a>
                </font>
              </td>
              <td align="right" width="50">
                <font face="Verdana" size="2">
                <a href="guilds.php?sortby=countofchar_guild&sortby1=$sortby1" style="color: black"><strong>Members</strong></a>
                </font>
              </td>
              <td align="right" width="50">
                <font face="Verdana" size="2">
                <a href="guilds.php?sortby=countofchar_counts&sortby1=$sortby1" style="color: black"><strong>Kills</strong></a>
                </font>
              </td>
            </tr>

EOF;

// Guilds / members
$result = sql_query($link, "SELECT guild_id,guild_name,COUNT(char_guild) AS countofchar_guild,SUM(char_counts) AS countofchar_counts
                    FROM myrunuo_guilds INNER JOIN myrunuo_characters ON guild_id=char_guild
                    GROUP BY guild_name ORDER by $sort1 LIMIT $gp,$guilds_perpage");
if (mysql_numrows($result)) {
  $num = $gp * $guilds_perpage + 1;
  while ($row = mysql_fetch_row($result)) {
    $guildid = intval($row[0]);
    $name = $row[1];
    $members = intval($row[2]);
    $kills = intval($row[3]);
    echo <<<EOF
            <tr> 
              <td align="right" width="10">
                <font face="Verdana" size="-1">$num</font></td>
              <td align="left" width="470">
                <font face="Verdana" size="-1"><a href="guild.php?id=$guildid">$name</a></font>
              </td>
              <td align="right" width="50">
                <font face="Verdana" size="-1">$members</font>
              </td>
              <td align="right" width="50">
                <font face="Verdana" size="-1">$kills</font>
              </td>
            </tr>

EOF;
    $num++;
  }
}
else {
  echo <<<EOF
            <tr> 
              <td colspan="3">
                <font face="Verdana" size="-1">No matching guilds found.</font>
              </td>
            </tr>

EOF;
}

echo <<<EOF
          </tbody>
        </table>
      </td>
    </tr>
    <tr> 
      <td>
        <table cellpadding="2" width="580" border="0">
          <tbody>
            <tr> 
              <td colspan="3">

EOF;

if ($wp - $guilds_perpage > 0) {
  $num = $wp - $guilds_perpage;
  echo "                <a href=\"guilds.php?wp=$num&gp=$gp&sortby=$sortby&sortby1=$sortby1\"><img src=\"images/items/back.jpg\" border=\"0\"></a>\n";
}
else
  echo "                &nbsp;&nbsp;";

$page = intval($wp / $guilds_perpage) + 1;
$pages = ceil($totalwar / $guilds_perpage);
if ($pages > 1)
  echo " <font size=\"-1\" face=\"Verdana\">Page [$page/$pages]</font> ";

if ($wp + $guilds_perpage < $totalwar) {
  $num = $wp + $guilds_perpage;
  echo "                <a href=\"guilds.php?wp=$num&gp=$gp&sortby=$sortby&sortby1=$sortby1\"><img src=\"images/items/next.jpg\" border=\"0\"></a>\n";
}

echo <<<EOF
              </td>
            </tr>
            <tr> 
              <td align="left" colspan="4">
                <img height="25" src="images/items/warfare.gif" width="243" border="0">
              </td>
            </tr>
            <tr> 
              <td width="10">&nbsp;</td>
              <td width="520">
                <font face="Verdana" size="-1">
                <a href="guilds.php?sortby1=guild_name&sortby=$sortby" style="color: black"><strong>Guild Name</strong></a></font>
              </td>
              <td align="right" width="50">
                <font face="Verdana" size="-1"><a href="guilds.php?sortby1=countofguild_1&sortby=$sortby" style="color: black"><strong>Enemies</strong></a></font>
              </td>
            </tr>

EOF;

// War guilds / enemies
$result = sql_query($link, "SELECT guild_id,guild_name,COUNT(guild_1) AS countofguild_1
                    FROM myrunuo_guilds INNER JOIN myrunuo_guilds_wars ON guild_id=guild_1 OR guild_id=guild_2
                    GROUP BY guild_id,guild_name ORDER by $sort2 LIMIT $wp,$guilds_perpage");
$num = mysql_numrows($result);

if ($num) {
  $num = $wp * $guilds_perpage + 1;
  while ($row = mysql_fetch_row($result)) {
    $guildid = intval($row[0]);
    $name = $row[1];
    $enemies = intval($row[2]);

    echo <<<EOF
            <tr> 
              <td align="right" width="10">
                <font face="Verdana" size="-1">$num</font>
              </td>
              <td align="left" width="520">
                <font face="Verdana" size="-1"><a href="guild.php?id=$guildid">$name</a></font>
              </td>
              <td align="right" width="50">
                <font face="Verdana" size="-1">$enemies</font>
              </td>
            </tr>

EOF;
    $num++;
  }
}
else {
  echo <<<EOF
           <tr> 
              <td colspan="4"><font face="Verdana" size="-1">No matching guilds found.</font></td>
            </tr>

EOF;
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
      <td colspan="4"><br></td>
    </tr>
    <tr>
      <td align="left" bgcolor="#32605e" colspan="4">
        <font face="Verdana" color="#ffffff" size="-1">&nbsp;&nbsp;<b>Last Updated:</b> $dt</font>
      </td>
    </tr>
  </tbody>
</table>
</font> 

</body>
</html>

EOF;

?>