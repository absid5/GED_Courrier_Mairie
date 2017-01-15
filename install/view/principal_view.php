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
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Language" content="<?php functions::xecho($Class_Install->getActualLang());?>" />
        <title>Maarch > <?php functions::xecho($longTitle);?></title>
        <link rel="stylesheet" href="css/merged_css.css" />
        <script src="js/merged_js.js"></script>
    </head>

    <body onLoad="minHeightOfSection();heightOfLicenceOverflow();" onResize="minHeightOfSection();heightOfLicenceOverflow();">
        <div align="center">
            <div id="fullWrapper" class="fullWrapper">
                <header id="header">
                    <?php include('install/view/includes/header.php');?>
                </header>
                <div class="line"></div>
                <section id="section">
                    <br />
                    <?php include('install/view/includes/progress.php');?>
                    <br />
                    <?php include('install/view/'.$view.'_view.php');?>
                    <br />
                </section>
                <!-- <div class="line"></div> -->
                <!-- <footer id="footer">
                    <?php include('install/view/includes/footer.php');?>
                </footer> -->
            </div>
        </div>
    </body>
</html>
