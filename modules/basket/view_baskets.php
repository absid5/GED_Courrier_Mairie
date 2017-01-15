<?php
/*
*    Copyright 2008-2015 Maarch
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

/*
* @brief  Access to the baskets
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

$_SESSION['count_view_baskets']++;

 $urlParameters = '';

    if(preg_match('/MSIE/i',$_SERVER["HTTP_USER_AGENT"]) && !preg_match('/Opera/i',$_SERVER["HTTP_USER_AGENT"])) { 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$_SERVER["HTTP_USER_AGENT"])) { 
        $ub = "Firefox"; 
    } else {
        $ub = "";
    }

if(($_SESSION['save_list']['fromDetail'] == "true" || $_SESSION['save_list']['fromValidateMail'] == "true" || $_SESSION['save_list']['fromProcess'] == "true") && ( ($_SESSION['count_view_baskets'] > 1 && $ub == "Firefox") || $ub != "Firefox" )) {
    if($_SESSION['save_list']['fromDetail'] == "true" || $_SESSION['save_list']['fromValidateMail'] == "true" || $_SESSION['save_list']['fromProcess'] == "true") {
        $urlParameters .= '&start='.$_SESSION['save_list']['start'];
        $urlParameters .= '&lines='.$_SESSION['save_list']['lines'];
        $urlParameters .= '&order='.$_SESSION['save_list']['order'];
        $urlParameters .= '&order_field='.$_SESSION['save_list']['order_field'];
        if ($_SESSION['save_list']['template'] <> "") {
            $urlParameters .= '&template='.$_SESSION['save_list']['template'];
        }
        $_SESSION['save_list']['fromDetail'] = "false";
        $_SESSION['save_list']['fromValidateMail'] = "false";
        $_SESSION['save_list']['fromProcess'] = "false";
        $_SESSION['save_list']['url'] = $urlParameters;
    }
    $_SESSION['save_list']['start'] = "";
    $_SESSION['save_list']['lines'] = "";
    $_SESSION['save_list']['order'] = "";
    $_SESSION['save_list']['order_field'] = "";
    $_SESSION['save_list']['template'] = "";  
}

$_SESSION['stockCheckbox']= '';

if (isset($_SESSION['search']['plain_text'])) {

    $_SESSION['search']['plain_text'] = "";

}
$_SESSION['FILE'] = array();
if (isset($_REQUEST['extension'])) {
    $_SESSION['origin'] = "scan";
    $_SESSION['FILE']['extension'] = $_REQUEST['extension'];
    $_SESSION['upfile']['size'] = $_REQUEST['taille_fichier'];
    $_SESSION['upfile']['mime'] = "application/pdf";
    $_SESSION['upfile']['local_path'] = "tmp/tmp_file_".$_REQUEST['md5'].".pdf";
    $_SESSION['upfile']['name'] = "tmp_file_".$_REQUEST['md5'].".pdf";
    $_SESSION['upfile']['md5'] = $_REQUEST['md5'];
    $_SESSION['upfile']['format'] = 'pdf';
} else {
    $_SESSION['origin'] = "";
    $_SESSION['upfile'] = array();
}
//file size
if (isset($_REQUEST['taille_fichier'])) {
    $_SESSION['FILE']['taille_fichier'] = $_REQUEST['taille_fichier'];
    $_SESSION['upfile']['size'] = $_REQUEST['taille_fichier'];
}
//file temporary path
if (isset($_REQUEST['Ftp_File'])) {
    $_SESSION['FILE']['Ftp_File'] = $_REQUEST['Ftp_File'];
}
//fingerprint of the file
if (isset($_REQUEST['md5'])) {
    $_SESSION['FILE']['md5'] = $_REQUEST['md5'];
}
//scan user
if (isset($_REQUEST['scan_user'])) {
    $_SESSION['FILE']['scan_user'] = $_REQUEST['scan_user'];
}
//scan workstation
if (isset($_REQUEST['scan_wkstation'])) {
    $_SESSION['FILE']['scan_wkstation'] = $_REQUEST['scan_wkstation'];
}
if (isset($_REQUEST['tmp_file'])) {
    $_SESSION['FILE']['tmp_file'] = $_REQUEST['tmp_file'];
}
//print_r($_SESSION['FILE']);
//print_r($_SESSION['upfile']);exit;
$_SESSION['category_id'] = '';
require_once 'core/class/class_request.php';
require_once 'core/class/class_db.php';
$core = new core_tools();
$core->test_user();
$core->load_lang();
$core->test_service('view_baskets', "basket");
if (! isset($_REQUEST['noinit'])) {
    $_SESSION['current_basket'] = array();
}
require_once "modules/basket/class/class_modules_tools.php";
/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if (isset($_REQUEST['level'])
    && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3
        || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$pagePath = $_SESSION['config']['businessappurl']
    . 'index.php?page=view_baskets&module=basket&baskets='.$_REQUEST['baskets'];
$pageLabel = _MY_BASKETS;
$pageId = "my_baskets";
$core->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/
$bask = new basket();
//$bask->load_basket();

if (isset($_REQUEST['baskets']) && ! empty($_REQUEST['baskets'])) {
    //$_SESSION['tmpbasket']['service'] = $_SESSION['user']['services'][0]['ID'];
    $_SESSION['tmpbasket']['status'] = "all";
    $bask->load_current_basket(trim($_REQUEST['baskets']));
    //$bask->show_array($_SESSION['current_basket']);
}

if ((isset($_REQUEST['id']) && !empty($_REQUEST['id'])) && !isset($_REQUEST['resid'])) {
	$_REQUEST['resid'] = $_REQUEST['id'];
}

if (
    isset($_REQUEST['directLinkToAction']) 
    && isset($_REQUEST['resid']) && !empty($_REQUEST['resid'])
) {
    //var_dump($_SESSION['user']['baskets']);exit;
    $foundBasketInUserSession = false;
    for (
        $ind_bask = 0;
        $ind_bask < count($_SESSION['user']['baskets']);
        $ind_bask++
    ) {
        if (
            $_SESSION['user']['baskets'][$ind_bask]['id'] == $_REQUEST['baskets']
        ) {
            if(
                isset($_SESSION['user']['baskets'][$ind_bask]['clause']) 
                && trim($_SESSION['user']['baskets'][$ind_bask]['clause']
            ) <> '') {
                $basketQuery = '(' 
                    . $_SESSION['user']['baskets'][$ind_bask]['clause'] 
                    . ')';
                $query = "select res_id from " 
                    . $_SESSION['user']['baskets'][$ind_bask]['view']
                    . " where (" . $basketQuery . ") and res_id = ?";
                
                $db = new Database();
                $stmt = $db->query($query,array($_REQUEST['resid']));
                if ($stmt->rowCount() < 1) {
                    //return false;
                } else {
                    $foundBasketInUserSession = true;
                }
            }
            break;
         }
    }
    if ($foundBasketInUserSession) {
        echo '<script language="javascript">';
        echo 'action_send_first_request(\'' 
            . $_SESSION['config']['businessappurl'] 
            . 'index.php?display=true&page=manage_action&module=core\''
            . ', \'mass\''
            . ',' . $_SESSION['current_basket']['default_action'] 
            . ',' . $_REQUEST['resid'] 
            . ',\'' . $_SESSION['current_basket']['table'] . '\''
            . ',\'basket\'' 
            . ',\'' . $_SESSION['current_basket']['coll_id'] . '\');';
        echo '</script>';
    } else {
        
    }
}
?><h1> <?php
if (count($_SESSION['user']['baskets']) > 0) {
    ?><div style="">
        <form name="select_basket" method="get" id="select_basket" action="<?php
    echo $_SESSION['config']['businessappurl'];
    ?>index.php">
        <i class ="fa fa-inbox fa-2x" title="" /></i> <?php echo _VIEW_BASKETS_TITLE;?> :
            <input type="hidden" name="page" id="page" value="view_baskets" />
            <input type="hidden" name="module" id="module" value="basket" />

            <select name="baskets"id="baskets" onchange="cleanSessionBasket('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=basket&page=cleanSessionBasket','ok'); this.form.submit();" class="listext_big" >
                <option value=""><?php echo _CHOOSE_BASKET;?></option>
                <?php
    for ($i = 0; $i < count($_SESSION['user']['baskets']); $i ++) {
        if($_SESSION['user']['baskets'][$i]['is_visible'] === 'Y') {
        ?>
        <option value="<?php
        if (isset($_SESSION['user']['baskets'][$i]['id'])) {
            functions::xecho($_SESSION['user']['baskets'][$i]['id']);
        }
        ?>" <?php
        if (isset($_SESSION['current_basket']['id'])
            && $_SESSION['current_basket']['id'] == $_SESSION['user']['baskets'][$i]['id']
        ) {
            echo 'selected="selected"';
        }
        ?>>
        <?php functions::xecho($_SESSION['user']['baskets'][$i]['name']);?>
        </option>
        <?php
        } 
    }
                ?>
            </select>
        </form>
        <?php
           if ($_REQUEST['origin'] == 'searching') {
                $urlParameters .= '&origin=searching'
            ?>
            <script type="text/javascript">
                var form_txt='<form name="frm_save_query" id="frm_save_query" action="#" method="post" class="forms addforms" onsubmit="send_request(this.id);" ><h2><?php echo _SAVE_QUERY_TITLE;?></h2><p><label for="query_name"><?php echo _QUERY_NAME;?></label><input type="text" name="query_name" id="query_name" value=""/></p><br/><p class="buttons"><input type="submit" name="submit" id="submit" value="<?php echo _VALIDATE;?>" class="button"/> <input type="button" name="cancel" id="cancel" value="<?php echo _CANCEL;?>" class="button" onclick="destroyModal(\'save_search\');"/></p></form>';

                function send_request(form_id)
                {
                    var form = $(form_id);
                    if(form)
                    {
                        var q_name = form.query_name.value;
                        $('modal').innerHTML = '<i class="fa fa-spinner fa-2x"></i>';

                        new Ajax.Request('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&dir=indexing_searching&page=manage_query',
                        {
                            method:'post',
                            parameters: {name: q_name,
                                        action : "creation"},
                            onSuccess: function(answer){
                                eval("response = "+answer.responseText)
                                if(response.status == 0)
                                {
                                    $('modal').innerHTML ='<h2><?php echo _QUERY_SAVED;?></h2><br/><input type="button" name="close" value="<?php echo _CLOSE_WINDOW;?>" onclick="destroyModal(\'save_search\');" class="button" />';
                                }
                                else if(response.status == 2)
                                {
                                    $('modal').innerHTML = '<div class="error"><?php echo _SQL_ERROR;?></div>'+form_txt;
                                    form.query_name.value = this.name;
                                }
                                else if(response.status == 3)
                                {
                                    $('modal').innerHTML = '<div class="error"><?php echo _QUERY_NAME.' '._IS_EMPTY;?></div>'+form_txt;
                                    form.query_name.value = this.name;
                                }
                                else
                                {
                                    $('modal').innerHTML = '<div class="error"><?php echo _SERVER_ERROR;?></div>'+form_txt;
                                    form.query_name.value = this.name;
                                }
                            },
                            onFailure: function(){
                                $('modal').innerHTML = '<div class="error"><?php echo _SERVER_ERROR;?></div>'+form_txt;
                                form.query_name.value = this.name;
                               }
                        });
                    }
                }
                </script>
            <?php
        }
        ?>
    </div>
    <?php
    /*
    if ($_REQUEST['origin'] == 'searching') {
        ?>
        <input type="button" onclick="createModal(form_txt);window.location.href='#top';" value="<?php echo _SAVE_QUERY;?>" class="button"/>
        <?php
    }
    */
} else {
    ?>
    <i class="fa fa-inbox fa-2x" title="" /></i> <?php echo _VIEW_BASKETS_TITLE;
}
?>
</h1>
<div id="inner_content" class="clearfix">
<?php

if(isset($_SESSION['info_basket'])) {
    ?>
    <div class="infoBasket" id="mainInfoBasket" onclick="this.hide();">
        <?php
        functions::xecho($_SESSION['info_basket']);
        ?>
    </div>
    <?php
}

if(isset($_SESSION['info_basket']) && $_SESSION['info_basket'] <> '') {
    ?>
    <script>
        var main_info = $('mainInfoBasket');
        if (main_info != null) {
            main_info.style.display = 'table-cell';
            Element.hide.delay(10, 'mainInfoBasket');
        }
    </script>
    <?php
}

if (count($_SESSION['user']['baskets']) == 0) {
    ?><div align="center"><?php echo _NO_BASKET_DEFINED_FOR_YOU;?></div><?php
}
if (isset($_SESSION['current_basket']['page_include'])
    && ! empty($_SESSION['current_basket']['page_include'])
) {
    //$bask->show_array($_SESSION['current_basket']);
    // include($_SESSION['current_basket']['page_include']);
    $redirect_to_action = $bask->is_redirect_to_action_basket(
        $_SESSION['current_basket']['id'],  
        $_SESSION['current_basket']['group_id'] 
    );
    
    if ($redirect_to_action) {
        // Redirect to action
        include($_SESSION['current_basket']['page_include']);
    } else {
        //show list
        require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
                ."class".DIRECTORY_SEPARATOR."class_lists.php";
        $list = new lists();
        $listContent = $list->loadList($_SESSION['current_basket']['page_no_frame'].$urlParameters);
        echo $listContent;
    }
} else {
    if (count($_SESSION['user']['baskets']) > 0) {
        $core->execute_modules_services(
            $_SESSION['modules_services'], 'view_basket', "include"
        );
        echo '<p style="border:0px solid;padding-left:250px;"></p>';
        ?>
        <div align="left" style="width:500px;"><?php
            echo "<p align = 'justify'>
            </p>";
        ?>
        </div><?php
    }
}

if ($_SESSION['cpt_info_basket'] > 0) {
    $_SESSION['info_basket'] = '';    
} else {
    $_SESSION['cpt_info_basket']++;
}

?></div>
