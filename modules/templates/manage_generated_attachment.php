<?php
/**
* File : manage_generated_attachment.php
*
* Result of the generate answer form
*
* @package Maarch LetterBox 2.3
* @version 1.0
* @since 10/2007
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/

/*require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_resource.php");
require_once "core/class/class_security.php";

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$func = new functions();
if (empty($_REQUEST['mode']) || !isset($_REQUEST['mode'])) {
    $_SESSION['error'] .= _NO_MODE_DEFINED.'.<br/>';
    header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&module=templates&page=generate_attachment_html&template=".$_REQUEST['template_id']);
    exit;
} else {
    $conn = new Database();
    if (empty($_REQUEST['template_content']) || !isset($_REQUEST['template_content'])) {
        $_SESSION['error'] .= _NO_CONTENT.'.<br/>';
        if ($_REQUEST['mode'] == 'add') {
            header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&module=templates&page=generate_attachment_html&template=".$_REQUEST['template_id']."&mode=".$_REQUEST['mode']);
        } else {
            header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&module=templates&page=generate_attachment_html&id=".$_REQUEST['id']."&mode=".$_REQUEST['mode']);
        }
        exit;
    } else {
        if ($_REQUEST['mode'] == "add") {
            if (empty($_REQUEST['answer_title']) || !isset($_REQUEST['answer_title'])) {
                $_REQUEST['answer_title'] = $_SESSION['courrier']['res_id']."_".$_REQUEST['template_label'].date("dmY");
            }
            $_REQUEST['answer_title'] = str_replace("\\", "", $_REQUEST['answer_title']);
            $_REQUEST['answer_title'] = str_replace("/", "", $_REQUEST['answer_title']);
            $_REQUEST['answer_title'] = str_replace("..", "", $_REQUEST['answer_title']);
            $path_tmp = $_SESSION['config']['tmppath'].DIRECTORY_SEPARATOR.$_REQUEST['answer_title'].".maarch";
            $myfile = fopen($path_tmp, "w");
            if (!$myfile) {
                $_SESSION['error'] .= _FILE_OPEN_ERROR.'.<br/>';
                header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&module=templates&page=generate_attachment_html&template=".$_REQUEST['template_id']);
                exit;
            }
            fwrite($myfile, $_REQUEST['template_content']);
            fclose($myfile);            
            //DOCSERVER
            if (!isset($_SESSION['collection_id_choice']) 
                || empty($_SESSION['collection_id_choice'])
            ) {
                $_SESSION['collection_id_choice'] 
                    = $_SESSION['user']['collections'][0];
            }
            $tmpPath = $path_tmp;
            $fileSize = filesize($tmpPath);
            require_once('core' . DIRECTORY_SEPARATOR . 'class' 
                . DIRECTORY_SEPARATOR . 'docservers_controler.php');
            $docserverControler = new docservers_controler();
            $fileInfos = array(
                "tmpDir"      => $_SESSION['config']['tmppath'],
                "size"        => $fileSize,
                "format"      => 'maarch',
                "tmpFileName" => $_REQUEST['answer_title'] . '.maarch',
            );
            $storeResult = array();
            $storeResult = $docserverControler->storeResourceOnDocserver(
                $_SESSION['collection_id_choice'], $fileInfos
            );
            if (isset($storeResult['error']) && $storeResult['error'] <> "") {
                $_SESSION['error'] = $storeResult['error'];
                
            } else {
                $pathTemplate = $storeResult['path_template'];
                $destinationDir = $storeResult['destination_dir'];
                $docserverId = $storeResult['docserver_id'];
                $fileDestinationName = $storeResult['file_destination_name'];
            }
            //INDEXING
            $res_attach = new resource();
            $_SESSION['data'] = array();
            array_push($_SESSION['data'], array('column' => "typist", 'value' => $_SESSION['user']['UserId'], 'type' => "string"));
            array_push($_SESSION['data'], array('column' => "format", 'value' => 'maarch', 'type' => "string"));
            array_push($_SESSION['data'], array('column' => "docserver_id", 'value' => $docserverId, 'type' => "string"));
            array_push($_SESSION['data'], array('column' => "status", 'value' => 'NEW', 'type' => "string"));
            array_push($_SESSION['data'], array('column' => "offset_doc", 'value' => ' ', 'type' => "string"));
            array_push($_SESSION['data'], array('column' => "logical_adr", 'value' => ' ', 'type' => "string"));
            array_push($_SESSION['data'], array('column' => "title", 'value' => $_REQUEST['answer_title'], 'type' => "string"));
            array_push($_SESSION['data'], array('column' => "coll_id", 'value' => $_SESSION['collection_id_choice'], 'type' => "string"));
            array_push($_SESSION['data'], array('column' => "res_id_master", 'value' => $_SESSION['doc_id'], 'type' => "integer"));
            array_push($_SESSION['data'], array('column' => "type_id", 'value' => 0, 'type' => "int"));
            $id = $res_attach->load_into_db(
                $_SESSION['tablename']['attach_res_attachments'],
                $destinationDir,
                $fileDestinationName,
                $pathTemplate,
                $docserverId,
                $_SESSION['data'],
                $_SESSION['config']['databasetype']
            );
            if ($id == false) {
                $_SESSION['error'] = $res_attach->get_error();
                header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&module=templates&page=generate_attachment_html&template=".$_REQUEST['template_id']."&mode=".$_REQUEST['mode']);
                exit();
            } else {
                if ($_SESSION['history']['attachadd'] == "true") {
                    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                    $hist = new history();
                    //$_SESSION['error'] = _NEW_ATTACH_ADDED;
                    $hist->add($_SESSION['tablename']['attach_res_attachments'], $id, "ADD", 'attachadd', _NEW_ATTACH_ADDED." (".$_REQUEST['answer_title'].") ", $_SESSION['config']['databasetype'],'attachments');
                    $sec = new security();
                    $view = $sec->retrieve_view_from_coll_id($_SESSION['collection_id_choice']);
                    $hist->add($view, $_SESSION['doc_id'], "ADD", 'attachadd', ucfirst(_DOC_NUM) . $id . ' ' . _NEW_ATTACH_ADDED . ' ' . _TO_MASTER_DOCUMENT . $_SESSION['doc_id'], $_SESSION['config']['databasetype'], 'apps');
                }
            }
            if (empty($_SESSION['error']) || $_SESSION['error'] == _NEW_ATTACH_ADDED) {
                ?>
                <script type="text/javascript">
                    var eleframe1 =  window.opener.top.document.getElementById('list_attach');
                    eleframe1.src = '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=attachments&page=frame_list_attachments';
                    window.top.close();
                </script>
                <?php
                exit();
            }
        } else {
            //mode = up 
            if (empty($_REQUEST['id']) || !isset($_REQUEST['id'])) {
                $_SESSION['error'] .= _ANSWER_OPEN_ERROR.'.<br/>';
                header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&module=templates&page=generate_attachment_html&template=".$_REQUEST['template_id']);
                exit;
            } else {
                $stmt = $conn->query("select docserver_id, path, filename from ".$_SESSION['tablename']['attach_res_attachments']." where res_id = ? ", 
									array($_REQUEST['id'])
						);
                if ($stmt->rowCount() == 0) {
                    $_SESSION['error'] = _NO_DOC_OR_NO_RIGHTS."...";
                    ?>
                    <script  type="text/javascript">
                        var eleframe1 =  window.opener.top.frames['process_frame'].document.getElementById('list_attach');
                        eleframe1.src = '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=attachments&page=frame_list_attachments';
                        window.top.close();
                    </script>
                    <?php
                    exit;
                } else {
                    $line = $stmt->fetchObject();
                    $docserver = $line->docserver_id;
                    $path = $line->path;
                    $filename = $line->filename;
                    $stmt = $conn->query("select path_template from ".$_SESSION['tablename']['docservers']." where docserver_id = ? ", 
									array($docserver)
								);
                    $line_doc = $stmt->fetchObject();
                    $docserver = $line_doc->path_template;
                    $file = $docserver.$path.strtolower($filename);
                    $file = str_replace("#",DIRECTORY_SEPARATOR,$file);
                    $myfile = fopen($file, "w");
                    if (!$myfile) {
                        $_SESSION['error'] .= _FILE_OPEN_ERROR.'.<br/>';
                        header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&module=templates&page=generate_attachment_html&id=".$_REQUEST['id']);
                        exit;
                    }
                    fwrite($myfile, $_REQUEST['template_content']);
                    fclose($myfile);
                    $stmt = $conn->query("update ".$_SESSION['tablename']['attach_res_attachments']." set title = ? where res_id = ? ", 
									array($_REQUEST['answer_title'], $_REQUEST['id'])
									);
                    if ($_SESSION['history']['attachup'] == "true") {
                        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                        $hist = new history();
                        $hist->add($_SESSION['tablename']['attach_res_attachments'], $_SESSION['courrier']['res_id'],"UP", 'attachup', _ANSWER_UPDATED."  (".$_SESSION['courrier']['res_id'].")", $_SESSION['config']['databasetype'],'attachments');

                    }
                    ?>
                    <script  type="text/javascript">
                        var eleframe1 =  window.opener.top.document.getElementById('list_attach');
                        eleframe1.src = '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=attachments&page=frame_list_attachments';
                        window.top.close();
                    </script>
                    <?php
                    exit();
                }
            }
        }
    }
}*/
