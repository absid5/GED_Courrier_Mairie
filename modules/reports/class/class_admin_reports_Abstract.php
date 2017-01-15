<?php
/*
*   Copyright 2008-2016 Maarch
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

/**
* @brief   Module reports :  Administration of the reports
*
* Forms and process to link reports to groups
*
* @file
* @author Yves Christian KPAKPO <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup reports
*/

abstract class admin_reports_Abstract extends Database
{

    /**
    * Loads the reports of a user group in session variables.
    *
    * @param  $group_id string User group identifier
    */
    public function load_reports_group($group_id)
    {
		$db = new Database();
        $_SESSION['m_admin']['reports']['groups']=array();

        $stmt = $db->query("select report_id from ".$_SESSION['tablename']['usergroups_reports']." where group_id = ?", array($group_id));
        //$this->show();
        if($stmt->rowCount() != 0)
        {
            while($value = $stmt->fetchObject())
            {
                array_push($_SESSION['m_admin']['reports']['groups'],$value->report_id);
            }
        }
        //$_SESSION['m_admin']['load_reports'] = false;
    }


    /**
    * Loads into database the reports for a user group
    *
    * @param  $reports array Array os reports
    * @param  $group string User group identifier
    */
    public function load_reports_db($reports, $group)
    {
        $db = new Database();
        
        $stmt = $db->query("delete from ".$_SESSION['tablename']['usergroups_reports']." where group_id = ? ", array($group));
        for($i=0; $i<count($reports);$i++)
        {
            $stmt = $db->query("insert into ".$_SESSION['tablename']['usergroups_reports']." values (?, ?)", 
					array($group, $reports[$i])
            );
        }

        if($_SESSION['history']['usergroupsreportsadd'] == "true")
        {
            require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
            $hist = new history();
            $hist->add($_SESSION['tablename']['usergroups_reports'], $group,"ADD",'usergroupsreportsadd', _GROUP_REPORTS_ADDED." : ".$group, $_SESSION['config']['databasetype']);
        }

        unset($_SESSION['m_admin']);

        $_SESSION['info'] =  _GROUP_REPORTS_ADDED;
        header("location: ".$_SESSION['config']['businessappurl']."index.php?page=admin_reports&module=reports");
        exit();
    }

    /**
    * Returns all the reports for the superadmin in an array
    *
    */
    protected function get_all_reports()
    {
        require_once('modules'.DIRECTORY_SEPARATOR.'reports'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');
        $rep = new reports();
        $enabled_reports = $rep->get_reports_from_xml();
        $reports = array();
        foreach(array_keys($enabled_reports)as $key)
        {
            $reports[$key] = true;
        }
        return $reports;
    }

    /**
    * Loads into session all the reports for a user
    *
    * @param  $user_id  string User identifier
    */
    public function load_user_reports($user_id, $from)
    {
        $db = new Database();
        $reports = array();
        if($user_id == "superadmin")
        {
            $reports = $this->get_all_reports();
        }
        else
        {
            require_once('modules'.DIRECTORY_SEPARATOR.'reports'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');
            $rep = new reports();
            $enabled_reports = $rep->get_reports_from_xml();
            //$_SESSION['user']['reports'] = array();
            require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."usergroups_controler.php");

            foreach(array_keys($enabled_reports)as $key)
            {
                $stmt = $db->query("select group_id from ".$_SESSION['tablename']['usergroups_reports']." where report_id = ? ", array($key));
                $find = false;
                $res = false;
                while($res=$stmt->fetchObject())
                {
                    if(usergroups_controler::inGroup($user_id, $res->group_id) == true)
                    {
                        $find = true;
                        break;
                    }
                }
                if($find == true)
                {
                    $reports[$key] = true;
                }
                else
                {
                    //$_SESSION['user']['reports'][$_SESSION['enabled_reports'][$i]['id']] = false;
                    //$reports[$_SESSION['enabled_reports'][$i]['id']] = false;
                }
            }
        }
        return $reports;
    }

    /**
    * Form for the management of the reports by groups
    *
    * @param    string  $id  group identifier (empty by default)
    */
    public function groupreports($id = "")
    {
        require_once 'core/class/class_security.php';
        require_once 'modules/reports/class/class_modules_tools.php';
        require_once 'core/class/usergroups_controler.php';
        require_once 'core/class/users_controler.php';
        $rep = new reports();
        $enabled_reports = $rep->get_reports_from_xml();
        $sec = new security();
        $func = new functions();
        $core_tools = new core_tools();
        $state = true;
        $tab = array();
        $users = array();
        $uc = new users_controler();
        $ugc = new usergroups_controler();
        $usersIds = $ugc->getUsers($id);
        //$this->show_array($usersIds);exit;
        //$this->show_array($enabled_reports);
        for ($i = 0; $i < count($usersIds); $i ++) {
            $tmpUser = $uc ->get($usersIds[$i]);
            if (isset($tmpUser)) {
                array_push($users, $tmpUser);
            }
        }
        unset($tmpUser);
        //$this->show_array($users);exit;
        if(empty($_SESSION['error']))
        {
			$db = new Database();
            $stmt = $db->query("select * from ".$_SESSION['tablename']['usergroups']." where group_id = ? and enabled = 'Y'", array($id));

            if($stmt->rowCount() == 0)
            {
                $_SESSION['error'] = _GROUP.' '._UNKNOWN;
                $state = false;
            }
            else
            {
                $line = $stmt->fetchObject();
                $_SESSION['m_admin']['reports']['GroupId'] = $line->group_id;
                $_SESSION['m_admin']['reports']['desc'] = $this->show_string($line->group_desc);
            }

            $this->load_reports_group($id);

            //$this->show_array($_SESSION['m_admin']['reports']['groups']);
        }

        if($state == false)
        {
            echo "<br /><br /><br /><br />"._GROUP.' '._UNKNOWN."<br /><br /><br /><br />";
        }
        else
        {
            ?>
            <div id="group_box" class="bloc">
            <?php
            if (count($users) > 0) {
                ?>
                <div onclick="new Effect.toggle('users_list', 'blind', {delay:0.2});return false;" >
                &nbsp;<i class="fa fa-users fa-2x"></i> <i><?php
                echo _SEE_GROUP_MEMBERS;
                ?></i> <i class="fa fa-angle-right"></i>
                <span class="lb1-details">&nbsp;</span></div>
                <div class="desc" id="users_list" style="display:none;">
                    <div class="ref-unit">
                        <table cellpadding="0" cellspacing="0" border="0" class="listingsmall" summary="">
                            <thead>
                                <tr>
                                    <th><?php echo _LASTNAME;?></th>
                                    <th ><?php echo _FIRSTNAME;?></th>
                                    <!--<th  ><?php echo _ENTITY;?></th>-->
                                    <th></th>
                                </tr>
                            </thead>

                        <tbody>
                             <?php
                        $color = ' class="col"';
                        for ($i = 0; $i < count($users); $i ++) {
                            if ($color == ' class="col"') {
                                $color = '';
                            } else {
                                $color = ' class="col"';
                            }
                            ?>
                            <tr <?php functions::xecho($color);?> >
                               <td style="width:25%;"><?php
                                   functions::xecho($users[$i]->__get('lastname'));
                                ?></td>
                                <td style="width:25%;"><?php
                                    functions::xecho($users[$i]->__get('firstname'));
                                ?></td>
                               <td></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                        </table>
                        <br/>
                    </div>
                </div>
            <?php
            }
        ?>

            </div>
            <form name="formgroupreport" method="post"  class="forms" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=reports&page=groupreports_up_db">

                <br><center><i><?php echo _AVAILABLE_REPORTS.'</i> '.$_SESSION['m_admin']['reports']['desc'];?> :</center>
                <?php
                $enabled_reports_sort_by_parent = array();
                $j=0;
                $last_val = '';
                foreach(array_keys($enabled_reports)as $key)
                {
                    if($enabled_reports[$key]['module'] <> $last_val)
                    {
                        $j=0;
                    }
                    $enabled_reports_sort_by_parent[$enabled_reports[$key]['module']][$j] = $enabled_reports[$key];
                    $j++;
                    $last_val = $enabled_reports[$key]['module'];
                }
            //  $this->show_array($enabled_reports_sort_by_parent);
                $_SESSION['cpt']=0;
                foreach(array_keys($enabled_reports_sort_by_parent) as $value)
                {
                    ?>
                    <h5 onclick="change3(<?php
                echo $_SESSION['cpt'];
                ?>)" id="h2<?php
                echo $_SESSION['cpt'];
                 ?>" class="categorie">
                  <i class='fa fa-plus fa-2x'></i>&nbsp;<b><?php functions::xecho($enabled_reports_sort_by_parent[$value][0]['module_label']);?></b>
                <span class="lb1-details">&nbsp;</span>
                </h5><br/>
                <div class="desc block_light admin" id="desc<?php
                 echo $_SESSION['cpt'];
                 ?>" style="display:none">
                <div class="ref-unit">
                            <table>
                                <?php
                                for($i=0; $i<count($enabled_reports_sort_by_parent[$value]); $i++)
                                {
                                    ?>
                                    <tr>
                                        <td width="800px" align="right" title="<?php functions::xecho($enabled_reports_sort_by_parent[$value][$i]['desc']);?>">
                                            <?php functions::xecho($enabled_reports_sort_by_parent[$value][$i]['label']);?> :
                                        </td>
                                        <td width="50px" align="left">
                                            <input type="checkbox" name="reports[]" value="<?php functions::xecho($enabled_reports_sort_by_parent[$value][$i]['id']);?>" <?php  if(in_array($enabled_reports_sort_by_parent[$value][$i]['id'],$_SESSION['m_admin']['reports']['groups'])){ echo 'checked="checked"';}?>  />
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                    <?php
                    $_SESSION['cpt']++;
                }
                ?>
                <br/>
                <p class="buttons">
                    <input id="groupbutton" type="submit"  name="Submit" value="<?php echo _VALIDATE;?>" class="button" />
                    <input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=admin&reinit=true';"/>
                </p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
            </form>

        <?php
        }
    }
}
?>
