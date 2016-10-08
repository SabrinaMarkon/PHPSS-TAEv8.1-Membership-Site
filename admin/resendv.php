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

	if (empty($userid)) {
		echo "Invalid link";
	}
	if (empty($email)) {
		echo "Invalid link";
	}

	if ($mailermethod == "smtp") {
	include "../SMTP.php";
	}
		$message = "Hello,\n\n"
               ."Please verify your email address by clicking this link ".$domain."/verify.php?userid=".$userid."&email=".$email."\n\n"
	           ."Thank you!\n\n\n"
	           .$sitename." Admin\n"
	           .$adminemail."\n\n\n\n";

	$return_path = $adminemail;
	if ($mailermethod == "smtp") {
	$mail->SetFrom($return_path, $sitename);
	$mail->AddReplyTo($return_path,$sitename);
	$mail->Subject = "[".$sitename."] Email Verification";
	$Message = eregi_replace("[\]",'',$message);
    $mail->Body = $Message;
    $mail->AddAddress($email);
	$mail->Send();
	}
	else {
	$headers .= "Reply-To: <$return_path>\n";
	$headers .= "X-Sender: <$return_path>\n";
	$headers .= "X-Mailer: PHP5\n";
	$headers .= "X-Priority: 3\n";
	$headers .= "Return-Path: <$return_path>\n";
	mail($email, "[".$sitename."] Email Verification", wordwrap(stripslashes($message)),"From: $return_path", "-f $return_path");
	}
    	echo "<br><center><p>A verification email has been sent to ".$email.".</p></center></td></tr></table>";

include "../footer.php";
mysql_close($dblink);
?>