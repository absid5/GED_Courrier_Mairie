<?php

try {
    //require_once("modules/moreq/moreq_tables_definition.php");
    require_once("core/class/class_db.php");
} catch (Exception $e){
    echo functions::xssafe($e->getMessage()) . ' // ';
}

/**
 * Interface for standard object controlers
 * @author boulio
 *
 */
interface ObjectControlerIF {
    /**
     * Save given object in database.
     * Return true if succeeded.
     * @param unknown_type $object
     * @return boolean
     */
    function save($object);

    /**
     * Return object with given id
     * if found.
     * @param $object_id
     */
    function get($object_id);

    /**
     * Delete given object from
     * database.
     * Return true if succeeded.
     * @param unknown_type $object
     * @return boolean
     */
    function delete($object);

}
