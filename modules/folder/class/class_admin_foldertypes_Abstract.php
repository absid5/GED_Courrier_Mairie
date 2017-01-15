<?php

/*
 * Copyright (C) 2008-2016 Maarch
 *
 * This file is part of Maarch.
 *
 * Maarch is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Maarch is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Maarch.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once "core/class/class_security.php";
require_once "core/class/class_history.php";

abstract class foldertype_Abstract
{
    /**
    * Load data from the foldertypes_doctypes table in the session 
    * ( $_SESSION['m_admin']['foldertype']['doctypes']  array)
    *
    * @param    string  $id  foldertype identifier
    */

    protected function load_doctypes($id)
    {
        $db = new Database();
        $_SESSION['m_admin']['foldertype']['structures'] = array();
        $stmt = $db->query(
        	"SELECT doctypes_first_level_id FROM "
        	. $_SESSION['tablename']['fold_foldertypes_doctypes_level1']
        	. " WHERE foldertype_id = ?", array($id)
        );
        while ($res = $stmt->fetchObject()) {
            array_push(
            	$_SESSION['m_admin']['foldertype']['structures'], 
            	$res->doctypes_first_level_id
            );
        }
        $_SESSION['m_admin']['doctypes'] = array();
        for ($i = 0; $i < count(
        	$_SESSION['m_admin']['foldertype']['structures']
        ); $i ++
        ) {
            $tmp = array();
            $stmt = $db->query(
            	"SELECT d.description, d.type_id FROM "
            	. $_SESSION['tablename']['doctypes'] 
            	. " d WHERE d.doctypes_first_level_id = ?",
            	array($_SESSION['m_admin']['foldertype']['structures'][$i])
            );
            while ($res = $stmt->fetchObject()) {
                $typeId = $res->type_id;
                if (! in_array($typeId, $tmp)) {
                    array_push($tmp, $typeId);
                    array_push(
                    	$_SESSION['m_admin']['doctypes'], 
                    	array(
                    		'ID' => $typeId, 
                    		'COMMENT' => functions::show_string($res->description)
                    	)
                    );
                }
            }
        }

        $_SESSION['m_admin']['foldertype']['doctypes'] = array();
        $stmt = $db->query(
        	"SELECT d.description, fd.doctype_id FROM "
        	. $_SESSION['tablename']['fold_foldertypes_doctypes'] . " fd, "
        	. $_SESSION['tablename']['doctypes'] 
        	. " d WHERE d.type_id = fd.doctype_id and fd.foldertype_id = ? order by d.description ", array($id)
        );
        while ($res = $stmt->fetchObject()) {
            array_push(
            	$_SESSION['m_admin']['foldertype']['doctypes'], 
            	$res->doctype_id
            );
        }
        $_SESSION['m_admin']['load_doctypes'] = false;
    }


    /**
    * Form for the management of the foldertype.
    *
    * @param    string  $mode administrator mode (modification, suspension, authorization, delete)
    * @param    string  $id  foldertype identifier (empty by default)
    */
    public function formfoldertype($mode, $id = "")
    {
        $func = new functions();
        $db = new Database();
        $state = true;
       
        $sec = new security();
        $_SESSION['m_admin']['foldertype']['COLL_ID'] = "";
        if ($mode == "up") {
            $_SESSION['m_admin']['mode'] = "up";
            if (empty($_SESSION['error'])) {
                $stmt = $db->query(
                	"SELECT * FROM " 
                	. $_SESSION['tablename']['fold_foldertypes']
                	. " WHERE foldertype_id = ?", array($id)
                );
                if ($stmt->rowCount() == 0) {
                    $_SESSION['error'] = _FOLDERTYPE_MISSING;
                    $state = false;
                } else {
                    $_SESSION['m_admin']['foldertype']['foldertypeId'] = $id;
                    $line = $stmt->fetchObject();

                    $_SESSION['m_admin']['foldertype']['desc'] =  $line->foldertype_label;
                    $_SESSION['m_admin']['foldertype']['comment'] = $line->maarch_comment;

                    $_SESSION['m_admin']['foldertype']['indexes'] = $this->get_indexes(
                    	$id, 'minimal'
                    );
                    $_SESSION['m_admin']['foldertype']['mandatory_indexes'] = $this->get_mandatory_indexes($id);

                    if (! isset($_SESSION['m_admin']['load_doctypes']) 
                    	|| $_SESSION['m_admin']['load_doctypes'] == true
                    ) {
                        $this->load_doctypes($id);
                        $_SESSION['m_admin']['load_doctypes'] = false;
                    }

                    $_SESSION['m_admin']['foldertype']['COLL_ID'] = $line->coll_id;
                    $table_view = $sec->retrieve_view_from_coll_id(
                    	$_SESSION['m_admin']['foldertype']['COLL_ID']
                    );
                    $res = $db->query(
                    	"SELECT count(*) as total_doc FROM " . $table_view
                    	. " WHERE foldertype_id = ?",
                    	array($_SESSION['m_admin']['foldertype']['foldertypeId'])
                    );
                    if ($res) {
	                    $line = $res->fetchObject();
    	                $totalDoc = $line->total_doc;
                    }
                }
            }
        } else {
            $_SESSION['m_admin']['foldertype']['indexes'] = array();
            $_SESSION['m_admin']['foldertype']['mandatory_indexes'] = array();
        }
        //$this->show_array($_SESSION['m_admin']);
        if ($mode == "add") {
            echo '<h1><i class="fa fa-briefcase fa-2x"'
            	. ' title="" /></i> ' . _FOLDERTYPE_ADDITION . '</h1>';
        } else if ($mode == "up") {
            echo '<h1><i class="fa fa-briefcase fa-2x"'
            	. ' title="" /></i> ' . _FOLDERTYPE_MODIFICATION . '</h1>';
        }
        ?>
        <div id="inner_content" class="clearfix">
            <?php
        if ($state == false) {
        	echo "<br /><br /><br /><br />" . _FOLDERTYPE . ' ' . _UNKNOWN
        		. "<br /><br /><br /><br />";
        } else {
            ?>
            <div class="block">
            <form name="formfoldertype" id="formfoldertype" method="post" action="<?php  
            if ($mode == "up") { 
            	echo $_SESSION['config']['businessappurl']
            		. "index.php?display=true&module=folder&page=foldertype_up_db"; 
            } else if ($mode == "add") { 
            	echo $_SESSION['config']['businessappurl']
            		. "index.php?display=true&module=folder&page=foldertype_add_db"; 
            } 
            ?>" class="forms">
            	<input type="hidden" name="display" value="true" />
                <input type="hidden" name="module" value="folder" />
                <?php  
            if ($mode == "up") {
            	?>
                <input type="hidden" name="page" value="foldertype_up_db" />
                <?php 
            } else if ($mode == "add") {
            	?>
                <input type="hidden" name="page" value="foldertype_add_db" />
                <?php 
            } 
            ?>
               <input type="hidden" name="order" id="order" value="<?php 
            if (isset($_REQUEST['order'])) {
            	functions::xecho($_REQUEST['order']);
            }
            ?>" />
               <input type="hidden" name="order_field" id="order_field" value="<?php 
            if (isset($_REQUEST['order_field'])) {
            	functions::xecho($_REQUEST['order_field']);
        	}
        	?>" />
               <input type="hidden" name="what" id="what" value="<?php 
            if (isset($_REQUEST['what'])) {
            	functions::xecho($_REQUEST['what']);
        	}
        	?>" />
               <input type="hidden" name="start" id="start" value="<?php 
            if (isset($_REQUEST['start'])) {
            	functions::xecho($_REQUEST['start']);
        	}
        	?>" />
            <?php
            if ($mode == "up") {
            	?>
                <p style="width: 400px;margin: auto;">
                	<label><?php echo _ID;?> : </label>
                    <input name="foldertypeId" id="foldertypeId" type="text" value="<?php  
                functions::xecho(
                	$_SESSION['m_admin']['foldertype']['foldertypeId']
                ); 
                ?>" <?php  
                if ($mode == "up") { 
                	echo 'readonly="readonly" class="readonly"';
                } 
                ?> />
                    <input type="hidden"  name="id" value="<?php functions::xecho($id);?>" />
                    <input type="hidden"  name="mode" value="<?php functions::xecho($mode);?>" />
                </p>
                <?php
            }

            if ($mode == "up") {
            	if (isset($totalDoc) && $totalDoc > 0) {
                	?>
                    <p style="width: 400px;margin: auto;">
                    	<label><?php echo _COLLECTION;?> : </label>
                        <input name="collection_show" id="collection_show" type="text" value="<?php  
                    functions::xecho(
                    	$sec->retrieve_coll_label_from_coll_id(
                    		$_SESSION['m_admin']['foldertype']['COLL_ID']
                    	)
                    ); 
                    ?>" readonly="readonly" class="readonly" />
                    	<input name="collection" id="collection" type="hidden" value="<?php  
                    functions::xecho($_SESSION['m_admin']['foldertype']['COLL_ID']);
                    ?>" />
                     </p>
                     <p align="center">
                     <?php
                     echo _CANTCHANGECOLL . " " . $totalDoc . " "
                     	. _DOCUMENTS_EXISTS_FOR_COUPLE_FOLDER_TYPE_COLLECTION;
                     ?>
                     </p>
                     <?php
                 } else {
                 	?>
                    <p style="width: 400px;margin: auto;">
                    	<label for="collection"><?php echo _COLLECTION;?> : </label>
                        <select name="collection" id="collection">
                        	<option value="" ><?php echo _CHOOSE_COLLECTION;?></option>
                            <?php  
                    for ($i = 0; $i < count($_SESSION['collections']); $i ++) {
                    	?>
                        <option value="<?php  
                        functions::xecho($_SESSION['collections'][$i]['id']);
                        ?>" <?php  
                        if ($_SESSION['m_admin']['foldertype']['COLL_ID'] == $_SESSION['collections'][$i]['id']) { 
                        	echo 'selected="selected"';
                        }
                        ?> ><?php  
                        functions::xecho($_SESSION['collections'][$i]['label']);
                        ?></option>
                        <?php
                    }
                    ?>
                    	</select>
                    </p>
                    <?php
                }
            } else {
            	?>
                <p style="width: 400px;margin: auto;">
                	<label for="collection"><?php echo _COLLECTION;?> : </label>
                    <select name="collection" id="collection" >
                    	<option value="" ><?php echo _CHOOSE_COLLECTION;?></option>
                        <?php  
                for ($i = 0; $i < count($_SESSION['collections']); $i ++) {
                	?>
                    <option value="<?php  
                    functions::xecho($_SESSION['collections'][$i]['id']);
                    ?>" <?php  
                    if ($_SESSION['m_admin']['foldertype']['COLL_ID'] == $_SESSION['collections'][$i]['id']) { 
                    	echo 'selected="selected"';
                    }
                    ?> ><?php  
                    functions::xecho($_SESSION['collections'][$i]['label']);
                    ?></option>
                    <?php
                }
                ?>
                	</select>
                </p>
                <?php
            }
            ?>
            <p style="width: 400px;margin: auto;">
            	<label><?php echo _DESC;?> : </label>
                <input name="desc"  type="text" id="desc" value="<?php functions::xecho($_SESSION['m_admin']['foldertype']['desc']);?>" />
            </p>
            <p style="width: 400px;margin: auto;">
            	<label><?php echo _COMMENTS;?> : </label>
                <textarea  cols="30" rows="4"  name="comment"  id="comment" ><?php functions::xecho($_SESSION['m_admin']['foldertype']['comment']); ?></textarea>
            </p>
            <div id="opt_index"></div>
                <p class="buttons" style="text-align:center;">
                	<input type="submit" name="Submit" value="<?php echo _VALIDATE;?>" class="button" />
                    <input type="button" name="cancel" value="<?php echo _CANCEL;?>" class="button"  onclick="javascript:window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=foldertypes&amp;module=folder';"/>
                </p>
           </form>
           </div>
           <script type="text/javascript">
                	get_ft_opt_index('<?php
            			echo $_SESSION['config']['businessappurl'];
            		?>index.php?display=true&page=get_index&module=folder');
                </script>
         
                <?php
            }
        ?>
        </div>
        <?php
    }

    /**
    * Processes data returned by formgroups()
    *
    * @param    string  $mode administrator mode (modification, suspension, authorization, delete)
    */
    protected function foldertypeinfo($mode)
    {
        $func = new functions();

        if ($mode == "up") {
            if (empty($_REQUEST['id']) || !isset($_REQUEST['id'])) {
                $_SESSION['error'] .= _ID_MISSING;
            } else {
                $_SESSION['m_admin']['foldertype']['foldertypeId'] = $func->wash(
                	$_REQUEST['id'], "alphanum", _THE_ID
                );
            }
        }
        if (isset($_REQUEST['desc']) && ! empty($_REQUEST['desc'])) {
            $_SESSION['m_admin']['foldertype']['desc'] = $func->wash(
            	$_REQUEST['desc'], "no", _THE_DESC
            );
        } else {
            $_SESSION['error'] .= _DESC_MISSING;
        }
        if (isset($_REQUEST['collection']) && ! empty($_REQUEST['collection'])) {
            $_SESSION['m_admin']['foldertype']['COLL_ID'] = $func->wash(
            	$_REQUEST['collection'], "no", _COLLECTION
            );
        } else {
            $_SESSION['error'] .= _COLLECTION . ' ' . _MISSING;
        }
        if (isset($_REQUEST['comment']) && ! empty($_REQUEST['comment'])) {
            $_SESSION['m_admin']['foldertype']['comment'] = $_REQUEST['comment'];
        }
        $_SESSION['m_admin']['foldertype']['indexes'] = array();
        $_SESSION['m_admin']['foldertype']['mandatory_indexes'] = array();
        if (isset($_REQUEST['fields'])) {
            for ($i = 0; $i < count($_REQUEST['fields']); $i ++) {
                array_push(
                	$_SESSION['m_admin']['foldertype']['indexes'], 
                	$_REQUEST['fields'][$i]
                );
            }
        }
        if (isset($_REQUEST['mandatory_fields'])) {
            for ($i = 0; $i < count($_REQUEST['mandatory_fields']); $i ++) {
                if (! in_array(
                	$_REQUEST['mandatory_fields'][$i], 
                	$_SESSION['m_admin']['foldertype']['indexes']
                )
                ) {
                    $_SESSION['error'] .= _IF_CHECKS_MANDATORY_MUST_CHECK_USE;
                }
                array_push(
                	$_SESSION['m_admin']['foldertype']['mandatory_indexes'], 
                	$_REQUEST['mandatory_fields'][$i]
                );
            }
        }
        $_SESSION['m_admin']['foldertype']['order'] = $_REQUEST['order'];
        $_SESSION['m_admin']['foldertype']['order_field'] = $_REQUEST['order_field'];
        $_SESSION['m_admin']['foldertype']['what'] = $_REQUEST['what'];
        $_SESSION['m_admin']['foldertype']['start'] = $_REQUEST['start'];
    }

    /**
    * Add ou modify foldertype in the database
    *
    * @param string $mode up or add
    */
    public function addupfoldertype($mode)
    {
        // add ou modify basket in the database
        $this->foldertypeinfo($mode);
        $order = $_SESSION['m_admin']['foldertype']['order'];
        $orderField = $_SESSION['m_admin']['foldertype']['order_field'];
        $what = $_SESSION['m_admin']['foldertype']['what'];
        $start = $_SESSION['m_admin']['foldertype']['start'];
        $db = new Database();

        if (! empty($_SESSION['error'])) {
            if ($mode == "up") {
                if (! empty($_SESSION['m_admin']['foldertype']['foldertypeId'])) {
                    header(
                    	"location: " . $_SESSION['config']['businessappurl']
                    	. "index.php?page=foldertype_up&id="
                    	. $_SESSION['m_admin']['foldertype']['foldertypeId'] 
                    	. "&module=folder"
                    );
                    exit();
                } else {
                    header(
                    	"location: " . $_SESSION['config']['businessappurl']
                    	. "index.php?page=foldertypes&module=folder&order="
                    	. $order . "&order_field=" . $orderField . "&start="
                    	. $start . "&what=" . $what
                    );
                    exit();
                }
            } else if ($mode == "add") {
                header(
                	"location: " . $_SESSION['config']['businessappurl']
                	. "index.php?page=foldertype_add&module=folder"
                	);
                exit();
            }
        } else {
            if ($mode == "add") {
                $stmt = $db->query(
                	"SELECT foldertype_label FROM " 
                	. $_SESSION['tablename']['fold_foldertypes']
                	. " WHERE foldertype_label= ? ", array($_SESSION['m_admin']['foldertype']['desc'])
                );
                if ($stmt->rowCount() > 0) {
                    $_SESSION['error'] = $_SESSION['m_admin']['foldertype']['desc'] 
                    	. " " . _ALREADY_EXISTS . "<br />";
                    header(
                    	"location: " . $_SESSION['config']['businessappurl']
                    	. "index.php?page=foldertype_add&module=folder"
                    );
                    exit();
                } else {
                    $db->query(
                    	"INSERT INTO " 
                    	. $_SESSION['tablename']['fold_foldertypes']
                    	. " (foldertype_label, maarch_comment, coll_id) VALUES (?, ?, ?)",
                        array($_SESSION['m_admin']['foldertype']['desc'], $_SESSION['m_admin']['foldertype']['comment'], $_SESSION['m_admin']['foldertype']['COLL_ID'])
                    );
                    $stmt = $db->query(
                    	'SELECT foldertype_id FROM '
                    	. $_SESSION['tablename']['fold_foldertypes']
                    	. " WHERE foldertype_label = ? and maarch_comment = ?",
                        array($_SESSION['m_admin']['foldertype']['desc'], $_SESSION['m_admin']['foldertype']['comment'])
                    );
                    $res = $stmt->fetchObject();
                    $_SESSION['m_admin']['foldertype']['foldertypeId'] = $res->foldertype_id;
                    $this->load_db();

                    for ($i = 0; $i < count(
                    	$_SESSION['m_admin']['foldertype']['indexes']
                    ); $i ++
                    ) {
                        $mandatory = 'N';
                        if (in_array(
                        	$_SESSION['m_admin']['foldertype']['indexes'][$i], 
                        	$_SESSION['m_admin']['foldertype']['mandatory_indexes'] 
                        )
                        ) {
                            $mandatory = 'Y';
                        }
                        $stmt = $db->query(
                        	"INSERT INTO " 
                        	. $_SESSION['tablename']['fold_foldertypes_indexes']
                        	. " (foldertype_id, field_name, mandatory) values(?, ?, ?)",
                            array($_SESSION['m_admin']['foldertype']['foldertypeId'], $_SESSION['m_admin']['foldertype']['indexes'][$i], $mandatory)
                        );
                    }

                    if ($_SESSION['history']['foldertypeadd'] == "true") {
                        $hist = new history();
                        $hist->add(
                        	$_SESSION['tablename']['fold_foldertypes'], 
                        	$_SESSION['m_admin']['foldertype']['foldertypeId'],
                        	"ADD", 'foldertypeadd', _FOLDERTYPE_ADDED . " : " 
                        	. $_SESSION['m_admin']['foldertype']['foldertypeId'], 
                        	$_SESSION['config']['databasetype'], 'folder'
                        );
                    }
                    $this->clearfoldertypeinfos();
                    $_SESSION['info'] = _FOLDERTYPE_ADDED;
                    unset($_SESSION['m_admin']);
                    header(
                    	"location: " . $_SESSION['config']['businessappurl']
                    	. "index.php?page=foldertypes&module=folder&order="
                    	. $order . "&order_field=" . $orderField . "&start="
                    	. $start . "&what=" . $what
                    );
                    exit();
                }
            } else if ($mode == "up") {
                $db->query(
                	"UPDATE " . $_SESSION['tablename']['fold_foldertypes']
                	. " SET foldertype_label = ? , maarch_comment = ? , coll_id = ? where foldertype_id= ?",
                    array($_SESSION['m_admin']['foldertype']['desc'], $_SESSION['m_admin']['foldertype']['comment'], $_SESSION['m_admin']['foldertype']['COLL_ID'], $_SESSION['m_admin']['foldertype']['foldertypeId'])
                );
                $this->load_db();

                $db->query(
                	"DELETE FROM " 
                	. $_SESSION['tablename']['fold_foldertypes_indexes']
                	. " WHERE foldertype_id = ?",
                	array($_SESSION['m_admin']['foldertype']['foldertypeId'])
                );

                for ($i = 0; $i < count(
                	$_SESSION['m_admin']['foldertype']['indexes']
                ); $i ++
                ) {
                    $mandatory = 'N';
                    if (in_array(
                    	$_SESSION['m_admin']['foldertype']['indexes'][$i], 
                    	$_SESSION['m_admin']['foldertype']['mandatory_indexes'] 
                    )
                    ) {
                        $mandatory = 'Y';
                    }
                    $db->query(
                    	"INSERT INTO "
                    	. $_SESSION['tablename']['fold_foldertypes_indexes']
                    	. " ( foldertype_id, field_name, mandatory) values(?, ?, ?)",
                    array($_SESSION['m_admin']['foldertype']['foldertypeId'], $_SESSION['m_admin']['foldertype']['indexes'][$i], $mandatory)
                    );
                }
                if ($_SESSION['history']['foldertypeup'] == "true") {
                    $hist = new history();
                    $hist->add(
                    	$_SESSION['tablename']['fold_foldertypes'], 
                    	$_SESSION['m_admin']['foldertype']['foldertypeId'], 
                    	"UP", 'foldertypeup', _FOLDERTYPE_UPDATE . " : " 
                    	. $_SESSION['m_admin']['foldertype']['foldertypeId'], 
                    	$_SESSION['config']['databasetype'], 'folder'
                    );
                }
                $this->clearfoldertypeinfos();
                $_SESSION['info'] = _FOLDERTYPE_UPDATE;
                unset($_SESSION['m_admin']);
                header(
                	"location: " . $_SESSION['config']['businessappurl']
                	. "index.php?page=foldertypes&module=folder&order="
                	. $order . "&order_field=" . $orderField . "&start=" 
                	. $start . "&what=" . $what
                );
                exit();
            }
        }
    }

    /**
    * Clear the session variable for the foldertypes
    */
    protected function clearfoldertypeinfos()
    {
        unset($_SESSION['m_admin']);
    }

    /**
    * Load the foldertype data in the database
    */
    protected function load_db()
    {
        $db = new Database();
        $db->query(
        	"DELETE FROM " . $_SESSION['tablename']['fold_foldertypes_doctypes'] 
        	. " WHERE foldertype_id= ?",
        	array($_SESSION['m_admin']['foldertype']['foldertypeId']) 
        );

        for ($i = 0; $i < count(
        	$_SESSION['m_admin']['foldertype']['doctypes'] 
        ); $i ++
        ) {
            $db->query(
            	"INSERT INTO " 
            	. $_SESSION['tablename']['fold_foldertypes_doctypes']
            	. " values (?, ?)",
                array($_SESSION['m_admin']['foldertype']['foldertypeId'], $_SESSION['m_admin']['foldertype']['doctypes'][$i])
            );
        }
    }

    /**
    *  delete foldertype in the database
    *
    * @param string $id foldertype identifier
    * @param string $mode allow, ban or del
    */
    public function adminfoldertype($id,$mode)
    {
        $order = $_REQUEST['order'];
        $orderField = $_REQUEST['order_field'];
        $start = $_REQUEST['start'];
        $what = $_REQUEST['what'];
        $db = new Database();
        if (! empty($_SESSION['error'])) {
            header(
            	"location: " . $_SESSION['config']['businessappurl']
            	. "index.php?page=foldertypes&module=folder&order=" . $order
            	. "&order_field=" . $orderField . "&start=" . $start . "&what="
            	. $what
            );
            exit();
        } else {
            $stmt = $db->query(
            	"SELECT foldertype_id FROM "
            	. $_SESSION['tablename']['fold_foldertypes']
            	. " WHERE foldertype_id= ?", array($id)
            );
            if ($stmt->rowCount() == 0) {
                $_SESSION['error'] = _FOLDERTYPE_MISSING;
                header(
                	"location: " . $_SESSION['config']['businessappurl']
                	. "index.php?page=foldertypes&module=folder&order=" . $order
                	. "&order_field=" . $orderField . "&start=" . $start
                	. "&what=" . $what
                );
                exit();
            } else {
                $info = $stmt->fetchObject();
                if ($mode == "del") {
                    $db->query(
                    	"DELETE FROM " 
                    	. $_SESSION['tablename']['fold_foldertypes']
                    	. "  WHERE foldertype_id = ?", array($id) 
                    );
                    $db->query(
                    	"DELETE FROM "
                    	. $_SESSION['tablename']['fold_foldertypes_doctypes']
                    	. "  WHERE foldertype_id = ?", array($id)
                    );
                    $db->query(
                    	"DELETE FROM " 
                    	. $_SESSION['tablename']['fold_foldertypes_indexes']
                    	. "  WHERE foldertype_id = ?", array($id)
                    );

                    if ($_SESSION['history']['foldertypedel'] == "true") {
                        $users = new history();
                        $users->add(
                        	$_SESSION['tablename']['fold_foldertypes'], $id,
                        	"DEL", 'foldertypedel', _FOLDERTYPE_DELETION . " : " . $id, 
                        	$_SESSION['config']['databasetype'], 'folder'
                        );
                    }
                    $_SESSION['error'] = _FOLDERTYPE_DELETION;
                }
                header(
                	"location: " . $_SESSION['config']['businessappurl']
                	. "index.php?page=foldertypes&module=folder&order=" . $order
                	. "&order_field=" . $orderField . "&start=" . $start 
                	. "&what=" . $what
                );
                exit();
            }
        }
    }

    /**
    * Returns in an array all indexes possible
    *
    * @return array $indexes[$i]
    *                   ['column'] : database field of the index
    *                   ['label'] : Index label
    *                   ['type'] : Index type ('date', 'string', 'integer' or 'float')
    *                   ['img'] : url to the image index
    */
  public function get_all_indexes()
    {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . 'folder' . DIRECTORY_SEPARATOR . 'xml'
            . DIRECTORY_SEPARATOR . 'folder_index.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR 
                . 'folder' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'folder_index.xml';
        } else {
            $path = 'modules' . DIRECTORY_SEPARATOR . 'folder'
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR 
                .'folder_index.xml';
        }
        $xmlfile = simplexml_load_file($path);
        include_once 'modules' . DIRECTORY_SEPARATOR . 'folder'
            . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
            . $_SESSION['config']['lang'] . '.php';
        $indexes = array();
        foreach ($xmlfile->INDEX as $item) {
            $label = (string) $item->label;
            if (!empty($label) && defined($label) && constant($label) <> NULL) {
                $label = constant($label);
            }

            $img = (string) $item->img;
            if (isset($item->default_value) && ! empty($item->default_value)) {
                $default = (string) $item->default_value;
                if (!empty($default) && defined($default) 
                    && constant($default) <> NULL
                ) {

                    $default = constant($default);
                }

            } else {
                $default = false;
            }
            if (isset($item->values_list)) {
                $values = array();
                $list = $item->values_list ;
                foreach ($list->value as $val) {
                    $labelVal = (string) $val->label;
                    if (!empty($labelVal) && defined($labelVal) 
                        && constant($labelVal) <> NULL
                    ) {

                        $labelVal = constant($labelVal);
                    }
                   

                    array_push(
                        $values,
                        array(
                            'id' => (string) $val->id,
                            'label' => $labelVal,
                        )

                    );
                }
                $tmpArr = array(

                    'column' => (string) $item->column,
                    'label' => $label,
                    'type' => (string) $item->type,
                    'img' => $_SESSION['config']['businessappurl']
                    . 'static.php?filename=' . $img,
                    'type_field' => 'select',
                    'values' => $values,
                    'default_value' => $default
                );
            } else if (isset($item->table)) {
                $values = array();
                $tableXml = $item->table;
                //$this->show_array($tableXml);
                $tableName = (string) $tableXml->table_name;
                $foreignKey = (string) $tableXml->foreign_key;
                $foreignLabel = (string) $tableXml->foreign_label;
                $whereClause = (string) $tableXml->where_clause;
                $order = (string) $tableXml->order;
                $query = "SELECT " . $foreignKey . ", " . $foreignLabel
                       . " FROM " . $tableName;
                if (isset($whereClause) && ! empty($whereClause)) {
                    $query .= " WHERE " . $whereClause;
                }
                if (isset($order) && ! empty($order)) {
                    $query .= ' '.$order;
                }

                $stmt = $db->query($query);
                while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
                     array_push(
                         $values,
                         array(
                             'id' => (string) $res[0],
                             'label' => (string) $res[1],


                         )
                     );
                }
                $tmpArr = array(
                    'column' => (string) $item->column,
                    'label' => $label,
                    'type' => (string) $item->type,
                    'img' => $_SESSION['config']['businessappurl']
                    . 'static.php?filename=' . $img,
                    'type_field' => 'select',
                    'values' => $values,
                    'default_value' => $default,
                );
            } else {
                $tmpArr = array(

                    'column' => (string) $item->column,
                    'label' => $label,
                    'type' => (string) $item->type,
                    'img' => $_SESSION['config']['businessappurl']
                    . 'static.php?filename=' . $img,
                    'type_field' => 'input',
                    'default_value' => $default,
                );
            }
            //$this->show_array($tmpArr);
            array_push($indexes, $tmpArr);
        }
        return $indexes;
    }

    /**
    * Returns in an array all indexes for a doctype
    *
    * @param string $foldertypeId Document type identifier
    * @param string $mode Mode 'full' or 'minimal', 'full' by default
    * @return array array of the indexes, depends on the chosen mode :
    *       1) mode = 'full' : $indexes[field_name] :  the key is the field name in the database
    *                                       ['label'] : Index label
    *                                       ['type'] : Index type ('date', 'string', 'integer' or 'float')
    *                                       ['img'] : url to the image index
    *       2) mode = 'minimal' : $indexes[$i] = field name in the database
    */
    public function get_indexes($foldertypeId, $mode= 'full')
    {
        $fields = array();
        $db = new Database();
        $stmt = $db->query(
        	"SELECT field_name FROM "
        	. $_SESSION['tablename']['fold_foldertypes_indexes']
        	. " WHERE  foldertype_id = ?", array($foldertypeId)
        );

        while ($res = $stmt->fetchObject()) {
            array_push($fields, $res->field_name);
        }
        if ($mode == 'minimal') {
            return $fields;
        }

        $indexes = array();
        if (file_exists(
        	$_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
        	. $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
        	. DIRECTORY_SEPARATOR . 'folder' . DIRECTORY_SEPARATOR . 'xml'
        	. DIRECTORY_SEPARATOR . 'folder_index.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom' 
            	. DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
            	. DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
            	. 'folder' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            	. 'folder_index.xml';
        } else {
            $path = 'modules' . DIRECTORY_SEPARATOR . 'folder' 
            	. DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            	. 'folder_index.xml';
        }
        $xmlfile = simplexml_load_file($path);
        include_once 'modules' . DIRECTORY_SEPARATOR . 'folder'
        	. DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
        	. $_SESSION['config']['lang'] . '.php';
        foreach ($xmlfile->INDEX as $item) {
            $label = (string) $item->label;
            if (!empty($label) && defined($label) && constant($label) <> NULL) {
            	$label = constant($label);
            }
            $col = (string) $item->column;
            $img = (string) $item->img;
            if (isset($item->default_value) && ! empty($item->default_value)) {
                $default = (string) $item->default_value;
            	if (! empty($default) && defined($default) 
            		&& constant($default) <> NULL
            	) {
            		$default = constant($default);
            	}
            } else {
                $default = false;
            }
            if (in_array($col, $fields)) {
                if (isset($item->values_list)) {
                    $values = array();
                    $list = $item->values_list ;
                    foreach ($list->value as $val) {
                        $labelVal = (string) $val->label;
                   		if (! empty($labelVal) && defined($labelVal) 
            				&& constant($labelVal) <> NULL
            			) {
            				$labelVal = constant($labelVal);
            			}
                        array_push(
                        	$values, 
                        	array(
                        		'id' => (string) $val->id, 
                        		'label' => $labelVal
                        	)
                        );
                    }
                    $indexes[$col] = array( 
                    	'label' => $label, 
                    	'type' => (string) $item->type, 
                    	'img' => $_SESSION['config']['businessappurl']
                    		. 'static.php?module=folder&filename=' . $img, 
                    	'type_field' => 'select', 
                    	'values' => $values, 
                    	'default_value' => $default,
                    );
                } else if (isset($item->table)) {
                    $values = array();
                    $tableXml = $item->table;
                    //$this->show_array($tableXml);
                    $tableName = (string) $tableXml->table_name;
                    $foreignKey = (string) $tableXml->foreign_key;
                    $foreignLabel = (string) $tableXml->foreign_label;
                    $whereClause = (string) $tableXml->where_clause;
                    $order = (string) $tableXml->order;
                    $query = "SELECT " . $foreignKey . ", " . $foreignLabel
                           . " FROM " . $tableName;
                    if (isset($whereClause) && ! empty($whereClause)) {
                        $query .= " where " . $whereClause;
                    }
                    if (isset($order) && ! empty($order)) {
                        $query .= ' '.$order;
                    }
                    $stmt = $db->query($query);
                    while ($res = $stmt->fetchObject()) {
                         array_push(
                             $values,
                             array(
                                 'id' => (string) $res->{$foreignKey},
                                 'label' => $res->{$foreignLabel},
                             )
                         );
                    }
                    $indexes[$col] = array(
                        'label' => $label,
                        'type' => (string) $item->type,
                        'img' => $_SESSION['config']['businessappurl']
                        . 'static.php?filename=' . $img,
                        'type_field' => 'select',
                        'values' => $values,
                        'default_value' => $default,
                    );
                } else {
                    $indexes[$col] = array( 
                    	'label' => $label, 
                    	'type' => (string) $item->type, 
                    	'img' => $_SESSION['config']['businessappurl']
                    		. 'static.php?module=folder&filename=' . $img, 
                    	'type_field' => 'input', 
                    	'default_value' => $default
                    );
                }
            }
        }
        return $indexes;
    }

    /**
    * Returns in an array all manadatory indexes possible for a given type
    *
    * @param string $foldertypeId Document type identifier
    * @return array Array of the manadatory indexes, $indexes[$i] = field name in the db
    */
    public function get_mandatory_indexes($foldertypeId)
    {
        $fields = array();
        $db = new Database();
        $stmt = $db->query(
        	"SELECT field_name FROM " 
        	. $_SESSION['tablename']['fold_foldertypes_indexes']
        	. " WHERE foldertype_id = ? and mandatory = 'Y'", array($foldertypeId)
        );

        while ($res = $stmt->fetchObject()) {
            array_push($fields, $res->field_name);
        }
        return $fields;
    }

    /**
    * Checks validity of indexes
    *
    * @param string $foldertypeId Folder type identifier
    * @param array $values Values to check
    * @return bool true if checks is ok, false if an error occurs
    */
    public function check_indexes($foldertypeId, $values)
    {
        if (empty($foldertypeId)) {
            return false;
        }

        // Checks the manadatory indexes
        $indexes = $this->get_indexes($foldertypeId);
        $mandatoryIndexes = $this->get_mandatory_indexes($foldertypeId);

        for ($i = 0; $i < count($mandatoryIndexes); $i ++) {
            if (empty($values[$mandatoryIndexes[$i]])) { // Pb 0
                $_SESSION['error'] .= $indexes[$mandatoryIndexes[$i]]['label']
                	. ' ' . _IS_EMPTY;
                return false;
            }
        }

        // Checks type indexes
        $datePattern = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";
        foreach (array_keys($values) as $key) {
            if (! empty($_SESSION['error'])) {
                return false;
            }
            if ($indexes[$key]['type'] == 'date' && !empty($values[$key])) {
                if (preg_match($datePattern, $values[$key]) == 0) {
                    $_SESSION['error'] .= $indexes[$key]['label'] . " "
                    	. _WRONG_FORMAT . ".<br/>";
                    return false;
                }
            } else if ($indexes[$key]['type'] == 'string'  
            	&& ! empty($values[$key])
            ) {
                $fieldValue = functions::wash(
                	$values[$key], "no", $indexes[$key]['label']
                );
            } else if ($indexes[$key]['type'] == 'float' 
            	&& ! empty($values[$key]) 
            ) {
                $fieldValue = functions::wash(
                	$values[$key], "float", $indexes[$key]['label']
                );
            } else if ($indexes[$key]['type'] == 'integer' 
            	&& ! empty($values[$key]) 
            ) {
                $fieldValue = functions::wash(
                	$values[$key], "num", $indexes[$key]['label']
                );
            }

            if (isset($indexes[$key]['values']) 
            	&& count($indexes[$key]['values']) > 0
            ) {
                $found = false;
                for ($i = 0; $i < count($indexes[$key]['values']); $i ++) {
                    if ($values[$key] == $indexes[$key]['values'][$i]['id'] || $values[$key] == "") {
                        $found = true;
                        break;
                    }
                }
                if (! $found) {
                    $_SESSION['error'] .= $indexes[$key]['label'] . " : "
                    	. _ITEM_NOT_IN_LIST . ".<br/>";
                    return false;
                }
            }
        }

        return true;
    }

    /**
    * Returns a string to use in an sql update query
    *
    * @param string $foldertypeId Folder type identifierer
    * @param array $values Values to update
    * @return string Part of the update sql query
    */
    public function get_sql_update($foldertypeId, $values)
    {
        $indexes = $this->get_indexes($foldertypeId);

        $req = '';
        foreach (array_keys($values) as $key) {
            if ($indexes[$key]['type'] == 'date' && ! empty($values[$key])) {
                $req .= ", " . $key . " = '" 
                	. functions::format_date_db($values[$key]) . "'";
            } else if ($indexes[$key]['type'] == 'string' 
            	&& ! empty($values[$key])
            ) {
                $req .= ", " . $key . " = '"
                	. functions::protect_string_db($values[$key]) . "'";
            } else if ($indexes[$key]['type'] == 'float' 
            	&& ! empty($values[$key])
            ) {
                $req .= ", " . $key . " = " . $values[$key];
            } else if ($indexes[$key]['type'] == 'integer' 
            	&& ! empty($values[$key])
            ) {
                $req .= ", " . $key . " = " . $values[$key];
            }
        }
        return $req;
    }

    /**
    * Returns an array used to insert data in the database
    *
    * @param string $foldertypeId Folder type identifier
    * @param array $values Values to update
    * @param array $data Return array
    * @return array
    */
    public function fill_data_array($foldertypeId, $values, $data = array())
    {
        $indexes = $this->get_indexes($foldertypeId);

        foreach (array_keys($values) as $key) {
            if ($indexes[$key]['type'] == 'date' && ! empty($values[$key])) {
                array_push(
                	$data, 
                	array(
                		'column' => $key, 
                		'value' => functions::format_date_db($values[$key]), 
                		'type' => "date"
                	)
                );
            } else if ($indexes[$key]['type'] == 'string' 
            	&& ! empty($values[$key])
            ) {
                array_push(
                	$data, 
                	array(
                		'column' => $key, 
                		'value' => functions::protect_string_db($values[$key]), 
                		'type' => "string"
                	)
                );
            } else if ($indexes[$key]['type'] == 'float' 
            	&& ! empty($values[$key])
            ) {
                array_push(
                	$data, 
                	array(
                		'column' => $key, 
                		'value' => $values[$key], 
                		'type' => "float"
                	)
                );
            } else if ($indexes[$key]['type'] == 'integer' 
            	&& !empty($values[$key])
            ) {
                array_push(
                	$data, 
                	array(
                		'column' => $key, 
                		'value' => $values[$key], 
                		'type' => "integer"
                	)
                );
            }
        }
        return $data;
    }

    /**
    * Inits in the database the indexes for a given folder id to null
    *
    * @param string $folderSysId Folder identifier
    */
    public function inits_opt_indexes($folderSysId)
    {
        $indexes = $this->get_all_indexes( );
        $query = "UPDATE " . $_SESSION['tablename']['fold_folders'] . " SET ";
        for ($i = 0; $i < count($indexes); $i ++) {
            $query .= $indexes[$i]['column'] . " = NULL, ";
        }
        $query = preg_replace(
        	'/, $/', ' WHERE folders_system_id = ?', $query
        );
        $arrayPDO = array($folderSysId);

        $db = new Database();
        $db->query($query, $arrayPDO);
    }


    public function search_checks($indexes, $fieldName, $val, $table_or_view = '' )
    {
		if (empty($table_or_view)) { $table_or_view = $_SESSION['tablename']['fold_folders']; }
		
        $whereRequest = '';
        $datePattern = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";
        foreach (array_keys($indexes) as $key) {
            if ($key == $fieldName) {// type == 'string'
                if (!empty($val)) {
                    $whereRequest .= " lower(" . $table_or_view
                        . "." . $key . ") like lower('%" 
                        . functions::protect_string_db($val) . "%') and ";
                }
                break;
            } else if ($key.'_from' == $fieldName || $key.'_to' == $fieldName) { 
            	// type == 'date'
                if (preg_match($datePattern, $val) == false) {
                    $_SESSION['error'] .= _WRONG_DATE_FORMAT . ' : ' . $val;
                } else {
                    $whereRequest .= " (" . $table_or_view
                    	. "." . $key . " >= '" . functions::format_date_db($val)
                    	. "') and ";
                }
                break;
            } else if ($key . '_min' == $fieldName 
            	|| $key . '_max' == $fieldName 
            ) {
                if ($indexes[$key]['type'] == 'integer' 
                	|| $indexes[$key]['type'] == 'float'
                ) {
                    if ($indexes[$key]['type'] == 'integer') {
                        $checkedVal = functions::wash(
                        	$val, "num", $indexes[$key]['label'], "no"
                        );
                    } else {
                        $checkedVal = functions::wash(
                        	$val, "float", $indexes[$key]['label'], "no"
                        );
                    }
                    if (empty($_SESSION['error'])) {
                        $whereRequest .= " (" . $table_or_view
                        	. "." . $key . " >= " . $checkedVal . ") and ";
                    }
                }
                break;
            }
        }
        return  $whereRequest;
    }
}
