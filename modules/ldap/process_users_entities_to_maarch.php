<?php
session_start();
//on charge le fichier config.xml du dossier apps/maarch_entreprise/xml pour pouvoir récupérer les données de connexion à la base de données.
//$xml = simplexml_load_file('../../../apps/maarch_entreprise/xml/config.xml');
$nomFichier = date('Y-m-d_H-i-s') . '.log'; //nom du fichier log enregistrer dans le dossier logLdap

$fichier = 'ldap_users';

echo "... CHARGEMENT DU FICHIER DE CONFIGURATION ...\n";
if( !isset($argv) ){

    exit(htmlentities("Ce script ne peut-etre appelé qu'en PHP CLI"));

}else if( isset($argv) && count($argv) < 2){

    exit("Erreur de Syntaxe !\nLa syntaxe est $argv[0] <fichier de conf xml> <xml de sortie>\n\n");

}else if(!file_exists($argv[1])){

    exit("/!\ Le fichier de configuration n'existe pas!\n\n");

}else{

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
unset($i);
$DnsEntities=array();
foreach ($dn_and_filter as $key => $value) {
    $DnsEntities[]=$value['id'];
}
//print_r($DnsEntities);

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
}
catch(PDOException $e) {
    $db = null;
    echo '/!\ Erreur de connexion: ' . $e->getMessage()."\n\n";
    exit;
}

$xp_ldap_conf = new domxpath($ldap_conf);

foreach($xp_ldap_conf->query("/root/config/*") as $cf)
    ${$cf->nodeName} = $cf->nodeValue;

//Si une class custom est définie

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

/**
Les Fonctions
*/


/* 
Fonction qui permet de récupérer l'information de la balise concernée dans le fichier xml
**/
function infoBalise($description, $balise)
{
    if($description == NULL) {
        return $contenu = '';

    }else{
        $contenu = NULL;
        $contenubalise = $description ->getElementsByTagName($balise);
        foreach($contenubalise as $contenu){
            $contenu = $contenu->firstChild->nodeValue . "";
        }
        //echo $contenu."\n";
        return $contenu ;
    }
}


    /*Fonction qui va llire les memberofs du ldap.xml*/
    function infoMemberOf($description, $balise,$OU)
    {
        if($description == NULL) {
            return $contenu = '';}
            else{
                $contenu = NULL;
                $contenubalise = $description ->getElementsByTagName($balise);
                foreach($contenubalise as $contenu)
                    $contenu = $contenu->firstChild->nodeValue . "";
                $nomGroupe = strstr($contenu, $OU, true);
                if($nomGroupe != false and $contenu != null){return $contenu;}elseif($nomGroupe == false and $contenu == null){return null;}elseif($nomGroupe == false and $contenu !=null){return ok;}
            }
        }


        /*Fonction qui va vérifier si l'utilisateur est dans la table des users ou non*/
        function verifUser($user_id, $db)
        {
            $qry = $db->prepare("SELECT * from users where upper(user_id) = upper(?)"); 
            $qry->execute(array($user_id));
            while ($row = $qry->fetch()){
                $user_id = $row['user_id'];
                if($user_id == null){echo "le pseudo $user_id n'a pas été trouvé dans la base !";
                return false; }else{echo "le pseudo $user_id a été trouvé dans la base";
                return true;}
            }
        }

        /*Fonction qui va vérifier les données de l'utilisateur dans la table users. Si il y a des données qui ne sont pas à jour, la fonction fait le update pour mettre à jour. */
        function verifUpdate($user_id,$firstname,$lastname,$phone,$mail,$employeNumber,$db)
        {

            $qry = $db->prepare("SELECT * from users where upper(user_id) = upper(?) and firstname = ? and lastname = ? and phone = ? and mail = ? and custom_t3 = ? and status = 'OK'");   
            $qry->execute(array($user_id,$firstname,$lastname,$phone,$mail,$employeNumber));
            $result = $qry->fetchAll();
            if($result==null){

                echo "les donnees doivent etre mis a jour !";
                $qry = $db->prepare("UPDATE users set  user_id = ?, firstname = ? , lastname = ? , phone = ? , mail = ?,custom_t3 = ?, status = 'OK', loginmode = 'standard' where upper(user_id) = upper(?) ");    
                $result = $qry->execute(array($user_id,$firstname,$lastname,$phone,$mail,$employeNumber,$user_id));
                $result = $qry->fetchAll();
                if($result==null){
                    echo "Error, données non mises à jours!";
                }else{
                    echo "données mises à jour!";
                }
            }else{
                echo "les donnees de users sont a jour \n";
                return true;
            }
        }

        /*Function qui va insérer l'utilisateur dans la table des users. Si l'utilisateur n'est pas présent, on lui done le mot de passe de maarch*/
        function insertUser($user_id,$firstname,$lastname,$phone,$mail,$employeNumber,$db)
        {

            $qry = $db->prepare("INSERT into users (user_id, password, firstname, lastname, phone, mail, custom_t3, enabled, change_password, status,loginmode) values (upper(?),'ef9689be896dacd901cae4f13593e90d',?,?,?,?,?,'Y','Y','OK','standard')");   
            $qry->execute(array($user_id,$firstname,$lastname,$phone,$mail,$employeNumber));
            $result = $qry->fetchAll();
            if($result==null){
                echo "Error : les donnees n'ont pas ete ajouté !\n";
                return false;
            }else{
                echo "la ligne a été ajouté \n";
                return true;
            }
        }


        /*Fonction qui va supprimer tout les users de la table users_entities lorsque les entitées ne sont pas présente dans le ldap*/
        function deleteUsersEntities($idEntitiesTab,$user_id, $db)
        {

            $qry ="SELECT * from users_entities where user_id ='$user_id' and ";
            $qry .= " entity_id not in ('".implode("','",$idEntitiesTab)."')";
            $qry = $db->prepare($qry);
            $qry->execute();
            $result = $qry->fetchAll();
            if(!empty($result)){
                echo "Dissociation des anciens services affectes aux utilisateurs ...\n";
                $qry = "delete from users_entities where user_id ='$user_id' and ";
                $qry .= " entity_id not in ('".implode("','",$idEntitiesTab)."')";
                $qry = $db->prepare($qry);
                $qry->execute();
                $result = $qry->fetchAll();
            }else{
                echo "les services sont a jour pour l'utilisateur $user_id! \n";
            }
        }



        /*Fontion qui va écrire dans le fichier log . Cela permet de faire un suivie du processus*/
        function ecrire_log($event,$nomFichier){

/*        $fp = fopen('var/www/html/maarch_entreprise_prod/modules/ldap/logLdap/'.$nomFichier,'a+'); // ouvrir le fichier ou le créer
        fseek($fp,SEEK_END); // poser le point de lecture à la fin du fichier
        $nouverr=date('Y-m-d_H-i-s')." : ".$event."\r\n"; // ajouter un retour à la ligne au fichier
        fputs($fp,$nouverr); // ecrire ce texte
        fclose($fp); //fermer le fichier */
    }



    function seekEntityId($ldap_id, $db){

        $qry = $db->prepare("SELECT entity_id, entity_label from entities WHERE ldap_id= ? ");
        if($qry->execute(array($ldap_id))){
          while($row = $qry->fetch()){
              //echo "la valeur de la requete pour seekParentEntityId est : ".$row['entity_id']."\n";
              $info = $row['entity_id'];
          }
      }
      return $info;
  }


  function insertUserEntity($pseudo, $entity_id, $db){

    $is_primary='Y';
    $qry=$db->prepare("SELECT * from users_entities where user_id = ?");
    $qry->execute(array($pseudo));
    $result = $qry->fetchAll();
    if(empty($result)){
        $is_primary='Y';
    }else{
        $is_primary='N';
    }

    $qry=$db->prepare("SELECT * from users_entities where user_id = ? and entity_id = ? ");
    $result = null;
    $qry->execute(array($pseudo,$entity_id));
    $result = $qry->fetchAll();
    //print_r($qry->errorInfo());
    if($result != null){
        echo "les donnees de users_entities sont a jour \n";
    }else{
        echo "les donnees de users_entities doivent etre mis a jour!";
        $qry2=$db->prepare("INSERT into users_entities (user_id,entity_id, primary_entity) values (?,?,?)");
        $result2 = $qry2->execute(array($pseudo, $entity_id, $is_primary));
        //print_r($qry2->errorInfo());
        $result2 = $qry2->fetchAll();
        if($result2 ==null){
            echo "Error, aucun users_entities n'a ete ajoute \n";
        }else{ echo "Insertion du users_entities effectue! \n";}
    }
}

/**
Chargement du fichier xml
*/

$dom = new DomDocument();
echo "... TRAITEMENT du fichier $fichier ...\n";
if(!($dom->load('../xml/'.$fichier.'.xml')))
{
    //echo "fichier : ".$fichier;
    $event = "Unable to load : " . $fichier.'.xml'."\n";
    echo $event;
    //ecrire_log($event,$nomFichier);
    exit();
}else{
    $event = "able to load : " . $fichier.'.xml'."\n";
    echo $event;
    //ecrire_log($event,$nomFichier);
}


/*On compte le nombre d'item dans le fichier xml. Ceci est réalisé car le nom de la balise est item suivi d'un chiffre*/
for($m = 0; ;$m++)
{
    $nomItem = 'item_'.$m;
    $list = $dom->getElementsByTagName("ldap_info")->item(0);
    $listItem = $list->getElementsByTagName($nomItem)->item(0);
    if($listItem == NULL){break;}
}


/**
Lecture du fichier ldap.xml des users pour mise à jours des données des tables users et users_entities
*/


$idUsersTab= array('superadmin'); //Ce tableau est initialisé avec superadmin pour qu'il ne soit pas passé en DEL lors de la mise à jour des users.
/*Boucle qui permet de travailler sur les données contenues dans le fichier xml. On récupère les données puis on les insère dans la table users_entities*/
for($i = 0; $i<$m ; $i++)
{
    $nomItem = 'item_'.$i;

    $list = $dom->getElementsByTagName("ldap_info")->item(0);
    $listItem = $list->getElementsByTagName($nomItem)->item(0);

    $user_id = infoBalise($listItem, 'xml_user_id');

    if($user_id == NULL){
        $event = "Id du User de l'$nomItem absent, arret du processus";
        //ecrire_log($event,$nomFichier);break;
    }
    echo("=============== $user_id ===============\n");
    echo("-------------Informations-------------\n");
    echo "+ xml :     $nomItem\n";
    echo "+ user_id : $user_id\n";
    echo("--------------------------------------\n");
    /*On Recherche les memberOf*/

    echo ".\n";
    echo ".\n";
    echo ".\n";
    echo "... RECHERCHE DE MEMBER OF ...\n";
    echo ".\n";
    echo ".\n";
    echo ".\n\n";

    $user_entity = $listItem->getElementsByTagName('xml_user_entity')->item(0);

    /*On initialise le tableau idEntitiesTab pour stocker les entités récupérées des membersOf*/
    $user_entities = array();

    //La boucle permet de récupérer les données de chaques memberOf.
    for($j = 0; ; $j++)
    {
        $nomItem = 'xml_'.$j;
        $dnMemberof = infoBalise($user_entity, $nomItem);
        // on arrête la lecture des memberof si cnMemberof est null
        if($dnMemberof == ''){break;}
        //$pos = strpos($cnMemberof, $DnsEntities[$key]);
        if (preg_match('/'.$DnsEntities[0].'/', $dnMemberof)) {

            $entity_ldap_id = $ad->group_info($dnMemberof,array('objectguid'),$DnsEntities[0]);
            $entity_ldap_id=$entity_ldap_id['objectguid'];
            $entityId=seekEntityId($entity_ldap_id, $db);
            echo("-------------Entite associee-------------\n");
            echo "+ xml :       $nomItem\n";
            echo "+ DN entity : $dnMemberof\n";
            echo "+ ldap_id :   $entity_ldap_id\n";
            echo "+ entity_id : $entityId\n";
            echo("----------------------------------------\n");
            if(!empty($user_id) and !empty($entityId)){

                insertUserEntity($user_id,$entityId,$db);
            }
            $user_entities[]=$entityId;
        }
}

if($dnMemberof != ''){
    deleteUsersEntities($user_entities,$user_id,$db);
}

if($dnMemberof == '' && $j==0){
    echo "... PAS DE MEMBER OF! ...\n";
}
echo("========================================\n\n\n");
}


//print_r($idUsersTab);

?>
