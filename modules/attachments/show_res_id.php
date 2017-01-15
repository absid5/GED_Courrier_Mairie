<?php

/*
*   Copyright 2015 Maarch
*
*   This file is part of Maarch Framework.
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

$core = new core_tools();
$core->test_user();

?>

<script type="text/javascript">
var input_res = window.opener.$('res_id_link');

//VALIDATE_MAIL
if (input_res) {
	input_res.value=<?php echo json_encode($_SESSION['stockCheckbox']);?>;
	window.opener.$('attach_link').click();
} else { //INDEX_MLB
	window.opener.$('res_id').value=<?php echo json_encode($_SESSION['stockCheckbox']);?>;
}

<?php
if ($_SESSION['current_basket']['id'] == "IndexingBasket") {
	?>window.opener.$('attach').click();<?php
}
?>

self.close();
</script>
