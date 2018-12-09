<?php

include 'init.php';

$output = '';

$newsletter = new ON_Newsletter();
$user =& ON_User::getInstance();

$newsletter->registerForm('newsletter');


$usercounts = $user->numRowsBulletinUsers();

if ($usercounts > 0) {
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
                        'mailsubject'=>ON_Filter($newsletter->newsletter_subject),
                        'mailbody'=>ON_Filter($newsletter->newsletter_body));
        $quemail->setValues($values);
        $quemail->insert($queid);
      }
      $mailcount = count($aBulletinUsers);
    }
    if ($mailcount) {
      ON_Say::add(fmtSuccess(sprintf(_('E-Bulletin successfully insert the mail que. Total %d e-mail will be post'),
                                     (int)$mailcount)));  
    }
  }
  
  if ($newsletter->newsletter_subject && $newsletter->newsletter_body) {
    $output = '<fieldset style="padding:6px"><legend>E-mail Subject: '.$newsletter->newsletter_subject .
      '</legend>E-mail Body<br />'.$newsletter->newsletter_body.'</fieldset>';
    $output .= $newsletter->form->toHtml();
  } else {
    ON_Say::add(fmtError(_('Please enter a bulletin first')));
  }
} else {
  ON_Say::add(fmtError(_('Registered bulletin member is 0')));
}

include 'theme.php';

?>