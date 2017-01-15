function showFileplanForm (path, extraValues, width, height) {
    
    if(typeof(width)==='undefined'){
        var width = '500px';
    }
    if(typeof(height)==='undefined'){
        var height = '230px';
    } 
    if ($('divList')) {
        $('divList').style.display = 'none';
    }
    new Ajax.Request(path,
    {
        method:'post',
        parameters: { url : path,
                      'values[]': extraValues
                    }, 
        onLoading: function(answer) {
            //show loading
            // $('loading').style.display='block';
        },  
        onSuccess: function(answer) {
            eval("response = "+answer.responseText);
            if(response.status == 0){
                var modal_content = response.content;
                createModal(modal_content, 'modal_fileplan', height, width); 
                eval(response.exec_js);
            } else {
                window.top.$('main_error').innerHTML = response.error;
            }
            // $('loading').style.display='none';
        }
    });
}

function validFileplanForm(path, form_id) {

	new Ajax.Request(path,
	{
		asynchronous:false,
		method:'post',
		parameters: Form.serialize(form_id),
		encoding: 'UTF-8',
		onLoading: function(answer) {
			// $('loading').style.display='inline';
		},
		onSuccess: function(answer) {
			eval("response = "+answer.responseText);
			if(response.status == 0){
				eval(response.exec_js);
			} else {
				alert(response.error);
			}
			// $('loading').style.display='none';
		}
	});
}

function execFileplanScript(path) {

    new Ajax.Request(path,
    {
        method:'post',
        parameters: { url : path
                    },   
        onLoading: function(answer) {
            // $('loading').style.display='block';
        },                        
        onSuccess: function(answer){
            eval("response = "+answer.responseText);
            if(response.status == 0){
                eval(response.exec_js);
            } else {
                window.top.$('main_error').innerHTML = response.error;
            }
            // $('loading').style.display='none';
        }
    });
}

function oneIsChecked(idForm, fieldName) {
    
    if(typeof(fieldName)==='undefined'){
        var fieldName = 'field[]';
    }
    var count = 0;
    var thisForm =  document.getElementById(idForm);
    for(var i=0; i < thisForm.elements.length; i++) {
        if (thisForm.elements[i].type == 'checkbox' && thisForm.elements[i].name == fieldName) {
            if(thisForm.elements[i].checked == true) count ++;
        }
    }
    // alert(count);
    var oneIsChecked = (count > 0) ? true: false;

    return oneIsChecked;
}

function getFieldsCheckedValues(idForm, fieldName) {

    if(typeof(fieldName)==='undefined'){
        var fieldName = 'field[]';
    }
    // var checkedValues = '';
    var checkedValues = [];
    
    var thisForm =  document.getElementById(idForm);
     for(var i=0; i < thisForm.elements.length; i++) {
        if (thisForm.elements[i].type == 'checkbox' && thisForm.elements[i].name == fieldName) {
            if(thisForm.elements[i].checked == true) 
                checkedValues.push(thisForm.elements[i].value);
                // checkedValues += thisForm.elements[i].value + ',';
        }
    }
    // alert(checkedValues);
    return checkedValues;
}

function showFileplanList(path, idForm, width, height, error) {

    var isChecked = oneIsChecked(idForm);
    // alert(isChecked);
    if(isChecked == true) {
        
        var checkedValues = getFieldsCheckedValues(idForm);
        
        window.top.$('main_error').innerHTML = '';
        window.top.$('main_info').innerHTML = '';
        showFileplanForm(path, checkedValues, width, height);
        
    } else {
        window.top.$('main_error').innerHTML = error;
    }
}

function loadFileplanList(idField, idList, path) {
    
    var fieldValue = $(idField).value;
    
    new Ajax.Request(path,
    {
        method:'post',
        parameters: { url : path,
                      param: fieldValue,
                    },   
        onLoading: function(answer) {
            //show loading
            $('loadingFileplan').style.display='block';
        },                        
        onSuccess: function(answer){
            $(idList).innerHTML = answer.responseText;
            $('loadingFileplan').style.display='none';
			evalMyScripts(idList);
        }
    });
}

function saveCheckedState(path, checkboxId) {

	 if(typeof(checkboxId) !='undefined'){

		new Ajax.Request(path,
		{
			method:'post',
			parameters: { url : path,
						  'value': checkboxId.value,
						  'checked': checkboxId.checked
						}, 
			onSuccess: function(answer) {
				eval("response = "+answer.responseText);
				if(response.status == 0){
					eval(response.exec_js);
				} else {
					window.top.$('main_error').innerHTML = response.error;
				}
			}
		});
	}
}
