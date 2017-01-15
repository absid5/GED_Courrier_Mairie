#!/bin/sh
eventStackPath='/var/www/maarch_entreprise/modules/notifications/batch/process_event_stack.php'
cd /var/www/maarch_entreprise/modules/notifications/batch/
php $eventStackPath -c /var/www/maarch_entreprise/modules/notifications/batch/config/config.xml -n NCT
php $eventStackPath -c /var/www/maarch_entreprise/modules/notifications/batch/config/config.xml -n NCC
php $eventStackPath -c /var/www/maarch_entreprise/modules/notifications/batch/config/config.xml -n ANC
php $eventStackPath -c /var/www/maarch_entreprise/modules/notifications/batch/config/config.xml -n AND
php $eventStackPath -c /var/www/maarch_entreprise/modules/notifications/batch/config/config.xml -n RED
