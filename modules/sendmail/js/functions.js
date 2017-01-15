var addEmailAdress = function (idField, idList, theUrlToListScript, paramNameSrv, minCharsSrv) {
     new Ajax.Autocompleter(
         idField,
         idList,
         theUrlToListScript,
         {
             paramName: paramNameSrv,
             minChars: minCharsSrv,
             tokens: ',',
             afterUpdateElement:extractEmailAdress
         });
 };

/*
function addTemplateToEmail(modele){
    $(body_from_html).value = modele + '<br>';
    tinyMCE.execCommand('mceInsertContent',false,modele);  
}
*/

function addTemplateToEmail(templateMails, path){

    new Ajax.Request(path,
    {
        method      :'post',
        parameters  :{
                        templateId : templateMails
                     },
        onSuccess   :function(answer){
            eval("response = " + answer.responseText);
            if (response.status == 0) {
                var strContent = response.content;
                //var strContentReplace = strContent.replace(reg, '\n') + '<p></p>';
                //var strContentReplace = strContent.replace(/\\n/g, '<p>') + '<p><p>';
                var strContentReplace = strContent.replace(/\\n/g, '');
                //tinyMCE.execCommand('mceInsertContent',false,strContentReplace); 
                tinyMCE.execCommand('mceSetContent',false,strContentReplace);
            } 

            /*
            else {
                window.top.$('main_error').innerHTML = response.error;
            }
            */
        }
    });
}

function changeSignature(selected, mailSignaturesJS){
    var nb = selected.getAttribute('data-nb');
    var body = $('body_from_html_ifr').contentWindow.document.getElementById("tinymce");
    var customTag = body.getElementsByTagName("mailSignature");

    if (customTag.length == 0) {
        body.innerHTML += "<mailSignature>toto</mailSignature>";
        customTag = body.getElementsByTagName("mailSignature");
    }

    if (nb >= 0) {
        customTag[0].innerHTML = mailSignaturesJS[nb].signature;
    } else {
        customTag[0].innerHTML = "";
    }
}

function showEmailForm(path, width, height, iframe_container_id) {
    
    if(typeof(width)==='undefined'){
        var width = '820px';
    }
    
    if(typeof(height)==='undefined'){
        var height = '545px';
    }  
	
    if(typeof(iframe_container_id)==='undefined'){
        var iframe_container_id = '';
    }  
    new Ajax.Request(path,
    {
        method:'post',
        parameters: { url : path
                    },  
        onSuccess: function(answer) {
            eval("response = "+answer.responseText);
           
            if(response.status == 0){
				console.log('Height = '+height);
				console.log('Width = '+width);
                var modal_content = convertToTextVisibleNewLine(response.content);
                createModal(modal_content, 'form_email', height, width,'',iframe_container_id); 
            } else {
                window.top.$('main_error').innerHTML = response.error;
            }
        }
    });
}

function updateAdress(path, action, adress, target, array_index, email_format_text_error) {
    
    if (validateEmail(adress) === true) {
        
        new Ajax.Request(path,
        {
            method:'post',
            parameters: { url : path,
                          'for': action,
                          email: adress,
                          field: target,
                          index: array_index
                        },
            onLoading: function(answer) {
                $('loading_' + target).style.display='inline';
            },
            onSuccess: function(answer) {
                eval("response = "+answer.responseText);
                if(response.status == 0){
                    $(target).innerHTML = response.content;
                    if (action == 'add') {$('email').value = '';}
                } else {
                    alert(response.error);
                    eval(response.exec_js);
                }
                // $('loading_' + target).style.display='none';
            }
        });
    } else {
        if(typeof(email_format_text_error) == 'undefined'){
            email_format_text_error = 'Email format is not available!';
        }
        alert(email_format_text_error);
    }
}

function validEmailForm (path, form_id) {
	// var content = tinyMCE.get('body_from_html').getContent(); // 
	// alert(content);
	tinyMCE.triggerSave();
    new Ajax.Request(path,
    {
        asynchronous:false,
        method:'post',
        // parameters: Form.serialize(form_id)+ '&body_from_html=' + content,   
        parameters: Form.serialize(form_id),   
        encoding: 'UTF-8',                       
        onSuccess: function(answer){
            eval("response = "+answer.responseText);
            if(response.status == 0){
                eval(response.exec_js);
                window.parent.destroyModal('form_email'); 
            } else {
                alert(response.error);
                eval(response.exec_js);
            }
        }
    });
}

function validEmailFormForSendToContact (path, form_id, path2, status) {
    // var content = tinyMCE.get('body_from_html').getContent(); // 
    // alert(content);
    tinyMCE.triggerSave();
    new Ajax.Request(path,
    {
        asynchronous:false,
        method:'post',
        // parameters: Form.serialize(form_id)+ '&body_from_html=' + content,   
        parameters: Form.serialize(form_id),   
        encoding: 'UTF-8',                       
        onSuccess: function(answer){
            eval("response = "+answer.responseText);
            if(response.status == 0){
                eval(response.exec_js);
                changeStatusForActionSendToContact(path2, status)
                window.parent.destroyModal('form_email'); 

            } else {
                alert(response.error);
                eval(response.exec_js);
            }
        }
    });
}

 function changeStatusForActionSendToContact(path, status){
    new Ajax.Request(path,
    {
        asynchronous:false,
        method:'post',
        parameters: {status : status},   
        encoding: 'UTF-8',                       
        onSuccess : function(){
                  //window.top.location.reload();
                  parent.document.getElementById('storage').click();
              }
    });
 }

function extractEmailAdress(field, item) {
    var fullAdress = item.innerHTML;
    var email = fullAdress.match(/\(([^)]+)\)/)[1];
    field.value = email;
}

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
 
function switchMode(action) {
    var div = document.getElementById(mode+"_mode");
    div.style.display = "block";
    if(action == "show") {
        div.style.display = "none"; // Hide the current div.
        mode = (mode === 'html')? 'raw' : 'html';      // switch the mode
        document.getElementById("is_html").value = (mode === 'html')? 'Y' : 'N'; //Update the hidden field
        document.getElementById(mode+"_mode").style.display = "block"; // Show the other div.
    }
}

var MyAjax = { };
MyAjax.Autocompleter = Class.create(Ajax.Autocompleter, {
    updateChoices: function(choices) {
        if(!this.changed && this.hasFocus) {
            this.update.innerHTML = choices;
            Element.cleanWhitespace(this.update);
            Element.cleanWhitespace(this.update.down());
            if(this.update.firstChild && this.update.down().childNodes) {
                this.entryCount = this.update.down().childNodes.length;
                for (var i = 0; i < this.entryCount; i++) {
                    var entry = this.getEntry(i);
                    entry.autocompleteIndex = i;
                    this.addObservers(entry);
                }
            } else {
                this.entryCount = 0;
            }
            this.stopIndicator();
            this.index = -1;

            if(this.entryCount==1 && this.options.autoSelect) {
                this.selectEntry();
                this.hide();
            } else {
                this.render();
            }
        }
    }
});

function clickAttachments(id){
    $("join_file_"+id).click();
}
function clickAttachmentsNotes(id){
    $("note_"+id).click();
}