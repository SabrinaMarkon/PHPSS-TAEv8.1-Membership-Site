<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";

$Subject =  $sitename." ".$_POST['Subject'];
$Message = $_POST['Message'];
$sendingemail = $_POST['sendingemail'];
$senderemail = $_POST['senderemail'];

    	?><table>
      	<tr>
        <td width="15%" valign=top><br>
        <? include("adminnavigation.php"); ?>
        </td>
        <td valign="top" align="center"><br><br> <?
    echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";

	if (empty($Message)) {
	   echo "Message field is empty, please click your browsers 'back' button.";
	   exit;
	}
	if ($sendingemail == 'other' && empty($senderemail)) {
		?><p>No reply email entered. Click <a href=email.php>here</a> to go back<p> <?
		include "../footer.php";
		exit;
	}
		
	if ($sendingemail == 'other'){
		$replyemail = $senderemail;
	}
	else {
		$replyemail = $sendingemail;
	}

	if($_POST['Member']) {
		echo "Sending to ".$_POST['Member'].". Please be patient...";
	    $query = "SELECT userid, name, pword, contact_email FROM members where status=1 and userid = '".trim($_POST['Member'])."' LIMIT 1";
		$result = mysql_query ($query)
		     or die ("Query failed");
	    $num_rows = @mysql_num_rows($result);
	} elseif($_POST['ADate'])  {
			echo "Sending to all active members that joined after ".$_POST['ADate'].". Please be patient...";
	    $query = "SELECT userid, name, pword, contact_email FROM members where status=1 and joindate >= '".trim($_POST['ADate'])."'";
		$result = mysql_query ($query)
		     or die ("Query failed");
	    $num_rows = @mysql_num_rows($result);
	
	} elseif($_POST['memtype']) {
		$memtype = $_POST['memtype'];
		if (is_array($memtype))
		{
		foreach ($memtype as $membershiplevel)
		{
		$howmany = 0;
	    $query = "SELECT distinct userid, name, pword, contact_email FROM members where status=1 and vacation=0 and memtype = '$membershiplevel'";
		$result = mysql_query ($query) or die(mysql_error());
	    $howmany = mysql_num_rows($result);
		echo "Sending to " .$howmany. " " . $membershiplevel . " members...<br>";
		$num_rows = $num_rows+$howmany;
		}
		}
		else
		{
	    $query = "SELECT distinct userid, name, pword, contact_email FROM members where status=1 and vacation=0 and memtype = '$memtype'";
		$result = mysql_query ($query) or die(mysql_error());
	    $num_rows = mysql_num_rows($result);
		echo "Sending to all ".$memtype." members...<br>";
		}
	} else {
		echo "Sending to all active members. Please be patient...";
	    $query = "SELECT distinct userid, name, pword, contact_email FROM members where status=1 and vacation=0";
		$result = mysql_query ($query)
		     or die ("Query failed");
	    $num_rows = mysql_num_rows($result);
	}

	$query1 = "SELECT * FROM pages WHERE name='Admin email footer ad'";
    $result1 = mysql_query ($query1);

    $line1 = mysql_fetch_array($result1);
    $htmlcode = $line1["htmlcode"];
	if($htmlcode) $htmlcode = "<br>$htmlcode<br><br>";	
	
	
    $line = "";

    $Subjectold = $Subject;
    $Messageold = $Message;
	
	if ($mailermethod == "smtp") {
	include "../SMTP.php";
	}
    while ($line = @mysql_fetch_array($result)) {
		$name = $line["name"];
        $userid = $line['userid'];
        $password = $line['pword'];
        $contactemail = $line["contact_email"];

        //reset values.
        $headers = "";
        $Subject = "Admin Mail ".$Subjectold;
        $Message = $Messageold;

        //replace the merge fields.
        $Subject = stripslashes($Subject);
		$Subject = str_replace("~userid~",$userid,$Subject);
        $Subject = str_replace("~name~",$name,$Subject);

        $Message = stripslashes($Message);
		$Message = str_replace("~userid~",$userid,$Message);
        $Message = str_replace("~name~",$name,$Message);
        $Message = str_replace("~email~",$contactemail,$Message);
		
		$name = trim($name);
		if(strpos($name, " ")) $firstname = substr($name , 0, strpos($name, " "));
		else $firstname = $name;
		
		$Message = str_replace("~fname~",$firstname,$Message);
        $Subject = str_replace("~fname~",$firstname,$Subject);
		
		$Message = "*********************<br>As a valued member of $sitename.<br> You have agreed to receive contact from us.<br> If you no longer want our messages, follow the instructions at the bottom.<br>*********************<br><br>".$Message."<br><br>*********************<br>".$htmlcode."<br>*********************<br>You are receiving this as you are a member of $sitename.<br> You can opt out of receiving emails only by deleting your account.<br>Please click below to delete your account.<br><br><a href=\"$domain/delete.php?userid=$userid&code=$password\">$domain/delete.php?userid=$userid&code=$password</a><br><br>$adminname - Admin<br>$sitename<br>$adminemail";

    $mail2 = clone $mail;
	if ($replyemail == $bounceemail) {
		$return_path = "user-".$userid."-".$bounceemail;
	}
	else {
		$return_path = $replyemail;
	}
	if ($mailermethod == "smtp") {
	$mail2->SetFrom($return_path, $sitename);
	$mail2->AddReplyTo($return_path,$sitename);
	$mail2->Subject = $Subject;
	$Message = eregi_replace("[\]",'',$Message);
    $mail2->MsgHTML($Message);
    $mail2->AddAddress($contactemail, $name);
	$mail2->Send();
	}
	else {
	$headers .= "From: $sitename<$return_path>\n";
	$headers .= "Reply-To: <$return_path>\n";
	$headers .= "X-Sender: <$return_path>\n";
	$headers .= "X-Mailer: PHP5\n";
	$headers .= "X-Priority: 3\n";
	$headers .= "Return-Path: <$return_path>\nContent-type: text/html; charset=iso-8859-1\n";
	mail($contactemail, $Subject, $Message,$headers, "-f$return_path")or print "Failed to send email.";
	}

	}

    echo "<br><br><center><b>Message sent to $num_rows members.</b></center>";
    ?>
    </td>
    </tr>
    </table>
	<?
include "../footer.php";
mysql_close($dblink);
?>