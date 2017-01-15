#!/bin/bash
cd C:\xampp\htdocs\maarch_courrier/modules/ldap/script/

#generation des fichiers xml
php C:\xampp\htdocs\maarch_courrier/modules/ldap/process_ldap_to_xml.php C:\xampp\htdocs\maarch_courrier/custom/cs_maarch_db/modules/ldap/xml/config.xml

#mise a jour bdd
php C:\xampp\htdocs\maarch_courrier/modules/ldap/process_entities_to_maarch.php C:\xampp\htdocs\maarch_courrier/custom/cs_maarch_db/modules/ldap/xml/config.xml
php C:\xampp\htdocs\maarch_courrier/modules/ldap/process_users_to_maarch.php C:\xampp\htdocs\maarch_courrier/custom/cs_maarch_db/modules/ldap/xml/config.xml
php C:\xampp\htdocs\maarch_courrier/modules/ldap/process_users_entities_to_maarch.php C:\xampp\htdocs\maarch_courrier/custom/cs_maarch_db/modules/ldap/xml/config.xml