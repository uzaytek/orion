<?php

/**
 * Global table saves variables for other objects such as theme, newsletter
 * 
 * Access all global variables a generic way, 
 * global class methods uses/access other tables
 *
 */
class ON_Global extends ON_Dao {

  /**
   * Contruct object
   */
  public function __construct()
  {
    parent::__construct(array('table'=> DB_TBL_GLOBALS, 'pk'=>'globalid','seq'=>'global_nid_seq'),
                        array('tag','tagproperty','tagvalue')
			);
  }

  /**
   * Add global variables to parameter array
   *
   * @param array $_conf Config array if exists in the table
   * @return void
   */
  public function getDBConfig($_conf) {
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query('SELECT * FROM '. $this->reg['table'] . $this->where() . $this->orderby());
      $rows =& $result->fetchAll(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    
    if (is_array($rows)) {
      foreach ($rows as $row) {
        $_conf[$row['tag']][$row['tagproperty']] = $row['tagvalue'];
      }
    }
  }// getdbconfig

  /**
   * Return all countr(y/ies)
   * 
   * If parameter country > 0 return specific country information
   * otherwise return all country information, Second element is 
   * default country id for select box, i.e. selected item
   *
   * @param integer $country Country table id
   * @return array(all data, default country id)
   */
  function getAllCountries($country=0) {
    $where = ($country > 0) ? ' WHERE id='.(int)$country : '';

    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query('SELECT * FROM '. DB_TBL_COUNTRIES . $where);
      $rows =& $result->fetchAll(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    $default = 0;
    if (is_array($rows)) {
      foreach ($rows as $row) {
        $data[$row['id']] = $row['country'];
        if ($row['isdefault'] > 0) {
          $default = $row['id'];
        }
      }
      return array($data,$default);
    }
  }

}
  
?>