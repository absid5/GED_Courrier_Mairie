<?php
/*
*
*    Copyright 2008,2012 Maarch
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
* @brief    Display document history
*
* @file     document_history.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  indexing_searching
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";
            
$core_tools = new core_tools();
$request    = new request();
$sec        = new security();
$list       = new lists();
$db         = new Database();

$parameters = '';

//Ressource ID
if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
} else {
    echo '<span class="error">'._ID.' '._IS_EMPTY.'</span>';
    exit();
}

//Collection ID
if(isset($_REQUEST['coll_id']) && !empty($_REQUEST['coll_id'])) {
    $table = $sec->retrieve_table_from_coll($_REQUEST['coll_id']);
    $view = $sec->retrieve_view_from_coll_id($_REQUEST['coll_id']);
    $parameters = "&coll_id=".$_REQUEST['coll_id'];
} else {
    echo '<span class="error">'._COLLECTION.' '._IS_EMPTY.'</span>';
    exit();
}

//Extra parameters
if (isset($_REQUEST['size']) && !empty($_REQUEST['size']))
    $parameters .= '&size='.$_REQUEST['size'];

if (isset($_REQUEST['load'])) {
    //
    $core_tools->load_lang();
    $core_tools->load_html();
    $core_tools->load_header('', true, false);
    
    ?><body><?php
    $core_tools->load_js();
    
    //Load list
    $target = $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=document_workflow_history&id='.$id.$parameters;
    $listContent = $list->loadList($target);
    echo $listContent;
    
    ?>
    </body>
    </html>
    <?php
} else {

    //If size is full change some parameters
    if (isset($_REQUEST['size']) 
        && ($_REQUEST['size'] == "full")
    ) {
        $sizeUser = "15";
        $sizeText = "30";
        $css = "listing spec";
        $cutString = 200;
        // $linesToShow  = 15;
    } else if (isset($_REQUEST['size']) 
        && ($_REQUEST['size'] == "medium")
    ) {
        $sizeUser = "15";
        $sizeText = "30";
        $css = "listing2 spec";
        $cutString = 100;
        $linesToShow  = 15;
    } else {
        $sizeUser = "10";
        $sizeText = "10";
        $css = "listingsmall";
        $cutString = 20;
        $linesToShow  = 15;
    }
    
    //Order
            $order = $order_field = '';
            $order = $list->getOrder();
            $order_field = $list->getOrderField();
            if (!empty($order_field) && !empty($order)) 
                $orderstr = "order by ".$order_field." ".$order;
            else  {
                $list->setOrder();
                $list->setOrderField('event_date');
                $orderstr = "order by event_date desc";
            }
            
    //From filters
        $where = "";
        $filterClause = $list->getFilters(); 
        if (!empty($filterClause)) $where = ' and '.$filterClause;//Filter clause
        
        //Query
            if((empty($table)|| !$table) && (!empty($view) && $view <> false)) {
                $whereTableOrView = "h.table_name= '" . $view. "'";
            }
            elseif((empty($view) || !$view) && (!empty($table)&& $table <> false))  {
                $whereTableOrView = "h.table_name= '" . $table. "'";
            }
            elseif(!empty($view) && !empty($table)&& $view <> false && $table <> false) {
                $whereTableOrView = "(h.table_name= '" . $table . "' OR h.table_name = '" . $view . "')";
            }
            
            $stmt = $db->query("SELECT h.event_date, ".$_SESSION['tablename']['users'].".user_id, "
                .$_SESSION['tablename']['users'].".firstname, ".$_SESSION['tablename']['users']
                .".lastname, h.info FROM " .$_SESSION['tablename']['history']
                ." h, ".$_SESSION['tablename']['users'] ." WHERE "
                .$whereTableOrView." and h.record_id = ? and h.user_id = ".$_SESSION['tablename']['users']
                .".user_id".$where." and event_id ~ '^[0-9]+$' and event_type like 'ACTION#%' ".$orderstr, array($id));
            // $request->show();
            
            $tab=array();
            while($line = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $temp= array();
                foreach (array_keys($line) as $resval)
                {
                    if (!is_int($resval))
                    {
                        array_push($temp,array('column'=>$resval,'value'=>$line[$resval]));
                    }
                }
                array_push($tab, $temp);
            }

        //Result Array
            for ($i=0;$i<count($tab);$i++)
            {
                for ($j=0;$j<count($tab[$i]);$j++)
                {
                    foreach(array_keys($tab[$i][$j]) as $value)
                    {
                        if($tab[$i][$j][$value]=="id")
                        {
                            $tab[$i][$j]["id"]=$tab[$i][$j]['value'];
                            $tab[$i][$j]["label"]=_ID;
                            $tab[$i][$j]["size"]="1";
                            $tab[$i][$j]["label_align"]="left";
                            $tab[$i][$j]["align"]="left";
                            $tab[$i][$j]["valign"]="bottom";
                            $tab[$i][$j]["show"]=true;
                            $tab[$i][$j]["order"]='id';
                        }
                        if($tab[$i][$j][$value]=="event_date")
                        {
                            $tab[$i][$j]["value"]=$request->dateformat($tab[$i][$j]["value"]);
                            $tab[$i][$j]["label"]=_DATE;
                            $tab[$i][$j]["size"]="10";
                            $tab[$i][$j]["label_align"]="left";
                            $tab[$i][$j]["align"]="left";
                            $tab[$i][$j]["valign"]="bottom";
                            $tab[$i][$j]["show"]=true;
                            $tab[$i][$j]["order"]='event_date';
                        }
                        if($tab[$i][$j][$value]=="firstname")
                        {
                            $firstname =  $request->show_string($tab[$i][$j]["value"]);
                        }
                        if($tab[$i][$j][$value]=="lastname")
                        {
                            $tab[$i][$j]["value"] = $request->show_string($tab[$i][$j]["value"]). ' ' .$firstname ;
                            $tab[$i][$j]["label"]=_USER;
                            $tab[$i][$j]["size"]=$sizeUser;
                            $tab[$i][$j]["label_align"]="left";
                            $tab[$i][$j]["align"]="left";
                            $tab[$i][$j]["valign"]="bottom";
                            $tab[$i][$j]["show"]=true;
                            $tab[$i][$j]["order"]='lastname';
                        }
                        if($tab[$i][$j][$value]=="info")
                        {
                            $tab[$i][$j]["value"] = $request->show_string($tab[$i][$j]["value"]);
                            $tab[$i][$j]["label"]=_EVENT;
                            $tab[$i][$j]["size"]=$sizeText;
                            $tab[$i][$j]["label_align"]="left";
                            $tab[$i][$j]["align"]="left";
                            $tab[$i][$j]["valign"]="bottom";
                            $tab[$i][$j]["show"]=true;
                            $tab[$i][$j]["order"]='info';
                        }
                    }
                }
            }

        //List
            $listKey = 'id';                                                            //Clé de la liste
            $paramsTab = array();                                                       //Initialiser le tableau de paramètres
            $paramsTab['bool_sortColumn'] = true;                                       //Affichage Tri
            $paramsTab['pageTitle'] ='';                                                //Titre de la page
            $paramsTab['bool_bigPageTitle'] = false;                                    //Affichage du titre en grand
            $paramsTab['urlParameters'] = 'dir=indexing_searching&id='
                .$id.'&display=true'.$parameters;                                       //Parametres d'url supplementaires 
            $paramsTab['listHeight'] = '100%';                                          //Hauteur de la liste
            // $paramsTab['bool_showSmallToolbar'] = true;                              //Mini barre d'outils
            // $paramsTab['linesToShow'] = $linesToShow;                                //Nombre de ligne a afficher 
     
            //Output
            $status = 0;
            $content = $list->showList($tab, $paramsTab, $listKey);
            $debug = '';
            //$debug = $list->debug();

    echo "{status : " . $status . ", content : '" . addslashes($debug.$content) . "', error : '" . addslashes($error) . "'}";
}