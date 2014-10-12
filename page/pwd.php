<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Kennwort-Generator</title>
<style type="text/css">
body
{
	color : #FFFFFF;
	font-family : Tahoma;
	font-size : 10pt;
}
</style>
</head>
<body bgcolor=#000000>

<?php
    $dummy = array_merge(range('0', '9'), range('a', 'z'), range('A', 'Z'), array('#','&','@','$','_','%','?','+'));
  
    // shuffle array
  
    mt_srand((double)microtime()*1000000);

    for ($i = 1; $i <= (count($dummy)*2); $i++)
    {
        $swap = mt_rand(0,count($dummy)-1);
        $tmp = $dummy[$swap];
        $dummy[$swap] = $dummy[0];
        $dummy[0] = $tmp;
    }
  
    // get password
  
    echo "Kennwort: <br />" . substr(implode('',$dummy),0,10);
?>

</body>
</html>