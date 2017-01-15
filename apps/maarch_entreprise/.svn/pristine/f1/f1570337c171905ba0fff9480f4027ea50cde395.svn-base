<?php
//echo 'ici' . $_SESSION['config']['coreurl'];
//print_r($_SESSION['config']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"><head>
  <title>Démo CMIS</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="Content-Language" content="fr">
  <link rel="stylesheet" type="text/css" href="cmis_stylesheet.css" media="screen">
  </head>
  <body>
    <div>
      <div align="center">
        <h3>Accès Maarch via CMIS</h3>
      </div>
      <div>
        <p>
          </p><h4>Voir un document (id = 101)</h4>
          <!--div id="liencmis">
            <a href="<?php echo $_SESSION['config']['coreurl'];?>core/class/web_service/cmis_test/test_rest.php?collection=letterbox_coll&resource=res&amp;idResource=101" target="_blank">
              <?php echo $_SESSION['config']['coreurl'];?>core/class/web_service/cmis_test/test_rest.php?collection=letterbox_collresource=res&amp;idResource=101
            </a>
          </div-->
          <div id="liencmis">
            <a href="<?php echo $_SESSION['config']['coreurl'];?>ws_server.php?cmis/letterbox_coll/res/101" target="_blank">
              <?php echo $_SESSION['config']['coreurl'];?>ws_server.php?cmis/letterbox_coll/res/101
            </a>
          </div>
          <!--curl -X GET -ubblier:maarch "http://127.0.0.1/syleam_trunk/ws_server.php?REST/res/101"-->
        <p></p>
        <!--form method="post" action="<?php echo $_SESSION['config']['coreurl'];?>ws_server.php?cmis/letterbox_coll/folder" target="_blank">
          <p>
            </p><h4>Créer un dossier</h4>
            <div id="liencmis">
              <input name="xmlFile" value="testcreatefolder.atom.xml" type="hidden">
              Fichier : TEST
              <br>
              <input class="button" name="submit" value="Créer" type="submit">
            </div>
          <p></p>
        </form-->
        <p>
          </p><h4>Voir la liste des corbeilles</h4>
          <div id="liencmis"> 
            <a href="<?php echo $_SESSION['config']['coreurl'];?>ws_server.php?cmis/letterbox_coll/basket" target="_blank">
              <?php echo $_SESSION['config']['coreurl'];?>ws_server.php?cmis/letterbox_coll/basket
            </a>
          </div>
          <!--curl -X GET -ubblier:maarch "<?php echo $_SESSION['config']['coreurl'];?>ws_server.php?REST/basket"-->
        <p></p>
        <p>
          </p><h4>Liste des documents d'une corbeille</h4>
          <div id="liencmis">
            <a href="<?php echo $_SESSION['config']['coreurl'];?>ws_server.php?cmis/letterbox_coll/basket/InitBasket" target="_blank">
              <?php echo $_SESSION['config']['coreurl'];?>ws_server.php?cmis/letterbox_coll/basket/InitBasket
            </a>
          </div>
          <!--curl -X GET -ubblier:maarch "<?php echo $_SESSION['config']['coreurl'];?>ws_server.php?REST/basket/MesCourriersATraiter"-->
        <p></p>
        <!--form method="post" action="<?php echo $_SESSION['config']['coreurl'];?>ws_server.php?cmis/letterbox_coll/res" target="_blank">
          <p>
            </p><h4>Recherche avancée de documents</h4>
            <div id="liencmis">
              <input name="xmlFile" value="query.xml" type="hidden">
              
              Requête : SELECT  cmis:objectId , maarch:type , maarch:entity , maarch:dest_user   FROM cmis:document  ORDER BY cmis:objectId asc 
              <br>
              <input class="button" name="submit" value="Rechercher" type="submit">
            </div>
          <p></p>
        </form-->
        <!--curl -X POST -ubblier:maarch "<?php echo $_SESSION['config']['coreurl'];?>ws_server.php?REST/res" -d atomFileContent=thexmlcontentfile-->
        <!--p>
          </p><h4>Consulter un dossier (id = TEST)</h4>
          <div id="liencmis">
            <a href="<?php echo $_SESSION['config']['coreurl'];?>ws_server.php?cmis/letterbox_coll/folder/TEST" target="_blank">
              <?php echo $_SESSION['config']['coreurl'];?>ws_server.php?cmis/letterbox_coll/folder/TEST
              </a>
          </div-->
          <!--curl -X GET -ubblier:maarch "<?php echo $_SESSION['config']['coreurl'];?>ws_server.php?REST/folder/RH"-->
        <p></p>
      </div>
    </div>
  </body>
</html>
