<?php

/*
*
*   Copyright 2015 Maarch
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
* Types Class
*
* Contains all the function to manage the doctypes
*
* @package  Maarch
* @version 2.0
* @since 10/2005
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*
*/

require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    ."class_security.php";
require_once 'core/core_tables.php';
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_history.php";

abstract class types_Abstract extends database
{

    /**
    * Form to add, modify or propose a doc type
    *
    * @param string $mode val, up or prop
    * @param integer $id type identifier, empty by default
    */
    public function formtype($mode, $id="")
    {
        // form to add, modify or proposale a doc type
        $func = new functions();
        $core = new core_tools();
        $sec = new security();
        $db = new Database();
        $state = true;
        if (! isset($_SESSION['m_admin']['doctypes'])) {
            $this->cleartypeinfos();
        }
        if ($mode <> "prop" && $mode <> "add") {
            $stmt = $db->query(
                "SELECT * FROM " . DOCTYPES_TABLE . " WHERE type_id = ?", array($id)
            );
            if ($stmt->rowCount() == 0) {
                $_SESSION['error'] = _DOCTYPE . ' ' . _ALREADY_EXISTS;
                $state = false;
            } else {
                $_SESSION['m_admin']['doctypes'] = array();
                $line = $stmt->fetchObject();
                $_SESSION['m_admin']['doctypes']['TYPE_ID'] = $line->type_id;
                $_SESSION['m_admin']['doctypes']['COLL_ID'] = $line->coll_id;
                $_SESSION['m_admin']['doctypes']['COLL_LABEL'] = $_SESSION['m_admin']['doctypes']['COLL_ID'];
                for ($i = 0; $i < count($_SESSION['collections']); $i ++) {
                    if ($_SESSION['collections'][$i]['id'] == $_SESSION['m_admin']['doctypes']['COLL_ID']) {
                        $_SESSION['m_admin']['doctypes']['COLL_LABEL'] = $_SESSION['collections'][$i]['label'];
                        break;
                    }
                }
                $_SESSION['m_admin']['doctypes']['LABEL'] = $this->show_string(
                    $line->description
                );
                $_SESSION['m_admin']['doctypes']['SUB_FOLDER'] = $line->doctypes_second_level_id;
                $_SESSION['m_admin']['doctypes']['VALIDATE'] = $line->enabled;
                $_SESSION['m_admin']['doctypes']['TABLE'] = $line->coll_id;
                $_SESSION['m_admin']['doctypes']['ACTUAL_COLL_ID'] = $line->coll_id;
                $_SESSION['m_admin']['doctypes']['indexes'] = $this->get_indexes(
                    $line->type_id, $line->coll_id, 'minimal'
                );
                $_SESSION['m_admin']['doctypes']['mandatory_indexes'] = $this->get_mandatory_indexes(
                    $line->type_id, $line->coll_id
                );
                $_SESSION['service_tag'] = 'doctype_up';
                $core->execute_modules_services(
                    $_SESSION['modules_services'], 'doctype_up', "include"
                );
                $core->execute_app_services($_SESSION['app_services'], 'doctype_up', 'include');
            }
        } else {// mode = add
            $_SESSION['m_admin']['doctypes']['indexes'] = array();
            $_SESSION['m_admin']['doctypes']['mandatory_indexes'] = array();
            $_SESSION['service_tag'] = 'doctype_add';
            echo $core->execute_modules_services(
                $_SESSION['modules_services'], 'doctype_up', "include"
            );
            $core->execute_app_services(
                $_SESSION['app_services'], 'doctype_up', 'include'
            );
            $_SESSION['service_tag'] = '';
        }
        ?>
        <h1><i class="fa fa-files-o fa-2x"></i>
        <?php
        if ($mode == "up") {
            echo _DOCTYPE_MODIFICATION;
        } else if ($mode == "add") {
            echo _ADD_DOCTYPE;
        }
        ?>
        </h1>
        <div id="inner_content" class="clearfix">
        <?php
        if ($state == false) {
            echo "<br /><br /><br /><br />" . _DOCTYPE . ' ' . _UNKOWN
                . "<br /><br /><br /><br />";
        } else {
            $arrayColl = $sec->retrieve_insert_collections();
            ?>
            <br/><br/>
            <form name="frmtype" id="frmtype" method="post" action="<?php
            echo $_SESSION['config']['businessappurl'];
            ?>index.php?page=types_up_db" class="forms">
            <input type="hidden" name="order" id="order" value="<?php
            functions::xecho($_REQUEST['order']);
            ?>" />
            <input type="hidden" name="order_field" id="order_field" value="<?php
            functions::xecho($_REQUEST['order_field']);
            ?>" />
            <input type="hidden" name="what" id="what" value="<?php
            functions::xecho($_REQUEST['what']);
            ?>" />
            <input type="hidden" name="start" id="start" value="<?php
            functions::xecho($_REQUEST['start']);
            ?>" />
            <div class="block">
                <input  type="hidden" name="mode" value="<?php functions::xecho($mode);?>" />
                <p>
                <label><?php echo _ATTACH_SUBFOLDER;?> : </label>
                <select name="sous_dossier" id="sous_dossier" class="listext" onchange="">
                    <option value=""><?php echo _CHOOSE_SUBFOLDER;?></option>
                    <?php
            for ($i = 0; $i < count($_SESSION['sous_dossiers']); $i ++) {
                ?>
                <option value="<?php
                functions::xecho($_SESSION['sous_dossiers'][$i]['ID']);
                ?>" <?php
                if (isset($_SESSION['m_admin']['doctypes']['SUB_FOLDER'])
                    && $_SESSION['sous_dossiers'][$i]['ID'] == $_SESSION['m_admin']['doctypes']['SUB_FOLDER']
                ) {
                    echo "selected=\"selected\" " ;
                }
                echo 'class="' . $_SESSION['sous_dossiers'][$i]['STYLE'] . '"';
                ?>><?php
                functions::xecho($_SESSION['sous_dossiers'][$i]['LABEL']);
                ?></option>
                <?php
            }
            ?>
            </select>
            </p>
            <p>
                <label for="collection"><?php echo _COLLECTION;?> : </label>
                <select name="collection" id="collection" onchange="get_opt_index('<?php
            echo $_SESSION['config']['businessappurl'];
            ?>index.php?display=true&page=get_index', this.options[this.options.selectedIndex].value);">
                    <option value="" ><?php echo _CHOOSE_COLLECTION;?></option>
            <?php
            for ($i = 0; $i < count($arrayColl); $i ++) {
                ?>
                <option value="<?php
                functions::xecho($arrayColl[$i]['id']);
                ?>" <?php
                if (isset($_SESSION['m_admin']['doctypes']['COLL_ID'])
                    && $_SESSION['m_admin']['doctypes']['COLL_ID'] == $arrayColl[$i]['id']
                ) {
                    echo 'selected="selected"';
                }
                ?> ><?php functions::xecho($arrayColl[$i]['label']);?></option>
                <?php
            }
             ?>
             </select>
             </p>
             <?php
            if ($mode == "up") {
                ?>
                <p>
                    <label for="id"><?php echo _ID;?> : </label>
                    <input type="text" class="readonly" name="idbis" value="<?php
                functions::xecho($id);
                ?>" readonly="readonly" />
                    <input type="hidden" name="id" value="<?php functions::xecho($id);?>" />
                </p>
                <?php
            }
            ?>
            <p>
                <label for="label"><?php echo _WORDING;?> : </label>
                <input name="label" type="text" class="textbox" id="label" value="<?php
            if (isset($_SESSION['m_admin']['doctypes']['LABEL'])) {
                functions::xecho($func->show_str($_SESSION['m_admin']['doctypes']['LABEL']));
            }
            ?>"/>
            </p>
            <?php
            $_SESSION['service_tag'] = 'frm_doctype';
            $core->execute_app_services(
                $_SESSION['app_services'], 'doctype_up', 'include'
            );
            ?>
            <div class="block_end">&nbsp;</div>
            <br/>
            <?php
            $core->execute_modules_services(
                $_SESSION['modules_services'], 'doctype_up', "include"
            );
            $_SESSION['service_tag'] = '';
            ?>
            <div id="opt_index"></div>
                <p class="buttons">
            <?php
            if ($mode == "up") {
                ?>
                <input class="button" type="submit" name="Submit" value="<?php
                echo _MODIFY_DOCTYPE;
                ?>"/>
                <?php
            } else if ($mode == "add") {
                ?>
                <input type="submit" class="button"  name="Submit" value="<?php
                echo _ADD_DOCTYPE;
                ?>" />
                <?php
            }
            ?>
            <input type="button" class="button"  name="cancel" value="<?php
            echo _CANCEL;
            ?>" onclick="javascript:window.location.href='<?php
            echo $_SESSION['config']['businessappurl'];
            ?>index.php?page=types';"/>
            </p>
            </div>
            </form>
            </div>
                <script type="text/javascript">
                var coll_list = $('collection');
                get_opt_index('<?php
            echo $_SESSION['config']['businessappurl'];
            ?>index.php?display=true&page=get_index', coll_list.options[coll_list.options.selectedIndex].value);
                </script>
                <script type="text/javascript">
            
                </script>
            <?php
         }
         ?>
    <?php
    }

    /**
    * Checks the formtype data
    */
    protected function typesinfo()
    {
        $db = new Database();
        $core = new core_tools();
        $func = new functions();
        if (! isset($_REQUEST['mode'])) {
            $_SESSION['error'] = _UNKNOWN_PARAM . "<br />";
        }

        if (isset($_REQUEST['label']) && ! empty($_REQUEST['label'])) {
            $_SESSION['m_admin']['doctypes']['LABEL'] = $func->wash(
                $_REQUEST['label'], "no", _THE_WORDING, 'yes', 0, 255
            );
        } else {
            $_SESSION['error'] .= _WORDING . ' ' . _IS_EMPTY;
        }

        $_SESSION['service_tag'] = "doctype_info";
        echo $core->execute_modules_services(
            $_SESSION['modules_services'], 'doctype_info', "include"
        );
        $core->execute_app_services(
            $_SESSION['app_services'], 'doctype_up', 'include'
        );
        $_SESSION['service_tag'] = '';
        if (! isset($_REQUEST['collection']) || empty($_REQUEST['collection'])) {
            $_SESSION['error'] .= _COLLECTION . ' ' . _IS_MANDATORY;
        } else {
            $_SESSION['m_admin']['doctypes']['COLL_ID'] = $_REQUEST['collection'];
            $_SESSION['m_admin']['doctypes']['indexes'] = array();
            $_SESSION['m_admin']['doctypes']['mandatory_indexes'] = array();
            if (isset($_REQUEST['fields'])) {
                for ($i = 0; $i < count($_REQUEST['fields']); $i ++) {
                    array_push(
                        $_SESSION['m_admin']['doctypes']['indexes'],
                        $_REQUEST['fields'][$i]
                    );
                }
            }
            if (isset($_REQUEST['mandatory_fields'])) {
                for ($i = 0; $i < count($_REQUEST['mandatory_fields']); $i ++) {
                    if (! in_array(
                        $_REQUEST['mandatory_fields'][$i],
                        $_SESSION['m_admin']['doctypes']['indexes']
                    )
                    ) {
                        $_SESSION['error'] .= _IF_CHECKS_MANDATORY_MUST_CHECK_USE;
                    }
                    array_push(
                        $_SESSION['m_admin']['doctypes']['mandatory_indexes'],
                        $_REQUEST['mandatory_fields'][$i]
                    );
                }
            }
        }
        if (! isset($_REQUEST['sous_dossier'])
            || empty($_REQUEST['sous_dossier'])
        ) {
            $_SESSION['error'] .= _THE_SUBFOLDER . ' ' . _IS_MANDATORY;
        } else {
            $_SESSION['m_admin']['doctypes']['SUB_FOLDER'] = $func->wash(
                $_REQUEST['sous_dossier'], "no", _THE_SUBFOLDER
            );

            $stmt = $db->query(
                "SELECT doctypes_first_level_id as id FROM "
                . $_SESSION['tablename']['doctypes_second_level']
                . " WHERE doctypes_second_level_id = ?",
                 array($_REQUEST['sous_dossier'])
            );
            $res = $stmt->fetchObject();
            $_SESSION['m_admin']['doctypes']['STRUCTURE'] = $res->id;
        }
        $_SESSION['m_admin']['doctypes']['order'] = $_REQUEST['order'];
        $_SESSION['m_admin']['doctypes']['order_field'] = $_REQUEST['order_field'];
        $_SESSION['m_admin']['doctypes']['what'] = $_REQUEST['what'];
        $_SESSION['m_admin']['doctypes']['start'] = $_REQUEST['start'];
    }

    /**
    * Modify, add or validate a doctype
    */
    public function uptypes()
    {
        $db = new Database();
        // modify, add or validate a doctype
        $core = new core_tools();
        $this->typesinfo();
        $order = $_SESSION['m_admin']['doctypes']['order'];
        $orderField = $_SESSION['m_admin']['doctypes']['order_field'];
        $what = $_SESSION['m_admin']['doctypes']['what'];
        $start = $_SESSION['m_admin']['doctypes']['start'];

        if (! empty($_SESSION['error'])) {
            if ($_REQUEST['mode'] == "up") {
                if (! empty($_SESSION['m_admin']['doctypes']['TYPE_ID'])) {
                    ?><script type="text/javascript">window.top.location.href='<?php
                    echo $_SESSION['config']['businessappurl']
                        . "index.php?page=types_up&id="
                        . $_SESSION['m_admin']['doctypes']['TYPE_ID'];
                    ?>';</script>
                    <?php
                    exit();
                } else {
                    ?>
                    <script type="text/javascript">window.top.location.href='<?php
                    echo $_SESSION['config']['businessappurl']
                        . "index.php?page=types&order=" . $order
                        . "&order_field=" . $orderField . "&start="
                        . $start . "&what=" . $what;
                    ?>';</script>
                    <?php
                    exit();
                }
            } else if ($_REQUEST['mode'] == "add") {
                ?> <script type="text/javascript">window.top.location.href='<?php
                echo $_SESSION['config']['businessappurl']
                    . "index.php?page=types_add";
                ?>';</script>
                <?php
                exit();
            }
        } else {
            if ($_REQUEST['mode'] <> "prop" && $_REQUEST['mode'] <> "add") {
                $db->query(
                    "UPDATE " . DOCTYPES_TABLE . " SET description = ? , doctypes_first_level_id = ?, doctypes_second_level_id = ?, enabled = 'Y', coll_id = ? 
                    WHERE type_id = ?",
                    array($_SESSION['m_admin']['doctypes']['LABEL'], $_SESSION['m_admin']['doctypes']['STRUCTURE'], $_SESSION['m_admin']['doctypes']['SUB_FOLDER'], 
                        $_SESSION['m_admin']['doctypes']['COLL_ID'], $_SESSION['m_admin']['doctypes']['TYPE_ID'])
                );

                $db->query(
                    "DELETE FROM " . DOCTYPES_INDEXES_TABLE . " WHERE coll_id = ? and type_id = ?",
                    array($_SESSION['m_admin']['doctypes']['COLL_ID'], $_SESSION['m_admin']['doctypes']['TYPE_ID'])
                );

                for ($i = 0; $i < count(
                    $_SESSION['m_admin']['doctypes']['indexes']
                ); $i ++
                ) {
                    $mandatory = 'N';
                    if (in_array(
                        $_SESSION['m_admin']['doctypes']['indexes'][$i],
                        $_SESSION['m_admin']['doctypes']['mandatory_indexes']
                    )
                    ) {
                        $mandatory = 'Y';
                    }
                    $db->query(
                        "INSERT INTO " . DOCTYPES_INDEXES_TABLE
                        . " (coll_id, type_id, field_name, mandatory) values(?, ?, ?, ?)",
                    array($_SESSION['m_admin']['doctypes']['COLL_ID'], $_SESSION['m_admin']['doctypes']['TYPE_ID'], 
                        $_SESSION['m_admin']['doctypes']['indexes'][$i], $mandatory)
                    );
                }
                $_SESSION['service_tag'] = "doctype_updatedb";
                $core->execute_modules_services(
                    $_SESSION['modules_services'], 'doctype_load_db', "include"
                );
                $core->execute_app_services(
                    $_SESSION['app_services'], 'doctype_up', 'include'
                );
                $_SESSION['service_tag'] = '';
                if ($_REQUEST['mode'] == "up") {
                    $_SESSION['info'] = _DOCTYPE_MODIFICATION;
                    if ($_SESSION['history']['doctypesup'] == "true") {
                        $hist = new history();
                        $hist->add(
                            DOCTYPES_TABLE,
                            $_SESSION['m_admin']['doctypes']['TYPE_ID'], "UP",'doctypesup',
                            _DOCTYPE_MODIFICATION . " : "
                            . $_SESSION['m_admin']['doctypes']['LABEL'],
                            $_SESSION['config']['databasetype']
                        );
                    }
                }
                $this->cleartypeinfos();
                ?>
                <script type="text/javascript">window.top.location.href='<?php
                echo $_SESSION['config']['businessappurl']
                    . "index.php?page=types&order=" . $order . "&order_field="
                    . $orderField . "&start=" . $start . "&what=" . $what;
                ?>';</script>
                <?php
                exit();
            } else {
                $hist = new history();
                if ($_REQUEST['mode'] == "add") {
                    $tmp = $this->protect_string_db(
                        $_SESSION['m_admin']['doctypes']['LABEL']
                    );
                    $db->query(
                        "INSERT INTO " . DOCTYPES_TABLE . " (coll_id, "
                        ." description, doctypes_first_level_id, "
                        . "doctypes_second_level_id,  enabled ) VALUES (?, ?, ?, ?, 'Y' )",
                        array($_SESSION['m_admin']['doctypes']['COLL_ID'], $tmp, $_SESSION['m_admin']['doctypes']['STRUCTURE'], $_SESSION['m_admin']['doctypes']['SUB_FOLDER'])
                    );
                    //$this->show();
                    $stmt = $db->query(
                        "SELECT type_id FROM " . DOCTYPES_TABLE
                        . " WHERE coll_id = ? and description = ? and doctypes_first_level_id = ? and doctypes_second_level_id = ?",
                        array($_SESSION['m_admin']['doctypes']['COLL_ID'], $tmp, $_SESSION['m_admin']['doctypes']['STRUCTURE']
                            , $_SESSION['m_admin']['doctypes']['SUB_FOLDER'])
                    );
                    //$this->show();
                    $res = $stmt->fetchObject();
                    $_SESSION['m_admin']['doctypes']['TYPE_ID'] = $res->type_id;
                    for ($i = 0; $i < count(
                        $_SESSION['m_admin']['doctypes']['indexes']
                    ); $i ++
                    ) {
                        $mandatory = 'N';
                        if (in_array(
                            $_SESSION['m_admin']['doctypes']['indexes'][$i],
                            $_SESSION['m_admin']['doctypes']['mandatory_indexes']
                        )
                        ) {
                            $mandatory = 'Y';
                        }
                        $db->query(
                            "INSERT INTO " . DOCTYPES_INDEXES_TABLE
                            . " (coll_id, type_id, field_name, mandatory) "
                            . "values(?, ?, ?, ?)",
                            array($_SESSION['m_admin']['doctypes']['COLL_ID'], $_SESSION['m_admin']['doctypes']['TYPE_ID']
                                , $_SESSION['m_admin']['doctypes']['indexes'][$i], $mandatory)
                        );
                    }

                    $_SESSION['service_tag'] = "doctype_insertdb";
                    echo $core->execute_modules_services(
                        $_SESSION['modules_services'], 'doctype_load_db', "include"
                    );
                    $core->execute_app_services(
                        $_SESSION['app_services'], 'doctype_up', 'include'
                    );
                    $_SESSION['service_tag'] = '';

                    if ($_SESSION['history']['doctypesadd'] == "true") {
                        $hist->add(
                            DOCTYPES_TABLE, $res->type_id, "ADD", 'doctypesadd', _DOCTYPE_ADDED
                            . " : " . $_SESSION['m_admin']['doctypes']['LABEL'],
                            $_SESSION['config']['databasetype']
                        );
                    }
                }
                $this->cleartypeinfos();

                ?> <script  type="text/javascript">window.top.location.href='<?php
                echo $_SESSION['config']['businessappurl']
                    . "index.php?page=types&order=" . $order . "&order_field="
                    . $orderField . "&start=" . $start . "&what=" . $what;
                ?>';</script>
                <?php
                exit();
            }
        }
    }

    /**
    * Clear the session variable for the doctypes
    */
    protected function cleartypeinfos()
    {
        // clear the session variable for the doctypes
        unset($_SESSION['m_admin']);
    }


    /**
    * Return in an array all enabled doctypes for a given collection
    *
    * @param string $collId Collection identifier
    */
    public function getArrayTypes($collId)
    {
        $types = array();
        if (empty($collId)) {
            return $types;
        }

        $db = new Database();
        $stmt = $db->query(
            "SELECT type_id, description FROM " . DOCTYPES_TABLE
            . " WHERE coll_id = ? and enabled = 'Y' "
            . "order by description",
            array($collId)
        );
        while ($res = $stmt->fetchObject()) {
            array_push(
                $types,
                array(
                    'ID' => $res->type_id,
                    'LABEL' => $this->show_string($res->description),
                )
            );
        }
        return $types;
    }


    /**
    * Return architecture for one doctype
    *
    * @param string $doctype
    */
    public function GetFullStructure($doctype)
    {
        $db = new Database();
        $structure = array();
        $levelQuery = "SELECT doctypes_first_level_id, "
            . "doctypes_second_level_id FROM " . DOCTYPES_TABLE
            . " WHERE type_id = ?";

        $stmt = $db->query($levelQuery, array($doctype));
        $result = $stmt->fetchObject();
        if ($stmt->rowCount() == 0) {
            return false;
        } else {
            array_push(
                $structure,
                array(
                    "doctype" => $doctype,
                    "first_level" => $result->doctypes_first_level_id,
                    "second_level" => $result->doctypes_second_level_id
                )
            );
            return $structure;
        }
    }

    /**
    * Return in an array all enabled doctypes_second_level
    *
    * @param string
    */
    public function getArrayDoctypesSecondLevel()
    {
        $secondLevel = array();
        $db = new Database();
        $stmt = $this->query(
            "SELECT doctypes_second_level_id, doctypes_second_level_label, "
            . "css_style FROM "
            . $_SESSION['tablename']['doctypes_second_level']
            . " WHERE enabled = 'Y' order by doctypes_second_level_label"
        );
        while ($res = $stmt->fetchObject()) {
            array_push(
                $secondLevel,
                array(
                    'ID' => $res->doctypes_second_level_id,
                    'LABEL' => $this->show_string($res->doctypes_second_level_label),
                    'STYLE' => $res->css_style,
                )
            );
        }
        return $secondLevel;
    }
    /**
    * Returns in an array all enabled doctypes for a given collection with the
    * structure
    *
    * @param string $collId Collection identifier
    */
    public function getArrayStructTypes($collId)
    {
        $db = new Database();
        $level1 = array();
        $stmt = $db->query(
            "SELECT d.type_id, d.description, d.doctypes_first_level_id, "
            . "d.doctypes_second_level_id, dsl.doctypes_second_level_label, "
            . "dfl.doctypes_first_level_label, dfl.css_style as style_level1, "
            . " dsl.css_style as style_level2 FROM " . DOCTYPES_TABLE . " d, "
            . $_SESSION['tablename']['doctypes_second_level'] . " dsl, "
            . $_SESSION['tablename']['doctypes_first_level']
            . " dfl WHERE coll_id = ? and d.enabled = 'Y' "
            . "and d.doctypes_second_level_id = dsl.doctypes_second_level_id "
            . "and d.doctypes_first_level_id = dfl.doctypes_first_level_id "
            . "and dsl.enabled = 'Y' and dfl.enabled = 'Y' "
            . "order by dfl.doctypes_first_level_label,"
            . "dsl.doctypes_second_level_label, d.description ",
            array($collId)
        );
        $lastLevel1 = '';
        $nbLevel1 = 0;
        $lastLevel2 = '';
        $nbLevel2 = 0;
        while ($res = $stmt->fetchObject()) {
            //var_dump($res);
            if ($lastLevel1 <> $res->doctypes_first_level_id) {
                array_push(
                    $level1,
                    array(
                        'id' => $res->doctypes_first_level_id,
                        'label' => $this->show_string($res->doctypes_first_level_label),
                        'style' => $res->style_level1,
                        'level2' => array(
                            array(
                                'id' => $res->doctypes_second_level_id,
                                'label' => $this->show_string($res->doctypes_second_level_label),
                                'style' => $res->style_level2,
                                'types' => array(
                                    array(
                                        'id' => $res->type_id,
                                        'label' => $this->show_string($res->description)
                                    )
                                )
                            )
                        )
                    )
                );
                $lastLevel1 = $res->doctypes_first_level_id;
                $nbLevel1 ++;
                $lastLevel2 = $res->doctypes_second_level_id;
                $nbLevel2 = 1;
            } else if ($lastLevel2 <> $res->doctypes_second_level_id) {
                array_push(
                    $level1[$nbLevel1 - 1]['level2'],
                    array(
                        'id' => $res->doctypes_second_level_id,
                        'label' => $this->show_string($res->doctypes_second_level_label),
                        'style' => $res->style_level2,
                        'types' => array(
                            array(
                                'id' => $res->type_id,
                                'label' => $this->show_string($res->description)
                            )
                        )
                    )
                );
                $lastLevel2 = $res->doctypes_second_level_id;
                $nbLevel2 ++;
            } else {
                array_push(
                    $level1[$nbLevel1 - 1]['level2'][$nbLevel2 - 1]['types'],
                    array(
                        'id' => $res->type_id,
                        'label' => $this->show_string($res->description)
                    )
                );
            }
            //$this->show_array($level1);
        }
        return $level1;
    }

    /**
    * Returns in an array all indexes possible for a given collection
    *
    * @param string $collId Collection identifier
    * @return array $indexes[$i]
    *                   ['column'] : database field of the index
    *                   ['label'] : Index label
    *                   ['type'] : Index type ('date', 'string', 'integer' or 'float')
    *                   ['img'] : url to the image index
    */
    public function get_all_indexes($collId)
    {
        $sec = new security();
        $db = new Database();
        $indColl = $sec->get_ind_collection($collId);
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
            . $_SESSION['collections'][$indColl]['index_file']
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . "xml"
                  . DIRECTORY_SEPARATOR
                  . $_SESSION['collections'][$indColl]['index_file'];
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                  . $_SESSION['collections'][$indColl]['index_file'];
        }

        $xmlfile = simplexml_load_file($path);

        $pathLang = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
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
                    'img' => $img,
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
                $query = "select " . $foreignKey . ", " . $foreignLabel
                       . " from " . $tableName;
                if (isset($whereClause) && ! empty($whereClause)) {
                    $query .= " where " . $whereClause;
                }
                if (isset($order) && ! empty($order)) {
                    $query .= ' '.$order;
                }
                
                $stmt = $db->query($query);
                while ($res = $stmt->fetch()) {
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
                    'img' => $img,
                    'type_field' => 'select',
                    'values' => $values,
                    'default_value' => $default,
                );
            } else {
                $tmpArr = array(
                    'column' => (string) $item->column,
                    'label' => $label,
                    'type' => (string) $item->type,
                    'img' => $img,
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
    * @param string $typeId Document type identifier
    * @param string $collId Collection identifier
    * @param string $mode Mode 'full' or 'minimal', 'full' by default
    * @return array array of the indexes, depends on the chosen mode :
    *       1) mode = 'full' : $indexes[field_name] :  the key is the field name in the database
    *                                       ['label'] : Index label
    *                                       ['type'] : Index type ('date', 'string', 'integer' or 'float')
    *                                       ['img'] : url to the image index
    *       2) mode = 'minimal' : $indexes[$i] = field name in the database
    */
    public function get_indexes($typeId, $collId, $mode='full')
    {
        $fields = array();
        $db = new Database();
        if (!empty($typeId)) {
            $stmt = $db->query(
                "SELECT field_name FROM " . DOCTYPES_INDEXES_TABLE
                . " WHERE coll_id = ? and type_id = ?",
                array($collId, $typeId)
            );
        } else {
            return array();
        }

        while ($res = $stmt->fetchObject()) {
            array_push($fields, $res->field_name);
        }
        if ($mode == 'minimal') {
            return $fields;
        }

        $indexes = array();
        $sec = new security();
        $indColl = $sec->get_ind_collection($collId);
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
            . $_SESSION['collections'][$indColl]['index_file']
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                  . "xml" . DIRECTORY_SEPARATOR
                  . $_SESSION['collections'][$indColl]['index_file'];
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                  . $_SESSION['collections'][$indColl]['index_file'];
        }

        $xmlfile = simplexml_load_file($path);
        $pathLang = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['lang'] . '.php';
        foreach ($xmlfile->INDEX as $item) {
            $label = (string) $item->label;
            if (!empty($label) && defined($label) 
                && constant($label) <> NULL
            ) {
                $label = constant($label);
            }
           
            $col = (string) $item->column;
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
            if (in_array($col, $fields)) {
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
                    $indexes[$col] = array(
                        'label' => $label,
                        'type' => (string) $item->type,
                        'img' => $img,
                        'type_field' => 'select',
                        'values' => $values,
                        'default_value' => $default,
						'origin' => 'document'
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
                    $query = "select " . $foreignKey . ", " . $foreignLabel
                           . " from " . $tableName;
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
                        'img' => $img,
                        'type_field' => 'select',
                        'values' => $values,
                        'default_value' => $default,
						'origin' => 'document'
                    );
                } else {
                    $indexes[$col] = array(
                        'label' => $label,
                        'type' => (string) $item->type,
                        'img' => $img,
                        'type_field' => 'input',
                        'default_value' => $default,
						'origin' => 'document'
                    );
                }
            }
        }
        //print_r($indexes);
        foreach(array_keys($indexes) as $key) {
            if (is_array($indexes[$key])) {
                //print_r($indexes[$key]);
                $indexes[$key]['label'] = functions::xssafe($indexes[$key]['label']);
                $indexes[$key]['type'] = functions::xssafe($indexes[$key]['type']);
                $indexes[$key]['img'] = functions::xssafe($indexes[$key]['img']);
                $indexes[$key]['type_field'] = functions::xssafe($indexes[$key]['type_field']);
                $indexes[$key]['default_value'] = functions::xssafe($indexes[$key]['default_value']);
                $indexes[$key]['origin'] = functions::xssafe($indexes[$key]['origin']);
                if (is_array($indexes[$key]['values'])) {
                    for ($cpt=0;$cpt<count($indexes[$key]['values']);$cpt++) {
                        //print_r($indexes[$key]['values'][$cpt]);
                        $indexes[$key]['values'][$cpt]['id'] = functions::xssafe($indexes[$key]['values'][$cpt]['id']);
                        $indexes[$key]['values'][$cpt]['label'] = functions::xssafe($indexes[$key]['values'][$cpt]['label']);
                    }
                    $indexes[$key]['type_field'] = functions::xssafe($indexes[$key]['type_field']);
                }
            }
        }
        return $indexes;
    }

    /**
    * Returns in an array all manadatory indexes possible for a given type
    *
    * @param string $typeId Document type identifier
    * @param string $collId Collection identifier
    * @return array Array of the manadatory indexes, $indexes[$i] = field name
    * in the db
    */
    public function get_mandatory_indexes($typeId, $collId)
    {
        $fields = array();
        $db = new Database();
        $stmt = $db->query(
            "SELECT field_name FROM " . DOCTYPES_INDEXES_TABLE
            . " WHERE coll_id = ? and type_id = ? and mandatory = 'Y'",
            array($collId, $typeId)
        );

        while ($res = $stmt->fetchObject()) {
            array_push($fields, $res->field_name);
        }
        return $fields;
    }

    /**
    * Checks validity of indexes
    *
    * @param string $typeId Document type identifier
    * @param string $collId Collection identifier
    * @param array $values Values to check
    * @return bool true if checks is ok, false if an error occurs
    */
    public function check_indexes($typeId, $collId, $values)
    {
        $sec = new security();
        $indColl = $sec->get_ind_collection($collId);
        $indexes = $this->get_indexes($typeId, $collId);
        $mandatoryIndexes = $this->get_mandatory_indexes($typeId, $collId);

        // Checks the manadatory indexes
        for ($i = 0; $i < count($mandatoryIndexes); $i ++) {
            if ((empty($values[$mandatoryIndexes[$i]])
                || $values[$mandatoryIndexes[$i]] == '')
            ) {
                $_SESSION['error'] .= $indexes[$mandatoryIndexes[$i]]['label']
                                   . _IS_EMPTY;
            }
        }

        // Checks type indexes
        $datePattern = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";
        foreach (array_keys($values) as $key) {
            //var_dump($values);
            //exit;
            if ($indexes[$key]['type'] == 'date' && ! empty($values[$key])) {
                if (preg_match($datePattern, $values[$key]) == 0) {
                    $_SESSION['error'] .= $indexes[$key]['label']
                                       . _WRONG_FORMAT;
                    return false;
                }
            } elseif ($indexes[$key]['type'] == 'string'
                && trim($values[$key]) <> ''
            ) {
                $fieldValue = $this->wash(
                    $values[$key], "no", $indexes[$key]['label']
                );
            } elseif ($indexes[$key]['type'] == 'float'
                && preg_match("/^[0-9.,]+$/", $values[$key]) == 1
            ) {     
                    $values[$key] = str_replace( ",", ".", $values[$key] );
                    $fieldValue = $this->wash(
                    $values[$key], "float", $indexes[$key]['label']
                );

            } elseif ($indexes[$key]['type'] == 'integer'
                && preg_match("/^[0-9]+$/", $values[$key]) == 1
            ) {
                $fieldValue = $this->wash(
                    $values[$key], "num", $indexes[$key]['label']
                );
            } elseif (!empty($values[$key])) {
                $_SESSION['error'] .= $indexes[$key]['label']
                                       . _WRONG_FORMAT;
                return false;
            }

            if (isset($indexes[$key]['values'])
                && count($indexes[$key]['values']) > 0
            ) {
                $found = false;
                for ($i = 0; $i < count($indexes[$key]['values']); $i++ ) {
                    if ($values[$key] == $indexes[$key]['values'][$i]['id']) {
                        $found = true;
                        break;
                    }
                }
                if (! $found && $values[$key] <> "") {
                    $_SESSION['error'] .= $indexes[$key]['label'] . " : "
                                       . _ITEM_NOT_IN_LIST . "";
                    return false;
                }
            }
        }
        if (! empty($_SESSION['error'])) {
            return false;
        } else {
            return true;
        }
    }


    /**
    * Returns a string to use in an sql update query
    *
    * @param string $typeId Document type identifier
    * @param string $collId Collection identifier
    * @param array $values Values to update
    * @return string Part of the update sql query
    */
    public function get_sql_update($typeId, $collId, $values)
    {
        $indexes = $this->get_indexes($typeId, $collId);

        $req = '';
        foreach (array_keys($values)as $key) {
            if ($indexes[$key]['type'] == 'date' && ! empty($values[$key])) {
                $req .= ", " . $key . " = '"
                     . $this->format_date_db($values[$key]) . "'";
            } else if ($indexes[$key]['type'] == 'string'
                && ! empty($values[$key])
            ) {
                $req .= ", " . $key . " = '"
                     . $this->protect_string_db($values[$key]) . "'";
            } else if ($indexes[$key]['type'] == 'float'
                && ! empty($values[$key])
            ) {
                $req .= ", " . $key . " = " . $values[$key] . "";
            } else if ($indexes[$key]['type'] == 'integer'
                && ! empty($values[$key])
            ) {
                $req .= ", " . $key . " = " . $values[$key] . "";
            }
        }
        return $req;
    }

    /**
    * Returns an array used to insert data in the database
    *
    * @param string $typeId Document type identifier
    * @param string $collId Collection identifier
    * @param array $values Values to update
    * @param array $data Return array
    * @return array
    */
    public function fill_data_array($typeId, $collId, $values, $data = array())
    {
        $indexes = $this->get_indexes($typeId, $collId);

        foreach (array_keys($values) as $key) {
            if ($indexes[$key]['type'] == 'date' && ! empty($values[$key])) {
                array_push(
                    $data,
                    array(
                        'column' => $key,
                        'value' => $this->format_date_db($values[$key]),
                        'type' => "date",
                    )
                );
            } else if ($indexes[$key]['type'] == 'string'
                && trim($values[$key]) <> ''
            ) {
                array_push(
                    $data,
                    array(
                        'column' => $key,
                        'value' => $this->protect_string_db($values[$key]),
                        'type' => "string",
                    )
                );
            } else if ($indexes[$key]['type'] == 'float'
                && preg_match("/^[0-9.,]+$/", $values[$key]) == 1
            ) {
                $values[$key] = str_replace( ",", ".", $values[$key] );
                array_push(
                    $data,
                    array(
                        'column' => $key,
                        'value' => $values[$key],
                        'type' => "float",
                    )
                );
            } else if ($indexes[$key]['type'] == 'integer'
                && preg_match("/^[0-9]+$/", $values[$key]) == 1
            ) {
                array_push(
                    $data,
                    array(
                        'column' => $key,
                        'value' => $values[$key],
                        'type' => "integer",
                    )
                );
            }
        }
        return $data;
    }

    /**
    * Inits in the database the indexes for a given res id to null
    *
    * @param string $collId Collection identifier
    * @param string $resId Resource identifier
    */
    public function inits_opt_indexes($collId, $resId)
    {
        $sec = new security();
        $table = $sec->retrieve_table_from_coll($collId);
        $db = new Database();

        $indexes = $this->get_all_indexes($collId);
        if (count($indexes) > 0) {
            $query = "UPDATE " . $table . " set ";
            for ($i = 0; $i < count($indexes); $i ++) {
                $query .= $indexes[$i]['column'] . " = NULL, ";
            }
            $query = preg_replace('/, $/', ' where res_id = ?', $query);
            $db->query($query, array($resId));
        }
    }


    /**
    * Makes the search checks for a given index, and builds the where query and
    *  json
    *
    * @param array $indexes Array of the possible indexes (used to check)
    * @param string $fieldName Field name, index identifier
    * @param string $val Value to check
    * @return array ['json_txt'] : json used in the search
    *               ['where'] : where query
    */
    public function search_checks($indexes, $fieldName, $val )
    {
        $func = new functions();
        $whereRequest = '';
        $jsonTxt = '';
        if (! empty($val)) {
            $datePattern = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";
            for ($j = 0; $j < count($indexes); $j ++) {
                $column = $indexes[$j]['column'] ;
                if (preg_match('/^doc_/', $fieldName)) {
                    $column = 'doc_' . $column;
                }
                // type == 'string or others'
                if ($indexes[$j]['column'] == $fieldName
                    || 'doc_' . $indexes[$j]['column'] == $fieldName
                ) {
					if ( $indexes[$j]['type'] == 'float' || $indexes[$j]['type'] == 'integer' )
					{
						       $jsonTxt .= " '" . $fieldName . "' : ['"
                             . addslashes(trim($val)) . "'],";
					$whereRequest .= " (" . $column . ") = ('"
                                      . $val . "') and ";
						
					} else {
                    $jsonTxt .= " '" . $fieldName . "' : ['"
                             . addslashes(trim($val)) . "'],";
					$whereRequest .= " lower(" . $column . ") like lower('%"
                                      . $this->protect_string_db($val) . "%') and ";
					}
                    break;
                } else if (($indexes[$j]['column'] . '_from' == $fieldName
                    || $indexes[$j]['column'] . '_to' == $fieldName
                    || 'doc_' . $indexes[$j]['column'] . '_from' == $fieldName
                    || 'doc_' . $indexes[$j]['column'] . '_to' == $fieldName)
                        && ! empty($val)
                ) { // type == 'date'
                    if (preg_match($datePattern, $val) == false) {
                        $_SESSION['error'] .= _WRONG_DATE_FORMAT . ' : ' . $val;
                    } else {
                        if ($indexes[$j]['column'] . '_from' == $fieldName
                            || 'doc_' . $indexes[$j]['column'] . '_from' == $fieldName
                        ) {
                            $whereRequest .= " (" . $column . " >= '"
                                          . $this->format_date_db($val) . "') and ";
                        } else {
                            $whereRequest .= " (" . $column . " <= '"
                                          . $this->format_date_db($val) . "') and ";
                        }
                        $jsonTxt .= " '" . $fieldName . "' : ['" . trim($val)
                                 . "'],";
                    }
                    break;
                } else if ($indexes[$j]['column'] . '_min' == $fieldName
                    || 'doc_' . $indexes[$j]['column'] . '_min' == $fieldName
                ) {
                    if ($indexes[$j]['type'] == 'integer'
                        || $indexes[$j]['type'] == 'float'
                    ) {
                        if ($indexes[$j]['type'] == 'integer') {
                            $valCheck = $func->wash(
                                $val, "num", $indexes[$j]['label'], "no"
                            );
                        } else {
                            $valCheck = $func->wash(
                                $val, "float", $indexes[$j]['label'], "no"
                            );
                        }
                        if (empty($_SESSION['error'])) {
                            $whereRequest .= " (" . $column . " >= " . $valCheck
                                          . ") and ";
                            $jsonTxt .= " '" . $fieldName . "' : ['" . $valCheck
                                     . "'],";
                        }
                    }
                    break;
                } else if ($indexes[$j]['column'] . '_max' == $fieldName
                    || 'doc_' . $indexes[$j]['column'] . '_max' == $fieldName
                ) {
                    if ($indexes[$j]['type'] == 'integer'
                        || $indexes[$j]['type'] == 'float'
                    ) {
                        if ($indexes[$j]['type'] == 'integer') {
                            $valCheck = $func->wash(
                                $val, "num", $indexes[$j]['label'], "no"
                            );
                        } else {
                            $valCheck = $func->wash(
                                $val, "float", $indexes[$j]['label'], "no"
                            );
                        }
                        if (empty($_SESSION['error'])) {
                            $whereRequest .= " (" . $column . " <= " . $valCheck
                                          . ") and ";
                            $jsonTxt .= " '" . $fieldName . "' : ['" . $valCheck
                                     . "'],";
                        }
                    }
                    break;
                }
            }
        }
        return array(
            'json_txt' => $jsonTxt,
            'where' => $whereRequest,
        );
    }
}
