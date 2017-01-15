<?php

/*
*   Copyright 2008-2011 Maarch
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

/*
* @brief view technical informations
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
    . 'class_request.php');
require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
    . 'class_functions.php');
require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
    . 'class_security.php');
require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
    . 'class_resource.php');
require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
    . 'docservers_controler.php');
$func = new functions();
$coreTools = new core_tools();
$coreTools->test_user();
$coreTools->load_lang();
$security = new security();
$db = new Database();

if ($coreTools->test_service('view_technical_infos', 'apps', false)) {
    if (!isset ($_SESSION['collection_id_choice'])
        || empty ($_SESSION['collection_id_choice'])
    ) {
        $collId = 'res_coll';
        $table = 'res_view';
        $adrTable = 'adr_x';
        $isView = true;
    } else {
        $collId = $_SESSION['collection_id_choice'];
        $table = $security->retrieve_view_from_coll_id($collId);
        $isView = true;
        if (empty ($table)) {
            $table = $security->retrieve_table_from_coll($collId);
            $isView = false;
        }
    }
    for ($cptColl = 0;$cptColl < count($_SESSION['collections']);$cptColl++) {
        if ($table == $_SESSION['collections'][$cptColl]['table'] 
            || $table == $_SESSION['collections'][$cptColl]['view']
        ) {
            $adrTable = $_SESSION['collections'][$cptColl]['adr'];
        }
    }
    $selectRes = "SELECT * FROM " . $table . " WHERE res_id = ?";
    $stmt = $db->query($selectRes, array($_SESSION['doc_id']));
    $res = $stmt->fetchObject();
    $typist = $res->typist;
    $format = $res->format;
    $filesize = $res->filesize;
    $docserverId = $res->docserver_id;
    $path = $res->path;
    $fileName = $res->filename;
    $creationDate = functions::format_date_db($res->creation_date, false);
    $fingerprint = $res->fingerprint;
    $offsetDoc = $res->offset_doc;
    $workBatch = $res->work_batch;
    $pageCount = $res->page_count;
    $isPaper = $res->is_paper;
    $scanDate = functions::format_date_db($res->scan_date);
    $scanUser = $res->scan_user;
    $scanLocation = $res->scan_location;
    $scanWkstation = $res->scan_wkstation;
    $scanBatch = $res->scan_batch;
    $docLanguage = $res->doc_language;
    $policyId = $res->policy_id;
    $cycleId = $res->cycle_id;
    $isMultiDs = $res->is_multi_docservers;
    if ($isMultiDs == 'Y') {
        $adr = array();
        $resource = new resource();
        $whereClause = ' and 1=1';
        $adr = $resource->getResourceAdr(
            $table, 
            $_SESSION['doc_id'], 
            $whereClause, 
            $adrTable
        );
    }
    ?>
    <dt class="fa fa-cogs" style="font-size:2em;padding-left: 15px;padding-right: 15px;" title="<?php echo _TECHNICAL_INFORMATIONS;?>"><sup><span style="font-size: 10px;display: none;" class="nbResZero"></span></sup></dt>
    <dd>
        <h2>
            <span class="date">
                <b><?php echo _SOURCE_FILE_PROPERTIES;?></b>
            </span>
        </h2>
        <br/>
        <table cellpadding="2" cellspacing="2" border="0" class="block forms details" width="100%">
            <tr>
                <th align="left" class="picto">
                    <i class="fa fa-user fa-2x" title="<?php echo _TYPIST;?>"></i>
                </th>
                <td align="left" width="200px"><?php echo _TYPIST;?> :</td>
                <td>
                    <input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($typist);?>"  />
                </td>
                <th align="left" class="picto">
                    <i class="fa fa-square fa-2x" title="<?php echo _SIZE;?>"></i>
                </th>
                <td align="left" width="200px"><?php echo _SIZE;?> :</td>
                <?php
                $txtByte = '';
                if (isset($_SESSION['lang'])) {
                    $txtByte = $_SESSION['lang']['txt_byte'];
                }
                ?>
                <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($filesize." ".$txtByte." ( ".round($filesize / 1024, 2)."K )");?>" /></td>
            </tr>
            <tr class="col">
                <th align="left" class="picto">
                    <i class="fa fa-file-image-o fa-2x" title="<?php echo _FORMAT;?>"></i>
                </th>
                <td align="left"><?php echo _FORMAT;?> :</td>
                <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($format);?>" size="40"  /></td>
                <th align="left" class="picto">
                    <i class="fa fa-calendar fa-2x" title="<?php echo _CREATION_DATE;?>"></i>
                </th>
                <td align="left"><?php echo _CREATION_DATE;?> :</td>
                <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($func->format_date_db($creationDate, false));?>"/></td>
            </tr>
            <tr>
                <th align="left" class="picto">
                    <i class="fa fa-lock fa-2x" title="<?php echo _FINGERPRINT;?>"></i>
                </th>
                <td align="left"><?php echo _FINGERPRINT;?> :</td>
                <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($fingerprint);?>"  title="<?php functions::xecho($fingerprint);?>" alt="<?php functions::xecho($fingerprint);?>" /></td>

                <th align="left" class="picto">
                    <i class="fa fa-gears fa-2x" title="<?php echo _WORK_BATCH;?>"></i>
                </th>
                <td align="left"><?php echo _WORK_BATCH;?> :</td>
                <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($workBatch);?>" title="<?php functions::xecho($workBatch);?>" alt="<?php functions::xecho($workBatch);?>" /></td>
            </tr>
        </table>
        <br>
        <?php 
        if ($coreTools->is_module_loaded('life_cycle')) {
            ?>
            <h2>
            <span class="date">
                <b><?php echo _LIFE_CYCLE;?></b>
            </span>
            </h2>
            <br/>
            <table cellpadding="2" cellspacing="2" border="0" class="block forms details" width="100%">
                <tr>
                    <th align="left" class="picto">
                        &nbsp;
                    </th>
                    <td align="left" width="200px"><?php echo _LC_POLICY_ID;?> :</td>
                    <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($policyId);?>"  /></td>
                    <th align="left" class="picto">
                        &nbsp;
                    </th>
                    <td align="left" width="200px"><?php echo _CYCLE_ID;?> :</td>
                    <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($cycleId);?>" /></td>
                </tr>
            </table>
            <?php
        }
        ?>
        <br>
        <h2>
        <span class="date">
            <b><?php echo _DOCSERVERS;?></b>
        </span>
        </h2>
        <br/>
        <?php
        $docserversControler = new docservers_controler();
        if ($isMultiDs == 'Y') {
            for ($cptAdr = 0;$cptAdr < count($adr[0]);$cptAdr++) {
                $docserver = $docserversControler->get(
                    $adr[0][$cptAdr]['docserver_id']
                );
                echo '<h4>' . functions::xssafe($adr[0][$cptAdr]['docserver_id'])
                    . ' (' . functions::xssafe($docserver->device_label) . ')</h4>';
                ?>
                <table cellpadding="2" cellspacing="2" border="0" class="block forms details" width="100%">
                    <tr>
                        <th align="left" class="picto">
                            &nbsp;
                        </th>
                        <td align="left" width="200px"><?php echo _PATH_TEMPLATE;?> :</td>
                        <td><input type="text" class="readonly" readonly="readonly" value="<?php echo str_replace('#', '/', functions::xssafe($adr[0][$cptAdr]['path']));?>"/></td>
                        <th align="left" class="picto">
                            &nbsp;
                        </th>
                        <td align="left" width="200px"><?php echo _FILE;?> :</td>
                        <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($adr[0][$cptAdr]['filename']);?>" /></td>
                    </tr>
                    <tr>
                        <th align="left" class="picto">
                            &nbsp;
                        </th>
                        <td align="left" width="200px"><?php echo _FORMAT;?> :</td>
                        <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($adr[0][$cptAdr]['format']);?>"/></td>
                        <th align="left" class="picto">
                            &nbsp;
                        </th>
                        <td align="left" width="200px"><?php echo _FINGERPRINT;?> :</td>
                        <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($adr[0][$cptAdr]['fingerprint']);?>" /></td>
                    </tr>
                    <tr>
                        <th align="left" class="picto">
                            &nbsp;
                        </th>
                        <td align="left" width="200px"><?php echo _OFFSET;?> :</td>
                        <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($adr[0][$cptAdr]['offset_doc']);?>"/></td>
                        <th align="left" class="picto">
                            &nbsp;
                        </th>
                        <td align="left" width="200px"><?php echo _ADR_PRIORITY;?> :</td>
                        <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($adr[0][$cptAdr]['adr_priority']);?>" /></td>
                    </tr>
                </table>
                <?php
            }
        } else {
            $docserver = $docserversControler->get($docserverId);
           echo '<h4>' . functions::xssafe($docserverId) 
                    . ' (' . functions::xssafe($docserver->device_label) . ')</h4>';
            ?>
            <table cellpadding="2" cellspacing="2" border="0" class="block forms details" width="100%">
                <tr>
                    <th align="left" class="picto">
                        &nbsp;
                    </th>
                    <td align="left" width="200px"><?php echo _PATH_TEMPLATE;?> :</td>
                    <td><input type="text" class="readonly" readonly="readonly" value="<?php echo functions::xssafe(str_replace('#', '/', $path));?>"/></td>
                    <th align="left" class="picto">
                        &nbsp;
                    </th>
                    <td align="left" width="200px"><?php echo _FILE;?> :</td>
                    <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($fileName);?>" /></td>
                </tr>
                <tr>
                    <th align="left" class="picto">
                        &nbsp;
                    </th>
                    <td align="left" width="200px"><?php echo _FORMAT;?> :</td>
                    <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($format);?>"/></td>
                    <th align="left" class="picto">
                        &nbsp;
                    </th>
                    <td align="left" width="200px"><?php echo _FINGERPRINT;?> :</td>
                    <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($fingerprint);?>" /></td>
                </tr>
                <tr>
                    <th align="left" class="picto">
                        &nbsp;
                    </th>
                    <td align="left" width="200px"><?php echo _OFFSET;?> :</td>
                    <td><input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($offsetDoc);?>"/></td>
                    <th align="left" class="picto">
                        &nbsp;
                    </th>
                    <td align="left" width="200px"><?php echo _ADR_PRIORITY;?> :</td>
                    <td><input type="text" class="readonly" readonly="readonly" value="1" /></td>
                </tr>
            </table>
            <?php
        }
        ?>
        <br>
        
    </dd>
    <?php
}
