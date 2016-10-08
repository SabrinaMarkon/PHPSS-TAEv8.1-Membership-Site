<?php
#require('../EgoPaySci.php'); // do NOT uncomment UNLESS this file is included on another page that does NOT already include the egopay sci. - S. Markon Feb 2016
$q = "select * from adpacks where enabled=\"yes\" order by id";
$r = mysql_query($q);
$rows = mysql_num_rows($r);
if ($rows > 0)
{
?>
<HR ALIGN= "center" size= 5 WIDTH= 75% COLOR= "#000000" NO SHADE><br>
<table cellpadding="0" cellspacing="2" border="0" align="center" width="90%" bgcolor="#999999">
<tr><td align="center" colspan="3">Purchase AdPacks</div></td></tr>
<tr bgcolor="#eeeeee"><td align="center"><b>Description</b></td><td align="center"><b>Price</b></td><td align="center"><b>Buy</b></td></tr>
<?php
	while ($rowz = mysql_fetch_array($r))
	{
	$adpackid = $rowz["id"];
	$description = $rowz["description"];
	$apprice = $rowz["price"];
	$howmanytimeschosen = $rowz["howmanytimeschosen"];
	$enabled = $rowz["enabled"];
	$points = $rowz["points"];
	$surfcredits = $rowz["surfcredits"];
	$banner_num = $rowz["banner_num"];
	$banner_views = $rowz["banner_views"];
	$solo_num = $rowz["solo_num"];
	$traffic_num = $rowz["traffic_num"];
	$traffic_views = $rowz["traffic_views"];
	$login_num = $rowz["login_num"];
	$login_views = $rowz["login_views"];
	$hotlink_num = $rowz["hotlink_num"];
	$hotlink_views = $rowz["hotlink_views"];
	$button_num = $rowz["button_num"];
	$button_views = $rowz["button_views"];
	$ptc_num = $rowz["ptc_num"];
	$ptc_views = $rowz["ptc_views"];
	$featuredad_num = $rowz["featuredad_num"];
	$featuredad_views = $rowz["featuredad_views"];
	$hheaderad_num = $rowz["hheaderad_num"];
	$hheaderad_views = $rowz["hheaderad_views"];
	$hheadlinead_num = $rowz["hheadlinead_num"];
	$hheadlinead_views = $rowz["hheadlinead_views"];
	$details = "";
	if ($points > 0)
		{
		$details .= "<span>$points Points</span><br>";
		}
	if ($surfcredits > 0)
		{
		$details .= "<span>$surfcredits Surf Credits</span><br>";
		}
	if ($solo_num > 0)
		{
		$details .= "<span>$solo_num Solo Ads</span><br>";
		}
	if (($featuredad_num > 0) and ($featuredad_views > 0))
		{
		$details .= "<span>$featuredad_num Featured Ads with $featuredad_views Impressions</span><br>";
		}
	if (($hheaderad_num > 0) and ($hheaderad_views > 0))
		{
		$details .= "<span>$hheaderad_num Hot Header Adz with $hheaderad_views Impressions</span><br>";
		}
	if (($hheadlinead_num > 0) and ($hheadlinead_views > 0))
		{
		$details .= "<span>$hheadlinead_num Hot Headline Adz with $hheadlinead_views Impressions</span><br>";
		}
	if (($banner_num > 0) and ($banner_views > 0))
		{
		$details .= "<span>$banner_num Banner Ads with $banner_views Impressions</span><br>";
		}
	if (($button_num > 0) and ($button_views > 0))
		{
		$details .= "<span>$button_num Button Banner Ads with $button_views Impressions</span><br>";
		}
	if (($login_num > 0) and ($login_views > 0))
		{
		$details .= "<span>$login_num Login Ads with $login_views Impressions</span><br>";
		}
	if (($traffic_num > 0) and ($traffic_views > 0))
		{
		$details .= "<span>$traffic_num Traffic Links with $traffic_views Impressions</span><br>";
		}
	if (($hotlink_num > 0) and ($hotlink_views > 0))
		{
		$details .= "<span>$hotlink_num Hot Links with $hotlink_views Impressions</span><br>";
		}
	if (($ptc_num > 0) and ($ptc_views > 0))
		{
		$details .= "<span>$ptc_num PTC Ads with $ptc_views Impressions</span><br>";
		}
?>
<tr bgcolor="#eeeeee"><td align="center">
<a href="javascript:;" onclick="document.getElementById('showadpack').innerHTML='<?php echo $details ?>';"><?php echo $description ?></a></td><td align="center"><?php echo $apprice ?></td>
<td align="center">

<?php
		  if ($paypal<>"") { ?>
			<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but01.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="<? echo $paypal; ?>">
			<input type="hidden" name="item_name" value="<? echo $sitename; ?> AdPack <? echo $userid; ?>">
			<input type="hidden" name="on0" value="User ID">
			<input type="hidden" name="os0" value="<? echo $userid; ?>">
			<input type="hidden" name="on1" value="adpackid">
			<input type="hidden" name="os1" value="<?php echo $adpackid ?>">				
			<input type="hidden" name="amount" value="<? echo $apprice; ?>">
			<input type="hidden" name="undefined_quantity" value="1">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="return" value="<? echo $domain; ?>/members/advertise.php">
			<input type="hidden" name="cancel" value="<? echo $domain; ?>/members/advertise.php">
			<input type="hidden" name="notify_url" value="<? echo $domain; ?>/members/advertise_ipn.php">
			<input type="hidden" name="currency_code" value="USD">
			</form>
          <? }

		  if ($adminpayza<>"") { ?>
			<form method="post" action="https://secure.payza.com/checkout" > 
			<input type="hidden" name="ap_purchasetype" value="item"/> 
			<input type="hidden" name="ap_merchant" value="<? echo $adminpayza; ?>"/> 
			<input type="hidden" name="ap_currency" value="USD"/> 
			<input type="hidden" name="ap_returnurl" value="<? echo $domain; ?>/members/advertise.php"/> 
			<input type="hidden" name="ap_itemname" value="<? echo $sitename; ?> AdPack <? echo $userid; ?>"/> 
			<input type="hidden" name="ap_quantity" value="1"/> 
			<input type="hidden" name="apc_1" value="<? echo $userid; ?>">
			<input type="hidden" name="apc_2" value="<?php echo $adpackid ?>">
			<input type="hidden" name="ap_amount" value="<? echo $apprice; ?>"/> 
			<input type="image" name="ap_image" src="<?php echo $domain ?>/images/payzasm.png"/>
			</form>
          <? }

			if (($egopay_store_id!="") and ($egopay_store_password!=""))
			{
			try {
					
				$oEgopay = new EgoPaySci(array(
					'store_id'          => $egopay_store_id,
					'store_password'    => $egopay_store_password
				));
				
				$sPaymentHash = $oEgopay->createHash(array(
				/*
				 * Payment amount with two decimal places 
				 */
					'amount'    => $apprice,
				/*
				 * Payment currency, USD/EUR
				 */
					'currency'  => 'USD',
				/*
				 * Description of the payment, limited to 120 chars
				 */
					'description' => $sitename . ' AdPack ' . $userid,
				
				/*
				 * Optional fields
				 */
				'fail_url'	=> $domain. '/members/advertise.php',
				'success_url'	=> $domain. '/members/advertise.php',
				
				/*
				 * 8 Custom fields, hidden from users, limited to 100 chars.
				 * You can retrieve them only from your callback file.
				 */
				'cf_1' => $userid,
				'cf_2' => $sitename . ' AdPack ' . $userid,
				'cf_3' => $apprice,
				'cf_4' => $adpackid,
				//'cf_5' => '',
				//'cf_6' => '',
				//'cf_7' => '',
				//'cf_8' => '',
				));
				
			} catch (EgoPayException $e) {
				die($e->getMessage());
			}
			?>
			<form action="<?php echo EgoPaySci::EGOPAY_PAYMENT_URL; ?>" method="post">
			<input type="hidden" name="hash" value="<?php echo $sPaymentHash ?>">
			<input type="image" src="<?php echo $domain ?>/images/egopaysm.png" border="0">
			</form>
			<?php
			} # if (($egopay_store_id!="") and ($egopay_store_password!=""))

			if ($adminperfectmoney != "")
			{
			?>
			<form action="https://perfectmoney.com/api/step1.asp" method="POST">
			<input type="hidden" name="PAYEE_ACCOUNT" value="<?php echo $adminperfectmoney ?>">
			<input type="hidden" name="PAYEE_NAME" value="<?php echo $adminname ?>">
			<input type="hidden" name="PAYMENT_AMOUNT" value="<?php echo $apprice ?>">
			<input type="hidden" name="PAYMENT_UNITS" value="USD">
			<input type="hidden" name="STATUS_URL" value="<?php echo $domain ?>/perfectmoney_ipn.php">
			<input type="hidden" name="PAYMENT_URL" value="<?php echo $domain ?>/members/advertise.php">
			<input type="hidden" name="NOPAYMENT_URL" value="<?php echo $domain ?>/members/advertise.php">
			<input type="hidden" name="BAGGAGE_FIELDS" value="userid item adpackid">
			<input type="hidden" name="userid" value="<?php echo $userid ?>">
			<input type="hidden" name="item" value="<?php echo $sitename ?> AdPack <?php echo $userid ?>">
			<input type="hidden" name="adpackid" value="<?php echo $adpackid ?>">
			<input type="image" name="PAYMENT_METHOD" value="PerfectMoney account" src="<?php echo $domain ?>/images/perfectmoneysm.png" border="0">
			</form>
			<?php
			} # if ($adminperfectmoney != "")

			if ($adminokpay != "")
			{
			?>
			<form  method="post" action="https://www.okpay.com/process.html">
			<input type="hidden" name="ok_receiver" value="<?php echo $adminokpay ?>">
			<input type="hidden" name="ok_item_1_name" value="<?php echo $sitename ?> AdPack <?php echo $userid ?>">
			<input type="hidden" name="ok_currency" value="usd">
			<input type="hidden" name="ok_item_1_type" value="service">
			<input type="hidden" name="ok_item_1_price" value="<?php echo $apprice ?>">
			<input type="hidden" name="ok_item_1_custom_1_title" value="userid">
			<input type="hidden" name="ok_item_1_custom_1_value" value="<?php echo $userid ?>">
			<input type="hidden" name="ok_item_1_custom_2_title" value="adpackid">
			<input type="hidden" name="ok_item_1_custom_2_value" value="<?php echo $adpackid ?>">
			<input type="hidden" name="ok_return_success" value="<?php echo $domain ?>/members/advertise.php">
			<input type="hidden" name="ok_return_fail" value="<?php echo $domain ?>/members/advertise.php">
			<input type="hidden" name="ok_ipn" value="<?php echo $domain ?>/okpay_ipn.php">
			<input type="image" name="submit" src="<?php echo $domain ?>/images/okpaysm.gif" border="0">
			</form>
			<?php
			} # if ($adminokpay != "")

			if ($adminsolidtrustpay != "")
			{
			?>
			<form action="https://solidtrustpay.com/handle.php" method="post">
			<input type="hidden" name="merchantAccount" value="<?php echo $adminsolidtrustpay ?>">
			<input type="hidden" name="sci_name" value="your_sci_name">
			<input type="hidden" name="amount" value="<?php echo $apprice ?>">
			<input type="hidden" name="currency" value="USD">
			<input type="hidden" name="user1" value="<?php echo $userid ?>">
			<input type="hidden" name="user2" value="<?php echo $adpackid ?>">
			<input type="hidden" name="notify_url" value="<?php echo $domain ?>/solidtrustpay_ipn.php">
			<input type="hidden" name="return_url" value="<?php echo $domain ?>/members/advertise.php">
			<input type="hidden" name="cancel_url"  value="<?php echo $domain ?>/members/advertise.php">
			<input type="hidden" name="item_id" value="<?php echo $sitename ?> AdPack <?php echo $userid ?>">
			<input type="image" name="cartImage" src="<?php echo $domain ?>/images/solidtrustpaysm.gif" alt="Solid Trust Pay" border="0">
			</form>
			<?php
			} # if ($adminsolidtrustpay != "")

		  if ($adminmoneybookers<>"") { ?>
			<form action="https://www.moneybookers.com/app/payment.pl" method="post" target="_blank">
			<input type="hidden" name="pay_to_email" value="<? echo $adminmoneybookers; ?>">
			<input type="hidden" name="status_url" value="<? echo $domain; ?>/moneybookers_ipn.php">
			<input type="hidden" name="return_url" value="<? echo $domain; ?>/members/advertise.php">
			<input type="hidden" name="cancel_url" value="<? echo $domain; ?>/advertise.php">
			<input type="hidden" name="language" value="EN">
			<input type="hidden" name="amount" value="<? echo $apprice; ?>">
			<input type="hidden" name="currency" value="USD">
			<input type="hidden" name="merchant_fields" value="userid,itemname,adpackid">
			<input type="hidden" name="itemname" value="<? echo $sitename; ?> AdPack <? echo $userid; ?>">
			<input type="hidden" name="userid" value="<? echo $userid; ?>">
			<input type="hidden" name="adpackid" value="<?php echo $adpackid ?>">
			<input type="hidden" name="detail1_text" value="<? echo $sitename; ?> AdPack <? echo $userid; ?>">
			<input type="image" style="border-width: 1px; border-color: #8B8583" width="82" src="<?php echo $domain ?>/images/moneybookerssm.gif">
			</form>
          <? }
?>

</td>
</tr>
<?php
	} # while ($rowz = mysql_fetch_array($r))
?>
<tr bgcolor="#eeeeee"><td colspan="3" align="center">
<div id="showadpack"></div>
</td></tr>
</table>
<br>
<?php
} # if ($rows > 0)
?>
