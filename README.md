# Maarch_Courrier
Maarch Courrier : Gestionnaire Électronique de Courrier

**/!\ Version de développement**

Dernière version stable V1.6 : http://wiki.maarch.org/Maarch_Courrier
Démonstration en ligne : http://demo.maarch.org/gec/

## Requis techniques

* Apache2.x
* PostgreSQL 9.x
* PHP 5.5 ou plus, MaarchCourrier 1.6 est compatible avec php7 !
   * Extensions : php_xsl, php_xmlrpc, php_gettext, php_gd, php_pgsql, php_mbstring, php_pdo_pgsql, php5-mcrypt, php_imap, php_soap
   * Spécifique Windows : php php_pdo_pgsql.dll, php php_fileinfo.dll
   * Bibliothèques pear/SOAP (pour php < 7.0), pear/CLITools
* imagick, php_imagick
* ghostscript
* 7z (p7z-full sous gnu/linux)
* wkhtmltopdf, wkhtmltoimage (http://wkhtmltopdf.org/downloads.html)
* LibreOffice (pour la conversion de documents)
* JRE >= 7

###  Recommandations pour le php.ini

php > 5.4 : error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT
display_errors (On)
short_open_tags (On)
magic_quotes_gpc (Off)
