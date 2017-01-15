<?php 
if (isset($_REQUEST['template_style']) && !empty($_REQUEST['template_style'])) {
    for (
        $cptStyle = 0;
        $cptStyle < count($_SESSION['m_admin']['templatesStyles']);
        $cptStyle ++
    ) {
        if (
            $_SESSION['m_admin']['templatesStyles'][$cptStyle]['fileExt'] 
            . ': ' . $_SESSION['m_admin']['templatesStyles'][$cptStyle]['fileName']
            == $_REQUEST['template_style']
        ) {
            $_SESSION['m_admin']['templates']['current_style'] 
                = $_SESSION['m_admin']['templatesStyles'][$cptStyle]['filePath'];
        }
    }
    
    echo "{status : 0, " . $_SESSION['m_admin']['templates']['current_style'] . "}";
    exit();
} else {
    echo "{status : 1}";
}
exit;
