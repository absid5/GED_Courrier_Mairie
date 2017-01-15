<?php

/*
*   Copyright 2013-2016 Maarch
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
* modules tools Class for notes
*
*  Contains all the functions to load modules tables for notes
*
* @package  maarch
* @version 3.0
* @since 10/2005
* @license GPL v3
* @author  Claire Figueras  <dev@maarch.org>
*
*/


// Loads the required class
try {
    require_once("core/class/class_db.php");
    require_once("modules/notes/notes_tables.php");
    require_once("modules/entities/entities_tables.php");
    require_once ("modules/notes/class/class_modules_tools.php");
    require_once "modules/entities/class/EntityControler.php";
} catch (Exception $e){
    functions::xecho($e->getMessage()).' // ';
}

abstract class notes_Abstract
{

    /**
    * Db query object used to connnect to the database
    */
    private static $db;
    
    /**
    * Entity object
    */
    public static $ent;
    
     /**
    * Notes table
    */
    public static $notes_table ;

    /**
    * Notes_entities table
    */
    public static $notes_entities_table ;
    
    /**
    * Entities table
    */
    public static $entities_table ;
    
    /**
    * Build Maarch module tables into sessions vars with a xml configuration
    * file
    */
    public function build_modules_tables()
    {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
            . DIRECTORY_SEPARATOR . "notes" . DIRECTORY_SEPARATOR . "xml"
            . DIRECTORY_SEPARATOR . "config.xml"
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR
                  . "notes" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                  . "config.xml";
        } else {
            $path = "modules" . DIRECTORY_SEPARATOR . "notes"
                  . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                  . "config.xml";
        }
        $xmlconfig = simplexml_load_file($path);
        foreach ($xmlconfig->TABLENAME as $tableName) {
            $_SESSION['tablename']['not_notes'] = (string) $tableName->not_notes;
            $_SESSION['tablename']['note_entities'] = (string) $tableName->note_entities;
        }
        $hist = $xmlconfig->HISTORY;
        $_SESSION['history']['noteadd'] = (string) $hist->noteadd;
        $_SESSION['history']['noteup'] = (string) $hist->noteup;
        $_SESSION['history']['notedel'] = (string) $hist->notedel;
    }
    
    /**
     * 
     * 
     * 
     */
     public function insertEntities($id)
     {
         //echo "RES_ID : ".$id;
     } 
    
    
    /**
     * Function to get which user can see a note
     * @id note identifier
     */
    public function getNotesEntities($id)
    {
        $db = new Database();
        $ent = new EntityControler();
        
        $query = "SELECT entity_id, entity_label FROM ".NOTE_ENTITIES_TABLE." , entities WHERE item_id LIKE entity_id and note_id = ?";
        
        try{
            $stmt = $db->query($query, array($id));
        } catch (Exception $e){}
        

        $entitiesList = array();
        $entitiesChosen = array();
        $entitiesList = $ent->getAllEntities();
        

        while($res = $stmt->fetchObject())
        {
            array_push($entitiesChosen, $ent->get($res->entity_id));
        }
        
        //self::disconnect();
        return $entitiesChosen;
    }
    
    public function getNotes($noteId, $userId, $userPrimaryEntity)
    {
        $query = "SELECT id FROM notes WHERE id in ("
                  . "SELECT note_id FROM ". NOTE_ENTITIES_TABLE. " WHERE (item_id in ("
                      ."SELECT entity_id FROM users_entities WHERE user_id = ?) and note_id = ?))"
            . "or (id = ? and user_id = ?)";
        $db = new Database();
        $stmt = $db->query($query, array($userId, $noteId, $noteId, $userId));

        if ($stmt->rowCount() > 0) {
            return true;
         } else {
            // test if public
            $query = "SELECT note_id FROM ". NOTE_ENTITIES_TABLE. " WHERE note_id = ?";
            $stmt = $db->query($query, array($noteId));
            if ($stmt->rowCount() == 0) {
                return true;
            } else {
                return false;
            }
         }
    }
    
    public function countUserNotes($id, $coll_id) {
        $not_nbr = 0;
        $db = new Database();

        $stmt = $db->query("SELECT id, identifier, user_id, date_note, note_text FROM "
                            . NOTES_TABLE 
                            . " WHERE identifier = ? and coll_id = ? order by date_note desc", array($id, $coll_id));

       while ($res = $stmt->fetchObject())
       {
           $query = "SELECT id FROM ". NOTE_ENTITIES_TABLE. " WHERE note_id = ?";
                    
           $stmt2 = $db->query($query, array($res->id));
                        
           if($stmt2->rowCount()==0)
            $not_nbr++;
           else
           {
             $stmt2 = $db->query( "SELECT id FROM notes WHERE id in ("
                . "SELECT note_id FROM ". NOTE_ENTITIES_TABLE. " WHERE (item_id in ("
                      ."SELECT entity_id FROM users_entities WHERE user_id = ?) and note_id = ?))"
                . "or (id = ? and user_id = ?)",
                array($_SESSION['user']['UserId'], $res->id, $res->id, $_SESSION['user']['UserId']));
            
                if($stmt2->rowCount()<>0)
                $not_nbr++;
            }
        }
        
        return $not_nbr;
    } 
    
    public function getUserNotes($id, $coll_id) {
        $userNotes = array();
        $db = new Database();

        $stmt = $db->query("SELECT id, identifier, user_id, date_note, note_text FROM "
                            . NOTES_TABLE 
                            . " WHERE identifier = ? and coll_id = ? order by date_note desc",
                            array($id, $coll_id));

       while ($res = $stmt->fetchObject())
       {
           $query = "SELECT id FROM ".NOTE_ENTITIES_TABLE." WHERE note_id = ?";
                    
           $stmt2 = $db->query($query, array($res->id));
                        
            if($stmt2->rowCount()==0) {
                array_push($userNotes,
                    array('id' => $res->id, //ID
                          'label' => functions::show_string($res->note_text), //Label
                          'author' => $res->user_id, //Author 
                          'date' => $res->date_note //Date
                        )
                );
           } else {
             $stmt2 = $db->query( "SELECT id FROM notes WHERE id in ("
                . "select note_id from ". NOTE_ENTITIES_TABLE. " where (item_id in ("
                      ."SELECT entity_id FROM users_entities WHERE user_id = ?) and note_id = ?))"
                . "or (id = ? and user_id = ?)",
                array($_SESSION['user']['UserId'], $res->id, $res->id, $_SESSION['user']['UserId']));
            
                if($stmt2->rowCount()<>0) {
                    array_push($userNotes,
                        array('id' => $res->id, //ID
                              'label' => functions::show_string($res->note_text), //Label
                              'author' => $res->user_id, //Author 
                              'date' => $res->date_note //Date
                            )
                    );
                }
            }
        }
        
        return $userNotes;
    }
	
	public function isUserNote($noteId, $userId, $userPrimaryEntity)
    {
        $query = "SELECT id FROM notes WHERE id in ("
                  . "SELECT note_id FROM note_entities WHERE (item_id in ("
                      ."SELECT entity_id FROM users_entities WHERE user_id = ?) and note_id = ?))"
                  . "or (id = ? and user_id = ?)";
        $db = new Database();
        $stmt = $db->query($query, array($userId, $noteId, $noteId, $userId));
        //$db->show();exit;
        if ($stmt->rowCount() > 0) {
            return true;
         } else {
            // test if public
            $query = "SELECT note_id FROM note_entities WHERE note_id = ?";
            $stmt = $db->query($query, array($noteId));
            if ($stmt->rowCount() == 0) {
                return true;
            } else {
                return false;
            }
         }
    }
}

