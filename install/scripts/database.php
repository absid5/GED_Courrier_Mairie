<?php
/*
*   Copyright 2008-2012 Maarch
*
*   This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief class of install tools
*
* @file
* @author Arnaud Veber
* @date $date$
* @version $Revision$
* @ingroup install
*/

if ($_REQUEST['action'] == 'testConnect') {
    $_SESSION['config']['databaseserver']     = $_REQUEST['databaseserver'];
    $_SESSION['config']['databaseserverport'] = $_REQUEST['databaseserverport'];
    $_SESSION['config']['databaseuser']       = $_REQUEST['databaseuser'];
    $_SESSION['config']['databasepassword']   = $_REQUEST['databasepassword'];
    $_SESSION['config']['databasetype']       = $_REQUEST['databasetype'];

    $checkDatabaseParameters = $Class_Install->checkDatabaseParameters(
        $_REQUEST['databaseserver'],
        $_REQUEST['databaseserverport'],
        $_REQUEST['databaseuser'],
        $_REQUEST['databasepassword'],
        $_REQUEST['databasetype']
    );

    if (!$checkDatabaseParameters) {
        $return['status'] = 0;
        $return['text'] = _BAD_INFORMATIONS_FOR_CONNECTION;

        $jsonReturn = json_encode($return);

        echo $jsonReturn;
        exit;
    }

       
        $filename = realpath('.').'/custom/';
        if (!file_exists($filename)) {
            $cheminCustom = realpath('.')."/custom";
            mkdir($cheminCustom, 0755);                
        }







    $return['status'] = 1;
    $return['text'] = '';

    $jsonReturn = json_encode($return);

    echo $jsonReturn;
    exit;
} elseif ($_REQUEST['action'] == 'createdatabase') {


    $verifDatabase = $Class_Install->verificationDatabase($_REQUEST['databasename']);
    //var_dump($verifDatabase);
    if($verifDatabase == false){
        //var_dump('test coucou');
        // $_SESSION['config']['databasename'] = $_REQUEST['databasename'];
        // //var_dump($_SESSION['config']);
        // $return['status'] = 3;
        // $return['text'] = "base de données existe déjà";
        // $jsonReturn = json_encode($return);
        // echo $jsonReturn;   
        //exit;
        $createCustom = $Class_Install->createCustom($_REQUEST['databasename']);
        //var_dump($createCustom);
        //var_dump($createCustom);
        if(!$createCustom){ 
            //var_dump($createDatabase);
            $return['status'] = 0;
            $return['text'] = _UNABLE_TO_CREATE_CUSTOM;

            $jsonReturn = json_encode($return);

            echo $jsonReturn;
            exit;
        }

        $fillConfigs = $Class_Install->fillConfigOfAppAndModule($_REQUEST['databasename']);
        //var_dump($fillConfigs);
        if (!$fillConfigs) {
            $return['status'] = 0;
            $return['text'] = _UNABLE_TO_CREATE_CUSTOM;

            $jsonReturn = json_encode($return);

            echo $jsonReturn;
            exit;
        }


        $return['status'] = 1;
        $return['text'] = 'redirect';

        $jsonReturn = json_encode($return);

        echo $jsonReturn;
        exit;


    }elseif($verifDatabase == true){

        //var_dump($verifDatabase);


        $createCustom = $Class_Install->createCustom($_REQUEST['databasename']);
            //var_dump($createCustom);
        if($createCustom === false){ 
            //var_dump($createDatabase);
            $return['status'] = 0;
            $return['text'] = _UNABLE_TO_CREATE_CUSTOM;

            $jsonReturn = json_encode($return);

            echo $jsonReturn;
            exit;
        }
        $_SESSION['config']['databasename'] = $_REQUEST['databasename'];

        $createDatabase = $Class_Install->createDatabase(
            $_REQUEST['databasename']
        );

        if (!$createDatabase) {
            $return['status'] = 0;
            $return['text'] = _UNABLE_TO_CREATE_DATABASE;

            $jsonReturn = json_encode($return);

            echo $jsonReturn;
            exit;
        }

        $return['status'] = 1;
        $return['text'] = '';

        $jsonReturn = json_encode($return);

        echo $jsonReturn;
        exit;

    }

} elseif ($_REQUEST['action'] == 'loadDatas') {

    $loadDatas = $Class_Install->createData(
        'sql/'.$_REQUEST['dataFilename'].'.sql'
    );

    if (!$loadDatas) {
        $return['status'] = 0;
        $return['text'] = _UNABLE_TO_LOAD_DATAS;

        $jsonReturn = json_encode($return);

        echo $jsonReturn;
        exit;
    }

    $return['status'] = 1;
    $return['text'] = 'redirect';

    $jsonReturn = json_encode($return);

    echo $jsonReturn;
    exit;
}
