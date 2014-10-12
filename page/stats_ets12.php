<?php

    include("database.php");

    // define phptal template
    require_once("PHPTAL.php");
    require_once("include/PHPTAL_EtsTranslator.php");

    $db = 'ETS12';
    $revision = '12';
    $revisionString = '12';
    $revisionName = 'Aufbruch Richtung Unendlichkeit';

    $template = new PHPTAL('guest/theme_blue_line_guest.html');
    $template->setTranslator(new PHPTAL_EtsTranslator());
    $template->setEncoding('ISO-8859-1');
    //$template->setForceReparse(true); // disable template cache
    $template->set('contentMacroName','stats_ets12.html/content');
    $template->set('pageTitle', 'Statistik von Runde 12 - Eine neue Zeitrechnung');

    // insert specific page logic here
    require_once("include/TemplateSettingsCommonGuest.php");

    $pfuschOutput .= "";
    
    $action = $_GET[action];
    switch ($action)
    {
      case '':
        $action = 'statistics';
      case "statistics" :
        $pfuschOutput .= "        <h3>Rundenzahlen - Ausbaustufen - Top 10</h3>
        <br/>
              <table border=0>
              		<tr><th align='right'>Bauaufträge Defensive gesamt</th><td>3757279</td></tr>
              		<tr><th align='right'>Bauaufträge Flugzeuge gesamt</th><td>3653232</td></tr>
              		<tr><th align='right'>Berichte gesamt</th><td>1603410</td></tr>
              		<tr><th align='right'>Ereignisse gesamt</th><td>11073880</td></tr>
              		<tr><th align='right'>Ingame Nachrichten gesamt</th><td>179367</td></tr>
              		<tr><th align='right'>Defensivanlagen gesamt</th><td>2080589</td></tr>
              		<tr><th align='right'>Flugzeuge gesamt</th><td>1621693</td></tr>
              		<tr><th align='right'>Spieler am Rundenende</th><td>326</td></tr>
              		<tr><th align='right'>Städte am Rundenende</th><td>2721</td></tr>
              	</table>
              	<br/><br/>
          <h3>Zerstörte Flugzeuge</h3>
          <br/>
          		<table border=0>
          			<tr><th>Name</th><th>Angriff verlust</th><th>Verteidigung verlust</th><th>Handel</th></tr>
          			<tr><th align='right'>Sparrow</th><td>18084</td><td>58721</td><td>3446</td></tr>
          			<tr><th align='right'>Blackbird</th><td>108262</td><td>79847</td><td>36293</td></tr>
          			<tr><th align='right'>Raven</th><td>107654</td><td>533967</td><td>139331</td></tr>
          			<tr><th align='right'>Eagle</th><td>31673</td><td>69076</td><td>5590</td></tr>
          			<tr><th align='right'>Falcon</th><td>128787</td><td>22133</td><td>23683</td></tr>
          			<tr><th align='right'>Nightingale</th><td>187979</td><td>382659</td><td>142002</td></tr>
          			<tr><th align='right'>Ravager</th><td>23059</td><td>10826</td><td>4680</td></tr>
          			<tr><th align='right'>Destroyer</th><td>13025</td><td>13997</td><td>5343</td></tr>
          			<tr><th align='right'>Spionagesonde</th><td>4708</td><td>49240</td><td>101</td></tr>
          			<tr><th align='right'>Settler</th><td>118</td><td>0</td><td>202</td></tr>
          			<tr><th align='right'>Scarecrow</th><td>390</td><td>497</td><td>264</td></tr>
           		</table>
           		<br/><br/>
           <h3>Plünderungen</h3>
           <br/>
           		<table border=0>
           			<tr><th align='right'>Iridium</th><td>8.062.730.140</td></tr>
           			<tr><th align='right'>Holzium</th><td>8.584.350.049</td></tr>
           			<tr><th align='right'>Wasser</th><td>82.912.835</td></tr>
           			<tr><th align='right'>Sauerstoff</th><td>3.509.924.111</td></tr>
           		</table>";
        break;
      case "cities" :
        $pfuschOutput .= "<h3>Städte - Größe - Top 50</h3>
			<br/>
						<table id='citiesSize'>
			<tr>
				<th class='rang'>         
				<th class='stadt'>Stadt</th>
          		<th class='name'>Name (Allianz)</th>
          		<th class='groesse'>Grösse</th>
        	</tr><tr>
						<td class='rang'>1</td>
						<td class='stadt'>Bhutmaster</td>
						<td class='name'>Beastmaster (-GR-)</td>
						<td class='groesse'>1518</td>
						</tr><tr>
						<td class='rang'>2</td>
						<td class='stadt'>THX Ceberus!</td>
						<td class='name'>Clown (EGAL)</td>
						<td class='groesse'>1338</td>
						</tr><tr>
						<td class='rang'>3</td>
						<td class='stadt'>ghostcity</td>
						<td class='name'>ghostdog (-GR-)</td>
						<td class='groesse'>1326</td>
						</tr><tr>
						<td class='rang'>4</td>
						<td class='stadt'>-HQ-</td>
						<td class='name'>Greiff (-TR-)</td>
						<td class='groesse'>1305</td>
						</tr><tr>
						<td class='rang'>5</td>
						<td class='stadt'>Eintracht Frankfurt</td>
						<td class='name'>lapos (-GR-)</td>
						<td class='groesse'>1296</td>
						</tr><tr>
						<td class='rang'>6</td>
						<td class='stadt'>HS</td>
						<td class='name'>Thor (-TR-)</td>
						<td class='groesse'>1280</td>
						</tr><tr>
						<td class='rang'>7</td>
						<td class='stadt'>LG</td>
						<td class='name'>franky008 (Troja)</td>
						<td class='groesse'>1279</td>
						</tr><tr>
						<td class='rang'>8</td>
						<td class='stadt'>Ehausen</td>
						<td class='name'>GinTonic ()</td>
						<td class='groesse'>1270</td>
						</tr><tr>
						<td class='rang'>9</td>
						<td class='stadt'>[BuG]Clown</td>
						<td class='name'>Clown (EGAL)</td>
						<td class='groesse'>1258</td>
						</tr><tr>
						<td class='rang'>10</td>
						<td class='stadt'>Die bewohnte Insel</td>
						<td class='name'>MaximKammerer (M3M0)</td>
						<td class='groesse'>1252</td>
						</tr><tr>
						<td class='rang'>11</td>
						<td class='stadt'>Charming</td>
						<td class='name'>webworker (DFB)</td>
						<td class='groesse'>1229</td>
						</tr><tr>
						<td class='rang'>12</td>
						<td class='stadt'>U</td>
						<td class='name'>hotty ()</td>
						<td class='groesse'>1198</td>
						</tr><tr>
						<td class='rang'>13</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1196</td>
						</tr><tr>
						<td class='rang'>14</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>bullshit (DFB)</td>
						<td class='groesse'>1193</td>
						</tr><tr>
						<td class='rang'>15</td>
						<td class='stadt'>vindobona</td>
						<td class='name'>advena (-SW-)</td>
						<td class='groesse'>1183</td>
						</tr><tr>
						<td class='rang'>16</td>
						<td class='stadt'>-</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1175</td>
						</tr><tr>
						<td class='rang'>17</td>
						<td class='stadt'>??</td>
						<td class='name'>Sepp (DFB)</td>
						<td class='groesse'>1175</td>
						</tr><tr>
						<td class='rang'>18</td>
						<td class='stadt'>-</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1171</td>
						</tr><tr>
						<td class='rang'>19</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1163</td>
						</tr><tr>
						<td class='rang'>20</td>
						<td class='stadt'>Hallo KarlHeinz</td>
						<td class='name'>KarlHeinz (DFB)</td>
						<td class='groesse'>1161</td>
						</tr><tr>
						<td class='rang'>21</td>
						<td class='stadt'>How YOU doin´?</td>
						<td class='name'>bullshit (DFB)</td>
						<td class='groesse'>1159</td>
						</tr><tr>
						<td class='rang'>22</td>
						<td class='stadt'>Feldberg</td>
						<td class='name'>Logorix ()</td>
						<td class='groesse'>1158</td>
						</tr><tr>
						<td class='rang'>23</td>
						<td class='stadt'>Terrania</td>
						<td class='name'>sbeck (Mehandor)</td>
						<td class='groesse'>1156</td>
						</tr><tr>
						<td class='rang'>24</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1155</td>
						</tr><tr>
						<td class='rang'>25</td>
						<td class='stadt'>II</td>
						<td class='name'>KarlHeinz (DFB)</td>
						<td class='groesse'>1154</td>
						</tr><tr>
						<td class='rang'>26</td>
						<td class='stadt'>-</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1154</td>
						</tr><tr>
						<td class='rang'>27</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>bashdi (DFB)</td>
						<td class='groesse'>1151</td>
						</tr><tr>
						<td class='rang'>28</td>
						<td class='stadt'>-</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1148</td>
						</tr><tr>
						<td class='rang'>29</td>
						<td class='stadt'>panther-s cave</td>
						<td class='name'>maccer (EGAL)</td>
						<td class='groesse'>1145</td>
						</tr><tr>
						<td class='rang'>30</td>
						<td class='stadt'>Busenberg</td>
						<td class='name'>HOW (-TI-)</td>
						<td class='groesse'>1135</td>
						</tr><tr>
						<td class='rang'>31</td>
						<td class='stadt'>[BuG]Clown</td>
						<td class='name'>bullshit (DFB)</td>
						<td class='groesse'>1126</td>
						</tr><tr>
						<td class='rang'>32</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>AxL (BM)</td>
						<td class='groesse'>1123</td>
						</tr><tr>
						<td class='rang'>33</td>
						<td class='stadt'></td>
						<td class='name'>d0m (M3M0)</td>
						<td class='groesse'>1123</td>
						</tr><tr>
						<td class='rang'>34</td>
						<td class='stadt'>-</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1119</td>
						</tr><tr>
						<td class='rang'>35</td>
						<td class='stadt'>very´s Wurstbude</td>
						<td class='name'>Tig (DFB)</td>
						<td class='groesse'>1119</td>
						</tr><tr>
						<td class='rang'>36</td>
						<td class='stadt'>Thx...</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1117</td>
						</tr><tr>
						<td class='rang'>37</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>daNova (M3M0)</td>
						<td class='groesse'>1115</td>
						</tr><tr>
						<td class='rang'>38</td>
						<td class='stadt'>Sorry Cebekuss</td>
						<td class='name'>KarlHeinz (DFB)</td>
						<td class='groesse'>1110</td>
						</tr><tr>
						<td class='rang'>39</td>
						<td class='stadt'>[BuG]Clown</td>
						<td class='name'>Clown (EGAL)</td>
						<td class='groesse'>1108</td>
						</tr><tr>
						<td class='rang'>40</td>
						<td class='stadt'>-</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1108</td>
						</tr><tr>
						<td class='rang'>41</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>daNova (M3M0)</td>
						<td class='groesse'>1104</td>
						</tr><tr>
						<td class='rang'>42</td>
						<td class='stadt'>Capital City</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>1101</td>
						</tr><tr>
						<td class='rang'>43</td>
						<td class='stadt'>Weltmeister 2014</td>
						<td class='name'>KarlHeinz (DFB)</td>
						<td class='groesse'>1100</td>
						</tr><tr>
						<td class='rang'>44</td>
						<td class='stadt'>[BuG]Clown</td>
						<td class='name'>Clown (EGAL)</td>
						<td class='groesse'>1093</td>
						</tr><tr>
						<td class='rang'>45</td>
						<td class='stadt'>Virus</td>
						<td class='name'>MagenDarmVirus (M3M0)</td>
						<td class='groesse'>1092</td>
						</tr><tr>
						<td class='rang'>46</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>Greiff (-TR-)</td>
						<td class='groesse'>1092</td>
						</tr><tr>
						<td class='rang'>47</td>
						<td class='stadt'>RU.HE</td>
						<td class='name'>DrGonZo (Medaron)</td>
						<td class='groesse'>1090</td>
						</tr><tr>
						<td class='rang'>48</td>
						<td class='stadt'>Signal Iduna Park</td>
						<td class='name'>KarlHeinz (DFB)</td>
						<td class='groesse'>1087</td>
						</tr><tr>
						<td class='rang'>49</td>
						<td class='stadt'>Shi-Gu</td>
						<td class='name'>reacher (EGAL)</td>
						<td class='groesse'>1086</td>
						</tr><tr>
						<td class='rang'>50</td>
						<td class='stadt'>- S.W.A.T. -</td>
						<td class='name'>Jay (DFB)</td>
						<td class='groesse'>1084</td>
						</tr></table>";
      	break;
      case "upgradings" :
        $pfuschOutput .= "<h3>Rundenzahlen - Ausbaustufen - Top 10</h3>
        <br/><table><tr>
		<th align='right'>Iridium-Mine</th><td>&nbsp;</td><td> <span title='bullshit'>214</span> -  <span title='Valdez'>183</span> -  <span title='bullshit'>180</span> -  <span title='Valdez'>178</span> -  <span title='Valdez'>177</span> -  <span title='Valdez'>177</span> -  <span title='sbeck'>176</span> -  <span title='Valdez'>176</span> -  <span title='Valdez'>174</span> -  <span title='bullshit'>174</span></td></tr><tr>
		<th align='right'>Holzium-Plantage</th><td>&nbsp;</td><td> <span title='bullshit'>214</span> -  <span title='bullshit'>214</span> -  <span title='bullshit'>214</span> -  <span title='bullshit'>214</span> -  <span title='bullshit'>210</span> -  <span title='Xryu'>208</span> -  <span title='Xryu'>208</span> -  <span title='bullshit'>203</span> -  <span title='Sepp'>198</span> -  <span title='Eagle'>195</span></td></tr><tr>
		<th align='right'>Wasser-Bohrturm</th><td>&nbsp;</td><td> <span title='G'>176</span> -  <span title='MaximKammerer'>173</span> -  <span title='GinTonic'>169</span> -  <span title='Clown'>166</span> -  <span title='webworker'>165</span> -  <span title='daNova'>156</span> -  <span title='lapos'>156</span> -  <span title='bullshit'>153</span> -  <span title='daNova'>152</span> -  <span title='daNova'>152</span></td></tr><tr>
		<th align='right'>Sauerstoff-Reaktor</th><td>&nbsp;</td><td> <span title='G'>179</span> -  <span title='GinTonic'>170</span> -  <span title='Clown'>165</span> -  <span title='daNova'>156</span> -  <span title='lapos'>156</span> -  <span title='daNova'>152</span> -  <span title='daNova'>152</span> -  <span title='Valdez'>150</span> -  <span title='daNova'>148</span> -  <span title='franky008'>148</span></td></tr><tr>
		<th align='right'>Depot</th><td>&nbsp;</td><td> <span title='Clown'>96</span> -  <span title='Clown'>87</span> -  <span title='Jay'>85</span> -  <span title='Jay'>85</span> -  <span title='advena'>81</span> -  <span title='Jay'>80</span> -  <span title='Beastmaster'>79</span> -  <span title='Jay'>75</span> -  <span title='Jay'>75</span> -  <span title='Tig'>75</span></td></tr><tr>
		<th align='right'>Tank</th><td>&nbsp;</td><td> <span title='Clown'>101</span> -  <span title='Clown'>97</span> -  <span title='Clown'>97</span> -  <span title='Beastmaster'>94</span> -  <span title='lapos'>84</span> -  <span title='advena'>84</span> -  <span title='MrFreeZe'>83</span> -  <span title='Clown'>79</span> -  <span title='Clown'>73</span> -  <span title='Clown'>73</span></td></tr><tr>
		<th align='right'>Hangar</th><td>&nbsp;</td><td> <span title='Breitseite'>183</span> -  <span title='Sogat'>167</span> -  <span title='Sogat'>166</span> -  <span title='Sogat'>162</span> -  <span title='t1802'>159</span> -  <span title='t1802'>159</span> -  <span title='t1802'>159</span> -  <span title='t1802'>159</span> -  <span title='t1802'>159</span> -  <span title='MagenDarmVirus'>158</span></td></tr><tr>
		<th align='right'>Flughafen</th><td>&nbsp;</td><td> <span title='bullshit'>214</span> -  <span title='Horst'>208</span> -  <span title='Klaus'>205</span> -  <span title='Xryu'>197</span> -  <span title='Aristarch'>196</span> -  <span title='lordalkohol'>196</span> -  <span title='Kalle'>194</span> -  <span title='Tig'>190</span> -  <span title='Breitseite'>190</span> -  <span title='camel'>187</span></td></tr><tr>
		<th align='right'>Bauzentrum</th><td>&nbsp;</td><td> <span title='ronsta'>109</span> -  <span title='Francis'>103</span> -  <span title='Thomas'>101</span> -  <span title='lordalkohol'>101</span> -  <span title='Horst'>99</span> -  <span title='Klaus'>98</span> -  <span title='bullshit'>97</span> -  <span title='HansHubert'>97</span> -  <span title='HansHubert'>97</span> -  <span title='Skunky'>97</span></td></tr><tr>
		<th align='right'>Technologiezentrum</th><td>&nbsp;</td><td> <span title='bullshit'>219</span> -  <span title='HansHubert'>201</span> -  <span title='Klaus'>188</span> -  <span title='Horst'>172</span> -  <span title='Toolkit'>168</span> -  <span title='Celia'>163</span> -  <span title='kiler'>162</span> -  <span title='Franz'>158</span> -  <span title='Oliver'>152</span> -  <span title='KarlHeinz'>150</span></td></tr><tr>
		<th align='right'>Handelszentrum</th><td>&nbsp;</td><td> <span title='Beastmaster'>93</span> -  <span title='Clown'>85</span> -  <span title='Clown'>77</span> -  <span title='Jay'>67</span> -  <span title='bullshit'>66</span> -  <span title='advena'>66</span> -  <span title='Jay'>65</span> -  <span title='Ilkay'>64</span> -  <span title='Ilkay'>64</span> -  <span title='Ilkay'>64</span></td></tr><tr>
		<th align='right'>Kommunikationszentrum</th><td>&nbsp;</td><td> <span title='KarlHeinz'>153</span> -  <span title='Pionierchor'>136</span> -  <span title='AxL'>136</span> -  <span title='d0m'>136</span> -  <span title='Valdez'>136</span> -  <span title='Jay'>136</span> -  <span title='Beastmaster'>121</span> -  <span title='lordalkohol'>120</span> -  <span title='kiler'>120</span> -  <span title='Balou'>120</span></td></tr><tr>
		<th align='right'>Verteidigungszentrum</th><td>&nbsp;</td><td> <span title='reacher'>152</span> -  <span title='Walle'>141</span> -  <span title='Walle'>141</span> -  <span title='Walle'>141</span> -  <span title='Walle'>141</span> -  <span title='Walle'>141</span> -  <span title='Walle'>141</span> -  <span title='Walle'>141</span> -  <span title='Walle'>141</span> -  <span title='VicVega'>140</span></td></tr><tr>
		<th align='right'>Oxidationsantrieb</th><td>&nbsp;</td><td><span title='Tig'>150</span> - <span title='bashdi'>143</span> - <span title='webworker'>140</span> - <span title='Phifor'>135</span> - <span title='Ilkay'>134</span> - <span title='Jay'>131</span> - <span title='Horst'>131</span> - <span title='Rainier'>125</span> - <span title='cjmischka'>121</span> - <span title='Sepp'>117</span></td></tr><tr>
		<th align='right'>Hoverantrieb</th><td>&nbsp;</td><td><span title='VicVega'>104</span> - <span title='MagenDarmVirus'>89</span> - <span title='Eagle'>87</span> - <span title='Ceberus'>78</span> - <span title='Kand'>77</span> - <span title='mengbillar'>76</span> - <span title='Balou'>70</span> - <span title='bonebreaker2705'>70</span> - <span title='Clown'>70</span> - <span title='Nicky'>68</span></td></tr><tr>
		<th align='right'>Antigravitationsantrieb</th><td>&nbsp;</td><td><span title='volvonist'>56</span> - <span title='Walle'>52</span> - <span title='MrFreeZe'>51</span> - <span title='CalvaDeCalvados'>42</span> - <span title='DerDude'>40</span> - <span title='Pitsch'>38</span> - <span title='Pfad'>34</span> - <span title='Pionierchor'>34</span> - <span title='Daxl'>29</span> - <span title='franky008'>28</span></td></tr><tr>
		<th align='right'>Elektronensequenzwaffen</th><td>&nbsp;</td><td><span title='bullshit'>454</span> - <span title='HansHubert'>430</span> - <span title='Klaus'>396</span> - <span title='Toolkit'>359</span> - <span title='Horst'>355</span> - <span title='Franz'>337</span> - <span title='Oliver'>333</span> - <span title='KarlHeinz'>319</span> - <span title='kiler'>319</span> - <span title='Rainier'>315</span></td></tr><tr>
		<th align='right'>Protonensequenzwaffen</th><td>&nbsp;</td><td><span title='d0m'>263</span> - <span title='runner'>258</span> - <span title='reacher'>251</span> - <span title='G'>234</span> - <span title='Breitseite'>232</span> - <span title='daNova'>225</span> - <span title='iceman333'>224</span> - <span title='handgemenge'>221</span> - <span title='dodo787'>216</span> - <span title='Xryu'>215</span></td></tr><tr>
		<th align='right'>Neutronensequenzwaffen</th><td>&nbsp;</td><td><span title='volvonist'>117</span> - <span title='Nightmaregirl'>110</span> - <span title='Walle'>101</span> - <span title='franky008'>100</span> - <span title='CalvaDeCalvados'>93</span> - <span title='Greiff'>91</span> - <span title='Daxl'>85</span> - <span title='Matty'>76</span> - <span title='pirx'>71</span> - <span title='Partyboy'>70</span></td></tr><tr>
		<th align='right'>Treibstoffverbrauch-Reduktion</th><td>&nbsp;</td><td><span title='advena'>102</span> - <span title='franky008'>90</span> - <span title='maccer'>73</span> - <span title='ReoLassan'>61</span> - <span title='Nandala'>58</span> - <span title='Pionierchor'>57</span> - <span title='CalvaDeCalvados'>54</span> - <span title='DerDude'>54</span> - <span title='Gothic'>51</span> - <span title='xxhulkxx'>51</span></td></tr><tr>
		<th align='right'>Flugzeugkapazitätsverwaltung</th><td>&nbsp;</td><td><span title='Beastmaster'>38</span> - <span title='Logorix'>38</span> - <span title='Aristarch'>37</span> - <span title='Kalle'>37</span> - <span title='Ursuul'>37</span> - <span title='hotty'>37</span> - <span title='MrKanister'>36</span> - <span title='Wassiljewna'>36</span> - <span title='Legion'>36</span> - <span title='sbeck'>35</span></td></tr><tr>
		<th align='right'>Computermanagement</th><td>&nbsp;</td><td><span title='lordalkohol'>257</span> - <span title='Beastmaster'>198</span> - <span title='Thomas'>190</span> - <span title='bashdi'>170</span> - <span title='camel'>156</span> - <span title='Valdez'>151</span> - <span title='Malt'>151</span> - <span title='Tig'>150</span> - <span title='Oliver'>142</span> - <span title='Twix'>140</span></td></tr><tr>
		<th align='right'>Lagerverwaltung</th><td>&nbsp;</td><td><span title='lapos'>76</span> - <span title='Dead'>68</span> - <span title='advena'>63</span> - <span title='Pionierchor'>62</span> - <span title='MagenDarmVirus'>60</span> - <span title='Suicide'>60</span> - <span title='Hermes'>57</span> - <span title='Legion'>57</span> - <span title='Logorix'>53</span> - <span title='Gothic'>52</span></td></tr><tr>
		<th align='right'>Wasserkompression</th><td>&nbsp;</td><td><span title='Beastmaster'>52</span> - <span title='AxL'>45</span> - <span title='Pikachu'>44</span> - <span title='daNova'>44</span> - <span title='t1802'>44</span> - <span title='MrKanister'>44</span> - <span title='iceman333'>43</span> - <span title='MagenDarmVirus'>42</span> - <span title='crash'>42</span> - <span title='G'>41</span></td></tr><tr>
		<th align='right'>Bergbautechnik</th><td>&nbsp;</td><td><span title='Beastmaster'>62</span> - <span title='volvonist'>58</span> - <span title='Clown'>57</span> - <span title='Walle'>55</span> - <span title='MagenDarmVirus'>53</span> - <span title='G'>53</span> - <span title='iceman333'>53</span> - <span title='daNova'>52</span> - <span title='Pikachu'>52</span> - <span title='crash'>52</span></td></tr>
		<tr><th align='right'>Hangarplätze</th><td>&nbsp;</td><td><span title='Breitseite'>1830</span> - <span title='Sogat'>1670</span> - <span title='Sogat'>1660</span> - <span title='Sogat'>1620</span> - <span title='t1802'>1590</span> - <span title='t1802'>1590</span> - <span title='t1802'>1590</span> - <span title='t1802'>1590</span> - <span title='t1802'>1590</span> - <span title='MagenDarmVirus'>1580</span></td></tr>
        <tr><th align='right'>Theoretisch größte Flotte</th><td>&nbsp;</td><td><span title='lordalkohol'>1751</span> - <span title='Thomas'>1490</span> - <span title='camel'>1403</span> - <span title='Tig'>1400</span> - <span title='Aristarch'>1376</span> - <span title='bashdi'>1365</span> - <span title='Kalle'>1357</span> - <span title='lordalkohol'>1346</span> - <span title='Xryu'>1324</span> - <span title='Breitseite'>1322</span></td></tr>
        <tr><th align='right'>Praktisch größte Flotte</th><td>&nbsp;</td><td><span title='Thomas'>1490</span> - <span title='Aristarch'>1376</span> - <span title='bashdi'>1365</span> - <span title='Kalle'>1357</span> - <span title='Xryu'>1324</span> - <span title='Breitseite'>1322</span> - <span title='Beastmaster'>1304</span> - <span title='bullshit'>1298</span> - <span title='dodo787'>1290</span> - <span title='volvonist'>1243</span></td></tr></table>";
        
        break;
      case "users_score" :
        $pfuschOutput .= "<h3>Spieler - Grösse - Top 50</h3>
				<br/>
						<table id='blockUserSize'>
				<tr>
						<td class='rang'>Rang</td>
						<td class='name'>Name (Allianz)</td>
						<td class='groesse'>Punkte</td>
				</tr><tr>
						<td class='rang'>1</td>
						<td class='name'>KarlHeinz (DFB)</td>
						<td class='groesse'>19677</td>
						</tr><tr>
						<td class='rang'>2</td>
						<td class='name'>Valdez (DFB)</td>
						<td class='groesse'>18840</td>
						</tr><tr>
						<td class='rang'>3</td>
						<td class='name'>Jay (DFB)</td>
						<td class='groesse'>17239</td>
						</tr><tr>
						<td class='rang'>4</td>
						<td class='name'>AxL (BM)</td>
						<td class='groesse'>17197</td>
						</tr><tr>
						<td class='rang'>5</td>
						<td class='name'>d0m (M3M0)</td>
						<td class='groesse'>17194</td>
						</tr><tr>
						<td class='rang'>6</td>
						<td class='name'>Tig (DFB)</td>
						<td class='groesse'>15566</td>
						</tr><tr>
						<td class='rang'>7</td>
						<td class='name'>Sepp (DFB)</td>
						<td class='groesse'>15423</td>
						</tr><tr>
						<td class='rang'>8</td>
						<td class='name'>Beastmaster (-GR-)</td>
						<td class='groesse'>15259</td>
						</tr><tr>
						<td class='rang'>9</td>
						<td class='name'>kiler (DFB)</td>
						<td class='groesse'>14770</td>
						</tr><tr>
						<td class='rang'>10</td>
						<td class='name'>lunachen (BM)</td>
						<td class='groesse'>14614</td>
						</tr><tr>
						<td class='rang'>11</td>
						<td class='name'>lordalkohol (DFB)</td>
						<td class='groesse'>14578</td>
						</tr><tr>
						<td class='rang'>12</td>
						<td class='name'>Clown (EGAL)</td>
						<td class='groesse'>14462</td>
						</tr><tr>
						<td class='rang'>13</td>
						<td class='name'>bullshit (DFB)</td>
						<td class='groesse'>14434</td>
						</tr><tr>
						<td class='rang'>14</td>
						<td class='name'>Oliver (DFB)</td>
						<td class='groesse'>14167</td>
						</tr><tr>
						<td class='rang'>15</td>
						<td class='name'>iceman333 (M3M0)</td>
						<td class='groesse'>14140</td>
						</tr><tr>
						<td class='rang'>16</td>
						<td class='name'>TheChosenOne (EGAL)</td>
						<td class='groesse'>14119</td>
						</tr><tr>
						<td class='rang'>17</td>
						<td class='name'>Gothic (BM)</td>
						<td class='groesse'>13712</td>
						</tr><tr>
						<td class='rang'>18</td>
						<td class='name'>Greiff (-TR-)</td>
						<td class='groesse'>13704</td>
						</tr><tr>
						<td class='rang'>19</td>
						<td class='name'>Celia (DFB)</td>
						<td class='groesse'>13702</td>
						</tr><tr>
						<td class='rang'>20</td>
						<td class='name'>Breitseite (M3M0)</td>
						<td class='groesse'>13550</td>
						</tr><tr>
						<td class='rang'>21</td>
						<td class='name'>daNova (M3M0)</td>
						<td class='groesse'>13354</td>
						</tr><tr>
						<td class='rang'>22</td>
						<td class='name'>volvonist (Medaron)</td>
						<td class='groesse'>13348</td>
						</tr><tr>
						<td class='rang'>23</td>
						<td class='name'>Pega (BM)</td>
						<td class='groesse'>13148</td>
						</tr><tr>
						<td class='rang'>24</td>
						<td class='name'>Klaus (DFB)</td>
						<td class='groesse'>12950</td>
						</tr><tr>
						<td class='rang'>25</td>
						<td class='name'>Pionierchor (AdA)</td>
						<td class='groesse'>12807</td>
						</tr><tr>
						<td class='rang'>26</td>
						<td class='name'>Ilkay (DFB)</td>
						<td class='groesse'>12783</td>
						</tr><tr>
						<td class='rang'>27</td>
						<td class='name'>Micha (BM)</td>
						<td class='groesse'>12620</td>
						</tr><tr>
						<td class='rang'>28</td>
						<td class='name'>Toolkit (DFB)</td>
						<td class='groesse'>12608</td>
						</tr><tr>
						<td class='rang'>29</td>
						<td class='name'>Rainier (DFB)</td>
						<td class='groesse'>12516</td>
						</tr><tr>
						<td class='rang'>30</td>
						<td class='name'>runner (EGAL)</td>
						<td class='groesse'>12411</td>
						</tr><tr>
						<td class='rang'>31</td>
						<td class='name'>Sogat (EGAL)</td>
						<td class='groesse'>12238</td>
						</tr><tr>
						<td class='rang'>32</td>
						<td class='name'>Balou (EGAL)</td>
						<td class='groesse'>12123</td>
						</tr><tr>
						<td class='rang'>33</td>
						<td class='name'>MagenDarmVirus (M3M0)</td>
						<td class='groesse'>11962</td>
						</tr><tr>
						<td class='rang'>34</td>
						<td class='name'>Shegoat (M3M0)</td>
						<td class='groesse'>11839</td>
						</tr><tr>
						<td class='rang'>35</td>
						<td class='name'>NewGee (DFB)</td>
						<td class='groesse'>11791</td>
						</tr><tr>
						<td class='rang'>36</td>
						<td class='name'>reacher (EGAL)</td>
						<td class='groesse'>11685</td>
						</tr><tr>
						<td class='rang'>37</td>
						<td class='name'>bashdi (DFB)</td>
						<td class='groesse'>11586</td>
						</tr><tr>
						<td class='rang'>38</td>
						<td class='name'>Walle (Medaron)</td>
						<td class='groesse'>11222</td>
						</tr><tr>
						<td class='rang'>39</td>
						<td class='name'>maccer (EGAL)</td>
						<td class='groesse'>11143</td>
						</tr><tr>
						<td class='rang'>40</td>
						<td class='name'>lSuicidel (BM)</td>
						<td class='groesse'>11069</td>
						</tr><tr>
						<td class='rang'>41</td>
						<td class='name'>Thor (-TR-)</td>
						<td class='groesse'>11051</td>
						</tr><tr>
						<td class='rang'>42</td>
						<td class='name'>dodo787 (M3M0)</td>
						<td class='groesse'>11010</td>
						</tr><tr>
						<td class='rang'>43</td>
						<td class='name'>Franz (DFB)</td>
						<td class='groesse'>10984</td>
						</tr><tr>
						<td class='rang'>44</td>
						<td class='name'>G (M3M0)</td>
						<td class='groesse'>10933</td>
						</tr><tr>
						<td class='rang'>45</td>
						<td class='name'>t1802 (M3M0)</td>
						<td class='groesse'>10933</td>
						</tr><tr>
						<td class='rang'>46</td>
						<td class='name'>Nightmaregirl (-TR-)</td>
						<td class='groesse'>10925</td>
						</tr><tr>
						<td class='rang'>47</td>
						<td class='name'>CalvaDeCalvados (Medaron)</td>
						<td class='groesse'>10915</td>
						</tr><tr>
						<td class='rang'>48</td>
						<td class='name'>Steiner (M3M0)</td>
						<td class='groesse'>10780</td>
						</tr><tr>
						<td class='rang'>49</td>
						<td class='name'>Beaker (M3M0)</td>
						<td class='groesse'>10744</td>
						</tr><tr>
						<td class='rang'>50</td>
						<td class='name'>Wassiljewna (Mehandor)</td>
						<td class='groesse'>10705</td>
						</tr></table>";
        break;
      case "users_power" :
        $pfuschOutput .= "<h3>Spieler - Grösse - Top 50</h3>
	<br/>
	<table id='blockUserSize'>
	<tr>
	<td class='rang'>Rang</td>
	<td class='name'>Name (Allianz)</td>
	<td class='groesse'>Stärke</td>
	</tr><tr>
				<td class='rang'>1</td>
				<td class='name'>Beastmaster (-GR-)</td>
				<td class='groesse'>2925</td>
				</tr><tr>
				<td class='rang'>2</td>
				<td class='name'>Valdez (DFB)</td>
				<td class='groesse'>2828</td>
				</tr><tr>
				<td class='rang'>3</td>
				<td class='name'>KarlHeinz (DFB)</td>
				<td class='groesse'>2810</td>
				</tr><tr>
				<td class='rang'>4</td>
				<td class='name'>Tig (DFB)</td>
				<td class='groesse'>2790</td>
				</tr><tr>
				<td class='rang'>5</td>
				<td class='name'>Greiff (-TR-)</td>
				<td class='groesse'>2755</td>
				</tr><tr>
				<td class='rang'>6</td>
				<td class='name'>lordalkohol (DFB)</td>
				<td class='groesse'>2753</td>
				</tr><tr>
				<td class='rang'>7</td>
				<td class='name'>Sepp (DFB)</td>
				<td class='groesse'>2586</td>
				</tr><tr>
				<td class='rang'>8</td>
				<td class='name'>Jay (DFB)</td>
				<td class='groesse'>2584</td>
				</tr><tr>
				<td class='rang'>9</td>
				<td class='name'>Oliver (DFB)</td>
				<td class='groesse'>2534</td>
				</tr><tr>
				<td class='rang'>10</td>
				<td class='name'>bashdi (DFB)</td>
				<td class='groesse'>2528</td>
				</tr><tr>
				<td class='rang'>11</td>
				<td class='name'>Celia (DFB)</td>
				<td class='groesse'>2525</td>
				</tr><tr>
				<td class='rang'>12</td>
				<td class='name'>volvonist (Medaron)</td>
				<td class='groesse'>2522</td>
				</tr><tr>
				<td class='rang'>13</td>
				<td class='name'>d0m (M3M0)</td>
				<td class='groesse'>2514</td>
				</tr><tr>
				<td class='rang'>14</td>
				<td class='name'>AxL (BM)</td>
				<td class='groesse'>2503</td>
				</tr><tr>
				<td class='rang'>15</td>
				<td class='name'>CalvaDeCalvados (Medaron)</td>
				<td class='groesse'>2483</td>
				</tr><tr>
				<td class='rang'>16</td>
				<td class='name'>Balou (EGAL)</td>
				<td class='groesse'>2452</td>
				</tr><tr>
				<td class='rang'>17</td>
				<td class='name'>Pega (BM)</td>
				<td class='groesse'>2357</td>
				</tr><tr>
				<td class='rang'>18</td>
				<td class='name'>lunachen (BM)</td>
				<td class='groesse'>2354</td>
				</tr><tr>
				<td class='rang'>19</td>
				<td class='name'>Clown (EGAL)</td>
				<td class='groesse'>2338</td>
				</tr><tr>
				<td class='rang'>20</td>
				<td class='name'>Ilkay (DFB)</td>
				<td class='groesse'>2337</td>
				</tr><tr>
				<td class='rang'>21</td>
				<td class='name'>kiler (DFB)</td>
				<td class='groesse'>2324</td>
				</tr><tr>
				<td class='rang'>22</td>
				<td class='name'>TheChosenOne (EGAL)</td>
				<td class='groesse'>2282</td>
				</tr><tr>
				<td class='rang'>23</td>
				<td class='name'>Toolkit (DFB)</td>
				<td class='groesse'>2249</td>
				</tr><tr>
				<td class='rang'>24</td>
				<td class='name'>Walle (Medaron)</td>
				<td class='groesse'>2225</td>
				</tr><tr>
				<td class='rang'>25</td>
				<td class='name'>tombombadil (BM)</td>
				<td class='groesse'>2222</td>
				</tr><tr>
				<td class='rang'>26</td>
				<td class='name'>Klaus (DFB)</td>
				<td class='groesse'>2203</td>
				</tr><tr>
				<td class='rang'>27</td>
				<td class='name'>Rainier (DFB)</td>
				<td class='groesse'>2194</td>
				</tr><tr>
				<td class='rang'>28</td>
				<td class='name'>Franz (DFB)</td>
				<td class='groesse'>2193</td>
				</tr><tr>
				<td class='rang'>29</td>
				<td class='name'>bullshit (DFB)</td>
				<td class='groesse'>2175</td>
				</tr><tr>
				<td class='rang'>30</td>
				<td class='name'>Sogat (EGAL)</td>
				<td class='groesse'>2159</td>
				</tr><tr>
				<td class='rang'>31</td>
				<td class='name'>t1802 (M3M0)</td>
				<td class='groesse'>2126</td>
				</tr><tr>
				<td class='rang'>32</td>
				<td class='name'>Johnnyb (M3M0)</td>
				<td class='groesse'>2111</td>
				</tr><tr>
				<td class='rang'>33</td>
				<td class='name'>maccer (EGAL)</td>
				<td class='groesse'>2093</td>
				</tr><tr>
				<td class='rang'>34</td>
				<td class='name'>Phifor (DFB)</td>
				<td class='groesse'>2084</td>
				</tr><tr>
				<td class='rang'>35</td>
				<td class='name'>2fast4u (BM)</td>
				<td class='groesse'>2081</td>
				</tr><tr>
				<td class='rang'>36</td>
				<td class='name'>Breitseite (M3M0)</td>
				<td class='groesse'>2078</td>
				</tr><tr>
				<td class='rang'>37</td>
				<td class='name'>Nightmaregirl (-TR-)</td>
				<td class='groesse'>2075</td>
				</tr><tr>
				<td class='rang'>38</td>
				<td class='name'>Hermes (Troja)</td>
				<td class='groesse'>2057</td>
				</tr><tr>
				<td class='rang'>39</td>
				<td class='name'>camel (BM)</td>
				<td class='groesse'>2056</td>
				</tr><tr>
				<td class='rang'>40</td>
				<td class='name'>Micha (BM)</td>
				<td class='groesse'>2046</td>
				</tr><tr>
				<td class='rang'>41</td>
				<td class='name'>runner (EGAL)</td>
				<td class='groesse'>2040</td>
				</tr><tr>
				<td class='rang'>42</td>
				<td class='name'>Eagle (M3M0)</td>
				<td class='groesse'>2032</td>
				</tr><tr>
				<td class='rang'>43</td>
				<td class='name'>Suicide (BuG)</td>
				<td class='groesse'>2028</td>
				</tr><tr>
				<td class='rang'>44</td>
				<td class='name'>daNova (M3M0)</td>
				<td class='groesse'>2025</td>
				</tr><tr>
				<td class='rang'>45</td>
				<td class='name'>Shegoat (M3M0)</td>
				<td class='groesse'>2025</td>
				</tr><tr>
				<td class='rang'>46</td>
				<td class='name'>Horst (DFB)</td>
				<td class='groesse'>2020</td>
				</tr><tr>
				<td class='rang'>47</td>
				<td class='name'>bonebreaker2705 (EGAL)</td>
				<td class='groesse'>2014</td>
				</tr><tr>
				<td class='rang'>48</td>
				<td class='name'>dodo787 (M3M0)</td>
				<td class='groesse'>2011</td>
				</tr><tr>
				<td class='rang'>49</td>
				<td class='name'>Beaker (M3M0)</td>
				<td class='groesse'>2010</td>
				</tr><tr>
				<td class='rang'>50</td>
				<td class='name'>Dungeonkeeper (BM)</td>
				<td class='groesse'>2006</td>
				</tr></table>";
        break;
      case "alliances_score" :
		$pfuschOutput .= "<h3>Allianzen - Grösse - Top 50</h3>
	<br/>
	<table id='blockUserSize'>
	<tr>
	<td>Rang</td>
	<td>Allianz</td>
	<td>Mitglieder</td>
	<td>Punkte</td>
	</tr><tr>
		<td class='rang'>1</td>
		<td class='name'>DFB</td>
		<td>25</td>
		<td class='groesse'>299377</td>
		</tr><tr>
		<td class='rang'>2</td>
		<td class='name'>M3M0</td>
		<td>28</td>
		<td class='groesse'>243084</td>
		</tr><tr>
		<td class='rang'>3</td>
		<td class='name'>BM</td>
		<td>22</td>
		<td class='groesse'>207757</td>
		</tr><tr>
		<td class='rang'>4</td>
		<td class='name'>EGAL</td>
		<td>16</td>
		<td class='groesse'>160441</td>
		</tr><tr>
		<td class='rang'>5</td>
		<td class='name'>-GR-</td>
		<td>15</td>
		<td class='groesse'>88031</td>
		</tr><tr>
		<td class='rang'>6</td>
		<td class='name'>Mehandor</td>
		<td>18</td>
		<td class='groesse'>83550</td>
		</tr><tr>
		<td class='rang'>7</td>
		<td class='name'>Medaron</td>
		<td>8</td>
		<td class='groesse'>53968</td>
		</tr><tr>
		<td class='rang'>8</td>
		<td class='name'>-SoD-</td>
		<td>15</td>
		<td class='groesse'>45521</td>
		</tr><tr>
		<td class='rang'>9</td>
		<td class='name'>-TR-</td>
		<td>4</td>
		<td class='groesse'>43311</td>
		</tr><tr>
		<td class='rang'>10</td>
		<td class='name'>Troja</td>
		<td>4</td>
		<td class='groesse'>25309</td>
		</tr><tr>
		<td class='rang'>11</td>
		<td class='name'>-TI-</td>
		<td>5</td>
		<td class='groesse'>24722</td>
		</tr><tr>
		<td class='rang'>12</td>
		<td class='name'>GdS</td>
		<td>5</td>
		<td class='groesse'>18505</td>
		</tr><tr>
		<td class='rang'>13</td>
		<td class='name'>-GO7-</td>
		<td>3</td>
		<td class='groesse'>16243</td>
		</tr><tr>
		<td class='rang'>14</td>
		<td class='name'>BuG</td>
		<td>2</td>
		<td class='groesse'>14054</td>
		</tr><tr>
		<td class='rang'>15</td>
		<td class='name'>hf</td>
		<td>4</td>
		<td class='groesse'>13580</td>
		</tr><tr>
		<td class='rang'>16</td>
		<td class='name'>AdA</td>
		<td>1</td>
		<td class='groesse'>12807</td>
		</tr><tr>
		<td class='rang'>17</td>
		<td class='name'>FS</td>
		<td>1</td>
		<td class='groesse'>8297</td>
		</tr><tr>
		<td class='rang'>18</td>
		<td class='name'>_BRB_</td>
		<td>1</td>
		<td class='groesse'>8070</td>
		</tr><tr>
		<td class='rang'>19</td>
		<td class='name'>wmtfussmdssuzaokumkh</td>
		<td>1</td>
		<td class='groesse'>7350</td>
		</tr><tr>
		<td class='rang'>20</td>
		<td class='name'>-SW-</td>
		<td>4</td>
		<td class='groesse'>7311</td>
		</tr><tr>
		<td class='rang'>21</td>
		<td class='name'>Erinys</td>
		<td>1</td>
		<td class='groesse'>6539</td>
		</tr><tr>
		<td class='rang'>22</td>
		<td class='name'>DAFG</td>
		<td>2</td>
		<td class='groesse'>5972</td>
		</tr><tr>
		<td class='rang'>23</td>
		<td class='name'>FoL</td>
		<td>1</td>
		<td class='groesse'>5699</td>
		</tr><tr>
		<td class='rang'>24</td>
		<td class='name'>OPPES</td>
		<td>2</td>
		<td class='groesse'>4496</td>
		</tr><tr>
		<td class='rang'>25</td>
		<td class='name'>S-H</td>
		<td>2</td>
		<td class='groesse'>4135</td>
		</tr><tr>
		<td class='rang'>26</td>
		<td class='name'>-TT-</td>
		<td>1</td>
		<td class='groesse'>3988</td>
		</tr><tr>
		<td class='rang'>27</td>
		<td class='name'>admin</td>
		<td>1</td>
		<td class='groesse'>3519</td>
		</tr><tr>
		<td class='rang'>28</td>
		<td class='name'>-F-</td>
		<td>1</td>
		<td class='groesse'>3491</td>
		</tr><tr>
		<td class='rang'>29</td>
		<td class='name'>Nachtwache</td>
		<td>1</td>
		<td class='groesse'>3437</td>
		</tr><tr>
		<td class='rang'>30</td>
		<td class='name'>-FSM-</td>
		<td>3</td>
		<td class='groesse'>3292</td>
		</tr><tr>
		<td class='rang'>31</td>
		<td class='name'>Vision</td>
		<td>3</td>
		<td class='groesse'>3202</td>
		</tr><tr>
		<td class='rang'>32</td>
		<td class='name'>-G-A-</td>
		<td>1</td>
		<td class='groesse'>3154</td>
		</tr><tr>
		<td class='rang'>33</td>
		<td class='name'>KGS</td>
		<td>2</td>
		<td class='groesse'>3134</td>
		</tr><tr>
		<td class='rang'>34</td>
		<td class='name'>OS</td>
		<td>1</td>
		<td class='groesse'>2668</td>
		</tr><tr>
		<td class='rang'>35</td>
		<td class='name'>-PwNeD-</td>
		<td>1</td>
		<td class='groesse'>2558</td>
		</tr><tr>
		<td class='rang'>36</td>
		<td class='name'>KoC</td>
		<td>3</td>
		<td class='groesse'>2414</td>
		</tr><tr>
		<td class='rang'>37</td>
		<td class='name'>Hag_Rocka_vs_RestETS</td>
		<td>1</td>
		<td class='groesse'>2385</td>
		</tr><tr>
		<td class='rang'>38</td>
		<td class='name'>-BrBa-</td>
		<td>1</td>
		<td class='groesse'>2031</td>
		</tr><tr>
		<td class='rang'>39</td>
		<td class='name'>-</td>
		<td>2</td>
		<td class='groesse'>2001</td>
		</tr><tr>
		<td class='rang'>40</td>
		<td class='name'>Bluna</td>
		<td>1</td>
		<td class='groesse'>1695</td>
		</tr><tr>
		<td class='rang'>41</td>
		<td class='name'>FdN</td>
		<td>1</td>
		<td class='groesse'>1694</td>
		</tr><tr>
		<td class='rang'>42</td>
		<td class='name'>iwan</td>
		<td>1</td>
		<td class='groesse'>963</td>
		</tr><tr>
		<td class='rang'>43</td>
		<td class='name'>MAD</td>
		<td>1</td>
		<td class='groesse'>946</td>
		</tr><tr>
		<td class='rang'>44</td>
		<td class='name'>Solo</td>
		<td>1</td>
		<td class='groesse'>782</td>
		</tr><tr>
		<td class='rang'>45</td>
		<td class='name'>GBS</td>
		<td>3</td>
		<td class='groesse'>620</td>
		</tr><tr>
		<td class='rang'>46</td>
		<td class='name'>Hadals</td>
		<td>2</td>
		<td class='groesse'>571</td>
		</tr><tr>
		<td class='rang'>47</td>
		<td class='name'>-42-</td>
		<td>1</td>
		<td class='groesse'>568</td>
		</tr><tr>
		<td class='rang'>48</td>
		<td class='name'>-DF-</td>
		<td>1</td>
		<td class='groesse'>496</td>
		</tr><tr>
		<td class='rang'>49</td>
		<td class='name'>Akademie</td>
		<td>1</td>
		<td class='groesse'>399</td>
		</tr><tr>
		<td class='rang'>50</td>
		<td class='name'>BZB</td>
		<td>1</td>
		<td class='groesse'>381</td>
		</tr></table>";
        break;
      case "alliances_power" :
        $pfuschOutput .= "<h3>Allianzen - Grösse - Top 50</h3>
	<br/>
	<table id='blockUserSize'>
	<tr>
	<td>Rang</td>
	<td>Allianz</td>
	<td>Mitglieder</td>
	<td>Stärke</td>
	</tr><tr>
		<td class='rang'>1</td>
		<td class='name'>DFB</td>
		<td>25</td>
		<td class='groesse'>54852</td>
		</tr><tr>
		<td class='rang'>2</td>
		<td class='name'>M3M0</td>
		<td>28</td>
		<td class='groesse'>44498</td>
		</tr><tr>
		<td class='rang'>3</td>
		<td class='name'>BM</td>
		<td>22</td>
		<td class='groesse'>39653</td>
		</tr><tr>
		<td class='rang'>4</td>
		<td class='name'>EGAL</td>
		<td>16</td>
		<td class='groesse'>30866</td>
		</tr><tr>
		<td class='rang'>5</td>
		<td class='name'>-GR-</td>
		<td>15</td>
		<td class='groesse'>19870</td>
		</tr><tr>
		<td class='rang'>6</td>
		<td class='name'>Mehandor</td>
		<td>18</td>
		<td class='groesse'>19405</td>
		</tr><tr>
		<td class='rang'>7</td>
		<td class='name'>Medaron</td>
		<td>8</td>
		<td class='groesse'>11233</td>
		</tr><tr>
		<td class='rang'>8</td>
		<td class='name'>-SoD-</td>
		<td>15</td>
		<td class='groesse'>11047</td>
		</tr><tr>
		<td class='rang'>9</td>
		<td class='name'>-TR-</td>
		<td>4</td>
		<td class='groesse'>8754</td>
		</tr><tr>
		<td class='rang'>10</td>
		<td class='name'>Troja</td>
		<td>4</td>
		<td class='groesse'>5582</td>
		</tr><tr>
		<td class='rang'>11</td>
		<td class='name'>-TI-</td>
		<td>5</td>
		<td class='groesse'>5406</td>
		</tr><tr>
		<td class='rang'>12</td>
		<td class='name'>GdS</td>
		<td>5</td>
		<td class='groesse'>4970</td>
		</tr><tr>
		<td class='rang'>13</td>
		<td class='name'>-GO7-</td>
		<td>3</td>
		<td class='groesse'>4002</td>
		</tr><tr>
		<td class='rang'>14</td>
		<td class='name'>BuG</td>
		<td>2</td>
		<td class='groesse'>3429</td>
		</tr><tr>
		<td class='rang'>15</td>
		<td class='name'>hf</td>
		<td>4</td>
		<td class='groesse'>2416</td>
		</tr><tr>
		<td class='rang'>16</td>
		<td class='name'>AdA</td>
		<td>1</td>
		<td class='groesse'>1617</td>
		</tr><tr>
		<td class='rang'>17</td>
		<td class='name'>FS</td>
		<td>1</td>
		<td class='groesse'>1276</td>
		</tr><tr>
		<td class='rang'>18</td>
		<td class='name'>_BRB_</td>
		<td>1</td>
		<td class='groesse'>1897</td>
		</tr><tr>
		<td class='rang'>19</td>
		<td class='name'>wmtfussmdssuzaokumkh</td>
		<td>1</td>
		<td class='groesse'>1513</td>
		</tr><tr>
		<td class='rang'>20</td>
		<td class='name'>-SW-</td>
		<td>4</td>
		<td class='groesse'>3035</td>
		</tr><tr>
		<td class='rang'>21</td>
		<td class='name'>Erinys</td>
		<td>1</td>
		<td class='groesse'>1726</td>
		</tr><tr>
		<td class='rang'>22</td>
		<td class='name'>DAFG</td>
		<td>2</td>
		<td class='groesse'>1899</td>
		</tr><tr>
		<td class='rang'>23</td>
		<td class='name'>FoL</td>
		<td>1</td>
		<td class='groesse'>1120</td>
		</tr><tr>
		<td class='rang'>24</td>
		<td class='name'>OPPES</td>
		<td>2</td>
		<td class='groesse'>1521</td>
		</tr><tr>
		<td class='rang'>25</td>
		<td class='name'>S-H</td>
		<td>2</td>
		<td class='groesse'>1361</td>
		</tr><tr>
		<td class='rang'>26</td>
		<td class='name'>-TT-</td>
		<td>1</td>
		<td class='groesse'>1032</td>
		</tr><tr>
		<td class='rang'>27</td>
		<td class='name'>admin</td>
		<td>1</td>
		<td class='groesse'>796</td>
		</tr><tr>
		<td class='rang'>28</td>
		<td class='name'>-F-</td>
		<td>1</td>
		<td class='groesse'>1083</td>
		</tr><tr>
		<td class='rang'>29</td>
		<td class='name'>Nachtwache</td>
		<td>1</td>
		<td class='groesse'>822</td>
		</tr><tr>
		<td class='rang'>30</td>
		<td class='name'>-FSM-</td>
		<td>3</td>
		<td class='groesse'>1161</td>
		</tr><tr>
		<td class='rang'>31</td>
		<td class='name'>Vision</td>
		<td>3</td>
		<td class='groesse'>1777</td>
		</tr><tr>
		<td class='rang'>32</td>
		<td class='name'>-G-A-</td>
		<td>1</td>
		<td class='groesse'>1191</td>
		</tr><tr>
		<td class='rang'>33</td>
		<td class='name'>KGS</td>
		<td>2</td>
		<td class='groesse'>1360</td>
		</tr><tr>
		<td class='rang'>34</td>
		<td class='name'>OS</td>
		<td>1</td>
		<td class='groesse'>920</td>
		</tr><tr>
		<td class='rang'>35</td>
		<td class='name'>-PwNeD-</td>
		<td>1</td>
		<td class='groesse'>454</td>
		</tr><tr>
		<td class='rang'>36</td>
		<td class='name'>KoC</td>
		<td>3</td>
		<td class='groesse'>1100</td>
		</tr><tr>
		<td class='rang'>37</td>
		<td class='name'>Hag_Rocka_vs_RestETS</td>
		<td>1</td>
		<td class='groesse'>569</td>
		</tr><tr>
		<td class='rang'>38</td>
		<td class='name'>-BrBa-</td>
		<td>1</td>
		<td class='groesse'>490</td>
		</tr><tr>
		<td class='rang'>39</td>
		<td class='name'>-</td>
		<td>2</td>
		<td class='groesse'>907</td>
		</tr><tr>
		<td class='rang'>40</td>
		<td class='name'>Bluna</td>
		<td>1</td>
		<td class='groesse'>736</td>
		</tr><tr>
		<td class='rang'>41</td>
		<td class='name'>FdN</td>
		<td>1</td>
		<td class='groesse'>808</td>
		</tr><tr>
		<td class='rang'>42</td>
		<td class='name'>iwan</td>
		<td>1</td>
		<td class='groesse'>430</td>
		</tr><tr>
		<td class='rang'>43</td>
		<td class='name'>MAD</td>
		<td>1</td>
		<td class='groesse'>333</td>
		</tr><tr>
		<td class='rang'>44</td>
		<td class='name'>Solo</td>
		<td>1</td>
		<td class='groesse'>87</td>
		</tr><tr>
		<td class='rang'>45</td>
		<td class='name'>GBS</td>
		<td>3</td>
		<td class='groesse'>268</td>
		</tr><tr>
		<td class='rang'>46</td>
		<td class='name'>Hadals</td>
		<td>2</td>
		<td class='groesse'>369</td>
		</tr><tr>
		<td class='rang'>47</td>
		<td class='name'>-42-</td>
		<td>1</td>
		<td class='groesse'>304</td>
		</tr><tr>
		<td class='rang'>48</td>
		<td class='name'>-DF-</td>
		<td>1</td>
		<td class='groesse'>273</td>
		</tr><tr>
		<td class='rang'>49</td>
		<td class='name'>Akademie</td>
		<td>1</td>
		<td class='groesse'>275</td>
		</tr><tr>
		<td class='rang'>50</td>
		<td class='name'>BZB</td>
		<td>1</td>
		<td class='groesse'>231</td>
		</tr></table>";
        break;
      
    }

    $template->set($action, 'true');
    $template->set('pfuschOutput',$pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
