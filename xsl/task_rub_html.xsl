<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="yes"/>
<xsl:key name="task_key" match="task" use="@id"/>
<!-- ##################################### -->
<xsl:template match="/task_rubrics">
	<div class="row" id="rubrow">		
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="task">
	<xsl:variable name="rLevel" select="rubric_details/@r_level"/>
	<xsl:variable name="divId" select="concat('rub',$rLevel)"/>
	<xsl:variable name="divTitle" select="concat('Rubric Level Description ', $rLevel)"/>
	<xsl:variable name="taId" select="concat('ta', $rLevel)"/>

	<div id="{$divId}" class="col-xs-4 col-md-2 col-lg-2 margin-left-md">
		<xsl:value-of select="$divTitle"/>
		<textarea class="form-control rubric" rows="3" id="{$taId}">
			<xsl:value-of select="rubric_details/text()"/>
		</textarea>
	</div>
</xsl:template>

<xsl:template match="text()"/>
</xsl:stylesheet>
