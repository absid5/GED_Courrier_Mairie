<?php
require_once('core' . DIRECTORY_SEPARATOR . 'class' 
    . DIRECTORY_SEPARATOR . 'class_core_tools.php');
require_once('core' . DIRECTORY_SEPARATOR . 'class' 
    . DIRECTORY_SEPARATOR . 'class_request.php');
require_once('core' . DIRECTORY_SEPARATOR . 'class' 
    . DIRECTORY_SEPARATOR . 'users_controler.php');
require_once('core' . DIRECTORY_SEPARATOR . 'class'
    . DIRECTORY_SEPARATOR . 'class_security.php');
require_once('core' . DIRECTORY_SEPARATOR . 'core_tables.php');



//Pour la gestion des TRACES
require_once('core' . DIRECTORY_SEPARATOR . 'class' 
    . DIRECTORY_SEPARATOR . 'class_history.php');
    
//Pour les actions sur les entités
require_once('modules/entities/class/EntityControler.php');

//Pour les actions sur les Services
require_once 'core/class/ServiceControler.php';
require_once 'apps/maarch_entreprise/class/class_business_app_tools.php';


$core = new core_tools();

if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    echo functions::xssafe($_SESSION['error']);
    $_SESSION['error'] = '';
    exit;
}




//////////////////////////////////////////////////////////////////////
/*************** Récupération entêtes *******************************/
// Récupération du XML qui correspond à la structure de la requete  //
//////////////////////////////////////////////////////////////////////

/*
if (isset($_SESSION['HTTP_REQUEST'])) {
    //$core->show_array($_SESSION['HTTP_REQUEST']);
}
*/

/**********************************************************************/
/**** TEST & RECUPERATION DU FICHIER DE CONFIG ****/
if (file_exists($_SESSION['config']['corepath'] . 'custom' . 
    DIRECTORY_SEPARATOR . $_SESSION['custom_override_id'] . 
    DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR . 
    $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml' . 
    DIRECTORY_SEPARATOR . 'mapping_sso.xml')
){
    $xmlPath = $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
    . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
    . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'mapping_sso.xml';
} elseif (file_exists($_SESSION['config']['corepath'] . 'apps'
    . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 
    'mapping_sso.xml')
){
    $xmlPath = $_SESSION['config']['corepath'] . DIRECTORY_SEPARATOR . 'apps'
    . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'mapping_sso.xml';
} else {
    echo _XML_FILE_NOT_EXISTS;
    exit;
}


$xmlconfig = simplexml_load_file($xmlPath);
$loginRequestArray = array();
$loginRequestArray = $core->object2array($xmlconfig);
//$core->show_array($loginRequestArray);


// Pour les traces
$trace = new history();


/**********************************************************************/
/**** OBLIGATORY ATTRIBUTES ****/

// Read XML SSO CONFIG
$xml = read_ssoXml($xmlPath,"ROOT",array("OBLIGATORY_USERUID",
                                     "OBLIGATORY_USERFIRSTNAME",
                                     "OBLIGATORY_USERLASTNAME",
                                     "OBLIGATORY_CODEUNIT",
                                     "OBLIGATORY_MAIL",
                                     "OBLIGATORY_NIGEND",
                                     "OBLIGATORY_PROFIL",
                                     "OBLIGATORY_ENTITY",
                                     "USER_SEP_TOKEN",
                                     "PROFIL_SEP_TOKEN",
                                     "ENTITY_SEP_TOKEN",
                                     "CODE_USERUID",                                     
                                     "CODE_USERFIRSTNAME",
                                     "CODE_USERLASTNAME",                                    
                                     "CODE_NIGEND",
                                     "CODE_CODEUNIT",
                                     "CODE_MAIL",
                                     "CODE_PROFIL",
                                     "CODE_ENTITY",
                                     "CODE_DATABASE")); 
   
$loginArray = array();
$recordProfils="";

foreach($xml as $row) 
{
  $loginArray['userUidRequired'] = $row[0];
  $loginArray['userFirstNameRequired'] = $row[1];
  $loginArray['userLastNameRequired'] = $row[2];
  $loginArray['codeUnitRequired'] = $row[3];
  $loginArray['mailRequired'] = $row[4];
  $loginArray['nigendRequired'] = $row[5];
  $loginArray['profilRequired'] = $row[6];
  $loginArray['entityRequired'] = $row[7];  
  $loginArray['user_separator'] = $row[8];
  $loginArray['profil_separator'] = $row[9];
  $loginArray['entity_separator'] = $row[10];
  
  $loginArray['userUidRequiredError'] = $row[11];
  $loginArray['userFirstNameRequiredError'] = $row[12];
  $loginArray['userLastNameRequiredError'] = $row[13];
  $loginArray['nigendRequiredError'] = $row[14];
  $loginArray['codeUnitRequiredError'] = $row[15];
  $loginArray['mailRequiredError'] = $row[16];
  $loginArray['profilRequiredError'] = $row[17];
  $loginArray['entityRequiredError'] = $row[18];
  $loginArray['databaseError'] = $row[19];
}
$loginArray['change_pass'] = 'N';



/**********************************************************************/
/**** GET HEADERS  ****/

// Call the function
$headers = getHeaders() ;

$profilArray = array();
foreach ($headers as $k => $v) 
{   
    //DEBUG
    //echo "$k = ".base64_decode($v)."<br/>\n" ;    
    
    switch ($k) {
        case "USER_UID":
            $loginArray['userUid'] = base64_decode($v);             
            break;  

            
        case "USER_FIRSTNAME":
            $loginArray['FirstName'] = base64_decode($v);
            break;          

            
        case "USER_LASTNAME":
            $loginArray['LastName'] = base64_decode($v);
            break;          


        case "NIGEND":          
            $loginArray['UserId'] = base64_decode($v);  
            break;

            
        case "UNITE_CODE":
            $loginArray['department'] = base64_decode($v);
            break;
            
            
        case "USER_MAIL":
            $loginArray['Mail'] = base64_decode($v);
            break;
            
            
        case "PROFILS":
            $profilArray = fillProfilArray($loginArray, base64_decode($v));
            $recordProfils=base64_decode($v);
            
            $loginArray['userGroup'] =$profilArray;
            break;
            
            
        case "ENTITIES":        
            $entityArray = fillEntityArray($loginArray, base64_decode($v));
            $recordEntities=base64_decode($v);                      
                
            $loginArray['Entities'] =$entityArray;
            //$loginArray['userEntity'] =$entityArray;
            break;
    }    
}



/**********************************************************************/
/**** MANAGEMENT OF ERRORS ****/

$_SESSION['error'] = '';

if ($loginArray['userUidRequired']=="true")
{
    if (!$loginArray['userUid']) {
    $_SESSION['error'] .= ' UID' . ' ' . _MISSING;
    $errorId=$loginArray['userUidRequiredError'];
    }
}

if ($loginArray['userFirstNameRequired']=="true")
{
    if (!$loginArray['FirstName']) {
    $_SESSION['error'] .= _FIRSTNAME . ' ' . _MISSING;
    $errorId=$loginArray['userFirstNameRequiredError'];
    }
}

if ($loginArray['userLastNameRequired']=="true")
{
    if (!$loginArray['LastName']) {
    $_SESSION['error'] .= _LASTNAME . ' ' . _MISSING;
    $errorId=$loginArray['userLastNameRequiredError'];
    }
}

if ($loginArray['nigendRequired']=="true")
{
    if (!$loginArray['UserId']) {
    $_SESSION['error'] .= _NIGEND . ' ' . _MISSING;
    $errorId=$loginArray['nigendRequiredError'];
    }
}

if ($loginArray['codeUnitRequired']=="true")
{
    if (!$loginArray['department']) {
    $_SESSION['error'] .= _CODEUNIT . ' ' . _MISSING;
    $errorId=$loginArray['codeUnitRequiredError'];  
    }
}

if ($loginArray['mailRequired']=="true")
{
    if (!$loginArray['Mail']) {
    $_SESSION['error'] .=_EMAIL . ' ' . _MISSING;
    $errorId=$loginArray['mailRequiredError'];
    }
}

if ($loginArray['profilRequired']=="true")
{
    if (!$loginArray['userGroup']) {
    $_SESSION['error'] .= _GROUP_ID . ' ' . _MISSING;
    $errorId=$loginArray['profilRequiredError'];
    }
}

if ($loginArray['entityRequired']=="true")
{
    if (!$loginArray['Entities']) {
    $_SESSION['error'] .= _ENTITY_ID . ' ' . _MISSING;
    $errorId=$loginArray['entityRequiredError'];
    }
}



/**********************************************************************/
/**** GESTION DES ERREURS ****/

if (isset($_SESSION['error']) && $_SESSION['error'] <> '') {
        
    //Traces techniques     
    $trace->add("users",
                $loginArray['UserId'],
                "LOGIN", 'userlogin',
                _CONNECTION_SSO_FAILED . 
                " CodeError " . $errorId . " : " .$_SESSION['error'],
                $_SESSION['config']['databasetype'],
                "ADMIN",
                true);
                
    
    header("location: " . $loginRequestArray['WEB_SSO_URL']
         . "index.php?errorId=" . $errorId 
         . "&errorMsg=" . $_SESSION['error']);  
    exit;
}



/**********************************************************************/
/**** USER ALREADY EXISTS?? ****/

$temoinUpdate = 0;// Témoin Update pour conserver le format du password
                  //et ne pas toucher à log.php
                  
$db = new Database();
$query = "SELECT user_id FROM " . USERS_TABLE 
       . " WHERE user_id = ?";
$stmt = $db->query($query, array($loginArray['UserId']));

/**********************************************************************/
/**** SAVE FUNCTIONS ****/

/***  Login = nigend  et mode de passe = "$".nigend."*"             ***/
$loginArray['password'] = '$' . $loginArray['UserId'] . '*';

/*** Fill user object to update it ***/
$userObject = fillUserObject($loginArray);
$groupArray = fillGroupArray($loginArray,$recordProfils);

//DEBUG
var_dump($userObject);



$params = array(
    'modules_services' => $_SESSION['modules_services'],
    'log_user_up' => $_SESSION['history']['usersup'],
    'log_user_add' => $_SESSION['history']['usersadd'],
    'databasetype' => $_SESSION['config']['databasetype'],
    'userdefaultpassword' => $loginArray['password'],
);

$uc = new users_controler();



/**********************************************************************/
/**** UPDATE OR INSERT ?? ****/

if ($stmt->rowCount() > 0) {
    $sec = new security();
    $userObject->password = $sec->getPasswordHash($loginArray['password']);
    
    //user exists, so update it
    $control = $uc->save($userObject, $groupArray, 'up', $params);
    $temoinUpdate = 1;
} else {    
    //user doesn't exists, so create it
    $control = $uc->save($userObject, $groupArray, 'add', $params);
}
if($temoinUpdate > 0){
    $userObject->password = $loginArray['password'];
}


if(!empty($control['error']) && $control['error'] <> 1) {
    echo $control['error'];exit;

    //Traces fonctionnelles
    $trace->add("users",
                $loginArray['UserId'],
                "LOGIN",
                _CONNECTION_SSO_FAILED . 
                " CodeError " . $loginArray['databaseError'] . 
                " : " .$_SESSION['error'],
                $_SESSION['config']['databasetype'],
                "ADMIN",
                true);

    header("location: " . $loginRequestArray['WEB_SSO_URL']
                . "index.php?errorId=" . $loginArray['databaseError']);
    exit;
} else {

    /**/
    //fill user entities
    $entityCtrl = new EntityControler();
    $entityCtrl->cleanUsersentities($loginArray['UserId'], 'user_id');
    $entityCtrl->loadDbUsersentities($loginArray['UserId'], 
                                     $entityArray);


    // Get the corresponding services
    $serv_controler = new ServiceControler();
    $serv_controler->loadEnabledServices();
    $business_app_tools = new business_app_tools();
    $core_tools = new core_tools();

    $business_app_tools->load_app_var_session($loginArray);
    $core_tools->load_var_session($_SESSION['modules'], $loginArray);   
    $loginArray['services'] = 
            $serv_controler->loadUserServices($loginArray['UserId']);       
        
        
/**********************************************************************/
/**** CONNECTION A MAARCH ****/

    $_SESSION['web_sso_url'] = $loginRequestArray['WEB_SSO_URL'];
    header("location: " . $_SESSION['config']['businessappurl'] 
        . "log.php?login=" . $loginArray['UserId'] 
        . "&pass=" . $loginArray['password']);

    //Traces fonctionnelles
    $trace->add("users",
                $loginArray['UserId'],
                "LOGIN",
                _CONNECTION_SSO_OK,
                $_SESSION['config']['databasetype'],
                "ADMIN",
                false);
    exit();
}


// Create an Object about User
function fillUserObject($loginArray)
{
    $user = new users();
    $user->user_id = $loginArray['UserId'];
    $user->password = $loginArray['password'];
    
                        
    $user->firstname = $loginArray['FirstName'];
    $user->lastname = $loginArray['LastName'];
    
    $user->department = $loginArray['department'];
    $user->mail = $loginArray['Mail'];
    $user->loginmode = 'sso';
    $user->change_password = 'N';
    return $user;
}

// Function to separate Last Name & First Name
function fillUserArray($sep, $nameConcat)
{    
    $fullName = array();  
    $tmp = array();  
    $tmp = explode($sep, $nameConcat);

    $fullName['FIRSTNAME'] =  $tmp[0];
    $fullName['LASTNAME'] =  $tmp[1];               

    return $fullName;
}

// Function to record groups in the array
function fillProfilArray($loginArray, $headerProfil)
{
    $groupArray = array();
    $tmp = array();
    $tmp = explode($loginArray['profil_separator'],$headerProfil);

    for ($cpt = 0;$cpt < count($tmp);$cpt++) {
        if ($cpt == 0) {
            $primaryGroup = 'Y';
        } else {
            $primaryGroup = 'N';
        }
        array_push(
            $groupArray,
            array(
                'GROUP_ID' =>  $tmp[$cpt],
                'PRIMARY' =>  $primaryGroup,
                'ROLE' =>  '',
            )
        );
    }   
    return $groupArray;
}

// Function to record entities in the array
function fillEntityArray($loginArray, $headerEntity)
{
    $entityArray = array();
    $tmp = array();
    $tmp = explode($loginArray['entity_separator'],$headerEntity);

    for ($cpt = 0;$cpt < count($tmp);$cpt++) {
        if ($cpt == 0) {
            $primaryEntity = 'Y';
        } else {
            $primaryEntity = 'N';
        }
        array_push(
            $entityArray,
            array(
                'ENTITY_ID' =>  $tmp[$cpt],
                'PRIMARY' =>  $primaryEntity,
                'ROLE' =>  '',
            )
        );
    }   
    return $entityArray;
}

// Function to collect all headers
function getHeaders() 
{
    foreach ($_SERVER as $h => $v ) 
    {
      if( ereg( 'HTTP_(.+)', $h, $hp ) )
      $headers[$hp[1]] = $v ;
    }
    return $headers;
}

// Get the XML
function read_ssoXml($fichier,$item,$champs) {
   if($chaine = @implode("",@file($fichier))) 
   {
      $tmp = preg_split("/<\/?".$item.">/",$chaine);

      for($i=1;$i<sizeof($tmp)-1;$i+=2)
         foreach($champs as $champ) 
         {
            $tmp2 = preg_split("/<\/?".$champ.">/",$tmp[$i]);
            $tmp3[$i-1][] = @$tmp2[1];
         }
      return $tmp3;
   }
}


// Function to record groups in the array (in order to update users)
function fillGroupArray($loginArray,$recordProfils)
{
    $groupArray = array();
    $tmp = array();
    
    $tmp = explode($loginArray['profil_separator'],$recordProfils);

    //$tmp = $loginArray['userGroup'];


    for ($cpt = 0;$cpt < count($tmp);$cpt++) {
        if ($cpt == 0) {
            $primaryGroup = 'Y';
        } else {
            $primaryGroup = 'N';
        }
        
        array_push(
            $groupArray,
            array(
                'USER_ID' =>  $loginArray['UserId'],
                'GROUP_ID' =>  $tmp[$cpt],
                'PRIMARY' =>  $primaryGroup,                
                'ROLE' =>  '',
            )
        );
    }
    return $groupArray;
}


$core->show_array($loginArray);
//exit;
