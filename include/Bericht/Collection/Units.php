<?php

/**
 * Ets_Collection_Units
 * 
 * This class represents information on a collection of units,
 * how many units there have been (amount of sent units) and how
 * many unit there still are (amount of lost units).
 * 
 * This code was especially produced for "ETS 2021 e.V.", a
 * registered association that may use this code for any reason
 * it wants.
 * 
 * @author Dennis Riehle
 */
class Ets_Collection_Units implements IteratorAggregate 
{
  const SPARROW              = 'Sparrow';
  const BLACKBIRD            = 'Blackbird';
  const RAVEN                = 'Raven';
  const EAGLE                = 'Eagle';
  const FALCON               = 'Falcon';
  const NIGHTINGALE          = 'Nightingale';
  const RAVAGER              = 'Ravager';
  const DESTROYER            = 'Destroyer';
  const SPIONAGESONDE        = 'Spionagesonde';
  const SETTLER              = 'Settler';
  const SCARECROW            = 'Scarecrow';
  const BOMBER               = 'Bomber';
  const KTRANS               = 'Kleines Transportflugzeug';
  const MTRANS               = 'Mittleres Transportflugzeug';
  const GTRANS               = 'Großes Transportflugzeug';
  
  const ELEKTRONENWOOFER     = 'Elektronenwoofer';
  const PROTONENWOOFER       = 'Protonenwoofer';
  const NEUTRONENWOOFER      = 'Neutronenwoofer';
  const ELEKTRONENSEQUENZER  = 'Elektronensequenzer';
  const PROTONENSEQUENZER    = 'Protonensequenzer';
  const NEUTRONENSEQUENZER   = 'Neutronensequenzer';
  const SCHUTZSCHILD         = 'Schutzschild';
  
  /**
   * ArrayObject of all Units
   *
   * @var ArrayObject
   */
  private $_units = null;
  
  /**
   * Creates new unit collection
   * 
   */
  public function __construct()
  {
    $this->_units = new ArrayObject();
  }
  
  /**
   * adds as many units as you want
   *
   * * You may specify as many parameters as you want in the following format:
   * $param = array(
   *   'type' => Type of Unit
   *   'sent' => Amount of sent Units
   *   'lost' => Amount of lost Units
   * );
   * 
   * @return void
   */
  public function addManyUnits()
  {
    $args = func_get_args();
    foreach($args as $key => $value) {
      if (!isset($value['type']) or !isset($value['sent']) or !isset($value['lost'])) continue;
      $this->_units[$value['type']] = array(
        'sent' => $value['sent'],
        'lost' => $value['lost']
      );
    }
  }
  
  /**
   * Creates entry for unit
   *
   * @param string $unit
   * @return void
   */
  private function _createUnitIfNotExist($unit)
  {
    if (!isset($this->_units[$unit])) {
      $this->_units[$unit] = array(
        'sent' => 0,
        'lost' => 0
      );
    }
  }
  
  /**
   * Sets information on units
   *
   * @param string $unit
   * @param integer $sent
   * @param integer $lost
   * @return Ets_Collection_Units
   */
  public function setUnits($unit, $sent, $lost)
  {
    $this->_createUnitIfNotExist($unit);
    $this->_units[$unit]['sent'] = $sent;
    $this->_units[$unit]['lost'] = $lost;
    return $this;
  }
  
  /**
   * Sets amount of lost units
   *
   * @param string $unit
   * @param integer $amount
   * @return Ets_Collection_Units
   */
  public function setLostUnits($unit, $amount)
  {
    $this->_createUnitIfNotExist($unit);
    $this->_units[$unit]['lost'] = $amount;
    return $this;
  }
  
  /**
   * Sets amount of sent units
   *
   * @param string $unit
   * @param integer $amount
   * @return Ets_Collection_Units
   */
  public function setSentUnits($unit, $amount)
  {
    $this->_createUnitIfNotExist($unit);
    $this->_units[$unit]['sent'] = $amount;
    return $this;
  }
  
  /**
   * Increaes number of lost units
   *
   * @param string $unit
   * @param integer $amount
   * @return Ets_Collection_Units
   */
  public function addLostUnits($unit, $amount)
  {
    $this->_createUnitIfNotExist($unit);
    $this->_units[$unit]['lost'] += $amount;
    return $this;
  }
  
  /**
   * Increaes number of sent units
   *
   * @param string $unit
   * @param integer $amount
   * @return Ets_Collection_Units
   */
  public function addSentUnits($unit, $amount)
  {
    $this->_createUnitIfNotExist($unit);
    $this->_units[$unit]['sent'] += $amount;
    return $this;
  }
  
  /**
   * Decreaases number of lost units
   *
   * @param string $unit
   * @param integer $amount
   * @return Ets_Collection_Units
   */
  public function removeLostUnits($unit, $amount)
  {
    $this->_createUnitIfNotExist($unit);
    $new = $this->_units[$unit]['lost'] - $amount;
    $this->_units[$unit]['lost'] = ($new < 0) ? 0 : $new;
    return $this;
  }
  
  /**
   * Decreases the number of sent units
   *
   * @param string $unit
   * @param integer $amount
   * @return Ets_Collection_Units
   */
  public function removeSentUnits($unit, $amount)
  {
    $this->_createUnitIfNotExist($unit);
    $new = $this->_units[$unit]['sent'] - $amount;
    $this->_units[$unit]['sent'] = ($new < 0) ? 0 : $new;
    return $this;
  }
  
  /**
   * Get information on unit
   * 
   * Returns an array in the following format:
   * $result = array(
   *   'sent' => Amount of sent Units
   *   'lost' => Amount of lost Units
   * );
   *
   * @param string $unit
   * @return array
   */
  public function getUnits($unit)
  {
    return isset($this->_units[$unit]) ? $this->_units[$unit] : null;
  }
  
  /**
   * will return the number of sent units of specified type
   *
   * @param string $unit
   * @return integer
   */
  public function getSentUnits($unit) {
  	$unitData = $this->getUnits($unit);
  	if(is_array($unitData)) {
  		return intval($unitData['sent']);
  	} else {
  		return 0;
  	}
  }
  
  /**
   * will return the number of lost units of specified type
   *
   * @param string $unit
   * @return integer
   */
  public function getLostUnits($unit) {
  	$unitData = $this->getUnits($unit);
  	if(is_array($unitData)) {
  		return intval($unitData['lost']);
  	} else {
  		return 0;
  	}
  }
  
  /**
   * Gets Iterator of Units Object
   *
   * @return ArrayInterator
   */
  public function getIterator()
  {
    return $this->_units->getIterator();
  }
}
