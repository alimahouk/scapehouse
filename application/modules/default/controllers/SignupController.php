<?php

class SignupController extends Zend_Controller_Action {

    public function init () {
        /* Initialize action controller here */
    }

    public function indexAction () { 

        // If user has a session and is logged in he has no bussiness being here ... REDIRECT to home.
        if (Zend_Auth::getInstance()->hasIdentity()) {

            $sessionContent = Model_StaticFunctions::sessionContent();
            $this->_redirect('/'.$sessionContent->username.'/home');
        }
        // Set the page class and head title>>> Important if you want the page to load properly useing css.


        $this->view->headTitle('Sign up for Scapehouse!', 'SET');
        $this->view->pageClass = "signup";

        // Signup validation processes .
        // Instantiate signup class.


        $signupProcessor = new Model_Signup();
        $userid = $signupProcessor->signup();


        if ($userid) {

            // Send email
            $hash = Model_StaticFunctions::salted_sha1(str_shuffle("abcdefghijklmnopqrstuv").$signup->firstname.$signup->passwordConfirm.$signup->captcha.$signup->username.str_shuffle("1234567890-"));

            $verifyTable = new Model_DbTable_Verify();
            $verifyTable->save($userid,$hash);

            require_once '../phpmailer/class.phpmailer.php';
            require_once '../phpmailer/class.smtp.php';

            $mail = new PHPMailer();

/*            $mail->IsSMTP();
            $mail->SMTPAuth = true; // enable SMTP authentication
            $mail->SMTPSecure = "tls"; // sets the prefix to the server
            $mail->Host = "smtp.live.com"; // sets HOTMAIL as the SMTP server
            $mail->Port = 25; // alternate is "26" - set the SMTP port for the HOTMAIL server
            $mail->Username = "akay64@live.com"; // HOTMAIL username
            $mail->Password = "akep6930pdOspx64"; // HOTMAIL password
*/
/*            $mail->IsSMTP();
            $mail->SMTPAuth = true; // enable SMTP authentication
           // $mail->SMTPSecure = "tls"; // sets the prefix to the server
            $mail->Host = "mail.scapehouse.com"; // sets HOTMAIL as the SMTP server
            $mail->Port = 26; // alternate is "26" - set the SMTP port for the HOTMAIL server
            $mail->Username = "postmaster+scapehouse.com"; // HOTMAIL username
            $mail->Password = "akrdOsIp8700nm45admin_POSTMASTER"; // HOTMAIL password

            $mail->FromName = "Scapehouse";
            $mail->From = "postmasterscapehouse.com";
            $mail->AddAddress($_POST['email']);
*/
        $mail->IsSMTP(); // telling the class to use SMTP
        
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = "tls"; // sets the prefix to the server
        $mail->Host = "smtp.live.com"; // sets HOTMAIL as the SMTP server
        $mail->Port = 25; // alternate is "26" - set the SMTP port for the HOTMAIL server
        $mail->Username = "YOUR_EMAIL@live.com"; // HOTMAIL username
        $mail->Password = "YOUR_EMAIL_PASSWORD"; // HOTMAIL password

        $mail->FromName = "Scapehouse";
        $mail->From = "scapehouse@gmail.com";
	
	$mail->AddAddress($_POST['email']);
	
            $mail->IsHTML(true);
            $mail->Subject = "Verify your email address.";
            $mail->Body = <<<MAIL
   <div lang="en">
<div style="padding:8px;border:1px solid #ccc;background-color:#b7f1b5"><div>
<table width="100%" style="border:1px solid #00ad00;background-color:#fff;color:#333;">
	<tbody>
		<tr>
			<td width="100%" style="margin:0;padding:0;border:0">
				<div style="padding:8px 0 0 8px;background-color:#f2f2f2;border-bottom:3px solid #00ad00;">
			      <a href="http://scapehouse.com" target="_blank"><img id="SHLogo" src="http://scapehouse.com/graphics/en/logos/scapehouse_364px.png" style="border:0;" width="364" height="75" /></a>
			    </div>
<div style="float:left;margin:0 8px 16px 0px;font-family:'lucida grande', lucida grande, helvetica, arial, sans-serif;padding:5px">
	<p style="font-size:14px;">Hi {$_POST['firstname']}!</p>
	<p style="font:14px 'lucida grande', lucida grande, helvetica, arial, sans-serif">We just need you to verify your email adddress. All you have to do is click the link below.</p>
	<p style="font-size:14px;"><a href="http://scapehouse.com/verify?accV=$hash" target="_blank" style="color:#6666d5;">http://scapehouse.com/verify?accV=$hash</a>
	<p style="font-size:12px;color:#919191;">(if clicking doesn't work, try copying and pasting it manually into your browser's address bar)</p>
	<p style="font-size:14px;">Thanks for trying Scapehouse!</p>
</div>
<br style="clear:both">
<p style="margin:0 5px 5px 5px;padding-top:13px;border-top:1px solid #ccc;font-family:'lucida grande',lucida grande,helvetica,arial,sans-serif;font-size:10px;font-style:normal;font-variant:normal;font-weight:normal;font-size-adjust:none;font-stretch:normal;line-height:18px;color:#919191">This message was automated, so please do not reply to it.</p>
        </td></tr>
      </tbody></table>
    </div>
  </div>
</div>

MAIL;

            $mail->Send();

            $session = Model_StaticFunctions::sessionContent();
            $this->_redirect('/'.$session->username.'/home?new=true');


        }

        $signup = $signup->signupErrors;

        $this->view->signupErrors = $signup;

    }

}

