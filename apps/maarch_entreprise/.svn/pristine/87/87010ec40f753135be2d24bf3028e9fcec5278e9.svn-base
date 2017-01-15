function modifyAccess(url_modify)
{
    var accesses = document.getElementsByName('security[]');
    var val = '';
    if(accesses)
    {
        for(var i=0; i< accesses.length;i++)
        {
            if(accesses[i].checked == true)
            {
                val = '&val='+accesses[i].value;
                break;
            }
        }
    }
    displayModal(url_modify+val, 'add_grant', 850, 650);
}

function removeAccess( url_remove, url_display_access)
{
    var accesses = document.getElementsByName('security[]');
    var val = '';
    if(accesses)
    {
        for(var i=0; i< accesses.length;i++)
        {
            if(accesses[i].checked == true)
            {
                val += accesses[i].value+'#';
            }
        }
    }
    //val.substring(0, val.length -3);

    new Ajax.Request(url_remove,
    {
            method:'post',
            parameters: {
                            security : val
                        },
                onSuccess: function(answer){
                eval("response = "+answer.responseText);
            //  alert(answer.responseText);
                if(response.status == 0  )
                {
                    updateContent(url_display_access, 'access');
                }
            }
        });
}

function checkAccess(current_form_id, url_check, url_manage, url_display_access, protect_string)
{
    var frm_values = get_form_values(current_form_id);
    if(protect_string == false)
    {
        var protect = false;
    }
    else
    {
        var protect = true;
    }
  //  alert(frm_values);
    if(protect)
    {
        //frm_values = frm_values.replace(/\'/g, "\\'");
        frm_values = frm_values.replace(/\"/g, '\\"');
        frm_values = frm_values.replace(/\r\n/g, ' ');
        frm_values = frm_values.replace(/\r/g, ' ');
        frm_values = frm_values.replace(/\n/g, ' ');
    }
    new Ajax.Request(url_check,
    {
        method:'post',
        parameters: {
                      form_values : frm_values
                    },
            onSuccess: function(answer){
            eval("response = "+answer.responseText);
        //  alert(answer.responseText);
            if(response.status == 0  )
            {
                manageAccess(url_manage,url_display_access, frm_values);
            }
            else //  Form Params errors
            {
                //console.log(response.error_txt);
                try{
                        $('frm_error').innerHTML = response.error_txt;
                    }
                catch(e){}
            }
        }
    });
}

function manageAccess(url_manage, url_display_access, frm_values)
{
    new Ajax.Request(url_manage,
    {
        method:'post',
        parameters: {
                      form_values : frm_values
                    },
            onSuccess: function(answer){
            eval("response = "+answer.responseText);
        //  alert(answer.responseText);
            if(response.status == 0  )
            {
                updateContent(url_display_access, 'access');
                destroyModal('add_grant');
            }
            else
            {
                //console.log(response.error_txt);
                try{
                        $('frm_error').innerHTML = response.error_txt;
                    }
                catch(e){}
            }
        }
    });
}
