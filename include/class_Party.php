<?php

class Party {
    public $p_fleet;
    public $p_home;
    public $d_home;
    public $user_techs;
    public $shield;
    public $points;

    public $strength = 0;
    public $strength_shield = 0;

    function Init($result) {
        for($i = 0; $i < ANZAHL_KAMPF_FLUGZEUGE; $i ++) {
            $this->p_fleet [$i] = $result [$i];
            $this->p_home [$i] = $result [$i];
        }
    }

    function InitDefense($result) {
        for($i = 0; $i <= ANZAHL_DEFENSIVE; $i ++)
            $this->d_home [$i] = $result [$i];
    }

    function StrengthCalc($electronsequenzweapons, $protonsequenzweapons, $neutronsequenzweapons, $shield_tech, $points, $shield, $active_shields) {
        global $p_power, $d_power, $t_increase, $p_tech, $d_tech;

        $this->user_techs [E_WEAPONS] = $electronsequenzweapons;
        $this->user_techs [P_WEAPONS] = $protonsequenzweapons;
        $this->user_techs [N_WEAPONS] = $neutronsequenzweapons;
        $this->user_techs [EW_WEAPONS] = $electronsequenzweapons;
        $this->user_techs [PW_WEAPONS] = $protonsequenzweapons;
        $this->user_techs [NW_WEAPONS] = $neutronsequenzweapons;
        $this->shield = $shield;
        $this->points = $points;

        for($i = 0; $i < ANZAHL_KAMPF_FLUGZEUGE; $i ++) {
            $this->strength += $this->p_home [$i] * self::getPlaneKW($p_tech[$i][T_POWER], $p_power [$i], $t_increase [$p_tech [$i] [T_POWER]], $this->user_techs [$p_tech [$i] [T_POWER]]);
        }

        for($i = 0; $i < ANZAHL_DEFENSIVE; $i ++)
            $this->strength += $this->d_home [$i] * ($d_power [$i] + $t_increase [$d_tech [$i] [T_POWER]] * $this->user_techs [$d_tech [$i] [T_POWER]]);

        $this->strength += NewbieDef ( $points );
    }

    function StrengthCalcShield($shield_tech, $active_shields) {
        $this->strength_shield = Shield ( $this->shield, $shield_tech, $active_shields );
    }

    static function getPlaneKW($techType, $basePower, $powerIncrease, $techLevel) {
        if (($techType == E_WEAPONS) || ($techType == P_WEAPONS) || ($techType == N_WEAPONS))
            $techPower = $powerIncrease * $techLevel;
        else
            $techPower = 0;
        return $basePower + $techPower;
    }
}

?>
