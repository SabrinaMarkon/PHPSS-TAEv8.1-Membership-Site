<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";
function formatDate($val) {
	$arr = explode("-", $val);
	return date("M d Y", mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
}
?>
<table>
<tr>
<td width="15%" valign=top><br>
<? include("adminnavigation.php"); ?>
</td>
<td valign="top" align="center"><br><br> <?
echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";
$action = $_REQUEST["action"];
##############################################################################################
if ($action == "savesettings")
{
$bounceconsequence = $_POST["bounceconsequence"];
$bouncesmax = $_POST["bouncesmax"];
$bounceemail = $_POST["bounceemail"];
$q = "update settings set setting=\"$bounceconsequence\" where name=\"bounceconsequence\"";
$r = mysql_query($q);
$q = "update settings set setting=\"$bouncesmax\" where name=\"bouncesmax\"";
$r = mysql_query($q);
$q = "update settings set setting=\"$bounceemail\" where name=\"bounceemail\"";
$r = mysql_query($q);
$spew = "<center><font color=\"#ff0000\"><b>Settings Saved</b></font></center><br><br>";
} # if ($action == "savesettings")
##############################################################################################
if ($action == "deletebounces")
{
$deleteuserid = $_POST["deleteuserid"];
$q = "delete from bounces where userid=\"$deleteuserid\"";
$r = mysql_query($q);
$spew = "<center><font color=\"#ff0000\"><b>Bounce Records Deleted</b></font></center><br><br>";
} # if ($action == "deletebounce")
##############################################################################################
if ($action == "confirmdeleteallbounces")
{
$spew = "<center><form action=\"bounce_viewer.php\" method=\"post\"><input type=\"hidden\" name=\"action\" value=\"deleteallbounces\"><input type=\"submit\" value=\"Confirm Mass Deletion Of All Bounces\" style=\"width: 450px; border: 2px #ff0000 solid; border-style: outset;\"></form><form action=\"bounce_viewer.php\" method=\"post\"><input type=\"submit\" value=\"Cancel Mass Deletion\" style=\"width: 450px;\"></form></center>";
} # if ($action == "confirmdeleteallbounces")
##############################################################################################
if ($action == "deleteallbounces")
{
$q1 = "delete from bounces";
$r1 = mysql_query($q1);
$spew = "<center><font color=\"#ff0000\"><b>All Bounce Records Were Deleted</b></font></center><br><br>";
} # if ($action == "deleteallbounces")
##############################################################################################
$q3 = "delete from bounces where bouncedate<='".(time()-7*24*60*60)."'";
$r3 = mysql_query($q3);
echo "<center><H2>Bounce Manager</H2></center><br>Bounce records are kept for 7 days then discarded in order to keep database size in check. Cleaning the database this way does NOT take members off vacation mode who bounced.<br>";
?>
<center>
<form action="bounce_viewer.php" method="post">
<input type="hidden" name="action" value="savesettings">
Bounce Email Address (ie. bounce@yourdomain.com on your server):&nbsp;<input type="text" name="bounceemail" value="<?php echo $bounceemail ?>">
<br><br>
How to handle Bouncing members (vacation is recommended since most members bounce sometimes!):&nbsp;<select name="bounceconsequence"><option value="vacation" <?php if ($bounceconsequence == "vacation") { echo "selected"; } ?>>Put on Vacation</option><option value="deletemember" <?php if ($bounceconsequence != "vacation") { echo "selected"; } ?>>Delete Member</option></select>
<br><br>
Bounces allowed (for each site) before member is deleted or placed on vacation in that site:&nbsp;<select name="bouncesmax">
<?php
for ($i=1;$i<=20;$i++)
{
?>
<option value="<?php echo $i ?>" <?php if ($bouncesmax == $i) { echo "selected"; } ?>><?php echo $i ?></option>
<?php
}
?>
</select>
<br><br>
<input type="submit" value="Save">
</form>
</center>
<?php

$countq = "select * from bounces";
$countr = mysql_query($countq);
$countrows = mysql_num_rows($countr);

$query = "SELECT *, count(userid) AS bouncecount FROM bounces GROUP BY userid";
$result = mysql_query ($query) or die(mysql_error());
$totalrows = mysql_num_rows($result);
if ($totalrows < 1)
{
echo "<center><p><b>There have been no new bounced emails in the past 7 days.</b></center></p>";
}
if ($totalrows > 0)
{
echo "<br><b>Total Bounced Emails: " . $countrows . "</b><br><br>";
?>
<br>
<?php
if ($spew != "")
	{
echo $spew;
	}
if ($spew == "")
	{
    ?>
<center>
<form action="bounce_viewer.php" method="post">
<input type="hidden" name="action" value="confirmdeleteallbounces">
<input type="submit" value="Purge All Bounce Records">
</form>
</center>
<?php
	}
?>
<br>
<table width="90%" cellpadding="4" cellspacing="2" border="0" align="center" bgcolor="#999999">
<tr bgcolor="#eeeeee">
<td align="center"><font size=2 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>">UserID</font></td>
<td align="center"><font size=2 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>">Total Bounces For This Site</font></td>
<td align="center"><font size=2 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>">Last Bounce Date</font></td>
<td align="center"><font size=2 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>">Last Bounce Error</font></td>
<td align="center"><font size=2 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>">Delete User Bounces</font></td>
</tr>
<?php
while ($line = mysql_fetch_array($result))
{
	$userid = $line["userid"];
	$bouncecount = $line["bouncecount"];
	$lastq = "select * from bounces where userid=\"$userid\" order by id desc limit 1";
	$lastr = mysql_query($lastq) or die(mysql_error());
	$lastrows = mysql_num_rows($lastr);
	if ($lastrows > 0)
	{
		$bounceerror = mysql_result($lastr,0,"bounceerror");
		$bounceerror = stripslashes($bounceerror);
		$bounceerror = str_replace('\\', '', $bounceerror);
		$bouncedate = mysql_result($lastr,0,"bouncedate");
		if (($bouncedate == "") or ($bouncedate == 0))
		{
			$showbouncedate = "";
		}
		else
		{
			$showbouncedate = formatDate($bouncedate);
		}
?>
<tr bgcolor="#eeeeee">
<td align="center"><font size=2 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>"><?php echo $userid ?></font></td>
<td align="center"><font size=2 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>"><?php echo $bouncecount ?></font></td>
<td align="center"><font size=2 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>"><?php echo $showbouncedate ?></font></td>
<td align="center"><div style="width: 400px; height: 100px; padding: 4px; overflow:auto; border-style: solid; border-size: 1px; border-color: #000000; background: #ffffff;"><? echo $bounceerror; ?></div></td>
<form method="POST" action="bounce_viewer.php">
<td align="center">
<input type="hidden" name="deleteuserid" value="<?php echo $userid ?>">
<input type="hidden" name="action" value="deletebounces">
<input type="submit" value="Delete">
</form>
</td></tr>
<tr><td colspan="5"></td></tr>
<?php
	} # if ($lastrows > 0)
} # while ($line = mysql_fetch_array($result))
echo "</table><br>";
} # if ($totalrows > 0)
echo "</td></tr></table>";
include "../footer.php";
mysql_close($dblink);
?>