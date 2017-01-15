<?php

require_once 'modules/folder/class/cmis/cmis_folder_controller.php' ;
require_once 'apps/maarch_entreprise/class/cmis/cmis_class_property.php' ;
require_once 'apps/maarch_entreprise/class/cmis/cmis_res_controller.php' ;

class basketCMIS extends folderCMIS
{
    private $basketId;


    public function __construct() {

        $this->id = "cmis:basket";
        $this->queryName = "cmis:basket";
        $this->baseId = "cmis:folder";
        $this->parentId = null; //notset
        $this->fileable = true;
        $this->queryable = true;
        $this->localName = "cmis:basket";
        $this->displayName = "cmis:basket";
        $this->creatable = false;

        //properties
        $this->name = new PropertyString($required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable);
        $this->name->setRequired(true);
        $this->name->setInherited(false);
        $this->name->setPropertyType('String');
        $this->name->setCardinality('Single');
        $this->name->setId('name');
        $this->name->setOpenChoice(true);

        $this->objectId = new PropertyId(false, false, 'Id', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->objectId->setId('objectId');

        $this->baseTypeId = new PropertyId(false, false, 'Id', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->baseTypeId->setId('baseTypeId');

        $this->objectTypeId = new PropertyId(true, false, 'Id', 'Single',
                'oncreate', 'NotApplicable', 'NotApplicable', false, false);
        $this->objectTypeId->setId('objectTypeId');

        $this->createdBy = new PropertyString(false, false, 'String', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->createdBy->setId('createdBy');

        $this->creationDate = new PropertyDateTime(false, false, 'DateTime', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->creationDate->setId('creationDate');

        $this->lastModifiedBy = new PropertyString(false, false, 'String', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->lastModifiedBy->setId('lastModifiedBy');

        $this->lastModificationDate = new PropertyDateTime(false, false, 'DateTime', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->lastModificationDate->setId('lastModificationDate');

        $this->changeToken = new PropertyString(false, false, 'String', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->changeToken->setId('changeToken');

        $this->parentId = new PropertyId(false, false, 'Id', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->parentId->setId('parentId');

        $this->path = new PropertyString(false, false, 'String', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->path->setId('path');

        $this->allowedChildObjectTypeIds = new PropertyId(false, false, 'Id', 'Multi',
                'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->allowedChildObjectTypeIds->setId('allowedChildObjectTypeIds');

        $this->basketId = new PropertyId(true, false, 'Id', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->basketId->setId('basketId');


        $this->properties = array(
                $this->name, $this->objectId, $this->baseTypeId, $this->objectTypeId, $this->createdBy, $this->creationDate,
                $this->lastModifiedBy, $this->lastModificationDate, $this->changeToken, $this->parentId,
                $this->path, $this->allowedChildObjectTypeIds, $this->basketId
        );

        $this->propertiesInXml = array( $this->basketId );
    }


    public function setBasketId($id){
        $this->basketId = $id;
    }

    public function setUrl($url){
        $this->url = $url;
    }

    public function getBasketId(){
        return $this->basketId;
    }

    public function getUrl(){
        return $this->url;
    }

    public function entryMethod ($atomFileContent, $requestedResourceId)
    {

        if(!isset($atomFileContent)){
            if(!isset($requestedResourceId)){

                //get list of baskets (no documents attached)
                $cmisBaskets = $this->loadBaskets();

                $basketDoc= new DOMDocument('1.0', 'utf-8');
                $basketDoc->xmlStandalone = true;
                $basketDoc->formatOutput = true;
                //TODO feed


                $title = 'Baskets';
                $basketDoc = basketCMIS::getFeed($cmisBaskets, $title);

                echo $basketDoc->saveXML();

                /**/
            }
            else{
                $str = 'show documents in basket ';

                //get list of baskets (no documents attached)
                $cmisBaskets = $this->loadBaskets();

                $found=false;
                $i=0;

                while(!$found && ($basket = $_SESSION['user']['baskets'][$i++])){
                    $found = (strcmp($basket['id'],$requestedResourceId)==0);
                }

                if($found){
                    //get the list of documents in the basket whose id is $requestedResourceId
                    $documents = $this->getDocumentsInBasket($basket);
                    $title = trim('Documents in '.$requestedResourceId);
                    $resAtom = resCMIS::getFeed($documents, $title);

                    echo $resAtom->saveXML();
                }
                else{
                    //TODO throw objectNotFound
                    echo "<br />ERREUR : objectNotFound.<br />";
                }
            }
        }
        else{
            //TODO throw notSupported or invalidArgument
                echo "<br />ERREUR : requete sql non réalisée throw notSupported or invalidArgument.<br />";
        }

        //return $requestedResourceId . ' ' . $atomFileContent;
    }


    private function getDocumentsInBasket($basket){
        require_once('core/class/class_db_pdo.php');
        $db = new Database();

        //$resArray = null

        $view = $basket['view'];
        $clause = $basket['clause'];
        $statement = "select * from " . $view . " where " . $clause;

        $result = $db->query($statement);

        //TODO
        if($result === false){
            //TODO throw objectNotFound or invalidArgument or runtime
            echo "<br />ERREUR : requete sql non réalisée runtime.<br />";
        }

        $resArray = array();
        while ($recordset = $result->fetchObject()) {
            $documentCmis = new resCMIS();
            $documentCmis->DocumentCmis();
            $documentCmis->setRes(
                array('res_id' => $recordset->res_id,
                    'type_label'=> $recordset->type_label,
                    'contact_society' => $recordset->contact_society ,
                    'contact_firstname' => $recordset->contact_firstname ,
                    'contact_lastname' => $recordset->contact_lastname ,
                    'entity_label' => $recordset->entity_label ,
                    'dest_user' => $recordset->dest_user ,
                    'doc_date' => $recordset->doc_date ,
                    'process_limit_date' => $recordset->process_limit_date,
                    'author' => $recordset->author
                ));


            $url = $_SESSION['config']['coreurl']."apps/maarch_entreprise/index.php?page=view_baskets&module=basket&directLinkToAction&baskets=". $basket['id'] . "&resid=" . $recordset->res_id;
            $url = htmlentities($url, ENT_COMPAT | ENT_HTML401 , "utf-8");

            $documentCmis->setUrl($url);

            array_push($resArray, $documentCmis);
        }

        return $resArray;
    }



    private function loadBaskets(){
        require_once('core/class/class_db_pdo.php');
        $db = new Database();
        
        $userId = pg_escape_string($_SESSION['user']['UserId']);
        $statement = "select group_id from usergroup_content where user_id = '" . $userId . "' and primary_group = 'Y'";
        $result = $db->query($statement);

        //TODO gerer les cas d erreurs
        if($result === false){
            //TODO throw runtime
            echo "<br />ERREUR : runtime.<br />";
        }

        $recordset = $result->fetchObject();
        $_SESSION['user']['primarygroup'] = $recordset->group_id;

        $userData = array(
                'UserId' => $_SESSION['user']['UserId'],
                'primarygroup' => $_SESSION['user']['primarygroup']
        );

        require_once('modules/basket/class/class_modules_tools.php');
        $basketTools = new basket();
        $basketTools->load_module_var_session($userData);

        $cmisBaskets = array();

        foreach($_SESSION['user']['baskets'] as $key => $basket){
            $cmisBaskets[$key] = $this->toCmisBasket($basket);
        }

        return $cmisBaskets;
    }


    public function toCmisBasket($basket){
        $basketCmis = new basketCMIS();

        $basketCmis->basketId->setValue($basket['id']);
        $basketCmis->name->setValue($basket['name']);

        return $basketCmis;
    }

}

