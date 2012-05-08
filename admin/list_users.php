<?php

include 'init.php';

$user =& ON_User::getInstance();
$enc = new ON_Enc();

if (isset($_POST['id'])) {
  $userid = intval($enc->decrypt($_POST['id']));

  // actions
  $bupdate = $user->load($userid);
  if($bupdate) {  
    if (strstr($_POST['go'], 'delete_user')) {
      $result = $user->isupdate('isdeleted=1, dtdeleted=NOW()', $userid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('User deleted successfully')));
      }
    }
  }
}


$res = $user->pager($pager, $numrows);

if ($res) {

  $inline_form = new ON_SelectForm(array('user.php'                     => _('Modify'), 
                                         'list_users.php#delete_user'    => _('Delete')));

  $t = new ON_Table($inline_form);
  $t->title(array(_('Username'), _('E-mail'), _('Tel'), _('Address')));
  $e = new ON_Enc();
  list($aCountries, ) = ON_Global::getAllCountries();
  while($row = $res->fetch()) {
    $t->tr(array($row['username'], $row['email'], $row['phone'], 
                 nl2br($row['address']).'<br />'._('Post Code:').$row['postcode'].'<br />'.
                 _('City/Country:').$row['city'].'/'.$aCountries[$row['country']],
		 ),	   
	   $e->encrypt($row['userid']));
  }
  // display page links(1,2,3..5 > >>) if available
  $t->addRow(array($pager->display()._('Total User Count:').' '.$numrows), $t->colCount());
  
  $output = $t->display();
} else {
  $output = fmtError(_('User records not found'));
}

include 'theme.php';

?>