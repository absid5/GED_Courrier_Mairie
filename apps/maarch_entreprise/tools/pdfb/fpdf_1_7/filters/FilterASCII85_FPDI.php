<?php

require_once ('FilterASCII85.php');

class FilterASCII85_FPDI extends FilterASCII85 {
    
    var $fpdi;
    
    function FPDI_FilterASCII85(&$fpdi) {
        $this->fpdi =& $fpdi;
    }

    function error($msg) {
        $this->fpdi->error($msg);
    }
}