<?php
/*
*
*    Copyright 2008,2009 Maarch
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
*
/**
* @brief  Reports administration : laffect reports to groups
*
* @file
* @author  Yves Christian KPAKPO <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup reports
*/

$admin = new core_tools();
$admin->test_admin('admin_reports', 'reports');
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=admin_reports&module=reports';
$page_label = _ADMIN_REPORTS;
$page_id = "admin_reports";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

//Get all group for admin
$_SESSION['m_admin']['load_groups']  = true;
$_SESSION['m_admin']['all_groups'] = array();

$db = new Database();
$stmt = $db->query("select group_id, group_desc from ".$_SESSION['tablename']['usergroups']." where enabled ='Y' order by group_desc");
while($res = $stmt->fetchObject())
{
    array_push($_SESSION['m_admin']['all_groups'], array('id'=> $res->group_id, 'label' => $res->group_desc));
}

//Get the groupe Id
$groupeid = "";

if(isset($_GET['id']) && !empty($_GET['id']))
{
    $groupeid = $_GET['id'];
}
?>
<h1><i class="fa fa-area-chart fa-2x"></i> <?php echo _ADMIN_REPORTS;?></h1>

            <script type='text/javascript'>
            // Two functions to access javascript object in Ajax frame
            var globalEval =  function(script){
              if(window.execScript){
                return window.execScript(script);
              } else if(navigator.userAgent.indexOf('KHTML') != -1){ //safari, konqueror..
                  var s = document.createElement('script');
                  s.type = 'text/javascript';
                  s.innerHTML = script;
                  document.getElementsByTagName('head')[0].appendChild(s);
              } else {
                return window.eval(script);
              }
            }
            //
            function evalMyScripts(targetId) {
                var myScripts = document.getElementById(targetId).getElementsByTagName('script');
                for (var i=0; i<myScripts.length; i++) {
                    globalEval(myScripts[i].innerHTML);
                }
            }

            function getXhr(){
                var xhr = null;
                if(window.XMLHttpRequest) // Firefox et autres
                    xhr = new XMLHttpRequest();
                else if(window.ActiveXObject){ // Internet Explorer
                    try {
                            xhr = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e){
                        xhr = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                }
                else { // XMLHttpRequest non supporté par le navigateur
                    alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
                    xhr = false;
                }
                return xhr;
            }

            // Méthode qui sera appelée pour rafraichir dynamiquement la liste des etats
            function listReportsUpdate(){
                var xhr = getXhr();
                // On défini ce qu'on va faire quand on aura la réponse
                xhr.onreadystatechange = function(){
                    //On affiche l'image de chargement
                    //if(xhr.readyState == 2)
                    if(xhr.readyState == 1)
                        document.getElementById('loading').style.display = 'block';
                    // On ne fait quelque chose que si on a tout reçu et que le serveur est ok
                    if(xhr.readyState == 4 && xhr.status == 200){
                        result = xhr.responseText;
                        // On se sert de innerHTML pour afficher le resultat dans la div
                        document.getElementById('listReports').innerHTML = result;
                        document.getElementById('loading').style.display = 'none';
                        evalMyScripts('listReports');
                    }
                }
                xhr.open("POST", "<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module=reports&page=list_reports';?>", true);
                xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                sendParam = '';
                // ici, on recupère les arguments à poster
                if(document.getElementById('group_id')){
                    selEnt = document.getElementById('group_id');
                    sendParam = sendParam + "group="+selEnt.options[selEnt.selectedIndex].value;
                }
                xhr.send(sendParam);

                //Call reinit function for show/hide info in list (cf: maarch.js)
                reinit();
            }

            </script>

        <div id="inner_content" class="clearfix">
        <div class="block">
        <h2>
        <form name="choose_a_group" id="choose_a_group" method="post">
            <label><?php echo ucwords( _GROUPS);?> :</label>
            <select name="group_id" id="group_id" onchange="listReportsUpdate();" class="listext_big">
            <option value=""><?php echo _CHOOSE_GROUP;?></option>
            <?php

            for($k=0;$k<count($_SESSION['m_admin']['all_groups']);$k++)
            {
                if($_SESSION['m_admin']['all_groups'][$k]['id'] == $_SESSION['m_admin']['group'])
                {
                    ?>
                        <option value="<?php functions::xecho($_SESSION['m_admin']['all_groups'][$k]['id']);?>" selected="selected"><?php functions::xecho($_SESSION['m_admin']['all_groups'][$k]['label']);?></option>
                    <?php
                }
                else
                {
                    ?>
                        <option value="<?php functions::xecho($_SESSION['m_admin']['all_groups'][$k]['id']);?>"><?php functions::xecho($_SESSION['m_admin']['all_groups'][$k]['label']);?></option>
                    <?php
                }
            }
            ?>
            </select>

        <div class="block_end">&nbsp;</div>
    </form>
    </h2>

    <div id="loading" style="display:none;text-align:center;"><p>
    <i class="fa fa-area-spinner fa-2x"></i>
    </p></div>

    <div id="listReports">

    <i><br><?php echo _HAVE_TO_SELECT_GROUP;?><br></i>
    </div>
</div>
</div>
