<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";

$userid = $_POST['userid'];
$email = $_POST['email'];
    	?><table>
      	<tr>
        <td width="15%" valign=top><br>
        <? include("adminnavigation.php"); ?>
        </td>
        <td  valign="top" align="center"><br><br> <?

    	echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";

		if ($mailermethod == "smtp") {
		include "../SMTP.php";
		}
		$query = "SELECT userid, contact_email FROM members WHERE verified = '0'";
		$result = mysql_query ($query);
		$count = mysql_num_rows($result);
		
		while($each = mysql_fetch_array($result)) {
		
			$message = "Hello,\n\n"
	               ."Please verify your email address by clicking this link ".$domain."/verify.php?userid=".$each['userid']."&email=".$each['contact_email']."\n\n"
		           ."Thank you!\n\n\n"
		           .$sitename." Admin\n"
		           .$adminemail."\n\n\n\n";

	$return_path = $adminemail;
	if ($mailermethod == "smtp") {
    $mail2 = clone $mail;
	$mail2->SetFrom($return_path, $sitename);
	$mail2->AddReplyTo($return_path,$sitename);
	$mail2->Subject = "[".$sitename."] Email Verification";
	$Message = eregi_replace("[\]",'',$message);
    $mail2->Body = $Message;
    $mail2->AddAddress($each['contact_email']);
	$mail2->Send();
	}
	else {
	$headers .= "From: $sitename<$return_path>\n";
	$headers .= "Reply-To: <$return_path>\n";
	$headers .= "X-Sender: <$return_path>\n";
	$headers .= "X-Mailer: PHP5\n";
	$headers .= "X-Priority: 3\n";
	$headers .= "Return-Path: <$return_path>\n";
	@mail($each['contact_email'], "[".$sitename."] Email Verification", wordwrap(stripslashes($message)),$headers, "-f$return_path");
	}
		}

    	echo "<br><center><p>A verification email has been sent to ".$count." members.</p></center></td></tr></table>";

include "../footer.php";
mysql_close($dblink);
?>