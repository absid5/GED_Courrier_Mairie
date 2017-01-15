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
* @brief  Contains the controler of template object 
* (create, save, modify, etc...)
* 
* 
* @file
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup templates
*/

// To activate de debug mode of the class
$_ENV['DEBUG'] = false;

// Loads the required class
try {
    require_once ('modules/templates/class/templates.php');
    require_once ('modules/templates/templates_tables_definition.php');
    require_once ('core/class/ObjectControlerAbstract.php');
    require_once ('core/class/ObjectControlerIF.php');
    require_once ('core/class/SecurityControler.php');
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
* @brief  Controler of the templates object 
*
*<ul>
*  <li>Get an templates object from an id</li>
*  <li>Save in the database a templates</li>
*  <li>Manage the operation on the templates related tables in the database 
*  (insert, select, update, delete)</li>
*</ul>
* @ingroup templates
*/
abstract class templates_controler_Abstract extends ObjectControler implements ObjectControlerIF
{
    
    protected $stylesArray = array();
    
    /**
    * Save given object in database:
    * - make an update if object already exists,
    * - make an insert if new object.
    * @param object $template
    * @param string mode up or add
    * @return array
    */
    public function save($template, $mode='') 
    {
        $control = array();
        if (!isset($template) || empty($template)) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _TEMPLATE_ID_EMPTY,
            );
            return $control;
        }
        $template = $this->isATemplate($template);
        $this->set_foolish_ids(array('template_id'));
        $this->set_specific_id('template_id');
        if ($mode == 'up') {
            $control = $this->control($template, $mode);
            $this->set_foolish_ids(array('template_id'));
            $this->set_specific_id('template_id');
            if ($control['status'] == 'ok') {
                $template = $control['value'];
                if ($template->template_file_name <> '') {
                    unlink($_SESSION['m_admin']['templates']['current_style']);
                }
                //var_dump($this);exit;
                //Update existing template
                if ($this->update($template)) {
                    $control = array(
                        'status' => 'ok', 
                        'value' => $template->template_id,
                    );
                    $this->updateTemplateEntityAssociation($template->template_id);
                    //history
                    if ($_SESSION['history']['templateadd'] == 'true') {
                        $history = new history();
                        $history->add(
                            _TEMPLATES_TABLE_NAME, 
                            $template->template_id, 
                            'UP', 'templateadd',
                            _TEMPLATES_UPDATED.' : '.$template->template_id, 
                            $_SESSION['config']['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        'status' => 'ko', 
                        'value' => '', 
                        'error' => _PB_WITH_TEMPLATE,
                    );
                }
                return $control;
            }
        } else {
            $control = $this->control($template, 'add');
            if ($control['status'] == 'ok') {
                $template = $control['value'];
                if ($template->template_file_name <> '') {
                    unlink($_SESSION['m_admin']['templates']['current_style']);
                }
                //Insert new template
                if ($this->insert($template)) {
                    $templateId = $this->getLastTemplateId($template->template_label);
                    $control = array(
                        'status' => 'ok', 
                        'value' => $templateId,
                    );
                    $this->updateTemplateEntityAssociation($templateId);
                    //history
                    if ($_SESSION['history']['templateadd'] == 'true') {
                        $history = new history();
                        $history->add(
                            _TEMPLATES_TABLE_NAME, 
                            $templateId, 
                            'ADD', 'templateadd',
                            _TEMPLATES_ADDED . ' : ' . $templateId, 
                            $_SESSION['config']['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        'status' => 'ko', 
                        'value' => '', 
                        'error' => _PB_WITH_TEMPLATE,
                    );
                }
            }
        }
        return $control;
    }

    /**
    * control the template object before action
    *
    * @param  object $template template object
    * @param  string $mode up or add
    * @return array ok if the object is well formated, ko otherwise
    */
    protected function control($template, $mode) 
    {
        $f = new functions();
        $sec = new SecurityControler();
        $error = '';


        // $template->template_label = $f->protect_string_db(
        //     $f->wash($template->template_label, 'no', _TEMPLATE_LABEL.' ', 'yes', 0, 255)
        // );
        // $template->template_comment = $f->protect_string_db(
        //     $f->wash($template->template_comment, 'no', _TEMPLATE_COMMENT.' ', 'yes', 0, 255)
        // );


        $template->template_label = $f->wash($template->template_label, 'no', _TEMPLATE_LABEL.' ', 'yes', 0, 255);
        $template->template_comment = $f->wash($template->template_comment, 'no', _TEMPLATE_COMMENT.' ', 'yes', 0, 255);

        
        $template->template_content = str_replace(';', '###', $template->template_content);        
        $template->template_content = str_replace('--', '___', $template->template_content); 
        $allowedTags = '<html><head><body><title>'; //Structure
        $allowedTags .= '<h1><h2><h3><h4><h5><h6><b><i><tt><u><strike><blockquote><pre><blink><font><big><small><sup><sub><strong><em>'; // Text formatting
        $allowedTags .='<p><br><hr><center><div><span>'; // Text position
        $allowedTags .= '<li><ol><ul><dl><dt><dd>'; // Lists
        $allowedTags .= '<img><a>'; // Multimedia
        $allowedTags .= '<table><tr><td><th><tbody><thead><tfooter><caption>'; // Tables
        $allowedTags .= '<form><input><textarea><select>'; // Forms
        $template->template_content = strip_tags($template->template_content, $allowedTags);
        $template->template_content = $f->protect_string_db($template->template_content);
        
        $template->template_type = $f->protect_string_db(
            $f->wash($template->template_type, 'no', _TEMPLATE_TYPE.' ', 'yes', 0, 32)
        );
        $template->template_style = $f->protect_string_db(
            $f->wash($template->template_style, 'no', _TEMPLATE_STYLE.' ', 'no', 0, 255)
        );
        if ($mode == 'add' && $this->templateExists($template->template_id)) {
            $error .= $template->template_id.' '._ALREADY_EXISTS.'#';
        }
        $template->template_target = $f->protect_string_db(
            $f->wash($template->template_target, 'no', _TEMPLATE_TARGET.' ', 'no', 0, 255)
        );
        $error .= $_SESSION['error'];
        //TODO:rewrite wash to return errors without html
        $error = str_replace('<br />', '#', $error);
        $return = array();
        if (!empty($error)) {
            $return = array(
                'status' => 'ko', 
                'value' => $template, 
                'error' => $error,
            );
        } else {
            if ($template->template_type == 'OFFICE') {
                if (
                    $mode == 'add' 
                    && !$_SESSION['m_admin']['templates']['applet']
                ) {
                    $return = array(
                        'status' => 'ko', 
                        'value' => $template, 
                        'error' => _EDIT_YOUR_TEMPLATE,
                    );
                    return $return;
                }
                if (
                    ($mode == 'up' || $mode == 'add') 
                    && $_SESSION['m_admin']['templates']['applet']
                ) {
                    $storeInfos = array();
                    $storeInfos = $this->storeTemplateFile();
                    if (!$storeInfos) {
                        $return = array(
                            'status' => 'ko', 
                            'value' => $template, 
                            'error' => $_SESSION['error'],
                        );
                    } else {
                        //print_r($storeInfos);exit;
                        $template->template_path = $storeInfos['destination_dir'];
                        $template->template_file_name = $storeInfos['file_destination_name'];
                        $return = array(
                            'status' => 'ok', 
                            'value' => $template,
                        );
                    }
                } else {
                    $return = array(
                        'status' => 'ok',
                        'value' => $template,
                    );
                }
            } else {
                $return = array(
                    'status' => 'ok', 
                    'value' => $template,
                );
            }
        }
        return $return;
    }

    /**
    * Inserts in the database (templates table) a templates object
    *
    * @param  $template templates object
    * @return bool true if the insertion is complete, false otherwise
    */
    protected function insert($template) 
    {
        return $this->advanced_insert($template);
    }

    /**
    * Updates in the database (templates table) a templates object
    *
    * @param  $template templates object
    * @return bool true if the update is complete, false otherwise
    */
    protected function update($template) 
    {
        return $this->advanced_update($template);
    }

    /**
    * Returns an templates object based on a templates identifier
    *
    * @param  $template_id string  templates identifier
    * @param  $comp_where string  where clause arguments 
    * (must begin with and or or)
    * @param  $can_be_disabled bool  if true gets the template even if it is 
    * disabled in the database (false by default)
    * @return templates object with properties from the database or null
    */
    public function get($template_id, $comp_where='', $can_be_disabled=false) 
    {
        $this->set_foolish_ids(array('template_id'));
        $this->set_specific_id('template_id');
        $template = $this->advanced_get($template_id, _TEMPLATES_TABLE_NAME);
        $template->template_content = str_replace('###', ';', $template->template_content);
        $template->template_content = str_replace('___', '--', $template->template_content);
        if (get_class($template) <> 'templates') {
            return null;
        } else {
            //var_dump($template);
            return $template;
        }
    }

    /**
    * get templates with given id for a ws.
    * Can return null if no corresponding object.
    * @param $template_id of template to send
    * @return template
    */
    public function getWs($template_id) 
    {
        $this->set_foolish_ids(array('template_id'));
        $this->set_specific_id('template_id');
        $template = $this->advanced_get($template_id, _TEMPLATES_TABLE_NAME);
        if (get_class($template) <> 'templates') {
            return null;
        } else {
            $template = $template->getArray();
            return $template;
        }
    }

    /**
    * Deletes in the database (templates related tables) a given 
    * templates (template_id)
    *
    * @param  $template string  templates identifier
    * @return bool true if the deletion is complete, false otherwise
    */
    public function delete($template) 
    {
        $control = array();
        if (!isset($template) || empty($template)) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _TEMPLATES_EMPTY,
            );
            return $control;
        }
        $template = $this->isATemplate($template);
        if (!$this->templateExists($template->template_id)) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _TEMPLATES_NOT_EXISTS,
            );
            return $control;
        }
        if ($this->linkExists($template->template_id)) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _LINK_EXISTS,
            );
            return $control;
        }
        $db = new Database();
        $query = "delete from "._TEMPLATES_TABLE_NAME." where template_id = ? " ;
            
        try {
            //
            $stmt = $db->query($query, array($template->template_id));
            $ok = true;
        } catch (Exception $e) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _CANNOT_DELETE_TEMPLATE_ID.' '.$template->template_id,
            );
            $ok = false;
        }
        $control = array(
            'status' => 'ok', 
            'value' => $template->template_id,
        );
        if ($_SESSION['history']['templatedel'] == 'true') {
            require_once('core/class/class_history.php');
            $history = new history();
            $history->add(
                _TEMPLATES_TABLE_NAME, $template->template_id, 'DEL', 'templatedel',
                _TEMPLATES_DELETED.' : '.$template->template_id, 
                $_SESSION['config']['databasetype']
            );
        }
        return $control;
    }

    /**
    * Disables a given templates
    * 
    * @param  $template templates object 
    * @return bool true if the disabling is complete, false otherwise 
    */
    public function disable($template) 
    {
        //
    }

    /**
    * Enables a given templates
    * 
    * @param  $template templates object  
    * @return bool true if the enabling is complete, false otherwise 
    */
    public function enable($template) 
    {
        //
    }

    /**
    * Fill a template object with an object if it's not a template
    *
    * @param  $object ws template object
    * @return object template
    */
    protected function isATemplate($object) 
    {
        if (get_class($object) <> 'templates') {
            $func = new functions();
            $templateObject = new templates();
            $array = array();
            $array = $func->object2array($object);
            foreach (array_keys($array) as $key) {
                $templateObject->{$key} = $array[$key];
            }
            return $templateObject;
        } else {
            return $object;
        }
    }

    /**
    * Checks if the template exists
    * 
    * @param $template_id templates identifier
    * @return bool true if the template exists
    */
    public function templateExists($template_id) 
    {
        if (!isset ($template_id) || empty ($template_id)) {
            return false;
        }
        $db = new Database();
        
        $query = "select template_id from " . _TEMPLATES_TABLE_NAME 
            . " where template_id = ? ";
        try {
            $stmt = $db->query($query, array($template_id));
        } catch (Exception $e) {
            echo _UNKNOWN . _TEMPLATES . ' ' . $template_id . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
    * Checks if the template is linked 
    * 
    * @param $template_id templates identifier
    * @return bool true if the template is linked
    */
    public function linkExists($template_id) 
    {
        if (!isset($template_id) || empty($template_id)) {
            return false;
        }
        $db = new Database();

        $query = "select template_id from " . _TEMPLATES_DOCTYPES_EXT_TABLE_NAME
            . " where template_id = ? ";
        $stmt = $db->query($query, array($template_id));
        if ($stmt->rowCount() > 0) {
            return true;
        }
    }
    
    /**
    * Return the last templateId
    * 
    * @return bigint templateId
    */
    public function getLastTemplateId($templateLabel)
    {
        $db = new Database();
        $query = "select template_id from " . _TEMPLATES_TABLE_NAME
            . " where template_label = ? "
            . " order by template_id desc";
        $stmt = $db->query($query, array($templateLabel));
        $queryResult = $stmt->fetchObject();
        return $queryResult->template_id;
    }

    /**
    * Return all templates ID
    * 
    * @return array of templates
    */
    public function getAllId($can_be_disabled = false) 
    {
        $db = new Database();
        $query = "select template_id from " . _TEMPLATES_TABLE_NAME . " ";
        if (!$can_be_disabled) {
            $query .= " where enabled = 'Y'";
        }
        try {
            //
            $stmt = $db->query($query);
        } catch (Exception $e) {
            echo _NO_TEMPLATES . ' // ';
        }
        if ($db->rowCount() > 0) {
            $result = array();
            $cptId = 0;
            while ($queryResult = $stmt->fetchObject()) {
                $result[$cptId] = $queryResult->template_id;
                $cptId++;
            }
            return $result;
        } else {
            return null;
        }
    }
    
    /**
    * Return all templates in an array
    * 
    * @return array of templates
    */
    public function getAllTemplatesForSelect() {
        $return = array();
        
        $db = new Database();
        $stmt = $db->query("select * from " . _TEMPLATES_TABLE_NAME . " ");
        
        while ($result = $stmt->fetchObject()) {
            $this_template = array();
            $this_template['ID'] = $result->template_id;
            $this_template['LABEL'] = $result->template_label;
            $this_template['COMMENT'] = $result->template_comment;
            $this_template['TYPE'] = $result->template_type;
            $this_template['TARGET'] = $result->template_target;
            array_push($return, $this_template);
        }
        
        return $return;
    }
    
    /**
    * Return all templates in an array for an entity
    * 
    * @param $entityId entity identifier
    * @return array of templates
    */
    public function getAllTemplatesForProcess($entityId) 
    {
        $db = new Database();
        $stmt = $db->query('select * from ' . _TEMPLATES_TABLE_NAME . ' t, ' . _TEMPLATES_ASSOCIATION_TABLE_NAME . ' ta '
                . 'where t.template_id = ta.template_id and ta.what = ? and ta.value_field = ? ORDER BY t.template_label',
                ['destination', $entityId]
        );
        $templates = [];
        while ($res = $stmt->fetchObject()) {
            array_push(
                $templates, array(
                    'ID' => $res->template_id, 
                    'LABEL' => $res->template_label,
                    'TYPE' => $res->template_type,
                    'TARGET' => $res->template_target,
                    'ATTACHMENT_TYPE' => $res->template_attachment_type,
                )
            );
        }
        return $templates;
    }
    
    public function updateTemplateEntityAssociation($templateId)
    {
		
        $db = new Database();
        $db->query("delete from " . _TEMPLATES_ASSOCIATION_TABLE_NAME 
            . " where template_id = ? and what = 'destination' ", array($templateId)
        );
       
        for ($i=0;$i<count($_SESSION['m_admin']['templatesEntitiesSelected']);$i++) {
            $db->query("insert into " . _TEMPLATES_ASSOCIATION_TABLE_NAME 
                . " (template_id, what, value_field, maarch_module) VALUES (?, 'destination', ? , 'entities')", 
                array($templateId, $_SESSION['m_admin']['templatesEntitiesSelected'][$i])
                
            ); 
        }
    }
    
    public function getAllItemsLinkedToModel($templateId, $field ='')
    {
        $db = new Database();
        $items = array();
        if (empty($templateId)) {
            return $items;
        }
        if (empty($field)) {
            $stmt = $db->query("select distinct what from " 
                . _TEMPLATES_ASSOCIATION_TABLE_NAME
                . " where template_id = ? ", array($templateId)
            );
            while ($res = $stmt->fetchObject()) {
                $items[$res->what] = array();
            }
            foreach (array_keys($items) as $key) {
                $stmt = $db->query("select value_field from " 
                    . _TEMPLATES_ASSOCIATION_TABLE_NAME 
                    . " where template_id = ? and what = ? ", array($templateId, $key)
                    );
                $items[$key] = array();
                while ($res = $stmt->fetchObject()) {
                    array_push($items[$key], $res->value_field);
                }
            }
        } else {
            $items[$field] = array();
            $stmt = $db->query("select value_field from " 
                . _TEMPLATES_ASSOCIATION_TABLE_NAME 
                . " where template_id = ? and what = ? ", array($templateId, $field)
            );
            while ($res = $stmt->fetchObject()) {
                array_push($items[$field], $res->value_field);
            }
        }
        return $items;
    }
    
    public function getTemplatesStyles($dir, $stylesArray)
    {
        $this->stylesArray = $stylesArray;
        //Browse all files of the style template dir
        $classScan = dir($dir);
        while (($filescan = $classScan->read()) != false) {
            if ($filescan == '.' || $filescan == '..' || $filescan == '.svn') {
                continue;
            } elseif (is_dir($dir . $folder . $filescan)) {
                $this->getTemplatesStyles($dir . $folder . $filescan . '/', $this->stylesArray);
            } else {
                $filePath = $dir . $folder . '/' . $filescan;
                $info = pathinfo($filePath);
                array_push(
                    $this->stylesArray, 
                    array(
                        'fileName' => basename($filePath, '.' . $info['extension']),
                        'fileExt'  => strtoupper($info['extension']),
                        'filePath' => $filePath,
                    )
                );
            }
        }
        return $this->stylesArray;
    }
    
    public function getTemplatesDatasources($configXml) 
    {
        $datasources = array();
        //Browse all files of the style template dir
        $xmlcontent = simplexml_load_file($configXml);
        foreach($xmlcontent->datasource as $datasource) {
            //<id> <label> <script>    
            if(@constant((string) $datasource->label)) {
                $label = constant((string)$datasource->label);
            } else {
                $label = (string) $datasource->label;
            }
            array_push(
                $datasources, 
                array(
                    'id' => (string)$datasource->id,
                    'label'  => $label,
                    'script' => (string)$datasource->script,
                )
            );
        }
        return $datasources;
    }
    
    public function getTemplatesTargets() 
    {
        $targets = array();
        //attachments
        array_push(
            $targets, 
            array(
                'id' => 'attachments',
                'label'  => _ATTACHMENTS,
            )
        );
        //notifications
        array_push(
            $targets, 
            array(
                'id' => 'notifications',
                'label'  => _NOTIFICATIONS,
            )
        );
        //doctypes
        array_push(
            $targets, 
            array(
                'id' => 'doctypes',
                'label'  => _DOCTYPES,
            )
        );
        //notes
        array_push(
            $targets, 
            array(
                'id' => 'notes',
                'label'  => _NOTES,
            )
        );
        //sendmail
         array_push(
             $targets, 
             array(
                 'id' => 'sendmail',
                 'label'  => _SENDMAIL,
             )
         );
        return $targets;
    }
    
    //returns file ext
    function extractFileExt($sFullPath) {
        $sName = $sFullPath;
        if (strpos($sName, '.')==0) {
            $ExtractFileExt = '';
        } else {
            $ExtractFileExt = explode ('.', $sName);
        }
        return end($ExtractFileExt);
    }
    
    function storeTemplateFile() {
        if (!$_SESSION['m_admin']['templates']['applet']) {
            $tmpFileName = 'cm_tmp_file_' . $_SESSION['user']['UserId']
                . '_' . rand() . '.' 
                . strtolower(
                    $this->extractFileExt(
                        $_SESSION['m_admin']['templates']['current_style']
                    )
                );
            $tmpFilePath = $_SESSION['config']['tmppath'] . $tmpFileName;
            if (!copy(
                    $_SESSION['m_admin']['templates']['current_style'],
                    $tmpFilePath
                )
            ) {
                $_SESSION['error'] = _PB_TO_COPY_STYLE_ON_TMP . ' ' . $tmpFilePath;
                return false;
            } else {
                $_SESSION['m_admin']['templates']['current_style'] = $tmpFilePath;
            }
        }
        if ($_SESSION['m_admin']['templates']['current_style'] == '') {
            $_SESSION['error'] = _SELECT_A_TEMPLATE_STYLE;
            return false;
        } else {
            if (file_exists($_SESSION['m_admin']['templates']['current_style'])) {
                $storeInfos = array();
                $fileName = basename(
                    $_SESSION['m_admin']['templates']['current_style']
                );
                $fileSize = filesize(
                    $_SESSION['m_admin']['templates']['current_style']
                );
                $fileExtension = $this->extractFileExt(
                    $_SESSION['m_admin']['templates']['current_style']
                );
                include_once 'core/class/docservers_controler.php';
                $docservers_controler = new docservers_controler();
                $fileTemplateInfos = array(
                    'tmpDir'      => $_SESSION['config']['tmppath'],
                    'size'        => $fileSize,
                    'format'      => $fileExtension,
                    'tmpFileName' => $fileName,
                );
                $storeInfos = $docservers_controler->storeResourceOnDocserver(
                    'templates',
                    $fileTemplateInfos
                );
                if (!file_exists(
                        $storeInfos['path_template']
                        .  str_replace("#", DIRECTORY_SEPARATOR, $storeInfos['destination_dir'])
                        . $storeInfos['file_destination_name']
                    )
                ) {
                    $_SESSION['error'] = $storeInfos['error'];
                    return false;
                }
                return $storeInfos;
            } else {
                $_SESSION['error'] = 'ERROR : file not exists ' 
                    . $_SESSION['m_admin']['templates']['current_style'];
                return false;
            }
        }
    }
    
     /**
    * Make a copy of template to temp directory for merge process
    *
    * @param object $templateObj : template object
    * @return string $templateCopyPath : path to working copy
    */
    protected function getWorkingCopy($templateObj) {
        
        if ($templateObj->template_type == 'HTML') {
            $fileExtension = 'html';
            $fileNameOnTmp = $_SESSION['config']['tmppath'] . 'tmp_template_' . $_SESSION['user']['UserId']
            . '_' . rand() . '.' . $fileExtension;
            $handle = fopen($fileNameOnTmp, 'w');
            if (fwrite($handle, $templateObj->template_content) === FALSE) {
                return false;
            }
            fclose($handle);
            return $fileNameOnTmp;
        } elseif ($templateObj->template_type == 'TXT') {
            $fileExtension = 'txt';
            $fileNameOnTmp = $_SESSION['config']['tmppath'] . 'tmp_template_' . $_SESSION['user']['UserId']
            . '_' . rand() . '.' . $fileExtension;
            $handle = fopen($fileNameOnTmp, 'w');
            if (fwrite($handle, $templateObj->template_content) === FALSE) {
                return false;
            }
            fclose($handle);
            return $fileNameOnTmp;
        } else {
            $dbTemplate = new Database();
            $query = "select path_template from " . _DOCSERVERS_TABLE_NAME 
                . " where docserver_id = 'TEMPLATES'";
            $stmt = $dbTemplate->query($query);
            $resDs = $stmt->fetchObject();
            $pathToDs = $resDs->path_template;
            $pathToTemplateOnDs = $pathToDs . str_replace(
                    "#", 
                    DIRECTORY_SEPARATOR, 
                    $templateObj->template_path
                )
                . $templateObj->template_file_name;
            
            return $pathToTemplateOnDs;
        }
    }
    
    protected function getDatasourceScript($datasourceId) 
    {
        if ($datasourceId <> '') {
            $xmlfile = 'modules/templates/xml/datasources.xml';
            $xmlfileCustom = $_SESSION['config']['corepath'] 
            . 'custom/' . $_SESSION['custom_override_id'] . '/' . $xmlfile;
             if (file_exists($xmlfileCustom)) {
                $xmlfile = $xmlfileCustom;
            }
            $fulllist = array();
            $fulllist = $this->getTemplatesDatasources($xmlfile);
            foreach ($fulllist as $ds) {
                if ($datasourceId == $ds['id']){
                    return (object)$ds;
                }
            }
        }
        return null;
    }
    
    protected function getBaseDatasources() {
        $datasources = array();
        
        // Date and time
        $datasources['datetime'][0]['date'] = date('d-m-Y');
        $datasources['datetime'][0]['time'] = date('H:i:s.u');
        $datasources['datetime'][0]['timestamp'] = time();
        
        // Session
        if(isset($_SESSION)) {
            // Config (!!! database)
            if(count($_SESSION['config']) > 0) {
                $datasources['config'][0] = $_SESSION['config'];
                $datasources['config'][0]['linktoapp'] = $_SESSION['config']['businessappurl']."index.php";
            }
            
            // Current basket
            if(count($_SESSION['current_basket']) > 0) {
                foreach($_SESSION['current_basket'] as $name => $value) {
                    if(!is_array($value)) {
                        $datasources['basket'][0][$name] = $value;
                    }
                }
            }
            
            // User
            if(count($_SESSION['user']) > 0) {
                foreach($_SESSION['user'] as $name => $value) {
                    if(!is_array($value)) {
                        $datasources['user'][0][strtolower($name)] = $value;
                    }
                }
                if(count($_SESSION['user']['entities']) > 0) {
                    foreach($_SESSION['user']['entities'] as $entity) {
                        if($entity['ENTITY_ID'] === $_SESSION['user']['primaryentity']['id']) {
                            $datasources['user'][0]['entity'] = $_SESSION['user']['entities'][0]['ENTITY_LABEL'];
                            $datasources['user'][0]['role'] = $_SESSION['user']['entities'][0]['ROLE'];
                        }
                    }
                }
            }
            
        }
    
        return $datasources;
    }
    
    
    /** Merge template with data from a datasource to the requested output 
    * 
    * @param string $templateId : templates identifier
    * @param array $params : array of parameters for datasource retrieval
    * @param string $outputType : save to 'file', retrieve 'content'
    * @return merged content or path to file
    */
    public function merge($templateId, $params=array(), $outputType) 
    {
        require_once 'core/class/class_functions.php';
        require_once 'modules/templates/templates_tables_definition.php';
        include_once 'apps/maarch_entreprise/tools/tbs/tbs_class_php5.php';
        include_once 'apps/maarch_entreprise/tools/tbs/tbs_plugin_opentbs.php';

        $templateObj = $this->get($templateId);
        
        // Get template path from docserver or copy HTML template to temp file 
        $pathToTemplate = $this->getWorkingCopy($templateObj);
        
        $datasources = $this->getBaseDatasources();
        // Make params array for datasrouce script
        foreach($params as $paramName => $paramValue) {
            $$paramName = $paramValue;
        }
        //Retrieve script for datasources
        $datasourceObj = $this->getDatasourceScript($templateObj->template_datasource);
        if($datasourceObj->script) {
            require $datasourceObj->script;
        }
        
        // Merge with TBS
        $TBS = new clsTinyButStrong;
        $TBS->NoErr = true;
        if($templateObj->template_type == 'OFFICE') {
            $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
            $TBS->LoadTemplate($pathToTemplate, OPENTBS_ALREADY_UTF8);
        } else {
            $TBS->LoadTemplate($pathToTemplate);
        }
        
        foreach ($datasources as $name => $datasource) {
            // Scalar values or arrays ?
            if(!is_array($datasource)) {
                $TBS->MergeField($name, $datasource);
            } else {
                $TBS->MergeBlock($name, 'array', $datasource);
            }
        }

        if ($ext = strrchr($pathToTemplate, '.')) {
            if ($ext === '.odt')
                $TBS->LoadTemplate('#styles.xml');
            else if ($ext === '.docx')
                $TBS->LoadTemplate('#word/header1.xml');

            foreach ($datasources as $name => $datasource) {
                // Scalar values or arrays ?
                if(!is_array($datasource)) {
                    $TBS->MergeField($name, $datasource);
                } else {
                    $TBS->MergeBlock($name, 'array', $datasource);
                }
            }

            if ($ext === '.docx') {
                $TBS->LoadTemplate('#word/footer1.xml');
                foreach ($datasources as $name => $datasource) {
                    // Scalar values or arrays ?
                    if(!is_array($datasource)) {
                        $TBS->MergeField($name, $datasource);
                    } else {
                        $TBS->MergeBlock($name, 'array', $datasource);
                    }
                }
            }

        }


        switch($outputType) {
        case 'content':
            if($templateObj->template_type == 'OFFICE') {
                $TBS->Show(OPENTBS_STRING);
            } else {
                $TBS->Show(TBS_NOTHING);
            }
            $myContent = $TBS->Source;
            return $myContent;
            
        case 'file':
            $func = new functions();
            $fileExtension = $func->extractFileExt($pathToTemplate);
            $fileNameOnTmp = 'tmp_file_' . $_SESSION['user']['UserId']
            . '_' . rand() . '.' . $fileExtension;
            $myFile = $_SESSION['config']['tmppath'] . $fileNameOnTmp;
            if($templateObj->template_type == 'OFFICE') {
                $TBS->Show(OPENTBS_FILE, $myFile);
            } else {
                $TBS->Show(TBS_NOTHING);
                $myContent = $TBS->Source;
                $handle = fopen($myFile, 'w');
                fwrite($handle, $myContent);
                fclose($handle);
            }
            return $myFile;
        }
    }
    
    /** Copy a template master on tmp dir 
    * 
    * @param string $templateId : templates identifier
    * @return string path of the template in tmp dir
    */
    public function copyTemplateOnTmp($templateId) 
    {
        $templateObj = $this->get($templateId);
        // Get template path from docserver
        $pathToTemplate = $this->getWorkingCopy($templateObj);
        $fileExtension = $this->extractFileExt($pathToTemplate);
        $fileNameOnTmp = 'tmp_file_' . $_SESSION['user']['UserId']
            . '_' . rand() . '.' . $fileExtension;
        $filePathOnTmp = $_SESSION['config']['tmppath'] . $fileNameOnTmp;
        // Copy the template from the DS to the tmp dir
        if (!copy($pathToTemplate, $filePathOnTmp)) {
            return '';
        } else {
            return $filePathOnTmp;
        }
    }
}
