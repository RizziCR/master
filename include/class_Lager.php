<?php
class Lager {
	
	private $_iridium = 0;
    private $_holzium = 0;
    private $_water = 0;
    private $_oxygen = 0;
    private $_capacity = 0;
    private $_capacity_oxygen = 0;
    private $_modified = false;
    private $_city = null;

    private $_delta_iridium = 0;
    private $_delta_holzium = 0;
    private $_delta_water = 0;
    private $_delta_oxygen = 0;

    private $_level = 0;
    private $_levelo = 0;
    private $_lv = 0;

    function __construct($city, $data = null) {
        $this->_city = stripslashes($city);
        if(is_array($data)) {
            $this->fromArray($data);
        }
        else {
        	$res = sql_query('SELECT r_iridium,r_holzium,r_water,r_oxygen,t_depot_management,b_depot,b_oxygen_depot FROM city WHERE ID="'.addslashes($this->_city).'"');
            list($this->_iridium, $this->_holzium, $this->_water, $this->_oxygen, $this->_lv, $this->_level, $this->_levelo) = sql_fetch_row($res);
            sql_free_result($res);
        }
        $this->recalcCapacity($this->_level, $this->_levelo, $this->_lv);
    }

    static function size($level, $lv) {
        return round(2 * (5000 * pow($level,2) + 200000) * pow(1.05, $lv));
    }

    static function sizeOxygen($level, $lv) {
        return round(2 * (4000 * pow($level,2) + 80000) * pow(1.05, $lv));
    }

    /**
     * Sekunden-Minuten-Ausgleich für Anzeige der Ressourcen.
     *
     * @param integer $seconds
     * @param float $iri_prod
     * @param float $holz_prod
     * @param float $water_prod
     * @param float $oxy_prod
     * @param float $oxy_prod_nowater
     * 	 */
    function fixTime($seconds, $iri_prod, $holz_prod, $water_prod, $oxy_prod, $oxy_prod_nowater) {
        $this->_iridium += $seconds * $iri_prod;
        $this->_holzium += $seconds * $holz_prod;
        $this->_water   += $seconds * $water_prod;
        if($this->_water > 0) {
            $this->_oxygen += $seconds * $oxy_prod;
        }
        else {
            $this->_oxygen += $seconds * $oxy_prod_nowater;
        }
        if($this->fillLevel() > $this->_capacity) {
            $scale = ($this->_capacity / $this->fillLevel());
            $this->_iridium = floor($this->_iridium * $scale);
            $this->_holzium = floor($this->_holzium * $scale);
            $this->_water   = floor($this->_water   * $scale);
        }

        if($this->fillLevelOxygen() > $this->_capacity_oxygen) {
            $this->_oxygen = floor($this->_capacity_oxygen);
        }
        $this->_iridium = max($this->_iridium, 0);
        $this->_holzium = max($this->_holzium, 0);
        $this->_water   = max($this->_water,   0);
        $this->_oxygen  = max($this->_oxygen,  0);
    }

    function addIridium($amount) {
        $old = $this->_iridium;
        $this->_iridium = max(0, min($this->_iridium + $amount, $this->_capacity - $this->_holzium - $this->_water));
        $this->_delta_iridium += $this->_iridium - $old;
        $this->_modified = true;
    }

    function addHolzium($amount) {
        $old = $this->_holzium;
        $this->_holzium = max(0, min($this->_holzium + $amount, $this->_capacity - $this->_iridium - $this->_water));
        $this->_delta_holzium += $this->_holzium - $old;
        $this->_modified = true;
    }

    function addWater($amount) {
        $old = $this->_water;
        $this->_water = max(0, min($this->_water + $amount, $this->_capacity - $this->_iridium - $this->_holzium));
        $this->_delta_water += $this->_water - $old;
        $this->_modified = true;
    }

    function addOxygen($amount) {
        $old = $this->_oxygen;
        $this->_oxygen = max(0, min($this->_oxygen + $amount, $this->_capacity_oxygen));
        $this->_delta_oxygen += $this->_oxygen - $old;
        $this->_modified = true;
    }

    function removeIridium($amount) {
        $old = $this->_iridium;
        $this->_iridium = max($this->_iridium - $amount, 0);
        $this->_delta_iridium += $this->_iridium - $old;
        $this->_modified = true;
    }

    function removeHolzium($amount) {
        $old = $this->_holzium;
        $this->_holzium = max($this->_holzium - $amount, 0);
        $this->_delta_holzium += $this->_holzium - $old;
        $this->_modified = true;
    }

    function removeWater($amount) {
        $old = $this->_water;
        $this->_water = max($this->_water - $amount, 0);
        $this->_delta_water += $this->_water - $old;
        $this->_modified = true;
    }

    function removeOxygen($amount) {
        $old = $this->_oxygen;
        $this->_oxygen = max($this->_oxygen - $amount, 0);
        $this->_delta_oxygen += $this->_oxygen - $old;
        $this->_modified = true;
    }

    function getIridium() {
        return $this->_iridium;
    }

    function getHolzium() {
        return $this->_holzium;
    }

    function getWater() {
        return $this->_water;
    }

    function getOxygen() {
        return $this->_oxygen;
    }

    function getCapacity() {
        return $this->_capacity;
    }

    function getCapacityOxygen() {
        return $this->_capacity_oxygen;
    }

    function fillLevel() {
        return ($this->_holzium + $this->_iridium + $this->_water);
    }

    function fillLevelOxygen() {
        return $this->_oxygen;
    }

    function fillLevelPercent() {
        return $this->fillLevel() / $this->_capacity * 100;
    }

    function fillLevelOxygenPercent() {
        return $this->fillLevelOxygen() / $this->_capacity_oxygen * 100;
    }

    function recalcCapacity($level = null, $levelo = null, $lv = null) {
        if(!isset($level))  $level = $this->_level;
        if(!isset($levelo)) $levelo = $this->_levelo;
        if(!isset($lv))     $lv = $this->_lv;

        $this->_capacity = Lager::size($level, $lv);
        $this->_capacity_oxygen = Lager::sizeOxygen($levelo, $lv);

        if($this->fillLevel() > $this->_capacity) {
            $old_i = $this->_iridium; $old_h = $this->_holzium; $old_w = $this->_water;
            $scale = ($this->_capacity / $this->fillLevel());
            $this->_iridium = max(0, floor($this->_iridium * $scale));
            $this->_holzium = max(0, floor($this->_holzium * $scale));
            $this->_water   = max(0, floor($this->_water   * $scale));
            $this->_delta_iridium += $this->_iridium - $old_i;
            $this->_delta_holzium += $this->_holzium - $old_h;
            $this->_delta_water   += $this->_water - $old_w;
            $this->_modified = true;
        }

        if($this->fillLevelOxygen() > $this->_capacity_oxygen) {
            $old_o = $this->_oxygen;
            $this->_oxygen = max(0, floor($this->_capacity_oxygen));
            $this->_delta_oxygen += $this->_oxygen - $old_o;
            $this->_modified = true;
        }
    }

    function save() {
        if($this->_modified == true) {
            sql_query('UPDATE city SET '.
                        'r_iridium=r_iridium+'.$this->_delta_iridium.', '.
                        'r_holzium=r_holzium+'.$this->_delta_holzium.', '.
                        'r_water=r_water+'.$this->_delta_water.', '.
                        'r_oxygen=r_oxygen+'.$this->_delta_oxygen.
                ' WHERE ID="'.addslashes($this->_city).'"');
            $this->_modified = false;
            $this->_delta_iridium = 0;
            $this->_delta_holzium = 0;
            $this->_delta_water = 0;
            $this->_delta_oxygen = 0;
        }
    }

    /**
     * Initializes the Lager object from a given array.
     *
     * @param array $data
     */
    function fromArray($data) {
        $this->_level   = intval($data['b_depot']);
        $this->_levelo  = intval($data['b_oxygen_depot']);
        $this->_lv		= intval($data['t_depot_management']);
        $this->_iridium = intval($data['r_iridium']);
        $this->_holzium = intval($data['r_holzium']);
        $this->_water   = intval($data['r_water']);
        $this->_oxygen  = intval($data['r_oxygen']);
    }
}
?>
