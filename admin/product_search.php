<?php

include 'init.php';
$product = new ON_Product();  
$product->searchForm($productSearchForm);

if (isset($productSearchForm)) {
  // replace forms if posted
  $output = $productSearchForm->toHtml();
}

if (isset($_REQUEST['sw'])) {  
  $sw = ON_Filter($_REQUEST['sw']);
  $aSearch = explode(' ', trim($sw));

  $where = '';
  foreach($aSearch as $term) {
    $where .= ' a.productname LIKE "'.$term.'%" OR a.productdetail LIKE "%'.$term.'%" OR';
    //postgre
    //$where .=  ' (to_tsvector(productdetail || productname) @@ to_tsquery('.$product->quote($term).')) OR';    
  }
  $where = substr($where, 0, -2);

  $res = $product->searchPager($pager, $numrows, null, $where);

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
    $output .= sprintf(_('%s result found'), $numrows);
    $output .= '<br clear="all" />';
    $output .= $pager->display();
    $output .= $t->display();
    $output .= '<br clear="all" />';
    $output .= $pager->display();
  } else {
    $output .= _('Result not found, please try again different terms or use detail search page');
  }
}

include 'theme.php';

?>