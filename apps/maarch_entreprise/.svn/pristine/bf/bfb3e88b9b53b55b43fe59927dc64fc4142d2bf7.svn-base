<?php
/*
*    Copyright 2008-2015 Maarch
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Contains the apps tools class
*
*
* @file
* @date $date$
* @version $Revision$
* @ingroup apps
*/

require_once 'apps/'. $_SESSION['config']['app_id'] .'/class/class_business_app_tools_Abstract.php';

class business_app_tools extends business_app_tools_Abstract
{
    // custom

    // Méthode surchargé pour spm car impossible de surchargé ce fichier dans le custom
    protected function _loadEntrepriseVar()
    {
        $core = new core_tools();
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
            . 'apps'.DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'entreprise.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                . DIRECTORY_SEPARATOR . 'entreprise.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'entreprise.xml';
        }
        $xmlfile = simplexml_load_file($path);
        $langPath = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
            . $_SESSION['config']['lang'] . '.php';

        $_SESSION['mail_natures'] = array();
        $_SESSION['mail_natures_attribute'] = array();
        $_SESSION['mail_natures_third'] = [];
        $mailNatures = $xmlfile->mail_natures;
        if (count($mailNatures) > 0) {
            foreach ($mailNatures->nature as $nature ) {
                $label = (string) $nature->label;
                $attribute = (string) $nature['with_reference'];
                $attributeThird = (string) $nature['with_third'];
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                ) {
                    $label = constant($label);
                }
                $_SESSION['mail_natures'][(string) $nature->id] = $label;
                $_SESSION['mail_natures_attribute'][(string) $nature->id] = $attribute;
                $_SESSION['mail_natures_third'][(string) $nature->id] = $attributeThird;
            }
            $_SESSION['default_mail_nature'] = (string) $mailNatures->default_nature;
        }


        $_SESSION['processing_modes'] = array();
        $processingModes = $xmlfile->process_modes; 
        if(count($processingModes) > 0) {
            foreach ($processingModes->process_mode as $process ) {
                $label = (string) $process->label;
                $_SESSION['processing_modes'][(string) $process->label] = $label;
                $process_mode_priority = (string) $process->process_mode_priority;
                $_SESSION['process_mode_priority'][(string) $process->label] = $process_mode_priority;
            }

        }
        //var_dump($_SESSION['processing_modes']);
        //exit;

        $_SESSION['attachment_types'] = array();
        $_SESSION['attachment_types_with_chrono'] = array();
        $_SESSION['attachment_types_show'] = array();
        $_SESSION['attachment_types_with_process'] = array();
        $attachmentTypes = $xmlfile->attachment_types;
        if (count($attachmentTypes) > 0) {
            foreach ($attachmentTypes->type as $type ) {
                $label = (string) $type->label;
                $with_chrono = (string) $type['with_chrono'];
                $get_chrono = (string) $type['get_chrono'];
                $attach_in_mail = (string) $type['attach_in_mail'];
                $show_attachment_type = (string) $type['show'];
                $process = (string) $type->process_mode;
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                ) {
                    $label = constant($label);
                }

                $array_get_chrono = explode(',', $get_chrono);
                $_SESSION['attachment_types'][(string) $type->id] = $label;
                $_SESSION['attachment_types_with_chrono'][(string) $type->id] = $with_chrono;
                $_SESSION['attachment_types_show'][(string) $type->id] = $show_attachment_type;
                $_SESSION['attachment_types_get_chrono'][(string) $type->id] = $array_get_chrono;
                $_SESSION['attachment_types_attach_in_mail'][(string) $type->id] = $attach_in_mail;
                $_SESSION['attachment_types_with_process'][(string) $type->id] = $process;
            }
        }




        $_SESSION['mail_priorities'] = array();
        $_SESSION['mail_priorities_attribute'] = array();
        $_SESSION['mail_priorities_wdays'] = array();
        $mailPriorities = $xmlfile->priorities;
        if (count($mailPriorities) > 0) {
            $i = 0;
            foreach ($mailPriorities->priority as $priority ) {
                $label = (string) $priority;
                $attribute = (string) $priority['with_delay'];
                $workingDays = (string) $priority['working_days'];
                $color = (string) $priority['color'];
                if($color == "" || $color == NULL){
                    $color = '#009DC5';
                }
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                ) {
                    $label = constant($label);
                }
                $_SESSION['mail_priorities'][$i] = $label;
                $_SESSION['mail_priorities_attribute'][$i] = $attribute;
                $_SESSION['mail_priorities_wdays'][$i] = ($workingDays != 'false' ? 'true' : 'false');
                $_SESSION['mail_priorities_color'][$i] = $color;
                $i++;
            }
            $_SESSION['default_mail_priority'] = (string) $mailPriorities->default_priority;
            $_SESSION['default_sve_priority'] = (string) $mailPriorities->default_sve_priority;
        }

        $contact_check = $xmlfile->contact_check;
        if (count($contact_check) > 0) {
            $_SESSION['check_days_before'] = (string) $contact_check->check_days_before;
        }

        $_SESSION['mail_titles'] = array();
        $mailTitles = $xmlfile->titles;
        if (count($mailTitles) > 0) {
            $i = 0;
            foreach ($mailTitles->title as $title ) {
                $label = (string) $title->label;
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                ) {
                    $label = constant($label);
                }
                $_SESSION['mail_titles'][(string)$title->id] = $label;
            }
            $_SESSION['default_mail_title'] = (string) $mailTitles->default_title;
        }

    }

}