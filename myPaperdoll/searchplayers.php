<?php

require("myrunuo.inc.php");

$msg = "";

// Check for sumitted response
check_get($submit, "submit");
if ($submit != "") {
  // Get name user is searching for
  check_get($player, "charname");

  // If the name input is less than 3 characters then flag error
  if (strlen($player) < 3)
    $msg = "<font face=\"Arial\" size=\"2\" size=\"3\">ERROR:</font><br>You must enter the name of the character you wish to search for. The name must be at least three letters long.</font><br>";
  else {
    // Setup exact / beginning name search
    $front = "LIKE '";
    $back = "'";

    check_get($which, "which");
    if ($which == "0") {
      $front = "LIKE '%";
      $back = "%'";
    }

    $link = sql_connect();

    $player = addslashes($player);
    $result = sql_query($link, "SELECT char_id,char_name FROM myrunuo_characters WHERE char_name {$front}{$player}{$back} ORDER by char_name"); // char_public=1 AND
    $msg = "<br><br>Your search returned the following characters:<br><br>\n";

    if (mysql_numrows($result)) {
      // Cycle through all records and display hyper link with shard player
      while ($row = mysql_fetch_row($result)) {
        $id = intval($row[0]);
        $name = htmlspecialchars($row[1]);
        $msg .= "<a href=\"player.php?id=$id\">$name</a><br>\n";
      }
    }
    else 
      $msg .= "<font face=\"Arial\" size=\"2\">No characters with that name found.</font><br>\n";

    mysql_free_result($result);
    mysql_close($link);
  }
}

echo <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html> 
<head> 
  <title>Search Players</title> 
  <meta http-equiv="Content-Type" content="text/html; CHARSET=iso-8859-1">
</head> 

<body bgcolor="#ffffff" text="#000000">

<font face="Verdana" size="4">Character Search</font><br>
<br> 
<form action="searchplayers.php" method="post">
  <font face="Verdana" size="2">
  Search for 
  <select name="which">
    <option value="0">Beginning of Name</option>
    <option value="1">Exact Name</option>
  </select>
  of character named <input type="text" name="charname" value="">&nbsp;&nbsp;
  <input type="submit" name="submit" value="search">
</form>
</font>

$msg

</body> 
</html>

EOF;

?>