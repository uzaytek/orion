<?php

include 'init.php';

$product = new ON_Product();
$product->registerForm('registerProduct');

$productid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;

$enc = new ON_Enc();

if ($productid) {
  $productid = (int)$enc->decrypt($productid);
  $bupdate = $product->load($productid);
}


// fill form with elements
$product->fillRegisterForm();

// process forms if posted
if ($product->form->isSubmitted() && $product->validate()) {

  if (isset($bupdate) && $bupdate == true) {
    $res = $product->update($productid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Product information updated successfully')));
      header('Location: list_products.php'.iif('currentpage'));
      exit;
    } else {
      ON_Say::add(fmtError(_('Product information update failed')));
    }
  } else {
    $res = $product->insert($productid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Product information inserted successfully')));  
    } else {
      ON_Say::add(fmtError(_('Product information insert failed')));
    }
  }
} else {
  $output = $product->form->toHtml();
}

include 'theme.php';

?>