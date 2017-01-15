<?php
/**
* File : user_info.php
*
* Page to show all data on a maarch user
*
* @package  Maarch Framework 3.0
* @version 3.0
* @since 10/2005
* @license GPL
* @author  Claire Figueras <dev@maarch.org>
*/

$core_tools = new core_tools();
$core_tools->load_lang();
$core_tools->load_html();
$core_tools->load_header('', true, false);
$func = new functions();
$db = new Database();
if($_REQUEST['id'] == "")
{
    echo '<script type="text/javascript">window.resizeTo(400, 300);</script>';
    echo '<br/><br/><center>'._YOU_MUST_SELECT_USER.'</center><br/><br/><div align="center">
        <input name="close" type="button" value="'._CLOSE.'"  onclick="self.close();" class="button" />
        </div>';
}
else
{
    $stmt = $db->query("SELECT * FROM ".$_SESSION['tablename']['users']." WHERE user_id = ?", array($_REQUEST['id']));
    if($stmt->rowCount() == 0)
    {
        $_SESSION['error'] = _THE_USER.' '._NOT_EXISTS;
        $state = false;
    }
    else
    {
        $user_data = array();
        $line = $stmt->fetchObject();
        $user_data['ID'] = $func->show_string($line->user_id);
        $user_data['LASTNAME'] = $func->show_string($line->lastname);
        $user_data['FIRSTNAME'] = $func->show_string($line->firstname);
        $user_data['PHONE'] = $func->show_string($line->phone);
        $user_data['MAIL'] = $func->show_string($line->mail);
    }
    ?>
<!--     <script type="text/javascript">window.resizeTo(500, 350);</script> -->
<div class="popup_content" align="center">
    <br/>
    <h2 align="center"><i class="fa fa-user fa-2x"></i> <?php echo _USER_DATA;?></h2>   <br/>
    <form name="frmuserdata" id="frmuserdata" method="post" action="#" class="forms addforms">

         <p id="lastname_p">
            <label for="lastname"><?php echo _LASTNAME;?> : </label>
            <input name="lastname" type="text"  id="lastname" value="<?php functions::xecho($func->show_str($user_data['LASTNAME']));?>" readonly="readonly"/>
         </p>
         <p id="firstname_p">
            <label for="firstname"><?php echo _FIRSTNAME;?> : </label>
            <input name="firstname" type="text"  id="firstname" value="<?php functions::xecho($func->show_str($user_data['FIRSTNAME']));?>" readonly="readonly"/>
         </p>
          <p>
            <label for="phone"><?php echo _PHONE;?> : </label>
            <input name="phone" type="text"  id="phone" value="<?php functions::xecho($func->show_str($user_data['PHONE']));?>" readonly="readonly"/>
         </p>
         <p>
            <label for="mail"><?php echo _MAIL;?> : </label>
            <input name="mail" type="text" id="mail" value="<?php functions::xecho($func->show_str($user_data['MAIL']));?>" readonly="readonly"/>
         </p>
         <?php
        if($core_tools->is_module_loaded('entities'))
        {
            require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_manage_entities.php');
            $ent = new entity();
            $entities = $ent->get_entities_of_user($_REQUEST['id']);

            ?>
            <p>
                <label for="entities"><?php echo _ENTITIES;?></label>
                <select multiple="multiple" name="entities"  size="7">
                <?php for($i=0; $i<count($entities);$i++)
                {
                    ?><option value=""><?php
                    if($entities[$i]['PRIMARY'] == 'Y')
                    {
                        echo '<b>'.functions::xssafe($entities[$i]['LABEL']).'</b>';
                    }
                    else
                    {
                        echo functions::xssafe($entities[$i]['LABEL']);
                    }
                    ?></option><?php
                }?>
                </select>
            </p>
            <?php
        }
        if (!$from_iframe) { ?>
            <p class="buttons">
                <input name="close" type="button" value="<?php echo _CLOSE;?>"  onclick="self.close();" class="button" />
            </p>
    <?php 
        }
    ?>
    </form >
</div>
    <?php
}
$core_tools->load_js();
