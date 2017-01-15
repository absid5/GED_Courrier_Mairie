<?php

/*
*    Copyright 2008-2011 Maarch
*
*  This file is part of Maarch Framework.
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
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once 'apps/'. $_SESSION['config']['app_id'] .'/admin/priorities/class_priorities.php';


$core_tools = new core_tools();
$core_tools->test_admin('admin_priorities', 'apps');

if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
    $priority = new Priorities();
    if ($mode === 'update') {
        $priority->updatePriorities();
    } elseif ($mode === 'delete') {
        $priority->deletePriority();
    }
}
?>
    <script type="text/javascript" >
        window.location.href='<?php echo $_SESSION['config']['businessappurl']; ?>index.php?page=admin_priorities&admin=priorities';
    </script>

<?php
exit;