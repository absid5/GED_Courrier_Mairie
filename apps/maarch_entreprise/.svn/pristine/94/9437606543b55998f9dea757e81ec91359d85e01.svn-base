<?php 
/*
*   Copyright 2008-2010 Maarch
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
* @brief  Activates function to show welcome message file in Maarch Entreprise
* 
* @file
* @author  Loïc Vinet  <dev@maarch.org>
* @author  Claire Figueras  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->test_service('welcome_text_load', "apps");

function get_file_template($this_file)
{     
  //File opening
  $mail_trait = file_get_contents($this_file);
  //Deleting comments 
  $mail_trait = preg_replace("/(<!--.*?-->)/s","", $mail_trait);    
  return $mail_trait; 
}

if (
  (isset($_SESSION['custom_override_id']) 
  && $_SESSION['custom_override_id'] <> '')
  && file_exists(
        'custom' . DIRECTORY_SEPARATOR 
        . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
        . 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
        . '/welcome_file.html'
    )
) {
   $path = 'custom' . DIRECTORY_SEPARATOR 
        . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
        . 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id'];
} else {
  $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id'];
}


?>
<div id="welcome_box_left_text" >
  <?php echo get_file_template($path . "/welcome_file.html");?>
  <div class="blank_space">&nbsp;</div>     
</div>
