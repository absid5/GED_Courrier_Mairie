<?php
/**
* File : print_sep_mlb_form.php
*
* script to print standard separator
*
* @package  Maarch FrameWork 3.0
* @version 2.1
* @since 10/2005
* @license GPL
* @author Yves Christian KPAKPO<dev@maarch.org>
* @author Laurent Giovannoni<dev@maarch.org>
*/


require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_functions.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_db.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_core_tools.php");
$func = new functions();

$db = new Database();
$core_tools = new core_tools();
/****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
{
    $init = true;
}
$level = "";
if(isset($_REQUEST['level']) && $_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)
{
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=print_sep_mlb_form&module=entities';
$page_label = _PRINT_SEPS;
$page_id = "print_sep_mlb_form";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
$_SESSION['entities'] = array();
$stmt = $db->query("select entity_id, entity_label from ".$_SESSION['tablename']['ent_entities']." where ENABLED = 'Y' order by entity_label");
while($line = $stmt->fetchObject())
{
    array_push($_SESSION['entities'], array("ID"=>$line->entity_id, "LABEL" => $func->show_str($line->entity_label)));
}
$_SESSION['separator']['entities'] = array();
?>
<h1><i class='fa fa-print fa-2x'></i> <?php echo _PRINT_SEPS;?></h1>
<div id="inner_content">
    <div class="block">
    <?php $link = $_SESSION['config']['businessappurl']."index.php?display=true&module=entities&page=print_sep_mlb&try=".(uniqid(md5(rand()), true));?>
    <form name="print_sep" id="print_sep" action="<?php functions::xecho($link);?>" target="sepwin" method="post" onSubmit="MM_openBrWindow('', 'sepwin', 'scrollbars=yes,menubar=no,toolbar=no,resizable=yes,width=330,height=500');">
        <table align="center" border="0" cellpadding="5">
            <tr>
                <td colspan="3">
                    <?php echo _CHOOSE_ENTITIES;?> :
                </td>
            </tr>
            <tr>
                <td>
                    <!--<label class="bold"><?php echo _ENTITIES_LIST;?> :</label><br/><br/>-->
                    <select name="entitieslist[]" id="entitieslist" size="20" multiple="multiple" >
                        <?php for($i=0;$i<count($_SESSION['entities']);$i++)
                        {
                            $state_entities = false;

                            for($j=0;$j<count($_SESSION['separator']['entities']);$j++)
                            {
                                if($_SESSION['entities'][$i]['ID'] == $_SESSION['separator']['entities'][$j]['ID'])
                                {
                                    $state_entities = true;
                                }
                            }

                            if($state_entities == false)
                            {
                                ?>
                                <option value="<?php functions::xecho($_SESSION['entities'][$i]['ID']);?>"><?php functions::xecho($func->show_str($_SESSION['entities'][$i]['LABEL']));?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <em><a href='javascript:selectall(document.forms["print_sep"].elements["entitieslist[]"]);' ><?php echo _SELECT_ALL;?></a></em></td>
                </td>
            </tr>
        </table>
        <br/>
        <div align="center">
            <?php echo _SELECT_BARCODE_TYPE;?> :
            <select name="typeBarcode" id="typeBarcode">
                <!--<option value="C39">C39</option>-->
                <option value="C128">C128</option>
                <option value="QRCODE">QRCODE</option>
            </select>
            <br/>
            <input class="button" name="print_generic" type="submit" value="<?php echo _ENTITIES_PRINT_SEP_MLB_GENERIC;?>" />
            <br><br>
            <input class="button" name="submit" type="submit" value="<?php echo _PRINT_SEPS_BUTTON;?>" />
            <input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" onClick="javascript:window.location.href='index.php';" />
        </div>
    </form>
    </div>
</div>
