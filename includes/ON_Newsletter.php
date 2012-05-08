<?php

/**
 * Newsletter dao object 
 * 
 */  
class ON_Newsletter extends ON_Dao
{

  /**
   * Pear Quickform ojbect
   * 
   * @var object
   */  
  public $form;

  /**
   * Construct and load itself
   * 
   */  
  public function __construct() {
    parent::__construct(array('table'=> DB_TBL_GLOBALS, 'pk'=>'globalid','seq'=>'_nid_seq'),
                        array('globalid','newsletter_body','newsletter_subject')
			);
    $this->load();
  }

  /**
   * load from database
   * 
   */  
  public function load() {

    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query("SELECT * FROM ".DB_TBL_GLOBALS.
                                 " WHERE tag='newsletter' AND tagproperty='template'");
      $row =& $result->fetch(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    // must be set for insert if not available
    if(is_array($row)) {
      $this->globalid = $row['globalid'];
      $aTemplate = unserialize($row['tagvalue']); // it is serialized in the insert/update
      foreach($aTemplate as $key=>$value) {
        $this->$key = $value;
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
      $tagvalue = serialize(array('newsletter_subject'=>$this->newsletter_subject,'newsletter_body'=>$this->newsletter_body));
      $result = self::$db->exec('INSERT INTO ' . DB_TBL_GLOBALS .
                                ' (tag, tagproperty, tagvalue) VALUES ('.
                                '\'newsletter\',\'template\','.$this->quote($tagvalue).');');
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
      $tagvalue = serialize(array('newsletter_subject'=>$this->newsletter_subject,'newsletter_body'=>$this->newsletter_body));
      $result = self::$db->exec('UPDATE ' . DB_TBL_GLOBALS .
                                ' SET tagvalue = '.$this->quote($tagvalue).'  WHERE globalid='.(int)$this->globalid);
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
    $this->form     = new ON_QuickForm($formName, 'post');
  }

  /**
   * Fill the send a bulletin form
   * 
   */
  public function fillSendForm() {
    // add elements
    $this->form->addElement('text', 'addtomail', _('E-mail'));
    $this->form->setAddNoteTemplate('addtomail',
				    _('If you find a copy of this bulletin please provide an e-mail'), true);
    $this->form->addElement('submit', 'sb', _('Send'), 'class="sb"');
  }

  /**
   * Fill the edit bulletin values form
   * 
   */
  public function fillForm() {

    // set form defaults, $defaults array will be used after update/insert process
    $defaults = $this->defaults();
    $this->form->setDefaults($defaults);
    
    // add elements
    $this->form->addElement('text', 'newsletter_subject', _('Bulletin Subject'));
    $this->form->addRule('newsletter_subject', _('Subject required'), 'required', null, 'client');

    $this->form->addElement('textarea', 'newsletter_body', _('Bulletin Body'), 'id="newsletter_body"');
    $this->form->setRichTextTemplate('newsletter_body');
    $this->form->addRule('newsletter_body', _('Bulletin body required'), 'newsletter_body', 'required', 'client');

    $this->form->addElement('submit', 'sb', _('Save'), 'class="sb"');
  }

  /**
   * Set values
   *
   * @param array $values The form values filled by panel user
   */
  public function setValues(&$values) {
    $this->newsletter_subject  = ON_Filter($values['newsletter_subject']);
    $this->newsletter_body     = ON_Filter($values['newsletter_body']);
  }
}


?>