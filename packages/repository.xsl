<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
		<html>
			<head/>
			<style>
			#one-column-emphasis{font-family:"Lucida Sans Unicode", "Lucida Grande", Sans-Serif;font-size:12px;width:90%;text-align:left;border-collapse:collapse;margin:20px;}
			#one-column-emphasis th{font-size:14px;font-weight:normal;color:#039;padding:12px 15px;}
			#one-column-emphasis td{color:#669;border-top:1px solid #e8edff;padding:10px 15px;}.oce-first{background:#d0dafd;border-right:10px solid transparent;border-left:10px solid transparent;}
			#one-column-emphasis tr:hover td{color:#339;background:#eff2ff;}
			</style>
			<body style="font-family: sans-serif;">
			<h1>
			 <xsl:text>Packages of </xsl:text>
			 <xsl:value-of select="packages/@vendor"/>
			</h1>
		<table id="one-column-emphasis" >
		<colgroup><col/><col class="oce-first"/></colgroup>
		<tr>
		<th scope="col">Thumbnail</th>
		<th scope="col">Name</th>
		<th scope="col">Version</th>
		<th scope="col">Type</th>
		<th scope="col">Summary</th>
		<th scope="col">Description</th>
		<th scope="col">Dependancy</th>
		<th scope="col">Download Link</th>
		</tr>
		<xsl:apply-templates/>
		</table>

			</body>
		</html>
	</xsl:template>

<xsl:template match="package">
    <tr>
	  <td>
      <img>
      <xsl:attribute name="src">
        <xsl:value-of select="@thumbnail_url"/>
      </xsl:attribute>
      </img>
      </td>
      <td><xsl:value-of select="@name"/></td>
      <td><xsl:value-of select="@version"/></td>
      <td><xsl:value-of select="@type"/></td>
      <td><xsl:value-of select="@summary"/></td>
      <td><xsl:value-of select="@description"/></td>
	  <td>
	  <xsl:apply-templates/>
	  </td>
	  
      <td>
      <A>
      <xsl:attribute name="HREF">
        <xsl:value-of select="@url"/>
      </xsl:attribute>
      <xsl:text>Download</xsl:text>
      </A>
      </td>
    </tr>
</xsl:template>

<xsl:template match="require">
<xsl:value-of select="@name"/><br/>
</xsl:template>
</xsl:stylesheet>