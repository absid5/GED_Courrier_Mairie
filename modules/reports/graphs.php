<?php
/**
* File : graph_test.php
*
* Show the graphics (used in the welcome page and the stats page)
*
* @package  Maarch PeopleBox 1.0
* @version 2.0
* @since 06/2006
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/
require_once('modules'.DIRECTORY_SEPARATOR.'reports'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_graphics.php');

$graphi = new graphics();

$title = "";
if(!empty($_GET['title']) && isset($_GET['title']))
{
	$tab_title=explode('?', $_GET['title']);
	$title = $tab_title[0];
}

$width = 1000;
if(isset($_GET['largeur']) && !empty($_GET['largeur']))
{
	$width = $_GET['largeur'];
}

$height = 500;
if(isset($_GET['hauteur']) && !empty($_GET['hauteur']))
{
	$height = $_GET['hauteur'];
}

$margin_bottom = 80;
if(isset($_GET['marge_bas']) && !empty($_GET['marge_bas']))
{
	$margin_bottom = $_GET['marge_bas'];
}

$labelX = "";
if(isset($_GET['labelX']) && !empty($_GET['labelX']))
{
	$labelX = $_GET['labelX'];
}

$labelY = "";
if(isset($_GET['labelY']) && !empty($_GET['labelY']))
{
	//$labelY = $_GET['labelY'];
	$tab_labelY = explode('?', $_GET['labelY']);
	$labelY = $tab_labelY[0];
}

$plot1_legend = "";
if(isset($_GET['plot1_legend']) && !empty($_GET['plot1_legend']))
{
	$plot1_legend = $_GET['plot1_legend'];
}

$plot2_legend = "";
if(isset($_GET['plot2_legend']) && !empty($_GET['plot2_legend']))
{
	$plot2_legend = $_GET['plot2_legend'];
}

if(isset($_GET['values']))
{
	if(count($_GET['values']) > 0)
	{
		$values = array();

		for($i=0;$i<count($_GET['values']);$i++)
		{
			array_push($values, $_GET['values'][$i]);
		}
	}
	else
	{
		echo "Error";
		exit();
	}
}

$courbe2 = array();
if(isset($_GET['val_courbe2']))
{

		for($i=0;$i<count($_GET['val_courbe2']);$i++)
		{
			array_push($courbe2, $_GET['val_courbe2'][$i]);
		}

}

$labels = array();
if(isset($_GET['labels']))
{
	if(count($_GET['labels']) > 0)
	{
		for($i=0;$i<count($_GET['labels']);$i++)
		{
			array_push($labels, $_GET['labels'][$i]);
		}
	}
}

// if($_GET['type'] == "histo")
// {
// 	$graphi->histo($width, $height, $_SESSION['GRAPH']['VALUES'], $title, $_SESSION['labels1'], $margin_bottom,$labelX,$labelY);
// }
// elseif($_GET['type'] == "courbe")
// {
// 	$graphi->courbe($width, $height, $values, $title, $labels, $labelX, $labelY);
// }
// elseif($_GET['type'] == "2courbes")
// {
// 	$graphi->groupe_courbes($width, $height, $values, $title, $labels, $labelX, $labelY, $courbe2, $plot1_legend, $plot2_legend);
// }
// elseif($_GET['type'] == "pie")
// {
// 	$graphi->camembert($width, $height, $values, $title, $_SESSION['labels1']);
// }

?>
