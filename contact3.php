<?php
/* Licence 

To use: simply put <?php include("contact2.php");?> in any php web
page to both show and process the form.
 
*** Start of configuration *** 

You will need to customise these 5 variables before you upload this to your website

*/

// what servers are allowed to use this to send you email
$safe_domains = array('ritchcenter.com', 'www.ritchcenter.com');
// A thank you message when someone fills in the form
$thankyou = 'Your message has been sent and we will get back to you shortly';
// Your email address goes here the form is sent to this address
$recipient = "ritchcenter@yahoo.com";
// The subject for the email sent to you
$subject = 'Messages sent from website';
// A word that checks that the form was filled in from your website to make it harder for abuse
$doublecheck = 'ritch123';
// The template for the message that gets sent to you. - do not remove the <from> and <message> 
// as these get replaced with the form details
$form = '

Hello,

<from> has visited your website at
http://www.ritchcenter.com/elearn/contact.php and filled in the contact form.

_________Start Message_________

<message>

__________End Message__________

';

/* *** End of configuration *** */


$me = $_SERVER['PHP_SELF'];

// Check if we are submitting the form

if(isset($_POST['submit'])) {

// Check REFERER to minimise Abuse
$i = count($safe_domains) - 1;
while ($i >= 0) {
  if (strpos($_SERVER['HTTP_REFERER'], "http://$safe_domains[$i]")=== 0) {
    $safe = "yes";
  }
$i--;
}
if ($safe != "yes") {
  echo "Error - You may not POST to this script from your previous
location " . $_SERVER['HTTP_REFERER'] . "<br />&nbsp;<br />\n";
  $baddata = 'BAD';
}
// We have a valid REFERER - so continue

// Now we want to ensure that a valid email address has been entered
$regexp =
"^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
$email = $_POST["email"];

$isvalid = 'Invalid_Email_address';
if (eregi($regexp, $email)){
        // Valid looking address
        $isvalid = 'Yes';
} 

if ($isvalid != 'Yes') {
        echo "Error - You entered an invalid email address - I could never
contact you at $email <br />&nbsp;<br />\n";
    $baddata = 'BAD';
}

// We want to make sure a message was entered!

$amessage = $_POST["message"];

if ($amessage == '') {
        echo "Error - I need a message to send - you did not enter a
message! <br />&nbsp;<br />\n";
    $baddata = 'BAD';
}
// print a thank you message
        if ($baddata != 'BAD'){
        print "$thankyou \n";
        }
}

// Process the submitted form or display the form to be filed in

if(isset($_POST['submit']) && ($baddata != 'BAD')) {

                $from = $_POST["email"];
                $headers = "From: <$from>\r\n";
                //$fmtMail = implode("", $form);
                $fmtMail = str_replace("<message>", $_POST["message"], $form);
                $fmtMail = str_replace("<from>", $_POST["name"], $fmtMail);
                $frommail = $_SERVER["REMOTE_ADDR"];
                $fmtMail1 = "$fmtMail \nThe message came from $frommail\n\n\n";

// Additional check that the form was sent from your website.
                if ($_POST["send"] == "$doublecheck") {
// send a message to you, that includes the senders IP address
                        mail($recipient, $subject, $fmtMail1, $headers, "-f$from");
// Send a copy of the message to the email address from the form
                        mail($from, $subject, $fmtMail, $headers, "-f$from");
                          }
} else {

// Present the form to be filled out

print <<< form

Please use this form to Contact Us. You will get a copy in your Email.

<form action ="$me" method="post">
Your Name: <input type="text" size="30" name="name" /><br />
Your Email: <input type="text" size="30" name="email" /><br />
Your Message:<br />
<textarea rows="10" cols="50" name="message"></textarea><br />
<input type="submit" value="submit" name="submit" /> $linkback <br />
<input type="hidden" name="send" value="$doublecheck" />
</form>
form;
}
?>