<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="yes"/>
<xsl:key name="task_key" match="task" use="@id"/>
<!-- ##################################### -->
<xsl:template match="/task_rubrics">
	<div>
		<h3>Rubank</h3>
		<table class="table" id="rubank">
			<thead>
				<xsl:for-each select="task[1]">				
					<tr>
						<th>Task</th>
						<xsl:for-each select="key('task_key', @id)">
							<th>Level <xsl:value-of select="position()"/></th>
			 			</xsl:for-each>
			 		</tr>
				</xsl:for-each>
			</thead>
			<tbody>
				<xsl:for-each select="task[count(.|key('task_key',@id)[1]) = 1]">
					<tr>
						<td><a href="#" onclick="getTask({@id},&#39;{text()}&#39;)"><xsl:value-of select="./text()" /></a></td>
						<xsl:for-each select="key('task_key', @id)">
							<td><xsl:value-of select="rubric_details"/></td>
			 			</xsl:for-each>
			 		</tr>
				</xsl:for-each>
			</tbody>
		</table>
	</div>
</xsl:template>

<xsl:template match="text()"/>
</xsl:stylesheet>