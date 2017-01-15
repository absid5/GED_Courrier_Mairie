<?php
/*
*
*    Copyright 2008,2013 Maarch
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
* @brief    Send emails
*
* @file     sendmail.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  sendmail
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";
require_once "modules".DIRECTORY_SEPARATOR."sendmail".DIRECTORY_SEPARATOR."sendmail_tables.php";
require_once "modules" . DIRECTORY_SEPARATOR . "sendmail" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";
    
$core_tools = new core_tools();
$request    = new request();
$list       = new lists();   
$sendmail_tools = new sendmail();
    
$identifier = '';
$origin = '';
$parameters = '';

//Collection ID
if(isset($_REQUEST['coll_id']) && !empty($_REQUEST['coll_id'])) 
    $parameters = "&coll_id=".$_REQUEST['coll_id'];
else if ((isset($_SESSION['collection_id_choice']) && !empty($_SESSION['collection_id_choice'])))
    $parameters = "&coll_id=".$_SESSION['collection_id_choice'];
    
//Identifier
if (isset($_REQUEST['identifier']) && !empty($_REQUEST['identifier'])) {
    $identifier = $_REQUEST['identifier'];
} else if (isset($_SESSION['doc_id']) && !empty($_SESSION['doc_id'])) {
        $identifier = $_SESSION['doc_id'];
} else {
    echo '<span class="error">'._IDENTIFIER.' '._IS_EMPTY.'</span>';
    exit();
}

//Origin
if (isset($_REQUEST['origin']) && !empty($_REQUEST['origin'])) $origin = $_REQUEST['origin']; else $origin = 'document';
 
//Extra parameters
if (isset($_REQUEST['size']) && !empty($_REQUEST['size'])) 
    $parameters .= '&size='.$_REQUEST['size']; else $parameters .= '&size=full';
if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) $parameters .= '&order='.$_REQUEST['order'];
if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) $parameters .= '&order_field='.$_REQUEST['order_field'];
if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) $parameters .= '&what='.$_REQUEST['what'];
if (isset($_REQUEST['start']) && !empty($_REQUEST['start'])) $parameters .= '&start='.$_REQUEST['start'];

 if (isset($_REQUEST['load'])) {
    $core_tools->load_lang();
    $core_tools->load_html();
    $core_tools->load_header('', true, false);
    
    ?><body><?php
    $core_tools->load_js();

    //Load list
    if (!empty($identifier)) {
    
        $target = $_SESSION['config']['businessappurl']
            .'index.php?module=sendmail&page=sendmail&identifier='
            .$identifier.'&origin='.$origin.$parameters;
            
        $listContent = $list->loadList($target);
        echo $listContent;
    } else {
        echo '<span class="error">'._ERROR_IN_PARAMETERS.'</span>';
    }
    ?><div id="container" style="width:100%;min-height:0px;height:0px;"></div></body></html><?php
} else {
	
    //If size is full change some parameters
    if (isset($_REQUEST['size']) 
        && ($_REQUEST['size'] == "full")
    ) {
        $sizeUser = "10";
        $sizeObject = "30";
        $css = "listing spec";
        $cutString = 150;
    } else if (isset($_REQUEST['size']) 
        && ($_REQUEST['size'] == "medium")
    ) {
        $sizeUser = "15";
        $sizeObject = "30";
        $css = "listingsmall";
        $cutString = 100;
    } else {
        $sizeUser = "10";
        $sizeObject = "10";
        $css = "listingsmall";
        $cutString = 20;
    }
    
    //Table or view
        $select[EMAILS_TABLE] = array(); //Emails
        $select[USERS_TABLE] = array(); //Users
        
    //Fields
        array_push($select[EMAILS_TABLE], "email_id", "res_id", "creation_date", "user_id", 
            "email_object", "email_object as email_object_short", "sender_email", "user_id as email_expediteur", "to_list as email_destinataire",  "email_id as id", 
            "coll_id", "email_status", "email_status as status_img", "email_status as status_label");    //Emails
        array_push($select[USERS_TABLE], "user_id", "firstname", "lastname","mail");  //Users
        
    //Where clause
        $where_tab = array();
        //
        $where_tab[] = " res_id = " . $identifier . " ";
        //From filters
        $filterClause = $list->getFilters(); 
        if (!empty($filterClause)) $where_tab[] = $filterClause;//Filter clause
        //Build where
        $where = implode(' and ', $where_tab);
    
    //Order
        $order = $order_field = '';
        $order = $list->getOrder();
        $order_field = $list->getOrderField();
        if (!empty($order_field) && !empty($order)) 
            $orderstr = "order by ".$order_field." ".$order;
        else  {
            $list->setOrder();
            $list->setOrderField('creation_date');
            $orderstr = "order by creation_date desc";
        }
    
    //Request
        $tab=$request->PDOselect(
            $select, $where, array(), $orderstr,
            $_SESSION['config']['databasetype'], "500", true, EMAILS_TABLE, USERS_TABLE,
            "user_id"
        );
        // $request->show();
        
    //Result Array
        for ($i=0;$i<count($tab);$i++)
        {
            for ($j=0;$j<count($tab[$i]);$j++)
            {
                foreach(array_keys($tab[$i][$j]) as $value)
                {
                    if($tab[$i][$j][$value]=="email_id")
                    {
                        $tab[$i][$j]["email_id"]=$tab[$i][$j]['value'];
                        $tab[$i][$j]["label"]='ID';
                        $tab[$i][$j]["size"]="1";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=false;
                        $tab[$i][$j]["order"]='id';
                    }
                    if($tab[$i][$j][$value]=="creation_date")
                    {
                        $tab[$i][$j]["value"]=$request->dateformat($tab[$i][$j]["value"]);
                        $tab[$i][$j]["label"]=_CREATION_DATE;
                        $tab[$i][$j]["size"]="11";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=true;
                        $tab[$i][$j]["order"]='creation_date';
                    }
                    if($tab[$i][$j][$value]=="user_id")
                    {
                        $tab[$i][$j]["label"]=_USER_ID;
                        $tab[$i][$j]["size"]="5";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=false;
                        $tab[$i][$j]["order"]='user_id';
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
                        $tab[$i][$j]["show"]=false;
                        $tab[$i][$j]["order"]='lastname';
                    }
                    
                    if($tab[$i][$j][$value]=="email_destinataire")
                    {
                        $tab_dest = explode(',', $tab[$i][$j]['value']);
                        $tab[$i][$j]['value'] = implode(', ', $tab_dest);
                        $tab[$i][$j]["value"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["label"]=_RECIPIENT;
                        $tab[$i][$j]["size"]=$sizeObject;
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=true;
                        $tab[$i][$j]["order"]='email_destinataire';
                    }
                    if($tab[$i][$j][$value]=="email_object")
                    {
                        $tab[$i][$j]["value"] = addslashes($tab[$i][$j]["value"]);
                        $tab[$i][$j]["label"]=_EMAIL_OBJECT;
                        $tab[$i][$j]["size"]=$sizeObject;
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=false;
                        $tab[$i][$j]["order"]='email_object';
                    }
                    if($tab[$i][$j][$value]=="email_object_short")
                    {
                        $tab[$i][$j]["value"] = $request->cut_string( $request->show_string($tab[$i][$j]["value"]), $cutString);
                        $tab[$i][$j]["label"]=_EMAIL_OBJECT;
                        $tab[$i][$j]["size"]=$sizeObject;
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=true;
                        $tab[$i][$j]["order"]='email_object_short';
                    }
                    if($tab[$i][$j][$value]=="status_label")
                    {
                        $tab[$i][$j]["value"] =  addslashes($_SESSION['sendmail']['status'][$tab[$i][$j]["value"]]['label']);
                        $tab[$i][$j]["label"]=_STATUS;
                        $tab[$i][$j]["size"]="1";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=false;
                        $tab[$i][$j]["order"]='status_label';
                    }
                    if($tab[$i][$j][$value]=="status_img")
                    {
                        $tab[$i][$j]["value"] = '<img src="'
                            .$_SESSION['config']['businessappurl'].'static.php?module=sendmail&filename='
                            .$_SESSION['sendmail']['status'][$tab[$i][$j]["value"]]['img'].'" title="'
                            .$_SESSION['sendmail']['status'][$tab[$i][$j]["value"]]['label'].'" width="20" height="20" />';
                        $tab[$i][$j]["label"]=_STATUS;
                        $tab[$i][$j]["size"]="1";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=true;
                        $tab[$i][$j]["order"]='status_img';
                    }
                    if($tab[$i][$j][$value]=="mail")
                    {
                        $tab[$i][$j]["value"] = $request->show_string($tab[$i][$j]["value"]) ;
                        $tab[$i][$j]["label"]=_SENDER;
                        $tab[$i][$j]["size"]=$sizeUser;
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=false;
                        $tab[$i][$j]["order"]='mail';
                    }
                    if($tab[$i][$j][$value]=="sender_email")
                    {

                        $tab[$i][$j]["value"] = $sendmail_tools->explodeSenderEmail($tab[$i][$j]["value"]);

                        $tab[$i][$j]["label"]=_SENDER;
                        $tab[$i][$j]["size"]="20";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=true;
                        $tab[$i][$j]["order"]='sender_email';
                    }
                    if($tab[$i][$j][$value]=="id")
                    {
                        $tab[$i][$j]["value"] = ($sendmail_tools->haveJoinedFiles($tab[$i][$j]["value"]))? 
                            '<i class="fa fa-paperclip fa-2x" title="'. _JOINED_FILES.'"></i>' : 
                                '';
                        $tab[$i][$j]["label"]=false;
                        $tab[$i][$j]["size"]="1";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=true;
                        $tab[$i][$j]["order"]=false;
                    }
                    if($tab[$i][$j][$value]=="email_status")
                    {
                        $tab[$i][$j]["label"]=_STATUS;
                        $tab[$i][$j]["size"]="1";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=false;
                        $tab[$i][$j]["order"]='email_status';
                    }
                }
            }
        }
        
        //List
        $listKey = 'email_id';                                                              //Clé de la liste
        $paramsTab = array();                                                               //Initialiser le tableau de paramètres
        $paramsTab['bool_sortColumn'] = true;                                               //Affichage Tri
        $paramsTab['pageTitle'] ='';                                                        //Titre de la page
        $paramsTab['bool_bigPageTitle'] = false;                                            //Affichage du titre en grand
        $paramsTab['urlParameters'] = 'identifier='.$identifier
                ."&origin=".$origin.'&display=true'.$parameters;                            //Parametres d'url supplementaires
        $paramsTab['filters'] = array();                                                     //Filtres    
        $paramsTab['listHeight'] = '100%';                                                  //Hauteur de la liste
        // $paramsTab['bool_showSmallToolbar'] = true;                                         //Mini barre d'outils
        // $paramsTab['linesToShow'] = 15;                                                     //Nombre de ligne a afficher
        $paramsTab['listCss'] = $css;                                                       //CSS
        $paramsTab['tools'] = array();                                                      //Icones dans la barre d'outils
          
        $add = array(
                "script"        =>  "showEmailForm('".$_SESSION['config']['businessappurl']  
                                        . "index.php?display=true&module=sendmail&page=sendmail_ajax_content"
                                        . "&mode=add&identifier=".$identifier."&origin=".$origin
                                        . $parameters."')",
                "icon"          =>  'envelope',
                "tooltip"       =>  _NEW_EMAIL,
                "alwaysVisible" =>  true
                );
        array_push($paramsTab['tools'],$add);   
        
        //Action icons array
        $paramsTab['actionIcons'] = array();      
        $read = array(
        "script"        => "showEmailForm('".$_SESSION['config']['businessappurl']
                                    ."index.php?display=true&module=sendmail&page=sendmail_ajax_content"
                                    ."&mode=read&id=@@email_id@@&identifier=".$identifier."&origin=".$origin
                                    . $parameters."');",
            "icon"      =>  'eye',
            "tooltip"   =>  _READ
        );
        array_push($paramsTab['actionIcons'], $read);  
        $update = array(
            "script"        => "showEmailForm('".$_SESSION['config']['businessappurl']
                                    ."index.php?display=true&module=sendmail&page=sendmail_ajax_content"
                                    ."&mode=up&id=@@email_id@@&identifier=".$identifier."&origin=".$origin
                                    . $parameters."');",
            "class"         =>  "change",
            "tooltip"       =>  _UPDATE,
            "disabledRules" => "@@user_id@@ != '".$_SESSION['user']['UserId']."' || @@email_status@@ == 'I'"
        );
        array_push($paramsTab['actionIcons'], $update);  
        $transfer = array(
            "script"        => "showEmailForm('".$_SESSION['config']['businessappurl']
                                    ."index.php?display=true&module=sendmail&page=sendmail_ajax_content"
                                    ."&mode=transfer&id=@@email_id@@&identifier=".$identifier."&origin=".$origin
                                    . $parameters."');",
            "icon"      =>  "mail-forward",
            "tooltip"       =>  _TRANSFER_EMAIL,
            "disabledRules" => "@@user_id@@ != '".$_SESSION['user']['UserId']."' || @@email_status@@ != 'S'"
        );
        array_push($paramsTab['actionIcons'], $transfer);     
         
        //Output
        $status = 0;
        $content = $list->showList($tab, $paramsTab, $listKey);
        // $debug = $list->debug();

    echo "{status : " . $status . ", content : '" . addslashes($debug.$content) . "', error : '" . addslashes($error) . "'}";
}
