#!/bin/sh
cd /var/www/maarch_entreprise/modules/notifications/batch/
emailStackPath='/var/www/maarch_entreprise/modules/notifications/batch/process_email_stack.php'
php $emailStackPath -c /var/www/maarch_entreprise/modules/notifications/batch/config/config.xml

