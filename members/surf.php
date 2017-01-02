<?php
include "../control.php";
if($userid != "")
{
	if ($memtype=="PRO") {
		$earn = $prosurfcreditspersite;
		$surftimer = $prosurftimer;
	}elseif($memtype=="JV Member"){
		$earn = $jvsurfcreditspersite;
		$surftimer = $jvsurftimer;
	}else {
		$earn = $superjvsurfcreditspersite;
		$surftimer = $superjvsurftimer;
	}
	$_SESSION['surftime'] = $surftimer; // set the surftime
	//check for new window if manual surf :
	if ($autosurf != 'yes') {
		if(isset($_SESSION['surftimestart']) && time()-$_SESSION['surftimestart'] < $_SESSION['surftime']+1){
			include "../header.php";
			include "../style.php";
			echo '<center><p><b>You can not surf in more than one window..<br> Or Refresh/Reload this window after '.($_SESSION['surftime']+1 -(time()-$_SESSION['surftimestart'])).' seconds</b></p></center>';
			include "../footer.php";
			mysql_close($dblink);
			exit();
		}
	}
	// are there any surf urls left that have enough points to award to this user when they visit: surfpoint = how many points the url has been given towards visitor rewards. earn = how many credits
	// a member gets for viewing the url.
	$surfrs1 = mysql_query("SELECT * FROM surfurls WHERE surfpoint>=$earn AND userid <> '$userid' AND approved='1'");
	$totinsurf1 = mysql_num_rows($surfrs1);
	if($totinsurf1 <= $surf_clicks){
		include "../header.php";
		include "../style.php";
		echo '<center><p><b>You have viewed all the surf pages. Please check back again later.<br><br><a href="'.$domain.'/members/index.php">Click here</a> to return to the Members Area.</b></p></center>';
		include "../footer.php";
		mysql_close($dblink);
		exit();
	}
	//get a surf url = PROBLEM HERE SHOWING THE SAME URL OVER AND OVER.
	$surfsql = "SELECT * FROM surfurls WHERE surfpoint>=$earn AND userid <> '$userid' AND approved='1' AND id!=$surf_last_id and shownyet='no'";
//	echo $surfsql;
//	exit;
	$surfrs = mysql_query($surfsql);
	$totinsurf = mysql_num_rows($surfrs);
	if($totinsurf < 1){
		// this means all the surf urls have been visited and have shownyet = yes. So reset them all.
		$shownyetq = "update surfurls set shownyet='no'";
		$shownyetr = mysql_query($shownyetq);
		$surfsql = "SELECT * FROM surfurls WHERE surfpoint>=$earn AND userid <> '$userid' AND approved='1' AND id!=$surf_last_id and shownyet='no' ORDER BY rand() LIMIT 1";
		$surfrs = mysql_query($surfsql);
		$totinsurf = mysql_num_rows($surfrs);
		if($totinsurf < 1){
			include "../header.php";
			include "../style.php";
			echo '<center><p><b>Currently, there are no surf pages to visit. Please check back again later.<br><br><a href="'.$domain.'/members/index.php">Click here</a> to return to the Members Area.</b></p></center>';
			include "../footer.php";
			mysql_close($dblink);
			exit();
		}
	}
	// values:
	$id = mysql_result($surfrs, 0, 'id');
	$_SESSION['currentsurfpage_id'] = $id;
	$url = mysql_result($surfrs, 0, 'surfurl');
	$_SESSION['currentsurfpage_url'] = $url;
	$_SESSION['currentsurfpage_userid'] = mysql_result($surfrs, 0, 'userid');
	$_SESSION['currentsurfpage_status'] = 'pending';
	$_SESSION['surftimestart'] = time();
	mysql_query("UPDATE surfurls SET surfview=surfview+1 WHERE id='".$id."'");
	?>
	<html>
	<head>
		<title><?php echo $sitename ?> Surf Exchange</title>
		<style  TYPE="text/css">
			<!--
			BODY {

				background-color: #CC9966;
				margin-top: 0px;
				margin-bottom: 0px;
			}
			-->
		</style>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta NAME="keywords" CONTENT="ad exchange, ad exchanges, free ad exchange, free ad exchanges, text exchange, free text exchange, text exchanges, free text exchanges, advertise, advertise free, advertising, free advertising, market, marketing, promote, promote free, market free, free marketing, free promotion, traffic, guaranteed traffic, free guaranteed traffic, free traffic, free hits, safelist, safelists, free safelist, free safelists, network marketing, free network marketing, affiliate marketing, free affiliate marketing, affiliate, affiliates, free affiliate program, free affiliate programs, affiliate program, affiliate programs, list builder, list builders, free list builder, free list builders, leads, free leads, free business leads, business leads, viral marketing, referrals, free referrals, referral builder, referral builders, free referral builder, free referral builders, banner advertising, post free ads, submit free ads">
		<META NAME="Description" Content="Drive quality traffic to your site and boost your sales! Free advertising solutions.">
		<link rel="shortcut icon" href="/favicon.ico">

	</head>
	<frameset ROWS="40,*" BORDER=0 FRAMEBORDER=1 FRAMESPACING=0>
		<frame name="header" scrolling="no" noresize marginheight="1" marginwidth="1" target="main" src="surfbar.php">
		<frame name="main" src="<? echo $url; ?>">
	</frameset>
	</html>
	<?php
}
else
{
	include "../header.php";
	include "../style.php";
	echo '<p>You must be logged in to access this site. Please <a href="../index.php">click here</a> to login.</p>';
	include "../footer.php";
	mysql_close($dblink);
	exit();
}
?>