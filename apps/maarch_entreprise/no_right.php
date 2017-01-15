<?php  

/*
*    Copyright 2008,2015 Maarch
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
* File : no_right.php
*
* Default error of right page
*
* @package  Maarch PeopleBox 1.0
* @version 2.1
* @since 10/2005
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/;

$core_tools = new core_tools();

unset($_SESSION['location_bar']['level2']);
unset($_SESSION['location_bar']['level3']);
unset($_SESSION['location_bar']['level4']);
$core_tools->manage_location_bar();
?>
<h1><?php echo _NO_RIGHT;?></h1>
<div id="inner_content" class="clearfix">
<br/><br/>
<div class="error"><?php echo _NO_RIGHT_TXT;?></div>
</div>
