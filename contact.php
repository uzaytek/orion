<?php

include 'init.php';
include 'assets/recaptchalib.php';


$settings = new ON_Settings();
$settings->load();


$form = new ON_QuickForm('contact' , 'post', '', '', 'class="register"', true); 
  
$form->addElement('text', 'name', _('Name')); 
$form->addRule('name', _('Name required'), 'required', null, 'client');

$form->addElement('text', 'email', _('E-mail')); 
$form->addRule('email', _('E-mail required'), 'required', null, 'client');
$form->addRule('email', _('Please check email, e-mail is not a valid e-mail address'), 'email', null, 'client');

$form->addElement('text', 'subject', _('Subject')); 
$form->addRule('subject', _('Subject required'), 'required', null, 'client');

$form->addElement('textarea', 'message', _('Message'), array('cols'=>40, 'rows'=>4)); 
$form->addRule('message', _('Message required'), 'required', null, 'client');

if ($form->validate()) {

  $resp = recaptcha_check_answer ('private_key', // privatekey
                                  $_SERVER["REMOTE_ADDR"],
                                  $_POST["recaptcha_challenge_field"],
                                  $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // captcha failed
    $output = _("reCAPTCHA error: ") . $resp->error;
 } else {
    // captcha success
    $values =& UN_Filter($form->exportValues());
    $body = $values['message']."\n\n".$values['name'];
    $headers  = 'Content-type: text/html; charset=utf-8' . "\n";
    $headers .= 'From: Ursaminor <'.filter_var($values['email'], FILTER_VALIDATE_EMAIL).'>' . "\n";
    // $bmail = mail(EM_ADMIN, $values['subject'], $body, $headers);
    if ($bmail) {
      $output = fmtSuccess(_('Your contact request has been successfully submitted'));  
    } else {
      $output = fmtError(_('Something wrong, mail function failed.'));
    }
  }
} else {

  //  $captcha = recaptcha_get_html('public_key');

  if (isset($captcha) && $captcha != '') {
    $form->addElement('html', $captcha);
  }
  $form->addElement('submit', 'sb', _('Send'));

  $output = $form->toHtml();
}

include PT_INCLUDE . 'ON_Theme.php';
theme($output);
?>
