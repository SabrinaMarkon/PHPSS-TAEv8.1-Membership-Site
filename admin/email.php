<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";
    	?>
		<table>
      	<tr>
        <td width="15%" valign=top><br>
        <? include("adminnavigation.php"); ?>
        </td>
        <td valign="top" align="center"><br><br> <?
    echo "<font size=2 face='$fonttype' color='$fontcolour'><p><center>";
     ?>
    <center>
    <p><b>Contact all active members in the database.</b></p>
    <p>Merge fields you may use in the subject or message body:</p>
    <br>~fname~
    <br>~userid~
    <br>~email~
    <br><br>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
				<!-- tinyMCE -->
		<script language="javascript" type="text/javascript" src="../jscripts/tiny_mce/tiny_mce.js"></script>
		<script language="javascript" type="text/javascript">
			tinyMCE.init({
				theme : "advanced",
				mode : "textareas",
				//save_callback : "customSave",
				content_css : "advanced.css",
				extended_valid_elements : "a[href|target|name],font[face|size|color|style],span[class|align|style]",
				theme_advanced_toolbar_location : "top",
				plugins : "table",
				theme_advanced_buttons3_add_before : "tablecontrols,separator",
				//invalid_elements : "a",
				theme_advanced_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1", // Theme specific setting CSS classes
				//execcommand_callback : "myCustomExecCommandHandler",
				debug : false
			});

			// Custom event handler
			function myCustomExecCommandHandler(editor_id, elm, command, user_interface, value) {
				var linkElm, imageElm, inst;

				switch (command) {
					case "mceLink":
						inst = tinyMCE.getInstanceById(editor_id);
						linkElm = tinyMCE.getParentElement(inst.selection.getFocusElement(), "a");

						if (linkElm)
							alert("Link dialog has been overriden. Found link href: " + tinyMCE.getAttrib(linkElm, "href"));
						else
							alert("Link dialog has been overriden.");

						return true;

					case "mceImage":
						inst = tinyMCE.getInstanceById(editor_id);
						imageElm = tinyMCE.getParentElement(inst.selection.getFocusElement(), "img");

						if (imageElm)
							alert("Image dialog has been overriden. Found image src: " + tinyMCE.getAttrib(imageElm, "src"));
						else
							alert("Image dialog has been overriden.");

						return true;
				}

				return false; // Pass to next handler in chain
			}
		</script>
		<!-- /tinyMCE -->  	
	

	<form method="POST" action="emailnow.php"><br>
	Members that joined on or after that date (YYYY-MM-DD):<br><input type="text" name="ADate" value=""><br>
	Specific member:<br><input type="text" name="Member" value="<? echo $_GET['Member']; ?>">
	<br>
	<input type="checkbox" name="memtype[]" value="PRO"> <?php echo $lowerlevel ?> members <input type="checkbox" name="memtype[]" value="JV Member"> <?php echo $middlelevel ?> members <input type="checkbox" name="memtype[]" value="SUPER JV"> <?php echo $toplevel ?> members
	<br><br>
	Reply To:&nbsp;<select id="sendingemail" name="sendingemail" style="width:200px;"><option value="<?php echo $bounceemail ?>">Bounce Email - <?php echo $bounceemail ?></option><option value="<?php echo $adminemail ?>">Admin Support Email - <?php echo $adminemail ?></option><option value="other">Other:</option></select>&nbsp;&nbsp;<span id="otheremail" style="display:none;"><input type="text" id="senderemail" name="senderemail" maxlength="255"></span>
	<br><br>
	Subject:<br><input type="text" name="Subject" value=""><br>
	Message:<br><textarea name="Message" rows="15" cols="60"></textarea><br>
    <input type="submit" value="Send">
	</form>
	</center>
    <br><br>
	<?   echo "</td></tr></table>";
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
include "../footer.php";
mysql_close($dblink);
?>