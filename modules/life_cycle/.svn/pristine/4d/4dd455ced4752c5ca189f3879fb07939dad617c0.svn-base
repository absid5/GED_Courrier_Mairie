<?php

/** Logger class
 *
 * @author Laurent Giovannoni <dev@maarch.org>
 **/

class Logger4Php
{

    /**
     * Array of errors levels
     *
     * @protected
     **/
    protected $error_levels = array('DEBUG' => 0, 'INFO' => 1, 'NOTICE' => 2, 'WARNING' => 3, 'ERROR' => 4);

    /**
     * Maps each handler with its log threshold.
     *
     * @protected
     **/
    protected $mapping;

    /**
     * Minimum log level
     *
     * @protected
     **/
    protected $threshold_level;

    /**
     * Path to log4Php library
     *
     * @protected
     **/
    protected $log4PhpLibrary;

    /**
     * Name of the logger
     *
     * @protected
     **/
    protected $log4PhpLogger;

    /**
     * Name of the business code
     *
     * @protected
     **/
    protected $log4PhpBusinessCode;

    /**
     * Path of the param of log4php
     *
     * @protected
     **/
    protected $log4PhpConfigPath;

    /**
     * Name of the batch
     *
     * @protected
     **/
    protected $log4PhpBatchName;

    /** Class constructor
     *
     * Inits the threshold level
     *
     * @param $threshold_level (string) Threshold level (set to 'INFO' by default)
     **/
    function __construct($threshold_level = 'WARNING')
    {
        $this->threshold_level = $threshold_level;
        $this->mapping = array_fill(0, count($this->error_levels), array());
    }

    /** Writes error message in current handlers
     *
     * writes only if the error level is greater or equal the threshold level
     *
     * @param $msg (string) Error message
     * @param $error_level (string) Error level (set to 'INFO' by default)
     * @param $error_code (integer) Error code (set to 0 by default)
     **/
    public function write($msg, $error_level = 'INFO', $error_code = 0, $other_params = array())
    {
        if (!array_key_exists($error_level, $this->error_levels)) {
            $error_level = 'INFO';
        }
        $foundLogger = false;
        if ($this->error_levels[$error_level] >= $this->error_levels[$this->threshold_level]) {
            for ($i=$this->error_levels[$error_level];$i>=0;$i--) {
                foreach ($this->mapping[$i] as $handler) {
                    $handler->write($msg, $error_level, $error_code, $other_params);
                    if (
                        get_class($handler) == 'FileHandler'
                        && (isset($this->log4PhpLibrary)
                        && !empty($this->log4PhpLibrary))
                    ) {
                        if ($error_code == 0) {
                            $result = 'OK';
                        } else {
                            $result = 'KO';
                            $msg = '%error_code:' . $error_code . '% ' . $msg;
                        }
                        require_once($this->log4PhpLibrary);
                        $remote_ip = '127.0.0.1';
                        Logger::configure($this->log4PhpConfigPath);
                        $logger = Logger::getLogger($this->log4PhpLogger);
                        $searchPatterns = array('%ACCESS_METHOD%',
                            '%RESULT%',
                            '%BUSINESS_CODE%',
                            '%HOW%',
                            '%WHAT%',
                            '%REMOTE_IP%',
                            '%BATCH_NAME%'
                        );
                        $replacePatterns = array('Script',
                            $result,
                            $this->log4PhpBusinessCode,
                            'UP',
                            $msg,
                            $remote_ip,
                            $this->log4PhpBatchName
                        );
                        $logLine = str_replace($searchPatterns,
                            $replacePatterns,
                            '[%ACCESS_METHOD%][%RESULT%]'
                            . '[%BUSINESS_CODE%][%HOW%][%WHAT%][%BATCH_NAME%]'
                        );
                        $this->writeLog4php($logger, $logLine, $error_level);
                    }
                }
            }
        }
    }

    /**
     *
     * write a log entry with a specific log level
     * @param object $logger Log4php logger
     * @param string $logLine Line we want to trace
     * @param enum $level Log level
     */
    function writeLog4php($logger, $logLine, $level) {
        switch ($level) {
            case 'DEBUG':
                $logger->debug($logLine);
                break;
            case 'INFO':
                $logger->info($logLine);
                break;
            case 'WARNING':
                $logger->warn($logLine);
                break;
            case 'ERROR':
                $logger->error($logLine);
                break;
            case 'FATAL':
                $logger->fatal($logLine);
                break;
        }
    }

    /** Adds a new handler in the current handlers array
     *
     * @param $handler (object) Handler object
     **/
    public function add_handler(&$handler, $error_level = NULL)
    {
        if(!isset($handler))
            return false;

        if(!isset($error_level) || !array_key_exists($error_level, $this->error_levels))
        {
            $error_level = $this->threshold_level;
        }

        $this->mapping[$this->error_levels[$error_level]][] = $handler;
        return true;
    }

    /** Adds a new handler in the current handlers array
     *
     * @param $handler (object) Handler object
     **/
    public function change_handler_log_level(&$handler, $log_level )
    {
        if (!isset($handler) || !isset($log_level))
            return false;

        if (!array_key_exists($log_level, $this->error_levels)) {
           return false;
        }

        for ($i=0; $i<count($this->mapping);$i++) {
            for($j=0;$j<count($this->mapping[$i]);$j++) {
                if($handler == $this->mapping[$i][$j]) {
                    unset($this->mapping[$i][$j]);
                }
            }
        }
        $this->mapping = array_values($this->mapping);
        $this->mapping[$this->error_levels[$log_level]][] = $handler;
        return true;
    }

    /** Sets treshold level
     *
     * @param $treshold (string) treshold level
     **/
    public function set_threshold_level($treshold)
    {
        if (isset($treshold) && array_key_exists($treshold, $this->error_levels)) {
            $this->threshold_level = $treshold;
            return true;
        }
        $this->threshold_level = 'WARNING';
        return false;
    }

    /** Sets log4Php library path
     *
     * @param $log4PhpLibrary (string) path
     **/
    public function set_log4PhpLibrary($log4PhpLibrary)
    {
        if (isset($log4PhpLibrary) && !empty($log4PhpLibrary)) {
            if (file_exists($log4PhpLibrary)) {
                $this->log4PhpLibrary = $log4PhpLibrary;
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /** Sets log4php logger name
     *
     * @param $log4PhpLogger (string) logger name
     **/
    public function set_log4PhpLogger($log4PhpLogger)
    {
        if (isset($log4PhpLogger) && !empty($log4PhpLogger)) {
            $this->log4PhpLogger = $log4PhpLogger;
            return true;
        }
        $this->log4PhpLogger = 'loggerTechnique';
        return false;
    }

    /** Sets log4php path to log4php xml config
     *
     * @param $log4PhpPath (string) path to log4php xml config
     **/
    public function set_log4PhpConfigPath($log4PhpConfigPath)
    {
        if (isset($log4PhpConfigPath) && !empty($log4PhpConfigPath)) {
            if (file_exists($log4PhpConfigPath)) {
                $this->log4PhpConfigPath = $log4PhpConfigPath;
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /** Sets log4php business code
     *
     * @param $log4PhpBusinessCode (string) business code
     **/
    public function set_log4PhpBusinessCode($log4PhpBusinessCode)
    {
        if (isset($log4PhpBusinessCode) && !empty($log4PhpBusinessCode)) {
            $this->log4PhpBusinessCode = $log4PhpBusinessCode;
            return true;
        }
        $this->log4PhpBusinessCode = 'Maarch';
        return false;
    }

    /** Sets log4php batch name
     *
     * @param $log4PhpBatchName (string) BatchName
     **/
    public function set_log4PhpBatchName($log4PhpBatchName)
    {
        if (isset($log4PhpBatchName) && !empty($log4PhpBatchName)) {
            $this->log4PhpBatchName = $log4PhpBatchName;
            return true;
        }
        $this->log4PhpBatchName = 'MaarchBatch';
        return false;
    }

    /** Class destructor
     *
     * Calls handlers destructors
     **/
    function __destruct()
    {
        for($i=0; $i<count($this->mapping);$i++)
        {
            foreach($this->mapping[$i] as $handler)
            {
                unset($handler);
            }
        }
    }
}
