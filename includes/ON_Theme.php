<?php

/**
 * Theme functions 
 * 
 */  

/**
 * Get selected theme
 * 
 */  
function theme(&$output=null) {
  global $settings;
  $t = PT_THEME . $settings->theme . '/index.php';
  if(file_exists($t)){
    include $t;
  }
}

/**
 * Get selected theme
 * 
 */  
function theme_popup(&$output=null) {
  global $settings;
  $t = PT_THEME . $settings->theme . '/popup.php';
  if(file_exists($t)){
    include $t;
  }
}


/**
 * Echo meta description
 * 
 */  
function theme_description() {
  global $settings;
  echo $settings->description;
}

/**
 * Echo meta keywords
 * 
 */  
function theme_keywords() {
  global $settings;
  echo $settings->keywords;
}

/**
 * Echo site title
 * 
 */  
function theme_title() {
  global $settings;
  echo $settings->title;
}

/**
 * Echo site logo
 * 
 */  
function theme_logo() {
  global $settings;
  echo '<img src="'.LC_UPLOAD . $settings->logo.'">';
}

/**
 * Echo site slogan
 * 
 */  
function theme_slogan() {
  global $settings;
  echo $settings->slogan;
}

/**
 * Echo welcome text
 * 
 */  
function theme_welcome() {
  global $settings;
  echo $settings->welcome;
}

/**
 * Echo gallery box
 * 
 */  
function theme_gallery_box() {
  return;
  $gallery = new ON_Gallery();

  $pagerOptions = array('perPage' => 1);
  $res = $gallery->pager($pager, $numrows, $pagerOptions);  
  if ($res) {
    $output .= '<h2><p>'._('Last Picture').'</p></h2>';
    while($row = $res->fetch()) {
      $output .= '<p><a href="' . LC_SITE . 'popup.php?t=image&id='.$row['imgid'].'"><img src="'. 
        LC_UPLOAD . 'thumb_'. $row['filename'].'"/></a></p>';            
    }
    $output .= '<p><a href="popup.php?t=categories">'._('All Gallery').'</a></p>';
    echo $output;
  }  
}

/**
 * Echo banner in the selected location
 * 
 */  
function theme_banner($location) {
  $banner    = new ON_Banner();
  $banner->setWhere(' (isdeleted=0 OR isdeleted IS NULL) AND location='.$banner->quote(filter_var($location)));
  $aAll = $banner->getAll();
  $output = '';
  foreach($aAll as $k=>$f) {
    $output .= '<p>'.url($f['url'], '<img alt="'.$f['origname'] . '" src="'. LC_UPLOAD . $f['filename'].'"/>').'</p>';            
  }
  echo $output;
}

/**
 * Echo bestseller products box
 * 
 */  
function theme_bestseller_box() {
  $products = new ON_Product();
  $enc = new ON_Enc();

  $pagerOptions = array('perPage' => 10);
  $res = $products->pager($pager, $numrows, $pagerOptions, ' AND a.isabestseller=1');
  $output = ''; 
  if ($res) {
    $output .= '<h2><p>'._('Bestsellers').'</p></h2><ol>';
    while($row = $res->fetch()) {
      $output .= '<li><a href="' . LC_SITE . 'detail.php?&id='. $enc->encrypt($row['productid']).'">'.
        $row['productname'].'</a></li>';
            
    }
    $output .= '</ol>';
    echo $output;
  }  
}



/**
 * Echo news box
 * 
 */  
function theme_news_box() {
  $news = new ON_News();

  $pagerOptions = array('perPage' => 5);
  $res = $news->pager($pager, $numrows, $pagerOptions);
  $output = ''; 
  if ($res) {
    $output .= '<h2><p>'._('News').'</p></h2><ul>';
    while($row = $res->fetch()) {
      $output .= '<li><a href="' . LC_SITE . 'index.php?t=news&id='. $row['newsid'].'">'.$row['newstitle'].'</a></p><p>' .
        truncate(clearCodes($row['newsdetail'])) .'</li>';
            
    }
    $output .= '</ul><p><a href="' . LC_SITE . 'index.php?t=news">'._('All News').'</a></p>';
    echo $output;
  }  
}

/**
 * Echo site address
 * 
 */  
function theme_address() {
  global $settings;
  echo $settings->address;
}

/**
 * Echo site name
 * 
 */  
function theme_sitename() {
  global $settings;
  echo $settings->sitename;
}

/**
 * Echo site url
 * 
 */  
function theme_site_url() {
  global $settings;
  echo LC_SITE;
}


/**
 * Echo theme path
 * 
 */  
function theme_url() {
  global $settings;
  echo LC_THEME.$settings->theme.'/';
}

/**
 * Echo theme content according to selected type and id
 * 
 */  
function theme_content(&$output=null) {
  global $settings, $session, $_conf;
  

  $id = (isset($_REQUEST['id'])) ? intval($_REQUEST['id']) : 0;

  if ($output) {
    echo $output;
    return;
  } 

  $displayPage = (isset($_conf['displayPage'])) ? $_conf['displayPage'] : '';

  switch ($displayPage) {

  case 'image'; // gallery image
  $image = new ON_Gallery();
  $bload = $image->load($id);
  if ($bload) {
    $output .= '<img alt="'.$image->origname . '" src="'. LC_UPLOAD . $image->filename.'"/>';            
  }
  break;

  case 'gallery': // gallery thumbnails
    $gallery = new ON_Gallery();
    $where = ' a.catid = '. $id;
    $res = $gallery->pager($pager, $numrows, $options, $where);

    if ($res) {
      $output .= '<p>';
      while($row = $res->fetch()) {
        $output .= '<a href="' . LC_SITE . 'index.php?t=image&id='.$row['imgid'].'"><img alt="'.$row['origname'] .
          '" src="'. LC_UPLOAD . 'thumb_'. $row['filename'].'"/></a>';            
      } 
      $output .= '</p>';
    }
    $output .= $pager->display();
    break;

  case 'categories': // gallery categories
    $cat = new ON_GalleryCat();
    $aCats = $cat->getGalleryCats();
    foreach($aCats as $catid=>$cattitle) {
      $output .= '<p><a href="' . LC_SITE . 'index.php?t=gallery&id='. $catid . '"> ' . $cattitle . '</a></p>';
    }
    break;
  case 'aboutus':// aboutus
    $output = '<p>'.$settings->aboutus.'</p>';
    $output .= '<p>'.$settings->accounts.'</p>';
    $output .= '<p>'.$settings->address.'</p>';
    break;
  case 'tos':
    $output = '<p>'.$settings->tos.'</p>';
    break;
  case 'policies':
    $output = '<p>'.$settings->guarantee.'</p>';
    $output .= '<p>'.$settings->refunding.'</p>';
    break;
  case 'faq':// news
    $faq = new ON_Faq();
    $res = $faq->pager($pager, $numrows);  
    if ($res) {
      $output .= '<h3>'._('Faq').'</h3>';
      while($row = $res->fetch()) {
        $output .= '<p><b>'.$row['faqtitle'].'</b><p>' . $row['faqdetail'] .'</p><hr>';            
      }
      $output .= $pager->display();
    }// end res
    break;
  case 'news':// news
    $news = new ON_News();
    if ($id > 0) { // load specific news
      $bload = $news->load($id);    
      if ($bload) {
        $output .= '<h3>'.$news->newstitle.'</h3>'.$news->newsdetail;
      }
    } else {
      // load all news with paginate
      $res = $news->pager($pager, $numrows);  
      if ($res) {
        $output .= '<h3>'._('News').'</h3>';
        while($row = $res->fetch()) {
          $output .= '<p><a href="' . LC_SITE . 'index.php?t=news&id='. $row['newsid'].'">'.$row['newstitle'].'</a></p><p>' .
            truncate(clearCodes($row['newsdetail']),400) .'</p><hr>';            
        }
        $output .= $pager->display();
      }// end res
    } // end else
    break;
 case 'user.update':
    $user =& ON_User::getInstance();
    $userid = $session->get('userid');
    $user->load($userid);
    $user->registerForm('updateUser');
    $user->fillRegisterForm();

    if ($user->form->isSubmitted() && $user->validate()) {
      $res = $user->update($userid);
      if ($res) {
        ON_Say::add(fmtSuccess(_('Your information updated successfully')));  
      } else {
        ON_Say::add(fmtError(_('Database Error: Update Failed')));
      }
    } else {
      $output = $user->form->toHtml();
    }
    break;
  case 'user.register':
    $user =& ON_User::getInstance();
    $user->registerForm('registerUser');
    $user->fillRegisterForm();

    if ($user->form->isSubmitted() && $user->validate()) {
      $res = $user->insert($userid);
      if ($res) {
        ON_Say::add(fmtSuccess(_('Your information saved successfully, please control your mailbox for activation')));  
      } else {
        ON_Say::add(fmtError(_('Database Error: Insert Failed')));
      }
    } else {
      $output = $user->form->toHtml();
    }
    break;
  case 'newproducts':
    $product = new ON_Product();
    // new products
    $res = $product->pager($pager, $numrows, null, 
                           ' AND (a.dtcreated > DATE_SUB(NOW(), INTERVAL 1 WEEK)) OR a.isanewone=1');

    if ($res) {
      $tpl = new ON_Display('Product');
      $tpl->title(_('New Products'));
      while($p = $res->fetch(PDO::FETCH_OBJ)) {
        $product->getfilter($p);
        $tpl->addRow($p);
      }
      $output .= $tpl->display();
    }
    break;
  default:

    $product = new ON_Product();
    $tpl = new ON_Display('Product');

    $output = theme_headlines();

    $catid = (isset($_REQUEST['catid'])) ? (int)$_REQUEST['catid'] : 0;

    if ($catid > 0) {
      // $catid category products
      $res = $product->pager($pager, $numrows, null, ' AND a.catid='.$catid);
      $cat = new ON_Cat();
      $aCats = $cat->getCats($catid);
      $tpl->title($aCats[$catid].'&nbsp;<a href="share.php?itemtype=cat&amp;itemid='.$catid.'">'._('Share').'</a>');
    } else {
      // promoted products
      $res = $product->pager($pager, $numrows, null, ' AND a.isapromote=1');
      $tpl->title(_('Popular Products'));
    }

    if ($res) {  
      while($p = $res->fetch(PDO::FETCH_OBJ)) {
        $product->getfilter($p);
        $tpl->addRow($p);
      }
      $output .= $tpl->display();
    }
    break;
  }
  echo $output;
}


/**
 * Print categories box
 *
 */
function theme_category_box() {
  echo '<h2><p>'._('Category').'</p></h2>';

  $cat = new ON_Cat();
  $aCatTitles = $cat->getIndentCats(0);
  echo '<ul>';
  foreach ($aCatTitles as $catid=>$cat) {
    $style = ($cat['level'] > 0) ? 'style="margin-left:'.($cat['level']*10).'px"' : '';
    echo '<li><a href="' . LC_SITE . 'index.php?catid=' . $catid . '" '.$style.'>'. $cat['cattitle'] . '</a></li>';
  }
  echo '</ul>';
}


/**
 * Print e-bulletin subscribe box
 *
 */
function theme_bulletin_box() {
  $out = '<h2><p>'._('E-Bulletin Subscribe').'</p></h2>';
  $out .= '<p id="mailboxon">';
  $out .= _('E-mail').' <input type="text" name="ebulletinmail" id="ebulletinmail">'.
    '<input type="button" class="sb" value="'._('Register').'" onClick="javascript:bulletinSubscribe(\'ebulletinmail\')">';
  $out .= '</p>';
  $out .= '<p style="display:none" id="mailboxoff">'._('Please control your mailbox for activation').'</p>';
  echo $out;
}

/**
 * Print web site login box
 *
 */
function theme_member_login_box() {
  global $settings, $session;

  $userid = $session->get('userid');
  $user =& ON_User::getInstance();
  $out = '<h2><p>'._('Login').'</p></h2>';
  if ($userid == 0) {
    $user->loginForm('loginUser','post','login.php');  
    $out .= $user->form->toHtml();
  } else {
    $bload = $user->load($userid);
    if ($bload) {
      $out .= '<strong>'.$user->username.'</strong><ul>
<li><a href="'.LC_SITE.'profile.php">'. _('Profile').'</a></li>
<li><a href="'.LC_SITE.'logout.php">'.  _('Logout').'</a></li></ul>';
    }
  }
  echo $out;
}


/**
 * Print web site main menu
 *
 */
function theme_menu() {
  global $settings;
  $out = '<ul>';
  $out .= '<li><a href="' . LC_SITE . 'index.php"><span>' .  _('Main Page').'</span></a></li>';
  $out .= '<li><a href="' . LC_SITE . 'index.php?t=newproducts"><span>' .  _('New Products').'</span></a></li>';
  $out .= '<li><a href="' . LC_SITE . 'user.php"><span>'  .  _('New Member').'</span></a></li>';
  $out .= '<li><a href="' . LC_SITE . 'basket.php"><span>' . _('My Basket').'</span></a></li>';
  $out .= '<li><a href="' . LC_SITE . 'index.php?t=faq"><span>'  . _('Faq').'</span></a></li>';
  $out .= '<li><a href="' . LC_SITE . 'index.php?t=aboutus"><span>'  . _('About Us').'</span></a></li>';
  $out .= '<li><a href="' . LC_SITE . 'contact.php"><span>'  . _('Contact').'</span></a></li>';
  $out .= '</ul>';
  echo $out;
}

/**
 * Print web site search box
 *
 */
function theme_search_box() {
  $product = new ON_Product();  
  $product->searchForm($searchForm);
  $out = '<h2><p>'._('Search').'</p></h2>';
  if (is_object($searchForm)) {
    echo $out . $searchForm->toHtml();
  }
}

/**
 * Echo headline products
 * 
 */  
function theme_headlines() {
  $product = new ON_Product();
  // new products
  $res = $product->pager($pager, $numrows, null, ' AND a.isaheadline=1');
  $output = '';
  if ($res) {
    $output = '<div id="slideshowHolder" style="border:1px solid #dedede">';
    while($p = $res->fetch(PDO::FETCH_OBJ)) {
      $file_name = $p->hostfilename . $p->fileextension;
      $file_path = PT_IMAGE . $file_name;
      if (file_exists($file_path)) {
        $bheadline = false;
        // if headline image exits, cached default image
        $headline_path = PT_IMAGE . 'headline_'.$file_name;
        if(file_exists($headline_path)) { // use headline image
          $bheadline = true;
        } else { // generate headline
          $thumb = new ON_Thumbnail($file_name);
          $thumb->size_height(300);
          $thumb->save(PT_IMAGE . 'headline_'.$file_name);
          if (file_exists($headline_path)) { // last control if save (cache) process successfull use saved one
            $bheadline = true;
          }
        }

        if ($bheadline ) {
          $image_src = LC_IMAGE . 'headline_'.$file_name;
        } else { // whatever use original
          $image_src = LC_IMAGE . $file_name;
        }

        $output .= '<img border="0" src="' . $image_src.'" alt="' . $p->productname . '" />';
      }// end if original file exists
    } // end while
    $output .= '</div>';
  }
  return $output;

}

/**
 * Print web site footer menu
 *
 */
function theme_footer_menu() {
  global $settings;
  $out = '<ul>';
  $out .= '<li><a href="' . LC_SITE . 'index.php"><span>' .  _('Main Page').'</span></a></li>';
  $out .= '<li><a href="' . LC_SITE . 'index.php?t=tos" title="'._('Terms of Usage').'"><span>' .
    _('Terms of Usage').'</span></a></li>';

  $out .= '<li><a href="' . LC_SITE . 'index.php?t=policies" title="'._('Policies').'"><span>' .
    _('Policies').'</span></a></li>';


  $out .= '<li><a href="' . LC_SITE . 'index.php?t=faq"><span>'  . _('Faq').'</span></a></li>';
  $out .= '<li><a href="' . LC_SITE . 'index.php?t=aboutus"><span>'  . _('About Us').'</span></a></li>';
  $out .= '<li><a href="' . LC_SITE . 'contact.php"><span>'  . _('Contact').'</span></a></li>';
  $out .= '</ul>';
  echo $out;
}


?>