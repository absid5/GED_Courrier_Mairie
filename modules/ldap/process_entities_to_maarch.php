<?php
session_start();

$fichier = 'ldap_entities';

echo "... CHARGEMENT DU FICHIER DE CONFIGURATION ...\n";
if( !isset($argv) ){
    exit(htmlentities("Ce script ne peut-etre appelé qu'en PHP CLI"));
}

else if( isset($argv) && count($argv) < 2){
    exit("/!\  Erreur de Syntaxe !\nLa syntaxe est $argv[0] <fichier de conf xml> <xml de sortie>\n\n");
}else if(!file_exists($argv[1])){
    exit("/!\ Le fichier de configuration n'existe pas!\n\n");
}
else
{
    $ldap_conf_file = trim($argv[1]);
    echo "Fichier OK !\n\n";
}
//Extraction de /root/config dans le fichier de conf
$ldap_conf = new DomDocument();
try
{
    $ldap_conf->load($ldap_conf_file);
}
catch(Exception $e)
{ 
    exit("/!\ Impossible de charger le document : ".$ldap_conf_file."\n
        Erreur : ".$e.getMessage."\n\n");
}

//**********************************//
//          FILTER AND DNs          //
//**********************************//
$i=0;
$xp_ldap_conf = new domxpath($ldap_conf);
foreach( $xp_ldap_conf->query("/root/filter/dn/@id") as $dn)
{
    
    $type = $xp_ldap_conf->query("/root/filter/dn[@id= '".$dn->nodeValue."']/@type")->item(0)->nodeValue;    //echo "for each filter ok\n";
    //$dn_and_filter[$i][$dn->nodeName] = $dn->nodeValue;
    //echo "nodename : ".$dn_and_filter[$i][$dn->nodeName]."\n";
    if($type=='entities'){
        $dn_and_filter[$i]['id'] = $dn->nodeValue;
    }
}
$xp_ldap_conf->query("/root/filter/dn/@id");
unset($i);
$DnsEntities=array();
foreach ($dn_and_filter as $key => $value) {
    $DnsEntities[]=$value['id'];
}
//print_r($DnsEntities);
//Arguments

//on charge le fichier config.xml pour pouvoir récupérer les données de connexion à la base de données.
$xml = simplexml_load_file($ldap_conf_file);
$host = $xml->config_base->databaseserver;
$dbname = $xml->config_base->databasename;
$user= $xml->config_base->databaseuser;
$password = $xml->config_base->databasepassword;

//echo 'host : '.$host;echo "\n";
//echo 'dbname :'.$dbname;echo "\n";
//echo 'user : '.$user;echo "\n";
//echo 'password :'.$password;echo "\n";

echo "... CONNEXION A LA BASE DE DONNEES MAARCH ...\n";

// Connexion, sélection de la base de données
try {
  $db = new PDO("pgsql:host=$host;dbname=$dbname", "$user", "$password");
  echo 'Connexion OK'."\n\n";
    //exit;
}
catch(PDOException $e) {
  $db = null;
  echo '/!\ Erreur de connexion: ' . $e->getMessage()."\n\n";
  exit;
}
//exit;
/**
Les Fonctions
*/

/* 
Fonction qui permet de récupérer l'information de la balise concernée dans le fichier xml
**/
function infoBalise($description, $balise)
{
    if($description == NULL) {
        return $contenu = '';}
        else{
            $contenu = NULL;
            $contenubalise = $description ->getElementsByTagName($balise);
            foreach($contenubalise as $contenu)
                $contenu = $contenu->firstChild->nodeValue . "";
            return $contenu ;
        }
    }


    function insertThisEntity($entityId, $entity_label,$xml_ldap_id, $db){
        $short_label = substr($entity_label, 0, 49);
        $qry = $db->prepare("INSERT into entities (entity_id, entity_label, short_label,entity_type,ldap_id, enabled) values (?,?,?,?,?,?) ");
        $qry->execute(array($entityId,$entity_label,$short_label,'Direction',$xml_ldap_id,'Y'));
        $result = $qry->fetchAll();
        if($result==null){
            echo "/!\ Error : L'entité $entity_label n'a pas été insérée !\n";

        }else{
            echo "... L'entité $entity_label a été insérée ...\n";
        }

    }

    function seekLdapId($xml_ldap_id,$db){

        $qry = $db->prepare("SELECT ldap_id from entities where ldap_id = ?");
        $qry->execute(array($xml_ldap_id));

        while($row = $qry->fetch()){
            $info = $row['ldap_id'];
        }
        return $info;   

    }

    function seekEntityId($xml_ldap_id,$db){

        $qry = $db->prepare("SELECT entity_id from entities where ldap_id = ?");
        $qry->execute(array($xml_ldap_id));
        while($row = $qry->fetch()){
            $info = $row['entity_id'];
        }
        return $info;   

    }


    function updateEntity($entityId, $xml_entity_label, $xml_entity_ldap_id, $xml_parent_entity_id, $db){
        $short_label = substr($xml_entity_label, 0, 49);
        if($xml_parent_entity_id != ''){
        //echo "UPDATE entities SET (entity_label,parent_entity_id) VALUES ('$xml_entity_label','$xml_parent_entity_id')  WHERE entity_id = '$entityId'";
            $qry = $db->prepare("UPDATE entities SET entity_label = ?, short_label = ?, parent_entity_id = ? WHERE entity_id = ?");
            $qry->execute(array($xml_entity_label,$short_label,$xml_parent_entity_id,$entityId));
        }else{
        //echo "UPDATE entities SET entity_label ='$xml_entity_label'  WHERE entity_id = '$entityId'";
            $qry = $db->prepare("UPDATE entities SET entity_label = ?, short_label = ?  WHERE entity_id = ?");
            $qry->execute(array($xml_entity_label,$short_label,$entityId));
        }
        $result = $qry->fetchAll();
        //print_r($qry->errorInfo());
        if(!$qry){
            echo "/!\ Error : les donnees n'ont pas ete mis a jour ! \n";
            print_r($qry->errorInfo());
        }else{
            echo "... les donnees ont ete mis a jour ...\n";
        }
    }

    function deleteOldEntities($tableau, $db)
    {
        //print_r($tableau);
        $entitiesXml= "'".implode("','",$tableau)."'";
        //echo $entitiesXml;
        $select = "update entities set enabled = 'N' where ldap_id not in ($entitiesXml)";
        //print_r($select);
        $qry= $db->prepare($select);
        $qry->execute(array());

    }

    function seekLastInsertId($db)
    {
        $info = null;
        $qry = $db->prepare("SELECT max(cast(substring(entity_id,5) as integer)) as id from entities order by id asc"); 
        $qry->execute(array());
        while($row = $qry->fetch()){
          $info = $row['id'];

      }
      if($info == null){return 100;}else{return $info;}
  }



/**
Chargement du fichier xml
*/

$xp_ldap_conf = new domxpath($ldap_conf);

foreach($xp_ldap_conf->query("/root/config/*") as $cf)
    ${$cf->nodeName} = $cf->nodeValue;

//Si une class custom est définie
echo "type ldap : ".$type_ldap."\n";
if( file_exists(dirname($ldap_conf_file)."/../class/class_".$type_ldap.".php") )
    include(dirname($ldap_conf_file)."/../class/class_".$type_ldap.".php");

//Sinon si la class est définie pour le module  
else if( file_exists(dirname($ldap_conf_file)."/../../../../../modules/ldap/class/class_".$type_ldap.".php") )
    include(dirname($ldap_conf_file)."/../../../../../modules/ldap/class/class_".$type_ldap.".php");

//Sinon
else
    exit("Impossible de charger class_".$type_ldap.".php\n");

//**********************************//
//          LDAP CONNECTION         //
//**********************************//

echo "... CONNEXION A L'ANNUAIRE $type_ldap ...\n";
//Try to create a new ldap instance
try
{
    if($prefix_login != ''){
        $login_admin =$prefix_login."\\".$login_admin;
    }
    $ad = new LDAP($domain,$login_admin,$pass,false);
    echo "Connexion Ldap ok\n\n";
}
catch(Exception $con_failure)
{
    exit("/!\ Impossible de se connecter à l'annuaire\n
        Erreur : ".$con_failure->getMessage()."\n\n");
}

/*Lecture du fichier xml des entités pour remplir la table entities*/

$dom = new DomDocument();
$dom->load('../xml/'.$fichier.'.xml');
//$tableau=array();

echo "... TRAITEMENT du fichier $fichier ...\n";

/*On compte le nombre d'item dans le fichier xml. Ceci est réalisé car le nom de la balise est item suivi d'un chiffre*/
for($m = 0; ; $m++)
{
    $nomItem = 'item_'.$m;
    $list = $dom->getElementsByTagName("ldap_info")->item(0);
    $listItem = $list->getElementsByTagName($nomItem)->item(0);
    if($listItem == NULL){break;}
}


/**
Lecture du fichier ldap.xml des entités pour mise à jours des données de la table entities
*/
/*Boucle qui permet de travailler sur les données contenues dans le fichier xml. On récupère les données puis on les insère dans la table entities en parcourant le fichier ldap.xml*/
//$ldapEntityId = 1000;
$array_ldap_id = array();


for($k = 0; $k<$m; $k++)
{
    $valeurLastInsert = seekLastInsertId($db);

    $entityId = $valeurLastInsert;

    $nomItemGroups = 'item_'.$k;

    $list = $dom->getElementsByTagName("ldap_info")->item(0);
    $listItem = $list->getElementsByTagName($nomItemGroups)->item(0);
    $xml_ldap_id = infoBalise($listItem, 'xml_ldap_id');
    $xml_entity_label = infoBalise($listItem, 'xml_entity_label');
    echo("=============== $xml_entity_label ===============\n");
    echo("-------------Informations-------------\n");
    echo "+ xml :     $nomItemGroups\n";
    echo "+ ldap_id : $xml_ldap_id\n";
    echo "+ entity_label : $xml_entity_label\n";
    echo("--------------------------------------\n\n");
    $db_ldap_id = seekLdapId($xml_ldap_id,$db);
    if($db_ldap_id == '' || $db_ldap_id == NULL){
        $entityId++;
        $entityId = 'ldap'.$entityId;
        echo "L'entite entity_label n'existe pas ...";
        echo "... generation de l'entity_id ... valeur ... $entityId\n";
        echo "... insertion de l'entité ...\n";
        insertThisEntity($entityId, $xml_entity_label, $xml_ldap_id, $db);


    }else{
        echo "L'entite existe deja ...\n";
        $entityId = seekEntityId($xml_ldap_id,$db);
        echo "... entity_id : $entityId ...\n";
        echo "... mise à jour ...\n";
        updateEntity($entityId, $xml_entity_label, $xml_ldap_id,'', $db);
        
    }
    $array_ldap_id[]= $xml_ldap_id;

    //Recuperation de la branche des parent_entity
    $listsParent = $listItem->getElementsByTagName("xml_parent_entity")->item(0);
    /*On compte le nombre d'item xml dans la balise xml_user_entity. Ceci est réalisé car le nom de la balise est item suivi d'un chiffre*/
    
    //$nb_parents = $listsParent->lastChild->previousSibling->tagName;
    $nb_parents = $listsParent->lastChild->tagName;

    //exit('NOMBRE PARENTS : '.$nb_parents);
    if($nb_parents != ''){
        $nb_parents = explode('_', $nb_parents);


        $nb_parents = $nb_parents[1];

        echo("\n-------------Entite Parente de $xml_entity_label-------------\n");
        for($q=0; $q<=$nb_parents; $q++){
            $nomXml = 'xml_'.$q;
            $parent_entityDn = infoBalise($listsParent, 'xml_'.$q);
            //echo $parent_entityDn."\n";

           
            if (preg_match('/'.$DnsEntities[0].'/', $parent_entityDn)) {
                $xml_parent_entity_ldap_id = $ad->group_info($parent_entityDn,array('objectguid'),$DnsEntities[0]);
                $xml_parent_entity_label = $ad->group_info($parent_entityDn,array('samaccountname'),$DnsEntities[0]);

                if(empty($xml_entity_label) || empty($xml_parent_entity_ldap_id)){
                    echo "Entité non trouvé dans l'AD!";
                    break;
                }

                $xml_parent_entity_ldap_id=$xml_parent_entity_ldap_id['objectguid'];
                $xml_parent_entity_label=$xml_parent_entity_label['samaccountname'];

                echo "+ xml :           $nomXml\n";
                echo "+ DN :            $parent_entityDn\n";
                echo "+ ldap_id :       $xml_parent_entity_ldap_id\n";
                echo "+ entity_label :  $xml_parent_entity_label\n\n";

                $db_parent_ldap_id = seekLdapId($xml_parent_entity_ldap_id,$db);
                if($db_parent_ldap_id == null){
                    $parentEntityId = $entityId;
                    $parentEntityId=explode('ldap', $parentEntityId);
                    $parentEntityId[1]++;
                    $parentEntityId = 'ldap'.$parentEntityId[1];
                    echo "L'entite $xml_parent_entity_label, parente de $xml_entity_label, n'existe pas\n";
                    echo "... insertion ...\n";
                    //ajouter function d'insertion de cette entite
                    insertThisEntity($parentEntityId, $xml_parent_entity_label, $xml_parent_entity_ldap_id, $db);

                    //echo "Mise à jour de l'entité $xml_entity_label, fille de $xml_parent_entity_label\n";
                    echo "... liaison de $xml_entity_label à $xml_parent_entity_label ...\n";
                    updateEntity($entityId, $xml_entity_label, $xml_ldap_id,$parentEntityId, $db);

                }else{

                    echo "L'entite $xml_parent_entity_label, parente de $xml_entity_label existe deja\n";
                    echo "... mise a jour ...\n";
                    $parentEntityId = seekEntityId($xml_parent_entity_ldap_id,$db);
                    updateEntity($parentEntityId, $xml_parent_entity_label, $xml_parent_entity_ldap_id, '', $db);

                    echo "... liaison de $xml_entity_label à $xml_parent_entity_label ...\n";
                    updateEntity($entityId, $xml_entity_label, $xml_ldap_id,$parentEntityId, $db);
                }

            }       
        }
        echo "-------------------------------------------\n";
    }
    echo("==========================================\n\n");
}
deleteOldEntities($array_ldap_id, $db);


?>
