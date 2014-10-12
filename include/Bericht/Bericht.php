<?php

require_once('Bericht/Abstract.php');
require_once('Bericht/Attack.php');
require_once('Bericht/Exception.php');
require_once('Bericht/Simple.php');
require_once('Bericht/Spy.php');
require_once('Bericht/Transport.php');

require_once('Collection/Ressources.php');
require_once('Collection/Units.php');

require_once('Coordinates/Exception.php');

require_once('Coordinates.php');

/**
 * Ets_Bericht
 * 
 * Some static functions for report handling
 * 
 * This code was especially produced for "ETS 2021 e.V.", a
 * registered association that may use this code for any reason
 * it wants.
 * 
 * @author Dennis Riehle
 */
class Ets_Bericht {
  
  /**
   * Creates a new report object
   *
   * @param string $type
   * @param string $id
   * @param string $xmlid
   * @param Ets_Coordinates $origin
   * @param Ets_Coordinates $destination
   * @param string $subject
   * @param integer|string $time
   * @param mixed $params
   * @return Ets_Bericht_Abstract
   */
  static public function factory($type, $id, $xmlid, Ets_Coordinates $origin, Ets_Coordinates $destination, $subject, $time, $params = null) 
  {
    $class = 'Ets_Bericht_'.$type;
    $inst = new $class($id, $xmlid, $origin, $destination, $subject, $time, $params);
    $inst->setType($type);
    return $inst;
  }
  
  /**
   * Converts a report to a string for storage
   *
   * @param Ets_Bericht_Abstract $report
   * @return string
   */
  static public function reportToString(Ets_Bericht_Abstract $report)
  {
    return base64_encode(serialize($report));
  }
  
  /**
   * Restores a report object from a string
   *
   * @param string $storedData
   * @return Ets_Bericht_Abstract
   * @throws Ets_Bericht_Exception
   */
  static public function stringToReport($storedData) 
  {
    // The class of the report needs to be defined before callind unserialize()!
    // Autoloading would be a better solution, though.
    // recreate instance
    $inst = @unserialize(base64_decode($data));
    if(!$inst instanceof Ets_Bericht_Abstract) {
      throw new Ets_Bericht_Exception('Invalid stored data.');
    }
    return $inst;
  }
  
  /**
   * Converts a report to XML
   * 
   * @param Ets_Bericht_Abstract $report
   * @return string
   */
  static public function reportToXml(Ets_Bericht_Abstract $report)
  {
    return $report->toXml();
  }
  
  /**
   * Restores a report object from XML
   * 
   * @param string $xml
   * @return Ets_Bericht_Abstract
   */
  static public function xmlToReport($xml)
  {
    // TODO: implement this... might be hard work though ;-)
    throw new Exception('This feature is not implemented yet.');
  }
  
  /**
   * Parsed tab-seperated format into a report object
   *
   * @param string $data
   * @return Ets_Bericht_Abstract
   */
  static public function parseOldData($id,$xmlid,$origin,$destination,$subject,$time,$tabText,$attackerAlliance = null,$defenderAlliance = null)
  {
    // if the text starts with "Angreifer", it is either a
    // spy report or an attack report
    $fleetname = '';
    if (substr($tabText, 0, 9) == 'Angreifer') {
      // read plaintext
      $tabText = explode("\n", $tabText);
      $attacker = explode('|', $tabText[0]);
      $defender = array();
      $attackerFleet = array();
      $defenderFleet = array();
      $ressis = array(
        Ets_Collection_Ressources::IRIDIUM, 
        Ets_Collection_Ressources::HOLZIUM, 
        Ets_Collection_Ressources::WASSER, 
        Ets_Collection_Ressources::SAUERSTOFF
      );
      $defenderPoints = 0;
      
      for ($i = 1; $i < count($tabText) - 1; $i ++) {
      	if(!$tabText[$i])
      		continue;
        
        $line = explode('|', $tabText[$i]);
        switch($line[0]) {
        	case 'Verteidiger':
        		$defender = $line;
        		break;
        	case 'Punkte':
        		$defenderPoints = $line[1];
        		break;
        	case 'Rohstoff':
        		$ressis[$line[1]] = $line[2];
        		break;
        	case 'Flotte':
        		$fleetname = $line[1];
        		break;
        	case 'Simulator':
        	case 'break':
        	case 'Plunder':
        	case 'info':
        	case 'headline':
        		break;
        	default:
        		if (empty($defender)) {
		          $attackerFleet[] = array(
		            'type' => $line[0],
		            'sent' => $line[1],
		            'lost' => $line[2]
		          );
		        } else {
		          $defenderFleet[] = array(
		            'type' => $line[0],
		            'sent' => $line[1],
		            'lost' => $line[2]
		          );
		        }
		        break;
        }
      }
      // create objects
      $attacker = new Ets_Coordinates($attacker[1], $attacker[2] , $attackerAlliance);
      $defender = new Ets_Coordinates($defender[1], $defender[2] , $defenderAlliance);
      $attackerFleetCollection = new Ets_Collection_Units();
      call_user_func_array(
          array($attackerFleetCollection, 'addManyUnits'),
          $attackerFleet
      );
      $defenderFleetCollection = new Ets_Collection_Units();
      call_user_func_array(
          array($defenderFleetCollection, 'addManyUnits'), 
          $defenderFleet
      );
      $ressourcesCollection = new Ets_Collection_Ressources(
          $ressis[Ets_Collection_Ressources::IRIDIUM], 
          $ressis[Ets_Collection_Ressources::HOLZIUM], 
          $ressis[Ets_Collection_Ressources::WASSER], 
          $ressis[Ets_Collection_Ressources::SAUERSTOFF]
      );
      
      $spyUnits = $attackerFleetCollection->getSentUnits(Ets_Collection_Units::SPIONAGESONDE);
      // if there is only one unit in the attackers fleet and the unit
      // is a spionagesonde, then we consider this a spy report
      if (count($attackerFleetCollection) == 1 && $spyUnits > 0 ) {
        return self::factory(
          'Spy',
          $id,
          $xmlid,
          $attacker,
          $defender,
          $subject,
          $time,
          array('spy' => $ressourcesCollection,
                'defender' => $defenderFleetCollection,
                'attacker'=>$attackerFleetCollection,
                'defenderPoints'=>$defenderPoints,
                'fleetName' => $fleetname
          )
        );
      }
      // otherwise we consider this an attack report
      else {
        return self::factory(
          'Attack',
          $id,
          $xmlid,
          $attacker,
          $defender,
          $subject,
          $time,
          array(
            'attacker' => $attackerFleetCollection, 
            'defender' => $defenderFleetCollection, 
            'plunder' => $ressourcesCollection,
            'defenderPoints'=>$defenderPoints,
            'fleetName' => $fleetname
          )
        );
      }
      
      // elseif this is a transport report
    } elseif (strpos($tabText,'berbrachte an')!==false || strpos($tabText,'lieferte Ihnen auf') !== false) {
      $subject = str_replace('&uuml;','ü',$subject);
   	  // read plaintext
      $tabText = explode("\n", $tabText);
      $attacker = $defender = '';
      if(preg_match('/\((.*)\).*\((.*)\)/',$tabText[0],$res)) {
	      if(strpos($tabText[0],'berbrachte an')!==false) {
	      	$attacker = $res[1];
	      	$defender = $res[2];
	      } else {
	      	$attacker = $res[2];
	      	$defender = $res[1];
	      }
      }
      $fleets = array();
      $ressis = array();
      $unitSuccess = true;
      foreach($tabText as $line) {
      	$data = explode('|',$line);
      	switch($data[0]) {
      		case 'info':
      			if(
      				$data[1] == Ets_Collection_Ressources::IRIDIUM ||
      				$data[1] == Ets_Collection_Ressources::HOLZIUM ||
      				$data[1] == Ets_Collection_Ressources::WASSER ||
      				$data[1] == Ets_Collection_Ressources::SAUERSTOFF
      				) {
      					$ressis[$data[1]] = $data[2];
      				} else {
      					$fleets[] = array(
      						'type'=>$data[1],
      						'sent'=>$data[2],
      						'lost'=>0);
      				}
      			break;
      		case 'Plunder':
      			$unitSuccess = false;
      			break;
      	}
      }
      $transportFleetCollection = new Ets_Collection_Units();
      foreach($fleets as $fleet) {
      	call_user_func_array(
          array($transportFleetCollection, 'addManyUnits'), 
          $fleets
      	);
      }
      // create objects
      $attacker = new Ets_Coordinates($origin , $attacker , $attackerAlliance);
      $defender = new Ets_Coordinates($destination, $defender , $defenderAlliance);
      
      $ressourcesCollection = new Ets_Collection_Ressources(
          $ressis[Ets_Collection_Ressources::IRIDIUM], 
          $ressis[Ets_Collection_Ressources::HOLZIUM], 
          $ressis[Ets_Collection_Ressources::WASSER], 
          $ressis[Ets_Collection_Ressources::SAUERSTOFF]
      );
      
      $params = array(
      	'ressources'=>$ressourcesCollection,
        'units'=>$transportFleetCollection,
        'unit_success'=>$unitSuccess,
        'fleetName' => $fleetname
      );
      return self::factory('Transport',$id,$xmlid,$attacker,$defender,$subject,$time,$params);
    } else {     // otherwise give a simple plaintext report as the result
      return false;
      
      return self::factory(
        'Simple',
        $id,
        $xmlid,
        $attacker,
        $defender,
        $subject,
        $time,
        $tabText
      );
    }
  }
}