<?php
/*
*
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
* @brief Displays user secondary baskets management data in the user administration
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

$core_tools = new core_tools();
$core_tools->test_admin('admin_users', 'apps');

?>

<script type="text/javascript">
    function test_form_secondary()
    {
        var error_num = check_form_baskets_secondary("secondary_baskets");
        if ( error_num == 1) {
            document.getElementById('secondary_baskets').submit();
        } else if(error_num == 4) {
            //alert("<?php echo _CHOOSE_SECONDARY_BASKET;?>");
            document.getElementById('secondary_baskets').submit();
        } else {
            alert("<?php echo _FORM_ERROR;?>");
        }
    }
</script>
<?php
require_once('modules/basket/class/class_modules_tools.php');
$bask = new basket();
$arrBasketsSecondProfile = array();
$arrBasketsSecondProfile = $bask->getBasketsOfSecondaryProfiles($_SESSION['m_admin']['users']['user_id']);
/*echo '<pre>';
print_r($arrBasketsSecondProfile);
echo '</pre>';*/
if (!empty($arrBasketsSecondProfile)) {
    $modalContentSecondary = '';
    $modalContentSecondary = $bask->chooseSecondaryBasketsList(
        $arrBasketsSecondProfile, 
        count($arrBasketsSecondProfile), 
        $_SESSION['m_admin']['users']['user_id'],
        "listingbasket specsmall"
    );
    ?>
    <div>
        <script type="text/javascript">
            var modal_content_secondary = '<?php echo preg_replace("/'/", "\'",$modalContentSecondary);?>';
        </script>
        <div class="h2_title">
            <a href="javascript://" onclick="createModal(modal_content_secondary, 'modal_secondary_baskets', <?php 
                if(count($arrBasketsSecondProfile) >0) {
                    ?>'600px', '1000px'<?php 
                }else{
                    ?>'100px', '320px'<?php 
                }?>);window.location.href='#top';"><i class="fa fa-inbox fa-3x" title="" /></i> <?php 
                    echo _MANAGE_SECONDARY_USER_BASKETS; 
                ?> </a>
        </div>
        <p id="abs">
            <?php echo _MANAGE_SECONDARY_USER_BASKETS_TEXT;?>
        </p>
    </div>
    <?php
} else {
    ?>
    <div>
        <div class="h2_title">
            <i class="fa fa-inbox fa-3x" title="" /></i> <?php 
                echo _MANAGE_SECONDARY_USER_BASKETS; 
            ?>
        </div>
        <p id="abs">
            <?php echo _MANAGE_SECONDARY_USER_BASKETS_HELP;?><br />
            <?php echo _MANAGE_SECONDARY_USER_BASKETS_HELP_COMPL;?><br />
        </p>
    </div>
    <?php
}

