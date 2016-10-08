<?php 
if(!isset($_SESSION))
{
session_start();
}
include "config.php";
if ($_REQUEST["userid"])
{
$_SESSION["uname"] = $_REQUEST["userid"];
$_SESSION["pw"] = $_REQUEST["password"];
}
if ($_SESSION["pw"] == $adminpw)
{
$loginq = "select * from members where userid=\"".$_SESSION["uname"]."\"";
}
else
{
$loginq = "select * from members where userid=\"".$_SESSION["uname"]."\" and pword=\"".$_SESSION["pw"]."\"";
}
$loginr = mysql_query($loginq);
$loginrows = mysql_num_rows($loginr);
	if ($loginrows < 1)
	{
	unset($_SESSION["uname"]);
	unset($_SESSION["pw"]);
	include "header.php";
	include "style.php"; 
    echo "<center>Error. Could not login.  <BR><BR><B><font size=3>If You Know For Sure, Your Username and Password Are Correct, Your Account May Have Been Deleted Due To Bounced Emails Or An Unverified Account - Unverified Accounts Get Deleted After 24 Hours.  <br><br>Please Sign Up Again And If You Can, Use A Gmail Account.  If You Were A Upgraded Member, Simply Contact Me $adminname ($adminemail) With Your Payment Receipt Info And I Will Upgrade Your New Account</font><br><br><br></center>";
	include "footer.php";
    exit;
	}
	if ($loginrows > 0)
	{
	$userrecord = mysql_fetch_array($loginr);
	$id=$userrecord["id"];
	$name=$userrecord["name"];
	$lastname=$userrecord["lastname"];
	$contact_email=$userrecord["contact_email"];
	$subscribed_email=$userrecord["subscribed_email"];
	$country=$userrecord["country"];
	$paypal_email=$userrecord["paypal_email"];
	$payza_email=$userrecord["payza_email"];
	$egopay_account=$userrecord["egopay_account"];
	$perfectmoney_account=$userrecord["perfectmoney_account"];
	$okpay_account=$userrecord["okpay_account"];
	$solidtrustpay_account=$userrecord["solidtrustpay_account"];
	$moneybookers_email=$userrecord["moneybookers_email"];
	$userid=$userrecord["userid"];
	$status=$userrecord["status"];
	$referid=$userrecord["referid"];
	$verified=$userrecord["verified"];
	$solos=$userrecord["solos"];
	$points=$userrecord["points"];
	$commission=$userrecord["commission"];
	$ip=$userrecord["ip"];
	$joindate=$userrecord["joindate"];
	$lastpost=$userrecord["lastpost"];
	$subscribed=$userrecord["subscribed"];
	$memtype=$userrecord["memtype"];
	$vacation=$userrecord["vacation"];
	$vacation_date=$userrecord["vacation_date"];
	$surfcredits=$userrecord["surfcredits"];
	$sitessurfedtoday=$userrecord["sitessurfedtoday"];
	$surfratiocounter=$userrecord["surfratiocounter"];
	$surf_clicks=$userrecord["surf_clicks"];
	$surf1_clicks=$userrecord["surf1_clicks"];
	$surf_last_id=$userrecord["surf_last_id"];
	$tickets=$userrecord["tickets"];
	$totalsitessurfedever=$userrecord["totalsitessurfedever"];
	$sitessurfedforcontest=$userrecord["sitessurfedforcontest"];
	$bonus_viewed=$userrecord["bonus_viewed"];
	}
extract($_REQUEST);
?>