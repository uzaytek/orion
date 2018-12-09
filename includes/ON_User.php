<?php

/**
 * User table dao object 
 * 
 */  
class ON_User extends ON_Dao
{

  /**
   * Pear Quickform ojbect, ON_QuickForm
   * 
   * @var object
   */  
  public $form;

  /**
   * If form values valid true otherwise false for insert/update process
   * 
   * @var boolean
   */  
  public $isFormValid;

  /**
   * static property to hold singleton instansce
   * 
   */
  static $instance = false;

  /**
   * private or protected so only getInstance method can instatiate
   * 
   * @return void
   */
  protected function __construct()
  {
    parent::__construct(array('table'=> DB_TBL_USERS, 'pk'=>'userid', 'seq'=>'user_nid_seq'),
			array('userid','email','username','passwd','salt','firstname','lastname',
			      'address','postcode','city','country','phone',
			      'bankname','bankaccount', 'taxoffice','taxnumber',
			      'billname', 'billaddress','billpostcode','billcity','billcountry','billphone','billfax',
			      'notes','isdeleted','dtcreated','dtdeleted','dtmodified','dtlastlogin',
			      'ticket')
			);
  }

  /**
   * factory method to return singleton instance
   * 
   * @return ON_User
   */
  public function getInstance() {
    if(!ON_User::$instance) {
      ON_User::$instance = new ON_User;
    }
    return ON_User::$instance;
  }

  /**
   * generates random ticket for user/session
   * 
   * @return string
   */
  public function setTicket() {    
    // make sure ticket is unique
    do {
      $ticket = genRandStr(32);
      $res =& $db->query('SELECT ticket FROM ' .$this->reg['table'] .
                         ' WHERE ticket='.$db->quote($ticket,'text'));
    } while($res->numRows() > 0);
    $this->ticket = $ticket;
  }

  /**
   * load user with a ticket
   * 
   * @return boolean if load successfull, false otherwise
   */
  public function loadTicket($ticket) {

    $data = $db->getRow('SELECT userid FROM '. $this->reg['table'] .
                        ' WHERE ticket='.$db->quote($ticket,'text'));
    if(is_array($data['userid'])) {
      return $this->load($data['userid']);
    }
    return false;
  }

  /**
   * Set user values
   * 
   * @param array $values The form values filled by panel user
   */  
  public function setValues(&$values) {
    if(isset($values['email'])) $this->email = ON_Filter($values['email']);
    if(isset($values['username'])) $this->username = ON_Filter($values['username']);
    if($this->salt == '') $this->salt = genRandStr(3);
    if(isset($values['passwd'])  && trim($values['passwd']) != '') $this->passwd = md5(md5($values['passwd']).md5($this->salt));
    if(isset($values['firstname'])) $this->firstname   = ON_Filter($values['firstname']);
    if(isset($values['lastname'])) $this->lastname     = ON_Filter($values['lastname']);      
    if(isset($values['address'])) $this->address       =  ON_Filter($values['address']);
    if(isset($values['postcode'])) $this->postcode     =  ON_Filter($values['postcode']);
    if(isset($values['city'])) $this->city         =  ON_Filter($values['city']);
    if(isset($values['country'])) $this->country      =  ON_Filter($values['country']);
    if(isset($values['phone'])) $this->phone        = ON_Filter($values['phone']);
    if(isset($values['billname'])) $this->billname     = ON_Filter($values['billname']);
    if(isset($values['billaddress'])) $this->billaddress  = ON_Filter($values['billaddress']);
    if(isset($values['billpostcode'])) $this->billpostcode = ON_Filter($values['billpostcode']);
    if(isset($values['billcity'])) $this->billcity     = ON_Filter($values['billcity']);
    if(isset($values['billcountry'])) $this->billcountry      =  ON_Filter($values['billcountry']);
    if(isset($values['billphone'])) $this->billphone    = ON_Filter($values['billphone']);
  }

  /**
   * Attach the billing information form elements to given form
   * 
   * @param object $form reference of ON_QuickForm form object which billing form will be attached 
   */  
  public function attachBillingForm(&$form) {
    // set form defaults
    $defaults = $this->defaults();

    list($aCountries, $defaultCountry) = ON_Global::getAllCountries();
    if ($defaults['billcountry'] == 0) {
      $defaults['billcountry'] = $defaultCountry;
    }

    // set form defaults, $defaults array will be used after update/insert process      
    $form->setDefaults($defaults);

    $form->addElement('hidden', 'reviewok');

    // billing information
    $form->addElement('header', 'billing', _('Billing Information')); 

    $form->addElement('text', 'billname', _('Billing Name')); 
    $form->addRule('billname', _('Billing name required'), 'required', null, 'client');

    $form->addElement('textarea', 'billaddress', _('Billing Address'), array('cols'=>40, 'rows'=>2));
    $form->addRule('billaddress', _('Billing address required'), 'required', null, 'client');

    $form->addElement('text', 'billpostcode', _('Post Code'), array('size' => 10, 'maxlength' => 5));

    $form->addElement('text', 'billcity', _('City'));
    $form->addRule('billcity', _('City required'), 'required', null, 'client');

    $form->addElement('select', 'billcountry', _('Country'), $aCountries);
    $form->addRule('billcountry', _('Country required'), 'required', null, 'client');

    $form->addElement('text', 'billphone', _('Telephone'), array('size' => 15, 'maxlength' => 30));
    $form->addRule('billphone', _('Telephone required'), 'required', null, 'client');

  }

  /**
   * Insert user values to db
   *
   * @param integer $userid The database row id 
   */  
  public function insert(&$userid) {

    if($this->username == '' AND filter_var($this->email, FILTER_VALIDATE_EMAIL)===false) {
      throw new Exception('User insert username or email failed');
    }

    if(!$this->dtcreated) {
      $this->dtcreated = $this->getDate('dt');
    }
    $this->isdeleted = 0;

    $trbeginbyme = false;
    if(!$this->inTransaction()) {
      // if we already in a transaction process(payment.insert) dont start a new one
      $trbeginbyme = parent::beginTransaction();
    }

    $flag = parent::insert($userid);

    if ($flag && $trbeginbyme) {
      parent::commitTransaction();
    }
    if ($flag) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Update user values to db
   *
   * @param integer $userid The database row id 
   */  
  public function update($userid) {

    if ($this->username == '' AND filter_var($this->email, FILTER_VALIDATE_EMAIL)===false) {
      return false;
    }

    $trbeginbyme = false;
    if(!$this->inTransaction()) {
      // if we already in a transaction process(payment.insert) dont start a new one
      $trbeginbyme = parent::beginTransaction();
    }
    $flag = parent::update($userid);    

    if ($flag && $trbeginbyme) {
      parent::commitTransaction();
    }
    if ($flag) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Insert e-bulletin subscription 
   *
   * @param string $email The subscribed user email
   * @return integer affected rows count
   */  
  public function insertBulletinSubscription($email) {
    if(!self::$db) $this->connect();
    try {
      $result =& self::$db->exec('INSERT INTO ' . DB_TBL_UBULLETIN . '(email)' .
                                 ' VALUES(' . $this->quote(ON_Filter($email)) . ')');
      return $result;
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }   
  }

  /**
   * e-bulletin user count
   *
   * @return integer e-bulletin user count
   */  
  public function numRowsBulletinUsers() {
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query('SELECT COUNT(*) AS NUMROWS FROM '.DB_TBL_UBULLETIN.' AS s ');
      return $result->fetchColumn();
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

  /**
   * return all e-bulletin users
   *
   * @return array all e-bulletin users
   */  
  public function getBulletinUsers() {
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query('SELECT * FROM '. DB_TBL_UBULLETIN.' AS s ');
      return $result->fetchAll();
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }


  /**
   * Register quickform object
   * 
   * @param string $formName The html form name 
   */
  public function registerForm($formName='registerUser',$method='post', $action='') {
    //formname, method, action, target, attributes, tracksubmit    
    $this->form = new ON_QuickForm($formName, $method, $action, '', 'class="register"', true); 
  }

  /**
   * Call quickform form validate
   * if form valid true call setValues
   *
   * @return boolean isFormValid
   */  
  public function validate() {
    $this->isFormValid = $this->form->validate();
    if ($this->isFormValid) {
      $values =& $this->form->exportValues();
      $this->setValues($values, $this->form);      
    }
    return $this->isFormValid;
  }

  /**
   * return default values
   * 
   */  
  public function defaults() {
    $defaults = parent::defaults();
    $defaults['passwd'] = ''; // empty md5 value

    return $defaults;
  }
  
  /**
   * register form elements, function must be seperate from registerForm method
   * because of database values which come from $this->defaults method
   * 
   */  
  public function fillRegisterForm() {
    // set form defaults
    $defaults = $this->defaults();

    list($aCountries, $defaultCountry) = ON_Global::getAllCountries();
    if ($defaults['country'] == 0) {
      $defaults['country'] = $defaultCountry;
    }

    $this->form->setDefaults($defaults);

    if ($this->userid > 0) {
      $enc = new ON_Enc();
      $this->form->addElement('hidden', 'id', $enc->encrypt($this->userid));
    }

    if (isset($_REQUEST['currentpage'])) {
      $this->form->addElement('hidden', 'currentpage', intval($_REQUEST['currentpage'])); 
    }

    $this->form->addElement('text', 'username', _('Username')); 
    $this->form->addRule('username', _('Username required'), 'required', null, 'client');
    $this->form->registerRule('isUsernameUnique', 'function', 'isUsernameUnique');
    $errorName = _('Username name already exists, please change it with a new one.');
    $this->form->addRule('username', $errorName,
                         'isUsernameUnique', array('userid' => $this->userid));

    $this->form->addElement('password', 'passwd', _('Password'));
    if ($this->userid == 0) {
      $this->form->addRule('passwd', _('Password required'), 'required', null, 'client');
    }

    $this->form->addElement('password', 'passwd2', _('Password Again'));      
    if ($this->userid == 0) {
      $this->form->addRule('passwd2', _('Password again field'), 'required', null, 'client');
    }
    $this->form->addRule(array('passwd', 'passwd2'), 
                         _('Password and Password again field are different, please control'), 'compare');
		       
    $this->form->addElement('text', 'email', _('E-mail')); 
    $this->form->addRule('email', _('E-mail required'), 'required', null, 'client');
    $this->form->addRule('email', _('E-mail must be a valid address'), 'email', null, 'client');

    $this->form->addElement('text', 'firstname', _('First Name')); 
    $this->form->addRule('firstname', _('First name required'), 'required', null, 'client');

    $this->form->addElement('text', 'lastname', _('Last Name')); 
    $this->form->addRule('lastname', _('Last name required'), 'required', null, 'client');

    $this->form->addElement('textarea', 'address', _('Address'), array('cols'=>40, 'rows'=>4));
    $this->form->addRule('address', _('Address required'), 'required', null, 'client');

    $this->form->addElement('text', 'postcode', _('Post Code'), array('size' => 10, 'maxlength' => 5));

    $this->form->addElement('text', 'city', _('City'));
    $this->form->addRule('city', _('City required'), 'required', null, 'client');

    $this->form->addElement('select', 'country', _('Country'), $aCountries);
    $this->form->addRule('country', _('Country required'), 'required', null, 'client');

    $this->form->addElement('text', 'phone', _('Telephone'), array('size' => 15, 'maxlength' => 20));
    $this->form->addRule('phone', _('Telephone required!'), 'required', null, 'client');
    $this->form->setAddNoteTemplate('phone', _('Format: Country Code - City Code - Tel Number'), true);

    if (IN_ADMIN_PANEL == TRUE) {
      $this->attachBillingForm($this->form);
    }

    $this->form->addElement('submit', 'submitUser', _('Save'), 'class="sb"');    
  }

  /**
   * make a login form
   * 
   */  
  public function loginForm($formName, $method='post', $action='') {
    //formname, method, action, target, attributes, tracksubmit    
    $this->form = new ON_QuickForm($formName, $method, $action, '', 'class="register"', true); 
    $this->form->setTableFormTemplate();
    $this->form->hideRequiredNote();
    
    $output = '';
    // add elements
    $this->form->addElement('text', 'username', _('Username')); 
    $this->form->addRule('username', _('Username required'), 'required', null, 'client');
 
    $this->form->addElement('password', 'passwd', _('Password'));
    $this->form->addRule('passwd', _('Password required'), 'required', null, 'client');

    $this->form->addElement('submit', 'sb', _('Login'), 'class="sb"');
  }

  /**
   * if count of failed login attempt big than 5 return true 
   *
   * @param string $username The username of the login process
   * @return boolean true if numrows count big than 5, false otherwise
   */  
  public function isLoginStrike($username) {
    if(!self::$db) $this->connect();
    try {
      $result = self::$db->query('SELECT COUNT(username) AS NUMROWS FROM ' . DB_TBL_LOGINSTRIKES .
                                 ' WHERE username='.$this->quote($username).
                                 ' AND dtcreated > DATE_SUB(NOW(), INTERVAL 15 MINUTE)');
      $numrows = $result->fetchColumn();
      //      echo "<h1>Failed Login : $numrows </h1>";
      if ($numrows > 5) {
        return true;
      }
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    return false;
  }
  
  /**
   * Insert a username to login strike table
   *
   * @param string $username The username
   * @return integer affected rows count
   */  
  public function insertLoginStrike($username) {
    if(!self::$db) $this->connect();
    try {
      return self::$db->query('INSERT INTO ' . DB_TBL_LOGINSTRIKES .' (username,dtcreated) VALUES (' . 
                              $this->quote($username) . ', NOW())');
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

  /**
   * Control login process
   *
   * @param array $values login form values
   * @param integer $userid The user table id
   * @return boolean if login successfull, false otherwise
   */  
  public function login(&$values, &$userid) {
    if ($this->form->validate()) {

      if ($this->isLoginStrike($values['username'])) {
        ON_Say::add(fmtError(_('Your IP Banned for 15 minutes, if you lost your password use lost password page')));
        return false;
      }

      if ($this->isUserExists($userid, $values['username'], $values['passwd'])) {
        return true;
      } else {
        // if user doesnt exists or failed password, add a new strike attemp for username
        $this->insertLoginStrike($values['username']);
      } // end login process

    }// end if form validate
    return false;    
  }

  /**
   * if username/password right return true
   *
   * @param integer $userid The user table id
   * @param string  $username The username
   * @param string  $passwd The password
   * @return boolean true if user exists, false otherwise
   */  
  public function isUserExists(&$userid, $username, $passwd) {
    try {
      if(!self::$db) $this->connect();
      $res = self::$db->query('SELECT userid FROM ' .$this->reg['table'] .
                              ' WHERE username=' . $this->quote($username) . 
                              ' AND passwd = md5(CONCAT(md5('. $this->quote($passwd) . '), md5(salt)))'); 
      $userid = $res->fetchColumn();
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    if ($userid) {
      return true;
    }
    return false;
  }

  /**
   * Check if username exists in the database, used in the new user application
   * 
   * @param string  $username The username value
   * @param integer $userid The userid if returned user, admin panel user edits
   * @return string Username string if available
   */
  public function isUsernameExists($username, $userid=0) {
    if(!self::$db) $this->connect();
    try {
      $where = ($userid > 0) ? 'AND userid!='.$userid : '';
      $result =& self::$db->query('SELECT username FROM ' . $this->reg['table'].				  
                                  ' WHERE username='.$this->quote($username).$where);
      return $result->fetchColumn(); 
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

}


?>