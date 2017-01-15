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
* @package  Maarch LetterBox 3.0
* @version 3.0
* @since 10/2005
* @license GPL
* @author  Loïc Vinet  <dev@maarch.org>
*
*/

abstract class list_show_with_template_Abstract extends list_show
{


    //Public variables

    public $actual_line_css;
    public $the_start;
    public $the_link;
    public $detail_destination;
    public $bool_radio_form;
    public $bool_check_form;
    public $bool_view_document;
    public $bool_detail;
    public $do_action;
    public $id_action;
    public $do_action_arr;
    public $hide_standard_list;

    //Load value from db with $result tab
    public function tmplt_load_value($actual_string, $theline, $result)
    {
        $my_explode= explode ("|", $actual_string);
        if (!$my_explode[1])
        {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        }
        else
        {
            $to_share = $my_explode[1];
            for($stand = 0; $stand <= count($result[$theline]); $stand++ )
            {
                if (isset($result[$theline][$stand]['column'])
                    && $result[$theline][$stand]['column'] == $to_share
                ) {
                        return $result[$theline][$stand]['value'];
                }

            }

        }
    }

    //Load value from db with $result tab
    public function tmplt_load_date($actual_string, $theline, $result)
    {
        $my_explode= explode ("|", $actual_string);
        if (!$my_explode[1])
        {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        }
        else
        {
            $to_share = $my_explode[1];
            for($stand= 0; $stand <= count($result[$theline]); $stand++ )
            {
                if (isset($result[$theline][$stand]['column'])
                    && $result[$theline][$stand]['column'] == $to_share
                ) {
                        return $this->format_date($result[$theline][$stand]['value']);
                }

            }

        }
    }


    //Load css defined in $actual_string
    public function tmplt_load_css($actual_string)
    {
        $my_explode= explode ("|", $actual_string);

        if (!$my_explode[1])
        {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        }
        else
        {
            return $my_explode[1];
        }
    }


    //Load image from apps defined in $actual_string
    public function tmplt_load_img($actual_string)
    {
        $my_explode= explode ("|", $actual_string);

        if (!$my_explode[1])
        {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        }
        else
        {
            return '<i class="fa fa-' . $my_explode[1] . '"></i>';
        }
    }


    //Load radio form if this parameters is loaded in list_show and list_show_with_template
    public function tmplt_load_external_script($actual_string, $theline, $result, $key)
    {
        $external = '';
        $my_explode= explode ("|", $actual_string);
        if (count($my_explode) <> 3)
        {

            return  _WRONG_PARAM_FOR_LOAD_VALUE;
        }

        $module_id = $my_explode[1];
        $file_name = $my_explode[2];

        include('modules'.DIRECTORY_SEPARATOR.$module_id.DIRECTORY_SEPARATOR.$file_name);
        return $external;

    }


    //Load function order from templated list
    public function tmplt_order_link($actual_string)
    {
        $my_explode= explode ("|", $actual_string);

        if (count($my_explode) <> 3)
        {

            return  _WRONG_PARAM_FOR_LOAD_VALUE;
        }
        else
        {
            $my_link = $this->the_link."&amp;start=".$this->the_start."&amp;order=".$my_explode[2]."&amp;order_field=".$my_explode[1];
            return $my_link;
        }
    }


    //Generate link to view the document
    public function url_docview($actual_string, $theline, $result, $key)
    {
        $return = $_SESSION['config']['businessappurl']."index.php?display=true&dir=indexing_searching&page=view_resource_controler&id=".$result[$theline][0][$key];
        return $return;
    }


    //Generate link to view detail page
    public function tmplt_url_docdetail($actual_string, $theline, $result, $key)
    {
        $return = $_SESSION['config']['businessappurl']."index.php?page=".$this->detail_destination."&amp;id=".$result[$theline][0][$key];
        return $return;
    }

    //Load radio form if this parameters is loaded in list_show and list_show_with_template
    public function tmplt_func_bool_radio_form($actual_string, $theline, $result, $key)
    {

        if ($this->bool_radio_form == true)
        {
            $return = '<input type="radio"  class="check" name="field" value="'.$result[$theline][0]['value'].'" class="check" />';
            return $return;
        }

    }



    //Load check form if this parameters is loaded in list_show and list_show_with_template
    public function tmplt_func_bool_check_form($actual_string, $theline, $result, $key)
    {

        if ($this->bool_check_form == true)
        {
            $return = '<input type="checkbox"  name="field" value="'.$result[$theline][0]['value'].'" class="check" />';
            return $return;
        }

    }
//onclick="valid_form( \'page\', \''.$result[$theline][0]['value'].'\', \''.$id_action.'\');

    //Load check form if this parameters is loaded in list_show and list_show_with_template
    public function tmplt_func_click_form($actual_string, $theline, $result, $key)
    {
        if ($this->do_action && ! empty($this->id_action)
            &&
            (! isset($this->do_actions_arr) || count($this->do_actions_arr) == 0
                || (isset($this->do_actions_arr)
                && $this->do_actions_arr[$theline] == true))
        ) {
            $return = '//onclick="valid_form( \'page\', \''.$result[$theline][0]['value'].'\', \''.$this->id_action.'\');" onmouseover="this.style.cursor=\'pointer\';"';
            return $return;
        }

    }


    //Load view_doc if this parameters is loaded in list_show and list_show_with_template
    public function tmplt_func_bool_detail_doc($actual_string, $theline, $result, $key)
    {

        if ($this->bool_detail == true)
        {

            $return = "<a href='".$_SESSION['config']['businessappurl']."index.php?page="
            .$this->detail_destination."&amp;id=".$result[$theline][0][$key]
            ."' title='". _DETAILS."'><i class='fa fa-info-circle fa-2x' title='"._DETAILS."'></i></a>";

            return $return;
        }

    }

    //Show img.eye if attachments on the doc
    public function tmplt_func_bool_see_attachments($actual_string, $theline, $result, $key)
    {

        $return = '';
        if ($result[$theline][0]['aDesReps']) {
            $return .= '<i ';
            $return .= 'class="fa fa-gears fa-2x" ';
            $return .= 'style="';
                $return .= 'cursor: pointer;';
            $return .= '" ';
            $return .= 'onclick=" ';
              $return .= 'loadRepList(';
                $return .= $result[$theline][0]['value'];
              $return .= ');';
            $return .= '" ';
            $return .= '></i>';
        }

        return $return;
    }

    public function tmplt_func_bool_see_items($actual_string, $theline, $result, $key)
    {
        $return = '';
        //if ($result[$theline][0]['aDesReps']) {
            $return .= '<i ';
            $return .= 'class="fa fa-info-circle fa-2x" ';
            $return .= 'style="';
                $return .= 'cursor: pointer;';
            $return .= '" title="' . _VIEW_ARCHIVES . '"';
            $return .= 'onclick="';
              $return .= 'ArchiveTransferBasket__loadItemList(';
                $return .= $result[$theline][0]['value'];
              $return .= ');';
            $return .= '" ';
            $return .= '></i>';
        //}
        return $return;
    }
    
    public function tmplt_func_see_persistent($actual_string, $theline, $result, $key)
    {
        if (isset($result[$theline][0]['isPersistent']) && $result[$theline][0]['isPersistent']) {
            $return = '<i class="fa fa-lock fa-2x" title="persistance activée"></i>';
        } else {
            $return = '<i class="fa fa-unlock fa-2x" title="persistance désactivée"></i>';
        }

        return $return;
    }

    //Load view_doc if this parameters is loaded in list_show and list_show_with_template
    public function tmplt_func_bool_detail_cases($actual_string, $theline, $result, $key)
    {
        if ($this->bool_detail == true)
        {
            $return = "<a href='".$_SESSION['config']['businessappurl']."index.php?page=details_cases&module=cases&amp;id=".$result[$theline][0]['case_id']."' title='". _DETAILS_CASES."'>
            <i class='fa fa-info-circle fa-2x' title='"._DETAILS."'></i></a>";

            return $return;
        }

    }


    //Load check form if this parameters is loaded in list_show and list_show_with_template
    public function tmplt_func_bool_view_doc($actual_string, $theline, $result, $key)
    {

        if ($this->bool_view_document == true)
        {

            $return = "<a href='".$_SESSION['config']['businessappurl']."index.php?display=true&dir=indexing_searching&page=view_resource_controler&id=".$result[$theline][0][$key]."' target=\"_blank\" title='"._VIEW_DOC."'>
                       <i class='fa fa-download fa-2x' title='"._VIEW_DOC."'></i></a>";
            return $return;
        }

    }




    //Load check form if this parameters is loaded in list_show and list_show_with_template
    public function tmplt_include_by_module($actual_string, $theline, $result, $key, $string_to_module)
    {
        $my_explode= explode ("|", $actual_string);
        if (count($my_explode) <> 2)
        {

            return  _WRONG_PARAM_FOR_LOAD_VALUE;
        }
        else
        {
            $core_tools = new core_tools();
            $module_id = $my_explode[1];
            if($core_tools->is_module_loaded($module_id) == true)
            {

                $temp = $string_to_module;
                preg_match_all('/##(.*?)##/', $temp, $out);

                for($i=0;$i<count($out[0]);$i++)
                {
                    $remplacement = $this->load_var_sys($out[1][$i], $theline,$result, $key);
                    $temp = str_replace($out[0][$i],$remplacement,$temp);
                }
                $string_to_module = $temp;

                return $string_to_module;

            }
            else
            {
                return '';
            }
        }


    }


    //Reload last css parameter defined for the result list
    public function tmplt_css_line_reload($actual_string)
    {
        return $this->actual_line_css;
    }


    //Load constant from lang file
    function tmplt_define_lang($actual_string)
    {
        $my_explode= explode ("|", $actual_string);

        if (!$my_explode[1])
        {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        }
        else
        {
            return constant($my_explode[1]);
        }
    }


    //Load css for actual line. For the next line, arg1 is swith by arg2
    public  function tmplt_css_line($actual_string)
    {
        $my_explode= explode ("|", $actual_string);

        if (!$my_explode[1])
        {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        }
        else
        {
            if(count($my_explode) == 2 )
            {
                return $my_explode[1];
            }
            elseif(count($my_explode) == 3 )
            {
                if ($this->actual_line_css == '')
                {
                    $this->actual_line_css = $my_explode[1];
                    return $this->actual_line_css;
                }
                elseif ($this->actual_line_css == $my_explode[1])
                {
                    $this->actual_line_css = $my_explode[2];
                    return $this->actual_line_css;
                }
                elseif ($this->actual_line_css == $my_explode[2])
                {
                    $this->actual_line_css = $my_explode[1];
                    return $this->actual_line_css;
                }
                else
                {
                    return _WRONG_PARAM_FOR_LOAD_VALUE;
                }
            }
            else
            {
                return _WRONG_PARAM_FOR_LOAD_VALUE;
            }

        }
    }



    //Load string ans search all function defined in this string
    public function load_var_sys($actual_string, $theline, $result = array(), $key = 'empty' , $include_by_module= '')
    {

        ##load_value|arg1##: load value in the db; arg1= column's value identifier
        if (preg_match("/^load_value\|/", $actual_string))
        //elseif($actual_string == "load_value")
        {
            $my_var = $this->tmplt_load_value($actual_string, $theline, $result);
        }
        ##load_value|arg1##: load value in the db; arg1= column's value identifier
        elseif (preg_match("/^load_date\|/", $actual_string))
        {
            $my_var = $this->tmplt_load_date($actual_string, $theline, $result);
        }
        ##load_css|arg1## : load css style - arg1= name of this class
        elseif (preg_match("/^load_css\|/", $actual_string))
        {
            $my_var = $this->tmplt_load_css($actual_string);
        }
        ##css_line|coll|nonecoll## : load css style for line arg1,arg2 : switch beetwin style on line one or line two
        elseif (preg_match("/^css_line_reload$/", $actual_string))
        {
            $my_var = $this->tmplt_css_line_reload($actual_string);
        }
        ##css_line|coll|nonecoll## : load css style for line arg1,arg2 : switch beetwin style on line one or line two
        elseif (preg_match("/^css_line\|/", $actual_string))
        {
            $my_var = $this->tmplt_css_line($actual_string);
        }
        ##load_img|arg1## : show loaded image; arg1= name of img file
        elseif (preg_match("/^load_img\|/", $actual_string))
        {

            $my_var = $this->tmplt_load_img($actual_string);
        }
        ##order_link|arg1|arg2## : reload list and change order;  arg1=type; arg2=sort
        elseif (preg_match("/^order_link\|/", $actual_string))
        {

            $my_var = $this->tmplt_order_link($actual_string);

        }
        ##url_docview## : view the file
        elseif (preg_match("/^url_docview$/", $actual_string))
        {
            $my_var = $this->url_docview($actual_string, $theline, $result, $key);

        }
        ##define_lang|arg1## : define constant by the lang file; arg1 = constant of lang.php
        elseif (preg_match("/^define_lang\|/", $actual_string))
        {
            $my_var = $this->tmplt_define_lang($actual_string);
        }
        ##url_docdetail## : load page detail for this file
        elseif (preg_match("/^url_docdetail$/", $actual_string))
        {
            $my_var = $this->tmplt_url_docdetail($actual_string, $theline, $result, $key);
        }
        ##func_bool_radio_form## : Activate parameters in class list show
        elseif (preg_match("/^func_bool_radio_form$/", $actual_string))
        {
            $my_var = $this->tmplt_func_bool_radio_form($actual_string, $theline, $result, $key);
        }
        ##func_bool_check_form## : Activate parameters in class list show
        elseif (preg_match("/^func_bool_check_form$/", $actual_string))
        {
            $my_var = $this->tmplt_func_bool_check_form($actual_string, $theline, $result, $key);
        }
        ##func_bool_view_doc## : Activate parameters in class list show
        elseif (preg_match("/^func_bool_view_doc$/", $actual_string))
        {
            $my_var = $this->tmplt_func_bool_view_doc($actual_string, $theline, $result, $key);
        }
        ##func_bool_detail_doc## : Activate parameters in class list show
        elseif (preg_match("/^func_bool_detail_doc$/", $actual_string))
        {
            $my_var = $this->tmplt_func_bool_detail_doc($actual_string, $theline, $result, $key);
        }
        elseif (preg_match("/^func_bool_detail_rm$/", $actual_string))
        {
            $my_var = $this->tmplt_func_bool_detail_rm($actual_string, $theline, $result, $key);
        }
        elseif (preg_match("/^func_bool_detail_io$/", $actual_string))
        {
            $my_var = $this->tmplt_func_bool_detail_io($actual_string, $theline, $result, $key);
        }
        elseif (preg_match("/^func_click_form$/", $actual_string))
        {
            $my_var = $this->tmplt_func_click_form($actual_string, $theline, $result, $key);
        }
        elseif (preg_match("/^func_include_by_module\|/", $actual_string))
        {
            $my_var = $this->tmplt_include_by_module($actual_string, $theline, $result, $key,$include_by_module);
        }
        elseif (preg_match("/^func_load_external_script\|/", $actual_string))
        {
            $my_var = $this->tmplt_load_external_script($actual_string, $theline, $result, $key,$include_by_module);
        }
        elseif (preg_match("/^func_bool_detail_case$/", $actual_string))
        {
            $my_var = $this->tmplt_func_bool_detail_cases($actual_string, $theline, $result, $key,$include_by_module);
        }
        elseif (preg_match("/^func_bool_see_attachments$/", $actual_string))
        {
            $my_var = $this->tmplt_func_bool_see_attachments($actual_string, $theline, $result, $key,$include_by_module);
        }
        elseif (preg_match("/^func_bool_see_items$/", $actual_string))
        {
            $my_var = $this->tmplt_func_bool_see_items($actual_string, $theline, $result, $key,$include_by_module);
        }
        elseif (preg_match("/^func_see_persitent$/", $actual_string))
        {
            $my_var = $this->tmplt_func_see_persistent($actual_string, $theline, $result, $key);
        }
        else
        {
            $my_var = _WRONG_FUNCTION_OR_WRONG_PARAMETERS;
        }
        return $my_var;
    }


    //Get template and remove all comments
    public function get_template($this_file)
    {
            //Ouverture du fichier
            $list_trait = file_get_contents ($this_file);
            //Suppression des commantaires dans la page
            $list_trait = preg_replace("/(<!--.*?-->)/s","", $list_trait);

            return $list_trait;

    }
    //show obect to switch in another lists
    public function display_template_for_user($template_list, $link)
    {

        /* $template_list : list of template
         *                               [name] : name of template file
         *                           [img] : html img to use for this template
         *                           [label] : label to show in alt tag or title tag
         */
        if ($this->hide_standard_list == true) {
            $standard = '';
        } else {
            $standard = "<a href='" . $link . "&template='><img src='"
                . $_SESSION['config']['businessappurl'] . "static.php?filename"
                . "=standard_list.gif' alt='" . _ACCESS_LIST_STANDARD
                . "' ></a>";
        }
        $extend = "";
        foreach ($template_list as $temp) {
            $extend .= "&nbsp;<a href='" . $link . "&amp;template="
                    .$temp['name'] . "'> <i class='" . $temp['img'] . "' title='" . $temp['label'] . "'></i></a>";
        }
        return $standard." ".$extend."";
    }


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
    public function list_doc_by_template(
    $result, $nb_total, $title,
    $what, $name = "search", $key, $detail_destination, $bool_view_document,
    $bool_radio_form, $method,$action, $button_label, $bool_detail, $bool_order,
    $bool_frame=false, $bool_export=false, $show_close=FALSE, $show_big_title=true,
    $show_full_list=true, $bool_check_form=false, $res_link = '', $module='',
    $bool_show_listletters = false, $all_sentence = '', $whatname = '',
    $used_css = 'listing spec', $comp_link = "", $link_in_line = false,
    $bool_show_actions_list = false, $actions = array(), $hidden_fields = '',
    $actions_json= '{}', $do_action = false, $id_action = '',
    $open_details_popup = true, $do_actions_arr = array(), $template = false,
    $template_list = array(), $actual_template = '', $mode_string = false,
    $hide_standard_list = false)
    {
        $core_tools = new core_tools();
        $core_tools->load_lang();
        $list_title = '';
        $str = '';
        $this->detail_destination = $detail_destination;
        $this->bool_radio_form = $bool_radio_form;
        $this->bool_check_form = $bool_check_form;
        $this->bool_view_document = $bool_view_document;
        $this->bool_detail = $bool_detail;
        $this->do_action = $do_action;
        $this->id_action = $id_action; /*To keep value for extended simples script =>*/ $_SESSION['extended_template']['id_default_action'] = $this->id_action;
        $this->do_action_arr = $do_actions_arr;
        $this->hide_standard_list = $hide_standard_list;
        if (isset($_REQUEST['start']) && $_REQUEST['start'] > $nb_total) {
            $_REQUEST['start'] = 0;
        }
        if(isset($_REQUEST['start']) && !empty($_REQUEST['start']))
        {
            $start = strip_tags($_REQUEST['start']);
        }
        else
        {
            $start = 0;
        }
        /* ---------------------- */
        if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."template".DIRECTORY_SEPARATOR.$actual_template.".html"))
        {
            $file = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."template".DIRECTORY_SEPARATOR.$actual_template.".html";
        }
        else
        {
            $file = 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."template".DIRECTORY_SEPARATOR.$actual_template.".html";
        }

        //To load including values template Use for case by exemple
        //##############################################################
        if($core_tools->is_module_loaded("cases") == true)
        {
            $case_file = "modules".DIRECTORY_SEPARATOR."cases".DIRECTORY_SEPARATOR."template_addon".DIRECTORY_SEPARATOR.$actual_template.".html";
            if (file_exists($case_file))
            {
                $addon_list_trait = $this->get_template($case_file);
                $addon_tmp = explode("#!#", $addon_list_trait);
                foreach($addon_tmp as $including_file)
                {
                    if (substr($including_file , 0, 5) == "TABLE")
                        $including_table = substr($including_file, 5);
                    if (substr($including_file , 0, 4) == "HEAD")
                        $including_head = substr($including_file, 4);
                    if (substr($including_file , 0, 6) == "RESULT")
                        $including_result = substr($including_file, 6);
                    if (substr($including_file , 0, 6) == "FOOTER")
                        $including_footer = substr($including_file, 6);
                }
            }
        }
        //##############################################################
        $list_trait = $this->get_template($file);
        $tmp = explode("#!#", $list_trait);
        //Generate link for reloading file
        if($bool_frame)
        {
            //$link = $name.".php?search=".$what;
            $link = $_SESSION['config']['businessappurl']."index.php?display=true&page=".$name."&amp;search=".$what;
        }
        else
        {
            $link = $_SESSION['config']['businessappurl']."index.php?page=".$name."&amp;search=".$what;
        }
        if (isset($_SESSION['where'])) {
            for ($i = 0; $i < count($_SESSION['where']); $i ++) {
                $link .= "&amp;where[]=".$_SESSION['where'][$i];
            }
        }
        if(!empty($module))
        {
            $link .= "&amp;module=".$module;
        }
        if(isset($_GET['what']))
        {
            $link .= "&amp;what=".strip_tags($_GET['what']);
        }
        if(isset($_REQUEST['start']) && !empty($_REQUEST['start']))
        {
            $start = strip_tags($_REQUEST['start']);
        }
        else
        {
            $start = 0;
        }
        $this->the_start = $start;
        $findme = 'order_field';
        $pos = stripos($name, $findme);
        if($pos === false)
        {
            if(isset($_GET['order']))
            {
                $orderby = strip_tags($_GET['order']);
            }
            else
            {
                $orderby = 'asc';
            }
            $link .= "&amp;order=".$orderby;
            if(isset($_GET['order_field']))
            {
                $orderfield = strip_tags($_GET['order_field']);
            }
            else
            {
                $orderfield = '';
            }
            $link .= "&amp;order_field=".$orderfield;
        }
        //echo $link;exit;
        $link .= $comp_link;
        if(isset($actual_template) && $actual_template <> '')
        {
            $link .= "&amp;template=".$actual_template;
        }
        else
        {
            $link .= "&amp;template=";
        }
        // Load object to switch template
        if (isset($template) && $template == true)
        {
            $tdeto = $this->display_template_for_user($template_list, $link);
            //$tdeto = _DISPLAY." : ".$tdeto;
        }
        //########################
        //require_once("core/class/class_core_tools.php");
        $core_tools = new core_tools();
        $disp_dc = '';
        if($core_tools->is_module_loaded("doc_converter") && $bool_export)
        {
            $_SESSION['doc_convert'] = array();
            require_once("modules".DIRECTORY_SEPARATOR."doc_converter".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
            $doc_converter = new doc_converter();
            $disp_dc = $doc_converter->convert_list($result, true);
        }
        //########################
        $page_list1 = '';
        $this->the_link = $link;
        $nb_show = $_SESSION['config']['nblinetoshow'];
        $nb_pages = ceil($nb_total/$nb_show);
        $end = $start + $nb_show;
        if($end > $nb_total)
        {
            $end = $nb_total;
        }
        if($show_big_title)
        {
            $list_title .= '<h1>';
            if(!empty($picto_path))
            { $list_title .= '<img src="'.$picto_path.'" alt="" class="title_img" /> ';}
            $list_title .= $title.'</h1>';
        }
        else
        {
            $list_title .= '<b>';
            if(!empty($picto_path))
            { $list_title .= '<img src="'.$picto_path.'" alt="" class="title_img" /> ';}
            $list_title .= $title.'</b>';
        }
        $theline = 0;
        // CHECK ALL IF ANY ACTION
        if ($bool_radio_form || $bool_check_form) {
            //$disp_dc .= '<input type="checkbox">';
            $disp_dc .= '<a href="#" onclick="checkAll();" >' . _CHECK_ALL . '</a>';
            $disp_dc .= '&nbsp;<a href="#" onclick="uncheckAll();" >' . _UNCHECK_ALL . '</a>';
            //$disp_dc .= '&nbsp;&nbsp;<input type="checkbox"><a href="#" onclick="reverseCheck();" >' . _REVERSE_CHECK . '</a>';
        }
        //if they are more 1 page we do pagination with 2 forms
        if($nb_pages > 1)
        {
            $next_start = 0;
            //$search_form = "<form name=\"newpage1\" method=\"get\" >";
            $page_list1 = _GO_TO_PAGE." <select name=\"startpage\" onchange=\"window.location.href='".$link."&amp;start='+this.value;\">";
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
            $previous = "";
            $next = "";
            if($start > 0)
            {
                $start_prev = $start - $nb_show;
                $previous = "<a href=\"".$link."&amp;start=".$start_prev."\"><i class=\"fa fa-backward\" title=\"" . _PREVIOUS . "\"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if($start <> $lastpage)
            {
                $start_next = $start + $nb_show;
                //$next = " <div class='list_next' ><a href=\"".$link."&amp;start=".$start_next."\">"._NEXT."</a> ></div>";
                $next = "<a href=\"".$link."&amp;start=".$start_next."\"><i class=\"fa fa-forward\" title=\"" . _NEXT . "\"></i></a>";
            }
            $page_list1 = '<div class="block" style="height:30px;vertical" '
                    . 'align="center" ><table width="100%" border="0"><tr>'
                    . '<td align="center" width="15%"><b>' . $previous
                    . '</b></td><td align="center" width="15%"><b>' . $next
                    . '</b></td><td width="10px">|</td><td align="center" '
                    . 'width="30%">' . $page_list1 . '</td><td width="10px">|'
                    . '</td><td width="210px" align="center">' . $disp_dc
                    . '</td><td width="5px">|</td><td align="right">' . $tdeto
                    . '</td></tr></table></b></div>';
        } else {
            $page_list1 = '<div class="block" style="height:30px;vertical" '
                    . 'align="center" ><table width="100%" border="0"><tr>'
                    . '<td align="center" width="15%"><b>&nbsp;'
                    . '</b></td><td align="center" width="15%"><b>&nbsp;'
                    . '</b></td><td width="10px">|</td><td align="center" '
                    . 'width="30%">&nbsp;</td><td width="10px">|'
                    . '</td><td width="210px" align="center">' . $disp_dc
                    . '</td><td width="5px">|</td><td align="right">' . $tdeto
                    . '</td></tr></table></b></div>';
        }
        //Script for action
        //#################
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
        //Exploding template to lunch funtion in load_var_sys()
        $table = '';
        $head = '';
        $content = '';
        $footer = '';
        foreach($tmp as $ac_tmp)
        {
            if (substr($ac_tmp , 0, 5) == "TABLE")
            {
                $table = substr($ac_tmp, 5);
                $true_table = $table;
                //appel des fonctions de remplacement;
                preg_match_all('/##(.*?)##/', $true_table, $out);

                for($i=0;$i<count($out[0]);$i++)
                {
                    $remplacement_table = $this->load_var_sys(
                        $out[1][$i], $theline, '', '', $including_table
                    );
                    $table = str_replace($out[0][$i],$remplacement_table,$true_table);
                }
            }
            elseif (substr($ac_tmp , 0, 4) == "HEAD")
            {
                $head = substr($ac_tmp, 4);
                $true_head = $head;
                preg_match_all('/##(.*?)##/', $true_head, $out);

                for($i=0;$i<count($out[0]);$i++)
                {
                    $remplacement_head = $this->load_var_sys($out[1][$i], $theline, '', '', $including_head);
                    $true_head = str_replace($out[0][$i],$remplacement_head,$true_head);
                }
                $head = $true_head;
            }
            elseif (substr($ac_tmp , 0, 6) == "RESULT")
            {
                $content = substr($ac_tmp, 6);
            }
            elseif (substr($ac_tmp , 0, 6) == "FOOTER")
            {
                $footer = substr($ac_tmp, 6);
            }
        }
        $content_list = '';
        for($theline = $start; $theline < $end ; $theline++)
        {
            $true_content=$content;
            preg_match_all('/##(.*?)##/', $true_content, $out);
            for($i=0;$i<count($out[0]);$i++)
            {
                $remplacement = $this->load_var_sys($out[1][$i], $theline, $result, $key,$including_result);
                $true_content = str_replace($out[0][$i],$remplacement,$true_content);
            }
            $content_list .= $true_content;
        }
        if( (($bool_radio_form || $bool_check_form) && count($result) > 0 && $bool_show_actions_list) || ($do_action && !empty($id_action)))
        {
            $str .= '<script type="text/javascript">';
            $str .= ' var arr_actions = '.$actions_json.';';
            $str .= ' var arr_msg_error = {\'confirm_title\' : \''._ACTION_CONFIRM.'\',';
                                        $str .= ' \'validate\' : \''._VALIDATE.'\',';
                                        $str .= ' \'cancel\' : \''._CANCEL.'\',';
                                        $str .= ' \'choose_action\' : \''._CHOOSE_ACTION.'\',';
                                        $str .= ' \'choose_one_doc\' : \''._CHOOSE_ONE_DOC.'\'';
                        $str .= ' };';
            //$str .= ' console.log(arr_msg_error);';
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
                        $str .= '  if(thisfrm.elements[i].name = \'field\' && thisfrm.elements[i].checked == true)';
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
                $str .= 'isAlreadyClick = true;';
                $str .= '}';
            $str .= ' }';
            $str .= ' </script>';
        }
        $str_foot = "";
        //#################
        //#################### Action module
        if(($bool_radio_form || $bool_check_form) && count($result) > 0 && !$bool_show_actions_list)
        {
            $str_foot .= ' <p align="center">';
            $str_foot .= ' <input class="button" type="submit" value="'.$button_label.'"  />';
           if($show_close )
            {
                $str_foot .= ' <input type="button" class="button" name="cancel" value="'._CLOSE_WINDOW.'" onclick="window.top.close();" />';
            }
            $str_foot .= ' </p>';
            $str_foot .= ' </form>';
            $str_foot .= ' <br/>';
        }
        else if(($bool_radio_form || $bool_check_form) && count($result) > 0 && $bool_show_actions_list)
        {
            $str_foot .= ' <p align="center">';
                $str_foot .= ' <b>'._ACTIONS.' :</b>';
                $str_foot .= ' <select name="action" id="action">';
                $str_foot .= ' <option value="">'. _CHOOSE_ACTION.'</option>';
                for($ind_act = 0; $ind_act < count($actions);$ind_act++)
                {
                    $str_foot .= ' <option value="'.$actions[$ind_act]['VALUE'].'">'.$actions[$ind_act]['LABEL'].'</option>';
                }
                $str_foot .= ' </select>';
                $str_foot .= ' <input type="button" name="send" id="send" value="'._VALIDATE.'" onclick="valid_form(\'mass\');window.location.href=\'#top\'" class="button" />';
            $str_foot .= ' </p>';
        $str_foot .= ' </form>';
        $str_foot .= ' <br/>';
        }
        elseif($do_action)
        {
            $str_foot .= ' </form>';
        }
        //######################
        // Print in application the generated template list result
        if ($mode_string == false)
        {
            echo $list_title.$page_list1.$str.$table.$head.$content_list.$footer.$str_foot;
        }
        else
        {
            return $list_title.$page_list1.$str.$table.$head.$content_list.$footer.$str_foot;
        }
    }
}
