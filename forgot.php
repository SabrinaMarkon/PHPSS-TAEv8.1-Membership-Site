<?
session_start();
include "config.php";
include "header.php";
include "style.php";
function ae_gen_password($syllables = 3, $use_prefix = false)
{
    // Define function unless it is already exists
    if (!function_exists('ae_arr'))
    {
        // This function returns random array element
        function ae_arr(&$arr)
        {
            return $arr[rand(0, sizeof($arr)-1)];
        }
    }

    // 20 prefixes
    $prefix = array('aero', 'anti', 'auto', 'bi', 'bio',
                    'cine', 'deca', 'demo', 'dyna', 'eco',
                    'ergo', 'geo', 'gyno', 'hypo', 'kilo',
                    'mega', 'tera', 'mini', 'nano', 'duo');

    // 10 random suffixes
    $suffix = array('dom', 'ity', 'ment', 'sion', 'ness',
                    'ence', 'er', 'ist', 'tion', 'or'); 

    // 8 vowel sounds 
    $vowels = array('a', 'o', 'e', 'i', 'y', 'u', 'ou', 'oo'); 

    // 20 random consonants 
    $consonants = array('w', 'r', 't', 'p', 's', 'd', 'f', 'g', 'h', 'j', 
                        'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'qu');

    $password = $use_prefix?ae_arr($prefix):'';
    $password_suffix = ae_arr($suffix);

    for($i=0; $i<$syllables; $i++)
    {
        // selecting random consonant
        $doubles = array('n', 'm', 't', 's');
        $c = ae_arr($consonants);
        if (in_array($c, $doubles)&&($i!=0)) { // maybe double it
            if (rand(0, 2) == 1) // 33% probability
                $c .= $c;
        }
        $password .= $c;
        //

        // selecting random vowel
        $password .= ae_arr($vowels);

        if ($i == $syllables - 1) // if suffix begin with vovel
            if (in_array($password_suffix[0], $vowels)) // add one more consonant 
                $password .= ae_arr($consonants);

    }

    // selecting random suffix
    $password .= $password_suffix;

    return $password;
}
?>
<br><br><br>
<center>
<b><font face="Tahoma" size="3">Reset your password?</font></b><br><br>
<?php
if($_POST['email']) {

	if ($mailermethod == "smtp") {
	include "SMTP.php";
	}
	$sql = mysql_query("SELECT * FROM members WHERE contact_email = '".$_POST['email']."'");
	if(@mysql_num_rows($sql)) {
	
		$user = mysql_fetch_array($sql);
		$password = $user['pword'];

		$message .= "Here's the information you requested\n\nYour Userid: ".$user['userid']."\nYour Password: ".$password."\n\n";
		$message .= "Log into your members area\n".$domain."/memberlogin.php\n\n\n$sitename Admin\n$adminemail\n";

		$return_path = $adminemail;
		if ($mailermethod == "smtp") {
		$mail->SetFrom($return_path, $sitename);
		$mail->AddReplyTo($return_path,$sitename);
		$mail->Subject = "Reset password";
		$Message = eregi_replace("[\]",'',$message);
		$mail->Body = $Message;
		$mail->AddAddress($user['contact_email']);
		$mail->Send();
		}
		else {
		$headers .= "From: $sitename<$return_path>\n";
		$headers .= "Reply-To: <$return_path>\n";
		$headers .= "X-Sender: <$return_path>\n";
		$headers .= "X-Mailer: PHP5\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "Return-Path: <$return_path>\n";
		@mail($user['contact_email'], "Reset password", $message,$headers, "-f $return_path");
		}		
		echo "<font color=\"red\">Your temporary password has been sent to your contact email.</font><br><br>";
	} else {
		echo "<font color=\"red\">Invalid email.</font><br><br>";
	}


}

?>

<form action="forgot.php" method="post">
Your Contact Email Address:<br>
<input type="text" name="email"><br><br>
<input type="submit" value="Recover my password">
</form>

</center>

  
<br><br><br> 

  

<? 

include "footer.php";
mysql_close($dblink);
?>
