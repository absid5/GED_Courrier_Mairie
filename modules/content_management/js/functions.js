//load applet in a modal
function loadApplet(url, value)
{
    if (value != '') {
        //console.log('value : '+value);
        displayModal(url, 'CMApplet', 300, 300);
    }
}

//applet send a message (error) to Maarch
function sendAppletMsg(theMsg)
{
    //theMsg = 'erreur!';
    //console.log(window.opener.$('divErrorAttachment'));

    var error = /^ERREUR/.test(theMsg);
    if (window.opener.$('divErrorAttachment')) {
        if(error){
            window.opener.$('divErrorAttachment').innerHTML = theMsg;
            window.opener.$('divInfoAttachment').setStyle({display: 'none'});
            window.opener.$('divErrorAttachment').setStyle({display: 'inline'});
            window.close();
        }else{
            window.opener.$('divInfoAttachment').innerHTML = theMsg;
            window.opener.$('divInfoAttachment').setStyle({display: 'inline'});
            window.opener.$('divErrorAttachment').setStyle({display: 'none'});
        }
    }else{
        if(error){
            window.opener.$('main_info').setStyle({display: 'none'});
            window.opener.$('main_error').setStyle({display: 'inline'});
            window.opener.$('main_error').innerHTML = theMsg;
            window.close();
        }else{
            window.opener.$('main_info').setStyle({display: 'inline'});
            window.opener.$('main_error').setStyle({display: 'none'});
            window.opener.$('main_info').innerHTML = theMsg;
        }
    }
    //$('divError').innerHTML = theMsg;
    //$('divError').setStyle({display: 'inline'});
    //window.close();
    /*if (theMsg != '' && theMsg != ' ') {
        if (window.opener.$('divError')) {
            window.opener.$('divError').innerHTML = theMsg;
        } else if ($('divError')) {
            $('divError').innerHTML = theMsg;
        }
    }*/
}

//destroy the modal of the applet and launch an ajax script
function endOfApplet(objectType, theMsg)
{
    if (window.opener.$('add')) {
        window.opener.$('add').setStyle({display: 'inline'});
    } else if (window.opener.$('edit')) {
        window.opener.$('edit').setStyle({display: 'inline'});
    }

    //if (window.opener.$('edit') && objectType != 'transmission') {
    //    window.opener.$('edit').setStyle({display: 'inline'});
    //}

    $('divError').innerHTML = theMsg;
    if (objectType == 'template' || objectType == 'templateStyle' || objectType == 'attachmentVersion' || objectType == 'attachmentUpVersion' || objectType == 'transmission') {
        endTemplate();
    } else if (objectType == 'resource') {
        endResource();
    } else if (objectType == 'attachmentFromTemplate') {
        endAttachmentFromTemplate();
    } else if (objectType == 'attachment') {
        endAttachment();
    } else if (objectType == 'outgoingMail') {
        endAttachmentOutgoing();
    } 
    //destroyModal('CMApplet');
}

function endAttachmentFromTemplate()
{
    //window.alert('template ?');
    if(window.opener.$('list_attach')) {
        window.opener.$('list_attach').src = window.opener.$('list_attach').src;
    }
    window.close();
}

function endAttachment()
{
	if (window.opener.$('cur_idAffich')) var num_rep = window.opener.$('cur_idAffich').value;
	if (window.opener.$('cur_resId')) var res_id_master = window.opener.$('cur_resId').value;
	if (window.opener.$('cur_rep')){
		var oldRep = window.opener.$('cur_rep').value;
	}

	if(window.opener.$('viewframevalidRep'+num_rep+'_'+oldRep)) {
		//window.opener.$('viewframevalidRep'+num_rep).src = "index.php?display=true&module=visa&page=view_doc&path=last";		
		window.opener.$('viewframevalidRep'+num_rep+'_'+oldRep).src = "index.php?display=true&module=visa&page=view_pdf_attachement&res_id_master="+res_id_master+"&id=last";					
	}
    window.close();
}

function endTemplate()
{
    //window.alert('template ?');
    window.close();
}

function endAttachmentOutgoing()
{
    console.log('Fin nouveau spontane');
	
	
    window.close();
	
}

//reload the list div and the document if necessary
function endResource()
{
    //window.alert('resource ?');
    showDivEnd(
        'loadVersions',
        'nbVersions',
        'createVersion',
        'index.php?display=false&module=content_management&page=list_versions'
    );
    if (window.opener.$('viewframe')) {
        window.opener.$('viewframe').src = window.opener.$('viewframe').src;
    } else if (window.opener.$('viewframevalid')) {
        window.opener.$('viewframevalid').src = window.opener.$('viewframevalid').src;
    }
    
    //window.close();
}

function showDivEnd(divName, spanNb, divCreate, path_manage_script)
{
    new Ajax.Request(path_manage_script,
    {
        method:'post',
        parameters: {res_id : 'test'},
            onSuccess: function(answer){
            eval("response = "+answer.responseText);
            if(response.status == 0 || response.status == 1) {
                if(response.status == 0) {
                    window.opener.$(divName).innerHTML = response.list;
                    window.opener.$(spanNb).innerHTML = response.nb;
                    window.opener.$(divCreate).innerHTML = response.create;
                    window.close();
                } else {
                    window.opener.$(divName).innerHTML = 'error = 1 : ' . response.error_txt;
                }
            } else {
                window.opener.$(divName).innerHTML = 'error > 1 : ' . response.error_txt;
                try {
                    //window.opener.$(divName).innerHTML = response.error_txt;
                }
                catch(e){}
            }
        }
    });
}

function showDiv(divName, spanNb, divCreate, path_manage_script)
{
    new Ajax.Request(path_manage_script,
    {
        method:'post',
        parameters: {res_id : 'test'},
            onSuccess: function(answer){
            eval("response = "+answer.responseText);
            if(response.status == 0 || response.status == 1) {
                if(response.status == 0) {
                    if ($(divName)) {
                        $(divName).innerHTML = response.list;
                        $(spanNb).innerHTML = response.nb;
                        $(divCreate).innerHTML = response.create;
                    } else {
                        window.opener.$(divName).innerHTML = response.list;
                        window.opener.$(spanNb).innerHTML = response.nb;
                        window.opener.$(divCreate).innerHTML = response.create;
                    }
                } else {
                    //
                }
            } else {
                try {
                    //$(divName).innerHTML = response.error_txt;
                }
                catch(e){}
            }
        }
    });
}

function checkEditingDoc(userId) {
    var lck_name = "applet_"+userId;
    if($('add')){
        var target = $('add');
    }else{
        var target = $('edit');
    }
    var path_manage_script = "index.php?display=true&page=checkEditingDoc&module=content_management";
    new Ajax.Request(path_manage_script,
    {
        method:'post',
        parameters: {
            lck_name : lck_name},
            onSuccess: function(answer){
            eval("response = "+answer.responseText);
            if(response.status == 0) {
                console.log('no lck found!');
                target.removeAttribute('disabled');
                target.style.opacity='1';
                target.value='Valider';
            } else {
                console.log('lck found! Editing in progress !');
                target.disabled='disabled';
                target.style.opacity='0.5';
                target.value='Edition en cours ...';
            }
        }
    });

}
