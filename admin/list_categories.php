<?php
include 'init.php';

$cat  = new ON_Cat();

if (isset($_REQUEST['go'])) {

  // delete a category
  if (strstr($_REQUEST["go"], 'delete_cat') && isset($_REQUEST['id'])) {
    $enc = new ON_Enc();
    $catid = (int)$enc->decrypt($_REQUEST['id']);
    $bupdate = $cat->load($catid);
    if($bupdate) {        
      $result = $cat->isupdate('isdeleted=1', $catid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Selected category deleted successfully')));
      }
    }
  } // end delete category

  // update category orders
  if (isset($_REQUEST['update_orders'])) {
    $result = $cat->updateCatOrders($_POST['catorder']);
    if ($result) {
      ON_Say::add(fmtSuccess(_('Category orders updated successfully')));
    }
  } // end update orders
}

// gets cat[catid] = cat values but add '---' cattitle name according to its level
$aCats = $cat->getIndentCats(0);

if (is_array($aCats)) {

  $block_form  = new ON_BlockForm('block'); // wrap to table with a form for catorder text box

  $inline_form = new ON_LinkForm(array('category.php?'                      =>_('Modify'), 
                                       'list_categories.php?go=delete_cat'  =>_('Delete')));
  $t = new ON_Table($inline_form, $block_form);
  $t->title(array(_('Category Title'),_('Category Order')));
  $e = new ON_Enc();

  foreach($aCats as $catid=>$cat) {
    $ecatid = $e->encrypt($catid);
    $cattitle = @str_repeat( "-", $cat['level']*2 ) . $cat['cattitle'];
    $t->tr(array($cattitle, $block_form->textElement('catorder['.$ecatid.']', $cat['catorder'])), $ecatid);
  }
  $t->addRow(_('Total Category Count:').count($aCats));
  $t->addRow($block_form->submit('update_orders', _('Update Category Orders')));
  $output = $t->display();
} else {
  $output = fmtError(_('Category record not found'));
}


include 'theme.php';

?>