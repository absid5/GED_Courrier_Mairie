<?php 

// function defination to convert array to xml
function array_to_xml($ldap_info, &$xml_ldap_info) {
	foreach($ldap_info as $key => $value) {
		if(is_array($value)) {
			if(!is_numeric($key)){
				$subnode = $xml_ldap_info->addChild("xml_$key");
				array_to_xml($value, $subnode);
			}
			else{
				$subnode = $xml_ldap_info->addChild("item_$key");
				array_to_xml($value, $subnode);
			}
		}
		else {
			$xml_ldap_info->addChild("xml_$key",htmlspecialchars("$value"));
		}
	}
}
function fusionTableau($list_users,$user_fields_nodename){
	$t4 = array();
	$nombreLigneTableau = count($list_users);
	//echo $nombreLigneTableau;
	for($i=0; $i<$nombreLigneTableau;$i++){
		foreach($list_users as &$values){
			$t1 = $list_users[$i];
		}
		$t1_ = array_values($t1);
		$t2 = $user_fields_nodename; 
		$t2_ = array_values($t2);
		$valeurUtilisateur = array();
		foreach ($t1_ as $o => $v1) {
			$v2 = $t2_[$o];
			$valeurUtilisateur[$v2] = $v1; 
		}
		$tableauComplet[]=$valeurUtilisateur;  
	}
	//var_dump($tableauComplet);
	return $tableauComplet;
}

//Arguments

if( !isset($argv) )
	exit(htmlentities("Ce script ne peut-etre appelé qu'en PHP CLI"));

else if( isset($argv) && count($argv) < 2)
	exit("Erreur de Syntaxe !\nLa syntaxe est $argv[0] <fichier de conf xml> <xml de sortie>");

else
{
	$ldap_conf_file = trim($argv[1]);
}

//Extraction de /root/config dans le fichier de conf
$ldap_conf = new DomDocument();
try
{
	$ldap_conf->load($ldap_conf_file);
}
catch(Exception $e)
{ 
	exit("Impossible de charger le document : ".$ldap_conf_file."\n
		Erreur : ".$e.getMessage."\n");
}


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
//			LDAP CONNECTION	      	//
//**********************************//

//Try to create a new ldap instance
try
{
	if($prefix_login != ''){
        $login_admin =$prefix_login."\\".$login_admin;
    }
	$ad = new LDAP($domain,$login_admin,$pass,false);
	//echo "connection Ldap ok\n";
}
catch(Exception $con_failure)
{
	exit("Impossible de se connecter à l'annuaire\n
		Erreur : ".$con_failure->getMessage()."\n");
}

//**********************************//
//				MAPPING	         	//
//**********************************//


//User
foreach( $xp_ldap_conf->query("/root/mapping/user/@* | /root/mapping/user/* | /root/mapping/user/*/@*")  as $us)
	if( !empty($us->nodeValue) ){
		//$us_user_fieldname = $us->nodeName;
		//$user_fields_nodename[$us->nodeName] = $us->nodeName;
		$user_fields[$us->nodeValue] = $us->nodeValue;
		//var_dump($user_fields);
	}

foreach( $xp_ldap_conf->query("/root/mapping/user/@* | /root/mapping/user/* | /root/mapping/user/*/@*")  as $us)
	if( !empty($us->nodeValue) ){
		//$us_user_fieldname = $us->nodeName;
		$user_fields_nodename[$us->nodeName] = $us->nodeName;
		//$user_fields[$us->nodeValue] = $us->nodeValue;
		//var_dump($user_fields_nodename);
	}
		
//Group
foreach( $xp_ldap_conf->query("/root/mapping/entity/@* | /root/mapping/entity/* | /root/mapping/entity/*/@*")  as $gs)
	if( !empty($gs->nodeValue) ){
		$group_fields_nodename[$gs->nodeName] = $gs->nodeName;
	}

foreach( $xp_ldap_conf->query("/root/mapping/entity/@* | /root/mapping/entity/* | /root/mapping/entity/*/@*")  as $gs)
	if( !empty($gs->nodeValue) ){
		$group_fields[$gs->nodeValue] = $gs->nodeValue;
	}

//**********************************//
//			FILTER AND DNs     		//
//**********************************//

$i=0;
foreach( $xp_ldap_conf->query("/root/filter/dn/@id") as $dn)
{
	//echo "for each filter ok\n";
	$dn_and_filter[$i][$dn->nodeName] = $dn->nodeValue;
	//echo "nodename : ".$dn_and_filter[$i][$dn->nodeName]."\n";
	if(empty($dn_and_filter[$i][$dn->nodeName])){
		$dn_and_filter[$i][$dn->nodeName]= "DC=".str_replace(".",",DC=",$domain);
	}
		//echo "nodename2 : ".$dn_and_filter[$i][$dn->nodeName]."\n";	
		//echo "domain : ".$domain."\n";
	$dn_and_filter[$i]['type'] = $xp_ldap_conf->query("/root/filter/dn[@id= '".$dn->nodeValue."']/@type")->item(0)->nodeValue;
	if(empty($dn_and_filter[$i]['type'])){
		$dn_and_filter[$i]['type'] = "users"; //Valeur par defaut
	}

	$dn_and_filter[$i]['user'] = $xp_ldap_conf->query("/root/filter/dn[@id= '".$dn->nodeValue."']/user")->item(0)->nodeValue;
	if(empty($dn_and_filter[$i]['user'])){
		$dn_and_filter[$i]['user'] = "(cn=*)"; //Valeur par defaut
	}
	
	$dn_and_filter[$i]['group'] = $xp_ldap_conf->query("/root/filter/dn[@id='".$dn->nodeValue."']/entity")->item(0)->nodeValue;
	if(empty($dn_and_filter[$i]['group'])){
		$dn_and_filter[$i]['group'] = "(cn=*)"; //Valeur par defaut
	}
	$i++;
	//echo "i : ".$i."\n";
}
unset($i);

//Aucun DN de défini : on prend tout l'annuaire en mode organization
if(count($dn_and_filter) < 1)
{   
	//echo "dn and filter <1 \n";
	$dn_and_filter[0]['id'] = "DC=".str_replace(".",",DC=",$domain);
	$dn_and_filter[0]['type'] = "users";
	$dn_and_filter[0]['user'] = "(cn=*)";
	$dn_and_filter[0]['group'] = "(cn=*)";
}

//**********************************//
//				XML OUT	     		//
//**********************************//
$out_xml = new DomDocument('1.0', 'UTF-8');
$xp_out = new domxpath($out_xml);

$dns = $out_xml->createElement('dns');
$out_xml->appendChild($dns);

//**********************************//
//			XML FUNCTIONS 	 		//
//**********************************//

function createUserNode($parent,$user)
{

	global $out_xml, $ad, $user_fields, $group_fields;

	if($parent == null || count($user) < 1 )
		return null;

	$u_node = $out_xml->createElement("user");
	//var_dump($u_node);
	foreach($user as $k_fd => $v_fd)
	{
		if( $k_fd == "dn" || $k_fd == "ext_id"){
			$u_node->setAttribute($k_fd,$v_fd);
		}
		else if($k_fd == "memberof" && count($v_fd) > 0)
		{
			$mbof = $out_xml->createElement($k_fd);

			if( isset($user['role']) )
				$mbof->setAttribute("role",$user['role']);
			
			for($i=0;$i<count($v_fd);$i++)
			{
				$tmp_g_inf = groupInfo($v_fd[$i]);
				
				if( !empty( $tmp_g_inf ) )
				{
					$mbof->appendChild( createGroupNode($mbof,$tmp_g_inf) );
				}
			}
			
			if($mbof->hasChildNodes())
				$u_node->appendChild($mbof);
		}
		else if($k_fd == "memberof" && count($v_fd) < 1)
		{
			//Si l'utilisateur n'est membre d'aucun groupe : Rien à faire
		}
		else if($k_fd == 'role')
		{
			//Traité dans memberof
		}	
		else{
			//var_dump($out_xml);
			$createContenuElement = $out_xml->createElement($k_fd,$v_fd);
			$u_node->appendChild($createContenuElement);
		}

		
	}
	//var_dump($u_node);
	return $u_node;
}

function createGroupNode($parent,$group)
{
	global $out_xml, $ad, $user_fields, $group_fields;

	if($parent == null || count($group) < 1 ){
		return null;
	}

	$g_node = $out_xml->createElement("group");
	
	foreach($group as $k_fd => $v_fd)
	{
		if( $k_fd == "dn" || $k_fd == "ext_id" ){
			$g_node->setAttribute($k_fd,$v_fd);
		}
		
		else if($k_fd == "memberof" && count($v_fd) > 0)
		{
			$mbof = $out_xml->createElement($k_fd);
			
			for($i=0;$i<count($v_fd);$i++)
			{
				$tmp_g_inf = groupInfo($v_fd[$i]);
				
				if( !empty( $tmp_g_inf ) )
					$mbof->appendChild( createGroupNode($mbof,$tmp_g_inf) );
			}
			
			if($mbof->hasChildNodes())
				$g_node->appendChild($mbof);
		}
		else if($k_fd == "memberof" && count($v_fd) < 1)
		{
			//Si le groupe n'est membre d'aucun groupe : Rien à faire
		}
		else
			$g_node->appendChild($out_xml->createElement($k_fd,$v_fd));
	}
	
	return $g_node;
}

function groupInfo($group_dn)
{
	global $ad, $group_fields, $dn_and_filter;
	
	if(!empty($dn_and_filter)){
		foreach($dn_and_filter as $dn_fil)
		{
			$tmp_g_inf = $ad->group_info($group_dn,array_values($group_fields),$dn_fil['id'],$dn_fil['group']);
			
			if( !empty($tmp_g_inf) )
			{
				$group_node = array();
				foreach($group_fields as $k_gf => $v_gf)
					$group_node[$k_gf] = $tmp_g_inf[$v_gf];

				return $group_node;
			}
		}
		return null;
	}
}

//**********************************//
//				USER	     		//
//**********************************//
if(!empty($dn_and_filter)){
	foreach($dn_and_filter as $dn_fil)
	{
		//var_dump($dn_and_filter);
		//Pour chaque DN on extrait les utilisateurs comme feuilles du DN

		$dn = $out_xml->createElement('dn');
		$dn->setAttribute("id",$dn_fil['id']);
		$dns->appendChild($dn);
		$type = $dn_fil['type'];
		$list_users = array();
		if($type == 'users'){
			//$user_fields = array("user_id" => "uid", "mail" => "mail");
			$xml_ldap_info = new SimpleXMLElement("<?xml version=\"1.0\"?><ldap_info></ldap_info>");
			$list_users = $ad->all_users(array_values($user_fields),$dn_fil['id'],$dn_fil['user']);
			$user_node = array();
			// creating object of SimpleXMLElement
			// function call to convert array to xml
			$ldap_info = fusionTableau($list_users,$user_fields_nodename);
			array_to_xml($ldap_info,$xml_ldap_info);

			//saving generated xml file
			$xml_ldap_info->asXML('../xml/ldap_users.xml');
			echo "File ldap_users.xml created\n";
		}elseif($type == 'entities'){
			$xml_ldap_info = new SimpleXMLElement("<?xml version=\"1.0\"?><ldap_info></ldap_info>");
			$list_groups = $ad->all_groups(array_values($group_fields),$dn_fil['id'],$dn_fil['groups']);
			$user_node = array();
			// creating object of SimpleXMLElement
			// function call to convert array to xml
			$ldap_info = fusionTableau($list_groups,$group_fields_nodename);
			array_to_xml($ldap_info,$xml_ldap_info);
			$xml_ldap_info->asXML('../xml/ldap_entities.xml');
			echo "File ldap_entities.xml created\n";	
		}
	}

}