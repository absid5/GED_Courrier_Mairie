Pour Linux :
- Param�trer un robot qui lance le script launch_batch_thumbnails.sh plac� dans modules/thumbnails/scripts

- Le fichier de param�tres est config_batch_letterbox.xml (dans le dossier xml)

- Ce script lance le fichier php create_tnl qui permet de cr�er les miniatures des documents ayant les champs "tnl_path" et "tnl_filename" vides. Ces champs sont � rajouter dans la table res_letterbox (et autres tables li�es comme les versions, documents li�s, etc.. ?) 

- Les miniatures sont enregistr�es dans un docserveur diff�rent  (l'id est � d�finir dans le fichier de param�tres) du docserveur principal

- La commande de conversion est la suivante 

convert -thumbnail x300 -background white -alpha remove <path_input>[0] <path_output>

imagemagick et ghostscript sont n�cessaires