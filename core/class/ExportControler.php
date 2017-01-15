<?php
/*------------------------------------------------------------------------------
- Require needed classes
------------------------------------------------------------------------------*/
require_once('core/class/class_functions.php');
require_once('core/class/class_history.php');

class EmptyObject {
    function __construct()
    {
        $test = '';
    }
}

/*------------------------------------------------------------------------------
- ExportControler
------------------------------------------------------------------------------*/
class ExportControler extends ExportFunctions
{
    /*--------------------------------------------------------------------------
    - Attributes
    --------------------------------------------------------------------------*/
        // Public
        // Private
        public $collection    = false;
        public $configuration = false;
        public $delimiter     = false;
        public $enclosure     = false;
        
        public $object_export = false;
        public $array_export  = false;
    
    /*--------------------------------------------------------------------------
    - Methods
    --------------------------------------------------------------------------*/
        // Public
        function __construct()
        {
            $this->collection = $_SESSION['collection_id_choice'];
            $this->load_configuration();
            $this->retrieve_datas();
            $this->process_functions();
            $this->insert_emptyColumns();
            $_SESSION['export']['filename'] = $this->make_csv();
        }
        
        // Private
        private function load_configuration()
        {
            // Retrieve id to create paths (app & custom)
            $id_app = $_SESSION['config']['app_id'];
            $id_custom = false;
            if (!empty($_SESSION['custom_override_id']))
                $id_custom = $_SESSION['custom_override_id'];
            $collection = $this->collection;
            
            // Retrieve name for export configuration file
            $fileName = 'export.xml';
            
            // Make paths to xml dir
            $pathToDir_app = 'apps/' . $id_app . '/xml/';
            $pathToDir_custom = 'custom/' . $id_custom . '/' . $pathToDir_app;
            
            $pathToFile_app = $pathToDir_app . $fileName;
            $pathToFile_custom = $pathToDir_custom . $fileName;
            
            // Load the configuration file
            if ($id_custom && file_exists($pathToFile_custom))
                $configuration = simplexml_load_file($pathToFile_custom);
            else
                $configuration = simplexml_load_file($pathToFile_app);
            
            // Store interesting part of the configuration
            $this->configuration = $configuration->{$collection};
            $this->delimiter     = end($configuration->CSVOPTIONS->DELIMITER);
            $this->enclosure     = end($configuration->CSVOPTIONS->ENCLOSURE);
            $this->isUtf8 = end($configuration->CSVOPTIONS->IS_UTF8);
        }
        
        private function retrieve_datas()
        {
            // Retrieve the query
            $query = $this->make_query();
            
            // Retrieve datas
            $db = new Database();

            $stmt = $db->query($query, $_SESSION['last_select_query_parameters']);
            $i = 0;
            $this->object_export = new EmptyObject();
            while($line = $stmt->fetchObject()) {
                if ($i == 0) {
                    $this->object_export->{$i} = $this->retrieve_header();
                    $i = 1;
                }
                if ($line->doc_date) {
                    $line->doc_date = substr($line->doc_date, 0,10);
                }
                if ($line->admission_date) {
                    $line->admission_date = substr($line->admission_date, 0,10);
                }
                if ($line->process_limit_date) {
                    $line->process_limit_date = substr($line->process_limit_date, 0,10);
                }
                $this->object_export->{$i} = $line;
                $i++;
            }
        }
        
        private function make_query()
        {
            // Retrieve the end of last select query on the list
            $endLastQuery = substr(
                $_SESSION['last_select_query'], 
                strpos(
                    $_SESSION['last_select_query'], 
                    'FROM'
                )
            );
            
            // Create template for the new query
            $query_template = 'SELECT ';
                $query_template .= '##DATABASE_FIELDS## ';
            $query_template .= $endLastQuery;
            
            // Retrieve ##DATABASE_FIELDS##
            $fields = $this->configuration->FIELD;
            $i_max = count($fields);
            $database_fields = false;
            for($i=0; $i<$i_max; $i++) {
                $field = $fields[$i];
                $database_fields .= $field->DATABASE_FIELD;
                if ($i != ($i_max-1))
                    $database_fields .= ', ';
            }
            
            // Return query
            return str_replace(
                '##DATABASE_FIELDS##', 
                $database_fields, 
                $query_template
            );
        }
        
        private function retrieve_header()
        {
            $return = new StdClass();
            $fields = $this->configuration->FIELD;
            $i_max = count($fields);
            for($i=0; $i<$i_max; $i++) {
                $field = $fields[$i];
                $database_field = end(explode('.', $field->DATABASE_FIELD));
                $return->{$database_field} = end($field->LIBELLE);
            }
            return $return;
        }
        
        private function encode()
        {
            foreach($this->object_export as $line_name => $line_value) {
                foreach($line_value as $column_name => $column_value) {
                    if ($this->retrieve_encoding($column_value) === false) {
                        $column_value = utf8_encode($column_value);
                    }
                    if ($this->isUtf8 <> "TRUE") {
                        $column_value = utf8_decode($column_value);
                    }
                    $column_value = $this->unprotect_string($column_value);
                    $this->object_export->{$line_name}->{$column_name} = $column_value;
                }
            }
        }
        
        private function retrieve_encoding($string)
        {
            return mb_detect_encoding($string, 'UTF-8', true);
        }
        
        private function unprotect_string($string)
        {
            return str_replace("\'", "'", $string);
        }
        
        private function process_functions()
        {
            $functions = $this->configuration->FUNCTIONS->FUNCTION;
            $functions_max = count($functions);
            for($i=0; $i<$functions_max; $i++) {
                $function = $functions[$i];
                $call = $function->CALL;
                if (method_exists($this, $call))
                    eval('$this->' . $call . '(\'' . $function->LIBELLE . '\');');
            }
        }
        
        private function insert_emptyColumns()
        {
            $emptys = $this->configuration->EMPTYS->EMPTY;
            $emptys_max = count($emptys);
            for ($i=0; $i<$emptys_max; $i++) {
                $empty = $emptys[$i];
                $libelle = end($empty->LIBELLE);
                $colname = end($empty->COLNAME);
                foreach($this->object_export as $line_name => $line_value) {
                    $line_value->{$colname} = $libelle;
                    break;
                }
            }
        }
        
        private function make_csv()
        {
            // Manage encoding
            $this->encode();
            
            // Object2Array
            $functions = new functions();
            $this->array_export = $functions->object2array($this->object_export);
            
            // Create temp path
            do {
                $csvName = $_SESSION['user']['UserId'] . '-' . md5(date('Y-m-d H:i:s')) . '.csv';
                if (isset($pathToCsv) && !empty($pathToCsv)) {
                    $csvName = $_SESSION['user']['UserId'] . '-' . md5($pathToCsv) . '.csv';
                }
                $pathToCsv = $_SESSION['config']['tmppath'] . $csvName;
            } while (file_exists($pathToCsv));
            
            // Write csv
            $csv = fopen($pathToCsv, 'a+');
            foreach ($this->array_export as $line) {
                fputcsv($csv, $line, $this->delimiter, $this->enclosure);
            }
            fclose($csv);
            
            // Return csvName
            return $csvName;
        }
}

class ExportFunctions
{
    /* -------------------------------------------------------------------------
    - Functions
    -
    - All the functions must have only one argument
    - This argument is the name of the column for the header of the export
    -
    - Toutes les fonctions doivent avoir un argument et un seul
    - Cette argument est le libelle de la colonne à afficher dans l'en-tête du
    - fichier d'export
    ------------------------------------------------------------------------- */
    function retrieve_copies($libelle)
    {
        $db = new Database();

        $collection = $this->collection;
        
        $query_template = 'SELECT ';
            $query_template .= 'item_id, ';
            $query_template .= 'item_type ';
        $query_template .= 'FROM ';
            $query_template .= 'listinstance ';
        $query_template .= 'WHERE ';
                $query_template .= "res_id = ##res_id## ";
            $query_template .= "AND ";
                $query_template .= "coll_id = '" . $collection . "' ";
            $query_template .= "AND ";
                $query_template .= "item_mode = 'cc'";
        
        
        $query_template2 = 'SELECT ';
            $query_template2 .= 'entity_id ';
        $query_template2 .= 'FROM ';
            $query_template2 .= 'users_entities ';
        $query_template2 .= 'WHERE ';
                $query_template2 .= "user_id = ##item_id## ";
            $query_template2 .= "AND ";
                $query_template2 .= "primary_entity = 'Y'";
        
        
        $i = 0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->retrieve_copies = $libelle;
                $i++;
                continue;
            }
            $return = false;
            $res_id = $line_value->res_id;
            $query1 = str_replace('##res_id##', '?', $query_template);
            $stmt = $db->query($query1, array($res_id));
            while($result1 = $stmt->fetchObject()) {
                $param2 = array();
                if ($result1->item_type == 'user_id') {
                    $param2[] = $result1->item_id;
                    $query2 = str_replace('##item_id##', '?', $query_template2);
                    $stmt2 = $db->query($query2, $param2);
                    while ($result2 = $stmt2->fetchObject()) {
                        $usersEntities = $result1->item_id . ' : ' . $result2->entity_id;
                    }
                } else {
                    $usersEntities = $result1->item_id;
                }
                $return .= $usersEntities . " # ";
            }
            if (strlen($return) > 3)
                $return = substr($return, 0, -3);
            
            $line_value->retrieve_copies = $return;
            $i++;
        }
    }
    
    function makeLink_detail($libelle)
    {
        $link_template = $_SESSION['config']['businessappurl']
            . 'index.php' 
            . '?page=details' 
            . '&dir=indexing_searching' 
            . '&id=##res_id##';
        
        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->makeLink_detail = $libelle;
                $i++;
                continue;
            }
            $res_id = $line_value->res_id;
            $link = str_replace('##res_id##', $res_id, $link_template);
            $line_value->makeLink_detail = $link;
        }
    }

    function get_priority($libelle)
    {

        $endLastQuery = substr(
            $_SESSION['last_select_query'], 
            strpos(
                $_SESSION['last_select_query'], 
                'FROM'
            )
        );

        $query_priority = "SELECT priority FROM res_view_letterbox WHERE res_id = ##res_id## ";

        $db = new Database();

        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->get_priority = $libelle;
                $i++;
                continue;
            }

            $res_id = $line_value->res_id;
            $query = str_replace('##res_id##', '?', $query_priority);
            $stmt = $db->query($query, array($res_id));

            $result = $stmt->fetchObject();

            $link_label = $_SESSION['mail_priorities'][$result->priority];

            $line_value->get_priority = $link_label;
        }
    }

    function get_status($libelle)
    {

        $endLastQuery = substr(
            $_SESSION['last_select_query'], 
            strpos(
                $_SESSION['last_select_query'], 
                'FROM'
            )
        );

        $query_status = "SELECT label_status FROM res_view_letterbox LEFT JOIN status on res_view_letterbox.status = status.id WHERE res_id = ##res_id## ";

        $db = new Database();

        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->get_status = $libelle;
                $i++;
                continue;
            }

            $res_id = $line_value->res_id;
            $query = str_replace('##res_id##', '?', $query_status);
            $stmt = $db->query($query, array($res_id));

            $result = $stmt->fetchObject();

            $line_value->get_status = $result->label_status;
        }
    }

    function get_tags($libelle)
    {

        $collection = $this->collection;

        $query_status = "SELECT tag_label FROM tags WHERE coll_id = '".$collection."' and res_id = ##res_id## ";

        $db = new Database();

        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->get_tags = $libelle;
                $i++;
                continue;
            }

            $res_id = $line_value->res_id;
            $query = str_replace('##res_id##', '?', $query_status);
            $stmt = $db->query($query, array($res_id));

            $return = "";
            while ($result = $stmt->fetchObject()) {
                $return .= $result->tag_label . " ## ";
            }


            $line_value->get_tags = $return;
        }
    }

    function get_contact_type($libelle)
    {

        $query_status = "SELECT ct.label FROM res_view_letterbox r LEFT JOIN contacts_v2 c ON c.contact_id = r.contact_id LEFT JOIN contact_types ct ON ct.id = c.contact_type WHERE r.res_id = ##res_id## ";

        $db = new Database();

        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->get_contact_type = $libelle;
                $i++;
                continue;
            }

            $res_id = $line_value->res_id;
            $query = str_replace('##res_id##', '?', $query_status);
            $stmt = $db->query($query, array($res_id));

            $result = $stmt->fetchObject();

            $line_value->get_contact_type = $result->label;
        }
    }

    function get_contact_civility($libelle)
    {

        $query_status = "SELECT c.title FROM res_view_letterbox r LEFT JOIN contacts_v2 c ON c.contact_id = r.contact_id WHERE r.res_id = ##res_id## ";

        $db = new Database();

        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->get_contact_civility = $libelle;
                $i++;
                continue;
            }

            $res_id = $line_value->res_id;
            $query = str_replace('##res_id##', '?', $query_status);
            $stmt = $db->query($query, array($res_id));

            $result = $stmt->fetchObject();

            $line_value->get_contact_civility = $_SESSION['mail_titles'][$result->title];
        }
    }

    function get_entity_dest_short_label($libelle)
    {

        $query_status = "SELECT destination FROM res_view_letterbox r WHERE r.res_id = ##res_id## ";

        $db = new Database();

        require_once("modules/entities/class/class_manage_entities.php");
        $entities = new entity();

        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->get_entity_dest_short_label = $libelle;
                $i++;
                continue;
            }

            $res_id = $line_value->res_id;
            $query = str_replace('##res_id##', '?', $query_status);
            $stmt = $db->query($query, array($res_id));

            $result = $stmt->fetchObject();

            $line_value->get_entity_dest_short_label = $entities->getentityshortlabel($result->destination);
        }
    }

    function get_signatory_name($libelle)
    {

        $query_status = "SELECT DISTINCT u.lastname, u.firstname FROM res_view_attachments r LEFT JOIN users u ON u.user_id = r.typist WHERE r.attachment_type = 'signed_response' and r.status = 'TRA' and r.res_id_master = ##res_id## ";

        $db = new Database();

        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->get_signatory_name = $libelle;
                $i++;
                continue;
            }

            $res_id = $line_value->res_id;
            $query = str_replace('##res_id##', '?', $query_status);
            $stmt = $db->query($query, array($res_id));

            $j = 0;
            $return = "";
            
            while($result = $stmt->fetchObject()){
                if ($j > 0) {
                    $return .= ", ";
                }
                $return .= strtoupper($result->lastname) . ' ' . ucfirst($result->firstname);
                $j++;

            }

            $line_value->get_signatory_name = $return;
        }
    }

    function get_signatory_date($libelle)
    {

        $query_status = "SELECT creation_date FROM res_view_attachments r WHERE r.attachment_type = 'signed_response' and r.status = 'TRA' and r.res_id_master = ##res_id## ";

        $db = new Database();

        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->get_signatory_date = $libelle;
                $i++;
                continue;
            }

            $res_id = $line_value->res_id;
            $query = str_replace('##res_id##', '?', $query_status);
            $stmt = $db->query($query, array($res_id));

            $j = 0;
            $return = "";

            while($result = $stmt->fetchObject()){

                if ($j > 0) {
                    $return .= ", ";
                }

                $return .= functions::format_date_db($result->creation_date);
                $j++;
                
            }

            $line_value->get_signatory_date = $return;
        }
    }

    function get_parent_folder($libelle)
    {

        $query_status = "SELECT folder_name FROM folders WHERE folders_system_id in ( SELECT f.parent_id FROM res_view_letterbox r LEFT JOIN folders f ON r.folders_system_id = f.folders_system_id WHERE r.res_id = ##res_id## )";

        $db = new Database();

        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->get_parent_folder = $libelle;
                $i++;
                continue;
            }

            $res_id = $line_value->res_id;
            $query = str_replace('##res_id##', '?', $query_status);
            $stmt = $db->query($query, array($res_id));

            $result = $stmt->fetchObject();

            $line_value->get_parent_folder = $result->folder_name;
        }
    }

    function get_answer_nature($libelle)
    {

        $query_status = "SELECT answer_type_bitmask FROM res_view_letterbox WHERE res_id = ##res_id## ";

        $db = new Database();

        $i=0;
        foreach($this->object_export as $line_name => $line_value) {
            if ($i == 0) {
                $line_value->get_answer_nature = $libelle;
                $i++;
                continue;
            }

            $res_id = $line_value->res_id;
            $query = str_replace('##res_id##', '?', $query_status);
            $stmt = $db->query($query, array($res_id));

            $result = $stmt->fetchObject();

		    $bitmask = $result->answer_type_bitmask;
		    switch ($bitmask) {
		        case "000000":
		            $answer = '';
		            break;
		        case "000001":
		            $answer = _SIMPLE_MAIL;
		            break;
		        case "000010":
		            $answer = _REGISTERED_MAIL;
		            break;
		        case "000100":
		            $answer = _DIRECT_CONTACT;
		            break;
		        case "001000":
		            $answer = _EMAIL;
		            break;
		        case "010000":
		            $answer = _FAX;
		            break;
		        case "100000":
		            $answer = _ANSWER;
		            break;
		        default:
		            $answer = '';
		    }

            $line_value->get_answer_nature = $answer;
        }    	
    }
}
