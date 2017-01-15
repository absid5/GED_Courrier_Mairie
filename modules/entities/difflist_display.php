<?php
# Display list
/* Requires 
    $difflist = diffusion list array 
        $_SESSION[m_admin]['entities'][listmodel]
        $_SESSION[$origin][listinstance]
    $roles = list of available roles
    $onlyCC =  hide 'dest';
*/
    echo '<div style="max-height:490px;overflow:auto;">';
    $empty = 0;
    $nb_roles = 0;
    if($origin == 'indexing' && $category == 'outgoing'){
        //$difflist['copy'] = array();
    }

    foreach($roles as $role_id => $role_label) {
        $nb_role++;
        if($category == 'outgoing' && $role_label == 'Destinataire'){
            $role_label = _SHIPPER;
        }
        if(($specific_role != $role_id && $specific_role.'_copy' != $role_id && $specific_role.'_info' != $role_id) && isset($specific_role) && $specific_role <> '') 
            continue;
        if(count($difflist[$role_id]['users']) > 0
            || count($difflist[$role_id]['entities']) > 0
        ) { 
            $empty++;
            ?>
            <h3 class="sstit" style="font-size: 1.2em;"><?php functions::xecho($role_label);?></h3><?php
        if(count($difflist[$role_id]['users']) > 0) { ?>
            <table cellpadding="0" cellspacing="0" border="0" class="listingsmall liste_diff spec" style="width:100%;margin:0;"><?php
            $color = ' class="col"';
            for($i=0, $l=count($difflist[$role_id]['users']);
                $i<$l;
                $i++
            ) {
                $user = $difflist[$role_id]['users'][$i];
                
                if ($color == ' class="col"') $color = ' ';
                else $color = ' class="col"';?>
                <tr <?php echo $color;?> >
                    <td style="width:15%;text-align:center;">
                        <i class="fa fa-user fa-2x" title="<?php echo _USER;?>"></i>
                    </td>
                    <td style="width:10%;"><?php
                    if($user['visible'] == 'Y') { ?>
                        <i class="fa fa-check fa-2x" title="<?php echo _VISIBLE;?>"></i> <?php
                    } else {?>
                        <i class="fa fa-times fa-2x" title="<?php echo _NOT_VISIBLE;?>"></i><?php
                    } ?>
                    </td>
                    <td style="width:37%;"><?php functions::xecho($user['lastname'] . " " . $user['firstname']);?></td>
                    <td style="width:38%;"><?php functions::xecho($user['entity_label']);?></td>
                </tr><?php
            } ?>
            </table><?php
        } 
        if(count($difflist[$role_id]['entities']) > 0) { ?>
            <table cellpadding="0" cellspacing="0" border="0" class="listingsmall liste_diff spec" style="width:100%;margin:0;"><?php
            $color = ' class="col"';
            for ($i=0, $l=count($difflist[$role_id]['entities']);
                $i<$l;
                $i++
            ) {
                $entity = $difflist[$role_id]['entities'][$i];
                if ($color == ' class="col"') $color = '';
                else $color = ' class="col"';?>
                <tr <?php echo $color;?> >
                    <td style="width:15%;text-align:center;">
                        <i class="fa fa-sitemap fa-2x" title="<?php echo _ENTITY . " " . $role_label ;?>" ></i>
                    </td>
                    <td style="width:10%;"><?php
                    if($entity['visible'] == 'Y') { ?>
                        <i class="fa fa-check fa-2x" title="<?php echo _VISIBLE;?>"></i><?php
                    } else {?>
                        <i class="fa fa-times fa-2x" title="<?php echo _NOT_VISIBLE;?>"></i><?php
                    } ?>
                    </td>
                    <td style="width:37%;"><?php functions::xecho($entity['entity_id']);?></td>
                    <td style="width:38%;"><?php functions::xecho($entity['entity_label']);?></td>
                </tr> <?php
            } ?>
            </table><?php
        } ?>
        <br/><?php
        }
    }

    if($empty == $nb_roles){
       echo '<div style="font-style:italic;text-align:center;color:#ea0000;margin:10px;">'._DIFF_LIST.' '._IS_EMPTY.'</div>';
    } 
echo '</div>';
?>
