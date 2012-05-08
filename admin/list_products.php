<?php

include 'init.php';

$product = new ON_Product();

if (isset($_POST['go']) && isset($_POST['id'])) {
  $enc = new ON_Enc();
  $productid = (int)$enc->decrypt($_POST['id']);
  $bupdate = $product->load($productid);
  if($bupdate) {  
    if (strstr($_POST['go'], 'delete_product')) {
      $result = $product->isupdate('isdeleted=1, dtdeleted=NOW()',$productid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Product deleted successfully')));
      }
    }
  }
}

$product->setOrder('a.productname');
$res = $product->pager($pager, $numrows);

if ($res) {
  $inline_form =& new ON_SelectForm($product->modifyOptions);
  $t = new ON_Table($inline_form);
  $t->title(array(_('Product Name'), _('Category'), _('Price'), _('Description')));  
  $e = new ON_Enc();
  $cat = new ON_Cat();
  $aCats = $cat->getCats();
  while($p = $res->fetch(PDO::FETCH_OBJ)) {
    $product->getfilter($p);
    $t->tr(array($p->productname, $aCats[$p->catid], $p->price, truncate(clearCodes($p->productdetail))), 
		 $e->encrypt($p->productid));
  }
  // display page links(1,2,3..5 > >>) if available
  $t->addRow($pager->display()._('Total Product Count:') . ' ' . $numrows, $t->colCount());

  $output = $t->display();
} else {
  $output = fmtError(_('Product records not found'));
}

include 'theme.php';

?>