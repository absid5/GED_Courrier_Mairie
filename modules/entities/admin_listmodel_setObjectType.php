<?php
# AJAX script to load objectType/difflist_type onto session

require_once 'modules/entities/class/class_manage_listdiff.php';
$difflist = new diffusion_list();

$objectType = $_REQUEST['objectType'];

$_SESSION['m_admin']['entity']['difflist_type'] = $difflist->get_difflist_type($objectType);

echo str_replace(' ', ', ', trim($_SESSION['m_admin']['entity']['difflist_type']->difflist_type_roles));
