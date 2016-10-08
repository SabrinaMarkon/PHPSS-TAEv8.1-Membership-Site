<?php
include "admincontrol.php";
$userid = $_GET['userid'];
$userinfo=mysql_query ("select * from members where userid='".$userid."'");
$userrecord=mysql_fetch_array($userinfo);
$login = true;
$userid = $userrecord['userid'];
$goto = "/members/index.php?userid=" . $userid . "&password=" . $adminpw;
echo '<META HTTP-EQUIV="Refresh" Content="0;URL=' . $goto . '">';
mysql_close($dblink);
?>