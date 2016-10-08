#!/usr/local/bin/php -q
<?php
include "config.php";
######### COPYRIGHT 2015 SABRINA MARKON PHPSITESCRIPTS.COM. ALL RIGHTS RESERVED. ##########################
// Reading in the email
$fd = fopen("php://stdin", "r");
while (!feof($fd)) {
  $email .= fread($fd, 1024);
}
fclose($fd);

// Parsing the email
$lines = explode("\n", $email);
$stillheaders=true;
for ($i=0; $i < count($lines); $i++) {
  if ($stillheaders) {
    // this is a header
    $headers .= $lines[$i]."\n";

    // look out for special headers
    if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
      $subject = $matches[1];
    }
    if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
      $from = $matches[1];
    }
    if (preg_match("/^To: (.*)/", $lines[$i], $matches)) {
      $to = $matches[1];
    }
  } else {
    // not a header, but message
    #break;
    #Optionally you can read out the message also, instead of the break:
    $message .= $lines[$i]."\n";
  }

  if (trim($lines[$i])=="") {
    // empty line, header section has ended
    $stillheaders = false;
  }
}
$return_path = "user-".$useridq."-".$bounceemail;
////////////////////////////////////////////////////////////////////////////
## METHOD 1 ##
// looks like: user-12345-bounce@yoursite.com
list($part1,$dum1) = explode("-$bounceemail", trim($to) );
// looks now like: user-12345
list($dum2,$part2) = explode("user-", $part1);
// looks now like: 12345
// $part2 now contains the user id "12345" in the example
$bounceduserid = $part2;
////////////////////////////////////////////////////////////////////////////
## METHOD 2: comment out the two below if the server can handle the first method.
## (sending email has userid and sitename as part of the email address)
$bouncedemail = trim($to);
$bounceduserid = trim($bounceduserid);
////////////////////////////////////////////////////////////////////////////

if ($bounceduserid != "")
{
$bouncemessage = addslashes($message);
$q1 = "insert into bounces (userid,email,bouncedate,bounceerror) values ('".$bounceduserid."','".$bouncedemail."',NOW(),'".$bouncemessage."')";
$r1 = mysql_query($q1);
$q2 = "select * from bounces where userid='".$bounceduserid."' or email='".$bouncedemail."'";
$r2 = mysql_query($q2);
$rows2 = mysql_num_rows($r2);
if ($rows2 >= $bouncesmax)
	{
	if ($bounceconsequence == "vacation")
		{
		$q2 = "update members set vacation=1, vacation_date = '".time()."' where userid='".$bounceduserid."' or subscribed_email='".$bouncedemail."' or contact_email='".$bouncedemail."'";
		$r2 = mysql_query($q2);
		}
	if ($bounceconsequence == "deletemember")
		{
		$q2 = "delete from members where userid='".$bounceduserid."' or subscribed_email='".$bouncedemail."' or contact_email='".$bouncedemail."'";
		$r2 = mysql_query($q2);
		}
	}
}
$q3 = "delete from bounces where bouncedate<='".(time()-7*24*60*60)."'";
$r3 = mysql_query($q3);
mysql_close();
return true;
?>