<?php
#### Original Enhanced Advertise&Surf TAE (Text Ad Exchange) v7.1 PHP 5.4 functional copyright 2009-2014 Sabrina Markon sabrina@phpsitescripts.com ####
include "connect.php";

date_default_timezone_set('America/Edmonton');

	$settings = mysql_query ('select * from settings');

	$settingrecord = mysql_fetch_array($settings);

	
    while ($settingrecord = mysql_fetch_array($settings)) {
	   	$setting[$settingrecord["name"]] = $settingrecord["setting"];
	}
	$setting['pay_with'] = unserialize($setting['pay_with']);
	extract($setting);

function buycommission ($userid, $amount) {

	global $probuycom, $jvbuycom, $superjvbuycom;

	if($amount) {

		$sql = mysql_query("SELECT * FROM members WHERE userid='$userid'");
		if(@mysql_num_rows($sql)) {
		$info = mysql_fetch_array($sql);
		if ($info['memtype'] == "PRO") {
			$percent = $probuycom;
			} elseif ($info['memtype'] == "JV Member") {
			$percent = $jvbuycom;
			} elseif ($info['memtype'] == "SUPER JV") {
			$percent = $superjvbuycom;
			}
			$earn = $amount*$percent/100;
			mysql_query("update members set commission=commission+".$earn." where userid='".$userid."'");
			mysql_query("INSERT INTO transactions VALUES ('id', '".$userid."','Buyer Commissions','".time()."','$earn \$')");
		}

	}

}
	
function assignpoints ($userid, $points) {

	global $propercent, $jvpercent, $superjvpercent;

	if($points) {

		$sql = mysql_query("SELECT * FROM members WHERE userid='$userid'");
		
		if(@mysql_num_rows($sql)) {
				$info = mysql_fetch_array($sql);
				
			if ($info['memtype'] == "PRO") {
			   $percent = $propercent;
			} elseif ($info['memtype'] == "JV Member") {
			   $percent = $jvpercent;
			} elseif ($info['memtype'] == "SUPER JV") {
			   $percent = $superjvpercent;
			}				
				$earn = $points *$percent/100;
				mysql_query("update members set points=points+".$earn." where userid='".$userid."'");
		}

	}

}	

function upgrade_jv ($userid,$paidcash,$referid) {

	global $jvpoints,$jvsurfcreditsignupbonus,$projvcom,$jvjvcom,$superjvjvcom;

	$today = date('Y-m-d',strtotime("now"));

	mysql_query("UPDATE members SET memtype = 'JV Member', points=points+".$jvpoints.", surfcredits=surfcredits+".$jvsurfcreditsignupbonus.", subscribed='$today' WHERE userid = '".$userid."'");
               mysql_query("INSERT INTO transactions VALUES ('id', '".$userid."','JV Upgrade','".time()."','Check for Transaction/Coupon')"); 

	$refq = "select * from members where userid=\"$referid\"";
	$refr = mysql_query($refq);
	$refrows = mysql_num_rows($refr);
	if ($refrows > 0)
	{
		$refmemtype = mysql_result($refr,0,"memtype");
		if ($mem=="SUPER JV") {
			$commission=$superjvjvcom;
		} elseif ($mem=="JV Member") {
			$commission=$jvjvcom;
		} elseif ($mem=="PRO") {
			$commission=$projvcom;
		}
		if ($commission > 0) {
			$refq2 = "update members set commission=commission+".$commission." where userid=\"$referid\"";
			$refr2 = mysql_query($refq2);
			mysql_query("insert into transactions values ('id','$referid','Referral Upgrade Commissions $userid','".time()."','$commission')");
		}
	} # if ($refrows > 0)
}

function upgrade_superjv ($userid,$paidcash,$referid) {

	global $superjvpoints,$superjvsurfcreditsignupbonus,$prosupercom,$jvsupercom,$superjv2supercom;

	$today = date('Y-m-d',strtotime("now"));

	mysql_query("UPDATE members SET memtype = 'SUPER JV', points=points+".$superjvpoints.", surfcredits=surfcredits+".$superjvsurfcreditsignupbonus.", subscribed='$today' WHERE userid = '".$userid."'") or die(mysql_error());
                  mysql_query("INSERT INTO transactions VALUES ('id', '".$userid."','SUPER JV Upgrade','".time()."','Check for Transaction/Coupon')"); 

	$refq = "select * from members where userid=\"$referid\"";
	$refr = mysql_query($refq);
	$refrows = mysql_num_rows($refr);
	if ($refrows > 0)
	{
		$refmemtype = mysql_result($refr,0,"memtype");
		if ($mem=="SUPER JV") {
			$commission=$superjv2supercom;
		} elseif ($mem=="JV Member") {
			$commission=$jvsupercom;
		} elseif ($mem=="PRO") {
			$commission=$prosupercom;
		}
		if ($commission > 0) {
			$refq2 = "update members set commission=commission+".$commission." where userid=\"$referid\"";
			$refr2 = mysql_query($refq2);
			mysql_query("insert into transactions values ('id','$referid','Referral Upgrade Commissions $userid','".time()."','$commission')");
		}
	} # if ($refrows > 0)
}



function upgrade_jv_exchange ($userid) {

	global $jvpoints,$jvsurfcreditsignupbonus,$projvcom,$jvjvcom,$superjvjvcom;

	$today = date('Y-m-d',strtotime("now"));

	mysql_query("UPDATE members SET memtype = 'JV Member', points=points+".$jvpoints.", surfcredits=surfcredits+".$jvsurfcreditsignupbonus.", subscribed='$today' WHERE userid = '".$userid."'");

}

function upgrade_superjv_exchange ($userid) {

	global $superjvpoints,$superjvsurfcreditsignupbonus,$projvcom,$jvjvcom,$superjvjvcom;

	$today = date('Y-m-d',strtotime("now"));

	mysql_query("UPDATE members SET memtype = 'SUPER JV', points=points+".$superjvpoints.", surfcredits=surfcredits+".$superjvsurfcreditsignupbonus.", subscribed='$today' WHERE userid = '".$userid."'");

}




function delete_account($userid) {

	$sql = mysql_query("SELECT * FROM `members` WHERE `userid` = '".$userid."'");
	$each = mysql_fetch_array($sql);

	$info = mysql_query("SELECT * FROM members WHERE userid = '".$each['referid']."'");
	if(@mysql_num_rows($info)) {
		$ref = mysql_fetch_array($info);
		
		if($ref['memtype'] == "PRO") $com = $prorefcom;
		else $com = $freerefcom;

		mysql_query("UPDATE members SET commission=commission-".$com." WHERE userid = '".$each['referid']."'");
		
		mysql_query("UPDATE members SET commission=0 WHERE userid = '".$each['referid']."' AND commission < '0'");
		
		if($com) mysql_query("INSERT INTO transactions VALUES ('".$each['referid']."','Deleted Referral $userid','".time()."','-$com\$')");
	}

    $query1 = "delete from members where userid='".$userid."'";
	$result1 = mysql_query ($query1);
	
    $query1 = "delete from builder where userid='".$userid."'";
	$result1 = mysql_query ($query1);
	
    $query1 = "delete from support where memberid='".$userid."'";
	$result1 = mysql_query ($query1);
	
    $query1 = "delete from clicks where memberid='".$userid."'";
	$result1 = mysql_query ($query1);

    $query2 = "delete from post where userid='".$userid."'";
	$result2 = mysql_query ($query2);
	
    $query2 = "delete from refmail where userid='".$userid."'";
	$result2 = mysql_query ($query2);
	
    $query2 = "delete from tlinks where userid='".$userid."'";
	$result2 = mysql_query ($query2);
	
    $query2 = "delete from solos where userid='".$userid."'";
	$result2 = mysql_query ($query2);
	
    $query2 = "delete from saved_post where userid='".$userid."'";
	$result2 = mysql_query ($query2);

    $query3 = "delete from viewed where userid='".$userid."'";
	$result3 = mysql_query ($query3);
    $query3 = "delete from banners where userid='".$userid."'";
	$result3 = mysql_query ($query3);	
    $query3 = "delete from drawing where userid='".$userid."'";
	$result3 = mysql_query ($query3);	
    $query3 = "delete from promo_used where userid='".$userid."'";
	$result3 = mysql_query ($query3);		
    $query3 = "delete from refmail where userid='".$userid."'";
	$result3 = mysql_query ($query3);		
    $query3 = "delete from support where userid='".$userid."'";
	$result3 = mysql_query ($query3);		
    $query3 = "delete from tlinks where userid='".$userid."'";
	$result3 = mysql_query ($query3);	
    $query3 = "delete from tlviews where userid='".$userid."'";
	$result3 = mysql_query ($query3);	
    $query3 = "delete from transactions where userid='".$userid."'";
	$result3 = mysql_query ($query3);	
    $query3 = "delete from urls where userid='".$userid."'";
	$result3 = mysql_query ($query3);	
	$query3 = "delete from banner_clicks where userid='".$userid."'";
	$result3 = mysql_query ($query3);		
	$query3 = "delete from navlink where userid='".$userid."'";
	$result3 = mysql_query ($query3);

}


function add_adclick($type) {

	global $userrecord,$domain,$price1,$price2,$price3,$price4,$price5,$price6,$price7,$price8,$price9,$price10;

	$userid = $userrecord['userid'];
	
	if($type == "text") {
		mysql_query("UPDATE members SET textad_clicks=textad_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['textad_clicks']+1;
	} elseif($type == "solo") {
		mysql_query("UPDATE members SET solo_clicks=solo_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['solo_clicks']+1;
	} elseif($type == "banner") {
		mysql_query("UPDATE members SET banner_clicks=banner_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['banner_clicks']+1;
	}elseif($type == "button") {
		mysql_query("UPDATE members SET button_clicks=button_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['button_clicks']+1;
	}elseif($type == "hotlink") {
		mysql_query("UPDATE members SET hotlink_clicks=hotlink_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['hotlink_clicks']+1;
	}elseif($type == "htmlad") {
		mysql_query("UPDATE members SET htmlad_clicks=htmlad_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['htmlad_clicks']+1;
	}elseif($type == "navtop") {
		mysql_query("UPDATE members SET navtop_clicks=navtop_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['navtop_clicks']+1;
	}elseif($type == "navbot") {
		mysql_query("UPDATE members SET navbot_clicks=navbot_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['navbot_clicks']+1;
	}elseif($type == "ptc") {
		mysql_query("UPDATE members SET ptc_clicks=ptc_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['ptc_clicks']+1;
	}elseif($type == "surfpages") {
		mysql_query("UPDATE members SET surf_clicks=surf_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['surf_clicks']+1;
	}else {
		mysql_query("UPDATE members SET traffic_clicks=traffic_clicks+1 WHERE userid='".$userrecord['userid']."'");
		$clicks = $userrecord['traffic_clicks']+1;
	}
	
	$sql = mysql_query("SELECT * FROM prizes WHERE type='".$type."' AND clicks='".$clicks."'");
	if(@mysql_num_rows($sql)) {
		$offer = mysql_fetch_array($sql);
		
			echo "You have won bonus prizes for viewing ".$offer['clicks']." websites!";
			$won = "";
			
				if($offer['commission']) { 
						$won .= " $".$offer['commission']." commissions";
						mysql_query("UPDATE members SET commission=commission+".$offer['commission']." WHERE userid='".$userid."'");
mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");
					}
						
					
					if($offer['points']) { 
						$won .= " ".$offer['points']." points ";
						mysql_query("UPDATE members SET points=points+".$offer['points']." WHERE userid='".$userid."'");
mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");

					}
					if($offer['surfcredits']) { 
						$won .= " ".$offer['surfcredits']." credits ";
						mysql_query("UPDATE members SET surfcredits=surfcredits+".$offer['surfcredits']." WHERE userid='".$userid."'");
					mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");

					}
					if($offer['banner_num'] AND $offer['banner_views']) {
						$won .= " ".$offer['banner_num']." banner(s) with ".$offer['banner_views']." impressions each";


						$count = $offer['banner_num'];
						while($count > 0) {
	

           mysql_query("INSERT INTO `banners` ( `id` , `name` , `bannerurl` , `targeturl` , `userid` , `status` , `shown` , `clicks` , `max` , `added` )VALUES (NULL , '', '', '', '".$userid."', '0', '0', '0', '".$offer['banner_views']."', '0')");

mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");
							$count--;

                             					}
					}
					
					  if($offer['button_num'] AND $offer['button_views']) {
						$won .= " ".$offer['button_num']." button banner(s) with ".$offer['button_views']." each ";
						$count = $offer['button_num'];
						while($count > 0) {
							mysql_query("INSERT INTO `buttons` ( `id` , `name` , `bannerurl` , `targeturl` , `userid` , `status` , `shown` , `clicks` , `max` , `added` )VALUES (NULL , '', '', '', '".$userid."', '0', '0', '0', '".$offer['button_views']."', '0')");
mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");							$count--;
						}
					}   if($offer['traffic_num'] AND $offer['traffic_views']) {
						$won .= " ".$offer['traffic_num']." traffic link(s) with ".$offer['traffic_views']." views per link";
						$count = $offer['traffic_num'];
						while($count > 0) {
							mysql_query("insert into tlinks (userid,paid) values('$userid','".$offer['traffic_views']."')");
mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");	
						$count--;
						}
					}
					 if($offer['ptc_num'] AND $offer['ptc_views']) {
						$won .= " ".$offer['ptc_num']." paid to click link(s) with ".$offer['ptc_views']." views per link";
						$count = $offer['ptc_num'];
						while($count > 0) {
							mysql_query("insert into ptcads (userid,paid) values('$userid','".$offer['ptc_views']."')");
mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");	
						$count--;
						}
					}

					if($offer['solo_num']) {
						$won .= " ".$offer['solo_num']." solo ad(s) ";
						$count = $offer['solo_num'];
						while($count > 0) {
							mysql_query("INSERT INTO `solos` (`id` ,`userid` ,`approved` ,`subject` ,`adbody` ,`sent` ,`added`) VALUES (NULL , '".$userid."', '0', '', '', '0', '0')");
mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");
							$count--;
						}
					}

					 if($offer['login_num'] AND $offer['login_views']) {
					
						$won .= " ".$offer['login_num']." login ad(s) with ".$offer['login_views']." impressions each";


						$count = $offer['login_num'];                                                                           while($count > 0) {

							mysql_query("insert into loginads (userid,max) values('$userid','".$offer['login_views']."')");
mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");							$count--;
						}
					} if($offer['hotlink_num'] AND $offer['hotlink_views']) {
					
						$won .= " ".$offer['hotlink_num']." hotlink ad(s) with ".$offer['hotlink_views']." views per link";
						$count = $offer['hotlink_num'];
						while($count > 0) {
							mysql_query("INSERT INTO hotlinks (userid,max,purchase) values('$userid','".$offer['hotlink_views']."','".time()."')");
mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");
							$count--;
						}
					}
                                                                                 if($offer['topnav_num']) {
						$won .= " ".$offer['topnav_num']." top navigation link(s) ";
						$count = $offer['topnav_num'];
						while($count > 0) {		
							mysql_query("INSERT INTO `topnav` ( `userid` , `date`) VALUES ('".$userid."', '".time()."')");
mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");
							$count--;
						}
					}
                                                                              if($offer['botnav_num']) {
						$won .= " ".$offer['botnav_num']." bottom navigation link(s) ";
						$count = $offer['botnav_num'];
						while($count > 0) {		
							mysql_query("INSERT INTO `botnav` ( `userid` , `date`) VALUES ('".$userid."', '".time()."')");
mysql_query("INSERT INTO prizetrans VALUES ('id', '".$userid."','".$won."','".time()."')");
							$count--;
						}
					}


		mysql_query("INSERT INTO prizes_won SET userid='$userid', won='$won'");			
					
				
		echo $won;
		echo "<br>";
	} else {
	
		if($type == "text") {
			echo " You have viewed $clicks text ads today.";
		} elseif($type == "htmlad") {
			echo " You have viewed $clicks html ads today.";
		} elseif($type == "solo") {
			echo " You have viewed $clicks solo ads today.";
		} elseif($type == "banner") {
			echo " You have viewed $clicks banner ads today.";
		} elseif($type == "button") {
			echo " You have viewed $clicks button banner ads today.";
		} elseif($type == "hotlink") {
			echo " You have viewed $clicks hotlinks today.";
		} elseif($type == "navtop") {
			echo " You have viewed $clicks top navigation links today.";
		} elseif($type == "navbot") {
			echo " You have viewed $clicks bottom navigation links today.";
		} elseif($type == "ptc") {
			echo " You have viewed $clicks paid to click ads today.";
		} elseif($type == "surfpages") {
			echo " You have viewed $clicks surf pages today.";
		} else {
			echo " You have viewed $clicks traffic links today.";
		}
	
	echo "<br>";
	}
	

}

$count= mysql_query("select * from members WHERE verified=1");
$rowcount = @mysql_num_rows($count);
$count= mysql_query("select * from members WHERE memtype='PRO' and verified='1'");
$prorowcount = @ mysql_num_rows($count);
$count= mysql_query("select * from members WHERE memtype='JV Member' and verified='1'");
$jvrowcount = @ mysql_num_rows($count);
$count= mysql_query("select * from members WHERE memtype='SUPER JV' and verified='1'");
$superjvrowcount = @ mysql_num_rows($count);

extract($_REQUEST);

?>