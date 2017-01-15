<?php
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$filename = $_SESSION['config']['tmppath'].$_GET['filename'].'.jnlp';
$name = 'DisCM.jnlp';
$size = filesize($filename);
// force le téléchargement du fichier avec un beau nom
header("Content-Type: application/x-java-jnlp-file");
header('Content-Disposition: attachment; filename="'.$name.'"');
 
// indique la taille du fichier à télécharger
header("Content-Length: ".$size);
 
// envoi le contenu du fichier
readfile($filename);
