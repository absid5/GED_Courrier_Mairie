<?php

//FOR ADD, UP TEMPLATES AND ADD ATTCHMENTS
//case of template, templateStyle, or new attachment generation

$func = new functions();

if ($objectType == 'templateStyle') {
    // a new template
    $fileExtension = $func->extractFileExt($objectId);
    $fileNameOnTmp = 'tmp_file_' . $_SESSION['user']['UserId']
        . '_' . rand() . '.' . $fileExtension;
    $filePathOnTmp = $_SESSION['config']['tmppath'] . $fileNameOnTmp;
    if (!copy($objectId, $filePathOnTmp)) {
        $result = array('ERROR' => _FAILED_TO_COPY_ON_TMP 
            . ':' . $objectId . ' ' . $filePathOnTmp
        );
        createXML('ERROR', $result);
    }
} elseif ($objectType == 'template' || $objectType == 'attachmentFromTemplate' || $objectType == 'attachmentVersion' || $objectType == 'outgoingMail' || $objectType == 'transmission') {
    if ($_SESSION['m_admin']['templates']['current_style'] <> '') {
        // edition in progress
        $fileExtension = $func->extractFileExt(
            $_SESSION['m_admin']['templates']['current_style']
        );
        $filePathOnTmp = $_SESSION['m_admin']['templates']['current_style'];
    } else {
        //new attachment from a template
        if (isset($_SESSION['cm']['resMaster']) && $_SESSION['cm']['resMaster'] <> '') {
            $sec = new security();
            $collId = $sec->retrieve_coll_id_from_table($objectTable);
            $res_view = $sec->retrieve_view_from_table($objectTable);
            $_SESSION['cm']['collId'] = $collId;
        }
        // new edition
        require_once 'modules/templates/class/templates_controler.php';
        $templateCtrl = new templates_controler();
        $params = array(
            'res_id' => $_SESSION['cm']['resMaster'],
            'coll_id' => $_SESSION['cm']['collId'],
            'res_view' => $res_view,
            'res_table' => $objectTable,
            'res_contact_id' => $_SESSION['cm']['contact_id'],
            'res_address_id' => $_SESSION['cm']['address_id'],
            'chronoAttachment' => $_SESSION['cm']['chronoAttachment']
            );
        if ($objectType == 'attachmentFromTemplate' || $objectType == 'attachmentVersion' || $objectType == 'outgoingMail' || $objectType == 'transmission') {
            $filePathOnTmp = $templateCtrl->merge($objectId, $params, 'file');
            $templateObj = $templateCtrl->get($objectId);
            $_SESSION['cm']['templateStyle'] = $templateObj->template_style;
        } elseif ($objectType == 'template') {
            $filePathOnTmp = $templateCtrl->copyTemplateOnTmp($objectId);
            if ($filePathOnTmp == '') {
                $result = array('ERROR' => _FAILED_TO_COPY_ON_TMP 
                    . ':' . $objectId . ' ' . $filePathOnTmp);
                createXML('ERROR', $result);
            }
        }
        $fileExtension = $func->extractFileExt($filePathOnTmp);
    }
}
