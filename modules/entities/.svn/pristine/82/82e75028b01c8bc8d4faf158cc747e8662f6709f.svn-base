<?php

$core = new core_tools();
$core->test_user();
$core->load_lang();

require_once('modules/entities/class/class_manage_listdiff.php');
$diff_list = new diffusion_list();
$db = new Database();

      $roles = $diff_list->list_difflist_roles();
      
      $listinstance = array();
      
      # Load header
      $query = 
          "SELECT distinct coll_id, res_id, difflist_type"
          . " FROM listinstance_history_details WHERE listinstance_history_id = ? GROUP BY coll_id, res_id, difflist_type";

      $stmt = $db->query($query,array($_REQUEST['listinstance_history_id']));

      $listinstance = $stmt->fetch();
      if($listinstance['difflist_type'] == "")
          $listinstance['difflist_type'] = 'entity_id';
      
      # OTHER ROLES USERS
      #**********************************************************************
      $stmt = $db->query(
          "select l.item_id, u.firstname, u.lastname, e.entity_id, "
          . "e.entity_label, l.visible, l.viewed, l.item_mode, l.difflist_type from listinstance_history_details l, " . USERS_TABLE
          . " u, " . ENT_ENTITIES . " e, " . ENT_USERS_ENTITIES
          . " ue where l.listinstance_history_id = ?"
          . " and l.item_type = 'user_id' and l.item_id = u.user_id "
          . " and l.item_id = ue.user_id and ue.user_id=u.user_id "
          . " and e.entity_id = ue.entity_id"
          . " and ue.primary_entity = 'Y' order by l.sequence ",array($_REQUEST['listinstance_history_id'])
      );
      //$diff_list->show();
      while ($res = $stmt->fetchObject()) {
          if($res->item_mode == 'cc') 
              $role_id = 'copy';
          else 
              $role_id = $res->item_mode;
              
          if(!isset($listinstance[$role_id]['users']))
              $listinstance[$role_id]['users'] = array();
          array_push(
              $listinstance[$role_id]['users'],
              array(
                  'user_id' => functions::show_string($res->item_id),
                  'lastname' => functions::show_string($res->lastname),
                  'firstname' => functions::show_string($res->firstname),
                  'entity_id' => functions::show_string($res->entity_id),
                  'entity_label' => functions::show_string($res->entity_label),
                  'visible' => functions::show_string($res->visible),
                  'viewed' => functions::show_string($res->viewed),
                  'difflist_type' => functions::show_string($res->difflist_type)
              )
          );
      }

      # OTHER ROLES ENTITIES
      #**********************************************************************
      $stmt = $db->query(
          "select l.item_id,  e.entity_label, l.visible, l.viewed, l.item_mode, l.difflist_type from listinstance_history_details l, " 
          . ENT_ENTITIES . " e where l.listinstance_history_id =  ?"
          . " and l.item_type = 'entity_id' and l.item_id = e.entity_id "
          . "order by l.sequence ",array($_REQUEST['listinstance_history_id'])
      );

      while ($res = $stmt->fetchObject()) {
          if($res->item_mode == 'cc') 
              $role_id = 'copy';
          else 
              $role_id = $res->item_mode;
              
          if(!isset($listinstance[$role_id]['entities']))
              $listinstance[$role_id]['entities'] = array();
          array_push(
              $listinstance[$role_id]['entities'],
              array(
                  'entity_id' => functions::show_string($res->item_id),
                  'entity_label' => functions::show_string($res->entity_label),
                  'visible' => functions::show_string($res->visible),
                  'viewed' => functions::show_string($res->viewed),
                  'difflist_type' => functions::show_string($res->difflist_type)
              )
          );
      }

  $difflist = $listinstance;

// $diff_list->get_difflist_type($listinstance['difflist_type']);

$return = '';

foreach($roles as $role_id => $role_label) {
    if($role_id == 'dest' && $onlyCC) continue;
    if(count($difflist[$role_id]['users']) > 0
        || count($difflist[$role_id]['entities']) > 0
    ) { 
      $return .= '<h3 class="sstit">'.$role_label.'</h3>';
    if(count($difflist[$role_id]['users']) > 0) { 
        $return .= '<table cellpadding="0" cellspacing="0" border="0" class="listingsmall liste_diff spec" style="width:100%;margin:0;">';
        $color = ' class="col"';
        for($i=0, $l=count($difflist[$role_id]['users']);
            $i<$l;
            $i++
        ) {
            $user = $difflist[$role_id]['users'][$i];
            
            if ($color == ' class="col"') $color = ' ';
            else $color = ' class="col"'; 
            $return .= '<tr '. $color.' >';
                $return .= '<td style="width:15%;text-align:center;">';
                $return .= '<i class="fa fa-user fa-2x" title="'._USER.' '.$role_label.'"></i>';
                $return .= '</td>';
                $return .= '<td style="width:10%;">';
                if($user['visible'] == 'Y') { 
                    $return .= '<i class="fa fa-check fa-2x" title="'._VISIBLE.'"></i>';
                } else {
                    $return .= '<i class="fa fa-times fa-2x" title="'._NOT_VISIBLE.'"></i>';
                } 
                $return .= '</td>';
                $return .= '<td style="width:37%;">'. $user['lastname'] . ' ' . $user['firstname'].'</td>';
                $return .= '<td style="width:38%;">'. $user['entity_label'].'</td>';
            $return .= '</tr>';
        } 
        $return .= '</table>';
    } 
    if(count($difflist[$role_id]['entities']) > 0) {
        $return .= '<table cellpadding="0" cellspacing="0" border="0" class="listingsmall liste_diff spec" style="width:100%;margin:0;">';
        $color = ' class="col"';
        for ($i=0, $l=count($difflist[$role_id]['entities']);
            $i<$l;
            $i++
        ) {
            $entity = $difflist[$role_id]['entities'][$i];
            if ($color == ' class="col"') $color = '';
            else $color = ' class="col"';
            $return .= '<tr'. $color.'>';
                $return .= '<td style="width:15%;text-align:center;">';
                    $return .= '<i class="fa fa-sitemap fa-2x" title="'._ENTITY . ' ' . $role_label.'" ></i>';
                $return .= '</td>';
                $return .= '<td style="width:10%;">';
                if($entity['visible'] == 'Y') { 
                    $return .= '<i class="fa fa-check fa-2x" title="'._VISIBLE.'"></i>';
                } else {
                    $return .= '<i class="fa fa-times fa-2x" title="'._NOT_VISIBLE.'"></i>';
                }
                $return .= '</td>';
                $return .= '<td style="width:37%;">'. $entity['entity_id'].'</td>';
                $return .= '<td style="width:38%;">'. $entity['entity_label'].'</td>';
            $return .= '</tr>';
        }
        $return .= '</table>';
    }
    $return .= '<br/>';
    }
}

echo "{status : 0, toShow : '" . addslashes($return) . "'}";
exit ();