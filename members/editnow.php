<?php
include "../control.php";
include "../header.php";
include "../style.php";

if (isset($_POST['Userid'])) {
   $userid = $_POST['Userid'];
}
if (isset($_POST['Password'])) {
   $password = $_POST['Password'];
}
if (isset($_POST['Password2'])) {
   $password2 = $_POST['Password2'];
}
if (isset($_POST['Name'])) {
   $name = $_POST['Name'];
}
if (isset($_POST['lastname'])) {
   $lastname = $_POST['lastname'];
}
if (isset($_POST['PaypalEmail'])) {
   $paypalemail = $_POST['PaypalEmail'];
}
if (isset($_POST['PayzaEmail'])) {
   $payzaemail = $_POST['PayzaEmail'];
}
if (isset($_POST['EgoPayAccount'])) {
   $egopayaccount = $_POST['EgoPayAccount'];
}
if (isset($_POST['PerfectMoneyAccount'])) {
   $perfectmoneyaccount = $_POST['PerfectMoneyAccount'];
}
if (isset($_POST['OKPayAccount'])) {
   $okpayaccount = $_POST['OKPayAccount'];
}
if (isset($_POST['SolidTrustPayAccount'])) {
   $solidtrustpayaccount = $_POST['SolidTrustPayAccount'];
}
if (isset($_POST['MoneybookersEmail'])) {
   $moneybookersemail = $_POST['MoneybookersEmail'];
}
if (isset($_POST['oldemail'])) {
   $oldemail = $_POST['oldemail'];
}
if (isset($_POST['ContactEmail'])) {
   $contactemail = $_POST['ContactEmail'];
}
if (isset($_POST['update_country'])) {
   $update_country = $_POST['update_country'];
}

if($userid<>"") {

    include("navigation.php");
    include("../banners.php");

	// errorchecking first:

    if (empty($userid)) {
        echo "Userid  field is empty.";
        exit;
        }
    if (empty($name)) {
        echo "Name field is empty do not match.";
        exit;
        }

    if ($password != $password2 ) {
        echo "Passwords do not match.";
        exit;
        }
    if ($solo == on) {
    	$solo = 1;
    }
    else {
     	$solo = 0;
    }
    if ($referral == on) {
    	$referral = 1;
    }
    else {
     	$referral = 0;
    }
	
	if(strpos($contactemail, "@")) {

		if ($password != "")
		{
	    $query = "update members set ";
	    $query .= "pword='$password',name='$name',lastname='$lastname',country='$update_country',contact_email='$contactemail',subscribed_email='$contactemail',paypal_email='$paypalemail',payza_email='$payzaemail',egopay_account='$egopayaccount',perfectmoney_account='$perfectmoneyaccount',okpay_account='$okpayaccount',solidtrustpay_account='$solidtrustpayaccount',moneybookers_email='$moneybookersemail',solos='$solo'";
	    $query .= "where userid='$userid'";
		$_SESSION["pw"] = $password;
		}
		else
		{
	    $query = "update members set ";
	    $query .= "name='$name',lastname='$lastname',country='$update_country',contact_email='$contactemail',subscribed_email='$contactemail',paypal_email='$paypalemail',payza_email='$payzaemail',egopay_account='$egopayaccount',perfectmoney_account='$perfectmoneyaccount',okpay_account='$okpayaccount',solidtrustpay_account='$solidtrustpayaccount',moneybookers_email='$moneybookersemail',solos='$solo'";
	    $query .= "where userid='$userid'";
		}
	    $result = mysql_query ($query)
	         or die ("Update failed, please try again.");
	    if ($oldemail != $contactemail) {

			if ($mailermethod == "smtp") {
			include "../SMTP.php";
			}

	        $query2 = "update members set verified=0 where userid='$Userid'";
	        $result2 = mysql_query ($query2);
			$message = "Dear ".$name.",\n\n"
	               ."Please verify your email address by clicking this link ".$domain."/verify.php?userid=".$userid."&email=".$contactemail."\n\n"
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
				$mail->AddAddress($contactemail);
				$mail->Send();
				}
				else {
				$headers .= "From: $sitename<$return_path>\n";
				$headers .= "Reply-To: <$return_path>\n";
				$headers .= "X-Sender: <$return_path>\n";
				$headers .= "X-Mailer: PHP5\n";
				$headers .= "X-Priority: 3\n";
				$headers .= "Return-Path: <$return_path>\nContent-type: text/html; charset=iso-8859-1\n";
				@mail($contactemail, $subject, $message, $headers, "-f $return_path");
				}

	        echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";
	    	echo "<br><center><p>Your email address has changed. A verification email has been sent to your new address, please click the verification link.</p></center>";
	    }
	     	echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";
	    	echo "<br><center>Profile successfully updated.</center>";
		
		
	} else {
	
		echo "<br><br><center>You cannot delete your email address altogether.  You can only change it to a new email address.<br>  
If you cannot verify the new address, you will not be able to use the website.
<br>
  To go on vacation and receive no solo ads while you
are away, click on Go on vacation below.<br><br>";
?>
			    <form method="POST" action="vacation.php?action=go">
                <input type="submit" value="Go on vacation">
	        	</form>
<?	
		echo "</center>";
	}
    echo "</font></td></tr></table>";
	
}
else
  { ?>

  <center><font size=3 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>"><p><b><center>You must be logged in to access this site. Please <a href="../index.php">click here</a> to login.</p></b></font></center>

  <? }

include "../footer.php";
mysql_close($dblink);
?>