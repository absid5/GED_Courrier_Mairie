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

	exit("/!\ Erreur de Syntaxe !\nLa syntaxe est $argv[0] <fichier de conf xml> <xml de sortie>\n\n");

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


		/*Fonction qui va vérifier si l'utilisateur est dans la table des users ou non*/
		function verifUser($user_id, $db)
		{
			$qry = $db->prepare("SELECT * from users where upper(user_id) = upper(?)");	
			$qry->execute(array($user_id));
			//print_r($qry->errorInfo());
			while ($row = $qry->fetch()){
				$user_id = $row['user_id'];
				if($user_id == ''){
					return false; 
				}else{
					return true;
				}
			}
		}

		/*Fonction qui va vérifier les données de l'utilisateur dans la table users. Si il y a des données qui ne sont pas à jour, la fonction fait le update pour mettre à jour. */
		function verifUpdate($user_id,$firstname,$lastname,$phone,$mail,$db)
		{

			$qry = $db->prepare("SELECT * from users where upper(user_id) = upper(?) and firstname = ? and lastname = ? and phone = ? and mail = ? and (status = 'OK' or status = 'ABS')");	
			$qry->execute(array($user_id,$firstname,$lastname,$phone,$mail));
			$result = $qry->fetchAll();
			if($result==null){
				echo "Les donnees doivent etre mis a jour !";
				$qry = $db->prepare("UPDATE users set  user_id = ?, firstname = ? , lastname = ? , phone = ? , mail = ? where upper(user_id) = upper(?) ");	
				$result = $qry->execute(array($user_id,$firstname,$lastname,$phone,$mail,$user_id));
				$result = $qry->fetchAll();
				if(!$result){
					echo "/!\ données non mises à jours!";
				}else{
					echo "... données mises à jour! ...\n";
				}
			}else{
				echo "... les donnees de $user_id sont a jour ...\n";
				return true;
			}
		}

		/*Function qui va insérer l'utilisateur dans la table des users. Si l'utilisateur n'est pas présent, on lui done le mot de passe de maarch*/
		function insertUser($user_id,$firstname,$lastname,$phone,$mail,$db)
		{

			$qry = $db->prepare("INSERT into users (user_id, password, firstname, lastname, phone, mail, enabled, change_password, status,loginmode) values (?,?,?,?,?,?,?,?,?,?)");	
			$qry->execute(array($user_id,'65d1d802c2c5e7e9035c5cef3cfc0902b6d0b591bfa85977055290736bbfcdd7e19cb7cfc9f980d0c815bbf7fe329a4efd8da880515ba520b22c0aa3a96514cc',$firstname,$lastname,$phone,$mail,'Y','Y','OK','standard'));
			$result = $qry->fetchAll();
			if(!$result){
				echo "/!\ L'utilisateur $user_id n'a pas ete insere !\n";
				return false;
			}else{
				echo "... l'utilisateur $user_id a été ajouté ...\n";
				return true;
			}
		}

		/*Fonction qui change le status d'un user s'il n'est plus dans le ldap*/
		function changeStatusUsers($idUsersTab,$db)
		{
			$query = "select user_id from users where status not in ('DEL') and ";
			$query .= "user_id not in ('".implode("','",$idUsersTab)."')";	
			$qry = $db->prepare($query);	
			$qry->execute(array());
			$result = $qry->fetchAll();
			if($result == null){
				echo "Aucun utilisateur n'a besoin d'être passé en DEL\n";
				return true;
			}else{
				echo "Des utilisateurs doivent changer de status !\n";

				$qry = $db->prepare("UPDATE users set status = 'DEL' where user_id in (select user_id from users where status not in ('DEL') and user_id not in ('".implode("','",$idUsersTab)."')) ");	
				$qry->execute();
				$result = $qry->fetchAll();
				if(!$result){
					echo "/!\ Aucun utilisateur n'a pu etre passé en DEL !\n";
				}else{
					echo "... des utilisateur ont été passé en DEL ...\n";
				}
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

/**
Chargement du fichier xml
*/

$dom = new DomDocument();
echo "... TRAITEMENT du fichier $fichier ...\n";
if(!($dom->load('../xml/'.$fichier.'.xml')))
{
	//echo "fichier : ".$fichier;
	$event = "/!\ Unable to load : " . $fichier.'.xml'."\n";
	echo $event;
	//ecrire_log($event,$nomFichier);
	exit();
}else{
	$event = "Able to load : " . $fichier.'.xml'."\n";
	echo $event;
	//ecrire_log($event,$nomFichier);
}


/*On compte le nombre d'item dans le fichier xml. Ceci est réalisé car le nom de la balise est item suivi d'un chiffre*/

for($m = 0; ;$m++)
{
	//echo 'test';
	$nomItem = 'item_'.$m;
	$list = $dom->getElementsByTagName("ldap_info")->item(0);
	//print_r($list);
	$listItem = $list->getElementsByTagName($nomItem)->item(0);
	if($listItem == NULL){break;}
}


/**
Lecture du fichier ldap.xml des users pour mise à jours des données des tables users et users_entities
*/

$event = "Lecture du fichier $nomFichier.xml pour mise à jours des données de la table users"."\n";
echo $event;
ecrire_log($event,$nomFichier);
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
		//ecrire_log($event,$nomFichier);
		break;
	}
	$firstname = infoBalise($listItem, 'xml_firstname');
	$lastname = infoBalise($listItem, 'xml_lastname');
	$phone = infoBalise($listItem, 'xml_phone');
	$mail = infoBalise($listItem, 'xml_mail');
	
	echo("=============== $user_id ===============\n");
    echo("-------------Informations-------------\n");
    echo "+ xml :     $nomItem\n";
    echo "+ user_id : $user_id\n";
    echo "+ firstname : $firstname\n";
    echo "+ lastname : $lastname\n";
    echo "+ phone : $phone\n";
    echo "+ mail : $mail\n";
    echo("--------------------------------------\n\n");

	$idEntitiesTab = array();

	array_push($idUsersTab, $user_id);



	//On vérifie le status des users. S'ils sont inexistants, on les ajoute. S'ils ont des données manquantes, on les met à jour. En revanche on ne supprime pas les utilisateurs. On les passe en DEL
	$isUser=verifUser($user_id,$db);
	print_r("\n");
	if($isUser){
		echo "... L'utilisateur $user_id existe deja ...\n";
		verifUpdate($user_id,$firstname,$lastname,$phone,$mail,$db);
	}else{
		echo "... L'utilisateur $user_id n'existe pas, il va etre insere dans la base de donnees ...\n";
		insertUser($user_id,$firstname,$lastname,$phone,$mail,$db);
	}
	echo "============================================\n\n";
}

echo "\n... Vérification du status des users ...\n";

changeStatusUsers($idUsersTab,$db); //Fonction qui va permettre de passer en DEL les users qui ne sont pas dans le LDAP.XML






?>
