<?php

/*
*   Copyright 2008-2015 Maarch
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
* modules tools Class for attachments
*
*  Contains all the functions to load modules tables for attachments
*
* @package  maarch
* @version 3.0
* @since 10/2005
* @license GPL v3
* @author  <dev@maarch.org>
*
*/

abstract class attachments_Abstract
{

    /**
    * Build Maarch module tables into sessions vars with a xml configuration
    * file
    */
    public function build_modules_tables()
    {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
            . DIRECTORY_SEPARATOR . "attachments" . DIRECTORY_SEPARATOR . "xml"
            . DIRECTORY_SEPARATOR . "config.xml"
        )
        ) {
            $configPath = $_SESSION['config']['corepath'] . 'custom'
                        . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                        . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR
                        . "attachments" . DIRECTORY_SEPARATOR . "xml"
                        . DIRECTORY_SEPARATOR . "config.xml";
        } else {
            $configPath = "modules" . DIRECTORY_SEPARATOR . "attachments"
                        . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                        . "config.xml";
        }
        $xmlconfig = simplexml_load_file($configPath);
        foreach ($xmlconfig->TABLENAME as $tableName) {
            $_SESSION['tablename']['attach_res_attachments'] = (string) $tableName->attach_res_attachments;
        }
		$conf = $xmlconfig->CONFIG;
		$_SESSION['modules_loaded']['attachments']['convertPdf'] = (string) $conf->convertPdf;
		$_SESSION['modules_loaded']['attachments']['vbs_convert_path'] = (string) $conf->vbs_convert_path;
		$_SESSION['modules_loaded']['attachments']['useExeConvert'] = (string) $conf->useExeConvert;
        $watermark = $conf->watermark;
        $_SESSION['modules_loaded']['attachments']['watermark']['enabled'] = (string) $watermark->enabled;
        $_SESSION['modules_loaded']['attachments']['watermark']['text'] = (string) $watermark->text;
        $_SESSION['modules_loaded']['attachments']['watermark']['position'] = (string) $watermark->position;
        $_SESSION['modules_loaded']['attachments']['watermark']['font'] = (string) $watermark->font;
        $_SESSION['modules_loaded']['attachments']['watermark']['text_color'] = (string) $watermark->text_color;
		 
        $hist = $xmlconfig->HISTORY;
        $_SESSION['history']['attachadd'] = (string) $hist->attachadd;
        $_SESSION['history']['attachup'] = (string) $hist->attachup;
        $_SESSION['history']['attachdel'] = (string) $hist->attachdel;
        $_SESSION['history']['attachview'] = (string) $hist->attachview;
    }
}
