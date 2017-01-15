<?php
/*
*    Copyright 2008-2015 Maarch
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
* @brief  Contains the functions to load administration services
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

abstract class admin_Abstract extends functions
{
	/**
	* Displays the administration services for the application
	*
	* @param $app_services Array Application services
	*/
	public function display_app_admin_services($app_services)
	{
		$debug_style = '';
	    if (preg_match("/MSIE 6.0/", $_SERVER['HTTP_USER_AGENT']))
			$debug_style =  'style="height:100px;"';

		$display_app_services = false;
		for($i=0;$i<count($app_services);$i++)
		{
			if ($app_services[$i]['servicetype'] == "admin"
			    && isset($_SESSION['user']['services'][$app_services[$i]['id']])
			    && $_SESSION['user']['services'][$app_services[$i]['id']]
			) {
				$display_app_services = true;
				break;
			}
		}

		if ($display_app_services) {
			echo '<div class="block">';
			echo '<h2 style="text-align:center;">Application</h2>';		
			echo '<div class="content" id="admin_apps">';
			for($i=0;$i<count($app_services);$i++)
			{
				if ($app_services[$i]['servicetype'] == "admin"
				    && isset($_SESSION['user']['services'][$app_services[$i]['id']])
				    && $_SESSION['user']['services'][$app_services[$i]['id']]
				) {
					?>
	                <div class="admin_item" title="<?php functions::xecho($app_services[$i]['comment']);?>" onclick="window.top.location='<?php echo($app_services[$i]['servicepage']);?>';">
	                    <div><i class="<?php functions::xecho($app_services[$i]['style']);?> fa-4x"></i></div>
	                    <div <?php functions::xecho($debug_style);?>>

	                            <strong><?php functions::xecho($app_services[$i]['name']);?></strong>

	                    </div>
	                </div>
	                <?php
				}
			}
			echo '<div class="clearfix"></div>';
			echo '</div>';
			echo '</div>';
		}
	}

	/**
	* Displays the administration services for each module
	*
	* @param $modules_services Array Modules services
	*/
	public function display_modules_admin_services($modules_services)
	{
		$debug_style = '';
	    if (preg_match("/MSIE 6.0/", $_SERVER['HTTP_USER_AGENT'])) {
			$debug_style =  'style="height:100px;"';
	    }
		$nb = 0;
		foreach (array_keys($modules_services) as $value) {
			for ($i = 0; $i < count($modules_services[$value]); $i ++) {
				if (isset($_SESSION['user']['services'][$modules_services[$value][$i]['id']])
				    && $modules_services[$value][$i]['servicetype'] == "admin"
				    && $_SESSION['user']['services'][$modules_services[$value][$i]['id']]
				) {
					if ($nb == 0) {
						echo '<div class="block" style="margin-top:10px;">';
						echo '<h2 style="text-align:center;">Modules</h2>';
						echo '<div class="content" id="admin_modules">';
					}
					$nb ++;
					?>
					<div class="admin_item" title="<?php echo 'Module '
					. functions::xssafe($value) .' : ' 
					. functions::xssafe($modules_services[$value][$i]['comment']);?>" onclick="window.top.location='<?php echo($modules_services[$value][$i]['servicepage']);?>';">
						<i class="<?php functions::xecho($modules_services[$value][$i]['style']);?> fa-4x"></i>
						<div <?php functions::xecho($debug_style);?> >

								<strong><?php functions::xecho($modules_services[$value][$i]['name']);?></strong>
						</div>
					</div>
					<?php
				}
			}
		}
		if ($nb > 0) {
			echo '</div>';
			echo '<div class="clearfix"></div>';
			echo '</div>';
		}
		
	}
}
