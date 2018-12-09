<?php

/**
 * Get backtrace information for sql loging
 *
 * trace runs in the development environment 
 */
class DebugBacktrace {

  /**
   * Start trace
   * 
   * @param integer $start Start time
   * @param string $trace Trace information
   * @param string $query The query
   */
  public static function start(&$start, &$trace, &$query=null) {
    if(ENVIRONMENT == 'DEVELOPMENT') {
      $start = microtime(true);    
      $bt = debug_backtrace();
      $trace = '';
      foreach($bt as $line) {
        $args = var_export($line['args'], true);
        $trace .= (isset($line['class']) ? $line['class'].': ' : '').
          "{$line['function']}($args) at {$line['file']}:{$line['line']}<br />";
      }
    }
  }

  /**
   * Stop trace
   * 
   * @param integer $start Start time
   * @param string $trace Trace information
   * @param string $query The query
   */
  public static function stop(&$start, &$trace, &$query) {
    if(ENVIRONMENT == 'DEVELOPMENT') {
      $time = microtime(true) - $start;
      LoggedPDO::$log[] = array('trace' => $trace,
                                'query' => $query,
                                'time' => round($time * 1000, 3));
    }
  }
}


/**
 * Extends PDO and logs all queries that are executed and how long
 * they take, including queries issued via prepared statements
 */
class LoggedPDO extends PDO
{
  public static $log = array();
  
  public function __construct($dsn, $username = null, $password = null) {
    parent::__construct($dsn, $username, $password);
  }
  
  /**
   * Print out the log when we're destructed. I'm assuming this will
   * be at the end of the page. If not you might want to remove this
   * destructor and manually call LoggedPDO::printLog();
   */
  public function __destruct() {
    if (ENVIRONMENT == 'DEVELOPMENT') {
      echo self::printLog();
    }
  }

  /**
   * start time and call PDO::query and save query to log array
   * 
   * @return PDO::query result  
   */  
  public function query($query) {
    DebugBacktrace::Start($start, $trace, $query);
    try {
      $result = parent::query($query);
    } catch (PDOException $e) {
      die($e->getMessage());
    }
    DebugBacktrace::Stop($start, $trace, $query);
    return $result;
  }

  /**
   * start time and call PDO::execute and save query to log array
   * 
   * @return PDO::exec result  
   */  
  public function exec($query) {
    DebugBacktrace::Start($start, $trace, $query);
    $result = parent::exec($query);
    DebugBacktrace::Stop($start, $trace, $query);
    return $result;
  }
  
  /**
   * @return LoggedPDOStatement
   */
  public function prepare($query) {
    return new LoggedPDOStatement(parent::prepare($query));
  }
  
  /**
   * Print log array, calculate total query time 
   * 
   * @return string log array print
   */  
  public static function printLog() {    
    $totalTime = 0;
    $s = (isset($_SESSION)) ? print_r($_SESSION,true) : '';
    $out = '<div style="clear:both">'.
      '<div>SESSION<p><pre>'.$s.'</pre></p></div>'.
      '<table border=1><tr><th>Trace</th><th>Query</th><th>Time (ms)</th></tr>';
    foreach(self::$log as $entry) {
      $totalTime += $entry['time'];
      $out .= '<tr><td>' . $entry['trace'] . '</td><td> ' . $entry['query'] . '</td><td>' .
        $entry['time'] . '</td></tr>';
    }
    $out .= '<tr><th>' . count(self::$log) . ' queries</th><th>' . $totalTime . '</th></tr>';
    $out .= '</table></div>';
    return $out;
  }  
}

/**
 * PDOStatement decorator that logs when a PDOStatement is
 * executed, and the time it took to run
 * @see LoggedPDO
 */
class LoggedPDOStatement {
  /**
   * The PDOStatement we decorate
   */
  private $statement;
  
  public function __construct(PDOStatement $statement) {
    $this->statement = $statement;
  }
  
  /**
   * When execute is called record the time it takes and
   * then log the query
   * @return PDO result set
   */
  public function execute($params) {

    DebugBacktrace::Start($start, $trace);

    $result = $this->statement->execute($params);


    $rv = $this->statement->queryString;
    
    foreach ($params as $k=>$v) {
      if (!is_numeric($v)) {
        $params[$k]="'".str_replace("'", "''", $v)."'";
      }
    }
    
    $txt_log = '[PS] ';
    if (strstr($rv,'?')) {
      while (preg_match('/\?/', $rv)) {
        $rv = preg_replace('/\?/','%'.(++$i).'$s',$rv,1);
      }
      $txt_log .= vsprintf($rv, $params);
    } else if(strstr($rv,':')) {
      $txt_log .= strtr($rv, $params);
    }

    DebugBacktrace::Stop($start, $trace, $txt_log);    
    return $result;
  }
  
  /**
   * Other than execute pass all other calls to the PDOStatement object
   * @param string $function_name
   * @param array $parameters arguments
   */
  public function __call($function_name, $parameters) {
    return call_user_func_array(array($this->statement, $function_name), $parameters);
  }
}


/**
 * Orion Database Access Object
 *
 * All database tables have to their dao class 
 * 
 */
class ON_Dao {

  /**
   * Register of table, private key, sequences variables
   *
   * @var array
   */
  protected $reg;

  /**
   * Database fields
   *
   * @var array
   */
  protected $fields;

  /**
   * Sql where query part
   *
   * @var string
   */
  protected $where;

  /**
   * Sql order by query part
   *
   * @var string
   */
  protected $orderby;


  /**
   * PDO connection object
   *
   * @var object
   * @see connect function
   */
  protected static $db = false;

  /**
   * Boolean shows queries have to be in a transsaction
   *
   * @var boolean
   * @see beginTransaction, commitTransaction functions
   */
  protected static $intransaction = false;
  

  /**
   * Populate variables
   */
  protected function __construct($reg=null, $fields=null) {
    if (isset($reg['table'])) {
      $this->reg['table']  = $reg['table'];
    }
    if (isset($reg['pk'])) {
      $this->reg['pk']   = $reg['pk'];
    }
    if(isset($reg['seq'])) {
      $this->reg['seq']  = $reg['seq'];
    }
    if (isset($fields)) {
      foreach ($fields as $key) {
        $this->fields[':'.$key] = null;
      }
    }
  }
  
  /**
   * Connect database set names,character set according to version
   */
  public function connect() {
    try {
      self::$db = new LoggedPDO('mysql:host=' . DB_HOST . ';dbname='.DB_DBASE, DB_USER, DB_PASSWORD);
      self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $res = self::$db->query("SHOW VARIABLES LIKE 'version'");
      $mysql_version = $res->fetch();
      if(version_compare($mysql_version[1], '4.2.0', '>=')>0) {
        self::$db->query("set names utf8"); 
        self::$db->query("set character set utf8"); 
        self::$db->query("set character_set_results = utf8");
      }
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

  /**
   * return database variable
   *
   * @param string $key database field variable
   * @return string
   */
  public function __get($key) {
    return $this->fields[':'.$key];
  }

  /**
   * set database variable if exists in fields
   *
   * @param string $key database field key
   * @param string $value database field value
   * @return boolean True if success otherwise throw exception
   */
  public function __set($key, $value) {
    if (array_key_exists(':'.$key, $this->fields)) {
      $this->fields[':' . $key] = $value;
      return true;
    } else {
      throw new Exception(_('Undefined Class Property: ') . $key);
    }
  }

  /**
   * load database object
   * 
   * @param integer $theid primary key of table
   * @return boolean True if success otherwise false
   */
  public function load($theid) {
    
    try {
      if(!self::$db) $this->connect();
      $this->setWhere($this->reg['pk'] .'='. (int)$theid, ' AND ');
      $result = self::$db->query('SELECT * FROM '. $this->reg['table'] . $this->where());
      $row =& $result->fetch(PDO::FETCH_ASSOC); 
      $this->where = ''; // empty $where for next queryies
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    
    if(is_array($row)) {
      foreach($row as $key=>$value) {
        $this->$key = $value;
      }
      return true;
    } else {
      return false;
    }
  }

  /**
   * find numrows
   * 
   * @return int
   */
  public function numRows() {
    try {
      if(!self::$db) $this->connect();
      $_query = 'SELECT COUNT(*) AS numrows FROM ' . $this->reg['table'] . $this->where();      
      $result = self::$db->query($_query);
      return (int)$result->fetchColumn(); 
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

  /**
   * select all rows according to where()
   * 
   * @return array
   */
  public function selectRows() {
    try {
      if(!self::$db) $this->connect();
      $_query = 'SELECT * FROM '. $this->reg['table'] . $this->where();
      $result = self::$db->query($_query);
      return $result->fetch(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

  /**
   * Paginate rows
   * 
   * @param object $pager Pager object for pagination
   * @param integer $numrows The row count of the table
   * @param array $pagerOptions Pager options
   * @param string $where Add to query if exists
   * @return boolean True if success otherwise false
   */  
  public function pager(&$pager, &$numrows, $pagerOptions=null, $where=null) {
    
    $query = 'SELECT a.*'.
      ' FROM ' . $this->reg['table'] . ' a '. 
      ' WHERE (a.isdeleted=0 OR a.isdeleted IS NULL)';
    
    if ($where) {$query .= ' AND '.$where;}
    $query .= $this->orderby();
    return $this->_pager($pager, $numrows, $pagerOptions, $query);
  }
  
  
  /**
   * Paginate database records according to limit, offset
   * 
   * @param object $pager Pagination object 
   * @param integer $numrows table numrows
   * @param array $pagerOptions Pager options
   * @param string $query where we paginatate
   * @return boolean True if success otherwise false
   */
  public function _pager(&$pager, &$numrows, $pagerOptions, $query) {
    if(!self::$db) $this->connect();
    // query for $numrows, i.e. dont select all rows just count(*) numrows
    $pos_from = strpos($query, 'FROM');
    if (!$pos_from) {
      throw new Exception(sprintf(_('Query: %s must have a "FROM" clause'), $query));
    }
    if ($numrows == 0) {
      $query_count = 'SELECT COUNT(*) AS NUMROWS ' . substr($query, $pos_from);
      $result = self::$db->query($query_count);
      $numrows = $result->fetchColumn();
    }

    $pager = new ON_Pager($numrows, $alimit, $pagerOptions);

    if ($numrows > 0) {      
      // we must use OFFSET for postgresql
      $slimit = (is_array($alimit) && $numrows > 1) ? ' LIMIT ' . $alimit['limit'] . ' OFFSET ' .$alimit['offset'] : '';
      $result =& self::$db->query($query . $slimit);      
    } else {
      $result = false;
    }
    return $result;
  }
  
  /**
   * Return all rows according to where
   * 
   * @return array Database rows
   */
  public function getAll() {
    
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query('SELECT * FROM '. $this->reg['table'] . $this->where() . $this->orderby());
      $rows =& $result->fetchAll(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    return $rows;
  }

  /**
   * Insert field values to table
   * 
   * @return boolean PDO::statement execute 
   */
  public function insert(&$insertid=0) {
    $insertid = 0;
    try {
      if(!self::$db) $this->connect();
      $keys = implode(',', array_keys($this->fields));
      $cols = str_replace(':', '', $keys);
      
      // if sequences set call next_seq_id and set insertid in the fields
      if (isset($this->reg['pk'])) {
        $insertid = $this->{$this->reg['pk']} = $this->next_seq_id();
      }
      // PDO::prepare,execute
      $stmt = self::$db->prepare('INSERT INTO ' . $this->reg['table'] .' ('.$cols . ') VALUES (' . $keys . ')');
      $ret = $stmt->execute($this->fields);
    } catch (PDOException $e) {
      // if in transaction rollback transaction
      try { self::$db->rollBack(); } catch (PDOException $e2) {};
      $this->fatal_error($e->getMessage());
    }
    return $ret;
  }

  /**
   * Return next sequences
   *
   * in postgre sequences
   * public function next_seq_id($name) {
   * $res = self::$db->query('SELECT NEXTVAL(\''.$this->reg['seq'].'\')');
   * $row = $res->fetch();
   * return $row[0];
   * } 
   *
   * @return integer auto_increment value
   */
  public function next_seq_id() {
    $res = self::$db->query('SHOW TABLE STATUS LIKE "'.$this->reg['table'].'"');
    $row = $res->fetch();
    return (int)$row['Auto_increment'];
  }
  
  /**
   * Update field values to table
   * 
   * @return boolean PDO::statement execute 
   */
  public function update($theid) {
    
    try {
      if(!self::$db) $this->connect();
      $akeys = array_keys($this->fields);
      $cols = '';
      foreach($akeys as $key) {
        $cols .= str_replace(':', '', $key).'='.$key.',';
      }
      $cols = substr($cols, 0, -1);//trim last ','
      $primary_col = $this->reg['pk']. ' = '.(int)$theid;
      $where = (!$this->where()) ? ' WHERE '.$primary_col : $this->where() . ' AND '.$primary_col;
      $stmt = self::$db->prepare('UPDATE '.$this->reg['table'].' SET '.$cols . $where);
      $ret = $stmt->execute($this->fields);
    } catch (PDOException $e) {
      try { self::$db->rollBack(); } catch (PDOException $e2) {};
      $this->fatal_error($e->getMessage());
    }
    return $ret;
  }
  
  /**
   * Return boolean $intransaction value if in a transaction true
   * 
   * @return boolean $intransaction
   */
  public function inTransaction() {
    return self::$intransaction;
  }

  /**
   * Begin a transaction
   * 
   * @return boolean Return true if start successfully
   */
  public function beginTransaction() {
    try {
      if(!self::$db) $this->connect();
      if(self::$db->beginTransaction()) self::$intransaction = true;
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    return true;
  }

  /**
   * Commit transaction
   * 
   * @return boolean Return true if commit successfully
   */
  public function commitTransaction() {
    try {
      self::$db->commit();
    } catch (PDOException $e) {
      try { self::$db->rollBack(); } catch (PDOException $e2) {};
      $this->fatal_error($e->getMessage());
    }
    return true;
  }

  /**
   * Return fields values
   * 
   * @return array table[fieldname] = fieldvalue
   */
  public function defaults() {    
    $out = array();
    if (count($this->fields)) {
      foreach($this->fields as $key=>$value) {
        $k = str_replace(':', '', $key);
        $out[$k] = $value;
      }
    }
    return $out;
  }

  /**
   * Update only is_xxx fields i.e. isonline, isdeleted
   * 
   * @return integer number of rows were affected
   */
  public function isupdate($set, $theid) {
    try {
      if(!self::$db) $this->connect();
      $where = ($theid > 0) ? ' WHERE '.$this->reg['pk']. ' = '.(int)$theid : $theid;
      $result = self::$db->query('UPDATE ' . $this->reg['table'] . ' SET ' . $set . $where);
    } catch (PDOException $e) {
      try { self::$db->rollBack(); } catch (PDOException $e2) {};
      $this->fatal_error($e->getMessage());
    }
    return $result;    
  }

  /**
   * Return quoted paramater
   * 
   * @return string The quoted parameter
   */
  public function quote($val) {
    return '\''.$val.'\'';
  }
  

  /**
   * Return date(time) value according to $type
   * 
   * @param string $type datetime type(database long date, database normal date, output long date, output date)
   * @param string $time Datetime value
   * @return string Datetime value
   */
  public function getDate($type, $time=null) {

    if (!$time) {
      $_time = time();
    } else {
      // from output(form value) to input database short value
      if (ereg("([0-9]{2})/([0-9]{2})/([0-9]{4})", $time, $aRegs)) {
        $_time = mktime(0, 0, 0, $aRegs[2], $aRegs[1], $aRegs[3]);
      }

      // from output(form value) to input database
      if (ereg("([0-9]{2})/([0-9]{2})/([0-9]{4}) ([0-9]{2}):([0-9]{2}):([0-9]{2})", $time, $aRegs)) {
        $_time = mktime($aRegs[4], $aRegs[5], $aRegs[6], $aRegs[2], $aRegs[1], $aRegs[3]);
      }
      // databese to output(form value or display purpose) 
      if (ereg("([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})", $time, $aRegs)) {
        $_time = mktime($aRegs[4], $aRegs[5], $aRegs[6], $aRegs[2], $aRegs[3], $aRegs[1]);
      }
    }
    switch ($type) {
    case 'dt': // an old one
    case 'il': // input long
      return date('Y-m-d H:i:s', $_time);
      break;
    case 'i': // input
      return date('Y-m-d', $_time);
      break;
    case 'o': // output 
      if ($time) {
        return date('d/m/Y', $_time);
      }
      break;
    case 'ol': // output long
      return date('d/m/Y H:i:s', $_time);
      break;      
    default:
      throw new Exception(_('Date/time type not implemented in the class'));
      break;
    }
  }

  /**
   * Echo error message, debug_backtrace output and die
   * 
   */
  protected function fatal_error($msg) {
    echo "<pre>Error!: $msg\n";
    $bt = debug_backtrace();
    foreach($bt as $line) {
      $args = var_export($line['args'], true);
      echo "{$line['function']}($args) at {$line['file']}:{$line['line']}\n";
    }
    echo "</pre>";
    die();
  }

  /**
   * Return where query
   * 
   * @return string where query
   */
  protected function where() {
    return ($this->where != '') ? ' WHERE ' . $this->where : '';    
  }

  /**
   * set where query
   * 
   * @param $where The string query
   * @param $op The operator 
   */
  public function setWhere($where, $op=' AND ') {
    if ($this->where != '' AND $op != '') {
      $this->where .= $op . $where;
    } else {
      $this->where = $where;
    }
  }

  /**
   * Return order by query
   * 
   * @return string order by
   */
  protected function orderby(){
    return ($this->orderby != '') ? ' ORDER BY '.$this->orderby : '';    
  }

  /**
   * Set orderby query
   * 
   */
  public function setOrder($orderby) {
    $this->orderby = $orderby;
  }

}

?>
