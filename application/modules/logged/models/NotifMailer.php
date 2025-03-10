<?php
class Logged_Model_NotifMailer {

    static function send($userid,$reciverid,$actionid,$subjid,$relType) {

        $usersTable = new Model_DbTable_Users();
        $sender = $usersTable->getUserById($userid);
        $reciver = $usersTable->getUserById($reciverid);
	
        require_once '../phpmailer/class.phpmailer.php';
        require_once '../phpmailer/class.smtp.php';
        
        $mail = new PHPMailer();

	$emails = array("dscapepostmaster@live.com","dscapepostmaster1@live.com","dscapepostmaster2@hotmail.com");
	$randomKey = rand(0,2);

        $mail->IsSMTP(); // telling the class to use SMTP
        
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = "ssl"; // sets the prefix to the server
        $mail->Host = "smtp.gmail.com"; // sets HOTMAIL as the SMTP server
        $mail->Port = 465; // alternate is "26" - set the SMTP port for the HOTMAIL server
        $mail->Username = "akay.productions@gmail.com"; // HOTMAIL username dscapepostmaster@live.com; dscapepostmaster1@live.com; dscapepostmaster2@live.com;
        $mail->Password = "YOUR_EMAIL_PASSWORD"; // Email password

        $mail->FromName = "Scapehouse";
        $mail->From = "akay.productions@gmail.com";
        //$mail->AddAddress("akay.productions@gmail.com");
	$mail->AddAddress($reciver["email"]);
	
        $mail->IsHTML(true);
	
	switch($relType){
	    case 1: // reply on scape
		$bodyText = "replied to your scape.";
		$goToLink = "<a href=\"http://scapehouse.com/{$reciver["username"]}/scape/{$subjid}\" style=\"color:#6666d5;text-decoration: none;\">&raquo;Go to scape</a>";
		$replyTable = new Logged_Model_DbTable_Reply();
		$actionContent = $replyTable->getReplyContentById($actionid);
		$actionContent =  strip_tags($actionContent);
		if(mb_strlen($actionContent,"UTF-8") > 300){
		$actionContent =  mb_substr($actionContent,0, 300,"UTF-8") . "...";
		}
		break;
	    case 4:// comment on reply
		$bodyText = "commented on your reply.";
		break;
	    case 7:// comment on photo
		$bodyText = "commented on your photo.";
		break;
	    case 10:// watch
		$bodyText = "is now watching you.";
		break;
	}
	
	$mail->Subject = "{$sender["fullname"]} $bodyText";
        
        $mail->Body = <<<MAIL
	
<div lang="en">
<div style="padding:8px;border:1px solid #ccc;background-color:#b7f1b5"><div>
<table width="100%" style="border:1px solid #00ad00;background-color:#fff;color:#333;">
	<tbody> 
		<tr>
			<td width="100%" style="margin:0;padding:0;border:0">
				<div style="padding:8px 0 0 8px;background-color:#f2f2f2;border-bottom:3px solid #00ad00;">
			      <a href="http://scapehouse.com" target="_blank"><img id="DSlogo" src="http://scapehouse.com/graphics/en/no_transp/logo_header_wide.png" style="border:0;" width="350" height="75" /></a>
			    </div>
<div style="float:left;margin:0 8px 16px 0px;font-family:'lucida grande', lucida grande, helvetica, arial, sans-serif;padding:5px">
	<p style="font-size:14px;">Hi {$reciver["firstname"]}!</p>

<h2 style="font:18px 'lucida grande', lucida grande, helvetica, arial, sans-serif; color: #2A2A2A !important;">
<a href="http://scapehouse.com/{$sender["username"]}/profile" target="_blank" style="color:#6666d5 !important;">{$sender["fullname"]}</a> <span style="color:#999;font-size:16px;font-weight:bold;">({$sender["username"]})</span> {$bodyText}</h2>

        <h4 style="color: #2A2A2A !important;">{$sender["firstname"]} wrote:</h4>
        <p>{$actionContent} {$goToLink}</p>
</div>
<br style="clear:both">
<p style="margin:0 5px 5px 5px;padding-top:13px;border-top:1px solid #ccc;font-family:'lucida grande',lucida grande,helvetica,arial,sans-serif;font-size:10px;font-style:normal;font-variant:normal;font-weight:normal;font-size-adjust:none;font-stretch:normal;line-height:18px;color:#919191">
If you don't want to receive these notifications anymore, you can <a href="http://scapehouse.com/{$sender["username"]}/console/notifs" target="_blank">turn them off</a>. To change your account settings, visit the <a href="http://scapehouse.com/{$sender["username"]}/console" target="_blank">Settings</a>. This message was automated, so please do not reply to it.</p>
        </td></tr>
      </tbody></table>
    </div>
  </div>
</div>

MAIL;

        $mail->Send();

    }

}
?>
