<?php
/**
* File : notes_details.php
*
* Popup to show the notes
*
* @package Maarch LetterBox 2.3
* @version 1.0
* @since 06/2007
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/
require_once "core/class/class_security.php";
require_once "core/class/class_request.php";
require_once "core/class/class_history.php";
require_once "modules/entities/class/EntityControler.php";
require_once 'core/core_tables.php';
require_once 'modules/notes/notes_tables.php';
require_once "modules" . DIRECTORY_SEPARATOR . "notes" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR
    . "class_modules_tools.php";
$core = new core_tools();
$core->load_lang();
$sec = new security();
$req = new request();
$ent = new EntityControler();
$notes_mod_tools = new notes;
$func = new functions();
$db = new Database();
$table = '';
$collId = "";
$user = '';
$text = "";
$userId = '';
$date = "";
$identifier = '';
if (empty($_SESSION['collection_id_choice'])) {
    $_SESSION['collection_id_choice'] = $_SESSION['user']['collections'][0];
    $collId = $_SESSION['collection_id_choice'] ;
} else if (isset($_REQUEST['coll_id'])&& empty($collId)) {
    $collId = $_REQUEST['coll_id'];
}
$view = $sec->retrieve_view_from_coll_id($collId);
$table = $sec->retrieve_table_from_coll($collId);

$error = '';
if (isset($_REQUEST['modify'])) {
    $id = $_REQUEST['id'];
    $identifier = $_REQUEST['identifier'];
    $table = $_REQUEST['table'];
    $collId = $_REQUEST['coll_id'];

    if (empty($_REQUEST['notes'])) {
        $error = _NOTES . ' ' . _EMPTY;
    } else if (empty($error)) {
        $text = $_REQUEST['notes'];
        $db->query(
            "UPDATE ".NOTES_TABLE." SET note_text = ?, date_note = CURRENT_TIMESTAMP WHERE id = ?",
            array($text, $id)
        );

        echo "<pre>";
        //print_r($_REQUEST['entities_chosen']);
        //print_r($_SESSION['notes']['entities']);
        echo "</pre>";
        
        if (isset($_REQUEST['entities_chosen']) && !empty($_REQUEST['entities_chosen']))
        {
            for ($i=0; $i<count($_REQUEST['entities_chosen']); $i++) 
            {
                $stmt = $db->query(
                    "SELECT id FROM " .NOTE_ENTITIES_TABLE. " WHERE item_id = ? and note_id = ?",
                    array($_REQUEST['entities_chosen'][$i], $id)
                );
                $result = $stmt->fetchObject();
                $note_entity_id = $result->id;
                
                if ($stmt->rowCount() == 0) 
                {
                    $db->query(
                        "INSERT INTO " . NOTE_ENTITIES_TABLE . "(note_id, item_id) VALUES"
                        . " (?, ?)",
                        array($id, $_REQUEST['entities_chosen'][$i])
                    );
                }
                else
                {
                    $db->query(
                        "UPDATE ".NOTE_ENTITIES_TABLE." SET item_id = ? WHERE id = ?",
                        array($_REQUEST['entities_chosen'][$i], $note_entity_id)
                    );
                }

            }
        }
        elseif (empty($_REQUEST['entities_chosen']))
        {
            $db->query(
                    "DELETE FROM " . NOTE_ENTITIES_TABLE . " where note_id = ?",
                    array($id)
            );
        }
        if ($_SESSION['history']['noteup']) {
            $hist = new history();
            $hist->add(
                NOTES_TABLE, $id , "UP", 'noteup', _NOTE_UPDATED . ' (' . $id . ')',
                $_SESSION['config']['databasetype'], 'notes'
            );
            if ($_SESSION['origin'] == "show_folder" ) {
                $hist->add(
                    $table, $identifier, "UP", 'noteup', _NOTE_UPDATED . _ON_FOLDER_NUM
                    . $identifier . ' (' . $id . ')',
                    $_SESSION['config']['databasetype'], 'notes'
                );
            } else {
                $hist->add(
                    $view, $identifier, "UP", 'noteup', _NOTE_UPDATED . _ON_DOC_NUM
                    . $identifier . ' (' . $id . ')',
                    $_SESSION['config']['databasetype'], 'notes'
                );
            }
        }
        //$_SESSION['error'] = _NOTES_MODIFIED;
        ?>
        <script type="text/javascript">window.opener.location.reload();self.close();</script>
        <?php
        exit();
    }

}
if (isset($_REQUEST['delete'])) {
    $id = $_REQUEST['id'];
    $identifier = $_REQUEST['identifier'];

    $db->query("DELETE FROM " . NOTE_ENTITIES_TABLE . " WHERE note_id = ?", array($id));
    $db->query("DELETE FROM " . NOTES_TABLE . " WHERE id = ?", array($id));

    if ($_SESSION['history']['notedel']) {
        $hist = new history();
        $hist->add(
            NOTES_TABLE, $id, "DEL", 'notedel', _NOTES_DELETED . ' (' . $id . ')',
            $_SESSION['config']['databasetype'], 'notes'
        );
        if ($_SESSION['origin'] == "show_folder" ) {
            $hist->add(
                $table, $identifier, "DEL", 'notedel', _NOTES_DELETED . _ON_FOLDER_NUM
                . $identifier . ' (' . $id . ')',
                $_SESSION['config']['databasetype'], 'notes'
            );
        } else {
            $hist->add(
                $view, $identifier, "DEL", 'notedel',  _NOTES_DELETED . _ON_DOC_NUM
                . $identifier . ' (' . $id . ')',
                $_SESSION['config']['databasetype'], 'notes'
            );
        }
    }
    //$_SESSION['error'] = _NOTES_DELETED;
    ?>
    <script type="text/javascript">window.opener.location.reload();self.close();</script>
    <?php
    exit();
}

if (isset($_REQUEST['id'])) {
    $sId = $_REQUEST['id'];
} else {
    $sId = "";
}
if (isset($_REQUEST['identifier'])) {
    $identifier = $_REQUEST['identifier'];
}
if (isset($_REQUEST['table']) && empty($table)) {
    $table = $_REQUEST['table'];
}
if (isset($_REQUEST['coll_id']) && empty($collId)) {
    $collId = $_REQUEST['coll_id'];
}

$core->load_html();
//here we building the header
$core->load_header(_NOTES);
$time = $core->get_session_time_expire();
?>
<body id="pop_up" onload="setTimeout(window.close, <?php echo $time;?>*60*1000);">
<?php
if (empty($table) && empty($collId)) {
    $error = _PB_TABLE_COLL;
} else {

    $arrayPDO = array();
    if (! empty($collId)) {
        $where = " and coll_id = :collId";
        $arrayPDO = array_merge($arrayPDO, array(":collId" => $collId));
    } else {
        $where = " and tablename = :table";
        $arrayPDO = array_merge($arrayPDO, array(":table" => $table));
    }
    $arrayPDO = array_merge($arrayPDO, array(":sId" => $sId));
    $stmt = $db->query(
        "SELECT n.identifier, n.date_note, n.user_id, n.note_text, u.lastname, "
        . "u.firstname FROM " . NOTES_TABLE . " n inner join ". USERS_TABLE
        . " u on n.user_id  = u.user_id WHERE n.id = :sId " . $where, $arrayPDO
    );

    $line = $stmt->fetchObject();
    $user = $func->show_string($line->lastname . " " . $line->firstname);
    $text = $func->show_string($line->note_text);
    $userId = $line->user_id;
    $date = $line->date_note;
    $identifier = $line->identifier;
}

$canModify = false;
if (trim($userId) == $_SESSION['user']['UserId']) {
    $canModify = true;
}
?>
<div class="error"><?php
echo $error;
$error = '';
?></div>
<h2 class="tit" style="padding:10px;"><?php echo _NOTES;?> </h2>
    <h2 class="sstit" style="padding:10px;"><?php
echo _NOTES . " " . _OF . " " . $user . " (" . $core->format_date_db($date, false, '', true) . ") ";
?></h2>

    <div class="block" style="padding:10px">
      <form name="form1" method="post" class="forms" action="<?php
echo $_SESSION['config']['businessappurl'] . "index.php?display=true"
    . "&module=notes&page=note_details";
?>">
        <input type="hidden" name="display" value="true" />
        <input type="hidden" name="modules" value="notes" />
        <input type="hidden" name="page" value="note_details" />
        <textarea  <?php
if (! $canModify) {
    ?>readonly="readonly" class="readonly" <?php
}
?>style="width:380px" cols="70" rows="10"  name="notes"  id="notes"><?php
functions::xecho($text);
?></textarea>

        <input type="hidden" name="id" id="id" value="<?php functions::xecho($sId);?>"/>
        <input type="hidden" name="identifier" id="identifier" value="<?php
functions::xecho($identifier);
?>"/>
        <input type="hidden" name="table" id="table" value="<?php
functions::xecho($table);
?>"/>
        <input type="hidden" name="coll_id" id="coll_id" value="<?php
functions::xecho($collId);
?>"/>
       <br/>
       <p class="buttons">
    <?php
if ($canModify) {
    ?>
    <input type="submit" name="modify" id="modify" value="<?php
    echo _MODIFY;
    ?>"  class="button"/>
    <input type="submit" name="delete" id="delete" value="<?php
    echo _DELETE;
    ?>"  class="button"/>
    <?php
}
?>
    <input type="button" name="close_button" value="<?php
echo _CLOSE_WINDOW;
?>" onclick="javascript:self.close();" class="button"/>
    </p>
    <?php

if ($canModify) {
    ?>
    <div>
        <h3 class="sstit" style="color: red"><?php echo _THIS_NOTE_IS_VISIBLE_BY;?></h3>
    </div>
    <table>
        <tr>
            <td>
                <div  id="config_entities" class ="scrollbox" style=" width: 700px; margin-left:auto; margin-right: auto; height:140px; border: 1px solid #999;">
                    <table align="center" width="100%" id="template_entities" >
                        <tr>
                            <td width="10%" align="center">
                            <?php 
                                $notesEntities = array();
                                $entitiesList = array();
                                $_SESSION['notes']['entities'] = array();
                                $_SESSION['notes']['entities'] = $notes_mod_tools->getNotesEntities($sId);
                                //$notesEntities = $notes_mod_tools->getNotesEntities($sId);
                                $entitiesList = $ent->getAllEntities();
                                echo "<pre>";
                                echo "</pre>";
                                if ($canModify) {
                            ?>
                                <select name="entitieslist[]" id="entitieslist" size="7" 
                                        ondblclick='moveclick($(entitieslist), $(entities_chosen));' multiple="multiple" >
                                <?php
                                    
                                    for ($j=0;$j<count($entitiesList);$j++) {
                                            $state_entity = false;
                                            
                                            if (in_array($entitiesList[$j], $_SESSION['notes']['entities'])) {
                                                $state_entity = true;
                                            } else {
                                                $state_entity = false;
                                            }

                                        if ($state_entity == false) {
                                    ?>
                                            <option value="<?php 
                                                echo $entitiesList[$j]->entity_id;
                                                ?>"><?php 
                                                echo $entitiesList[$j]->entity_label;
                                                ?></option>
                                        <?php
                                        }
                                    }
                                        ?>  
                                </select>
                                <br/>
                            </td>
                            <td width="10%" align="center">
                                <input type="button" class="button" value="<?php 
                                    echo _ADD; 
                                    ?> &gt;&gt;" onclick='Move($(entitieslist), $(entities_chosen));' />
                                <br />
                                <br />
                                <input type="button" class="button" value="&lt;&lt; <?php 
                                    echo _REMOVE;
                                    ?>" onclick='Move($(entities_chosen), $(entitieslist));' />
                            </td>
    <?php
}
?>
                            <td width="10%" align="center">
                                <select name="entities_chosen[]" id="entities_chosen" size="7" 
                                        ondblclick='moveclick($(entities_chosen), $(entitieslist));' multiple="multiple">
                                    <?php
                                        for ($i=0;$i<count($_SESSION['notes']['entities']);$i++) {
                                            $state_entity = false;
                                            if ($state_entity == false) {
                                        ?>
                                                <option value="<?php 
                                                    echo $_SESSION['notes']['entities'][$i]->entity_id;
                                                ?>" selected="selected" ><?php 
                                                    echo $_SESSION['notes']['entities'][$i]->entity_label; 
                                                ?></option>
                                        <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <?php
    }
    ?>
      </form>
    </div>
    <div class="block_end">&nbsp;</div>
</body>
</html>
