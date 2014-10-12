<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/report">
<html>
<head>
	<title>Escape to Space: Bericht (formatiert für das Weltforum)</title>
	<link href="css/css.css" type="text/css" rel="stylesheet" />
</head>

<body id="forenberichte" onLoad="document.getElementsByTagName('textarea')[0].select();" >
<div id="content">
<p class="table_head">Bericht (formatiert für das Weltforum)</p>
<p>Im Textfeld unten ist der ausgewählte Bericht bereits für das Posten im <a href="http://forum.escape-to-space.de">Weltforum</a> vorbereitet (keine Ingame-Nicks mehr drin, BB-Codes bereits eingefügt). Du kannst einfach den Inhalt des Textfeldes kopieren und in deinen Beitrag einfügen.</p>

<textarea cols="50" rows="40">[quote]<xsl:if test="contains(subject, 'wurde erfolgreich erobert')">[b][size=150]Kolonie erfolgreich erobert![/size][/b]</xsl:if>
<xsl:if test="contains(subject, 'Sie haben Ihre Kolonie')">[b][size=150]Kolonie an den Feind verloren![/size][/b]</xsl:if>
[b]Zeit:[/b] <xsl:value-of select="concat(substring(time,12), ', ', substring(time,9,2), '.', substring(time,6,2), '.', substring(time,1,4))" />

[b]Angreifer: K<xsl:value-of select="substring-before(origin/@coordinates,':')" />&#160;<xsl:if test="origin/@alliance != ''">(<xsl:value-of select="origin/@alliance" />)</xsl:if>[/b]
<xsl:for-each select="attacker/unit"><xsl:value-of select="@type" />&#160;<xsl:value-of select="@sent" />&#160;<xsl:value-of select="@lost" />&#160;[i](<xsl:value-of select="round(@lost div @sent * 100)" />%)[/i]
</xsl:for-each>
[b]Verteidiger: K<xsl:value-of select="substring(destination/@coordinates,1,1)" />&#160;<xsl:if test="destination/@alliance != ''">(<xsl:value-of select="destination/@alliance" />)</xsl:if>[/b]
<xsl:for-each select="defender/unit[@type!='Schutzschild']"><xsl:value-of select="@type" />&#160;<xsl:value-of select="@sent" />&#160;<xsl:value-of select="@lost" />&#160;[i](<xsl:value-of select="round(@lost div @sent * 100)" />%)[/i]
</xsl:for-each>Schutzschild <xsl:value-of select="defender/unit[@type='Schutzschild']/@sent" />&#160;<xsl:value-of select="defender/unit[@type='Schutzschild']/@lost" />
[i]Punkte <xsl:choose>
<xsl:when test="defender/@points &lt; 40"><xsl:value-of select="defender/@points" />[/i]</xsl:when>
<xsl:otherwise>ca. <xsl:value-of select="round(defender/@points div 50) * 50" />[/i]</xsl:otherwise>
</xsl:choose><xsl:if test="transport/ressource[@amount > 0]">

[b]<xsl:choose>
<xsl:when test="@type = 'Spy'">Rohstoffe</xsl:when>
<xsl:otherwise>Plünderung</xsl:otherwise>
</xsl:choose>:[/b]
<xsl:for-each select="transport/ressource"><xsl:if test="@amount &gt; 0"><xsl:value-of select="@type" />&#160;<xsl:value-of select="@amount" />&#160;
</xsl:if></xsl:for-each></xsl:if>
<xsl:if test="fleetname != ''">
[b]Flottentext:[/b]
<xsl:value-of select="fleetname" />
</xsl:if>[/quote]
</textarea>
<p><a><xsl:attribute name="href">./messages_berichte.php?bid=<xsl:value-of select="@bid" /></xsl:attribute>zurück zum Bericht</a><br />
<a href="javascript:window.close()">Fenster schliessen</a></p>

</div>
</body>
</html>
</xsl:template>

</xsl:stylesheet>