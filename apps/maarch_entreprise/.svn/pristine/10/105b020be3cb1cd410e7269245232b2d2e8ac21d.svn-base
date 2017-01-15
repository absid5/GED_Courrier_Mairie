<?php

/* View */
if ($viewResourceArr['status'] <> 'ko') {
    if (strtolower($viewResourceArr['mime_type']) == 'application/maarch') {
        ?>
        <head><meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></head>
        <!--<body id="validation_page" 
        onload="javascript:moveTo(0,0);">
            <div id="template_content" style="width:100%;">-->
            <?php echo $content;?>
            <!--</div>
        </body>
        </html>-->
        <?php
    } else {
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: public');
        header('Content-Description: File Transfer');
        header('Content-Type: ' . strtolower($viewResourceArr['mime_type']));
        header('Content-Disposition: inline; filename=' . basename(
                    'maarch.' . strtolower($viewResourceArr['ext'])
               )
               . ';');
        header('Content-Transfer-Encoding: binary');
        readfile($filePathOnTmp);
        exit();
    }
} else {
    $core_tools->load_html();
    $core_tools->load_header('', true, false);
    echo '<body>';
    echo '<br/><div class="error">' . $viewResourceArr['error'] . '</div>';
    echo '</body></html>';
    exit();
}
