<?php
  
  require_once("config_general.php");

  function now()
  {
    return microtime(TRUE);
  }

  class Bench
  {
    function NewMarke($name)
    {
      $this->marke[] = array($name,now() - $this->marke[0][1]);
    }

    function Start()
    {
      $this->marke[0] = array("Start der Messung",now());
    }

    function ShowResults()
    {
      for ($i=0;$i<count($this->marke);$i++)
        $output .= $this->marke[$i][1] . " <= " . $this->marke[$i][0] . "\n";
      mail($debugEmail,"CronResults",$output);
    }
  }
?>
