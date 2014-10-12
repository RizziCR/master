<?php

/**
 * Ets_Coordinates
 * 
 * General class to represent coordinates and (optionally) a nickname
 * assinged to them. Offers additional functionality to work with
 * coordinates.
 * 
 * This code was especially produced for "ETS 2021 e.V.", a
 * registered association that may use this code for any reason
 * it wants.
 * 
 * @author Dennis Riehle
 */
class Ets_Coordinates
{
  /**
   * x component (continent)
   *
   * @var integer
   */
  private $_x = null;
  
  /**
   * y component (land)
   *
   * @var integer
   */
  private $_y = null;
  
  /**
   * z component (city)
   *
   * @var integer
   */
  private $_z = null;
  
  /**
   * User name (optional)
   *
   * @var string
   */
  private $_name = '';
  
  /**
   * the alliance of the user ( optional)
   *
   * @var string
   */
  private $_alliance = '';
  
  /**
   * holds the fact whether this city is a capital
   * 
   * @var boolean
   */
  private $_capital = false;

  /**
   * Creates new coordinates object
   * 
   *  __construct ( int $x, int $y, int $z [, string $name ] )
   *  __constrcut ( array $coords [, string $name ] [, string alliance] )
   * 
   * @throws Ets_Coordinates_Exception
   */
  public function __construct($coords,$name = null,$alliance = null)
  {
  	  $coords = split(':',$coords);
      $this->_x = (int) $coords[0];
      $this->_y = (int) $coords[1];
      $this->_z = (int) $coords[2];
      $this->setName(trim($name));
      $this->setAlliance(trim($alliance));

      $home_res = sql_query('SELECT home FROM city WHERE city="'.$this->coordinatesToString().'" LIMIT 1');
      list($home) = sql_fetch_row($home_res);
      sql_free_result($home_res);
      $this->_capital = ($home == 'YES');
  }

  /**
   * Gets string representation
   * 
   * Format: <x>:<y>:<z> | <Username> (<alliance>)
   *
   * @return string
   */
  public function __toString()
  {
    return $this->coordinatesToString().' | '.$this->getName() . (($this->_alliance != '') ? ' (' . $this->_alliance . ')' : '');
  }

  /**
   * Represents coordinates as string
   *
   * @return string
   */
  public function coordinatesToString()
  {
    return $this->_x . ':' . $this->_y . ':' . $this->_z;
  }

  /**
   * Represents coordinates as XML
   *
   * @return string
   */
  public function coordinatesToXml()
  {
    return '<x>' . $this->_x . '</x><y>' . $this->_y . '</y><z>' . $this->_z . '</z>';
  }

  /**
   * Returns continent component
   *
   * @return integer
   */
  public function getContinent()
  {
    return $this->_x;
  }

  /**
   * Returns land component
   *
   * @return integer
   */
  public function getLand()
  {
    return $this->_y;
  }

  /**
   * Returns city component
   *
   * @return integer
   */
  public function getCity()
  {
    return $this->_z;
  }

  /**
   * Checks if continent is the same as continent passed by argument
   *
   * @param integer $k
   * @return boolean
   */
  public function isContinent($k)
  {
    return ($this->_x == $k);
  }

  /**
   * Checks if land is the same as land passed by argument
   *
   * @param integer $l
   * @return boolean
   */
  public function isLand($l)
  {
    return ($this->_y == $l);
  }

  /**
   * Checks if city is the same as city passed by argument
   *
   * @param integer $c
   * @return boolean
   */
  public function isCity($c)
  {
    return ($this->_z == $c);
  }

  /**
   * Sets user's nickname
   *
   * @param string $name
   * @return Ets_Coordinates
   */
  public function setName($name)
  {
  	$nameparts = explode(' ',$name);
    $this->_name = (string) $nameparts[0];
    return $this;
  }

  /**
   * Returns user's nickname
   *
   * @return string
   */
  public function getName()
  {
    return $this->_name;
  }
  
  /**
   * Sets user's alliance
   *
   * @param string $name
   * @return Ets_Coordinates
   */
  public function setAlliance($alliance)
  {
    $this->_alliance = (string) $alliance;
    return $this;
  }

  /**
   * Returns user's alliance
   *
   * @return string
   */
  public function getAlliance()
  {
    return $this->_alliance;
  }

  /**
   * Returns true if this city is the capital of an user.
   */
  public function isCapital()
  {
  	return $this->_capital;
  }
}
