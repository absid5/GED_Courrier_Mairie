<?php

if($_SESSION['service_tag'] == 'doctype_up')
{
    $db = new Database();
    $stmt = $db->query("SELECT * FROM ".$_SESSION['tablename']['mlb_doctype_ext']." WHERE type_id = ?", array($_SESSION['m_admin']['doctypes']['TYPE_ID']));

    if($stmt->rowCount() == 0)
    {
        $_SESSION['m_admin']['doctypes']['process_delay'] = 21;
        $_SESSION['m_admin']['doctypes']['delay1'] = 14;
        $_SESSION['m_admin']['doctypes']['delay2'] = 1;
        $_SESSION['m_admin']['doctypes']['sve_type'] = 'NORMAL';
    }
    else
    {
        $line = $stmt->fetchObject();
        //var_dump($line);
        $_SESSION['m_admin']['doctypes']['process_delay'] = $line->process_delay;
        $_SESSION['m_admin']['doctypes']['delay1'] = $line->delay1;
        $_SESSION['m_admin']['doctypes']['delay2'] = $line->delay2;
        $_SESSION['m_admin']['doctypes']['sve_type'] = $line->process_mode;
        //var_dump($_SESSION['m_admin']['doctypes']['sve_type']);
    }
}
elseif($_SESSION['service_tag'] == 'doctype_add')
{
    $_SESSION['m_admin']['doctypes']['process_delay'] = 21;
    $_SESSION['m_admin']['doctypes']['delay1'] = 14;
    $_SESSION['m_admin']['doctypes']['delay2'] = 1;

}
elseif($_SESSION['service_tag'] == 'frm_doctype')
{
    $func = new functions();
    ?>
    <p>
        <label for="label"><?php echo _PROCESS_DELAY;?> : </label>
        <input name="process_delay" type="text" class="textbox" id="label" maxlength="4" value="<?php functions::xecho($func->show_str($_SESSION['m_admin']['doctypes']['process_delay']));?>"/>
    </p>
    <p>
        <label for="label"><?php echo _ALARM1_DELAY;?> : </label>
        <input name="delay1" type="text" class="textbox" id="label" maxlength="4" value="<?php functions::xecho($func->show_str($_SESSION['m_admin']['doctypes']['delay1']));?>"/>
    </p>
    <p>
        <label for="label"><?php echo _ALARM2_DELAY;?> : </label>
        <input name="delay2" type="text" class="textbox" id="label" maxlength="4" value="<?php functions::xecho($func->show_str($_SESSION['m_admin']['doctypes']['delay2']));?>"/>
    </p>
    
    <p>
        <label for="label"><?php echo _PROCESSING_MODE;?> : </label>
        
    <?php
        // Variable qui ajoutera l'attribut selected de la liste déroulante
        $selected = '';
 
        // Parcours du tableau
        echo '<select name="process">';
        if(isset($_SESSION['m_admin']['doctypes']['sve_type'])){
            $selected = $_SESSION['m_admin']['doctypes']['sve_type'];
            echo "\t",'<option value="'.$selected.'" selected="selected">'.$selected.'</option>',"\n";
        }
        foreach($_SESSION['processing_modes'] as $process)
        {
            // Affichage de la ligne
            echo "\t",'<option value="', $process ,'"', $selected ,'>', $process ,'</option>',"\n";
            // Remise à zéro de $selected
            $selected='';
        }
        echo '</select>',"\n";
      ?>
    </p>

    


    <?php
}
elseif($_SESSION['service_tag'] == "doctype_info")
{
    $func = new functions();
    if(isset($_REQUEST['process_delay']) && $_REQUEST['process_delay'] >= 0)
    {
        $_SESSION['m_admin']['doctypes']['process_delay'] = $func->wash($_REQUEST['process_delay'], "num", _PROCESS_DELAY);
    }
    if(isset($_REQUEST['delay1']) && $_REQUEST['delay1'] >= 0)
    {
        $_SESSION['m_admin']['doctypes']['delay1'] = $func->wash($_REQUEST['delay1'], "num", _ALERT_DELAY_1);
    }
    if(isset($_REQUEST['delay2']) &&  $_REQUEST['delay2'] >= 0)
    {
        $_SESSION['m_admin']['doctypes']['delay2'] = $func->wash($_REQUEST['delay2'], "num", _ALERT_DELAY_2);
    }
}
elseif($_SESSION['service_tag'] == "doctype_updatedb")
{
    $db = new Database();

    $stmt = $db->query("SELECT type_id FROM ".$_SESSION['tablename']['mlb_doctype_ext']." WHERE type_id = ?", array($_SESSION['m_admin']['doctypes']['TYPE_ID']));
    if($stmt->rowCount() > 0)
    {
        $db->query("UPDATE ".$_SESSION['tablename']['mlb_doctype_ext']." SET process_delay = ?, delay1 = ?, delay2 = ?, process_mode = ? WHERE type_id = ?",
            array($_SESSION['m_admin']['doctypes']['process_delay'], $_SESSION['m_admin']['doctypes']['delay1'], $_SESSION['m_admin']['doctypes']['delay2'], $_REQUEST['process'], $_SESSION['m_admin']['doctypes']['TYPE_ID']));
    }
    else
    {
        $db->query("INSERT INTO ".$_SESSION['tablename']['mlb_doctype_ext']." (type_id, process_delay, delay1, delay2, process_mode) VALUES (?, ?, ?, ?, ?)",
        array($_SESSION['m_admin']['doctypes']['TYPE_ID'], $_SESSION['m_admin']['doctypes']['process_delay'], $_SESSION['m_admin']['doctypes']['delay1'], $_SESSION['m_admin']['doctypes']['delay2'], $_REQUEST['process']));
    }

}
elseif($_SESSION['service_tag'] == "doctype_insertdb")
{
    $db = new Database();
    $db->query("INSERT INTO ".$_SESSION['tablename']['mlb_doctype_ext']." (type_id, process_delay, delay1, delay2, process_mode) VALUES (?, ?, ?, ?, ?)",
            array($_SESSION['m_admin']['doctypes']['TYPE_ID'], $_SESSION['m_admin']['doctypes']['process_delay'], $_SESSION['m_admin']['doctypes']['delay1'], $_SESSION['m_admin']['doctypes']['delay2'], $_REQUEST['process']));
}
elseif($_SESSION['service_tag'] == "doctype_delete")
{
    $db = new Database();
    $db->query("DELETE FROM ".$_SESSION['tablename']['mlb_doctype_ext']." WHERE type_id = ?", array($_SESSION['m_admin']['doctypes']['TYPE_ID']));
}
