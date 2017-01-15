<?php
/*
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
*/

/**
* @brief Show the tree
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");

$core_tools = new core_tools();
$core_tools->load_lang();
$func = new functions();
$db = new Database();

$nb_trees = 0;
if(isset($_SESSION['doctypes_chosen_tree']))
{
    $nb_trees = count($_SESSION['doctypes_chosen_tree']);
}
$core_tools->load_html();
$core_tools->load_header('', true, false);
$f_level = array();
$folder_module = $core_tools->is_module_loaded('folder');

?>
<body>
<?php
if($nb_trees < 1 && $folder_module)
{
    echo _NO_DEFINED_TREES;
}
else
{
    if ((
    	(isset($_SESSION['doctypes_chosen_tree']) && ! empty($_SESSION['doctypes_chosen_tree'])) 
    	&& $folder_module 
    	) || ! $folder_module)
    {
        ?>
        <script type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'].'tools/tafelTree/';?>js/prototype.js"></script>
        <script type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'].'tools/tafelTree/';?>js/scriptaculous.js"></script>
        <script type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'].'tools/tafelTree/';?>Tree.js"></script>
        <?php
        $search_customer_results = array();
        $f_level = array();
        if($folder_module)
        {
            $query="SELECT d.doctypes_first_level_id, d.doctypes_first_level_label FROM ".$_SESSION['tablename']['fold_foldertypes_doctypes_level1']." g, ".$_SESSION['tablename']['doctypes_first_level']." d WHERE g.foldertype_id = ? and g.doctypes_first_level_id = d.doctypes_first_level_id and d.enabled = 'Y' order by d.doctypes_first_level_label";
            $stmt = $db->query($query, array($_SESSION['doctypes_chosen_tree']));
        }
        else
        {
            $query="SELECT d.doctypes_first_level_id, d.doctypes_first_level_label FROM  ".$_SESSION['tablename']['doctypes_first_level']." d WHERE d.enabled = 'Y' order by d.doctypes_first_level_label";
            $stmt = $db->query($query);
        }

        while($res1 = $stmt->fetchObject())
        {
            $s_level = array();
            $stmt2 = $db->query("SELECT doctypes_second_level_id, doctypes_second_level_label FROM ".$_SESSION['tablename']['doctypes_second_level']." WHERE doctypes_first_level_id = ? and enabled = 'Y'", array($res1->doctypes_first_level_id));
            while($res2 = $stmt2->fetchObject())
            {
                $doctypes = array();
                $stmt3 = $db->query("SELECT type_id, description FROM ".$_SESSION['tablename']['doctypes']." WHERE doctypes_first_level_id = ? and doctypes_second_level_id = ? and enabled = 'Y' ", array($res1->doctypes_first_level_id, $res2->doctypes_second_level_id));
                while($res3 = $stmt3->fetchObject())
                {
                    $results = array();
                    array_push($doctypes, array('type_id' => $res3->type_id, 'description' => $func->show_string($res3->description), "results" => $results));
                }
                array_push($s_level, array('doctypes_second_level_id' => $res2->doctypes_second_level_id, 'doctypes_second_level_label' => $func->show_string($res2->doctypes_second_level_label, true), 'doctypes' => $doctypes));
            }
            array_push($f_level, array('doctypes_first_level_id' => $res1->doctypes_first_level_id, 'doctypes_first_level_label' => $func->show_string($res1->doctypes_first_level_label, true), 'second_level' => $s_level));
        }
        if($folder_module)
        {
            for($i=0;$i<count($_SESSION['tree_foldertypes']);$i++)
            {
                if($_SESSION['tree_foldertypes'][$i]['ID'] == $_SESSION['doctypes_chosen_tree'])
                $fLabel = $_SESSION['tree_foldertypes'][$i]['LABEL'];
            }
            array_push($search_customer_results, array('folder_id' => $fLabel, 'content' => $f_level));
        }
        else
        {
            array_push($search_customer_results, array('folder_id' => _TREE_ROOT, 'content' => $f_level));
        }
        //$core_tools->show_array($search_customer_results);
        ?>
        <script type="text/javascript">

            var BASE_URL = '<?php echo $_SESSION['config']['businessappurl'];?>';
            function funcOpen(branch, response) {
                // Ici tu peux traiter le retour et retourner true si
                // tu veux ins�rer les enfants, false si tu veux pas
                //MyClick(branch);
                return true;
            }

            function myClick(branch) {

            }

            function MyOpen(branch)
            {
                if(branch.struct.script != '' && branch.struct.script != 'default')
                {
                    var parents = [];
                    parents = branch.getParents();
                    var str = '';
                    for(var i=0; i < (parents.length -1) ;i++)
                    {
                        str = str + '&parent_id[]=' + parents[i].getId();
                    }
                    var str_children  = '';
                    var children = branch.getChildren();
                    for(var i=0; i < (children.length -1) ;i++)
                    {
                        str_children = str_children + '&children_id[]=' + children[i].getId();
                    }
                }
                return true;
            }

            function MyClose(branch)
            {
                var parents = branch.getParents();
                var branch_id = branch.getId();
                if(current_branch_id != null)
                {
                    var branch2 = tree.getBranchById(current_branch_id);
                    if(current_branch_id == branch_id )
                    {
                        current_branch_id = branch.getNextOpenedBranch;
                    }
                    else if(branch2 && branch2.isChild(branch_id))
                    {
                        current_branch_id = branch.getNextOpenedBranch;
                    }
                }
                branch.collapse();
                branch.openIt(false);
            }

            function MyBeforeOpen(branch, opened)
            {
                if(opened == true)
                {
                    MyClose(branch);
                }
                else
                {
                    current_branch_id = branch.getId();
                    MyOpen(branch);
                    return true;
                }
            }

            function myMouseOver (branch)
            {
                document.body.style.cursor='pointer';
            }

            function myMouseOut (branch)
            {
                document.body.style.cursor='auto';
            }

            var tree = null;
            var current_branch_id = null;

            function TafelTreeInit ()
            {
                var struct = [
                <?php

                    for($i=0;$i<count($search_customer_results);$i++)
                    {
                            ?>
                            {
                                'id':'<?php functions::xecho(addslashes($search_customer_results[$i]['folder_id']));?>',
                                'txt':'<b><?php functions::xecho(addslashes($search_customer_results[$i]['folder_id']));?></b>',
                                'items':[
                                            <?php
                                            for($j=0;$j<count($search_customer_results[$i]['content']);$j++)
                                            {
                                                ?>
                                                {
                                                    'id':'<?php functions::xecho(addslashes($search_customer_results[$i]['content'][$j]['doctypes_first_level_id']));?>',
                                                    'txt':'<?php functions::xecho(addslashes($search_customer_results[$i]['content'][$j]['doctypes_first_level_label']));?>',
                                                    'items':[
                                                                <?php
                                                                for($k=0;$k<count($search_customer_results[$i]['content'][$j]['second_level']);$k++)
                                                                {
                                                                    ?>
                                                                    {
                                                                        'id':'<?php functions::xecho(addslashes($search_customer_results[$i]['content'][$j]['second_level'][$k]['doctypes_second_level_id']));?>',
                                                                        'txt':'<?php functions::xecho(addslashes($search_customer_results[$i]['content'][$j]['second_level'][$k]['doctypes_second_level_label']));?>',
                                                                        'items':[
                                                                                    <?php
                                                                                    for($l=0;$l<count($search_customer_results[$i]['content'][$j]['second_level'][$k]['doctypes']);$l++)
                                                                                    {
                                                                                        ?>
                                                                                        {
                                                                                            <?php
                                                                                            ?>
                                                                                            'txt':'<span style="font-style:italic;"><small><small><a href="#" onclick="window.open(\'<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=types_up&id=<?php functions::xecho($search_customer_results[$i]['content'][$j]['second_level'][$k]['doctypes'][$l]['type_id']);?>\');"><?php functions::xecho(addslashes($search_customer_results[$i]['content'][$j]['second_level'][$k]['doctypes'][$l]['description']));?></a></small></small></span>',
                                                                                            'img':'empty.gif'
                                                                                        }
                                                                                        <?php
                                                                                        if($l <> count($search_customer_results[$i]['content'][$j]['second_level'][$k]['doctypes']) - 1)
                                                                                        echo ',';
                                                                                    } ?>
                                                                                ]
                                                                    }
                                                                    <?php
                                                                    if($k <> count($search_customer_results[$i]['content'][$j]['second_level']) - 1)
                                                                    echo ',';
                                                                }
                                                                ?>
                                                            ]
                                                }
                                                <?php
                                                if($j <> count($search_customer_results[$i]['content']) - 1)
                                                    echo ',';
                                            }
                                            ?>
                                        ]
                            }
                            <?php
                            if ($i <> count($search_customer_results) - 1)
                                echo ',';
                        }

                                ?>
                            ];
                tree = new TafelTree('trees_div', struct, {
                    'generate' : true,
                    'imgBase' : '<?php echo $_SESSION['config']['businessappurl'].'tools/tafelTree/';?>imgs/',
                    'defaultImg' : 'folder.gif',
                    //'defaultImg' : 'page.gif',
                    'defaultImgOpen' : 'folderopen.gif',
                    'defaultImgClose' : 'folder.gif',
                    'onOpenPopulate' : [funcOpen, 'get_tree_children.php?IdTree=<?php functions::xecho($_SESSION['doctypes_chosen_tree']);?>']
                });

                //open all branches
                tree.expend();
            };
        </script>
        <div id="trees_div"></div>
        <?php
    }
}
$core_tools->load_js();
?>
</body>
</html>
