<?php
include "../control.php";
include "../header.php";

$userid = $_POST['userid'];
$email = $_POST['email'];

if($userid != "") {

    include("navigation.php");
    include("../banners2.php");
	if ($mailermethod == "smtp") {
	include "../SMTP.php";
	}
		$message = "Hello,\n\n"
               ."Please verify your email address by clicking this link ".$domain."/verify.php?userid=".$userid."&email=".$email."\n\n"
	           ."Thank you!\n\n\n"
	           .$sitename." Admin\n"
	           ."".$adminemail."\n\n\n\n";

		$subject = $sitename." Email Verification";
		$return_path = $adminemail;
		if ($mailermethod == "smtp") {
		$mail->SetFrom($return_path, $sitename);
		$mail->AddReplyTo($return_path,$sitename);
		$mail->Subject = $subject;
		$Message = eregi_replace("[\]",'',$message);
		$mail->Body = $Message;
		$mail->AddAddress($email);
		$mail->Send();
		}
		else {
		$headers .= "From: $sitename<$return_path>\n";
		$headers .= "Reply-To: <$return_path>\n";
		$headers .= "X-Sender: <$return_path>\n";
		$headers .= "X-Mailer: PHP5\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "Return-Path: <$return_path>\n";
		@mail($email, $subject, $message, $headers, "-f $return_path");
		}

        echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";
    	echo "<br><center><p>A verification email has been sent to ".$email.", please click the verification link.</p></center>";
    	echo "</font></td></tr></table>";
    }
else
  { ?>

  <font size=3 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>"><p><b><center>You must be logged in to access this site. Please <a href="../index.php">click here</a> to login.</p></b></font>

  <? }

include "../footer.php";
mysql_close($dblink);
?>