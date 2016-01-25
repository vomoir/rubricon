<?xml version="1.0" ?>
<!--
    Document: sqlite_recordset2html.xsl
    Author  : tony edwards
    Version : 1.0, 2009-12-17
    Comment : add this line to xml schema file after <?xml version="1.0"/>:
              <?xml-stylesheet type="text/xsl" href="sqlite_recordset2html.xsl"?>
              Converts sqlite recordset to nicely displayed html.
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="yes"/>
<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="recordset">
    <xsl:apply-templates select="rows/row"/>
</xsl:template>

<xsl:template match="row">
	<xsl:variable name="jot_id" select="column[@name='jot_id']/value"/>
	<div class="jot">
		<input type="button" id="btnEditTop" onclick="editJot({$jot_id})" value="edit"></input>
	    <p>Date: <xsl:value-of select="column[@name='jot_date']/value"/></p>
	    <p>Type: <xsl:value-of select="column[@name='jottype']/value"/></p>
	    <p>Contact: <xsl:value-of select="concat(column[@name='firstname'],'  ', column[@name='lastname'])"/></p>
	    <p>Subject: <xsl:value-of select="column[@name='subject']/value"/></p>
	    <p>Message: <xsl:value-of select="column[@name='message']/value"/></p>
	    <input type="button" id="btnEdit" onclick="editJot({$jot_id})" value="edit"></input>
	    <hr />
	</div>
</xsl:template>

</xsl:stylesheet>

