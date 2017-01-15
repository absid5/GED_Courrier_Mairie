<?php
include_once('modules/entities/entities_tables.php');
if($_SESSION['service_tag'] == 'admin_templates')
{?>
    <table align="center" width="100%" id="template_entities" >
        <tr>
            <td colspan="3"><?php echo _CHOOSE_ENTITY_TEMPLATE;?> :</td>
        </tr>
        <tr>
            <td width="40%" align="center">
                <select name="entitieslist[]" id="entitieslist" size="7" ondblclick='moveclick(document.frmtemplate.elements["entitieslist[]"],document.frmtemplate.elements["entities_chosen[]"]);' multiple="multiple" >
                <?php
                for($i=0;$i<count($_SESSION['m_admin']['entities']);$i++)
                {
                    $state_entity = false;
                    for($j=0;$j<count($_SESSION['m_admin']['template']['ENTITIES_LIST']);$j++)
                    {
                        if($_SESSION['m_admin']['entities'][$i]['ID'] == $_SESSION['m_admin']['template']['ENTITIES_LIST'][$j]['ID'])
                        {
                            $state_entity = true;
                        }
                    }
                    if($state_entity == false)
                    {
                        ?>
                        <option value="<?php functions::xecho($_SESSION['m_admin']['entities'][$i]['ID']);?>"><?php echo $_SESSION['m_admin']['entities'][$i]['LABEL'];?></option>
                    <?php
                    }
                }
                ?>
                </select><br/>
                <em><a href='javascript:selectall(document.forms["frmtemplate"].elements["entitieslist[]"]);' ><?php echo _SELECT_ALL;?></a></em>
            </td>
            <td width="20%" align="center">
                <input type="button" class="button" value="<?php echo _ADD;?> &gt;&gt;" onclick='Move(document.frmtemplate.elements["entitieslist[]"],document.frmtemplate.elements["entities_chosen[]"]);' />
                <br />
                <br />
                <input type="button" class="button" value="&lt;&lt; <?php echo _REMOVE;?>" onclick='Move(document.frmtemplate.elements["entities_chosen[]"],document.frmtemplate.elements["entitieslist[]"]);' />
            </td>
            <td width="40%" align="center">
                <select name="entities_chosen[]" id="entities_chosen" size="7" ondblclick='moveclick(document.frmtemplate.elements["entities_chosen[]"],document.frmtemplate.elements["entitieslist"]);' multiple="multiple" >
                <?php
                for($i=0;$i<count($_SESSION['m_admin']['entities']);$i++)
                {
                    $state_entity = false;
                    for($j=0;$j<count($_SESSION['m_admin']['template']['ENTITIES_LIST']);$j++)
                    {
                        if($_SESSION['m_admin']['entities'][$i]['ID'] == $_SESSION['m_admin']['template']['ENTITIES_LIST'][$j]['ID'])
                        {
                            $state_entity = true;
                        }
                    }
                    if($state_entity == true)
                    {
                    ?>
                        <option value="<?php functions::xecho($_SESSION['m_admin']['entities'][$i]['ID']);?>" selected="selected" ><?php echo $_SESSION['m_admin']['entities'][$i]['LABEL'];?></option>
                    <?php
                    }
                }
                ?>
                </select><br/>
                <em><a href="javascript:selectall(document.forms['frmtemplate'].elements['entities_chosen[]']);" >
                <?php echo _SELECT_ALL;?></a></em>
            </td>
        </tr>
    </table><?php
    $_SESSION['service_tag'] = '';
}
elseif($_SESSION['service_tag'] == 'load_template_session')
{
    require_once('modules'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');

    $template = new templates();
    $entities = $template->getAllItemsLinkedToModel($_SESSION['m_admin']['template']['ID'], 'destination');
    $_SESSION['m_admin']['template']['ENTITIES_LIST'] = array();

    $db = new Database();
    for($i=0; $i<count($entities['destination']);$i++)
    {
        $stmt = $db->query("select entity_label from ".ENT_ENTITIES." where entity_id = ?",array($entities['destination'][$i]) );
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        array_push($_SESSION['m_admin']['template']['ENTITIES_LIST'], array('ID' => $entities['destination'][$i], 'LABEL' => $res->label));
    }

    $_SESSION['service_tag'] = '';
}
elseif($_SESSION['service_tag'] == 'template_info')
{
    require_once("modules".DIRECTORY_SEPARATOR."entities".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_entities.php");
    $ent = new entity();
    $_SESSION['m_admin']['template']['ENTITIES'] = array();
    for($i=0;$i<count($_REQUEST['entities_chosen']); $i++)
    {
        $label = $ent->getentitylabel($_REQUEST['entities_chosen'][$i]);
        if($label <> false)
        {
            array_push($_SESSION['m_admin']['template']['ENTITIES'], array('ID' => $_REQUEST['entities_chosen'][$i], 'LABEL' => $label));
        }
    }
    $_SESSION['service_tag'] = '';
}
elseif($_SESSION['service_tag'] == 'load_template_db')
{
    $db = new Database();
    $stmt = $db->query("Delete from ".$_SESSION['tablename']['temp_templates_association']." where template_id = ? and what = 'destination'",array($_SESSION['m_admin']['template']['ID']));

    for($i=0; $i < count($_SESSION['m_admin']['template']['ENTITIES']);$i++)
    {
        $stmt = $db->query("insert into ".$_SESSION['tablename']['temp_templates_association']." ( template_id, what, value_field, maarch_module  ) VALUES (  ? , 'destination', ?, 'entities')",array($_SESSION['m_admin']['template']['ID'],$_SESSION['m_admin']['template']['ENTITIES'][$i]['ID']));
    }
    $_SESSION['service_tag'] = '';
}

?>
