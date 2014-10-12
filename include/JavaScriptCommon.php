<?php

require_once("constants.php");

  // javascript for resource counter
if (time() >= PAUSE_END || time() < PAUSE_BEGIN) {
$resourceCounter = "<script type=\"text/javascript\">
      old_date = new Date();
      var count_ir = document.getElementById('count_iridium');
      var count_hz = document.getElementById('count_holzium');
      var count_wa = document.getElementById('count_water');
      var count_ox = document.getElementById('count_oxygen');

      var count_depot = document.getElementById('count_depot');
      var count_oxygen_depot = document.getElementById('count_oxygen_depot');

      var r_depot_size        = ".$timefixed_depot->getCapacity().";
      var r_oxygen_depot_size = ".$timefixed_depot->getCapacityOxygen().";

      function count_res_dep1()
      {
        new_date = new Date();
        timediff = Math.round((new_date.getTime() - old_date.getTime()) / 1000);

        menge['iridium'] = ".$timefixed_depot->getIridium()." + timediff * ".$ir_factor.";
        menge['holzium'] = ".$timefixed_depot->getHolzium()." + timediff * ".$hz_factor.";
        menge['water']   = ".$timefixed_depot->getWater()."   + timediff * ".$wa_factor.";

        c_ir = Math.ceil(menge['iridium']);
        c_hz = Math.ceil(menge['holzium']);
        c_wa = Math.ceil(menge['water']);

        gain = timediff * ".($ir_factor + $hz_factor + $wa_factor)." / r_depot_size * 100;
        c_depot = Math.round(100*(".$timefixed_depot->fillLevelPercent()." + gain))/100;
        count_depot.innerHTML = number_format(c_depot,0,'','.') + '%';

        if (c_wa < 0)
          c_wa = 0;

        count_ir.innerHTML = number_format(c_ir,0,'','.');
        count_hz.innerHTML = number_format(c_hz,0,'','.');
        count_wa.innerHTML = number_format(c_wa,0,'','.');

        if (c_ir + c_hz + c_wa <= r_depot_size)
          window.setTimeout('count_res_dep1()',1000);
      }
      function count_res_dep2()
      {
        new_date = new Date();
        timediff = Math.round((new_date.getTime() - old_date.getTime()) / 1000);

        menge['oxygen'] = ".$timefixed_depot->getOxygen()." + timediff * ".$ox_factor.";

        c_ox = Math.ceil(menge['oxygen']);

        gain = timediff * ".$ox_factor." / r_depot_size * 100;
        c_oxygen_depot = Math.round(100*(".$timefixed_depot->fillLevelOxygenPercent()." + gain))/100;
        count_oxygen_depot.innerHTML = number_format(c_oxygen_depot,0,'','.') + '%';

        count_ox.innerHTML = number_format(c_ox,0,'','.');

        if (c_ox <= r_oxygen_depot_size)
          window.setTimeout('count_res_dep2()',1000);
     }

      count_res_dep1();
      count_res_dep2();
  </script>";
}
else {
    $resourceCounter = "";
}

  // javascript for clock
  $clockJSCode = '<script type="text/javascript">
    var time = document.getElementById("time");
    var versatz;
    var last;
    var d;
    start_clock('.time().');
  </script>';


  // JavaScript for Session Time Out Warning
  $sessionTimeoutCode = '<script type="text/javascript">
    _initTimer("sessiontimeout",'.DISPLAY_SESSION_TIMEOUT_WARNING.',"Besuch beendet.",true,"Besuchszeit: ");
  </script>';


?>
