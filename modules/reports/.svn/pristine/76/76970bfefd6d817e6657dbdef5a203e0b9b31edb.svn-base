<?php

/*
*   Copyright 2008-2016 Maarch
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

/**
* Graphics Class
*
* Contains the functions to create graphics
*
* @package Maarch PeopleBox 1.0
* @version 1.0
* @since 06/2007
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*
*/

abstract class graphics_Abstract
{
	/**
	* Graphic border color
	*
    * @access protected
    * @var Color Object
    */
	protected $border_color;

	/**
	* Color of the text
	*
    * @access protected
    * @var Color Object
    */
	protected $typo_color;

	/**
	* General background of the graphic
	*
    * @access protected
    * @var Color Object
    */
	protected $background_color ;

	/**
	* Color of the plot
	*
    * @access protected
    * @var Color Object
    */
	protected $plot_color;

	/**
	* Color of the second plot
	*
    * @access protected
    * @var Color Object
    */
	protected $plot_color2;

	/**
	* Color of the first plot filling
	*
    * @access protected
    * @var Color Object
    */
	protected $filling_color;

	/**
	* Color of the second plot filling
	*
    * @access protected
    * @var Color Object
    */
	protected $filling_color2;

	/**
	* Grid color
	*
    * @access protected
    * @var Color Object
    */
	protected $grid_color;

	/**
	* Color of the axis
	*
    * @access protected
    * @var Color Object
    */
	protected $axis_color;

	/**
	* In the bar graphic, color of the shadow
	*
    * @access protected
    * @var Color Object
    */
	protected $bar_shadow_color;

	/**
	* Construct method : load the color
	*
	*/
	function __construct()
	{
		// $this->border_color = new Color(153, 153, 153);
		// $this->typo_color = new Color(0, 0, 0);
		// $this->plot_color = new Color(200, 0, 0, 20);
		// $this->filling_color2 = new Color(200, 80, 80, 75);
		// $this->filling_color = new Color(255, 250, 174, 50);
		// $this->grid_color = new Color(255, 255, 255);
		// $this->background_color = new Color(212, 208, 200);
		// $this->axis_color = new Color(102, 102, 102);
		// $this->bar_shadow_color = new Color(180, 180, 180, 10);
		// $this->plot_color2 = new Color(254, 194, 0);
	}


	public function show_stats_array($title, $data)
	{
		$nb_coll = count($data[0]);
		$keys = array_keys($data[0]);
		?><div align="center">
			<div><b><?php functions::xecho($title);?></b></div>
             <br/>
             <table  border="0" cellspacing="0" class="listing spec">
             	<thead>
				<tr>
                <?php for($i=0; $i< $nb_coll;$i++)
				{?>
                	<th><?php functions::xecho($data[0][$keys[$i]]);?></th>
                <?php
				}?>
                </tr>
                </thead>
               	<tbody>

                     <?php
					 $color = "";
					 for($i=1; $i< count($data);$i++)
					{
						if($color == ' class="col"')
						{
							$color = '';
						}
						else
						{
							$color = ' class="col"';
						}?>
                    	<tr <?php echo $color;?>>
                        	<?php
							for($j=0; $j< $nb_coll;$j++)
							{?>
								<td><?php functions::xecho($data[$i][$keys[$j]]);?></td>
								<?php
							}?>
                         </tr>
                    <?php
					}?>

                </tbody>
             </table>
        </div><?php
	}
}
?>
