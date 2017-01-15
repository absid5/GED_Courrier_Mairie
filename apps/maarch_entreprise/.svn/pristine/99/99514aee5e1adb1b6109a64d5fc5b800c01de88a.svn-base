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
* List Show Class
*
*  Contains all the function to manage and show list
*
* @package  Maarch
* @version 2.1
* @since 10/2005
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
* @author  Loïc Vinet  <dev@maarch.org>
* @author  Laurent Giovannoni  <dev@maarch.org>
*
*/

class list_show_Abstract extends functions
{
    /**
    * Show the document list in result of the search
    *
    * @param    array       $listarr
    * @param    integer     $nb_total total number of documents
    * @param    string      $title
    * @param    string      $what search expression
    * @param    string      $name "search" by default, the calling page
    * @param    string      $key the key seach for the form
    * @param    string      $detail_destination the link to detail page
    * @param    boolean     $bool_view_document boolean to view document or not
    * @param    boolean     $bool_radio_form boolean to add radio to select row
    * @param    string      $method method of the select form
    * @param    string      $action action of the select form
    * @param    string      $button_label label(session var) of the button of the select form
    * @param    boolean     $bool_detail boolean to show the detail page link or not
    * @param    boolean     $bool_order boolean to show the order icons or not
    * @param    boolean     $bool_frame true if calling by frame
    * @param    boolean     $bool_export true if we activate the list export (obsolete => to delete)
    * @param    boolean     $show_close true : the close window button is showed
    * @param    boolean     $show_big_title true : the title is displayed in the title container
    * @param    boolean     $show_full_list true : the list takes all the screen, otherwise it is addforms2 class
    * @param    boolean     $bool_check_form   true : add checkbox to select row
    * @param    string  $res_link  obsolete (to delete)
    * @param    string  $module  module name if the function is called in a module
    * @param    boolean     $bool_show_listletters  true : show list letters, search on the elements of the list possible
    * @param    string  $all_sentence  string  : all item
    * @param    string  $whatname  name of the element to search
    * @param    string  $used_css  css used in the list
    * @param    string  $comp_link  url link complement
    * @param    string  $link_in_line
    * @param    string  $bool_show_actions_list  true : shows the possible actions of the list on a combo list
    * @param    array   $actions  list of the elements of the actions combo list
    * @param    string  $hidden_fields  hidden fields in the form
    */
    public function list_doc(
    $result,
    $nb_total,
    $title,
    $what,
    $name = "search",
    $key,
    $detail_destination,
    $bool_view_document,
    $bool_radio_form,
    $method,
    $action,
    $button_label,
    $bool_detail,
    $bool_order,
    $bool_frame= false,
    $bool_export= false,
    $show_close = FALSE,
    $show_big_title = true,
    $show_full_list = true,
    $bool_check_form = false,
    $res_link = '',
    $module='',
    $bool_show_listletters = false,
    $all_sentence = '',
    $whatname = '',
    $used_css = 'listing spec',
    $comp_link = "",
    $link_in_line = false,
    $bool_show_actions_list = false,
    $actions = array(),
    $hidden_fields = '',
    $actions_json= '{}',
    $do_action = false,
    $id_action = '',
    $open_details_popup = true,
    $do_actions_arr = array(),
    $template = false,
    $template_list = array(),
    $actual_template= '',
    $mode_string = false,
    $hide_standard_list = false
    )
    {
        //echo $actual_template;exit;
        if ($template && $actual_template <> '') {
            $str = '';
            $str .= '<div style="text-align: right;"><input type="button" onclick="window.open(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&page=export\', \'_blank\');" class="button" name="submit" value="'.  _EXPORT_LIST.'" /></div>';
            require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_list_show_with_template.php');
            $list_temp = new list_show_with_template();

            $str .= $list_temp->list_doc_by_template($result, $nb_total, $title,$what,$name,$key,$detail_destination,$bool_view_document,$bool_radio_form,$method,$action,
            $button_label, $bool_detail, $bool_order, $bool_frame,$bool_export, $show_close, $show_big_title,
            $show_full_list, $bool_check_form, $res_link, $module, $bool_show_listletters, $all_sentence,
            $whatname, $used_css , $comp_link, $link_in_line, $bool_show_actions_list , $actions,
            $hidden_fields, $actions_json, $do_action, $id_action , $open_details_popup, $do_actions_arr, $template, $template_list, $actual_template, true, $hide_standard_list);
            $str .= '<div style="text-align: right;"><input type="button" onclick="window.open(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&page=export\', \'_blank\');" class="button" name="submit" value="'.  _EXPORT_LIST.'" /></div>';
            if($mode_string)
            {
                return $str;
            }
            else
            {
                echo $str;
            }

        }
        else
        {
            $str = '';
            if ($bool_export) {
                $str .= '<div style="text-align: right;"><input type="button" onclick="window.open(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&page=export\', \'_blank\');" class="button" name="submit" value="'.  _EXPORT_LIST.'" /></div>';
            }
            require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_list_show_with_template.php');
            //show the document list in result of the search
            $page_list1 = "";
            $page_list2 = "";
            $link="";
            $str .= '';
            //$listvalue = array();
            $listcolumn = array();
            $listshow = array();
            $listformat = array();
            $ordercol = array();
            if(isset($_REQUEST['start']) && $_REQUEST['start'] > $nb_total)
            {
                $_REQUEST['start'] = 0;
            }
            // put in tab the different label of the column
            for ($j=0;$j<count($result[0]);$j++)
            {
                if(isset($result[0][$j]["label"]))
                {
                    array_push($listcolumn, $result[0][$j]["label"]);
                }
                else
                {
                     array_push($listcolumn,'');
                }
                if(isset($result[0][$j]["show"]))
                {
                    array_push($listshow,$result[0][$j]["show"]);
                }
                else
                {
                    array_push($listshow,false);
                }
                if(isset($result[0][$j]["order"]))
                {
                    array_push($ordercol,$result[0][$j]["order"]);
                }
                else
                {
                     array_push($ordercol,'');
                }
            }

            $func = new functions();

            if($bool_frame)
            {
                $link = $_SESSION['config']['businessappurl'].'index.php?display=true&amp;page='.$name.'&amp;search='.$what;
                //$link = $name.".php?search=".$what;
            }
            else
            {
                $link = $_SESSION['config']['businessappurl']."index.php?page=".$name."&amp;search=".$what;
            }
            if(isset($_SESSION['where']))
            {
                for($i=0;$i<count($_SESSION['where']);$i++)
                {
                    $link .= "&amp;where[]=".$_SESSION['where'][$i];
                }
            }
            if(!empty($module))
            {
                $link .= "&amp;module=".$module;
            }
            if(isset($_REQUEST['what']))
            {
                $link .= "&amp;what=".strip_tags($_REQUEST['what']);
            }
            if(isset($_REQUEST['start']) && !empty($_REQUEST['start']))
            {
                $start = strip_tags($_REQUEST['start']);
            }
            else
            {
                $start = 0;
            }

            if(isset($_REQUEST['order']))
            {
                $orderby = strip_tags($_REQUEST['order']);
            }
            else
            {
                //$orderby = 'asc';
                $orderby = '';
            }
            if(!preg_match('/order=/', $comp_link))  //&& $order <> ''
            {
                $link .= "&amp;order=".$orderby;
            }
            if(isset($_REQUEST['order_field']))
            {
                $orderfield = strip_tags($_REQUEST['order_field']);
            }
            else
            {
                $orderfield = '';
            }
            if(!preg_match('/order_field=/', $comp_link) && $orderfield <> '')
            {
                $link .= "&amp;order_field=".$orderfield;
            }
            $link .= $comp_link;

            $nb_show = $_SESSION['config']['nblinetoshow'];
            $nb_pages = ceil($nb_total/$nb_show);
            $end = $start + $nb_show;
            if($end > $nb_total)
            {
                $end = $nb_total;
            }

            if($actual_template <> '')
            {
                $link .= "&amp;template=".$actual_template;
            }
            else
            {
                $link .= "&amp;template=";
            }


            //########################
            $disp_dc = '';
            $core = new core_tools();
            if($core ->is_module_loaded("doc_converter") && $bool_export)
            {
                $_SESSION['doc_convert'] = array();
                require_once("modules".DIRECTORY_SEPARATOR."doc_converter".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
                $doc_converter = new doc_converter();
                $disp_dc = $doc_converter->convert_list($result, true);
            }
            //########################

            $tdeto = '';
            if ($template == true)
            {
                require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_list_show_with_template.php');
                $template_object = new list_show_with_template();
                $tdeto = $template_object->display_template_for_user($template_list, $link);
            }

            // if they are more 1 page we do pagination with 2 forms
            $previous = "";
            $next = "";
            if($nb_pages > 1)
            {
                $next_start = 0;
                //$search_form = "<div class='list_show_page'><form name=\"newpage1\" method=\"get\" >";
                $page_list1 = _GO_TO_PAGE." <select id=\"startpage_list\" name=\"startpage\" onchange=\"window.location.href='".$link."&amp;start='+this.value;\">";
                $lastpage = 0;
                for($i = 0;$i <> $nb_pages; $i++)
                {
                    $page_name = $i + 1;
                    $the_line = $i + 1;
                    if($start == $next_start)
                    {
                        $page_list1 .= "<option value=\"".$next_start."\" selected=\"selected\">".$the_line."</option>";
                    }
                    else
                    {
                        $page_list1 .= "<option value=\"".$next_start."\">".$the_line."</option>";
                    }
                    $next_start = $next_start + $nb_show;
                    $lastpage = $next_start;
                }
                $page_list1 .= "</select>";
                $lastpage = $lastpage - $nb_show;

                if($start > 0)
                {
                    $start_prev = $start - $nb_show;
                    $previous = " <a id=\"previous_items\" href=\"".$link."&amp;start=".$start_prev."\"><i class=\"fa fa-backward\" title=\"" . _PREVIOUS . "\"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
                }

                if($start <> $lastpage)
                {
                    $start_next = $start + $nb_show;
                    $next = " <a id=\"next_items\" href=\"".$link."&amp;start=".$start_next."\"><i class=\"fa fa-forward\" title=\"" . _NEXT . "\"></i></a>";
                }
            }
        $page_list1 = '<div class="block" style="height:30px;vertical" align="center" ><table width="100%" border="0" summary=""><tr><td align="center" width="15%"><b>'.$previous.'</b></td><td align="center" width="15%"><b>'.$next.'</b></td><td width="10px">|</td><td align="center" width="30%">'.$page_list1.'</td><td width="10px">|</td><td width="210px" align="center">'.$disp_dc.'</td><td width="10px">|</td><td align="right">'.$tdeto.'</td></tr></table></b></div>';

            if($show_big_title)
            {
                $str .=  '<h1>';
                if(!empty($picto_path))
                { $str .= '<img src="'.$picto_path.'" alt="" class="title_img" /> ';}
                $str .= $title.'</h1>';
            }
            else
            {
                $str .= '<b>';
                if(!empty($picto_path))
                { $str .= '<img src="'.$picto_path.'" alt="" class="title_img" /> ';}
                $str .= $title.'</b>';
            }
            if($bool_show_listletters)
            {
                $str.=self::listletters($link,$name,$all_sentence,_SEARCH." ".$whatname,_ALPHABETICAL_LIST, false, false, array(), true);
            }
            $str .= $page_list1;
            $str .= ' <div align="center">';
            if($bool_radio_form || $bool_check_form || ($do_action && !empty($id_action)))
            {
                $temp = '<form name="form_select" id="form_select" action="'.$action.'" method="'.$method.'" class="forms';
                if(!$show_full_list)
                {
                    $temp .= " addforms2\" >";
                }
                else
                {
                    $temp .= "\" >";
                }
                $str .= $temp;
                $str .= $hidden_fields;
            }
            if( (($bool_radio_form || $bool_check_form) && count($result) > 0 && $bool_show_actions_list) || ($do_action && !empty($id_action)))
            {

                $str .= '<script type="text/javascript">';
                $str .= 'var arr_actions = '.$actions_json.';';
                $str .= ' var arr_msg_error = {\'confirm_title\' : \''._ACTION_CONFIRM.'\',';
                                            $str .= ' \'validate\' : \''._VALIDATE.'\',';
                                            $str .= ' \'cancel\' : \''._CANCEL.'\',';
                                            $str .= ' \'choose_action\' : \''._CHOOSE_ACTION.'\',';
                                            $str .= ' \'choose_one_doc\' : \''._CHOOSE_ONE_DOC.'\'';
                            $str .= ' };';
                $str .= ' valid_form=function(mode, res_id, id_action)';
                $str .= '{';
                $str .= 'if(!isAlreadyClick){';
                    $str .= ' var val = \'\';';
                    $str .= ' var action_id = \'\';';
                    $str .= ' var table = \'\';';
                    $str .= ' var coll_id = \'\';';
                    $str .= ' var module = \'\';';
                    $str .= ' var thisfrm = document.getElementById(\'form_select\');';
                    $str .= ' if(thisfrm)';
                    $str .= ' {';
                        $str .= ' for(var i=0; i < thisfrm.elements.length; i++)';
                        $str .= ' {';

                            $str .= ' if(thisfrm.elements[i].name = \'field\' && thisfrm.elements[i].checked == true)';
                            $str .= ' {';
                                $str .= ' val += thisfrm.elements[i].value+\',\';';
                            $str .= ' }';
                            $str .= ' else if(thisfrm.elements[i].id == \'action\')';
                        $str .= '   {';
                                $str .= ' action_id = thisfrm.elements[i].options[thisfrm.elements[i].selectedIndex].value;';
                            $str .= ' }';
                            $str .= ' else if(thisfrm.elements[i].id == \'table\')';
                            $str .= ' {';
                                $str .= ' table = thisfrm.elements[i].value;';
                            $str .= ' }';
                            $str .= ' else if(thisfrm.elements[i].id == \'coll_id\')';
                            $str .= ' {';
                                $str .= ' coll_id = thisfrm.elements[i].value;';
                            $str .= ' }';
                            $str .= ' else if(thisfrm.elements[i].id == \'module\')';
                            $str .= ' {';
                                $str .= ' module = thisfrm.elements[i].value;';
                            $str .= ' }';
                        $str .= ' }';
                        $str .= ' val = val.substr(0, val.length -1);';
                        $str .= ' var val_frm = {\'values\' : val,  \'action_id\' : action_id, \'table\' : table, \'coll_id\' : coll_id, \'module\' : module};';
                        $str .= ' if(res_id && res_id != \'\')';
                        $str .= ' {';
                            $str .= ' val_frm[\'values\'] = res_id;';
                        $str .= ' }';
                        $str .= ' if(id_action && id_action != \'\')';
                        $str .= ' {';
                            $str .= ' val_frm[\'action_id\'] = id_action;';
                        $str .= ' }';

                        $str .= ' action_send_first_request(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&page=manage_action&module=core\', mode,  val_frm[\'action_id\'], val_frm[\'values\'], val_frm[\'table\'], val_frm[\'module\'], val_frm[\'coll_id\']);';
                    $str .= ' }';
                    $str .= ' else';
                    $str .= ' {';
                        $str .= ' alert(\'Validation form error\');';
                    $str .= ' }';

                    $str .= 'if (mode == \'mass\')';
                    $str .= '{';
                    $str .= '   isAlreadyClick = false;';
                    $str .= '}';
                    $str .= 'else';
                    $str .= '{';
                    $str .= '   isAlreadyClick = true;';
                    $str .= '}';

                $str .= '}';
                $str .= ' }';
                $str .= ' </script>';
            }
            $str .= ' <table border="0" cellspacing="0" class="'.$used_css.'" summary="">';
                $str .= ' <thead>';
                    $str .= ' <tr>';
                      if($bool_view_document)
                     {
                            $str .= ' <th style="width:3%;">&nbsp;</th>';
                    }
                     if($bool_radio_form ||$bool_check_form)
                     {
                         $str .= ' <th style="width:3%;">&nbsp;</th>';
                        }

                        for($count_column = 0;$count_column < count($listcolumn);$count_column++)
                        {
                            if($listshow[$count_column]==true)
                            {

                            $str .= ' <th style="width:'.$result[0][$count_column]['size'].'%;" valign="'.$result[0][$count_column]['valign'].'"  align="'.$result[0][$count_column]['label_align'].'" ><span>'.$listcolumn[$count_column];


                                if($bool_order)
                                {

                                    $str .= ' <br/><br/> <a class="order_items" href="'.$link.'&amp;start='.$start.'&amp;order=desc&amp;order_field='.$ordercol[$count_column].'" title="'._DESC_SORT.'"><i class="fa fa-angle-down fa-2x" title="'._DESC_SORT.'" ></i> </a> <a class="order_items" href="'.$link.'&amp;start='.$start.'&amp;order=asc&amp;order_field='.$ordercol[$count_column].'" title="'._ASC_SORT.'"> <i class="fa fa-angle-up fa-2x" title="'._ASC_SORT.'" ></i></a>';
                                }
                            $str .= ' </span></th>';

                        }
                            }
                            if($bool_detail)
                            {
                                 $str .= ' <th style="width:4%;" valign="bottom" >&nbsp; </th>';
                            }
                    $str .= '</tr>';
                    $str .= ' </thead>';
                    $str .= ' <tbody>';
            $color = "";

            for($theline = $start; $theline < $end ; $theline++)
            {
                if($color == ' class="col"')
                {
                    $color = '';
                }
                else
                {
                    $color = ' class="col"';
                }


                $str .= ' <tr '.$color.'>';

                if ($bool_radio_form || $bool_check_form) {
                    $str .= ' <td style="width:3%;">';
                    $str .= ' <div align="center">';

                     if ($bool_radio_form) {
                         if (count($do_actions_arr) == 0
                             ||  $do_actions_arr[$theline] == true
                         ) {
                             $str .= '<input type="radio"  class="check" name="field" value="'.$result[$theline][0]['value'].'" class="check" />&nbsp;&nbsp;';
                         } else {
                             $str .= '<i class="fa fa-lock fa-2x" title="'._DOC_LOCKED.'"></i>';
                         }
                     } else if ($bool_check_form) {
                        if (! $bool_show_actions_list ) {
                            $str .= '<input type="checkbox"  class="check" name="field[]" class="check" value="'.$result[$theline][0]['value'].'" />&nbsp;&nbsp;';
                        } else {
                            if (count($do_actions_arr) == 0
                                || $do_actions_arr[$theline] == true
                            ) {
                                $str .= '<input type="checkbox"  class="check" name="field" class="check" value="'.$result[$theline][0]['value'].'" />&nbsp;&nbsp;';
                            } else {
                                $str .= '<i class="fa fa-lock fa-2x" title="'._DOC_LOCKED.'"></i>';
                            }
                        }
                    }
                    $str .= ' </div>';
                    $str .= ' </td>';
                }
                if ($bool_view_document) {
                    $str .= ' <td style="width:3%;">';
                    $str .= ' <div align="center">';
                    if ($bool_view_document) {
                        $str .= '<a href="'.$_SESSION['config']['businessappurl'].'index.php?display=true&amp;dir=indexing_searching&amp;page=view_resource_controler&amp;id='.$result[$theline][0][$key].'" target="_blank" title="'._VIEW_DOC.'">';
                        $str .= ' <i class="fa fa-download fa-2x" title="'._VIEW_DOC.'"></i></a>';
                    }
                    $str .= ' </div>';
                    $str .= ' </td>';
                }

                for($count_column = 0;$count_column < count($listcolumn);$count_column++) {
                    if(isset($result[$theline][$count_column]['show'])
                        && $result[$theline][$count_column]['show'] == true
                    ) {
                        if($do_action && !empty($id_action)
                            && (count($do_actions_arr) == 0
                                || $do_actions_arr[$theline] == true)
                        ) {
                            $str .= ' <td style="width:'
                                 . $result[$theline][$count_column]['size']
                                 . '%;" align="'
                                 . $result[$theline][$count_column]['align']
                                 . '" onclick="valid_form( \'page\', \''
                                 . $result[$theline][0]['value'] . '\', \''
                                 . $id_action . '\');" ';
                            if (isset($result[$theline][$count_column]['css_style'])) {
                                 $str .= $result[$theline][$count_column]['css_style'];
                            }
                            $str .= '>' . functions::show_str(
                                     self::thisword(
                                         $result[$theline][$count_column]['value'],
                                         $what
                                     )
                                 ) . '</td>';
                        }
                            else if($do_action && !empty($id_action) &&  $do_actions_arr[$theline] == false)
                            {
                                $str .= ' <td style="width:'.$result[$theline][$count_column]['size'].'%;" align="'.$result[$theline][$count_column]['align'].'" '.$result[$theline][$count_column]['css_style'].'><em>'.functions::show_str(self::thisword($result[$theline][$count_column]['value'],$what)).'</em></td>';

                            }
                            else if($link_in_line)
                        {
                            $str .= ' <script type="text/javascript">';
                                $str .= ' var window2 = null;';
                                    $str .= ' function openpopup(linkpage)';
                                    $str .= ' {';
                                        $str .= ' if(window2 == null)';
                                        $str .= ' {';
                                            $str .= ' window2=window.open(linkpage);';
                                        $str .= ' }';
                                        $str .= ' else';
                                        $str .= ' {';
                                            $str .= ' window2.close();';
                                            $str .= ' window2 = 0;';
                                            $str .= ' window2 = window.open(linkpage);';
                                        $str .= ' }';
                                    $str .= ' }';
                                $str .= ' </script>';

                                $str .= ' <td style="width:'.$result[$theline][$count_column]['size'].'%;" align="'.$result[$theline][$count_column]['align'].'" onclick="openpopup(\''.$detail_destination.'?id='.$result[$theline][0]['value'].'\');" '.$result[$theline][$count_column]['css_style'].'>'.functions::show_str(self::thisword($result[$theline][$count_column]['value'],$what)).'</td>';
                        }
                        else
                        {
                            $str .= ' <td style="width:'.$result[$theline][$count_column]['size'].'%;" align="'.$result[$theline][$count_column]['align'].'" ';
                            if(isset($result[$theline][$count_column]['css_style']))
                            {
                                $str .= $result[$theline][$count_column]['css_style'];
                            }
                            $str .='>'.functions::show_str(self::thisword($result[$theline][$count_column]['value'],$what)).'</td>';
                        }
                    }
                }
                if($bool_detail)
                {
                    if($bool_frame && $open_details_popup)
                    {
                        $str .= ' <td style="width:4%;"  align="center"><div align="right">';
                                $str .= ' <a href="javascript:window.open(\''.$_SESSION['config']['businessappurl'].'index.php?page='.$detail_destination.'&amp;id='.$result[$theline][0][$key].'\',\'_parent\',\'_parent\');" title="'._DETAILS.'"><i class="fa fa-info-circle fa-2x" title="'._DETAILS.'"></i></a></div>';
                            $str .= ' </td>';
                    }
                    elseif($bool_frame && !$open_details_popup)
                    {
                        $str .= '<td style="width:4%;"  align="center">';
                            $str .= '<a href="#" title="'._DETAILS.'" onclick="javascript:window.top.location=\''.$_SESSION['config']['businessappurl'].'index.php?page='.$detail_destination.'&amp;id='.$result[$theline][0][$key].'\';return false;"><i class="fa fa-info-circle fa-2x" title="'._DETAILS.'"></a>';
                            $str .= ' </td>';
                    }
                    else
                    {
                        $str .= ' <td style="width:4%;"  align="center"><div align="right">
                                <a href="'.$_SESSION['config']['businessappurl'].'index.php?page='.$detail_destination.'&amp;id='.$result[$theline][0][$key].'" title="'._DETAILS.'"><i class="fa fa-info-circle fa-2x" title="'._DETAILS.'"></i></a></div>';
                        $str .= ' </td>';
                    }
                }
                $str .= ' </tr>';
            }
            $str .= '</tbody>';
            $str .= ' </table>';
            $str .= ' <br/>';
            if(($bool_radio_form || $bool_check_form) && count($result) > 0 && !$bool_show_actions_list)
            {
                $str .= ' <p align="center">';
                $str .= ' <input class="button" type="submit" value="'.$button_label.'"  />';
               if($show_close )
                {
                    $str .= ' <input type="button" class="button" name="cancel" value="'._CLOSE_WINDOW.'" onclick="window.top.close();" />';
                }
                $str .= ' </p>';
                $str .= ' </form>';
                $str .= ' <br/>';
            }
            else if(($bool_radio_form || $bool_check_form) && count($result) > 0 && $bool_show_actions_list)
            {
                $str .= ' <p align="center">';
                    $str .= ' <b>'._ACTIONS.' :</b>';
                    $str .= ' <select name="action" id="action">';
                    $str .= ' <option value="">'. _CHOOSE_ACTION.'</option>';
                    for($ind_act = 0; $ind_act < count($actions);$ind_act++)
                    {
                        $str .= ' <option value="'.$actions[$ind_act]['VALUE'].'">'.$actions[$ind_act]['LABEL'].'</option>';
                    }
                    $str .= ' </select>';
                    $str .= ' <input type="button" name="send" id="send" value="'._VALIDATE.'" onclick="valid_form(\'mass\');" class="button" />';
                $str .= ' </p>';
            $str .= ' </form>';
            $str .= ' <br/>';
            }
            elseif($do_action)
            {
                $str .= ' </form>';
            }
            elseif($show_close)
            {
                $str .= ' <input type="button" class="button" name="cancel" value="'._CLOSE_WINDOW.'" onclick="window.top.close();" />';
            }
            if ($bool_export) {
                $str .= '<div style="text-align: right;"><input type="button" onclick="window.open(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&page=export\', \'_blank\');" class="button" name="submit" value="'.  _EXPORT_LIST.'" /></div>';
            }
            if($mode_string)
            {
                return $str;
            }
            else
            {
                echo $str;
            }

        }
    }

    /**
    * Mark with a color background the word you're searching in the detail of the row
    *
    * @param string $words
    * @param string $need
    * @return string $words
    * @return string $size
    */
    protected static function thisword($words,$need, $is_split = FALSE, $size = 70)
    {
        // mark with a color background the word you're searching in the detail of the row
        if(!$is_split || strlen($words) < $size)
        {
            if (strlen($need) > 3)
            {
                $ar_need = explode(" ", $need);

                for($i = 0; $i < count($ar_need); $i++)
                {
                    $save_ar_need = "";
                    $pos = stripos($words, $ar_need[$i]);

                    if($pos !== false)
                    {
                        $save_ar_need = substr($words, $pos, strlen($ar_need[$i]));
                    }

                    $words = preg_replace("/(".$ar_need[$i].")/i","<span class=\"thisword\">".$save_ar_need."</span>",$words);
                }
            }
        }
        else
        {
            $i = 0;
            $newwords = '';
            if(preg_match('/@/', $words))
            {
                $tab_words = preg_split('/@/', $words);
                $newwords = $tab_words[0].'@<br/>'.$tab_words[1];
            }
            else if(!preg_match('/ /', $words))
            {
                while(true)
                {
                    if(strlen(substr($words, $i)) > $size)
                    {
                        $newwords .= '<br/>'.substr($words,$i,  $size);

                        $i = $i + $size ;
                    }
                    else
                    {
                        $newwords .= '<br/>'.substr($words,$i);
                        break;
                    }

                }
            }
            else
            {
                $newwords = $words;
            }
            /*while(true)
            {
                if(strlen(substr($words, $i)) > $size)
                {
                    $newwords .= '<br/>'.substr($words,$i,  $size);

                    $i = $i + $size ;
                }
                else
                {
                    $newwords .= '<br/>'.substr($words,$i);
                    break;
                }

            }*/
            $words =$newwords;
            //$words = $words. '<br/>[...]';
        }

        return $words;
    }

    /* *
    * show the alphabetical list
    *
    * @param string $page the page (users, groups,...)
    * @param string $all_text txt to say all item
    * @param string $button_text text of button
    * @param string $alpha_text text of the alphabetical list
    */
    public static function listletters($link, $page, $all_text, $button_text, $alpha_list_text, $show_searchbox = true, $autoCompletion = false, $autoCompletionArray2 = array(), $whatinput =  'what', $whatListinput = 'whatList')
    {
        $link = preg_replace("/(&(?!amp;))/", "&amp;", $link);
        ?>
        <div id="list_letter">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="forms" summary="">
          <tr>
            <td >
                <strong><?php echo $alpha_list_text;?></strong> :
                <?php  for($i=ord('A'); $i <= ord('Z');$i++)
                {
                    ?>
                    <a  href="<?php echo $link;?>&amp;<?php functions::xecho($whatinput );?>=<?php echo chr($i);?>"><?php echo chr($i);?></a>
                    <?php
                }
                ?>
                - <a href="<?php echo $link;?>&amp;<?php functions::xecho($whatinput );?>="><?php echo $all_text;?></a>
            </td>
            <td  align="right">
            <?php
            if($show_searchbox)
            {
                ?>
                <form action="<?php echo($link);?>" method="post" id="frmletters">
                    <div>
                    <input name="<?php functions::xecho($whatinput );?>" id="<?php functions::xecho($whatinput );?>" type="text" size="15"/>
                    <?php
                    if($autoCompletion)
                    {
                        //preg_replace("/(&(?!amp;))/", "&amp;",$autoCompletionArray2['list_script_url'])
                        ?>
                        <div id="<?php functions::xecho($whatListinput );?>" class="autocomplete"></div>
                        <script type="text/javascript">
                        <?php if ($autoCompletionArray2['searchBoxAutoCompletionUpdate']) { ?>
                                launch_autocompleter_update('<?php echo $autoCompletionArray2['list_script_url'];?>', '<?php functions::xecho($whatinput );?>', '<?php functions::xecho($whatListinput );?>', '<?php functions::xecho($autoCompletionArray2['number_to_begin']);?>', 'selectedObject')
                            <?php } else { ?>
                                initList('<?php functions::xecho($whatinput );?>', '<?php functions::xecho($whatListinput );?>', '<?php echo $autoCompletionArray2['list_script_url'];?>', 'what', '<?php functions::xecho($autoCompletionArray2['number_to_begin']);?>');
                            <?php } ?>
                        </script>
                        <input type="hidden" name="selectedObject" id="selectedObject" />
                        <?php
                    }
                    ?>
                    <input name="Submit" class="button" type="submit" value="<?php functions::xecho($button_text);?>"/>
                </div>
                </form>
                <?php
            }
            else
            {
                echo "&nbsp;&nbsp;";
            }
            ?>
            </td>
          </tr>
        </table>
        </div>
        <?php
    }

    /* *
    * show an administration list
    * @param array $result result of a request
    * @param integer $nb_total total number of items
    * @param string $title list title
    * @param string $expr search expression
    * @param string $name  the calling page
    * @param string $key the key seach for the form
    * @param boolean $bool_order boolean to show the order icons or not
    * @param boolean $page_name_up modification page
    * @param boolean $page_name_val validation page
    * @param boolean $page_name_ban suspend page
    * @param boolean $page_name_del delete page
    * @param boolean $page_name_add  page to add a new item
    * @param boolean $label_add
    * @param boolean $bool_history FALSE by default, is the list an hisory list ?
    * @param boolean $bool_simple_list FALSE by default, shows or not the radio or checkbox
    * @param string $all_sentence
    * @param string $whatname
    * @param string $picto_path
    * @param string $is_part_of_module
    * @param string $show_big_title
    * @param string $flag_not_admin
    */
    public static function admin_list($result, $nb_total, $title, $expr, $name, $admin, $key, $bool_order, $page_name_up, $page_name_val, $page_name_ban, $page_name_del, $page_name_add, $label_add, $bool_history = FALSE, $bool_simple_list = FALSE, $all_sentence='', $whatname='', $picto_path ='', $is_part_of_module = FALSE, $show_big_title = true, $flag_not_admin = false, $show_listletters = true, $what ="", $autoCompletion = false, $autoCompletionArray = array(), $is_in_apps_dir = false, $is_del_page_popup = false, $whatinput = 'what', $whatListinput = 'whatList')
    {
        // show the document list in result of the search
        $page_list1 = "";
        $page_list2 = "";
        $link="";
        //$listvalue = array();
        $listcolumn = array();
        $listshow = array();
        $ordercol = array();
        for ($i=0;$i<1;$i++)
        {
            if(isset($result[$i]))
            {
                for ($j=0;$j<count($result[$i]);$j++)
                {
                    if(isset($result[$i][$j]['label']))
                    {
                        array_push($listcolumn,$result[$i][$j]['label']);
                    }
                    else
                    {
                        array_push($listcolumn,'');
                    }
                    if(isset($listshow,$result[$i][$j]['show']))
                    {
                        array_push($listshow,$result[$i][$j]['show']);
                    }
                    else
                    {
                        array_push($listshow,true);
                    }
                    if(isset($result[$i][$j]["order"]))
                    {
                        array_push($ordercol,$result[$i][$j]["order"]);
                    }
                    else
                    {
                        array_push($ordercol,'');
                    }
                }
            }
        }


        $func = new functions();
        $param_comp = '';
        if(isset($_REQUEST['start']) && !empty($_REQUEST['start']))
        {
            $start = strip_tags($_REQUEST['start']);
        }
        else
        {
            $start = 0;
        }
        $param_comp .= "&amp;start=".$start;
        if($name == "structures" || $name == "subfolders" || $name == "types"  || $name == "contact_types" || $name == "contact_purposes" || $name == "contacts_v2" || $name == "contacts_v2_up" || $name == "contact_addresses_list")
        {
            $link = $_SESSION['config']['businessappurl']."index.php?page=".$name;
        }
        else
        {
            if($is_part_of_module == false && $is_in_apps_dir == false)
            {
                $link = $_SESSION['config']['businessappurl']."index.php?page=".$name."&amp;admin=".$admin;
            }
            elseif($is_in_apps_dir)
            {

                $link = $_SESSION['config']['businessappurl']."index.php?page=".$name."&amp;dir=".$admin;
            }
            else
            {
                $link = $_SESSION['config']['businessappurl']."index.php?page=".$name."&amp;module=".$admin;
            }
        }

        if(isset($_REQUEST['order']))
        {
            $orderby = strip_tags($_REQUEST['order']);
        }
        else
        {
            $orderby = 'asc';
        }
        $param_comp .= "&amp;order=".$orderby;
        $link .= "&amp;order=".$orderby;

        if(isset($_REQUEST['order_field']))
        {
            $orderfield = strip_tags($_REQUEST['order_field']);
        }
        else
        {
            $orderfield = '';
        }
        $link .= "&amp;order_field=".$orderfield;
        $param_comp .= "&amp;order_field=".$orderfield;
        if(isset($_REQUEST['what']))
        {
            $get_what = strip_tags($_REQUEST['what']);
            $get_what = str_replace('"', '%22', $get_what);
        }
        else
        {
            $get_what = '';
        }
        $link .= "&amp;what=".$get_what;
        $param_comp .= "&amp;what=".$what;
        // define the defaults values
        $nb_show = $_SESSION['config']['nblinetoshow'];
        $nb_pages = ceil($nb_total/$nb_show);
        $end = $start + $nb_show;
        if($end > $nb_total)
        {
            $end = $nb_total;
        }

        if(!empty($what))
        {
            $link .= "&amp;what=".$what;
        }

        $link = preg_replace("/(&(?!amp;))/", "&amp;", $link);
        // if they are more 1 page we do pagination with 2 forms
        if($nb_pages > 1)
        {
            $next_start = 0;

            $page_list1 = '<form  id="newpage1" method="get" action="'.urldecode($link).'" >
            <p>
                <label for="startpage">'._GO_TO_PAGE.'</label>
                <select name="startpage" id="startpage" class="small" onchange="window.location.href=\''.$link.'&amp;start=\'+$(\'newpage1\').startpage.value;">';

            $lastpage = 0;

            for($i = 0;$i <> $nb_pages; $i++)
            {
                $page_name = $i + 1;

                $the_line = $i + 1;
                if($start == $next_start)
                {
                    $page_list1 .= "<option value=\"".$next_start."\" selected=\"selected\">".$the_line."</option>";
                    $page_list2 .= "<option value=\"".$next_start."\"  selected=\"selected\">".$the_line."</option>";
                }
                else
                {
                    $page_list1 .= "<option value=\"".$next_start."\">".$the_line."</option>";
                    $page_list2 .= "<option value=\"".$next_start."\">".$the_line."</option>";
                }

                $next_start = $next_start + $nb_show;
                $lastpage = $next_start;
            }

            $lastpage = $lastpage - $nb_show;

            $previous = "";
            $next = "";
                $page_list1 = $page_list1."</select>";
            if($start > 0)
            {
                $start_prev = $start - $nb_show;
                $previous = "<a href=\"".$link."&amp;start=".$start_prev."\"><i class=\"fa fa-backward\" title=\"" . _PREVIOUS . "\"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }

            if($start <> $lastpage)
            {
                $start_next = $start + $nb_show;
                $next = " <a href=\"".$link."&amp;start=".$start_next."\"><i class=\"fa fa-forward\" title=\"" . _NEXT . "\"></i></a>";
            }

            $page_list1 .= $previous." ".$next.'</p></form>';
        }
        if($show_big_title)
        {
            echo '<h1>';
            if(!empty($picto_path))
            { echo '<i class="fa fa-'.$picto_path.' fa-2x"></i> ';}
            echo $title.'</h1>';
            ?><div id="inner_content" class="clearfix"><?php
        }
        else
        {
            echo '<h2>';
            if(!empty($picto_path))
            { echo '<i class="fa fa-'.$picto_path.' fa-2x"></i> ';}
            echo $title.'</h2>';
                echo ' <div class="text-align">';
        }

        if(!$bool_history)
        {
            if($show_listletters)
            {
                if(!$autoCompletion)
                {
                    self::listletters($link, $name, $all_sentence, _SEARCH." ".$whatname, _ALPHABETICAL_LIST);
                }
                else
                {
                    self::listletters($link, $name, $all_sentence, _SEARCH." ".$whatname, _ALPHABETICAL_LIST, true, $autoCompletion, $autoCompletionArray, $whatinput, $whatListinput);
                }
            }
        }
        echo $page_list1;
        ?>
        <table width="100%" border="0" cellspacing="0" class="listing spec" summary="">
            <thead>
                <tr>
            <?php

                for($count_column = 0;$count_column < count($listcolumn);$count_column++)
                {
                    if($listshow[$count_column]==true)
                    {
                    ?>
                        <th <?php if(isset($result[0][$count_column]['size'])){?>style="width:<?php functions::xecho($result[0][$count_column]['size']);?>%;"<?php } if(isset($result[0][$count_column]['valign'])){?> valign="<?php functions::xecho($result[0][$count_column]['valign']);?>"<?php } if(isset($result[0][$count_column]['label_align'])){?> align="<?php functions::xecho($result[0][$count_column]['label_align']);?>"<?php } ?>
                         ><span>&nbsp;<?php echo $listcolumn[$count_column]?>
                         <?php  if($bool_order && !empty($ordercol[$count_column]))
                        { ?> <br/> <a href="<?php echo($link);?>&amp;start=<?php functions::xecho($start);?>&amp;order=desc&amp;order_field=<?php functions::xecho($result['0'][$count_column]['column']);?>" title="<?php echo _DESC_SORT;?>"><i class="fa fa-angle-down fa-2x" title="<?php echo _DESC_SORT;?>" ></i> </a> <a href="<?php echo($link);?>&amp;start=<?php functions::xecho($start);?>&amp;order=asc&amp;order_field=<?php functions::xecho($result['0'][$count_column]['column']);?>" title="<?php echo _ASC_SORT;?>"> <i class="fa fa-angle-up fa-2x" title="<?php echo _ASC_SORT;?>" ></i></a> <?php  }

                        ?></span></th>
                        <?php
                    }
                }
                ?>
                </tr>
            </thead>

            <?php
            if(!$bool_history && !$bool_simple_list)
            {
                if(!$is_part_of_module && !$flag_not_admin && !$is_in_apps_dir)
                {
                    $path_add = $_SESSION['config']['businessappurl']."index.php?page=".$page_name_add."&amp;admin=".$admin;
                }
                elseif($flag_not_admin && !$is_in_apps_dir)
                {
                    $path_add = $_SESSION['config']['businessappurl']."index.php?page=".$page_name_add;
                }
                elseif($is_in_apps_dir)
                {
                    $path_add = $_SESSION['config']['businessappurl']."index.php?page=".$page_name_add."&amp;dir=".$admin;
                }
                else
                {
                    $path_add = $_SESSION['config']['businessappurl']."index.php?page=".$page_name_add."&amp;module=".$admin;
                }
                 if(!empty($page_name_add))
                {
                    $path_add = preg_replace("/(&(?!amp;))/", "&amp;",$path_add);
                ?>
                <tfoot>
                    <tr>
                        <td colspan="9" class="price">
                            <!--<span class="add clearfix">
                                <a href="<?php echo($path_add.$param_comp);?>">
                                    <span>
                                        <?php functions::xecho($label_add);?>
                                    </span>
                                </a>
                            </span>-->
                            <a href="<?php echo($path_add.$param_comp);?>">
                                <i class="fa fa-plus-square fa-3x" title="<?php functions::xecho($label_add);?>"></i>
                            </a>
                        </td>
                    </tr>
                </tfoot>
                <?php
                }
            }
            elseif($bool_simple_list)
            {
                $width = "450";
                if($name == 'structures'){
                    $height = "570";
                    $width = "600";
                }
                elseif($page_name_up == "contrat_up"  || $name == 'contacts_v2')
                {
                    $height = "750";
                }
                elseif($name == 'types')
                {
                    $height = "650";
                }
                else
                {
                    $height = "250";
                }
                if($admin == "types" || $admin == "structures" || $admin == "subfolders" || $admin == "contact_types" || $admin == "contact_purposes" || $admin == "contacts_v2" || $admin == "contact_addresses" || $admin == "contact_addresses_list")
                {
                    //$path_root = $_SESSION['config']['businessappurl']."admin/architecture/".$admin."/";
                    $path_root = $_SESSION['config']['businessappurl']."index.php?display=true";
                }
                else
                {
                    if(!$is_part_of_module)
                    {
                        //$path_root = $_SESSION['config']['businessappurl']."admin/".$admin."/";
                        $path_root = $_SESSION['config']['businessappurl']."index.php?display=true&amp;admin=".$admin;
                    }
                    else
                    {
                        //$path_root = $_SESSION['urltomodules'].$admin."/";
                        $path_root = $_SESSION['config']['businessappurl']."index.php?display=true&amp;module=".$admin;
                    }
                }
                if(!empty($page_name_add))
                {
                ?>
                    <tfoot>
                         <tr>
                            <td colspan="<?php  if($name <> 'types'){ echo'7';} else{ echo '5'; }
                            ?>" class="price">
                                <!--<span class="add clearfix">
                                    <a href="javascript://" onclick="window.open('<?php 
                                        echo $path_root; 
                                        if($name <> 'types' && $name <> 'contacts_v2' && $name <> 'contact_addresses') { 
                                            echo '&amp;page='.$page_name_up;
                                            ?>&amp;mode=add<?php
                                        } else { 
                                            echo  '&amp;page='.$page_name_add; 
                                        }?>','add','height=250, width=500, resizable=yes, scrollbars=yes');" >
                                        <span><?php functions::xecho($label_add);?></span>
                                    </a>
                                </span>-->
                                <a href="javascript://" onclick="window.open('<?php 
                                    echo $path_root; 
                                    if($name <> 'types' && $name <> 'contacts_v2' && $name <> 'contact_addresses') { 
                                        echo '&amp;page='.$page_name_up;
                                        ?>&amp;mode=add<?php
                                    } else { 
                                        echo  '&amp;page='.$page_name_add; 
                                    }?>','add','height=<?php functions::xecho($height);?>, width=<?php functions::xecho($width);?>, resizable=yes, scrollbars=yes');" >
                                    <i class="fa fa-plus-square fa-3x" title="<?php functions::xecho($label_add);?>"></i>
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                    <?php
                }
            }
        ?>
        <tbody>
        <?php
        $color = "";
        for($theline = $start; $theline < $end ; $theline++)
        {
            // background color
            if($color == ' class="col"')
            {
                $color = '';
            }
            else
            {
                $color = ' class="col"';
            }
            ?>
            <tr <?php echo $color;?>>
                    <?php
                    $enabled = "";
                    if(isset($page_name) && $page_name == "users")
                    {
                        $complete_name = "";
                    }
                    else
                    {
                        $admin_id = "";
                    }
                    $can_modify = true;
                    $can_delete = true;
                    for($count_column = 0;$count_column < count($listcolumn);$count_column++)
                    {
                        if(isset($result[$theline][$count_column]['show']) && $result[$theline][$count_column]['show']==true)
                        {
                    ?>
                           <td style="width:<?php functions::xecho($result[$theline][$count_column]['size']);?>%;" align="<?php functions::xecho($result[$theline][$count_column]['align']);?>">

                            <?php
                                if($result[$theline][$count_column]['column'] == "enabled")
                                {
                                    $enabled = $result[$theline][$count_column]['enabled'];

                                    if($result[$theline][$count_column]['enabled'] == "N")
                                     {
                                    ?>
                                    <i class="fa fa-pause fa-2x" title="<?php echo _NOT_ENABLED;?>"></i>
                                    <?php
                                     }
                                    elseif($result[$theline][$count_column]['enabled'] == "Y")
                                    {
                                    ?>
                                    <i class="fa fa-check fa-2x" title="<?php echo _ENABLED;?>"></i>
                                    <?php
                                    }
                                }
                                else
                                {
                                    if(isset($page_name) && $page_name == "users")
                                    {
                                        if($result[$theline][$count_column]['column'] == "lastname" || $result[$theline][$count_column]['column'] == "firstname" )
                                        {
                                            $complete_name .= " ".$result[$theline][$count_column]['value'];
                                        }
                                    }
                                    else
                                    {
                                        $admin_id = $result[$theline][0][$key];
                                    }
                                    if($name == 'types' || $name == "groups" || $name== 'contrat'|| $name== 'sous_dossiers' || $name== 'hist')
                                    {
                                        echo $result[$theline][$count_column]['value'];
                                    }
                                    else
                                    {
                                        echo functions::show_str(self::thisword($result[$theline][$count_column]['value'],$expr, TRUE));
                                    }

                                }
                                ?>
                                </td>
                    <?php
                        }
                        elseif(isset($result[$theline][$count_column]['can_modify']) && $result[$theline][$count_column]['can_modify']=='false')
                        {
                            $can_modify = false;
                        }
                        elseif(
                            isset($result[$theline][$count_column]['can_delete']) 
                            && ($result[$theline][$count_column]['can_delete']=='false' 
                            || $result[$theline][$count_column]['can_delete'] == 'N'))
                        {
                            $can_delete = false;
                        }
                    }
                    if(!$bool_history && !$bool_simple_list)
                    {

                        $valeur = str_replace("&#039;", "'", $result[$theline][0][$key]);
                        $valeur = str_replace('&','%26', $valeur);
                        $valeur = str_replace('#','%23',$valeur);

                        if(!$is_part_of_module && !$flag_not_admin && !$is_in_apps_dir)
                        {
                            $path_up = $_SESSION['config']['businessappurl']."index.php?page=".$page_name_up."&amp;admin=".$admin."&amp;id=".$valeur;
                        }
                        elseif($flag_not_admin && !$is_in_apps_dir)
                        {
                            $path_up = $_SESSION['config']['businessappurl']."index.php?page=".$page_name_up."&amp;id=".$valeur;
                        }

                        elseif($is_in_apps_dir)
                        {
                            $path_up = $_SESSION['config']['businessappurl']."index.php?page=".$page_name_up."&amp;dir=".$admin."&amp;id=".$valeur;
                        }
                        else
                        {
                            $path_up = $_SESSION['config']['businessappurl']."index.php?page=".$page_name_up."&amp;module=".$admin."&amp;id=".$valeur;
                        }
                    ?>

                    <td class="action">
                        <?php
                        if( $can_modify == false)
                        {
                            echo "&nbsp;";
                        }
                        else
                        {
                            $path_up = preg_replace("/(&(?!amp;))/", "&amp;", $path_up);
                        ?>
                        <a href="<?php echo($path_up.$param_comp);?>" class="actionList"><i class="fa fa-edit fa-2x" title="<?php echo _MODIFY;?>"></i><?php //echo _MODIFY;?></a>
                        <?php

                        }?>
                    </td>
                    <?php  if($name<> 'types')
                    {?>
                    <td class="action">
                    <?php
                            if($enabled == "N"   )
                            {
                                if(!$is_part_of_module)
                                 {
                                    //$path_auth = $_SESSION['config']['businessappurl'].'admin/'.$admin.'/'.$page_name_val.".php?id=".$result[$theline][0][$key];
                                    if (strpos($page_name_val, "contacts_v2_status") !== false  || strpos($page_name_ban, "contact_addresses_status") !== false) {
                                        $path_auth = $_SESSION['config']['businessappurl'].'index.php?page='.$page_name_val."&amp;id=".$result[$theline][0][$key];
                                    } else {
                                        $path_auth = $_SESSION['config']['businessappurl'].'index.php?display=true&amp;admin='.$admin.'&amp;page='.$page_name_val."&amp;id=".$result[$theline][0][$key];
                                    }
                                 }
                                 else
                                 {
                                    //$path_auth = $_SESSION['urltomodules'].$admin.'/'.$page_name_val.".php?id=".$result[$theline][0][$key];
                                    $path_auth = $_SESSION['config']['businessappurl'].'index.php?display=true&amp;module='.$admin.'&amp;page='.$page_name_val."&amp;id=".$result[$theline][0][$key];
                                 }
                                if($name == "users" &&  $result[$theline][0][$key] == "superadmin")
                                {
                                    echo "&nbsp;";
                                }
                                else
                                {
                                    $path_auth = preg_replace("/(&(?!amp;))/", "&amp;", $path_auth);
                            ?>
                                <a href="<?php echo($path_auth.$param_comp);?>" class="actionList" onclick="return(confirm('<?php echo _REALLY_AUTHORIZE." "; if(isset($page_name) && $page_name == "users"){ echo $complete_name;}
                                 else { echo $admin_id; } ?> ?'));"><i class="fa fa-check fa-2x" title="<?php echo _AUTHORIZE;?>"></i></a>
                                <?php
                                }
                            }
                            else
                            {
                                if(!empty($page_name_ban))
                                {
                                 if(!$is_part_of_module)
                                 {
                                    if (strpos($page_name_ban, "contacts_v2_status") !== false || strpos($page_name_ban, "contact_addresses_status") !== false) {
                                        $path_ban = $_SESSION['config']['businessappurl'].'index.php?page='.$page_name_ban."&amp;id=".$result[$theline][0][$key];
                                    } else {
                                        // $path_ban = $_SESSION['config']['businessappurl'].'admin/'.$admin.'/'.$page_name_ban.".php?id=".$result[$theline][0][$key];
                                        $path_ban = $_SESSION['config']['businessappurl'].'index.php?display=true&amp;admin='.$admin.'&amp;page='.$page_name_ban."&id=".$result[$theline][0][$key];
                                    }
                                 }
                                 else
                                 {
                                    //$path_ban = $_SESSION['urltomodules'].$admin.'/'.$page_name_ban.".php?id=".$result[$theline][0][$key];
                                    $path_ban = $_SESSION['config']['businessappurl'].'index.php?display=true&amp;module='.$admin.'&amp;page='.$page_name_ban."&amp;id=".$result[$theline][0][$key];
                                 }
                                if($name == "users" &&  $result[$theline][0][$key] == "superadmin")
                                {
                                    echo "&nbsp;";
                                }
                                else
                                {
                                    $path_ban = preg_replace("/(&(?!amp;))/", "&amp;", $path_ban);
                                    $item_mode = null;
                                    if($_REQUEST['page']=='users_management_controler'){

                                        //var_dump($result[$theline][0][$key]);
                                        $db = new Database();
                                        $stmt = $db->query("select item_id, item_mode, entity_id, entity_label from  listmodels, entities where item_id = ? and item_mode = 'dest' and listmodels.object_id = entities.entity_id",array($result[$theline][0][$key]));
                                        $entity_label = array();
                                        while($res = $stmt->fetchObject()){
                                            $item_mode = null;
                                            $item_mode = $res->item_mode;
                                            $entity_label[] = $res->entity_label;
                                        }
                                        if($item_mode == 'dest'){
                                           ?>
                                        <a href="<?php echo($path_ban.$param_comp);?>" class="actionList" onclick="alert('<?php echo _THE_USER_JS.$admin_id._PLEASE_CHECK_LISTDIFF; 
                                            if(count($entity_label)==1){ echo $entity_label[0];}else{
                                                for($i=0;$i<count($entity_label);$i++){
                                                echo $entity_label[$i];
                                                if($i < count($entity_label)-1){
                                                    echo _AND;
                                                    }
                                                }
                                            }
                                            
                                            echo ' .';  ?>');"><i class="fa fa-pause fa-2x" title="<?php echo _SUSPEND;?>"></i></a>


                                        <?php  

                                        }else{

                                             ?>
                                        <a href="<?php echo($path_ban.$param_comp);?>" class="actionList" onclick="return(confirm('<?php echo _REALLY_SUSPEND." ";  if(isset($page_name) && $page_name == "users"){ echo $complete_name;} else { echo $admin_id; } ?> ?'));"><i class="fa fa-pause fa-2x" title="<?php echo _SUSPEND;?>"></i></a>


                                        <?php  

                                        }



                                        }else{
                            ?>
                                <a href="<?php echo($path_ban.$param_comp);?>" class="actionList" onclick="return(confirm('<?php echo _REALLY_SUSPEND." ";  if(isset($page_name) && $page_name == "users"){ echo $complete_name;} else { echo $admin_id; } ?> ?'));"><i class="fa fa-pause fa-2x" title="<?php echo _SUSPEND;?>"></i></a>
                            <?php 
                                        }

                                    }
                                }
                            }
                            ?>
                    </td>
                    <?php  }


                    ?>
                    <td class="action" >
                    <?php
                    if(!empty($page_name_del))
                    {
                        if(!$is_part_of_module && !$flag_not_admin && !$is_in_apps_dir)
                        {
                            //$path_del = $_SESSION['config']['businessappurl'].'admin/'.$admin.'/'.$page_name_del.".php?id=".$result[$theline][0][$key]."&amp;admin=".$admin;
                            $path_del = $_SESSION['config']['businessappurl'].'index.php?page='.$page_name_del."&amp;id=".$result[$theline][0][$key]."&amp;admin=".$admin;
                        }
                        elseif($flag_not_admin && !$is_in_apps_dir)
                        {
                            $is_del_page_popup = true;
                            //$path_del = "index.php?page=".$page_name_del."&id=".$result[$theline][0][$key];
                            $path_del =  $_SESSION['config']['businessappurl']."index.php?page=".$page_name_del."&amp;id=".$result[$theline][0][$key];
                        }
                        elseif($is_in_apps_dir)
                        {
                            //$path_del = $_SESSION['config']['businessappurl'].$admin.'/'.$page_name_del.".php?id=".$result[$theline][0][$key]."&amp;dir=".$admin;
                            $path_del = $_SESSION['config']['businessappurl'].'index.php?page='.$page_name_del."&amp;id=".$result[$theline][0][$key]."&amp;dir=".$admin;
                        }
                        else
                        {
                            //$path_del = $_SESSION['urltomodules'].$admin.'/'.$page_name_del.".php?id=".$result[$theline][0][$key]."&amp;module=".$admin;
                            $path_del = $_SESSION['config']['businessappurl'].'index.php?page='.$page_name_del."&amp;id=".$result[$theline][0][$key]."&amp;module=".$admin;
                        }
                        if(!$is_del_page_popup)
                        {
                            $path_del .= '&amp;display=true';
                        }
                        if( $can_delete == false || $name == "users" &&  $result[$theline][0][$key] == "superadmin")
                        {
                            echo "&nbsp;";
                        }
                        else
                        {
                            $path_del = preg_replace("/(&(?!amp;))/", "&amp;", $path_del);
                        ?>
                            <a href="<?php echo($path_del.$param_comp);?>"  class="actionList"
                        onclick="return(confirm('<?php echo _REALLY_DELETE." ";  if(isset($page_name) && $page_name == "users"){ echo $complete_name;}
                                 else { echo $admin_id; }?> ?\n\r\n\r<?php echo _DEFINITIVE_ACTION;?>'));"><i class="fa fa-remove fa-2x" title="<?php echo _DELETE;?>"></i></a>
                        <?php
                        }
                    }
                    ?>
                    </td>
                    <?php  }
                    else if($bool_simple_list)
                    {
                        $width = "450";
                        if($name == 'structures'){
                            $height = "570";
                            $width = "600";
                        }
                        elseif($page_name_up == "contrat_up")
                        {
                            $height = "750";
                        }
                        elseif($name == 'types')
                        {
                            $height = "650";
                        }
                        else
                        {
                            $height = "250";
                        }


                if($admin == "types" || $admin == "structures" || $admin == "subfolders" || $admin == "contact_types" || $admin == "contact_purposes" || $admin == "contacts_v2" || $admin == "contact_addresses" || $admin == "contact_addresses_list")
                {
                    $path_up2 = $_SESSION['config']['businessappurl']."index.php?display=true&amp;page=".$page_name_up."&amp;mode=up&amp;id=".$result[$theline][0][$key];
                    $path_del2 = $_SESSION['config']['businessappurl']."index.php?display=true&amp;page=".$page_name_del."&amp;id=".$result[$theline][0][$key];
                }
                elseif(!$is_part_of_module)
                {
                    $path_up2 = $_SESSION['config']['businessappurl']."index.php?display=true&amp;page=".$page_name_up."&amp;mode=up&amp;id=".$result[$theline][0][$key]."&amp;admin=".$admin;
                    $path_del2 = $_SESSION['config']['businessappurl']."index.php?display=true&amp;page=".$page_name_del."&amp;id=".$result[$theline][0][$key]."&amp;admin=".$admin;
                }
                else
                {
                    $path_up2 =  $_SESSION['config']['businessappurl']."index.php?display=true&amp;page=".$page_name_up."&amp;mode=up&amp;id=".$result[$theline][0][$key]."&amp;module=".$admin;
                    $path_del2 = $_SESSION['config']['businessappurl']."index.php?display=true&amp;page=".$page_name_del.".&amp;id=".$result[$theline][0][$key]."&amp;module=".$admin;

                }
                $path_up2 = preg_replace("/(&(?!amp;))/", "&amp;", $path_up2);
                $path_del2 = preg_replace("/(&(?!amp;))/", "&amp;", $path_del2);
                        ?>
                        <td class="action">
                        <a  href="javascript://" class="actionList" onclick="window.open('<?php echo($path_up2);?>','','height=<?php functions::xecho($height);?>, width=<?php functions::xecho($width);?>,scrollbars=yes,resizable=yes');" ><i class="fa fa-edit fa-2x" title="<?php echo _MODIFY;?>"></i></a>
                    </td>

                    <td class="action" >
                        <a href="<?php echo($path_del2.$param_comp);?>" class="actionList"
                    onclick="return(confirm('<?php echo _REALLY_DELETE;  if(isset($page_name) && $page_name == "users"){ echo $complete_name;}
                             else { echo " ".$admin_id; }?> ?\n\r\n\r<?php echo _DEFINITIVE_ACTION;?>'));"><i class="fa fa-remove fa-2x" title="<?php echo _DELETE;?>"></i></a>
                    </td>
                    <?php  } ?>
              </tr>


        <?php
        }
        ?>  </tbody>

        </table><br/>
        </div>
        <?php
        $core = new core_tools();
        if($core->is_module_loaded("doc_converter"))
        {
            $_SESSION['doc_convert'] = array();
            require_once("modules".DIRECTORY_SEPARATOR."doc_converter".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
            $doc_converter = new doc_converter();
            $doc_converter->convert_list($result);
        }
?>

        <script>
            $$('#container')[0].setAttribute('style', 'width: 90%; min-width: 1000px;');
            $$('#content')[0].setAttribute('style', 'width: auto; min-width: 1000px;');
            $$('#inner_content')[0].setAttribute('style', 'width: auto; min-width: 1000px;');
            // $$('table#test')[0].setAttribute('style', 'width: 100%; min-width: 900px; margin: 0;');
            $$('table.listing')[0].setAttribute('style', 'width: 100%; min-width: 900px;');
        </script>

<?php
    }


    public static function define_order($order, $field)
    {
        // configure the sql argument order by
        $orderby = "";

        if(isset($field)  && !empty($field) && (empty($order) || $order == 'asc' || $order == 'desc'))
        {
            $orderby = "order by ".$field." ".$order;
        }
        return $orderby;
    }


    public function list_simple(
        $result,
        $nb_total,
        $title,
        $what,
        $key,
        $bool_view_document,
        $page_view = '',
        $used_css = 'listing spec',
        $page_modify = '',
        $height_page_modify = 400,
        $width_page_modify = 500,
        $page_del = '',
        $modeString = false
    )
    {
        //self::show_array($result);
        $listcolumn = array();
        $listshow = array();
        $listformat = array();
        $start = 0;
        $end = $nb_total;
        $str = '';
        // put in tab the different label of the column
        if (isset($result[0])) {
            for ($j = 0; $j < count($result[0]); $j ++) {
                if (isset($result[0][$j]["label"])) {
                    array_push($listcolumn, $result[0][$j]["label"]);
                } else {
                    array_push($listcolumn, '');
                }
                if (isset($result[0][$j]["show"])) {
                    array_push($listshow, $result[0][$j]["show"]);
                } else {
                    array_push($listshow, false);
                }
            }
        }
        //self::show_array($listcolumn);
        //self::show_array($listshow);
        $func = new functions();
        $nb_show = $_SESSION['config']['nblinetoshow'];
        $str .= '<b>';
        if (!empty($picto_path)) {
            $str .= '<img src="'.$picto_path.'" alt="" class="title_img" /> ';
        }
        $str .= $title . '</b>';
        $str .= ' <div align="center">';

        $str .= '<table border="0" cellspacing="0" class="' . $used_css . '">';
             $str .= '<thead>';
                $str .= '<tr>';
                    if ($bool_view_document) {
                        $str .= '<th style="width:3%;">&nbsp;</th>';
                    }
                    for ($count_column = 0;$count_column < count($listcolumn);$count_column++) {
                        if ($listshow[$count_column]==true) {
                            $str .= '<th style="width:' . $result[0][$count_column]['size']
                                . '%;" valign="' . $result[0][$count_column]['valign']
                                . '"  align="' . $result[0][$count_column]['label_align']
                                . '" ><span>' . $listcolumn[$count_column] . '</span>';
                        }
                    }
                    $str .= '<th style="width:4%;" valign="bottom" >&nbsp; </th>';
                $str .= '</tr>';
            $str .= '</thead>';
            $str .= '<tbody>';
        $color = "";
        for ($theline = $start; $theline < $end ; $theline++) {
            if($color == ' class="col"') {
                $color = '';
            } else {
                $color = ' class="col"';
            }
            $str .= '<tr ' . $color . '>';
                 if ($bool_view_document) {
                    $str .= " <td><a href='" . $page_view . "&amp;id="
                        . $result[$theline][0][$key]
                        . "' target=\"_blank\" title='" . _VIEW_DOC . "'>"
                        . '<i class="fa fa-download fa-2x" title="'._VIEW_DOC.'"></i>'
                        . "</a></td>";
                 }
                $bool_modify = false;
                $bool_del = false;
                for ($count_column=0;$count_column<count($listcolumn);$count_column ++) {
                    if (isset($result[$theline][$count_column]['show'])
                        && $result[$theline][$count_column]['show'] == true
                    ) {
                            $str .= '<td style="width:'
                                . $result[$theline][$count_column]['size']
                                . '%;" align="'
                                . $result[$theline][$count_column]['align']
                                . '">'
                                . functions::show_str(self::thisword($result[$theline][$count_column]['value'], $what))
                                . '</td>';
                    } else {
                        if ($result[$theline][$count_column]['column'] == 'modify_item' &&  $result[$theline][$count_column]['value'] == true) {
                            $bool_modify = true;
                        }
                        if ($result[$theline][$count_column]['column']  == 'delete_item' &&  $result[$theline][$count_column]['value'] == true) {
                            $bool_del = true;
                        }
                    }
                }
                if ($bool_modify) {
                    $str .= '<td class="action">';
                        $str .= '<a  href="javascript://" class="actionList" onclick="window.open(\''
                            . $page_modify;
                        if(preg_match('/\?/',$page_modify)){
                            $str .= '&amp;';
                        } else {
                            $str .= '?';
                        }
                        $str .= 'id=' . $result[$theline][0][$key] . '\',\'\',\'height='
                            . $height_page_modify . ', width=' . $width_page_modify
                            . ',scrollbars=yes,resizable=yes\');">'
                            . '<i class="fa fa-edit fa-2x" title="' . _MODIFY . '"></i></a>';
                    $str .= '</td>';
                } else {
                    $str .= '<td class="action">&nbsp;</td>';
                }
                if ($bool_del) {
                    $str .= '<td class="action">';
                        $str .= '<a href="' . $page_del . '&id=' . $result[$theline][0][$key] . '" class="delete"'
                            . 'onclick="return(confirm(\'' . _REALLY_DELETE . ' ?\n\r\n\r'
                            . _DEFINITIVE_ACTION . '\'));">'
                            . _DELETE . '</a>';
                    $str .= '</td>';
                } else {
                    $str .= '<td class="action">&nbsp;</td>';
                }
            $str .= '</tr>';
        }
        $str .= '</tbody>';
        $str .= '</table>';
        $str .= '</div>';
        if ($modeString) {
            return $str;
        } else {
            echo $str;
        }
    }

    public function list_attachments(
        $result,
        $nb_total,
        $title,
        $what,
        $key,
        $bool_view_document,
        $page_view = '',
        $used_css = 'listing spec',
        $page_modify = '',
        $height_page_modify = 400,
        $width_page_modify = 500,
        $page_del = '',
        $modeString = false
    )
    {
        //self::show_array($result);
        $listcolumn = array();
        $listshow = array();
        $listformat = array();
        $start = 0;
        $end = $nb_total;
        $str = '';
        // put in tab the different label of the column
        if (isset($result[0])) {
            for ($j = 0; $j < count($result[0]); $j ++) {
                if (isset($result[0][$j]["label"])) {
                    array_push($listcolumn, $result[0][$j]["label"]);
                } else {
                    array_push($listcolumn, '');
                }
                if (isset($result[0][$j]["show"])) {
                    array_push($listshow, $result[0][$j]["show"]);
                } else {
                    array_push($listshow, false);
                }
            }
        }
        //self::show_array($listcolumn);
        //self::show_array($listshow);
        $func = new functions();
        $nb_show = $_SESSION['config']['nblinetoshow'];
        $str .= '<b>';
        if (!empty($picto_path)) {
            $str .= '<img src="'.$picto_path.'" alt="" class="title_img" /> ';
        }
        $str .= $title . '</b>';
        $str .= ' <div align="center">';
		$str .= '<input type="hidden" id="category_id" value="outgoing"/>';
        $str .= '<table border="0" cellspacing="0" class="' . $used_css . '">';
             $str .= '<thead>';
                $str .= '<tr>';
                    if ($bool_view_document) {
                        $str .= '<th style="width:3%;">&nbsp;</th>';
                    }
                    for ($count_column = 0;$count_column < count($listcolumn);$count_column++) {
                        if ($listshow[$count_column]==true) {
                            $str .= '<th style="width:' . $result[0][$count_column]['size']
                                . '%;" valign="' . $result[0][$count_column]['valign']
                                . '"  align="' . $result[0][$count_column]['label_align']
                                . '" ><span>' . $listcolumn[$count_column] . '</span>';
                        }
                    }
                    $str .= '<th style="width:4%;" valign="bottom" >&nbsp; </th>';
                $str .= '</tr>';
            $str .= '</thead>';
            $str .= '<tbody>';
        $color = "";
        for ($theline = $start; $theline < $end ; $theline++) {
            if($color == ' class="col"') {
                $color = '';
            } else {
                $color = ' class="col"';
            }
            $str .= '<tr ' . $color . '>';
                 if ($bool_view_document) {
                    $str .= " <td><a href='" . $page_view . "&amp;id="
                        . $result[$theline][0][$key]
                        . "' target=\"_blank\" title='" . _VIEW_DOC . "'>"
                        . '<i class="fa fa-download fa-2x" title="'._VIEW_DOC.'"></i>'
                        . "</a></td>";
                 }
                $bool_modify = false;
                $bool_del = false;
                for ($count_column=0;$count_column<count($listcolumn);$count_column ++) {
                    if (isset($result[$theline][$count_column]['show'])
                        && $result[$theline][$count_column]['show'] == true
                    ) {
                            $str .= '<td style="width:'
                                . $result[$theline][$count_column]['size']
                                . '%;" align="'
                                . $result[$theline][$count_column]['align']
                                . '">'
                                . functions::show_str(self::thisword($result[$theline][$count_column]['value'], $what))
                                . '</td>';
                    } else {
                        if ($result[$theline][$count_column]['column'] == 'modify_item' &&  $result[$theline][$count_column]['value'] == true) {
                            $bool_modify = true;
                        }
                        if ($result[$theline][$count_column]['column']  == 'delete_item' &&  $result[$theline][$count_column]['value'] == true) {
                            $bool_del = true;
                        }
                    }
                }
                if ($bool_modify) {
                    $str .= '<td class="action">';
                        $str .= '<a  href="javascript://" class="change" onclick="modifyAttachmentsForm(\'' . $_SESSION['config']['businessappurl']
                            . 'index.php?display=true&module=attachments&page=attachments_content';
                        $str .= '&id=' . $result[$theline][0][$key] . '\',\'98%\',\'auto\');">'
                            . _MODIFY . '</a>';
                    $str .= '</td>';
                } else {
                    $str .= '<td class="action">&nbsp;</td>';
                }
                if ($bool_del) {
                    $str .= '<td class="action">';
                        $str .= '<a href="' . $page_del . '&id=' . $result[$theline][0][$key] . '" class="delete"'
                            . 'onclick="return(confirm(\'' . _REALLY_DELETE . ' ?\n\r\n\r'
                            . _DEFINITIVE_ACTION . '\'));">'
                            . _DELETE . '</a>';
                    $str .= '</td>';
                } else {
                    $str .= '<td class="action">&nbsp;</td>';
                }
            $str .= '</tr>';
        }
        $str .= '</tbody>';
        $str .= '</table>';
        $str .= '</div>';
        if ($modeString) {
            return $str;
        } else {
            echo $str;
        }
    }
}

