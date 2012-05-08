<?php

/**
 * File table dao object 
 * 
 */  
class ON_File extends ON_Dao
{

  /**
   * Pear Quickform ojbect
   * 
   * @var object
   */  
  public $form;

  /**
   * Construct file
   * 
   */  
  public function __construct()
  {
    parent::__construct(array('table'=> DB_TBL_FILES, 'pk'=>'fileid','seq'=>'_nid_seq'),
                        array('fileid','fkid','type','filename','origname',
                              'isonline','isdeleted',
                              'dtcreated')
			);
  }

  /**
   * Register quickform object
   * 
   * @param string $formName The html form name 
   */
  public function registerForm($formName='register') {
    //$formName, $method='post', $action='', $target='', $attr=null,
    $this->form     = new ON_QuickForm($formName, 'post');
  }

  /**
   * Fill the file form
   * 
   */
  public function fillForm() {
    
    if ($this->fkid > 0) {
      $e = new ON_Enc();
      $this->form->addElement('hidden', 'id', $e->encrypt($this->fkid));
    }

    $this->form->addElement('file', 'afile', _('File'), array('size'=>51));
    $this->form->addRule('afile', 
                         sprintf(_('Maximum file size is: %d KB'), fmtKB($GLOBALS['_conf']['HTML_MAX_FILE_SIZE']['file'])), 
                         'maxfilesize', $GLOBALS['_conf']['HTML_MAX_FILE_SIZE']['file']);
    $this->form->addRule('afile', _('File extension required in types:'),
                         'mimetype', $GLOBALS['_conf']['HTML_PERMIT_TYPES']['file']);
    // Tell well-behaved browsers not to allow upload of a file larger than max file size
    $this->form->setMaxFileSize($GLOBALS['_conf']['HTML_MAX_FILE_SIZE']['file']);


    $this->form->addElement('submit', 'sb', _('Upload'), 'class="sb"');
 }

  /**
   * Set file values
   * 
   * @param array $values The form values filled by panel user
   * @param string $path The file upload to under the $path
   */  
  public function setValues(&$values, $path) {
    //uploaded logo file and create thumbnail
    $file =& $this->form->getElement('afile');
    if ($file->isUploadedFile()) {
      $aFile = $file->getValue();
      $file_ext = getExtension($aFile['name']);
      do {
        $newname = genRandStr(32).$file_ext;
      } while (file_exists($path . $newname));

      // move file
      if ($file->moveUploadedFile($path, $newname)) {
        $this->filename = $newname;
        $this->origname = ON_Filter($aFile['name']);
        return true;
      } else {
        // move upload failed
        ON_Say::add(fmtError(_('Upload error: file upload failed')));
        return false;
      }
    } // end is uploaded file
  }

  /**
   * Insert values to db
   *
   * @param integer $fileid The database row id 
   */  
  public function insert($fileid) {
    $this->dtcreated  = $this->getDate('il');
    $this->isdeleted  = 0;
    return parent::insert($fileid);
  }

}

?>
