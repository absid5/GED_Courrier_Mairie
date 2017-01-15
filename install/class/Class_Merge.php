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

class Merge
{
    function __construct()
    {
        //merge the css
        $this->mergeCss();
        //merge the js
        $this->mergeJs();
    }

    private function mergeCss()
    {
        if ($this->needToMergeCss()) {
            $dirStyle = 'install/css/';
            $styleDirectory = opendir($dirStyle);
            $mergedCss = '@charset "UTF-8";'."\n\n";

            //atProperty first
            $cssLines = file(realpath($dirStyle.'atProperty.css'));
            $mergedCss .= '/* atProperty.css */'."\n";
            for ($i=0; $i<count($cssLines); $i++) {
                $mergedCss .= $cssLines[$i];
            }
            $mergedCss .= "\n\n";

            while($styleFile = @readdir($styleDirectory)) {
                if (   substr($styleFile, 0, 1) != '.'
                    && substr($styleFile, -4) == '.css'
                    && $styleFile != 'merged_css.css'
                    && $styleFile != 'atProperty.css'
                ) {
                    $cssLines = file(realpath($dirStyle.$styleFile));
                    $mergedCss .= '/* '.$styleFile.' */'."\n";
                    for ($i=0; $i<count($cssLines); $i++) {
                        $mergedCss .= $cssLines[$i];
                    }
                    $mergedCss .= "\n\n";
                }
            }
            closedir($styleDirectory);

            if (file_exists($dirStyle.'merged_css.css')) {
                unlink($dirStyle.'merged_css.css');
            }
            $merged_style = fopen($dirStyle.'merged_css.css', "a+");
            fwrite ($merged_style, $mergedCss);
            fclose ($merged_style);
        }
    }

    private function mergeJs()
    {
        if ($this->needToMergeJs()) {
            $dirJavascript = 'install/js/';
            $javascriptDirectory = opendir($dirJavascript);
            $mergedJavascript = '// JavaScript Document'."\n\n";


            //jquery first
            $javascriptLines = file(realpath($dirJavascript.'0_jQuery.js'));
            $mergedJavascript .= '/* 0_jQuery.js */'."\n";
            for ($i=0; $i<count($javascriptLines); $i++) {
                $mergedJavascript .= $javascriptLines[$i];
            }
            $mergedJavascript .= "\n\n";

            while($javascriptFile = @readdir($javascriptDirectory)) {
                if (   substr($javascriptFile, 0, 1) != '.'
                    && substr($javascriptFile, -3) == '.js'
                    && $javascriptFile != 'merged_js.js'
                    && $javascriptFile != '0_jQuery.js'
                ) {
                    $javascriptLines = file(realpath($dirJavascript.$javascriptFile));
                    $mergedJavascript .= '/* '.$javascriptFile.' */'."\n";
                    for ($i=0; $i<count($javascriptLines); $i++) {
                        $mergedJavascript .= $javascriptLines[$i];
                    }
                    $mergedJavascript .= "\n\n";
                }
            }
            closedir($javascriptDirectory);
            if (file_exists($dirJavascript.'merged_js.js')) {
                unlink($dirJavascript.'merged_js.js');
            }
            $merged_javascript = fopen($dirJavascript.'merged_js.js', "a+");
            fwrite ($merged_javascript, $mergedJavascript);
            fclose ($merged_javascript);
        }
    }

    private function needToMergeCss()
    {
        $write = false;
        $dirStyle = 'install/css/';
        $dateModMerged = '1';
        if (file_exists($dirStyle.'merged_css.css')) {
            $dateModMerged = filemtime($dirStyle.'merged_css.css');
        }
        $styleDirectory = opendir($dirStyle);
        while($styleFile = @readdir($styleDirectory)) {
            if (   substr($styleFile, 0, 1) != '.'
                && substr($styleFile, -4) == '.css'
                && $styleFile != 'merged_css.css'
            ) {
                $dateMod = filemtime(realpath($dirStyle.$styleFile));
                if ($dateMod > $dateModMerged) {
                    $write = true;
                }
            }
        }
        closedir($styleDirectory);

        return $write;
    }

    private function needToMergeJs()
    {
        $write = false;
        $dirJavascript = 'install/js/';
        $dateModMerged = '1';
        if (file_exists($dirJavascript.'merged_js.js')) {
            $dateModMerged = filemtime($dirJavascript.'merged_js.js');
        }
        $javascriptDirectory = opendir($dirJavascript);
        while($javascriptFile = @readdir($javascriptDirectory)) {
            if (   substr($javascriptFile, 0, 1) != '.'
                && substr($javascriptFile, -3) == '.js'
                && $javascriptFile != 'merged_js.js'
                && $javascriptFile != 'jQuery_dev.js'
            ) {
                $dateMod = filemtime(realpath($dirJavascript.$javascriptFile));
                if ($dateMod > $dateModMerged) {
                    $write = true;
                }
            }
        }
        closedir($javascriptDirectory);

        return $write;
    }
}
