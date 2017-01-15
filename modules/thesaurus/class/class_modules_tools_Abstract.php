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


abstract class thesaurus_Abstract
{
    var $array_thesaurus;

	public function build_modules_tables()
	{
		if (file_exists(
		    $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
		    . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
		    . DIRECTORY_SEPARATOR . "thesaurus" . DIRECTORY_SEPARATOR . "xml"
		    . DIRECTORY_SEPARATOR . "config.xml"
		)
		) {
			$path = $_SESSION['config']['corepath'] . 'custom'
			      . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
			      . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR
			      . "thesaurus" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
			      . "config.xml";
		} else {
			$path = "modules" . DIRECTORY_SEPARATOR . "thesaurus"
			      . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
			      . "config.xml";
		}
		$xmlconfig = simplexml_load_file($path);
	
		$hist = $xmlconfig->HISTORY;
		$_SESSION['history']['tagadd'] = (string) $hist->tagadd;
		$_SESSION['history']['tagup'] = (string) $hist->tagup;
		$_SESSION['history']['tagdel'] = (string) $hist->tagdel;
	}

	public function getList()
	{
		$array_thesaurus = array();
		$db = new Database(); 
        $stmt = $db->query(
            'SELECT * FROM thesaurus'
            . ' ORDER BY thesaurus_name ASC '
            ,$where_what);

        while($thesaurus=$stmt->fetchObject()){
            $array_thesaurus[]=$thesaurus;
        }
        return $array_thesaurus;
	}

    public function countThesaurus()
    {
        $array_thesaurus = array();
        $db = new Database(); 
        $stmt = $db->query(
            'SELECT count(*) as total FROM thesaurus'
            ,array());

        $thesaurus=$stmt->fetchObject();
        return $thesaurus->total;
    }

    public function getInfoThesaurusById($thesaurus_id)
    {
        $arrayPDO = array($thesaurus_id);
        $db = new Database(); 
        $stmt = $db->query(
            'SELECT * FROM thesaurus'
            . ' WHERE thesaurus_id = ? '
            . ' ORDER BY thesaurus_name ASC '
            ,$arrayPDO);

        $thesaurus=$stmt->fetchObject();
        return $thesaurus;
    }

    public function get_by_label($thesaurus_id)
    {
        /*
         * Searching a thesaurus by id
         * @If thesaurus exists, return this value, else, return false
         */
        
        if (empty($thesaurus_id)) {
           
            return null;
        }

        $db = new Database();
        $stmt = $db->query(
            'SELECT thesaurus_name FROM thesaurus'
            . ' WHERE thesaurus_id = ?'
            ,array($thesaurus_id));
      
        $thesaurus_name=$stmt->fetchObject();

        if (isset($thesaurus_name)) {
            return $thesaurus_name->thesaurus_name;
        } else {
            return null;
        }
    }

    public function get_by_id($thesaurus_name)
    {
        /*
         * Searching a thesaurus by name
         * @If thesaurus exists, return this value, else, return false
         */
        
        if (empty($thesaurus_name)) {
           
            return null;
        }

        $db = new Database();
        $stmt = $db->query(
            'SELECT thesaurus_id FROM thesaurus'
            . ' WHERE thesaurus_name = ?'
            ,array($thesaurus_name));
      
        $thesaurus_result=$stmt->fetchObject();

        if (isset($thesaurus_result)) {
            return $thesaurus_result->thesaurus_id;
        } else {
            return null;
        }
    }

    public function getThesaursusListRes($res_id)
    {
        $array_thesaurus = array();
        $db = new Database();
        $arrayPDO = array($res_id);
        $stmt = $db->query(
            'SELECT thesaurus.thesaurus_id,thesaurus.thesaurus_name from thesaurus, thesaurus_res WHERE thesaurus_res.res_id = ? AND thesaurus.thesaurus_id = thesaurus_res.thesaurus_id'
            ,$arrayPDO);

        while($thesaurus=$stmt->fetchObject()){
            $array_thesaurus[]=array("LABEL" => $thesaurus->thesaurus_name, "ID" => $thesaurus->thesaurus_id);
        }
        return $array_thesaurus;
    }

	/**
    * Gets all thesaurus in an array
    *
    * @param string $parent the root of the tree
    * @param string $selected identifier of the selected thesaurus
    * @param string $tabspace margin of separation of tree's branches
    * @param array  $except array of thesaurus_id ( elements of the tree that should not appear )
    */
    public function getShortThesaurusTreeAdvanced($thesaurus)
    {      
        $db = new Database();

        $thesaurus_tmp = array();
        $stmt = $db->prepare("SELECT * FROM thesaurus WHERE thesaurus_parent_id is not null ORDER BY thesaurus_name  ASC");
        $stmt->execute(array());
        $lines = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($lines as $key => $line) {
                $thesaurus_tmp['AZ_terme'][] = array(
                    'ID' => $line->thesaurus_id, 
                    'LABEL' => $line->thesaurus_name,
                    'DESC' => $line->thesaurus_description, 
                    'PARENT' => $line->thesaurus_parent_id, 
                    'ASSOC' => $line->thesaurus_name_associate,
                    'CREATION' => $line->creation_date
                );

            //$thesaurus_tmp = array_merge((array)$thesaurus_tmp,(array)$this->getChildren($line->thesaurus_name,''));
        }

        $stmt = $db->prepare("SELECT * FROM thesaurus WHERE thesaurus_parent_id is null ORDER BY thesaurus_name  ASC");
        $stmt->execute(array());
        $lines = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($lines as $key => $line) {
                $thesaurus_tmp['generic_terme'][] = array(
                    'ID' => $line->thesaurus_id, 
                    'LABEL' => $line->thesaurus_name,
                    'DESC' => $line->thesaurus_description, 
                    'PARENT' => $line->thesaurus_parent_id, 
                    'ASSOC' => $line->thesaurus_name_associate,
                    'CREATION' => $line->creation_date
                );

            //$thesaurus_tmp = array_merge((array)$thesaurus_tmp,(array)$this->getChildren($line->thesaurus_name,''));
        }

        return $thesaurus_tmp;
    }

    public function getChildren($parent, $level = '')
    {  
        $db = new Database();

        $thesaurus_tmp = array();
        $level=$level."&nbsp;&nbsp;&nbsp;&nbsp;";
 
        $stmt = $db->query("SELECT * FROM thesaurus WHERE thesaurus_parent_id = '".$db->protect_string_db($parent)."' ORDER BY thesaurus_name  ASC");

        $lines = $stmt->fetchAll(PDO::FETCH_OBJ);

        if(count($lines) == 0){
            return [];
        }
        foreach ($lines as $key => $line) {
 
            $thesaurus_tmp[] = array(
                    'ID' => $line->thesaurus_id, 
                    'LABEL' => $level.$line->thesaurus_name,
                    'DESC' => $line->thesaurus_description, 
                    'PARENT_ID' => $line->thesaurus_parent_id, 
                    'ASSOC' => $line->thesaurus_name_associate,
                    'CREATION' => $line->creation_date
            );

            $thesaurus_tmp = array_merge((array)$thesaurus_tmp,(array)$this->getChildren($line->thesaurus_name,$level));

                
        }
        return $thesaurus_tmp;
    }
    /**
    * Gets all children of a thesaurus in an array
    *
    * @param string $parent the root of the tree
    * @param string $selected identifier of the selected entity
    * @param string $tabspace margin of separation of tree's branches
    * @param array  $except array of entity_id ( elements of the tree that should not appear )
    */

    public function getThesaurusChildrenTreeAdvanced($thesaurus, $parent = '', $tabspace = '')
    {   

        $db = new Database();
        //var_dump(trim($parent));
        if ($parent == "" || $parent == null) {
            $stmt = $db->query("SELECT * FROM thesaurus WHERE thesaurus_parent_id ='' OR thesaurus_parent_id is null ORDER BY thesaurus_name ASC");
        } else {
            $stmt = $db->query("SELECT * FROM thesaurus WHERE thesaurus_parent_id = ? ORDER BY thesaurus_name ASC",array(trim($parent)));
        }
   
        
        if ($stmt->rowCount() > 0) {
            $espace = $tabspace.'&emsp;';
            while ($line = $stmt->fetchObject()) {
                /*if($line->thesaurus_name == "2 - MEDECINE-SANTE"){
                    exit();
                } */                                         
                array_push(
                    $thesaurus, 
                    array(
                        'ID' => $line->thesaurus_id, 
                        'LABEL' => $espace.$line->thesaurus_name,
                     	'DESC' => $line->thesaurus_description, 
                     	'ASSOC' => $line->thesaurus_name_associate,
                     	'CREATION' => $line->creation_date
                    )
                );
               
                $db2 = new thesaurus();
                $db3 = new Database();
                $stmt2 = $db3->query("SELECT thesaurus_name FROM thesaurus WHERE thesaurus_parent_id = ? ",array($line->thesaurus_name));
                $tmp = array();
                //var_dump($stmt2->rowCount());
                if ($stmt2->rowCount() > 0) {
                    //var_dump(trim($line->entity_id));
                    $tmp = $db2->getThesaurusChildrenTreeAdvanced($tmp,$line->thesaurus_name,$espace);
                    $thesaurus = array_merge($thesaurus, $tmp);
                }
                //var_dump($stmt->rowCount() ." => " . $parent."<br/>");
            }
            return $thesaurus;
        }
    }

    public function saveResThesaurusList($thesaurus_list,$res_id)
    {
        // Loads the required class
        try {
            require_once("core/class/class_history.php");

        } catch (Exception $e){
            functions::xecho($e->getMessage()).' // ';
        }

        $db = new Database();
        $thesaurus_list=explode('__', $thesaurus_list);

        foreach ($thesaurus_list as $key => $value) {
            $arrayPDO = array($value,$res_id);
            $stmt = $db->query(
            'INSERT INTO thesaurus_res(thesaurus_id, res_id) VALUES(?,?)'
            ,$arrayPDO);

            if ($stmt){ 
                $hist = new history();
                $hist->add(
                    "thesaurus_res", $value, "ADD", 'thesauruslinkadd', _THESAURUS . ' « '. $value .' » ' . _ADDED_TO_RES.' : « '.
                    $res_id .' »',
                    $_SESSION['config']['databasetype'], 'thesaurus'
                );
            }
        }
        
        return true;
    }

    public function updateResThesaurusList($thesaurus_list,$res_id)
    {
        // Loads the required class
        try {
            require_once("core/class/class_history.php");

        } catch (Exception $e){
            functions::xecho($e->getMessage()).' // ';
        }

        $db = new Database();
        $thesaurus_list=explode('__', $thesaurus_list);
		//var_dump($thesaurus_list);
        $arrayPDO = array($res_id);
        $stmt = $db->query(
        'DELETE FROM thesaurus_res WHERE res_id = ?'
        ,$arrayPDO);

        if ($stmt){ 
            $hist = new history();
            $hist->add(
                "thesaurus_res", $res_id, "DEL", 'thesauruslinkreset', _UPDATE . ' « ' . _RESET_THESAURUS . ' »',
                $_SESSION['config']['databasetype'], 'thesaurus'
            );
        }
		if(!empty($thesaurus_list[0])){
        foreach ($thesaurus_list as $key => $value) {
            $arrayPDO = array($value,$res_id);
            $stmt = $db->query(
            'INSERT INTO thesaurus_res(thesaurus_id, res_id) VALUES(?,?)'
            ,$arrayPDO);

            if ($stmt){ 
                $hist = new history();
                $hist->add(
                    "thesaurus_res", $res_id, "ADD", 'thesauruslinkadd', _THESAURUS . ' : « '. $value .' » ' . _ADDED_TO_RES.' : « '.
                    $res_id .' »',
                    $_SESSION['config']['databasetype'], 'thesaurus'
                );
            }
        }
		}
        
        return true;
    }

    public function insert($new_thesaurus)
    {
        $db = new Database();

        if(!empty($new_thesaurus['thesaurus_parent_id'])){
            $stmt = $db->query("SELECT thesaurus_name FROM thesaurus WHERE thesaurus_id = ?", array($new_thesaurus['thesaurus_parent_id']));
            $line = $stmt->fetchObject();
            $new_thesaurus_parent_id = $line->thesaurus_name;
            $new_thesaurus_name_associate = array();
        }else{
            $new_thesaurus_parent_id = $new_thesaurus['thesaurus_parent_id'];
        }
        

        if(!empty($new_thesaurus['thesaurus_name_associate'])){
            foreach ($new_thesaurus['thesaurus_name_associate'] as $key => $value) {
                $stmt = $db->query("SELECT thesaurus_name FROM thesaurus WHERE thesaurus_id = ?", array($value));
                $line = $stmt->fetchObject();
                $new_thesaurus_name_associate[] = $line->thesaurus_name;
            }
            $new_thesaurus_name_associate = implode(',', $new_thesaurus_name_associate);
        }else{
            $new_thesaurus_name_associate = $new_thesaurus['thesaurus_name_associate'];
        }
        $stmt = $db->query(
            "INSERT INTO thesaurus(thesaurus_name, thesaurus_description, thesaurus_name_associate, thesaurus_parent_id, used_for)"
            . " VALUES (?, ?, ?, ?, ?)"
          ,array($new_thesaurus['thesaurus_name'],$new_thesaurus['thesaurus_description'],$new_thesaurus_name_associate,$new_thesaurus_parent_id, $new_thesaurus['used_for']));
        $hist = new history();
        $hist->add(
            "thesaurus", $new_thesaurus['thesaurus_name'], "ADD", 'thesaurusadd', _THESAURUS_ADDED.' : "'.
            substr(functions::protect_string_db($new_thesaurus['thesaurus_name']), 0, 254) .'"',
            $_SESSION['config']['databasetype'], 'thesaurus'
        );


        
    }

     public function update($new_thesaurus)
    {
        $db = new Database();
        if(!empty($new_thesaurus['thesaurus_parent_id'])){
            $stmt = $db->query("SELECT thesaurus_name FROM thesaurus WHERE thesaurus_id = ?", array($new_thesaurus['thesaurus_parent_id']));
            $line = $stmt->fetchObject();
            $new_thesaurus_parent_id = $line->thesaurus_name;
            $new_thesaurus_name_associate = array();
        }else{
            $new_thesaurus_parent_id = $new_thesaurus['thesaurus_parent_id'];
        }
        

        if(!empty($new_thesaurus['thesaurus_name_associate'][0])){
            foreach ($new_thesaurus['thesaurus_name_associate'] as $key => $value) {
                $stmt = $db->query("SELECT thesaurus_name FROM thesaurus WHERE thesaurus_id = ?", array($value));
                $line = $stmt->fetchObject();
                $new_thesaurus_name_associate[] = $line->thesaurus_name;
            }
            $new_thesaurus_name_associate = implode(',', $new_thesaurus_name_associate);
        }else{
            $new_thesaurus_name_associate = $new_thesaurus['thesaurus_name_associate'];
        }
        $stmt = $db->query(
            "UPDATE thesaurus SET thesaurus_name=?, thesaurus_description=?, thesaurus_name_associate=?, thesaurus_parent_id=?, used_for=? WHERE thesaurus_id=?"
          ,array($new_thesaurus['thesaurus_name'],$new_thesaurus['thesaurus_description'],$new_thesaurus_name_associate,$new_thesaurus_parent_id,$new_thesaurus['used_for'],$new_thesaurus['thesaurus_id']));
        $hist = new history();
        $hist->add(
            "thesaurus", $new_thesaurus['thesaurus_name'], "UP", 'thesaurusup', _THESAURUS_UPDATED.' : "'.
            substr(functions::protect_string_db($new_thesaurus['thesaurus_name']), 0, 254) .'"',
            $_SESSION['config']['databasetype'], 'thesaurus'
        );


        
    }

    public function delete($thesaurus_id)
    {
        /*
         * Deleting  [REALLY] a thesaurus for a ressource
         */
        $thesaurus_name = $this->get_by_label($thesaurus_id);
        $db = new Database();
        $stmt = $db->query(
                "DELETE FROM thesaurus"
                . " WHERE thesaurus_id = ?"
        ,array($thesaurus_id));
        if ($stmt){
            $hist = new history();
            $hist->add(
                "thesaurus", $thesaurus_name, "DEL", 'thesaurusdel', _THESAURUS_DELETED.' : "'.
                substr(functions::protect_string_db($thesaurus_name), 0, 254) .'"',
                $_SESSION['config']['databasetype'], 'thesaurus'
            );
            return true; 
        }
        return false;
        //$db->show();
    }

    public function store($mode='up', $params){
    /*
     * Store into the database a thesarus for a ressource
     */
        if ($mode=='add'){
            $new_thesaurus['thesaurus_name'] = $params[0];
            $new_thesaurus['thesaurus_description'] = $params[1];
            $new_thesaurus['thesaurus_name_associate'] = $params[2];
            $new_thesaurus['thesaurus_parent_id'] = $params[3];
            $new_thesaurus['used_for'] = $params[5];
            $this->insert($new_thesaurus);  
            
            return true;
        }
        elseif($mode=='up'){
            
            $new_thesaurus['thesaurus_name'] = $params[0];
            $new_thesaurus['thesaurus_description'] = $params[1];
            $new_thesaurus['thesaurus_name_associate'] = $params[2];
            $new_thesaurus['thesaurus_parent_id'] = $params[3];
            $new_thesaurus['thesaurus_id'] = $params[4];
            $new_thesaurus['used_for'] = $params[5];
            $this->update($new_thesaurus);  

            return true;
        }
        else
        {
            return false;   
        }
    }

    /*
     * Searching a list of ressources by identifiant
     * @Return : an Array with label's ressources or 0
     */
    public function getresarray_byId($thesaurus_id){
        $array = array();
        
        $db = new Database();
        $stmt = $db->query(
                "SELECT res_id AS bump FROM thesaurus_res"
                . " WHERE thesaurus_id = ?"
                . " AND res_id <> 0"
        ,array($thesaurus_id));
        
        while ($result = $stmt->fetchObject())
        {
            array_push($array, $result->bump);
        }
        
        if ($array)
        {
            return $array; 
        }
        
        return false;
    }

    public function getThesIdByLabel($thesaurus_name){
        $array = array();
        
        $db = new Database();
        $stmt = $db->query(
                "SELECT thesaurus_id FROM thesaurus"
                . " WHERE thesaurus_name LIKE ?"
        ,array($thesaurus_name));
        
        $result = $stmt->fetchObject();
        return $result->thesaurus_id; 
    }

}

