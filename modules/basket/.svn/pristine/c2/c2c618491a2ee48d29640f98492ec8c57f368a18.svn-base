<?php
/*
*
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
* @brief Displays absence management data in the user profil
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/
?>
<script type="text/javascript">
	function test_form()
	{
		var error_num = check_form_baskets("redirect_my_baskets_to");
		if( error_num == 1)
		{
			document.getElementById('redirect_my_baskets_to').submit();
		}
		else if(error_num == 2)
		{
			alert("<?php echo _FORMAT_ERROR_ON_USER_FIELD;?>");
		}
		else if(error_num == 3)
		{
			alert("<?php echo _BASKETS_OWNER_MISSING;?>");
		}
		else if(error_num == 4)
		{
			alert("<?php echo _CHOOSE_USER_TO_REDIRECT;?>");
		}
		else
		{
			alert("<?php echo _FORM_ERROR;?>");
		}
	}
</script>
<?php
   //	$this->show_array($_SESSION['user']['baskets']);
    require_once('modules'.DIRECTORY_SEPARATOR.'basket'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');
	$bask = new basket();
	$modal_content = $bask->redirect_my_baskets_list($_SESSION['user']['baskets'], count($_SESSION['user']['baskets']), $_SESSION['user']['UserId'], "listingbasket specsmall");
	echo "<div>";
	?>
		<script type="text/javascript">
			var modal_content = '<?php echo addslashes($modal_content);?>';
		</script>
		<h2><a href="javascript://" onclick="createModal(modal_content, 'modal_redirect', <?php if(count($_SESSION['user']['baskets']) >0) {?>'auto', '950px'<?php }else{?>'100px', '300px'<?php }?>);window.location.href='#top';autocomplete(<?php echo count($_SESSION['user']['baskets']);?>, '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=basket&page=autocomplete_users_list')"><i class = "fa fa-user-times fa-3x" title="" /></i> <?php echo _MY_ABS;?> </a></h2>
         <p id="abs"><?php echo _MY_ABS_TXT;?></p>
    </div>
