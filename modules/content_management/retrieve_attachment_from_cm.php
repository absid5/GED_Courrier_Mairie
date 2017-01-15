<?php
//case of res -> update attachment
require_once 'modules/attachments/attachments_tables.php';
$dbAttachment = new Database();
$query = "SELECT relation, docserver_id, path, filename, format 
        FROM res_view_attachments 
        WHERE (res_id = ? OR res_id_version = ?) AND res_id_master = ? ORDER BY relation desc";

$stmt = $dbAttachment->query($query, array($objectId, $objectId, $_SESSION['doc_id']));

if ($stmt->rowCount() == 0) {
    $result = array('ERROR' => _THE_DOC . ' ' . _EXISTS_OR_RIGHT);
    createXML('ERROR', $result);
} else {
    $line = $stmt->fetchObject();
    $docserver = $line->docserver_id;
    $path = $line->path;
    $filename = $line->filename;
    $format = $line->format;
    $query = "select path_template from " . _DOCSERVERS_TABLE_NAME
        . " where docserver_id = ?";
    $stmt = $dbAttachment->query($query, array($docserver));
    $func = new functions();
    $lineDoc = $stmt->fetchObject();
    $docserver = $lineDoc->path_template;
    $fileOnDs = $docserver . $path . $filename;
    $fileOnDs = str_replace('#', DIRECTORY_SEPARATOR, $fileOnDs);
    $fileExtension = $func->extractFileExt($fileOnDs);
    $fileNameOnTmp = 'tmp_file_' . $_SESSION['user']['UserId']
        . '_' . rand() . '.' . $fileExtension;
    $filePathOnTmp = $_SESSION['config']['tmppath'] . $fileNameOnTmp;
    if (!copy($fileOnDs, $filePathOnTmp)) {
        $result = array('ERROR' => _FAILED_TO_COPY_ON_TMP 
            . ':' . $fileOnDs . ' ' . $filePathOnTmp
        );
        createXML('ERROR', $result);
    }
}
