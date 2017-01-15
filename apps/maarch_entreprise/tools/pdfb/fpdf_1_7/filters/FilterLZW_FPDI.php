<?php

require_once ('FilterLZW.php');

class FilterLZW_FPDI extends FilterLZW {
    
    var $fpdi;
    
    function FilterLZW_FPDI(&$fpdi) {
        $this->fpdi =& $fpdi;
    }

    function error($msg) {
        $this->fpdi->error($msg);
    }
}
