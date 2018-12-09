<?php

include 'init.php';

$letterid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;
$newsletter = new ON_Newsletter();
$enc = new ON_Enc();

$letterid = (int)$enc->decrypt($letterid);
$bload = $newsletter->load($letterid);

$newsletter->registerForm('newsletter');

$user =& ON_User::getInstance();
$usercounts = $user->numRowsBulletinUsers();

$output = '';

if ($bload && $usercounts > 0) {
  // fill form with elements
  $newsletter->fillSendForm();
  
  // process forms if posted
  if ($newsletter->form->isSubmitted() && $newsletter->form->validate()) {
    $news_values =& $newsletter->form->exportValues();
    $quemail = new ON_QueMail();  
    $aBulletinUsers = $user->getBulletinUsers();
    $queid = 0;
    if (is_array($aBulletinUsers)) {
      if ($news_values['addtomail']) {
        array_push($aBulletinUsers, array('email'=>ON_Filter($news_values['addtomail'])));	
      }

      foreach($aBulletinUsers as $aUser) {
        $values = array('mailto'=>ON_Filter($aUser['email']), 
                        'mailsubject'=>ON_Filter($newsletter->lettersubject),
                        'mailbody'=>ON_Filter($newsletter->letterbody));
        $quemail->setValues($values);
        $quemail->insert($queid);
      }
      $mailcount = count($aBulletinUsers);
    }
    if ($mailcount) {
      ON_Say::add(fmtSuccess(sprintf(_('E-Bulletin successfully insert the mail queue. Total %d e-mail will be post'),
                                     (int)$mailcount)));  
    }
  } else {  // show form
    if ($bload) {
      $output = '<fieldset style="padding:6px"><legend>E-mail Subject: '.$newsletter->lettersubject .
        '</legend>E-mail Body<br />'.$newsletter->letterbody.'</fieldset>';
      $output .= $newsletter->form->toHtml();
    } else {
      $output .= fmtError(_('Registered e-bulletin not found'));
    }
  }
} else {
  ON_Say::add(fmtError(_('Registered e-bulletin member is 0')));
}

include 'theme.php';

?>