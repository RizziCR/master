<?php

    include("database.php");

    // define phptal template
    require_once("PHPTAL.php");
    require_once("include/PHPTAL_EtsTranslator.php");

    $db = 'ETS12';
    $revision = '13';
    $revisionString = '13';
    $revisionName = 'Tanz in den Mai';

    $template = new PHPTAL('guest/theme_blue_line_guest.html');
    $template->setTranslator(new PHPTAL_EtsTranslator());
    $template->setEncoding('ISO-8859-1');
    //$template->setForceReparse(true); // disable template cache
    $template->set('contentMacroName','stats_ets13.html/content');
    $template->set('pageTitle', 'Statistik von Runde 13 - Tanz in den Mai');

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
              		<tr><th align='right'>Berichte gesamt</th><td>1.007.620</td></tr>
              		<tr><th align='right'>Ereignisse gesamt</th><td>5.923.318</td></tr>
              		<tr><th align='right'>Ingame Nachrichten gesamt</th><td>110.223</td></tr>
              		<tr><th align='right'>Defensivanlagen gesamt</th><td>1.111.965</td></tr>
              		<tr><th align='right'>Flugzeuge gesamt</th><td>793.780</td></tr>
              		<tr><th align='right'>Spieler am Rundenende</th><td>333</td></tr>
              		<tr><th align='right'>Städte am Rundenende</th><td>2.364</td></tr>
              	</table>
              	<br/><br/>
          <h3>Zerstörte Flugzeuge</h3>
          <br/>
          		<table border=0>
          			<tr><th>Name</th><th>Angriff verlust</th><th>Verteidigung verlust</th><th>Handel</th></tr>
          			<tr><th align='right'>Sparrow</th><td>2865</td><td>17614</td><td>1288</td></tr>
          			<tr><th align='right'>Blackbird</th><td>17877</td><td>11744</td><td>1248</td></tr>
          			<tr><th align='right'>Raven</th><td>16872</td><td>46672</td><td>2764</td></tr>
          			<tr><th align='right'>Eagle</th><td>30795</td><td>179305</td><td>25627</td></tr>
          			<tr><th align='right'>Falcon</th><td>48726</td><td>8019</td><td>6745</td></tr>
          			<tr><th align='right'>Nightingale</th><td>43496</td><td>85886</td><td>13410</td></tr>
          			<tr><th align='right'>Ravager</th><td>53034</td><td>90611</td><td>42627</td></tr>
          			<tr><th align='right'>Destroyer</th><td>24021</td><td>31675</td><td>8170</td></tr>
          			<tr><th align='right'>Spionagesonde</th><td>9154</td><td>36278</td><td>137</td></tr>
          			<tr><th align='right'>Settler</th><td>34</td><td>5</td><td>102</td></tr>
          			<tr><th align='right'>Scarecrow</th><td>80</td><td>52</td><td>170</td></tr>
           		</table>
           		<br/><br/>
           <h3>Plünderungen</h3>
           <br/>
           		<table border=0>
           			<tr><th align='right'>Iridium</th><td>3.632.489.299</td></tr>
           			<tr><th align='right'>Holzium</th><td>3.507.380.022</td></tr>
           			<tr><th align='right'>Wasser</th><td>18.518.557</td></tr>
           			<tr><th align='right'>Sauerstoff</th><td>1.602.015.301</td></tr>
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
						<td class='stadt'>trotz KZ Kolo11 zu 8</td>
						<td class='name'>Beastmaster (-SoD-)</td>
						<td class='groesse'>1378</td>
						</tr><tr>
						<td class='rang'>2</td>
						<td class='stadt'></td>
						<td class='name'>ronsta (LaFamiglia)</td>
						<td class='groesse'>1265</td>
						</tr><tr>
						<td class='rang'>3</td>
						<td class='stadt'></td>
						<td class='name'>Skunky (LaFamiglia)</td>
						<td class='groesse'>1265</td>
						</tr><tr>
						<td class='rang'>4</td>
						<td class='stadt'>Gruss an HagbarD</td>
						<td class='name'>Rocka (BoP-VisioN)</td>
						<td class='groesse'>1237</td>
						</tr><tr>
						<td class='rang'>5</td>
						<td class='stadt'>Gruß an Vicvega</td>
						<td class='name'>Dungeonkeeper (BM)</td>
						<td class='groesse'>1207</td>
						</tr><tr>
						<td class='rang'>6</td>
						<td class='stadt'>mille grazie a tutti</td>
						<td class='name'>Valerian (LaFamiglia)</td>
						<td class='groesse'>1205</td>
						</tr><tr>
						<td class='rang'>7</td>
						<td class='stadt'>/\/\/\/\/\/\/\/\/\/\</td>
						<td class='name'>DrGonZo (RU.HE.)</td>
						<td class='groesse'>1142</td>
						</tr><tr>
						<td class='rang'>8</td>
						<td class='stadt'>/\/\/\/\/\/\/\/\/\/\</td>
						<td class='name'>Rausragend (RU.HE.)</td>
						<td class='groesse'>1142</td>
						</tr><tr>
						<td class='rang'>9</td>
						<td class='stadt'></td>
						<td class='name'>ghostdog (-GR-)</td>
						<td class='groesse'>1141</td>
						</tr><tr>
						<td class='rang'>10</td>
						<td class='stadt'>Bazinga</td>
						<td class='name'>ProzMP (-SoD-)</td>
						<td class='groesse'>1132</td>
						</tr><tr>
						<td class='rang'>11</td>
						<td class='stadt'>Vindobona</td>
						<td class='name'>aadvena (LaFamiglia)</td>
						<td class='groesse'>1023</td>
						</tr><tr>
						<td class='rang'>12</td>
						<td class='stadt'></td>
						<td class='name'>hotty (AAW)</td>
						<td class='groesse'>1008</td>
						</tr><tr>
						<td class='rang'>13</td>
						<td class='stadt'>panther-s-cave</td>
						<td class='name'>maccer (.)</td>
						<td class='groesse'>1003</td>
						</tr><tr>
						<td class='rang'>14</td>
						<td class='stadt'>ol-lo</td>
						<td class='name'>AxL (BM)</td>
						<td class='groesse'>999</td>
						</tr><tr>
						<td class='rang'>15</td>
						<td class='stadt'>****Co-R-uscant</td>
						<td class='name'>Shegoat (-SW-)</td>
						<td class='groesse'>975</td>
						</tr><tr>
						<td class='rang'>16</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>AxL (BM)</td>
						<td class='groesse'>975</td>
						</tr><tr>
						<td class='rang'>17</td>
						<td class='stadt'>-</td>
						<td class='name'>timreK (-)</td>
						<td class='groesse'>972</td>
						</tr><tr>
						<td class='rang'>18</td>
						<td class='stadt'>Die bewohnte Insel</td>
						<td class='name'>MaximKammerer (8472)</td>
						<td class='groesse'>969</td>
						</tr><tr>
						<td class='rang'>19</td>
						<td class='stadt'>Ascona</td>
						<td class='name'>zimmernagel (BM)</td>
						<td class='groesse'>967</td>
						</tr><tr>
						<td class='rang'>20</td>
						<td class='stadt'></td>
						<td class='name'>Gothic (BM)</td>
						<td class='groesse'>958</td>
						</tr><tr>
						<td class='rang'>21</td>
						<td class='stadt'>Hauptstadt</td>
						<td class='name'>Gonokokke (-SW-ist-bloed)</td>
						<td class='groesse'>943</td>
						</tr><tr>
						<td class='rang'>22</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>AxL (BM)</td>
						<td class='groesse'>937</td>
						</tr><tr>
						<td class='rang'>23</td>
						<td class='stadt'>Larissa</td>
						<td class='name'>DrBob (AdA)</td>
						<td class='groesse'>933</td>
						</tr><tr>
						<td class='rang'>24</td>
						<td class='stadt'>Corona</td>
						<td class='name'>Malt (SuN)</td>
						<td class='groesse'>933</td>
						</tr><tr>
						<td class='rang'>25</td>
						<td class='stadt'></td>
						<td class='name'>bonebreaker2705 (.)</td>
						<td class='groesse'>928</td>
						</tr><tr>
						<td class='rang'>26</td>
						<td class='stadt'>Sim City</td>
						<td class='name'>TanK (-SoD-)</td>
						<td class='groesse'>924</td>
						</tr><tr>
						<td class='rang'>27</td>
						<td class='stadt'>Neue Stadt</td>
						<td class='name'>R2-D2 (-SW-)</td>
						<td class='groesse'>916</td>
						</tr><tr>
						<td class='rang'>28</td>
						<td class='stadt'>/ / | | \ \</td>
						<td class='name'>MrFreeZe (CoIS)</td>
						<td class='groesse'>914</td>
						</tr><tr>
						<td class='rang'>29</td>
						<td class='stadt'>Für Rhaido!</td>
						<td class='name'>sagacy (-SW-)</td>
						<td class='groesse'>911</td>
						</tr><tr>
						<td class='rang'>30</td>
						<td class='stadt'>.F.</td>
						<td class='name'>Valerian (LaFamiglia)</td>
						<td class='groesse'>907</td>
						</tr><tr>
						<td class='rang'>31</td>
						<td class='stadt'>...A...</td>
						<td class='name'>Valerian (LaFamiglia)</td>
						<td class='groesse'>907</td>
						</tr><tr>
						<td class='rang'>32</td>
						<td class='stadt'>.........A.........</td>
						<td class='name'>Valerian (LaFamiglia)</td>
						<td class='groesse'>907</td>
						</tr><tr>
						<td class='rang'>33</td>
						<td class='stadt'>....M....</td>
						<td class='name'>Valerian (LaFamiglia)</td>
						<td class='groesse'>907</td>
						</tr><tr>
						<td class='rang'>34</td>
						<td class='stadt'>.....I.....</td>
						<td class='name'>Valerian (LaFamiglia)</td>
						<td class='groesse'>907</td>
						</tr><tr>
						<td class='rang'>35</td>
						<td class='stadt'>.........I.........</td>
						<td class='name'>Valerian (LaFamiglia)</td>
						<td class='groesse'>907</td>
						</tr><tr>
						<td class='rang'>36</td>
						<td class='stadt'>.......L.......</td>
						<td class='name'>Valerian (LaFamiglia)</td>
						<td class='groesse'>907</td>
						</tr><tr>
						<td class='rang'>37</td>
						<td class='stadt'>......G......</td>
						<td class='name'>Valerian (LaFamiglia)</td>
						<td class='groesse'>907</td>
						</tr><tr>
						<td class='rang'>38</td>
						<td class='stadt'>thx ghostdog!!!</td>
						<td class='name'>wOuLd (Aos)</td>
						<td class='groesse'>905</td>
						</tr><tr>
						<td class='rang'>39</td>
						<td class='stadt'>stehend </td>
						<td class='name'>Amazone (-SB-)</td>
						<td class='groesse'>901</td>
						</tr><tr>
						<td class='rang'>40</td>
						<td class='stadt'>Stewjon</td>
						<td class='name'>Obi-Wan-Kenobi (-SW-)</td>
						<td class='groesse'>901</td>
						</tr><tr>
						<td class='rang'>41</td>
						<td class='stadt'>-</td>
						<td class='name'>sagacy (-SW-)</td>
						<td class='groesse'>901</td>
						</tr><tr>
						<td class='rang'>42</td>
						<td class='stadt'>-</td>
						<td class='name'>sagacy (-SW-)</td>
						<td class='groesse'>901</td>
						</tr><tr>
						<td class='rang'>43</td>
						<td class='stadt'>-</td>
						<td class='name'>sagacy (-SW-)</td>
						<td class='groesse'>901</td>
						</tr><tr>
						<td class='rang'>44</td>
						<td class='stadt'>-</td>
						<td class='name'>sagacy (-SW-)</td>
						<td class='groesse'>901</td>
						</tr><tr>
						<td class='rang'>45</td>
						<td class='stadt'>-</td>
						<td class='name'>sagacy (-SW-)</td>
						<td class='groesse'>901</td>
						</tr><tr>
						<td class='rang'>46</td>
						<td class='stadt'>-</td>
						<td class='name'>sagacy (-SW-)</td>
						<td class='groesse'>901</td>
						</tr><tr>
						<td class='rang'>47</td>
						<td class='stadt'>-</td>
						<td class='name'>sagacy (-SW-)</td>
						<td class='groesse'>901</td>
						</tr><tr>
						<td class='rang'>48</td>
						<td class='stadt'>#1</td>
						<td class='name'>Shellie (FdO)</td>
						<td class='groesse'>900</td>
						</tr><tr>
						<td class='rang'>49</td>
						<td class='stadt'>#2</td>
						<td class='name'>Shellie (FdO)</td>
						<td class='groesse'>899</td>
						</tr><tr>
						<td class='rang'>50</td>
						<td class='stadt'></td>
						<td class='name'>Destroyer (Skittles)</td>
						<td class='groesse'>897</td>
						</tr></table>";
      	break;
      case "upgradings" :
        $pfuschOutput .= "<h3>Rundenzahlen - Ausbaustufen - Top 10</h3>
        <br/><table><tr>
		<th align='right'>Iridium-Mine</th><td>&nbsp;</td><td><span title='Shellie'>150</span> - <span title='Shellie'>150</span> - <span title='Shellie'>147</span> - <span title='Shellie'>147</span> - <span title='Shellie'>147</span> - <span title='Shellie'>145</span> - <span title='Rausragend'>143</span> - <span title='R2-D2'>143</span> - <span title='R2-D2'>143</span> - <span title='R2-D2'>143</span></td></tr><tr>
		<th align='right'>Holzium-Plantage</th><td>&nbsp;</td><td><span title='Mephistopheles'>169</span> - <span title='Shellie'>160</span> - <span title='Shellie'>160</span> - <span title='Weltmacht'>156</span> - <span title='Weltmacht'>156</span> - <span title='Weltmacht'>156</span> - <span title='Weltmacht'>156</span> - <span title='Weltmacht'>156</span> - <span title='Weltmacht'>156</span> - <span title='Ceberus'>156</span></td></tr><tr>
		<th align='right'>Wasser-Bohrturm</th><td>&nbsp;</td><td><span title='Rausragend'>144</span> - <span title='DrGonZo'>139</span> - <span title='AxL'>138</span> - <span title='Dungeonkeeper'>137</span> - <span title='sagacy'>136</span> - <span title='sagacy'>136</span> - <span title='sagacy'>136</span> - <span title='sagacy'>136</span> - <span title='sagacy'>136</span> - <span title='sagacy'>136</span></td></tr><tr>
		<th align='right'>Sauerstoff-Reaktor</th><td>&nbsp;</td><td><span title='sagacy'>136</span> - <span title='sagacy'>136</span> - <span title='sagacy'>136</span> - <span title='sagacy'>136</span> - <span title='sagacy'>136</span> - <span title='sagacy'>136</span> - <span title='sagacy'>136</span> - <span title='AxL'>135</span> - <span title='DrGonZo'>133</span> - <span title='Shellie'>128</span></td></tr><tr>
		<th align='right'>Depot</th><td>&nbsp;</td><td><span title='Beastmaster'>116</span> - <span title='Rocka'>95</span> - <span title='ronsta'>92</span> - <span title='Skunky'>92</span> - <span title='Valerian'>88</span> - <span title='Valerian'>77</span> - <span title='Dungeonkeeper'>76</span> - <span title='Valerian'>74</span> - <span title='MaximKammerer'>72</span> - <span title='aadvena'>71</span></td></tr><tr>
		<th align='right'>Tank</th><td>&nbsp;</td><td><span title='Beastmaster'>118</span> - <span title='Rausragend'>112</span> - <span title='ronsta'>111</span> - <span title='Skunky'>111</span> - <span title='Rocka'>94</span> - <span title='DrGonZo'>92</span> - <span title='MaximKammerer'>89</span> - <span title='Dungeonkeeper'>87</span> - <span title='Valerian'>84</span> - <span title='aadvena'>83</span></td></tr><tr>
		<th align='right'>Hangar</th><td>&nbsp;</td><td><span title='Timsl'>135</span> - <span title='ExaR'>132</span> - <span title='Teufel'>129</span> - <span title='JeanLucPicard'>121</span> - <span title='Beastmaster'>119</span> - <span title='franky008'>118</span> - <span title='MeisterYoda'>118</span> - <span title='Balou'>115</span> - <span title='birke'>115</span> - <span title='bonebreaker2705'>113</span></td></tr><tr>
		<th align='right'>Flughafen</th><td>&nbsp;</td><td><span title='reacher'>181</span> - <span title='Timsl'>179</span> - <span title='runner'>176</span> - <span title='camel'>157</span> - <span title='Balou'>156</span> - <span title='ExaR'>155</span> - <span title='Crisp'>154</span> - <span title='MeisterYoda'>154</span> - <span title='birke'>147</span> - <span title='bonebreaker2705'>142</span></td></tr><tr>
		<th align='right'>Bauzentrum</th><td>&nbsp;</td><td><span title='Crisp'>82</span> - <span title='-'>82</span> - <span title='Weini'>81</span> - <span title='Weini'>81</span> - <span title='Weini'>81</span> - <span title='Weini'>80</span> - <span title='Weini'>80</span> - <span title='Weini'>80</span> - <span title='Weltmacht'>78</span> - <span title='runner'>78</span></td></tr><tr>
		<th align='right'>Technologiezentrum</th><td>&nbsp;</td><td><span title='ghostdog'>103</span> - <span title='reacher'>99</span> - <span title='ronsta'>93</span> - <span title='Skunky'>93</span> - <span title='runner'>90</span> - <span title='Beastmaster'>87</span> - <span title='Rocka'>86</span> - <span title='ExaR'>85</span> - <span title='lordalkohoI'>80</span> - <span title='R2-D2'>78</span></td></tr><tr>
		<th align='right'>Handelszentrum</th><td>&nbsp;</td><td><span title='Beastmaster'>115</span> - <span title='Rocka'>86</span> - <span title='ronsta'>81</span> - <span title='Skunky'>81</span> - <span title='Dungeonkeeper'>76</span> - <span title='Valerian'>70</span> - <span title='aadvena'>63</span> - <span title='MaximKammerer'>62</span> - <span title='timreK'>61</span> - <span title='Valerian'>58</span></td></tr><tr>
		<th align='right'>Kommunikationszentrum</th><td>&nbsp;</td><td><span title='Balou'>91</span> - <span title='Gothic'>91</span> - <span title='sagacy'>91</span> - <span title='zimmernagel'>91</span> - <span title='Shegoat'>91</span> - <span title='CalvaDeCalvados'>83</span> - <span title='JeanLucPicard'>81</span> - <span title='crow'>80</span> - <span title='tombombadil'>78</span> - <span title='Pitsch'>78</span></td></tr><tr>
		<th align='right'>Verteidigungszentrum</th><td>&nbsp;</td><td><span title='Beastmaster'>117</span> - <span title='ronsta'>109</span> - <span title='Skunky'>109</span> - <span title='Rocka'>104</span> - <span title='MeisterSpeedy'>100</span> - <span title='MeisterSpeedy'>100</span> - <span title='Valerian'>99</span> - <span title='RefpO'>99</span> - <span title='Malt'>98</span> - <span title='blub'>97</span></td></tr><tr>
			<th align='right'>Oxidationsantrieb</th><td>&nbsp;</td><td><span title='Beastmaster'>81</span> - <span title='TanK'>80</span> - <span title='TiLan'>76</span> - <span title='Klopfer'>73</span> - <span title='Pfeifenstopfer'>70</span> - <span title='ghostdog'>66</span> - <span title='Mephistopheles'>65</span> - <span title='bullshit'>62</span> - <span title='XrandomY'>61</span> - <span title='lordalkohoI'>59</span></td></tr><tr>
			<th align='right'>Hoverantrieb</th><td>&nbsp;</td><td><span title='Ceberus'>69</span> - <span title='lordalkohoI'>65</span> - <span title='Mephistopheles'>64</span> - <span title='Pfeifenstopfer'>55</span> - <span title='XrandomY'>50</span> - <span title='Shellie'>50</span> - <span title='Phifor'>46</span> - <span title='bashdi'>46</span> - <span title='Rhaido'>44</span> - <span title='Greiff'>44</span></td></tr><tr>
			<th align='right'>Antigravitationsantrieb</th><td>&nbsp;</td><td><span title='Pitsch'>57</span> - <span title='L0ckdogg'>53</span> - <span title='R2-D2'>51</span> - <span title='Shegoat'>47</span> - <span title='DrBob'>46</span> - <span title='Sogat'>45</span> - <span title='Chewbacca'>43</span> - <span title='Micha'>41</span> - <span title='Timsl'>41</span> - <span title='Admiral'>40</span></td></tr><tr>
			<th align='right'>Elektronensequenzwaffen</th><td>&nbsp;</td><td><span title='ghostdog'>191</span> - <span title='Beastmaster'>164</span> - <span title='Reeper'>151</span> - <span title='TanK'>143</span> - <span title='r4d6'>138</span> - <span title='adler'>130</span> - <span title='N'>116</span> - <span title='blub'>115</span> - <span title='Doctor'>111</span> - <span title='bullshit'>96</span></td></tr><tr>
			<th align='right'>Protonensequenzwaffen</th><td>&nbsp;</td><td><span title='reacher'>198</span> - <span title='runner'>192</span> - <span title='Shellie'>154</span> - <span title='maccer'>141</span> - <span title='bonebreaker2705'>134</span> - <span title='Balou'>133</span> - <span title='lordalkohoI'>124</span> - <span title='Teufel'>120</span> - <span title='Mephistopheles'>115</span> - <span title='Ceberus'>115</span></td></tr><tr>
			<th align='right'>Neutronensequenzwaffen</th><td>&nbsp;</td><td><span title='DarthVader'>111</span> - <span title='MeisterYoda'>108</span> - <span title='Timsl'>103</span> - <span title='Weltmacht'>98</span> - <span title='Slade'>91</span> - <span title='camel'>87</span> - <span title='Sogat'>83</span> - <span title='R2-D2'>82</span> - <span title='2fast4u'>78</span> - <span title='birke'>76</span></td></tr><tr>
			<th align='right'>Treibstoffverbrauch-Reduktion</th><td>&nbsp;</td><td><span title='aadvena'>73</span> - <span title='franky008'>69</span> - <span title='Obi-Wan-Kenobi'>67</span> - <span title='ReoLassan'>63</span> - <span title='crow'>56</span> - <span title='Marlon'>52</span> - <span title='AxL'>52</span> - <span title='DrBob'>51</span> - <span title='Micha'>50</span> - <span title='Gothic'>50</span></td></tr><tr>
			<th align='right'>Flugzeugkapazitätsverwaltung</th><td>&nbsp;</td><td><span title='zimmernagel'>39</span> - <span title='Teufel'>38</span> - <span title='Doctor'>37</span> - <span title='Crixus'>37</span> - <span title='ProzMP'>36</span> - <span title='Frozen'>34</span> - <span title='MrFreeZe'>34</span> - <span title='N'>33</span> - <span title='Valerian'>32</span> - <span title='Pitsch'>32</span></td></tr><tr>
			<th align='right'>Computermanagement</th><td>&nbsp;</td><td><span title='ExaR'>162</span> - <span title='R2-D2'>147</span> - <span title='Pega'>146</span> - <span title='XrandomY'>141</span> - <span title='JeanLucPicard'>136</span> - <span title='Shegoat'>134</span> - <span title='Timsl'>129</span> - <span title='birke'>129</span> - <span title='Aristarch'>126</span> - <span title='Ki-Adi-Mundi'>116</span></td></tr><tr>
			<th align='right'>Lagerverwaltung</th><td>&nbsp;</td><td><span title='RefpO'>71</span> - <span title='aadvena'>64</span> - <span title='Teufel'>52</span> - <span title='ronsta'>50</span> - <span title='Skunky'>50</span> - <span title='Dungeonkeeper'>50</span> - <span title='zimmernagel'>50</span> - <span title='DrBob'>47</span> - <span title='Greiff'>46</span> - <span title='CalvaDeCalvados'>46</span></td></tr><tr>
			<th align='right'>Wasserkompression</th><td>&nbsp;</td><td><span title='sagacy'>41</span> - <span title='Destroyer'>40</span> - <span title='AxL'>39</span> - <span title='Daxl'>39</span> - <span title='zimmernagel'>38</span> - <span title='camel'>37</span> - <span title='Pitsch'>36</span> - <span title='Valerian'>36</span> - <span title='reacher'>36</span> - <span title='Chewbacca'>35</span></td></tr><tr>
			<th align='right'>Bergbautechnik</th><td>&nbsp;</td><td><span title='sagacy'>55</span> - <span title='Beastmaster'>51</span> - <span title='Valerian'>51</span> - <span title='Gothic'>50</span> - <span title='Chewbacca'>50</span> - <span title='zimmernagel'>49</span> - <span title='MeisterSpeedy'>49</span> - <span title='Melle'>48</span> - <span title='RefpO'>47</span> - <span title='ExaR'>47</span></td></tr><tr><th align='right'>Hangarplätze</th><td>&nbsp;</td><td><span title='Timsl'>1350</span> - <span title='ExaR'>1320</span> - <span title='Teufel'>1290</span> - <span title='JeanLucPicard'>1210</span> - <span title='Beastmaster'>1190</span> - <span title='franky008'>1180</span> - <span title='MeisterYoda'>1180</span> - <span title='Balou'>1150</span> - <span title='birke'>1150</span> - <span title='bonebreaker2705'>1130</span></td></tr><tr><th align='right'>Theoretisch größte Flotte</th><td>&nbsp;</td><td><span title='Timsl'>1282</span> - <span title='ExaR'>1261</span> - <span title='birke'>1122</span> - <span title='Balou'>1119</span> - <span title='reacher'>1115</span> - <span title='camel'>1109</span> - <span title='runner'>1108</span> - <span title='MeisterYoda'>1100</span> - <span title='R2-D2'>1096</span> - <span title='Aristarch'>1083</span></td></tr><tr><th align='right'>Praktisch größte Flotte</th><td>&nbsp;</td><td><span title='Timsl'>1282</span> - <span title='ExaR'>1261</span> - <span title='birke'>1122</span> - <span title='Balou'>1119</span> - <span title='reacher'>1115</span> - <span title='camel'>1109</span> - <span title='MeisterYoda'>1100</span> - <span title='Aristarch'>1083</span> - <span title='XrandomY'>1063</span> - <span title='Crisp'>1061</span></td></tr><tr><th align='right'>Aufeinanderfolgende Logins</th><td>&nbsp;</td><td><span title='MeisterYoda'>121</span> - <span title='Mephistopheles'>121</span> - <span title='maccer'>121</span> - <span title='Timsl'>121</span> - <span title='Greiff'>120</span> - <span title='ProzMP'>120</span> - <span title='birke'>120</span> - <span title='Neferet'>119</span> - <span title='Weltmacht'>119</span> - <span title='AxL'>119</span></td></tr></table>";
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
							<td class='name'>sagacy (-SW-)</td>
							<td class='groesse'>12345</td>
							</tr><tr>
							<td class='rang'>2</td>
							<td class='name'>R2-D2 (-SW-)</td>
							<td class='groesse'>11643</td>
							</tr><tr>
							<td class='rang'>3</td>
							<td class='name'>AxL (BM)</td>
							<td class='groesse'>11636</td>
							</tr><tr>
							<td class='rang'>4</td>
							<td class='name'>Gothic (BM)</td>
							<td class='groesse'>11592</td>
							</tr><tr>
							<td class='rang'>5</td>
							<td class='name'>Shegoat (-SW-)</td>
							<td class='groesse'>11303</td>
							</tr><tr>
							<td class='rang'>6</td>
							<td class='name'>maccer (.)</td>
							<td class='groesse'>10821</td>
							</tr><tr>
							<td class='rang'>7</td>
							<td class='name'>Balou (.)</td>
							<td class='groesse'>10739</td>
							</tr><tr>
							<td class='rang'>8</td>
							<td class='name'>Valerian (LaFamiglia)</td>
							<td class='groesse'>10707</td>
							</tr><tr>
							<td class='rang'>9</td>
							<td class='name'>Obi-Wan-Kenobi (-SW-)</td>
							<td class='groesse'>10521</td>
							</tr><tr>
							<td class='rang'>10</td>
							<td class='name'>zimmernagel (BM)</td>
							<td class='groesse'>10281</td>
							</tr><tr>
							<td class='rang'>11</td>
							<td class='name'>Shellie (FdO)</td>
							<td class='groesse'>10280</td>
							</tr><tr>
							<td class='rang'>12</td>
							<td class='name'>lordalkohoI (FdO)</td>
							<td class='groesse'>10260</td>
							</tr><tr>
							<td class='rang'>13</td>
							<td class='name'>MeisterYoda (-SW-)</td>
							<td class='groesse'>10163</td>
							</tr><tr>
							<td class='rang'>14</td>
							<td class='name'>lunachen (BM)</td>
							<td class='groesse'>9941</td>
							</tr><tr>
							<td class='rang'>15</td>
							<td class='name'>runner (.)</td>
							<td class='groesse'>9789</td>
							</tr><tr>
							<td class='rang'>16</td>
							<td class='name'>Destroyer (Skittles)</td>
							<td class='groesse'>9770</td>
							</tr><tr>
							<td class='rang'>17</td>
							<td class='name'>reacher (.)</td>
							<td class='groesse'>9693</td>
							</tr><tr>
							<td class='rang'>18</td>
							<td class='name'>ProzMP (-SoD-)</td>
							<td class='groesse'>9623</td>
							</tr><tr>
							<td class='rang'>19</td>
							<td class='name'>Mephistopheles (-SB-)</td>
							<td class='groesse'>9424</td>
							</tr><tr>
							<td class='rang'>20</td>
							<td class='name'>Pega (BM)</td>
							<td class='groesse'>9280</td>
							</tr><tr>
							<td class='rang'>21</td>
							<td class='name'>DarthVader (-SW-)</td>
							<td class='groesse'>9240</td>
							</tr><tr>
							<td class='rang'>22</td>
							<td class='name'>Weltmacht (-SW-)</td>
							<td class='groesse'>9098</td>
							</tr><tr>
							<td class='rang'>23</td>
							<td class='name'>JeanLucPicard (-SW-)</td>
							<td class='groesse'>8954</td>
							</tr><tr>
							<td class='rang'>24</td>
							<td class='name'>Greiff (FdO)</td>
							<td class='groesse'>8814</td>
							</tr><tr>
							<td class='rang'>25</td>
							<td class='name'>ExaR (-SW-)</td>
							<td class='groesse'>8769</td>
							</tr><tr>
							<td class='rang'>26</td>
							<td class='name'>MattiMQ (AdA)</td>
							<td class='groesse'>8665</td>
							</tr><tr>
							<td class='rang'>27</td>
							<td class='name'>crow (BM)</td>
							<td class='groesse'>8510</td>
							</tr><tr>
							<td class='rang'>28</td>
							<td class='name'>tombombadil (BM)</td>
							<td class='groesse'>8466</td>
							</tr><tr>
							<td class='rang'>29</td>
							<td class='name'>bonebreaker2705 (.)</td>
							<td class='groesse'>8311</td>
							</tr><tr>
							<td class='rang'>30</td>
							<td class='name'>Crisp (-SB-)</td>
							<td class='groesse'>8219</td>
							</tr><tr>
							<td class='rang'>31</td>
							<td class='name'>Slade (-SW-)</td>
							<td class='groesse'>8120</td>
							</tr><tr>
							<td class='rang'>32</td>
							<td class='name'>Weini (Skittles)</td>
							<td class='groesse'>8052</td>
							</tr><tr>
							<td class='rang'>33</td>
							<td class='name'>ShinJin (BM)</td>
							<td class='groesse'>8002</td>
							</tr><tr>
							<td class='rang'>34</td>
							<td class='name'>Pitsch (AdA)</td>
							<td class='groesse'>7994</td>
							</tr><tr>
							<td class='rang'>35</td>
							<td class='name'>Neferet (BM)</td>
							<td class='groesse'>7937</td>
							</tr><tr>
							<td class='rang'>36</td>
							<td class='name'>Timsl (-SW-)</td>
							<td class='groesse'>7915</td>
							</tr><tr>
							<td class='rang'>37</td>
							<td class='name'>Sogat (-SW-)</td>
							<td class='groesse'>7822</td>
							</tr><tr>
							<td class='rang'>38</td>
							<td class='name'>Micha (BM)</td>
							<td class='groesse'>7809</td>
							</tr><tr>
							<td class='rang'>39</td>
							<td class='name'>Beastmaster (-SoD-)</td>
							<td class='groesse'>7643</td>
							</tr><tr>
							<td class='rang'>40</td>
							<td class='name'>TheChosenOne (-SW-)</td>
							<td class='groesse'>7639</td>
							</tr><tr>
							<td class='rang'>41</td>
							<td class='name'>camel (-SW-)</td>
							<td class='groesse'>7527</td>
							</tr><tr>
							<td class='rang'>42</td>
							<td class='name'>Fleaky007 (Aos)</td>
							<td class='groesse'>7500</td>
							</tr><tr>
							<td class='rang'>43</td>
							<td class='name'>Ki-Adi-Mundi (-SW-)</td>
							<td class='groesse'>7408</td>
							</tr><tr>
							<td class='rang'>44</td>
							<td class='name'>XrandomY (.)</td>
							<td class='groesse'>7364</td>
							</tr><tr>
							<td class='rang'>45</td>
							<td class='name'>CalvaDeCalvados (LaFamiglia)</td>
							<td class='groesse'>7361</td>
							</tr><tr>
							<td class='rang'>46</td>
							<td class='name'>S (BM)</td>
							<td class='groesse'>7332</td>
							</tr><tr>
							<td class='rang'>47</td>
							<td class='name'>Aristarch (.)</td>
							<td class='groesse'>7275</td>
							</tr><tr>
							<td class='rang'>48</td>
							<td class='name'>Ceberus (Skittles)</td>
							<td class='groesse'>7218</td>
							</tr><tr>
							<td class='rang'>49</td>
							<td class='name'>Ursuul (BM)</td>
							<td class='groesse'>7167</td>
							</tr><tr>
							<td class='rang'>50</td>
							<td class='name'>Pfeifenstopfer (FdO)</td>
							<td class='groesse'>7074</td>
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
					<td class='name'>Shegoat (-SW-)</td>
					<td class='groesse'>2513</td>
					</tr><tr>
					<td class='rang'>2</td>
					<td class='name'>sagacy (-SW-)</td>
					<td class='groesse'>2512</td>
					</tr><tr>
					<td class='rang'>3</td>
					<td class='name'>Pega (BM)</td>
					<td class='groesse'>2337</td>
					</tr><tr>
					<td class='rang'>4</td>
					<td class='name'>AxL (BM)</td>
					<td class='groesse'>2308</td>
					</tr><tr>
					<td class='rang'>5</td>
					<td class='name'>Obi-Wan-Kenobi (-SW-)</td>
					<td class='groesse'>2271</td>
					</tr><tr>
					<td class='rang'>6</td>
					<td class='name'>MeisterYoda (-SW-)</td>
					<td class='groesse'>2236</td>
					</tr><tr>
					<td class='rang'>7</td>
					<td class='name'>ExaR (-SW-)</td>
					<td class='groesse'>2207</td>
					</tr><tr>
					<td class='rang'>8</td>
					<td class='name'>ShinJin (BM)</td>
					<td class='groesse'>2194</td>
					</tr><tr>
					<td class='rang'>9</td>
					<td class='name'>Timsl (-SW-)</td>
					<td class='groesse'>2178</td>
					</tr><tr>
					<td class='rang'>10</td>
					<td class='name'>R2-D2 (-SW-)</td>
					<td class='groesse'>2164</td>
					</tr><tr>
					<td class='rang'>11</td>
					<td class='name'>lunachen (BM)</td>
					<td class='groesse'>2161</td>
					</tr><tr>
					<td class='rang'>12</td>
					<td class='name'>Slade (-SW-)</td>
					<td class='groesse'>2138</td>
					</tr><tr>
					<td class='rang'>13</td>
					<td class='name'>JeanLucPicard (-SW-)</td>
					<td class='groesse'>2118</td>
					</tr><tr>
					<td class='rang'>14</td>
					<td class='name'>zimmernagel (BM)</td>
					<td class='groesse'>2067</td>
					</tr><tr>
					<td class='rang'>15</td>
					<td class='name'>tombombadil (BM)</td>
					<td class='groesse'>2063</td>
					</tr><tr>
					<td class='rang'>16</td>
					<td class='name'>Weltmacht (-SW-)</td>
					<td class='groesse'>2034</td>
					</tr><tr>
					<td class='rang'>17</td>
					<td class='name'>Neferet (BM)</td>
					<td class='groesse'>2032</td>
					</tr><tr>
					<td class='rang'>18</td>
					<td class='name'>ProzMP (-SoD-)</td>
					<td class='groesse'>2030</td>
					</tr><tr>
					<td class='rang'>19</td>
					<td class='name'>Micha (BM)</td>
					<td class='groesse'>2014</td>
					</tr><tr>
					<td class='rang'>20</td>
					<td class='name'>Ki-Adi-Mundi (-SW-)</td>
					<td class='groesse'>2003</td>
					</tr><tr>
					<td class='rang'>21</td>
					<td class='name'>Balou (.)</td>
					<td class='groesse'>2000</td>
					</tr><tr>
					<td class='rang'>22</td>
					<td class='name'>DarthVader (-SW-)</td>
					<td class='groesse'>1992</td>
					</tr><tr>
					<td class='rang'>23</td>
					<td class='name'>Fleaky007 (Aos)</td>
					<td class='groesse'>1992</td>
					</tr><tr>
					<td class='rang'>24</td>
					<td class='name'>lordalkohoI (FdO)</td>
					<td class='groesse'>1955</td>
					</tr><tr>
					<td class='rang'>25</td>
					<td class='name'>Chewbacca (-SW-)</td>
					<td class='groesse'>1931</td>
					</tr><tr>
					<td class='rang'>26</td>
					<td class='name'>birke (BM)</td>
					<td class='groesse'>1926</td>
					</tr><tr>
					<td class='rang'>27</td>
					<td class='name'>camel (-SW-)</td>
					<td class='groesse'>1904</td>
					</tr><tr>
					<td class='rang'>28</td>
					<td class='name'>Valerian (LaFamiglia)</td>
					<td class='groesse'>1890</td>
					</tr><tr>
					<td class='rang'>29</td>
					<td class='name'>maccer (.)</td>
					<td class='groesse'>1886</td>
					</tr><tr>
					<td class='rang'>30</td>
					<td class='name'>Gothic (BM)</td>
					<td class='groesse'>1882</td>
					</tr><tr>
					<td class='rang'>31</td>
					<td class='name'>crow (BM)</td>
					<td class='groesse'>1868</td>
					</tr><tr>
					<td class='rang'>32</td>
					<td class='name'>Pitsch (AdA)</td>
					<td class='groesse'>1863</td>
					</tr><tr>
					<td class='rang'>33</td>
					<td class='name'>TheChosenOne (-SW-)</td>
					<td class='groesse'>1858</td>
					</tr><tr>
					<td class='rang'>34</td>
					<td class='name'>Mephistopheles (-SB-)</td>
					<td class='groesse'>1792</td>
					</tr><tr>
					<td class='rang'>35</td>
					<td class='name'>XrandomY (.)</td>
					<td class='groesse'>1785</td>
					</tr><tr>
					<td class='rang'>36</td>
					<td class='name'>Shellie (FdO)</td>
					<td class='groesse'>1740</td>
					</tr><tr>
					<td class='rang'>37</td>
					<td class='name'>Greiff (FdO)</td>
					<td class='groesse'>1735</td>
					</tr><tr>
					<td class='rang'>38</td>
					<td class='name'>L0ckdogg (BM)</td>
					<td class='groesse'>1734</td>
					</tr><tr>
					<td class='rang'>39</td>
					<td class='name'>Ursuul (BM)</td>
					<td class='groesse'>1729</td>
					</tr><tr>
					<td class='rang'>40</td>
					<td class='name'>Admiral (-SW-)</td>
					<td class='groesse'>1729</td>
					</tr><tr>
					<td class='rang'>41</td>
					<td class='name'>S (BM)</td>
					<td class='groesse'>1726</td>
					</tr><tr>
					<td class='rang'>42</td>
					<td class='name'>2fast4u (BM)</td>
					<td class='groesse'>1703</td>
					</tr><tr>
					<td class='rang'>43</td>
					<td class='name'>Aristarch (.)</td>
					<td class='groesse'>1700</td>
					</tr><tr>
					<td class='rang'>44</td>
					<td class='name'>Sogat (-SW-)</td>
					<td class='groesse'>1680</td>
					</tr><tr>
					<td class='rang'>45</td>
					<td class='name'>Beastmaster (-SoD-)</td>
					<td class='groesse'>1675</td>
					</tr><tr>
					<td class='rang'>46</td>
					<td class='name'>CalvaDeCalvados (LaFamiglia)</td>
					<td class='groesse'>1656</td>
					</tr><tr>
					<td class='rang'>47</td>
					<td class='name'>hotty (AAW)</td>
					<td class='groesse'>1637</td>
					</tr><tr>
					<td class='rang'>48</td>
					<td class='name'>runner (.)</td>
					<td class='groesse'>1635</td>
					</tr><tr>
					<td class='rang'>49</td>
					<td class='name'>pirx (LaFamiglia)</td>
					<td class='groesse'>1634</td>
					</tr><tr>
					<td class='rang'>50</td>
					<td class='name'>DrBob (AdA)</td>
					<td class='groesse'>1620</td>
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
		<td class='name'>BM</td>
		<td>33</td>
		<td class='groesse'>178178</td>
		</tr><tr>
		<td class='rang'>2</td>
		<td class='name'>-SW-</td>
		<td>23</td>
		<td class='groesse'>156475</td>
		</tr><tr>
		<td class='rang'>3</td>
		<td class='name'>Skittles</td>
		<td>19</td>
		<td class='groesse'>86726</td>
		</tr><tr>
		<td class='rang'>4</td>
		<td class='name'>FdO</td>
		<td>13</td>
		<td class='groesse'>76436</td>
		</tr><tr>
		<td class='rang'>5</td>
		<td class='name'>AdA</td>
		<td>17</td>
		<td class='groesse'>62939</td>
		</tr><tr>
		<td class='rang'>6</td>
		<td class='name'>.</td>
		<td>8</td>
		<td class='groesse'>58852</td>
		</tr><tr>
		<td class='rang'>7</td>
		<td class='name'>-SoD-</td>
		<td>11</td>
		<td class='groesse'>46264</td>
		</tr><tr>
		<td class='rang'>8</td>
		<td class='name'>-SB-</td>
		<td>9</td>
		<td class='groesse'>42856</td>
		</tr><tr>
		<td class='rang'>9</td>
		<td class='name'>LaFamiglia</td>
		<td>10</td>
		<td class='groesse'>40823</td>
		</tr><tr>
		<td class='rang'>10</td>
		<td class='name'>Aos</td>
		<td>9</td>
		<td class='groesse'>37744</td>
		</tr><tr>
		<td class='rang'>11</td>
		<td class='name'>CoIS</td>
		<td>5</td>
		<td class='groesse'>22246</td>
		</tr><tr>
		<td class='rang'>12</td>
		<td class='name'>-GR-</td>
		<td>4</td>
		<td class='groesse'>20121</td>
		</tr><tr>
		<td class='rang'>13</td>
		<td class='name'>AAW</td>
		<td>5</td>
		<td class='groesse'>18306</td>
		</tr><tr>
		<td class='rang'>14</td>
		<td class='name'>-SW-ist-bloed</td>
		<td>5</td>
		<td class='groesse'>14178</td>
		</tr><tr>
		<td class='rang'>15</td>
		<td class='name'>Troja</td>
		<td>3</td>
		<td class='groesse'>12362</td>
		</tr><tr>
		<td class='rang'>16</td>
		<td class='name'>SylSoD</td>
		<td>7</td>
		<td class='groesse'>10747</td>
		</tr><tr>
		<td class='rang'>17</td>
		<td class='name'>8472</td>
		<td>8</td>
		<td class='groesse'>9185</td>
		</tr><tr>
		<td class='rang'>18</td>
		<td class='name'>SuN</td>
		<td>3</td>
		<td class='groesse'>8690</td>
		</tr><tr>
		<td class='rang'>19</td>
		<td class='name'>RU.HE.</td>
		<td>2</td>
		<td class='groesse'>6834</td>
		</tr><tr>
		<td class='rang'>20</td>
		<td class='name'>SoK</td>
		<td>1</td>
		<td class='groesse'>5512</td>
		</tr><tr>
		<td class='rang'>21</td>
		<td class='name'>olDschOOl</td>
		<td>7</td>
		<td class='groesse'>3836</td>
		</tr><tr>
		<td class='rang'>22</td>
		<td class='name'>BoP-VisioN</td>
		<td>2</td>
		<td class='groesse'>3755</td>
		</tr><tr>
		<td class='rang'>23</td>
		<td class='name'>LgC</td>
		<td>1</td>
		<td class='groesse'>3622</td>
		</tr><tr>
		<td class='rang'>24</td>
		<td class='name'>OPPES</td>
		<td>2</td>
		<td class='groesse'>3560</td>
		</tr><tr>
		<td class='rang'>25</td>
		<td class='name'>Mattys-kW</td>
		<td>1</td>
		<td class='groesse'>3459</td>
		</tr><tr>
		<td class='rang'>26</td>
		<td class='name'>Dings</td>
		<td>1</td>
		<td class='groesse'>2968</td>
		</tr><tr>
		<td class='rang'>27</td>
		<td class='name'>-pFC-</td>
		<td>1</td>
		<td class='groesse'>2331</td>
		</tr><tr>
		<td class='rang'>28</td>
		<td class='name'>FdN</td>
		<td>1</td>
		<td class='groesse'>2130</td>
		</tr><tr>
		<td class='rang'>29</td>
		<td class='name'>B.U.G</td>
		<td>1</td>
		<td class='groesse'>2080</td>
		</tr><tr>
		<td class='rang'>30</td>
		<td class='name'>-</td>
		<td>1</td>
		<td class='groesse'>1900</td>
		</tr><tr>
		<td class='rang'>31</td>
		<td class='name'>KGS</td>
		<td>2</td>
		<td class='groesse'>1853</td>
		</tr><tr>
		<td class='rang'>32</td>
		<td class='name'>-G-A-</td>
		<td>1</td>
		<td class='groesse'>1430</td>
		</tr><tr>
		<td class='rang'>33</td>
		<td class='name'>DARK</td>
		<td>2</td>
		<td class='groesse'>1407</td>
		</tr><tr>
		<td class='rang'>34</td>
		<td class='name'>KoC</td>
		<td>2</td>
		<td class='groesse'>1391</td>
		</tr><tr>
		<td class='rang'>35</td>
		<td class='name'>schlingels</td>
		<td>2</td>
		<td class='groesse'>1251</td>
		</tr><tr>
		<td class='rang'>36</td>
		<td class='name'>PORZ</td>
		<td>1</td>
		<td class='groesse'>1193</td>
		</tr><tr>
		<td class='rang'>37</td>
		<td class='name'>-FC-</td>
		<td>1</td>
		<td class='groesse'>995</td>
		</tr><tr>
		<td class='rang'>38</td>
		<td class='name'>Bretzfeld</td>
		<td>1</td>
		<td class='groesse'>572</td>
		</tr><tr>
		<td class='rang'>39</td>
		<td class='name'>wBuG</td>
		<td>1</td>
		<td class='groesse'>324</td>
		</tr><tr>
		<td class='rang'>40</td>
		<td class='name'>ETS</td>
		<td>1</td>
		<td class='groesse'>275</td>
		</tr><tr>
		<td class='rang'>41</td>
		<td class='name'>hSvabo</td>
		<td>1</td>
		<td class='groesse'>255</td>
		</tr><tr>
		<td class='rang'>42</td>
		<td class='name'>test</td>
		<td>1</td>
		<td class='groesse'>224</td>
		</tr><tr>
		<td class='rang'>43</td>
		<td class='name'>Frauenparkplatz</td>
		<td>1</td>
		<td class='groesse'>57</td>
		</tr><tr>
		<td class='rang'>44</td>
		<td class='name'>AIDS</td>
		<td>1</td>
		<td class='groesse'>12</td>
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
		<td class='name'>BM</td>
		<td>33</td>
		<td class='groesse'>18506</td>
		</tr><tr>
		<td class='rang'>2</td>
		<td class='name'>-SW-</td>
		<td>23</td>
		<td class='groesse'>22202</td>
		</tr><tr>
		<td class='rang'>3</td>
		<td class='name'>Skittles</td>
		<td>19</td>
		<td class='groesse'>12449</td>
		</tr><tr>
		<td class='rang'>4</td>
		<td class='name'>FdO</td>
		<td>13</td>
		<td class='groesse'>14169</td>
		</tr><tr>
		<td class='rang'>5</td>
		<td class='name'>AdA</td>
		<td>17</td>
		<td class='groesse'>7128</td>
		</tr><tr>
		<td class='rang'>6</td>
		<td class='name'>.</td>
		<td>8</td>
		<td class='groesse'>5688</td>
		</tr><tr>
		<td class='rang'>7</td>
		<td class='name'>-SoD-</td>
		<td>11</td>
		<td class='groesse'>5755</td>
		</tr><tr>
		<td class='rang'>8</td>
		<td class='name'>-SB-</td>
		<td>9</td>
		<td class='groesse'>0</td>
		</tr><tr>
		<td class='rang'>9</td>
		<td class='name'>LaFamiglia</td>
		<td>10</td>
		<td class='groesse'>2849</td>
		</tr><tr>
		<td class='rang'>10</td>
		<td class='name'>Aos</td>
		<td>9</td>
		<td class='groesse'>4029</td>
		</tr><tr>
		<td class='rang'>11</td>
		<td class='name'>CoIS</td>
		<td>5</td>
		<td class='groesse'>1947</td>
		</tr><tr>
		<td class='rang'>12</td>
		<td class='name'>-GR-</td>
		<td>4</td>
		<td class='groesse'>2170</td>
		</tr><tr>
		<td class='rang'>13</td>
		<td class='name'>AAW</td>
		<td>5</td>
		<td class='groesse'>2263</td>
		</tr><tr>
		<td class='rang'>14</td>
		<td class='name'>-SW-ist-bloed</td>
		<td>5</td>
		<td class='groesse'>4566</td>
		</tr><tr>
		<td class='rang'>15</td>
		<td class='name'>Troja</td>
		<td>3</td>
		<td class='groesse'>1592</td>
		</tr><tr>
		<td class='rang'>16</td>
		<td class='name'>SylSoD</td>
		<td>7</td>
		<td class='groesse'>3466</td>
		</tr><tr>
		<td class='rang'>17</td>
		<td class='name'>8472</td>
		<td>8</td>
		<td class='groesse'>1417</td>
		</tr><tr>
		<td class='rang'>18</td>
		<td class='name'>SuN</td>
		<td>3</td>
		<td class='groesse'>1071</td>
		</tr><tr>
		<td class='rang'>19</td>
		<td class='name'>RU.HE.</td>
		<td>2</td>
		<td class='groesse'>628</td>
		</tr><tr>
		<td class='rang'>20</td>
		<td class='name'>SoK</td>
		<td>1</td>
		<td class='groesse'>628</td>
		</tr><tr>
		<td class='rang'>21</td>
		<td class='name'>olDschOOl</td>
		<td>7</td>
		<td class='groesse'>2256</td>
		</tr><tr>
		<td class='rang'>22</td>
		<td class='name'>BoP-VisioN</td>
		<td>2</td>
		<td class='groesse'>0</td>
		</tr><tr>
		<td class='rang'>23</td>
		<td class='name'>LgC</td>
		<td>1</td>
		<td class='groesse'>397</td>
		</tr><tr>
		<td class='rang'>24</td>
		<td class='name'>OPPES</td>
		<td>2</td>
		<td class='groesse'>434</td>
		</tr><tr>
		<td class='rang'>25</td>
		<td class='name'>Mattys-kW</td>
		<td>1</td>
		<td class='groesse'>0</td>
		</tr><tr>
		<td class='rang'>26</td>
		<td class='name'>Dings</td>
		<td>1</td>
		<td class='groesse'>310</td>
		</tr><tr>
		<td class='rang'>27</td>
		<td class='name'>-pFC-</td>
		<td>1</td>
		<td class='groesse'>0</td>
		</tr><tr>
		<td class='rang'>28</td>
		<td class='name'>FdN</td>
		<td>1</td>
		<td class='groesse'>399</td>
		</tr><tr>
		<td class='rang'>29</td>
		<td class='name'>B.U.G</td>
		<td>1</td>
		<td class='groesse'>511</td>
		</tr><tr>
		<td class='rang'>30</td>
		<td class='name'>-</td>
		<td>1</td>
		<td class='groesse'>72</td>
		</tr><tr>
		<td class='rang'>31</td>
		<td class='name'>KGS</td>
		<td>2</td>
		<td class='groesse'>527</td>
		</tr><tr>
		<td class='rang'>32</td>
		<td class='name'>-G-A-</td>
		<td>1</td>
		<td class='groesse'>303</td>
		</tr><tr>
		<td class='rang'>33</td>
		<td class='name'>DARK</td>
		<td>2</td>
		<td class='groesse'>319</td>
		</tr><tr>
		<td class='rang'>34</td>
		<td class='name'>KoC</td>
		<td>2</td>
		<td class='groesse'>357</td>
		</tr><tr>
		<td class='rang'>35</td>
		<td class='name'>schlingels</td>
		<td>2</td>
		<td class='groesse'>262</td>
		</tr><tr>
		<td class='rang'>36</td>
		<td class='name'>PORZ</td>
		<td>1</td>
		<td class='groesse'>41</td>
		</tr><tr>
		<td class='rang'>37</td>
		<td class='name'>-FC-</td>
		<td>1</td>
		<td class='groesse'>0</td>
		</tr><tr>
		<td class='rang'>38</td>
		<td class='name'>Bretzfeld</td>
		<td>1</td>
		<td class='groesse'>226</td>
		</tr><tr>
		<td class='rang'>39</td>
		<td class='name'>wBuG</td>
		<td>1</td>
		<td class='groesse'>0</td>
		</tr><tr>
		<td class='rang'>40</td>
		<td class='name'>ETS</td>
		<td>1</td>
		<td class='groesse'>21</td>
		</tr><tr>
		<td class='rang'>41</td>
		<td class='name'>hSvabo</td>
		<td>1</td>
		<td class='groesse'>0</td>
		</tr><tr>
		<td class='rang'>42</td>
		<td class='name'>test</td>
		<td>1</td>
		<td class='groesse'>0</td>
		</tr><tr>
		<td class='rang'>43</td>
		<td class='name'>Frauenparkplatz</td>
		<td>1</td>
		<td class='groesse'>0</td>
		</tr><tr>
		<td class='rang'>44</td>
		<td class='name'>AIDS</td>
		<td>1</td>
		<td class='groesse'>0</td>
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
