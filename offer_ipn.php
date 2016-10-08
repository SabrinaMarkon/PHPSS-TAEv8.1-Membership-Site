<?



include "config.php";



$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {

$value = urlencode(stripslashes($value));

$req .= "&$key=$value";

//Remove this line after you have debugged

//print $req;

}



// post back to PayPal system to validate

$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Host: www.paypal.com\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);



// assign posted variables to local variables

$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$quantity = $_POST['quantity'];
$txn_id = $_POST['txn_id'];
$txn_type = $_POST['txn_type'];
$subscr_id = $_POST['subscr_id'];
$subscr_date = $_POST['subscr_date'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$userid = $_POST['option_selection1'];


if (!$fp) {

// HTTP ERROR

} else {

fputs ($fp, $header . $req);

while (!feof($fp)) {

$res = fgets ($fp, 1024);

if (strcmp ($res, "VERIFIED") == 0) {

print "VERIFIED";

	if ($txn_type == "subscr_cancel") {

	$q = "update subscriptions set paymentdate='$subscr_date',status='Canceled' where transaction='$subscr_id'";
	$r = mysql_query($q);
	exit;
	}
	else if ($txn_type == "subscr_modify") {
	$q = "update subscriptions set paymentdate='$subscr_date',status='Modified' where transaction='$subscr_id'";
	$r = mysql_query($q);
	exit;
	}
	else if ($txn_type == "subscr_failed") {
	$q = "update subscriptions set paymentdate='$subscr_date',status='Failed' where transaction='$subscr_id'";
	$r = mysql_query($q);
	exit;
	}
	else if ($txn_type == "subscr_payment") {
	$q = "update subscriptions set paymentdate='$subscr_date',status='Paid' where transaction='$subscr_id'";
	$r = mysql_query($q);
	exit;
	}
	else {

	$subscriptionq = "insert into subscriptions (userid,transaction,product,paymentdate,amountreceived,status) values ('$userid','$subscr_id','Special Offer Ad Package - Paypal Subscription','$subscr_date','$payment_amount','Paid')";
	$subscriptionr = mysql_query($subscriptionq);

	if ($receiver_email == $paypal AND $payment_status == "Completed") {



		$query = "select * from members where userid='".$userid."'";

		$result = mysql_query ($query)

		           		or die ("Query failed");

		$numrows = @ mysql_num_rows($result);

		

		if ($numrows == 1) {


		$user = mysql_fetch_array($result);
		$referid = $user["referid"];
		$paypal = $user["paypal_email"];
		$payza = $user["payza_email"];
		$egopay = $user["egopay_account"];
		$perfectmoney = $user["perfectmoney_account"];
		$okpay = $user["okpay_account"];
		$solidtrustpay = $user["solidtrustpay_account"];
		$moneybookers = $user["moneybookers_email"];
		$paychoice = "Paypal";
		$transaction = $txn_id;
#buycommission($referid, $amount);
			$refq = "select * from members where userid=\"$referid\"";
			$refr = mysql_query($refq);
			$refrows = mysql_num_rows($refr);
			if ($refrows > 0) {
				$refmemtype = mysql_result($refr,0,"memtype");
				$refemail = mysql_result($refr,0,"contact_email");
				if ($refmemtype == "PRO") {
					$percent = $probuycom;
					} elseif ($refmemtype == "JV Member") {
					$percent = $jvbuycom;
					} elseif ($refmemtype == "SUPER JV") {
					$percent = $superjvbuycom;
					}
					$refearn = $payment_amount*$percent/100;
					mysql_query("update members set commission=commission+".$refearn." where userid='".$referid."'");
					mysql_query("insert into transactions values ('id', '".$referid."','Special Offer Buyer Commissions','".time()."','$refearn')");

			// email sponsor - pass subject and message depending on which item the userid bought.
			$refsubject = "Your Referral Just Made a Purchase!";
			$refmessage = "A member of your downline, " . $userid . ", just purchased your Special Offer for " . $payment_amount . "\n"
			. "As a " . $refmemtype . " member, you earn " . $refearn . " added to your account!\n"
			. "Thank you! \n"
			. $sitename . " Admin \n";
			sponsorEmail ($referid, $refsubject, $refmessage); 
			}

			mysql_query("INSERT INTO transactions VALUES ('id', '".$userid."','Paypal Special Offer Ad Package','".time()."','$payment_amount\$')");


			$sql = mysql_query("SELECT * FROM offerpage LIMIT 1");
			$offer = mysql_fetch_array($sql);
		

			if($offer['points']) mysql_query("UPDATE members SET points=points+".$offer['points']." WHERE userid='".$userid."'");

			if($offer['surfcredits']) mysql_query("UPDATE members SET surfcredits=surfcredits+".$offer['surfcredits']." WHERE userid='".$userid."'");

					if($offer['banner_num'] AND $offer['banner_views']) {
					
						$count = $offer['banner_num'];
						while($count > 0) {
							mysql_query("INSERT INTO `banners` ( `id` , `name` , `bannerurl` , `targeturl` , `userid` , `status` , `shown` , `clicks` , `max` , `added` )VALUES (NULL , '', '', '', '".$userid."', '0', '0', '0', '".$offer['banner_views']."', '0')");
							$count--;
						}
					}

					if($offer['featuredad_num'] AND $offer['featuredad_views']) {
						$count = $offer['featuredad_num'];
						while($count > 0) {
							mysql_query("insert into featuredads (userid,max,purchase) values('$userid','".$offer['featuredad_views']."',NOW())");
							$count--;
							}
						}

					   if($offer['button_num'] AND $offer['button_views']) {
					
						$count = $offer['button_num'];
						while($count > 0) {
							mysql_query("INSERT INTO `buttons` ( `id` , `name` , `bannerurl` , `targeturl` , `userid` , `status` , `shown` , `clicks` , `max` , `added` )VALUES (NULL , '', '', '', '".$userid."', '0', '0', '0', '".$offer['button_views']."', '0')");
							$count--;
						}
					} if($offer['traffic_num'] AND $offer['traffic_views']) {
					
						$count = $offer['traffic_num'];
						while($count > 0) {
							mysql_query("insert into tlinks (userid,paid) values('$userid','".$offer['traffic_views']."')");
							$count--;
						}
					}
					
					if($offer['login_num'] AND $offer['login_views']) {
					
						$count = $offer['login_num'];
						while($count > 0) {
							mysql_query("insert into loginads (userid,max) values('$userid','".$offer['login_views']."')");
							$count--;
						}
					} if($offer['hotlink_num'] AND $offer['hotlink_views']) {
					
						$count = $offer['hotlink_num'];
						while($count > 0) {
							mysql_query("INSERT INTO hotlinks (userid,max,purchase) values('$userid','".$offer['hotlink_views']."','".time()."')");
							$count--;
						}
					}
					
					if($offer['solo_num']) {
					
						$count = $offer['solo_num'];
						while($count > 0) {
							mysql_query("INSERT INTO `solos` (`id` ,`userid` ,`approved` ,`subject` ,`adbody` ,`sent` ,`added`) VALUES (NULL , '".$userid."', '0', '', '', '0', '0')");
							$count--;
						}
					}	
					if($offer['hheadlinead_num'] AND $offer['hheadlinead_views']) {
						$count = $offer['hheadlinead_num'];
						while($count > 0) {
							mysql_query("insert into hheadlineads (userid,max,purchase) values('$userid','".$offer['hheadlinead_views']."',NOW())");
							$count--;
							}
						}
					if($offer['hheaderad_num'] AND $offer['hheaderad_views']) {
						$count = $offer['hheaderad_num'];
						while($count > 0) {
							mysql_query("insert into hheaderads (userid,max,purchase) values('$userid','".$offer['hheaderad_views']."',NOW())");
							$count--;
							}
						}

				if($offer['upgrade_pro']=="1") {
					upgrade_jv($userid,"yes",$referid);
					}
					
				if($offer['upgrade_pro']=="2") {
					upgrade_superjv($userid,"yes",$referid);
					}				
					if($offer['ptc_num'] AND $offer['ptc_views']) {

				

					$count = $offer['ptc_num'];

					while($count > 0) {

						mysql_query("insert into ptcads (userid,paid) values('$userid','".$offer['ptc_views']."')");

						$count--;

					}

				}

   if($offer['topnav_num']) {
					
				$count = $offer['topnav_num'];
						while($count > 0) {		
							mysql_query("INSERT INTO `topnav` (`userid` , `date`) VALUES ('".$userid."', '".time()."')");
	
					$count--;
						}
					}

                                                                  if($offer['botnav_num']) {
					
				$count = $offer['botnav_num'];
						while($count > 0) {		
							mysql_query("INSERT INTO `botnav` (`userid` , `date`) VALUES ('".$userid."', '".time()."')");
	
					$count--;
						}
					}

		

		} else {

		//Error
		exit;
		

		}

	

	}

	} // else



}


else if (strcmp ($res, "INVALID") == 0) {

// log for manual investigation

print "NOT VERIFIED";



}

}

fclose ($fp);

}



mysql_close();







?>