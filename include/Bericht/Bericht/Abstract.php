<?php

/**
 * Ets_Bericht_Abstract
 * 
 * This abstract class is supposed to build the basis for all
 * reports that occour in the game Escape To Space.
 * 
 * This code was especially produced for "ETS 2021 e.V.", a
 * registered association that may use this code for any reason
 * it wants.
 * 
 * @author Dennis Riehle
 */
abstract class Ets_Bericht_Abstract
{
  /**
   * Origin of the fleet
   *
   * @var Ets_Coordinates
   */
  protected $_origin = null;
  
  /**
   * Destination of the fleet
   *
   * @var Ets_Coordinates
   */
  protected $_destination = null;
  
  /**
   * The subject of the report 
   *
   * @var string
   */
  protected $_subject = null;
  
  /**
   * The name of the fleet 
   *
   * @var string
   */
  protected $_fleetName = null;
  
  /**
   * Time, when the reported action happened
   * UNIX Timestamp
   *
   * @var int
   */
  protected $_time = null;
  
  /**
   * The ID of the report for HTML access
   *
   * @var string
   */
  protected $_id = null;
  
  /**
   * The ID of the report for XML access
   *
   * @var string
   */
  protected $_xmlid = null;
  
  /**
   * the type of this instance
   * 
   * @var string
   */
  protected $_type = null;
  
  /**
   * Creates a new report
   * 
   * Needs the ID for XML access, the coordinates of origin and destination
   * of the fleet, the subject for the report and the time when something 
   * happened.
   * 
   * May take additional parameter, based on the concrete type of report.
   * See init() functions of concrete report implementations.
   *
   * @param string $id
   * @param string $xmlid
   * @param Ets_Coordinates $origin
   * @param Ets_Coordinates $destination
   * @param string $subject
   * @param int|string $time
   * @param mixed $params
   */
  final public function __construct($bid, $xmlid, Ets_Coordinates $origin, Ets_Coordinates $destination, $subject, $time, $params = null)
  {
    // store data
    $this->_id = $bid;
    $this->_xmlid = $xmlid;
    $this->_origin = $origin;
    $this->_destination = $destination;
    $this->_subject = (string) $subject;
    $this->_fleetName = (string) $params['fleetName'];
    
    // store unix timestamp
    $this->_time = $time; //(is_int($time) ? $time : strtotime($time));
    //if ($this->_time === false) $this->_time = time();
    
    // generate xml id
    // $this->_xmlid = $this->_generateXmlId();
    
    // call init function of extended class
    $this->_init($params);
  }
  
  /**
   * Generates a new unique Xml ID
   *
   * @return string
   */
  final protected function _generateXmlId()
  {
		return uniqid();
  }
  
  /**
   * Initialises a concrete class
   * 
   * A concrete implementation of a report class must 
   * override this method to do all setup that is needed
   * for further processing.
   * 
   * This method is called automatically by the
   * constructor of this class.
   *
   * @param mixed $params
   */
  abstract protected function _init($params);
  
  /**
   * Generates XML for report
   * 
   * This method is used to generate the inner XML for
   * a report. A concrete implementation of a report class
   * has to override this method to archieve whatever
   * representation of its data is wanted.
   *
   * @return string
   */
  abstract protected function _generateInnerXml();
  
  /**
   * Generates global XML for report
   * 
   * This method generates those parts of XML, which
   * should be identical in all reports.
   * 
   * It is possible to override this method in a concrete
   * report implementation, even though it is strongly 
   * recommenced not to do so!
   *
   * @return string
   */
  protected function _generateGlobalXml()
  {
    //date_default_timezone_set('Europe/Paris');
    $r = sprintf(
            '<origin name="%s" coordinates="%s" alliance="%s" capital="%s" />',
            htmlspecialchars($this->_origin->getName()),
            htmlspecialchars($this->_origin->coordinatesToString()),
            htmlspecialchars($this->_origin->getAlliance()),
            htmlspecialchars($this->_origin->isCapital() ? "true" : "false")
         )
       . sprintf(
            '<destination name="%s" coordinates="%s" alliance="%s" capital="%s"/>',
            htmlspecialchars($this->_destination->getName()),
            htmlspecialchars($this->_destination->coordinatesToString()),
            htmlspecialchars($this->_destination->getAlliance()),
            htmlspecialchars($this->_destination->isCapital() ? "true" : "false")
         )
       . sprintf(
            '<subject>%s</subject>',
            htmlspecialchars($this->_subject)
         )
       . sprintf(
            '<fleetname>%s</fleetname>',
            htmlspecialchars($this->_fleetName)
         )
         . sprintf(
            '<time unix="%d">%s</time>',
            $this->_time,
            date('Y-m-d H:i:s', $this->_time)
         );
    return $r;
  }
  
  /**
   * Generates a unit element for xml result
   *
   * @param string $unit
   * @param integer $sent
   * @param integer $lost
   * @return string
   */
  protected function _generateUnitXml($unit, $sent, $lost)
  {
    return sprintf(
        '<unit type="%s" sent="%d" lost="%d" />',
        htmlspecialchars($unit),
        $sent,
        $lost
    );
  }
  
  /**
   * Generates a ressource element for xml result
   *
   * @param string $ressource
   * @param integer $amount
   * @return string
   */
  protected function _generateRessourceXml($ressource, $amount) 
  {
    return sprintf(
        '<ressource type="%s" amount="%d" />',
        htmlspecialchars($ressource),
        $amount
    );
  }
  
  /**
   * Generates XML output
   * 
   * produces xml output for the current report, by using
   * the _generateInnerXml() implemenation of the concrete
   * report class.
   * 
   * @return string
   */
  final public function toXml($forum = false)
  {
  	$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
  	
  	if($forum){
  		$xml .= '<?xml-stylesheet type="text/xsl" href="templates/berichte_forum.xsl" ?>';
  	}
  	
  	$r = $xml . "\n"
       //. '<!DOCTYPE report SYSTEM "report.dtd">' . "\n"
       . sprintf(
            '<report bid="%s" xmlid="%s" type="%s">',
       	    htmlspecialchars($this->_id),
            htmlspecialchars($this->_xmlid),
            htmlspecialchars($this->getType())
         )
       . $this->_generateGlobalXml()
       . $this->_generateInnerXml()
       . '</report>';
    return $r;
  }
  
  /**
   * returns the type of this bericht
   * 
   * @return string
   */
  public function getType() {
  	if(!$this->_type) {
  		return get_class($this);
  	} else {
  		return $this->_type;
  	}
  }
  /**
   * sets the type of this bericht
   *
   * @param string $type
   */
  public function setType($type) {
  	$this->_type = $type;
  }
  /**
   * Returns XML ID
   *
   * @return string
   */
  final public function getXmlId()
  {
    return $this->_xmlid;
  }
}