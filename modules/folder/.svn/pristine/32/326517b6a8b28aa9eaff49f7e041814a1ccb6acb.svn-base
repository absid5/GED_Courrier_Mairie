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
* modules tools Class for workflow
*
*  Contains all the functions to  modules tables for workflow
*
* @package  maarch
* @version 3.0
* @since 10/2005
* @license GPL v3
* @author  Laurent Giovannoni  <dev@maarch.org>
*
*/
require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
. 'class_request.php';
require_once 'core' . DIRECTORY_SEPARATOR . 'core_tables.php';
require_once 'modules' . DIRECTORY_SEPARATOR . 'folder' . DIRECTORY_SEPARATOR
    . 'class' . DIRECTORY_SEPARATOR . 'class_admin_foldertypes.php';
require_once 'modules/folder/folder_tables.php';
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_history.php";


abstract class folder_Abstract extends request
{
	/**
	* System Identifier of the folder
    * @access private
    * @var integer
    */
	protected $system_id;
	/**
	* Identifier of the foldertype
    * @access private
    * @var integer
    */
	protected $foldertype_id;

	/**
	* Label of the foldertype
    * @access private
    * @var integer
    */
	protected $foldertype_label;

	/**
	* Identifier of the folder (Matricule)
    * @access private
    * @var integer
    */
	protected $folder_id;

	/**
	* Folder Name
    * @access private
    * @var integer
    */
	protected $folder_name;

	/**
	* System Identifier of the parent folder
    * @access private
    * @var integer
    */
	protected $parent_id;


	/**
	* Identifier of the folder creator
    * @access private
    * @var string
    */
	protected $typist;

	/**
	* Folder status
    * @access private
    * @var string
    */
	protected $status;

	/**
	* Level of the folder
    * @access private
    * @var integer
    */
	protected $level;


	/**
	* Time of the folder retention
    * @access private
    * @var string
    */
	//protected $retention_time;

	/**
	* Creation date of the folder
    * @access private
    * @var date
    */
	protected $creation_date;

	/**
	* identifier of the folder out card
    * @access private
    * @var integer
    */
	protected $folder_out_id;

	/**
	* folder is complete or not
    * @access private
    * @var boolean
    */
	protected $complete;

	/**
	* Collection identifier
    * @access private
    * @var string
    */
	protected $coll_id;
	/**
	* Collection identifier
    * @access private
    * @var string
    */
	protected $last_modified_date;

	/**
	* Last modification date
    * @access private
    * @var date
    */
	protected $desarchive;

	/**
	* Folder is in frozen state or not
    * @access private
    * @var string
    */
	protected $is_frozen;
    
    /**
	* Dynamic index
    * @access private
    * @var array
    */
	protected $index;

    protected $destination;
    
    protected $dest_user;

	function __construct()
	{
		parent::__construct();
		$this->index = array();
	}

	/**
	* Build Maarch module tables into sessions vars with a xml
	* configuration file
	*/
	public function build_modules_tables()
	{
		if (file_exists(
			$_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
			. $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
			. DIRECTORY_SEPARATOR . "folder" . DIRECTORY_SEPARATOR . "xml"
			. DIRECTORY_SEPARATOR . "config.xml"
		)
		) {
			$path = $_SESSION['config']['corepath'] . 'custom'
				. DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
				. DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR
				. "folder" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
				. "config.xml";
		} else {
			$path = "modules" . DIRECTORY_SEPARATOR . "folder"
				. DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
				. "config.xml";
		}
		$xmlconfig = simplexml_load_file($path);

		$tableName = $xmlconfig->TABLENAME;
		$_SESSION['tablename']['fold_folders'] = (string) $tableName->fold_folders;
		$_SESSION['tablename']['fold_folders_out'] = (string) $tableName->fold_folders_out;
		$_SESSION['tablename']['fold_foldertypes'] = (string) $tableName->fold_foldertypes;
		$_SESSION['tablename']['fold_foldertypes_doctypes'] = (string) $tableName->fold_foldertypes_doctypes;
		$_SESSION['tablename']['fold_foldertypes_indexes'] = (string) $tableName->fold_foldertypes_indexes;
		$_SESSION['tablename']['fold_foldertypes_doctypes_level1'] = (string) $tableName->fold_foldertypes_doctypes_level1;

        $view = $xmlconfig->VIEW;
		$_SESSION['view']['view_folders'] = (string) $view->view_folders;
        
		$history = $xmlconfig->HISTORY;
		$_SESSION['history']['folderdel'] = (string) $history->folderdel;
		$_SESSION['history']['folderadd'] = (string) $history->folderadd;
		$_SESSION['history']['folderup'] = (string) $history->folderup;
		$_SESSION['history']['folderview'] = (string) $history->folderview;
		$_SESSION['history']['foldertypeadd'] = (string) $history->foldertypeadd;
		$_SESSION['history']['foldertypeup'] = (string) $history->foldertypeup;
		$_SESSION['history']['foldertypedel'] = (string) $history->foldertypedel;
	}


	/**
	* load folder object from the folder system id
	*
	* @param int $id folder system id
	* @param string $table folder table
	*/
	function load_folder($id, $table)
	{
		$ft = new foldertype();
		$db = new Database();
		$stmt = $db->query("SELECT foldertype_id FROM ".$table." WHERE folders_system_id = ?", array($id));
		$res = $stmt->fetchObject();
		$this->foldertype_id = $res->foldertype_id;
		$this->system_id = $id;
		$tab_index = $ft->get_indexes($this->foldertype_id);

		$fields = " folder_id, parent_id, folder_name, subject, description, "
		    . "author, typist, status, folder_level, creation_date, destination, "
		    . "dest_user, folder_out_id, is_complete, is_folder_out, last_modified_date, "
		    . "folder_name, is_frozen";
		foreach(array_keys($tab_index) as $key)
		{
			$fields .= ", ".$key;
		}
		$stmt = $db->query("SELECT ".$fields." FROM ".FOLD_FOLDERS_TABLE." WHERE folders_system_id = ?", array($id));
		//$this->show();
		$res = $stmt->fetchObject();

		$this->folder_id = functions::show_string($res->folder_id);
		$this->folder_name = $res->folder_name;
		$this->parent_id = $res->parent_id;
		$this->typist = functions::show_string($res->typist);
		$this->status = $res->status;
		$this->level = $res->folder_level;
		$this->creation_date = functions::format_date_db($res->creation_date, true);
		$this->folder_out_id = $res->folder_out_id;
		$this->complete = $res->is_complete;
		$this->desarchive = $res->is_folder_out;
		$this->is_frozen = $res->is_frozen;
		$this->destination = $res->destination;
		$this->dest_user = $res->dest_user;
		$this->last_modified_date = functions::format_date_db($res->last_modified_date, true);

		foreach(array_keys($tab_index) as $key)
		{
			$tab_index[$key]['value'] = $res->{$key};
			$tab_index[$key]['show_value'] = $res->{$key};
			if($tab_index[$key]['type_field'] == 'select')
			{
				for($i=0;$i<count($tab_index[$key]['values']);$i++)
				{
					if($tab_index[$key]['values'][$i]['id'] == $tab_index[$key]['value'] )
					{
						$tab_index[$key]['show_value'] = $tab_index[$key]['values'][$i]['label'];
						break;
					}
				}
			}
			elseif($tab_index[$key]['type'] == 'date')
			{
				$tab_index[$key]['show_value'] = $this->format_date_db($tab_index[$key]['value'], true);
			}
			elseif($tab_index[$key]['type'] == 'string')
			{
				$tab_index[$key]['show_value'] = functions::show_string($tab_index[$key]['value']);
			}
		}
		$this->index = array();
		$this->index = $tab_index;

		$stmt = $db->query("SELECT foldertype_label, coll_id FROM ".$_SESSION['tablename']['fold_foldertypes']." WHERE foldertype_id = ?", array($this->foldertype_id));
		$res = $stmt->fetchObject();
		$this->foldertype_label = $res->foldertype_label;
		$this->coll_id = functions::show_string($res->coll_id);

	}

	/**
	* Creates a folder
	*/
	public function create_folder($iframe=false)
	{
		$db = new Database();
		$this->checks_folder_data();
		if (! empty($_SESSION['error'])) {
			if($iframe == true){
					header(
					"location: " . $_SESSION['config']['businessappurl']
				    . "index.php?page=create_folder_form_iframe&module=folder&display=false"
					);
				}else{
					header(
					"location: " . $_SESSION['config']['businessappurl']
				    . "index.php?page=create_folder_form&module=folder"
					);
				}
			exit();
		} else if(preg_match('/\'|\"/',$_SESSION['m_admin']['folder']['folder_id'])) 
{ 
			$_SESSION['error'] = _CHAR_ERROR . "<br />";
				if($iframe == true){
					header(
					"location: " . $_SESSION['config']['businessappurl']
				    . "index.php?page=create_folder_form_iframe&module=folder&display=false"
					);
				}else{
					header(
					"location: " . $_SESSION['config']['businessappurl']
				    . "index.php?page=create_folder_form&module=folder"
					);
				}
				exit();
		}else{
			$stmt = $db->query(
				"SELECT folder_id FROM " . FOLD_FOLDERS_TABLE
			    . " WHERE folder_id= ? and status != 'DEL'",
			    array($_SESSION['m_admin']['folder']['folder_id'])
			);
			if ($stmt->rowCount() > 0) {
				$_SESSION['error'] = $_SESSION['m_admin']['folder']['folder_id']
				    . " " . _ALREADY_EXISTS . "<br />";
				if($iframe == true){
					header(
					"location: " . $_SESSION['config']['businessappurl']
				    . "index.php?page=create_folder_form_iframe&module=folder&display=false"
					);
				}else{
					header(
					"location: " . $_SESSION['config']['businessappurl']
				    . "index.php?page=create_folder_form&module=folder"
					);
				}
				exit();
			} else {
				if ($_SESSION['m_admin']['folder']['folder_parent']) {
					$stmt = $db->query(
						"SELECT destination FROM " . FOLD_FOLDERS_TABLE . " WHERE folders_system_id = ? and status != 'DEL'",
						array($_SESSION['m_admin']['folder']['folder_parent'])
					);
					$resQuery = $stmt->fetchObject();
				}


				$folderDest = null;
				if (isset($resQuery)) {
					$folderDest = $resQuery->destination;
				} else if (isset($_REQUEST['folder_dest']) && $_REQUEST['folder_dest'] && isset($_SESSION['user']['primaryentity']['id'])) {
					$folderDest = $_SESSION['user']['primaryentity']['id'];
				}
				$db->query(
					"INSERT INTO " . FOLD_FOLDERS_TABLE
					. " (folder_id, folder_name, foldertype_id, creation_date, "
					. "typist, last_modified_date, parent_id,folder_level, destination) VALUES (?, ?, ?,  CURRENT_TIMESTAMP, ?, CURRENT_TIMESTAMP, ?, ?, ?)",
					[	$_SESSION['m_admin']['folder']['folder_id'],
						$_SESSION['m_admin']['folder']['folder_name'],
						$_SESSION['m_admin']['folder']['foldertype_id'],
						$_SESSION['user']['UserId'],
						$_SESSION['m_admin']['folder']['folder_parent'],
						$_SESSION['m_admin']['folder']['folder_level'],
						$folderDest
					]
				);

				$stmt = $db->query(
					'SELECT folders_system_id FROM ' . FOLD_FOLDERS_TABLE
				    . " WHERE folder_id = ?", array($_SESSION['m_admin']['folder']['folder_id'])
				);
				$res = $stmt->fetchObject();
				$id = $res->folders_system_id;

				$_SESSION['m_admin']['folder']['folders_system_id'] = $id;

				$foldertype = new foldertype();

				$query = $foldertype->get_sql_update(
				    $_SESSION['m_admin']['folder']['foldertype_id'],
				    $_SESSION['m_admin']['folder']['indexes']
				);
				if (! empty($query)) {
					$query = preg_replace('/^,/', '', $query);
					$query = "UPDATE  " .FOLD_FOLDERS_TABLE . " SET " . $query
					    . " WHERE folders_system_id = ?";
					$db->query($query, array($id));
				}
				if ($_SESSION['history']['folderadd'] == "true") {
					$hist = new history();
					$hist->add(
					    FOLD_FOLDERS_TABLE, $id, "ADD",'folderadd',
					    _FOLDER_ADDED . " : "
					    . $_SESSION['m_admin']['folder']['folder_id'],
					    $_SESSION['config']['databasetype'], 'folder'
					);
				}

				$_SESSION['info'] = _FOLDER_ADDED;
				//unset($_SESSION['m_admin']);
				if($iframe == true){
					header(
					"location: " . $_SESSION['config']['businessappurl']
				    . "index.php?page=create_folder_form_iframe&module=folder&display=false"
					);
				}else{
					unset($_SESSION['m_admin']);
					header(
					"location: " . $_SESSION['config']['businessappurl']
				    . "index.php?page=show_folder&module=folder&id=" . $id
					);
				}
				exit();
			}

		}
	}

	/**
	* Processes data during folder creation
	*/
	protected function checks_folder_data()
	{
		$foldertype = new foldertype();

		if (isset($_REQUEST['folder_id']) && ! empty($_REQUEST['folder_id'])) {
			$_SESSION['m_admin']['folder']['folder_id'] = $this->wash(
			    $_REQUEST['folder_id'], "no", _FOLDER_ID
			);
		} else {
			$_SESSION['m_admin']['folder']['folder_id'] = '';
			$_SESSION['error'] .= _FOLDER_ID . ' ' . _IS_EMPTY . '<br/>';
		}

		if (isset($_REQUEST['folder_name'])
		    && ! empty($_REQUEST['folder_name'])
		) {
			$_SESSION['m_admin']['folder']['folder_name'] = $this->wash(
			    $_REQUEST['folder_name'], "no", _FOLDERNAME
			);
		} else {
			$_SESSION['m_admin']['folder']['folder_name'] = '';
			$_SESSION['error'] .= _FOLDERNAME . ' ' . _IS_EMPTY . '<br/>';
		}

		$_SESSION['m_admin']['folder']['folder_parent'] = 0;
		$_SESSION['m_admin']['folder']['folder_level'] = 1;

		if (isset($_REQUEST['folder_parent'])
		    && ! empty($_REQUEST['folder_parent'])
		) {
			$_SESSION['m_admin']['folder']['folder_parent'] = $this->wash(
			    $_REQUEST['folder_parent'], "num", _FOLDER_PARENT
			);
			$_SESSION['m_admin']['folder']['folder_level'] = 2;
		}

		if (isset($_REQUEST['foldertype']) && ! empty($_REQUEST['foldertype'])) {
			$_SESSION['m_admin']['folder']['foldertype_id'] = $this->wash(
			    $_REQUEST['foldertype'], "no", _FOLDERTYPE
			);
			$indexes = $foldertype->get_indexes(
			    $_SESSION['m_admin']['folder']['foldertype_id']
			);

			$values = array();
			foreach (array_keys($indexes) as $key) {
				if (isset($_REQUEST[$key])) {
					$values [$key] = $_REQUEST[$key];
				} else {
					$values [$key] = '';
				}
			}

			$_SESSION['m_admin']['folder']['indexes'] = $values ;
			$foldertype->check_indexes($_SESSION['m_admin']['folder']['foldertype_id'], $values );
		}
		else
		{
			$_SESSION['m_admin']['folder']['foldertype'] = '';
			$_SESSION['error'] .= _FOLDERTYPE.' '._IS_EMPTY.'<br/>';
		}
	}


	/**
	* get all data from the current folder object
	*/
	public function get_folder_info()
	{
		$folder = array();
		$folder['system_id'] = $this->system_id ;
		$folder['foldertype_id'] = $this->foldertype_id ;
		$folder['folder_name'] = $this->folder_name ;
		$folder['foldertype_label'] = $this->foldertype_label ;
		$folder['folder_id'] = $this->folder_id ;
		$folder['parent_id'] = $this->parent_id ;
		$folder['typist'] = $this->typist ;
		$folder['status'] = $this->status;
		$folder['level'] = $this->level ;
		$folder['creation_date'] = $this->creation_date ;
		$folder['folder_out_id'] = $this->folder_out_id ;
		$folder['complete'] = $this->complete ;
		$folder['desarchive'] = $this->desarchive ;
		$folder['is_frozen'] = $this->is_frozen ;
		$folder['coll_id'] = $this->coll_id ;
		$folder['last_modified_date'] = $this->last_modified_date ;
		$folder['destination'] = $this->destination;
		$folder['index'] = array();
		$folder['index'] = $this->index;

		return $folder;
	}

	public function get_field($field_name, $in_index = false)
	{
		if($field_name == 'folders_system_id')
		{
			return $this->system_id;
		}
		elseif($field_name == 'foldertype_id')
		{
			return $this->foldertype_id;
		}
		elseif($field_name == 'folder_name')
		{
			return $this->folder_name;
		}
		elseif($field_name == 'folder_id')
		{
			return $this->folder_id;
		}
		elseif($field_name == 'parent_id')
		{
			return $this->parent_id;
		}
		elseif($field_name == 'subject')
		{
			return $this->subject;
		}
		elseif($field_name == 'typist')
		{
			return $this->typist;
		}
		elseif($field_name == 'status')
		{
			return $this->status;
		}
		elseif($field_name == 'level')
		{
			return $this->level;
		}
		elseif($field_name == 'creation_date')
		{
			return $this->creation_date;
		}
		elseif($field_name == 'folder_out_id')
		{
			return $this->folder_out_id;
		}
		elseif($field_name == 'complete')
		{
			return $this->complete;
		}
		elseif($field_name == 'desarchive')
		{
			return $this->desarchive;
		}
	    elseif($field_name == 'is_frozen')
		{
			return $this->is_frozen;
		}
		elseif($field_name == 'coll_id')
		{
			return $this->coll_id;
		}
		elseif($in_index)
		{
			for($i=0; $i < count($this->index);$i++)
			{
				if($field_name == $this->index[$i]['column'])
				{
					return $this->index[$i]['value'];
				}
			}
			return '';
		}
		else
		{
			return '';
		}
	}

	/**
	* calculate the missing document types from a folder
	*
	* @param string $table_res resources table
	* @param string $table_foldertypes_doc foldertypes doctypes table
	* @param string $table_doctypes doctypes table
	* @param string $id folder system id
	* @param string $foldertype_id id of the foldertype
	*/
	public function missing_res($table_res,  $table_foldertypes_doc, $table_doctypes, $id, $foldertype_id)
	{
		$db = new Database();
		$indexed_types = array();
		$stmt = $db->query("SELECT distinct type_id as type FROM ".$table_res." WHERE status <> 'DEL' and folders_system_id = ?", array($id));
		while($res = $stmt->fetchObject())
		{
			array_push($indexed_types, $res->type );
		}

		$waited_types = array();
		$stmt = $db->query("SELECT doctype_id FROM ".$table_foldertypes_doc." WHERE foldertype_id = ?", array($foldertype_id));

		while($res = $stmt->fetchObject())
		{
			array_push($waited_types, $res->doctype_id );
		}
		$temp = array();
		$temp = array_diff($waited_types, $indexed_types);
		$temp = array_values($temp);
		$missing_res = array();
		for($i=0; $i < count($temp); $i++)
		{
			$stmt = $db->query("SELECT type_id, description FROM ".$table_doctypes." WHERE type_id = ?", array($temp[$i]));
			//$this->show();
			$res = $stmt->fetchObject();
			if($res->type_id <> "")
			{
				array_push($missing_res, array('ID' => $res->type_id, 'LABEL' => functions::show_string($res->description)));
			}
		}
		return $missing_res ;
	}


	/* calculate the missing document types from a folder
	*
	* @param string $table_res resources table
	* @param string $table_foldertypes_doc foldertypes doctypes table
	* @param string $table_doctypes doctypes table
	* @param string $id folder system id
	* @param string $foldertype_id id of the foldertype
	*/
	public function missing_res2($table_res,  $table_foldertypes_doc, $table_doctypes, $id, $foldertype_id)
	{
		$db = new Database();
		$indexed_types = array();
		$stmt = $db->query("SELECT distinct type_id as type FROM ".$table_res." WHERE status <> 'DEL' and folders_system_id = ?", array($id));

		while($res = $stmt->fetchObject())
		{
			array_push($indexed_types, $res->type );
		}
		$waited_types = array();
		$stmt = $db->query("SELECT doctype_id FROM ".$table_foldertypes_doc." WHERE foldertype_id = ?", array($foldertype_id));
		while($res = $stmt->fetchObject())
		{
			array_push($waited_types, $res->doctype_id );
		}
		$html_tab = "<table width=\"100%\" border=\"1\" align=\"center\"><tr><td>"._PIECE_TYPE."</td><td>"._MISSING."</td></tr>";
		for($i=0; $i < count($waited_types); $i++)
		{
			$stmt = $db->query("SELECT type_id, description FROM ".$table_doctypes." WHERE type_id = ?", array($waited_types[$i]));
			$res = $stmt->fetchObject();
			if($res->type_id <> "")
			{
				$stmt = $db->query("SELECT type_id as type FROM ".$table_res." WHERE status <> 'DEL' and folders_system_id = ? and type_id = ?", array($id, $waited_types[$i]));
				$res2 = $stmt->fetchObject();
				if($waited_types[$i] <> $res2->type)
				{
					$html_tab .= "<tr><td><b>". functions::show_string($res->description)."</b></td><td>";
					$html_tab .= "<b>X</b>";
				}
				else
				{
					$html_tab .= "<tr><td>".functions::show_string($res->description)."</td><td>";
					$html_tab .= "&nbsp;";
				}
			}
			$html_tab .= "</td></tr>";
		}
		$html_tab .= "</table>";
		return $html_tab ;
	}


	/**
	* calculate if a folder is complete or no
	*
	* @param string $table_res resources table
	* @param string $table_foldertypes_doc foldertypes doctypes table
	* @param string $table_doctypes doctypes table
	* @param string $id folder system id
	*/
	public function is_complete($table_res, $table_foldertypes_doc, $table_doctypes, $id)
	{
		$tab = $this->missing_res($table_res, $table_foldertypes_doc, $table_doctypes, $id, $this->foldertype_id);

		if(count($tab) > 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	* get all the history data of the current folder
	*
	* @param string $table_history history table
	* @param string $table_folder folders table
	*/
	public function get_history()
	{
		$db = new Database();

		$history = array();

		$stmt = $db->query("SELECT h.event_date, h.info, h.user_id, u.lastname, u.firstname 
							FROM ".$_SESSION['tablename']['history']." h, ".$_SESSION['tablename']['users']." u 
							WHERE h.table_name = ? and h.record_id = ? and h.event_type <> 'UP_CONTRACT' and h.user_id = u.user_id order by h.event_date desc ",
							array($_SESSION['tablename']['fold_folders'], $this->system_id));
		//$this->show();
		while($res = $stmt->fetchObject())
		{
			array_push($history, array( 'DATE' => $res->event_date, 'EVENT' => functions::show_string($res->info), 'USER' => functions::show_string($res->lastname.' '.$res->firstname) ));
		}

		return $history;
	}


	/**
	* modify the folder in the users table
	*
	* @param string $id folder identifier
	* @param string $user_id user identifier
	* @param string $table users table
	*/
	public function modify_default_folder_in_db($id, $user_id, $table)
	{
		$db = new Database();
		$db->query('UPDATE '.$table." SET custom_t1 = ? WHERE user_id = ?", array($id, $user_id));
	}

	public function update_folder($values, $id_to_update)
	{
		$data = array();
		$foldertype = new foldertype();
		$request = new request();
		$func = new functions();
		$foldertype_id =  $values['foldertype_id'];
		$folderDest = null;
		if (!empty($values['folder_dest']) && isset($_SESSION['user']['primaryentity']['id'])) {
			$folderDest = $_SESSION['user']['primaryentity']['id'];
		}
		if(!empty($foldertype_id))
		{
			$indexes = $foldertype->get_indexes($foldertype_id,'minimal');
			$val_indexes = array();
			for($i=0; $i<count($indexes);$i++)
			{
				$val_indexes[$indexes[$i]] =  $values[$indexes[$i]];
			}

			$test_type = $foldertype->check_indexes($foldertype_id, $val_indexes );
			if($test_type)
			{
				$data = $foldertype->fill_data_array($foldertype_id, $val_indexes, $data);
			}
		}
		else
		{
			$_SESSION['error'] .= _FOLDERTYPE.' '._IS_EMPTY;
		}

		$func->wash($values['folder_name'], "no", _FOLDERNAME, "yes", "", "255", '', '');

		if(empty($_SESSION['error']))
		{
			$where = " folders_system_id = ?";
			$arrayPDO = array($id_to_update);
			array_push($data, array('column' => 'folder_name', 		  'value' =>functions::protect_string_db($values['folder_name']),		   'type' =>"string"));
			array_push($data, array('column' => 'last_modified_date', 'value' => $request->current_datetime(), 'type' => "date"));
			if (isset($_SESSION['user']['primaryentity']['id']))
				$data[] = ['column' => 'destination', 'value' => $folderDest, 'type' => 'string'];
			
			$request->PDOupdate($_SESSION['tablename']['fold_folders'], $data, $where, $arrayPDO, $_SESSION['config']['databasetype']);

			//$_SESSION['error'] = _FOLDER_INDEX_UPDATED." (".$values['folder_id'].")";
			$_SESSION['info'] = _FOLDER_UPDATED." (".$values['folder_id'].")";
			if($_SESSION['history']['folderup'])
			{
				$hist = new history();
				$hist->add(FOLD_FOLDERS_TABLE, $id_to_update, "UP", 'folderup', $_SESSION['error'], $_SESSION['config']['databasetype'],'folder');
			}
		}
		$_SESSION['error_page'] = $_SESSION['error'];
		$_SESSION['error']= '';
		?>
		<script  type="text/javascript">
			//window.opener.reload();
			var error_div = $('main_error');
			if(error_div)
			{
				error_div.innerHTML = '<?php echo addslashes($_SESSION['error_page']);?>';
			}
		</script>
		<?php
	}

	public function closeFolder($folderId)
	{
	    if (empty($folderId)) {
	         return false;
	    }
	    $db = new Database();
	    $db->query(
	    	"UPDATE " . FOLD_FOLDERS_TABLE . " SET status = 'END' "
	        . "WHERE folders_system_id = ?", array($folderId)
	    );
	    if ($_SESSION['history']['folderup']) {
			$hist = new history();
			$msg = _FOLDER_CLOSED .' : ' . $folderId ;
			$hist->add(
			    FOLD_FOLDERS_TABLE, $folderId, "UP", 'folderup', $msg,
			    $_SESSION['config']['databasetype'], 'folder'
			);
		}
	}
	public function freezeFolder($folderId, $params=array())
	{
	 	if (empty($folderId)) {
	         return false;
	    }
	    $db = new Database();
	    $db->query(
	    	"UPDATE " . FOLD_FOLDERS_TABLE . " SET is_frozen = 'Y' "
	        . "WHERE folders_system_id = ?", array($folderId)
	    );
	 	if ($_SESSION['history']['folderup']) {
			$hist = new history();
			$msg = _FROZEN_FOLDER .' : ' . $folderId ;
			$hist->add(
			    FOLD_FOLDERS_TABLE, $folderId, "UP", 'folderup', $msg,
			    $_SESSION['config']['databasetype'], 'folder'
			);
		}
	}


	public function unfreezeFolder($folderId, $params=array())
	{
	 	if (empty($folderId)) {
	         return false;
	    }
	    $db = new Database();
	    $db->query(
	    	"UPDATE " . FOLD_FOLDERS_TABLE . " SET is_frozen = 'N' "
	        . "WHERE folders_system_id = ?", array($folderId)
	    );
	 	if ($_SESSION['history']['folderup']) {
			$hist = new history();
			$msg = _UNFROZEN_FOLDER .' : ' . $folderId ;
			$hist->add(
			    FOLD_FOLDERS_TABLE, $folderId, "UP", 'folderup', $msg,
			    $_SESSION['config']['databasetype'], 'folder'
			);
		}
	}

	public function delete_folder($folder_sys_id, $foldertype)
	{
		$db = new Database();
		$stmt = $db->query("SELECT coll_id FROM ".$_SESSION['tablename']['fold_foldertypes']." WHERE foldertype_id = ?", array($foldertype));
		$res = $stmt->fetchObject();
		$coll_id = $res->coll_id;
		require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_security.php');
		$sec = new security();
		$table = $sec->retrieve_table_from_coll($coll_id);

		if(!empty($table) && !empty($folder_sys_id))
		{
			$stmt = $db->query("SELECT folder_level, folder_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE folders_system_id = ?", array($folder_sys_id));
			$res = $stmt->fetchObject();
			$level = $res->folder_level;
			$fold_id = $res->folder_id;
			$where = '';

			$arrayPDO = array($folder_sys_id);
			if($level == 1)
			{
				$stmt = $db->query("SELECT folders_system_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE parent_id = ? and folder_level = 2", array($folder_sys_id));
				if($stmt->rowCount() > 0)
				{
					while($res = $stmt->fetchObject())
					{
						$where .= " or folders_system_id = ?";
						$arrayPDO = array_merge($arrayPDO, array($res->folders_system_id));
					}
				}
			}
			$db->query("UPDATE ".$table." SET status = 'DEL' WHERE folders_system_id = ? ".$where, $arrayPDO);
			$db->query("UPDATE ".$_SESSION['tablename']['fold_folders']." SET status = 'DEL' WHERE folders_system_id = ? ".$where, $arrayPDO);
			$_SESSION['info'] = _FOLDER_DELETED." (".$fold_id.")";
		}
	}

	public function is_folder_exists($folder_system_id)
	{
		if($folder_system_id <> "")
		{
			$db = new Database();
			$stmt = $db->query("SELECT folder_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE folders_system_id = ?", array($folder_system_id));
			$res = $stmt->fetchObject();
			if($res->folder_id <> "")
			{
				$find = true;
			}
			else
			{
				$find = false;
			}
		}
		else
		{
			$find = false;
		}
		return $find;
	}

	/**
	* calculate if a folder is empty
	*
	* @param string $table_res resources table
	* @param string $id folder system id
	*/
	public function is_folder_empty($table_res, $id)
	{
		$db = new Database();
		$indexed_types = array();
		$stmt = $db->query("SELECT * FROM ".$table_res." WHERE status <> 'DEL' and folders_system_id = ?", array($id));
		if($stmt->rowCount() > 0)
		{
			$empty = false;
		}
		else
		{
			$empty = true;
		}
		return $empty;
	}
    
    public function get_foldertypes_doctype($type_id)
	{
		$foldertypes = array();
        if($type_id <> "") {
            $db = new Database();
            $stmt = $db->query("SELECT foldertype_id FROM ".$_SESSION['tablename']['fold_foldertypes_doctypes']." WHERE doctype_id = ?", array($type_id));
            //$this->show();
            while($res = $stmt->fetchObject())
            {
                array_push($foldertypes, $res->foldertype_id );
            }
        }
        return $foldertypes ;
    }


    public function get_folders_tree()
	{
		$folders = array();
		$db = new Database();
		$stmt = $db->query('SELECT folders_system_id, folder_name, parent_id, folder_level FROM folders WHERE foldertype_id not in (100) AND status NOT IN (\'DEL\') AND parent_id = 0 order by folder_id asc', array());
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$stmt2 = $db->query(
					"SELECT count(*) as total FROM res_view_letterbox WHERE status NOT IN ('DEL')", array()
					);
			$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
			$stmt3 = $db->query(
				"SELECT count(*) as total FROM folders WHERE foldertype_id not in (100) AND parent_id IN (".$row['folders_system_id'].")  AND status NOT IN ('DEL')"
			);
			$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
			$nbsp ='';
			for ($i=1; $i < $row['folder_level'] ; $i++) { 
				$nbsp .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';
			}
			$row['folder_name'] = $nbsp.$row['folder_name'];

			$folders[] = array(
				'parent_id' => $row['parent_id'],
				'folders_system_id' => $row['folders_system_id'],
				'folder_name' => $row['folder_name'],
				'folder_level' => $row['folder_level'],
				'nb_doc' => $row2['total'],
				'nb_subfolder' => $row3['total']
			);
			$folders = array_merge($folders,$this->get_folders_tree_children($row['folders_system_id']));
		}
        return $folders;
    }

    public function get_folders_tree_children($parent_id)
	{
		$folders = array();
		$db = new Database();
		$stmt = $db->query('SELECT folders_system_id, folder_name, parent_id, folder_level FROM folders WHERE foldertype_id not in (100) AND status NOT IN (\'DEL\') AND parent_id = \''.$parent_id.'\' order by folder_id asc', array());
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$stmt2 = $db->query(
					"SELECT count(*) as total FROM res_view_letterbox WHERE status NOT IN ('DEL')", array()
					);
			$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
			$stmt3 = $db->query(
				"SELECT count(*) as total FROM folders WHERE foldertype_id not in (100) AND parent_id IN (".$row['folders_system_id'].")  AND status NOT IN ('DEL')"
			);
			$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
			$nbsp ='';
			for ($i=1; $i < $row['folder_level'] ; $i++) { 
				$nbsp .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';
			}
			$row['folder_name'] = $nbsp.$row['folder_name'];

			$folders[] = array(
				'parent_id' => $row['parent_id'],
				'folders_system_id' => $row['folders_system_id'],
				'folder_name' => $row['folder_name'],
				'folder_level' => $row['folder_level'],
				'nb_doc' => $row2['total'],
				'nb_subfolder' => $row3['total']
			);
		}
        return $folders ;
    }
}
