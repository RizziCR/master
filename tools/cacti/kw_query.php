#! /usr/bin/php
<?php
    require_once("database.php");
    
    $result = sql_query("
SELECT round(sum(
`d_electronwoofer`*150+
`d_protonwoofer`*250+
`d_neutronwoofer`*400+
`d_electronsequenzer`*500+`d_electronsequenzer`*12*t_electronsequenzweapons+
`d_protonsequenzer`*750+`d_protonsequenzer`*25*t_protonsequenzweapons+
`d_neutronsequenzer`*1000+`d_neutronsequenzer`*40*t_neutronsequenzweapons+
(2000 * `b_shield` + 20 * pow(`b_shield`, 2.5)) * (1 + pow(t_shield_tech, 2) / 100.0)
)) AS def_kw , 
round(sum(
`p_sparrow_gesamt`*100+`p_sparrow_gesamt`*12*`t_electronsequenzweapons`+
`p_blackbird_gesamt`*350+`p_blackbird_gesamt`*12*t_electronsequenzweapons+
`p_raven_gesamt`*500+`p_raven_gesamt`*12*t_electronsequenzweapons+
`p_eagle_gesamt`*550+`p_eagle_gesamt`*25*t_protonsequenzweapons+
`p_falcon_gesamt`*950+`p_falcon_gesamt`*25*t_protonsequenzweapons+
`p_nightingale_gesamt`*1500+`p_nightingale_gesamt`*25*t_protonsequenzweapons+
`p_ravager_gesamt`*2500+`p_ravager_gesamt`*40*t_neutronsequenzweapons+
`p_destroyer_gesamt`*4000+`p_destroyer_gesamt`*40*t_neutronsequenzweapons+
`p_scarecrow_gesamt`*1200+`p_scarecrow_gesamt`*40*t_neutronsequenzweapons+
`p_bomber_gesamt`*1300+`p_bomber_gesamt`*40*t_neutronsequenzweapons
)) AS att_kw
FROM `city`,`usarios` WHERE city.user=usarios.ID
    ");
    $res = sql_fetch_assoc($result);

    echo time().':'.$res[def_kw].':'.$res[att_kw];

?>