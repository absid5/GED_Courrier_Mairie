function showNotesForm(path, width, height) {
    
    if(typeof(width)==='undefined'){
        var width = '800';
    }
    
    if(typeof(height)==='undefined'){
        var height = '480';
    }  
    
    new Ajax.Request(path,
    {
        method:'post',
        parameters: { url : path
                    },  
        onSuccess: function(answer) {
            eval("response = "+answer.responseText);
           
            if(response.status == 0){
             
                var modal_content = convertToTextVisibleNewLine(response.content);
                createModal(modal_content, 'form_notes', height, width); 
            } else {
                window.top.$('main_error').innerHTML = response.error;
            }
        }
    });
}

function validNotesForm (path, form_id) {

    new Ajax.Request(path,
    {
        asynchronous:false,
        method:'post',
        parameters: Form.serialize(form_id),
        encoding: 'UTF-8',                       
        onSuccess: function(answer){
            eval("response = "+answer.responseText);
            if(response.status == 0){
                destroyModal('form_notes'); 
                eval(response.exec_js);
            } else {
                alert(response.error);
            }
        }
    });
}

function addTemplateToNote(templateNotes, path)
{
    new Ajax.Request(path,
    {
        method:'post',
        parameters: { 
                      templateId : templateNotes
                    }, 
        onSuccess: function(answer) {
            eval("response = "+answer.responseText);
            if (response.status == 0) {
                var strContent = response.content;
                var reg = new RegExp(/\\n/gi);
                var strContentReplace = strContent.replace(reg, '\n');
                $('notes').value = $('notes').value + ' ' + strContentReplace;
            } else {
                window.top.$('main_error').innerHTML = response.error;
            }
        }
    });
}