<?php
$core = new core_tools();
$core->load_lang();

if ($core->is_module_loaded('moreq')) {
    define('ADD_RECORD', 1);
    define('CREATE_SERIE', 2);
    define('CREATE_OTHER_AGREGATION', 4);
    define('DATA_MODIFICATION', 8);
    define('DELETE_RECORD', 16);
    define('DELETE_SERIE', 32);
    define('DELETE_OTHER_AGREGATION', 64);
    define('VIEW_LOG', 128);

    // If you add new bitmask, don't forget to increase this constant
    define('MAX_BITMASK', 255);

    $_ENV['security_bitmask'] = array();
    array_push(
        $_ENV['security_bitmask'],
        array(
        	'ID' => ADD_RECORD,
        	'LABEL' => _ADD_RECORD_LABEL,
        )
    );
    array_push(
        $_ENV['security_bitmask'],
        array(
        	'ID' => CREATE_SERIE,
        	'LABEL' => _CREATE_CLASS_LABEL,
        )
    );
    array_push(
        $_ENV['security_bitmask'],
        array(
        	'ID' => CREATE_OTHER_AGREGATION,
        	'LABEL' => _CREATE_OTHER_AGREGATION_LABEL,
        )
    );
    array_push(
        $_ENV['security_bitmask'],
        array(
        	'ID' => DATA_MODIFICATION,
        	'LABEL' => _DATA_MODIFICATION_LABEL,
        )
    );
    array_push(
        $_ENV['security_bitmask'],
        array(
        	'ID' => DELETE_RECORD,
        	'LABEL' => _DELETE_RECORD_LABEL,
        )
    );
    array_push(
        $_ENV['security_bitmask'],
        array(
        	'ID' => DELETE_SERIE,
        	'LABEL' => _DELETE_SERIE_LABEL,
        )
    );
    array_push(
        $_ENV['security_bitmask'],
        array(
        	'ID' => DELETE_OTHER_AGREGATION,
        	'LABEL' => _DELETE_OTHER_AGREGATION_LABEL,
        )
    );
    array_push(
        $_ENV['security_bitmask'],
        array(
       		'ID' => VIEW_LOG,
       		'LABEL' => _VIEW_LOG_LABEL,
        )
    );
} else {
    // define('ADD_RECORD', 1);
    define('DATA_MODIFICATION', 8);
    define('DELETE_RECORD', 16);
    // define('VIEW_LOG', 128);

    // If you add new bitmask, don't forget to increase this constant
    define('MAX_BITMASK', 255);

    $_ENV['security_bitmask'] = array();
/*    array_push(
        $_ENV['security_bitmask'],
        array(
        	'ID' => ADD_RECORD,
        	'LABEL' => _ADD_RECORD_LABEL,
        )
    );*/
    array_push(
        $_ENV['security_bitmask'],
        array(
        	'ID' => DATA_MODIFICATION,
        	'LABEL' => _DATA_MODIFICATION_LABEL,
        )
    );
    array_push(
        $_ENV['security_bitmask'],
        array(
        	'ID' => DELETE_RECORD,
        	'LABEL' => _DELETE_RECORD_LABEL,
        )
    );
}

function getTaskLabel($taskId, $tasksArray)
{
    for ($i = 0; $i < count($tasksArray); $i ++) {
        if ($tasksArray[$i]['ID'] == $taskId) {
            return $tasksArray[$i]['LABEL'];
        }
    }
    return '';
}
