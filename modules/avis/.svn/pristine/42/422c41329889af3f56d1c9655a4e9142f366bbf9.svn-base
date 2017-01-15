<?php
/*
*   Copyright 2013-2016 Maarch
*
*   This file is part of Maarch Framework.
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief  Contains the controler of the note Object
*
*
* @file
* @author Alex Orluc <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup avis
*/

/**
* @brief  Controler of the note Object
*
* @ingroup notes
*/
abstract class avis_controler_Abstract
{
    public function getAvis($resId)
    {
        //define avis limit date
        $db = new Database();
        
        $query = "SELECT notes.user_id,notes.note_text,recommendation_limit_date FROM notes,mlb_coll_ext WHERE identifier = ? AND note_text LIKE '[POUR AVIS]%' AND notes.identifier=mlb_coll_ext.res_id";
                
        $stmt = $db->query($query, array($resId));

        $avis = $stmt->fetchObject();

        return $avis;
              
    }
    #####################################
    ## send avis
    #####################################
    public function processAvis($resId, $recommendation_limit_date='')
    {
        //define avis limit date
        $db = new Database();
        
        if($recommendation_limit_date <> ''){
        
            $query = "UPDATE mlb_coll_ext SET recommendation_limit_date = ? where res_id = ?";
            $stmt = $db->query($query, array($recommendation_limit_date,$resId));
        }

        $query = "UPDATE res_letterbox SET modification_date = CURRENT_DATE where res_id = ?";        
        $stmt = $db->query($query, array($resId));
              
    }

    public function getList($res_id, $coll_id, $bool_modif=false, $typeList, $isAvisStep = false, $fromDetail = ""){
        require_once 'modules/entities/class/class_manage_listdiff.php';
        $core_tools =new core_tools();

        $id_tab="tab_avisSetWorkflow";
        $id_form="form_avisSetWorkflow";
        
        if ($fromDetail == "Y" && !$core_tools->test_service('config_avis_workflow_in_detail', 'avis', false)) {
            $bool_modif = false;
        }else if($fromDetail == "" && !$core_tools->test_service('config_avis_workflow', 'avis', false)){
            $bool_modif = false;
        }
        $circuitAvis = $this->getWorkflow($res_id, $coll_id, $typeList);

        $model = new diffusion_list();
        $modelListDiff = $model->get_listmodel('AVIS_CIRCUIT',$_SESSION['destination_entity']);

        // LOAD MODEL if exist
        if(empty($circuitAvis['avis']['users']) && !empty($modelListDiff)){
            $circuitAvis = $modelListDiff;
        }

        $str = "";

        if (!isset($circuitAvis['avis']['users']) && $fromDetail == "Y" && !isset($circuitAvis['lastAvis']['users']) && (!$core_tools->test_service('config_avis_workflow_in_detail', 'avis', false))){
            $str .= "<div class='error' id='divErrorAvis' name='divErrorAvis' onclick='this.hide();'>" . _EMPTY_USER_LIST . "</div>";
            $str .= "<div><strong><em>" . _EMPTY_AVIS_WORKFLOW . "</em></strong></div>";
        }else if(!isset($circuitAvis['avis']['users']) && $fromDetail == "" && !isset($circuitAvis['lastAvis']['users']) && (!$core_tools->test_service('config_avis_workflow', 'avis', false))){
            $str .= "<div class='error' id='divErrorAvis' name='divErrorAvis' onclick='this.hide();'>" . _EMPTY_USER_LIST . "</div>";
            $str .= "<div><strong><em>" . _EMPTY_AVIS_WORKFLOW . "</em></strong></div>";

        }else {
            $diff_list = new diffusion_list();
            $listModels = $diff_list->select_listmodels($typeList);

            //$listModels = $modelListDiff;

            $str .= '<div align="center">';
        
            $str .= '<div class="error" id="divErrorAvis" onclick="this.hide();"></div>';
            $str .= '<div class="info" id="divInfoAvis" onclick="this.hide();"></div>';

            if (!empty($listModels) && $bool_modif && !$isAvisStep){
                $str .= '<select name="modelList" id="modelList" onchange="load_listmodel_avis(this.options[this.selectedIndex], \''.$typeList.'\', \''.$id_tab.'\');">';
                $str .= '<option value="">Sélectionnez un modèle</option>';
                foreach($listModels as $lm){
                    $str .= '<option value="'.$lm['object_id'].'">'.$lm['title'].'</option>';
                }
                $str .= '</select>';
            }

            $str .= '<table class="listing spec detailtabricatordebug" style="width:100%;" cellspacing="0" border="0" id="'.$id_tab.'">';
            $str .= '<thead><tr>';
            $str .= '<th style="width:40%;" align="left" valign="bottom"><span>Avis</span></th>';
            if ($bool_modif){
                $str .= '<th style="width:5%;"></th>';
                $str .= '<th style="width:5%;"></th>';
                $str .= '<th style="width:5%;"></th>';
                $str .= '<th style="width:5%;"></th>';
                $str .= '<th style="width:45%;" align="left" valign="bottom"><span>Consigne</span></th>';
                $str .= '<th style="width:0;display:none" align="left" valign="bottom"></th>';
                $str .= '<th style="width:0;display:none" align="center" valign="bottom"></th>';
            }
            else {
                $str .= '<th style="width:55%;" align="left" valign="bottom"><span>Consigne</span></th>';
                $str .= '<th style="width:10%;" align="left" valign="bottom"><span>Etat</span></th>';
            }
            $str .= '</tr></thead>';
            $str .= '<tbody>';
            $color = "";
        
            if ($typeList == 'AVIS_CIRCUIT'){
                if (!isset($circuitAvis['avis']['users']) && !isset($circuitAvis['lastAvis']['users'])){
                    $j=0;
                    $str .= '<tr class="col" id="lineAvisWorkflow_'.$j.'">';
                    $str .= '<td>';
                    $str .= '<span id="avis_rank_' . $j . '"><span class="nbResZero" style="font-weight:bold;opacity:0.5;">1</span> </span>';
                    if ($bool_modif){
                        $str .= '<select id="avis_'.$j.'" name="avis_'.$j.'" >';
                        $str .= '<option value="" >Sélectionnez un utilisateur</option>';

                        $tab_userentities = $this->getEntityAvis();

                        /** Order by parent entity **/
                        foreach ($tab_userentities as $key => $value) {
                            $str .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
                            $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                            foreach($tab_users as $user){
                                if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
                                    $str .= '<option value="'.$user['id'].'" >'.$user['lastname'].', '.$user['firstname'].'</option>';
                                }
                                
                            }
                            $str .= '</optgroup>';
                        }
                        /*$tab_usergroups = $this->getGroupAvis();
                        foreach ($tab_usergroups as $key => $value) {
                            $str .= '<optgroup label="'.$tab_usergroups[$key]['group_desc'].'">';
                            $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                            foreach($tab_users as $user){
                                $str .= '<option value="'.$user['id'].'" >'.$user['lastname'].', '.$user['firstname'].'</option>';
                            }
                        }
                        $str .= '</optgroup>';*/
                        $str .= '</select>';
                    }
                    $str .= '<span id="lastavis_' . $j . '"> ';
                    //$str .= '<i title="'._LAST_AVIS.'" style="color : #fdd16c" class="fa fa-certificate fa-lg fa-fw"></i>';
                    $str .= '</span></td>';
                    $str .= '<td><a href="javascript://" id="avis_down_'.$j.'" name="avis_down_'.$j.'" style="visibility:hidden;" onclick="deplacerLigneAvis(0,1,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x" title="'.DOWN_USER_WORKFLOW.'"></i></a></td>';
                    $str .= '<td><a href="javascript://" id="avis_up_'.$j.'" name="avis_up_'.$j.'" style="visibility:hidden;" ><i class="fa fa-arrow-up fa-2x" title="'.UP_USER_WORKFLOW.'"></i></a></td>';
                    $str .= '<td><a href="javascript://" onclick="delRowAvis(this.parentNode.parentNode.rowIndex,\''.$id_tab.'\')" id="avis_suppr_'.$j.'" name="avis_suppr_'.$j.'" style="visibility:hidden;" ><i class="fa fa-user-times fa-2x" title="'.DEL_USER_WORKFLOW.'"></i></a></td>';
                    $str .= '<td><a href="javascript://" style="visibility:visible;"  id="avis_add_'.$j.'" name="avis_add_'.$j.'" onclick="addRowAvis(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x" title="'.ADD_USER_WORKFLOW.'"></i></a></td>';
                    $str .= '<td><input type="text" id="avis_consigne_'.$j.'" name="avis_consigne_'.$j.'" onmouseover="setTitle(this);" style="width:95%;"/></td>';
                    $str .= '<td style="display:none"><input type="hidden" id="avis_date_'.$j.'" name="avis_date_'.$j.'"/></td>';

                    $str .= '<td style="display:none"><input type="checkbox" id="avis_isSign_'.$j.'" name="avis_isSign_'.$j.'" style="visibility:hidden;" /></td>';
                    $str .= '<td><i class="fa fa-plus fa-lg" title="Nouvel utilisateur ajouté"></i></td>';
                    $str .= '</tr>';
                }
                else{
                    //var_dump($myPosAvis);
                    $isAvisStep = true;
                    if ($isAvisStep)
                        $myPosAvis = $this->myPosAvis($res_id, $coll_id, $typeList);
                    if (isset($circuitAvis['avis']['users'])){
                        foreach($circuitAvis['avis']['users'] as $seq=>$step){
                            if($color == ' class="col"') {
                                $color = '';
                            } else {
                                $color = ' class="col"';
                            }

                            if (($isAvisStep && !is_null($myPosAvis) && $myPosAvis >= $seq || $step['process_date'] != '') && $circuitAvis['avis']['users'][$seq]['user_id'] == $_SESSION['user']['UserId'])
                                    $title = ' Vous ne pouvez pas modifier votre propre étape ';
                                else
                                    $title = '';

                            $str .= '<tr ' . $color . ' title="'.$title.'">';

                            if ($bool_modif){
                                $str .= '<td>';
                                $tab_users = $this->getUsersAvis();

                                if ($isAvisStep && !is_null($myPosAvis) && $myPosAvis >= $seq || $step['process_date'] != '' /*&& $circuitAvis['avis']['users'][$seq]['user_id'] == $_SESSION['user']['UserId']*/)
                                    $disabled = ' disabled ';
                                else
                                    $disabled = '';
                                $str .= '<span id="avis_rank_' . $seq . '"><span class="nbResZero" style="font-weight:bold;opacity:0.5;">'. ($seq + 1) . '</span> </span>';
                                $str .= '<select id="avis_'.$seq.'" name="avis_'.$seq.'" '.$disabled.'>';
                                $str .= '<option value="" >Sélectionnez un utilisateur</option>';
        

                                /** Order by parent entity **/
                                $tab_userentities = $this->getEntityAvis();
                                foreach ($tab_userentities as $key => $value) {
                                    $str .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
                                    $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                                    foreach($tab_users as $user){
                                        if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
                                            $selected = " ";
                                            if ($user['id'] == $step['user_id'])
                                                $selected = " selected";
                                            $str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
                                        }
                                        
                                    }
                                    $str .= '</optgroup>';
                                }
                                /*$tab_usergroups = $this->getGroupAvis();
                                foreach ($tab_usergroups as $key => $value) {
                                    $str .= '<optgroup label="'.$tab_usergroups[$key]['group_desc'].'">';
                                    $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                                    foreach($tab_users as $user){
                                        $selected = " ";
                                        if ($user['id'] == $step['user_id'])
                                            $selected = " selected";
                                        $str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';

                                    }
                                    $str .= '</optgroup>';
                                }*/
                                $str .= '</select>';

                                $str .= "<span id=\"lastAvis_" . $seq . "\">";
                                if (empty($circuitAvis['lastAvis']['users']) && $seq == count ($circuitAvis['avis']['users'])-1)
                                    $str .= " <i title=\""._LAST_AVIS."\" style=\"color : #fdd16c;visibility:hidden;\" class=\"fa fa-certificate fa-lg fa-fw\"></i>";
                                $str .= "</span></td>";
                                $up = ' style="visibility:visible"';
                                $displayCB = ' style="visibility:hidden"';
                                $checkCB = '';
                                if ($isAvisStep && !is_null($myPosAvis) && $myPosAvis >= $seq || $step['process_date'] != '')
                                    $down = ' style="visibility:hidden"';
                                else
                                    $down = ' style="visibility:visible"';
                                if ($isAvisStep && !is_null($myPosAvis) && $myPosAvis >= $seq || $step['process_date'] != '' || count($circuitAvis['avis']['users']) == '1')
                                    $del = ' style="visibility:hidden"';
                                else
                                    $del = ' style="visibility:visible"';
                                if (empty($circuitAvis['lastAvis']['users']) && $seq == count ($circuitAvis['avis']['users'])-1){
                                    $add = ' style="visibility:visible"';
                                    $down = ' style="visibility:hidden"';
                                    $displayCB = ' style="visibility:hidden"';
                                    $checkCB = ' checked';
                                }
                                else{
                                    $add = ' style="visibility:hidden"';
                                }
                                if ($isAvisStep && $myPosAvis >= $seq || $step['process_date'] != '')
                                    $displayCB = ' style="visibility:hidden"';

                                if ($seq == 0 || ($isAvisStep && $myPosAvis >= $seq) || $circuitAvis['avis']['users'][$seq-1]['process_date'] != ''){
                                    $up = ' style="visibility:hidden"';
                                }
                                $str .= '<td><a href="javascript://"  '.$down.' id="avis_down_'.$seq.'" name="avis_down_'.$seq.'" onclick="deplacerLigneAvis(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x" title="'.DOWN_USER_WORKFLOW.'"></i></a></td>';
                                $str .= '<td><a href="javascript://"   '.$up.' id="avis_up_'.$seq.'" name="avis_up_'.$seq.'" onclick="deplacerLigneAvis(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1,\''.$id_tab.'\')" ><i class="fa fa-arrow-up fa-2x" title="'.UP_USER_WORKFLOW.'"></i></a></td>';
                                $str .= '<td id="allez"><a href="javascript://" onclick="delRowAvis(this.parentNode.parentNode.rowIndex,\''.$id_tab.'\')" id="suppr_'.$seq.'" name="suppr_'.$seq.'" '.$del.' ><i class="fa fa-user-times fa-2x" title="'.DEL_USER_WORKFLOW.'"></i></a></td>';
                                $str .= '<td><a href="javascript://" '.$add.'  id="avis_add_'.$seq.'" name="avis_add_'.$seq.'" onclick="addRowAvis(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x" title="'.ADD_USER_WORKFLOW.'"></i></a></td>';
                                $str .= '<td><input type="text" id="avis_consigne_'.$seq.'" name="avis_consigne_'.$seq.'" value="'.$step['process_comment'].'" onmouseover="setTitle(this);" style="width:95%;" '.$disabled.'/></td>';
                                $str .= '<td style="display:none"><input type="hidden" value="'.$step['process_date'].'" id="avis_date_'.$seq.'" name="avis_date_'.$seq.'"/></td>';


                                $str .= '<td style="display:none"><input type="checkbox" id="avis_isSign_'.$seq.'" name="avis_isSign_'.$seq.'" '.$displayCB.' '.$checkCB.'/></td>';
                                if ($step['process_date'] != '')
                                    $str .= '<td><i class="fa fa-check fa-2x" title="'._AVIS_SENT.'"></i></td>';
                                else
                                    $str .= '<td><i class="fa fa-hourglass-half fa-lg" title="'._WAITING_FOR_AVIS.'"></i></td>';

                            }
                            else{
                                $str .= '<td><span><strong>' . ($seq + 1) . ' </strong></span>'.$step['firstname'].' '.$step['lastname'];
                                $str .= '</td>';
                                $str .= '<td>'.$step['process_comment'].'</td>';
                                if ($step['process_date'] != '')
                                    $str .= '<td><i class="fa fa-check fa-2x" title="'._AVIS_SENT.'"></i></td>';
                                else
                                    $str .= '<td><i class="fa fa-hourglass-half fa-lg" title="'._WAITING_FOR_AVIS.'"></i></td>';
                                // else $str .= '<td></td>';
                            }
                            $str .= '</tr>';
                        }
                    }
                    //ajout signataire

                    if (!empty($circuitAvis['lastAvis']['users'])){
                        $seq = count ($circuitAvis['avis']['users']);
                        if($color == ' class="col"') {
                            $color = '';
                        } else {
                            $color = ' class="col"';
                        }

                        $str .= '<tr ' . $color . '>';
                        if ($bool_modif){
                            if ($isAvisStep && $myPosAvis >= $seq)
                                $disabled = ' disabled ';
                            else
                                $disabled = '';

                            $str .= '<td>';
                            $tab_users = $this->getUsersAvis();
                            $str .= '<span id="rank_' . $seq . '"><strong>'. ($seq + 1) . ' </strong></span>';
                            $str .= '<select id="avis_'.$seq.'" name="avis_'.$seq.'" '.$disabled.'>';
                            $str .= '<option value="" >Sélectionnez un utilisateur</option>';
                            
                            /** Order by parent entity **/
                            $tab_userentities = $this->getEntityAvis();
                            foreach ($tab_userentities as $key => $value) {
                                $str .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
                                $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                                foreach($tab_users as $user){
                                    if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
                                        $selected = " ";
                                        if ($user['id'] == $circuitAvis['lastAvis']['users'][0]['user_id'])
                                            $selected = " selected";
                                        $str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
                                    }
                                    
                                }
                                $str .= '</optgroup>';
                            }
                            /*$tab_usergroups = $this->getGroupAvis();
                            foreach ($tab_usergroups as $key => $value) {
                                $str .= '<optgroup label="'.$tab_usergroups[$key]['group_desc'].'">';
                                $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                                foreach($tab_users as $user){
                                    $selected = " ";
                                    if ($user['id'] == $circuitAvis['lastAvis']['users'][0]['user_id'])
                                        $selected = " selected";
                                    $str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
                                }
                                $str .= '</optgroup>';
                            }*/
                            $str .= '</select>';

                            $str .= '<span id="lastAvis_' . $seq . '"> <i title="Signataire" style="color : #fdd16c;visibility:hidden;" class="fa fa-certificate fa-lg fa-fw"></i></span></td>';
                            if ($isAvisStep && ($myPosAvis+1 == $seq || $myPosAvis == $seq))
                                $up = ' style="visibility:hidden"';
                            else
                                $up = ' style="visibility:visible"';
                            $down = ' style="visibility:hidden"';

                            // if ($isAvisStep && $myPosAvis == $seq) $add = ' style="visibility:hidden"';
                            // else $add = ' style="visibility:visible"';
                            $add = ' style="visibility:visible"';

                            if ($isAvisStep && $myPosAvis == $seq)
                                $del = ' style="visibility:hidden"';
                            else
                                $del = ' style="visibility:visible"';

                            if (count ($circuitAvis['avis']['users']) == 0){
                                $up     = 'style="visibility:hidden"';
                                $del    = 'style="visibility:hidden"';
                            }
                            $displayCB = ' style="visibility:hidden"';
                            if ($isAvisStep && $myPosAvis == $seq)
                                $displayCB = ' style="visibility:hidden"';

                            $str .= '<td><a href="javascript://"  '.$down.' id="avis_down_'.$seq.'" name="avis_down_'.$seq.'" onclick="deplacerLigneAvis(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x" title="'.DOWN_USER_WORKFLOW.'"></i></a></td>';
                            $str .= '<td><a href="javascript://"   '.$up.' id="avis_up_'.$seq.'" name="avis_up_'.$seq.'" onclick="deplacerLigneAvis(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1,\''.$id_tab.'\')" ><i class="fa fa-arrow-up fa-2x" title="'.UP_USER_WORKFLOW.'"></i></a></td>';
                            $str .= '<td><a href="javascript://" onclick="delRowAvis(this.parentNode.parentNode.rowIndex,\''.$id_tab.'\')" id="avis_suppr_'.$seq.'" name="avis_suppr_'.$seq.'" '.$del.' ><i class="fa fa-user-times fa-2x" title="'.DEL_USER_WORKFLOW.'"></i></a></td>';
                            $str .= '<td><a href="javascript://" '.$add.'  id="avis_add_'.$seq.'" name="avis_add_'.$seq.'" onclick="addRowAvis(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x" title="'.ADD_USER_WORKFLOW.'"></i></a></td>';
                            $str .= '<td><input type="text" id="avis_consigne_'.$seq.'" name="avis_consigne_'.$seq.'" value="'.$circuitAvis['lastAvis']['users'][0]['process_comment'].'" onmouseover="setTitle(this);" style="width:95%;" '.$disabled.'/></td>';
                            $str .= '<td style="display:none"><input type="hidden" id="avis_date_'.$seq.'" name="avis_date_'.$seq.'" value="'.$circuitAvis['lastAvis']['users'][0]['process_date'].'" /></td>';

                            $str .= '<td style="display:none"><input type="checkbox" id="avis_isSign_'.$seq.'" name="avis_isSign_'.$seq.'" '.$displayCB.' checked/></td>';
                        } else {
                            $str .= '<td><span><strong>' . ($seq + 1) . ' </strong></span>' . $circuitAvis['lastAvis']['users'][0]['firstname'].' '.$circuitAvis['lastAvis']['users'][0]['lastname'];
                            $str .= ' <i title="'._LAST_AVIS.'" style="color : #fdd16c;visibility:hidden;" class="fa fa-certificate fa-lg fa-fw"></i></td>';
                            $str .= '<td>'.$circuitAvis['lastAvis']['users'][0]['process_comment'].'</td>'; 
                            if ($circuitAvis['lastAvis']['users'][0]['process_date'] != '')
                                $str .= '<td><i class="fa fa-check fa-2x" title="'._SIGNED.'"></i></td>';
                            else
                                $str .= '<td><i class="fa fa-hourglass-half fa-lg" title="'._WAITING_FOR_SIGN.'"></i></td>';
                                
                        }
                        $str .= '</tr>';
                    }
                }
            }
        
            $str .= '</tbody>';
            $str .= '</table>';
            if ($bool_modif){
                $str .= '<input type="button" name="send" id="send" value="Sauvegarder" class="button" ';

                    if ($fromDetail == "Y") {
                        $str .= 'onclick="saveAvisWorkflow(\''.$res_id.'\', \''.$coll_id.'\', \''.$id_tab.'\', \'Y\');" /> ';
                    } else {
                        $str .= 'onclick="saveAvisWorkflow(\''.$res_id.'\', \''.$coll_id.'\', \''.$id_tab.'\', \'N\');" /> ';
                    }

                if ($fromDetail == "Y") {
                    $str .= '<input type="button" name="reset" id="reset" value="Reinitialiser" class="button" ';

                    $str .= 'onclick="if(confirm(\'Voulez-vous réinitialiser le circuit ?\')){resetAvisWorkflow(\''.$res_id.'\', \''.$coll_id.'\', \''.$id_tab.'\', \'Y\');}" /> ';
                }

                    $str .= '<input type="button" name="save" id="save" value="Enregistrer comme modèle" class="button" onclick="$(\'modalSaveAvisModel\').style.display = \'block\';" />';
                    $str .= '<div id="modalSaveAvisModel" >';
                    $str .= '<h3>Sauvegarder le circuit de avis</h3>';
                    $str .= '<input type="hidden" value="'.$typeList . '_' . strtoupper(base_convert(date('U'), 10, 36)).'" name="objectId_inputAvis" id="objectId_inputAvis"/><br/>';
                    $str .= '<label for="titleModel">Titre</label> ';
                    $str .= '<input type="text" name="titleModelAvis" id="titleModelAvis"/><br/>';
                    $str .= '<input type="button" name="saveModelAvis" id="saveModelAvis" value="'._VALIDATE.'" class="button" onclick="saveAvisModel(\''.$id_tab.'\');" /> ';
                    $str .= '<input type="button" name="cancelModelAvis" id="cancelModelAvis" value="'._CANCEL.'" class="button" onclick="$(\'modalSaveAvisModel\').style.display = \'none\';" />';
                    $str .= '</div>';
            }
            $str .= '</div>';
        }
        return $str;
    }

    public function getListPopup($res_id, $coll_id, $bool_modif=false, $typeList, $isAvisStep = false, $fromDetail = ""){
        require_once 'modules/entities/class/class_manage_listdiff.php';
        $core_tools =new core_tools();

        $id_tab="tab_avisSetWorkflowPopup";
        $id_form="form_avisSetWorkflowPopup";
        
                
        $circuitAvis = $this->getWorkflow($res_id, $coll_id, $typeList);

        $model = new diffusion_list();
        $modelListDiff = $model->get_listmodel('AVIS_CIRCUIT',$_SESSION['destination_entity']);

        // LOAD MODEL if exist
        if(empty($circuitAvis['avis']['users']) && !empty($modelListDiff)){
            $circuitAvis = $modelListDiff;
        }


        $str = "";
        if (!isset($circuitAvis['avis']['users']) && !isset($circuitAvis['lastAvis']['users']) && !$core_tools->test_service('config_avisPopup_workflow_in_detail', 'avis', false) && $fromDetail == "Y"){
            $str .= "<div class='error' id='divErrorAvisPopup' name='divErrorAvisPopup' onclick='this.hide();'>" . _EMPTY_USER_LIST . "</div>";
            $str .= "<div><strong><em>" . _EMPTY_AVIS_WORKFLOW . "</em></strong></div>";
        }
        else {
            require_once("modules/entities/class/class_manage_listdiff.php");
            $diff_list = new diffusion_list();
            $listModels = $diff_list->select_listmodels($typeList);

            $str .= '<div align="center">';
        
            $str .= '<div class="error" id="divErrorAvisPopup" onclick="this.hide();"></div>';
            $str .= '<div class="info" id="divInfoAvisPopup" onclick="this.hide();"></div>';

            if (!empty($listModels) && $bool_modif && !$isAvisStep){
                $str .= '<select name="modelListPopup" id="modelListPopup" onchange="load_listmodel_avisPopup(this.options[this.selectedIndex], \''.$typeList.'\', \''.$id_tab.'\');">';
                $str .= '<option value="">Sélectionnez un modèle</option>';
                foreach($listModels as $lm){
                    $str .= '<option value="'.$lm['object_id'].'">'.$lm['title'].'</option>';
                }
                $str .= '</select>';
            }

            $str .= '<table class="listing spec detailtabricatordebug" style="width:100%;" cellspacing="0" border="0" id="'.$id_tab.'">';
            $str .= '<thead><tr>';
            $str .= '<th style="width:40%;" align="left" valign="bottom"><span>Avis</span></th>';
            if ($bool_modif){
                $str .= '<th style="width:5%;"></th>';
                $str .= '<th style="width:5%;"></th>';
                $str .= '<th style="width:5%;"></th>';
                $str .= '<th style="width:5%;"></th>';
                $str .= '<th style="width:45%;" align="left" valign="bottom"><span>Consigne</span></th>';
                $str .= '<th style="width:0;display:none" align="left" valign="bottom"></th>';
                $str .= '<th style="width:0;display:none" align="center" valign="bottom"></th>';
            }
            else {
                $str .= '<th style="width:55%;" align="left" valign="bottom"><span>Consigne</span></th>';
                $str .= '<th style="width:10%;" align="left" valign="bottom"><span>Etat</span></th>';
            }
            $str .= '</tr></thead>';
            $str .= '<tbody>';
            $color = "";
        
            if ($typeList == 'AVIS_CIRCUIT'){
                if (!isset($circuitAvis['avis']['users']) && !isset($circuitAvis['lastAvis']['users'])){
                    $j=0;
                    $str .= '<tr class="col" id="lineAvisWorkflowPopup_'.$j.'">';
                    $str .= '<td>';
                    $str .= '<span id="avisPopup_rankPopup_' . $j . '"> <span class="nbResZero" style="font-weight:bold;opacity:0.5;">'. ($seq + 1) . '</span> </span>';
                    if ($bool_modif){
                        $str .= '<select style="width:150px;" id="avisPopup_'.$j.'" name="avisPopup_'.$j.'" >';
                        $str .= '<option value="" >Sélectionnez un utilisateur</option>';
                        
                        /** Order by parent entity **/
                        $tab_userentities = $this->getEntityAvis();
                        foreach ($tab_userentities as $key => $value) {
                            $str .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
                            $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                            foreach($tab_users as $user){
                                if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
                                    $str .= '<option value="'.$user['id'].'" >'.$user['lastname'].', '.$user['firstname'].'</option>';
                                }
                                
                            }
                            $str .= '</optgroup>';
                        }
                        /*$tab_usergroups = $this->getGroupAvis();
                        foreach ($tab_usergroups as $key => $value) {
                            $str .= '<optgroup label="'.$tab_usergroups[$key]['group_desc'].'">';
                            $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                            foreach($tab_users as $user){
                                $str .= '<option value="'.$user['id'].'" >'.$user['lastname'].', '.$user['firstname'].'</option>';
                            }
                        }
                        $str .= '</optgroup>';*/
                        $str .= '</select>';
                    }
                    $str .= '<span id="lastavisPopup_' . $j . '"> <i title="'._LAST_AVIS.'" style="color : #fdd16c;visibility:hidden;" class="fa fa-certificate fa-lg fa-fw"></i></span></td>';
                    $str .= '<td><a href="javascript://" id="avisPopup_down_'.$j.'" name="avisPopup_down_'.$j.'" style="visibility:hidden;" onclick="deplacerLigneAvisPopup(0,1,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x" title="'.DOWN_USER_WORKFLOW.'"></i></a></td>';
                    $str .= '<td><a href="javascript://" id="avisPopup_up_'.$j.'" name="avisPopup_up_'.$j.'" style="visibility:hidden;" ><i class="fa fa-arrow-up fa-2x" title="'.UP_USER_WORKFLOW.'"></i></a></td>';
                    $str .= '<td><a href="javascript://" onclick="delRowAvisPopup(this.parentNode.parentNode.rowIndex,\''.$id_tab.'\')" id="avisPopup_suppr_'.$j.'" name="avisPopup_suppr_'.$j.'" style="visibility:hidden;" ><i class="fa fa-user-times fa-2x" title="'.DEL_USER_WORKFLOW.'"></i></a></td>';
                    $str .= '<td><a href="javascript://" style="visibility:visible;"  id="avisPopup_add_'.$j.'" name="avisPopup_add_'.$j.'" onclick="addRowAvisPopup(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x" title="'.ADD_USER_WORKFLOW.'"></i></a></td>';
                    $str .= '<td><input type="text" id="avisPopup_consigne_'.$j.'" name="avisPopup_consigne_'.$j.'" onmouseover="setTitle(this);" style="width:95%;"/></td>';
                    $str .= '<td style="display:none"><input type="hidden" id="avisPopup_date_'.$j.'" name="avisPopup_date_'.$j.'"/></td>';

                    $str .= '<td style="display:none"><input type="checkbox" id="avisPopup_isSign_'.$j.'" name="avisPopup_isSign_'.$j.'" style="visibility:hidden;" /></td>';
                    $str .= '<td><i class="fa fa-plus fa-lg" title="Nouvel utilisateur ajouté"></i></td>';
                    $str .= '</tr>';
                }
                else{
                    if ($isAvisStep)
                        $myPosAvis = $this->myPosAvis($res_id, $coll_id, $typeList);
                    if (isset($circuitAvis['avis']['users'])){
                        foreach($circuitAvis['avis']['users'] as $seq=>$step){
                            if($color == ' class="col"') {
                                $color = '';
                            } else {
                                $color = ' class="col"';
                            }

                            $str .= '<tr ' . $color . '>';

                            if ($bool_modif){
                                $str .= '<td>';
                                $tab_users = $this->getUsersAvis();

                                if ($isAvisStep && $myPosAvis >= $seq || $step['process_date'] != '')
                                    $disabled = ' disabled ';
                                else
                                    $disabled = '';
                                $str .= '<span id="avisPopup_rankPopup_' . $seq . '"><span class="nbResZero" style="font-weight:bold;opacity:0.5;">'. ($seq + 1) . '</span> </span>';
                                $str .= '<select style="width:150px;" id="avisPopup_'.$seq.'" name="avisPopup_'.$seq.'" '.$disabled.'>';
                                $str .= '<option value="" >Sélectionnez un utilisateur</option>';
                                
                                /** Order by parent entity **/
                                $tab_userentities = $this->getEntityAvis();
                                foreach ($tab_userentities as $key => $value) {
                                    $str .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
                                    $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                                    foreach($tab_users as $user){
                                        if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
                                            $selected = " ";
                                            if ($user['id'] == $step['user_id']){
                                                $selected = " selected";
                                            }
                                            $str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
                                        }
                                        
                                    }
                                    $str .= '</optgroup>';
                                }
                                /*$tab_usergroups = $this->getGroupAvis();
                                foreach ($tab_usergroups as $key => $value) {
                                    $str .= '<optgroup label="'.$tab_usergroups[$key]['group_desc'].'">';
                                    $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                                    foreach($tab_users as $user){
                                        $selected = " ";
                                        if ($user['id'] == $step['user_id']){
                                            $selected = " selected";
                                        }
                                        $str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';

                                    }
                                    $str .= '</optgroup>';
                                }*/
                                $str .= '</select>';

                                $str .= "<span id=\"lastAvisPopup_" . $seq . "\">";
                                if (empty($circuitAvis['lastAvis']['users']) && $seq == count ($circuitAvis['avis']['users'])-1)
                                    $str .= " <i title=\""._LAST_AVIS."\" style=\"color : #fdd16c;visibility:hidden;\" class=\"fa fa-certificate fa-lg fa-fw\"></i>";
                                $str .= "</span></td>";
                                $up = ' style="visibility:visible"';
                                $displayCB = ' style="visibility:hidden"';
                                $checkCB = '';
                                if ($isAvisStep && $myPosAvis >= $seq || $step['process_date'] != '')
                                    $down = ' style="visibility:hidden"';
                                else
                                    $down = ' style="visibility:visible"';
                                if ($isAvisStep && $myPosAvis >= $seq || $step['process_date'] != '' || count($circuitAvis['avis']['users']) == '1')
                                    $del = ' style="visibility:hidden"';
                                else
                                    $del = ' style="visibility:visible"';
                                if (empty($circuitAvis['lastAvis']['users']) && $seq == count ($circuitAvis['avis']['users'])-1){
                                    $add = ' style="visibility:visible"';
                                    $down = ' style="visibility:hidden"';
                                    $displayCB = ' style="visibility:hidden"';
                                    $checkCB = ' checked';
                                }
                                else{
                                    $add = ' style="visibility:hidden"';
                                }
                                if ($isAvisStep && $myPosAvis >= $seq || $step['process_date'] != '')
                                    $displayCB = ' style="visibility:hidden"';

                                if ($seq == 0 || ($isAvisStep && $myPosAvis+1 >= $seq) || $circuitAvis['avis']['users'][$seq-1]['process_date'] != ''){
                                    $up = ' style="visibility:hidden"';
                                }
                                $str .= '<td><a href="javascript://"  '.$down.' id="avisPopup_down_'.$seq.'" name="avisPopup_down_'.$seq.'" onclick="deplacerLigneAvisPopup(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x" title="'.DOWN_USER_WORKFLOW.'"></i></a></td>';
                                $str .= '<td><a href="javascript://"   '.$up.' id="avisPopup_up_'.$seq.'" name="avisPopup_up_'.$seq.'" onclick="deplacerLigneAvisPopup(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1,\''.$id_tab.'\')" ><i class="fa fa-arrow-up fa-2x" title="'.UP_USER_WORKFLOW.'"></i></a></td>';
                                $str .= '<td id="allez"><a href="javascript://" onclick="delRowAvisPopup(this.parentNode.parentNode.rowIndex,\''.$id_tab.'\')" id="suppr_'.$seq.'" name="suppr_'.$seq.'" '.$del.' ><i class="fa fa-user-times fa-2x" title="'.DEL_USER_WORKFLOW.'"></i></a></td>';
                                $str .= '<td><a href="javascript://" '.$add.'  id="avisPopup_add_'.$seq.'" name="avisPopup_add_'.$seq.'" onclick="addRowAvisPopup(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x" title="'.ADD_USER_WORKFLOW.'"></i></a></td>';
                                $str .= '<td><input type="text" id="avisPopup_consigne_'.$seq.'" name="avisPopup_consigne_'.$seq.'" value="'.$step['process_comment'].'" onmouseover="setTitle(this);" style="width:95%;" '.$disabled.'/></td>';
                                $str .= '<td style="display:none"><input type="hidden" value="'.$step['process_date'].'" id="avisPopup_date_'.$seq.'" name="avisPopup_date_'.$seq.'"/></td>';


                                $str .= '<td style="display:none"><input type="checkbox" id="avisPopup_isSign_'.$seq.'" name="avisPopup_isSign_'.$seq.'" '.$displayCB.' '.$checkCB.'/></td>';
                                if ($step['process_date'] != '')
                                    $str .= '<td><i class="fa fa-check fa-2x" title="'._AVIS_SENT.'"></i></td>';
                                else
                                    $str .= '<td><i class="fa fa-hourglass-half fa-lg" title="'._WAITING_FOR_AVIS.'"></i></td>';

                            }
                            else{
                                $str .= '<td><span><strong>' . ($seq + 1) . ' </strong></span>'.$step['firstname'].' '.$step['lastname'];
                                $str .= '</td>';
                                $str .= '<td>'.$step['process_comment'].'</td>';
                                if ($step['process_date'] != '')
                                    $str .= '<td><i class="fa fa-check fa-2x" title="'._AVIS_SENT.'"></i></td>';
                                else
                                    $str .= '<td><i class="fa fa-hourglass-half fa-lg" title="'._WAITING_FOR_AVIS.'"></i></td>';
                                // else $str .= '<td></td>';
                            }
                            $str .= '</tr>';
                        }
                    }
                    //ajout signataire

                    if (!empty($circuitAvis['lastAvis']['users'])){
                        $seq = count ($circuitAvis['avis']['users']);
                        if($color == ' class="col"') {
                            $color = '';
                        } else {
                            $color = ' class="col"';
                        }

                        $str .= '<tr ' . $color . '>';
                        if ($bool_modif){
                            if ($isAvisStep && $myPosAvis >= $seq)
                                $disabled = ' disabled ';
                            else
                                $disabled = '';

                            $str .= '<td>';
                            $tab_users = $this->getUsersAvis();
                            $str .= '<span id="rank_' . $seq . '"><strong>'. ($seq + 1) . ' </strong></span>';
                            $str .= '<select id="avisPopup_'.$seq.'" name="avisPopup_'.$seq.'" '.$disabled.'>';
                            $str .= '<option value="" >Sélectionnez un utilisateur</option>';
                            
                            /** Order by parent entity **/
                            $tab_userentities = $this->getEntityAvis();
                            foreach ($tab_userentities as $key => $value) {
                                $str .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
                                $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                                foreach($tab_users as $user){
                                    if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
                                        $selected = " ";
                                        if ($user['id'] == $circuitAvis['lastAvis']['users'][0]['user_id'])
                                            $selected = " selected";
                                        $str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
                                    }
                                    
                                }
                                $str .= '</optgroup>';
                            }
                            /*$tab_usergroups = $this->getGroupAvis();
                            foreach ($tab_usergroups as $key => $value) {
                                $str .= '<optgroup label="'.$tab_usergroups[$key]['group_desc'].'">';
                                $tab_users = $this->getUsersAvis($tab_usergroups[$key]['group_id']);
                                foreach($tab_users as $user){
                                    $selected = " ";
                                    if ($user['id'] == $circuitAvis['lastAvis']['users'][0]['user_id'])
                                        $selected = " selected";
                                    $str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
                                }
                                $str .= '</optgroup>';
                            }*/
                            $str .= '</select>';

                            $str .= '<span id="lastAvisPopup_' . $seq . '"> <i title="Signataire" style="color : #fdd16c;visibility:hidden;" class="fa fa-certificate fa-lg fa-fw"></i></span></td>';
                            if ($isAvisStep && ($myPosAvis+1 == $seq || $myPosAvis == $seq))
                                $up = ' style="visibility:hidden"';
                            else
                                $up = ' style="visibility:visible"';
                            $down = ' style="visibility:hidden"';

                            // if ($isAvisStep && $myPosAvis == $seq) $add = ' style="visibility:hidden"';
                            // else $add = ' style="visibility:visible"';
                            $add = ' style="visibility:visible"';

                            if ($isAvisStep && $myPosAvis == $seq)
                                $del = ' style="visibility:hidden"';
                            else
                                $del = ' style="visibility:visible"';

                            if (count ($circuitAvis['avis']['users']) == 0){
                                $up     = 'style="visibility:hidden"';
                                $del    = 'style="visibility:hidden"';
                            }
                            $displayCB = ' style="visibility:hidden"';
                            if ($isAvisStep && $myPosAvis == $seq)
                                $displayCB = ' style="visibility:hidden"';

                            $str .= '<td><a href="javascript://"  '.$down.' id="avisPopup_down_'.$seq.'" name="avisPopup_down_'.$seq.'" onclick="deplacerLigneAvisPopup(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x" title="'.DOWN_USER_WORKFLOW.'"></i></a></td>';
                            $str .= '<td><a href="javascript://"   '.$up.' id="avisPopup_up_'.$seq.'" name="avisPopup_up_'.$seq.'" onclick="deplacerLigneAvisPopup(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1,\''.$id_tab.'\')" ><i class="fa fa-arrow-up fa-2x" title="'.UP_USER_WORKFLOW.'"></i></a></td>';
                            $str .= '<td><a href="javascript://" onclick="delRowAvisPopup(this.parentNode.parentNode.rowIndex,\''.$id_tab.'\')" id="avisPopup_suppr_'.$seq.'" name="avisPopup_suppr_'.$seq.'" '.$del.' ><i class="fa fa-user-times fa-2x" title="'.DEL_USER_WORKFLOW.'"></i></a></td>';
                            $str .= '<td><a href="javascript://" '.$add.'  id="avisPopup_add_'.$seq.'" name="avisPopup_add_'.$seq.'" onclick="addRowAvisPopup(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x" title="'.ADD_USER_WORKFLOW.'"></i></a></td>';
                            $str .= '<td><input type="text" id="avisPopup_consigne_'.$seq.'" name="avisPopup_consigne_'.$seq.'" value="'.$circuitAvis['lastAvis']['users'][0]['process_comment'].'" onmouseover="setTitle(this);" style="width:95%;" '.$disabled.'/></td>';
                            $str .= '<td style="display:none"><input type="hidden" id="avisPopup_date_'.$seq.'" name="avisPopup_date_'.$seq.'" value="'.$circuitAvis['lastAvis']['users'][0]['process_date'].'" /></td>';

                            $str .= '<td style="display:none"><input type="checkbox" id="avisPopup_isSign_'.$seq.'" name="avisPopup_isSign_'.$seq.'" '.$displayCB.' checked/></td>';
                        } else {
                            $str .= '<td><span><strong>' . ($seq + 1) . ' </strong></span>' . $circuitAvis['lastAvis']['users'][0]['firstname'].' '.$circuitAvis['lastAvis']['users'][0]['lastname'];
                            $str .= ' <i title="'._LAST_AVIS.'" style="color : #fdd16c;visibility:hidden;" class="fa fa-certificate fa-lg fa-fw"></i></td>';
                            $str .= '<td>'.$circuitAvis['lastAvis']['users'][0]['process_comment'].'</td>'; 
                            if ($circuitAvis['lastAvis']['users'][0]['process_date'] != '')
                                $str .= '<td><i class="fa fa-check fa-2x" title="'._SIGNED.'"></i></td>';
                            else
                                $str .= '<td><i class="fa fa-hourglass-half fa-lg" title="'._WAITING_FOR_SIGN.'"></i></td>';
                                
                        }
                        $str .= '</tr>';
                    }
                }
            }
        
            $str .= '</tbody>';
            $str .= '</table>';
            if ($bool_modif){

                    $str .= '<input type="button" name="save" id="save" value="Enregistrer comme modèle" class="button" onclick="$(\'modalSaveAvisModelPopup\').style.display = \'block\';" />';
                    $str .= '<div id="modalSaveAvisModelPopup" >';
                    $str .= '<h3>Sauvegarder le circuit de avis</h3>';
                    $str .= '<input type="hidden" value="'.$typeList . '_' . strtoupper(base_convert(date('U'), 10, 36)).'" name="objectId_inputAvisPopup" id="objectId_inputAvisPopup"/><br/>';
                    $str .= '<label for="titleModel">Titre</label> ';
                    $str .= '<input type="text" name="titleModelAvisPopup" id="titleModelAvisPopup"/><br/>';
                    $str .= '<input type="button" name="saveModelAvis" id="saveModelAvis" value="'._VALIDATE.'" class="button" onclick="saveAvisModelPopup(\''.$id_tab.'\');" /> ';
                    $str .= '<input type="button" name="cancelModelAvis" id="cancelModelAvis" value="'._CANCEL.'" class="button" onclick="$(\'modalSaveAvisModelPopup\').style.display = \'none\';" />';
                    $str .= '</div>';
            }
            $str .= '</div>';
        }
        return $str;
    }

    public function getWorkflow($res_id, $coll_id, $typeList){
        require_once('modules/entities/class/class_manage_listdiff.php');
        $listdiff = new diffusion_list();
        $roles = $listdiff->list_difflist_roles();
        $circuitAvis = $listdiff->get_listinstance($res_id, false, $coll_id, $typeList);
        if (isset($circuitAvis['copy'])) unset($circuitAvis['copy']);
        return $circuitAvis;
    }

    public function getEntityAvis(){
        $db = new Database();
        
        $stmt = $db->query("SELECT distinct(entities.entity_id) from users, usergroup_content, users_entities,entities WHERE users_entities.user_id = users.user_id and 
            users_entities.primary_entity = 'Y' and users.user_id = usergroup_content.user_id AND entities.entity_id = users_entities.entity_id AND group_id IN 
            (SELECT group_id FROM usergroups_services WHERE service_id = ?)  
            order by entities.entity_id", array('avis_documents'));
        
        $tab_userentities = array();
        
        
        while($res = $stmt->fetchObject()){
            array_push($tab_userentities,array('entity_id'=>$res->entity_id));
        }
        //print_r($tab_userentities);
        return $tab_userentities;
    }

    public function getGroupAvis(){
        $db = new Database();
        
        $stmt = $db->query("SELECT DISTINCT(usergroup_content.group_id),group_desc from usergroups, usergroup_content WHERE usergroups.group_id = usergroup_content.group_id AND usergroup_content.group_id IN (SELECT group_id FROM usergroups_services WHERE service_id = ?)", array('avis_documents'));
        
        $tab_usergroup = array();
        
        
        while($res = $stmt->fetchObject()){
            array_push($tab_usergroup,array('group_id'=>$res->group_id,'group_desc'=>$res->group_desc));
        }
        //print_r($tab_usergroup);
        return $tab_usergroup;
    }

    public function getUsersAvis($group_id = null){
        $db = new Database();
        
        if($group_id <> null){
            $stmt = $db->query("SELECT users.user_id, users.firstname, users.lastname, usergroup_content.group_id,entities.entity_id from users, usergroup_content, users_entities,entities WHERE users_entities.user_id = users.user_id and 
                users_entities.primary_entity = 'Y' and users.user_id = usergroup_content.user_id AND entities.entity_id = users_entities.entity_id AND group_id IN 
                (SELECT group_id FROM usergroups_services WHERE service_id = ? AND group_id = ?)  order by users.lastname", array('avis_documents',$group_id));
        }else{
            $stmt = $db->query("SELECT users.user_id, users.firstname, users.lastname, usergroup_content.group_id,entities.entity_id from users, usergroup_content, users_entities,entities WHERE users_entities.user_id = users.user_id and 
                users_entities.primary_entity = 'Y' and users.user_id = usergroup_content.user_id AND entities.entity_id = users_entities.entity_id AND group_id IN 
                (SELECT group_id FROM usergroups_services WHERE service_id = ?)  
                order by users.lastname", array('avis_documents'));
        }
        
        $tab_users = array();
        
        
        while($res = $stmt->fetchObject()){
            array_push($tab_users,array('id'=>$res->user_id, 'firstname'=>$res->firstname,'lastname'=>$res->lastname,'group_id'=>$res->group_id,'entity_id'=>$res->entity_id));
        }
        return $tab_users;
    }

    public function myPosAvis($res_id, $coll_id, $listDiffType){
        $db = new Database();
        $stmt = $db->query("SELECT sequence, item_mode from listinstance WHERE res_id= ? and coll_id = ? and difflist_type = ? and item_id = ? and  process_date ISNULL ORDER BY listinstance_id ASC LIMIT 1", array($res_id, $coll_id, $listDiffType, $_SESSION['user']['UserId']));
        $res = $stmt->fetchObject();
        /*if ($res->item_mode == 'sign'){
            return $this->nbVisa($res_id, $coll_id);
        }*/
        return $res->sequence;
    }

    protected function getWorkflowsNumberByTitle($title){
        $db = new Database();
        $stmt = $db->query("SELECT * FROM listmodels WHERE title = ?", array($title));
        return $stmt->rowCount();
    }

    public function isWorkflowTitleFree($title){
        $nb = $this->getWorkflowsNumberByTitle($title);
        if ($nb == 0)
            return true;
        else
            return false;
    }

    public function saveModelWorkflow($id_list, $workflow, $typeList, $title){
        require_once('modules/entities/class/class_manage_listdiff.php');
        $diff_list = new diffusion_list();

        
        $diff_list->save_listmodel(
            $workflow, 
            $typeList,
            $id_list,
            $title
        );    
    }

    public function saveWorkflow($res_id, $coll_id, $workflow, $typeList){
        require_once('modules/entities/class/class_manage_listdiff.php');
        $diff_list = new diffusion_list();
        
        $diff_list->save_listinstance(
            $workflow, 
            $typeList,
            $coll_id, 
            $res_id, 
            $_SESSION['user']['UserId'],
            $_SESSION['user']['primaryentity']['id']
        ); 
        
    }

    public function setStatusAvis($res_id, $coll_id){
        $curr_avis_wf = $this->getWorkflow($res_id, $coll_id, 'AVIS_CIRCUIT');

        $db = new Database();
        $stmt = $db->query("SELECT sequence, item_mode from listinstance WHERE res_id= ? and coll_id = ? and difflist_type = ? and process_date ISNULL ORDER BY listinstance_id ASC LIMIT 1", array($res_id, $coll_id, 'AVIS_CIRCUIT'));
        $resListDiffAvis = $stmt->fetchObject();

        // If there is only one step in the avis workflow, we set status to EVISA
        if (count($curr_avis_wf['avis']) == 0){
            $mailStatus = 'NEW';
        } else {
            $mailStatus = 'EAVIS';
        }

        $db->query("UPDATE res_letterbox SET status = ? WHERE res_id = ? ", array($mailStatus, $res_id));

    }

    public function getCurrentStepAvis($res_id, $coll_id, $listDiffType){
        $db = new Database();
        if($listDiffType == 'entity_id'){
            $order = 'DESC';
        }else{
            $order = 'ASC';
        }
        $stmt = $db->query("SELECT sequence, item_mode from listinstance WHERE res_id= ? and coll_id = ? and difflist_type = ? and process_date ISNULL ORDER BY listinstance_id ".$order." LIMIT 1", array($res_id, $coll_id, $listDiffType));
        $res = $stmt->fetchObject();
        /*if ($res->item_mode == 'avis'){
            return $this->nbAvis($res_id, $coll_id);
        }*/
        return $res->sequence;
    }

    public function getStepDetailsAvis($res_id, $coll_id, $listDiffType, $sequence)
    {
        $stepDetails = array();
        $db = new Database();
        $stmt = $db->query("SELECT * "
            . "from listinstance WHERE res_id= ? and coll_id = ? "
            . "and difflist_type = ? and sequence = ? "
            . "ORDER BY listinstance_id ASC LIMIT 1", 
            array($res_id, $coll_id, $listDiffType, $sequence));
        $res = $stmt->fetchObject();
        $stepDetails['listinstance_id'] = $res->listinstance_id;
        $stepDetails['coll_id'] = $res->coll_id;
        $stepDetails['res_id'] = $res->res_id;
        $stepDetails['listinstance_type'] = $res->listinstance_type;
        $stepDetails['sequence'] = $res->sequence;
        $stepDetails['item_id'] = $res->item_id;
        $stepDetails['item_type'] = $res->item_type;
        $stepDetails['item_mode'] = $res->item_mode;
        $stepDetails['added_by_user'] = $res->added_by_user;
        $stepDetails['added_by_entity'] = $res->added_by_entity;
        $stepDetails['visible'] = $res->visible;
        $stepDetails['viewed'] = $res->viewed;
        $stepDetails['difflist_type'] = $res->difflist_type;
        $stepDetails['process_date'] = $res->process_date;
        $stepDetails['process_comment'] = $res->process_comment;
        
        return $stepDetails;
    }

    public function nbAvis($res_id, $coll_id){
        $db = new Database();
        $stmt = $db->query("SELECT listinstance_id from listinstance WHERE res_id= ? and coll_id = ? and item_mode = ? and difflist_type = 'AVIS_CIRCUIT'", array($res_id, $coll_id, 'avis'));
        return $stmt->rowCount();
    }

        #####################################
    ## add note on a resource
    #####################################
    public function UpdateNoteAvis($resId, $collId, $noteContent)
    {
        $status = 'ok';
        $error = '';
        //control parameters
        if (isset($resId) && empty($resId)) {
            $status = 'ko';
            $error = 'resId empty ';
        }
        if (isset($collId) && empty($collId)) {
            $status = 'ko';
            $error = 'collId empty ';
        }
        if (isset($noteContent) && empty($noteContent)) {
            $status = 'ko';
            $error .= 'noteContent empty ';
        }
        //process
        if ($status == 'ok') {
            require_once 'core/class/class_security.php';
            require_once 'modules/notes/notes_tables.php';
            $security = new security();
            $view = $security->retrieve_view_from_coll_id($collId);
            $table = $security->retrieve_table_from_coll($collId);
            $db = new Database();
            $query = "SELECT res_id FROM " . $view . " WHERE res_id = ?";
            $stmt = $db->query($query, array($resId));
            if ($stmt->rowCount() == 0) {
                $status = 'ko';
                $error .= 'resId not exists';
            } else {
                $query =
                    "UPDATE " . NOTES_TABLE
                    . " SET note_text = ?"
                    . ", date_note = CURRENT_TIMESTAMP"
                    . " WHERE identifier = ?"
                    . " AND note_text LIKE '[POUR AVIS]%'"
                    . " AND coll_id = ?";

                $stmt = $db->query($query, array($noteContent, $resId, $collId));

                $hist = new history();
                $hist->add(
                    $view, $resId, 'UP', 'resup', _AVIS_UPDATED
                    . _ON_DOC_NUM . $resId . ' ' . _BY . ' '.$_SESSION['user']['UserId'],
                    $_SESSION['config']['databasetype'], 'notes'
                );

            }
        }
        $returnArray = array(
            'status' => $status,
            'value' => $id,
            'error' => $error,
        );
        return $returnArray;
    }
    
}
