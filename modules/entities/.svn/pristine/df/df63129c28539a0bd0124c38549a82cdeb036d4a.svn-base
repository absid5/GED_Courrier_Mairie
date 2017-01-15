// LEGACY !!!!!!!
// Change destination entity_id => load new listmodel with script load_listinstance
function change_entity(
	entity_id, 
	path_manage_script, 
	diff_list_id, 
	origin_keyword, 
	display_value_tr, 
	load_listmodel,
    category
) {
    if(category === undefined){
        category = '';
    }
    var div_id = diff_list_id || 'diff_list_div';
    var tr_display_val = display_value_tr || 'table-row';
    var origin_arg = origin_keyword || '';
    var load_listmodel = load_listmodel || 'true';
		
    if($('destination_mandatory'))
    {
        var isMandatory = $('destination_mandatory').style.display;
    }
    else
    {
        var isMandatory = "none";
    }
    if(entity_id != null)
    {
        new Ajax.Request(path_manage_script,
        {
            method:'post',
            parameters: { 
				objectType : 'entity_id',
				objectId : entity_id,
				collId : 'letterbox_coll',
                load_from_model : load_listmodel,
                origin : origin_arg,
                mandatory : isMandatory,
                category : category
            },
                onSuccess: function(answer){
                eval("response = "+answer.responseText);
                //alert(answer.responseText);
                if(response.status == 0 )
                {
                    var diff_list_tr = $('diff_list_tr');
                    var diff_list_div = $(div_id);
                    if(diff_list_div != null)
                    {
                        diff_list_div.innerHTML = response.div_content;
                    }
                    if(diff_list_tr)
                    {
                        diff_list_tr.style.display = tr_display_val;
                    }
                    else
                    {
                        diff_list_div.style.display = 'block';
                    }
                }
                else
                {
                    var diff_list_tr = $('diff_list_tr');
                    var diff_list_div = $(div_id);
                    if(diff_list_div != null)
                    {
                        diff_list_div.innerHTML = '';
                    }
                    if(diff_list_tr)
                    {
                        diff_list_tr.style.display = tr_display_val;
                    }
                    else
                    {
                        diff_list_div.style.display = 'none';
                    }
                    try{
                        $('frm_error').innerHTML = response.error_txt;
                        }
                    catch(e){}
                }
            }
        });
    }
}

// Load list of listmodels to fill select list (index, validate)
// >>> type of list (entity_id, type_id, custom list type)
// >>> type of element to fill (select or list)
// >>> id of element to fill
function select_listmodels(
	objectType, 
	returnElementType, 
	returnElementId
) {
	new Ajax.Request(
		'index.php?display=true&module=entities&page=select_listmodels',
        {
            method:'post',
            parameters: { 
				objectType : objectType,
				returnElementType : returnElementType
            },
            onSuccess: function(answer)
				{
					var returnElement = $(returnElementId);
					if(returnElement != null && returnElement.nodeName.toUpperCase() == returnElementType.toUpperCase()) {
						returnElement.innerHTML += answer.responseText;
					}
				}
        }
	);
}

// Load listmodel to session[origin]
// >>> type of list (entity_id, type_id, custom list type)
// >>> id of list (entity_id, type_id, custom id)
// >>> id of div to fill
// >>> origin keyword
function load_listmodel(
	selectedOption,
	diff_list_id, 
	origin_keyword,
    category
) {
    if(category === undefined){
        category = '';
    }
    var div_id = diff_list_id || 'diff_list_div';
    var origin = origin_keyword || '';
	
	var objectType = selectedOption.getAttribute('data-object_type');
	var objectId = selectedOption.value;
	
	var diff_list_div = $(div_id);
	new Ajax.Request(
		"index.php?display=true&module=entities&page=load_listmodel",
        {
            method:'post',
            parameters: { 
				objectType : objectType,
				objectId : objectId,
                origin : origin,
                category : category
			},
            onSuccess: function(answer){
                eval("response = "+answer.responseText);
                //alert(answer.responseText);
                if(response.status == 0 ) {
					diff_list_div.innerHTML = response.div_content;
                }
                else {
					diff_list_div.innerHTML = '';
                    try{
                        $('frm_error').innerHTML = response.error_txt;
                    } catch(e){}
                }
            }
        }
	);
}

function change_diff_list(
	origin,
	display_value_tr, 
	difflist_div, 
	difflist_tr,
    category,
    specific_role
) {
    if(category === undefined){
        category = '';
    }
    if(specific_role === undefined){
        specific_role = '';
    }
    var list_div = difflist_div || 'diff_list_div';
    var list_div_from_action = 'diff_list_div_from_action';
    var list_tr = difflist_tr || 'diff_list_tr';
    var tr_display_val = display_value_tr || 'table-row';

    new Ajax.Request(
		'index.php?display=true&module=entities&page=load_listinstance',
        {
            method:'post',
            parameters: {
			origin : origin,
            category : category,
            specific_role : specific_role
            },
            onSuccess: function(answer){
                eval("response = "+answer.responseText);
                //alert(answer.responseText);
                if(response.status == 0 )
                {
                    //alert(window.opener.document);
                    var diff_list_tr = window.opener.$(list_tr);
                    var diff_list_div = window.opener.$(list_div);
                    var diff_list_div_from_action = window.opener.$(list_div_from_action);
                    if(diff_list_div != null)
                    {
                        diff_list_div.innerHTML = response.div_content;
                    }
                    if(diff_list_div_from_action != null)
                    {
                        diff_list_div_from_action.innerHTML = response.div_content_action;
                    }
                    if(diff_list_tr != null)
                    {
                        diff_list_tr.style.display = tr_display_val;
                    }
                    if(window.opener.$('save_list_diff')){
                        window.opener.$('save_list_diff').click();
                    }
                    window.close();
                }
                else
                {
                    //alert(response.error_txt);
                    try{
                        $('frm_error').innerHTML = response.error_txt;
                        }
                    catch(e){}
                }
            }
        }
	);
}

function isIdToken(value)
{
    var token = value.match(/[\w_]+/g);
    if(!token)
        return false;
    if(token[0] != value)
        return false;
    else 
        return true;
    
}

function validate_difflist_type() {
  var main_error = $('main_error'); 
  main_error.innerHTML = '';
  
  var difflist_type_id = $('difflist_type_id').value;
  var difflist_type_label = $('difflist_type_label').value;
  
  var allow_entities = 'N';
  
  if($('allow_entities').checked)
	allow_entities = 'Y';
  
  var difflist_type_roles = "";
  
  var selected_roles = $('selected_roles');
  for (var i=0;i<selected_roles.length;i++) {
	difflist_type_roles = difflist_type_roles + selected_roles[i].value + " ";
  }
  
  var idValid = isIdToken(difflist_type_id);
  if(idValid == false) {
      main_error.innerHTML = 'Identifiant invalide (A-Z, a-z, 0-9 et _)';
      return;
  }
  main_error.innerHTML = '';
    
  new Ajax.Request(
    'index.php?module=entities&page=admin_difflist_type_validate&display=true',
    { 
      method: 'post',
      parameters: 
      {
        mode : $('mode').value,
		difflist_type_id : difflist_type_id,
        difflist_type_label : difflist_type_label,
		difflist_type_roles : difflist_type_roles,
		allow_entities : allow_entities
      },
      onSuccess: function(transport) {
          var responseText = transport.responseText.replace(new RegExp("(\r|\n)", "g"), "");
          if(responseText)
            $('difflist_type_messages').innerHTML += responseText;
          else  
            goTo('index.php?module=entities&page=admin_difflist_types');
        }
    }
  );
  
}

function saveListDiff(
    mode,
    table, 
    collId, 
    resId,
    userId,
    concatList,
    onlyCC
) {
    new Ajax.Request(
        'index.php?display=true&module=entities&page=save_list_diff',
        {
            method:'post',
            parameters: {
                mode : mode,
                table : table,
                collId : collId,
                resId : resId,
                userId : userId,
                concatList : concatList,
                onlyCC : onlyCC,
            },
            onSuccess: function(answer){
                eval("response = "+answer.responseText);
                //alert(answer.responseText);
                if (response.status == 0) {
                    //alert(window.opener.document);
                    var div_diff_list_message = $('div_diff_list_message');
                    if (div_diff_list_message != null) {
                        div_diff_list_message.innerHTML = response.div_content;
                        Element.hide.delay(2, 'div_diff_list_message');
                    }
                }
            }
        }
    );
}