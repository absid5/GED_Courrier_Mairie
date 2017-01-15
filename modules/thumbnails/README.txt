Pour Linux :
- Paramétrer un robot qui lance le script launch_batch_thumbnails.sh placé dans modules/thumbnails/scripts

- Le fichier de paramètres est config_batch_letterbox.xml (dans le dossier xml)

- Ce script lance le fichier php create_tnl qui permet de créer les miniatures des documents ayant les champs "tnl_path" et "tnl_filename" vides. Ces champs sont à rajouter dans la table res_letterbox (et autres tables liées comme les versions, documents liés, etc.. ?) 

- Les miniatures sont enregistrées dans un docserveur différent  (l'id est à définir dans le fichier de paramètres) du docserveur principal

- La commande de conversion est la suivante 

convert -thumbnail x300 -background white -alpha remove <path_input>[0] <path_output>

imagemagick et ghostscript sont nécessaires