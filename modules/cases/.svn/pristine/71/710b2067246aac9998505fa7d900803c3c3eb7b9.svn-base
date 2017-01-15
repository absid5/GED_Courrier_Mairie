<?php
/*
*    Copyright 2008,2009 Maarch
*
*  This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief  Include case properties in detail.php if cases's module is activated
*
* @file hist_doc.php
* @author Loic Vinet <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/

if (isset($case_properties['case_id']) && $case_properties['case_id'] <> '') {
?>
<h2>
    <span class="date">
	<b><?php echo _LINKED_TO_CASE;?></b>
	</span>
</h2>
<?php
echo "<p align='right'><b><a href = '"
    . $_SESSION['config']['businessappurl']
    . "index.php?page=details_cases&module=cases&id="
    . $case_properties['case_id'] . "'><i class='fa fa-briefcase fa-2x'></i> " . _CLICK_HERE_TO_ACCESS_CASE.
    "</a></b></p>";
?>
<br/>
<table cellpadding="2" cellspacing="2" border="0" class="block forms details" width="100%">
    <tr>
	    <th align="left" class="picto">
	       <!--	<img alt="<?php echo _CASE;?>" src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=picto_infos.gif" />-->
	    </th>
	    <td align="left" width="200px">
            <?php echo _NUM_CASE;?> :
		</td>
		<td>
		  <input type="text" class="readonly" readonly="readonly" value="<?php
functions::xecho($case_properties['case_id']);
?>" size="40" title="<?php
functions::xecho($case_properties['case_id']);
?>" alt="<?php functions::xecho($case_properties['case_id']);?>" />
        </td>
		<th align="left" class="picto">
		  <!--<img alt="<?php echo _CASE;?>" src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=picto_infos.gif" />-->
		</th>
		<td align="left" width="200px">
		    <?php functions::xecho(_CASE_LABEL);?> :
		</td>
		<td>
		    <input type="text" class="readonly" readonly="readonly" value="<?php
functions::xecho($case_properties['case_label']);
?>" size="40" title="<?php
functions::xecho($case_properties['case_label']);
?>" alt="<?php functions::xecho($case_properties['case_label']);?>" />
		</td>
	</tr>
	<tr>
	    <th align="left" class="picto">
	       <!--<img alt="<?php echo _CASE;?>" src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=picto_infos.gif" />-->
	    </th>
		<td align="left" width="200px">
		    <?php echo _CASE_DESCRIPTION;?> :
		</td>
		<td colspan="3">
		  <input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($case_properties['case_description']);?>" size="40" title="<?php functions::xecho($case_properties['case_description']);?>" alt="<?php functions::xecho($case_properties['case_description']);?>" />
		</td>
    </tr>
</table>
<br/>
<?php
}
?>
