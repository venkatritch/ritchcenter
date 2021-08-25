<?php
/* Recommend this website to a friend
Script Version 0.2, copyright RRWH.com 2005.

This script is distributed under the licence conditions on the website http://rrwh.com/scripts.php

You only need to modify 3 variables - the $my_email, $safe_domains and $message

To use this script, simply set the 3 variables, upload it to your website and on the page you wish it to appear
add the following php code 
	<?php include("contact2.php"); ?>
This script creates the form, and outputs a message on sucess.
*/

// your email address - so you get copies - in case of abuse.
$my_email = 'venkat_ritch@yahoo.com';
// set the safe domains to your own domain names
$safe_domains = array('www.ritchcenter.com', 'ritchcenter.com');



// check if we are being submitted, and if we are, process it and output a message
if ((isset($_POST['email_friend'])) && ($_POST['email_friend'] == 'SEND') ) {
$headd=$_POST['tellemail'];
	$headers = "From: <$headd>\r\n";
mail($my_email, 'Hi', $_POST['tellfriend'], $headers);
$TEL=$_POST['tel'];
$MSG="Thanks for your message. Your Tel No:<$TEL>\r\n";
mail($_POST['tellemail'], $MSG, 'We appreciate if you can forward our site link to your Friends', $my_email);

	addslashes(extract($_POST));
	// this is the message you send when the form is completed.
	// DO NOT remove any words enclosed in "< >" as they get substituted for form values

// No need to modify ANYTHING below.

	// Check REFERER to minimise Abuse
	$i = count($safe_domains) - 1;
	while ($i >= 0) {
		if (strpos($_SERVER['HTTP_REFERER'], "http://$safe_domains[$i]") === 0) {
		$safe = "yes";
		}
	$i--;
	}
	// make sure form fields have been filled in, name is optional
	if ( ($safe == "yes") && (($tellemail != '' ) || ($tellfriend != '' ))) {
		$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
		$sendervalid = 'Nope';
		$rxvalid = 'Nope';
		if (eregi($regexp, $tellemail)){
			$sendervalid = 'Yes';
		} 
		if (eregi($regexp, $tellfriend)){
			$rxvalid = 'Yes';
		}
		if (($sendervalid == 'Yes') && ($rxvalid == 'Yes')) {
			// valid to and from address
			$good = 'yes';
		}
	}
	
	if ($good =='yes') {
		// form data validated.
		$subject = 'Recommended Website';
		$headers = "From <$tellemail>\r\n";
		$fmtMail = str_replace("<tellfriend>", $tellfriend, $message);
		$fmtMail = str_replace("<tellname>", $tellname, $fmtMail);
		$fmtMail = str_replace("<tellemail>", $tellemail, $fmtMail);
		$frommail = $_SERVER["REMOTE_ADDR"];
		$fmtMail2 = "$fmtMail \n\n Remote IP is $frommail \n\n\n";
		
		//echo "$tell_friend - $subject \n";
		// send message
		//mail($tellfriend, $subject, $fmtMail, $headers, "-f$tellemail");
		// send message to admin address
		mail($my_email, $subject, $fmtMail2, $headers, "-f$tellemail");
		// output message
		echo "Message sent!\n<br>\n";
	}
}
?>
