<?php

/**
 * Settings table(settings saved in global table) dao
 *
 */ 
class ON_Settings extends ON_Dao
{

  /**
   * Pear Quickform ojbect
   * 
   * @var object
   */  
  public $form;

  /**
   * Construct setting
   * 
   */  
  public function __construct() {
    parent::__construct(array('table'=> DB_TBL_GLOBALS, 'pk'=>'globalid','seq'=>'_nid_seq'),
                        array('globalid','description','keywords','logo',
                              'title','sitename','slogan','tos','accounts',
                              'aboutus','guarantee','refunding',
                              'address',
                              'theme',)
                        );
  }

  /**
   * Load setting object with table values
   * 
   */
  public function load() {
    
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query("SELECT * FROM ".DB_TBL_GLOBALS." WHERE tag='settings' AND tagproperty='theme'");
      $row =& $result->fetch(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    // must be set for insert if not available
    if(is_array($row)) {
      $this->globalid = $row['globalid'];
      $aArray = unserialize($row['tagvalue']);
      if (is_array($aArray)) {
        foreach($aArray as $key=>$value) {
          $this->{$key} = $value;
        }
      }
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * Insert values to db
   *
   */  
  public function insert() {
    try {
      if(!self::$db) $this->connect();
      $defaults = $this->defaults();
      unset($defaults['globalid']);//protect globalid saved again to serialized field
      // serialize all value for text field
      $tagvalue = serialize($defaults);
      $result = self::$db->exec('INSERT INTO ' . DB_TBL_GLOBALS .
                                ' (tag, tagproperty, tagvalue) VALUES ('.
                                '\'settings\',\'theme\',\''.$tagvalue.'\')');
      return $result;
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

  /**
   * Update values
   *
   */  
  public function update() {
    try {
      if(!self::$db) $this->connect();
      $defaults = $this->defaults();
      unset($defaults['globalid']);//protect globalid
      $tagvalue = serialize($defaults);
      $result = self::$db->exec('UPDATE ' . DB_TBL_GLOBALS .
				 ' SET tagvalue = \''.$tagvalue.'\'  WHERE globalid='.(int)$this->globalid);
      return $result;
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

  /**
   * Register quickform object
   * 
   * @param string $formName The html form name 
   */
  public function registerForm($formName) {
    //$formName, $method='post', $action='', $target='', $attr=null,
    $this->form     = new ON_QuickForm($formName, 'post');
  }

  /**
   * Fill the settings form
   * 
   */
  public function fillForm() {

    // set form defaults, $defaults array will be used after update/insert process
    $defaults = $this->defaults();
    $this->form->setDefaults($defaults);
    
    // add elements
    $this->form->addElement('text', 'sitename', _('Site Name'));

    $this->form->addElement('text', 'title', _('Site Title'));

    $this->form->addElement('text', 'keywords', _('Keywords'));

    $this->form->addElement('text', 'description', _('Description'));

    $this->form->addElement('text', 'slogan', _('Slogan Text'));

    // select of theme directory
    $this->form->addElement('select', 'theme', _('Theme'), $this->getThemeDirs());

    $this->form->addElement('file', 'logo', _('Logo'));
    $maxLogoSize = $GLOBALS['_conf']['HTML_MAX_FILE_SIZE']['logo'];
    $this->form->addRule('logo', 
                         sprintf(_('Maximum file size is: %d KB'), fmtKB($maxLogoSize)), 'maxfilesize', $maxLogoSize);
    $this->form->addRule('logo', _('File extension required in types:'), 'mimetype', 
                         $GLOBALS['_conf']['HTML_PERMIT_TYPES']['logo']);
    $this->form->setMaxFileSize($maxLogoSize);
    $this->form->setFileTemplate('logo', $defaults['logo']);

    $this->form->addElement('textarea', 'aboutus', _('About Us'));

    $this->form->addElement('textarea', 'tos', _('Terms of Usage'));

    $this->form->addElement('textarea', 'accounts', _('Bank/Payment Accounts'));

    $this->form->addElement('textarea', 'guarantee', _('Guarantee Policy'));

    $this->form->addElement('textarea', 'refunding', _('Refunding Policy'));

    $this->form->addElement('textarea', 'address', _('Address'), ' id="address"');

    $this->form->setRichTextTemplate('aboutus,tos,accounts,guarantee,refunding,address');
    $this->form->addElement('submit', 'sb', _('Save'), 'class="sb"');
  }

  /**
   * Set setting values
   * 
   * @param array $values The form values filled by panel user
   */  
  public function setValues(&$values) {
    $this->description  = ON_Filter($values['description']);
    $this->keywords     = ON_Filter($values['keywords']);
    $this->sitename     = ON_Filter($values['sitename']);
    $this->title        = ON_Filter($values['title']);
    $this->slogan       = ON_Filter($values['slogan']);
    $this->aboutus      = ON_Filter($values['aboutus']);
    $this->tos          = ON_Filter($values['tos']);
    $this->accounts     = ON_Filter($values['accounts']);
    $this->guarantee    = ON_Filter($values['guarantee']);
    $this->refunding    = ON_Filter($values['refunding']);
    $this->address      = ON_Filter($values['address']);
    $this->theme        = ON_Filter($values['theme']);

    //uploaded logo file and create thumbnail
    $file =& $this->form->getElement('logo');
    if ($file->isUploadedFile()) {
      $aFile = $file->getValue();
      $file_ext = getExtension(ON_Filter($aFile['name']));
      do {
        $newname = genRandStr(4).$file_ext;
      } while (file_exists(PT_UPLOAD . $newname));
       // move file
      if ($file->moveUploadedFile(PT_UPLOAD, $newname)) {
        $this->logo = $newname;
      } else {
        // move upload failed
        ON_Say::add(fmtError(_('Upload error: logo upload failed')));
        return false;
      }
    } // end is uploaded file
  }// end function setValues

  /**
   * Return asset/themes/ directory names in the settings page
   * 
   */  
  function getThemeDirs() {
    $out = array();
    if (is_dir(PT_THEME)) {
      if ($handle = opendir(PT_THEME)) {
        while (false !== ($fh = readdir($handle))) {
          if ($fh != "." && $fh != ".." && is_dir(PT_THEME . $fh)) {
            $out[$fh] = $fh;            
          }
        }
        closedir($handle);
      }
    }
    return $out;
  }
}


?>