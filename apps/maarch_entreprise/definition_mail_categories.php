<?php

/*
*    Copyright 2008-2016 Maarch
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
* @brief   Contains data structure used to get the proper index for a given category, to checks data and to loads in db (indexing, process, validation, details, ...) and the function to access it
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

///////////////////// Pattern to check dates
$_ENV['date_pattern'] = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";

/*
 * Categories are described in a global variable : $_ENV['categories']
 * $_ENV['categories'][category_id]
 *      ['img_cat'] : url of the icon category
 *      [id_field] // id_field must be an identifier of a field in the form and in the database
 *          ['type_form'] = Form type field: 'integer', 'date', 'string', 'yes_no', 'special'
 *          ['type_field'] = Database type field :'integer', 'date', 'string', 'yes_no', 'special'
 *          ['mandatory'] = true or false
 *          ['label'] = label of the field
 *          ['img'] = Image url (optional)
 *          ['table'] = keyword : 'res' = field in the res_x like table
 *                                'coll_ext' = field in collection ext table (ex : mlb_ext_coll)
 *                                'none' = field in no table, only in form for special functionnality
 *                                'special' = field in other table, handled in the code
 *          ['modify'] = true or false (optional) : can we modify this field (used in details.php)
 *          ['form_show'] = keyword (used with modify = true)
 *                           'select' = displayed in a select item
 *                           'textfield' = displayed in text input
 *                           'date' = displayed in a date input (with calendar activated)
 *      ['other_cases'] // particular cases handled in code
 *            [identifier] // keyword handled in the code
 *                  ['type_form'] = Form type field:'integer', 'date', 'string', 'yes_no', 'special'
 *                  ['type_field'] = Databse type field :'integer', 'date', 'string', 'yes_no', 'special'
 *                  ['mandatory'] = true or false
 *                  ['label'] = label of the field
 *                  ['table'] = keyword : 'res' = field in the res_x like table
 *                                        'coll_ext' = field in collection ext table (ex : mlb_ext_coll)
 *                                        'none' = field in no table, only in form for special functionnality
 *                                        'special' = field in another table, handled in the code
 *                  ['modify'] = true or false (optional) : can we modify this field (used in details.php)
 *                  ['form_show'] = keyword (used with modify = true)
 *                              'select' = displayed in a select item
 *                              'textfield' = keyword : 'date' = date input field
 *                              'date' = displayed in a date input (with calendar activated)
 *
 */

/**************************** Categories descriptions******************************/
$_ENV['categories'] = array ();

///////////////////////////// INCOMING ////////////////////////////////////////////////
$_ENV['categories']['incoming'] = array ();
$_ENV['categories']['incoming']['img_cat'] = '<i class="fa fa-arrow-right fa-2x"></i>';
$_ENV['categories']['incoming']['other_cases'] = array ();
$_ENV['categories']['incoming']['priority'] = array (
    'type_form' => 'integer',
    'type_field' => 'integer',
    'mandatory' => false,
    'label' => _PRIORITY,
    'table' => 'res',
    'img' => 'exclamation',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['incoming']['type_id'] = array (
    'type_form' => 'integer',
    'type_field' => 'integer',
    'mandatory' => true,
    'label' => _DOCTYPE_MAIL,
    'table' => 'res',
    'img' => 'file',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['incoming']['doc_date'] = array (
    'type_form' => 'date',
    'type_field' => 'date',
    'mandatory' => true,
    'label' => _MAIL_DATE,
    'table' => 'res',
    'img' => 'calendar-o',
    'modify' => true,
    'form_show' => 'date'
);
$_ENV['categories']['incoming']['admission_date'] = array (
    'type_form' => 'date',
    'type_field' => 'date',
    'mandatory' => true,
    'label' => _RECEIVING_DATE,
    'table' => 'coll_ext',
    'img' => 'calendar',
    'modify' => true,
    'form_show' => 'date'
);
$_ENV['categories']['incoming']['nature_id'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _NATURE,
    'table' => 'coll_ext',
    'img' => 'envelope',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['incoming']['reference_number'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => false,
    'label' => _MONITORING_NUMBER,
    'table' => 'res',
    'img' => 'barcode',
    'modify' => false,
    'form_show' => 'textfield'
);
$_ENV['categories']['incoming']['subject'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _SUBJECT,
    'table' => 'res',
    'img' => 'info',
    'modify' => true,
    'form_show' => 'textarea'
);
$_ENV['categories']['incoming']['process_limit_date_use'] = array (
    'type_form' => 'radio',
    'mandatory' => true,
    'label' => _PROCESS_LIMIT_DATE_USE,
    'table' => 'none',
    'values' => array (
        'Y',
        'N'
    ),
    'modify' => false
);
$_ENV['categories']['incoming']['other_cases']['process_limit_date'] = array (
    'type_form' => 'date',
    'type_field' => 'date',
    'label' => _PROCESS_LIMIT_DATE,
    'table' => 'coll_ext',
    'img' => 'bell',
    'modify' => true,
    'form_show' => 'date'
);
$_ENV['categories']['incoming']['type_contact'] = array (
    'type_form' => 'radio',
    'mandatory' => true,
    'label' => _SHIPPER_TYPE,
    'table' => 'none',
    'values' => array (
        'internal',
        'external'
    ),
    'modify' => false
);
$_ENV['categories']['incoming']['other_cases']['contact'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _SHIPPER,
    'table' => 'coll_ext',
    'special' => 'exp_user_id,exp_contact_id',
    'modify' => false
);

$_ENV['categories']['incoming']['confidentiality'] = array (
    'type_form' => 'radio',
    'type_field' => 'string',
    'mandatory' => false,
    'label' => _CONFIDENTIALITY,
    'table' => 'res',
    'values' => array (
        'Y',
        'N'
    ),
    'img' => 'exclamation-triangle',
    'modify' => true,
);

///////////////////////////// GED DOC ////////////////////////////////////////////////
$_ENV['categories']['ged_doc'] = array ();
$_ENV['categories']['ged_doc']['img_cat'] = '<i class="fa fa-arrow-right fa-2x"></i>';
$_ENV['categories']['ged_doc']['other_cases'] = array ();
$_ENV['categories']['ged_doc']['type_id'] = array (
    'type_form' => 'integer',
    'type_field' => 'integer',
    'mandatory' => true,
    'label' => _DOCTYPE,
    'table' => 'res',
    'img' => 'file',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['ged_doc']['doc_date'] = array (
    'type_form' => 'date',
    'type_field' => 'date',
    'mandatory' => true,
    'label' => _DOC_DATE,
    'table' => 'res',
    'img' => 'calendar-o',
    'modify' => true,
    'form_show' => 'date'
);
$_ENV['categories']['ged_doc']['subject'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _SUBJECT,
    'table' => 'res',
    'img' => 'info',
    'modify' => true,
    'form_show' => 'textarea'
);
$_ENV['categories']['ged_doc']['type_contact'] = array (
    'type_form' => 'radio',
    'mandatory' => true,
    'label' => _AUTHOR_TYPE,
    'table' => 'none',
    'values' => array (
        'internal',
        'external'
    ),
    'modify' => false
);
$_ENV['categories']['ged_doc']['other_cases']['contact'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _AUTHOR_DOC,
    'table' => 'coll_ext',
    'special' => 'exp_user_id,exp_contact_id',
    'modify' => false
);

$_ENV['categories']['ged_doc']['confidentiality'] = array (
    'type_form' => 'radio',
    'type_field' => 'string',
    'mandatory' => false,
    'label' => _CONFIDENTIALITY,
    'table' => 'res',
    'values' => array (
        'Y',
        'N'
    ),
    'img' => 'exclamation-triangle',
    'modify' => true,
);

///////////////////////////// OUTGOING ////////////////////////////////////////////////
$_ENV['categories']['outgoing'] = array ();
$_ENV['categories']['outgoing']['img_cat'] = '<i class="fa fa-arrow-left fa-2x"></i>';
$_ENV['categories']['outgoing']['other_cases'] = array ();
$_ENV['categories']['outgoing']['priority'] = array (
    'type_form' => 'integer',
    'type_field' => 'integer',
    'mandatory' => false,
    'label' => _PRIORITY,
    'table' => 'res',
    'img' => 'exclamation',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['outgoing']['type_id'] = array (
    'type_form' => 'integer',
    'type_field' => 'integer',
    'mandatory' => true,
    'label' => _DOCTYPE_MAIL,
    'table' => 'res',
    'img' => 'file',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['outgoing']['doc_date'] = array (
    'type_form' => 'date',
    'type_field' => 'date',
    'mandatory' => true,
    'label' => _MAIL_DATE,
    'table' => 'res',
    'img' => 'calendar-o',
    'modify' => true,
    'form_show' => 'date'
);
$_ENV['categories']['outgoing']['nature_id'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _NATURE,
    'table' => 'coll_ext',
    'img' => 'envelope',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['outgoing']['reference_number'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => false,
    'label' => _MONITORING_NUMBER,
    'table' => 'res',
    'img' => 'barcode',
    'modify' => false,
    'form_show' => 'textfield'
);
$_ENV['categories']['outgoing']['subject'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _SUBJECT,
    'table' => 'res',
    'img' => 'info',
    'modify' => true,
    'form_show' => 'textarea'
);
/*$_ENV['categories']['outgoing']['other_cases']['chrono_number'] = array (
    'type_form' => 'integer',
    'type_field' => 'integer',
    'mandatory' => true,
    'label' => _CHRONO_NUMBER,
    'table' => 'none',
    'img' => 'compass',
    'modify' => false,
    'form_show' => 'textfield'
);*/
$_ENV['categories']['outgoing']['process_limit_date_use'] = array (
    'type_form' => 'radio',
    'mandatory' => true,
    'label' => _PROCESS_LIMIT_DATE_USE,
    'table' => 'none',
    'values' => array (
        'Y',
        'N'
    ),
    'modify' => false
);
$_ENV['categories']['outgoing']['other_cases']['process_limit_date'] = array (
    'type_form' => 'date',
    'type_field' => 'date',
    'label' => _PROCESS_LIMIT_DATE,
    'table' => 'coll_ext',
    'img' => 'bell',
    'modify' => false,
    'form_show' => 'date'
);
$_ENV['categories']['outgoing']['type_contact'] = array (
    'type_form' => 'radio',
    'mandatory' => true,
    'label' => _DEST_TYPE,
    'table' => 'none',
    'values' => array (
        'internal',
        'external',
		'multi_external'
    ),
    'modify' => false
);
$_ENV['categories']['outgoing']['other_cases']['contact'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _DEST,
    'table' => 'coll_ext',
    'special' => 'dest_user_id,dest_contact_id,is_multicontacts',
    'img' => 'book',
    'modify' => false
);
$_ENV['categories']['outgoing']['confidentiality'] = array (
    'type_form' => 'radio',
    'type_field' => 'string',
    'mandatory' => false,
    'label' => _CONFIDENTIALITY,
    'table' => 'res',
    'values' => array (
        'Y',
        'N'
    ),
    'img' => 'exclamation-triangle',
    'modify' => true,
);

///////////////////////////// INTERNAL ////////////////////////////////////////////////
$_ENV['categories']['internal'] = array ();
$_ENV['categories']['internal']['img_cat'] = '<i class="fa fa-arrow-down fa-2x"></i>';
$_ENV['categories']['internal']['other_cases'] = array ();
$_ENV['categories']['internal']['priority'] = array (
    'type_form' => 'integer',
    'type_field' => 'integer',
    'mandatory' => false,
    'label' => _PRIORITY,
    'table' => 'res',
    'img' => 'exclamation',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['internal']['type_id'] = array (
    'type_form' => 'integer',
    'type_field' => 'integer',
    'mandatory' => true,
    'label' => _DOCTYPE_MAIL,
    'table' => 'res',
    'img' => 'file',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['internal']['doc_date'] = array (
    'type_form' => 'date',
    'type_field' => 'date',
    'mandatory' => true,
    'label' => _MAIL_DATE,
    'table' => 'res',
    'img' => 'calendar-o',
    'modify' => true,
    'form_show' => 'date'
);
$_ENV['categories']['internal']['nature_id'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _NATURE,
    'table' => 'coll_ext',
    'img' => 'envelope',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['internal']['reference_number'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => false,
    'label' => _MONITORING_NUMBER,
    'table' => 'res',
    'img' => 'barcode',
    'modify' => false,
    'form_show' => 'textfield'
);
$_ENV['categories']['internal']['subject'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _SUBJECT,
    'table' => 'res',
    'img' => 'info',
    'modify' => true,
    'form_show' => 'textarea'
);
$_ENV['categories']['internal']['process_limit_date_use'] = array (
    'type_form' => 'radio',
    'mandatory' => true,
    'label' => _PROCESS_LIMIT_DATE_USE,
    'table' => 'none',
    'values' => array (
        'Y',
        'N'
    ),
    'modify' => false
);
$_ENV['categories']['internal']['other_cases']['process_limit_date'] = array (
    'type_form' => 'date',
    'type_field' => 'date',
    'label' => _PROCESS_LIMIT_DATE,
    'table' => 'coll_ext',
    'img' => 'bell',
    'modify' => false
);
$_ENV['categories']['internal']['type_contact'] = array (
    'type_form' => 'radio',
    'mandatory' => true,
    'label' => _SHIPPER_TYPE,
    'table' => 'none',
    'values' => array (
        'internal',
        'external'
    ),
    'modify' => false
);
$_ENV['categories']['internal']['other_cases']['contact'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _SHIPPER,
    'table' => 'coll_ext',
    'special' => 'exp_user_id,exp_contact_id',
    'modify' => false
);

/////////////////////////////FOLDER DOCUMENT////////////////////////////////////////////////
$_ENV['categories']['folder_document'] = array ();
$_ENV['categories']['folder_document']['img_cat'] = '<i class="fa fa-folder fa-2x"></i>';
$_ENV['categories']['folder_document']['other_cases'] = array ();
$_ENV['categories']['folder_document']['type_id'] = array (
    'type_form' => 'integer',
    'type_field' => 'integer',
    'mandatory' => true,
    'label' => _DOCTYPE,
    'table' => 'res',
    'img' => 'file',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['folder_document']['doc_date'] = array (
    'type_form' => 'date',
    'type_field' => 'date',
    'mandatory' => true,
    'label' => _DOC_DATE,
    'table' => 'res',
    'img' => 'calendar-o',
    'modify' => true,
    'form_show' => 'date'
);
$_ENV['categories']['folder_document']['subject'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _SUBJECT,
    'table' => 'res',
    'img' => 'info',
    'modify' => true,
    'form_show' => 'textarea'
);
$_ENV['categories']['folder_document']['author'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _AUTHOR,
    'table' => 'res',
    'img' => 'pencil-square-o',
    'modify' => true,
    'form_show' => 'textfield'
);

/////////////////////////////EMPTY////////////////////////////////////////////////
$_ENV['categories']['empty'] = array ();
$_ENV['categories']['empty']['img_cat'] = '<i class="fa fa-circle-thin fa-2x"></i>';
$_ENV['categories']['empty']['other_cases'] = array ();
$_ENV['categories']['empty']['type_id'] = array (
    'type_form' => 'integer',
    'type_field' => 'integer',
    'mandatory' => true,
    'label' => _DOCTYPE,
    'table' => 'res',
    'img' => 'file',
    'modify' => true,
    'form_show' => 'select'
);
$_ENV['categories']['empty']['doc_date'] = array (
    'type_form' => 'date',
    'type_field' => 'date',
    'mandatory' => true,
    'label' => _DOC_DATE,
    'table' => 'res',
    'img' => 'calendar-o',
    'modify' => true,
    'form_show' => 'date'
);
$_ENV['categories']['empty']['title'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _INVOICE_NUMBER,
    'table' => 'res',
    'img' => 'info',
    'modify' => true,
    'form_show' => 'textfield'
);
$_ENV['categories']['empty']['identifier'] = array (
    'type_form' => 'string',
    'type_field' => 'string',
    'mandatory' => true,
    'label' => _INVOICE_NUMBER,
    'table' => 'res',
    'img' => 'compass',
    'modify' => true,
    'form_show' => 'textfield'
);

/////////////////////////////MODULES SPECIFIC////////////////////////////////////////////////
$core = new core_tools();
if ($core->is_module_loaded('entities')) {
    //Entities module (incoming)
    $_ENV['categories']['incoming']['destination'] = array (
        'type_form' => 'string',
        'type_field' => 'string',
        'mandatory' => true,
        'label' => _DEPARTMENT_DEST,
        'table' => 'res',
        'img' => 'sitemap',
        'modify' => false,
        'form_show' => 'textarea'
    );
    $_ENV['categories']['incoming']['other_cases']['diff_list'] = array (
        'type' => 'special',
        'mandatory' => true,
        'label' => _DIFF_LIST,
        'table' => 'special'
    );

    // Entities module (outgoing)
    $_ENV['categories']['outgoing']['destination'] = array (
        'type_form' => 'string',
        'type_field' => 'string',
        'mandatory' => true,
        'label' => _DEPARTMENT_EXP,
        'table' => 'res',
        'img' => 'sitemap',
        'modify' => false,
        'form_show' => 'textarea'
    );
    $_ENV['categories']['outgoing']['other_cases']['diff_list'] = array (
        'type' => 'special',
        'mandatory' => true,
        'label' => _DIFF_LIST,
        'table' => 'special'
    );

    // Entities module (internal)
    $_ENV['categories']['internal']['destination'] = array (
        'type_form' => 'string',
        'type_field' => 'string',
        'mandatory' => true,
        'label' => _DEPARTMENT_DEST,
        'table' => 'res',
        'img' => 'sitemap',
        'modify' => false,
        'form_show' => 'textarea'
    );
    $_ENV['categories']['internal']['other_cases']['diff_list'] = array (
        'type' => 'special',
        'mandatory' => true,
        'label' => _DIFF_LIST,
        'table' => 'special'
    );

    $_ENV['categories']['ged_doc']['destination'] = array (
        'type_form' => 'string',
        'type_field' => 'string',
        'mandatory' => true,
        'label' => _DEPARTMENT_OWNER,
        'table' => 'res',
        'img' => 'sitemap',
        'modify' => false,
        'form_show' => 'textarea'
    );


}

if ($core->is_module_loaded('folder')) {
    //Folder (incoming)
    $_ENV['categories']['incoming']['other_cases']['folder'] = array (
        'type_form' => 'string',
        'type_field' => 'string',
        'mandatory' => false,
        'label' => _FOLDER,
        'table' => 'none',
        'img' => 'folder',
        'modify' => true,
        'form_show' => 'autocomplete'
    );
    //Folder (outgoing)
    $_ENV['categories']['outgoing']['other_cases']['folder'] = array (
        'type_form' => 'string',
        'type_field' => 'string',
        'mandatory' => false,
        'label' => _FOLDER,
        'table' => 'none',
        'img' => 'folder',
        'modify' => true,
        'form_show' => 'autocomplete'
    );
    //Folder (internal)
    $_ENV['categories']['internal']['other_cases']['folder'] = array (
        'type_form' => 'string',
        'type_field' => 'string',
        'mandatory' => false,
        'label' => _FOLDER,
        'table' => 'none',
        'img' => 'folder',
        'modify' => true,
        'form_show' => 'autocomplete'
    );
    //Folder (folder_document)
    $_ENV['categories']['folder_document']['other_cases']['folder'] = array (
        'type_form' => 'string',
        'type_field' => 'string',
        'mandatory' => false,
        'label' => _FOLDER,
        'table' => 'none',
        'img' => 'folder',
        'modify' => true,
        'form_show' => 'autocomplete'
    );
    //Folder (internal)
    $_ENV['categories']['ged_doc']['other_cases']['folder'] = array (
        'type_form' => 'string',
        'type_field' => 'string',
        'mandatory' => false,
        'label' => _FOLDER,
        'table' => 'none',
        'img' => 'folder',
        'modify' => true,
        'form_show' => 'autocomplete'
    );
}
/************************* END *************************************************************/

/**
* Gets the index of a given res_id in an array
*
* @param $coll_id string  Collection identifier
* @param $res_id string  Resource identifier
* @param $mode string  'full' : fills the array with maximum data (id, value, show_value,etc...), 'minimal' the array contains only the value or 'form" the array contains the data to be displayed in a form
* @param $params array optional parameters
*           $params['img_'.field_id] = true (gets the img url in the 'full' mode array for the field_id), false (no img for the field_id )
* @return Array Structure different in 'full' mode, 'form' and 'minimal' mode
*           mode 'full' : $data[field_id] field_id exemple: 'priority'
*                                       ['value'] : value of the field in the database
*                                       ['show_value'] : value to display
*                                       ['label'] : label to display
*                                       ['display'] : type of display (only 'textinput' and 'textarea' for the moment)
*                                       ['img'] : url of an icon to display (optional)
*           mode 'minimal' : $data[field_id] = value of the field in the database
*           mode 'form' : $data[field_id] field_id exemple: 'priority'
*                                       ['value'] : value of the field in the database
*                                       ['show_value'] : value to display
*                                       ['label'] : label to display
*                                       ['display'] : type of display (only 'textinput' and 'textarea' for the moment)
*                                       ['img'] : url of an icon to display (optional)
*                                       ['readonly'] : true or false
*                                       ['field_type'] : keyword 'date' = date input field with calendar
 *                                                               'textfield' = text input field
 *                                                               'select' = select field
*                                       ['select'] : array of options items (only if field_type = 'select')
*                                               [$i]['id'] : option value
*                                                   ['label'] : option text
*/
function get_general_data($coll_id, $res_id, $mode, $params = array ()) {
    require_once ("core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "class_security.php");
    $sec = new security();
    $view = $sec->retrieve_view_from_coll_id($coll_id);
    if (empty ($view)) {
        $view = $sec->retrieve_table_from_coll($coll_id);
    }
    $db = new Database();
    $stmt = $db->query('SELECT category_id FROM ' . $view . ' WHERE res_id = ?', array($res_id));
    $res = $stmt->fetchObject();
    $cat_id = $res->category_id;
    $fields = '';
    $data = array ();
    $arr = array ();

    // First we load the category_id
    if ($mode == 'full' || 'form') {
        if ($params['img_category_id'] == true) {
            $data['category_id'] = array (
                'value' => $res->category_id,
                'show_value' => $_SESSION['coll_categories']['letterbox_coll'][$res->category_id],
                'label' => _CATEGORY,
                'display' => 'textinput',
                'img' => 'arrows'
            );
        } else {
            $data['category_id'] = array (
                'value' => $res->category_id,
                'show_value' => $_SESSION['coll_categories']['letterbox_coll'][$res->category_id],
                'label' => _CATEGORY,
                'display' => 'textinput'
            );
        }
    } else {
        $data['category_id'] = $res->category_id;
    }
    if (!isset ($cat_id) || empty ($cat_id)) {
        $cat_id = 'empty';
    }
    //Then we browse the $_ENV['categories'] array to get the other indexes
    foreach (array_keys($_ENV['categories'][$cat_id]) as $field) {
        // Normal cases : fields are put in a string to make a query
        if ($field <> 'process_limit_date_use' && $field <> 'other_cases' && $field <> 'type_contact' && $field <> 'img_cat') {
            $fields .= $field . ',';
            if (($mode == 'full' || $mode == 'form') && (!isset ($params['show_' . $field]) || $params['show_' . $field] == true)) {
                if ($params['img_' . $field] == true) {
                    $data[$field] = array (
                        'value' => '',
                        'show_value' => '',
                        'label' => $_ENV['categories'][$cat_id][$field]['label'],
                        'display' => 'textinput',
                        'img' => $_ENV['categories'][$cat_id][$field]['img']
                    );
                } else {
                    $data[$field] = array (
                        'value' => '',
                        'show_value' => '',
                        'label' => $_ENV['categories'][$cat_id][$field]['label'],
                        'display' => 'textinput'
                    );
                }
                array_push($arr, $field);
                if ($field == 'subject' || $field == 'destination') {
                    $data[$field]['display'] = 'textarea';
                }
                $data[$field]['readonly'] = true;
                if ($mode == 'form' && $_ENV['categories'][$cat_id][$field]['modify']) {
                    $data[$field]['readonly'] = false;
                    $data[$field]['field_type'] = $_ENV['categories'][$cat_id][$field]['form_show'];
                    if ($data[$field]['field_type'] == 'select') {
                        $data[$field]['select'] = array ();
                        if ($field == 'type_id') {
                            require_once ("apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "class_types.php");
                            $type = new types();
                            if ($_SESSION['features']['show_types_tree'] == 'true') {
                                $data[$field]['select'] = $type->getArrayStructTypes($coll_id);
                            } else {
                                $data[$field]['select'] = $type->getArrayTypes($coll_id);
                            }
                        } else
                            if ($field == 'destination') {
                                //require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_entities.php");
                                // TO DO : get the entities list
                            } else
                                if ($field == 'nature_id') {
                                    foreach (array_keys($_SESSION['mail_natures']) as $nature) {
                                        array_push($data[$field]['select'], array (
                                            'ID' => $nature,
                                            'LABEL' => $_SESSION['mail_natures'][$nature]
                                        ));
                                    }
                                } else
                                    if ($field == 'priority') {
                                        foreach (array_keys($_SESSION['mail_priorities']) as $prio) {
                                            array_push($data[$field]['select'], array (
                                                'ID' => $prio,
                                                'LABEL' => $_SESSION['mail_priorities'][$prio]
                                            ));
                                        }
                                    }
                    }
                }
            } else
                if ($mode == 'minimal' && (!isset ($params['show_' . $field]) || $params['show_' . $field] == true)) {
                    $data[$field] = '';
                    array_push($arr, $field);
                }
        }
    }
    // Special cases :  fields are put in a string to make a query
        // Special cases :  fields are put in a string to make a query
    //confidentiality
    if (isset ($_ENV['categories'][$cat_id]['confidentiality']) && count($_ENV['categories'][$cat_id]['confidentiality']) > 0 )
    {
        $data['confidentiality'] = array (
                    /*  'value' => '',
                  'show_value' => '',
                    'label' => $_ENV['categories'][$cat_id]['other_cases']['process_limit_date']['label'],
                    'display' => 'textinput',*/
                    'img' => $_ENV['categories'][$cat_id]['confidentiality']['img'],
                    'modify' => true,
                    'label' => $_ENV['categories'][$cat_id]['confidentiality']['label'],
                    'form_show' => 'radio',
                    'field_type' => 'radio',
                    'readonly' => true
                );
                
        // ajout du test suivant pour permettre de rendre le champs non modifiable
        if ($mode == 'form' && $_ENV['categories'][$cat_id]['confidentiality']['modify']) {
                $data['confidentiality']['readonly'] = false;
        }
         $data['confidentiality']['radio'] = array();
         array_push($data['confidentiality']['radio'], array (
                'ID' => 'Y',
                'LABEL' => _YES
        ));
         array_push($data['confidentiality']['radio'], array (
                'ID' => 'N',
                'LABEL' => _NO
        ));
    }
    // Process limit date
    if (isset ($_ENV['categories'][$cat_id]['other_cases']['process_limit_date']) && count($_ENV['categories'][$cat_id]['other_cases']['process_limit_date']) > 0 && (!isset ($params['show_process_limit_date']) || $params['show_process_limit_date'] == true)) {
        $fields .= 'process_limit_date,';
        if ($mode == 'full' || 'form') {
            if ($params['img_process_limit_date'] == true) {
                $data['process_limit_date'] = array (
                    'value' => '',
                    'show_value' => '',
                    'label' => $_ENV['categories'][$cat_id]['other_cases']['process_limit_date']['label'],
                    'display' => 'textinput',
                    'img' => $_ENV['categories'][$cat_id]['other_cases']['process_limit_date']['img'],
                    'modify' => true,
                    'form_show' => 'date',
                    'field_type' => 'date'
                );
            } else {
                $data['process_limit_date'] = array (
                    'value' => '',
                    'show_value' => '',
                    'label' => $_ENV['categories'][$cat_id]['other_cases']['process_limit_date']['label'],
                    'display' => 'textinput'
                );
            }
            $data['process_limit_date']['readonly'] = false;
            if (isset($_ENV['categories'][$cat_id]['process_limit_date']['modify'])
                && $mode == 'form' && $_ENV['categories'][$cat_id]['process_limit_date']['modify']
            ) {
                $data['process_limit_date']['field_type'] = $_ENV['categories'][$cat_id]['process_limit_date']['form_show'];
                $data['process_limit_date']['readonly'] = false;
            }
        } else {
            $data['process_limit_date'] = '';
            $data['process_limit_date_use'] = false;
        }
        array_push($arr, 'process_limit_date');
    }
    // Contact
    if (isset ($_ENV['categories'][$cat_id]['other_cases']['contact']) && count($_ENV['categories'][$cat_id]['other_cases']['contact']) > 0 && (!isset ($params['show_contact']) || $params['show_contact'] == true)) {
        $fields .= $_ENV['categories'][$cat_id]['other_cases']['contact']['special'] . ',';
        if (preg_match('/,/', $_ENV['categories'][$cat_id]['other_cases']['contact']['special'])) {
            $arr_tmp = preg_split('/,/', $_ENV['categories'][$cat_id]['other_cases']['contact']['special']);
        } else {
            $arr_tmp = array (
                $_ENV['categories'][$cat_id]['other_cases']['contact']['special']
            );
        }
        for ($i = 0; $i < count($arr_tmp); $i++) {
            if ($mode == 'full' || $mode == 'form') {
                $data[$arr_tmp[$i]] = array (
                    'value' => '',
                    'show_value' => '',
                    'label' => $_ENV['categories'][$cat_id]['other_cases']['contact']['label'],
                    'display' => 'textarea',
					'img' => $_ENV['categories'][$cat_id]['other_cases']['contact']['img'],
                );
                $data[$arr_tmp[$i]]['readonly'] = true;
                if (isset($_ENV['categories'][$cat_id][$arr_tmp[$i]]['modify'])
                    && $mode == 'form' && $_ENV['categories'][$cat_id][$arr_tmp[$i]]['modify']
                ) {
                    $data[$arr_tmp[$i]]['field_type'] = $_ENV['categories'][$cat_id][$arr_tmp[$i]]['form_show'];
                    $data[$arr_tmp[$i]]['readonly'] = false;
                }
            }
            array_push($arr, $arr_tmp[$i]);
        }

        if ($mode == 'minimal') {
            $data['contact'] = '';
        }

    }
    // Folder
    if (isset ($_ENV['categories'][$cat_id]['other_cases']['folder']) && count($_ENV['categories'][$cat_id]['other_cases']['folder']) > 0 && (!isset ($params['show_folder']) || $params['show_folder'] == true)) {
        $fields .= 'folders_system_id,';


        array_push($arr, 'folder');
        if ($mode == 'full' || $mode == 'form') {
            if ($params['img_folder'] == true) {
                $data['folder'] = array(
                    'value' => '',
                    'show_value' => '',
                    'label' => $_ENV['categories'][$cat_id]['other_cases']['folder']['label'],
                    'display' => 'textinput',
                    'img' => $_ENV['categories'][$cat_id]['other_cases']['folder']['img']
                );
            } else {
                $data['folder'] = array(
                    'value' => '',
                    'show_value' => '',
                    'label' => $_ENV['categories'][$cat_id]['other_cases']['folder']['label'],
                    'display' => 'textinput'
                );
            }
            $data['folder']['readonly'] = true;
            if ($mode == 'form' && $_ENV['categories'][$cat_id]['other_cases']['folder']['modify']) {
                $data['folder']['field_type'] = $_ENV['categories'][$cat_id]['other_cases']['folder']['form_show'];
                $data['folder']['readonly'] = false;
            }
        } else {
            $data['folder'] = '';
        }
    }

    if ($mode == 'full' || $mode == 'form') {
        $fields = preg_replace('/,$/', ',type_label', $fields);
    } else {
        $fields = preg_replace('/,$/', '', $fields);
    }
    // Query
    $stmt = $db->query("SELECT category_id," . $fields . " FROM " . $view . " WHERE res_id = ?", array($res_id));

    $line = $stmt->fetchObject();
    // We fill the array with the query result
    for ($i = 0; $i < count($arr); $i++) {
        if ($mode == 'full' || $mode == 'form') {
            // Normal Cases
            if (isset($line->{$arr[$i]})) {
                $data[$arr[$i]]['value'] = $line->{$arr[$i]};
            }
            if ($arr[$i] <> 'folder') {
                $data[$arr[$i]]['show_value'] = functions::show_string($data[$arr[$i]]['value']);
            }
            if (isset($_ENV['categories'][$cat_id][$arr[$i]]['type_field'])
                && $_ENV['categories'][$cat_id][$arr[$i]]['type_field'] == 'date'
            ) {
                $data[$arr[$i]]['show_value'] = functions::format_date_db($line->{$arr[$i]}, false);
            } else if (isset($_ENV['categories'][$cat_id]['other_cases'][$arr[$i]]['type_field'])
                && $_ENV['categories'][$cat_id]['other_cases'][$arr[$i]]['type_field'] == 'date'
            ) {
                $data[$arr[$i]]['show_value'] = functions::format_date_db($line->{$arr[$i]}, false);
            } else if (isset($_ENV['categories'][$cat_id][$arr[$i]]['type_field'])
                && $_ENV['categories'][$cat_id][$arr[$i]]['type_field'] == 'string'
            ) {
                $data[$arr[$i]]['show_value'] = functions::show_string($line->{$arr[$i]}, true, '', '', false);
            }
            // special cases :
            if ($arr[$i] == 'priority') {
                $data[$arr[$i]]['show_value'] = $_SESSION['mail_priorities'][$line->{$arr[$i]}];
            }
            elseif ($arr[$i] == 'destination') {
                $stmt2 = $db->query('SELECT entity_label FROM ' . $_SESSION['tablename']['ent_entities'] . " WHERE entity_id = ?", array($line->{$arr[$i]}));
                if ($stmt2->rowCount() == 1) {
                    $res2 = $stmt2->fetchObject();
                    $data[$arr[$i]]['show_value'] = functions::show_string($res2->entity_label, true);
                }
            }
            elseif ($arr[$i] == 'nature_id') {
                $data[$arr[$i]]['show_value'] = $_SESSION['mail_natures'][$line->{$arr[$i]}];
            }
            elseif ($arr[$i] == 'reference_number') {
                if (empty ($line->{$arr[$i]})) {
                    unset ($data[$arr[$i]]);
                }
            }
            elseif ($arr[$i] == 'type_id') {
                $data[$arr[$i]]['show_value'] = functions::show_string($line->type_label);
            }
            // Contact
            elseif ($arr[$i] == 'dest_user_id' || $arr[$i] == 'exp_user_id') {
                if (!empty ($line->{$arr[$i]})) {
                    $stmt2 = $db->query('SELECT lastname, firstname FROM ' . $_SESSION['tablename']['users'] . " WHERE user_id = ?", array($line->{$arr[$i]}));
                    $res = $stmt2->fetchObject();
                    $data[$arr[$i]]['show_value'] = $res->lastname . ' ' . $res->firstname;
                    $data[$arr[$i]]['addon'] = '<a href="#" id="contact_card" title="' . _CONTACT_CARD . '" onclick="window.open(\'' . $_SESSION['config']['businessappurl'] . 'index.php?display=true&page=user_info&id=' . $line->{$arr[$i]} . '\', \'contact_info\', \'height=400, width=600,scrollbars=yes,resizable=yes\');" ><i class="fa fa-book fa-2x" title="' . _CONTACT_CARD . '"></i></a>';
                } else {
                    unset ($data[$arr[$i]]);
                }
            }
            elseif ($arr[$i] == 'dest_contact_id' || $arr[$i] == 'exp_contact_id') {
                if (!empty ($line->{$arr[$i]})) {
                    $stmt2 = $db->query("SELECT address_id FROM mlb_coll_ext WHERE res_id = ?", array($res_id));
                    $resAddress = $stmt2->fetchObject();
                    $addressId = $resAddress->address_id;
                    $stmt2 = $db->query('SELECT is_corporate_person, is_private, contact_lastname, contact_firstname, society, society_short, contact_purpose_id, address_num, address_street, address_postal_code, address_town, lastname, firstname FROM view_contacts WHERE contact_id = ? and ca_id = ?', array($line->{$arr[$i]}, $addressId));
                    $res = $stmt2->fetchObject();
                    if ($res->is_corporate_person == 'Y') {
                        $data[$arr[$i]]['show_value'] = $res->society . ' ' ;
                        if (!empty ($res->society_short)) {
                            $data[$arr[$i]]['show_value'] .= '('.$res->society_short.') ';
                        }
                    } else {
                        $data[$arr[$i]]['show_value'] = $res->contact_lastname . ' ' . $res->contact_firstname . ' ';
                        if (!empty ($res->society)) {
                            $data[$arr[$i]]['show_value'] .= '(' .$res->society . ') ';
                        }                        
                    }
                    if ($res->is_private == 'Y') {
                        $data[$arr[$i]]['show_value'] .= '('._CONFIDENTIAL_ADDRESS.')';
                    } else {
                        require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
                        $contact = new contacts_v2();
                        $data[$arr[$i]]['show_value'] .= '- ' . $contact->get_label_contact($res->contact_purpose_id, $_SESSION['tablename']['contact_purposes']).' : ';
                        if (!empty($res->lastname) || !empty($res->firstname)) {
                            $data[$arr[$i]]['show_value'] .= $res->lastname . ' ' . $res->firstname . ' ';
                        }
                        if (!empty($res->address_num) || !empty($res->address_street) || !empty($res->address_town) || !empty($res->address_postal_code)) {
                            $data[$arr[$i]]['show_value'] .= ', '.$res->address_num .' ' . $res->address_street .' ' . $res->address_postal_code .' ' . strtoupper($res->address_town);
                        }         
                    }
                    $data[$arr[$i]]['addon'] = '<a href="#" id="contact_card" title="' . _CONTACT_CARD . '" onclick="window.open(\'' . $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=my_contacts&page=info_contact_iframe&mode=view&popup&contactid=' . $line->{$arr[$i]} . '&addressid='.$addressId.'\', \'contact_info\', \'height=800, width=1000,scrollbars=yes,resizable=yes\');" ><i class="fa fa-book fa-2x" title="' . _CONTACT_CARD . '"></i></a>';
                } else {
                    unset ($data[$arr[$i]]);
                }
            }
            // Folder
            elseif ($arr[$i] == 'folder' && isset($line->folders_system_id) && $line->folders_system_id <> '') {
                $stmt2 = $db->query('SELECT folder_id, folder_name, subject, folders_system_id, parent_id FROM ' 
                    . $_SESSION['tablename']['fold_folders'] 
                    . " WHERE status <> 'FOLDDEL' and folders_system_id = ?", 
                     array($line->folders_system_id));

                if ($stmt2->rowCount() > 0) {
                    $res = $stmt2->fetchObject();
                    $data['folder']['show_value'] = $res->folder_id . ', ' . $res->folder_name . ' (' . $res->folders_system_id . ')';
                }
            }
        } else // 'mimimal' mode
            {
            // Normal cases
            $data[$arr[$i]] = $line->{$arr[$i]};
            if ($_ENV['categories'][$cat_id][$arr[$i]]['type_field'] == 'date') {
                $data[$arr[$i]] = functions::format_date_db($line->{$arr[$i]}, false);
            }
            elseif ($_ENV['categories'][$cat_id]['other_cases'][$arr[$i]]['type_field'] == 'date') {
                $data[$arr[$i]] = functions::format_date_db($line->{$arr[$i]}, false);
            }
            elseif ($_ENV['categories'][$cat_id][$arr[$i]]['type_field'] == 'string') {
                $data[$arr[$i]] = functions::show_string($line->{$arr[$i]}, true, '', '', false);
            }
            // special cases :
            // Contact
            if ($arr[$i] == 'dest_user_id' || $arr[$i] == 'exp_user_id') {
                if (!empty ($line->{$arr[$i]})) {
                    $data['type_contact'] = 'internal';
                    $stmt2 = $db->query('SELECT lastname, firstname FROM ' . $_SESSION['tablename']['users'] . " WHERE user_id = ?", array($line->{$arr[$i]}));
                    $res = $stmt2->fetchObject();
                    $data['contact'] = $res->lastname . ' ' . $res->firstname;
                    $data['contactId'] = $line->{$arr[$i]};
                }
                unset ($data[$arr[$i]]);

            }
            elseif ($arr[$i] == 'dest_contact_id' || $arr[$i] == 'exp_contact_id') {
                if (!empty ($line->{$arr[$i]})) {
                    $data['type_contact'] = 'external';
                    $stmt2 = $db->query("SELECT address_id FROM mlb_coll_ext WHERE res_id = ?", array($res_id));
                    $resAddress = $stmt2->fetchObject();
                    $addressId = $resAddress->address_id;
                    $stmt2 = $db->query('SELECT is_corporate_person, is_private, contact_lastname, contact_firstname, society, society_short, contact_purpose_id, address_num, address_street, address_postal_code, address_town, lastname, firstname FROM view_contacts WHERE contact_id = ? and ca_id = ?', array($line->{$arr[$i]}, $addressId));
                    $res = $stmt2->fetchObject();
                    if ($res->is_corporate_person == 'Y') {
                        $data['contact'] = $res->society . ' ' ;
                        if (!empty ($res->society_short)) {
                            $data['contact'] .= '('.$res->society_short.') ';
                        }
                    } else {
                        $data['contact'] = $res->contact_lastname . ' ' . $res->contact_firstname . ' ';
                        if (!empty ($res->society)) {
                            $data['contact'] .= '(' .$res->society . ') ';
                        }                        
                    }
                    if ($res->is_private == 'Y') {
                        $data['contact'] .= '('._CONFIDENTIAL_ADDRESS.')';
                    } else {
                        require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
                        $contact = new contacts_v2();
                        $data['contact'] .= '- ' . $contact->get_label_contact($res->contact_purpose_id, $_SESSION['tablename']['contact_purposes']).' : ';
                        if (!empty($res->lastname) || !empty($res->firstname)) {
                            $data['contact'] .= $res->lastname . ' ' . $res->firstname . ' ';
                        }
                        if (!empty($res->address_num) || !empty($res->address_street) || !empty($res->address_town) || !empty($res->address_postal_code)) {
                            $data['contact'] .= ', '.$res->address_num .' ' . $res->address_street .' ' . $res->address_postal_code .' ' . strtoupper($res->address_town);
                        }         
                    }
                    $data['contactId'] = $line->{$arr[$i]};
                    $data['addressId'] = $addressId;
                }
                unset ($data[$arr[$i]]);
                
            } else if ($arr[$i] == 'is_multicontacts') {
                if (!empty ($line->{$arr[$i]})) {
                    $data['type_contact'] = 'multi_external';
                }
                unset ($data[$arr[$i]]);			
			
			}
            // Folder
            elseif ($arr[$i] == 'folder' && isset($line->folders_system_id) && $line->folders_system_id <> '' ) {
                $stmt2 = $db->query('SELECT folder_id, folder_name, subject, folders_system_id, parent_id FROM ' 
                . $_SESSION['tablename']['fold_folders'] 
                . " WHERE status <> 'FOLDDEL' and folders_system_id = ?", 
                    array($line->folders_system_id));

                $res = $stmt2->fetchObject();
                $data['folder'] = $res->folder_id . ', ' . $res->folder_name . ' (' . $res->folders_system_id . ')';
            }
        }
    }

    if ($mode == 'minimal' && isset ($data['process_limit_date']) && !empty ($data['process_limit_date'])) {
        $data['process_limit_date_use'] = true;
    }
    
    //print_r($data);
    foreach(array_keys($data) as $key) {
        if (is_array($data[$key])) {
            $data[$key]['value'] = functions::xssafe($data[$key]['value']);
            $data[$key]['show_value'] = functions::xssafe($data[$key]['show_value']);
        } else {
            $data[$key] = functions::xssafe($data[$key]);
        }
        
    }
                        
    return $data;
}

/**
 * Returns the icon for the given category or the default icon
 *
 * @param $cat_id string Category identifiert
 * @return string Icon Url
 **/
function get_img_cat($cat_id) {
    $default = '<i class="fa fa-remove fa-2x"></i>';
    if (empty ($cat_id)) {

        return $default;
    } else {
        if (!empty ($_ENV['categories'][$cat_id]['img_cat'])) {
            return $_ENV['categories'][$cat_id]['img_cat'];
        } else {
            return $default;
        }
    }
}
?>
