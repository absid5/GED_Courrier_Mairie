function checkGroup(current_form_id, url_check, url_manage, url_display_access, protect_string)
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
				manageGroup(url_manage,url_display_access, frm_values);
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

function doActionGroup( url_action, url_display_access)
{
	var groups = document.getElementsByName('groups[]');
	var val = '';
	if(groups)
	{
		for(var i=0; i< groups.length;i++)
		{
			if(groups[i].checked == true)
			{
				val += groups[i].value+'#';	
			}		
		}
	}
	//val.substring(0, val.length -3);

	new Ajax.Request(url_action, 
	{
		    method:'post',
		    parameters: { 
							usergroups : val
						},
		        onSuccess: function(answer){
				eval("response = "+answer.responseText);
			//	alert(answer.responseText);
				if(response.status == 0  )
				{
					updateContent(url_display_access, 'ugc');
				}
			}
		});
}

function manageGroup(url_manage, url_display_access, frm_values)
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
				updateContent(url_display_access, 'ugc');
				destroyModal('add_ugc');
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

function changePassword(url)
{
	new Ajax.Request(url,
	{
		method:'post',
		parameters: { 
					},
			onSuccess: function(answer){
			eval("response = "+answer.responseText);
		//	alert(answer.responseText);
			if(response.status == 0  )
			{
				destroyModal('pwd_changed');
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
