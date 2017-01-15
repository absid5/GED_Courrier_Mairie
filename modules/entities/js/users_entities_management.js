function checkUserEntity(current_form_id, url_check, url_manage, url_display, protect_string)
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
	if(protect)
	{
		frm_values = frm_values.replace(/\'/g, "\\'");
		frm_values = frm_values.replace(/\"/g, '\\"');
		frm_values = frm_values.replace(/\r\n/g, ' ');
		frm_values = frm_values.replace(/\r/g, ' ');
		frm_values = frm_values.replace(/\n/g, ' ');
	}
	//alert(frm_values);
	new Ajax.Request(url_check,
	{
		method:'post',
		parameters: {
					  form_values : frm_values
					},
			onSuccess: function(answer){
			eval("response = "+answer.responseText);
		//	alert(answer.responseText);
			if(response.status == 0  )
			{
				manageUserEntity(url_manage,url_display, frm_values);
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

function doActionEntity( url_action, url_display)
{
	var ent = document.getElementsByName('entities[]');
	var val = '';
	if(ent)
	{
		for(var i=0; i< ent.length;i++)
		{
			if(ent[i].checked == true)
			{
				val += ent[i].value+'#';	
			}		
		}
	}

	new Ajax.Request(url_action, 
	{
		    method:'post',
		    parameters: { 
							entities : val
						},
		        onSuccess: function(answer){
				eval("response = "+answer.responseText);
			//	alert(answer.responseText);
				if(response.status == 0  )
				{
					updateContent(url_display, 'user_entities');
				}
			}
		});
}

function manageUserEntity(url_manage, url_display, frm_values)
{
	new Ajax.Request(url_manage,
	{
		method:'post',
		parameters: { 
					  form_values : frm_values
					},
			onSuccess: function(answer){
			eval("response = "+answer.responseText);
		//	alert(answer.responseText);
			if(response.status == 0  )
			{
				updateContent(url_display, 'user_entities');
				destroyModal('add_user_entities');
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
