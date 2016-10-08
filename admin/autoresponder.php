<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";
    	?>
		<!-- tinyMCE -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
		<script language="javascript" type="text/javascript" src="../jscripts/tiny_mce/tiny_mce.js"></script>
		<script language="javascript" type="text/javascript">
		tinyMCE.init({
			mode : "textareas",
			theme : "advanced",
			extended_valid_elements : "a[href|target|name],font[face|size|color|style],span[class|align|style]"
		});
		</script>
		<!-- /tinyMCE --> 		
		<table>
      	<tr>
        <td width="15%" valign=top><br>
        <? include("adminnavigation.php"); ?>
        </td>
        <td valign="top" align="center"><br><br> <?
    	echo "<font size=2 face='$fonttype' color='$fontcolour'><p><center>";

        echo "<center><H2>Autoresponder</H2></center>";
		
		if($_POST['action'] == "update") {

					$sendingemail = $_POST['sendingemail']; // select box
					$senderemail = $_POST['senderemail']; // text field
					if ($sendingemail == 'other'){
						$replyemail = $senderemail;
					}
					else {
						$replyemail = $sendingemail;
					}

				mysql_query("UPDATE autoresponder SET replyemail='$replyemail', days = '".$_POST['days']."', subject = '".$_POST['subject']."', message = '".$_POST['message']."', memtype = '".$_POST['memtype']."' WHERE id='".$_POST['id']."'");

				echo "<font color=red>The autoresponder has been updated.</font><br><br>";
			
		} else if($_POST['action'] == "add") {
		
					$sendingemail = $_POST['sendingemail']; // select box
					$senderemail = $_POST['senderemail']; // text field
					if ($sendingemail == 'other'){
						$replyemail = $senderemail;
					}
					else {
						$replyemail = $sendingemail;
					}

				mysql_query("INSERT INTO autoresponder SET replyemail='$replyemail', days = '".$_POST['days']."', subject = '".$_POST['subject']."', message = '".$_POST['message']."', memtype = '".$_POST['memtype']."'");

				echo "<font color=red>The autoresponder has been added.</font><br><br>";
			
		} else if($_POST['action'] == "delete") {
		
				mysql_query("DELETE FROM autoresponder WHERE id='".$_POST['id']."'");

				echo "<font color=red>The autoresponder has been deleted.</font><br><br>";
			
		}
		
		
		$sql = mysql_query("SELECT * FROM autoresponder");
		if(@mysql_num_rows($sql)) {
			echo "<table width=\"50%\" border=\"1\" cellpadding=\"5\"><tr><th>Subject</th><th>Days</th><th>Members</th><th>Action</th></tr>";
			
			while($each = mysql_fetch_array($sql)) {
				echo "<tr><td>".$each['subject']."</td><td align=\"center\">".$each['days']."</td><td align=\"center\">".$each['memtype']."</td><td align=\"center\"><form method=\"post\"><input type=\"hidden\" name=\"id\" value=\"".$each['id']."\"><input type=\"hidden\" name=\"action\" value=\"edit\"><input type=\"submit\" value=\"Edit\"></form><form method=\"post\"><input type=\"hidden\" name=\"id\" value=\"".$each['id']."\"><input type=\"hidden\" name=\"action\" value=\"delete\"><input type=\"submit\" value=\"Delete\"></form></td></tr>";
			}
			
			
			echo "</table><br><br>";
		}
		
		
			
		
		if($_POST['action'] == "edit") {
			$sql = mysql_query("SELECT * FROM autoresponder WHERE id='".$_POST['id']."' LIMIT 1");
			
			if(@mysql_num_rows($sql)) {
			$email = mysql_fetch_array($sql);
		?>
			<h2>Edit</h2>
		
		  <form method="POST" action="autoresponder.php">
		  <input type="hidden" name="action" value="update">
		  <input type="hidden" name="id" value="<? echo $email['id']; ?>">
		  <br>
		  You can use the tags: ~fname~ , ~userid~ & ~email~ <br><br>
		  <table>
		  <tr><td>Reply To:</td><td><select id="sendingemail" name="sendingemail" style="width:100px;"><option value="<?php echo $bounceemail ?>" <?php if ($email['replyemail'] == $bounceemail) { echo "selected"; } ?>>Bounce Email - <?php echo $bounceemail ?></option><option value="<?php echo $adminemail ?>"  <?php if ($email['replyemail'] == $adminemail) { echo "selected"; } ?>>Admin Support Email - <?php echo $adminemail ?></option><option value="other" <?php if (($email['replyemail'] != $bounceemail) and ($email['replyemail'] != $adminemail)) { echo "selected"; } ?>>Other:</option></select>&nbsp;&nbsp;<span id="otheremail" style="display:<?php if (($email['replyemail'] == $bounceemail) and ($email['replyemail'] == $adminemail)) { echo "none"; } else { echo "inline"; } ?>;"><input type="text" id="senderemail" name="senderemail" maxlength="255" value="<?php echo $email['replyemail']; ?>"></span></td></tr>
		   <tr><td>Subject:</td><td><input type="text" name="subject" value="<? echo $email['subject']; ?>" size="70"></td></tr>
		   <tr><td>Send after:</td><td><input type="text" name="days" value="<? echo $email['days']; ?>" size="4"> days</td></tr>
		   <tr><td>Send to:</td><td><input type="radio" name="memtype" value="PRO"<? if($email['memtype'] == "PRO") echo " CHECKED"; ?>> PRO members <input type="radio" name="memtype" value="JV Member"<? if($email['memtype'] == "JV Member") echo " CHECKED"; ?>> JV members  <input type="radio" name="memtype" value="SUPER JV"<? if($email['memtype'] == "SUPER JV") echo " CHECKED"; ?>> SUPER JV members <input type="radio" name="memtype" value="All"<? if($email['memtype'] == "All") echo " CHECKED"; ?>> All</td></tr>
		   <tr><td>Message:</td><td><textarea name="message" cols="65" rows="30"><? echo $email['message']; ?></textarea></td></tr>
		  <tr><td colspan="2" align="center"><input type="submit" value="Update"></td></tr>
		  </table>
          </form>
		
		
		
		<?
			} else echo "<font color=red>That autoresponder doesn't exist anymore.</font><br><br>";
		} else {
		?>
		
			<h2>Add</h2>
		  <form method="POST" action="autoresponder.php">
		  <input type="hidden" name="action" value="add">
		  <br>
		  You can use the tags: ~fname~ , ~userid~ & ~email~ <br><br>
		  <table>
		  <tr><td>Reply To:</td><td><select id="sendingemail" name="sendingemail" style="width:100px;"><option value="bounceemail">Bounce Email - <?php echo $bounceemail ?></option><option value="adminemail">Admin Support Email - <?php echo $adminemail ?></option><option value="other">Other:</option></select>&nbsp;&nbsp;<span id="otheremail" style="display:none;"><input type="text" id="senderemail" name="senderemail" maxlength="255"></span></td></tr>
		   <tr><td>Subject:</td><td><input type="text" name="subject" value="" size="70"></td></tr>
		   <tr><td>Send after:</td><td><input type="text" name="days" value="" size="4"> days</td></tr>
		   <tr><td>Send to:</td><td><input type="radio" name="memtype" value="PRO"> PRO members <input type="radio" name="memtype" value="JV Member"> JV members  <input type="radio" name="memtype" value="SUPER JV"> SUPER JV members <input type="radio" name="memtype" value="All" CHECKED> All</td></tr>
		   <tr><td>Message:</td><td><textarea name="message" cols="65" rows="30"></textarea></td></tr>
		  <tr><td colspan="2" align="center"><input type="submit" value="Update"></td></tr>
		  </table>
          </form>
		
		<?
		}
		
?>
<script language="javascript" type="text/javascript">
$('#sendingemail').on('change', function() {
	if ($(this).val() === 'other') {
		$('#otheremail').show();
	}
	else {
		$("#otheremail").hide();
	}
{
}
});
</script>
<?php
        echo "<br><br></td></tr></table>";
include "../footer.php";

?>