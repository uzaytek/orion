<?php


/**
 * Statistics total table dao class
 *
 */ 
class ON_StatTotal extends ON_Dao
{

  /**
   * Construct
   *
   */ 
  public function __construct()
  {
    parent::__construct(array('table'=> DB_TBL_STATTOTALS),
                        array('statid','stattype','useraction','monthvalue','yearvalue','monthtotal')
			);
  }

  /**
   * Set stat total values
   * 
   * @param array $values total values come from caller
   */  
  public function setValues(&$values) {
    $this->statid     = (int)$values['statid'];
    $this->stattype   = (int)$values['stattype'];
    $this->useraction = (int)$values['useraction'];
    $this->monthvalue = (int)$values['monthvalue'];
    $this->yearvalue  = (int)$values['yearvalue'];
    $this->monthtotal = (int)$values['monthtotal'];
  }

  /**
   * Return stat totals of user actions
   * 
   * @param integer $month requested month
   * @return PDOStatement object; resource of the executed query
   */  
  public function getRotateTotal($month) {
    if ($month > 0) {
      $qry ='SELECT s.statid,s.stattype, MAX(EXTRACT(MONTH FROM s.dtcreated)) as monthvalue,'.
        ' MAX(EXTRACT(YEAR FROM s.dtcreated)) as yearvalue, '.
        ' SUM(CASE WHEN s.useraction='.ON_USER_ACTION_VIEW.' THEN 1 ELSE 0 END) AS ui,'.
        ' SUM(CASE WHEN s.useraction='.ON_ANON_ACTION_VIEW.' THEN 1 ELSE 0 END) AS ai,'.
        ' SUM(CASE WHEN s.useraction='.ON_USER_ACTION_SHARE.' THEN 1 ELSE 0 END) AS ut,'.
        ' SUM(CASE WHEN s.useraction='.ON_ANON_ACTION_SHARE.' THEN 1 ELSE 0 END) AS at'.
        ' FROM ' .DB_TBL_STATS .' s'.
        ' WHERE EXTRACT(MONTH FROM s.dtcreated) =' .$month .
        ' GROUP BY s.statid, s.stattype';
    }
    try {
      if(!self::$db) $this->connect();
      return self::$db->query($qry);
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }      
  }

  /**
   * Return distinct years from stat total table
   * 
   * @return array years
   */  
  public function getDistinctYears() {
    try {
      if(!self::$db) $this->connect();
      $res = self::$db->query('SELECT DISTINCT yearvalue FROM '.DB_TBL_STATTOTALS. ' ORDER BY yearvalue');
      $row = $res->fetchAll(PDO::FETCH_COLUMN);
      $out = array();
      if (is_array($row)) {
        foreach($row as $k=>$v) {
          $out[$v]=$v;
        }
      }
      return $out;
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

  /**
   * Return stat totals of user actions
   * 
   * @param integer $month requested month
   * @param integer $year requested year
   * @return PDOStatement object; resource of the executed query
   */  
  public function getTotalStats($month, $year) {
    $where = ' WHERE ';
    $where .= ($year == 0) ? ' s.yearvalue = '. date('Y') : ' s.yearvalue = '.(int)$year;
    $where .= ($month > 0) ? ' AND s.monthvalue = '. $month : '';      
    
    if ($this->stattype == ON_STATS_PRODUCT) {
      $qry ='SELECT a.productname,a.productid,s.statid,s.stattype,' .
        ' CASE WHEN s.useraction='.ON_USER_ACTION_VIEW.' THEN monthtotal END AS ui,'.
        ' CASE WHEN s.useraction='.ON_ANON_ACTION_VIEW.' THEN monthtotal END AS ai,'.
        ' CASE WHEN s.useraction='.ON_USER_ACTION_SHARE.' THEN monthtotal END AS ut,'.
        ' CASE WHEN s.useraction='.ON_ANON_ACTION_SHARE.' THEN monthtotal END AS at'.
        ' FROM '.DB_TBL_PRODUCTS . ' a '.
        ' INNER JOIN '.DB_TBL_STATTOTALS .' s ON a.productid=s.statid '.
        $where;
    } else {
      $qry ='SELECT s.statid,s.stattype,' .
        ' CASE WHEN s.useraction='.ON_USER_ACTION_VIEW.' THEN monthtotal END AS ui,'.
        ' CASE WHEN s.useraction='.ON_ANON_ACTION_VIEW.' THEN monthtotal END AS ai,'.
        ' CASE WHEN s.useraction='.ON_USER_ACTION_SHARE.' THEN monthtotal END AS ut,'.
        ' CASE WHEN s.useraction='.ON_ANON_ACTION_SHARE.' THEN monthtotal END AS at'.
        ' FROM ' . DB_TBL_CATS .' a' .
        ' INNER JOIN ' . DB_TBL_STATTOTALS .' s ON a.catid=s.statid '.
        $where;      
    }
    try {
      if(!self::$db) $this->connect();
      return self::$db->query($qry);
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }      
  }
}


?>