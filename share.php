<?php

include 'init.php';

//formname, method, action, target, attributes, tracksubmit    
$form = new ON_QuickForm('recommendation'); 
  
// add elements
$form->addElement('hidden', 'itemid', ON_Filter($_REQUEST['itemid']));
$form->addElement('hidden', 'itemtype', ON_Filter($_REQUEST['itemtype']));
$form->addElement('hidden', 'returnUrl', urldecode(ON_Filter($_SERVER['REQUEST_URI'])));

$form->addElement('text', 'name1', _('First/Last Name')); 
$form->addRule('name1', _('Name required'), 'required', null, 'client');

$form->addElement('text', 'email1', _('Your E-mail')); 
$form->addRule('email1', _('E-mail required'), 'required', null, 'client');
$form->addRule('email1', _('E-mail address regular expression failed'), 'email', null, 'client');

$form->addElement('text', 'email2', _('Friend E-mail(s)')); 
$form->addRule('email2', _('Friend e-mail(s) required'), 'required', null, 'client');
$form->addElement('html', $form->withTR(_('You can use comma to seperate different emails')));

$form->addElement('submit', 'sb', _('Send'), 'class="sb"');

if ($form->validate()) {

  $values =& $form->exportValues();


  // get shared item id
  $enc = new ON_Enc;
  $itemid = (isset($values['itemid'])) ? ON_Filter($values['itemid']) : 0;

  // add stat
  $stat = new ON_Stat;
  $useraction  =  $session->get('userid') ? ON_USER_ACTION_SHARE : ON_ANON_ACTION_SHARE ;  

  if ((isset($values['itemtype']) && $values['itemtype']=='product')) { 
    // product statistics
    $itemid = (int)$enc->decrypt($itemid);
    $stattype = ON_STAT_PRODUCT;
  } else {
    $itemid = (int)$itemid;
    $stattype = ON_STAT_CATEGORY;
  }
  $aStatValues = array('itemid'=>$itemid, 'stattype'=>$stattype, 'useraction'=> $useraction);  
  $stat->setValues($aStatValues);
  $stat->insert();

  // add mail to que
  $quemail = new ON_QueMail;
  $aMails = array_map('trim', explode(",", $values['email2']));

  $mailbody = sprintf(_('Share a link with you from %s'), $values['name1'])."\n\n".$values['returnUrl'];

  $queid = 0;    
  if (is_array($aMails)) {
    foreach ($aMails as $smail) {
      $aQueValues = array('mailto'=>ON_Filter($smail), 
                          'mailsubject'=> sprintf(_('Product recommend to you from %s'), $values['name1']),
                          'mailbody'=>$mailbody);
      $quemail->setValues($aQueValues);
      $quemail->insert($queid);      
    }
  }

  // show a message to user
  if ($queid) {
    $output = fmtSuccess(_('E-mail(s) sent to your friend address, thank you'));  
  } else {
    $output = fmtError(_('E-mail(s) failed, something wrong for system please call tecnical support'));
  }
} else {
  $output = $form->toHtml();
}

$settings = new ON_Settings();
$settings->load();

include PT_INCLUDE . 'ON_Theme.php';
theme($output);



?>