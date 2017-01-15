<?php
/**
* File : search_customer.php
*
* Advanced search form
*
* @package  Maarch Framework 3.0
* @version 2.1
* @since 10/2005
* @license GPL
* @author Loïc Vinet  <dev@maarch.org>
* @author Claire Figueras  <dev@maarch.org>
*/

require_once
    "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR 
    . "class_business_app_tools.php";
    
$appTools   = new business_app_tools();
$core       = new core_tools();

$core->test_user();
$core->load_lang();
$core->test_service('view_folder_tree', 'folder');
$_SESSION['indexation'] = false;

$_SESSION['category_id_session'] = '';

//Definition de la collection
$_SESSION['collection_id_choice'] = $_SESSION['user']['collections'][0];
if ($_SESSION['user']['collections'][1] == 'letterbox_coll') {
    $_SESSION['collection_id_choice'] = 'letterbox_coll';
}
/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == 'true') {
    $init = true;
}
$level = '';
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4
    || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$pagePath = $_SESSION['config']['businessappurl']
           . 'index.php?page=search_folder_tree&module=folder';
$pageLabel = _VIEW_FOLDER_TREE;
$pageId = 'search_folder_tree';
$core->manage_location_bar(
    $pagePath, $pageLabel, $pageId, $init, $level
);
/***********************************************************/

//$core->show_array($_REQUEST);
$_SESSION['origin'] = "search_folder_tree";
?>
<script type="text/javascript" >
    BASE_URL = "<?php echo $_SESSION['config']['businessappurl'] ?>";
</script>
<h1><i class="fa fa-search fa-2x"></i> <?php echo _VIEW_FOLDER_TREE;?></h1>
<div id="inner_content" align="center">
    <div class="block">
        <h2>
		<form method="post" name="form_search_folder" id="form_search_folder" action="#">
        <table width="100%" border="0">
            <tr>
                <td align="right"><label for="folder"><?php
            echo _FOLDER;
            ?> :</label></td>
                            <td class="indexing_field">
                                <input type="text" name="folder" id="folder" size="45" onKeyPress="if(event.keyCode == 13) submitForm();" />
                                <div id="show_folder" class="autocomplete" style="color:#666;"></div>
                            </td>
                            <!-- <td align="right"><label for="subfolder"><?php echo _SUBFOLDER;?> :</label></td>
                            <td>
                                <input type="text" name="subfolder" id="subfolder" size="45" onKeyPress="if(event.keyCode == 13) submitForm();" />
                                <div id="show_subfolder" class="autocomplete"></div>
                            </td>-->
                            <td>
                                <input id="tree_send" type="button" value="<?php
            echo _SEARCH;
            ?>" onclick="javascript:submitForm();" class="button">
                </td>
				<td width="50%"><div style="font-size: 8px;margin-top: -11px;">
        <br>
        <a href="javascript://" onClick="window.top.location.href='<?php
                echo $_SESSION['config']['businessappurl'];
                ?>index.php?page=search_folder_tree&module=folder&erase=true';">
                <i class="fa fa-refresh fa-4x" style="color: #ffffff;" title="<?php echo _NEW_SEARCH;?>"></i>
        </a>
    </div></td>
            </tr>
        </table>
		</form>
        </h2>
        <table width="100%" height="100%" cellspacing="5" style="border:1px solid #999999;">
        <tr>
            <td width="55%" height="720px" style="vertical-align: top; text-align: left;border-right:1px solid #999999;">
                <div id="loading" style="display:none;"><i class="fa fa-spinner fa-2x"></i></div>
                <div id="myTree" style="height: 100%;overflow: auto;">&nbsp;</div>
            </td>
            <td width="45%" style="vertical-align: top;">
                <div id="docView"><p align="center"><img src="<?php echo $_SESSION['config']['businessappurl']
                    .'static.php?filename=bg_home_home.gif'; 
                    ?>"  width="400px" alt="Maarch" /></p></div>
            </td>
        </tr>
    </table>
    </div>
    <!-- Display the layout of search_folder_tree -->
</div>
<script type="text/javascript">

    initList('folder', 'show_folder', '<?php
        echo $_SESSION['config']['businessappurl'];
        ?>index.php?display=true&module=folder&page=autocomplete_folders&mode=folder', 
        'Input', '2');
    function submitForm()
    {
        var folder = $('folder').value;
        if($('myTree'))
        {
            $('myTree').innerHTML="";
        }
        // Get tree parameters from an ajax script (get_tree_info.php)
        new Ajax.Request(BASE_URL+'index.php?display=true&module=folder&page=get_tree_info&display=true',{
            method: 'post',
            parameters: {
                tree_id: 'myTree',
                project: folder
            },
            onSuccess: function(response){
                eval('params='+response.responseText+';');
                //console.log(params);
            },
            onLoading: function(answer) {
                //show loading
                $('loading').style.display='block';
            }, 
            onComplete: function(response){
                $('loading').style.display='none';
                $('myTree').innerHTML=response.responseText;
                 // alert(response.responseText);

             /*   if(more_params['onComplete_callback'])
                {
                    eval(more_params['onComplete_callback']);
                }*/
            }
        });
    }
    function get_folders(folders_system_id)
    {

            if($(''+folders_system_id).hasClassName('mt_fopened')){
                new Ajax.Request(BASE_URL+'index.php?page=ajax_get_folder&module=folder&display=true',
                {  
                    method:'post',
                    parameters: {folders_system_id: folders_system_id,
                                FOLDER_TREE_RESET: true
                                },
                    onSuccess: function(answer){
                        folders = JSON.parse(answer.responseText);

                        for (var i = 0; i <= folders.length; i++) {
                            level=folders[i].folder_level*10;

                            $(''+folders_system_id).innerHTML ='<span onclick="get_folders('+folders[i].folders_system_id+')">'+folders[i].nom_folder+'</span><b>('+folders[i].nb_subfolder+' <?php echo _MARKET; ?>, <span onclick="get_folder_docs('+folders[i].folders_system_id+')">'+folders[i].nb_doc+' document(s)</span>)</b>';
                            $(''+folders_system_id).addClassName('mt_fclosed');
                            $(''+folders_system_id).removeClassName('mt_fopened');
                            $(''+folders_system_id).removeClassName('link_open');
                            $(''+folders_system_id).style.listStyleImage= 'url(static.php?filename=folder.gif)';
                        };
                        
                    },
                    onFailure: function(){
                        $(''+folders_system_id).innerHTML += '<div class="error">_SERVER_ERROR</div>';
                       }
                });

            }else{
                new Ajax.Request(BASE_URL+'index.php?page=ajax_get_folder&module=folder&display=true',
                {  
                    method:'post',
                    parameters: {folders_system_id: folders_system_id,
                                FOLDER_TREE: true
                                },
                    onSuccess: function(answer){
                        folders = JSON.parse(answer.responseText);

                        for (var i = 0; i <= folders.length; i++) {
                            level=folders[i].folder_level*10;
                            var style='style="margin-left:'+level+'px;"';
                            $(''+folders_system_id).innerHTML +='<li '+style+' class="folder" id="'+folders[i].folders_system_id+'"><span onclick="get_folders('+folders[i].folders_system_id+')" class="folder">'+folders[i].nom_folder+'</span><b>('+folders[i].nb_subfolder+' <?php echo _MARKET; ?>, <span onclick="get_folder_docs('+folders[i].folders_system_id+')">'+folders[i].nb_doc+' document(s)</span>)</b></li>';

                            $(''+folders_system_id).addClassName('mt_fopened');
                            $(''+folders_system_id).removeClassName('mt_fclosed');
                            $(''+folders_system_id).addClassName('link_open');
                            $(''+folders_system_id).style.listStyleImage= 'url(static.php?filename=folderopen.gif)';
                        };
                        
                    },
                    onFailure: function(){
                        $(''+folders_system_id).innerHTML += '<div class="error">_SERVER_ERROR</div>';
                       }
                });
        }

        }

        function get_autofolders(folder_level)
        {

            if($('autofolders').hasClassName('mt_fopened')){
                $('autofolders').innerHTML = "<span onclick='get_autofolders("+folder_level+")'>Plan de classement dynamique</span>";
                $('autofolders').addClassName('mt_fclosed');
                $('autofolders').style.listStyleImage= 'url(static.php?filename=folder.gif)';
                $('autofolders').removeClassName('link_open');
                $('autofolders').removeClassName('mt_fopened');
                $('autofolders').src = BASE_URL+'static.php?filename=folder.gif';

            }else{
                new Ajax.Request(BASE_URL+'index.php?page=ajax_get_folder&module=folder&display=true',
                {  
                    method:'post',
                    parameters: {folder_level: folder_level,
                                AUTOFOLDERS_TREE: true
                                },
                    onSuccess: function(answer){
                        autofolders = JSON.parse(answer.responseText);

                        for (var i = 0; i <= autofolders.length; i++) {
                            var style='style="margin-left:20px;"';

                            $('autofolders').innerHTML +='<li '+style+' class="folder" id="'+autofolders[i].id[0]+'"><span onclick="get_autofolder_folders(\''+autofolders[i].id[0]+'\','+autofolders[i].tree_level+',\''+autofolders[i].id[0]+'\',\''+autofolders[i].id[0]+'\',\''+autofolders[i].desc[0]+'\')">'+autofolders[i].desc[0]+'</span></li>';

                            $('autofolders').addClassName('mt_fopened');
                            $('autofolders').style.listStyleImage= 'url(static.php?filename=folderopen.gif)';
                            $('autofolders').addClassName('link_open');
                            $('autofolders').removeClassName('mt_fclosed');

                        };
                        
                    },
                    onFailure: function(){
                        $(''+folders_system_id).innerHTML += '<div class="error">_SERVER_ERROR</div>';
                       }
                });
        }

        }

        function get_autofolder_folders(id, folder_level,id_folder,path_folder,desc_tree)
        {
            if($(''+id_folder).hasClassName('mt_fopened')){

                var options= '\''+id+'\',\''+folder_level+'\',\''+id_folder+'\',\''+path_folder+'\',\''+desc_tree+'\'';
                $(''+id_folder).innerHTML = '<span onclick="get_autofolder_folders('+options+')">'+desc_tree+'</span>';
                $(''+id_folder).addClassName('mt_fclosed');
                $(''+id_folder).style.listStyleImage= 'url(static.php?filename=folder.gif)';
                $(''+id_folder).removeClassName('link_open');
                $(''+id_folder).removeClassName('mt_fopened');

            }else{
                new Ajax.Request(BASE_URL+'index.php?page=ajax_get_folder&module=folder&display=true',
                {  
                    method:'post',
                    parameters: {folder_level: folder_level,
                                id : id,
                                path_folder : path_folder,
                                id_folder : id_folder,
                                AUTOFOLDER_FOLDERS: true
                                },
                    onSuccess: function(answer){
                        folders = JSON.parse(answer.responseText);
                        for (var i = 0; i <= folders.length; i++) {

                            if(folders[i].folder_level==2){
                                var style='style="margin-left:30px;"';
                            }else{
                                var folder_level=folders[i].folder_level+1;
                                level=folder_level*7;
                                var style='style="margin-left:'+level+'px;"';
                            }
                            var new_path_folder=path_folder+';'+folders[i].libelle;
                            if(folders[i].check_docs==true){
                                var onclick='onclick="get_autofolder_folder_docs(\''+id+'\','+folders[i].folder_level+',\''+folders[i].libelle+'\',\''+new_path_folder+'\',\''+folders[i].libelle+'\')"';
                            }else{
                                var onclick='onclick="get_autofolder_folders(\''+id+'\','+folders[i].folder_level+',\''+folders[i].libelle+'\',\''+new_path_folder+'\',\''+folders[i].libelle+'\')"';
                            }
                            $(''+id_folder).innerHTML +='<li '+style+' class="folder" id="'+folders[i].libelle+'"><span '+onclick+'>'+folders[i].libelle+'</span></li>';

                            $(''+id_folder).addClassName('mt_fopened');
                            $(''+id_folder).style.listStyleImage= 'url(static.php?filename=folderopen.gif)';
                            $(''+id_folder).addClassName('link_open');
                            $(''+id_folder).removeClassName('mt_fclosed');
                        };
                        
                    },
                    onFailure: function(){
                        $(''+folders_system_id).innerHTML += '<div class="error">_SERVER_ERROR</div>';
                       }
                });
        }

        }

        function get_autofolder_folder_docs(id, folder_level,id_folder,path_folder)
        {
            if($(''+id_folder).hasClassName('mt_fopened')){
                var options= '\''+id+'\',\''+folder_level+'\',\''+id_folder+'\',\''+path_folder+'\',\''+id_folder+'\'';
                $(''+id_folder).innerHTML = '<span onclick="get_autofolder_folder_docs('+options+')">'+id_folder+'</span>';
                $(''+id_folder).addClassName('mt_fclosed');
                $(''+id_folder).style.listStyleImage= 'url(static.php?filename=folder.gif)';
                $(''+id_folder).removeClassName('link_open');
                $(''+id_folder).removeClassName('mt_fopened');

            }else{

                new Ajax.Request(BASE_URL+'index.php?page=ajax_get_folder&module=folder&display=true',
                {  
                    method:'post',
                    parameters: {folder_level: folder_level,
                                id : id,
                                id_folder : id_folder,
                                path_folder : path_folder,
                                AUTOFOLDER_FOLDER_DOCS: true
                                },
                    onSuccess: function(answer){
                        docs = JSON.parse(answer.responseText);
                        for (var i = 0; i <= docs.length; i++) {

                            var style='style="margin-left:35px;"';
                            $(''+id_folder).innerHTML +='<ul class="doc" style="margin-top:10px;margin-left:10px;'+style+'"><b>'+docs[i].type_label+'</b> <a onclick=\'updateContent("index.php?dir=indexing_searching&page=little_details_invoices&display=true&value='+docs[i].res_id+'", "docView");\'>'+docs[i].res_id+' - '+docs[i].subject+'</a></ul>';

                            $(''+id_folder).addClassName('mt_fopened');
                            $(''+id_folder).style.listStyleImage= 'url(static.php?filename=folderopen.gif)';
                            $(''+id_folder).addClassName('link_open');
                            $(''+id_folder).removeClassName('mt_fclosed');
                        };
                        
                    },
                    onFailure: function(){
                        $(''+folders_system_id).innerHTML += '<div class="error">_SERVER_ERROR</div>';
                       }
                });
        }

        }

        function get_folder_docs(folders_system_id)
        {
            if($(''+folders_system_id).hasClassName('mt_fopened')){
                new Ajax.Request(BASE_URL+'index.php?page=ajax_get_folder&module=folder&display=true',
                {  
                    method:'post',
                    parameters: {folders_system_id: folders_system_id,
                                FOLDER_TREE_RESET: true
                                },
                    onSuccess: function(answer){
                        folders = JSON.parse(answer.responseText);

                        for (var i = 0; i <= folders.length; i++) {
                            level=folders[i].folder_level*10;
                            $(''+folders_system_id).innerHTML ='<span onclick="get_folders('+folders[i].folders_system_id+')">'+folders[i].nom_folder+'</span><b>('+folders[i].nb_subfolder+' <?php echo _MARKET; ?>, <span onclick="get_folder_docs('+folders[i].folders_system_id+')">'+folders[i].nb_doc+' document(s)</span>)</b>';
                            $(''+folders_system_id).addClassName('mt_fclosed');
                            $(''+folders_system_id).removeClassName('mt_fopened');
                            $(''+folders_system_id).removeClassName('link_open');
                            $(''+folders_system_id).style.listStyleImage= 'url(static.php?filename=folder.gif)';
                        };
                        
                    },
                    onFailure: function(){
                        $(''+folders_system_id).innerHTML += '<div class="error">_SERVER_ERROR</div>';
                       }
                });

            }else{
                new Ajax.Request(BASE_URL+'index.php?page=ajax_get_folder&module=folder&display=true',
                {  
                    method:'post',
                    parameters: {folders_system_id: folders_system_id,
                                FOLDER_TREE_DOCS: true
                                },
                    onSuccess: function(answer){
                        docs = JSON.parse(answer.responseText);

                        if (docs != '') {
                            for (var subarray in docs) {
                                $(''+folders_system_id).innerHTML +='<ul class="doc" style="margin-top:10px;margin-left:30px;"><li><b>'+subarray+'</b></li>';
                                for (var mail in docs[subarray]) {
                                    $(''+folders_system_id).innerHTML +='<li style="margin-bottom:10px;margin-top:10px;margin-left:50px;"><b>'+mail+'</b></li>';
                                    for (var i = 0; i < docs[subarray][mail].length; i++) {
                                        $(''+folders_system_id).innerHTML +='<li style="margin-bottom:5px;margin-left:80px;"><a onclick=\'updateContent("index.php?dir=indexing_searching&page=little_details_invoices&display=true&value='+docs[subarray][mail][i].res_id+'", "docView");\'>('+docs[subarray][mail][i].res_id+') '+docs[subarray][mail][i].type_label+' - '+docs[subarray][mail][i].subject+'</li>';
                                        $(''+folders_system_id).addClassName('mt_fopened');
                                        $(''+folders_system_id).removeClassName('mt_fclosed');
                                        $(''+folders_system_id).addClassName('link_open');
                                        $(''+folders_system_id).style.listStyleImage= 'url(static.php?filename=folderopen.gif)';
                                    }
                                }
                                $(''+folders_system_id).innerHTML +='</ul>';
                            }
                        }
                        
                    },
                    onFailure: function(){
                        $(''+folders_system_id).innerHTML += '<div class="error">_SERVER_ERROR</div>';
                       }
                });
            }
        }

</script>
<script type="text/javascript" src="<?php
echo $_SESSION['config']['businessappurl'] . 'tools/'
?>MaarchJS/dist/maarch.js"></script>
<script type="text/javascript" src="<?php
echo $_SESSION['config']['businessappurl'] . 'js/'
?>search_customer.js"></script>