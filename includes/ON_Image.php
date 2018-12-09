<?php

/**
 * Image dao object for products
 */
class ON_Image extends ON_Dao
{

  /**
   * Quickform object
   *
   * @var object
   */
  public $form;

  /**
   * Construct 
   */
  public function __construct($productid)
  {
    parent::__construct(array('table'=> DB_TBL_IMAGES, 'pk'=>'imgid','seq'=>'product_image_nid_seq'),
                        array('imgid','productid','hostfilename','realfilename',
                              'fileextension','filewh','isdefault','isdeleted',
                              'dtcreated')
                        );
    $this->productid = (int)$productid;
  }

  /**
   * Register quickform object
   * 
   * @param string $formName The html form name 
   */
  public function registerForm($formName) {
    $this->form     = new ON_QuickForm($formName);
  }
  

  /**
   * Fill the image form
   * 
   */
  public function fillForm() {
    global $_conf;

    $enc = new ON_Enc();
   
    // add hidden element to session for controller purpose
    if ($this->imgid > 0) { 
      $this->form->addElement('hidden', 'imgid', $enc->encrypt((int)$this->imgid));
    }

    $this->form->addElement('file', 'image_1', _('Image'), array('size'=> 43,'id'=>'my_image_element'));
    $this->form->addElement('hidden', 'id',$enc->encrypt((int)$this->productid));

    $maxfilesize = sprintf(_('Maximum file size is: %d KB'), fmtKB($_conf['HTML_MAX_FILE_SIZE']['product']));     
    $this->form->addRule('image_1', $maxfilesize, 'maxfilesize', $_conf['HTML_MAX_FILE_SIZE']['product']);
    $this->form->addRule('image_1', 
                         _('File extension required in types:'). implode(',', $_conf['HTML_PERMIT_TYPES']['product']),
                         'mimetype',
                         $_conf['HTML_PERMIT_TYPES']['product']);
    // Tell well-behaved browsers not to allow upload of a file larger than max file size
    $this->form->setMaxFileSize($_conf['HTML_MAX_FILE_SIZE']['product']);    

    $this->form->addElement('submit', 'sb', _('Upload'), 'class="si"');
    $this->form->addElement('html', $this->form->withTR($maxfilesize));
    $this->form->addElement('html', $this->form->withTR(_('Permitted Extensions:') . 
                                                        implode(',', $_conf['HTML_PERMIT_DISPLAY']['product'])));

  }

  /**
   * Set values for image
   *
   * @param object $file Pear Quickform element object
   * @return boolean Return true if upload successfull
   */
  public function setValues(&$file) {
    // move file    
    $afile = $file->getValue();
    $this->hostfilename   = $this->getRandomName();
    $this->realfilename   = ON_Filter($afile['name']);
    $this->fileextension  = $this->ext(ON_Filter($afile['name']));
    $this->filewh         = $this->filewh(ON_Filter($afile['tmp_name']));
    $this->isdefault      = 0;
    $this->dtcreated      = $this->getDate('dt');

    if ($file->moveUploadedFile(PT_IMAGE, $this->hostfilename . $this->fileextension)){
      return true;
    }
    return false;
  }

  /**
   * return image file width,height informations
   *
   * @param string $sfile_addr The file physical address
   * @return string widht,height informations
   */
  private function filewh($addr) {
    $tmp = @getimagesize($addr);
    if ($tmp != false && is_array($tmp)) {
      return $tmp[0].",".$tmp[1];
    }
  }
  
  /**
   * returns file extension
   *
   * @param string $name The file name
   * @return string extension of given file
   */
  private function ext($name) {
    if (strstr($name, ".")) {
      return strtolower(substr($name, strrpos($name, ".")));
    }
  }

  /**
   * returns server file name
   *
   * @return string name of file
   */
  public function getName() {
    return $this->hostfilename . $this->fileextension;
  }

  /**
   * generates random name for image
   * 
   * Controls image table for uniq name
   * 
   * @return string
   */
  private function getRandomName() {
    // make sure image name is unique
    if(!self::$db) $this->connect();
    do {
      $name = genRandStr(10, true);
      $res = self::$db->query('SELECT hostfilename FROM ' .$this->reg['table'] .
                              ' WHERE hostfilename='.$this->quote($name));
    } while ($res->fetchColumn() > 0);
    return $name;
  }

  /**
   * delete one image
   *  
   * @param integer $theid
   * @return integer number of rows were affected
   */
  public function delete($theid) {

    if(!self::$db) $this->connect();
    // get image name and delete it from file system
    $this->load($theid);
    $res = self::$db->exec('DELETE FROM '.$this->reg['table'] .
                           ' WHERE '.$this->reg['pk']. ' = '.intval($theid).$this->where());
    if ($res) {
      @unlink(PT_IMAGE . $this->name . $this->ext);
      @unlink(PT_IMAGE . 'thumb.' . $this->name . $this->ext);
    }
    return $res;
  }

  /**
   * delete all product image
   *  
   * @param integer $theid
   * @return integer number of rows were affected
   */
  public function deleteAll() {

    if(!self::$db) $this->connect();

    $res =& self::$db->query('SELECT name, ext FROM ' . $this->reg['table'] .
                             ' WHERE fk_id = '.intval($this->fk_id) . $this->where());
    while($row = $res->fetchRow()) {
      @unlink(PT_IMAGE . $row['name'] . $row['ext']);
      @unlink(PT_IMAGE . 'thumb.' . $row['name'] . $row['ext']);
    }

    $res = self::$db->exec('DELETE FROM '.$this->reg['table'] .
                           ' WHERE fk_id = '.intval($this->fk_id) . $this->where());
    return $res;  
  }
}

?>