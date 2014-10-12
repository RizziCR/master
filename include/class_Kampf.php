<?php

class Kampf {
    public $p_angr;
    public $p_vert;
    public $d_vert;
    public $p_angr_lost;
    public $p_vert_lost;
    public $d_vert_lost;

    public $SumOffPlanes = 0;
    public $SumOffEspionageProbe = 0;
    public $SumOffScarecrow = 0;
    public $SumOffBomber = 0;
    public $SumDefPlanes = 0;
    public $SumDefDef = 0;
    public $capacity = 0;

    function Init($p_angr, $p_vert, $d_vert) {
        $this->p_angr = $p_angr;
        $this->p_vert = $p_vert;
        $this->d_vert = $d_vert;
    }

    function Fight($probability) {
        mt_srand ( ( double ) microtime () * 1000000 );

        for($i = 0; $i < ANZAHL_KAMPF_FLUGZEUGE; $i ++) {
            $this->p_angr_lost [$i] = 0;

            for($f = 0; $f < $this->p_angr [$i]; $f ++)
                if (mt_rand ( 1, 10000 ) / 100 > $probability)
                    $this->p_angr_lost [$i] ++;
        }

        for($i = 0; $i < ANZAHL_KAMPF_FLUGZEUGE; $i ++) {
            $this->p_vert_lost [$i] = 0;

            for($f = 0; $f < $this->p_vert [$i]; $f ++)
                if (mt_rand ( 1, 9999 ) / 100 < $probability)
                    $this->p_vert_lost [$i] ++;
        }

        for($i = 0; $i < ANZAHL_DEFENSIVE; $i ++) {
            $this->d_vert_lost [$i] = 0;

            for($f = 0; $f < $this->d_vert [$i]; $f ++)
                if (mt_rand ( 1, 9999 ) / 100 < $probability)
                    $this->d_vert_lost [$i] ++;
        }
    }

    function FightShield($probability) {
        mt_srand ( ( double ) microtime () * 1000000 );

        for($i = 0; $i < ANZAHL_KAMPF_FLUGZEUGE; $i ++) {
            $this->p_angr_lost [$i] = 0;

            for($f = 0; $f < $this->p_angr [$i]; $f ++)
                if (mt_rand ( 0, 10000 ) / 100 > $probability)
                    $this->p_angr_lost [$i] ++;
        }
    }

    function SumOffense() {
        for($i = 0; $i < ANZAHL_KAMPF_FLUGZEUGE; $i ++)
            $this->SumOffPlanes += $this->p_angr [$i] - $this->p_angr_lost [$i];

        $this->SumOffEspionageProbe = $this->p_angr [ESPIONAGE_PROBE] - $this->p_angr_lost [ESPIONAGE_PROBE];
        $this->SumOffScarecrow = $this->p_angr [SCARECROW] - $this->p_angr_lost [SCARECROW];
        $this->SumOffBomber = $this->p_angr [BOMBER] - $this->p_angr_lost [BOMBER];
    }

    function SumDefense() {
        for($i = 0; $i < ANZAHL_KAMPF_FLUGZEUGE; $i ++)
            if ($i != ESPIONAGE_PROBE)
                $this->SumDefPlanes += $this->p_vert [$i] - $this->p_vert_lost [$i];

        for($i = 0; $i < ANZAHL_DEFENSIVE; $i ++)
            $this->SumDefDef += $this->d_vert [$i] - $this->d_vert_lost [$i];
    }

    function CalcCapacity($user_plane_size) {
        global $p_capacity, $t_increase;

        for($i = 0; $i < ANZAHL_KAMPF_FLUGZEUGE; $i ++)
            $this->capacity += ($this->p_angr [$i] - $this->p_angr_lost [$i]) * $p_capacity [$i] * pow ( $t_increase [PLANE_SIZE], $user_plane_size );

        $this->capacity = floor ( $this->capacity );
    }

    function getSurvivorFleet() {
        $array = array();
        for($i = 0; $i < ANZAHL_KAMPF_FLUGZEUGE; $i ++)
            $array[$i] = $this->p_angr [$i] - $this->p_angr_lost [$i];
        return $array;
    }
}

?>
