<?php
include "../control.php";
?>
<html>
<head>
<?
$redirecturl = "hheaderadclicks3.php?id=".$_GET['adid']."&url=".urlencode($_GET['url']);
?>
</head>
<body>
<center>
<?

if($_GET['message']) {

	if($_GET['url']) {
	?>
	<div style="float: left; padding-top: 75px;">
	<a href="<? echo $_GET['url']; ?>" target="_top">Remove This Frame</a>
	</div>

	<div style="float: right; padding-top: 75px;">
	<a href="<? echo $_GET['url']; ?>" target="_top">Remove This Frame</a>
	</div>

	<?
	}

	echo $_GET['message'];
	
} else {



$query3 = "select * from members where userid='".$userid."'";
		$result3 = mysql_query ($query3)
            		or die ("Query failed");
					
        $userrecord = mysql_fetch_array($result3);
		$memtype = $userrecord["memtype"];

if ($memtype=="SUPER JV")
{
	$earn = $superjvhheaderadearn;
	$timer = $superjvhheaderadcredittimer;
}
if ($memtype=="JV Member")
{
	$earn = $jvhheaderadearn;
	$timer = $jvhheaderadcredittimer;
}
if (($memtype!="SUPER JV") and ($memtype!="JV Member"))
{
	$earn = $prohheaderadearn;
	$timer = $prohheaderadcredittimer;
}

	?><br>
	You Will Earn <? echo $earn ?>  Credits After <span id="plzwait"><?php echo $timer ?> seconds</span>
	<script type="text/javascript">
	counter = <?php echo $timer ?>;
	function countdown() {
		if ((0 <= 100) || (0 > 0)) {
			counter--;
			if(counter > 0) {
				document.getElementById("plzwait").innerHTML = '<b>'+counter+'<\/b> seconds';
				setTimeout('countdown()',1000);
			}
		if(counter < 1)
		{
		window.location="<?php echo $redirecturl ?>";
		}
		}
	}
	countdown();
	</script>
	<?
}
?>
<br>

<?

include("../banners.php");
mysql_close();
?>
</center>
</body>
</html>