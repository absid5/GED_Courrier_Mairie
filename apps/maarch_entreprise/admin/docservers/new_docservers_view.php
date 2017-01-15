<?php
//CONTROLLER
    //INIT
        $noModeUri = getDependantUri(
            'objectId',
            getDependantUri(
                'mode',
                $_SERVER['REQUEST_URI']
            )
        );
    
        $modeList   = false;
        $modeCreate = false;
        $modeRead   = false;
        $modeUpdate = false;
        
        $formFields = array(
            'docserver_id' => array(
                'show'     => true,
                'input'    => 'text',
                
            ),
            'docserver_type_id' => array(
                'show'          => true,
                'input'         => 'text',
                
            ),
            'device_label' => array(
                'show'     => true,
                'input'    => 'text',
                
            ),
            'is_readonly'     => array(
                'show'        => true,
                'input'       => 'radio',
                'radioValues' => array(
                    'Oui'     => 'Y',
                    'Non'     => 'N',
                    
                ),
                
            ),
            'size_format'        => array(
                'show'           => true,
                'jsEvent'        => 'onChange="convertSizeMoGoTo($(this).value);" ',
                'input'          => 'select',
                'selectValues'   => array(
                    'Megaoctets' => 'Mo',
                    'Gigaoctets' => 'Go',
                    'Teraoctets' => 'To',
                    
                ),
                
            ),
            'enabled'   => array(
                'show'  => false,
                
            ),
            'size_limit_number' => array(
                'show'          => true,
                'input'         => 'hidden',
                
            ),
            'size_limit_number_inForm' => array(
                'show'          => true,
                'input'         => 'text',
                'jsEvent'       => 'onKeyUp="update_conversion();" ',
                
            ),
            'actual_size_number' => array(
                'show'                 => true,
                'input'                => 'hidden',
                
            ),
            'actual_size_number_inForm' => array(
                'show'                 => true,
                'input'                => 'text',
                'readonly'             => true,
                
            ),
            'pourcentage_size' => array(
                'show'                 => true,
                'input'                => 'text',
                
            ),
            'path_template' => array(
                'show'      => true,
                'input'     => 'text',
                
            ),
            'ext_docservers_info' => array(
                'show'            => false,
                
            ),
            'chain_before' => array(
                'show'     => false,
                
            ),
            'chain_after' => array(
                'show'    => false,
                
            ),
            'creation_date' => array(
                'show'      => false,
                
            ),
            'closing_date' => array(
                'show'     => false,
                
            ),
            'coll_id'   => array(
                'show'  => true,
                'input' => 'text',
                
            ),
            'priority_number' => array(
                'show'        => true,
                'input'       => 'text',
                
            ),
            'docserver_location_id' => array(
                'show'              => true,
                'input'             => 'text',
                
            ),
            'adr_priority_number' => array(
                'show'            => true,
                'input'           => 'text',
                
            ),
            
        );
        
        $formButtons = array(
            'save'     => array(
                'show' => false,
                'jsEvent' => 'saveWithXSD',
                
            ),'add'     => array(
                'show' => false,
                'jsEvent' => 'saveWithXSD',
                
            ),
            'cancel'   => array(
                'show' => false,
                'jsEvent' => 'onClick="window.location.href=\''.$noModeUri.'\'"; ',
                
            ),
            'back'       => array(
                'show'   => false,
                'jsEvent' => 'onClick="window.location.href=\''.$noModeUri.'\'"; ',
                
            ),
            
        );
    
    //Titre de la page
        $messageController = new MessageController();
        $messageController->loadMessageFile($params['viewLocation'] . '/xml/' . $params['objectName'] . '_Messages.xml');
        
        if ($params['mode'] == 'list') {
            $modeList = true;
            $titleText = $messageController->getMessageText('docservers_list', false, array(count($dataObjectList->{$params['objectName']})));
            
        } elseif ($params['mode'] == 'create') {
            $modeCreate = true;
            $titleText = $messageController->getMessageText('docservers_create');
                
        } elseif ($params['mode'] == 'read') {
            $modeRead = true;
            //$titleText = getLabel(_READ).' '.getLabel($objectLabel);
            $titleText = $messageController->getMessageText('docservers_read');
                
        } elseif ($params['mode'] == 'update') {
            $modeUpdate = true;
            $titleText = $messageController->getMessageText('docservers_update');
            
        }
        
    //make list or form
        $columnsLabels = $messageController->getTexts(
            $params['objectName'] . '.'
        );
                
        if ($modeList) {
            /* just show the list */
            $str_returnShow = $listContent;
            
        } elseif ($modeCreate) {
            $formButtons['add']['show'] = true;
            $formButtons['cancel']['show'] = true;            
            $str_returnShow = makeForm($formFields, $formButtons, $dataObject, $schemaPath, $params, $noModeUri, $columnsLabels);
            
        } elseif ($modeRead) {
            foreach($formFields as $key => $value) {
                $formFields[$key]['readonly'] = true;
            }
            
            $formButtons['back']['show'] = true;
            
            $str_returnShow = makeForm($formFields, $formButtons, $dataObject, $schemaPath, $params, $noModeUri, $columnsLabels);
            
        } elseif ($modeUpdate) {
            $formFields['docserver_id']['readonly'] = true;
            $formButtons['save']['show'] = true;
            $formButtons['cancel']['show'] = true;
            
            $str_returnShow = makeForm($formFields, $formButtons, $dataObject, $schemaPath, $params, $noModeUri, $columnsLabels);
        }
    
        
    //default JS
        $str_defaultJs .= '<script>';
            if ($modeCreate || $modeRead || $modeUpdate) {
                $str_defaultJs .= 'convertSizeMoGoTo(\'Mo\');';
                $str_defaultJs .= 'showPercent();';
            }
        $str_defaultJs .= '</script>';
?>

<!--VIEW-->
<script>
    function convertSizeMoGoTo(targetUnit) {
        var size_limit_number_inForm = false;
        var size_limit_numberinOctet = $('size_limit_number').value;
        if (targetUnit == 'Mo') {
            size_limit_number_inForm = (size_limit_numberinOctet / (1000 * 1000));
        } else if(targetUnit == 'Go') {
            size_limit_number_inForm = (size_limit_numberinOctet / (1000 * 1000 * 1000));
        } else if(targetUnit == 'To') {
            size_limit_number_inForm = (size_limit_numberinOctet / (1000 * 1000 * 1000 * 1000));
        }
        
        $('size_limit_number_inForm').setValue(size_limit_number_inForm);
        
        
        var actual_size_number_inForm = false;
        var actual_size_numberinOctet = $('actual_size_number').value;
        if (targetUnit == 'Mo') {
            actual_size_number_inForm = (actual_size_numberinOctet / (1000 * 1000));
        } else if(targetUnit == 'Go') {
            actual_size_number_inForm = (actual_size_numberinOctet / (1000 * 1000 * 1000));
        } else if(targetUnit == 'To') {
            actual_size_number_inForm = (actual_size_numberinOctet / (1000 * 1000 * 1000 * 1000));
        }
        
        $('actual_size_number_inForm').setValue(actual_size_number_inForm);
        
    }
    
    function update_conversion()
    {
        var actualUnit = $('size_format').value;
        var size_limit_numberinOctet = false;
        var size_limit_number_inForm = $('size_limit_number_inForm').value;
        
        if (actualUnit == 'Mo') {
            size_limit_numberinOctet = (size_limit_number_inForm * (1000 * 1000));
        } else if (actualUnit == 'Go') {
            size_limit_numberinOctet = (size_limit_number_inForm * (1000 * 1000 * 1000));
        } else if (actualUnit == 'To') {
            size_limit_numberinOctet = (size_limit_number_inForm * (1000 * 1000 * 1000 * 1000));
        }
        
        $('size_limit_number').setValue(size_limit_numberinOctet);
        
        showPercent();
    }
    
    function showPercent() {
        var size_limit = $('size_limit_number').value;
        var actual_size = $('actual_size_number').value;
        
        var percent = false;
        var ratio = false;
        
        ratio = actual_size / size_limit;
        
        percent = ratio * 100;
        
        $('pourcentage_size').setValue(percent + ' %');
        $('pourcentage_size').style.backgroundColor = 'rgba(200, 35, 45, ' + ratio + ')';
    }
    
    function getCheckedValue(radioObj) {
        if(!radioObj)
            return "";
        var radioLength = radioObj.length;
        if(radioLength == undefined)
            if(radioObj.checked) {
                return radioObj.value;
            } else {
                return "";
            }
        for(var i = 0; i < radioLength; i++) {
            if(radioObj[i].checked) {
                return radioObj[i].value;
            }
        }
        return "";
    }
    
    
<?php if ($modeList) { ?>

    function show_goToTop() {
        var scrollHeight = f_filterResults (
            window.pageYOffset ? window.pageYOffset : 0,
            document.documentElement ? document.documentElement.scrollTop : 0,
            document.body ? document.body.scrollTop : 0
        );
        
        var listHeight = $('<?php functions::xecho($params['objectName']); ?>_list').getHeight();
        
        var innerHeight = window.innerHeight;
        var innerWidth  = window.innerWidth;
        var half_innerWidth  = (innerWidth / 2);
        
        var goToTopHeight = $('goToTop').getHeight();
        var goToTopWidth  = $('goToTop').getWidth();
        
        var top  = (innerHeight - (goToTopHeight + 68));
        var left = (half_innerWidth + 500 + 10);
        
        var opacity = (scrollHeight / (listHeight - innerHeight));
    
        if (opacity < 0.01) {
            $('goToTop').style.top     = '0px';
            $('goToTop').style.left    = '0px';
            $('goToTop').style.display = 'none';
            return ;
        } else if (opacity > 1) {
            opacity = 1;
        }
        
        $('goToTop').style.top     = top + 'px';
        $('goToTop').style.left    = left + 'px';
        $('goToTop').style.display = 'block';
        $('goToTop').style.opacity = opacity;
        return;
    }
    
    Event.observe(window, 'scroll', function() {
        show_goToTop();
    });
    
<?php } ?>

</script>
<h1>
    <i class="fa fa-hdd-o fa-2x"></i>
    <?php functions::xecho($titleText);?>
</h1>
<div class="<?php echo $params['objectName'] ?>">
    <div id="returnAjax"><br /><br /></div>
    <?php functions::xecho($str_returnShow);?>
</div>
<?php functions::xecho($str_defaultJs);?>
