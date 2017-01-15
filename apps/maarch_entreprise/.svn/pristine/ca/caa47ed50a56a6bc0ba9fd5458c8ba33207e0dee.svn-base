<?php

if ($_REQUEST['AJAX_CALL'] && $_REQUEST['res_id']) {
    $res_id = $_REQUEST['res_id'];
    $user_id = ($_REQUEST['user_id']) ? $_REQUEST['user_id'] : false;
    $table = ($_REQUEST['table']) ? $_REQUEST['table'] : false;
    $coll_id = ($_REQUEST['coll_id']) ? $_REQUEST['coll_id'] : false;

    $docLocker = new docLocker($res_id, $user_id, $table, $coll_id);

    if ($_REQUEST['lock'])
        $docLocker->lock();
    else if ($_REQUEST['unlock'])
        $docLocker->unlock();
}

class docLocker
{
    private $res_id  = null;
    private $user_id = null;
    private $table   = null;
    private $coll_id = null;

    public function __construct($res_id, $user_id = false, $table = false, $coll_id = false)
    {
        // set properties
        $this->res_id  = $res_id;
        $this->user_id = ($user_id) ? $user_id : $_SESSION['user']['UserId'];
        $this->table   = ($table) ? $table : 'res_letterbox';
        $this->coll_id = ($coll_id) ? $coll_id : 'letterbox_coll';

        // require
        $corePath         = $_SESSION['config']['corepath'];
        $appId            = $_SESSION['config']['app_id'];
        $customOverrideId = $_SESSION['custom_override_id'];

    }

    public function canOpen()
    {
        if (!$this->checkProperties()) return false;
        if ($this->isLocked() && $this->userLock() != $this->user_id) {
            $userlock_id = $this->userLock();

            $db = new Database();
            $stmt = $db->query("SELECT firstname, lastname FROM users WHERE user_id = ?", array($userlock_id));
            $userLock_info = $stmt->fetchObject();           
            $_SESSION['userLock'] = $userLock_info->firstname .' '. $userLock_info->lastname;

            return false;
        }

        return true;
    }

    public function lock()
    {
        if (!$this->checkProperties()) return false;

        $query = "UPDATE ";
            $query .= $this->table . " ";
        $query .= "SET ";
            $query .= "locker_user_id = ?, ";
            $query .= "locker_time = current_timestamp + interval '1 MINUTE' ";
        $query .= "WHERE ";
            $query .= "res_id = ?";

        $arrayPDO = array($this->user_id, $this->res_id);

        $db = new Database();
        $db->query($query, $arrayPDO);

        return true;
    }

    public function unlock()
    {
        if (!$this->checkProperties()) return false;

        $query .= "UPDATE ";
            $query .= $this->table . " ";
        $query .= "SET ";
            $query .= "locker_user_id = NULL, ";
            $query .= "locker_time = NULL ";
        $query .= "WHERE ";
            $query .= "res_id = ?";

        $arrayPDO = array($this->res_id);

        $db = new Database();
        $db->query($query, $arrayPDO);

        return true;
    }

    private function checkProperties()
    {
        if (is_null($this->res_id))  return false;
        if (is_null($this->user_id)) return false;
        if (is_null($this->table))   return false;
        if (is_null($this->coll_id)) return false;

        return true;
    }

    private function isLocked()
    {
        $query = "SELECT ";
            $query .= "1 ";
        $query .= "FROM ";
            $query .= $this->table . " ";
        $query .= "WHERE ";
                $query .= "res_id = ? ";
            $query .= "AND ";
                $query .= "locker_time > current_timestamp";

        $arrayPDO = array($this->res_id);

        $db = new Database();
        $stmt = $db->query($query, $arrayPDO);

        if ($stmt->rowCount() > 0)
                return true; 

        return false;
    }

    private function userLock()
    {
        $query = "SELECT ";
            $query .= "locker_user_id as user_lock ";
        $query .= "FROM ";
            $query .= $this->table . " ";
        $query .= "WHERE ";
            $query .= "res_id = ? ";

        $arrayPDO = array($this->res_id);
        
        $db = new Database();
        $stmt = $db->query($query, $arrayPDO);

        while ($result = $stmt->fetchObject())
            return $result->user_lock;

        return '';
    }
}